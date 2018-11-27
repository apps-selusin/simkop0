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

$tpinjamandetail_view = NULL; // Initialize page object first

class ctpinjamandetail_view extends ctpinjamandetail {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tpinjamandetail';

	// Page object name
	var $PageObjName = 'tpinjamandetail_view';

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
		$KeyUrl = "";
		if (@$_GET["id"] <> "") {
			$this->RecKey["id"] = $_GET["id"];
			$KeyUrl .= "&amp;id=" . urlencode($this->RecKey["id"]);
		}
		if (@$_GET["angsuran"] <> "") {
			$this->RecKey["angsuran"] = $_GET["angsuran"];
			$KeyUrl .= "&amp;angsuran=" . urlencode($this->RecKey["angsuran"]);
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
			define("EW_TABLE_NAME", 'tpinjamandetail', TRUE);

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
			if (@$_GET["id"] <> "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->RecKey["id"] = $this->id->QueryStringValue;
			} elseif (@$_POST["id"] <> "") {
				$this->id->setFormValue($_POST["id"]);
				$this->RecKey["id"] = $this->id->FormValue;
			} else {
				$sReturnUrl = "tpinjamandetaillist.php"; // Return to list
			}
			if (@$_GET["angsuran"] <> "") {
				$this->angsuran->setQueryStringValue($_GET["angsuran"]);
				$this->RecKey["angsuran"] = $this->angsuran->QueryStringValue;
			} elseif (@$_POST["angsuran"] <> "") {
				$this->angsuran->setFormValue($_POST["angsuran"]);
				$this->RecKey["angsuran"] = $this->angsuran->FormValue;
			} else {
				$sReturnUrl = "tpinjamandetaillist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "tpinjamandetaillist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "tpinjamandetaillist.php"; // Not page request, return to list
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("tpinjamandetaillist.php"), "", $this->TableVar, TRUE);
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
if (!isset($tpinjamandetail_view)) $tpinjamandetail_view = new ctpinjamandetail_view();

// Page init
$tpinjamandetail_view->Page_Init();

// Page main
$tpinjamandetail_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tpinjamandetail_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = ftpinjamandetailview = new ew_Form("ftpinjamandetailview", "view");

// Form_CustomValidate event
ftpinjamandetailview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftpinjamandetailview.ValidateRequired = true;
<?php } else { ?>
ftpinjamandetailview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if (!$tpinjamandetail_view->IsModal) { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php $tpinjamandetail_view->ExportOptions->Render("body") ?>
<?php
	foreach ($tpinjamandetail_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if (!$tpinjamandetail_view->IsModal) { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php $tpinjamandetail_view->ShowPageHeader(); ?>
<?php
$tpinjamandetail_view->ShowMessage();
?>
<form name="ftpinjamandetailview" id="ftpinjamandetailview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tpinjamandetail_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tpinjamandetail_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tpinjamandetail">
<?php if ($tpinjamandetail_view->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($tpinjamandetail->tanggal->Visible) { // tanggal ?>
	<tr id="r_tanggal">
		<td><span id="elh_tpinjamandetail_tanggal"><?php echo $tpinjamandetail->tanggal->FldCaption() ?></span></td>
		<td data-name="tanggal"<?php echo $tpinjamandetail->tanggal->CellAttributes() ?>>
<span id="el_tpinjamandetail_tanggal">
<span<?php echo $tpinjamandetail->tanggal->ViewAttributes() ?>>
<?php echo $tpinjamandetail->tanggal->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->periode->Visible) { // periode ?>
	<tr id="r_periode">
		<td><span id="elh_tpinjamandetail_periode"><?php echo $tpinjamandetail->periode->FldCaption() ?></span></td>
		<td data-name="periode"<?php echo $tpinjamandetail->periode->CellAttributes() ?>>
<span id="el_tpinjamandetail_periode">
<span<?php echo $tpinjamandetail->periode->ViewAttributes() ?>>
<?php echo $tpinjamandetail->periode->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->id->Visible) { // id ?>
	<tr id="r_id">
		<td><span id="elh_tpinjamandetail_id"><?php echo $tpinjamandetail->id->FldCaption() ?></span></td>
		<td data-name="id"<?php echo $tpinjamandetail->id->CellAttributes() ?>>
<span id="el_tpinjamandetail_id">
<span<?php echo $tpinjamandetail->id->ViewAttributes() ?>>
<?php echo $tpinjamandetail->id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->angsuran->Visible) { // angsuran ?>
	<tr id="r_angsuran">
		<td><span id="elh_tpinjamandetail_angsuran"><?php echo $tpinjamandetail->angsuran->FldCaption() ?></span></td>
		<td data-name="angsuran"<?php echo $tpinjamandetail->angsuran->CellAttributes() ?>>
<span id="el_tpinjamandetail_angsuran">
<span<?php echo $tpinjamandetail->angsuran->ViewAttributes() ?>>
<?php echo $tpinjamandetail->angsuran->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->masaangsuran->Visible) { // masaangsuran ?>
	<tr id="r_masaangsuran">
		<td><span id="elh_tpinjamandetail_masaangsuran"><?php echo $tpinjamandetail->masaangsuran->FldCaption() ?></span></td>
		<td data-name="masaangsuran"<?php echo $tpinjamandetail->masaangsuran->CellAttributes() ?>>
<span id="el_tpinjamandetail_masaangsuran">
<span<?php echo $tpinjamandetail->masaangsuran->ViewAttributes() ?>>
<?php echo $tpinjamandetail->masaangsuran->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->dispensasidenda->Visible) { // dispensasidenda ?>
	<tr id="r_dispensasidenda">
		<td><span id="elh_tpinjamandetail_dispensasidenda"><?php echo $tpinjamandetail->dispensasidenda->FldCaption() ?></span></td>
		<td data-name="dispensasidenda"<?php echo $tpinjamandetail->dispensasidenda->CellAttributes() ?>>
<span id="el_tpinjamandetail_dispensasidenda">
<span<?php echo $tpinjamandetail->dispensasidenda->ViewAttributes() ?>>
<?php echo $tpinjamandetail->dispensasidenda->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->plafond->Visible) { // plafond ?>
	<tr id="r_plafond">
		<td><span id="elh_tpinjamandetail_plafond"><?php echo $tpinjamandetail->plafond->FldCaption() ?></span></td>
		<td data-name="plafond"<?php echo $tpinjamandetail->plafond->CellAttributes() ?>>
<span id="el_tpinjamandetail_plafond">
<span<?php echo $tpinjamandetail->plafond->ViewAttributes() ?>>
<?php echo $tpinjamandetail->plafond->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->angsuranpokok->Visible) { // angsuranpokok ?>
	<tr id="r_angsuranpokok">
		<td><span id="elh_tpinjamandetail_angsuranpokok"><?php echo $tpinjamandetail->angsuranpokok->FldCaption() ?></span></td>
		<td data-name="angsuranpokok"<?php echo $tpinjamandetail->angsuranpokok->CellAttributes() ?>>
<span id="el_tpinjamandetail_angsuranpokok">
<span<?php echo $tpinjamandetail->angsuranpokok->ViewAttributes() ?>>
<?php echo $tpinjamandetail->angsuranpokok->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->angsuranpokokauto->Visible) { // angsuranpokokauto ?>
	<tr id="r_angsuranpokokauto">
		<td><span id="elh_tpinjamandetail_angsuranpokokauto"><?php echo $tpinjamandetail->angsuranpokokauto->FldCaption() ?></span></td>
		<td data-name="angsuranpokokauto"<?php echo $tpinjamandetail->angsuranpokokauto->CellAttributes() ?>>
<span id="el_tpinjamandetail_angsuranpokokauto">
<span<?php echo $tpinjamandetail->angsuranpokokauto->ViewAttributes() ?>>
<?php echo $tpinjamandetail->angsuranpokokauto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->angsuranbunga->Visible) { // angsuranbunga ?>
	<tr id="r_angsuranbunga">
		<td><span id="elh_tpinjamandetail_angsuranbunga"><?php echo $tpinjamandetail->angsuranbunga->FldCaption() ?></span></td>
		<td data-name="angsuranbunga"<?php echo $tpinjamandetail->angsuranbunga->CellAttributes() ?>>
<span id="el_tpinjamandetail_angsuranbunga">
<span<?php echo $tpinjamandetail->angsuranbunga->ViewAttributes() ?>>
<?php echo $tpinjamandetail->angsuranbunga->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->angsuranbungaauto->Visible) { // angsuranbungaauto ?>
	<tr id="r_angsuranbungaauto">
		<td><span id="elh_tpinjamandetail_angsuranbungaauto"><?php echo $tpinjamandetail->angsuranbungaauto->FldCaption() ?></span></td>
		<td data-name="angsuranbungaauto"<?php echo $tpinjamandetail->angsuranbungaauto->CellAttributes() ?>>
<span id="el_tpinjamandetail_angsuranbungaauto">
<span<?php echo $tpinjamandetail->angsuranbungaauto->ViewAttributes() ?>>
<?php echo $tpinjamandetail->angsuranbungaauto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->denda->Visible) { // denda ?>
	<tr id="r_denda">
		<td><span id="elh_tpinjamandetail_denda"><?php echo $tpinjamandetail->denda->FldCaption() ?></span></td>
		<td data-name="denda"<?php echo $tpinjamandetail->denda->CellAttributes() ?>>
<span id="el_tpinjamandetail_denda">
<span<?php echo $tpinjamandetail->denda->ViewAttributes() ?>>
<?php echo $tpinjamandetail->denda->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->dendapersen->Visible) { // dendapersen ?>
	<tr id="r_dendapersen">
		<td><span id="elh_tpinjamandetail_dendapersen"><?php echo $tpinjamandetail->dendapersen->FldCaption() ?></span></td>
		<td data-name="dendapersen"<?php echo $tpinjamandetail->dendapersen->CellAttributes() ?>>
<span id="el_tpinjamandetail_dendapersen">
<span<?php echo $tpinjamandetail->dendapersen->ViewAttributes() ?>>
<?php echo $tpinjamandetail->dendapersen->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->totalangsuran->Visible) { // totalangsuran ?>
	<tr id="r_totalangsuran">
		<td><span id="elh_tpinjamandetail_totalangsuran"><?php echo $tpinjamandetail->totalangsuran->FldCaption() ?></span></td>
		<td data-name="totalangsuran"<?php echo $tpinjamandetail->totalangsuran->CellAttributes() ?>>
<span id="el_tpinjamandetail_totalangsuran">
<span<?php echo $tpinjamandetail->totalangsuran->ViewAttributes() ?>>
<?php echo $tpinjamandetail->totalangsuran->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->totalangsuranauto->Visible) { // totalangsuranauto ?>
	<tr id="r_totalangsuranauto">
		<td><span id="elh_tpinjamandetail_totalangsuranauto"><?php echo $tpinjamandetail->totalangsuranauto->FldCaption() ?></span></td>
		<td data-name="totalangsuranauto"<?php echo $tpinjamandetail->totalangsuranauto->CellAttributes() ?>>
<span id="el_tpinjamandetail_totalangsuranauto">
<span<?php echo $tpinjamandetail->totalangsuranauto->ViewAttributes() ?>>
<?php echo $tpinjamandetail->totalangsuranauto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->sisaangsuran->Visible) { // sisaangsuran ?>
	<tr id="r_sisaangsuran">
		<td><span id="elh_tpinjamandetail_sisaangsuran"><?php echo $tpinjamandetail->sisaangsuran->FldCaption() ?></span></td>
		<td data-name="sisaangsuran"<?php echo $tpinjamandetail->sisaangsuran->CellAttributes() ?>>
<span id="el_tpinjamandetail_sisaangsuran">
<span<?php echo $tpinjamandetail->sisaangsuran->ViewAttributes() ?>>
<?php echo $tpinjamandetail->sisaangsuran->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->sisaangsuranauto->Visible) { // sisaangsuranauto ?>
	<tr id="r_sisaangsuranauto">
		<td><span id="elh_tpinjamandetail_sisaangsuranauto"><?php echo $tpinjamandetail->sisaangsuranauto->FldCaption() ?></span></td>
		<td data-name="sisaangsuranauto"<?php echo $tpinjamandetail->sisaangsuranauto->CellAttributes() ?>>
<span id="el_tpinjamandetail_sisaangsuranauto">
<span<?php echo $tpinjamandetail->sisaangsuranauto->ViewAttributes() ?>>
<?php echo $tpinjamandetail->sisaangsuranauto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->tanggalbayar->Visible) { // tanggalbayar ?>
	<tr id="r_tanggalbayar">
		<td><span id="elh_tpinjamandetail_tanggalbayar"><?php echo $tpinjamandetail->tanggalbayar->FldCaption() ?></span></td>
		<td data-name="tanggalbayar"<?php echo $tpinjamandetail->tanggalbayar->CellAttributes() ?>>
<span id="el_tpinjamandetail_tanggalbayar">
<span<?php echo $tpinjamandetail->tanggalbayar->ViewAttributes() ?>>
<?php echo $tpinjamandetail->tanggalbayar->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->terlambat->Visible) { // terlambat ?>
	<tr id="r_terlambat">
		<td><span id="elh_tpinjamandetail_terlambat"><?php echo $tpinjamandetail->terlambat->FldCaption() ?></span></td>
		<td data-name="terlambat"<?php echo $tpinjamandetail->terlambat->CellAttributes() ?>>
<span id="el_tpinjamandetail_terlambat">
<span<?php echo $tpinjamandetail->terlambat->ViewAttributes() ?>>
<?php echo $tpinjamandetail->terlambat->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->bayarpokok->Visible) { // bayarpokok ?>
	<tr id="r_bayarpokok">
		<td><span id="elh_tpinjamandetail_bayarpokok"><?php echo $tpinjamandetail->bayarpokok->FldCaption() ?></span></td>
		<td data-name="bayarpokok"<?php echo $tpinjamandetail->bayarpokok->CellAttributes() ?>>
<span id="el_tpinjamandetail_bayarpokok">
<span<?php echo $tpinjamandetail->bayarpokok->ViewAttributes() ?>>
<?php echo $tpinjamandetail->bayarpokok->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->bayarpokokauto->Visible) { // bayarpokokauto ?>
	<tr id="r_bayarpokokauto">
		<td><span id="elh_tpinjamandetail_bayarpokokauto"><?php echo $tpinjamandetail->bayarpokokauto->FldCaption() ?></span></td>
		<td data-name="bayarpokokauto"<?php echo $tpinjamandetail->bayarpokokauto->CellAttributes() ?>>
<span id="el_tpinjamandetail_bayarpokokauto">
<span<?php echo $tpinjamandetail->bayarpokokauto->ViewAttributes() ?>>
<?php echo $tpinjamandetail->bayarpokokauto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->bayarbunga->Visible) { // bayarbunga ?>
	<tr id="r_bayarbunga">
		<td><span id="elh_tpinjamandetail_bayarbunga"><?php echo $tpinjamandetail->bayarbunga->FldCaption() ?></span></td>
		<td data-name="bayarbunga"<?php echo $tpinjamandetail->bayarbunga->CellAttributes() ?>>
<span id="el_tpinjamandetail_bayarbunga">
<span<?php echo $tpinjamandetail->bayarbunga->ViewAttributes() ?>>
<?php echo $tpinjamandetail->bayarbunga->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->bayarbungaauto->Visible) { // bayarbungaauto ?>
	<tr id="r_bayarbungaauto">
		<td><span id="elh_tpinjamandetail_bayarbungaauto"><?php echo $tpinjamandetail->bayarbungaauto->FldCaption() ?></span></td>
		<td data-name="bayarbungaauto"<?php echo $tpinjamandetail->bayarbungaauto->CellAttributes() ?>>
<span id="el_tpinjamandetail_bayarbungaauto">
<span<?php echo $tpinjamandetail->bayarbungaauto->ViewAttributes() ?>>
<?php echo $tpinjamandetail->bayarbungaauto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->bayardenda->Visible) { // bayardenda ?>
	<tr id="r_bayardenda">
		<td><span id="elh_tpinjamandetail_bayardenda"><?php echo $tpinjamandetail->bayardenda->FldCaption() ?></span></td>
		<td data-name="bayardenda"<?php echo $tpinjamandetail->bayardenda->CellAttributes() ?>>
<span id="el_tpinjamandetail_bayardenda">
<span<?php echo $tpinjamandetail->bayardenda->ViewAttributes() ?>>
<?php echo $tpinjamandetail->bayardenda->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->bayardendaauto->Visible) { // bayardendaauto ?>
	<tr id="r_bayardendaauto">
		<td><span id="elh_tpinjamandetail_bayardendaauto"><?php echo $tpinjamandetail->bayardendaauto->FldCaption() ?></span></td>
		<td data-name="bayardendaauto"<?php echo $tpinjamandetail->bayardendaauto->CellAttributes() ?>>
<span id="el_tpinjamandetail_bayardendaauto">
<span<?php echo $tpinjamandetail->bayardendaauto->ViewAttributes() ?>>
<?php echo $tpinjamandetail->bayardendaauto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->bayartitipan->Visible) { // bayartitipan ?>
	<tr id="r_bayartitipan">
		<td><span id="elh_tpinjamandetail_bayartitipan"><?php echo $tpinjamandetail->bayartitipan->FldCaption() ?></span></td>
		<td data-name="bayartitipan"<?php echo $tpinjamandetail->bayartitipan->CellAttributes() ?>>
<span id="el_tpinjamandetail_bayartitipan">
<span<?php echo $tpinjamandetail->bayartitipan->ViewAttributes() ?>>
<?php echo $tpinjamandetail->bayartitipan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->bayartitipanauto->Visible) { // bayartitipanauto ?>
	<tr id="r_bayartitipanauto">
		<td><span id="elh_tpinjamandetail_bayartitipanauto"><?php echo $tpinjamandetail->bayartitipanauto->FldCaption() ?></span></td>
		<td data-name="bayartitipanauto"<?php echo $tpinjamandetail->bayartitipanauto->CellAttributes() ?>>
<span id="el_tpinjamandetail_bayartitipanauto">
<span<?php echo $tpinjamandetail->bayartitipanauto->ViewAttributes() ?>>
<?php echo $tpinjamandetail->bayartitipanauto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->totalbayar->Visible) { // totalbayar ?>
	<tr id="r_totalbayar">
		<td><span id="elh_tpinjamandetail_totalbayar"><?php echo $tpinjamandetail->totalbayar->FldCaption() ?></span></td>
		<td data-name="totalbayar"<?php echo $tpinjamandetail->totalbayar->CellAttributes() ?>>
<span id="el_tpinjamandetail_totalbayar">
<span<?php echo $tpinjamandetail->totalbayar->ViewAttributes() ?>>
<?php echo $tpinjamandetail->totalbayar->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->totalbayarauto->Visible) { // totalbayarauto ?>
	<tr id="r_totalbayarauto">
		<td><span id="elh_tpinjamandetail_totalbayarauto"><?php echo $tpinjamandetail->totalbayarauto->FldCaption() ?></span></td>
		<td data-name="totalbayarauto"<?php echo $tpinjamandetail->totalbayarauto->CellAttributes() ?>>
<span id="el_tpinjamandetail_totalbayarauto">
<span<?php echo $tpinjamandetail->totalbayarauto->ViewAttributes() ?>>
<?php echo $tpinjamandetail->totalbayarauto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->pelunasan->Visible) { // pelunasan ?>
	<tr id="r_pelunasan">
		<td><span id="elh_tpinjamandetail_pelunasan"><?php echo $tpinjamandetail->pelunasan->FldCaption() ?></span></td>
		<td data-name="pelunasan"<?php echo $tpinjamandetail->pelunasan->CellAttributes() ?>>
<span id="el_tpinjamandetail_pelunasan">
<span<?php echo $tpinjamandetail->pelunasan->ViewAttributes() ?>>
<?php echo $tpinjamandetail->pelunasan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->pelunasanauto->Visible) { // pelunasanauto ?>
	<tr id="r_pelunasanauto">
		<td><span id="elh_tpinjamandetail_pelunasanauto"><?php echo $tpinjamandetail->pelunasanauto->FldCaption() ?></span></td>
		<td data-name="pelunasanauto"<?php echo $tpinjamandetail->pelunasanauto->CellAttributes() ?>>
<span id="el_tpinjamandetail_pelunasanauto">
<span<?php echo $tpinjamandetail->pelunasanauto->ViewAttributes() ?>>
<?php echo $tpinjamandetail->pelunasanauto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->finalty->Visible) { // finalty ?>
	<tr id="r_finalty">
		<td><span id="elh_tpinjamandetail_finalty"><?php echo $tpinjamandetail->finalty->FldCaption() ?></span></td>
		<td data-name="finalty"<?php echo $tpinjamandetail->finalty->CellAttributes() ?>>
<span id="el_tpinjamandetail_finalty">
<span<?php echo $tpinjamandetail->finalty->ViewAttributes() ?>>
<?php echo $tpinjamandetail->finalty->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->finaltyauto->Visible) { // finaltyauto ?>
	<tr id="r_finaltyauto">
		<td><span id="elh_tpinjamandetail_finaltyauto"><?php echo $tpinjamandetail->finaltyauto->FldCaption() ?></span></td>
		<td data-name="finaltyauto"<?php echo $tpinjamandetail->finaltyauto->CellAttributes() ?>>
<span id="el_tpinjamandetail_finaltyauto">
<span<?php echo $tpinjamandetail->finaltyauto->ViewAttributes() ?>>
<?php echo $tpinjamandetail->finaltyauto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->status->Visible) { // status ?>
	<tr id="r_status">
		<td><span id="elh_tpinjamandetail_status"><?php echo $tpinjamandetail->status->FldCaption() ?></span></td>
		<td data-name="status"<?php echo $tpinjamandetail->status->CellAttributes() ?>>
<span id="el_tpinjamandetail_status">
<span<?php echo $tpinjamandetail->status->ViewAttributes() ?>>
<?php echo $tpinjamandetail->status->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjamandetail->keterangan->Visible) { // keterangan ?>
	<tr id="r_keterangan">
		<td><span id="elh_tpinjamandetail_keterangan"><?php echo $tpinjamandetail->keterangan->FldCaption() ?></span></td>
		<td data-name="keterangan"<?php echo $tpinjamandetail->keterangan->CellAttributes() ?>>
<span id="el_tpinjamandetail_keterangan">
<span<?php echo $tpinjamandetail->keterangan->ViewAttributes() ?>>
<?php echo $tpinjamandetail->keterangan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
ftpinjamandetailview.Init();
</script>
<?php
$tpinjamandetail_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tpinjamandetail_view->Page_Terminate();
?>
