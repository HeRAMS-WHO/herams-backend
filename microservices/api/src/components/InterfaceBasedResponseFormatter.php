<?php
declare(strict_types=1);

namespace herams\api\components;

use yii\web\ResponseFormatterInterface;

class InterfaceBasedResponseFormatter implements ResponseFormatterInterface
{

    public function format($response)
    {
        var_dump($response);
        die();
        // TODO: Implement format() method.
    }
}
