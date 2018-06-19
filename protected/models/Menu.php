<?php

namespace app\models;


use yii\base\Model;

class Menu extends Model
{
    /**
     * List left side menu categories with WS links.
     * @return array
     */
    public static function categories($pid)
    {
        $categories = (new \yii\db\Query())
            ->select(['id', 'name', 'layout', 'ws_chart_url', 'ws_map_url', 'parent_id', 'aggregated'])
            ->from('prime2_category')
            ->where(['>', 'id', 1])
            ->andWhere('project_id = :pid')
            ->addParams([':pid' => $pid])
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
            if ($cat['parent_id'] == $parentId) {
                $subCategories = self::nestedCategories($all, $cat['id']);
                if (count($subCategories) > 0)
                    $cat['subcategories'] = $subCategories;
                $nested[] = $cat;
            }
        }

        return $nested;
    }
}
