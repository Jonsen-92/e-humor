<?php
    echo $this->Html->script('jquery.validate.min');
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

	#btn1 {
        background-color: #008CBA;
        /* font-size: 24px; */
        padding: 12px 28px;
        border-radius: 8px;
        color: white;
        margin-left:20px;
    }

    #btn1:hover {
        background-color: #4CAF50;
        color: white;
        box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24), 0 17px 50px 0 rgba(0,0,0,0.19);
    }

</style>

<script>
    jQuery(document).ready(function () {
		IzinTidakMasukKerja.init();
        $(':input[type="submit"]').prop('id', 'btn1');
	});

    var IzinTidakMasukKerja = function(){
        var handleValidation = function() {
            $('#IzinTidakMasukKerjaEditForm').validate({
                rules: {
                    "data[IzinTidakMasukKerja][tanggal_izin]":{required: true},
                    "data[IzinTidakMasukKerja][jam_mulai]":{required: true},
                    "data[IzinTidakMasukKerja][jam_akhir]": {required: true},
                    "data[IzinTidakMasukKerja][nip_pemberi_izin]":{required: true},
                    "data[IzinTidakMasukKerja][keterangan]":{required: true},
                },
                messages:{
                    "data[IzinTidakMasukKerja][tanggal_izin]" : {required: "Tanggal Izin Tidak Boleh Kosong"},
                    "data[IzinTidakMasukKerja][jam_mulai]" : {required: "Jam Mulai Tidak Boleh Kosong"},
                    "data[IzinTidakMasukKerja][jam_akhir]": {required:"Jam Akhir Cuti Tidak Boleh Kosong"},
                    "data[IzinTidakMasukKerja][nip_pemberi_izin]":{required: "Atasan Cuti Tidak Boleh Kosong"},
                    "data[IzinTidakMasukKerja][keterangan]":{required: "Keperluan Tidak Boleh Kosong"},
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

    $(document).ready(function () {
	    $('#IzinTidakMasukKerjaTanggalMulai').datepicker({
	       	dateFormat: "dd-mm-yy",
            changeMonth: true,
            changeYear: true,
            minDate:0 
	    });

        $('#IzinTidakMasukKerjaTanggalAkhir').datepicker({
	       	dateFormat: "dd-mm-yy",
            changeMonth: true,
            changeYear: true,
            minDate:0 
	    });

    })

    function validasiTgl(){
        var tglMulai = $('#IzinTidakMasukKerjaTanggalMulai').val();
        var tglAkhir = $('#IzinTidakMasukKerjaTanggalAkhir').val();

        if(tglAkhir < tglMulai){
            alert('Tanggal Berakhir Izin tidak boleh lebih kecil dari tanggal mulai');
            $('#IzinTidakMasukKerjaTanggalAkhir').val('').focus();
        }
    }

    function dataAtasan(val){
        $.getJSON('<?php echo $url; ?>/proses/izin_keluar_kantors/getDataAtasan/'+val, function(data){
            $('#IzinTidakMasukKerjaJabatanPemberiIzin').val(data.Pegawai.jabatan);
            $('#IzinTidakMasukKerjaNamaPemberiIzin').val(data.Pegawai.nama);
        })
    }
</script>

<h3><b>Edit Izin Keluar Kantor</b></h3>
<br>

<?php echo (isset($data['option']))?$this->Form->create($model, $data['option']):$this->Form->create($model,array('type' => 'file','onsubmit'=>"", 'class'=>'form-horizontal'));
?>

<div class="col-md-6">
    <?php 

        echo $this->Form->input('nip_pemohon', array('label' => array('text' => 'NIP Pemohon', 'class' => 'col-sm-3 control-label'),'type' => 'text','class' => 'form-control','value'=>$data[$model]['nip_pemohon'],'readonly', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('nama_pemohon', array('label' => array('text' => 'Nama Pemohon', 'class' => 'col-sm-3 control-label'),'type' => 'text', 'class' => 'form-control','value'=>$data[$model]['nama_pemohon'],'readonly', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('tanggal_mulai', array('label' => array('text' => 'Tanggal Mulai', 'class' => 'col-md-3 control-label'),'type' => 'text','value'=>$data[$model]['tanggal_mulai'], 'onchange'=>'validasiTgl()' ,'class' => 'form-control','readonly', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('tanggal_akhir', array('label' => array('text' => 'Tanggal Akhir', 'class' => 'col-md-3 control-label'),'type' => 'text','value'=>$data[$model]['tanggal_akhir'],'onchange'=>'validasiTgl()' ,'class' => 'form-control','readonly', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('nip_pemberi_izin', array('label' => array('text' => 'Nama Atasan', 'class' => 'col-md-3 control-label'),'type' => 'select','value'=>$data[$model]['nip_pemberi_izin'], 'class' => 'form-control','options'=>$dataAtasan,'readonly', 'onchange'=>'dataAtasan(this.value)', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('nama_pemberi_izin', array('label' => array('text' => 'Nama Atasan', 'class' => 'col-md-3 control-label'),'type' => 'hidden', 'value'=>$data[$model]['nama_pemberi_izin'],'class' => 'form-control','readonly', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('jabatan_pemberi_izin', array('label' => array('text' => 'Jabatan Atasan', 'class' => 'col-md-3 control-label'),'type' => 'text','value'=>$data[$model]['jabatan_pemberi_izin'], 'class' => 'form-control','readonly', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('keterangan', array('label' => array('text' => 'Keperluan', 'class' => 'col-md-3 control-label'),'type' => 'textarea','value'=>$data[$model]['keterangan'], 'class' => 'form-control', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

    ?>
</div>

<br>
<?php echo $this->Form->end(__((isset($button)?$button:'EDIT'), true));?>