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

        $this->insert('{{%setting}}', [
            'key_setting' => 'fullname',
            'key_value' => 'Nama Lengkap'
        ]);
        $this->insert('{{%setting}}', [
            'key_setting' => 'jabatan',
            'key_value' => 'Jabatan Guru'
        ]);
        $this->insert('{{%setting}}', [
            'key_setting' => 'kepala_madrasah',
            'key_value' => 'Nama Kepala Madrasah'
        ]);
        $this->insert('{{%setting}}', [
            'key_setting' => 'nip',
            'key_value' => 'NIP Guru'
        ]);
        $this->insert('{{%setting}}', [
            'key_setting' => 'nip_kepala_madrasah',
            'key_value' => 'NIP Kepala Madrasah'
        ]);
        $this->insert('{{%setting}}', [
            'key_setting' => 'unit_kerja',
            'key_value' => 'Unit Kerja'
        ]);
        $this->insert('{{%setting}}', [
            'key_setting' => 'unit_organisasi',
            'key_value' => 'Unit Organisasi'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%setting}}');
    }
}
