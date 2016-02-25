function selectCountry(point, layer, id) {
    var urlId = typeof id !== 'undefined' ? '&id=' + id : '';
    var url = '/marketplace/country-dashboard?layer=' + layer + '&iso_3=' + point.iso_3 + urlId;
    showBootbox(url);
}

function selectGlobal(layer) {
    var url = '/marketplace/global-dashboard?layer=' + layer;
    showBootbox(url);
}

function selectHealthCluster(point){
    var layer = 'healthClusters';
    if(point.subnational) {
        var url = '/marketplace/health-cluster-dashboard?layer=' + layer + '&iso_3=' + point.iso_3 + '&id=' + point.id;
        showBootbox(url);
    } else {
        selectCountry(point, layer, point.id);
    }
}

function selectEvent(point, layer){
    var url = '/marketplace/event-dashboard?layer=' + layer + '&iso_3=' + point.iso_3 + '&id=' + point.id;
    showBootbox(url);
}

function showBootbox(url) {
    var search = window.location.search.substr(0,1) == "?" ? "&" + window.location.search.substr(1) : "";
    url = url + search;
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