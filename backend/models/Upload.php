<?php

namespace backend\models;


use common\models\Options;
use common\models\Questions;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;


/**
 * Signup form
 */
class Upload extends Model
{
    public $file;
    public $fileMaxSize = 1024 * 1000 * 20;

    public function rules()
    {
        return [
            [
                [ 'file' ],
                'file',
                'extensions'=>'xlsx',
                'skipOnEmpty' => true,
                'maxSize' => $this->fileMaxSize
            ],
        ];
    }

    function simple_errors($errors) {
        $result = [];
        foreach ($errors as $lev1) {
            foreach ($lev1 as $key => $error) {
                $result[] = $error;
            }
        }
        return array_unique($result);
    }

    public static function upload1($model, $subject) {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        if (!$model->validate()) {
            $errors[] = $model->simple_errors($model->errors);
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }

        $photoFile = UploadedFile::getInstance($model, 'file');
        if ($photoFile) {
            if (isset($photoFile->size)) {
                $photoFolderName = '@backend/web/uploads/excel_questions/';
                if (!file_exists(\Yii::getAlias($photoFolderName))) {
                    mkdir(\Yii::getAlias($photoFolderName), 0777, true);
                }
                $photoName = \Yii::$app->security->generateRandomString(20). '.' . $photoFile->extension;
                $url = $photoFolderName."/".$photoName;
                $photoFile->saveAs($photoFolderName."/".$photoName);

                $inputFileName = \Yii::getAlias($url);
                $spreadsheet = IOFactory::load($inputFileName);
                $data = $spreadsheet->getActiveSheet()->toArray();

                if (!file_exists($inputFileName)) {
                    unlink($inputFileName);
                }

                foreach ($data as $key => $row) {

                    if ($key != 0) {
                        $question = $row[0];
                        $optionTrue = $row[1];
                        $option1 = $row[2];
                        $option2 = $row[3];
                        $option3 = $row[4];

                        if ($question == "") {
                            break;
                        }
                        if ($optionTrue == "") {
                            $optionTrue = ".";
                        }
                        if ($option1 == "") {
                            $option1 = ".";
                        }
                        if ($option2 == "") {
                            $option2 = ".";
                        }
                        if ($option3 == "") {
                            $option3 = ".";
                        }

                        $option = [
                            0 => $optionTrue,
                            1 => $option1,
                            2 =>$option2,
                            3 =>$option3,
                        ];
                        $optionData = custom_shuffle($option);
                        $new = new Questions();
                        $new->subject_id = $subject->id;
                        $new->text = $question;
                        $new->status = 1;
                        if (!$new->validate()) {
                            $errors[] = $new->errors;
                            $transaction->rollBack();
                            return ['is_ok' => false , 'errors' => $errors];
                        }
                        if ($new->save(false)) {
                            foreach ($optionData as $key => $item) {
                                $newOption = new Options();
                                $newOption->question_id = $new->id;
                                $newOption->text = $item;
                                $newOption->subject_id = $subject->id;
                                if ($key == 0) {
                                    $newOption->is_correct = 1;
                                }
                                if (!$newOption->save(false)) {
                                    $errors[] = ['Option not saved.'];
                                    $transaction->rollBack();
                                    return ['is_ok' => false , 'errors' => $errors];
                                }
                            }
                        } else {
                            $errors[] = $new->errors;
                            $transaction->rollBack();
                            return ['is_ok' => false , 'errors' => $errors];
                        }
                    }
                }
            }
        } else {
            $errors[] = ['Fayl yuborilmadi!'];
        }

        if (count($errors) == 0) {
            $transaction->commit();
            return ['is_ok' => true];
        }else {
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }
    }

    public static function upload($model, $subject)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        if (!$model->validate()) {
            $errors[] = $model->simple_errors($model->errors);
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }

