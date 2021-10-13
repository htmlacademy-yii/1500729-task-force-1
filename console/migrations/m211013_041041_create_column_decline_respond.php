<?php

use yii\db\Migration;

/**
 * Class m211013_041041_create_column_decline_respond
 */
class m211013_041041_create_column_decline_respond extends Migration
{

    public function safeUp()
    {
         $this->addColumn('responds', 'decline', $this->tinyInteger(2));
    }


    public function safeDown()
    {
        $this->dropColumn('responds', 'decline');
    }
}
