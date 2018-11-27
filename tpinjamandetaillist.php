<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "tpinjamandetailinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$tpinjamandetail_list = NULL; // Initialize page object first

class ctpinjamandetail_list extends ctpinjamandetail {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tpinjamandetail';

	// Page object name
	var $PageObjName = 'tpinjamandetail_list';

	// Grid form hidden field names
	var $FormName = 'ftpinjamandetaillist';
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

		// Table object (tpinjamandetail)
		if (!isset($GLOBALS["tpinjamandetail"]) || get_class($GLOBALS["tpinjamandetail"]) == "ctpinjamandetail") {
			$GLOBALS["tpinjamandetail"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tpinjamandetail"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "tpinjamandetailadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "tpinjamandetaildelete.php";
		$this->MultiUpdateUrl = "tpinjamandetailupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tpinjamandetail', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption ftpinjamandetaillistsrch";

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
		$this->angsuran->SetVisibility();
		$this->masaangsuran->SetVisibility();
		$this->dispensasidenda->SetVisibility();
		$this->plafond->SetVisibility();
		$this->angsuranpokok->SetVisibility();
		$this->angsuranpokokauto->SetVisibility();
		$this->angsuranbunga->SetVisibility();
		$this->angsuranbungaauto->SetVisibility();
		$this->denda->SetVisibility();
		$this->dendapersen->SetVisibility();
		$this->totalangsuran->SetVisibility();
		$this->totalangsuranauto->SetVisibility();
		$this->sisaangsuran->SetVisibility();
		$this->sisaangsuranauto->SetVisibility();
		$this->tanggalbayar->SetVisibility();
		$this->terlambat->SetVisibility();
		$this->bayarpokok->SetVisibility();
		$this->bayarpokokauto->SetVisibility();
		$this->bayarbunga->SetVisibility();
		$this->bayarbungaauto->SetVisibility();
		$this->bayardenda->SetVisibility();
		$this->bayardendaauto->SetVisibility();
		$this->bayartitipan->SetVisibility();
		$this->bayartitipanauto->SetVisibility();
		$this->totalbayar->SetVisibility();
		$this->totalbayarauto->SetVisibility();
		$this->pelunasan->SetVisibility();
		$this->pelunasanauto->SetVisibility();
		$this->finalty->SetVisibility();
		$this->finaltyauto->SetVisibility();
		$this->status->SetVisibility();
		$this->keterangan->SetVisibility();

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
		global $EW_EXPORT, $tpinjamandetail;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($tpinjamandetail);
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
			$this->id->setFormValue($arrKeyFlds[0]);
			$this->angsuran->setFormValue($arrKeyFlds[1]);
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
			$sSavedFilterList = isset($UserProfile) ? $UserProfile->GetSearchFilters(CurrentUserName(), "ftpinjamandetaillistsrch") : "";
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->tanggal->AdvancedSearch->ToJSON(), ","); // Field tanggal
		$sFilterList = ew_Concat($sFilterList, $this->periode->AdvancedSearch->ToJSON(), ","); // Field periode
		$sFilterList = ew_Concat($sFilterList, $this->id->AdvancedSearch->ToJSON(), ","); // Field id
		$sFilterList = ew_Concat($sFilterList, $this->angsuran->AdvancedSearch->ToJSON(), ","); // Field angsuran
		$sFilterList = ew_Concat($sFilterList, $this->masaangsuran->AdvancedSearch->ToJSON(), ","); // Field masaangsuran
		$sFilterList = ew_Concat($sFilterList, $this->dispensasidenda->AdvancedSearch->ToJSON(), ","); // Field dispensasidenda
		$sFilterList = ew_Concat($sFilterList, $this->plafond->AdvancedSearch->ToJSON(), ","); // Field plafond
		$sFilterList = ew_Concat($sFilterList, $this->angsuranpokok->AdvancedSearch->ToJSON(), ","); // Field angsuranpokok
		$sFilterList = ew_Concat($sFilterList, $this->angsuranpokokauto->AdvancedSearch->ToJSON(), ","); // Field angsuranpokokauto
		$sFilterList = ew_Concat($sFilterList, $this->angsuranbunga->AdvancedSearch->ToJSON(), ","); // Field angsuranbunga
		$sFilterList = ew_Concat($sFilterList, $this->angsuranbungaauto->AdvancedSearch->ToJSON(), ","); // Field angsuranbungaauto
		$sFilterList = ew_Concat($sFilterList, $this->denda->AdvancedSearch->ToJSON(), ","); // Field denda
		$sFilterList = ew_Concat($sFilterList, $this->dendapersen->AdvancedSearch->ToJSON(), ","); // Field dendapersen
		$sFilterList = ew_Concat($sFilterList, $this->totalangsuran->AdvancedSearch->ToJSON(), ","); // Field totalangsuran
		$sFilterList = ew_Concat($sFilterList, $this->totalangsuranauto->AdvancedSearch->ToJSON(), ","); // Field totalangsuranauto
		$sFilterList = ew_Concat($sFilterList, $this->sisaangsuran->AdvancedSearch->ToJSON(), ","); // Field sisaangsuran
		$sFilterList = ew_Concat($sFilterList, $this->sisaangsuranauto->AdvancedSearch->ToJSON(), ","); // Field sisaangsuranauto
		$sFilterList = ew_Concat($sFilterList, $this->tanggalbayar->AdvancedSearch->ToJSON(), ","); // Field tanggalbayar
		$sFilterList = ew_Concat($sFilterList, $this->terlambat->AdvancedSearch->ToJSON(), ","); // Field terlambat
		$sFilterList = ew_Concat($sFilterList, $this->bayarpokok->AdvancedSearch->ToJSON(), ","); // Field bayarpokok
		$sFilterList = ew_Concat($sFilterList, $this->bayarpokokauto->AdvancedSearch->ToJSON(), ","); // Field bayarpokokauto
		$sFilterList = ew_Concat($sFilterList, $this->bayarbunga->AdvancedSearch->ToJSON(), ","); // Field bayarbunga
		$sFilterList = ew_Concat($sFilterList, $this->bayarbungaauto->AdvancedSearch->ToJSON(), ","); // Field bayarbungaauto
		$sFilterList = ew_Concat($sFilterList, $this->bayardenda->AdvancedSearch->ToJSON(), ","); // Field bayardenda
		$sFilterList = ew_Concat($sFilterList, $this->bayardendaauto->AdvancedSearch->ToJSON(), ","); // Field bayardendaauto
		$sFilterList = ew_Concat($sFilterList, $this->bayartitipan->AdvancedSearch->ToJSON(), ","); // Field bayartitipan
		$sFilterList = ew_Concat($sFilterList, $this->bayartitipanauto->AdvancedSearch->ToJSON(), ","); // Field bayartitipanauto
		$sFilterList = ew_Concat($sFilterList, $this->totalbayar->AdvancedSearch->ToJSON(), ","); // Field totalbayar
		$sFilterList = ew_Concat($sFilterList, $this->totalbayarauto->AdvancedSearch->ToJSON(), ","); // Field totalbayarauto
		$sFilterList = ew_Concat($sFilterList, $this->pelunasan->AdvancedSearch->ToJSON(), ","); // Field pelunasan
		$sFilterList = ew_Concat($sFilterList, $this->pelunasanauto->AdvancedSearch->ToJSON(), ","); // Field pelunasanauto
		$sFilterList = ew_Concat($sFilterList, $this->finalty->AdvancedSearch->ToJSON(), ","); // Field finalty
		$sFilterList = ew_Concat($sFilterList, $this->finaltyauto->AdvancedSearch->ToJSON(), ","); // Field finaltyauto
		$sFilterList = ew_Concat($sFilterList, $this->status->AdvancedSearch->ToJSON(), ","); // Field status
		$sFilterList = ew_Concat($sFilterList, $this->keterangan->AdvancedSearch->ToJSON(), ","); // Field keterangan
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "ftpinjamandetaillistsrch", $filters);

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

		// Field angsuran
		$this->angsuran->AdvancedSearch->SearchValue = @$filter["x_angsuran"];
		$this->angsuran->AdvancedSearch->SearchOperator = @$filter["z_angsuran"];
		$this->angsuran->AdvancedSearch->SearchCondition = @$filter["v_angsuran"];
		$this->angsuran->AdvancedSearch->SearchValue2 = @$filter["y_angsuran"];
		$this->angsuran->AdvancedSearch->SearchOperator2 = @$filter["w_angsuran"];
		$this->angsuran->AdvancedSearch->Save();

		// Field masaangsuran
		$this->masaangsuran->AdvancedSearch->SearchValue = @$filter["x_masaangsuran"];
		$this->masaangsuran->AdvancedSearch->SearchOperator = @$filter["z_masaangsuran"];
		$this->masaangsuran->AdvancedSearch->SearchCondition = @$filter["v_masaangsuran"];
		$this->masaangsuran->AdvancedSearch->SearchValue2 = @$filter["y_masaangsuran"];
		$this->masaangsuran->AdvancedSearch->SearchOperator2 = @$filter["w_masaangsuran"];
		$this->masaangsuran->AdvancedSearch->Save();

		// Field dispensasidenda
		$this->dispensasidenda->AdvancedSearch->SearchValue = @$filter["x_dispensasidenda"];
		$this->dispensasidenda->AdvancedSearch->SearchOperator = @$filter["z_dispensasidenda"];
		$this->dispensasidenda->AdvancedSearch->SearchCondition = @$filter["v_dispensasidenda"];
		$this->dispensasidenda->AdvancedSearch->SearchValue2 = @$filter["y_dispensasidenda"];
		$this->dispensasidenda->AdvancedSearch->SearchOperator2 = @$filter["w_dispensasidenda"];
		$this->dispensasidenda->AdvancedSearch->Save();

		// Field plafond
		$this->plafond->AdvancedSearch->SearchValue = @$filter["x_plafond"];
		$this->plafond->AdvancedSearch->SearchOperator = @$filter["z_plafond"];
		$this->plafond->AdvancedSearch->SearchCondition = @$filter["v_plafond"];
		$this->plafond->AdvancedSearch->SearchValue2 = @$filter["y_plafond"];
		$this->plafond->AdvancedSearch->SearchOperator2 = @$filter["w_plafond"];
		$this->plafond->AdvancedSearch->Save();

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

