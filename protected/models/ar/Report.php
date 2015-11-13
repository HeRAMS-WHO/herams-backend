<?php

namespace prime\models\ar;

use Befound\ActiveRecord\Behaviors\DateTimeBehavior;
use Befound\ActiveRecord\Behaviors\JsonBehavior;
use Befound\Components\DateTime;
use prime\components\ActiveRecord;
use prime\interfaces\ReportInterface;
use prime\interfaces\SignatureInterface;
use prime\interfaces\UserDataInterface;
use prime\models\ar\Project;
use prime\models\ar\User;
use prime\models\ar\UserData;
use prime\objects\Signature;
use Psr\Http\Message\StreamInterface;

/**
 * Class Report
 * @package prime\models
 * @property Project $project
 */
class Report extends ActiveRecord implements ReportInterface
{
    public function beforeSave($insert)
    {
        if(!$insert) {
            throw new \Exception(\Yii::t('app', 'A report cannot be updated'));
        }
        return parent::beforeSave($insert);
    }

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                DateTimeBehavior::class => [
                    'class' => DateTimeBehavior::class
                ],
                JsonBehavior::class => [
                    'class' => JsonBehavior::class,
                    'jsonAttributes' => ['user_data']
                ]
            ]
        );
    }


    /**
     * Returns the Mime type of the body
     * @return string
     */
    public function getMimeType()
    {
        return $this->mime_type;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::class, ['id', 'project_id']);
    }

    /**
     * Returns all the data that the user entered to "sign" the report when publishing.
     * @return SignatureInterface
     */
    public function getSignature()
    {
        return new Signature(
            $this->email,
            $this->user_id,
            $this->name,
            \DateTimeImmutable::createFromFormat(DateTime::MYSQL_DATETIME, $this->time)
        );
    }

    /**
     * Returns the body of the report (ie pdf or html)
     * @return StreamInterface
     */
    public function getStream()
    {
        return \GuzzleHttp\Psr7\stream_for($this->data);
    }

    /**
     * Returns all other (with respect to getSignatureData) the data the user entered when generating the report
     * @return UserDataInterface
     */
    public function getUserData()
    {
        return new UserData([
            'project_id' => $this->project_id,
            'generator' => $this->generator,
            'data' => $this->user_data
        ]);
    }

    public function rules()
    {
        return [
            [['data', 'mime_type', 'email', 'user_id', 'name', 'time', 'published', 'user_data', 'project_id', 'generator'], 'required']
        ];
    }

    public static function saveReport(ReportInterface $report, $projectId, $generator)
    {
        $report = new self([
            'data' => $report->getStream(),
            'mime_type' => $report->getMimeType(),
            'email' => $report->getSignature()->getEmail(),
            'user_id' => $report->getSignature()->getId(),
            'name' => $report->getSignature()->getName(),
            'time' => $report->getSignature()->getTime()->format(DateTime::MYSQL_DATETIME),
            'published' => new DateTime(),
            'user_data' => $report->getUserData()->getData(),
            'project_id' => $projectId,
            'generator' => $generator
        ]);

        if($report->save()) {
            return $report;
        } else {
            return null;
        }
    }

    public function userCan($operation, User $user = null)
    {
        $result = parent::userCan($operation, $user);
        if(!$result) {
            $result = $this->project->userCan($operation, $user);
        }
        return $result;
    }


}