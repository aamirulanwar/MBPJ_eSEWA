<?php
notify_msg('user');
?>
<div class="card card-accent-info">
    <div class="card-header">
        <div class="pull-left">
            <strong>Jumlah Rekod : <?php echo $total_result?></strong>
        </div>
        <?php if($this->auth->access_view($this->curuser,array(3006))):?>
        <div class="pull-right">
            <a href="/asset/add_category" class="add-new"><i style="color: green" class="fa fa-plus-circle fa-lg"></i> Tambah</a>
        </div>
        <?php endif;?>
    </div>
    <div class="card-body">
        <form class="form-horizontal" action="/asset/category" method="post">
            <div class="col-lg-4 row" style="margin-top: 20px;margin-bottom: 20px;">
                <div class="input-group">
                    <input type="text" name="type_name" value="<?php echo search_default($data_search,'type_name');?>" class="form-control" placeholder="Jenis Harta">
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
                    <th>Kod / Nama kategori</th>
                    <th>Jumlah kekosongan / Jumlah unit</th>
                    <th>Nilai Harta (RM)</th>
                    <th>Jenis</th>
                    <th>Status / Catatan</th>
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
                            <td><?php echo '<strong>'.$row['CATEGORY_CODE'].'</strong>' .' - '. $row['CATEGORY_NAME']?></td>
                            <td><?php echo $row['TOTAL_AVAILABLE_UNIT'] .'/'. $row['TOTAL_UNIT']?></td>
                            <td>
                                <?php echo 'Nilai&nbsp;semasa&nbsp;:'.num($row['CURRENT_VALUE']) ?><br>
                                <?php echo 'Harga&nbsp;sewaan&nbsp;:'.num($row['RENTAL_FEE_DEFAULT']) ?>
                            </td>
                            <td>
                                <?php echo $row['TYPE_NAME'] ?><br>
                            </td>
                            <td>
                                <?php
                                    get_status_active($row['ACTIVE']);
                                ?>
                                <?php
                                    if(!empty($row['REMARK'])):
                                        echo '<br><strong>Catatan&nbsp;:</strong> '.$row['REMARK'];
                                    endif;
                                ?>
                            </td>
                            <td class="text-center">
                                <?php if($this->auth->access_view($this->curuser,array(3007))):?>
                                <a class="btn btn-block btn-primary btn-display" href="/asset/edit_category/<?php echo urlEncrypt($row['CATEGORY_ID'])?>"><span class="glyphicon glyphicon-edit"></span> Kemaskini </a>
                                <?php endif;?>
                                <?php if($this->auth->access_view($this->curuser,array(3008))):?>
                                <button class="btn btn-block btn-danger btn-display" onclick="delete_modal('/asset/delete_asset_category/','<?php echo $row['CATEGORY_ID']?>')"><span class="glyphicon glyphicon-remove"></span> Padam </button>
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