import axios from 'axios';
import React, { useState } from 'react';
import { Fieldset, Form, Submit, TextInput } from '../Components/Forms';

const Create = () => {
    const [formData, setFormData] = useState({
        name: 'Adrian Calton',
        email: 'accalton@gmail.com',
        phone: '0481 500 796'
    });

    const handleChange = (field: string, value: string) => {
        setFormData({
            ...formData,
            [field]: value
        });
    }

    const handleSubmit = async (event: React.SubmitEvent<HTMLFormElement>) => {
        event.preventDefault();
        axios.post('/memberships/store', formData)
            .then(response => console.log(response));
    }

    return (
        <Form handleSubmit={handleSubmit}>
            <Fieldset>
                <TextInput name={'name'} label={'Full Name'} value={formData.name} update={handleChange} autofocus />
                <TextInput name={'email'} label={'Email Address'} value={formData.email} update={handleChange} />
                <TextInput name={'phone'} label={'Phone Number'} value={formData.phone} update={handleChange} />
                <Submit />
            </Fieldset>
        </Form>
    );
}

export default Create;
