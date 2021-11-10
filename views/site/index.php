<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */

$this->title = 'SIEKA REPORT GENERATOR';
?>
<div class="site-index">
    <div class="row justify-content-between">
        <div class="col">
            <?php $form = ActiveForm::begin([
                'options' => ['enctype' => 'multipart/form-data']
            ]); ?>
            <?=$form->field($uploadForm, 'file_sieka')->fileInput([
                'accept' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ])->label(false)?>
            <div class="form-group">
                <?= Html::submitButton('Upload', ['class' => 'btn btn-success', 'id' => 'btn-kirim']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
        <div class="col text-right">
            <div class="btn-group" role="group" aria-label="Download Button">
                <?= Html::a('<i class="fas fa-sync-alt"></i> Reset Data', ['reset'], [
                    'class' => 'btn btn-warning',
                    'data' => [
                        'confirm' => 'Yakin ingin menghapus semua data?',
                        'method' => 'post'
                    ]
                ]) ?>
                <?= Html::a('<i class="fas fa-download"></i> Laporan Bulanan', ['/download/laporan-bulanan'], ['class' => 'btn btn-info']) ?>
                <?= Html::a('<i class="fas fa-download"></i> Laporan Harian', ['/download/laporan-harian'], ['class' => 'btn btn-primary']) ?>
            </div>
            
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'pager' => [
                    'class' => \yii\bootstrap4\LinkPager::class
                ],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'attribute' => 'tanggal',
                        'contentOptions' => ['style' => 'width: 125px']
                    ],
                    'kegiatan',
                    'volume',
                    'satuan'
                ],
            ]); ?>
        </div>
    </div>
</div>

<?php

// $quantitas = '4 jam pelajaran';
// // preg_match_all('!\d+!', $quantitas, $volume);
// $volume = filter_var($quantitas, FILTER_SANITIZE_NUMBER_INT);
// echo '<pre>';
// print_r($volume);
// echo '</pre>';
// // $satuan = preg_split("/[0-9]+/", "4 jam pelajaran");
// $satuan = ucwords(ltrim($quantitas, '0..9 '));
// echo '<pre>';
// print_r($satuan);
// echo '</pre>';

// $str = '01 Sep 2021';
// echo '<pre>';
// $date = date('Y-m-d', strtotime($str));
// print_r($date);
// echo '</pre>';