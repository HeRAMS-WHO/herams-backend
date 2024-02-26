import React from 'react';
import { Slide, Alert, Button } from '@mui/material';
import AlertDialogMaterial from "./AlertDialogMaterial";

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

      <AlertDialogMaterial open={open} handleClose={handleClose} title={finalTitle}>
        <Alert severity={type}>{finalMessage}</Alert>
      </AlertDialogMaterial>
    </div>
  );
};

export default AlertMaterial;
