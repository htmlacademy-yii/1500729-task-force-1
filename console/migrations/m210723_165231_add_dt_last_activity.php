<?php


use frontend\models\Users;
use yii\db\Migration;

/**
 * Class m210723_165231_add_dt_last_activity
 */
class m210723_165231_add_dt_last_activity extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $users = Users::find()->all();
         foreach ($users as $user) {
             $randomInt = rand(1611410112, 1627048512);
             $randomDate = date("Y-m-d H:i:s", $randomInt);
             $this->update('users',['dt_last_activity' => $randomDate], ['id' => $user->id]);
         }

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210723_165231_add_dt_last_activity cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210723_165231_add_dt_last_activity cannot be reverted.\n";

        return false;
    }
    */
}
