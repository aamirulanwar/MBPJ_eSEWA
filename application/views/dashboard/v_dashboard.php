<div id="report_yes" class="container-fluid" style="padding:5px;display: none" >
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-6 col-lg-3">
                <div class="card">
                    <div class="card-body p-3 d-flex align-items-center">
                        <i class="fa fa-hourglass-end bg-primary p-3 font-2xl mr-3"></i>
                        <div>
                            <div class="text-value-sm text-primary" id="new_application">0</div>
                            <div class="text-muted text-uppercase font-weight-bold small">Permohonan Belum Diproses</div>
                        </div>
                    </div>
                    <div class="card-footer px-3 py-2">
                        <a class="btn-block text-muted d-flex justify-content-between align-items-center" href="/dashboard/new_application">
                            <span class="small font-weight-bold">Lihat Senarai</span>
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card">
                    <div class="card-body p-3 d-flex align-items-center">
                        <i class="fa fa-check bg-success p-3 font-2xl mr-3"></i>
                        <div>
                            <div class="text-value-sm text-success" id="not_registered">0</div>
                            <div class="text-muted text-uppercase font-weight-bold small">Permohonan Lulus Belum Didaftar</div>
                        </div>
                    </div>
                    <div class="card-footer px-3 py-2">
                        <a class="btn-block text-muted d-flex justify-content-between align-items-center" href="/dashboard/not_registered">
                            <span class="small font-weight-bold">Lihat Senarai</span>
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card">
                    <div class="card-body p-3 d-flex align-items-center">
                        <i class="fa fa-calendar-times-o bg-warning p-3 font-2xl mr-3"></i>
                        <div>
                            <div class="text-value-sm text-warning" id="acc_expiry">0</div>
                            <div class="text-muted text-uppercase font-weight-bold small">Akaun Hampir Tamat</div>
                        </div>
                    </div>
                    <div class="card-footer px-3 py-2">
                        <a class="btn-block text-muted d-flex justify-content-between align-items-center" href="/dashboard/almost_expired">
                            <span class="small font-weight-bold">Lihat Senarai</span>
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card">
                    <div class="card-body p-3 d-flex align-items-center">
                        <i class="fa fa-exclamation-triangle bg-danger p-3 font-2xl mr-3"></i>
                        <div>
                            <div id="notice_this_month" class="text-value-sm text-danger">0</div>
                            <div class="text-muted text-uppercase font-weight-bold small">Akaun sewaan dikenakan notis</div>
                        </div>
                    </div>
                    <div class="card-footer px-3 py-2">
                        <a class="btn-block text-muted d-flex justify-content-between align-items-center" href="/bill/notice_list">
                            <span class="small font-weight-bold">Lihat Senarai</span>
                            <span class="small font-weight-bold">&nbsp;</span>
<!--                            <i class="fa fa-angle-right"></i>-->

                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-columns cols-2">
            <div class="card">
                <div class="card-header">Bilangan Permohonan Sewaan <?php echo date("Y")?></div>
                <div class="card-body">
                    <div class="chart-wrapper">
                        <canvas id="canvas-3"></canvas>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Bilangan Akaun Sewaan</div>
                <div class="card-body">
                    <div class="chart-wrapper">
                        <canvas id="canvas-5"></canvas>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Tawaran Sewaan vs Setuju Terima <?php echo date("Y")?></div>
                <div class="card-body">
                    <div class="chart-wrapper">
                        <canvas id="canvas-4"></canvas>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Status Permohonan Sewaan <?php echo date("Y")?></div>
                <div class="card-body">
                    <div class="chart-wrapper">
                        <canvas id="canvas-2"></canvas>
                    </div>
                </div>
            </div>
<!--            <div class="card">-->
<!--                <div class="card-header">Jumlah Kutipan --><?php //echo date("Y")?><!--</div>-->
<!--                <div class="card-body">-->
<!--                    <div class="chart-wrapper">-->
<!--                        <canvas id="canvas-1"></canvas>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="card">-->
<!--                <div class="card-header">-</div>-->
<!--                <div class="card-body">-->
<!--                    <div class="chart-wrapper">-->
<!--                        <canvas id="canvas-6"></canvas>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
        </div>
    </div>
</div>
<div id="report_no" class="container-fluid" style="padding:5px;text-align: center" >
    <img style="width: 100px; margin-top: 20vh;" src="/assets/images/loading1.gif">
