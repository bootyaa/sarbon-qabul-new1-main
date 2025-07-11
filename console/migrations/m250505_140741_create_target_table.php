<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%target}}`.
 */
class m250505_140741_create_target_table extends Migration
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

        $tableName = Yii::$app->db->tablePrefix . 'target';
        if (!(Yii::$app->db->getTableSchema($tableName, true) === null)) {
            $this->dropTable('target');
        }

        $this->createTable('{{%target}}', [
            'id' => $this->primaryKey(),
            'cons_id' => $this->integer()->null(),
            'type' => $this->integer()->defaultValue(0),
            'name' => $this->string(255)->notNull(),
            'created_at'=>$this->integer()->null(),
            'updated_at'=>$this->integer()->null(),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'is_deleted' => $this->tinyInteger()->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->addForeignKey('ik_target_table_consulting_table', 'target', 'cons_id', 'consulting', 'id');

        $this->addColumn('user' , 'target_id' , $this->integer()->null());
        $this->addForeignKey('ik_user_table_target_table', 'user', 'target_id', 'target', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%target}}');
    }
}
