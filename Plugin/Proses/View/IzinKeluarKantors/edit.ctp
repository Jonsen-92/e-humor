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
		IzinKeluarKantor.init();
        $(':input[type="submit"]').prop('id', 'btn1');
	});

    var IzinKeluarKantor = function(){
        var handleValidation = function() {
            $('#IzinKeluarKantorEditForm').validate({
                rules: {
                    "data[IzinKeluarKantor][tanggal_izin]":{required: true},
                    "data[IzinKeluarKantor][jam_mulai]":{required: true},
                    "data[IzinKeluarKantor][jam_akhir]": {required: true},
                    "data[IzinKeluarKantor][nip_pemberi_izin]":{required: true},
                    "data[IzinKeluarKantor][keterangan]":{required: true},
                },
                messages:{
                    "data[IzinKeluarKantor][tanggal_izin]" : {required: "Tanggal Izin Tidak Boleh Kosong"},
                    "data[IzinKeluarKantor][jam_mulai]" : {required: "Jam Mulai Tidak Boleh Kosong"},
                    "data[IzinKeluarKantor][jam_akhir]": {required:"Jam Akhir Cuti Tidak Boleh Kosong"},
                    "data[IzinKeluarKantor][nip_pemberi_izin]":{required: "Atasan Cuti Tidak Boleh Kosong"},
                    "data[IzinKeluarKantor][keterangan]":{required: "Keperluan Tidak Boleh Kosong"},
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
	    $('#IzinKeluarKantorTanggalIzin').datepicker({
	       	// dateFormat: "dd-mm-yy",
            changeMonth: true,
            changeYear: true,
            minDate:0 
	    });

    })

    function validasiJam(val){
        var jamMulai = $('#IzinKeluarKantorJamMulai').val();console.log(jamMulai);
        var jamAkhir = $('#IzinKeluarKantorJamAkhir').val();

        if(jamAkhir){
            if(jamAkhir < jamMulai){
                alert('Jam Akhir tidak boleh lebih kecil dari jam mulai');
                $('#IzinKeluarKantorJamMulai').val('');
                $('#IzinKeluarKantorJamAkhir').val('');
            }
        }
    }

    function dataAtasan(val){
        $.getJSON('<?php echo $url; ?>/proses/izin_keluar_kantors/getDataAtasan/'+val, function(data){
            $('#IzinKeluarKantorJabatanPemberiIzin').val(data.Pegawai.jabatan);
            $('#IzinKeluarKantorNamaPemberiIzin').val(data.Pegawai.nama);
        })
    }
</script>

<h3><b>Edit Izin Keluar Kantor</b></h3>
<br>

<?php echo (isset($data['option']))?$this->Form->create($model, $data['option']):$this->Form->create($model,array('type' => 'file','onsubmit'=>"", 'class'=>'form-horizontal'));
?>

<div class="col-md-6">
    <?php 
        $jam = array(''=>'Pilih Jam',
                     '07:30'=>'07:30',
                     '08:00'=>'08:00',
                     '08:30'=>'08:30',
                     '09:00'=>'09:00',
                     '09:30'=>'09:30',
                     '10:00'=>'10:00',
                     '10:30'=>'10:30',
                     '11:00'=>'11:00',
                     '11:30'=>'11:30',
                     '12:00'=>'12:00',
                     '13:00'=>'13:00',
                     '13:30'=>'13:30',
                     '14:00'=>'14:00',
                     '14:30'=>'14:30',
                     '15:00'=>'15:00',
                     '15:30'=>'15:30',
                     '16:00'=>'16:00',
                     '16:30'=>'16:30');

        echo $this->Form->input('nip_pemohon', array('label' => array('text' => 'NIP Pemohon', 'class' => 'col-sm-3 control-label'),'type' => 'text','class' => 'form-control','value'=>$data[$model]['nip_pemohon'],'readonly', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('nama_pemohon', array('label' => array('text' => 'Nama Pemohon', 'class' => 'col-sm-3 control-label'),'type' => 'text', 'class' => 'form-control','value'=>$data[$model]['nama_pemohon'],'readonly', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('tanggal_izin', array('label' => array('text' => 'Tanggal Izin', 'class' => 'col-md-3 control-label'),'type' => 'text', 'class' => 'form-control','value'=>$data[$model]['tanggal_izin'],'readonly', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('jam_mulai', array('label' => array('text' => 'Dari Jam', 'class' => 'col-md-3 control-label'),'type' => 'select','value'=>$data[$model]['jam_mulai'], 'options'=>$jam,'onchange'=>'validasiJam()' ,'class' => 'form-control','readonly', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('jam_akhir', array('label' => array('text' => 'Sampai Jam', 'class' => 'col-md-3 control-label'),'type' => 'select','value'=>$data[$model]['jam_akhir'], 'options'=>$jam,'onchange'=>'validasiJam()' ,'class' => 'form-control','readonly', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('nip_pemberi_izin', array('label' => array('text' => 'Nama Atasan', 'class' => 'col-md-3 control-label'),'type' => 'select','value'=>$data[$model]['nip_pemberi_izin'], 'class' => 'form-control','options'=>$dataAtasan,'readonly', 'onchange'=>'dataAtasan(this.value)', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('nama_pemberi_izin', array('label' => array('text' => 'Nama Atasan', 'class' => 'col-md-3 control-label'),'type' => 'hidden', 'value'=>$data[$model]['nama_pemberi_izin'],'class' => 'form-control','readonly', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('jabatan_pemberi_izin', array('label' => array('text' => 'Jabatan Atasan', 'class' => 'col-md-3 control-label'),'type' => 'text','value'=>$data[$model]['jabatan_pemberi_izin'], 'class' => 'form-control','readonly', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('keterangan', array('label' => array('text' => 'Keperluan', 'class' => 'col-md-3 control-label'),'type' => 'textarea','value'=>$data[$model]['keterangan'], 'class' => 'form-control', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

    ?>
</div>

<br>
<?php echo $this->Form->end(__((isset($button)?$button:'EDIT'), true));?>