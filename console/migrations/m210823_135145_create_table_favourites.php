<?php

use yii\db\Migration;

/**
 * Class m210823_135145_create_table_favourites
 */
class m210823_135145_create_table_favourites extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->createTable('favourites',
         [
             'id' => $this->primaryKey(),
             'author_id' => $this->integer(),
             'executor_id' => $this->integer()
         ]);
         $this->addForeignKey('author_id', 'favourites', 'author_id', 'users', 'id');
        $this->addForeignKey('executor_id', 'favourites', 'executor_id', 'users', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('favourites');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210823_135145_create_table_favourites cannot be reverted.\n";

        return false;
    }
    */
}
