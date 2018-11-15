<?php

namespace prime\models\forms;

use DateTime;
use prime\models\ar\Setting;
use prime\models\search\Project;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\validators\ExistValidator;
use yii\validators\RangeValidator;
use yii\validators\RequiredValidator;

/**
 * Class Settings
 * Model for all site-wide settings.
 * All attributes that are safe are settable.
 *
 * @package models\forms
 */
class Settings extends Model
{
    private $data = [];

    public function attributeLabels() {
        return [

        ];
    }

    public function getAttributeLabel($attribute)
    {
        return strtr(parent::getAttributeLabel($attribute), ['Icons ' => 'Icon: ']);
    }

    public function rules()
    {
        return [
            ['limeSurvey.host', 'url'],
            [['limeSurvey.password', 'limeSurvey.username'], RequiredValidator::class],
            [[
                'icons.projects',
                'icons.user',
                'icons.configuration',
                'icons.user',
                'icons.read',
                'icons.update',
                'icons.share',
                'icons.close',
                'icons.open',
                'icons.remove',
                'icons.request',
                'icons.download',
            ], RangeValidator::class, 'range' => array_keys($this->iconOptions())]
        ];
    }

    public function init() {
        foreach(Setting::find()->all() as $setting) {
            $this->data[$setting->key] = $setting->decodedValue;
        }
        foreach(app()->params['defaultSettings'] as $attribute => $value) {
            if(!array_key_exists($attribute, $this->data)) {
                $this->data[$attribute] = $value;
            }
        }

    }

    public function __get($name)
    {
        if ($this->isAttributeSafe($name)) {
            return isset($this->data[$name]) ? $this->data[$name] : null;
        } else {
            return parent::__get($name);
        }
    }

    public function __set($name, $value) {
        if ($this->isAttributeSafe($name)) {
            $this->data[$name] = $value;
        } else {
            parent::__set($name, $value);
        }
    }

    public function countryPolygonsFileOptions()
    {
        return
            ArrayHelper::map(
                FileHelper::findFiles(
                    \Yii::getAlias('@app/data/countryPolygons/'),
                    [
                        'recursive' => false,
                        'only' => ['*.json']
                    ]
                ),
                function ($file) {
                    return basename($file);
                },
                function ($file) {
                    return app()->formatter->asDatetime(
                        DateTime::createFromFormat('Y-m-d_H-i-s', basename($file, '.json')),
                        'full'
                    );
                }
            );
    }

