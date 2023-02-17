<?php
declare(strict_types=1);

namespace herams\api\components;

use yii\web\BadRequestHttpException;

class Request extends \yii\web\Request
{
    /**
     * Retrieves a string value from the body
     * @param string $name
     * @param string|null $defaultValue The default value
     * @return string
     * @throws BadRequestHttpException
     */
    public function getStringBodyParam(string $name, string $defaultValue = null): null|string
    {
        $result = $this->getBodyParam($name, $defaultValue);
        if (!isset($result) || !is_scalar($result)) {
            throw new BadRequestHttpException("Expected body param {$name} to be (compatible with) string");
        }
        return (string) $result;
    }

}
