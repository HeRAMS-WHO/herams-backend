<?php

declare(strict_types=1);

namespace herams\api\domain\permission;

use herams\common\models\RequestModel;
use SamIT\abac\interfaces\Authorizable;
use SamIT\abac\interfaces\Grant;
use yii\validators\RequiredValidator;

class NewPermission extends RequestModel implements Grant
{
    public string $source_id = '';

    public string $source_name = '';

    public string $target_id = '';

    public string $target_name = '';

    public string $permission = '';

    public function rules(): array
    {
        return [
            [['source_id', 'source_name', 'target_id', 'target_name', 'permission'], RequiredValidator::class],
        ];
    }

    public function getSource(): Authorizable
    {
        return new \SamIT\abac\values\Authorizable($this->source_id, $this->source_name);
    }

    public function getTarget(): Authorizable
    {
        return new \SamIT\abac\values\Authorizable($this->target_id, $this->target_name);
    }

    public function getPermission(): string
    {
        return $this->permission;
    }
}
