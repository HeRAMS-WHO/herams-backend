yii.allowAction = function ($e) {
    var message = $e.data('confirm');
    return message === undefined || yii.confirm(message, $e);
};
//yii.confirm = function (message, $e) {
//    bootbox.confirm(message, function (confirmed) {
//        if (confirmed) {
//            yii.handleAction($e);
//        }
//    });
//    // confirm will always return false on the first call
//    // to cancel click handler
//    return false;
//}
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

//fix for kartik date-time-range filter
function apply_filter(){
    $('.grid-view').yiiGridView('applyFilter');
}