import { useParentStateContext } from "@contexts/ParentStateContext";
import { SetStateAction, useEffect, useState } from "react";

interface Props extends React.PropsWithChildren {
    name: string,
    state: any,
    setState: React.Dispatch<SetStateAction<any>>,
    value: any,
}

const Input = ({ children, name, state, setState, value }: Props) => {
    const { parentInitialized, parentState, setParentState } = useParentStateContext();

    useEffect(() => {
        setState(value);
    }, [value]);

    useEffect(() => {
        if (parentInitialized) {
            setParentState({...parentState, [name]: state});
        }
    }, [state]);

    return (
        <>
            {children}
        </>
    )
}

export default Input;
