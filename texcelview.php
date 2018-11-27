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

$texcel_view = NULL; // Initialize page object first

class ctexcel_view extends ctexcel {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'texcel';

	// Page object name
	var $PageObjName = 'texcel_view';

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
		$KeyUrl = "";
		if (@$_GET["SHEET"] <> "") {
			$this->RecKey["SHEET"] = $_GET["SHEET"];
			$KeyUrl .= "&amp;SHEET=" . urlencode($this->RecKey["SHEET"]);
		}
		if (@$_GET["NOMOR"] <> "") {
			$this->RecKey["NOMOR"] = $_GET["NOMOR"];
			$KeyUrl .= "&amp;NOMOR=" . urlencode($this->RecKey["NOMOR"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'texcel', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
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

		// Create Token
		$this->CreateToken();
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

			// Handle modal response
			if ($this->IsModal) {
				$row = array();
				$row["url"] = $url;
				echo ew_ArrayToJson(array($row));
			} else {
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $IsModal = FALSE;
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["SHEET"] <> "") {
				$this->SHEET->setQueryStringValue($_GET["SHEET"]);
				$this->RecKey["SHEET"] = $this->SHEET->QueryStringValue;
			} elseif (@$_POST["SHEET"] <> "") {
				$this->SHEET->setFormValue($_POST["SHEET"]);
				$this->RecKey["SHEET"] = $this->SHEET->FormValue;
			} else {
				$sReturnUrl = "texcellist.php"; // Return to list
			}
			if (@$_GET["NOMOR"] <> "") {
				$this->NOMOR->setQueryStringValue($_GET["NOMOR"]);
				$this->RecKey["NOMOR"] = $this->NOMOR->QueryStringValue;
			} elseif (@$_POST["NOMOR"] <> "") {
				$this->NOMOR->setFormValue($_POST["NOMOR"]);
				$this->RecKey["NOMOR"] = $this->NOMOR->FormValue;
			} else {
				$sReturnUrl = "texcellist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "texcellist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "texcellist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("ViewPageAddLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->AddUrl) . "',caption:'" . $addcaption . "'});\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "");

		// Edit
		$item = &$option->Add("edit");
		$editcaption = ew_HtmlTitle($Language->Phrase("ViewPageEditLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->EditUrl) . "',caption:'" . $editcaption . "'});\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "");

		// Copy
		$item = &$option->Add("copy");
		$copycaption = ew_HtmlTitle($Language->Phrase("ViewPageCopyLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->CopyUrl) . "',caption:'" . $copycaption . "'});\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "");

		// Delete
		$item = &$option->Add("delete");
		if ($this->IsModal) // Handle as inline delete
			$item->Body = "<a onclick=\"return ew_ConfirmDelete(this);\" class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode(ew_AddQueryStringToUrl($this->DeleteUrl, "a_delete=1")) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "");

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
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

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("texcellist.php"), "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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
if (!isset($texcel_view)) $texcel_view = new ctexcel_view();

// Page init
$texcel_view->Page_Init();

// Page main
$texcel_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$texcel_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = ftexcelview = new ew_Form("ftexcelview", "view");

// Form_CustomValidate event
ftexcelview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftexcelview.ValidateRequired = true;
<?php } else { ?>
ftexcelview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if (!$texcel_view->IsModal) { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php $texcel_view->ExportOptions->Render("body") ?>
<?php
	foreach ($texcel_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if (!$texcel_view->IsModal) { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php $texcel_view->ShowPageHeader(); ?>
<?php
$texcel_view->ShowMessage();
?>
<form name="ftexcelview" id="ftexcelview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($texcel_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $texcel_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="texcel">
<?php if ($texcel_view->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($texcel->SHEET->Visible) { // SHEET ?>
	<tr id="r_SHEET">
		<td><span id="elh_texcel_SHEET"><?php echo $texcel->SHEET->FldCaption() ?></span></td>
		<td data-name="SHEET"<?php echo $texcel->SHEET->CellAttributes() ?>>
<span id="el_texcel_SHEET">
<span<?php echo $texcel->SHEET->ViewAttributes() ?>>
<?php echo $texcel->SHEET->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($texcel->NOMOR->Visible) { // NOMOR ?>
	<tr id="r_NOMOR">
		<td><span id="elh_texcel_NOMOR"><?php echo $texcel->NOMOR->FldCaption() ?></span></td>
		<td data-name="NOMOR"<?php echo $texcel->NOMOR->CellAttributes() ?>>
<span id="el_texcel_NOMOR">
<span<?php echo $texcel->NOMOR->ViewAttributes() ?>>
<?php echo $texcel->NOMOR->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($texcel->A->Visible) { // A ?>
	<tr id="r_A">
		<td><span id="elh_texcel_A"><?php echo $texcel->A->FldCaption() ?></span></td>
		<td data-name="A"<?php echo $texcel->A->CellAttributes() ?>>
<span id="el_texcel_A">
<span<?php echo $texcel->A->ViewAttributes() ?>>
<?php echo $texcel->A->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($texcel->B->Visible) { // B ?>
	<tr id="r_B">
		<td><span id="elh_texcel_B"><?php echo $texcel->B->FldCaption() ?></span></td>
		<td data-name="B"<?php echo $texcel->B->CellAttributes() ?>>
<span id="el_texcel_B">
<span<?php echo $texcel->B->ViewAttributes() ?>>
<?php echo $texcel->B->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($texcel->C->Visible) { // C ?>
	<tr id="r_C">
		<td><span id="elh_texcel_C"><?php echo $texcel->C->FldCaption() ?></span></td>
		<td data-name="C"<?php echo $texcel->C->CellAttributes() ?>>
<span id="el_texcel_C">
<span<?php echo $texcel->C->ViewAttributes() ?>>
<?php echo $texcel->C->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($texcel->D->Visible) { // D ?>
	<tr id="r_D">
		<td><span id="elh_texcel_D"><?php echo $texcel->D->FldCaption() ?></span></td>
		<td data-name="D"<?php echo $texcel->D->CellAttributes() ?>>
<span id="el_texcel_D">
<span<?php echo $texcel->D->ViewAttributes() ?>>
<?php echo $texcel->D->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($texcel->E->Visible) { // E ?>
	<tr id="r_E">
		<td><span id="elh_texcel_E"><?php echo $texcel->E->FldCaption() ?></span></td>
		<td data-name="E"<?php echo $texcel->E->CellAttributes() ?>>
<span id="el_texcel_E">
<span<?php echo $texcel->E->ViewAttributes() ?>>
<?php echo $texcel->E->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($texcel->F->Visible) { // F ?>
	<tr id="r_F">
		<td><span id="elh_texcel_F"><?php echo $texcel->F->FldCaption() ?></span></td>
		<td data-name="F"<?php echo $texcel->F->CellAttributes() ?>>
<span id="el_texcel_F">
<span<?php echo $texcel->F->ViewAttributes() ?>>
<?php echo $texcel->F->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($texcel->G->Visible) { // G ?>
	<tr id="r_G">
		<td><span id="elh_texcel_G"><?php echo $texcel->G->FldCaption() ?></span></td>
		<td data-name="G"<?php echo $texcel->G->CellAttributes() ?>>
<span id="el_texcel_G">
<span<?php echo $texcel->G->ViewAttributes() ?>>
<?php echo $texcel->G->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($texcel->H->Visible) { // H ?>
	<tr id="r_H">
		<td><span id="elh_texcel_H"><?php echo $texcel->H->FldCaption() ?></span></td>
		<td data-name="H"<?php echo $texcel->H->CellAttributes() ?>>
<span id="el_texcel_H">
<span<?php echo $texcel->H->ViewAttributes() ?>>
<?php echo $texcel->H->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($texcel->_I->Visible) { // I ?>
	<tr id="r__I">
		<td><span id="elh_texcel__I"><?php echo $texcel->_I->FldCaption() ?></span></td>
		<td data-name="_I"<?php echo $texcel->_I->CellAttributes() ?>>
<span id="el_texcel__I">
<span<?php echo $texcel->_I->ViewAttributes() ?>>
<?php echo $texcel->_I->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($texcel->J->Visible) { // J ?>
	<tr id="r_J">
		<td><span id="elh_texcel_J"><?php echo $texcel->J->FldCaption() ?></span></td>
		<td data-name="J"<?php echo $texcel->J->CellAttributes() ?>>
<span id="el_texcel_J">
<span<?php echo $texcel->J->ViewAttributes() ?>>
<?php echo $texcel->J->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($texcel->K->Visible) { // K ?>
	<tr id="r_K">
		<td><span id="elh_texcel_K"><?php echo $texcel->K->FldCaption() ?></span></td>
		<td data-name="K"<?php echo $texcel->K->CellAttributes() ?>>
<span id="el_texcel_K">
<span<?php echo $texcel->K->ViewAttributes() ?>>
<?php echo $texcel->K->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($texcel->L->Visible) { // L ?>
	<tr id="r_L">
		<td><span id="elh_texcel_L"><?php echo $texcel->L->FldCaption() ?></span></td>
		<td data-name="L"<?php echo $texcel->L->CellAttributes() ?>>
<span id="el_texcel_L">
<span<?php echo $texcel->L->ViewAttributes() ?>>
<?php echo $texcel->L->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($texcel->M->Visible) { // M ?>
	<tr id="r_M">
		<td><span id="elh_texcel_M"><?php echo $texcel->M->FldCaption() ?></span></td>
		<td data-name="M"<?php echo $texcel->M->CellAttributes() ?>>
<span id="el_texcel_M">
<span<?php echo $texcel->M->ViewAttributes() ?>>
<?php echo $texcel->M->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($texcel->N->Visible) { // N ?>
	<tr id="r_N">
		<td><span id="elh_texcel_N"><?php echo $texcel->N->FldCaption() ?></span></td>
		<td data-name="N"<?php echo $texcel->N->CellAttributes() ?>>
<span id="el_texcel_N">
<span<?php echo $texcel->N->ViewAttributes() ?>>
<?php echo $texcel->N->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($texcel->O->Visible) { // O ?>
	<tr id="r_O">
		<td><span id="elh_texcel_O"><?php echo $texcel->O->FldCaption() ?></span></td>
		<td data-name="O"<?php echo $texcel->O->CellAttributes() ?>>
<span id="el_texcel_O">
<span<?php echo $texcel->O->ViewAttributes() ?>>
<?php echo $texcel->O->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($texcel->P->Visible) { // P ?>
	<tr id="r_P">
		<td><span id="elh_texcel_P"><?php echo $texcel->P->FldCaption() ?></span></td>
		<td data-name="P"<?php echo $texcel->P->CellAttributes() ?>>
<span id="el_texcel_P">
<span<?php echo $texcel->P->ViewAttributes() ?>>
<?php echo $texcel->P->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($texcel->Q->Visible) { // Q ?>
	<tr id="r_Q">
		<td><span id="elh_texcel_Q"><?php echo $texcel->Q->FldCaption() ?></span></td>
		<td data-name="Q"<?php echo $texcel->Q->CellAttributes() ?>>
<span id="el_texcel_Q">
<span<?php echo $texcel->Q->ViewAttributes() ?>>
<?php echo $texcel->Q->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($texcel->R->Visible) { // R ?>
	<tr id="r_R">
		<td><span id="elh_texcel_R"><?php echo $texcel->R->FldCaption() ?></span></td>
		<td data-name="R"<?php echo $texcel->R->CellAttributes() ?>>
<span id="el_texcel_R">
<span<?php echo $texcel->R->ViewAttributes() ?>>
<?php echo $texcel->R->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($texcel->S->Visible) { // S ?>
	<tr id="r_S">
		<td><span id="elh_texcel_S"><?php echo $texcel->S->FldCaption() ?></span></td>
		<td data-name="S"<?php echo $texcel->S->CellAttributes() ?>>
<span id="el_texcel_S">
<span<?php echo $texcel->S->ViewAttributes() ?>>
<?php echo $texcel->S->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($texcel->T->Visible) { // T ?>
	<tr id="r_T">
		<td><span id="elh_texcel_T"><?php echo $texcel->T->FldCaption() ?></span></td>
		<td data-name="T"<?php echo $texcel->T->CellAttributes() ?>>
<span id="el_texcel_T">
<span<?php echo $texcel->T->ViewAttributes() ?>>
<?php echo $texcel->T->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($texcel->U->Visible) { // U ?>
	<tr id="r_U">
		<td><span id="elh_texcel_U"><?php echo $texcel->U->FldCaption() ?></span></td>
		<td data-name="U"<?php echo $texcel->U->CellAttributes() ?>>
<span id="el_texcel_U">
<span<?php echo $texcel->U->ViewAttributes() ?>>
<?php echo $texcel->U->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($texcel->V->Visible) { // V ?>
	<tr id="r_V">
		<td><span id="elh_texcel_V"><?php echo $texcel->V->FldCaption() ?></span></td>
		<td data-name="V"<?php echo $texcel->V->CellAttributes() ?>>
<span id="el_texcel_V">
<span<?php echo $texcel->V->ViewAttributes() ?>>
<?php echo $texcel->V->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($texcel->W->Visible) { // W ?>
	<tr id="r_W">
		<td><span id="elh_texcel_W"><?php echo $texcel->W->FldCaption() ?></span></td>
		<td data-name="W"<?php echo $texcel->W->CellAttributes() ?>>
<span id="el_texcel_W">
<span<?php echo $texcel->W->ViewAttributes() ?>>
<?php echo $texcel->W->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($texcel->X->Visible) { // X ?>
	<tr id="r_X">
		<td><span id="elh_texcel_X"><?php echo $texcel->X->FldCaption() ?></span></td>
		<td data-name="X"<?php echo $texcel->X->CellAttributes() ?>>
<span id="el_texcel_X">
<span<?php echo $texcel->X->ViewAttributes() ?>>
<?php echo $texcel->X->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($texcel->Y->Visible) { // Y ?>
	<tr id="r_Y">
		<td><span id="elh_texcel_Y"><?php echo $texcel->Y->FldCaption() ?></span></td>
		<td data-name="Y"<?php echo $texcel->Y->CellAttributes() ?>>
<span id="el_texcel_Y">
<span<?php echo $texcel->Y->ViewAttributes() ?>>
<?php echo $texcel->Y->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($texcel->Z->Visible) { // Z ?>
	<tr id="r_Z">
		<td><span id="elh_texcel_Z"><?php echo $texcel->Z->FldCaption() ?></span></td>
		<td data-name="Z"<?php echo $texcel->Z->CellAttributes() ?>>
<span id="el_texcel_Z">
<span<?php echo $texcel->Z->ViewAttributes() ?>>
<?php echo $texcel->Z->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
ftexcelview.Init();
</script>
<?php
$texcel_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$texcel_view->Page_Terminate();
?>
