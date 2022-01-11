<?php


namespace prime\models\forms\workspace;

use prime\models\ar\Project;
use prime\models\ar\Workspace;
use prime\objects\BatchResult;
use SamIT\LimeSurvey\Interfaces\TokenInterface;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\validators\RangeValidator;
use yii\validators\RequiredValidator;

class Import extends Model
{
    /** @var int */
    private $project;

    /**
     * @var array|TokenInterface[]
     */
    private $tokenObjects = [];

    public $titleField;
    public $tokens = [];


    private $fieldOptions = [];
    private $tokenOptions = [];
    /**
     * Import constructor.
     * @param Project $project
     * @param TokenInterface[] $tokens
     * @param array $config
     */
    public function __construct(
        Project $project,
        array $tokens,
        $config = []
    ) {
        parent::__construct($config);
        $this->project = $project;
        $usedTokens = $this->project->getWorkspaces()->select(['token'])->indexBy('token')->column();
        foreach ($tokens as $token) {
            if (empty($token->getToken()) || isset($usedTokens[$token->getToken()])) {
                continue;
            }

            $this->tokenOptions[$token->getToken()] = "[{$token->getToken()}] " . implode(', ', array_filter($token->getCustomAttributes()));
            // Selected by default.
            $this->tokens[$token->getToken()] = $token->getToken();
            $this->tokenObjects[$token->getToken()] = $token;
            $this->fieldOptions['token'] = 'token';
            foreach ($token->getCustomAttributes() as $key => $value) {
                if (!empty($value)) {
                    $this->fieldOptions[$key] = $key;
                }
            }
        }
        if (empty($this->tokens)) {
            throw new InvalidConfigException('No available tokens');
        }
    }


    public function tokenOptions(): array
    {
        return $this->tokenOptions;
    }

    public function fieldOptions(): array
    {
        return $this->fieldOptions;
    }

    public function attributeHints()
    {
        return [
            'titleField' => \Yii::t('app', 'The token field that is used as the workspace name, empty token attributes are ignored'),
        ];
    }

    private function getName(TokenInterface $token): string
    {
        return $token->getCustomAttributes()[$this->titleField] ?? $token->{'get'. ucfirst($this->titleField)}();
    }

    public function rules()
    {
        return [
            [['titleField', 'tokens'], RequiredValidator::class],
            [['titleField'], RangeValidator::class, 'range' => array_keys($this->fieldOptions())],
            [['tokens'], RangeValidator::class, 'range' => array_keys($this->tokenOptions()), 'allowArray' => true],
            [['tokens'], function ($params) {
                foreach ($this->tokens as $token) {
                    /** @var TokenInterface $tokenObject */
                    $tokenObject = $this->tokenObjects[$token];
                    $workspace = new Workspace();
                    $workspace->tool_id = $this->project->id;
                    $workspace->title = $this->getName($tokenObject);
                    $workspace->setAttribute('token', $token);
                    if (!$workspace->validate()) {
                        foreach ($workspace->errors as $attribute => $errors) {
                            foreach ($errors as $error) {
                                $this->addError('tokens', "Invalid configuration for token {$token}: $error");
                            }
                        }
                    }
                    return false;
                }
            }]
        ];
    }


    public function run()
    {
        $success = 0;
        $fail = 0;
        foreach ($this->tokens as $token) {
            /** @var TokenInterface $tokenObject */
            $tokenObject = $this->tokenObjects[$token];
            $workspace = new Workspace();
            $workspace->tool_id = $this->project->id;
            $workspace->title = $this->getName($tokenObject);
            $workspace->setAttribute('token', $token);
            if ($workspace->save()) {
                $success++;
            } else {
                $fail++;
            }
        }
        return new BatchResult($success, $fail);
    }
}
