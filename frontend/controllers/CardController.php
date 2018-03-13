<?php

namespace frontend\controllers;

use Yii;
use frontend\models\fromModels\CardLogin;
use frontend\models\fromModels\CardAdd;
use yii\filters\AccessControl;
use frontend\models\dataModels\Card;
use yii\data\ArrayDataProvider;
use yii\data\Pagination;
use yii\data\ActiveDataProvider;
use frontend\models\fromModels\CardMoneyAdd;
use frontend\models\fromModels\CardPay;
use yii\bootstrap\ActiveForm;
use frontend\models\fromModels\SearchCard;

class CardController  extends \frontend\common\FrontendController{
	
	public $layout = false; //不使用布局
	
	public $enableCsrfValidation = false;
	
	public $defaultAction = 'card-list';
	
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
				'access' => [
						'class' => AccessControl::className(),
						'only' => ['login','add-card','card-list'],
						'rules' => [
								[
										'actions' => ['login'],
										'allow' => true,
										'roles' => ['?'],
								],
								[
										'actions' => ['add-card','card-list'],
										'allow' => true,
										'roles' => ['@'],
								],
						],
				],
		];
	}
	
	
	//登入
	public function actionLogin(){
		
		if (!Yii::$app->user->isGuest) {
			return $this->redirect('/card/card-list');
		}
		$model = new CardLogin();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			return $this->goBack();
		} else {
			$this->layout = false;
			$model->password = '';
			
			return $this->render('login', [
					'model' => $model,
			]);
		}
	}
	
	// 添加卡
	public function actionAddCard(){
		$model = new CardAdd();
		$this->layout = 'card';
		
		
		if ($model->load(Yii::$app->request->post())) {
			if ($card = $model->addCard()) {
				return $this->redirect('/card/card-list');
			}
		}
		return $this->render('addCard', [
				'model' => $model,
		]);
	}
	
	// 卡列表
	public function actionCardList(){
		$this->layout = 'card';
		$sreachmodel = new SearchCard();
		$cardModle = Card::find()->where(['user_id'=>\Yii::$app->user->getId()])->orderBy('id desc');
		if ($sreachmodel->load(Yii::$app->request->get())){
			$cardModle = $sreachmodel->sreach($cardModle);
		}
		

		$dataProvider = new ActiveDataProvider([
				'query'=>$cardModle,
// 				'defaultPageSize'=>10,
				'totalCount'=>$cardModle->count()
		]);
		
		
		$cardMoneyAdd = new CardMoneyAdd();
		$cardPayModle = new CardPay();
		
		return $this->render('cardList', [
				'dataProvider'=>$dataProvider,
				'model'=>$cardModle,
				'addmodel'=>$cardMoneyAdd,
				'paymodel'=>$cardPayModle,
				'sreachmodel'=>$sreachmodel
		]);
	}
	
	// 卡详情
	public function actionCardInf(){
		
	}
	
	/**
	 * Logs out the current user.
	 *
	 * @return mixed
	 */
	public function actionLogout()
	{
		Yii::$app->user->logout();
		
		return $this->goHome();
	}
	
	// 异步验证数据
	public function actionValidationCardAdd(){
		$model= new CardMoneyAdd();
		\Yii::$app->response->format=\Yii::$app->response::FORMAT_JSON;
		if ($model->load(Yii::$app->request->post())  && $model->validate()) {
			
		}
		return ActiveForm::validate($model);
	}
	
	// 添加
	public function actionCardAdd(){
		$model=new CardMoneyAdd();
		\Yii::$app->response->format=\Yii::$app->response::FORMAT_JSON;
		if ($model->load(Yii::$app->request->post()) && $model->addCardMoney()) {
			return $this->redirect('/card/card-list');
		}
		return ['state'=>3,'message'=>'参数错误','data'=>$model->errors];
	}
	
	// 异步验证数据
	public function actionValidationPayAdd(){
		$model= new CardPay();
		\Yii::$app->response->format=\Yii::$app->response::FORMAT_JSON;
		if ($model->load(Yii::$app->request->post())  && $model->validate()) {
			
		}
		return ActiveForm::validate($model);
	}
	
	// 添加
	public function actionPayAdd(){
		$model= new CardPay();
		\Yii::$app->response->format=\Yii::$app->response::FORMAT_JSON;
		if ($model->load(Yii::$app->request->post()) && $model->addPayMoney()) {
			return $this->redirect('/card/card-list');
		}
		return ['state'=>3,'message'=>'参数错误','data'=>$model->errors];
	}
	
	
}

