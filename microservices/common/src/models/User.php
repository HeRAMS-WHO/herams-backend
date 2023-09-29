<?php

declare(strict_types=1);

namespace herams\common\models;

/**
 * Attributes
 * @property int $id
 * @property string $email
 * @property string $password_hash
 * @property string $name
 * @property string $language
 * @property int $newsletter_subscription
 * @property string $created_at
 * @property int|null $updated_at
 */
class User extends ActiveRecord {
    public static function tableName(): string {
        return '{{%user}}';
    }
    public function fields(): array {
        $fields = parent::fields();
        unset($fields['password_hash']);
        return $fields;
    }
}