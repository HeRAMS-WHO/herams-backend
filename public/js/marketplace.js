function select(point, layer) {
    //$('#{$map->getId()}').removeClass('col-md-12').addClass('col-md-9');
    //$('#{$map->getId()}').highcharts().reflow();
    //$('#map-details').removeClass('col-xs-0').removeClass('col-md-0').addClass('col-md-3').addClass('col-xs-12');
    var id = point.id;
    $.ajax({
        url: '/marketplace/summary',
        data: {id: id, layer: layer}
    })
    .success(function(body) {
        bootbox.alert({
            message: body
        });
        //$('#map-details').html(data);
    });
}