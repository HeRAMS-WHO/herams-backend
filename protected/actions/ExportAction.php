<?php
declare(strict_types=1);

namespace prime\actions;

use GuzzleHttp\Psr7\StreamWrapper;
use prime\helpers\CsvWriter;
use prime\helpers\PeclWriter;
use prime\models\forms\Export;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\Response;
use yii\web\User;

class ExportAction extends Action
{
    /** @var \Closure */
    public $subject;
    /**
     * @var \Closure
     */
    public $checkAccess;
    /**
     * @var \Closure
     */
    public $responseQuery;
    /**
     * @var \Closure
     */
    public $surveyFinder;

    public $view = 'export';

    public function init()
    {
        parent::init();
        if (!$this->subject instanceof \Closure) {
            throw new InvalidConfigException('Subject must be a closure');
        }
        if (!$this->responseQuery instanceof \Closure) {
            throw new InvalidConfigException('Response iterator must be a closure');
        }
        if (!$this->surveyFinder instanceof \Closure) {
            throw new InvalidConfigException('Survey finder must be a closure');
        }
        if (!$this->checkAccess instanceof \Closure) {
            throw new InvalidConfigException('Checkaccess must be a closure');
        }
    }

    public function run(
        Request $request,
        Response $response,
        User $user
    ) {
        $subject = ($this->subject)($request);
        if (!isset($subject)) {
            throw new NotFoundHttpException();
        } elseif (!($this->checkAccess)($subject, $user)) {
            throw new ForbiddenHttpException();
        }
        $survey = ($this->surveyFinder)($subject);

        $model = new Export($survey);
        $params = $request->bodyParams;
        if ($model->load($params) && $model->validate()) {
            switch ($params['format'] ?? 'csv') {
                case 'xlsx':
                    $writer = new PeclWriter();
                    break;
                case 'csv':
                    $writer = new CsvWriter();
                    break;
                default:
                    throw new BadRequestHttpException();
            }
            set_time_limit(300);

            $model->run($writer, ($this->responseQuery)($subject));
            $stream = $writer->getStream();
            $extension = FileHelper::getExtensionsByMimeType($writer->getMimeType())[0] ?? 'unknown';
            return $response->sendStreamAsFile(StreamWrapper::getResource($stream), date('Ymd his') . ".$extension", [
                'mimeType' => $writer->getMimeType(),
                'fileSize' => $stream->getSize()
            ]);
        } else {
            return $this->controller->render($this->view, ['model' => $model, 'subject' => $subject]);
        }
    }
}
