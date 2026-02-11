import { useState } from 'react';
import { Form, Repeater, SelectInput } from '../Components/Forms';
import { FormDataContext } from '../Contexts/FormDataContext';
import { MembershipFormState } from '../Interfaces/';

const MembershipForm = () => {
    const [formData, setFormData] = useState<MembershipFormState>({
        contacts: [],
        id: null,
        members: [],
        type: ''
    });

    const tooManyMembersAlert = 'Only "Family" memberships may have more than one member. Please delete any additional members before selecting a different type.';

    const validateType = (value: string) => {
        if (value !== 'family' && formData.members.length > 1) {
            alert(tooManyMembersAlert);
            return false;
        }

        return true;
    }

    return (
        <FormDataContext.Provider value={{ formData, setFormData }}>
            <Form>
                <fieldset>
                    <h2>Memberhip Details</h2>
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
                        value={formData?.type}
                    />
                </fieldset>

                <Repeater dataKey={'members'} label={'Members'}>
                    <div>I am a repeater.</div>
                </Repeater>
            </Form>
        </FormDataContext.Provider>
    );
}

export default MembershipForm;
