interface Member {
    date_of_birth?: string,
    email?: string,
    membership_member: MembershipMember,
    name: string,
    phone?: string,
}

interface MembershipMember {
    type: 'contact' | 'member',
}

export default Member;
