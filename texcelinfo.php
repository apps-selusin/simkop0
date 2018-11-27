<?php

// Global variable for table object
$texcel = NULL;

//
// Table class for texcel
//
class ctexcel extends cTable {
	var $SHEET;
	var $NOMOR;
	var $A;
	var $B;
	var $C;
	var $D;
	var $E;
	var $F;
	var $G;
	var $H;
	var $_I;
	var $J;
	var $K;
	var $L;
	var $M;
	var $N;
	var $O;
	var $P;
	var $Q;
	var $R;
	var $S;
	var $T;
	var $U;
	var $V;
	var $W;
	var $X;
	var $Y;
	var $Z;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'texcel';
		$this->TableName = 'texcel';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`texcel`";
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

		// SHEET
		$this->SHEET = new cField('texcel', 'texcel', 'x_SHEET', 'SHEET', '`SHEET`', '`SHEET`', 200, -1, FALSE, '`SHEET`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->SHEET->Sortable = TRUE; // Allow sort
		$this->fields['SHEET'] = &$this->SHEET;

		// NOMOR
		$this->NOMOR = new cField('texcel', 'texcel', 'x_NOMOR', 'NOMOR', '`NOMOR`', '`NOMOR`', 20, -1, FALSE, '`NOMOR`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->NOMOR->Sortable = TRUE; // Allow sort
		$this->NOMOR->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['NOMOR'] = &$this->NOMOR;

		// A
		$this->A = new cField('texcel', 'texcel', 'x_A', 'A', '`A`', '`A`', 200, -1, FALSE, '`A`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->A->Sortable = TRUE; // Allow sort
		$this->fields['A'] = &$this->A;

		// B
		$this->B = new cField('texcel', 'texcel', 'x_B', 'B', '`B`', '`B`', 200, -1, FALSE, '`B`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->B->Sortable = TRUE; // Allow sort
		$this->fields['B'] = &$this->B;

		// C
		$this->C = new cField('texcel', 'texcel', 'x_C', 'C', '`C`', '`C`', 200, -1, FALSE, '`C`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->C->Sortable = TRUE; // Allow sort
		$this->fields['C'] = &$this->C;

		// D
		$this->D = new cField('texcel', 'texcel', 'x_D', 'D', '`D`', '`D`', 200, -1, FALSE, '`D`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->D->Sortable = TRUE; // Allow sort
		$this->fields['D'] = &$this->D;

		// E
		$this->E = new cField('texcel', 'texcel', 'x_E', 'E', '`E`', '`E`', 200, -1, FALSE, '`E`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->E->Sortable = TRUE; // Allow sort
		$this->fields['E'] = &$this->E;

		// F
		$this->F = new cField('texcel', 'texcel', 'x_F', 'F', '`F`', '`F`', 200, -1, FALSE, '`F`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->F->Sortable = TRUE; // Allow sort
		$this->fields['F'] = &$this->F;

		// G
		$this->G = new cField('texcel', 'texcel', 'x_G', 'G', '`G`', '`G`', 200, -1, FALSE, '`G`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->G->Sortable = TRUE; // Allow sort
		$this->fields['G'] = &$this->G;

		// H
		$this->H = new cField('texcel', 'texcel', 'x_H', 'H', '`H`', '`H`', 200, -1, FALSE, '`H`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->H->Sortable = TRUE; // Allow sort
		$this->fields['H'] = &$this->H;

		// I
		$this->_I = new cField('texcel', 'texcel', 'x__I', 'I', '`I`', '`I`', 200, -1, FALSE, '`I`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->_I->Sortable = TRUE; // Allow sort
		$this->fields['I'] = &$this->_I;

		// J
		$this->J = new cField('texcel', 'texcel', 'x_J', 'J', '`J`', '`J`', 200, -1, FALSE, '`J`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->J->Sortable = TRUE; // Allow sort
		$this->fields['J'] = &$this->J;

		// K
		$this->K = new cField('texcel', 'texcel', 'x_K', 'K', '`K`', '`K`', 200, -1, FALSE, '`K`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->K->Sortable = TRUE; // Allow sort
		$this->fields['K'] = &$this->K;

		// L
		$this->L = new cField('texcel', 'texcel', 'x_L', 'L', '`L`', '`L`', 200, -1, FALSE, '`L`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->L->Sortable = TRUE; // Allow sort
		$this->fields['L'] = &$this->L;

		// M
		$this->M = new cField('texcel', 'texcel', 'x_M', 'M', '`M`', '`M`', 200, -1, FALSE, '`M`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->M->Sortable = TRUE; // Allow sort
		$this->fields['M'] = &$this->M;

		// N
		$this->N = new cField('texcel', 'texcel', 'x_N', 'N', '`N`', '`N`', 200, -1, FALSE, '`N`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->N->Sortable = TRUE; // Allow sort
		$this->fields['N'] = &$this->N;

		// O
		$this->O = new cField('texcel', 'texcel', 'x_O', 'O', '`O`', '`O`', 200, -1, FALSE, '`O`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->O->Sortable = TRUE; // Allow sort
		$this->fields['O'] = &$this->O;

		// P
		$this->P = new cField('texcel', 'texcel', 'x_P', 'P', '`P`', '`P`', 200, -1, FALSE, '`P`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->P->Sortable = TRUE; // Allow sort
		$this->fields['P'] = &$this->P;

		// Q
		$this->Q = new cField('texcel', 'texcel', 'x_Q', 'Q', '`Q`', '`Q`', 200, -1, FALSE, '`Q`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Q->Sortable = TRUE; // Allow sort
		$this->fields['Q'] = &$this->Q;

		// R
		$this->R = new cField('texcel', 'texcel', 'x_R', 'R', '`R`', '`R`', 200, -1, FALSE, '`R`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->R->Sortable = TRUE; // Allow sort
		$this->fields['R'] = &$this->R;

		// S
		$this->S = new cField('texcel', 'texcel', 'x_S', 'S', '`S`', '`S`', 200, -1, FALSE, '`S`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->S->Sortable = TRUE; // Allow sort
		$this->fields['S'] = &$this->S;

		// T
		$this->T = new cField('texcel', 'texcel', 'x_T', 'T', '`T`', '`T`', 200, -1, FALSE, '`T`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->T->Sortable = TRUE; // Allow sort
		$this->fields['T'] = &$this->T;

		// U
		$this->U = new cField('texcel', 'texcel', 'x_U', 'U', '`U`', '`U`', 200, -1, FALSE, '`U`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->U->Sortable = TRUE; // Allow sort
		$this->fields['U'] = &$this->U;

		// V
		$this->V = new cField('texcel', 'texcel', 'x_V', 'V', '`V`', '`V`', 200, -1, FALSE, '`V`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->V->Sortable = TRUE; // Allow sort
		$this->fields['V'] = &$this->V;

		// W
		$this->W = new cField('texcel', 'texcel', 'x_W', 'W', '`W`', '`W`', 200, -1, FALSE, '`W`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->W->Sortable = TRUE; // Allow sort
		$this->fields['W'] = &$this->W;

		// X
		$this->X = new cField('texcel', 'texcel', 'x_X', 'X', '`X`', '`X`', 200, -1, FALSE, '`X`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->X->Sortable = TRUE; // Allow sort
		$this->fields['X'] = &$this->X;

		// Y
		$this->Y = new cField('texcel', 'texcel', 'x_Y', 'Y', '`Y`', '`Y`', 200, -1, FALSE, '`Y`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Y->Sortable = TRUE; // Allow sort
		$this->fields['Y'] = &$this->Y;

		// Z
		$this->Z = new cField('texcel', 'texcel', 'x_Z', 'Z', '`Z`', '`Z`', 200, -1, FALSE, '`Z`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Z->Sortable = TRUE; // Allow sort
		$this->fields['Z'] = &$this->Z;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`texcel`";
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
			if (array_key_exists('SHEET', $rs))
				ew_AddFilter($where, ew_QuotedName('SHEET', $this->DBID) . '=' . ew_QuotedValue($rs['SHEET'], $this->SHEET->FldDataType, $this->DBID));
			if (array_key_exists('NOMOR', $rs))
				ew_AddFilter($where, ew_QuotedName('NOMOR', $this->DBID) . '=' . ew_QuotedValue($rs['NOMOR'], $this->NOMOR->FldDataType, $this->DBID));
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
		return "`SHEET` = '@SHEET@' AND `NOMOR` = @NOMOR@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		$sKeyFilter = str_replace("@SHEET@", ew_AdjustSql($this->SHEET->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		if (!is_numeric($this->NOMOR->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@NOMOR@", ew_AdjustSql($this->NOMOR->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "texcellist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "texcellist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("texcelview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("texcelview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "texceladd.php?" . $this->UrlParm($parm);
		else
			$url = "texceladd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("texceledit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("texceladd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("texceldelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "SHEET:" . ew_VarToJson($this->SHEET->CurrentValue, "string", "'");
		$json .= ",NOMOR:" . ew_VarToJson($this->NOMOR->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->SHEET->CurrentValue)) {
			$sUrl .= "SHEET=" . urlencode($this->SHEET->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->NOMOR->CurrentValue)) {
			$sUrl .= "&NOMOR=" . urlencode($this->NOMOR->CurrentValue);
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
			if ($isPost && isset($_POST["SHEET"]))
				$arKey[] = ew_StripSlashes($_POST["SHEET"]);
			elseif (isset($_GET["SHEET"]))
				$arKey[] = ew_StripSlashes($_GET["SHEET"]);
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["NOMOR"]))
				$arKey[] = ew_StripSlashes($_POST["NOMOR"]);
			elseif (isset($_GET["NOMOR"]))
				$arKey[] = ew_StripSlashes($_GET["NOMOR"]);
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
				if (!is_numeric($key[1])) // NOMOR
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
			$this->SHEET->CurrentValue = $key[0];
			$this->NOMOR->CurrentValue = $key[1];
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
		$this->SHEET->setDbValue($rs->fields('SHEET'));
		$this->NOMOR->setDbValue($rs->fields('NOMOR'));
		$this->A->setDbValue($rs->fields('A'));
		$this->B->setDbValue($rs->fields('B'));
		$this->C->setDbValue($rs->fields('C'));
		$this->D->setDbValue($rs->fields('D'));
		$this->E->setDbValue($rs->fields('E'));
		$this->F->setDbValue($rs->fields('F'));
		$this->G->setDbValue($rs->fields('G'));
		$this->H->setDbValue($rs->fields('H'));
		$this->_I->setDbValue($rs->fields('I'));
		$this->J->setDbValue($rs->fields('J'));
		$this->K->setDbValue($rs->fields('K'));
		$this->L->setDbValue($rs->fields('L'));
		$this->M->setDbValue($rs->fields('M'));
		$this->N->setDbValue($rs->fields('N'));
		$this->O->setDbValue($rs->fields('O'));
		$this->P->setDbValue($rs->fields('P'));
		$this->Q->setDbValue($rs->fields('Q'));
		$this->R->setDbValue($rs->fields('R'));
		$this->S->setDbValue($rs->fields('S'));
		$this->T->setDbValue($rs->fields('T'));
		$this->U->setDbValue($rs->fields('U'));
		$this->V->setDbValue($rs->fields('V'));
		$this->W->setDbValue($rs->fields('W'));
		$this->X->setDbValue($rs->fields('X'));
		$this->Y->setDbValue($rs->fields('Y'));
		$this->Z->setDbValue($rs->fields('Z'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// SHEET
		// NOMOR
		// A
		// B
		// C
		// D
		// E
		// F
		// G
		// H
		// I
		// J
		// K
		// L
		// M
		// N
		// O
		// P
		// Q
		// R
		// S
		// T
		// U
		// V
		// W
		// X
		// Y
		// Z
		// SHEET

		$this->SHEET->ViewValue = $this->SHEET->CurrentValue;
		$this->SHEET->ViewCustomAttributes = "";

		// NOMOR
		$this->NOMOR->ViewValue = $this->NOMOR->CurrentValue;
		$this->NOMOR->ViewCustomAttributes = "";

		// A
		$this->A->ViewValue = $this->A->CurrentValue;
		$this->A->ViewCustomAttributes = "";

		// B
		$this->B->ViewValue = $this->B->CurrentValue;
		$this->B->ViewCustomAttributes = "";

		// C
		$this->C->ViewValue = $this->C->CurrentValue;
		$this->C->ViewCustomAttributes = "";

		// D
		$this->D->ViewValue = $this->D->CurrentValue;
		$this->D->ViewCustomAttributes = "";

		// E
		$this->E->ViewValue = $this->E->CurrentValue;
		$this->E->ViewCustomAttributes = "";

		// F
		$this->F->ViewValue = $this->F->CurrentValue;
		$this->F->ViewCustomAttributes = "";

		// G
		$this->G->ViewValue = $this->G->CurrentValue;
		$this->G->ViewCustomAttributes = "";

		// H
		$this->H->ViewValue = $this->H->CurrentValue;
		$this->H->ViewCustomAttributes = "";

		// I
		$this->_I->ViewValue = $this->_I->CurrentValue;
		$this->_I->ViewCustomAttributes = "";

		// J
		$this->J->ViewValue = $this->J->CurrentValue;
		$this->J->ViewCustomAttributes = "";

		// K
		$this->K->ViewValue = $this->K->CurrentValue;
		$this->K->ViewCustomAttributes = "";

		// L
		$this->L->ViewValue = $this->L->CurrentValue;
		$this->L->ViewCustomAttributes = "";

		// M
		$this->M->ViewValue = $this->M->CurrentValue;
		$this->M->ViewCustomAttributes = "";

		// N
		$this->N->ViewValue = $this->N->CurrentValue;
		$this->N->ViewCustomAttributes = "";

		// O
		$this->O->ViewValue = $this->O->CurrentValue;
		$this->O->ViewCustomAttributes = "";

		// P
		$this->P->ViewValue = $this->P->CurrentValue;
		$this->P->ViewCustomAttributes = "";

		// Q
		$this->Q->ViewValue = $this->Q->CurrentValue;
		$this->Q->ViewCustomAttributes = "";

		// R
		$this->R->ViewValue = $this->R->CurrentValue;
		$this->R->ViewCustomAttributes = "";

		// S
		$this->S->ViewValue = $this->S->CurrentValue;
		$this->S->ViewCustomAttributes = "";

		// T
		$this->T->ViewValue = $this->T->CurrentValue;
		$this->T->ViewCustomAttributes = "";

		// U
		$this->U->ViewValue = $this->U->CurrentValue;
		$this->U->ViewCustomAttributes = "";

		// V
		$this->V->ViewValue = $this->V->CurrentValue;
		$this->V->ViewCustomAttributes = "";

		// W
		$this->W->ViewValue = $this->W->CurrentValue;
		$this->W->ViewCustomAttributes = "";

		// X
		$this->X->ViewValue = $this->X->CurrentValue;
		$this->X->ViewCustomAttributes = "";

		// Y
		$this->Y->ViewValue = $this->Y->CurrentValue;
		$this->Y->ViewCustomAttributes = "";

		// Z
		$this->Z->ViewValue = $this->Z->CurrentValue;
		$this->Z->ViewCustomAttributes = "";

		// SHEET
		$this->SHEET->LinkCustomAttributes = "";
		$this->SHEET->HrefValue = "";
		$this->SHEET->TooltipValue = "";

		// NOMOR
		$this->NOMOR->LinkCustomAttributes = "";
		$this->NOMOR->HrefValue = "";
		$this->NOMOR->TooltipValue = "";

		// A
		$this->A->LinkCustomAttributes = "";
		$this->A->HrefValue = "";
		$this->A->TooltipValue = "";

		// B
		$this->B->LinkCustomAttributes = "";
		$this->B->HrefValue = "";
		$this->B->TooltipValue = "";

		// C
		$this->C->LinkCustomAttributes = "";
		$this->C->HrefValue = "";
		$this->C->TooltipValue = "";

		// D
		$this->D->LinkCustomAttributes = "";
		$this->D->HrefValue = "";
		$this->D->TooltipValue = "";

		// E
		$this->E->LinkCustomAttributes = "";
		$this->E->HrefValue = "";
		$this->E->TooltipValue = "";

		// F
		$this->F->LinkCustomAttributes = "";
		$this->F->HrefValue = "";
		$this->F->TooltipValue = "";

		// G
		$this->G->LinkCustomAttributes = "";
		$this->G->HrefValue = "";
		$this->G->TooltipValue = "";

		// H
		$this->H->LinkCustomAttributes = "";
		$this->H->HrefValue = "";
		$this->H->TooltipValue = "";

		// I
		$this->_I->LinkCustomAttributes = "";
		$this->_I->HrefValue = "";
		$this->_I->TooltipValue = "";

		// J
		$this->J->LinkCustomAttributes = "";
		$this->J->HrefValue = "";
		$this->J->TooltipValue = "";

		// K
		$this->K->LinkCustomAttributes = "";
		$this->K->HrefValue = "";
		$this->K->TooltipValue = "";

		// L
		$this->L->LinkCustomAttributes = "";
		$this->L->HrefValue = "";
		$this->L->TooltipValue = "";

		// M
		$this->M->LinkCustomAttributes = "";
		$this->M->HrefValue = "";
		$this->M->TooltipValue = "";

		// N
		$this->N->LinkCustomAttributes = "";
		$this->N->HrefValue = "";
		$this->N->TooltipValue = "";

		// O
		$this->O->LinkCustomAttributes = "";
		$this->O->HrefValue = "";
		$this->O->TooltipValue = "";

		// P
		$this->P->LinkCustomAttributes = "";
		$this->P->HrefValue = "";
		$this->P->TooltipValue = "";

		// Q
		$this->Q->LinkCustomAttributes = "";
		$this->Q->HrefValue = "";
		$this->Q->TooltipValue = "";

		// R
		$this->R->LinkCustomAttributes = "";
		$this->R->HrefValue = "";
		$this->R->TooltipValue = "";

		// S
		$this->S->LinkCustomAttributes = "";
		$this->S->HrefValue = "";
		$this->S->TooltipValue = "";

		// T
		$this->T->LinkCustomAttributes = "";
		$this->T->HrefValue = "";
		$this->T->TooltipValue = "";

		// U
		$this->U->LinkCustomAttributes = "";
		$this->U->HrefValue = "";
		$this->U->TooltipValue = "";

		// V
		$this->V->LinkCustomAttributes = "";
		$this->V->HrefValue = "";
		$this->V->TooltipValue = "";

		// W
		$this->W->LinkCustomAttributes = "";
		$this->W->HrefValue = "";
		$this->W->TooltipValue = "";

		// X
		$this->X->LinkCustomAttributes = "";
		$this->X->HrefValue = "";
		$this->X->TooltipValue = "";

		// Y
		$this->Y->LinkCustomAttributes = "";
		$this->Y->HrefValue = "";
		$this->Y->TooltipValue = "";

		// Z
		$this->Z->LinkCustomAttributes = "";
		$this->Z->HrefValue = "";
		$this->Z->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// SHEET
		$this->SHEET->EditAttrs["class"] = "form-control";
		$this->SHEET->EditCustomAttributes = "";
		$this->SHEET->EditValue = $this->SHEET->CurrentValue;
		$this->SHEET->ViewCustomAttributes = "";

		// NOMOR
		$this->NOMOR->EditAttrs["class"] = "form-control";
		$this->NOMOR->EditCustomAttributes = "";
		$this->NOMOR->EditValue = $this->NOMOR->CurrentValue;
		$this->NOMOR->ViewCustomAttributes = "";

		// A
		$this->A->EditAttrs["class"] = "form-control";
		$this->A->EditCustomAttributes = "";
		$this->A->EditValue = $this->A->CurrentValue;
		$this->A->PlaceHolder = ew_RemoveHtml($this->A->FldCaption());

		// B
		$this->B->EditAttrs["class"] = "form-control";
		$this->B->EditCustomAttributes = "";
		$this->B->EditValue = $this->B->CurrentValue;
		$this->B->PlaceHolder = ew_RemoveHtml($this->B->FldCaption());

		// C
		$this->C->EditAttrs["class"] = "form-control";
		$this->C->EditCustomAttributes = "";
		$this->C->EditValue = $this->C->CurrentValue;
		$this->C->PlaceHolder = ew_RemoveHtml($this->C->FldCaption());

		// D
		$this->D->EditAttrs["class"] = "form-control";
		$this->D->EditCustomAttributes = "";
		$this->D->EditValue = $this->D->CurrentValue;
		$this->D->PlaceHolder = ew_RemoveHtml($this->D->FldCaption());

		// E
		$this->E->EditAttrs["class"] = "form-control";
		$this->E->EditCustomAttributes = "";
		$this->E->EditValue = $this->E->CurrentValue;
		$this->E->PlaceHolder = ew_RemoveHtml($this->E->FldCaption());

		// F
		$this->F->EditAttrs["class"] = "form-control";
		$this->F->EditCustomAttributes = "";
		$this->F->EditValue = $this->F->CurrentValue;
		$this->F->PlaceHolder = ew_RemoveHtml($this->F->FldCaption());

		// G
		$this->G->EditAttrs["class"] = "form-control";
		$this->G->EditCustomAttributes = "";
		$this->G->EditValue = $this->G->CurrentValue;
		$this->G->PlaceHolder = ew_RemoveHtml($this->G->FldCaption());

		// H
		$this->H->EditAttrs["class"] = "form-control";
		$this->H->EditCustomAttributes = "";
		$this->H->EditValue = $this->H->CurrentValue;
		$this->H->PlaceHolder = ew_RemoveHtml($this->H->FldCaption());

		// I
		$this->_I->EditAttrs["class"] = "form-control";
		$this->_I->EditCustomAttributes = "";
		$this->_I->EditValue = $this->_I->CurrentValue;
		$this->_I->PlaceHolder = ew_RemoveHtml($this->_I->FldCaption());

		// J
		$this->J->EditAttrs["class"] = "form-control";
		$this->J->EditCustomAttributes = "";
		$this->J->EditValue = $this->J->CurrentValue;
		$this->J->PlaceHolder = ew_RemoveHtml($this->J->FldCaption());

		// K
		$this->K->EditAttrs["class"] = "form-control";
		$this->K->EditCustomAttributes = "";
		$this->K->EditValue = $this->K->CurrentValue;
		$this->K->PlaceHolder = ew_RemoveHtml($this->K->FldCaption());

		// L
		$this->L->EditAttrs["class"] = "form-control";
		$this->L->EditCustomAttributes = "";
		$this->L->EditValue = $this->L->CurrentValue;
		$this->L->PlaceHolder = ew_RemoveHtml($this->L->FldCaption());

		// M
		$this->M->EditAttrs["class"] = "form-control";
		$this->M->EditCustomAttributes = "";
		$this->M->EditValue = $this->M->CurrentValue;
		$this->M->PlaceHolder = ew_RemoveHtml($this->M->FldCaption());

		// N
		$this->N->EditAttrs["class"] = "form-control";
		$this->N->EditCustomAttributes = "";
		$this->N->EditValue = $this->N->CurrentValue;
		$this->N->PlaceHolder = ew_RemoveHtml($this->N->FldCaption());

		// O
		$this->O->EditAttrs["class"] = "form-control";
		$this->O->EditCustomAttributes = "";
		$this->O->EditValue = $this->O->CurrentValue;
		$this->O->PlaceHolder = ew_RemoveHtml($this->O->FldCaption());

		// P
		$this->P->EditAttrs["class"] = "form-control";
		$this->P->EditCustomAttributes = "";
		$this->P->EditValue = $this->P->CurrentValue;
		$this->P->PlaceHolder = ew_RemoveHtml($this->P->FldCaption());

		// Q
		$this->Q->EditAttrs["class"] = "form-control";
		$this->Q->EditCustomAttributes = "";
		$this->Q->EditValue = $this->Q->CurrentValue;
		$this->Q->PlaceHolder = ew_RemoveHtml($this->Q->FldCaption());

		// R
		$this->R->EditAttrs["class"] = "form-control";
		$this->R->EditCustomAttributes = "";
		$this->R->EditValue = $this->R->CurrentValue;
		$this->R->PlaceHolder = ew_RemoveHtml($this->R->FldCaption());

		// S
		$this->S->EditAttrs["class"] = "form-control";
		$this->S->EditCustomAttributes = "";
		$this->S->EditValue = $this->S->CurrentValue;
		$this->S->PlaceHolder = ew_RemoveHtml($this->S->FldCaption());

		// T
		$this->T->EditAttrs["class"] = "form-control";
		$this->T->EditCustomAttributes = "";
		$this->T->EditValue = $this->T->CurrentValue;
		$this->T->PlaceHolder = ew_RemoveHtml($this->T->FldCaption());

		// U
		$this->U->EditAttrs["class"] = "form-control";
		$this->U->EditCustomAttributes = "";
		$this->U->EditValue = $this->U->CurrentValue;
		$this->U->PlaceHolder = ew_RemoveHtml($this->U->FldCaption());

		// V
		$this->V->EditAttrs["class"] = "form-control";
		$this->V->EditCustomAttributes = "";
		$this->V->EditValue = $this->V->CurrentValue;
		$this->V->PlaceHolder = ew_RemoveHtml($this->V->FldCaption());

		// W
		$this->W->EditAttrs["class"] = "form-control";
		$this->W->EditCustomAttributes = "";
		$this->W->EditValue = $this->W->CurrentValue;
		$this->W->PlaceHolder = ew_RemoveHtml($this->W->FldCaption());

		// X
		$this->X->EditAttrs["class"] = "form-control";
		$this->X->EditCustomAttributes = "";
		$this->X->EditValue = $this->X->CurrentValue;
		$this->X->PlaceHolder = ew_RemoveHtml($this->X->FldCaption());

		// Y
		$this->Y->EditAttrs["class"] = "form-control";
		$this->Y->EditCustomAttributes = "";
		$this->Y->EditValue = $this->Y->CurrentValue;
		$this->Y->PlaceHolder = ew_RemoveHtml($this->Y->FldCaption());

		// Z
		$this->Z->EditAttrs["class"] = "form-control";
		$this->Z->EditCustomAttributes = "";
		$this->Z->EditValue = $this->Z->CurrentValue;
		$this->Z->PlaceHolder = ew_RemoveHtml($this->Z->FldCaption());

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
					if ($this->SHEET->Exportable) $Doc->ExportCaption($this->SHEET);
					if ($this->NOMOR->Exportable) $Doc->ExportCaption($this->NOMOR);
					if ($this->A->Exportable) $Doc->ExportCaption($this->A);
					if ($this->B->Exportable) $Doc->ExportCaption($this->B);
					if ($this->C->Exportable) $Doc->ExportCaption($this->C);
					if ($this->D->Exportable) $Doc->ExportCaption($this->D);
					if ($this->E->Exportable) $Doc->ExportCaption($this->E);
					if ($this->F->Exportable) $Doc->ExportCaption($this->F);
					if ($this->G->Exportable) $Doc->ExportCaption($this->G);
					if ($this->H->Exportable) $Doc->ExportCaption($this->H);
					if ($this->_I->Exportable) $Doc->ExportCaption($this->_I);
					if ($this->J->Exportable) $Doc->ExportCaption($this->J);
					if ($this->K->Exportable) $Doc->ExportCaption($this->K);
					if ($this->L->Exportable) $Doc->ExportCaption($this->L);
					if ($this->M->Exportable) $Doc->ExportCaption($this->M);
					if ($this->N->Exportable) $Doc->ExportCaption($this->N);
					if ($this->O->Exportable) $Doc->ExportCaption($this->O);
					if ($this->P->Exportable) $Doc->ExportCaption($this->P);
					if ($this->Q->Exportable) $Doc->ExportCaption($this->Q);
					if ($this->R->Exportable) $Doc->ExportCaption($this->R);
					if ($this->S->Exportable) $Doc->ExportCaption($this->S);
					if ($this->T->Exportable) $Doc->ExportCaption($this->T);
					if ($this->U->Exportable) $Doc->ExportCaption($this->U);
					if ($this->V->Exportable) $Doc->ExportCaption($this->V);
					if ($this->W->Exportable) $Doc->ExportCaption($this->W);
					if ($this->X->Exportable) $Doc->ExportCaption($this->X);
					if ($this->Y->Exportable) $Doc->ExportCaption($this->Y);
					if ($this->Z->Exportable) $Doc->ExportCaption($this->Z);
				} else {
					if ($this->SHEET->Exportable) $Doc->ExportCaption($this->SHEET);
					if ($this->NOMOR->Exportable) $Doc->ExportCaption($this->NOMOR);
					if ($this->A->Exportable) $Doc->ExportCaption($this->A);
					if ($this->B->Exportable) $Doc->ExportCaption($this->B);
					if ($this->C->Exportable) $Doc->ExportCaption($this->C);
					if ($this->D->Exportable) $Doc->ExportCaption($this->D);
					if ($this->E->Exportable) $Doc->ExportCaption($this->E);
					if ($this->F->Exportable) $Doc->ExportCaption($this->F);
					if ($this->G->Exportable) $Doc->ExportCaption($this->G);
					if ($this->H->Exportable) $Doc->ExportCaption($this->H);
					if ($this->_I->Exportable) $Doc->ExportCaption($this->_I);
					if ($this->J->Exportable) $Doc->ExportCaption($this->J);
					if ($this->K->Exportable) $Doc->ExportCaption($this->K);
					if ($this->L->Exportable) $Doc->ExportCaption($this->L);
					if ($this->M->Exportable) $Doc->ExportCaption($this->M);
					if ($this->N->Exportable) $Doc->ExportCaption($this->N);
					if ($this->O->Exportable) $Doc->ExportCaption($this->O);
					if ($this->P->Exportable) $Doc->ExportCaption($this->P);
					if ($this->Q->Exportable) $Doc->ExportCaption($this->Q);
					if ($this->R->Exportable) $Doc->ExportCaption($this->R);
					if ($this->S->Exportable) $Doc->ExportCaption($this->S);
					if ($this->T->Exportable) $Doc->ExportCaption($this->T);
					if ($this->U->Exportable) $Doc->ExportCaption($this->U);
					if ($this->V->Exportable) $Doc->ExportCaption($this->V);
					if ($this->W->Exportable) $Doc->ExportCaption($this->W);
					if ($this->X->Exportable) $Doc->ExportCaption($this->X);
					if ($this->Y->Exportable) $Doc->ExportCaption($this->Y);
					if ($this->Z->Exportable) $Doc->ExportCaption($this->Z);
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
						if ($this->SHEET->Exportable) $Doc->ExportField($this->SHEET);
						if ($this->NOMOR->Exportable) $Doc->ExportField($this->NOMOR);
						if ($this->A->Exportable) $Doc->ExportField($this->A);
						if ($this->B->Exportable) $Doc->ExportField($this->B);
						if ($this->C->Exportable) $Doc->ExportField($this->C);
						if ($this->D->Exportable) $Doc->ExportField($this->D);
						if ($this->E->Exportable) $Doc->ExportField($this->E);
						if ($this->F->Exportable) $Doc->ExportField($this->F);
						if ($this->G->Exportable) $Doc->ExportField($this->G);
						if ($this->H->Exportable) $Doc->ExportField($this->H);
						if ($this->_I->Exportable) $Doc->ExportField($this->_I);
						if ($this->J->Exportable) $Doc->ExportField($this->J);
						if ($this->K->Exportable) $Doc->ExportField($this->K);
						if ($this->L->Exportable) $Doc->ExportField($this->L);
						if ($this->M->Exportable) $Doc->ExportField($this->M);
						if ($this->N->Exportable) $Doc->ExportField($this->N);
						if ($this->O->Exportable) $Doc->ExportField($this->O);
						if ($this->P->Exportable) $Doc->ExportField($this->P);
						if ($this->Q->Exportable) $Doc->ExportField($this->Q);
						if ($this->R->Exportable) $Doc->ExportField($this->R);
						if ($this->S->Exportable) $Doc->ExportField($this->S);
						if ($this->T->Exportable) $Doc->ExportField($this->T);
						if ($this->U->Exportable) $Doc->ExportField($this->U);
						if ($this->V->Exportable) $Doc->ExportField($this->V);
						if ($this->W->Exportable) $Doc->ExportField($this->W);
						if ($this->X->Exportable) $Doc->ExportField($this->X);
						if ($this->Y->Exportable) $Doc->ExportField($this->Y);
						if ($this->Z->Exportable) $Doc->ExportField($this->Z);
					} else {
						if ($this->SHEET->Exportable) $Doc->ExportField($this->SHEET);
						if ($this->NOMOR->Exportable) $Doc->ExportField($this->NOMOR);
						if ($this->A->Exportable) $Doc->ExportField($this->A);
						if ($this->B->Exportable) $Doc->ExportField($this->B);
						if ($this->C->Exportable) $Doc->ExportField($this->C);
						if ($this->D->Exportable) $Doc->ExportField($this->D);
						if ($this->E->Exportable) $Doc->ExportField($this->E);
						if ($this->F->Exportable) $Doc->ExportField($this->F);
						if ($this->G->Exportable) $Doc->ExportField($this->G);
						if ($this->H->Exportable) $Doc->ExportField($this->H);
						if ($this->_I->Exportable) $Doc->ExportField($this->_I);
						if ($this->J->Exportable) $Doc->ExportField($this->J);
						if ($this->K->Exportable) $Doc->ExportField($this->K);
						if ($this->L->Exportable) $Doc->ExportField($this->L);
						if ($this->M->Exportable) $Doc->ExportField($this->M);
						if ($this->N->Exportable) $Doc->ExportField($this->N);
						if ($this->O->Exportable) $Doc->ExportField($this->O);
						if ($this->P->Exportable) $Doc->ExportField($this->P);
						if ($this->Q->Exportable) $Doc->ExportField($this->Q);
						if ($this->R->Exportable) $Doc->ExportField($this->R);
						if ($this->S->Exportable) $Doc->ExportField($this->S);
						if ($this->T->Exportable) $Doc->ExportField($this->T);
						if ($this->U->Exportable) $Doc->ExportField($this->U);
						if ($this->V->Exportable) $Doc->ExportField($this->V);
						if ($this->W->Exportable) $Doc->ExportField($this->W);
						if ($this->X->Exportable) $Doc->ExportField($this->X);
						if ($this->Y->Exportable) $Doc->ExportField($this->Y);
						if ($this->Z->Exportable) $Doc->ExportField($this->Z);
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
