<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "texcelinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$texcel_list = NULL; // Initialize page object first

class ctexcel_list extends ctexcel {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'texcel';

	// Page object name
	var $PageObjName = 'texcel_list';

	// Grid form hidden field names
	var $FormName = 'ftexcellist';
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

		// Table object (texcel)
		if (!isset($GLOBALS["texcel"]) || get_class($GLOBALS["texcel"]) == "ctexcel") {
			$GLOBALS["texcel"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["texcel"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "texceladd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "texceldelete.php";
		$this->MultiUpdateUrl = "texcelupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'texcel', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption ftexcellistsrch";

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
		$this->SHEET->SetVisibility();
		$this->NOMOR->SetVisibility();
		$this->A->SetVisibility();
		$this->B->SetVisibility();
		$this->C->SetVisibility();
		$this->D->SetVisibility();
		$this->E->SetVisibility();
		$this->F->SetVisibility();
		$this->G->SetVisibility();
		$this->H->SetVisibility();
		$this->_I->SetVisibility();
		$this->J->SetVisibility();
		$this->K->SetVisibility();
		$this->L->SetVisibility();
		$this->M->SetVisibility();
		$this->N->SetVisibility();
		$this->O->SetVisibility();
		$this->P->SetVisibility();
		$this->Q->SetVisibility();
		$this->R->SetVisibility();
		$this->S->SetVisibility();
		$this->T->SetVisibility();
		$this->U->SetVisibility();
		$this->V->SetVisibility();
		$this->W->SetVisibility();
		$this->X->SetVisibility();
		$this->Y->SetVisibility();
		$this->Z->SetVisibility();

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
		global $EW_EXPORT, $texcel;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($texcel);
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
		if (count($arrKeyFlds) >= 2) {
			$this->SHEET->setFormValue($arrKeyFlds[0]);
			$this->NOMOR->setFormValue($arrKeyFlds[1]);
			if (!is_numeric($this->NOMOR->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = isset($UserProfile) ? $UserProfile->GetSearchFilters(CurrentUserName(), "ftexcellistsrch") : "";
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->SHEET->AdvancedSearch->ToJSON(), ","); // Field SHEET
		$sFilterList = ew_Concat($sFilterList, $this->NOMOR->AdvancedSearch->ToJSON(), ","); // Field NOMOR
		$sFilterList = ew_Concat($sFilterList, $this->A->AdvancedSearch->ToJSON(), ","); // Field A
		$sFilterList = ew_Concat($sFilterList, $this->B->AdvancedSearch->ToJSON(), ","); // Field B
		$sFilterList = ew_Concat($sFilterList, $this->C->AdvancedSearch->ToJSON(), ","); // Field C
		$sFilterList = ew_Concat($sFilterList, $this->D->AdvancedSearch->ToJSON(), ","); // Field D
		$sFilterList = ew_Concat($sFilterList, $this->E->AdvancedSearch->ToJSON(), ","); // Field E
		$sFilterList = ew_Concat($sFilterList, $this->F->AdvancedSearch->ToJSON(), ","); // Field F
		$sFilterList = ew_Concat($sFilterList, $this->G->AdvancedSearch->ToJSON(), ","); // Field G
		$sFilterList = ew_Concat($sFilterList, $this->H->AdvancedSearch->ToJSON(), ","); // Field H
		$sFilterList = ew_Concat($sFilterList, $this->_I->AdvancedSearch->ToJSON(), ","); // Field I
		$sFilterList = ew_Concat($sFilterList, $this->J->AdvancedSearch->ToJSON(), ","); // Field J
		$sFilterList = ew_Concat($sFilterList, $this->K->AdvancedSearch->ToJSON(), ","); // Field K
		$sFilterList = ew_Concat($sFilterList, $this->L->AdvancedSearch->ToJSON(), ","); // Field L
		$sFilterList = ew_Concat($sFilterList, $this->M->AdvancedSearch->ToJSON(), ","); // Field M
		$sFilterList = ew_Concat($sFilterList, $this->N->AdvancedSearch->ToJSON(), ","); // Field N
		$sFilterList = ew_Concat($sFilterList, $this->O->AdvancedSearch->ToJSON(), ","); // Field O
		$sFilterList = ew_Concat($sFilterList, $this->P->AdvancedSearch->ToJSON(), ","); // Field P
		$sFilterList = ew_Concat($sFilterList, $this->Q->AdvancedSearch->ToJSON(), ","); // Field Q
		$sFilterList = ew_Concat($sFilterList, $this->R->AdvancedSearch->ToJSON(), ","); // Field R
		$sFilterList = ew_Concat($sFilterList, $this->S->AdvancedSearch->ToJSON(), ","); // Field S
		$sFilterList = ew_Concat($sFilterList, $this->T->AdvancedSearch->ToJSON(), ","); // Field T
		$sFilterList = ew_Concat($sFilterList, $this->U->AdvancedSearch->ToJSON(), ","); // Field U
		$sFilterList = ew_Concat($sFilterList, $this->V->AdvancedSearch->ToJSON(), ","); // Field V
		$sFilterList = ew_Concat($sFilterList, $this->W->AdvancedSearch->ToJSON(), ","); // Field W
		$sFilterList = ew_Concat($sFilterList, $this->X->AdvancedSearch->ToJSON(), ","); // Field X
		$sFilterList = ew_Concat($sFilterList, $this->Y->AdvancedSearch->ToJSON(), ","); // Field Y
		$sFilterList = ew_Concat($sFilterList, $this->Z->AdvancedSearch->ToJSON(), ","); // Field Z
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "ftexcellistsrch", $filters);

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

		// Field SHEET
		$this->SHEET->AdvancedSearch->SearchValue = @$filter["x_SHEET"];
		$this->SHEET->AdvancedSearch->SearchOperator = @$filter["z_SHEET"];
		$this->SHEET->AdvancedSearch->SearchCondition = @$filter["v_SHEET"];
		$this->SHEET->AdvancedSearch->SearchValue2 = @$filter["y_SHEET"];
		$this->SHEET->AdvancedSearch->SearchOperator2 = @$filter["w_SHEET"];
		$this->SHEET->AdvancedSearch->Save();

		// Field NOMOR
		$this->NOMOR->AdvancedSearch->SearchValue = @$filter["x_NOMOR"];
		$this->NOMOR->AdvancedSearch->SearchOperator = @$filter["z_NOMOR"];
		$this->NOMOR->AdvancedSearch->SearchCondition = @$filter["v_NOMOR"];
		$this->NOMOR->AdvancedSearch->SearchValue2 = @$filter["y_NOMOR"];
		$this->NOMOR->AdvancedSearch->SearchOperator2 = @$filter["w_NOMOR"];
		$this->NOMOR->AdvancedSearch->Save();

		// Field A
		$this->A->AdvancedSearch->SearchValue = @$filter["x_A"];
		$this->A->AdvancedSearch->SearchOperator = @$filter["z_A"];
		$this->A->AdvancedSearch->SearchCondition = @$filter["v_A"];
		$this->A->AdvancedSearch->SearchValue2 = @$filter["y_A"];
		$this->A->AdvancedSearch->SearchOperator2 = @$filter["w_A"];
		$this->A->AdvancedSearch->Save();

		// Field B
		$this->B->AdvancedSearch->SearchValue = @$filter["x_B"];
		$this->B->AdvancedSearch->SearchOperator = @$filter["z_B"];
		$this->B->AdvancedSearch->SearchCondition = @$filter["v_B"];
		$this->B->AdvancedSearch->SearchValue2 = @$filter["y_B"];
		$this->B->AdvancedSearch->SearchOperator2 = @$filter["w_B"];
		$this->B->AdvancedSearch->Save();

		// Field C
		$this->C->AdvancedSearch->SearchValue = @$filter["x_C"];
		$this->C->AdvancedSearch->SearchOperator = @$filter["z_C"];
		$this->C->AdvancedSearch->SearchCondition = @$filter["v_C"];
		$this->C->AdvancedSearch->SearchValue2 = @$filter["y_C"];
		$this->C->AdvancedSearch->SearchOperator2 = @$filter["w_C"];
		$this->C->AdvancedSearch->Save();

		// Field D
		$this->D->AdvancedSearch->SearchValue = @$filter["x_D"];
		$this->D->AdvancedSearch->SearchOperator = @$filter["z_D"];
		$this->D->AdvancedSearch->SearchCondition = @$filter["v_D"];
		$this->D->AdvancedSearch->SearchValue2 = @$filter["y_D"];
		$this->D->AdvancedSearch->SearchOperator2 = @$filter["w_D"];
		$this->D->AdvancedSearch->Save();

		// Field E
		$this->E->AdvancedSearch->SearchValue = @$filter["x_E"];
		$this->E->AdvancedSearch->SearchOperator = @$filter["z_E"];
		$this->E->AdvancedSearch->SearchCondition = @$filter["v_E"];
		$this->E->AdvancedSearch->SearchValue2 = @$filter["y_E"];
		$this->E->AdvancedSearch->SearchOperator2 = @$filter["w_E"];
		$this->E->AdvancedSearch->Save();

		// Field F
		$this->F->AdvancedSearch->SearchValue = @$filter["x_F"];
		$this->F->AdvancedSearch->SearchOperator = @$filter["z_F"];
		$this->F->AdvancedSearch->SearchCondition = @$filter["v_F"];
		$this->F->AdvancedSearch->SearchValue2 = @$filter["y_F"];
		$this->F->AdvancedSearch->SearchOperator2 = @$filter["w_F"];
		$this->F->AdvancedSearch->Save();

		// Field G
		$this->G->AdvancedSearch->SearchValue = @$filter["x_G"];
		$this->G->AdvancedSearch->SearchOperator = @$filter["z_G"];
		$this->G->AdvancedSearch->SearchCondition = @$filter["v_G"];
		$this->G->AdvancedSearch->SearchValue2 = @$filter["y_G"];
		$this->G->AdvancedSearch->SearchOperator2 = @$filter["w_G"];
		$this->G->AdvancedSearch->Save();

		// Field H
		$this->H->AdvancedSearch->SearchValue = @$filter["x_H"];
		$this->H->AdvancedSearch->SearchOperator = @$filter["z_H"];
		$this->H->AdvancedSearch->SearchCondition = @$filter["v_H"];
		$this->H->AdvancedSearch->SearchValue2 = @$filter["y_H"];
		$this->H->AdvancedSearch->SearchOperator2 = @$filter["w_H"];
		$this->H->AdvancedSearch->Save();

		// Field I
		$this->_I->AdvancedSearch->SearchValue = @$filter["x__I"];
		$this->_I->AdvancedSearch->SearchOperator = @$filter["z__I"];
		$this->_I->AdvancedSearch->SearchCondition = @$filter["v__I"];
		$this->_I->AdvancedSearch->SearchValue2 = @$filter["y__I"];
		$this->_I->AdvancedSearch->SearchOperator2 = @$filter["w__I"];
		$this->_I->AdvancedSearch->Save();

		// Field J
		$this->J->AdvancedSearch->SearchValue = @$filter["x_J"];
		$this->J->AdvancedSearch->SearchOperator = @$filter["z_J"];
		$this->J->AdvancedSearch->SearchCondition = @$filter["v_J"];
		$this->J->AdvancedSearch->SearchValue2 = @$filter["y_J"];
		$this->J->AdvancedSearch->SearchOperator2 = @$filter["w_J"];
		$this->J->AdvancedSearch->Save();

		// Field K
		$this->K->AdvancedSearch->SearchValue = @$filter["x_K"];
		$this->K->AdvancedSearch->SearchOperator = @$filter["z_K"];
		$this->K->AdvancedSearch->SearchCondition = @$filter["v_K"];
		$this->K->AdvancedSearch->SearchValue2 = @$filter["y_K"];
		$this->K->AdvancedSearch->SearchOperator2 = @$filter["w_K"];
		$this->K->AdvancedSearch->Save();

		// Field L
		$this->L->AdvancedSearch->SearchValue = @$filter["x_L"];
		$this->L->AdvancedSearch->SearchOperator = @$filter["z_L"];
		$this->L->AdvancedSearch->SearchCondition = @$filter["v_L"];
		$this->L->AdvancedSearch->SearchValue2 = @$filter["y_L"];
		$this->L->AdvancedSearch->SearchOperator2 = @$filter["w_L"];
		$this->L->AdvancedSearch->Save();

		// Field M
		$this->M->AdvancedSearch->SearchValue = @$filter["x_M"];
		$this->M->AdvancedSearch->SearchOperator = @$filter["z_M"];
		$this->M->AdvancedSearch->SearchCondition = @$filter["v_M"];
		$this->M->AdvancedSearch->SearchValue2 = @$filter["y_M"];
		$this->M->AdvancedSearch->SearchOperator2 = @$filter["w_M"];
		$this->M->AdvancedSearch->Save();

		// Field N
		$this->N->AdvancedSearch->SearchValue = @$filter["x_N"];
		$this->N->AdvancedSearch->SearchOperator = @$filter["z_N"];
		$this->N->AdvancedSearch->SearchCondition = @$filter["v_N"];
		$this->N->AdvancedSearch->SearchValue2 = @$filter["y_N"];
		$this->N->AdvancedSearch->SearchOperator2 = @$filter["w_N"];
		$this->N->AdvancedSearch->Save();

		// Field O
		$this->O->AdvancedSearch->SearchValue = @$filter["x_O"];
		$this->O->AdvancedSearch->SearchOperator = @$filter["z_O"];
		$this->O->AdvancedSearch->SearchCondition = @$filter["v_O"];
		$this->O->AdvancedSearch->SearchValue2 = @$filter["y_O"];
		$this->O->AdvancedSearch->SearchOperator2 = @$filter["w_O"];
		$this->O->AdvancedSearch->Save();

		// Field P
		$this->P->AdvancedSearch->SearchValue = @$filter["x_P"];
		$this->P->AdvancedSearch->SearchOperator = @$filter["z_P"];
		$this->P->AdvancedSearch->SearchCondition = @$filter["v_P"];
		$this->P->AdvancedSearch->SearchValue2 = @$filter["y_P"];
		$this->P->AdvancedSearch->SearchOperator2 = @$filter["w_P"];
		$this->P->AdvancedSearch->Save();

		// Field Q
		$this->Q->AdvancedSearch->SearchValue = @$filter["x_Q"];
		$this->Q->AdvancedSearch->SearchOperator = @$filter["z_Q"];
		$this->Q->AdvancedSearch->SearchCondition = @$filter["v_Q"];
		$this->Q->AdvancedSearch->SearchValue2 = @$filter["y_Q"];
		$this->Q->AdvancedSearch->SearchOperator2 = @$filter["w_Q"];
		$this->Q->AdvancedSearch->Save();

		// Field R
		$this->R->AdvancedSearch->SearchValue = @$filter["x_R"];
		$this->R->AdvancedSearch->SearchOperator = @$filter["z_R"];
		$this->R->AdvancedSearch->SearchCondition = @$filter["v_R"];
		$this->R->AdvancedSearch->SearchValue2 = @$filter["y_R"];
		$this->R->AdvancedSearch->SearchOperator2 = @$filter["w_R"];
		$this->R->AdvancedSearch->Save();

		// Field S
		$this->S->AdvancedSearch->SearchValue = @$filter["x_S"];
		$this->S->AdvancedSearch->SearchOperator = @$filter["z_S"];
		$this->S->AdvancedSearch->SearchCondition = @$filter["v_S"];
		$this->S->AdvancedSearch->SearchValue2 = @$filter["y_S"];
		$this->S->AdvancedSearch->SearchOperator2 = @$filter["w_S"];
		$this->S->AdvancedSearch->Save();

		// Field T
		$this->T->AdvancedSearch->SearchValue = @$filter["x_T"];
		$this->T->AdvancedSearch->SearchOperator = @$filter["z_T"];
		$this->T->AdvancedSearch->SearchCondition = @$filter["v_T"];
		$this->T->AdvancedSearch->SearchValue2 = @$filter["y_T"];
		$this->T->AdvancedSearch->SearchOperator2 = @$filter["w_T"];
		$this->T->AdvancedSearch->Save();

		// Field U
		$this->U->AdvancedSearch->SearchValue = @$filter["x_U"];
		$this->U->AdvancedSearch->SearchOperator = @$filter["z_U"];
		$this->U->AdvancedSearch->SearchCondition = @$filter["v_U"];
		$this->U->AdvancedSearch->SearchValue2 = @$filter["y_U"];
		$this->U->AdvancedSearch->SearchOperator2 = @$filter["w_U"];
		$this->U->AdvancedSearch->Save();

		// Field V
		$this->V->AdvancedSearch->SearchValue = @$filter["x_V"];
		$this->V->AdvancedSearch->SearchOperator = @$filter["z_V"];
		$this->V->AdvancedSearch->SearchCondition = @$filter["v_V"];
		$this->V->AdvancedSearch->SearchValue2 = @$filter["y_V"];
		$this->V->AdvancedSearch->SearchOperator2 = @$filter["w_V"];
		$this->V->AdvancedSearch->Save();

		// Field W
		$this->W->AdvancedSearch->SearchValue = @$filter["x_W"];
		$this->W->AdvancedSearch->SearchOperator = @$filter["z_W"];
		$this->W->AdvancedSearch->SearchCondition = @$filter["v_W"];
		$this->W->AdvancedSearch->SearchValue2 = @$filter["y_W"];
		$this->W->AdvancedSearch->SearchOperator2 = @$filter["w_W"];
		$this->W->AdvancedSearch->Save();

		// Field X
		$this->X->AdvancedSearch->SearchValue = @$filter["x_X"];
		$this->X->AdvancedSearch->SearchOperator = @$filter["z_X"];
		$this->X->AdvancedSearch->SearchCondition = @$filter["v_X"];
		$this->X->AdvancedSearch->SearchValue2 = @$filter["y_X"];
		$this->X->AdvancedSearch->SearchOperator2 = @$filter["w_X"];
		$this->X->AdvancedSearch->Save();

		// Field Y
		$this->Y->AdvancedSearch->SearchValue = @$filter["x_Y"];
		$this->Y->AdvancedSearch->SearchOperator = @$filter["z_Y"];
		$this->Y->AdvancedSearch->SearchCondition = @$filter["v_Y"];
		$this->Y->AdvancedSearch->SearchValue2 = @$filter["y_Y"];
		$this->Y->AdvancedSearch->SearchOperator2 = @$filter["w_Y"];
		$this->Y->AdvancedSearch->Save();

		// Field Z
		$this->Z->AdvancedSearch->SearchValue = @$filter["x_Z"];
		$this->Z->AdvancedSearch->SearchOperator = @$filter["z_Z"];
		$this->Z->AdvancedSearch->SearchCondition = @$filter["v_Z"];
		$this->Z->AdvancedSearch->SearchValue2 = @$filter["y_Z"];
		$this->Z->AdvancedSearch->SearchOperator2 = @$filter["w_Z"];
		$this->Z->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->SHEET, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->A, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->B, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->C, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->D, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->E, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->F, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->G, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->H, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->_I, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->J, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->K, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->L, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->M, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->N, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->O, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->P, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Q, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->R, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->S, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->T, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->U, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->V, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->W, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->X, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Y, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Z, $arKeywords, $type);
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
			$this->UpdateSort($this->SHEET); // SHEET
			$this->UpdateSort($this->NOMOR); // NOMOR
			$this->UpdateSort($this->A); // A
			$this->UpdateSort($this->B); // B
			$this->UpdateSort($this->C); // C
			$this->UpdateSort($this->D); // D
			$this->UpdateSort($this->E); // E
			$this->UpdateSort($this->F); // F
			$this->UpdateSort($this->G); // G
			$this->UpdateSort($this->H); // H
			$this->UpdateSort($this->_I); // I
			$this->UpdateSort($this->J); // J
			$this->UpdateSort($this->K); // K
			$this->UpdateSort($this->L); // L
			$this->UpdateSort($this->M); // M
			$this->UpdateSort($this->N); // N
			$this->UpdateSort($this->O); // O
			$this->UpdateSort($this->P); // P
			$this->UpdateSort($this->Q); // Q
			$this->UpdateSort($this->R); // R
			$this->UpdateSort($this->S); // S
			$this->UpdateSort($this->T); // T
			$this->UpdateSort($this->U); // U
			$this->UpdateSort($this->V); // V
			$this->UpdateSort($this->W); // W
			$this->UpdateSort($this->X); // X
			$this->UpdateSort($this->Y); // Y
			$this->UpdateSort($this->Z); // Z
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
				$this->SHEET->setSort("");
				$this->NOMOR->setSort("");
				$this->A->setSort("");
				$this->B->setSort("");
				$this->C->setSort("");
				$this->D->setSort("");
				$this->E->setSort("");
				$this->F->setSort("");
				$this->G->setSort("");
				$this->H->setSort("");
				$this->_I->setSort("");
				$this->J->setSort("");
				$this->K->setSort("");
				$this->L->setSort("");
				$this->M->setSort("");
				$this->N->setSort("");
				$this->O->setSort("");
				$this->P->setSort("");
				$this->Q->setSort("");
				$this->R->setSort("");
				$this->S->setSort("");
				$this->T->setSort("");
				$this->U->setSort("");
				$this->V->setSort("");
				$this->W->setSort("");
				$this->X->setSort("");
				$this->Y->setSort("");
				$this->Z->setSort("");
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->SHEET->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->NOMOR->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"ftexcellistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"ftexcellistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.ftexcellist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"ftexcellistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->SHEET->DbValue = $row['SHEET'];
		$this->NOMOR->DbValue = $row['NOMOR'];
		$this->A->DbValue = $row['A'];
		$this->B->DbValue = $row['B'];
		$this->C->DbValue = $row['C'];
		$this->D->DbValue = $row['D'];
		$this->E->DbValue = $row['E'];
		$this->F->DbValue = $row['F'];
		$this->G->DbValue = $row['G'];
		$this->H->DbValue = $row['H'];
		$this->_I->DbValue = $row['I'];
		$this->J->DbValue = $row['J'];
		$this->K->DbValue = $row['K'];
		$this->L->DbValue = $row['L'];
		$this->M->DbValue = $row['M'];
		$this->N->DbValue = $row['N'];
		$this->O->DbValue = $row['O'];
		$this->P->DbValue = $row['P'];
		$this->Q->DbValue = $row['Q'];
		$this->R->DbValue = $row['R'];
		$this->S->DbValue = $row['S'];
		$this->T->DbValue = $row['T'];
		$this->U->DbValue = $row['U'];
		$this->V->DbValue = $row['V'];
		$this->W->DbValue = $row['W'];
		$this->X->DbValue = $row['X'];
		$this->Y->DbValue = $row['Y'];
		$this->Z->DbValue = $row['Z'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("SHEET")) <> "")
			$this->SHEET->CurrentValue = $this->getKey("SHEET"); // SHEET
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("NOMOR")) <> "")
			$this->NOMOR->CurrentValue = $this->getKey("NOMOR"); // NOMOR
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

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
if (!isset($texcel_list)) $texcel_list = new ctexcel_list();

// Page init
$texcel_list->Page_Init();

// Page main
$texcel_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$texcel_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = ftexcellist = new ew_Form("ftexcellist", "list");
ftexcellist.FormKeyCountName = '<?php echo $texcel_list->FormKeyCountName ?>';

// Form_CustomValidate event
ftexcellist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftexcellist.ValidateRequired = true;
<?php } else { ?>
ftexcellist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = ftexcellistsrch = new ew_Form("ftexcellistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($texcel_list->TotalRecs > 0 && $texcel_list->ExportOptions->Visible()) { ?>
<?php $texcel_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($texcel_list->SearchOptions->Visible()) { ?>
<?php $texcel_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($texcel_list->FilterOptions->Visible()) { ?>
<?php $texcel_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $texcel_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($texcel_list->TotalRecs <= 0)
			$texcel_list->TotalRecs = $texcel->SelectRecordCount();
	} else {
		if (!$texcel_list->Recordset && ($texcel_list->Recordset = $texcel_list->LoadRecordset()))
			$texcel_list->TotalRecs = $texcel_list->Recordset->RecordCount();
	}
	$texcel_list->StartRec = 1;
	if ($texcel_list->DisplayRecs <= 0 || ($texcel->Export <> "" && $texcel->ExportAll)) // Display all records
		$texcel_list->DisplayRecs = $texcel_list->TotalRecs;
	if (!($texcel->Export <> "" && $texcel->ExportAll))
		$texcel_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$texcel_list->Recordset = $texcel_list->LoadRecordset($texcel_list->StartRec-1, $texcel_list->DisplayRecs);

	// Set no record found message
	if ($texcel->CurrentAction == "" && $texcel_list->TotalRecs == 0) {
		if ($texcel_list->SearchWhere == "0=101")
			$texcel_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$texcel_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$texcel_list->RenderOtherOptions();
?>
<?php if ($texcel->Export == "" && $texcel->CurrentAction == "") { ?>
<form name="ftexcellistsrch" id="ftexcellistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($texcel_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="ftexcellistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="texcel">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($texcel_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($texcel_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $texcel_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($texcel_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($texcel_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($texcel_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($texcel_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php $texcel_list->ShowPageHeader(); ?>
<?php
$texcel_list->ShowMessage();
?>
<?php if ($texcel_list->TotalRecs > 0 || $texcel->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid texcel">
<form name="ftexcellist" id="ftexcellist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($texcel_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $texcel_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="texcel">
<div id="gmp_texcel" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($texcel_list->TotalRecs > 0 || $texcel->CurrentAction == "gridedit") { ?>
<table id="tbl_texcellist" class="table ewTable">
<?php echo $texcel->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$texcel_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$texcel_list->RenderListOptions();

// Render list options (header, left)
$texcel_list->ListOptions->Render("header", "left");
?>
<?php if ($texcel->SHEET->Visible) { // SHEET ?>
	<?php if ($texcel->SortUrl($texcel->SHEET) == "") { ?>
		<th data-name="SHEET"><div id="elh_texcel_SHEET" class="texcel_SHEET"><div class="ewTableHeaderCaption"><?php echo $texcel->SHEET->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="SHEET"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $texcel->SortUrl($texcel->SHEET) ?>',1);"><div id="elh_texcel_SHEET" class="texcel_SHEET">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $texcel->SHEET->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($texcel->SHEET->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($texcel->SHEET->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($texcel->NOMOR->Visible) { // NOMOR ?>
	<?php if ($texcel->SortUrl($texcel->NOMOR) == "") { ?>
		<th data-name="NOMOR"><div id="elh_texcel_NOMOR" class="texcel_NOMOR"><div class="ewTableHeaderCaption"><?php echo $texcel->NOMOR->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="NOMOR"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $texcel->SortUrl($texcel->NOMOR) ?>',1);"><div id="elh_texcel_NOMOR" class="texcel_NOMOR">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $texcel->NOMOR->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($texcel->NOMOR->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($texcel->NOMOR->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($texcel->A->Visible) { // A ?>
	<?php if ($texcel->SortUrl($texcel->A) == "") { ?>
		<th data-name="A"><div id="elh_texcel_A" class="texcel_A"><div class="ewTableHeaderCaption"><?php echo $texcel->A->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="A"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $texcel->SortUrl($texcel->A) ?>',1);"><div id="elh_texcel_A" class="texcel_A">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $texcel->A->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($texcel->A->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($texcel->A->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($texcel->B->Visible) { // B ?>
	<?php if ($texcel->SortUrl($texcel->B) == "") { ?>
		<th data-name="B"><div id="elh_texcel_B" class="texcel_B"><div class="ewTableHeaderCaption"><?php echo $texcel->B->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="B"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $texcel->SortUrl($texcel->B) ?>',1);"><div id="elh_texcel_B" class="texcel_B">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $texcel->B->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($texcel->B->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($texcel->B->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($texcel->C->Visible) { // C ?>
	<?php if ($texcel->SortUrl($texcel->C) == "") { ?>
		<th data-name="C"><div id="elh_texcel_C" class="texcel_C"><div class="ewTableHeaderCaption"><?php echo $texcel->C->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="C"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $texcel->SortUrl($texcel->C) ?>',1);"><div id="elh_texcel_C" class="texcel_C">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $texcel->C->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($texcel->C->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($texcel->C->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($texcel->D->Visible) { // D ?>
	<?php if ($texcel->SortUrl($texcel->D) == "") { ?>
		<th data-name="D"><div id="elh_texcel_D" class="texcel_D"><div class="ewTableHeaderCaption"><?php echo $texcel->D->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="D"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $texcel->SortUrl($texcel->D) ?>',1);"><div id="elh_texcel_D" class="texcel_D">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $texcel->D->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($texcel->D->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($texcel->D->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($texcel->E->Visible) { // E ?>
	<?php if ($texcel->SortUrl($texcel->E) == "") { ?>
		<th data-name="E"><div id="elh_texcel_E" class="texcel_E"><div class="ewTableHeaderCaption"><?php echo $texcel->E->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="E"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $texcel->SortUrl($texcel->E) ?>',1);"><div id="elh_texcel_E" class="texcel_E">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $texcel->E->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($texcel->E->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($texcel->E->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($texcel->F->Visible) { // F ?>
	<?php if ($texcel->SortUrl($texcel->F) == "") { ?>
		<th data-name="F"><div id="elh_texcel_F" class="texcel_F"><div class="ewTableHeaderCaption"><?php echo $texcel->F->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="F"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $texcel->SortUrl($texcel->F) ?>',1);"><div id="elh_texcel_F" class="texcel_F">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $texcel->F->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($texcel->F->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($texcel->F->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($texcel->G->Visible) { // G ?>
	<?php if ($texcel->SortUrl($texcel->G) == "") { ?>
		<th data-name="G"><div id="elh_texcel_G" class="texcel_G"><div class="ewTableHeaderCaption"><?php echo $texcel->G->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="G"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $texcel->SortUrl($texcel->G) ?>',1);"><div id="elh_texcel_G" class="texcel_G">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $texcel->G->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($texcel->G->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($texcel->G->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($texcel->H->Visible) { // H ?>
	<?php if ($texcel->SortUrl($texcel->H) == "") { ?>
		<th data-name="H"><div id="elh_texcel_H" class="texcel_H"><div class="ewTableHeaderCaption"><?php echo $texcel->H->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="H"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $texcel->SortUrl($texcel->H) ?>',1);"><div id="elh_texcel_H" class="texcel_H">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $texcel->H->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($texcel->H->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($texcel->H->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($texcel->_I->Visible) { // I ?>
	<?php if ($texcel->SortUrl($texcel->_I) == "") { ?>
		<th data-name="_I"><div id="elh_texcel__I" class="texcel__I"><div class="ewTableHeaderCaption"><?php echo $texcel->_I->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_I"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $texcel->SortUrl($texcel->_I) ?>',1);"><div id="elh_texcel__I" class="texcel__I">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $texcel->_I->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($texcel->_I->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($texcel->_I->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($texcel->J->Visible) { // J ?>
	<?php if ($texcel->SortUrl($texcel->J) == "") { ?>
		<th data-name="J"><div id="elh_texcel_J" class="texcel_J"><div class="ewTableHeaderCaption"><?php echo $texcel->J->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="J"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $texcel->SortUrl($texcel->J) ?>',1);"><div id="elh_texcel_J" class="texcel_J">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $texcel->J->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($texcel->J->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($texcel->J->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($texcel->K->Visible) { // K ?>
	<?php if ($texcel->SortUrl($texcel->K) == "") { ?>
		<th data-name="K"><div id="elh_texcel_K" class="texcel_K"><div class="ewTableHeaderCaption"><?php echo $texcel->K->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="K"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $texcel->SortUrl($texcel->K) ?>',1);"><div id="elh_texcel_K" class="texcel_K">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $texcel->K->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($texcel->K->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($texcel->K->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($texcel->L->Visible) { // L ?>
	<?php if ($texcel->SortUrl($texcel->L) == "") { ?>
		<th data-name="L"><div id="elh_texcel_L" class="texcel_L"><div class="ewTableHeaderCaption"><?php echo $texcel->L->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="L"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $texcel->SortUrl($texcel->L) ?>',1);"><div id="elh_texcel_L" class="texcel_L">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $texcel->L->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($texcel->L->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($texcel->L->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($texcel->M->Visible) { // M ?>
	<?php if ($texcel->SortUrl($texcel->M) == "") { ?>
		<th data-name="M"><div id="elh_texcel_M" class="texcel_M"><div class="ewTableHeaderCaption"><?php echo $texcel->M->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="M"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $texcel->SortUrl($texcel->M) ?>',1);"><div id="elh_texcel_M" class="texcel_M">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $texcel->M->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($texcel->M->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($texcel->M->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($texcel->N->Visible) { // N ?>
	<?php if ($texcel->SortUrl($texcel->N) == "") { ?>
		<th data-name="N"><div id="elh_texcel_N" class="texcel_N"><div class="ewTableHeaderCaption"><?php echo $texcel->N->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="N"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $texcel->SortUrl($texcel->N) ?>',1);"><div id="elh_texcel_N" class="texcel_N">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $texcel->N->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($texcel->N->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($texcel->N->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($texcel->O->Visible) { // O ?>
	<?php if ($texcel->SortUrl($texcel->O) == "") { ?>
		<th data-name="O"><div id="elh_texcel_O" class="texcel_O"><div class="ewTableHeaderCaption"><?php echo $texcel->O->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="O"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $texcel->SortUrl($texcel->O) ?>',1);"><div id="elh_texcel_O" class="texcel_O">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $texcel->O->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($texcel->O->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($texcel->O->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($texcel->P->Visible) { // P ?>
	<?php if ($texcel->SortUrl($texcel->P) == "") { ?>
		<th data-name="P"><div id="elh_texcel_P" class="texcel_P"><div class="ewTableHeaderCaption"><?php echo $texcel->P->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="P"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $texcel->SortUrl($texcel->P) ?>',1);"><div id="elh_texcel_P" class="texcel_P">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $texcel->P->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($texcel->P->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($texcel->P->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($texcel->Q->Visible) { // Q ?>
	<?php if ($texcel->SortUrl($texcel->Q) == "") { ?>
		<th data-name="Q"><div id="elh_texcel_Q" class="texcel_Q"><div class="ewTableHeaderCaption"><?php echo $texcel->Q->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Q"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $texcel->SortUrl($texcel->Q) ?>',1);"><div id="elh_texcel_Q" class="texcel_Q">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $texcel->Q->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($texcel->Q->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($texcel->Q->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($texcel->R->Visible) { // R ?>
	<?php if ($texcel->SortUrl($texcel->R) == "") { ?>
		<th data-name="R"><div id="elh_texcel_R" class="texcel_R"><div class="ewTableHeaderCaption"><?php echo $texcel->R->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="R"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $texcel->SortUrl($texcel->R) ?>',1);"><div id="elh_texcel_R" class="texcel_R">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $texcel->R->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($texcel->R->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($texcel->R->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($texcel->S->Visible) { // S ?>
	<?php if ($texcel->SortUrl($texcel->S) == "") { ?>
		<th data-name="S"><div id="elh_texcel_S" class="texcel_S"><div class="ewTableHeaderCaption"><?php echo $texcel->S->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="S"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $texcel->SortUrl($texcel->S) ?>',1);"><div id="elh_texcel_S" class="texcel_S">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $texcel->S->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($texcel->S->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($texcel->S->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($texcel->T->Visible) { // T ?>
	<?php if ($texcel->SortUrl($texcel->T) == "") { ?>
		<th data-name="T"><div id="elh_texcel_T" class="texcel_T"><div class="ewTableHeaderCaption"><?php echo $texcel->T->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="T"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $texcel->SortUrl($texcel->T) ?>',1);"><div id="elh_texcel_T" class="texcel_T">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $texcel->T->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($texcel->T->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($texcel->T->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($texcel->U->Visible) { // U ?>
	<?php if ($texcel->SortUrl($texcel->U) == "") { ?>
		<th data-name="U"><div id="elh_texcel_U" class="texcel_U"><div class="ewTableHeaderCaption"><?php echo $texcel->U->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="U"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $texcel->SortUrl($texcel->U) ?>',1);"><div id="elh_texcel_U" class="texcel_U">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $texcel->U->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($texcel->U->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($texcel->U->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($texcel->V->Visible) { // V ?>
	<?php if ($texcel->SortUrl($texcel->V) == "") { ?>
		<th data-name="V"><div id="elh_texcel_V" class="texcel_V"><div class="ewTableHeaderCaption"><?php echo $texcel->V->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="V"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $texcel->SortUrl($texcel->V) ?>',1);"><div id="elh_texcel_V" class="texcel_V">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $texcel->V->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($texcel->V->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($texcel->V->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($texcel->W->Visible) { // W ?>
	<?php if ($texcel->SortUrl($texcel->W) == "") { ?>
		<th data-name="W"><div id="elh_texcel_W" class="texcel_W"><div class="ewTableHeaderCaption"><?php echo $texcel->W->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="W"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $texcel->SortUrl($texcel->W) ?>',1);"><div id="elh_texcel_W" class="texcel_W">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $texcel->W->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($texcel->W->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($texcel->W->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($texcel->X->Visible) { // X ?>
	<?php if ($texcel->SortUrl($texcel->X) == "") { ?>
		<th data-name="X"><div id="elh_texcel_X" class="texcel_X"><div class="ewTableHeaderCaption"><?php echo $texcel->X->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="X"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $texcel->SortUrl($texcel->X) ?>',1);"><div id="elh_texcel_X" class="texcel_X">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $texcel->X->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($texcel->X->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($texcel->X->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($texcel->Y->Visible) { // Y ?>
	<?php if ($texcel->SortUrl($texcel->Y) == "") { ?>
		<th data-name="Y"><div id="elh_texcel_Y" class="texcel_Y"><div class="ewTableHeaderCaption"><?php echo $texcel->Y->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Y"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $texcel->SortUrl($texcel->Y) ?>',1);"><div id="elh_texcel_Y" class="texcel_Y">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $texcel->Y->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($texcel->Y->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($texcel->Y->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($texcel->Z->Visible) { // Z ?>
	<?php if ($texcel->SortUrl($texcel->Z) == "") { ?>
		<th data-name="Z"><div id="elh_texcel_Z" class="texcel_Z"><div class="ewTableHeaderCaption"><?php echo $texcel->Z->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Z"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $texcel->SortUrl($texcel->Z) ?>',1);"><div id="elh_texcel_Z" class="texcel_Z">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $texcel->Z->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($texcel->Z->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($texcel->Z->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$texcel_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($texcel->ExportAll && $texcel->Export <> "") {
	$texcel_list->StopRec = $texcel_list->TotalRecs;
} else {

	// Set the last record to display
	if ($texcel_list->TotalRecs > $texcel_list->StartRec + $texcel_list->DisplayRecs - 1)
		$texcel_list->StopRec = $texcel_list->StartRec + $texcel_list->DisplayRecs - 1;
	else
		$texcel_list->StopRec = $texcel_list->TotalRecs;
}
$texcel_list->RecCnt = $texcel_list->StartRec - 1;
if ($texcel_list->Recordset && !$texcel_list->Recordset->EOF) {
	$texcel_list->Recordset->MoveFirst();
	$bSelectLimit = $texcel_list->UseSelectLimit;
	if (!$bSelectLimit && $texcel_list->StartRec > 1)
		$texcel_list->Recordset->Move($texcel_list->StartRec - 1);
} elseif (!$texcel->AllowAddDeleteRow && $texcel_list->StopRec == 0) {
	$texcel_list->StopRec = $texcel->GridAddRowCount;
}

// Initialize aggregate
$texcel->RowType = EW_ROWTYPE_AGGREGATEINIT;
$texcel->ResetAttrs();
$texcel_list->RenderRow();
while ($texcel_list->RecCnt < $texcel_list->StopRec) {
	$texcel_list->RecCnt++;
	if (intval($texcel_list->RecCnt) >= intval($texcel_list->StartRec)) {
		$texcel_list->RowCnt++;

		// Set up key count
		$texcel_list->KeyCount = $texcel_list->RowIndex;

		// Init row class and style
		$texcel->ResetAttrs();
		$texcel->CssClass = "";
		if ($texcel->CurrentAction == "gridadd") {
		} else {
			$texcel_list->LoadRowValues($texcel_list->Recordset); // Load row values
		}
		$texcel->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$texcel->RowAttrs = array_merge($texcel->RowAttrs, array('data-rowindex'=>$texcel_list->RowCnt, 'id'=>'r' . $texcel_list->RowCnt . '_texcel', 'data-rowtype'=>$texcel->RowType));

		// Render row
		$texcel_list->RenderRow();

		// Render list options
		$texcel_list->RenderListOptions();
?>
	<tr<?php echo $texcel->RowAttributes() ?>>
<?php

// Render list options (body, left)
$texcel_list->ListOptions->Render("body", "left", $texcel_list->RowCnt);
?>
	<?php if ($texcel->SHEET->Visible) { // SHEET ?>
		<td data-name="SHEET"<?php echo $texcel->SHEET->CellAttributes() ?>>
<span id="el<?php echo $texcel_list->RowCnt ?>_texcel_SHEET" class="texcel_SHEET">
<span<?php echo $texcel->SHEET->ViewAttributes() ?>>
<?php echo $texcel->SHEET->ListViewValue() ?></span>
</span>
<a id="<?php echo $texcel_list->PageObjName . "_row_" . $texcel_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($texcel->NOMOR->Visible) { // NOMOR ?>
		<td data-name="NOMOR"<?php echo $texcel->NOMOR->CellAttributes() ?>>
<span id="el<?php echo $texcel_list->RowCnt ?>_texcel_NOMOR" class="texcel_NOMOR">
<span<?php echo $texcel->NOMOR->ViewAttributes() ?>>
<?php echo $texcel->NOMOR->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($texcel->A->Visible) { // A ?>
		<td data-name="A"<?php echo $texcel->A->CellAttributes() ?>>
<span id="el<?php echo $texcel_list->RowCnt ?>_texcel_A" class="texcel_A">
<span<?php echo $texcel->A->ViewAttributes() ?>>
<?php echo $texcel->A->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($texcel->B->Visible) { // B ?>
		<td data-name="B"<?php echo $texcel->B->CellAttributes() ?>>
<span id="el<?php echo $texcel_list->RowCnt ?>_texcel_B" class="texcel_B">
<span<?php echo $texcel->B->ViewAttributes() ?>>
<?php echo $texcel->B->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($texcel->C->Visible) { // C ?>
		<td data-name="C"<?php echo $texcel->C->CellAttributes() ?>>
<span id="el<?php echo $texcel_list->RowCnt ?>_texcel_C" class="texcel_C">
<span<?php echo $texcel->C->ViewAttributes() ?>>
<?php echo $texcel->C->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($texcel->D->Visible) { // D ?>
		<td data-name="D"<?php echo $texcel->D->CellAttributes() ?>>
<span id="el<?php echo $texcel_list->RowCnt ?>_texcel_D" class="texcel_D">
<span<?php echo $texcel->D->ViewAttributes() ?>>
<?php echo $texcel->D->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($texcel->E->Visible) { // E ?>
		<td data-name="E"<?php echo $texcel->E->CellAttributes() ?>>
<span id="el<?php echo $texcel_list->RowCnt ?>_texcel_E" class="texcel_E">
<span<?php echo $texcel->E->ViewAttributes() ?>>
<?php echo $texcel->E->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($texcel->F->Visible) { // F ?>
		<td data-name="F"<?php echo $texcel->F->CellAttributes() ?>>
<span id="el<?php echo $texcel_list->RowCnt ?>_texcel_F" class="texcel_F">
<span<?php echo $texcel->F->ViewAttributes() ?>>
<?php echo $texcel->F->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($texcel->G->Visible) { // G ?>
		<td data-name="G"<?php echo $texcel->G->CellAttributes() ?>>
<span id="el<?php echo $texcel_list->RowCnt ?>_texcel_G" class="texcel_G">
<span<?php echo $texcel->G->ViewAttributes() ?>>
<?php echo $texcel->G->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($texcel->H->Visible) { // H ?>
		<td data-name="H"<?php echo $texcel->H->CellAttributes() ?>>
<span id="el<?php echo $texcel_list->RowCnt ?>_texcel_H" class="texcel_H">
<span<?php echo $texcel->H->ViewAttributes() ?>>
<?php echo $texcel->H->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($texcel->_I->Visible) { // I ?>
		<td data-name="_I"<?php echo $texcel->_I->CellAttributes() ?>>
<span id="el<?php echo $texcel_list->RowCnt ?>_texcel__I" class="texcel__I">
<span<?php echo $texcel->_I->ViewAttributes() ?>>
<?php echo $texcel->_I->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($texcel->J->Visible) { // J ?>
		<td data-name="J"<?php echo $texcel->J->CellAttributes() ?>>
<span id="el<?php echo $texcel_list->RowCnt ?>_texcel_J" class="texcel_J">
<span<?php echo $texcel->J->ViewAttributes() ?>>
<?php echo $texcel->J->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($texcel->K->Visible) { // K ?>
		<td data-name="K"<?php echo $texcel->K->CellAttributes() ?>>
<span id="el<?php echo $texcel_list->RowCnt ?>_texcel_K" class="texcel_K">
<span<?php echo $texcel->K->ViewAttributes() ?>>
<?php echo $texcel->K->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($texcel->L->Visible) { // L ?>
		<td data-name="L"<?php echo $texcel->L->CellAttributes() ?>>
<span id="el<?php echo $texcel_list->RowCnt ?>_texcel_L" class="texcel_L">
<span<?php echo $texcel->L->ViewAttributes() ?>>
<?php echo $texcel->L->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($texcel->M->Visible) { // M ?>
		<td data-name="M"<?php echo $texcel->M->CellAttributes() ?>>
<span id="el<?php echo $texcel_list->RowCnt ?>_texcel_M" class="texcel_M">
<span<?php echo $texcel->M->ViewAttributes() ?>>
<?php echo $texcel->M->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($texcel->N->Visible) { // N ?>
		<td data-name="N"<?php echo $texcel->N->CellAttributes() ?>>
<span id="el<?php echo $texcel_list->RowCnt ?>_texcel_N" class="texcel_N">
<span<?php echo $texcel->N->ViewAttributes() ?>>
<?php echo $texcel->N->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($texcel->O->Visible) { // O ?>
		<td data-name="O"<?php echo $texcel->O->CellAttributes() ?>>
<span id="el<?php echo $texcel_list->RowCnt ?>_texcel_O" class="texcel_O">
<span<?php echo $texcel->O->ViewAttributes() ?>>
<?php echo $texcel->O->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($texcel->P->Visible) { // P ?>
		<td data-name="P"<?php echo $texcel->P->CellAttributes() ?>>
<span id="el<?php echo $texcel_list->RowCnt ?>_texcel_P" class="texcel_P">
<span<?php echo $texcel->P->ViewAttributes() ?>>
<?php echo $texcel->P->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($texcel->Q->Visible) { // Q ?>
		<td data-name="Q"<?php echo $texcel->Q->CellAttributes() ?>>
<span id="el<?php echo $texcel_list->RowCnt ?>_texcel_Q" class="texcel_Q">
<span<?php echo $texcel->Q->ViewAttributes() ?>>
<?php echo $texcel->Q->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($texcel->R->Visible) { // R ?>
		<td data-name="R"<?php echo $texcel->R->CellAttributes() ?>>
<span id="el<?php echo $texcel_list->RowCnt ?>_texcel_R" class="texcel_R">
<span<?php echo $texcel->R->ViewAttributes() ?>>
<?php echo $texcel->R->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($texcel->S->Visible) { // S ?>
		<td data-name="S"<?php echo $texcel->S->CellAttributes() ?>>
<span id="el<?php echo $texcel_list->RowCnt ?>_texcel_S" class="texcel_S">
<span<?php echo $texcel->S->ViewAttributes() ?>>
<?php echo $texcel->S->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($texcel->T->Visible) { // T ?>
		<td data-name="T"<?php echo $texcel->T->CellAttributes() ?>>
<span id="el<?php echo $texcel_list->RowCnt ?>_texcel_T" class="texcel_T">
<span<?php echo $texcel->T->ViewAttributes() ?>>
<?php echo $texcel->T->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($texcel->U->Visible) { // U ?>
		<td data-name="U"<?php echo $texcel->U->CellAttributes() ?>>
<span id="el<?php echo $texcel_list->RowCnt ?>_texcel_U" class="texcel_U">
<span<?php echo $texcel->U->ViewAttributes() ?>>
<?php echo $texcel->U->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($texcel->V->Visible) { // V ?>
		<td data-name="V"<?php echo $texcel->V->CellAttributes() ?>>
<span id="el<?php echo $texcel_list->RowCnt ?>_texcel_V" class="texcel_V">
<span<?php echo $texcel->V->ViewAttributes() ?>>
<?php echo $texcel->V->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($texcel->W->Visible) { // W ?>
		<td data-name="W"<?php echo $texcel->W->CellAttributes() ?>>
<span id="el<?php echo $texcel_list->RowCnt ?>_texcel_W" class="texcel_W">
<span<?php echo $texcel->W->ViewAttributes() ?>>
<?php echo $texcel->W->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($texcel->X->Visible) { // X ?>
		<td data-name="X"<?php echo $texcel->X->CellAttributes() ?>>
<span id="el<?php echo $texcel_list->RowCnt ?>_texcel_X" class="texcel_X">
<span<?php echo $texcel->X->ViewAttributes() ?>>
<?php echo $texcel->X->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($texcel->Y->Visible) { // Y ?>
		<td data-name="Y"<?php echo $texcel->Y->CellAttributes() ?>>
<span id="el<?php echo $texcel_list->RowCnt ?>_texcel_Y" class="texcel_Y">
<span<?php echo $texcel->Y->ViewAttributes() ?>>
<?php echo $texcel->Y->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($texcel->Z->Visible) { // Z ?>
		<td data-name="Z"<?php echo $texcel->Z->CellAttributes() ?>>
<span id="el<?php echo $texcel_list->RowCnt ?>_texcel_Z" class="texcel_Z">
<span<?php echo $texcel->Z->ViewAttributes() ?>>
<?php echo $texcel->Z->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$texcel_list->ListOptions->Render("body", "right", $texcel_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($texcel->CurrentAction <> "gridadd")
		$texcel_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($texcel->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($texcel_list->Recordset)
	$texcel_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($texcel->CurrentAction <> "gridadd" && $texcel->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($texcel_list->Pager)) $texcel_list->Pager = new cPrevNextPager($texcel_list->StartRec, $texcel_list->DisplayRecs, $texcel_list->TotalRecs) ?>
<?php if ($texcel_list->Pager->RecordCount > 0 && $texcel_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($texcel_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $texcel_list->PageUrl() ?>start=<?php echo $texcel_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($texcel_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $texcel_list->PageUrl() ?>start=<?php echo $texcel_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $texcel_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($texcel_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $texcel_list->PageUrl() ?>start=<?php echo $texcel_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($texcel_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $texcel_list->PageUrl() ?>start=<?php echo $texcel_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $texcel_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $texcel_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $texcel_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $texcel_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($texcel_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($texcel_list->TotalRecs == 0 && $texcel->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($texcel_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
ftexcellistsrch.FilterList = <?php echo $texcel_list->GetFilterList() ?>;
ftexcellistsrch.Init();
ftexcellist.Init();
</script>
<?php
$texcel_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$texcel_list->Page_Terminate();
?>
