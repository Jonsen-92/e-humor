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


<script type="text/javascript">
    jQuery(document).ready(function () {
		PengajuanCuti.init();
        $(':input[type="submit"]').prop('id', 'btn1');
	});

	var PengajuanCuti = function(){
        var handleValidation = function() {
            $('#PengajuanCutiAddForm').validate({
                rules: {
                    "data[PengajuanCuti][nip_atasan]":{required: true},
                    "data[PengajuanCuti][kode_jenis_cuti]":{required: true},
                    "data[PengajuanCuti][dari_tanggal]": {required: true},
                    "data[PengajuanCuti][sampai_tanggal]":{required: true},
                    "data[PengajuanCuti][alasan_cuti]":{required: true},
                    "data[PengajuanCuti][alamat_menjalankan_cuti]":{required: true}
                },
                messages:{
                    "data[PengajuanCuti][nip_atasan]" : {required: "Atasan Tidak Boleh Kosong"},
                    "data[PengajuanCuti][kode_jenis_cuti]" : {required: "Jenis Cuti Tidak Boleh Kosong"},
                    "data[PengajuanCuti][dari_tanggal]": {required:"Tanggal Mulai Cuti Tidak Boleh Kosong"},
                    "data[PengajuanCuti][sampai_tanggal]":{required: "Tanggal Berakhir Cuti Tidak Boleh Kosong"},
                    "data[PengajuanCuti][alasan_cuti]":{required: "Alasan Tidak Boleh Kosong"},
                    "data[PengajuanCuti][alamat_menjalankan_cuti]":{required: "Alamat Menjalani Cuti Tidak Boleh Kosong"}
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
        sisaCuti(0);
        cutiSakit(0);
	    $('#PengajuanCutiDariTanggal').datepicker({
	       	// dateFormat: "dd-mm-yy",
            changeMonth: true,
            changeYear: true,
            minDate:0 
	    });

        $('#PengajuanCutiSampaiTanggal').datepicker({
	       	// dateFormat: "dd-mm-yy",
            changeMonth: true,
            changeYear: true,
            minDate: 0,
            maxDate: "+4m" 
	    });
    })

    function sisaCuti(val)
    {
        var data = 0;
        $.getJSON('<?php echo $url; ?>/proses/pengajuan_cutis/getNamaCuti/'+val, function(desc){
            $('#PengajuanCutiDescJenisCuti').val(desc);
		})

        if(val == 'CT'){
            var nip = $('#PengajuanCutiNip').val();
		    $.getJSON('<?php echo $url; ?>/proses/pengajuan_cutis/getSisaCuti/'+nip, function(data){
                if(data){
                    $('#PengajuanCutiSisaCuti').val(data+' Hari').show();
                    $('#labelsisacuti').show();
                    $('#PengajuanCutiSisaCuti2').val(data)
                    validasiSisaCuti();
                }
                else{
                    Swal.fire(
							'Data Jumlah Cuti anda belum tersedia',
							'Silahkan hubungi bagian Kepegawaian anda untuk menginput data cuti anda',
							'question'
						)
                    document.getElementById("btn1").disabled = true;
                }
		    })
        }
        else{
            $('#PengajuanCutiSisaCuti').val(data+' Hari').hide();
            $('#labelsisacuti').hide();
        }

        // cutiSakit(val);
    }

    function selisih(){
        var tglMulai = $('#PengajuanCutiDariTanggal').val();
	    var tglAkhir = $('#PengajuanCutiSampaiTanggal').val();
        
        var a = new Date(tglMulai);
        var b = new Date(tglAkhir);
	    var jarakWaktu = b.getTime() - a.getTime();
	    var jarakHari = jarakWaktu / (1000 * 3600 * 24) + 1;
        var weekend = 0;

        if(tglAkhir < tglMulai){
            alert('Tanggal Berakhir Cuti tidak boleh lebih kecil dari tanggal mulai');
            $('#PengajuanCutiJumlahCuti').val('');
            $('#PengajuanCutiSampaiTanggal').val('').focus();
        }
        else{
            const dString = tglMulai;
            let [month, day, year] = dString.split('/');

            // month - 1 as month in the Date constructor is zero indexed
            const now = new Date(year, month - 1, day - 1);

            let loopDay = now;
            for (let i = 1; i <= jarakHari; i++) {
                loopDay.setDate(loopDay.getDate()+1);
                c = new Date(loopDay).getDay();
                if(c == 6 || c == 0){
                    weekend += 1;
                }
            }
            jumlahCuti = jarakHari - weekend;
            $('#PengajuanCutiJumlahCuti').val(jumlahCuti+' Hari Kerja');
            $('#PengajuanCutiJumlahCuti2').val(jumlahCuti);
        }
        validasiSisaCuti();
    }

    function validasiSisaCuti(){
        var sisa_cuti = Number($('#PengajuanCutiSisaCuti2').val());console.log(sisa_cuti);
        var jumlah_aju = Number($('#PengajuanCutiJumlahCuti2').val());console.log(jumlah_aju);
        var jenisCuti = $('#PengajuanCutiKodeJenisCuti').val();

        if(jenisCuti == 'CT'){
            if(sisa_cuti < jumlah_aju){
                Swal.fire(
							'Jumlah Pengajuan lebih besar dari Sisa cuti',
							'Silahkan isikan tidak melebihi sisa cuti anda',
							'question'
						)
                        $('#PengajuanCutiDariTanggal').val('');
                        $('#PengajuanCutiSampaiTanggal').val('');
            }
        }
    }

    function cek_file(id){
        inputFile = document.getElementById(id);
        pathFile = inputFile.value;
        ekstensi = /(\.pdf|\.jpg|\.png|\.jpeg)$/i;
        ekstensi2 = /(\.jpg|\.png|\.jpeg)$/i;
        ukuran = inputFile.files[0].size;
        // console.log(pathFile);
        // console.log(inputFile.files[0].name);
        // console.log(inputFile.files);

        //ekstensi .jpg atau .png dan ukuran file kurang dari 500 Kb (512000 byte)
        if(ekstensi.exec(pathFile) && ukuran <= 512000){
        // console.log('cocok');

        //untuk load gambar
            if(ekstensi2.exec(pathFile)){
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('SuratSakit').innerHTML = '<img src="'+e.target.result+'" style="width:150px"/>';
                };
                reader.readAsDataURL(inputFile.files[0]);
            }
            else{
                document.getElementById('SuratSakit').innerHTML = '';
            }
        }
        else{
        alert('Silakan pilih file .jpg atau .png atau .pdf dengan ukuran maksimal 500 Kb');
        document.getElementById(id).value = '';
        return false;
        }
    }

    function cutiSakit(val){console.log(val);
        if(val == 'CS'){
            $('#PengajuanCutiSuratSakit').show();
            $('#labelSuratSakit').show();
        }
        else{
            $('#PengajuanCutiSuratSakit').hide();
            $('#labelSuratSakit').hide();
        }
    }
</script>

<h3><b>Form Pengajuan Cuti</b></h3>
<br>

<?php echo (isset($data['option']))?$this->Form->create($model, $data['option']):$this->Form->create($model,array('type' => 'file','onsubmit'=>"", 'class'=>'form-horizontal'));
?>

<div class="col-md-6">
    <?php 
        $jabatanPPK = array('KPN'=>'Ketua','WKPN'=>'Wakil Ketua','KPA'=>'KPA','Plh.KPN'=>'Ketua (Pelaksana Harian)','Plt.KPN'=>'Ketua (Pelaksana Tugas)','Plh.KPA'=>'KPA (Pelaksana Harian)','Plt.KPA'=>'KPA (Pelaksana Tugas)');
        echo $this->Form->input('nip', array('label' => array('text' => 'N I P', 'class' => 'col-sm-3 control-label'),'type' => 'text','class' => 'form-control','value'=>$dataUser['nip'],'readonly', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('nama', array('label' => array('text' => 'Nama Pegawai', 'class' => 'col-sm-3 control-label'),'type' => 'text', 'class' => 'form-control','value'=>$dataUser['name'],'readonly', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('jabatan', array('label' => array('text' => 'Jabatan', 'class' => 'col-sm-3 control-label'),'type' => 'text', 'class' => 'form-control','value'=>$dataUser['jabatan'],'readonly', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        if($this->Session->read('Auth.User') == 'ASN'){
            echo $this->Form->input('tmt_cpns_tahun', array('label' => array('text' => 'TMT (tahun)', 'class' => 'col-md-3 control-label'),'type' => 'hidden','readonly', 'value'=>$masaKerja->y,'class' => 'form-control', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));
            
            echo $this->Form->input('masa_kerja', array('label' => array('text' => 'Masa Kerja', 'class' => 'col-md-3 control-label'),'type' => 'text', 'class' => 'form-control', 'readonly','value'=>$masaKerja->y.' Tahun '.$masaKerja->m.' Bulan', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));
        }

        echo $this->Form->input('unit_kerja', array('label' => array('text' => 'Unit Kerja', 'class' => 'col-md-3 control-label'),'type' => 'text', 'class' => 'form-control','value'=>$dataUser['unit_kerja'],'readonly', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('hp', array('label' => array('text' => 'No Telp/HP Aktif', 'class' => 'col-md-3 control-label'),'type' => 'text', 'class' => 'form-control','value'=>$telp,'readonly', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('nip_atasan', array('label' => array('text' => 'Atasan Langsung', 'class' => 'col-md-3 control-label'),'type' => 'select', 'class' => 'form-control','options'=>$dataAtasan,'readonly', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('nip_ppk', array('label' => array('text' => 'Pembina Kepegawaian (PPK)', 'class' => 'col-md-3 control-label'),'type' => 'select', 'class' => 'form-control','options'=>$dataAtasan,'readonly', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('jabatan_ppk', array('label' => array('text' => 'Jabatan PPK', 'class' => 'col-md-3 control-label'),'type' => 'select', 'class' => 'form-control','options'=>$jabatanPPK,'readonly', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('kode_jenis_cuti', array('label' => array('text' => 'Jenis Cuti Yang Diambil', 'class' => 'col-md-3 control-label'),'type' => 'select','onchange'=>'sisaCuti(this.value);cutiSakit(this.value)','options'=>$jenisCuti, 'class' => 'form-control', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('desc_jenis_cuti', array('label' => false,'type' => 'hidden','class' => 'form-control', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('sisa_cuti', array('label' => array('text' => 'Sisa Cuti','id'=>'labelsisacuti', 'class' => 'col-md-3 control-label'),'type' => 'text','readonly', 'class' => 'form-control', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('sisa_cuti2', array('label' => array('text' => 'Sisa Cuti','id'=>'labelsisacuti', 'class' => 'col-md-3 control-label'),'type' => 'hidden','readonly', 'class' => 'form-control', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

    ?>
</div>

<div class="col-md-6">
    <?php 
        echo $this->Form->input('dari_tanggal', array('label' => array('text' => 'Tanggal Mulai', 'class' => 'col-md-3 control-label'),'type' => 'text','readonly','onchange'=>'selisih()', 'class' => 'form-control', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('sampai_tanggal', array('label' => array('text' => 'Tanggal Berakhir', 'class' => 'col-md-3 control-label'),'type' => 'text', 'readonly' ,'onchange'=>'selisih()','class' => 'form-control', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('jumlah_cuti', array('label' => array('text' => 'Lamanya Cuti', 'class' => 'col-md-3 control-label'),'type' => 'text', 'readonly', 'class' => 'form-control', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('jumlah_cuti2', array('label' => array('text' => 'Lamanya Cuti', 'class' => 'col-md-3 control-label'),'type' => 'hidden', 'readonly', 'class' => 'form-control', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));
        
        echo $this->Form->input('alasan_cuti', array('label' => array('text' => 'Alasan Cuti', 'class' => 'col-md-3 control-label'),'type' => 'textarea', 'class' => 'form-control', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));

        echo $this->Form->input('alamat_menjalankan_cuti', array('label' => array('text' => 'Alamat Menjalankan Cuti', 'class' => 'col-md-3 control-label'),'type' => 'textarea', 'class' => 'form-control', 'between' => '<div class="col-sm-8">', 'after' => '</div>'));
        
        echo $this->Form->input('surat_sakit', array('label' => array('text' => 'Softcopy Surat Sakit','id'=>'labelSuratSakit', 'class' => 'col-sm-3 control-label'), 'type' => 'file', 'accept'=>array('image/*','pdf/*'), 'onchange'=>'cek_file(id)', 'class' => 'form-control', 'between'=>'<div class="col-sm-8">','after'=>'<div id="SuratSakit"></div></div>'));
    ?>
</div>
<br>
<?php echo $this->Form->end(__((isset($button)?$button:'SUBMIT'), true));?>