        $photoFile = UploadedFile::getInstance($model, 'file');
        if ($photoFile && isset($photoFile->size)) {
            $uploadDir = '@backend/web/uploads/excel_questions/';
            if (!file_exists(\Yii::getAlias($uploadDir))) {
                mkdir(\Yii::getAlias($uploadDir), 0777, true);
            }

            $photoName = \Yii::$app->security->generateRandomString(20) . '.' . $photoFile->extension;
            $filePath = $uploadDir . "/" . $photoName;
            $photoFile->saveAs(\Yii::getAlias($filePath));

            $spreadsheet = IOFactory::load(\Yii::getAlias($filePath));
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();
            $drawings = $sheet->getDrawingCollection();

            $images = [];

            foreach ($drawings as $drawing) {
                $coordinate = $drawing->getCoordinates();
                $row = preg_replace('/[^0-9]/', '', $coordinate);
                $col = preg_replace('/[0-9]/', '', $coordinate);

                try {
                    if ($drawing instanceof MemoryDrawing) {
                        ob_start();
                        call_user_func($drawing->getRenderingFunction(), $drawing->getImageResource());
                        $imageData = ob_get_contents();
                        ob_end_clean();
                        $extension = $drawing->getMimeType() == MemoryDrawing::MIMETYPE_PNG ? 'png' : 'jpg';
                    } else {
                        $imageData = file_get_contents($drawing->getPath());
                        $extension = $drawing->getExtension();
                    }

                    // Rasm fayl nomini yaratish
                    $photoName = $subject->id . "_" . time() . "_" . Yii::$app->security->generateRandomString(20) . '.' . $extension;

                    // Papkaga qarab rasmni saqlash
                    if ($col == 'A') {
                        $dir = '@backend/web/uploads/questions/';
                    } else {
                        $dir = '@backend/web/uploads/options/';
                    }

                    if (!file_exists(Yii::getAlias($dir))) {
                        mkdir(Yii::getAlias($dir), 0777, true);
                    }

                    $fullPath = Yii::getAlias($dir . $photoName);
                    file_put_contents($fullPath, $imageData);

                    // Faqat fayl nomini saqlaymiz
                    $images[$row][$col] = $photoName;

                } catch (\Exception $e) {
                    Yii::warning("Rasmni saqlashda xatolik: " . $e->getMessage());
                }
            }

            foreach ($data as $key => $row) {
                if ($key == 0) continue;

                $questionText = $row[0];
                $optionTrue = $row[1] ?? '.';
                $option1 = $row[2] ?? '.';
                $option2 = $row[3] ?? '.';
                $option3 = $row[4] ?? '.';

                if (empty($questionText)) break;

                $optionData = custom_shuffle([
                    ['text' => $optionTrue, 'column' => 'B'],
                    ['text' => $option1, 'column' => 'C'],
                    ['text' => $option2, 'column' => 'D'],
                    ['text' => $option3, 'column' => 'E'],
                ]);

                $new = new Questions();
                $new->subject_id = $subject->id;
                $new->text = $questionText;
                $new->status = 1;

                $rowNumber = $key + 1;

                if (isset($images[$rowNumber]['A'])) {
                    $new->image = $images[$rowNumber]['A'];
                }

                if (!$new->validate()) {
                    $errors[] = $new->errors;
                    $transaction->rollBack();
                    return ['is_ok' => false, 'errors' => $errors];
                }

                if ($new->save(false)) {
                    foreach ($optionData as $idx => $item) {
                        $newOption = new Options();
                        $newOption->question_id = $new->id;
                        $newOption->text = $item['text'];
                        $newOption->subject_id = $subject->id;
                        $newOption->is_correct = $idx == 0 ? 1 : 0;

                        $col = $item['column'];
                        if (isset($images[$rowNumber][$col])) {
                            $newOption->image = $images[$rowNumber][$col];
                        }

                        if (!$newOption->save(false)) {
                            $errors[] = ['Option not saved.'];
                            $transaction->rollBack();
                            return ['is_ok' => false, 'errors' => $errors];
                        }
                    }
                } else {
                    $errors[] = $new->errors;
                    $transaction->rollBack();
                    return ['is_ok' => false, 'errors' => $errors];
                }
            }

            unlink(Yii::getAlias($filePath));
        } else {
            $errors[] = ['Fayl yuborilmadi!'];
        }

        if (count($errors) == 0) {
            $transaction->commit();
            return ['is_ok' => true];
        } else {
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }
    }
}
