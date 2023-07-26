
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
		
		/* .modal-header {
                padding: 2px 16px;
                background-color: #5cb85c;
                color: white;
		}

		.modal-body {padding: 2px 16px;} */

		.modal-footer {
                padding: 2px 16px;
                /* background-color: #00FFFF; */
                /* color: white; */
		}

        img {
            width: 110%;
            margin: 0 auto;
        }

        .btn {
            padding: 18px 34px;
            font-size: 18px;
            font-weight: 700;
            display: inline-block;
            margin-right: 24px;
            margin-bottom: 24px;
            color: #fff;
            background-color: #ec6964;
            border-color: #ec6964;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            line-height: 1.5;
            border-radius: 0.25;
        }
	</style>

<!-- Tampilan Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">Welcome Back</h3>
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


<div id="wrapper">

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- row 2-->
                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel panel-danger">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-calendar fa-fw"></i> SISTEM APLIKASI CUTI ONLINE</h3>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <a href="proses/pengajuan_cutis/add" class="btn">AJUKAN CUTI SEKARANG</a>
                                    <figure class="highcharts-figure">
                                        <img src="img/icon_cuti2.png" alt="" srcset="">
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

        </div>

    </div>

    