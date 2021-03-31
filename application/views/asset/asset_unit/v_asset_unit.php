<?php
notify_msg('user');
?>
<div class="card card-accent-info">
    <div class="card-header">
        <div class="pull-left">
            <strong>Jumlah Rekod : <?php echo $total_result?></strong>
        </div>
        <?php if($this->auth->access_view($this->curuser,array(3014))):?>
        <div class="pull-right">
            <a href="/asset/add_asset_unit" class="add-new"><i style="color: green" class="fa fa-plus-circle fa-lg"></i> Tambah</a>
        </div>
        <?php endif;?>
    </div>
    <div class="card-body">
        <form class="form-horizontal" action="/asset/asset_unit" method="post">
            <div class="col-lg-4 row" style="margin-top: 20px;margin-bottom: 20px;">
                <div class="input-group">
                    <input type="text" name="search" value="<?php echo search_default($data_search,'search');?>" class="form-control" placeholder="Jenis Kategori">
                    <span class="input-group-btn">
                    <button class="btn btn-success btn-square" type="submit">Carian</button>
                </span>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <tr>
                    <th>No.</th>
                    <th>Kawasan Aset</th>
                    <th>Kod Seksyen</th>
                    <th>Harga Sewaan</th>
                    <th>Status</th>
                    <th>Status Sewaan</th>
                    <th class="text-center">Tindakan</th>
                </tr>
                <tbody>
                <?php
                if($data_list):
                    $i = (uri_segment(3)=='')?0:uri_segment(3);
                    foreach($data_list as $row):
                        $i=$i+1;
                        ?>
                        <tr>
                            <td><?php echo $i?></td>
                            <td><?php echo $row['ASSET_NAME']?></td>
                            <td><?php echo '<strong>'.$row['CATEGORY_CODE'].'</strong> - '.$row['CATEGORY_NAME']?></td>
                            <td>RM <?=num($row['RENTAL_FEE'],2)?></td>
                            <td>
                                <?php
                                get_status_active($row['ACTIVE']);
                                ?>
                            </td>
                            <td>
                                <?php
                                get_status_rental($row['RENTAL_STATUS']);
                                ?>
                            </td>
                            <td class="text-center">
                                <?php if($this->auth->access_view($this->curuser,array(3015))):?>
                                <a class="btn btn-primary btn-display" href="/asset/edit_asset_unit/<?php echo urlEncrypt($row['ASSET_ID'])?>"><span class="glyphicon glyphicon-edit"></span> Kemaskini </a>
                                <?php endif;?>
                                <?php if($this->auth->access_view($this->curuser,array(3016))):?>
                                <button class="btn btn-danger btn-display" onclick="delete_modal('/asset/delete_asset_unit/','<?php echo $row['ASSET_ID']?>')"><span class="glyphicon glyphicon-remove"></span> Padam </button>
                                <?php endif;?>
                            </td>
                        </tr>
                    <?php
                    endforeach;
                else:
                    echo '<tr><td colspan="10" class="text-center"> - Tiada Rekod - </td></tr>';
                endif;
                ?>
                </tbody>
            </table>
        </div>
        <div class="pull-right">
            <?php echo paging_link(); ?>
        </div>
    </div>
</div>