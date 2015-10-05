<?php

interface GroupInterface {
    /**
     * @return int The unique ID for this survey.
     */
    public function getId();

    /**
     * @return QuestionInterface[]
     */
    public function getQuestions();
}