<?php
    echo $this->Html->script('thickbox');
    echo $this->Html->css('jquery.thickbox');
    echo $this->Html->script('jquery.validate.min');
    echo $this->Html->script('jquery.maskedinput');
	echo $this->Html->script('jquery-ui-1.8.9.autocomplete.min');
	echo $this->Html->script('jquery.validate.min.js');
	// echo $this->Html->script('big.min.js');
	echo $this->Html->script('jquery-ui.js');
	echo $this->Html->script('jquery.ui.datepicker');
	echo $this->Html->script('sweetalert2.min.js');
	echo $this->Html->css('sweetalert2.min.css');
?>
<?php if (isset($addjs)) echo $this->Html->script($addjs);?>

<style>
	.input .col-sm-7 span {
        color:red;
    }
  	.input .col-sm-8 span {
        color:red;
    }
    .input .col-sm-9 span {
    	color: red;
    }

	.btn1 {
        background-color: #008CBA;
        font-size: 24px;
        padding: 12px 28px;
        border-radius: 8px;
        color: white;
        margin-left:20px;
    }

    .btn1:hover {
        background-color: #4CAF50;
        color: white;
        box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24), 0 17px 50px 0 rgba(0,0,0,0.19);
    }

</style>

<script type="text/javascript">
    jQuery(document).ready(function () {
        hitungCuti();
		PegawaiAdd.init();
        $(':input[type="submit"]').prop('class', 'btn1');
    });

        var PegawaiAdd = function(){
        var handleValidation = function() {
            $('#DataCutiPegawaiAddForm').validate({
                rules: {
                    "data[DataCutiPegawai][nip]":{required: true},
                    "data[DataCutiPegawai][tahun]":{required: true},
                    "data[DataCutiPegawai][sisa_cuti_tahun_lalu]":{required: true, number: true, maxlength:2},
                    "data[DataCutiPegawai][cuti_ditangguhkan]":{required: true, number: true, maxlength:2},
                    "data[DataCutiPegawai][jumlah_cuti_tahun_berjalan]":{required: true, number: true, maxlength:2},
                    "data[DataCutiPegawai][jumlah_cuti_sudah_diambil]":{required: true, number: true, maxlength:2}
                },
                messages:{
                    "data[DataCutiPegawai][nip]":{required: "NIP tidak boleh kosong"},
                    "data[DataCutiPegawai][tahun]":{required: "Tahun tidak boleh kosong"},
                    "data[DataCutiPegawai][sisa_cuti_tahun_lalu]" : {required: "Sisa Cuti Tahun Lalu Tidak Boleh Kosong", number: "input hanya angka saja", maxlength: "maksimal 2 digit"},
                    "data[DataCutiPegawai][cuti_ditangguhkan]" : {required: "Cuti Ditanguhkan Tidak Boleh Kosong", number: "input hanya angka saja", maxlength: "maksimal 2 digit"},
                    "data[DataCutiPegawai][jumlah_cuti_tahun_berjalan]" : {required: "Jumlah Cuti Tahun Ini Tidak Boleh Kosong", number: "input hanya angka saja", maxlength: "maksimal 2 digit"},
                    "data[DataCutiPegawai][jumlah_cuti_sudah_diambil]" : {required: "Jumlah Cuti Sudah Terpakai Tidak Boleh Kosong", number: "input hanya angka saja", maxlength: "maksimal 2 digit"}
                },
                errorElement: 'span',
                errorPlacement: function (error, element)
                // {error.appendTo(element.closest('.input').find('.control-label'));}
                {
            element.after(error);

            $(".required").removeClass('required');
        }
            });
        };
        return {
            init: function() {
                handleValidation();
            }
        };
    }();

    function hitungCuti(){
        var CutiTL = Number($('#DataCutiPegawaiSisaCutiTahunLalu').val());
        var CutiTG = Number($('#DataCutiPegawaiCutiDitangguhkan').val());
        var CutiTI = Number($('#DataCutiPegawaiJumlahCutiTahunBerjalan').val());
        var CutiSD = Number($('#DataCutiPegawaiJumlahCutiSudahDiambil').val());

        sisaCuti = CutiTL+CutiTG+CutiTI-CutiSD;
        $('#DataCutiPegawaiSisaCutiTahunBerjalan').val(sisaCuti);
    }

    function validasiCuti()
    {
        var nip = $('#DataCutiPegawaiNip').val();
        var tahun = $('#DataCutiPegawaiTahun').val();
        $.getJSON('<?php echo $url; ?>/proses/data_cuti_pegawais/getData/'+nip+'/'+tahun, function(data){
            if(data){
                Swal.fire(
							'Data Cuti untuk tahun '+tahun+' sudah ada',
							'Silahkan pilih edit jika ada perubahan',
							'question'
						)
                $('#DataCutiPegawaiTahun').val('');
            }
        });
    }

