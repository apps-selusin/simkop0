<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "tpinjamandetail_info.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$tpinjamandetail__list = NULL; // Initialize page object first

class ctpinjamandetail__list extends ctpinjamandetail_ {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tpinjamandetail_';

	// Page object name
	var $PageObjName = 'tpinjamandetail__list';

	// Grid form hidden field names
	var $FormName = 'ftpinjamandetail_list';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
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
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
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

		// Table object (tpinjamandetail_)
		if (!isset($GLOBALS["tpinjamandetail_"]) || get_class($GLOBALS["tpinjamandetail_"]) == "ctpinjamandetail_") {
			$GLOBALS["tpinjamandetail_"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tpinjamandetail_"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "tpinjamandetail_add.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "tpinjamandetail_delete.php";
		$this->MultiUpdateUrl = "tpinjamandetail_update.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tpinjamandetail_', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption ftpinjamandetail_listsrch";

		// List actions
		$this->ListActions = new cListActions();
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
		$this->id->SetVisibility();
		$this->berjangka->SetVisibility();
		$this->angsuran->SetVisibility();
		$this->angsuranpokok->SetVisibility();
		$this->angsuranpokokauto->SetVisibility();
		$this->angsuranbunga->SetVisibility();
		$this->angsuranbungaauto->SetVisibility();
		$this->totalangsuran->SetVisibility();
		$this->totalangsuranauto->SetVisibility();
		$this->sisaangsuran->SetVisibility();
		$this->sisaangsuranauto->SetVisibility();

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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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
		global $EW_EXPORT, $tpinjamandetail_;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($tpinjamandetail_);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Process filter list
			$this->ProcessFilterList();

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 3) {
			$this->id->setFormValue($arrKeyFlds[0]);
			$this->berjangka->setFormValue($arrKeyFlds[1]);
			if (!is_numeric($this->berjangka->FormValue))
				return FALSE;
			$this->angsuran->setFormValue($arrKeyFlds[2]);
			if (!is_numeric($this->angsuran->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = isset($UserProfile) ? $UserProfile->GetSearchFilters(CurrentUserName(), "ftpinjamandetail_listsrch") : "";
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->id->AdvancedSearch->ToJSON(), ","); // Field id
		$sFilterList = ew_Concat($sFilterList, $this->berjangka->AdvancedSearch->ToJSON(), ","); // Field berjangka
		$sFilterList = ew_Concat($sFilterList, $this->angsuran->AdvancedSearch->ToJSON(), ","); // Field angsuran
		$sFilterList = ew_Concat($sFilterList, $this->angsuranpokok->AdvancedSearch->ToJSON(), ","); // Field angsuranpokok
		$sFilterList = ew_Concat($sFilterList, $this->angsuranpokokauto->AdvancedSearch->ToJSON(), ","); // Field angsuranpokokauto
		$sFilterList = ew_Concat($sFilterList, $this->angsuranbunga->AdvancedSearch->ToJSON(), ","); // Field angsuranbunga
		$sFilterList = ew_Concat($sFilterList, $this->angsuranbungaauto->AdvancedSearch->ToJSON(), ","); // Field angsuranbungaauto
		$sFilterList = ew_Concat($sFilterList, $this->totalangsuran->AdvancedSearch->ToJSON(), ","); // Field totalangsuran
		$sFilterList = ew_Concat($sFilterList, $this->totalangsuranauto->AdvancedSearch->ToJSON(), ","); // Field totalangsuranauto
		$sFilterList = ew_Concat($sFilterList, $this->sisaangsuran->AdvancedSearch->ToJSON(), ","); // Field sisaangsuran
		$sFilterList = ew_Concat($sFilterList, $this->sisaangsuranauto->AdvancedSearch->ToJSON(), ","); // Field sisaangsuranauto
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}
		$sFilterList = preg_replace('/,$/', "", $sFilterList);

		// Return filter list in json
		if ($sFilterList <> "")
			$sFilterList = "\"data\":{" . $sFilterList . "}";
		if ($sSavedFilterList <> "") {
			if ($sFilterList <> "")
				$sFilterList .= ",";
			$sFilterList .= "\"filters\":" . $sSavedFilterList;
		}
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Process filter list
	function ProcessFilterList() {
		global $UserProfile;
		if (@$_POST["ajax"] == "savefilters") { // Save filter request (Ajax)
			$filters = ew_StripSlashes(@$_POST["filters"]);
			$UserProfile->SetSearchFilters(CurrentUserName(), "ftpinjamandetail_listsrch", $filters);

			// Clean output buffer
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			echo ew_ArrayToJson(array(array("success" => TRUE))); // Success
			$this->Page_Terminate();
			exit();
		} elseif (@$_POST["cmd"] == "resetfilter") {
			$this->RestoreFilterList();
		}
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ew_StripSlashes(@$_POST["filter"]), TRUE);
		$this->Command = "search";

		// Field id
		$this->id->AdvancedSearch->SearchValue = @$filter["x_id"];
		$this->id->AdvancedSearch->SearchOperator = @$filter["z_id"];
		$this->id->AdvancedSearch->SearchCondition = @$filter["v_id"];
		$this->id->AdvancedSearch->SearchValue2 = @$filter["y_id"];
		$this->id->AdvancedSearch->SearchOperator2 = @$filter["w_id"];
		$this->id->AdvancedSearch->Save();

		// Field berjangka
		$this->berjangka->AdvancedSearch->SearchValue = @$filter["x_berjangka"];
		$this->berjangka->AdvancedSearch->SearchOperator = @$filter["z_berjangka"];
		$this->berjangka->AdvancedSearch->SearchCondition = @$filter["v_berjangka"];
		$this->berjangka->AdvancedSearch->SearchValue2 = @$filter["y_berjangka"];
		$this->berjangka->AdvancedSearch->SearchOperator2 = @$filter["w_berjangka"];
		$this->berjangka->AdvancedSearch->Save();

		// Field angsuran
		$this->angsuran->AdvancedSearch->SearchValue = @$filter["x_angsuran"];
		$this->angsuran->AdvancedSearch->SearchOperator = @$filter["z_angsuran"];
		$this->angsuran->AdvancedSearch->SearchCondition = @$filter["v_angsuran"];
		$this->angsuran->AdvancedSearch->SearchValue2 = @$filter["y_angsuran"];
		$this->angsuran->AdvancedSearch->SearchOperator2 = @$filter["w_angsuran"];
		$this->angsuran->AdvancedSearch->Save();

		// Field angsuranpokok
		$this->angsuranpokok->AdvancedSearch->SearchValue = @$filter["x_angsuranpokok"];
		$this->angsuranpokok->AdvancedSearch->SearchOperator = @$filter["z_angsuranpokok"];
		$this->angsuranpokok->AdvancedSearch->SearchCondition = @$filter["v_angsuranpokok"];
		$this->angsuranpokok->AdvancedSearch->SearchValue2 = @$filter["y_angsuranpokok"];
		$this->angsuranpokok->AdvancedSearch->SearchOperator2 = @$filter["w_angsuranpokok"];
		$this->angsuranpokok->AdvancedSearch->Save();

		// Field angsuranpokokauto
		$this->angsuranpokokauto->AdvancedSearch->SearchValue = @$filter["x_angsuranpokokauto"];
		$this->angsuranpokokauto->AdvancedSearch->SearchOperator = @$filter["z_angsuranpokokauto"];
		$this->angsuranpokokauto->AdvancedSearch->SearchCondition = @$filter["v_angsuranpokokauto"];
		$this->angsuranpokokauto->AdvancedSearch->SearchValue2 = @$filter["y_angsuranpokokauto"];
		$this->angsuranpokokauto->AdvancedSearch->SearchOperator2 = @$filter["w_angsuranpokokauto"];
		$this->angsuranpokokauto->AdvancedSearch->Save();

		// Field angsuranbunga
		$this->angsuranbunga->AdvancedSearch->SearchValue = @$filter["x_angsuranbunga"];
		$this->angsuranbunga->AdvancedSearch->SearchOperator = @$filter["z_angsuranbunga"];
		$this->angsuranbunga->AdvancedSearch->SearchCondition = @$filter["v_angsuranbunga"];
		$this->angsuranbunga->AdvancedSearch->SearchValue2 = @$filter["y_angsuranbunga"];
		$this->angsuranbunga->AdvancedSearch->SearchOperator2 = @$filter["w_angsuranbunga"];
		$this->angsuranbunga->AdvancedSearch->Save();

		// Field angsuranbungaauto
		$this->angsuranbungaauto->AdvancedSearch->SearchValue = @$filter["x_angsuranbungaauto"];
		$this->angsuranbungaauto->AdvancedSearch->SearchOperator = @$filter["z_angsuranbungaauto"];
		$this->angsuranbungaauto->AdvancedSearch->SearchCondition = @$filter["v_angsuranbungaauto"];
		$this->angsuranbungaauto->AdvancedSearch->SearchValue2 = @$filter["y_angsuranbungaauto"];
		$this->angsuranbungaauto->AdvancedSearch->SearchOperator2 = @$filter["w_angsuranbungaauto"];
		$this->angsuranbungaauto->AdvancedSearch->Save();

		// Field totalangsuran
		$this->totalangsuran->AdvancedSearch->SearchValue = @$filter["x_totalangsuran"];
		$this->totalangsuran->AdvancedSearch->SearchOperator = @$filter["z_totalangsuran"];
		$this->totalangsuran->AdvancedSearch->SearchCondition = @$filter["v_totalangsuran"];
		$this->totalangsuran->AdvancedSearch->SearchValue2 = @$filter["y_totalangsuran"];
		$this->totalangsuran->AdvancedSearch->SearchOperator2 = @$filter["w_totalangsuran"];
		$this->totalangsuran->AdvancedSearch->Save();

		// Field totalangsuranauto
		$this->totalangsuranauto->AdvancedSearch->SearchValue = @$filter["x_totalangsuranauto"];
		$this->totalangsuranauto->AdvancedSearch->SearchOperator = @$filter["z_totalangsuranauto"];
		$this->totalangsuranauto->AdvancedSearch->SearchCondition = @$filter["v_totalangsuranauto"];
		$this->totalangsuranauto->AdvancedSearch->SearchValue2 = @$filter["y_totalangsuranauto"];
		$this->totalangsuranauto->AdvancedSearch->SearchOperator2 = @$filter["w_totalangsuranauto"];
		$this->totalangsuranauto->AdvancedSearch->Save();

		// Field sisaangsuran
		$this->sisaangsuran->AdvancedSearch->SearchValue = @$filter["x_sisaangsuran"];
		$this->sisaangsuran->AdvancedSearch->SearchOperator = @$filter["z_sisaangsuran"];
		$this->sisaangsuran->AdvancedSearch->SearchCondition = @$filter["v_sisaangsuran"];
		$this->sisaangsuran->AdvancedSearch->SearchValue2 = @$filter["y_sisaangsuran"];
		$this->sisaangsuran->AdvancedSearch->SearchOperator2 = @$filter["w_sisaangsuran"];
		$this->sisaangsuran->AdvancedSearch->Save();

		// Field sisaangsuranauto
		$this->sisaangsuranauto->AdvancedSearch->SearchValue = @$filter["x_sisaangsuranauto"];
		$this->sisaangsuranauto->AdvancedSearch->SearchOperator = @$filter["z_sisaangsuranauto"];
		$this->sisaangsuranauto->AdvancedSearch->SearchCondition = @$filter["v_sisaangsuranauto"];
		$this->sisaangsuranauto->AdvancedSearch->SearchValue2 = @$filter["y_sisaangsuranauto"];
		$this->sisaangsuranauto->AdvancedSearch->SearchOperator2 = @$filter["w_sisaangsuranauto"];
		$this->sisaangsuranauto->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->id, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSQL(&$Where, &$Fld, $arKeywords, $type) {
		global $EW_BASIC_SEARCH_IGNORE_PATTERN;
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if ($EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace($EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();

				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}

				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));

				// Search keyword in any fields
				if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
					foreach ($ar as $sKeyword) {
						if ($sKeyword <> "") {
							if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
							$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
						}
					}
				} else {
					$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id); // id
			$this->UpdateSort($this->berjangka); // berjangka
			$this->UpdateSort($this->angsuran); // angsuran
			$this->UpdateSort($this->angsuranpokok); // angsuranpokok
			$this->UpdateSort($this->angsuranpokokauto); // angsuranpokokauto
			$this->UpdateSort($this->angsuranbunga); // angsuranbunga
			$this->UpdateSort($this->angsuranbungaauto); // angsuranbungaauto
			$this->UpdateSort($this->totalangsuran); // totalangsuran
			$this->UpdateSort($this->totalangsuranauto); // totalangsuranauto
			$this->UpdateSort($this->sisaangsuran); // sisaangsuran
			$this->UpdateSort($this->sisaangsuranauto); // sisaangsuranauto
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->id->setSort("");
				$this->berjangka->setSort("");
				$this->angsuran->setSort("");
				$this->angsuranpokok->setSort("");
				$this->angsuranpokokauto->setSort("");
				$this->angsuranbunga->setSort("");
				$this->angsuranbungaauto->setSort("");
				$this->totalangsuran->setSort("");
				$this->totalangsuranauto->setSort("");
				$this->sisaangsuran->setSort("");
				$this->sisaangsuranauto->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = FALSE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		$viewcaption = ew_HtmlTitle($Language->Phrase("ViewLink"));
		if (TRUE) {
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if (TRUE) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		$copycaption = ew_HtmlTitle($Language->Phrase("CopyLink"));
		if (TRUE) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if (TRUE)
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt && $this->Export == "" && $this->CurrentAction == "") {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->berjangka->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->angsuran->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("AddLink"));
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "");
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"ftpinjamandetail_listsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"ftpinjamandetail_listsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.ftpinjamandetail_list}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"ftpinjamandetail_listsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
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

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->berjangka->DbValue = $row['berjangka'];
		$this->angsuran->DbValue = $row['angsuran'];
		$this->angsuranpokok->DbValue = $row['angsuranpokok'];
		$this->angsuranpokokauto->DbValue = $row['angsuranpokokauto'];
		$this->angsuranbunga->DbValue = $row['angsuranbunga'];
		$this->angsuranbungaauto->DbValue = $row['angsuranbungaauto'];
		$this->totalangsuran->DbValue = $row['totalangsuran'];
		$this->totalangsuranauto->DbValue = $row['totalangsuranauto'];
		$this->sisaangsuran->DbValue = $row['sisaangsuran'];
		$this->sisaangsuranauto->DbValue = $row['sisaangsuranauto'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("berjangka")) <> "")
			$this->berjangka->CurrentValue = $this->getKey("berjangka"); // berjangka
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("angsuran")) <> "")
			$this->angsuran->CurrentValue = $this->getKey("angsuran"); // angsuran
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

		// Convert decimal values if posted back
		if ($this->angsuranpokok->FormValue == $this->angsuranpokok->CurrentValue && is_numeric(ew_StrToFloat($this->angsuranpokok->CurrentValue)))
			$this->angsuranpokok->CurrentValue = ew_StrToFloat($this->angsuranpokok->CurrentValue);

		// Convert decimal values if posted back
		if ($this->angsuranpokokauto->FormValue == $this->angsuranpokokauto->CurrentValue && is_numeric(ew_StrToFloat($this->angsuranpokokauto->CurrentValue)))
			$this->angsuranpokokauto->CurrentValue = ew_StrToFloat($this->angsuranpokokauto->CurrentValue);

		// Convert decimal values if posted back
		if ($this->angsuranbunga->FormValue == $this->angsuranbunga->CurrentValue && is_numeric(ew_StrToFloat($this->angsuranbunga->CurrentValue)))
			$this->angsuranbunga->CurrentValue = ew_StrToFloat($this->angsuranbunga->CurrentValue);

		// Convert decimal values if posted back
		if ($this->angsuranbungaauto->FormValue == $this->angsuranbungaauto->CurrentValue && is_numeric(ew_StrToFloat($this->angsuranbungaauto->CurrentValue)))
			$this->angsuranbungaauto->CurrentValue = ew_StrToFloat($this->angsuranbungaauto->CurrentValue);

		// Convert decimal values if posted back
		if ($this->totalangsuran->FormValue == $this->totalangsuran->CurrentValue && is_numeric(ew_StrToFloat($this->totalangsuran->CurrentValue)))
			$this->totalangsuran->CurrentValue = ew_StrToFloat($this->totalangsuran->CurrentValue);

		// Convert decimal values if posted back
		if ($this->totalangsuranauto->FormValue == $this->totalangsuranauto->CurrentValue && is_numeric(ew_StrToFloat($this->totalangsuranauto->CurrentValue)))
			$this->totalangsuranauto->CurrentValue = ew_StrToFloat($this->totalangsuranauto->CurrentValue);

		// Convert decimal values if posted back
		if ($this->sisaangsuran->FormValue == $this->sisaangsuran->CurrentValue && is_numeric(ew_StrToFloat($this->sisaangsuran->CurrentValue)))
			$this->sisaangsuran->CurrentValue = ew_StrToFloat($this->sisaangsuran->CurrentValue);

		// Convert decimal values if posted back
		if ($this->sisaangsuranauto->FormValue == $this->sisaangsuranauto->CurrentValue && is_numeric(ew_StrToFloat($this->sisaangsuranauto->CurrentValue)))
			$this->sisaangsuranauto->CurrentValue = ew_StrToFloat($this->sisaangsuranauto->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($tpinjamandetail__list)) $tpinjamandetail__list = new ctpinjamandetail__list();

