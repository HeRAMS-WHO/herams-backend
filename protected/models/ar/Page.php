<?php


namespace prime\models\ar;


use prime\models\ActiveRecord;

/**
 * Class Page
 * @package prime\models\ar
 * @property Page[] $children
 * @property Element[] $elements
 * @property int $tool_id
 */
class Page extends ActiveRecord
{

    public function getChildren()
    {
        return $this->hasMany(Page::class, ['parent_id' => 'id'])->from(['childpage' => self::tableName()]);
    }

    public function getElements() {
        return $this->hasMany(Element::class, ['page_id' => 'id']);
    }

    public function getParent()
    {
        return $this->hasOne(Page::class, ['id' => 'parent_id'])->from(['parentpage' => self::tableName()]);
    }


}