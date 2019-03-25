<?php


namespace prime\widgets;


use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\StringHelper;

/**
 * Class BaseWidget
 * @package prime\widgets
 * @property-write array $options
 */
abstract class BaseWidget extends Widget
{

    private $_options;

    public $tag = 'div';

    public function init()
    {
        parent::init();
        if (isset($this->_options['id'])) {
            throw new InvalidConfigException('Do not set id via options, instead use the id property');
        }

        echo Html::beginTag($this->tag, $this->getOptions());
    }

    protected function getOptions(): array
    {
        $options = $this->_options;

        Html::addCssClass($options, StringHelper::basename(get_class($this)));
        $options['id'] = $this->getId();
        return $options;
    }

    public function setOptions(array $options)
    {
        $this->_options = $options;
    }

    abstract protected function runInternal();

    public function run()
    {
        $this->runInternal();
        echo Html::endTag($this->tag);
    }


}