interface Props {
    autofocus?: boolean,
    label: string,
    name: string,
    update: Function,
    value?: string
}

const TextInput = ({ autofocus = false, label, name, update, value }: Props) => {
    const handleChange = (event: React.ChangeEvent<{ name: string, value: string }>) => {
        update(event.target.name, event.target.value);
    }

    return (
        <div className={'form-input'}>
            <label>
                {label}:
                <input type="text" name={name} value={value} autoFocus={autofocus} onChange={handleChange} />
            </label>
        </div>
    );
}

export default TextInput;
