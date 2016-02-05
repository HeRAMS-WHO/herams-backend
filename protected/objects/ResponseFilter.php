<?php

namespace prime\objects;

use prime\interfaces\ResponseCollectionInterface;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;

class ResponseFilter
{
    protected $responses = [];
    protected $filteredResponses = [];
    protected $groupedResponses = [];

    public function __construct(array $responses)
    {
        foreach($responses as $response) {
            /** @var ResponseInterface $response */
            $this->responses[] = $response;
        }
        $this->filteredResponses = $this->responses;
    }

    /**
     * Just like an array_filter, supply a closure, if true => keep
     * @param \Closure|null $closure
     */
    public function filter($closure = null)
    {
        $this->filteredResponses = [];
        foreach($this->responses as $response) {
            if($closure instanceof \Closure && $closure($response)) {
                $this->filteredResponses[] = $response;
            }
        }
    }

    public function getFilteredResponses()
    {
        return $this->filteredResponses;
    }

    public function getGroups()
    {
        return $this->groupedResponses;
    }

    /**
     * Group the responses by the value in the $byKey data value
     * @param string $byKey
     */
    public function group($byKey)
    {
        $this->groupedResponses = [];
        foreach($this->filteredResponses as $response) {
            /** @var ResponseInterface $response */
            if(isset($response->getData()[$byKey])) {
                $groupKey = $response->getData()[$byKey];
                if(!isset($this->groupedResponses[$groupKey])) {
                    $this->groupedResponses[$groupKey] = [];
                }
                $this->groupedResponses[$groupKey][] = $response;
            }
        }
    }

    public function sortGroupsInternally(\Closure $sort)
    {
        foreach($this->groupedResponses as $key => &$responses) {
            usort($responses, $sort);
        }
    }

    public function sortGroups(\Closure $sort)
    {
        usort($this->groupedResponses, $sort);
    }
}