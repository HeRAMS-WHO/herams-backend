<?php
declare(strict_types=1);

namespace prime\helpers;

class AgGridHelper
{
    public static function generateColumnTypeDate (
        string $textToTranslate,
        string $fieldName,
        string $language = 'en-US'
    ) : array
    {
      return [
          'headerName' => \Yii::t('app', $textToTranslate),
          'field' => $fieldName,
          'filter' => 'agDateColumnFilter',
          'filterParams' => new \yii\web\JsExpression(<<<JS
                {
                    'newRowsAction' : 'keep',
                    'suppressAndOrCondition': true, 
                    comparator: function(filterLocalDateAtMidnight, cellValue) {
                        var dateParts = cellValue.indexOf('-') > -1 ? cellValue.split("-") : cellValue.split("/");
                        var isISO = dateParts[0].length === 4;
                        var cellYear = isISO ? Number(dateParts[0]) : Number(dateParts[2]);
                        var cellMonth = isISO ? Number(dateParts[1]) - 1 : Number(dateParts[0]) - 1;
                        var cellDay = isISO ? Number(dateParts[2]) : Number(dateParts[1]);
                        var cellDate = new Date(cellYear, cellMonth, cellDay).setHours(0, 0, 0, 0);
                        var filterYear = filterLocalDateAtMidnight.getFullYear();
                        var filterMonth = filterLocalDateAtMidnight.getMonth();
                        var filterDay = filterLocalDateAtMidnight.getDate();
                        var filterDate = new Date(filterYear, filterMonth, filterDay).setHours(0, 0, 0, 0);
                        if (cellDate === filterDate) {
                            return 0;
                        }
                        if (cellDate < filterDate) {
                            return -1;
                        }
                        return 1;
                    }
                }
            JS),
          'cellRenderer' => new \yii\web\JsExpression(<<<JS
                function(params) {
                    return params.value === '0000-00-00' ? '' : params.value;
                }
            JS)
      ];
    }
}