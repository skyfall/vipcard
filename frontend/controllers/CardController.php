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
use frontend\models\dataModels\CardPayList;
use frontend\models\dataModels\CardAddList;
use frontend\models\dataModels\CardPayLog;
use frontend\models\fromModels\CardFixUser;

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
	
	// 卡详情(消费记录)
	public function actionCardInf(){
		$cardId = \Yii::$app->request->get('card_id',0);
		/**
		 * 
		 * @var Card $CardInf
		 */
		$CardInf = Card::find()->where(['id'=>$cardId])->andWhere(['user_id'=>\Yii::$app->user->getId()])->one();
		if (empty($CardInf)){
			return $this->redirect('/card/card-list');
		}
		
		$this->layout = 'card';
		$model = CardPayLog::find()->where(['user_id'=>\Yii::$app->user->getId()])->andWhere(['card_id'=>$cardId])->orderBy('id desc');;
		$dataProvider = new ActiveDataProvider([
				'query'=>$model,
				// 				'defaultPageSize'=>10,
				'totalCount'=>$model->count()
		]);
		
		
		$cardMoneyAdd = new CardMoneyAdd();
		$cardPayModle = new CardPay();
		
		$fixmodel =  new CardFixUser();
		$fixmodel->card_id = $CardInf->id;
		$fixmodel->card_no = $CardInf->card_no;
		$fixmodel->user_name = $CardInf->card_user_name;
		$fixmodel->user_tel = $CardInf->card_user_tel;
		return $this->render('cardInfo', [
				'CardInf'=>$CardInf,
				'dataProvider'=>$dataProvider,
				'fixmodel'=>$fixmodel,
				'addmodel'=>$cardMoneyAdd,
				'paymodel'=>$cardPayModle,
		]);
	}
	
	// 充值记录
	public function actionCardAddList(){
		$cardId = \Yii::$app->request->get('card_id',0);
		$CardInf = Card::find()->where(['id'=>$cardId])->andWhere(['user_id'=>\Yii::$app->user->getId()])->one();
		if (empty($CardInf)){
			return $this->redirect('/card/card-list');
		}
		
		$this->layout = 'card';
		$model = CardAddList::find()->where(['user_id'=>\Yii::$app->user->getId()])->andWhere(['card_id'=>$cardId])->orderBy('id desc');;
		$dataProvider = new ActiveDataProvider([
				'query'=>$model,
				// 				'defaultPageSize'=>10,
				'totalCount'=>$model->count()
		]);
		
		return $this->render('cardInfo', [
				'CardInf'=>$CardInf,
				'dataProvider'=>$dataProvider,
		]);
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
	
	// 添加充值记录
	public function actionCardAdd(){
		$model=new CardMoneyAdd();
		\Yii::$app->response->format=\Yii::$app->response::FORMAT_JSON;
		if ($model->load(Yii::$app->request->post()) && $model->addCardMoney()) {
			return $this->redirect(\Yii::$app->request->referrer);
		}
		return ActiveForm::validate($model);
	}
	
	// 异步验证数据
	public function actionValidationPayAdd(){
		$model= new CardPay();
		\Yii::$app->response->format=\Yii::$app->response::FORMAT_JSON;
		$model->load(Yii::$app->request->post());
		return ActiveForm::validate($model);
	}
	
	// 添加交易记录
	public function actionPayAdd(){
		$model= new CardPay();
		\Yii::$app->response->format=\Yii::$app->response::FORMAT_JSON;
		if ($model->load(Yii::$app->request->post()) && $model->addPayMoney()) {
			return $this->redirect(\Yii::$app->request->referrer);
		}
		return ActiveForm::validate($model);
	}
	
	
	// 修改用户信息 验证接口
	public function actionValidationFixUser(){
		$model= new CardFixUser();
		\Yii::$app->response->format=\Yii::$app->response::FORMAT_JSON;
		$model->load(Yii::$app->request->post());
		return ActiveForm::validate($model);
	}
	
	// 修改用户信息 验证接口
	public function actionFixUser(){
		$model= new CardFixUser();
		\Yii::$app->response->format=\Yii::$app->response::FORMAT_JSON;
		$model->load(Yii::$app->request->post());
		return ActiveForm::validate($model);
	}
	
}

