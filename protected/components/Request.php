<?php

namespace app\components;

use Neomerx\JsonApi\Contracts\Integration\CurrentRequestInterface;

class Request extends \Befound\web\Request implements CurrentRequestInterface
{


    /**
     * Get content.
     *
     * @return string|null
     */
    public function getContent()
    {
        // TODO: Implement getContent() method.
    }

    /**
     * Get inputs.
     *
     * @return array
     */
    public function getQueryParameters()
    {
        return $this->getQueryParams();
        // TODO: Implement getQueryParameters() method.
    }

    /**
     * Get header value.
     *
     * @param string $name
     *
     * @return string
     */
    public function getHeader($name)
    {
        // TODO: Implement getHeader() method.
    }
}