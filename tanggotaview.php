<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "tanggotainfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$tanggota_view = NULL; // Initialize page object first

class ctanggota_view extends ctanggota {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tanggota';

	// Page object name
	var $PageObjName = 'tanggota_view';

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

		// Table object (tanggota)
		if (!isset($GLOBALS["tanggota"]) || get_class($GLOBALS["tanggota"]) == "ctanggota") {
			$GLOBALS["tanggota"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tanggota"];
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
			define("EW_TABLE_NAME", 'tanggota', TRUE);

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
		$this->registrasi->SetVisibility();
		$this->periode->SetVisibility();
		$this->id->SetVisibility();
		$this->anggota->SetVisibility();
		$this->namaanggota->SetVisibility();
		$this->alamat->SetVisibility();
		$this->tempatlahir->SetVisibility();
		$this->tanggallahir->SetVisibility();
		$this->kelamin->SetVisibility();
		$this->pekerjaan->SetVisibility();
		$this->telepon->SetVisibility();
		$this->hp->SetVisibility();
		$this->fax->SetVisibility();
		$this->_email->SetVisibility();
		$this->website->SetVisibility();
		$this->jenisanggota->SetVisibility();
		$this->model->SetVisibility();
		$this->namakantor->SetVisibility();
		$this->alamatkantor->SetVisibility();
		$this->wilayah->SetVisibility();
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
		global $EW_EXPORT, $tanggota;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($tanggota);
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
				$sReturnUrl = "tanggotalist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "tanggotalist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "tanggotalist.php"; // Not page request, return to list
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
		$this->registrasi->setDbValue($rs->fields('registrasi'));
		$this->periode->setDbValue($rs->fields('periode'));
		$this->id->setDbValue($rs->fields('id'));
		$this->anggota->setDbValue($rs->fields('anggota'));
		$this->namaanggota->setDbValue($rs->fields('namaanggota'));
		$this->alamat->setDbValue($rs->fields('alamat'));
		$this->tempatlahir->setDbValue($rs->fields('tempatlahir'));
		$this->tanggallahir->setDbValue($rs->fields('tanggallahir'));
		$this->kelamin->setDbValue($rs->fields('kelamin'));
		$this->pekerjaan->setDbValue($rs->fields('pekerjaan'));
		$this->telepon->setDbValue($rs->fields('telepon'));
		$this->hp->setDbValue($rs->fields('hp'));
		$this->fax->setDbValue($rs->fields('fax'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->website->setDbValue($rs->fields('website'));
		$this->jenisanggota->setDbValue($rs->fields('jenisanggota'));
		$this->model->setDbValue($rs->fields('model'));
		$this->namakantor->setDbValue($rs->fields('namakantor'));
		$this->alamatkantor->setDbValue($rs->fields('alamatkantor'));
		$this->wilayah->setDbValue($rs->fields('wilayah'));
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
		$this->registrasi->DbValue = $row['registrasi'];
		$this->periode->DbValue = $row['periode'];
		$this->id->DbValue = $row['id'];
		$this->anggota->DbValue = $row['anggota'];
		$this->namaanggota->DbValue = $row['namaanggota'];
		$this->alamat->DbValue = $row['alamat'];
		$this->tempatlahir->DbValue = $row['tempatlahir'];
		$this->tanggallahir->DbValue = $row['tanggallahir'];
		$this->kelamin->DbValue = $row['kelamin'];
		$this->pekerjaan->DbValue = $row['pekerjaan'];
		$this->telepon->DbValue = $row['telepon'];
		$this->hp->DbValue = $row['hp'];
		$this->fax->DbValue = $row['fax'];
		$this->_email->DbValue = $row['email'];
		$this->website->DbValue = $row['website'];
		$this->jenisanggota->DbValue = $row['jenisanggota'];
		$this->model->DbValue = $row['model'];
		$this->namakantor->DbValue = $row['namakantor'];
		$this->alamatkantor->DbValue = $row['alamatkantor'];
		$this->wilayah->DbValue = $row['wilayah'];
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

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// registrasi
		// periode
		// id
		// anggota
		// namaanggota
		// alamat
		// tempatlahir
		// tanggallahir
		// kelamin
		// pekerjaan
		// telepon
		// hp
		// fax
		// email
		// website
		// jenisanggota
		// model
		// namakantor
		// alamatkantor
		// wilayah
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

		// registrasi
		$this->registrasi->ViewValue = $this->registrasi->CurrentValue;
		$this->registrasi->ViewValue = ew_FormatDateTime($this->registrasi->ViewValue, 0);
		$this->registrasi->ViewCustomAttributes = "";

		// periode
		$this->periode->ViewValue = $this->periode->CurrentValue;
		$this->periode->ViewCustomAttributes = "";

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// anggota
		$this->anggota->ViewValue = $this->anggota->CurrentValue;
		$this->anggota->ViewCustomAttributes = "";

		// namaanggota
		$this->namaanggota->ViewValue = $this->namaanggota->CurrentValue;
		$this->namaanggota->ViewCustomAttributes = "";

		// alamat
		$this->alamat->ViewValue = $this->alamat->CurrentValue;
		$this->alamat->ViewCustomAttributes = "";

		// tempatlahir
		$this->tempatlahir->ViewValue = $this->tempatlahir->CurrentValue;
		$this->tempatlahir->ViewCustomAttributes = "";

		// tanggallahir
		$this->tanggallahir->ViewValue = $this->tanggallahir->CurrentValue;
		$this->tanggallahir->ViewValue = ew_FormatDateTime($this->tanggallahir->ViewValue, 0);
		$this->tanggallahir->ViewCustomAttributes = "";

		// kelamin
		$this->kelamin->ViewValue = $this->kelamin->CurrentValue;
		$this->kelamin->ViewCustomAttributes = "";

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

		// namakantor
		$this->namakantor->ViewValue = $this->namakantor->CurrentValue;
		$this->namakantor->ViewCustomAttributes = "";

		// alamatkantor
		$this->alamatkantor->ViewValue = $this->alamatkantor->CurrentValue;
		$this->alamatkantor->ViewCustomAttributes = "";

		// wilayah
		$this->wilayah->ViewValue = $this->wilayah->CurrentValue;
		$this->wilayah->ViewCustomAttributes = "";

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

			// registrasi
			$this->registrasi->LinkCustomAttributes = "";
			$this->registrasi->HrefValue = "";
			$this->registrasi->TooltipValue = "";

			// periode
			$this->periode->LinkCustomAttributes = "";
			$this->periode->HrefValue = "";
			$this->periode->TooltipValue = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

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

			// tempatlahir
			$this->tempatlahir->LinkCustomAttributes = "";
			$this->tempatlahir->HrefValue = "";
			$this->tempatlahir->TooltipValue = "";

			// tanggallahir
			$this->tanggallahir->LinkCustomAttributes = "";
			$this->tanggallahir->HrefValue = "";
			$this->tanggallahir->TooltipValue = "";

			// kelamin
			$this->kelamin->LinkCustomAttributes = "";
			$this->kelamin->HrefValue = "";
			$this->kelamin->TooltipValue = "";

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

			// namakantor
			$this->namakantor->LinkCustomAttributes = "";
			$this->namakantor->HrefValue = "";
			$this->namakantor->TooltipValue = "";

			// alamatkantor
			$this->alamatkantor->LinkCustomAttributes = "";
			$this->alamatkantor->HrefValue = "";
			$this->alamatkantor->TooltipValue = "";

			// wilayah
			$this->wilayah->LinkCustomAttributes = "";
			$this->wilayah->HrefValue = "";
			$this->wilayah->TooltipValue = "";

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("tanggotalist.php"), "", $this->TableVar, TRUE);
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
if (!isset($tanggota_view)) $tanggota_view = new ctanggota_view();

// Page init
$tanggota_view->Page_Init();

// Page main
$tanggota_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tanggota_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = ftanggotaview = new ew_Form("ftanggotaview", "view");

// Form_CustomValidate event
ftanggotaview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftanggotaview.ValidateRequired = true;
<?php } else { ?>
ftanggotaview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftanggotaview.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftanggotaview.Lists["x_active"].Options = <?php echo json_encode($tanggota->active->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if (!$tanggota_view->IsModal) { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php $tanggota_view->ExportOptions->Render("body") ?>
<?php
	foreach ($tanggota_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if (!$tanggota_view->IsModal) { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php $tanggota_view->ShowPageHeader(); ?>
<?php
$tanggota_view->ShowMessage();
?>
<form name="ftanggotaview" id="ftanggotaview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tanggota_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tanggota_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tanggota">
<?php if ($tanggota_view->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($tanggota->registrasi->Visible) { // registrasi ?>
	<tr id="r_registrasi">
		<td><span id="elh_tanggota_registrasi"><?php echo $tanggota->registrasi->FldCaption() ?></span></td>
		<td data-name="registrasi"<?php echo $tanggota->registrasi->CellAttributes() ?>>
<span id="el_tanggota_registrasi">
<span<?php echo $tanggota->registrasi->ViewAttributes() ?>>
<?php echo $tanggota->registrasi->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->periode->Visible) { // periode ?>
	<tr id="r_periode">
		<td><span id="elh_tanggota_periode"><?php echo $tanggota->periode->FldCaption() ?></span></td>
		<td data-name="periode"<?php echo $tanggota->periode->CellAttributes() ?>>
<span id="el_tanggota_periode">
<span<?php echo $tanggota->periode->ViewAttributes() ?>>
<?php echo $tanggota->periode->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->id->Visible) { // id ?>
	<tr id="r_id">
		<td><span id="elh_tanggota_id"><?php echo $tanggota->id->FldCaption() ?></span></td>
		<td data-name="id"<?php echo $tanggota->id->CellAttributes() ?>>
<span id="el_tanggota_id">
<span<?php echo $tanggota->id->ViewAttributes() ?>>
<?php echo $tanggota->id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->anggota->Visible) { // anggota ?>
	<tr id="r_anggota">
		<td><span id="elh_tanggota_anggota"><?php echo $tanggota->anggota->FldCaption() ?></span></td>
		<td data-name="anggota"<?php echo $tanggota->anggota->CellAttributes() ?>>
<span id="el_tanggota_anggota">
<span<?php echo $tanggota->anggota->ViewAttributes() ?>>
<?php echo $tanggota->anggota->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->namaanggota->Visible) { // namaanggota ?>
	<tr id="r_namaanggota">
		<td><span id="elh_tanggota_namaanggota"><?php echo $tanggota->namaanggota->FldCaption() ?></span></td>
		<td data-name="namaanggota"<?php echo $tanggota->namaanggota->CellAttributes() ?>>
<span id="el_tanggota_namaanggota">
<span<?php echo $tanggota->namaanggota->ViewAttributes() ?>>
<?php echo $tanggota->namaanggota->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->alamat->Visible) { // alamat ?>
	<tr id="r_alamat">
		<td><span id="elh_tanggota_alamat"><?php echo $tanggota->alamat->FldCaption() ?></span></td>
		<td data-name="alamat"<?php echo $tanggota->alamat->CellAttributes() ?>>
<span id="el_tanggota_alamat">
<span<?php echo $tanggota->alamat->ViewAttributes() ?>>
<?php echo $tanggota->alamat->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->tempatlahir->Visible) { // tempatlahir ?>
	<tr id="r_tempatlahir">
		<td><span id="elh_tanggota_tempatlahir"><?php echo $tanggota->tempatlahir->FldCaption() ?></span></td>
		<td data-name="tempatlahir"<?php echo $tanggota->tempatlahir->CellAttributes() ?>>
<span id="el_tanggota_tempatlahir">
<span<?php echo $tanggota->tempatlahir->ViewAttributes() ?>>
<?php echo $tanggota->tempatlahir->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->tanggallahir->Visible) { // tanggallahir ?>
	<tr id="r_tanggallahir">
		<td><span id="elh_tanggota_tanggallahir"><?php echo $tanggota->tanggallahir->FldCaption() ?></span></td>
		<td data-name="tanggallahir"<?php echo $tanggota->tanggallahir->CellAttributes() ?>>
<span id="el_tanggota_tanggallahir">
<span<?php echo $tanggota->tanggallahir->ViewAttributes() ?>>
<?php echo $tanggota->tanggallahir->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->kelamin->Visible) { // kelamin ?>
	<tr id="r_kelamin">
		<td><span id="elh_tanggota_kelamin"><?php echo $tanggota->kelamin->FldCaption() ?></span></td>
		<td data-name="kelamin"<?php echo $tanggota->kelamin->CellAttributes() ?>>
<span id="el_tanggota_kelamin">
<span<?php echo $tanggota->kelamin->ViewAttributes() ?>>
<?php echo $tanggota->kelamin->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->pekerjaan->Visible) { // pekerjaan ?>
	<tr id="r_pekerjaan">
		<td><span id="elh_tanggota_pekerjaan"><?php echo $tanggota->pekerjaan->FldCaption() ?></span></td>
		<td data-name="pekerjaan"<?php echo $tanggota->pekerjaan->CellAttributes() ?>>
<span id="el_tanggota_pekerjaan">
<span<?php echo $tanggota->pekerjaan->ViewAttributes() ?>>
<?php echo $tanggota->pekerjaan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->telepon->Visible) { // telepon ?>
	<tr id="r_telepon">
		<td><span id="elh_tanggota_telepon"><?php echo $tanggota->telepon->FldCaption() ?></span></td>
		<td data-name="telepon"<?php echo $tanggota->telepon->CellAttributes() ?>>
<span id="el_tanggota_telepon">
<span<?php echo $tanggota->telepon->ViewAttributes() ?>>
<?php echo $tanggota->telepon->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->hp->Visible) { // hp ?>
	<tr id="r_hp">
		<td><span id="elh_tanggota_hp"><?php echo $tanggota->hp->FldCaption() ?></span></td>
		<td data-name="hp"<?php echo $tanggota->hp->CellAttributes() ?>>
<span id="el_tanggota_hp">
<span<?php echo $tanggota->hp->ViewAttributes() ?>>
<?php echo $tanggota->hp->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->fax->Visible) { // fax ?>
	<tr id="r_fax">
		<td><span id="elh_tanggota_fax"><?php echo $tanggota->fax->FldCaption() ?></span></td>
		<td data-name="fax"<?php echo $tanggota->fax->CellAttributes() ?>>
<span id="el_tanggota_fax">
<span<?php echo $tanggota->fax->ViewAttributes() ?>>
<?php echo $tanggota->fax->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->_email->Visible) { // email ?>
	<tr id="r__email">
		<td><span id="elh_tanggota__email"><?php echo $tanggota->_email->FldCaption() ?></span></td>
		<td data-name="_email"<?php echo $tanggota->_email->CellAttributes() ?>>
<span id="el_tanggota__email">
<span<?php echo $tanggota->_email->ViewAttributes() ?>>
<?php echo $tanggota->_email->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->website->Visible) { // website ?>
	<tr id="r_website">
		<td><span id="elh_tanggota_website"><?php echo $tanggota->website->FldCaption() ?></span></td>
		<td data-name="website"<?php echo $tanggota->website->CellAttributes() ?>>
<span id="el_tanggota_website">
<span<?php echo $tanggota->website->ViewAttributes() ?>>
<?php echo $tanggota->website->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->jenisanggota->Visible) { // jenisanggota ?>
	<tr id="r_jenisanggota">
		<td><span id="elh_tanggota_jenisanggota"><?php echo $tanggota->jenisanggota->FldCaption() ?></span></td>
		<td data-name="jenisanggota"<?php echo $tanggota->jenisanggota->CellAttributes() ?>>
<span id="el_tanggota_jenisanggota">
<span<?php echo $tanggota->jenisanggota->ViewAttributes() ?>>
<?php echo $tanggota->jenisanggota->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->model->Visible) { // model ?>
	<tr id="r_model">
		<td><span id="elh_tanggota_model"><?php echo $tanggota->model->FldCaption() ?></span></td>
		<td data-name="model"<?php echo $tanggota->model->CellAttributes() ?>>
<span id="el_tanggota_model">
<span<?php echo $tanggota->model->ViewAttributes() ?>>
<?php echo $tanggota->model->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->namakantor->Visible) { // namakantor ?>
	<tr id="r_namakantor">
		<td><span id="elh_tanggota_namakantor"><?php echo $tanggota->namakantor->FldCaption() ?></span></td>
		<td data-name="namakantor"<?php echo $tanggota->namakantor->CellAttributes() ?>>
<span id="el_tanggota_namakantor">
<span<?php echo $tanggota->namakantor->ViewAttributes() ?>>
<?php echo $tanggota->namakantor->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->alamatkantor->Visible) { // alamatkantor ?>
	<tr id="r_alamatkantor">
		<td><span id="elh_tanggota_alamatkantor"><?php echo $tanggota->alamatkantor->FldCaption() ?></span></td>
		<td data-name="alamatkantor"<?php echo $tanggota->alamatkantor->CellAttributes() ?>>
<span id="el_tanggota_alamatkantor">
<span<?php echo $tanggota->alamatkantor->ViewAttributes() ?>>
<?php echo $tanggota->alamatkantor->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->wilayah->Visible) { // wilayah ?>
	<tr id="r_wilayah">
		<td><span id="elh_tanggota_wilayah"><?php echo $tanggota->wilayah->FldCaption() ?></span></td>
		<td data-name="wilayah"<?php echo $tanggota->wilayah->CellAttributes() ?>>
<span id="el_tanggota_wilayah">
<span<?php echo $tanggota->wilayah->ViewAttributes() ?>>
<?php echo $tanggota->wilayah->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->petugas->Visible) { // petugas ?>
	<tr id="r_petugas">
		<td><span id="elh_tanggota_petugas"><?php echo $tanggota->petugas->FldCaption() ?></span></td>
		<td data-name="petugas"<?php echo $tanggota->petugas->CellAttributes() ?>>
<span id="el_tanggota_petugas">
<span<?php echo $tanggota->petugas->ViewAttributes() ?>>
<?php echo $tanggota->petugas->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->pembayaran->Visible) { // pembayaran ?>
	<tr id="r_pembayaran">
		<td><span id="elh_tanggota_pembayaran"><?php echo $tanggota->pembayaran->FldCaption() ?></span></td>
		<td data-name="pembayaran"<?php echo $tanggota->pembayaran->CellAttributes() ?>>
<span id="el_tanggota_pembayaran">
<span<?php echo $tanggota->pembayaran->ViewAttributes() ?>>
<?php echo $tanggota->pembayaran->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->bank->Visible) { // bank ?>
	<tr id="r_bank">
		<td><span id="elh_tanggota_bank"><?php echo $tanggota->bank->FldCaption() ?></span></td>
		<td data-name="bank"<?php echo $tanggota->bank->CellAttributes() ?>>
<span id="el_tanggota_bank">
<span<?php echo $tanggota->bank->ViewAttributes() ?>>
<?php echo $tanggota->bank->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->atasnama->Visible) { // atasnama ?>
	<tr id="r_atasnama">
		<td><span id="elh_tanggota_atasnama"><?php echo $tanggota->atasnama->FldCaption() ?></span></td>
		<td data-name="atasnama"<?php echo $tanggota->atasnama->CellAttributes() ?>>
<span id="el_tanggota_atasnama">
<span<?php echo $tanggota->atasnama->ViewAttributes() ?>>
<?php echo $tanggota->atasnama->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->tipe->Visible) { // tipe ?>
	<tr id="r_tipe">
		<td><span id="elh_tanggota_tipe"><?php echo $tanggota->tipe->FldCaption() ?></span></td>
		<td data-name="tipe"<?php echo $tanggota->tipe->CellAttributes() ?>>
<span id="el_tanggota_tipe">
<span<?php echo $tanggota->tipe->ViewAttributes() ?>>
<?php echo $tanggota->tipe->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->kantor->Visible) { // kantor ?>
	<tr id="r_kantor">
		<td><span id="elh_tanggota_kantor"><?php echo $tanggota->kantor->FldCaption() ?></span></td>
		<td data-name="kantor"<?php echo $tanggota->kantor->CellAttributes() ?>>
<span id="el_tanggota_kantor">
<span<?php echo $tanggota->kantor->ViewAttributes() ?>>
<?php echo $tanggota->kantor->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->keterangan->Visible) { // keterangan ?>
	<tr id="r_keterangan">
		<td><span id="elh_tanggota_keterangan"><?php echo $tanggota->keterangan->FldCaption() ?></span></td>
		<td data-name="keterangan"<?php echo $tanggota->keterangan->CellAttributes() ?>>
<span id="el_tanggota_keterangan">
<span<?php echo $tanggota->keterangan->ViewAttributes() ?>>
<?php echo $tanggota->keterangan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->active->Visible) { // active ?>
	<tr id="r_active">
		<td><span id="elh_tanggota_active"><?php echo $tanggota->active->FldCaption() ?></span></td>
		<td data-name="active"<?php echo $tanggota->active->CellAttributes() ?>>
<span id="el_tanggota_active">
<span<?php echo $tanggota->active->ViewAttributes() ?>>
<?php echo $tanggota->active->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->ip->Visible) { // ip ?>
	<tr id="r_ip">
		<td><span id="elh_tanggota_ip"><?php echo $tanggota->ip->FldCaption() ?></span></td>
		<td data-name="ip"<?php echo $tanggota->ip->CellAttributes() ?>>
<span id="el_tanggota_ip">
<span<?php echo $tanggota->ip->ViewAttributes() ?>>
<?php echo $tanggota->ip->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->status->Visible) { // status ?>
	<tr id="r_status">
		<td><span id="elh_tanggota_status"><?php echo $tanggota->status->FldCaption() ?></span></td>
		<td data-name="status"<?php echo $tanggota->status->CellAttributes() ?>>
<span id="el_tanggota_status">
<span<?php echo $tanggota->status->ViewAttributes() ?>>
<?php echo $tanggota->status->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->user->Visible) { // user ?>
	<tr id="r_user">
		<td><span id="elh_tanggota_user"><?php echo $tanggota->user->FldCaption() ?></span></td>
		<td data-name="user"<?php echo $tanggota->user->CellAttributes() ?>>
<span id="el_tanggota_user">
<span<?php echo $tanggota->user->ViewAttributes() ?>>
<?php echo $tanggota->user->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->created->Visible) { // created ?>
	<tr id="r_created">
		<td><span id="elh_tanggota_created"><?php echo $tanggota->created->FldCaption() ?></span></td>
		<td data-name="created"<?php echo $tanggota->created->CellAttributes() ?>>
<span id="el_tanggota_created">
<span<?php echo $tanggota->created->ViewAttributes() ?>>
<?php echo $tanggota->created->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tanggota->modified->Visible) { // modified ?>
	<tr id="r_modified">
		<td><span id="elh_tanggota_modified"><?php echo $tanggota->modified->FldCaption() ?></span></td>
		<td data-name="modified"<?php echo $tanggota->modified->CellAttributes() ?>>
<span id="el_tanggota_modified">
<span<?php echo $tanggota->modified->ViewAttributes() ?>>
<?php echo $tanggota->modified->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
ftanggotaview.Init();
</script>
<?php
$tanggota_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tanggota_view->Page_Terminate();
?>
