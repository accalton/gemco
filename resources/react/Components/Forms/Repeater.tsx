import { ParentStateContext, useParentStateContext } from "@contexts/ParentStateContext";
import { useEffect, useState } from "react";
import Button from "./Button";

interface Props extends React.PropsWithChildren {
    addRow?: Function,
    index: string | false,
    initialState: any,
    label?: string,
}

const Repeater = ({ addRow, children, initialState, index = false, label }: Props) => {
    const [state, setState] = useState<Array<any>>();
    const { parentInitialized, parentState, setParentState } = useParentStateContext();

    useEffect(() => {
        if (state) {
            let newState = parentState;
            if (index !== false) {
                newState[index] = state;
            } else {
                newState = state;
            }

            setParentState(newState);
        }
    }, [state]);

    useEffect(() => {
        if (parentInitialized) {
            setState(initialState);
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
                    <Button
                        label={'Add row'}
                        onClick={handleAddRow}
                    />
                </div>
            </fieldset>
        </ParentStateContext.Provider>
    );
}

export default Repeater;
