import { useState } from 'react';
import { createRoot } from 'react-dom/client';
import MembershipForm from './Memberships/MembershipForm';
import { AuthorizationContext } from './Contexts/AuthorizationContext';

const App = ({ children }: React.PropsWithChildren) => {
    const [apiToken, setApiToken] = useState<string>('');

    return (
        <AuthorizationContext.Provider value={{ apiToken, setApiToken }}>
            {children}
        </AuthorizationContext.Provider>
    );
}

const node = document.getElementById('Memberships');
if (node) {
    const root = createRoot(node);
    root.render(
        <App>
            <MembershipForm />
        </App>
    );
}
