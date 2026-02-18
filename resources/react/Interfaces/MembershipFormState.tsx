import { Member } from './';
import Address from './Address';

interface MembershipFormState {
    address: Address,
    contacts: Member[],
    id: number | null,
    member: Member,
    members: Member[],
    type: string,
}

export default MembershipFormState;
