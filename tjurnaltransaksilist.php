<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "tjurnaltransaksiinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$tjurnaltransaksi_list = NULL; // Initialize page object first

class ctjurnaltransaksi_list extends ctjurnaltransaksi {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tjurnaltransaksi';

	// Page object name
	var $PageObjName = 'tjurnaltransaksi_list';

	// Grid form hidden field names
	var $FormName = 'ftjurnaltransaksilist';
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

		// Table object (tjurnaltransaksi)
		if (!isset($GLOBALS["tjurnaltransaksi"]) || get_class($GLOBALS["tjurnaltransaksi"]) == "ctjurnaltransaksi") {
			$GLOBALS["tjurnaltransaksi"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tjurnaltransaksi"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "tjurnaltransaksiadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "tjurnaltransaksidelete.php";
		$this->MultiUpdateUrl = "tjurnaltransaksiupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tjurnaltransaksi', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption ftjurnaltransaksilistsrch";

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
		$this->pembayaran_->SetVisibility();
		$this->bunga_->SetVisibility();
		$this->denda_->SetVisibility();
		$this->titipan_->SetVisibility();
		$this->administrasi_->SetVisibility();
		$this->modal_->SetVisibility();
		$this->pinjaman_->SetVisibility();
		$this->biaya_->SetVisibility();
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
		global $EW_EXPORT, $tjurnaltransaksi;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($tjurnaltransaksi);
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
			$sSavedFilterList = isset($UserProfile) ? $UserProfile->GetSearchFilters(CurrentUserName(), "ftjurnaltransaksilistsrch") : "";
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
		$sFilterList = ew_Concat($sFilterList, $this->pembayaran_->AdvancedSearch->ToJSON(), ","); // Field pembayaran_
		$sFilterList = ew_Concat($sFilterList, $this->bunga_->AdvancedSearch->ToJSON(), ","); // Field bunga_
		$sFilterList = ew_Concat($sFilterList, $this->denda_->AdvancedSearch->ToJSON(), ","); // Field denda_
		$sFilterList = ew_Concat($sFilterList, $this->titipan_->AdvancedSearch->ToJSON(), ","); // Field titipan_
		$sFilterList = ew_Concat($sFilterList, $this->administrasi_->AdvancedSearch->ToJSON(), ","); // Field administrasi_
		$sFilterList = ew_Concat($sFilterList, $this->modal_->AdvancedSearch->ToJSON(), ","); // Field modal_
		$sFilterList = ew_Concat($sFilterList, $this->pinjaman_->AdvancedSearch->ToJSON(), ","); // Field pinjaman_
		$sFilterList = ew_Concat($sFilterList, $this->biaya_->AdvancedSearch->ToJSON(), ","); // Field biaya_
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "ftjurnaltransaksilistsrch", $filters);

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

		// Field pembayaran_
		$this->pembayaran_->AdvancedSearch->SearchValue = @$filter["x_pembayaran_"];
		$this->pembayaran_->AdvancedSearch->SearchOperator = @$filter["z_pembayaran_"];
		$this->pembayaran_->AdvancedSearch->SearchCondition = @$filter["v_pembayaran_"];
		$this->pembayaran_->AdvancedSearch->SearchValue2 = @$filter["y_pembayaran_"];
		$this->pembayaran_->AdvancedSearch->SearchOperator2 = @$filter["w_pembayaran_"];
		$this->pembayaran_->AdvancedSearch->Save();

		// Field bunga_
		$this->bunga_->AdvancedSearch->SearchValue = @$filter["x_bunga_"];
		$this->bunga_->AdvancedSearch->SearchOperator = @$filter["z_bunga_"];
		$this->bunga_->AdvancedSearch->SearchCondition = @$filter["v_bunga_"];
		$this->bunga_->AdvancedSearch->SearchValue2 = @$filter["y_bunga_"];
		$this->bunga_->AdvancedSearch->SearchOperator2 = @$filter["w_bunga_"];
		$this->bunga_->AdvancedSearch->Save();

		// Field denda_
		$this->denda_->AdvancedSearch->SearchValue = @$filter["x_denda_"];
		$this->denda_->AdvancedSearch->SearchOperator = @$filter["z_denda_"];
		$this->denda_->AdvancedSearch->SearchCondition = @$filter["v_denda_"];
		$this->denda_->AdvancedSearch->SearchValue2 = @$filter["y_denda_"];
		$this->denda_->AdvancedSearch->SearchOperator2 = @$filter["w_denda_"];
		$this->denda_->AdvancedSearch->Save();

		// Field titipan_
		$this->titipan_->AdvancedSearch->SearchValue = @$filter["x_titipan_"];
		$this->titipan_->AdvancedSearch->SearchOperator = @$filter["z_titipan_"];
		$this->titipan_->AdvancedSearch->SearchCondition = @$filter["v_titipan_"];
		$this->titipan_->AdvancedSearch->SearchValue2 = @$filter["y_titipan_"];
		$this->titipan_->AdvancedSearch->SearchOperator2 = @$filter["w_titipan_"];
		$this->titipan_->AdvancedSearch->Save();

		// Field administrasi_
		$this->administrasi_->AdvancedSearch->SearchValue = @$filter["x_administrasi_"];
		$this->administrasi_->AdvancedSearch->SearchOperator = @$filter["z_administrasi_"];
		$this->administrasi_->AdvancedSearch->SearchCondition = @$filter["v_administrasi_"];
		$this->administrasi_->AdvancedSearch->SearchValue2 = @$filter["y_administrasi_"];
		$this->administrasi_->AdvancedSearch->SearchOperator2 = @$filter["w_administrasi_"];
		$this->administrasi_->AdvancedSearch->Save();

		// Field modal_
		$this->modal_->AdvancedSearch->SearchValue = @$filter["x_modal_"];
		$this->modal_->AdvancedSearch->SearchOperator = @$filter["z_modal_"];
		$this->modal_->AdvancedSearch->SearchCondition = @$filter["v_modal_"];
		$this->modal_->AdvancedSearch->SearchValue2 = @$filter["y_modal_"];
		$this->modal_->AdvancedSearch->SearchOperator2 = @$filter["w_modal_"];
		$this->modal_->AdvancedSearch->Save();

		// Field pinjaman_
		$this->pinjaman_->AdvancedSearch->SearchValue = @$filter["x_pinjaman_"];
		$this->pinjaman_->AdvancedSearch->SearchOperator = @$filter["z_pinjaman_"];
		$this->pinjaman_->AdvancedSearch->SearchCondition = @$filter["v_pinjaman_"];
		$this->pinjaman_->AdvancedSearch->SearchValue2 = @$filter["y_pinjaman_"];
		$this->pinjaman_->AdvancedSearch->SearchOperator2 = @$filter["w_pinjaman_"];
		$this->pinjaman_->AdvancedSearch->Save();

		// Field biaya_
		$this->biaya_->AdvancedSearch->SearchValue = @$filter["x_biaya_"];
		$this->biaya_->AdvancedSearch->SearchOperator = @$filter["z_biaya_"];
		$this->biaya_->AdvancedSearch->SearchCondition = @$filter["v_biaya_"];
		$this->biaya_->AdvancedSearch->SearchValue2 = @$filter["y_biaya_"];
		$this->biaya_->AdvancedSearch->SearchOperator2 = @$filter["w_biaya_"];
		$this->biaya_->AdvancedSearch->Save();

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
		$this->BuildSearchSql($sWhere, $this->pembayaran_, $Default, FALSE); // pembayaran_
		$this->BuildSearchSql($sWhere, $this->bunga_, $Default, FALSE); // bunga_
		$this->BuildSearchSql($sWhere, $this->denda_, $Default, FALSE); // denda_
		$this->BuildSearchSql($sWhere, $this->titipan_, $Default, FALSE); // titipan_
		$this->BuildSearchSql($sWhere, $this->administrasi_, $Default, FALSE); // administrasi_
		$this->BuildSearchSql($sWhere, $this->modal_, $Default, FALSE); // modal_
		$this->BuildSearchSql($sWhere, $this->pinjaman_, $Default, FALSE); // pinjaman_
		$this->BuildSearchSql($sWhere, $this->biaya_, $Default, FALSE); // biaya_
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
			$this->pembayaran_->AdvancedSearch->Save(); // pembayaran_
			$this->bunga_->AdvancedSearch->Save(); // bunga_
			$this->denda_->AdvancedSearch->Save(); // denda_
			$this->titipan_->AdvancedSearch->Save(); // titipan_
			$this->administrasi_->AdvancedSearch->Save(); // administrasi_
			$this->modal_->AdvancedSearch->Save(); // modal_
			$this->pinjaman_->AdvancedSearch->Save(); // pinjaman_
			$this->biaya_->AdvancedSearch->Save(); // biaya_
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
		if ($this->pembayaran_->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->bunga_->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->denda_->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->titipan_->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->administrasi_->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->modal_->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->pinjaman_->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->biaya_->AdvancedSearch->IssetSession())
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
		$this->pembayaran_->AdvancedSearch->UnsetSession();
		$this->bunga_->AdvancedSearch->UnsetSession();
		$this->denda_->AdvancedSearch->UnsetSession();
		$this->titipan_->AdvancedSearch->UnsetSession();
		$this->administrasi_->AdvancedSearch->UnsetSession();
		$this->modal_->AdvancedSearch->UnsetSession();
		$this->pinjaman_->AdvancedSearch->UnsetSession();
		$this->biaya_->AdvancedSearch->UnsetSession();
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
		$this->pembayaran_->AdvancedSearch->Load();
		$this->bunga_->AdvancedSearch->Load();
		$this->denda_->AdvancedSearch->Load();
		$this->titipan_->AdvancedSearch->Load();
		$this->administrasi_->AdvancedSearch->Load();
		$this->modal_->AdvancedSearch->Load();
		$this->pinjaman_->AdvancedSearch->Load();
		$this->biaya_->AdvancedSearch->Load();
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
			$this->UpdateSort($this->pembayaran_); // pembayaran_
			$this->UpdateSort($this->bunga_); // bunga_
			$this->UpdateSort($this->denda_); // denda_
			$this->UpdateSort($this->titipan_); // titipan_
			$this->UpdateSort($this->administrasi_); // administrasi_
			$this->UpdateSort($this->modal_); // modal_
			$this->UpdateSort($this->pinjaman_); // pinjaman_
			$this->UpdateSort($this->biaya_); // biaya_
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
				$this->pembayaran_->setSort("");
				$this->bunga_->setSort("");
				$this->denda_->setSort("");
				$this->titipan_->setSort("");
				$this->administrasi_->setSort("");
				$this->modal_->setSort("");
				$this->pinjaman_->setSort("");
				$this->biaya_->setSort("");
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"ftjurnaltransaksilistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"ftjurnaltransaksilistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.ftjurnaltransaksilist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"ftjurnaltransaksilistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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

		// pembayaran_
		$this->pembayaran_->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_pembayaran_"]);
		if ($this->pembayaran_->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->pembayaran_->AdvancedSearch->SearchOperator = @$_GET["z_pembayaran_"];

		// bunga_
		$this->bunga_->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_bunga_"]);
		if ($this->bunga_->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->bunga_->AdvancedSearch->SearchOperator = @$_GET["z_bunga_"];

		// denda_
		$this->denda_->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_denda_"]);
		if ($this->denda_->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->denda_->AdvancedSearch->SearchOperator = @$_GET["z_denda_"];

		// titipan_
		$this->titipan_->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_titipan_"]);
		if ($this->titipan_->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->titipan_->AdvancedSearch->SearchOperator = @$_GET["z_titipan_"];

		// administrasi_
		$this->administrasi_->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_administrasi_"]);
		if ($this->administrasi_->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->administrasi_->AdvancedSearch->SearchOperator = @$_GET["z_administrasi_"];

		// modal_
		$this->modal_->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_modal_"]);
		if ($this->modal_->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->modal_->AdvancedSearch->SearchOperator = @$_GET["z_modal_"];

		// pinjaman_
		$this->pinjaman_->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_pinjaman_"]);
		if ($this->pinjaman_->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->pinjaman_->AdvancedSearch->SearchOperator = @$_GET["z_pinjaman_"];

		// biaya_
		$this->biaya_->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_biaya_"]);
		if ($this->biaya_->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->biaya_->AdvancedSearch->SearchOperator = @$_GET["z_biaya_"];

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
		$this->pembayaran_->DbValue = $row['pembayaran_'];
		$this->bunga_->DbValue = $row['bunga_'];
		$this->denda_->DbValue = $row['denda_'];
		$this->titipan_->DbValue = $row['titipan_'];
		$this->administrasi_->DbValue = $row['administrasi_'];
		$this->modal_->DbValue = $row['modal_'];
		$this->pinjaman_->DbValue = $row['pinjaman_'];
		$this->biaya_->DbValue = $row['biaya_'];
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

		// Convert decimal values if posted back
		if ($this->pembayaran_->FormValue == $this->pembayaran_->CurrentValue && is_numeric(ew_StrToFloat($this->pembayaran_->CurrentValue)))
			$this->pembayaran_->CurrentValue = ew_StrToFloat($this->pembayaran_->CurrentValue);

		// Convert decimal values if posted back
		if ($this->bunga_->FormValue == $this->bunga_->CurrentValue && is_numeric(ew_StrToFloat($this->bunga_->CurrentValue)))
			$this->bunga_->CurrentValue = ew_StrToFloat($this->bunga_->CurrentValue);

		// Convert decimal values if posted back
		if ($this->denda_->FormValue == $this->denda_->CurrentValue && is_numeric(ew_StrToFloat($this->denda_->CurrentValue)))
			$this->denda_->CurrentValue = ew_StrToFloat($this->denda_->CurrentValue);

		// Convert decimal values if posted back
		if ($this->titipan_->FormValue == $this->titipan_->CurrentValue && is_numeric(ew_StrToFloat($this->titipan_->CurrentValue)))
			$this->titipan_->CurrentValue = ew_StrToFloat($this->titipan_->CurrentValue);

		// Convert decimal values if posted back
		if ($this->administrasi_->FormValue == $this->administrasi_->CurrentValue && is_numeric(ew_StrToFloat($this->administrasi_->CurrentValue)))
			$this->administrasi_->CurrentValue = ew_StrToFloat($this->administrasi_->CurrentValue);

		// Convert decimal values if posted back
		if ($this->modal_->FormValue == $this->modal_->CurrentValue && is_numeric(ew_StrToFloat($this->modal_->CurrentValue)))
			$this->modal_->CurrentValue = ew_StrToFloat($this->modal_->CurrentValue);

		// Convert decimal values if posted back
		if ($this->pinjaman_->FormValue == $this->pinjaman_->CurrentValue && is_numeric(ew_StrToFloat($this->pinjaman_->CurrentValue)))
			$this->pinjaman_->CurrentValue = ew_StrToFloat($this->pinjaman_->CurrentValue);

		// Convert decimal values if posted back
		if ($this->biaya_->FormValue == $this->biaya_->CurrentValue && is_numeric(ew_StrToFloat($this->biaya_->CurrentValue)))
			$this->biaya_->CurrentValue = ew_StrToFloat($this->biaya_->CurrentValue);

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

			// pembayaran_
			$this->pembayaran_->EditAttrs["class"] = "form-control";
			$this->pembayaran_->EditCustomAttributes = "";
			$this->pembayaran_->EditValue = ew_HtmlEncode($this->pembayaran_->AdvancedSearch->SearchValue);
			$this->pembayaran_->PlaceHolder = ew_RemoveHtml($this->pembayaran_->FldCaption());

			// bunga_
			$this->bunga_->EditAttrs["class"] = "form-control";
			$this->bunga_->EditCustomAttributes = "";
			$this->bunga_->EditValue = ew_HtmlEncode($this->bunga_->AdvancedSearch->SearchValue);
			$this->bunga_->PlaceHolder = ew_RemoveHtml($this->bunga_->FldCaption());

			// denda_
			$this->denda_->EditAttrs["class"] = "form-control";
			$this->denda_->EditCustomAttributes = "";
			$this->denda_->EditValue = ew_HtmlEncode($this->denda_->AdvancedSearch->SearchValue);
			$this->denda_->PlaceHolder = ew_RemoveHtml($this->denda_->FldCaption());

			// titipan_
			$this->titipan_->EditAttrs["class"] = "form-control";
			$this->titipan_->EditCustomAttributes = "";
			$this->titipan_->EditValue = ew_HtmlEncode($this->titipan_->AdvancedSearch->SearchValue);
			$this->titipan_->PlaceHolder = ew_RemoveHtml($this->titipan_->FldCaption());

			// administrasi_
			$this->administrasi_->EditAttrs["class"] = "form-control";
			$this->administrasi_->EditCustomAttributes = "";
			$this->administrasi_->EditValue = ew_HtmlEncode($this->administrasi_->AdvancedSearch->SearchValue);
			$this->administrasi_->PlaceHolder = ew_RemoveHtml($this->administrasi_->FldCaption());

			// modal_
			$this->modal_->EditAttrs["class"] = "form-control";
			$this->modal_->EditCustomAttributes = "";
			$this->modal_->EditValue = ew_HtmlEncode($this->modal_->AdvancedSearch->SearchValue);
			$this->modal_->PlaceHolder = ew_RemoveHtml($this->modal_->FldCaption());

			// pinjaman_
			$this->pinjaman_->EditAttrs["class"] = "form-control";
			$this->pinjaman_->EditCustomAttributes = "";
			$this->pinjaman_->EditValue = ew_HtmlEncode($this->pinjaman_->AdvancedSearch->SearchValue);
			$this->pinjaman_->PlaceHolder = ew_RemoveHtml($this->pinjaman_->FldCaption());

			// biaya_
			$this->biaya_->EditAttrs["class"] = "form-control";
			$this->biaya_->EditCustomAttributes = "";
			$this->biaya_->EditValue = ew_HtmlEncode($this->biaya_->AdvancedSearch->SearchValue);
			$this->biaya_->PlaceHolder = ew_RemoveHtml($this->biaya_->FldCaption());

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
		$this->pembayaran_->AdvancedSearch->Load();
		$this->bunga_->AdvancedSearch->Load();
		$this->denda_->AdvancedSearch->Load();
		$this->titipan_->AdvancedSearch->Load();
		$this->administrasi_->AdvancedSearch->Load();
		$this->modal_->AdvancedSearch->Load();
		$this->pinjaman_->AdvancedSearch->Load();
		$this->biaya_->AdvancedSearch->Load();
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
if (!isset($tjurnaltransaksi_list)) $tjurnaltransaksi_list = new ctjurnaltransaksi_list();

// Page init
$tjurnaltransaksi_list->Page_Init();

// Page main
$tjurnaltransaksi_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tjurnaltransaksi_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = ftjurnaltransaksilist = new ew_Form("ftjurnaltransaksilist", "list");
ftjurnaltransaksilist.FormKeyCountName = '<?php echo $tjurnaltransaksi_list->FormKeyCountName ?>';

// Form_CustomValidate event
ftjurnaltransaksilist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftjurnaltransaksilist.ValidateRequired = true;
<?php } else { ?>
ftjurnaltransaksilist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftjurnaltransaksilist.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftjurnaltransaksilist.Lists["x_active"].Options = <?php echo json_encode($tjurnaltransaksi->active->Options()) ?>;

// Form object for search
var CurrentSearchForm = ftjurnaltransaksilistsrch = new ew_Form("ftjurnaltransaksilistsrch");

// Validate function for search
ftjurnaltransaksilistsrch.Validate = function(fobj) {
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
ftjurnaltransaksilistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftjurnaltransaksilistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
ftjurnaltransaksilistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
ftjurnaltransaksilistsrch.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftjurnaltransaksilistsrch.Lists["x_active"].Options = <?php echo json_encode($tjurnaltransaksi->active->Options()) ?>;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($tjurnaltransaksi_list->TotalRecs > 0 && $tjurnaltransaksi_list->ExportOptions->Visible()) { ?>
<?php $tjurnaltransaksi_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($tjurnaltransaksi_list->SearchOptions->Visible()) { ?>
<?php $tjurnaltransaksi_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($tjurnaltransaksi_list->FilterOptions->Visible()) { ?>
<?php $tjurnaltransaksi_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $tjurnaltransaksi_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($tjurnaltransaksi_list->TotalRecs <= 0)
			$tjurnaltransaksi_list->TotalRecs = $tjurnaltransaksi->SelectRecordCount();
	} else {
		if (!$tjurnaltransaksi_list->Recordset && ($tjurnaltransaksi_list->Recordset = $tjurnaltransaksi_list->LoadRecordset()))
			$tjurnaltransaksi_list->TotalRecs = $tjurnaltransaksi_list->Recordset->RecordCount();
	}
	$tjurnaltransaksi_list->StartRec = 1;
	if ($tjurnaltransaksi_list->DisplayRecs <= 0 || ($tjurnaltransaksi->Export <> "" && $tjurnaltransaksi->ExportAll)) // Display all records
		$tjurnaltransaksi_list->DisplayRecs = $tjurnaltransaksi_list->TotalRecs;
	if (!($tjurnaltransaksi->Export <> "" && $tjurnaltransaksi->ExportAll))
		$tjurnaltransaksi_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$tjurnaltransaksi_list->Recordset = $tjurnaltransaksi_list->LoadRecordset($tjurnaltransaksi_list->StartRec-1, $tjurnaltransaksi_list->DisplayRecs);

	// Set no record found message
	if ($tjurnaltransaksi->CurrentAction == "" && $tjurnaltransaksi_list->TotalRecs == 0) {
		if ($tjurnaltransaksi_list->SearchWhere == "0=101")
			$tjurnaltransaksi_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$tjurnaltransaksi_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$tjurnaltransaksi_list->RenderOtherOptions();
?>
<?php if ($tjurnaltransaksi->Export == "" && $tjurnaltransaksi->CurrentAction == "") { ?>
<form name="ftjurnaltransaksilistsrch" id="ftjurnaltransaksilistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($tjurnaltransaksi_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="ftjurnaltransaksilistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="tjurnaltransaksi">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$tjurnaltransaksi_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$tjurnaltransaksi->RowType = EW_ROWTYPE_SEARCH;

// Render row
$tjurnaltransaksi->ResetAttrs();
$tjurnaltransaksi_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($tjurnaltransaksi->active->Visible) { // active ?>
	<div id="xsc_active" class="ewCell form-group">
		<label class="ewSearchCaption ewLabel"><?php echo $tjurnaltransaksi->active->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_active" id="z_active" value="="></span>
		<span class="ewSearchField">
<div id="tp_x_active" class="ewTemplate"><input type="radio" data-table="tjurnaltransaksi" data-field="x_active" data-value-separator="<?php echo $tjurnaltransaksi->active->DisplayValueSeparatorAttribute() ?>" name="x_active" id="x_active" value="{value}"<?php echo $tjurnaltransaksi->active->EditAttributes() ?>></div>
<div id="dsl_x_active" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $tjurnaltransaksi->active->RadioButtonListHtml(FALSE, "x_active") ?>
</div></div>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($tjurnaltransaksi_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($tjurnaltransaksi_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $tjurnaltransaksi_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($tjurnaltransaksi_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($tjurnaltransaksi_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($tjurnaltransaksi_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($tjurnaltransaksi_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php $tjurnaltransaksi_list->ShowPageHeader(); ?>
<?php
$tjurnaltransaksi_list->ShowMessage();
?>
<?php if ($tjurnaltransaksi_list->TotalRecs > 0 || $tjurnaltransaksi->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid tjurnaltransaksi">
<form name="ftjurnaltransaksilist" id="ftjurnaltransaksilist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tjurnaltransaksi_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tjurnaltransaksi_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tjurnaltransaksi">
<div id="gmp_tjurnaltransaksi" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($tjurnaltransaksi_list->TotalRecs > 0 || $tjurnaltransaksi->CurrentAction == "gridedit") { ?>
<table id="tbl_tjurnaltransaksilist" class="table ewTable">
<?php echo $tjurnaltransaksi->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$tjurnaltransaksi_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$tjurnaltransaksi_list->RenderListOptions();

// Render list options (header, left)
$tjurnaltransaksi_list->ListOptions->Render("header", "left");
?>
<?php if ($tjurnaltransaksi->tanggal->Visible) { // tanggal ?>
	<?php if ($tjurnaltransaksi->SortUrl($tjurnaltransaksi->tanggal) == "") { ?>
		<th data-name="tanggal"><div id="elh_tjurnaltransaksi_tanggal" class="tjurnaltransaksi_tanggal"><div class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->tanggal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tanggal"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnaltransaksi->SortUrl($tjurnaltransaksi->tanggal) ?>',1);"><div id="elh_tjurnaltransaksi_tanggal" class="tjurnaltransaksi_tanggal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->tanggal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tjurnaltransaksi->tanggal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnaltransaksi->tanggal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnaltransaksi->periode->Visible) { // periode ?>
	<?php if ($tjurnaltransaksi->SortUrl($tjurnaltransaksi->periode) == "") { ?>
		<th data-name="periode"><div id="elh_tjurnaltransaksi_periode" class="tjurnaltransaksi_periode"><div class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->periode->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="periode"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnaltransaksi->SortUrl($tjurnaltransaksi->periode) ?>',1);"><div id="elh_tjurnaltransaksi_periode" class="tjurnaltransaksi_periode">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->periode->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tjurnaltransaksi->periode->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnaltransaksi->periode->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnaltransaksi->id->Visible) { // id ?>
	<?php if ($tjurnaltransaksi->SortUrl($tjurnaltransaksi->id) == "") { ?>
		<th data-name="id"><div id="elh_tjurnaltransaksi_id" class="tjurnaltransaksi_id"><div class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnaltransaksi->SortUrl($tjurnaltransaksi->id) ?>',1);"><div id="elh_tjurnaltransaksi_id" class="tjurnaltransaksi_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->id->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tjurnaltransaksi->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnaltransaksi->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnaltransaksi->nomor->Visible) { // nomor ?>
	<?php if ($tjurnaltransaksi->SortUrl($tjurnaltransaksi->nomor) == "") { ?>
		<th data-name="nomor"><div id="elh_tjurnaltransaksi_nomor" class="tjurnaltransaksi_nomor"><div class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->nomor->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nomor"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnaltransaksi->SortUrl($tjurnaltransaksi->nomor) ?>',1);"><div id="elh_tjurnaltransaksi_nomor" class="tjurnaltransaksi_nomor">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->nomor->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tjurnaltransaksi->nomor->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnaltransaksi->nomor->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnaltransaksi->transaksi->Visible) { // transaksi ?>
	<?php if ($tjurnaltransaksi->SortUrl($tjurnaltransaksi->transaksi) == "") { ?>
		<th data-name="transaksi"><div id="elh_tjurnaltransaksi_transaksi" class="tjurnaltransaksi_transaksi"><div class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->transaksi->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="transaksi"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnaltransaksi->SortUrl($tjurnaltransaksi->transaksi) ?>',1);"><div id="elh_tjurnaltransaksi_transaksi" class="tjurnaltransaksi_transaksi">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->transaksi->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tjurnaltransaksi->transaksi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnaltransaksi->transaksi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnaltransaksi->referensi->Visible) { // referensi ?>
	<?php if ($tjurnaltransaksi->SortUrl($tjurnaltransaksi->referensi) == "") { ?>
		<th data-name="referensi"><div id="elh_tjurnaltransaksi_referensi" class="tjurnaltransaksi_referensi"><div class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->referensi->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="referensi"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnaltransaksi->SortUrl($tjurnaltransaksi->referensi) ?>',1);"><div id="elh_tjurnaltransaksi_referensi" class="tjurnaltransaksi_referensi">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->referensi->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tjurnaltransaksi->referensi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnaltransaksi->referensi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnaltransaksi->model->Visible) { // model ?>
	<?php if ($tjurnaltransaksi->SortUrl($tjurnaltransaksi->model) == "") { ?>
		<th data-name="model"><div id="elh_tjurnaltransaksi_model" class="tjurnaltransaksi_model"><div class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->model->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="model"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnaltransaksi->SortUrl($tjurnaltransaksi->model) ?>',1);"><div id="elh_tjurnaltransaksi_model" class="tjurnaltransaksi_model">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->model->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tjurnaltransaksi->model->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnaltransaksi->model->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnaltransaksi->rekening->Visible) { // rekening ?>
	<?php if ($tjurnaltransaksi->SortUrl($tjurnaltransaksi->rekening) == "") { ?>
		<th data-name="rekening"><div id="elh_tjurnaltransaksi_rekening" class="tjurnaltransaksi_rekening"><div class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->rekening->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="rekening"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnaltransaksi->SortUrl($tjurnaltransaksi->rekening) ?>',1);"><div id="elh_tjurnaltransaksi_rekening" class="tjurnaltransaksi_rekening">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->rekening->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tjurnaltransaksi->rekening->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnaltransaksi->rekening->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnaltransaksi->debet->Visible) { // debet ?>
	<?php if ($tjurnaltransaksi->SortUrl($tjurnaltransaksi->debet) == "") { ?>
		<th data-name="debet"><div id="elh_tjurnaltransaksi_debet" class="tjurnaltransaksi_debet"><div class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->debet->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="debet"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnaltransaksi->SortUrl($tjurnaltransaksi->debet) ?>',1);"><div id="elh_tjurnaltransaksi_debet" class="tjurnaltransaksi_debet">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->debet->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tjurnaltransaksi->debet->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnaltransaksi->debet->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnaltransaksi->credit->Visible) { // credit ?>
	<?php if ($tjurnaltransaksi->SortUrl($tjurnaltransaksi->credit) == "") { ?>
		<th data-name="credit"><div id="elh_tjurnaltransaksi_credit" class="tjurnaltransaksi_credit"><div class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->credit->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="credit"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnaltransaksi->SortUrl($tjurnaltransaksi->credit) ?>',1);"><div id="elh_tjurnaltransaksi_credit" class="tjurnaltransaksi_credit">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->credit->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tjurnaltransaksi->credit->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnaltransaksi->credit->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnaltransaksi->pembayaran_->Visible) { // pembayaran_ ?>
	<?php if ($tjurnaltransaksi->SortUrl($tjurnaltransaksi->pembayaran_) == "") { ?>
		<th data-name="pembayaran_"><div id="elh_tjurnaltransaksi_pembayaran_" class="tjurnaltransaksi_pembayaran_"><div class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->pembayaran_->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pembayaran_"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnaltransaksi->SortUrl($tjurnaltransaksi->pembayaran_) ?>',1);"><div id="elh_tjurnaltransaksi_pembayaran_" class="tjurnaltransaksi_pembayaran_">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->pembayaran_->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tjurnaltransaksi->pembayaran_->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnaltransaksi->pembayaran_->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnaltransaksi->bunga_->Visible) { // bunga_ ?>
	<?php if ($tjurnaltransaksi->SortUrl($tjurnaltransaksi->bunga_) == "") { ?>
		<th data-name="bunga_"><div id="elh_tjurnaltransaksi_bunga_" class="tjurnaltransaksi_bunga_"><div class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->bunga_->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bunga_"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnaltransaksi->SortUrl($tjurnaltransaksi->bunga_) ?>',1);"><div id="elh_tjurnaltransaksi_bunga_" class="tjurnaltransaksi_bunga_">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->bunga_->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tjurnaltransaksi->bunga_->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnaltransaksi->bunga_->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnaltransaksi->denda_->Visible) { // denda_ ?>
	<?php if ($tjurnaltransaksi->SortUrl($tjurnaltransaksi->denda_) == "") { ?>
		<th data-name="denda_"><div id="elh_tjurnaltransaksi_denda_" class="tjurnaltransaksi_denda_"><div class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->denda_->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="denda_"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnaltransaksi->SortUrl($tjurnaltransaksi->denda_) ?>',1);"><div id="elh_tjurnaltransaksi_denda_" class="tjurnaltransaksi_denda_">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->denda_->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tjurnaltransaksi->denda_->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnaltransaksi->denda_->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnaltransaksi->titipan_->Visible) { // titipan_ ?>
	<?php if ($tjurnaltransaksi->SortUrl($tjurnaltransaksi->titipan_) == "") { ?>
		<th data-name="titipan_"><div id="elh_tjurnaltransaksi_titipan_" class="tjurnaltransaksi_titipan_"><div class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->titipan_->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="titipan_"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnaltransaksi->SortUrl($tjurnaltransaksi->titipan_) ?>',1);"><div id="elh_tjurnaltransaksi_titipan_" class="tjurnaltransaksi_titipan_">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->titipan_->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tjurnaltransaksi->titipan_->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnaltransaksi->titipan_->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnaltransaksi->administrasi_->Visible) { // administrasi_ ?>
	<?php if ($tjurnaltransaksi->SortUrl($tjurnaltransaksi->administrasi_) == "") { ?>
		<th data-name="administrasi_"><div id="elh_tjurnaltransaksi_administrasi_" class="tjurnaltransaksi_administrasi_"><div class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->administrasi_->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="administrasi_"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnaltransaksi->SortUrl($tjurnaltransaksi->administrasi_) ?>',1);"><div id="elh_tjurnaltransaksi_administrasi_" class="tjurnaltransaksi_administrasi_">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->administrasi_->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tjurnaltransaksi->administrasi_->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnaltransaksi->administrasi_->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnaltransaksi->modal_->Visible) { // modal_ ?>
	<?php if ($tjurnaltransaksi->SortUrl($tjurnaltransaksi->modal_) == "") { ?>
		<th data-name="modal_"><div id="elh_tjurnaltransaksi_modal_" class="tjurnaltransaksi_modal_"><div class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->modal_->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="modal_"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnaltransaksi->SortUrl($tjurnaltransaksi->modal_) ?>',1);"><div id="elh_tjurnaltransaksi_modal_" class="tjurnaltransaksi_modal_">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->modal_->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tjurnaltransaksi->modal_->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnaltransaksi->modal_->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnaltransaksi->pinjaman_->Visible) { // pinjaman_ ?>
	<?php if ($tjurnaltransaksi->SortUrl($tjurnaltransaksi->pinjaman_) == "") { ?>
		<th data-name="pinjaman_"><div id="elh_tjurnaltransaksi_pinjaman_" class="tjurnaltransaksi_pinjaman_"><div class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->pinjaman_->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pinjaman_"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnaltransaksi->SortUrl($tjurnaltransaksi->pinjaman_) ?>',1);"><div id="elh_tjurnaltransaksi_pinjaman_" class="tjurnaltransaksi_pinjaman_">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->pinjaman_->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tjurnaltransaksi->pinjaman_->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnaltransaksi->pinjaman_->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnaltransaksi->biaya_->Visible) { // biaya_ ?>
	<?php if ($tjurnaltransaksi->SortUrl($tjurnaltransaksi->biaya_) == "") { ?>
		<th data-name="biaya_"><div id="elh_tjurnaltransaksi_biaya_" class="tjurnaltransaksi_biaya_"><div class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->biaya_->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="biaya_"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnaltransaksi->SortUrl($tjurnaltransaksi->biaya_) ?>',1);"><div id="elh_tjurnaltransaksi_biaya_" class="tjurnaltransaksi_biaya_">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->biaya_->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tjurnaltransaksi->biaya_->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnaltransaksi->biaya_->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnaltransaksi->kantor->Visible) { // kantor ?>
	<?php if ($tjurnaltransaksi->SortUrl($tjurnaltransaksi->kantor) == "") { ?>
		<th data-name="kantor"><div id="elh_tjurnaltransaksi_kantor" class="tjurnaltransaksi_kantor"><div class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->kantor->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="kantor"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnaltransaksi->SortUrl($tjurnaltransaksi->kantor) ?>',1);"><div id="elh_tjurnaltransaksi_kantor" class="tjurnaltransaksi_kantor">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->kantor->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tjurnaltransaksi->kantor->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnaltransaksi->kantor->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnaltransaksi->keterangan->Visible) { // keterangan ?>
	<?php if ($tjurnaltransaksi->SortUrl($tjurnaltransaksi->keterangan) == "") { ?>
		<th data-name="keterangan"><div id="elh_tjurnaltransaksi_keterangan" class="tjurnaltransaksi_keterangan"><div class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->keterangan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="keterangan"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnaltransaksi->SortUrl($tjurnaltransaksi->keterangan) ?>',1);"><div id="elh_tjurnaltransaksi_keterangan" class="tjurnaltransaksi_keterangan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->keterangan->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tjurnaltransaksi->keterangan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnaltransaksi->keterangan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnaltransaksi->active->Visible) { // active ?>
	<?php if ($tjurnaltransaksi->SortUrl($tjurnaltransaksi->active) == "") { ?>
		<th data-name="active"><div id="elh_tjurnaltransaksi_active" class="tjurnaltransaksi_active"><div class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->active->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="active"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnaltransaksi->SortUrl($tjurnaltransaksi->active) ?>',1);"><div id="elh_tjurnaltransaksi_active" class="tjurnaltransaksi_active">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->active->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tjurnaltransaksi->active->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnaltransaksi->active->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnaltransaksi->ip->Visible) { // ip ?>
	<?php if ($tjurnaltransaksi->SortUrl($tjurnaltransaksi->ip) == "") { ?>
		<th data-name="ip"><div id="elh_tjurnaltransaksi_ip" class="tjurnaltransaksi_ip"><div class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->ip->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ip"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnaltransaksi->SortUrl($tjurnaltransaksi->ip) ?>',1);"><div id="elh_tjurnaltransaksi_ip" class="tjurnaltransaksi_ip">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->ip->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tjurnaltransaksi->ip->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnaltransaksi->ip->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnaltransaksi->status->Visible) { // status ?>
	<?php if ($tjurnaltransaksi->SortUrl($tjurnaltransaksi->status) == "") { ?>
		<th data-name="status"><div id="elh_tjurnaltransaksi_status" class="tjurnaltransaksi_status"><div class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->status->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnaltransaksi->SortUrl($tjurnaltransaksi->status) ?>',1);"><div id="elh_tjurnaltransaksi_status" class="tjurnaltransaksi_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->status->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tjurnaltransaksi->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnaltransaksi->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnaltransaksi->user->Visible) { // user ?>
	<?php if ($tjurnaltransaksi->SortUrl($tjurnaltransaksi->user) == "") { ?>
		<th data-name="user"><div id="elh_tjurnaltransaksi_user" class="tjurnaltransaksi_user"><div class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->user->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="user"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnaltransaksi->SortUrl($tjurnaltransaksi->user) ?>',1);"><div id="elh_tjurnaltransaksi_user" class="tjurnaltransaksi_user">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->user->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tjurnaltransaksi->user->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnaltransaksi->user->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnaltransaksi->created->Visible) { // created ?>
	<?php if ($tjurnaltransaksi->SortUrl($tjurnaltransaksi->created) == "") { ?>
		<th data-name="created"><div id="elh_tjurnaltransaksi_created" class="tjurnaltransaksi_created"><div class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->created->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="created"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnaltransaksi->SortUrl($tjurnaltransaksi->created) ?>',1);"><div id="elh_tjurnaltransaksi_created" class="tjurnaltransaksi_created">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->created->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tjurnaltransaksi->created->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnaltransaksi->created->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tjurnaltransaksi->modified->Visible) { // modified ?>
	<?php if ($tjurnaltransaksi->SortUrl($tjurnaltransaksi->modified) == "") { ?>
		<th data-name="modified"><div id="elh_tjurnaltransaksi_modified" class="tjurnaltransaksi_modified"><div class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->modified->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="modified"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tjurnaltransaksi->SortUrl($tjurnaltransaksi->modified) ?>',1);"><div id="elh_tjurnaltransaksi_modified" class="tjurnaltransaksi_modified">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tjurnaltransaksi->modified->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tjurnaltransaksi->modified->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tjurnaltransaksi->modified->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$tjurnaltransaksi_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($tjurnaltransaksi->ExportAll && $tjurnaltransaksi->Export <> "") {
	$tjurnaltransaksi_list->StopRec = $tjurnaltransaksi_list->TotalRecs;
} else {

	// Set the last record to display
	if ($tjurnaltransaksi_list->TotalRecs > $tjurnaltransaksi_list->StartRec + $tjurnaltransaksi_list->DisplayRecs - 1)
		$tjurnaltransaksi_list->StopRec = $tjurnaltransaksi_list->StartRec + $tjurnaltransaksi_list->DisplayRecs - 1;
	else
		$tjurnaltransaksi_list->StopRec = $tjurnaltransaksi_list->TotalRecs;
}
$tjurnaltransaksi_list->RecCnt = $tjurnaltransaksi_list->StartRec - 1;
if ($tjurnaltransaksi_list->Recordset && !$tjurnaltransaksi_list->Recordset->EOF) {
	$tjurnaltransaksi_list->Recordset->MoveFirst();
	$bSelectLimit = $tjurnaltransaksi_list->UseSelectLimit;
	if (!$bSelectLimit && $tjurnaltransaksi_list->StartRec > 1)
		$tjurnaltransaksi_list->Recordset->Move($tjurnaltransaksi_list->StartRec - 1);
} elseif (!$tjurnaltransaksi->AllowAddDeleteRow && $tjurnaltransaksi_list->StopRec == 0) {
	$tjurnaltransaksi_list->StopRec = $tjurnaltransaksi->GridAddRowCount;
}

// Initialize aggregate
$tjurnaltransaksi->RowType = EW_ROWTYPE_AGGREGATEINIT;
$tjurnaltransaksi->ResetAttrs();
$tjurnaltransaksi_list->RenderRow();
while ($tjurnaltransaksi_list->RecCnt < $tjurnaltransaksi_list->StopRec) {
	$tjurnaltransaksi_list->RecCnt++;
	if (intval($tjurnaltransaksi_list->RecCnt) >= intval($tjurnaltransaksi_list->StartRec)) {
		$tjurnaltransaksi_list->RowCnt++;

		// Set up key count
		$tjurnaltransaksi_list->KeyCount = $tjurnaltransaksi_list->RowIndex;

		// Init row class and style
		$tjurnaltransaksi->ResetAttrs();
		$tjurnaltransaksi->CssClass = "";
		if ($tjurnaltransaksi->CurrentAction == "gridadd") {
		} else {
			$tjurnaltransaksi_list->LoadRowValues($tjurnaltransaksi_list->Recordset); // Load row values
		}
		$tjurnaltransaksi->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$tjurnaltransaksi->RowAttrs = array_merge($tjurnaltransaksi->RowAttrs, array('data-rowindex'=>$tjurnaltransaksi_list->RowCnt, 'id'=>'r' . $tjurnaltransaksi_list->RowCnt . '_tjurnaltransaksi', 'data-rowtype'=>$tjurnaltransaksi->RowType));

		// Render row
		$tjurnaltransaksi_list->RenderRow();

		// Render list options
		$tjurnaltransaksi_list->RenderListOptions();
?>
	<tr<?php echo $tjurnaltransaksi->RowAttributes() ?>>
<?php

// Render list options (body, left)
$tjurnaltransaksi_list->ListOptions->Render("body", "left", $tjurnaltransaksi_list->RowCnt);
?>
	<?php if ($tjurnaltransaksi->tanggal->Visible) { // tanggal ?>
		<td data-name="tanggal"<?php echo $tjurnaltransaksi->tanggal->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_list->RowCnt ?>_tjurnaltransaksi_tanggal" class="tjurnaltransaksi_tanggal">
<span<?php echo $tjurnaltransaksi->tanggal->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->tanggal->ListViewValue() ?></span>
</span>
<a id="<?php echo $tjurnaltransaksi_list->PageObjName . "_row_" . $tjurnaltransaksi_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($tjurnaltransaksi->periode->Visible) { // periode ?>
		<td data-name="periode"<?php echo $tjurnaltransaksi->periode->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_list->RowCnt ?>_tjurnaltransaksi_periode" class="tjurnaltransaksi_periode">
<span<?php echo $tjurnaltransaksi->periode->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->periode->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnaltransaksi->id->Visible) { // id ?>
		<td data-name="id"<?php echo $tjurnaltransaksi->id->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_list->RowCnt ?>_tjurnaltransaksi_id" class="tjurnaltransaksi_id">
<span<?php echo $tjurnaltransaksi->id->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnaltransaksi->nomor->Visible) { // nomor ?>
		<td data-name="nomor"<?php echo $tjurnaltransaksi->nomor->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_list->RowCnt ?>_tjurnaltransaksi_nomor" class="tjurnaltransaksi_nomor">
<span<?php echo $tjurnaltransaksi->nomor->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->nomor->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnaltransaksi->transaksi->Visible) { // transaksi ?>
		<td data-name="transaksi"<?php echo $tjurnaltransaksi->transaksi->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_list->RowCnt ?>_tjurnaltransaksi_transaksi" class="tjurnaltransaksi_transaksi">
<span<?php echo $tjurnaltransaksi->transaksi->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->transaksi->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnaltransaksi->referensi->Visible) { // referensi ?>
		<td data-name="referensi"<?php echo $tjurnaltransaksi->referensi->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_list->RowCnt ?>_tjurnaltransaksi_referensi" class="tjurnaltransaksi_referensi">
<span<?php echo $tjurnaltransaksi->referensi->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->referensi->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnaltransaksi->model->Visible) { // model ?>
		<td data-name="model"<?php echo $tjurnaltransaksi->model->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_list->RowCnt ?>_tjurnaltransaksi_model" class="tjurnaltransaksi_model">
<span<?php echo $tjurnaltransaksi->model->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->model->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnaltransaksi->rekening->Visible) { // rekening ?>
		<td data-name="rekening"<?php echo $tjurnaltransaksi->rekening->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_list->RowCnt ?>_tjurnaltransaksi_rekening" class="tjurnaltransaksi_rekening">
<span<?php echo $tjurnaltransaksi->rekening->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->rekening->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnaltransaksi->debet->Visible) { // debet ?>
		<td data-name="debet"<?php echo $tjurnaltransaksi->debet->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_list->RowCnt ?>_tjurnaltransaksi_debet" class="tjurnaltransaksi_debet">
<span<?php echo $tjurnaltransaksi->debet->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->debet->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnaltransaksi->credit->Visible) { // credit ?>
		<td data-name="credit"<?php echo $tjurnaltransaksi->credit->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_list->RowCnt ?>_tjurnaltransaksi_credit" class="tjurnaltransaksi_credit">
<span<?php echo $tjurnaltransaksi->credit->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->credit->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnaltransaksi->pembayaran_->Visible) { // pembayaran_ ?>
		<td data-name="pembayaran_"<?php echo $tjurnaltransaksi->pembayaran_->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_list->RowCnt ?>_tjurnaltransaksi_pembayaran_" class="tjurnaltransaksi_pembayaran_">
<span<?php echo $tjurnaltransaksi->pembayaran_->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->pembayaran_->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnaltransaksi->bunga_->Visible) { // bunga_ ?>
		<td data-name="bunga_"<?php echo $tjurnaltransaksi->bunga_->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_list->RowCnt ?>_tjurnaltransaksi_bunga_" class="tjurnaltransaksi_bunga_">
<span<?php echo $tjurnaltransaksi->bunga_->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->bunga_->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnaltransaksi->denda_->Visible) { // denda_ ?>
		<td data-name="denda_"<?php echo $tjurnaltransaksi->denda_->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_list->RowCnt ?>_tjurnaltransaksi_denda_" class="tjurnaltransaksi_denda_">
<span<?php echo $tjurnaltransaksi->denda_->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->denda_->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnaltransaksi->titipan_->Visible) { // titipan_ ?>
		<td data-name="titipan_"<?php echo $tjurnaltransaksi->titipan_->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_list->RowCnt ?>_tjurnaltransaksi_titipan_" class="tjurnaltransaksi_titipan_">
<span<?php echo $tjurnaltransaksi->titipan_->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->titipan_->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnaltransaksi->administrasi_->Visible) { // administrasi_ ?>
		<td data-name="administrasi_"<?php echo $tjurnaltransaksi->administrasi_->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_list->RowCnt ?>_tjurnaltransaksi_administrasi_" class="tjurnaltransaksi_administrasi_">
<span<?php echo $tjurnaltransaksi->administrasi_->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->administrasi_->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnaltransaksi->modal_->Visible) { // modal_ ?>
		<td data-name="modal_"<?php echo $tjurnaltransaksi->modal_->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_list->RowCnt ?>_tjurnaltransaksi_modal_" class="tjurnaltransaksi_modal_">
<span<?php echo $tjurnaltransaksi->modal_->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->modal_->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnaltransaksi->pinjaman_->Visible) { // pinjaman_ ?>
		<td data-name="pinjaman_"<?php echo $tjurnaltransaksi->pinjaman_->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_list->RowCnt ?>_tjurnaltransaksi_pinjaman_" class="tjurnaltransaksi_pinjaman_">
<span<?php echo $tjurnaltransaksi->pinjaman_->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->pinjaman_->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnaltransaksi->biaya_->Visible) { // biaya_ ?>
		<td data-name="biaya_"<?php echo $tjurnaltransaksi->biaya_->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_list->RowCnt ?>_tjurnaltransaksi_biaya_" class="tjurnaltransaksi_biaya_">
<span<?php echo $tjurnaltransaksi->biaya_->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->biaya_->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnaltransaksi->kantor->Visible) { // kantor ?>
		<td data-name="kantor"<?php echo $tjurnaltransaksi->kantor->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_list->RowCnt ?>_tjurnaltransaksi_kantor" class="tjurnaltransaksi_kantor">
<span<?php echo $tjurnaltransaksi->kantor->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->kantor->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnaltransaksi->keterangan->Visible) { // keterangan ?>
		<td data-name="keterangan"<?php echo $tjurnaltransaksi->keterangan->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_list->RowCnt ?>_tjurnaltransaksi_keterangan" class="tjurnaltransaksi_keterangan">
<span<?php echo $tjurnaltransaksi->keterangan->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->keterangan->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnaltransaksi->active->Visible) { // active ?>
		<td data-name="active"<?php echo $tjurnaltransaksi->active->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_list->RowCnt ?>_tjurnaltransaksi_active" class="tjurnaltransaksi_active">
<span<?php echo $tjurnaltransaksi->active->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->active->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnaltransaksi->ip->Visible) { // ip ?>
		<td data-name="ip"<?php echo $tjurnaltransaksi->ip->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_list->RowCnt ?>_tjurnaltransaksi_ip" class="tjurnaltransaksi_ip">
<span<?php echo $tjurnaltransaksi->ip->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->ip->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnaltransaksi->status->Visible) { // status ?>
		<td data-name="status"<?php echo $tjurnaltransaksi->status->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_list->RowCnt ?>_tjurnaltransaksi_status" class="tjurnaltransaksi_status">
<span<?php echo $tjurnaltransaksi->status->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->status->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnaltransaksi->user->Visible) { // user ?>
		<td data-name="user"<?php echo $tjurnaltransaksi->user->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_list->RowCnt ?>_tjurnaltransaksi_user" class="tjurnaltransaksi_user">
<span<?php echo $tjurnaltransaksi->user->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->user->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnaltransaksi->created->Visible) { // created ?>
		<td data-name="created"<?php echo $tjurnaltransaksi->created->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_list->RowCnt ?>_tjurnaltransaksi_created" class="tjurnaltransaksi_created">
<span<?php echo $tjurnaltransaksi->created->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->created->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tjurnaltransaksi->modified->Visible) { // modified ?>
		<td data-name="modified"<?php echo $tjurnaltransaksi->modified->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_list->RowCnt ?>_tjurnaltransaksi_modified" class="tjurnaltransaksi_modified">
<span<?php echo $tjurnaltransaksi->modified->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->modified->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$tjurnaltransaksi_list->ListOptions->Render("body", "right", $tjurnaltransaksi_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($tjurnaltransaksi->CurrentAction <> "gridadd")
		$tjurnaltransaksi_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($tjurnaltransaksi->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($tjurnaltransaksi_list->Recordset)
	$tjurnaltransaksi_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($tjurnaltransaksi->CurrentAction <> "gridadd" && $tjurnaltransaksi->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($tjurnaltransaksi_list->Pager)) $tjurnaltransaksi_list->Pager = new cPrevNextPager($tjurnaltransaksi_list->StartRec, $tjurnaltransaksi_list->DisplayRecs, $tjurnaltransaksi_list->TotalRecs) ?>
<?php if ($tjurnaltransaksi_list->Pager->RecordCount > 0 && $tjurnaltransaksi_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($tjurnaltransaksi_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $tjurnaltransaksi_list->PageUrl() ?>start=<?php echo $tjurnaltransaksi_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($tjurnaltransaksi_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $tjurnaltransaksi_list->PageUrl() ?>start=<?php echo $tjurnaltransaksi_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $tjurnaltransaksi_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($tjurnaltransaksi_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $tjurnaltransaksi_list->PageUrl() ?>start=<?php echo $tjurnaltransaksi_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($tjurnaltransaksi_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $tjurnaltransaksi_list->PageUrl() ?>start=<?php echo $tjurnaltransaksi_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $tjurnaltransaksi_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $tjurnaltransaksi_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $tjurnaltransaksi_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $tjurnaltransaksi_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($tjurnaltransaksi_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($tjurnaltransaksi_list->TotalRecs == 0 && $tjurnaltransaksi->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($tjurnaltransaksi_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
ftjurnaltransaksilistsrch.FilterList = <?php echo $tjurnaltransaksi_list->GetFilterList() ?>;
ftjurnaltransaksilistsrch.Init();
ftjurnaltransaksilist.Init();
</script>
<?php
$tjurnaltransaksi_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tjurnaltransaksi_list->Page_Terminate();
?>
