<?php
foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
    if (!is_array($message)) {
        $type = $key;
        $body = $message;
    } else {
        $type = $message['type'];
        $body = $message['text'];
    }
    $config = [
        'type' => $type,
        'title' => isset($message['title']) ? $message['title'] : null,
        'body' => $body,
        'showSeparator' => true,
        'delay' => isset($message['delay']) ? $message['delay'] : 1000,
        'pluginOptions' => \yii\helpers\ArrayHelper::merge([
            'placement' => [
                'from' => 'top',
                'align' => 'right'
            ],
            'offset' => [
                'x' => 5,
                'y' => 55
            ]
        ], isset($message['pluginOptions']) ? $message['pluginOptions'] : [])
    ];
    !isset($message['title']) ?: $config['title'] = $message['title'];
    !isset($message['icon']) ?: $config['icon'] = $message['icon'];
    echo \kartik\growl\Growl::widget($config);
}