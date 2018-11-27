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

$tjurnaltransaksi_view = NULL; // Initialize page object first

class ctjurnaltransaksi_view extends ctjurnaltransaksi {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tjurnaltransaksi';

	// Page object name
	var $PageObjName = 'tjurnaltransaksi_view';

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
			define("EW_TABLE_NAME", 'tjurnaltransaksi', TRUE);

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
				$sReturnUrl = "tjurnaltransaksilist.php"; // Return to list
			}
			if (@$_GET["nomor"] <> "") {
				$this->nomor->setQueryStringValue($_GET["nomor"]);
				$this->RecKey["nomor"] = $this->nomor->QueryStringValue;
			} elseif (@$_POST["nomor"] <> "") {
				$this->nomor->setFormValue($_POST["nomor"]);
				$this->RecKey["nomor"] = $this->nomor->FormValue;
			} else {
				$sReturnUrl = "tjurnaltransaksilist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "tjurnaltransaksilist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "tjurnaltransaksilist.php"; // Not page request, return to list
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("tjurnaltransaksilist.php"), "", $this->TableVar, TRUE);
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
if (!isset($tjurnaltransaksi_view)) $tjurnaltransaksi_view = new ctjurnaltransaksi_view();

// Page init
$tjurnaltransaksi_view->Page_Init();

// Page main
$tjurnaltransaksi_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tjurnaltransaksi_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = ftjurnaltransaksiview = new ew_Form("ftjurnaltransaksiview", "view");

