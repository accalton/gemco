import { createContext, SetStateAction, useContext } from 'react';
import { MembershipFormState } from '../Interfaces';

interface FormDataContextType {
    formData: MembershipFormState,
    setFormData: React.Dispatch<SetStateAction<MembershipFormState>>,
}

export const FormDataContext = createContext<FormDataContextType | null>(null);

export const useFormDataContext = () => {
    const context = useContext(FormDataContext);

    if (context === null) {
        throw new Error('No form data context.');
    }

    return context;
}
