<?php

declare(strict_types=1);

namespace herams\api\models;

use yii\validators\DefaultValueValidator;
use yii\validators\NumberValidator;
use yii\validators\RequiredValidator;
use yii\validators\SafeValidator;

/**
 * @property int $id
 * @property array|null $config
 */
class Element extends \yii\db\ActiveRecord
{
    public function rules(): array
    {
        return [
            [['width', 'height', 'sort', 'page_id'],
                NumberValidator::class,
                'integerOnly' => true,
            ],
            [['page_id', 'height', 'title'], RequiredValidator::class],
            [['colorMap', 'variables', 'groupingVariable'], SafeValidator::class],
            [['dataSort']],
            [['sort'],
                DefaultValueValidator::class,
                'value' => 0,
            ],

        ];
    }

    public function setTitle(string $title): void
    {
        $config = $this->config ?? [];
        if (empty($title)) {
            unset($config['title']);
        } else {
            $config['title'] = $title;
        }
        $this->config = $config;
    }

    public function getTitle(): string|null
    {
        return $this->config['title'] ?? null;
    }

    public function setVariables(array $variables): void
    {
        $config = $this->config ?? [];
        if (empty($variables)) {
            unset($config['variables']);
        } else {
            $config['variables'] = $variables;
        }
        $this->config = $config;
    }

    public function getVariables(): array
    {
        return $this->config['variables'] ?? [];
    }

    public function setColorMap(array $colorMap): void
    {
        $config = $this->config ?? [];
        if (empty($colorMap)) {
            unset($config['colorMap']);
        } else {
            $config['colorMap'] = $colorMap;
        }
        $this->config = $config;
    }

    public function getColorMap(): array
    {
        return $this->config['colorMap'] ?? [];
    }

    public function setGroupingVariable(string|null $name): void
    {
        $config = $this->config ?? [];
        if (empty($name)) {
            unset($config['groupingVariable']);
        } else {
            $config['groupingVariable'] = $name;
        }
        $this->config = $config;
    }

    public function getGroupingVariable(): null|string
    {
        return $this->config['groupingVariable'] ?? null;
    }
}
