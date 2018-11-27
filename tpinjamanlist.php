<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "tpinjamaninfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$tpinjaman_list = NULL; // Initialize page object first

class ctpinjaman_list extends ctpinjaman {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tpinjaman';

	// Page object name
	var $PageObjName = 'tpinjaman_list';

	// Grid form hidden field names
	var $FormName = 'ftpinjamanlist';
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

		// Table object (tpinjaman)
		if (!isset($GLOBALS["tpinjaman"]) || get_class($GLOBALS["tpinjaman"]) == "ctpinjaman") {
			$GLOBALS["tpinjaman"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tpinjaman"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "tpinjamanadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "tpinjamandelete.php";
		$this->MultiUpdateUrl = "tpinjamanupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tpinjaman', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption ftpinjamanlistsrch";

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
		$this->agunan->SetVisibility();
		$this->dataagunan1->SetVisibility();
		$this->dataagunan2->SetVisibility();
		$this->dataagunan3->SetVisibility();
		$this->dataagunan4->SetVisibility();
		$this->dataagunan5->SetVisibility();
		$this->saldobekusimpanan->SetVisibility();
		$this->saldobekuminimal->SetVisibility();
		$this->plafond->SetVisibility();
		$this->bunga->SetVisibility();
		$this->bungapersen->SetVisibility();
		$this->administrasi->SetVisibility();
		$this->administrasipersen->SetVisibility();
		$this->asuransi->SetVisibility();
		$this->notaris->SetVisibility();
		$this->biayamaterai->SetVisibility();
		$this->potongansaldobeku->SetVisibility();
		$this->angsuranpokok->SetVisibility();
		$this->angsuranpokokauto->SetVisibility();
		$this->angsuranbunga->SetVisibility();
		$this->angsuranbungaauto->SetVisibility();
		$this->denda->SetVisibility();
		$this->dendapersen->SetVisibility();
		$this->totalangsuran->SetVisibility();
		$this->totalangsuranauto->SetVisibility();
		$this->totalterima->SetVisibility();
		$this->totalterimaauto->SetVisibility();
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
		global $EW_EXPORT, $tpinjaman;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($tpinjaman);
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
			$sSavedFilterList = isset($UserProfile) ? $UserProfile->GetSearchFilters(CurrentUserName(), "ftpinjamanlistsrch") : "";
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
		$sFilterList = ew_Concat($sFilterList, $this->agunan->AdvancedSearch->ToJSON(), ","); // Field agunan
		$sFilterList = ew_Concat($sFilterList, $this->dataagunan1->AdvancedSearch->ToJSON(), ","); // Field dataagunan1
		$sFilterList = ew_Concat($sFilterList, $this->dataagunan2->AdvancedSearch->ToJSON(), ","); // Field dataagunan2
		$sFilterList = ew_Concat($sFilterList, $this->dataagunan3->AdvancedSearch->ToJSON(), ","); // Field dataagunan3
		$sFilterList = ew_Concat($sFilterList, $this->dataagunan4->AdvancedSearch->ToJSON(), ","); // Field dataagunan4
		$sFilterList = ew_Concat($sFilterList, $this->dataagunan5->AdvancedSearch->ToJSON(), ","); // Field dataagunan5
		$sFilterList = ew_Concat($sFilterList, $this->saldobekusimpanan->AdvancedSearch->ToJSON(), ","); // Field saldobekusimpanan
		$sFilterList = ew_Concat($sFilterList, $this->saldobekuminimal->AdvancedSearch->ToJSON(), ","); // Field saldobekuminimal
		$sFilterList = ew_Concat($sFilterList, $this->plafond->AdvancedSearch->ToJSON(), ","); // Field plafond
		$sFilterList = ew_Concat($sFilterList, $this->bunga->AdvancedSearch->ToJSON(), ","); // Field bunga
		$sFilterList = ew_Concat($sFilterList, $this->bungapersen->AdvancedSearch->ToJSON(), ","); // Field bungapersen
		$sFilterList = ew_Concat($sFilterList, $this->administrasi->AdvancedSearch->ToJSON(), ","); // Field administrasi
		$sFilterList = ew_Concat($sFilterList, $this->administrasipersen->AdvancedSearch->ToJSON(), ","); // Field administrasipersen
		$sFilterList = ew_Concat($sFilterList, $this->asuransi->AdvancedSearch->ToJSON(), ","); // Field asuransi
		$sFilterList = ew_Concat($sFilterList, $this->notaris->AdvancedSearch->ToJSON(), ","); // Field notaris
		$sFilterList = ew_Concat($sFilterList, $this->biayamaterai->AdvancedSearch->ToJSON(), ","); // Field biayamaterai
		$sFilterList = ew_Concat($sFilterList, $this->potongansaldobeku->AdvancedSearch->ToJSON(), ","); // Field potongansaldobeku
		$sFilterList = ew_Concat($sFilterList, $this->angsuranpokok->AdvancedSearch->ToJSON(), ","); // Field angsuranpokok
		$sFilterList = ew_Concat($sFilterList, $this->angsuranpokokauto->AdvancedSearch->ToJSON(), ","); // Field angsuranpokokauto
		$sFilterList = ew_Concat($sFilterList, $this->angsuranbunga->AdvancedSearch->ToJSON(), ","); // Field angsuranbunga
		$sFilterList = ew_Concat($sFilterList, $this->angsuranbungaauto->AdvancedSearch->ToJSON(), ","); // Field angsuranbungaauto
		$sFilterList = ew_Concat($sFilterList, $this->denda->AdvancedSearch->ToJSON(), ","); // Field denda
		$sFilterList = ew_Concat($sFilterList, $this->dendapersen->AdvancedSearch->ToJSON(), ","); // Field dendapersen
		$sFilterList = ew_Concat($sFilterList, $this->totalangsuran->AdvancedSearch->ToJSON(), ","); // Field totalangsuran
		$sFilterList = ew_Concat($sFilterList, $this->totalangsuranauto->AdvancedSearch->ToJSON(), ","); // Field totalangsuranauto
		$sFilterList = ew_Concat($sFilterList, $this->totalterima->AdvancedSearch->ToJSON(), ","); // Field totalterima
		$sFilterList = ew_Concat($sFilterList, $this->totalterimaauto->AdvancedSearch->ToJSON(), ","); // Field totalterimaauto
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "ftpinjamanlistsrch", $filters);

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

		// Field agunan
		$this->agunan->AdvancedSearch->SearchValue = @$filter["x_agunan"];
		$this->agunan->AdvancedSearch->SearchOperator = @$filter["z_agunan"];
		$this->agunan->AdvancedSearch->SearchCondition = @$filter["v_agunan"];
		$this->agunan->AdvancedSearch->SearchValue2 = @$filter["y_agunan"];
		$this->agunan->AdvancedSearch->SearchOperator2 = @$filter["w_agunan"];
		$this->agunan->AdvancedSearch->Save();

		// Field dataagunan1
		$this->dataagunan1->AdvancedSearch->SearchValue = @$filter["x_dataagunan1"];
		$this->dataagunan1->AdvancedSearch->SearchOperator = @$filter["z_dataagunan1"];
		$this->dataagunan1->AdvancedSearch->SearchCondition = @$filter["v_dataagunan1"];
		$this->dataagunan1->AdvancedSearch->SearchValue2 = @$filter["y_dataagunan1"];
		$this->dataagunan1->AdvancedSearch->SearchOperator2 = @$filter["w_dataagunan1"];
		$this->dataagunan1->AdvancedSearch->Save();

		// Field dataagunan2
		$this->dataagunan2->AdvancedSearch->SearchValue = @$filter["x_dataagunan2"];
		$this->dataagunan2->AdvancedSearch->SearchOperator = @$filter["z_dataagunan2"];
		$this->dataagunan2->AdvancedSearch->SearchCondition = @$filter["v_dataagunan2"];
		$this->dataagunan2->AdvancedSearch->SearchValue2 = @$filter["y_dataagunan2"];
		$this->dataagunan2->AdvancedSearch->SearchOperator2 = @$filter["w_dataagunan2"];
		$this->dataagunan2->AdvancedSearch->Save();

		// Field dataagunan3
		$this->dataagunan3->AdvancedSearch->SearchValue = @$filter["x_dataagunan3"];
		$this->dataagunan3->AdvancedSearch->SearchOperator = @$filter["z_dataagunan3"];
		$this->dataagunan3->AdvancedSearch->SearchCondition = @$filter["v_dataagunan3"];
		$this->dataagunan3->AdvancedSearch->SearchValue2 = @$filter["y_dataagunan3"];
		$this->dataagunan3->AdvancedSearch->SearchOperator2 = @$filter["w_dataagunan3"];
		$this->dataagunan3->AdvancedSearch->Save();

		// Field dataagunan4
		$this->dataagunan4->AdvancedSearch->SearchValue = @$filter["x_dataagunan4"];
		$this->dataagunan4->AdvancedSearch->SearchOperator = @$filter["z_dataagunan4"];
		$this->dataagunan4->AdvancedSearch->SearchCondition = @$filter["v_dataagunan4"];
		$this->dataagunan4->AdvancedSearch->SearchValue2 = @$filter["y_dataagunan4"];
		$this->dataagunan4->AdvancedSearch->SearchOperator2 = @$filter["w_dataagunan4"];
		$this->dataagunan4->AdvancedSearch->Save();

		// Field dataagunan5
		$this->dataagunan5->AdvancedSearch->SearchValue = @$filter["x_dataagunan5"];
		$this->dataagunan5->AdvancedSearch->SearchOperator = @$filter["z_dataagunan5"];
		$this->dataagunan5->AdvancedSearch->SearchCondition = @$filter["v_dataagunan5"];
		$this->dataagunan5->AdvancedSearch->SearchValue2 = @$filter["y_dataagunan5"];
		$this->dataagunan5->AdvancedSearch->SearchOperator2 = @$filter["w_dataagunan5"];
		$this->dataagunan5->AdvancedSearch->Save();

		// Field saldobekusimpanan
		$this->saldobekusimpanan->AdvancedSearch->SearchValue = @$filter["x_saldobekusimpanan"];
		$this->saldobekusimpanan->AdvancedSearch->SearchOperator = @$filter["z_saldobekusimpanan"];
		$this->saldobekusimpanan->AdvancedSearch->SearchCondition = @$filter["v_saldobekusimpanan"];
		$this->saldobekusimpanan->AdvancedSearch->SearchValue2 = @$filter["y_saldobekusimpanan"];
		$this->saldobekusimpanan->AdvancedSearch->SearchOperator2 = @$filter["w_saldobekusimpanan"];
		$this->saldobekusimpanan->AdvancedSearch->Save();

		// Field saldobekuminimal
		$this->saldobekuminimal->AdvancedSearch->SearchValue = @$filter["x_saldobekuminimal"];
		$this->saldobekuminimal->AdvancedSearch->SearchOperator = @$filter["z_saldobekuminimal"];
		$this->saldobekuminimal->AdvancedSearch->SearchCondition = @$filter["v_saldobekuminimal"];
		$this->saldobekuminimal->AdvancedSearch->SearchValue2 = @$filter["y_saldobekuminimal"];
		$this->saldobekuminimal->AdvancedSearch->SearchOperator2 = @$filter["w_saldobekuminimal"];
		$this->saldobekuminimal->AdvancedSearch->Save();

		// Field plafond
		$this->plafond->AdvancedSearch->SearchValue = @$filter["x_plafond"];
		$this->plafond->AdvancedSearch->SearchOperator = @$filter["z_plafond"];
		$this->plafond->AdvancedSearch->SearchCondition = @$filter["v_plafond"];
		$this->plafond->AdvancedSearch->SearchValue2 = @$filter["y_plafond"];
		$this->plafond->AdvancedSearch->SearchOperator2 = @$filter["w_plafond"];
		$this->plafond->AdvancedSearch->Save();

		// Field bunga
		$this->bunga->AdvancedSearch->SearchValue = @$filter["x_bunga"];
		$this->bunga->AdvancedSearch->SearchOperator = @$filter["z_bunga"];
		$this->bunga->AdvancedSearch->SearchCondition = @$filter["v_bunga"];
		$this->bunga->AdvancedSearch->SearchValue2 = @$filter["y_bunga"];
		$this->bunga->AdvancedSearch->SearchOperator2 = @$filter["w_bunga"];
		$this->bunga->AdvancedSearch->Save();

		// Field bungapersen
		$this->bungapersen->AdvancedSearch->SearchValue = @$filter["x_bungapersen"];
		$this->bungapersen->AdvancedSearch->SearchOperator = @$filter["z_bungapersen"];
		$this->bungapersen->AdvancedSearch->SearchCondition = @$filter["v_bungapersen"];
		$this->bungapersen->AdvancedSearch->SearchValue2 = @$filter["y_bungapersen"];
		$this->bungapersen->AdvancedSearch->SearchOperator2 = @$filter["w_bungapersen"];
		$this->bungapersen->AdvancedSearch->Save();

		// Field administrasi
		$this->administrasi->AdvancedSearch->SearchValue = @$filter["x_administrasi"];
		$this->administrasi->AdvancedSearch->SearchOperator = @$filter["z_administrasi"];
		$this->administrasi->AdvancedSearch->SearchCondition = @$filter["v_administrasi"];
		$this->administrasi->AdvancedSearch->SearchValue2 = @$filter["y_administrasi"];
		$this->administrasi->AdvancedSearch->SearchOperator2 = @$filter["w_administrasi"];
		$this->administrasi->AdvancedSearch->Save();

		// Field administrasipersen
		$this->administrasipersen->AdvancedSearch->SearchValue = @$filter["x_administrasipersen"];
		$this->administrasipersen->AdvancedSearch->SearchOperator = @$filter["z_administrasipersen"];
		$this->administrasipersen->AdvancedSearch->SearchCondition = @$filter["v_administrasipersen"];
		$this->administrasipersen->AdvancedSearch->SearchValue2 = @$filter["y_administrasipersen"];
		$this->administrasipersen->AdvancedSearch->SearchOperator2 = @$filter["w_administrasipersen"];
		$this->administrasipersen->AdvancedSearch->Save();

		// Field asuransi
		$this->asuransi->AdvancedSearch->SearchValue = @$filter["x_asuransi"];
		$this->asuransi->AdvancedSearch->SearchOperator = @$filter["z_asuransi"];
		$this->asuransi->AdvancedSearch->SearchCondition = @$filter["v_asuransi"];
		$this->asuransi->AdvancedSearch->SearchValue2 = @$filter["y_asuransi"];
		$this->asuransi->AdvancedSearch->SearchOperator2 = @$filter["w_asuransi"];
		$this->asuransi->AdvancedSearch->Save();

		// Field notaris
		$this->notaris->AdvancedSearch->SearchValue = @$filter["x_notaris"];
		$this->notaris->AdvancedSearch->SearchOperator = @$filter["z_notaris"];
		$this->notaris->AdvancedSearch->SearchCondition = @$filter["v_notaris"];
		$this->notaris->AdvancedSearch->SearchValue2 = @$filter["y_notaris"];
		$this->notaris->AdvancedSearch->SearchOperator2 = @$filter["w_notaris"];
		$this->notaris->AdvancedSearch->Save();

		// Field biayamaterai
		$this->biayamaterai->AdvancedSearch->SearchValue = @$filter["x_biayamaterai"];
		$this->biayamaterai->AdvancedSearch->SearchOperator = @$filter["z_biayamaterai"];
		$this->biayamaterai->AdvancedSearch->SearchCondition = @$filter["v_biayamaterai"];
		$this->biayamaterai->AdvancedSearch->SearchValue2 = @$filter["y_biayamaterai"];
		$this->biayamaterai->AdvancedSearch->SearchOperator2 = @$filter["w_biayamaterai"];
		$this->biayamaterai->AdvancedSearch->Save();

		// Field potongansaldobeku
		$this->potongansaldobeku->AdvancedSearch->SearchValue = @$filter["x_potongansaldobeku"];
		$this->potongansaldobeku->AdvancedSearch->SearchOperator = @$filter["z_potongansaldobeku"];
		$this->potongansaldobeku->AdvancedSearch->SearchCondition = @$filter["v_potongansaldobeku"];
		$this->potongansaldobeku->AdvancedSearch->SearchValue2 = @$filter["y_potongansaldobeku"];
		$this->potongansaldobeku->AdvancedSearch->SearchOperator2 = @$filter["w_potongansaldobeku"];
		$this->potongansaldobeku->AdvancedSearch->Save();

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

		// Field totalterima
		$this->totalterima->AdvancedSearch->SearchValue = @$filter["x_totalterima"];
		$this->totalterima->AdvancedSearch->SearchOperator = @$filter["z_totalterima"];
		$this->totalterima->AdvancedSearch->SearchCondition = @$filter["v_totalterima"];
		$this->totalterima->AdvancedSearch->SearchValue2 = @$filter["y_totalterima"];
		$this->totalterima->AdvancedSearch->SearchOperator2 = @$filter["w_totalterima"];
		$this->totalterima->AdvancedSearch->Save();

		// Field totalterimaauto
		$this->totalterimaauto->AdvancedSearch->SearchValue = @$filter["x_totalterimaauto"];
		$this->totalterimaauto->AdvancedSearch->SearchOperator = @$filter["z_totalterimaauto"];
		$this->totalterimaauto->AdvancedSearch->SearchCondition = @$filter["v_totalterimaauto"];
		$this->totalterimaauto->AdvancedSearch->SearchValue2 = @$filter["y_totalterimaauto"];
		$this->totalterimaauto->AdvancedSearch->SearchOperator2 = @$filter["w_totalterimaauto"];
		$this->totalterimaauto->AdvancedSearch->Save();

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
		$this->BuildSearchSql($sWhere, $this->agunan, $Default, FALSE); // agunan
		$this->BuildSearchSql($sWhere, $this->dataagunan1, $Default, FALSE); // dataagunan1
		$this->BuildSearchSql($sWhere, $this->dataagunan2, $Default, FALSE); // dataagunan2
		$this->BuildSearchSql($sWhere, $this->dataagunan3, $Default, FALSE); // dataagunan3
		$this->BuildSearchSql($sWhere, $this->dataagunan4, $Default, FALSE); // dataagunan4
		$this->BuildSearchSql($sWhere, $this->dataagunan5, $Default, FALSE); // dataagunan5
		$this->BuildSearchSql($sWhere, $this->saldobekusimpanan, $Default, FALSE); // saldobekusimpanan
		$this->BuildSearchSql($sWhere, $this->saldobekuminimal, $Default, FALSE); // saldobekuminimal
		$this->BuildSearchSql($sWhere, $this->plafond, $Default, FALSE); // plafond
		$this->BuildSearchSql($sWhere, $this->bunga, $Default, FALSE); // bunga
		$this->BuildSearchSql($sWhere, $this->bungapersen, $Default, FALSE); // bungapersen
		$this->BuildSearchSql($sWhere, $this->administrasi, $Default, FALSE); // administrasi
		$this->BuildSearchSql($sWhere, $this->administrasipersen, $Default, FALSE); // administrasipersen
		$this->BuildSearchSql($sWhere, $this->asuransi, $Default, FALSE); // asuransi
		$this->BuildSearchSql($sWhere, $this->notaris, $Default, FALSE); // notaris
		$this->BuildSearchSql($sWhere, $this->biayamaterai, $Default, FALSE); // biayamaterai
		$this->BuildSearchSql($sWhere, $this->potongansaldobeku, $Default, FALSE); // potongansaldobeku
		$this->BuildSearchSql($sWhere, $this->angsuranpokok, $Default, FALSE); // angsuranpokok
		$this->BuildSearchSql($sWhere, $this->angsuranpokokauto, $Default, FALSE); // angsuranpokokauto
		$this->BuildSearchSql($sWhere, $this->angsuranbunga, $Default, FALSE); // angsuranbunga
		$this->BuildSearchSql($sWhere, $this->angsuranbungaauto, $Default, FALSE); // angsuranbungaauto
		$this->BuildSearchSql($sWhere, $this->denda, $Default, FALSE); // denda
		$this->BuildSearchSql($sWhere, $this->dendapersen, $Default, FALSE); // dendapersen
		$this->BuildSearchSql($sWhere, $this->totalangsuran, $Default, FALSE); // totalangsuran
		$this->BuildSearchSql($sWhere, $this->totalangsuranauto, $Default, FALSE); // totalangsuranauto
		$this->BuildSearchSql($sWhere, $this->totalterima, $Default, FALSE); // totalterima
		$this->BuildSearchSql($sWhere, $this->totalterimaauto, $Default, FALSE); // totalterimaauto
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
			$this->agunan->AdvancedSearch->Save(); // agunan
			$this->dataagunan1->AdvancedSearch->Save(); // dataagunan1
			$this->dataagunan2->AdvancedSearch->Save(); // dataagunan2
			$this->dataagunan3->AdvancedSearch->Save(); // dataagunan3
			$this->dataagunan4->AdvancedSearch->Save(); // dataagunan4
			$this->dataagunan5->AdvancedSearch->Save(); // dataagunan5
			$this->saldobekusimpanan->AdvancedSearch->Save(); // saldobekusimpanan
			$this->saldobekuminimal->AdvancedSearch->Save(); // saldobekuminimal
			$this->plafond->AdvancedSearch->Save(); // plafond
			$this->bunga->AdvancedSearch->Save(); // bunga
			$this->bungapersen->AdvancedSearch->Save(); // bungapersen
			$this->administrasi->AdvancedSearch->Save(); // administrasi
			$this->administrasipersen->AdvancedSearch->Save(); // administrasipersen
			$this->asuransi->AdvancedSearch->Save(); // asuransi
			$this->notaris->AdvancedSearch->Save(); // notaris
			$this->biayamaterai->AdvancedSearch->Save(); // biayamaterai
			$this->potongansaldobeku->AdvancedSearch->Save(); // potongansaldobeku
			$this->angsuranpokok->AdvancedSearch->Save(); // angsuranpokok
			$this->angsuranpokokauto->AdvancedSearch->Save(); // angsuranpokokauto
			$this->angsuranbunga->AdvancedSearch->Save(); // angsuranbunga
			$this->angsuranbungaauto->AdvancedSearch->Save(); // angsuranbungaauto
			$this->denda->AdvancedSearch->Save(); // denda
			$this->dendapersen->AdvancedSearch->Save(); // dendapersen
			$this->totalangsuran->AdvancedSearch->Save(); // totalangsuran
			$this->totalangsuranauto->AdvancedSearch->Save(); // totalangsuranauto
			$this->totalterima->AdvancedSearch->Save(); // totalterima
			$this->totalterimaauto->AdvancedSearch->Save(); // totalterimaauto
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
		$this->BuildBasicSearchSQL($sWhere, $this->agunan, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->dataagunan1, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->dataagunan2, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->dataagunan3, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->dataagunan4, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->dataagunan5, $arKeywords, $type);
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
		if ($this->agunan->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dataagunan1->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dataagunan2->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dataagunan3->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dataagunan4->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dataagunan5->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->saldobekusimpanan->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->saldobekuminimal->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->plafond->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->bunga->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->bungapersen->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->administrasi->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->administrasipersen->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->asuransi->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->notaris->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->biayamaterai->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->potongansaldobeku->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->angsuranpokok->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->angsuranpokokauto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->angsuranbunga->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->angsuranbungaauto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->denda->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dendapersen->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->totalangsuran->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->totalangsuranauto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->totalterima->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->totalterimaauto->AdvancedSearch->IssetSession())
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
		$this->agunan->AdvancedSearch->UnsetSession();
		$this->dataagunan1->AdvancedSearch->UnsetSession();
		$this->dataagunan2->AdvancedSearch->UnsetSession();
		$this->dataagunan3->AdvancedSearch->UnsetSession();
		$this->dataagunan4->AdvancedSearch->UnsetSession();
		$this->dataagunan5->AdvancedSearch->UnsetSession();
		$this->saldobekusimpanan->AdvancedSearch->UnsetSession();
		$this->saldobekuminimal->AdvancedSearch->UnsetSession();
		$this->plafond->AdvancedSearch->UnsetSession();
		$this->bunga->AdvancedSearch->UnsetSession();
		$this->bungapersen->AdvancedSearch->UnsetSession();
		$this->administrasi->AdvancedSearch->UnsetSession();
		$this->administrasipersen->AdvancedSearch->UnsetSession();
		$this->asuransi->AdvancedSearch->UnsetSession();
		$this->notaris->AdvancedSearch->UnsetSession();
		$this->biayamaterai->AdvancedSearch->UnsetSession();
		$this->potongansaldobeku->AdvancedSearch->UnsetSession();
		$this->angsuranpokok->AdvancedSearch->UnsetSession();
		$this->angsuranpokokauto->AdvancedSearch->UnsetSession();
		$this->angsuranbunga->AdvancedSearch->UnsetSession();
		$this->angsuranbungaauto->AdvancedSearch->UnsetSession();
		$this->denda->AdvancedSearch->UnsetSession();
		$this->dendapersen->AdvancedSearch->UnsetSession();
		$this->totalangsuran->AdvancedSearch->UnsetSession();
		$this->totalangsuranauto->AdvancedSearch->UnsetSession();
		$this->totalterima->AdvancedSearch->UnsetSession();
		$this->totalterimaauto->AdvancedSearch->UnsetSession();
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
		$this->agunan->AdvancedSearch->Load();
		$this->dataagunan1->AdvancedSearch->Load();
		$this->dataagunan2->AdvancedSearch->Load();
		$this->dataagunan3->AdvancedSearch->Load();
		$this->dataagunan4->AdvancedSearch->Load();
		$this->dataagunan5->AdvancedSearch->Load();
		$this->saldobekusimpanan->AdvancedSearch->Load();
		$this->saldobekuminimal->AdvancedSearch->Load();
		$this->plafond->AdvancedSearch->Load();
		$this->bunga->AdvancedSearch->Load();
		$this->bungapersen->AdvancedSearch->Load();
		$this->administrasi->AdvancedSearch->Load();
		$this->administrasipersen->AdvancedSearch->Load();
		$this->asuransi->AdvancedSearch->Load();
		$this->notaris->AdvancedSearch->Load();
		$this->biayamaterai->AdvancedSearch->Load();
		$this->potongansaldobeku->AdvancedSearch->Load();
		$this->angsuranpokok->AdvancedSearch->Load();
		$this->angsuranpokokauto->AdvancedSearch->Load();
		$this->angsuranbunga->AdvancedSearch->Load();
		$this->angsuranbungaauto->AdvancedSearch->Load();
		$this->denda->AdvancedSearch->Load();
		$this->dendapersen->AdvancedSearch->Load();
		$this->totalangsuran->AdvancedSearch->Load();
		$this->totalangsuranauto->AdvancedSearch->Load();
		$this->totalterima->AdvancedSearch->Load();
		$this->totalterimaauto->AdvancedSearch->Load();
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
			$this->UpdateSort($this->agunan); // agunan
			$this->UpdateSort($this->dataagunan1); // dataagunan1
			$this->UpdateSort($this->dataagunan2); // dataagunan2
			$this->UpdateSort($this->dataagunan3); // dataagunan3
			$this->UpdateSort($this->dataagunan4); // dataagunan4
			$this->UpdateSort($this->dataagunan5); // dataagunan5
			$this->UpdateSort($this->saldobekusimpanan); // saldobekusimpanan
			$this->UpdateSort($this->saldobekuminimal); // saldobekuminimal
			$this->UpdateSort($this->plafond); // plafond
			$this->UpdateSort($this->bunga); // bunga
			$this->UpdateSort($this->bungapersen); // bungapersen
			$this->UpdateSort($this->administrasi); // administrasi
			$this->UpdateSort($this->administrasipersen); // administrasipersen
			$this->UpdateSort($this->asuransi); // asuransi
			$this->UpdateSort($this->notaris); // notaris
			$this->UpdateSort($this->biayamaterai); // biayamaterai
			$this->UpdateSort($this->potongansaldobeku); // potongansaldobeku
			$this->UpdateSort($this->angsuranpokok); // angsuranpokok
			$this->UpdateSort($this->angsuranpokokauto); // angsuranpokokauto
			$this->UpdateSort($this->angsuranbunga); // angsuranbunga
			$this->UpdateSort($this->angsuranbungaauto); // angsuranbungaauto
			$this->UpdateSort($this->denda); // denda
			$this->UpdateSort($this->dendapersen); // dendapersen
			$this->UpdateSort($this->totalangsuran); // totalangsuran
			$this->UpdateSort($this->totalangsuranauto); // totalangsuranauto
			$this->UpdateSort($this->totalterima); // totalterima
			$this->UpdateSort($this->totalterimaauto); // totalterimaauto
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
				$this->agunan->setSort("");
				$this->dataagunan1->setSort("");
				$this->dataagunan2->setSort("");
				$this->dataagunan3->setSort("");
				$this->dataagunan4->setSort("");
				$this->dataagunan5->setSort("");
				$this->saldobekusimpanan->setSort("");
				$this->saldobekuminimal->setSort("");
				$this->plafond->setSort("");
				$this->bunga->setSort("");
				$this->bungapersen->setSort("");
				$this->administrasi->setSort("");
				$this->administrasipersen->setSort("");
				$this->asuransi->setSort("");
				$this->notaris->setSort("");
				$this->biayamaterai->setSort("");
				$this->potongansaldobeku->setSort("");
				$this->angsuranpokok->setSort("");
				$this->angsuranpokokauto->setSort("");
				$this->angsuranbunga->setSort("");
				$this->angsuranbungaauto->setSort("");
				$this->denda->setSort("");
				$this->dendapersen->setSort("");
				$this->totalangsuran->setSort("");
				$this->totalangsuranauto->setSort("");
				$this->totalterima->setSort("");
				$this->totalterimaauto->setSort("");
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"ftpinjamanlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"ftpinjamanlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.ftpinjamanlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"ftpinjamanlistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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

		// agunan
		$this->agunan->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_agunan"]);
		if ($this->agunan->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->agunan->AdvancedSearch->SearchOperator = @$_GET["z_agunan"];

		// dataagunan1
		$this->dataagunan1->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dataagunan1"]);
		if ($this->dataagunan1->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dataagunan1->AdvancedSearch->SearchOperator = @$_GET["z_dataagunan1"];

		// dataagunan2
		$this->dataagunan2->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dataagunan2"]);
		if ($this->dataagunan2->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dataagunan2->AdvancedSearch->SearchOperator = @$_GET["z_dataagunan2"];

		// dataagunan3
		$this->dataagunan3->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dataagunan3"]);
		if ($this->dataagunan3->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dataagunan3->AdvancedSearch->SearchOperator = @$_GET["z_dataagunan3"];

		// dataagunan4
		$this->dataagunan4->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dataagunan4"]);
		if ($this->dataagunan4->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dataagunan4->AdvancedSearch->SearchOperator = @$_GET["z_dataagunan4"];

		// dataagunan5
		$this->dataagunan5->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dataagunan5"]);
		if ($this->dataagunan5->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dataagunan5->AdvancedSearch->SearchOperator = @$_GET["z_dataagunan5"];

		// saldobekusimpanan
		$this->saldobekusimpanan->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_saldobekusimpanan"]);
		if ($this->saldobekusimpanan->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->saldobekusimpanan->AdvancedSearch->SearchOperator = @$_GET["z_saldobekusimpanan"];

		// saldobekuminimal
		$this->saldobekuminimal->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_saldobekuminimal"]);
		if ($this->saldobekuminimal->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->saldobekuminimal->AdvancedSearch->SearchOperator = @$_GET["z_saldobekuminimal"];

		// plafond
		$this->plafond->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_plafond"]);
		if ($this->plafond->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->plafond->AdvancedSearch->SearchOperator = @$_GET["z_plafond"];

		// bunga
		$this->bunga->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_bunga"]);
		if ($this->bunga->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->bunga->AdvancedSearch->SearchOperator = @$_GET["z_bunga"];

		// bungapersen
		$this->bungapersen->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_bungapersen"]);
		if ($this->bungapersen->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->bungapersen->AdvancedSearch->SearchOperator = @$_GET["z_bungapersen"];

		// administrasi
		$this->administrasi->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_administrasi"]);
		if ($this->administrasi->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->administrasi->AdvancedSearch->SearchOperator = @$_GET["z_administrasi"];

		// administrasipersen
		$this->administrasipersen->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_administrasipersen"]);
		if ($this->administrasipersen->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->administrasipersen->AdvancedSearch->SearchOperator = @$_GET["z_administrasipersen"];

		// asuransi
		$this->asuransi->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_asuransi"]);
		if ($this->asuransi->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->asuransi->AdvancedSearch->SearchOperator = @$_GET["z_asuransi"];

		// notaris
		$this->notaris->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_notaris"]);
		if ($this->notaris->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->notaris->AdvancedSearch->SearchOperator = @$_GET["z_notaris"];

		// biayamaterai
		$this->biayamaterai->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_biayamaterai"]);
		if ($this->biayamaterai->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->biayamaterai->AdvancedSearch->SearchOperator = @$_GET["z_biayamaterai"];

		// potongansaldobeku
		$this->potongansaldobeku->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_potongansaldobeku"]);
		if ($this->potongansaldobeku->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->potongansaldobeku->AdvancedSearch->SearchOperator = @$_GET["z_potongansaldobeku"];

		// angsuranpokok
		$this->angsuranpokok->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_angsuranpokok"]);
		if ($this->angsuranpokok->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->angsuranpokok->AdvancedSearch->SearchOperator = @$_GET["z_angsuranpokok"];

		// angsuranpokokauto
		$this->angsuranpokokauto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_angsuranpokokauto"]);
		if ($this->angsuranpokokauto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->angsuranpokokauto->AdvancedSearch->SearchOperator = @$_GET["z_angsuranpokokauto"];

		// angsuranbunga
		$this->angsuranbunga->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_angsuranbunga"]);
		if ($this->angsuranbunga->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->angsuranbunga->AdvancedSearch->SearchOperator = @$_GET["z_angsuranbunga"];

		// angsuranbungaauto
		$this->angsuranbungaauto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_angsuranbungaauto"]);
		if ($this->angsuranbungaauto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->angsuranbungaauto->AdvancedSearch->SearchOperator = @$_GET["z_angsuranbungaauto"];

		// denda
		$this->denda->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_denda"]);
		if ($this->denda->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->denda->AdvancedSearch->SearchOperator = @$_GET["z_denda"];

		// dendapersen
		$this->dendapersen->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dendapersen"]);
		if ($this->dendapersen->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dendapersen->AdvancedSearch->SearchOperator = @$_GET["z_dendapersen"];

		// totalangsuran
		$this->totalangsuran->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_totalangsuran"]);
		if ($this->totalangsuran->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->totalangsuran->AdvancedSearch->SearchOperator = @$_GET["z_totalangsuran"];

		// totalangsuranauto
		$this->totalangsuranauto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_totalangsuranauto"]);
		if ($this->totalangsuranauto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->totalangsuranauto->AdvancedSearch->SearchOperator = @$_GET["z_totalangsuranauto"];

		// totalterima
		$this->totalterima->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_totalterima"]);
		if ($this->totalterima->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->totalterima->AdvancedSearch->SearchOperator = @$_GET["z_totalterima"];

		// totalterimaauto
		$this->totalterimaauto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_totalterimaauto"]);
		if ($this->totalterimaauto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->totalterimaauto->AdvancedSearch->SearchOperator = @$_GET["z_totalterimaauto"];

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
		$this->agunan->setDbValue($rs->fields('agunan'));
		$this->dataagunan1->setDbValue($rs->fields('dataagunan1'));
		$this->dataagunan2->setDbValue($rs->fields('dataagunan2'));
		$this->dataagunan3->setDbValue($rs->fields('dataagunan3'));
		$this->dataagunan4->setDbValue($rs->fields('dataagunan4'));
		$this->dataagunan5->setDbValue($rs->fields('dataagunan5'));
		$this->saldobekusimpanan->setDbValue($rs->fields('saldobekusimpanan'));
		$this->saldobekuminimal->setDbValue($rs->fields('saldobekuminimal'));
		$this->plafond->setDbValue($rs->fields('plafond'));
		$this->bunga->setDbValue($rs->fields('bunga'));
		$this->bungapersen->setDbValue($rs->fields('bungapersen'));
		$this->administrasi->setDbValue($rs->fields('administrasi'));
		$this->administrasipersen->setDbValue($rs->fields('administrasipersen'));
		$this->asuransi->setDbValue($rs->fields('asuransi'));
		$this->notaris->setDbValue($rs->fields('notaris'));
		$this->biayamaterai->setDbValue($rs->fields('biayamaterai'));
		$this->potongansaldobeku->setDbValue($rs->fields('potongansaldobeku'));
		$this->angsuranpokok->setDbValue($rs->fields('angsuranpokok'));
		$this->angsuranpokokauto->setDbValue($rs->fields('angsuranpokokauto'));
		$this->angsuranbunga->setDbValue($rs->fields('angsuranbunga'));
		$this->angsuranbungaauto->setDbValue($rs->fields('angsuranbungaauto'));
		$this->denda->setDbValue($rs->fields('denda'));
		$this->dendapersen->setDbValue($rs->fields('dendapersen'));
		$this->totalangsuran->setDbValue($rs->fields('totalangsuran'));
		$this->totalangsuranauto->setDbValue($rs->fields('totalangsuranauto'));
		$this->totalterima->setDbValue($rs->fields('totalterima'));
		$this->totalterimaauto->setDbValue($rs->fields('totalterimaauto'));
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
		$this->agunan->DbValue = $row['agunan'];
		$this->dataagunan1->DbValue = $row['dataagunan1'];
		$this->dataagunan2->DbValue = $row['dataagunan2'];
		$this->dataagunan3->DbValue = $row['dataagunan3'];
		$this->dataagunan4->DbValue = $row['dataagunan4'];
		$this->dataagunan5->DbValue = $row['dataagunan5'];
		$this->saldobekusimpanan->DbValue = $row['saldobekusimpanan'];
		$this->saldobekuminimal->DbValue = $row['saldobekuminimal'];
		$this->plafond->DbValue = $row['plafond'];
		$this->bunga->DbValue = $row['bunga'];
		$this->bungapersen->DbValue = $row['bungapersen'];
		$this->administrasi->DbValue = $row['administrasi'];
		$this->administrasipersen->DbValue = $row['administrasipersen'];
		$this->asuransi->DbValue = $row['asuransi'];
		$this->notaris->DbValue = $row['notaris'];
		$this->biayamaterai->DbValue = $row['biayamaterai'];
		$this->potongansaldobeku->DbValue = $row['potongansaldobeku'];
		$this->angsuranpokok->DbValue = $row['angsuranpokok'];
		$this->angsuranpokokauto->DbValue = $row['angsuranpokokauto'];
		$this->angsuranbunga->DbValue = $row['angsuranbunga'];
		$this->angsuranbungaauto->DbValue = $row['angsuranbungaauto'];
		$this->denda->DbValue = $row['denda'];
		$this->dendapersen->DbValue = $row['dendapersen'];
		$this->totalangsuran->DbValue = $row['totalangsuran'];
		$this->totalangsuranauto->DbValue = $row['totalangsuranauto'];
		$this->totalterima->DbValue = $row['totalterima'];
		$this->totalterimaauto->DbValue = $row['totalterimaauto'];
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
		if ($this->saldobekusimpanan->FormValue == $this->saldobekusimpanan->CurrentValue && is_numeric(ew_StrToFloat($this->saldobekusimpanan->CurrentValue)))
			$this->saldobekusimpanan->CurrentValue = ew_StrToFloat($this->saldobekusimpanan->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldobekuminimal->FormValue == $this->saldobekuminimal->CurrentValue && is_numeric(ew_StrToFloat($this->saldobekuminimal->CurrentValue)))
			$this->saldobekuminimal->CurrentValue = ew_StrToFloat($this->saldobekuminimal->CurrentValue);

		// Convert decimal values if posted back
		if ($this->plafond->FormValue == $this->plafond->CurrentValue && is_numeric(ew_StrToFloat($this->plafond->CurrentValue)))
			$this->plafond->CurrentValue = ew_StrToFloat($this->plafond->CurrentValue);

		// Convert decimal values if posted back
		if ($this->bunga->FormValue == $this->bunga->CurrentValue && is_numeric(ew_StrToFloat($this->bunga->CurrentValue)))
			$this->bunga->CurrentValue = ew_StrToFloat($this->bunga->CurrentValue);

		// Convert decimal values if posted back
		if ($this->bungapersen->FormValue == $this->bungapersen->CurrentValue && is_numeric(ew_StrToFloat($this->bungapersen->CurrentValue)))
			$this->bungapersen->CurrentValue = ew_StrToFloat($this->bungapersen->CurrentValue);

		// Convert decimal values if posted back
		if ($this->administrasi->FormValue == $this->administrasi->CurrentValue && is_numeric(ew_StrToFloat($this->administrasi->CurrentValue)))
			$this->administrasi->CurrentValue = ew_StrToFloat($this->administrasi->CurrentValue);

		// Convert decimal values if posted back
		if ($this->administrasipersen->FormValue == $this->administrasipersen->CurrentValue && is_numeric(ew_StrToFloat($this->administrasipersen->CurrentValue)))
			$this->administrasipersen->CurrentValue = ew_StrToFloat($this->administrasipersen->CurrentValue);

		// Convert decimal values if posted back
		if ($this->asuransi->FormValue == $this->asuransi->CurrentValue && is_numeric(ew_StrToFloat($this->asuransi->CurrentValue)))
			$this->asuransi->CurrentValue = ew_StrToFloat($this->asuransi->CurrentValue);

		// Convert decimal values if posted back
		if ($this->notaris->FormValue == $this->notaris->CurrentValue && is_numeric(ew_StrToFloat($this->notaris->CurrentValue)))
			$this->notaris->CurrentValue = ew_StrToFloat($this->notaris->CurrentValue);

		// Convert decimal values if posted back
		if ($this->biayamaterai->FormValue == $this->biayamaterai->CurrentValue && is_numeric(ew_StrToFloat($this->biayamaterai->CurrentValue)))
			$this->biayamaterai->CurrentValue = ew_StrToFloat($this->biayamaterai->CurrentValue);

		// Convert decimal values if posted back
		if ($this->potongansaldobeku->FormValue == $this->potongansaldobeku->CurrentValue && is_numeric(ew_StrToFloat($this->potongansaldobeku->CurrentValue)))
			$this->potongansaldobeku->CurrentValue = ew_StrToFloat($this->potongansaldobeku->CurrentValue);

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
		if ($this->totalterima->FormValue == $this->totalterima->CurrentValue && is_numeric(ew_StrToFloat($this->totalterima->CurrentValue)))
			$this->totalterima->CurrentValue = ew_StrToFloat($this->totalterima->CurrentValue);

		// Convert decimal values if posted back
		if ($this->totalterimaauto->FormValue == $this->totalterimaauto->CurrentValue && is_numeric(ew_StrToFloat($this->totalterimaauto->CurrentValue)))
			$this->totalterimaauto->CurrentValue = ew_StrToFloat($this->totalterimaauto->CurrentValue);

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
		// agunan
		// dataagunan1
		// dataagunan2
		// dataagunan3
		// dataagunan4
		// dataagunan5
		// saldobekusimpanan
		// saldobekuminimal
		// plafond
		// bunga
		// bungapersen
		// administrasi
		// administrasipersen
		// asuransi
		// notaris
		// biayamaterai
		// potongansaldobeku
		// angsuranpokok
		// angsuranpokokauto
		// angsuranbunga
		// angsuranbungaauto
		// denda
		// dendapersen
		// totalangsuran
		// totalangsuranauto
		// totalterima
		// totalterimaauto
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

		// agunan
		$this->agunan->ViewValue = $this->agunan->CurrentValue;
		$this->agunan->ViewCustomAttributes = "";

		// dataagunan1
		$this->dataagunan1->ViewValue = $this->dataagunan1->CurrentValue;
		$this->dataagunan1->ViewCustomAttributes = "";

		// dataagunan2
		$this->dataagunan2->ViewValue = $this->dataagunan2->CurrentValue;
		$this->dataagunan2->ViewCustomAttributes = "";

		// dataagunan3
		$this->dataagunan3->ViewValue = $this->dataagunan3->CurrentValue;
		$this->dataagunan3->ViewCustomAttributes = "";

		// dataagunan4
		$this->dataagunan4->ViewValue = $this->dataagunan4->CurrentValue;
		$this->dataagunan4->ViewCustomAttributes = "";

		// dataagunan5
		$this->dataagunan5->ViewValue = $this->dataagunan5->CurrentValue;
		$this->dataagunan5->ViewCustomAttributes = "";

		// saldobekusimpanan
		$this->saldobekusimpanan->ViewValue = $this->saldobekusimpanan->CurrentValue;
		$this->saldobekusimpanan->ViewCustomAttributes = "";

		// saldobekuminimal
		$this->saldobekuminimal->ViewValue = $this->saldobekuminimal->CurrentValue;
		$this->saldobekuminimal->ViewCustomAttributes = "";

		// plafond
		$this->plafond->ViewValue = $this->plafond->CurrentValue;
		$this->plafond->ViewCustomAttributes = "";

		// bunga
		$this->bunga->ViewValue = $this->bunga->CurrentValue;
		$this->bunga->ViewCustomAttributes = "";

		// bungapersen
		$this->bungapersen->ViewValue = $this->bungapersen->CurrentValue;
		$this->bungapersen->ViewCustomAttributes = "";

		// administrasi
		$this->administrasi->ViewValue = $this->administrasi->CurrentValue;
		$this->administrasi->ViewCustomAttributes = "";

		// administrasipersen
		$this->administrasipersen->ViewValue = $this->administrasipersen->CurrentValue;
		$this->administrasipersen->ViewCustomAttributes = "";

		// asuransi
		$this->asuransi->ViewValue = $this->asuransi->CurrentValue;
		$this->asuransi->ViewCustomAttributes = "";

		// notaris
		$this->notaris->ViewValue = $this->notaris->CurrentValue;
		$this->notaris->ViewCustomAttributes = "";

		// biayamaterai
		$this->biayamaterai->ViewValue = $this->biayamaterai->CurrentValue;
		$this->biayamaterai->ViewCustomAttributes = "";

		// potongansaldobeku
		$this->potongansaldobeku->ViewValue = $this->potongansaldobeku->CurrentValue;
		$this->potongansaldobeku->ViewCustomAttributes = "";

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

		// totalterima
		$this->totalterima->ViewValue = $this->totalterima->CurrentValue;
		$this->totalterima->ViewCustomAttributes = "";

		// totalterimaauto
		$this->totalterimaauto->ViewValue = $this->totalterimaauto->CurrentValue;
		$this->totalterimaauto->ViewCustomAttributes = "";

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

			// agunan
			$this->agunan->LinkCustomAttributes = "";
			$this->agunan->HrefValue = "";
			$this->agunan->TooltipValue = "";

			// dataagunan1
			$this->dataagunan1->LinkCustomAttributes = "";
			$this->dataagunan1->HrefValue = "";
			$this->dataagunan1->TooltipValue = "";

			// dataagunan2
			$this->dataagunan2->LinkCustomAttributes = "";
			$this->dataagunan2->HrefValue = "";
			$this->dataagunan2->TooltipValue = "";

			// dataagunan3
			$this->dataagunan3->LinkCustomAttributes = "";
			$this->dataagunan3->HrefValue = "";
			$this->dataagunan3->TooltipValue = "";

			// dataagunan4
			$this->dataagunan4->LinkCustomAttributes = "";
			$this->dataagunan4->HrefValue = "";
			$this->dataagunan4->TooltipValue = "";

			// dataagunan5
			$this->dataagunan5->LinkCustomAttributes = "";
			$this->dataagunan5->HrefValue = "";
			$this->dataagunan5->TooltipValue = "";

			// saldobekusimpanan
			$this->saldobekusimpanan->LinkCustomAttributes = "";
			$this->saldobekusimpanan->HrefValue = "";
			$this->saldobekusimpanan->TooltipValue = "";

			// saldobekuminimal
			$this->saldobekuminimal->LinkCustomAttributes = "";
			$this->saldobekuminimal->HrefValue = "";
			$this->saldobekuminimal->TooltipValue = "";

			// plafond
			$this->plafond->LinkCustomAttributes = "";
			$this->plafond->HrefValue = "";
			$this->plafond->TooltipValue = "";

			// bunga
			$this->bunga->LinkCustomAttributes = "";
			$this->bunga->HrefValue = "";
			$this->bunga->TooltipValue = "";

			// bungapersen
			$this->bungapersen->LinkCustomAttributes = "";
			$this->bungapersen->HrefValue = "";
			$this->bungapersen->TooltipValue = "";

			// administrasi
			$this->administrasi->LinkCustomAttributes = "";
			$this->administrasi->HrefValue = "";
			$this->administrasi->TooltipValue = "";

			// administrasipersen
			$this->administrasipersen->LinkCustomAttributes = "";
			$this->administrasipersen->HrefValue = "";
			$this->administrasipersen->TooltipValue = "";

			// asuransi
			$this->asuransi->LinkCustomAttributes = "";
			$this->asuransi->HrefValue = "";
			$this->asuransi->TooltipValue = "";

			// notaris
			$this->notaris->LinkCustomAttributes = "";
			$this->notaris->HrefValue = "";
			$this->notaris->TooltipValue = "";

			// biayamaterai
			$this->biayamaterai->LinkCustomAttributes = "";
			$this->biayamaterai->HrefValue = "";
			$this->biayamaterai->TooltipValue = "";

			// potongansaldobeku
			$this->potongansaldobeku->LinkCustomAttributes = "";
			$this->potongansaldobeku->HrefValue = "";
			$this->potongansaldobeku->TooltipValue = "";

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

			// totalterima
			$this->totalterima->LinkCustomAttributes = "";
			$this->totalterima->HrefValue = "";
			$this->totalterima->TooltipValue = "";

			// totalterimaauto
			$this->totalterimaauto->LinkCustomAttributes = "";
			$this->totalterimaauto->HrefValue = "";
			$this->totalterimaauto->TooltipValue = "";

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

			// agunan
			$this->agunan->EditAttrs["class"] = "form-control";
			$this->agunan->EditCustomAttributes = "";
			$this->agunan->EditValue = ew_HtmlEncode($this->agunan->AdvancedSearch->SearchValue);
			$this->agunan->PlaceHolder = ew_RemoveHtml($this->agunan->FldCaption());

			// dataagunan1
			$this->dataagunan1->EditAttrs["class"] = "form-control";
			$this->dataagunan1->EditCustomAttributes = "";
			$this->dataagunan1->EditValue = ew_HtmlEncode($this->dataagunan1->AdvancedSearch->SearchValue);
			$this->dataagunan1->PlaceHolder = ew_RemoveHtml($this->dataagunan1->FldCaption());

			// dataagunan2
			$this->dataagunan2->EditAttrs["class"] = "form-control";
			$this->dataagunan2->EditCustomAttributes = "";
			$this->dataagunan2->EditValue = ew_HtmlEncode($this->dataagunan2->AdvancedSearch->SearchValue);
			$this->dataagunan2->PlaceHolder = ew_RemoveHtml($this->dataagunan2->FldCaption());

			// dataagunan3
			$this->dataagunan3->EditAttrs["class"] = "form-control";
			$this->dataagunan3->EditCustomAttributes = "";
			$this->dataagunan3->EditValue = ew_HtmlEncode($this->dataagunan3->AdvancedSearch->SearchValue);
			$this->dataagunan3->PlaceHolder = ew_RemoveHtml($this->dataagunan3->FldCaption());

			// dataagunan4
			$this->dataagunan4->EditAttrs["class"] = "form-control";
			$this->dataagunan4->EditCustomAttributes = "";
			$this->dataagunan4->EditValue = ew_HtmlEncode($this->dataagunan4->AdvancedSearch->SearchValue);
			$this->dataagunan4->PlaceHolder = ew_RemoveHtml($this->dataagunan4->FldCaption());

			// dataagunan5
			$this->dataagunan5->EditAttrs["class"] = "form-control";
			$this->dataagunan5->EditCustomAttributes = "";
			$this->dataagunan5->EditValue = ew_HtmlEncode($this->dataagunan5->AdvancedSearch->SearchValue);
			$this->dataagunan5->PlaceHolder = ew_RemoveHtml($this->dataagunan5->FldCaption());

			// saldobekusimpanan
			$this->saldobekusimpanan->EditAttrs["class"] = "form-control";
			$this->saldobekusimpanan->EditCustomAttributes = "";
			$this->saldobekusimpanan->EditValue = ew_HtmlEncode($this->saldobekusimpanan->AdvancedSearch->SearchValue);
			$this->saldobekusimpanan->PlaceHolder = ew_RemoveHtml($this->saldobekusimpanan->FldCaption());

			// saldobekuminimal
			$this->saldobekuminimal->EditAttrs["class"] = "form-control";
			$this->saldobekuminimal->EditCustomAttributes = "";
			$this->saldobekuminimal->EditValue = ew_HtmlEncode($this->saldobekuminimal->AdvancedSearch->SearchValue);
			$this->saldobekuminimal->PlaceHolder = ew_RemoveHtml($this->saldobekuminimal->FldCaption());

			// plafond
			$this->plafond->EditAttrs["class"] = "form-control";
			$this->plafond->EditCustomAttributes = "";
			$this->plafond->EditValue = ew_HtmlEncode($this->plafond->AdvancedSearch->SearchValue);
			$this->plafond->PlaceHolder = ew_RemoveHtml($this->plafond->FldCaption());

			// bunga
			$this->bunga->EditAttrs["class"] = "form-control";
			$this->bunga->EditCustomAttributes = "";
			$this->bunga->EditValue = ew_HtmlEncode($this->bunga->AdvancedSearch->SearchValue);
			$this->bunga->PlaceHolder = ew_RemoveHtml($this->bunga->FldCaption());

			// bungapersen
			$this->bungapersen->EditAttrs["class"] = "form-control";
			$this->bungapersen->EditCustomAttributes = "";
			$this->bungapersen->EditValue = ew_HtmlEncode($this->bungapersen->AdvancedSearch->SearchValue);
			$this->bungapersen->PlaceHolder = ew_RemoveHtml($this->bungapersen->FldCaption());

			// administrasi
			$this->administrasi->EditAttrs["class"] = "form-control";
			$this->administrasi->EditCustomAttributes = "";
			$this->administrasi->EditValue = ew_HtmlEncode($this->administrasi->AdvancedSearch->SearchValue);
			$this->administrasi->PlaceHolder = ew_RemoveHtml($this->administrasi->FldCaption());

			// administrasipersen
			$this->administrasipersen->EditAttrs["class"] = "form-control";
			$this->administrasipersen->EditCustomAttributes = "";
			$this->administrasipersen->EditValue = ew_HtmlEncode($this->administrasipersen->AdvancedSearch->SearchValue);
			$this->administrasipersen->PlaceHolder = ew_RemoveHtml($this->administrasipersen->FldCaption());

			// asuransi
			$this->asuransi->EditAttrs["class"] = "form-control";
			$this->asuransi->EditCustomAttributes = "";
			$this->asuransi->EditValue = ew_HtmlEncode($this->asuransi->AdvancedSearch->SearchValue);
			$this->asuransi->PlaceHolder = ew_RemoveHtml($this->asuransi->FldCaption());

			// notaris
			$this->notaris->EditAttrs["class"] = "form-control";
			$this->notaris->EditCustomAttributes = "";
			$this->notaris->EditValue = ew_HtmlEncode($this->notaris->AdvancedSearch->SearchValue);
			$this->notaris->PlaceHolder = ew_RemoveHtml($this->notaris->FldCaption());

			// biayamaterai
			$this->biayamaterai->EditAttrs["class"] = "form-control";
			$this->biayamaterai->EditCustomAttributes = "";
			$this->biayamaterai->EditValue = ew_HtmlEncode($this->biayamaterai->AdvancedSearch->SearchValue);
			$this->biayamaterai->PlaceHolder = ew_RemoveHtml($this->biayamaterai->FldCaption());

			// potongansaldobeku
			$this->potongansaldobeku->EditAttrs["class"] = "form-control";
			$this->potongansaldobeku->EditCustomAttributes = "";
			$this->potongansaldobeku->EditValue = ew_HtmlEncode($this->potongansaldobeku->AdvancedSearch->SearchValue);
			$this->potongansaldobeku->PlaceHolder = ew_RemoveHtml($this->potongansaldobeku->FldCaption());

			// angsuranpokok
			$this->angsuranpokok->EditAttrs["class"] = "form-control";
			$this->angsuranpokok->EditCustomAttributes = "";
			$this->angsuranpokok->EditValue = ew_HtmlEncode($this->angsuranpokok->AdvancedSearch->SearchValue);
			$this->angsuranpokok->PlaceHolder = ew_RemoveHtml($this->angsuranpokok->FldCaption());

			// angsuranpokokauto
			$this->angsuranpokokauto->EditAttrs["class"] = "form-control";
			$this->angsuranpokokauto->EditCustomAttributes = "";
			$this->angsuranpokokauto->EditValue = ew_HtmlEncode($this->angsuranpokokauto->AdvancedSearch->SearchValue);
			$this->angsuranpokokauto->PlaceHolder = ew_RemoveHtml($this->angsuranpokokauto->FldCaption());

			// angsuranbunga
			$this->angsuranbunga->EditAttrs["class"] = "form-control";
			$this->angsuranbunga->EditCustomAttributes = "";
			$this->angsuranbunga->EditValue = ew_HtmlEncode($this->angsuranbunga->AdvancedSearch->SearchValue);
			$this->angsuranbunga->PlaceHolder = ew_RemoveHtml($this->angsuranbunga->FldCaption());

			// angsuranbungaauto
			$this->angsuranbungaauto->EditAttrs["class"] = "form-control";
			$this->angsuranbungaauto->EditCustomAttributes = "";
			$this->angsuranbungaauto->EditValue = ew_HtmlEncode($this->angsuranbungaauto->AdvancedSearch->SearchValue);
			$this->angsuranbungaauto->PlaceHolder = ew_RemoveHtml($this->angsuranbungaauto->FldCaption());

			// denda
			$this->denda->EditAttrs["class"] = "form-control";
			$this->denda->EditCustomAttributes = "";
			$this->denda->EditValue = ew_HtmlEncode($this->denda->AdvancedSearch->SearchValue);
			$this->denda->PlaceHolder = ew_RemoveHtml($this->denda->FldCaption());

			// dendapersen
			$this->dendapersen->EditAttrs["class"] = "form-control";
			$this->dendapersen->EditCustomAttributes = "";
			$this->dendapersen->EditValue = ew_HtmlEncode($this->dendapersen->AdvancedSearch->SearchValue);
			$this->dendapersen->PlaceHolder = ew_RemoveHtml($this->dendapersen->FldCaption());

			// totalangsuran
			$this->totalangsuran->EditAttrs["class"] = "form-control";
			$this->totalangsuran->EditCustomAttributes = "";
			$this->totalangsuran->EditValue = ew_HtmlEncode($this->totalangsuran->AdvancedSearch->SearchValue);
			$this->totalangsuran->PlaceHolder = ew_RemoveHtml($this->totalangsuran->FldCaption());

			// totalangsuranauto
			$this->totalangsuranauto->EditAttrs["class"] = "form-control";
			$this->totalangsuranauto->EditCustomAttributes = "";
			$this->totalangsuranauto->EditValue = ew_HtmlEncode($this->totalangsuranauto->AdvancedSearch->SearchValue);
			$this->totalangsuranauto->PlaceHolder = ew_RemoveHtml($this->totalangsuranauto->FldCaption());

			// totalterima
			$this->totalterima->EditAttrs["class"] = "form-control";
			$this->totalterima->EditCustomAttributes = "";
			$this->totalterima->EditValue = ew_HtmlEncode($this->totalterima->AdvancedSearch->SearchValue);
			$this->totalterima->PlaceHolder = ew_RemoveHtml($this->totalterima->FldCaption());

			// totalterimaauto
			$this->totalterimaauto->EditAttrs["class"] = "form-control";
			$this->totalterimaauto->EditCustomAttributes = "";
			$this->totalterimaauto->EditValue = ew_HtmlEncode($this->totalterimaauto->AdvancedSearch->SearchValue);
			$this->totalterimaauto->PlaceHolder = ew_RemoveHtml($this->totalterimaauto->FldCaption());

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
		$this->agunan->AdvancedSearch->Load();
		$this->dataagunan1->AdvancedSearch->Load();
		$this->dataagunan2->AdvancedSearch->Load();
		$this->dataagunan3->AdvancedSearch->Load();
		$this->dataagunan4->AdvancedSearch->Load();
		$this->dataagunan5->AdvancedSearch->Load();
		$this->saldobekusimpanan->AdvancedSearch->Load();
		$this->saldobekuminimal->AdvancedSearch->Load();
		$this->plafond->AdvancedSearch->Load();
		$this->bunga->AdvancedSearch->Load();
		$this->bungapersen->AdvancedSearch->Load();
		$this->administrasi->AdvancedSearch->Load();
		$this->administrasipersen->AdvancedSearch->Load();
		$this->asuransi->AdvancedSearch->Load();
		$this->notaris->AdvancedSearch->Load();
		$this->biayamaterai->AdvancedSearch->Load();
		$this->potongansaldobeku->AdvancedSearch->Load();
		$this->angsuranpokok->AdvancedSearch->Load();
		$this->angsuranpokokauto->AdvancedSearch->Load();
		$this->angsuranbunga->AdvancedSearch->Load();
		$this->angsuranbungaauto->AdvancedSearch->Load();
		$this->denda->AdvancedSearch->Load();
		$this->dendapersen->AdvancedSearch->Load();
		$this->totalangsuran->AdvancedSearch->Load();
		$this->totalangsuranauto->AdvancedSearch->Load();
		$this->totalterima->AdvancedSearch->Load();
		$this->totalterimaauto->AdvancedSearch->Load();
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
if (!isset($tpinjaman_list)) $tpinjaman_list = new ctpinjaman_list();

// Page init
$tpinjaman_list->Page_Init();

// Page main
$tpinjaman_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tpinjaman_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = ftpinjamanlist = new ew_Form("ftpinjamanlist", "list");
ftpinjamanlist.FormKeyCountName = '<?php echo $tpinjaman_list->FormKeyCountName ?>';

// Form_CustomValidate event
ftpinjamanlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftpinjamanlist.ValidateRequired = true;
<?php } else { ?>
ftpinjamanlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftpinjamanlist.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftpinjamanlist.Lists["x_active"].Options = <?php echo json_encode($tpinjaman->active->Options()) ?>;

// Form object for search
var CurrentSearchForm = ftpinjamanlistsrch = new ew_Form("ftpinjamanlistsrch");

// Validate function for search
ftpinjamanlistsrch.Validate = function(fobj) {
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
ftpinjamanlistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftpinjamanlistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
ftpinjamanlistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
ftpinjamanlistsrch.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftpinjamanlistsrch.Lists["x_active"].Options = <?php echo json_encode($tpinjaman->active->Options()) ?>;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($tpinjaman_list->TotalRecs > 0 && $tpinjaman_list->ExportOptions->Visible()) { ?>
<?php $tpinjaman_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($tpinjaman_list->SearchOptions->Visible()) { ?>
<?php $tpinjaman_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($tpinjaman_list->FilterOptions->Visible()) { ?>
<?php $tpinjaman_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $tpinjaman_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($tpinjaman_list->TotalRecs <= 0)
			$tpinjaman_list->TotalRecs = $tpinjaman->SelectRecordCount();
	} else {
		if (!$tpinjaman_list->Recordset && ($tpinjaman_list->Recordset = $tpinjaman_list->LoadRecordset()))
			$tpinjaman_list->TotalRecs = $tpinjaman_list->Recordset->RecordCount();
	}
	$tpinjaman_list->StartRec = 1;
	if ($tpinjaman_list->DisplayRecs <= 0 || ($tpinjaman->Export <> "" && $tpinjaman->ExportAll)) // Display all records
		$tpinjaman_list->DisplayRecs = $tpinjaman_list->TotalRecs;
	if (!($tpinjaman->Export <> "" && $tpinjaman->ExportAll))
		$tpinjaman_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$tpinjaman_list->Recordset = $tpinjaman_list->LoadRecordset($tpinjaman_list->StartRec-1, $tpinjaman_list->DisplayRecs);

	// Set no record found message
	if ($tpinjaman->CurrentAction == "" && $tpinjaman_list->TotalRecs == 0) {
		if ($tpinjaman_list->SearchWhere == "0=101")
			$tpinjaman_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$tpinjaman_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$tpinjaman_list->RenderOtherOptions();
?>
<?php if ($tpinjaman->Export == "" && $tpinjaman->CurrentAction == "") { ?>
<form name="ftpinjamanlistsrch" id="ftpinjamanlistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($tpinjaman_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="ftpinjamanlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="tpinjaman">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$tpinjaman_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$tpinjaman->RowType = EW_ROWTYPE_SEARCH;

// Render row
$tpinjaman->ResetAttrs();
$tpinjaman_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($tpinjaman->active->Visible) { // active ?>
	<div id="xsc_active" class="ewCell form-group">
		<label class="ewSearchCaption ewLabel"><?php echo $tpinjaman->active->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_active" id="z_active" value="="></span>
		<span class="ewSearchField">
<div id="tp_x_active" class="ewTemplate"><input type="radio" data-table="tpinjaman" data-field="x_active" data-value-separator="<?php echo $tpinjaman->active->DisplayValueSeparatorAttribute() ?>" name="x_active" id="x_active" value="{value}"<?php echo $tpinjaman->active->EditAttributes() ?>></div>
<div id="dsl_x_active" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $tpinjaman->active->RadioButtonListHtml(FALSE, "x_active") ?>
</div></div>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($tpinjaman_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($tpinjaman_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $tpinjaman_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($tpinjaman_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($tpinjaman_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($tpinjaman_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($tpinjaman_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php $tpinjaman_list->ShowPageHeader(); ?>
<?php
$tpinjaman_list->ShowMessage();
?>
<?php if ($tpinjaman_list->TotalRecs > 0 || $tpinjaman->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid tpinjaman">
<form name="ftpinjamanlist" id="ftpinjamanlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tpinjaman_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tpinjaman_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tpinjaman">
<div id="gmp_tpinjaman" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($tpinjaman_list->TotalRecs > 0 || $tpinjaman->CurrentAction == "gridedit") { ?>
<table id="tbl_tpinjamanlist" class="table ewTable">
<?php echo $tpinjaman->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$tpinjaman_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$tpinjaman_list->RenderListOptions();

// Render list options (header, left)
$tpinjaman_list->ListOptions->Render("header", "left");
?>
<?php if ($tpinjaman->tanggal->Visible) { // tanggal ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->tanggal) == "") { ?>
		<th data-name="tanggal"><div id="elh_tpinjaman_tanggal" class="tpinjaman_tanggal"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->tanggal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tanggal"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->tanggal) ?>',1);"><div id="elh_tpinjaman_tanggal" class="tpinjaman_tanggal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->tanggal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->tanggal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->tanggal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->periode->Visible) { // periode ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->periode) == "") { ?>
		<th data-name="periode"><div id="elh_tpinjaman_periode" class="tpinjaman_periode"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->periode->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="periode"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->periode) ?>',1);"><div id="elh_tpinjaman_periode" class="tpinjaman_periode">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->periode->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->periode->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->periode->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->id->Visible) { // id ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->id) == "") { ?>
		<th data-name="id"><div id="elh_tpinjaman_id" class="tpinjaman_id"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->id) ?>',1);"><div id="elh_tpinjaman_id" class="tpinjaman_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->id->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->transaksi->Visible) { // transaksi ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->transaksi) == "") { ?>
		<th data-name="transaksi"><div id="elh_tpinjaman_transaksi" class="tpinjaman_transaksi"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->transaksi->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="transaksi"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->transaksi) ?>',1);"><div id="elh_tpinjaman_transaksi" class="tpinjaman_transaksi">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->transaksi->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->transaksi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->transaksi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->referensi->Visible) { // referensi ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->referensi) == "") { ?>
		<th data-name="referensi"><div id="elh_tpinjaman_referensi" class="tpinjaman_referensi"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->referensi->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="referensi"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->referensi) ?>',1);"><div id="elh_tpinjaman_referensi" class="tpinjaman_referensi">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->referensi->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->referensi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->referensi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->anggota->Visible) { // anggota ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->anggota) == "") { ?>
		<th data-name="anggota"><div id="elh_tpinjaman_anggota" class="tpinjaman_anggota"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->anggota->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="anggota"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->anggota) ?>',1);"><div id="elh_tpinjaman_anggota" class="tpinjaman_anggota">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->anggota->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->anggota->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->anggota->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->namaanggota->Visible) { // namaanggota ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->namaanggota) == "") { ?>
		<th data-name="namaanggota"><div id="elh_tpinjaman_namaanggota" class="tpinjaman_namaanggota"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->namaanggota->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="namaanggota"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->namaanggota) ?>',1);"><div id="elh_tpinjaman_namaanggota" class="tpinjaman_namaanggota">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->namaanggota->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->namaanggota->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->namaanggota->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->alamat->Visible) { // alamat ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->alamat) == "") { ?>
		<th data-name="alamat"><div id="elh_tpinjaman_alamat" class="tpinjaman_alamat"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->alamat->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="alamat"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->alamat) ?>',1);"><div id="elh_tpinjaman_alamat" class="tpinjaman_alamat">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->alamat->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->alamat->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->alamat->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->pekerjaan->Visible) { // pekerjaan ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->pekerjaan) == "") { ?>
		<th data-name="pekerjaan"><div id="elh_tpinjaman_pekerjaan" class="tpinjaman_pekerjaan"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->pekerjaan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pekerjaan"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->pekerjaan) ?>',1);"><div id="elh_tpinjaman_pekerjaan" class="tpinjaman_pekerjaan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->pekerjaan->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->pekerjaan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->pekerjaan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->telepon->Visible) { // telepon ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->telepon) == "") { ?>
		<th data-name="telepon"><div id="elh_tpinjaman_telepon" class="tpinjaman_telepon"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->telepon->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="telepon"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->telepon) ?>',1);"><div id="elh_tpinjaman_telepon" class="tpinjaman_telepon">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->telepon->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->telepon->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->telepon->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->hp->Visible) { // hp ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->hp) == "") { ?>
		<th data-name="hp"><div id="elh_tpinjaman_hp" class="tpinjaman_hp"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->hp->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="hp"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->hp) ?>',1);"><div id="elh_tpinjaman_hp" class="tpinjaman_hp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->hp->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->hp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->hp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->fax->Visible) { // fax ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->fax) == "") { ?>
		<th data-name="fax"><div id="elh_tpinjaman_fax" class="tpinjaman_fax"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->fax->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fax"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->fax) ?>',1);"><div id="elh_tpinjaman_fax" class="tpinjaman_fax">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->fax->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->fax->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->fax->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->_email->Visible) { // email ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->_email) == "") { ?>
		<th data-name="_email"><div id="elh_tpinjaman__email" class="tpinjaman__email"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->_email->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_email"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->_email) ?>',1);"><div id="elh_tpinjaman__email" class="tpinjaman__email">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->_email->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->_email->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->_email->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->website->Visible) { // website ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->website) == "") { ?>
		<th data-name="website"><div id="elh_tpinjaman_website" class="tpinjaman_website"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->website->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="website"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->website) ?>',1);"><div id="elh_tpinjaman_website" class="tpinjaman_website">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->website->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->website->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->website->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->jenisanggota->Visible) { // jenisanggota ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->jenisanggota) == "") { ?>
		<th data-name="jenisanggota"><div id="elh_tpinjaman_jenisanggota" class="tpinjaman_jenisanggota"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->jenisanggota->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jenisanggota"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->jenisanggota) ?>',1);"><div id="elh_tpinjaman_jenisanggota" class="tpinjaman_jenisanggota">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->jenisanggota->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->jenisanggota->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->jenisanggota->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->model->Visible) { // model ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->model) == "") { ?>
		<th data-name="model"><div id="elh_tpinjaman_model" class="tpinjaman_model"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->model->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="model"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->model) ?>',1);"><div id="elh_tpinjaman_model" class="tpinjaman_model">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->model->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->model->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->model->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->jenispinjaman->Visible) { // jenispinjaman ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->jenispinjaman) == "") { ?>
		<th data-name="jenispinjaman"><div id="elh_tpinjaman_jenispinjaman" class="tpinjaman_jenispinjaman"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->jenispinjaman->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jenispinjaman"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->jenispinjaman) ?>',1);"><div id="elh_tpinjaman_jenispinjaman" class="tpinjaman_jenispinjaman">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->jenispinjaman->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->jenispinjaman->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->jenispinjaman->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->jenisbunga->Visible) { // jenisbunga ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->jenisbunga) == "") { ?>
		<th data-name="jenisbunga"><div id="elh_tpinjaman_jenisbunga" class="tpinjaman_jenisbunga"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->jenisbunga->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jenisbunga"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->jenisbunga) ?>',1);"><div id="elh_tpinjaman_jenisbunga" class="tpinjaman_jenisbunga">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->jenisbunga->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->jenisbunga->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->jenisbunga->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->angsuran->Visible) { // angsuran ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->angsuran) == "") { ?>
		<th data-name="angsuran"><div id="elh_tpinjaman_angsuran" class="tpinjaman_angsuran"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->angsuran->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="angsuran"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->angsuran) ?>',1);"><div id="elh_tpinjaman_angsuran" class="tpinjaman_angsuran">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->angsuran->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->angsuran->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->angsuran->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->masaangsuran->Visible) { // masaangsuran ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->masaangsuran) == "") { ?>
		<th data-name="masaangsuran"><div id="elh_tpinjaman_masaangsuran" class="tpinjaman_masaangsuran"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->masaangsuran->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="masaangsuran"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->masaangsuran) ?>',1);"><div id="elh_tpinjaman_masaangsuran" class="tpinjaman_masaangsuran">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->masaangsuran->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->masaangsuran->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->masaangsuran->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->jatuhtempo->Visible) { // jatuhtempo ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->jatuhtempo) == "") { ?>
		<th data-name="jatuhtempo"><div id="elh_tpinjaman_jatuhtempo" class="tpinjaman_jatuhtempo"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->jatuhtempo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jatuhtempo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->jatuhtempo) ?>',1);"><div id="elh_tpinjaman_jatuhtempo" class="tpinjaman_jatuhtempo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->jatuhtempo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->jatuhtempo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->jatuhtempo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->dispensasidenda->Visible) { // dispensasidenda ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->dispensasidenda) == "") { ?>
		<th data-name="dispensasidenda"><div id="elh_tpinjaman_dispensasidenda" class="tpinjaman_dispensasidenda"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->dispensasidenda->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="dispensasidenda"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->dispensasidenda) ?>',1);"><div id="elh_tpinjaman_dispensasidenda" class="tpinjaman_dispensasidenda">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->dispensasidenda->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->dispensasidenda->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->dispensasidenda->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->agunan->Visible) { // agunan ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->agunan) == "") { ?>
		<th data-name="agunan"><div id="elh_tpinjaman_agunan" class="tpinjaman_agunan"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->agunan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="agunan"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->agunan) ?>',1);"><div id="elh_tpinjaman_agunan" class="tpinjaman_agunan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->agunan->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->agunan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->agunan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->dataagunan1->Visible) { // dataagunan1 ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->dataagunan1) == "") { ?>
		<th data-name="dataagunan1"><div id="elh_tpinjaman_dataagunan1" class="tpinjaman_dataagunan1"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->dataagunan1->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="dataagunan1"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->dataagunan1) ?>',1);"><div id="elh_tpinjaman_dataagunan1" class="tpinjaman_dataagunan1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->dataagunan1->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->dataagunan1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->dataagunan1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->dataagunan2->Visible) { // dataagunan2 ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->dataagunan2) == "") { ?>
		<th data-name="dataagunan2"><div id="elh_tpinjaman_dataagunan2" class="tpinjaman_dataagunan2"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->dataagunan2->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="dataagunan2"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->dataagunan2) ?>',1);"><div id="elh_tpinjaman_dataagunan2" class="tpinjaman_dataagunan2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->dataagunan2->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->dataagunan2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->dataagunan2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->dataagunan3->Visible) { // dataagunan3 ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->dataagunan3) == "") { ?>
		<th data-name="dataagunan3"><div id="elh_tpinjaman_dataagunan3" class="tpinjaman_dataagunan3"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->dataagunan3->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="dataagunan3"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->dataagunan3) ?>',1);"><div id="elh_tpinjaman_dataagunan3" class="tpinjaman_dataagunan3">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->dataagunan3->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->dataagunan3->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->dataagunan3->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->dataagunan4->Visible) { // dataagunan4 ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->dataagunan4) == "") { ?>
		<th data-name="dataagunan4"><div id="elh_tpinjaman_dataagunan4" class="tpinjaman_dataagunan4"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->dataagunan4->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="dataagunan4"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->dataagunan4) ?>',1);"><div id="elh_tpinjaman_dataagunan4" class="tpinjaman_dataagunan4">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->dataagunan4->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->dataagunan4->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->dataagunan4->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->dataagunan5->Visible) { // dataagunan5 ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->dataagunan5) == "") { ?>
		<th data-name="dataagunan5"><div id="elh_tpinjaman_dataagunan5" class="tpinjaman_dataagunan5"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->dataagunan5->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="dataagunan5"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->dataagunan5) ?>',1);"><div id="elh_tpinjaman_dataagunan5" class="tpinjaman_dataagunan5">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->dataagunan5->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->dataagunan5->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->dataagunan5->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->saldobekusimpanan->Visible) { // saldobekusimpanan ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->saldobekusimpanan) == "") { ?>
		<th data-name="saldobekusimpanan"><div id="elh_tpinjaman_saldobekusimpanan" class="tpinjaman_saldobekusimpanan"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->saldobekusimpanan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="saldobekusimpanan"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->saldobekusimpanan) ?>',1);"><div id="elh_tpinjaman_saldobekusimpanan" class="tpinjaman_saldobekusimpanan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->saldobekusimpanan->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->saldobekusimpanan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->saldobekusimpanan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->saldobekuminimal->Visible) { // saldobekuminimal ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->saldobekuminimal) == "") { ?>
		<th data-name="saldobekuminimal"><div id="elh_tpinjaman_saldobekuminimal" class="tpinjaman_saldobekuminimal"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->saldobekuminimal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="saldobekuminimal"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->saldobekuminimal) ?>',1);"><div id="elh_tpinjaman_saldobekuminimal" class="tpinjaman_saldobekuminimal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->saldobekuminimal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->saldobekuminimal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->saldobekuminimal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->plafond->Visible) { // plafond ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->plafond) == "") { ?>
		<th data-name="plafond"><div id="elh_tpinjaman_plafond" class="tpinjaman_plafond"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->plafond->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="plafond"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->plafond) ?>',1);"><div id="elh_tpinjaman_plafond" class="tpinjaman_plafond">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->plafond->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->plafond->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->plafond->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->bunga->Visible) { // bunga ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->bunga) == "") { ?>
		<th data-name="bunga"><div id="elh_tpinjaman_bunga" class="tpinjaman_bunga"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->bunga->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bunga"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->bunga) ?>',1);"><div id="elh_tpinjaman_bunga" class="tpinjaman_bunga">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->bunga->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->bunga->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->bunga->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->bungapersen->Visible) { // bungapersen ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->bungapersen) == "") { ?>
		<th data-name="bungapersen"><div id="elh_tpinjaman_bungapersen" class="tpinjaman_bungapersen"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->bungapersen->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bungapersen"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->bungapersen) ?>',1);"><div id="elh_tpinjaman_bungapersen" class="tpinjaman_bungapersen">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->bungapersen->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->bungapersen->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->bungapersen->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->administrasi->Visible) { // administrasi ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->administrasi) == "") { ?>
		<th data-name="administrasi"><div id="elh_tpinjaman_administrasi" class="tpinjaman_administrasi"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->administrasi->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="administrasi"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->administrasi) ?>',1);"><div id="elh_tpinjaman_administrasi" class="tpinjaman_administrasi">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->administrasi->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->administrasi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->administrasi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->administrasipersen->Visible) { // administrasipersen ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->administrasipersen) == "") { ?>
		<th data-name="administrasipersen"><div id="elh_tpinjaman_administrasipersen" class="tpinjaman_administrasipersen"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->administrasipersen->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="administrasipersen"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->administrasipersen) ?>',1);"><div id="elh_tpinjaman_administrasipersen" class="tpinjaman_administrasipersen">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->administrasipersen->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->administrasipersen->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->administrasipersen->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->asuransi->Visible) { // asuransi ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->asuransi) == "") { ?>
		<th data-name="asuransi"><div id="elh_tpinjaman_asuransi" class="tpinjaman_asuransi"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->asuransi->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="asuransi"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->asuransi) ?>',1);"><div id="elh_tpinjaman_asuransi" class="tpinjaman_asuransi">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->asuransi->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->asuransi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->asuransi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->notaris->Visible) { // notaris ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->notaris) == "") { ?>
		<th data-name="notaris"><div id="elh_tpinjaman_notaris" class="tpinjaman_notaris"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->notaris->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="notaris"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->notaris) ?>',1);"><div id="elh_tpinjaman_notaris" class="tpinjaman_notaris">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->notaris->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->notaris->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->notaris->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->biayamaterai->Visible) { // biayamaterai ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->biayamaterai) == "") { ?>
		<th data-name="biayamaterai"><div id="elh_tpinjaman_biayamaterai" class="tpinjaman_biayamaterai"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->biayamaterai->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="biayamaterai"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->biayamaterai) ?>',1);"><div id="elh_tpinjaman_biayamaterai" class="tpinjaman_biayamaterai">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->biayamaterai->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->biayamaterai->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->biayamaterai->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->potongansaldobeku->Visible) { // potongansaldobeku ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->potongansaldobeku) == "") { ?>
		<th data-name="potongansaldobeku"><div id="elh_tpinjaman_potongansaldobeku" class="tpinjaman_potongansaldobeku"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->potongansaldobeku->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="potongansaldobeku"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->potongansaldobeku) ?>',1);"><div id="elh_tpinjaman_potongansaldobeku" class="tpinjaman_potongansaldobeku">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->potongansaldobeku->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->potongansaldobeku->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->potongansaldobeku->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->angsuranpokok->Visible) { // angsuranpokok ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->angsuranpokok) == "") { ?>
		<th data-name="angsuranpokok"><div id="elh_tpinjaman_angsuranpokok" class="tpinjaman_angsuranpokok"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->angsuranpokok->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="angsuranpokok"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->angsuranpokok) ?>',1);"><div id="elh_tpinjaman_angsuranpokok" class="tpinjaman_angsuranpokok">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->angsuranpokok->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->angsuranpokok->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->angsuranpokok->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->angsuranpokokauto->Visible) { // angsuranpokokauto ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->angsuranpokokauto) == "") { ?>
		<th data-name="angsuranpokokauto"><div id="elh_tpinjaman_angsuranpokokauto" class="tpinjaman_angsuranpokokauto"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->angsuranpokokauto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="angsuranpokokauto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->angsuranpokokauto) ?>',1);"><div id="elh_tpinjaman_angsuranpokokauto" class="tpinjaman_angsuranpokokauto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->angsuranpokokauto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->angsuranpokokauto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->angsuranpokokauto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->angsuranbunga->Visible) { // angsuranbunga ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->angsuranbunga) == "") { ?>
		<th data-name="angsuranbunga"><div id="elh_tpinjaman_angsuranbunga" class="tpinjaman_angsuranbunga"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->angsuranbunga->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="angsuranbunga"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->angsuranbunga) ?>',1);"><div id="elh_tpinjaman_angsuranbunga" class="tpinjaman_angsuranbunga">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->angsuranbunga->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->angsuranbunga->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->angsuranbunga->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->angsuranbungaauto->Visible) { // angsuranbungaauto ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->angsuranbungaauto) == "") { ?>
		<th data-name="angsuranbungaauto"><div id="elh_tpinjaman_angsuranbungaauto" class="tpinjaman_angsuranbungaauto"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->angsuranbungaauto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="angsuranbungaauto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->angsuranbungaauto) ?>',1);"><div id="elh_tpinjaman_angsuranbungaauto" class="tpinjaman_angsuranbungaauto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->angsuranbungaauto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->angsuranbungaauto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->angsuranbungaauto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->denda->Visible) { // denda ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->denda) == "") { ?>
		<th data-name="denda"><div id="elh_tpinjaman_denda" class="tpinjaman_denda"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->denda->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="denda"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->denda) ?>',1);"><div id="elh_tpinjaman_denda" class="tpinjaman_denda">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->denda->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->denda->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->denda->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->dendapersen->Visible) { // dendapersen ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->dendapersen) == "") { ?>
		<th data-name="dendapersen"><div id="elh_tpinjaman_dendapersen" class="tpinjaman_dendapersen"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->dendapersen->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="dendapersen"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->dendapersen) ?>',1);"><div id="elh_tpinjaman_dendapersen" class="tpinjaman_dendapersen">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->dendapersen->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->dendapersen->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->dendapersen->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->totalangsuran->Visible) { // totalangsuran ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->totalangsuran) == "") { ?>
		<th data-name="totalangsuran"><div id="elh_tpinjaman_totalangsuran" class="tpinjaman_totalangsuran"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->totalangsuran->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="totalangsuran"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->totalangsuran) ?>',1);"><div id="elh_tpinjaman_totalangsuran" class="tpinjaman_totalangsuran">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->totalangsuran->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->totalangsuran->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->totalangsuran->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->totalangsuranauto->Visible) { // totalangsuranauto ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->totalangsuranauto) == "") { ?>
		<th data-name="totalangsuranauto"><div id="elh_tpinjaman_totalangsuranauto" class="tpinjaman_totalangsuranauto"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->totalangsuranauto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="totalangsuranauto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->totalangsuranauto) ?>',1);"><div id="elh_tpinjaman_totalangsuranauto" class="tpinjaman_totalangsuranauto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->totalangsuranauto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->totalangsuranauto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->totalangsuranauto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->totalterima->Visible) { // totalterima ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->totalterima) == "") { ?>
		<th data-name="totalterima"><div id="elh_tpinjaman_totalterima" class="tpinjaman_totalterima"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->totalterima->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="totalterima"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->totalterima) ?>',1);"><div id="elh_tpinjaman_totalterima" class="tpinjaman_totalterima">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->totalterima->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->totalterima->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->totalterima->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->totalterimaauto->Visible) { // totalterimaauto ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->totalterimaauto) == "") { ?>
		<th data-name="totalterimaauto"><div id="elh_tpinjaman_totalterimaauto" class="tpinjaman_totalterimaauto"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->totalterimaauto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="totalterimaauto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->totalterimaauto) ?>',1);"><div id="elh_tpinjaman_totalterimaauto" class="tpinjaman_totalterimaauto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->totalterimaauto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->totalterimaauto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->totalterimaauto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->terbilang->Visible) { // terbilang ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->terbilang) == "") { ?>
		<th data-name="terbilang"><div id="elh_tpinjaman_terbilang" class="tpinjaman_terbilang"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->terbilang->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="terbilang"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->terbilang) ?>',1);"><div id="elh_tpinjaman_terbilang" class="tpinjaman_terbilang">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->terbilang->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->terbilang->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->terbilang->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->petugas->Visible) { // petugas ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->petugas) == "") { ?>
		<th data-name="petugas"><div id="elh_tpinjaman_petugas" class="tpinjaman_petugas"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->petugas->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="petugas"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->petugas) ?>',1);"><div id="elh_tpinjaman_petugas" class="tpinjaman_petugas">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->petugas->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->petugas->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->petugas->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->pembayaran->Visible) { // pembayaran ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->pembayaran) == "") { ?>
		<th data-name="pembayaran"><div id="elh_tpinjaman_pembayaran" class="tpinjaman_pembayaran"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->pembayaran->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pembayaran"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->pembayaran) ?>',1);"><div id="elh_tpinjaman_pembayaran" class="tpinjaman_pembayaran">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->pembayaran->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->pembayaran->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->pembayaran->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->bank->Visible) { // bank ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->bank) == "") { ?>
		<th data-name="bank"><div id="elh_tpinjaman_bank" class="tpinjaman_bank"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->bank->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bank"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->bank) ?>',1);"><div id="elh_tpinjaman_bank" class="tpinjaman_bank">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->bank->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->bank->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->bank->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->atasnama->Visible) { // atasnama ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->atasnama) == "") { ?>
		<th data-name="atasnama"><div id="elh_tpinjaman_atasnama" class="tpinjaman_atasnama"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->atasnama->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="atasnama"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->atasnama) ?>',1);"><div id="elh_tpinjaman_atasnama" class="tpinjaman_atasnama">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->atasnama->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->atasnama->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->atasnama->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->tipe->Visible) { // tipe ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->tipe) == "") { ?>
		<th data-name="tipe"><div id="elh_tpinjaman_tipe" class="tpinjaman_tipe"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->tipe->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipe"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->tipe) ?>',1);"><div id="elh_tpinjaman_tipe" class="tpinjaman_tipe">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->tipe->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->tipe->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->tipe->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->kantor->Visible) { // kantor ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->kantor) == "") { ?>
		<th data-name="kantor"><div id="elh_tpinjaman_kantor" class="tpinjaman_kantor"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->kantor->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="kantor"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->kantor) ?>',1);"><div id="elh_tpinjaman_kantor" class="tpinjaman_kantor">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->kantor->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->kantor->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->kantor->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->keterangan->Visible) { // keterangan ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->keterangan) == "") { ?>
		<th data-name="keterangan"><div id="elh_tpinjaman_keterangan" class="tpinjaman_keterangan"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->keterangan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="keterangan"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->keterangan) ?>',1);"><div id="elh_tpinjaman_keterangan" class="tpinjaman_keterangan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->keterangan->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->keterangan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->keterangan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->active->Visible) { // active ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->active) == "") { ?>
		<th data-name="active"><div id="elh_tpinjaman_active" class="tpinjaman_active"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->active->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="active"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->active) ?>',1);"><div id="elh_tpinjaman_active" class="tpinjaman_active">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->active->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->active->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->active->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->ip->Visible) { // ip ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->ip) == "") { ?>
		<th data-name="ip"><div id="elh_tpinjaman_ip" class="tpinjaman_ip"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->ip->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ip"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->ip) ?>',1);"><div id="elh_tpinjaman_ip" class="tpinjaman_ip">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->ip->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->ip->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->ip->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->status->Visible) { // status ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->status) == "") { ?>
		<th data-name="status"><div id="elh_tpinjaman_status" class="tpinjaman_status"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->status->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->status) ?>',1);"><div id="elh_tpinjaman_status" class="tpinjaman_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->status->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->user->Visible) { // user ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->user) == "") { ?>
		<th data-name="user"><div id="elh_tpinjaman_user" class="tpinjaman_user"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->user->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="user"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->user) ?>',1);"><div id="elh_tpinjaman_user" class="tpinjaman_user">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->user->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->user->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->user->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->created->Visible) { // created ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->created) == "") { ?>
		<th data-name="created"><div id="elh_tpinjaman_created" class="tpinjaman_created"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->created->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="created"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->created) ?>',1);"><div id="elh_tpinjaman_created" class="tpinjaman_created">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->created->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->created->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->created->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tpinjaman->modified->Visible) { // modified ?>
	<?php if ($tpinjaman->SortUrl($tpinjaman->modified) == "") { ?>
		<th data-name="modified"><div id="elh_tpinjaman_modified" class="tpinjaman_modified"><div class="ewTableHeaderCaption"><?php echo $tpinjaman->modified->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="modified"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tpinjaman->SortUrl($tpinjaman->modified) ?>',1);"><div id="elh_tpinjaman_modified" class="tpinjaman_modified">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpinjaman->modified->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpinjaman->modified->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpinjaman->modified->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$tpinjaman_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($tpinjaman->ExportAll && $tpinjaman->Export <> "") {
	$tpinjaman_list->StopRec = $tpinjaman_list->TotalRecs;
} else {

	// Set the last record to display
	if ($tpinjaman_list->TotalRecs > $tpinjaman_list->StartRec + $tpinjaman_list->DisplayRecs - 1)
		$tpinjaman_list->StopRec = $tpinjaman_list->StartRec + $tpinjaman_list->DisplayRecs - 1;
	else
		$tpinjaman_list->StopRec = $tpinjaman_list->TotalRecs;
}
$tpinjaman_list->RecCnt = $tpinjaman_list->StartRec - 1;
if ($tpinjaman_list->Recordset && !$tpinjaman_list->Recordset->EOF) {
	$tpinjaman_list->Recordset->MoveFirst();
	$bSelectLimit = $tpinjaman_list->UseSelectLimit;
	if (!$bSelectLimit && $tpinjaman_list->StartRec > 1)
		$tpinjaman_list->Recordset->Move($tpinjaman_list->StartRec - 1);
} elseif (!$tpinjaman->AllowAddDeleteRow && $tpinjaman_list->StopRec == 0) {
	$tpinjaman_list->StopRec = $tpinjaman->GridAddRowCount;
}

