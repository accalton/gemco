import { useState } from 'react';
import { Form, Fieldset, SelectInput } from '../Components/Forms';
import { FormContext } from '../Contexts/FormContext';
import { MembershipFormState } from '../Interfaces/';
import { MemberFieldset } from './Components';

const MembershipForm = () => {
    const [formState, setFormState] = useState<MembershipFormState>({
        contacts: [],
        id: null,
        members: [],
        type: ''
    });

    const membershipId = window.location.href.split('/').pop();
    const tooManyMembersAlert = 'Only "Family" memberships may have more than one member. Please delete any additional members before selecting a different type.';

    const validateType = (value: string) => {
        if (value !== 'family' && formState.members.length > 1) {
            alert(tooManyMembersAlert);
            return false;
        }

        return true;
    }

    return (
        <FormContext.Provider value={{ formState, setFormState }}>
            <Form apiUrl={'/api/memberships/' + membershipId}>
                <Fieldset label={'Membership Details'}>
                    <SelectInput
                        isValid={validateType}
                        label='Type'
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
                        value={formState.type}
                    />
                </Fieldset>

                <MemberFieldset />
            </Form>
        </FormContext.Provider>
    );
}

export default MembershipForm;
