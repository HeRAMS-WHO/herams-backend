<?php
declare(strict_types=1);

namespace prime\components;

use Carbon\Carbon;
use prime\interfaces\AuditServiceInterface;
use prime\interfaces\NewAuditEntryInterface;
use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\base\NotSupportedException;
use yii\web\Application;

class AuditService extends Component implements BootstrapInterface, AuditServiceInterface
{
    public string $table = '{{%audit}}';
    /**
     * @var NewAuditEntryInterface[]
     */
    private array $entries = [];
    private Application $application;

    public bool $enabled = true;

    public function add(NewAuditEntryInterface $entry): void
    {
        if ($this->enabled) {
            $this->entries[] = [
                $entry,
                $this->getUserId(),
            ];
        }
    }

    private function getUserId(): int
    {
        return $this->application->user->id;
    }

    public function bootstrap($app): void
    {
        if (!$app instanceof Application) {
            throw new NotSupportedException('This service only supports web application');
        }
        $this->application = $app;
        $app->on(Application::EVENT_AFTER_REQUEST, \Closure::fromCallable([$this, 'commit']));
    }

    public function commit(): void
    {
        if (empty($this->entries)) {
            return;
        }

        $rows = [];
        $timestamp = Carbon::now();
        foreach ($this->entries as list($entry, $userId)) {
            $rows[] = [
                $entry->getSubjectName(),
                $entry->getSubjectId(),
                $entry->getEvent(),
                $timestamp,
                $userId
            ];
        }

        try {
            $this->application->getDb()->createCommand()->batchInsert(
                $this->table,
                ['subject_name', 'subject_id', 'event', 'created_at', 'created_by'],
                $rows
            )->execute();
        } catch (\Exception $e) {
            \Yii::error($rows, 'auditservice.uncommitted');
            \Yii::error($e);
        }
        $this->entries = [];
    }
}
