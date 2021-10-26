<?php

use yii\db\Migration;

/**
 * Class m211025_045703_add_coordinates_to_tasks
 */
class m211025_045703_add_coordinates_to_tasks extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->addColumn('tasks', 'latitude', 'DECIMAL(10,6)');
         $this->addColumn('tasks', 'longitude', 'DECIMAL(10,6)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('tasks', 'latitude');
        $this->dropColumn('tasks', 'longitude');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211025_045703_add_coordinates_to_tasks cannot be reverted.\n";

        return false;
    }
    */
}
