<?php

use yii\db\Migration;

/**
 * Class m211129_160042_add_table_auth
 */
class m211129_160042_add_table_auth extends Migration
{

    public function safeUp()
    {
        $this->createTable('auth', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'source' => $this->string()->notNull(),
            'source_id' => $this->string()->notNull(),
        ]);
        $this->addForeignKey('fk-auth-user_id-user-id', 'auth', 'user_id', 'users', 'id', 'CASCADE', 'CASCADE');
    }


    public function safeDown()
    {
        $this->dropTable('auth');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211129_160042_add_table_auth cannot be reverted.\n";

        return false;
    }
    */
}
