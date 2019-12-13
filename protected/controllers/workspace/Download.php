<?php


namespace prime\controllers\workspace;


use prime\interfaces\HeramsResponseInterface;
use prime\models\ar\Workspace;
use prime\models\permissions\Permission;
use SamIT\LimeSurvey\Interfaces\AnswerInterface;
use SamIT\LimeSurvey\Interfaces\QuestionInterface;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use SamIT\LimeSurvey\JsonRpc\Concrete\Survey;
use yii\base\Action;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\User;
use function iter\toArray;
use function iter\toArrayWithKeys;

class Download extends Action
{
    public function run(
        Response $response,
        User $user,
        int $id,
        $text = false
    ) {
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
                // Extract each question separately.
                $questions[$question->getTitle()] = $question;
            }
        }
        $rows = [];
        /** @var HeramsResponseInterface $record */
        foreach($workspace->getResponses()->each() as $record) {
            echo '<pre>';
            var_dump($record->getRawData());
            $rows[] = $row =  toArrayWithKeys($this->getRow($questions, $record));
            var_dump($row); die();
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

    /**
     * @var QuestionInterface[] $questions
     */
    private function getRow(
        array $questions,
        HeramsResponseInterface $record

    ) {
        $data = $record->getRawData();

        foreach($questions as $question) {
            // Extract each question separately.
            switch ($question->getDimensions()) {
                case 0:
                    $answers = $question->getAnswers();
                    // Open question
                    if ($answers === null) {
                        yield $question->getTitle() => $data[$question->getTitle()] ?? null;
                    } else {
                    // Single choice
                        $map = ArrayHelper::map($answers,
                            function(AnswerInterface $a) { return $a->getCode(); },
                            function(AnswerInterface $a) { return $a->getText(); }
                        );
                        yield $question->getTitle() => $map[$data[$question->getTitle()] ?? null] ?? null;
                    }
                    break;
                case 1:
                    foreach($question->getQuestions(0) as $subQuestion) {
                        $answers = $subQuestion->getAnswers();
                        if ($answers === null) {
                            // Open question
                            yield "{$question->getTitle()}[{$subQuestion->getTitle()}]" => $data["{$question->getTitle()}[{$subQuestion->getTitle()}]"] ?? null;
                        } else {
                            // Closed
                            $value = $data[$question->getTitle()][$subQuestion->getTitle()] ?? null;

                            $map = ArrayHelper::map($subQuestion->getAnswers(),
                                function(AnswerInterface $a) { return $a->getCode(); },
                                function(AnswerInterface $a) { return $a->getText(); }
                            );

                            yield "{$question->getTitle()}[{$subQuestion->getTitle()}]" => $map[$value] ?? $value ?? null;
                        }
                    }
                    break;
                case 2:
                    $rowQuestions = $question->getQuestions(0);
                    usort($rowQuestions, function(QuestionInterface $a, QuestionInterface $b) {
                        return $a->getIndex() <=> $b->getIndex();
                    });
                    foreach($rowQuestions as $rowQuestion) {
                        $cells = $rowQuestion->getQuestions(0);
                        usort($cells, function(QuestionInterface $a, QuestionInterface $b) {
                            return $a->getIndex() <=> $b->getIndex();
                        });
                        foreach($cells as $cell) {
                            $code = "{$question->getTitle()}[{$rowQuestion->getTitle()}_{$cell->getTitle()}]";
                            yield $code => $data[$code] ?? null;
                        }
                    }
                    break;
                default:
            }
        }
    }
}