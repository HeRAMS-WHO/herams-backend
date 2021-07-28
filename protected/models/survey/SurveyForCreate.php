<?php
declare(strict_types=1);

namespace prime\models\survey;

use prime\traits\DisableYiiLoad;
use yii\base\Model;
use yii\validators\RequiredValidator;

class SurveyForCreate extends Model
{
    use DisableYiiLoad;

    public array $config = [];

    public function rules(): array
    {
        return [
            [['config'], RequiredValidator::class],
        ];
    }
}
