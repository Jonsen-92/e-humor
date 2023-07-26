<style>
.btn {
  background-color: DodgerBlue;
  border: none;
  color: white;
  padding: 3px 10px;
  font-size: 16px;
  cursor: pointer;
}

.add {
  background-color: #228B22;
  border: none;
  color: white;
  padding: 8px 10px;
  font-size: 16px;
  cursor: pointer;
  border-radius: 8px;
}

/* Darker background on mouse-over */
.btn:hover {
  background-color: #FFD700;
}
.add:hover {
  background-color: #00CED1;
}
</style>
<div class="index" style="overflow-x:auto;">
	<br/>
	<h2><?php echo __('Data Pengajuan Cuti');?></h2>
	<div class="col-md-12">
		<div class="row">
	<?php echo $this->General->searchfiltering(array
		('nip'=>array ('label'=>'NIP'),
	     'nama'=>array ('label'=>'Nama Pegawai')));?>

	</div>
	</div>
	<div style="">
	<table cellpadding="0" cellspacing="0" class="table table-striped table-hover" >
	<tr class="altrow">
		<th><?php echo $this->Paginator->sort('nip','N I P');?></th>
		<th><?php echo $this->Paginator->sort('nama','Nama Pegawai');?></th>
		<th><?php echo $this->Paginator->sort('jabatan','Jabatan'); ?></th> 
		<th><?php echo $this->Paginator->sort('desc_jenis_cuti','Jenis Cuti'); ?></th>
		<th><?php echo $this->Paginator->sort('jumlah_cuti','Jumlah Cuti (Hari)'); ?></th>
		<th><?php echo $this->Paginator->sort('dari_tanggal','Tanggal Mulai'); ?></th>
		<th><?php echo $this->Paginator->sort('sampai_tanggal','Tanggal Akhir'); ?></th>
		<th><?php echo $this->Paginator->sort('status_validasi','Status Validasi'); ?></th>
		<th><?php echo $this->Paginator->sort('status_apr_atasan','Apr Atasan'); ?></th>
		<th><?php echo $this->Paginator->sort('status_apr_ppk','Apr PPK'); ?></th>
		<th style ="width:30%;" class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
    foreach ($data as $i=>$v): ?>
	<tr<?php echo $i%2==0?' class="altrow"':'';?>>
		<td><?php  echo $v[$model]['nip'];?>&nbsp;</td>
		<td><?php  echo $v[$model]['nama'];?>&nbsp;</td>
		<td><?php  echo $v[$model]['jabatan'];?>&nbsp;</td>
		<td><?php  echo $v[$model]['desc_jenis_cuti'];?>&nbsp;</td>
		<td><?php  echo $v[$model]['jumlah_cuti'];?>&nbsp;</td>
		<td><?php  echo $v[$model]['dari_tanggal'];?>&nbsp;</td>
		<td><?php  echo $v[$model]['sampai_tanggal'];?>&nbsp;</td>
		<td><?php  if($v[$model]['status_validasi'] == 'PROSES'){
                        echo "<span class='badge btn-warning'>PROSES</span>";
                    }
                    else if($v[$model]['status_validasi'] == 'VALID'){
                        echo "<span class='badge btn-success'>VALID</span>";
                    }
                    else if($v[$model]['status_validasi']  == 'TIDAK VALID'){
                        echo "<span class='badge btn-danger'>TIDAK VALID</span>";
                    } 
			?>
		</td>
		<td><?php  if($v[$model]['status_apr_atasan'] == 'PROSES'){
                        echo "<span class='badge btn-warning'>PROSES</span>";
                    }
                    else if($v[$model]['status_apr_atasan'] == 'DISETUJUI'){
                        echo "<span class='badge btn-success'>DISETUJUI</span>";
                    }
                    else if($v[$model]['status_apr_atasan']  == 'DITOLAK'){
                        echo "<span class='badge btn-danger'>DISETUJUI</span>";
                    } 
			?>
		</td>
		<td><?php  if($v[$model]['status_apr_ppk'] == 'PROSES'){
                        echo "<span class='badge btn-warning'>PROSES</span>";
                    }
                    else if($v[$model]['status_apr_ppk'] == 'DISETUJUI'){
                        echo "<span class='badge btn-success'>DISETUJUI</span>";
                    }
                    else if($v[$model]['status_apr_ppk']  == 'DITOLAK'){
                        echo "<span class='badge btn-danger'>DISETUJUI</span>";
                    } 
			?>
		</td>
		<td style ="width:30%;" class="actions">
		<?php
			echo $this->Html->link(__('', true), array('action' => 'view', $v[$model]['id']), array('class' => 'btn fa fa-eye','title'=>'Lihat Detail Pengajuan Cuti'));

            if($v[$model]['status_validasi'] == 'VALID' && $v[$model]['status_apr_atasan'] == 'PROSES'){
                echo $this->Html->link(__('', true), array('action' => 'approve', $v[$model]['id'],$v[$model]['nip']), array('class' => 'btn fa fa-check','title'=>'Persetujuan Pengajuan Cuti'), __('Apakah anda yakin menyetujui pengajuan cuti # %s?', $v[$model]['nama']));

                echo $this->Html->link(__('', true), array('action' => '#', ''), array('class' => 'btn fa fa-ban','data-toggle'=>'modal','data-target'=>'#modal'.$v[$model]['id'],'title'=>'Tolak Pengajuan Cuti'));
            }

		?>
                    <div class="modal fade in" id="modal<?php echo $v[$model]['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5><b>Alasan Penolakan Pengajuan Cuti</b></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                         <span aria-hidden="true">&times;</span>
                                        </button>
                                </div>
                                <?php echo $this->Form->create('PengajuanCuti', array('type'=>'file','url'=>'disapprove/'.$v[$model]['id']));?>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <?php
                                                echo $this->Form->input('alasan_tolak',array('label'=>false,'type'=>'textarea','class'=>'form-control','between'=>'<div class="col-sm-12">','after'=>'</div>'));
                                            ?>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <?php echo $this->Form->end(array('class'=>'btn btn-primary','label'=>'SUBMIT'));?>
                                    </div>
                            </div>
                        </div>
                    </div>
		</td>
	</tr>
	<?php endforeach; ?>
	</table>

	</div>

	<p>
	<?php
	$this->Paginator->options(array('url' => $this->passedArgs));
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>

	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo ' &nbsp; ';
		echo $this->Paginator->numbers(array('separator' => ' | '));
		echo ' &nbsp; ';
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>

<div class="actions actionsindex">
	<h3><?php echo __('Actions'); ?></h3>
	<!-- <ul>
<li ><?php
echo $this->Html->link(__(' ADD', true), array('action' => 'add'),array('class'=>'add fa fa-plus') ); ?></li>
	</ul> -->
</div>
