<?php

namespace prime\interfaces;

use Befound\Components\Map;
use prime\models\Project;

interface UserDataInterface extends \JsonSerializable, \ArrayAccess {

    /**
     * @return array|Map
     */
    public function getData();

}