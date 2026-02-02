interface Props {
    autofocus?: boolean,
    name: string,
    label: string
}

const TextInput = ({ autofocus = false, name, label }: Props) => {
    return (
        <div className={'form-input'}>
            <label>
                {label}:
                <input type="text" name={name} autoFocus={autofocus} />
            </label>
        </div>
    );
}

export default TextInput;
