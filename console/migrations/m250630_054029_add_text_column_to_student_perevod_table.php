<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%student_perevod}}`.
 */
class m250630_054029_add_text_column_to_student_perevod_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('student_perevot', 'text', $this->text()->null());
        $this->addColumn('student_oferta', 'text', $this->text()->null());
        $this->addColumn('student_dtm', 'text', $this->text()->null());
        $this->addColumn('student_master', 'text', $this->text()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
