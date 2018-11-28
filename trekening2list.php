<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "trekening2info.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$trekening2_list = NULL; // Initialize page object first

class ctrekening2_list extends ctrekening2 {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'trekening2';

	// Page object name
	var $PageObjName = 'trekening2_list';

	// Grid form hidden field names
	var $FormName = 'ftrekening2list';
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

		// Table object (trekening2)
		if (!isset($GLOBALS["trekening2"]) || get_class($GLOBALS["trekening2"]) == "ctrekening2") {
			$GLOBALS["trekening2"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["trekening2"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "trekening2add.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "trekening2delete.php";
		$this->MultiUpdateUrl = "trekening2update.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'trekening2', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption ftrekening2listsrch";

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
		$this->parent1->SetVisibility();
		$this->id1->SetVisibility();
		$this->rekening1->SetVisibility();
		$this->parent2->SetVisibility();
		$this->id2->SetVisibility();
		$this->rekening2->SetVisibility();
		$this->tipe->SetVisibility();
		$this->posisi->SetVisibility();
		$this->laporan->SetVisibility();
		$this->status->SetVisibility();
		$this->keterangan->SetVisibility();
		$this->active->SetVisibility();

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
		global $EW_EXPORT, $trekening2;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($trekening2);
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
		if (count($arrKeyFlds) >= 1) {
			$this->id->setFormValue($arrKeyFlds[0]);
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = isset($UserProfile) ? $UserProfile->GetSearchFilters(CurrentUserName(), "ftrekening2listsrch") : "";
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->group->AdvancedSearch->ToJSON(), ","); // Field group
		$sFilterList = ew_Concat($sFilterList, $this->id->AdvancedSearch->ToJSON(), ","); // Field id
		$sFilterList = ew_Concat($sFilterList, $this->rekening->AdvancedSearch->ToJSON(), ","); // Field rekening
		$sFilterList = ew_Concat($sFilterList, $this->parent->AdvancedSearch->ToJSON(), ","); // Field parent
		$sFilterList = ew_Concat($sFilterList, $this->parent1->AdvancedSearch->ToJSON(), ","); // Field parent1
		$sFilterList = ew_Concat($sFilterList, $this->id1->AdvancedSearch->ToJSON(), ","); // Field id1
		$sFilterList = ew_Concat($sFilterList, $this->rekening1->AdvancedSearch->ToJSON(), ","); // Field rekening1
		$sFilterList = ew_Concat($sFilterList, $this->parent2->AdvancedSearch->ToJSON(), ","); // Field parent2
		$sFilterList = ew_Concat($sFilterList, $this->id2->AdvancedSearch->ToJSON(), ","); // Field id2
		$sFilterList = ew_Concat($sFilterList, $this->rekening2->AdvancedSearch->ToJSON(), ","); // Field rekening2
		$sFilterList = ew_Concat($sFilterList, $this->tipe->AdvancedSearch->ToJSON(), ","); // Field tipe
		$sFilterList = ew_Concat($sFilterList, $this->posisi->AdvancedSearch->ToJSON(), ","); // Field posisi
		$sFilterList = ew_Concat($sFilterList, $this->laporan->AdvancedSearch->ToJSON(), ","); // Field laporan
		$sFilterList = ew_Concat($sFilterList, $this->status->AdvancedSearch->ToJSON(), ","); // Field status
		$sFilterList = ew_Concat($sFilterList, $this->keterangan->AdvancedSearch->ToJSON(), ","); // Field keterangan
		$sFilterList = ew_Concat($sFilterList, $this->active->AdvancedSearch->ToJSON(), ","); // Field active
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "ftrekening2listsrch", $filters);

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

		// Field group
		$this->group->AdvancedSearch->SearchValue = @$filter["x_group"];
		$this->group->AdvancedSearch->SearchOperator = @$filter["z_group"];
		$this->group->AdvancedSearch->SearchCondition = @$filter["v_group"];
		$this->group->AdvancedSearch->SearchValue2 = @$filter["y_group"];
		$this->group->AdvancedSearch->SearchOperator2 = @$filter["w_group"];
		$this->group->AdvancedSearch->Save();

		// Field id
		$this->id->AdvancedSearch->SearchValue = @$filter["x_id"];
		$this->id->AdvancedSearch->SearchOperator = @$filter["z_id"];
		$this->id->AdvancedSearch->SearchCondition = @$filter["v_id"];
		$this->id->AdvancedSearch->SearchValue2 = @$filter["y_id"];
		$this->id->AdvancedSearch->SearchOperator2 = @$filter["w_id"];
		$this->id->AdvancedSearch->Save();

		// Field rekening
		$this->rekening->AdvancedSearch->SearchValue = @$filter["x_rekening"];
		$this->rekening->AdvancedSearch->SearchOperator = @$filter["z_rekening"];
		$this->rekening->AdvancedSearch->SearchCondition = @$filter["v_rekening"];
		$this->rekening->AdvancedSearch->SearchValue2 = @$filter["y_rekening"];
		$this->rekening->AdvancedSearch->SearchOperator2 = @$filter["w_rekening"];
		$this->rekening->AdvancedSearch->Save();

		// Field parent
		$this->parent->AdvancedSearch->SearchValue = @$filter["x_parent"];
		$this->parent->AdvancedSearch->SearchOperator = @$filter["z_parent"];
		$this->parent->AdvancedSearch->SearchCondition = @$filter["v_parent"];
		$this->parent->AdvancedSearch->SearchValue2 = @$filter["y_parent"];
		$this->parent->AdvancedSearch->SearchOperator2 = @$filter["w_parent"];
		$this->parent->AdvancedSearch->Save();

		// Field parent1
		$this->parent1->AdvancedSearch->SearchValue = @$filter["x_parent1"];
		$this->parent1->AdvancedSearch->SearchOperator = @$filter["z_parent1"];
		$this->parent1->AdvancedSearch->SearchCondition = @$filter["v_parent1"];
		$this->parent1->AdvancedSearch->SearchValue2 = @$filter["y_parent1"];
		$this->parent1->AdvancedSearch->SearchOperator2 = @$filter["w_parent1"];
		$this->parent1->AdvancedSearch->Save();

		// Field id1
		$this->id1->AdvancedSearch->SearchValue = @$filter["x_id1"];
		$this->id1->AdvancedSearch->SearchOperator = @$filter["z_id1"];
		$this->id1->AdvancedSearch->SearchCondition = @$filter["v_id1"];
		$this->id1->AdvancedSearch->SearchValue2 = @$filter["y_id1"];
		$this->id1->AdvancedSearch->SearchOperator2 = @$filter["w_id1"];
		$this->id1->AdvancedSearch->Save();

		// Field rekening1
		$this->rekening1->AdvancedSearch->SearchValue = @$filter["x_rekening1"];
		$this->rekening1->AdvancedSearch->SearchOperator = @$filter["z_rekening1"];
		$this->rekening1->AdvancedSearch->SearchCondition = @$filter["v_rekening1"];
		$this->rekening1->AdvancedSearch->SearchValue2 = @$filter["y_rekening1"];
		$this->rekening1->AdvancedSearch->SearchOperator2 = @$filter["w_rekening1"];
		$this->rekening1->AdvancedSearch->Save();

		// Field parent2
		$this->parent2->AdvancedSearch->SearchValue = @$filter["x_parent2"];
		$this->parent2->AdvancedSearch->SearchOperator = @$filter["z_parent2"];
		$this->parent2->AdvancedSearch->SearchCondition = @$filter["v_parent2"];
		$this->parent2->AdvancedSearch->SearchValue2 = @$filter["y_parent2"];
		$this->parent2->AdvancedSearch->SearchOperator2 = @$filter["w_parent2"];
		$this->parent2->AdvancedSearch->Save();

		// Field id2
		$this->id2->AdvancedSearch->SearchValue = @$filter["x_id2"];
		$this->id2->AdvancedSearch->SearchOperator = @$filter["z_id2"];
		$this->id2->AdvancedSearch->SearchCondition = @$filter["v_id2"];
		$this->id2->AdvancedSearch->SearchValue2 = @$filter["y_id2"];
		$this->id2->AdvancedSearch->SearchOperator2 = @$filter["w_id2"];
		$this->id2->AdvancedSearch->Save();

		// Field rekening2
		$this->rekening2->AdvancedSearch->SearchValue = @$filter["x_rekening2"];
		$this->rekening2->AdvancedSearch->SearchOperator = @$filter["z_rekening2"];
		$this->rekening2->AdvancedSearch->SearchCondition = @$filter["v_rekening2"];
		$this->rekening2->AdvancedSearch->SearchValue2 = @$filter["y_rekening2"];
		$this->rekening2->AdvancedSearch->SearchOperator2 = @$filter["w_rekening2"];
		$this->rekening2->AdvancedSearch->Save();

		// Field tipe
		$this->tipe->AdvancedSearch->SearchValue = @$filter["x_tipe"];
		$this->tipe->AdvancedSearch->SearchOperator = @$filter["z_tipe"];
		$this->tipe->AdvancedSearch->SearchCondition = @$filter["v_tipe"];
		$this->tipe->AdvancedSearch->SearchValue2 = @$filter["y_tipe"];
		$this->tipe->AdvancedSearch->SearchOperator2 = @$filter["w_tipe"];
		$this->tipe->AdvancedSearch->Save();

		// Field posisi
		$this->posisi->AdvancedSearch->SearchValue = @$filter["x_posisi"];
		$this->posisi->AdvancedSearch->SearchOperator = @$filter["z_posisi"];
		$this->posisi->AdvancedSearch->SearchCondition = @$filter["v_posisi"];
		$this->posisi->AdvancedSearch->SearchValue2 = @$filter["y_posisi"];
		$this->posisi->AdvancedSearch->SearchOperator2 = @$filter["w_posisi"];
		$this->posisi->AdvancedSearch->Save();

		// Field laporan
		$this->laporan->AdvancedSearch->SearchValue = @$filter["x_laporan"];
		$this->laporan->AdvancedSearch->SearchOperator = @$filter["z_laporan"];
		$this->laporan->AdvancedSearch->SearchCondition = @$filter["v_laporan"];
		$this->laporan->AdvancedSearch->SearchValue2 = @$filter["y_laporan"];
		$this->laporan->AdvancedSearch->SearchOperator2 = @$filter["w_laporan"];
		$this->laporan->AdvancedSearch->Save();

		// Field status
		$this->status->AdvancedSearch->SearchValue = @$filter["x_status"];
		$this->status->AdvancedSearch->SearchOperator = @$filter["z_status"];
		$this->status->AdvancedSearch->SearchCondition = @$filter["v_status"];
		$this->status->AdvancedSearch->SearchValue2 = @$filter["y_status"];
		$this->status->AdvancedSearch->SearchOperator2 = @$filter["w_status"];
		$this->status->AdvancedSearch->Save();

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
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		$this->BuildSearchSql($sWhere, $this->group, $Default, FALSE); // group
		$this->BuildSearchSql($sWhere, $this->id, $Default, FALSE); // id
		$this->BuildSearchSql($sWhere, $this->rekening, $Default, FALSE); // rekening
		$this->BuildSearchSql($sWhere, $this->parent, $Default, FALSE); // parent
		$this->BuildSearchSql($sWhere, $this->parent1, $Default, FALSE); // parent1
		$this->BuildSearchSql($sWhere, $this->id1, $Default, FALSE); // id1
		$this->BuildSearchSql($sWhere, $this->rekening1, $Default, FALSE); // rekening1
		$this->BuildSearchSql($sWhere, $this->parent2, $Default, FALSE); // parent2
		$this->BuildSearchSql($sWhere, $this->id2, $Default, FALSE); // id2
		$this->BuildSearchSql($sWhere, $this->rekening2, $Default, FALSE); // rekening2
		$this->BuildSearchSql($sWhere, $this->tipe, $Default, FALSE); // tipe
		$this->BuildSearchSql($sWhere, $this->posisi, $Default, FALSE); // posisi
		$this->BuildSearchSql($sWhere, $this->laporan, $Default, FALSE); // laporan
		$this->BuildSearchSql($sWhere, $this->status, $Default, FALSE); // status
		$this->BuildSearchSql($sWhere, $this->keterangan, $Default, FALSE); // keterangan
		$this->BuildSearchSql($sWhere, $this->active, $Default, FALSE); // active

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->group->AdvancedSearch->Save(); // group
			$this->id->AdvancedSearch->Save(); // id
			$this->rekening->AdvancedSearch->Save(); // rekening
			$this->parent->AdvancedSearch->Save(); // parent
			$this->parent1->AdvancedSearch->Save(); // parent1
			$this->id1->AdvancedSearch->Save(); // id1
			$this->rekening1->AdvancedSearch->Save(); // rekening1
			$this->parent2->AdvancedSearch->Save(); // parent2
			$this->id2->AdvancedSearch->Save(); // id2
			$this->rekening2->AdvancedSearch->Save(); // rekening2
			$this->tipe->AdvancedSearch->Save(); // tipe
			$this->posisi->AdvancedSearch->Save(); // posisi
			$this->laporan->AdvancedSearch->Save(); // laporan
			$this->status->AdvancedSearch->Save(); // status
			$this->keterangan->AdvancedSearch->Save(); // keterangan
			$this->active->AdvancedSearch->Save(); // active
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
		$this->BuildBasicSearchSQL($sWhere, $this->id, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->rekening, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->parent, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->parent1, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->id1, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->rekening1, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->parent2, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->id2, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->rekening2, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->tipe, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->posisi, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->laporan, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->status, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->keterangan, $arKeywords, $type);
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
		if ($this->group->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->rekening->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->parent->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->parent1->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id1->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->rekening1->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->parent2->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id2->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->rekening2->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->tipe->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->posisi->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->laporan->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->status->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->keterangan->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->active->AdvancedSearch->IssetSession())
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
		$this->group->AdvancedSearch->UnsetSession();
		$this->id->AdvancedSearch->UnsetSession();
		$this->rekening->AdvancedSearch->UnsetSession();
		$this->parent->AdvancedSearch->UnsetSession();
		$this->parent1->AdvancedSearch->UnsetSession();
		$this->id1->AdvancedSearch->UnsetSession();
		$this->rekening1->AdvancedSearch->UnsetSession();
		$this->parent2->AdvancedSearch->UnsetSession();
		$this->id2->AdvancedSearch->UnsetSession();
		$this->rekening2->AdvancedSearch->UnsetSession();
		$this->tipe->AdvancedSearch->UnsetSession();
		$this->posisi->AdvancedSearch->UnsetSession();
		$this->laporan->AdvancedSearch->UnsetSession();
		$this->status->AdvancedSearch->UnsetSession();
		$this->keterangan->AdvancedSearch->UnsetSession();
		$this->active->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->group->AdvancedSearch->Load();
		$this->id->AdvancedSearch->Load();
		$this->rekening->AdvancedSearch->Load();
		$this->parent->AdvancedSearch->Load();
		$this->parent1->AdvancedSearch->Load();
		$this->id1->AdvancedSearch->Load();
		$this->rekening1->AdvancedSearch->Load();
		$this->parent2->AdvancedSearch->Load();
		$this->id2->AdvancedSearch->Load();
		$this->rekening2->AdvancedSearch->Load();
		$this->tipe->AdvancedSearch->Load();
		$this->posisi->AdvancedSearch->Load();
		$this->laporan->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
		$this->keterangan->AdvancedSearch->Load();
		$this->active->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->parent1); // parent1
			$this->UpdateSort($this->id1); // id1
			$this->UpdateSort($this->rekening1); // rekening1
			$this->UpdateSort($this->parent2); // parent2
			$this->UpdateSort($this->id2); // id2
			$this->UpdateSort($this->rekening2); // rekening2
			$this->UpdateSort($this->tipe); // tipe
			$this->UpdateSort($this->posisi); // posisi
			$this->UpdateSort($this->laporan); // laporan
			$this->UpdateSort($this->status); // status
			$this->UpdateSort($this->keterangan); // keterangan
			$this->UpdateSort($this->active); // active
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
				$this->parent1->setSort("");
				$this->id1->setSort("");
				$this->rekening1->setSort("");
				$this->parent2->setSort("");
				$this->id2->setSort("");
				$this->rekening2->setSort("");
				$this->tipe->setSort("");
				$this->posisi->setSort("");
				$this->laporan->setSort("");
				$this->status->setSort("");
				$this->keterangan->setSort("");
				$this->active->setSort("");
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"ftrekening2listsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"ftrekening2listsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.ftrekening2list}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"ftrekening2listsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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
		// group

