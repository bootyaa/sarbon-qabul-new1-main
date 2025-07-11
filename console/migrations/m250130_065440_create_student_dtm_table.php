<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%student_dtm}}`.
 */
class m250130_065440_create_student_dtm_table extends Migration
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

        $tableName = Yii::$app->db->tablePrefix . 'student_dtm';
        if (!(Yii::$app->db->getTableSchema($tableName, true) === null)) {
            $this->dropTable('student_dtm');
        }

        $this->createTable('{{%student_dtm}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->null(),
            'student_id' => $this->integer()->null(),
            'edu_direction_id' => $this->integer()->null(),
            'direction_id' => $this->integer()->null(),
            'language_id' => $this->integer()->null(),
            'edu_type_id' => $this->integer()->null(),
            'edu_form_id' => $this->integer()->null(),

            'file' => $this->string()->null(),
            'file_status' => $this->tinyInteger(1)->defaultValue(0),

            'status' => $this->integer()->defaultValue(1),
            'created_at'=>$this->integer()->null(),
            'updated_at'=>$this->integer()->null(),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'is_deleted' => $this->tinyInteger()->defaultValue(0),
        ], $tableOptions);
        $this->addForeignKey('ik_student_dtm_table_user_table', 'student_dtm', 'user_id', 'user', 'id');
        $this->addForeignKey('ik_student_dtm_table_student_table', 'student_dtm', 'student_id', 'student', 'id');
        $this->addForeignKey('ik_student_dtm_table_edu_direction_table', 'student_dtm', 'edu_direction_id', 'edu_direction', 'id');
        $this->addForeignKey('ik_student_dtm_table_direction_table', 'student_dtm', 'direction_id', 'direction', 'id');
        $this->addForeignKey('ik_student_dtm_table_language_table', 'student_dtm', 'language_id', 'lang', 'id');
        $this->addForeignKey('ik_student_dtm_table_edu_type_table', 'student_dtm', 'edu_type_id', 'edu_type', 'id');
        $this->addForeignKey('ik_student_dtm_table_edu_form_table', 'student_dtm', 'edu_form_id', 'edu_form', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%student_dtm}}');
    }
}
