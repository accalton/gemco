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

    return (
        <Fieldset>
            <div style={{
                flexGrow: 1,
                flexBasis: '100%',
                textAlign: 'right'
            }}>
                <button
                    onClick={
                        () => removeRow(rowIndex, member.membership_member.type)
                    }
                    style={{

                    }}
                >
                    Remove
                </button>
            </div>
            <TextInput name={'name'} label={'Full Name'} value={member.name} onUpdate={handleChange} />
            <DatePicker name={'date_of_birth'} label={'Date of Birth'} value={member.date_of_birth} onUpdate={handleChange} />
            <TextInput name={'email'} label={'Email Address'} value={member.email} onUpdate={handleChange} />
            <TextInput name={'phone'} label={'Phone Number'} value={member.phone} onUpdate={handleChange} />
        </Fieldset>
    );
}

export default MemberFieldset;
