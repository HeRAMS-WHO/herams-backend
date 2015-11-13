<?php

namespace prime\interfaces;

use Befound\Components\Map;
use prime\models\ar\Project;

interface UserDataInterface extends \JsonSerializable, \ArrayAccess {

    /**
     * @return array|Map
     */
    public function getData();

}