export const AddIcon = ({...props}) => {
    return (
        <svg className="icon icon-add" {...props}>
            <symbol id="icon-add" viewBox="0 0 32 32">
                <path
                    d="M27 0h-22c-2.76 0.003-4.997 2.24-5 5v22c0.003 2.76 2.24 4.997 5 5h22c2.76-0.003 4.997-2.24 5-5v-22c-0.003-2.76-2.24-4.997-5-5zM30 27c0 1.657-1.343 3-3 3h-22c-1.657 0-3-1.343-3-3v-22c0-1.657 1.343-3 3-3h22c1.657 0 3 1.343 3 3z"></path>
                <path d="M17 6h-2v9h-9v2h9v9h2v-9h9v-2h-9z"></path>
            </symbol>
            <use href="#icon-add"></use>
        </svg>
    )
}

export const DeleteIcon = ({...props}) => {
    return (
        <svg className="icon icon-trash" {...props}>
            <symbol id="icon-trash" viewBox="0 0 32 32">
                <path
                    d="M32 4h-12v-2c0-1.105-0.895-2-2-2h-4c-1.104 0-2 0.895-2 2v2h-12v2h4v21c0.003 2.76 2.24 4.997 5 5h14c2.76-0.003 4.997-2.24 5-5v-21h4zM14 2h4v2h-4zM26 27c0 1.657-1.343 3-3 3h-14c-1.657 0-3-1.343-3-3v-21h20z"></path>
                <path d="M9 8h2v20h-2z"></path>
                <path d="M15 8h2v20h-2z"></path>
                <path d="M21 8h2v20h-2z"></path>
            </symbol>
            <use href="#icon-trash"></use>
        </svg>
    )
}