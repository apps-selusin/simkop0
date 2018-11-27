<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "tbayartitipaninfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$tbayartitipan_view = NULL; // Initialize page object first

class ctbayartitipan_view extends ctbayartitipan {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tbayartitipan';

	// Page object name
	var $PageObjName = 'tbayartitipan_view';

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

		// Table object (tbayartitipan)
		if (!isset($GLOBALS["tbayartitipan"]) || get_class($GLOBALS["tbayartitipan"]) == "ctbayartitipan") {
			$GLOBALS["tbayartitipan"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tbayartitipan"];
		}
		$KeyUrl = "";
		if (@$_GET["id"] <> "") {
			$this->RecKey["id"] = $_GET["id"];
			$KeyUrl .= "&amp;id=" . urlencode($this->RecKey["id"]);
		}
		if (@$_GET["titipan"] <> "") {
			$this->RecKey["titipan"] = $_GET["titipan"];
			$KeyUrl .= "&amp;titipan=" . urlencode($this->RecKey["titipan"]);
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
			define("EW_TABLE_NAME", 'tbayartitipan', TRUE);

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
		$this->titipan->SetVisibility();
		$this->bayartitipan->SetVisibility();
		$this->bayartitipanauto->SetVisibility();
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
		global $EW_EXPORT, $tbayartitipan;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($tbayartitipan);
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
				$sReturnUrl = "tbayartitipanlist.php"; // Return to list
			}
			if (@$_GET["titipan"] <> "") {
				$this->titipan->setQueryStringValue($_GET["titipan"]);
				$this->RecKey["titipan"] = $this->titipan->QueryStringValue;
			} elseif (@$_POST["titipan"] <> "") {
				$this->titipan->setFormValue($_POST["titipan"]);
				$this->RecKey["titipan"] = $this->titipan->FormValue;
			} else {
				$sReturnUrl = "tbayartitipanlist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "tbayartitipanlist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "tbayartitipanlist.php"; // Not page request, return to list
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
		$this->titipan->setDbValue($rs->fields('titipan'));
		$this->bayartitipan->setDbValue($rs->fields('bayartitipan'));
		$this->bayartitipanauto->setDbValue($rs->fields('bayartitipanauto'));
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
		$this->titipan->DbValue = $row['titipan'];
		$this->bayartitipan->DbValue = $row['bayartitipan'];
		$this->bayartitipanauto->DbValue = $row['bayartitipanauto'];
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
		if ($this->bayartitipan->FormValue == $this->bayartitipan->CurrentValue && is_numeric(ew_StrToFloat($this->bayartitipan->CurrentValue)))
			$this->bayartitipan->CurrentValue = ew_StrToFloat($this->bayartitipan->CurrentValue);

		// Convert decimal values if posted back
		if ($this->bayartitipanauto->FormValue == $this->bayartitipanauto->CurrentValue && is_numeric(ew_StrToFloat($this->bayartitipanauto->CurrentValue)))
			$this->bayartitipanauto->CurrentValue = ew_StrToFloat($this->bayartitipanauto->CurrentValue);

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
		// titipan
		// bayartitipan
		// bayartitipanauto
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

		// titipan
		$this->titipan->ViewValue = $this->titipan->CurrentValue;
		$this->titipan->ViewCustomAttributes = "";

		// bayartitipan
		$this->bayartitipan->ViewValue = $this->bayartitipan->CurrentValue;
		$this->bayartitipan->ViewCustomAttributes = "";

		// bayartitipanauto
		$this->bayartitipanauto->ViewValue = $this->bayartitipanauto->CurrentValue;
		$this->bayartitipanauto->ViewCustomAttributes = "";

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

			// titipan
			$this->titipan->LinkCustomAttributes = "";
			$this->titipan->HrefValue = "";
			$this->titipan->TooltipValue = "";

			// bayartitipan
			$this->bayartitipan->LinkCustomAttributes = "";
			$this->bayartitipan->HrefValue = "";
			$this->bayartitipan->TooltipValue = "";

			// bayartitipanauto
			$this->bayartitipanauto->LinkCustomAttributes = "";
			$this->bayartitipanauto->HrefValue = "";
			$this->bayartitipanauto->TooltipValue = "";

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("tbayartitipanlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($tbayartitipan_view)) $tbayartitipan_view = new ctbayartitipan_view();

// Page init
$tbayartitipan_view->Page_Init();

// Page main
$tbayartitipan_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tbayartitipan_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = ftbayartitipanview = new ew_Form("ftbayartitipanview", "view");

// Form_CustomValidate event
ftbayartitipanview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbayartitipanview.ValidateRequired = true;
<?php } else { ?>
ftbayartitipanview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftbayartitipanview.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftbayartitipanview.Lists["x_active"].Options = <?php echo json_encode($tbayartitipan->active->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if (!$tbayartitipan_view->IsModal) { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php $tbayartitipan_view->ExportOptions->Render("body") ?>
<?php
	foreach ($tbayartitipan_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if (!$tbayartitipan_view->IsModal) { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php $tbayartitipan_view->ShowPageHeader(); ?>
<?php
$tbayartitipan_view->ShowMessage();
?>
<form name="ftbayartitipanview" id="ftbayartitipanview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tbayartitipan_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tbayartitipan_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tbayartitipan">
<?php if ($tbayartitipan_view->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($tbayartitipan->tanggal->Visible) { // tanggal ?>
	<tr id="r_tanggal">
		<td><span id="elh_tbayartitipan_tanggal"><?php echo $tbayartitipan->tanggal->FldCaption() ?></span></td>
		<td data-name="tanggal"<?php echo $tbayartitipan->tanggal->CellAttributes() ?>>
<span id="el_tbayartitipan_tanggal">
<span<?php echo $tbayartitipan->tanggal->ViewAttributes() ?>>
<?php echo $tbayartitipan->tanggal->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->periode->Visible) { // periode ?>
	<tr id="r_periode">
		<td><span id="elh_tbayartitipan_periode"><?php echo $tbayartitipan->periode->FldCaption() ?></span></td>
		<td data-name="periode"<?php echo $tbayartitipan->periode->CellAttributes() ?>>
<span id="el_tbayartitipan_periode">
<span<?php echo $tbayartitipan->periode->ViewAttributes() ?>>
<?php echo $tbayartitipan->periode->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->id->Visible) { // id ?>
	<tr id="r_id">
		<td><span id="elh_tbayartitipan_id"><?php echo $tbayartitipan->id->FldCaption() ?></span></td>
		<td data-name="id"<?php echo $tbayartitipan->id->CellAttributes() ?>>
<span id="el_tbayartitipan_id">
<span<?php echo $tbayartitipan->id->ViewAttributes() ?>>
<?php echo $tbayartitipan->id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->transaksi->Visible) { // transaksi ?>
	<tr id="r_transaksi">
		<td><span id="elh_tbayartitipan_transaksi"><?php echo $tbayartitipan->transaksi->FldCaption() ?></span></td>
		<td data-name="transaksi"<?php echo $tbayartitipan->transaksi->CellAttributes() ?>>
<span id="el_tbayartitipan_transaksi">
<span<?php echo $tbayartitipan->transaksi->ViewAttributes() ?>>
<?php echo $tbayartitipan->transaksi->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->referensi->Visible) { // referensi ?>
	<tr id="r_referensi">
		<td><span id="elh_tbayartitipan_referensi"><?php echo $tbayartitipan->referensi->FldCaption() ?></span></td>
		<td data-name="referensi"<?php echo $tbayartitipan->referensi->CellAttributes() ?>>
<span id="el_tbayartitipan_referensi">
<span<?php echo $tbayartitipan->referensi->ViewAttributes() ?>>
<?php echo $tbayartitipan->referensi->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->anggota->Visible) { // anggota ?>
	<tr id="r_anggota">
		<td><span id="elh_tbayartitipan_anggota"><?php echo $tbayartitipan->anggota->FldCaption() ?></span></td>
		<td data-name="anggota"<?php echo $tbayartitipan->anggota->CellAttributes() ?>>
<span id="el_tbayartitipan_anggota">
<span<?php echo $tbayartitipan->anggota->ViewAttributes() ?>>
<?php echo $tbayartitipan->anggota->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->namaanggota->Visible) { // namaanggota ?>
	<tr id="r_namaanggota">
		<td><span id="elh_tbayartitipan_namaanggota"><?php echo $tbayartitipan->namaanggota->FldCaption() ?></span></td>
		<td data-name="namaanggota"<?php echo $tbayartitipan->namaanggota->CellAttributes() ?>>
<span id="el_tbayartitipan_namaanggota">
<span<?php echo $tbayartitipan->namaanggota->ViewAttributes() ?>>
<?php echo $tbayartitipan->namaanggota->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->alamat->Visible) { // alamat ?>
	<tr id="r_alamat">
		<td><span id="elh_tbayartitipan_alamat"><?php echo $tbayartitipan->alamat->FldCaption() ?></span></td>
		<td data-name="alamat"<?php echo $tbayartitipan->alamat->CellAttributes() ?>>
<span id="el_tbayartitipan_alamat">
<span<?php echo $tbayartitipan->alamat->ViewAttributes() ?>>
<?php echo $tbayartitipan->alamat->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->pekerjaan->Visible) { // pekerjaan ?>
	<tr id="r_pekerjaan">
		<td><span id="elh_tbayartitipan_pekerjaan"><?php echo $tbayartitipan->pekerjaan->FldCaption() ?></span></td>
		<td data-name="pekerjaan"<?php echo $tbayartitipan->pekerjaan->CellAttributes() ?>>
<span id="el_tbayartitipan_pekerjaan">
<span<?php echo $tbayartitipan->pekerjaan->ViewAttributes() ?>>
<?php echo $tbayartitipan->pekerjaan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->telepon->Visible) { // telepon ?>
	<tr id="r_telepon">
		<td><span id="elh_tbayartitipan_telepon"><?php echo $tbayartitipan->telepon->FldCaption() ?></span></td>
		<td data-name="telepon"<?php echo $tbayartitipan->telepon->CellAttributes() ?>>
<span id="el_tbayartitipan_telepon">
<span<?php echo $tbayartitipan->telepon->ViewAttributes() ?>>
<?php echo $tbayartitipan->telepon->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->hp->Visible) { // hp ?>
	<tr id="r_hp">
		<td><span id="elh_tbayartitipan_hp"><?php echo $tbayartitipan->hp->FldCaption() ?></span></td>
		<td data-name="hp"<?php echo $tbayartitipan->hp->CellAttributes() ?>>
<span id="el_tbayartitipan_hp">
<span<?php echo $tbayartitipan->hp->ViewAttributes() ?>>
<?php echo $tbayartitipan->hp->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->fax->Visible) { // fax ?>
	<tr id="r_fax">
		<td><span id="elh_tbayartitipan_fax"><?php echo $tbayartitipan->fax->FldCaption() ?></span></td>
		<td data-name="fax"<?php echo $tbayartitipan->fax->CellAttributes() ?>>
<span id="el_tbayartitipan_fax">
<span<?php echo $tbayartitipan->fax->ViewAttributes() ?>>
<?php echo $tbayartitipan->fax->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->_email->Visible) { // email ?>
	<tr id="r__email">
		<td><span id="elh_tbayartitipan__email"><?php echo $tbayartitipan->_email->FldCaption() ?></span></td>
		<td data-name="_email"<?php echo $tbayartitipan->_email->CellAttributes() ?>>
<span id="el_tbayartitipan__email">
<span<?php echo $tbayartitipan->_email->ViewAttributes() ?>>
<?php echo $tbayartitipan->_email->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->website->Visible) { // website ?>
	<tr id="r_website">
		<td><span id="elh_tbayartitipan_website"><?php echo $tbayartitipan->website->FldCaption() ?></span></td>
		<td data-name="website"<?php echo $tbayartitipan->website->CellAttributes() ?>>
<span id="el_tbayartitipan_website">
<span<?php echo $tbayartitipan->website->ViewAttributes() ?>>
<?php echo $tbayartitipan->website->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->jenisanggota->Visible) { // jenisanggota ?>
	<tr id="r_jenisanggota">
		<td><span id="elh_tbayartitipan_jenisanggota"><?php echo $tbayartitipan->jenisanggota->FldCaption() ?></span></td>
		<td data-name="jenisanggota"<?php echo $tbayartitipan->jenisanggota->CellAttributes() ?>>
<span id="el_tbayartitipan_jenisanggota">
<span<?php echo $tbayartitipan->jenisanggota->ViewAttributes() ?>>
<?php echo $tbayartitipan->jenisanggota->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->model->Visible) { // model ?>
	<tr id="r_model">
		<td><span id="elh_tbayartitipan_model"><?php echo $tbayartitipan->model->FldCaption() ?></span></td>
		<td data-name="model"<?php echo $tbayartitipan->model->CellAttributes() ?>>
<span id="el_tbayartitipan_model">
<span<?php echo $tbayartitipan->model->ViewAttributes() ?>>
<?php echo $tbayartitipan->model->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->jenispinjaman->Visible) { // jenispinjaman ?>
	<tr id="r_jenispinjaman">
		<td><span id="elh_tbayartitipan_jenispinjaman"><?php echo $tbayartitipan->jenispinjaman->FldCaption() ?></span></td>
		<td data-name="jenispinjaman"<?php echo $tbayartitipan->jenispinjaman->CellAttributes() ?>>
<span id="el_tbayartitipan_jenispinjaman">
<span<?php echo $tbayartitipan->jenispinjaman->ViewAttributes() ?>>
<?php echo $tbayartitipan->jenispinjaman->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->jenisbunga->Visible) { // jenisbunga ?>
	<tr id="r_jenisbunga">
		<td><span id="elh_tbayartitipan_jenisbunga"><?php echo $tbayartitipan->jenisbunga->FldCaption() ?></span></td>
		<td data-name="jenisbunga"<?php echo $tbayartitipan->jenisbunga->CellAttributes() ?>>
<span id="el_tbayartitipan_jenisbunga">
<span<?php echo $tbayartitipan->jenisbunga->ViewAttributes() ?>>
<?php echo $tbayartitipan->jenisbunga->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->angsuran->Visible) { // angsuran ?>
	<tr id="r_angsuran">
		<td><span id="elh_tbayartitipan_angsuran"><?php echo $tbayartitipan->angsuran->FldCaption() ?></span></td>
		<td data-name="angsuran"<?php echo $tbayartitipan->angsuran->CellAttributes() ?>>
<span id="el_tbayartitipan_angsuran">
<span<?php echo $tbayartitipan->angsuran->ViewAttributes() ?>>
<?php echo $tbayartitipan->angsuran->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->masaangsuran->Visible) { // masaangsuran ?>
	<tr id="r_masaangsuran">
		<td><span id="elh_tbayartitipan_masaangsuran"><?php echo $tbayartitipan->masaangsuran->FldCaption() ?></span></td>
		<td data-name="masaangsuran"<?php echo $tbayartitipan->masaangsuran->CellAttributes() ?>>
<span id="el_tbayartitipan_masaangsuran">
<span<?php echo $tbayartitipan->masaangsuran->ViewAttributes() ?>>
<?php echo $tbayartitipan->masaangsuran->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->jatuhtempo->Visible) { // jatuhtempo ?>
	<tr id="r_jatuhtempo">
		<td><span id="elh_tbayartitipan_jatuhtempo"><?php echo $tbayartitipan->jatuhtempo->FldCaption() ?></span></td>
		<td data-name="jatuhtempo"<?php echo $tbayartitipan->jatuhtempo->CellAttributes() ?>>
<span id="el_tbayartitipan_jatuhtempo">
<span<?php echo $tbayartitipan->jatuhtempo->ViewAttributes() ?>>
<?php echo $tbayartitipan->jatuhtempo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->dispensasidenda->Visible) { // dispensasidenda ?>
	<tr id="r_dispensasidenda">
		<td><span id="elh_tbayartitipan_dispensasidenda"><?php echo $tbayartitipan->dispensasidenda->FldCaption() ?></span></td>
		<td data-name="dispensasidenda"<?php echo $tbayartitipan->dispensasidenda->CellAttributes() ?>>
<span id="el_tbayartitipan_dispensasidenda">
<span<?php echo $tbayartitipan->dispensasidenda->ViewAttributes() ?>>
<?php echo $tbayartitipan->dispensasidenda->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->titipan->Visible) { // titipan ?>
	<tr id="r_titipan">
		<td><span id="elh_tbayartitipan_titipan"><?php echo $tbayartitipan->titipan->FldCaption() ?></span></td>
		<td data-name="titipan"<?php echo $tbayartitipan->titipan->CellAttributes() ?>>
<span id="el_tbayartitipan_titipan">
<span<?php echo $tbayartitipan->titipan->ViewAttributes() ?>>
<?php echo $tbayartitipan->titipan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->bayartitipan->Visible) { // bayartitipan ?>
	<tr id="r_bayartitipan">
		<td><span id="elh_tbayartitipan_bayartitipan"><?php echo $tbayartitipan->bayartitipan->FldCaption() ?></span></td>
		<td data-name="bayartitipan"<?php echo $tbayartitipan->bayartitipan->CellAttributes() ?>>
<span id="el_tbayartitipan_bayartitipan">
<span<?php echo $tbayartitipan->bayartitipan->ViewAttributes() ?>>
<?php echo $tbayartitipan->bayartitipan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->bayartitipanauto->Visible) { // bayartitipanauto ?>
	<tr id="r_bayartitipanauto">
		<td><span id="elh_tbayartitipan_bayartitipanauto"><?php echo $tbayartitipan->bayartitipanauto->FldCaption() ?></span></td>
		<td data-name="bayartitipanauto"<?php echo $tbayartitipan->bayartitipanauto->CellAttributes() ?>>
<span id="el_tbayartitipan_bayartitipanauto">
<span<?php echo $tbayartitipan->bayartitipanauto->ViewAttributes() ?>>
<?php echo $tbayartitipan->bayartitipanauto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->terbilang->Visible) { // terbilang ?>
	<tr id="r_terbilang">
		<td><span id="elh_tbayartitipan_terbilang"><?php echo $tbayartitipan->terbilang->FldCaption() ?></span></td>
		<td data-name="terbilang"<?php echo $tbayartitipan->terbilang->CellAttributes() ?>>
<span id="el_tbayartitipan_terbilang">
<span<?php echo $tbayartitipan->terbilang->ViewAttributes() ?>>
<?php echo $tbayartitipan->terbilang->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->petugas->Visible) { // petugas ?>
	<tr id="r_petugas">
		<td><span id="elh_tbayartitipan_petugas"><?php echo $tbayartitipan->petugas->FldCaption() ?></span></td>
		<td data-name="petugas"<?php echo $tbayartitipan->petugas->CellAttributes() ?>>
<span id="el_tbayartitipan_petugas">
<span<?php echo $tbayartitipan->petugas->ViewAttributes() ?>>
<?php echo $tbayartitipan->petugas->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->pembayaran->Visible) { // pembayaran ?>
	<tr id="r_pembayaran">
		<td><span id="elh_tbayartitipan_pembayaran"><?php echo $tbayartitipan->pembayaran->FldCaption() ?></span></td>
		<td data-name="pembayaran"<?php echo $tbayartitipan->pembayaran->CellAttributes() ?>>
<span id="el_tbayartitipan_pembayaran">
<span<?php echo $tbayartitipan->pembayaran->ViewAttributes() ?>>
<?php echo $tbayartitipan->pembayaran->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->bank->Visible) { // bank ?>
	<tr id="r_bank">
		<td><span id="elh_tbayartitipan_bank"><?php echo $tbayartitipan->bank->FldCaption() ?></span></td>
		<td data-name="bank"<?php echo $tbayartitipan->bank->CellAttributes() ?>>
<span id="el_tbayartitipan_bank">
<span<?php echo $tbayartitipan->bank->ViewAttributes() ?>>
<?php echo $tbayartitipan->bank->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->atasnama->Visible) { // atasnama ?>
	<tr id="r_atasnama">
		<td><span id="elh_tbayartitipan_atasnama"><?php echo $tbayartitipan->atasnama->FldCaption() ?></span></td>
		<td data-name="atasnama"<?php echo $tbayartitipan->atasnama->CellAttributes() ?>>
<span id="el_tbayartitipan_atasnama">
<span<?php echo $tbayartitipan->atasnama->ViewAttributes() ?>>
<?php echo $tbayartitipan->atasnama->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->tipe->Visible) { // tipe ?>
	<tr id="r_tipe">
		<td><span id="elh_tbayartitipan_tipe"><?php echo $tbayartitipan->tipe->FldCaption() ?></span></td>
		<td data-name="tipe"<?php echo $tbayartitipan->tipe->CellAttributes() ?>>
<span id="el_tbayartitipan_tipe">
<span<?php echo $tbayartitipan->tipe->ViewAttributes() ?>>
<?php echo $tbayartitipan->tipe->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->kantor->Visible) { // kantor ?>
	<tr id="r_kantor">
		<td><span id="elh_tbayartitipan_kantor"><?php echo $tbayartitipan->kantor->FldCaption() ?></span></td>
		<td data-name="kantor"<?php echo $tbayartitipan->kantor->CellAttributes() ?>>
<span id="el_tbayartitipan_kantor">
<span<?php echo $tbayartitipan->kantor->ViewAttributes() ?>>
<?php echo $tbayartitipan->kantor->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->keterangan->Visible) { // keterangan ?>
	<tr id="r_keterangan">
		<td><span id="elh_tbayartitipan_keterangan"><?php echo $tbayartitipan->keterangan->FldCaption() ?></span></td>
		<td data-name="keterangan"<?php echo $tbayartitipan->keterangan->CellAttributes() ?>>
<span id="el_tbayartitipan_keterangan">
<span<?php echo $tbayartitipan->keterangan->ViewAttributes() ?>>
<?php echo $tbayartitipan->keterangan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->active->Visible) { // active ?>
	<tr id="r_active">
		<td><span id="elh_tbayartitipan_active"><?php echo $tbayartitipan->active->FldCaption() ?></span></td>
		<td data-name="active"<?php echo $tbayartitipan->active->CellAttributes() ?>>
<span id="el_tbayartitipan_active">
<span<?php echo $tbayartitipan->active->ViewAttributes() ?>>
<?php echo $tbayartitipan->active->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->ip->Visible) { // ip ?>
	<tr id="r_ip">
		<td><span id="elh_tbayartitipan_ip"><?php echo $tbayartitipan->ip->FldCaption() ?></span></td>
		<td data-name="ip"<?php echo $tbayartitipan->ip->CellAttributes() ?>>
<span id="el_tbayartitipan_ip">
<span<?php echo $tbayartitipan->ip->ViewAttributes() ?>>
<?php echo $tbayartitipan->ip->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->status->Visible) { // status ?>
	<tr id="r_status">
		<td><span id="elh_tbayartitipan_status"><?php echo $tbayartitipan->status->FldCaption() ?></span></td>
		<td data-name="status"<?php echo $tbayartitipan->status->CellAttributes() ?>>
<span id="el_tbayartitipan_status">
<span<?php echo $tbayartitipan->status->ViewAttributes() ?>>
<?php echo $tbayartitipan->status->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->user->Visible) { // user ?>
	<tr id="r_user">
		<td><span id="elh_tbayartitipan_user"><?php echo $tbayartitipan->user->FldCaption() ?></span></td>
		<td data-name="user"<?php echo $tbayartitipan->user->CellAttributes() ?>>
<span id="el_tbayartitipan_user">
<span<?php echo $tbayartitipan->user->ViewAttributes() ?>>
<?php echo $tbayartitipan->user->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->created->Visible) { // created ?>
	<tr id="r_created">
		<td><span id="elh_tbayartitipan_created"><?php echo $tbayartitipan->created->FldCaption() ?></span></td>
		<td data-name="created"<?php echo $tbayartitipan->created->CellAttributes() ?>>
<span id="el_tbayartitipan_created">
<span<?php echo $tbayartitipan->created->ViewAttributes() ?>>
<?php echo $tbayartitipan->created->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tbayartitipan->modified->Visible) { // modified ?>
	<tr id="r_modified">
		<td><span id="elh_tbayartitipan_modified"><?php echo $tbayartitipan->modified->FldCaption() ?></span></td>
		<td data-name="modified"<?php echo $tbayartitipan->modified->CellAttributes() ?>>
<span id="el_tbayartitipan_modified">
<span<?php echo $tbayartitipan->modified->ViewAttributes() ?>>
<?php echo $tbayartitipan->modified->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
ftbayartitipanview.Init();
</script>
<?php
$tbayartitipan_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tbayartitipan_view->Page_Terminate();
?>
