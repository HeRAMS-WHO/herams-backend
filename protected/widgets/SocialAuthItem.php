<?php


namespace prime\widgets;

use app\components\Html;
use yii\authclient\widgets\AuthChoiceItem;

class SocialAuthItem extends AuthChoiceItem
{
    public function run()
    {
        $this->view->registerCss('.auth-clients { margin: 0px; padding: 0px; } .auth-client { float: none; margin-top: 10px; margin-right: 0px; }');
        $htmlOptions = [];
        if ($this->authChoice->popupMode) {
            $viewOptions = $this->client->getViewOptions();
            $htmlOptions['data-popup-width'] = isset($viewOptions['popupWidth']) ? $viewOptions['popupWidth'] : 860;
            $htmlOptions['data-popup-height'] = isset($viewOptions['popupHeight']) ? $viewOptions['popupHeight'] : 480;
        }

        $htmlOptions['class'] = 'btn btn-block btn-social btn-' . $this->client->getName();
        echo Html::a(
            Html::tag('span', '', ['class' => 'fa fa-' . $this->client->getName()]) . " " . \Yii::t('app', 'Sign in with') . ' '. $this->client->getTitle(),
            $this->authChoice->createClientUrl($this->client), $htmlOptions
        );

    }
}