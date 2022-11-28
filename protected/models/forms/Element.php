<?php

namespace prime\models\forms;

use prime\traits\SurveyHelper;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\base\Model;
use yii\base\UnknownMethodException;
use yii\validators\RangeValidator;
use yii\validators\RegularExpressionValidator;

class Element extends Model
{
    /**
     * @var \herams\common\domain\element\Element
     */
    private $element;

    public function __construct(
        SurveyInterface $survey,
        \herams\common\domain\element\Element $element
    ) {
        parent::__construct();
        $this->survey = $survey;
        $this->element = $element;
        if ($element->isNewRecord && $element->getCode() === null) {
            $element->setCode(array_keys($this->codeOptions())[0]);
        }
    }

    public function load($data, $formName = null): bool
    {
        if (parent::load($data, $formName)) {
            // Recreate validators.
            $this->getValidators()->exchangeArray($this->createValidators());
            return true;
        }
        return false;
    }

    public function __isset($name)
    {
        return parent::__isset($name) || isset($this->element->$name);
    }

    public function __set($name, $value)
    {
        if (preg_match('/^color\.(.*)/', $name, $matches)) {
            $this->setColor($matches[1], $value);
        } elseif (! $this->canSetProperty($name)) {
            $this->element->{$name} = $value;
        } else {
            parent::__set($name, $value);
        }
    }

    public function __get($name)
    {
        if (preg_match('/^color\.(.*)/', $name, $matches)) {
            return $this->getColor($matches[1]);
        } elseif (! $this->canGetProperty($name)) {
            return $this->element->{$name};
        }
        return parent::__get($name);
    }

    public function attributeLabels(): array
    {
        $result = $this->element->attributeLabels();
        $result['survey'] = \Yii::t('app', 'Survey');
        // Add color labels.
        if ($this->element->code !== null) {
            foreach ($this->getAnswers($this->element->getCode()) as $code => $answer) {
                $result[strtr("color.$code", [
                    '-' => '_',
                ])] = $answer;
            }
        }
        return $result;
    }

    public function attributeHints(): array
    {
        $result = $this->element->attributeHints();
        $result['transpose'] = \Yii::t('app', 'This will reload the page losing all other changes');
        $result['code'] = \Yii::t('app', 'This will reload the page losing all other changes');
        return $result;
    }

    public function __call($name, $params)
    {
        try {
            return parent::__call($name, $params);
        } catch (UnknownMethodException $e) {
            return $this->element->$name(...$params);
        }
    }

    public function rules(): array
    {
        return array_merge($this->element->rules(), [
            $this->colorRule(),
            [['code'],
                RangeValidator::class,
                'range' => function () {
                    return array_keys($this->codeOptions());
                },
            ],
        ]);
    }

    private function colorRule(): array
    {
        return [
            $this->colorAttributes(),
            RegularExpressionValidator::class,
            'pattern' => '/^\#[0-9a-fA-F]{6}$/',
        ];
    }

    public function answerCodes(): array
    {
        if (! isset($this->element->code)) {
            return [];
        }
        return array_keys($this->getAnswers($this->element->code));
    }

    public function onUnsafeAttribute($name, $value)
    {
        if (in_array($name, $this->colorAttributes())) {
            $this->setColor($name, $value);
        } else {
            parent::onUnsafeAttribute($name, $value);
        }
    }

    public function colorAttributes(): array
    {
        if (! $this->element->isAttributeSafe('colors')) {
            return [];
        }
        return array_map(function ($code) {
            return strtr("color.$code", [
                '-' => '_',
            ]);
        }, $this->answerCodes());
    }

    public function getTitlePlaceHolder(): string
    {
        return isset($this->code) ? $this->getTitleFromCode($this->code) : 'Pick one';
    }

    public function codeOptions(): array
    {
        $codeOptions = [];
        foreach ($this->survey->getGroups() as $group) {
            foreach ($group->getQuestions() as $question) {
                if (
                    $question->getAnswers() !== null
                    || ($question->getDimensions() === 1 && $question->getQuestions(0)[0]->getAnswers() !== null)
                ) {
                    $text = strip_tags($question->getText());
                    $codeOptions[$question->getTitle()] = $this->normalizeQuestionText($text) . " ({$question->getTitle()})";
                }
            }
        }
        if ($this->element->transpose) {
            $codeOptions['availability'] = $this->getTitleFromCode('availability');
            $codeOptions['fullyAvailable'] = $this->getTitleFromCode('fullyAvailable');
            $codeOptions['causes'] = $this->getTitleFromCode('causes');
        } else {
            $codeOptions['subjectAvailabilityBucket'] = $this->getTitleFromCode('subjectAvailabilityBucket');
        }
        return $codeOptions;
    }

    public function getColor(string $code): string
    {
        return $this->element->getColors()[$code] ?? '#000000';
    }

    public function setColor(string $code, string $color)
    {
        $colors = $this->element->getColors();
        $colors[$code] = $color;
        $this->element->setColors($colors);
    }

    public function save(): bool
    {
        return $this->validate() && $this->element->save();
    }

    private function getAnswers(string $code)
    {
        switch ($code) {
            case 'causes':
                $expr = strtr($this->element->project->getMap()->getSubjectExpression(), [
                    '$' => 'x$',
                ]);
                foreach ($this->survey->getGroups() as $group) {
                    foreach ($group->getQuestions() as $question) {
                        if (preg_match($expr, $question->getTitle())) {
                            return $this->getQuestionAnswers($question->getTitle());
                        }
                    }
                }
                return [];
            default:
                try {
                    return $this->getQuestionAnswers($this->element->code);
                } catch (\Throwable $t) {
                    return [];
                }
        }
    }
}
