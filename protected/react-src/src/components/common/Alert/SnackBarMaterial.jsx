// CustomSnackbarWithProgress.jsx
import React, { useEffect, useState } from 'react';
import { Snackbar, Alert, LinearProgress } from '@mui/material';

const SnackBarMaterial = ({ open, handleClose, message, severity, duration = 6000 }) => {
    const [progress, setProgress] = useState(0);
    const [internalOpen, setInternalOpen] = useState(false);

    useEffect(() => {
        if (open) {
            setProgress(0); // Reset progress to 0 immediately
            setInternalOpen(true);
        } else {
            setInternalOpen(false); // Close the snackbar immediately when open is set to false
        }
    }, [open]);

    useEffect(() => {
        // Only start the timer if the snackbar is internally open
        if (internalOpen) {
            const timer = setInterval(() => {
                setProgress((prevProgress) => {
                    const delta = 100 / (duration / 100); // Calculate progress increment
                    const newProgress = prevProgress + delta;
                    if (newProgress >= 100) {
                        clearInterval(timer); // Stop the timer if progress is full
                        return 100;
                    }
                    return newProgress;
                });
            }, 100); // Update progress every 100ms

            return () => clearInterval(timer); // Cleanup timer on unmount
        }
    }, [internalOpen, duration]);

    return (
        <Snackbar open={internalOpen} autoHideDuration={duration} onClose={() => {
            handleClose();
            setInternalOpen(false); // Ensure internal state is reset on close
        }}>
            <Alert onClose={handleClose} severity={severity} sx={{ width: '100%' }} variant="filled">
                {message}
                <LinearProgress variant="determinate" value={progress} />
            </Alert>
        </Snackbar>
    );
};

export default SnackBarMaterial;
