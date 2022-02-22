yii.confirm = function (message, ok, cancel) {

    iziToast.question({
        message: message,
        overlay: true,
        overlayClose: true,
        color: 'red',
        closeOnEscape: true,
        position: "center",
        buttons: [
            ['<button>Yes</button>', (instance, toast) => {
                instance.hide({}, toast, 'yes');
        }],
            ['<button>No</button>', (instance, toast) => {
                instance.hide({}, toast, 'no');
        }],
        ],
        onClosed: (instance, toast, closedBy) => {
            if (closedBy === 'yes') {
                !ok || ok();
            } else {
                !cancel || cancel();
            }
        }
    });
    return false;
}