// Initialize aggregate
$tpinjaman->RowType = EW_ROWTYPE_AGGREGATEINIT;
$tpinjaman->ResetAttrs();
$tpinjaman_list->RenderRow();
while ($tpinjaman_list->RecCnt < $tpinjaman_list->StopRec) {
	$tpinjaman_list->RecCnt++;
	if (intval($tpinjaman_list->RecCnt) >= intval($tpinjaman_list->StartRec)) {
		$tpinjaman_list->RowCnt++;

		// Set up key count
		$tpinjaman_list->KeyCount = $tpinjaman_list->RowIndex;

		// Init row class and style
		$tpinjaman->ResetAttrs();
		$tpinjaman->CssClass = "";
		if ($tpinjaman->CurrentAction == "gridadd") {
		} else {
			$tpinjaman_list->LoadRowValues($tpinjaman_list->Recordset); // Load row values
		}
		$tpinjaman->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$tpinjaman->RowAttrs = array_merge($tpinjaman->RowAttrs, array('data-rowindex'=>$tpinjaman_list->RowCnt, 'id'=>'r' . $tpinjaman_list->RowCnt . '_tpinjaman', 'data-rowtype'=>$tpinjaman->RowType));

		// Render row
		$tpinjaman_list->RenderRow();

		// Render list options
		$tpinjaman_list->RenderListOptions();
?>
	<tr<?php echo $tpinjaman->RowAttributes() ?>>
<?php

// Render list options (body, left)
$tpinjaman_list->ListOptions->Render("body", "left", $tpinjaman_list->RowCnt);
?>
	<?php if ($tpinjaman->tanggal->Visible) { // tanggal ?>
		<td data-name="tanggal"<?php echo $tpinjaman->tanggal->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_tanggal" class="tpinjaman_tanggal">
<span<?php echo $tpinjaman->tanggal->ViewAttributes() ?>>
<?php echo $tpinjaman->tanggal->ListViewValue() ?></span>
</span>
<a id="<?php echo $tpinjaman_list->PageObjName . "_row_" . $tpinjaman_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($tpinjaman->periode->Visible) { // periode ?>
		<td data-name="periode"<?php echo $tpinjaman->periode->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_periode" class="tpinjaman_periode">
<span<?php echo $tpinjaman->periode->ViewAttributes() ?>>
<?php echo $tpinjaman->periode->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->id->Visible) { // id ?>
		<td data-name="id"<?php echo $tpinjaman->id->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_id" class="tpinjaman_id">
<span<?php echo $tpinjaman->id->ViewAttributes() ?>>
<?php echo $tpinjaman->id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->transaksi->Visible) { // transaksi ?>
		<td data-name="transaksi"<?php echo $tpinjaman->transaksi->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_transaksi" class="tpinjaman_transaksi">
<span<?php echo $tpinjaman->transaksi->ViewAttributes() ?>>
<?php echo $tpinjaman->transaksi->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->referensi->Visible) { // referensi ?>
		<td data-name="referensi"<?php echo $tpinjaman->referensi->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_referensi" class="tpinjaman_referensi">
<span<?php echo $tpinjaman->referensi->ViewAttributes() ?>>
<?php echo $tpinjaman->referensi->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->anggota->Visible) { // anggota ?>
		<td data-name="anggota"<?php echo $tpinjaman->anggota->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_anggota" class="tpinjaman_anggota">
<span<?php echo $tpinjaman->anggota->ViewAttributes() ?>>
<?php echo $tpinjaman->anggota->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->namaanggota->Visible) { // namaanggota ?>
		<td data-name="namaanggota"<?php echo $tpinjaman->namaanggota->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_namaanggota" class="tpinjaman_namaanggota">
<span<?php echo $tpinjaman->namaanggota->ViewAttributes() ?>>
<?php echo $tpinjaman->namaanggota->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->alamat->Visible) { // alamat ?>
		<td data-name="alamat"<?php echo $tpinjaman->alamat->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_alamat" class="tpinjaman_alamat">
<span<?php echo $tpinjaman->alamat->ViewAttributes() ?>>
<?php echo $tpinjaman->alamat->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->pekerjaan->Visible) { // pekerjaan ?>
		<td data-name="pekerjaan"<?php echo $tpinjaman->pekerjaan->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_pekerjaan" class="tpinjaman_pekerjaan">
<span<?php echo $tpinjaman->pekerjaan->ViewAttributes() ?>>
<?php echo $tpinjaman->pekerjaan->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->telepon->Visible) { // telepon ?>
		<td data-name="telepon"<?php echo $tpinjaman->telepon->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_telepon" class="tpinjaman_telepon">
<span<?php echo $tpinjaman->telepon->ViewAttributes() ?>>
<?php echo $tpinjaman->telepon->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->hp->Visible) { // hp ?>
		<td data-name="hp"<?php echo $tpinjaman->hp->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_hp" class="tpinjaman_hp">
<span<?php echo $tpinjaman->hp->ViewAttributes() ?>>
<?php echo $tpinjaman->hp->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->fax->Visible) { // fax ?>
		<td data-name="fax"<?php echo $tpinjaman->fax->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_fax" class="tpinjaman_fax">
<span<?php echo $tpinjaman->fax->ViewAttributes() ?>>
<?php echo $tpinjaman->fax->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->_email->Visible) { // email ?>
		<td data-name="_email"<?php echo $tpinjaman->_email->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman__email" class="tpinjaman__email">
<span<?php echo $tpinjaman->_email->ViewAttributes() ?>>
<?php echo $tpinjaman->_email->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->website->Visible) { // website ?>
		<td data-name="website"<?php echo $tpinjaman->website->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_website" class="tpinjaman_website">
<span<?php echo $tpinjaman->website->ViewAttributes() ?>>
<?php echo $tpinjaman->website->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->jenisanggota->Visible) { // jenisanggota ?>
		<td data-name="jenisanggota"<?php echo $tpinjaman->jenisanggota->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_jenisanggota" class="tpinjaman_jenisanggota">
<span<?php echo $tpinjaman->jenisanggota->ViewAttributes() ?>>
<?php echo $tpinjaman->jenisanggota->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->model->Visible) { // model ?>
		<td data-name="model"<?php echo $tpinjaman->model->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_model" class="tpinjaman_model">
<span<?php echo $tpinjaman->model->ViewAttributes() ?>>
<?php echo $tpinjaman->model->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->jenispinjaman->Visible) { // jenispinjaman ?>
		<td data-name="jenispinjaman"<?php echo $tpinjaman->jenispinjaman->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_jenispinjaman" class="tpinjaman_jenispinjaman">
<span<?php echo $tpinjaman->jenispinjaman->ViewAttributes() ?>>
<?php echo $tpinjaman->jenispinjaman->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->jenisbunga->Visible) { // jenisbunga ?>
		<td data-name="jenisbunga"<?php echo $tpinjaman->jenisbunga->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_jenisbunga" class="tpinjaman_jenisbunga">
<span<?php echo $tpinjaman->jenisbunga->ViewAttributes() ?>>
<?php echo $tpinjaman->jenisbunga->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->angsuran->Visible) { // angsuran ?>
		<td data-name="angsuran"<?php echo $tpinjaman->angsuran->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_angsuran" class="tpinjaman_angsuran">
<span<?php echo $tpinjaman->angsuran->ViewAttributes() ?>>
<?php echo $tpinjaman->angsuran->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->masaangsuran->Visible) { // masaangsuran ?>
		<td data-name="masaangsuran"<?php echo $tpinjaman->masaangsuran->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_masaangsuran" class="tpinjaman_masaangsuran">
<span<?php echo $tpinjaman->masaangsuran->ViewAttributes() ?>>
<?php echo $tpinjaman->masaangsuran->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->jatuhtempo->Visible) { // jatuhtempo ?>
		<td data-name="jatuhtempo"<?php echo $tpinjaman->jatuhtempo->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_jatuhtempo" class="tpinjaman_jatuhtempo">
<span<?php echo $tpinjaman->jatuhtempo->ViewAttributes() ?>>
<?php echo $tpinjaman->jatuhtempo->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->dispensasidenda->Visible) { // dispensasidenda ?>
		<td data-name="dispensasidenda"<?php echo $tpinjaman->dispensasidenda->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_dispensasidenda" class="tpinjaman_dispensasidenda">
<span<?php echo $tpinjaman->dispensasidenda->ViewAttributes() ?>>
<?php echo $tpinjaman->dispensasidenda->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->agunan->Visible) { // agunan ?>
		<td data-name="agunan"<?php echo $tpinjaman->agunan->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_agunan" class="tpinjaman_agunan">
<span<?php echo $tpinjaman->agunan->ViewAttributes() ?>>
<?php echo $tpinjaman->agunan->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->dataagunan1->Visible) { // dataagunan1 ?>
		<td data-name="dataagunan1"<?php echo $tpinjaman->dataagunan1->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_dataagunan1" class="tpinjaman_dataagunan1">
<span<?php echo $tpinjaman->dataagunan1->ViewAttributes() ?>>
<?php echo $tpinjaman->dataagunan1->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->dataagunan2->Visible) { // dataagunan2 ?>
		<td data-name="dataagunan2"<?php echo $tpinjaman->dataagunan2->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_dataagunan2" class="tpinjaman_dataagunan2">
<span<?php echo $tpinjaman->dataagunan2->ViewAttributes() ?>>
<?php echo $tpinjaman->dataagunan2->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->dataagunan3->Visible) { // dataagunan3 ?>
		<td data-name="dataagunan3"<?php echo $tpinjaman->dataagunan3->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_dataagunan3" class="tpinjaman_dataagunan3">
<span<?php echo $tpinjaman->dataagunan3->ViewAttributes() ?>>
<?php echo $tpinjaman->dataagunan3->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->dataagunan4->Visible) { // dataagunan4 ?>
		<td data-name="dataagunan4"<?php echo $tpinjaman->dataagunan4->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_dataagunan4" class="tpinjaman_dataagunan4">
<span<?php echo $tpinjaman->dataagunan4->ViewAttributes() ?>>
<?php echo $tpinjaman->dataagunan4->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->dataagunan5->Visible) { // dataagunan5 ?>
		<td data-name="dataagunan5"<?php echo $tpinjaman->dataagunan5->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_dataagunan5" class="tpinjaman_dataagunan5">
<span<?php echo $tpinjaman->dataagunan5->ViewAttributes() ?>>
<?php echo $tpinjaman->dataagunan5->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->saldobekusimpanan->Visible) { // saldobekusimpanan ?>
		<td data-name="saldobekusimpanan"<?php echo $tpinjaman->saldobekusimpanan->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_saldobekusimpanan" class="tpinjaman_saldobekusimpanan">
<span<?php echo $tpinjaman->saldobekusimpanan->ViewAttributes() ?>>
<?php echo $tpinjaman->saldobekusimpanan->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->saldobekuminimal->Visible) { // saldobekuminimal ?>
		<td data-name="saldobekuminimal"<?php echo $tpinjaman->saldobekuminimal->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_saldobekuminimal" class="tpinjaman_saldobekuminimal">
<span<?php echo $tpinjaman->saldobekuminimal->ViewAttributes() ?>>
<?php echo $tpinjaman->saldobekuminimal->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->plafond->Visible) { // plafond ?>
		<td data-name="plafond"<?php echo $tpinjaman->plafond->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_plafond" class="tpinjaman_plafond">
<span<?php echo $tpinjaman->plafond->ViewAttributes() ?>>
<?php echo $tpinjaman->plafond->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->bunga->Visible) { // bunga ?>
		<td data-name="bunga"<?php echo $tpinjaman->bunga->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_bunga" class="tpinjaman_bunga">
<span<?php echo $tpinjaman->bunga->ViewAttributes() ?>>
<?php echo $tpinjaman->bunga->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->bungapersen->Visible) { // bungapersen ?>
		<td data-name="bungapersen"<?php echo $tpinjaman->bungapersen->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_bungapersen" class="tpinjaman_bungapersen">
<span<?php echo $tpinjaman->bungapersen->ViewAttributes() ?>>
<?php echo $tpinjaman->bungapersen->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->administrasi->Visible) { // administrasi ?>
		<td data-name="administrasi"<?php echo $tpinjaman->administrasi->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_administrasi" class="tpinjaman_administrasi">
<span<?php echo $tpinjaman->administrasi->ViewAttributes() ?>>
<?php echo $tpinjaman->administrasi->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->administrasipersen->Visible) { // administrasipersen ?>
		<td data-name="administrasipersen"<?php echo $tpinjaman->administrasipersen->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_administrasipersen" class="tpinjaman_administrasipersen">
<span<?php echo $tpinjaman->administrasipersen->ViewAttributes() ?>>
<?php echo $tpinjaman->administrasipersen->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->asuransi->Visible) { // asuransi ?>
		<td data-name="asuransi"<?php echo $tpinjaman->asuransi->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_asuransi" class="tpinjaman_asuransi">
<span<?php echo $tpinjaman->asuransi->ViewAttributes() ?>>
<?php echo $tpinjaman->asuransi->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->notaris->Visible) { // notaris ?>
		<td data-name="notaris"<?php echo $tpinjaman->notaris->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_notaris" class="tpinjaman_notaris">
<span<?php echo $tpinjaman->notaris->ViewAttributes() ?>>
<?php echo $tpinjaman->notaris->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->biayamaterai->Visible) { // biayamaterai ?>
		<td data-name="biayamaterai"<?php echo $tpinjaman->biayamaterai->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_biayamaterai" class="tpinjaman_biayamaterai">
<span<?php echo $tpinjaman->biayamaterai->ViewAttributes() ?>>
<?php echo $tpinjaman->biayamaterai->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->potongansaldobeku->Visible) { // potongansaldobeku ?>
		<td data-name="potongansaldobeku"<?php echo $tpinjaman->potongansaldobeku->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_potongansaldobeku" class="tpinjaman_potongansaldobeku">
<span<?php echo $tpinjaman->potongansaldobeku->ViewAttributes() ?>>
<?php echo $tpinjaman->potongansaldobeku->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->angsuranpokok->Visible) { // angsuranpokok ?>
		<td data-name="angsuranpokok"<?php echo $tpinjaman->angsuranpokok->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_angsuranpokok" class="tpinjaman_angsuranpokok">
<span<?php echo $tpinjaman->angsuranpokok->ViewAttributes() ?>>
<?php echo $tpinjaman->angsuranpokok->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->angsuranpokokauto->Visible) { // angsuranpokokauto ?>
		<td data-name="angsuranpokokauto"<?php echo $tpinjaman->angsuranpokokauto->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_angsuranpokokauto" class="tpinjaman_angsuranpokokauto">
<span<?php echo $tpinjaman->angsuranpokokauto->ViewAttributes() ?>>
<?php echo $tpinjaman->angsuranpokokauto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->angsuranbunga->Visible) { // angsuranbunga ?>
		<td data-name="angsuranbunga"<?php echo $tpinjaman->angsuranbunga->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_angsuranbunga" class="tpinjaman_angsuranbunga">
<span<?php echo $tpinjaman->angsuranbunga->ViewAttributes() ?>>
<?php echo $tpinjaman->angsuranbunga->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->angsuranbungaauto->Visible) { // angsuranbungaauto ?>
		<td data-name="angsuranbungaauto"<?php echo $tpinjaman->angsuranbungaauto->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_angsuranbungaauto" class="tpinjaman_angsuranbungaauto">
<span<?php echo $tpinjaman->angsuranbungaauto->ViewAttributes() ?>>
<?php echo $tpinjaman->angsuranbungaauto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->denda->Visible) { // denda ?>
		<td data-name="denda"<?php echo $tpinjaman->denda->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_denda" class="tpinjaman_denda">
<span<?php echo $tpinjaman->denda->ViewAttributes() ?>>
<?php echo $tpinjaman->denda->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->dendapersen->Visible) { // dendapersen ?>
		<td data-name="dendapersen"<?php echo $tpinjaman->dendapersen->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_dendapersen" class="tpinjaman_dendapersen">
<span<?php echo $tpinjaman->dendapersen->ViewAttributes() ?>>
<?php echo $tpinjaman->dendapersen->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->totalangsuran->Visible) { // totalangsuran ?>
		<td data-name="totalangsuran"<?php echo $tpinjaman->totalangsuran->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_totalangsuran" class="tpinjaman_totalangsuran">
<span<?php echo $tpinjaman->totalangsuran->ViewAttributes() ?>>
<?php echo $tpinjaman->totalangsuran->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->totalangsuranauto->Visible) { // totalangsuranauto ?>
		<td data-name="totalangsuranauto"<?php echo $tpinjaman->totalangsuranauto->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_totalangsuranauto" class="tpinjaman_totalangsuranauto">
<span<?php echo $tpinjaman->totalangsuranauto->ViewAttributes() ?>>
<?php echo $tpinjaman->totalangsuranauto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->totalterima->Visible) { // totalterima ?>
		<td data-name="totalterima"<?php echo $tpinjaman->totalterima->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_totalterima" class="tpinjaman_totalterima">
<span<?php echo $tpinjaman->totalterima->ViewAttributes() ?>>
<?php echo $tpinjaman->totalterima->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->totalterimaauto->Visible) { // totalterimaauto ?>
		<td data-name="totalterimaauto"<?php echo $tpinjaman->totalterimaauto->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_totalterimaauto" class="tpinjaman_totalterimaauto">
<span<?php echo $tpinjaman->totalterimaauto->ViewAttributes() ?>>
<?php echo $tpinjaman->totalterimaauto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->terbilang->Visible) { // terbilang ?>
		<td data-name="terbilang"<?php echo $tpinjaman->terbilang->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_terbilang" class="tpinjaman_terbilang">
<span<?php echo $tpinjaman->terbilang->ViewAttributes() ?>>
<?php echo $tpinjaman->terbilang->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->petugas->Visible) { // petugas ?>
		<td data-name="petugas"<?php echo $tpinjaman->petugas->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_petugas" class="tpinjaman_petugas">
<span<?php echo $tpinjaman->petugas->ViewAttributes() ?>>
<?php echo $tpinjaman->petugas->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->pembayaran->Visible) { // pembayaran ?>
		<td data-name="pembayaran"<?php echo $tpinjaman->pembayaran->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_pembayaran" class="tpinjaman_pembayaran">
<span<?php echo $tpinjaman->pembayaran->ViewAttributes() ?>>
<?php echo $tpinjaman->pembayaran->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->bank->Visible) { // bank ?>
		<td data-name="bank"<?php echo $tpinjaman->bank->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_bank" class="tpinjaman_bank">
<span<?php echo $tpinjaman->bank->ViewAttributes() ?>>
<?php echo $tpinjaman->bank->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->atasnama->Visible) { // atasnama ?>
		<td data-name="atasnama"<?php echo $tpinjaman->atasnama->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_atasnama" class="tpinjaman_atasnama">
<span<?php echo $tpinjaman->atasnama->ViewAttributes() ?>>
<?php echo $tpinjaman->atasnama->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->tipe->Visible) { // tipe ?>
		<td data-name="tipe"<?php echo $tpinjaman->tipe->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_tipe" class="tpinjaman_tipe">
<span<?php echo $tpinjaman->tipe->ViewAttributes() ?>>
<?php echo $tpinjaman->tipe->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->kantor->Visible) { // kantor ?>
		<td data-name="kantor"<?php echo $tpinjaman->kantor->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_kantor" class="tpinjaman_kantor">
<span<?php echo $tpinjaman->kantor->ViewAttributes() ?>>
<?php echo $tpinjaman->kantor->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->keterangan->Visible) { // keterangan ?>
		<td data-name="keterangan"<?php echo $tpinjaman->keterangan->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_keterangan" class="tpinjaman_keterangan">
<span<?php echo $tpinjaman->keterangan->ViewAttributes() ?>>
<?php echo $tpinjaman->keterangan->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->active->Visible) { // active ?>
		<td data-name="active"<?php echo $tpinjaman->active->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_active" class="tpinjaman_active">
<span<?php echo $tpinjaman->active->ViewAttributes() ?>>
<?php echo $tpinjaman->active->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->ip->Visible) { // ip ?>
		<td data-name="ip"<?php echo $tpinjaman->ip->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_ip" class="tpinjaman_ip">
<span<?php echo $tpinjaman->ip->ViewAttributes() ?>>
<?php echo $tpinjaman->ip->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->status->Visible) { // status ?>
		<td data-name="status"<?php echo $tpinjaman->status->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_status" class="tpinjaman_status">
<span<?php echo $tpinjaman->status->ViewAttributes() ?>>
<?php echo $tpinjaman->status->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->user->Visible) { // user ?>
		<td data-name="user"<?php echo $tpinjaman->user->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_user" class="tpinjaman_user">
<span<?php echo $tpinjaman->user->ViewAttributes() ?>>
<?php echo $tpinjaman->user->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->created->Visible) { // created ?>
		<td data-name="created"<?php echo $tpinjaman->created->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_created" class="tpinjaman_created">
<span<?php echo $tpinjaman->created->ViewAttributes() ?>>
<?php echo $tpinjaman->created->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tpinjaman->modified->Visible) { // modified ?>
		<td data-name="modified"<?php echo $tpinjaman->modified->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_list->RowCnt ?>_tpinjaman_modified" class="tpinjaman_modified">
<span<?php echo $tpinjaman->modified->ViewAttributes() ?>>
<?php echo $tpinjaman->modified->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$tpinjaman_list->ListOptions->Render("body", "right", $tpinjaman_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($tpinjaman->CurrentAction <> "gridadd")
		$tpinjaman_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($tpinjaman->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($tpinjaman_list->Recordset)
	$tpinjaman_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($tpinjaman->CurrentAction <> "gridadd" && $tpinjaman->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($tpinjaman_list->Pager)) $tpinjaman_list->Pager = new cPrevNextPager($tpinjaman_list->StartRec, $tpinjaman_list->DisplayRecs, $tpinjaman_list->TotalRecs) ?>
<?php if ($tpinjaman_list->Pager->RecordCount > 0 && $tpinjaman_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($tpinjaman_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $tpinjaman_list->PageUrl() ?>start=<?php echo $tpinjaman_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($tpinjaman_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $tpinjaman_list->PageUrl() ?>start=<?php echo $tpinjaman_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $tpinjaman_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($tpinjaman_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $tpinjaman_list->PageUrl() ?>start=<?php echo $tpinjaman_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($tpinjaman_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $tpinjaman_list->PageUrl() ?>start=<?php echo $tpinjaman_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $tpinjaman_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $tpinjaman_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $tpinjaman_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $tpinjaman_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($tpinjaman_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($tpinjaman_list->TotalRecs == 0 && $tpinjaman->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($tpinjaman_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
ftpinjamanlistsrch.FilterList = <?php echo $tpinjaman_list->GetFilterList() ?>;
ftpinjamanlistsrch.Init();
ftpinjamanlist.Init();
</script>
<?php
$tpinjaman_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tpinjaman_list->Page_Terminate();
?>