		// Field denda
		$this->denda->AdvancedSearch->SearchValue = @$filter["x_denda"];
		$this->denda->AdvancedSearch->SearchOperator = @$filter["z_denda"];
		$this->denda->AdvancedSearch->SearchCondition = @$filter["v_denda"];
		$this->denda->AdvancedSearch->SearchValue2 = @$filter["y_denda"];
		$this->denda->AdvancedSearch->SearchOperator2 = @$filter["w_denda"];
		$this->denda->AdvancedSearch->Save();

		// Field dendapersen
		$this->dendapersen->AdvancedSearch->SearchValue = @$filter["x_dendapersen"];
		$this->dendapersen->AdvancedSearch->SearchOperator = @$filter["z_dendapersen"];
		$this->dendapersen->AdvancedSearch->SearchCondition = @$filter["v_dendapersen"];
		$this->dendapersen->AdvancedSearch->SearchValue2 = @$filter["y_dendapersen"];
		$this->dendapersen->AdvancedSearch->SearchOperator2 = @$filter["w_dendapersen"];
		$this->dendapersen->AdvancedSearch->Save();

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

		// Field tanggalbayar
		$this->tanggalbayar->AdvancedSearch->SearchValue = @$filter["x_tanggalbayar"];
		$this->tanggalbayar->AdvancedSearch->SearchOperator = @$filter["z_tanggalbayar"];
		$this->tanggalbayar->AdvancedSearch->SearchCondition = @$filter["v_tanggalbayar"];
		$this->tanggalbayar->AdvancedSearch->SearchValue2 = @$filter["y_tanggalbayar"];
		$this->tanggalbayar->AdvancedSearch->SearchOperator2 = @$filter["w_tanggalbayar"];
		$this->tanggalbayar->AdvancedSearch->Save();

		// Field terlambat
		$this->terlambat->AdvancedSearch->SearchValue = @$filter["x_terlambat"];
		$this->terlambat->AdvancedSearch->SearchOperator = @$filter["z_terlambat"];
		$this->terlambat->AdvancedSearch->SearchCondition = @$filter["v_terlambat"];
		$this->terlambat->AdvancedSearch->SearchValue2 = @$filter["y_terlambat"];
		$this->terlambat->AdvancedSearch->SearchOperator2 = @$filter["w_terlambat"];
		$this->terlambat->AdvancedSearch->Save();

		// Field bayarpokok
		$this->bayarpokok->AdvancedSearch->SearchValue = @$filter["x_bayarpokok"];
		$this->bayarpokok->AdvancedSearch->SearchOperator = @$filter["z_bayarpokok"];
		$this->bayarpokok->AdvancedSearch->SearchCondition = @$filter["v_bayarpokok"];
		$this->bayarpokok->AdvancedSearch->SearchValue2 = @$filter["y_bayarpokok"];
		$this->bayarpokok->AdvancedSearch->SearchOperator2 = @$filter["w_bayarpokok"];
		$this->bayarpokok->AdvancedSearch->Save();

		// Field bayarpokokauto
		$this->bayarpokokauto->AdvancedSearch->SearchValue = @$filter["x_bayarpokokauto"];
		$this->bayarpokokauto->AdvancedSearch->SearchOperator = @$filter["z_bayarpokokauto"];
		$this->bayarpokokauto->AdvancedSearch->SearchCondition = @$filter["v_bayarpokokauto"];
		$this->bayarpokokauto->AdvancedSearch->SearchValue2 = @$filter["y_bayarpokokauto"];
		$this->bayarpokokauto->AdvancedSearch->SearchOperator2 = @$filter["w_bayarpokokauto"];
		$this->bayarpokokauto->AdvancedSearch->Save();

		// Field bayarbunga
		$this->bayarbunga->AdvancedSearch->SearchValue = @$filter["x_bayarbunga"];
		$this->bayarbunga->AdvancedSearch->SearchOperator = @$filter["z_bayarbunga"];
		$this->bayarbunga->AdvancedSearch->SearchCondition = @$filter["v_bayarbunga"];
		$this->bayarbunga->AdvancedSearch->SearchValue2 = @$filter["y_bayarbunga"];
		$this->bayarbunga->AdvancedSearch->SearchOperator2 = @$filter["w_bayarbunga"];
		$this->bayarbunga->AdvancedSearch->Save();

		// Field bayarbungaauto
		$this->bayarbungaauto->AdvancedSearch->SearchValue = @$filter["x_bayarbungaauto"];
		$this->bayarbungaauto->AdvancedSearch->SearchOperator = @$filter["z_bayarbungaauto"];
		$this->bayarbungaauto->AdvancedSearch->SearchCondition = @$filter["v_bayarbungaauto"];
		$this->bayarbungaauto->AdvancedSearch->SearchValue2 = @$filter["y_bayarbungaauto"];
		$this->bayarbungaauto->AdvancedSearch->SearchOperator2 = @$filter["w_bayarbungaauto"];
		$this->bayarbungaauto->AdvancedSearch->Save();

		// Field bayardenda
		$this->bayardenda->AdvancedSearch->SearchValue = @$filter["x_bayardenda"];
		$this->bayardenda->AdvancedSearch->SearchOperator = @$filter["z_bayardenda"];
		$this->bayardenda->AdvancedSearch->SearchCondition = @$filter["v_bayardenda"];
		$this->bayardenda->AdvancedSearch->SearchValue2 = @$filter["y_bayardenda"];
		$this->bayardenda->AdvancedSearch->SearchOperator2 = @$filter["w_bayardenda"];
		$this->bayardenda->AdvancedSearch->Save();

		// Field bayardendaauto
		$this->bayardendaauto->AdvancedSearch->SearchValue = @$filter["x_bayardendaauto"];
		$this->bayardendaauto->AdvancedSearch->SearchOperator = @$filter["z_bayardendaauto"];
		$this->bayardendaauto->AdvancedSearch->SearchCondition = @$filter["v_bayardendaauto"];
		$this->bayardendaauto->AdvancedSearch->SearchValue2 = @$filter["y_bayardendaauto"];
		$this->bayardendaauto->AdvancedSearch->SearchOperator2 = @$filter["w_bayardendaauto"];
		$this->bayardendaauto->AdvancedSearch->Save();

		// Field bayartitipan
		$this->bayartitipan->AdvancedSearch->SearchValue = @$filter["x_bayartitipan"];
		$this->bayartitipan->AdvancedSearch->SearchOperator = @$filter["z_bayartitipan"];
		$this->bayartitipan->AdvancedSearch->SearchCondition = @$filter["v_bayartitipan"];
		$this->bayartitipan->AdvancedSearch->SearchValue2 = @$filter["y_bayartitipan"];
		$this->bayartitipan->AdvancedSearch->SearchOperator2 = @$filter["w_bayartitipan"];
		$this->bayartitipan->AdvancedSearch->Save();

		// Field bayartitipanauto
		$this->bayartitipanauto->AdvancedSearch->SearchValue = @$filter["x_bayartitipanauto"];
		$this->bayartitipanauto->AdvancedSearch->SearchOperator = @$filter["z_bayartitipanauto"];
		$this->bayartitipanauto->AdvancedSearch->SearchCondition = @$filter["v_bayartitipanauto"];
		$this->bayartitipanauto->AdvancedSearch->SearchValue2 = @$filter["y_bayartitipanauto"];
		$this->bayartitipanauto->AdvancedSearch->SearchOperator2 = @$filter["w_bayartitipanauto"];
		$this->bayartitipanauto->AdvancedSearch->Save();

		// Field totalbayar
		$this->totalbayar->AdvancedSearch->SearchValue = @$filter["x_totalbayar"];
		$this->totalbayar->AdvancedSearch->SearchOperator = @$filter["z_totalbayar"];
		$this->totalbayar->AdvancedSearch->SearchCondition = @$filter["v_totalbayar"];
		$this->totalbayar->AdvancedSearch->SearchValue2 = @$filter["y_totalbayar"];
		$this->totalbayar->AdvancedSearch->SearchOperator2 = @$filter["w_totalbayar"];
		$this->totalbayar->AdvancedSearch->Save();

		// Field totalbayarauto
		$this->totalbayarauto->AdvancedSearch->SearchValue = @$filter["x_totalbayarauto"];
		$this->totalbayarauto->AdvancedSearch->SearchOperator = @$filter["z_totalbayarauto"];
		$this->totalbayarauto->AdvancedSearch->SearchCondition = @$filter["v_totalbayarauto"];
		$this->totalbayarauto->AdvancedSearch->SearchValue2 = @$filter["y_totalbayarauto"];
		$this->totalbayarauto->AdvancedSearch->SearchOperator2 = @$filter["w_totalbayarauto"];
		$this->totalbayarauto->AdvancedSearch->Save();

		// Field pelunasan
		$this->pelunasan->AdvancedSearch->SearchValue = @$filter["x_pelunasan"];
		$this->pelunasan->AdvancedSearch->SearchOperator = @$filter["z_pelunasan"];
		$this->pelunasan->AdvancedSearch->SearchCondition = @$filter["v_pelunasan"];
		$this->pelunasan->AdvancedSearch->SearchValue2 = @$filter["y_pelunasan"];
		$this->pelunasan->AdvancedSearch->SearchOperator2 = @$filter["w_pelunasan"];
		$this->pelunasan->AdvancedSearch->Save();

		// Field pelunasanauto
		$this->pelunasanauto->AdvancedSearch->SearchValue = @$filter["x_pelunasanauto"];
		$this->pelunasanauto->AdvancedSearch->SearchOperator = @$filter["z_pelunasanauto"];
		$this->pelunasanauto->AdvancedSearch->SearchCondition = @$filter["v_pelunasanauto"];
		$this->pelunasanauto->AdvancedSearch->SearchValue2 = @$filter["y_pelunasanauto"];
		$this->pelunasanauto->AdvancedSearch->SearchOperator2 = @$filter["w_pelunasanauto"];
		$this->pelunasanauto->AdvancedSearch->Save();

		// Field finalty
		$this->finalty->AdvancedSearch->SearchValue = @$filter["x_finalty"];
		$this->finalty->AdvancedSearch->SearchOperator = @$filter["z_finalty"];
		$this->finalty->AdvancedSearch->SearchCondition = @$filter["v_finalty"];
		$this->finalty->AdvancedSearch->SearchValue2 = @$filter["y_finalty"];
		$this->finalty->AdvancedSearch->SearchOperator2 = @$filter["w_finalty"];
		$this->finalty->AdvancedSearch->Save();

