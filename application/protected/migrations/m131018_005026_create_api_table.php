<?php

class m131018_005026_create_api_table extends CDbMigration
{
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
            $this->createTable('{{api}}', array(
                'id' => 'char(32) null',
                'application_id' => 'char(32) not null',
                'path' => 'varchar(64) not null',
                'description' => 'varchar(255) null',
                'created' => 'datetime null',
                'updated' => 'datetime null',
            ), 'ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci');
            $this->addPrimaryKey('pk_api', '{{api}}', 'id');
            $this->addForeignKey('fk_application_id', '{{api}}', 'application_id', '{{application}}', 'id', 'NO ACTION', 'NO ACTION');
	}

	public function safeDown()
	{
            $this->dropTable('{{api}}');
	}
}