<?php
    declare(strict_types=1);

    namespace utils\interfaces;

    use herams\common\models\Survey;

    interface SurveyParserCleanInterface {
        public static function findQuestionInfo(
            Survey $survey,
            string $questionIdentifier
        ): array;
    }

