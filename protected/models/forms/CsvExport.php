<?php
declare(strict_types=1);

namespace prime\models\forms;


use GuzzleHttp\Psr7\Stream;
use prime\interfaces\HeramsResponseInterface;
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

    public $language;
    private $survey;

    public function __construct(SurveyInterface $survey, $config = [])
    {
        parent::__construct($config);
        $this->survey = $survey;
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
     * @return iterable
     * @throws NotSupportedException
     */
    private function getColumns(SurveyInterface $survey): iterable
    {
//        yield 'subjectId';
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
                if ($question->getId() === 'UOID') continue;
                switch ($question->getDimensions()) {
                    case 0:
                        yield [$question];
                        break;
                    case 1:
                        foreach($question->getQuestions(0) as $subQuestion) {
                            yield [$question, $subQuestion];
                        }
                        break;
                    case 2:
                        foreach($question->getQuestions(0) as $xQuestion) {
                            foreach($xQuestion->getQuestions(0) as $yQuestion) {
                                yield [$question, $xQuestion, $yQuestion];
                            }
                        }
                        break;
                    default:
                        throw new NotSupportedException('Only questions with dimensions 0, 1 or 2 are supported');
                }
            }
        }
    }

    private function writeTextHeader($stream, iterable $questions): int
    {
        return $this->writeHeader($stream, $questions, static function(array $questions): string {
            /** @var QuestionInterface $question */
            $question = array_pop($questions);
            $result = $question->getText();
            while (null !== $question = array_pop($questions)) {
                $result .= ' ' . $question->getText();
            }
            return $result;
        });
    }

    private function writeHeader($stream, iterable $questions, \Closure $text): int
    {
        return $this->fputcsv($stream, map($text, $questions));
    }

    private function writeCodeHeader($stream, iterable $questions): int
    {
        return $this->writeHeader($stream, $questions, static function(array $questions): string {
            /** @var QuestionInterface $question */
            $question = array_shift($questions);
            $result = $question->getTitle();
            while (null !== $question = array_shift($questions)) {
                $result .= '_' . $question->getTitle();
            }
            return $result;
        });
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
            $size += $this->writeTextHeader($file, $columns);
        }

        if ($this->includeCodeHeader) {
            $size += $this->writeCodeHeader($file, $columns);
        }

        foreach($records as $record) {
            $size += $this->writeRecord($file, $columns, $record);
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

    /**
     * @var QuestionInterface[][] $columns
     */
    private function writeRecord($stream, iterable $columns, HeramsResponseInterface $record): int
    {
        return $this->fputcsv($stream, map(function($questionPath) use ($record) {
            if (is_string($questionPath)) {
                return $record->$questionPath;
            }
            if ($this->answersAsText) {
                return $this->getValueText($record, $questionPath);
            } else {
                return $this->getValue($record, $questionPath);

            }

        }, $columns));
    }

    /**
     * @param HeramsResponseInterface $record
     * @param QuestionInterface[] $path
     */
    private function getValue(HeramsResponseInterface $record, iterable $path): ?string
    {
        $data = $record->getRawData();
        /** @var QuestionInterface $question */
        foreach($path as $question) {
            $data = $data[$question->getTitle()] ?? null;
        }
        if (is_array($data)) {
            return "Bad data, got array: " . implode(', ', $data);
        }
        return $data;
    }
    /**
     * @param HeramsResponseInterface $record
     * @param QuestionInterface[] $path
     */
    private function getValueText(HeramsResponseInterface $record, iterable $path): ?string
    {
        $data = $record->getRawData();

        /** @var QuestionInterface $question */
        foreach($path as $question) {
            $data = $data[$question->getTitle()] ?? null;
        }

        $answers = $question->getAnswers();
        if (isset($answers)) {
            foreach($answers as $answer) {
                if ($answer->getCode() === $data) {
                    return $answer->getText();
                }
            }
        }
        if (is_array($data)) {
            return "Bad data, got array: " . implode(', ', $data);
        }
        return $data;
    }

    public function getLanguages()
    {
        $codes = $this->survey->getLanguages();
        $names = toArray(map(static function(string $code): string {
            return \Locale::getDisplayLanguage($code);
        }, $codes));
        return array_combine($codes, $names);
    }

}