<?php
    notify_msg('user');
?>
<div class="card card-accent-info">
    <div class="card-header">
        <div class="pull-left">
            <strong>Jumlah Rekod : <?php echo $total_result?></strong>
        </div>
        <?php if($this->auth->access_view($this->curuser,array(2010))):?>
        <div class="pull-right">
            <a href="/user/add_user/" class="add-new"><i style="color: green" class="fa fa-plus-circle fa-lg"></i> Tambah</a>
        </div>
        <?php endif;?>
    </div>
    <div class="card-body">
        <form class="form-horizontal" action="/user" method="post">
            <div class="col-lg-4 row" style="margin-top: 20px;margin-bottom: 20px;">
                <div class="input-group">
                    <input type="text" name="search" value="<?php echo search_default($data_search,'search');?>" class="form-control" placeholder="">
                    <span class="input-group-btn">
                    <button class="btn btn-success btn-square" type="submit">Cari</button>
                </span>
                </div>
            </div><!-- /input-group -->
        </form>
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <tr>
                    <th>No.</th>
                    <th>Nama</th>
<!--                    <th>Position</th>-->
                    <th>Jabatan/Bahagian</th>
                    <th>Id pengguna</th>
                    <th>No. Tel</th>
                    <th>Kumpulan Pengguna</th>
<!--                    <th>User Role</th>-->
                    <th>Status</th>
                    <th class="text-center">Tindakan</th>
                </tr>
                <tbody>
                <?php
                if($user_list):
                    $i = (uri_segment(3)=='')?0:uri_segment(3);
                    foreach($user_list as $user):
                        $i=$i+1;
                        ?>
                        <tr>
                            <td><?php echo $i?></td>
                            <td><?php echo $user['USER_NAME']?></td>
<!--                            <td>--><?php //echo $user['pos_name']?><!--</td>-->
                            <td><?php echo $user['DEPARTMENT_NAME']?></td>
                            <td><?php echo $user['USER_EMAIL']?></td>
                            <td><?php echo $user['USER_NOBIMBIT']?></td>
                            <td><?php echo $user['USER_GROUP_DESC']?></td>
<!--                            <td>--><?php //echo user_role_text($user['user_role'])?><!--</td>-->
                            <td>
                                <?php
                                get_status_active($user['ACTIVE']);
                                ?>
                            </td>
                            <td class="text-center">
                                <?php if($this->auth->access_view($this->curuser,array(2011))):?>
                                <a class="btn btn-primary btn-display btn-block" href="/user/edit_user/<?php echo urlEncrypt($user['USER_ID'])?>"><span class="glyphicon glyphicon-edit"></span> Kemaskini </a>
                                <?php endif;?>
                                <?php if($this->auth->access_view($this->curuser,array(2012))):?>
                                <button class="btn btn-danger btn-display btn-block" onclick="delete_modal('/user/delete_user/','<?php echo $user['USER_ID']?>')"><span class="glyphicon glyphicon-remove"></span> Padam </button>
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