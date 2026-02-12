import axios from 'axios';
import { useAuthorizationContext } from './../../Contexts/AuthorizationContext';
import { useEffect, useState } from 'react';
import { useFormContext } from '../../Contexts/FormContext';

interface Props {
    apiUrl: string,
}

export const useForm = ({ apiUrl }: Props) => {
    const { apiToken, setApiToken } = useAuthorizationContext();

    const getApiToken = async () => {
        await axios.post('/create-token', {
                token_name: 'apiToken'
            }).then(response => setApiToken(response.data.token));
    }

    const getFormData = async () => {
        return await axios.get(apiUrl, {
            headers: {
                Authorization: `Bearer ${apiToken}`
            }
        })
        .then(response => response.data);
    }

    return {
        apiToken,
        getApiToken,
        getFormData,
        setApiToken,
    }
}

interface FormProps extends React.PropsWithChildren {
    apiUrl: string,
}

export const Form = ({ children, apiUrl }: FormProps) => {
    const [initialized, setInitialized] = useState<boolean>(false);

    const { apiToken } = useAuthorizationContext();
    const { formState, setFormState } = useFormContext();

    const { getApiToken, getFormData } = useForm({
        apiUrl
    });

    useEffect(() => {
        getApiToken();
    }, []);

    useEffect(() => {
        if (apiToken && !initialized) {
            setInitialized(true);
            getFormData().then(data => setFormState(data));
        }
    }, [apiToken]);

    useEffect(() => {
        console.log(formState);
    }, [formState]);

    const handleSubmit = (event: React.SubmitEvent<HTMLFormElement>) => {
        event.preventDefault();
        console.log(formState);
    }

    return (
        <form onSubmit={handleSubmit}>
            {children}
        </form>
    );
}
