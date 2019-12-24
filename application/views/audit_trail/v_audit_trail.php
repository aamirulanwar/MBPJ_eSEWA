<?php
    notify_msg('user');
?>
<form method="post" action="/audit_trail" class="no-need-print">
    <div class="card card-accent-info">
        <div class="card-body">
            <div class="form-group row">
                <div class="col-sm-4">
                    <label class="col-form-label">Pengguna</label>
                    <select name="user_id" id="user_id" class="form-control">
                        <option value=""> - Semua - </option>
                        <?php
                            foreach ($data_user as $user):
                                echo option_value($user['USER_ID'],$user['USER_NAME'],'user_id');
                            endforeach;
                        ?>
                    </select>
                </div>
                <div class="col-sm-4">
                    <label class="col-form-label">Tarikh dari</label>
                    <input type="input" name="date_start" id="date_start" class="form-control date_class" placeholder="Tarikh mula" value="<?php echo set_value('date_start',$data_search['date_start'])?>">
					<label><a href="javascript:;" onclick="document.getElementById('date_start').value=''">Kosongkan tarikh mula</a></label>
				</div>
                <div class="col-sm-4">
                    <label class="col-form-label">Tarikh hingga</label>
                    <input type="input" name="date_end" id="date_end" class="form-control date_class" placeholder="Tarikh tamat" value="<?php echo set_value('date_end',$data_search['date_end'])?>">
					<label><a href="javascript:;" onclick="document.getElementById('date_end').value=''">Kosongkan tarikh tamat</a></label>
				</div>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary pull-right btn-submit">Cari</button>
            </div>
        </div>
    </div>
</form>

<div class="card card-accent-info">
    <div class="card-header">
        <div class="pull-left">
            <strong>Jumlah Rekod : <?php echo $total_result?></strong>
        </div>
        <div class="pull-right">
            <button onclick="window.print()" class="btn btn-warning btn-sm pull-right">Print</button>
        </div>
        <!--        --><?php //if($this->auth->access_view($this->curuser,array(2018))):?>
<!--        <div class="pull-right">-->
<!--            <a href="/user/add_user/" class="add-new"><span style="color: green" class="glyphicon glyphicon-plus-sign"></span> Tambah</a>-->
<!--        </div>-->
        <!--        --><?php //endif;?>
    </div>
    <div class="card-body">
<!--        <form class="form-horizontal" action="/user/user_list/" method="post">-->
<!--            <div class="col-lg-4 row" style="margin-top: 20px;margin-bottom: 20px;">-->
<!--                <div class="input-group">-->
<!--                    <input type="text" name="search" value="--><?php //echo (is_array($data_search))?$data_search['search']:'';?><!--" class="form-control" placeholder="">-->
<!--                    <span class="input-group-btn">-->
<!--                    <button class="btn btn-success btn-square" type="submit">Carian Pengguna</button>-->
<!--                </span>-->
<!--                </div>-->
<!--            </div><!-- /input-group -->
<!--        </form>-->
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <tr>
                    <th>No.</th>
                    <th>Tarikh</th>
                    <th>Log</th>
                    <th>Nama</th>
                    <th>ID Pengguna (E-mail)</th>
                    <th>Jabatan/Bahagian</th>
                    <th>Ip address</th>
<!--                    <th>Kumpulan Pengguna</th>-->
                    <th>Status</th>
<!--                    <th class="text-center">Tindakan</th>-->
                </tr>
                <tbody>
                <?php
                if($data_list):
                    $i = (uri_segment(2)=='')?0:uri_segment(2);
                    foreach($data_list as $data):
                        $i=$i+1;
                        ?>
                        <tr>
                            <td><?php echo $i?></td>
                            <td><?php echo date_display($data['DT_ADDED'],'d-M-Y h:i A') ?></td>
                            <td><?php echo $data['LOG_DESC']?> (<?php echo $data['LOG_ID']?>)</td>
                            <td><?php echo $data['USER_NAME']?></td>
                            <td><?php echo $data['USER_EMAIL']?></td>
                            <td><?php echo $data['DEPARTMENT_NAME']?></td>
                            <td><?php echo $data['IP_ADDRESS']?></td>
<!--                            <td>--><?php //echo user_role_text($user['user_role'])?><!--</td>-->
                            <td>
                                <?php
                                if($data['STATUS']==PROCESS_STATUS_SUCCEED):
                                    echo 'Berjaya';
                                else:
                                    echo 'Gagal';
                                endif;
                                ?>
                            </td>
                            <td class="text-center" style="display: none">
                                <!--                        --><?php //if($this->auth->access_view($this->curuser,array(2019))):?>
                                <a class="btn btn-primary btn-display" href="/user/edit_user/<?php echo $user['USER_ID']?>"><span class="glyphicon glyphicon-edit"></span> Update </a>
                                <!--                        --><?php //endif;?>
                                <!--                        --><?php //if($this->auth->access_view($this->curuser,array(2020))):?>
                                <button class="btn btn-danger btn-display" onclick="delete_modal('/user/delete_user/','<?php echo $user['USER_ID']?>')"><span class="glyphicon glyphicon-remove"></span> Delete </button>
                                <!--                        --><?php //endif;?>
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