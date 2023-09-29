const deletionProps = (confirmationText, actionEndpoint, redirectEndpoint) => {
    return {
        'data-herams-action' : "delete",
        'data-herams-confirm' : confirmationText,
        'data-herams-endpoint' : actionEndpoint,
        'data-herams-redirect' : redirectEndpoint
    }

}

export default deletionProps;