import axios from 'axios';
import { useAuthorizationContext } from '@contexts/AuthorizationContext';
import { SetStateAction, useEffect, useState } from 'react';
import { ParentStateContext } from '@contexts/ParentStateContext';

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
    state: {},
    setState: React.Dispatch<SetStateAction<{}>>
}

export const Form = ({ children, apiUrl, state, setState }: FormProps) => {
    const [initialized, setInitialized] = useState(false);
    const { apiToken } = useAuthorizationContext();

    const { getApiToken, getFormData } = useForm({
        apiUrl
    });

    useEffect(() => {
        getApiToken();
    }, []);

    useEffect(() => {
        if (apiToken && !initialized) {
            getFormData()
                .then(data => setState(data))
                .then(() => setInitialized(true));
        }
    }, [apiToken]);

    const handleSubmit = (event: React.SubmitEvent<HTMLFormElement>) => {
        event.preventDefault();
        console.log(state);
    }

    return (
        <ParentStateContext.Provider value={{ parentInitialized: initialized, parentState: state, setParentState: setState }}>
            <form onSubmit={handleSubmit}>
                {children}
            </form>
        </ParentStateContext.Provider>
    );
}
