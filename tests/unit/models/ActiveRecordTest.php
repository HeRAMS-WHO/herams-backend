<?php

declare(strict_types=1);

namespace prime\tests\unit\models;

use Codeception\Test\Unit;
use prime\components\ActiveQuery;
use prime\models\ActiveRecord;

class ActiveRecordTest extends Unit
{
    public function testGetDisplayField(): void
    {
        $model = new class() extends ActiveRecord {
            public function attributes()
            {
                return [
                    'title',
                    'name',
                    'email',
                ];
            }

            public function getPrimaryKey($asArray = false)
            {
                return ['primary', 'key'];
            }
        };
        $model->title = 'title';
        $model->name = 'name';
        $model->email = 'email';

        $this->assertTrue($model->hasAttribute('title'));
        $this->assertSame('title', $model->getAttribute('title'));
        $this->assertSame('title', $model->getDisplayField());

        $model->title = null;

        $this->assertSame('name', $model->getDisplayField());
        $model->name = null;
        $this->assertSame('email', $model->getDisplayField());
        $model->email = null;
        $this->assertStringContainsString('primary', $model->getDisplayField());
        $this->assertStringContainsString('key', $model->getDisplayField());
    }
}
