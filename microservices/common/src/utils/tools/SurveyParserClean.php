<?php
    declare(strict_types=1);

    namespace herams\common\utils\tools;
    use herams\common\models\Survey;
    use herams\common\utils\interfaces\SurveyParserCleanInterface;

    final class SurveyParserClean implements SurveyParserCleanInterface {

        public static function findQuestionInfo(
            Survey $survey,
            string $questionIdentifier
        ) : array
        {
            foreach($survey->config['pages'] as $page){
                foreach($page['elements'] as $question){
                    if ($question['name'] === $questionIdentifier){
                        return $question;
                    }
                }
            }
            return [];
        }
    }