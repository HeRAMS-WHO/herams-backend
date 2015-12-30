function select(point, layer) {
    var iso_3 = point.iso_3;
    var url = '/marketplace/summary?layer=' + layer + '&iso_3=' + iso_3;
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

function hover(point, layer, eventIn) {
    var iso_3 = point.iso_3;
    eventIn = typeof(eventIn) == 'undefined' ? true : eventIn;

    if(eventIn) {
        $('.country-list-item[data-iso3="' + iso_3 + '"]').addClass('country-list-item-hover');
    } else {
        $('.country-list-item[data-iso3="' + iso_3 + '"]').removeClass('country-list-item-hover');
    }

}

$(function(){
    $('.country-list-item').click(function(){
        select({iso_3: $(this).attr('data-iso3')}, 'countries');
    })
});