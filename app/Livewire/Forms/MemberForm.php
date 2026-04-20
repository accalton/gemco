<?php

namespace App\Livewire\Forms;

use App\Models\Member;
use Livewire\Attributes\Validate;
use Livewire\Form;

class MemberForm extends Form
{
    #[Validate('required')]
    public string $name = '';

    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required')]
    public string $phone = '';

    #[Validate('required')]
    public string $date_of_birth = '';

    public function setMember(?Member $member)
    {
        if ($member) {
            $this->name = $member->name;
            $this->email = $member->email;
            $this->phone = $member->phone;
            $this->date_of_birth = $member->date_of_birth;
        }
    }
}
