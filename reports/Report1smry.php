<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "phprptinc/ewrcfg10.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "phprptinc/ewmysql.php") ?>
<?php include_once "phprptinc/ewrfn10.php" ?>
<?php include_once "phprptinc/ewrusrfn10.php" ?>
<?php include_once "Report1smryinfo.php" ?>
<?php

//
// Page class
//

$Report1_summary = NULL; // Initialize page object first

class crReport1_summary extends crReport1 {

	// Page ID
	var $PageID = 'summary';

	// Project ID
	var $ProjectID = "{e3b093f1-120d-4a26-9ac0-4e96d12c121b}";

	// Page object name
	var $PageObjName = 'Report1_summary';

	// Page name
	function PageName() {
		return ewr_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ewr_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Export URLs
	var $ExportPrintUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportPdfUrl;
	var $ReportTableClass;
	var $ReportTableStyle = "";

	// Custom export
	var $ExportPrintCustom = FALSE;
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Message
	function getMessage() {
		return @$_SESSION[EWR_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EWR_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EWR_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EWR_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_WARNING_MESSAGE], $v);
	}

		// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EWR_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EWR_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EWR_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EWR_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog ewDisplayTable\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") // Header exists, display
			echo $sHeader;
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") // Fotoer exists, display
			echo $sFooter;
	}

	// Validate page request
	function IsPageRequest() {
		if ($this->UseTokenInUrl) {
			if (ewr_IsHttpPost())
				return ($this->TableVar == @$_POST("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == @$_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $CheckToken = EWR_CHECK_TOKEN;
	var $CheckTokenFn = "ewr_CheckToken";
	var $CreateTokenFn = "ewr_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ewr_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EWR_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EWR_TOKEN_NAME]);
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
		global $conn, $ReportLanguage;

		// Language object
		$ReportLanguage = new crLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (Report1)
		if (!isset($GLOBALS["Report1"])) {
			$GLOBALS["Report1"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["Report1"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";

		// Page ID
		if (!defined("EWR_PAGE_ID"))
			define("EWR_PAGE_ID", 'summary', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EWR_TABLE_NAME"))
			define("EWR_TABLE_NAME", 'Report1', TRUE);

		// Start timer
		$GLOBALS["gsTimer"] = new crTimer();

		// Open connection
		if (!isset($conn)) $conn = ewr_Connect($this->DBID);

		// Export options
		$this->ExportOptions = new crListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Search options
		$this->SearchOptions = new crListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Filter options
		$this->FilterOptions = new crListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fReport1summary";

		// Generate report options
		$this->GenerateOptions = new crListOptions();
		$this->GenerateOptions->Tag = "div";
		$this->GenerateOptions->TagClassName = "ewGenerateOption";
	}

	//
	// Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $gsEmailContentType, $ReportLanguage, $Security;
		global $gsCustomExport;

		// Get export parameters
		if (@$_GET["export"] <> "")
			$this->Export = strtolower($_GET["export"]);
		elseif (@$_POST["export"] <> "")
			$this->Export = strtolower($_POST["export"]);
		$gsExport = $this->Export; // Get export parameter, used in header
		$gsExportFile = $this->TableVar; // Get export file, used in header
		$gsEmailContentType = @$_POST["contenttype"]; // Get email content type

		// Setup placeholder
		// Setup export options

		$this->SetupExportOptions();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $ReportLanguage->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Security, $ReportLanguage, $ReportOptions;
		$exportid = session_id();
		$ReportTypes = array();

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("PrinterFriendly", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("PrinterFriendly", TRUE)) . "\" href=\"" . $this->ExportPrintUrl . "\">" . $ReportLanguage->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = FALSE;
		$ReportTypes["print"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormPrint") : "";

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToExcel", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToExcel", TRUE)) . "\" href=\"" . $this->ExportExcelUrl . "\">" . $ReportLanguage->Phrase("ExportToExcel") . "</a>";
		$item->Visible = FALSE;
		$ReportTypes["excel"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormExcel") : "";

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToWord", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToWord", TRUE)) . "\" href=\"" . $this->ExportWordUrl . "\">" . $ReportLanguage->Phrase("ExportToWord") . "</a>";

		//$item->Visible = FALSE;
		$item->Visible = FALSE;
		$ReportTypes["word"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormWord") : "";

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" href=\"" . $this->ExportPdfUrl . "\">" . $ReportLanguage->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Uncomment codes below to show export to Pdf link
//		$item->Visible = FALSE;

		$ReportTypes["pdf"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormPdf") : "";

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = $this->PageUrl() . "export=email";
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" id=\"emf_Report1\" href=\"javascript:void(0);\" onclick=\"ewr_EmailDialogShow({lnk:'emf_Report1',hdr:ewLanguage.Phrase('ExportToEmail'),url:'$url',exportid:'$exportid',el:this});\">" . $ReportLanguage->Phrase("ExportToEmail") . "</a>";
		$item->Visible = FALSE;
		$ReportTypes["email"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormEmail") : "";
		$ReportOptions["ReportTypes"] = $ReportTypes;

		// Drop down button for export
		$this->ExportOptions->UseDropDownButton = FALSE;
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = $this->ExportOptions->UseDropDownButton;
		$this->ExportOptions->DropDownButtonPhrase = $ReportLanguage->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fReport1summary\" href=\"#\">" . $ReportLanguage->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fReport1summary\" href=\"#\">" . $ReportLanguage->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton; // v8
		$this->FilterOptions->DropDownButtonPhrase = $ReportLanguage->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Set up options (extended)
		$this->SetupExportOptionsExt();

		// Hide options for export
		if ($this->Export <> "") {
			$this->ExportOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}

		// Set up table class
		if ($this->Export == "word" || $this->Export == "excel" || $this->Export == "pdf")
			$this->ReportTableClass = "ewTable";
		else
			$this->ReportTableClass = "table ewTable";
	}

	// Set up search options
	function SetupSearchOptions() {
		global $ReportLanguage;

		// Filter panel button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = $this->FilterApplied ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-caption=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-toggle=\"button\" data-form=\"fReport1summary\">" . $ReportLanguage->Phrase("SearchBtn") . "</button>";
		$item->Visible = FALSE;

		// Reset filter
		$item = &$this->SearchOptions->Add("resetfilter");
		$item->Body = "<button type=\"button\" class=\"btn btn-default\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ResetAllFilter", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ResetAllFilter", TRUE)) . "\" onclick=\"location='" . ewr_CurrentPage() . "?cmd=reset'\">" . $ReportLanguage->Phrase("ResetAllFilter") . "</button>";
		$item->Visible = FALSE && $this->FilterApplied;

		// Button group for reset filter
		$this->SearchOptions->UseButtonGroup = TRUE;

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide options for export
		if ($this->Export <> "")
			$this->SearchOptions->HideAllOptions();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $ReportLanguage, $EWR_EXPORT, $gsExportFile;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		if ($this->Export <> "" && array_key_exists($this->Export, $EWR_EXPORT)) {
			$sContent = ob_get_contents();
			if (ob_get_length())
				ob_end_clean();

			// Remove all <div data-tagid="..." id="orig..." class="hide">...</div> (for customviewtag export, except "googlemaps")
			if (preg_match_all('/<div\s+data-tagid=[\'"]([\s\S]*?)[\'"]\s+id=[\'"]orig([\s\S]*?)[\'"]\s+class\s*=\s*[\'"]hide[\'"]>([\s\S]*?)<\/div\s*>/i', $sContent, $divmatches, PREG_SET_ORDER)) {
				foreach ($divmatches as $divmatch) {
					if ($divmatch[1] <> "googlemaps")
						$sContent = str_replace($divmatch[0], '', $sContent);
				}
			}
			$fn = $EWR_EXPORT[$this->Export];
			if ($this->Export == "email") { // Email
				if (@$this->GenOptions["reporttype"] == "email") {
					$saveResponse = $this->$fn($sContent, $this->GenOptions);
					$this->WriteGenResponse($saveResponse);
				} else {
					echo $this->$fn($sContent, array());
				}
				$url = ""; // Avoid redirect
			} else {
				$saveToFile = $this->$fn($sContent, $this->GenOptions);
				if (@$this->GenOptions["reporttype"] <> "") {
					$saveUrl = ($saveToFile <> "") ? ewr_ConvertFullUrl($saveToFile) : $ReportLanguage->Phrase("GenerateSuccess");
					$this->WriteGenResponse($saveUrl);
					$url = ""; // Avoid redirect
				}
			}
		}

		 // Close connection
		ewr_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EWR_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}

	// Initialize common variables
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $FilterOptions; // Filter options

	// Paging variables
	var $RecIndex = 0; // Record index
	var $RecCount = 0; // Record count
	var $StartGrp = 0; // Start group
	var $StopGrp = 0; // Stop group
	var $TotalGrps = 0; // Total groups
	var $GrpCount = 0; // Group count
	var $GrpCounter = array(); // Group counter
	var $DisplayGrps = 3; // Groups per page
	var $GrpRange = 10;
	var $Sort = "";
	var $Filter = "";
	var $PageFirstGroupFilter = "";
	var $UserIDFilter = "";
	var $DrillDown = FALSE;
	var $DrillDownInPanel = FALSE;
	var $DrillDownList = "";

	// Clear field for ext filter
	var $ClearExtFilter = "";
	var $PopupName = "";
	var $PopupValue = "";
	var $FilterApplied;
	var $SearchCommand = FALSE;
	var $ShowHeader;
	var $GrpColumnCount = 0;
	var $SubGrpColumnCount = 0;
	var $DtlColumnCount = 0;
	var $Cnt, $Col, $Val, $Smry, $Mn, $Mx, $GrandCnt, $GrandSmry, $GrandMn, $GrandMx;
	var $TotCount;
	var $GrandSummarySetup = FALSE;
	var $GrpIdx;
	var $DetailRows = array();

	//
	// Page main
	//
	function Page_Main() {
		global $rs;
		global $rsgrp;
		global $Security;
		global $gsFormError;
		global $gbDrillDownInPanel;
		global $ReportBreadcrumb;
		global $ReportLanguage;

		// Set field visibility for detail fields
		$this->id1->SetVisibility();
		$this->rekening1->SetVisibility();
		$this->id2->SetVisibility();
		$this->rekening2->SetVisibility();
		$this->tipe->SetVisibility();
		$this->posisi->SetVisibility();
		$this->laporan->SetVisibility();
		$this->status->SetVisibility();
		$this->parent->SetVisibility();
		$this->keterangan->SetVisibility();
		$this->active->SetVisibility();
		$this->id->SetVisibility();
		$this->rekening->SetVisibility();

		// Aggregate variables
		// 1st dimension = no of groups (level 0 used for grand total)
		// 2nd dimension = no of fields

		$nDtls = 14;
		$nGrps = 2;
		$this->Val = &ewr_InitArray($nDtls, 0);
		$this->Cnt = &ewr_Init2DArray($nGrps, $nDtls, 0);
		$this->Smry = &ewr_Init2DArray($nGrps, $nDtls, 0);
		$this->Mn = &ewr_Init2DArray($nGrps, $nDtls, NULL);
		$this->Mx = &ewr_Init2DArray($nGrps, $nDtls, NULL);
		$this->GrandCnt = &ewr_InitArray($nDtls, 0);
		$this->GrandSmry = &ewr_InitArray($nDtls, 0);
		$this->GrandMn = &ewr_InitArray($nDtls, NULL);
		$this->GrandMx = &ewr_InitArray($nDtls, NULL);

		// Set up array if accumulation required: array(Accum, SkipNullOrZero)
		$this->Col = array(array(FALSE, FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE));

		// Set up groups per page dynamically
		$this->SetUpDisplayGrps();

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();

		// Load custom filters
		$this->Page_FilterLoad();

		// Set up popup filter
		$this->SetupPopup();

		// Load group db values if necessary
		$this->LoadGroupDbValues();

		// Handle Ajax popup
		$this->ProcessAjaxPopup();

		// Extended filter
		$sExtendedFilter = "";

		// Build popup filter
		$sPopupFilter = $this->GetPopupFilter();

		//ewr_SetDebugMsg("popup filter: " . $sPopupFilter);
		ewr_AddFilter($this->Filter, $sPopupFilter);

		// No filter
		$this->FilterApplied = FALSE;
		$this->FilterOptions->GetItem("savecurrentfilter")->Visible = FALSE;
		$this->FilterOptions->GetItem("deletefilter")->Visible = FALSE;

		// Call Page Selecting event
		$this->Page_Selecting($this->Filter);

		// Search options
		$this->SetupSearchOptions();

		// Get sort
		$this->Sort = $this->GetSort($this->GenOptions);

		// Get total group count
		$sGrpSort = ewr_UpdateSortFields($this->getSqlOrderByGroup(), $this->Sort, 2); // Get grouping field only
		$sSql = ewr_BuildReportSql($this->getSqlSelectGroup(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderByGroup(), $this->Filter, $sGrpSort);
		$this->TotalGrps = $this->GetGrpCnt($sSql);
		if ($this->DisplayGrps <= 0 || $this->DrillDown) // Display all groups
			$this->DisplayGrps = $this->TotalGrps;
		$this->StartGrp = 1;

		// Show header
		$this->ShowHeader = ($this->TotalGrps > 0);

		// Set up start position if not export all
		if ($this->ExportAll && $this->Export <> "")
			$this->DisplayGrps = $this->TotalGrps;
		else
			$this->SetUpStartGroup($this->GenOptions);

		// Set no record found message
		if ($this->TotalGrps == 0) {
				if ($this->Filter == "0=101") {
					$this->setWarningMessage($ReportLanguage->Phrase("EnterSearchCriteria"));
				} else {
					$this->setWarningMessage($ReportLanguage->Phrase("NoRecord"));
				}
		}

		// Hide export options if export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();

		// Hide search/filter options if export/drilldown
		if ($this->Export <> "" || $this->DrillDown) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
			$this->GenerateOptions->HideAllOptions();
		}

		// Get current page groups
		$rsgrp = $this->GetGrpRs($sSql, $this->StartGrp, $this->DisplayGrps);

		// Init detail recordset
		$rs = NULL;
		$this->SetupFieldCount();
	}

	// Get summary count
	function GetSummaryCount($lvl, $curValue = TRUE) {
		$cnt = 0;
		foreach ($this->DetailRows as $row) {
			$wrkgroup = $row["group"];
			if ($lvl >= 1) {
				$val = $curValue ? $this->group->CurrentValue : $this->group->OldValue;
				$grpval = $curValue ? $this->group->GroupValue() : $this->group->GroupOldValue();
				if (is_null($val) && !is_null($wrkgroup) || !is_null($val) && is_null($wrkgroup) ||
					$grpval <> $this->group->getGroupValueBase($wrkgroup))
				continue;
			}
			$cnt++;
		}
		return $cnt;
	}

	// Check level break
	function ChkLvlBreak($lvl) {
		switch ($lvl) {
			case 1:
				return (is_null($this->group->CurrentValue) && !is_null($this->group->OldValue)) ||
					(!is_null($this->group->CurrentValue) && is_null($this->group->OldValue)) ||
					($this->group->GroupValue() <> $this->group->GroupOldValue());
		}
	}

	// Accummulate summary
	function AccumulateSummary() {
		$cntx = count($this->Smry);
		for ($ix = 0; $ix < $cntx; $ix++) {
			$cnty = count($this->Smry[$ix]);
			for ($iy = 1; $iy < $cnty; $iy++) {
				if ($this->Col[$iy][0]) { // Accumulate required
					$valwrk = $this->Val[$iy];
					if (is_null($valwrk)) {
						if (!$this->Col[$iy][1])
							$this->Cnt[$ix][$iy]++;
					} else {
						$accum = (!$this->Col[$iy][1] || !is_numeric($valwrk) || $valwrk <> 0);
						if ($accum) {
							$this->Cnt[$ix][$iy]++;
							if (is_numeric($valwrk)) {
								$this->Smry[$ix][$iy] += $valwrk;
								if (is_null($this->Mn[$ix][$iy])) {
									$this->Mn[$ix][$iy] = $valwrk;
									$this->Mx[$ix][$iy] = $valwrk;
								} else {
									if ($this->Mn[$ix][$iy] > $valwrk) $this->Mn[$ix][$iy] = $valwrk;
									if ($this->Mx[$ix][$iy] < $valwrk) $this->Mx[$ix][$iy] = $valwrk;
								}
							}
						}
					}
				}
			}
		}
		$cntx = count($this->Smry);
		for ($ix = 0; $ix < $cntx; $ix++) {
			$this->Cnt[$ix][0]++;
		}
	}

	// Reset level summary
	function ResetLevelSummary($lvl) {

		// Clear summary values
		$cntx = count($this->Smry);
		for ($ix = $lvl; $ix < $cntx; $ix++) {
			$cnty = count($this->Smry[$ix]);
			for ($iy = 1; $iy < $cnty; $iy++) {
				$this->Cnt[$ix][$iy] = 0;
				if ($this->Col[$iy][0]) {
					$this->Smry[$ix][$iy] = 0;
					$this->Mn[$ix][$iy] = NULL;
					$this->Mx[$ix][$iy] = NULL;
				}
			}
		}
		$cntx = count($this->Smry);
		for ($ix = $lvl; $ix < $cntx; $ix++) {
			$this->Cnt[$ix][0] = 0;
		}

		// Reset record count
		$this->RecCount = 0;
	}

	// Accummulate grand summary
	function AccumulateGrandSummary() {
		$this->TotCount++;
		$cntgs = count($this->GrandSmry);
		for ($iy = 1; $iy < $cntgs; $iy++) {
			if ($this->Col[$iy][0]) {
				$valwrk = $this->Val[$iy];
				if (is_null($valwrk) || !is_numeric($valwrk)) {
					if (!$this->Col[$iy][1])
						$this->GrandCnt[$iy]++;
				} else {
					if (!$this->Col[$iy][1] || $valwrk <> 0) {
						$this->GrandCnt[$iy]++;
						$this->GrandSmry[$iy] += $valwrk;
						if (is_null($this->GrandMn[$iy])) {
							$this->GrandMn[$iy] = $valwrk;
							$this->GrandMx[$iy] = $valwrk;
						} else {
							if ($this->GrandMn[$iy] > $valwrk) $this->GrandMn[$iy] = $valwrk;
							if ($this->GrandMx[$iy] < $valwrk) $this->GrandMx[$iy] = $valwrk;
						}
					}
				}
			}
		}
	}

	// Get group count
	function GetGrpCnt($sql) {
		$conn = &$this->Connection();
		$rsgrpcnt = $conn->Execute($sql);
		$grpcnt = ($rsgrpcnt) ? $rsgrpcnt->RecordCount() : 0;
		if ($rsgrpcnt) $rsgrpcnt->Close();
		return $grpcnt;
	}

	// Get group recordset
	function GetGrpRs($wrksql, $start = -1, $grps = -1) {
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EWR_ERROR_FN"];
		$rswrk = $conn->SelectLimit($wrksql, $grps, $start - 1);
		$conn->raiseErrorFn = '';
		return $rswrk;
	}

	// Get group row values
	function GetGrpRow($opt) {
		global $rsgrp;
		if (!$rsgrp)
			return;
		if ($opt == 1) { // Get first group

			//$rsgrp->MoveFirst(); // NOTE: no need to move position
			$this->group->setDbValue(""); // Init first value
		} else { // Get next group
			$rsgrp->MoveNext();
		}
		if (!$rsgrp->EOF)
			$this->group->setDbValue($rsgrp->fields[0]);
		if ($rsgrp->EOF) {
			$this->group->setDbValue("");
		}
	}

	// Get detail recordset
	function GetDetailRs($wrksql) {
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EWR_ERROR_FN"];
		$rswrk = $conn->Execute($wrksql);
		$dbtype = ewr_GetConnectionType($this->DBID);
		if ($dbtype == "MYSQL" || $dbtype == "POSTGRESQL") {
			$this->DetailRows = ($rswrk) ? $rswrk->GetRows() : array();
		} else { // Cannot MoveFirst, use another recordset
			$rstmp = $conn->Execute($wrksql);
			$this->DetailRows = ($rstmp) ? $rstmp->GetRows() : array();
			$rstmp->Close();
		}
		$conn->raiseErrorFn = "";
		return $rswrk;
	}

	// Get row values
	function GetRow($opt) {
		global $rs;
		if (!$rs)
			return;
		if ($opt == 1) { // Get first row
			$rs->MoveFirst(); // Move first
			if ($this->GrpCount == 1) {
				$this->FirstRowData = array();
				$this->FirstRowData['id1'] = ewr_Conv($rs->fields('id1'), 200);
				$this->FirstRowData['rekening1'] = ewr_Conv($rs->fields('rekening1'), 200);
				$this->FirstRowData['id2'] = ewr_Conv($rs->fields('id2'), 200);
				$this->FirstRowData['rekening2'] = ewr_Conv($rs->fields('rekening2'), 200);
				$this->FirstRowData['tipe'] = ewr_Conv($rs->fields('tipe'), 200);
				$this->FirstRowData['posisi'] = ewr_Conv($rs->fields('posisi'), 200);
				$this->FirstRowData['laporan'] = ewr_Conv($rs->fields('laporan'), 200);
				$this->FirstRowData['status'] = ewr_Conv($rs->fields('status'), 200);
				$this->FirstRowData['parent'] = ewr_Conv($rs->fields('parent'), 200);
				$this->FirstRowData['keterangan'] = ewr_Conv($rs->fields('keterangan'), 200);
				$this->FirstRowData['active'] = ewr_Conv($rs->fields('active'), 202);
				$this->FirstRowData['group'] = ewr_Conv($rs->fields('group'), 20);
				$this->FirstRowData['id'] = ewr_Conv($rs->fields('id'), 200);
				$this->FirstRowData['rekening'] = ewr_Conv($rs->fields('rekening'), 200);
			}
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			$this->id1->setDbValue($rs->fields('id1'));
			$this->rekening1->setDbValue($rs->fields('rekening1'));
			$this->id2->setDbValue($rs->fields('id2'));
			$this->rekening2->setDbValue($rs->fields('rekening2'));
			$this->tipe->setDbValue($rs->fields('tipe'));
			$this->posisi->setDbValue($rs->fields('posisi'));
			$this->laporan->setDbValue($rs->fields('laporan'));
			$this->status->setDbValue($rs->fields('status'));
			$this->parent->setDbValue($rs->fields('parent'));
			$this->keterangan->setDbValue($rs->fields('keterangan'));
			$this->active->setDbValue($rs->fields('active'));
			if ($opt <> 1) {
				if (is_array($this->group->GroupDbValues))
					$this->group->setDbValue(@$this->group->GroupDbValues[$rs->fields('group')]);
				else
					$this->group->setDbValue(ewr_GroupValue($this->group, $rs->fields('group')));
			}
			$this->id->setDbValue($rs->fields('id'));
			$this->rekening->setDbValue($rs->fields('rekening'));
			$this->Val[1] = $this->id1->CurrentValue;
			$this->Val[2] = $this->rekening1->CurrentValue;
			$this->Val[3] = $this->id2->CurrentValue;
			$this->Val[4] = $this->rekening2->CurrentValue;
			$this->Val[5] = $this->tipe->CurrentValue;
			$this->Val[6] = $this->posisi->CurrentValue;
			$this->Val[7] = $this->laporan->CurrentValue;
			$this->Val[8] = $this->status->CurrentValue;
			$this->Val[9] = $this->parent->CurrentValue;
			$this->Val[10] = $this->keterangan->CurrentValue;
			$this->Val[11] = $this->active->CurrentValue;
			$this->Val[12] = $this->id->CurrentValue;
			$this->Val[13] = $this->rekening->CurrentValue;
		} else {
			$this->id1->setDbValue("");
			$this->rekening1->setDbValue("");
			$this->id2->setDbValue("");
			$this->rekening2->setDbValue("");
			$this->tipe->setDbValue("");
			$this->posisi->setDbValue("");
			$this->laporan->setDbValue("");
			$this->status->setDbValue("");
			$this->parent->setDbValue("");
			$this->keterangan->setDbValue("");
			$this->active->setDbValue("");
			$this->group->setDbValue("");
			$this->id->setDbValue("");
			$this->rekening->setDbValue("");
		}
	}

	// Set up starting group
	function SetUpStartGroup($options = array()) {

		// Exit if no groups
		if ($this->DisplayGrps == 0)
			return;
		$startGrp = (@$options["start"] <> "") ? $options["start"] : @$_GET[EWR_TABLE_START_GROUP];
		$pageNo = (@$options["pageno"] <> "") ? $options["pageno"] : @$_GET["pageno"];

		// Check for a 'start' parameter
		if ($startGrp != "") {
			$this->StartGrp = $startGrp;
			$this->setStartGroup($this->StartGrp);
		} elseif ($pageNo != "") {
			$nPageNo = $pageNo;
			if (is_numeric($nPageNo)) {
				$this->StartGrp = ($nPageNo-1)*$this->DisplayGrps+1;
				if ($this->StartGrp <= 0) {
					$this->StartGrp = 1;
				} elseif ($this->StartGrp >= intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1) {
					$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1;
				}
				$this->setStartGroup($this->StartGrp);
			} else {
				$this->StartGrp = $this->getStartGroup();
			}
		} else {
			$this->StartGrp = $this->getStartGroup();
		}

		// Check if correct start group counter
		if (!is_numeric($this->StartGrp) || $this->StartGrp == "") { // Avoid invalid start group counter
			$this->StartGrp = 1; // Reset start group counter
			$this->setStartGroup($this->StartGrp);
		} elseif (intval($this->StartGrp) > intval($this->TotalGrps)) { // Avoid starting group > total groups
			$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to last page first group
			$this->setStartGroup($this->StartGrp);
		} elseif (($this->StartGrp-1) % $this->DisplayGrps <> 0) {
			$this->StartGrp = intval(($this->StartGrp-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to page boundary
			$this->setStartGroup($this->StartGrp);
		}
	}

	// Load group db values if necessary
	function LoadGroupDbValues() {
		$conn = &$this->Connection();
	}

	// Process Ajax popup
	function ProcessAjaxPopup() {
		global $ReportLanguage;
		$conn = &$this->Connection();
		$fld = NULL;
		if (@$_GET["popup"] <> "") {
			$popupname = $_GET["popup"];

			// Check popup name
			// Output data as Json

			if (!is_null($fld)) {
				$jsdb = ewr_GetJsDb($fld, $fld->FldType);
				if (ob_get_length())
					ob_end_clean();
				echo $jsdb;
				exit();
			}
		}
	}

	// Set up popup
	function SetupPopup() {
		global $ReportLanguage;
		$conn = &$this->Connection();
		if ($this->DrillDown)
			return;

		// Process post back form
		if (ewr_IsHttpPost()) {
			$sName = @$_POST["popup"]; // Get popup form name
			if ($sName <> "") {
				$cntValues = (is_array(@$_POST["sel_$sName"])) ? count($_POST["sel_$sName"]) : 0;
				if ($cntValues > 0) {
					$arValues = ewr_StripSlashes($_POST["sel_$sName"]);
					if (trim($arValues[0]) == "") // Select all
						$arValues = EWR_INIT_VALUE;
					$_SESSION["sel_$sName"] = $arValues;
					$_SESSION["rf_$sName"] = ewr_StripSlashes(@$_POST["rf_$sName"]);
					$_SESSION["rt_$sName"] = ewr_StripSlashes(@$_POST["rt_$sName"]);
					$this->ResetPager();
				}
			}

		// Get 'reset' command
		} elseif (@$_GET["cmd"] <> "") {
			$sCmd = $_GET["cmd"];
			if (strtolower($sCmd) == "reset") {
				$this->ResetPager();
			}
		}

		// Load selection criteria to array
	}

	// Reset pager
	function ResetPager() {

		// Reset start position (reset command)
		$this->StartGrp = 1;
		$this->setStartGroup($this->StartGrp);
	}

	// Set up number of groups displayed per page
	function SetUpDisplayGrps() {
		$sWrk = @$_GET[EWR_TABLE_GROUP_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayGrps = intval($sWrk);
			} else {
				if (strtoupper($sWrk) == "ALL") { // Display all groups
					$this->DisplayGrps = -1;
				} else {
					$this->DisplayGrps = 3; // Non-numeric, load default
				}
			}
			$this->setGroupPerPage($this->DisplayGrps); // Save to session

			// Reset start position (reset command)
			$this->StartGrp = 1;
			$this->setStartGroup($this->StartGrp);
		} else {
			if ($this->getGroupPerPage() <> "") {
				$this->DisplayGrps = $this->getGroupPerPage(); // Restore from session
			} else {
				$this->DisplayGrps = 3; // Load default
			}
		}
	}

	// Render row
	function RenderRow() {
		global $rs, $Security, $ReportLanguage;
		$conn = &$this->Connection();
		if (!$this->GrandSummarySetup) { // Get Grand total
			$bGotCount = FALSE;
			$bGotSummary = FALSE;

			// Get total count from sql directly
			$sSql = ewr_BuildReportSql($this->getSqlSelectCount(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, "");
			$rstot = $conn->Execute($sSql);
			if ($rstot) {
				$this->TotCount = ($rstot->RecordCount()>1) ? $rstot->RecordCount() : $rstot->fields[0];
				$rstot->Close();
				$bGotCount = TRUE;
			} else {
				$this->TotCount = 0;
			}
		$bGotSummary = TRUE;

			// Accumulate grand summary from detail records
			if (!$bGotCount || !$bGotSummary) {
				$sSql = ewr_BuildReportSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, "");
				$rs = $conn->Execute($sSql);
				if ($rs) {
					$this->GetRow(1);
					while (!$rs->EOF) {
						$this->AccumulateGrandSummary();
						$this->GetRow(2);
					}
					$rs->Close();
				}
			}
			$this->GrandSummarySetup = TRUE; // No need to set up again
		}

		// Call Row_Rendering event
		$this->Row_Rendering();

		//
		// Render view codes
		//

		if ($this->RowType == EWR_ROWTYPE_TOTAL && !($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowTotalSubType == EWR_ROWTOTAL_HEADER)) { // Summary row
			ewr_PrependClass($this->RowAttrs["class"], ($this->RowTotalType == EWR_ROWTOTAL_PAGE || $this->RowTotalType == EWR_ROWTOTAL_GRAND) ? "ewRptGrpAggregate" : "ewRptGrpSummary" . $this->RowGroupLevel); // Set up row class
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP) $this->RowAttrs["data-group"] = $this->group->GroupOldValue(); // Set up group attribute

			// group
			$this->group->GroupViewValue = $this->group->GroupOldValue();
			$this->group->CellAttrs["class"] = ($this->RowGroupLevel == 1) ? "ewRptGrpSummary1" : "ewRptGrpField1";
			$this->group->GroupViewValue = ewr_DisplayGroupValue($this->group, $this->group->GroupViewValue);
			$this->group->GroupSummaryOldValue = $this->group->GroupSummaryValue;
			$this->group->GroupSummaryValue = $this->group->GroupViewValue;
			$this->group->GroupSummaryViewValue = ($this->group->GroupSummaryOldValue <> $this->group->GroupSummaryValue) ? $this->group->GroupSummaryValue : "&nbsp;";

			// group
			$this->group->HrefValue = "";

			// id1
			$this->id1->HrefValue = "";

			// rekening1
			$this->rekening1->HrefValue = "";

			// id2
			$this->id2->HrefValue = "";

			// rekening2
			$this->rekening2->HrefValue = "";

			// tipe
			$this->tipe->HrefValue = "";

			// posisi
			$this->posisi->HrefValue = "";

			// laporan
			$this->laporan->HrefValue = "";

			// status
			$this->status->HrefValue = "";

			// parent
			$this->parent->HrefValue = "";

			// keterangan
			$this->keterangan->HrefValue = "";

			// active
			$this->active->HrefValue = "";

			// id
			$this->id->HrefValue = "";

			// rekening
			$this->rekening->HrefValue = "";
		} else {
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowTotalSubType == EWR_ROWTOTAL_HEADER) {
			$this->RowAttrs["data-group"] = $this->group->GroupValue(); // Set up group attribute
			} else {
			$this->RowAttrs["data-group"] = $this->group->GroupValue(); // Set up group attribute
			}

			// group
			$this->group->GroupViewValue = $this->group->GroupValue();
			$this->group->CellAttrs["class"] = "ewRptGrpField1";
			$this->group->GroupViewValue = ewr_DisplayGroupValue($this->group, $this->group->GroupViewValue);
			if ($this->group->GroupValue() == $this->group->GroupOldValue() && !$this->ChkLvlBreak(1))
				$this->group->GroupViewValue = "&nbsp;";

			// id1
			$this->id1->ViewValue = $this->id1->CurrentValue;
			$this->id1->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// rekening1
			$this->rekening1->ViewValue = $this->rekening1->CurrentValue;
			$this->rekening1->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// id2
			$this->id2->ViewValue = $this->id2->CurrentValue;
			$this->id2->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// rekening2
			$this->rekening2->ViewValue = $this->rekening2->CurrentValue;
			$this->rekening2->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// tipe
			$this->tipe->ViewValue = $this->tipe->CurrentValue;
			$this->tipe->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// posisi
			$this->posisi->ViewValue = $this->posisi->CurrentValue;
			$this->posisi->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// laporan
			$this->laporan->ViewValue = $this->laporan->CurrentValue;
			$this->laporan->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// status
			$this->status->ViewValue = $this->status->CurrentValue;
			$this->status->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// parent
			$this->parent->ViewValue = $this->parent->CurrentValue;
			$this->parent->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// keterangan
			$this->keterangan->ViewValue = $this->keterangan->CurrentValue;
			$this->keterangan->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// active
			$this->active->ViewValue = $this->active->CurrentValue;
			$this->active->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// rekening
			$this->rekening->ViewValue = $this->rekening->CurrentValue;
			$this->rekening->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// group
			$this->group->HrefValue = "";

			// id1
			$this->id1->HrefValue = "";

			// rekening1
			$this->rekening1->HrefValue = "";

			// id2
			$this->id2->HrefValue = "";

			// rekening2
			$this->rekening2->HrefValue = "";

			// tipe
			$this->tipe->HrefValue = "";

			// posisi
			$this->posisi->HrefValue = "";

			// laporan
			$this->laporan->HrefValue = "";

			// status
			$this->status->HrefValue = "";

			// parent
			$this->parent->HrefValue = "";

			// keterangan
			$this->keterangan->HrefValue = "";

			// active
			$this->active->HrefValue = "";

			// id
			$this->id->HrefValue = "";

			// rekening
			$this->rekening->HrefValue = "";
		}

		// Call Cell_Rendered event
		if ($this->RowType == EWR_ROWTYPE_TOTAL) { // Summary row

			// group
			$CurrentValue = $this->group->GroupViewValue;
			$ViewValue = &$this->group->GroupViewValue;
			$ViewAttrs = &$this->group->ViewAttrs;
			$CellAttrs = &$this->group->CellAttrs;
			$HrefValue = &$this->group->HrefValue;
			$LinkAttrs = &$this->group->LinkAttrs;
			$this->Cell_Rendered($this->group, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
		} else {

			// group
			$CurrentValue = $this->group->GroupValue();
			$ViewValue = &$this->group->GroupViewValue;
			$ViewAttrs = &$this->group->ViewAttrs;
			$CellAttrs = &$this->group->CellAttrs;
			$HrefValue = &$this->group->HrefValue;
			$LinkAttrs = &$this->group->LinkAttrs;
			$this->Cell_Rendered($this->group, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// id1
			$CurrentValue = $this->id1->CurrentValue;
			$ViewValue = &$this->id1->ViewValue;
			$ViewAttrs = &$this->id1->ViewAttrs;
			$CellAttrs = &$this->id1->CellAttrs;
			$HrefValue = &$this->id1->HrefValue;
			$LinkAttrs = &$this->id1->LinkAttrs;
			$this->Cell_Rendered($this->id1, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// rekening1
			$CurrentValue = $this->rekening1->CurrentValue;
			$ViewValue = &$this->rekening1->ViewValue;
			$ViewAttrs = &$this->rekening1->ViewAttrs;
			$CellAttrs = &$this->rekening1->CellAttrs;
			$HrefValue = &$this->rekening1->HrefValue;
			$LinkAttrs = &$this->rekening1->LinkAttrs;
			$this->Cell_Rendered($this->rekening1, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// id2
			$CurrentValue = $this->id2->CurrentValue;
			$ViewValue = &$this->id2->ViewValue;
			$ViewAttrs = &$this->id2->ViewAttrs;
			$CellAttrs = &$this->id2->CellAttrs;
			$HrefValue = &$this->id2->HrefValue;
			$LinkAttrs = &$this->id2->LinkAttrs;
			$this->Cell_Rendered($this->id2, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// rekening2
			$CurrentValue = $this->rekening2->CurrentValue;
			$ViewValue = &$this->rekening2->ViewValue;
			$ViewAttrs = &$this->rekening2->ViewAttrs;
			$CellAttrs = &$this->rekening2->CellAttrs;
			$HrefValue = &$this->rekening2->HrefValue;
			$LinkAttrs = &$this->rekening2->LinkAttrs;
			$this->Cell_Rendered($this->rekening2, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// tipe
			$CurrentValue = $this->tipe->CurrentValue;
			$ViewValue = &$this->tipe->ViewValue;
			$ViewAttrs = &$this->tipe->ViewAttrs;
			$CellAttrs = &$this->tipe->CellAttrs;
			$HrefValue = &$this->tipe->HrefValue;
			$LinkAttrs = &$this->tipe->LinkAttrs;
			$this->Cell_Rendered($this->tipe, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// posisi
			$CurrentValue = $this->posisi->CurrentValue;
			$ViewValue = &$this->posisi->ViewValue;
			$ViewAttrs = &$this->posisi->ViewAttrs;
			$CellAttrs = &$this->posisi->CellAttrs;
			$HrefValue = &$this->posisi->HrefValue;
			$LinkAttrs = &$this->posisi->LinkAttrs;
			$this->Cell_Rendered($this->posisi, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// laporan
			$CurrentValue = $this->laporan->CurrentValue;
			$ViewValue = &$this->laporan->ViewValue;
			$ViewAttrs = &$this->laporan->ViewAttrs;
			$CellAttrs = &$this->laporan->CellAttrs;
			$HrefValue = &$this->laporan->HrefValue;
			$LinkAttrs = &$this->laporan->LinkAttrs;
			$this->Cell_Rendered($this->laporan, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// status
			$CurrentValue = $this->status->CurrentValue;
			$ViewValue = &$this->status->ViewValue;
			$ViewAttrs = &$this->status->ViewAttrs;
			$CellAttrs = &$this->status->CellAttrs;
			$HrefValue = &$this->status->HrefValue;
			$LinkAttrs = &$this->status->LinkAttrs;
			$this->Cell_Rendered($this->status, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// parent
			$CurrentValue = $this->parent->CurrentValue;
			$ViewValue = &$this->parent->ViewValue;
			$ViewAttrs = &$this->parent->ViewAttrs;
			$CellAttrs = &$this->parent->CellAttrs;
			$HrefValue = &$this->parent->HrefValue;
			$LinkAttrs = &$this->parent->LinkAttrs;
			$this->Cell_Rendered($this->parent, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// keterangan
			$CurrentValue = $this->keterangan->CurrentValue;
			$ViewValue = &$this->keterangan->ViewValue;
			$ViewAttrs = &$this->keterangan->ViewAttrs;
			$CellAttrs = &$this->keterangan->CellAttrs;
			$HrefValue = &$this->keterangan->HrefValue;
			$LinkAttrs = &$this->keterangan->LinkAttrs;
			$this->Cell_Rendered($this->keterangan, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// active
			$CurrentValue = $this->active->CurrentValue;
			$ViewValue = &$this->active->ViewValue;
			$ViewAttrs = &$this->active->ViewAttrs;
			$CellAttrs = &$this->active->CellAttrs;
			$HrefValue = &$this->active->HrefValue;
			$LinkAttrs = &$this->active->LinkAttrs;
			$this->Cell_Rendered($this->active, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// id
			$CurrentValue = $this->id->CurrentValue;
			$ViewValue = &$this->id->ViewValue;
			$ViewAttrs = &$this->id->ViewAttrs;
			$CellAttrs = &$this->id->CellAttrs;
			$HrefValue = &$this->id->HrefValue;
			$LinkAttrs = &$this->id->LinkAttrs;
			$this->Cell_Rendered($this->id, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// rekening
			$CurrentValue = $this->rekening->CurrentValue;
			$ViewValue = &$this->rekening->ViewValue;
			$ViewAttrs = &$this->rekening->ViewAttrs;
			$CellAttrs = &$this->rekening->CellAttrs;
			$HrefValue = &$this->rekening->HrefValue;
			$LinkAttrs = &$this->rekening->LinkAttrs;
			$this->Cell_Rendered($this->rekening, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
		}

		// Call Row_Rendered event
		$this->Row_Rendered();
		$this->SetupFieldCount();
	}

	// Setup field count
	function SetupFieldCount() {
		$this->GrpColumnCount = 0;
		$this->SubGrpColumnCount = 0;
		$this->DtlColumnCount = 0;
		if ($this->group->Visible) $this->GrpColumnCount += 1;
		if ($this->id1->Visible) $this->DtlColumnCount += 1;
		if ($this->rekening1->Visible) $this->DtlColumnCount += 1;
		if ($this->id2->Visible) $this->DtlColumnCount += 1;
		if ($this->rekening2->Visible) $this->DtlColumnCount += 1;
		if ($this->tipe->Visible) $this->DtlColumnCount += 1;
		if ($this->posisi->Visible) $this->DtlColumnCount += 1;
		if ($this->laporan->Visible) $this->DtlColumnCount += 1;
		if ($this->status->Visible) $this->DtlColumnCount += 1;
		if ($this->parent->Visible) $this->DtlColumnCount += 1;
		if ($this->keterangan->Visible) $this->DtlColumnCount += 1;
		if ($this->active->Visible) $this->DtlColumnCount += 1;
		if ($this->id->Visible) $this->DtlColumnCount += 1;
		if ($this->rekening->Visible) $this->DtlColumnCount += 1;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $ReportBreadcrumb;
		$ReportBreadcrumb = new crBreadcrumb();
		$url = substr(ewr_CurrentUrl(), strrpos(ewr_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$ReportBreadcrumb->Add("summary", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	function SetupExportOptionsExt() {
		global $ReportLanguage, $ReportOptions;
		$ReportTypes = $ReportOptions["ReportTypes"];
		$ReportOptions["ReportTypes"] = $ReportTypes;
	}

	// Return popup filter
	function GetPopupFilter() {
		$sWrk = "";
		if ($this->DrillDown)
			return "";
		return $sWrk;
	}

	//-------------------------------------------------------------------------------
	// Function GetSort
	// - Return Sort parameters based on Sort Links clicked
	// - Variables setup: Session[EWR_TABLE_SESSION_ORDER_BY], Session["sort_Table_Field"]
	function GetSort($options = array()) {
		if ($this->DrillDown)
			return "";
		$bResetSort = @$options["resetsort"] == "1" || @$_GET["cmd"] == "resetsort";
		$orderBy = (@$options["order"] <> "") ? @$options["order"] : ewr_StripSlashes(@$_GET["order"]);
		$orderType = (@$options["ordertype"] <> "") ? @$options["ordertype"] : ewr_StripSlashes(@$_GET["ordertype"]);

		// Check for a resetsort command
		if ($bResetSort) {
			$this->setOrderBy("");
			$this->setStartGroup(1);
			$this->group->setSort("");
			$this->id1->setSort("");
			$this->rekening1->setSort("");
			$this->id2->setSort("");
			$this->rekening2->setSort("");
			$this->tipe->setSort("");
			$this->posisi->setSort("");
			$this->laporan->setSort("");
			$this->status->setSort("");
			$this->parent->setSort("");
			$this->keterangan->setSort("");
			$this->active->setSort("");
			$this->id->setSort("");
			$this->rekening->setSort("");

		// Check for an Order parameter
		} elseif ($orderBy <> "") {
			$this->CurrentOrder = $orderBy;
			$this->CurrentOrderType = $orderType;
			$sSortSql = $this->SortSql();
			$this->setOrderBy($sSortSql);
			$this->setStartGroup(1);
		}
		return $this->getOrderBy();
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
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
}
?>
<?php ewr_Header(FALSE) ?>
<?php

// Create page object
if (!isset($Report1_summary)) $Report1_summary = new crReport1_summary();
if (isset($Page)) $OldPage = $Page;
$Page = &$Report1_summary;

// Page init
$Page->Page_Init();

// Page main
$Page->Page_Main();

// Global Page Rendering event (in ewrusrfn*.php)
Page_Rendering();

// Page Rendering event
$Page->Page_Render();
?>
<?php include_once "header.php" ?>
<?php include_once "phprptinc/header.php" ?>
<script type="text/javascript">

// Create page object
var Report1_summary = new ewr_Page("Report1_summary");

// Page properties
Report1_summary.PageID = "summary"; // Page ID
var EWR_PAGE_ID = Report1_summary.PageID;

// Extend page with Chart_Rendering function
Report1_summary.Chart_Rendering = 
 function(chart, chartid) { // DO NOT CHANGE THIS LINE!

 	//alert(chartid);
 }

// Extend page with Chart_Rendered function
Report1_summary.Chart_Rendered = 
 function(chart, chartid) { // DO NOT CHANGE THIS LINE!

 	//alert(chartid);
 }
</script>
<?php if (!$Page->DrillDown) { ?>
<?php } ?>
<?php if (!$Page->DrillDown) { ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<!-- container (begin) -->
<div id="ewContainer" class="ewContainer">
<!-- top container (begin) -->
<div id="ewTop" class="ewTop">
<a id="top"></a>
<?php if (@$Page->GenOptions["showfilter"] == "1") { ?>
<?php $Page->ShowFilterList(TRUE) ?>
<?php } ?>
<!-- top slot -->
<div class="ewToolbar">
<?php if (!$Page->DrillDown || !$Page->DrillDownInPanel) { ?>
<?php if ($ReportBreadcrumb) $ReportBreadcrumb->Render(); ?>
<?php } ?>
<?php
if (!$Page->DrillDownInPanel) {
	$Page->ExportOptions->Render("body");
	$Page->SearchOptions->Render("body");
	$Page->FilterOptions->Render("body");
	$Page->GenerateOptions->Render("body");
}
?>
<?php if (!$Page->DrillDown) { ?>
<?php echo $ReportLanguage->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php $Page->ShowPageHeader(); ?>
<?php $Page->ShowMessage(); ?>
</div>
<!-- top container (end) -->
	<!-- left container (begin) -->
	<div id="ewLeft" class="ewLeft">
	<!-- Left slot -->
	</div>
	<!-- left container (end) -->
	<!-- center container - report (begin) -->
	<div id="ewCenter" class="ewCenter">
	<!-- center slot -->
<!-- summary report starts -->
<div id="report_summary">
<?php

// Set the last group to display if not export all
if ($Page->ExportAll && $Page->Export <> "") {
	$Page->StopGrp = $Page->TotalGrps;
} else {
	$Page->StopGrp = $Page->StartGrp + $Page->DisplayGrps - 1;
}

// Stop group <= total number of groups
if (intval($Page->StopGrp) > intval($Page->TotalGrps))
	$Page->StopGrp = $Page->TotalGrps;
$Page->RecCount = 0;
$Page->RecIndex = 0;

// Get first row
if ($Page->TotalGrps > 0) {
	$Page->GetGrpRow(1);
	$Page->GrpCount = 1;
}
$Page->GrpIdx = ewr_InitArray($Page->StopGrp - $Page->StartGrp + 1, -1);
while ($rsgrp && !$rsgrp->EOF && $Page->GrpCount <= $Page->DisplayGrps || $Page->ShowHeader) {

	// Show dummy header for custom template
	// Show header

	if ($Page->ShowHeader) {
?>
<?php if ($Page->GrpCount > 1) { ?>
</tbody>
</table>
</div>
<?php if (!($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php include "Report1smrypager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<span data-class="tpb<?php echo $Page->GrpCount-1 ?>_Report1"><?php echo $Page->PageBreakContent ?></span>
<?php } ?>
<?php if ($Page->Export == "word" || $Page->Export == "excel") { ?>
<div class="ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } else { ?>
<div class="panel panel-default ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } ?>
<!-- Report grid (begin) -->
<div class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="<?php echo $Page->ReportTableClass ?>">
<thead>
	<!-- Table header -->
	<tr class="ewTableHeader">
<?php if ($Page->group->Visible) { ?>
	<?php if ($Page->group->ShowGroupHeaderAsRow) { ?>
	<td data-field="group">&nbsp;</td>
	<?php } else { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="group"><div class="Report1_group"><span class="ewTableHeaderCaption"><?php echo $Page->group->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="group">
<?php if ($Page->SortUrl($Page->group) == "") { ?>
		<div class="ewTableHeaderBtn Report1_group">
			<span class="ewTableHeaderCaption"><?php echo $Page->group->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Report1_group" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->group) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->group->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->group->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->group->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
	<?php } ?>
<?php } ?>
<?php if ($Page->id1->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="id1"><div class="Report1_id1"><span class="ewTableHeaderCaption"><?php echo $Page->id1->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="id1">
<?php if ($Page->SortUrl($Page->id1) == "") { ?>
		<div class="ewTableHeaderBtn Report1_id1">
			<span class="ewTableHeaderCaption"><?php echo $Page->id1->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Report1_id1" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->id1) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->id1->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->id1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->id1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->rekening1->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="rekening1"><div class="Report1_rekening1"><span class="ewTableHeaderCaption"><?php echo $Page->rekening1->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="rekening1">
<?php if ($Page->SortUrl($Page->rekening1) == "") { ?>
		<div class="ewTableHeaderBtn Report1_rekening1">
			<span class="ewTableHeaderCaption"><?php echo $Page->rekening1->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Report1_rekening1" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->rekening1) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->rekening1->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->rekening1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->rekening1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->id2->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="id2"><div class="Report1_id2"><span class="ewTableHeaderCaption"><?php echo $Page->id2->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="id2">
<?php if ($Page->SortUrl($Page->id2) == "") { ?>
		<div class="ewTableHeaderBtn Report1_id2">
			<span class="ewTableHeaderCaption"><?php echo $Page->id2->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Report1_id2" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->id2) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->id2->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->id2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->id2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->rekening2->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="rekening2"><div class="Report1_rekening2"><span class="ewTableHeaderCaption"><?php echo $Page->rekening2->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="rekening2">
<?php if ($Page->SortUrl($Page->rekening2) == "") { ?>
		<div class="ewTableHeaderBtn Report1_rekening2">
			<span class="ewTableHeaderCaption"><?php echo $Page->rekening2->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Report1_rekening2" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->rekening2) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->rekening2->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->rekening2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->rekening2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->tipe->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="tipe"><div class="Report1_tipe"><span class="ewTableHeaderCaption"><?php echo $Page->tipe->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="tipe">
<?php if ($Page->SortUrl($Page->tipe) == "") { ?>
		<div class="ewTableHeaderBtn Report1_tipe">
			<span class="ewTableHeaderCaption"><?php echo $Page->tipe->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Report1_tipe" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->tipe) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->tipe->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->tipe->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->tipe->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->posisi->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="posisi"><div class="Report1_posisi"><span class="ewTableHeaderCaption"><?php echo $Page->posisi->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="posisi">
<?php if ($Page->SortUrl($Page->posisi) == "") { ?>
		<div class="ewTableHeaderBtn Report1_posisi">
			<span class="ewTableHeaderCaption"><?php echo $Page->posisi->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Report1_posisi" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->posisi) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->posisi->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->posisi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->posisi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->laporan->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="laporan"><div class="Report1_laporan"><span class="ewTableHeaderCaption"><?php echo $Page->laporan->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="laporan">
<?php if ($Page->SortUrl($Page->laporan) == "") { ?>
		<div class="ewTableHeaderBtn Report1_laporan">
			<span class="ewTableHeaderCaption"><?php echo $Page->laporan->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Report1_laporan" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->laporan) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->laporan->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->laporan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->laporan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->status->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="status"><div class="Report1_status"><span class="ewTableHeaderCaption"><?php echo $Page->status->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="status">
<?php if ($Page->SortUrl($Page->status) == "") { ?>
		<div class="ewTableHeaderBtn Report1_status">
			<span class="ewTableHeaderCaption"><?php echo $Page->status->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Report1_status" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->status) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->status->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->parent->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="parent"><div class="Report1_parent"><span class="ewTableHeaderCaption"><?php echo $Page->parent->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="parent">
<?php if ($Page->SortUrl($Page->parent) == "") { ?>
		<div class="ewTableHeaderBtn Report1_parent">
			<span class="ewTableHeaderCaption"><?php echo $Page->parent->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Report1_parent" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->parent) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->parent->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->parent->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->parent->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->keterangan->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="keterangan"><div class="Report1_keterangan"><span class="ewTableHeaderCaption"><?php echo $Page->keterangan->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="keterangan">
<?php if ($Page->SortUrl($Page->keterangan) == "") { ?>
		<div class="ewTableHeaderBtn Report1_keterangan">
			<span class="ewTableHeaderCaption"><?php echo $Page->keterangan->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Report1_keterangan" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->keterangan) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->keterangan->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->keterangan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->keterangan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->active->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="active"><div class="Report1_active"><span class="ewTableHeaderCaption"><?php echo $Page->active->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="active">
<?php if ($Page->SortUrl($Page->active) == "") { ?>
		<div class="ewTableHeaderBtn Report1_active">
			<span class="ewTableHeaderCaption"><?php echo $Page->active->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Report1_active" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->active) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->active->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->active->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->active->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->id->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="id"><div class="Report1_id"><span class="ewTableHeaderCaption"><?php echo $Page->id->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="id">
<?php if ($Page->SortUrl($Page->id) == "") { ?>
		<div class="ewTableHeaderBtn Report1_id">
			<span class="ewTableHeaderCaption"><?php echo $Page->id->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Report1_id" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->id) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->id->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->rekening->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="rekening"><div class="Report1_rekening"><span class="ewTableHeaderCaption"><?php echo $Page->rekening->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="rekening">
<?php if ($Page->SortUrl($Page->rekening) == "") { ?>
		<div class="ewTableHeaderBtn Report1_rekening">
			<span class="ewTableHeaderCaption"><?php echo $Page->rekening->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Report1_rekening" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->rekening) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->rekening->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->rekening->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->rekening->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
	</tr>
</thead>
<tbody>
<?php
		if ($Page->TotalGrps == 0) break; // Show header only
		$Page->ShowHeader = FALSE;
	}

	// Build detail SQL
	$sWhere = ewr_DetailFilterSQL($Page->group, $Page->getSqlFirstGroupField(), $Page->group->GroupValue(), $Page->DBID);
	if ($Page->PageFirstGroupFilter <> "") $Page->PageFirstGroupFilter .= " OR ";
	$Page->PageFirstGroupFilter .= $sWhere;
	if ($Page->Filter != "")
		$sWhere = "($Page->Filter) AND ($sWhere)";
	$sSql = ewr_BuildReportSql($Page->getSqlSelect(), $Page->getSqlWhere(), $Page->getSqlGroupBy(), $Page->getSqlHaving(), $Page->getSqlOrderBy(), $sWhere, $Page->Sort);
	$rs = $Page->GetDetailRs($sSql);
	$rsdtlcnt = ($rs) ? $rs->RecordCount() : 0;
	if ($rsdtlcnt > 0)
		$Page->GetRow(1);
	$Page->GrpIdx[$Page->GrpCount] = $rsdtlcnt;
	while ($rs && !$rs->EOF) { // Loop detail records
		$Page->RecCount++;
		$Page->RecIndex++;
?>
<?php if ($Page->group->Visible && $Page->ChkLvlBreak(1) && $Page->group->ShowGroupHeaderAsRow) { ?>
<?php

		// Render header row
		$Page->ResetAttrs();
		$Page->RowType = EWR_ROWTYPE_TOTAL;
		$Page->RowTotalType = EWR_ROWTOTAL_GROUP;
		$Page->RowTotalSubType = EWR_ROWTOTAL_HEADER;
		$Page->RowGroupLevel = 1;
		$Page->group->Count = $Page->GetSummaryCount(1);
		$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->group->Visible) { ?>
		<td data-field="group"<?php echo $Page->group->CellAttributes(); ?>><span class="ewGroupToggle icon-collapse"></span></td>
<?php } ?>
		<td data-field="group" colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount - 1) ?>"<?php echo $Page->group->CellAttributes() ?>>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
		<span class="ewSummaryCaption Report1_group"><span class="ewTableHeaderCaption"><?php echo $Page->group->FldCaption() ?></span></span>
<?php } else { ?>
	<?php if ($Page->SortUrl($Page->group) == "") { ?>
		<span class="ewSummaryCaption Report1_group">
			<span class="ewTableHeaderCaption"><?php echo $Page->group->FldCaption() ?></span>
		</span>
	<?php } else { ?>
		<span class="ewTableHeaderBtn ewPointer ewSummaryCaption Report1_group" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->group) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->group->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->group->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->group->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</span>
	<?php } ?>
<?php } ?>
		<?php echo $ReportLanguage->Phrase("SummaryColon") ?>
<span data-class="tpx<?php echo $Page->GrpCount ?>_Report1_group"<?php echo $Page->group->ViewAttributes() ?>><?php echo $Page->group->GroupViewValue ?></span>
		<span class="ewSummaryCount">(<span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->group->Count,0,-2,-2,-2) ?></span>)</span>
		</td>
	</tr>
<?php } ?>
<?php

		// Render detail row
		$Page->ResetAttrs();
		$Page->RowType = EWR_ROWTYPE_DETAIL;
		$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->group->Visible) { ?>
	<?php if ($Page->group->ShowGroupHeaderAsRow) { ?>
		<td data-field="group"<?php echo $Page->group->CellAttributes(); ?>>&nbsp;</td>
	<?php } else { ?>
		<td data-field="group"<?php echo $Page->group->CellAttributes(); ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_Report1_group"<?php echo $Page->group->ViewAttributes() ?>><?php echo $Page->group->GroupViewValue ?></span></td>
	<?php } ?>
<?php } ?>
<?php if ($Page->id1->Visible) { ?>
		<td data-field="id1"<?php echo $Page->id1->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->RecCount ?>_Report1_id1"<?php echo $Page->id1->ViewAttributes() ?>><?php echo $Page->id1->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->rekening1->Visible) { ?>
		<td data-field="rekening1"<?php echo $Page->rekening1->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->RecCount ?>_Report1_rekening1"<?php echo $Page->rekening1->ViewAttributes() ?>><?php echo $Page->rekening1->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->id2->Visible) { ?>
		<td data-field="id2"<?php echo $Page->id2->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->RecCount ?>_Report1_id2"<?php echo $Page->id2->ViewAttributes() ?>><?php echo $Page->id2->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->rekening2->Visible) { ?>
		<td data-field="rekening2"<?php echo $Page->rekening2->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->RecCount ?>_Report1_rekening2"<?php echo $Page->rekening2->ViewAttributes() ?>><?php echo $Page->rekening2->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->tipe->Visible) { ?>
		<td data-field="tipe"<?php echo $Page->tipe->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->RecCount ?>_Report1_tipe"<?php echo $Page->tipe->ViewAttributes() ?>><?php echo $Page->tipe->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->posisi->Visible) { ?>
		<td data-field="posisi"<?php echo $Page->posisi->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->RecCount ?>_Report1_posisi"<?php echo $Page->posisi->ViewAttributes() ?>><?php echo $Page->posisi->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->laporan->Visible) { ?>
		<td data-field="laporan"<?php echo $Page->laporan->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->RecCount ?>_Report1_laporan"<?php echo $Page->laporan->ViewAttributes() ?>><?php echo $Page->laporan->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->status->Visible) { ?>
		<td data-field="status"<?php echo $Page->status->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->RecCount ?>_Report1_status"<?php echo $Page->status->ViewAttributes() ?>><?php echo $Page->status->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->parent->Visible) { ?>
		<td data-field="parent"<?php echo $Page->parent->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->RecCount ?>_Report1_parent"<?php echo $Page->parent->ViewAttributes() ?>><?php echo $Page->parent->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->keterangan->Visible) { ?>
		<td data-field="keterangan"<?php echo $Page->keterangan->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->RecCount ?>_Report1_keterangan"<?php echo $Page->keterangan->ViewAttributes() ?>><?php echo $Page->keterangan->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->active->Visible) { ?>
		<td data-field="active"<?php echo $Page->active->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->RecCount ?>_Report1_active"<?php echo $Page->active->ViewAttributes() ?>><?php echo $Page->active->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->id->Visible) { ?>
		<td data-field="id"<?php echo $Page->id->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->RecCount ?>_Report1_id"<?php echo $Page->id->ViewAttributes() ?>><?php echo $Page->id->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->rekening->Visible) { ?>
		<td data-field="rekening"<?php echo $Page->rekening->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->RecCount ?>_Report1_rekening"<?php echo $Page->rekening->ViewAttributes() ?>><?php echo $Page->rekening->ListViewValue() ?></span></td>
<?php } ?>
	</tr>
<?php

		// Accumulate page summary
		$Page->AccumulateSummary();

		// Get next record
		$Page->GetRow(2);

		// Show Footers
?>
<?php
	} // End detail records loop
?>
<?php

	// Next group
	$Page->GetGrpRow(2);

	// Show header if page break
	if ($Page->Export <> "")
		$Page->ShowHeader = ($Page->ExportPageBreakCount == 0) ? FALSE : ($Page->GrpCount % $Page->ExportPageBreakCount == 0);

	// Page_Breaking server event
	if ($Page->ShowHeader)
		$Page->Page_Breaking($Page->ShowHeader, $Page->PageBreakContent);
	$Page->GrpCount++;

	// Handle EOF
	if (!$rsgrp || $rsgrp->EOF)
		$Page->ShowHeader = FALSE;
} // End while
?>
<?php if ($Page->TotalGrps > 0) { ?>
</tbody>
<tfoot>
<?php
	$Page->ResetAttrs();
	$Page->RowType = EWR_ROWTYPE_TOTAL;
	$Page->RowTotalType = EWR_ROWTOTAL_GRAND;
	$Page->RowTotalSubType = EWR_ROWTOTAL_FOOTER;
	$Page->RowAttrs["class"] = "ewRptGrandSummary";
	$Page->RenderRow();
?>
<?php if ($Page->group->ShowCompactSummaryFooter) { ?>
	<tr<?php echo $Page->RowAttributes() ?>><td colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount) ?>"><?php echo $ReportLanguage->Phrase("RptGrandSummary") ?> (<span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->TotCount,0,-2,-2,-2) ?></span>)</td></tr>
<?php } else { ?>
	<tr<?php echo $Page->RowAttributes() ?>><td colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount) ?>"><?php echo $ReportLanguage->Phrase("RptGrandSummary") ?> <span class="ewDirLtr">(<?php echo ewr_FormatNumber($Page->TotCount,0,-2,-2,-2); ?><?php echo $ReportLanguage->Phrase("RptDtlRec") ?>)</span></td></tr>
<?php } ?>
	</tfoot>
<?php } elseif (!$Page->ShowHeader && FALSE) { // No header displayed ?>
<?php if ($Page->Export == "word" || $Page->Export == "excel") { ?>
<div class="ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } else { ?>
<div class="panel panel-default ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } ?>
<!-- Report grid (begin) -->
<div class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="<?php echo $Page->ReportTableClass ?>">
<?php } ?>
<?php if ($Page->TotalGrps > 0 || FALSE) { // Show footer ?>
</table>
</div>
<?php if (!($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php include "Report1smrypager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
</div>
<!-- Summary Report Ends -->
	</div>
	<!-- center container - report (end) -->
	<!-- right container (begin) -->
	<div id="ewRight" class="ewRight">
	<!-- Right slot -->
	</div>
	<!-- right container (end) -->
<div class="clearfix"></div>
<!-- bottom container (begin) -->
<div id="ewBottom" class="ewBottom">
	<!-- Bottom slot -->
	</div>
<!-- Bottom Container (End) -->
</div>
<!-- Table Container (End) -->
<?php $Page->ShowPageFooter(); ?>
<?php if (EWR_DEBUG_ENABLED) echo ewr_DebugMsg(); ?>
<?php

// Close recordsets
if ($rsgrp) $rsgrp->Close();
if ($rs) $rs->Close();
?>
<?php if (!$Page->DrillDown) { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "phprptinc/footer.php" ?>
<?php include_once "footer.php" ?>
<?php
$Page->Page_Terminate();
if (isset($OldPage)) $Page = $OldPage;
?>
