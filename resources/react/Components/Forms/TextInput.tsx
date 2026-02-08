interface Props {
    autofocus?: boolean,
    label: string,
    name: string,
    onChange: Function,
    required?: boolean,
    value?: string
}

const TextInput = ({ autofocus = false, label, name, onChange, required, value }: Props) => {
    const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        onChange(event.target.name, event.target.value);
    }

    return (
        <div className={'form-input'}>
            <label>
                {label}{required && (
                    <span className="required-input">*</span>
                )}:
                <input type="text" name={name} value={value} autoFocus={autofocus} onChange={handleChange} />
            </label>
        </div>
    );
}

export default TextInput;
