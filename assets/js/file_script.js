var dependent_no = 0;

function ajax_err(jqXHR,error, errorThrown) {
    if(jqXHR.status&&jqXHR.status==400){
        alert(jqXHR.responseText);
    }else if(jqXHR.status&&jqXHR.status==419){
        // location.reload();
    }else{
        alert(error+' -- '+errorThrown+' -- '+jqXHR.status);
    }
}
function get_data_category_by_type(category_id){

    var type_id = $('#type_id').val();
    $.ajax({
        url: '/rental_application/get_data_category_by_type',
        type: 'post', //the way you want to send data to your URL
        dataType: 'text',
        data: {
            category_id    : category_id,
            type_id     : type_id
        },
        beforeSend: function () {

        },
        success: function (data) { //probably this request will return anything, it'll be put in var "data"
            $('#category_id').html(data);
        },
        complete: function () {

        },
        error: function(jqXHR,error, errorThrown) {
            ajax_err(jqXHR,error, errorThrown)
        }
    });
}

function currency_format(MyString){

    var x = MyString.value;
    y=x.replace(/[^\d.]/g,'');
    // y=x.replace(/^(\-(\d*))$/,'');
    var objRegex  = new RegExp('(-?[0-9]+)([0-9]{3})');

    //Check For Criteria....
    while(objRegex.test(y))
    {
        //Add Commas After Every Three Digits Of Number...
        y = y.replace(objRegex, '$1,$2');
    }
    var n = y.indexOf(".");
    if (n!=-1) {
        if(y.length>n+2) {
            y=y.substr(0,n+3);
        }
    }

    MyString.value=y;
}

function one_decimal_point(MyString){

    var x = MyString.value;
    y=x.replace(/[^\d.]/g,'');
    var objRegex  = new RegExp('(-?[0-9]+)([0-9]{3})');

    //Check For Criteria....
    // while(objRegex.test(y))
    // {
    //     //Add Commas After Every Three Digits Of Number...
    //     // y = y.replace(objRegex, '$1,$2');
    //     y=y;
    // }
    var n = y.indexOf(".");
    if (n!=-1) {
        if(y.length>n+1) {
            y=y.substr(0,n+2);
        }
    }

    MyString.value=y;
}

function add_dependent() {

    dependent_no = dependent_no+1;

    var dependent_arr = ["Anak","Isteri","Ibu","Ayah","Adik bawah 18 tahun"];
    var option_val = '';
    jQuery.each(dependent_arr, function( key, value ) {
        if(option_val==''){
            option_val = ' <option value="'+value+'">'+value+'</option>';
        }else{
            option_val = option_val+' <option value="'+value+'">'+value+'</option>';
        }
    });

    $('#dependent').append('' +
        '                <div id="dependent_no_'+dependent_no+'" class="form-group row">\n' +
        '                    <label class="col-sm-3 col-form-label"></label>\n' +
        '                    <div class="col-sm-3">\n' +
        '                        <input type="text" name="dependent_name[]" class="form-control" placeholder="Nama" value="">\n' +
        '                    </div>\n' +
        '                    <div class="col-sm-2">\n' +
        // '                        <input type="text" name="dependent_relationship[]" class="form-control" placeholder="Hubungan" value="">\n' +
        '                       <select class="form-control" name="dependent_relationship[]">' +
                                option_val+
        '                       </select>' +
        '                    <c/div>\n' +'                    ' +
        '                    <div class="col-sm-2">\n' +
        '                        <button type="button" onclick="remove_dependent('+dependent_no+')" class="btn btn-danger">x</i></button>\n' +
        '                    </div>\n' +
        '                </div>')
}

function remove_dependent(no_remove) {
    $('#dependent_no_'+no_remove).remove();
}

