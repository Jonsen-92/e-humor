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
        <th><?php echo $this->Paginator->sort('nip_pemohon','N I P');?></th>
		<th><?php echo $this->Paginator->sort('nip_pemohon','Nama Pegawai');?></th>
		<th><?php echo $this->Paginator->sort('tanggal_izin','Tanggal Izin'); ?></th> 
		<th><?php echo $this->Paginator->sort('jam_mulai','Jam Mulai'); ?></th>
		<th><?php echo $this->Paginator->sort('jam_akhir','Jam Akhir'); ?></th>
		<th><?php echo $this->Paginator->sort('apr_pemberi_izin','Apr Atasan'); ?></th>
	</tr>
	<?php
    foreach ($data as $i=>$v): ?>
	<tr<?php echo $i%2==0?' class="altrow"':'';?>>
        <td><?php  echo $v[$model]['nip_pemohon'];?>&nbsp;</td>
		<td><?php  echo $v[$model]['nama_pemohon'];?>&nbsp;</td>
		<td><?php  echo $v[$model]['tanggal_izin'];?>&nbsp;</td>
		<td><?php  echo $v[$model]['jam_mulai'];?>&nbsp;</td>
		<td><?php  echo $v[$model]['jam_akhir'];?>&nbsp;</td>
		<td><?php  if($v[$model]['apr_pemberi_izin'] == 'PROSES'){
                        echo "<span class='badge btn-warning'>PROSES</span>";
                    }
                    else if($v[$model]['apr_pemberi_izin'] == 'DISETUJUI'){
                        echo "<span class='badge btn-success'>DISETUJUI</span>";
                    }
                    else if($v[$model]['apr_pemberi_izin']  == 'DITOLAK'){
                        echo "<span class='badge btn-danger'>DITOLAK</span>";
                    } 
			?>
		</td>
		<td style ="width:30%;" class="actions">
		<?php
			echo $this->Html->link(__('', true), array('action' => 'view', $v[$model]['id']), array('class' => 'btn fa fa-eye','title'=>'Lihat Detail Permohonan Izin'));

            if($v[$model]['apr_pemberi_izin'] == 'PROSES'){
                echo $this->Html->link(__('', true), array('action' => 'approve', $v[$model]['id']), array('class' => 'btn fa fa-check','title'=>'Persetujuan Izin Keluar Kantor'), __('Apakah anda yakin menyetujui izin Keluar Kantor # %s?', $v[$model]['nama_pemohon']));

                echo $this->Html->link(__('', true), array('action' => '#', ''), array('class' => 'btn fa fa-ban','data-toggle'=>'modal','data-target'=>'#modal'.$v[$model]['id'],'title'=>'Tolak Izin Keluar Kantor'));
            }

		?>
                    <div class="modal fade in" id="modal<?php echo $v[$model]['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5><b>Alasan Penolakan Permohonan</b></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                         <span aria-hidden="true">&times;</span>
                                        </button>
                                </div>
                                <?php echo $this->Form->create('IzinKeluarKantor', array('type'=>'file','url'=>'disapprove/'.$v[$model]['id']));?>
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
