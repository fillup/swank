<?php

class m131018_012030_create_api_parameter_table extends CDbMigration
{
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
            $this->createTable('{{api_parameter}}', array(
                'id' => 'char(32) null',
                'operation_id' => 'char(32) not null',
                'paramType' => 'varchar(8) not null',
                'name' => 'varchar(32) not null',
                'description' => 'varchar(255) null',
                'dataType' => 'varchar(32) not null',
                'format' => 'varchar(32) null',
                'required' => 'tinyint(1) not null',
                'minimum' => 'varchar(32) null',
                'maximum' => 'varchar(32) null',
                'enum' => 'varchar(64) null',
                'created' => 'datetime null',
                'updated' => 'datetime null',
            ), 'ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci');
            $this->addPrimaryKey('pk_api_parameter', '{{api_parameter}}', 'id');
            $this->addForeignKey('fk_parameter_operation_id', '{{api_parameter}}', 'operation_id', '{{api_operation}}', 'id', 'NO ACTION', 'NO ACTION');
            $this->createIndex('idx_operation_parameter', '{{api_parameter}}', 'operation_id,name', true);
	}

	public function safeDown()
	{
            $this->dropTable('{{api_parameter}}');
	}
}