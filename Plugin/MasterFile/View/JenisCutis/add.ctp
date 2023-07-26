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

	.btn {
        padding: 18px 34px 18ps 34px;
        font-size: 18px;
        font-weight: 700;
        display: inline-block;
        margin-left: 20px;
        margin-bottom: 24px;
        margin-top: 24px;
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

<script type="text/javascript">
    jQuery(document).ready(function () {
		JenisCutiAdd.init();
        $(':input[type="submit"]').prop('class', 'btn btn-danger');
	});

	var JenisCutiAdd = function(){
        var handleValidation = function() {
            $('#JenisCutiAddForm').validate({
                rules: {
                    "data[JenisCuti][lama_hari]": {required: true, number:true, maxlength:2},
                    "data[JenisCuti][kode_cuti]": {required: true},
                },
                messages:{
                    "data[JenisCuti][lama_hari]" : {required: "Jumlah Hari Tidak Boleh Kosong", number: "input hanya angka saja", maxlength: "maksimal 2 digit"},
                    "data[JenisCuti][kode_cuti]" : {required: "Silahkan Pilih Kode Cuti"}
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

    function validateCuti(){
        var val = $('#JenisCutiKodeCuti').val();
        if(val == 'CT'){
            desc = 'Cuti Tahunan';
        }
        else if(val == 'CB'){
            desc = 'Cuti Besar';
        }
        else if(val == 'CS'){
            desc = 'Cuti Sakit';
        }
        else if(val == 'CM'){
            desc = 'Cuti Melahirkan';
        }
        else if(val == 'CAP'){
            desc = 'Cuti Karena Alasan Penting';
        }
        else if(val == 'CTLN'){
            desc = 'Cuti Cuti Diluar Tanggungan Negara';
        }

        var val = $('#JenisCutiNamaCuti').val(desc);
    }

    function validateExist(val){
        // var val = $('#JenisCutiKodeCuti').val();
        $.getJSON('/edaran/master_file/jenis_cutis/getCuti/'+val, function(data){
			if(data){
				Swal.fire(
							'Kode Cuti '+val+' sudah terdaftar',
							'Silahkan update data cuti jika ada perubahan',
							'question'
						)
                $('#JenisCutiLamaHari').val('');
			}
		})
    }

</script>

<h3><b>Form Jenis Cuti</b></h3>
<br>

<?php echo (isset($data['option']))?$this->Form->create($model, $data['option']):$this->Form->create($model,array('onsubmit'=>"return validateExist()", 'class'=>'form-horizontal'));
?>

<div class="col-md-6">
<?php
	echo $this->Form->input('kode_cuti', array('label' => array('text' => 'Kode Cuti', 'class' => 'col-sm-3 control-label'),'type' => 'select','options'=>$jenisCuti,'onchange'=>'validateCuti(this.value);validateExist(this.value)','class' => 'form-control', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

	echo $this->Form->input('nama_cuti', array('label' => array('text' => 'Nama Cuti', 'class' => 'col-sm-3 control-label'),'type' => 'text','readonly', 'class' => 'form-control', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

    echo $this->Form->input('lama_hari', array('label' => array('text' => 'Jumlah (Hari)', 'class' => 'col-sm-3 control-label'),'type' => 'text', 'class' => 'form-control', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

?>

</div>
<br>
<?php echo $this->Form->end(__((isset($button)?$button:'SUBMIT'), true));?>