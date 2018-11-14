<?php

namespace prime\interfaces;

interface UserDataInterface extends \JsonSerializable, \ArrayAccess {

    /**
     * @return array
     */
    public function getData();

}