</script>

<h3><b>Form Data Cuti Pegawai</b></h3>
<br>

<?php echo (isset($data['option']))?$this->Form->create($model, $data['option']):$this->Form->create($model,array('onsubmit'=>"", 'class'=>'form-horizontal'));
?>

<div class="col-md-6">
    <?php 
        echo $this->Form->input('nip', array('label' => array('text' => 'N I P', 'class' => 'col-sm-3 control-label'),'type' => 'text','class' => 'form-control','readonly', 'between' => '<div class="col-sm-7">', 'after' => '</div>'));
    ?>
        <input class="thickbox" style="width:50px;height:33px" id="LovNip" rel="./lovNip/addscript:validasiCuti()/pid:DataCutiPegawaiNip/pname:DataCutiPegawaiNama/pmodel:Pegawai" alt="./lovNip/addscript:validasiCuti()/pid:DataCutiPegawaiNip/pname:DataCutiPegawaiNama/pmodel:Pegawai" type="button" value="..." role="textbox" aria-autocomplete="list" aria-haspopup="true">
    <?php 
        $thn[''] = 'Pilih Tahun Cuti';
        for($tahun=date('Y');$tahun>=date('Y')-3;$tahun--){
            $thn[$tahun] = $tahun;
        }
        echo $this->Form->input('nama', array('label' => array('text' => 'Nama Pegawai', 'class' => 'col-sm-3 control-label'),'type' => 'text', 'class' => 'form-control','readonly', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('tahun', array('label' => array('text' => 'Tahun', 'class' => 'col-sm-3 control-label'),'type' => 'select','options'=>$thn,'onchange'=>'validasiCuti()', 'class' => 'form-control','readonly', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('sisa_cuti_tahun_lalu', array('label' => array('text' => 'Sisa Cuti (N-1)', 'class' => 'col-sm-3 control-label'),'type' => 'text','oninput'=>'hitungCuti()', 'class' => 'form-control','value'=>6, 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('cuti_ditangguhkan', array('label' => array('text' => 'Jumlah Cuti yang Ditangguhkan', 'class' => 'col-md-3 control-label'),'type' => 'text','oninput'=>'hitungCuti()','value'=>0,'min'=>0,'class' => 'form-control', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));
        
        echo $this->Form->input('jumlah_cuti_tahun_berjalan', array('label' => array('text' => 'Jumlah Cuti Tahun Ini', 'class' => 'col-md-3 control-label'),'type' => 'text','oninput'=>'hitungCuti()', 'min'=>0,'class' => 'form-control', 'value'=>12, 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('jumlah_cuti_sudah_diambil', array('label' => array('text' => 'Jumlah Cuti Sudah Terpakai', 'class' => 'col-md-3 control-label'),'type' => 'text','oninput'=>'hitungCuti()','value'=>0, 'min'=>0,'class' => 'form-control',  'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('sisa_cuti_tahun_berjalan', array('label' => array('text' => 'Total Sisa Cuti', 'class' => 'col-md-3 control-label'),'type' => 'text','value'=>0, 'readonly', 'min'=>0,'class' => 'form-control', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));
    ?>
</div>
<br>
<?php echo $this->Form->end(__((isset($button)?$button:'SUBMIT'), true));?>