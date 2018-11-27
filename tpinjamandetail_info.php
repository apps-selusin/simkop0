<?php

// Global variable for table object
$tpinjamandetail_ = NULL;

//
// Table class for tpinjamandetail_
//
class ctpinjamandetail_ extends cTable {
	var $id;
	var $berjangka;
	var $angsuran;
	var $angsuranpokok;
	var $angsuranpokokauto;
	var $angsuranbunga;
	var $angsuranbungaauto;
	var $totalangsuran;
	var $totalangsuranauto;
	var $sisaangsuran;
	var $sisaangsuranauto;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'tpinjamandetail_';
		$this->TableName = 'tpinjamandetail_';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`tpinjamandetail_`";
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

		// id
		$this->id = new cField('tpinjamandetail_', 'tpinjamandetail_', 'x_id', 'id', '`id`', '`id`', 200, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->id->Sortable = TRUE; // Allow sort
		$this->fields['id'] = &$this->id;

		// berjangka
		$this->berjangka = new cField('tpinjamandetail_', 'tpinjamandetail_', 'x_berjangka', 'berjangka', '`berjangka`', '`berjangka`', 20, -1, FALSE, '`berjangka`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->berjangka->Sortable = TRUE; // Allow sort
		$this->berjangka->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['berjangka'] = &$this->berjangka;

		// angsuran
		$this->angsuran = new cField('tpinjamandetail_', 'tpinjamandetail_', 'x_angsuran', 'angsuran', '`angsuran`', '`angsuran`', 20, -1, FALSE, '`angsuran`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->angsuran->Sortable = TRUE; // Allow sort
		$this->angsuran->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['angsuran'] = &$this->angsuran;

		// angsuranpokok
		$this->angsuranpokok = new cField('tpinjamandetail_', 'tpinjamandetail_', 'x_angsuranpokok', 'angsuranpokok', '`angsuranpokok`', '`angsuranpokok`', 5, -1, FALSE, '`angsuranpokok`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->angsuranpokok->Sortable = TRUE; // Allow sort
		$this->angsuranpokok->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['angsuranpokok'] = &$this->angsuranpokok;

		// angsuranpokokauto
		$this->angsuranpokokauto = new cField('tpinjamandetail_', 'tpinjamandetail_', 'x_angsuranpokokauto', 'angsuranpokokauto', '`angsuranpokokauto`', '`angsuranpokokauto`', 5, -1, FALSE, '`angsuranpokokauto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->angsuranpokokauto->Sortable = TRUE; // Allow sort
		$this->angsuranpokokauto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['angsuranpokokauto'] = &$this->angsuranpokokauto;

		// angsuranbunga
		$this->angsuranbunga = new cField('tpinjamandetail_', 'tpinjamandetail_', 'x_angsuranbunga', 'angsuranbunga', '`angsuranbunga`', '`angsuranbunga`', 5, -1, FALSE, '`angsuranbunga`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->angsuranbunga->Sortable = TRUE; // Allow sort
		$this->angsuranbunga->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['angsuranbunga'] = &$this->angsuranbunga;

		// angsuranbungaauto
		$this->angsuranbungaauto = new cField('tpinjamandetail_', 'tpinjamandetail_', 'x_angsuranbungaauto', 'angsuranbungaauto', '`angsuranbungaauto`', '`angsuranbungaauto`', 5, -1, FALSE, '`angsuranbungaauto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->angsuranbungaauto->Sortable = TRUE; // Allow sort
		$this->angsuranbungaauto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['angsuranbungaauto'] = &$this->angsuranbungaauto;

		// totalangsuran
		$this->totalangsuran = new cField('tpinjamandetail_', 'tpinjamandetail_', 'x_totalangsuran', 'totalangsuran', '`totalangsuran`', '`totalangsuran`', 5, -1, FALSE, '`totalangsuran`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->totalangsuran->Sortable = TRUE; // Allow sort
		$this->totalangsuran->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['totalangsuran'] = &$this->totalangsuran;

		// totalangsuranauto
		$this->totalangsuranauto = new cField('tpinjamandetail_', 'tpinjamandetail_', 'x_totalangsuranauto', 'totalangsuranauto', '`totalangsuranauto`', '`totalangsuranauto`', 5, -1, FALSE, '`totalangsuranauto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->totalangsuranauto->Sortable = TRUE; // Allow sort
		$this->totalangsuranauto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['totalangsuranauto'] = &$this->totalangsuranauto;

		// sisaangsuran
		$this->sisaangsuran = new cField('tpinjamandetail_', 'tpinjamandetail_', 'x_sisaangsuran', 'sisaangsuran', '`sisaangsuran`', '`sisaangsuran`', 5, -1, FALSE, '`sisaangsuran`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->sisaangsuran->Sortable = TRUE; // Allow sort
		$this->sisaangsuran->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['sisaangsuran'] = &$this->sisaangsuran;

		// sisaangsuranauto
		$this->sisaangsuranauto = new cField('tpinjamandetail_', 'tpinjamandetail_', 'x_sisaangsuranauto', 'sisaangsuranauto', '`sisaangsuranauto`', '`sisaangsuranauto`', 5, -1, FALSE, '`sisaangsuranauto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->sisaangsuranauto->Sortable = TRUE; // Allow sort
		$this->sisaangsuranauto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['sisaangsuranauto'] = &$this->sisaangsuranauto;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`tpinjamandetail_`";
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
			if (array_key_exists('berjangka', $rs))
				ew_AddFilter($where, ew_QuotedName('berjangka', $this->DBID) . '=' . ew_QuotedValue($rs['berjangka'], $this->berjangka->FldDataType, $this->DBID));
			if (array_key_exists('angsuran', $rs))
				ew_AddFilter($where, ew_QuotedName('angsuran', $this->DBID) . '=' . ew_QuotedValue($rs['angsuran'], $this->angsuran->FldDataType, $this->DBID));
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
		return "`id` = '@id@' AND `berjangka` = @berjangka@ AND `angsuran` = @angsuran@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		$sKeyFilter = str_replace("@id@", ew_AdjustSql($this->id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		if (!is_numeric($this->berjangka->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@berjangka@", ew_AdjustSql($this->berjangka->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		if (!is_numeric($this->angsuran->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@angsuran@", ew_AdjustSql($this->angsuran->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "tpinjamandetail_list.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "tpinjamandetail_list.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("tpinjamandetail_view.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("tpinjamandetail_view.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "tpinjamandetail_add.php?" . $this->UrlParm($parm);
		else
			$url = "tpinjamandetail_add.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("tpinjamandetail_edit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("tpinjamandetail_add.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("tpinjamandetail_delete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "id:" . ew_VarToJson($this->id->CurrentValue, "string", "'");
		$json .= ",berjangka:" . ew_VarToJson($this->berjangka->CurrentValue, "number", "'");
		$json .= ",angsuran:" . ew_VarToJson($this->angsuran->CurrentValue, "number", "'");
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
		if (!is_null($this->berjangka->CurrentValue)) {
			$sUrl .= "&berjangka=" . urlencode($this->berjangka->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->angsuran->CurrentValue)) {
			$sUrl .= "&angsuran=" . urlencode($this->angsuran->CurrentValue);
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
			if ($isPost && isset($_POST["berjangka"]))
				$arKey[] = ew_StripSlashes($_POST["berjangka"]);
			elseif (isset($_GET["berjangka"]))
				$arKey[] = ew_StripSlashes($_GET["berjangka"]);
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["angsuran"]))
				$arKey[] = ew_StripSlashes($_POST["angsuran"]);
			elseif (isset($_GET["angsuran"]))
				$arKey[] = ew_StripSlashes($_GET["angsuran"]);
			else
				$arKeys = NULL; // Do not setup
			if (is_array($arKeys)) $arKeys[] = $arKey;

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_array($key) || count($key) <> 3)
					continue; // Just skip so other keys will still work
				if (!is_numeric($key[1])) // berjangka
					continue;
				if (!is_numeric($key[2])) // angsuran
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
			$this->berjangka->CurrentValue = $key[1];
			$this->angsuran->CurrentValue = $key[2];
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
		$this->id->setDbValue($rs->fields('id'));
		$this->berjangka->setDbValue($rs->fields('berjangka'));
		$this->angsuran->setDbValue($rs->fields('angsuran'));
		$this->angsuranpokok->setDbValue($rs->fields('angsuranpokok'));
		$this->angsuranpokokauto->setDbValue($rs->fields('angsuranpokokauto'));
		$this->angsuranbunga->setDbValue($rs->fields('angsuranbunga'));
		$this->angsuranbungaauto->setDbValue($rs->fields('angsuranbungaauto'));
		$this->totalangsuran->setDbValue($rs->fields('totalangsuran'));
		$this->totalangsuranauto->setDbValue($rs->fields('totalangsuranauto'));
		$this->sisaangsuran->setDbValue($rs->fields('sisaangsuran'));
		$this->sisaangsuranauto->setDbValue($rs->fields('sisaangsuranauto'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id
		// berjangka
		// angsuran
		// angsuranpokok
		// angsuranpokokauto
		// angsuranbunga
		// angsuranbungaauto
		// totalangsuran
		// totalangsuranauto
		// sisaangsuran
		// sisaangsuranauto
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// berjangka
		$this->berjangka->ViewValue = $this->berjangka->CurrentValue;
		$this->berjangka->ViewCustomAttributes = "";

		// angsuran
		$this->angsuran->ViewValue = $this->angsuran->CurrentValue;
		$this->angsuran->ViewCustomAttributes = "";

		// angsuranpokok
		$this->angsuranpokok->ViewValue = $this->angsuranpokok->CurrentValue;
		$this->angsuranpokok->ViewCustomAttributes = "";

		// angsuranpokokauto
		$this->angsuranpokokauto->ViewValue = $this->angsuranpokokauto->CurrentValue;
		$this->angsuranpokokauto->ViewCustomAttributes = "";

		// angsuranbunga
		$this->angsuranbunga->ViewValue = $this->angsuranbunga->CurrentValue;
		$this->angsuranbunga->ViewCustomAttributes = "";

		// angsuranbungaauto
		$this->angsuranbungaauto->ViewValue = $this->angsuranbungaauto->CurrentValue;
		$this->angsuranbungaauto->ViewCustomAttributes = "";

		// totalangsuran
		$this->totalangsuran->ViewValue = $this->totalangsuran->CurrentValue;
		$this->totalangsuran->ViewCustomAttributes = "";

		// totalangsuranauto
		$this->totalangsuranauto->ViewValue = $this->totalangsuranauto->CurrentValue;
		$this->totalangsuranauto->ViewCustomAttributes = "";

		// sisaangsuran
		$this->sisaangsuran->ViewValue = $this->sisaangsuran->CurrentValue;
		$this->sisaangsuran->ViewCustomAttributes = "";

		// sisaangsuranauto
		$this->sisaangsuranauto->ViewValue = $this->sisaangsuranauto->CurrentValue;
		$this->sisaangsuranauto->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// berjangka
		$this->berjangka->LinkCustomAttributes = "";
		$this->berjangka->HrefValue = "";
		$this->berjangka->TooltipValue = "";

		// angsuran
		$this->angsuran->LinkCustomAttributes = "";
		$this->angsuran->HrefValue = "";
		$this->angsuran->TooltipValue = "";

		// angsuranpokok
		$this->angsuranpokok->LinkCustomAttributes = "";
		$this->angsuranpokok->HrefValue = "";
		$this->angsuranpokok->TooltipValue = "";

		// angsuranpokokauto
		$this->angsuranpokokauto->LinkCustomAttributes = "";
		$this->angsuranpokokauto->HrefValue = "";
		$this->angsuranpokokauto->TooltipValue = "";

		// angsuranbunga
		$this->angsuranbunga->LinkCustomAttributes = "";
		$this->angsuranbunga->HrefValue = "";
		$this->angsuranbunga->TooltipValue = "";

		// angsuranbungaauto
		$this->angsuranbungaauto->LinkCustomAttributes = "";
		$this->angsuranbungaauto->HrefValue = "";
		$this->angsuranbungaauto->TooltipValue = "";

		// totalangsuran
		$this->totalangsuran->LinkCustomAttributes = "";
		$this->totalangsuran->HrefValue = "";
		$this->totalangsuran->TooltipValue = "";

		// totalangsuranauto
		$this->totalangsuranauto->LinkCustomAttributes = "";
		$this->totalangsuranauto->HrefValue = "";
		$this->totalangsuranauto->TooltipValue = "";

		// sisaangsuran
		$this->sisaangsuran->LinkCustomAttributes = "";
		$this->sisaangsuran->HrefValue = "";
		$this->sisaangsuran->TooltipValue = "";

		// sisaangsuranauto
		$this->sisaangsuranauto->LinkCustomAttributes = "";
		$this->sisaangsuranauto->HrefValue = "";
		$this->sisaangsuranauto->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// id
		$this->id->EditAttrs["class"] = "form-control";
		$this->id->EditCustomAttributes = "";
		$this->id->EditValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// berjangka
		$this->berjangka->EditAttrs["class"] = "form-control";
		$this->berjangka->EditCustomAttributes = "";
		$this->berjangka->EditValue = $this->berjangka->CurrentValue;
		$this->berjangka->ViewCustomAttributes = "";

		// angsuran
		$this->angsuran->EditAttrs["class"] = "form-control";
		$this->angsuran->EditCustomAttributes = "";
		$this->angsuran->EditValue = $this->angsuran->CurrentValue;
		$this->angsuran->ViewCustomAttributes = "";

		// angsuranpokok
		$this->angsuranpokok->EditAttrs["class"] = "form-control";
		$this->angsuranpokok->EditCustomAttributes = "";
		$this->angsuranpokok->EditValue = $this->angsuranpokok->CurrentValue;
		$this->angsuranpokok->PlaceHolder = ew_RemoveHtml($this->angsuranpokok->FldCaption());
		if (strval($this->angsuranpokok->EditValue) <> "" && is_numeric($this->angsuranpokok->EditValue)) $this->angsuranpokok->EditValue = ew_FormatNumber($this->angsuranpokok->EditValue, -2, -1, -2, 0);

		// angsuranpokokauto
		$this->angsuranpokokauto->EditAttrs["class"] = "form-control";
		$this->angsuranpokokauto->EditCustomAttributes = "";
		$this->angsuranpokokauto->EditValue = $this->angsuranpokokauto->CurrentValue;
		$this->angsuranpokokauto->PlaceHolder = ew_RemoveHtml($this->angsuranpokokauto->FldCaption());
		if (strval($this->angsuranpokokauto->EditValue) <> "" && is_numeric($this->angsuranpokokauto->EditValue)) $this->angsuranpokokauto->EditValue = ew_FormatNumber($this->angsuranpokokauto->EditValue, -2, -1, -2, 0);

		// angsuranbunga
		$this->angsuranbunga->EditAttrs["class"] = "form-control";
		$this->angsuranbunga->EditCustomAttributes = "";
		$this->angsuranbunga->EditValue = $this->angsuranbunga->CurrentValue;
		$this->angsuranbunga->PlaceHolder = ew_RemoveHtml($this->angsuranbunga->FldCaption());
		if (strval($this->angsuranbunga->EditValue) <> "" && is_numeric($this->angsuranbunga->EditValue)) $this->angsuranbunga->EditValue = ew_FormatNumber($this->angsuranbunga->EditValue, -2, -1, -2, 0);

		// angsuranbungaauto
		$this->angsuranbungaauto->EditAttrs["class"] = "form-control";
		$this->angsuranbungaauto->EditCustomAttributes = "";
		$this->angsuranbungaauto->EditValue = $this->angsuranbungaauto->CurrentValue;
		$this->angsuranbungaauto->PlaceHolder = ew_RemoveHtml($this->angsuranbungaauto->FldCaption());
		if (strval($this->angsuranbungaauto->EditValue) <> "" && is_numeric($this->angsuranbungaauto->EditValue)) $this->angsuranbungaauto->EditValue = ew_FormatNumber($this->angsuranbungaauto->EditValue, -2, -1, -2, 0);

		// totalangsuran
		$this->totalangsuran->EditAttrs["class"] = "form-control";
		$this->totalangsuran->EditCustomAttributes = "";
		$this->totalangsuran->EditValue = $this->totalangsuran->CurrentValue;
		$this->totalangsuran->PlaceHolder = ew_RemoveHtml($this->totalangsuran->FldCaption());
		if (strval($this->totalangsuran->EditValue) <> "" && is_numeric($this->totalangsuran->EditValue)) $this->totalangsuran->EditValue = ew_FormatNumber($this->totalangsuran->EditValue, -2, -1, -2, 0);

		// totalangsuranauto
		$this->totalangsuranauto->EditAttrs["class"] = "form-control";
		$this->totalangsuranauto->EditCustomAttributes = "";
		$this->totalangsuranauto->EditValue = $this->totalangsuranauto->CurrentValue;
		$this->totalangsuranauto->PlaceHolder = ew_RemoveHtml($this->totalangsuranauto->FldCaption());
		if (strval($this->totalangsuranauto->EditValue) <> "" && is_numeric($this->totalangsuranauto->EditValue)) $this->totalangsuranauto->EditValue = ew_FormatNumber($this->totalangsuranauto->EditValue, -2, -1, -2, 0);

		// sisaangsuran
		$this->sisaangsuran->EditAttrs["class"] = "form-control";
		$this->sisaangsuran->EditCustomAttributes = "";
		$this->sisaangsuran->EditValue = $this->sisaangsuran->CurrentValue;
		$this->sisaangsuran->PlaceHolder = ew_RemoveHtml($this->sisaangsuran->FldCaption());
		if (strval($this->sisaangsuran->EditValue) <> "" && is_numeric($this->sisaangsuran->EditValue)) $this->sisaangsuran->EditValue = ew_FormatNumber($this->sisaangsuran->EditValue, -2, -1, -2, 0);

		// sisaangsuranauto
		$this->sisaangsuranauto->EditAttrs["class"] = "form-control";
		$this->sisaangsuranauto->EditCustomAttributes = "";
		$this->sisaangsuranauto->EditValue = $this->sisaangsuranauto->CurrentValue;
		$this->sisaangsuranauto->PlaceHolder = ew_RemoveHtml($this->sisaangsuranauto->FldCaption());
		if (strval($this->sisaangsuranauto->EditValue) <> "" && is_numeric($this->sisaangsuranauto->EditValue)) $this->sisaangsuranauto->EditValue = ew_FormatNumber($this->sisaangsuranauto->EditValue, -2, -1, -2, 0);

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
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->berjangka->Exportable) $Doc->ExportCaption($this->berjangka);
					if ($this->angsuran->Exportable) $Doc->ExportCaption($this->angsuran);
					if ($this->angsuranpokok->Exportable) $Doc->ExportCaption($this->angsuranpokok);
					if ($this->angsuranpokokauto->Exportable) $Doc->ExportCaption($this->angsuranpokokauto);
					if ($this->angsuranbunga->Exportable) $Doc->ExportCaption($this->angsuranbunga);
					if ($this->angsuranbungaauto->Exportable) $Doc->ExportCaption($this->angsuranbungaauto);
					if ($this->totalangsuran->Exportable) $Doc->ExportCaption($this->totalangsuran);
					if ($this->totalangsuranauto->Exportable) $Doc->ExportCaption($this->totalangsuranauto);
					if ($this->sisaangsuran->Exportable) $Doc->ExportCaption($this->sisaangsuran);
					if ($this->sisaangsuranauto->Exportable) $Doc->ExportCaption($this->sisaangsuranauto);
				} else {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->berjangka->Exportable) $Doc->ExportCaption($this->berjangka);
					if ($this->angsuran->Exportable) $Doc->ExportCaption($this->angsuran);
					if ($this->angsuranpokok->Exportable) $Doc->ExportCaption($this->angsuranpokok);
					if ($this->angsuranpokokauto->Exportable) $Doc->ExportCaption($this->angsuranpokokauto);
					if ($this->angsuranbunga->Exportable) $Doc->ExportCaption($this->angsuranbunga);
					if ($this->angsuranbungaauto->Exportable) $Doc->ExportCaption($this->angsuranbungaauto);
					if ($this->totalangsuran->Exportable) $Doc->ExportCaption($this->totalangsuran);
					if ($this->totalangsuranauto->Exportable) $Doc->ExportCaption($this->totalangsuranauto);
					if ($this->sisaangsuran->Exportable) $Doc->ExportCaption($this->sisaangsuran);
					if ($this->sisaangsuranauto->Exportable) $Doc->ExportCaption($this->sisaangsuranauto);
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
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->berjangka->Exportable) $Doc->ExportField($this->berjangka);
						if ($this->angsuran->Exportable) $Doc->ExportField($this->angsuran);
						if ($this->angsuranpokok->Exportable) $Doc->ExportField($this->angsuranpokok);
						if ($this->angsuranpokokauto->Exportable) $Doc->ExportField($this->angsuranpokokauto);
						if ($this->angsuranbunga->Exportable) $Doc->ExportField($this->angsuranbunga);
						if ($this->angsuranbungaauto->Exportable) $Doc->ExportField($this->angsuranbungaauto);
						if ($this->totalangsuran->Exportable) $Doc->ExportField($this->totalangsuran);
						if ($this->totalangsuranauto->Exportable) $Doc->ExportField($this->totalangsuranauto);
						if ($this->sisaangsuran->Exportable) $Doc->ExportField($this->sisaangsuran);
						if ($this->sisaangsuranauto->Exportable) $Doc->ExportField($this->sisaangsuranauto);
					} else {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->berjangka->Exportable) $Doc->ExportField($this->berjangka);
						if ($this->angsuran->Exportable) $Doc->ExportField($this->angsuran);
						if ($this->angsuranpokok->Exportable) $Doc->ExportField($this->angsuranpokok);
						if ($this->angsuranpokokauto->Exportable) $Doc->ExportField($this->angsuranpokokauto);
						if ($this->angsuranbunga->Exportable) $Doc->ExportField($this->angsuranbunga);
						if ($this->angsuranbungaauto->Exportable) $Doc->ExportField($this->angsuranbungaauto);
						if ($this->totalangsuran->Exportable) $Doc->ExportField($this->totalangsuran);
						if ($this->totalangsuranauto->Exportable) $Doc->ExportField($this->totalangsuranauto);
						if ($this->sisaangsuran->Exportable) $Doc->ExportField($this->sisaangsuran);
						if ($this->sisaangsuranauto->Exportable) $Doc->ExportField($this->sisaangsuranauto);
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
