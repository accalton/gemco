import axios from 'axios';
import { useEffect, useState } from 'react';
import Member from '../Interfaces/Member';
import { MemberFieldset } from './Components';
import { Repeater, Submit } from '../Components/Forms';

interface MembershipFormState {
    id: number | null,
    members: Member[],
    type: string,
}

const MembershipForm = () => {
    const [formData, setFormData] = useState<MembershipFormState>({
        id: null,
        members: [],
        type: ''
    });

    useEffect(() => {
        const membershipId = window.location.href.split('/').pop();
        if (membershipId) {
            axios.get('/api/memberships/' + membershipId)
                .then(response => JSON.parse(response.data))
                .then(data => setFormData(data));
        }
    }, []);

    const addMember = () => {
        const newMember: Member = {
            name: '',
            date_of_birth: '',
            email: '',
            phone: ''
        };

        let members = formData.members;
        members.push(newMember);
        setFormData({...formData, members});
    }

    const removeMember = (index: number) => {
        if (confirm('Remove member? This cannot be undone without refreshing the page.')) {
            let members = formData.members;
            members.splice(index, 1);
            setFormData({...formData, members});
        }
    }

    const handleChangeMember = (index: number, field: string, value: string) => {
        let members = formData.members;
        members[index] = {...members[index], [field]: value}
        setFormData({...formData, members});
    }

    const handleSubmit = async (event: React.SubmitEvent<HTMLFormElement>) => {
        event.preventDefault();
        axios.post('/api/memberships/post', formData)
            .then(response => console.log(response));
    }

    return (
        <form onSubmit={handleSubmit}>
            <Repeater addRow={addMember}>
                <select onChange={(event) => setFormData({...formData, type: event.target.value})} value={formData.type}>
                    <option>Please select</option>
                    <option value={'adult'}>Adult</option>
                    <option value={'conscession'}>Conscession</option>
                    <option value={'family'}>Family</option>
                    <option value={'youth'}>Youth</option>
                </select>
                {formData.members.map((member, index) => (
                    <MemberFieldset member={member} onUpdate={handleChangeMember} rowIndex={index} removeMember={removeMember} />
                ))}
            </Repeater>
            <Submit />
        </form>
    );
}

export default MembershipForm;
