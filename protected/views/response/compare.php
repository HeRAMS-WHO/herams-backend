<?php

declare(strict_types=1);

use GuzzleHttp\Psr7\Utils;
use prime\components\View;
use prime\interfaces\ColumnDefinition;
use prime\interfaces\HeramsResponseInterface;
use prime\interfaces\WriterInterface;
use prime\models\ar\ResponseForLimesurvey;
use prime\models\forms\Export;
use prime\widgets\Section;
use Psr\Http\Message\StreamInterface;
use SamIT\LimeSurvey\Interfaces\ResponseInterface;
use yii\helpers\Html;

/**
 * @var ResponseForLimesurvey $storedResponse,
 * @var ResponseInterface $limesurveyResponse
 * @var View $this
 */

$this->title = \Yii::t('app', 'Compare data for HF {hf}', [
    'hf' => $storedResponse->hf_id,
]);

$export = new Export($storedResponse->workspace->project->survey);
$export->answersAsText = true;
$writer = new class() implements WriterInterface {
    private $table = [];

    private $columnCount = 0;

    public function writeRecord(
        HeramsResponseInterface $record,
        ColumnDefinition ...$columns
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

    public function getStream(): StreamInterface
    {
        $result = Utils::streamFor('');
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

$this->registerCss(
    <<<CSS
.main,
.main .content {
    max-width: inherit;
    width: 100%;
}

.main {
    padding: 0 30px;
}

.column {
    max-width: 25%;
    display: inline-block;
    vertical-align: top;
}

.column + .column {
    margin-left: 10px;
}

.column h1 {
    height: 4rem;
}
CSS
);

Section::begin();

$export->run($writer, ResponseForLimesurvey::find()->andWhere($storedResponse->getPrimaryKey(true)));

$options = [
    'class' => ['column'],
];

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
echo Html::beginTag('div', $options);
echo Html::tag('h1', 'Response as it would be exported (but vertically)');
echo $writer->getStream()->getContents();
echo Html::endTag('div');

Section::end();
