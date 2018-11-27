<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "tjurnalsaldoinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$tjurnalsaldo_view = NULL; // Initialize page object first

class ctjurnalsaldo_view extends ctjurnalsaldo {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tjurnalsaldo';

	// Page object name
	var $PageObjName = 'tjurnalsaldo_view';

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

		// Table object (tjurnalsaldo)
		if (!isset($GLOBALS["tjurnalsaldo"]) || get_class($GLOBALS["tjurnalsaldo"]) == "ctjurnalsaldo") {
			$GLOBALS["tjurnalsaldo"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tjurnalsaldo"];
		}
		$KeyUrl = "";
		if (@$_GET["id"] <> "") {
			$this->RecKey["id"] = $_GET["id"];
			$KeyUrl .= "&amp;id=" . urlencode($this->RecKey["id"]);
		}
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
			define("EW_TABLE_NAME", 'tjurnalsaldo', TRUE);

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
		$this->model->SetVisibility();
		$this->rekening->SetVisibility();
		$this->debet->SetVisibility();
		$this->credit->SetVisibility();
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
		global $EW_EXPORT, $tjurnalsaldo;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($tjurnalsaldo);
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
				$sReturnUrl = "tjurnalsaldolist.php"; // Return to list
			}
			if (@$_GET["nomor"] <> "") {
				$this->nomor->setQueryStringValue($_GET["nomor"]);
				$this->RecKey["nomor"] = $this->nomor->QueryStringValue;
			} elseif (@$_POST["nomor"] <> "") {
				$this->nomor->setFormValue($_POST["nomor"]);
				$this->RecKey["nomor"] = $this->nomor->FormValue;
			} else {
				$sReturnUrl = "tjurnalsaldolist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "tjurnalsaldolist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "tjurnalsaldolist.php"; // Not page request, return to list
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
		$this->model->setDbValue($rs->fields('model'));
		$this->rekening->setDbValue($rs->fields('rekening'));
		$this->debet->setDbValue($rs->fields('debet'));
		$this->credit->setDbValue($rs->fields('credit'));
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
		$this->kantor->DbValue = $row['kantor'];
		$this->keterangan->DbValue = $row['keterangan'];
		$this->active->DbValue = $row['active'];
		$this->ip->DbValue = $row['ip'];
		$this->status->DbValue = $row['status'];
		$this->user->DbValue = $row['user'];
		$this->created->DbValue = $row['created'];
		$this->modified->DbValue = $row['modified'];
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
		if ($this->debet->FormValue == $this->debet->CurrentValue && is_numeric(ew_StrToFloat($this->debet->CurrentValue)))
			$this->debet->CurrentValue = ew_StrToFloat($this->debet->CurrentValue);

		// Convert decimal values if posted back
		if ($this->credit->FormValue == $this->credit->CurrentValue && is_numeric(ew_StrToFloat($this->credit->CurrentValue)))
			$this->credit->CurrentValue = ew_StrToFloat($this->credit->CurrentValue);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("tjurnalsaldolist.php"), "", $this->TableVar, TRUE);
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
if (!isset($tjurnalsaldo_view)) $tjurnalsaldo_view = new ctjurnalsaldo_view();

// Page init
$tjurnalsaldo_view->Page_Init();

// Page main
$tjurnalsaldo_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tjurnalsaldo_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = ftjurnalsaldoview = new ew_Form("ftjurnalsaldoview", "view");

// Form_CustomValidate event
ftjurnalsaldoview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftjurnalsaldoview.ValidateRequired = true;
<?php } else { ?>
ftjurnalsaldoview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftjurnalsaldoview.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftjurnalsaldoview.Lists["x_active"].Options = <?php echo json_encode($tjurnalsaldo->active->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if (!$tjurnalsaldo_view->IsModal) { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php $tjurnalsaldo_view->ExportOptions->Render("body") ?>
<?php
	foreach ($tjurnalsaldo_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if (!$tjurnalsaldo_view->IsModal) { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php $tjurnalsaldo_view->ShowPageHeader(); ?>
<?php
$tjurnalsaldo_view->ShowMessage();
?>
<form name="ftjurnalsaldoview" id="ftjurnalsaldoview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tjurnalsaldo_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tjurnalsaldo_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tjurnalsaldo">
<?php if ($tjurnalsaldo_view->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($tjurnalsaldo->tanggal->Visible) { // tanggal ?>
	<tr id="r_tanggal">
		<td><span id="elh_tjurnalsaldo_tanggal"><?php echo $tjurnalsaldo->tanggal->FldCaption() ?></span></td>
		<td data-name="tanggal"<?php echo $tjurnalsaldo->tanggal->CellAttributes() ?>>
<span id="el_tjurnalsaldo_tanggal">
<span<?php echo $tjurnalsaldo->tanggal->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->tanggal->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnalsaldo->periode->Visible) { // periode ?>
	<tr id="r_periode">
		<td><span id="elh_tjurnalsaldo_periode"><?php echo $tjurnalsaldo->periode->FldCaption() ?></span></td>
		<td data-name="periode"<?php echo $tjurnalsaldo->periode->CellAttributes() ?>>
<span id="el_tjurnalsaldo_periode">
<span<?php echo $tjurnalsaldo->periode->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->periode->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnalsaldo->id->Visible) { // id ?>
	<tr id="r_id">
		<td><span id="elh_tjurnalsaldo_id"><?php echo $tjurnalsaldo->id->FldCaption() ?></span></td>
		<td data-name="id"<?php echo $tjurnalsaldo->id->CellAttributes() ?>>
<span id="el_tjurnalsaldo_id">
<span<?php echo $tjurnalsaldo->id->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnalsaldo->nomor->Visible) { // nomor ?>
	<tr id="r_nomor">
		<td><span id="elh_tjurnalsaldo_nomor"><?php echo $tjurnalsaldo->nomor->FldCaption() ?></span></td>
		<td data-name="nomor"<?php echo $tjurnalsaldo->nomor->CellAttributes() ?>>
<span id="el_tjurnalsaldo_nomor">
<span<?php echo $tjurnalsaldo->nomor->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->nomor->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnalsaldo->transaksi->Visible) { // transaksi ?>
	<tr id="r_transaksi">
		<td><span id="elh_tjurnalsaldo_transaksi"><?php echo $tjurnalsaldo->transaksi->FldCaption() ?></span></td>
		<td data-name="transaksi"<?php echo $tjurnalsaldo->transaksi->CellAttributes() ?>>
<span id="el_tjurnalsaldo_transaksi">
<span<?php echo $tjurnalsaldo->transaksi->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->transaksi->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnalsaldo->referensi->Visible) { // referensi ?>
	<tr id="r_referensi">
		<td><span id="elh_tjurnalsaldo_referensi"><?php echo $tjurnalsaldo->referensi->FldCaption() ?></span></td>
		<td data-name="referensi"<?php echo $tjurnalsaldo->referensi->CellAttributes() ?>>
<span id="el_tjurnalsaldo_referensi">
<span<?php echo $tjurnalsaldo->referensi->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->referensi->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnalsaldo->model->Visible) { // model ?>
	<tr id="r_model">
		<td><span id="elh_tjurnalsaldo_model"><?php echo $tjurnalsaldo->model->FldCaption() ?></span></td>
		<td data-name="model"<?php echo $tjurnalsaldo->model->CellAttributes() ?>>
<span id="el_tjurnalsaldo_model">
<span<?php echo $tjurnalsaldo->model->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->model->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnalsaldo->rekening->Visible) { // rekening ?>
	<tr id="r_rekening">
		<td><span id="elh_tjurnalsaldo_rekening"><?php echo $tjurnalsaldo->rekening->FldCaption() ?></span></td>
		<td data-name="rekening"<?php echo $tjurnalsaldo->rekening->CellAttributes() ?>>
<span id="el_tjurnalsaldo_rekening">
<span<?php echo $tjurnalsaldo->rekening->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->rekening->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnalsaldo->debet->Visible) { // debet ?>
	<tr id="r_debet">
		<td><span id="elh_tjurnalsaldo_debet"><?php echo $tjurnalsaldo->debet->FldCaption() ?></span></td>
		<td data-name="debet"<?php echo $tjurnalsaldo->debet->CellAttributes() ?>>
<span id="el_tjurnalsaldo_debet">
<span<?php echo $tjurnalsaldo->debet->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->debet->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnalsaldo->credit->Visible) { // credit ?>
	<tr id="r_credit">
		<td><span id="elh_tjurnalsaldo_credit"><?php echo $tjurnalsaldo->credit->FldCaption() ?></span></td>
		<td data-name="credit"<?php echo $tjurnalsaldo->credit->CellAttributes() ?>>
<span id="el_tjurnalsaldo_credit">
<span<?php echo $tjurnalsaldo->credit->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->credit->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnalsaldo->kantor->Visible) { // kantor ?>
	<tr id="r_kantor">
		<td><span id="elh_tjurnalsaldo_kantor"><?php echo $tjurnalsaldo->kantor->FldCaption() ?></span></td>
		<td data-name="kantor"<?php echo $tjurnalsaldo->kantor->CellAttributes() ?>>
<span id="el_tjurnalsaldo_kantor">
<span<?php echo $tjurnalsaldo->kantor->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->kantor->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnalsaldo->keterangan->Visible) { // keterangan ?>
	<tr id="r_keterangan">
		<td><span id="elh_tjurnalsaldo_keterangan"><?php echo $tjurnalsaldo->keterangan->FldCaption() ?></span></td>
		<td data-name="keterangan"<?php echo $tjurnalsaldo->keterangan->CellAttributes() ?>>
<span id="el_tjurnalsaldo_keterangan">
<span<?php echo $tjurnalsaldo->keterangan->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->keterangan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnalsaldo->active->Visible) { // active ?>
	<tr id="r_active">
		<td><span id="elh_tjurnalsaldo_active"><?php echo $tjurnalsaldo->active->FldCaption() ?></span></td>
		<td data-name="active"<?php echo $tjurnalsaldo->active->CellAttributes() ?>>
<span id="el_tjurnalsaldo_active">
<span<?php echo $tjurnalsaldo->active->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->active->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnalsaldo->ip->Visible) { // ip ?>
	<tr id="r_ip">
		<td><span id="elh_tjurnalsaldo_ip"><?php echo $tjurnalsaldo->ip->FldCaption() ?></span></td>
		<td data-name="ip"<?php echo $tjurnalsaldo->ip->CellAttributes() ?>>
<span id="el_tjurnalsaldo_ip">
<span<?php echo $tjurnalsaldo->ip->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->ip->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnalsaldo->status->Visible) { // status ?>
	<tr id="r_status">
		<td><span id="elh_tjurnalsaldo_status"><?php echo $tjurnalsaldo->status->FldCaption() ?></span></td>
		<td data-name="status"<?php echo $tjurnalsaldo->status->CellAttributes() ?>>
<span id="el_tjurnalsaldo_status">
<span<?php echo $tjurnalsaldo->status->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->status->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnalsaldo->user->Visible) { // user ?>
	<tr id="r_user">
		<td><span id="elh_tjurnalsaldo_user"><?php echo $tjurnalsaldo->user->FldCaption() ?></span></td>
		<td data-name="user"<?php echo $tjurnalsaldo->user->CellAttributes() ?>>
<span id="el_tjurnalsaldo_user">
<span<?php echo $tjurnalsaldo->user->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->user->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnalsaldo->created->Visible) { // created ?>
	<tr id="r_created">
		<td><span id="elh_tjurnalsaldo_created"><?php echo $tjurnalsaldo->created->FldCaption() ?></span></td>
		<td data-name="created"<?php echo $tjurnalsaldo->created->CellAttributes() ?>>
<span id="el_tjurnalsaldo_created">
<span<?php echo $tjurnalsaldo->created->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->created->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnalsaldo->modified->Visible) { // modified ?>
	<tr id="r_modified">
		<td><span id="elh_tjurnalsaldo_modified"><?php echo $tjurnalsaldo->modified->FldCaption() ?></span></td>
		<td data-name="modified"<?php echo $tjurnalsaldo->modified->CellAttributes() ?>>
<span id="el_tjurnalsaldo_modified">
<span<?php echo $tjurnalsaldo->modified->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->modified->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
ftjurnalsaldoview.Init();
</script>
<?php
$tjurnalsaldo_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tjurnalsaldo_view->Page_Terminate();
?>
