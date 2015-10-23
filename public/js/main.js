yii.allowAction = function ($e) {
    var message = $e.data('confirm');
    return message === undefined || yii.confirm(message, $e);
};
yii.confirm = function (message, $e) {
    bootbox.confirm(message, function (confirmed) {
        if (confirmed) {
            yii.handleAction($e);
        }
    });
    // confirm will always return false on the first call
    // to cancel click handler
    return false;
}