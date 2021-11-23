<?php

namespace app\controllers;

use app\models\Kegiatan;
use Yii;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use app\helpers\Formatter;

class DownloadController extends \yii\web\Controller
{
    private $_periode;

    public function __construct($id, $module, $config = [])
    {
        $periode = Kegiatan::find()->one();

        if ($periode) {
            $this->_periode = Formatter::asIndonesianMonth($periode->tanggal);
        }

        parent::__construct($id, $module, $config);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLaporanBulanan()
    {
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();

        $filename = 'Laporan Bulanan ' . $this->_periode;

        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('Khairil')
            ->setLastModifiedBy('Khairil')
            ->setTitle($filename)
            ->setSubject($filename)
            ->setDescription('Sieka Report for Office 2007 XLSX, generated using PHP classes.')
            ->setKeywords('office 2007 openxml php')
            ->setCategory('Report File');
        
        // Format Header Cell
        $spreadsheet->setActiveSheetIndex(0)->mergeCells('A1:E1');
        $spreadsheet->setActiveSheetIndex(0)->mergeCells('A2:E2');
        $spreadsheet->setActiveSheetIndex(0)->mergeCells('A3:E3');
        $spreadsheet->setActiveSheetIndex(0)->mergeCells('A4:E4');
        $spreadsheet->setActiveSheetIndex(0)->mergeCells('A6:B6');
        $spreadsheet->setActiveSheetIndex(0)->mergeCells('A7:B7');
        $spreadsheet->setActiveSheetIndex(0)->mergeCells('A8:B8');
        $spreadsheet->setActiveSheetIndex(0)->mergeCells('A9:B9');

        $sheet = $spreadsheet->getActiveSheet();

        $styleHeader = [
            'font' => [
                'bold' => true
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];
        // Set style for header
        $sheet->getStyle('A1:E4')->applyFromArray($styleHeader);

        // Add some data
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'CAPAIAN KINERJA HARIAN')
            ->setCellValue('A2', 'PEGAWAI NEGERI SIPIL (PNS KEMENTERIAN AGAMA)')
            ->setCellValue('A3', 'KABUPATEN BIMA')
            ->setCellValue('A4', 'Bulan ' . $this->_periode);
        
        // Style Identitas
        $styleIdentitas = [
            'font' => [
                'bold' => true
            ]
        ];
        // Set style for header
        $sheet->getStyle('A6:C9')->applyFromArray($styleIdentitas);

        // Identitas Yang Dinilai
        $spreadsheet->setActiveSheetIndex(0)    
            ->setCellValue('A6', 'NAMA')
            ->setCellValue('A7', 'JABATAN')
            ->setCellValue('A8', 'UNIT ORGANISASI')
            ->setCellValue('A9', 'UNIT KERJA');
        
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('C6', ': ' . Yii::$app->setting->getFullname())
            ->setCellValue('C7', ': ' . Yii::$app->setting->getJabatan())
            ->setCellValue('C8', ': ' . Yii::$app->setting->getUnitOrganisasi())
            ->setCellValue('C9', ': ' . Yii::$app->setting->getUnitKerja());

        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A11', 'No.')
            ->setCellValue('B11', 'Tanggal')
            ->setCellValue('C11', 'Kegiatan')
            ->setCellValue('D11', 'Volume/Satuan')
            ->setCellValue('E11', 'KET');

        $base = 12;
        $datas = Kegiatan::find()->all();

        if (empty($datas)) {
            Yii::$app->session->setFlash('warning', 'Data SIEKA harap diunggah terlebih dahulu');
            return $this->goHome();
        }

        // Bikin row sebanyak jumlah kegiatan
        $sheet->insertNewRowBefore($base, count($datas));

        // Insert data
        foreach ($datas as $key => $data) {
            $sheet->setCellValue('A'.$base, $key+1);
            $sheet->setCellValue('B'.$base, $data->tanggal);
            $sheet->setCellValue('C'.$base, $data->kegiatan);
            $sheet->setCellValue('D'.$base, $data->volume . ' ' . $data->satuan);
            $base++;
        }

        $spreadsheet->getActiveSheet()
            ->getStyle('A11:E11')
            ->getFont()
            ->setBold(true);

        $spreadsheet->getActiveSheet()
            ->getStyle('A11:E11')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $spreadsheet->getActiveSheet()
            ->getStyle('A11:E' . intval($base-1))
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)
            ->setColor(new Color('00000000'));

        // Mengetahui
        $spreadsheet->setActiveSheetIndex(0)
            ->mergeCells('A'.intval($base+2).':C'.intval($base+2));
        $spreadsheet->setActiveSheetIndex(0)
            ->mergeCells('A'.intval($base+3).':C'.intval($base+3));
        $spreadsheet->setActiveSheetIndex(0)
            ->mergeCells('A'.intval($base+8).':C'.intval($base+8));
        $spreadsheet->setActiveSheetIndex(0)
            ->mergeCells('A'.intval($base+9).':C'.intval($base+9));

        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A'.intval($base+2), 'Mengetahui')
            ->setCellValue('A'.intval($base+3), 'Kepala Madrasah')
            ->setCellValue('A'.intval($base+8), Yii::$app->setting->getKepalaMadrasah());
        $spreadsheet->setActiveSheetIndex(0)    
            ->setCellValue('A'.intval($base+9), 'NIP. ' . Yii::$app->setting->getNipKepalaMadrasah());

        // Yang Dinilai
        $spreadsheet->setActiveSheetIndex(0)
            ->mergeCells('D'.intval($base+1).':E'.intval($base+1));
        $spreadsheet->setActiveSheetIndex(0)
            ->mergeCells('D'.intval($base+3).':E'.intval($base+3));
        $spreadsheet->setActiveSheetIndex(0)
            ->mergeCells('D'.intval($base+8).':E'.intval($base+8));
        $spreadsheet->setActiveSheetIndex(0)
            ->mergeCells('D'.intval($base+9).':E'.intval($base+9));

        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('D'.intval($base+1), 'Rato-Bolo, ' . Formatter::asIndonesianDate(date('Y-m-d')))
            ->setCellValue('D'.intval($base+3), 'Guru Yang Dinilai,')
            ->setCellValue('D'.intval($base+8), Yii::$app->setting->getFullname())
            ->setCellValue('D'.intval($base+9), 'NIP. ' . Yii::$app->setting->getNip());

        foreach ($sheet->getColumnIterator() as $column) {
            $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }

        // Rename worksheet
        $sheet->setTitle('Bulanan');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    
    public function actionLaporanHarian()
    {
        $kegiatan = Kegiatan::find()
            ->select(['tanggal', 'kegiatan', 'volume', 'satuan'])
            ->orderBy(['tanggal' => 'ASC'])
            ->all();

        if (empty($kegiatan)) {
            Yii::$app->session->setFlash('warning', 'Data SIEKA harap diunggah terlebih dahulu');
            return $this->goHome();
        }

        $data = [];
        foreach ($kegiatan as $val) {
            $data[$val->tanggal][] = $val;
        }

        // Start Create Excel
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();

        $filename = 'Laporan Harian ' . $this->_periode;

        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('Khairil')
            ->setLastModifiedBy('Khairil')
            ->setTitle($filename)
            ->setSubject($filename)
            ->setDescription('Sieka Report for Office 2007 XLSX, generated using PHP classes.')
            ->setKeywords('office 2007 openxml php')
            ->setCategory('Report File');

        $i = 0;
        foreach ($data as $key => $val) {
            $worksheet = $spreadsheet->createSheet($i);
            $worksheet->setTitle($key);

            $sheet = $spreadsheet->setActiveSheetIndex($i);

            $sheet->mergeCells('A1:H1');
            $sheet->mergeCells('A2:H2');
            $sheet->mergeCells('A3:H3');
            
            $sheet->mergeCells('A5:B5');
            $sheet->mergeCells('A6:B6');
            $sheet->mergeCells('A7:B7');
            $sheet->mergeCells('A8:B8');
            $sheet->mergeCells('A9:B9');


            $styleHeader = [
                'font' => [
                    'bold' => true
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ];

            $sheet->getStyle('A1:H3')->applyFromArray($styleHeader);
            
            $sheet->setCellValue('A1', 'LAPORAN CAPAIAN KINERJA HARIAN')
                ->setCellValue('A2', 'PEGAWAI NEGERI SIPIL (PNS KEMENTERIAN AGAMA)')
                ->setCellValue('A3', 'KABUPATEN BIMA');

            // Identitas Yang Dinilai
            $sheet->setCellValue('A5', 'NAMA')
                ->setCellValue('A6', 'JABATAN')
                ->setCellValue('A7', 'UNIT ORGANISASI')
                ->setCellValue('A8', 'UNIT KERJA')
                ->setCellValue('A9', 'HARI/TANGGAL');
            
            $sheet->setCellValue('C5', ':')
                ->setCellValue('C6', ':')
                ->setCellValue('C7', ':')
                ->setCellValue('C8', ':')
                ->setCellValue('C9', ':');

            $spreadsheet->getActiveSheet()
                ->getColumnDimension('C')->setAutoSize(true);
            
            $sheet->setCellValue('D5', Yii::$app->setting->getFullname())
                ->setCellValue('D6', Yii::$app->setting->getJabatan())
                ->setCellValue('D7', Yii::$app->setting->getUnitOrganisasi())
                ->setCellValue('D8', Yii::$app->setting->getUnitKerja())
                ->setCellValue('D9', Formatter::asIndonesianDateWithDay($key));

            $sheet->mergeCells('B11:D11');
            
            $sheet->setCellValue('A11', 'NO.')
                ->setCellValue('B11', 'KEGIATAN')
                ->setCellValue('E11', 'OUTPUT')
                ->setCellValue('F11', 'VOLUME')
                ->setCellValue('G11', 'SATUAN')
                ->setCellValue('H11', 'KET');

            $base = 12;

            // Bikin row sebanyak jumlah kegiatan
            $sheet->insertNewRowBefore($base, count($val)+2);

            // Add row "Presensi Kehadiran"
            $j = 1;
            $sheet->setCellValue('A'.$base, $j);
            $sheet->mergeCells('B'.$base.':D'.$base);
            $sheet->setCellValue('B'.$base, 'Presensi Kehadiran');
            $sheet->setCellValue('E'.$base, null);
            $sheet->setCellValue('F'.$base, null);
            $sheet->setCellValue('G'.$base, null);
            $sheet->setCellValue('H'.$base, null);

            // Insert data
            foreach ($val as $x => $data) {
                $j = $x+2;
                $sheet->setCellValue('A'.intval($base+1), $j);
                $sheet->mergeCells('B'.intval($base+1).':D'.intval($base+1));
                $sheet->setCellValue('B'.intval($base+1), $data->kegiatan);
                $sheet->setCellValue('E'.intval($base+1), null);
                $sheet->setCellValue('F'.intval($base+1), $data->volume);
                $sheet->setCellValue('G'.intval($base+1), $data->satuan);
                $sheet->setCellValue('H'.intval($base+1), null);
                $base++;
            }

            // Add row "Presensi Pulang"
            $sheet->setCellValue('A'.intval($base+1), $j+1);
            $sheet->mergeCells('B'.intval($base+1).':D'.intval($base+1));
            $sheet->setCellValue('B'.intval($base+1), 'Presensi Pulang');
            $sheet->setCellValue('E'.intval($base+1), null);
            $sheet->setCellValue('F'.intval($base+1), null);
            $sheet->setCellValue('G'.intval($base+1), null);
            $sheet->setCellValue('H'.intval($base+1), null);

            $spreadsheet->getActiveSheet()
                ->getStyle('A11:H11')
                ->getFont()
                ->setBold(true);
            $spreadsheet->getActiveSheet()
                ->getStyle('A11:H11')
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            // Apply border
            $spreadsheet->getActiveSheet()
                ->getStyle('A11:H' . intval($base+1))
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN)
                ->setColor(new Color('00000000'));
            
            // Mengetahui
            $sheet->mergeCells('A'.intval($base+4).':D'.intval($base+4));
            $sheet->mergeCells('A'.intval($base+10).':D'.intval($base+10));
            $sheet->mergeCells('A'.intval($base+11).':D'.intval($base+11));

            $sheet->setCellValue('A'.intval($base+4), 'Pejabat penilai/Atasan Langsung')
                ->setCellValue('A'.intval($base+10), Yii::$app->setting->getKepalaMadrasah());
            $sheet->setCellValue('A'.intval($base+11), 'NIP. ' . Yii::$app->setting->getNipKepalaMadrasah());

            $spreadsheet->getActiveSheet()
                ->getStyle('A'.intval($base+10))
                ->getFont()
                ->setBold(true);
            $spreadsheet->getActiveSheet()
                ->getStyle('A'.intval($base+11))
                ->getFont()
                ->setBold(true);

            // Yang Dinilai
            $sheet->mergeCells('F'.intval($base+4).':H'.intval($base+4));
            $sheet->mergeCells('F'.intval($base+10).':H'.intval($base+10));
            $sheet->mergeCells('F'.intval($base+11).':H'.intval($base+11));

            $sheet->setCellValue('F'.intval($base+4), 'Yang dinilai, ')
                ->setCellValue('F'.intval($base+10), Yii::$app->setting->getFullname())
                ->setCellValue('F'.intval($base+11), 'NIP. ' .Yii::$app->setting->getNip());

            $spreadsheet->getActiveSheet()
                ->getStyle('F'.intval($base+10))
                ->getFont()
                ->setBold(true);
            $spreadsheet->getActiveSheet()
                ->getStyle('F'.intval($base+11))
                ->getFont()
                ->setBold(true);

            $spreadsheet->getActiveSheet()
                ->getColumnDimension('A')
                ->setAutoSize(true);
            $spreadsheet->getActiveSheet()
                ->getColumnDimension('B')
                ->setAutoSize(true);
            $spreadsheet->getActiveSheet()
                ->getColumnDimension('C')
                ->setAutoSize(true);
            $spreadsheet->getActiveSheet()
                ->getColumnDimension('D')
                ->setAutoSize(true);

            $i++;
        }

        $spreadsheet->setActiveSheetIndex(0);

        // Download Excel File
        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

}
