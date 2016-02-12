<?php
    use yii\helpers\Html;

    echo Html::img('@web/img/MapMonde_RVB.svg', [
        'class' => ['col-md-12']
    ]);
?>
<div class="col-sm-12">

<h1>About PRIME</h1>

<h2>
    PRIME supports timely and evidence-based decision making in humanitarian emergencies by providing a platform for the standardised production and exchange of critical information among health sector actors involved in the response.
</h2>
</div>
<div class="col-sm-12 col-md-6">
    PRIME drives the production of information through a series of tools that significantly facilitate and reinforce all aspects of information management, including data collection, management, analysis and reporting. The information produced through PRIME is searchable and accessible in the information Marketplace.
    <?php
    if (\Yii::$app->user->isGuest) {
        echo Html::a(\Yii::t('app', 'Login or sign up'), ['user/login'], [
            'class' => 'btn btn-default btn-primary',
            'style' => 'display: block; margin-top: 10px;'
        ]);
    }
    ?>
</div>
<style>
    body {
        font-size: 2em;
    }
    ul#prime-usp {
        position: relative;
        list-style-type: none;
        padding-left: 0;
    }
    /*img {*/
        /*position: absolute;*/
        /*left: 0px;*/
        /*height: 1em;*/
    /*}*/

    #prime-usp li {
        text-align: justify;
        margin-bottom: 10px;
    }
    #prime-usp li img {
        width: 10%;
        padding-right: 10px;
    }
    #prime-usp li div {
        width: 90%;
    }
    #prime-usp li img, li div {
        display: inline-block;
        vertical-align: middle;;
    }
</style>
<div class="col-sm-12 col-md-6">
    PRIME AIMS MORE PARTICULARLY AT IMPROVING
    <ul id="prime-usp">
    <li><?=Html::img('@web/img/picto_Reliability.svg'); ?><div>The reliability of the information through improved transparency and wider participation</div></li>
    <li><?=Html::img('@web/img/picto_Consistency.svg'); ?><div>The consistency of the information by ensuring up to date and reliable information is made available at any point in time during an emergency</div></li>
    <li><?=Html::img('@web/img/picto_Efficiency.svg'); ?><div>The timeliness and efficiency of information management by offering efficient and easily-accessible data collection, management, analysis and reporting services</div></li>
    <li><?=Html::img('@web/img/picto_Efficiency.svg'); ?><div>The access to information through PRIME’s Marketplace thanks to easily searchable and centralized information</div></li>
    </ul>
</div>

<?php