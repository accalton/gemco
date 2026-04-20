<?php

namespace App\Livewire\Memberships;

use App\Livewire\Forms\MemberForm;
use App\Livewire\Forms\MembershipForm;
use App\Models\Membership;
use Livewire\Component;

class Form extends Component
{
    public MembershipForm $form;
    public MemberForm $member;

    public Membership $membership;

    public function render()
    {
        return view('livewire.memberships.form');
    }

    public function mount(?Membership $membership): void
    {
        $this->membership = $membership;
        $this->form->setMembership($membership);
        $this->member->setMember($membership->member);
    }

    public function save()
    {
        $this->validate();

        $this->dispatch('validating-membership');
    }
}
