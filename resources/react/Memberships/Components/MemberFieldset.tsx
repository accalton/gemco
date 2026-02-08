import { DatePicker, Fieldset, TextInput } from '../../Components/Forms';
import Member from '../../Interfaces/Member';

interface Props {
    member: Member,
    onUpdate: Function,
    removeRow: Function,
    rowIndex: number,
}

const MemberFieldset = ({ member, onUpdate, removeRow, rowIndex }: Props) => {
    const handleChange = (field: string, value: string) => {
        onUpdate(member.membership_member.type, rowIndex, field, value);
    }

    const handleRemoveRow = (event: React.MouseEvent) => {
        event.preventDefault();
        removeRow(rowIndex, member.membership_member.type);
    }

    return (
        <Fieldset>
            <div style={{
                flexGrow: 1,
                flexBasis: '100%',
                textAlign: 'right'
            }}>
                <button onClick={handleRemoveRow}>
                    Remove
                </button>
            </div>
            <TextInput name={'name'} label={'Full Name'} value={member.name} onChange={handleChange} />
            <DatePicker name={'date_of_birth'} label={'Date of Birth'} value={member.date_of_birth} onChange={handleChange} />
            <TextInput name={'email'} label={'Email Address'} value={member.email} onChange={handleChange} />
            <TextInput name={'phone'} label={'Phone Number'} value={member.phone} onChange={handleChange} />
        </Fieldset>
    );
}

export default MemberFieldset;
