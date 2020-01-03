<?php
declare(strict_types=1);

namespace prime\actions;


use GuzzleHttp\Psr7\StreamWrapper;
use prime\models\forms\CsvExport;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\web\ForbiddenHttpException;
use yii\web\Request;
use yii\web\Response;
use yii\web\User;

class ExportCsvAction extends Action
{
    /**
     * @var \Closure
     */
    public $checkAccess;
    /**
     * @var \Closure
     */
    public $responseIterator;
    /**
     * @var \Closure
     */
    public $surveyFinder;
    public $view = 'export';

    public function init()
    {
        parent::init();
        if (!$this->responseIterator instanceof \Closure) {
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
        if (!($this->checkAccess)($request, $user)) {
            throw new ForbiddenHttpException();
        }
        $survey = ($this->surveyFinder)($request);

        $model = new CsvExport($survey);
        if ($request->isPost && $model->load($request->bodyParams) && $model->validate()) {
            $stream = StreamWrapper::getResource($model->run(($this->responseIterator)($request)));
            return $response->sendStreamAsFile($stream, date('Ymd his') . '.csv', [
                'mimeType' => 'text/csv'
            ]);
        } else {
            return $this->controller->render($this->view, ['model' => $model]);
        }
    }


}