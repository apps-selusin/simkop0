<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "tbayartitipaninfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$tbayartitipan_list = NULL; // Initialize page object first

class ctbayartitipan_list extends ctbayartitipan {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tbayartitipan';

	// Page object name
	var $PageObjName = 'tbayartitipan_list';

	// Grid form hidden field names
	var $FormName = 'ftbayartitipanlist';
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

		// Table object (tbayartitipan)
		if (!isset($GLOBALS["tbayartitipan"]) || get_class($GLOBALS["tbayartitipan"]) == "ctbayartitipan") {
			$GLOBALS["tbayartitipan"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tbayartitipan"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "tbayartitipanadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "tbayartitipandelete.php";
		$this->MultiUpdateUrl = "tbayartitipanupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tbayartitipan', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption ftbayartitipanlistsrch";

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
		$this->transaksi->SetVisibility();
		$this->referensi->SetVisibility();
		$this->anggota->SetVisibility();
		$this->namaanggota->SetVisibility();
		$this->alamat->SetVisibility();
		$this->pekerjaan->SetVisibility();
		$this->telepon->SetVisibility();
		$this->hp->SetVisibility();
		$this->fax->SetVisibility();
		$this->_email->SetVisibility();
		$this->website->SetVisibility();
		$this->jenisanggota->SetVisibility();
		$this->model->SetVisibility();
		$this->jenispinjaman->SetVisibility();
		$this->jenisbunga->SetVisibility();
		$this->angsuran->SetVisibility();
		$this->masaangsuran->SetVisibility();
		$this->jatuhtempo->SetVisibility();
		$this->dispensasidenda->SetVisibility();
		$this->titipan->SetVisibility();
		$this->bayartitipan->SetVisibility();
		$this->bayartitipanauto->SetVisibility();
		$this->terbilang->SetVisibility();
		$this->petugas->SetVisibility();
		$this->pembayaran->SetVisibility();
		$this->bank->SetVisibility();
		$this->atasnama->SetVisibility();
		$this->tipe->SetVisibility();
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
		global $EW_EXPORT, $tbayartitipan;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($tbayartitipan);
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
			$this->titipan->setFormValue($arrKeyFlds[1]);
			if (!is_numeric($this->titipan->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = isset($UserProfile) ? $UserProfile->GetSearchFilters(CurrentUserName(), "ftbayartitipanlistsrch") : "";
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->tanggal->AdvancedSearch->ToJSON(), ","); // Field tanggal
		$sFilterList = ew_Concat($sFilterList, $this->periode->AdvancedSearch->ToJSON(), ","); // Field periode
		$sFilterList = ew_Concat($sFilterList, $this->id->AdvancedSearch->ToJSON(), ","); // Field id
		$sFilterList = ew_Concat($sFilterList, $this->transaksi->AdvancedSearch->ToJSON(), ","); // Field transaksi
		$sFilterList = ew_Concat($sFilterList, $this->referensi->AdvancedSearch->ToJSON(), ","); // Field referensi
		$sFilterList = ew_Concat($sFilterList, $this->anggota->AdvancedSearch->ToJSON(), ","); // Field anggota
		$sFilterList = ew_Concat($sFilterList, $this->namaanggota->AdvancedSearch->ToJSON(), ","); // Field namaanggota
		$sFilterList = ew_Concat($sFilterList, $this->alamat->AdvancedSearch->ToJSON(), ","); // Field alamat
		$sFilterList = ew_Concat($sFilterList, $this->pekerjaan->AdvancedSearch->ToJSON(), ","); // Field pekerjaan
		$sFilterList = ew_Concat($sFilterList, $this->telepon->AdvancedSearch->ToJSON(), ","); // Field telepon
		$sFilterList = ew_Concat($sFilterList, $this->hp->AdvancedSearch->ToJSON(), ","); // Field hp
		$sFilterList = ew_Concat($sFilterList, $this->fax->AdvancedSearch->ToJSON(), ","); // Field fax
		$sFilterList = ew_Concat($sFilterList, $this->_email->AdvancedSearch->ToJSON(), ","); // Field email
		$sFilterList = ew_Concat($sFilterList, $this->website->AdvancedSearch->ToJSON(), ","); // Field website
		$sFilterList = ew_Concat($sFilterList, $this->jenisanggota->AdvancedSearch->ToJSON(), ","); // Field jenisanggota
		$sFilterList = ew_Concat($sFilterList, $this->model->AdvancedSearch->ToJSON(), ","); // Field model
		$sFilterList = ew_Concat($sFilterList, $this->jenispinjaman->AdvancedSearch->ToJSON(), ","); // Field jenispinjaman
		$sFilterList = ew_Concat($sFilterList, $this->jenisbunga->AdvancedSearch->ToJSON(), ","); // Field jenisbunga
		$sFilterList = ew_Concat($sFilterList, $this->angsuran->AdvancedSearch->ToJSON(), ","); // Field angsuran
		$sFilterList = ew_Concat($sFilterList, $this->masaangsuran->AdvancedSearch->ToJSON(), ","); // Field masaangsuran
		$sFilterList = ew_Concat($sFilterList, $this->jatuhtempo->AdvancedSearch->ToJSON(), ","); // Field jatuhtempo
		$sFilterList = ew_Concat($sFilterList, $this->dispensasidenda->AdvancedSearch->ToJSON(), ","); // Field dispensasidenda
		$sFilterList = ew_Concat($sFilterList, $this->titipan->AdvancedSearch->ToJSON(), ","); // Field titipan
		$sFilterList = ew_Concat($sFilterList, $this->bayartitipan->AdvancedSearch->ToJSON(), ","); // Field bayartitipan
		$sFilterList = ew_Concat($sFilterList, $this->bayartitipanauto->AdvancedSearch->ToJSON(), ","); // Field bayartitipanauto
		$sFilterList = ew_Concat($sFilterList, $this->terbilang->AdvancedSearch->ToJSON(), ","); // Field terbilang
		$sFilterList = ew_Concat($sFilterList, $this->petugas->AdvancedSearch->ToJSON(), ","); // Field petugas
		$sFilterList = ew_Concat($sFilterList, $this->pembayaran->AdvancedSearch->ToJSON(), ","); // Field pembayaran
		$sFilterList = ew_Concat($sFilterList, $this->bank->AdvancedSearch->ToJSON(), ","); // Field bank
		$sFilterList = ew_Concat($sFilterList, $this->atasnama->AdvancedSearch->ToJSON(), ","); // Field atasnama
		$sFilterList = ew_Concat($sFilterList, $this->tipe->AdvancedSearch->ToJSON(), ","); // Field tipe
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "ftbayartitipanlistsrch", $filters);

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

		// Field anggota
		$this->anggota->AdvancedSearch->SearchValue = @$filter["x_anggota"];
		$this->anggota->AdvancedSearch->SearchOperator = @$filter["z_anggota"];
		$this->anggota->AdvancedSearch->SearchCondition = @$filter["v_anggota"];
		$this->anggota->AdvancedSearch->SearchValue2 = @$filter["y_anggota"];
		$this->anggota->AdvancedSearch->SearchOperator2 = @$filter["w_anggota"];
		$this->anggota->AdvancedSearch->Save();

		// Field namaanggota
		$this->namaanggota->AdvancedSearch->SearchValue = @$filter["x_namaanggota"];
		$this->namaanggota->AdvancedSearch->SearchOperator = @$filter["z_namaanggota"];
		$this->namaanggota->AdvancedSearch->SearchCondition = @$filter["v_namaanggota"];
		$this->namaanggota->AdvancedSearch->SearchValue2 = @$filter["y_namaanggota"];
		$this->namaanggota->AdvancedSearch->SearchOperator2 = @$filter["w_namaanggota"];
		$this->namaanggota->AdvancedSearch->Save();

		// Field alamat
		$this->alamat->AdvancedSearch->SearchValue = @$filter["x_alamat"];
		$this->alamat->AdvancedSearch->SearchOperator = @$filter["z_alamat"];
		$this->alamat->AdvancedSearch->SearchCondition = @$filter["v_alamat"];
		$this->alamat->AdvancedSearch->SearchValue2 = @$filter["y_alamat"];
		$this->alamat->AdvancedSearch->SearchOperator2 = @$filter["w_alamat"];
		$this->alamat->AdvancedSearch->Save();

		// Field pekerjaan
		$this->pekerjaan->AdvancedSearch->SearchValue = @$filter["x_pekerjaan"];
		$this->pekerjaan->AdvancedSearch->SearchOperator = @$filter["z_pekerjaan"];
		$this->pekerjaan->AdvancedSearch->SearchCondition = @$filter["v_pekerjaan"];
		$this->pekerjaan->AdvancedSearch->SearchValue2 = @$filter["y_pekerjaan"];
		$this->pekerjaan->AdvancedSearch->SearchOperator2 = @$filter["w_pekerjaan"];
		$this->pekerjaan->AdvancedSearch->Save();

		// Field telepon
		$this->telepon->AdvancedSearch->SearchValue = @$filter["x_telepon"];
		$this->telepon->AdvancedSearch->SearchOperator = @$filter["z_telepon"];
		$this->telepon->AdvancedSearch->SearchCondition = @$filter["v_telepon"];
		$this->telepon->AdvancedSearch->SearchValue2 = @$filter["y_telepon"];
		$this->telepon->AdvancedSearch->SearchOperator2 = @$filter["w_telepon"];
		$this->telepon->AdvancedSearch->Save();

		// Field hp
		$this->hp->AdvancedSearch->SearchValue = @$filter["x_hp"];
		$this->hp->AdvancedSearch->SearchOperator = @$filter["z_hp"];
		$this->hp->AdvancedSearch->SearchCondition = @$filter["v_hp"];
		$this->hp->AdvancedSearch->SearchValue2 = @$filter["y_hp"];
		$this->hp->AdvancedSearch->SearchOperator2 = @$filter["w_hp"];
		$this->hp->AdvancedSearch->Save();

		// Field fax
		$this->fax->AdvancedSearch->SearchValue = @$filter["x_fax"];
		$this->fax->AdvancedSearch->SearchOperator = @$filter["z_fax"];
		$this->fax->AdvancedSearch->SearchCondition = @$filter["v_fax"];
		$this->fax->AdvancedSearch->SearchValue2 = @$filter["y_fax"];
		$this->fax->AdvancedSearch->SearchOperator2 = @$filter["w_fax"];
		$this->fax->AdvancedSearch->Save();

		// Field email
		$this->_email->AdvancedSearch->SearchValue = @$filter["x__email"];
		$this->_email->AdvancedSearch->SearchOperator = @$filter["z__email"];
		$this->_email->AdvancedSearch->SearchCondition = @$filter["v__email"];
		$this->_email->AdvancedSearch->SearchValue2 = @$filter["y__email"];
		$this->_email->AdvancedSearch->SearchOperator2 = @$filter["w__email"];
		$this->_email->AdvancedSearch->Save();

		// Field website
		$this->website->AdvancedSearch->SearchValue = @$filter["x_website"];
		$this->website->AdvancedSearch->SearchOperator = @$filter["z_website"];
		$this->website->AdvancedSearch->SearchCondition = @$filter["v_website"];
		$this->website->AdvancedSearch->SearchValue2 = @$filter["y_website"];
		$this->website->AdvancedSearch->SearchOperator2 = @$filter["w_website"];
		$this->website->AdvancedSearch->Save();

		// Field jenisanggota
		$this->jenisanggota->AdvancedSearch->SearchValue = @$filter["x_jenisanggota"];
		$this->jenisanggota->AdvancedSearch->SearchOperator = @$filter["z_jenisanggota"];
		$this->jenisanggota->AdvancedSearch->SearchCondition = @$filter["v_jenisanggota"];
		$this->jenisanggota->AdvancedSearch->SearchValue2 = @$filter["y_jenisanggota"];
		$this->jenisanggota->AdvancedSearch->SearchOperator2 = @$filter["w_jenisanggota"];
		$this->jenisanggota->AdvancedSearch->Save();

		// Field model
		$this->model->AdvancedSearch->SearchValue = @$filter["x_model"];
		$this->model->AdvancedSearch->SearchOperator = @$filter["z_model"];
		$this->model->AdvancedSearch->SearchCondition = @$filter["v_model"];
		$this->model->AdvancedSearch->SearchValue2 = @$filter["y_model"];
		$this->model->AdvancedSearch->SearchOperator2 = @$filter["w_model"];
		$this->model->AdvancedSearch->Save();

		// Field jenispinjaman
		$this->jenispinjaman->AdvancedSearch->SearchValue = @$filter["x_jenispinjaman"];
		$this->jenispinjaman->AdvancedSearch->SearchOperator = @$filter["z_jenispinjaman"];
		$this->jenispinjaman->AdvancedSearch->SearchCondition = @$filter["v_jenispinjaman"];
		$this->jenispinjaman->AdvancedSearch->SearchValue2 = @$filter["y_jenispinjaman"];
		$this->jenispinjaman->AdvancedSearch->SearchOperator2 = @$filter["w_jenispinjaman"];
		$this->jenispinjaman->AdvancedSearch->Save();

		// Field jenisbunga
		$this->jenisbunga->AdvancedSearch->SearchValue = @$filter["x_jenisbunga"];
		$this->jenisbunga->AdvancedSearch->SearchOperator = @$filter["z_jenisbunga"];
		$this->jenisbunga->AdvancedSearch->SearchCondition = @$filter["v_jenisbunga"];
		$this->jenisbunga->AdvancedSearch->SearchValue2 = @$filter["y_jenisbunga"];
		$this->jenisbunga->AdvancedSearch->SearchOperator2 = @$filter["w_jenisbunga"];
		$this->jenisbunga->AdvancedSearch->Save();

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

		// Field jatuhtempo
		$this->jatuhtempo->AdvancedSearch->SearchValue = @$filter["x_jatuhtempo"];
		$this->jatuhtempo->AdvancedSearch->SearchOperator = @$filter["z_jatuhtempo"];
		$this->jatuhtempo->AdvancedSearch->SearchCondition = @$filter["v_jatuhtempo"];
		$this->jatuhtempo->AdvancedSearch->SearchValue2 = @$filter["y_jatuhtempo"];
		$this->jatuhtempo->AdvancedSearch->SearchOperator2 = @$filter["w_jatuhtempo"];
		$this->jatuhtempo->AdvancedSearch->Save();

		// Field dispensasidenda
		$this->dispensasidenda->AdvancedSearch->SearchValue = @$filter["x_dispensasidenda"];
		$this->dispensasidenda->AdvancedSearch->SearchOperator = @$filter["z_dispensasidenda"];
		$this->dispensasidenda->AdvancedSearch->SearchCondition = @$filter["v_dispensasidenda"];
		$this->dispensasidenda->AdvancedSearch->SearchValue2 = @$filter["y_dispensasidenda"];
		$this->dispensasidenda->AdvancedSearch->SearchOperator2 = @$filter["w_dispensasidenda"];
		$this->dispensasidenda->AdvancedSearch->Save();

		// Field titipan
		$this->titipan->AdvancedSearch->SearchValue = @$filter["x_titipan"];
		$this->titipan->AdvancedSearch->SearchOperator = @$filter["z_titipan"];
		$this->titipan->AdvancedSearch->SearchCondition = @$filter["v_titipan"];
		$this->titipan->AdvancedSearch->SearchValue2 = @$filter["y_titipan"];
		$this->titipan->AdvancedSearch->SearchOperator2 = @$filter["w_titipan"];
		$this->titipan->AdvancedSearch->Save();

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

		// Field terbilang
		$this->terbilang->AdvancedSearch->SearchValue = @$filter["x_terbilang"];
		$this->terbilang->AdvancedSearch->SearchOperator = @$filter["z_terbilang"];
		$this->terbilang->AdvancedSearch->SearchCondition = @$filter["v_terbilang"];
		$this->terbilang->AdvancedSearch->SearchValue2 = @$filter["y_terbilang"];
		$this->terbilang->AdvancedSearch->SearchOperator2 = @$filter["w_terbilang"];
		$this->terbilang->AdvancedSearch->Save();

		// Field petugas
		$this->petugas->AdvancedSearch->SearchValue = @$filter["x_petugas"];
		$this->petugas->AdvancedSearch->SearchOperator = @$filter["z_petugas"];
		$this->petugas->AdvancedSearch->SearchCondition = @$filter["v_petugas"];
		$this->petugas->AdvancedSearch->SearchValue2 = @$filter["y_petugas"];
		$this->petugas->AdvancedSearch->SearchOperator2 = @$filter["w_petugas"];
		$this->petugas->AdvancedSearch->Save();

		// Field pembayaran
		$this->pembayaran->AdvancedSearch->SearchValue = @$filter["x_pembayaran"];
		$this->pembayaran->AdvancedSearch->SearchOperator = @$filter["z_pembayaran"];
		$this->pembayaran->AdvancedSearch->SearchCondition = @$filter["v_pembayaran"];
		$this->pembayaran->AdvancedSearch->SearchValue2 = @$filter["y_pembayaran"];
		$this->pembayaran->AdvancedSearch->SearchOperator2 = @$filter["w_pembayaran"];
		$this->pembayaran->AdvancedSearch->Save();

		// Field bank
		$this->bank->AdvancedSearch->SearchValue = @$filter["x_bank"];
		$this->bank->AdvancedSearch->SearchOperator = @$filter["z_bank"];
		$this->bank->AdvancedSearch->SearchCondition = @$filter["v_bank"];
		$this->bank->AdvancedSearch->SearchValue2 = @$filter["y_bank"];
		$this->bank->AdvancedSearch->SearchOperator2 = @$filter["w_bank"];
		$this->bank->AdvancedSearch->Save();

		// Field atasnama
		$this->atasnama->AdvancedSearch->SearchValue = @$filter["x_atasnama"];
		$this->atasnama->AdvancedSearch->SearchOperator = @$filter["z_atasnama"];
		$this->atasnama->AdvancedSearch->SearchCondition = @$filter["v_atasnama"];
		$this->atasnama->AdvancedSearch->SearchValue2 = @$filter["y_atasnama"];
		$this->atasnama->AdvancedSearch->SearchOperator2 = @$filter["w_atasnama"];
		$this->atasnama->AdvancedSearch->Save();

		// Field tipe
		$this->tipe->AdvancedSearch->SearchValue = @$filter["x_tipe"];
		$this->tipe->AdvancedSearch->SearchOperator = @$filter["z_tipe"];
		$this->tipe->AdvancedSearch->SearchCondition = @$filter["v_tipe"];
		$this->tipe->AdvancedSearch->SearchValue2 = @$filter["y_tipe"];
		$this->tipe->AdvancedSearch->SearchOperator2 = @$filter["w_tipe"];
		$this->tipe->AdvancedSearch->Save();

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
		$this->BuildSearchSql($sWhere, $this->transaksi, $Default, FALSE); // transaksi
		$this->BuildSearchSql($sWhere, $this->referensi, $Default, FALSE); // referensi
		$this->BuildSearchSql($sWhere, $this->anggota, $Default, FALSE); // anggota
		$this->BuildSearchSql($sWhere, $this->namaanggota, $Default, FALSE); // namaanggota
		$this->BuildSearchSql($sWhere, $this->alamat, $Default, FALSE); // alamat
		$this->BuildSearchSql($sWhere, $this->pekerjaan, $Default, FALSE); // pekerjaan
		$this->BuildSearchSql($sWhere, $this->telepon, $Default, FALSE); // telepon
		$this->BuildSearchSql($sWhere, $this->hp, $Default, FALSE); // hp
		$this->BuildSearchSql($sWhere, $this->fax, $Default, FALSE); // fax
		$this->BuildSearchSql($sWhere, $this->_email, $Default, FALSE); // email
		$this->BuildSearchSql($sWhere, $this->website, $Default, FALSE); // website
		$this->BuildSearchSql($sWhere, $this->jenisanggota, $Default, FALSE); // jenisanggota
		$this->BuildSearchSql($sWhere, $this->model, $Default, FALSE); // model
		$this->BuildSearchSql($sWhere, $this->jenispinjaman, $Default, FALSE); // jenispinjaman
		$this->BuildSearchSql($sWhere, $this->jenisbunga, $Default, FALSE); // jenisbunga
		$this->BuildSearchSql($sWhere, $this->angsuran, $Default, FALSE); // angsuran
		$this->BuildSearchSql($sWhere, $this->masaangsuran, $Default, FALSE); // masaangsuran
		$this->BuildSearchSql($sWhere, $this->jatuhtempo, $Default, FALSE); // jatuhtempo
		$this->BuildSearchSql($sWhere, $this->dispensasidenda, $Default, FALSE); // dispensasidenda
		$this->BuildSearchSql($sWhere, $this->titipan, $Default, FALSE); // titipan
		$this->BuildSearchSql($sWhere, $this->bayartitipan, $Default, FALSE); // bayartitipan
		$this->BuildSearchSql($sWhere, $this->bayartitipanauto, $Default, FALSE); // bayartitipanauto
		$this->BuildSearchSql($sWhere, $this->terbilang, $Default, FALSE); // terbilang
		$this->BuildSearchSql($sWhere, $this->petugas, $Default, FALSE); // petugas
		$this->BuildSearchSql($sWhere, $this->pembayaran, $Default, FALSE); // pembayaran
		$this->BuildSearchSql($sWhere, $this->bank, $Default, FALSE); // bank
		$this->BuildSearchSql($sWhere, $this->atasnama, $Default, FALSE); // atasnama
		$this->BuildSearchSql($sWhere, $this->tipe, $Default, FALSE); // tipe
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
			$this->transaksi->AdvancedSearch->Save(); // transaksi
			$this->referensi->AdvancedSearch->Save(); // referensi
			$this->anggota->AdvancedSearch->Save(); // anggota
			$this->namaanggota->AdvancedSearch->Save(); // namaanggota
			$this->alamat->AdvancedSearch->Save(); // alamat
			$this->pekerjaan->AdvancedSearch->Save(); // pekerjaan
			$this->telepon->AdvancedSearch->Save(); // telepon
			$this->hp->AdvancedSearch->Save(); // hp
			$this->fax->AdvancedSearch->Save(); // fax
			$this->_email->AdvancedSearch->Save(); // email
			$this->website->AdvancedSearch->Save(); // website
			$this->jenisanggota->AdvancedSearch->Save(); // jenisanggota
			$this->model->AdvancedSearch->Save(); // model
			$this->jenispinjaman->AdvancedSearch->Save(); // jenispinjaman
			$this->jenisbunga->AdvancedSearch->Save(); // jenisbunga
			$this->angsuran->AdvancedSearch->Save(); // angsuran
			$this->masaangsuran->AdvancedSearch->Save(); // masaangsuran
			$this->jatuhtempo->AdvancedSearch->Save(); // jatuhtempo
			$this->dispensasidenda->AdvancedSearch->Save(); // dispensasidenda
			$this->titipan->AdvancedSearch->Save(); // titipan
			$this->bayartitipan->AdvancedSearch->Save(); // bayartitipan
			$this->bayartitipanauto->AdvancedSearch->Save(); // bayartitipanauto
			$this->terbilang->AdvancedSearch->Save(); // terbilang
			$this->petugas->AdvancedSearch->Save(); // petugas
			$this->pembayaran->AdvancedSearch->Save(); // pembayaran
			$this->bank->AdvancedSearch->Save(); // bank
			$this->atasnama->AdvancedSearch->Save(); // atasnama
			$this->tipe->AdvancedSearch->Save(); // tipe
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
		$this->BuildBasicSearchSQL($sWhere, $this->anggota, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->namaanggota, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->alamat, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->pekerjaan, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->telepon, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->hp, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->fax, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->_email, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->website, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->jenisanggota, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->model, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->jenispinjaman, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->jenisbunga, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->masaangsuran, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->terbilang, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->petugas, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->pembayaran, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->bank, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->atasnama, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->tipe, $arKeywords, $type);
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
		if ($this->transaksi->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->referensi->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->anggota->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->namaanggota->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->alamat->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->pekerjaan->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->telepon->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->hp->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fax->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_email->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->website->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->jenisanggota->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->model->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->jenispinjaman->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->jenisbunga->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->angsuran->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->masaangsuran->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->jatuhtempo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dispensasidenda->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->titipan->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->bayartitipan->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->bayartitipanauto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->terbilang->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->petugas->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->pembayaran->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->bank->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->atasnama->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->tipe->AdvancedSearch->IssetSession())
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
		$this->transaksi->AdvancedSearch->UnsetSession();
		$this->referensi->AdvancedSearch->UnsetSession();
		$this->anggota->AdvancedSearch->UnsetSession();
		$this->namaanggota->AdvancedSearch->UnsetSession();
		$this->alamat->AdvancedSearch->UnsetSession();
		$this->pekerjaan->AdvancedSearch->UnsetSession();
		$this->telepon->AdvancedSearch->UnsetSession();
		$this->hp->AdvancedSearch->UnsetSession();
		$this->fax->AdvancedSearch->UnsetSession();
		$this->_email->AdvancedSearch->UnsetSession();
		$this->website->AdvancedSearch->UnsetSession();
		$this->jenisanggota->AdvancedSearch->UnsetSession();
		$this->model->AdvancedSearch->UnsetSession();
		$this->jenispinjaman->AdvancedSearch->UnsetSession();
		$this->jenisbunga->AdvancedSearch->UnsetSession();
		$this->angsuran->AdvancedSearch->UnsetSession();
		$this->masaangsuran->AdvancedSearch->UnsetSession();
		$this->jatuhtempo->AdvancedSearch->UnsetSession();
		$this->dispensasidenda->AdvancedSearch->UnsetSession();
		$this->titipan->AdvancedSearch->UnsetSession();
		$this->bayartitipan->AdvancedSearch->UnsetSession();
		$this->bayartitipanauto->AdvancedSearch->UnsetSession();
		$this->terbilang->AdvancedSearch->UnsetSession();
		$this->petugas->AdvancedSearch->UnsetSession();
		$this->pembayaran->AdvancedSearch->UnsetSession();
		$this->bank->AdvancedSearch->UnsetSession();
		$this->atasnama->AdvancedSearch->UnsetSession();
		$this->tipe->AdvancedSearch->UnsetSession();
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
		$this->transaksi->AdvancedSearch->Load();
		$this->referensi->AdvancedSearch->Load();
		$this->anggota->AdvancedSearch->Load();
		$this->namaanggota->AdvancedSearch->Load();
		$this->alamat->AdvancedSearch->Load();
		$this->pekerjaan->AdvancedSearch->Load();
		$this->telepon->AdvancedSearch->Load();
		$this->hp->AdvancedSearch->Load();
		$this->fax->AdvancedSearch->Load();
		$this->_email->AdvancedSearch->Load();
		$this->website->AdvancedSearch->Load();
		$this->jenisanggota->AdvancedSearch->Load();
		$this->model->AdvancedSearch->Load();
		$this->jenispinjaman->AdvancedSearch->Load();
		$this->jenisbunga->AdvancedSearch->Load();
		$this->angsuran->AdvancedSearch->Load();
		$this->masaangsuran->AdvancedSearch->Load();
		$this->jatuhtempo->AdvancedSearch->Load();
		$this->dispensasidenda->AdvancedSearch->Load();
		$this->titipan->AdvancedSearch->Load();
		$this->bayartitipan->AdvancedSearch->Load();
		$this->bayartitipanauto->AdvancedSearch->Load();
		$this->terbilang->AdvancedSearch->Load();
		$this->petugas->AdvancedSearch->Load();
		$this->pembayaran->AdvancedSearch->Load();
		$this->bank->AdvancedSearch->Load();
		$this->atasnama->AdvancedSearch->Load();
		$this->tipe->AdvancedSearch->Load();
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
			$this->UpdateSort($this->transaksi); // transaksi
			$this->UpdateSort($this->referensi); // referensi
			$this->UpdateSort($this->anggota); // anggota
			$this->UpdateSort($this->namaanggota); // namaanggota
			$this->UpdateSort($this->alamat); // alamat
			$this->UpdateSort($this->pekerjaan); // pekerjaan
			$this->UpdateSort($this->telepon); // telepon
			$this->UpdateSort($this->hp); // hp
			$this->UpdateSort($this->fax); // fax
			$this->UpdateSort($this->_email); // email
			$this->UpdateSort($this->website); // website
			$this->UpdateSort($this->jenisanggota); // jenisanggota
			$this->UpdateSort($this->model); // model
			$this->UpdateSort($this->jenispinjaman); // jenispinjaman
			$this->UpdateSort($this->jenisbunga); // jenisbunga
			$this->UpdateSort($this->angsuran); // angsuran
			$this->UpdateSort($this->masaangsuran); // masaangsuran
			$this->UpdateSort($this->jatuhtempo); // jatuhtempo
			$this->UpdateSort($this->dispensasidenda); // dispensasidenda
			$this->UpdateSort($this->titipan); // titipan
			$this->UpdateSort($this->bayartitipan); // bayartitipan
			$this->UpdateSort($this->bayartitipanauto); // bayartitipanauto
			$this->UpdateSort($this->terbilang); // terbilang
			$this->UpdateSort($this->petugas); // petugas
			$this->UpdateSort($this->pembayaran); // pembayaran
			$this->UpdateSort($this->bank); // bank
			$this->UpdateSort($this->atasnama); // atasnama
			$this->UpdateSort($this->tipe); // tipe
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
				$this->transaksi->setSort("");
				$this->referensi->setSort("");
				$this->anggota->setSort("");
				$this->namaanggota->setSort("");
				$this->alamat->setSort("");
				$this->pekerjaan->setSort("");
				$this->telepon->setSort("");
				$this->hp->setSort("");
				$this->fax->setSort("");
				$this->_email->setSort("");
				$this->website->setSort("");
				$this->jenisanggota->setSort("");
				$this->model->setSort("");
				$this->jenispinjaman->setSort("");
				$this->jenisbunga->setSort("");
				$this->angsuran->setSort("");
				$this->masaangsuran->setSort("");
				$this->jatuhtempo->setSort("");
				$this->dispensasidenda->setSort("");
				$this->titipan->setSort("");
				$this->bayartitipan->setSort("");
				$this->bayartitipanauto->setSort("");
				$this->terbilang->setSort("");
				$this->petugas->setSort("");
				$this->pembayaran->setSort("");
				$this->bank->setSort("");
				$this->atasnama->setSort("");
				$this->tipe->setSort("");
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id->CurrentValue . $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"] . $this->titipan->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"ftbayartitipanlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"ftbayartitipanlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.ftbayartitipanlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"ftbayartitipanlistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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

		// transaksi
		$this->transaksi->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_transaksi"]);
		if ($this->transaksi->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->transaksi->AdvancedSearch->SearchOperator = @$_GET["z_transaksi"];

		// referensi
		$this->referensi->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_referensi"]);
		if ($this->referensi->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->referensi->AdvancedSearch->SearchOperator = @$_GET["z_referensi"];

		// anggota
		$this->anggota->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_anggota"]);
		if ($this->anggota->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->anggota->AdvancedSearch->SearchOperator = @$_GET["z_anggota"];

		// namaanggota
		$this->namaanggota->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_namaanggota"]);
		if ($this->namaanggota->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->namaanggota->AdvancedSearch->SearchOperator = @$_GET["z_namaanggota"];

		// alamat
		$this->alamat->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_alamat"]);
		if ($this->alamat->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->alamat->AdvancedSearch->SearchOperator = @$_GET["z_alamat"];

		// pekerjaan
		$this->pekerjaan->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_pekerjaan"]);
		if ($this->pekerjaan->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->pekerjaan->AdvancedSearch->SearchOperator = @$_GET["z_pekerjaan"];

		// telepon
		$this->telepon->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_telepon"]);
		if ($this->telepon->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->telepon->AdvancedSearch->SearchOperator = @$_GET["z_telepon"];

		// hp
		$this->hp->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_hp"]);
		if ($this->hp->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->hp->AdvancedSearch->SearchOperator = @$_GET["z_hp"];

		// fax
		$this->fax->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fax"]);
		if ($this->fax->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fax->AdvancedSearch->SearchOperator = @$_GET["z_fax"];

		// email
		$this->_email->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__email"]);
		if ($this->_email->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_email->AdvancedSearch->SearchOperator = @$_GET["z__email"];

		// website
		$this->website->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_website"]);
		if ($this->website->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->website->AdvancedSearch->SearchOperator = @$_GET["z_website"];

		// jenisanggota
		$this->jenisanggota->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_jenisanggota"]);
		if ($this->jenisanggota->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->jenisanggota->AdvancedSearch->SearchOperator = @$_GET["z_jenisanggota"];

		// model
		$this->model->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_model"]);
		if ($this->model->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->model->AdvancedSearch->SearchOperator = @$_GET["z_model"];

		// jenispinjaman
		$this->jenispinjaman->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_jenispinjaman"]);
		if ($this->jenispinjaman->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->jenispinjaman->AdvancedSearch->SearchOperator = @$_GET["z_jenispinjaman"];

		// jenisbunga
		$this->jenisbunga->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_jenisbunga"]);
		if ($this->jenisbunga->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->jenisbunga->AdvancedSearch->SearchOperator = @$_GET["z_jenisbunga"];

		// angsuran
		$this->angsuran->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_angsuran"]);
		if ($this->angsuran->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->angsuran->AdvancedSearch->SearchOperator = @$_GET["z_angsuran"];

		// masaangsuran
		$this->masaangsuran->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_masaangsuran"]);
		if ($this->masaangsuran->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->masaangsuran->AdvancedSearch->SearchOperator = @$_GET["z_masaangsuran"];

		// jatuhtempo
		$this->jatuhtempo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_jatuhtempo"]);
		if ($this->jatuhtempo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->jatuhtempo->AdvancedSearch->SearchOperator = @$_GET["z_jatuhtempo"];

		// dispensasidenda
		$this->dispensasidenda->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dispensasidenda"]);
		if ($this->dispensasidenda->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dispensasidenda->AdvancedSearch->SearchOperator = @$_GET["z_dispensasidenda"];

		// titipan
		$this->titipan->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_titipan"]);
		if ($this->titipan->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->titipan->AdvancedSearch->SearchOperator = @$_GET["z_titipan"];

		// bayartitipan
		$this->bayartitipan->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_bayartitipan"]);
		if ($this->bayartitipan->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->bayartitipan->AdvancedSearch->SearchOperator = @$_GET["z_bayartitipan"];

		// bayartitipanauto
		$this->bayartitipanauto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_bayartitipanauto"]);
		if ($this->bayartitipanauto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->bayartitipanauto->AdvancedSearch->SearchOperator = @$_GET["z_bayartitipanauto"];

		// terbilang
		$this->terbilang->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_terbilang"]);
		if ($this->terbilang->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->terbilang->AdvancedSearch->SearchOperator = @$_GET["z_terbilang"];

		// petugas
		$this->petugas->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_petugas"]);
		if ($this->petugas->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->petugas->AdvancedSearch->SearchOperator = @$_GET["z_petugas"];

		// pembayaran
		$this->pembayaran->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_pembayaran"]);
		if ($this->pembayaran->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->pembayaran->AdvancedSearch->SearchOperator = @$_GET["z_pembayaran"];

		// bank
		$this->bank->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_bank"]);
		if ($this->bank->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->bank->AdvancedSearch->SearchOperator = @$_GET["z_bank"];

		// atasnama
		$this->atasnama->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_atasnama"]);
		if ($this->atasnama->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->atasnama->AdvancedSearch->SearchOperator = @$_GET["z_atasnama"];

		// tipe
		$this->tipe->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_tipe"]);
		if ($this->tipe->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->tipe->AdvancedSearch->SearchOperator = @$_GET["z_tipe"];

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
		$this->transaksi->setDbValue($rs->fields('transaksi'));
		$this->referensi->setDbValue($rs->fields('referensi'));
		$this->anggota->setDbValue($rs->fields('anggota'));
		$this->namaanggota->setDbValue($rs->fields('namaanggota'));
		$this->alamat->setDbValue($rs->fields('alamat'));
		$this->pekerjaan->setDbValue($rs->fields('pekerjaan'));
		$this->telepon->setDbValue($rs->fields('telepon'));
		$this->hp->setDbValue($rs->fields('hp'));
		$this->fax->setDbValue($rs->fields('fax'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->website->setDbValue($rs->fields('website'));
		$this->jenisanggota->setDbValue($rs->fields('jenisanggota'));
		$this->model->setDbValue($rs->fields('model'));
		$this->jenispinjaman->setDbValue($rs->fields('jenispinjaman'));
		$this->jenisbunga->setDbValue($rs->fields('jenisbunga'));
		$this->angsuran->setDbValue($rs->fields('angsuran'));
		$this->masaangsuran->setDbValue($rs->fields('masaangsuran'));
		$this->jatuhtempo->setDbValue($rs->fields('jatuhtempo'));
		$this->dispensasidenda->setDbValue($rs->fields('dispensasidenda'));
		$this->titipan->setDbValue($rs->fields('titipan'));
		$this->bayartitipan->setDbValue($rs->fields('bayartitipan'));
		$this->bayartitipanauto->setDbValue($rs->fields('bayartitipanauto'));
		$this->terbilang->setDbValue($rs->fields('terbilang'));
		$this->petugas->setDbValue($rs->fields('petugas'));
		$this->pembayaran->setDbValue($rs->fields('pembayaran'));
		$this->bank->setDbValue($rs->fields('bank'));
		$this->atasnama->setDbValue($rs->fields('atasnama'));
		$this->tipe->setDbValue($rs->fields('tipe'));
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
		$this->transaksi->DbValue = $row['transaksi'];
		$this->referensi->DbValue = $row['referensi'];
		$this->anggota->DbValue = $row['anggota'];
		$this->namaanggota->DbValue = $row['namaanggota'];
		$this->alamat->DbValue = $row['alamat'];
		$this->pekerjaan->DbValue = $row['pekerjaan'];
		$this->telepon->DbValue = $row['telepon'];
		$this->hp->DbValue = $row['hp'];
		$this->fax->DbValue = $row['fax'];
		$this->_email->DbValue = $row['email'];
		$this->website->DbValue = $row['website'];
		$this->jenisanggota->DbValue = $row['jenisanggota'];
		$this->model->DbValue = $row['model'];
		$this->jenispinjaman->DbValue = $row['jenispinjaman'];
		$this->jenisbunga->DbValue = $row['jenisbunga'];
		$this->angsuran->DbValue = $row['angsuran'];
		$this->masaangsuran->DbValue = $row['masaangsuran'];
		$this->jatuhtempo->DbValue = $row['jatuhtempo'];
		$this->dispensasidenda->DbValue = $row['dispensasidenda'];
		$this->titipan->DbValue = $row['titipan'];
		$this->bayartitipan->DbValue = $row['bayartitipan'];
		$this->bayartitipanauto->DbValue = $row['bayartitipanauto'];
		$this->terbilang->DbValue = $row['terbilang'];
		$this->petugas->DbValue = $row['petugas'];
		$this->pembayaran->DbValue = $row['pembayaran'];
		$this->bank->DbValue = $row['bank'];
		$this->atasnama->DbValue = $row['atasnama'];
		$this->tipe->DbValue = $row['tipe'];
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
		if (strval($this->getKey("titipan")) <> "")
			$this->titipan->CurrentValue = $this->getKey("titipan"); // titipan
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
		if ($this->bayartitipan->FormValue == $this->bayartitipan->CurrentValue && is_numeric(ew_StrToFloat($this->bayartitipan->CurrentValue)))
			$this->bayartitipan->CurrentValue = ew_StrToFloat($this->bayartitipan->CurrentValue);

		// Convert decimal values if posted back
		if ($this->bayartitipanauto->FormValue == $this->bayartitipanauto->CurrentValue && is_numeric(ew_StrToFloat($this->bayartitipanauto->CurrentValue)))
			$this->bayartitipanauto->CurrentValue = ew_StrToFloat($this->bayartitipanauto->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// tanggal
		// periode
		// id
		// transaksi
		// referensi
		// anggota
		// namaanggota
		// alamat
		// pekerjaan
		// telepon
		// hp
		// fax
		// email
		// website
		// jenisanggota
		// model
		// jenispinjaman
		// jenisbunga
		// angsuran
		// masaangsuran
		// jatuhtempo
		// dispensasidenda
		// titipan
		// bayartitipan
		// bayartitipanauto
		// terbilang
		// petugas
		// pembayaran
		// bank
		// atasnama
		// tipe
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

		// transaksi
		$this->transaksi->ViewValue = $this->transaksi->CurrentValue;
		$this->transaksi->ViewCustomAttributes = "";

		// referensi
		$this->referensi->ViewValue = $this->referensi->CurrentValue;
		$this->referensi->ViewCustomAttributes = "";

		// anggota
		$this->anggota->ViewValue = $this->anggota->CurrentValue;
		$this->anggota->ViewCustomAttributes = "";

		// namaanggota
		$this->namaanggota->ViewValue = $this->namaanggota->CurrentValue;
		$this->namaanggota->ViewCustomAttributes = "";

		// alamat
		$this->alamat->ViewValue = $this->alamat->CurrentValue;
		$this->alamat->ViewCustomAttributes = "";

		// pekerjaan
		$this->pekerjaan->ViewValue = $this->pekerjaan->CurrentValue;
		$this->pekerjaan->ViewCustomAttributes = "";

		// telepon
		$this->telepon->ViewValue = $this->telepon->CurrentValue;
		$this->telepon->ViewCustomAttributes = "";

		// hp
		$this->hp->ViewValue = $this->hp->CurrentValue;
		$this->hp->ViewCustomAttributes = "";

		// fax
		$this->fax->ViewValue = $this->fax->CurrentValue;
		$this->fax->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// website
		$this->website->ViewValue = $this->website->CurrentValue;
		$this->website->ViewCustomAttributes = "";

		// jenisanggota
		$this->jenisanggota->ViewValue = $this->jenisanggota->CurrentValue;
		$this->jenisanggota->ViewCustomAttributes = "";

		// model
		$this->model->ViewValue = $this->model->CurrentValue;
		$this->model->ViewCustomAttributes = "";

		// jenispinjaman
		$this->jenispinjaman->ViewValue = $this->jenispinjaman->CurrentValue;
		$this->jenispinjaman->ViewCustomAttributes = "";

		// jenisbunga
		$this->jenisbunga->ViewValue = $this->jenisbunga->CurrentValue;
		$this->jenisbunga->ViewCustomAttributes = "";

		// angsuran
		$this->angsuran->ViewValue = $this->angsuran->CurrentValue;
		$this->angsuran->ViewCustomAttributes = "";

		// masaangsuran
		$this->masaangsuran->ViewValue = $this->masaangsuran->CurrentValue;
		$this->masaangsuran->ViewCustomAttributes = "";

		// jatuhtempo
		$this->jatuhtempo->ViewValue = $this->jatuhtempo->CurrentValue;
		$this->jatuhtempo->ViewValue = ew_FormatDateTime($this->jatuhtempo->ViewValue, 0);
		$this->jatuhtempo->ViewCustomAttributes = "";

		// dispensasidenda
		$this->dispensasidenda->ViewValue = $this->dispensasidenda->CurrentValue;
		$this->dispensasidenda->ViewCustomAttributes = "";

		// titipan
		$this->titipan->ViewValue = $this->titipan->CurrentValue;
		$this->titipan->ViewCustomAttributes = "";

		// bayartitipan
		$this->bayartitipan->ViewValue = $this->bayartitipan->CurrentValue;
		$this->bayartitipan->ViewCustomAttributes = "";

		// bayartitipanauto
		$this->bayartitipanauto->ViewValue = $this->bayartitipanauto->CurrentValue;
		$this->bayartitipanauto->ViewCustomAttributes = "";

		// terbilang
		$this->terbilang->ViewValue = $this->terbilang->CurrentValue;
		$this->terbilang->ViewCustomAttributes = "";

		// petugas
		$this->petugas->ViewValue = $this->petugas->CurrentValue;
		$this->petugas->ViewCustomAttributes = "";

		// pembayaran
		$this->pembayaran->ViewValue = $this->pembayaran->CurrentValue;
		$this->pembayaran->ViewCustomAttributes = "";

		// bank
		$this->bank->ViewValue = $this->bank->CurrentValue;
		$this->bank->ViewCustomAttributes = "";

		// atasnama
		$this->atasnama->ViewValue = $this->atasnama->CurrentValue;
		$this->atasnama->ViewCustomAttributes = "";

		// tipe
		$this->tipe->ViewValue = $this->tipe->CurrentValue;
		$this->tipe->ViewCustomAttributes = "";

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

			// transaksi
			$this->transaksi->LinkCustomAttributes = "";
			$this->transaksi->HrefValue = "";
			$this->transaksi->TooltipValue = "";

			// referensi
			$this->referensi->LinkCustomAttributes = "";
			$this->referensi->HrefValue = "";
			$this->referensi->TooltipValue = "";

			// anggota
			$this->anggota->LinkCustomAttributes = "";
			$this->anggota->HrefValue = "";
			$this->anggota->TooltipValue = "";

			// namaanggota
			$this->namaanggota->LinkCustomAttributes = "";
			$this->namaanggota->HrefValue = "";
			$this->namaanggota->TooltipValue = "";

			// alamat
			$this->alamat->LinkCustomAttributes = "";
			$this->alamat->HrefValue = "";
			$this->alamat->TooltipValue = "";

			// pekerjaan
			$this->pekerjaan->LinkCustomAttributes = "";
			$this->pekerjaan->HrefValue = "";
			$this->pekerjaan->TooltipValue = "";

			// telepon
			$this->telepon->LinkCustomAttributes = "";
			$this->telepon->HrefValue = "";
			$this->telepon->TooltipValue = "";

			// hp
			$this->hp->LinkCustomAttributes = "";
			$this->hp->HrefValue = "";
			$this->hp->TooltipValue = "";

			// fax
			$this->fax->LinkCustomAttributes = "";
			$this->fax->HrefValue = "";
			$this->fax->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// website
			$this->website->LinkCustomAttributes = "";
			$this->website->HrefValue = "";
			$this->website->TooltipValue = "";

			// jenisanggota
			$this->jenisanggota->LinkCustomAttributes = "";
			$this->jenisanggota->HrefValue = "";
			$this->jenisanggota->TooltipValue = "";

			// model
			$this->model->LinkCustomAttributes = "";
			$this->model->HrefValue = "";
			$this->model->TooltipValue = "";

			// jenispinjaman
			$this->jenispinjaman->LinkCustomAttributes = "";
			$this->jenispinjaman->HrefValue = "";
			$this->jenispinjaman->TooltipValue = "";

			// jenisbunga
			$this->jenisbunga->LinkCustomAttributes = "";
			$this->jenisbunga->HrefValue = "";
			$this->jenisbunga->TooltipValue = "";

			// angsuran
			$this->angsuran->LinkCustomAttributes = "";
			$this->angsuran->HrefValue = "";
			$this->angsuran->TooltipValue = "";

			// masaangsuran
			$this->masaangsuran->LinkCustomAttributes = "";
			$this->masaangsuran->HrefValue = "";
			$this->masaangsuran->TooltipValue = "";

			// jatuhtempo
			$this->jatuhtempo->LinkCustomAttributes = "";
			$this->jatuhtempo->HrefValue = "";
			$this->jatuhtempo->TooltipValue = "";

			// dispensasidenda
			$this->dispensasidenda->LinkCustomAttributes = "";
			$this->dispensasidenda->HrefValue = "";
			$this->dispensasidenda->TooltipValue = "";

			// titipan
			$this->titipan->LinkCustomAttributes = "";
			$this->titipan->HrefValue = "";
			$this->titipan->TooltipValue = "";

			// bayartitipan
			$this->bayartitipan->LinkCustomAttributes = "";
			$this->bayartitipan->HrefValue = "";
			$this->bayartitipan->TooltipValue = "";

			// bayartitipanauto
			$this->bayartitipanauto->LinkCustomAttributes = "";
			$this->bayartitipanauto->HrefValue = "";
			$this->bayartitipanauto->TooltipValue = "";

			// terbilang
			$this->terbilang->LinkCustomAttributes = "";
			$this->terbilang->HrefValue = "";
			$this->terbilang->TooltipValue = "";

			// petugas
			$this->petugas->LinkCustomAttributes = "";
			$this->petugas->HrefValue = "";
			$this->petugas->TooltipValue = "";

			// pembayaran
			$this->pembayaran->LinkCustomAttributes = "";
			$this->pembayaran->HrefValue = "";
			$this->pembayaran->TooltipValue = "";

			// bank
			$this->bank->LinkCustomAttributes = "";
			$this->bank->HrefValue = "";
			$this->bank->TooltipValue = "";

			// atasnama
			$this->atasnama->LinkCustomAttributes = "";
			$this->atasnama->HrefValue = "";
			$this->atasnama->TooltipValue = "";

			// tipe
			$this->tipe->LinkCustomAttributes = "";
			$this->tipe->HrefValue = "";
			$this->tipe->TooltipValue = "";

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

			// anggota
			$this->anggota->EditAttrs["class"] = "form-control";
			$this->anggota->EditCustomAttributes = "";
			$this->anggota->EditValue = ew_HtmlEncode($this->anggota->AdvancedSearch->SearchValue);
			$this->anggota->PlaceHolder = ew_RemoveHtml($this->anggota->FldCaption());

			// namaanggota
			$this->namaanggota->EditAttrs["class"] = "form-control";
			$this->namaanggota->EditCustomAttributes = "";
			$this->namaanggota->EditValue = ew_HtmlEncode($this->namaanggota->AdvancedSearch->SearchValue);
			$this->namaanggota->PlaceHolder = ew_RemoveHtml($this->namaanggota->FldCaption());

			// alamat
			$this->alamat->EditAttrs["class"] = "form-control";
			$this->alamat->EditCustomAttributes = "";
			$this->alamat->EditValue = ew_HtmlEncode($this->alamat->AdvancedSearch->SearchValue);
			$this->alamat->PlaceHolder = ew_RemoveHtml($this->alamat->FldCaption());

			// pekerjaan
			$this->pekerjaan->EditAttrs["class"] = "form-control";
			$this->pekerjaan->EditCustomAttributes = "";
			$this->pekerjaan->EditValue = ew_HtmlEncode($this->pekerjaan->AdvancedSearch->SearchValue);
			$this->pekerjaan->PlaceHolder = ew_RemoveHtml($this->pekerjaan->FldCaption());

			// telepon
			$this->telepon->EditAttrs["class"] = "form-control";
			$this->telepon->EditCustomAttributes = "";
			$this->telepon->EditValue = ew_HtmlEncode($this->telepon->AdvancedSearch->SearchValue);
			$this->telepon->PlaceHolder = ew_RemoveHtml($this->telepon->FldCaption());

			// hp
			$this->hp->EditAttrs["class"] = "form-control";
			$this->hp->EditCustomAttributes = "";
			$this->hp->EditValue = ew_HtmlEncode($this->hp->AdvancedSearch->SearchValue);
			$this->hp->PlaceHolder = ew_RemoveHtml($this->hp->FldCaption());

			// fax
			$this->fax->EditAttrs["class"] = "form-control";
			$this->fax->EditCustomAttributes = "";
			$this->fax->EditValue = ew_HtmlEncode($this->fax->AdvancedSearch->SearchValue);
			$this->fax->PlaceHolder = ew_RemoveHtml($this->fax->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->AdvancedSearch->SearchValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// website
			$this->website->EditAttrs["class"] = "form-control";
			$this->website->EditCustomAttributes = "";
			$this->website->EditValue = ew_HtmlEncode($this->website->AdvancedSearch->SearchValue);
			$this->website->PlaceHolder = ew_RemoveHtml($this->website->FldCaption());

			// jenisanggota
			$this->jenisanggota->EditAttrs["class"] = "form-control";
			$this->jenisanggota->EditCustomAttributes = "";
			$this->jenisanggota->EditValue = ew_HtmlEncode($this->jenisanggota->AdvancedSearch->SearchValue);
			$this->jenisanggota->PlaceHolder = ew_RemoveHtml($this->jenisanggota->FldCaption());

			// model
			$this->model->EditAttrs["class"] = "form-control";
			$this->model->EditCustomAttributes = "";
			$this->model->EditValue = ew_HtmlEncode($this->model->AdvancedSearch->SearchValue);
			$this->model->PlaceHolder = ew_RemoveHtml($this->model->FldCaption());

			// jenispinjaman
			$this->jenispinjaman->EditAttrs["class"] = "form-control";
			$this->jenispinjaman->EditCustomAttributes = "";
			$this->jenispinjaman->EditValue = ew_HtmlEncode($this->jenispinjaman->AdvancedSearch->SearchValue);
			$this->jenispinjaman->PlaceHolder = ew_RemoveHtml($this->jenispinjaman->FldCaption());

			// jenisbunga
			$this->jenisbunga->EditAttrs["class"] = "form-control";
			$this->jenisbunga->EditCustomAttributes = "";
			$this->jenisbunga->EditValue = ew_HtmlEncode($this->jenisbunga->AdvancedSearch->SearchValue);
			$this->jenisbunga->PlaceHolder = ew_RemoveHtml($this->jenisbunga->FldCaption());

			// angsuran
			$this->angsuran->EditAttrs["class"] = "form-control";
			$this->angsuran->EditCustomAttributes = "";
			$this->angsuran->EditValue = ew_HtmlEncode($this->angsuran->AdvancedSearch->SearchValue);
			$this->angsuran->PlaceHolder = ew_RemoveHtml($this->angsuran->FldCaption());

			// masaangsuran
			$this->masaangsuran->EditAttrs["class"] = "form-control";
			$this->masaangsuran->EditCustomAttributes = "";
			$this->masaangsuran->EditValue = ew_HtmlEncode($this->masaangsuran->AdvancedSearch->SearchValue);
			$this->masaangsuran->PlaceHolder = ew_RemoveHtml($this->masaangsuran->FldCaption());

			// jatuhtempo
			$this->jatuhtempo->EditAttrs["class"] = "form-control";
			$this->jatuhtempo->EditCustomAttributes = "";
			$this->jatuhtempo->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->jatuhtempo->AdvancedSearch->SearchValue, 0), 8));
			$this->jatuhtempo->PlaceHolder = ew_RemoveHtml($this->jatuhtempo->FldCaption());

			// dispensasidenda
			$this->dispensasidenda->EditAttrs["class"] = "form-control";
			$this->dispensasidenda->EditCustomAttributes = "";
			$this->dispensasidenda->EditValue = ew_HtmlEncode($this->dispensasidenda->AdvancedSearch->SearchValue);
			$this->dispensasidenda->PlaceHolder = ew_RemoveHtml($this->dispensasidenda->FldCaption());

			// titipan
			$this->titipan->EditAttrs["class"] = "form-control";
			$this->titipan->EditCustomAttributes = "";
			$this->titipan->EditValue = ew_HtmlEncode($this->titipan->AdvancedSearch->SearchValue);
			$this->titipan->PlaceHolder = ew_RemoveHtml($this->titipan->FldCaption());

			// bayartitipan
			$this->bayartitipan->EditAttrs["class"] = "form-control";
			$this->bayartitipan->EditCustomAttributes = "";
			$this->bayartitipan->EditValue = ew_HtmlEncode($this->bayartitipan->AdvancedSearch->SearchValue);
			$this->bayartitipan->PlaceHolder = ew_RemoveHtml($this->bayartitipan->FldCaption());

			// bayartitipanauto
			$this->bayartitipanauto->EditAttrs["class"] = "form-control";
			$this->bayartitipanauto->EditCustomAttributes = "";
			$this->bayartitipanauto->EditValue = ew_HtmlEncode($this->bayartitipanauto->AdvancedSearch->SearchValue);
			$this->bayartitipanauto->PlaceHolder = ew_RemoveHtml($this->bayartitipanauto->FldCaption());

			// terbilang
			$this->terbilang->EditAttrs["class"] = "form-control";
			$this->terbilang->EditCustomAttributes = "";
			$this->terbilang->EditValue = ew_HtmlEncode($this->terbilang->AdvancedSearch->SearchValue);
			$this->terbilang->PlaceHolder = ew_RemoveHtml($this->terbilang->FldCaption());

			// petugas
			$this->petugas->EditAttrs["class"] = "form-control";
			$this->petugas->EditCustomAttributes = "";
			$this->petugas->EditValue = ew_HtmlEncode($this->petugas->AdvancedSearch->SearchValue);
			$this->petugas->PlaceHolder = ew_RemoveHtml($this->petugas->FldCaption());

			// pembayaran
			$this->pembayaran->EditAttrs["class"] = "form-control";
			$this->pembayaran->EditCustomAttributes = "";
			$this->pembayaran->EditValue = ew_HtmlEncode($this->pembayaran->AdvancedSearch->SearchValue);
			$this->pembayaran->PlaceHolder = ew_RemoveHtml($this->pembayaran->FldCaption());

			// bank
			$this->bank->EditAttrs["class"] = "form-control";
			$this->bank->EditCustomAttributes = "";
			$this->bank->EditValue = ew_HtmlEncode($this->bank->AdvancedSearch->SearchValue);
			$this->bank->PlaceHolder = ew_RemoveHtml($this->bank->FldCaption());

			// atasnama
			$this->atasnama->EditAttrs["class"] = "form-control";
			$this->atasnama->EditCustomAttributes = "";
			$this->atasnama->EditValue = ew_HtmlEncode($this->atasnama->AdvancedSearch->SearchValue);
			$this->atasnama->PlaceHolder = ew_RemoveHtml($this->atasnama->FldCaption());

			// tipe
			$this->tipe->EditAttrs["class"] = "form-control";
			$this->tipe->EditCustomAttributes = "";
			$this->tipe->EditValue = ew_HtmlEncode($this->tipe->AdvancedSearch->SearchValue);
			$this->tipe->PlaceHolder = ew_RemoveHtml($this->tipe->FldCaption());

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
		$this->transaksi->AdvancedSearch->Load();
		$this->referensi->AdvancedSearch->Load();
		$this->anggota->AdvancedSearch->Load();
		$this->namaanggota->AdvancedSearch->Load();
		$this->alamat->AdvancedSearch->Load();
		$this->pekerjaan->AdvancedSearch->Load();
		$this->telepon->AdvancedSearch->Load();
		$this->hp->AdvancedSearch->Load();
		$this->fax->AdvancedSearch->Load();
		$this->_email->AdvancedSearch->Load();
		$this->website->AdvancedSearch->Load();
		$this->jenisanggota->AdvancedSearch->Load();
		$this->model->AdvancedSearch->Load();
		$this->jenispinjaman->AdvancedSearch->Load();
		$this->jenisbunga->AdvancedSearch->Load();
		$this->angsuran->AdvancedSearch->Load();
		$this->masaangsuran->AdvancedSearch->Load();
		$this->jatuhtempo->AdvancedSearch->Load();
		$this->dispensasidenda->AdvancedSearch->Load();
		$this->titipan->AdvancedSearch->Load();
		$this->bayartitipan->AdvancedSearch->Load();
		$this->bayartitipanauto->AdvancedSearch->Load();
		$this->terbilang->AdvancedSearch->Load();
		$this->petugas->AdvancedSearch->Load();
		$this->pembayaran->AdvancedSearch->Load();
		$this->bank->AdvancedSearch->Load();
		$this->atasnama->AdvancedSearch->Load();
		$this->tipe->AdvancedSearch->Load();
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
if (!isset($tbayartitipan_list)) $tbayartitipan_list = new ctbayartitipan_list();

// Page init
$tbayartitipan_list->Page_Init();

// Page main
$tbayartitipan_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tbayartitipan_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = ftbayartitipanlist = new ew_Form("ftbayartitipanlist", "list");
ftbayartitipanlist.FormKeyCountName = '<?php echo $tbayartitipan_list->FormKeyCountName ?>';

// Form_CustomValidate event
ftbayartitipanlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbayartitipanlist.ValidateRequired = true;
<?php } else { ?>
ftbayartitipanlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftbayartitipanlist.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftbayartitipanlist.Lists["x_active"].Options = <?php echo json_encode($tbayartitipan->active->Options()) ?>;

// Form object for search
var CurrentSearchForm = ftbayartitipanlistsrch = new ew_Form("ftbayartitipanlistsrch");

// Validate function for search
ftbayartitipanlistsrch.Validate = function(fobj) {
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
ftbayartitipanlistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbayartitipanlistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
ftbayartitipanlistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
ftbayartitipanlistsrch.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftbayartitipanlistsrch.Lists["x_active"].Options = <?php echo json_encode($tbayartitipan->active->Options()) ?>;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($tbayartitipan_list->TotalRecs > 0 && $tbayartitipan_list->ExportOptions->Visible()) { ?>
<?php $tbayartitipan_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($tbayartitipan_list->SearchOptions->Visible()) { ?>
<?php $tbayartitipan_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($tbayartitipan_list->FilterOptions->Visible()) { ?>
<?php $tbayartitipan_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $tbayartitipan_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($tbayartitipan_list->TotalRecs <= 0)
			$tbayartitipan_list->TotalRecs = $tbayartitipan->SelectRecordCount();
	} else {
		if (!$tbayartitipan_list->Recordset && ($tbayartitipan_list->Recordset = $tbayartitipan_list->LoadRecordset()))
			$tbayartitipan_list->TotalRecs = $tbayartitipan_list->Recordset->RecordCount();
	}
	$tbayartitipan_list->StartRec = 1;
	if ($tbayartitipan_list->DisplayRecs <= 0 || ($tbayartitipan->Export <> "" && $tbayartitipan->ExportAll)) // Display all records
		$tbayartitipan_list->DisplayRecs = $tbayartitipan_list->TotalRecs;
	if (!($tbayartitipan->Export <> "" && $tbayartitipan->ExportAll))
		$tbayartitipan_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$tbayartitipan_list->Recordset = $tbayartitipan_list->LoadRecordset($tbayartitipan_list->StartRec-1, $tbayartitipan_list->DisplayRecs);

	// Set no record found message
	if ($tbayartitipan->CurrentAction == "" && $tbayartitipan_list->TotalRecs == 0) {
		if ($tbayartitipan_list->SearchWhere == "0=101")
			$tbayartitipan_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$tbayartitipan_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$tbayartitipan_list->RenderOtherOptions();
?>
<?php if ($tbayartitipan->Export == "" && $tbayartitipan->CurrentAction == "") { ?>
<form name="ftbayartitipanlistsrch" id="ftbayartitipanlistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($tbayartitipan_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="ftbayartitipanlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="tbayartitipan">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$tbayartitipan_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$tbayartitipan->RowType = EW_ROWTYPE_SEARCH;

// Render row
$tbayartitipan->ResetAttrs();
$tbayartitipan_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($tbayartitipan->active->Visible) { // active ?>
	<div id="xsc_active" class="ewCell form-group">
		<label class="ewSearchCaption ewLabel"><?php echo $tbayartitipan->active->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_active" id="z_active" value="="></span>
		<span class="ewSearchField">
<div id="tp_x_active" class="ewTemplate"><input type="radio" data-table="tbayartitipan" data-field="x_active" data-value-separator="<?php echo $tbayartitipan->active->DisplayValueSeparatorAttribute() ?>" name="x_active" id="x_active" value="{value}"<?php echo $tbayartitipan->active->EditAttributes() ?>></div>
<div id="dsl_x_active" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $tbayartitipan->active->RadioButtonListHtml(FALSE, "x_active") ?>
</div></div>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($tbayartitipan_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($tbayartitipan_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $tbayartitipan_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($tbayartitipan_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($tbayartitipan_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($tbayartitipan_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($tbayartitipan_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php $tbayartitipan_list->ShowPageHeader(); ?>
<?php
$tbayartitipan_list->ShowMessage();
?>
<?php if ($tbayartitipan_list->TotalRecs > 0 || $tbayartitipan->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid tbayartitipan">
<form name="ftbayartitipanlist" id="ftbayartitipanlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tbayartitipan_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tbayartitipan_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tbayartitipan">
<div id="gmp_tbayartitipan" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($tbayartitipan_list->TotalRecs > 0 || $tbayartitipan->CurrentAction == "gridedit") { ?>
<table id="tbl_tbayartitipanlist" class="table ewTable">
<?php echo $tbayartitipan->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$tbayartitipan_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$tbayartitipan_list->RenderListOptions();

// Render list options (header, left)
$tbayartitipan_list->ListOptions->Render("header", "left");
?>
<?php if ($tbayartitipan->tanggal->Visible) { // tanggal ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->tanggal) == "") { ?>
		<th data-name="tanggal"><div id="elh_tbayartitipan_tanggal" class="tbayartitipan_tanggal"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->tanggal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tanggal"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->tanggal) ?>',1);"><div id="elh_tbayartitipan_tanggal" class="tbayartitipan_tanggal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->tanggal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->tanggal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->tanggal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->periode->Visible) { // periode ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->periode) == "") { ?>
		<th data-name="periode"><div id="elh_tbayartitipan_periode" class="tbayartitipan_periode"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->periode->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="periode"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->periode) ?>',1);"><div id="elh_tbayartitipan_periode" class="tbayartitipan_periode">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->periode->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->periode->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->periode->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->id->Visible) { // id ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->id) == "") { ?>
		<th data-name="id"><div id="elh_tbayartitipan_id" class="tbayartitipan_id"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->id) ?>',1);"><div id="elh_tbayartitipan_id" class="tbayartitipan_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->id->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->transaksi->Visible) { // transaksi ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->transaksi) == "") { ?>
		<th data-name="transaksi"><div id="elh_tbayartitipan_transaksi" class="tbayartitipan_transaksi"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->transaksi->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="transaksi"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->transaksi) ?>',1);"><div id="elh_tbayartitipan_transaksi" class="tbayartitipan_transaksi">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->transaksi->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->transaksi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->transaksi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->referensi->Visible) { // referensi ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->referensi) == "") { ?>
		<th data-name="referensi"><div id="elh_tbayartitipan_referensi" class="tbayartitipan_referensi"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->referensi->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="referensi"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->referensi) ?>',1);"><div id="elh_tbayartitipan_referensi" class="tbayartitipan_referensi">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->referensi->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->referensi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->referensi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->anggota->Visible) { // anggota ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->anggota) == "") { ?>
		<th data-name="anggota"><div id="elh_tbayartitipan_anggota" class="tbayartitipan_anggota"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->anggota->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="anggota"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->anggota) ?>',1);"><div id="elh_tbayartitipan_anggota" class="tbayartitipan_anggota">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->anggota->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->anggota->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->anggota->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->namaanggota->Visible) { // namaanggota ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->namaanggota) == "") { ?>
		<th data-name="namaanggota"><div id="elh_tbayartitipan_namaanggota" class="tbayartitipan_namaanggota"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->namaanggota->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="namaanggota"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->namaanggota) ?>',1);"><div id="elh_tbayartitipan_namaanggota" class="tbayartitipan_namaanggota">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->namaanggota->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->namaanggota->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->namaanggota->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->alamat->Visible) { // alamat ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->alamat) == "") { ?>
		<th data-name="alamat"><div id="elh_tbayartitipan_alamat" class="tbayartitipan_alamat"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->alamat->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="alamat"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->alamat) ?>',1);"><div id="elh_tbayartitipan_alamat" class="tbayartitipan_alamat">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->alamat->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->alamat->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->alamat->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->pekerjaan->Visible) { // pekerjaan ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->pekerjaan) == "") { ?>
		<th data-name="pekerjaan"><div id="elh_tbayartitipan_pekerjaan" class="tbayartitipan_pekerjaan"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->pekerjaan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pekerjaan"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->pekerjaan) ?>',1);"><div id="elh_tbayartitipan_pekerjaan" class="tbayartitipan_pekerjaan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->pekerjaan->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->pekerjaan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->pekerjaan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->telepon->Visible) { // telepon ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->telepon) == "") { ?>
		<th data-name="telepon"><div id="elh_tbayartitipan_telepon" class="tbayartitipan_telepon"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->telepon->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="telepon"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->telepon) ?>',1);"><div id="elh_tbayartitipan_telepon" class="tbayartitipan_telepon">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->telepon->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->telepon->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->telepon->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->hp->Visible) { // hp ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->hp) == "") { ?>
		<th data-name="hp"><div id="elh_tbayartitipan_hp" class="tbayartitipan_hp"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->hp->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="hp"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->hp) ?>',1);"><div id="elh_tbayartitipan_hp" class="tbayartitipan_hp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->hp->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->hp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->hp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->fax->Visible) { // fax ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->fax) == "") { ?>
		<th data-name="fax"><div id="elh_tbayartitipan_fax" class="tbayartitipan_fax"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->fax->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fax"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->fax) ?>',1);"><div id="elh_tbayartitipan_fax" class="tbayartitipan_fax">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->fax->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->fax->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->fax->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->_email->Visible) { // email ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->_email) == "") { ?>
		<th data-name="_email"><div id="elh_tbayartitipan__email" class="tbayartitipan__email"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->_email->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_email"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->_email) ?>',1);"><div id="elh_tbayartitipan__email" class="tbayartitipan__email">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->_email->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->_email->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->_email->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->website->Visible) { // website ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->website) == "") { ?>
		<th data-name="website"><div id="elh_tbayartitipan_website" class="tbayartitipan_website"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->website->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="website"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->website) ?>',1);"><div id="elh_tbayartitipan_website" class="tbayartitipan_website">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->website->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->website->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->website->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->jenisanggota->Visible) { // jenisanggota ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->jenisanggota) == "") { ?>
		<th data-name="jenisanggota"><div id="elh_tbayartitipan_jenisanggota" class="tbayartitipan_jenisanggota"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->jenisanggota->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jenisanggota"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->jenisanggota) ?>',1);"><div id="elh_tbayartitipan_jenisanggota" class="tbayartitipan_jenisanggota">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->jenisanggota->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->jenisanggota->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->jenisanggota->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->model->Visible) { // model ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->model) == "") { ?>
		<th data-name="model"><div id="elh_tbayartitipan_model" class="tbayartitipan_model"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->model->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="model"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->model) ?>',1);"><div id="elh_tbayartitipan_model" class="tbayartitipan_model">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->model->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->model->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->model->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->jenispinjaman->Visible) { // jenispinjaman ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->jenispinjaman) == "") { ?>
		<th data-name="jenispinjaman"><div id="elh_tbayartitipan_jenispinjaman" class="tbayartitipan_jenispinjaman"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->jenispinjaman->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jenispinjaman"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->jenispinjaman) ?>',1);"><div id="elh_tbayartitipan_jenispinjaman" class="tbayartitipan_jenispinjaman">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->jenispinjaman->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->jenispinjaman->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->jenispinjaman->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->jenisbunga->Visible) { // jenisbunga ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->jenisbunga) == "") { ?>
		<th data-name="jenisbunga"><div id="elh_tbayartitipan_jenisbunga" class="tbayartitipan_jenisbunga"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->jenisbunga->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jenisbunga"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->jenisbunga) ?>',1);"><div id="elh_tbayartitipan_jenisbunga" class="tbayartitipan_jenisbunga">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->jenisbunga->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->jenisbunga->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->jenisbunga->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->angsuran->Visible) { // angsuran ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->angsuran) == "") { ?>
		<th data-name="angsuran"><div id="elh_tbayartitipan_angsuran" class="tbayartitipan_angsuran"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->angsuran->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="angsuran"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->angsuran) ?>',1);"><div id="elh_tbayartitipan_angsuran" class="tbayartitipan_angsuran">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->angsuran->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->angsuran->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->angsuran->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->masaangsuran->Visible) { // masaangsuran ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->masaangsuran) == "") { ?>
		<th data-name="masaangsuran"><div id="elh_tbayartitipan_masaangsuran" class="tbayartitipan_masaangsuran"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->masaangsuran->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="masaangsuran"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->masaangsuran) ?>',1);"><div id="elh_tbayartitipan_masaangsuran" class="tbayartitipan_masaangsuran">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->masaangsuran->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->masaangsuran->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->masaangsuran->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->jatuhtempo->Visible) { // jatuhtempo ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->jatuhtempo) == "") { ?>
		<th data-name="jatuhtempo"><div id="elh_tbayartitipan_jatuhtempo" class="tbayartitipan_jatuhtempo"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->jatuhtempo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jatuhtempo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->jatuhtempo) ?>',1);"><div id="elh_tbayartitipan_jatuhtempo" class="tbayartitipan_jatuhtempo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->jatuhtempo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->jatuhtempo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->jatuhtempo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->dispensasidenda->Visible) { // dispensasidenda ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->dispensasidenda) == "") { ?>
		<th data-name="dispensasidenda"><div id="elh_tbayartitipan_dispensasidenda" class="tbayartitipan_dispensasidenda"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->dispensasidenda->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="dispensasidenda"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->dispensasidenda) ?>',1);"><div id="elh_tbayartitipan_dispensasidenda" class="tbayartitipan_dispensasidenda">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->dispensasidenda->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->dispensasidenda->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->dispensasidenda->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->titipan->Visible) { // titipan ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->titipan) == "") { ?>
		<th data-name="titipan"><div id="elh_tbayartitipan_titipan" class="tbayartitipan_titipan"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->titipan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="titipan"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->titipan) ?>',1);"><div id="elh_tbayartitipan_titipan" class="tbayartitipan_titipan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->titipan->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->titipan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->titipan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->bayartitipan->Visible) { // bayartitipan ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->bayartitipan) == "") { ?>
		<th data-name="bayartitipan"><div id="elh_tbayartitipan_bayartitipan" class="tbayartitipan_bayartitipan"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->bayartitipan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bayartitipan"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->bayartitipan) ?>',1);"><div id="elh_tbayartitipan_bayartitipan" class="tbayartitipan_bayartitipan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->bayartitipan->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->bayartitipan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->bayartitipan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->bayartitipanauto->Visible) { // bayartitipanauto ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->bayartitipanauto) == "") { ?>
		<th data-name="bayartitipanauto"><div id="elh_tbayartitipan_bayartitipanauto" class="tbayartitipan_bayartitipanauto"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->bayartitipanauto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bayartitipanauto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->bayartitipanauto) ?>',1);"><div id="elh_tbayartitipan_bayartitipanauto" class="tbayartitipan_bayartitipanauto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->bayartitipanauto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->bayartitipanauto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->bayartitipanauto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->terbilang->Visible) { // terbilang ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->terbilang) == "") { ?>
		<th data-name="terbilang"><div id="elh_tbayartitipan_terbilang" class="tbayartitipan_terbilang"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->terbilang->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="terbilang"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->terbilang) ?>',1);"><div id="elh_tbayartitipan_terbilang" class="tbayartitipan_terbilang">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->terbilang->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->terbilang->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->terbilang->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->petugas->Visible) { // petugas ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->petugas) == "") { ?>
		<th data-name="petugas"><div id="elh_tbayartitipan_petugas" class="tbayartitipan_petugas"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->petugas->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="petugas"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->petugas) ?>',1);"><div id="elh_tbayartitipan_petugas" class="tbayartitipan_petugas">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->petugas->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->petugas->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->petugas->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->pembayaran->Visible) { // pembayaran ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->pembayaran) == "") { ?>
		<th data-name="pembayaran"><div id="elh_tbayartitipan_pembayaran" class="tbayartitipan_pembayaran"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->pembayaran->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pembayaran"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->pembayaran) ?>',1);"><div id="elh_tbayartitipan_pembayaran" class="tbayartitipan_pembayaran">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->pembayaran->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->pembayaran->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->pembayaran->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->bank->Visible) { // bank ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->bank) == "") { ?>
		<th data-name="bank"><div id="elh_tbayartitipan_bank" class="tbayartitipan_bank"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->bank->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bank"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->bank) ?>',1);"><div id="elh_tbayartitipan_bank" class="tbayartitipan_bank">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->bank->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->bank->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->bank->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->atasnama->Visible) { // atasnama ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->atasnama) == "") { ?>
		<th data-name="atasnama"><div id="elh_tbayartitipan_atasnama" class="tbayartitipan_atasnama"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->atasnama->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="atasnama"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->atasnama) ?>',1);"><div id="elh_tbayartitipan_atasnama" class="tbayartitipan_atasnama">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->atasnama->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->atasnama->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->atasnama->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->tipe->Visible) { // tipe ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->tipe) == "") { ?>
		<th data-name="tipe"><div id="elh_tbayartitipan_tipe" class="tbayartitipan_tipe"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->tipe->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipe"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->tipe) ?>',1);"><div id="elh_tbayartitipan_tipe" class="tbayartitipan_tipe">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->tipe->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->tipe->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->tipe->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->kantor->Visible) { // kantor ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->kantor) == "") { ?>
		<th data-name="kantor"><div id="elh_tbayartitipan_kantor" class="tbayartitipan_kantor"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->kantor->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="kantor"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->kantor) ?>',1);"><div id="elh_tbayartitipan_kantor" class="tbayartitipan_kantor">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->kantor->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->kantor->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->kantor->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->keterangan->Visible) { // keterangan ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->keterangan) == "") { ?>
		<th data-name="keterangan"><div id="elh_tbayartitipan_keterangan" class="tbayartitipan_keterangan"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->keterangan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="keterangan"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->keterangan) ?>',1);"><div id="elh_tbayartitipan_keterangan" class="tbayartitipan_keterangan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->keterangan->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->keterangan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->keterangan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->active->Visible) { // active ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->active) == "") { ?>
		<th data-name="active"><div id="elh_tbayartitipan_active" class="tbayartitipan_active"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->active->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="active"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->active) ?>',1);"><div id="elh_tbayartitipan_active" class="tbayartitipan_active">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->active->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->active->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->active->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->ip->Visible) { // ip ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->ip) == "") { ?>
		<th data-name="ip"><div id="elh_tbayartitipan_ip" class="tbayartitipan_ip"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->ip->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ip"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->ip) ?>',1);"><div id="elh_tbayartitipan_ip" class="tbayartitipan_ip">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->ip->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->ip->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->ip->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->status->Visible) { // status ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->status) == "") { ?>
		<th data-name="status"><div id="elh_tbayartitipan_status" class="tbayartitipan_status"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->status->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->status) ?>',1);"><div id="elh_tbayartitipan_status" class="tbayartitipan_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->status->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->user->Visible) { // user ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->user) == "") { ?>
		<th data-name="user"><div id="elh_tbayartitipan_user" class="tbayartitipan_user"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->user->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="user"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->user) ?>',1);"><div id="elh_tbayartitipan_user" class="tbayartitipan_user">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->user->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->user->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->user->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->created->Visible) { // created ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->created) == "") { ?>
		<th data-name="created"><div id="elh_tbayartitipan_created" class="tbayartitipan_created"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->created->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="created"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->created) ?>',1);"><div id="elh_tbayartitipan_created" class="tbayartitipan_created">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->created->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->created->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->created->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayartitipan->modified->Visible) { // modified ?>
	<?php if ($tbayartitipan->SortUrl($tbayartitipan->modified) == "") { ?>
		<th data-name="modified"><div id="elh_tbayartitipan_modified" class="tbayartitipan_modified"><div class="ewTableHeaderCaption"><?php echo $tbayartitipan->modified->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="modified"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayartitipan->SortUrl($tbayartitipan->modified) ?>',1);"><div id="elh_tbayartitipan_modified" class="tbayartitipan_modified">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayartitipan->modified->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayartitipan->modified->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayartitipan->modified->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$tbayartitipan_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($tbayartitipan->ExportAll && $tbayartitipan->Export <> "") {
	$tbayartitipan_list->StopRec = $tbayartitipan_list->TotalRecs;
} else {

	// Set the last record to display
	if ($tbayartitipan_list->TotalRecs > $tbayartitipan_list->StartRec + $tbayartitipan_list->DisplayRecs - 1)
		$tbayartitipan_list->StopRec = $tbayartitipan_list->StartRec + $tbayartitipan_list->DisplayRecs - 1;
	else
		$tbayartitipan_list->StopRec = $tbayartitipan_list->TotalRecs;
}
$tbayartitipan_list->RecCnt = $tbayartitipan_list->StartRec - 1;
if ($tbayartitipan_list->Recordset && !$tbayartitipan_list->Recordset->EOF) {
	$tbayartitipan_list->Recordset->MoveFirst();
	$bSelectLimit = $tbayartitipan_list->UseSelectLimit;
	if (!$bSelectLimit && $tbayartitipan_list->StartRec > 1)
		$tbayartitipan_list->Recordset->Move($tbayartitipan_list->StartRec - 1);
} elseif (!$tbayartitipan->AllowAddDeleteRow && $tbayartitipan_list->StopRec == 0) {
	$tbayartitipan_list->StopRec = $tbayartitipan->GridAddRowCount;
}

// Initialize aggregate
$tbayartitipan->RowType = EW_ROWTYPE_AGGREGATEINIT;
$tbayartitipan->ResetAttrs();
$tbayartitipan_list->RenderRow();
while ($tbayartitipan_list->RecCnt < $tbayartitipan_list->StopRec) {
	$tbayartitipan_list->RecCnt++;
	if (intval($tbayartitipan_list->RecCnt) >= intval($tbayartitipan_list->StartRec)) {
		$tbayartitipan_list->RowCnt++;

		// Set up key count
		$tbayartitipan_list->KeyCount = $tbayartitipan_list->RowIndex;

		// Init row class and style
		$tbayartitipan->ResetAttrs();
		$tbayartitipan->CssClass = "";
		if ($tbayartitipan->CurrentAction == "gridadd") {
		} else {
			$tbayartitipan_list->LoadRowValues($tbayartitipan_list->Recordset); // Load row values
		}
		$tbayartitipan->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$tbayartitipan->RowAttrs = array_merge($tbayartitipan->RowAttrs, array('data-rowindex'=>$tbayartitipan_list->RowCnt, 'id'=>'r' . $tbayartitipan_list->RowCnt . '_tbayartitipan', 'data-rowtype'=>$tbayartitipan->RowType));

		// Render row
		$tbayartitipan_list->RenderRow();

		// Render list options
		$tbayartitipan_list->RenderListOptions();
?>
	<tr<?php echo $tbayartitipan->RowAttributes() ?>>
<?php

// Render list options (body, left)
$tbayartitipan_list->ListOptions->Render("body", "left", $tbayartitipan_list->RowCnt);
?>
	<?php if ($tbayartitipan->tanggal->Visible) { // tanggal ?>
		<td data-name="tanggal"<?php echo $tbayartitipan->tanggal->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_tanggal" class="tbayartitipan_tanggal">
<span<?php echo $tbayartitipan->tanggal->ViewAttributes() ?>>
<?php echo $tbayartitipan->tanggal->ListViewValue() ?></span>
</span>
<a id="<?php echo $tbayartitipan_list->PageObjName . "_row_" . $tbayartitipan_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($tbayartitipan->periode->Visible) { // periode ?>
		<td data-name="periode"<?php echo $tbayartitipan->periode->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_periode" class="tbayartitipan_periode">
<span<?php echo $tbayartitipan->periode->ViewAttributes() ?>>
<?php echo $tbayartitipan->periode->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->id->Visible) { // id ?>
		<td data-name="id"<?php echo $tbayartitipan->id->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_id" class="tbayartitipan_id">
<span<?php echo $tbayartitipan->id->ViewAttributes() ?>>
<?php echo $tbayartitipan->id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->transaksi->Visible) { // transaksi ?>
		<td data-name="transaksi"<?php echo $tbayartitipan->transaksi->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_transaksi" class="tbayartitipan_transaksi">
<span<?php echo $tbayartitipan->transaksi->ViewAttributes() ?>>
<?php echo $tbayartitipan->transaksi->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->referensi->Visible) { // referensi ?>
		<td data-name="referensi"<?php echo $tbayartitipan->referensi->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_referensi" class="tbayartitipan_referensi">
<span<?php echo $tbayartitipan->referensi->ViewAttributes() ?>>
<?php echo $tbayartitipan->referensi->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->anggota->Visible) { // anggota ?>
		<td data-name="anggota"<?php echo $tbayartitipan->anggota->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_anggota" class="tbayartitipan_anggota">
<span<?php echo $tbayartitipan->anggota->ViewAttributes() ?>>
<?php echo $tbayartitipan->anggota->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->namaanggota->Visible) { // namaanggota ?>
		<td data-name="namaanggota"<?php echo $tbayartitipan->namaanggota->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_namaanggota" class="tbayartitipan_namaanggota">
<span<?php echo $tbayartitipan->namaanggota->ViewAttributes() ?>>
<?php echo $tbayartitipan->namaanggota->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->alamat->Visible) { // alamat ?>
		<td data-name="alamat"<?php echo $tbayartitipan->alamat->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_alamat" class="tbayartitipan_alamat">
<span<?php echo $tbayartitipan->alamat->ViewAttributes() ?>>
<?php echo $tbayartitipan->alamat->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->pekerjaan->Visible) { // pekerjaan ?>
		<td data-name="pekerjaan"<?php echo $tbayartitipan->pekerjaan->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_pekerjaan" class="tbayartitipan_pekerjaan">
<span<?php echo $tbayartitipan->pekerjaan->ViewAttributes() ?>>
<?php echo $tbayartitipan->pekerjaan->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->telepon->Visible) { // telepon ?>
		<td data-name="telepon"<?php echo $tbayartitipan->telepon->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_telepon" class="tbayartitipan_telepon">
<span<?php echo $tbayartitipan->telepon->ViewAttributes() ?>>
<?php echo $tbayartitipan->telepon->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->hp->Visible) { // hp ?>
		<td data-name="hp"<?php echo $tbayartitipan->hp->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_hp" class="tbayartitipan_hp">
<span<?php echo $tbayartitipan->hp->ViewAttributes() ?>>
<?php echo $tbayartitipan->hp->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->fax->Visible) { // fax ?>
		<td data-name="fax"<?php echo $tbayartitipan->fax->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_fax" class="tbayartitipan_fax">
<span<?php echo $tbayartitipan->fax->ViewAttributes() ?>>
<?php echo $tbayartitipan->fax->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->_email->Visible) { // email ?>
		<td data-name="_email"<?php echo $tbayartitipan->_email->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan__email" class="tbayartitipan__email">
<span<?php echo $tbayartitipan->_email->ViewAttributes() ?>>
<?php echo $tbayartitipan->_email->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->website->Visible) { // website ?>
		<td data-name="website"<?php echo $tbayartitipan->website->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_website" class="tbayartitipan_website">
<span<?php echo $tbayartitipan->website->ViewAttributes() ?>>
<?php echo $tbayartitipan->website->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->jenisanggota->Visible) { // jenisanggota ?>
		<td data-name="jenisanggota"<?php echo $tbayartitipan->jenisanggota->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_jenisanggota" class="tbayartitipan_jenisanggota">
<span<?php echo $tbayartitipan->jenisanggota->ViewAttributes() ?>>
<?php echo $tbayartitipan->jenisanggota->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->model->Visible) { // model ?>
		<td data-name="model"<?php echo $tbayartitipan->model->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_model" class="tbayartitipan_model">
<span<?php echo $tbayartitipan->model->ViewAttributes() ?>>
<?php echo $tbayartitipan->model->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->jenispinjaman->Visible) { // jenispinjaman ?>
		<td data-name="jenispinjaman"<?php echo $tbayartitipan->jenispinjaman->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_jenispinjaman" class="tbayartitipan_jenispinjaman">
<span<?php echo $tbayartitipan->jenispinjaman->ViewAttributes() ?>>
<?php echo $tbayartitipan->jenispinjaman->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->jenisbunga->Visible) { // jenisbunga ?>
		<td data-name="jenisbunga"<?php echo $tbayartitipan->jenisbunga->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_jenisbunga" class="tbayartitipan_jenisbunga">
<span<?php echo $tbayartitipan->jenisbunga->ViewAttributes() ?>>
<?php echo $tbayartitipan->jenisbunga->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->angsuran->Visible) { // angsuran ?>
		<td data-name="angsuran"<?php echo $tbayartitipan->angsuran->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_angsuran" class="tbayartitipan_angsuran">
<span<?php echo $tbayartitipan->angsuran->ViewAttributes() ?>>
<?php echo $tbayartitipan->angsuran->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->masaangsuran->Visible) { // masaangsuran ?>
		<td data-name="masaangsuran"<?php echo $tbayartitipan->masaangsuran->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_masaangsuran" class="tbayartitipan_masaangsuran">
<span<?php echo $tbayartitipan->masaangsuran->ViewAttributes() ?>>
<?php echo $tbayartitipan->masaangsuran->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->jatuhtempo->Visible) { // jatuhtempo ?>
		<td data-name="jatuhtempo"<?php echo $tbayartitipan->jatuhtempo->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_jatuhtempo" class="tbayartitipan_jatuhtempo">
<span<?php echo $tbayartitipan->jatuhtempo->ViewAttributes() ?>>
<?php echo $tbayartitipan->jatuhtempo->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->dispensasidenda->Visible) { // dispensasidenda ?>
		<td data-name="dispensasidenda"<?php echo $tbayartitipan->dispensasidenda->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_dispensasidenda" class="tbayartitipan_dispensasidenda">
<span<?php echo $tbayartitipan->dispensasidenda->ViewAttributes() ?>>
<?php echo $tbayartitipan->dispensasidenda->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->titipan->Visible) { // titipan ?>
		<td data-name="titipan"<?php echo $tbayartitipan->titipan->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_titipan" class="tbayartitipan_titipan">
<span<?php echo $tbayartitipan->titipan->ViewAttributes() ?>>
<?php echo $tbayartitipan->titipan->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->bayartitipan->Visible) { // bayartitipan ?>
		<td data-name="bayartitipan"<?php echo $tbayartitipan->bayartitipan->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_bayartitipan" class="tbayartitipan_bayartitipan">
<span<?php echo $tbayartitipan->bayartitipan->ViewAttributes() ?>>
<?php echo $tbayartitipan->bayartitipan->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->bayartitipanauto->Visible) { // bayartitipanauto ?>
		<td data-name="bayartitipanauto"<?php echo $tbayartitipan->bayartitipanauto->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_bayartitipanauto" class="tbayartitipan_bayartitipanauto">
<span<?php echo $tbayartitipan->bayartitipanauto->ViewAttributes() ?>>
<?php echo $tbayartitipan->bayartitipanauto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->terbilang->Visible) { // terbilang ?>
		<td data-name="terbilang"<?php echo $tbayartitipan->terbilang->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_terbilang" class="tbayartitipan_terbilang">
<span<?php echo $tbayartitipan->terbilang->ViewAttributes() ?>>
<?php echo $tbayartitipan->terbilang->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->petugas->Visible) { // petugas ?>
		<td data-name="petugas"<?php echo $tbayartitipan->petugas->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_petugas" class="tbayartitipan_petugas">
<span<?php echo $tbayartitipan->petugas->ViewAttributes() ?>>
<?php echo $tbayartitipan->petugas->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->pembayaran->Visible) { // pembayaran ?>
		<td data-name="pembayaran"<?php echo $tbayartitipan->pembayaran->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_pembayaran" class="tbayartitipan_pembayaran">
<span<?php echo $tbayartitipan->pembayaran->ViewAttributes() ?>>
<?php echo $tbayartitipan->pembayaran->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->bank->Visible) { // bank ?>
		<td data-name="bank"<?php echo $tbayartitipan->bank->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_bank" class="tbayartitipan_bank">
<span<?php echo $tbayartitipan->bank->ViewAttributes() ?>>
<?php echo $tbayartitipan->bank->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->atasnama->Visible) { // atasnama ?>
		<td data-name="atasnama"<?php echo $tbayartitipan->atasnama->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_atasnama" class="tbayartitipan_atasnama">
<span<?php echo $tbayartitipan->atasnama->ViewAttributes() ?>>
<?php echo $tbayartitipan->atasnama->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->tipe->Visible) { // tipe ?>
		<td data-name="tipe"<?php echo $tbayartitipan->tipe->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_tipe" class="tbayartitipan_tipe">
<span<?php echo $tbayartitipan->tipe->ViewAttributes() ?>>
<?php echo $tbayartitipan->tipe->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->kantor->Visible) { // kantor ?>
		<td data-name="kantor"<?php echo $tbayartitipan->kantor->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_kantor" class="tbayartitipan_kantor">
<span<?php echo $tbayartitipan->kantor->ViewAttributes() ?>>
<?php echo $tbayartitipan->kantor->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->keterangan->Visible) { // keterangan ?>
		<td data-name="keterangan"<?php echo $tbayartitipan->keterangan->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_keterangan" class="tbayartitipan_keterangan">
<span<?php echo $tbayartitipan->keterangan->ViewAttributes() ?>>
<?php echo $tbayartitipan->keterangan->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->active->Visible) { // active ?>
		<td data-name="active"<?php echo $tbayartitipan->active->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_active" class="tbayartitipan_active">
<span<?php echo $tbayartitipan->active->ViewAttributes() ?>>
<?php echo $tbayartitipan->active->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->ip->Visible) { // ip ?>
		<td data-name="ip"<?php echo $tbayartitipan->ip->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_ip" class="tbayartitipan_ip">
<span<?php echo $tbayartitipan->ip->ViewAttributes() ?>>
<?php echo $tbayartitipan->ip->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->status->Visible) { // status ?>
		<td data-name="status"<?php echo $tbayartitipan->status->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_status" class="tbayartitipan_status">
<span<?php echo $tbayartitipan->status->ViewAttributes() ?>>
<?php echo $tbayartitipan->status->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->user->Visible) { // user ?>
		<td data-name="user"<?php echo $tbayartitipan->user->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_user" class="tbayartitipan_user">
<span<?php echo $tbayartitipan->user->ViewAttributes() ?>>
<?php echo $tbayartitipan->user->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->created->Visible) { // created ?>
		<td data-name="created"<?php echo $tbayartitipan->created->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_created" class="tbayartitipan_created">
<span<?php echo $tbayartitipan->created->ViewAttributes() ?>>
<?php echo $tbayartitipan->created->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayartitipan->modified->Visible) { // modified ?>
		<td data-name="modified"<?php echo $tbayartitipan->modified->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_list->RowCnt ?>_tbayartitipan_modified" class="tbayartitipan_modified">
<span<?php echo $tbayartitipan->modified->ViewAttributes() ?>>
<?php echo $tbayartitipan->modified->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$tbayartitipan_list->ListOptions->Render("body", "right", $tbayartitipan_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($tbayartitipan->CurrentAction <> "gridadd")
		$tbayartitipan_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($tbayartitipan->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($tbayartitipan_list->Recordset)
	$tbayartitipan_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($tbayartitipan->CurrentAction <> "gridadd" && $tbayartitipan->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($tbayartitipan_list->Pager)) $tbayartitipan_list->Pager = new cPrevNextPager($tbayartitipan_list->StartRec, $tbayartitipan_list->DisplayRecs, $tbayartitipan_list->TotalRecs) ?>
<?php if ($tbayartitipan_list->Pager->RecordCount > 0 && $tbayartitipan_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($tbayartitipan_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $tbayartitipan_list->PageUrl() ?>start=<?php echo $tbayartitipan_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($tbayartitipan_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $tbayartitipan_list->PageUrl() ?>start=<?php echo $tbayartitipan_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $tbayartitipan_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($tbayartitipan_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $tbayartitipan_list->PageUrl() ?>start=<?php echo $tbayartitipan_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($tbayartitipan_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $tbayartitipan_list->PageUrl() ?>start=<?php echo $tbayartitipan_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $tbayartitipan_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $tbayartitipan_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $tbayartitipan_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $tbayartitipan_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($tbayartitipan_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($tbayartitipan_list->TotalRecs == 0 && $tbayartitipan->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($tbayartitipan_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
ftbayartitipanlistsrch.FilterList = <?php echo $tbayartitipan_list->GetFilterList() ?>;
ftbayartitipanlistsrch.Init();
ftbayartitipanlist.Init();
</script>
<?php
$tbayartitipan_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tbayartitipan_list->Page_Terminate();
?>
