<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;


    public function fillHtmlField($field, $value)
    {
        $id = $this->grabAttributeFrom($field, 'id');
        $this->waitForJS("return typeof CKEDITOR.instances[" . json_encode($id) . "] !== 'undefined';", 10);
        $this->executeJS("CKEDITOR.instances[" . json_encode($id) . "].setData(" . json_encode($value) . ");");
    }

    /**
     * Define custom actions here
     */
    public function select2Option(array $select, $option)
    {
        if (!isset($select['css'])) {
            throw new \RuntimeException("This function requires a CSS selector.");
        }

        $selector = [
            'css' => $select['css'] . '~ span.select2'
        ];
        $this->click($selector);
        $this->fillField(['css' =>'.select2-search__field'], $option);
        $this->waitForText($option);
        $this->pressKey(['css' =>'.select2-search__field'], WebDriverKeys::ENTER);

    }


}
