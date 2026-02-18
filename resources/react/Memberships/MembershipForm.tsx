import Fieldset from '@components/Forms/Fieldset';
import { Form } from '@components/Forms/Form';
import DatePicker from '@components/Forms/Inputs/DatePicker';
import SelectInput from '@components/Forms/Inputs/SelectInput';
import { useEffect, useState } from 'react';
import { Member, MembershipFormState } from '../Interfaces';
import Button from '@components/Forms/Button';
import TextInput from '@components/Forms/Inputs/TextInput';
import Repeater from '@components/Forms/Repeater';

const MembershipForm = () => {
    const [canAddMember, setCanAddMember] = useState<boolean>(false);
    const [membersLimit, setMembersLimit] = useState<number | false>(false);
    const [state, setState] = useState<MembershipFormState>({
        address: {
            line1: null,
            line2: null,
            suburb: null,
            state: null,
            postcode: null,
        },
        contacts: [],
        id: null,
        member: {
            name: null,
            date_of_birth: null,
            email: null,
            member_membership: {
                relationship: null,
            },
            phone: null
        },
        members: [],
        type: ''
    });

    const membershipId = window.location.href.split('/').pop();
    const alertTooManyMembers = 'Too many members.';

    useEffect(() => {
        console.log(state);
        if (state.type !== 'family') {
            setMembersLimit(1);
        } else {
            setMembersLimit(4);
        }

        updateCanAddMember();
        console.log(state);
    }, [state]);

    useEffect(() => {
        updateCanAddMember();
    }, [membersLimit]);

    const addRow = (type: 'contacts' | 'members', content: Member = {
        id: null,
        name: null,
        date_of_birth: null,
        email: null,
        member_membership: {
            relationship: null,
        },
        phone: null,
    }) => {
        let rows = state[type];
        rows.push(content);

        setState({...state, [type]: rows});
    }

    const confirmRemove = (type: 'contacts' | 'members', index: number) => {
        if (confirm('Are you sure? This action cannot be undone without refreshing the page before submitting.')) {
            removeRow(type, index);
        }
    }

    const removeRow = (type: 'contacts' | 'members', index: number) => {
        let rows = state[type];
        rows.splice(index, 1);

        setState({...state, [type]: rows});
    }

    const switchRow = (oldType: 'contacts' | 'members', newType: 'contacts' | 'members', index: number) => {
        const row = state[oldType][index];
        removeRow(oldType, index);

        addRow(newType, row);
    }

    const updateCanAddMember = () => {
        if (membersLimit === false) {
            setCanAddMember(true);
        } else {
            setCanAddMember(state.type === 'family' && state.members.length < membersLimit);
        }
    }

    const validateMembershipType = (value: string): boolean => {
        if (value !== 'family' && state.members.length) {
            alert(alertTooManyMembers);
            return false;
        }

        return true;
    }

    return (
        <Form
            apiUrl={'/api/memberships/' + membershipId}
            state={state}
            setState={setState}
        >
            <Fieldset label={'Details'} initialState={state}>
                <SelectInput
                    isValid={validateMembershipType}
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

            <Fieldset initialState={state.address} label={'Address'} parentKey={'address'}>
                <TextInput name={'line1'} label={'Address Line 1'} value={state.address.line1} />
                <TextInput name={'line2'} label={'Address Line 2'} value={state.address.line2} />
                <TextInput name={'suburb'} label={'Suburb'} value={state.address.suburb} />
                <TextInput name={'state'} label={'State'} value={state.address.state} />
                <TextInput name={'postcode'} label={'Post Code'} value={state.address.postcode} />
            </Fieldset>

            <Fieldset initialState={state.member} label={'Primary Member'} parentKey={'member'}>
                <TextInput label={'Full Name'} name={'name'} value={state.member.name} required />
                <DatePicker label={'Date of Birth'} name={'date_of_birth'} value={state.member.date_of_birth} required />
                <TextInput label={'Email Address'} name={'email'} value={state.member.email} />
                <TextInput label={'Phone Number'} name={'phone'} value={state.member.phone} />
            </Fieldset>

            {state.type === 'family' && <Repeater
                addRow={() => addRow('members')}
                initialState={state.members}
                label={'Members'}
                limit={membersLimit}
                parentKey={'members'}
            >
                {state.members.map((member, index) => (
                    <Fieldset initialState={member} parentKey={index}>
                        <div className="button-controls">
                            <Button onClick={() => switchRow('members', 'contacts', index)} label={'Switch to Contacts'} />
                            <Button onClick={() => confirmRemove('members', index)} label={'Remove'} />
                        </div>
                        <TextInput label={'Full Name'} name={'name'} value={member.name} required />
                        <DatePicker label={'Date of Birth'} name={'date_of_birth'} value={member.date_of_birth} required />
                        <TextInput label={'Email Address'} name={'email'} value={member.email} />
                        <TextInput label={'Phone Number'} name={'phone'} value={member.phone} />
                    </Fieldset>
                ))}
            </Repeater>}

            <Repeater
                addRow={() => addRow('contacts')}
                initialState={state.contacts}
                label={'Emergency Contacts'}
                parentKey={'contacts'}
            >
                {state?.contacts.map((contact, index) => (
                    <Fieldset initialState={contact} parentKey={index}>
                        <div className="button-controls">
                            {(canAddMember) && (
                                <Button onClick={() => switchRow('contacts', 'members', index)} label={'Switch to Members'} />
                            )}
                            <Button onClick={() => confirmRemove('contacts', index)} label={'Remove'} />
                        </div>
                        <TextInput label={'Full Name'} name={'name'} value={contact.name} required />
                        <DatePicker label={'Date of Birth'} name={'date_of_birth'} value={contact.date_of_birth} />
                        <TextInput label={'Phone Number'} name={'phone'} value={contact.phone} required />
                        <Fieldset initialState={contact.member_membership} parentKey={'member_membership'}>
                            <TextInput label={'Relationship'} name={'relationship'} value={contact.member_membership.relationship} />
                        </Fieldset>
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
