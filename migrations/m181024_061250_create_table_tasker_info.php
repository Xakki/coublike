<?php

use yii\db\Migration;

/**
 * Class m181024_061250_create_table_tasker_info
 */
class m181024_061250_create_table_tasker_info extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('tasker_info', [
            'tasker_id' => 'int(10) unsigned NOT NULL COMMENT \'ID задачи\'',
            'ti_date' => 'datetime NOT NULL',
            'ti_info' => 'text NOT NULL',
        ]);
        $this->createIndex('tasker_id_x', 'tasker_info', 'tasker_id');
        $this->dropColumn('tasker_data', 'td_data');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181024_061250_create_table_tasker_info cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181024_061250_create_table_tasker_info cannot be reverted.\n";

        return false;
    }
    */
}
