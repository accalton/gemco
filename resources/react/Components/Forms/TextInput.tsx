import { useEffect, useState } from "react";
import { useRepeaterRowContext } from "../../Contexts/RepeaterRowContext";

interface Props {
    autofocus?: boolean,
    isValid: Function,
    label: string,
    name: string,
    required?: boolean,
    rowIndex?: number,
    value?: string
}

const TextInput = ({ autofocus = false, isValid = () => true, label, name, required, value }: Props) => {
    const [state, setState] = useState<string|undefined>();

    const repeaterRowContext = useRepeaterRowContext();
    const { rowState, setRowState } = repeaterRowContext ? repeaterRowContext : { rowState: {}, setRowState() {} }

    useEffect(() => {
        setState(value);
    }, [value]);

    useEffect(() => {
        if (isValid()) {
            setRowState({...rowState, [name]: state});
        }
    }, [state]);

    const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        if (isValid(event)) {
            setState(event.target.value);
        }
    }

    return (
        <div className={'form-input'}>
            <label>
                {label}{required && (
                    <span className="required-input">*</span>
                )}:
                <input type="text" name={name} value={state} autoFocus={autofocus} onChange={handleChange} />
            </label>
        </div>
    );
}

export default TextInput;
