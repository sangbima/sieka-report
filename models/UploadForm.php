<?php

namespace app\models;

use Yii;
use yii\base\Model;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderExcel;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user This property is read-only.
 *
 */
class UploadForm extends Model
{
    public $file_sieka;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['file_sieka'], 'file'],
            [['file_sieka'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xlsx']
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $filename = time() . '-' . Yii::$app->security->generateRandomString() . '.' . $this->file_sieka->extension;
            $this->file_sieka->saveAs('@app/uploads/' . $filename);

            // Ekstract file excel
            $file = Yii::getAlias("@app", $throwException = true) . "/uploads/" . $filename;
            $reader = new ReaderExcel();
            
            $spreadsheet = $reader->load($file);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            $baseRow = 1;
            while (!empty($sheetData[$baseRow])) {
                $quantitas = $sheetData[$baseRow][2];
                $volume = filter_var($quantitas, FILTER_SANITIZE_NUMBER_INT);
                $satuan = ucwords(ltrim($quantitas, '0..9 '));

                $model = new \app\models\Kegiatan();
                $model->tanggal = date('Y-m-d', strtotime($sheetData[$baseRow][0]));
                $model->kegiatan = $sheetData[$baseRow][1];
                $model->volume = $volume;
                $model->satuan = $satuan;
                $model->save();
                $baseRow++;
            }
            unlink($file);
            return true;
        }

        return false;
    }
}
