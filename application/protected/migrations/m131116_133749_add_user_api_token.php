<?php

class m131116_133749_add_user_api_token extends CDbMigration
{

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
        $this->addColumn('{{user}}', 'api_token', 'char(32) null');
    }

    public function safeDown()
    {
        $this->dropColumn('{{user}}', 'api_token');
    }

}
