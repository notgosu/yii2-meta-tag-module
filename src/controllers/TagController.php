<?php

namespace notgosu\yii2\modules\metaTag\controllers;

use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

use notgosu\yii2\modules\metaTag\Module;
use notgosu\yii2\modules\metaTag\models\MetaTag;

/**
 * TagController implements the CRUD actions for MetaTag model.
 */
class TagController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all MetaTag models.
     * @return mixed
     */
    public function actionIndex()
    {
		if (!\Yii::$app->user->can('index', [], false))
		    throw new ForbiddenHttpException(Module::t('metaTag', 'Access denied.'));
		
        $dataProvider = new ActiveDataProvider([
            'query' => MetaTag::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MetaTag model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if (!\Yii::$app->user->can('view'))
            throw new ForbiddenHttpException(Module::t('metaTag', 'Access denied.'));
        
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MetaTag model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		if (!\Yii::$app->user->can('create'))
		    throw new ForbiddenHttpException(Module::t('metaTag', 'Access denied.'));
        
        $model = new MetaTag();

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MetaTag model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (\Yii::$app->user->can('update', ['model' => $model]))
            throw new ForbiddenHttpException(Module::t('metaTag', 'Access denied.'));
        
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MetaTag model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		if (!\Yii::$app->user->can('delete'))
			throw new ForbiddenHttpException(\Module::t('metaTag', 'Access denied.'));

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MetaTag model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MetaTag the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MetaTag::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Module::t('metaTag', 'The requested model does not exist.'));
        }
    }
}
