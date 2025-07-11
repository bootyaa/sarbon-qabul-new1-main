<?php

use yii\db\Migration;

class m250526_050144_create_exam_student_question_history extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/51278467/mysql-collation-utf8mb4-unicode-ci-vs-utf8mb4-default-collation
            // https://www.eversql.com/mysql-utf8-vs-utf8mb4-whats-the-difference-between-utf8-and-utf8mb4/
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';
        }

        $tableName = Yii::$app->db->tablePrefix . 'exam_student_question_history';
        if (!(Yii::$app->db->getTableSchema($tableName, true) === null)) {
            $this->dropTable('exam_student_question_history');
        }



        $this->createTable('exam_student_question_history', [
            'id' => $this->primaryKey(),
            'exam_student_question_id' => $this->integer()->notNull(),
            'is_correct' => $this->integer()->null(),
            'data' => $this->json()->null(),
            'created_at' => $this->integer()->notNull()->defaultValue(0)->comment('Creation timestamp'),
            'updated_at' => $this->integer()->notNull()->defaultValue(0)->comment('Update timestamp'),
            'created_by' => $this->integer()->notNull()->defaultValue(0)->comment('ID of the user who created the record'),
            'updated_by' => $this->integer()->notNull()->defaultValue(0)->comment('ID of the user who last updated the record'),

        ], $tableOptions);

        // $this->addForeignKey('exam_student_question_history_exam_student_question', 'exam_student_question_history', 'exam_student_question_id', 'exam_student_questions', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('exam_student_question_history');
    }
}
