<?php
declare(strict_types=1);

namespace prime\models\ar;

use prime\models\ActiveRecord;
use prime\objects\enums\ElementType;
use prime\validators\BackedEnumValidator;
use yii\validators\NumberValidator;
use yii\validators\RequiredValidator;

/**
 * Models a row in the `Element` table, adding minimal functionality only.
 * @property string $type
 */
class RawElement extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%element}}';
    }

    public function init()
    {
        $this->type ??= ElementType::Svelte->value;
    }


    public function rules(): array
    {
        return [
            [['sort', 'type', 'width', 'height'], RequiredValidator::class],
            [['type'],
                BackedEnumValidator::class,
                'example' => ElementType::Svelte
            ],
            [['width', 'height'],
                NumberValidator::class,
                'integerOnly' => true,
                'min' => 1,
                'max' => 4,
            ],
            [['sort'],
                NumberValidator::class,
                'integerOnly' => true,
            ],
        ];
    }


}
