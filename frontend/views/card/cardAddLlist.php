<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;

$css = '.head {
			background-color:  #ffffff;
			margin-top: 30px
		}';
$this->registerCss($css);

$HTML= <<< HTML
	<div class="col-md-10 head" >
		<div class="row">
			<div class="col-md-4">
				<label>卡号:</label>
				<label>00000000001</label>
			</div>
			<div class="col-md-4">
				<label>用户名:</label>
				<label>用户名</label>
			</div>
			<div class="col-md-4">
				<label>手机号码:</label>
				<label>18657127171</label>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<label>开卡时间:</label>
				<label>2018-01-01</label>
			</div>
			<div class="col-md-4">
				<label>当前余额:</label>
				<label>1000.01</label>
			</div>
			<div class="col-md-4">
				<a type="submit" class="btn btn-default" href="./card_pay.html">充值</a>
				<button class="btn btn-default" data-toggle="modal" data-target="#myModal">修改</button>
			</div>
		</div>
	</div>
HTML;

echo $HTML;

echo GridView::widget([
		'dataProvider'=>$dataProvider,
		'columns'=>[
				['label'=>'系统编号','value'=>'id'],
				['label'=>'充值时间','value'=>function ($value){
					return date('Y-m-d H:i:s',$value->create_time);
				}],
				['label'=>'充值金额','value'=>function($value){
					return sprintf("%.2f",($value->pay_money/100));
				}],
				['label'=>'充值后余额','value'=>function($value){
					return sprintf("%.2f",($value->after_mone/100));
				}],
				]
				]);
?>

