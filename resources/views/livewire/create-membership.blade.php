<div>
    <form wire:submit="save">
        @csrf

        <fieldset>
            <h2>Membership</h2>
            <label>
                Type:
                <select wire:model.live="type">
                    <option value="">Select one . . .</option>
                    @foreach(\App\Models\Membership::TYPES as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            @error('type')
                <span class="error">{{ $message }}</span>
            @enderror
        </fieldset>

        <fieldset>
            <h2>Member Details</h2>
            <label>
                Name:
                <input type="text" wire:model="member.name" />
            </label>
            @error('member.name')
                <span class="error">{{ $message }}</span>
            @enderror
            <label>
                Email Address:
                <input type="text" wire:model="member.email" />
            </label>
            @error('member.email')
                <span class="error">{{ $message }}</span>
            @enderror
            <label>
                Phone Number:
                <input type="text" wire:model="member.phone" />
            </label>
            @error('member.phone')
                <span class="error">{{ $message }}</span>
            @enderror
            <label>
                Date of Birth:
                <input type="text" wire:model="member.date_of_birth" />
            </label>
            @error('member.date_of_birth')
                <span class="error">{{ $message }}</span>
            @enderror
        </fieldset>

        @if($type === 'family')
            <fieldset>
                <h2>Additional Members</h2>
                @foreach($members as $index => $member)
                    <fieldset>
                        <label>
                            Name:
                            <input type="text" wire:model="members.{{ $index }}.name" />
                        </label>
                        @error('members.' . $index . '.name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                        <label>
                            Email Address:
                            <input type="text" wire:model="members.{{ $index }}.email" />
                        </label>
                        @error('members.' . $index . '.email')
                            <span class="error">{{ $message }}</span>
                        @enderror
                        <label>
                            Phone Number:
                            <input type="text" wire:model="members.{{ $index }}.phone" />
                        </label>
                        @error('members.' . $index . '.phone')
                            <span class="error">{{ $message }}</span>
                        @enderror
                        <label>
                            Date of Birth:
                            <input type="text" wire:model="members.{{ $index }}.date_of_birth" />
                        </label>
                        @error('members.' . $index . '.date_of_birth')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </fieldset>
                @endforeach

                <button type="button" wire:click="addMember">Add Member</button>
            </fieldset>
        @endif

        <fieldset>
            <h2>Contacts</h2>
            @foreach($contacts as $index => $contact)
                <fieldset>
                    <label>
                        Name:
                        <input type="text" wire:model="contacts.{{ $index }}.name" />
                    </label>
                    @error('contacts.' . $index . '.name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                    <label>
                        Phone Number:
                        <input type="text" wire:model="contacts.{{ $index }}.phone" />
                    </label>
                    @error('contacts.' . $index . '.phone')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </fieldset>
            @endforeach

            <button type="button" wire:click="addContact">Add Contact</button>
        </fieldset>

        <button type="submit">Submit</button>
    </form>
</div>
