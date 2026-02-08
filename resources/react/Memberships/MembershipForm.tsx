import axios from 'axios';
import { useEffect, useState } from 'react';
import Member from '../Interfaces/Member';
import { MemberFieldset } from './Components';
import { Fieldset, Repeater, SelectInput, Submit } from '../Components/Forms';

interface MembershipFormState {
    contacts: Member[],
    id: number | null,
    members: Member[],
    type: string,
}

const MembershipForm = () => {
    const [formData, setFormData] = useState<MembershipFormState>({
        contacts: [],
        id: null,
        members: [],
        type: ''
    });

    const [membersLimit, setMembersLimit] = useState<number | null>();

    const tooManyMembersAlert = 'Only "Family" memberships may have more than one member. Please delete any additional members before selecting a different type.';

    useEffect(() => {
        const membershipId = window.location.href.split('/').pop();
        if (membershipId) {
            axios.get('/api/memberships/' + membershipId)
                .then(response => setFormData(response.data));
        }
    }, []);

    useEffect(() => {
        if (formData.type !== 'family') {
            setMembersLimit(1);
        } else {
            setMembersLimit(null);
        }
    }, [formData.type]);

    const addRow = (type: 'contact' | 'member') => {
        let rowType = convertType(type);
        if (rowType) {
            const newRow: Member = {
                date_of_birth: '',
                email: '',
                membership_member: {
                    type
                },
                name: '',
                phone: '',
            }
    
            let rows = formData[rowType];
            rows.push(newRow);
            setFormData({...formData, [rowType]: rows});
        }
    }

    const convertType = (type: string): void | 'contacts' | 'members' => {
            switch (type) {
                case 'contact':
                    return 'contacts';
                case 'member':
                    return 'members';
                default:
                    return;
            }
    }

    const handleChangeRow = (type: 'contact' | 'member', index: number, field: string, value: string) => {
        let rowType = convertType(type);

        if (rowType) {
            let rows = formData[rowType];
            rows[index] = {...rows[index], [field]: value};
            setFormData({...formData, [rowType]: rows});
        }
    }

    const handleContactToMember = (index: number) => {
        if (membersLimit && formData.members.length >= membersLimit) {
            alert(tooManyMembersAlert);
            return;
        }

        convertRowType(index, 'member');
    }

    const handleMemberToContact = (index: number) => {
        convertRowType(index, 'contact');
    }

    const convertRowType = (index: number, newType: 'contact' | 'member') => {
        const contacts = formData.contacts;
        const members = formData.members;
        switch (newType) {
            case 'contact':
                const member = members[index];
                members.splice(index, 1);
                member.membership_member.type = newType;
                contacts.push(member);
                break;
            case 'member':
                const contact = contacts[index];
                contacts.splice(index, 1);
                contact.membership_member.type = newType;
                members.push(contact);
                break;
        }

        setFormData({...formData, contacts: contacts, members: members });
    }

    const handleSubmit = async (event: React.SubmitEvent<HTMLFormElement>) => {
        event.preventDefault();
        axios.post('/api/memberships/post', formData)
            .then(response => console.log(response));
    }

    const handleChangeType = (name: string, value: string) => {
        if (value !== 'family' && formData.members.length > 1) {
            alert(tooManyMembersAlert);
            return;
        }

        setFormData({...formData, type: value});
    }

    const removeRow = (index: number, type: 'contact' | 'member') => {
        let rowType: void | 'contacts' | 'members' = convertType(type);
        if (rowType) {
            if (confirm('Remove row? This cannot be undone without refreshing the page.')) {
                let rows = formData[rowType];
                rows.splice(index, 1);
                setFormData({...formData, [rowType]: rows});
            }
        }
    }

    return (
        <form onSubmit={handleSubmit}>
            <fieldset>
                <h2>Memberhip Details</h2>
                <SelectInput
                    label='Type'
                    name={'type'}
                    onChange={handleChangeType}
                    options={[
                        {
                            label: 'Please select . . .',
                            value: '',
                        },
                        {
                            label: 'Adult',
                            value: 'adult',
                        },
                        {
                            label: 'Conscession',
                            value: 'conscession',
                        },
                        {
                            label: 'Family',
                            value: 'family',
                        },
                        {
                            label: 'Youth',
                            value: 'youth',
                        }
                    ]}
                    value={formData.type}
                />
            </fieldset>

            <Repeater addRow={() => addRow('member')} addRowLabel={'+ Add a Member'} label={'Members'} limit={membersLimit}>
                {formData.members.map((member, index) => (
                    <MemberFieldset
                        changeType={handleMemberToContact}
                        key={index}
                        member={member}
                        onUpdate={handleChangeRow}
                        rowIndex={index}
                        removeRow={removeRow}
                    />
                ))}
            </Repeater>

            <Repeater addRow={() => addRow('contact')} addRowLabel={'+ Add a Contact'} label={'Contacts'}>
                {formData.contacts.map((contact, index) => (
                    <MemberFieldset
                        changeType={handleContactToMember}
                        key={index}
                        member={contact}
                        onUpdate={handleChangeRow}
                        rowIndex={index}
                        removeRow={removeRow}
                    />
                ))}
            </Repeater>

            <Submit label={'Save Membership'} />
        </form>
    );
}

export default MembershipForm;
