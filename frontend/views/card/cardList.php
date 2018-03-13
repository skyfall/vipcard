<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\base\Model;
use yii\bootstrap\Modal;
use PharIo\Manifest\Url;
use yii\web\UrlManager;
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

// 搜索表单
$form = ActiveForm::begin(['id' => 'add-form','options'=>['class'=>'modal-body','style'=>'margin-left: 15px'],'method'=>"get",'action'=>\yii\helpers\Url::toRoute(['card/card-list'])]);
echo $form->field($sreachmodel, 'card_no')->textInput(['autofocus' => true]);
echo Html::submitButton('搜索', ['class' => 'btn btn-primary', 'name' => 'login-button']);
ActiveForm::end();

echo GridView::widget([
		'dataProvider'=>$dataProvider,
		'columns'=>[
				['label'=>'系统编号','value'=>'id'],
				['label'=>'卡号','value'=>'card_no'],
				['label'=>'用户名','value'=>'card_user_name'],
				['label'=>'手机号','value'=>'card_user_tel'],
				['label'=>'余额','value'=>function($value){
					return sprintf("%.2f",($value->card_money/100));
				}],
				[
						'class' => 'yii\grid\ActionColumn',
						'header' => '操作',
						'template' => '{pay} {addBtton} {info}',//只需要展示删除和更新
						'buttons' => [
								'pay' => function($url, $model, $key){
									return Html::button('消费',  [
											'id' => '',
											'data-toggle' => 'modal',
											'data-target' => '#myModalpay',
											'card_no'=>$model->card_no,
											'card_id'=>$model->id,
											'class' => 'btn btn-success pay-btn',
									]);
								},
								'addBtton' => function($url, $model, $key){
									return Html::button('充值',  [
											'id' => '',
											'data-toggle' => 'modal',
											'data-target' => '#myModal',
											'card_no'=>$model->card_no,
											'card_id'=>$model->id,
											'class' => 'btn btn-success create-btn',
									]);
								},
								'info' => function($url, $model, $key){
								return Html::a('详情', \yii\helpers\Url::toRoute(['card/card-inf','card_id'=>$model->id]) ,[
											'id' => '',
											'card_no'=>$model->card_no,
											'card_id'=>$model->id,
											'class' => 'btn btn-success',
									]);
								},
						],
				],
		]
]);


//  充值表单

Modal::begin([
		'id' => 'myModal',
		'header' => '</button><h4 class="modal-title" id="myModalLabel">充值</h4>',
// 		'footer' => '<button  type="submit" class="btn btn-default" data-dismiss="modal">关闭</button><button type="button" class="btn btn-primary" id="addFromBtn" >充值</button>',
]);
$form = ActiveForm::begin(['id' => 'add-form','options'=>['class'=>'modal-body','style'=>'margin-left: 15px'],'enableAjaxValidation' => true,'validationUrl'=>\yii\helpers\Url::toRoute(['card/validation-card-add']), 'action' => \yii\helpers\Url::toRoute(['card/card-add'])]);
$js = <<<JS
    $(document).on('click', '.create-btn', function () {
            console.log(this.attributes.card_no.value)
        	console.log(this.attributes.card_id.value)
			$("#cardmoneyadd-card_id").val(this.attributes.card_id.value)
			$("#cardmoneyadd-card_no").val(this.attributes.card_no.value)
    });
JS;
$this->registerJs($js);
// // 劫持请求
$js = <<< JS
$(function(){
	$(document).on('beforeSubmit', '#add-form', function () {
		var form = $(this);
		//返回错误的表单信息
		if (form.find('.has-error').length)
		{
			return false;
		}
		//表单提交
		$.ajax({
			url  : form.attr('action'),
			type  : 'post',
			data  : form.serialize(),
			success: function (response){
				if(response.success){
					alert('保存成功');
					window.location.reload();
				}
			},
			error : function (){
				alert('系统错误');
				return false;
			}
		});
			return false;
	});
}); 
JS;
// $this->registerJs($js);

echo $form->field($addmodel, 'card_id')->textInput(['readonly'=>"true"]);
echo $form->field($addmodel, 'card_no')->textInput(['readonly'=>"true"]);            	
echo $form->field($addmodel, 'money')->textInput(['autofocus' => true]);
echo Html::submitButton('充值', ['class' => 'btn btn-primary', 'name' => 'login-button']) ;
ActiveForm::end();
Modal::end();



// 消费表单

Modal::begin([
		'id' => 'myModalpay',
		'header' => '</button><h4 class="modal-title" id="myModalLabel">消费</h4>',
// 		'footer' => '<button type="submit" class="btn btn-default" data-dismiss="modal">关闭</button><button type="button" class="btn btn-primary"  id="payFromBtn">消费</button>',
]);
$formPay = ActiveForm::begin(['id' => 'pay-form','options'=>['class'=>'modal-body','style'=>'margin-left: 15px'],'enableAjaxValidation' => true, 'validationUrl' => \yii\helpers\Url::toRoute(['card/validation-pay-add']),'action' => \yii\helpers\Url::toRoute(['card/pay-add'])]);
$js = <<<JS
    $(document).on('click', '.pay-btn', function () {
			console.log(this.attributes.card_id.value)
			$("#cardpay-card_id").val(this.attributes.card_id.value)
			$("#cardpay-card_no").val(this.attributes.card_no.value)
    });
JS;


$this->registerJs($js);

// // 劫持请求
$jsFrom = <<< JS
$(function(){
	$(document).on('beforeSubmit', '#pay-form', function () {
		var form = $(this);
		//返回错误的表单信息
		if (form.find('.has-error').length)
		{
			return false;
		}
		//表单提交
		$.ajax({
			url  : form.attr('action'),
			type  : 'post',
			data  : form.serialize(),
			success: function (response){
				if(response.success){
					alert('保存成功');
					window.location.reload();
				}
			},
			error : function (){
				alert('系统错误');
				return false;
			}
		});
			return false;
	});
});
JS;
// $this->registerJs($jsFrom);

echo $formPay->field($paymodel, 'card_id')->textInput(['readonly'=>"true"]);
echo $formPay->field($paymodel, 'card_no')->textInput(['readonly'=>"true"]);
echo $formPay->field($paymodel, 'money')->textInput(['autofocus' => true]);
echo Html::submitButton('消费', ['class' => 'btn btn-primary', 'name' => 'login-button']) ;
ActiveForm::end();
Modal::end();


?>
