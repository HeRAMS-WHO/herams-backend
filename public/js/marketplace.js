function select(point, layer) {
    var id = point.id;
    $.ajax({
        url: '/marketplace/summary',
        data: {id: id, layer: layer}
    })
    .success(function(body) {
        bootbox.dialog({
            message: body,
            buttons: [
                {
                    label: '<span class="glyphicon glyphicon-new-window"></span>',
                    className: "btn-default",
                    callback: function() {
                        window.open('/marketplace/summary?layer=' + layer + '&id=' + id);
                    }
                },
                {
                    label: '<span class="glyphicon glyphicon-ok"></span>',
                    className: "btn-primary",

                }
            ]
        });
    });
}