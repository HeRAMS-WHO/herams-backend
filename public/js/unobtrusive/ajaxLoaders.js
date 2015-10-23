/*


 */

(function($){

        //class: 'glyphicon glyphicon-refresh',
        //tpl: '<span class="isloading-wrapper %wrapper%">%text% <span class="%class% ajaxLoaders-spin"></span></span>'

    function showWindowOverlay($source, text)
    {
        $.isLoading({
            text: text,
            class: 'glyphicon glyphicon-refresh',
            tpl: '<span class="isloading-wrapper %wrapper%">%text%<span class="%class% ajaxLoaders-spin"></span></span>'
        });
    }

    function hideWindowOverlay($source)
    {
        $.isLoading( "hide" );
    }

    $(function(){
        //$.isLoading().defaults.class = 'glyphicon glyphicon-refresh';
        //$.isLoading().defaults.tpl = '<span class="isloading-wrapper %wrapper%">%text% <span class="%class% ajaxLoaders-spin"></span></span>';

        $(document).ajaxSend(function(e, jqXhr, ajaxSettings) {
            if (typeof(ajaxSettings.source) != 'undefined') {
                var $source = $(ajaxSettings.source);
                if(typeof($source.attr('data-loader')) != 'undefined') {
                    var text = typeof($source.attr('data-loader-text')) != 'undefined' ? $source.attr('data-loader-text') : '';
                    switch($source.attr('data-loader'))
                    {
                        case 'overlay-window':
                            showWindowOverlay($source, text);
                            break;
                    }
                }
            }
        });

        $(document).ajaxComplete(function(e, jqXhr, ajaxSettings) {
            if (typeof(ajaxSettings.source) != 'undefined') {
                var $source = $(ajaxSettings.source);
                if(typeof($source.attr('data-loader')) != 'undefined') {
                    switch($source.attr('data-loader'))
                    {
                        case 'overlay-window':
                            hideWindowOverlay($source);
                            break;
                    }
                }
            }
        });
    });
})(jQuery);