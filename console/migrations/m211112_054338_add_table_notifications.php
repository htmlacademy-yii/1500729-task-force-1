<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m211112_054338_add_table_notifications
 */
class m211112_054338_add_table_notifications extends Migration
{

    public function safeUp()
    {
        $this->createTable('notifications', ['id' => $this->primaryKey(),
            'type' => $this->string()->notNull(),
            'task_id' => $this->integer()->notNull(),
            'recipient_id' => $this->integer()->notNull(),
            'date_add' => $this->dateTime()->defaultValue(new \yii\db\Expression('NOW()')),
            'read' => $this->integer()->defaultValue(0)
        ]);
        $this->addForeignKey('task_id', 'notifications', 'task_id', 'tasks', 'id');
        $this->addForeignKey('recipient_id', 'notifications', 'recipient_id', 'users', 'id');


    }

    public function safeDown()
    {
        $this->dropTable('notifications');
    }
}
