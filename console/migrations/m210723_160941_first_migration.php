<?php

use frontend\models\Users;
use yii\db\Migration;

/**
 * Class m210723_160941_firstmigration
 */
class m210723_160941_firstmigration extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $data = ['data/schema.sql',
            'data/categories.sql',
            'data/locations.sql'];

        foreach ($data as $file) {
            $sql = file_get_contents($file);
            $this->execute($sql);
        }
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210723_160941_firstmigration cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210723_160941_firstmigration cannot be reverted.\n";

        return false;
    }
    */
}