function upload_picture(upload_name) {
    $('#upload_name').val(upload_name);
    var opts = {
        'url': '/upload_file/insert_temp/',
        // 'dataType': 'text',
        dataType: 'json',
        beforeSend: function () {
            $.LoadingOverlay("show",{
                size : '40px'
            });
        },
        success: function (data) {
           if(data.status_upload==1){
                $('#'+upload_name+'_status').val('');
                $('#alert_modal .modal-body').html(data.error_remark);
                $('#alert_modal').modal('show');
                $('#'+upload_name).val('');
           }else{
               $('#'+upload_name+'_status').val(1);
               $('#'+upload_name+'_content').html('<img class="img_upload" src="'+data.display_path+'">&nbsp;&nbsp;<button onclick="remove_image_upload(\''+upload_name+'\','+data.id_upload+')" type="button" class="btn btn-danger">x</i></button>');
           }
        },
        complete: function () {
            $.LoadingOverlay("hide");
        },
        error: function(jqXHR,error, errorThrown) {
            ajax_err(jqXHR,error, errorThrown)
        }
    };
    $('#submit_image').ajaxSubmit(opts);
}

function remove_image_upload(upload_name,id) {
    $.ajax({
        url: '/upload_file/remove_temp',
        type: 'post', //the way you want to send data to your URL
        dataType: 'text',
        data: {
            upload_name : upload_name,
            id          : id
        },
        beforeSend: function () {
            $.LoadingOverlay("show",{
                size : '40px'
            });
        },
        success: function (data) { //probably this request will return anything, it'll be put in var "data"
            if(data==1){
                // alert(upload_name);
                $('#'+upload_name+'_content').html('<input accept="image/png,image/jpeg,image/jpg" type="file" onchange="upload_picture(\''+upload_name+'\')" name="'+upload_name+'" id="'+upload_name+'" class="form-control">');
                $('#'+upload_name+'_status').val('');
            }
        },
        complete: function () {
            $.LoadingOverlay("hide");
        },
        error: function(jqXHR,error, errorThrown) {
            ajax_err(jqXHR,error, errorThrown)
        }
    });
}

function calculate_duration(){

    var date_start  = $('#date_start').val();
    var date_end    = $('#date_end').val();
    $.ajax({
        url: '/account/calculate_duration',
        type: 'post', //the way you want to send data to your URL
        dataType: 'text',
        data: {
            date_start      : date_start,
            date_end        : date_end
        },
        beforeSend: function () {

        },
        success: function (data) { //probably this request will return anything, it'll be put in var "data"
            // $('#category_id').html(data);
            $('#rental_duration').val(data);
        },
        complete: function () {

        },
        error: function(jqXHR,error, errorThrown) {
            ajax_err(jqXHR,error, errorThrown)
        }
    });
}

function calculate_cost(obj) {
    var estimation_rental_charge = $('#estimation_rental_charge').val();
    estimation_rental_charge = estimation_rental_charge.replace(',','');
    estimation_rental_charge = estimation_rental_charge.replace(',','');
    estimation_rental_charge = estimation_rental_charge.replace(',','');
    estimation_rental_charge = estimation_rental_charge.replace(',','');

    var difference_rental_charge = $('#difference_rental_charge').val();
    difference_rental_charge = difference_rental_charge.replace(',','');
    difference_rental_charge = difference_rental_charge.replace(',','');
    difference_rental_charge = difference_rental_charge.replace(',','');
    difference_rental_charge = difference_rental_charge.replace(',','');

    var type = $('input[name=difference_rental_charge_type]:checked').val();
    var total_cost = 0;
    if(type==1){
        total_cost = Number(estimation_rental_charge)+Number(difference_rental_charge);
    }else if(type==2){
        total_cost = Number(estimation_rental_charge)-Number(difference_rental_charge);
    }

    $('#rental_charge').val(total_cost);
}

