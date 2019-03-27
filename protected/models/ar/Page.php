<?php


namespace prime\models\ar;


use prime\interfaces\PageInterface;
use prime\models\ActiveRecord;
use prime\objects\GroupPage;
use prime\objects\HeramsResponse;
use SamIT\LimeSurvey\Interfaces\GroupInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\validators\ExistValidator;
use yii\validators\NumberValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;

/**
 * Class Page
 * @package prime\models\ar
 * @property Page[] $children
 * @property Element[] $elements
 * @property int $tool_id
 * @property ?Page $parent
 */
class Page extends ActiveRecord implements PageInterface
{
    public function init()
    {
        $this->sort = 0;
        parent::init();

    }


    public function getProject()
    {
        return $this->hasOne(Project::class, ['id' => 'tool_id']);
    }

    public function getChildren()
    {
        return $this->hasMany(Page::class, ['parent_id' => 'id'])->from(['childpage' => self::tableName()]);
    }

    public function getParentId(): ?int
    {
        return $this->getAttribute('parent_id');
    }

    public function getId(): int
    {
        return $this->getAttribute('id');
    }

    public function getTitle(): string
    {
        return $this->getAttribute('title');
    }

    public function getChildPages(SurveyInterface $survey): iterable
    {
        foreach($this->children as $page) {
            yield $page;
        }

        if ($this->id !== 5) {
            return;
        }
        /** @var GroupInterface $group */
        foreach($survey->getGroups() as $group) {
            if (strpos($group->getTitle(), 'HeRAMS ') === 0) {
                yield new GroupPage($group, $this);
            }
        }
    }

    public function getChildElements(): iterable
    {
        foreach($this->elements as $element) {
            yield $element;
        }
    }

    public function getElements() {
        return $this->hasMany(Element::class, ['page_id' => 'id'])->orderBy(['sort' => SORT_ASC]);
    }

    public function getParent()
    {
        return $this->hasOne(Page::class, ['id' => 'parent_id'])->from(['parentpage' => self::tableName()]);
    }

    public function rules()
    {
        return [
            [['title', 'tool_id', 'sort'], RequiredValidator::class],
            [['sort'], NumberValidator::class],
            [['title'], StringValidator::class],
            [['parent_id'], ExistValidator::class, 'targetClass' => __CLASS__, 'targetAttribute' => 'id'],
            [['tool_id'], ExistValidator::class, 'targetClass' => Project::class, 'targetAttribute' => 'id']
        ];
    }

    public function prepareData(array $data)
    {
        $result = [];
        /** @var HeramsResponse $response */
        foreach($data as $response) {
            foreach($response->getSubjects() as $subject) {
                $result[] = $subject;
            }
        }
        return $result;
    }

    public function parentOptions()
    {
        return $this->find()
            ->andWhere([
                'tool_id' => $this->tool_id,
                'parent_id' => null
            ])
            ->andWhere(['not', ['id' => $this->id]])
            ->select(['title', 'id'])
            ->indexBy('id')
            ->column();
    }

    public function getParentPage(): ?PageInterface
    {
        return $this->parent;
    }


    public function filterResponses(iterable $responses): iterable
    {
        return $responses;
    }
}