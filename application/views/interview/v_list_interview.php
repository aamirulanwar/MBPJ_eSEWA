<?php
notify_msg('notify_msg');
?>
<div class="card card-accent-info">
    <div class="card-header">
        <div class="pull-left">
            <strong>Jumlah Rekod : <?php echo $total_result?></strong>
        </div>
        <?php if($this->auth->access_view($this->curuser,array(4006))):?>
        <div class="pull-right">
            <a href="/interview/add_interview" class="add-new"><i style="color: green" class="fa fa-plus-circle fa-lg"></i> Tambah</a>
        </div>
        <?php endif;?>
    </div>
    <div class="card-body">
<!--        <form class="form-horizontal" action="/user" method="post">-->
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
                    <th>Jenis sewaan</th>
                    <th>Tajuk temuduga</th>
                    <th>Tarikh temuduga</th>
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
                            <td><?php echo $row['TYPE_NAME']?></td>
                            <td>
                                <?php echo $row['INTERVIEW_NAME']?><br>
                                <i class="icons font-l cui-circle-check"></i> <a href="excel_interview_application/<?php echo urlEncrypt($row['INTERVIEW_ID'])?>" target="_blank">Senarai Panggilan Temuduga</a><br>
                                <i class="icons font-l cui-comment-square"></i> <a href="doc_panel_review/<?php echo urlEncrypt($row['INTERVIEW_ID'])?>" target="_blank">Borang Ulasan Panel</a>
                            </td>
                            <td><?php echo date_display($row['DATE_INTERVIEW'])?></td>
                            <td class="text-center">
                                <?php if($this->auth->access_view($this->curuser,array(4007))):?>
                                <a class="btn btn-block btn-primary btn-display" href="/interview/interview_details/<?php echo urlEncrypt($row['INTERVIEW_ID'])?>"><span class="glyphicon glyphicon-edit"></span> Terperinci </a>
                                <?php endif;?>
                                <?php if($this->auth->access_view($this->curuser,array(4008))):?>
                                <button class="btn btn-danger btn-display btn-block" onclick="delete_modal('/interview/delete_interview/','<?php echo $row['INTERVIEW_ID']?>')"><span class="glyphicon glyphicon-remove"></span> Padam </button>
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