function calculate_cost_application() {
    var rent_fee = $('#rent_fee').val();
    var new_rent_fee = rent_fee.replace(/,/g,'');

    var difference_rental_charge = $('#difference_rental_charge').val();
    var new_difference_rental_charge = difference_rental_charge.replace(/,/g,'');

    var type = $('input[name=difference_rental_charge_type]:checked').val();
    var total_cost = 0;
    if(type==1){
        total_cost = Number(new_rent_fee)+Number(new_difference_rental_charge);
    }else if(type==2){
        total_cost = Number(new_rent_fee)-Number(new_difference_rental_charge);
    }

    $('#rental_charge').val(total_cost);

    calculate_rental_deposit()
}


function waste_charge(val) {
    if(val==1){
        $('#waste_management_charge').show();
    }else{
        $('#waste_management_charge').hide();
    }
}

function frozen_charge(val) {
    if(val==1){
        $('#freezer_management_charge').show();
    }else{
        $('#freezer_management_charge').hide();
    }
}

function get_rent() {
    var asset_id = $('#asset_id').val();
    $.ajax({
        url: '/rental_application/get_asset_by_id',
        type: 'post', //the way you want to send data to your URL
        dataType: 'text',
        data: {
            asset_id : asset_id
        },
        beforeSend: function () {

        },
        success: function (data) { //probably this request will return anything, it'll be put in var "data"
            $('#rent_fee').val(data);
        },
        complete: function () {

        },
        error: function(jqXHR,error, errorThrown) {
            ajax_err(jqXHR,error, errorThrown)
        }
    });
}

function calculate_rental_deposit() {
    var rent_fee = $('#rental_charge').val();
    var new_rent_fee = rent_fee.replace(/,/g,'');
    // rent_fee = rent_fee.replace(',','');
    // rent_fee = rent_fee.replace(',','');
    // rent_fee = rent_fee.replace(',','');

    var waste_management_bills = $('input[name=waste_management_bills]:checked').val();
    if(waste_management_bills==1){
        var waste_management_charge = $('#waste_management_charge_id').val();
        var new_waste_management_charge = waste_management_charge.replace(/,/g,'');
        // waste_management_charge = waste_management_charge.replace(',','');
        // waste_management_charge = waste_management_charge.replace(',','');
        // waste_management_charge = waste_management_charge.replace(',','');
    }else{
        var new_waste_management_charge = '0';
    }

    var freezer_management_bills = $('input[name=freezer_management_bills]:checked').val();
    if(freezer_management_bills==1){
        var freezer_management_charge = $('#freezer_management_charge_id').val();
        var new_freezer_management_charge = freezer_management_charge.replace(/,/g,'');
        // waste_management_charge = waste_management_charge.replace(',','');
        // waste_management_charge = waste_management_charge.replace(',','');
        // waste_management_charge = waste_management_charge.replace(',','');
    }else{
        var new_freezer_management_charge = '0';
    }

    var total_deposit = (Number(new_rent_fee)+Number(new_waste_management_charge)+Number(new_freezer_management_charge))*4;

    $('#deposit_rental').val(Number(total_deposit.toFixed(2)).toLocaleString());
}

function calculate_area_billboard() {
    var height  = $('#height_billboard').val();
    var width   = $('#width_billboard').val();

    var area = Number(height)*Number(width);
    $('#area_billboard').val(area)
}

function add_transaction_code(type='J') {

    $.ajax({
        url: '/bill/add_transaction',
        type: 'post', //the way you want to send data to your URL
        dataType: 'text',
        data: {
            type:type
        },
        beforeSend: function () {
            $.LoadingOverlay("show",{
                size : '40px'
            });
        },
        success: function (data) { //probably this request will return anything, it'll be put in var "data"
            $('#content_transaction').append(data);
        },
        complete: function () {
            $.LoadingOverlay("hide");
            $('.js-example-basic-single').select2();
        },
        error: function(jqXHR,error, errorThrown) {
            ajax_err(jqXHR,error, errorThrown)
        }
    });
}

