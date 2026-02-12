import { createContext, SetStateAction, useContext } from 'react';

interface RepeaterRowContextType {
    rowState: any,
    setRowState: React.Dispatch<SetStateAction<any>>
}

export const RepeaterRowContext = createContext<RepeaterRowContextType | null>(null);

export const useRepeaterRowContext = () => {
    const context = useContext(RepeaterRowContext);

    return context;
}