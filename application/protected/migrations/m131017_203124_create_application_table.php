<?php

class m131017_203124_create_application_table extends CDbMigration
{
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
            $this->createTable('{{application}}', array(
                'id' => 'char(32) null',
                'user_id' => 'char(32) not null',
                'name' => 'varchar(64) not null',
                'description' => 'varchar(255) null',
                'base_path' => 'varchar(255) null',
                'resource_path' => 'varchar(64) null',
                'api_version' => 'varchar(16) null',
                'created' => 'datetime null',
                'updated' => 'datetime null',
            ), 'ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci');
            $this->addPrimaryKey('pk_application', '{{application}}', 'id');
            $this->addForeignKey('fk_application_user', '{{application}}', 'user_id', '{{user}}', 'id', 'NO ACTION', 'NO ACTION');
	}

	public function safeDown()
	{
            $this->dropTable('{{application}}');
	}
}