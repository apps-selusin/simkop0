<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "tjurnalkasbankinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$tjurnalkasbank_list = NULL; // Initialize page object first

class ctjurnalkasbank_list extends ctjurnalkasbank {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tjurnalkasbank';

	// Page object name
	var $PageObjName = 'tjurnalkasbank_list';

	// Grid form hidden field names
	var $FormName = 'ftjurnalkasbanklist';
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

		// Table object (tjurnalkasbank)
		if (!isset($GLOBALS["tjurnalkasbank"]) || get_class($GLOBALS["tjurnalkasbank"]) == "ctjurnalkasbank") {
			$GLOBALS["tjurnalkasbank"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tjurnalkasbank"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "tjurnalkasbankadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "tjurnalkasbankdelete.php";
		$this->MultiUpdateUrl = "tjurnalkasbankupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tjurnalkasbank', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption ftjurnalkasbanklistsrch";

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
		$this->tanggal->SetVisibility();
		$this->periode->SetVisibility();
		$this->id->SetVisibility();
		$this->nomor->SetVisibility();
		$this->transaksi->SetVisibility();
		$this->referensi->SetVisibility();
		$this->model->SetVisibility();
		$this->rekening->SetVisibility();
		$this->debet->SetVisibility();
		$this->credit->SetVisibility();
		$this->kantor->SetVisibility();
		$this->keterangan->SetVisibility();
		$this->active->SetVisibility();
		$this->ip->SetVisibility();
		$this->status->SetVisibility();
		$this->user->SetVisibility();
		$this->created->SetVisibility();
		$this->modified->SetVisibility();

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
		global $EW_EXPORT, $tjurnalkasbank;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($tjurnalkasbank);
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
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values

			// Process filter list
			$this->ProcessFilterList();
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

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

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
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

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
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
		if (count($arrKeyFlds) >= 2) {
			$this->id->setFormValue($arrKeyFlds[0]);
			$this->nomor->setFormValue($arrKeyFlds[1]);
			if (!is_numeric($this->nomor->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = isset($UserProfile) ? $UserProfile->GetSearchFilters(CurrentUserName(), "ftjurnalkasbanklistsrch") : "";
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->tanggal->AdvancedSearch->ToJSON(), ","); // Field tanggal
		$sFilterList = ew_Concat($sFilterList, $this->periode->AdvancedSearch->ToJSON(), ","); // Field periode
		$sFilterList = ew_Concat($sFilterList, $this->id->AdvancedSearch->ToJSON(), ","); // Field id
		$sFilterList = ew_Concat($sFilterList, $this->nomor->AdvancedSearch->ToJSON(), ","); // Field nomor
		$sFilterList = ew_Concat($sFilterList, $this->transaksi->AdvancedSearch->ToJSON(), ","); // Field transaksi
		$sFilterList = ew_Concat($sFilterList, $this->referensi->AdvancedSearch->ToJSON(), ","); // Field referensi
		$sFilterList = ew_Concat($sFilterList, $this->model->AdvancedSearch->ToJSON(), ","); // Field model
		$sFilterList = ew_Concat($sFilterList, $this->rekening->AdvancedSearch->ToJSON(), ","); // Field rekening
		$sFilterList = ew_Concat($sFilterList, $this->debet->AdvancedSearch->ToJSON(), ","); // Field debet
		$sFilterList = ew_Concat($sFilterList, $this->credit->AdvancedSearch->ToJSON(), ","); // Field credit
		$sFilterList = ew_Concat($sFilterList, $this->kantor->AdvancedSearch->ToJSON(), ","); // Field kantor
		$sFilterList = ew_Concat($sFilterList, $this->keterangan->AdvancedSearch->ToJSON(), ","); // Field keterangan
		$sFilterList = ew_Concat($sFilterList, $this->active->AdvancedSearch->ToJSON(), ","); // Field active
		$sFilterList = ew_Concat($sFilterList, $this->ip->AdvancedSearch->ToJSON(), ","); // Field ip
		$sFilterList = ew_Concat($sFilterList, $this->status->AdvancedSearch->ToJSON(), ","); // Field status
		$sFilterList = ew_Concat($sFilterList, $this->user->AdvancedSearch->ToJSON(), ","); // Field user
		$sFilterList = ew_Concat($sFilterList, $this->created->AdvancedSearch->ToJSON(), ","); // Field created
		$sFilterList = ew_Concat($sFilterList, $this->modified->AdvancedSearch->ToJSON(), ","); // Field modified
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "ftjurnalkasbanklistsrch", $filters);

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

		// Field tanggal
		$this->tanggal->AdvancedSearch->SearchValue = @$filter["x_tanggal"];
		$this->tanggal->AdvancedSearch->SearchOperator = @$filter["z_tanggal"];
		$this->tanggal->AdvancedSearch->SearchCondition = @$filter["v_tanggal"];
		$this->tanggal->AdvancedSearch->SearchValue2 = @$filter["y_tanggal"];
		$this->tanggal->AdvancedSearch->SearchOperator2 = @$filter["w_tanggal"];
		$this->tanggal->AdvancedSearch->Save();

		// Field periode
		$this->periode->AdvancedSearch->SearchValue = @$filter["x_periode"];
		$this->periode->AdvancedSearch->SearchOperator = @$filter["z_periode"];
		$this->periode->AdvancedSearch->SearchCondition = @$filter["v_periode"];
		$this->periode->AdvancedSearch->SearchValue2 = @$filter["y_periode"];
		$this->periode->AdvancedSearch->SearchOperator2 = @$filter["w_periode"];
		$this->periode->AdvancedSearch->Save();

		// Field id
		$this->id->AdvancedSearch->SearchValue = @$filter["x_id"];
		$this->id->AdvancedSearch->SearchOperator = @$filter["z_id"];
		$this->id->AdvancedSearch->SearchCondition = @$filter["v_id"];
		$this->id->AdvancedSearch->SearchValue2 = @$filter["y_id"];
		$this->id->AdvancedSearch->SearchOperator2 = @$filter["w_id"];
		$this->id->AdvancedSearch->Save();

		// Field nomor
		$this->nomor->AdvancedSearch->SearchValue = @$filter["x_nomor"];
		$this->nomor->AdvancedSearch->SearchOperator = @$filter["z_nomor"];
		$this->nomor->AdvancedSearch->SearchCondition = @$filter["v_nomor"];
		$this->nomor->AdvancedSearch->SearchValue2 = @$filter["y_nomor"];
		$this->nomor->AdvancedSearch->SearchOperator2 = @$filter["w_nomor"];
		$this->nomor->AdvancedSearch->Save();

		// Field transaksi
		$this->transaksi->AdvancedSearch->SearchValue = @$filter["x_transaksi"];
		$this->transaksi->AdvancedSearch->SearchOperator = @$filter["z_transaksi"];
		$this->transaksi->AdvancedSearch->SearchCondition = @$filter["v_transaksi"];
		$this->transaksi->AdvancedSearch->SearchValue2 = @$filter["y_transaksi"];
		$this->transaksi->AdvancedSearch->SearchOperator2 = @$filter["w_transaksi"];
		$this->transaksi->AdvancedSearch->Save();

		// Field referensi
		$this->referensi->AdvancedSearch->SearchValue = @$filter["x_referensi"];
		$this->referensi->AdvancedSearch->SearchOperator = @$filter["z_referensi"];
		$this->referensi->AdvancedSearch->SearchCondition = @$filter["v_referensi"];
		$this->referensi->AdvancedSearch->SearchValue2 = @$filter["y_referensi"];
		$this->referensi->AdvancedSearch->SearchOperator2 = @$filter["w_referensi"];
		$this->referensi->AdvancedSearch->Save();

		// Field model
		$this->model->AdvancedSearch->SearchValue = @$filter["x_model"];
		$this->model->AdvancedSearch->SearchOperator = @$filter["z_model"];
		$this->model->AdvancedSearch->SearchCondition = @$filter["v_model"];
		$this->model->AdvancedSearch->SearchValue2 = @$filter["y_model"];
		$this->model->AdvancedSearch->SearchOperator2 = @$filter["w_model"];
		$this->model->AdvancedSearch->Save();

		// Field rekening
		$this->rekening->AdvancedSearch->SearchValue = @$filter["x_rekening"];
		$this->rekening->AdvancedSearch->SearchOperator = @$filter["z_rekening"];
		$this->rekening->AdvancedSearch->SearchCondition = @$filter["v_rekening"];
		$this->rekening->AdvancedSearch->SearchValue2 = @$filter["y_rekening"];
		$this->rekening->AdvancedSearch->SearchOperator2 = @$filter["w_rekening"];
		$this->rekening->AdvancedSearch->Save();

		// Field debet
		$this->debet->AdvancedSearch->SearchValue = @$filter["x_debet"];
		$this->debet->AdvancedSearch->SearchOperator = @$filter["z_debet"];
		$this->debet->AdvancedSearch->SearchCondition = @$filter["v_debet"];
		$this->debet->AdvancedSearch->SearchValue2 = @$filter["y_debet"];
		$this->debet->AdvancedSearch->SearchOperator2 = @$filter["w_debet"];
		$this->debet->AdvancedSearch->Save();

		// Field credit
		$this->credit->AdvancedSearch->SearchValue = @$filter["x_credit"];
		$this->credit->AdvancedSearch->SearchOperator = @$filter["z_credit"];
		$this->credit->AdvancedSearch->SearchCondition = @$filter["v_credit"];
		$this->credit->AdvancedSearch->SearchValue2 = @$filter["y_credit"];
		$this->credit->AdvancedSearch->SearchOperator2 = @$filter["w_credit"];
		$this->credit->AdvancedSearch->Save();

		// Field kantor
		$this->kantor->AdvancedSearch->SearchValue = @$filter["x_kantor"];
		$this->kantor->AdvancedSearch->SearchOperator = @$filter["z_kantor"];
		$this->kantor->AdvancedSearch->SearchCondition = @$filter["v_kantor"];
		$this->kantor->AdvancedSearch->SearchValue2 = @$filter["y_kantor"];
		$this->kantor->AdvancedSearch->SearchOperator2 = @$filter["w_kantor"];
		$this->kantor->AdvancedSearch->Save();

		// Field keterangan
		$this->keterangan->AdvancedSearch->SearchValue = @$filter["x_keterangan"];
		$this->keterangan->AdvancedSearch->SearchOperator = @$filter["z_keterangan"];
		$this->keterangan->AdvancedSearch->SearchCondition = @$filter["v_keterangan"];
		$this->keterangan->AdvancedSearch->SearchValue2 = @$filter["y_keterangan"];
		$this->keterangan->AdvancedSearch->SearchOperator2 = @$filter["w_keterangan"];
		$this->keterangan->AdvancedSearch->Save();

		// Field active
		$this->active->AdvancedSearch->SearchValue = @$filter["x_active"];
		$this->active->AdvancedSearch->SearchOperator = @$filter["z_active"];
		$this->active->AdvancedSearch->SearchCondition = @$filter["v_active"];
		$this->active->AdvancedSearch->SearchValue2 = @$filter["y_active"];
		$this->active->AdvancedSearch->SearchOperator2 = @$filter["w_active"];
		$this->active->AdvancedSearch->Save();

		// Field ip
		$this->ip->AdvancedSearch->SearchValue = @$filter["x_ip"];
		$this->ip->AdvancedSearch->SearchOperator = @$filter["z_ip"];
		$this->ip->AdvancedSearch->SearchCondition = @$filter["v_ip"];
		$this->ip->AdvancedSearch->SearchValue2 = @$filter["y_ip"];
		$this->ip->AdvancedSearch->SearchOperator2 = @$filter["w_ip"];
		$this->ip->AdvancedSearch->Save();

		// Field status
		$this->status->AdvancedSearch->SearchValue = @$filter["x_status"];
		$this->status->AdvancedSearch->SearchOperator = @$filter["z_status"];
		$this->status->AdvancedSearch->SearchCondition = @$filter["v_status"];
		$this->status->AdvancedSearch->SearchValue2 = @$filter["y_status"];
		$this->status->AdvancedSearch->SearchOperator2 = @$filter["w_status"];
		$this->status->AdvancedSearch->Save();

		// Field user
		$this->user->AdvancedSearch->SearchValue = @$filter["x_user"];
		$this->user->AdvancedSearch->SearchOperator = @$filter["z_user"];
		$this->user->AdvancedSearch->SearchCondition = @$filter["v_user"];
		$this->user->AdvancedSearch->SearchValue2 = @$filter["y_user"];
		$this->user->AdvancedSearch->SearchOperator2 = @$filter["w_user"];
		$this->user->AdvancedSearch->Save();

		// Field created
		$this->created->AdvancedSearch->SearchValue = @$filter["x_created"];
		$this->created->AdvancedSearch->SearchOperator = @$filter["z_created"];
		$this->created->AdvancedSearch->SearchCondition = @$filter["v_created"];
		$this->created->AdvancedSearch->SearchValue2 = @$filter["y_created"];
		$this->created->AdvancedSearch->SearchOperator2 = @$filter["w_created"];
		$this->created->AdvancedSearch->Save();

		// Field modified
		$this->modified->AdvancedSearch->SearchValue = @$filter["x_modified"];
		$this->modified->AdvancedSearch->SearchOperator = @$filter["z_modified"];
		$this->modified->AdvancedSearch->SearchCondition = @$filter["v_modified"];
		$this->modified->AdvancedSearch->SearchValue2 = @$filter["y_modified"];
		$this->modified->AdvancedSearch->SearchOperator2 = @$filter["w_modified"];
		$this->modified->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		$this->BuildSearchSql($sWhere, $this->tanggal, $Default, FALSE); // tanggal
		$this->BuildSearchSql($sWhere, $this->periode, $Default, FALSE); // periode
		$this->BuildSearchSql($sWhere, $this->id, $Default, FALSE); // id
		$this->BuildSearchSql($sWhere, $this->nomor, $Default, FALSE); // nomor
		$this->BuildSearchSql($sWhere, $this->transaksi, $Default, FALSE); // transaksi
		$this->BuildSearchSql($sWhere, $this->referensi, $Default, FALSE); // referensi
		$this->BuildSearchSql($sWhere, $this->model, $Default, FALSE); // model
		$this->BuildSearchSql($sWhere, $this->rekening, $Default, FALSE); // rekening
		$this->BuildSearchSql($sWhere, $this->debet, $Default, FALSE); // debet
		$this->BuildSearchSql($sWhere, $this->credit, $Default, FALSE); // credit
		$this->BuildSearchSql($sWhere, $this->kantor, $Default, FALSE); // kantor
		$this->BuildSearchSql($sWhere, $this->keterangan, $Default, FALSE); // keterangan
		$this->BuildSearchSql($sWhere, $this->active, $Default, FALSE); // active
		$this->BuildSearchSql($sWhere, $this->ip, $Default, FALSE); // ip
		$this->BuildSearchSql($sWhere, $this->status, $Default, FALSE); // status
		$this->BuildSearchSql($sWhere, $this->user, $Default, FALSE); // user
		$this->BuildSearchSql($sWhere, $this->created, $Default, FALSE); // created
		$this->BuildSearchSql($sWhere, $this->modified, $Default, FALSE); // modified

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->tanggal->AdvancedSearch->Save(); // tanggal
			$this->periode->AdvancedSearch->Save(); // periode
			$this->id->AdvancedSearch->Save(); // id
			$this->nomor->AdvancedSearch->Save(); // nomor
			$this->transaksi->AdvancedSearch->Save(); // transaksi
			$this->referensi->AdvancedSearch->Save(); // referensi
			$this->model->AdvancedSearch->Save(); // model
			$this->rekening->AdvancedSearch->Save(); // rekening
			$this->debet->AdvancedSearch->Save(); // debet
			$this->credit->AdvancedSearch->Save(); // credit
			$this->kantor->AdvancedSearch->Save(); // kantor
			$this->keterangan->AdvancedSearch->Save(); // keterangan
			$this->active->AdvancedSearch->Save(); // active
			$this->ip->AdvancedSearch->Save(); // ip
			$this->status->AdvancedSearch->Save(); // status
			$this->user->AdvancedSearch->Save(); // user
			$this->created->AdvancedSearch->Save(); // created
			$this->modified->AdvancedSearch->Save(); // modified
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $Default, $MultiValue) {
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = ($Default) ? $Fld->AdvancedSearch->SearchValueDefault : $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = ($Default) ? $Fld->AdvancedSearch->SearchOperatorDefault : $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = ($Default) ? $Fld->AdvancedSearch->SearchConditionDefault : $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = ($Default) ? $Fld->AdvancedSearch->SearchValue2Default : $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = ($Default) ? $Fld->AdvancedSearch->SearchOperator2Default : $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";

		//$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);

		//$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1)
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal, $this->DBID) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2, $this->DBID) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2, $this->DBID);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE || $Fld->FldDataType == EW_DATATYPE_TIME) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->periode, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->id, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->transaksi, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->referensi, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->model, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->rekening, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->kantor, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->keterangan, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->ip, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->status, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->user, $arKeywords, $type);
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
		if ($this->tanggal->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->periode->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nomor->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->transaksi->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->referensi->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->model->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->rekening->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->debet->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->credit->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->kantor->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->keterangan->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->active->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ip->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->status->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->user->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->created->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->modified->AdvancedSearch->IssetSession())
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

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->tanggal->AdvancedSearch->UnsetSession();
		$this->periode->AdvancedSearch->UnsetSession();
		$this->id->AdvancedSearch->UnsetSession();
		$this->nomor->AdvancedSearch->UnsetSession();
		$this->transaksi->AdvancedSearch->UnsetSession();
		$this->referensi->AdvancedSearch->UnsetSession();
		$this->model->AdvancedSearch->UnsetSession();
		$this->rekening->AdvancedSearch->UnsetSession();
		$this->debet->AdvancedSearch->UnsetSession();
		$this->credit->AdvancedSearch->UnsetSession();
		$this->kantor->AdvancedSearch->UnsetSession();
		$this->keterangan->AdvancedSearch->UnsetSession();
		$this->active->AdvancedSearch->UnsetSession();
		$this->ip->AdvancedSearch->UnsetSession();
		$this->status->AdvancedSearch->UnsetSession();
		$this->user->AdvancedSearch->UnsetSession();
		$this->created->AdvancedSearch->UnsetSession();
		$this->modified->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->tanggal->AdvancedSearch->Load();
		$this->periode->AdvancedSearch->Load();
		$this->id->AdvancedSearch->Load();
		$this->nomor->AdvancedSearch->Load();
		$this->transaksi->AdvancedSearch->Load();
		$this->referensi->AdvancedSearch->Load();
		$this->model->AdvancedSearch->Load();
		$this->rekening->AdvancedSearch->Load();
		$this->debet->AdvancedSearch->Load();
		$this->credit->AdvancedSearch->Load();
		$this->kantor->AdvancedSearch->Load();
		$this->keterangan->AdvancedSearch->Load();
		$this->active->AdvancedSearch->Load();
		$this->ip->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
		$this->user->AdvancedSearch->Load();
		$this->created->AdvancedSearch->Load();
		$this->modified->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->tanggal); // tanggal
			$this->UpdateSort($this->periode); // periode
			$this->UpdateSort($this->id); // id
			$this->UpdateSort($this->nomor); // nomor
			$this->UpdateSort($this->transaksi); // transaksi
			$this->UpdateSort($this->referensi); // referensi
			$this->UpdateSort($this->model); // model
			$this->UpdateSort($this->rekening); // rekening
			$this->UpdateSort($this->debet); // debet
			$this->UpdateSort($this->credit); // credit
			$this->UpdateSort($this->kantor); // kantor
			$this->UpdateSort($this->keterangan); // keterangan
			$this->UpdateSort($this->active); // active
			$this->UpdateSort($this->ip); // ip
			$this->UpdateSort($this->status); // status
			$this->UpdateSort($this->user); // user
			$this->UpdateSort($this->created); // created
			$this->UpdateSort($this->modified); // modified
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
				$this->tanggal->setSort("");
				$this->periode->setSort("");
				$this->id->setSort("");
				$this->nomor->setSort("");
				$this->transaksi->setSort("");
				$this->referensi->setSort("");
				$this->model->setSort("");
				$this->rekening->setSort("");
				$this->debet->setSort("");
				$this->credit->setSort("");
				$this->kantor->setSort("");
				$this->keterangan->setSort("");
				$this->active->setSort("");
				$this->ip->setSort("");
				$this->status->setSort("");
				$this->user->setSort("");
				$this->created->setSort("");
				$this->modified->setSort("");
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->nomor->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"ftjurnalkasbanklistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"ftjurnalkasbanklistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.ftjurnalkasbanklist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"ftjurnalkasbanklistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// tanggal

		$this->tanggal->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_tanggal"]);
		if ($this->tanggal->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->tanggal->AdvancedSearch->SearchOperator = @$_GET["z_tanggal"];

		// periode
		$this->periode->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_periode"]);
		if ($this->periode->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->periode->AdvancedSearch->SearchOperator = @$_GET["z_periode"];

		// id
		$this->id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id"]);
		if ($this->id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id->AdvancedSearch->SearchOperator = @$_GET["z_id"];

		// nomor
		$this->nomor->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nomor"]);
		if ($this->nomor->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nomor->AdvancedSearch->SearchOperator = @$_GET["z_nomor"];

		// transaksi
		$this->transaksi->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_transaksi"]);
		if ($this->transaksi->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->transaksi->AdvancedSearch->SearchOperator = @$_GET["z_transaksi"];

		// referensi
		$this->referensi->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_referensi"]);
		if ($this->referensi->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->referensi->AdvancedSearch->SearchOperator = @$_GET["z_referensi"];

		// model
		$this->model->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_model"]);
		if ($this->model->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->model->AdvancedSearch->SearchOperator = @$_GET["z_model"];

		// rekening
		$this->rekening->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_rekening"]);
		if ($this->rekening->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->rekening->AdvancedSearch->SearchOperator = @$_GET["z_rekening"];

		// debet
		$this->debet->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_debet"]);
		if ($this->debet->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->debet->AdvancedSearch->SearchOperator = @$_GET["z_debet"];

		// credit
		$this->credit->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_credit"]);
		if ($this->credit->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->credit->AdvancedSearch->SearchOperator = @$_GET["z_credit"];

		// kantor
		$this->kantor->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_kantor"]);
		if ($this->kantor->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->kantor->AdvancedSearch->SearchOperator = @$_GET["z_kantor"];

		// keterangan
		$this->keterangan->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_keterangan"]);
		if ($this->keterangan->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->keterangan->AdvancedSearch->SearchOperator = @$_GET["z_keterangan"];

		// active
		$this->active->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_active"]);
		if ($this->active->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->active->AdvancedSearch->SearchOperator = @$_GET["z_active"];

		// ip
		$this->ip->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ip"]);
		if ($this->ip->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ip->AdvancedSearch->SearchOperator = @$_GET["z_ip"];

		// status
		$this->status->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_status"]);
		if ($this->status->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->status->AdvancedSearch->SearchOperator = @$_GET["z_status"];

		// user
		$this->user->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_user"]);
		if ($this->user->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->user->AdvancedSearch->SearchOperator = @$_GET["z_user"];

		// created
		$this->created->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_created"]);
		if ($this->created->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->created->AdvancedSearch->SearchOperator = @$_GET["z_created"];

		// modified
		$this->modified->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_modified"]);
		if ($this->modified->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->modified->AdvancedSearch->SearchOperator = @$_GET["z_modified"];
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
		$this->kantor->setDbValue($rs->fields('kantor'));
		$this->keterangan->setDbValue($rs->fields('keterangan'));
		$this->active->setDbValue($rs->fields('active'));
		$this->ip->setDbValue($rs->fields('ip'));
		$this->status->setDbValue($rs->fields('status'));
		$this->user->setDbValue($rs->fields('user'));
		$this->created->setDbValue($rs->fields('created'));
		$this->modified->setDbValue($rs->fields('modified'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->tanggal->DbValue = $row['tanggal'];
		$this->periode->DbValue = $row['periode'];
		$this->id->DbValue = $row['id'];
		$this->nomor->DbValue = $row['nomor'];
		$this->transaksi->DbValue = $row['transaksi'];
		$this->referensi->DbValue = $row['referensi'];
		$this->model->DbValue = $row['model'];
		$this->rekening->DbValue = $row['rekening'];
		$this->debet->DbValue = $row['debet'];
		$this->credit->DbValue = $row['credit'];
		$this->kantor->DbValue = $row['kantor'];
		$this->keterangan->DbValue = $row['keterangan'];
		$this->active->DbValue = $row['active'];
		$this->ip->DbValue = $row['ip'];
		$this->status->DbValue = $row['status'];
		$this->user->DbValue = $row['user'];
		$this->created->DbValue = $row['created'];
		$this->modified->DbValue = $row['modified'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("nomor")) <> "")
			$this->nomor->CurrentValue = $this->getKey("nomor"); // nomor
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
		if ($this->debet->FormValue == $this->debet->CurrentValue && is_numeric(ew_StrToFloat($this->debet->CurrentValue)))
			$this->debet->CurrentValue = ew_StrToFloat($this->debet->CurrentValue);

		// Convert decimal values if posted back
		if ($this->credit->FormValue == $this->credit->CurrentValue && is_numeric(ew_StrToFloat($this->credit->CurrentValue)))
			$this->credit->CurrentValue = ew_StrToFloat($this->credit->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
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
		// kantor
		// keterangan
		// active
		// ip
		// status
		// user
		// created
		// modified

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// tanggal
			$this->tanggal->EditAttrs["class"] = "form-control";
			$this->tanggal->EditCustomAttributes = "";
			$this->tanggal->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->tanggal->AdvancedSearch->SearchValue, 0), 8));
			$this->tanggal->PlaceHolder = ew_RemoveHtml($this->tanggal->FldCaption());

			// periode
			$this->periode->EditAttrs["class"] = "form-control";
			$this->periode->EditCustomAttributes = "";
			$this->periode->EditValue = ew_HtmlEncode($this->periode->AdvancedSearch->SearchValue);
			$this->periode->PlaceHolder = ew_RemoveHtml($this->periode->FldCaption());

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->AdvancedSearch->SearchValue);
			$this->id->PlaceHolder = ew_RemoveHtml($this->id->FldCaption());

			// nomor
			$this->nomor->EditAttrs["class"] = "form-control";
			$this->nomor->EditCustomAttributes = "";
			$this->nomor->EditValue = ew_HtmlEncode($this->nomor->AdvancedSearch->SearchValue);
			$this->nomor->PlaceHolder = ew_RemoveHtml($this->nomor->FldCaption());

			// transaksi
			$this->transaksi->EditAttrs["class"] = "form-control";
			$this->transaksi->EditCustomAttributes = "";
			$this->transaksi->EditValue = ew_HtmlEncode($this->transaksi->AdvancedSearch->SearchValue);
			$this->transaksi->PlaceHolder = ew_RemoveHtml($this->transaksi->FldCaption());

			// referensi
			$this->referensi->EditAttrs["class"] = "form-control";
			$this->referensi->EditCustomAttributes = "";
			$this->referensi->EditValue = ew_HtmlEncode($this->referensi->AdvancedSearch->SearchValue);
			$this->referensi->PlaceHolder = ew_RemoveHtml($this->referensi->FldCaption());

			// model
			$this->model->EditAttrs["class"] = "form-control";
			$this->model->EditCustomAttributes = "";
			$this->model->EditValue = ew_HtmlEncode($this->model->AdvancedSearch->SearchValue);
			$this->model->PlaceHolder = ew_RemoveHtml($this->model->FldCaption());

			// rekening
			$this->rekening->EditAttrs["class"] = "form-control";
			$this->rekening->EditCustomAttributes = "";
			$this->rekening->EditValue = ew_HtmlEncode($this->rekening->AdvancedSearch->SearchValue);
			$this->rekening->PlaceHolder = ew_RemoveHtml($this->rekening->FldCaption());

			// debet
			$this->debet->EditAttrs["class"] = "form-control";
			$this->debet->EditCustomAttributes = "";
			$this->debet->EditValue = ew_HtmlEncode($this->debet->AdvancedSearch->SearchValue);
			$this->debet->PlaceHolder = ew_RemoveHtml($this->debet->FldCaption());

			// credit
			$this->credit->EditAttrs["class"] = "form-control";
			$this->credit->EditCustomAttributes = "";
			$this->credit->EditValue = ew_HtmlEncode($this->credit->AdvancedSearch->SearchValue);
			$this->credit->PlaceHolder = ew_RemoveHtml($this->credit->FldCaption());

			// kantor
			$this->kantor->EditAttrs["class"] = "form-control";
			$this->kantor->EditCustomAttributes = "";
			$this->kantor->EditValue = ew_HtmlEncode($this->kantor->AdvancedSearch->SearchValue);
			$this->kantor->PlaceHolder = ew_RemoveHtml($this->kantor->FldCaption());

			// keterangan
			$this->keterangan->EditAttrs["class"] = "form-control";
			$this->keterangan->EditCustomAttributes = "";
			$this->keterangan->EditValue = ew_HtmlEncode($this->keterangan->AdvancedSearch->SearchValue);
			$this->keterangan->PlaceHolder = ew_RemoveHtml($this->keterangan->FldCaption());

			// active
			$this->active->EditCustomAttributes = "";
			$this->active->EditValue = $this->active->Options(FALSE);

			// ip
			$this->ip->EditAttrs["class"] = "form-control";
			$this->ip->EditCustomAttributes = "";
			$this->ip->EditValue = ew_HtmlEncode($this->ip->AdvancedSearch->SearchValue);
			$this->ip->PlaceHolder = ew_RemoveHtml($this->ip->FldCaption());

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = ew_HtmlEncode($this->status->AdvancedSearch->SearchValue);
			$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

			// user
			$this->user->EditAttrs["class"] = "form-control";
			$this->user->EditCustomAttributes = "";
			$this->user->EditValue = ew_HtmlEncode($this->user->AdvancedSearch->SearchValue);
			$this->user->PlaceHolder = ew_RemoveHtml($this->user->FldCaption());

			// created
			$this->created->EditAttrs["class"] = "form-control";
			$this->created->EditCustomAttributes = "";
			$this->created->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->created->AdvancedSearch->SearchValue, 0), 8));
			$this->created->PlaceHolder = ew_RemoveHtml($this->created->FldCaption());

			// modified
			$this->modified->EditAttrs["class"] = "form-control";
			$this->modified->EditCustomAttributes = "";
			$this->modified->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->modified->AdvancedSearch->SearchValue, 0), 8));
			$this->modified->PlaceHolder = ew_RemoveHtml($this->modified->FldCaption());
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->tanggal->AdvancedSearch->Load();
		$this->periode->AdvancedSearch->Load();
		$this->id->AdvancedSearch->Load();
		$this->nomor->AdvancedSearch->Load();
		$this->transaksi->AdvancedSearch->Load();
		$this->referensi->AdvancedSearch->Load();
		$this->model->AdvancedSearch->Load();
		$this->rekening->AdvancedSearch->Load();
		$this->debet->AdvancedSearch->Load();
		$this->credit->AdvancedSearch->Load();
		$this->kantor->AdvancedSearch->Load();
		$this->keterangan->AdvancedSearch->Load();
		$this->active->AdvancedSearch->Load();
		$this->ip->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
		$this->user->AdvancedSearch->Load();
		$this->created->AdvancedSearch->Load();
		$this->modified->AdvancedSearch->Load();
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
		if ($pageId == "list") {
			switch ($fld->FldVar) {
			}
		} elseif ($pageId == "extbs") {
			switch ($fld->FldVar) {
			}
		} 
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		if ($pageId == "list") {
			switch ($fld->FldVar) {
			}
		} elseif ($pageId == "extbs") {
			switch ($fld->FldVar) {
			}
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
if (!isset($tjurnalkasbank_list)) $tjurnalkasbank_list = new ctjurnalkasbank_list();

// Page init
$tjurnalkasbank_list->Page_Init();

// Page main
$tjurnalkasbank_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tjurnalkasbank_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = ftjurnalkasbanklist = new ew_Form("ftjurnalkasbanklist", "list");
ftjurnalkasbanklist.FormKeyCountName = '<?php echo $tjurnalkasbank_list->FormKeyCountName ?>';

// Form_CustomValidate event
ftjurnalkasbanklist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftjurnalkasbanklist.ValidateRequired = true;
<?php } else { ?>
ftjurnalkasbanklist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftjurnalkasbanklist.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftjurnalkasbanklist.Lists["x_active"].Options = <?php echo json_encode($tjurnalkasbank->active->Options()) ?>;

// Form object for search
var CurrentSearchForm = ftjurnalkasbanklistsrch = new ew_Form("ftjurnalkasbanklistsrch");

// Validate function for search
ftjurnalkasbanklistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
ftjurnalkasbanklistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftjurnalkasbanklistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
ftjurnalkasbanklistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
ftjurnalkasbanklistsrch.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftjurnalkasbanklistsrch.Lists["x_active"].Options = <?php echo json_encode($tjurnalkasbank->active->Options()) ?>;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($tjurnalkasbank_list->TotalRecs > 0 && $tjurnalkasbank_list->ExportOptions->Visible()) { ?>
<?php $tjurnalkasbank_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($tjurnalkasbank_list->SearchOptions->Visible()) { ?>
<?php $tjurnalkasbank_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($tjurnalkasbank_list->FilterOptions->Visible()) { ?>
<?php $tjurnalkasbank_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $tjurnalkasbank_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($tjurnalkasbank_list->TotalRecs <= 0)
			$tjurnalkasbank_list->TotalRecs = $tjurnalkasbank->SelectRecordCount();
	} else {
		if (!$tjurnalkasbank_list->Recordset && ($tjurnalkasbank_list->Recordset = $tjurnalkasbank_list->LoadRecordset()))
			$tjurnalkasbank_list->TotalRecs = $tjurnalkasbank_list->Recordset->RecordCount();
	}
	$tjurnalkasbank_list->StartRec = 1;
	if ($tjurnalkasbank_list->DisplayRecs <= 0 || ($tjurnalkasbank->Export <> "" && $tjurnalkasbank->ExportAll)) // Display all records
		$tjurnalkasbank_list->DisplayRecs = $tjurnalkasbank_list->TotalRecs;
	if (!($tjurnalkasbank->Export <> "" && $tjurnalkasbank->ExportAll))
		$tjurnalkasbank_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$tjurnalkasbank_list->Recordset = $tjurnalkasbank_list->LoadRecordset($tjurnalkasbank_list->StartRec-1, $tjurnalkasbank_list->DisplayRecs);

	// Set no record found message
	if ($tjurnalkasbank->CurrentAction == "" && $tjurnalkasbank_list->TotalRecs == 0) {
		if ($tjurnalkasbank_list->SearchWhere == "0=101")
			$tjurnalkasbank_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$tjurnalkasbank_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$tjurnalkasbank_list->RenderOtherOptions();
?>
<?php if ($tjurnalkasbank->Export == "" && $tjurnalkasbank->CurrentAction == "") { ?>
<form name="ftjurnalkasbanklistsrch" id="ftjurnalkasbanklistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($tjurnalkasbank_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="ftjurnalkasbanklistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="tjurnalkasbank">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$tjurnalkasbank_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$tjurnalkasbank->RowType = EW_ROWTYPE_SEARCH;

// Render row
$tjurnalkasbank->ResetAttrs();
$tjurnalkasbank_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($tjurnalkasbank->active->Visible) { // active ?>
	<div id="xsc_active" class="ewCell form-group">
		<label class="ewSearchCaption ewLabel"><?php echo $tjurnalkasbank->active->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_active" id="z_active" value="="></span>
		<span class="ewSearchField">
<div id="tp_x_active" class="ewTemplate"><input type="radio" data-table="tjurnalkasbank" data-field="x_active" data-value-separator="<?php echo $tjurnalkasbank->active->DisplayValueSeparatorAttribute() ?>" name="x_active" id="x_active" value="{value}"<?php echo $tjurnalkasbank->active->EditAttributes() ?>></div>
<div id="dsl_x_active" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $tjurnalkasbank->active->RadioButtonListHtml(FALSE, "x_active") ?>
</div></div>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($tjurnalkasbank_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($tjurnalkasbank_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $tjurnalkasbank_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($tjurnalkasbank_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($tjurnalkasbank_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($tjurnalkasbank_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($tjurnalkasbank_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php $tjurnalkasbank_list->ShowPageHeader(); ?>
<?php
$tjurnalkasbank_list->ShowMessage();
?>
<?php if ($tjurnalkasbank_list->TotalRecs > 0 || $tjurnalkasbank->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid tjurnalkasbank">
<form name="ftjurnalkasbanklist" id="ftjurnalkasbanklist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tjurnalkasbank_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tjurnalkasbank_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tjurnalkasbank">
<div id="gmp_tjurnalkasbank" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($tjurnalkasbank_list->TotalRecs > 0 || $tjurnalkasbank->CurrentAction == "gridedit") { ?>
<table id="tbl_tjurnalkasbanklist" class="table ewTable">
<?php echo $tjurnalkasbank->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$tjurnalkasbank_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$tjurnalkasbank_list->RenderListOptions();

// Render list options (header, left)
$tjurnalkasbank_list->ListOptions->Render("header", "left");
?>
<?php if ($tjurnalkasbank->tanggal->Visible) { // tanggal ?>
	<?php if ($tjurnalkasbank->SortUrl($tjurnalkasbank->tanggal) == "") { ?>
		<th data-name="tanggal"><div id="elh_tjurnalkasbank_tanggal" class="tjurnalkasbank_tanggal"><div class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->tanggal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tanggal"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnalkasbank->SortUrl($tjurnalkasbank->tanggal) ?>',1);"><div id="elh_tjurnalkasbank_tanggal" class="tjurnalkasbank_tanggal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->tanggal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tjurnalkasbank->tanggal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnalkasbank->tanggal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnalkasbank->periode->Visible) { // periode ?>
	<?php if ($tjurnalkasbank->SortUrl($tjurnalkasbank->periode) == "") { ?>
		<th data-name="periode"><div id="elh_tjurnalkasbank_periode" class="tjurnalkasbank_periode"><div class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->periode->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="periode"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnalkasbank->SortUrl($tjurnalkasbank->periode) ?>',1);"><div id="elh_tjurnalkasbank_periode" class="tjurnalkasbank_periode">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->periode->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tjurnalkasbank->periode->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnalkasbank->periode->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnalkasbank->id->Visible) { // id ?>
	<?php if ($tjurnalkasbank->SortUrl($tjurnalkasbank->id) == "") { ?>
		<th data-name="id"><div id="elh_tjurnalkasbank_id" class="tjurnalkasbank_id"><div class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnalkasbank->SortUrl($tjurnalkasbank->id) ?>',1);"><div id="elh_tjurnalkasbank_id" class="tjurnalkasbank_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->id->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tjurnalkasbank->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnalkasbank->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnalkasbank->nomor->Visible) { // nomor ?>
	<?php if ($tjurnalkasbank->SortUrl($tjurnalkasbank->nomor) == "") { ?>
		<th data-name="nomor"><div id="elh_tjurnalkasbank_nomor" class="tjurnalkasbank_nomor"><div class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->nomor->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nomor"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnalkasbank->SortUrl($tjurnalkasbank->nomor) ?>',1);"><div id="elh_tjurnalkasbank_nomor" class="tjurnalkasbank_nomor">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->nomor->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tjurnalkasbank->nomor->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnalkasbank->nomor->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnalkasbank->transaksi->Visible) { // transaksi ?>
	<?php if ($tjurnalkasbank->SortUrl($tjurnalkasbank->transaksi) == "") { ?>
		<th data-name="transaksi"><div id="elh_tjurnalkasbank_transaksi" class="tjurnalkasbank_transaksi"><div class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->transaksi->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="transaksi"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnalkasbank->SortUrl($tjurnalkasbank->transaksi) ?>',1);"><div id="elh_tjurnalkasbank_transaksi" class="tjurnalkasbank_transaksi">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->transaksi->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tjurnalkasbank->transaksi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnalkasbank->transaksi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnalkasbank->referensi->Visible) { // referensi ?>
	<?php if ($tjurnalkasbank->SortUrl($tjurnalkasbank->referensi) == "") { ?>
		<th data-name="referensi"><div id="elh_tjurnalkasbank_referensi" class="tjurnalkasbank_referensi"><div class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->referensi->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="referensi"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnalkasbank->SortUrl($tjurnalkasbank->referensi) ?>',1);"><div id="elh_tjurnalkasbank_referensi" class="tjurnalkasbank_referensi">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->referensi->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tjurnalkasbank->referensi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnalkasbank->referensi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnalkasbank->model->Visible) { // model ?>
	<?php if ($tjurnalkasbank->SortUrl($tjurnalkasbank->model) == "") { ?>
		<th data-name="model"><div id="elh_tjurnalkasbank_model" class="tjurnalkasbank_model"><div class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->model->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="model"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnalkasbank->SortUrl($tjurnalkasbank->model) ?>',1);"><div id="elh_tjurnalkasbank_model" class="tjurnalkasbank_model">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->model->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tjurnalkasbank->model->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnalkasbank->model->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnalkasbank->rekening->Visible) { // rekening ?>
	<?php if ($tjurnalkasbank->SortUrl($tjurnalkasbank->rekening) == "") { ?>
		<th data-name="rekening"><div id="elh_tjurnalkasbank_rekening" class="tjurnalkasbank_rekening"><div class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->rekening->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="rekening"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnalkasbank->SortUrl($tjurnalkasbank->rekening) ?>',1);"><div id="elh_tjurnalkasbank_rekening" class="tjurnalkasbank_rekening">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->rekening->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tjurnalkasbank->rekening->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnalkasbank->rekening->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnalkasbank->debet->Visible) { // debet ?>
	<?php if ($tjurnalkasbank->SortUrl($tjurnalkasbank->debet) == "") { ?>
		<th data-name="debet"><div id="elh_tjurnalkasbank_debet" class="tjurnalkasbank_debet"><div class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->debet->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="debet"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnalkasbank->SortUrl($tjurnalkasbank->debet) ?>',1);"><div id="elh_tjurnalkasbank_debet" class="tjurnalkasbank_debet">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->debet->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tjurnalkasbank->debet->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnalkasbank->debet->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnalkasbank->credit->Visible) { // credit ?>
	<?php if ($tjurnalkasbank->SortUrl($tjurnalkasbank->credit) == "") { ?>
		<th data-name="credit"><div id="elh_tjurnalkasbank_credit" class="tjurnalkasbank_credit"><div class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->credit->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="credit"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnalkasbank->SortUrl($tjurnalkasbank->credit) ?>',1);"><div id="elh_tjurnalkasbank_credit" class="tjurnalkasbank_credit">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->credit->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tjurnalkasbank->credit->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnalkasbank->credit->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnalkasbank->kantor->Visible) { // kantor ?>
	<?php if ($tjurnalkasbank->SortUrl($tjurnalkasbank->kantor) == "") { ?>
		<th data-name="kantor"><div id="elh_tjurnalkasbank_kantor" class="tjurnalkasbank_kantor"><div class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->kantor->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="kantor"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnalkasbank->SortUrl($tjurnalkasbank->kantor) ?>',1);"><div id="elh_tjurnalkasbank_kantor" class="tjurnalkasbank_kantor">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->kantor->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tjurnalkasbank->kantor->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnalkasbank->kantor->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnalkasbank->keterangan->Visible) { // keterangan ?>
	<?php if ($tjurnalkasbank->SortUrl($tjurnalkasbank->keterangan) == "") { ?>
		<th data-name="keterangan"><div id="elh_tjurnalkasbank_keterangan" class="tjurnalkasbank_keterangan"><div class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->keterangan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="keterangan"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnalkasbank->SortUrl($tjurnalkasbank->keterangan) ?>',1);"><div id="elh_tjurnalkasbank_keterangan" class="tjurnalkasbank_keterangan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->keterangan->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tjurnalkasbank->keterangan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnalkasbank->keterangan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnalkasbank->active->Visible) { // active ?>
	<?php if ($tjurnalkasbank->SortUrl($tjurnalkasbank->active) == "") { ?>
		<th data-name="active"><div id="elh_tjurnalkasbank_active" class="tjurnalkasbank_active"><div class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->active->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="active"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnalkasbank->SortUrl($tjurnalkasbank->active) ?>',1);"><div id="elh_tjurnalkasbank_active" class="tjurnalkasbank_active">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->active->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tjurnalkasbank->active->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnalkasbank->active->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnalkasbank->ip->Visible) { // ip ?>
	<?php if ($tjurnalkasbank->SortUrl($tjurnalkasbank->ip) == "") { ?>
		<th data-name="ip"><div id="elh_tjurnalkasbank_ip" class="tjurnalkasbank_ip"><div class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->ip->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ip"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnalkasbank->SortUrl($tjurnalkasbank->ip) ?>',1);"><div id="elh_tjurnalkasbank_ip" class="tjurnalkasbank_ip">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->ip->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tjurnalkasbank->ip->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnalkasbank->ip->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnalkasbank->status->Visible) { // status ?>
	<?php if ($tjurnalkasbank->SortUrl($tjurnalkasbank->status) == "") { ?>
		<th data-name="status"><div id="elh_tjurnalkasbank_status" class="tjurnalkasbank_status"><div class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->status->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnalkasbank->SortUrl($tjurnalkasbank->status) ?>',1);"><div id="elh_tjurnalkasbank_status" class="tjurnalkasbank_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->status->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tjurnalkasbank->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnalkasbank->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnalkasbank->user->Visible) { // user ?>
	<?php if ($tjurnalkasbank->SortUrl($tjurnalkasbank->user) == "") { ?>
		<th data-name="user"><div id="elh_tjurnalkasbank_user" class="tjurnalkasbank_user"><div class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->user->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="user"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnalkasbank->SortUrl($tjurnalkasbank->user) ?>',1);"><div id="elh_tjurnalkasbank_user" class="tjurnalkasbank_user">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->user->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tjurnalkasbank->user->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnalkasbank->user->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnalkasbank->created->Visible) { // created ?>
	<?php if ($tjurnalkasbank->SortUrl($tjurnalkasbank->created) == "") { ?>
		<th data-name="created"><div id="elh_tjurnalkasbank_created" class="tjurnalkasbank_created"><div class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->created->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="created"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnalkasbank->SortUrl($tjurnalkasbank->created) ?>',1);"><div id="elh_tjurnalkasbank_created" class="tjurnalkasbank_created">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->created->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tjurnalkasbank->created->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnalkasbank->created->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnalkasbank->modified->Visible) { // modified ?>
	<?php if ($tjurnalkasbank->SortUrl($tjurnalkasbank->modified) == "") { ?>
		<th data-name="modified"><div id="elh_tjurnalkasbank_modified" class="tjurnalkasbank_modified"><div class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->modified->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="modified"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnalkasbank->SortUrl($tjurnalkasbank->modified) ?>',1);"><div id="elh_tjurnalkasbank_modified" class="tjurnalkasbank_modified">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnalkasbank->modified->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tjurnalkasbank->modified->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnalkasbank->modified->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$tjurnalkasbank_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($tjurnalkasbank->ExportAll && $tjurnalkasbank->Export <> "") {
	$tjurnalkasbank_list->StopRec = $tjurnalkasbank_list->TotalRecs;
} else {

	// Set the last record to display
	if ($tjurnalkasbank_list->TotalRecs > $tjurnalkasbank_list->StartRec + $tjurnalkasbank_list->DisplayRecs - 1)
		$tjurnalkasbank_list->StopRec = $tjurnalkasbank_list->StartRec + $tjurnalkasbank_list->DisplayRecs - 1;
	else
		$tjurnalkasbank_list->StopRec = $tjurnalkasbank_list->TotalRecs;
}
$tjurnalkasbank_list->RecCnt = $tjurnalkasbank_list->StartRec - 1;
if ($tjurnalkasbank_list->Recordset && !$tjurnalkasbank_list->Recordset->EOF) {
	$tjurnalkasbank_list->Recordset->MoveFirst();
	$bSelectLimit = $tjurnalkasbank_list->UseSelectLimit;
	if (!$bSelectLimit && $tjurnalkasbank_list->StartRec > 1)
		$tjurnalkasbank_list->Recordset->Move($tjurnalkasbank_list->StartRec - 1);
} elseif (!$tjurnalkasbank->AllowAddDeleteRow && $tjurnalkasbank_list->StopRec == 0) {
	$tjurnalkasbank_list->StopRec = $tjurnalkasbank->GridAddRowCount;
}

// Initialize aggregate
$tjurnalkasbank->RowType = EW_ROWTYPE_AGGREGATEINIT;
$tjurnalkasbank->ResetAttrs();
$tjurnalkasbank_list->RenderRow();
while ($tjurnalkasbank_list->RecCnt < $tjurnalkasbank_list->StopRec) {
	$tjurnalkasbank_list->RecCnt++;
	if (intval($tjurnalkasbank_list->RecCnt) >= intval($tjurnalkasbank_list->StartRec)) {
		$tjurnalkasbank_list->RowCnt++;

		// Set up key count
		$tjurnalkasbank_list->KeyCount = $tjurnalkasbank_list->RowIndex;

		// Init row class and style
		$tjurnalkasbank->ResetAttrs();
		$tjurnalkasbank->CssClass = "";
		if ($tjurnalkasbank->CurrentAction == "gridadd") {
		} else {
			$tjurnalkasbank_list->LoadRowValues($tjurnalkasbank_list->Recordset); // Load row values
		}
		$tjurnalkasbank->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$tjurnalkasbank->RowAttrs = array_merge($tjurnalkasbank->RowAttrs, array('data-rowindex'=>$tjurnalkasbank_list->RowCnt, 'id'=>'r' . $tjurnalkasbank_list->RowCnt . '_tjurnalkasbank', 'data-rowtype'=>$tjurnalkasbank->RowType));

		// Render row
		$tjurnalkasbank_list->RenderRow();

		// Render list options
		$tjurnalkasbank_list->RenderListOptions();
?>
	<tr<?php echo $tjurnalkasbank->RowAttributes() ?>>
<?php

// Render list options (body, left)
$tjurnalkasbank_list->ListOptions->Render("body", "left", $tjurnalkasbank_list->RowCnt);
?>
	<?php if ($tjurnalkasbank->tanggal->Visible) { // tanggal ?>
		<td data-name="tanggal"<?php echo $tjurnalkasbank->tanggal->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_list->RowCnt ?>_tjurnalkasbank_tanggal" class="tjurnalkasbank_tanggal">
<span<?php echo $tjurnalkasbank->tanggal->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->tanggal->ListViewValue() ?></span>
</span>
<a id="<?php echo $tjurnalkasbank_list->PageObjName . "_row_" . $tjurnalkasbank_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($tjurnalkasbank->periode->Visible) { // periode ?>
		<td data-name="periode"<?php echo $tjurnalkasbank->periode->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_list->RowCnt ?>_tjurnalkasbank_periode" class="tjurnalkasbank_periode">
<span<?php echo $tjurnalkasbank->periode->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->periode->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnalkasbank->id->Visible) { // id ?>
		<td data-name="id"<?php echo $tjurnalkasbank->id->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_list->RowCnt ?>_tjurnalkasbank_id" class="tjurnalkasbank_id">
<span<?php echo $tjurnalkasbank->id->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnalkasbank->nomor->Visible) { // nomor ?>
		<td data-name="nomor"<?php echo $tjurnalkasbank->nomor->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_list->RowCnt ?>_tjurnalkasbank_nomor" class="tjurnalkasbank_nomor">
<span<?php echo $tjurnalkasbank->nomor->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->nomor->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnalkasbank->transaksi->Visible) { // transaksi ?>
		<td data-name="transaksi"<?php echo $tjurnalkasbank->transaksi->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_list->RowCnt ?>_tjurnalkasbank_transaksi" class="tjurnalkasbank_transaksi">
<span<?php echo $tjurnalkasbank->transaksi->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->transaksi->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnalkasbank->referensi->Visible) { // referensi ?>
		<td data-name="referensi"<?php echo $tjurnalkasbank->referensi->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_list->RowCnt ?>_tjurnalkasbank_referensi" class="tjurnalkasbank_referensi">
<span<?php echo $tjurnalkasbank->referensi->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->referensi->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnalkasbank->model->Visible) { // model ?>
		<td data-name="model"<?php echo $tjurnalkasbank->model->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_list->RowCnt ?>_tjurnalkasbank_model" class="tjurnalkasbank_model">
<span<?php echo $tjurnalkasbank->model->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->model->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnalkasbank->rekening->Visible) { // rekening ?>
		<td data-name="rekening"<?php echo $tjurnalkasbank->rekening->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_list->RowCnt ?>_tjurnalkasbank_rekening" class="tjurnalkasbank_rekening">
<span<?php echo $tjurnalkasbank->rekening->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->rekening->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnalkasbank->debet->Visible) { // debet ?>
		<td data-name="debet"<?php echo $tjurnalkasbank->debet->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_list->RowCnt ?>_tjurnalkasbank_debet" class="tjurnalkasbank_debet">
<span<?php echo $tjurnalkasbank->debet->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->debet->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnalkasbank->credit->Visible) { // credit ?>
		<td data-name="credit"<?php echo $tjurnalkasbank->credit->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_list->RowCnt ?>_tjurnalkasbank_credit" class="tjurnalkasbank_credit">
<span<?php echo $tjurnalkasbank->credit->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->credit->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnalkasbank->kantor->Visible) { // kantor ?>
		<td data-name="kantor"<?php echo $tjurnalkasbank->kantor->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_list->RowCnt ?>_tjurnalkasbank_kantor" class="tjurnalkasbank_kantor">
<span<?php echo $tjurnalkasbank->kantor->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->kantor->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnalkasbank->keterangan->Visible) { // keterangan ?>
		<td data-name="keterangan"<?php echo $tjurnalkasbank->keterangan->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_list->RowCnt ?>_tjurnalkasbank_keterangan" class="tjurnalkasbank_keterangan">
<span<?php echo $tjurnalkasbank->keterangan->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->keterangan->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnalkasbank->active->Visible) { // active ?>
		<td data-name="active"<?php echo $tjurnalkasbank->active->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_list->RowCnt ?>_tjurnalkasbank_active" class="tjurnalkasbank_active">
<span<?php echo $tjurnalkasbank->active->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->active->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnalkasbank->ip->Visible) { // ip ?>
		<td data-name="ip"<?php echo $tjurnalkasbank->ip->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_list->RowCnt ?>_tjurnalkasbank_ip" class="tjurnalkasbank_ip">
<span<?php echo $tjurnalkasbank->ip->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->ip->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnalkasbank->status->Visible) { // status ?>
		<td data-name="status"<?php echo $tjurnalkasbank->status->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_list->RowCnt ?>_tjurnalkasbank_status" class="tjurnalkasbank_status">
<span<?php echo $tjurnalkasbank->status->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->status->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnalkasbank->user->Visible) { // user ?>
		<td data-name="user"<?php echo $tjurnalkasbank->user->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_list->RowCnt ?>_tjurnalkasbank_user" class="tjurnalkasbank_user">
<span<?php echo $tjurnalkasbank->user->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->user->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnalkasbank->created->Visible) { // created ?>
		<td data-name="created"<?php echo $tjurnalkasbank->created->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_list->RowCnt ?>_tjurnalkasbank_created" class="tjurnalkasbank_created">
<span<?php echo $tjurnalkasbank->created->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->created->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnalkasbank->modified->Visible) { // modified ?>
		<td data-name="modified"<?php echo $tjurnalkasbank->modified->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_list->RowCnt ?>_tjurnalkasbank_modified" class="tjurnalkasbank_modified">
<span<?php echo $tjurnalkasbank->modified->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->modified->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$tjurnalkasbank_list->ListOptions->Render("body", "right", $tjurnalkasbank_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($tjurnalkasbank->CurrentAction <> "gridadd")
		$tjurnalkasbank_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($tjurnalkasbank->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($tjurnalkasbank_list->Recordset)
	$tjurnalkasbank_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($tjurnalkasbank->CurrentAction <> "gridadd" && $tjurnalkasbank->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($tjurnalkasbank_list->Pager)) $tjurnalkasbank_list->Pager = new cPrevNextPager($tjurnalkasbank_list->StartRec, $tjurnalkasbank_list->DisplayRecs, $tjurnalkasbank_list->TotalRecs) ?>
<?php if ($tjurnalkasbank_list->Pager->RecordCount > 0 && $tjurnalkasbank_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($tjurnalkasbank_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $tjurnalkasbank_list->PageUrl() ?>start=<?php echo $tjurnalkasbank_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($tjurnalkasbank_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $tjurnalkasbank_list->PageUrl() ?>start=<?php echo $tjurnalkasbank_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $tjurnalkasbank_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($tjurnalkasbank_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $tjurnalkasbank_list->PageUrl() ?>start=<?php echo $tjurnalkasbank_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($tjurnalkasbank_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $tjurnalkasbank_list->PageUrl() ?>start=<?php echo $tjurnalkasbank_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $tjurnalkasbank_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $tjurnalkasbank_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $tjurnalkasbank_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $tjurnalkasbank_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($tjurnalkasbank_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($tjurnalkasbank_list->TotalRecs == 0 && $tjurnalkasbank->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($tjurnalkasbank_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
ftjurnalkasbanklistsrch.FilterList = <?php echo $tjurnalkasbank_list->GetFilterList() ?>;
ftjurnalkasbanklistsrch.Init();
ftjurnalkasbanklist.Init();
</script>
<?php
$tjurnalkasbank_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tjurnalkasbank_list->Page_Terminate();
?>
