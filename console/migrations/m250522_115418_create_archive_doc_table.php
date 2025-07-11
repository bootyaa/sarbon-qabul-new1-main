<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%archive_doc}}`.
 */
class m250522_115418_create_archive_doc_table extends Migration
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

        $tableName = Yii::$app->db->tablePrefix . 'archive_doc';
        if (!(Yii::$app->db->getTableSchema($tableName, true) === null)) {
            $this->dropTable('archive_doc');
        }


        $this->createTable('{{%archive_doc}}', [
            'id' => $this->primaryKey(),
            'student_id' => $this->integer()->null(),
            'direction_id' => $this->integer()->null(),
            'edu_form_id' => $this->integer()->null(),
            'edu_direction_id' => $this->integer()->null(),

            'direction' => $this->string(255)->null(),
            'edu_form' => $this->string(100)->null(),
            'student_full_name' => $this->string(255)->null(),
            'phone_number' => $this->string(50)->null(),
            'submission_date' => $this->date()->defaultValue(date('Y-m-d'))->null(),

            'application_letter' => $this->boolean()->defaultValue(false),
            'passport_copy' => $this->boolean()->defaultValue(false),
            'diploma_original' => $this->boolean()->defaultValue(false),
            'photo_3x4' => $this->boolean()->defaultValue(false),
            'contract_copy' => $this->boolean()->defaultValue(false),
            'payment_receipt' => $this->boolean()->defaultValue(false),

            'created_at' => $this->integer()->null(),
            'updated_at' => $this->integer()->null(),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'is_deleted' => $this->tinyInteger()->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->addForeignKey('archive_doc_table_student_table', 'archive_doc', 'student_id', 'student', 'id');
        $this->addForeignKey('archive_doc_table_direction_table', 'archive_doc', 'direction_id', 'direction', 'id');
        $this->addForeignKey('archive_doc_table_edu_form_table', 'archive_doc', 'edu_form_id', 'edu_form', 'id');
        $this->addForeignKey('archive_doc_table_edu_direction_table', 'archive_doc', 'edu_direction_id', 'edu_direction', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('archive_doc_table_student_table', 'archive_doc');
        $this->dropForeignKey('archive_doc_table_direction_table', 'archive_doc');
        $this->dropForeignKey('archive_doc_table_edu_form_table', 'archive_doc');
        $this->dropForeignKey('archive_doc_table_edu_direction_table', 'archive_doc');
        $this->dropTable('{{%archive_doc}}');
    }
}
