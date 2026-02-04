import { Fieldset, TextInput } from '../../Components/Forms';
import Member from '../../Interfaces/Member';

interface Props {
    member: Member,
    onUpdate: Function,
    removeMember: Function,
    rowIndex: number,
}

const MemberFieldset = ({ member, onUpdate, removeMember, rowIndex }: Props) => {
    const handleChange = (field: string, value: string) => {
        onUpdate(rowIndex, field, value);
    }

    return (
        <Fieldset>
            <button onClick={() => removeMember(rowIndex)}>Remove</button>
            <TextInput name={'name'} label={'Full Name'} value={member.name} onUpdate={handleChange} />
            <TextInput name={'date_of_birth'} label={'Date of Birth'} value={member.date_of_birth} onUpdate={handleChange} />
            <TextInput name={'email'} label={'Email Address'} value={member.email} onUpdate={handleChange} />
            <TextInput name={'phone'} label={'Phone Number'} value={member.phone} onUpdate={handleChange} />
        </Fieldset>
    );
}

export default MemberFieldset;