</div>
<script>
    function load_dashboard() {
        $.ajax({
            url : '/report/report_dashboard',
            type : 'post',
            data : {},
            dataType: 'JSON',
            beforeSend:function(){

            }, success: function(data) {
                // alert(JSON.stringify(data));
                $('#report_no').hide();
                $('#report_yes').show();
                $('#new_application').html(data.new_application);
                $('#not_registered').html(data.not_registered);
                $('#notice_this_month').html(data.notice_this_month);
                $('#acc_expiry').html(data.acc_expiry);

                doughnut_chart(data.label_app,data.number_app);
                bar_chart(data.label_status,data.number_status_accepted,data.number_status_rejected);
                piechart(data.label_acc_type,data.number_acc_type);
                radarchart(data.label_accepted,data.number_offered,data.number_accepted);

            }
        });
    }

    function doughnut_chart(label,data){
        var doughnutChart = new Chart($('#canvas-3'), {
            type: 'doughnut',
            data: {
                labels: label,
                datasets: [{
                    data: data,
                    backgroundColor: ['#FF6384', '#43c9c9', '#688f8f', '#3caf85', '#309fdb', '#fbce4a', '#e95b54', '#f7915e', '#854e9b'],
                    hoverBackgroundColor: ['#FF6384', '#43c9c9', '#688f8f', '#3caf85', '#309fdb', '#fbce4a', '#e95b54', '#f7915e', '#854e9b']
                }]
            },
            options: {
                responsive: true,
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            // alert(JSON.stringify(data));
                            // console.log('data dta',data);
                            // console.log('data dta',data.datasets[tooltipItem.datasetIndex]);
                            //get the concerned dataset
                            var dataset = data.datasets[tooltipItem.datasetIndex];
                            //calculate the total of this data set
                            var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
                                return Number(previousValue) + Number(currentValue);
                            });
                            // alert(total);
                            //get the current items value
                            var currentValue = dataset.data[tooltipItem.index];
                            // alert(currentValue);
                            //calculate the precentage based on the total and current item, also this does a rough rounding to give a whole number
                            var percentage = Math.floor(((Number(currentValue)/Number(total)) * 100)+0.5);

                            return currentValue;
                        }
                    }
                }
            }
        }); // eslint-disable-next-line no-unused-vars
    }

    function bar_chart(label,data_accepted,data_rejected){
        var barChart = new Chart($('#canvas-2'), {
            type: 'bar',
            data: {
                labels: label,
                datasets: [{
                    label: 'Diterima',
                    backgroundColor: 'rgba(0, 179, 0, 1)',
                    borderColor: 'rgba(151, 187, 205, 0.8)',
                    highlightFill: 'rgba(151, 187, 205, 0.75)',
                    highlightStroke: 'rgba(151, 187, 205, 1)',
                    data: data_accepted
                },
                    {
                        label: 'Ditolak',
                        backgroundColor: 'rgba(255, 0, 0, 0.8)',
                        borderColor: 'rgba(220, 220, 220, 0.8)',
                        highlightFill: 'rgba(220, 220, 220, 0.75)',
                        highlightStroke: 'rgba(220, 220, 220, 1)',
                        data: data_rejected
                    }]
            },
            options: {
                responsive: true
            }
        }); // eslint-disable-next-line no-unused-vars
    }

    function piechart(label_acc_type,number_acc_type){
        var pieChart = new Chart($('#canvas-5'), {
            type: 'pie',
            data: {
                labels: label_acc_type,
                datasets: [{
                    data: number_acc_type,
                    backgroundColor: ['#FF6384', '#43c9c9', '#688f8f', '#3caf85', '#309fdb', '#fbce4a', '#e95b54', '#f7915e', '#854e9b', '#2b4b71', '#2dc63e'],
                    hoverBackgroundColor: ['#FF6384', '#43c9c9', '#688f8f', '#3caf85', '#309fdb', '#fbce4a', '#e95b54', '#f7915e', '#854e9b', '#2b4b71', '#2dc63e']
                }]
            },
            options: {
                responsive: true
            }
        }); // eslint-disable-next-line no-unused-vars
    }

    function radarchart(label_accepted,number_offered,number_accepted){
        var radarChart = new Chart($('#canvas-4'), {
            type: 'bar',
            data: {
                labels: label_accepted,
                datasets: [{
                    label: 'Tawaran Sewaan',
                    backgroundColor: 'rgba(0, 179, 0, 1)',
                    borderColor: 'rgba(220, 220, 220, 1)',
                    pointBackgroundColor: 'rgba(220, 220, 220, 1)',
                    pointBorderColor: '#fff',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(220, 220, 220, 1)',
                    data: number_offered
                }, {
                    label: 'Setuju Terima',
                    backgroundColor: 'rgba(255, 0, 0, 0.8)',
                    borderColor: 'rgba(151, 187, 205, 1)',
                    pointBackgroundColor: 'rgba(151, 187, 205, 1)',
                    pointBorderColor: '#fff',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(151, 187, 205, 1)',
                    data: number_accepted
                }]
            },
            options: {
                responsive: true
            }
        }); // eslint-disable-next-line no-unused-vars
    }

    load_dashboard();
</script>
