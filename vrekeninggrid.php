<?php

// Create page object
if (!isset($vrekening_grid)) $vrekening_grid = new cvrekening_grid();

// Page init
$vrekening_grid->Page_Init();

// Page main
$vrekening_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$vrekening_grid->Page_Render();
?>
<?php if ($vrekening->Export == "") { ?>
<script type="text/javascript">

// Form object
var fvrekeninggrid = new ew_Form("fvrekeninggrid", "grid");
fvrekeninggrid.FormKeyCountName = '<?php echo $vrekening_grid->FormKeyCountName ?>';

// Validate form
fvrekeninggrid.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_group");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $vrekening->group->FldCaption(), $vrekening->group->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_group");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($vrekening->group->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $vrekening->id->FldCaption(), $vrekening->id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_rekening");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $vrekening->rekening->FldCaption(), $vrekening->rekening->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tipe");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $vrekening->tipe->FldCaption(), $vrekening->tipe->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_posisi");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $vrekening->posisi->FldCaption(), $vrekening->posisi->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_laporan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $vrekening->laporan->FldCaption(), $vrekening->laporan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $vrekening->status->FldCaption(), $vrekening->status->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_parent");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $vrekening->parent->FldCaption(), $vrekening->parent->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_keterangan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $vrekening->keterangan->FldCaption(), $vrekening->keterangan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_active");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $vrekening->active->FldCaption(), $vrekening->active->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fvrekeninggrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "group", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "rekening", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tipe", false)) return false;
	if (ew_ValueChanged(fobj, infix, "posisi", false)) return false;
	if (ew_ValueChanged(fobj, infix, "laporan", false)) return false;
	if (ew_ValueChanged(fobj, infix, "status", false)) return false;
	if (ew_ValueChanged(fobj, infix, "parent", false)) return false;
	if (ew_ValueChanged(fobj, infix, "keterangan", false)) return false;
	if (ew_ValueChanged(fobj, infix, "active", false)) return false;
	return true;
}

