<?php
declare(strict_types=1);

namespace prime\models\ar\read;

use prime\behaviors\LocalizableReadBehavior;
use prime\models\ar\Response;
use prime\traits\ReadOnlyTrait;
use SamIT\Yii2\VirtualFields\VirtualFieldBehavior;
use yii\db\Expression;

class Facility extends \prime\models\ar\Facility
{
    use ReadOnlyTrait;

    public function behaviors(): array
    {
        return [
            VirtualFieldBehavior::class => [
                'class' => VirtualFieldBehavior::class,
                'virtualFields' => [
                    'responseCount' => [
                        VirtualFieldBehavior::CAST => VirtualFieldBehavior::CAST_INT,
                        VirtualFieldBehavior::GREEDY => Response::find()->limit(1)->select('count(*)')
                            ->where(['facility_id' => new Expression(self::tableName() . '.[[id]]')]),
                        VirtualFieldBehavior::LAZY => static fn (\prime\models\ar\Facility $facility) => $facility->getResponses()->count()

                    ]
                ]
            ],

            LocalizableReadBehavior::class => [
                'class' => LocalizableReadBehavior::class,
                'attributes' => ['name', 'alternative_name'],
                'locale' => \Yii::$app->language,
                'defaultLocale' => \Yii::$app->sourceLanguage,
            ]
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public static function labels(): array
    {
        return [
            'uuid' => \Yii::t('app', 'Universal ID')
        ] + parent::labels();
    }
}
