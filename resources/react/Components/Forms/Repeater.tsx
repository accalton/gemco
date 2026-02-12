interface Props extends React.PropsWithChildren {
    label?: string,
    onAddRow: React.MouseEventHandler<HTMLButtonElement>,
}

const Repeater = ({ children, label, onAddRow }: Props) => {
    const handleAddRow = (event: React.MouseEvent<HTMLButtonElement>) => {
        event.preventDefault();
        onAddRow(event);
    }

    return (
        <div className="repeater">
            <h2>{label}</h2>
            {children}
            <button onClick={handleAddRow}>+ Add Row</button>
        </div>
    );
}

export default Repeater;
