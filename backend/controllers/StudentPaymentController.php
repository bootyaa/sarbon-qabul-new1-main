<?php

namespace backend\controllers;

use common\models\Student;
use common\models\StudentPayment;
use common\models\StudentPaymentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StudentPaymentController implements the CRUD actions for StudentPayment model.
 */
class StudentPaymentController extends Controller
{
    use ActionTrait;

    public function actionIndex()
    {
        $searchModel = new StudentPaymentSearch();
        $dataProvider = $searchModel->searchIndex($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new StudentPayment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($id)
    {
        $student = $this->findStudentModel($id);
        $model = new StudentPayment();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $result = StudentPayment::createItem($student, $model);
                if ($result['is_ok']) {
                    \Yii::$app->session->setFlash('success');
                } else {
                    \Yii::$app->session->setFlash('error', $result['errors']);
                }
            }
            return $this->redirect(['student/view', 'id' => $model->student_id]);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing StudentPayment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $student = $this->findStudentModel($model->student_id);

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $result = StudentPayment::createItem($student, $model);
                if ($result['is_ok']) {
                    \Yii::$app->session->setFlash('success');
                } else {
                    \Yii::$app->session->setFlash('error', $result['errors']);
                }
            }
            return $this->redirect(['student/view', 'id' => $model->student_id]);
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing StudentPayment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->is_deleted = 1;
        $model->save(false);
        \Yii::$app->session->setFlash('success');
        return $this->redirect(['student/view', 'id' => $model->student_id]);
    }

    /**
     * Finds the StudentPayment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return StudentPayment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StudentPayment::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }

    protected function findStudentModel($id)
    {
        if (($model = Student::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
