<?php
declare(strict_types=1);

namespace prime\helpers;

class AgGridHelper
{
    public static function generateColumnTypeDate (
        string $textToTranslate,
        string $colName
    ) : array
    {
      return [
          'headerName' => \Yii::t('app', $textToTranslate),
          'field' => $colName,
          'filter' => 'agDateColumnFilter',
          'filterParams' => new \yii\web\JsExpression(<<<JS
                {
                    comparator: function(filterLocalDateAtMidnight, cellValue) {
                        var dateParts = cellValue.split("-");
                        var cellYear = Number(dateParts[0]);
                        var cellMonth = Number(dateParts[1]) - 1; // Months are 0-based in JS
                        var cellDay = Number(dateParts[2]);
                        var cellDate = new Date(cellYear, cellMonth, cellDay);
        
                        var filterYear = filterLocalDateAtMidnight.getFullYear();
                        var filterMonth = filterLocalDateAtMidnight.getMonth();
                        var filterDay = filterLocalDateAtMidnight.getDate();
                        var filterDate = new Date(filterYear, filterMonth, filterDay);
        
                        if (cellDate.getTime() === filterDate.getTime()) {
                            return 0;
                        }
                        if (cellDate < filterDate) {
                            return -1;
                        }
                        if (cellDate > filterDate) {
                            return 1;
                        }
                    }
                }
            JS),
          'cellRenderer' => new \yii\web\JsExpression(<<<JS
        function(params) {
            if (params.value) {
                var dateParts = params.value.split("-");
                var dateObject = new Date(Number(dateParts[0]), Number(dateParts[1]) - 1, Number(dateParts[2]));
                return (dateObject.getMonth() + 1) + '/' + dateObject.getDate() + '/' + dateObject.getFullYear();
            }
            return '';
        }
        JS)
      ];
    }
}