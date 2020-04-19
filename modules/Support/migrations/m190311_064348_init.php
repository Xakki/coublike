<?php

use yii\db\Migration;

/**
 * Class m190311_064348_init
 */
class m190311_064348_init extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{support_message}}', [
            'id' => $this->primaryKey(),
            'created' => $this->dateTime(),
            'read' => $this->dateTime(),
            'user_from' => $this->integer(),
            'user_to' => $this->integer(),
            'mess' => $this->string(255),
            'del_from' => $this->dateTime(),
            'del_to' => $this->dateTime(),
        ]);
        $this->createIndex('user_from_idx', '{{support_message}}', 'user_from');
        $this->createIndex('user_to_idx', '{{support_message}}', 'user_to');

        return true;
    }

    public function safeDown()
    {
        $this->dropTable('{{support_message}}');
        return true;
    }

}
