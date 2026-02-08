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

    const handleSubmit = async (event: React.SubmitEvent<HTMLFormElement>) => {
        event.preventDefault();
        axios.post('/api/memberships/post', formData)
            .then(response => console.log(response));
    }

    const handleChangeType = (name: string, value: string) => {
        if (value !== 'family' && formData.members.length > 1) {
            alert('Only "Family" memberships may have more than one member. Please delete any additional members before selecting a different type.');
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

            <Repeater addRow={() => addRow('member')} addRowLabel={'Add Member'} label={'Members'} limit={membersLimit}>
                {formData.members.map((member, index) => (
                    <MemberFieldset
                        key={index}
                        member={member}
                        onUpdate={handleChangeRow}
                        rowIndex={index}
                        removeRow={removeRow}
                    />
                ))}
            </Repeater>

            <hr />

            <Repeater addRow={() => addRow('contact')} addRowLabel={'Add Contact'} label={'Contacts'} limit={2}>
                {formData.contacts.map((contact, index) => (
                    <MemberFieldset
                        key={index}
                        member={contact}
                        onUpdate={handleChangeRow}
                        rowIndex={index}
                        removeRow={removeRow}
                    />
                ))}
            </Repeater>
            <Submit />
        </form>
    );
}

export default MembershipForm;
