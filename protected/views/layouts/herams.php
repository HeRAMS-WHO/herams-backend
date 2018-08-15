<?php
    use \yii\bootstrap\Html;
    use yii\widgets\Breadcrumbs;
    /* @var $this \yii\web\View */
    /* @var $content string */
    $this->beginPage();
    $this->registerAssetBundle(\prime\assets\AppAsset::class);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
        <?= Html::csrfMetaTags() ?>
        <?=Html::tag('link', null, [
            'rel' => 'shortcut icon',
            'href' => \yii\helpers\Url::to('@web/img/favicon.png'),
            'type' => 'img/x-icon'
        ]); ?>
        <title><?=Html::encode($this->title ?: app()->name); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php $this->head(); ?>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/fontawesome.css" integrity="sha384-q3jl8XQu1OpdLgGFvNRnPdj5VIlCvgsDQTQB6owSOHWlAurxul7f+JpUOVdAiJ5P" crossorigin="anonymous">
<style>
html,
body {
  font-family: Roboto, "Open Sans", Helvetica, Arial, sans-serif;
  font-size: 14px;
  font-weight: 400;
  padding: 0;
  margin: 0;
}
html,
body,
body > div,
body > div > div {
  height: 100%;
}
body {
  background-color: #e0e0e0;
}
a,
a:hover,
a:focus {
  cursor: pointer;
}
*:focus {
  outline: none;
}


/**
    OVERALL WRAPPER
 */
.mainWrapper .main {
  background-color: #e0e0e0;
}
.mainWrapper .main .background-top {
  height: 173px;
  background-color: #42424b;
}
.mainWrapper .main .content {
  position: fixed;
  top: 24px;
  width: 100%;
  padding-left: 20px;
  padding-right: 15px;
}


/**
    HEADER
 */

.mainWrapper .main .content .header .row:nth-child(2) {
  height: 79px;
}
.mainWrapper .main .content .header .row:nth-child(2) div:first-child {
  padding: 0; margin: 0;
}
.mainWrapper .main .content .header .breadcrumbs {
  font-size: 1.2em;
  font-weight: 300;
  color: #878787;
}
.mainWrapper .main .content .header {
  height: 79px;
  padding:0;
  padding-right: 20px;
}
.mainWrapper .main .content .header .title {
  font-size: 3em;
  font-weight: 300;
  line-height: 34px;
  color: white;
}
.mainWrapper .main .content .header .title span {
  margin: 0;
  padding-bottom: 10px;
  border-bottom: 4px solid #5791e1;
}

.mainWrapper .main .content .header > .row > div:nth-child(2) {
  padding:0;
  margin: 0;
}



/**
    USER PROFILE
 */

.mainWrapper .main .content .header .user-profile
{
  padding-top:0;
  margin-top:0;
  line-height:1em;

  display:flex;
  justify-content: flex-end;
  align-items:center;

}

/* user profile txt infos */
.mainWrapper .main .content .header .user-profile > div:first-child {
  font-size: 1em;
  font-weight: medium;
  color: white;
  text-align: right;
}
.mainWrapper .main .content .header .user-profile > div:first-child .usrname {
  text-transform: uppercase;
  margin: 2px 0;
}
.mainWrapper .main .content .header .user-profile > div:first-child .email {
  font-size: 0.9em;
  color: #b3b1b8;
  margin: 2px 0;
}
.mainWrapper .main .content .header .user-profile > div:first-child .org {
  margin: 10px 0;
}
.mainWrapper .main .content .header .user-profile > div:first-child .org > span {
  text-transform: uppercase;
  color: #42424b;
  border-radius:3px;
  padding: 2px;
  background-color: #e9e9e9;
  width: 40px;
}

/* user profile icons/pictures container */
.mainWrapper .main .content .header .user-profile > div:nth-child(2) {
  display:flex;
  justify-content: space-around;
  align-items:center;

  max-width: 90px;
}

/* user profile picture placeholder */
.mainWrapper .main .content .header .user-profile > div:nth-child(2) > i:first-child {
  font-size: 4em;
  color: #59585e;
  padding: 0 10px;
}
/* arrow down */
.mainWrapper .main .content .header .user-profile > div:nth-child(2) > i:nth-child(2) {
  font-size: 1.2em;
  color: #e9e9e9;
  cursor: pointer;
}


/**
    MAIN CONTENT
 */
/* Main content container (white area) */
.mainWrapper .main .content .datagrid {
  background-color: white;
  height: 85vh;
  border-top-right-radius: 5px;
}
.mainWrapper .main .content .datagrid .row * {
  padding-right: 0;
  padding-left: 0;
  margin: 0;
}
.mainWrapper .main .content .datagrid .row.spacer {
  height: 15px;
}

/* Main content */
.mainWrapper .main .content .datagrid .row .main-content-area {
  padding: 30px;
}

/**
    BOOTSTRAP POPOVER
 */
.hidden-popover{
  display:none;
}
.popover.bottom>.arrow {
  display: none;
}
.popover-content {
  padding:0;
  margin:0;
}
.log-popover > ul {
  list-style:none;
  padding:0;
  margin:0
}
.log-popover > ul > li {
  padding:5px 10px 5px 20px;
  font-size: 1em;
  font-family: Roboto, 'sans-serif';
}
.log-popover > ul > li:hover {
  color: white;
  background-color:#5791e1;
}

/* BROWSERHAPPY */
.browsehappy {
  margin: 0.2em 0;
  background: #ccc;
  color: #000;
  padding: 0.2em 0;
}
</style>
</head>

<body>
        <?php $this->beginBody(); ?>

  <!--[if lt IE 7]>
      <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
  <![endif]-->

      <div class="mainWrapper">
            <div class="main">
                <div class="background-top"></div>
                <div class="content">
                    <div class="header container-fluid">
                        <div class="row">
                            <div class="col-sm-6 col-lg-8">
                                <div class="breadcrumbs"><a href="/">&lt; Back</a></div>
                                <div class="title"><span><?php if (isset($this->params['sectionTitle'])) echo $this->params['sectionTitle']; ?></span></div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <div class="user-profile">
                                    <div>
                                        <div class="usrname"><?php echo Html::encode(Yii::$app->user->identity->firstName.' '.Yii::$app->user->identity->lastName); ?></div>
                                        <div class="email"><?php echo Html::encode(Yii::$app->user->identity->email); ?></div>
                                    </div>
                                        <i class="fas fa-user-circle"></i>
                                        <i class="fas fa-angle-down" id="log"></i>
                                    <div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="datagrid container-fluid">
                        <div class="row spacer"><div class="col-12"></div></div>
                            <div class="col-12">
                                <div class="main-content-area">
        <?php
            //if(!isset($this->params['hideMenu']) || $this->params['hideMenu'] == false) {
            //    echo $this->render('//menu');
            //}
            echo $this->render('//flash.php');

            $defaultContainerOptions = ['class' => 'container'];

            echo Html::tag(
                'div',
                $content,
                isset($this->params['containerOptions'])
                    ? \yii\helpers\ArrayHelper::merge($defaultContainerOptions, $this->params['containerOptions'])
                    : $defaultContainerOptions
            );
?>

</div>
						</div>
					<div class="row spacer"><div class="col-12"></div></div>
				</div>
		</div>
	</div>
    <div id="popover-content" class="hidden-popover">
        <div class="log-popover">
        <ul>
            <li>Profile</li>
            <li>Logout</li>
        </ul>
        </div>
    </div>

    <?php $this->endBody(); ?>
    </body>
</html>
<?php $this->endPage() ?>
