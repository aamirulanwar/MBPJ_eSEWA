</div>
</div>
</main>
</div>
<footer class="app-footer">
    <div>
<!--        <span>Copyright &copy; AUFA INTELLIGENCE SDN BHD. All right reserved.</span>-->
    </div>
<!--    <div class="ml-auto">-->
<!--        <span>Powered by</span>-->
<!--        <a href="https://coreui.io">CoreUI</a>-->
<!--    </div>-->
</footer>

<div class="modal fade" id="modal_delete">
    <div class="modal-dialog modal-sm" style="margin-top: 25vh;z-index: 9999999">
        <div class="modal-content">
            <div class="modal-body" style="text-align: center">
                Padam rekod?
            </div>
            <div class="modal-footer" id="delete_footer_modal">
                <button type="button" onclick="confirm_record();" class="btn btn-primary">Ya</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="modal_cancel">
    <div class="modal-dialog modal-sm" style="margin-top: 25vh;">
        <div class="modal-content">
            <div class="modal-body">
                <p>Anda pasti mahu batal rekod ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="confirm_record();" class="btn btn-primary">Ya</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="modal_warning_gantt" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Info</h4>
            </div>
            <div class="modal-body">
                <div id="content_warning"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="alert_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Perhatian</h5>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="link" value="">
<input type="hidden" id="id" value="">

<!-- CoreUI and necessary plugins-->
<script src="/assets/node_modules/popper.js/dist/umd/popper.min.js"></script>
<script src="/assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="/assets/node_modules/pace-progress/pace.min.js"></script>
<script src="/assets/node_modules/perfect-scrollbar/dist/perfect-scrollbar.min.js"></script>
<script src="/assets/node_modules/@coreui/coreui/dist/js/coreui.min.js"></script>
<script src="/assets/jquery-ui/jquery-ui.min.js"></script>
<script src="/assets/js/form.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.6/dist/loadingoverlay.min.js"></script>
<script src="/assets/js/file_script.js"></script>

<!-- Plugins and scripts required by this view-->
<?php
    if(uri_segment(1)=='dashboard'):
        ?>
        <script src="/assets/node_modules/@coreui/coreui-plugin-chartjs-custom-tooltips/dist/js/custom-tooltips.min.js"></script>
<!--        <script src="/assets/js/charts.js"></script>-->
        <?php
    endif;
?>
<script>
    function delete_modal(link_delete,delete_id){
        $('#modal_delete').modal('show');
        $('#link').val(link_delete);
        $('#id').val(delete_id);
    }

    function confirm_record(){
        var link = $('#link').val();
        var delete_id   = $('#id').val();
        $.ajax({
            'url': link,
            'type': 'post', //the way you want to send data to your URL
            'dataType': 'text',
            'data': {'delete_id': delete_id},
            'beforeSend': function () {
                $('#delete_footer_modal').html('<em><i class="fa fa-clock-o"></i> Memproses rekod...</em>');
            },
            'success': function (data) { //probably this request will return anything, it'll be put in var "data"
                setTimeout(function () {
                    $('#delete_footer_modal').html('<span style="color: green">'+data+'</span>');
                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);
                }, 1500);
            }
        });
    }

    setTimeout(function(){
        $('.alert').hide();
    }, 30000);

    $('.date_class').datepicker({
        dateFormat:"dd M yy",
        changeMonth: true,
        changeYear: true,
        // showButtonPanel: true,
        yearRange: "-100:+0", // last hundred years
    });

    $('.date_class_duration').datepicker({
        dateFormat:"dd M yy",
        changeMonth: true,
        changeYear: true,
        // showButtonPanel: true,
        yearRange: "-5:+100", // last hundred years
    });

    $('.form-control-plaintext').prop('readonly', true);

    $('.date_class').prop('readonly', true);

    $( document ).on( 'focus', ':input', function(){
        $( this ).attr( 'autocomplete', 'off' );
    });

    $(document).ready(function() {
        $('.js-example-basic-single').select2(
            {

            }
        );
    });
</script>

<!--<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>-->
<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">-->
<!--<script src="https://cdn.jsdelivr.net/npm/gijgo@1.9.10/js/gijgo.min.js" type="text/javascript"></script>-->
<!--<link href="https://cdn.jsdelivr.net/npm/gijgo@1.9.10/css/gijgo.min.css" rel="stylesheet" type="text/css" />-->
</body>
</html>