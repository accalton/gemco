import axios from 'axios';
import { useEffect, useState } from 'react';
import Member from '../Interfaces/Member';
import { MemberFieldset } from './Components';
import { Submit } from '../Components/Forms';

interface MembershipFormState {
    id: number | null,
    members: Member[]
}

const MembershipForm = () => {
    const [formData, setFormData] = useState<MembershipFormState>({
        id: null,
        members: [],
    });

    useEffect(() => {
        axios.get('/api/memberships/1')
            .then(response => JSON.parse(response.data))
            .then(data => setFormData(data));
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
        let members = formData.members;
        members.splice(index, 1);
        setFormData({...formData, members});
    }

    const handleSubmit = async (event: React.SubmitEvent<HTMLFormElement>) => {
        event.preventDefault();
        axios.post('/api/memberships/post', formData)
            .then(response => console.log(response));
    }

    return (
        <form onSubmit={handleSubmit}>
            {formData.members.map((member, index) => (
                <MemberFieldset member={member} rowIndex={index} removeMember={removeMember} />
            ))}
            <button style={{
                border: '1px solid black',
                padding: '5px',
                margin: '0 10px'
            }} onClick={addMember}>+ Add</button>
            <Submit />
        </form>
    );
}

export default MembershipForm;
