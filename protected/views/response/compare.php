<?php
declare(strict_types=1);

use prime\models\ar\Permission;
use yii\helpers\Html;

/**
 * @var \prime\models\ar\Response $storedResponse,
 * @var \SamIT\LimeSurvey\Interfaces\ResponseInterface $limesurveyResponse
 * @var \prime\components\View $this
 */

$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Admin dashboard'),
    'url' => ['/admin']
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Projects'),
    'url' => ['/project']
];
$this->params['breadcrumbs'][] = [
    'label' => $storedResponse->workspace->project->title,
    'url' => app()->user->can(Permission::PERMISSION_WRITE, $storedResponse->workspace->project) ? ['project/update', 'id' => $storedResponse->workspace->project->id] : null
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Workspaces'),
    'url' => ['/project/workspaces', 'id' => $storedResponse->workspace->project->id]
];
$this->params['breadcrumbs'][] = [
    'label' => $storedResponse->workspace->title,
    'url' => app()->user->can(Permission::PERMISSION_WRITE, $storedResponse->workspace) ? ['workspace/update', 'id' => $storedResponse->workspace->id] : null
];
$this->params['breadcrumbs'][] = [
    'label' => \Yii::t('app', 'Responses'),
    'url' => ['workspace/responses', 'id' => $storedResponse->workspace->id]
];
$this->title = \Yii::t('app', 'Compare data for HF {hf}', ['hf' => $storedResponse->hf_id]);
//$this->params['breadcrumbs'][] = $this->title;

$options = ['style' => [
    'width' => '32%',
    'display' => 'inline-block',
    'vertical-align' => 'top'
]];

$export = new \prime\models\forms\Export($storedResponse->workspace->project->survey);
$export->answersAsText = true;
$writer = new class implements \prime\interfaces\WriterInterface {
    private $table = [];
    private $columnCount = 0;
    public function writeRecord(
        \prime\interfaces\HeramsResponseInterface $record,
        \prime\interfaces\ColumnDefinition ...$columns
    ): void {
        foreach ($columns as $i => $column) {
            $this->table[$i][$this->columnCount] = $column->getValue($record);
        }
        $this->columnCount++;
    }

    public function writeHeader(string ...$headers): void
    {
        foreach ($headers as $i => $header) {
            $this->table[$i][$this->columnCount] = $header;
        }
        $this->columnCount++;
    }

    public function getStream(): \Psr\Http\Message\StreamInterface
    {
        $result = \GuzzleHttp\Psr7\stream_for('');
        $result->write('<table>');
        $result->write('<tr>');
        $result->write(Html::tag('th', 'Text'));
        $result->write(Html::tag('th', 'Code'));
        $result->write(Html::tag('th', 'Value'));
        $result->write('</tr>');
        foreach ($this->table as $row) {
            $result->write('<tr>');
            foreach ($row as $cell) {
                $result->write(Html::tag('td', $cell));
            }
            $result->write('</tr>');
        }
        $result->write('</table>');
        $this->table = [];
        $this->columnCount = 0;
        $result->rewind();
        return $result;
    }

    public function getMimeType(): string
    {
        return 'text/html';
    }
};
$export->run($writer, \prime\models\ar\Response::find()->andWhere($storedResponse->getPrimaryKey(true)));
echo Html::beginTag('div', $options);
    echo Html::tag('h1', 'Our latest data');
    echo Html::tag('pre', print_r($storedResponse->data, true));
echo Html::endTag('div');
echo Html::beginTag('div', $options);
    echo Html::tag('h1', 'Our interpretation of the LS data');
    $loader = new \prime\helpers\LimesurveyDataLoader();
    $loader->loadData($limesurveyResponse->getData(), $storedResponse->workspace, $storedResponse);
    echo Html::tag('pre', print_r($storedResponse->data, true));
echo Html::endTag('div');
echo Html::beginTag('div', $options);
    echo Html::tag('h1', 'Data fresh from LS');
    echo Html::tag('pre', print_r($limesurveyResponse->getData(), true));
echo Html::endTag('div');
echo Html::beginTag('div');
echo Html::tag('h1', 'Response as it would be exported (but vertically)');
echo $writer->getStream()->getContents();
echo Html::endTag('div');
