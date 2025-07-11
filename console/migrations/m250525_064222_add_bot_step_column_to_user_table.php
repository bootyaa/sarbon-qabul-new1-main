<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m250525_064222_add_bot_step_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex(
            'idx-user-telegram_id',
            'user',
            'telegram_id'
        );

        $this->addColumn('telegram', 'oferta_file', $this->string()->null());
        $this->addColumn('telegram', 'perevot_file', $this->string()->null());
        $this->addColumn('telegram', 'dtm_file', $this->string()->null());
        $this->addColumn('telegram', 'master_file', $this->string()->null());

        $this->createIndex(
            'idx-telegram-telegram_id',
            'telegram',
            'telegram_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
