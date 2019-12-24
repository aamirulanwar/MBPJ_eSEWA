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