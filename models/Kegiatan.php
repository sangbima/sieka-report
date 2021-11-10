<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kegiatan".
 *
 * @property int $id
 * @property string $tanggal
 * @property string $kegiatan
 * @property int $volume
 * @property string $satuan
 * @property string $status
 */
class Kegiatan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kegiatan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tanggal', 'kegiatan', 'volume', 'satuan'], 'required'],
            [['tanggal'], 'safe'],
            [['volume'], 'integer'],
            [['kegiatan'], 'string', 'max' => 255],
            [['satuan'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tanggal' => 'Tanggal',
            'kegiatan' => 'Kegiatan',
            'volume' => 'Volume',
            'satuan' => 'Satuan',
            'status' => 'Status',
        ];
    }
}
