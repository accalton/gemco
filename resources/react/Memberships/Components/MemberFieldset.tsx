import { DatePicker, Fieldset, TextInput } from '../../Components/Forms';
import Member from '../../Interfaces/Member';

interface Props {
    changeType: Function,
    member: Member,
    onUpdate: Function,
    removeRow: Function,
    rowIndex: number,
}

const MemberFieldset = ({ changeType, member, onUpdate, removeRow, rowIndex }: Props) => {
    const handleChange = (field: string, value: string) => {
        onUpdate(member.membership_member.type, rowIndex, field, value);
    }

    const handleChangeType = (event: React.MouseEvent) => {
        event.preventDefault();
        changeType(rowIndex, member.membership_member.type);
    }

    const handleRemoveRow = (event: React.MouseEvent) => {
        event.preventDefault();
        removeRow(rowIndex, member.membership_member.type);
    }

    return (
        <Fieldset>
            <div className="button-controls">
                <button onClick={handleChangeType}>
                    Change to {member.membership_member.type === 'contact' ? 'Member' : 'Contact'}
                </button>
                <button onClick={handleRemoveRow}>
                    Remove
                </button>
            </div>
            <TextInput
                name={'name'}
                label={'Full Name'}
                value={member.name}
                onChange={handleChange}
                required={true}
            />
            <DatePicker
                name={'date_of_birth'}
                label={'Date of Birth'}
                value={member.date_of_birth}
                onChange={handleChange}
                required={member.membership_member.type === 'member' ? true : false}
            />
            <TextInput
                name={'email'}
                label={'Email Address'}
                value={member.email}
                onChange={handleChange}
                required={true}
            />
            <TextInput
                name={'phone'}
                label={'Phone Number'}
                value={member.phone}
                onChange={handleChange}
                required={true}
            />
        </Fieldset>
    );
}

export default MemberFieldset;
