

const useReloadSpecialVariables = () => {
    const language = languageSelected.value
    reloadSpecialVariables({language, state:specialVariables})
}

export default useReloadSpecialVariables