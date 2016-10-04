<?php
namespace prime\interfaces;

use Psr\Http\Message\StreamInterface;

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

    /**
     * Return the tool image.
     * @return StreamInterface
     */
    public function getImage();
}