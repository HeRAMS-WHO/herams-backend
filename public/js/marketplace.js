function selectCountry(point, layer) {
    var iso_3 = point.iso_3;
    var search = window.location.search.substr(0,1) == "?" ? "&" + window.location.search.substr(1) : "";
    var url = '/marketplace/country-dashboard?layer=' + layer + '&iso_3=' + iso_3 + search;
    showBootbox(url);
}

function selectGlobal(layer) {
    var search = window.location.search.substr(0,1) == "?" ? "&" + window.location.search.substr(1) : "";
    var url = '/marketplace/global-dashboard?layer=' + layer + search;
    showBootbox(url);
}

function showBootbox(url) {
    bootbox.dialog({
        message: '<iframe src="' + url + '&popup=1' + '" style="width: 100%; height: 500px; border: 0px;"></iframe>',
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
        selectCountry({iso_3: $(this).attr('data-iso3')}, 'countries');
    })
});