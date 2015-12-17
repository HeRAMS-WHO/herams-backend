function select(point, layer) {
    var id = point.id;
    var url = '/marketplace/summary?layer=' + layer + '&id=' + id;
    //$.ajax({
    //    url: url,
    //    data: {id: id, layer: layer}
    //})
    //.success(function(body) {
    //    bootbox.dialog({
    //        message: body,
    //        buttons: [
    //            {
    //                label: '<span class="glyphicon glyphicon-new-window"></span>',
    //                className: "btn-default",
    //                callback: function() {
    //                    window.open('/marketplace/summary?layer=' + layer + '&id=' + id);
    //                }
    //            },
    //            {
    //                label: '<span class="glyphicon glyphicon-ok"></span>',
    //                className: "btn-primary",
    //
    //            }
    //        ]
    //    });
    //});


    bootbox.dialog({
        message: '<iframe src="' + url + '&noMenu=1' + '" style="width: 100%; height: 500px; border: 0px;"></iframe>',
        buttons: [
            {
                label: '<span class="glyphicon glyphicon-new-window"></span>',
                className: "btn-default",
                callback: function() {
                    window.open(url);
                }
            },
            {
                label: '<span class="glyphicon glyphicon-ok"></span>',
                className: "btn-primary",

            }
        ],
        size: 'large'
    });
}