function add_journal_transaction(type='J',journal_code,tr_code,journal_id,bill_category,bill_amount) {
    
    $.ajax({
        url: '/bill/add_journal_transaction',
        type: 'post', //the way you want to send data to your URL
        data: {
            type:type,
            journal_code:journal_code,
            journal_id:journal_id,
            tr_code:tr_code,
            bill_category:bill_category,
            bill_amount:bill_amount
        },
        beforeSend: function () {
            $.LoadingOverlay("show",{
                size : '40px'
            });
        },
        success: function (data) { //probably this request will return anything, it'll be put in var "data"
            $('#content_transaction').append(data);
        },
        complete: function () {
            $.LoadingOverlay("hide");
            if (journal_code!='B03' || journal_code!='R03') {

                $('.js-example-basic-single').select2({disabled:true});
            }
            else {

                $('.js-example-basic-single').select2();
            }
        },
        error: function(jqXHR,error, errorThrown) {
            ajax_err(jqXHR,error, errorThrown)
        }
    });
}

function chooseJournalCode() {
    
    $.ajax({
        url: '/journal/journal_code_lists',
        type: 'GET',
        data:{
            bill_category: $('#default_bill_category').val()
        }
    })
    .done(function(r) {
        
        var $select = $('#choose_journal_code');
        var select_option = '<option value="">Sila pilih satu</option>';
        
        $.each(r, function(index, obj) {
            select_option+='<option value="'+obj.JOURNAL_ID+'" data-journal-code="'+obj.JOURNAL_CODE+'">'+obj.JOURNAL_DESC+' ('+obj.JOURNAL_CODE+') '+'</option>';
        });

        $select.html(select_option);
        $('#add_transaction_code_modal').modal('show');
    })
    .fail(function(e) {
        console.log("error");
        console.log(e);
        alert("Tiada data ditemui untuk senarai rekod kod jurnal");
    });
}

function confirmChooseJournalCode(el,tr_code_el) {
    
    if ($(el).val()!="") {

        var bill_category = $(tr_code_el).find(':selected').attr('data-bill-category');
        var bill_amount = $(tr_code_el).find(':selected').attr('data-bill-amount');
        console.log($(tr_code_el).val());
        add_journal_transaction('J',$(el).find(':selected').attr('data-journal-code'),$(tr_code_el).val(),$(el).val(),bill_category,bill_amount);
        $(el).val("");
        $('#add_transaction_code_modal').modal('hide');
    }
    else {

        alert("Sila pilih satu kod jurnal");
    }
}

function setSelectedTrcode(select_el,target_el) {
    
    $(target_el).val($(select_el).val());
}

function remove_tr_code(obj) {
    $(obj).parent().parent().remove();
}

function status_approve_new() {
    var status_application = $('input[name=status_application]:checked').val();
    if(status_application==1){
        $('.approval-new').hide();
    }else{
        $('.approval-new').show();
    }
}

function status_agree_default() {
    var status_application = $('input[name=status_agree]:checked').val();
    if(status_application==1){
        $('.agree-default').hide();
    }else{
        $('.agree-default').show();
    }
}

function occupation_status_clicked(){
    var occupation_status = $('input[name=occupation_status]:checked').val();
    if(occupation_status == 1){
        $('#occupation').show();
        $('#total_earnings').show();
    }else{
        $('#occupation').hide();
        $('#total_earnings').hide();
    }
}

function get_category_by_type(category_id) {
    var type_id     = $('#type_id').val();
    // var category_id = $('#category_id').val();
    $.ajax({
        url: '/ajax_js/get_category_by_type',
        type: 'post', //the way you want to send data to your URL
        dataType: 'text',
        data: {
            type_id     : type_id,
            category_id : category_id,
        },
        beforeSend: function () {

        },
        success: function (data) { //probably this request will return anything, it'll be put in var "data"
            $('#category_id').html(data);
        },
        complete: function () {

        },
        error: function(jqXHR,error, errorThrown) {
            ajax_err(jqXHR,error, errorThrown)
        }
    });
}

function number_only(obj){
    var sanitized = obj.value.replace(/[^0-9]/g, '');
    // Update value
    obj.value = sanitized;
}