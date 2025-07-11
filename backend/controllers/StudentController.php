<?php

namespace backend\controllers;

use backend\models\Passport;
use backend\models\SignUp;
use common\models\ConfirmFile;
use common\models\Contract;
use common\models\CrmPush;
use common\models\EduDirection;
use common\models\EduType;
use common\models\Exam;
use common\models\ExamStudentQuestions;
use common\models\ExamStudentQuestionsSearch;
use common\models\ExamSubject;
use common\models\StepFour;
use common\models\StepOne;
use common\models\StepOneThree;
use common\models\StepOneTwo;
use common\models\StepThreeFour;
use common\models\StepThreeOne;
use common\models\StepThreeThree;
use common\models\StepThreeTwo;
use common\models\StepTwo;
use common\models\Student;
use common\models\StudentDtm;
use common\models\StudentMaster;
use common\models\StudentOferta;
use common\models\StudentPaymentSearch;
use common\models\StudentPerevot;
use common\models\StudentSearch;
use kartik\mpdf\Pdf;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\UploadPdf;
use Yii;

/**
 * StudentController implements the CRUD actions for Student model.
 */
class StudentController extends Controller
{
    use ActionTrait;

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Student models.
     * @return string
     */
    public function actionIndex()
    {
        $eduType = $this->eduTypeFindModel(1);
        $searchModel = new StudentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $eduType);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'eduType' => $eduType
        ]);
    }

    public function actionPerevot()
    {
        $eduType = $this->eduTypeFindModel(2);
        $searchModel = new StudentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $eduType);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'eduType' => $eduType
        ]);
    }


    public function actionDtm()
    {
        $eduType = $this->eduTypeFindModel(3);
        $searchModel = new StudentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $eduType);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'eduType' => $eduType
        ]);
    }

    public function actionMaster()
    {
        $eduType = $this->eduTypeFindModel(4);
        $searchModel = new StudentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $eduType);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'eduType' => $eduType
        ]);
    }

    public function actionChala()
    {
        $searchModel = new StudentSearch();
        $dataProvider = $searchModel->chala($this->request->queryParams);

        return $this->render('chala', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionContract()
    {
        $searchModel = new StudentSearch();
        $dataProvider = $searchModel->contract($this->request->queryParams);

        return $this->render('contract', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }


    public function actionAll()
    {
        $searchModel = new StudentSearch();
        $dataProvider = $searchModel->all($this->request->queryParams);

        return $this->render('all', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }


    public function actionOffline()
    {
        $searchModel = new StudentSearch();
        $dataProvider = $searchModel->offline($this->request->queryParams);

        return $this->render('offline', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionArchive()
    {
        $searchModel = new StudentSearch();
        $dataProvider = $searchModel->archive($this->request->queryParams);

        return $this->render('archive', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Student model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModelView($id);
        $searchModel = new StudentPaymentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $id);

        return $this->render('view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionViewExam($id)
    {
        $student = $this->findModel($id);

        $searchModel = new ExamStudentQuestionsSearch();
        $searchModel->user_id = $student->user_id;

        $dataProvider = $searchModel->searchNew(Yii::$app->request->queryParams, $student);

        return $this->render('view-exam', [
            'model' => $student,    
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionViewExam1($id)
    {
        $student = $this->findModel($id);
        $exams = ExamStudentQuestions::find()->where(['user_id' => $student->user_id])->all();

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => \common\models\ExamStudentQuestions::find()
                ->where(['user_id' => $student->user_id])
                // ->andWhere(['is_deleted' => 0, 'status' => 1])
                ->with(['question', 'user'])
                ->orderBy(['created_at' => SORT_DESC]),
            'pagination' => ['pageSize' => 50],
        ]);

        // dd($exams);
        return $this->render('view-exam', [
            'model' => $student,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionExam($id)
    {
        $student = $this->findModel($id);
        $exams = ExamStudentQuestions::find()->where(['user_id' => $student->user_id])->all();

        return $this->render('exam', [
            'model' => $student,
            'exams' => $exams
        ]);
    }

    /**
     * Creates a new Student model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new SignUp();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $result = $model->signup();
                if ($result['is_ok']) {
                    \Yii::$app->session->setFlash('success');
                    return $this->redirect(['view', 'id' => $result['student']->id]);
                } else {
                    \Yii::$app->session->setFlash('error', $result['errors']);
                }
                return $this->redirect(\Yii::$app->request->referrer);
            }
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Student model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Student model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->is_deleted = 1;
        $model->update(false);

        return $this->redirect(['index']);
    }

    public function actionInfo($id)
    {
        $student = $this->findModel($id);
        $user = $student->user;
        $action = '';
        if (\Yii::$app->params['ikIntegration'] == 1) {
            $action = '_form-step1';
            $model = new StepOne();
        } elseif (\Yii::$app->params['ikIntegration'] == 2) {
            $action = '_form-step12';
            $model = new StepOneTwo();
        } elseif (\Yii::$app->params['ikIntegration'] == 3) {
            $action = '_form-step13';
            $model = new StepOneThree();
        } else {
            return false;
        }
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $result = $model->ikStep($user, $student);
                if ($result['is_ok']) {
                    \Yii::$app->session->setFlash('success');
                } else {
                    \Yii::$app->session->setFlash('error', $result['errors']);
                }
                return $this->redirect(['view', 'id' => $student->id]);
            }
        }

        return $this->renderAjax($action, [
            'model' => $model,
            'student' => $student,
        ]);
    }

    public function actionInfoFull($id)
    {
        $student = $this->findModel($id);
        $user = $student->user;
        $action = '_form-step13';
        $model = new StepOneThree();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $result = $model->ikStep($user, $student);
                if ($result['is_ok']) {
                    \Yii::$app->session->setFlash('success');
                } else {
                    \Yii::$app->session->setFlash('error', $result['errors']);
                }
                return $this->redirect(['view', 'id' => $student->id]);
            }
        }

        return $this->renderAjax($action, [
            'model' => $model,
            'student' => $student,
        ]);
    }

    public function actionEduType($id)
    {
        $student = $this->findModel($id);
        $user = $student->user;
        $model = new StepTwo();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $result = $model->ikStep($user, $student);
                if ($result['is_ok']) {
                    \Yii::$app->session->setFlash('success');
                } else {
                    \Yii::$app->session->setFlash('error', $result['errors']);
                }
                return $this->redirect(['view', 'id' => $student->id]);
            }
        }

        return $this->renderAjax('_form-step2', [
            'model' => $model,
            'student' => $student,
        ]);
    }

    public function actionUserUpdate($id)
    {
        $model = $this->findModel($id);
        $old = $model;
        if ($this->request->isPost) {
            $postData = $this->request->post();
            if (isset($postData[$model->formName()])) {
                $model->setAttributes([
                    'status' => $postData[$model->formName()]['status'] ?? $model->status,
                    'username' => $postData[$model->formName()]['username'] ?? $model->username,
                    'password' => $postData[$model->formName()]['password'] ?? $model->password,
                ], false);

                $result = Student::userUpdate($model, $old);
                if ($result['is_ok']) {
                    \Yii::$app->session->setFlash('success');
                } else {
                    \Yii::$app->session->setFlash('error', $result['errors']);
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->renderAjax('user-update', [
            'model' => $model,
        ]);
    }

    public function actionDirection($id)
    {
        $student = $this->findModel($id);
        $user = $student->user;

        $action = '';
        if ($student->edu_type_id == 1) {
            $model = new StepThreeOne();
            $action = '_form-step3';
        } elseif ($student->edu_type_id == 2) {
            $model = new StepThreeTwo();
            $action = '_form-step32';
        } elseif ($student->edu_type_id == 3) {
            $model = new StepThreeThree();
            $action = '_form-step33';
        } elseif ($student->edu_type_id == 4) {
            $model = new StepThreeFour();
            $action = '_form-step34';
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->edu_type_id = $student->edu_type_id;
                $result = $model->ikStep($user, $student);
                if ($result['is_ok']) {
                    \Yii::$app->session->setFlash('success');
                } else {
                    \Yii::$app->session->setFlash('error', $result['errors']);
                }
                return $this->redirect(['view', 'id' => $student->id]);
            }
        }

        return $this->renderAjax($action, [
            'model' => $model,
            'student' => $student,
        ]);
    }

    public function actionOfertaUpload($id)
    {
        $model = $this->ofertafindModel($id);

        if ($this->request->isPost) {
            $post = $this->request->post();
            if ($model->load($post)) {
                $result = StudentOferta::upload($model);
                if ($result['is_ok']) {
                    \Yii::$app->session->setFlash('success');
                } else {
                    \Yii::$app->session->setFlash('error', $result['errors']);
                }
                return $this->redirect(['view', 'id' => $model->student_id]);
            }
        }

        return $this->renderAjax('oferta-upload', [
            'model' => $model,
        ]);
    }

    public function actionOfertaConfirm($id)
    {
        $model = $this->ofertafindModel($id);

        $old = $model;
        if ($this->request->isPost) {
            $post = $this->request->post();
            if ($model->load($post)) {
                $result = StudentOferta::confirm($model, $old);
                if ($result['is_ok']) {
                    \Yii::$app->session->setFlash('success');
                } else {
                    \Yii::$app->session->setFlash('error', $result['errors']);
                }
                return $this->redirect(['view', 'id' => $model->student_id]);
            }
        }

        return $this->renderAjax('oferta-confirm', [
            'model' => $model,
        ]);
    }

    public function actionTrUpload($id)
    {
        $studentFile = $this->trFindModel($id);

        $model = new UploadPdf();

        if ($this->request->isPost) {
            $post = $this->request->post();
            if ($model->load($post)) {
                $result = UploadPdf::upload($model, $studentFile);
                if ($result['is_ok']) {
                    \Yii::$app->session->setFlash('success');
                } else {
                    \Yii::$app->session->setFlash('error', $result['errors']);
                }
                return $this->redirect(['view', 'id' => $studentFile->student_id]);
            }
        }

        return $this->renderAjax('oferta-upload', [
            'model' => $model,
            'studentFile' => $studentFile,
        ]);
    }

    public function actionTrConfirm($id)
    {
        $model = $this->trfindModel($id);

        $old = $model;
        if ($this->request->isPost) {
            $post = $this->request->post();
            if ($model->load($post)) {
                $result = ConfirmFile::confirm($model, $old);
                if ($result['is_ok']) {
                    \Yii::$app->session->setFlash('success');
                } else {
                    \Yii::$app->session->setFlash('error', $result['errors']);
                }
                return $this->redirect(['view', 'id' => $model->student_id]);
            }
        }

        return $this->renderAjax('oferta-confirm', [
            'model' => $model,
        ]);
    }

    public function actionDtmUpload($id)
    {
        $studentFile = $this->dtmFindModel($id);

        $model = new UploadPdf();

        if ($this->request->isPost) {
            $post = $this->request->post();
            if ($model->load($post)) {
                $result = UploadPdf::upload($model, $studentFile);
                if ($result['is_ok']) {
                    \Yii::$app->session->setFlash('success');
                } else {
                    \Yii::$app->session->setFlash('error', $result['errors']);
                }
                return $this->redirect(['view', 'id' => $studentFile->student_id]);
            }
        }

        return $this->renderAjax('oferta-upload', [
            'model' => $model,
            'studentFile' => $studentFile,
        ]);
    }

    public function actionDtmConfirm($id)
    {
        $model = $this->dtmfindModel($id);

        $old = $model;
        if ($this->request->isPost) {
            $post = $this->request->post();
            if ($model->load($post)) {
                $result = ConfirmFile::confirm($model, $old);
                if ($result['is_ok']) {
                    \Yii::$app->session->setFlash('success');
                } else {
                    \Yii::$app->session->setFlash('error', $result['errors']);
                }
                return $this->redirect(['view', 'id' => $model->student_id]);
            }
        }

        return $this->renderAjax('oferta-confirm', [
            'model' => $model,
        ]);
    }

    public function actionMasterUpload($id)
    {
        $studentFile = $this->masterFindModel($id);

        $model = new UploadPdf();

        if ($this->request->isPost) {
            $post = $this->request->post();
            if ($model->load($post)) {
                $result = UploadPdf::upload($model, $studentFile);
                if ($result['is_ok']) {
                    \Yii::$app->session->setFlash('success');
                } else {
                    \Yii::$app->session->setFlash('error', $result['errors']);
                }
                return $this->redirect(['view', 'id' => $studentFile->student_id]);
            }
        }

        return $this->renderAjax('oferta-upload', [
            'model' => $model,
            'studentFile' => $studentFile,
        ]);
    }

    public function actionMasterConfirm($id)
    {
        $model = $this->masterfindModel($id);

        $old = $model;
        if ($this->request->isPost) {
            $post = $this->request->post();
            if ($model->load($post)) {
                $result = ConfirmFile::confirm($model, $old);
                if ($result['is_ok']) {
                    \Yii::$app->session->setFlash('success');
                } else {
                    \Yii::$app->session->setFlash('error', $result['errors']);
                }
                return $this->redirect(['view', 'id' => $model->student_id]);
            }
        }

        return $this->renderAjax('oferta-confirm', [
            'model' => $model,
        ]);
    }

    public function actionSertificateUpload($id)
    {
        $studentFile = $this->sertificateFindModel($id);

        if ($studentFile) {
            $model = new UploadPdf();
            if ($this->request->isPost) {
                $post = $this->request->post();
                if ($model->load($post)) {
                    $result = UploadPdf::upload($model, $studentFile);
                    if ($result['is_ok']) {
                        \Yii::$app->session->setFlash('success');
                    } else {
                        \Yii::$app->session->setFlash('error', $result['errors']);
                    }
                    return $this->redirect(['view', 'id' => $studentFile->student_id]);
                }
            }

            return $this->renderAjax('oferta-upload', [
                'model' => $model,
                'studentFile' => $studentFile,
            ]);
        }
    }

    public function actionSertificateConfirm($id)
    {
        $model = $this->sertificatefindModel($id);

        $old = $model;
        if ($this->request->isPost) {
            $post = $this->request->post();
            if ($model->load($post)) {
                $result = ExamSubject::confirm($model, $old);
                if ($result['is_ok']) {
                    \Yii::$app->session->setFlash('success');
                } else {
                    \Yii::$app->session->setFlash('error', $result['errors']);
                }
                return $this->redirect(['view', 'id' => $model->student_id]);
            }
        }

        return $this->renderAjax('sertificate-confirm', [
            'model' => $model,
        ]);
    }

    public function actionAddBall($id)
    {
        $model = $this->sertificatefindModel($id);

        $old = $model;
        if ($this->request->isPost) {
            $post = $this->request->post();
            if ($model->load($post)) {
                $result = ExamSubject::addBall($model, $old);
                if ($result['is_ok']) {
                    \Yii::$app->session->setFlash('success');
                } else {
                    \Yii::$app->session->setFlash('error', $result['errors']);
                }
                return $this->redirect(['view', 'id' => $model->student_id]);
            }
        }

        return $this->renderAjax('add-ball', [
            'model' => $model,
        ]);
    }

    public function actionContractLoad($id, $type)
    {
        $errors = [];
        $student = Student::findOne(['id' => $id]);

        $eduDirection = $student->eduDirection;
        if ($eduDirection) {
            if ($eduDirection->type == 2) {
                if (!permission('student', 'contract-load-admins')) {
                    $errors[] = ['Shartnoma yuklab olishingizga ruxsat berilmagan.'];
                }
            }
        } else {
            $errors[] = ['Shartnomangiz mavjud emas.'];
        }

        if (count($errors) > 0) {
            \Yii::$app->session->setFlash('error', $errors);
            return $this->redirect(\Yii::$app->request->referrer);
        }

        $action = '';
        if ($type == 2) {
            $action = 'con2';
        } elseif ($type == 3) {
            $action = 'con3';
        } else {
            $errors[] = ['Type not\'g\'ri tanlandi!'];
            \Yii::$app->session->setFlash('error', $errors);
            return $this->redirect(\Yii::$app->request->referrer);
        }

        $result = Contract::crmPush($student);
        if (!$result['is_ok']) {
            \Yii::$app->session->setFlash('error', $result['errors']);
            return $this->redirect(\Yii::$app->request->referrer);
        }

        $pdf = \Yii::$app->ikPdf;
        $content = $pdf->contract($student, $action);

        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_DOWNLOAD,
            'content' => $content,
            'cssInline' => '
                body {
                    color: #000000;
                }
            ',
            'filename' => date('YmdHis') . ".pdf",
            'options' => [
                'title' => 'Contract',
                'subject' => 'Student Contract',
                'keywords' => 'pdf, contract, student',
            ],
        ]);

        return $pdf->render();
    }


    public function actionContractLoadAdmins($id, $type) {}

    public function actionContractUpdate($id, $type)
    {
        $model = new Contract();

        $models = [
            1 => Exam::class,
            2 => StudentPerevot::class,
            3 => StudentDtm::class,
            4 => StudentMaster::class,
        ];

        $modelClass = $models[$type] ?? null;
        $query = $modelClass::findOne($id);

        if ($this->request->isPost) {
            $post = $this->request->post();
            if ($model->load($post)) {
                $result = Student::contractUpdate($query, $model);
                if ($result['is_ok']) {
                    \Yii::$app->session->setFlash('success');
                } else {
                    \Yii::$app->session->setFlash('error', $result['errors']);
                }
                return $this->redirect(['view', 'id' => $query->student_id]);
            }
        }

        return $this->renderAjax('contract-update', [
            'model' => $model,
            'query' => $query,
        ]);
    }


    public function actionExamChange($id)
    {
        $student = $this->findModel($id);
        $result = Exam::change($student);
        if ($result['is_ok']) {
            \Yii::$app->session->setFlash('success');
        } else {
            \Yii::$app->session->setFlash('error', $result['errors']);
        }
        return $this->redirect(\Yii::$app->request->referrer);
    }


    /**
     * Finds the Student model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Student the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */

    protected function findModelView($id)
    {
        if (($model = Student::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }


    protected function findModel($id)
    {
        if (($model = Student::findOne(['id' => $id])) !== null) {
            $user = $model->user;
            $isPermission = $model->contractCheck;
            if ($isPermission && $user->status != 0) {
                return $model;
            }
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }

    protected function ofertafindModel($id)
    {
        if (($model = StudentOferta::findOne(['id' => $id, 'is_deleted' => 0])) !== null) {
            $user = $model->student->user;
            $isPermission = $model->student->contractCheck;
            if ($isPermission && $user->status == 10) {
                return $model;
            }
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }

    protected function trFindModel($id)
    {
        if (($model = StudentPerevot::findOne(['id' => $id, 'is_deleted' => 0])) !== null) {
            $user = $model->student->user;
            $isPermission = $model->student->contractCheck;
            if ($isPermission && $user->status == 10) {
                return $model;
            }
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }

    protected function dtmFindModel($id)
    {
        if (($model = StudentDtm::findOne(['id' => $id, 'is_deleted' => 0])) !== null) {
            $user = $model->student->user;
            $isPermission = $model->student->contractCheck;
            if ($isPermission && $user->status == 10) {
                return $model;
            }
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }

    protected function masterFindModel($id)
    {
        if (($model = StudentMaster::findOne(['id' => $id, 'is_deleted' => 0])) !== null) {
            $user = $model->student->user;
            $isPermission = $model->student->contractCheck;
            if ($isPermission && $user->status == 10) {
                return $model;
            }
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }

    protected function sertificateFindModel($id)
    {
        if (($model = ExamSubject::findOne(['id' => $id, 'is_deleted' => 0])) !== null) {
            $user = $model->student->user;
            $isPermission = $model->student->contractCheck;
            if ($isPermission && $user->status == 10 && $user->step == 5) {
                return $model;
            }
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }

    protected function examFindModel($id)
    {
        if (($model = Exam::findOne(['id' => $id, 'is_deleted' => 0])) !== null) {
            $user = $model->student->user;
            $isPermission = $model->student->contractCheck;
            if ($isPermission && $user->status == 10) {
                return $model;
            }
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }

    protected function eduTypeFindModel($id)
    {
        if (($model = EduType::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
