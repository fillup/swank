<?php

class m131126_021412_add_unique_index_to_api_token extends CDbMigration
{

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
        $this->createIndex('idx_api_token', '{{user}}', 'api_token', true);
    }

    public function safeDown()
    {
        $this->dropIndex('idx_api_token', '{{user}}');
    }

}
