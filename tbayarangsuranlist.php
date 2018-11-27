<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "tbayarangsuraninfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$tbayarangsuran_list = NULL; // Initialize page object first

class ctbayarangsuran_list extends ctbayarangsuran {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tbayarangsuran';

	// Page object name
	var $PageObjName = 'tbayarangsuran_list';

	// Grid form hidden field names
	var $FormName = 'ftbayarangsuranlist';
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

		// Table object (tbayarangsuran)
		if (!isset($GLOBALS["tbayarangsuran"]) || get_class($GLOBALS["tbayarangsuran"]) == "ctbayarangsuran") {
			$GLOBALS["tbayarangsuran"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tbayarangsuran"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "tbayarangsuranadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "tbayarangsurandelete.php";
		$this->MultiUpdateUrl = "tbayarangsuranupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tbayarangsuran', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption ftbayarangsuranlistsrch";

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
		$this->terlambat->SetVisibility();
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
		$this->saldotitipan->SetVisibility();
		$this->saldotitipansisa->SetVisibility();
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
		global $EW_EXPORT, $tbayarangsuran;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($tbayarangsuran);
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
			$sSavedFilterList = isset($UserProfile) ? $UserProfile->GetSearchFilters(CurrentUserName(), "ftbayarangsuranlistsrch") : "";
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
		$sFilterList = ew_Concat($sFilterList, $this->terlambat->AdvancedSearch->ToJSON(), ","); // Field terlambat
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
		$sFilterList = ew_Concat($sFilterList, $this->saldotitipan->AdvancedSearch->ToJSON(), ","); // Field saldotitipan
		$sFilterList = ew_Concat($sFilterList, $this->saldotitipansisa->AdvancedSearch->ToJSON(), ","); // Field saldotitipansisa
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "ftbayarangsuranlistsrch", $filters);

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

		// Field terlambat
		$this->terlambat->AdvancedSearch->SearchValue = @$filter["x_terlambat"];
		$this->terlambat->AdvancedSearch->SearchOperator = @$filter["z_terlambat"];
		$this->terlambat->AdvancedSearch->SearchCondition = @$filter["v_terlambat"];
		$this->terlambat->AdvancedSearch->SearchValue2 = @$filter["y_terlambat"];
		$this->terlambat->AdvancedSearch->SearchOperator2 = @$filter["w_terlambat"];
		$this->terlambat->AdvancedSearch->Save();

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

		// Field saldotitipan
		$this->saldotitipan->AdvancedSearch->SearchValue = @$filter["x_saldotitipan"];
		$this->saldotitipan->AdvancedSearch->SearchOperator = @$filter["z_saldotitipan"];
		$this->saldotitipan->AdvancedSearch->SearchCondition = @$filter["v_saldotitipan"];
		$this->saldotitipan->AdvancedSearch->SearchValue2 = @$filter["y_saldotitipan"];
		$this->saldotitipan->AdvancedSearch->SearchOperator2 = @$filter["w_saldotitipan"];
		$this->saldotitipan->AdvancedSearch->Save();

		// Field saldotitipansisa
		$this->saldotitipansisa->AdvancedSearch->SearchValue = @$filter["x_saldotitipansisa"];
		$this->saldotitipansisa->AdvancedSearch->SearchOperator = @$filter["z_saldotitipansisa"];
		$this->saldotitipansisa->AdvancedSearch->SearchCondition = @$filter["v_saldotitipansisa"];
		$this->saldotitipansisa->AdvancedSearch->SearchValue2 = @$filter["y_saldotitipansisa"];
		$this->saldotitipansisa->AdvancedSearch->SearchOperator2 = @$filter["w_saldotitipansisa"];
		$this->saldotitipansisa->AdvancedSearch->Save();

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
		$this->BuildSearchSql($sWhere, $this->terlambat, $Default, FALSE); // terlambat
		$this->BuildSearchSql($sWhere, $this->dispensasidenda, $Default, FALSE); // dispensasidenda
		$this->BuildSearchSql($sWhere, $this->plafond, $Default, FALSE); // plafond
		$this->BuildSearchSql($sWhere, $this->angsuranpokok, $Default, FALSE); // angsuranpokok
		$this->BuildSearchSql($sWhere, $this->angsuranpokokauto, $Default, FALSE); // angsuranpokokauto
		$this->BuildSearchSql($sWhere, $this->angsuranbunga, $Default, FALSE); // angsuranbunga
		$this->BuildSearchSql($sWhere, $this->angsuranbungaauto, $Default, FALSE); // angsuranbungaauto
		$this->BuildSearchSql($sWhere, $this->denda, $Default, FALSE); // denda
		$this->BuildSearchSql($sWhere, $this->dendapersen, $Default, FALSE); // dendapersen
		$this->BuildSearchSql($sWhere, $this->totalangsuran, $Default, FALSE); // totalangsuran
		$this->BuildSearchSql($sWhere, $this->totalangsuranauto, $Default, FALSE); // totalangsuranauto
		$this->BuildSearchSql($sWhere, $this->sisaangsuran, $Default, FALSE); // sisaangsuran
		$this->BuildSearchSql($sWhere, $this->sisaangsuranauto, $Default, FALSE); // sisaangsuranauto
		$this->BuildSearchSql($sWhere, $this->saldotitipan, $Default, FALSE); // saldotitipan
		$this->BuildSearchSql($sWhere, $this->saldotitipansisa, $Default, FALSE); // saldotitipansisa
		$this->BuildSearchSql($sWhere, $this->bayarpokok, $Default, FALSE); // bayarpokok
		$this->BuildSearchSql($sWhere, $this->bayarpokokauto, $Default, FALSE); // bayarpokokauto
		$this->BuildSearchSql($sWhere, $this->bayarbunga, $Default, FALSE); // bayarbunga
		$this->BuildSearchSql($sWhere, $this->bayarbungaauto, $Default, FALSE); // bayarbungaauto
		$this->BuildSearchSql($sWhere, $this->bayardenda, $Default, FALSE); // bayardenda
		$this->BuildSearchSql($sWhere, $this->bayardendaauto, $Default, FALSE); // bayardendaauto
		$this->BuildSearchSql($sWhere, $this->bayartitipan, $Default, FALSE); // bayartitipan
		$this->BuildSearchSql($sWhere, $this->bayartitipanauto, $Default, FALSE); // bayartitipanauto
		$this->BuildSearchSql($sWhere, $this->totalbayar, $Default, FALSE); // totalbayar
		$this->BuildSearchSql($sWhere, $this->totalbayarauto, $Default, FALSE); // totalbayarauto
		$this->BuildSearchSql($sWhere, $this->pelunasan, $Default, FALSE); // pelunasan
		$this->BuildSearchSql($sWhere, $this->pelunasanauto, $Default, FALSE); // pelunasanauto
		$this->BuildSearchSql($sWhere, $this->finalty, $Default, FALSE); // finalty
		$this->BuildSearchSql($sWhere, $this->finaltyauto, $Default, FALSE); // finaltyauto
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
			$this->terlambat->AdvancedSearch->Save(); // terlambat
			$this->dispensasidenda->AdvancedSearch->Save(); // dispensasidenda
			$this->plafond->AdvancedSearch->Save(); // plafond
			$this->angsuranpokok->AdvancedSearch->Save(); // angsuranpokok
			$this->angsuranpokokauto->AdvancedSearch->Save(); // angsuranpokokauto
			$this->angsuranbunga->AdvancedSearch->Save(); // angsuranbunga
			$this->angsuranbungaauto->AdvancedSearch->Save(); // angsuranbungaauto
			$this->denda->AdvancedSearch->Save(); // denda
			$this->dendapersen->AdvancedSearch->Save(); // dendapersen
			$this->totalangsuran->AdvancedSearch->Save(); // totalangsuran
			$this->totalangsuranauto->AdvancedSearch->Save(); // totalangsuranauto
			$this->sisaangsuran->AdvancedSearch->Save(); // sisaangsuran
			$this->sisaangsuranauto->AdvancedSearch->Save(); // sisaangsuranauto
			$this->saldotitipan->AdvancedSearch->Save(); // saldotitipan
			$this->saldotitipansisa->AdvancedSearch->Save(); // saldotitipansisa
			$this->bayarpokok->AdvancedSearch->Save(); // bayarpokok
			$this->bayarpokokauto->AdvancedSearch->Save(); // bayarpokokauto
			$this->bayarbunga->AdvancedSearch->Save(); // bayarbunga
			$this->bayarbungaauto->AdvancedSearch->Save(); // bayarbungaauto
			$this->bayardenda->AdvancedSearch->Save(); // bayardenda
			$this->bayardendaauto->AdvancedSearch->Save(); // bayardendaauto
			$this->bayartitipan->AdvancedSearch->Save(); // bayartitipan
			$this->bayartitipanauto->AdvancedSearch->Save(); // bayartitipanauto
			$this->totalbayar->AdvancedSearch->Save(); // totalbayar
			$this->totalbayarauto->AdvancedSearch->Save(); // totalbayarauto
			$this->pelunasan->AdvancedSearch->Save(); // pelunasan
			$this->pelunasanauto->AdvancedSearch->Save(); // pelunasanauto
			$this->finalty->AdvancedSearch->Save(); // finalty
			$this->finaltyauto->AdvancedSearch->Save(); // finaltyauto
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
		if ($this->terlambat->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dispensasidenda->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->plafond->AdvancedSearch->IssetSession())
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
		if ($this->sisaangsuran->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->sisaangsuranauto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->saldotitipan->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->saldotitipansisa->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->bayarpokok->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->bayarpokokauto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->bayarbunga->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->bayarbungaauto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->bayardenda->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->bayardendaauto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->bayartitipan->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->bayartitipanauto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->totalbayar->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->totalbayarauto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->pelunasan->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->pelunasanauto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->finalty->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->finaltyauto->AdvancedSearch->IssetSession())
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
		$this->terlambat->AdvancedSearch->UnsetSession();
		$this->dispensasidenda->AdvancedSearch->UnsetSession();
		$this->plafond->AdvancedSearch->UnsetSession();
		$this->angsuranpokok->AdvancedSearch->UnsetSession();
		$this->angsuranpokokauto->AdvancedSearch->UnsetSession();
		$this->angsuranbunga->AdvancedSearch->UnsetSession();
		$this->angsuranbungaauto->AdvancedSearch->UnsetSession();
		$this->denda->AdvancedSearch->UnsetSession();
		$this->dendapersen->AdvancedSearch->UnsetSession();
		$this->totalangsuran->AdvancedSearch->UnsetSession();
		$this->totalangsuranauto->AdvancedSearch->UnsetSession();
		$this->sisaangsuran->AdvancedSearch->UnsetSession();
		$this->sisaangsuranauto->AdvancedSearch->UnsetSession();
		$this->saldotitipan->AdvancedSearch->UnsetSession();
		$this->saldotitipansisa->AdvancedSearch->UnsetSession();
		$this->bayarpokok->AdvancedSearch->UnsetSession();
		$this->bayarpokokauto->AdvancedSearch->UnsetSession();
		$this->bayarbunga->AdvancedSearch->UnsetSession();
		$this->bayarbungaauto->AdvancedSearch->UnsetSession();
		$this->bayardenda->AdvancedSearch->UnsetSession();
		$this->bayardendaauto->AdvancedSearch->UnsetSession();
		$this->bayartitipan->AdvancedSearch->UnsetSession();
		$this->bayartitipanauto->AdvancedSearch->UnsetSession();
		$this->totalbayar->AdvancedSearch->UnsetSession();
		$this->totalbayarauto->AdvancedSearch->UnsetSession();
		$this->pelunasan->AdvancedSearch->UnsetSession();
		$this->pelunasanauto->AdvancedSearch->UnsetSession();
		$this->finalty->AdvancedSearch->UnsetSession();
		$this->finaltyauto->AdvancedSearch->UnsetSession();
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
		$this->terlambat->AdvancedSearch->Load();
		$this->dispensasidenda->AdvancedSearch->Load();
		$this->plafond->AdvancedSearch->Load();
		$this->angsuranpokok->AdvancedSearch->Load();
		$this->angsuranpokokauto->AdvancedSearch->Load();
		$this->angsuranbunga->AdvancedSearch->Load();
		$this->angsuranbungaauto->AdvancedSearch->Load();
		$this->denda->AdvancedSearch->Load();
		$this->dendapersen->AdvancedSearch->Load();
		$this->totalangsuran->AdvancedSearch->Load();
		$this->totalangsuranauto->AdvancedSearch->Load();
		$this->sisaangsuran->AdvancedSearch->Load();
		$this->sisaangsuranauto->AdvancedSearch->Load();
		$this->saldotitipan->AdvancedSearch->Load();
		$this->saldotitipansisa->AdvancedSearch->Load();
		$this->bayarpokok->AdvancedSearch->Load();
		$this->bayarpokokauto->AdvancedSearch->Load();
		$this->bayarbunga->AdvancedSearch->Load();
		$this->bayarbungaauto->AdvancedSearch->Load();
		$this->bayardenda->AdvancedSearch->Load();
		$this->bayardendaauto->AdvancedSearch->Load();
		$this->bayartitipan->AdvancedSearch->Load();
		$this->bayartitipanauto->AdvancedSearch->Load();
		$this->totalbayar->AdvancedSearch->Load();
		$this->totalbayarauto->AdvancedSearch->Load();
		$this->pelunasan->AdvancedSearch->Load();
		$this->pelunasanauto->AdvancedSearch->Load();
		$this->finalty->AdvancedSearch->Load();
		$this->finaltyauto->AdvancedSearch->Load();
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
			$this->UpdateSort($this->terlambat); // terlambat
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
			$this->UpdateSort($this->saldotitipan); // saldotitipan
			$this->UpdateSort($this->saldotitipansisa); // saldotitipansisa
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
				$this->terlambat->setSort("");
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
				$this->saldotitipan->setSort("");
				$this->saldotitipansisa->setSort("");
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"ftbayarangsuranlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"ftbayarangsuranlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.ftbayarangsuranlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"ftbayarangsuranlistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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

		// terlambat
		$this->terlambat->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_terlambat"]);
		if ($this->terlambat->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->terlambat->AdvancedSearch->SearchOperator = @$_GET["z_terlambat"];

		// dispensasidenda
		$this->dispensasidenda->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dispensasidenda"]);
		if ($this->dispensasidenda->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dispensasidenda->AdvancedSearch->SearchOperator = @$_GET["z_dispensasidenda"];

		// plafond
		$this->plafond->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_plafond"]);
		if ($this->plafond->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->plafond->AdvancedSearch->SearchOperator = @$_GET["z_plafond"];

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

		// sisaangsuran
		$this->sisaangsuran->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_sisaangsuran"]);
		if ($this->sisaangsuran->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->sisaangsuran->AdvancedSearch->SearchOperator = @$_GET["z_sisaangsuran"];

		// sisaangsuranauto
		$this->sisaangsuranauto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_sisaangsuranauto"]);
		if ($this->sisaangsuranauto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->sisaangsuranauto->AdvancedSearch->SearchOperator = @$_GET["z_sisaangsuranauto"];

		// saldotitipan
		$this->saldotitipan->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_saldotitipan"]);
		if ($this->saldotitipan->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->saldotitipan->AdvancedSearch->SearchOperator = @$_GET["z_saldotitipan"];

		// saldotitipansisa
		$this->saldotitipansisa->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_saldotitipansisa"]);
		if ($this->saldotitipansisa->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->saldotitipansisa->AdvancedSearch->SearchOperator = @$_GET["z_saldotitipansisa"];

		// bayarpokok
		$this->bayarpokok->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_bayarpokok"]);
		if ($this->bayarpokok->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->bayarpokok->AdvancedSearch->SearchOperator = @$_GET["z_bayarpokok"];

		// bayarpokokauto
		$this->bayarpokokauto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_bayarpokokauto"]);
		if ($this->bayarpokokauto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->bayarpokokauto->AdvancedSearch->SearchOperator = @$_GET["z_bayarpokokauto"];

		// bayarbunga
		$this->bayarbunga->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_bayarbunga"]);
		if ($this->bayarbunga->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->bayarbunga->AdvancedSearch->SearchOperator = @$_GET["z_bayarbunga"];

		// bayarbungaauto
		$this->bayarbungaauto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_bayarbungaauto"]);
		if ($this->bayarbungaauto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->bayarbungaauto->AdvancedSearch->SearchOperator = @$_GET["z_bayarbungaauto"];

		// bayardenda
		$this->bayardenda->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_bayardenda"]);
		if ($this->bayardenda->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->bayardenda->AdvancedSearch->SearchOperator = @$_GET["z_bayardenda"];

		// bayardendaauto
		$this->bayardendaauto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_bayardendaauto"]);
		if ($this->bayardendaauto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->bayardendaauto->AdvancedSearch->SearchOperator = @$_GET["z_bayardendaauto"];

		// bayartitipan
		$this->bayartitipan->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_bayartitipan"]);
		if ($this->bayartitipan->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->bayartitipan->AdvancedSearch->SearchOperator = @$_GET["z_bayartitipan"];

		// bayartitipanauto
		$this->bayartitipanauto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_bayartitipanauto"]);
		if ($this->bayartitipanauto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->bayartitipanauto->AdvancedSearch->SearchOperator = @$_GET["z_bayartitipanauto"];

		// totalbayar
		$this->totalbayar->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_totalbayar"]);
		if ($this->totalbayar->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->totalbayar->AdvancedSearch->SearchOperator = @$_GET["z_totalbayar"];

		// totalbayarauto
		$this->totalbayarauto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_totalbayarauto"]);
		if ($this->totalbayarauto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->totalbayarauto->AdvancedSearch->SearchOperator = @$_GET["z_totalbayarauto"];

		// pelunasan
		$this->pelunasan->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_pelunasan"]);
		if ($this->pelunasan->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->pelunasan->AdvancedSearch->SearchOperator = @$_GET["z_pelunasan"];

		// pelunasanauto
		$this->pelunasanauto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_pelunasanauto"]);
		if ($this->pelunasanauto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->pelunasanauto->AdvancedSearch->SearchOperator = @$_GET["z_pelunasanauto"];

		// finalty
		$this->finalty->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_finalty"]);
		if ($this->finalty->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->finalty->AdvancedSearch->SearchOperator = @$_GET["z_finalty"];

		// finaltyauto
		$this->finaltyauto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_finaltyauto"]);
		if ($this->finaltyauto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->finaltyauto->AdvancedSearch->SearchOperator = @$_GET["z_finaltyauto"];

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
		$this->terlambat->setDbValue($rs->fields('terlambat'));
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
		$this->saldotitipan->setDbValue($rs->fields('saldotitipan'));
		$this->saldotitipansisa->setDbValue($rs->fields('saldotitipansisa'));
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
		$this->terlambat->DbValue = $row['terlambat'];
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
		$this->saldotitipan->DbValue = $row['saldotitipan'];
		$this->saldotitipansisa->DbValue = $row['saldotitipansisa'];
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
		if ($this->saldotitipan->FormValue == $this->saldotitipan->CurrentValue && is_numeric(ew_StrToFloat($this->saldotitipan->CurrentValue)))
			$this->saldotitipan->CurrentValue = ew_StrToFloat($this->saldotitipan->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldotitipansisa->FormValue == $this->saldotitipansisa->CurrentValue && is_numeric(ew_StrToFloat($this->saldotitipansisa->CurrentValue)))
			$this->saldotitipansisa->CurrentValue = ew_StrToFloat($this->saldotitipansisa->CurrentValue);

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
		// terlambat
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
		// saldotitipan
		// saldotitipansisa
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

		// terlambat
		$this->terlambat->ViewValue = $this->terlambat->CurrentValue;
		$this->terlambat->ViewCustomAttributes = "";

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

		// saldotitipan
		$this->saldotitipan->ViewValue = $this->saldotitipan->CurrentValue;
		$this->saldotitipan->ViewCustomAttributes = "";

		// saldotitipansisa
		$this->saldotitipansisa->ViewValue = $this->saldotitipansisa->CurrentValue;
		$this->saldotitipansisa->ViewCustomAttributes = "";

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

			// terlambat
			$this->terlambat->LinkCustomAttributes = "";
			$this->terlambat->HrefValue = "";
			$this->terlambat->TooltipValue = "";

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

			// saldotitipan
			$this->saldotitipan->LinkCustomAttributes = "";
			$this->saldotitipan->HrefValue = "";
			$this->saldotitipan->TooltipValue = "";

			// saldotitipansisa
			$this->saldotitipansisa->LinkCustomAttributes = "";
			$this->saldotitipansisa->HrefValue = "";
			$this->saldotitipansisa->TooltipValue = "";

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

			// terlambat
			$this->terlambat->EditAttrs["class"] = "form-control";
			$this->terlambat->EditCustomAttributes = "";
			$this->terlambat->EditValue = ew_HtmlEncode($this->terlambat->AdvancedSearch->SearchValue);
			$this->terlambat->PlaceHolder = ew_RemoveHtml($this->terlambat->FldCaption());

			// dispensasidenda
			$this->dispensasidenda->EditAttrs["class"] = "form-control";
			$this->dispensasidenda->EditCustomAttributes = "";
			$this->dispensasidenda->EditValue = ew_HtmlEncode($this->dispensasidenda->AdvancedSearch->SearchValue);
			$this->dispensasidenda->PlaceHolder = ew_RemoveHtml($this->dispensasidenda->FldCaption());

			// plafond
			$this->plafond->EditAttrs["class"] = "form-control";
			$this->plafond->EditCustomAttributes = "";
			$this->plafond->EditValue = ew_HtmlEncode($this->plafond->AdvancedSearch->SearchValue);
			$this->plafond->PlaceHolder = ew_RemoveHtml($this->plafond->FldCaption());

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

			// sisaangsuran
			$this->sisaangsuran->EditAttrs["class"] = "form-control";
			$this->sisaangsuran->EditCustomAttributes = "";
			$this->sisaangsuran->EditValue = ew_HtmlEncode($this->sisaangsuran->AdvancedSearch->SearchValue);
			$this->sisaangsuran->PlaceHolder = ew_RemoveHtml($this->sisaangsuran->FldCaption());

			// sisaangsuranauto
			$this->sisaangsuranauto->EditAttrs["class"] = "form-control";
			$this->sisaangsuranauto->EditCustomAttributes = "";
			$this->sisaangsuranauto->EditValue = ew_HtmlEncode($this->sisaangsuranauto->AdvancedSearch->SearchValue);
			$this->sisaangsuranauto->PlaceHolder = ew_RemoveHtml($this->sisaangsuranauto->FldCaption());

			// saldotitipan
			$this->saldotitipan->EditAttrs["class"] = "form-control";
			$this->saldotitipan->EditCustomAttributes = "";
			$this->saldotitipan->EditValue = ew_HtmlEncode($this->saldotitipan->AdvancedSearch->SearchValue);
			$this->saldotitipan->PlaceHolder = ew_RemoveHtml($this->saldotitipan->FldCaption());

			// saldotitipansisa
			$this->saldotitipansisa->EditAttrs["class"] = "form-control";
			$this->saldotitipansisa->EditCustomAttributes = "";
			$this->saldotitipansisa->EditValue = ew_HtmlEncode($this->saldotitipansisa->AdvancedSearch->SearchValue);
			$this->saldotitipansisa->PlaceHolder = ew_RemoveHtml($this->saldotitipansisa->FldCaption());

			// bayarpokok
			$this->bayarpokok->EditAttrs["class"] = "form-control";
			$this->bayarpokok->EditCustomAttributes = "";
			$this->bayarpokok->EditValue = ew_HtmlEncode($this->bayarpokok->AdvancedSearch->SearchValue);
			$this->bayarpokok->PlaceHolder = ew_RemoveHtml($this->bayarpokok->FldCaption());

			// bayarpokokauto
			$this->bayarpokokauto->EditAttrs["class"] = "form-control";
			$this->bayarpokokauto->EditCustomAttributes = "";
			$this->bayarpokokauto->EditValue = ew_HtmlEncode($this->bayarpokokauto->AdvancedSearch->SearchValue);
			$this->bayarpokokauto->PlaceHolder = ew_RemoveHtml($this->bayarpokokauto->FldCaption());

			// bayarbunga
			$this->bayarbunga->EditAttrs["class"] = "form-control";
			$this->bayarbunga->EditCustomAttributes = "";
			$this->bayarbunga->EditValue = ew_HtmlEncode($this->bayarbunga->AdvancedSearch->SearchValue);
			$this->bayarbunga->PlaceHolder = ew_RemoveHtml($this->bayarbunga->FldCaption());

			// bayarbungaauto
			$this->bayarbungaauto->EditAttrs["class"] = "form-control";
			$this->bayarbungaauto->EditCustomAttributes = "";
			$this->bayarbungaauto->EditValue = ew_HtmlEncode($this->bayarbungaauto->AdvancedSearch->SearchValue);
			$this->bayarbungaauto->PlaceHolder = ew_RemoveHtml($this->bayarbungaauto->FldCaption());

			// bayardenda
			$this->bayardenda->EditAttrs["class"] = "form-control";
			$this->bayardenda->EditCustomAttributes = "";
			$this->bayardenda->EditValue = ew_HtmlEncode($this->bayardenda->AdvancedSearch->SearchValue);
			$this->bayardenda->PlaceHolder = ew_RemoveHtml($this->bayardenda->FldCaption());

			// bayardendaauto
			$this->bayardendaauto->EditAttrs["class"] = "form-control";
			$this->bayardendaauto->EditCustomAttributes = "";
			$this->bayardendaauto->EditValue = ew_HtmlEncode($this->bayardendaauto->AdvancedSearch->SearchValue);
			$this->bayardendaauto->PlaceHolder = ew_RemoveHtml($this->bayardendaauto->FldCaption());

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

			// totalbayar
			$this->totalbayar->EditAttrs["class"] = "form-control";
			$this->totalbayar->EditCustomAttributes = "";
			$this->totalbayar->EditValue = ew_HtmlEncode($this->totalbayar->AdvancedSearch->SearchValue);
			$this->totalbayar->PlaceHolder = ew_RemoveHtml($this->totalbayar->FldCaption());

			// totalbayarauto
			$this->totalbayarauto->EditAttrs["class"] = "form-control";
			$this->totalbayarauto->EditCustomAttributes = "";
			$this->totalbayarauto->EditValue = ew_HtmlEncode($this->totalbayarauto->AdvancedSearch->SearchValue);
			$this->totalbayarauto->PlaceHolder = ew_RemoveHtml($this->totalbayarauto->FldCaption());

			// pelunasan
			$this->pelunasan->EditAttrs["class"] = "form-control";
			$this->pelunasan->EditCustomAttributes = "";
			$this->pelunasan->EditValue = ew_HtmlEncode($this->pelunasan->AdvancedSearch->SearchValue);
			$this->pelunasan->PlaceHolder = ew_RemoveHtml($this->pelunasan->FldCaption());

			// pelunasanauto
			$this->pelunasanauto->EditAttrs["class"] = "form-control";
			$this->pelunasanauto->EditCustomAttributes = "";
			$this->pelunasanauto->EditValue = ew_HtmlEncode($this->pelunasanauto->AdvancedSearch->SearchValue);
			$this->pelunasanauto->PlaceHolder = ew_RemoveHtml($this->pelunasanauto->FldCaption());

			// finalty
			$this->finalty->EditAttrs["class"] = "form-control";
			$this->finalty->EditCustomAttributes = "";
			$this->finalty->EditValue = ew_HtmlEncode($this->finalty->AdvancedSearch->SearchValue);
			$this->finalty->PlaceHolder = ew_RemoveHtml($this->finalty->FldCaption());

			// finaltyauto
			$this->finaltyauto->EditAttrs["class"] = "form-control";
			$this->finaltyauto->EditCustomAttributes = "";
			$this->finaltyauto->EditValue = ew_HtmlEncode($this->finaltyauto->AdvancedSearch->SearchValue);
			$this->finaltyauto->PlaceHolder = ew_RemoveHtml($this->finaltyauto->FldCaption());

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
		$this->terlambat->AdvancedSearch->Load();
		$this->dispensasidenda->AdvancedSearch->Load();
		$this->plafond->AdvancedSearch->Load();
		$this->angsuranpokok->AdvancedSearch->Load();
		$this->angsuranpokokauto->AdvancedSearch->Load();
		$this->angsuranbunga->AdvancedSearch->Load();
		$this->angsuranbungaauto->AdvancedSearch->Load();
		$this->denda->AdvancedSearch->Load();
		$this->dendapersen->AdvancedSearch->Load();
		$this->totalangsuran->AdvancedSearch->Load();
		$this->totalangsuranauto->AdvancedSearch->Load();
		$this->sisaangsuran->AdvancedSearch->Load();
		$this->sisaangsuranauto->AdvancedSearch->Load();
		$this->saldotitipan->AdvancedSearch->Load();
		$this->saldotitipansisa->AdvancedSearch->Load();
		$this->bayarpokok->AdvancedSearch->Load();
		$this->bayarpokokauto->AdvancedSearch->Load();
		$this->bayarbunga->AdvancedSearch->Load();
		$this->bayarbungaauto->AdvancedSearch->Load();
		$this->bayardenda->AdvancedSearch->Load();
		$this->bayardendaauto->AdvancedSearch->Load();
		$this->bayartitipan->AdvancedSearch->Load();
		$this->bayartitipanauto->AdvancedSearch->Load();
		$this->totalbayar->AdvancedSearch->Load();
		$this->totalbayarauto->AdvancedSearch->Load();
		$this->pelunasan->AdvancedSearch->Load();
		$this->pelunasanauto->AdvancedSearch->Load();
		$this->finalty->AdvancedSearch->Load();
		$this->finaltyauto->AdvancedSearch->Load();
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
if (!isset($tbayarangsuran_list)) $tbayarangsuran_list = new ctbayarangsuran_list();

// Page init
$tbayarangsuran_list->Page_Init();

// Page main
$tbayarangsuran_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tbayarangsuran_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = ftbayarangsuranlist = new ew_Form("ftbayarangsuranlist", "list");
ftbayarangsuranlist.FormKeyCountName = '<?php echo $tbayarangsuran_list->FormKeyCountName ?>';

// Form_CustomValidate event
ftbayarangsuranlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbayarangsuranlist.ValidateRequired = true;
<?php } else { ?>
ftbayarangsuranlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftbayarangsuranlist.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftbayarangsuranlist.Lists["x_active"].Options = <?php echo json_encode($tbayarangsuran->active->Options()) ?>;

// Form object for search
var CurrentSearchForm = ftbayarangsuranlistsrch = new ew_Form("ftbayarangsuranlistsrch");

// Validate function for search
ftbayarangsuranlistsrch.Validate = function(fobj) {
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
ftbayarangsuranlistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbayarangsuranlistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
ftbayarangsuranlistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
ftbayarangsuranlistsrch.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftbayarangsuranlistsrch.Lists["x_active"].Options = <?php echo json_encode($tbayarangsuran->active->Options()) ?>;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($tbayarangsuran_list->TotalRecs > 0 && $tbayarangsuran_list->ExportOptions->Visible()) { ?>
<?php $tbayarangsuran_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($tbayarangsuran_list->SearchOptions->Visible()) { ?>
<?php $tbayarangsuran_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($tbayarangsuran_list->FilterOptions->Visible()) { ?>
<?php $tbayarangsuran_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $tbayarangsuran_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($tbayarangsuran_list->TotalRecs <= 0)
			$tbayarangsuran_list->TotalRecs = $tbayarangsuran->SelectRecordCount();
	} else {
		if (!$tbayarangsuran_list->Recordset && ($tbayarangsuran_list->Recordset = $tbayarangsuran_list->LoadRecordset()))
			$tbayarangsuran_list->TotalRecs = $tbayarangsuran_list->Recordset->RecordCount();
	}
	$tbayarangsuran_list->StartRec = 1;
	if ($tbayarangsuran_list->DisplayRecs <= 0 || ($tbayarangsuran->Export <> "" && $tbayarangsuran->ExportAll)) // Display all records
		$tbayarangsuran_list->DisplayRecs = $tbayarangsuran_list->TotalRecs;
	if (!($tbayarangsuran->Export <> "" && $tbayarangsuran->ExportAll))
		$tbayarangsuran_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$tbayarangsuran_list->Recordset = $tbayarangsuran_list->LoadRecordset($tbayarangsuran_list->StartRec-1, $tbayarangsuran_list->DisplayRecs);

	// Set no record found message
	if ($tbayarangsuran->CurrentAction == "" && $tbayarangsuran_list->TotalRecs == 0) {
		if ($tbayarangsuran_list->SearchWhere == "0=101")
			$tbayarangsuran_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$tbayarangsuran_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$tbayarangsuran_list->RenderOtherOptions();
?>
<?php if ($tbayarangsuran->Export == "" && $tbayarangsuran->CurrentAction == "") { ?>
<form name="ftbayarangsuranlistsrch" id="ftbayarangsuranlistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($tbayarangsuran_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="ftbayarangsuranlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="tbayarangsuran">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$tbayarangsuran_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$tbayarangsuran->RowType = EW_ROWTYPE_SEARCH;

// Render row
$tbayarangsuran->ResetAttrs();
$tbayarangsuran_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($tbayarangsuran->active->Visible) { // active ?>
	<div id="xsc_active" class="ewCell form-group">
		<label class="ewSearchCaption ewLabel"><?php echo $tbayarangsuran->active->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_active" id="z_active" value="="></span>
		<span class="ewSearchField">
<div id="tp_x_active" class="ewTemplate"><input type="radio" data-table="tbayarangsuran" data-field="x_active" data-value-separator="<?php echo $tbayarangsuran->active->DisplayValueSeparatorAttribute() ?>" name="x_active" id="x_active" value="{value}"<?php echo $tbayarangsuran->active->EditAttributes() ?>></div>
<div id="dsl_x_active" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $tbayarangsuran->active->RadioButtonListHtml(FALSE, "x_active") ?>
</div></div>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($tbayarangsuran_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($tbayarangsuran_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $tbayarangsuran_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($tbayarangsuran_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($tbayarangsuran_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($tbayarangsuran_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($tbayarangsuran_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php $tbayarangsuran_list->ShowPageHeader(); ?>
<?php
$tbayarangsuran_list->ShowMessage();
?>
<?php if ($tbayarangsuran_list->TotalRecs > 0 || $tbayarangsuran->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid tbayarangsuran">
<form name="ftbayarangsuranlist" id="ftbayarangsuranlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tbayarangsuran_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tbayarangsuran_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tbayarangsuran">
<div id="gmp_tbayarangsuran" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($tbayarangsuran_list->TotalRecs > 0 || $tbayarangsuran->CurrentAction == "gridedit") { ?>
<table id="tbl_tbayarangsuranlist" class="table ewTable">
<?php echo $tbayarangsuran->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$tbayarangsuran_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$tbayarangsuran_list->RenderListOptions();

// Render list options (header, left)
$tbayarangsuran_list->ListOptions->Render("header", "left");
?>
<?php if ($tbayarangsuran->tanggal->Visible) { // tanggal ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->tanggal) == "") { ?>
		<th data-name="tanggal"><div id="elh_tbayarangsuran_tanggal" class="tbayarangsuran_tanggal"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->tanggal->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tanggal"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->tanggal) ?>',1);"><div id="elh_tbayarangsuran_tanggal" class="tbayarangsuran_tanggal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->tanggal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->tanggal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->tanggal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->periode->Visible) { // periode ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->periode) == "") { ?>
		<th data-name="periode"><div id="elh_tbayarangsuran_periode" class="tbayarangsuran_periode"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->periode->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="periode"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->periode) ?>',1);"><div id="elh_tbayarangsuran_periode" class="tbayarangsuran_periode">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->periode->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->periode->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->periode->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->id->Visible) { // id ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->id) == "") { ?>
		<th data-name="id"><div id="elh_tbayarangsuran_id" class="tbayarangsuran_id"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->id) ?>',1);"><div id="elh_tbayarangsuran_id" class="tbayarangsuran_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->id->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->transaksi->Visible) { // transaksi ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->transaksi) == "") { ?>
		<th data-name="transaksi"><div id="elh_tbayarangsuran_transaksi" class="tbayarangsuran_transaksi"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->transaksi->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="transaksi"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->transaksi) ?>',1);"><div id="elh_tbayarangsuran_transaksi" class="tbayarangsuran_transaksi">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->transaksi->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->transaksi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->transaksi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->referensi->Visible) { // referensi ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->referensi) == "") { ?>
		<th data-name="referensi"><div id="elh_tbayarangsuran_referensi" class="tbayarangsuran_referensi"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->referensi->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="referensi"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->referensi) ?>',1);"><div id="elh_tbayarangsuran_referensi" class="tbayarangsuran_referensi">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->referensi->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->referensi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->referensi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->anggota->Visible) { // anggota ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->anggota) == "") { ?>
		<th data-name="anggota"><div id="elh_tbayarangsuran_anggota" class="tbayarangsuran_anggota"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->anggota->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="anggota"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->anggota) ?>',1);"><div id="elh_tbayarangsuran_anggota" class="tbayarangsuran_anggota">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->anggota->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->anggota->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->anggota->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->namaanggota->Visible) { // namaanggota ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->namaanggota) == "") { ?>
		<th data-name="namaanggota"><div id="elh_tbayarangsuran_namaanggota" class="tbayarangsuran_namaanggota"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->namaanggota->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="namaanggota"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->namaanggota) ?>',1);"><div id="elh_tbayarangsuran_namaanggota" class="tbayarangsuran_namaanggota">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->namaanggota->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->namaanggota->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->namaanggota->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->alamat->Visible) { // alamat ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->alamat) == "") { ?>
		<th data-name="alamat"><div id="elh_tbayarangsuran_alamat" class="tbayarangsuran_alamat"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->alamat->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="alamat"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->alamat) ?>',1);"><div id="elh_tbayarangsuran_alamat" class="tbayarangsuran_alamat">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->alamat->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->alamat->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->alamat->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->pekerjaan->Visible) { // pekerjaan ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->pekerjaan) == "") { ?>
		<th data-name="pekerjaan"><div id="elh_tbayarangsuran_pekerjaan" class="tbayarangsuran_pekerjaan"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->pekerjaan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pekerjaan"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->pekerjaan) ?>',1);"><div id="elh_tbayarangsuran_pekerjaan" class="tbayarangsuran_pekerjaan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->pekerjaan->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->pekerjaan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->pekerjaan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->telepon->Visible) { // telepon ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->telepon) == "") { ?>
		<th data-name="telepon"><div id="elh_tbayarangsuran_telepon" class="tbayarangsuran_telepon"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->telepon->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="telepon"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->telepon) ?>',1);"><div id="elh_tbayarangsuran_telepon" class="tbayarangsuran_telepon">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->telepon->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->telepon->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->telepon->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->hp->Visible) { // hp ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->hp) == "") { ?>
		<th data-name="hp"><div id="elh_tbayarangsuran_hp" class="tbayarangsuran_hp"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->hp->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="hp"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->hp) ?>',1);"><div id="elh_tbayarangsuran_hp" class="tbayarangsuran_hp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->hp->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->hp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->hp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->fax->Visible) { // fax ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->fax) == "") { ?>
		<th data-name="fax"><div id="elh_tbayarangsuran_fax" class="tbayarangsuran_fax"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->fax->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fax"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->fax) ?>',1);"><div id="elh_tbayarangsuran_fax" class="tbayarangsuran_fax">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->fax->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->fax->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->fax->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->_email->Visible) { // email ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->_email) == "") { ?>
		<th data-name="_email"><div id="elh_tbayarangsuran__email" class="tbayarangsuran__email"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->_email->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_email"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->_email) ?>',1);"><div id="elh_tbayarangsuran__email" class="tbayarangsuran__email">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->_email->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->_email->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->_email->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->website->Visible) { // website ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->website) == "") { ?>
		<th data-name="website"><div id="elh_tbayarangsuran_website" class="tbayarangsuran_website"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->website->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="website"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->website) ?>',1);"><div id="elh_tbayarangsuran_website" class="tbayarangsuran_website">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->website->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->website->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->website->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->jenisanggota->Visible) { // jenisanggota ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->jenisanggota) == "") { ?>
		<th data-name="jenisanggota"><div id="elh_tbayarangsuran_jenisanggota" class="tbayarangsuran_jenisanggota"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->jenisanggota->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jenisanggota"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->jenisanggota) ?>',1);"><div id="elh_tbayarangsuran_jenisanggota" class="tbayarangsuran_jenisanggota">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->jenisanggota->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->jenisanggota->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->jenisanggota->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->model->Visible) { // model ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->model) == "") { ?>
		<th data-name="model"><div id="elh_tbayarangsuran_model" class="tbayarangsuran_model"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->model->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="model"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->model) ?>',1);"><div id="elh_tbayarangsuran_model" class="tbayarangsuran_model">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->model->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->model->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->model->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->jenispinjaman->Visible) { // jenispinjaman ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->jenispinjaman) == "") { ?>
		<th data-name="jenispinjaman"><div id="elh_tbayarangsuran_jenispinjaman" class="tbayarangsuran_jenispinjaman"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->jenispinjaman->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jenispinjaman"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->jenispinjaman) ?>',1);"><div id="elh_tbayarangsuran_jenispinjaman" class="tbayarangsuran_jenispinjaman">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->jenispinjaman->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->jenispinjaman->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->jenispinjaman->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->jenisbunga->Visible) { // jenisbunga ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->jenisbunga) == "") { ?>
		<th data-name="jenisbunga"><div id="elh_tbayarangsuran_jenisbunga" class="tbayarangsuran_jenisbunga"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->jenisbunga->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jenisbunga"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->jenisbunga) ?>',1);"><div id="elh_tbayarangsuran_jenisbunga" class="tbayarangsuran_jenisbunga">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->jenisbunga->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->jenisbunga->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->jenisbunga->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->angsuran->Visible) { // angsuran ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->angsuran) == "") { ?>
		<th data-name="angsuran"><div id="elh_tbayarangsuran_angsuran" class="tbayarangsuran_angsuran"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->angsuran->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="angsuran"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->angsuran) ?>',1);"><div id="elh_tbayarangsuran_angsuran" class="tbayarangsuran_angsuran">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->angsuran->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->angsuran->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->angsuran->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->masaangsuran->Visible) { // masaangsuran ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->masaangsuran) == "") { ?>
		<th data-name="masaangsuran"><div id="elh_tbayarangsuran_masaangsuran" class="tbayarangsuran_masaangsuran"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->masaangsuran->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="masaangsuran"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->masaangsuran) ?>',1);"><div id="elh_tbayarangsuran_masaangsuran" class="tbayarangsuran_masaangsuran">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->masaangsuran->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->masaangsuran->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->masaangsuran->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->jatuhtempo->Visible) { // jatuhtempo ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->jatuhtempo) == "") { ?>
		<th data-name="jatuhtempo"><div id="elh_tbayarangsuran_jatuhtempo" class="tbayarangsuran_jatuhtempo"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->jatuhtempo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jatuhtempo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->jatuhtempo) ?>',1);"><div id="elh_tbayarangsuran_jatuhtempo" class="tbayarangsuran_jatuhtempo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->jatuhtempo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->jatuhtempo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->jatuhtempo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->terlambat->Visible) { // terlambat ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->terlambat) == "") { ?>
		<th data-name="terlambat"><div id="elh_tbayarangsuran_terlambat" class="tbayarangsuran_terlambat"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->terlambat->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="terlambat"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->terlambat) ?>',1);"><div id="elh_tbayarangsuran_terlambat" class="tbayarangsuran_terlambat">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->terlambat->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->terlambat->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->terlambat->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->dispensasidenda->Visible) { // dispensasidenda ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->dispensasidenda) == "") { ?>
		<th data-name="dispensasidenda"><div id="elh_tbayarangsuran_dispensasidenda" class="tbayarangsuran_dispensasidenda"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->dispensasidenda->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="dispensasidenda"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->dispensasidenda) ?>',1);"><div id="elh_tbayarangsuran_dispensasidenda" class="tbayarangsuran_dispensasidenda">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->dispensasidenda->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->dispensasidenda->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->dispensasidenda->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->plafond->Visible) { // plafond ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->plafond) == "") { ?>
		<th data-name="plafond"><div id="elh_tbayarangsuran_plafond" class="tbayarangsuran_plafond"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->plafond->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="plafond"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->plafond) ?>',1);"><div id="elh_tbayarangsuran_plafond" class="tbayarangsuran_plafond">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->plafond->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->plafond->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->plafond->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->angsuranpokok->Visible) { // angsuranpokok ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->angsuranpokok) == "") { ?>
		<th data-name="angsuranpokok"><div id="elh_tbayarangsuran_angsuranpokok" class="tbayarangsuran_angsuranpokok"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->angsuranpokok->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="angsuranpokok"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->angsuranpokok) ?>',1);"><div id="elh_tbayarangsuran_angsuranpokok" class="tbayarangsuran_angsuranpokok">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->angsuranpokok->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->angsuranpokok->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->angsuranpokok->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->angsuranpokokauto->Visible) { // angsuranpokokauto ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->angsuranpokokauto) == "") { ?>
		<th data-name="angsuranpokokauto"><div id="elh_tbayarangsuran_angsuranpokokauto" class="tbayarangsuran_angsuranpokokauto"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->angsuranpokokauto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="angsuranpokokauto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->angsuranpokokauto) ?>',1);"><div id="elh_tbayarangsuran_angsuranpokokauto" class="tbayarangsuran_angsuranpokokauto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->angsuranpokokauto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->angsuranpokokauto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->angsuranpokokauto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->angsuranbunga->Visible) { // angsuranbunga ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->angsuranbunga) == "") { ?>
		<th data-name="angsuranbunga"><div id="elh_tbayarangsuran_angsuranbunga" class="tbayarangsuran_angsuranbunga"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->angsuranbunga->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="angsuranbunga"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->angsuranbunga) ?>',1);"><div id="elh_tbayarangsuran_angsuranbunga" class="tbayarangsuran_angsuranbunga">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->angsuranbunga->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->angsuranbunga->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->angsuranbunga->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->angsuranbungaauto->Visible) { // angsuranbungaauto ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->angsuranbungaauto) == "") { ?>
		<th data-name="angsuranbungaauto"><div id="elh_tbayarangsuran_angsuranbungaauto" class="tbayarangsuran_angsuranbungaauto"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->angsuranbungaauto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="angsuranbungaauto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->angsuranbungaauto) ?>',1);"><div id="elh_tbayarangsuran_angsuranbungaauto" class="tbayarangsuran_angsuranbungaauto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->angsuranbungaauto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->angsuranbungaauto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->angsuranbungaauto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->denda->Visible) { // denda ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->denda) == "") { ?>
		<th data-name="denda"><div id="elh_tbayarangsuran_denda" class="tbayarangsuran_denda"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->denda->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="denda"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->denda) ?>',1);"><div id="elh_tbayarangsuran_denda" class="tbayarangsuran_denda">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->denda->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->denda->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->denda->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->dendapersen->Visible) { // dendapersen ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->dendapersen) == "") { ?>
		<th data-name="dendapersen"><div id="elh_tbayarangsuran_dendapersen" class="tbayarangsuran_dendapersen"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->dendapersen->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="dendapersen"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->dendapersen) ?>',1);"><div id="elh_tbayarangsuran_dendapersen" class="tbayarangsuran_dendapersen">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->dendapersen->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->dendapersen->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->dendapersen->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->totalangsuran->Visible) { // totalangsuran ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->totalangsuran) == "") { ?>
		<th data-name="totalangsuran"><div id="elh_tbayarangsuran_totalangsuran" class="tbayarangsuran_totalangsuran"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->totalangsuran->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="totalangsuran"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->totalangsuran) ?>',1);"><div id="elh_tbayarangsuran_totalangsuran" class="tbayarangsuran_totalangsuran">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->totalangsuran->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->totalangsuran->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->totalangsuran->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->totalangsuranauto->Visible) { // totalangsuranauto ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->totalangsuranauto) == "") { ?>
		<th data-name="totalangsuranauto"><div id="elh_tbayarangsuran_totalangsuranauto" class="tbayarangsuran_totalangsuranauto"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->totalangsuranauto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="totalangsuranauto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->totalangsuranauto) ?>',1);"><div id="elh_tbayarangsuran_totalangsuranauto" class="tbayarangsuran_totalangsuranauto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->totalangsuranauto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->totalangsuranauto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->totalangsuranauto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->sisaangsuran->Visible) { // sisaangsuran ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->sisaangsuran) == "") { ?>
		<th data-name="sisaangsuran"><div id="elh_tbayarangsuran_sisaangsuran" class="tbayarangsuran_sisaangsuran"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->sisaangsuran->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="sisaangsuran"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->sisaangsuran) ?>',1);"><div id="elh_tbayarangsuran_sisaangsuran" class="tbayarangsuran_sisaangsuran">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->sisaangsuran->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->sisaangsuran->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->sisaangsuran->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->sisaangsuranauto->Visible) { // sisaangsuranauto ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->sisaangsuranauto) == "") { ?>
		<th data-name="sisaangsuranauto"><div id="elh_tbayarangsuran_sisaangsuranauto" class="tbayarangsuran_sisaangsuranauto"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->sisaangsuranauto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="sisaangsuranauto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->sisaangsuranauto) ?>',1);"><div id="elh_tbayarangsuran_sisaangsuranauto" class="tbayarangsuran_sisaangsuranauto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->sisaangsuranauto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->sisaangsuranauto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->sisaangsuranauto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->saldotitipan->Visible) { // saldotitipan ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->saldotitipan) == "") { ?>
		<th data-name="saldotitipan"><div id="elh_tbayarangsuran_saldotitipan" class="tbayarangsuran_saldotitipan"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->saldotitipan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="saldotitipan"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->saldotitipan) ?>',1);"><div id="elh_tbayarangsuran_saldotitipan" class="tbayarangsuran_saldotitipan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->saldotitipan->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->saldotitipan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->saldotitipan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->saldotitipansisa->Visible) { // saldotitipansisa ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->saldotitipansisa) == "") { ?>
		<th data-name="saldotitipansisa"><div id="elh_tbayarangsuran_saldotitipansisa" class="tbayarangsuran_saldotitipansisa"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->saldotitipansisa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="saldotitipansisa"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->saldotitipansisa) ?>',1);"><div id="elh_tbayarangsuran_saldotitipansisa" class="tbayarangsuran_saldotitipansisa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->saldotitipansisa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->saldotitipansisa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->saldotitipansisa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->bayarpokok->Visible) { // bayarpokok ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->bayarpokok) == "") { ?>
		<th data-name="bayarpokok"><div id="elh_tbayarangsuran_bayarpokok" class="tbayarangsuran_bayarpokok"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->bayarpokok->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bayarpokok"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->bayarpokok) ?>',1);"><div id="elh_tbayarangsuran_bayarpokok" class="tbayarangsuran_bayarpokok">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->bayarpokok->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->bayarpokok->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->bayarpokok->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->bayarpokokauto->Visible) { // bayarpokokauto ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->bayarpokokauto) == "") { ?>
		<th data-name="bayarpokokauto"><div id="elh_tbayarangsuran_bayarpokokauto" class="tbayarangsuran_bayarpokokauto"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->bayarpokokauto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bayarpokokauto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->bayarpokokauto) ?>',1);"><div id="elh_tbayarangsuran_bayarpokokauto" class="tbayarangsuran_bayarpokokauto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->bayarpokokauto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->bayarpokokauto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->bayarpokokauto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->bayarbunga->Visible) { // bayarbunga ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->bayarbunga) == "") { ?>
		<th data-name="bayarbunga"><div id="elh_tbayarangsuran_bayarbunga" class="tbayarangsuran_bayarbunga"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->bayarbunga->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bayarbunga"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->bayarbunga) ?>',1);"><div id="elh_tbayarangsuran_bayarbunga" class="tbayarangsuran_bayarbunga">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->bayarbunga->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->bayarbunga->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->bayarbunga->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->bayarbungaauto->Visible) { // bayarbungaauto ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->bayarbungaauto) == "") { ?>
		<th data-name="bayarbungaauto"><div id="elh_tbayarangsuran_bayarbungaauto" class="tbayarangsuran_bayarbungaauto"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->bayarbungaauto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bayarbungaauto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->bayarbungaauto) ?>',1);"><div id="elh_tbayarangsuran_bayarbungaauto" class="tbayarangsuran_bayarbungaauto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->bayarbungaauto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->bayarbungaauto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->bayarbungaauto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->bayardenda->Visible) { // bayardenda ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->bayardenda) == "") { ?>
		<th data-name="bayardenda"><div id="elh_tbayarangsuran_bayardenda" class="tbayarangsuran_bayardenda"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->bayardenda->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bayardenda"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->bayardenda) ?>',1);"><div id="elh_tbayarangsuran_bayardenda" class="tbayarangsuran_bayardenda">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->bayardenda->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->bayardenda->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->bayardenda->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->bayardendaauto->Visible) { // bayardendaauto ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->bayardendaauto) == "") { ?>
		<th data-name="bayardendaauto"><div id="elh_tbayarangsuran_bayardendaauto" class="tbayarangsuran_bayardendaauto"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->bayardendaauto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bayardendaauto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->bayardendaauto) ?>',1);"><div id="elh_tbayarangsuran_bayardendaauto" class="tbayarangsuran_bayardendaauto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->bayardendaauto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->bayardendaauto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->bayardendaauto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->bayartitipan->Visible) { // bayartitipan ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->bayartitipan) == "") { ?>
		<th data-name="bayartitipan"><div id="elh_tbayarangsuran_bayartitipan" class="tbayarangsuran_bayartitipan"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->bayartitipan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bayartitipan"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->bayartitipan) ?>',1);"><div id="elh_tbayarangsuran_bayartitipan" class="tbayarangsuran_bayartitipan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->bayartitipan->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->bayartitipan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->bayartitipan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->bayartitipanauto->Visible) { // bayartitipanauto ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->bayartitipanauto) == "") { ?>
		<th data-name="bayartitipanauto"><div id="elh_tbayarangsuran_bayartitipanauto" class="tbayarangsuran_bayartitipanauto"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->bayartitipanauto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bayartitipanauto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->bayartitipanauto) ?>',1);"><div id="elh_tbayarangsuran_bayartitipanauto" class="tbayarangsuran_bayartitipanauto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->bayartitipanauto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->bayartitipanauto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->bayartitipanauto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->totalbayar->Visible) { // totalbayar ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->totalbayar) == "") { ?>
		<th data-name="totalbayar"><div id="elh_tbayarangsuran_totalbayar" class="tbayarangsuran_totalbayar"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->totalbayar->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="totalbayar"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->totalbayar) ?>',1);"><div id="elh_tbayarangsuran_totalbayar" class="tbayarangsuran_totalbayar">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->totalbayar->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->totalbayar->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->totalbayar->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->totalbayarauto->Visible) { // totalbayarauto ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->totalbayarauto) == "") { ?>
		<th data-name="totalbayarauto"><div id="elh_tbayarangsuran_totalbayarauto" class="tbayarangsuran_totalbayarauto"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->totalbayarauto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="totalbayarauto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->totalbayarauto) ?>',1);"><div id="elh_tbayarangsuran_totalbayarauto" class="tbayarangsuran_totalbayarauto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->totalbayarauto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->totalbayarauto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->totalbayarauto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->pelunasan->Visible) { // pelunasan ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->pelunasan) == "") { ?>
		<th data-name="pelunasan"><div id="elh_tbayarangsuran_pelunasan" class="tbayarangsuran_pelunasan"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->pelunasan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pelunasan"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->pelunasan) ?>',1);"><div id="elh_tbayarangsuran_pelunasan" class="tbayarangsuran_pelunasan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->pelunasan->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->pelunasan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->pelunasan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->pelunasanauto->Visible) { // pelunasanauto ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->pelunasanauto) == "") { ?>
		<th data-name="pelunasanauto"><div id="elh_tbayarangsuran_pelunasanauto" class="tbayarangsuran_pelunasanauto"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->pelunasanauto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pelunasanauto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->pelunasanauto) ?>',1);"><div id="elh_tbayarangsuran_pelunasanauto" class="tbayarangsuran_pelunasanauto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->pelunasanauto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->pelunasanauto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->pelunasanauto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->finalty->Visible) { // finalty ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->finalty) == "") { ?>
		<th data-name="finalty"><div id="elh_tbayarangsuran_finalty" class="tbayarangsuran_finalty"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->finalty->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="finalty"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->finalty) ?>',1);"><div id="elh_tbayarangsuran_finalty" class="tbayarangsuran_finalty">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->finalty->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->finalty->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->finalty->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->finaltyauto->Visible) { // finaltyauto ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->finaltyauto) == "") { ?>
		<th data-name="finaltyauto"><div id="elh_tbayarangsuran_finaltyauto" class="tbayarangsuran_finaltyauto"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->finaltyauto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="finaltyauto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->finaltyauto) ?>',1);"><div id="elh_tbayarangsuran_finaltyauto" class="tbayarangsuran_finaltyauto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->finaltyauto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->finaltyauto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->finaltyauto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->terbilang->Visible) { // terbilang ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->terbilang) == "") { ?>
		<th data-name="terbilang"><div id="elh_tbayarangsuran_terbilang" class="tbayarangsuran_terbilang"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->terbilang->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="terbilang"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->terbilang) ?>',1);"><div id="elh_tbayarangsuran_terbilang" class="tbayarangsuran_terbilang">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->terbilang->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->terbilang->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->terbilang->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->petugas->Visible) { // petugas ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->petugas) == "") { ?>
		<th data-name="petugas"><div id="elh_tbayarangsuran_petugas" class="tbayarangsuran_petugas"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->petugas->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="petugas"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->petugas) ?>',1);"><div id="elh_tbayarangsuran_petugas" class="tbayarangsuran_petugas">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->petugas->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->petugas->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->petugas->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->pembayaran->Visible) { // pembayaran ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->pembayaran) == "") { ?>
		<th data-name="pembayaran"><div id="elh_tbayarangsuran_pembayaran" class="tbayarangsuran_pembayaran"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->pembayaran->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pembayaran"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->pembayaran) ?>',1);"><div id="elh_tbayarangsuran_pembayaran" class="tbayarangsuran_pembayaran">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->pembayaran->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->pembayaran->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->pembayaran->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->bank->Visible) { // bank ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->bank) == "") { ?>
		<th data-name="bank"><div id="elh_tbayarangsuran_bank" class="tbayarangsuran_bank"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->bank->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bank"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->bank) ?>',1);"><div id="elh_tbayarangsuran_bank" class="tbayarangsuran_bank">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->bank->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->bank->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->bank->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->atasnama->Visible) { // atasnama ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->atasnama) == "") { ?>
		<th data-name="atasnama"><div id="elh_tbayarangsuran_atasnama" class="tbayarangsuran_atasnama"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->atasnama->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="atasnama"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->atasnama) ?>',1);"><div id="elh_tbayarangsuran_atasnama" class="tbayarangsuran_atasnama">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->atasnama->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->atasnama->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->atasnama->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->tipe->Visible) { // tipe ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->tipe) == "") { ?>
		<th data-name="tipe"><div id="elh_tbayarangsuran_tipe" class="tbayarangsuran_tipe"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->tipe->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipe"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->tipe) ?>',1);"><div id="elh_tbayarangsuran_tipe" class="tbayarangsuran_tipe">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->tipe->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->tipe->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->tipe->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->kantor->Visible) { // kantor ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->kantor) == "") { ?>
		<th data-name="kantor"><div id="elh_tbayarangsuran_kantor" class="tbayarangsuran_kantor"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->kantor->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="kantor"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->kantor) ?>',1);"><div id="elh_tbayarangsuran_kantor" class="tbayarangsuran_kantor">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->kantor->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->kantor->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->kantor->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->keterangan->Visible) { // keterangan ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->keterangan) == "") { ?>
		<th data-name="keterangan"><div id="elh_tbayarangsuran_keterangan" class="tbayarangsuran_keterangan"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->keterangan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="keterangan"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->keterangan) ?>',1);"><div id="elh_tbayarangsuran_keterangan" class="tbayarangsuran_keterangan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->keterangan->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->keterangan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->keterangan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->active->Visible) { // active ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->active) == "") { ?>
		<th data-name="active"><div id="elh_tbayarangsuran_active" class="tbayarangsuran_active"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->active->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="active"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->active) ?>',1);"><div id="elh_tbayarangsuran_active" class="tbayarangsuran_active">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->active->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->active->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->active->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->ip->Visible) { // ip ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->ip) == "") { ?>
		<th data-name="ip"><div id="elh_tbayarangsuran_ip" class="tbayarangsuran_ip"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->ip->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ip"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->ip) ?>',1);"><div id="elh_tbayarangsuran_ip" class="tbayarangsuran_ip">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->ip->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->ip->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->ip->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->status->Visible) { // status ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->status) == "") { ?>
		<th data-name="status"><div id="elh_tbayarangsuran_status" class="tbayarangsuran_status"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->status->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->status) ?>',1);"><div id="elh_tbayarangsuran_status" class="tbayarangsuran_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->status->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->user->Visible) { // user ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->user) == "") { ?>
		<th data-name="user"><div id="elh_tbayarangsuran_user" class="tbayarangsuran_user"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->user->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="user"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->user) ?>',1);"><div id="elh_tbayarangsuran_user" class="tbayarangsuran_user">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->user->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->user->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->user->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->created->Visible) { // created ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->created) == "") { ?>
		<th data-name="created"><div id="elh_tbayarangsuran_created" class="tbayarangsuran_created"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->created->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="created"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->created) ?>',1);"><div id="elh_tbayarangsuran_created" class="tbayarangsuran_created">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->created->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->created->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->created->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($tbayarangsuran->modified->Visible) { // modified ?>
	<?php if ($tbayarangsuran->SortUrl($tbayarangsuran->modified) == "") { ?>
		<th data-name="modified"><div id="elh_tbayarangsuran_modified" class="tbayarangsuran_modified"><div class="ewTableHeaderCaption"><?php echo $tbayarangsuran->modified->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="modified"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbayarangsuran->SortUrl($tbayarangsuran->modified) ?>',1);"><div id="elh_tbayarangsuran_modified" class="tbayarangsuran_modified">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbayarangsuran->modified->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbayarangsuran->modified->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbayarangsuran->modified->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$tbayarangsuran_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($tbayarangsuran->ExportAll && $tbayarangsuran->Export <> "") {
	$tbayarangsuran_list->StopRec = $tbayarangsuran_list->TotalRecs;
} else {

	// Set the last record to display
	if ($tbayarangsuran_list->TotalRecs > $tbayarangsuran_list->StartRec + $tbayarangsuran_list->DisplayRecs - 1)
		$tbayarangsuran_list->StopRec = $tbayarangsuran_list->StartRec + $tbayarangsuran_list->DisplayRecs - 1;
	else
		$tbayarangsuran_list->StopRec = $tbayarangsuran_list->TotalRecs;
}
$tbayarangsuran_list->RecCnt = $tbayarangsuran_list->StartRec - 1;
if ($tbayarangsuran_list->Recordset && !$tbayarangsuran_list->Recordset->EOF) {
	$tbayarangsuran_list->Recordset->MoveFirst();
	$bSelectLimit = $tbayarangsuran_list->UseSelectLimit;
	if (!$bSelectLimit && $tbayarangsuran_list->StartRec > 1)
		$tbayarangsuran_list->Recordset->Move($tbayarangsuran_list->StartRec - 1);
} elseif (!$tbayarangsuran->AllowAddDeleteRow && $tbayarangsuran_list->StopRec == 0) {
	$tbayarangsuran_list->StopRec = $tbayarangsuran->GridAddRowCount;
}

