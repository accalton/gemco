<?php

namespace App\Livewire\Forms;

use App\Models\Member;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ContactForm extends Form
{
    #[Validate('required')]
    public string $name = '';

    #[Validate('required')]
    public string $phone = '';

    public string $email = '';
    public string $date_of_birth = '';

    public function setContact(?Member $contact)
    {
        if ($contact) {
            $this->name = $contact->name;
            $this->email = $contact->email;
            $this->phone = $contact->phone;
            $this->date_of_birth = $contact->date_of_birth;
        }
    }
}
