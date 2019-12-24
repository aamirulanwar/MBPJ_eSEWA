<div class="form-group row">
    <label class="col-sm-3 col-form-label">Salinan kad pengenalan </label>
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