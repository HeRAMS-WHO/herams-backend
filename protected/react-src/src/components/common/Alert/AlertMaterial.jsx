import React from 'react';
import { Dialog, DialogActions, DialogContent, DialogTitle, Slide, Alert, Button } from '@mui/material';

const Transition = React.forwardRef(function Transition(props, ref) {
  return <Slide direction="up" ref={ref} {...props} />;
});

const AlertMaterial = ({
  type = 'success',
  open,
  handleClose,
  showButton = false,
  buttonName = '',
  alertTitle = '',
  alertMessage = ''
}) => {

  const defaultContents = {
    success: {
      title: "Success",
      message: "This is a success alert — check it out!",
      buttonName: "Show Success Alert"
    },
    warning: {
      title: "Warning",
      message: "This is a warning alert — check it out!",
      buttonName: "Show Warning Alert"
    },
    error: {
      title: "Error",
      message: "This is an error alert — check it out!",
      buttonName: "Show Error Alert"
    }
  };

  const content = defaultContents[type];
  const finalTitle = alertTitle || content.title;
  const finalMessage = alertMessage || content.message;
  const finalButtonName = buttonName || content.buttonName;

  return (
    <div>
      {showButton && (
        <Button onClick={handleClose}>{finalButtonName}</Button> // Changed to handleClose for consistency
      )}

      <AlertDialog open={open} handleClose={handleClose} title={finalTitle}>
        <Alert severity={type}>{finalMessage}</Alert>
      </AlertDialog>
    </div>
  );
};

export default AlertMaterial;
