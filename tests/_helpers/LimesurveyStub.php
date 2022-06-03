<?php

namespace prime\tests\_helpers;

use SamIT\LimeSurvey\JsonRpc\Client;
use SamIT\LimeSurvey\JsonRpc\Concrete\Answer;
use SamIT\LimeSurvey\JsonRpc\Concrete\Group;
use SamIT\LimeSurvey\JsonRpc\Concrete\Question;

class LimesurveyStub extends Client
{
    private $tokens;

    public function __construct()
    {
        // Don't call parent
        $this->tokens = [
            12345 => [
                'token1' => new TokenStub(12345, [
                    'token' => 'token1',
                ]),
                'token2' => new TokenStub(12345, [
                    'token' => 'token2',

                ]),
            ],
        ];
    }

    public function getUrl($surveyId, array $params = [])
    {
        return 'https://limesurvey/' . $surveyId;
    }

    public function __invoke($name)
    {
        throw new \RuntimeException('Function ' . $name . ' is not stubbed');
    }

    public function getResponsesByToken($surveyId, $token)
    {
        return [];
    }

    public function getGroups($surveyId, $language)
    {
        switch ($surveyId) {
            case 12345:
                return [
                    new Group($this, [
                        'id' => 1,
                        'title' => 'Group title',
                    ], [
                        'surveyId' => $surveyId,

                    ]),
                ];
            default:
                return [];
        }
    }

    public function getSurvey($id, $language = null)
    {
        return new \SamIT\LimeSurvey\JsonRpc\Concrete\Survey($this, [
            'id' => $id,
            'languages' => ['en', 'nl', 'fr'],
            'language' => 'en',
        ], [
        ]);
    }

    public function listSurveys($user = null)
    {
        return [
            [
                'sid' => 12345,
                'surveyls_title' => 'Test title',
                'active' => 'Y',
            ],
            [
                'sid' => 11111,
                'surveyls_title' => 'Another survey title',
                'active' => 'Y',
            ],
        ];
    }

    public function getQuestions($surveyId, $groupId, $language)
    {
        switch ([$surveyId, $groupId]) {
            case [12345, 1]:
                return [
                    new Question($this, [
                        'id' => 1,
                        'index' => 5,
                        'title' => 'noe',
                        'text' => 'random',
                    ]),
                    new Question($this, [
                        'id' => 'MoSD3',
                        'title' => 'MoSD3',
                        'index' => 3,
                        'text' => 'Question text',
                    ], [
                        'answers' => [
                            new Answer($this, [
                                'text' => 'Primary',
                                'code' => 'A1',
                            ]),
                        ],
                    ]),
                ];
            default:
                return [];
        }
    }

    public function createToken($surveyId, array $tokenData, $generateToken = true)
    {
        $result = new TokenStub($surveyId, $tokenData);
        codecept_debug("Creating token with survey {$surveyId}:");
        codecept_debug($tokenData);
        $this->tokens[$surveyId][$result->getToken()] = $result;
        return $result;
    }

    public function getTokens($surveyId, array $attributesConditions = null, $attributeCount = 20, $limit = 10000)
    {
        return $this->tokens[$surveyId] ?? [];
    }

    public function getToken($surveyId, $token, $attributeCount = 20)
    {
        return $this->tokens[$surveyId][$token];
    }
}
