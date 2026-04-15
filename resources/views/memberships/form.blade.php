@vite(['resources/scss/app.scss', 'resources/js/app.js'])

<form method="POST" action="/memberships/create">
    @csrf

    <fieldset>
        <h2>Membership</h2>
        <label>
            Type:
            <select name="type">
                <option value="">Select one</option>
                @foreach(\App\Models\Membership::TYPES as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
            @error('type')
                <span class="error">{{ $message }}</span>
            @enderror
        </label>
    </fieldset>

    <fieldset>
        <h2>Member Details</h2>
        <label>
            Name: <span class="required">*</span>
            <input name="members[0][name]" type="text" />
            @error('members.0.name')
                <span class="error">{{ $message }}</span>
            @enderror
        </label>

        <label>
            Date of Birth: <span class="required">*</span>
            <input name="members[0][date_of_birth]" type="date" />
            @error('members.0.date_of_birth')
                <span class="error">{{ $message }}</span>
            @enderror
        </label>
        
        <label>
            Email Address: <span class="required">*</span>
            <input name="members[0][email]" type="email" />
            @error('members.0.email')
                <span class="error">{{ $message }}</span>
            @enderror
        </label>

        <label>
            Phone Number: <span class="required">*</span>
            <input name="members[0][phone]" type="tel" />
            @error('members.0.phone')
                <span class="error">{{ $message }}</span>
            @enderror
        </label>
    </fieldset>

    <fieldset>
        <h2>Contacts</h2>
        <label>
            Name: <span class="required">*</span>
            <input name="contacts[0][name]" type="text" />
            @error('contacts.0.name')
                <span class="error">{{ $message }}</span>
            @enderror
        </label>

        <label>
            Phone Number: <span class="required">*</span>
            <input name="contacts[0][phone]" type="tel" />
            @error('contacts.0.phone')
                <span class="error">{{ $message }}</span>
            @enderror
        </label>

        <label>
            Email Address:
            <input name="contacts[0][email]" type="email" />
            @error('contacts.0.email')
                <span class="error">{{ $message }}</span>
            @enderror
        </label>
    </fieldset>
    
    <input type="submit" />
</form>