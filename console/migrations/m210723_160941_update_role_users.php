<?php

use frontend\models\Users;
use yii\db\Migration;

/**
 * Class m210723_160941_update_role_users
 */
class m210723_160941_update_role_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = file_get_contents('data/schema.sql');
        $this->execute($sql);


        $users = Users::find()->all();
        foreach ($users as $user) {
            $this->update('users', ['role' => rand(0,1)], ['id' => $user->id]);
        }
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210723_160941_update_role_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210723_160941_update_role_users cannot be reverted.\n";

        return false;
    }
    */
}
