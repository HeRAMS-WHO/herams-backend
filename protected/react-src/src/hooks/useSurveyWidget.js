const useSurveyWidget = ({url}) => {
    const [settings, setSettings] = useState(null)
    const [haveToDelete, setHaveToDelete] = useState(false)
    useEffect(() => {
        get(url).then((response) => {
            setSettings(response.settings)
            setHaveToDelete(response.haveToDelete)
        })
    }, [])
}