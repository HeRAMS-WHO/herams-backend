<div class="filters">
    <div class="basic">
        <?php

        use prime\widgets\nestedselect\NestedSelect;
        use yii\helpers\Html;

        echo NestedSelect::widget([
            'placeholder' => 'Location',
            'name' => 'geo',
            'options' => [
            ],
            'items' => [
                    'State 1' => [
                        'Substate' => [
                                'X' => 'Y'
                        ],
                    'D' => 'A',
                    'E' => 'B',
                    'F' => 'C'
                ],
                'State 2' => [
                    'A' => 'A',
                    'B' => 'B',
                    'C' => 'C'
                ],
                'State 3' => [
                    'A' => 'A',
                    'B' => 'B',
                    'C' => 'C'
                ]
            ]

        ]);
        echo Html::input('date');
        echo NestedSelect::widget([
            'name' => 'type',
            'items' => [
                'D' => 'A',
                'E' => 'B',
                'F' => 'C'
            ],
        ]);
        ?>

    </div>
    <div class="advanced">
        <a href="#">Advanced filters</a>
        <a href="#">View advanced filters</a>
    </div>
    <div class="buttons">
        <button>Clear all</button>
        <button>Apply all</button>
    </div>

</div>