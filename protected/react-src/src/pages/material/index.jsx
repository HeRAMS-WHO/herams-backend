import React, { useState } from 'react'
import Button from '@mui/material/Button';
import Grid from '@mui/material/Grid';
import Item from '@mui/material/Grid';
import {
    Alert, AlertTitle, AppBar,
    Avatar,
    Backdrop,
    Badge,
    Box,
    ButtonGroup,
    Checkbox, Chip,
    Fade,
    FormControlLabel,
    FormGroup, IconButton, Link,
    Modal, Toolbar,
    Typography
} from "@mui/material";
import { styled } from '@mui/material/styles';
import DeleteIcon from '@mui/icons-material/Delete';
import FourKIcon from '@mui/icons-material/FourK';
import ThreeSixtyIcon from '@mui/icons-material/ThreeSixty';
import AccessAlarmIcon from '@mui/icons-material/AccessAlarm';
import MailIcon from '@mui/icons-material/Mail';
import CloudUploadIcon from '@mui/icons-material/CloudUpload';
import TextField from '@mui/material/TextField';
import Autocomplete from '@mui/material/Autocomplete';
import Slider from '@mui/material/Slider';
import Input from '@mui/material/Input';
import InputLabel from '@mui/material/InputLabel';
import InputAdornment from '@mui/material/InputAdornment';
import FormControl from '@mui/material/FormControl';
import AccountCircle from '@mui/icons-material/AccountCircle';
import ShoppingCartIcon from '@mui/icons-material/ShoppingCart';
import HealingIcon from '@mui/icons-material/Healing';
import MonitorHeartIcon from '@mui/icons-material/MonitorHeart';
import MedicalInformationIcon from '@mui/icons-material/MedicalInformation';
import Stepper from '@mui/material/Stepper';
import Step from '@mui/material/Step';
import StepLabel from '@mui/material/StepLabel';
import PropTypes from 'prop-types';
import Tabs from '@mui/material/Tabs';
import Tab from '@mui/material/Tab';
import { DataGrid, GridToolbar } from '@mui/x-data-grid';
import Table from '@mui/material/Table';
import TableBody from '@mui/material/TableBody';
import TableCell from '@mui/material/TableCell';
import TableContainer from '@mui/material/TableContainer';
import TableHead from '@mui/material/TableHead';
import TableRow from '@mui/material/TableRow';
import Paper from '@mui/material/Paper';
import {DisplaySettings} from "@mui/icons-material";
import Dialog from '@mui/material/Dialog';
import DialogActions from '@mui/material/DialogActions';
import DialogContent from '@mui/material/DialogContent';
import DialogContentText from '@mui/material/DialogContentText';
import DialogTitle from '@mui/material/DialogTitle';
import Slide from '@mui/material/Slide';
import DialpadIcon from '@mui/icons-material/Dialpad';
import {DatePicker, DateTimePicker, LocalizationProvider, MobileDatePicker, TimePicker} from "@mui/x-date-pickers";
import {AdapterDayjs} from "@mui/x-date-pickers/AdapterDayjs";
import {DemoItem} from "@mui/x-date-pickers/internals/demo";
import dayjs from "dayjs";
import AlertMaterial from '../../components/common/Alert/AlertMaterial'

const Transition = React.forwardRef(function Transition(props, ref) {
    return <Slide direction="up" ref={ref} {...props} />;
});

function createData(name, calories, fat, carbs, protein) {
    return { name, calories, fat, carbs, protein };
}

const rows2 = [
    createData('Frozen yoghurt', 159, 6.0, 24, 4.0),
    createData('Ice cream sandwich', 237, 9.0, 37, 4.3),
    createData('Eclair', 262, 16.0, 24, 6.0),
    createData('Cupcake', 305, 3.7, 67, 4.3),
    createData('Gingerbread', 356, 16.0, 49, 3.9),
];

function CustomTabPanel(props) {
    const { children, value, index, ...other } = props;

    return (
        <div
            role="tabpanel"
            hidden={value !== index}
            id={`simple-tabpanel-${index}`}
            aria-labelledby={`simple-tab-${index}`}
            {...other}
        >
            {value === index && (
                <Box sx={{ p: 3 }}>
                    <Typography>{children}</Typography>
                </Box>
            )}
        </div>
    );
}

