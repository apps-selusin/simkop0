<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "tlaporaninfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$tlaporan_list = NULL; // Initialize page object first

class ctlaporan_list extends ctlaporan {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tlaporan';

	// Page object name
	var $PageObjName = 'tlaporan_list';

	// Grid form hidden field names
	var $FormName = 'ftlaporanlist';
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

		// Table object (tlaporan)
		if (!isset($GLOBALS["tlaporan"]) || get_class($GLOBALS["tlaporan"]) == "ctlaporan") {
			$GLOBALS["tlaporan"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tlaporan"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "tlaporanadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "tlaporandelete.php";
		$this->MultiUpdateUrl = "tlaporanupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tlaporan', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption ftlaporanlistsrch";

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
		$this->group->SetVisibility();
		$this->rekening->SetVisibility();
		$this->tipe->SetVisibility();
		$this->posisi->SetVisibility();
		$this->laporan->SetVisibility();
		$this->keterangan->SetVisibility();
		$this->debet1->SetVisibility();
		$this->credit1->SetVisibility();
		$this->saldo1->SetVisibility();
		$this->debet2->SetVisibility();
		$this->credit2->SetVisibility();
		$this->saldo2->SetVisibility();
		$this->debet3->SetVisibility();
		$this->credit3->SetVisibility();
		$this->saldo3->SetVisibility();
		$this->debet4->SetVisibility();
		$this->credit4->SetVisibility();
		$this->saldo4->SetVisibility();
		$this->debet5->SetVisibility();
		$this->credit5->SetVisibility();
		$this->saldo5->SetVisibility();
		$this->debet6->SetVisibility();
		$this->credit6->SetVisibility();
		$this->saldo6->SetVisibility();
		$this->debet7->SetVisibility();
		$this->credit7->SetVisibility();
		$this->saldo7->SetVisibility();
		$this->debet8->SetVisibility();
		$this->credit8->SetVisibility();
		$this->saldo8->SetVisibility();
		$this->debet9->SetVisibility();
		$this->credit9->SetVisibility();
		$this->saldo9->SetVisibility();
		$this->debet10->SetVisibility();
		$this->credit10->SetVisibility();
		$this->saldo10->SetVisibility();
		$this->debet11->SetVisibility();
		$this->credit11->SetVisibility();
		$this->saldo11->SetVisibility();
		$this->debet12->SetVisibility();
		$this->credit12->SetVisibility();
		$this->saldo12->SetVisibility();

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
		global $EW_EXPORT, $tlaporan;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($tlaporan);
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
		if (count($arrKeyFlds) >= 1) {
			$this->nomor->setFormValue($arrKeyFlds[0]);
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
			$sSavedFilterList = isset($UserProfile) ? $UserProfile->GetSearchFilters(CurrentUserName(), "ftlaporanlistsrch") : "";
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
		$sFilterList = ew_Concat($sFilterList, $this->group->AdvancedSearch->ToJSON(), ","); // Field group
		$sFilterList = ew_Concat($sFilterList, $this->rekening->AdvancedSearch->ToJSON(), ","); // Field rekening
		$sFilterList = ew_Concat($sFilterList, $this->tipe->AdvancedSearch->ToJSON(), ","); // Field tipe
		$sFilterList = ew_Concat($sFilterList, $this->posisi->AdvancedSearch->ToJSON(), ","); // Field posisi
		$sFilterList = ew_Concat($sFilterList, $this->laporan->AdvancedSearch->ToJSON(), ","); // Field laporan
		$sFilterList = ew_Concat($sFilterList, $this->keterangan->AdvancedSearch->ToJSON(), ","); // Field keterangan
		$sFilterList = ew_Concat($sFilterList, $this->debet1->AdvancedSearch->ToJSON(), ","); // Field debet1
		$sFilterList = ew_Concat($sFilterList, $this->credit1->AdvancedSearch->ToJSON(), ","); // Field credit1
		$sFilterList = ew_Concat($sFilterList, $this->saldo1->AdvancedSearch->ToJSON(), ","); // Field saldo1
		$sFilterList = ew_Concat($sFilterList, $this->debet2->AdvancedSearch->ToJSON(), ","); // Field debet2
		$sFilterList = ew_Concat($sFilterList, $this->credit2->AdvancedSearch->ToJSON(), ","); // Field credit2
		$sFilterList = ew_Concat($sFilterList, $this->saldo2->AdvancedSearch->ToJSON(), ","); // Field saldo2
		$sFilterList = ew_Concat($sFilterList, $this->debet3->AdvancedSearch->ToJSON(), ","); // Field debet3
		$sFilterList = ew_Concat($sFilterList, $this->credit3->AdvancedSearch->ToJSON(), ","); // Field credit3
		$sFilterList = ew_Concat($sFilterList, $this->saldo3->AdvancedSearch->ToJSON(), ","); // Field saldo3
		$sFilterList = ew_Concat($sFilterList, $this->debet4->AdvancedSearch->ToJSON(), ","); // Field debet4
		$sFilterList = ew_Concat($sFilterList, $this->credit4->AdvancedSearch->ToJSON(), ","); // Field credit4
		$sFilterList = ew_Concat($sFilterList, $this->saldo4->AdvancedSearch->ToJSON(), ","); // Field saldo4
		$sFilterList = ew_Concat($sFilterList, $this->debet5->AdvancedSearch->ToJSON(), ","); // Field debet5
		$sFilterList = ew_Concat($sFilterList, $this->credit5->AdvancedSearch->ToJSON(), ","); // Field credit5
		$sFilterList = ew_Concat($sFilterList, $this->saldo5->AdvancedSearch->ToJSON(), ","); // Field saldo5
		$sFilterList = ew_Concat($sFilterList, $this->debet6->AdvancedSearch->ToJSON(), ","); // Field debet6
		$sFilterList = ew_Concat($sFilterList, $this->credit6->AdvancedSearch->ToJSON(), ","); // Field credit6
		$sFilterList = ew_Concat($sFilterList, $this->saldo6->AdvancedSearch->ToJSON(), ","); // Field saldo6
		$sFilterList = ew_Concat($sFilterList, $this->debet7->AdvancedSearch->ToJSON(), ","); // Field debet7
		$sFilterList = ew_Concat($sFilterList, $this->credit7->AdvancedSearch->ToJSON(), ","); // Field credit7
		$sFilterList = ew_Concat($sFilterList, $this->saldo7->AdvancedSearch->ToJSON(), ","); // Field saldo7
		$sFilterList = ew_Concat($sFilterList, $this->debet8->AdvancedSearch->ToJSON(), ","); // Field debet8
		$sFilterList = ew_Concat($sFilterList, $this->credit8->AdvancedSearch->ToJSON(), ","); // Field credit8
		$sFilterList = ew_Concat($sFilterList, $this->saldo8->AdvancedSearch->ToJSON(), ","); // Field saldo8
		$sFilterList = ew_Concat($sFilterList, $this->debet9->AdvancedSearch->ToJSON(), ","); // Field debet9
		$sFilterList = ew_Concat($sFilterList, $this->credit9->AdvancedSearch->ToJSON(), ","); // Field credit9
		$sFilterList = ew_Concat($sFilterList, $this->saldo9->AdvancedSearch->ToJSON(), ","); // Field saldo9
		$sFilterList = ew_Concat($sFilterList, $this->debet10->AdvancedSearch->ToJSON(), ","); // Field debet10
		$sFilterList = ew_Concat($sFilterList, $this->credit10->AdvancedSearch->ToJSON(), ","); // Field credit10
		$sFilterList = ew_Concat($sFilterList, $this->saldo10->AdvancedSearch->ToJSON(), ","); // Field saldo10
		$sFilterList = ew_Concat($sFilterList, $this->debet11->AdvancedSearch->ToJSON(), ","); // Field debet11
		$sFilterList = ew_Concat($sFilterList, $this->credit11->AdvancedSearch->ToJSON(), ","); // Field credit11
		$sFilterList = ew_Concat($sFilterList, $this->saldo11->AdvancedSearch->ToJSON(), ","); // Field saldo11
		$sFilterList = ew_Concat($sFilterList, $this->debet12->AdvancedSearch->ToJSON(), ","); // Field debet12
		$sFilterList = ew_Concat($sFilterList, $this->credit12->AdvancedSearch->ToJSON(), ","); // Field credit12
		$sFilterList = ew_Concat($sFilterList, $this->saldo12->AdvancedSearch->ToJSON(), ","); // Field saldo12
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "ftlaporanlistsrch", $filters);

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

		// Field group
		$this->group->AdvancedSearch->SearchValue = @$filter["x_group"];
		$this->group->AdvancedSearch->SearchOperator = @$filter["z_group"];
		$this->group->AdvancedSearch->SearchCondition = @$filter["v_group"];
		$this->group->AdvancedSearch->SearchValue2 = @$filter["y_group"];
		$this->group->AdvancedSearch->SearchOperator2 = @$filter["w_group"];
		$this->group->AdvancedSearch->Save();

		// Field rekening
		$this->rekening->AdvancedSearch->SearchValue = @$filter["x_rekening"];
		$this->rekening->AdvancedSearch->SearchOperator = @$filter["z_rekening"];
		$this->rekening->AdvancedSearch->SearchCondition = @$filter["v_rekening"];
		$this->rekening->AdvancedSearch->SearchValue2 = @$filter["y_rekening"];
		$this->rekening->AdvancedSearch->SearchOperator2 = @$filter["w_rekening"];
		$this->rekening->AdvancedSearch->Save();

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

		// Field keterangan
		$this->keterangan->AdvancedSearch->SearchValue = @$filter["x_keterangan"];
		$this->keterangan->AdvancedSearch->SearchOperator = @$filter["z_keterangan"];
		$this->keterangan->AdvancedSearch->SearchCondition = @$filter["v_keterangan"];
		$this->keterangan->AdvancedSearch->SearchValue2 = @$filter["y_keterangan"];
		$this->keterangan->AdvancedSearch->SearchOperator2 = @$filter["w_keterangan"];
		$this->keterangan->AdvancedSearch->Save();

		// Field debet1
		$this->debet1->AdvancedSearch->SearchValue = @$filter["x_debet1"];
		$this->debet1->AdvancedSearch->SearchOperator = @$filter["z_debet1"];
		$this->debet1->AdvancedSearch->SearchCondition = @$filter["v_debet1"];
		$this->debet1->AdvancedSearch->SearchValue2 = @$filter["y_debet1"];
		$this->debet1->AdvancedSearch->SearchOperator2 = @$filter["w_debet1"];
		$this->debet1->AdvancedSearch->Save();

		// Field credit1
		$this->credit1->AdvancedSearch->SearchValue = @$filter["x_credit1"];
		$this->credit1->AdvancedSearch->SearchOperator = @$filter["z_credit1"];
		$this->credit1->AdvancedSearch->SearchCondition = @$filter["v_credit1"];
		$this->credit1->AdvancedSearch->SearchValue2 = @$filter["y_credit1"];
		$this->credit1->AdvancedSearch->SearchOperator2 = @$filter["w_credit1"];
		$this->credit1->AdvancedSearch->Save();

		// Field saldo1
		$this->saldo1->AdvancedSearch->SearchValue = @$filter["x_saldo1"];
		$this->saldo1->AdvancedSearch->SearchOperator = @$filter["z_saldo1"];
		$this->saldo1->AdvancedSearch->SearchCondition = @$filter["v_saldo1"];
		$this->saldo1->AdvancedSearch->SearchValue2 = @$filter["y_saldo1"];
		$this->saldo1->AdvancedSearch->SearchOperator2 = @$filter["w_saldo1"];
		$this->saldo1->AdvancedSearch->Save();

		// Field debet2
		$this->debet2->AdvancedSearch->SearchValue = @$filter["x_debet2"];
		$this->debet2->AdvancedSearch->SearchOperator = @$filter["z_debet2"];
		$this->debet2->AdvancedSearch->SearchCondition = @$filter["v_debet2"];
		$this->debet2->AdvancedSearch->SearchValue2 = @$filter["y_debet2"];
		$this->debet2->AdvancedSearch->SearchOperator2 = @$filter["w_debet2"];
		$this->debet2->AdvancedSearch->Save();

		// Field credit2
		$this->credit2->AdvancedSearch->SearchValue = @$filter["x_credit2"];
		$this->credit2->AdvancedSearch->SearchOperator = @$filter["z_credit2"];
		$this->credit2->AdvancedSearch->SearchCondition = @$filter["v_credit2"];
		$this->credit2->AdvancedSearch->SearchValue2 = @$filter["y_credit2"];
		$this->credit2->AdvancedSearch->SearchOperator2 = @$filter["w_credit2"];
		$this->credit2->AdvancedSearch->Save();

		// Field saldo2
		$this->saldo2->AdvancedSearch->SearchValue = @$filter["x_saldo2"];
		$this->saldo2->AdvancedSearch->SearchOperator = @$filter["z_saldo2"];
		$this->saldo2->AdvancedSearch->SearchCondition = @$filter["v_saldo2"];
		$this->saldo2->AdvancedSearch->SearchValue2 = @$filter["y_saldo2"];
		$this->saldo2->AdvancedSearch->SearchOperator2 = @$filter["w_saldo2"];
		$this->saldo2->AdvancedSearch->Save();

		// Field debet3
		$this->debet3->AdvancedSearch->SearchValue = @$filter["x_debet3"];
		$this->debet3->AdvancedSearch->SearchOperator = @$filter["z_debet3"];
		$this->debet3->AdvancedSearch->SearchCondition = @$filter["v_debet3"];
		$this->debet3->AdvancedSearch->SearchValue2 = @$filter["y_debet3"];
		$this->debet3->AdvancedSearch->SearchOperator2 = @$filter["w_debet3"];
		$this->debet3->AdvancedSearch->Save();

		// Field credit3
		$this->credit3->AdvancedSearch->SearchValue = @$filter["x_credit3"];
		$this->credit3->AdvancedSearch->SearchOperator = @$filter["z_credit3"];
		$this->credit3->AdvancedSearch->SearchCondition = @$filter["v_credit3"];
		$this->credit3->AdvancedSearch->SearchValue2 = @$filter["y_credit3"];
		$this->credit3->AdvancedSearch->SearchOperator2 = @$filter["w_credit3"];
		$this->credit3->AdvancedSearch->Save();

		// Field saldo3
		$this->saldo3->AdvancedSearch->SearchValue = @$filter["x_saldo3"];
		$this->saldo3->AdvancedSearch->SearchOperator = @$filter["z_saldo3"];
		$this->saldo3->AdvancedSearch->SearchCondition = @$filter["v_saldo3"];
		$this->saldo3->AdvancedSearch->SearchValue2 = @$filter["y_saldo3"];
		$this->saldo3->AdvancedSearch->SearchOperator2 = @$filter["w_saldo3"];
		$this->saldo3->AdvancedSearch->Save();

		// Field debet4
		$this->debet4->AdvancedSearch->SearchValue = @$filter["x_debet4"];
		$this->debet4->AdvancedSearch->SearchOperator = @$filter["z_debet4"];
		$this->debet4->AdvancedSearch->SearchCondition = @$filter["v_debet4"];
		$this->debet4->AdvancedSearch->SearchValue2 = @$filter["y_debet4"];
		$this->debet4->AdvancedSearch->SearchOperator2 = @$filter["w_debet4"];
		$this->debet4->AdvancedSearch->Save();

		// Field credit4
		$this->credit4->AdvancedSearch->SearchValue = @$filter["x_credit4"];
		$this->credit4->AdvancedSearch->SearchOperator = @$filter["z_credit4"];
		$this->credit4->AdvancedSearch->SearchCondition = @$filter["v_credit4"];
		$this->credit4->AdvancedSearch->SearchValue2 = @$filter["y_credit4"];
		$this->credit4->AdvancedSearch->SearchOperator2 = @$filter["w_credit4"];
		$this->credit4->AdvancedSearch->Save();

		// Field saldo4
		$this->saldo4->AdvancedSearch->SearchValue = @$filter["x_saldo4"];
		$this->saldo4->AdvancedSearch->SearchOperator = @$filter["z_saldo4"];
		$this->saldo4->AdvancedSearch->SearchCondition = @$filter["v_saldo4"];
		$this->saldo4->AdvancedSearch->SearchValue2 = @$filter["y_saldo4"];
		$this->saldo4->AdvancedSearch->SearchOperator2 = @$filter["w_saldo4"];
		$this->saldo4->AdvancedSearch->Save();

		// Field debet5
		$this->debet5->AdvancedSearch->SearchValue = @$filter["x_debet5"];
		$this->debet5->AdvancedSearch->SearchOperator = @$filter["z_debet5"];
		$this->debet5->AdvancedSearch->SearchCondition = @$filter["v_debet5"];
		$this->debet5->AdvancedSearch->SearchValue2 = @$filter["y_debet5"];
		$this->debet5->AdvancedSearch->SearchOperator2 = @$filter["w_debet5"];
		$this->debet5->AdvancedSearch->Save();

		// Field credit5
		$this->credit5->AdvancedSearch->SearchValue = @$filter["x_credit5"];
		$this->credit5->AdvancedSearch->SearchOperator = @$filter["z_credit5"];
		$this->credit5->AdvancedSearch->SearchCondition = @$filter["v_credit5"];
		$this->credit5->AdvancedSearch->SearchValue2 = @$filter["y_credit5"];
		$this->credit5->AdvancedSearch->SearchOperator2 = @$filter["w_credit5"];
		$this->credit5->AdvancedSearch->Save();

		// Field saldo5
		$this->saldo5->AdvancedSearch->SearchValue = @$filter["x_saldo5"];
		$this->saldo5->AdvancedSearch->SearchOperator = @$filter["z_saldo5"];
		$this->saldo5->AdvancedSearch->SearchCondition = @$filter["v_saldo5"];
		$this->saldo5->AdvancedSearch->SearchValue2 = @$filter["y_saldo5"];
		$this->saldo5->AdvancedSearch->SearchOperator2 = @$filter["w_saldo5"];
		$this->saldo5->AdvancedSearch->Save();

		// Field debet6
		$this->debet6->AdvancedSearch->SearchValue = @$filter["x_debet6"];
		$this->debet6->AdvancedSearch->SearchOperator = @$filter["z_debet6"];
		$this->debet6->AdvancedSearch->SearchCondition = @$filter["v_debet6"];
		$this->debet6->AdvancedSearch->SearchValue2 = @$filter["y_debet6"];
		$this->debet6->AdvancedSearch->SearchOperator2 = @$filter["w_debet6"];
		$this->debet6->AdvancedSearch->Save();

		// Field credit6
		$this->credit6->AdvancedSearch->SearchValue = @$filter["x_credit6"];
		$this->credit6->AdvancedSearch->SearchOperator = @$filter["z_credit6"];
		$this->credit6->AdvancedSearch->SearchCondition = @$filter["v_credit6"];
		$this->credit6->AdvancedSearch->SearchValue2 = @$filter["y_credit6"];
		$this->credit6->AdvancedSearch->SearchOperator2 = @$filter["w_credit6"];
		$this->credit6->AdvancedSearch->Save();

		// Field saldo6
		$this->saldo6->AdvancedSearch->SearchValue = @$filter["x_saldo6"];
		$this->saldo6->AdvancedSearch->SearchOperator = @$filter["z_saldo6"];
		$this->saldo6->AdvancedSearch->SearchCondition = @$filter["v_saldo6"];
		$this->saldo6->AdvancedSearch->SearchValue2 = @$filter["y_saldo6"];
		$this->saldo6->AdvancedSearch->SearchOperator2 = @$filter["w_saldo6"];
		$this->saldo6->AdvancedSearch->Save();

		// Field debet7
		$this->debet7->AdvancedSearch->SearchValue = @$filter["x_debet7"];
		$this->debet7->AdvancedSearch->SearchOperator = @$filter["z_debet7"];
		$this->debet7->AdvancedSearch->SearchCondition = @$filter["v_debet7"];
		$this->debet7->AdvancedSearch->SearchValue2 = @$filter["y_debet7"];
		$this->debet7->AdvancedSearch->SearchOperator2 = @$filter["w_debet7"];
		$this->debet7->AdvancedSearch->Save();

		// Field credit7
		$this->credit7->AdvancedSearch->SearchValue = @$filter["x_credit7"];
		$this->credit7->AdvancedSearch->SearchOperator = @$filter["z_credit7"];
		$this->credit7->AdvancedSearch->SearchCondition = @$filter["v_credit7"];
		$this->credit7->AdvancedSearch->SearchValue2 = @$filter["y_credit7"];
		$this->credit7->AdvancedSearch->SearchOperator2 = @$filter["w_credit7"];
		$this->credit7->AdvancedSearch->Save();

		// Field saldo7
		$this->saldo7->AdvancedSearch->SearchValue = @$filter["x_saldo7"];
		$this->saldo7->AdvancedSearch->SearchOperator = @$filter["z_saldo7"];
		$this->saldo7->AdvancedSearch->SearchCondition = @$filter["v_saldo7"];
		$this->saldo7->AdvancedSearch->SearchValue2 = @$filter["y_saldo7"];
		$this->saldo7->AdvancedSearch->SearchOperator2 = @$filter["w_saldo7"];
		$this->saldo7->AdvancedSearch->Save();

		// Field debet8
		$this->debet8->AdvancedSearch->SearchValue = @$filter["x_debet8"];
		$this->debet8->AdvancedSearch->SearchOperator = @$filter["z_debet8"];
		$this->debet8->AdvancedSearch->SearchCondition = @$filter["v_debet8"];
		$this->debet8->AdvancedSearch->SearchValue2 = @$filter["y_debet8"];
		$this->debet8->AdvancedSearch->SearchOperator2 = @$filter["w_debet8"];
		$this->debet8->AdvancedSearch->Save();

		// Field credit8
		$this->credit8->AdvancedSearch->SearchValue = @$filter["x_credit8"];
		$this->credit8->AdvancedSearch->SearchOperator = @$filter["z_credit8"];
		$this->credit8->AdvancedSearch->SearchCondition = @$filter["v_credit8"];
		$this->credit8->AdvancedSearch->SearchValue2 = @$filter["y_credit8"];
		$this->credit8->AdvancedSearch->SearchOperator2 = @$filter["w_credit8"];
		$this->credit8->AdvancedSearch->Save();

		// Field saldo8
		$this->saldo8->AdvancedSearch->SearchValue = @$filter["x_saldo8"];
		$this->saldo8->AdvancedSearch->SearchOperator = @$filter["z_saldo8"];
		$this->saldo8->AdvancedSearch->SearchCondition = @$filter["v_saldo8"];
		$this->saldo8->AdvancedSearch->SearchValue2 = @$filter["y_saldo8"];
		$this->saldo8->AdvancedSearch->SearchOperator2 = @$filter["w_saldo8"];
		$this->saldo8->AdvancedSearch->Save();

		// Field debet9
		$this->debet9->AdvancedSearch->SearchValue = @$filter["x_debet9"];
		$this->debet9->AdvancedSearch->SearchOperator = @$filter["z_debet9"];
		$this->debet9->AdvancedSearch->SearchCondition = @$filter["v_debet9"];
		$this->debet9->AdvancedSearch->SearchValue2 = @$filter["y_debet9"];
		$this->debet9->AdvancedSearch->SearchOperator2 = @$filter["w_debet9"];
		$this->debet9->AdvancedSearch->Save();

		// Field credit9
		$this->credit9->AdvancedSearch->SearchValue = @$filter["x_credit9"];
		$this->credit9->AdvancedSearch->SearchOperator = @$filter["z_credit9"];
		$this->credit9->AdvancedSearch->SearchCondition = @$filter["v_credit9"];
		$this->credit9->AdvancedSearch->SearchValue2 = @$filter["y_credit9"];
		$this->credit9->AdvancedSearch->SearchOperator2 = @$filter["w_credit9"];
		$this->credit9->AdvancedSearch->Save();

		// Field saldo9
		$this->saldo9->AdvancedSearch->SearchValue = @$filter["x_saldo9"];
		$this->saldo9->AdvancedSearch->SearchOperator = @$filter["z_saldo9"];
		$this->saldo9->AdvancedSearch->SearchCondition = @$filter["v_saldo9"];
		$this->saldo9->AdvancedSearch->SearchValue2 = @$filter["y_saldo9"];
		$this->saldo9->AdvancedSearch->SearchOperator2 = @$filter["w_saldo9"];
		$this->saldo9->AdvancedSearch->Save();

		// Field debet10
		$this->debet10->AdvancedSearch->SearchValue = @$filter["x_debet10"];
		$this->debet10->AdvancedSearch->SearchOperator = @$filter["z_debet10"];
		$this->debet10->AdvancedSearch->SearchCondition = @$filter["v_debet10"];
		$this->debet10->AdvancedSearch->SearchValue2 = @$filter["y_debet10"];
		$this->debet10->AdvancedSearch->SearchOperator2 = @$filter["w_debet10"];
		$this->debet10->AdvancedSearch->Save();

		// Field credit10
		$this->credit10->AdvancedSearch->SearchValue = @$filter["x_credit10"];
		$this->credit10->AdvancedSearch->SearchOperator = @$filter["z_credit10"];
		$this->credit10->AdvancedSearch->SearchCondition = @$filter["v_credit10"];
		$this->credit10->AdvancedSearch->SearchValue2 = @$filter["y_credit10"];
		$this->credit10->AdvancedSearch->SearchOperator2 = @$filter["w_credit10"];
		$this->credit10->AdvancedSearch->Save();

		// Field saldo10
		$this->saldo10->AdvancedSearch->SearchValue = @$filter["x_saldo10"];
		$this->saldo10->AdvancedSearch->SearchOperator = @$filter["z_saldo10"];
		$this->saldo10->AdvancedSearch->SearchCondition = @$filter["v_saldo10"];
		$this->saldo10->AdvancedSearch->SearchValue2 = @$filter["y_saldo10"];
		$this->saldo10->AdvancedSearch->SearchOperator2 = @$filter["w_saldo10"];
		$this->saldo10->AdvancedSearch->Save();

		// Field debet11
		$this->debet11->AdvancedSearch->SearchValue = @$filter["x_debet11"];
		$this->debet11->AdvancedSearch->SearchOperator = @$filter["z_debet11"];
		$this->debet11->AdvancedSearch->SearchCondition = @$filter["v_debet11"];
		$this->debet11->AdvancedSearch->SearchValue2 = @$filter["y_debet11"];
		$this->debet11->AdvancedSearch->SearchOperator2 = @$filter["w_debet11"];
		$this->debet11->AdvancedSearch->Save();

		// Field credit11
		$this->credit11->AdvancedSearch->SearchValue = @$filter["x_credit11"];
		$this->credit11->AdvancedSearch->SearchOperator = @$filter["z_credit11"];
		$this->credit11->AdvancedSearch->SearchCondition = @$filter["v_credit11"];
		$this->credit11->AdvancedSearch->SearchValue2 = @$filter["y_credit11"];
		$this->credit11->AdvancedSearch->SearchOperator2 = @$filter["w_credit11"];
		$this->credit11->AdvancedSearch->Save();

		// Field saldo11
		$this->saldo11->AdvancedSearch->SearchValue = @$filter["x_saldo11"];
		$this->saldo11->AdvancedSearch->SearchOperator = @$filter["z_saldo11"];
		$this->saldo11->AdvancedSearch->SearchCondition = @$filter["v_saldo11"];
		$this->saldo11->AdvancedSearch->SearchValue2 = @$filter["y_saldo11"];
		$this->saldo11->AdvancedSearch->SearchOperator2 = @$filter["w_saldo11"];
		$this->saldo11->AdvancedSearch->Save();

		// Field debet12
		$this->debet12->AdvancedSearch->SearchValue = @$filter["x_debet12"];
		$this->debet12->AdvancedSearch->SearchOperator = @$filter["z_debet12"];
		$this->debet12->AdvancedSearch->SearchCondition = @$filter["v_debet12"];
		$this->debet12->AdvancedSearch->SearchValue2 = @$filter["y_debet12"];
		$this->debet12->AdvancedSearch->SearchOperator2 = @$filter["w_debet12"];
		$this->debet12->AdvancedSearch->Save();

		// Field credit12
		$this->credit12->AdvancedSearch->SearchValue = @$filter["x_credit12"];
		$this->credit12->AdvancedSearch->SearchOperator = @$filter["z_credit12"];
		$this->credit12->AdvancedSearch->SearchCondition = @$filter["v_credit12"];
		$this->credit12->AdvancedSearch->SearchValue2 = @$filter["y_credit12"];
		$this->credit12->AdvancedSearch->SearchOperator2 = @$filter["w_credit12"];
		$this->credit12->AdvancedSearch->Save();

		// Field saldo12
		$this->saldo12->AdvancedSearch->SearchValue = @$filter["x_saldo12"];
		$this->saldo12->AdvancedSearch->SearchOperator = @$filter["z_saldo12"];
		$this->saldo12->AdvancedSearch->SearchCondition = @$filter["v_saldo12"];
		$this->saldo12->AdvancedSearch->SearchValue2 = @$filter["y_saldo12"];
		$this->saldo12->AdvancedSearch->SearchOperator2 = @$filter["w_saldo12"];
		$this->saldo12->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->periode, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->id, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->transaksi, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->referensi, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->rekening, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->tipe, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->posisi, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->laporan, $arKeywords, $type);
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
			$this->UpdateSort($this->tanggal); // tanggal
			$this->UpdateSort($this->periode); // periode
			$this->UpdateSort($this->id); // id
			$this->UpdateSort($this->nomor); // nomor
			$this->UpdateSort($this->transaksi); // transaksi
			$this->UpdateSort($this->referensi); // referensi
			$this->UpdateSort($this->group); // group
			$this->UpdateSort($this->rekening); // rekening
			$this->UpdateSort($this->tipe); // tipe
			$this->UpdateSort($this->posisi); // posisi
			$this->UpdateSort($this->laporan); // laporan
			$this->UpdateSort($this->keterangan); // keterangan
			$this->UpdateSort($this->debet1); // debet1
			$this->UpdateSort($this->credit1); // credit1
			$this->UpdateSort($this->saldo1); // saldo1
			$this->UpdateSort($this->debet2); // debet2
			$this->UpdateSort($this->credit2); // credit2
			$this->UpdateSort($this->saldo2); // saldo2
			$this->UpdateSort($this->debet3); // debet3
			$this->UpdateSort($this->credit3); // credit3
			$this->UpdateSort($this->saldo3); // saldo3
			$this->UpdateSort($this->debet4); // debet4
			$this->UpdateSort($this->credit4); // credit4
			$this->UpdateSort($this->saldo4); // saldo4
			$this->UpdateSort($this->debet5); // debet5
			$this->UpdateSort($this->credit5); // credit5
			$this->UpdateSort($this->saldo5); // saldo5
			$this->UpdateSort($this->debet6); // debet6
			$this->UpdateSort($this->credit6); // credit6
			$this->UpdateSort($this->saldo6); // saldo6
			$this->UpdateSort($this->debet7); // debet7
			$this->UpdateSort($this->credit7); // credit7
			$this->UpdateSort($this->saldo7); // saldo7
			$this->UpdateSort($this->debet8); // debet8
			$this->UpdateSort($this->credit8); // credit8
			$this->UpdateSort($this->saldo8); // saldo8
			$this->UpdateSort($this->debet9); // debet9
			$this->UpdateSort($this->credit9); // credit9
			$this->UpdateSort($this->saldo9); // saldo9
			$this->UpdateSort($this->debet10); // debet10
			$this->UpdateSort($this->credit10); // credit10
			$this->UpdateSort($this->saldo10); // saldo10
			$this->UpdateSort($this->debet11); // debet11
			$this->UpdateSort($this->credit11); // credit11
			$this->UpdateSort($this->saldo11); // saldo11
			$this->UpdateSort($this->debet12); // debet12
			$this->UpdateSort($this->credit12); // credit12
			$this->UpdateSort($this->saldo12); // saldo12
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
				$this->group->setSort("");
				$this->rekening->setSort("");
				$this->tipe->setSort("");
				$this->posisi->setSort("");
				$this->laporan->setSort("");
				$this->keterangan->setSort("");
				$this->debet1->setSort("");
				$this->credit1->setSort("");
				$this->saldo1->setSort("");
				$this->debet2->setSort("");
				$this->credit2->setSort("");
				$this->saldo2->setSort("");
				$this->debet3->setSort("");
				$this->credit3->setSort("");
				$this->saldo3->setSort("");
				$this->debet4->setSort("");
				$this->credit4->setSort("");
				$this->saldo4->setSort("");
				$this->debet5->setSort("");
				$this->credit5->setSort("");
				$this->saldo5->setSort("");
				$this->debet6->setSort("");
				$this->credit6->setSort("");
				$this->saldo6->setSort("");
				$this->debet7->setSort("");
				$this->credit7->setSort("");
				$this->saldo7->setSort("");
				$this->debet8->setSort("");
				$this->credit8->setSort("");
				$this->saldo8->setSort("");
				$this->debet9->setSort("");
				$this->credit9->setSort("");
				$this->saldo9->setSort("");
				$this->debet10->setSort("");
				$this->credit10->setSort("");
				$this->saldo10->setSort("");
				$this->debet11->setSort("");
				$this->credit11->setSort("");
				$this->saldo11->setSort("");
				$this->debet12->setSort("");
				$this->credit12->setSort("");
				$this->saldo12->setSort("");
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->nomor->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"ftlaporanlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"ftlaporanlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.ftlaporanlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"ftlaporanlistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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
		$this->tanggal->setDbValue($rs->fields('tanggal'));
		$this->periode->setDbValue($rs->fields('periode'));
		$this->id->setDbValue($rs->fields('id'));
		$this->nomor->setDbValue($rs->fields('nomor'));
		$this->transaksi->setDbValue($rs->fields('transaksi'));
		$this->referensi->setDbValue($rs->fields('referensi'));
		$this->group->setDbValue($rs->fields('group'));
		$this->rekening->setDbValue($rs->fields('rekening'));
		$this->tipe->setDbValue($rs->fields('tipe'));
		$this->posisi->setDbValue($rs->fields('posisi'));
		$this->laporan->setDbValue($rs->fields('laporan'));
		$this->keterangan->setDbValue($rs->fields('keterangan'));
		$this->debet1->setDbValue($rs->fields('debet1'));
		$this->credit1->setDbValue($rs->fields('credit1'));
		$this->saldo1->setDbValue($rs->fields('saldo1'));
		$this->debet2->setDbValue($rs->fields('debet2'));
		$this->credit2->setDbValue($rs->fields('credit2'));
		$this->saldo2->setDbValue($rs->fields('saldo2'));
		$this->debet3->setDbValue($rs->fields('debet3'));
		$this->credit3->setDbValue($rs->fields('credit3'));
		$this->saldo3->setDbValue($rs->fields('saldo3'));
		$this->debet4->setDbValue($rs->fields('debet4'));
		$this->credit4->setDbValue($rs->fields('credit4'));
		$this->saldo4->setDbValue($rs->fields('saldo4'));
		$this->debet5->setDbValue($rs->fields('debet5'));
		$this->credit5->setDbValue($rs->fields('credit5'));
		$this->saldo5->setDbValue($rs->fields('saldo5'));
		$this->debet6->setDbValue($rs->fields('debet6'));
		$this->credit6->setDbValue($rs->fields('credit6'));
		$this->saldo6->setDbValue($rs->fields('saldo6'));
		$this->debet7->setDbValue($rs->fields('debet7'));
		$this->credit7->setDbValue($rs->fields('credit7'));
		$this->saldo7->setDbValue($rs->fields('saldo7'));
		$this->debet8->setDbValue($rs->fields('debet8'));
		$this->credit8->setDbValue($rs->fields('credit8'));
		$this->saldo8->setDbValue($rs->fields('saldo8'));
		$this->debet9->setDbValue($rs->fields('debet9'));
		$this->credit9->setDbValue($rs->fields('credit9'));
		$this->saldo9->setDbValue($rs->fields('saldo9'));
		$this->debet10->setDbValue($rs->fields('debet10'));
		$this->credit10->setDbValue($rs->fields('credit10'));
		$this->saldo10->setDbValue($rs->fields('saldo10'));
		$this->debet11->setDbValue($rs->fields('debet11'));
		$this->credit11->setDbValue($rs->fields('credit11'));
		$this->saldo11->setDbValue($rs->fields('saldo11'));
		$this->debet12->setDbValue($rs->fields('debet12'));
		$this->credit12->setDbValue($rs->fields('credit12'));
		$this->saldo12->setDbValue($rs->fields('saldo12'));
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
		$this->group->DbValue = $row['group'];
		$this->rekening->DbValue = $row['rekening'];
		$this->tipe->DbValue = $row['tipe'];
		$this->posisi->DbValue = $row['posisi'];
		$this->laporan->DbValue = $row['laporan'];
		$this->keterangan->DbValue = $row['keterangan'];
		$this->debet1->DbValue = $row['debet1'];
		$this->credit1->DbValue = $row['credit1'];
		$this->saldo1->DbValue = $row['saldo1'];
		$this->debet2->DbValue = $row['debet2'];
		$this->credit2->DbValue = $row['credit2'];
		$this->saldo2->DbValue = $row['saldo2'];
		$this->debet3->DbValue = $row['debet3'];
		$this->credit3->DbValue = $row['credit3'];
		$this->saldo3->DbValue = $row['saldo3'];
		$this->debet4->DbValue = $row['debet4'];
		$this->credit4->DbValue = $row['credit4'];
		$this->saldo4->DbValue = $row['saldo4'];
		$this->debet5->DbValue = $row['debet5'];
		$this->credit5->DbValue = $row['credit5'];
		$this->saldo5->DbValue = $row['saldo5'];
		$this->debet6->DbValue = $row['debet6'];
		$this->credit6->DbValue = $row['credit6'];
		$this->saldo6->DbValue = $row['saldo6'];
		$this->debet7->DbValue = $row['debet7'];
		$this->credit7->DbValue = $row['credit7'];
		$this->saldo7->DbValue = $row['saldo7'];
		$this->debet8->DbValue = $row['debet8'];
		$this->credit8->DbValue = $row['credit8'];
		$this->saldo8->DbValue = $row['saldo8'];
		$this->debet9->DbValue = $row['debet9'];
		$this->credit9->DbValue = $row['credit9'];
		$this->saldo9->DbValue = $row['saldo9'];
		$this->debet10->DbValue = $row['debet10'];
		$this->credit10->DbValue = $row['credit10'];
		$this->saldo10->DbValue = $row['saldo10'];
		$this->debet11->DbValue = $row['debet11'];
		$this->credit11->DbValue = $row['credit11'];
		$this->saldo11->DbValue = $row['saldo11'];
		$this->debet12->DbValue = $row['debet12'];
		$this->credit12->DbValue = $row['credit12'];
		$this->saldo12->DbValue = $row['saldo12'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
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
		if ($this->debet1->FormValue == $this->debet1->CurrentValue && is_numeric(ew_StrToFloat($this->debet1->CurrentValue)))
			$this->debet1->CurrentValue = ew_StrToFloat($this->debet1->CurrentValue);

		// Convert decimal values if posted back
		if ($this->credit1->FormValue == $this->credit1->CurrentValue && is_numeric(ew_StrToFloat($this->credit1->CurrentValue)))
			$this->credit1->CurrentValue = ew_StrToFloat($this->credit1->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldo1->FormValue == $this->saldo1->CurrentValue && is_numeric(ew_StrToFloat($this->saldo1->CurrentValue)))
			$this->saldo1->CurrentValue = ew_StrToFloat($this->saldo1->CurrentValue);

		// Convert decimal values if posted back
		if ($this->debet2->FormValue == $this->debet2->CurrentValue && is_numeric(ew_StrToFloat($this->debet2->CurrentValue)))
			$this->debet2->CurrentValue = ew_StrToFloat($this->debet2->CurrentValue);

		// Convert decimal values if posted back
		if ($this->credit2->FormValue == $this->credit2->CurrentValue && is_numeric(ew_StrToFloat($this->credit2->CurrentValue)))
			$this->credit2->CurrentValue = ew_StrToFloat($this->credit2->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldo2->FormValue == $this->saldo2->CurrentValue && is_numeric(ew_StrToFloat($this->saldo2->CurrentValue)))
			$this->saldo2->CurrentValue = ew_StrToFloat($this->saldo2->CurrentValue);

		// Convert decimal values if posted back
		if ($this->debet3->FormValue == $this->debet3->CurrentValue && is_numeric(ew_StrToFloat($this->debet3->CurrentValue)))
			$this->debet3->CurrentValue = ew_StrToFloat($this->debet3->CurrentValue);

		// Convert decimal values if posted back
		if ($this->credit3->FormValue == $this->credit3->CurrentValue && is_numeric(ew_StrToFloat($this->credit3->CurrentValue)))
			$this->credit3->CurrentValue = ew_StrToFloat($this->credit3->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldo3->FormValue == $this->saldo3->CurrentValue && is_numeric(ew_StrToFloat($this->saldo3->CurrentValue)))
			$this->saldo3->CurrentValue = ew_StrToFloat($this->saldo3->CurrentValue);

		// Convert decimal values if posted back
		if ($this->debet4->FormValue == $this->debet4->CurrentValue && is_numeric(ew_StrToFloat($this->debet4->CurrentValue)))
			$this->debet4->CurrentValue = ew_StrToFloat($this->debet4->CurrentValue);

		// Convert decimal values if posted back
		if ($this->credit4->FormValue == $this->credit4->CurrentValue && is_numeric(ew_StrToFloat($this->credit4->CurrentValue)))
			$this->credit4->CurrentValue = ew_StrToFloat($this->credit4->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldo4->FormValue == $this->saldo4->CurrentValue && is_numeric(ew_StrToFloat($this->saldo4->CurrentValue)))
			$this->saldo4->CurrentValue = ew_StrToFloat($this->saldo4->CurrentValue);

		// Convert decimal values if posted back
		if ($this->debet5->FormValue == $this->debet5->CurrentValue && is_numeric(ew_StrToFloat($this->debet5->CurrentValue)))
			$this->debet5->CurrentValue = ew_StrToFloat($this->debet5->CurrentValue);

		// Convert decimal values if posted back
		if ($this->credit5->FormValue == $this->credit5->CurrentValue && is_numeric(ew_StrToFloat($this->credit5->CurrentValue)))
			$this->credit5->CurrentValue = ew_StrToFloat($this->credit5->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldo5->FormValue == $this->saldo5->CurrentValue && is_numeric(ew_StrToFloat($this->saldo5->CurrentValue)))
			$this->saldo5->CurrentValue = ew_StrToFloat($this->saldo5->CurrentValue);

		// Convert decimal values if posted back
		if ($this->debet6->FormValue == $this->debet6->CurrentValue && is_numeric(ew_StrToFloat($this->debet6->CurrentValue)))
			$this->debet6->CurrentValue = ew_StrToFloat($this->debet6->CurrentValue);

		// Convert decimal values if posted back
		if ($this->credit6->FormValue == $this->credit6->CurrentValue && is_numeric(ew_StrToFloat($this->credit6->CurrentValue)))
			$this->credit6->CurrentValue = ew_StrToFloat($this->credit6->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldo6->FormValue == $this->saldo6->CurrentValue && is_numeric(ew_StrToFloat($this->saldo6->CurrentValue)))
			$this->saldo6->CurrentValue = ew_StrToFloat($this->saldo6->CurrentValue);

		// Convert decimal values if posted back
		if ($this->debet7->FormValue == $this->debet7->CurrentValue && is_numeric(ew_StrToFloat($this->debet7->CurrentValue)))
			$this->debet7->CurrentValue = ew_StrToFloat($this->debet7->CurrentValue);

		// Convert decimal values if posted back
		if ($this->credit7->FormValue == $this->credit7->CurrentValue && is_numeric(ew_StrToFloat($this->credit7->CurrentValue)))
			$this->credit7->CurrentValue = ew_StrToFloat($this->credit7->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldo7->FormValue == $this->saldo7->CurrentValue && is_numeric(ew_StrToFloat($this->saldo7->CurrentValue)))
			$this->saldo7->CurrentValue = ew_StrToFloat($this->saldo7->CurrentValue);

		// Convert decimal values if posted back
		if ($this->debet8->FormValue == $this->debet8->CurrentValue && is_numeric(ew_StrToFloat($this->debet8->CurrentValue)))
			$this->debet8->CurrentValue = ew_StrToFloat($this->debet8->CurrentValue);

		// Convert decimal values if posted back
		if ($this->credit8->FormValue == $this->credit8->CurrentValue && is_numeric(ew_StrToFloat($this->credit8->CurrentValue)))
			$this->credit8->CurrentValue = ew_StrToFloat($this->credit8->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldo8->FormValue == $this->saldo8->CurrentValue && is_numeric(ew_StrToFloat($this->saldo8->CurrentValue)))
			$this->saldo8->CurrentValue = ew_StrToFloat($this->saldo8->CurrentValue);

		// Convert decimal values if posted back
		if ($this->debet9->FormValue == $this->debet9->CurrentValue && is_numeric(ew_StrToFloat($this->debet9->CurrentValue)))
			$this->debet9->CurrentValue = ew_StrToFloat($this->debet9->CurrentValue);

		// Convert decimal values if posted back
		if ($this->credit9->FormValue == $this->credit9->CurrentValue && is_numeric(ew_StrToFloat($this->credit9->CurrentValue)))
			$this->credit9->CurrentValue = ew_StrToFloat($this->credit9->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldo9->FormValue == $this->saldo9->CurrentValue && is_numeric(ew_StrToFloat($this->saldo9->CurrentValue)))
			$this->saldo9->CurrentValue = ew_StrToFloat($this->saldo9->CurrentValue);

		// Convert decimal values if posted back
		if ($this->debet10->FormValue == $this->debet10->CurrentValue && is_numeric(ew_StrToFloat($this->debet10->CurrentValue)))
			$this->debet10->CurrentValue = ew_StrToFloat($this->debet10->CurrentValue);

		// Convert decimal values if posted back
		if ($this->credit10->FormValue == $this->credit10->CurrentValue && is_numeric(ew_StrToFloat($this->credit10->CurrentValue)))
			$this->credit10->CurrentValue = ew_StrToFloat($this->credit10->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldo10->FormValue == $this->saldo10->CurrentValue && is_numeric(ew_StrToFloat($this->saldo10->CurrentValue)))
			$this->saldo10->CurrentValue = ew_StrToFloat($this->saldo10->CurrentValue);

		// Convert decimal values if posted back
		if ($this->debet11->FormValue == $this->debet11->CurrentValue && is_numeric(ew_StrToFloat($this->debet11->CurrentValue)))
			$this->debet11->CurrentValue = ew_StrToFloat($this->debet11->CurrentValue);

		// Convert decimal values if posted back
		if ($this->credit11->FormValue == $this->credit11->CurrentValue && is_numeric(ew_StrToFloat($this->credit11->CurrentValue)))
			$this->credit11->CurrentValue = ew_StrToFloat($this->credit11->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldo11->FormValue == $this->saldo11->CurrentValue && is_numeric(ew_StrToFloat($this->saldo11->CurrentValue)))
			$this->saldo11->CurrentValue = ew_StrToFloat($this->saldo11->CurrentValue);

		// Convert decimal values if posted back
		if ($this->debet12->FormValue == $this->debet12->CurrentValue && is_numeric(ew_StrToFloat($this->debet12->CurrentValue)))
			$this->debet12->CurrentValue = ew_StrToFloat($this->debet12->CurrentValue);

		// Convert decimal values if posted back
		if ($this->credit12->FormValue == $this->credit12->CurrentValue && is_numeric(ew_StrToFloat($this->credit12->CurrentValue)))
			$this->credit12->CurrentValue = ew_StrToFloat($this->credit12->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldo12->FormValue == $this->saldo12->CurrentValue && is_numeric(ew_StrToFloat($this->saldo12->CurrentValue)))
			$this->saldo12->CurrentValue = ew_StrToFloat($this->saldo12->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// tanggal
		// periode
		// id
		// nomor
		// transaksi
		// referensi
		// group
		// rekening
		// tipe
		// posisi
		// laporan
		// keterangan
		// debet1
		// credit1
		// saldo1
		// debet2
		// credit2
		// saldo2
		// debet3
		// credit3
		// saldo3
		// debet4
		// credit4
		// saldo4
		// debet5
		// credit5
		// saldo5
		// debet6
		// credit6
		// saldo6
		// debet7
		// credit7
		// saldo7
		// debet8
		// credit8
		// saldo8
		// debet9
		// credit9
		// saldo9
		// debet10
		// credit10
		// saldo10
		// debet11
		// credit11
		// saldo11
		// debet12
		// credit12
		// saldo12

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

		// group
		$this->group->ViewValue = $this->group->CurrentValue;
		$this->group->ViewCustomAttributes = "";

		// rekening
		$this->rekening->ViewValue = $this->rekening->CurrentValue;
		$this->rekening->ViewCustomAttributes = "";

		// tipe
		$this->tipe->ViewValue = $this->tipe->CurrentValue;
		$this->tipe->ViewCustomAttributes = "";

		// posisi
		$this->posisi->ViewValue = $this->posisi->CurrentValue;
		$this->posisi->ViewCustomAttributes = "";

		// laporan
		$this->laporan->ViewValue = $this->laporan->CurrentValue;
		$this->laporan->ViewCustomAttributes = "";

		// keterangan
		$this->keterangan->ViewValue = $this->keterangan->CurrentValue;
		$this->keterangan->ViewCustomAttributes = "";

		// debet1
		$this->debet1->ViewValue = $this->debet1->CurrentValue;
		$this->debet1->ViewCustomAttributes = "";

		// credit1
		$this->credit1->ViewValue = $this->credit1->CurrentValue;
		$this->credit1->ViewCustomAttributes = "";

		// saldo1
		$this->saldo1->ViewValue = $this->saldo1->CurrentValue;
		$this->saldo1->ViewCustomAttributes = "";

		// debet2
		$this->debet2->ViewValue = $this->debet2->CurrentValue;
		$this->debet2->ViewCustomAttributes = "";

		// credit2
		$this->credit2->ViewValue = $this->credit2->CurrentValue;
		$this->credit2->ViewCustomAttributes = "";

		// saldo2
		$this->saldo2->ViewValue = $this->saldo2->CurrentValue;
		$this->saldo2->ViewCustomAttributes = "";

		// debet3
		$this->debet3->ViewValue = $this->debet3->CurrentValue;
		$this->debet3->ViewCustomAttributes = "";

		// credit3
		$this->credit3->ViewValue = $this->credit3->CurrentValue;
		$this->credit3->ViewCustomAttributes = "";

		// saldo3
		$this->saldo3->ViewValue = $this->saldo3->CurrentValue;
		$this->saldo3->ViewCustomAttributes = "";

		// debet4
		$this->debet4->ViewValue = $this->debet4->CurrentValue;
		$this->debet4->ViewCustomAttributes = "";

		// credit4
		$this->credit4->ViewValue = $this->credit4->CurrentValue;
		$this->credit4->ViewCustomAttributes = "";

		// saldo4
		$this->saldo4->ViewValue = $this->saldo4->CurrentValue;
		$this->saldo4->ViewCustomAttributes = "";

		// debet5
		$this->debet5->ViewValue = $this->debet5->CurrentValue;
		$this->debet5->ViewCustomAttributes = "";

		// credit5
		$this->credit5->ViewValue = $this->credit5->CurrentValue;
		$this->credit5->ViewCustomAttributes = "";

		// saldo5
		$this->saldo5->ViewValue = $this->saldo5->CurrentValue;
		$this->saldo5->ViewCustomAttributes = "";

		// debet6
		$this->debet6->ViewValue = $this->debet6->CurrentValue;
		$this->debet6->ViewCustomAttributes = "";

		// credit6
		$this->credit6->ViewValue = $this->credit6->CurrentValue;
		$this->credit6->ViewCustomAttributes = "";

		// saldo6
		$this->saldo6->ViewValue = $this->saldo6->CurrentValue;
		$this->saldo6->ViewCustomAttributes = "";

		// debet7
		$this->debet7->ViewValue = $this->debet7->CurrentValue;
		$this->debet7->ViewCustomAttributes = "";

		// credit7
		$this->credit7->ViewValue = $this->credit7->CurrentValue;
		$this->credit7->ViewCustomAttributes = "";

		// saldo7
		$this->saldo7->ViewValue = $this->saldo7->CurrentValue;
		$this->saldo7->ViewCustomAttributes = "";

		// debet8
		$this->debet8->ViewValue = $this->debet8->CurrentValue;
		$this->debet8->ViewCustomAttributes = "";

		// credit8
		$this->credit8->ViewValue = $this->credit8->CurrentValue;
		$this->credit8->ViewCustomAttributes = "";

		// saldo8
		$this->saldo8->ViewValue = $this->saldo8->CurrentValue;
		$this->saldo8->ViewCustomAttributes = "";

		// debet9
		$this->debet9->ViewValue = $this->debet9->CurrentValue;
		$this->debet9->ViewCustomAttributes = "";

		// credit9
		$this->credit9->ViewValue = $this->credit9->CurrentValue;
		$this->credit9->ViewCustomAttributes = "";

		// saldo9
		$this->saldo9->ViewValue = $this->saldo9->CurrentValue;
		$this->saldo9->ViewCustomAttributes = "";

		// debet10
		$this->debet10->ViewValue = $this->debet10->CurrentValue;
		$this->debet10->ViewCustomAttributes = "";

		// credit10
		$this->credit10->ViewValue = $this->credit10->CurrentValue;
		$this->credit10->ViewCustomAttributes = "";

		// saldo10
		$this->saldo10->ViewValue = $this->saldo10->CurrentValue;
		$this->saldo10->ViewCustomAttributes = "";

		// debet11
		$this->debet11->ViewValue = $this->debet11->CurrentValue;
		$this->debet11->ViewCustomAttributes = "";

		// credit11
		$this->credit11->ViewValue = $this->credit11->CurrentValue;
		$this->credit11->ViewCustomAttributes = "";

		// saldo11
		$this->saldo11->ViewValue = $this->saldo11->CurrentValue;
		$this->saldo11->ViewCustomAttributes = "";

		// debet12
		$this->debet12->ViewValue = $this->debet12->CurrentValue;
		$this->debet12->ViewCustomAttributes = "";

		// credit12
		$this->credit12->ViewValue = $this->credit12->CurrentValue;
		$this->credit12->ViewCustomAttributes = "";

		// saldo12
		$this->saldo12->ViewValue = $this->saldo12->CurrentValue;
		$this->saldo12->ViewCustomAttributes = "";

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

			// group
			$this->group->LinkCustomAttributes = "";
			$this->group->HrefValue = "";
			$this->group->TooltipValue = "";

			// rekening
			$this->rekening->LinkCustomAttributes = "";
			$this->rekening->HrefValue = "";
			$this->rekening->TooltipValue = "";

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

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";
			$this->keterangan->TooltipValue = "";

			// debet1
			$this->debet1->LinkCustomAttributes = "";
			$this->debet1->HrefValue = "";
			$this->debet1->TooltipValue = "";

			// credit1
			$this->credit1->LinkCustomAttributes = "";
			$this->credit1->HrefValue = "";
			$this->credit1->TooltipValue = "";

			// saldo1
			$this->saldo1->LinkCustomAttributes = "";
			$this->saldo1->HrefValue = "";
			$this->saldo1->TooltipValue = "";

			// debet2
			$this->debet2->LinkCustomAttributes = "";
			$this->debet2->HrefValue = "";
			$this->debet2->TooltipValue = "";

			// credit2
			$this->credit2->LinkCustomAttributes = "";
			$this->credit2->HrefValue = "";
			$this->credit2->TooltipValue = "";

			// saldo2
			$this->saldo2->LinkCustomAttributes = "";
			$this->saldo2->HrefValue = "";
			$this->saldo2->TooltipValue = "";

			// debet3
			$this->debet3->LinkCustomAttributes = "";
			$this->debet3->HrefValue = "";
			$this->debet3->TooltipValue = "";

			// credit3
			$this->credit3->LinkCustomAttributes = "";
			$this->credit3->HrefValue = "";
			$this->credit3->TooltipValue = "";

			// saldo3
			$this->saldo3->LinkCustomAttributes = "";
			$this->saldo3->HrefValue = "";
			$this->saldo3->TooltipValue = "";

			// debet4
			$this->debet4->LinkCustomAttributes = "";
			$this->debet4->HrefValue = "";
			$this->debet4->TooltipValue = "";

			// credit4
			$this->credit4->LinkCustomAttributes = "";
			$this->credit4->HrefValue = "";
			$this->credit4->TooltipValue = "";

			// saldo4
			$this->saldo4->LinkCustomAttributes = "";
			$this->saldo4->HrefValue = "";
			$this->saldo4->TooltipValue = "";

			// debet5
			$this->debet5->LinkCustomAttributes = "";
			$this->debet5->HrefValue = "";
			$this->debet5->TooltipValue = "";

			// credit5
			$this->credit5->LinkCustomAttributes = "";
			$this->credit5->HrefValue = "";
			$this->credit5->TooltipValue = "";

			// saldo5
			$this->saldo5->LinkCustomAttributes = "";
			$this->saldo5->HrefValue = "";
			$this->saldo5->TooltipValue = "";

			// debet6
			$this->debet6->LinkCustomAttributes = "";
			$this->debet6->HrefValue = "";
			$this->debet6->TooltipValue = "";

			// credit6
			$this->credit6->LinkCustomAttributes = "";
			$this->credit6->HrefValue = "";
			$this->credit6->TooltipValue = "";

			// saldo6
			$this->saldo6->LinkCustomAttributes = "";
			$this->saldo6->HrefValue = "";
			$this->saldo6->TooltipValue = "";

			// debet7
			$this->debet7->LinkCustomAttributes = "";
			$this->debet7->HrefValue = "";
			$this->debet7->TooltipValue = "";

			// credit7
			$this->credit7->LinkCustomAttributes = "";
			$this->credit7->HrefValue = "";
			$this->credit7->TooltipValue = "";

			// saldo7
			$this->saldo7->LinkCustomAttributes = "";
			$this->saldo7->HrefValue = "";
			$this->saldo7->TooltipValue = "";

			// debet8
			$this->debet8->LinkCustomAttributes = "";
			$this->debet8->HrefValue = "";
			$this->debet8->TooltipValue = "";

			// credit8
			$this->credit8->LinkCustomAttributes = "";
			$this->credit8->HrefValue = "";
			$this->credit8->TooltipValue = "";

			// saldo8
			$this->saldo8->LinkCustomAttributes = "";
			$this->saldo8->HrefValue = "";
			$this->saldo8->TooltipValue = "";

			// debet9
			$this->debet9->LinkCustomAttributes = "";
			$this->debet9->HrefValue = "";
			$this->debet9->TooltipValue = "";

			// credit9
			$this->credit9->LinkCustomAttributes = "";
			$this->credit9->HrefValue = "";
			$this->credit9->TooltipValue = "";

			// saldo9
			$this->saldo9->LinkCustomAttributes = "";
			$this->saldo9->HrefValue = "";
			$this->saldo9->TooltipValue = "";

			// debet10
			$this->debet10->LinkCustomAttributes = "";
			$this->debet10->HrefValue = "";
			$this->debet10->TooltipValue = "";

			// credit10
			$this->credit10->LinkCustomAttributes = "";
			$this->credit10->HrefValue = "";
			$this->credit10->TooltipValue = "";

			// saldo10
			$this->saldo10->LinkCustomAttributes = "";
			$this->saldo10->HrefValue = "";
			$this->saldo10->TooltipValue = "";

			// debet11
			$this->debet11->LinkCustomAttributes = "";
			$this->debet11->HrefValue = "";
			$this->debet11->TooltipValue = "";

			// credit11
			$this->credit11->LinkCustomAttributes = "";
			$this->credit11->HrefValue = "";
			$this->credit11->TooltipValue = "";

			// saldo11
			$this->saldo11->LinkCustomAttributes = "";
			$this->saldo11->HrefValue = "";
			$this->saldo11->TooltipValue = "";

			// debet12
			$this->debet12->LinkCustomAttributes = "";
			$this->debet12->HrefValue = "";
			$this->debet12->TooltipValue = "";

			// credit12
			$this->credit12->LinkCustomAttributes = "";
			$this->credit12->HrefValue = "";
			$this->credit12->TooltipValue = "";

			// saldo12
			$this->saldo12->LinkCustomAttributes = "";
			$this->saldo12->HrefValue = "";
			$this->saldo12->TooltipValue = "";
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
if (!isset($tlaporan_list)) $tlaporan_list = new ctlaporan_list();

// Page init
$tlaporan_list->Page_Init();

// Page main
$tlaporan_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tlaporan_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = ftlaporanlist = new ew_Form("ftlaporanlist", "list");
ftlaporanlist.FormKeyCountName = '<?php echo $tlaporan_list->FormKeyCountName ?>';

// Form_CustomValidate event
ftlaporanlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftlaporanlist.ValidateRequired = true;
<?php } else { ?>
ftlaporanlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = ftlaporanlistsrch = new ew_Form("ftlaporanlistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($tlaporan_list->TotalRecs > 0 && $tlaporan_list->ExportOptions->Visible()) { ?>
<?php $tlaporan_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($tlaporan_list->SearchOptions->Visible()) { ?>
<?php $tlaporan_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($tlaporan_list->FilterOptions->Visible()) { ?>
<?php $tlaporan_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $tlaporan_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($tlaporan_list->TotalRecs <= 0)
			$tlaporan_list->TotalRecs = $tlaporan->SelectRecordCount();
	} else {
		if (!$tlaporan_list->Recordset && ($tlaporan_list->Recordset = $tlaporan_list->LoadRecordset()))
			$tlaporan_list->TotalRecs = $tlaporan_list->Recordset->RecordCount();
	}
	$tlaporan_list->StartRec = 1;
	if ($tlaporan_list->DisplayRecs <= 0 || ($tlaporan->Export <> "" && $tlaporan->ExportAll)) // Display all records
		$tlaporan_list->DisplayRecs = $tlaporan_list->TotalRecs;
	if (!($tlaporan->Export <> "" && $tlaporan->ExportAll))
		$tlaporan_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$tlaporan_list->Recordset = $tlaporan_list->LoadRecordset($tlaporan_list->StartRec-1, $tlaporan_list->DisplayRecs);

	// Set no record found message
	if ($tlaporan->CurrentAction == "" && $tlaporan_list->TotalRecs == 0) {
		if ($tlaporan_list->SearchWhere == "0=101")
			$tlaporan_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$tlaporan_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$tlaporan_list->RenderOtherOptions();
?>
<?php if ($tlaporan->Export == "" && $tlaporan->CurrentAction == "") { ?>
<form name="ftlaporanlistsrch" id="ftlaporanlistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($tlaporan_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="ftlaporanlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="tlaporan">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($tlaporan_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($tlaporan_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $tlaporan_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($tlaporan_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($tlaporan_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($tlaporan_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($tlaporan_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php $tlaporan_list->ShowPageHeader(); ?>
<?php
$tlaporan_list->ShowMessage();
?>
<?php if ($tlaporan_list->TotalRecs > 0 || $tlaporan->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid tlaporan">
<form name="ftlaporanlist" id="ftlaporanlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tlaporan_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tlaporan_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tlaporan">
<div id="gmp_tlaporan" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($tlaporan_list->TotalRecs > 0 || $tlaporan->CurrentAction == "gridedit") { ?>
<table id="tbl_tlaporanlist" class="table ewTable">
<?php echo $tlaporan->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$tlaporan_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$tlaporan_list->RenderListOptions();

// Render list options (header, left)
$tlaporan_list->ListOptions->Render("header", "left");
?>
<?php if ($tlaporan->tanggal->Visible) { // tanggal ?>
	<?php if ($tlaporan->SortUrl($tlaporan->tanggal) == "") { ?>
		<th data-name="tanggal"><div id="elh_tlaporan_tanggal" class="tlaporan_tanggal"><div class="ewTableHeaderCaption"><?php echo $tlaporan->tanggal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tanggal"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->tanggal) ?>',1);"><div id="elh_tlaporan_tanggal" class="tlaporan_tanggal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->tanggal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->tanggal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->tanggal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->periode->Visible) { // periode ?>
	<?php if ($tlaporan->SortUrl($tlaporan->periode) == "") { ?>
		<th data-name="periode"><div id="elh_tlaporan_periode" class="tlaporan_periode"><div class="ewTableHeaderCaption"><?php echo $tlaporan->periode->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="periode"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->periode) ?>',1);"><div id="elh_tlaporan_periode" class="tlaporan_periode">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->periode->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->periode->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->periode->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->id->Visible) { // id ?>
	<?php if ($tlaporan->SortUrl($tlaporan->id) == "") { ?>
		<th data-name="id"><div id="elh_tlaporan_id" class="tlaporan_id"><div class="ewTableHeaderCaption"><?php echo $tlaporan->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->id) ?>',1);"><div id="elh_tlaporan_id" class="tlaporan_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->id->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->nomor->Visible) { // nomor ?>
	<?php if ($tlaporan->SortUrl($tlaporan->nomor) == "") { ?>
		<th data-name="nomor"><div id="elh_tlaporan_nomor" class="tlaporan_nomor"><div class="ewTableHeaderCaption"><?php echo $tlaporan->nomor->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nomor"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->nomor) ?>',1);"><div id="elh_tlaporan_nomor" class="tlaporan_nomor">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->nomor->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->nomor->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->nomor->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->transaksi->Visible) { // transaksi ?>
	<?php if ($tlaporan->SortUrl($tlaporan->transaksi) == "") { ?>
		<th data-name="transaksi"><div id="elh_tlaporan_transaksi" class="tlaporan_transaksi"><div class="ewTableHeaderCaption"><?php echo $tlaporan->transaksi->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="transaksi"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->transaksi) ?>',1);"><div id="elh_tlaporan_transaksi" class="tlaporan_transaksi">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->transaksi->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->transaksi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->transaksi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->referensi->Visible) { // referensi ?>
	<?php if ($tlaporan->SortUrl($tlaporan->referensi) == "") { ?>
		<th data-name="referensi"><div id="elh_tlaporan_referensi" class="tlaporan_referensi"><div class="ewTableHeaderCaption"><?php echo $tlaporan->referensi->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="referensi"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->referensi) ?>',1);"><div id="elh_tlaporan_referensi" class="tlaporan_referensi">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->referensi->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->referensi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->referensi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->group->Visible) { // group ?>
	<?php if ($tlaporan->SortUrl($tlaporan->group) == "") { ?>
		<th data-name="group"><div id="elh_tlaporan_group" class="tlaporan_group"><div class="ewTableHeaderCaption"><?php echo $tlaporan->group->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="group"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->group) ?>',1);"><div id="elh_tlaporan_group" class="tlaporan_group">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->group->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->group->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->group->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->rekening->Visible) { // rekening ?>
	<?php if ($tlaporan->SortUrl($tlaporan->rekening) == "") { ?>
		<th data-name="rekening"><div id="elh_tlaporan_rekening" class="tlaporan_rekening"><div class="ewTableHeaderCaption"><?php echo $tlaporan->rekening->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="rekening"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->rekening) ?>',1);"><div id="elh_tlaporan_rekening" class="tlaporan_rekening">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->rekening->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->rekening->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->rekening->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->tipe->Visible) { // tipe ?>
	<?php if ($tlaporan->SortUrl($tlaporan->tipe) == "") { ?>
		<th data-name="tipe"><div id="elh_tlaporan_tipe" class="tlaporan_tipe"><div class="ewTableHeaderCaption"><?php echo $tlaporan->tipe->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipe"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->tipe) ?>',1);"><div id="elh_tlaporan_tipe" class="tlaporan_tipe">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->tipe->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->tipe->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->tipe->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->posisi->Visible) { // posisi ?>
	<?php if ($tlaporan->SortUrl($tlaporan->posisi) == "") { ?>
		<th data-name="posisi"><div id="elh_tlaporan_posisi" class="tlaporan_posisi"><div class="ewTableHeaderCaption"><?php echo $tlaporan->posisi->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="posisi"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->posisi) ?>',1);"><div id="elh_tlaporan_posisi" class="tlaporan_posisi">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->posisi->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->posisi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->posisi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->laporan->Visible) { // laporan ?>
	<?php if ($tlaporan->SortUrl($tlaporan->laporan) == "") { ?>
		<th data-name="laporan"><div id="elh_tlaporan_laporan" class="tlaporan_laporan"><div class="ewTableHeaderCaption"><?php echo $tlaporan->laporan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="laporan"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->laporan) ?>',1);"><div id="elh_tlaporan_laporan" class="tlaporan_laporan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->laporan->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->laporan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->laporan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->keterangan->Visible) { // keterangan ?>
	<?php if ($tlaporan->SortUrl($tlaporan->keterangan) == "") { ?>
		<th data-name="keterangan"><div id="elh_tlaporan_keterangan" class="tlaporan_keterangan"><div class="ewTableHeaderCaption"><?php echo $tlaporan->keterangan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="keterangan"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->keterangan) ?>',1);"><div id="elh_tlaporan_keterangan" class="tlaporan_keterangan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->keterangan->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->keterangan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->keterangan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->debet1->Visible) { // debet1 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->debet1) == "") { ?>
		<th data-name="debet1"><div id="elh_tlaporan_debet1" class="tlaporan_debet1"><div class="ewTableHeaderCaption"><?php echo $tlaporan->debet1->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="debet1"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->debet1) ?>',1);"><div id="elh_tlaporan_debet1" class="tlaporan_debet1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->debet1->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->debet1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->debet1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->credit1->Visible) { // credit1 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->credit1) == "") { ?>
		<th data-name="credit1"><div id="elh_tlaporan_credit1" class="tlaporan_credit1"><div class="ewTableHeaderCaption"><?php echo $tlaporan->credit1->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="credit1"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->credit1) ?>',1);"><div id="elh_tlaporan_credit1" class="tlaporan_credit1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->credit1->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->credit1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->credit1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->saldo1->Visible) { // saldo1 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->saldo1) == "") { ?>
		<th data-name="saldo1"><div id="elh_tlaporan_saldo1" class="tlaporan_saldo1"><div class="ewTableHeaderCaption"><?php echo $tlaporan->saldo1->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="saldo1"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->saldo1) ?>',1);"><div id="elh_tlaporan_saldo1" class="tlaporan_saldo1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->saldo1->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->saldo1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->saldo1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->debet2->Visible) { // debet2 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->debet2) == "") { ?>
		<th data-name="debet2"><div id="elh_tlaporan_debet2" class="tlaporan_debet2"><div class="ewTableHeaderCaption"><?php echo $tlaporan->debet2->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="debet2"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->debet2) ?>',1);"><div id="elh_tlaporan_debet2" class="tlaporan_debet2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->debet2->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->debet2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->debet2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->credit2->Visible) { // credit2 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->credit2) == "") { ?>
		<th data-name="credit2"><div id="elh_tlaporan_credit2" class="tlaporan_credit2"><div class="ewTableHeaderCaption"><?php echo $tlaporan->credit2->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="credit2"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->credit2) ?>',1);"><div id="elh_tlaporan_credit2" class="tlaporan_credit2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->credit2->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->credit2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->credit2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->saldo2->Visible) { // saldo2 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->saldo2) == "") { ?>
		<th data-name="saldo2"><div id="elh_tlaporan_saldo2" class="tlaporan_saldo2"><div class="ewTableHeaderCaption"><?php echo $tlaporan->saldo2->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="saldo2"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->saldo2) ?>',1);"><div id="elh_tlaporan_saldo2" class="tlaporan_saldo2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->saldo2->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->saldo2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->saldo2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->debet3->Visible) { // debet3 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->debet3) == "") { ?>
		<th data-name="debet3"><div id="elh_tlaporan_debet3" class="tlaporan_debet3"><div class="ewTableHeaderCaption"><?php echo $tlaporan->debet3->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="debet3"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->debet3) ?>',1);"><div id="elh_tlaporan_debet3" class="tlaporan_debet3">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->debet3->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->debet3->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->debet3->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->credit3->Visible) { // credit3 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->credit3) == "") { ?>
		<th data-name="credit3"><div id="elh_tlaporan_credit3" class="tlaporan_credit3"><div class="ewTableHeaderCaption"><?php echo $tlaporan->credit3->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="credit3"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->credit3) ?>',1);"><div id="elh_tlaporan_credit3" class="tlaporan_credit3">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->credit3->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->credit3->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->credit3->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->saldo3->Visible) { // saldo3 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->saldo3) == "") { ?>
		<th data-name="saldo3"><div id="elh_tlaporan_saldo3" class="tlaporan_saldo3"><div class="ewTableHeaderCaption"><?php echo $tlaporan->saldo3->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="saldo3"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->saldo3) ?>',1);"><div id="elh_tlaporan_saldo3" class="tlaporan_saldo3">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->saldo3->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->saldo3->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->saldo3->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->debet4->Visible) { // debet4 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->debet4) == "") { ?>
		<th data-name="debet4"><div id="elh_tlaporan_debet4" class="tlaporan_debet4"><div class="ewTableHeaderCaption"><?php echo $tlaporan->debet4->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="debet4"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->debet4) ?>',1);"><div id="elh_tlaporan_debet4" class="tlaporan_debet4">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->debet4->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->debet4->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->debet4->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->credit4->Visible) { // credit4 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->credit4) == "") { ?>
		<th data-name="credit4"><div id="elh_tlaporan_credit4" class="tlaporan_credit4"><div class="ewTableHeaderCaption"><?php echo $tlaporan->credit4->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="credit4"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->credit4) ?>',1);"><div id="elh_tlaporan_credit4" class="tlaporan_credit4">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->credit4->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->credit4->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->credit4->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->saldo4->Visible) { // saldo4 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->saldo4) == "") { ?>
		<th data-name="saldo4"><div id="elh_tlaporan_saldo4" class="tlaporan_saldo4"><div class="ewTableHeaderCaption"><?php echo $tlaporan->saldo4->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="saldo4"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->saldo4) ?>',1);"><div id="elh_tlaporan_saldo4" class="tlaporan_saldo4">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->saldo4->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->saldo4->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->saldo4->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->debet5->Visible) { // debet5 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->debet5) == "") { ?>
		<th data-name="debet5"><div id="elh_tlaporan_debet5" class="tlaporan_debet5"><div class="ewTableHeaderCaption"><?php echo $tlaporan->debet5->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="debet5"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->debet5) ?>',1);"><div id="elh_tlaporan_debet5" class="tlaporan_debet5">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->debet5->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->debet5->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->debet5->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->credit5->Visible) { // credit5 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->credit5) == "") { ?>
		<th data-name="credit5"><div id="elh_tlaporan_credit5" class="tlaporan_credit5"><div class="ewTableHeaderCaption"><?php echo $tlaporan->credit5->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="credit5"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->credit5) ?>',1);"><div id="elh_tlaporan_credit5" class="tlaporan_credit5">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->credit5->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->credit5->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->credit5->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->saldo5->Visible) { // saldo5 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->saldo5) == "") { ?>
		<th data-name="saldo5"><div id="elh_tlaporan_saldo5" class="tlaporan_saldo5"><div class="ewTableHeaderCaption"><?php echo $tlaporan->saldo5->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="saldo5"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->saldo5) ?>',1);"><div id="elh_tlaporan_saldo5" class="tlaporan_saldo5">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->saldo5->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->saldo5->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->saldo5->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->debet6->Visible) { // debet6 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->debet6) == "") { ?>
		<th data-name="debet6"><div id="elh_tlaporan_debet6" class="tlaporan_debet6"><div class="ewTableHeaderCaption"><?php echo $tlaporan->debet6->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="debet6"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->debet6) ?>',1);"><div id="elh_tlaporan_debet6" class="tlaporan_debet6">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->debet6->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->debet6->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->debet6->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->credit6->Visible) { // credit6 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->credit6) == "") { ?>
		<th data-name="credit6"><div id="elh_tlaporan_credit6" class="tlaporan_credit6"><div class="ewTableHeaderCaption"><?php echo $tlaporan->credit6->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="credit6"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->credit6) ?>',1);"><div id="elh_tlaporan_credit6" class="tlaporan_credit6">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->credit6->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->credit6->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->credit6->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->saldo6->Visible) { // saldo6 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->saldo6) == "") { ?>
		<th data-name="saldo6"><div id="elh_tlaporan_saldo6" class="tlaporan_saldo6"><div class="ewTableHeaderCaption"><?php echo $tlaporan->saldo6->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="saldo6"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->saldo6) ?>',1);"><div id="elh_tlaporan_saldo6" class="tlaporan_saldo6">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->saldo6->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->saldo6->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->saldo6->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->debet7->Visible) { // debet7 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->debet7) == "") { ?>
		<th data-name="debet7"><div id="elh_tlaporan_debet7" class="tlaporan_debet7"><div class="ewTableHeaderCaption"><?php echo $tlaporan->debet7->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="debet7"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->debet7) ?>',1);"><div id="elh_tlaporan_debet7" class="tlaporan_debet7">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->debet7->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->debet7->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->debet7->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->credit7->Visible) { // credit7 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->credit7) == "") { ?>
		<th data-name="credit7"><div id="elh_tlaporan_credit7" class="tlaporan_credit7"><div class="ewTableHeaderCaption"><?php echo $tlaporan->credit7->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="credit7"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->credit7) ?>',1);"><div id="elh_tlaporan_credit7" class="tlaporan_credit7">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->credit7->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->credit7->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->credit7->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->saldo7->Visible) { // saldo7 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->saldo7) == "") { ?>
		<th data-name="saldo7"><div id="elh_tlaporan_saldo7" class="tlaporan_saldo7"><div class="ewTableHeaderCaption"><?php echo $tlaporan->saldo7->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="saldo7"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->saldo7) ?>',1);"><div id="elh_tlaporan_saldo7" class="tlaporan_saldo7">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->saldo7->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->saldo7->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->saldo7->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->debet8->Visible) { // debet8 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->debet8) == "") { ?>
		<th data-name="debet8"><div id="elh_tlaporan_debet8" class="tlaporan_debet8"><div class="ewTableHeaderCaption"><?php echo $tlaporan->debet8->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="debet8"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->debet8) ?>',1);"><div id="elh_tlaporan_debet8" class="tlaporan_debet8">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->debet8->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->debet8->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->debet8->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->credit8->Visible) { // credit8 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->credit8) == "") { ?>
		<th data-name="credit8"><div id="elh_tlaporan_credit8" class="tlaporan_credit8"><div class="ewTableHeaderCaption"><?php echo $tlaporan->credit8->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="credit8"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->credit8) ?>',1);"><div id="elh_tlaporan_credit8" class="tlaporan_credit8">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->credit8->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->credit8->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->credit8->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->saldo8->Visible) { // saldo8 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->saldo8) == "") { ?>
		<th data-name="saldo8"><div id="elh_tlaporan_saldo8" class="tlaporan_saldo8"><div class="ewTableHeaderCaption"><?php echo $tlaporan->saldo8->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="saldo8"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->saldo8) ?>',1);"><div id="elh_tlaporan_saldo8" class="tlaporan_saldo8">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->saldo8->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->saldo8->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->saldo8->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->debet9->Visible) { // debet9 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->debet9) == "") { ?>
		<th data-name="debet9"><div id="elh_tlaporan_debet9" class="tlaporan_debet9"><div class="ewTableHeaderCaption"><?php echo $tlaporan->debet9->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="debet9"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->debet9) ?>',1);"><div id="elh_tlaporan_debet9" class="tlaporan_debet9">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->debet9->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->debet9->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->debet9->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->credit9->Visible) { // credit9 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->credit9) == "") { ?>
		<th data-name="credit9"><div id="elh_tlaporan_credit9" class="tlaporan_credit9"><div class="ewTableHeaderCaption"><?php echo $tlaporan->credit9->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="credit9"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->credit9) ?>',1);"><div id="elh_tlaporan_credit9" class="tlaporan_credit9">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->credit9->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->credit9->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->credit9->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->saldo9->Visible) { // saldo9 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->saldo9) == "") { ?>
		<th data-name="saldo9"><div id="elh_tlaporan_saldo9" class="tlaporan_saldo9"><div class="ewTableHeaderCaption"><?php echo $tlaporan->saldo9->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="saldo9"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->saldo9) ?>',1);"><div id="elh_tlaporan_saldo9" class="tlaporan_saldo9">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->saldo9->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->saldo9->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->saldo9->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->debet10->Visible) { // debet10 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->debet10) == "") { ?>
		<th data-name="debet10"><div id="elh_tlaporan_debet10" class="tlaporan_debet10"><div class="ewTableHeaderCaption"><?php echo $tlaporan->debet10->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="debet10"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->debet10) ?>',1);"><div id="elh_tlaporan_debet10" class="tlaporan_debet10">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->debet10->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->debet10->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->debet10->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->credit10->Visible) { // credit10 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->credit10) == "") { ?>
		<th data-name="credit10"><div id="elh_tlaporan_credit10" class="tlaporan_credit10"><div class="ewTableHeaderCaption"><?php echo $tlaporan->credit10->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="credit10"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->credit10) ?>',1);"><div id="elh_tlaporan_credit10" class="tlaporan_credit10">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->credit10->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->credit10->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->credit10->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->saldo10->Visible) { // saldo10 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->saldo10) == "") { ?>
		<th data-name="saldo10"><div id="elh_tlaporan_saldo10" class="tlaporan_saldo10"><div class="ewTableHeaderCaption"><?php echo $tlaporan->saldo10->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="saldo10"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->saldo10) ?>',1);"><div id="elh_tlaporan_saldo10" class="tlaporan_saldo10">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->saldo10->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->saldo10->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->saldo10->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->debet11->Visible) { // debet11 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->debet11) == "") { ?>
		<th data-name="debet11"><div id="elh_tlaporan_debet11" class="tlaporan_debet11"><div class="ewTableHeaderCaption"><?php echo $tlaporan->debet11->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="debet11"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->debet11) ?>',1);"><div id="elh_tlaporan_debet11" class="tlaporan_debet11">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->debet11->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->debet11->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->debet11->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->credit11->Visible) { // credit11 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->credit11) == "") { ?>
		<th data-name="credit11"><div id="elh_tlaporan_credit11" class="tlaporan_credit11"><div class="ewTableHeaderCaption"><?php echo $tlaporan->credit11->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="credit11"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->credit11) ?>',1);"><div id="elh_tlaporan_credit11" class="tlaporan_credit11">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->credit11->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->credit11->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->credit11->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->saldo11->Visible) { // saldo11 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->saldo11) == "") { ?>
		<th data-name="saldo11"><div id="elh_tlaporan_saldo11" class="tlaporan_saldo11"><div class="ewTableHeaderCaption"><?php echo $tlaporan->saldo11->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="saldo11"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->saldo11) ?>',1);"><div id="elh_tlaporan_saldo11" class="tlaporan_saldo11">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->saldo11->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->saldo11->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->saldo11->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->debet12->Visible) { // debet12 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->debet12) == "") { ?>
		<th data-name="debet12"><div id="elh_tlaporan_debet12" class="tlaporan_debet12"><div class="ewTableHeaderCaption"><?php echo $tlaporan->debet12->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="debet12"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->debet12) ?>',1);"><div id="elh_tlaporan_debet12" class="tlaporan_debet12">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->debet12->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->debet12->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->debet12->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->credit12->Visible) { // credit12 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->credit12) == "") { ?>
		<th data-name="credit12"><div id="elh_tlaporan_credit12" class="tlaporan_credit12"><div class="ewTableHeaderCaption"><?php echo $tlaporan->credit12->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="credit12"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->credit12) ?>',1);"><div id="elh_tlaporan_credit12" class="tlaporan_credit12">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->credit12->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->credit12->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->credit12->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tlaporan->saldo12->Visible) { // saldo12 ?>
	<?php if ($tlaporan->SortUrl($tlaporan->saldo12) == "") { ?>
		<th data-name="saldo12"><div id="elh_tlaporan_saldo12" class="tlaporan_saldo12"><div class="ewTableHeaderCaption"><?php echo $tlaporan->saldo12->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="saldo12"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tlaporan->SortUrl($tlaporan->saldo12) ?>',1);"><div id="elh_tlaporan_saldo12" class="tlaporan_saldo12">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tlaporan->saldo12->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tlaporan->saldo12->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tlaporan->saldo12->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$tlaporan_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($tlaporan->ExportAll && $tlaporan->Export <> "") {
	$tlaporan_list->StopRec = $tlaporan_list->TotalRecs;
} else {

	// Set the last record to display
	if ($tlaporan_list->TotalRecs > $tlaporan_list->StartRec + $tlaporan_list->DisplayRecs - 1)
		$tlaporan_list->StopRec = $tlaporan_list->StartRec + $tlaporan_list->DisplayRecs - 1;
	else
		$tlaporan_list->StopRec = $tlaporan_list->TotalRecs;
}
$tlaporan_list->RecCnt = $tlaporan_list->StartRec - 1;
if ($tlaporan_list->Recordset && !$tlaporan_list->Recordset->EOF) {
	$tlaporan_list->Recordset->MoveFirst();
	$bSelectLimit = $tlaporan_list->UseSelectLimit;
	if (!$bSelectLimit && $tlaporan_list->StartRec > 1)
		$tlaporan_list->Recordset->Move($tlaporan_list->StartRec - 1);
} elseif (!$tlaporan->AllowAddDeleteRow && $tlaporan_list->StopRec == 0) {
	$tlaporan_list->StopRec = $tlaporan->GridAddRowCount;
}

