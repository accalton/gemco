import { useFormDataContext } from "../../Contexts/FormDataContext";

interface Props {
    isValid?: Function,
    label: string,
    name: string,
    options: SelectOption[],
    required?: boolean,
    value?: string
}

interface SelectOption {
    label: string,
    value: string,
}

const SelectInput = ({ isValid = () => true, label, name, options, required, value }: Props) => {
    const { formData, setFormData } = useFormDataContext();

    const handleChange = (event: React.ChangeEvent<HTMLSelectElement>) => {
        if (isValid(event.target.value)) {
            setFormData({...formData, [event.target.name]: event.target.value});
        }
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
