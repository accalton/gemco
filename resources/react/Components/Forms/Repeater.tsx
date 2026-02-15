import { ParentStateContext, useParentStateContext } from "@contexts/ParentStateContext";
import { useEffect, useState } from "react";
import Button from "./Button";

interface Props extends React.PropsWithChildren {
    addRow?: Function,
    initialState: any,
    label?: string,
    limit?: number | false,
    parentKey: string | false,
}

const Repeater = ({ addRow, children, initialState, label, limit, parentKey = false }: Props) => {
    const [canAddRow, setCanAddRow] = useState<boolean>(true);
    const [state, setState] = useState<Array<any>>();
    const { parentInitialized, parentState, setParentState } = useParentStateContext();

    useEffect(() => {
        if (state) {
            let newState = parentState;
            if (parentKey !== false) {
                newState[parentKey] = state;
            } else {
                newState = state;
            }

            setParentState(newState);
        }
    }, [state]);

    useEffect(() => {
        if (limit && state) {
            setCanAddRow(state.length < limit);
        } else if (state) {
            setCanAddRow(true);
        }
    }, [limit]);

    useEffect(() => {
        if (parentInitialized) {
            setState(initialState);

            if (limit) {
                setCanAddRow(initialState.length < limit);
            }
        }
    }, [initialState]);

    const handleAddRow = (event: React.MouseEvent<HTMLButtonElement>) => {
        event.preventDefault();

        addRow();
    }

    return (
        <ParentStateContext.Provider value={{ parentInitialized, parentState: state, setParentState: setState }}>
            <fieldset className="repeater">
                {label && (
                    <h2>{label}</h2>
                )}

                {children}

                <div className="button-controls">
                    {canAddRow && (
                        <Button
                            label={'Add row'}
                            onClick={handleAddRow}
                        />
                    )}
                </div>
            </fieldset>
        </ParentStateContext.Provider>
    );
}

export default Repeater;
