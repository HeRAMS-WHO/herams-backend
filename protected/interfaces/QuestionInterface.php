<?php

interface QuestionInterface {
    /**
     * @return int The unique ID for this survey.
     */
    public function getId();

    /**
     * Returns all subquestions
     * @return QuestionInterface[]
     */
    public function getQuestions();
}