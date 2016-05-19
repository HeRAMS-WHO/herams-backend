$(function(){

    var resizer = function($iframe) {
        var h = $iframe.contents().height();
        var w = $iframe.contents().width()
        console.log("Height: " + h);
        console.log("Width: " + w);
        // debugger;
        if ($iframe.attr('data-resize') === 'height') {
            $iframe.height(h);
            return;
        }

        $iframe.height(Math.max(h, 100));
        $iframe.width(Math.max(w, 100));
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