<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="col-md-2" style="margin-top: 60px">
    <?php
    NavBar::begin([
    		'brandLabel' => Yii::$app->name,
    		'brandUrl' => Yii::$app->homeUrl,
    		'options' => [
    				'class' => 'navbar-inverse navbar-fixed-top',
    		],
    ]);
    $menuItems = [
    		['label' => 'Home', 'url' => ['/card/card-list']],
    ];
    if (Yii::$app->user->isGuest) {
    	$menuItems[] = ['label' => 'login', 'url' => ['/card/login']];
    } else {
    	$menuItems[] = '<li>'
    		. Html::beginForm(['/card/logout'], 'post')
    		. Html::submitButton(
    				'Logout (' . Yii::$app->user->identity->user_name . ')',
    				['class' => 'btn btn-link logout']
    				)
    				. Html::endForm()
    				. '</li>';
    }
    echo Nav::widget([
    		'options' => ['class' => 'navbar-nav navbar-right'],
    		'items' => $menuItems,
    ]);
    NavBar::end();
    
    
    NavBar::begin(['options' => [
    		'class' => 'none'],'innerContainerOptions' => ['class'=>""]]);
    $menuItems = [
//         ['label' => 'Home', 'url' => ['/#']],
        ['label' => '卡列表', 'url' => ['/card/card-list']],
        ['label' => '添加卡', 'url' => ['/card/add-card']],
    ];
    echo Nav::widget([
    	'options' => ['class' => 'nav nav-pills nav-stacked'],
        'items' => $menuItems,
    	
    ]);
    NavBar::end();
    ?>
</div>

<div class="col-md-10" style="margin-top: 60px">
   <?= $content ?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
