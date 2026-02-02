interface Props extends React.PropsWithChildren {
    handleSubmit: React.SubmitEventHandler,
}

const Form = ({ handleSubmit, children }: Props) => {
    return (
        <form onSubmit={handleSubmit}>
            {children}
        </form>
    );
}

export default Form;