// Initialize aggregate
$tlaporan->RowType = EW_ROWTYPE_AGGREGATEINIT;
$tlaporan->ResetAttrs();
$tlaporan_list->RenderRow();
while ($tlaporan_list->RecCnt < $tlaporan_list->StopRec) {
	$tlaporan_list->RecCnt++;
	if (intval($tlaporan_list->RecCnt) >= intval($tlaporan_list->StartRec)) {
		$tlaporan_list->RowCnt++;

		// Set up key count
		$tlaporan_list->KeyCount = $tlaporan_list->RowIndex;

		// Init row class and style
		$tlaporan->ResetAttrs();
		$tlaporan->CssClass = "";
		if ($tlaporan->CurrentAction == "gridadd") {
		} else {
			$tlaporan_list->LoadRowValues($tlaporan_list->Recordset); // Load row values
		}
		$tlaporan->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$tlaporan->RowAttrs = array_merge($tlaporan->RowAttrs, array('data-rowindex'=>$tlaporan_list->RowCnt, 'id'=>'r' . $tlaporan_list->RowCnt . '_tlaporan', 'data-rowtype'=>$tlaporan->RowType));

		// Render row
		$tlaporan_list->RenderRow();

		// Render list options
		$tlaporan_list->RenderListOptions();
?>
	<tr<?php echo $tlaporan->RowAttributes() ?>>
<?php

// Render list options (body, left)
$tlaporan_list->ListOptions->Render("body", "left", $tlaporan_list->RowCnt);
?>
	<?php if ($tlaporan->tanggal->Visible) { // tanggal ?>
		<td data-name="tanggal"<?php echo $tlaporan->tanggal->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_tanggal" class="tlaporan_tanggal">
<span<?php echo $tlaporan->tanggal->ViewAttributes() ?>>
<?php echo $tlaporan->tanggal->ListViewValue() ?></span>
</span>
<a id="<?php echo $tlaporan_list->PageObjName . "_row_" . $tlaporan_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($tlaporan->periode->Visible) { // periode ?>
		<td data-name="periode"<?php echo $tlaporan->periode->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_periode" class="tlaporan_periode">
<span<?php echo $tlaporan->periode->ViewAttributes() ?>>
<?php echo $tlaporan->periode->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->id->Visible) { // id ?>
		<td data-name="id"<?php echo $tlaporan->id->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_id" class="tlaporan_id">
<span<?php echo $tlaporan->id->ViewAttributes() ?>>
<?php echo $tlaporan->id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->nomor->Visible) { // nomor ?>
		<td data-name="nomor"<?php echo $tlaporan->nomor->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_nomor" class="tlaporan_nomor">
<span<?php echo $tlaporan->nomor->ViewAttributes() ?>>
<?php echo $tlaporan->nomor->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->transaksi->Visible) { // transaksi ?>
		<td data-name="transaksi"<?php echo $tlaporan->transaksi->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_transaksi" class="tlaporan_transaksi">
<span<?php echo $tlaporan->transaksi->ViewAttributes() ?>>
<?php echo $tlaporan->transaksi->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->referensi->Visible) { // referensi ?>
		<td data-name="referensi"<?php echo $tlaporan->referensi->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_referensi" class="tlaporan_referensi">
<span<?php echo $tlaporan->referensi->ViewAttributes() ?>>
<?php echo $tlaporan->referensi->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->group->Visible) { // group ?>
		<td data-name="group"<?php echo $tlaporan->group->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_group" class="tlaporan_group">
<span<?php echo $tlaporan->group->ViewAttributes() ?>>
<?php echo $tlaporan->group->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->rekening->Visible) { // rekening ?>
		<td data-name="rekening"<?php echo $tlaporan->rekening->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_rekening" class="tlaporan_rekening">
<span<?php echo $tlaporan->rekening->ViewAttributes() ?>>
<?php echo $tlaporan->rekening->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->tipe->Visible) { // tipe ?>
		<td data-name="tipe"<?php echo $tlaporan->tipe->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_tipe" class="tlaporan_tipe">
<span<?php echo $tlaporan->tipe->ViewAttributes() ?>>
<?php echo $tlaporan->tipe->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->posisi->Visible) { // posisi ?>
		<td data-name="posisi"<?php echo $tlaporan->posisi->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_posisi" class="tlaporan_posisi">
<span<?php echo $tlaporan->posisi->ViewAttributes() ?>>
<?php echo $tlaporan->posisi->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->laporan->Visible) { // laporan ?>
		<td data-name="laporan"<?php echo $tlaporan->laporan->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_laporan" class="tlaporan_laporan">
<span<?php echo $tlaporan->laporan->ViewAttributes() ?>>
<?php echo $tlaporan->laporan->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->keterangan->Visible) { // keterangan ?>
		<td data-name="keterangan"<?php echo $tlaporan->keterangan->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_keterangan" class="tlaporan_keterangan">
<span<?php echo $tlaporan->keterangan->ViewAttributes() ?>>
<?php echo $tlaporan->keterangan->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->debet1->Visible) { // debet1 ?>
		<td data-name="debet1"<?php echo $tlaporan->debet1->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_debet1" class="tlaporan_debet1">
<span<?php echo $tlaporan->debet1->ViewAttributes() ?>>
<?php echo $tlaporan->debet1->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->credit1->Visible) { // credit1 ?>
		<td data-name="credit1"<?php echo $tlaporan->credit1->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_credit1" class="tlaporan_credit1">
<span<?php echo $tlaporan->credit1->ViewAttributes() ?>>
<?php echo $tlaporan->credit1->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->saldo1->Visible) { // saldo1 ?>
		<td data-name="saldo1"<?php echo $tlaporan->saldo1->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_saldo1" class="tlaporan_saldo1">
<span<?php echo $tlaporan->saldo1->ViewAttributes() ?>>
<?php echo $tlaporan->saldo1->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->debet2->Visible) { // debet2 ?>
		<td data-name="debet2"<?php echo $tlaporan->debet2->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_debet2" class="tlaporan_debet2">
<span<?php echo $tlaporan->debet2->ViewAttributes() ?>>
<?php echo $tlaporan->debet2->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->credit2->Visible) { // credit2 ?>
		<td data-name="credit2"<?php echo $tlaporan->credit2->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_credit2" class="tlaporan_credit2">
<span<?php echo $tlaporan->credit2->ViewAttributes() ?>>
<?php echo $tlaporan->credit2->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->saldo2->Visible) { // saldo2 ?>
		<td data-name="saldo2"<?php echo $tlaporan->saldo2->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_saldo2" class="tlaporan_saldo2">
<span<?php echo $tlaporan->saldo2->ViewAttributes() ?>>
<?php echo $tlaporan->saldo2->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->debet3->Visible) { // debet3 ?>
		<td data-name="debet3"<?php echo $tlaporan->debet3->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_debet3" class="tlaporan_debet3">
<span<?php echo $tlaporan->debet3->ViewAttributes() ?>>
<?php echo $tlaporan->debet3->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->credit3->Visible) { // credit3 ?>
		<td data-name="credit3"<?php echo $tlaporan->credit3->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_credit3" class="tlaporan_credit3">
<span<?php echo $tlaporan->credit3->ViewAttributes() ?>>
<?php echo $tlaporan->credit3->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->saldo3->Visible) { // saldo3 ?>
		<td data-name="saldo3"<?php echo $tlaporan->saldo3->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_saldo3" class="tlaporan_saldo3">
<span<?php echo $tlaporan->saldo3->ViewAttributes() ?>>
<?php echo $tlaporan->saldo3->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->debet4->Visible) { // debet4 ?>
		<td data-name="debet4"<?php echo $tlaporan->debet4->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_debet4" class="tlaporan_debet4">
<span<?php echo $tlaporan->debet4->ViewAttributes() ?>>
<?php echo $tlaporan->debet4->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->credit4->Visible) { // credit4 ?>
		<td data-name="credit4"<?php echo $tlaporan->credit4->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_credit4" class="tlaporan_credit4">
<span<?php echo $tlaporan->credit4->ViewAttributes() ?>>
<?php echo $tlaporan->credit4->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->saldo4->Visible) { // saldo4 ?>
		<td data-name="saldo4"<?php echo $tlaporan->saldo4->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_saldo4" class="tlaporan_saldo4">
<span<?php echo $tlaporan->saldo4->ViewAttributes() ?>>
<?php echo $tlaporan->saldo4->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->debet5->Visible) { // debet5 ?>
		<td data-name="debet5"<?php echo $tlaporan->debet5->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_debet5" class="tlaporan_debet5">
<span<?php echo $tlaporan->debet5->ViewAttributes() ?>>
<?php echo $tlaporan->debet5->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->credit5->Visible) { // credit5 ?>
		<td data-name="credit5"<?php echo $tlaporan->credit5->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_credit5" class="tlaporan_credit5">
<span<?php echo $tlaporan->credit5->ViewAttributes() ?>>
<?php echo $tlaporan->credit5->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->saldo5->Visible) { // saldo5 ?>
		<td data-name="saldo5"<?php echo $tlaporan->saldo5->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_saldo5" class="tlaporan_saldo5">
<span<?php echo $tlaporan->saldo5->ViewAttributes() ?>>
<?php echo $tlaporan->saldo5->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->debet6->Visible) { // debet6 ?>
		<td data-name="debet6"<?php echo $tlaporan->debet6->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_debet6" class="tlaporan_debet6">
<span<?php echo $tlaporan->debet6->ViewAttributes() ?>>
<?php echo $tlaporan->debet6->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->credit6->Visible) { // credit6 ?>
		<td data-name="credit6"<?php echo $tlaporan->credit6->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_credit6" class="tlaporan_credit6">
<span<?php echo $tlaporan->credit6->ViewAttributes() ?>>
<?php echo $tlaporan->credit6->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->saldo6->Visible) { // saldo6 ?>
		<td data-name="saldo6"<?php echo $tlaporan->saldo6->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_saldo6" class="tlaporan_saldo6">
<span<?php echo $tlaporan->saldo6->ViewAttributes() ?>>
<?php echo $tlaporan->saldo6->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->debet7->Visible) { // debet7 ?>
		<td data-name="debet7"<?php echo $tlaporan->debet7->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_debet7" class="tlaporan_debet7">
<span<?php echo $tlaporan->debet7->ViewAttributes() ?>>
<?php echo $tlaporan->debet7->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->credit7->Visible) { // credit7 ?>
		<td data-name="credit7"<?php echo $tlaporan->credit7->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_credit7" class="tlaporan_credit7">
<span<?php echo $tlaporan->credit7->ViewAttributes() ?>>
<?php echo $tlaporan->credit7->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->saldo7->Visible) { // saldo7 ?>
		<td data-name="saldo7"<?php echo $tlaporan->saldo7->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_saldo7" class="tlaporan_saldo7">
<span<?php echo $tlaporan->saldo7->ViewAttributes() ?>>
<?php echo $tlaporan->saldo7->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->debet8->Visible) { // debet8 ?>
		<td data-name="debet8"<?php echo $tlaporan->debet8->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_debet8" class="tlaporan_debet8">
<span<?php echo $tlaporan->debet8->ViewAttributes() ?>>
<?php echo $tlaporan->debet8->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->credit8->Visible) { // credit8 ?>
		<td data-name="credit8"<?php echo $tlaporan->credit8->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_credit8" class="tlaporan_credit8">
<span<?php echo $tlaporan->credit8->ViewAttributes() ?>>
<?php echo $tlaporan->credit8->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->saldo8->Visible) { // saldo8 ?>
		<td data-name="saldo8"<?php echo $tlaporan->saldo8->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_saldo8" class="tlaporan_saldo8">
<span<?php echo $tlaporan->saldo8->ViewAttributes() ?>>
<?php echo $tlaporan->saldo8->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->debet9->Visible) { // debet9 ?>
		<td data-name="debet9"<?php echo $tlaporan->debet9->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_debet9" class="tlaporan_debet9">
<span<?php echo $tlaporan->debet9->ViewAttributes() ?>>
<?php echo $tlaporan->debet9->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->credit9->Visible) { // credit9 ?>
		<td data-name="credit9"<?php echo $tlaporan->credit9->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_credit9" class="tlaporan_credit9">
<span<?php echo $tlaporan->credit9->ViewAttributes() ?>>
<?php echo $tlaporan->credit9->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->saldo9->Visible) { // saldo9 ?>
		<td data-name="saldo9"<?php echo $tlaporan->saldo9->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_saldo9" class="tlaporan_saldo9">
<span<?php echo $tlaporan->saldo9->ViewAttributes() ?>>
<?php echo $tlaporan->saldo9->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->debet10->Visible) { // debet10 ?>
		<td data-name="debet10"<?php echo $tlaporan->debet10->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_debet10" class="tlaporan_debet10">
<span<?php echo $tlaporan->debet10->ViewAttributes() ?>>
<?php echo $tlaporan->debet10->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->credit10->Visible) { // credit10 ?>
		<td data-name="credit10"<?php echo $tlaporan->credit10->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_credit10" class="tlaporan_credit10">
<span<?php echo $tlaporan->credit10->ViewAttributes() ?>>
<?php echo $tlaporan->credit10->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->saldo10->Visible) { // saldo10 ?>
		<td data-name="saldo10"<?php echo $tlaporan->saldo10->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_saldo10" class="tlaporan_saldo10">
<span<?php echo $tlaporan->saldo10->ViewAttributes() ?>>
<?php echo $tlaporan->saldo10->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->debet11->Visible) { // debet11 ?>
		<td data-name="debet11"<?php echo $tlaporan->debet11->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_debet11" class="tlaporan_debet11">
<span<?php echo $tlaporan->debet11->ViewAttributes() ?>>
<?php echo $tlaporan->debet11->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->credit11->Visible) { // credit11 ?>
		<td data-name="credit11"<?php echo $tlaporan->credit11->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_credit11" class="tlaporan_credit11">
<span<?php echo $tlaporan->credit11->ViewAttributes() ?>>
<?php echo $tlaporan->credit11->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->saldo11->Visible) { // saldo11 ?>
		<td data-name="saldo11"<?php echo $tlaporan->saldo11->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_saldo11" class="tlaporan_saldo11">
<span<?php echo $tlaporan->saldo11->ViewAttributes() ?>>
<?php echo $tlaporan->saldo11->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->debet12->Visible) { // debet12 ?>
		<td data-name="debet12"<?php echo $tlaporan->debet12->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_debet12" class="tlaporan_debet12">
<span<?php echo $tlaporan->debet12->ViewAttributes() ?>>
<?php echo $tlaporan->debet12->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->credit12->Visible) { // credit12 ?>
		<td data-name="credit12"<?php echo $tlaporan->credit12->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_credit12" class="tlaporan_credit12">
<span<?php echo $tlaporan->credit12->ViewAttributes() ?>>
<?php echo $tlaporan->credit12->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tlaporan->saldo12->Visible) { // saldo12 ?>
		<td data-name="saldo12"<?php echo $tlaporan->saldo12->CellAttributes() ?>>
<span id="el<?php echo $tlaporan_list->RowCnt ?>_tlaporan_saldo12" class="tlaporan_saldo12">
<span<?php echo $tlaporan->saldo12->ViewAttributes() ?>>
<?php echo $tlaporan->saldo12->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$tlaporan_list->ListOptions->Render("body", "right", $tlaporan_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($tlaporan->CurrentAction <> "gridadd")
		$tlaporan_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($tlaporan->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($tlaporan_list->Recordset)
	$tlaporan_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($tlaporan->CurrentAction <> "gridadd" && $tlaporan->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($tlaporan_list->Pager)) $tlaporan_list->Pager = new cPrevNextPager($tlaporan_list->StartRec, $tlaporan_list->DisplayRecs, $tlaporan_list->TotalRecs) ?>
<?php if ($tlaporan_list->Pager->RecordCount > 0 && $tlaporan_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($tlaporan_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $tlaporan_list->PageUrl() ?>start=<?php echo $tlaporan_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($tlaporan_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $tlaporan_list->PageUrl() ?>start=<?php echo $tlaporan_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $tlaporan_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($tlaporan_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $tlaporan_list->PageUrl() ?>start=<?php echo $tlaporan_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($tlaporan_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $tlaporan_list->PageUrl() ?>start=<?php echo $tlaporan_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $tlaporan_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $tlaporan_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $tlaporan_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $tlaporan_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($tlaporan_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($tlaporan_list->TotalRecs == 0 && $tlaporan->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($tlaporan_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
ftlaporanlistsrch.FilterList = <?php echo $tlaporan_list->GetFilterList() ?>;
ftlaporanlistsrch.Init();
ftlaporanlist.Init();
</script>
<?php
$tlaporan_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tlaporan_list->Page_Terminate();
?>
