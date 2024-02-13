import Grid from "@mui/material/Grid";
import Typography from "@mui/material/Typography";
import Button from "@mui/material/Button";
import {useState} from "react";
import Box from '@mui/material/Box';
import Modal from '@mui/material/Modal';
import * as apiService from "../../services/apiService";
import {styled} from "@mui/material/styles";
import {Alert, CircularProgress, Link} from "@mui/material";
import CheckCircleOutlineIcon from '@mui/icons-material/CheckCircleOutline';
import HighlightOffIcon from '@mui/icons-material/HighlightOff';
import Radio from '@mui/material/Radio';
import RadioGroup from '@mui/material/RadioGroup';
import FormControlLabel from '@mui/material/FormControlLabel';
import FormControl from '@mui/material/FormControl';
import FilePresentIcon from '@mui/icons-material/FilePresent';
import {BASE_URL} from "../../services/apiProxyService";

const VisuallyHiddenInput = styled('input')({
    clip: 'rect(0 0 0 0)',
    clipPath: 'inset(50%)',
    height: 1,
    overflow: 'hidden',
    position: 'absolute',
    bottom: 0,
    left: 0,
    whiteSpace: 'nowrap',
    width: 1,
});

const style = {
    position: 'absolute',
    top: '50%',
    left: '50%',
    transform: 'translate(-50%, -50%)',
    width: 600,
    bgcolor: 'background.paper',
    border: '2px solid #000',
    boxShadow: 24,
    p: 4,
};

const ProjectImport = () => {
    const [open, setOpen] = useState(false);
    const handleClose = () => setOpen(false);
    const [showLoading, setShowLoading] = useState(false);
    const [showInvalidFile, setShowInvalidFile] = useState(false);
    const [selectedFile, setSelectedFile] = useState(null);
    const [uploadResponse, setUploadResponse] = useState(null);
    const [showValidationModal, setShowValidationModal] = useState(true);

    const handleFileChange = (event) => {
        setShowInvalidFile(false);
        setSelectedFile(event.target.files[0]);
        if (event.target.files[0].type != 'text/csv') {
            setShowInvalidFile(true);
            setSelectedFile(null);
        }
        setUploadResponse(null);
    };

    const importWs = async (confirm_import) => {
        setShowLoading(true);
        setUploadResponse(null);
        setShowValidationModal(confirm_import === 'no');
        handleClose(); // close the modal popup
        const formData = new FormData();
        formData.append("file", selectedFile);
        formData.append('confirm_import', confirm_import);

        try {
            const response = await apiService.importWs(replaceVariablesAsText(':projectId'), formData);
            const responseData = await response.json();
            setShowLoading(false);
            setUploadResponse(responseData);
            if (response.ok) {
                setOpen(true);
            }
        } catch (error) {
            console.error('Error in importWs:', error);
        }
    };

    return (
        <Grid container>
            <Modal
                open={open}
                onClose={handleClose}
                aria-labelledby="modal-modal-title"
                aria-describedby="modal-modal-description"
            >

                <Box sx={style}>
                    <Typography id="modal-modal-title" variant="h6" component="h2">
                        Import File Validation
                    </Typography>
                    {showValidationModal &&
                        <Grid item xs={12}>
                            {uploadResponse && uploadResponse.data &&
                                <Typography id="modal-modal-description" sx={{mt: 2}}>
                                    <CheckCircleOutlineIcon color="success" /> Data is valid: {uploadResponse.data.valid} records
                                    ready to be imported
                                </Typography>
                            }
                            <Button variant="outlined" onClick={handleClose} sx={{mt: 2, ml: '15vw'}}>
                                Back
                            </Button>
                            {uploadResponse && uploadResponse.data && uploadResponse.data.valid > 0 &&
                                <Button onClick={() => importWs('yes')} color="primary" variant="contained" sx={{mt: 2, ml: '3vw'}}>
                                    Submit Import File
                                </Button>
                            }
                        </Grid>
                    }
                    {!showValidationModal &&
                        <Grid item xs={12}>
                            {uploadResponse && uploadResponse.data &&
                                <div>
                                    <Typography id="modal-modal-description" sx={{mt: 2}}>
                                        Import completed
                                    </Typography>
                                    <Typography id="modal-modal-description" sx={{mt: 1}}>
                                        <CheckCircleOutlineIcon color="success" /> {uploadResponse.data.valid} records
                                        have been imported
                                    </Typography>
                                    <Typography id="modal-modal-description" sx={{mt: 1}}>
                                        <HighlightOffIcon color="error" /> {uploadResponse.data.invalid} records
                                        could not be imported
                                    </Typography>
                                </div>
                            }
                            <Button variant="outlined" onClick={handleClose} sx={{mt: 2, ml: '30vw'}}>
                                Close
                            </Button>
                        </Grid>
                    }
                </Box>
            </Modal>
            <Grid item xs={12}>
                <br /><br />
            </Grid>
            <Grid item xs={1}></Grid>
            <Grid item xs={7}>
                <FormControl>
                    <RadioGroup
                        aria-labelledby="demo-radio-buttons-group-label"
                        defaultValue="workspace"
                        name="radio-buttons-group"
                    >
                        <FormControlLabel value="workspace" control={<Radio />} label="Workspace" />
                        <FormControlLabel value="hsdu_baseline" control={<Radio />} label="HSDU baseline" />
                        <FormControlLabel value="hsdu_updates" control={<Radio />} label="HSDU updates" />
                        <FormControlLabel value="situation_updates" control={<Radio />} label="Situation updates" />
                    </RadioGroup>
                </FormControl>
            </Grid>
            <Grid item xs={4}>
                <Link color="primary" href={BASE_URL + replaceVariablesAsText('/project/:projectId/import-ws-template')}>
                    Download template
                </Link>
            </Grid>
            <Grid item xs={1}></Grid>
            <Grid item xs={10}>
                <FormControl>
                    <Button sx={{width: '70vw'}} component="label" variant="outlined" endIcon={<FilePresentIcon/>}>
                        Select CSV file
                        <VisuallyHiddenInput type="file" onChange={(e) => handleFileChange(e)}/>
                    </Button>
                </FormControl>
                {selectedFile &&
                    <Alert severity="warning" sx={{width: '70vw'}}>
                        Selected file: {selectedFile.name}
                    </Alert>
                }
                {showInvalidFile &&
                    <Alert severity="error" sx={{width: '70vw'}}>
                        Invalid file format
                    </Alert>
                }
                {uploadResponse &&
                    <Alert severity="info" sx={{width: '70vw'}}>
                        {uploadResponse.msg}
                    </Alert>
                }
            </Grid>
            <Grid item xs={8}></Grid>
            <Grid item xs={4}>
                <br />
                {showLoading && <CircularProgress/>}
                {!showLoading &&
                    <Button onClick={() => importWs('no')} component="label" color="primary" variant="contained">
                        Validate Data
                    </Button>
                }
            </Grid>
        </Grid>
    );
}

export default ProjectImport;
