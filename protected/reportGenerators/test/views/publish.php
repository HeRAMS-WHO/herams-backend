<?php
use yii\helpers\ArrayHelper;
?>
<table>
    <tr>
        <td>Title</td>
        <td><?=ArrayHelper::getValue($userData->getData(), 'test.title')?></td>
    </tr>
    <tr>
        <td>Description</td>
        <td><?=ArrayHelper::getValue($userData->getData(), 'test.description')?></td>
    </tr>
    <tr>
        <td>Chosen options</td>
        <td><ul>
        <?php foreach(ArrayHelper::getValue($userData->getData(), 'test.options', []) as $option) {
            echo '<li>' . $option . '</li>';
        } ?>
        </ul></td>
    </tr>
</table>

<table>
    <tr>
        <td>User</td>
        <td><?=$signature->getName()?></td>
    </tr>
</table>