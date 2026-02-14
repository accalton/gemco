import { createContext, SetStateAction, useContext } from 'react';

interface ParentStateContextType {
    parentInitialized: boolean,
    parentState: {} | [] | null,
    setParentState: React.Dispatch<SetStateAction<{} | []>> | null,
}

export const ParentStateContext = createContext<ParentStateContextType | null>(null);

export const useParentStateContext = () => {
    const context = useContext(ParentStateContext);

    if (context === null) {
        throw new Error('No parent state context.');
    }

    return context;
}