<?php

use yii\db\Schema;
use yii\db\Migration;

class m151120_145745_start extends Migration
{
    public function up()
    {
        $this->createTable('tasker', [
            'id' => $this->primaryKey(),
            'time_cr' => 'int(10) unsigned NOT NULL COMMENT \'Время создания\'',
            'time_up' => 'int(10) unsigned DEFAULT NULL COMMENT \'Время обновления\'',
            'time_end' => 'int(10) unsigned DEFAULT NULL COMMENT \'Время завершения\'',
            'type' => 'enum(\'like\',\'repost\',\'follow\',\'view\') NOT NULL COMMENT \'Тип задачи\'',
            'social' => 'enum(\'tw\',\'coub\',\'fb\',\'vk\') NOT NULL COMMENT \'Соц. сеть\'',
            'status' => 'tinyint(3) unsigned NOT NULL DEFAULT \'0\' COMMENT \'Статус\'',
            'user_id' => 'int(10) unsigned NOT NULL COMMENT \'Пользователь\'',
            'group_id' => 'int(10) unsigned DEFAULT NULL COMMENT \'Группа\'',
            'stats_mode' => 'tinyint(2) unsigned DEFAULT 0 COMMENT \'Период сбора статистики в сутки\'',
            'social_id' => 'bigint(20) unsigned DEFAULT NULL COMMENT \'ID из соц сети\'',
            'comment' => 'varchar(255) DEFAULT NULL COMMENT \'Коментарии\'',
            'likes' => 'int(11) NOT NULL DEFAULT \'0\' COMMENT \'Баланс\'',
            'likes_rsv' => 'int(11) NOT NULL DEFAULT \'0\' COMMENT \'Баланс в резерве, выполняющиеся задания\'',
            'likes_sum' => 'int(11) NOT NULL DEFAULT \'0\' COMMENT \'Баланс итого, потрачено на задание\'',
            'social_link' => 'varchar(255) DEFAULT NULL',
            'social_link_tiny' => 'varchar(255) DEFAULT NULL',
            'tasker_data_id' => 'bigint(20) unsigned DEFAULT NULL',
            'stats_time' => 'int(11) DEFAULT 0',
        ]);
        $this->createIndex('ts_user_id_x', 'tasker', 'user_id');
        $this->createIndex('ts_status_x', 'tasker', 'status');
        $this->createIndex('ts_type_social_status_x', 'tasker', ['type', 'social', 'status']);


        $this->createTable('tasker_action', [
            'ta_id' => $this->primaryKey(),
            'ta_tasker_id' => 'int(10) unsigned NOT NULL COMMENT \'ID задачи\'',
            'ta_user_id' => 'int(10) unsigned NOT NULL COMMENT \'Пользователь\'',
            'ta_time' => 'int(10) unsigned NOT NULL COMMENT \'Время создания\'',
            'ta_status' => 'tinyint(3) unsigned NOT NULL COMMENT \'Статус\'',
            'ta_data' => 'varchar(255) COMMENT \'Доп данные\'',
        ]);
        $this->createIndex('ta_tasker_id_x', 'tasker_action', 'ta_tasker_id');
        $this->createIndex('ta_user_id_x', 'tasker_action', 'ta_user_id');

        $this->execute('CREATE TABLE `tasker_data` (
  `td_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `td_tasker_id` int(10) unsigned NOT NULL,
  `td_date` datetime NOT NULL,
  `td_data` text NOT NULL,
  `td_stats` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`td_id`,`td_date`),
  KEY `x_td_stats` (`td_tasker_id`,`td_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
PARTITION BY RANGE ( YEAR(td_date))
SUBPARTITION BY HASH ( MONTH(td_date))
SUBPARTITIONS 12
(PARTITION p_2016 VALUES LESS THAN (2016) ENGINE = MyISAM,
 PARTITION p_2017 VALUES LESS THAN (2017) ENGINE = MyISAM,
 PARTITION p_2018 VALUES LESS THAN (2018) ENGINE = MyISAM,
 PARTITION p_2019 VALUES LESS THAN (2019) ENGINE = MyISAM,
 PARTITION p_2020 VALUES LESS THAN (2020) ENGINE = MyISAM,
 PARTITION p_2021 VALUES LESS THAN (2021) ENGINE = MyISAM,
 PARTITION p_2022 VALUES LESS THAN (2022) ENGINE = MyISAM,
 PARTITION p_2023 VALUES LESS THAN (2023) ENGINE = MyISAM,
 PARTITION p_2024 VALUES LESS THAN (2024) ENGINE = MyISAM,
 PARTITION p_max VALUES LESS THAN MAXVALUE ENGINE = MyISAM);');


        $this->addColumn('{{%social_account}}', 'token', Schema::TYPE_STRING . '(255) NULL');

        $this->addColumn('tasker', 'action_sum', 'int(11) NOT NULL DEFAULT \'0\' COMMENT \'Сумма выполненных задач\'');
        $this->addColumn('tasker', 'action_cost', 'int(11) NOT NULL DEFAULT \'0\' COMMENT \'Стоймость выполнения задачи\'');
        $this->addColumn('tasker', 'reason', 'varchar(128) DEFAULT NULL COMMENT \'Причина блокировки\'');
        $this->renameColumn('tasker', 'likes_rsv', 'likes_spend');

        $this->createTable('user_log', [
            'ul_id' => $this->primaryKey(),
            'ul_type' => 'tinyint(1) unsigned NOT NULL COMMENT \'Тип лога\'',
            'ul_time' => 'int(10) unsigned NOT NULL COMMENT \'Время создания\'',
            'ul_user_id' => 'int(10) unsigned NOT NULL COMMENT \'Пользователь\'',
            'ul_log' => 'varchar(255) DEFAULT NULL COMMENT \'Лог\'',
        ]);
        $this->createIndex('ul_user_id_x', 'user_log', 'ul_user_id');

        $this->addColumn('{{%user}}', 'likes', Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT \'0\'');
        $this->addColumn('{{%user}}', 'likes_sum', Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT \'0\'');
        $this->addColumn('{{%user}}', 'likes_pay_sum', Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT \'0\'');
        $this->addColumn('{{%user}}', 'likes_earn_sum', Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT \'0\'');
        $this->addColumn('{{%user}}', 'likes_buy_sum', Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT \'0\'');

        $this->addColumn('{{%user}}', 'referral_id', Schema::TYPE_INTEGER . '(11) DEFAULT NULL');
        $this->addColumn('{{%user}}', 'referral_url', Schema::TYPE_STRING . '(255) DEFAULT NULL');
        $this->addColumn('{{%user}}', 'referral_earn', Schema::TYPE_INTEGER . '(11) DEFAULT NULL');
        $this->addColumn('{{%user}}', 'referral_buy', Schema::TYPE_INTEGER . '(11) DEFAULT NULL');
        $this->addColumn('{{%user}}', 'likes_ref_sum', Schema::TYPE_INTEGER . '(11) DEFAULT NULL');
        $this->addColumn('{{%user}}', 'login_at', Schema::TYPE_INTEGER . '(11) DEFAULT NULL');

        $this->createIndex('referral_id_x', '{{%user}}', 'referral_id');

        $this->createTable('user_pay', [
            'up_id' => $this->primaryKey(),
            'up_paysystem' => 'int(10) unsigned NOT NULL COMMENT \'ID\'',
            'up_time_cr' => 'int(10) unsigned NOT NULL COMMENT \'Время создания\'',
            'up_time_up' => 'int(10) unsigned NOT NULL COMMENT \'Время обновления\'',
            'up_amount' => 'float(10,4) unsigned NOT NULL COMMENT \'Сумма\'',
            'up_user_id' => 'int(10) unsigned NOT NULL COMMENT \'Пользователь\'',
            'up_status' => 'tinyint(3) unsigned NOT NULL COMMENT \'Статус\'',
            'up_likes' => 'int(10) unsigned NOT NULL COMMENT \'Лайки\'',
            'up_likes_bonus' => 'int(10) unsigned NOT NULL COMMENT \'+ бонус\'',
        ]);
        $this->createIndex('up_paysystem_user_idx', 'user_pay', 'up_user_id,up_paysystem');

        $this->createTable('messages', [
            'm_id' => $this->primaryKey(),
            'm_time_cr' => 'int(10) unsigned NOT NULL COMMENT \'Время создания\'',
            'm_user_from' => 'int(10) unsigned NOT NULL COMMENT \'Пользователь\'',
            'm_user_to' => 'int(10) unsigned NOT NULL COMMENT \'Пользователь\'',
            'm_text' => 'varchar(255) NOT NULL COMMENT \'текст\'',
            'm_del_from' => 'tinyint(1) NOT NULL DEFAULT 0 COMMENT \'удалил отправитель\'',
            'm_del_to' => 'tinyint(1) NOT NULL DEFAULT 0 COMMENT \'удалил получатель\'',
            'm_action' => 'int(11) NOT NULL DEFAULT 0 COMMENT \'статус\'',
        ]);
        $this->createIndex('m_user_from_idx', 'messages', 'm_user_from');
        $this->createIndex('m_user_to_idx', 'messages', 'm_user_to');

        return true;
    }

    public function down()
    {
        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