    public function iconOptions()
    {
        return [
            'asterisk' => '&#x2a;',
            'plus' => '&#x2b;',
            'eur' => '&#x20ac;',
            'euro' => '&#x20ac;',
            'minus' => '&#x2212;',
            'cloud' => '&#x2601;',
            'pencil' => '&#x270f;',
            'glass' => '&#xe001;',
            'music' => '&#xe002;',
            'search' => '&#xe003;',
            'heart' => '&#xe005;',
            'star-empty' => '&#xe007;',
            'user' => '&#xe008;',
            'film' => '&#xe009;',
            'th-large' => '&#xe010;',
            'th' => '&#xe011;',
            'th-list' => '&#xe012;',
            'ok' => '&#xe013;',
            'remove' => '&#xe014;',
            'zoom-in' => '&#xe015;',
            'zoom-out' => '&#xe016;',
            'off' => '&#xe017;',
            'signal' => '&#xe018;',
            'cog' => '&#xe019;',
            'trash' => '&#xe020;',
            'file' => '&#xe022;',
            'time' => '&#xe023;',
            'road' => '&#xe024;',
            'download-alt' => '&#xe025;',
            'download' => '&#xe026;',
            'upload' => '&#xe027;',
            'inbox' => '&#xe028;',
            'play-circle' => '&#xe029;',
            'repeat' => '&#xe030;',
            'refresh' => '&#xe031;',
            'list-alt' => '&#xe032;',
            'flag' => '&#xe034;',
            'headphones' => '&#xe035;',
            'volume-off' => '&#xe036;',
            'volume-down' => '&#xe037;',
            'volume-up' => '&#xe038;',
            'qrcode' => '&#xe039;',
            'barcode' => '&#xe040;',
            'tag' => '&#xe041;',
            'tags' => '&#xe042;',
            'book' => '&#xe043;',
            'bookmark' => '&#xe044;',
            'print' => '&#xe045;',
            'camera' => '&#xe046;',
            'font' => '&#xe047;',
            'bold' => '&#xe048;',
            'italic' => '&#xe049;',
            'text-height' => '&#xe050;',
            'text-width' => '&#xe051;',
            'list' => '&#xe056;',
            'indent-left' => '&#xe057;',
            'indent-right' => '&#xe058;',
            'facetime-video' => '&#xe059;',
            'picture' => '&#xe060;',
            'map-marker' => '&#xe062;',
            'adjust' => '&#xe063;',
            'tint' => '&#xe064;',
            'edit' => '&#xe065;',
            'share' => '&#xe066;',
            'check' => '&#xe067;',
            'move' => '&#xe068;',
            'step-backward' => '&#xe069;',
            'fast-backward' => '&#xe070;',
            'backward' => '&#xe071;',
            'play' => '&#xe072;',
            'pause' => '&#xe073;',
            'stop' => '&#xe074;',
            'forward' => '&#xe075;',
            'fast-forward' => '&#xe076;',
            'step-forward' => '&#xe077;',
            'eject' => '&#xe078;',
            'chevron-left' => '&#xe079;',
            'chevron-right' => '&#xe080;',
            'plus-sign' => '&#xe081;',
            'minus-sign' => '&#xe082;',
            'remove-sign' => '&#xe083;',
            'ok-sign' => '&#xe084;',
            'question-sign' => '&#xe085;',
            'screenshot' => '&#xe087;',
            'remove-circle' => '&#xe088;',
            'ok-circle' => '&#xe089;',
            'ban-circle' => '&#xe090;',
            'arrow-left' => '&#xe091;',
            'arrow-right' => '&#xe092;',
            'arrow-up' => '&#xe093;',
            'arrow-down' => '&#xe094;',
            'share-alt' => '&#xe095;',
            'resize-full' => '&#xe096;',
            'resize-small' => '&#xe097;',
            'exclamation-sign' => '&#xe101;',
            'gift' => '&#xe102;',
            'leaf' => '&#xe103;',
            'fire' => '&#xe104;',
            'eye-open' => '&#xe105;',
            'eye-close' => '&#xe106;',
            'warning-sign' => '&#xe107;',
            'plane' => '&#xe108;',
            'calendar' => '&#xe109;',
            'random' => '&#xe110;',
            'comment' => '&#xe111;',
            'magnet' => '&#xe112;',
            'chevron-up' => '&#xe113;',
            'chevron-down' => '&#xe114;',
            'retweet' => '&#xe115;',
            'folder-close' => '&#xe117;',
            'folder-open' => '&#xe118;',
            'resize-vertical' => '&#xe119;',
            'resize-horizontal' => '&#xe120;',
            'hdd' => '&#xe121;',
            'bullhorn' => '&#xe122;',
            'bell' => '&#xe123;',
            'certificate' => '&#xe124;',
            'thumbs-up' => '&#xe125;',
            'thumbs-down' => '&#xe126;',
            'hand-right' => '&#xe127;',
            'hand-left' => '&#xe128;',
            'hand-up' => '&#xe129;',
            'hand-down' => '&#xe130;',
            'circle-arrow-right' => '&#xe131;',
            'circle-arrow-left' => '&#xe132;',
            'circle-arrow-up' => '&#xe133;',
            'circle-arrow-down' => '&#xe134;',
            'globe' => '&#xe135;',
            'wrench' => '&#xe136;',
            'tasks' => '&#xe137;',
            'filter' => '&#xe138;',
            'briefcase' => '&#xe139;',
            'fullscreen' => '&#xe140;',
            'dashboard' => '&#xe141;',
            'paperclip' => '&#xe142;',
            'heart-empty' => '&#xe143;',
            'link' => '&#xe144;',
            'phone' => '&#xe145;',
            'pushpin' => '&#xe146;',
            'usd' => '&#xe148;',
            'gbp' => '&#xe149;',
            'sort' => '&#xe150;',
            'sort-by-alphabet' => '&#xe151;',
            'sort-by-alphabet-alt' => '&#xe152;',
            'sort-by-order' => '&#xe153;',
            'sort-by-order-alt' => '&#xe154;',
            'sort-by-attributes' => '&#xe155;',
            'sort-by-attributes-alt' => '&#xe156;',
            'unchecked' => '&#xe157;',
            'expand' => '&#xe158;',
            'collapse-down' => '&#xe159;',
            'collapse-up' => '&#xe160;',
            'log-in' => '&#xe161;',
            'flash' => '&#xe162;',
            'log-out' => '&#xe163;',
            'new-window' => '&#xe164;',
            'record' => '&#xe165;',
            'save' => '&#xe166;',
            'open' => '&#xe167;',
            'saved' => '&#xe168;',
            'import' => '&#xe169;',
            'export' => '&#xe170;',
            'send' => '&#xe171;',
            'floppy-disk' => '&#xe172;',
            'floppy-saved' => '&#xe173;',
            'floppy-remove' => '&#xe174;',
            'floppy-save' => '&#xe175;',
            'floppy-open' => '&#xe176;',
            'credit-card' => '&#xe177;',
            'transfer' => '&#xe178;',
            'cutlery' => '&#xe179;',
            'header' => '&#xe180;',
            'compressed' => '&#xe181;',
            'earphone' => '&#xe182;',
            'phone-alt' => '&#xe183;',
            'tower' => '&#xe184;',
            'stats' => '&#xe185;',
            'sd-video' => '&#xe186;',
            'hd-video' => '&#xe187;',
            'subtitles' => '&#xe188;',
            'sound-stereo' => '&#xe189;',
            'sound-dolby' => '&#xe190;',
            'sound-5-1' => '&#xe191;',
            'sound-6-1' => '&#xe192;',
            'sound-7-1' => '&#xe193;',
            'copyright-mark' => '&#xe194;',
            'registration-mark' => '&#xe195;',
            'cloud-download' => '&#xe197;',
            'cloud-upload' => '&#xe198;',
            'tree-conifer' => '&#xe199;',
            'tree-deciduous' => '&#xe200;',
            'cd' => '&#xe201;',
            'save-file' => '&#xe202;',
            'open-file' => '&#xe203;',
            'level-up' => '&#xe204;',
            'copy' => '&#xe205;',
            'paste' => '&#xe206;',
            'alert' => '&#xe209;',
            'equalizer' => '&#xe210;',
            'king' => '&#xe211;',
            'queen' => '&#xe212;',
            'pawn' => '&#xe213;',
            'bishop' => '&#xe214;',
            'knight' => '&#xe215;',
            'baby-formula' => '&#xe216;',
            'tent' => '&#x26fa;',
            'blackboard' => '&#xe218;',
            'bed' => '&#xe219;',
            'apple' => '&#xf8ff;',
            'erase' => '&#xe221;',
            'hourglass' => '&#x231b;',
            'lamp' => '&#xe223;',
            'duplicate' => '&#xe224;',
            'piggy-bank' => '&#xe225;',
            'scissors' => '&#xe226;',
            'bitcoin' => '&#xe227;',
            'yen' => '&#x00a5;',
            'ruble' => '&#x20bd;',
            'scale' => '&#xe230;',
            'ice-lolly' => '&#xe231;',
            'ice-lolly-tasted' => '&#xe232;',
            'education' => '&#xe233;',
            'option-horizontal' => '&#xe234;',
            'option-vertical' => '&#xe235;',
            'menu-hamburger' => '&#xe236;',
            'modal-window' => '&#xe237;',
            'oil' => '&#xe238;',
            'grain' => '&#xe239;',
            'sunglasses' => '&#xe240;',
            'text-size' => '&#xe241;',
            'text-color' => '&#xe242;',
            'text-background' => '&#xe243;',
            'object-align-top' => '&#xe244;',
            'object-align-bottom' => '&#xe245;',
            'object-align-horizontal' => '&#xe246;',
            'object-align-left' => '&#xe247;',
            'object-align-vertical' => '&#xe248;',
            'object-align-right' => '&#xe249;',
            'triangle-right' => '&#xe250;',
            'triangle-left' => '&#xe251;',
            'triangle-bottom' => '&#xe252;',
            'triangle-top' => '&#xe253;',
            'console' => '&#xe254;',
            'superscript' => '&#xe255;',
            'subscript' => '&#xe256;',
            'menu-left' => '&#xe257;',
            'menu-right' => '&#xe258;',
            'menu-down' => '&#xe259;',
            'menu-up' => '&#xe260;',
            'asterisk' => '*',
            'plus' => '+',
            'eur' => '€',
            'euro' => '€',
            'minus' => '−',
            'cloud' => '☁',
            'pencil' => '✏',
            'glass' => '',
            'music' => '',
            'search' => '',
            'heart' => '',
            'star-empty' => '',
            'user' => '',
            'film' => '',
            'th-large' => '',
            'th' => '',
            'th-list' => '',
            'ok' => '',
            'remove' => '',
            'zoom-in' => '',
            'zoom-out' => '',
            'off' => '',
            'signal' => '',
            'cog' => '',
            'trash' => '',
            'file' => '',
            'time' => '',
            'road' => '',
            'download-alt' => '',
            'download' => '',
            'upload' => '',
            'inbox' => '',
            'play-circle' => '',
            'repeat' => '',
            'refresh' => '',
            'list-alt' => '',
            'flag' => '',
            'headphones' => '',
            'volume-off' => '',
            'volume-down' => '',
            'volume-up' => '',
            'qrcode' => '',
            'barcode' => '',
            'tag' => '',
            'tags' => '',
            'book' => '',
            'bookmark' => '',
            'print' => '',
            'camera' => '',
            'font' => '',
            'bold' => '',
            'italic' => '',
            'text-height' => '',
            'text-width' => '',
            'list' => '',
            'indent-left' => '',
            'indent-right' => '',
            'facetime-video' => '',
            'picture' => '',
            'map-marker' => '',
            'adjust' => '',
            'tint' => '',
            'edit' => '',
            'share' => '',
            'check' => '',
            'move' => '',
            'step-backward' => '',
            'fast-backward' => '',
            'backward' => '',
            'play' => '',
            'pause' => '',
            'stop' => '',
            'forward' => '',
            'fast-forward' => '',
            'step-forward' => '',
            'eject' => '',
            'chevron-left' => '',
            'chevron-right' => '',
            'plus-sign' => '',
            'minus-sign' => '',
            'remove-sign' => '',
            'ok-sign' => '',
            'question-sign' => '',
            'screenshot' => '',
            'remove-circle' => '',
            'ok-circle' => '',
            'ban-circle' => '',
            'arrow-left' => '',
            'arrow-right' => '',
            'arrow-up' => '',
            'arrow-down' => '',
            'share-alt' => '',
            'resize-full' => '',
            'resize-small' => '',
            'exclamation-sign' => '',
            'gift' => '',
            'leaf' => '',
            'fire' => '',
            'eye-open' => '',
            'eye-close' => '',
            'warning-sign' => '',
            'plane' => '',
            'calendar' => '',
            'random' => '',
            'comment' => '',
            'magnet' => '',
            'chevron-up' => '',
            'chevron-down' => '',
            'retweet' => '',
            'folder-close' => '',
            'folder-open' => '',
            'resize-vertical' => '',
            'resize-horizontal' => '',
            'hdd' => '',
            'bullhorn' => '',
            'bell' => '',
            'certificate' => '',
            'thumbs-up' => '',
            'thumbs-down' => '',
            'hand-right' => '',
            'hand-left' => '',
            'hand-up' => '',
            'hand-down' => '',
            'circle-arrow-right' => '',
            'circle-arrow-left' => '',
            'circle-arrow-up' => '',
            'circle-arrow-down' => '',
            'globe' => '',
            'wrench' => '',
            'tasks' => '',
            'filter' => '',
            'briefcase' => '',
            'fullscreen' => '',
            'dashboard' => '',
            'paperclip' => '',
            'heart-empty' => '',
            'link' => '',
            'phone' => '',
            'pushpin' => '',
            'usd' => '',
            'gbp' => '',
            'sort' => '',
            'sort-by-alphabet' => '',
            'sort-by-alphabet-alt' => '',
            'sort-by-order' => '',
            'sort-by-order-alt' => '',
            'sort-by-attributes' => '',
            'sort-by-attributes-alt' => '',
            'unchecked' => '',
            'expand' => '',
            'collapse-down' => '',
            'collapse-up' => '',
            'log-in' => '',
            'flash' => '',
            'log-out' => '',
            'new-window' => '',
            'record' => '',
            'save' => '',
            'open' => '',
            'saved' => '',
            'import' => '',
            'export' => '',
            'send' => '',
            'floppy-disk' => '',
            'floppy-saved' => '',
            'floppy-remove' => '',
            'floppy-save' => '',
            'floppy-open' => '',
            'credit-card' => '',
            'transfer' => '',
            'cutlery' => '',
            'header' => '',
            'compressed' => '',
            'earphone' => '',
            'phone-alt' => '',
            'tower' => '',
            'stats' => '',
            'sd-video' => '',
            'hd-video' => '',
            'subtitles' => '',
            'sound-stereo' => '',
            'sound-dolby' => '',
            'sound-5-1' => '',
            'sound-6-1' => '',
            'sound-7-1' => '',
            'copyright-mark' => '',
            'registration-mark' => '',
            'cloud-download' => '',
            'cloud-upload' => '',
            'tree-conifer' => '',
            'tree-deciduous' => '',
            'cd' => '',
            'save-file' => '',
            'open-file' => '',
            'level-up' => '',
            'copy' => '',
            'paste' => '',
            'alert' => '',
            'equalizer' => '',
            'king' => '',
            'queen' => '',
            'pawn' => '',
            'bishop' => '',
            'knight' => '',
            'baby-formula' => '',
            'tent' => '⛺',
            'blackboard' => '',
            'bed' => '',
            'apple' => '',
            'erase' => '',
            'hourglass' => '⌛',
            'lamp' => '',
            'duplicate' => '',
            'piggy-bank' => '',
            'scissors' => '',
            'bitcoin' => '',
            'yen' => '¥',
            'ruble' => '₽',
            'scale' => '',
            'ice-lolly' => '',
            'ice-lolly-tasted' => '',
            'education' => '',
            'option-horizontal' => '',
            'option-vertical' => '',
            'menu-hamburger' => '',
            'modal-window' => '',
            'oil' => '',
            'grain' => '',
            'sunglasses' => '',
            'text-size' => '',
            'text-color' => '',
            'text-background' => '',
            'object-align-top' => '',
            'object-align-bottom' => '',
            'object-align-horizontal' => '',
            'object-align-left' => '',
            'object-align-vertical' => '',
            'object-align-right' => '',
            'triangle-right' => '',
            'triangle-left' => '',
            'triangle-bottom' => '',
            'triangle-top' => '',
            'console' => '',
            'superscript' => '',
            'subscript' => '',
            'menu-left' => '',
            'menu-right' => '',
            'menu-down' => '',
            'menu-up' => '',
        ];
    }

    public function save() {
        $transaction = \Yii::$app->db->beginTransaction();
        foreach($this->data as $key => $value) {
            if (!Setting::set($key, $value)) {
                $this->addError($key, "Incorrect value");
            }
        }
        if (!$this->hasErrors()) {
            $transaction->commit();
            return true;
        } else {
            $transaction->rollBack();
            return false;
        }

    }

    public function countryGradesSurveyOptions() {
        return $this->surveyOptions();
    }

    public function healthClusterDashboardProjectOptions()
    {
        return ArrayHelper::map(
            \prime\models\ar\Project::find()->all(),
            'id',
            'title'
        );
    }

    public function eventGradesSurveyOptions() {
        return $this->surveyOptions();
    }
    public function surveyOptions()
    {
        $result = array_filter(ArrayHelper::map(app()->limeSurvey->listSurveys(), 'sid', function ($details) {
            if (substr_compare('[INTAKE]', $details['surveyls_title'], 0, 8) != 0) {
                return $details['surveyls_title'] . (($details['active'] == 'N') ? " (INACTIVE)" : "") . " [{$details['sid']}]";
            }

            return false;
        }));

        return $result;
    }
}