// Initialize aggregate
$tbayarangsuran->RowType = EW_ROWTYPE_AGGREGATEINIT;
$tbayarangsuran->ResetAttrs();
$tbayarangsuran_list->RenderRow();
while ($tbayarangsuran_list->RecCnt < $tbayarangsuran_list->StopRec) {
	$tbayarangsuran_list->RecCnt++;
	if (intval($tbayarangsuran_list->RecCnt) >= intval($tbayarangsuran_list->StartRec)) {
		$tbayarangsuran_list->RowCnt++;

		// Set up key count
		$tbayarangsuran_list->KeyCount = $tbayarangsuran_list->RowIndex;

		// Init row class and style
		$tbayarangsuran->ResetAttrs();
		$tbayarangsuran->CssClass = "";
		if ($tbayarangsuran->CurrentAction == "gridadd") {
		} else {
			$tbayarangsuran_list->LoadRowValues($tbayarangsuran_list->Recordset); // Load row values
		}
		$tbayarangsuran->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$tbayarangsuran->RowAttrs = array_merge($tbayarangsuran->RowAttrs, array('data-rowindex'=>$tbayarangsuran_list->RowCnt, 'id'=>'r' . $tbayarangsuran_list->RowCnt . '_tbayarangsuran', 'data-rowtype'=>$tbayarangsuran->RowType));

		// Render row
		$tbayarangsuran_list->RenderRow();

		// Render list options
		$tbayarangsuran_list->RenderListOptions();
?>
	<tr<?php echo $tbayarangsuran->RowAttributes() ?>>
<?php

// Render list options (body, left)
$tbayarangsuran_list->ListOptions->Render("body", "left", $tbayarangsuran_list->RowCnt);
?>
	<?php if ($tbayarangsuran->tanggal->Visible) { // tanggal ?>
		<td data-name="tanggal"<?php echo $tbayarangsuran->tanggal->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_tanggal" class="tbayarangsuran_tanggal">
<span<?php echo $tbayarangsuran->tanggal->ViewAttributes() ?>>
<?php echo $tbayarangsuran->tanggal->ListViewValue() ?></span>
</span>
<a id="<?php echo $tbayarangsuran_list->PageObjName . "_row_" . $tbayarangsuran_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($tbayarangsuran->periode->Visible) { // periode ?>
		<td data-name="periode"<?php echo $tbayarangsuran->periode->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_periode" class="tbayarangsuran_periode">
<span<?php echo $tbayarangsuran->periode->ViewAttributes() ?>>
<?php echo $tbayarangsuran->periode->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->id->Visible) { // id ?>
		<td data-name="id"<?php echo $tbayarangsuran->id->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_id" class="tbayarangsuran_id">
<span<?php echo $tbayarangsuran->id->ViewAttributes() ?>>
<?php echo $tbayarangsuran->id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->transaksi->Visible) { // transaksi ?>
		<td data-name="transaksi"<?php echo $tbayarangsuran->transaksi->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_transaksi" class="tbayarangsuran_transaksi">
<span<?php echo $tbayarangsuran->transaksi->ViewAttributes() ?>>
<?php echo $tbayarangsuran->transaksi->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->referensi->Visible) { // referensi ?>
		<td data-name="referensi"<?php echo $tbayarangsuran->referensi->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_referensi" class="tbayarangsuran_referensi">
<span<?php echo $tbayarangsuran->referensi->ViewAttributes() ?>>
<?php echo $tbayarangsuran->referensi->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->anggota->Visible) { // anggota ?>
		<td data-name="anggota"<?php echo $tbayarangsuran->anggota->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_anggota" class="tbayarangsuran_anggota">
<span<?php echo $tbayarangsuran->anggota->ViewAttributes() ?>>
<?php echo $tbayarangsuran->anggota->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->namaanggota->Visible) { // namaanggota ?>
		<td data-name="namaanggota"<?php echo $tbayarangsuran->namaanggota->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_namaanggota" class="tbayarangsuran_namaanggota">
<span<?php echo $tbayarangsuran->namaanggota->ViewAttributes() ?>>
<?php echo $tbayarangsuran->namaanggota->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->alamat->Visible) { // alamat ?>
		<td data-name="alamat"<?php echo $tbayarangsuran->alamat->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_alamat" class="tbayarangsuran_alamat">
<span<?php echo $tbayarangsuran->alamat->ViewAttributes() ?>>
<?php echo $tbayarangsuran->alamat->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->pekerjaan->Visible) { // pekerjaan ?>
		<td data-name="pekerjaan"<?php echo $tbayarangsuran->pekerjaan->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_pekerjaan" class="tbayarangsuran_pekerjaan">
<span<?php echo $tbayarangsuran->pekerjaan->ViewAttributes() ?>>
<?php echo $tbayarangsuran->pekerjaan->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->telepon->Visible) { // telepon ?>
		<td data-name="telepon"<?php echo $tbayarangsuran->telepon->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_telepon" class="tbayarangsuran_telepon">
<span<?php echo $tbayarangsuran->telepon->ViewAttributes() ?>>
<?php echo $tbayarangsuran->telepon->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->hp->Visible) { // hp ?>
		<td data-name="hp"<?php echo $tbayarangsuran->hp->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_hp" class="tbayarangsuran_hp">
<span<?php echo $tbayarangsuran->hp->ViewAttributes() ?>>
<?php echo $tbayarangsuran->hp->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->fax->Visible) { // fax ?>
		<td data-name="fax"<?php echo $tbayarangsuran->fax->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_fax" class="tbayarangsuran_fax">
<span<?php echo $tbayarangsuran->fax->ViewAttributes() ?>>
<?php echo $tbayarangsuran->fax->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->_email->Visible) { // email ?>
		<td data-name="_email"<?php echo $tbayarangsuran->_email->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran__email" class="tbayarangsuran__email">
<span<?php echo $tbayarangsuran->_email->ViewAttributes() ?>>
<?php echo $tbayarangsuran->_email->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->website->Visible) { // website ?>
		<td data-name="website"<?php echo $tbayarangsuran->website->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_website" class="tbayarangsuran_website">
<span<?php echo $tbayarangsuran->website->ViewAttributes() ?>>
<?php echo $tbayarangsuran->website->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->jenisanggota->Visible) { // jenisanggota ?>
		<td data-name="jenisanggota"<?php echo $tbayarangsuran->jenisanggota->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_jenisanggota" class="tbayarangsuran_jenisanggota">
<span<?php echo $tbayarangsuran->jenisanggota->ViewAttributes() ?>>
<?php echo $tbayarangsuran->jenisanggota->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->model->Visible) { // model ?>
		<td data-name="model"<?php echo $tbayarangsuran->model->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_model" class="tbayarangsuran_model">
<span<?php echo $tbayarangsuran->model->ViewAttributes() ?>>
<?php echo $tbayarangsuran->model->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->jenispinjaman->Visible) { // jenispinjaman ?>
		<td data-name="jenispinjaman"<?php echo $tbayarangsuran->jenispinjaman->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_jenispinjaman" class="tbayarangsuran_jenispinjaman">
<span<?php echo $tbayarangsuran->jenispinjaman->ViewAttributes() ?>>
<?php echo $tbayarangsuran->jenispinjaman->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->jenisbunga->Visible) { // jenisbunga ?>
		<td data-name="jenisbunga"<?php echo $tbayarangsuran->jenisbunga->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_jenisbunga" class="tbayarangsuran_jenisbunga">
<span<?php echo $tbayarangsuran->jenisbunga->ViewAttributes() ?>>
<?php echo $tbayarangsuran->jenisbunga->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->angsuran->Visible) { // angsuran ?>
		<td data-name="angsuran"<?php echo $tbayarangsuran->angsuran->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_angsuran" class="tbayarangsuran_angsuran">
<span<?php echo $tbayarangsuran->angsuran->ViewAttributes() ?>>
<?php echo $tbayarangsuran->angsuran->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->masaangsuran->Visible) { // masaangsuran ?>
		<td data-name="masaangsuran"<?php echo $tbayarangsuran->masaangsuran->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_masaangsuran" class="tbayarangsuran_masaangsuran">
<span<?php echo $tbayarangsuran->masaangsuran->ViewAttributes() ?>>
<?php echo $tbayarangsuran->masaangsuran->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->jatuhtempo->Visible) { // jatuhtempo ?>
		<td data-name="jatuhtempo"<?php echo $tbayarangsuran->jatuhtempo->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_jatuhtempo" class="tbayarangsuran_jatuhtempo">
<span<?php echo $tbayarangsuran->jatuhtempo->ViewAttributes() ?>>
<?php echo $tbayarangsuran->jatuhtempo->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->terlambat->Visible) { // terlambat ?>
		<td data-name="terlambat"<?php echo $tbayarangsuran->terlambat->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_terlambat" class="tbayarangsuran_terlambat">
<span<?php echo $tbayarangsuran->terlambat->ViewAttributes() ?>>
<?php echo $tbayarangsuran->terlambat->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->dispensasidenda->Visible) { // dispensasidenda ?>
		<td data-name="dispensasidenda"<?php echo $tbayarangsuran->dispensasidenda->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_dispensasidenda" class="tbayarangsuran_dispensasidenda">
<span<?php echo $tbayarangsuran->dispensasidenda->ViewAttributes() ?>>
<?php echo $tbayarangsuran->dispensasidenda->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->plafond->Visible) { // plafond ?>
		<td data-name="plafond"<?php echo $tbayarangsuran->plafond->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_plafond" class="tbayarangsuran_plafond">
<span<?php echo $tbayarangsuran->plafond->ViewAttributes() ?>>
<?php echo $tbayarangsuran->plafond->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->angsuranpokok->Visible) { // angsuranpokok ?>
		<td data-name="angsuranpokok"<?php echo $tbayarangsuran->angsuranpokok->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_angsuranpokok" class="tbayarangsuran_angsuranpokok">
<span<?php echo $tbayarangsuran->angsuranpokok->ViewAttributes() ?>>
<?php echo $tbayarangsuran->angsuranpokok->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->angsuranpokokauto->Visible) { // angsuranpokokauto ?>
		<td data-name="angsuranpokokauto"<?php echo $tbayarangsuran->angsuranpokokauto->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_angsuranpokokauto" class="tbayarangsuran_angsuranpokokauto">
<span<?php echo $tbayarangsuran->angsuranpokokauto->ViewAttributes() ?>>
<?php echo $tbayarangsuran->angsuranpokokauto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->angsuranbunga->Visible) { // angsuranbunga ?>
		<td data-name="angsuranbunga"<?php echo $tbayarangsuran->angsuranbunga->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_angsuranbunga" class="tbayarangsuran_angsuranbunga">
<span<?php echo $tbayarangsuran->angsuranbunga->ViewAttributes() ?>>
<?php echo $tbayarangsuran->angsuranbunga->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->angsuranbungaauto->Visible) { // angsuranbungaauto ?>
		<td data-name="angsuranbungaauto"<?php echo $tbayarangsuran->angsuranbungaauto->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_angsuranbungaauto" class="tbayarangsuran_angsuranbungaauto">
<span<?php echo $tbayarangsuran->angsuranbungaauto->ViewAttributes() ?>>
<?php echo $tbayarangsuran->angsuranbungaauto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->denda->Visible) { // denda ?>
		<td data-name="denda"<?php echo $tbayarangsuran->denda->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_denda" class="tbayarangsuran_denda">
<span<?php echo $tbayarangsuran->denda->ViewAttributes() ?>>
<?php echo $tbayarangsuran->denda->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->dendapersen->Visible) { // dendapersen ?>
		<td data-name="dendapersen"<?php echo $tbayarangsuran->dendapersen->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_dendapersen" class="tbayarangsuran_dendapersen">
<span<?php echo $tbayarangsuran->dendapersen->ViewAttributes() ?>>
<?php echo $tbayarangsuran->dendapersen->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->totalangsuran->Visible) { // totalangsuran ?>
		<td data-name="totalangsuran"<?php echo $tbayarangsuran->totalangsuran->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_totalangsuran" class="tbayarangsuran_totalangsuran">
<span<?php echo $tbayarangsuran->totalangsuran->ViewAttributes() ?>>
<?php echo $tbayarangsuran->totalangsuran->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->totalangsuranauto->Visible) { // totalangsuranauto ?>
		<td data-name="totalangsuranauto"<?php echo $tbayarangsuran->totalangsuranauto->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_totalangsuranauto" class="tbayarangsuran_totalangsuranauto">
<span<?php echo $tbayarangsuran->totalangsuranauto->ViewAttributes() ?>>
<?php echo $tbayarangsuran->totalangsuranauto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->sisaangsuran->Visible) { // sisaangsuran ?>
		<td data-name="sisaangsuran"<?php echo $tbayarangsuran->sisaangsuran->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_sisaangsuran" class="tbayarangsuran_sisaangsuran">
<span<?php echo $tbayarangsuran->sisaangsuran->ViewAttributes() ?>>
<?php echo $tbayarangsuran->sisaangsuran->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->sisaangsuranauto->Visible) { // sisaangsuranauto ?>
		<td data-name="sisaangsuranauto"<?php echo $tbayarangsuran->sisaangsuranauto->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_sisaangsuranauto" class="tbayarangsuran_sisaangsuranauto">
<span<?php echo $tbayarangsuran->sisaangsuranauto->ViewAttributes() ?>>
<?php echo $tbayarangsuran->sisaangsuranauto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->saldotitipan->Visible) { // saldotitipan ?>
		<td data-name="saldotitipan"<?php echo $tbayarangsuran->saldotitipan->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_saldotitipan" class="tbayarangsuran_saldotitipan">
<span<?php echo $tbayarangsuran->saldotitipan->ViewAttributes() ?>>
<?php echo $tbayarangsuran->saldotitipan->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->saldotitipansisa->Visible) { // saldotitipansisa ?>
		<td data-name="saldotitipansisa"<?php echo $tbayarangsuran->saldotitipansisa->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_saldotitipansisa" class="tbayarangsuran_saldotitipansisa">
<span<?php echo $tbayarangsuran->saldotitipansisa->ViewAttributes() ?>>
<?php echo $tbayarangsuran->saldotitipansisa->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->bayarpokok->Visible) { // bayarpokok ?>
		<td data-name="bayarpokok"<?php echo $tbayarangsuran->bayarpokok->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_bayarpokok" class="tbayarangsuran_bayarpokok">
<span<?php echo $tbayarangsuran->bayarpokok->ViewAttributes() ?>>
<?php echo $tbayarangsuran->bayarpokok->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->bayarpokokauto->Visible) { // bayarpokokauto ?>
		<td data-name="bayarpokokauto"<?php echo $tbayarangsuran->bayarpokokauto->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_bayarpokokauto" class="tbayarangsuran_bayarpokokauto">
<span<?php echo $tbayarangsuran->bayarpokokauto->ViewAttributes() ?>>
<?php echo $tbayarangsuran->bayarpokokauto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->bayarbunga->Visible) { // bayarbunga ?>
		<td data-name="bayarbunga"<?php echo $tbayarangsuran->bayarbunga->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_bayarbunga" class="tbayarangsuran_bayarbunga">
<span<?php echo $tbayarangsuran->bayarbunga->ViewAttributes() ?>>
<?php echo $tbayarangsuran->bayarbunga->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->bayarbungaauto->Visible) { // bayarbungaauto ?>
		<td data-name="bayarbungaauto"<?php echo $tbayarangsuran->bayarbungaauto->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_bayarbungaauto" class="tbayarangsuran_bayarbungaauto">
<span<?php echo $tbayarangsuran->bayarbungaauto->ViewAttributes() ?>>
<?php echo $tbayarangsuran->bayarbungaauto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->bayardenda->Visible) { // bayardenda ?>
		<td data-name="bayardenda"<?php echo $tbayarangsuran->bayardenda->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_bayardenda" class="tbayarangsuran_bayardenda">
<span<?php echo $tbayarangsuran->bayardenda->ViewAttributes() ?>>
<?php echo $tbayarangsuran->bayardenda->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->bayardendaauto->Visible) { // bayardendaauto ?>
		<td data-name="bayardendaauto"<?php echo $tbayarangsuran->bayardendaauto->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_bayardendaauto" class="tbayarangsuran_bayardendaauto">
<span<?php echo $tbayarangsuran->bayardendaauto->ViewAttributes() ?>>
<?php echo $tbayarangsuran->bayardendaauto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->bayartitipan->Visible) { // bayartitipan ?>
		<td data-name="bayartitipan"<?php echo $tbayarangsuran->bayartitipan->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_bayartitipan" class="tbayarangsuran_bayartitipan">
<span<?php echo $tbayarangsuran->bayartitipan->ViewAttributes() ?>>
<?php echo $tbayarangsuran->bayartitipan->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->bayartitipanauto->Visible) { // bayartitipanauto ?>
		<td data-name="bayartitipanauto"<?php echo $tbayarangsuran->bayartitipanauto->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_bayartitipanauto" class="tbayarangsuran_bayartitipanauto">
<span<?php echo $tbayarangsuran->bayartitipanauto->ViewAttributes() ?>>
<?php echo $tbayarangsuran->bayartitipanauto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->totalbayar->Visible) { // totalbayar ?>
		<td data-name="totalbayar"<?php echo $tbayarangsuran->totalbayar->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_totalbayar" class="tbayarangsuran_totalbayar">
<span<?php echo $tbayarangsuran->totalbayar->ViewAttributes() ?>>
<?php echo $tbayarangsuran->totalbayar->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->totalbayarauto->Visible) { // totalbayarauto ?>
		<td data-name="totalbayarauto"<?php echo $tbayarangsuran->totalbayarauto->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_totalbayarauto" class="tbayarangsuran_totalbayarauto">
<span<?php echo $tbayarangsuran->totalbayarauto->ViewAttributes() ?>>
<?php echo $tbayarangsuran->totalbayarauto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->pelunasan->Visible) { // pelunasan ?>
		<td data-name="pelunasan"<?php echo $tbayarangsuran->pelunasan->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_pelunasan" class="tbayarangsuran_pelunasan">
<span<?php echo $tbayarangsuran->pelunasan->ViewAttributes() ?>>
<?php echo $tbayarangsuran->pelunasan->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->pelunasanauto->Visible) { // pelunasanauto ?>
		<td data-name="pelunasanauto"<?php echo $tbayarangsuran->pelunasanauto->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_pelunasanauto" class="tbayarangsuran_pelunasanauto">
<span<?php echo $tbayarangsuran->pelunasanauto->ViewAttributes() ?>>
<?php echo $tbayarangsuran->pelunasanauto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->finalty->Visible) { // finalty ?>
		<td data-name="finalty"<?php echo $tbayarangsuran->finalty->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_finalty" class="tbayarangsuran_finalty">
<span<?php echo $tbayarangsuran->finalty->ViewAttributes() ?>>
<?php echo $tbayarangsuran->finalty->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->finaltyauto->Visible) { // finaltyauto ?>
		<td data-name="finaltyauto"<?php echo $tbayarangsuran->finaltyauto->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_finaltyauto" class="tbayarangsuran_finaltyauto">
<span<?php echo $tbayarangsuran->finaltyauto->ViewAttributes() ?>>
<?php echo $tbayarangsuran->finaltyauto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->terbilang->Visible) { // terbilang ?>
		<td data-name="terbilang"<?php echo $tbayarangsuran->terbilang->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_terbilang" class="tbayarangsuran_terbilang">
<span<?php echo $tbayarangsuran->terbilang->ViewAttributes() ?>>
<?php echo $tbayarangsuran->terbilang->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->petugas->Visible) { // petugas ?>
		<td data-name="petugas"<?php echo $tbayarangsuran->petugas->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_petugas" class="tbayarangsuran_petugas">
<span<?php echo $tbayarangsuran->petugas->ViewAttributes() ?>>
<?php echo $tbayarangsuran->petugas->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->pembayaran->Visible) { // pembayaran ?>
		<td data-name="pembayaran"<?php echo $tbayarangsuran->pembayaran->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_pembayaran" class="tbayarangsuran_pembayaran">
<span<?php echo $tbayarangsuran->pembayaran->ViewAttributes() ?>>
<?php echo $tbayarangsuran->pembayaran->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->bank->Visible) { // bank ?>
		<td data-name="bank"<?php echo $tbayarangsuran->bank->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_bank" class="tbayarangsuran_bank">
<span<?php echo $tbayarangsuran->bank->ViewAttributes() ?>>
<?php echo $tbayarangsuran->bank->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->atasnama->Visible) { // atasnama ?>
		<td data-name="atasnama"<?php echo $tbayarangsuran->atasnama->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_atasnama" class="tbayarangsuran_atasnama">
<span<?php echo $tbayarangsuran->atasnama->ViewAttributes() ?>>
<?php echo $tbayarangsuran->atasnama->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->tipe->Visible) { // tipe ?>
		<td data-name="tipe"<?php echo $tbayarangsuran->tipe->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_tipe" class="tbayarangsuran_tipe">
<span<?php echo $tbayarangsuran->tipe->ViewAttributes() ?>>
<?php echo $tbayarangsuran->tipe->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->kantor->Visible) { // kantor ?>
		<td data-name="kantor"<?php echo $tbayarangsuran->kantor->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_kantor" class="tbayarangsuran_kantor">
<span<?php echo $tbayarangsuran->kantor->ViewAttributes() ?>>
<?php echo $tbayarangsuran->kantor->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->keterangan->Visible) { // keterangan ?>
		<td data-name="keterangan"<?php echo $tbayarangsuran->keterangan->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_keterangan" class="tbayarangsuran_keterangan">
<span<?php echo $tbayarangsuran->keterangan->ViewAttributes() ?>>
<?php echo $tbayarangsuran->keterangan->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->active->Visible) { // active ?>
		<td data-name="active"<?php echo $tbayarangsuran->active->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_active" class="tbayarangsuran_active">
<span<?php echo $tbayarangsuran->active->ViewAttributes() ?>>
<?php echo $tbayarangsuran->active->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->ip->Visible) { // ip ?>
		<td data-name="ip"<?php echo $tbayarangsuran->ip->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_ip" class="tbayarangsuran_ip">
<span<?php echo $tbayarangsuran->ip->ViewAttributes() ?>>
<?php echo $tbayarangsuran->ip->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->status->Visible) { // status ?>
		<td data-name="status"<?php echo $tbayarangsuran->status->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_status" class="tbayarangsuran_status">
<span<?php echo $tbayarangsuran->status->ViewAttributes() ?>>
<?php echo $tbayarangsuran->status->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->user->Visible) { // user ?>
		<td data-name="user"<?php echo $tbayarangsuran->user->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_user" class="tbayarangsuran_user">
<span<?php echo $tbayarangsuran->user->ViewAttributes() ?>>
<?php echo $tbayarangsuran->user->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->created->Visible) { // created ?>
		<td data-name="created"<?php echo $tbayarangsuran->created->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_created" class="tbayarangsuran_created">
<span<?php echo $tbayarangsuran->created->ViewAttributes() ?>>
<?php echo $tbayarangsuran->created->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($tbayarangsuran->modified->Visible) { // modified ?>
		<td data-name="modified"<?php echo $tbayarangsuran->modified->CellAttributes() ?>>
<span id="el<?php echo $tbayarangsuran_list->RowCnt ?>_tbayarangsuran_modified" class="tbayarangsuran_modified">
<span<?php echo $tbayarangsuran->modified->ViewAttributes() ?>>
<?php echo $tbayarangsuran->modified->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$tbayarangsuran_list->ListOptions->Render("body", "right", $tbayarangsuran_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($tbayarangsuran->CurrentAction <> "gridadd")
		$tbayarangsuran_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($tbayarangsuran->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($tbayarangsuran_list->Recordset)
	$tbayarangsuran_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($tbayarangsuran->CurrentAction <> "gridadd" && $tbayarangsuran->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($tbayarangsuran_list->Pager)) $tbayarangsuran_list->Pager = new cPrevNextPager($tbayarangsuran_list->StartRec, $tbayarangsuran_list->DisplayRecs, $tbayarangsuran_list->TotalRecs) ?>
<?php if ($tbayarangsuran_list->Pager->RecordCount > 0 && $tbayarangsuran_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($tbayarangsuran_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $tbayarangsuran_list->PageUrl() ?>start=<?php echo $tbayarangsuran_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($tbayarangsuran_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $tbayarangsuran_list->PageUrl() ?>start=<?php echo $tbayarangsuran_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $tbayarangsuran_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($tbayarangsuran_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $tbayarangsuran_list->PageUrl() ?>start=<?php echo $tbayarangsuran_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($tbayarangsuran_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $tbayarangsuran_list->PageUrl() ?>start=<?php echo $tbayarangsuran_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $tbayarangsuran_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $tbayarangsuran_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $tbayarangsuran_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $tbayarangsuran_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($tbayarangsuran_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($tbayarangsuran_list->TotalRecs == 0 && $tbayarangsuran->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($tbayarangsuran_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
ftbayarangsuranlistsrch.FilterList = <?php echo $tbayarangsuran_list->GetFilterList() ?>;
ftbayarangsuranlistsrch.Init();
ftbayarangsuranlist.Init();
</script>
<?php
$tbayarangsuran_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tbayarangsuran_list->Page_Terminate();
?>
