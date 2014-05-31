<?php

class m140522_212433_add_authorization_option_to_application extends CDbMigration
{
    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
        $this->addColumn('{{application}}','authorization_type','varchar(16) null');
        $this->addColumn('{{application}}','authorization_config','text null');
    }

    public function safeDown()
    {
        $this->dropColumn('{{application}}','authorization_type');
        $this->dropColumn('{{application}}','authorization_config');
    }
}