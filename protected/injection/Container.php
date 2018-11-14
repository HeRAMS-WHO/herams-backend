<?php

namespace prime\injection;

class Container extends \yii\di\Container
{
    protected function build($class, $params, $config)
    {
        $result = parent::build($class, $params, $config);
        // Do setter injection.
        if ($result instanceof SetterInjectionInterface) {
            foreach($result->listDependencies() as $setter => $class) {
                $result->$setter($this->get($class));
            }
        }
        return $result;
    }


}