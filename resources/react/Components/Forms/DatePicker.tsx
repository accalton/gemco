interface Props {
    autofocus?: boolean,
    label: string,
    name: string,
    onChange: Function,
    required?: boolean,
    rowIndex?: number,
    value?: string,
}

const DatePicker = ({ autofocus = false, label, name, onChange, required, rowIndex, value }: Props) => {
    const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        onChange(event, rowIndex)
    }

    return (
        <div className={'form-input'}>
            <label>
                {label}{required && (
                    <span className="required-input">*</span>
                )}:
                <input type="date" name={name} value={value} autoFocus={autofocus} onChange={handleChange} />
            </label>
        </div>
    );
}

export default DatePicker;
