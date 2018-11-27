<?php

// Global variable for table object
$tanggota = NULL;

//
// Table class for tanggota
//
class ctanggota extends cTable {
	var $registrasi;
	var $periode;
	var $id;
	var $anggota;
	var $namaanggota;
	var $alamat;
	var $tempatlahir;
	var $tanggallahir;
	var $kelamin;
	var $pekerjaan;
	var $telepon;
	var $hp;
	var $fax;
	var $_email;
	var $website;
	var $jenisanggota;
	var $model;
	var $namakantor;
	var $alamatkantor;
	var $wilayah;
	var $petugas;
	var $pembayaran;
	var $bank;
	var $atasnama;
	var $tipe;
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
		$this->TableVar = 'tanggota';
		$this->TableName = 'tanggota';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`tanggota`";
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

		// registrasi
		$this->registrasi = new cField('tanggota', 'tanggota', 'x_registrasi', 'registrasi', '`registrasi`', ew_CastDateFieldForLike('`registrasi`', 0, "DB"), 135, 0, FALSE, '`registrasi`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->registrasi->Sortable = TRUE; // Allow sort
		$this->registrasi->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['registrasi'] = &$this->registrasi;

		// periode
		$this->periode = new cField('tanggota', 'tanggota', 'x_periode', 'periode', '`periode`', '`periode`', 200, -1, FALSE, '`periode`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->periode->Sortable = TRUE; // Allow sort
		$this->fields['periode'] = &$this->periode;

		// id
		$this->id = new cField('tanggota', 'tanggota', 'x_id', 'id', '`id`', '`id`', 200, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->id->Sortable = TRUE; // Allow sort
		$this->fields['id'] = &$this->id;

		// anggota
		$this->anggota = new cField('tanggota', 'tanggota', 'x_anggota', 'anggota', '`anggota`', '`anggota`', 200, -1, FALSE, '`anggota`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->anggota->Sortable = TRUE; // Allow sort
		$this->fields['anggota'] = &$this->anggota;

		// namaanggota
		$this->namaanggota = new cField('tanggota', 'tanggota', 'x_namaanggota', 'namaanggota', '`namaanggota`', '`namaanggota`', 200, -1, FALSE, '`namaanggota`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->namaanggota->Sortable = TRUE; // Allow sort
		$this->fields['namaanggota'] = &$this->namaanggota;

		// alamat
		$this->alamat = new cField('tanggota', 'tanggota', 'x_alamat', 'alamat', '`alamat`', '`alamat`', 200, -1, FALSE, '`alamat`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->alamat->Sortable = TRUE; // Allow sort
		$this->fields['alamat'] = &$this->alamat;

		// tempatlahir
		$this->tempatlahir = new cField('tanggota', 'tanggota', 'x_tempatlahir', 'tempatlahir', '`tempatlahir`', '`tempatlahir`', 200, -1, FALSE, '`tempatlahir`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tempatlahir->Sortable = TRUE; // Allow sort
		$this->fields['tempatlahir'] = &$this->tempatlahir;

		// tanggallahir
		$this->tanggallahir = new cField('tanggota', 'tanggota', 'x_tanggallahir', 'tanggallahir', '`tanggallahir`', ew_CastDateFieldForLike('`tanggallahir`', 0, "DB"), 135, 0, FALSE, '`tanggallahir`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tanggallahir->Sortable = TRUE; // Allow sort
		$this->tanggallahir->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['tanggallahir'] = &$this->tanggallahir;

		// kelamin
		$this->kelamin = new cField('tanggota', 'tanggota', 'x_kelamin', 'kelamin', '`kelamin`', '`kelamin`', 200, -1, FALSE, '`kelamin`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->kelamin->Sortable = TRUE; // Allow sort
		$this->fields['kelamin'] = &$this->kelamin;

		// pekerjaan
		$this->pekerjaan = new cField('tanggota', 'tanggota', 'x_pekerjaan', 'pekerjaan', '`pekerjaan`', '`pekerjaan`', 200, -1, FALSE, '`pekerjaan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pekerjaan->Sortable = TRUE; // Allow sort
		$this->fields['pekerjaan'] = &$this->pekerjaan;

		// telepon
		$this->telepon = new cField('tanggota', 'tanggota', 'x_telepon', 'telepon', '`telepon`', '`telepon`', 200, -1, FALSE, '`telepon`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->telepon->Sortable = TRUE; // Allow sort
		$this->fields['telepon'] = &$this->telepon;

		// hp
		$this->hp = new cField('tanggota', 'tanggota', 'x_hp', 'hp', '`hp`', '`hp`', 200, -1, FALSE, '`hp`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->hp->Sortable = TRUE; // Allow sort
		$this->fields['hp'] = &$this->hp;

		// fax
		$this->fax = new cField('tanggota', 'tanggota', 'x_fax', 'fax', '`fax`', '`fax`', 200, -1, FALSE, '`fax`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fax->Sortable = TRUE; // Allow sort
		$this->fields['fax'] = &$this->fax;

		// email
		$this->_email = new cField('tanggota', 'tanggota', 'x__email', 'email', '`email`', '`email`', 200, -1, FALSE, '`email`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->_email->Sortable = TRUE; // Allow sort
		$this->fields['email'] = &$this->_email;

		// website
		$this->website = new cField('tanggota', 'tanggota', 'x_website', 'website', '`website`', '`website`', 200, -1, FALSE, '`website`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->website->Sortable = TRUE; // Allow sort
		$this->fields['website'] = &$this->website;

		// jenisanggota
		$this->jenisanggota = new cField('tanggota', 'tanggota', 'x_jenisanggota', 'jenisanggota', '`jenisanggota`', '`jenisanggota`', 200, -1, FALSE, '`jenisanggota`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jenisanggota->Sortable = TRUE; // Allow sort
		$this->fields['jenisanggota'] = &$this->jenisanggota;

		// model
		$this->model = new cField('tanggota', 'tanggota', 'x_model', 'model', '`model`', '`model`', 200, -1, FALSE, '`model`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->model->Sortable = TRUE; // Allow sort
		$this->fields['model'] = &$this->model;

		// namakantor
		$this->namakantor = new cField('tanggota', 'tanggota', 'x_namakantor', 'namakantor', '`namakantor`', '`namakantor`', 200, -1, FALSE, '`namakantor`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->namakantor->Sortable = TRUE; // Allow sort
		$this->fields['namakantor'] = &$this->namakantor;

		// alamatkantor
		$this->alamatkantor = new cField('tanggota', 'tanggota', 'x_alamatkantor', 'alamatkantor', '`alamatkantor`', '`alamatkantor`', 200, -1, FALSE, '`alamatkantor`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->alamatkantor->Sortable = TRUE; // Allow sort
		$this->fields['alamatkantor'] = &$this->alamatkantor;

		// wilayah
		$this->wilayah = new cField('tanggota', 'tanggota', 'x_wilayah', 'wilayah', '`wilayah`', '`wilayah`', 200, -1, FALSE, '`wilayah`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->wilayah->Sortable = TRUE; // Allow sort
		$this->fields['wilayah'] = &$this->wilayah;

		// petugas
		$this->petugas = new cField('tanggota', 'tanggota', 'x_petugas', 'petugas', '`petugas`', '`petugas`', 200, -1, FALSE, '`petugas`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->petugas->Sortable = TRUE; // Allow sort
		$this->fields['petugas'] = &$this->petugas;

		// pembayaran
		$this->pembayaran = new cField('tanggota', 'tanggota', 'x_pembayaran', 'pembayaran', '`pembayaran`', '`pembayaran`', 200, -1, FALSE, '`pembayaran`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pembayaran->Sortable = TRUE; // Allow sort
		$this->fields['pembayaran'] = &$this->pembayaran;

		// bank
		$this->bank = new cField('tanggota', 'tanggota', 'x_bank', 'bank', '`bank`', '`bank`', 200, -1, FALSE, '`bank`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->bank->Sortable = TRUE; // Allow sort
		$this->fields['bank'] = &$this->bank;

		// atasnama
		$this->atasnama = new cField('tanggota', 'tanggota', 'x_atasnama', 'atasnama', '`atasnama`', '`atasnama`', 200, -1, FALSE, '`atasnama`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->atasnama->Sortable = TRUE; // Allow sort
		$this->fields['atasnama'] = &$this->atasnama;

		// tipe
		$this->tipe = new cField('tanggota', 'tanggota', 'x_tipe', 'tipe', '`tipe`', '`tipe`', 200, -1, FALSE, '`tipe`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tipe->Sortable = TRUE; // Allow sort
		$this->fields['tipe'] = &$this->tipe;

		// kantor
		$this->kantor = new cField('tanggota', 'tanggota', 'x_kantor', 'kantor', '`kantor`', '`kantor`', 200, -1, FALSE, '`kantor`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->kantor->Sortable = TRUE; // Allow sort
		$this->fields['kantor'] = &$this->kantor;

		// keterangan
		$this->keterangan = new cField('tanggota', 'tanggota', 'x_keterangan', 'keterangan', '`keterangan`', '`keterangan`', 200, -1, FALSE, '`keterangan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->keterangan->Sortable = TRUE; // Allow sort
		$this->fields['keterangan'] = &$this->keterangan;

		// active
		$this->active = new cField('tanggota', 'tanggota', 'x_active', 'active', '`active`', '`active`', 202, -1, FALSE, '`active`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->active->Sortable = TRUE; // Allow sort
		$this->active->OptionCount = 2;
		$this->fields['active'] = &$this->active;

		// ip
		$this->ip = new cField('tanggota', 'tanggota', 'x_ip', 'ip', '`ip`', '`ip`', 200, -1, FALSE, '`ip`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->ip->Sortable = TRUE; // Allow sort
		$this->fields['ip'] = &$this->ip;

		// status
		$this->status = new cField('tanggota', 'tanggota', 'x_status', 'status', '`status`', '`status`', 200, -1, FALSE, '`status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->status->Sortable = TRUE; // Allow sort
		$this->fields['status'] = &$this->status;

		// user
		$this->user = new cField('tanggota', 'tanggota', 'x_user', 'user', '`user`', '`user`', 200, -1, FALSE, '`user`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->user->Sortable = TRUE; // Allow sort
		$this->fields['user'] = &$this->user;

		// created
		$this->created = new cField('tanggota', 'tanggota', 'x_created', 'created', '`created`', ew_CastDateFieldForLike('`created`', 0, "DB"), 135, 0, FALSE, '`created`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->created->Sortable = TRUE; // Allow sort
		$this->created->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['created'] = &$this->created;

		// modified
		$this->modified = new cField('tanggota', 'tanggota', 'x_modified', 'modified', '`modified`', ew_CastDateFieldForLike('`modified`', 0, "DB"), 135, 0, FALSE, '`modified`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`tanggota`";
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
		return "`id` = '@id@'";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		$sKeyFilter = str_replace("@id@", ew_AdjustSql($this->id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "tanggotalist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "tanggotalist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("tanggotaview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("tanggotaview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "tanggotaadd.php?" . $this->UrlParm($parm);
		else
			$url = "tanggotaadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("tanggotaedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("tanggotaadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("tanggotadelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "id:" . ew_VarToJson($this->id->CurrentValue, "string", "'");
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
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["id"]))
				$arKeys[] = ew_StripSlashes($_POST["id"]);
			elseif (isset($_GET["id"]))
				$arKeys[] = ew_StripSlashes($_GET["id"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
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
			$this->id->CurrentValue = $key;
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
		$this->registrasi->setDbValue($rs->fields('registrasi'));
		$this->periode->setDbValue($rs->fields('periode'));
		$this->id->setDbValue($rs->fields('id'));
		$this->anggota->setDbValue($rs->fields('anggota'));
		$this->namaanggota->setDbValue($rs->fields('namaanggota'));
		$this->alamat->setDbValue($rs->fields('alamat'));
		$this->tempatlahir->setDbValue($rs->fields('tempatlahir'));
		$this->tanggallahir->setDbValue($rs->fields('tanggallahir'));
		$this->kelamin->setDbValue($rs->fields('kelamin'));
		$this->pekerjaan->setDbValue($rs->fields('pekerjaan'));
		$this->telepon->setDbValue($rs->fields('telepon'));
		$this->hp->setDbValue($rs->fields('hp'));
		$this->fax->setDbValue($rs->fields('fax'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->website->setDbValue($rs->fields('website'));
		$this->jenisanggota->setDbValue($rs->fields('jenisanggota'));
		$this->model->setDbValue($rs->fields('model'));
		$this->namakantor->setDbValue($rs->fields('namakantor'));
		$this->alamatkantor->setDbValue($rs->fields('alamatkantor'));
		$this->wilayah->setDbValue($rs->fields('wilayah'));
		$this->petugas->setDbValue($rs->fields('petugas'));
		$this->pembayaran->setDbValue($rs->fields('pembayaran'));
		$this->bank->setDbValue($rs->fields('bank'));
		$this->atasnama->setDbValue($rs->fields('atasnama'));
		$this->tipe->setDbValue($rs->fields('tipe'));
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
		// registrasi
		// periode
		// id
		// anggota
		// namaanggota
		// alamat
		// tempatlahir
		// tanggallahir
		// kelamin
		// pekerjaan
		// telepon
		// hp
		// fax
		// email
		// website
		// jenisanggota
		// model
		// namakantor
		// alamatkantor
		// wilayah
		// petugas
		// pembayaran
		// bank
		// atasnama
		// tipe
		// kantor
		// keterangan
		// active
		// ip
		// status
		// user
		// created
		// modified
		// registrasi

		$this->registrasi->ViewValue = $this->registrasi->CurrentValue;
		$this->registrasi->ViewValue = ew_FormatDateTime($this->registrasi->ViewValue, 0);
		$this->registrasi->ViewCustomAttributes = "";

		// periode
		$this->periode->ViewValue = $this->periode->CurrentValue;
		$this->periode->ViewCustomAttributes = "";

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// anggota
		$this->anggota->ViewValue = $this->anggota->CurrentValue;
		$this->anggota->ViewCustomAttributes = "";

		// namaanggota
		$this->namaanggota->ViewValue = $this->namaanggota->CurrentValue;
		$this->namaanggota->ViewCustomAttributes = "";

		// alamat
		$this->alamat->ViewValue = $this->alamat->CurrentValue;
		$this->alamat->ViewCustomAttributes = "";

		// tempatlahir
		$this->tempatlahir->ViewValue = $this->tempatlahir->CurrentValue;
		$this->tempatlahir->ViewCustomAttributes = "";

		// tanggallahir
		$this->tanggallahir->ViewValue = $this->tanggallahir->CurrentValue;
		$this->tanggallahir->ViewValue = ew_FormatDateTime($this->tanggallahir->ViewValue, 0);
		$this->tanggallahir->ViewCustomAttributes = "";

		// kelamin
		$this->kelamin->ViewValue = $this->kelamin->CurrentValue;
		$this->kelamin->ViewCustomAttributes = "";

		// pekerjaan
		$this->pekerjaan->ViewValue = $this->pekerjaan->CurrentValue;
		$this->pekerjaan->ViewCustomAttributes = "";

		// telepon
		$this->telepon->ViewValue = $this->telepon->CurrentValue;
		$this->telepon->ViewCustomAttributes = "";

		// hp
		$this->hp->ViewValue = $this->hp->CurrentValue;
		$this->hp->ViewCustomAttributes = "";

		// fax
		$this->fax->ViewValue = $this->fax->CurrentValue;
		$this->fax->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// website
		$this->website->ViewValue = $this->website->CurrentValue;
		$this->website->ViewCustomAttributes = "";

		// jenisanggota
		$this->jenisanggota->ViewValue = $this->jenisanggota->CurrentValue;
		$this->jenisanggota->ViewCustomAttributes = "";

		// model
		$this->model->ViewValue = $this->model->CurrentValue;
		$this->model->ViewCustomAttributes = "";

		// namakantor
		$this->namakantor->ViewValue = $this->namakantor->CurrentValue;
		$this->namakantor->ViewCustomAttributes = "";

		// alamatkantor
		$this->alamatkantor->ViewValue = $this->alamatkantor->CurrentValue;
		$this->alamatkantor->ViewCustomAttributes = "";

		// wilayah
		$this->wilayah->ViewValue = $this->wilayah->CurrentValue;
		$this->wilayah->ViewCustomAttributes = "";

		// petugas
		$this->petugas->ViewValue = $this->petugas->CurrentValue;
		$this->petugas->ViewCustomAttributes = "";

		// pembayaran
		$this->pembayaran->ViewValue = $this->pembayaran->CurrentValue;
		$this->pembayaran->ViewCustomAttributes = "";

		// bank
		$this->bank->ViewValue = $this->bank->CurrentValue;
		$this->bank->ViewCustomAttributes = "";

		// atasnama
		$this->atasnama->ViewValue = $this->atasnama->CurrentValue;
		$this->atasnama->ViewCustomAttributes = "";

		// tipe
		$this->tipe->ViewValue = $this->tipe->CurrentValue;
		$this->tipe->ViewCustomAttributes = "";

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

		// registrasi
		$this->registrasi->LinkCustomAttributes = "";
		$this->registrasi->HrefValue = "";
		$this->registrasi->TooltipValue = "";

		// periode
		$this->periode->LinkCustomAttributes = "";
		$this->periode->HrefValue = "";
		$this->periode->TooltipValue = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// anggota
		$this->anggota->LinkCustomAttributes = "";
		$this->anggota->HrefValue = "";
		$this->anggota->TooltipValue = "";

		// namaanggota
		$this->namaanggota->LinkCustomAttributes = "";
		$this->namaanggota->HrefValue = "";
		$this->namaanggota->TooltipValue = "";

		// alamat
		$this->alamat->LinkCustomAttributes = "";
		$this->alamat->HrefValue = "";
		$this->alamat->TooltipValue = "";

		// tempatlahir
		$this->tempatlahir->LinkCustomAttributes = "";
		$this->tempatlahir->HrefValue = "";
		$this->tempatlahir->TooltipValue = "";

		// tanggallahir
		$this->tanggallahir->LinkCustomAttributes = "";
		$this->tanggallahir->HrefValue = "";
		$this->tanggallahir->TooltipValue = "";

		// kelamin
		$this->kelamin->LinkCustomAttributes = "";
		$this->kelamin->HrefValue = "";
		$this->kelamin->TooltipValue = "";

		// pekerjaan
		$this->pekerjaan->LinkCustomAttributes = "";
		$this->pekerjaan->HrefValue = "";
		$this->pekerjaan->TooltipValue = "";

		// telepon
		$this->telepon->LinkCustomAttributes = "";
		$this->telepon->HrefValue = "";
		$this->telepon->TooltipValue = "";

		// hp
		$this->hp->LinkCustomAttributes = "";
		$this->hp->HrefValue = "";
		$this->hp->TooltipValue = "";

		// fax
		$this->fax->LinkCustomAttributes = "";
		$this->fax->HrefValue = "";
		$this->fax->TooltipValue = "";

		// email
		$this->_email->LinkCustomAttributes = "";
		$this->_email->HrefValue = "";
		$this->_email->TooltipValue = "";

		// website
		$this->website->LinkCustomAttributes = "";
		$this->website->HrefValue = "";
		$this->website->TooltipValue = "";

		// jenisanggota
		$this->jenisanggota->LinkCustomAttributes = "";
		$this->jenisanggota->HrefValue = "";
		$this->jenisanggota->TooltipValue = "";

		// model
		$this->model->LinkCustomAttributes = "";
		$this->model->HrefValue = "";
		$this->model->TooltipValue = "";

		// namakantor
		$this->namakantor->LinkCustomAttributes = "";
		$this->namakantor->HrefValue = "";
		$this->namakantor->TooltipValue = "";

		// alamatkantor
		$this->alamatkantor->LinkCustomAttributes = "";
		$this->alamatkantor->HrefValue = "";
		$this->alamatkantor->TooltipValue = "";

		// wilayah
		$this->wilayah->LinkCustomAttributes = "";
		$this->wilayah->HrefValue = "";
		$this->wilayah->TooltipValue = "";

		// petugas
		$this->petugas->LinkCustomAttributes = "";
		$this->petugas->HrefValue = "";
		$this->petugas->TooltipValue = "";

		// pembayaran
		$this->pembayaran->LinkCustomAttributes = "";
		$this->pembayaran->HrefValue = "";
		$this->pembayaran->TooltipValue = "";

		// bank
		$this->bank->LinkCustomAttributes = "";
		$this->bank->HrefValue = "";
		$this->bank->TooltipValue = "";

		// atasnama
		$this->atasnama->LinkCustomAttributes = "";
		$this->atasnama->HrefValue = "";
		$this->atasnama->TooltipValue = "";

		// tipe
		$this->tipe->LinkCustomAttributes = "";
		$this->tipe->HrefValue = "";
		$this->tipe->TooltipValue = "";

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

		// registrasi
		$this->registrasi->EditAttrs["class"] = "form-control";
		$this->registrasi->EditCustomAttributes = "";
		$this->registrasi->EditValue = ew_FormatDateTime($this->registrasi->CurrentValue, 8);
		$this->registrasi->PlaceHolder = ew_RemoveHtml($this->registrasi->FldCaption());

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

		// anggota
		$this->anggota->EditAttrs["class"] = "form-control";
		$this->anggota->EditCustomAttributes = "";
		$this->anggota->EditValue = $this->anggota->CurrentValue;
		$this->anggota->PlaceHolder = ew_RemoveHtml($this->anggota->FldCaption());

		// namaanggota
		$this->namaanggota->EditAttrs["class"] = "form-control";
		$this->namaanggota->EditCustomAttributes = "";
		$this->namaanggota->EditValue = $this->namaanggota->CurrentValue;
		$this->namaanggota->PlaceHolder = ew_RemoveHtml($this->namaanggota->FldCaption());

		// alamat
		$this->alamat->EditAttrs["class"] = "form-control";
		$this->alamat->EditCustomAttributes = "";
		$this->alamat->EditValue = $this->alamat->CurrentValue;
		$this->alamat->PlaceHolder = ew_RemoveHtml($this->alamat->FldCaption());

		// tempatlahir
		$this->tempatlahir->EditAttrs["class"] = "form-control";
		$this->tempatlahir->EditCustomAttributes = "";
		$this->tempatlahir->EditValue = $this->tempatlahir->CurrentValue;
		$this->tempatlahir->PlaceHolder = ew_RemoveHtml($this->tempatlahir->FldCaption());

		// tanggallahir
		$this->tanggallahir->EditAttrs["class"] = "form-control";
		$this->tanggallahir->EditCustomAttributes = "";
		$this->tanggallahir->EditValue = ew_FormatDateTime($this->tanggallahir->CurrentValue, 8);
		$this->tanggallahir->PlaceHolder = ew_RemoveHtml($this->tanggallahir->FldCaption());

		// kelamin
		$this->kelamin->EditAttrs["class"] = "form-control";
		$this->kelamin->EditCustomAttributes = "";
		$this->kelamin->EditValue = $this->kelamin->CurrentValue;
		$this->kelamin->PlaceHolder = ew_RemoveHtml($this->kelamin->FldCaption());

		// pekerjaan
		$this->pekerjaan->EditAttrs["class"] = "form-control";
		$this->pekerjaan->EditCustomAttributes = "";
		$this->pekerjaan->EditValue = $this->pekerjaan->CurrentValue;
		$this->pekerjaan->PlaceHolder = ew_RemoveHtml($this->pekerjaan->FldCaption());

		// telepon
		$this->telepon->EditAttrs["class"] = "form-control";
		$this->telepon->EditCustomAttributes = "";
		$this->telepon->EditValue = $this->telepon->CurrentValue;
		$this->telepon->PlaceHolder = ew_RemoveHtml($this->telepon->FldCaption());

		// hp
		$this->hp->EditAttrs["class"] = "form-control";
		$this->hp->EditCustomAttributes = "";
		$this->hp->EditValue = $this->hp->CurrentValue;
		$this->hp->PlaceHolder = ew_RemoveHtml($this->hp->FldCaption());

		// fax
		$this->fax->EditAttrs["class"] = "form-control";
		$this->fax->EditCustomAttributes = "";
		$this->fax->EditValue = $this->fax->CurrentValue;
		$this->fax->PlaceHolder = ew_RemoveHtml($this->fax->FldCaption());

		// email
		$this->_email->EditAttrs["class"] = "form-control";
		$this->_email->EditCustomAttributes = "";
		$this->_email->EditValue = $this->_email->CurrentValue;
		$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

		// website
		$this->website->EditAttrs["class"] = "form-control";
		$this->website->EditCustomAttributes = "";
		$this->website->EditValue = $this->website->CurrentValue;
		$this->website->PlaceHolder = ew_RemoveHtml($this->website->FldCaption());

		// jenisanggota
		$this->jenisanggota->EditAttrs["class"] = "form-control";
		$this->jenisanggota->EditCustomAttributes = "";
		$this->jenisanggota->EditValue = $this->jenisanggota->CurrentValue;
		$this->jenisanggota->PlaceHolder = ew_RemoveHtml($this->jenisanggota->FldCaption());

		// model
		$this->model->EditAttrs["class"] = "form-control";
		$this->model->EditCustomAttributes = "";
		$this->model->EditValue = $this->model->CurrentValue;
		$this->model->PlaceHolder = ew_RemoveHtml($this->model->FldCaption());

		// namakantor
		$this->namakantor->EditAttrs["class"] = "form-control";
		$this->namakantor->EditCustomAttributes = "";
		$this->namakantor->EditValue = $this->namakantor->CurrentValue;
		$this->namakantor->PlaceHolder = ew_RemoveHtml($this->namakantor->FldCaption());

		// alamatkantor
		$this->alamatkantor->EditAttrs["class"] = "form-control";
		$this->alamatkantor->EditCustomAttributes = "";
		$this->alamatkantor->EditValue = $this->alamatkantor->CurrentValue;
		$this->alamatkantor->PlaceHolder = ew_RemoveHtml($this->alamatkantor->FldCaption());

		// wilayah
		$this->wilayah->EditAttrs["class"] = "form-control";
		$this->wilayah->EditCustomAttributes = "";
		$this->wilayah->EditValue = $this->wilayah->CurrentValue;
		$this->wilayah->PlaceHolder = ew_RemoveHtml($this->wilayah->FldCaption());

		// petugas
		$this->petugas->EditAttrs["class"] = "form-control";
		$this->petugas->EditCustomAttributes = "";
		$this->petugas->EditValue = $this->petugas->CurrentValue;
		$this->petugas->PlaceHolder = ew_RemoveHtml($this->petugas->FldCaption());

		// pembayaran
		$this->pembayaran->EditAttrs["class"] = "form-control";
		$this->pembayaran->EditCustomAttributes = "";
		$this->pembayaran->EditValue = $this->pembayaran->CurrentValue;
		$this->pembayaran->PlaceHolder = ew_RemoveHtml($this->pembayaran->FldCaption());

		// bank
		$this->bank->EditAttrs["class"] = "form-control";
		$this->bank->EditCustomAttributes = "";
		$this->bank->EditValue = $this->bank->CurrentValue;
		$this->bank->PlaceHolder = ew_RemoveHtml($this->bank->FldCaption());

		// atasnama
		$this->atasnama->EditAttrs["class"] = "form-control";
		$this->atasnama->EditCustomAttributes = "";
		$this->atasnama->EditValue = $this->atasnama->CurrentValue;
		$this->atasnama->PlaceHolder = ew_RemoveHtml($this->atasnama->FldCaption());

		// tipe
		$this->tipe->EditAttrs["class"] = "form-control";
		$this->tipe->EditCustomAttributes = "";
		$this->tipe->EditValue = $this->tipe->CurrentValue;
		$this->tipe->PlaceHolder = ew_RemoveHtml($this->tipe->FldCaption());

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
					if ($this->registrasi->Exportable) $Doc->ExportCaption($this->registrasi);
					if ($this->periode->Exportable) $Doc->ExportCaption($this->periode);
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->anggota->Exportable) $Doc->ExportCaption($this->anggota);
					if ($this->namaanggota->Exportable) $Doc->ExportCaption($this->namaanggota);
					if ($this->alamat->Exportable) $Doc->ExportCaption($this->alamat);
					if ($this->tempatlahir->Exportable) $Doc->ExportCaption($this->tempatlahir);
					if ($this->tanggallahir->Exportable) $Doc->ExportCaption($this->tanggallahir);
					if ($this->kelamin->Exportable) $Doc->ExportCaption($this->kelamin);
					if ($this->pekerjaan->Exportable) $Doc->ExportCaption($this->pekerjaan);
					if ($this->telepon->Exportable) $Doc->ExportCaption($this->telepon);
					if ($this->hp->Exportable) $Doc->ExportCaption($this->hp);
					if ($this->fax->Exportable) $Doc->ExportCaption($this->fax);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->website->Exportable) $Doc->ExportCaption($this->website);
					if ($this->jenisanggota->Exportable) $Doc->ExportCaption($this->jenisanggota);
					if ($this->model->Exportable) $Doc->ExportCaption($this->model);
					if ($this->namakantor->Exportable) $Doc->ExportCaption($this->namakantor);
					if ($this->alamatkantor->Exportable) $Doc->ExportCaption($this->alamatkantor);
					if ($this->wilayah->Exportable) $Doc->ExportCaption($this->wilayah);
					if ($this->petugas->Exportable) $Doc->ExportCaption($this->petugas);
					if ($this->pembayaran->Exportable) $Doc->ExportCaption($this->pembayaran);
					if ($this->bank->Exportable) $Doc->ExportCaption($this->bank);
					if ($this->atasnama->Exportable) $Doc->ExportCaption($this->atasnama);
					if ($this->tipe->Exportable) $Doc->ExportCaption($this->tipe);
					if ($this->kantor->Exportable) $Doc->ExportCaption($this->kantor);
					if ($this->keterangan->Exportable) $Doc->ExportCaption($this->keterangan);
					if ($this->active->Exportable) $Doc->ExportCaption($this->active);
					if ($this->ip->Exportable) $Doc->ExportCaption($this->ip);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->user->Exportable) $Doc->ExportCaption($this->user);
					if ($this->created->Exportable) $Doc->ExportCaption($this->created);
					if ($this->modified->Exportable) $Doc->ExportCaption($this->modified);
				} else {
					if ($this->registrasi->Exportable) $Doc->ExportCaption($this->registrasi);
					if ($this->periode->Exportable) $Doc->ExportCaption($this->periode);
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->anggota->Exportable) $Doc->ExportCaption($this->anggota);
					if ($this->namaanggota->Exportable) $Doc->ExportCaption($this->namaanggota);
					if ($this->alamat->Exportable) $Doc->ExportCaption($this->alamat);
					if ($this->tempatlahir->Exportable) $Doc->ExportCaption($this->tempatlahir);
					if ($this->tanggallahir->Exportable) $Doc->ExportCaption($this->tanggallahir);
					if ($this->kelamin->Exportable) $Doc->ExportCaption($this->kelamin);
					if ($this->pekerjaan->Exportable) $Doc->ExportCaption($this->pekerjaan);
					if ($this->telepon->Exportable) $Doc->ExportCaption($this->telepon);
					if ($this->hp->Exportable) $Doc->ExportCaption($this->hp);
					if ($this->fax->Exportable) $Doc->ExportCaption($this->fax);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->website->Exportable) $Doc->ExportCaption($this->website);
					if ($this->jenisanggota->Exportable) $Doc->ExportCaption($this->jenisanggota);
					if ($this->model->Exportable) $Doc->ExportCaption($this->model);
					if ($this->namakantor->Exportable) $Doc->ExportCaption($this->namakantor);
					if ($this->alamatkantor->Exportable) $Doc->ExportCaption($this->alamatkantor);
					if ($this->wilayah->Exportable) $Doc->ExportCaption($this->wilayah);
					if ($this->petugas->Exportable) $Doc->ExportCaption($this->petugas);
					if ($this->pembayaran->Exportable) $Doc->ExportCaption($this->pembayaran);
					if ($this->bank->Exportable) $Doc->ExportCaption($this->bank);
					if ($this->atasnama->Exportable) $Doc->ExportCaption($this->atasnama);
					if ($this->tipe->Exportable) $Doc->ExportCaption($this->tipe);
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
						if ($this->registrasi->Exportable) $Doc->ExportField($this->registrasi);
						if ($this->periode->Exportable) $Doc->ExportField($this->periode);
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->anggota->Exportable) $Doc->ExportField($this->anggota);
						if ($this->namaanggota->Exportable) $Doc->ExportField($this->namaanggota);
						if ($this->alamat->Exportable) $Doc->ExportField($this->alamat);
						if ($this->tempatlahir->Exportable) $Doc->ExportField($this->tempatlahir);
						if ($this->tanggallahir->Exportable) $Doc->ExportField($this->tanggallahir);
						if ($this->kelamin->Exportable) $Doc->ExportField($this->kelamin);
						if ($this->pekerjaan->Exportable) $Doc->ExportField($this->pekerjaan);
						if ($this->telepon->Exportable) $Doc->ExportField($this->telepon);
						if ($this->hp->Exportable) $Doc->ExportField($this->hp);
						if ($this->fax->Exportable) $Doc->ExportField($this->fax);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->website->Exportable) $Doc->ExportField($this->website);
						if ($this->jenisanggota->Exportable) $Doc->ExportField($this->jenisanggota);
						if ($this->model->Exportable) $Doc->ExportField($this->model);
						if ($this->namakantor->Exportable) $Doc->ExportField($this->namakantor);
						if ($this->alamatkantor->Exportable) $Doc->ExportField($this->alamatkantor);
						if ($this->wilayah->Exportable) $Doc->ExportField($this->wilayah);
						if ($this->petugas->Exportable) $Doc->ExportField($this->petugas);
						if ($this->pembayaran->Exportable) $Doc->ExportField($this->pembayaran);
						if ($this->bank->Exportable) $Doc->ExportField($this->bank);
						if ($this->atasnama->Exportable) $Doc->ExportField($this->atasnama);
						if ($this->tipe->Exportable) $Doc->ExportField($this->tipe);
						if ($this->kantor->Exportable) $Doc->ExportField($this->kantor);
						if ($this->keterangan->Exportable) $Doc->ExportField($this->keterangan);
						if ($this->active->Exportable) $Doc->ExportField($this->active);
						if ($this->ip->Exportable) $Doc->ExportField($this->ip);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->user->Exportable) $Doc->ExportField($this->user);
						if ($this->created->Exportable) $Doc->ExportField($this->created);
						if ($this->modified->Exportable) $Doc->ExportField($this->modified);
					} else {
						if ($this->registrasi->Exportable) $Doc->ExportField($this->registrasi);
						if ($this->periode->Exportable) $Doc->ExportField($this->periode);
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->anggota->Exportable) $Doc->ExportField($this->anggota);
						if ($this->namaanggota->Exportable) $Doc->ExportField($this->namaanggota);
						if ($this->alamat->Exportable) $Doc->ExportField($this->alamat);
						if ($this->tempatlahir->Exportable) $Doc->ExportField($this->tempatlahir);
						if ($this->tanggallahir->Exportable) $Doc->ExportField($this->tanggallahir);
						if ($this->kelamin->Exportable) $Doc->ExportField($this->kelamin);
						if ($this->pekerjaan->Exportable) $Doc->ExportField($this->pekerjaan);
						if ($this->telepon->Exportable) $Doc->ExportField($this->telepon);
						if ($this->hp->Exportable) $Doc->ExportField($this->hp);
						if ($this->fax->Exportable) $Doc->ExportField($this->fax);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->website->Exportable) $Doc->ExportField($this->website);
						if ($this->jenisanggota->Exportable) $Doc->ExportField($this->jenisanggota);
						if ($this->model->Exportable) $Doc->ExportField($this->model);
						if ($this->namakantor->Exportable) $Doc->ExportField($this->namakantor);
						if ($this->alamatkantor->Exportable) $Doc->ExportField($this->alamatkantor);
						if ($this->wilayah->Exportable) $Doc->ExportField($this->wilayah);
						if ($this->petugas->Exportable) $Doc->ExportField($this->petugas);
						if ($this->pembayaran->Exportable) $Doc->ExportField($this->pembayaran);
						if ($this->bank->Exportable) $Doc->ExportField($this->bank);
						if ($this->atasnama->Exportable) $Doc->ExportField($this->atasnama);
						if ($this->tipe->Exportable) $Doc->ExportField($this->tipe);
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
