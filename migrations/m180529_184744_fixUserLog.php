<?php

use yii\db\Migration;
use yii\db\Schema;
/**
 * Class m180529_184744_fixUserLog
 */
class m180529_184744_fixUserLog extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user_log}}', 'ul_int', Schema::TYPE_INTEGER);
        $this->addColumn('{{%user_log}}', 'ul_int2', Schema::TYPE_INTEGER);
        $this->addColumn('{{%user_log}}', 'ul_flag', Schema::TYPE_BOOLEAN.' NOT NULL DEFAULT 0');
        $this->alterColumn('{{%user_pay}}', 'up_paysystem', Schema::TYPE_STRING.'(64) NOT NULL COMMENT \'class name\'');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180529_184744_fixUserLog cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180529_184744_fixUserLog cannot be reverted.\n";

        return false;
    }
    */
}
