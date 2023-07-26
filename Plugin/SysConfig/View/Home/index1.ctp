
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<script>
	jQuery(document).ready(function () {
		$('#myModal').modal('show');
	});
	</script>

	<style>

		.close {
                color: white;
                float: right;
                font-size: 28px;
                font-weight: bold;
		}
		
		.modal-header {
                padding: 2px 16px;
                background-color: #5cb85c;
                color: white;
		}

		.modal-body {padding: 2px 16px;}

		.modal-footer {
                padding: 2px 16px;
                background-color: #5cb85c;
                color: white;
		}
	</style>

<!-- Tampilan Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">Welcome</h3>
            </div>
            <div class="modal-body">
				<h2><?php echo strtoupper($this->Session->read('Auth.User.name'));?></h2>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<!-- batas Modal -->

        <?php 
            echo $this->Html->css('style_admin.css'); 
            echo $this->Html->css('bootstrap.css'); 
            echo $this->Html->css('plugins/font-awesome/css/font-awesome.min.css'); 
            echo $this->Html->script('highcharts/highcharts.js'); 
            echo $this->Html->script('highcharts/modules/series-label.js'); 
            echo $this->Html->script('highcharts/modules/exporting.js'); 
            echo $this->Html->script('highcharts/modules/export-data.js'); 
            echo $this->Html->script('highcharts/modules/accessibility.js'); 
        ?>

<?php $akses = $this->Session->read('Auth.User.type_akses');
      $array = array(0,5);
      if(in_array($akses, $array)){ ?>
    <h2>HALO SELAMAT DATANG</h2>
<?php } else { ?>

<div id="wrapper">

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- row 2-->
                <div class="row">

                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="panel panel-danger">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-pie-chart fa-fw"></i>CAPAIAN KINERJA TAHUN <?php echo date('Y')-1; ?></h3>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <figure class="highcharts-figure">
                                        <div id="containerr"></div>
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="panel panel-danger">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-pie-chart fa-fw"></i>CAPAIAN KINERJA TAHUN <?php echo (date('Y')); ?></h3>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <figure class="highcharts-figure">
                                        <div id="container"></div>
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

        </div>

    </div>
<?php } ?>

    <script>
        //TAHUN SEBELUMNYA
    Highcharts.chart('containerr', {
    title: {
        text: 'NILAI CAPAIAN KINERJA / BULAN'
    },
    xAxis: {
        categories: ['JAN','FEB','MAR','APR','MEI','JUN','JUL','AUG','SEPT','OKT','NOV','DES']
    },
    labels: {
        items: [{
            html: '',
            style: {
                left: '50px',
                top: '18px',
                color: ( // theme
                    Highcharts.defaultOptions.title.style &&
                    Highcharts.defaultOptions.title.style.color
                ) || 'black'
            }
        }]
    },
    series: [
        {
        type: 'column',
        name: 'NILAI',
        data: [<?php echo $nilai_postt; ?>]  
        },
    
        
        {
        type: 'spline',
        name: 'TREND NILAI CAPAIAN',
        data: [<?php echo $nilai_postt; ?>],
        marker: {
            lineWidth: 2,
            lineColor: Highcharts.getOptions().colors[3],
            fillColor: 'white'
            }
        }, 
    // {
    //     type: 'pie',
    //     name: 'Total consumption',
    //     data: [{
    //         name: 'Jane',
    //         y: 13,
    //         color: Highcharts.getOptions().colors[0] // Jane's color
    //     }, {
    //         name: 'John',
    //         y: 23,
    //         color: Highcharts.getOptions().colors[1] // John's color
    //     }, {
    //         name: 'Joe',
    //         y: 19,
    //         color: Highcharts.getOptions().colors[2] // Joe's color
    //     }],
    //     center: [60, 80],
    //     size: 100,
    //     showInLegend: false,
    //     dataLabels: {
    //         enabled: false
    //     }
    // }
]
});
 

 //TAHUN INI
Highcharts.chart('container', {
    title: {
        text: 'NILAI CAPAIAN KINERJA / BULAN'
    },
    xAxis: {
        categories: ['JAN','FEB','MAR','APR','MEI','JUN','JUL','AUG','SEPT','OKT','NOV','DES']
    },
    labels: {
        items: [{
            html: '',
            style: {
                left: '50px',
                top: '18px',
                color: ( // theme
                    Highcharts.defaultOptions.title.style &&
                    Highcharts.defaultOptions.title.style.color
                ) || 'black'
            }
        }]
    },
    series: [
        {
        type: 'column',
        name: 'NILAI',
        data: [<?php echo $nilai_post; ?>]  
        },
    
        
        {
        type: 'spline',
        name: 'TREND NILAI CAPAIAN',
        data: [<?php echo $nilai_post; ?>],
        marker: {
            lineWidth: 2,
            lineColor: Highcharts.getOptions().colors[3],
            fillColor: 'white'
            }
        }, 
    // {
    //     type: 'pie',
    //     name: 'Total consumption',
    //     data: [{
    //         name: 'Jane',
    //         y: 13,
    //         color: Highcharts.getOptions().colors[0] // Jane's color
    //     }, {
    //         name: 'John',
    //         y: 23,
    //         color: Highcharts.getOptions().colors[1] // John's color
    //     }, {
    //         name: 'Joe',
    //         y: 19,
    //         color: Highcharts.getOptions().colors[2] // Joe's color
    //     }],
    //     center: [60, 80],
    //     size: 100,
    //     showInLegend: false,
    //     dataLabels: {
    //         enabled: false
    //     }
    // }
]
});

</script>