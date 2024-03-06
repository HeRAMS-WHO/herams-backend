import { useEffect, useState } from "react";
import { 
    Typography, 
    Grid, 
    TextField, 
    FormControl, 
    InputLabel, 
    Checkbox,
    MenuItem,
    Select, 
    FormControlLabel, 
    Button
} from "@mui/material";
import { fetchCountries, 
    fetchProjectVisibilityChoices, 
    fetchSurveys,
    fetchCreateProject
} from "../../services/apiProxyService";

const CreateProject = () => {
    const { projectId = null} = params

    const [languages, setLanguages] = useState({});
    const [titles, setTitle] = useState({});
    const [projectLanguages, setProjectLanguages] = useState({});
    const [projectVisibilities, setProjectVisibilities] = useState([]);
    const [selectedVisibility, setSelectedVisibility] = useState('Public, anyone can view this project')
    const [surveys, setSurveys] = useState([])
    const [selectedAdminSurvey, setSelectedAdminSurvey] = useState('')
    const [selectedDataSurvey, setSelectedDataSurvey] = useState('')
    const [countries, setCountries] = useState([])
    const [selectedCountry, setSelectedCountry] = useState('Unespecify (training / not in list)')
    const [latitude, setLatitude] = useState(0)
    const [longitude, setLongitude] = useState(0)
    const [externalDashboard, setExternalDashboard] = useState('')
    const [showErrors, setShowErrors] = useState(false)

    useEffect(() => {
        let tempTitles = {};
        let languagesTemp = {};
        locales.value.forEach((locale) => {
            tempTitles[locale.value] = '';
        });
        locales.value
            .forEach((locale) => languagesTemp[locale.value] = locale.name )
        setLanguages(languagesTemp)
        
        fetchProjectVisibilityChoices()
            .then(response => setProjectVisibilities(response))
        fetchSurveys()
            .then(response => {
                setSurveys(response)
            })
        fetchCountries()
            .then(response => setCountries(response))
        if (projectId){
            fetchProject(projectId)
                .then(response => {
                    setLatitude(response.latitude)
                    setLongitude(response.longitude)
                    setExternalDashboard(response.dashboard_url)
                    setSelectedCountry(response.country)
                    setSelectedVisibility(response.visibility)
                    setSelectedAdminSurvey(response.admin_survey_id)
                    setSelectedDataSurvey(response.data_survey_id)
                    let tempTitle = response.i18n.title
                    setTitle(tempTitle)
                    let tempLanguages = {}
                    response.languages.forEach(language => {
                        tempLanguages[language] = true
                    })
                    setProjectLanguages(tempLanguages)

                })
        }
    }, [locales.value]);
    
    const handleLanguageChange = (event, language) => {
        setTitle({ ...titles, [language]: event.target.value });
    };
    
    const handleCheckboxesLanguageChange = (event, language) => {
        
        setProjectLanguages({ ...projectLanguages, [language]: event.target.value === 'on' });
    };
    const checkIfThereIsAnyErrorOnForm = () => {
        let hasAnError = false
        if (!titles?.en){
            hasAnError = true
        }
        if (!projectLanguages.en){
            hasAnError = true
        }
        
        Object
            .keys(languages)
            .forEach((language) => {
                if (projectLanguages[language] === true && !titles[language]) {
                    hasAnError = true
                }
            })
        if (selectedAdminSurvey === selectedDataSurvey){
            hasAnError = true
        }
        if (!selectedAdminSurvey || !selectedDataSurvey){
            hasAnError = true
        }
        if (!latitude && !longitude){
            hasAnError = true
        }
        return hasAnError
    }
    const handleSubmit = () => {
        if (checkIfThereIsAnyErrorOnForm()){
            setShowErrors(true)
            console.log('there is an error')
            return true;
        }
        createProject()
    };
    const createProject = () => {
        const payload = {
            latitude: latitude,
            longitude: longitude,
            projectvisibility: selectedVisibility,
            country: selectedCountry,
            title: titles,
            languages: Object.keys(projectLanguages).filter(language => projectLanguages[language]),
            adminSurveyId: selectedAdminSurvey,
            dataSurveyId: selectedDataSurvey,
            primaryLanguage: 'en',
            dashboardUrl: externalDashboard
        }

        fetchCreateProject(payload, {})
            .then(response => response.json())
            .then((response) => {
                if (response.id){
                    useNavigate()("/admin/project/:projectId/workspace".replace(':projectId', response.id))
                }
            })

    }

    return (
        <>
            <Grid container justifyContent="flex-start">
                <Grid item xs={12} sx={{ marginBottom: '20px' }}>
                    <Button variant="contained" onClick={handleSubmit}>
                        Submit
                    </Button>
                    <Typography variant="h6">
                        {__('1. Project name')} <span style={{ color: 'red' }}>*</span>
                    </Typography>
                </Grid>
                {Object.keys(languages).map(language => (
                    <Grid item xs={12} key={language}  sx={{ marginBottom: '10px' }}>
                        <FormControl fullWidth>
                            <InputLabel htmlFor={`language-${language}`} shrink={Boolean(titles[language])}>
                                {languages[language].charAt(0).toUpperCase() + languages[language].slice(1)} 
                                {language === 'en' && <span style={{ color: 'red' }}>*</span>}
                            </InputLabel>
                            <TextField
                                id={`language-${language}`}
                                fullWidth
                                value={titles[language]}
                                onChange={(e) => handleLanguageChange(e, language)}
                            />
                            {language === 'en' &&
                                showErrors &&
                                !titles?.en && 
                                <span style={{ color: 'red' }}> {__('Name in english is required')}</span>}
                            {language !== 'en' &&
                                showErrors &&
                                projectLanguages[language] === true &&
                                !titles[language] && 
                                <span style={{ color: 'red' }}> 
                                    {__('This field is required. If ')} 
                                    {language} 
                                    {__(' is checked in project languages')}
                                </span>
                            }
                        </FormControl>
                    </Grid>
                ))}
                <Grid item xs={12} sx={{ marginBottom: '20px' }}>
                    <Typography variant="h6">
                        {__('2. Project languages')} <span style={{ color: 'red' }}>*</span>
                    </Typography>
                    <Typography variant="body1" color="text.secondary">
                        {__('These will be available for workspace & facility data')}
                    </Typography>
                </Grid>
                {Object.keys(languages).map(language => (
                    <Grid item xs={12} key={language}>
                        <FormControlLabel
                            control={<Checkbox value={projectLanguages[language]} onChange={(e) => handleCheckboxesLanguageChange(e, language)} />}
                            label={languages[language].charAt(0).toUpperCase() + languages[language].slice(1)}
                        />
                        <Typography>
                            {language === 'en' &&
                                showErrors &&
                                !projectLanguages?.en && 
                                <span style={{ color: 'red' }}> {__('English in project Languages is required')}</span>}
                        </Typography>
                    </Grid>
                    
                ))}
                <Grid item xs={12} sx={{ marginBottom: '20px', marginTop: '20px' }}>
                    <Typography variant="h6">
                        {__('3. Project visibilities')}
                    </Typography>
                </Grid>
                <Grid item xs={12} sx={{ marginBottom: '20px' }}>
                    <Select
                        labelId="select-visibility-label"
                        id="select-visibility"
                        value={selectedVisibility}
                        onChange={(e) => setSelectedVisibility(e.target.value)}
                        fullWidth
                    >
                        {projectVisibilities.map((visibility, index) => (
                            <MenuItem 
                                key={visibility.value} 
                                value={visibility.label}>
                                    {__(visibility.label)}
                            </MenuItem>
                        ))}
                    </Select>
                </Grid>
                <Grid item xs={12} sx={{ marginBottom: '20px', marginTop: '20px' }}>
                    <Typography variant="h6">
                        {__('4. Admin survey')} <span style={{ color: 'red' }}>*</span>
                    </Typography>
                    <Typography variant="body1" color="text.secondary">
                        {__('Survey to use for facility settings')}
                    </Typography>
                </Grid>
                <Grid item xs={12} sx={{ marginBottom: '20px' }}>
                    <Select
                        id="admin-survey"
                        value={selectedAdminSurvey}
                        onChange={(e) => setSelectedAdminSurvey(e.target.value)}
                        fullWidth
                    >
                        {surveys.map((survey, index) => (
                            <MenuItem 
                                key={survey.id} 
                                value={survey.id}>
                                    {__(survey.title)}
                            </MenuItem>
                        ))}
                    </Select>
                    <Typography>
                        { showErrors &&
                            selectedAdminSurvey === selectedDataSurvey && 
                            <span style={{ color: 'red' }}> {__('Admin survey and data survey must be different.')} </span>}
                        { showErrors &&
                            !selectedAdminSurvey && 
                            <span style={{ color: 'red' }}> {__('You must select an admin survey.')} </span>}
                    </Typography>
                </Grid>
                <Grid item xs={12} sx={{ marginBottom: '20px', marginTop: '20px' }}>
                    <Typography variant="h6">
                        {__('5. Data survey')} <span style={{ color: 'red' }}>*</span>
                    </Typography>
                    <Typography variant="body1" color="text.secondary">
                        {__('Survey to use for facility data')}
                    </Typography>
                </Grid>
                <Grid item xs={12} sx={{ marginBottom: '20px' }}>
                    <Select
                        id="data-survey"
                        value={selectedDataSurvey}
                        onChange={(e) => setSelectedDataSurvey(e.target.value)}
                        fullWidth
                    >
                        {surveys.map((survey, index) => (
                            <MenuItem 
                                key={survey.id} 
                                value={survey.id}>
                                    {__(survey.title)}
                            </MenuItem>
                        ))}
                    </Select>
                    <Typography>
                        { showErrors &&
                            selectedAdminSurvey === selectedDataSurvey && 
                            <span style={{ color: 'red' }}> {__('Admin survey and data survey must be different.')} </span>}
                        { showErrors &&
                            !selectedDataSurvey && 
                            <span style={{ color: 'red' }}> {__('You must select a data survey.')} </span>}
                    </Typography>
                </Grid>

                <Grid item xs={12} sx={{ marginBottom: '20px', marginTop: '20px' }}>
                    <Typography variant="h6">
                        {__('6. Country')} <span style={{ color: 'red' }}>*</span>
                    </Typography>
                </Grid>
                <Grid item xs={12} sx={{ marginBottom: '20px' }}>
                    <Select
                        id="country"
                        value={selectedCountry}
                        onChange={(e) => setSelectedCountry(e.target.value)}
                        fullWidth
                    >
                        <MenuItem
                            key={0}
                            value='UND'
                            >
                            {__('Unespecify (training / not in list)')}
                        </MenuItem>
                        {countries.map((country) => (
                            <MenuItem 
                                key={country.name} 
                                value={country.iso3}>
                                    {__(country.name)}
                            </MenuItem>
                        ))}
                    </Select>
                </Grid>

                <Grid item xs={12} sx={{ marginBottom: '20px' }}>
                    <Typography variant="h6">
                        {__('7. Latitude')}
                    </Typography>
                </Grid>
                <Grid item xs={12} sx={{ marginBottom: '20px' }}>
                    <TextField
                        id="latitude"
                        type="number"
                        fullWidth
                        value={latitude}
                        onChange={(e) => setLatitude(e.target.value)}
                    />
                    { showErrors &&
                        !longitude && 
                        !latitude && 
                        <span style={{ color: 'red' }}> {__('Latitude and longitude can not be both of them 0.')} </span>}
                </Grid>

                <Grid item xs={12} sx={{ marginBottom: '20px' }}>
                    <Typography variant="h6">
                        {__('8. Longitude')}
                    </Typography>
                </Grid>
                <Grid item xs={12} sx={{ marginBottom: '20px' }}>
                    <TextField
                        id="longitude"
                        type="number"
                        fullWidth
                        value={longitude}
                        onChange={(e) => setLongitude(e.target.value)}
                    />
                    { showErrors &&
                        !longitude && 
                        !latitude && 
                        <span style={{ color: 'red' }}> {__('Latitude and longitude can not be both of them 0.')} </span>}
                </Grid>

                <Grid item xs={12} sx={{ marginBottom: '20px' }}>
                    <Typography variant="h6">
                        {__('9. External dashboard URL')}
                    </Typography>
                    <Typography variant="body1" color="text.secondary">
                        {__('Leave empty to use built-in dashboarding')}
                    </Typography>
                </Grid>
                <Grid item xs={12} sx={{ marginBottom: '20px' }}>
                    <TextField
                        id="dashboard"
                        fullWidth
                        value={externalDashboard}
                        onChange={(e) => setExternalDashboard(e.target.value)}
                    />
                </Grid>

            </Grid>
            
        </>
    );
};

export default CreateProject;