<?php

namespace app\models;


use yii\base\Model;

class Menu extends Model
{
    /**
     * List left side menu categories with WS links.
     * @return array
     */
    public static function categories()
    {
        $categories = (new \yii\db\Query())
            ->select(['id', 'name', 'layout', 'ws_chart_url', 'ws_map_url'])
            ->from('prime2_category')
            ->where(['>', 'id', 1])
            ->all();

        return $categories;
    }

    /**
     * List left side menu categories with WS links.
     * @return array
     */
    public static function cats()
    {
        $cats = [
            [
                "id" => "2",
                "name" => "Overview",
                "layout" => "layout14",
                "ws_chart_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/category?id=2",
                "ws_map_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/map-points?id=11&code=HF2&pid=374",
            ],
            [
                "id" => "6",
                "name" => "Infrastructure",
                "layout" => "layout13",
                "ws_chart_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/category?id=6",
                "ws_map_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/map-points?id=11&code=HF2&pid=374",
                "subcategories" => [
                    [
                        "id" => "6",
                        "name" => "Descriptive",
                        "layout" => "layout13",
                        "ws_chart_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/category?id=6",
                        "ws_map_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/map-points?id=11&code=HF2&pid=374",
                    ],
                    [
                        "id" => "17",
                        "name" => "Basic amenities",
                        "layout" => "layout13",
                        "ws_chart_url" => "",
                        "ws_map_url" => "",
                    ],
                ],
            ],
            [
                "id" => "3",
                "name" => "Damage",
                "layout" => "layout13",
                "ws_chart_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/category?id=3",
                "ws_map_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/map-points?id=12&code=HFINF1&pid=374",
            ],
            [
                "id" => "7",
                "name" => "Management",
                "layout" => "layout13",
                "ws_chart_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/category?id=7",
                "ws_map_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/map-points?id=17&code=HF4&pid=374",
                "subcategories" => [
                    [
                        "id" => "7",
                        "name" => "Ownership",
                        "layout" => "layout13",
                        "ws_chart_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/category?id=7",
                        "ws_map_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/map-points?id=17&code=HF4&pid=374",
                    ],
                    [
                        "id" => "8",
                        "name" => "External support",
                        "layout" => "layout13",
                        "ws_chart_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/category?id=8",
                        "ws_map_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/map-points?id=18&code=HFSUP1&pid=374",
                    ],
                ],
            ],
            [
                "id" => "4",
                "name" => "Status",
                "layout" => "layout13",
                "ws_chart_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/category?id=4",
                "ws_map_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/map-points?id=2&code=HFINF3&pid=374",
                "subcategories" => [
                    [
                        "id" => "4",
                        "name" => "Functionality",
                        "layout" => "layout13",
                        "ws_chart_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/category?id=4",
                        "ws_map_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/map-points?id=2&code=HFINF3&pid=374",
                    ],
                    [
                        "id" => "9",
                        "name" => "Accessibility",
                        "layout" => "layout13",
                        "ws_chart_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/category?id=9",
                        "ws_map_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/map-points?id=19&code=HFACC1&pid=374",
                    ],
                ],
            ],
            [
                "id" => "5",
                "name" => "Service Availability",
                "layout" => "layout13",
                "ws_chart_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/services?group=4,5,6,7,8,9,10,11,12&pid=374",
                "ws_map_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/map-points?id=14&code=NA&pid=374&services=4",
                "subcategories" => [
                    [
                        "id" => "7",
                        "name" => "Service Availability Overview",
                        "layout" => "layout13",
                        "ws_chart_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/services?group=4,5,6,7,8,9,10,11,12&pid=374",
                        "ws_map_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/map-points?id=14&code=NA&pid=374&services=4",
                    ],
                    [
                        "id" => "7",
                        "name" => "General Clinical Services & Trauma Care",
                        "layout" => "layout13",
                        "ws_chart_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/services?group=4&pid=374",
                        "ws_map_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/map-points?id=14&code=NA&pid=374&services=4",
                    ],
                    [
                        "id" => "8",
                        "name" => "Child Health",
                        "layout" => "layout13",
                        "ws_chart_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/services?group=5&pid=374",
                        "ws_map_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/map-points?id=14&code=NA&pid=374&services=5",
                    ],
                    [
                        "id" => "9",
                        "name" => "Communicable Diseases",
                        "layout" => "layout13",
                        "ws_chart_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/services?group=6&pid=374",
                        "ws_map_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/map-points?id=14&code=NA&pid=374&services=6",
                    ],
                    [
                        "id" => "10",
                        "name" => "Sexual & reproductive health",
                        "layout" => "layout13",
                        "ws_chart_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/services?group=7,8,11&pid=374",
                        "ws_map_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/map-points?id=14&code=NA&pid=374&services=7,8,11",
                        "subcategories" => [
                            [
                                "id" => "11",
                                "name" => "Sexual & reproductive health",
                                "layout" => "layout13",
                                "ws_chart_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/services?group=7,8,11&pid=374",
                                "ws_map_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/map-points?id=14&code=NA&pid=374&services=7,8,11",
                            ],
                            [
                                "id" => "11",
                                "name" => "STI & HIV/AIDS",
                                "layout" => "layout13",
                                "ws_chart_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/services?group=11&pid=374",
                                "ws_map_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/map-points?id=14&code=NA&pid=374&services=11",
                            ],
                            [
                                "id" => "12",
                                "name" => "Maternal and Newborn Health",
                                "layout" => "layout13",
                                "ws_chart_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/services?group=7&pid=374",
                                "ws_map_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/map-points?id=14&code=NA&pid=374&services=7",
                            ],
                            [
                                "id" => "13",
                                "name" => "Sexual Violence",
                                "layout" => "layout13",
                                "ws_chart_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/services?group=8&pid=374",
                                "ws_map_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/map-points?id=14&code=NA&pid=374&services=8",
                            ],
                        ],
                    ],
                    [
                        "id" => "14",
                        "name" => "Non communicable Diseases & mental health",
                        "layout" => "layout13",
                        "ws_chart_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/services?group=9&pid=374",
                        "ws_map_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/map-points?id=14&code=NA&pid=374&services=9",
                    ],
                    [
                        "id" => "15",
                        "name" => "Environmental Health",
                        "layout" => "layout13",
                        "ws_chart_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/services?group=10&pid=374",
                        "ws_map_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/map-points?id=14&code=NA&pid=374&services=10",
                    ],
                    [
                        "id" => "16",
                        "name" => "Health information",
                        "layout" => "layout13",
                        "ws_chart_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/services?group=12&pid=374",
                        "ws_map_url" => "https://herams-dev.westeurope.cloudapp.azure.com/aping/map-points?id=14&code=NA&pid=374&services=12",
                    ],
                ],
            ],
            [
                "id" => "18",
                "name" => "Other resources",
                "layout" => "layout13",
                "ws_chart_url" => "",
                "ws_map_url" => "",
                "subcategories" => [
                    [
                        "id" => "19",
                        "name" => "Human resources",
                        "layout" => "layout13",
                        "ws_chart_url" => "",
                        "ws_map_url" => "",
                    ],
                    [
                        "id" => "20",
                        "name" => "Essential medicines",
                        "layout" => "layout13",
                        "ws_chart_url" => "",
                        "ws_map_url" => "",
                    ],
                    [
                        "id" => "21",
                        "name" => "Basic equipment",
                        "layout" => "layout13",
                        "ws_chart_url" => "",
                        "ws_map_url" => "",
                    ],
                ],
            ],
        ];

        return $cats;
    }
}
