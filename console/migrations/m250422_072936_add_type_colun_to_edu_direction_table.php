<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%cons}}`.
 */
class m250422_072936_add_type_colun_to_edu_direction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('edu_direction', 'type' , $this->integer()->defaultValue(0));

        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/51278467/mysql-collation-utf8mb4-unicode-ci-vs-utf8mb4-default-collation
            // https://www.eversql.com/mysql-utf8-vs-utf8mb4-whats-the-difference-between-utf8-and-utf8mb4/
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';
        }

        $tableName = Yii::$app->db->tablePrefix . 'direction_ball';
        if (!(Yii::$app->db->getTableSchema($tableName, true) === null)) {
            $this->dropTable('direction_ball');
        }

        $this->createTable('{{%direction_ball}}', [
            'id' => $this->primaryKey(),
            'edu_direction_id' => $this->integer()->null(),
            'type' => $this->float()->defaultValue(0),
            'start_ball' => $this->float()->defaultValue(0),
            'end_ball' => $this->float()->defaultValue(0),

            'status' => $this->integer()->defaultValue(1),
            'created_at'=>$this->integer()->null(),
            'updated_at'=>$this->integer()->null(),
            'created_by' => $this->integer()->defaultValue(0),
            'updated_by' => $this->integer()->defaultValue(0),
            'is_deleted' => $this->tinyInteger()->defaultValue(0),
        ], $tableOptions);
        $this->addForeignKey('ik_direction_ball_table_edu_direction_table', 'direction_ball', 'edu_direction_id', 'edu_direction', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
