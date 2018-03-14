<?php
namespace frontend\controllers;

use frontend\common\FrontendController;
use frontend\models\dataModels\Card;
use League\Csv\Writer;
use frontend\models\dataModels\CardPayLog;

class CardExcelController extends FrontendController{
	
	// 下载全部会员卡列表
	public function actionDownCardList(){
		if (\Yii::$app->user->isGuest){
			return $this->goHome();
		}
		
		$cardModle = Card::find()->where(['user_id'=>\Yii::$app->user->getId()])->orderBy('id desc');
		
		//load the CSV document from a string
		$csv = Writer::createFromString('');
		$header = ['系统编号', '卡号', '用户名','手机号','余额'];
		
		//insert the header
		$csv->insertOne($header);

		
		$dataList = $cardModle->asArray()->select(['id','card_no','card_user_name','card_user_tel','card_money'])->all();
		
		foreach ($dataList as $k=>$v){
			$dataList[$k]['card_money'] = sprintf("%.2f",($v['card_money']/100));
		}
		//insert all the records
		$csv->insertAll($dataList);
		
		$downName = 'list-'.date('Ymd-H:i:s').'.csv';
		header('Transfer-Encoding: chunked');
		header('Content-Encoding: none');
		header('Content-Type: text/csv; charset=UTF-8');
		header('Content-Description: File Transfer');
		header('Content-Disposition: attachment; filename="'.$downName.'"');
		
		foreach ($csv->chunk(1024) as $chunk) {
// 			var_dump($chunk);exit();
			echo dechex(strlen($chunk))."\r\n$chunk\r\n";
		}
		echo "0\r\n\r\n";
		exit();
	}
	
	// 下载全部会员卡列表
	public function actionDownCardLog($card_id = '',$card_no = '0'){
		if (\Yii::$app->user->isGuest){
			return $this->goHome();
		}
		
		if (empty($card_id)){
			return $this->goHome();
		}
		
		$cardModle = CardPayLog::find()->where(['user_id'=>\Yii::$app->user->getId()])->andWhere(['card_id'=>$card_id])->orderBy('id desc');
		
		//load the CSV document from a string
		$csv = Writer::createFromString('');
		$header = ['系统编号', '时间', '交易金额','交易后余额','类型'];
		
		//insert the header
		$csv->insertOne($header);
		
		
		$dataList = $cardModle->asArray()->select(['id','create_time','money','after_mone','type'])->all();
		foreach ($dataList as $k=>$v){
			$dataList[$k]['money'] = sprintf("%.2f",($v['money']/100));
			$dataList[$k]['after_mone'] = sprintf("%.2f",($v['after_mone']/100));
			$dataList[$k]['type'] = $v['type'] == 1 ? '充值' : '消费'; 
		}
		//insert all the records
		$csv->insertAll($dataList);
		
		$downName = 'card-'.$card_no.'-'.date('Ymd-H:i:s').'.csv';
		header('Transfer-Encoding: chunked');
		header('Content-Encoding: none');
		header('Content-Type: text/csv; charset=UTF-8');
		header('Content-Description: File Transfer');
		header('Content-Disposition: attachment; filename="'.$downName.'"');
		
		foreach ($csv->chunk(1024) as $chunk) {
			echo dechex(strlen($chunk))."\r\n$chunk\r\n";
		}
		echo "0\r\n\r\n";
		exit();
	}
}