<?php

use app\components\Html;

/**
 * @var string $previewUrl
 * @var \yii\web\View $this
 * @var string $reportGenerator
 * @var int $projectId
 */

$this->params['subMenu']['items'] = [
    [
        'label' => \Yii::t('app', 'Save'),
        'url' => '#',
        'linkOptions' => [
            'id' => 'save_preview'
        ]
    ],
    [
        'label' => \Yii::t('app', 'Publish'),
        'url' => [
            'reports/publish',
            'reportGenerator' => $reportGenerator,
            'projectId' => $projectId
        ],
        'linkOptions' => [
            'id' => 'publish_preview',

        ]
    ]
];

$js = <<<EOL
var \$iframe = $('iframe#preview');
$('#save_preview').click(function(){
    $.post(
        '',
        \$iframe.contents().find(':input').serialize()
    )
    .success(function(data, response){
        \$iframe.attr( 'src', function ( i, val ) { return val; });
    })
    .error(function(data, response) {
        $('#response').html(data);
    })
    ;
});

$('#publish_preview').click(function(e){
    \$button = $(this);
    e.preventDefault();

    $.post(
        '',
        \$iframe.contents().find(':input').serialize()
    )
    .success(function(data, response){
        window.location = \$button.attr('href');
    })
    .error(function(data, response) {
        $('#response').html(data);
    })

    ;
});
EOL;

$this->registerJs($js);

echo Html::tag('div', '', ['id' => 'response']);
echo Html::tag('iframe', '', ['src' => $previewUrl, 'style' => ['width' => '100%', 'height' => '500px'], 'id' => 'preview']);
