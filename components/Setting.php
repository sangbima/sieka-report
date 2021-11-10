<?php
namespace app\components;

use app\models\Setting as Pengaturan;

class Setting
{
    public function getFullname()
    {
        $data = Pengaturan::findOne(['key_setting' => 'fullname']);
        return $data->key_value;
    }

    public function getNip()
    {
        $data = Pengaturan::findOne(['key_setting' => 'nip']);
        return $data->key_value;
    }

    public function getJabatan()
    {
        $data = Pengaturan::findOne(['key_setting' => 'jabatan']);
        return $data->key_value;
    }

    public function getUnitOrganisasi()
    {
        $data = Pengaturan::findOne(['key_setting' => 'unit_organisasi']);
        return $data->key_value;
    }

    public function getUnitKerja()
    {
        $data = Pengaturan::findOne(['key_setting' => 'unit_kerja']);
        return $data->key_value;
    }

    public function getKepalaMadrasah()
    {
        $data = Pengaturan::findOne(['key_setting' => 'kepala_madrasah']);
        return $data->key_value;
    }

    public function getNipKepalaMadrasah()
    {
        $data = Pengaturan::findOne(['key_setting' => 'nip_kepala_madrasah']);
        return $data->key_value;
    }
}