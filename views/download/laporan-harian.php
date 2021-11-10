<table class="table table-sm">
    <thead>
        <tr>
            <th style="width: 150px;">Tanggal</th>
            <th>Data</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $key => $val): ?>
        <tr>
            <td><?=$key?></td>
            <td>
                <table style="width: 100%;" class="table table-striped table-bordered">
                    <tr>
                        <th>No</th>
                        <th>Kegiatan</th>
                        <th>Output</th>
                        <th>Volume</th>
                        <th>Satuan</th>
                        <th>Ket.</th>
                    </tr>
                    <?php foreach ($val as $key => $d): ?>
                    <tr>
                        <td><?=$key+1?></td>
                        <td><?=$d->kegiatan?></td>
                        <td>&nbsp;</td>
                        <td><?=$d->volume?></td>
                        <td><?=$d->satuan?></td>
                        <td>&nbsp;</td>
                    </tr>
                    <?php $key++; endforeach; ?>
                </table>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>