// Page init
$tpinjamandetail__list->Page_Init();

// Page main
$tpinjamandetail__list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tpinjamandetail__list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = ftpinjamandetail_list = new ew_Form("ftpinjamandetail_list", "list");
ftpinjamandetail_list.FormKeyCountName = '<?php echo $tpinjamandetail__list->FormKeyCountName ?>';

// Form_CustomValidate event
ftpinjamandetail_list.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftpinjamandetail_list.ValidateRequired = true;
<?php } else { ?>
ftpinjamandetail_list.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = ftpinjamandetail_listsrch = new ew_Form("ftpinjamandetail_listsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($tpinjamandetail__list->TotalRecs > 0 && $tpinjamandetail__list->ExportOptions->Visible()) { ?>
<?php $tpinjamandetail__list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($tpinjamandetail__list->SearchOptions->Visible()) { ?>
<?php $tpinjamandetail__list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($tpinjamandetail__list->FilterOptions->Visible()) { ?>
<?php $tpinjamandetail__list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $tpinjamandetail__list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($tpinjamandetail__list->TotalRecs <= 0)
			$tpinjamandetail__list->TotalRecs = $tpinjamandetail_->SelectRecordCount();
	} else {
		if (!$tpinjamandetail__list->Recordset && ($tpinjamandetail__list->Recordset = $tpinjamandetail__list->LoadRecordset()))
			$tpinjamandetail__list->TotalRecs = $tpinjamandetail__list->Recordset->RecordCount();
	}
	$tpinjamandetail__list->StartRec = 1;
	if ($tpinjamandetail__list->DisplayRecs <= 0 || ($tpinjamandetail_->Export <> "" && $tpinjamandetail_->ExportAll)) // Display all records
		$tpinjamandetail__list->DisplayRecs = $tpinjamandetail__list->TotalRecs;
	if (!($tpinjamandetail_->Export <> "" && $tpinjamandetail_->ExportAll))
		$tpinjamandetail__list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$tpinjamandetail__list->Recordset = $tpinjamandetail__list->LoadRecordset($tpinjamandetail__list->StartRec-1, $tpinjamandetail__list->DisplayRecs);

	// Set no record found message
	if ($tpinjamandetail_->CurrentAction == "" && $tpinjamandetail__list->TotalRecs == 0) {
		if ($tpinjamandetail__list->SearchWhere == "0=101")
			$tpinjamandetail__list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$tpinjamandetail__list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$tpinjamandetail__list->RenderOtherOptions();
?>
<?php if ($tpinjamandetail_->Export == "" && $tpinjamandetail_->CurrentAction == "") { ?>
<form name="ftpinjamandetail_listsrch" id="ftpinjamandetail_listsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($tpinjamandetail__list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="ftpinjamandetail_listsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="tpinjamandetail_">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($tpinjamandetail__list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($tpinjamandetail__list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $tpinjamandetail__list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($tpinjamandetail__list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($tpinjamandetail__list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($tpinjamandetail__list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($tpinjamandetail__list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php $tpinjamandetail__list->ShowPageHeader(); ?>
<?php
$tpinjamandetail__list->ShowMessage();
?>
<?php if ($tpinjamandetail__list->TotalRecs > 0 || $tpinjamandetail_->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid tpinjamandetail_">
<form name="ftpinjamandetail_list" id="ftpinjamandetail_list" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tpinjamandetail__list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tpinjamandetail__list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tpinjamandetail_">
<div id="gmp_tpinjamandetail_" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($tpinjamandetail__list->TotalRecs > 0 || $tpinjamandetail_->CurrentAction == "gridedit") { ?>
<table id="tbl_tpinjamandetail_list" class="table ewTable">
<?php echo $tpinjamandetail_->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$tpinjamandetail__list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$tpinjamandetail__list->RenderListOptions();

// Render list options (header, left)
$tpinjamandetail__list->ListOptions->Render("header", "left");
?>
<?php if ($tpinjamandetail_->id->Visible) { // id ?>
	<?php if ($tpinjamandetail_->SortUrl($tpinjamandetail_->id) == "") { ?>
		<th data-name="id"><div id="elh_tpinjamandetail__id" class="tpinjamandetail__id"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail_->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail_->SortUrl($tpinjamandetail_->id) ?>',1);"><div id="elh_tpinjamandetail__id" class="tpinjamandetail__id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail_->id->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail_->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail_->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail_->berjangka->Visible) { // berjangka ?>
	<?php if ($tpinjamandetail_->SortUrl($tpinjamandetail_->berjangka) == "") { ?>
		<th data-name="berjangka"><div id="elh_tpinjamandetail__berjangka" class="tpinjamandetail__berjangka"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail_->berjangka->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="berjangka"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail_->SortUrl($tpinjamandetail_->berjangka) ?>',1);"><div id="elh_tpinjamandetail__berjangka" class="tpinjamandetail__berjangka">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail_->berjangka->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail_->berjangka->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail_->berjangka->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail_->angsuran->Visible) { // angsuran ?>
	<?php if ($tpinjamandetail_->SortUrl($tpinjamandetail_->angsuran) == "") { ?>
		<th data-name="angsuran"><div id="elh_tpinjamandetail__angsuran" class="tpinjamandetail__angsuran"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail_->angsuran->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="angsuran"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail_->SortUrl($tpinjamandetail_->angsuran) ?>',1);"><div id="elh_tpinjamandetail__angsuran" class="tpinjamandetail__angsuran">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail_->angsuran->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail_->angsuran->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail_->angsuran->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail_->angsuranpokok->Visible) { // angsuranpokok ?>
	<?php if ($tpinjamandetail_->SortUrl($tpinjamandetail_->angsuranpokok) == "") { ?>
		<th data-name="angsuranpokok"><div id="elh_tpinjamandetail__angsuranpokok" class="tpinjamandetail__angsuranpokok"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail_->angsuranpokok->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="angsuranpokok"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail_->SortUrl($tpinjamandetail_->angsuranpokok) ?>',1);"><div id="elh_tpinjamandetail__angsuranpokok" class="tpinjamandetail__angsuranpokok">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail_->angsuranpokok->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail_->angsuranpokok->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail_->angsuranpokok->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail_->angsuranpokokauto->Visible) { // angsuranpokokauto ?>
	<?php if ($tpinjamandetail_->SortUrl($tpinjamandetail_->angsuranpokokauto) == "") { ?>
		<th data-name="angsuranpokokauto"><div id="elh_tpinjamandetail__angsuranpokokauto" class="tpinjamandetail__angsuranpokokauto"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail_->angsuranpokokauto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="angsuranpokokauto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail_->SortUrl($tpinjamandetail_->angsuranpokokauto) ?>',1);"><div id="elh_tpinjamandetail__angsuranpokokauto" class="tpinjamandetail__angsuranpokokauto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail_->angsuranpokokauto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail_->angsuranpokokauto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail_->angsuranpokokauto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail_->angsuranbunga->Visible) { // angsuranbunga ?>
	<?php if ($tpinjamandetail_->SortUrl($tpinjamandetail_->angsuranbunga) == "") { ?>
		<th data-name="angsuranbunga"><div id="elh_tpinjamandetail__angsuranbunga" class="tpinjamandetail__angsuranbunga"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail_->angsuranbunga->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="angsuranbunga"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail_->SortUrl($tpinjamandetail_->angsuranbunga) ?>',1);"><div id="elh_tpinjamandetail__angsuranbunga" class="tpinjamandetail__angsuranbunga">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail_->angsuranbunga->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail_->angsuranbunga->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail_->angsuranbunga->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail_->angsuranbungaauto->Visible) { // angsuranbungaauto ?>
	<?php if ($tpinjamandetail_->SortUrl($tpinjamandetail_->angsuranbungaauto) == "") { ?>
		<th data-name="angsuranbungaauto"><div id="elh_tpinjamandetail__angsuranbungaauto" class="tpinjamandetail__angsuranbungaauto"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail_->angsuranbungaauto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="angsuranbungaauto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail_->SortUrl($tpinjamandetail_->angsuranbungaauto) ?>',1);"><div id="elh_tpinjamandetail__angsuranbungaauto" class="tpinjamandetail__angsuranbungaauto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail_->angsuranbungaauto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail_->angsuranbungaauto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail_->angsuranbungaauto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail_->totalangsuran->Visible) { // totalangsuran ?>
	<?php if ($tpinjamandetail_->SortUrl($tpinjamandetail_->totalangsuran) == "") { ?>
		<th data-name="totalangsuran"><div id="elh_tpinjamandetail__totalangsuran" class="tpinjamandetail__totalangsuran"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail_->totalangsuran->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="totalangsuran"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail_->SortUrl($tpinjamandetail_->totalangsuran) ?>',1);"><div id="elh_tpinjamandetail__totalangsuran" class="tpinjamandetail__totalangsuran">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail_->totalangsuran->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail_->totalangsuran->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail_->totalangsuran->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail_->totalangsuranauto->Visible) { // totalangsuranauto ?>
	<?php if ($tpinjamandetail_->SortUrl($tpinjamandetail_->totalangsuranauto) == "") { ?>
		<th data-name="totalangsuranauto"><div id="elh_tpinjamandetail__totalangsuranauto" class="tpinjamandetail__totalangsuranauto"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail_->totalangsuranauto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="totalangsuranauto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail_->SortUrl($tpinjamandetail_->totalangsuranauto) ?>',1);"><div id="elh_tpinjamandetail__totalangsuranauto" class="tpinjamandetail__totalangsuranauto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail_->totalangsuranauto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail_->totalangsuranauto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail_->totalangsuranauto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail_->sisaangsuran->Visible) { // sisaangsuran ?>
	<?php if ($tpinjamandetail_->SortUrl($tpinjamandetail_->sisaangsuran) == "") { ?>
		<th data-name="sisaangsuran"><div id="elh_tpinjamandetail__sisaangsuran" class="tpinjamandetail__sisaangsuran"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail_->sisaangsuran->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="sisaangsuran"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail_->SortUrl($tpinjamandetail_->sisaangsuran) ?>',1);"><div id="elh_tpinjamandetail__sisaangsuran" class="tpinjamandetail__sisaangsuran">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail_->sisaangsuran->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail_->sisaangsuran->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail_->sisaangsuran->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail_->sisaangsuranauto->Visible) { // sisaangsuranauto ?>
	<?php if ($tpinjamandetail_->SortUrl($tpinjamandetail_->sisaangsuranauto) == "") { ?>
		<th data-name="sisaangsuranauto"><div id="elh_tpinjamandetail__sisaangsuranauto" class="tpinjamandetail__sisaangsuranauto"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail_->sisaangsuranauto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="sisaangsuranauto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail_->SortUrl($tpinjamandetail_->sisaangsuranauto) ?>',1);"><div id="elh_tpinjamandetail__sisaangsuranauto" class="tpinjamandetail__sisaangsuranauto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail_->sisaangsuranauto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail_->sisaangsuranauto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail_->sisaangsuranauto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$tpinjamandetail__list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($tpinjamandetail_->ExportAll && $tpinjamandetail_->Export <> "") {
	$tpinjamandetail__list->StopRec = $tpinjamandetail__list->TotalRecs;
} else {

	// Set the last record to display
	if ($tpinjamandetail__list->TotalRecs > $tpinjamandetail__list->StartRec + $tpinjamandetail__list->DisplayRecs - 1)
		$tpinjamandetail__list->StopRec = $tpinjamandetail__list->StartRec + $tpinjamandetail__list->DisplayRecs - 1;
	else
		$tpinjamandetail__list->StopRec = $tpinjamandetail__list->TotalRecs;
}
$tpinjamandetail__list->RecCnt = $tpinjamandetail__list->StartRec - 1;
if ($tpinjamandetail__list->Recordset && !$tpinjamandetail__list->Recordset->EOF) {
	$tpinjamandetail__list->Recordset->MoveFirst();
	$bSelectLimit = $tpinjamandetail__list->UseSelectLimit;
	if (!$bSelectLimit && $tpinjamandetail__list->StartRec > 1)
		$tpinjamandetail__list->Recordset->Move($tpinjamandetail__list->StartRec - 1);
} elseif (!$tpinjamandetail_->AllowAddDeleteRow && $tpinjamandetail__list->StopRec == 0) {
	$tpinjamandetail__list->StopRec = $tpinjamandetail_->GridAddRowCount;
}

// Initialize aggregate
$tpinjamandetail_->RowType = EW_ROWTYPE_AGGREGATEINIT;
$tpinjamandetail_->ResetAttrs();
$tpinjamandetail__list->RenderRow();
while ($tpinjamandetail__list->RecCnt < $tpinjamandetail__list->StopRec) {
	$tpinjamandetail__list->RecCnt++;
	if (intval($tpinjamandetail__list->RecCnt) >= intval($tpinjamandetail__list->StartRec)) {
		$tpinjamandetail__list->RowCnt++;

		// Set up key count
		$tpinjamandetail__list->KeyCount = $tpinjamandetail__list->RowIndex;

		// Init row class and style
		$tpinjamandetail_->ResetAttrs();
		$tpinjamandetail_->CssClass = "";
		if ($tpinjamandetail_->CurrentAction == "gridadd") {
		} else {
			$tpinjamandetail__list->LoadRowValues($tpinjamandetail__list->Recordset); // Load row values
		}
		$tpinjamandetail_->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$tpinjamandetail_->RowAttrs = array_merge($tpinjamandetail_->RowAttrs, array('data-rowindex'=>$tpinjamandetail__list->RowCnt, 'id'=>'r' . $tpinjamandetail__list->RowCnt . '_tpinjamandetail_', 'data-rowtype'=>$tpinjamandetail_->RowType));

		// Render row
		$tpinjamandetail__list->RenderRow();

		// Render list options
		$tpinjamandetail__list->RenderListOptions();
?>
	<tr<?php echo $tpinjamandetail_->RowAttributes() ?>>
<?php

// Render list options (body, left)
$tpinjamandetail__list->ListOptions->Render("body", "left", $tpinjamandetail__list->RowCnt);
?>
	<?php if ($tpinjamandetail_->id->Visible) { // id ?>
		<td data-name="id"<?php echo $tpinjamandetail_->id->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail__list->RowCnt ?>_tpinjamandetail__id" class="tpinjamandetail__id">
<span<?php echo $tpinjamandetail_->id->ViewAttributes() ?>>
<?php echo $tpinjamandetail_->id->ListViewValue() ?></span>
</span>
<a id="<?php echo $tpinjamandetail__list->PageObjName . "_row_" . $tpinjamandetail__list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($tpinjamandetail_->berjangka->Visible) { // berjangka ?>
		<td data-name="berjangka"<?php echo $tpinjamandetail_->berjangka->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail__list->RowCnt ?>_tpinjamandetail__berjangka" class="tpinjamandetail__berjangka">
<span<?php echo $tpinjamandetail_->berjangka->ViewAttributes() ?>>
<?php echo $tpinjamandetail_->berjangka->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail_->angsuran->Visible) { // angsuran ?>
		<td data-name="angsuran"<?php echo $tpinjamandetail_->angsuran->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail__list->RowCnt ?>_tpinjamandetail__angsuran" class="tpinjamandetail__angsuran">
<span<?php echo $tpinjamandetail_->angsuran->ViewAttributes() ?>>
<?php echo $tpinjamandetail_->angsuran->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail_->angsuranpokok->Visible) { // angsuranpokok ?>
		<td data-name="angsuranpokok"<?php echo $tpinjamandetail_->angsuranpokok->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail__list->RowCnt ?>_tpinjamandetail__angsuranpokok" class="tpinjamandetail__angsuranpokok">
<span<?php echo $tpinjamandetail_->angsuranpokok->ViewAttributes() ?>>
<?php echo $tpinjamandetail_->angsuranpokok->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail_->angsuranpokokauto->Visible) { // angsuranpokokauto ?>
		<td data-name="angsuranpokokauto"<?php echo $tpinjamandetail_->angsuranpokokauto->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail__list->RowCnt ?>_tpinjamandetail__angsuranpokokauto" class="tpinjamandetail__angsuranpokokauto">
<span<?php echo $tpinjamandetail_->angsuranpokokauto->ViewAttributes() ?>>
<?php echo $tpinjamandetail_->angsuranpokokauto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail_->angsuranbunga->Visible) { // angsuranbunga ?>
		<td data-name="angsuranbunga"<?php echo $tpinjamandetail_->angsuranbunga->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail__list->RowCnt ?>_tpinjamandetail__angsuranbunga" class="tpinjamandetail__angsuranbunga">
<span<?php echo $tpinjamandetail_->angsuranbunga->ViewAttributes() ?>>
<?php echo $tpinjamandetail_->angsuranbunga->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail_->angsuranbungaauto->Visible) { // angsuranbungaauto ?>
		<td data-name="angsuranbungaauto"<?php echo $tpinjamandetail_->angsuranbungaauto->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail__list->RowCnt ?>_tpinjamandetail__angsuranbungaauto" class="tpinjamandetail__angsuranbungaauto">
<span<?php echo $tpinjamandetail_->angsuranbungaauto->ViewAttributes() ?>>
<?php echo $tpinjamandetail_->angsuranbungaauto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail_->totalangsuran->Visible) { // totalangsuran ?>
		<td data-name="totalangsuran"<?php echo $tpinjamandetail_->totalangsuran->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail__list->RowCnt ?>_tpinjamandetail__totalangsuran" class="tpinjamandetail__totalangsuran">
<span<?php echo $tpinjamandetail_->totalangsuran->ViewAttributes() ?>>
<?php echo $tpinjamandetail_->totalangsuran->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail_->totalangsuranauto->Visible) { // totalangsuranauto ?>
		<td data-name="totalangsuranauto"<?php echo $tpinjamandetail_->totalangsuranauto->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail__list->RowCnt ?>_tpinjamandetail__totalangsuranauto" class="tpinjamandetail__totalangsuranauto">
<span<?php echo $tpinjamandetail_->totalangsuranauto->ViewAttributes() ?>>
<?php echo $tpinjamandetail_->totalangsuranauto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail_->sisaangsuran->Visible) { // sisaangsuran ?>
		<td data-name="sisaangsuran"<?php echo $tpinjamandetail_->sisaangsuran->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail__list->RowCnt ?>_tpinjamandetail__sisaangsuran" class="tpinjamandetail__sisaangsuran">
<span<?php echo $tpinjamandetail_->sisaangsuran->ViewAttributes() ?>>
<?php echo $tpinjamandetail_->sisaangsuran->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail_->sisaangsuranauto->Visible) { // sisaangsuranauto ?>
		<td data-name="sisaangsuranauto"<?php echo $tpinjamandetail_->sisaangsuranauto->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail__list->RowCnt ?>_tpinjamandetail__sisaangsuranauto" class="tpinjamandetail__sisaangsuranauto">
<span<?php echo $tpinjamandetail_->sisaangsuranauto->ViewAttributes() ?>>
<?php echo $tpinjamandetail_->sisaangsuranauto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$tpinjamandetail__list->ListOptions->Render("body", "right", $tpinjamandetail__list->RowCnt);
?>
	</tr>
<?php
	}
	if ($tpinjamandetail_->CurrentAction <> "gridadd")
		$tpinjamandetail__list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($tpinjamandetail_->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($tpinjamandetail__list->Recordset)
	$tpinjamandetail__list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($tpinjamandetail_->CurrentAction <> "gridadd" && $tpinjamandetail_->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($tpinjamandetail__list->Pager)) $tpinjamandetail__list->Pager = new cPrevNextPager($tpinjamandetail__list->StartRec, $tpinjamandetail__list->DisplayRecs, $tpinjamandetail__list->TotalRecs) ?>
<?php if ($tpinjamandetail__list->Pager->RecordCount > 0 && $tpinjamandetail__list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($tpinjamandetail__list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $tpinjamandetail__list->PageUrl() ?>start=<?php echo $tpinjamandetail__list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($tpinjamandetail__list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $tpinjamandetail__list->PageUrl() ?>start=<?php echo $tpinjamandetail__list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $tpinjamandetail__list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($tpinjamandetail__list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $tpinjamandetail__list->PageUrl() ?>start=<?php echo $tpinjamandetail__list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($tpinjamandetail__list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $tpinjamandetail__list->PageUrl() ?>start=<?php echo $tpinjamandetail__list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $tpinjamandetail__list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $tpinjamandetail__list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $tpinjamandetail__list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $tpinjamandetail__list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($tpinjamandetail__list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($tpinjamandetail__list->TotalRecs == 0 && $tpinjamandetail_->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($tpinjamandetail__list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
ftpinjamandetail_listsrch.FilterList = <?php echo $tpinjamandetail__list->GetFilterList() ?>;
ftpinjamandetail_listsrch.Init();
ftpinjamandetail_list.Init();
</script>
<?php
$tpinjamandetail__list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tpinjamandetail__list->Page_Terminate();
?>
