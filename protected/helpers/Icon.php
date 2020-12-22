<?php


namespace prime\helpers;

use prime\assets\IconBundle;
use yii\helpers\Html;
use yii\helpers\Inflector;

/**
 * Class Icon
 * @package prime\helpers
 * @method static string eye(array $options = [])
 * @method static string pencilAlt(array $options = [])
 * @method static string share(array $options = [])
 * @method static string hospital(array $options = [])
 * @method static string user(array $options = [])
 * @method static string clipboardList(array $options = [])
 * @method static string signOutAlt(array $options = [])
 * @method static string windowMaximize(array $options = [])
 * @method static string admin(array $options = [])
 * @method static string partner(array $options = [])
 * @method static string bug(array $options = [])
 * @method static string close(array $options = [])
 * @method static string home(array $options = [])
 * @method static string chevronLeft(array $options = [])
 * @method static string chevronRight(array $options = [])
 * @method static string chevronDown(array $options = [])
 * @method static string chevronUp(array $options = [])
 * @method static string star(array $options = [])
 * @method static string question(array $options = [])
 * @method static string paintBrush(array $options = [])
 * @method static string search(array $options = [])
 * @method static string list(array $options = [])
 *
 * @method static string add(array $options = [])
 * @method static string recycling(array $options = [])
 * @method static string up_arrow_1(array $options = [])
 * @method static string download_2(array $options = [])
 * @method static string trash(array $options = [])
 * @method static string export(array $options = [])
 *
 * @method static string heart(array $options = [])
 *
 * // NovelT icons
 * @method static string project(array $options = [])
 * @method static string healthFacility(array $options = [])
 * @method static string delete(array $options = [])
 * @method static string edit(array $options = [])
 * @method static string download(array $options = [])
 * @method static string sync(array$options = [])
 */
class Icon
{
    public static function contributors(array $options = []): string
    {
        return self::partner($options);
    }

    public static function icon($name, array $options = [])
    {

        Html::addCssClass($options, ['icon', "icon-$name"]);
        return Html::tag(
            'svg',
            self::svgSource($name),
            $options
        );
    }

    private static $icons = null;

    private static $registeredIcons = [];

    /**
     * @param $name
     * @return string
     * @todo Load symbol definitions in head instead of just before use.
     */
    public static function svgSource($name)
    {
        // Register the icon bundle, this is needed for the CSS file that defines sizes for all icons.
        IconBundle::register(\Yii::$app->view);
        if (!isset(self::$icons)) {
            \Yii::beginProfile('loadIcons');
            /** @var \SimpleXMLElement $file */
            $symbols = simplexml_load_file(\Yii::getAlias('@app/assets/icons/symbol-defs.svg'));
            foreach ($symbols->defs->symbol as $symbol) {
                self::$icons[(string) $symbol['id']] = $symbol->asXML();
            }
            \Yii::endProfile('loadIcons');
        }

        $use = Html::tag('use', '', ['href' => "#icon-{$name}"]);
        if (!isset(self::$registeredIcons["icon-{$name}"])) {
            self::$registeredIcons["icon-{$name}"] = true;
            return self::$icons["icon-{$name}"] . $use;
        } else {
            return $use;
        }
    }

    public static function __callStatic($name, $arguments)
    {
        return self::icon(Inflector::camel2id($name), $arguments[0] ?? []);
    }
}