// Form_CustomValidate event
ftjurnaltransaksiview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftjurnaltransaksiview.ValidateRequired = true;
<?php } else { ?>
ftjurnaltransaksiview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftjurnaltransaksiview.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftjurnaltransaksiview.Lists["x_active"].Options = <?php echo json_encode($tjurnaltransaksi->active->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if (!$tjurnaltransaksi_view->IsModal) { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php $tjurnaltransaksi_view->ExportOptions->Render("body") ?>
<?php
	foreach ($tjurnaltransaksi_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if (!$tjurnaltransaksi_view->IsModal) { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php $tjurnaltransaksi_view->ShowPageHeader(); ?>
<?php
$tjurnaltransaksi_view->ShowMessage();
?>
<form name="ftjurnaltransaksiview" id="ftjurnaltransaksiview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tjurnaltransaksi_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tjurnaltransaksi_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tjurnaltransaksi">
<?php if ($tjurnaltransaksi_view->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($tjurnaltransaksi->tanggal->Visible) { // tanggal ?>
	<tr id="r_tanggal">
		<td><span id="elh_tjurnaltransaksi_tanggal"><?php echo $tjurnaltransaksi->tanggal->FldCaption() ?></span></td>
		<td data-name="tanggal"<?php echo $tjurnaltransaksi->tanggal->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_tanggal">
<span<?php echo $tjurnaltransaksi->tanggal->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->tanggal->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnaltransaksi->periode->Visible) { // periode ?>
	<tr id="r_periode">
		<td><span id="elh_tjurnaltransaksi_periode"><?php echo $tjurnaltransaksi->periode->FldCaption() ?></span></td>
		<td data-name="periode"<?php echo $tjurnaltransaksi->periode->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_periode">
<span<?php echo $tjurnaltransaksi->periode->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->periode->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnaltransaksi->id->Visible) { // id ?>
	<tr id="r_id">
		<td><span id="elh_tjurnaltransaksi_id"><?php echo $tjurnaltransaksi->id->FldCaption() ?></span></td>
		<td data-name="id"<?php echo $tjurnaltransaksi->id->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_id">
<span<?php echo $tjurnaltransaksi->id->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnaltransaksi->nomor->Visible) { // nomor ?>
	<tr id="r_nomor">
		<td><span id="elh_tjurnaltransaksi_nomor"><?php echo $tjurnaltransaksi->nomor->FldCaption() ?></span></td>
		<td data-name="nomor"<?php echo $tjurnaltransaksi->nomor->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_nomor">
<span<?php echo $tjurnaltransaksi->nomor->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->nomor->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnaltransaksi->transaksi->Visible) { // transaksi ?>
	<tr id="r_transaksi">
		<td><span id="elh_tjurnaltransaksi_transaksi"><?php echo $tjurnaltransaksi->transaksi->FldCaption() ?></span></td>
		<td data-name="transaksi"<?php echo $tjurnaltransaksi->transaksi->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_transaksi">
<span<?php echo $tjurnaltransaksi->transaksi->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->transaksi->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnaltransaksi->referensi->Visible) { // referensi ?>
	<tr id="r_referensi">
		<td><span id="elh_tjurnaltransaksi_referensi"><?php echo $tjurnaltransaksi->referensi->FldCaption() ?></span></td>
		<td data-name="referensi"<?php echo $tjurnaltransaksi->referensi->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_referensi">
<span<?php echo $tjurnaltransaksi->referensi->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->referensi->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnaltransaksi->model->Visible) { // model ?>
	<tr id="r_model">
		<td><span id="elh_tjurnaltransaksi_model"><?php echo $tjurnaltransaksi->model->FldCaption() ?></span></td>
		<td data-name="model"<?php echo $tjurnaltransaksi->model->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_model">
<span<?php echo $tjurnaltransaksi->model->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->model->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnaltransaksi->rekening->Visible) { // rekening ?>
	<tr id="r_rekening">
		<td><span id="elh_tjurnaltransaksi_rekening"><?php echo $tjurnaltransaksi->rekening->FldCaption() ?></span></td>
		<td data-name="rekening"<?php echo $tjurnaltransaksi->rekening->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_rekening">
<span<?php echo $tjurnaltransaksi->rekening->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->rekening->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnaltransaksi->debet->Visible) { // debet ?>
	<tr id="r_debet">
		<td><span id="elh_tjurnaltransaksi_debet"><?php echo $tjurnaltransaksi->debet->FldCaption() ?></span></td>
		<td data-name="debet"<?php echo $tjurnaltransaksi->debet->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_debet">
<span<?php echo $tjurnaltransaksi->debet->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->debet->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnaltransaksi->credit->Visible) { // credit ?>
	<tr id="r_credit">
		<td><span id="elh_tjurnaltransaksi_credit"><?php echo $tjurnaltransaksi->credit->FldCaption() ?></span></td>
		<td data-name="credit"<?php echo $tjurnaltransaksi->credit->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_credit">
<span<?php echo $tjurnaltransaksi->credit->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->credit->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnaltransaksi->pembayaran_->Visible) { // pembayaran_ ?>
	<tr id="r_pembayaran_">
		<td><span id="elh_tjurnaltransaksi_pembayaran_"><?php echo $tjurnaltransaksi->pembayaran_->FldCaption() ?></span></td>
		<td data-name="pembayaran_"<?php echo $tjurnaltransaksi->pembayaran_->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_pembayaran_">
<span<?php echo $tjurnaltransaksi->pembayaran_->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->pembayaran_->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnaltransaksi->bunga_->Visible) { // bunga_ ?>
	<tr id="r_bunga_">
		<td><span id="elh_tjurnaltransaksi_bunga_"><?php echo $tjurnaltransaksi->bunga_->FldCaption() ?></span></td>
		<td data-name="bunga_"<?php echo $tjurnaltransaksi->bunga_->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_bunga_">
<span<?php echo $tjurnaltransaksi->bunga_->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->bunga_->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnaltransaksi->denda_->Visible) { // denda_ ?>
	<tr id="r_denda_">
		<td><span id="elh_tjurnaltransaksi_denda_"><?php echo $tjurnaltransaksi->denda_->FldCaption() ?></span></td>
		<td data-name="denda_"<?php echo $tjurnaltransaksi->denda_->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_denda_">
<span<?php echo $tjurnaltransaksi->denda_->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->denda_->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnaltransaksi->titipan_->Visible) { // titipan_ ?>
	<tr id="r_titipan_">
		<td><span id="elh_tjurnaltransaksi_titipan_"><?php echo $tjurnaltransaksi->titipan_->FldCaption() ?></span></td>
		<td data-name="titipan_"<?php echo $tjurnaltransaksi->titipan_->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_titipan_">
<span<?php echo $tjurnaltransaksi->titipan_->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->titipan_->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnaltransaksi->administrasi_->Visible) { // administrasi_ ?>
	<tr id="r_administrasi_">
		<td><span id="elh_tjurnaltransaksi_administrasi_"><?php echo $tjurnaltransaksi->administrasi_->FldCaption() ?></span></td>
		<td data-name="administrasi_"<?php echo $tjurnaltransaksi->administrasi_->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_administrasi_">
<span<?php echo $tjurnaltransaksi->administrasi_->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->administrasi_->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnaltransaksi->modal_->Visible) { // modal_ ?>
	<tr id="r_modal_">
		<td><span id="elh_tjurnaltransaksi_modal_"><?php echo $tjurnaltransaksi->modal_->FldCaption() ?></span></td>
		<td data-name="modal_"<?php echo $tjurnaltransaksi->modal_->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_modal_">
<span<?php echo $tjurnaltransaksi->modal_->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->modal_->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnaltransaksi->pinjaman_->Visible) { // pinjaman_ ?>
	<tr id="r_pinjaman_">
		<td><span id="elh_tjurnaltransaksi_pinjaman_"><?php echo $tjurnaltransaksi->pinjaman_->FldCaption() ?></span></td>
		<td data-name="pinjaman_"<?php echo $tjurnaltransaksi->pinjaman_->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_pinjaman_">
<span<?php echo $tjurnaltransaksi->pinjaman_->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->pinjaman_->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnaltransaksi->biaya_->Visible) { // biaya_ ?>
	<tr id="r_biaya_">
		<td><span id="elh_tjurnaltransaksi_biaya_"><?php echo $tjurnaltransaksi->biaya_->FldCaption() ?></span></td>
		<td data-name="biaya_"<?php echo $tjurnaltransaksi->biaya_->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_biaya_">
<span<?php echo $tjurnaltransaksi->biaya_->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->biaya_->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnaltransaksi->kantor->Visible) { // kantor ?>
	<tr id="r_kantor">
		<td><span id="elh_tjurnaltransaksi_kantor"><?php echo $tjurnaltransaksi->kantor->FldCaption() ?></span></td>
		<td data-name="kantor"<?php echo $tjurnaltransaksi->kantor->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_kantor">
<span<?php echo $tjurnaltransaksi->kantor->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->kantor->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnaltransaksi->keterangan->Visible) { // keterangan ?>
	<tr id="r_keterangan">
		<td><span id="elh_tjurnaltransaksi_keterangan"><?php echo $tjurnaltransaksi->keterangan->FldCaption() ?></span></td>
		<td data-name="keterangan"<?php echo $tjurnaltransaksi->keterangan->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_keterangan">
<span<?php echo $tjurnaltransaksi->keterangan->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->keterangan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnaltransaksi->active->Visible) { // active ?>
	<tr id="r_active">
		<td><span id="elh_tjurnaltransaksi_active"><?php echo $tjurnaltransaksi->active->FldCaption() ?></span></td>
		<td data-name="active"<?php echo $tjurnaltransaksi->active->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_active">
<span<?php echo $tjurnaltransaksi->active->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->active->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnaltransaksi->ip->Visible) { // ip ?>
	<tr id="r_ip">
		<td><span id="elh_tjurnaltransaksi_ip"><?php echo $tjurnaltransaksi->ip->FldCaption() ?></span></td>
		<td data-name="ip"<?php echo $tjurnaltransaksi->ip->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_ip">
<span<?php echo $tjurnaltransaksi->ip->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->ip->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnaltransaksi->status->Visible) { // status ?>
	<tr id="r_status">
		<td><span id="elh_tjurnaltransaksi_status"><?php echo $tjurnaltransaksi->status->FldCaption() ?></span></td>
		<td data-name="status"<?php echo $tjurnaltransaksi->status->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_status">
<span<?php echo $tjurnaltransaksi->status->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->status->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnaltransaksi->user->Visible) { // user ?>
	<tr id="r_user">
		<td><span id="elh_tjurnaltransaksi_user"><?php echo $tjurnaltransaksi->user->FldCaption() ?></span></td>
		<td data-name="user"<?php echo $tjurnaltransaksi->user->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_user">
<span<?php echo $tjurnaltransaksi->user->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->user->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnaltransaksi->created->Visible) { // created ?>
	<tr id="r_created">
		<td><span id="elh_tjurnaltransaksi_created"><?php echo $tjurnaltransaksi->created->FldCaption() ?></span></td>
		<td data-name="created"<?php echo $tjurnaltransaksi->created->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_created">
<span<?php echo $tjurnaltransaksi->created->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->created->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tjurnaltransaksi->modified->Visible) { // modified ?>
	<tr id="r_modified">
		<td><span id="elh_tjurnaltransaksi_modified"><?php echo $tjurnaltransaksi->modified->FldCaption() ?></span></td>
		<td data-name="modified"<?php echo $tjurnaltransaksi->modified->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_modified">
<span<?php echo $tjurnaltransaksi->modified->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->modified->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
ftjurnaltransaksiview.Init();
</script>
<?php
$tjurnaltransaksi_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tjurnaltransaksi_view->Page_Terminate();
?>
