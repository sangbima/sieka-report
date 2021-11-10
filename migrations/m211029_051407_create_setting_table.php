<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%setting}}`.
 */
class m211029_051407_create_setting_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%setting}}', [
            'key_setting' => $this->string(25)->notNull(),
            'key_value' => $this->string(255)->notNull()
        ]);

        $this->addPrimaryKey('PK-settings-key_setting', '{{%setting}}', 'key_setting');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%setting}}');
    }
}
