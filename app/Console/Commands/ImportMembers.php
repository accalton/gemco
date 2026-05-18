<?php

namespace App\Console\Commands;

use App\Models\Address;
use App\Models\Identification;
use App\Models\Member;
use App\Models\MemberMembership;
use App\Models\Membership;
use Carbon\Carbon;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ImportMembers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-members';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $today;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->today = new DateTime();
        $path = Storage::path('gemco_members.xlsx');

        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadEmptyCells(false);

        $spreadsheet = $reader->load($path);

        // $this->importMembers($spreadsheet->getSheet(0));
        $this->importIdentifications($spreadsheet->getSheet(1));
    }

    /**
     * @param array $row
     *
     * @return ?Member
     */
    private function createMember(array $row): ?Member
    {
        $name = implode(' ', [
            $row[1], $row[0]
        ]);

        if (!trim($name)) {
            return null;
        }

        $dateOfBirth = DateTime::createFromFormat('d/m/Y H:i:s', $row[4] . '00:00:00');

        $data = [
            'name' => implode(' ', [
                $row[1],
                $row[0]
            ]),
            'phone' => $row[2],
            'email' => $row[5],
            'date_of_birth' => $dateOfBirth ?: null,
        ];

        $member = Member::firstOrCreate($data);

        return $member;
    }

    /**
     * @param array $row
     * @param Member $member
     * @param ?Address $address
     *
     * @return Membership
     */
    private function createMembership(array $row, Member $member, ?Address $address): Membership
    {
        $expiryDate = $this->getExpiry($row);
        if ($expiryDate && $expiryDate < $this->today) {
            $status = Membership::STATUS_EXPIRED;
        } else {
            $status = Membership::STATUS_ACTIVE;
        }

        $membership = Membership::firstOrCreate([
            'address_id' => $address->id ?? null,
            'expiry'     => $expiryDate,
            'member_id'  => $member->id,
            'status'     => $status,
            'type'       => $this->getType($row),
        ]);

        return $membership;
    }

    /**
     * @param array $row
     * @param int $index
     *
     * @return ?DateTime
     */
    private function getExpiry(array $row, int $index = 8): ?DateTime
    {
        $expiryDate = DateTime::createFromFormat('d/m/Y H:i:s', $row[$index] . '00:00:00');
        if ($expiryDate) {
            $expiryDate->modify('+1 year');
        } else {
            $expiryDate = null;
        }

        return $expiryDate;
    }

    /**
     * @param array $row
     *
     * @return string
     */
    private function getType(array $row): string
    {
        $type = strtolower($row[6]);
        $youthTypes = [
            'family (youth)',
            'associate youth'
        ];
        if (in_array($type, $youthTypes)) {
            $type = 'family';
        }

        return $type;
    }

    /**
     * @param Worksheet $sheet
     */
    private function importIdentifications(Worksheet $sheet): void
    {
        $index = 0;
        foreach ($sheet->toArray() as $row) {
            if ($index++ === 0) {
                continue;
            }

            $name = implode(' ', [
                $row[1], $row[0]
            ]);

            $member = Member::where('name', $name)->get();

            if ($member->count() === 1) {
                Identification::firstOrCreate([
                    'expiry'    => $this->getExpiry($row, 3),
                    'member_id' => $member[0]->id,
                    'number'    => $row[2],
                    'type'      => Identification::TYPE_WORKING_WITH_CHILDREN,
                ]);
            }
        }
    }

    /**
     * @param Worksheet $worksheet
     *
     * @return void
     */
    private function importMembers(Worksheet $sheet): void
    {
        $index = 0;
        $previousMembership = null;
        foreach ($sheet->toArray() as $row) {
            if ($index++ === 0) {
                continue;
            }

            $row = array_map('trim', $row);

            if (!$member = $this->createMember($row)) {
                continue;
            }

            $address = $this->verifyAddress($row);

            if (
                $previousMembership &&
                ($this->getType($row) === 'family') &&
                $this->isFamilyMember($member, $address, $row, $previousMembership)
            ) {
                continue;
            }

            $membership = $this->createMembership($row, $member, $address);

            $previousMembership = $membership;
        }
    }

    /**
     * @param Member $member
     * @param ?Address $address
     * @param array $row
     * @param Membership $membership
     *
     * @return bool
     */
    private function isFamilyMember(Member $member, ?Address $address, array $row, Membership $membership): bool
    {
        $isFamilyMember = false;

        if ($address && ($address->id === $membership->address_id)) {
            $isFamilyMember = true;
        }

        if ($membership->expiry === $this->getExpiry($row)) {
            $memberName = $membership->member->name;

            $memberName = explode(' ', $memberName);
            $surname = array_pop($memberName);

            if ($surname === $row[0]) {
                $isFamilyMember = true;
            }
        }

        if ($isFamilyMember) {
            $membership->members()->attach($member->id, [
                'type' => MemberMembership::TYPE_MEMBER
            ]);
        }

        return $isFamilyMember;
    }

    /**
     * @param string $address
     *
     * @return Address
     */
    private function verifyAddress(array $row): ?Address
    {
        if (!$address = $row[3]) {
            return null;
        }

        $address = array_map('trim', explode(',', $address));

        switch (count($address)) {
            case 4:
                $line1 = array_shift($address);
                $suburb = array_shift($address);
                $state = array_shift($address);
                $postcode = array_shift($address);
                break;
            case 3:
                $line1 = array_shift($address);
                $suburb = array_shift($address);
                $stateStart = stripos($address[0], 'vic');
                $postcode = trim(substr($address[0], $stateStart + 3));
                break;
            case 2:
                $line1 = array_shift($address);
                $stateStart = stripos($address[0], 'vic');
                $suburb = trim(substr($address[0], 0, $stateStart));
                $postcode = trim(substr($address[0], $stateStart + 3));
                break;
            default:
                if (is_numeric($address[0])) {
                    $address = Address::create([
                        'postcode' => $address[0]
                    ]);

                    return $address;
                }

                return null;
        }

        $address = Address::firstOrCreate([
            'line1'    => $line1,
            'suburb'   => $suburb,
            'state'    => 'VIC',
            'postcode' => $postcode,
        ]);

        return $address;
    }
}
