import { createContext, SetStateAction, useContext } from 'react';

interface AuthorizationContextType {
    apiToken: string,
    setApiToken: React.Dispatch<SetStateAction<string>>,
}

export const AuthorizationContext = createContext<AuthorizationContextType | null>(null);

export const useAuthorizationContext = () => {
    const context = useContext(AuthorizationContext);

    if (context === null) {
        throw new Error('No authorization context.');
    }

    return context;
}