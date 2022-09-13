<?php

declare(strict_types=1);

namespace prime\widgets\AgGrid;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

class AgGrid extends Widget
{
    public string|array $route;

    public array $columns = [];

    public function init()
    {
        parent::init();
        $this->view->registerAssetBundle(AgGridBundle::class);
    }

    public function run()
    {
        $config = Json::encode([
            'id' => $this->getId(),
            'gridOptions' => [
                'columnDefs' => $this->columns,
                'defaultColDef' => [
                    'filter' => 'agTextColumnFilter',
                    'floatingFilter' => true,
                    'resizable' => true,
                    'sortable' => true,
                    'minWidth' => 100,
                ],
            ],
            'url' => Url::to($this->route),

        ]);
        $this->view->registerJs(<<<JS
        
            
            (async () => {
            const config = {$config};
            const data = await Herams.fetchWithCsrf(config.url, null, 'GET');
            console.log('data loaded', data);
          
            const gridOptions = {
                ...config.gridOptions,
                rowData: data,
                onGridReady: (params) => {
                    
                    // params.columnApi.sizeColumnsToFit(1300)
                    params.columnApi.autoSizeAllColumns()
                }
            }
            const grid = new agGrid.Grid(document.getElementById(config.id), gridOptions);
            window.grid = grid
           
            })();
        JS);
        return Html::tag('div', '', [
            'id' => $this->getId(),
            'style' => [
                //                'width' => '100%',
                //                'height' => '500px',
                //                'background-color' => 'red'
            ],
            'class' => ['ag-theme-alpine'],

        ]);
    }
}
