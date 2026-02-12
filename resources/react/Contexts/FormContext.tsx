import { createContext, SetStateAction, useContext } from 'react';
import { MembershipFormState } from '../Interfaces';

interface FormContextType {
    formState: MembershipFormState,
    setFormState: React.Dispatch<SetStateAction<MembershipFormState>>,
}

export const FormContext = createContext<FormContextType | null>(null);

export const useFormContext = () => {
    const context = useContext(FormContext);

    if (context === null) {
        throw new Error('No form data context.');
    }

    return context;
}
