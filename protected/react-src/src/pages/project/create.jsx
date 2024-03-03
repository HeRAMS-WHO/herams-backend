import { useEffect, useState } from "react";
import { Typography, Grid, TextField, FormControl, InputLabel, Checkbox, FormControlLabel } from "@mui/material";

const CreateProject = () => {
    const [languages, setLanguages] = useState({});
    const [titles, setTitle] = useState({});
    const [projectLanguages, setProjectLanguages] = useState({});
    const [projectVisibilities, setProjectVisibilities] = useState();	
    useEffect(() => {
        console.log("ok")
        let tempTitles = {};
        let languagesTemp = {};
        locales.value.forEach((locale) => {
            tempTitles[locale.value] = '';
        });
        locales
        .value
            .forEach((locale) => languagesTemp[locale.value] = locale.name )
        setLanguages(languagesTemp)
    }, [locales.value]);
    
    const handleLanguageChange = (event, language) => {
        setTitle({ ...titles, [language]: event.target.value });
    };
    
    const handleCheckboxesLanguageChange = (event, language) => {
        
        setProjectLanguages({ ...projectLanguages, [language]: event.target.value === 'on' });
    };
    
    const handleSubmit = () => {
        console.log('Project Name:', projectName);
        console.log('Languages:', languages);
        // Aquí puedes realizar acciones adicionales con los valores ingresados
    };

    return (
        <>
            <Grid container justifyContent="flex-start">
                <Grid item xs={12} sx={{ marginBottom: '20px' }}>
                    <Typography variant="h6">
                        {__('1. Project name')}
                    </Typography>
                </Grid>
                {Object.keys(languages).map(language => (
                    <Grid item xs={12} key={language}  sx={{ marginBottom: '20px' }}>
                        <FormControl fullWidth>
                            <InputLabel htmlFor={`language-${language}`} shrink={Boolean(titles[language])}>
                                {languages[language].charAt(0).toUpperCase() + languages[language].slice(1)} 
                                {language === 'en' && <span style={{ color: 'red' }}>*</span>} {/* Agrega un asterisco rojo al final de la etiqueta solo para el idioma "en" */}
                            </InputLabel>
                            <TextField
                                id={`language-${language}`}
                                fullWidth
                                value={titles[language]}
                                onChange={(e) => handleLanguageChange(e, language)}
                            />
                        </FormControl>
                    </Grid>
                ))}
                {Object.keys(languages).map(language => (
                    <Grid item xs={12} key={language}>
                        <FormControlLabel
                            control={<Checkbox value={projectLanguages[language]} onChange={(e) => handleCheckboxesLanguageChange(e, language)} />}
                            label={languages[language].charAt(0).toUpperCase() + languages[language].slice(1)}
                        />
                    </Grid>
                ))}
            </Grid>
            <button onClick={handleSubmit}>Submit</button>
        </>
    );
};

export default CreateProject;