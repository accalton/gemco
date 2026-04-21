<?php

namespace App\Livewire;

use Livewire\Component;

class CreateMembership extends Component
{
    public array $contacts = [];
    public array $member = [];
    public array $members = [];
    public string $type = '';

    public function addContact()
    {
        $this->contacts[] = [];
    }

    public function addMember()
    {
        $this->members[] = [];
    }

    public function render()
    {
        return view('livewire.create-membership');
    }
    
    public function rules()
    {
        return [
            'type' => 'required',

            'member.date_of_birth' => 'required',
            'member.email'         => 'required',
            'member.name'          => 'required',
            'member.phone'         => 'required',

            'contacts.*.name'  => 'required',
            'contacts.*.phone' => 'required',

            'members.*.date_of_birth' => 'required',
            'members.*.email'         => 'required',
            'members.*.name'          => 'required',
            'members.*.phone'         => 'required',
        ];
    }

    public function save()
    {
        $this->validate();
    }
}
