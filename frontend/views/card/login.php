<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<!DOCTYPE html>
<?php $this->beginPage() ?>
<html>
	<head>
	  <?php $this->head() ?>
		<!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
		<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<title>登入页面</title>
		<style type="text/css">
			.from {
				border: 1px solid #d8dee2;
				margin-top:100px;
				background-color:  #ffffff
			}
		</style>
	</head>
	<?php $this->beginBody() ?>
	<body style="background-color:  #f9f9f9">
	
	    <div class="col-md-offset-5 from col-md-2">
        	<div style="margin-top: 14px;margin-bottom: 14px">
            	<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                	<?= $form->field($model, 'username')->textInput($model->getErrors('password') ? [] : ['autofocus' => true]) ?>

                	<?= $form->field($model, 'password')->passwordInput($model->getErrors('password') ? ['autofocus' => true]:[] ) ?>


                	<div class="form-group">
                    	<?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                	</div>

           	 	<?php ActiveForm::end(); ?>
        	</div>
    	</div>
	</body>
<?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>