// Form_CustomValidate event
fvrekeninggrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fvrekeninggrid.ValidateRequired = true;
<?php } else { ?>
fvrekeninggrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fvrekeninggrid.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fvrekeninggrid.Lists["x_active"].Options = <?php echo json_encode($vrekening->active->Options()) ?>;

// Form object for search
</script>
<?php } ?>
<?php
if ($vrekening->CurrentAction == "gridadd") {
	if ($vrekening->CurrentMode == "copy") {
		$bSelectLimit = $vrekening_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$vrekening_grid->TotalRecs = $vrekening->SelectRecordCount();
			$vrekening_grid->Recordset = $vrekening_grid->LoadRecordset($vrekening_grid->StartRec-1, $vrekening_grid->DisplayRecs);
		} else {
			if ($vrekening_grid->Recordset = $vrekening_grid->LoadRecordset())
				$vrekening_grid->TotalRecs = $vrekening_grid->Recordset->RecordCount();
		}
		$vrekening_grid->StartRec = 1;
		$vrekening_grid->DisplayRecs = $vrekening_grid->TotalRecs;
	} else {
		$vrekening->CurrentFilter = "0=1";
		$vrekening_grid->StartRec = 1;
		$vrekening_grid->DisplayRecs = $vrekening->GridAddRowCount;
	}
	$vrekening_grid->TotalRecs = $vrekening_grid->DisplayRecs;
	$vrekening_grid->StopRec = $vrekening_grid->DisplayRecs;
} else {
	$bSelectLimit = $vrekening_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($vrekening_grid->TotalRecs <= 0)
			$vrekening_grid->TotalRecs = $vrekening->SelectRecordCount();
	} else {
		if (!$vrekening_grid->Recordset && ($vrekening_grid->Recordset = $vrekening_grid->LoadRecordset()))
			$vrekening_grid->TotalRecs = $vrekening_grid->Recordset->RecordCount();
	}
	$vrekening_grid->StartRec = 1;
	$vrekening_grid->DisplayRecs = $vrekening_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$vrekening_grid->Recordset = $vrekening_grid->LoadRecordset($vrekening_grid->StartRec-1, $vrekening_grid->DisplayRecs);

	// Set no record found message
	if ($vrekening->CurrentAction == "" && $vrekening_grid->TotalRecs == 0) {
		if ($vrekening_grid->SearchWhere == "0=101")
			$vrekening_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$vrekening_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$vrekening_grid->RenderOtherOptions();
?>
<?php $vrekening_grid->ShowPageHeader(); ?>
<?php
$vrekening_grid->ShowMessage();
?>
<?php if ($vrekening_grid->TotalRecs > 0 || $vrekening->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid vrekening">
<div id="fvrekeninggrid" class="ewForm form-inline">
<div id="gmp_vrekening" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_vrekeninggrid" class="table ewTable">
<?php echo $vrekening->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$vrekening_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$vrekening_grid->RenderListOptions();

// Render list options (header, left)
$vrekening_grid->ListOptions->Render("header", "left");
?>
<?php if ($vrekening->group->Visible) { // group ?>
	<?php if ($vrekening->SortUrl($vrekening->group) == "") { ?>
		<th data-name="group"><div id="elh_vrekening_group" class="vrekening_group"><div class="ewTableHeaderCaption"><?php echo $vrekening->group->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="group"><div><div id="elh_vrekening_group" class="vrekening_group">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vrekening->group->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vrekening->group->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vrekening->group->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vrekening->id->Visible) { // id ?>
	<?php if ($vrekening->SortUrl($vrekening->id) == "") { ?>
		<th data-name="id"><div id="elh_vrekening_id" class="vrekening_id"><div class="ewTableHeaderCaption"><?php echo $vrekening->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id"><div><div id="elh_vrekening_id" class="vrekening_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vrekening->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vrekening->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vrekening->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vrekening->rekening->Visible) { // rekening ?>
	<?php if ($vrekening->SortUrl($vrekening->rekening) == "") { ?>
		<th data-name="rekening"><div id="elh_vrekening_rekening" class="vrekening_rekening"><div class="ewTableHeaderCaption"><?php echo $vrekening->rekening->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="rekening"><div><div id="elh_vrekening_rekening" class="vrekening_rekening">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vrekening->rekening->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vrekening->rekening->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vrekening->rekening->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vrekening->tipe->Visible) { // tipe ?>
	<?php if ($vrekening->SortUrl($vrekening->tipe) == "") { ?>
		<th data-name="tipe"><div id="elh_vrekening_tipe" class="vrekening_tipe"><div class="ewTableHeaderCaption"><?php echo $vrekening->tipe->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipe"><div><div id="elh_vrekening_tipe" class="vrekening_tipe">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vrekening->tipe->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vrekening->tipe->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vrekening->tipe->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vrekening->posisi->Visible) { // posisi ?>
	<?php if ($vrekening->SortUrl($vrekening->posisi) == "") { ?>
		<th data-name="posisi"><div id="elh_vrekening_posisi" class="vrekening_posisi"><div class="ewTableHeaderCaption"><?php echo $vrekening->posisi->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="posisi"><div><div id="elh_vrekening_posisi" class="vrekening_posisi">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vrekening->posisi->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vrekening->posisi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vrekening->posisi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vrekening->laporan->Visible) { // laporan ?>
	<?php if ($vrekening->SortUrl($vrekening->laporan) == "") { ?>
		<th data-name="laporan"><div id="elh_vrekening_laporan" class="vrekening_laporan"><div class="ewTableHeaderCaption"><?php echo $vrekening->laporan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="laporan"><div><div id="elh_vrekening_laporan" class="vrekening_laporan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vrekening->laporan->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vrekening->laporan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vrekening->laporan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vrekening->status->Visible) { // status ?>
	<?php if ($vrekening->SortUrl($vrekening->status) == "") { ?>
		<th data-name="status"><div id="elh_vrekening_status" class="vrekening_status"><div class="ewTableHeaderCaption"><?php echo $vrekening->status->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status"><div><div id="elh_vrekening_status" class="vrekening_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vrekening->status->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vrekening->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vrekening->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vrekening->parent->Visible) { // parent ?>
	<?php if ($vrekening->SortUrl($vrekening->parent) == "") { ?>
		<th data-name="parent"><div id="elh_vrekening_parent" class="vrekening_parent"><div class="ewTableHeaderCaption"><?php echo $vrekening->parent->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="parent"><div><div id="elh_vrekening_parent" class="vrekening_parent">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vrekening->parent->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vrekening->parent->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vrekening->parent->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vrekening->keterangan->Visible) { // keterangan ?>
	<?php if ($vrekening->SortUrl($vrekening->keterangan) == "") { ?>
		<th data-name="keterangan"><div id="elh_vrekening_keterangan" class="vrekening_keterangan"><div class="ewTableHeaderCaption"><?php echo $vrekening->keterangan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="keterangan"><div><div id="elh_vrekening_keterangan" class="vrekening_keterangan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vrekening->keterangan->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vrekening->keterangan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vrekening->keterangan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vrekening->active->Visible) { // active ?>
	<?php if ($vrekening->SortUrl($vrekening->active) == "") { ?>
		<th data-name="active"><div id="elh_vrekening_active" class="vrekening_active"><div class="ewTableHeaderCaption"><?php echo $vrekening->active->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="active"><div><div id="elh_vrekening_active" class="vrekening_active">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vrekening->active->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vrekening->active->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vrekening->active->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$vrekening_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$vrekening_grid->StartRec = 1;
$vrekening_grid->StopRec = $vrekening_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($vrekening_grid->FormKeyCountName) && ($vrekening->CurrentAction == "gridadd" || $vrekening->CurrentAction == "gridedit" || $vrekening->CurrentAction == "F")) {
		$vrekening_grid->KeyCount = $objForm->GetValue($vrekening_grid->FormKeyCountName);
		$vrekening_grid->StopRec = $vrekening_grid->StartRec + $vrekening_grid->KeyCount - 1;
	}
}
$vrekening_grid->RecCnt = $vrekening_grid->StartRec - 1;
if ($vrekening_grid->Recordset && !$vrekening_grid->Recordset->EOF) {
	$vrekening_grid->Recordset->MoveFirst();
	$bSelectLimit = $vrekening_grid->UseSelectLimit;
	if (!$bSelectLimit && $vrekening_grid->StartRec > 1)
		$vrekening_grid->Recordset->Move($vrekening_grid->StartRec - 1);
} elseif (!$vrekening->AllowAddDeleteRow && $vrekening_grid->StopRec == 0) {
	$vrekening_grid->StopRec = $vrekening->GridAddRowCount;
}

// Initialize aggregate
$vrekening->RowType = EW_ROWTYPE_AGGREGATEINIT;
$vrekening->ResetAttrs();
$vrekening_grid->RenderRow();
if ($vrekening->CurrentAction == "gridadd")
	$vrekening_grid->RowIndex = 0;
if ($vrekening->CurrentAction == "gridedit")
	$vrekening_grid->RowIndex = 0;
while ($vrekening_grid->RecCnt < $vrekening_grid->StopRec) {
	$vrekening_grid->RecCnt++;
	if (intval($vrekening_grid->RecCnt) >= intval($vrekening_grid->StartRec)) {
		$vrekening_grid->RowCnt++;
		if ($vrekening->CurrentAction == "gridadd" || $vrekening->CurrentAction == "gridedit" || $vrekening->CurrentAction == "F") {
			$vrekening_grid->RowIndex++;
			$objForm->Index = $vrekening_grid->RowIndex;
			if ($objForm->HasValue($vrekening_grid->FormActionName))
				$vrekening_grid->RowAction = strval($objForm->GetValue($vrekening_grid->FormActionName));
			elseif ($vrekening->CurrentAction == "gridadd")
				$vrekening_grid->RowAction = "insert";
			else
				$vrekening_grid->RowAction = "";
		}

		// Set up key count
		$vrekening_grid->KeyCount = $vrekening_grid->RowIndex;

		// Init row class and style
		$vrekening->ResetAttrs();
		$vrekening->CssClass = "";
		if ($vrekening->CurrentAction == "gridadd") {
			if ($vrekening->CurrentMode == "copy") {
				$vrekening_grid->LoadRowValues($vrekening_grid->Recordset); // Load row values
				$vrekening_grid->SetRecordKey($vrekening_grid->RowOldKey, $vrekening_grid->Recordset); // Set old record key
			} else {
				$vrekening_grid->LoadDefaultValues(); // Load default values
				$vrekening_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$vrekening_grid->LoadRowValues($vrekening_grid->Recordset); // Load row values
		}
		$vrekening->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($vrekening->CurrentAction == "gridadd") // Grid add
			$vrekening->RowType = EW_ROWTYPE_ADD; // Render add
		if ($vrekening->CurrentAction == "gridadd" && $vrekening->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$vrekening_grid->RestoreCurrentRowFormValues($vrekening_grid->RowIndex); // Restore form values
		if ($vrekening->CurrentAction == "gridedit") { // Grid edit
			if ($vrekening->EventCancelled) {
				$vrekening_grid->RestoreCurrentRowFormValues($vrekening_grid->RowIndex); // Restore form values
			}
			if ($vrekening_grid->RowAction == "insert")
				$vrekening->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$vrekening->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($vrekening->CurrentAction == "gridedit" && ($vrekening->RowType == EW_ROWTYPE_EDIT || $vrekening->RowType == EW_ROWTYPE_ADD) && $vrekening->EventCancelled) // Update failed
			$vrekening_grid->RestoreCurrentRowFormValues($vrekening_grid->RowIndex); // Restore form values
		if ($vrekening->RowType == EW_ROWTYPE_EDIT) // Edit row
			$vrekening_grid->EditRowCnt++;
		if ($vrekening->CurrentAction == "F") // Confirm row
			$vrekening_grid->RestoreCurrentRowFormValues($vrekening_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$vrekening->RowAttrs = array_merge($vrekening->RowAttrs, array('data-rowindex'=>$vrekening_grid->RowCnt, 'id'=>'r' . $vrekening_grid->RowCnt . '_vrekening', 'data-rowtype'=>$vrekening->RowType));

		// Render row
		$vrekening_grid->RenderRow();

		// Render list options
		$vrekening_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($vrekening_grid->RowAction <> "delete" && $vrekening_grid->RowAction <> "insertdelete" && !($vrekening_grid->RowAction == "insert" && $vrekening->CurrentAction == "F" && $vrekening_grid->EmptyRow())) {
?>
	<tr<?php echo $vrekening->RowAttributes() ?>>
<?php

// Render list options (body, left)
$vrekening_grid->ListOptions->Render("body", "left", $vrekening_grid->RowCnt);
?>
	<?php if ($vrekening->group->Visible) { // group ?>
		<td data-name="group"<?php echo $vrekening->group->CellAttributes() ?>>
<?php if ($vrekening->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_group" class="form-group vrekening_group">
<input type="text" data-table="vrekening" data-field="x_group" name="x<?php echo $vrekening_grid->RowIndex ?>_group" id="x<?php echo $vrekening_grid->RowIndex ?>_group" size="30" placeholder="<?php echo ew_HtmlEncode($vrekening->group->getPlaceHolder()) ?>" value="<?php echo $vrekening->group->EditValue ?>"<?php echo $vrekening->group->EditAttributes() ?>>
</span>
<input type="hidden" data-table="vrekening" data-field="x_group" name="o<?php echo $vrekening_grid->RowIndex ?>_group" id="o<?php echo $vrekening_grid->RowIndex ?>_group" value="<?php echo ew_HtmlEncode($vrekening->group->OldValue) ?>">
<?php } ?>
<?php if ($vrekening->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_group" class="form-group vrekening_group">
<input type="text" data-table="vrekening" data-field="x_group" name="x<?php echo $vrekening_grid->RowIndex ?>_group" id="x<?php echo $vrekening_grid->RowIndex ?>_group" size="30" placeholder="<?php echo ew_HtmlEncode($vrekening->group->getPlaceHolder()) ?>" value="<?php echo $vrekening->group->EditValue ?>"<?php echo $vrekening->group->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($vrekening->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_group" class="vrekening_group">
<span<?php echo $vrekening->group->ViewAttributes() ?>>
<?php echo $vrekening->group->ListViewValue() ?></span>
</span>
<?php if ($vrekening->CurrentAction <> "F") { ?>
<input type="hidden" data-table="vrekening" data-field="x_group" name="x<?php echo $vrekening_grid->RowIndex ?>_group" id="x<?php echo $vrekening_grid->RowIndex ?>_group" value="<?php echo ew_HtmlEncode($vrekening->group->FormValue) ?>">
<input type="hidden" data-table="vrekening" data-field="x_group" name="o<?php echo $vrekening_grid->RowIndex ?>_group" id="o<?php echo $vrekening_grid->RowIndex ?>_group" value="<?php echo ew_HtmlEncode($vrekening->group->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="vrekening" data-field="x_group" name="fvrekeninggrid$x<?php echo $vrekening_grid->RowIndex ?>_group" id="fvrekeninggrid$x<?php echo $vrekening_grid->RowIndex ?>_group" value="<?php echo ew_HtmlEncode($vrekening->group->FormValue) ?>">
<input type="hidden" data-table="vrekening" data-field="x_group" name="fvrekeninggrid$o<?php echo $vrekening_grid->RowIndex ?>_group" id="fvrekeninggrid$o<?php echo $vrekening_grid->RowIndex ?>_group" value="<?php echo ew_HtmlEncode($vrekening->group->OldValue) ?>">
<?php } ?>
<?php } ?>
<a id="<?php echo $vrekening_grid->PageObjName . "_row_" . $vrekening_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vrekening->id->Visible) { // id ?>
		<td data-name="id"<?php echo $vrekening->id->CellAttributes() ?>>
<?php if ($vrekening->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_id" class="form-group vrekening_id">
<input type="text" data-table="vrekening" data-field="x_id" name="x<?php echo $vrekening_grid->RowIndex ?>_id" id="x<?php echo $vrekening_grid->RowIndex ?>_id" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($vrekening->id->getPlaceHolder()) ?>" value="<?php echo $vrekening->id->EditValue ?>"<?php echo $vrekening->id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="vrekening" data-field="x_id" name="o<?php echo $vrekening_grid->RowIndex ?>_id" id="o<?php echo $vrekening_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($vrekening->id->OldValue) ?>">
<?php } ?>
<?php if ($vrekening->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_id" class="form-group vrekening_id">
<span<?php echo $vrekening->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $vrekening->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="vrekening" data-field="x_id" name="x<?php echo $vrekening_grid->RowIndex ?>_id" id="x<?php echo $vrekening_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($vrekening->id->CurrentValue) ?>">
<?php } ?>
<?php if ($vrekening->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_id" class="vrekening_id">
<span<?php echo $vrekening->id->ViewAttributes() ?>>
<?php echo $vrekening->id->ListViewValue() ?></span>
</span>
<?php if ($vrekening->CurrentAction <> "F") { ?>
<input type="hidden" data-table="vrekening" data-field="x_id" name="x<?php echo $vrekening_grid->RowIndex ?>_id" id="x<?php echo $vrekening_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($vrekening->id->FormValue) ?>">
<input type="hidden" data-table="vrekening" data-field="x_id" name="o<?php echo $vrekening_grid->RowIndex ?>_id" id="o<?php echo $vrekening_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($vrekening->id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="vrekening" data-field="x_id" name="fvrekeninggrid$x<?php echo $vrekening_grid->RowIndex ?>_id" id="fvrekeninggrid$x<?php echo $vrekening_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($vrekening->id->FormValue) ?>">
<input type="hidden" data-table="vrekening" data-field="x_id" name="fvrekeninggrid$o<?php echo $vrekening_grid->RowIndex ?>_id" id="fvrekeninggrid$o<?php echo $vrekening_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($vrekening->id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($vrekening->rekening->Visible) { // rekening ?>
		<td data-name="rekening"<?php echo $vrekening->rekening->CellAttributes() ?>>
<?php if ($vrekening->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_rekening" class="form-group vrekening_rekening">
<input type="text" data-table="vrekening" data-field="x_rekening" name="x<?php echo $vrekening_grid->RowIndex ?>_rekening" id="x<?php echo $vrekening_grid->RowIndex ?>_rekening" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($vrekening->rekening->getPlaceHolder()) ?>" value="<?php echo $vrekening->rekening->EditValue ?>"<?php echo $vrekening->rekening->EditAttributes() ?>>
</span>
<input type="hidden" data-table="vrekening" data-field="x_rekening" name="o<?php echo $vrekening_grid->RowIndex ?>_rekening" id="o<?php echo $vrekening_grid->RowIndex ?>_rekening" value="<?php echo ew_HtmlEncode($vrekening->rekening->OldValue) ?>">
<?php } ?>
<?php if ($vrekening->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_rekening" class="form-group vrekening_rekening">
<input type="text" data-table="vrekening" data-field="x_rekening" name="x<?php echo $vrekening_grid->RowIndex ?>_rekening" id="x<?php echo $vrekening_grid->RowIndex ?>_rekening" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($vrekening->rekening->getPlaceHolder()) ?>" value="<?php echo $vrekening->rekening->EditValue ?>"<?php echo $vrekening->rekening->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($vrekening->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_rekening" class="vrekening_rekening">
<span<?php echo $vrekening->rekening->ViewAttributes() ?>>
<?php echo $vrekening->rekening->ListViewValue() ?></span>
</span>
<?php if ($vrekening->CurrentAction <> "F") { ?>
<input type="hidden" data-table="vrekening" data-field="x_rekening" name="x<?php echo $vrekening_grid->RowIndex ?>_rekening" id="x<?php echo $vrekening_grid->RowIndex ?>_rekening" value="<?php echo ew_HtmlEncode($vrekening->rekening->FormValue) ?>">
<input type="hidden" data-table="vrekening" data-field="x_rekening" name="o<?php echo $vrekening_grid->RowIndex ?>_rekening" id="o<?php echo $vrekening_grid->RowIndex ?>_rekening" value="<?php echo ew_HtmlEncode($vrekening->rekening->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="vrekening" data-field="x_rekening" name="fvrekeninggrid$x<?php echo $vrekening_grid->RowIndex ?>_rekening" id="fvrekeninggrid$x<?php echo $vrekening_grid->RowIndex ?>_rekening" value="<?php echo ew_HtmlEncode($vrekening->rekening->FormValue) ?>">
<input type="hidden" data-table="vrekening" data-field="x_rekening" name="fvrekeninggrid$o<?php echo $vrekening_grid->RowIndex ?>_rekening" id="fvrekeninggrid$o<?php echo $vrekening_grid->RowIndex ?>_rekening" value="<?php echo ew_HtmlEncode($vrekening->rekening->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($vrekening->tipe->Visible) { // tipe ?>
		<td data-name="tipe"<?php echo $vrekening->tipe->CellAttributes() ?>>
<?php if ($vrekening->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_tipe" class="form-group vrekening_tipe">
<input type="text" data-table="vrekening" data-field="x_tipe" name="x<?php echo $vrekening_grid->RowIndex ?>_tipe" id="x<?php echo $vrekening_grid->RowIndex ?>_tipe" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($vrekening->tipe->getPlaceHolder()) ?>" value="<?php echo $vrekening->tipe->EditValue ?>"<?php echo $vrekening->tipe->EditAttributes() ?>>
</span>
<input type="hidden" data-table="vrekening" data-field="x_tipe" name="o<?php echo $vrekening_grid->RowIndex ?>_tipe" id="o<?php echo $vrekening_grid->RowIndex ?>_tipe" value="<?php echo ew_HtmlEncode($vrekening->tipe->OldValue) ?>">
<?php } ?>
<?php if ($vrekening->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_tipe" class="form-group vrekening_tipe">
<input type="text" data-table="vrekening" data-field="x_tipe" name="x<?php echo $vrekening_grid->RowIndex ?>_tipe" id="x<?php echo $vrekening_grid->RowIndex ?>_tipe" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($vrekening->tipe->getPlaceHolder()) ?>" value="<?php echo $vrekening->tipe->EditValue ?>"<?php echo $vrekening->tipe->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($vrekening->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_tipe" class="vrekening_tipe">
<span<?php echo $vrekening->tipe->ViewAttributes() ?>>
<?php echo $vrekening->tipe->ListViewValue() ?></span>
</span>
<?php if ($vrekening->CurrentAction <> "F") { ?>
<input type="hidden" data-table="vrekening" data-field="x_tipe" name="x<?php echo $vrekening_grid->RowIndex ?>_tipe" id="x<?php echo $vrekening_grid->RowIndex ?>_tipe" value="<?php echo ew_HtmlEncode($vrekening->tipe->FormValue) ?>">
<input type="hidden" data-table="vrekening" data-field="x_tipe" name="o<?php echo $vrekening_grid->RowIndex ?>_tipe" id="o<?php echo $vrekening_grid->RowIndex ?>_tipe" value="<?php echo ew_HtmlEncode($vrekening->tipe->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="vrekening" data-field="x_tipe" name="fvrekeninggrid$x<?php echo $vrekening_grid->RowIndex ?>_tipe" id="fvrekeninggrid$x<?php echo $vrekening_grid->RowIndex ?>_tipe" value="<?php echo ew_HtmlEncode($vrekening->tipe->FormValue) ?>">
<input type="hidden" data-table="vrekening" data-field="x_tipe" name="fvrekeninggrid$o<?php echo $vrekening_grid->RowIndex ?>_tipe" id="fvrekeninggrid$o<?php echo $vrekening_grid->RowIndex ?>_tipe" value="<?php echo ew_HtmlEncode($vrekening->tipe->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($vrekening->posisi->Visible) { // posisi ?>
		<td data-name="posisi"<?php echo $vrekening->posisi->CellAttributes() ?>>
<?php if ($vrekening->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_posisi" class="form-group vrekening_posisi">
<input type="text" data-table="vrekening" data-field="x_posisi" name="x<?php echo $vrekening_grid->RowIndex ?>_posisi" id="x<?php echo $vrekening_grid->RowIndex ?>_posisi" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($vrekening->posisi->getPlaceHolder()) ?>" value="<?php echo $vrekening->posisi->EditValue ?>"<?php echo $vrekening->posisi->EditAttributes() ?>>
</span>
<input type="hidden" data-table="vrekening" data-field="x_posisi" name="o<?php echo $vrekening_grid->RowIndex ?>_posisi" id="o<?php echo $vrekening_grid->RowIndex ?>_posisi" value="<?php echo ew_HtmlEncode($vrekening->posisi->OldValue) ?>">
<?php } ?>
<?php if ($vrekening->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_posisi" class="form-group vrekening_posisi">
<input type="text" data-table="vrekening" data-field="x_posisi" name="x<?php echo $vrekening_grid->RowIndex ?>_posisi" id="x<?php echo $vrekening_grid->RowIndex ?>_posisi" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($vrekening->posisi->getPlaceHolder()) ?>" value="<?php echo $vrekening->posisi->EditValue ?>"<?php echo $vrekening->posisi->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($vrekening->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_posisi" class="vrekening_posisi">
<span<?php echo $vrekening->posisi->ViewAttributes() ?>>
<?php echo $vrekening->posisi->ListViewValue() ?></span>
</span>
<?php if ($vrekening->CurrentAction <> "F") { ?>
<input type="hidden" data-table="vrekening" data-field="x_posisi" name="x<?php echo $vrekening_grid->RowIndex ?>_posisi" id="x<?php echo $vrekening_grid->RowIndex ?>_posisi" value="<?php echo ew_HtmlEncode($vrekening->posisi->FormValue) ?>">
<input type="hidden" data-table="vrekening" data-field="x_posisi" name="o<?php echo $vrekening_grid->RowIndex ?>_posisi" id="o<?php echo $vrekening_grid->RowIndex ?>_posisi" value="<?php echo ew_HtmlEncode($vrekening->posisi->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="vrekening" data-field="x_posisi" name="fvrekeninggrid$x<?php echo $vrekening_grid->RowIndex ?>_posisi" id="fvrekeninggrid$x<?php echo $vrekening_grid->RowIndex ?>_posisi" value="<?php echo ew_HtmlEncode($vrekening->posisi->FormValue) ?>">
<input type="hidden" data-table="vrekening" data-field="x_posisi" name="fvrekeninggrid$o<?php echo $vrekening_grid->RowIndex ?>_posisi" id="fvrekeninggrid$o<?php echo $vrekening_grid->RowIndex ?>_posisi" value="<?php echo ew_HtmlEncode($vrekening->posisi->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($vrekening->laporan->Visible) { // laporan ?>
		<td data-name="laporan"<?php echo $vrekening->laporan->CellAttributes() ?>>
<?php if ($vrekening->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_laporan" class="form-group vrekening_laporan">
<input type="text" data-table="vrekening" data-field="x_laporan" name="x<?php echo $vrekening_grid->RowIndex ?>_laporan" id="x<?php echo $vrekening_grid->RowIndex ?>_laporan" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($vrekening->laporan->getPlaceHolder()) ?>" value="<?php echo $vrekening->laporan->EditValue ?>"<?php echo $vrekening->laporan->EditAttributes() ?>>
</span>
<input type="hidden" data-table="vrekening" data-field="x_laporan" name="o<?php echo $vrekening_grid->RowIndex ?>_laporan" id="o<?php echo $vrekening_grid->RowIndex ?>_laporan" value="<?php echo ew_HtmlEncode($vrekening->laporan->OldValue) ?>">
<?php } ?>
<?php if ($vrekening->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_laporan" class="form-group vrekening_laporan">
<input type="text" data-table="vrekening" data-field="x_laporan" name="x<?php echo $vrekening_grid->RowIndex ?>_laporan" id="x<?php echo $vrekening_grid->RowIndex ?>_laporan" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($vrekening->laporan->getPlaceHolder()) ?>" value="<?php echo $vrekening->laporan->EditValue ?>"<?php echo $vrekening->laporan->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($vrekening->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_laporan" class="vrekening_laporan">
<span<?php echo $vrekening->laporan->ViewAttributes() ?>>
<?php echo $vrekening->laporan->ListViewValue() ?></span>
</span>
<?php if ($vrekening->CurrentAction <> "F") { ?>
<input type="hidden" data-table="vrekening" data-field="x_laporan" name="x<?php echo $vrekening_grid->RowIndex ?>_laporan" id="x<?php echo $vrekening_grid->RowIndex ?>_laporan" value="<?php echo ew_HtmlEncode($vrekening->laporan->FormValue) ?>">
<input type="hidden" data-table="vrekening" data-field="x_laporan" name="o<?php echo $vrekening_grid->RowIndex ?>_laporan" id="o<?php echo $vrekening_grid->RowIndex ?>_laporan" value="<?php echo ew_HtmlEncode($vrekening->laporan->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="vrekening" data-field="x_laporan" name="fvrekeninggrid$x<?php echo $vrekening_grid->RowIndex ?>_laporan" id="fvrekeninggrid$x<?php echo $vrekening_grid->RowIndex ?>_laporan" value="<?php echo ew_HtmlEncode($vrekening->laporan->FormValue) ?>">
<input type="hidden" data-table="vrekening" data-field="x_laporan" name="fvrekeninggrid$o<?php echo $vrekening_grid->RowIndex ?>_laporan" id="fvrekeninggrid$o<?php echo $vrekening_grid->RowIndex ?>_laporan" value="<?php echo ew_HtmlEncode($vrekening->laporan->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($vrekening->status->Visible) { // status ?>
		<td data-name="status"<?php echo $vrekening->status->CellAttributes() ?>>
<?php if ($vrekening->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_status" class="form-group vrekening_status">
<input type="text" data-table="vrekening" data-field="x_status" name="x<?php echo $vrekening_grid->RowIndex ?>_status" id="x<?php echo $vrekening_grid->RowIndex ?>_status" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($vrekening->status->getPlaceHolder()) ?>" value="<?php echo $vrekening->status->EditValue ?>"<?php echo $vrekening->status->EditAttributes() ?>>
</span>
<input type="hidden" data-table="vrekening" data-field="x_status" name="o<?php echo $vrekening_grid->RowIndex ?>_status" id="o<?php echo $vrekening_grid->RowIndex ?>_status" value="<?php echo ew_HtmlEncode($vrekening->status->OldValue) ?>">
<?php } ?>
<?php if ($vrekening->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_status" class="form-group vrekening_status">
<input type="text" data-table="vrekening" data-field="x_status" name="x<?php echo $vrekening_grid->RowIndex ?>_status" id="x<?php echo $vrekening_grid->RowIndex ?>_status" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($vrekening->status->getPlaceHolder()) ?>" value="<?php echo $vrekening->status->EditValue ?>"<?php echo $vrekening->status->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($vrekening->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_status" class="vrekening_status">
<span<?php echo $vrekening->status->ViewAttributes() ?>>
<?php echo $vrekening->status->ListViewValue() ?></span>
</span>
<?php if ($vrekening->CurrentAction <> "F") { ?>
<input type="hidden" data-table="vrekening" data-field="x_status" name="x<?php echo $vrekening_grid->RowIndex ?>_status" id="x<?php echo $vrekening_grid->RowIndex ?>_status" value="<?php echo ew_HtmlEncode($vrekening->status->FormValue) ?>">
<input type="hidden" data-table="vrekening" data-field="x_status" name="o<?php echo $vrekening_grid->RowIndex ?>_status" id="o<?php echo $vrekening_grid->RowIndex ?>_status" value="<?php echo ew_HtmlEncode($vrekening->status->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="vrekening" data-field="x_status" name="fvrekeninggrid$x<?php echo $vrekening_grid->RowIndex ?>_status" id="fvrekeninggrid$x<?php echo $vrekening_grid->RowIndex ?>_status" value="<?php echo ew_HtmlEncode($vrekening->status->FormValue) ?>">
<input type="hidden" data-table="vrekening" data-field="x_status" name="fvrekeninggrid$o<?php echo $vrekening_grid->RowIndex ?>_status" id="fvrekeninggrid$o<?php echo $vrekening_grid->RowIndex ?>_status" value="<?php echo ew_HtmlEncode($vrekening->status->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($vrekening->parent->Visible) { // parent ?>
		<td data-name="parent"<?php echo $vrekening->parent->CellAttributes() ?>>
<?php if ($vrekening->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($vrekening->parent->getSessionValue() <> "") { ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_parent" class="form-group vrekening_parent">
<span<?php echo $vrekening->parent->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $vrekening->parent->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $vrekening_grid->RowIndex ?>_parent" name="x<?php echo $vrekening_grid->RowIndex ?>_parent" value="<?php echo ew_HtmlEncode($vrekening->parent->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_parent" class="form-group vrekening_parent">
<input type="text" data-table="vrekening" data-field="x_parent" name="x<?php echo $vrekening_grid->RowIndex ?>_parent" id="x<?php echo $vrekening_grid->RowIndex ?>_parent" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($vrekening->parent->getPlaceHolder()) ?>" value="<?php echo $vrekening->parent->EditValue ?>"<?php echo $vrekening->parent->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="vrekening" data-field="x_parent" name="o<?php echo $vrekening_grid->RowIndex ?>_parent" id="o<?php echo $vrekening_grid->RowIndex ?>_parent" value="<?php echo ew_HtmlEncode($vrekening->parent->OldValue) ?>">
<?php } ?>
<?php if ($vrekening->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($vrekening->parent->getSessionValue() <> "") { ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_parent" class="form-group vrekening_parent">
<span<?php echo $vrekening->parent->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $vrekening->parent->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $vrekening_grid->RowIndex ?>_parent" name="x<?php echo $vrekening_grid->RowIndex ?>_parent" value="<?php echo ew_HtmlEncode($vrekening->parent->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_parent" class="form-group vrekening_parent">
<input type="text" data-table="vrekening" data-field="x_parent" name="x<?php echo $vrekening_grid->RowIndex ?>_parent" id="x<?php echo $vrekening_grid->RowIndex ?>_parent" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($vrekening->parent->getPlaceHolder()) ?>" value="<?php echo $vrekening->parent->EditValue ?>"<?php echo $vrekening->parent->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($vrekening->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_parent" class="vrekening_parent">
<span<?php echo $vrekening->parent->ViewAttributes() ?>>
<?php echo $vrekening->parent->ListViewValue() ?></span>
</span>
<?php if ($vrekening->CurrentAction <> "F") { ?>
<input type="hidden" data-table="vrekening" data-field="x_parent" name="x<?php echo $vrekening_grid->RowIndex ?>_parent" id="x<?php echo $vrekening_grid->RowIndex ?>_parent" value="<?php echo ew_HtmlEncode($vrekening->parent->FormValue) ?>">
<input type="hidden" data-table="vrekening" data-field="x_parent" name="o<?php echo $vrekening_grid->RowIndex ?>_parent" id="o<?php echo $vrekening_grid->RowIndex ?>_parent" value="<?php echo ew_HtmlEncode($vrekening->parent->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="vrekening" data-field="x_parent" name="fvrekeninggrid$x<?php echo $vrekening_grid->RowIndex ?>_parent" id="fvrekeninggrid$x<?php echo $vrekening_grid->RowIndex ?>_parent" value="<?php echo ew_HtmlEncode($vrekening->parent->FormValue) ?>">
<input type="hidden" data-table="vrekening" data-field="x_parent" name="fvrekeninggrid$o<?php echo $vrekening_grid->RowIndex ?>_parent" id="fvrekeninggrid$o<?php echo $vrekening_grid->RowIndex ?>_parent" value="<?php echo ew_HtmlEncode($vrekening->parent->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($vrekening->keterangan->Visible) { // keterangan ?>
		<td data-name="keterangan"<?php echo $vrekening->keterangan->CellAttributes() ?>>
<?php if ($vrekening->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_keterangan" class="form-group vrekening_keterangan">
<input type="text" data-table="vrekening" data-field="x_keterangan" name="x<?php echo $vrekening_grid->RowIndex ?>_keterangan" id="x<?php echo $vrekening_grid->RowIndex ?>_keterangan" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($vrekening->keterangan->getPlaceHolder()) ?>" value="<?php echo $vrekening->keterangan->EditValue ?>"<?php echo $vrekening->keterangan->EditAttributes() ?>>
</span>
<input type="hidden" data-table="vrekening" data-field="x_keterangan" name="o<?php echo $vrekening_grid->RowIndex ?>_keterangan" id="o<?php echo $vrekening_grid->RowIndex ?>_keterangan" value="<?php echo ew_HtmlEncode($vrekening->keterangan->OldValue) ?>">
<?php } ?>
<?php if ($vrekening->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_keterangan" class="form-group vrekening_keterangan">
<input type="text" data-table="vrekening" data-field="x_keterangan" name="x<?php echo $vrekening_grid->RowIndex ?>_keterangan" id="x<?php echo $vrekening_grid->RowIndex ?>_keterangan" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($vrekening->keterangan->getPlaceHolder()) ?>" value="<?php echo $vrekening->keterangan->EditValue ?>"<?php echo $vrekening->keterangan->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($vrekening->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_keterangan" class="vrekening_keterangan">
<span<?php echo $vrekening->keterangan->ViewAttributes() ?>>
<?php echo $vrekening->keterangan->ListViewValue() ?></span>
</span>
<?php if ($vrekening->CurrentAction <> "F") { ?>
<input type="hidden" data-table="vrekening" data-field="x_keterangan" name="x<?php echo $vrekening_grid->RowIndex ?>_keterangan" id="x<?php echo $vrekening_grid->RowIndex ?>_keterangan" value="<?php echo ew_HtmlEncode($vrekening->keterangan->FormValue) ?>">
<input type="hidden" data-table="vrekening" data-field="x_keterangan" name="o<?php echo $vrekening_grid->RowIndex ?>_keterangan" id="o<?php echo $vrekening_grid->RowIndex ?>_keterangan" value="<?php echo ew_HtmlEncode($vrekening->keterangan->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="vrekening" data-field="x_keterangan" name="fvrekeninggrid$x<?php echo $vrekening_grid->RowIndex ?>_keterangan" id="fvrekeninggrid$x<?php echo $vrekening_grid->RowIndex ?>_keterangan" value="<?php echo ew_HtmlEncode($vrekening->keterangan->FormValue) ?>">
<input type="hidden" data-table="vrekening" data-field="x_keterangan" name="fvrekeninggrid$o<?php echo $vrekening_grid->RowIndex ?>_keterangan" id="fvrekeninggrid$o<?php echo $vrekening_grid->RowIndex ?>_keterangan" value="<?php echo ew_HtmlEncode($vrekening->keterangan->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($vrekening->active->Visible) { // active ?>
		<td data-name="active"<?php echo $vrekening->active->CellAttributes() ?>>
<?php if ($vrekening->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_active" class="form-group vrekening_active">
<div id="tp_x<?php echo $vrekening_grid->RowIndex ?>_active" class="ewTemplate"><input type="radio" data-table="vrekening" data-field="x_active" data-value-separator="<?php echo $vrekening->active->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $vrekening_grid->RowIndex ?>_active" id="x<?php echo $vrekening_grid->RowIndex ?>_active" value="{value}"<?php echo $vrekening->active->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $vrekening_grid->RowIndex ?>_active" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $vrekening->active->RadioButtonListHtml(FALSE, "x{$vrekening_grid->RowIndex}_active") ?>
</div></div>
</span>
<input type="hidden" data-table="vrekening" data-field="x_active" name="o<?php echo $vrekening_grid->RowIndex ?>_active" id="o<?php echo $vrekening_grid->RowIndex ?>_active" value="<?php echo ew_HtmlEncode($vrekening->active->OldValue) ?>">
<?php } ?>
<?php if ($vrekening->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_active" class="form-group vrekening_active">
<div id="tp_x<?php echo $vrekening_grid->RowIndex ?>_active" class="ewTemplate"><input type="radio" data-table="vrekening" data-field="x_active" data-value-separator="<?php echo $vrekening->active->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $vrekening_grid->RowIndex ?>_active" id="x<?php echo $vrekening_grid->RowIndex ?>_active" value="{value}"<?php echo $vrekening->active->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $vrekening_grid->RowIndex ?>_active" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $vrekening->active->RadioButtonListHtml(FALSE, "x{$vrekening_grid->RowIndex}_active") ?>
</div></div>
</span>
<?php } ?>
<?php if ($vrekening->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $vrekening_grid->RowCnt ?>_vrekening_active" class="vrekening_active">
<span<?php echo $vrekening->active->ViewAttributes() ?>>
<?php echo $vrekening->active->ListViewValue() ?></span>
</span>
<?php if ($vrekening->CurrentAction <> "F") { ?>
<input type="hidden" data-table="vrekening" data-field="x_active" name="x<?php echo $vrekening_grid->RowIndex ?>_active" id="x<?php echo $vrekening_grid->RowIndex ?>_active" value="<?php echo ew_HtmlEncode($vrekening->active->FormValue) ?>">
<input type="hidden" data-table="vrekening" data-field="x_active" name="o<?php echo $vrekening_grid->RowIndex ?>_active" id="o<?php echo $vrekening_grid->RowIndex ?>_active" value="<?php echo ew_HtmlEncode($vrekening->active->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="vrekening" data-field="x_active" name="fvrekeninggrid$x<?php echo $vrekening_grid->RowIndex ?>_active" id="fvrekeninggrid$x<?php echo $vrekening_grid->RowIndex ?>_active" value="<?php echo ew_HtmlEncode($vrekening->active->FormValue) ?>">
<input type="hidden" data-table="vrekening" data-field="x_active" name="fvrekeninggrid$o<?php echo $vrekening_grid->RowIndex ?>_active" id="fvrekeninggrid$o<?php echo $vrekening_grid->RowIndex ?>_active" value="<?php echo ew_HtmlEncode($vrekening->active->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$vrekening_grid->ListOptions->Render("body", "right", $vrekening_grid->RowCnt);
?>
	</tr>
<?php if ($vrekening->RowType == EW_ROWTYPE_ADD || $vrekening->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fvrekeninggrid.UpdateOpts(<?php echo $vrekening_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($vrekening->CurrentAction <> "gridadd" || $vrekening->CurrentMode == "copy")
		if (!$vrekening_grid->Recordset->EOF) $vrekening_grid->Recordset->MoveNext();
}
?>
<?php
	if ($vrekening->CurrentMode == "add" || $vrekening->CurrentMode == "copy" || $vrekening->CurrentMode == "edit") {
		$vrekening_grid->RowIndex = '$rowindex$';
		$vrekening_grid->LoadDefaultValues();

		// Set row properties
		$vrekening->ResetAttrs();
		$vrekening->RowAttrs = array_merge($vrekening->RowAttrs, array('data-rowindex'=>$vrekening_grid->RowIndex, 'id'=>'r0_vrekening', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($vrekening->RowAttrs["class"], "ewTemplate");
		$vrekening->RowType = EW_ROWTYPE_ADD;

		// Render row
		$vrekening_grid->RenderRow();

		// Render list options
		$vrekening_grid->RenderListOptions();
		$vrekening_grid->StartRowCnt = 0;
?>
	<tr<?php echo $vrekening->RowAttributes() ?>>
<?php

// Render list options (body, left)
$vrekening_grid->ListOptions->Render("body", "left", $vrekening_grid->RowIndex);
?>
	<?php if ($vrekening->group->Visible) { // group ?>
		<td data-name="group">
<?php if ($vrekening->CurrentAction <> "F") { ?>
<span id="el$rowindex$_vrekening_group" class="form-group vrekening_group">
<input type="text" data-table="vrekening" data-field="x_group" name="x<?php echo $vrekening_grid->RowIndex ?>_group" id="x<?php echo $vrekening_grid->RowIndex ?>_group" size="30" placeholder="<?php echo ew_HtmlEncode($vrekening->group->getPlaceHolder()) ?>" value="<?php echo $vrekening->group->EditValue ?>"<?php echo $vrekening->group->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_vrekening_group" class="form-group vrekening_group">
<span<?php echo $vrekening->group->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $vrekening->group->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="vrekening" data-field="x_group" name="x<?php echo $vrekening_grid->RowIndex ?>_group" id="x<?php echo $vrekening_grid->RowIndex ?>_group" value="<?php echo ew_HtmlEncode($vrekening->group->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="vrekening" data-field="x_group" name="o<?php echo $vrekening_grid->RowIndex ?>_group" id="o<?php echo $vrekening_grid->RowIndex ?>_group" value="<?php echo ew_HtmlEncode($vrekening->group->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($vrekening->id->Visible) { // id ?>
		<td data-name="id">
<?php if ($vrekening->CurrentAction <> "F") { ?>
<span id="el$rowindex$_vrekening_id" class="form-group vrekening_id">
<input type="text" data-table="vrekening" data-field="x_id" name="x<?php echo $vrekening_grid->RowIndex ?>_id" id="x<?php echo $vrekening_grid->RowIndex ?>_id" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($vrekening->id->getPlaceHolder()) ?>" value="<?php echo $vrekening->id->EditValue ?>"<?php echo $vrekening->id->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_vrekening_id" class="form-group vrekening_id">
<span<?php echo $vrekening->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $vrekening->id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="vrekening" data-field="x_id" name="x<?php echo $vrekening_grid->RowIndex ?>_id" id="x<?php echo $vrekening_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($vrekening->id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="vrekening" data-field="x_id" name="o<?php echo $vrekening_grid->RowIndex ?>_id" id="o<?php echo $vrekening_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($vrekening->id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($vrekening->rekening->Visible) { // rekening ?>
		<td data-name="rekening">
<?php if ($vrekening->CurrentAction <> "F") { ?>
<span id="el$rowindex$_vrekening_rekening" class="form-group vrekening_rekening">
<input type="text" data-table="vrekening" data-field="x_rekening" name="x<?php echo $vrekening_grid->RowIndex ?>_rekening" id="x<?php echo $vrekening_grid->RowIndex ?>_rekening" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($vrekening->rekening->getPlaceHolder()) ?>" value="<?php echo $vrekening->rekening->EditValue ?>"<?php echo $vrekening->rekening->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_vrekening_rekening" class="form-group vrekening_rekening">
<span<?php echo $vrekening->rekening->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $vrekening->rekening->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="vrekening" data-field="x_rekening" name="x<?php echo $vrekening_grid->RowIndex ?>_rekening" id="x<?php echo $vrekening_grid->RowIndex ?>_rekening" value="<?php echo ew_HtmlEncode($vrekening->rekening->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="vrekening" data-field="x_rekening" name="o<?php echo $vrekening_grid->RowIndex ?>_rekening" id="o<?php echo $vrekening_grid->RowIndex ?>_rekening" value="<?php echo ew_HtmlEncode($vrekening->rekening->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($vrekening->tipe->Visible) { // tipe ?>
		<td data-name="tipe">
<?php if ($vrekening->CurrentAction <> "F") { ?>
<span id="el$rowindex$_vrekening_tipe" class="form-group vrekening_tipe">
<input type="text" data-table="vrekening" data-field="x_tipe" name="x<?php echo $vrekening_grid->RowIndex ?>_tipe" id="x<?php echo $vrekening_grid->RowIndex ?>_tipe" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($vrekening->tipe->getPlaceHolder()) ?>" value="<?php echo $vrekening->tipe->EditValue ?>"<?php echo $vrekening->tipe->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_vrekening_tipe" class="form-group vrekening_tipe">
<span<?php echo $vrekening->tipe->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $vrekening->tipe->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="vrekening" data-field="x_tipe" name="x<?php echo $vrekening_grid->RowIndex ?>_tipe" id="x<?php echo $vrekening_grid->RowIndex ?>_tipe" value="<?php echo ew_HtmlEncode($vrekening->tipe->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="vrekening" data-field="x_tipe" name="o<?php echo $vrekening_grid->RowIndex ?>_tipe" id="o<?php echo $vrekening_grid->RowIndex ?>_tipe" value="<?php echo ew_HtmlEncode($vrekening->tipe->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($vrekening->posisi->Visible) { // posisi ?>
		<td data-name="posisi">
<?php if ($vrekening->CurrentAction <> "F") { ?>
<span id="el$rowindex$_vrekening_posisi" class="form-group vrekening_posisi">
<input type="text" data-table="vrekening" data-field="x_posisi" name="x<?php echo $vrekening_grid->RowIndex ?>_posisi" id="x<?php echo $vrekening_grid->RowIndex ?>_posisi" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($vrekening->posisi->getPlaceHolder()) ?>" value="<?php echo $vrekening->posisi->EditValue ?>"<?php echo $vrekening->posisi->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_vrekening_posisi" class="form-group vrekening_posisi">
<span<?php echo $vrekening->posisi->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $vrekening->posisi->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="vrekening" data-field="x_posisi" name="x<?php echo $vrekening_grid->RowIndex ?>_posisi" id="x<?php echo $vrekening_grid->RowIndex ?>_posisi" value="<?php echo ew_HtmlEncode($vrekening->posisi->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="vrekening" data-field="x_posisi" name="o<?php echo $vrekening_grid->RowIndex ?>_posisi" id="o<?php echo $vrekening_grid->RowIndex ?>_posisi" value="<?php echo ew_HtmlEncode($vrekening->posisi->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($vrekening->laporan->Visible) { // laporan ?>
		<td data-name="laporan">
<?php if ($vrekening->CurrentAction <> "F") { ?>
<span id="el$rowindex$_vrekening_laporan" class="form-group vrekening_laporan">
<input type="text" data-table="vrekening" data-field="x_laporan" name="x<?php echo $vrekening_grid->RowIndex ?>_laporan" id="x<?php echo $vrekening_grid->RowIndex ?>_laporan" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($vrekening->laporan->getPlaceHolder()) ?>" value="<?php echo $vrekening->laporan->EditValue ?>"<?php echo $vrekening->laporan->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_vrekening_laporan" class="form-group vrekening_laporan">
<span<?php echo $vrekening->laporan->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $vrekening->laporan->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="vrekening" data-field="x_laporan" name="x<?php echo $vrekening_grid->RowIndex ?>_laporan" id="x<?php echo $vrekening_grid->RowIndex ?>_laporan" value="<?php echo ew_HtmlEncode($vrekening->laporan->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="vrekening" data-field="x_laporan" name="o<?php echo $vrekening_grid->RowIndex ?>_laporan" id="o<?php echo $vrekening_grid->RowIndex ?>_laporan" value="<?php echo ew_HtmlEncode($vrekening->laporan->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($vrekening->status->Visible) { // status ?>
		<td data-name="status">
<?php if ($vrekening->CurrentAction <> "F") { ?>
<span id="el$rowindex$_vrekening_status" class="form-group vrekening_status">
<input type="text" data-table="vrekening" data-field="x_status" name="x<?php echo $vrekening_grid->RowIndex ?>_status" id="x<?php echo $vrekening_grid->RowIndex ?>_status" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($vrekening->status->getPlaceHolder()) ?>" value="<?php echo $vrekening->status->EditValue ?>"<?php echo $vrekening->status->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_vrekening_status" class="form-group vrekening_status">
<span<?php echo $vrekening->status->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $vrekening->status->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="vrekening" data-field="x_status" name="x<?php echo $vrekening_grid->RowIndex ?>_status" id="x<?php echo $vrekening_grid->RowIndex ?>_status" value="<?php echo ew_HtmlEncode($vrekening->status->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="vrekening" data-field="x_status" name="o<?php echo $vrekening_grid->RowIndex ?>_status" id="o<?php echo $vrekening_grid->RowIndex ?>_status" value="<?php echo ew_HtmlEncode($vrekening->status->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($vrekening->parent->Visible) { // parent ?>
		<td data-name="parent">
<?php if ($vrekening->CurrentAction <> "F") { ?>
<?php if ($vrekening->parent->getSessionValue() <> "") { ?>
<span id="el$rowindex$_vrekening_parent" class="form-group vrekening_parent">
<span<?php echo $vrekening->parent->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $vrekening->parent->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $vrekening_grid->RowIndex ?>_parent" name="x<?php echo $vrekening_grid->RowIndex ?>_parent" value="<?php echo ew_HtmlEncode($vrekening->parent->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_vrekening_parent" class="form-group vrekening_parent">
<input type="text" data-table="vrekening" data-field="x_parent" name="x<?php echo $vrekening_grid->RowIndex ?>_parent" id="x<?php echo $vrekening_grid->RowIndex ?>_parent" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($vrekening->parent->getPlaceHolder()) ?>" value="<?php echo $vrekening->parent->EditValue ?>"<?php echo $vrekening->parent->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_vrekening_parent" class="form-group vrekening_parent">
<span<?php echo $vrekening->parent->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $vrekening->parent->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="vrekening" data-field="x_parent" name="x<?php echo $vrekening_grid->RowIndex ?>_parent" id="x<?php echo $vrekening_grid->RowIndex ?>_parent" value="<?php echo ew_HtmlEncode($vrekening->parent->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="vrekening" data-field="x_parent" name="o<?php echo $vrekening_grid->RowIndex ?>_parent" id="o<?php echo $vrekening_grid->RowIndex ?>_parent" value="<?php echo ew_HtmlEncode($vrekening->parent->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($vrekening->keterangan->Visible) { // keterangan ?>
		<td data-name="keterangan">
<?php if ($vrekening->CurrentAction <> "F") { ?>
<span id="el$rowindex$_vrekening_keterangan" class="form-group vrekening_keterangan">
<input type="text" data-table="vrekening" data-field="x_keterangan" name="x<?php echo $vrekening_grid->RowIndex ?>_keterangan" id="x<?php echo $vrekening_grid->RowIndex ?>_keterangan" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($vrekening->keterangan->getPlaceHolder()) ?>" value="<?php echo $vrekening->keterangan->EditValue ?>"<?php echo $vrekening->keterangan->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_vrekening_keterangan" class="form-group vrekening_keterangan">
<span<?php echo $vrekening->keterangan->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $vrekening->keterangan->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="vrekening" data-field="x_keterangan" name="x<?php echo $vrekening_grid->RowIndex ?>_keterangan" id="x<?php echo $vrekening_grid->RowIndex ?>_keterangan" value="<?php echo ew_HtmlEncode($vrekening->keterangan->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="vrekening" data-field="x_keterangan" name="o<?php echo $vrekening_grid->RowIndex ?>_keterangan" id="o<?php echo $vrekening_grid->RowIndex ?>_keterangan" value="<?php echo ew_HtmlEncode($vrekening->keterangan->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($vrekening->active->Visible) { // active ?>
		<td data-name="active">
<?php if ($vrekening->CurrentAction <> "F") { ?>
<span id="el$rowindex$_vrekening_active" class="form-group vrekening_active">
<div id="tp_x<?php echo $vrekening_grid->RowIndex ?>_active" class="ewTemplate"><input type="radio" data-table="vrekening" data-field="x_active" data-value-separator="<?php echo $vrekening->active->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $vrekening_grid->RowIndex ?>_active" id="x<?php echo $vrekening_grid->RowIndex ?>_active" value="{value}"<?php echo $vrekening->active->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $vrekening_grid->RowIndex ?>_active" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $vrekening->active->RadioButtonListHtml(FALSE, "x{$vrekening_grid->RowIndex}_active") ?>
</div></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_vrekening_active" class="form-group vrekening_active">
<span<?php echo $vrekening->active->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $vrekening->active->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="vrekening" data-field="x_active" name="x<?php echo $vrekening_grid->RowIndex ?>_active" id="x<?php echo $vrekening_grid->RowIndex ?>_active" value="<?php echo ew_HtmlEncode($vrekening->active->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="vrekening" data-field="x_active" name="o<?php echo $vrekening_grid->RowIndex ?>_active" id="o<?php echo $vrekening_grid->RowIndex ?>_active" value="<?php echo ew_HtmlEncode($vrekening->active->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$vrekening_grid->ListOptions->Render("body", "right", $vrekening_grid->RowCnt);
?>
<script type="text/javascript">
fvrekeninggrid.UpdateOpts(<?php echo $vrekening_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($vrekening->CurrentMode == "add" || $vrekening->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $vrekening_grid->FormKeyCountName ?>" id="<?php echo $vrekening_grid->FormKeyCountName ?>" value="<?php echo $vrekening_grid->KeyCount ?>">
<?php echo $vrekening_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($vrekening->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $vrekening_grid->FormKeyCountName ?>" id="<?php echo $vrekening_grid->FormKeyCountName ?>" value="<?php echo $vrekening_grid->KeyCount ?>">
<?php echo $vrekening_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($vrekening->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fvrekeninggrid">
</div>
<?php

// Close recordset
if ($vrekening_grid->Recordset)
	$vrekening_grid->Recordset->Close();
?>
<?php if ($vrekening_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($vrekening_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($vrekening_grid->TotalRecs == 0 && $vrekening->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($vrekening_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($vrekening->Export == "") { ?>
<script type="text/javascript">
fvrekeninggrid.Init();
</script>
<?php } ?>
<?php
$vrekening_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$vrekening_grid->Page_Terminate();
?>
