interface Props extends React.PropsWithChildren {
    addRow: React.MouseEventHandler,
}

const Repeater = ({ addRow, children }: Props) => {
    return (
        <div>
            {children}
            <button onClick={addRow}>Add Row</button>
        </div>
    );
}

export default Repeater;
