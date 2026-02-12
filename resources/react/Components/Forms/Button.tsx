interface Props {
    label: string,
    onClick: React.MouseEventHandler<HTMLButtonElement>,
}

const Button = ({ label, onClick }: Props) => {
    const handleClick = (event: React.MouseEvent<HTMLButtonElement>) => {
        event.preventDefault();
        onClick(event);
    }

    return (
        <button onClick={handleClick}>{label}</button>
    );
}

export default Button;
