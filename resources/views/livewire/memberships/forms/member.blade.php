<fieldset>
    @if($title)
        <h2>{{ $title }}</h2>
    @endif
    <label>
        Name:
        <input type="text" wire:model="form.name" />
        @error('form.name')
            <span class="error">{{ $message }}</span>
        @enderror
    </label>
    <label>
        Email:
        <input type="email" wire:model="form.email" />
        @error('form.email')
            <span class="error">{{ $message }}</span>
        @enderror
    </label>
    <label>
        Phone:
        <input type="tel" wire:model="form.phone" />
        @error('form.phone')
            <span class="error">{{ $message }}</span>
        @enderror
    </label>
    <label>
        Date of Birth:
        <input type="date" wire:model="form.date_of_birth" />
        @error('form.date_of_birth')
            <span class="error">{{ $message }}</span>
        @enderror
    </label>
</fieldset>
