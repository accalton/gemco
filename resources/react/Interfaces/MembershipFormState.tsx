import { Member } from './';

interface MembershipFormState {
    contacts: Member[],
    id: number | null,
    members: Member[],
    type: string,
}

export default MembershipFormState;
