<?php
/**
 * User: sam
 * Date: 9/8/15
 * Time: 9:44 AM
 */
interface ResponseInterface {

    /**
     * @return int
     */
    public function getSurveyId();

    /**
     * @return string
     */
    public function getId();

}