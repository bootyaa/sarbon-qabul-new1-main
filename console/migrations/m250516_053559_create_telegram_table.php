<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%telegram}}`.
 */
class m250516_053559_create_telegram_table extends Migration
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

        $tableName = Yii::$app->db->tablePrefix . 'telegram';
        if (!(Yii::$app->db->getTableSchema($tableName, true) === null)) {
            $this->dropTable('telegram');
        }

        $this->createTable('{{%telegram}}', [
            'id' => $this->primaryKey(),
            'telegram_id' => $this->string(255)->null(),
            'phone' => $this->string(255)->null(),
            'user_id' => $this->string(255)->null(),
            'step' => $this->integer()->defaultValue(0),
            'lang_id' => $this->integer()->defaultValue(1),

            'birthday' => $this->string()->null(),
            'passport_number' => $this->string()->null(),
            'passport_serial' => $this->string()->null(),
            'passport_pin' => $this->string()->null(),

            'edu_type_id' => $this->integer()->null(),
            'edu_form_id' => $this->integer()->null(),
            'edu_direction_id' => $this->integer()->null(),
            'direction_course_id' => $this->integer()->null(),
            'exam_type' => $this->integer()->defaultValue(0),
            'branch_id' => $this->integer()->null(),
            'exam_date_id' => $this->integer()->null(),

            'status' => $this->integer()->defaultValue(1),
            'created_at'=>$this->integer()->null(),
            'updated_at'=>$this->integer()->null(),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'is_deleted' => $this->tinyInteger()->defaultValue(0),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%telegram}}');
    }
}
