<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%direction}}`.
 */
class m250422_071017_add_type_column_to_direction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('direction', 'type', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