		$this->group->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_group"]);
		if ($this->group->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->group->AdvancedSearch->SearchOperator = @$_GET["z_group"];

		// id
		$this->id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id"]);
		if ($this->id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id->AdvancedSearch->SearchOperator = @$_GET["z_id"];

		// rekening
		$this->rekening->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_rekening"]);
		if ($this->rekening->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->rekening->AdvancedSearch->SearchOperator = @$_GET["z_rekening"];

		// parent
		$this->parent->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_parent"]);
		if ($this->parent->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->parent->AdvancedSearch->SearchOperator = @$_GET["z_parent"];

		// parent1
		$this->parent1->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_parent1"]);
		if ($this->parent1->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->parent1->AdvancedSearch->SearchOperator = @$_GET["z_parent1"];

		// id1
		$this->id1->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id1"]);
		if ($this->id1->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id1->AdvancedSearch->SearchOperator = @$_GET["z_id1"];

		// rekening1
		$this->rekening1->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_rekening1"]);
		if ($this->rekening1->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->rekening1->AdvancedSearch->SearchOperator = @$_GET["z_rekening1"];

		// parent2
		$this->parent2->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_parent2"]);
		if ($this->parent2->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->parent2->AdvancedSearch->SearchOperator = @$_GET["z_parent2"];

		// id2
		$this->id2->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id2"]);
		if ($this->id2->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id2->AdvancedSearch->SearchOperator = @$_GET["z_id2"];

		// rekening2
		$this->rekening2->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_rekening2"]);
		if ($this->rekening2->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->rekening2->AdvancedSearch->SearchOperator = @$_GET["z_rekening2"];

		// tipe
		$this->tipe->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_tipe"]);
		if ($this->tipe->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->tipe->AdvancedSearch->SearchOperator = @$_GET["z_tipe"];

		// posisi
		$this->posisi->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_posisi"]);
		if ($this->posisi->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->posisi->AdvancedSearch->SearchOperator = @$_GET["z_posisi"];

		// laporan
		$this->laporan->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_laporan"]);
		if ($this->laporan->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->laporan->AdvancedSearch->SearchOperator = @$_GET["z_laporan"];

		// status
		$this->status->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_status"]);
		if ($this->status->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->status->AdvancedSearch->SearchOperator = @$_GET["z_status"];

		// keterangan
		$this->keterangan->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_keterangan"]);
		if ($this->keterangan->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->keterangan->AdvancedSearch->SearchOperator = @$_GET["z_keterangan"];

		// active
		$this->active->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_active"]);
		if ($this->active->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->active->AdvancedSearch->SearchOperator = @$_GET["z_active"];
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
		$this->group->setDbValue($rs->fields('group'));
		$this->id->setDbValue($rs->fields('id'));
		$this->rekening->setDbValue($rs->fields('rekening'));
		$this->parent->setDbValue($rs->fields('parent'));
		$this->parent1->setDbValue($rs->fields('parent1'));
		$this->id1->setDbValue($rs->fields('id1'));
		$this->rekening1->setDbValue($rs->fields('rekening1'));
		$this->parent2->setDbValue($rs->fields('parent2'));
		$this->id2->setDbValue($rs->fields('id2'));
		$this->rekening2->setDbValue($rs->fields('rekening2'));
		$this->tipe->setDbValue($rs->fields('tipe'));
		$this->posisi->setDbValue($rs->fields('posisi'));
		$this->laporan->setDbValue($rs->fields('laporan'));
		$this->status->setDbValue($rs->fields('status'));
		$this->keterangan->setDbValue($rs->fields('keterangan'));
		$this->active->setDbValue($rs->fields('active'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->group->DbValue = $row['group'];
		$this->id->DbValue = $row['id'];
		$this->rekening->DbValue = $row['rekening'];
		$this->parent->DbValue = $row['parent'];
		$this->parent1->DbValue = $row['parent1'];
		$this->id1->DbValue = $row['id1'];
		$this->rekening1->DbValue = $row['rekening1'];
		$this->parent2->DbValue = $row['parent2'];
		$this->id2->DbValue = $row['id2'];
		$this->rekening2->DbValue = $row['rekening2'];
		$this->tipe->DbValue = $row['tipe'];
		$this->posisi->DbValue = $row['posisi'];
		$this->laporan->DbValue = $row['laporan'];
		$this->status->DbValue = $row['status'];
		$this->keterangan->DbValue = $row['keterangan'];
		$this->active->DbValue = $row['active'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
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
		// group
		// id
		// rekening
		// parent
		// parent1
		// id1
		// rekening1
		// parent2
		// id2
		// rekening2
		// tipe
		// posisi
		// laporan
		// status
		// keterangan
		// active

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// group
		$this->group->ViewValue = $this->group->CurrentValue;
		if (strval($this->group->CurrentValue) <> "") {
			$sFilterWrk = "`group`" . ew_SearchString("=", $this->group->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `group`, `rekening` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `trekening2`";
		$sWhereWrk = "";
		$this->group->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->group, $sWhereWrk); // Call Lookup selecting
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

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// rekening
		$this->rekening->ViewValue = $this->rekening->CurrentValue;
		$this->rekening->ViewCustomAttributes = "";

		// parent
		if (strval($this->parent->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->parent->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `id`, `rekening` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `trekening2`";
		$sWhereWrk = "";
		$this->parent->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->parent, $sWhereWrk); // Call Lookup selecting
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

		// parent1
		$this->parent1->ViewValue = $this->parent1->CurrentValue;
		if (strval($this->parent1->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->parent1->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `id`, `rekening` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `trekening2`";
		$sWhereWrk = "";
		$this->parent1->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->parent1, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->parent1->ViewValue = $this->parent1->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->parent1->ViewValue = $this->parent1->CurrentValue;
			}
		} else {
			$this->parent1->ViewValue = NULL;
		}
		$this->parent1->ViewCustomAttributes = "";

		// id1
		$this->id1->ViewValue = $this->id1->CurrentValue;
		$this->id1->ViewCustomAttributes = "";

		// rekening1
		$this->rekening1->ViewValue = $this->rekening1->CurrentValue;
		$this->rekening1->ViewCustomAttributes = "";

		// parent2
		$this->parent2->ViewValue = $this->parent2->CurrentValue;
		if (strval($this->parent2->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->parent2->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `id`, `rekening` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `trekening2`";
		$sWhereWrk = "";
		$this->parent2->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->parent2, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->parent2->ViewValue = $this->parent2->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->parent2->ViewValue = $this->parent2->CurrentValue;
			}
		} else {
			$this->parent2->ViewValue = NULL;
		}
		$this->parent2->ViewCustomAttributes = "";

		// id2
		$this->id2->ViewValue = $this->id2->CurrentValue;
		$this->id2->ViewCustomAttributes = "";

		// rekening2
		$this->rekening2->ViewValue = $this->rekening2->CurrentValue;
		$this->rekening2->ViewCustomAttributes = "";

		// tipe
		$this->tipe->ViewValue = $this->tipe->CurrentValue;
		$this->tipe->ViewCustomAttributes = "";

		// posisi
		$this->posisi->ViewValue = $this->posisi->CurrentValue;
		$this->posisi->ViewCustomAttributes = "";

		// laporan
		$this->laporan->ViewValue = $this->laporan->CurrentValue;
		$this->laporan->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
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

			// parent1
			$this->parent1->LinkCustomAttributes = "";
			$this->parent1->HrefValue = "";
			$this->parent1->TooltipValue = "";

			// id1
			$this->id1->LinkCustomAttributes = "";
			$this->id1->HrefValue = "";
			$this->id1->TooltipValue = "";

			// rekening1
			$this->rekening1->LinkCustomAttributes = "";
			$this->rekening1->HrefValue = "";
			$this->rekening1->TooltipValue = "";

			// parent2
			$this->parent2->LinkCustomAttributes = "";
			$this->parent2->HrefValue = "";
			$this->parent2->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// parent1
			$this->parent1->EditAttrs["class"] = "form-control";
			$this->parent1->EditCustomAttributes = "";
			$this->parent1->EditValue = ew_HtmlEncode($this->parent1->AdvancedSearch->SearchValue);
			$this->parent1->PlaceHolder = ew_RemoveHtml($this->parent1->FldCaption());

			// id1
			$this->id1->EditAttrs["class"] = "form-control";
			$this->id1->EditCustomAttributes = "";
			$this->id1->EditValue = ew_HtmlEncode($this->id1->AdvancedSearch->SearchValue);
			$this->id1->PlaceHolder = ew_RemoveHtml($this->id1->FldCaption());

			// rekening1
			$this->rekening1->EditAttrs["class"] = "form-control";
			$this->rekening1->EditCustomAttributes = "";
			$this->rekening1->EditValue = ew_HtmlEncode($this->rekening1->AdvancedSearch->SearchValue);
			$this->rekening1->PlaceHolder = ew_RemoveHtml($this->rekening1->FldCaption());

			// parent2
			$this->parent2->EditAttrs["class"] = "form-control";
			$this->parent2->EditCustomAttributes = "";
			$this->parent2->EditValue = ew_HtmlEncode($this->parent2->AdvancedSearch->SearchValue);
			$this->parent2->PlaceHolder = ew_RemoveHtml($this->parent2->FldCaption());

			// id2
			$this->id2->EditAttrs["class"] = "form-control";
			$this->id2->EditCustomAttributes = "";
			$this->id2->EditValue = ew_HtmlEncode($this->id2->AdvancedSearch->SearchValue);
			$this->id2->PlaceHolder = ew_RemoveHtml($this->id2->FldCaption());

			// rekening2
			$this->rekening2->EditAttrs["class"] = "form-control";
			$this->rekening2->EditCustomAttributes = "";
			$this->rekening2->EditValue = ew_HtmlEncode($this->rekening2->AdvancedSearch->SearchValue);
			$this->rekening2->PlaceHolder = ew_RemoveHtml($this->rekening2->FldCaption());

			// tipe
			$this->tipe->EditAttrs["class"] = "form-control";
			$this->tipe->EditCustomAttributes = "";
			$this->tipe->EditValue = ew_HtmlEncode($this->tipe->AdvancedSearch->SearchValue);
			$this->tipe->PlaceHolder = ew_RemoveHtml($this->tipe->FldCaption());

			// posisi
			$this->posisi->EditAttrs["class"] = "form-control";
			$this->posisi->EditCustomAttributes = "";
			$this->posisi->EditValue = ew_HtmlEncode($this->posisi->AdvancedSearch->SearchValue);
			$this->posisi->PlaceHolder = ew_RemoveHtml($this->posisi->FldCaption());

			// laporan
			$this->laporan->EditAttrs["class"] = "form-control";
			$this->laporan->EditCustomAttributes = "";
			$this->laporan->EditValue = ew_HtmlEncode($this->laporan->AdvancedSearch->SearchValue);
			$this->laporan->PlaceHolder = ew_RemoveHtml($this->laporan->FldCaption());

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = ew_HtmlEncode($this->status->AdvancedSearch->SearchValue);
			$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

			// keterangan
			$this->keterangan->EditAttrs["class"] = "form-control";
			$this->keterangan->EditCustomAttributes = "";
			$this->keterangan->EditValue = ew_HtmlEncode($this->keterangan->AdvancedSearch->SearchValue);
			$this->keterangan->PlaceHolder = ew_RemoveHtml($this->keterangan->FldCaption());

			// active
			$this->active->EditCustomAttributes = "";
			$this->active->EditValue = $this->active->Options(FALSE);
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
		$this->group->AdvancedSearch->Load();
		$this->id->AdvancedSearch->Load();
		$this->rekening->AdvancedSearch->Load();
		$this->parent->AdvancedSearch->Load();
		$this->parent1->AdvancedSearch->Load();
		$this->id1->AdvancedSearch->Load();
		$this->rekening1->AdvancedSearch->Load();
		$this->parent2->AdvancedSearch->Load();
		$this->id2->AdvancedSearch->Load();
		$this->rekening2->AdvancedSearch->Load();
		$this->tipe->AdvancedSearch->Load();
		$this->posisi->AdvancedSearch->Load();
		$this->laporan->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
		$this->keterangan->AdvancedSearch->Load();
		$this->active->AdvancedSearch->Load();
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
if (!isset($trekening2_list)) $trekening2_list = new ctrekening2_list();

// Page init
$trekening2_list->Page_Init();

// Page main
$trekening2_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$trekening2_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = ftrekening2list = new ew_Form("ftrekening2list", "list");
ftrekening2list.FormKeyCountName = '<?php echo $trekening2_list->FormKeyCountName ?>';

// Form_CustomValidate event
ftrekening2list.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftrekening2list.ValidateRequired = true;
<?php } else { ?>
ftrekening2list.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftrekening2list.Lists["x_parent1"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_rekening","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"trekening2"};
ftrekening2list.Lists["x_parent2"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_rekening","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"trekening2"};
ftrekening2list.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftrekening2list.Lists["x_active"].Options = <?php echo json_encode($trekening2->active->Options()) ?>;

// Form object for search
var CurrentSearchForm = ftrekening2listsrch = new ew_Form("ftrekening2listsrch");

// Validate function for search
ftrekening2listsrch.Validate = function(fobj) {
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
ftrekening2listsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftrekening2listsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
ftrekening2listsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
ftrekening2listsrch.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftrekening2listsrch.Lists["x_active"].Options = <?php echo json_encode($trekening2->active->Options()) ?>;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($trekening2_list->TotalRecs > 0 && $trekening2_list->ExportOptions->Visible()) { ?>
<?php $trekening2_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($trekening2_list->SearchOptions->Visible()) { ?>
<?php $trekening2_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($trekening2_list->FilterOptions->Visible()) { ?>
<?php $trekening2_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $trekening2_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($trekening2_list->TotalRecs <= 0)
			$trekening2_list->TotalRecs = $trekening2->SelectRecordCount();
	} else {
		if (!$trekening2_list->Recordset && ($trekening2_list->Recordset = $trekening2_list->LoadRecordset()))
			$trekening2_list->TotalRecs = $trekening2_list->Recordset->RecordCount();
	}
	$trekening2_list->StartRec = 1;
	if ($trekening2_list->DisplayRecs <= 0 || ($trekening2->Export <> "" && $trekening2->ExportAll)) // Display all records
		$trekening2_list->DisplayRecs = $trekening2_list->TotalRecs;
	if (!($trekening2->Export <> "" && $trekening2->ExportAll))
		$trekening2_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$trekening2_list->Recordset = $trekening2_list->LoadRecordset($trekening2_list->StartRec-1, $trekening2_list->DisplayRecs);

	// Set no record found message
	if ($trekening2->CurrentAction == "" && $trekening2_list->TotalRecs == 0) {
		if ($trekening2_list->SearchWhere == "0=101")
			$trekening2_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$trekening2_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$trekening2_list->RenderOtherOptions();
?>
<?php if ($trekening2->Export == "" && $trekening2->CurrentAction == "") { ?>
<form name="ftrekening2listsrch" id="ftrekening2listsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($trekening2_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="ftrekening2listsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="trekening2">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$trekening2_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$trekening2->RowType = EW_ROWTYPE_SEARCH;

// Render row
$trekening2->ResetAttrs();
$trekening2_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($trekening2->active->Visible) { // active ?>
	<div id="xsc_active" class="ewCell form-group">
		<label class="ewSearchCaption ewLabel"><?php echo $trekening2->active->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_active" id="z_active" value="="></span>
		<span class="ewSearchField">
<div id="tp_x_active" class="ewTemplate"><input type="radio" data-table="trekening2" data-field="x_active" data-value-separator="<?php echo $trekening2->active->DisplayValueSeparatorAttribute() ?>" name="x_active" id="x_active" value="{value}"<?php echo $trekening2->active->EditAttributes() ?>></div>
<div id="dsl_x_active" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $trekening2->active->RadioButtonListHtml(FALSE, "x_active") ?>
</div></div>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($trekening2_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($trekening2_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $trekening2_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($trekening2_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($trekening2_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($trekening2_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($trekening2_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php $trekening2_list->ShowPageHeader(); ?>
<?php
$trekening2_list->ShowMessage();
?>
<?php if ($trekening2_list->TotalRecs > 0 || $trekening2->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid trekening2">
<form name="ftrekening2list" id="ftrekening2list" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($trekening2_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $trekening2_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="trekening2">
<div id="gmp_trekening2" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($trekening2_list->TotalRecs > 0 || $trekening2->CurrentAction == "gridedit") { ?>
<table id="tbl_trekening2list" class="table ewTable">
<?php echo $trekening2->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$trekening2_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$trekening2_list->RenderListOptions();

// Render list options (header, left)
$trekening2_list->ListOptions->Render("header", "left");
?>
<?php if ($trekening2->parent1->Visible) { // parent1 ?>
	<?php if ($trekening2->SortUrl($trekening2->parent1) == "") { ?>
		<th data-name="parent1"><div id="elh_trekening2_parent1" class="trekening2_parent1"><div class="ewTableHeaderCaption"><?php echo $trekening2->parent1->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="parent1"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $trekening2->SortUrl($trekening2->parent1) ?>',1);"><div id="elh_trekening2_parent1" class="trekening2_parent1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $trekening2->parent1->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($trekening2->parent1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($trekening2->parent1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($trekening2->id1->Visible) { // id1 ?>
	<?php if ($trekening2->SortUrl($trekening2->id1) == "") { ?>
		<th data-name="id1"><div id="elh_trekening2_id1" class="trekening2_id1"><div class="ewTableHeaderCaption"><?php echo $trekening2->id1->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id1"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $trekening2->SortUrl($trekening2->id1) ?>',1);"><div id="elh_trekening2_id1" class="trekening2_id1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $trekening2->id1->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($trekening2->id1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($trekening2->id1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($trekening2->rekening1->Visible) { // rekening1 ?>
	<?php if ($trekening2->SortUrl($trekening2->rekening1) == "") { ?>
		<th data-name="rekening1"><div id="elh_trekening2_rekening1" class="trekening2_rekening1"><div class="ewTableHeaderCaption"><?php echo $trekening2->rekening1->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="rekening1"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $trekening2->SortUrl($trekening2->rekening1) ?>',1);"><div id="elh_trekening2_rekening1" class="trekening2_rekening1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $trekening2->rekening1->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($trekening2->rekening1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($trekening2->rekening1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($trekening2->parent2->Visible) { // parent2 ?>
	<?php if ($trekening2->SortUrl($trekening2->parent2) == "") { ?>
		<th data-name="parent2"><div id="elh_trekening2_parent2" class="trekening2_parent2"><div class="ewTableHeaderCaption"><?php echo $trekening2->parent2->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="parent2"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $trekening2->SortUrl($trekening2->parent2) ?>',1);"><div id="elh_trekening2_parent2" class="trekening2_parent2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $trekening2->parent2->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($trekening2->parent2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($trekening2->parent2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($trekening2->id2->Visible) { // id2 ?>
	<?php if ($trekening2->SortUrl($trekening2->id2) == "") { ?>
		<th data-name="id2"><div id="elh_trekening2_id2" class="trekening2_id2"><div class="ewTableHeaderCaption"><?php echo $trekening2->id2->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id2"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $trekening2->SortUrl($trekening2->id2) ?>',1);"><div id="elh_trekening2_id2" class="trekening2_id2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $trekening2->id2->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($trekening2->id2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($trekening2->id2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($trekening2->rekening2->Visible) { // rekening2 ?>
	<?php if ($trekening2->SortUrl($trekening2->rekening2) == "") { ?>
		<th data-name="rekening2"><div id="elh_trekening2_rekening2" class="trekening2_rekening2"><div class="ewTableHeaderCaption"><?php echo $trekening2->rekening2->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="rekening2"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $trekening2->SortUrl($trekening2->rekening2) ?>',1);"><div id="elh_trekening2_rekening2" class="trekening2_rekening2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $trekening2->rekening2->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($trekening2->rekening2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($trekening2->rekening2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($trekening2->tipe->Visible) { // tipe ?>
	<?php if ($trekening2->SortUrl($trekening2->tipe) == "") { ?>
		<th data-name="tipe"><div id="elh_trekening2_tipe" class="trekening2_tipe"><div class="ewTableHeaderCaption"><?php echo $trekening2->tipe->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipe"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $trekening2->SortUrl($trekening2->tipe) ?>',1);"><div id="elh_trekening2_tipe" class="trekening2_tipe">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $trekening2->tipe->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($trekening2->tipe->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($trekening2->tipe->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($trekening2->posisi->Visible) { // posisi ?>
	<?php if ($trekening2->SortUrl($trekening2->posisi) == "") { ?>
		<th data-name="posisi"><div id="elh_trekening2_posisi" class="trekening2_posisi"><div class="ewTableHeaderCaption"><?php echo $trekening2->posisi->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="posisi"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $trekening2->SortUrl($trekening2->posisi) ?>',1);"><div id="elh_trekening2_posisi" class="trekening2_posisi">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $trekening2->posisi->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($trekening2->posisi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($trekening2->posisi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($trekening2->laporan->Visible) { // laporan ?>
	<?php if ($trekening2->SortUrl($trekening2->laporan) == "") { ?>
		<th data-name="laporan"><div id="elh_trekening2_laporan" class="trekening2_laporan"><div class="ewTableHeaderCaption"><?php echo $trekening2->laporan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="laporan"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $trekening2->SortUrl($trekening2->laporan) ?>',1);"><div id="elh_trekening2_laporan" class="trekening2_laporan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $trekening2->laporan->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($trekening2->laporan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($trekening2->laporan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($trekening2->status->Visible) { // status ?>
	<?php if ($trekening2->SortUrl($trekening2->status) == "") { ?>
		<th data-name="status"><div id="elh_trekening2_status" class="trekening2_status"><div class="ewTableHeaderCaption"><?php echo $trekening2->status->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $trekening2->SortUrl($trekening2->status) ?>',1);"><div id="elh_trekening2_status" class="trekening2_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $trekening2->status->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($trekening2->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($trekening2->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($trekening2->keterangan->Visible) { // keterangan ?>
	<?php if ($trekening2->SortUrl($trekening2->keterangan) == "") { ?>
		<th data-name="keterangan"><div id="elh_trekening2_keterangan" class="trekening2_keterangan"><div class="ewTableHeaderCaption"><?php echo $trekening2->keterangan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="keterangan"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $trekening2->SortUrl($trekening2->keterangan) ?>',1);"><div id="elh_trekening2_keterangan" class="trekening2_keterangan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $trekening2->keterangan->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($trekening2->keterangan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($trekening2->keterangan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($trekening2->active->Visible) { // active ?>
	<?php if ($trekening2->SortUrl($trekening2->active) == "") { ?>
		<th data-name="active"><div id="elh_trekening2_active" class="trekening2_active"><div class="ewTableHeaderCaption"><?php echo $trekening2->active->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="active"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $trekening2->SortUrl($trekening2->active) ?>',1);"><div id="elh_trekening2_active" class="trekening2_active">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $trekening2->active->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($trekening2->active->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($trekening2->active->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$trekening2_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($trekening2->ExportAll && $trekening2->Export <> "") {
	$trekening2_list->StopRec = $trekening2_list->TotalRecs;
} else {

	// Set the last record to display
	if ($trekening2_list->TotalRecs > $trekening2_list->StartRec + $trekening2_list->DisplayRecs - 1)
		$trekening2_list->StopRec = $trekening2_list->StartRec + $trekening2_list->DisplayRecs - 1;
	else
		$trekening2_list->StopRec = $trekening2_list->TotalRecs;
}
$trekening2_list->RecCnt = $trekening2_list->StartRec - 1;
if ($trekening2_list->Recordset && !$trekening2_list->Recordset->EOF) {
	$trekening2_list->Recordset->MoveFirst();
	$bSelectLimit = $trekening2_list->UseSelectLimit;
	if (!$bSelectLimit && $trekening2_list->StartRec > 1)
		$trekening2_list->Recordset->Move($trekening2_list->StartRec - 1);
} elseif (!$trekening2->AllowAddDeleteRow && $trekening2_list->StopRec == 0) {
	$trekening2_list->StopRec = $trekening2->GridAddRowCount;
}

// Initialize aggregate
$trekening2->RowType = EW_ROWTYPE_AGGREGATEINIT;
$trekening2->ResetAttrs();
$trekening2_list->RenderRow();
while ($trekening2_list->RecCnt < $trekening2_list->StopRec) {
	$trekening2_list->RecCnt++;
	if (intval($trekening2_list->RecCnt) >= intval($trekening2_list->StartRec)) {
		$trekening2_list->RowCnt++;

		// Set up key count
		$trekening2_list->KeyCount = $trekening2_list->RowIndex;

		// Init row class and style
		$trekening2->ResetAttrs();
		$trekening2->CssClass = "";
		if ($trekening2->CurrentAction == "gridadd") {
		} else {
			$trekening2_list->LoadRowValues($trekening2_list->Recordset); // Load row values
		}
		$trekening2->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$trekening2->RowAttrs = array_merge($trekening2->RowAttrs, array('data-rowindex'=>$trekening2_list->RowCnt, 'id'=>'r' . $trekening2_list->RowCnt . '_trekening2', 'data-rowtype'=>$trekening2->RowType));

		// Render row
		$trekening2_list->RenderRow();

		// Render list options
		$trekening2_list->RenderListOptions();
?>
	<tr<?php echo $trekening2->RowAttributes() ?>>
<?php

// Render list options (body, left)
$trekening2_list->ListOptions->Render("body", "left", $trekening2_list->RowCnt);
?>
	<?php if ($trekening2->parent1->Visible) { // parent1 ?>
		<td data-name="parent1"<?php echo $trekening2->parent1->CellAttributes() ?>>
<span id="el<?php echo $trekening2_list->RowCnt ?>_trekening2_parent1" class="trekening2_parent1">
<span<?php echo $trekening2->parent1->ViewAttributes() ?>>
<?php echo $trekening2->parent1->ListViewValue() ?></span>
</span>
<a id="<?php echo $trekening2_list->PageObjName . "_row_" . $trekening2_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($trekening2->id1->Visible) { // id1 ?>
		<td data-name="id1"<?php echo $trekening2->id1->CellAttributes() ?>>
<span id="el<?php echo $trekening2_list->RowCnt ?>_trekening2_id1" class="trekening2_id1">
<span<?php echo $trekening2->id1->ViewAttributes() ?>>
<?php echo $trekening2->id1->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($trekening2->rekening1->Visible) { // rekening1 ?>
		<td data-name="rekening1"<?php echo $trekening2->rekening1->CellAttributes() ?>>
<span id="el<?php echo $trekening2_list->RowCnt ?>_trekening2_rekening1" class="trekening2_rekening1">
<span<?php echo $trekening2->rekening1->ViewAttributes() ?>>
<?php echo $trekening2->rekening1->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($trekening2->parent2->Visible) { // parent2 ?>
		<td data-name="parent2"<?php echo $trekening2->parent2->CellAttributes() ?>>
<span id="el<?php echo $trekening2_list->RowCnt ?>_trekening2_parent2" class="trekening2_parent2">
<span<?php echo $trekening2->parent2->ViewAttributes() ?>>
<?php echo $trekening2->parent2->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($trekening2->id2->Visible) { // id2 ?>
		<td data-name="id2"<?php echo $trekening2->id2->CellAttributes() ?>>
<span id="el<?php echo $trekening2_list->RowCnt ?>_trekening2_id2" class="trekening2_id2">
<span<?php echo $trekening2->id2->ViewAttributes() ?>>
<?php echo $trekening2->id2->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($trekening2->rekening2->Visible) { // rekening2 ?>
		<td data-name="rekening2"<?php echo $trekening2->rekening2->CellAttributes() ?>>
<span id="el<?php echo $trekening2_list->RowCnt ?>_trekening2_rekening2" class="trekening2_rekening2">
<span<?php echo $trekening2->rekening2->ViewAttributes() ?>>
<?php echo $trekening2->rekening2->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($trekening2->tipe->Visible) { // tipe ?>
		<td data-name="tipe"<?php echo $trekening2->tipe->CellAttributes() ?>>
<span id="el<?php echo $trekening2_list->RowCnt ?>_trekening2_tipe" class="trekening2_tipe">
<span<?php echo $trekening2->tipe->ViewAttributes() ?>>
<?php echo $trekening2->tipe->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($trekening2->posisi->Visible) { // posisi ?>
		<td data-name="posisi"<?php echo $trekening2->posisi->CellAttributes() ?>>
<span id="el<?php echo $trekening2_list->RowCnt ?>_trekening2_posisi" class="trekening2_posisi">
<span<?php echo $trekening2->posisi->ViewAttributes() ?>>
<?php echo $trekening2->posisi->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($trekening2->laporan->Visible) { // laporan ?>
		<td data-name="laporan"<?php echo $trekening2->laporan->CellAttributes() ?>>
<span id="el<?php echo $trekening2_list->RowCnt ?>_trekening2_laporan" class="trekening2_laporan">
<span<?php echo $trekening2->laporan->ViewAttributes() ?>>
<?php echo $trekening2->laporan->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($trekening2->status->Visible) { // status ?>
		<td data-name="status"<?php echo $trekening2->status->CellAttributes() ?>>
<span id="el<?php echo $trekening2_list->RowCnt ?>_trekening2_status" class="trekening2_status">
<span<?php echo $trekening2->status->ViewAttributes() ?>>
<?php echo $trekening2->status->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($trekening2->keterangan->Visible) { // keterangan ?>
		<td data-name="keterangan"<?php echo $trekening2->keterangan->CellAttributes() ?>>
<span id="el<?php echo $trekening2_list->RowCnt ?>_trekening2_keterangan" class="trekening2_keterangan">
<span<?php echo $trekening2->keterangan->ViewAttributes() ?>>
<?php echo $trekening2->keterangan->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($trekening2->active->Visible) { // active ?>
		<td data-name="active"<?php echo $trekening2->active->CellAttributes() ?>>
<span id="el<?php echo $trekening2_list->RowCnt ?>_trekening2_active" class="trekening2_active">
<span<?php echo $trekening2->active->ViewAttributes() ?>>
<?php echo $trekening2->active->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$trekening2_list->ListOptions->Render("body", "right", $trekening2_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($trekening2->CurrentAction <> "gridadd")
		$trekening2_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($trekening2->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($trekening2_list->Recordset)
	$trekening2_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($trekening2->CurrentAction <> "gridadd" && $trekening2->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($trekening2_list->Pager)) $trekening2_list->Pager = new cPrevNextPager($trekening2_list->StartRec, $trekening2_list->DisplayRecs, $trekening2_list->TotalRecs) ?>
<?php if ($trekening2_list->Pager->RecordCount > 0 && $trekening2_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($trekening2_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $trekening2_list->PageUrl() ?>start=<?php echo $trekening2_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($trekening2_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $trekening2_list->PageUrl() ?>start=<?php echo $trekening2_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $trekening2_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($trekening2_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $trekening2_list->PageUrl() ?>start=<?php echo $trekening2_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($trekening2_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $trekening2_list->PageUrl() ?>start=<?php echo $trekening2_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $trekening2_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $trekening2_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $trekening2_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $trekening2_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($trekening2_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($trekening2_list->TotalRecs == 0 && $trekening2->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($trekening2_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
ftrekening2listsrch.FilterList = <?php echo $trekening2_list->GetFilterList() ?>;
ftrekening2listsrch.Init();
ftrekening2list.Init();
</script>
<?php
$trekening2_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$trekening2_list->Page_Terminate();
?>