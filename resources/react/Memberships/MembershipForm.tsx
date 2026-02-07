import axios from 'axios';
import { useEffect, useState } from 'react';
import Member from '../Interfaces/Member';
import { MemberFieldset } from './Components';
import { Repeater, Submit } from '../Components/Forms';

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

    useEffect(() => {
        const membershipId = window.location.href.split('/').pop();
        if (membershipId) {
            axios.get('/api/memberships/' + membershipId)
                .then(response => setFormData(response.data));
        }
    }, []);

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

    return (
        <form onSubmit={handleSubmit}>
            <select name={'type'} onChange={(event) => setFormData({...formData, type: event.target.value})} value={formData.type}>
                <option>Please select</option>
                <option value={'adult'}>Adult</option>
                <option value={'conscession'}>Conscession</option>
                <option value={'family'}>Family</option>
                <option value={'youth'}>Youth</option>
            </select>
            <Repeater addRow={() => addRow('member')}>
                {formData.members.map((member, index) => (
                    <MemberFieldset
                        member={member}
                        onUpdate={handleChangeRow}
                        rowIndex={index}
                        removeRow={removeRow}
                    />
                ))}
            </Repeater>

            <hr />

            <Repeater addRow={() => addRow('contact')}>
                {formData.contacts.map((contact, index) => (
                    <MemberFieldset
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
