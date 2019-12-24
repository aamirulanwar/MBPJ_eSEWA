<?php
    notify_msg('department');
?>
<div class="card card-accent-info">
    <div class="card-header">
        <div class="pull-left">
            <strong>Jumlah Rekod : <?php echo $total_dept?></strong>
        </div>
        <?php if($this->auth->access_view($this->curuser,array(2002))):?>
        <div class="pull-right">
            <a href="/department/add_department/" class="add-new"><i style="color: green" class="fa fa-plus-circle fa-lg"></i> Tambah</a>
        </div>
        <?php endif;?>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <tr>
                    <th>No.</th>
                    <th>Jabatan/Bahagian</th>
                    <th>Status</th>
                    <th class="text-center">Tindakan</th>
                </tr>
                <tbody>
                <?php
                if($department_arr):
                    $i = 0;
                    foreach($department_arr as $dept):
                        $i=$i+1;
                        $tab = '10px';
                        $arrow = '';
                        if($dept['DEPT_LEVEL']==DEPARTMENT_LEVEL_2):
                            $tab = '50px;';
                            $arrow = '<span aria-hidden="true" style="color: #0000d6" class="glyphicon glyphicon-triangle-right"></span> ';
                        endif;
                        ?>
                        <tr>
                            <td><?php echo $i?></td>
                            <td style="padding-left: <?php echo $tab?>;"><?php echo $arrow.$dept['DEPARTMENT_NAME']?></td>
                            <td>
                                <?php
                                get_status_active($dept['ACTIVE']);
                                ?>
                            </td>
                            <td class="text-center">
                                <?php if($this->auth->access_view($this->curuser,array(2003))):?>
                                <a class="btn btn-primary btn-display" href="/department/edit_department/<?php echo urlEncrypt($dept['DEPARTMENT_ID'])?>"><span class="glyphicon glyphicon-edit"></span> Kemaskini </a>
                                <?php endif;?>
                                <?php if($this->auth->access_view($this->curuser,array(2004))):?>
                                <button class="btn btn-danger btn-display" onclick="delete_modal('/department/delete_department/','<?php echo $dept['DEPARTMENT_ID']?>')"><span class="glyphicon glyphicon-remove"></span> Padam </button>
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