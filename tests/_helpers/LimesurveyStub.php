<?php


namespace prime\tests\_helpers;


class LimesurveyStub
{
    public function __invoke($name)
    {
        throw new \RuntimeException('Function ' . $name . ' is not stubbed');
    }


    public function listSurveys(): array
    {
        return [
            [
                'sid' => 12345,
                'surveyls_title' => 'Test title',
                'active' => 'Y'
            ]
        ];
    }

    public function createToken($surveyId, array $tokenData, $generateToken = true)
    {
        return new TokenStub($surveyId, $tokenData, $generateToken);
    }

}