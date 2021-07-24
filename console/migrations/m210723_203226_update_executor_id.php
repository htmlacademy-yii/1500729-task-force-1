<?php

use yii\db\Migration;

/**
 * Class m210723_203226_update_executor_id
 */
class m210723_203226_update_executor_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tasks = \frontend\models\Tasks::find()->all();
        foreach ($tasks as $task) {
            $this->update('tasks', ['executor_id' => rand(1,20)], ['id' => $task->id]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210723_203226_update_executor_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210723_203226_update_executor_id cannot be reverted.\n";

        return false;
    }
    */
}
