<?php

namespace prime\interfaces;

use Befound\Components\Map;

interface UserDataInterface extends \JsonSerializable, \ArrayAccess {

    /**
     * @return array|Map
     */
    public function getData();

}