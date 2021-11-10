<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "setting".
 *
 * @property string $key_setting
 * @property string $key_value
 */
class Setting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'setting';
    }

    public function beforeSave($insert)
    {
        $this->key_setting = strtolower(str_replace(' ', '_', $this->key_setting));
        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['key_setting', 'key_value'], 'required'],
            [['key_setting'], 'string', 'max' => 25],
            [['key_value'], 'string', 'max' => 255],
            [['key_setting'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'key_setting' => 'Key Setting',
            'key_value' => 'Key Value',
        ];
    }
}
