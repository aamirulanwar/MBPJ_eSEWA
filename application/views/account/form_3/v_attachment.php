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