(function($) {
    $(function(){
        $(document).on('shown.bs.tab', '[data-toggle="tab"]', function(e) {
            $($(e.target).attr('href')).find('div[data-highcharts-chart]').each(function(key, chart) {
                $(chart).highcharts().reflow();
            });
        });
    })
})(jQuery);