<?php

namespace App\Livewire\Forms;

use App\Models\Membership;
use Livewire\Attributes\Validate;
use Livewire\Form;

class MembershipForm extends Form
{
    #[Validate('required')]
    public ?string $type = '';

    public function setMembership(Membership $membership)
    {
        $this->type = $membership->type;
    }
}
