<?php

/*
 * @var \prime\interfaces\ProjectInterface $project
 */

?>
@font-face {
    font-family: "Open Sans";
    src:url(data:font/opentype;base64,<?=base64_encode(file_get_contents(Yii::getAlias('@app/assets/fonts/OpenSans-Regular.ttf')))?>) format("truetype");
    font-style: normal;
    font-weight: 400;
}

@font-face {
    font-family: "Open Sans";
    src:url(data:font/opentype;base64,<?=base64_encode(file_get_contents(Yii::getAlias('@app/assets/fonts/OpenSans-Semibold.ttf')))?>) format("truetype");
    font-style: normal;
    font-weight: 600;
}

@font-face {
    font-family: "Open Sans";
    src:url(data:font/opentype;base64,<?=base64_encode(file_get_contents(Yii::getAlias('@app/assets/fonts/OpenSans-Bold.ttf')))?>) format("truetype");
    font-style: normal;
    font-weight: 900;
}

body {
    font-family: "Open Sans";
    color: #666;
    background-color: transparent;
}

<?php
    $path = $project->getToolImagePath();

    /** @var \prime\interfaces\ProjectInterface $project */
    if (preg_match('_^/site/text-image\?text=(.*)$_', $path, $matches)) {
        $content = \app\components\Html::textImage($matches[1]);
    } else {
        // Check if path is local.
        if (strncasecmp('http', $path, 4) === 0) {
            $image = imagecreatefromstring(file_get_contents($path));
        } else {
            $image = imagecreatefromstring(file_get_contents(Yii::getAlias('@webroot' . $path)));
        }

        $height = 140;
        $width = imagesx($image) / imagesy($image) * $height;
        $newImage = imagecreatetruecolor($width, $height);
        imagecopyresized($newImage, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));
        ob_start();
        imagepng($newImage);
        $content = ob_get_clean();

    }
?>

.header {
    background-image: url(data:image/png;base64,<?=base64_encode($content)?>);
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
        background-color: white;
    }

    div.no-break {
        page-break-inside: avoid;
    }
}

@media screen {
    .container-fluid {
        background-color: white;
        margin-top: 30px;
        width: 210mm;
        padding: 15mm;

        box-shadow: 0 10px 16px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19) !important;
    }

    .container-fluid:first-of-type {
        margin-top: 0px;
    }
}

textarea {
    width: 100%;
    height: 80px;
    border: 2px solid red;
}