<?php
declare(strict_types=1);

namespace prime\models\forms;


use GuzzleHttp\Psr7\Stream;
use prime\helpers\ClosureColumn;
use prime\helpers\DataTextColumn;
use prime\helpers\GetterColumn;
use prime\helpers\RawDataColumn;
use prime\interfaces\ColumnDefinition;
use prime\interfaces\HeramsResponseInterface;
use prime\models\ar\Response;
use Psr\Http\Message\StreamInterface;
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

class CsvExport extends Model
{
    public $includeTextHeader = true;
    public $includeCodeHeader = true;

    public $answersAsText = false;

    private $survey;
    private $language;

    public function __construct(SurveyInterface $survey, $config = [])
    {
        parent::__construct($config);
        $this->survey = $survey;
    }

    public function setLanguage(string $language)
    {
        $this->language = empty($language) ? $this->survey->getDefaultLanguage() : $language;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function rules()
    {
        return [
            [['includeTextHeader', 'includeCodeHeader', 'answersAsText'], BooleanValidator::class],
            [['language'], RangeValidator::class, 'range' => $this->survey->getLanguages()]
        ];
    }

    /**
     * @param SurveyInterface $survey
     * @return iterable|ColumnDefinition[]
     * @throws NotSupportedException
     */
    private function getColumns(SurveyInterface $survey): iterable
    {
        yield new GetterColumn('id', 'External ID', 'external_id');
        yield new ClosureColumn(static function(HeramsResponseInterface $response): ?string {
            if ($response instanceof Response) {
                return strtr('https://ls.herams.org/admin/responses?sa=view&surveyid={surveyId}&id={id}', [
                    '{id}' => $response->getId(),
                    '{surveyId}' => $response->survey_id
                ]);
            }
            return null;

        }, 'external_url', 'External URL');
        yield new ClosureColumn(static function(HeramsResponseInterface $response): ?string {
            if ($response instanceof Response) {
                return $response->last_updated;
            }
            return null;

        }, 'last_synced', 'Last synced');
        yield new GetterColumn('subjectId', 'Subject ID');
        yield new GetterColumn('date', 'Date');

        /** @var QuestionInterface[] $questions */

        $groups = $survey->getGroups();
        usort($groups, function(GroupInterface $a, GroupInterface $b) {
            return $a->getIndex() <=> $b->getIndex();
        });
        foreach($groups as $group) {
            $questions = $group->getQuestions();
            usort($questions, function(QuestionInterface $a, QuestionInterface $b) {
                return $a->getIndex() <=> $b->getIndex();
            });
            foreach($questions as $question) {
                // Don't add column for UOID
                if (in_array($question->getTitle(), ['UOID', 'Update'])) continue;
                switch ($question->getDimensions()) {
                    case 0:
                        yield $this->answersAsText ? new DataTextColumn($question) : new RawDataColumn($question);
                        break;
                    case 1:
                        foreach($question->getQuestions(0) as $subQuestion) {
                            yield $this->answersAsText
                                ? new DataTextColumn($question, $subQuestion)
                                : new RawDataColumn($question, $subQuestion);
                        }
                        break;
                    case 2:
                        foreach($question->getQuestions(0) as $xQuestion) {
                            foreach($xQuestion->getQuestions(0) as $yQuestion) {
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
     * @param $stream
     * @return int
     */
    private function writeTextHeader($stream, ColumnDefinition ...$columns): int
    {
        return $this->fputcsv($stream, map(static function(ColumnDefinition $column): string {
           return $column->getHeaderText();
        }, $columns));
    }

    private function writeCodeHeader($stream, ColumnDefinition ...$columns): int
    {
        return $this->fputcsv($stream, map(static function(ColumnDefinition $column): string {
            return $column->getHeaderCode();
        }, $columns));
    }

    /**
     * @param SurveyInterface $survey
     * @param HeramsResponseInterface[]|iterable $records
     * @throws NotSupportedException
     */
    public function run(
        iterable $records
    ): StreamInterface {
        $size = 0;
        $file = fopen('php://temp', 'w');

        if ($this->survey instanceof LocaleAwareInterface) {
            $survey = $this->survey->getLocalized($this->language);
        } else {
            $survey = $this->survey;
        }
        $columns = toArray($this->getColumns($survey));

        if ($this->includeTextHeader) {
            $size += $this->writeTextHeader($file, ...$columns);
        }

        if ($this->includeCodeHeader) {
            $size += $this->writeCodeHeader($file, ...$columns);
        }

        foreach($records as $record) {
            $size += $this->writeRecord($file, $record, ...$columns);
        }
        return new Stream($file, ['size' => $size]);
    }

    private function fputcsv($resource, iterable $data): int
    {
        $result = fputcsv($resource, is_array($data) ? $data : toArray($data));
        if ($result === false) {
            throw new \RuntimeException('Write failed');
        }
        return $result;
    }

    private function writeRecord($stream, HeramsResponseInterface $record, ColumnDefinition ...$columns): int
    {
        return $this->fputcsv($stream, map(static function(ColumnDefinition $column) use ($record) {
            return $column->getValue($record);
        }, $columns));
    }

    public function getLanguages(): array
    {
        $codes = $this->survey->getLanguages();
        $names = toArray(map(static function(string $code): string {
            return \Locale::getDisplayLanguage($code);
        }, $codes));

        $result =  array_combine($codes, $names);
        return array_merge(["default" => \Yii::t('app', 'Survey default ({lang})', [
            'lang' => $names[$this->survey->getDefaultLanguage()]], $result)
        ]);
    }

}