import { Children } from 'react';

interface Props extends React.PropsWithChildren {
    addRow: React.MouseEventHandler,
    addRowLabel: string,
    label: string,
    limit?: number | null,
}

const Repeater = ({ addRow, addRowLabel, children, label, limit }: Props) => {
    const handleAddRow = (event: React.MouseEvent) => {
        event.preventDefault();

        if (limit) {
            const currentRows = Children.count(children);
            if (currentRows >= limit) {
                return;
            }
        }

        addRow(event);
    }

    const showAddRowButton = () => {
        if (limit) {
            const currentRows = Children.count(children);
            return currentRows < limit;
        } else {
            return true;
        }
    }

    return (
        <div className="repeater">
            <h2>{label}</h2>
            {children}
            {showAddRowButton() && (
                <div className="button-controls">
                    <button onClick={handleAddRow}>{addRowLabel}</button>
                </div>
            )}
        </div>
    );
}

export default Repeater;