		// Field finaltyauto
		$this->finaltyauto->AdvancedSearch->SearchValue = @$filter["x_finaltyauto"];
		$this->finaltyauto->AdvancedSearch->SearchOperator = @$filter["z_finaltyauto"];
		$this->finaltyauto->AdvancedSearch->SearchCondition = @$filter["v_finaltyauto"];
		$this->finaltyauto->AdvancedSearch->SearchValue2 = @$filter["y_finaltyauto"];
		$this->finaltyauto->AdvancedSearch->SearchOperator2 = @$filter["w_finaltyauto"];
		$this->finaltyauto->AdvancedSearch->Save();

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
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->periode, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->id, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->masaangsuran, $arKeywords, $type);
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
			$this->UpdateSort($this->angsuran); // angsuran
			$this->UpdateSort($this->masaangsuran); // masaangsuran
			$this->UpdateSort($this->dispensasidenda); // dispensasidenda
			$this->UpdateSort($this->plafond); // plafond
			$this->UpdateSort($this->angsuranpokok); // angsuranpokok
			$this->UpdateSort($this->angsuranpokokauto); // angsuranpokokauto
			$this->UpdateSort($this->angsuranbunga); // angsuranbunga
			$this->UpdateSort($this->angsuranbungaauto); // angsuranbungaauto
			$this->UpdateSort($this->denda); // denda
			$this->UpdateSort($this->dendapersen); // dendapersen
			$this->UpdateSort($this->totalangsuran); // totalangsuran
			$this->UpdateSort($this->totalangsuranauto); // totalangsuranauto
			$this->UpdateSort($this->sisaangsuran); // sisaangsuran
			$this->UpdateSort($this->sisaangsuranauto); // sisaangsuranauto
			$this->UpdateSort($this->tanggalbayar); // tanggalbayar
			$this->UpdateSort($this->terlambat); // terlambat
			$this->UpdateSort($this->bayarpokok); // bayarpokok
			$this->UpdateSort($this->bayarpokokauto); // bayarpokokauto
			$this->UpdateSort($this->bayarbunga); // bayarbunga
			$this->UpdateSort($this->bayarbungaauto); // bayarbungaauto
			$this->UpdateSort($this->bayardenda); // bayardenda
			$this->UpdateSort($this->bayardendaauto); // bayardendaauto
			$this->UpdateSort($this->bayartitipan); // bayartitipan
			$this->UpdateSort($this->bayartitipanauto); // bayartitipanauto
			$this->UpdateSort($this->totalbayar); // totalbayar
			$this->UpdateSort($this->totalbayarauto); // totalbayarauto
			$this->UpdateSort($this->pelunasan); // pelunasan
			$this->UpdateSort($this->pelunasanauto); // pelunasanauto
			$this->UpdateSort($this->finalty); // finalty
			$this->UpdateSort($this->finaltyauto); // finaltyauto
			$this->UpdateSort($this->status); // status
			$this->UpdateSort($this->keterangan); // keterangan
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
				$this->angsuran->setSort("");
				$this->masaangsuran->setSort("");
				$this->dispensasidenda->setSort("");
				$this->plafond->setSort("");
				$this->angsuranpokok->setSort("");
				$this->angsuranpokokauto->setSort("");
				$this->angsuranbunga->setSort("");
				$this->angsuranbungaauto->setSort("");
				$this->denda->setSort("");
				$this->dendapersen->setSort("");
				$this->totalangsuran->setSort("");
				$this->totalangsuranauto->setSort("");
				$this->sisaangsuran->setSort("");
				$this->sisaangsuranauto->setSort("");
				$this->tanggalbayar->setSort("");
				$this->terlambat->setSort("");
				$this->bayarpokok->setSort("");
				$this->bayarpokokauto->setSort("");
				$this->bayarbunga->setSort("");
				$this->bayarbungaauto->setSort("");
				$this->bayardenda->setSort("");
				$this->bayardendaauto->setSort("");
				$this->bayartitipan->setSort("");
				$this->bayartitipanauto->setSort("");
				$this->totalbayar->setSort("");
				$this->totalbayarauto->setSort("");
				$this->pelunasan->setSort("");
				$this->pelunasanauto->setSort("");
				$this->finalty->setSort("");
				$this->finaltyauto->setSort("");
				$this->status->setSort("");
				$this->keterangan->setSort("");
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->angsuran->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"ftpinjamandetaillistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"ftpinjamandetaillistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.ftpinjamandetaillist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"ftpinjamandetaillistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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
		$this->angsuran->setDbValue($rs->fields('angsuran'));
		$this->masaangsuran->setDbValue($rs->fields('masaangsuran'));
		$this->dispensasidenda->setDbValue($rs->fields('dispensasidenda'));
		$this->plafond->setDbValue($rs->fields('plafond'));
		$this->angsuranpokok->setDbValue($rs->fields('angsuranpokok'));
		$this->angsuranpokokauto->setDbValue($rs->fields('angsuranpokokauto'));
		$this->angsuranbunga->setDbValue($rs->fields('angsuranbunga'));
		$this->angsuranbungaauto->setDbValue($rs->fields('angsuranbungaauto'));
		$this->denda->setDbValue($rs->fields('denda'));
		$this->dendapersen->setDbValue($rs->fields('dendapersen'));
		$this->totalangsuran->setDbValue($rs->fields('totalangsuran'));
		$this->totalangsuranauto->setDbValue($rs->fields('totalangsuranauto'));
		$this->sisaangsuran->setDbValue($rs->fields('sisaangsuran'));
		$this->sisaangsuranauto->setDbValue($rs->fields('sisaangsuranauto'));
		$this->tanggalbayar->setDbValue($rs->fields('tanggalbayar'));
		$this->terlambat->setDbValue($rs->fields('terlambat'));
		$this->bayarpokok->setDbValue($rs->fields('bayarpokok'));
		$this->bayarpokokauto->setDbValue($rs->fields('bayarpokokauto'));
		$this->bayarbunga->setDbValue($rs->fields('bayarbunga'));
		$this->bayarbungaauto->setDbValue($rs->fields('bayarbungaauto'));
		$this->bayardenda->setDbValue($rs->fields('bayardenda'));
		$this->bayardendaauto->setDbValue($rs->fields('bayardendaauto'));
		$this->bayartitipan->setDbValue($rs->fields('bayartitipan'));
		$this->bayartitipanauto->setDbValue($rs->fields('bayartitipanauto'));
		$this->totalbayar->setDbValue($rs->fields('totalbayar'));
		$this->totalbayarauto->setDbValue($rs->fields('totalbayarauto'));
		$this->pelunasan->setDbValue($rs->fields('pelunasan'));
		$this->pelunasanauto->setDbValue($rs->fields('pelunasanauto'));
		$this->finalty->setDbValue($rs->fields('finalty'));
		$this->finaltyauto->setDbValue($rs->fields('finaltyauto'));
		$this->status->setDbValue($rs->fields('status'));
		$this->keterangan->setDbValue($rs->fields('keterangan'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->tanggal->DbValue = $row['tanggal'];
		$this->periode->DbValue = $row['periode'];
		$this->id->DbValue = $row['id'];
		$this->angsuran->DbValue = $row['angsuran'];
		$this->masaangsuran->DbValue = $row['masaangsuran'];
		$this->dispensasidenda->DbValue = $row['dispensasidenda'];
		$this->plafond->DbValue = $row['plafond'];
		$this->angsuranpokok->DbValue = $row['angsuranpokok'];
		$this->angsuranpokokauto->DbValue = $row['angsuranpokokauto'];
		$this->angsuranbunga->DbValue = $row['angsuranbunga'];
		$this->angsuranbungaauto->DbValue = $row['angsuranbungaauto'];
		$this->denda->DbValue = $row['denda'];
		$this->dendapersen->DbValue = $row['dendapersen'];
		$this->totalangsuran->DbValue = $row['totalangsuran'];
		$this->totalangsuranauto->DbValue = $row['totalangsuranauto'];
		$this->sisaangsuran->DbValue = $row['sisaangsuran'];
		$this->sisaangsuranauto->DbValue = $row['sisaangsuranauto'];
		$this->tanggalbayar->DbValue = $row['tanggalbayar'];
		$this->terlambat->DbValue = $row['terlambat'];
		$this->bayarpokok->DbValue = $row['bayarpokok'];
		$this->bayarpokokauto->DbValue = $row['bayarpokokauto'];
		$this->bayarbunga->DbValue = $row['bayarbunga'];
		$this->bayarbungaauto->DbValue = $row['bayarbungaauto'];
		$this->bayardenda->DbValue = $row['bayardenda'];
		$this->bayardendaauto->DbValue = $row['bayardendaauto'];
		$this->bayartitipan->DbValue = $row['bayartitipan'];
		$this->bayartitipanauto->DbValue = $row['bayartitipanauto'];
		$this->totalbayar->DbValue = $row['totalbayar'];
		$this->totalbayarauto->DbValue = $row['totalbayarauto'];
		$this->pelunasan->DbValue = $row['pelunasan'];
		$this->pelunasanauto->DbValue = $row['pelunasanauto'];
		$this->finalty->DbValue = $row['finalty'];
		$this->finaltyauto->DbValue = $row['finaltyauto'];
		$this->status->DbValue = $row['status'];
		$this->keterangan->DbValue = $row['keterangan'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
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
		if ($this->plafond->FormValue == $this->plafond->CurrentValue && is_numeric(ew_StrToFloat($this->plafond->CurrentValue)))
			$this->plafond->CurrentValue = ew_StrToFloat($this->plafond->CurrentValue);

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
		if ($this->denda->FormValue == $this->denda->CurrentValue && is_numeric(ew_StrToFloat($this->denda->CurrentValue)))
			$this->denda->CurrentValue = ew_StrToFloat($this->denda->CurrentValue);

		// Convert decimal values if posted back
		if ($this->dendapersen->FormValue == $this->dendapersen->CurrentValue && is_numeric(ew_StrToFloat($this->dendapersen->CurrentValue)))
			$this->dendapersen->CurrentValue = ew_StrToFloat($this->dendapersen->CurrentValue);

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

		// Convert decimal values if posted back
		if ($this->bayarpokok->FormValue == $this->bayarpokok->CurrentValue && is_numeric(ew_StrToFloat($this->bayarpokok->CurrentValue)))
			$this->bayarpokok->CurrentValue = ew_StrToFloat($this->bayarpokok->CurrentValue);

		// Convert decimal values if posted back
		if ($this->bayarpokokauto->FormValue == $this->bayarpokokauto->CurrentValue && is_numeric(ew_StrToFloat($this->bayarpokokauto->CurrentValue)))
			$this->bayarpokokauto->CurrentValue = ew_StrToFloat($this->bayarpokokauto->CurrentValue);

		// Convert decimal values if posted back
		if ($this->bayarbunga->FormValue == $this->bayarbunga->CurrentValue && is_numeric(ew_StrToFloat($this->bayarbunga->CurrentValue)))
			$this->bayarbunga->CurrentValue = ew_StrToFloat($this->bayarbunga->CurrentValue);

		// Convert decimal values if posted back
		if ($this->bayarbungaauto->FormValue == $this->bayarbungaauto->CurrentValue && is_numeric(ew_StrToFloat($this->bayarbungaauto->CurrentValue)))
			$this->bayarbungaauto->CurrentValue = ew_StrToFloat($this->bayarbungaauto->CurrentValue);

		// Convert decimal values if posted back
		if ($this->bayardenda->FormValue == $this->bayardenda->CurrentValue && is_numeric(ew_StrToFloat($this->bayardenda->CurrentValue)))
			$this->bayardenda->CurrentValue = ew_StrToFloat($this->bayardenda->CurrentValue);

		// Convert decimal values if posted back
		if ($this->bayardendaauto->FormValue == $this->bayardendaauto->CurrentValue && is_numeric(ew_StrToFloat($this->bayardendaauto->CurrentValue)))
			$this->bayardendaauto->CurrentValue = ew_StrToFloat($this->bayardendaauto->CurrentValue);

		// Convert decimal values if posted back
		if ($this->bayartitipan->FormValue == $this->bayartitipan->CurrentValue && is_numeric(ew_StrToFloat($this->bayartitipan->CurrentValue)))
			$this->bayartitipan->CurrentValue = ew_StrToFloat($this->bayartitipan->CurrentValue);

		// Convert decimal values if posted back
		if ($this->bayartitipanauto->FormValue == $this->bayartitipanauto->CurrentValue && is_numeric(ew_StrToFloat($this->bayartitipanauto->CurrentValue)))
			$this->bayartitipanauto->CurrentValue = ew_StrToFloat($this->bayartitipanauto->CurrentValue);

		// Convert decimal values if posted back
		if ($this->totalbayar->FormValue == $this->totalbayar->CurrentValue && is_numeric(ew_StrToFloat($this->totalbayar->CurrentValue)))
			$this->totalbayar->CurrentValue = ew_StrToFloat($this->totalbayar->CurrentValue);

		// Convert decimal values if posted back
		if ($this->totalbayarauto->FormValue == $this->totalbayarauto->CurrentValue && is_numeric(ew_StrToFloat($this->totalbayarauto->CurrentValue)))
			$this->totalbayarauto->CurrentValue = ew_StrToFloat($this->totalbayarauto->CurrentValue);

		// Convert decimal values if posted back
		if ($this->pelunasan->FormValue == $this->pelunasan->CurrentValue && is_numeric(ew_StrToFloat($this->pelunasan->CurrentValue)))
			$this->pelunasan->CurrentValue = ew_StrToFloat($this->pelunasan->CurrentValue);

		// Convert decimal values if posted back
		if ($this->pelunasanauto->FormValue == $this->pelunasanauto->CurrentValue && is_numeric(ew_StrToFloat($this->pelunasanauto->CurrentValue)))
			$this->pelunasanauto->CurrentValue = ew_StrToFloat($this->pelunasanauto->CurrentValue);

		// Convert decimal values if posted back
		if ($this->finalty->FormValue == $this->finalty->CurrentValue && is_numeric(ew_StrToFloat($this->finalty->CurrentValue)))
			$this->finalty->CurrentValue = ew_StrToFloat($this->finalty->CurrentValue);

		// Convert decimal values if posted back
		if ($this->finaltyauto->FormValue == $this->finaltyauto->CurrentValue && is_numeric(ew_StrToFloat($this->finaltyauto->CurrentValue)))
			$this->finaltyauto->CurrentValue = ew_StrToFloat($this->finaltyauto->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// tanggal
		// periode
		// id
		// angsuran
		// masaangsuran
		// dispensasidenda
		// plafond
		// angsuranpokok
		// angsuranpokokauto
		// angsuranbunga
		// angsuranbungaauto
		// denda
		// dendapersen
		// totalangsuran
		// totalangsuranauto
		// sisaangsuran
		// sisaangsuranauto
		// tanggalbayar
		// terlambat
		// bayarpokok
		// bayarpokokauto
		// bayarbunga
		// bayarbungaauto
		// bayardenda
		// bayardendaauto
		// bayartitipan
		// bayartitipanauto
		// totalbayar
		// totalbayarauto
		// pelunasan
		// pelunasanauto
		// finalty
		// finaltyauto
		// status
		// keterangan

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

		// angsuran
		$this->angsuran->ViewValue = $this->angsuran->CurrentValue;
		$this->angsuran->ViewCustomAttributes = "";

		// masaangsuran
		$this->masaangsuran->ViewValue = $this->masaangsuran->CurrentValue;
		$this->masaangsuran->ViewCustomAttributes = "";

		// dispensasidenda
		$this->dispensasidenda->ViewValue = $this->dispensasidenda->CurrentValue;
		$this->dispensasidenda->ViewCustomAttributes = "";

		// plafond
		$this->plafond->ViewValue = $this->plafond->CurrentValue;
		$this->plafond->ViewCustomAttributes = "";

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

		// denda
		$this->denda->ViewValue = $this->denda->CurrentValue;
		$this->denda->ViewCustomAttributes = "";

		// dendapersen
		$this->dendapersen->ViewValue = $this->dendapersen->CurrentValue;
		$this->dendapersen->ViewCustomAttributes = "";

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

		// tanggalbayar
		$this->tanggalbayar->ViewValue = $this->tanggalbayar->CurrentValue;
		$this->tanggalbayar->ViewValue = ew_FormatDateTime($this->tanggalbayar->ViewValue, 0);
		$this->tanggalbayar->ViewCustomAttributes = "";

		// terlambat
		$this->terlambat->ViewValue = $this->terlambat->CurrentValue;
		$this->terlambat->ViewCustomAttributes = "";

		// bayarpokok
		$this->bayarpokok->ViewValue = $this->bayarpokok->CurrentValue;
		$this->bayarpokok->ViewCustomAttributes = "";

		// bayarpokokauto
		$this->bayarpokokauto->ViewValue = $this->bayarpokokauto->CurrentValue;
		$this->bayarpokokauto->ViewCustomAttributes = "";

		// bayarbunga
		$this->bayarbunga->ViewValue = $this->bayarbunga->CurrentValue;
		$this->bayarbunga->ViewCustomAttributes = "";

		// bayarbungaauto
		$this->bayarbungaauto->ViewValue = $this->bayarbungaauto->CurrentValue;
		$this->bayarbungaauto->ViewCustomAttributes = "";

		// bayardenda
		$this->bayardenda->ViewValue = $this->bayardenda->CurrentValue;
		$this->bayardenda->ViewCustomAttributes = "";

		// bayardendaauto
		$this->bayardendaauto->ViewValue = $this->bayardendaauto->CurrentValue;
		$this->bayardendaauto->ViewCustomAttributes = "";

		// bayartitipan
		$this->bayartitipan->ViewValue = $this->bayartitipan->CurrentValue;
		$this->bayartitipan->ViewCustomAttributes = "";

		// bayartitipanauto
		$this->bayartitipanauto->ViewValue = $this->bayartitipanauto->CurrentValue;
		$this->bayartitipanauto->ViewCustomAttributes = "";

		// totalbayar
		$this->totalbayar->ViewValue = $this->totalbayar->CurrentValue;
		$this->totalbayar->ViewCustomAttributes = "";

		// totalbayarauto
		$this->totalbayarauto->ViewValue = $this->totalbayarauto->CurrentValue;
		$this->totalbayarauto->ViewCustomAttributes = "";

		// pelunasan
		$this->pelunasan->ViewValue = $this->pelunasan->CurrentValue;
		$this->pelunasan->ViewCustomAttributes = "";

		// pelunasanauto
		$this->pelunasanauto->ViewValue = $this->pelunasanauto->CurrentValue;
		$this->pelunasanauto->ViewCustomAttributes = "";

		// finalty
		$this->finalty->ViewValue = $this->finalty->CurrentValue;
		$this->finalty->ViewCustomAttributes = "";

		// finaltyauto
		$this->finaltyauto->ViewValue = $this->finaltyauto->CurrentValue;
		$this->finaltyauto->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		$this->status->ViewCustomAttributes = "";

		// keterangan
		$this->keterangan->ViewValue = $this->keterangan->CurrentValue;
		$this->keterangan->ViewCustomAttributes = "";

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

			// angsuran
			$this->angsuran->LinkCustomAttributes = "";
			$this->angsuran->HrefValue = "";
			$this->angsuran->TooltipValue = "";

			// masaangsuran
			$this->masaangsuran->LinkCustomAttributes = "";
			$this->masaangsuran->HrefValue = "";
			$this->masaangsuran->TooltipValue = "";

			// dispensasidenda
			$this->dispensasidenda->LinkCustomAttributes = "";
			$this->dispensasidenda->HrefValue = "";
			$this->dispensasidenda->TooltipValue = "";

			// plafond
			$this->plafond->LinkCustomAttributes = "";
			$this->plafond->HrefValue = "";
			$this->plafond->TooltipValue = "";

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

			// denda
			$this->denda->LinkCustomAttributes = "";
			$this->denda->HrefValue = "";
			$this->denda->TooltipValue = "";

			// dendapersen
			$this->dendapersen->LinkCustomAttributes = "";
			$this->dendapersen->HrefValue = "";
			$this->dendapersen->TooltipValue = "";

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

			// tanggalbayar
			$this->tanggalbayar->LinkCustomAttributes = "";
			$this->tanggalbayar->HrefValue = "";
			$this->tanggalbayar->TooltipValue = "";

			// terlambat
			$this->terlambat->LinkCustomAttributes = "";
			$this->terlambat->HrefValue = "";
			$this->terlambat->TooltipValue = "";

			// bayarpokok
			$this->bayarpokok->LinkCustomAttributes = "";
			$this->bayarpokok->HrefValue = "";
			$this->bayarpokok->TooltipValue = "";

			// bayarpokokauto
			$this->bayarpokokauto->LinkCustomAttributes = "";
			$this->bayarpokokauto->HrefValue = "";
			$this->bayarpokokauto->TooltipValue = "";

			// bayarbunga
			$this->bayarbunga->LinkCustomAttributes = "";
			$this->bayarbunga->HrefValue = "";
			$this->bayarbunga->TooltipValue = "";

			// bayarbungaauto
			$this->bayarbungaauto->LinkCustomAttributes = "";
			$this->bayarbungaauto->HrefValue = "";
			$this->bayarbungaauto->TooltipValue = "";

			// bayardenda
			$this->bayardenda->LinkCustomAttributes = "";
			$this->bayardenda->HrefValue = "";
			$this->bayardenda->TooltipValue = "";

			// bayardendaauto
			$this->bayardendaauto->LinkCustomAttributes = "";
			$this->bayardendaauto->HrefValue = "";
			$this->bayardendaauto->TooltipValue = "";

			// bayartitipan
			$this->bayartitipan->LinkCustomAttributes = "";
			$this->bayartitipan->HrefValue = "";
			$this->bayartitipan->TooltipValue = "";

			// bayartitipanauto
			$this->bayartitipanauto->LinkCustomAttributes = "";
			$this->bayartitipanauto->HrefValue = "";
			$this->bayartitipanauto->TooltipValue = "";

			// totalbayar
			$this->totalbayar->LinkCustomAttributes = "";
			$this->totalbayar->HrefValue = "";
			$this->totalbayar->TooltipValue = "";

			// totalbayarauto
			$this->totalbayarauto->LinkCustomAttributes = "";
			$this->totalbayarauto->HrefValue = "";
			$this->totalbayarauto->TooltipValue = "";

			// pelunasan
			$this->pelunasan->LinkCustomAttributes = "";
			$this->pelunasan->HrefValue = "";
			$this->pelunasan->TooltipValue = "";

			// pelunasanauto
			$this->pelunasanauto->LinkCustomAttributes = "";
			$this->pelunasanauto->HrefValue = "";
			$this->pelunasanauto->TooltipValue = "";

			// finalty
			$this->finalty->LinkCustomAttributes = "";
			$this->finalty->HrefValue = "";
			$this->finalty->TooltipValue = "";

			// finaltyauto
			$this->finaltyauto->LinkCustomAttributes = "";
			$this->finaltyauto->HrefValue = "";
			$this->finaltyauto->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";
			$this->keterangan->TooltipValue = "";
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
if (!isset($tpinjamandetail_list)) $tpinjamandetail_list = new ctpinjamandetail_list();

// Page init
$tpinjamandetail_list->Page_Init();

// Page main
$tpinjamandetail_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tpinjamandetail_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = ftpinjamandetaillist = new ew_Form("ftpinjamandetaillist", "list");
ftpinjamandetaillist.FormKeyCountName = '<?php echo $tpinjamandetail_list->FormKeyCountName ?>';

// Form_CustomValidate event
ftpinjamandetaillist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftpinjamandetaillist.ValidateRequired = true;
<?php } else { ?>
ftpinjamandetaillist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = ftpinjamandetaillistsrch = new ew_Form("ftpinjamandetaillistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($tpinjamandetail_list->TotalRecs > 0 && $tpinjamandetail_list->ExportOptions->Visible()) { ?>
<?php $tpinjamandetail_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($tpinjamandetail_list->SearchOptions->Visible()) { ?>
<?php $tpinjamandetail_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($tpinjamandetail_list->FilterOptions->Visible()) { ?>
<?php $tpinjamandetail_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $tpinjamandetail_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($tpinjamandetail_list->TotalRecs <= 0)
			$tpinjamandetail_list->TotalRecs = $tpinjamandetail->SelectRecordCount();
	} else {
		if (!$tpinjamandetail_list->Recordset && ($tpinjamandetail_list->Recordset = $tpinjamandetail_list->LoadRecordset()))
			$tpinjamandetail_list->TotalRecs = $tpinjamandetail_list->Recordset->RecordCount();
	}
	$tpinjamandetail_list->StartRec = 1;
	if ($tpinjamandetail_list->DisplayRecs <= 0 || ($tpinjamandetail->Export <> "" && $tpinjamandetail->ExportAll)) // Display all records
		$tpinjamandetail_list->DisplayRecs = $tpinjamandetail_list->TotalRecs;
	if (!($tpinjamandetail->Export <> "" && $tpinjamandetail->ExportAll))
		$tpinjamandetail_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$tpinjamandetail_list->Recordset = $tpinjamandetail_list->LoadRecordset($tpinjamandetail_list->StartRec-1, $tpinjamandetail_list->DisplayRecs);

	// Set no record found message
	if ($tpinjamandetail->CurrentAction == "" && $tpinjamandetail_list->TotalRecs == 0) {
		if ($tpinjamandetail_list->SearchWhere == "0=101")
			$tpinjamandetail_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$tpinjamandetail_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$tpinjamandetail_list->RenderOtherOptions();
?>
<?php if ($tpinjamandetail->Export == "" && $tpinjamandetail->CurrentAction == "") { ?>
<form name="ftpinjamandetaillistsrch" id="ftpinjamandetaillistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($tpinjamandetail_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="ftpinjamandetaillistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="tpinjamandetail">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($tpinjamandetail_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($tpinjamandetail_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $tpinjamandetail_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($tpinjamandetail_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($tpinjamandetail_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($tpinjamandetail_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($tpinjamandetail_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php $tpinjamandetail_list->ShowPageHeader(); ?>
<?php
$tpinjamandetail_list->ShowMessage();
?>
<?php if ($tpinjamandetail_list->TotalRecs > 0 || $tpinjamandetail->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid tpinjamandetail">
<form name="ftpinjamandetaillist" id="ftpinjamandetaillist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tpinjamandetail_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tpinjamandetail_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tpinjamandetail">
<div id="gmp_tpinjamandetail" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($tpinjamandetail_list->TotalRecs > 0 || $tpinjamandetail->CurrentAction == "gridedit") { ?>
<table id="tbl_tpinjamandetaillist" class="table ewTable">
<?php echo $tpinjamandetail->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$tpinjamandetail_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$tpinjamandetail_list->RenderListOptions();

// Render list options (header, left)
$tpinjamandetail_list->ListOptions->Render("header", "left");
?>
<?php if ($tpinjamandetail->tanggal->Visible) { // tanggal ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->tanggal) == "") { ?>
		<th data-name="tanggal"><div id="elh_tpinjamandetail_tanggal" class="tpinjamandetail_tanggal"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->tanggal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tanggal"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->tanggal) ?>',1);"><div id="elh_tpinjamandetail_tanggal" class="tpinjamandetail_tanggal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->tanggal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->tanggal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->tanggal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->periode->Visible) { // periode ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->periode) == "") { ?>
		<th data-name="periode"><div id="elh_tpinjamandetail_periode" class="tpinjamandetail_periode"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->periode->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="periode"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->periode) ?>',1);"><div id="elh_tpinjamandetail_periode" class="tpinjamandetail_periode">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->periode->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->periode->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->periode->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->id->Visible) { // id ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->id) == "") { ?>
		<th data-name="id"><div id="elh_tpinjamandetail_id" class="tpinjamandetail_id"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->id) ?>',1);"><div id="elh_tpinjamandetail_id" class="tpinjamandetail_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->id->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->angsuran->Visible) { // angsuran ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->angsuran) == "") { ?>
		<th data-name="angsuran"><div id="elh_tpinjamandetail_angsuran" class="tpinjamandetail_angsuran"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->angsuran->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="angsuran"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->angsuran) ?>',1);"><div id="elh_tpinjamandetail_angsuran" class="tpinjamandetail_angsuran">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->angsuran->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->angsuran->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->angsuran->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->masaangsuran->Visible) { // masaangsuran ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->masaangsuran) == "") { ?>
		<th data-name="masaangsuran"><div id="elh_tpinjamandetail_masaangsuran" class="tpinjamandetail_masaangsuran"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->masaangsuran->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="masaangsuran"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->masaangsuran) ?>',1);"><div id="elh_tpinjamandetail_masaangsuran" class="tpinjamandetail_masaangsuran">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->masaangsuran->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->masaangsuran->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->masaangsuran->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->dispensasidenda->Visible) { // dispensasidenda ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->dispensasidenda) == "") { ?>
		<th data-name="dispensasidenda"><div id="elh_tpinjamandetail_dispensasidenda" class="tpinjamandetail_dispensasidenda"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->dispensasidenda->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="dispensasidenda"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->dispensasidenda) ?>',1);"><div id="elh_tpinjamandetail_dispensasidenda" class="tpinjamandetail_dispensasidenda">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->dispensasidenda->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->dispensasidenda->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->dispensasidenda->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->plafond->Visible) { // plafond ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->plafond) == "") { ?>
		<th data-name="plafond"><div id="elh_tpinjamandetail_plafond" class="tpinjamandetail_plafond"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->plafond->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="plafond"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->plafond) ?>',1);"><div id="elh_tpinjamandetail_plafond" class="tpinjamandetail_plafond">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->plafond->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->plafond->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->plafond->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->angsuranpokok->Visible) { // angsuranpokok ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->angsuranpokok) == "") { ?>
		<th data-name="angsuranpokok"><div id="elh_tpinjamandetail_angsuranpokok" class="tpinjamandetail_angsuranpokok"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->angsuranpokok->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="angsuranpokok"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->angsuranpokok) ?>',1);"><div id="elh_tpinjamandetail_angsuranpokok" class="tpinjamandetail_angsuranpokok">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->angsuranpokok->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->angsuranpokok->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->angsuranpokok->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->angsuranpokokauto->Visible) { // angsuranpokokauto ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->angsuranpokokauto) == "") { ?>
		<th data-name="angsuranpokokauto"><div id="elh_tpinjamandetail_angsuranpokokauto" class="tpinjamandetail_angsuranpokokauto"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->angsuranpokokauto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="angsuranpokokauto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->angsuranpokokauto) ?>',1);"><div id="elh_tpinjamandetail_angsuranpokokauto" class="tpinjamandetail_angsuranpokokauto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->angsuranpokokauto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->angsuranpokokauto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->angsuranpokokauto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->angsuranbunga->Visible) { // angsuranbunga ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->angsuranbunga) == "") { ?>
		<th data-name="angsuranbunga"><div id="elh_tpinjamandetail_angsuranbunga" class="tpinjamandetail_angsuranbunga"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->angsuranbunga->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="angsuranbunga"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->angsuranbunga) ?>',1);"><div id="elh_tpinjamandetail_angsuranbunga" class="tpinjamandetail_angsuranbunga">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->angsuranbunga->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->angsuranbunga->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->angsuranbunga->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->angsuranbungaauto->Visible) { // angsuranbungaauto ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->angsuranbungaauto) == "") { ?>
		<th data-name="angsuranbungaauto"><div id="elh_tpinjamandetail_angsuranbungaauto" class="tpinjamandetail_angsuranbungaauto"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->angsuranbungaauto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="angsuranbungaauto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->angsuranbungaauto) ?>',1);"><div id="elh_tpinjamandetail_angsuranbungaauto" class="tpinjamandetail_angsuranbungaauto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->angsuranbungaauto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->angsuranbungaauto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->angsuranbungaauto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->denda->Visible) { // denda ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->denda) == "") { ?>
		<th data-name="denda"><div id="elh_tpinjamandetail_denda" class="tpinjamandetail_denda"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->denda->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="denda"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->denda) ?>',1);"><div id="elh_tpinjamandetail_denda" class="tpinjamandetail_denda">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->denda->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->denda->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->denda->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->dendapersen->Visible) { // dendapersen ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->dendapersen) == "") { ?>
		<th data-name="dendapersen"><div id="elh_tpinjamandetail_dendapersen" class="tpinjamandetail_dendapersen"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->dendapersen->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="dendapersen"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->dendapersen) ?>',1);"><div id="elh_tpinjamandetail_dendapersen" class="tpinjamandetail_dendapersen">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->dendapersen->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->dendapersen->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->dendapersen->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->totalangsuran->Visible) { // totalangsuran ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->totalangsuran) == "") { ?>
		<th data-name="totalangsuran"><div id="elh_tpinjamandetail_totalangsuran" class="tpinjamandetail_totalangsuran"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->totalangsuran->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="totalangsuran"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->totalangsuran) ?>',1);"><div id="elh_tpinjamandetail_totalangsuran" class="tpinjamandetail_totalangsuran">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->totalangsuran->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->totalangsuran->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->totalangsuran->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->totalangsuranauto->Visible) { // totalangsuranauto ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->totalangsuranauto) == "") { ?>
		<th data-name="totalangsuranauto"><div id="elh_tpinjamandetail_totalangsuranauto" class="tpinjamandetail_totalangsuranauto"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->totalangsuranauto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="totalangsuranauto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->totalangsuranauto) ?>',1);"><div id="elh_tpinjamandetail_totalangsuranauto" class="tpinjamandetail_totalangsuranauto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->totalangsuranauto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->totalangsuranauto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->totalangsuranauto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->sisaangsuran->Visible) { // sisaangsuran ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->sisaangsuran) == "") { ?>
		<th data-name="sisaangsuran"><div id="elh_tpinjamandetail_sisaangsuran" class="tpinjamandetail_sisaangsuran"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->sisaangsuran->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="sisaangsuran"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->sisaangsuran) ?>',1);"><div id="elh_tpinjamandetail_sisaangsuran" class="tpinjamandetail_sisaangsuran">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->sisaangsuran->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->sisaangsuran->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->sisaangsuran->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->sisaangsuranauto->Visible) { // sisaangsuranauto ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->sisaangsuranauto) == "") { ?>
		<th data-name="sisaangsuranauto"><div id="elh_tpinjamandetail_sisaangsuranauto" class="tpinjamandetail_sisaangsuranauto"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->sisaangsuranauto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="sisaangsuranauto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->sisaangsuranauto) ?>',1);"><div id="elh_tpinjamandetail_sisaangsuranauto" class="tpinjamandetail_sisaangsuranauto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->sisaangsuranauto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->sisaangsuranauto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->sisaangsuranauto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->tanggalbayar->Visible) { // tanggalbayar ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->tanggalbayar) == "") { ?>
		<th data-name="tanggalbayar"><div id="elh_tpinjamandetail_tanggalbayar" class="tpinjamandetail_tanggalbayar"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->tanggalbayar->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tanggalbayar"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->tanggalbayar) ?>',1);"><div id="elh_tpinjamandetail_tanggalbayar" class="tpinjamandetail_tanggalbayar">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->tanggalbayar->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->tanggalbayar->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->tanggalbayar->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->terlambat->Visible) { // terlambat ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->terlambat) == "") { ?>
		<th data-name="terlambat"><div id="elh_tpinjamandetail_terlambat" class="tpinjamandetail_terlambat"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->terlambat->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="terlambat"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->terlambat) ?>',1);"><div id="elh_tpinjamandetail_terlambat" class="tpinjamandetail_terlambat">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->terlambat->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->terlambat->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->terlambat->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->bayarpokok->Visible) { // bayarpokok ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->bayarpokok) == "") { ?>
		<th data-name="bayarpokok"><div id="elh_tpinjamandetail_bayarpokok" class="tpinjamandetail_bayarpokok"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->bayarpokok->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bayarpokok"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->bayarpokok) ?>',1);"><div id="elh_tpinjamandetail_bayarpokok" class="tpinjamandetail_bayarpokok">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->bayarpokok->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->bayarpokok->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->bayarpokok->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->bayarpokokauto->Visible) { // bayarpokokauto ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->bayarpokokauto) == "") { ?>
		<th data-name="bayarpokokauto"><div id="elh_tpinjamandetail_bayarpokokauto" class="tpinjamandetail_bayarpokokauto"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->bayarpokokauto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bayarpokokauto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->bayarpokokauto) ?>',1);"><div id="elh_tpinjamandetail_bayarpokokauto" class="tpinjamandetail_bayarpokokauto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->bayarpokokauto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->bayarpokokauto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->bayarpokokauto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->bayarbunga->Visible) { // bayarbunga ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->bayarbunga) == "") { ?>
		<th data-name="bayarbunga"><div id="elh_tpinjamandetail_bayarbunga" class="tpinjamandetail_bayarbunga"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->bayarbunga->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bayarbunga"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->bayarbunga) ?>',1);"><div id="elh_tpinjamandetail_bayarbunga" class="tpinjamandetail_bayarbunga">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->bayarbunga->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->bayarbunga->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->bayarbunga->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->bayarbungaauto->Visible) { // bayarbungaauto ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->bayarbungaauto) == "") { ?>
		<th data-name="bayarbungaauto"><div id="elh_tpinjamandetail_bayarbungaauto" class="tpinjamandetail_bayarbungaauto"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->bayarbungaauto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bayarbungaauto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->bayarbungaauto) ?>',1);"><div id="elh_tpinjamandetail_bayarbungaauto" class="tpinjamandetail_bayarbungaauto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->bayarbungaauto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->bayarbungaauto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->bayarbungaauto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->bayardenda->Visible) { // bayardenda ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->bayardenda) == "") { ?>
		<th data-name="bayardenda"><div id="elh_tpinjamandetail_bayardenda" class="tpinjamandetail_bayardenda"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->bayardenda->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bayardenda"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->bayardenda) ?>',1);"><div id="elh_tpinjamandetail_bayardenda" class="tpinjamandetail_bayardenda">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->bayardenda->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->bayardenda->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->bayardenda->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->bayardendaauto->Visible) { // bayardendaauto ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->bayardendaauto) == "") { ?>
		<th data-name="bayardendaauto"><div id="elh_tpinjamandetail_bayardendaauto" class="tpinjamandetail_bayardendaauto"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->bayardendaauto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bayardendaauto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->bayardendaauto) ?>',1);"><div id="elh_tpinjamandetail_bayardendaauto" class="tpinjamandetail_bayardendaauto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->bayardendaauto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->bayardendaauto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->bayardendaauto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->bayartitipan->Visible) { // bayartitipan ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->bayartitipan) == "") { ?>
		<th data-name="bayartitipan"><div id="elh_tpinjamandetail_bayartitipan" class="tpinjamandetail_bayartitipan"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->bayartitipan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bayartitipan"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->bayartitipan) ?>',1);"><div id="elh_tpinjamandetail_bayartitipan" class="tpinjamandetail_bayartitipan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->bayartitipan->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->bayartitipan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->bayartitipan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->bayartitipanauto->Visible) { // bayartitipanauto ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->bayartitipanauto) == "") { ?>
		<th data-name="bayartitipanauto"><div id="elh_tpinjamandetail_bayartitipanauto" class="tpinjamandetail_bayartitipanauto"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->bayartitipanauto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bayartitipanauto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->bayartitipanauto) ?>',1);"><div id="elh_tpinjamandetail_bayartitipanauto" class="tpinjamandetail_bayartitipanauto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->bayartitipanauto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->bayartitipanauto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->bayartitipanauto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->totalbayar->Visible) { // totalbayar ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->totalbayar) == "") { ?>
		<th data-name="totalbayar"><div id="elh_tpinjamandetail_totalbayar" class="tpinjamandetail_totalbayar"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->totalbayar->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="totalbayar"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->totalbayar) ?>',1);"><div id="elh_tpinjamandetail_totalbayar" class="tpinjamandetail_totalbayar">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->totalbayar->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->totalbayar->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->totalbayar->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->totalbayarauto->Visible) { // totalbayarauto ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->totalbayarauto) == "") { ?>
		<th data-name="totalbayarauto"><div id="elh_tpinjamandetail_totalbayarauto" class="tpinjamandetail_totalbayarauto"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->totalbayarauto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="totalbayarauto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->totalbayarauto) ?>',1);"><div id="elh_tpinjamandetail_totalbayarauto" class="tpinjamandetail_totalbayarauto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->totalbayarauto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->totalbayarauto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->totalbayarauto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->pelunasan->Visible) { // pelunasan ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->pelunasan) == "") { ?>
		<th data-name="pelunasan"><div id="elh_tpinjamandetail_pelunasan" class="tpinjamandetail_pelunasan"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->pelunasan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pelunasan"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->pelunasan) ?>',1);"><div id="elh_tpinjamandetail_pelunasan" class="tpinjamandetail_pelunasan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->pelunasan->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->pelunasan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->pelunasan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->pelunasanauto->Visible) { // pelunasanauto ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->pelunasanauto) == "") { ?>
		<th data-name="pelunasanauto"><div id="elh_tpinjamandetail_pelunasanauto" class="tpinjamandetail_pelunasanauto"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->pelunasanauto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pelunasanauto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->pelunasanauto) ?>',1);"><div id="elh_tpinjamandetail_pelunasanauto" class="tpinjamandetail_pelunasanauto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->pelunasanauto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->pelunasanauto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->pelunasanauto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->finalty->Visible) { // finalty ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->finalty) == "") { ?>
		<th data-name="finalty"><div id="elh_tpinjamandetail_finalty" class="tpinjamandetail_finalty"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->finalty->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="finalty"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->finalty) ?>',1);"><div id="elh_tpinjamandetail_finalty" class="tpinjamandetail_finalty">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->finalty->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->finalty->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->finalty->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->finaltyauto->Visible) { // finaltyauto ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->finaltyauto) == "") { ?>
		<th data-name="finaltyauto"><div id="elh_tpinjamandetail_finaltyauto" class="tpinjamandetail_finaltyauto"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->finaltyauto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="finaltyauto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->finaltyauto) ?>',1);"><div id="elh_tpinjamandetail_finaltyauto" class="tpinjamandetail_finaltyauto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->finaltyauto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->finaltyauto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->finaltyauto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->status->Visible) { // status ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->status) == "") { ?>
		<th data-name="status"><div id="elh_tpinjamandetail_status" class="tpinjamandetail_status"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->status->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->status) ?>',1);"><div id="elh_tpinjamandetail_status" class="tpinjamandetail_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->status->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjamandetail->keterangan->Visible) { // keterangan ?>
	<?php if ($tpinjamandetail->SortUrl($tpinjamandetail->keterangan) == "") { ?>
		<th data-name="keterangan"><div id="elh_tpinjamandetail_keterangan" class="tpinjamandetail_keterangan"><div class="ewTableHeaderCaption"><?php echo $tpinjamandetail->keterangan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="keterangan"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjamandetail->SortUrl($tpinjamandetail->keterangan) ?>',1);"><div id="elh_tpinjamandetail_keterangan" class="tpinjamandetail_keterangan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjamandetail->keterangan->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjamandetail->keterangan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjamandetail->keterangan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$tpinjamandetail_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($tpinjamandetail->ExportAll && $tpinjamandetail->Export <> "") {
	$tpinjamandetail_list->StopRec = $tpinjamandetail_list->TotalRecs;
} else {

	// Set the last record to display
	if ($tpinjamandetail_list->TotalRecs > $tpinjamandetail_list->StartRec + $tpinjamandetail_list->DisplayRecs - 1)
		$tpinjamandetail_list->StopRec = $tpinjamandetail_list->StartRec + $tpinjamandetail_list->DisplayRecs - 1;
	else
		$tpinjamandetail_list->StopRec = $tpinjamandetail_list->TotalRecs;
}
$tpinjamandetail_list->RecCnt = $tpinjamandetail_list->StartRec - 1;
if ($tpinjamandetail_list->Recordset && !$tpinjamandetail_list->Recordset->EOF) {
	$tpinjamandetail_list->Recordset->MoveFirst();
	$bSelectLimit = $tpinjamandetail_list->UseSelectLimit;
	if (!$bSelectLimit && $tpinjamandetail_list->StartRec > 1)
		$tpinjamandetail_list->Recordset->Move($tpinjamandetail_list->StartRec - 1);
} elseif (!$tpinjamandetail->AllowAddDeleteRow && $tpinjamandetail_list->StopRec == 0) {
	$tpinjamandetail_list->StopRec = $tpinjamandetail->GridAddRowCount;
}

