<?php

use yii\db\Migration;

/**
 * Class m211005_120719_create_name_of_files
 */
class m211005_120719_create_name_of_files extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->addColumn('files', 'name', $this->char(128));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('files', 'name');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211005_120719_create_name_of_files cannot be reverted.\n";

        return false;
    }
    */
}
