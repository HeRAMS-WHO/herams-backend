$(function(){

    var resizer = function($iframe) {
        console.log($iframe.contents().find("body").height());
        console.log($iframe.contents().find("body").width());
        // debugger;
        if ($iframe.attr('data-resize') === 'height') {
            $iframe.height($iframe.contents().find("body").height());
            return;
        }

        $iframe.height($iframe.contents().find("body").height());
        $iframe.width($iframe.contents().find("body").width());
    };

    $("iframe.resize").on("load", function() {
        console.log("Watching iframe.");
        var $body = $(this).contents().find("body");
        var $this = $(this);
        $body.on("mresize", function() {
            resizer($this);
        });
        $body.trigger("mresize");
    }).each(function(i, el) {
        setTimeout(function() {
            resizer($(el));
        }, 500);
    });




});