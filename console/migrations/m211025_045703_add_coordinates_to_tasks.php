<?php

use yii\db\Migration;

class m211025_045703_add_coordinates_to_tasks extends Migration
{

    public function safeUp()
    {
         $this->addColumn('tasks', 'latitude', 'DECIMAL(10,6)');
         $this->addColumn('tasks', 'longitude', 'DECIMAL(10,6)');
    }

    public function safeDown()
    {
        $this->dropColumn('tasks', 'latitude');
        $this->dropColumn('tasks', 'longitude');
    }

}
