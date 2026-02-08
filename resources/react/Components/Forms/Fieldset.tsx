interface Props extends React.PropsWithChildren {
    label?: string
}

const Fieldset = ({ children, label }: Props) => {
    return (
        <fieldset>
            {label && (
                <h2>{label}</h2>
            )}
            {children}
        </fieldset>
    );
}

export default Fieldset;
