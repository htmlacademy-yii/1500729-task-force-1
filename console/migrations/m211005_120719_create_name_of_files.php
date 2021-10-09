<?php

use yii\db\Migration;

/**
 * Class m211005_120719_create_name_of_files
 */
class m211005_120719_create_name_of_files extends Migration
{
    public function safeUp()
    {
        $this->addColumn('files', 'name', $this->char(128));
    }

    public function safeDown()
    {
        $this->dropColumn('files', 'name');
    }
}
