<?php
notify_msg('notify_msg');
?>
<form method="post" action="/bill/notice_list">

<style> 
.btn-orange,    
.btn-orange:hover,  
.btn-orange:active, 
.btn-orange:visited,    
.btn-orange:focus { 
    background-color: #ff9900;  
    color:black;    
}

.btn-yellow,    
.btn-yellow:hover,  
.btn-yellow:active, 
.btn-yellow:visited,    
.btn-yellow:focus { 
    background-color: #ffff00;  
    color:black;    
}   
</style>
<div class="card card-accent-info">
    <div class="card-header">
        <div class="pull-left">
            <strong>Carian Akaun</strong>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="form-group col-4">
                <label for="exampleFormControlInput1">No. akaun</label>
                <input type="text" name="account_number" value="<?php echo set_value('account_number', search_default($data_search,'account_number')) ?>" class="form-control" id="exampleFormControlInput1" placeholder="No. akaun">
            </div>
            <div class="form-group col-4">
                <label for="exampleFormControlInput1">No. kad pengenalan</label>
                <input type="text" name="ic_number" value="<?php echo set_value('ic_number', search_default($data_search,'ic_number')) ?>" class="form-control" placeholder="No. kad pengenalan">
            </div>
            <div class="form-group col-4">
                <label for="exampleFormControlInput1">Nama penyewa</label>
                <input type="text" name="name" value="<?php echo set_value('name', search_default($data_search,'name')) ?>" class="form-control" placeholder="Nama penyewa">
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary pull-right btn-submit">Cari</button>
    </div>
</div>
</form>

<div class="card card-accent-info">
    <div class="card-header">
        <div class="pull-left">
            <strong>Jumlah Rekod : <?php echo $total_result?></strong>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <tr>
                    <th>No.</th>
                    <th>Akaun Sewaan</th>
                    <th>Jenis / Kod kategori</th>
                    <th class="text-right">Jumlah Tunggakan (RM)</th>
                    <th class="text-center">Notis</th>
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
                            <td>
                                <strong><?php echo $row['ACCOUNT_NUMBER']?></strong><br>
                                <?php echo $row['NAME']?><br>
                                <?php echo ($row['APPLICANT_TYPE']==APPLICANT_TYPE_INDIVIDUAL)? display_ic_number($row['IC_NUMBER']):$row['COMPANY_REGISTRATION_NUMBER']?>
                            </td>
                            <td>
                                <?php echo '<strong>'.$row['TYPE_NAME'].'</strong>' ?><br>
                                <?php echo '<strong>'.$row['CATEGORY_CODE'].'</strong> - '.$row['CATEGORY_NAME'] ?>
                            </td>
                            <td class="text-right">
                                <?php echo num($row['total_tunggakan'])?><br>
<!--                                <strong>Aging : </strong> --><?php //echo $row['AGING']?>
                            </td>
                            <td class="text-center">
                                <!-- <?php 
                                    // for($cnt=1;$cnt<=$row['NOTICE_LEVEL'];$cnt++):
                                    //     if($row['NOTICE_LEVEL']==$cnt):
                                    //         $btn_class  = 'btn btn-block btn-danger btn-sm active';
                                    //         $arrow      = '<i class="fa fa-arrow-right"></i>';
                                    //     else:
                                    //         $btn_class  = 'btn btn-block btn-outline-danger btn-sm';
                                    //         $arrow      = '';
                                    //     endif;

                                    //     echo '<a class="'.$btn_class.'" href="/bill/generate_notice/'.urlEncrypt($row['ACCOUNT_ID']).'/'.urlEncrypt($cnt).'"><span class="glyphicon glyphicon-edit"></span> <strong>'.notice_level($cnt).'</strong> </a>';
                                    // endfor;

                                    // if($row['NOTICE_LEVEL']<=NOTICE_LEVEL_4):
                                    //     echo '<br><a class="btn btn-block btn-info btn-sm" title="Jana Notis '.notice_level(NOTICE_LEVEL_5).'" href="/bill/generate_notice/'.urlEncrypt($row['ACCOUNT_ID']).'/'.urlEncrypt(NOTICE_LEVEL_5).'"><span class="glyphicon glyphicon-edit"></span><strong> '.notice_level(NOTICE_LEVEL_5).'</strong> </a>';
                                    // endif;
                                    // if($row['NOTICE_LEVEL']==NOTICE_LEVEL_4):
                                    //     echo '<br><a class="btn btn-block btn-info btn-sm" title="Jana Notis '.notice_level(NOTICE_LEVEL_6).'" href="/bill/generate_notice/'.urlEncrypt($row['ACCOUNT_ID']).'/'.urlEncrypt(NOTICE_LEVEL_6).'"><span class="glyphicon glyphicon-edit"></span><strong> '.notice_level(NOTICE_LEVEL_6).'</strong> </a>';
                                    // endif;
                                ?>-->
                                   <?php
                                    for($cnt=1;$cnt<=$row['NOTICE_LEVEL'];$cnt++):
                                    
                                        if ($cnt<=3):
                                            $btn_class  = 'btn btn-block btn-yellow btn-sm active';
                                            $arrow      = '<i class="fa fa-arrow-right"></i>';
                                            echo '<a class="'.$btn_class.'" href="/bill/generate_notice/'.urlEncrypt($row['ACCOUNT_ID']).'/'.urlEncrypt($cnt).'"><span class="glyphicon glyphicon-edit"></span> <strong>'.notice_level($cnt).'</strong> </a>';
                                        
                                        elseif ($cnt==4):
                                            $btn_class  = 'btn btn-block btn-orange btn-sm active';
                                            $arrow      = '<i class="fa fa-arrow-right"></i>';
                                            echo '<a class="'.$btn_class.'" href="/bill/generate_notice/'.urlEncrypt($row['ACCOUNT_ID']).'/'.urlEncrypt($cnt).'"><span class="glyphicon glyphicon-edit"></span> <strong>'.notice_level($cnt).'</strong> </a>';
                                        
                                        endif;
                                                                                                                
                                    endfor;
                                    
                                    if($row['NOTICE_LEVEL']<=NOTICE_LEVEL_6):
                                        echo '<br><a class="btn btn-block btn-danger btn-sm active" title="Jana Notis '.notice_level(NOTICE_LEVEL_5).'" href="/bill/generate_notice/'.urlEncrypt($row['ACCOUNT_ID']).'/'.urlEncrypt(NOTICE_LEVEL_5).'"><span class="glyphicon glyphicon-edit"></span><strong> '.notice_level(NOTICE_LEVEL_5).'</strong> </a>';
                           endif;
                                    if($row['NOTICE_LEVEL']==NOTICE_LEVEL_5):
                                        echo '<br><a class="btn btn-block btn-danger btn-sm active" title="Jana Notis '.notice_level(NOTICE_LEVEL_6).'" href="/bill/generate_notice/'.urlEncrypt($row['ACCOUNT_ID']).'/'.urlEncrypt(NOTICE_LEVEL_6).'"><span class="glyphicon glyphicon-edit"></span><strong> '.notice_level(NOTICE_LEVEL_6).'</strong> </a>';
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