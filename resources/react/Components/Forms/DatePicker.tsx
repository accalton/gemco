interface Props {
    autofocus?: boolean,
    label: string,
    name: string,
    onUpdate: Function,
    value?: string,
}

const DatePicker = ({ autofocus = false, label, name, onUpdate, value }: Props) => {
    const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        onUpdate(event.target.name, event.target.value);
    }

    return (
        <div className={'form-input'}>
            <label>
                {label}
                <input type="date" name={name} value={value} autoFocus={autofocus} onChange={handleChange} />
            </label>
        </div>
    );
}

export default DatePicker;
