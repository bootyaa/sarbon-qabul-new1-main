<?php

namespace backend\controllers;

use common\models\ArchiveDoc;
use common\models\ArchiveDocSearch;
use common\models\Student;
use Mpdf\Mpdf;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ArchiveDocController implements the CRUD actions for Target model.
 */
class ArchiveDocController extends Controller
{
    use ActionTrait;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ArchiveDoc models.
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ArchiveDocSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ArchiveDoc model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ArchiveDoc model.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ArchiveDoc();

        if ($model->load(Yii::$app->request->post())) {
            // Talaba ma’lumotlarini student_id orqali olish
            $student = Student::findOne($model->student_id);
            if ($student) {
                $model->student_full_name = $student->first_name . ' ' . $student->last_name . ' ' . $student->middle_name;
                $model->direction = $student->eduDirection->direction->name_uz;
                $model->edu_form = $student->eduForm->name_uz;
                $model->phone_number = $student->username;
                $model->direction_id = $student->direction_id;
                $model->edu_form_id = $student->edu_form_id;
                $model->edu_direction_id = $student->edu_direction_id;
                $model->submission_date = date('Y-m-d');
            }

            if ($model->save()) {
                return $this->redirect(['index']); // ✅ view emas, index sahifasiga qaytadi
                // return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    // public function actionCreate1()
    // {
    //     $model = new ArchiveDoc();

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         return $this->redirect(['view', 'id' => $model->id]);
    //     }

    //     return $this->render('create', [
    //         'model' => $model,
    //     ]);
    // }


    public function actionPdf($id)
    {
        $model = $this->findModel($id);

        $html = $this->renderPartial('pdf', [
            'model' => $model,
        ]);

        $pdf = new Mpdf([
            'format' => 'A4',
            'margin_top' => 20,
            'margin_bottom' => 20, // pastgi bo‘sh joyni ham aniqlaymiz
            'tempDir' => __DIR__ . '/../runtime/mpdf_temp',
        ]);

        // Footer qo‘shamiz
        $pdf->SetHTMLFooter('<div style="text-align: center;">Toshkent – ' . date('Y') . ' yil</div>');

        $pdf->WriteHTML($html);
        return $pdf->Output('Talaba_blanka.pdf', \Mpdf\Output\Destination::INLINE);
    }

    /**
     * Updates an existing ArchiveDoc model.
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes (soft delete) an existing ArchiveDoc model.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->is_deleted = 1;
        $model->save(false);

        return $this->redirect(['index']);
    }

    /**
     * Finds the ArchiveDoc model based on its primary key value.
     * @param int $id
     * @return ArchiveDoc
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = ArchiveDoc::findOne(['id' => $id, 'is_deleted' => 0])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested document does not exist.');
    }
}