CustomTabPanel.propTypes = {
    children: PropTypes.node,
    index: PropTypes.number.isRequired,
    value: PropTypes.number.isRequired,
};

function a11yProps(index) {
    return {
        id: `simple-tab-${index}`,
        'aria-controls': `simple-tabpanel-${index}`,
    };
}

const StyledBadge = styled(Badge)(({ theme }) => ({
    '& .MuiBadge-badge': {
        right: -3,
        top: 13,
        border: `2px solid ${theme.palette.background.paper}`,
        padding: '0 4px',
    },
}));

const marks = [
    {
        value: 0,
        label: '0°C',
    },
    {
        value: 20,
        label: '20°C',
    },
    {
        value: 37,
        label: '37°C',
    },
    {
        value: 100,
        label: '100°C',
    },
];

const columns = [
    { field: 'id', headerName: 'ID', width: 70 },
    { field: 'firstName', headerName: 'First name', width: 130 },
    { field: 'lastName', headerName: 'Last name', width: 130 },
    {
        field: 'age',
        headerName: 'Age',
        type: 'number',
        width: 90,
    },
    {
        field: 'fullName',
        headerName: 'Full name',
        description: 'This column has a value getter and is not sortable.',
        sortable: false,
        width: 160,
        valueGetter: (params) =>
            `${params.row.firstName || ''} ${params.row.lastName || ''}`,
    },
    {
        field: 'date', headerName: 'Date', width: 130, type: 'date',
        valueFormatter: (params) => dayjs(params.value).format('DD/MM/YYYY'),
    }
];

const rows = [
    { id: 1, lastName: 'Snow', firstName: 'Jon', age: 35, date: '2023-01-12' },
    { id: 2, lastName: 'Lannister', firstName: 'Cersei', age: 42, date: '2023-04-23' },
    { id: 3, lastName: 'Lannister', firstName: 'Jaime', age: 45, date: '2023-07-15' },
    { id: 4, lastName: 'Stark', firstName: 'Arya', age: 16, date: '2022-10-15' },
    { id: 5, lastName: 'Targaryen', firstName: 'Daenerys', age: null, date: '2020-08-22' },
    { id: 6, lastName: 'Melisandre', firstName: null, age: 150, date: '2022-07-21' },
    { id: 7, lastName: 'Clifford', firstName: 'Ferrara', age: 44, date: '2021-09-16' },
    { id: 8, lastName: 'Frances', firstName: 'Rossini', age: 36, date: '2021-06-14' },
    { id: 9, lastName: 'Roxie', firstName: 'Harvey', age: 65, date: '2020-12-17' },
];

function valuetext(value) {
    return `${value}°C`;
}

