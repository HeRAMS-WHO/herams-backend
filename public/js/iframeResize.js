$(function(){
    var $iframe = $("iframe.resize");
    var resizer = function(e) {
        $iframe.height($iframe.contents().find("body").height());
        $iframe.width($iframe.contents().find("body").width());
    };

    $iframe.on("load", function() {
        var $body = $iframe.contents().find("body");
        $body.on("mresize", resizer);
        $body.trigger("mresize");
    });
});