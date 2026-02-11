import { useEffect, useState } from "react";
import { useFormDataContext } from "../../Contexts/FormDataContext";
import { MembershipFormState } from "../../Interfaces";

interface Props extends React.PropsWithChildren {
    dataKey: string,
    label?: string,
}

const Repeater = ({ children, dataKey, label }: Props) => {
    const { formData } = useFormDataContext();
    const [rows, setRows] = useState<any>([]);

    useEffect(() => {
        if (formData !== null) {
            setRows(formData[dataKey as keyof MembershipFormState]);
        }
    }, [formData]);

    return (
        <div className="repeater">
            <h2>{label}</h2>
            {rows.map(() => (
                <div>
                    Hooray
                </div>
            ))}
        </div>
    );
}

export default Repeater;