const top100Films = [
    { label: 'The Shawshank Redemption', year: 1994 },
    { label: 'The Godfather', year: 1972 },
    { label: 'The Godfather: Part II', year: 1974 },
    { label: 'The Dark Knight', year: 2008 },
    { label: '12 Angry Men', year: 1957 },
    { label: "Schindler's List", year: 1993 },
    { label: 'Pulp Fiction', year: 1994 },
    {
        label: 'The Lord of the Rings: The Return of the King',
        year: 2003,
    },
    { label: 'The Good, the Bad and the Ugly', year: 1966 },
    { label: 'Fight Club', year: 1999 },
    {
        label: 'The Lord of the Rings: The Fellowship of the Ring',
        year: 2001,
    },
    {
        label: 'Star Wars: Episode V - The Empire Strikes Back',
        year: 1980,
    },
    { label: 'Forrest Gump', year: 1994 },
    { label: 'Inception', year: 2010 },
    {
        label: 'The Lord of the Rings: The Two Towers',
        year: 2002,
    },
    { label: "One Flew Over the Cuckoo's Nest", year: 1975 },
    { label: 'Goodfellas', year: 1990 },
    { label: 'The Matrix', year: 1999 },
    { label: 'Seven Samurai', year: 1954 },
    {
        label: 'Star Wars: Episode IV - A New Hope',
        year: 1977,
    },
    { label: 'City of God', year: 2002 },
    { label: 'Se7en', year: 1995 },
    { label: 'The Silence of the Lambs', year: 1991 },
    { label: "It's a Wonderful Life", year: 1946 },
    { label: 'Life Is Beautiful', year: 1997 },
    { label: 'The Usual Suspects', year: 1995 },
    { label: 'Léon: The Professional', year: 1994 },
    { label: 'Spirited Away', year: 2001 },
    { label: 'Saving Private Ryan', year: 1998 },
    { label: 'Once Upon a Time in the West', year: 1968 },
    { label: 'American History X', year: 1998 },
    { label: 'Interstellar', year: 2014 },
    { label: 'Casablanca', year: 1942 },
    { label: 'City Lights', year: 1931 },
    { label: 'Psycho', year: 1960 },
    { label: 'The Green Mile', year: 1999 },
    { label: 'The Intouchables', year: 2011 },
    { label: 'Modern Times', year: 1936 },
    { label: 'Raiders of the Lost Ark', year: 1981 },
    { label: 'Rear Window', year: 1954 },
    { label: 'The Pianist', year: 2002 },
    { label: 'The Departed', year: 2006 },
    { label: 'Terminator 2: Judgment Day', year: 1991 },
    { label: 'Back to the Future', year: 1985 },
    { label: 'Whiplash', year: 2014 },
    { label: 'Gladiator', year: 2000 },
    { label: 'Memento', year: 2000 },
    { label: 'The Prestige', year: 2006 },
    { label: 'The Lion King', year: 1994 },
    { label: 'Apocalypse Now', year: 1979 },
    { label: 'Alien', year: 1979 },
    { label: 'Sunset Boulevard', year: 1950 },
    {
        label: 'Dr. Strangelove or: How I Learned to Stop Worrying and Love the Bomb',
        year: 1964,
    },
    { label: 'The Great Dictator', year: 1940 },
    { label: 'Cinema Paradiso', year: 1988 },
    { label: 'The Lives of Others', year: 2006 },
    { label: 'Grave of the Fireflies', year: 1988 },
    { label: 'Paths of Glory', year: 1957 },
    { label: 'Django Unchained', year: 2012 },
    { label: 'The Shining', year: 1980 },
    { label: 'WALL·E', year: 2008 },
    { label: 'American Beauty', year: 1999 },
    { label: 'The Dark Knight Rises', year: 2012 },
    { label: 'Princess Mononoke', year: 1997 },
    { label: 'Aliens', year: 1986 },
    { label: 'Oldboy', year: 2003 },
    { label: 'Once Upon a Time in America', year: 1984 },
    { label: 'Witness for the Prosecution', year: 1957 },
    { label: 'Das Boot', year: 1981 },
    { label: 'Citizen Kane', year: 1941 },
    { label: 'North by Northwest', year: 1959 },
    { label: 'Vertigo', year: 1958 },
    {
        label: 'Star Wars: Episode VI - Return of the Jedi',
        year: 1983,
    },
    { label: 'Reservoir Dogs', year: 1992 },
    { label: 'Braveheart', year: 1995 },
    { label: 'M', year: 1931 },
    { label: 'Requiem for a Dream', year: 2000 },
    { label: 'Amélie', year: 2001 },
    { label: 'A Clockwork Orange', year: 1971 },
    { label: 'Like Stars on Earth', year: 2007 },
    { label: 'Taxi Driver', year: 1976 },
    { label: 'Lawrence of Arabia', year: 1962 },
    { label: 'Double Indemnity', year: 1944 },
    {
        label: 'Eternal Sunshine of the Spotless Mind',
        year: 2004,
    },
    { label: 'Amadeus', year: 1984 },
    { label: 'To Kill a Mockingbird', year: 1962 },
    { label: 'Toy Story 3', year: 2010 },
    { label: 'Logan', year: 2017 },
    { label: 'Full Metal Jacket', year: 1987 },
    { label: 'Dangal', year: 2016 },
    { label: 'The Sting', year: 1973 },
    { label: '2001: A Space Odyssey', year: 1968 },
    { label: "Singin' in the Rain", year: 1952 },
    { label: 'Toy Story', year: 1995 },
    { label: 'Bicycle Thieves', year: 1948 },
    { label: 'The Kid', year: 1921 },
    { label: 'Inglourious Basterds', year: 2009 },
    { label: 'Snatch', year: 2000 },
    { label: '3 Idiots', year: 2009 },
    { label: 'Monty Python and the Holy Grail', year: 1975 },
];

