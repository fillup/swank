<?php

class m140510_010217_add_application_visibility extends CDbMigration
{

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
        $this->addColumn('{{application}}', 'visibility', 'varchar(16) not null');
    }

    public function safeDown()
    {
        $this->dropColumn('{{application}}', 'visibility');
    }

}
