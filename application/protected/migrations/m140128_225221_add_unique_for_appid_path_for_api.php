<?php

class m140128_225221_add_unique_for_appid_path_for_api extends CDbMigration
{

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
        $this->createIndex('idx_application_id_path', '{{api}}', 'application_id,path', true);
    }

    public function safeDown()
    {
        $this->dropIndex('idx_application_id_path', '{{api}}');
    }

}
