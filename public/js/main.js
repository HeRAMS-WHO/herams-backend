yii.confirm = function (message, ok, cancel) {

    bootbox.confirm(
        {
            message: message,
            buttons: {
                confirm: {
                    label: "OK"
                },
                cancel: {
                    label: "Cancel"
                }
            },
            callback: function (confirmed) {
                if (confirmed) {
                    !ok || ok();
                } else {
                    !cancel || cancel();
                }
            }
        }
    );
    // confirm will always return false on the first call
    // to cancel click handler
    return false;
}

