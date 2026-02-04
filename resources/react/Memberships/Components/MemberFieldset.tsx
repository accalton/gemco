import { Fieldset, TextInput } from '../../Components/Forms';
import Member from '../../Interfaces/Member';

interface Props {
    member: Member,
    removeMember: Function,
    rowIndex: number,
}

const MemberFieldset = ({ member, removeMember, rowIndex }: Props) => {
    return (
        <Fieldset>
            <button onClick={() => removeMember(rowIndex)}>Remove</button>
            <TextInput name={'name'} label={'Full Name'} value={member.name} />
            <TextInput name={'date_of_birth'} label={'Date of Birth'} value={member.date_of_birth} />
            <TextInput name={'email'} label={'Email Address'} value={member.email} />
            <TextInput name={'phone'} label={'Phone Number'} value={member.phone} />
        </Fieldset>
    );
}

export default MemberFieldset;
