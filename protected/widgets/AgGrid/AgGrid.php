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
        //        $this->view->registerAssetBundle(AgGridPremiumBundle::class);
    }

    public function run()
    {
        $config = Json::encode([
            'id' => $this->getId(),
            'gridId' => "{$this->getId()}-grid",

            'gridOptions' => [
                'columnDefs' => $this->columns,
                'animateRows' => true,
                'defaultColDef' => [
                    'filter' => 'agTextColumnFilter',
                    'floatingFilter' => true,
                    'resizable' => true,
                    'sortable' => true,
                    'minWidth' => 100,
                    'enablePivot' => true,
                    'pivot' => true,
                    'enableValue' => true,
                    'enableRowGroup' => true,
                ],
                'sideBar' => 'columns',
                //                'pivotMode' => true,

            ],
            'url' => Url::to($this->route),

        ]);
        $this->view->registerJs(<<<JS
        
            
            (async () => {
            const config = {$config};
            
            const columnStateKey = [config.gridId, window.location.pathname, 'columnState'].join('|');
            const filterStateKey = [config.gridId, window.location.pathname, 'filterState'].join('|');
            const storage = {
                set columnState(value) {
                    localStorage.setItem(columnStateKey, JSON.stringify(value))
                },
                get columnState() {
                    return JSON.parse(localStorage.getItem(columnStateKey))
                },
                set filterState(value) {
                    localStorage.setItem(filterStateKey, JSON.stringify(value))
                },
                get filterState() {
                    return JSON.parse(localStorage.getItem(filterStateKey))
                }
            }
            const gridOptions = {
                ...config.gridOptions,
                // rowData: data,
                onGridReady: (params) => {
                    params.columnApi.autoSizeAllColumns(false)
                    const columnState = storage.columnState
                    if (columnState) {
                        params.columnApi.applyColumnState({ 
                            state: columnState,
                            applyOrder: true
                        })
                    }
                    // Sets the filter model via the grid API
                    gridOptions.api.setFilterModel(storage.filterState);
                    
                    // params.columnApi.sizeColumnsToFit()
                },
                onColumnMoved: (params) => storage.columnState = params.columnApi.getColumnState(),
                onSortChanged: (params) => storage.columnState = params.columnApi.getColumnState(),
                onColumnVisible: (params) => storage.columnState = params.columnApi.getColumnState(), 
                onColumnResized: (params) => storage.columnState = params.columnApi.getColumnState(),
                onFilterChanged: (params) => storage.filterState = params.api.getFilterModel(),               
            }
            const grid = new agGrid.Grid(document.getElementById(config.gridId), gridOptions);
            
            gridOptions.api.showLoadingOverlay()
            
            document.getElementById(config.id).querySelector('button.reset').addEventListener('click', () => {
                gridOptions.columnApi.resetColumnState();
                // gridOptions.api.resetFilterValues();
            })
            
            
            
            
            window.grid = grid
            const data = await Herams.fetchWithCsrf(config.url, null, 'GET');
            gridOptions.api.setRowData(data);
            
            })();
        JS);

        return Html::tag(
            'section',
            Html::tag(
                'div',
                Html::button('Reset grid', [
                    'class' => 'reset',
                    'style' => [
                        'border' => 'none',
                    ],
                ]),
                [
                    'style' => [
                        'justify-content' => 'end',
                        'display' => 'flex',
                        'border' => '0px solid var(--ag-border-color)',
                        'border-bottom' => 'none',
                    ],
                ]
            )
            . Html::tag('div', '', [
                'id' => "{$this->getId()}-grid",

                'style' => [
                    'flex-grow' => 1,
                    'flex-basis' => '100px',
                ],
            ]),
            [
                'id' => $this->getId(),
                'role' => 'grid',
                'class' => ['ag-theme-alpine'],
                'style' => [
                    'display' => 'flex',
                    'flex-direction' => 'column',
                    'min-height' => '200px',
                ],
            ]
        );
    }
}
