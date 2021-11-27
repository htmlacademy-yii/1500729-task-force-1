<?php

use yii\db\Migration;

/**
 * Class m211127_065708_change_width_column_adress
 */
class m211127_065708_change_width_column_adress extends Migration
{

    public function safeUp()
    {
          $this->alterColumn('tasks', 'address', $this->text(500));
    }


    public function safeDown()
    {
        echo "m211127_065708_change_width_column_adress cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211127_065708_change_width_column_adress cannot be reverted.\n";

        return false;
    }
    */
}
