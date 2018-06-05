<?php

namespace app\models;


use yii\base\Model;
use yii\db\Query;

class Menu extends Model
{
    /**
     * List left side menu categories with WS links.
     * The categories should preferably come directly from question groups.
     * @return array
     */
    public static function categories()
    {
        $categories = (new Query())
            ->select(['id', 'name', 'layout', 'ws_chart_url', 'ws_map_url', 'parent_id'])
            ->from('prime2_category')
            ->where(['>', 'id', 1])
            ->all();

        return self::nestedCategories($categories, null);
    }

    /**
     * Order sub-categories under parents.
     * @param arrray $all
     * @param int $parentId
     * @return array
     */
    public static function nestedCategories($all, $parentId)
    {
        $nested = [];

        foreach ($all as $cat) {
            if ($cat['parent'] == $parentId) {
                $subCategories = self::nestedCategories($all, $cat['id']);
                if (count($subCategories) > 0)
                    $cat['subcats'] = $subCategories;
                $nested[] = $cat;
            }
        }

        return $nested;
    }
    
}