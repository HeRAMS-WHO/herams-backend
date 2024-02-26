import {MapContainer, TileLayer, useMap, ZoomControl} from 'react-leaflet'
import Grid from "@mui/material/Grid";
import HomeIcon from '@mui/icons-material/Home';
import AccountCircleIcon from '@mui/icons-material/AccountCircle';
import FavoriteIcon from '@mui/icons-material/Favorite';
import HelpOutlineIcon from '@mui/icons-material/HelpOutline';
import LogoutIcon from '@mui/icons-material/Logout';
import SettingsIcon from '@mui/icons-material/Settings';
import PublicIcon from '@mui/icons-material/Public';
import AddIcon from '@mui/icons-material/Add';
import replaceVariablesAsText from '../../utils/replaceVariablesAsText'
import Item from "@mui/material/Grid";
import Accordion from '@mui/material/Accordion';
import AccordionSummary from '@mui/material/AccordionSummary';
import AccordionDetails from '@mui/material/AccordionDetails';
import List from '@mui/material/List';
import ListItem from '@mui/material/ListItem';
import ListItemButton from '@mui/material/ListItemButton';
import ListItemText from '@mui/material/ListItemText';
import ExpandMoreIcon from '@mui/icons-material/ExpandMore';
import {Box, Divider, ListItemIcon, Typography} from "@mui/material";
import {useEffect, useState} from 'react';
import L from 'leaflet';
import {getProjectsMap} from "../../services/apiProxyService";

const ProjectsList = ({ projects }) => (
    <List sx={{minHeight: '75vh'}}>
        {projects.map((project, index) => (
            <ListItem disablePadding key={index}>
                <ListItemButton href={replaceVariablesAsText(`/admin/project/`)+project.id}>
                    <ListItemText primary={project.name} />
                </ListItemButton>
            </ListItem>
        ))}
        <Divider />
        <ListItem disablePadding>
            <ListItemButton href={replaceVariablesAsText(`/admin/project/create`)}>
                <ListItemIcon>
                    <AddIcon />
                </ListItemIcon>
                <ListItemText primary="New project" />
            </ListItemButton>
        </ListItem>
    </List>
);

const ProjectMarker = ({ projects }) => {
    const map = useMap();

    useEffect(() => {
        projects.forEach(({ position, number, content, name }) => {
            const projectIcon = L.divIcon({
                className: 'custom-icon',
                html: `<div class="numbered-circle">${number}</div>`,
                iconAnchor: [12, 12],
            });

            const customPopupContent = `
                <div class="leaflet-popup-content-header">${name}</div>
                <div class="leaflet-popup-content-body">${content}</div>
            `;

            L.marker(position, { icon: projectIcon })
                .addTo(map)
                .bindPopup(customPopupContent);
        });

        // Cleanup function to remove all layers (markers and circles) when component unmounts or locations change
        return () => {
            map.eachLayer((layer) => {
                if (layer instanceof L.Marker || layer instanceof L.Circle) {
                    map.removeLayer(layer);
                }
            });
        };
    }, [projects, map]);

    return null;
};

const HomeIndex = () => {

    const [projects, setProjects] = useState([]);

    useEffect(() => {
        // Simulate fetching data with AJAX call
        const fetchData = async () => {
            const projectList = await getProjectsMap();
            setProjects(projectList);
        };

        fetchData();
    }, []);


    return (
        <Grid container>
            <Grid item xs={4} sm={4} md={7} lg={8} position={"relative"} zIndex={5000}>
                <Grid item xs={12} sm={12} md={5} lg={3} position={"absolute"}>
                    <Accordion>
                        <AccordionSummary
                            expandIcon={<ExpandMoreIcon fontSize={"large"} />}
                            aria-controls="herams-content"
                            id="herams-header"
                        >
                            <img
                                srcSet="https://v2.herams-staging.org/img/HeRAMS.svg"
                                src="https://v2.herams-staging.org/img/HeRAMS.svg"
                                alt="HeRAMs"
                                style={{height: '5vh', mt: 3}}
                                loading="lazy" />
                        </AccordionSummary>
                        <AccordionDetails sx={{height: '90vh'}}>
                            <Divider />
                            <ProjectsList projects={projects} />
                            <Box>
                                <Typography>
                                    Place carousel here Place carousel here Place carousel here
                                </Typography>
                            </Box>
                        </AccordionDetails>
                    </Accordion>
                </Grid>
            </Grid>
            <Grid item xs={8} sm={8} md={5} lg={4} zIndex={600} sx={{mt: 3, pr: 2, display: 'flex', justifyContent: 'flex-end'}}>
                <Item>
                    <Link to={replaceVariablesAsText(`/admin/project`)}>
                        <SettingsIcon fontSize={"large"} />
                    </Link>
                    <Link to={replaceVariablesAsText(`/home`)}>
                        <HomeIcon fontSize={"large"} />
                    </Link>
                    <Link to={replaceVariablesAsText(`/user/favorites`)}>
                        <FavoriteIcon fontSize={"large"} />
                    </Link>
                    <Link to={replaceVariablesAsText(`/user/profile`)}>
                        <AccountCircleIcon fontSize={"large"} />
                    </Link>
                    <Link to="https://docs.herams.org/" target="_blank">
                        <HelpOutlineIcon fontSize={"large"} />
                    </Link>
                    <Link to={replaceVariablesAsText(`/auth/logout`)}>
                        <LogoutIcon fontSize={"large"} />
                    </Link>
                    <Link to={replaceVariablesAsText(`/user/profile`)} >
                        <PublicIcon fontSize={"large"} sx={{ml: 2}}/> EN <ExpandMoreIcon />
                    </Link>
                </Item>
            </Grid>
            <MapContainer center={[8.6753, 9.082]} zoom={5.4} minZoom={3} maxZoom={5} zoomControl={false}
                          style={{height: "100vh", width: "100vw", position: "absolute"}}>
                <ZoomControl position={'bottomright'}></ZoomControl>
                <TileLayer
                    url="https://services.arcgisonline.com/arcgis/rest/services/Canvas/World_Light_Gray_Base/MapServer/tile/{z}/{y}/{x}"
                />
                <ProjectMarker projects={projects} />
            </MapContainer>
        </Grid>
    )
}

export default HomeIndex
