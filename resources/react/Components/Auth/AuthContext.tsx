import { createContext, useState, SetStateAction, useContext } from 'react';

interface AuthContextType {
    apiToken: string,
    setApiToken: React.Dispatch<SetStateAction<string>>,
}

export const AuthContext = createContext<AuthContextType | null>(null);

export const useAuthContext = () => {
    const context = useContext(AuthContext);

    if (context === null) {
        throw new Error('Context is fucked');
    }

    return context;
}