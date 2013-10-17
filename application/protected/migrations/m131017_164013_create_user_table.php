<?php

class m131017_164013_create_user_table extends CDbMigration
{
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
            $this->createTable('{{user}}', array(
                'id' => 'char(32) null',
                'name' => 'varchar(64) not null',
                'email' => 'varchar(128) not null',
                'access_token' => 'char(40) null',
                'created' => 'datetime null',
                'last_login' => 'datetime null',
                'role' => 'varchar(16) not null',
                'status' => 'tinyint(1) not null',
            ), 'ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci');
            $this->addPrimaryKey('pk_user', '{{user}}', 'id');
            $this->createIndex('idx_user_email', '{{user}}', 'email', true);
	}

	public function safeDown()
	{
            $this->dropTable('{{user}}');
	}
}