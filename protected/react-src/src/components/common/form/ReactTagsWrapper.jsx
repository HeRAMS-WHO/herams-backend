import {ReactTags} from 'react-tag-autocomplete'
import {useCallback} from "react";

const ReactTagsWrapper = ({state, setter, ...props}) => {
    const onAddTag = useCallback(
        (newTag) => {
            setter([...state, newTag])
        },
        [state]
    )

    const onDeleteTag = useCallback(
        (tagIndex) => {
            setter(state.filter((_, i) => i !== tagIndex))
        },
        [state]
    )
    return (
        <>
            <ReactTags
                selected={state}
                onAdd={onAddTag}
                onDelete={onDeleteTag}
                {...props} />
        </>
    )
}

export default ReactTagsWrapper