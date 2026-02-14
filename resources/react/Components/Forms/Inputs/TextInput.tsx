import { useState } from 'react';
import Input from './Input';

interface Props {
    autofocus?: boolean,
    isValid?: Function,
    label: string,
    name: string,
    required?: boolean,
    value?: string
}

const TextInput = ({ autofocus = false, isValid = () => true, label, name, required, value }: Props) => {
    const [state, setState] = useState<string|undefined>();

    const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        if (isValid(event)) {
            setState(event.target.value);
        }
    }

    return (
        <Input name={name} state={state} setState={setState} value={value}>
            <div className={'form-input'}>
                <label>
                    {label}{required && (
                        <span className="required-input">*</span>
                    )}:
                    <input type="text" name={name} value={state} autoFocus={autofocus} onChange={handleChange} />
                </label>
            </div>
        </Input>
    );
}

export default TextInput;
