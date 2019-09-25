<?php


namespace prime\controllers\workspace;


use prime\interfaces\HeramsResponseInterface;
use prime\models\ar\Workspace;
use prime\models\permissions\Permission;
use SamIT\LimeSurvey\Interfaces\QuestionInterface;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;
use SamIT\LimeSurvey\JsonRpc\Concrete\Survey;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\User;

class Download extends Action
{
    public function run(
        Response $response,
        User $user,
        int $id,
        $text = false
    ) {
        $codeAsText = $text;
        $workspace = Workspace::findOne(['id' => $id]);
        if (!isset($workspace)) {
            throw new NotFoundHttpException();
        }
        if (!(
            $user->can(Permission::PERMISSION_ADMIN, $workspace)
            || $user->can(Permission::PERMISSION_WRITE, $workspace->project)
        )) {
            throw new ForbiddenHttpException();
        }

        /** @var Survey $survey */
        $survey = $workspace->project->getSurvey();
        /** @var QuestionInterface[] $questions */
        $questions = [];
        foreach($survey->getGroups() as $group) {
            foreach($group->getQuestions() as $question) {
                $questions[$question->getTitle()] = $question;
            }
        }
        $rows = [];
        $codes = [];
        /** @var HeramsResponseInterface $record */
        foreach($workspace->responses as $record) {
            $row = [];
            foreach ($record->getRawData() as $code => $value) {
                if (null !== $question = $survey->getQuestionByCode($code)) {
                    $text = $question->getText();
                    $answer = $this->getAnswer($question, $value, $codeAsText);


                } elseif (preg_match('/^(.+)\[(.*)\]$/', $code,
                        $matches) && null !== $question = $survey->getQuestionByCode($matches[1])
                ) {
                    if (null !== $sub = $question->getQuestionByCode($matches[2])) {
                        $text = $sub->getText();
                        $answer = $this->getAnswer($sub, $value, $text);
                    } elseif ($question->getDimensions() == 2 && preg_match('/^(.+)_(.+)$/', $matches[2],
                            $subMatches)
                    ) {
                        if (null !== ($sub = $question->getQuestionByCode($subMatches[1], 0))
                            && null !== $sub2 = $question->getQuestionByCode($subMatches[2], 1)
                        ) {
                            $text = $sub->getText() . ' - ' . $sub2->getText();
                            $answer = $this->getAnswer($sub2, $value, $text);
                        } else {
                            throw new \RuntimeException("Could not find subquestions for 2 dimensional question.");

                        }
                    } else {
                        $text = "Not found";
                        $answer = $value;
                    }
                } else {
                    $text = $code;
                    $answer = $value;
                }
//                echo str_pad($code, 20) . " | " . str_pad(is_null($value) ? 'NULL' : $value, 20) . " | ";
//                echo str_pad(trim(strip_tags($answer)), 40) . ' | ';
//                echo trim(strip_tags($text));
//                echo "\n";
                $codes[$text] = trim($code);
                $row[$text] = trim($answer);
            }
            $rows[] = $row;
        }

        $stream = fopen('php://temp', 'w+');
        // First get all columns.
        $columns = [];
        foreach($rows as $row) {
            foreach($row as $key => $dummy) {
                $columns[$key] = true;
            }
        }

        if (!empty($columns)) {
            fputcsv($stream, array_keys($columns));
            $header = [];
            foreach(array_keys($columns) as $columnName) {
                $header[] = $codes[$columnName];
            }
            fputcsv($stream, $header);

            /** @var ResponseInterface $record */
            foreach ($rows as $data) {
                $row = [];
                foreach(array_keys($columns) as $column) {
                    $row[$column] = isset($data[$column]) ? $data[$column] : null;
                }
                fputcsv($stream, $row);
            }
        }
        return $response->sendStreamAsFile($stream, "{$workspace->title}.csv", [
            'mimeType' => 'text/csv'
        ]);
    }

    private function getAnswer(QuestionInterface $q, $value, $text = false)
    {
        if (empty($value)) {
            return "(not set)";
        } elseif ($text && (null !== $answers = $q->getAnswers())) {
            foreach($answers as $answer) {
                if ($answer->getCode() == $value) {
                    return $answer->getText();
                }
            }
            return "Invalid answer : `$value`.";
        } else {
            return $value;
        }

    }

}