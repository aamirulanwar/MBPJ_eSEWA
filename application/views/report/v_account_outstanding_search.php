<form method="post" action="/report/gl_summary/">
    <div class="card card-accent-info">
        <div class="card-body">
            <div class="form-group row">
                <div class="col-sm-4">
                    <input type="input" name="date_start" class="form-control date_class" placeholder="Tarikh mula" value="<?php echo set_value('date_start','')?>">
                </div>
                <label class="col-sm-2 col-form-label text-center"> hingga </label>
                <div class="col-sm-4">
                    <input type="input" name="date_end" class="form-control date_class" placeholder="Tarikh tamat" value="<?php echo set_value('date_end','')?>">
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary pull-right btn-submit">Cari</button>
            </div>
        </div>
    </div>
</form>
<div class="card card-accent-info">
    <div class="card-body">
        <div class="table-responsive">
            <?php load_view('/report/v_account_outstanding')?>  
        </div>
    </div>
</div>