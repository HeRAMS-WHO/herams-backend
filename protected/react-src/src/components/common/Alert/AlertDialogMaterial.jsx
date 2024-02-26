import React, { useEffect, useState } from 'react';
import { Dialog, DialogActions, DialogContent, DialogTitle, Slide, Alert, Button, LinearProgress } from '@mui/material';

const Transition = React.forwardRef(function Transition(props, ref) {
    return <Slide direction="up" ref={ref} {...props} />;
});

const AlertDialogMaterial = ({ open, handleClose, title, children, duration = 5000 }) => {
    const [progress, setProgress] = useState(0);

    useEffect(() => {
        let timer;
        if (open) {
            setProgress(0); // Reset progress on open
            timer = setInterval(() => {
                setProgress((oldProgress) => {
                    const diff = 100 / (duration / 100); // Increment based on duration
                    return Math.min(oldProgress + diff, 100);
                });
            }, 100);
        }

        return () => {
            clearInterval(timer);
        };
    }, [open, duration]);

    useEffect(() => {
        // Close the dialog when progress hits 100%
        if (progress >= 100) {
            handleClose();
            setProgress(0); // Reset progress for next time
        }
    }, [progress, handleClose]);

    return (
        <Dialog
            open={open}
            TransitionComponent={Transition}
            keepMounted
            onClose={handleClose}
            aria-describedby="alert-dialog-slide-description"
        >
            <DialogTitle>{title}</DialogTitle>
            <DialogContent>
                {children}
                <LinearProgress variant="determinate" value={progress} />
            </DialogContent>
            <DialogActions>
                <Button onClick={handleClose}>Close</Button>
            </DialogActions>
        </Dialog>
    );
};
export default AlertDialogMaterial;