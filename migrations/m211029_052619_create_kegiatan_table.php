<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%kegiatan}}`.
 */
class m211029_052619_create_kegiatan_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%kegiatan}}', [
            'id' => $this->primaryKey(),
            'tanggal' => $this->date()->notNull(),
            'kegiatan' => $this->string(255)->notNull(),
            'volume' => $this->integer()->notNull(),
            'satuan' => $this->string(50)->notNull(),
            'status' => $this->string(10)->notNull()->defaultValue('baru')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%kegiatan}}');
    }
}
