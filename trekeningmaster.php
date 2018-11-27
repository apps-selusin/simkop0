<?php

// group
// id
// rekening
// tipe
// posisi
// laporan
// status
// parent
// keterangan
// active

?>
<?php if ($trekening->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $trekening->TableCaption() ?></h4> -->
<table id="tbl_trekeningmaster" class="table table-bordered table-striped ewViewTable">
<?php echo $trekening->TableCustomInnerHtml ?>
	<tbody>
<?php if ($trekening->group->Visible) { // group ?>
		<tr id="r_group">
			<td><?php echo $trekening->group->FldCaption() ?></td>
			<td<?php echo $trekening->group->CellAttributes() ?>>
<span id="el_trekening_group">
<span<?php echo $trekening->group->ViewAttributes() ?>>
<?php echo $trekening->group->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($trekening->id->Visible) { // id ?>
		<tr id="r_id">
			<td><?php echo $trekening->id->FldCaption() ?></td>
			<td<?php echo $trekening->id->CellAttributes() ?>>
<span id="el_trekening_id">
<span<?php echo $trekening->id->ViewAttributes() ?>>
<?php echo $trekening->id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($trekening->rekening->Visible) { // rekening ?>
		<tr id="r_rekening">
			<td><?php echo $trekening->rekening->FldCaption() ?></td>
			<td<?php echo $trekening->rekening->CellAttributes() ?>>
<span id="el_trekening_rekening">
<span<?php echo $trekening->rekening->ViewAttributes() ?>>
<?php echo $trekening->rekening->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($trekening->tipe->Visible) { // tipe ?>
		<tr id="r_tipe">
			<td><?php echo $trekening->tipe->FldCaption() ?></td>
			<td<?php echo $trekening->tipe->CellAttributes() ?>>
<span id="el_trekening_tipe">
<span<?php echo $trekening->tipe->ViewAttributes() ?>>
<?php echo $trekening->tipe->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($trekening->posisi->Visible) { // posisi ?>
		<tr id="r_posisi">
			<td><?php echo $trekening->posisi->FldCaption() ?></td>
			<td<?php echo $trekening->posisi->CellAttributes() ?>>
<span id="el_trekening_posisi">
<span<?php echo $trekening->posisi->ViewAttributes() ?>>
<?php echo $trekening->posisi->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($trekening->laporan->Visible) { // laporan ?>
		<tr id="r_laporan">
			<td><?php echo $trekening->laporan->FldCaption() ?></td>
			<td<?php echo $trekening->laporan->CellAttributes() ?>>
<span id="el_trekening_laporan">
<span<?php echo $trekening->laporan->ViewAttributes() ?>>
<?php echo $trekening->laporan->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($trekening->status->Visible) { // status ?>
		<tr id="r_status">
			<td><?php echo $trekening->status->FldCaption() ?></td>
			<td<?php echo $trekening->status->CellAttributes() ?>>
<span id="el_trekening_status">
<span<?php echo $trekening->status->ViewAttributes() ?>>
<?php echo $trekening->status->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($trekening->parent->Visible) { // parent ?>
		<tr id="r_parent">
			<td><?php echo $trekening->parent->FldCaption() ?></td>
			<td<?php echo $trekening->parent->CellAttributes() ?>>
<span id="el_trekening_parent">
<span<?php echo $trekening->parent->ViewAttributes() ?>>
<?php echo $trekening->parent->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($trekening->keterangan->Visible) { // keterangan ?>
		<tr id="r_keterangan">
			<td><?php echo $trekening->keterangan->FldCaption() ?></td>
			<td<?php echo $trekening->keterangan->CellAttributes() ?>>
<span id="el_trekening_keterangan">
<span<?php echo $trekening->keterangan->ViewAttributes() ?>>
<?php echo $trekening->keterangan->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($trekening->active->Visible) { // active ?>
		<tr id="r_active">
			<td><?php echo $trekening->active->FldCaption() ?></td>
			<td<?php echo $trekening->active->CellAttributes() ?>>
<span id="el_trekening_active">
<span<?php echo $trekening->active->ViewAttributes() ?>>
<?php echo $trekening->active->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
