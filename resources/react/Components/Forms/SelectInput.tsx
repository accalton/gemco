interface Props {
    label: string,
    name: string,
    onChange: Function,
    options: SelectOption[],
    required?: boolean,
    value?: string
}

interface SelectOption {
    label: string,
    value: string,
}

const SelectInput = ({ label, name, onChange, options, required, value }: Props) => {
    const handleChange = (event: React.ChangeEvent<HTMLSelectElement>) => {
        onChange(event.target.name, event.target.value);
    }

    return (
        <div className={'form-input'}>
            <label>
                {label}{required && (
                    <span className="required-input">*</span>
                )}:
                <select name={name} value={value} onChange={handleChange}>
                    {options.map((option, index) => (
                        <option key={index} value={option.value}>{option.label}</option>
                    ))}
                </select>
            </label>
        </div>
    );
}

export default SelectInput;
