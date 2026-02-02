interface Props extends React.PropsWithChildren {

}

const Fieldset = ({ children }: Props) => {
    return (
        <fieldset>
            {children}
        </fieldset>
    );
}

export default Fieldset;
