<?php

use yii\db\Migration;

class m250422_072935_alter_hr_column_to_consulting_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('consulting', 'hr', $this->text()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250422_072935_alter_hr_column_to_consulting_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250422_072935_alter_hr_column_to_consulting_table cannot be reverted.\n";

        return false;
    }
    */
}
