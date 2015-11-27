<?php
namespace prime\interfaces;

interface ProjectInterface {

    /**
     * Returns the name of the location of the project
     * @return string
     */
    public function getLocality();

    /**
     * Return the url to the tool image
     * @return string
     */
    public function getToolImagePath();
}