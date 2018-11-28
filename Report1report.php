<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php

// Global variable for table object
$Report1 = NULL;

//
// Table class for Report1
//
class cReport1 extends cTableBase {
	var $group;
	var $parent;
	var $rekening;
	var $id1;
	var $rekening1;
	var $id2;
	var $rekening2;
	var $tipe;
	var $posisi;
	var $laporan;
	var $status;
	var $keterangan;
	var $active;
	var $id;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'Report1';
		$this->TableName = 'Report1';
		$this->TableType = 'REPORT';

		// Update Table
		$this->UpdateTable = "`vrekening2`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->UserIDAllowSecurity = 0; // User ID Allow

		// group
		$this->group = new cField('Report1', 'Report1', 'x_group', 'group', '`group`', '`group`', 20, -1, FALSE, '`group`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->group->Sortable = TRUE; // Allow sort
		$this->group->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->group->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->group->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['group'] = &$this->group;

		// parent
		$this->parent = new cField('Report1', 'Report1', 'x_parent', 'parent', '`parent`', '`parent`', 200, -1, FALSE, '`parent`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->parent->Sortable = TRUE; // Allow sort
		$this->parent->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->parent->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['parent'] = &$this->parent;

		// rekening
		$this->rekening = new cField('Report1', 'Report1', 'x_rekening', 'rekening', '`rekening`', '`rekening`', 200, -1, FALSE, '`rekening`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->rekening->Sortable = TRUE; // Allow sort
		$this->fields['rekening'] = &$this->rekening;

		// id1
		$this->id1 = new cField('Report1', 'Report1', 'x_id1', 'id1', '`id1`', '`id1`', 200, -1, FALSE, '`id1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->id1->Sortable = TRUE; // Allow sort
		$this->fields['id1'] = &$this->id1;

		// rekening1
		$this->rekening1 = new cField('Report1', 'Report1', 'x_rekening1', 'rekening1', '`rekening1`', '`rekening1`', 200, -1, FALSE, '`rekening1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->rekening1->Sortable = TRUE; // Allow sort
		$this->fields['rekening1'] = &$this->rekening1;

		// id2
		$this->id2 = new cField('Report1', 'Report1', 'x_id2', 'id2', '`id2`', '`id2`', 200, -1, FALSE, '`id2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->id2->Sortable = TRUE; // Allow sort
		$this->fields['id2'] = &$this->id2;

		// rekening2
		$this->rekening2 = new cField('Report1', 'Report1', 'x_rekening2', 'rekening2', '`rekening2`', '`rekening2`', 200, -1, FALSE, '`rekening2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->rekening2->Sortable = TRUE; // Allow sort
		$this->fields['rekening2'] = &$this->rekening2;

		// tipe
		$this->tipe = new cField('Report1', 'Report1', 'x_tipe', 'tipe', '`tipe`', '`tipe`', 200, -1, FALSE, '`tipe`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->tipe->Sortable = TRUE; // Allow sort
		$this->tipe->OptionCount = 2;
		$this->fields['tipe'] = &$this->tipe;

		// posisi
		$this->posisi = new cField('Report1', 'Report1', 'x_posisi', 'posisi', '`posisi`', '`posisi`', 200, -1, FALSE, '`posisi`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->posisi->Sortable = TRUE; // Allow sort
		$this->posisi->OptionCount = 2;
		$this->fields['posisi'] = &$this->posisi;

		// laporan
		$this->laporan = new cField('Report1', 'Report1', 'x_laporan', 'laporan', '`laporan`', '`laporan`', 200, -1, FALSE, '`laporan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->laporan->Sortable = TRUE; // Allow sort
		$this->laporan->OptionCount = 2;
		$this->fields['laporan'] = &$this->laporan;

		// status
		$this->status = new cField('Report1', 'Report1', 'x_status', 'status', '`status`', '`status`', 200, -1, FALSE, '`status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'CHECKBOX');
		$this->status->Sortable = TRUE; // Allow sort
		$this->status->OptionCount = 1;
		$this->fields['status'] = &$this->status;

		// keterangan
		$this->keterangan = new cField('Report1', 'Report1', 'x_keterangan', 'keterangan', '`keterangan`', '`keterangan`', 200, -1, FALSE, '`keterangan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->keterangan->Sortable = TRUE; // Allow sort
		$this->fields['keterangan'] = &$this->keterangan;

		// active
		$this->active = new cField('Report1', 'Report1', 'x_active', 'active', '`active`', '`active`', 202, -1, FALSE, '`active`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->active->Sortable = TRUE; // Allow sort
		$this->active->OptionCount = 2;
		$this->fields['active'] = &$this->active;

		// id
		$this->id = new cField('Report1', 'Report1', 'x_id', 'id', '`id`', '`id`', 200, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->id->Sortable = TRUE; // Allow sort
		$this->fields['id'] = &$this->id;
	}

	// Set Field Visibility
	function SetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Report group level SQL
	var $_SqlGroupSelect = "";

	function getSqlGroupSelect() { // Select
		return ($this->_SqlGroupSelect <> "") ? $this->_SqlGroupSelect : "SELECT DISTINCT `group` FROM `vrekening2`";
	}

	function SqlGroupSelect() { // For backward compatibility
		return $this->getSqlGroupSelect();
	}

	function setSqlGroupSelect($v) {
		$this->_SqlGroupSelect = $v;
	}
	var $_SqlGroupWhere = "";

	function getSqlGroupWhere() { // Where
		return ($this->_SqlGroupWhere <> "") ? $this->_SqlGroupWhere : "";
	}

	function SqlGroupWhere() { // For backward compatibility
		return $this->getSqlGroupWhere();
	}

	function setSqlGroupWhere($v) {
		$this->_SqlGroupWhere = $v;
	}
	var $_SqlGroupGroupBy = "";

	function getSqlGroupGroupBy() { // Group By
		return ($this->_SqlGroupGroupBy <> "") ? $this->_SqlGroupGroupBy : "";
	}

	function SqlGroupGroupBy() { // For backward compatibility
		return $this->getSqlGroupGroupBy();
	}

	function setSqlGroupGroupBy($v) {
		$this->_SqlGroupGroupBy = $v;
	}
	var $_SqlGroupHaving = "";

	function getSqlGroupHaving() { // Having
		return ($this->_SqlGroupHaving <> "") ? $this->_SqlGroupHaving : "";
	}

	function SqlGroupHaving() { // For backward compatibility
		return $this->getSqlGroupHaving();
	}

	function setSqlGroupHaving($v) {
		$this->_SqlGroupHaving = $v;
	}
	var $_SqlGroupOrderBy = "";

	function getSqlGroupOrderBy() { // Order By
		return ($this->_SqlGroupOrderBy <> "") ? $this->_SqlGroupOrderBy : "`group` ASC";
	}

	function SqlGroupOrderBy() { // For backward compatibility
		return $this->getSqlGroupOrderBy();
	}

	function setSqlGroupOrderBy($v) {
		$this->_SqlGroupOrderBy = $v;
	}

	// Report detail level SQL
	var $_SqlDetailSelect = "";

	function getSqlDetailSelect() { // Select
		return ($this->_SqlDetailSelect <> "") ? $this->_SqlDetailSelect : "SELECT * FROM `vrekening2`";
	}

	function SqlDetailSelect() { // For backward compatibility
		return $this->getSqlDetailSelect();
	}

	function setSqlDetailSelect($v) {
		$this->_SqlDetailSelect = $v;
	}
	var $_SqlDetailWhere = "";

	function getSqlDetailWhere() { // Where
		return ($this->_SqlDetailWhere <> "") ? $this->_SqlDetailWhere : "";
	}

	function SqlDetailWhere() { // For backward compatibility
		return $this->getSqlDetailWhere();
	}

	function setSqlDetailWhere($v) {
		$this->_SqlDetailWhere = $v;
	}
	var $_SqlDetailGroupBy = "";

	function getSqlDetailGroupBy() { // Group By
		return ($this->_SqlDetailGroupBy <> "") ? $this->_SqlDetailGroupBy : "";
	}

	function SqlDetailGroupBy() { // For backward compatibility
		return $this->getSqlDetailGroupBy();
	}

	function setSqlDetailGroupBy($v) {
		$this->_SqlDetailGroupBy = $v;
	}
	var $_SqlDetailHaving = "";

	function getSqlDetailHaving() { // Having
		return ($this->_SqlDetailHaving <> "") ? $this->_SqlDetailHaving : "";
	}

	function SqlDetailHaving() { // For backward compatibility
		return $this->getSqlDetailHaving();
	}

	function setSqlDetailHaving($v) {
		$this->_SqlDetailHaving = $v;
	}
	var $_SqlDetailOrderBy = "";

	function getSqlDetailOrderBy() { // Order By
		return ($this->_SqlDetailOrderBy <> "") ? $this->_SqlDetailOrderBy : "";
	}

	function SqlDetailOrderBy() { // For backward compatibility
		return $this->getSqlDetailOrderBy();
	}

	function setSqlDetailOrderBy($v) {
		$this->_SqlDetailOrderBy = $v;
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

	// Report group SQL
	function GroupSQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = "";
		return ew_BuildSelectSql($this->getSqlGroupSelect(), $this->getSqlGroupWhere(),
			 $this->getSqlGroupGroupBy(), $this->getSqlGroupHaving(),
			 $this->getSqlGroupOrderBy(), $sFilter, $sSort);
	}

	// Report detail SQL
	function DetailSQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = "";
		return ew_BuildSelectSql($this->getSqlDetailSelect(), $this->getSqlDetailWhere(),
			$this->getSqlDetailGroupBy(), $this->getSqlDetailHaving(),
			$this->getSqlDetailOrderBy(), $sFilter, $sSort);
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
			return "Report1report.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "Report1report.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "?" . $this->UrlParm($parm);
		else
			$url = "";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("", $this->UrlParm());
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
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$Report1_report = NULL; // Initialize page object first

class cReport1_report extends cReport1 {

	// Page ID
	var $PageID = 'report';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'Report1';

	// Page object name
	var $PageObjName = 'Report1_report';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		return $PageUrl;
	}

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		return TRUE;
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (Report1)
		if (!isset($GLOBALS["Report1"]) || get_class($GLOBALS["Report1"]) == "cReport1") {
			$GLOBALS["Report1"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["Report1"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'report', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'Report1', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Get export parameters
		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT_REPORT;
		if ($this->Export <> "" && array_key_exists($this->Export, $EW_EXPORT_REPORT)) {
			$sContent = ob_get_contents();
			$fn = $EW_EXPORT_REPORT[$this->Export];
			$this->$fn($sContent);
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $ExportOptions; // Export options
	var $RecCnt = 0;
	var $RowCnt = 0; // For custom view tag
	var $ReportSql = "";
	var $ReportFilter = "";
	var $DefaultFilter = "";
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $MasterRecordExists;
	var $Command;
	var $DtlRecordCount;
	var $ReportGroups;
	var $ReportCounts;
	var $LevelBreak;
	var $ReportTotals;
	var $ReportMaxs;
	var $ReportMins;
	var $Recordset;
	var $DetailRecordset;
	var $RecordExists;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		$this->ReportGroups = &ew_InitArray(2, NULL);
		$this->ReportCounts = &ew_InitArray(2, 0);
		$this->LevelBreak = &ew_InitArray(2, FALSE);
		$this->ReportTotals = &ew_Init2DArray(2, 14, 0);
		$this->ReportMaxs = &ew_Init2DArray(2, 14, 0);
		$this->ReportMins = &ew_Init2DArray(2, 14, 0);

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
	}

	// Check level break
	function ChkLvlBreak() {
		$this->LevelBreak[1] = FALSE;
		if ($this->RecCnt == 0) { // Start Or End of Recordset
			$this->LevelBreak[1] = TRUE;
		} else {
			if (!ew_CompareValue($this->group->CurrentValue, $this->ReportGroups[0])) {
				$this->LevelBreak[1] = TRUE;
			}
		}
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// group
		// parent
		// rekening
		// id1
		// rekening1
		// id2
		// rekening2
		// tipe
		// posisi
		// laporan
		// status
		// keterangan
		// active
		// id

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// group
		if (strval($this->group->CurrentValue) <> "") {
			$sFilterWrk = "`group`" . ew_SearchString("=", $this->group->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `group`, `rekening` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `trekening`";
		$sWhereWrk = "";
		$this->group->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->group->ViewValue = $this->group->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->group->ViewValue = $this->group->CurrentValue;
			}
		} else {
			$this->group->ViewValue = NULL;
		}
		$this->group->ViewCustomAttributes = "";

		// parent
		if (strval($this->parent->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->parent->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `id`, `rekening` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `trekening`";
		$sWhereWrk = "";
		$this->parent->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->parent->ViewValue = $this->parent->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->parent->ViewValue = $this->parent->CurrentValue;
			}
		} else {
			$this->parent->ViewValue = NULL;
		}
		$this->parent->ViewCustomAttributes = "";

		// rekening
		$this->rekening->ViewValue = $this->rekening->CurrentValue;
		$this->rekening->ViewCustomAttributes = "";

		// id1
		$this->id1->ViewValue = $this->id1->CurrentValue;
		$this->id1->ViewCustomAttributes = "";

		// rekening1
		$this->rekening1->ViewValue = $this->rekening1->CurrentValue;
		$this->rekening1->ViewCustomAttributes = "";

		// id2
		$this->id2->ViewValue = $this->id2->CurrentValue;
		$this->id2->ViewCustomAttributes = "";

		// rekening2
		$this->rekening2->ViewValue = $this->rekening2->CurrentValue;
		$this->rekening2->ViewCustomAttributes = "";

		// tipe
		if (strval($this->tipe->CurrentValue) <> "") {
			$this->tipe->ViewValue = $this->tipe->OptionCaption($this->tipe->CurrentValue);
		} else {
			$this->tipe->ViewValue = NULL;
		}
		$this->tipe->ViewCustomAttributes = "";

		// posisi
		if (strval($this->posisi->CurrentValue) <> "") {
			$this->posisi->ViewValue = $this->posisi->OptionCaption($this->posisi->CurrentValue);
		} else {
			$this->posisi->ViewValue = NULL;
		}
		$this->posisi->ViewCustomAttributes = "";

		// laporan
		if (strval($this->laporan->CurrentValue) <> "") {
			$this->laporan->ViewValue = $this->laporan->OptionCaption($this->laporan->CurrentValue);
		} else {
			$this->laporan->ViewValue = NULL;
		}
		$this->laporan->ViewCustomAttributes = "";

		// status
		if (strval($this->status->CurrentValue) <> "") {
			$this->status->ViewValue = "";
			$arwrk = explode(",", strval($this->status->CurrentValue));
			$cnt = count($arwrk);
			for ($ari = 0; $ari < $cnt; $ari++) {
				$this->status->ViewValue .= $this->status->OptionCaption(trim($arwrk[$ari]));
				if ($ari < $cnt-1) $this->status->ViewValue .= ew_ViewOptionSeparator($ari);
			}
		} else {
			$this->status->ViewValue = NULL;
		}
		$this->status->ViewCustomAttributes = "";

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

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

			// group
			$this->group->LinkCustomAttributes = "";
			$this->group->HrefValue = "";
			$this->group->TooltipValue = "";

			// parent
			$this->parent->LinkCustomAttributes = "";
			$this->parent->HrefValue = "";
			$this->parent->TooltipValue = "";

			// rekening
			$this->rekening->LinkCustomAttributes = "";
			$this->rekening->HrefValue = "";
			$this->rekening->TooltipValue = "";

			// id1
			$this->id1->LinkCustomAttributes = "";
			$this->id1->HrefValue = "";
			$this->id1->TooltipValue = "";

			// rekening1
			$this->rekening1->LinkCustomAttributes = "";
			$this->rekening1->HrefValue = "";
			$this->rekening1->TooltipValue = "";

			// id2
			$this->id2->LinkCustomAttributes = "";
			$this->id2->HrefValue = "";
			$this->id2->TooltipValue = "";

			// rekening2
			$this->rekening2->LinkCustomAttributes = "";
			$this->rekening2->HrefValue = "";
			$this->rekening2->TooltipValue = "";

			// tipe
			$this->tipe->LinkCustomAttributes = "";
			$this->tipe->HrefValue = "";
			$this->tipe->TooltipValue = "";

			// posisi
			$this->posisi->LinkCustomAttributes = "";
			$this->posisi->HrefValue = "";
			$this->posisi->TooltipValue = "";

			// laporan
			$this->laporan->LinkCustomAttributes = "";
			$this->laporan->HrefValue = "";
			$this->laporan->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";
			$this->keterangan->TooltipValue = "";

			// active
			$this->active->LinkCustomAttributes = "";
			$this->active->HrefValue = "";
			$this->active->TooltipValue = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("report", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($Report1_report)) $Report1_report = new cReport1_report();

// Page init
$Report1_report->Page_Init();

// Page main
$Report1_report->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$Report1_report->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($Report1->Export == "") { ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<div class="ewToolbar">
<?php if ($Report1->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($Report1->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php
$Report1_report->DefaultFilter = "";
$Report1_report->ReportFilter = $Report1_report->DefaultFilter;
if ($Report1_report->DbDetailFilter <> "") {
	if ($Report1_report->ReportFilter <> "") $Report1_report->ReportFilter .= " AND ";
	$Report1_report->ReportFilter .= "(" . $Report1_report->DbDetailFilter . ")";
}
$ReportConn = &$Report1_report->Connection();

// Set up filter and load Group level sql
$Report1->CurrentFilter = $Report1_report->ReportFilter;
$Report1_report->ReportSql = $Report1->GroupSQL();

// Load recordset
$Report1_report->Recordset = $ReportConn->Execute($Report1_report->ReportSql);
$Report1_report->RecordExists = !$Report1_report->Recordset->EOF;
?>
<?php if ($Report1->Export == "") { ?>
<?php if ($Report1_report->RecordExists) { ?>
<div class="ewViewExportOptions"><?php $Report1_report->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php } ?>
<?php $Report1_report->ShowPageHeader(); ?>
<table class="ewReportTable">
<?php

// Get First Row
if ($Report1_report->RecordExists) {
	$Report1->group->setDbValue($Report1_report->Recordset->fields('group'));
	$Report1_report->ReportGroups[0] = $Report1->group->DbValue;
}
$Report1_report->RecCnt = 0;
$Report1_report->ReportCounts[0] = 0;
$Report1_report->ChkLvlBreak();
while (!$Report1_report->Recordset->EOF) {

	// Render for view
	$Report1->RowType = EW_ROWTYPE_VIEW;
	$Report1->ResetAttrs();
	$Report1_report->RenderRow();

	// Show group headers
	if ($Report1_report->LevelBreak[1]) { // Reset counter and aggregation
?>
	<tr><td class="ewGroupField"><?php echo $Report1->group->FldCaption() ?></td>
	<td colspan=13 class="ewGroupName">
<span<?php echo $Report1->group->ViewAttributes() ?>>
<?php echo $Report1->group->ViewValue ?></span>
</td></tr>
<?php
	}

	// Get detail records
	$Report1_report->ReportFilter = $Report1_report->DefaultFilter;
	if ($Report1_report->ReportFilter <> "") $Report1_report->ReportFilter .= " AND ";
	if (is_null($Report1->group->CurrentValue)) {
		$Report1_report->ReportFilter .= "(`group` IS NULL)";
	} else {
		$Report1_report->ReportFilter .= "(`group` = " . ew_QuotedValue($Report1->group->CurrentValue, EW_DATATYPE_NUMBER, $Report1_report->DBID) . ")";
	}
	if ($Report1_report->DbDetailFilter <> "") {
		if ($Report1_report->ReportFilter <> "")
			$Report1_report->ReportFilter .= " AND ";
		$Report1_report->ReportFilter .= "(" . $Report1_report->DbDetailFilter . ")";
	}

	// Set up detail SQL
	$Report1->CurrentFilter = $Report1_report->ReportFilter;
	$Report1_report->ReportSql = $Report1->DetailSQL();

	// Load detail records
	$Report1_report->DetailRecordset = $ReportConn->Execute($Report1_report->ReportSql);
	$Report1_report->DtlRecordCount = $Report1_report->DetailRecordset->RecordCount();

	// Initialize aggregates
	if (!$Report1_report->DetailRecordset->EOF) {
		$Report1_report->RecCnt++;
	}
	if ($Report1_report->RecCnt == 1) {
		$Report1_report->ReportCounts[0] = 0;
	}
	for ($i = 1; $i <= 1; $i++) {
		if ($Report1_report->LevelBreak[$i]) { // Reset counter and aggregation
			$Report1_report->ReportCounts[$i] = 0;
		}
	}
	$Report1_report->ReportCounts[0] += $Report1_report->DtlRecordCount;
	$Report1_report->ReportCounts[1] += $Report1_report->DtlRecordCount;
	if ($Report1_report->RecordExists) {
?>
	<tr>
		<td><div class="ewGroupIndent"></div></td>
		<td class="ewGroupHeader"><?php echo $Report1->parent->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->rekening->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->id1->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->rekening1->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->id2->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->rekening2->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->tipe->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->posisi->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->laporan->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->status->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->keterangan->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->active->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->id->FldCaption() ?></td>
	</tr>
<?php
	}
	while (!$Report1_report->DetailRecordset->EOF) {
		$Report1_report->RowCnt++;
		$Report1->parent->setDbValue($Report1_report->DetailRecordset->fields('parent'));
		$Report1->rekening->setDbValue($Report1_report->DetailRecordset->fields('rekening'));
		$Report1->id1->setDbValue($Report1_report->DetailRecordset->fields('id1'));
		$Report1->rekening1->setDbValue($Report1_report->DetailRecordset->fields('rekening1'));
		$Report1->id2->setDbValue($Report1_report->DetailRecordset->fields('id2'));
		$Report1->rekening2->setDbValue($Report1_report->DetailRecordset->fields('rekening2'));
		$Report1->tipe->setDbValue($Report1_report->DetailRecordset->fields('tipe'));
		$Report1->posisi->setDbValue($Report1_report->DetailRecordset->fields('posisi'));
		$Report1->laporan->setDbValue($Report1_report->DetailRecordset->fields('laporan'));
		$Report1->status->setDbValue($Report1_report->DetailRecordset->fields('status'));
		$Report1->keterangan->setDbValue($Report1_report->DetailRecordset->fields('keterangan'));
		$Report1->active->setDbValue($Report1_report->DetailRecordset->fields('active'));
		$Report1->id->setDbValue($Report1_report->DetailRecordset->fields('id'));

		// Render for view
		$Report1->RowType = EW_ROWTYPE_VIEW;
		$Report1->ResetAttrs();
		$Report1_report->RenderRow();
?>
	<tr>
		<td><div class="ewGroupIndent"></div></td>
		<td<?php echo $Report1->parent->CellAttributes() ?>>
<span<?php echo $Report1->parent->ViewAttributes() ?>>
<?php echo $Report1->parent->ViewValue ?></span>
</td>
		<td<?php echo $Report1->rekening->CellAttributes() ?>>
<span<?php echo $Report1->rekening->ViewAttributes() ?>>
<?php echo $Report1->rekening->ViewValue ?></span>
</td>
		<td<?php echo $Report1->id1->CellAttributes() ?>>
<span<?php echo $Report1->id1->ViewAttributes() ?>>
<?php echo $Report1->id1->ViewValue ?></span>
</td>
		<td<?php echo $Report1->rekening1->CellAttributes() ?>>
<span<?php echo $Report1->rekening1->ViewAttributes() ?>>
<?php echo $Report1->rekening1->ViewValue ?></span>
</td>
		<td<?php echo $Report1->id2->CellAttributes() ?>>
<span<?php echo $Report1->id2->ViewAttributes() ?>>
<?php echo $Report1->id2->ViewValue ?></span>
</td>
		<td<?php echo $Report1->rekening2->CellAttributes() ?>>
<span<?php echo $Report1->rekening2->ViewAttributes() ?>>
<?php echo $Report1->rekening2->ViewValue ?></span>
</td>
		<td<?php echo $Report1->tipe->CellAttributes() ?>>
<span<?php echo $Report1->tipe->ViewAttributes() ?>>
<?php echo $Report1->tipe->ViewValue ?></span>
</td>
		<td<?php echo $Report1->posisi->CellAttributes() ?>>
<span<?php echo $Report1->posisi->ViewAttributes() ?>>
<?php echo $Report1->posisi->ViewValue ?></span>
</td>
		<td<?php echo $Report1->laporan->CellAttributes() ?>>
<span<?php echo $Report1->laporan->ViewAttributes() ?>>
<?php echo $Report1->laporan->ViewValue ?></span>
</td>
		<td<?php echo $Report1->status->CellAttributes() ?>>
<span<?php echo $Report1->status->ViewAttributes() ?>>
<?php echo $Report1->status->ViewValue ?></span>
</td>
		<td<?php echo $Report1->keterangan->CellAttributes() ?>>
<span<?php echo $Report1->keterangan->ViewAttributes() ?>>
<?php echo $Report1->keterangan->ViewValue ?></span>
</td>
		<td<?php echo $Report1->active->CellAttributes() ?>>
<span<?php echo $Report1->active->ViewAttributes() ?>>
<?php echo $Report1->active->ViewValue ?></span>
</td>
		<td<?php echo $Report1->id->CellAttributes() ?>>
<span<?php echo $Report1->id->ViewAttributes() ?>>
<?php echo $Report1->id->ViewValue ?></span>
</td>
	</tr>
<?php
		$Report1_report->DetailRecordset->MoveNext();
	}
	$Report1_report->DetailRecordset->Close();

	// Save old group data
	$Report1_report->ReportGroups[0] = $Report1->group->CurrentValue;

	// Get next record
	$Report1_report->Recordset->MoveNext();
	if ($Report1_report->Recordset->EOF) {
		$Report1_report->RecCnt = 0; // EOF, force all level breaks
	} else {
		$Report1->group->setDbValue($Report1_report->Recordset->fields('group'));
	}
	$Report1_report->ChkLvlBreak();

	// Show footers
	if ($Report1_report->LevelBreak[1]) {
		$Report1->group->CurrentValue = $Report1_report->ReportGroups[0];

		// Render row for view
		$Report1->RowType = EW_ROWTYPE_VIEW;
		$Report1->ResetAttrs();
		$Report1_report->RenderRow();
		$Report1->group->CurrentValue = $Report1->group->DbValue;
?>
<?php
}
}

// Close recordset
$Report1_report->Recordset->Close();
?>
<?php if ($Report1_report->RecordExists) { ?>
	<tr><td colspan=14>&nbsp;<br></td></tr>
	<tr><td colspan=14 class="ewGrandSummary"><?php echo $Language->Phrase("RptGrandTotal") ?>&nbsp;(<?php echo ew_FormatNumber($Report1_report->ReportCounts[0], 0) ?>&nbsp;<?php echo $Language->Phrase("RptDtlRec") ?>)</td></tr>
<?php } ?>
<?php if ($Report1_report->RecordExists) { ?>
	<tr><td colspan=14>&nbsp;<br></td></tr>
<?php } else { ?>
	<tr><td><?php echo $Language->Phrase("NoRecord") ?></td></tr>
<?php } ?>
</table>
<?php
$Report1_report->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($Report1->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$Report1_report->Page_Terminate();
?>
