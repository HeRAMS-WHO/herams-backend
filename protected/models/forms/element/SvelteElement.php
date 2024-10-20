<?php

declare(strict_types=1);

namespace prime\models\forms\element;

use Collecthor\DataInterfaces\VariableSetInterface;
use herams\api\validators\EnumValidator;
use herams\common\attributes\Field;
use herams\common\attributes\JsonField;
use herams\common\interfaces\HeramsVariableSetRepositoryInterface;
use herams\common\models\Page;
use herams\common\models\RequestModel;
use herams\common\values\PageId;
use prime\helpers\DeferredVariableSet;
use prime\objects\enums\ChartType;
use prime\objects\enums\DataSort;
use prime\validators\VariableValidator;
use SamIT\Yii2\abac\PermissionValidator;
use yii\validators\NumberValidator;
use yii\validators\RequiredValidator;
use yii\validators\SafeValidator;

final class SvelteElement extends RequestModel
{
    #[Field('page_id')]
    public mixed $pageId = null;

    public mixed $width = 1;

    public mixed $height = 1;

    public mixed $sort = 1;

    #[JsonField('config')]
    public mixed $title = null;

    #[JsonField('config')]
    public mixed $variables = [];

    #[JsonField('config')]
    public mixed $groupingVariable = null;

    #[JsonField('config')]
    public mixed $dataSort = DataSort::Source;

    #[JsonField('config')]
    public mixed $colorMap = [];

    #[JsonField('config')]
    public mixed $type = ChartType::Bar;

    public function __construct(
        private HeramsVariableSetRepositoryInterface $variableSetRepository
    ) {
        parent::__construct();
    }

    private function getVariableSet(): VariableSetInterface
    {
        return new DeferredVariableSet(fn () => $this->variableSetRepository->retrieveForPage(new PageId($this->pageId)));
    }

    public function rules(): array
    {
        return [
            [['colorMap'], SafeValidator::class],
            [['width', 'height'],
                NumberValidator::class,
                'max' => 4,
                'min' => 1,
                'integerOnly' => true,
            ],
            [['sort'],
                NumberValidator::class,
                'min' => 1,
                'integerOnly' => true,
            ],
            [['pageId', 'title', 'variables'], RequiredValidator::class],
            PermissionValidator::create(['pageId'], Page::find()),

            [['dataSort'],
                EnumValidator::class,
                'enumClass' => DataSort::class,
            ],
            [['type'],
                EnumValidator::class,
                'enumClass' => ChartType::class,
            ],
            VariableValidator::multipleFromSet($this->getVariableSet(), 'variables')
                ->withCondition(fn ($model, $attribute) => ! $this->hasErrors('pageId')),
            VariableValidator::singleFromSet($this->getVariableSet(), 'groupingVariable'),

        ];
    }
}
