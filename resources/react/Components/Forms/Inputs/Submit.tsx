interface Props {
    label?: string,
}
const Submit = ({ label }: Props) => {
    return <input type="submit" value={label ? label : 'Submit'} />
}

export default Submit;
