<?php

class m131018_005259_create_api_operation_table extends CDbMigration
{
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
            $this->createTable('{{api_operation}}', array(
                'id' => 'char(32) null',
                'api_id' => 'char(32) not null',
                'method' => 'varchar(8) not null',
                'nickname' => 'varchar(32) not null',
                'type' => 'varchar(32) not null',
                'summary' => 'varchar(255) null',
                'notes' => 'varchar(2048) null',
                'created' => 'datetime null',
                'updated' => 'datetime null',
            ), 'ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci');
            $this->addPrimaryKey('pk_api_operation', '{{api_operation}}', 'id');
            $this->addForeignKey('fk_operation_api_id', '{{api_operation}}', 'api_id', '{{api}}', 'id', 'NO ACTION', 'NO ACTION');
            $this->createIndex('idx_operation_api_method', '{{api_operation}}', 'api_id,method', true);
	}

	public function safeDown()
	{
            $this->dropTable('{{api_operation}}');
	}
}