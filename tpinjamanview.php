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

$tpinjaman_view = NULL; // Initialize page object first

class ctpinjaman_view extends ctpinjaman {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tpinjaman';

	// Page object name
	var $PageObjName = 'tpinjaman_view';

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
		$KeyUrl = "";
		if (@$_GET["id"] <> "") {
			$this->RecKey["id"] = $_GET["id"];
			$KeyUrl .= "&amp;id=" . urlencode($this->RecKey["id"]);
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
			define("EW_TABLE_NAME", 'tpinjaman', TRUE);

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
				$sReturnUrl = "tpinjamanlist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "tpinjamanlist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "tpinjamanlist.php"; // Not page request, return to list
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("tpinjamanlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($tpinjaman_view)) $tpinjaman_view = new ctpinjaman_view();

// Page init
$tpinjaman_view->Page_Init();

// Page main
$tpinjaman_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tpinjaman_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = ftpinjamanview = new ew_Form("ftpinjamanview", "view");

// Form_CustomValidate event
ftpinjamanview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftpinjamanview.ValidateRequired = true;
<?php } else { ?>
ftpinjamanview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftpinjamanview.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftpinjamanview.Lists["x_active"].Options = <?php echo json_encode($tpinjaman->active->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if (!$tpinjaman_view->IsModal) { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php $tpinjaman_view->ExportOptions->Render("body") ?>
<?php
	foreach ($tpinjaman_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if (!$tpinjaman_view->IsModal) { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php $tpinjaman_view->ShowPageHeader(); ?>
<?php
$tpinjaman_view->ShowMessage();
?>
<form name="ftpinjamanview" id="ftpinjamanview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tpinjaman_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tpinjaman_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tpinjaman">
<?php if ($tpinjaman_view->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($tpinjaman->tanggal->Visible) { // tanggal ?>
	<tr id="r_tanggal">
		<td><span id="elh_tpinjaman_tanggal"><?php echo $tpinjaman->tanggal->FldCaption() ?></span></td>
		<td data-name="tanggal"<?php echo $tpinjaman->tanggal->CellAttributes() ?>>
<span id="el_tpinjaman_tanggal">
<span<?php echo $tpinjaman->tanggal->ViewAttributes() ?>>
<?php echo $tpinjaman->tanggal->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->periode->Visible) { // periode ?>
	<tr id="r_periode">
		<td><span id="elh_tpinjaman_periode"><?php echo $tpinjaman->periode->FldCaption() ?></span></td>
		<td data-name="periode"<?php echo $tpinjaman->periode->CellAttributes() ?>>
<span id="el_tpinjaman_periode">
<span<?php echo $tpinjaman->periode->ViewAttributes() ?>>
<?php echo $tpinjaman->periode->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->id->Visible) { // id ?>
	<tr id="r_id">
		<td><span id="elh_tpinjaman_id"><?php echo $tpinjaman->id->FldCaption() ?></span></td>
		<td data-name="id"<?php echo $tpinjaman->id->CellAttributes() ?>>
<span id="el_tpinjaman_id">
<span<?php echo $tpinjaman->id->ViewAttributes() ?>>
<?php echo $tpinjaman->id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->transaksi->Visible) { // transaksi ?>
	<tr id="r_transaksi">
		<td><span id="elh_tpinjaman_transaksi"><?php echo $tpinjaman->transaksi->FldCaption() ?></span></td>
		<td data-name="transaksi"<?php echo $tpinjaman->transaksi->CellAttributes() ?>>
<span id="el_tpinjaman_transaksi">
<span<?php echo $tpinjaman->transaksi->ViewAttributes() ?>>
<?php echo $tpinjaman->transaksi->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->referensi->Visible) { // referensi ?>
	<tr id="r_referensi">
		<td><span id="elh_tpinjaman_referensi"><?php echo $tpinjaman->referensi->FldCaption() ?></span></td>
		<td data-name="referensi"<?php echo $tpinjaman->referensi->CellAttributes() ?>>
<span id="el_tpinjaman_referensi">
<span<?php echo $tpinjaman->referensi->ViewAttributes() ?>>
<?php echo $tpinjaman->referensi->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->anggota->Visible) { // anggota ?>
	<tr id="r_anggota">
		<td><span id="elh_tpinjaman_anggota"><?php echo $tpinjaman->anggota->FldCaption() ?></span></td>
		<td data-name="anggota"<?php echo $tpinjaman->anggota->CellAttributes() ?>>
<span id="el_tpinjaman_anggota">
<span<?php echo $tpinjaman->anggota->ViewAttributes() ?>>
<?php echo $tpinjaman->anggota->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->namaanggota->Visible) { // namaanggota ?>
	<tr id="r_namaanggota">
		<td><span id="elh_tpinjaman_namaanggota"><?php echo $tpinjaman->namaanggota->FldCaption() ?></span></td>
		<td data-name="namaanggota"<?php echo $tpinjaman->namaanggota->CellAttributes() ?>>
<span id="el_tpinjaman_namaanggota">
<span<?php echo $tpinjaman->namaanggota->ViewAttributes() ?>>
<?php echo $tpinjaman->namaanggota->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->alamat->Visible) { // alamat ?>
	<tr id="r_alamat">
		<td><span id="elh_tpinjaman_alamat"><?php echo $tpinjaman->alamat->FldCaption() ?></span></td>
		<td data-name="alamat"<?php echo $tpinjaman->alamat->CellAttributes() ?>>
<span id="el_tpinjaman_alamat">
<span<?php echo $tpinjaman->alamat->ViewAttributes() ?>>
<?php echo $tpinjaman->alamat->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->pekerjaan->Visible) { // pekerjaan ?>
	<tr id="r_pekerjaan">
		<td><span id="elh_tpinjaman_pekerjaan"><?php echo $tpinjaman->pekerjaan->FldCaption() ?></span></td>
		<td data-name="pekerjaan"<?php echo $tpinjaman->pekerjaan->CellAttributes() ?>>
<span id="el_tpinjaman_pekerjaan">
<span<?php echo $tpinjaman->pekerjaan->ViewAttributes() ?>>
<?php echo $tpinjaman->pekerjaan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->telepon->Visible) { // telepon ?>
	<tr id="r_telepon">
		<td><span id="elh_tpinjaman_telepon"><?php echo $tpinjaman->telepon->FldCaption() ?></span></td>
		<td data-name="telepon"<?php echo $tpinjaman->telepon->CellAttributes() ?>>
<span id="el_tpinjaman_telepon">
<span<?php echo $tpinjaman->telepon->ViewAttributes() ?>>
<?php echo $tpinjaman->telepon->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->hp->Visible) { // hp ?>
	<tr id="r_hp">
		<td><span id="elh_tpinjaman_hp"><?php echo $tpinjaman->hp->FldCaption() ?></span></td>
		<td data-name="hp"<?php echo $tpinjaman->hp->CellAttributes() ?>>
<span id="el_tpinjaman_hp">
<span<?php echo $tpinjaman->hp->ViewAttributes() ?>>
<?php echo $tpinjaman->hp->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->fax->Visible) { // fax ?>
	<tr id="r_fax">
		<td><span id="elh_tpinjaman_fax"><?php echo $tpinjaman->fax->FldCaption() ?></span></td>
		<td data-name="fax"<?php echo $tpinjaman->fax->CellAttributes() ?>>
<span id="el_tpinjaman_fax">
<span<?php echo $tpinjaman->fax->ViewAttributes() ?>>
<?php echo $tpinjaman->fax->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->_email->Visible) { // email ?>
	<tr id="r__email">
		<td><span id="elh_tpinjaman__email"><?php echo $tpinjaman->_email->FldCaption() ?></span></td>
		<td data-name="_email"<?php echo $tpinjaman->_email->CellAttributes() ?>>
<span id="el_tpinjaman__email">
<span<?php echo $tpinjaman->_email->ViewAttributes() ?>>
<?php echo $tpinjaman->_email->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->website->Visible) { // website ?>
	<tr id="r_website">
		<td><span id="elh_tpinjaman_website"><?php echo $tpinjaman->website->FldCaption() ?></span></td>
		<td data-name="website"<?php echo $tpinjaman->website->CellAttributes() ?>>
<span id="el_tpinjaman_website">
<span<?php echo $tpinjaman->website->ViewAttributes() ?>>
<?php echo $tpinjaman->website->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->jenisanggota->Visible) { // jenisanggota ?>
	<tr id="r_jenisanggota">
		<td><span id="elh_tpinjaman_jenisanggota"><?php echo $tpinjaman->jenisanggota->FldCaption() ?></span></td>
		<td data-name="jenisanggota"<?php echo $tpinjaman->jenisanggota->CellAttributes() ?>>
<span id="el_tpinjaman_jenisanggota">
<span<?php echo $tpinjaman->jenisanggota->ViewAttributes() ?>>
<?php echo $tpinjaman->jenisanggota->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->model->Visible) { // model ?>
	<tr id="r_model">
		<td><span id="elh_tpinjaman_model"><?php echo $tpinjaman->model->FldCaption() ?></span></td>
		<td data-name="model"<?php echo $tpinjaman->model->CellAttributes() ?>>
<span id="el_tpinjaman_model">
<span<?php echo $tpinjaman->model->ViewAttributes() ?>>
<?php echo $tpinjaman->model->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->jenispinjaman->Visible) { // jenispinjaman ?>
	<tr id="r_jenispinjaman">
		<td><span id="elh_tpinjaman_jenispinjaman"><?php echo $tpinjaman->jenispinjaman->FldCaption() ?></span></td>
		<td data-name="jenispinjaman"<?php echo $tpinjaman->jenispinjaman->CellAttributes() ?>>
<span id="el_tpinjaman_jenispinjaman">
<span<?php echo $tpinjaman->jenispinjaman->ViewAttributes() ?>>
<?php echo $tpinjaman->jenispinjaman->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->jenisbunga->Visible) { // jenisbunga ?>
	<tr id="r_jenisbunga">
		<td><span id="elh_tpinjaman_jenisbunga"><?php echo $tpinjaman->jenisbunga->FldCaption() ?></span></td>
		<td data-name="jenisbunga"<?php echo $tpinjaman->jenisbunga->CellAttributes() ?>>
<span id="el_tpinjaman_jenisbunga">
<span<?php echo $tpinjaman->jenisbunga->ViewAttributes() ?>>
<?php echo $tpinjaman->jenisbunga->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->angsuran->Visible) { // angsuran ?>
	<tr id="r_angsuran">
		<td><span id="elh_tpinjaman_angsuran"><?php echo $tpinjaman->angsuran->FldCaption() ?></span></td>
		<td data-name="angsuran"<?php echo $tpinjaman->angsuran->CellAttributes() ?>>
<span id="el_tpinjaman_angsuran">
<span<?php echo $tpinjaman->angsuran->ViewAttributes() ?>>
<?php echo $tpinjaman->angsuran->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->masaangsuran->Visible) { // masaangsuran ?>
	<tr id="r_masaangsuran">
		<td><span id="elh_tpinjaman_masaangsuran"><?php echo $tpinjaman->masaangsuran->FldCaption() ?></span></td>
		<td data-name="masaangsuran"<?php echo $tpinjaman->masaangsuran->CellAttributes() ?>>
<span id="el_tpinjaman_masaangsuran">
<span<?php echo $tpinjaman->masaangsuran->ViewAttributes() ?>>
<?php echo $tpinjaman->masaangsuran->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->jatuhtempo->Visible) { // jatuhtempo ?>
	<tr id="r_jatuhtempo">
		<td><span id="elh_tpinjaman_jatuhtempo"><?php echo $tpinjaman->jatuhtempo->FldCaption() ?></span></td>
		<td data-name="jatuhtempo"<?php echo $tpinjaman->jatuhtempo->CellAttributes() ?>>
<span id="el_tpinjaman_jatuhtempo">
<span<?php echo $tpinjaman->jatuhtempo->ViewAttributes() ?>>
<?php echo $tpinjaman->jatuhtempo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->dispensasidenda->Visible) { // dispensasidenda ?>
	<tr id="r_dispensasidenda">
		<td><span id="elh_tpinjaman_dispensasidenda"><?php echo $tpinjaman->dispensasidenda->FldCaption() ?></span></td>
		<td data-name="dispensasidenda"<?php echo $tpinjaman->dispensasidenda->CellAttributes() ?>>
<span id="el_tpinjaman_dispensasidenda">
<span<?php echo $tpinjaman->dispensasidenda->ViewAttributes() ?>>
<?php echo $tpinjaman->dispensasidenda->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->agunan->Visible) { // agunan ?>
	<tr id="r_agunan">
		<td><span id="elh_tpinjaman_agunan"><?php echo $tpinjaman->agunan->FldCaption() ?></span></td>
		<td data-name="agunan"<?php echo $tpinjaman->agunan->CellAttributes() ?>>
<span id="el_tpinjaman_agunan">
<span<?php echo $tpinjaman->agunan->ViewAttributes() ?>>
<?php echo $tpinjaman->agunan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->dataagunan1->Visible) { // dataagunan1 ?>
	<tr id="r_dataagunan1">
		<td><span id="elh_tpinjaman_dataagunan1"><?php echo $tpinjaman->dataagunan1->FldCaption() ?></span></td>
		<td data-name="dataagunan1"<?php echo $tpinjaman->dataagunan1->CellAttributes() ?>>
<span id="el_tpinjaman_dataagunan1">
<span<?php echo $tpinjaman->dataagunan1->ViewAttributes() ?>>
<?php echo $tpinjaman->dataagunan1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->dataagunan2->Visible) { // dataagunan2 ?>
	<tr id="r_dataagunan2">
		<td><span id="elh_tpinjaman_dataagunan2"><?php echo $tpinjaman->dataagunan2->FldCaption() ?></span></td>
		<td data-name="dataagunan2"<?php echo $tpinjaman->dataagunan2->CellAttributes() ?>>
<span id="el_tpinjaman_dataagunan2">
<span<?php echo $tpinjaman->dataagunan2->ViewAttributes() ?>>
<?php echo $tpinjaman->dataagunan2->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->dataagunan3->Visible) { // dataagunan3 ?>
	<tr id="r_dataagunan3">
		<td><span id="elh_tpinjaman_dataagunan3"><?php echo $tpinjaman->dataagunan3->FldCaption() ?></span></td>
		<td data-name="dataagunan3"<?php echo $tpinjaman->dataagunan3->CellAttributes() ?>>
<span id="el_tpinjaman_dataagunan3">
<span<?php echo $tpinjaman->dataagunan3->ViewAttributes() ?>>
<?php echo $tpinjaman->dataagunan3->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->dataagunan4->Visible) { // dataagunan4 ?>
	<tr id="r_dataagunan4">
		<td><span id="elh_tpinjaman_dataagunan4"><?php echo $tpinjaman->dataagunan4->FldCaption() ?></span></td>
		<td data-name="dataagunan4"<?php echo $tpinjaman->dataagunan4->CellAttributes() ?>>
<span id="el_tpinjaman_dataagunan4">
<span<?php echo $tpinjaman->dataagunan4->ViewAttributes() ?>>
<?php echo $tpinjaman->dataagunan4->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->dataagunan5->Visible) { // dataagunan5 ?>
	<tr id="r_dataagunan5">
		<td><span id="elh_tpinjaman_dataagunan5"><?php echo $tpinjaman->dataagunan5->FldCaption() ?></span></td>
		<td data-name="dataagunan5"<?php echo $tpinjaman->dataagunan5->CellAttributes() ?>>
<span id="el_tpinjaman_dataagunan5">
<span<?php echo $tpinjaman->dataagunan5->ViewAttributes() ?>>
<?php echo $tpinjaman->dataagunan5->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->saldobekusimpanan->Visible) { // saldobekusimpanan ?>
	<tr id="r_saldobekusimpanan">
		<td><span id="elh_tpinjaman_saldobekusimpanan"><?php echo $tpinjaman->saldobekusimpanan->FldCaption() ?></span></td>
		<td data-name="saldobekusimpanan"<?php echo $tpinjaman->saldobekusimpanan->CellAttributes() ?>>
<span id="el_tpinjaman_saldobekusimpanan">
<span<?php echo $tpinjaman->saldobekusimpanan->ViewAttributes() ?>>
<?php echo $tpinjaman->saldobekusimpanan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->saldobekuminimal->Visible) { // saldobekuminimal ?>
	<tr id="r_saldobekuminimal">
		<td><span id="elh_tpinjaman_saldobekuminimal"><?php echo $tpinjaman->saldobekuminimal->FldCaption() ?></span></td>
		<td data-name="saldobekuminimal"<?php echo $tpinjaman->saldobekuminimal->CellAttributes() ?>>
<span id="el_tpinjaman_saldobekuminimal">
<span<?php echo $tpinjaman->saldobekuminimal->ViewAttributes() ?>>
<?php echo $tpinjaman->saldobekuminimal->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->plafond->Visible) { // plafond ?>
	<tr id="r_plafond">
		<td><span id="elh_tpinjaman_plafond"><?php echo $tpinjaman->plafond->FldCaption() ?></span></td>
		<td data-name="plafond"<?php echo $tpinjaman->plafond->CellAttributes() ?>>
<span id="el_tpinjaman_plafond">
<span<?php echo $tpinjaman->plafond->ViewAttributes() ?>>
<?php echo $tpinjaman->plafond->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->bunga->Visible) { // bunga ?>
	<tr id="r_bunga">
		<td><span id="elh_tpinjaman_bunga"><?php echo $tpinjaman->bunga->FldCaption() ?></span></td>
		<td data-name="bunga"<?php echo $tpinjaman->bunga->CellAttributes() ?>>
<span id="el_tpinjaman_bunga">
<span<?php echo $tpinjaman->bunga->ViewAttributes() ?>>
<?php echo $tpinjaman->bunga->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->bungapersen->Visible) { // bungapersen ?>
	<tr id="r_bungapersen">
		<td><span id="elh_tpinjaman_bungapersen"><?php echo $tpinjaman->bungapersen->FldCaption() ?></span></td>
		<td data-name="bungapersen"<?php echo $tpinjaman->bungapersen->CellAttributes() ?>>
<span id="el_tpinjaman_bungapersen">
<span<?php echo $tpinjaman->bungapersen->ViewAttributes() ?>>
<?php echo $tpinjaman->bungapersen->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->administrasi->Visible) { // administrasi ?>
	<tr id="r_administrasi">
		<td><span id="elh_tpinjaman_administrasi"><?php echo $tpinjaman->administrasi->FldCaption() ?></span></td>
		<td data-name="administrasi"<?php echo $tpinjaman->administrasi->CellAttributes() ?>>
<span id="el_tpinjaman_administrasi">
<span<?php echo $tpinjaman->administrasi->ViewAttributes() ?>>
<?php echo $tpinjaman->administrasi->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->administrasipersen->Visible) { // administrasipersen ?>
	<tr id="r_administrasipersen">
		<td><span id="elh_tpinjaman_administrasipersen"><?php echo $tpinjaman->administrasipersen->FldCaption() ?></span></td>
		<td data-name="administrasipersen"<?php echo $tpinjaman->administrasipersen->CellAttributes() ?>>
<span id="el_tpinjaman_administrasipersen">
<span<?php echo $tpinjaman->administrasipersen->ViewAttributes() ?>>
<?php echo $tpinjaman->administrasipersen->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->asuransi->Visible) { // asuransi ?>
	<tr id="r_asuransi">
		<td><span id="elh_tpinjaman_asuransi"><?php echo $tpinjaman->asuransi->FldCaption() ?></span></td>
		<td data-name="asuransi"<?php echo $tpinjaman->asuransi->CellAttributes() ?>>
<span id="el_tpinjaman_asuransi">
<span<?php echo $tpinjaman->asuransi->ViewAttributes() ?>>
<?php echo $tpinjaman->asuransi->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->notaris->Visible) { // notaris ?>
	<tr id="r_notaris">
		<td><span id="elh_tpinjaman_notaris"><?php echo $tpinjaman->notaris->FldCaption() ?></span></td>
		<td data-name="notaris"<?php echo $tpinjaman->notaris->CellAttributes() ?>>
<span id="el_tpinjaman_notaris">
<span<?php echo $tpinjaman->notaris->ViewAttributes() ?>>
<?php echo $tpinjaman->notaris->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->biayamaterai->Visible) { // biayamaterai ?>
	<tr id="r_biayamaterai">
		<td><span id="elh_tpinjaman_biayamaterai"><?php echo $tpinjaman->biayamaterai->FldCaption() ?></span></td>
		<td data-name="biayamaterai"<?php echo $tpinjaman->biayamaterai->CellAttributes() ?>>
<span id="el_tpinjaman_biayamaterai">
<span<?php echo $tpinjaman->biayamaterai->ViewAttributes() ?>>
<?php echo $tpinjaman->biayamaterai->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->potongansaldobeku->Visible) { // potongansaldobeku ?>
	<tr id="r_potongansaldobeku">
		<td><span id="elh_tpinjaman_potongansaldobeku"><?php echo $tpinjaman->potongansaldobeku->FldCaption() ?></span></td>
		<td data-name="potongansaldobeku"<?php echo $tpinjaman->potongansaldobeku->CellAttributes() ?>>
<span id="el_tpinjaman_potongansaldobeku">
<span<?php echo $tpinjaman->potongansaldobeku->ViewAttributes() ?>>
<?php echo $tpinjaman->potongansaldobeku->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->angsuranpokok->Visible) { // angsuranpokok ?>
	<tr id="r_angsuranpokok">
		<td><span id="elh_tpinjaman_angsuranpokok"><?php echo $tpinjaman->angsuranpokok->FldCaption() ?></span></td>
		<td data-name="angsuranpokok"<?php echo $tpinjaman->angsuranpokok->CellAttributes() ?>>
<span id="el_tpinjaman_angsuranpokok">
<span<?php echo $tpinjaman->angsuranpokok->ViewAttributes() ?>>
<?php echo $tpinjaman->angsuranpokok->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->angsuranpokokauto->Visible) { // angsuranpokokauto ?>
	<tr id="r_angsuranpokokauto">
		<td><span id="elh_tpinjaman_angsuranpokokauto"><?php echo $tpinjaman->angsuranpokokauto->FldCaption() ?></span></td>
		<td data-name="angsuranpokokauto"<?php echo $tpinjaman->angsuranpokokauto->CellAttributes() ?>>
<span id="el_tpinjaman_angsuranpokokauto">
<span<?php echo $tpinjaman->angsuranpokokauto->ViewAttributes() ?>>
<?php echo $tpinjaman->angsuranpokokauto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->angsuranbunga->Visible) { // angsuranbunga ?>
	<tr id="r_angsuranbunga">
		<td><span id="elh_tpinjaman_angsuranbunga"><?php echo $tpinjaman->angsuranbunga->FldCaption() ?></span></td>
		<td data-name="angsuranbunga"<?php echo $tpinjaman->angsuranbunga->CellAttributes() ?>>
<span id="el_tpinjaman_angsuranbunga">
<span<?php echo $tpinjaman->angsuranbunga->ViewAttributes() ?>>
<?php echo $tpinjaman->angsuranbunga->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->angsuranbungaauto->Visible) { // angsuranbungaauto ?>
	<tr id="r_angsuranbungaauto">
		<td><span id="elh_tpinjaman_angsuranbungaauto"><?php echo $tpinjaman->angsuranbungaauto->FldCaption() ?></span></td>
		<td data-name="angsuranbungaauto"<?php echo $tpinjaman->angsuranbungaauto->CellAttributes() ?>>
<span id="el_tpinjaman_angsuranbungaauto">
<span<?php echo $tpinjaman->angsuranbungaauto->ViewAttributes() ?>>
<?php echo $tpinjaman->angsuranbungaauto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->denda->Visible) { // denda ?>
	<tr id="r_denda">
		<td><span id="elh_tpinjaman_denda"><?php echo $tpinjaman->denda->FldCaption() ?></span></td>
		<td data-name="denda"<?php echo $tpinjaman->denda->CellAttributes() ?>>
<span id="el_tpinjaman_denda">
<span<?php echo $tpinjaman->denda->ViewAttributes() ?>>
<?php echo $tpinjaman->denda->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->dendapersen->Visible) { // dendapersen ?>
	<tr id="r_dendapersen">
		<td><span id="elh_tpinjaman_dendapersen"><?php echo $tpinjaman->dendapersen->FldCaption() ?></span></td>
		<td data-name="dendapersen"<?php echo $tpinjaman->dendapersen->CellAttributes() ?>>
<span id="el_tpinjaman_dendapersen">
<span<?php echo $tpinjaman->dendapersen->ViewAttributes() ?>>
<?php echo $tpinjaman->dendapersen->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->totalangsuran->Visible) { // totalangsuran ?>
	<tr id="r_totalangsuran">
		<td><span id="elh_tpinjaman_totalangsuran"><?php echo $tpinjaman->totalangsuran->FldCaption() ?></span></td>
		<td data-name="totalangsuran"<?php echo $tpinjaman->totalangsuran->CellAttributes() ?>>
<span id="el_tpinjaman_totalangsuran">
<span<?php echo $tpinjaman->totalangsuran->ViewAttributes() ?>>
<?php echo $tpinjaman->totalangsuran->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->totalangsuranauto->Visible) { // totalangsuranauto ?>
	<tr id="r_totalangsuranauto">
		<td><span id="elh_tpinjaman_totalangsuranauto"><?php echo $tpinjaman->totalangsuranauto->FldCaption() ?></span></td>
		<td data-name="totalangsuranauto"<?php echo $tpinjaman->totalangsuranauto->CellAttributes() ?>>
<span id="el_tpinjaman_totalangsuranauto">
<span<?php echo $tpinjaman->totalangsuranauto->ViewAttributes() ?>>
<?php echo $tpinjaman->totalangsuranauto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->totalterima->Visible) { // totalterima ?>
	<tr id="r_totalterima">
		<td><span id="elh_tpinjaman_totalterima"><?php echo $tpinjaman->totalterima->FldCaption() ?></span></td>
		<td data-name="totalterima"<?php echo $tpinjaman->totalterima->CellAttributes() ?>>
<span id="el_tpinjaman_totalterima">
<span<?php echo $tpinjaman->totalterima->ViewAttributes() ?>>
<?php echo $tpinjaman->totalterima->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->totalterimaauto->Visible) { // totalterimaauto ?>
	<tr id="r_totalterimaauto">
		<td><span id="elh_tpinjaman_totalterimaauto"><?php echo $tpinjaman->totalterimaauto->FldCaption() ?></span></td>
		<td data-name="totalterimaauto"<?php echo $tpinjaman->totalterimaauto->CellAttributes() ?>>
<span id="el_tpinjaman_totalterimaauto">
<span<?php echo $tpinjaman->totalterimaauto->ViewAttributes() ?>>
<?php echo $tpinjaman->totalterimaauto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->terbilang->Visible) { // terbilang ?>
	<tr id="r_terbilang">
		<td><span id="elh_tpinjaman_terbilang"><?php echo $tpinjaman->terbilang->FldCaption() ?></span></td>
		<td data-name="terbilang"<?php echo $tpinjaman->terbilang->CellAttributes() ?>>
<span id="el_tpinjaman_terbilang">
<span<?php echo $tpinjaman->terbilang->ViewAttributes() ?>>
<?php echo $tpinjaman->terbilang->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->petugas->Visible) { // petugas ?>
	<tr id="r_petugas">
		<td><span id="elh_tpinjaman_petugas"><?php echo $tpinjaman->petugas->FldCaption() ?></span></td>
		<td data-name="petugas"<?php echo $tpinjaman->petugas->CellAttributes() ?>>
<span id="el_tpinjaman_petugas">
<span<?php echo $tpinjaman->petugas->ViewAttributes() ?>>
<?php echo $tpinjaman->petugas->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->pembayaran->Visible) { // pembayaran ?>
	<tr id="r_pembayaran">
		<td><span id="elh_tpinjaman_pembayaran"><?php echo $tpinjaman->pembayaran->FldCaption() ?></span></td>
		<td data-name="pembayaran"<?php echo $tpinjaman->pembayaran->CellAttributes() ?>>
<span id="el_tpinjaman_pembayaran">
<span<?php echo $tpinjaman->pembayaran->ViewAttributes() ?>>
<?php echo $tpinjaman->pembayaran->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->bank->Visible) { // bank ?>
	<tr id="r_bank">
		<td><span id="elh_tpinjaman_bank"><?php echo $tpinjaman->bank->FldCaption() ?></span></td>
		<td data-name="bank"<?php echo $tpinjaman->bank->CellAttributes() ?>>
<span id="el_tpinjaman_bank">
<span<?php echo $tpinjaman->bank->ViewAttributes() ?>>
<?php echo $tpinjaman->bank->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->atasnama->Visible) { // atasnama ?>
	<tr id="r_atasnama">
		<td><span id="elh_tpinjaman_atasnama"><?php echo $tpinjaman->atasnama->FldCaption() ?></span></td>
		<td data-name="atasnama"<?php echo $tpinjaman->atasnama->CellAttributes() ?>>
<span id="el_tpinjaman_atasnama">
<span<?php echo $tpinjaman->atasnama->ViewAttributes() ?>>
<?php echo $tpinjaman->atasnama->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->tipe->Visible) { // tipe ?>
	<tr id="r_tipe">
		<td><span id="elh_tpinjaman_tipe"><?php echo $tpinjaman->tipe->FldCaption() ?></span></td>
		<td data-name="tipe"<?php echo $tpinjaman->tipe->CellAttributes() ?>>
<span id="el_tpinjaman_tipe">
<span<?php echo $tpinjaman->tipe->ViewAttributes() ?>>
<?php echo $tpinjaman->tipe->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->kantor->Visible) { // kantor ?>
	<tr id="r_kantor">
		<td><span id="elh_tpinjaman_kantor"><?php echo $tpinjaman->kantor->FldCaption() ?></span></td>
		<td data-name="kantor"<?php echo $tpinjaman->kantor->CellAttributes() ?>>
<span id="el_tpinjaman_kantor">
<span<?php echo $tpinjaman->kantor->ViewAttributes() ?>>
<?php echo $tpinjaman->kantor->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->keterangan->Visible) { // keterangan ?>
	<tr id="r_keterangan">
		<td><span id="elh_tpinjaman_keterangan"><?php echo $tpinjaman->keterangan->FldCaption() ?></span></td>
		<td data-name="keterangan"<?php echo $tpinjaman->keterangan->CellAttributes() ?>>
<span id="el_tpinjaman_keterangan">
<span<?php echo $tpinjaman->keterangan->ViewAttributes() ?>>
<?php echo $tpinjaman->keterangan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->active->Visible) { // active ?>
	<tr id="r_active">
		<td><span id="elh_tpinjaman_active"><?php echo $tpinjaman->active->FldCaption() ?></span></td>
		<td data-name="active"<?php echo $tpinjaman->active->CellAttributes() ?>>
<span id="el_tpinjaman_active">
<span<?php echo $tpinjaman->active->ViewAttributes() ?>>
<?php echo $tpinjaman->active->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->ip->Visible) { // ip ?>
	<tr id="r_ip">
		<td><span id="elh_tpinjaman_ip"><?php echo $tpinjaman->ip->FldCaption() ?></span></td>
		<td data-name="ip"<?php echo $tpinjaman->ip->CellAttributes() ?>>
<span id="el_tpinjaman_ip">
<span<?php echo $tpinjaman->ip->ViewAttributes() ?>>
<?php echo $tpinjaman->ip->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->status->Visible) { // status ?>
	<tr id="r_status">
		<td><span id="elh_tpinjaman_status"><?php echo $tpinjaman->status->FldCaption() ?></span></td>
		<td data-name="status"<?php echo $tpinjaman->status->CellAttributes() ?>>
<span id="el_tpinjaman_status">
<span<?php echo $tpinjaman->status->ViewAttributes() ?>>
<?php echo $tpinjaman->status->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->user->Visible) { // user ?>
	<tr id="r_user">
		<td><span id="elh_tpinjaman_user"><?php echo $tpinjaman->user->FldCaption() ?></span></td>
		<td data-name="user"<?php echo $tpinjaman->user->CellAttributes() ?>>
<span id="el_tpinjaman_user">
<span<?php echo $tpinjaman->user->ViewAttributes() ?>>
<?php echo $tpinjaman->user->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->created->Visible) { // created ?>
	<tr id="r_created">
		<td><span id="elh_tpinjaman_created"><?php echo $tpinjaman->created->FldCaption() ?></span></td>
		<td data-name="created"<?php echo $tpinjaman->created->CellAttributes() ?>>
<span id="el_tpinjaman_created">
<span<?php echo $tpinjaman->created->ViewAttributes() ?>>
<?php echo $tpinjaman->created->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpinjaman->modified->Visible) { // modified ?>
	<tr id="r_modified">
		<td><span id="elh_tpinjaman_modified"><?php echo $tpinjaman->modified->FldCaption() ?></span></td>
		<td data-name="modified"<?php echo $tpinjaman->modified->CellAttributes() ?>>
<span id="el_tpinjaman_modified">
<span<?php echo $tpinjaman->modified->ViewAttributes() ?>>
<?php echo $tpinjaman->modified->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
ftpinjamanview.Init();
</script>
<?php
$tpinjaman_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tpinjaman_view->Page_Terminate();
?>
