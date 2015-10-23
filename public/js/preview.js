var $iframe = $('iframe#preview');
$('#save_preview').click(function(){
    $.ajax({
        type: "POST",
        data: $iframe.contents().find(':input').serialize(),
        source: $(this)
    })
    .success(function(data, response){
        $iframe.attr( 'src', function ( i, val ) { return val; });
    })
    .error(function(data, response) {
        $('#response').html(data);
    })
    ;
});

$('#publish_preview').click(function(e){
    $button = $(this);
    e.preventDefault();

    $.ajax({
        type: "POST",
        data: $iframe.contents().find(':input').serialize()
    })
    .success(function(data, response){
        window.location = $button.attr('href');
    })
    .error(function(data, response) {
        $('#response').html(data);
    })
    ;
});