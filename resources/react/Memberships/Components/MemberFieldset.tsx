import { Button, DatePicker, Fieldset, Repeater, RepeaterRow, TextInput } from '../../Components/Forms';
import { useFormContext } from '../../Contexts/FormContext';
import { Member } from '../../Interfaces';

const MemberFieldset = () => {
    const { formState, setFormState } = useFormContext();

    const addRow = () => {
        const members = formState.members;
        const newMember: Member = {
            membership_member: {
                type: 'member'
            },
            name: '',
        }

        members.push(newMember);
        setFormState({...formState, members});
    }

    const removeRow = (index: number) => {
        if (confirm('Remove row? This cannot be undone without refreshing the page.')) {
            const members = formState.members;
            members.splice(index, 1);
            setFormState({...formState, members});
        }
    }

    const handleChangeValue = (event: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>, index: number) => {
        const members = formState.members;
        const name = event.target.name;
        const value = event.target.value;
        
        members[index] = {...members[index], [name]: value};
        setFormState({...formState, members});
    }

    return (
        <Fieldset label={'Members'}>
            <Repeater onAddRow={addRow}>
                {formState.members.map((member: Member, index: number) => (
                    <RepeaterRow index={index} key={index} model={'members'}>
                        <div className="button-controls">
                            <Button
                                label={'Remove'}
                                onClick={() => removeRow(index)}
                            />
                        </div>
                        <TextInput
                            label={'Full Name'}
                            name={'name'}
                            required={true}
                            value={member.name}
                        />
                        <DatePicker
                            name={'date_of_birth'}
                            label={'Date of Birth'}
                            value={member.date_of_birth}
                            onChange={handleChangeValue}
                            required={member.membership_member.type === 'member' ? true : false}
                        />
                        <TextInput
                            name={'email'}
                            label={'Email Address'}
                            value={member.email}
                            required={true}
                        />
                        <TextInput
                            name={'phone'}
                            label={'Phone Number'}
                            value={member.phone}
                            required={true}
                        />
                    </RepeaterRow>
                ))}
            </Repeater>
        </Fieldset>
    );
}

export default MemberFieldset;
