import { createRoot } from 'react-dom/client';
import MembershipForm from './Memberships/MembershipForm';

const node = document.getElementById('Memberships');
if (node) {
    const root = createRoot(node);
    root.render(<MembershipForm />);
}