const steps = [
    'Select master blaster campaign settings',
    'Create an ad group',
    'Create an ad',
];

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
    width: 400,
    bgcolor: 'background.paper',
    border: '2px solid #000',
    boxShadow: 24,
    p: 4,
};

const label = { inputProps: { 'aria-label': 'Checkbox demo' } };

const Index = () => {
    const [open, setOpen] = React.useState(false);
    const handleOpen = () => setOpen(true);
    const handleClose = () => setOpen(false);
    const [value, setValue] = React.useState(0);

    const handleChange = (event, newValue) => {
        setValue(newValue);
    };

    const handleClickOpen = () => {
        setOpen(true);
    };

    const [successOpen, setSuccessOpen] = useState(false);
    const [warningOpen, setWarningOpen] = useState(false);
    const [errorOpen, setErrorOpen] = useState(false);

    // Handlers for AlertMaterial
    const handleOpenSuccess = () => setSuccessOpen(true);
    const handleOpenWarning = () => setWarningOpen(true);
    const handleOpenError = () => setErrorOpen(true);

    const handleCloseSuccess = () => setSuccessOpen(false);
    const handleCloseWarning = () => setWarningOpen(false);
    const handleCloseError = () => setErrorOpen(false);

    return (
        <LocalizationProvider dateAdapter={AdapterDayjs}>
        <Grid className="container-fluid px-2">
            <Item>
                <Box sx={{ flexGrow: 1 }}>
                    <AppBar position="static">
                        <Toolbar>
                            <IconButton
                                size="large"
                                edge="start"
                                color="inherit"
                                aria-label="menu"
                                sx={{ mr: 2 }}
                            >
                                <DisplaySettings />
                            </IconButton>
                            <Typography variant="h6" component="div" sx={{ flexGrow: 1 }}>
                                Material UI POC
                            </Typography>
                        </Toolbar>
                    </AppBar>
                </Box>
            </Item>

            <Grid>
                <Item>
                    <h3>Data grid</h3>
                </Item>
                <Grid item xs={12}>
                    <div style={{ height: 400, width: '100%' }}>
                        <DataGrid
                            rows={rows}
                            columns={columns}
                            initialState={{
                                pagination: {
                                    paginationModel: { page: 0, pageSize: 5 },
                                },
                            }}
                            slots={{ toolbar: GridToolbar }}
                            pageSizeOptions={[5, 10]}
                            checkboxSelection
                        />
                    </div>
                </Grid>
            </Grid>

            <Grid container>
                <Grid item xs={12}>
                    <Item>
                        <h3>Dense table</h3>
                    </Item>
                </Grid>
                <Grid item xs={6}>
                    <TableContainer component={Paper}>
                        <Table sx={{ minWidth: 650 }} size="small" aria-label="a dense table">
                            <TableHead>
                                <TableRow>
                                    <TableCell>Dessert (100g serving)</TableCell>
                                    <TableCell align="right">Calories</TableCell>
                                    <TableCell align="right">Fat&nbsp;(g)</TableCell>
                                    <TableCell align="right">Carbs&nbsp;(g)</TableCell>
                                    <TableCell align="right">Protein&nbsp;(g)</TableCell>
                                </TableRow>
                            </TableHead>
                            <TableBody>
                                {rows2.map((row) => (
                                    <TableRow
                                        key={row.name}
                                        sx={{ '&:last-child td, &:last-child th': { border: 0 } }}
                                    >
                                        <TableCell component="th" scope="row">
                                            {row.name}
                                        </TableCell>
                                        <TableCell align="right">{row.calories}</TableCell>
                                        <TableCell align="right">{row.fat}</TableCell>
                                        <TableCell align="right">{row.carbs}</TableCell>
                                        <TableCell align="right">{row.protein}</TableCell>
                                    </TableRow>
                                ))}
                            </TableBody>
                        </Table>
                    </TableContainer>
                </Grid>
            </Grid>

            <Grid>
                <Item>
                    <h3>Datetime pickers</h3>
                </Item>
            </Grid>
            <Grid container spacing={1}>
                <Grid item xs={12}>
                    <h4>Date picker</h4>
                    <Item>
                        <DatePicker label="Basic date picker" />
                    </Item>
                </Grid>
                <Grid item xs={12}>
                    <h4>Time picker</h4>
                    <Item>
                        <TimePicker label="Basic time picker" />
                    </Item>
                </Grid>
                <Grid item xs={12}>
                    <h4>Mobile picker</h4>
                    <Item>
                        <DemoItem label="Mobile variant">
                            <MobileDatePicker defaultValue={dayjs('2023-12-12')} />
                        </DemoItem>
                    </Item>
                </Grid>
                <Grid item xs={12}>
                    <h4>Datetime picker</h4>
                    <Item>
                        <DateTimePicker label="Basic date time picker" />
                    </Item>
                </Grid>
            </Grid>

            <Grid>
                <Item>
                    <h3>Grid - responsive</h3>
                </Item>
            </Grid>
            <Grid container spacing={1}>
                <Grid item xs={8} border={1}>
                    <Item>xs=8</Item>
                </Grid>
                <Grid item xs={4} border={1}>
                    <Item>xs=4</Item>
                </Grid>
                <Grid item xs={4} border={1}>
                    <Item>xs=4</Item>
                </Grid>
                <Grid item xs={8} border={1}>
                    <Item>xs=8</Item>
                </Grid>
            </Grid>

            <Grid container spacing={1}>
                <Grid item xs={12}>
                    <Item>
                        <h3>Buttons</h3>
                    </Item>
                </Grid>
                <Grid item xs={12}>
                    <ButtonGroup variant="contained" aria-label="outlined primary button group">
                        <Button>One</Button>
                        <Button>Two</Button>
                        <Button>Three</Button>
                    </ButtonGroup>
                </Grid>
                <Grid item xs={12}>
                    <Button variant="text">Text</Button>
                    <Button variant="contained">Contained</Button>
                    <Button variant="contained" size="large">Large button</Button>
                    <Button variant="outlined">Outlined</Button>
                    <Button variant="outlined" size="small">Small button</Button>
                    <Button component="label" color="secondary" variant="contained" startIcon={<CloudUploadIcon />}>
                        Upload file
                        <VisuallyHiddenInput type="file" />
                    </Button>
                </Grid>
            </Grid>

            <Grid>
                <Item>
                    <h3>Stepper</h3>
                </Item>
                <Grid item xs={12}>
                    <Box sx={{ width: '100%' }}>
                        <Stepper activeStep={1} alternativeLabel>
                            {steps.map((label) => (
                                <Step key={label}>
                                    <StepLabel>{label}</StepLabel>
                                </Step>
                            ))}
                        </Stepper>
                    </Box>
                </Grid>
            </Grid>


            <Grid>
                <Item>
                    <h3>Tabs</h3>
                </Item>
                <Grid item xs={12}>
                    <Box sx={{ width: '100%' }}>
                        <Box sx={{ borderBottom: 1, borderColor: 'divider' }}>
                            <Tabs value={value} onChange={handleChange} aria-label="basic tabs example">
                                <Tab label="Item One" {...a11yProps(0)} />
                                <Tab label="Item Two" {...a11yProps(1)} />
                                <Tab label="Item Three" {...a11yProps(2)} />
                            </Tabs>
                        </Box>
                        <CustomTabPanel value={value} index={0}>
                            Curabitur at aliquet quam. Cras mollis consectetur ipsum vel accumsan. Pellentesque dictum augue nunc, ut pellentesque est vulputate blandit. Mauris eu rhoncus lacus. Sed venenatis vel diam et bibendum. Nam accumsan felis non nibh cursus, eget porta quam tristique. Nam vitae neque rutrum, varius ligula sit amet, molestie ipsum.                         </CustomTabPanel>
                        <CustomTabPanel value={value} index={1}>
                            Donec quis ornare tellus. Nam nec diam eget dolor bibendum gravida eu in leo. Quisque vitae velit eu enim viverra sagittis. Nunc euismod nisi nunc, a tincidunt diam pellentesque non. Nam dignissim tincidunt ex sit amet mollis. Nulla facilisi. Proin nibh leo, malesuada id tellus eu, aliquam aliquet tellus. Nullam porttitor lobortis dictum.                         </CustomTabPanel>
                        <CustomTabPanel value={value} index={2}>
                            Maecenas sit amet sollicitudin libero. Phasellus ligula arcu, sodales et magna vel, dictum elementum tellus. Curabitur aliquam diam in ante suscipit congue. Nunc id augue et felis pharetra euismod. Phasellus blandit congue ipsum, id molestie nibh tempus vitae. Nam eros lorem, gravida eu nunc in, scelerisque vestibulum velit. Maecenas vel nibh nunc. Nunc nisl neque, sollicitudin in nisi at, consequat pretium velit. In non velit neque.                         </CustomTabPanel>
                    </Box>
                </Grid>
            </Grid>

            <Grid>
                <Item>
                    <h3>Alerts</h3>
                </Item>
                <Grid item xs={12}>
                    <Alert severity="error">
                        <AlertTitle>Error</AlertTitle>
                        This is an error alert — <strong>check it out!</strong>
                    </Alert>
                    <Alert severity="warning">
                        <AlertTitle>Warning</AlertTitle>
                        This is a warning alert — <strong>check it out!</strong>
                    </Alert>
                    <Alert severity="info">
                        <AlertTitle>Info</AlertTitle>
                        This is an info alert — <strong>check it out!</strong>
                    </Alert>
                    <Alert severity="success">
                        <AlertTitle>Success</AlertTitle>
                        This is a success alert — <strong>check it out!</strong>
                    </Alert>
                </Grid>
            </Grid>

            <Grid>
                <Item>
                    <h3>Modal</h3>
                </Item>
                <React.Fragment>
                    <Button color="info" variant="contained" startIcon={<DialpadIcon />} onClick={handleClickOpen}>
                        Slide in alert dialog
                    </Button>
                    <Dialog
                        open={open}
                        TransitionComponent={Transition}
                        keepMounted
                        onClose={handleClose}
                        aria-describedby="alert-dialog-slide-description"
                    >
                        <DialogTitle>{"Use Google's location service?"}</DialogTitle>
                        <DialogContent>
                            <DialogContentText id="alert-dialog-slide-description">
                                Let Google help apps determine location. This means sending anonymous
                                location data to Google, even when no apps are running.
                            </DialogContentText>
                        </DialogContent>
                        <DialogActions>
                            <Button onClick={handleClose}>Disagree</Button>
                            <Button onClick={handleClose}>Agree</Button>
                        </DialogActions>
                    </Dialog>
                </React.Fragment>
            </Grid>

            {/* Buttons to trigger different types of alerts */}
            <Grid item xs={12} sx={{ my: 2 }}> {/* Adding vertical margin to the entire Grid item */}
                <Button
                  variant="contained"
                  color="success"
                  onClick={handleOpenSuccess}
                  sx={{ mr: 1 }} // Adding right margin to individual buttons
                >
                    Show Success Alert
                </Button>
                <Button
                  variant="contained"
                  color="warning"
                  onClick={handleOpenWarning}
                  sx={{ mr: 1 }} // Adding right margin to individual buttons
                >
                    Show Warning Alert
                </Button>
                <Button
                  variant="contained"
                  color="error"
                  onClick={handleOpenError}
                >
                    Show Error Alert
                </Button>
            </Grid>


            {/* AlertMaterial components for each type */}
            <AlertMaterial type="success" open={successOpen} handleClose={handleCloseSuccess} />
            <AlertMaterial type="warning" open={warningOpen} handleClose={handleCloseWarning} />
            <AlertMaterial type="error" open={errorOpen} handleClose={handleCloseError} />

            <Grid>
                <Item>
                    <h3>Checkbox</h3>
                </Item>
                <Grid item xs={12}>
                    <FormGroup>
                        <FormControlLabel control={<Checkbox defaultChecked />} label="Label" />
                        <FormControlLabel required control={<Checkbox />} label="Required" />
                        <FormControlLabel disabled control={<Checkbox />} label="Disabled" />
                    </FormGroup>
                </Grid>
            </Grid>

            <Grid>
                <Item>
                    <h3>Dropdown with filtering</h3>
                </Item>
                <Grid item xs={12}>
                    <Autocomplete
                        disablePortal
                        id="combo-box-demo"
                        options={top100Films}
                        sx={{ width: 300 }}
                        renderInput={(params) => <TextField {...params} label="Movie" />}
                    />
                </Grid>
            </Grid>

            <Grid>
                <Item>
                    <h3>Textfield</h3>
                </Item>
                <Grid item xs={12}>
                    <Box sx={{ '& > :not(style)': { m: 1 } }}>
                        <FormControl variant="standard">
                            <InputLabel htmlFor="input-with-icon-adornment">
                                With a start adornment
                            </InputLabel>
                            <Input
                                id="input-with-icon-adornment"
                                startAdornment={
                                    <InputAdornment position="start">
                                        <AccountCircle />
                                    </InputAdornment>
                                }
                            />
                        </FormControl>
                        <TextField
                            id="input-with-icon-textfield"
                            label="TextField"
                            InputProps={{
                                startAdornment: (
                                    <InputAdornment position="start">
                                        <AccountCircle />
                                    </InputAdornment>
                                ),
                            }}
                            variant="standard"
                        />
                        <Box sx={{ display: 'flex', alignItems: 'flex-end' }}>
                            <AccountCircle sx={{ color: 'action.active', mr: 1, my: 0.5 }} />
                            <TextField id="input-with-sx" label="With sx" variant="standard" />
                        </Box>
                    </Box>
                </Grid>
            </Grid>

            <Grid>
                <Item>
                    <h3>Badge & Avatar</h3>
                </Item>
                <Grid item xs={12}>
                    <Badge badgeContent={4} color="secondary">
                        <MailIcon color="action" />
                    </Badge>
                    <Badge badgeContent={4} color="success">
                        <MailIcon color="action" />
                    </Badge>
                    <IconButton aria-label="cart">
                        <StyledBadge badgeContent={1} color="secondary">
                            <ShoppingCartIcon />
                        </StyledBadge>
                    </IconButton>
                </Grid>
                <Grid item xs={12}>
                    <Chip avatar={<Avatar>M</Avatar>} label="Avatar" />
                </Grid>
            </Grid>

            <Grid>
                <Item>
                    <h3>Material Icons</h3>
                </Item>
                <Grid item xs={12}>
                    <MailIcon />
                    <HealingIcon />
                    <MedicalInformationIcon color="success" />
                    <MonitorHeartIcon color="primary" />
                    <DeleteIcon color="warning" />
                    <Item>
                        <Link href="https://mui.com/material-ui/material-icons/" underline="hover" target="_blank" rel="noopener">
                            Totally there are 2,126 icons, click here
                        </Link>
                    </Item>
                </Grid>
            </Grid>

            <Grid>
                <Item>
                    <h3>Slider</h3>
                </Item>
                <Grid item xs={12}>
                    <Box sx={{ width: 300 }}>
                        <Slider
                            aria-label="Custom marks"
                            defaultValue={20}
                            getAriaValueText={valuetext}
                            step={10}
                            valueLabelDisplay="auto"
                            marks={marks}
                        />
                    </Box>
                </Grid>
            </Grid>

        </Grid>
        </LocalizationProvider>
    );
}

export default Index;
