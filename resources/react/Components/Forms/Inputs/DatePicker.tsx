import { useEffect, useState } from "react";
import Input from "./Input";

interface Props {
    autofocus?: boolean,
    label: string,
    name: string,
    required?: boolean,
    rowIndex?: number,
    value?: string,
}

const DatePicker = ({ autofocus = false, label, name, required, value }: Props) => {
    const [state, setState] = useState<string>();

    const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setState(event.target.value);
    }

    return (
        <Input name={name} state={state} setState={setState} value={value}>
            <div className={'form-input'}>
                <label>
                    {label}{required && (
                        <span className="required-input">*</span>
                    )}:
                    <input type="date" name={name} value={state} autoFocus={autofocus} onChange={handleChange} />
                </label>
            </div>
        </Input>
    );
}

export default DatePicker;
