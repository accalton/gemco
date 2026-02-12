import { useEffect, useState } from "react";
import { useRepeaterRowContext } from "../../Contexts/RepeaterRowContext";

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
    const [state, setState] = useState<string|undefined>();

    const repeaterRowContext = useRepeaterRowContext();
    const { rowState, setRowState } = repeaterRowContext ? repeaterRowContext : { rowState: {}, setRowState() {} }

    useEffect(() => {
        setState(value);
    }, [value]);

    useEffect(() => {
        setRowState({...rowState, [name]: state});
    }, [state]);

    const handleChange = (event: React.ChangeEvent<HTMLSelectElement>) => {
        if (isValid(event.target.value)) {
            setState(event.target.value);
        }
    }

    return (
        <div className={'form-input'}>
            <label>
                {label}{required && (
                    <span className="required-input">*</span>
                )}:
                <select name={name} value={state} onChange={handleChange}>
                    {options.map((option, index) => (
                        <option key={index} value={option.value}>{option.label}</option>
                    ))}
                </select>
            </label>
        </div>
    );
}

export default SelectInput;