// Initialize aggregate
$tpinjamandetail->RowType = EW_ROWTYPE_AGGREGATEINIT;
$tpinjamandetail->ResetAttrs();
$tpinjamandetail_list->RenderRow();
while ($tpinjamandetail_list->RecCnt < $tpinjamandetail_list->StopRec) {
	$tpinjamandetail_list->RecCnt++;
	if (intval($tpinjamandetail_list->RecCnt) >= intval($tpinjamandetail_list->StartRec)) {
		$tpinjamandetail_list->RowCnt++;

		// Set up key count
		$tpinjamandetail_list->KeyCount = $tpinjamandetail_list->RowIndex;

		// Init row class and style
		$tpinjamandetail->ResetAttrs();
		$tpinjamandetail->CssClass = "";
		if ($tpinjamandetail->CurrentAction == "gridadd") {
		} else {
			$tpinjamandetail_list->LoadRowValues($tpinjamandetail_list->Recordset); // Load row values
		}
		$tpinjamandetail->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$tpinjamandetail->RowAttrs = array_merge($tpinjamandetail->RowAttrs, array('data-rowindex'=>$tpinjamandetail_list->RowCnt, 'id'=>'r' . $tpinjamandetail_list->RowCnt . '_tpinjamandetail', 'data-rowtype'=>$tpinjamandetail->RowType));

		// Render row
		$tpinjamandetail_list->RenderRow();

		// Render list options
		$tpinjamandetail_list->RenderListOptions();
?>
	<tr<?php echo $tpinjamandetail->RowAttributes() ?>>
<?php

// Render list options (body, left)
$tpinjamandetail_list->ListOptions->Render("body", "left", $tpinjamandetail_list->RowCnt);
?>
	<?php if ($tpinjamandetail->tanggal->Visible) { // tanggal ?>
		<td data-name="tanggal"<?php echo $tpinjamandetail->tanggal->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_tanggal" class="tpinjamandetail_tanggal">
<span<?php echo $tpinjamandetail->tanggal->ViewAttributes() ?>>
<?php echo $tpinjamandetail->tanggal->ListViewValue() ?></span>
</span>
<a id="<?php echo $tpinjamandetail_list->PageObjName . "_row_" . $tpinjamandetail_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($tpinjamandetail->periode->Visible) { // periode ?>
		<td data-name="periode"<?php echo $tpinjamandetail->periode->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_periode" class="tpinjamandetail_periode">
<span<?php echo $tpinjamandetail->periode->ViewAttributes() ?>>
<?php echo $tpinjamandetail->periode->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->id->Visible) { // id ?>
		<td data-name="id"<?php echo $tpinjamandetail->id->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_id" class="tpinjamandetail_id">
<span<?php echo $tpinjamandetail->id->ViewAttributes() ?>>
<?php echo $tpinjamandetail->id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->angsuran->Visible) { // angsuran ?>
		<td data-name="angsuran"<?php echo $tpinjamandetail->angsuran->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_angsuran" class="tpinjamandetail_angsuran">
<span<?php echo $tpinjamandetail->angsuran->ViewAttributes() ?>>
<?php echo $tpinjamandetail->angsuran->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->masaangsuran->Visible) { // masaangsuran ?>
		<td data-name="masaangsuran"<?php echo $tpinjamandetail->masaangsuran->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_masaangsuran" class="tpinjamandetail_masaangsuran">
<span<?php echo $tpinjamandetail->masaangsuran->ViewAttributes() ?>>
<?php echo $tpinjamandetail->masaangsuran->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->dispensasidenda->Visible) { // dispensasidenda ?>
		<td data-name="dispensasidenda"<?php echo $tpinjamandetail->dispensasidenda->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_dispensasidenda" class="tpinjamandetail_dispensasidenda">
<span<?php echo $tpinjamandetail->dispensasidenda->ViewAttributes() ?>>
<?php echo $tpinjamandetail->dispensasidenda->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->plafond->Visible) { // plafond ?>
		<td data-name="plafond"<?php echo $tpinjamandetail->plafond->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_plafond" class="tpinjamandetail_plafond">
<span<?php echo $tpinjamandetail->plafond->ViewAttributes() ?>>
<?php echo $tpinjamandetail->plafond->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->angsuranpokok->Visible) { // angsuranpokok ?>
		<td data-name="angsuranpokok"<?php echo $tpinjamandetail->angsuranpokok->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_angsuranpokok" class="tpinjamandetail_angsuranpokok">
<span<?php echo $tpinjamandetail->angsuranpokok->ViewAttributes() ?>>
<?php echo $tpinjamandetail->angsuranpokok->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->angsuranpokokauto->Visible) { // angsuranpokokauto ?>
		<td data-name="angsuranpokokauto"<?php echo $tpinjamandetail->angsuranpokokauto->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_angsuranpokokauto" class="tpinjamandetail_angsuranpokokauto">
<span<?php echo $tpinjamandetail->angsuranpokokauto->ViewAttributes() ?>>
<?php echo $tpinjamandetail->angsuranpokokauto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->angsuranbunga->Visible) { // angsuranbunga ?>
		<td data-name="angsuranbunga"<?php echo $tpinjamandetail->angsuranbunga->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_angsuranbunga" class="tpinjamandetail_angsuranbunga">
<span<?php echo $tpinjamandetail->angsuranbunga->ViewAttributes() ?>>
<?php echo $tpinjamandetail->angsuranbunga->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->angsuranbungaauto->Visible) { // angsuranbungaauto ?>
		<td data-name="angsuranbungaauto"<?php echo $tpinjamandetail->angsuranbungaauto->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_angsuranbungaauto" class="tpinjamandetail_angsuranbungaauto">
<span<?php echo $tpinjamandetail->angsuranbungaauto->ViewAttributes() ?>>
<?php echo $tpinjamandetail->angsuranbungaauto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->denda->Visible) { // denda ?>
		<td data-name="denda"<?php echo $tpinjamandetail->denda->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_denda" class="tpinjamandetail_denda">
<span<?php echo $tpinjamandetail->denda->ViewAttributes() ?>>
<?php echo $tpinjamandetail->denda->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->dendapersen->Visible) { // dendapersen ?>
		<td data-name="dendapersen"<?php echo $tpinjamandetail->dendapersen->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_dendapersen" class="tpinjamandetail_dendapersen">
<span<?php echo $tpinjamandetail->dendapersen->ViewAttributes() ?>>
<?php echo $tpinjamandetail->dendapersen->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->totalangsuran->Visible) { // totalangsuran ?>
		<td data-name="totalangsuran"<?php echo $tpinjamandetail->totalangsuran->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_totalangsuran" class="tpinjamandetail_totalangsuran">
<span<?php echo $tpinjamandetail->totalangsuran->ViewAttributes() ?>>
<?php echo $tpinjamandetail->totalangsuran->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->totalangsuranauto->Visible) { // totalangsuranauto ?>
		<td data-name="totalangsuranauto"<?php echo $tpinjamandetail->totalangsuranauto->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_totalangsuranauto" class="tpinjamandetail_totalangsuranauto">
<span<?php echo $tpinjamandetail->totalangsuranauto->ViewAttributes() ?>>
<?php echo $tpinjamandetail->totalangsuranauto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->sisaangsuran->Visible) { // sisaangsuran ?>
		<td data-name="sisaangsuran"<?php echo $tpinjamandetail->sisaangsuran->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_sisaangsuran" class="tpinjamandetail_sisaangsuran">
<span<?php echo $tpinjamandetail->sisaangsuran->ViewAttributes() ?>>
<?php echo $tpinjamandetail->sisaangsuran->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->sisaangsuranauto->Visible) { // sisaangsuranauto ?>
		<td data-name="sisaangsuranauto"<?php echo $tpinjamandetail->sisaangsuranauto->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_sisaangsuranauto" class="tpinjamandetail_sisaangsuranauto">
<span<?php echo $tpinjamandetail->sisaangsuranauto->ViewAttributes() ?>>
<?php echo $tpinjamandetail->sisaangsuranauto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->tanggalbayar->Visible) { // tanggalbayar ?>
		<td data-name="tanggalbayar"<?php echo $tpinjamandetail->tanggalbayar->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_tanggalbayar" class="tpinjamandetail_tanggalbayar">
<span<?php echo $tpinjamandetail->tanggalbayar->ViewAttributes() ?>>
<?php echo $tpinjamandetail->tanggalbayar->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->terlambat->Visible) { // terlambat ?>
		<td data-name="terlambat"<?php echo $tpinjamandetail->terlambat->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_terlambat" class="tpinjamandetail_terlambat">
<span<?php echo $tpinjamandetail->terlambat->ViewAttributes() ?>>
<?php echo $tpinjamandetail->terlambat->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->bayarpokok->Visible) { // bayarpokok ?>
		<td data-name="bayarpokok"<?php echo $tpinjamandetail->bayarpokok->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_bayarpokok" class="tpinjamandetail_bayarpokok">
<span<?php echo $tpinjamandetail->bayarpokok->ViewAttributes() ?>>
<?php echo $tpinjamandetail->bayarpokok->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->bayarpokokauto->Visible) { // bayarpokokauto ?>
		<td data-name="bayarpokokauto"<?php echo $tpinjamandetail->bayarpokokauto->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_bayarpokokauto" class="tpinjamandetail_bayarpokokauto">
<span<?php echo $tpinjamandetail->bayarpokokauto->ViewAttributes() ?>>
<?php echo $tpinjamandetail->bayarpokokauto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->bayarbunga->Visible) { // bayarbunga ?>
		<td data-name="bayarbunga"<?php echo $tpinjamandetail->bayarbunga->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_bayarbunga" class="tpinjamandetail_bayarbunga">
<span<?php echo $tpinjamandetail->bayarbunga->ViewAttributes() ?>>
<?php echo $tpinjamandetail->bayarbunga->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->bayarbungaauto->Visible) { // bayarbungaauto ?>
		<td data-name="bayarbungaauto"<?php echo $tpinjamandetail->bayarbungaauto->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_bayarbungaauto" class="tpinjamandetail_bayarbungaauto">
<span<?php echo $tpinjamandetail->bayarbungaauto->ViewAttributes() ?>>
<?php echo $tpinjamandetail->bayarbungaauto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->bayardenda->Visible) { // bayardenda ?>
		<td data-name="bayardenda"<?php echo $tpinjamandetail->bayardenda->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_bayardenda" class="tpinjamandetail_bayardenda">
<span<?php echo $tpinjamandetail->bayardenda->ViewAttributes() ?>>
<?php echo $tpinjamandetail->bayardenda->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->bayardendaauto->Visible) { // bayardendaauto ?>
		<td data-name="bayardendaauto"<?php echo $tpinjamandetail->bayardendaauto->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_bayardendaauto" class="tpinjamandetail_bayardendaauto">
<span<?php echo $tpinjamandetail->bayardendaauto->ViewAttributes() ?>>
<?php echo $tpinjamandetail->bayardendaauto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->bayartitipan->Visible) { // bayartitipan ?>
		<td data-name="bayartitipan"<?php echo $tpinjamandetail->bayartitipan->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_bayartitipan" class="tpinjamandetail_bayartitipan">
<span<?php echo $tpinjamandetail->bayartitipan->ViewAttributes() ?>>
<?php echo $tpinjamandetail->bayartitipan->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->bayartitipanauto->Visible) { // bayartitipanauto ?>
		<td data-name="bayartitipanauto"<?php echo $tpinjamandetail->bayartitipanauto->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_bayartitipanauto" class="tpinjamandetail_bayartitipanauto">
<span<?php echo $tpinjamandetail->bayartitipanauto->ViewAttributes() ?>>
<?php echo $tpinjamandetail->bayartitipanauto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->totalbayar->Visible) { // totalbayar ?>
		<td data-name="totalbayar"<?php echo $tpinjamandetail->totalbayar->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_totalbayar" class="tpinjamandetail_totalbayar">
<span<?php echo $tpinjamandetail->totalbayar->ViewAttributes() ?>>
<?php echo $tpinjamandetail->totalbayar->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->totalbayarauto->Visible) { // totalbayarauto ?>
		<td data-name="totalbayarauto"<?php echo $tpinjamandetail->totalbayarauto->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_totalbayarauto" class="tpinjamandetail_totalbayarauto">
<span<?php echo $tpinjamandetail->totalbayarauto->ViewAttributes() ?>>
<?php echo $tpinjamandetail->totalbayarauto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->pelunasan->Visible) { // pelunasan ?>
		<td data-name="pelunasan"<?php echo $tpinjamandetail->pelunasan->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_pelunasan" class="tpinjamandetail_pelunasan">
<span<?php echo $tpinjamandetail->pelunasan->ViewAttributes() ?>>
<?php echo $tpinjamandetail->pelunasan->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->pelunasanauto->Visible) { // pelunasanauto ?>
		<td data-name="pelunasanauto"<?php echo $tpinjamandetail->pelunasanauto->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_pelunasanauto" class="tpinjamandetail_pelunasanauto">
<span<?php echo $tpinjamandetail->pelunasanauto->ViewAttributes() ?>>
<?php echo $tpinjamandetail->pelunasanauto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->finalty->Visible) { // finalty ?>
		<td data-name="finalty"<?php echo $tpinjamandetail->finalty->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_finalty" class="tpinjamandetail_finalty">
<span<?php echo $tpinjamandetail->finalty->ViewAttributes() ?>>
<?php echo $tpinjamandetail->finalty->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->finaltyauto->Visible) { // finaltyauto ?>
		<td data-name="finaltyauto"<?php echo $tpinjamandetail->finaltyauto->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_finaltyauto" class="tpinjamandetail_finaltyauto">
<span<?php echo $tpinjamandetail->finaltyauto->ViewAttributes() ?>>
<?php echo $tpinjamandetail->finaltyauto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->status->Visible) { // status ?>
		<td data-name="status"<?php echo $tpinjamandetail->status->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_status" class="tpinjamandetail_status">
<span<?php echo $tpinjamandetail->status->ViewAttributes() ?>>
<?php echo $tpinjamandetail->status->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjamandetail->keterangan->Visible) { // keterangan ?>
		<td data-name="keterangan"<?php echo $tpinjamandetail->keterangan->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail_list->RowCnt ?>_tpinjamandetail_keterangan" class="tpinjamandetail_keterangan">
<span<?php echo $tpinjamandetail->keterangan->ViewAttributes() ?>>
<?php echo $tpinjamandetail->keterangan->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$tpinjamandetail_list->ListOptions->Render("body", "right", $tpinjamandetail_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($tpinjamandetail->CurrentAction <> "gridadd")
		$tpinjamandetail_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($tpinjamandetail->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($tpinjamandetail_list->Recordset)
	$tpinjamandetail_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($tpinjamandetail->CurrentAction <> "gridadd" && $tpinjamandetail->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($tpinjamandetail_list->Pager)) $tpinjamandetail_list->Pager = new cPrevNextPager($tpinjamandetail_list->StartRec, $tpinjamandetail_list->DisplayRecs, $tpinjamandetail_list->TotalRecs) ?>
<?php if ($tpinjamandetail_list->Pager->RecordCount > 0 && $tpinjamandetail_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($tpinjamandetail_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $tpinjamandetail_list->PageUrl() ?>start=<?php echo $tpinjamandetail_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($tpinjamandetail_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $tpinjamandetail_list->PageUrl() ?>start=<?php echo $tpinjamandetail_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $tpinjamandetail_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($tpinjamandetail_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $tpinjamandetail_list->PageUrl() ?>start=<?php echo $tpinjamandetail_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($tpinjamandetail_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $tpinjamandetail_list->PageUrl() ?>start=<?php echo $tpinjamandetail_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $tpinjamandetail_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $tpinjamandetail_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $tpinjamandetail_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $tpinjamandetail_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($tpinjamandetail_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($tpinjamandetail_list->TotalRecs == 0 && $tpinjamandetail->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($tpinjamandetail_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
ftpinjamandetaillistsrch.FilterList = <?php echo $tpinjamandetail_list->GetFilterList() ?>;
ftpinjamandetaillistsrch.Init();
ftpinjamandetaillist.Init();
</script>
<?php
$tpinjamandetail_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tpinjamandetail_list->Page_Terminate();
?>
