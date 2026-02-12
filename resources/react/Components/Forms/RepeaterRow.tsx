import { useEffect, useImperativeHandle, useState } from 'react';
import { RepeaterRowContext } from '../../Contexts/RepeaterRowContext';
import { useFormContext } from '../../Contexts/FormContext';

interface Props extends React.PropsWithChildren {
    index: number,
    model: string,
}

const RepeaterRow = ({ children, index, model }: Props) => {
    const { formState, setFormState } = useFormContext();
    const [rowState, setRowState] = useState<any>();

    useEffect(() => {
        const rows = formState[model];
        const row = rows[index];

        setRowState(row);
    }, []);

    useEffect(() => {
        const rows = formState[model];
        if (rows && rowState) {
            rows[index] = rowState;
            setFormState({...formState, [model]: rows});
        }
    }, [rowState]);

    return (
        <RepeaterRowContext.Provider value={{ rowState, setRowState }}>
            <fieldset>
                {children}
            </fieldset>
        </RepeaterRowContext.Provider>
    );
}

export default RepeaterRow;
