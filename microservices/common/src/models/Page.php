<?php

namespace herams\common\models;

use herams\common\domain\element\Element;
use herams\common\interfaces\PageInterface;
use prime\interfaces\Exportable;
use prime\queries\ElementQuery;
use yii\base\InvalidArgumentException;
use yii\base\NotSupportedException;
use yii\db\ActiveQuery;
use yii\validators\ExistValidator;
use yii\validators\NumberValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;
use function iter\rewindable\map;

/**
 * Class Page
 * @package prime\models\ar
 * @property Page[] $children
 * @property Element[] $elements
 * @property int $project_id
 * @property Project $project
 * @property int $sort
 * @property bool $add_services
 * @property string $title
 * @property int $id
 * @property ?Page $parent
 */
class Page extends ActiveRecord implements PageInterface
{
    public function init()
    {
        $this->sort = 0;
        parent::init();
    }

    public function titleOptions(): array
    {
        $sourceLanguage = \Yii::$app->sourceLanguage;
        return [
            \Yii::t('app.pagetitle', 'Overview', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'Infrastructure', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'Condition', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'Functionality', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'Accessibility', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'Building and equipment condition', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'Management and support', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'Basic Amenities', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'Water', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'Sanitation', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'Communication', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'Cold chain', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'Power', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'Service availability', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'Waste management', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'Health Information', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'Health information systems', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'General clinical services & trauma care', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'General clinical services & trauma care (part I)', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'General clinical services & trauma care (part II)', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'General clinical services & trauma care (part III)', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'General clinical services', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'Trauma Care', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'Child health & nutrition', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'Child health', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'Nutrition', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'Vaccination', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'Communicable diseases', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'Sexual and reproductive health', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'STI & HIV/AIDS', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'Maternal and newborn health', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'Sexual violence', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'Non-communicable diseases', language: $sourceLanguage),
            \Yii::t('app.pagetitle', 'Mental health', language: $sourceLanguage),
        ];
    }

    public function getProject()
    {
        return $this->hasOne(Project::class, [
            'id' => 'project_id',
        ])->inverseOf('pages');
    }

    public function getChildren()
    {
        return $this->hasMany(Page::class, [
            'parent_id' => 'id',
        ])
            ->from([
                'childpage' => self::tableName(),
            ])
            ->orderBy([
                'sort' => SORT_ASC,
            ])
            ->inverseOf('parent');
    }

    public function getParentId(): ?int
    {
        return $this->getAttribute('parent_id');
    }

    public static function labels(): array
    {
        return array_merge(parent::labels(), [
            'parent_id' => \Yii::t('app', 'Parent page'),
            'sort' => \Yii::t('app', 'Sort index'),
            'add_services' => \Yii::t('app', 'Add services'),
            'project_id' => \Yii::t('app', 'Project'),
        ]);
    }

    public function getId(): int
    {
        return $this->getAttribute('id');
    }

    public function getTitle(): string
    {
        return $this->getAttribute('title');
    }

    public function getChildPages(): iterable
    {
        foreach ($this->children as $page) {
            yield $page;
        }

        if ($this->add_services) {
            throw new NotSupportedException('This feature is no longer supported');
        }
    }

    /**
     * @return iterable<Element>
     */
    public function getChildElements(): iterable
    {
        yield from $this->elements;
    }

    public function getElements(): ElementQuery
    {
        return $this
            ->hasMany(Element::class, [
                'page_id' => 'id',
            ])
            ->inverseOf('page')
            ->orderBy([
                'sort' => SORT_ASC,
            ]);
    }

    public function getParent(): ActiveQuery
    {
        return $this->hasOne(Page::class, [
            'id' => 'parent_id',
        ])->from([
            'parentpage' => self::tableName(),
        ])->inverseOf('children');
    }

    public function rules(): array
    {
        return [
            [['title', 'sort', 'project_id'], RequiredValidator::class],
            [['sort'], NumberValidator::class],
            [['title'], StringValidator::class],
            [['parent_id'],
                ExistValidator::class,
                'targetClass' => __CLASS__,
                'targetAttribute' => 'id',
                'filter' => function (ActiveQuery $query) {
                    return $query->andWhere([
                        'project_id' => $this->project_id,
                    ]);
                },
            ],
            [['project_id'],
                ExistValidator::class,
                'targetClass' => Project::class,
                'targetAttribute' => 'id',
            ],
        ];
    }

    public function scenarios()
    {
        $result = parent::scenarios();
        $result[self::SCENARIO_DEFAULT][] = '!project_id';
        return $result;
    }

    public function beforeDelete()
    {
        /** @var Page $child */
        foreach ($this->getChildren()->each() as $child) {
            $child->delete();
        }
        /** @var Element $element */
        foreach ($this->getElements()->each() as $element) {
            $element->delete();
        }
        return parent::beforeDelete();
    }

    public function parentOptions()
    {
        $result = $this->find()
            ->andWhere([
                'project_id' => $this->project_id,
                'parent_id' => null,
            ])
            ->andFilterWhere([
                'not', [
                    'id' => $this->id,

                ], ])
            ->select(['title', 'id'])
            ->indexBy('id')
            ->column();

        $result = map(static fn ($v) => \Yii::t('app.pagetitle', $v), $result);

        return $result;
    }

    public function getParentPage(): ?PageInterface
    {
        return $this->parent;
    }

    public function filterResponses(iterable $responses): iterable
    {
        return $responses;
    }

    public function export(): array
    {
        if (! $this->validate()) {
            throw new \LogicException('Cannot export an invalid page: ' . print_r($this->errors, true));
        }
        $attributes = $this->attributes;
        foreach ($this->primaryKey() as $key) {
            unset($attributes[$key]);
        }
        unset($attributes['project_id'], $attributes['parent_id']);
        $elements = [];
        foreach ($this->elements as $element) {
            $elements[] = $element->export();
        }
        $pages = [];
        foreach ($this->children as $page) {
            $pages[] = $page->export();
        }
        return [
            'type' => 'page',
            'attributes' => $attributes,
            'elements' => $elements,
            'pages' => $pages,
        ];
    }

    /**
     * @param Page|Project $parent
     */
    public static function import($parent, array $data): Page
    {
        if (! ($parent instanceof Page || $parent instanceof Project)) {
            throw new \InvalidArgumentException('Parent must be instance of Page or Project');
        }
        $result = new Page();
        $result->setAttributes($data['attributes']);
        if ($parent instanceof Page) {
            $result->project_id = $parent->project_id;
            $result->parent_id = $parent->id;
        } else {
            $result->project_id = $parent->id;
        }

        if (! $result->validate()) {
            throw new InvalidArgumentException('Validation failed: ' . print_r($result->errors, true));
        }

        $result->save(false);

        foreach ($data['elements'] as $elementData) {
            Element::import($result, $elementData);
        }

        foreach ($data['pages'] as $pageData) {
            Page::import($result, $pageData);
        }

        return $result;
    }
}