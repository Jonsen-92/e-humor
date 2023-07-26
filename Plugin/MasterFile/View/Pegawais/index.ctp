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
<div class="index">
	<br/>
	<h2><?php echo __('Data Pegawai');?></h2>
	<div class="col-md-12">
		<div class="row">
	<?php echo $this->General->searchfiltering(array
		('nip'=>array ('label'=>'NIP'),
	    'nama'=>array ('label'=>'Nama Pegawai')));?>

	</div>
	</div>
	<div style="">
	<table cellpadding="0" cellspacing="0" class="table table-striped table-hover">
	<tr class="altrow">
		<th><?php echo $this->Paginator->sort('nip','N I P');?></th>
		<th><?php echo $this->Paginator->sort('nama','Nama Pegawai');?></th>
		<th><?php echo $this->Paginator->sort('jabatan','Jabatan'); ?></th> 
		<th><?php echo $this->Paginator->sort('pangkat_golongan','Pangkat/Golongan'); ?></th>
		<th><?php echo $this->Paginator->sort('unit_kerja','Instansi'); ?></th>
		<th style ="width:30%;" class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
    foreach ($test as $i=>$v): ?>
	<tr<?php echo $i%2==0?' class="altrow"':'';?>>
		<td><?php  echo $v[$model]['nip'];?>&nbsp;</td>
		<td><?php  echo $v[$model]['nama'];?>&nbsp;</td>
		<td><?php  echo $v[$model]['jabatan'];?>&nbsp;</td>
		<td><?php  echo $v[$model]['pangkat_golongan'];?>&nbsp;</td>
		<td><?php  echo $v[$model]['unit_kerja'];?>&nbsp;</td>
		<td style ="width:30%;" class="actions">
		<?php
			echo $this->Html->link(__('', true), array('action' => 'view', $v[$model]['id']), array('class' => 'btn fa fa-eye','title'=>'lihat'));
            echo $this->Html->link(__('', true), array('action' => 'edit', $v[$model]['id']),array('class' => 'btn fa fa-edit','title'=>'edit'));
            echo $this->Form->postLink(__(''), array('action' => 'delete', $v[$model]['id']),array('class' => 'btn fa fa-trash','title'=>'hapus'), __('Are you sure you want to delete pegawai# %s?', $v[$model]['nama']));
		?>
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
	<ul>
<li ><?php
echo $this->Html->link(__(' ADD', true), array('action' => 'add'),array('class'=>'add fa fa-plus') ); ?></li>
	</ul>
</div>
