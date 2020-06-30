<div class="form-group row">
    <label class="col-sm-3 col-form-label">Surat permohonan</label>
    <div class="col-sm-5">
        <div id="letter_application_file_content">
            <?php
            if($letter_application):
                ?>
                <img class="img_upload" src="/<?php echo $letter_application['PATH'].$letter_application['FILENAME']?>">&nbsp;&nbsp;
            <?php
            else:
                ?>
                -
            <?php
            endif;
            ?>
        </div>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Salinan kad pengenalan</label>
    <div class="col-sm-5">
        <div id="ic_number_pic_content">
            <?php
            if($ic_number_pic):
                ?>
                <img class="img_upload" src="/<?php echo $ic_number_pic['PATH'].$ic_number_pic['FILENAME']?>">&nbsp;&nbsp;
            <?php
            else:
                ?>
                -
            <?php
            endif;
            ?>
        </div>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Pelan lokasi (Google map) </label>
    <div class="col-sm-5">
        <div id="location_plan_file_content">
            <?php
            if($location_plan):
                ?>
                <img class="img_upload" src="/<?php echo $location_plan['PATH'].$location_plan['FILENAME']?>">&nbsp;&nbsp;
            <?php
            else:
                ?>
                -
            <?php
            endif;
            ?>
        </div>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Foto lokasi</label>
    <div class="col-sm-5">
        <div id="photo_location_file_content">
            <?php
            if($photo_location):
                ?>
                <img class="img_upload" src="/<?php echo $photo_location['PATH'].$photo_location['FILENAME']?>">&nbsp;&nbsp;
            <?php
            else:
                ?>
                -
            <?php
            endif;
            ?>
        </div>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Salinan cadangan pelan struktur </label>
    <div class="col-sm-5">
        <div id="suggestion_structure_plan_file_content">
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
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Carian SSM (Borang 9) </label>
    <div class="col-sm-5">
        <div id="app_ssm_file_content">
            <?php
            if($ssm):
                ?>
                <img class="img_upload" src="/<?php echo $ssm['PATH'].$ssm['FILENAME']?>">&nbsp;&nbsp;
            <?php
            else:
                ?>
                -
            <?php
            endif;
            ?>
        </div>
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