    <div class="form-group row">
    <label class="col-sm-3 col-form-label">Salinan kad pengenalan <span class="mandatory">*</span></label>
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
        <input type="hidden" name="ic_number_pic_status" id="ic_number_pic_status" class="form-control" value="<?php echo set_value('ic_number_pic_status',($ic_number_pic)?1:'')?>">
        <?php echo form_error('ic_number_pic_status')?>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Gambar passport <span class="mandatory">*</span></label>
    <div class="col-sm-5">
        <div id="passport_pic_content">
            <?php
            if($passport_pic):
                ?>
                <img class="img_upload" src="/<?php echo $passport_pic['PATH'].$passport_pic['FILENAME']?>">&nbsp;&nbsp;
            <?php
            else:
                ?>
                -
            <?php
            endif;
            ?>
        </div>
        <input type="hidden" name="passport_pic_status" id="passport_pic_status" class="form-control" value="<?php echo set_value('passport_pic_status',($passport_pic)?1:'')?>">
        <?php echo form_error('passport_pic_status')?>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Salinan pendaftaran perniagaan (SSM)</label>
    <div class="col-sm-5">
        <div id="ssm_pic_content">
            <?php
            if($ssm_pic):
                ?>
                <img class="img_upload" src="/<?php echo $ssm_pic['PATH'].$ssm_pic['FILENAME']?>">&nbsp;&nbsp;
            <?php
            else:
                ?>
                -
            <?php
            endif;
            ?>
        </div>
        <input type="hidden" name="ssm_pic_status" id="ssm_pic_status" class="form-control" value="<?php echo set_value('ssm_pic_status',($ssm_pic)?1:'')?>">
        <?php echo form_error('ssm_pic_status')?>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Dokumen Perjanjian </label>
    <div class="col-sm-5">
        <div id="doc_agreement_upload">
            <?php
            if($doc_agreement):
                ?>
                <img class="img_upload" src="/<?php echo FILE_UPLOAD_TEMP.'/'.$doc_agreement['FILE_NAME']?>">&nbsp;&nbsp;<button onclick="remove_image_upload('doc_agreement',<?php echo $doc_agreement['ID']?>)" type="button" class="btn btn-danger">x</i></button>
            <?php
            else:
                ?>
                <input type="file" accept="image/png,image/jpeg,image/jpg,image/pdf" onchange="upload_picture('doc_agreement')" name="doc_agreement" id="doc_agreement" class="form-control">
            <?php
            endif;
            ?>
        </div>
        <input type="hidden" name="upload_name" id="upload_name" class="form-control" value="<?php echo set_value('doc_agreement',($doc_agreement)?1:'')?>">
        <?php echo form_error('doc_agreement')?>
    </div>
</div>
            
