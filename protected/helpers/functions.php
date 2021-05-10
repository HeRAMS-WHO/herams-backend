<?php
/**
 * Some helper functions in the global namespace.
 */

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
