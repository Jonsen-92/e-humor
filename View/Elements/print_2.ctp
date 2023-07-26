<?php //layout nota retur faktur penjualan  
// pr($data);exit;?>
<div class="print1">
	<div class="headerData">
		<table class="header" width="100%" style="font-size:10px;">
			<tr valign="top">
				<td>Nama Cabang</td>
				<td><?php echo ": ".$data[0]['A']['name'];?></td>
			</tr>
			<tr valign="top">
				<td>Alamat Cabang</td>
				<td><?php echo ": ".$data[0]['A']['address'];?></td>
			</tr>
			<tr valign="top">
				<td>NPWP / NPPKP</td>
				<td><?php echo ": ".$data[0]['B']['pph_npwp'];?></td>
			</tr>
		</table>
		<br/>
		<h3 align="center">Nota Retur atas Kesalahan / Koreksi Administrasi<br/>Bukan atas Retur Barang</h3>
		<br/>
		<table class="headerData">
			<tr>
				<td>Pembeli<td>
				<td><td>
				<td><td>
			</tr>
			<tr>
				<td><td>
				<td>Nama<td>
				<td><?php echo ": ".$data[0]['F']['name'];?><td>
			</tr>
			<tr>
				<td><td>
				<td>Alamat<td>
				<td><?php echo ": ".$data[0]['F']['cs_address1'];?><td>
			</tr>
		</table>
		<table width="100%">
			<tr>
				<td>Atas faktur penjualan No :<?php echo " ".$data[0]['D']['si_code'];?></td>
				<td></td>
				<td>Tanggal :<?php echo " ".date('d-m-Y', strtotime($data[0]['D']['si_date']));?></td>
				<td></td>
				<td>No Retur :<?php echo " ".$data[0]['C']['rj_code'];?></td>
			</tr>
		</table>
		<table class="detailData" cellpadding="0" cellspacing="0"
			<tr valign="top">
				<th style="border-top:1px solid #000;border-bottom:1px solid #000;border-right:1px solid #000;border-left:1px solid #000;">No</th>
				<th style="border-top:1px solid #000;border-bottom:1px solid #000;border-right:1px solid #000;">Nama Barang Kena Pajak yang dikembalikan</th>
				<th style="border-top:1px solid #000;border-bottom:1px solid #000;border-right:1px solid #000;">Kwantum</th>
				<th style="border-top:1px solid #000;border-bottom:1px solid #000;border-right:1px solid #000;">Harga Satuan Menurut Faktur Pajak</th>
				<th style="border-top:1px solid #000;border-bottom:1px solid #000;border-right:1px solid #000;">Harga Jual yang dikembalikan</th>
			</tr>
			<?php foreach ($data as $i=>$v): 
			$count = 1;?>
				<tr valign="top">
					<td style="border-left:1px solid #000;border-right:1px solid #000;"><?php echo $count+$i;?></td>
					<td style="border-right:1px solid #000;"><?php echo $v['H']['name'];?></td>
					<td style="border-right:1px solid #000;" align="center"><?php echo $v[0]['total_dpp']/$v['G']['dpp'];?></td>
					<td style="border-right:1px solid #000;" align="right"><?php echo number_format($v['G']['dpp'],2,",",".");?></td>
					<td style="border-right:1px solid #000;" align="right"><?php echo number_format($v[0]['total_dpp'],2,",",".");?></td>
				</tr>
			<?php endforeach; ?>
				<tr valign="top">
					<th style="border-top:1px solid #000;border-bottom:1px solid #000;"></th>
					<th style="border-top:1px solid #000;border-bottom:1px solid #000;"></th>
					<th style="border-top:1px solid #000;border-bottom:1px solid #000;"></th>
					<th style="border-top:1px solid #000;border-bottom:1px solid #000;"></th>
					<th style="border-top:1px solid #000;border-bottom:1px solid #000;"></th>
				</tr>
				<tr>
					<td></td>
					<td>Jumlah harga Jual yang dikembalikan<td>
					<td></td>
					<td align="right"><?php echo number_format($data[0]['C']['tdpp'],2,",",".");?></td>
					<td></td>
				</tr>
				<tr>
					<td></td>
					<td>PPN</td>
					<td></td>
					<td></td>
					<td align="right"><?php echo number_format($data[0]['C']['tppn'],2,",",".");?></td>
				</tr>
		</table>
		<br/>
		<br/>
		<div>Keterangan atas penerbitan Nota Retur :<br/>Kesalahanan terjadi karena input data program :</div>
	</div>
</div>