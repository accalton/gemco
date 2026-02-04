interface Props extends React.PropsWithChildren {
    handleSubmit: React.SubmitEventHandler,
}

const Form = ({ handleSubmit, children }: Props) => {
    const addRow = (type: string, row: any) => {
        console.log('Adding row');
    }

    const removeRow = (type: string, index: number) => {
        console.log('Removing row');
    }

    return (
        <form onSubmit={handleSubmit}>
            {children}
        </form>
    );
}

export default Form;
