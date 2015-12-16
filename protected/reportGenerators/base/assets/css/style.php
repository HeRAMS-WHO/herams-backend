<?php

/*
 * @var \prime\interfaces\ProjectInterface $project
 */

?>
@font-face {
    font-family: "Open Sans";
    src:url(data:font/opentype;base64,<?=base64_encode(file_get_contents(\yii\helpers\Url::to('@app/assets/fonts/OpenSans-Regular.ttf')))?>) format("truetype");
    font-style: normal;
    font-weight: 400;
}

@font-face {
    font-family: "Open Sans";
    src:url(data:font/opentype;base64,<?=base64_encode(file_get_contents(\yii\helpers\Url::to('@app/assets/fonts/OpenSans-Semibold.ttf')))?>) format("truetype");
    font-style: normal;
    font-weight: 600;
}

@font-face {
    font-family: "Open Sans";
    src:url(data:font/opentype;base64,<?=base64_encode(file_get_contents(\yii\helpers\Url::to('@app/assets/fonts/OpenSans-Bold.ttf')))?>) format("truetype");
    font-style: normal;
    font-weight: 900;
}

body {
    font-family: "Open Sans";
    color: #666;
}

.header {
    background-image: url(data:image/jpg;base64,<?=base64_encode(file_get_contents($project->getToolImagePath()))?>);
    background-position: right;
    background-repeat: no-repeat;
    background-size: contain;
    height: 70px;
}

.text-large {
    font-size: 2.5em;
}

.text-medium {
    font-size: 1.5em;
}

h1 {
    font-size: 3em;
    font-weight: 400;
    margin-top: 5px;
    margin-bottom: 5px;
}

h2 {
    font-size: 2em;
    font-weight: 400;
}

h4 {
    margin-top: 0px;
    margin-bottom: 5px;
    font-weight: 400;
    font-size: 1.1em;
}

table {
    font-size: 1em;
}

.table-striped > tbody > tr:nth-of-type(odd) {
    background-color: #e8e8e8;
}

td {
    padding: 5px;
}

hr {
    margin-top: 10px;
    margin-bottom: 10px;
    border-color: #8d8d8d;
    border-width: 2px;
}

@media print {
    .container-fluid {page-break-after: always;}

    body {
        font-size: 1.1em;
    }

    div.no-break {
        page-break-inside: avoid;
    }
}

textarea {
    width: 100%;
    height: 80px;
    border: 2px solid red;
}