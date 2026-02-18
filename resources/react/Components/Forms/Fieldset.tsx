import { ParentStateContext, useParentStateContext } from "@contexts/ParentStateContext";
import { useEffect, useState } from "react";

interface Props extends React.PropsWithChildren {
    label?: string
    initialState: any,
    parentKey?: string | number | false,
}

const Fieldset = ({ children, label, initialState, parentKey = false }: Props) => {
    const [state, setState] = useState();
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
        if (parentInitialized) {
            setState(initialState);
        }
    }, [initialState]);

    return (
        <ParentStateContext.Provider value={{ parentInitialized, parentState: state, setParentState: setState }}>
            <fieldset>
                {label && (
                    <h2>{label}</h2>
                )}
                {children}
            </fieldset>
        </ParentStateContext.Provider>
    );
}

export default Fieldset;
