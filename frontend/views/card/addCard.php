<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$css = '		.from {
			border: 1px solid #d8dee2;
			margin-top:100px;
			background-color:  #ffffff
		}
		.head {
			background-color:  #ffffff;
			margin-top: 30px
		}';
$this->registerCss($css);

?>

	    <div class="col-md-10 head">
        	<div style="margin-top: 14px;margin-bottom: 14px">
            	<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                	<?= $form->field($model, 'cardNo')->textInput(['autofocus' => true]) ?>

                	<?= $form->field($model, 'cardUserName')->textInput()?>
                	
                	<?= $form->field($model, 'cardTel')->textInput()?>
                	
                	<?= $form->field($model, 'cardMoney')->textInput()?>


                	<div class="form-group">
                    	<?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                	</div>

           	 	<?php ActiveForm::end(); ?>
        	</div>
    	</div>