<?php

// Global variable for table object
$tjurnaltransaksi = NULL;

//
// Table class for tjurnaltransaksi
//
class ctjurnaltransaksi extends cTable {
	var $tanggal;
	var $periode;
	var $id;
	var $nomor;
	var $transaksi;
	var $referensi;
	var $model;
	var $rekening;
	var $debet;
	var $credit;
	var $pembayaran_;
	var $bunga_;
	var $denda_;
	var $titipan_;
	var $administrasi_;
	var $modal_;
	var $pinjaman_;
	var $biaya_;
	var $kantor;
	var $keterangan;
	var $active;
	var $ip;
	var $status;
	var $user;
	var $created;
	var $modified;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'tjurnaltransaksi';
		$this->TableName = 'tjurnaltransaksi';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`tjurnaltransaksi`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// tanggal
		$this->tanggal = new cField('tjurnaltransaksi', 'tjurnaltransaksi', 'x_tanggal', 'tanggal', '`tanggal`', ew_CastDateFieldForLike('`tanggal`', 0, "DB"), 135, 0, FALSE, '`tanggal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tanggal->Sortable = TRUE; // Allow sort
		$this->tanggal->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['tanggal'] = &$this->tanggal;

		// periode
		$this->periode = new cField('tjurnaltransaksi', 'tjurnaltransaksi', 'x_periode', 'periode', '`periode`', '`periode`', 200, -1, FALSE, '`periode`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->periode->Sortable = TRUE; // Allow sort
		$this->fields['periode'] = &$this->periode;

		// id
		$this->id = new cField('tjurnaltransaksi', 'tjurnaltransaksi', 'x_id', 'id', '`id`', '`id`', 200, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->id->Sortable = TRUE; // Allow sort
		$this->fields['id'] = &$this->id;

		// nomor
		$this->nomor = new cField('tjurnaltransaksi', 'tjurnaltransaksi', 'x_nomor', 'nomor', '`nomor`', '`nomor`', 20, -1, FALSE, '`nomor`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nomor->Sortable = TRUE; // Allow sort
		$this->nomor->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nomor'] = &$this->nomor;

		// transaksi
		$this->transaksi = new cField('tjurnaltransaksi', 'tjurnaltransaksi', 'x_transaksi', 'transaksi', '`transaksi`', '`transaksi`', 200, -1, FALSE, '`transaksi`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->transaksi->Sortable = TRUE; // Allow sort
		$this->fields['transaksi'] = &$this->transaksi;

		// referensi
		$this->referensi = new cField('tjurnaltransaksi', 'tjurnaltransaksi', 'x_referensi', 'referensi', '`referensi`', '`referensi`', 200, -1, FALSE, '`referensi`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->referensi->Sortable = TRUE; // Allow sort
		$this->fields['referensi'] = &$this->referensi;

		// model
		$this->model = new cField('tjurnaltransaksi', 'tjurnaltransaksi', 'x_model', 'model', '`model`', '`model`', 200, -1, FALSE, '`model`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->model->Sortable = TRUE; // Allow sort
		$this->fields['model'] = &$this->model;

		// rekening
		$this->rekening = new cField('tjurnaltransaksi', 'tjurnaltransaksi', 'x_rekening', 'rekening', '`rekening`', '`rekening`', 200, -1, FALSE, '`rekening`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->rekening->Sortable = TRUE; // Allow sort
		$this->fields['rekening'] = &$this->rekening;

		// debet
		$this->debet = new cField('tjurnaltransaksi', 'tjurnaltransaksi', 'x_debet', 'debet', '`debet`', '`debet`', 5, -1, FALSE, '`debet`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->debet->Sortable = TRUE; // Allow sort
		$this->debet->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['debet'] = &$this->debet;

		// credit
		$this->credit = new cField('tjurnaltransaksi', 'tjurnaltransaksi', 'x_credit', 'credit', '`credit`', '`credit`', 5, -1, FALSE, '`credit`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->credit->Sortable = TRUE; // Allow sort
		$this->credit->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['credit'] = &$this->credit;

		// pembayaran_
		$this->pembayaran_ = new cField('tjurnaltransaksi', 'tjurnaltransaksi', 'x_pembayaran_', 'pembayaran_', '`pembayaran_`', '`pembayaran_`', 5, -1, FALSE, '`pembayaran_`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pembayaran_->Sortable = TRUE; // Allow sort
		$this->pembayaran_->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['pembayaran_'] = &$this->pembayaran_;

		// bunga_
		$this->bunga_ = new cField('tjurnaltransaksi', 'tjurnaltransaksi', 'x_bunga_', 'bunga_', '`bunga_`', '`bunga_`', 5, -1, FALSE, '`bunga_`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->bunga_->Sortable = TRUE; // Allow sort
		$this->bunga_->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['bunga_'] = &$this->bunga_;

		// denda_
		$this->denda_ = new cField('tjurnaltransaksi', 'tjurnaltransaksi', 'x_denda_', 'denda_', '`denda_`', '`denda_`', 5, -1, FALSE, '`denda_`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->denda_->Sortable = TRUE; // Allow sort
		$this->denda_->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['denda_'] = &$this->denda_;

		// titipan_
		$this->titipan_ = new cField('tjurnaltransaksi', 'tjurnaltransaksi', 'x_titipan_', 'titipan_', '`titipan_`', '`titipan_`', 5, -1, FALSE, '`titipan_`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->titipan_->Sortable = TRUE; // Allow sort
		$this->titipan_->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['titipan_'] = &$this->titipan_;

		// administrasi_
		$this->administrasi_ = new cField('tjurnaltransaksi', 'tjurnaltransaksi', 'x_administrasi_', 'administrasi_', '`administrasi_`', '`administrasi_`', 5, -1, FALSE, '`administrasi_`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->administrasi_->Sortable = TRUE; // Allow sort
		$this->administrasi_->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['administrasi_'] = &$this->administrasi_;

		// modal_
		$this->modal_ = new cField('tjurnaltransaksi', 'tjurnaltransaksi', 'x_modal_', 'modal_', '`modal_`', '`modal_`', 5, -1, FALSE, '`modal_`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->modal_->Sortable = TRUE; // Allow sort
		$this->modal_->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['modal_'] = &$this->modal_;

		// pinjaman_
		$this->pinjaman_ = new cField('tjurnaltransaksi', 'tjurnaltransaksi', 'x_pinjaman_', 'pinjaman_', '`pinjaman_`', '`pinjaman_`', 5, -1, FALSE, '`pinjaman_`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pinjaman_->Sortable = TRUE; // Allow sort
		$this->pinjaman_->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['pinjaman_'] = &$this->pinjaman_;

		// biaya_
		$this->biaya_ = new cField('tjurnaltransaksi', 'tjurnaltransaksi', 'x_biaya_', 'biaya_', '`biaya_`', '`biaya_`', 5, -1, FALSE, '`biaya_`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->biaya_->Sortable = TRUE; // Allow sort
		$this->biaya_->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['biaya_'] = &$this->biaya_;

		// kantor
		$this->kantor = new cField('tjurnaltransaksi', 'tjurnaltransaksi', 'x_kantor', 'kantor', '`kantor`', '`kantor`', 200, -1, FALSE, '`kantor`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->kantor->Sortable = TRUE; // Allow sort
		$this->fields['kantor'] = &$this->kantor;

		// keterangan
		$this->keterangan = new cField('tjurnaltransaksi', 'tjurnaltransaksi', 'x_keterangan', 'keterangan', '`keterangan`', '`keterangan`', 200, -1, FALSE, '`keterangan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->keterangan->Sortable = TRUE; // Allow sort
		$this->fields['keterangan'] = &$this->keterangan;

		// active
		$this->active = new cField('tjurnaltransaksi', 'tjurnaltransaksi', 'x_active', 'active', '`active`', '`active`', 202, -1, FALSE, '`active`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->active->Sortable = TRUE; // Allow sort
		$this->active->OptionCount = 2;
		$this->fields['active'] = &$this->active;

		// ip
		$this->ip = new cField('tjurnaltransaksi', 'tjurnaltransaksi', 'x_ip', 'ip', '`ip`', '`ip`', 200, -1, FALSE, '`ip`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->ip->Sortable = TRUE; // Allow sort
		$this->fields['ip'] = &$this->ip;

		// status
		$this->status = new cField('tjurnaltransaksi', 'tjurnaltransaksi', 'x_status', 'status', '`status`', '`status`', 200, -1, FALSE, '`status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->status->Sortable = TRUE; // Allow sort
		$this->fields['status'] = &$this->status;

		// user
		$this->user = new cField('tjurnaltransaksi', 'tjurnaltransaksi', 'x_user', 'user', '`user`', '`user`', 200, -1, FALSE, '`user`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->user->Sortable = TRUE; // Allow sort
		$this->fields['user'] = &$this->user;

		// created
		$this->created = new cField('tjurnaltransaksi', 'tjurnaltransaksi', 'x_created', 'created', '`created`', ew_CastDateFieldForLike('`created`', 0, "DB"), 135, 0, FALSE, '`created`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->created->Sortable = TRUE; // Allow sort
		$this->created->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['created'] = &$this->created;

		// modified
		$this->modified = new cField('tjurnaltransaksi', 'tjurnaltransaksi', 'x_modified', 'modified', '`modified`', ew_CastDateFieldForLike('`modified`', 0, "DB"), 135, 0, FALSE, '`modified`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->modified->Sortable = TRUE; // Allow sort
		$this->modified->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['modified'] = &$this->modified;
	}

	// Set Field Visibility
	function SetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`tjurnaltransaksi`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		$bInsert = $conn->Execute($this->InsertSQL($rs));
		if ($bInsert) {
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('id', $rs))
				ew_AddFilter($where, ew_QuotedName('id', $this->DBID) . '=' . ew_QuotedValue($rs['id'], $this->id->FldDataType, $this->DBID));
			if (array_key_exists('nomor', $rs))
				ew_AddFilter($where, ew_QuotedName('nomor', $this->DBID) . '=' . ew_QuotedValue($rs['nomor'], $this->nomor->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`id` = '@id@' AND `nomor` = @nomor@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		$sKeyFilter = str_replace("@id@", ew_AdjustSql($this->id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		if (!is_numeric($this->nomor->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nomor@", ew_AdjustSql($this->nomor->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "tjurnaltransaksilist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "tjurnaltransaksilist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("tjurnaltransaksiview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("tjurnaltransaksiview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "tjurnaltransaksiadd.php?" . $this->UrlParm($parm);
		else
			$url = "tjurnaltransaksiadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("tjurnaltransaksiedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("tjurnaltransaksiadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("tjurnaltransaksidelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "id:" . ew_VarToJson($this->id->CurrentValue, "string", "'");
		$json .= ",nomor:" . ew_VarToJson($this->nomor->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id->CurrentValue)) {
			$sUrl .= "id=" . urlencode($this->id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->nomor->CurrentValue)) {
			$sUrl .= "&nomor=" . urlencode($this->nomor->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
			for ($i = 0; $i < $cnt; $i++)
				$arKeys[$i] = explode($EW_COMPOSITE_KEY_SEPARATOR, $arKeys[$i]);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
			for ($i = 0; $i < $cnt; $i++)
				$arKeys[$i] = explode($EW_COMPOSITE_KEY_SEPARATOR, $arKeys[$i]);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["id"]))
				$arKey[] = ew_StripSlashes($_POST["id"]);
			elseif (isset($_GET["id"]))
				$arKey[] = ew_StripSlashes($_GET["id"]);
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["nomor"]))
				$arKey[] = ew_StripSlashes($_POST["nomor"]);
			elseif (isset($_GET["nomor"]))
				$arKey[] = ew_StripSlashes($_GET["nomor"]);
			else
				$arKeys = NULL; // Do not setup
			if (is_array($arKeys)) $arKeys[] = $arKey;

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_array($key) || count($key) <> 2)
					continue; // Just skip so other keys will still work
				if (!is_numeric($key[1])) // nomor
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->id->CurrentValue = $key[0];
			$this->nomor->CurrentValue = $key[1];
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->tanggal->setDbValue($rs->fields('tanggal'));
		$this->periode->setDbValue($rs->fields('periode'));
		$this->id->setDbValue($rs->fields('id'));
		$this->nomor->setDbValue($rs->fields('nomor'));
		$this->transaksi->setDbValue($rs->fields('transaksi'));
		$this->referensi->setDbValue($rs->fields('referensi'));
		$this->model->setDbValue($rs->fields('model'));
		$this->rekening->setDbValue($rs->fields('rekening'));
		$this->debet->setDbValue($rs->fields('debet'));
		$this->credit->setDbValue($rs->fields('credit'));
		$this->pembayaran_->setDbValue($rs->fields('pembayaran_'));
		$this->bunga_->setDbValue($rs->fields('bunga_'));
		$this->denda_->setDbValue($rs->fields('denda_'));
		$this->titipan_->setDbValue($rs->fields('titipan_'));
		$this->administrasi_->setDbValue($rs->fields('administrasi_'));
		$this->modal_->setDbValue($rs->fields('modal_'));
		$this->pinjaman_->setDbValue($rs->fields('pinjaman_'));
		$this->biaya_->setDbValue($rs->fields('biaya_'));
		$this->kantor->setDbValue($rs->fields('kantor'));
		$this->keterangan->setDbValue($rs->fields('keterangan'));
		$this->active->setDbValue($rs->fields('active'));
		$this->ip->setDbValue($rs->fields('ip'));
		$this->status->setDbValue($rs->fields('status'));
		$this->user->setDbValue($rs->fields('user'));
		$this->created->setDbValue($rs->fields('created'));
		$this->modified->setDbValue($rs->fields('modified'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// tanggal
		// periode
		// id
		// nomor
		// transaksi
		// referensi
		// model
		// rekening
		// debet
		// credit
		// pembayaran_
		// bunga_
		// denda_
		// titipan_
		// administrasi_
		// modal_
		// pinjaman_
		// biaya_
		// kantor
		// keterangan
		// active
		// ip
		// status
		// user
		// created
		// modified
		// tanggal

		$this->tanggal->ViewValue = $this->tanggal->CurrentValue;
		$this->tanggal->ViewValue = ew_FormatDateTime($this->tanggal->ViewValue, 0);
		$this->tanggal->ViewCustomAttributes = "";

		// periode
		$this->periode->ViewValue = $this->periode->CurrentValue;
		$this->periode->ViewCustomAttributes = "";

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// nomor
		$this->nomor->ViewValue = $this->nomor->CurrentValue;
		$this->nomor->ViewCustomAttributes = "";

		// transaksi
		$this->transaksi->ViewValue = $this->transaksi->CurrentValue;
		$this->transaksi->ViewCustomAttributes = "";

		// referensi
		$this->referensi->ViewValue = $this->referensi->CurrentValue;
		$this->referensi->ViewCustomAttributes = "";

		// model
		$this->model->ViewValue = $this->model->CurrentValue;
		$this->model->ViewCustomAttributes = "";

		// rekening
		$this->rekening->ViewValue = $this->rekening->CurrentValue;
		$this->rekening->ViewCustomAttributes = "";

		// debet
		$this->debet->ViewValue = $this->debet->CurrentValue;
		$this->debet->ViewCustomAttributes = "";

		// credit
		$this->credit->ViewValue = $this->credit->CurrentValue;
		$this->credit->ViewCustomAttributes = "";

		// pembayaran_
		$this->pembayaran_->ViewValue = $this->pembayaran_->CurrentValue;
		$this->pembayaran_->ViewCustomAttributes = "";

		// bunga_
		$this->bunga_->ViewValue = $this->bunga_->CurrentValue;
		$this->bunga_->ViewCustomAttributes = "";

		// denda_
		$this->denda_->ViewValue = $this->denda_->CurrentValue;
		$this->denda_->ViewCustomAttributes = "";

		// titipan_
		$this->titipan_->ViewValue = $this->titipan_->CurrentValue;
		$this->titipan_->ViewCustomAttributes = "";

		// administrasi_
		$this->administrasi_->ViewValue = $this->administrasi_->CurrentValue;
		$this->administrasi_->ViewCustomAttributes = "";

		// modal_
		$this->modal_->ViewValue = $this->modal_->CurrentValue;
		$this->modal_->ViewCustomAttributes = "";

		// pinjaman_
		$this->pinjaman_->ViewValue = $this->pinjaman_->CurrentValue;
		$this->pinjaman_->ViewCustomAttributes = "";

		// biaya_
		$this->biaya_->ViewValue = $this->biaya_->CurrentValue;
		$this->biaya_->ViewCustomAttributes = "";

		// kantor
		$this->kantor->ViewValue = $this->kantor->CurrentValue;
		$this->kantor->ViewCustomAttributes = "";

		// keterangan
		$this->keterangan->ViewValue = $this->keterangan->CurrentValue;
		$this->keterangan->ViewCustomAttributes = "";

		// active
		if (strval($this->active->CurrentValue) <> "") {
			$this->active->ViewValue = $this->active->OptionCaption($this->active->CurrentValue);
		} else {
			$this->active->ViewValue = NULL;
		}
		$this->active->ViewCustomAttributes = "";

		// ip
		$this->ip->ViewValue = $this->ip->CurrentValue;
		$this->ip->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		$this->status->ViewCustomAttributes = "";

		// user
		$this->user->ViewValue = $this->user->CurrentValue;
		$this->user->ViewCustomAttributes = "";

		// created
		$this->created->ViewValue = $this->created->CurrentValue;
		$this->created->ViewValue = ew_FormatDateTime($this->created->ViewValue, 0);
		$this->created->ViewCustomAttributes = "";

		// modified
		$this->modified->ViewValue = $this->modified->CurrentValue;
		$this->modified->ViewValue = ew_FormatDateTime($this->modified->ViewValue, 0);
		$this->modified->ViewCustomAttributes = "";

		// tanggal
		$this->tanggal->LinkCustomAttributes = "";
		$this->tanggal->HrefValue = "";
		$this->tanggal->TooltipValue = "";

		// periode
		$this->periode->LinkCustomAttributes = "";
		$this->periode->HrefValue = "";
		$this->periode->TooltipValue = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// nomor
		$this->nomor->LinkCustomAttributes = "";
		$this->nomor->HrefValue = "";
		$this->nomor->TooltipValue = "";

		// transaksi
		$this->transaksi->LinkCustomAttributes = "";
		$this->transaksi->HrefValue = "";
		$this->transaksi->TooltipValue = "";

		// referensi
		$this->referensi->LinkCustomAttributes = "";
		$this->referensi->HrefValue = "";
		$this->referensi->TooltipValue = "";

		// model
		$this->model->LinkCustomAttributes = "";
		$this->model->HrefValue = "";
		$this->model->TooltipValue = "";

		// rekening
		$this->rekening->LinkCustomAttributes = "";
		$this->rekening->HrefValue = "";
		$this->rekening->TooltipValue = "";

		// debet
		$this->debet->LinkCustomAttributes = "";
		$this->debet->HrefValue = "";
		$this->debet->TooltipValue = "";

		// credit
		$this->credit->LinkCustomAttributes = "";
		$this->credit->HrefValue = "";
		$this->credit->TooltipValue = "";

		// pembayaran_
		$this->pembayaran_->LinkCustomAttributes = "";
		$this->pembayaran_->HrefValue = "";
		$this->pembayaran_->TooltipValue = "";

		// bunga_
		$this->bunga_->LinkCustomAttributes = "";
		$this->bunga_->HrefValue = "";
		$this->bunga_->TooltipValue = "";

		// denda_
		$this->denda_->LinkCustomAttributes = "";
		$this->denda_->HrefValue = "";
		$this->denda_->TooltipValue = "";

		// titipan_
		$this->titipan_->LinkCustomAttributes = "";
		$this->titipan_->HrefValue = "";
		$this->titipan_->TooltipValue = "";

		// administrasi_
		$this->administrasi_->LinkCustomAttributes = "";
		$this->administrasi_->HrefValue = "";
		$this->administrasi_->TooltipValue = "";

		// modal_
		$this->modal_->LinkCustomAttributes = "";
		$this->modal_->HrefValue = "";
		$this->modal_->TooltipValue = "";

		// pinjaman_
		$this->pinjaman_->LinkCustomAttributes = "";
		$this->pinjaman_->HrefValue = "";
		$this->pinjaman_->TooltipValue = "";

		// biaya_
		$this->biaya_->LinkCustomAttributes = "";
		$this->biaya_->HrefValue = "";
		$this->biaya_->TooltipValue = "";

		// kantor
		$this->kantor->LinkCustomAttributes = "";
		$this->kantor->HrefValue = "";
		$this->kantor->TooltipValue = "";

		// keterangan
		$this->keterangan->LinkCustomAttributes = "";
		$this->keterangan->HrefValue = "";
		$this->keterangan->TooltipValue = "";

		// active
		$this->active->LinkCustomAttributes = "";
		$this->active->HrefValue = "";
		$this->active->TooltipValue = "";

		// ip
		$this->ip->LinkCustomAttributes = "";
		$this->ip->HrefValue = "";
		$this->ip->TooltipValue = "";

		// status
		$this->status->LinkCustomAttributes = "";
		$this->status->HrefValue = "";
		$this->status->TooltipValue = "";

		// user
		$this->user->LinkCustomAttributes = "";
		$this->user->HrefValue = "";
		$this->user->TooltipValue = "";

		// created
		$this->created->LinkCustomAttributes = "";
		$this->created->HrefValue = "";
		$this->created->TooltipValue = "";

		// modified
		$this->modified->LinkCustomAttributes = "";
		$this->modified->HrefValue = "";
		$this->modified->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// tanggal
		$this->tanggal->EditAttrs["class"] = "form-control";
		$this->tanggal->EditCustomAttributes = "";
		$this->tanggal->EditValue = ew_FormatDateTime($this->tanggal->CurrentValue, 8);
		$this->tanggal->PlaceHolder = ew_RemoveHtml($this->tanggal->FldCaption());

		// periode
		$this->periode->EditAttrs["class"] = "form-control";
		$this->periode->EditCustomAttributes = "";
		$this->periode->EditValue = $this->periode->CurrentValue;
		$this->periode->PlaceHolder = ew_RemoveHtml($this->periode->FldCaption());

		// id
		$this->id->EditAttrs["class"] = "form-control";
		$this->id->EditCustomAttributes = "";
		$this->id->EditValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// nomor
		$this->nomor->EditAttrs["class"] = "form-control";
		$this->nomor->EditCustomAttributes = "";
		$this->nomor->EditValue = $this->nomor->CurrentValue;
		$this->nomor->ViewCustomAttributes = "";

		// transaksi
		$this->transaksi->EditAttrs["class"] = "form-control";
		$this->transaksi->EditCustomAttributes = "";
		$this->transaksi->EditValue = $this->transaksi->CurrentValue;
		$this->transaksi->PlaceHolder = ew_RemoveHtml($this->transaksi->FldCaption());

		// referensi
		$this->referensi->EditAttrs["class"] = "form-control";
		$this->referensi->EditCustomAttributes = "";
		$this->referensi->EditValue = $this->referensi->CurrentValue;
		$this->referensi->PlaceHolder = ew_RemoveHtml($this->referensi->FldCaption());

		// model
		$this->model->EditAttrs["class"] = "form-control";
		$this->model->EditCustomAttributes = "";
		$this->model->EditValue = $this->model->CurrentValue;
		$this->model->PlaceHolder = ew_RemoveHtml($this->model->FldCaption());

		// rekening
		$this->rekening->EditAttrs["class"] = "form-control";
		$this->rekening->EditCustomAttributes = "";
		$this->rekening->EditValue = $this->rekening->CurrentValue;
		$this->rekening->PlaceHolder = ew_RemoveHtml($this->rekening->FldCaption());

		// debet
		$this->debet->EditAttrs["class"] = "form-control";
		$this->debet->EditCustomAttributes = "";
		$this->debet->EditValue = $this->debet->CurrentValue;
		$this->debet->PlaceHolder = ew_RemoveHtml($this->debet->FldCaption());
		if (strval($this->debet->EditValue) <> "" && is_numeric($this->debet->EditValue)) $this->debet->EditValue = ew_FormatNumber($this->debet->EditValue, -2, -1, -2, 0);

		// credit
		$this->credit->EditAttrs["class"] = "form-control";
		$this->credit->EditCustomAttributes = "";
		$this->credit->EditValue = $this->credit->CurrentValue;
		$this->credit->PlaceHolder = ew_RemoveHtml($this->credit->FldCaption());
		if (strval($this->credit->EditValue) <> "" && is_numeric($this->credit->EditValue)) $this->credit->EditValue = ew_FormatNumber($this->credit->EditValue, -2, -1, -2, 0);

		// pembayaran_
		$this->pembayaran_->EditAttrs["class"] = "form-control";
		$this->pembayaran_->EditCustomAttributes = "";
		$this->pembayaran_->EditValue = $this->pembayaran_->CurrentValue;
		$this->pembayaran_->PlaceHolder = ew_RemoveHtml($this->pembayaran_->FldCaption());
		if (strval($this->pembayaran_->EditValue) <> "" && is_numeric($this->pembayaran_->EditValue)) $this->pembayaran_->EditValue = ew_FormatNumber($this->pembayaran_->EditValue, -2, -1, -2, 0);

		// bunga_
		$this->bunga_->EditAttrs["class"] = "form-control";
		$this->bunga_->EditCustomAttributes = "";
		$this->bunga_->EditValue = $this->bunga_->CurrentValue;
		$this->bunga_->PlaceHolder = ew_RemoveHtml($this->bunga_->FldCaption());
		if (strval($this->bunga_->EditValue) <> "" && is_numeric($this->bunga_->EditValue)) $this->bunga_->EditValue = ew_FormatNumber($this->bunga_->EditValue, -2, -1, -2, 0);

		// denda_
		$this->denda_->EditAttrs["class"] = "form-control";
		$this->denda_->EditCustomAttributes = "";
		$this->denda_->EditValue = $this->denda_->CurrentValue;
		$this->denda_->PlaceHolder = ew_RemoveHtml($this->denda_->FldCaption());
		if (strval($this->denda_->EditValue) <> "" && is_numeric($this->denda_->EditValue)) $this->denda_->EditValue = ew_FormatNumber($this->denda_->EditValue, -2, -1, -2, 0);

		// titipan_
		$this->titipan_->EditAttrs["class"] = "form-control";
		$this->titipan_->EditCustomAttributes = "";
		$this->titipan_->EditValue = $this->titipan_->CurrentValue;
		$this->titipan_->PlaceHolder = ew_RemoveHtml($this->titipan_->FldCaption());
		if (strval($this->titipan_->EditValue) <> "" && is_numeric($this->titipan_->EditValue)) $this->titipan_->EditValue = ew_FormatNumber($this->titipan_->EditValue, -2, -1, -2, 0);

		// administrasi_
		$this->administrasi_->EditAttrs["class"] = "form-control";
		$this->administrasi_->EditCustomAttributes = "";
		$this->administrasi_->EditValue = $this->administrasi_->CurrentValue;
		$this->administrasi_->PlaceHolder = ew_RemoveHtml($this->administrasi_->FldCaption());
		if (strval($this->administrasi_->EditValue) <> "" && is_numeric($this->administrasi_->EditValue)) $this->administrasi_->EditValue = ew_FormatNumber($this->administrasi_->EditValue, -2, -1, -2, 0);

		// modal_
		$this->modal_->EditAttrs["class"] = "form-control";
		$this->modal_->EditCustomAttributes = "";
		$this->modal_->EditValue = $this->modal_->CurrentValue;
		$this->modal_->PlaceHolder = ew_RemoveHtml($this->modal_->FldCaption());
		if (strval($this->modal_->EditValue) <> "" && is_numeric($this->modal_->EditValue)) $this->modal_->EditValue = ew_FormatNumber($this->modal_->EditValue, -2, -1, -2, 0);

		// pinjaman_
		$this->pinjaman_->EditAttrs["class"] = "form-control";
		$this->pinjaman_->EditCustomAttributes = "";
		$this->pinjaman_->EditValue = $this->pinjaman_->CurrentValue;
		$this->pinjaman_->PlaceHolder = ew_RemoveHtml($this->pinjaman_->FldCaption());
		if (strval($this->pinjaman_->EditValue) <> "" && is_numeric($this->pinjaman_->EditValue)) $this->pinjaman_->EditValue = ew_FormatNumber($this->pinjaman_->EditValue, -2, -1, -2, 0);

		// biaya_
		$this->biaya_->EditAttrs["class"] = "form-control";
		$this->biaya_->EditCustomAttributes = "";
		$this->biaya_->EditValue = $this->biaya_->CurrentValue;
		$this->biaya_->PlaceHolder = ew_RemoveHtml($this->biaya_->FldCaption());
		if (strval($this->biaya_->EditValue) <> "" && is_numeric($this->biaya_->EditValue)) $this->biaya_->EditValue = ew_FormatNumber($this->biaya_->EditValue, -2, -1, -2, 0);

		// kantor
		$this->kantor->EditAttrs["class"] = "form-control";
		$this->kantor->EditCustomAttributes = "";
		$this->kantor->EditValue = $this->kantor->CurrentValue;
		$this->kantor->PlaceHolder = ew_RemoveHtml($this->kantor->FldCaption());

		// keterangan
		$this->keterangan->EditAttrs["class"] = "form-control";
		$this->keterangan->EditCustomAttributes = "";
		$this->keterangan->EditValue = $this->keterangan->CurrentValue;
		$this->keterangan->PlaceHolder = ew_RemoveHtml($this->keterangan->FldCaption());

		// active
		$this->active->EditCustomAttributes = "";
		$this->active->EditValue = $this->active->Options(FALSE);

		// ip
		$this->ip->EditAttrs["class"] = "form-control";
		$this->ip->EditCustomAttributes = "";
		$this->ip->EditValue = $this->ip->CurrentValue;
		$this->ip->PlaceHolder = ew_RemoveHtml($this->ip->FldCaption());

		// status
		$this->status->EditAttrs["class"] = "form-control";
		$this->status->EditCustomAttributes = "";
		$this->status->EditValue = $this->status->CurrentValue;
		$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

		// user
		$this->user->EditAttrs["class"] = "form-control";
		$this->user->EditCustomAttributes = "";
		$this->user->EditValue = $this->user->CurrentValue;
		$this->user->PlaceHolder = ew_RemoveHtml($this->user->FldCaption());

		// created
		$this->created->EditAttrs["class"] = "form-control";
		$this->created->EditCustomAttributes = "";
		$this->created->EditValue = ew_FormatDateTime($this->created->CurrentValue, 8);
		$this->created->PlaceHolder = ew_RemoveHtml($this->created->FldCaption());

		// modified
		$this->modified->EditAttrs["class"] = "form-control";
		$this->modified->EditCustomAttributes = "";
		$this->modified->EditValue = ew_FormatDateTime($this->modified->CurrentValue, 8);
		$this->modified->PlaceHolder = ew_RemoveHtml($this->modified->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->tanggal->Exportable) $Doc->ExportCaption($this->tanggal);
					if ($this->periode->Exportable) $Doc->ExportCaption($this->periode);
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->nomor->Exportable) $Doc->ExportCaption($this->nomor);
					if ($this->transaksi->Exportable) $Doc->ExportCaption($this->transaksi);
					if ($this->referensi->Exportable) $Doc->ExportCaption($this->referensi);
					if ($this->model->Exportable) $Doc->ExportCaption($this->model);
					if ($this->rekening->Exportable) $Doc->ExportCaption($this->rekening);
					if ($this->debet->Exportable) $Doc->ExportCaption($this->debet);
					if ($this->credit->Exportable) $Doc->ExportCaption($this->credit);
					if ($this->pembayaran_->Exportable) $Doc->ExportCaption($this->pembayaran_);
					if ($this->bunga_->Exportable) $Doc->ExportCaption($this->bunga_);
					if ($this->denda_->Exportable) $Doc->ExportCaption($this->denda_);
					if ($this->titipan_->Exportable) $Doc->ExportCaption($this->titipan_);
					if ($this->administrasi_->Exportable) $Doc->ExportCaption($this->administrasi_);
					if ($this->modal_->Exportable) $Doc->ExportCaption($this->modal_);
					if ($this->pinjaman_->Exportable) $Doc->ExportCaption($this->pinjaman_);
					if ($this->biaya_->Exportable) $Doc->ExportCaption($this->biaya_);
					if ($this->kantor->Exportable) $Doc->ExportCaption($this->kantor);
					if ($this->keterangan->Exportable) $Doc->ExportCaption($this->keterangan);
					if ($this->active->Exportable) $Doc->ExportCaption($this->active);
					if ($this->ip->Exportable) $Doc->ExportCaption($this->ip);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->user->Exportable) $Doc->ExportCaption($this->user);
					if ($this->created->Exportable) $Doc->ExportCaption($this->created);
					if ($this->modified->Exportable) $Doc->ExportCaption($this->modified);
				} else {
					if ($this->tanggal->Exportable) $Doc->ExportCaption($this->tanggal);
					if ($this->periode->Exportable) $Doc->ExportCaption($this->periode);
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->nomor->Exportable) $Doc->ExportCaption($this->nomor);
					if ($this->transaksi->Exportable) $Doc->ExportCaption($this->transaksi);
					if ($this->referensi->Exportable) $Doc->ExportCaption($this->referensi);
					if ($this->model->Exportable) $Doc->ExportCaption($this->model);
					if ($this->rekening->Exportable) $Doc->ExportCaption($this->rekening);
					if ($this->debet->Exportable) $Doc->ExportCaption($this->debet);
					if ($this->credit->Exportable) $Doc->ExportCaption($this->credit);
					if ($this->pembayaran_->Exportable) $Doc->ExportCaption($this->pembayaran_);
					if ($this->bunga_->Exportable) $Doc->ExportCaption($this->bunga_);
					if ($this->denda_->Exportable) $Doc->ExportCaption($this->denda_);
					if ($this->titipan_->Exportable) $Doc->ExportCaption($this->titipan_);
					if ($this->administrasi_->Exportable) $Doc->ExportCaption($this->administrasi_);
					if ($this->modal_->Exportable) $Doc->ExportCaption($this->modal_);
					if ($this->pinjaman_->Exportable) $Doc->ExportCaption($this->pinjaman_);
					if ($this->biaya_->Exportable) $Doc->ExportCaption($this->biaya_);
					if ($this->kantor->Exportable) $Doc->ExportCaption($this->kantor);
					if ($this->keterangan->Exportable) $Doc->ExportCaption($this->keterangan);
					if ($this->active->Exportable) $Doc->ExportCaption($this->active);
					if ($this->ip->Exportable) $Doc->ExportCaption($this->ip);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->user->Exportable) $Doc->ExportCaption($this->user);
					if ($this->created->Exportable) $Doc->ExportCaption($this->created);
					if ($this->modified->Exportable) $Doc->ExportCaption($this->modified);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->tanggal->Exportable) $Doc->ExportField($this->tanggal);
						if ($this->periode->Exportable) $Doc->ExportField($this->periode);
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->nomor->Exportable) $Doc->ExportField($this->nomor);
						if ($this->transaksi->Exportable) $Doc->ExportField($this->transaksi);
						if ($this->referensi->Exportable) $Doc->ExportField($this->referensi);
						if ($this->model->Exportable) $Doc->ExportField($this->model);
						if ($this->rekening->Exportable) $Doc->ExportField($this->rekening);
						if ($this->debet->Exportable) $Doc->ExportField($this->debet);
						if ($this->credit->Exportable) $Doc->ExportField($this->credit);
						if ($this->pembayaran_->Exportable) $Doc->ExportField($this->pembayaran_);
						if ($this->bunga_->Exportable) $Doc->ExportField($this->bunga_);
						if ($this->denda_->Exportable) $Doc->ExportField($this->denda_);
						if ($this->titipan_->Exportable) $Doc->ExportField($this->titipan_);
						if ($this->administrasi_->Exportable) $Doc->ExportField($this->administrasi_);
						if ($this->modal_->Exportable) $Doc->ExportField($this->modal_);
						if ($this->pinjaman_->Exportable) $Doc->ExportField($this->pinjaman_);
						if ($this->biaya_->Exportable) $Doc->ExportField($this->biaya_);
						if ($this->kantor->Exportable) $Doc->ExportField($this->kantor);
						if ($this->keterangan->Exportable) $Doc->ExportField($this->keterangan);
						if ($this->active->Exportable) $Doc->ExportField($this->active);
						if ($this->ip->Exportable) $Doc->ExportField($this->ip);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->user->Exportable) $Doc->ExportField($this->user);
						if ($this->created->Exportable) $Doc->ExportField($this->created);
						if ($this->modified->Exportable) $Doc->ExportField($this->modified);
					} else {
						if ($this->tanggal->Exportable) $Doc->ExportField($this->tanggal);
						if ($this->periode->Exportable) $Doc->ExportField($this->periode);
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->nomor->Exportable) $Doc->ExportField($this->nomor);
						if ($this->transaksi->Exportable) $Doc->ExportField($this->transaksi);
						if ($this->referensi->Exportable) $Doc->ExportField($this->referensi);
						if ($this->model->Exportable) $Doc->ExportField($this->model);
						if ($this->rekening->Exportable) $Doc->ExportField($this->rekening);
						if ($this->debet->Exportable) $Doc->ExportField($this->debet);
						if ($this->credit->Exportable) $Doc->ExportField($this->credit);
						if ($this->pembayaran_->Exportable) $Doc->ExportField($this->pembayaran_);
						if ($this->bunga_->Exportable) $Doc->ExportField($this->bunga_);
						if ($this->denda_->Exportable) $Doc->ExportField($this->denda_);
						if ($this->titipan_->Exportable) $Doc->ExportField($this->titipan_);
						if ($this->administrasi_->Exportable) $Doc->ExportField($this->administrasi_);
						if ($this->modal_->Exportable) $Doc->ExportField($this->modal_);
						if ($this->pinjaman_->Exportable) $Doc->ExportField($this->pinjaman_);
						if ($this->biaya_->Exportable) $Doc->ExportField($this->biaya_);
						if ($this->kantor->Exportable) $Doc->ExportField($this->kantor);
						if ($this->keterangan->Exportable) $Doc->ExportField($this->keterangan);
						if ($this->active->Exportable) $Doc->ExportField($this->active);
						if ($this->ip->Exportable) $Doc->ExportField($this->ip);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->user->Exportable) $Doc->ExportField($this->user);
						if ($this->created->Exportable) $Doc->ExportField($this->created);
						if ($this->modified->Exportable) $Doc->ExportField($this->modified);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
