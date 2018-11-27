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

$tbayarangsuran_view = NULL; // Initialize page object first

class ctbayarangsuran_view extends ctbayarangsuran {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tbayarangsuran';

	// Page object name
	var $PageObjName = 'tbayarangsuran_view';

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
			define("EW_TABLE_NAME", 'tbayarangsuran', TRUE);

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
				$sReturnUrl = "tbayarangsuranlist.php"; // Return to list
			}
			if (@$_GET["angsuran"] <> "") {
				$this->angsuran->setQueryStringValue($_GET["angsuran"]);
				$this->RecKey["angsuran"] = $this->angsuran->QueryStringValue;
			} elseif (@$_POST["angsuran"] <> "") {
				$this->angsuran->setFormValue($_POST["angsuran"]);
				$this->RecKey["angsuran"] = $this->angsuran->FormValue;
			} else {
				$sReturnUrl = "tbayarangsuranlist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "tbayarangsuranlist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "tbayarangsuranlist.php"; // Not page request, return to list
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("tbayarangsuranlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($tbayarangsuran_view)) $tbayarangsuran_view = new ctbayarangsuran_view();

// Page init
$tbayarangsuran_view->Page_Init();

// Page main
$tbayarangsuran_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tbayarangsuran_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = ftbayarangsuranview = new ew_Form("ftbayarangsuranview", "view");

// Form_CustomValidate event
ftbayarangsuranview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbayarangsuranview.ValidateRequired = true;
<?php } else { ?>
ftbayarangsuranview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftbayarangsuranview.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftbayarangsuranview.Lists["x_active"].Options = <?php echo json_encode($tbayarangsuran->active->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if (!$tbayarangsuran_view->IsModal) { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php $tbayarangsuran_view->ExportOptions->Render("body") ?>
<?php
	foreach ($tbayarangsuran_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if (!$tbayarangsuran_view->IsModal) { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php $tbayarangsuran_view->ShowPageHeader(); ?>
<?php
$tbayarangsuran_view->ShowMessage();
?>
<form name="ftbayarangsuranview" id="ftbayarangsuranview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tbayarangsuran_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tbayarangsuran_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tbayarangsuran">
<?php if ($tbayarangsuran_view->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($tbayarangsuran->tanggal->Visible) { // tanggal ?>
	<tr id="r_tanggal">
		<td><span id="elh_tbayarangsuran_tanggal"><?php echo $tbayarangsuran->tanggal->FldCaption() ?></span></td>
		<td data-name="tanggal"<?php echo $tbayarangsuran->tanggal->CellAttributes() ?>>
<span id="el_tbayarangsuran_tanggal">
<span<?php echo $tbayarangsuran->tanggal->ViewAttributes() ?>>
<?php echo $tbayarangsuran->tanggal->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->periode->Visible) { // periode ?>
	<tr id="r_periode">
		<td><span id="elh_tbayarangsuran_periode"><?php echo $tbayarangsuran->periode->FldCaption() ?></span></td>
		<td data-name="periode"<?php echo $tbayarangsuran->periode->CellAttributes() ?>>
<span id="el_tbayarangsuran_periode">
<span<?php echo $tbayarangsuran->periode->ViewAttributes() ?>>
<?php echo $tbayarangsuran->periode->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->id->Visible) { // id ?>
	<tr id="r_id">
		<td><span id="elh_tbayarangsuran_id"><?php echo $tbayarangsuran->id->FldCaption() ?></span></td>
		<td data-name="id"<?php echo $tbayarangsuran->id->CellAttributes() ?>>
<span id="el_tbayarangsuran_id">
<span<?php echo $tbayarangsuran->id->ViewAttributes() ?>>
<?php echo $tbayarangsuran->id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->transaksi->Visible) { // transaksi ?>
	<tr id="r_transaksi">
		<td><span id="elh_tbayarangsuran_transaksi"><?php echo $tbayarangsuran->transaksi->FldCaption() ?></span></td>
		<td data-name="transaksi"<?php echo $tbayarangsuran->transaksi->CellAttributes() ?>>
<span id="el_tbayarangsuran_transaksi">
<span<?php echo $tbayarangsuran->transaksi->ViewAttributes() ?>>
<?php echo $tbayarangsuran->transaksi->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->referensi->Visible) { // referensi ?>
	<tr id="r_referensi">
		<td><span id="elh_tbayarangsuran_referensi"><?php echo $tbayarangsuran->referensi->FldCaption() ?></span></td>
		<td data-name="referensi"<?php echo $tbayarangsuran->referensi->CellAttributes() ?>>
<span id="el_tbayarangsuran_referensi">
<span<?php echo $tbayarangsuran->referensi->ViewAttributes() ?>>
<?php echo $tbayarangsuran->referensi->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->anggota->Visible) { // anggota ?>
	<tr id="r_anggota">
		<td><span id="elh_tbayarangsuran_anggota"><?php echo $tbayarangsuran->anggota->FldCaption() ?></span></td>
		<td data-name="anggota"<?php echo $tbayarangsuran->anggota->CellAttributes() ?>>
<span id="el_tbayarangsuran_anggota">
<span<?php echo $tbayarangsuran->anggota->ViewAttributes() ?>>
<?php echo $tbayarangsuran->anggota->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->namaanggota->Visible) { // namaanggota ?>
	<tr id="r_namaanggota">
		<td><span id="elh_tbayarangsuran_namaanggota"><?php echo $tbayarangsuran->namaanggota->FldCaption() ?></span></td>
		<td data-name="namaanggota"<?php echo $tbayarangsuran->namaanggota->CellAttributes() ?>>
<span id="el_tbayarangsuran_namaanggota">
<span<?php echo $tbayarangsuran->namaanggota->ViewAttributes() ?>>
<?php echo $tbayarangsuran->namaanggota->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->alamat->Visible) { // alamat ?>
	<tr id="r_alamat">
		<td><span id="elh_tbayarangsuran_alamat"><?php echo $tbayarangsuran->alamat->FldCaption() ?></span></td>
		<td data-name="alamat"<?php echo $tbayarangsuran->alamat->CellAttributes() ?>>
<span id="el_tbayarangsuran_alamat">
<span<?php echo $tbayarangsuran->alamat->ViewAttributes() ?>>
<?php echo $tbayarangsuran->alamat->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->pekerjaan->Visible) { // pekerjaan ?>
	<tr id="r_pekerjaan">
		<td><span id="elh_tbayarangsuran_pekerjaan"><?php echo $tbayarangsuran->pekerjaan->FldCaption() ?></span></td>
		<td data-name="pekerjaan"<?php echo $tbayarangsuran->pekerjaan->CellAttributes() ?>>
<span id="el_tbayarangsuran_pekerjaan">
<span<?php echo $tbayarangsuran->pekerjaan->ViewAttributes() ?>>
<?php echo $tbayarangsuran->pekerjaan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->telepon->Visible) { // telepon ?>
	<tr id="r_telepon">
		<td><span id="elh_tbayarangsuran_telepon"><?php echo $tbayarangsuran->telepon->FldCaption() ?></span></td>
		<td data-name="telepon"<?php echo $tbayarangsuran->telepon->CellAttributes() ?>>
<span id="el_tbayarangsuran_telepon">
<span<?php echo $tbayarangsuran->telepon->ViewAttributes() ?>>
<?php echo $tbayarangsuran->telepon->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->hp->Visible) { // hp ?>
	<tr id="r_hp">
		<td><span id="elh_tbayarangsuran_hp"><?php echo $tbayarangsuran->hp->FldCaption() ?></span></td>
		<td data-name="hp"<?php echo $tbayarangsuran->hp->CellAttributes() ?>>
<span id="el_tbayarangsuran_hp">
<span<?php echo $tbayarangsuran->hp->ViewAttributes() ?>>
<?php echo $tbayarangsuran->hp->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->fax->Visible) { // fax ?>
	<tr id="r_fax">
		<td><span id="elh_tbayarangsuran_fax"><?php echo $tbayarangsuran->fax->FldCaption() ?></span></td>
		<td data-name="fax"<?php echo $tbayarangsuran->fax->CellAttributes() ?>>
<span id="el_tbayarangsuran_fax">
<span<?php echo $tbayarangsuran->fax->ViewAttributes() ?>>
<?php echo $tbayarangsuran->fax->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->_email->Visible) { // email ?>
	<tr id="r__email">
		<td><span id="elh_tbayarangsuran__email"><?php echo $tbayarangsuran->_email->FldCaption() ?></span></td>
		<td data-name="_email"<?php echo $tbayarangsuran->_email->CellAttributes() ?>>
<span id="el_tbayarangsuran__email">
<span<?php echo $tbayarangsuran->_email->ViewAttributes() ?>>
<?php echo $tbayarangsuran->_email->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->website->Visible) { // website ?>
	<tr id="r_website">
		<td><span id="elh_tbayarangsuran_website"><?php echo $tbayarangsuran->website->FldCaption() ?></span></td>
		<td data-name="website"<?php echo $tbayarangsuran->website->CellAttributes() ?>>
<span id="el_tbayarangsuran_website">
<span<?php echo $tbayarangsuran->website->ViewAttributes() ?>>
<?php echo $tbayarangsuran->website->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->jenisanggota->Visible) { // jenisanggota ?>
	<tr id="r_jenisanggota">
		<td><span id="elh_tbayarangsuran_jenisanggota"><?php echo $tbayarangsuran->jenisanggota->FldCaption() ?></span></td>
		<td data-name="jenisanggota"<?php echo $tbayarangsuran->jenisanggota->CellAttributes() ?>>
<span id="el_tbayarangsuran_jenisanggota">
<span<?php echo $tbayarangsuran->jenisanggota->ViewAttributes() ?>>
<?php echo $tbayarangsuran->jenisanggota->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->model->Visible) { // model ?>
	<tr id="r_model">
		<td><span id="elh_tbayarangsuran_model"><?php echo $tbayarangsuran->model->FldCaption() ?></span></td>
		<td data-name="model"<?php echo $tbayarangsuran->model->CellAttributes() ?>>
<span id="el_tbayarangsuran_model">
<span<?php echo $tbayarangsuran->model->ViewAttributes() ?>>
<?php echo $tbayarangsuran->model->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->jenispinjaman->Visible) { // jenispinjaman ?>
	<tr id="r_jenispinjaman">
		<td><span id="elh_tbayarangsuran_jenispinjaman"><?php echo $tbayarangsuran->jenispinjaman->FldCaption() ?></span></td>
		<td data-name="jenispinjaman"<?php echo $tbayarangsuran->jenispinjaman->CellAttributes() ?>>
<span id="el_tbayarangsuran_jenispinjaman">
<span<?php echo $tbayarangsuran->jenispinjaman->ViewAttributes() ?>>
<?php echo $tbayarangsuran->jenispinjaman->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->jenisbunga->Visible) { // jenisbunga ?>
	<tr id="r_jenisbunga">
		<td><span id="elh_tbayarangsuran_jenisbunga"><?php echo $tbayarangsuran->jenisbunga->FldCaption() ?></span></td>
		<td data-name="jenisbunga"<?php echo $tbayarangsuran->jenisbunga->CellAttributes() ?>>
<span id="el_tbayarangsuran_jenisbunga">
<span<?php echo $tbayarangsuran->jenisbunga->ViewAttributes() ?>>
<?php echo $tbayarangsuran->jenisbunga->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->angsuran->Visible) { // angsuran ?>
	<tr id="r_angsuran">
		<td><span id="elh_tbayarangsuran_angsuran"><?php echo $tbayarangsuran->angsuran->FldCaption() ?></span></td>
		<td data-name="angsuran"<?php echo $tbayarangsuran->angsuran->CellAttributes() ?>>
<span id="el_tbayarangsuran_angsuran">
<span<?php echo $tbayarangsuran->angsuran->ViewAttributes() ?>>
<?php echo $tbayarangsuran->angsuran->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->masaangsuran->Visible) { // masaangsuran ?>
	<tr id="r_masaangsuran">
		<td><span id="elh_tbayarangsuran_masaangsuran"><?php echo $tbayarangsuran->masaangsuran->FldCaption() ?></span></td>
		<td data-name="masaangsuran"<?php echo $tbayarangsuran->masaangsuran->CellAttributes() ?>>
<span id="el_tbayarangsuran_masaangsuran">
<span<?php echo $tbayarangsuran->masaangsuran->ViewAttributes() ?>>
<?php echo $tbayarangsuran->masaangsuran->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->jatuhtempo->Visible) { // jatuhtempo ?>
	<tr id="r_jatuhtempo">
		<td><span id="elh_tbayarangsuran_jatuhtempo"><?php echo $tbayarangsuran->jatuhtempo->FldCaption() ?></span></td>
		<td data-name="jatuhtempo"<?php echo $tbayarangsuran->jatuhtempo->CellAttributes() ?>>
<span id="el_tbayarangsuran_jatuhtempo">
<span<?php echo $tbayarangsuran->jatuhtempo->ViewAttributes() ?>>
<?php echo $tbayarangsuran->jatuhtempo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->terlambat->Visible) { // terlambat ?>
	<tr id="r_terlambat">
		<td><span id="elh_tbayarangsuran_terlambat"><?php echo $tbayarangsuran->terlambat->FldCaption() ?></span></td>
		<td data-name="terlambat"<?php echo $tbayarangsuran->terlambat->CellAttributes() ?>>
<span id="el_tbayarangsuran_terlambat">
<span<?php echo $tbayarangsuran->terlambat->ViewAttributes() ?>>
<?php echo $tbayarangsuran->terlambat->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->dispensasidenda->Visible) { // dispensasidenda ?>
	<tr id="r_dispensasidenda">
		<td><span id="elh_tbayarangsuran_dispensasidenda"><?php echo $tbayarangsuran->dispensasidenda->FldCaption() ?></span></td>
		<td data-name="dispensasidenda"<?php echo $tbayarangsuran->dispensasidenda->CellAttributes() ?>>
<span id="el_tbayarangsuran_dispensasidenda">
<span<?php echo $tbayarangsuran->dispensasidenda->ViewAttributes() ?>>
<?php echo $tbayarangsuran->dispensasidenda->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->plafond->Visible) { // plafond ?>
	<tr id="r_plafond">
		<td><span id="elh_tbayarangsuran_plafond"><?php echo $tbayarangsuran->plafond->FldCaption() ?></span></td>
		<td data-name="plafond"<?php echo $tbayarangsuran->plafond->CellAttributes() ?>>
<span id="el_tbayarangsuran_plafond">
<span<?php echo $tbayarangsuran->plafond->ViewAttributes() ?>>
<?php echo $tbayarangsuran->plafond->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->angsuranpokok->Visible) { // angsuranpokok ?>
	<tr id="r_angsuranpokok">
		<td><span id="elh_tbayarangsuran_angsuranpokok"><?php echo $tbayarangsuran->angsuranpokok->FldCaption() ?></span></td>
		<td data-name="angsuranpokok"<?php echo $tbayarangsuran->angsuranpokok->CellAttributes() ?>>
<span id="el_tbayarangsuran_angsuranpokok">
<span<?php echo $tbayarangsuran->angsuranpokok->ViewAttributes() ?>>
<?php echo $tbayarangsuran->angsuranpokok->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->angsuranpokokauto->Visible) { // angsuranpokokauto ?>
	<tr id="r_angsuranpokokauto">
		<td><span id="elh_tbayarangsuran_angsuranpokokauto"><?php echo $tbayarangsuran->angsuranpokokauto->FldCaption() ?></span></td>
		<td data-name="angsuranpokokauto"<?php echo $tbayarangsuran->angsuranpokokauto->CellAttributes() ?>>
<span id="el_tbayarangsuran_angsuranpokokauto">
<span<?php echo $tbayarangsuran->angsuranpokokauto->ViewAttributes() ?>>
<?php echo $tbayarangsuran->angsuranpokokauto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->angsuranbunga->Visible) { // angsuranbunga ?>
	<tr id="r_angsuranbunga">
		<td><span id="elh_tbayarangsuran_angsuranbunga"><?php echo $tbayarangsuran->angsuranbunga->FldCaption() ?></span></td>
		<td data-name="angsuranbunga"<?php echo $tbayarangsuran->angsuranbunga->CellAttributes() ?>>
<span id="el_tbayarangsuran_angsuranbunga">
<span<?php echo $tbayarangsuran->angsuranbunga->ViewAttributes() ?>>
<?php echo $tbayarangsuran->angsuranbunga->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->angsuranbungaauto->Visible) { // angsuranbungaauto ?>
	<tr id="r_angsuranbungaauto">
		<td><span id="elh_tbayarangsuran_angsuranbungaauto"><?php echo $tbayarangsuran->angsuranbungaauto->FldCaption() ?></span></td>
		<td data-name="angsuranbungaauto"<?php echo $tbayarangsuran->angsuranbungaauto->CellAttributes() ?>>
<span id="el_tbayarangsuran_angsuranbungaauto">
<span<?php echo $tbayarangsuran->angsuranbungaauto->ViewAttributes() ?>>
<?php echo $tbayarangsuran->angsuranbungaauto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->denda->Visible) { // denda ?>
	<tr id="r_denda">
		<td><span id="elh_tbayarangsuran_denda"><?php echo $tbayarangsuran->denda->FldCaption() ?></span></td>
		<td data-name="denda"<?php echo $tbayarangsuran->denda->CellAttributes() ?>>
<span id="el_tbayarangsuran_denda">
<span<?php echo $tbayarangsuran->denda->ViewAttributes() ?>>
<?php echo $tbayarangsuran->denda->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->dendapersen->Visible) { // dendapersen ?>
	<tr id="r_dendapersen">
		<td><span id="elh_tbayarangsuran_dendapersen"><?php echo $tbayarangsuran->dendapersen->FldCaption() ?></span></td>
		<td data-name="dendapersen"<?php echo $tbayarangsuran->dendapersen->CellAttributes() ?>>
<span id="el_tbayarangsuran_dendapersen">
<span<?php echo $tbayarangsuran->dendapersen->ViewAttributes() ?>>
<?php echo $tbayarangsuran->dendapersen->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->totalangsuran->Visible) { // totalangsuran ?>
	<tr id="r_totalangsuran">
		<td><span id="elh_tbayarangsuran_totalangsuran"><?php echo $tbayarangsuran->totalangsuran->FldCaption() ?></span></td>
		<td data-name="totalangsuran"<?php echo $tbayarangsuran->totalangsuran->CellAttributes() ?>>
<span id="el_tbayarangsuran_totalangsuran">
<span<?php echo $tbayarangsuran->totalangsuran->ViewAttributes() ?>>
<?php echo $tbayarangsuran->totalangsuran->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->totalangsuranauto->Visible) { // totalangsuranauto ?>
	<tr id="r_totalangsuranauto">
		<td><span id="elh_tbayarangsuran_totalangsuranauto"><?php echo $tbayarangsuran->totalangsuranauto->FldCaption() ?></span></td>
		<td data-name="totalangsuranauto"<?php echo $tbayarangsuran->totalangsuranauto->CellAttributes() ?>>
<span id="el_tbayarangsuran_totalangsuranauto">
<span<?php echo $tbayarangsuran->totalangsuranauto->ViewAttributes() ?>>
<?php echo $tbayarangsuran->totalangsuranauto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->sisaangsuran->Visible) { // sisaangsuran ?>
	<tr id="r_sisaangsuran">
		<td><span id="elh_tbayarangsuran_sisaangsuran"><?php echo $tbayarangsuran->sisaangsuran->FldCaption() ?></span></td>
		<td data-name="sisaangsuran"<?php echo $tbayarangsuran->sisaangsuran->CellAttributes() ?>>
<span id="el_tbayarangsuran_sisaangsuran">
<span<?php echo $tbayarangsuran->sisaangsuran->ViewAttributes() ?>>
<?php echo $tbayarangsuran->sisaangsuran->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->sisaangsuranauto->Visible) { // sisaangsuranauto ?>
	<tr id="r_sisaangsuranauto">
		<td><span id="elh_tbayarangsuran_sisaangsuranauto"><?php echo $tbayarangsuran->sisaangsuranauto->FldCaption() ?></span></td>
		<td data-name="sisaangsuranauto"<?php echo $tbayarangsuran->sisaangsuranauto->CellAttributes() ?>>
<span id="el_tbayarangsuran_sisaangsuranauto">
<span<?php echo $tbayarangsuran->sisaangsuranauto->ViewAttributes() ?>>
<?php echo $tbayarangsuran->sisaangsuranauto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->saldotitipan->Visible) { // saldotitipan ?>
	<tr id="r_saldotitipan">
		<td><span id="elh_tbayarangsuran_saldotitipan"><?php echo $tbayarangsuran->saldotitipan->FldCaption() ?></span></td>
		<td data-name="saldotitipan"<?php echo $tbayarangsuran->saldotitipan->CellAttributes() ?>>
<span id="el_tbayarangsuran_saldotitipan">
<span<?php echo $tbayarangsuran->saldotitipan->ViewAttributes() ?>>
<?php echo $tbayarangsuran->saldotitipan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->saldotitipansisa->Visible) { // saldotitipansisa ?>
	<tr id="r_saldotitipansisa">
		<td><span id="elh_tbayarangsuran_saldotitipansisa"><?php echo $tbayarangsuran->saldotitipansisa->FldCaption() ?></span></td>
		<td data-name="saldotitipansisa"<?php echo $tbayarangsuran->saldotitipansisa->CellAttributes() ?>>
<span id="el_tbayarangsuran_saldotitipansisa">
<span<?php echo $tbayarangsuran->saldotitipansisa->ViewAttributes() ?>>
<?php echo $tbayarangsuran->saldotitipansisa->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->bayarpokok->Visible) { // bayarpokok ?>
	<tr id="r_bayarpokok">
		<td><span id="elh_tbayarangsuran_bayarpokok"><?php echo $tbayarangsuran->bayarpokok->FldCaption() ?></span></td>
		<td data-name="bayarpokok"<?php echo $tbayarangsuran->bayarpokok->CellAttributes() ?>>
<span id="el_tbayarangsuran_bayarpokok">
<span<?php echo $tbayarangsuran->bayarpokok->ViewAttributes() ?>>
<?php echo $tbayarangsuran->bayarpokok->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->bayarpokokauto->Visible) { // bayarpokokauto ?>
	<tr id="r_bayarpokokauto">
		<td><span id="elh_tbayarangsuran_bayarpokokauto"><?php echo $tbayarangsuran->bayarpokokauto->FldCaption() ?></span></td>
		<td data-name="bayarpokokauto"<?php echo $tbayarangsuran->bayarpokokauto->CellAttributes() ?>>
<span id="el_tbayarangsuran_bayarpokokauto">
<span<?php echo $tbayarangsuran->bayarpokokauto->ViewAttributes() ?>>
<?php echo $tbayarangsuran->bayarpokokauto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->bayarbunga->Visible) { // bayarbunga ?>
	<tr id="r_bayarbunga">
		<td><span id="elh_tbayarangsuran_bayarbunga"><?php echo $tbayarangsuran->bayarbunga->FldCaption() ?></span></td>
		<td data-name="bayarbunga"<?php echo $tbayarangsuran->bayarbunga->CellAttributes() ?>>
<span id="el_tbayarangsuran_bayarbunga">
<span<?php echo $tbayarangsuran->bayarbunga->ViewAttributes() ?>>
<?php echo $tbayarangsuran->bayarbunga->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->bayarbungaauto->Visible) { // bayarbungaauto ?>
	<tr id="r_bayarbungaauto">
		<td><span id="elh_tbayarangsuran_bayarbungaauto"><?php echo $tbayarangsuran->bayarbungaauto->FldCaption() ?></span></td>
		<td data-name="bayarbungaauto"<?php echo $tbayarangsuran->bayarbungaauto->CellAttributes() ?>>
<span id="el_tbayarangsuran_bayarbungaauto">
<span<?php echo $tbayarangsuran->bayarbungaauto->ViewAttributes() ?>>
<?php echo $tbayarangsuran->bayarbungaauto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->bayardenda->Visible) { // bayardenda ?>
	<tr id="r_bayardenda">
		<td><span id="elh_tbayarangsuran_bayardenda"><?php echo $tbayarangsuran->bayardenda->FldCaption() ?></span></td>
		<td data-name="bayardenda"<?php echo $tbayarangsuran->bayardenda->CellAttributes() ?>>
<span id="el_tbayarangsuran_bayardenda">
<span<?php echo $tbayarangsuran->bayardenda->ViewAttributes() ?>>
<?php echo $tbayarangsuran->bayardenda->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->bayardendaauto->Visible) { // bayardendaauto ?>
	<tr id="r_bayardendaauto">
		<td><span id="elh_tbayarangsuran_bayardendaauto"><?php echo $tbayarangsuran->bayardendaauto->FldCaption() ?></span></td>
		<td data-name="bayardendaauto"<?php echo $tbayarangsuran->bayardendaauto->CellAttributes() ?>>
<span id="el_tbayarangsuran_bayardendaauto">
<span<?php echo $tbayarangsuran->bayardendaauto->ViewAttributes() ?>>
<?php echo $tbayarangsuran->bayardendaauto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->bayartitipan->Visible) { // bayartitipan ?>
	<tr id="r_bayartitipan">
		<td><span id="elh_tbayarangsuran_bayartitipan"><?php echo $tbayarangsuran->bayartitipan->FldCaption() ?></span></td>
		<td data-name="bayartitipan"<?php echo $tbayarangsuran->bayartitipan->CellAttributes() ?>>
<span id="el_tbayarangsuran_bayartitipan">
<span<?php echo $tbayarangsuran->bayartitipan->ViewAttributes() ?>>
<?php echo $tbayarangsuran->bayartitipan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->bayartitipanauto->Visible) { // bayartitipanauto ?>
	<tr id="r_bayartitipanauto">
		<td><span id="elh_tbayarangsuran_bayartitipanauto"><?php echo $tbayarangsuran->bayartitipanauto->FldCaption() ?></span></td>
		<td data-name="bayartitipanauto"<?php echo $tbayarangsuran->bayartitipanauto->CellAttributes() ?>>
<span id="el_tbayarangsuran_bayartitipanauto">
<span<?php echo $tbayarangsuran->bayartitipanauto->ViewAttributes() ?>>
<?php echo $tbayarangsuran->bayartitipanauto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->totalbayar->Visible) { // totalbayar ?>
	<tr id="r_totalbayar">
		<td><span id="elh_tbayarangsuran_totalbayar"><?php echo $tbayarangsuran->totalbayar->FldCaption() ?></span></td>
		<td data-name="totalbayar"<?php echo $tbayarangsuran->totalbayar->CellAttributes() ?>>
<span id="el_tbayarangsuran_totalbayar">
<span<?php echo $tbayarangsuran->totalbayar->ViewAttributes() ?>>
<?php echo $tbayarangsuran->totalbayar->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->totalbayarauto->Visible) { // totalbayarauto ?>
	<tr id="r_totalbayarauto">
		<td><span id="elh_tbayarangsuran_totalbayarauto"><?php echo $tbayarangsuran->totalbayarauto->FldCaption() ?></span></td>
		<td data-name="totalbayarauto"<?php echo $tbayarangsuran->totalbayarauto->CellAttributes() ?>>
<span id="el_tbayarangsuran_totalbayarauto">
<span<?php echo $tbayarangsuran->totalbayarauto->ViewAttributes() ?>>
<?php echo $tbayarangsuran->totalbayarauto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->pelunasan->Visible) { // pelunasan ?>
	<tr id="r_pelunasan">
		<td><span id="elh_tbayarangsuran_pelunasan"><?php echo $tbayarangsuran->pelunasan->FldCaption() ?></span></td>
		<td data-name="pelunasan"<?php echo $tbayarangsuran->pelunasan->CellAttributes() ?>>
<span id="el_tbayarangsuran_pelunasan">
<span<?php echo $tbayarangsuran->pelunasan->ViewAttributes() ?>>
<?php echo $tbayarangsuran->pelunasan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->pelunasanauto->Visible) { // pelunasanauto ?>
	<tr id="r_pelunasanauto">
		<td><span id="elh_tbayarangsuran_pelunasanauto"><?php echo $tbayarangsuran->pelunasanauto->FldCaption() ?></span></td>
		<td data-name="pelunasanauto"<?php echo $tbayarangsuran->pelunasanauto->CellAttributes() ?>>
<span id="el_tbayarangsuran_pelunasanauto">
<span<?php echo $tbayarangsuran->pelunasanauto->ViewAttributes() ?>>
<?php echo $tbayarangsuran->pelunasanauto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->finalty->Visible) { // finalty ?>
	<tr id="r_finalty">
		<td><span id="elh_tbayarangsuran_finalty"><?php echo $tbayarangsuran->finalty->FldCaption() ?></span></td>
		<td data-name="finalty"<?php echo $tbayarangsuran->finalty->CellAttributes() ?>>
<span id="el_tbayarangsuran_finalty">
<span<?php echo $tbayarangsuran->finalty->ViewAttributes() ?>>
<?php echo $tbayarangsuran->finalty->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->finaltyauto->Visible) { // finaltyauto ?>
	<tr id="r_finaltyauto">
		<td><span id="elh_tbayarangsuran_finaltyauto"><?php echo $tbayarangsuran->finaltyauto->FldCaption() ?></span></td>
		<td data-name="finaltyauto"<?php echo $tbayarangsuran->finaltyauto->CellAttributes() ?>>
<span id="el_tbayarangsuran_finaltyauto">
<span<?php echo $tbayarangsuran->finaltyauto->ViewAttributes() ?>>
<?php echo $tbayarangsuran->finaltyauto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->terbilang->Visible) { // terbilang ?>
	<tr id="r_terbilang">
		<td><span id="elh_tbayarangsuran_terbilang"><?php echo $tbayarangsuran->terbilang->FldCaption() ?></span></td>
		<td data-name="terbilang"<?php echo $tbayarangsuran->terbilang->CellAttributes() ?>>
<span id="el_tbayarangsuran_terbilang">
<span<?php echo $tbayarangsuran->terbilang->ViewAttributes() ?>>
<?php echo $tbayarangsuran->terbilang->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->petugas->Visible) { // petugas ?>
	<tr id="r_petugas">
		<td><span id="elh_tbayarangsuran_petugas"><?php echo $tbayarangsuran->petugas->FldCaption() ?></span></td>
		<td data-name="petugas"<?php echo $tbayarangsuran->petugas->CellAttributes() ?>>
<span id="el_tbayarangsuran_petugas">
<span<?php echo $tbayarangsuran->petugas->ViewAttributes() ?>>
<?php echo $tbayarangsuran->petugas->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->pembayaran->Visible) { // pembayaran ?>
	<tr id="r_pembayaran">
		<td><span id="elh_tbayarangsuran_pembayaran"><?php echo $tbayarangsuran->pembayaran->FldCaption() ?></span></td>
		<td data-name="pembayaran"<?php echo $tbayarangsuran->pembayaran->CellAttributes() ?>>
<span id="el_tbayarangsuran_pembayaran">
<span<?php echo $tbayarangsuran->pembayaran->ViewAttributes() ?>>
<?php echo $tbayarangsuran->pembayaran->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->bank->Visible) { // bank ?>
	<tr id="r_bank">
		<td><span id="elh_tbayarangsuran_bank"><?php echo $tbayarangsuran->bank->FldCaption() ?></span></td>
		<td data-name="bank"<?php echo $tbayarangsuran->bank->CellAttributes() ?>>
<span id="el_tbayarangsuran_bank">
<span<?php echo $tbayarangsuran->bank->ViewAttributes() ?>>
<?php echo $tbayarangsuran->bank->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->atasnama->Visible) { // atasnama ?>
	<tr id="r_atasnama">
		<td><span id="elh_tbayarangsuran_atasnama"><?php echo $tbayarangsuran->atasnama->FldCaption() ?></span></td>
		<td data-name="atasnama"<?php echo $tbayarangsuran->atasnama->CellAttributes() ?>>
<span id="el_tbayarangsuran_atasnama">
<span<?php echo $tbayarangsuran->atasnama->ViewAttributes() ?>>
<?php echo $tbayarangsuran->atasnama->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->tipe->Visible) { // tipe ?>
	<tr id="r_tipe">
		<td><span id="elh_tbayarangsuran_tipe"><?php echo $tbayarangsuran->tipe->FldCaption() ?></span></td>
		<td data-name="tipe"<?php echo $tbayarangsuran->tipe->CellAttributes() ?>>
<span id="el_tbayarangsuran_tipe">
<span<?php echo $tbayarangsuran->tipe->ViewAttributes() ?>>
<?php echo $tbayarangsuran->tipe->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->kantor->Visible) { // kantor ?>
	<tr id="r_kantor">
		<td><span id="elh_tbayarangsuran_kantor"><?php echo $tbayarangsuran->kantor->FldCaption() ?></span></td>
		<td data-name="kantor"<?php echo $tbayarangsuran->kantor->CellAttributes() ?>>
<span id="el_tbayarangsuran_kantor">
<span<?php echo $tbayarangsuran->kantor->ViewAttributes() ?>>
<?php echo $tbayarangsuran->kantor->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->keterangan->Visible) { // keterangan ?>
	<tr id="r_keterangan">
		<td><span id="elh_tbayarangsuran_keterangan"><?php echo $tbayarangsuran->keterangan->FldCaption() ?></span></td>
		<td data-name="keterangan"<?php echo $tbayarangsuran->keterangan->CellAttributes() ?>>
<span id="el_tbayarangsuran_keterangan">
<span<?php echo $tbayarangsuran->keterangan->ViewAttributes() ?>>
<?php echo $tbayarangsuran->keterangan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->active->Visible) { // active ?>
	<tr id="r_active">
		<td><span id="elh_tbayarangsuran_active"><?php echo $tbayarangsuran->active->FldCaption() ?></span></td>
		<td data-name="active"<?php echo $tbayarangsuran->active->CellAttributes() ?>>
<span id="el_tbayarangsuran_active">
<span<?php echo $tbayarangsuran->active->ViewAttributes() ?>>
<?php echo $tbayarangsuran->active->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->ip->Visible) { // ip ?>
	<tr id="r_ip">
		<td><span id="elh_tbayarangsuran_ip"><?php echo $tbayarangsuran->ip->FldCaption() ?></span></td>
		<td data-name="ip"<?php echo $tbayarangsuran->ip->CellAttributes() ?>>
<span id="el_tbayarangsuran_ip">
<span<?php echo $tbayarangsuran->ip->ViewAttributes() ?>>
<?php echo $tbayarangsuran->ip->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->status->Visible) { // status ?>
	<tr id="r_status">
		<td><span id="elh_tbayarangsuran_status"><?php echo $tbayarangsuran->status->FldCaption() ?></span></td>
		<td data-name="status"<?php echo $tbayarangsuran->status->CellAttributes() ?>>
<span id="el_tbayarangsuran_status">
<span<?php echo $tbayarangsuran->status->ViewAttributes() ?>>
<?php echo $tbayarangsuran->status->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->user->Visible) { // user ?>
	<tr id="r_user">
		<td><span id="elh_tbayarangsuran_user"><?php echo $tbayarangsuran->user->FldCaption() ?></span></td>
		<td data-name="user"<?php echo $tbayarangsuran->user->CellAttributes() ?>>
<span id="el_tbayarangsuran_user">
<span<?php echo $tbayarangsuran->user->ViewAttributes() ?>>
<?php echo $tbayarangsuran->user->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->created->Visible) { // created ?>
	<tr id="r_created">
		<td><span id="elh_tbayarangsuran_created"><?php echo $tbayarangsuran->created->FldCaption() ?></span></td>
		<td data-name="created"<?php echo $tbayarangsuran->created->CellAttributes() ?>>
<span id="el_tbayarangsuran_created">
<span<?php echo $tbayarangsuran->created->ViewAttributes() ?>>
<?php echo $tbayarangsuran->created->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayarangsuran->modified->Visible) { // modified ?>
	<tr id="r_modified">
		<td><span id="elh_tbayarangsuran_modified"><?php echo $tbayarangsuran->modified->FldCaption() ?></span></td>
		<td data-name="modified"<?php echo $tbayarangsuran->modified->CellAttributes() ?>>
<span id="el_tbayarangsuran_modified">
<span<?php echo $tbayarangsuran->modified->ViewAttributes() ?>>
<?php echo $tbayarangsuran->modified->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
ftbayarangsuranview.Init();
</script>
<?php
$tbayarangsuran_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tbayarangsuran_view->Page_Terminate();
?>
