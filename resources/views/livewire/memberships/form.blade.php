<form wire:submit="save">
    <fieldset>
        <h2>Membership:</h2>
        <label>
            Type:
            <select wire:model.live="form.type">
                <option value="">Select one . . .</option>
                @foreach(\App\Models\Membership::TYPES as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
            @error('form.type')
                <span class="error">{{ $message }}</span>
            @enderror
        </label>
    </fieldset>

    <livewire:memberships.forms.member :member="$membership->member" :title="'Member Details'" />

    <fieldset>
        <h2>Additional Members</h2>
        @foreach($membership->members as $index => $member)
            <livewire:memberships.forms.member :member="$member" :key="$member->id" />
        @endforeach
    </fieldset>

    <button type="submit">Submit</button>
</form>