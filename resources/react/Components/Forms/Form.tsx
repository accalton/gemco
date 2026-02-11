import axios from 'axios';
import { useAuthorizationContext } from './../../Contexts/AuthorizationContext';
import { useEffect, useState } from 'react';
import { useFormDataContext } from '../../Contexts/FormDataContext';

interface Props {
    apiUrl: string,
}

export const useForm = ({ apiUrl }: Props) => {
    const { apiToken, setApiToken } = useAuthorizationContext();
    const [initialLoad, setInitialLoad] = useState(false);

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

export const Form = ({ children }: React.PropsWithChildren) => {
    const [initialized, setInitialized] = useState<boolean>(false);

    const { apiToken } = useAuthorizationContext();
    const { formData, setFormData } = useFormDataContext();

    const { getApiToken, getFormData } = useForm({
        apiUrl: '/api/memberships/1'
    });

    useEffect(() => {
        getApiToken();
    }, []);

    useEffect(() => {
        if (apiToken && !initialized) {
            setInitialized(true);
            getFormData().then(data => setFormData(data));
        }
    }, [apiToken]);

    return (
        <form>
            {children}
        </form>
    );
}
