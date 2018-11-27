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

$tlaporan_view = NULL; // Initialize page object first

class ctlaporan_view extends ctlaporan {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tlaporan';

	// Page object name
	var $PageObjName = 'tlaporan_view';

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
		$KeyUrl = "";
		if (@$_GET["nomor"] <> "") {
			$this->RecKey["nomor"] = $_GET["nomor"];
			$KeyUrl .= "&amp;nomor=" . urlencode($this->RecKey["nomor"]);
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
			define("EW_TABLE_NAME", 'tlaporan', TRUE);

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
			if (@$_GET["nomor"] <> "") {
				$this->nomor->setQueryStringValue($_GET["nomor"]);
				$this->RecKey["nomor"] = $this->nomor->QueryStringValue;
			} elseif (@$_POST["nomor"] <> "") {
				$this->nomor->setFormValue($_POST["nomor"]);
				$this->RecKey["nomor"] = $this->nomor->FormValue;
			} else {
				$sReturnUrl = "tlaporanlist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "tlaporanlist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "tlaporanlist.php"; // Not page request, return to list
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("tlaporanlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($tlaporan_view)) $tlaporan_view = new ctlaporan_view();

// Page init
$tlaporan_view->Page_Init();

// Page main
$tlaporan_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tlaporan_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = ftlaporanview = new ew_Form("ftlaporanview", "view");

// Form_CustomValidate event
ftlaporanview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftlaporanview.ValidateRequired = true;
<?php } else { ?>
ftlaporanview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if (!$tlaporan_view->IsModal) { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php $tlaporan_view->ExportOptions->Render("body") ?>
<?php
	foreach ($tlaporan_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if (!$tlaporan_view->IsModal) { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php $tlaporan_view->ShowPageHeader(); ?>
<?php
$tlaporan_view->ShowMessage();
?>
<form name="ftlaporanview" id="ftlaporanview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tlaporan_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tlaporan_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tlaporan">
<?php if ($tlaporan_view->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($tlaporan->tanggal->Visible) { // tanggal ?>
	<tr id="r_tanggal">
		<td><span id="elh_tlaporan_tanggal"><?php echo $tlaporan->tanggal->FldCaption() ?></span></td>
		<td data-name="tanggal"<?php echo $tlaporan->tanggal->CellAttributes() ?>>
<span id="el_tlaporan_tanggal">
<span<?php echo $tlaporan->tanggal->ViewAttributes() ?>>
<?php echo $tlaporan->tanggal->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->periode->Visible) { // periode ?>
	<tr id="r_periode">
		<td><span id="elh_tlaporan_periode"><?php echo $tlaporan->periode->FldCaption() ?></span></td>
		<td data-name="periode"<?php echo $tlaporan->periode->CellAttributes() ?>>
<span id="el_tlaporan_periode">
<span<?php echo $tlaporan->periode->ViewAttributes() ?>>
<?php echo $tlaporan->periode->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->id->Visible) { // id ?>
	<tr id="r_id">
		<td><span id="elh_tlaporan_id"><?php echo $tlaporan->id->FldCaption() ?></span></td>
		<td data-name="id"<?php echo $tlaporan->id->CellAttributes() ?>>
<span id="el_tlaporan_id">
<span<?php echo $tlaporan->id->ViewAttributes() ?>>
<?php echo $tlaporan->id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->nomor->Visible) { // nomor ?>
	<tr id="r_nomor">
		<td><span id="elh_tlaporan_nomor"><?php echo $tlaporan->nomor->FldCaption() ?></span></td>
		<td data-name="nomor"<?php echo $tlaporan->nomor->CellAttributes() ?>>
<span id="el_tlaporan_nomor">
<span<?php echo $tlaporan->nomor->ViewAttributes() ?>>
<?php echo $tlaporan->nomor->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->transaksi->Visible) { // transaksi ?>
	<tr id="r_transaksi">
		<td><span id="elh_tlaporan_transaksi"><?php echo $tlaporan->transaksi->FldCaption() ?></span></td>
		<td data-name="transaksi"<?php echo $tlaporan->transaksi->CellAttributes() ?>>
<span id="el_tlaporan_transaksi">
<span<?php echo $tlaporan->transaksi->ViewAttributes() ?>>
<?php echo $tlaporan->transaksi->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->referensi->Visible) { // referensi ?>
	<tr id="r_referensi">
		<td><span id="elh_tlaporan_referensi"><?php echo $tlaporan->referensi->FldCaption() ?></span></td>
		<td data-name="referensi"<?php echo $tlaporan->referensi->CellAttributes() ?>>
<span id="el_tlaporan_referensi">
<span<?php echo $tlaporan->referensi->ViewAttributes() ?>>
<?php echo $tlaporan->referensi->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->group->Visible) { // group ?>
	<tr id="r_group">
		<td><span id="elh_tlaporan_group"><?php echo $tlaporan->group->FldCaption() ?></span></td>
		<td data-name="group"<?php echo $tlaporan->group->CellAttributes() ?>>
<span id="el_tlaporan_group">
<span<?php echo $tlaporan->group->ViewAttributes() ?>>
<?php echo $tlaporan->group->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->rekening->Visible) { // rekening ?>
	<tr id="r_rekening">
		<td><span id="elh_tlaporan_rekening"><?php echo $tlaporan->rekening->FldCaption() ?></span></td>
		<td data-name="rekening"<?php echo $tlaporan->rekening->CellAttributes() ?>>
<span id="el_tlaporan_rekening">
<span<?php echo $tlaporan->rekening->ViewAttributes() ?>>
<?php echo $tlaporan->rekening->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->tipe->Visible) { // tipe ?>
	<tr id="r_tipe">
		<td><span id="elh_tlaporan_tipe"><?php echo $tlaporan->tipe->FldCaption() ?></span></td>
		<td data-name="tipe"<?php echo $tlaporan->tipe->CellAttributes() ?>>
<span id="el_tlaporan_tipe">
<span<?php echo $tlaporan->tipe->ViewAttributes() ?>>
<?php echo $tlaporan->tipe->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->posisi->Visible) { // posisi ?>
	<tr id="r_posisi">
		<td><span id="elh_tlaporan_posisi"><?php echo $tlaporan->posisi->FldCaption() ?></span></td>
		<td data-name="posisi"<?php echo $tlaporan->posisi->CellAttributes() ?>>
<span id="el_tlaporan_posisi">
<span<?php echo $tlaporan->posisi->ViewAttributes() ?>>
<?php echo $tlaporan->posisi->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->laporan->Visible) { // laporan ?>
	<tr id="r_laporan">
		<td><span id="elh_tlaporan_laporan"><?php echo $tlaporan->laporan->FldCaption() ?></span></td>
		<td data-name="laporan"<?php echo $tlaporan->laporan->CellAttributes() ?>>
<span id="el_tlaporan_laporan">
<span<?php echo $tlaporan->laporan->ViewAttributes() ?>>
<?php echo $tlaporan->laporan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->keterangan->Visible) { // keterangan ?>
	<tr id="r_keterangan">
		<td><span id="elh_tlaporan_keterangan"><?php echo $tlaporan->keterangan->FldCaption() ?></span></td>
		<td data-name="keterangan"<?php echo $tlaporan->keterangan->CellAttributes() ?>>
<span id="el_tlaporan_keterangan">
<span<?php echo $tlaporan->keterangan->ViewAttributes() ?>>
<?php echo $tlaporan->keterangan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->debet1->Visible) { // debet1 ?>
	<tr id="r_debet1">
		<td><span id="elh_tlaporan_debet1"><?php echo $tlaporan->debet1->FldCaption() ?></span></td>
		<td data-name="debet1"<?php echo $tlaporan->debet1->CellAttributes() ?>>
<span id="el_tlaporan_debet1">
<span<?php echo $tlaporan->debet1->ViewAttributes() ?>>
<?php echo $tlaporan->debet1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->credit1->Visible) { // credit1 ?>
	<tr id="r_credit1">
		<td><span id="elh_tlaporan_credit1"><?php echo $tlaporan->credit1->FldCaption() ?></span></td>
		<td data-name="credit1"<?php echo $tlaporan->credit1->CellAttributes() ?>>
<span id="el_tlaporan_credit1">
<span<?php echo $tlaporan->credit1->ViewAttributes() ?>>
<?php echo $tlaporan->credit1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->saldo1->Visible) { // saldo1 ?>
	<tr id="r_saldo1">
		<td><span id="elh_tlaporan_saldo1"><?php echo $tlaporan->saldo1->FldCaption() ?></span></td>
		<td data-name="saldo1"<?php echo $tlaporan->saldo1->CellAttributes() ?>>
<span id="el_tlaporan_saldo1">
<span<?php echo $tlaporan->saldo1->ViewAttributes() ?>>
<?php echo $tlaporan->saldo1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->debet2->Visible) { // debet2 ?>
	<tr id="r_debet2">
		<td><span id="elh_tlaporan_debet2"><?php echo $tlaporan->debet2->FldCaption() ?></span></td>
		<td data-name="debet2"<?php echo $tlaporan->debet2->CellAttributes() ?>>
<span id="el_tlaporan_debet2">
<span<?php echo $tlaporan->debet2->ViewAttributes() ?>>
<?php echo $tlaporan->debet2->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->credit2->Visible) { // credit2 ?>
	<tr id="r_credit2">
		<td><span id="elh_tlaporan_credit2"><?php echo $tlaporan->credit2->FldCaption() ?></span></td>
		<td data-name="credit2"<?php echo $tlaporan->credit2->CellAttributes() ?>>
<span id="el_tlaporan_credit2">
<span<?php echo $tlaporan->credit2->ViewAttributes() ?>>
<?php echo $tlaporan->credit2->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->saldo2->Visible) { // saldo2 ?>
	<tr id="r_saldo2">
		<td><span id="elh_tlaporan_saldo2"><?php echo $tlaporan->saldo2->FldCaption() ?></span></td>
		<td data-name="saldo2"<?php echo $tlaporan->saldo2->CellAttributes() ?>>
<span id="el_tlaporan_saldo2">
<span<?php echo $tlaporan->saldo2->ViewAttributes() ?>>
<?php echo $tlaporan->saldo2->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->debet3->Visible) { // debet3 ?>
	<tr id="r_debet3">
		<td><span id="elh_tlaporan_debet3"><?php echo $tlaporan->debet3->FldCaption() ?></span></td>
		<td data-name="debet3"<?php echo $tlaporan->debet3->CellAttributes() ?>>
<span id="el_tlaporan_debet3">
<span<?php echo $tlaporan->debet3->ViewAttributes() ?>>
<?php echo $tlaporan->debet3->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->credit3->Visible) { // credit3 ?>
	<tr id="r_credit3">
		<td><span id="elh_tlaporan_credit3"><?php echo $tlaporan->credit3->FldCaption() ?></span></td>
		<td data-name="credit3"<?php echo $tlaporan->credit3->CellAttributes() ?>>
<span id="el_tlaporan_credit3">
<span<?php echo $tlaporan->credit3->ViewAttributes() ?>>
<?php echo $tlaporan->credit3->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->saldo3->Visible) { // saldo3 ?>
	<tr id="r_saldo3">
		<td><span id="elh_tlaporan_saldo3"><?php echo $tlaporan->saldo3->FldCaption() ?></span></td>
		<td data-name="saldo3"<?php echo $tlaporan->saldo3->CellAttributes() ?>>
<span id="el_tlaporan_saldo3">
<span<?php echo $tlaporan->saldo3->ViewAttributes() ?>>
<?php echo $tlaporan->saldo3->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->debet4->Visible) { // debet4 ?>
	<tr id="r_debet4">
		<td><span id="elh_tlaporan_debet4"><?php echo $tlaporan->debet4->FldCaption() ?></span></td>
		<td data-name="debet4"<?php echo $tlaporan->debet4->CellAttributes() ?>>
<span id="el_tlaporan_debet4">
<span<?php echo $tlaporan->debet4->ViewAttributes() ?>>
<?php echo $tlaporan->debet4->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->credit4->Visible) { // credit4 ?>
	<tr id="r_credit4">
		<td><span id="elh_tlaporan_credit4"><?php echo $tlaporan->credit4->FldCaption() ?></span></td>
		<td data-name="credit4"<?php echo $tlaporan->credit4->CellAttributes() ?>>
<span id="el_tlaporan_credit4">
<span<?php echo $tlaporan->credit4->ViewAttributes() ?>>
<?php echo $tlaporan->credit4->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->saldo4->Visible) { // saldo4 ?>
	<tr id="r_saldo4">
		<td><span id="elh_tlaporan_saldo4"><?php echo $tlaporan->saldo4->FldCaption() ?></span></td>
		<td data-name="saldo4"<?php echo $tlaporan->saldo4->CellAttributes() ?>>
<span id="el_tlaporan_saldo4">
<span<?php echo $tlaporan->saldo4->ViewAttributes() ?>>
<?php echo $tlaporan->saldo4->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->debet5->Visible) { // debet5 ?>
	<tr id="r_debet5">
		<td><span id="elh_tlaporan_debet5"><?php echo $tlaporan->debet5->FldCaption() ?></span></td>
		<td data-name="debet5"<?php echo $tlaporan->debet5->CellAttributes() ?>>
<span id="el_tlaporan_debet5">
<span<?php echo $tlaporan->debet5->ViewAttributes() ?>>
<?php echo $tlaporan->debet5->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->credit5->Visible) { // credit5 ?>
	<tr id="r_credit5">
		<td><span id="elh_tlaporan_credit5"><?php echo $tlaporan->credit5->FldCaption() ?></span></td>
		<td data-name="credit5"<?php echo $tlaporan->credit5->CellAttributes() ?>>
<span id="el_tlaporan_credit5">
<span<?php echo $tlaporan->credit5->ViewAttributes() ?>>
<?php echo $tlaporan->credit5->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->saldo5->Visible) { // saldo5 ?>
	<tr id="r_saldo5">
		<td><span id="elh_tlaporan_saldo5"><?php echo $tlaporan->saldo5->FldCaption() ?></span></td>
		<td data-name="saldo5"<?php echo $tlaporan->saldo5->CellAttributes() ?>>
<span id="el_tlaporan_saldo5">
<span<?php echo $tlaporan->saldo5->ViewAttributes() ?>>
<?php echo $tlaporan->saldo5->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->debet6->Visible) { // debet6 ?>
	<tr id="r_debet6">
		<td><span id="elh_tlaporan_debet6"><?php echo $tlaporan->debet6->FldCaption() ?></span></td>
		<td data-name="debet6"<?php echo $tlaporan->debet6->CellAttributes() ?>>
<span id="el_tlaporan_debet6">
<span<?php echo $tlaporan->debet6->ViewAttributes() ?>>
<?php echo $tlaporan->debet6->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->credit6->Visible) { // credit6 ?>
	<tr id="r_credit6">
		<td><span id="elh_tlaporan_credit6"><?php echo $tlaporan->credit6->FldCaption() ?></span></td>
		<td data-name="credit6"<?php echo $tlaporan->credit6->CellAttributes() ?>>
<span id="el_tlaporan_credit6">
<span<?php echo $tlaporan->credit6->ViewAttributes() ?>>
<?php echo $tlaporan->credit6->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->saldo6->Visible) { // saldo6 ?>
	<tr id="r_saldo6">
		<td><span id="elh_tlaporan_saldo6"><?php echo $tlaporan->saldo6->FldCaption() ?></span></td>
		<td data-name="saldo6"<?php echo $tlaporan->saldo6->CellAttributes() ?>>
<span id="el_tlaporan_saldo6">
<span<?php echo $tlaporan->saldo6->ViewAttributes() ?>>
<?php echo $tlaporan->saldo6->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->debet7->Visible) { // debet7 ?>
	<tr id="r_debet7">
		<td><span id="elh_tlaporan_debet7"><?php echo $tlaporan->debet7->FldCaption() ?></span></td>
		<td data-name="debet7"<?php echo $tlaporan->debet7->CellAttributes() ?>>
<span id="el_tlaporan_debet7">
<span<?php echo $tlaporan->debet7->ViewAttributes() ?>>
<?php echo $tlaporan->debet7->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->credit7->Visible) { // credit7 ?>
	<tr id="r_credit7">
		<td><span id="elh_tlaporan_credit7"><?php echo $tlaporan->credit7->FldCaption() ?></span></td>
		<td data-name="credit7"<?php echo $tlaporan->credit7->CellAttributes() ?>>
<span id="el_tlaporan_credit7">
<span<?php echo $tlaporan->credit7->ViewAttributes() ?>>
<?php echo $tlaporan->credit7->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->saldo7->Visible) { // saldo7 ?>
	<tr id="r_saldo7">
		<td><span id="elh_tlaporan_saldo7"><?php echo $tlaporan->saldo7->FldCaption() ?></span></td>
		<td data-name="saldo7"<?php echo $tlaporan->saldo7->CellAttributes() ?>>
<span id="el_tlaporan_saldo7">
<span<?php echo $tlaporan->saldo7->ViewAttributes() ?>>
<?php echo $tlaporan->saldo7->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->debet8->Visible) { // debet8 ?>
	<tr id="r_debet8">
		<td><span id="elh_tlaporan_debet8"><?php echo $tlaporan->debet8->FldCaption() ?></span></td>
		<td data-name="debet8"<?php echo $tlaporan->debet8->CellAttributes() ?>>
<span id="el_tlaporan_debet8">
<span<?php echo $tlaporan->debet8->ViewAttributes() ?>>
<?php echo $tlaporan->debet8->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->credit8->Visible) { // credit8 ?>
	<tr id="r_credit8">
		<td><span id="elh_tlaporan_credit8"><?php echo $tlaporan->credit8->FldCaption() ?></span></td>
		<td data-name="credit8"<?php echo $tlaporan->credit8->CellAttributes() ?>>
<span id="el_tlaporan_credit8">
<span<?php echo $tlaporan->credit8->ViewAttributes() ?>>
<?php echo $tlaporan->credit8->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->saldo8->Visible) { // saldo8 ?>
	<tr id="r_saldo8">
		<td><span id="elh_tlaporan_saldo8"><?php echo $tlaporan->saldo8->FldCaption() ?></span></td>
		<td data-name="saldo8"<?php echo $tlaporan->saldo8->CellAttributes() ?>>
<span id="el_tlaporan_saldo8">
<span<?php echo $tlaporan->saldo8->ViewAttributes() ?>>
<?php echo $tlaporan->saldo8->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->debet9->Visible) { // debet9 ?>
	<tr id="r_debet9">
		<td><span id="elh_tlaporan_debet9"><?php echo $tlaporan->debet9->FldCaption() ?></span></td>
		<td data-name="debet9"<?php echo $tlaporan->debet9->CellAttributes() ?>>
<span id="el_tlaporan_debet9">
<span<?php echo $tlaporan->debet9->ViewAttributes() ?>>
<?php echo $tlaporan->debet9->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->credit9->Visible) { // credit9 ?>
	<tr id="r_credit9">
		<td><span id="elh_tlaporan_credit9"><?php echo $tlaporan->credit9->FldCaption() ?></span></td>
		<td data-name="credit9"<?php echo $tlaporan->credit9->CellAttributes() ?>>
<span id="el_tlaporan_credit9">
<span<?php echo $tlaporan->credit9->ViewAttributes() ?>>
<?php echo $tlaporan->credit9->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->saldo9->Visible) { // saldo9 ?>
	<tr id="r_saldo9">
		<td><span id="elh_tlaporan_saldo9"><?php echo $tlaporan->saldo9->FldCaption() ?></span></td>
		<td data-name="saldo9"<?php echo $tlaporan->saldo9->CellAttributes() ?>>
<span id="el_tlaporan_saldo9">
<span<?php echo $tlaporan->saldo9->ViewAttributes() ?>>
<?php echo $tlaporan->saldo9->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->debet10->Visible) { // debet10 ?>
	<tr id="r_debet10">
		<td><span id="elh_tlaporan_debet10"><?php echo $tlaporan->debet10->FldCaption() ?></span></td>
		<td data-name="debet10"<?php echo $tlaporan->debet10->CellAttributes() ?>>
<span id="el_tlaporan_debet10">
<span<?php echo $tlaporan->debet10->ViewAttributes() ?>>
<?php echo $tlaporan->debet10->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->credit10->Visible) { // credit10 ?>
	<tr id="r_credit10">
		<td><span id="elh_tlaporan_credit10"><?php echo $tlaporan->credit10->FldCaption() ?></span></td>
		<td data-name="credit10"<?php echo $tlaporan->credit10->CellAttributes() ?>>
<span id="el_tlaporan_credit10">
<span<?php echo $tlaporan->credit10->ViewAttributes() ?>>
<?php echo $tlaporan->credit10->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->saldo10->Visible) { // saldo10 ?>
	<tr id="r_saldo10">
		<td><span id="elh_tlaporan_saldo10"><?php echo $tlaporan->saldo10->FldCaption() ?></span></td>
		<td data-name="saldo10"<?php echo $tlaporan->saldo10->CellAttributes() ?>>
<span id="el_tlaporan_saldo10">
<span<?php echo $tlaporan->saldo10->ViewAttributes() ?>>
<?php echo $tlaporan->saldo10->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->debet11->Visible) { // debet11 ?>
	<tr id="r_debet11">
		<td><span id="elh_tlaporan_debet11"><?php echo $tlaporan->debet11->FldCaption() ?></span></td>
		<td data-name="debet11"<?php echo $tlaporan->debet11->CellAttributes() ?>>
<span id="el_tlaporan_debet11">
<span<?php echo $tlaporan->debet11->ViewAttributes() ?>>
<?php echo $tlaporan->debet11->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->credit11->Visible) { // credit11 ?>
	<tr id="r_credit11">
		<td><span id="elh_tlaporan_credit11"><?php echo $tlaporan->credit11->FldCaption() ?></span></td>
		<td data-name="credit11"<?php echo $tlaporan->credit11->CellAttributes() ?>>
<span id="el_tlaporan_credit11">
<span<?php echo $tlaporan->credit11->ViewAttributes() ?>>
<?php echo $tlaporan->credit11->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->saldo11->Visible) { // saldo11 ?>
	<tr id="r_saldo11">
		<td><span id="elh_tlaporan_saldo11"><?php echo $tlaporan->saldo11->FldCaption() ?></span></td>
		<td data-name="saldo11"<?php echo $tlaporan->saldo11->CellAttributes() ?>>
<span id="el_tlaporan_saldo11">
<span<?php echo $tlaporan->saldo11->ViewAttributes() ?>>
<?php echo $tlaporan->saldo11->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->debet12->Visible) { // debet12 ?>
	<tr id="r_debet12">
		<td><span id="elh_tlaporan_debet12"><?php echo $tlaporan->debet12->FldCaption() ?></span></td>
		<td data-name="debet12"<?php echo $tlaporan->debet12->CellAttributes() ?>>
<span id="el_tlaporan_debet12">
<span<?php echo $tlaporan->debet12->ViewAttributes() ?>>
<?php echo $tlaporan->debet12->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->credit12->Visible) { // credit12 ?>
	<tr id="r_credit12">
		<td><span id="elh_tlaporan_credit12"><?php echo $tlaporan->credit12->FldCaption() ?></span></td>
		<td data-name="credit12"<?php echo $tlaporan->credit12->CellAttributes() ?>>
<span id="el_tlaporan_credit12">
<span<?php echo $tlaporan->credit12->ViewAttributes() ?>>
<?php echo $tlaporan->credit12->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tlaporan->saldo12->Visible) { // saldo12 ?>
	<tr id="r_saldo12">
		<td><span id="elh_tlaporan_saldo12"><?php echo $tlaporan->saldo12->FldCaption() ?></span></td>
		<td data-name="saldo12"<?php echo $tlaporan->saldo12->CellAttributes() ?>>
<span id="el_tlaporan_saldo12">
<span<?php echo $tlaporan->saldo12->ViewAttributes() ?>>
<?php echo $tlaporan->saldo12->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
ftlaporanview.Init();
</script>
<?php
$tlaporan_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tlaporan_view->Page_Terminate();
?>