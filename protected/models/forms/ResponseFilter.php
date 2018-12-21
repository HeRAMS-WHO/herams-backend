<?php


namespace prime\models\forms;


use Carbon\Carbon;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;
use yii\base\Model;

/**
 * Class ResponseFilter implements filtering for Response Collections
 * @package prime\models\forms
 */
class ResponseFilter extends Model
{
    public $date;


    public function filter(array $responses): array
    {
        \Yii::beginProfile('filter');
        $limit = new Carbon($this->date);
        // Index by UOID.
        /** @var ResponseInterface[] $indexed */
        $indexed = [];
        /** @var ResponseInterface $response */
        foreach($responses as $response) {
            // Date filter
            if (!$limit->greaterThan($response->getSubmitDate())) {
                continue;
            }

            $data = $response->getData();
            if (!isset($indexed[$data['UOID']]) || $indexed[$data['UOID']]->getSubmitDate() < $response->getSubmitDate()) {
                $indexed[$data['UOID']] = $response;
            }
        }

        \Yii::endProfile('filter');
        return array_values($indexed);
    }

}