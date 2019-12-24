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