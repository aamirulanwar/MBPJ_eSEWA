<?php
    notify_msg('user');
?>
<div class="card card-accent-info">
    <div class="card-header">
        <div class="pull-left">
            <strong>Jumlah Rekod : <?php echo $total_result?></strong>
        </div>
        <?php if($this->auth->access_view($this->curuser,array(2006))):?>
        <div class="pull-right">
            <a href="/user_group/add_user_group" class="add-new"><i style="color: green" class="fa fa-plus-circle fa-lg"></i> Tambah</a>
        </div>
        <?php endif;?>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <tr>
                    <th>No.</th>
                    <th>Nama</th>
                    <!--                <th>Access Level</th>-->
                    <th>Status</th>
                    <th class="text-center">Tindakan</th>
                </tr>
                <tbody>
                <?php
                if($user_group_list):
                    $i = 0;
                    foreach($user_group_list as $user_group):
                        $i=$i+1;
                        ?>
                        <tr>
                            <td><?php echo $i?></td>
                            <td><?php echo $user_group['USER_GROUP_DESC']?></td>
                            <!--                    <td>--><?php
                            //                        if($user_group['access_level']==ACCESS_LEVEL_ALL):
                            //                            echo 'All';
                            //                        else:
                            //                            echo 'Own Department/Section';
                            //                        endif;
                            //                        ?>
                            <!--                    </td>-->
                            <td>
                                <?php
                                get_status_active($user_group['ACTIVE']);
                                ?>
                            </td>
                            <td class="text-center">
                                <?php if($this->auth->access_view($this->curuser,array(2007))):?>
                                <a class="btn btn-display btn-primary" href="/user_group/edit_user_group/<?php echo $user_group['USER_GROUP_ID']?>"><span class="glyphicon glyphicon-edit"></span> Kemaskini </a>
                                <?php endif;?>
                                <?php if($this->auth->access_view($this->curuser,array(2008))):?>
                                <button class="btn btn-display btn-danger" onclick="delete_modal('/user_group/delete_user_group/','<?php echo $user_group['USER_GROUP_ID']?>')"><span class="glyphicon glyphicon-remove"></span> Padam </button>
                                <?php endif;?>
                            </td>
                        </tr>
                        <?php
                    endforeach;
                else:
                    echo '<tr><td colspan="4" class="text-center"> - Tiada Rekod - </td></tr>';
                endif;
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>