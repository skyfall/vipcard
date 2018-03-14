<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;

$css = '.head {
			background-color:  #ffffff;
			margin-top: 30px
		}';
$this->registerCss($css);


$HTML= <<< HTML
	<div class="col-md-12 head" >
		<div class="row">
			<div class="col-md-4">
				<label>卡号:</label>
				<label>%d</label>
			</div>
			<div class="col-md-4">
				<label>用户名:</label>
				<label>%s</label>
			</div>
			<div class="col-md-4">
				<label>手机号码:</label>
				<label>%s</label>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<label>开卡时间:</label>
				<label>%s</label>
			</div>
			<div class="col-md-4">
				<label>当前余额:</label>
				<label>%s</label>
			</div>
			<div class="col-md-4">
				
				<button class="btn btn-default" data-toggle="modal" data-target="#myModal">修改</button>
				<button class="btn btn-default" data-toggle="modal" data-target="#addMoneyModle">充值</button>
				<button class="btn btn-default" data-toggle="modal" data-target="#myModalpay">消费</button>
			</div>
		</div>
	</div>
HTML;
$card_money = sprintf("%.2f",($CardInf->card_money/100));
echo sprintf($HTML,$CardInf->card_no,$CardInf->card_user_name,$CardInf->card_user_tel,date('Y-m-d H:i:s',$CardInf->create_time),$card_money
		);

echo GridView::widget([
		'dataProvider'=>$dataProvider,
		'columns'=>[
				['label'=>'系统编号','value'=>'id'],
				['label'=>'时间','value'=>function ($value){
					return date('Y-m-d H:i:s',$value->create_time);
				}],
				['label'=>'交易金额','value'=>function($value){
					return sprintf("%.2f",($value->money/100));
				}],
				['label'=>'交易后余额','value'=>function($value){
					return sprintf("%.2f",($value->after_mone/100));
				}],
				['label'=>'类型','value'=>function($value){
					return $value->type == 1 ? '充值' : '消费';
				}],
		]
	]);

Modal::begin([
		'id' => 'myModal',
		'header' => '</button><h4 class="modal-title" id="myModalLabel">修改</h4>',
		// 		'footer' => '<button  type="submit" class="btn btn-default" data-dismiss="modal">关闭</button><button type="button" class="btn btn-primary" id="addFromBtn" >充值</button>',
]);

$form = ActiveForm::begin(['id' => 'add-form','options'=>['class'=>'modal-body','style'=>'margin-left: 15px'],'enableAjaxValidation' => true,'validationUrl'=>\yii\helpers\Url::toRoute(['card/validation-fix-user']), 'action' => \yii\helpers\Url::toRoute(['card/fix-user'])]);

echo $form->field($fixmodel, 'card_id')->textInput(['readonly'=>"true"]);
echo $form->field($fixmodel, 'card_no')->textInput(['readonly'=>"true"]);
echo $form->field($fixmodel, 'user_name')->textInput();
echo $form->field($fixmodel, 'user_tel')->textInput();

echo Html::submitButton('修改', ['class' => 'btn btn-primary', 'name' => 'login-button']) ;
ActiveForm::end();
Modal::end();


//  充值表单
$Modal1 = Modal::begin([
		'id' => 'addMoneyModle',
		'header' => '</button><h4 class="modal-title" id="myModalMoney">充值</h4>',
		// 		'footer' => '<button  type="submit" class="btn btn-default" data-dismiss="modal">关闭</button><button type="button" class="btn btn-primary" id="addFromBtn" >充值</button>',
]);
$form = ActiveForm::begin(['id' => 'add-money-form','options'=>['class'=>'modal-body','style'=>'margin-left: 15px'],'enableAjaxValidation' => true,'validationUrl'=>\yii\helpers\Url::toRoute(['card/validation-card-add']), 'action'=>\yii\helpers\Url::toRoute(['card/card-add'])]);

$js = <<<JS
    $(document).on('click', '.create-btn', function () {
            console.log(this.attributes.card_no.value)
        	console.log(this.attributes.card_id.value)
			$("#cardmoneyadd-card_id").val(this.attributes.card_id.value)
			$("#cardmoneyadd-card_no").val(this.attributes.card_no.value)
    });
JS;
$this->registerJs($js);

echo $form->field($addmodel, 'card_id')->textInput(['readonly'=>"true",'value'=>$CardInf->id]);
echo $form->field($addmodel, 'card_no')->textInput(['readonly'=>"true",'value'=>$CardInf->card_no]);
echo $form->field($addmodel, 'money')->textInput(['autofocus' => true]);
echo Html::submitButton('充值', ['class' => 'btn btn-primary', 'name' => 'login-button']) ;
$form::end();
$Modal1::end();

// 消费表单
$Modal2 = Modal::begin([
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

echo $formPay->field($paymodel, 'card_id')->textInput(['readonly'=>"true",'value'=>$CardInf->id]);
echo $formPay->field($paymodel, 'card_no')->textInput(['readonly'=>"true",'value'=>$CardInf->card_no]);
echo $formPay->field($paymodel, 'money')->textInput(['autofocus' => true]);
echo Html::submitButton('消费', ['class' => 'btn btn-primary', 'name' => 'login-button']) ;
$formPay::end();
$Modal2::end();



?>

	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">充值</h4>
				</div>
				<div class="modal-body" style="margin-left: 15px">
					<div class="row">
						<label>用户信息</label>
						<div class="col-md-12" >
							<div class="col-md-4">
								<label>卡号</label>
								<input type="email" class="form-control" id="exampleInputEmail1" placeholder="卡号" value="<?php echo $CardInf->card_no?>">
							</div>
							<div class="col-md-4">
								<label>用户名</label>
								<input type="email" class="form-control" id="exampleInputEmail1" placeholder="用户名" value="<?php echo $CardInf->card_user_name?>">
							</div>
							<div class="col-md-4">
								<label>手机号</label>
								<input type="email" class="form-control" id="exampleInputEmail1" placeholder="手机号" value="<?php echo $CardInf->card_user_tel ?>">
							</div>
						</div>
					</div>
	
				</div>
				<div class="modal-footer" style="margin-top: 20px">
					<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
					<button type="button" class="btn btn-primary">提交更改</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal -->
	</div>

