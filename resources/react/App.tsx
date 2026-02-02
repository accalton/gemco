import { createRoot } from 'react-dom/client';
import CreateMembership from './Memberships/Create';

const node = document.getElementById('Memberships');
if (node) {
    const root = createRoot(node);
    root.render(<CreateMembership />);
}
