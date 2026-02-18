interface Member {
    date_of_birth?: string,
    email?: string,
    id?: number,
    member_membership: {
        relationship?: string,
    },
    name?: string,
    phone?: string,
}

export default Member;
