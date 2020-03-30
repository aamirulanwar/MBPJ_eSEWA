<style>
    th{
        text-align: center;
    }
</style>
 <form method="post" action="/report/hartnah/">
    <div class="card card-accent-info">
        <div class="card-body">
            <h1 class="need-print" style="margin-bottom: 20px;"><?php echo $pagetitle?></h1>

<?php
notify_msg('notify_msg');
checking_validation(validation_errors());
?>

    <div class="card card-accent-info">
        <div class="card-header">
            <h3 class="box-title">Laporan Perjanian Sewaan Hartanah</h3>
        </div>
            <div class="card-body">
                <!-- <div class="pull-right"> -->
                        <table class="table table-hover table-bordered table-aging" style="margin-bottom: 0px;">
                            <tr>
                                <th width="3%" rowspan="2" style="text-align:center">BULAN</th>
                                <th colspan="5" width="7%" style="text-align:center">JENIS HARTANAH</th>
                                <th width="7%" rowspan="2" style="text-align:center">JUMLAH</th>
                            </tr>
                            <tr>
                                <th width="7%">PLB</th>
                                <th width="7%">PASAR</th>
                                <th width="7%">MEDAN SELERA</th>
                                <th width="7%">UPEN</th>
                                <th width="7%">KIOSK MIKRO-MARA</th>
                            </tr>
                            <?php
                            $cnt = 0;
                            foreach ($data as $d):
                            $cnt=$cnt+1;
                            ?>
                            <tr>
                                <td width="3%" class="text-right"><?php echo $d['BULAN']?>.</td>
                                <td width="3%" class="text-right">
                                    <?php if($d['TYPE_ID']==3): ?>
                                    <?php echo $d['BIL']?>
                                    <?php endif; ?>
                                </td>
                                <td width="3%" class="text-right">
                                    <?php if($d['TYPE_ID']==1): ?>
                                    <?php echo $d['BIL']?>
                                    <?php endif; ?>
                                </td>
                                <td width="3%" class="text-right">
                                    <?php if($d['TYPE_ID']==2): ?>
                                    <?php echo $d['BIL']?>
                                    <?php endif; ?>
                                </td>
                                <td width="3%" class="text-right">
                                    <?php if($d['TYPE_ID']==4): ?>
                                    <?php echo $d['BIL']?>
                                    <?php endif; ?>
                                </td>
                                <td width="3%" class="text-right">
                                    <?php if($d['TYPE_ID']==5): ?>
                                    <?php echo $d['BIL']?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php
                            endforeach;
                            ?>
                        </table>
                    </div>
                <!-- </div> -->
            </div>
    </div>
