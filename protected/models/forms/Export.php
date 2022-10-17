<?php

declare(strict_types=1);

namespace prime\models\forms;

use prime\helpers\ClosureColumn;
use prime\helpers\DataTextColumn;
use prime\helpers\GetterColumn;
use prime\helpers\RawDataColumn;
use prime\interfaces\ColumnDefinition;
use prime\interfaces\HeramsResponseInterface;
use prime\interfaces\WriterInterface;
use prime\objects\HeramsCodeMap;
use prime\queries\SurveyResponseQuery;
use SamIT\LimeSurvey\Interfaces\GroupInterface;
use SamIT\LimeSurvey\Interfaces\LocaleAwareInterface;
use SamIT\LimeSurvey\Interfaces\QuestionInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\base\Model;
use yii\base\NotSupportedException;
use yii\validators\BooleanValidator;
use yii\validators\RangeValidator;

use function iter\map;
use function iter\toArray;

class Export extends Model
{
    private const DEFAULT_LANGUAGE = 'default';

    public $includeTextHeader = true;

    public $includeCodeHeader = true;

    public $answersAsText = false;

    private SurveyInterface $survey;

    public $language = self::DEFAULT_LANGUAGE;

    private $filter;

    public function attributeLabels(): array
    {
        return [
            'includeTextHeader' => \Yii::t('app', 'Include text header'),
            'includeCodeHeader' => \Yii::t('app', 'Include code header'),
            'answersAsText' => \Yii::t('app', 'Answers as text'),
            'language' => \Yii::t('app', 'Language'),
        ];
    }

    public function getFilterModel(): ResponseFilter
    {
        return $this->filter;
    }

    public function __construct(SurveyInterface $survey, $config = [])
    {
        parent::__construct($config);
        $this->survey = $survey;
        $this->filter = new ResponseFilter($survey, new HeramsCodeMap());
    }

    public function rules(): array
    {
        return [
            [['includeTextHeader', 'includeCodeHeader', 'answersAsText'], BooleanValidator::class],
            [['language'],
                RangeValidator::class,
                'range' => array_keys($this->getLanguages()),
            ],
        ];
    }

    public function load($data, $formName = null): bool
    {
        if ($formName === null) {
            $this->filter->load($data);
        }
        return parent::load($data, $formName);
    }

    /**
     * @return iterable|ColumnDefinition[]
     * @throws NotSupportedException
     */
    private function getColumns(SurveyInterface $survey): iterable
    {
        yield new GetterColumn('id', 'External ID', 'external_id');
//        yield new ClosureColumn(static function(HeramsResponseInterface $response): ?string {
//            if ($response instanceof Response) {
//                return strtr('https://ls.herams.org/admin/responses?sa=view&surveyid={surveyId}&id={id}', [
//                    '{id}' => $response->getId(),
//                    '{surveyId}' => $response->survey_id
//                ]);
//            }
//            return null;
//
//        }, 'external_url', 'External URL');
        yield new ClosureColumn(static function (HeramsResponseInterface $response): ?string {
            return $response->updated_at ?? null;
        }, 'last_synced', 'Last synced');

        if ($this->answersAsText) {
            yield new ClosureColumn(static function (HeramsResponseInterface $response): string {
                return (string) $response->workspace->title;
            }, 'workspace_id', 'Workspace ID');
        } else {
            yield new ClosureColumn(static function (HeramsResponseInterface $response): string {
                return (string) $response->workspace_id;
            }, 'workspace_id', 'Workspace ID');
        }
        yield new GetterColumn('subjectId', 'Subject ID');
        yield new GetterColumn('date', 'Date');

        /** @var QuestionInterface[] $questions */

        $groups = $survey->getGroups();
        usort($groups, function (GroupInterface $a, GroupInterface $b) {
            return $a->getIndex() <=> $b->getIndex();
        });
        foreach ($groups as $group) {
            $questions = $group->getQuestions();
            usort($questions, function (QuestionInterface $a, QuestionInterface $b) {
                return $a->getIndex() <=> $b->getIndex();
            });
            foreach ($questions as $question) {
                // Don't add column for UOID
                if (in_array($question->getTitle(), ['UOID', 'Update'])) {
                    continue;
                }
                switch ($question->getDimensions()) {
                    case 0:
                        yield $this->answersAsText ? new DataTextColumn($question) : new RawDataColumn($question);
                        break;
                    case 1:
                        foreach ($question->getQuestions(0) as $subQuestion) {
                            yield $this->answersAsText
                                ? new DataTextColumn($question, $subQuestion)
                                : new RawDataColumn($question, $subQuestion);
                        }
                        break;
                    case 2:
                        foreach ($question->getQuestions(0) as $xQuestion) {
                            foreach ($xQuestion->getQuestions(0) as $yQuestion) {
                                yield $this->answersAsText
                                    ? new DataTextColumn($question, $xQuestion, $yQuestion)
                                    : new RawDataColumn($question, $xQuestion, $yQuestion);
                            }
                        }
                        break;
                    default:
                        throw new NotSupportedException('Only questions with dimensions 0, 1 or 2 are supported');
                }
            }
        }
    }

    /**
     * @throws NotSupportedException
     */
    public function run(
        WriterInterface $writer,
        SurveyResponseQuery $responseQuery
    ): void {
        $query = isset($this->filter->date) ? $this->filter->filterQuery($responseQuery) : $responseQuery;

        if (
            $this->survey instanceof LocaleAwareInterface
            && $this->language !== self::DEFAULT_LANGUAGE
        ) {
            $survey = $this->survey->getLocalized($this->language);
        } else {
            $survey = $this->survey;
        }
        $columns = toArray($this->getColumns($survey));

        if ($this->includeTextHeader) {
            $writer->writeHeader(...toArray(map(function (ColumnDefinition $column): string {
                return $column->getHeaderText();
            }, $columns)));
        }

        if ($this->includeCodeHeader) {
            $writer->writeHeader(...toArray(map(function (ColumnDefinition $column): string {
                return $column->getHeaderCode();
            }, $columns)));
        }

        foreach ($query->each() as $record) {
            $writer->writeRecord($record, ...$columns);
        }
    }

    public function getLanguages(): array
    {
        $codes = $this->survey->getLanguages();
        $names = toArray(map(static function (string $code): string {
            return \Locale::getDisplayLanguage($code);
        }, $codes));

        $result = array_combine($codes, $names);
        return array_merge([
            self::DEFAULT_LANGUAGE => \Yii::t('app', 'Survey default ({lang})', [
                'lang' => \Locale::getDisplayLanguage($this->survey->getDefaultLanguage()),
            ]),
        ], $result);
    }
}
