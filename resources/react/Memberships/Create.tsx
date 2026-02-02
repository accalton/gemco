import React from 'react';
import { Fieldset, Form, Submit, TextInput } from '../Components/Forms';

const Create = () => {
    const handleSubmit = (event: React.SubmitEvent<HTMLFormElement>) => {
        const formData = new FormData(event.currentTarget);
        event.preventDefault();
        fetch('/memberships/store', {
            method: 'POST',
            body: formData
        }).then(response => console.log(response));
    }

    return (
        <Form handleSubmit={handleSubmit}>
            <Fieldset>
                <TextInput name={'name'} label={'Full Name'} autofocus />
                <TextInput name={'email'} label={'Email Address'} />
                <TextInput name={'phone'} label={'Phone Number'} />
                <Submit />
            </Fieldset>
        </Form>
    );
}

export default Create;
