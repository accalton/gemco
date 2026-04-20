<?php

namespace App\Livewire\Memberships\Forms;

use App\Livewire\Forms\MemberForm;
use Livewire\Attributes\On;
use Livewire\Component;

class Member extends Component
{
    public MemberForm $form;
    public string $title = '';

    #[On('validating-membership')]
    public function onSaveMembership()
    {
        $this->validate();
    }

    public function mount($member)
    {
        $this->form->setMember($member);
    }

    public function render()
    {
        return view('livewire.memberships.forms.member');
    }
}
