import Fieldset from '@components/Forms/Fieldset';
import { Form } from '@components/Forms/Form';
import DatePicker from '@components/Forms/Inputs/DatePicker';
import SelectInput from '@components/Forms/Inputs/SelectInput';
import { useEffect, useState } from 'react';
import { MembershipFormState } from '../Interfaces';
import Button from '@components/Forms/Button';
import TextInput from '@components/Forms/Inputs/TextInput';
import Repeater from '@components/Forms/Repeater';

const MembershipForm = () => {
    const [state, setState] = useState<MembershipFormState>({
        contacts: [],
        id: null,
        members: [],
        type: ''
    });

    const membershipId = window.location.href.split('/').pop();

    useEffect(() => {
        //
    }, [state]);

    const addRow = (type: 'contacts' | 'members') => {
        let rows = state[type];
        rows.push({
            id: null,
        });

        setState({...state, [type]: rows});
    }

    const removeRow = (type: 'contacts' | 'members', index: number) => {
        let rows = state[type];
        rows.splice(index, 1);

        setState({...state, [type]: rows});
    }

    return (
        <Form
            apiUrl={'/api/memberships/' + membershipId}
            state={state}
            setState={setState}
        >
            <Fieldset label={'Details'} initialState={state}>
                <SelectInput
                    label={'Type'}
                    name={'type'}
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
                    value={state.type}
                />
            </Fieldset>

            <Repeater addRow={() => addRow('members')} index={'members'} initialState={state.members} label={'Members'}>
                {state.members.map((member, index) => (
                    <Fieldset initialState={member} index={index}>
                        <div className="button-controls">
                            <Button onClick={() => removeRow('members', index)} label={'Remove'} />
                        </div>
                        <TextInput label={'Full Name'} name={'name'} value={member.name} />
                        <DatePicker label={'Date of Birth'} name={'date_of_birth'} value={member.date_of_birth} />
                        <TextInput label={'Email Address'} name={'email'} value={member.email} />
                        <TextInput label={'Phone Number'} name={'phone'} value={member.phone} />
                    </Fieldset>
                ))}
            </Repeater>

            <Repeater addRow={() => addRow('contacts')} index={'contacts'} initialState={state.contacts} label={'Contacts'}>
                {state?.contacts.map((contact, index) => (
                    <Fieldset initialState={contact} index={index}>
                        <TextInput label={'Full Name'} name={'name'} value={contact.name} />
                        <DatePicker label={'Date of Birth'} name={'date_of_birth'} value={contact.date_of_birth} />
                        <TextInput label={'Email Address'} name={'email'} value={contact.email} />
                        <TextInput label={'Phone Number'} name={'phone'} value={contact.phone} />
                    </Fieldset>
                ))}
            </Repeater>

            <fieldset>
                <div className="button-controls">
                    <Button label={'Submit'} onClick={() => console.log(state)} />
                </div>
            </fieldset>
        </Form>
    );
}

export default MembershipForm;
