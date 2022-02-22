<?php

namespace prime\widgets\InlineUpload;

use yii\helpers\Html;
use yii\widgets\InputWidget;

class InlineUpload extends InputWidget
{
    public function run(): string
    {
        parent::run();
        $this->options['style']['min-height'] = '500px';
        ob_start();
        if ($this->hasModel()) {
            echo Html::activeTextarea($this->model, $this->attribute, $this->options);
        } else {
            echo Html::textarea($this->name, $this->value, $this->options);
        }
        echo Html::fileInput('file', null, [
            'form' => 'none',
            'id' => $this->options['id'] . '-f'
        ]);
        $this->view->registerJs(<<<JS
    document.getElementById("{$this->options['id']}-f").addEventListener('change', function() {
        let input = document.getElementById('{$this->options['id']}');
        if (this.files.length === 1) {
            let url = URL.createObjectURL(this.files[0]);
            
            let fileReader = new FileReader();
            
            fileReader.onload = function(event) {
                input.value = event.target.result;
            };
            fileReader.readAsText(this.files[0]);
            
        } else {
            input.value = input.getAttribute('data-default');
            $(input).trigger('change');
        }
    })
    
JS
        );

        return ob_get_clean();
    }
}
