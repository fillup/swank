<?php

class m131018_013401_create_api_response_table extends CDbMigration
{
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
            $this->createTable('{{api_response}}', array(
                'id' => 'char(32) null',
                'operation_id' => 'char(32) not null',
                'code' => 'integer(3) not null',
                'message' => 'varchar(255) not null',
                'responseModel' => 'varchar(34) null',
                'created' => 'datetime null',
                'updated' => 'datetime null',
            ), 'ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci');
            $this->addPrimaryKey('pk_api_response', '{{api_response}}', 'id');
            $this->addForeignKey('fk_response_operation_id', '{{api_response}}', 'operation_id', '{{api_operation}}', 'id', 'NO ACTION', 'NO ACTION');
	}

	public function safeDown()
	{
            $this->dropTable('{{api_response}}');
	}
}