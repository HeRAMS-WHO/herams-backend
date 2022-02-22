<?php

/**
 * Some helper functions in the global namespace.
 */

namespace {

    /**
     *
     */
    function app()
    {
        return \Yii::$app;
    }


    function requireParameter(mixed $value, string $type, string $name)
    {
        if (!$value instanceof $type) {
            throw new InvalidArgumentException("Param {$name} must be of type {$type}");
        }
    }
}

namespace yii\web {

    use prime\values\Id;

    function http_build_query(array $params)
    {
        $realParams = [];
        foreach ($params as $k => $v) {
            if (is_object($v)) {
                $realParams[$k] = (string) $v->getValue();
            } else {
                $realParams[$k] = $v;
            }
        }

        return \http_build_query($realParams);
    }
}
