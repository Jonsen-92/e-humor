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
		PegawaiAdd.init();
        $(':input[type="submit"]').prop('class', 'btn1');
	});

	var PegawaiAdd = function(){
        var handleValidation = function() {
            $('#PegawaiAddForm').validate({
                rules: {
                    "data[Pegawai][nip]":{required: true, number: true, maxlength:18},
                    "data[Pegawai][nama]":{required: true},
                    "data[Pegawai][no_hp_wa]": {required: true, number:true},
                    "data[Pegawai][jabatan]":{required: true},
                    "data[Pegawai][pangkat_golongan]":{required: true},
                    "data[Pegawai][unit_kerja]":{required: true}
                },
                messages:{
                    "data[Pegawai][nip]" : {required: "NIP Pegawai Tidak Boleh Kosong", number: "input hanya angka saja", maxlength: "maksimal 18 digit"},
                    "data[Pegawai][nama]" : {required: "Nama Tidak Boleh Kosong"},
                    "data[Pegawai][no_hp_wa]": {required:"Nomor Hp/WA Tidak Boleh Kosong", number:"Input hanya angka saja"},
                    "data[Pegawai][jabatan]":{required: "Jabatan Tidak Boleh Kosong"},
                    "data[Pegawai][pangkat_golongan]":{required: "Pangkat/Golongan Tidak Boleh Kosong"},
                    "data[Pegawai][unit_kerja]":{required: "Instansi Tidak Boleh Kosong"}
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

	function validateNip()
	{
		var nip = $('#PegawaiNip').val();
		$.getJSON('<?php echo $url; ?>/master_file/pegawais/getNip/'+nip, function(data){
			if(data){
				Swal.fire(
							'NIP '+nip+' sudah terdaftar',
							'Silahkan update data pegawai jika ada perubahan',
							'question'
						)
				$('#PegawaiNip').val('');
			}
		})
	}

    function changeJenis(val){
        if(val == 'PPNPN'){
            $('#PegawaiJenisPegawai').val(val);
        }
        else if(val == 'PPPK'){
            $('#PegawaiJenisPegawai').val('PPPK');
        }
        else{
            $('#PegawaiJenisPegawai').val('ASN');
        }
    }

    $(document).ready(function () {
	    $('#PegawaiTmtCpns').datepicker({
	       	dateFormat: "dd-mm-yy",
            changeMonth: true,
            changeYear: true,
            maxDate:0 
	    });
    })

    function cek_file(id){
        inputFile = document.getElementById(id);
        pathFile = inputFile.value;
        ekstensi = /(\.jpg|\.png|\.jpeg)$/i;
        ukuran = inputFile.files[0].size;
        // console.log(pathFile);
        // console.log(inputFile.files[0].name);
        // console.log(inputFile.files);

        //ekstensi .jpg atau .png dan ukuran file kurang dari 500 Kb (512000 byte)
        if(ekstensi.exec(pathFile) && ukuran <= 512000){
        // console.log('cocok');

        //untuk load gambar
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(id+'Img').innerHTML = '<img src="'+e.target.result+'" style="width:150px"/>';
            // document.getElementById(id+'Img').innerHTML = '<img src="'+e.target.result+'" style="width:350px"/>';
        };
        reader.readAsDataURL(inputFile.files[0]);
        }
        else{
        alert('Silakan pilih file .jpg atau .png dengan ukuran maksimal 500 Kb');
        document.getElementById(id).value = '';
        return false;
        }
    }

</script>



<h3><b>Form Data Pegawai</b></h3>
<br>

<?php echo (isset($data['option']))?$this->Form->create($model, $data['option']):$this->Form->create($model,array('type' => 'file', 'onsubmit'=>"", 'class'=>'form-horizontal'));
?>

<div class="col-md-6">
<?php
    echo $this->Form->input('jenis_pegawai', array('label' => array('text' => 'Jenis Pegawai', 'class' => 'col-sm-3 control-label'),'type' => 'hidden','readonly','class' => 'form-control', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

	echo $this->Form->input('nip', array('label' => array('text' => 'N I P / N I K', 'class' => 'col-sm-3 control-label','id'=>'labelNip'),'type' => 'text','onchange'=>'validateNip(this.value)','class' => 'form-control', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

	echo $this->Form->input('nama', array('label' => array('text' => 'Nama Pegawai', 'class' => 'col-sm-3 control-label'),'type' => 'text', 'class' => 'form-control', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

    echo $this->Form->input('jabatan', array('label' => array('text' => 'Jabatan', 'class' => 'col-sm-3 control-label'),'type' => 'text', 'class' => 'form-control', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));
	
	echo $this->Form->input('pangkat_golongan', array('label' => array('text' => 'Pangkat/Golongan', 'class' => 'col-md-3 control-label','id'=>'labelPangkat'),'type' => 'select','options'=>$pangkat,'onchange'=>'changeJenis(this.value)','readonly', 'class' => 'form-control', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

    echo $this->Form->input('tmt_cpns', array('label' => array('text' => 'TMT CPNS', 'class' => 'col-md-3 control-label','id'=>'labelTmt'),'type' => 'text', 'class' => 'form-control', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

	echo $this->Form->input('unit_kerja', array('label' => array('text' => 'Instansi', 'class' => 'col-md-3 control-label'),'type' => 'text', 'class' => 'form-control', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

	echo $this->Form->input('email', array('label' => array('text' => 'Email', 'class' => 'col-md-3 control-label'),'type' => 'text', 'class' => 'form-control', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

	echo $this->Form->input('no_hp_wa', array('label' => array('text' => 'No. HP (WA)', 'class' => 'col-md-3 control-label'),'type' => 'text','class' => 'form-control', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

	echo $this->Form->input('status_pegawai', array('label' => array('text' => 'Status Pegawai', 'class' => 'col-md-3 control-label'),'type' => 'select','options'=>array('AKTIF'=>'AKTIF','NON AKTIF'=>'NON AKTIF'),'class' => 'form-control', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

    echo $this->Form->input('ttd', array('label' => array('text' => 'QR Ttd', 'class' => 'col-sm-3 control-label'), 'type' => 'file', 'accept'=>'image/*', 'onchange'=>'cek_file(id)', 'class' => 'form-control', 'between'=>'<div class="col-sm-8">','after'=>'<div id="PegawaiTtdImg"></div></div>'));

?>

</div>
<br>
<?php echo $this->Form->end(__((isset($button)?$button:'SUBMIT'), true));?>
