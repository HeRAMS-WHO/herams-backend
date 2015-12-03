function select(point, layer) {
    var id = point.id;
    $.ajax({
        url: '/marketplace/summary',
        data: {id: id, layer: layer}
    })
    .success(function(body) {
        bootbox.alert({
            message: body
        });
    });
}