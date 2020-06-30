<div class="form-group row">
    <label class="col-sm-3 col-form-label">Carian SSM (Borang 9)</label>
    <div class="col-sm-5">
        <div id="app_ssm_file_content">
            <?php
            if($app_ssm_file):
                ?>
                <img class="img_upload" src="/<?php echo $app_ssm_file['PATH'].$app_ssm_file['FILENAME']?>">&nbsp;&nbsp;
            <?php
            else:
                ?>
                -
            <?php
            endif;
            ?>
        </div>
        <input type="hidden" name="app_ssm_file_status" id="app_ssm_file_status" class="form-control" value="<?php echo set_value('app_ssm_file_status',($app_ssm_file)?1:'')?>">
        <?php echo form_error('app_ssm_file_status')?>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Pelan struktur </label>
    <div class="col-sm-5">
        <div id="structure_plan_content">
            <?php
            if($structure_plan):
                ?>
                <img class="img_upload" src="/<?php echo $structure_plan['PATH'].$structure_plan['FILENAME']?>">&nbsp;&nbsp;
            <?php
            else:
                ?>
                -
            <?php
            endif;
            ?>
        </div>
        <input type="hidden" name="structure_plan_status" id="structure_plan_status" class="form-control" value="<?php echo set_value('structure_plan_status',($structure_plan)?1:'')?>">
        <?php echo form_error('structure_plan_status')?>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Pelan lokasi</label>
    <div class="col-sm-5">
        <div id="location_plan_file_content">
            <?php
            if($location_plan_file):
                ?>
                <img class="img_upload" src="/<?php echo $location_plan_file['PATH'].$location_plan_file['FILENAME']?>">&nbsp;&nbsp;
            <?php
            else:
                ?>
                -
            <?php
            endif;
            ?>
        </div>
        <input type="hidden" name="location_plan_file_status" id="location_plan_file_status" class="form-control" value="<?php echo set_value('location_plan_file_status',($location_plan_file)?1:'')?>">
        <?php echo form_error('location_plan_file_status')?>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Map info</label>
    <div class="col-sm-5">
        <div id="map_info_content">
            <?php
            if($map_info):
                ?>
                <img class="img_upload" src="/<?php echo $map_info['PATH'].$map_info['FILENAME']?>">&nbsp;&nbsp;
            <?php
            else:
                ?>
                -
            <?php
            endif;
            ?>
        </div>
        <input type="hidden" name="map_info_status" id="map_info_status" class="form-control" value="<?php echo set_value('map_info_status',($map_info)?1:'')?>">
        <?php echo form_error('map_info_status')?>
    </div>
</div>
<div class="form-group row" style="display:<?=( $customPageKey == "2") ? "" : "none"?>">
    <label class="col-sm-3 col-form-label">Dokumen Tandatangan Perjanjian & Proses Hasil</label>
    <div class="col-sm-5">
        <div class="row files" id="containerDokumenTandatangan">            
            <span class="btn btn-default btn-file">
                <a href="javascript:;" style="display:block" class="btn btn-primary btn-block btn-outlined" onclick="document.getElementById('filesUpload1').click()">Tambah Dokumen</a>
                <div id="inputFileContainer">
                    <input type='file' id="filesUpload1" name="files1[]" style="display:none" />
                </div>
            </span>
            <br />
            <ul class="fileList"></ul>
            <div id="uploadedSupportDocument">
                <ul>
                <?php
                    if ( $customPageKey == "2")
                    {
                        foreach ($support_documents as $document) 
                        {
                            # code...
                            echo "<li><a href='/".$document["PATH"]."/".$document["FILENAME"]."' download>".$document["FILENAME"]."</a></li>";
                        } 
                    }
                ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    $(document).ready(function()
    {
        $("#filesUpload1").change(function()
        {
            var fd = new FormData();
            var files = $('#filesUpload1')[0].files[0];
            fd.append('fileUploaded',files);
            fd.append('account_id',$("#account_id").val());

            $.ajax({
                url: '/account/upload_dokumen_tandatangan',
                type: 'post',
                data: fd,
                contentType: false,
                processData: false,
                success: function(response)
                {
                    response = JSON.parse(response);
                    console.log(response);
                    if (response.status == true)
                    {
                        $(".fileList").append("<li id='" + response.detail + "'><strong>" + response.message + "</strong>&nbsp<a href='javascript:;' onclick='removeImageLink(" + response.detail + ")'> Batal</a></li>");
                    }
                    $("#filesUpload1").val('');
                },
                error: function(error){$("#filesUpload1").val('');}
            });
        });
    });

    function removeImageLink(id)
    {
        $.ajax({
                url: '/account/delete_dokumen_temp',
                type: 'post',
                data: {"temp_id": id},
                success: function(response)
                {
                    response = JSON.parse(response);
                    if (response.status == true)
                    {
                        console.log(response);
                        $("#"+id).remove();
                    }
                },
            });
    }
</script>