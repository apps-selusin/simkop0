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

$tjurnaltransaksi_edit = NULL; // Initialize page object first

class ctjurnaltransaksi_edit extends ctjurnaltransaksi {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tjurnaltransaksi';

	// Page object name
	var $PageObjName = 'tjurnaltransaksi_edit';

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

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tjurnaltransaksi', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Create form object
		$objForm = new cFormObj();
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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
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
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Load key from QueryString
		if (@$_GET["id"] <> "") {
			$this->id->setQueryStringValue($_GET["id"]);
		}
		if (@$_GET["nomor"] <> "") {
			$this->nomor->setQueryStringValue($_GET["nomor"]);
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->id->CurrentValue == "") {
			$this->Page_Terminate("tjurnaltransaksilist.php"); // Invalid key, return to list
		}
		if ($this->nomor->CurrentValue == "") {
			$this->Page_Terminate("tjurnaltransaksilist.php"); // Invalid key, return to list
		}

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("tjurnaltransaksilist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "tjurnaltransaksilist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
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

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->tanggal->FldIsDetailKey) {
			$this->tanggal->setFormValue($objForm->GetValue("x_tanggal"));
			$this->tanggal->CurrentValue = ew_UnFormatDateTime($this->tanggal->CurrentValue, 0);
		}
		if (!$this->periode->FldIsDetailKey) {
			$this->periode->setFormValue($objForm->GetValue("x_periode"));
		}
		if (!$this->id->FldIsDetailKey) {
			$this->id->setFormValue($objForm->GetValue("x_id"));
		}
		if (!$this->nomor->FldIsDetailKey) {
			$this->nomor->setFormValue($objForm->GetValue("x_nomor"));
		}
		if (!$this->transaksi->FldIsDetailKey) {
			$this->transaksi->setFormValue($objForm->GetValue("x_transaksi"));
		}
		if (!$this->referensi->FldIsDetailKey) {
			$this->referensi->setFormValue($objForm->GetValue("x_referensi"));
		}
		if (!$this->model->FldIsDetailKey) {
			$this->model->setFormValue($objForm->GetValue("x_model"));
		}
		if (!$this->rekening->FldIsDetailKey) {
			$this->rekening->setFormValue($objForm->GetValue("x_rekening"));
		}
		if (!$this->debet->FldIsDetailKey) {
			$this->debet->setFormValue($objForm->GetValue("x_debet"));
		}
		if (!$this->credit->FldIsDetailKey) {
			$this->credit->setFormValue($objForm->GetValue("x_credit"));
		}
		if (!$this->pembayaran_->FldIsDetailKey) {
			$this->pembayaran_->setFormValue($objForm->GetValue("x_pembayaran_"));
		}
		if (!$this->bunga_->FldIsDetailKey) {
			$this->bunga_->setFormValue($objForm->GetValue("x_bunga_"));
		}
		if (!$this->denda_->FldIsDetailKey) {
			$this->denda_->setFormValue($objForm->GetValue("x_denda_"));
		}
		if (!$this->titipan_->FldIsDetailKey) {
			$this->titipan_->setFormValue($objForm->GetValue("x_titipan_"));
		}
		if (!$this->administrasi_->FldIsDetailKey) {
			$this->administrasi_->setFormValue($objForm->GetValue("x_administrasi_"));
		}
		if (!$this->modal_->FldIsDetailKey) {
			$this->modal_->setFormValue($objForm->GetValue("x_modal_"));
		}
		if (!$this->pinjaman_->FldIsDetailKey) {
			$this->pinjaman_->setFormValue($objForm->GetValue("x_pinjaman_"));
		}
		if (!$this->biaya_->FldIsDetailKey) {
			$this->biaya_->setFormValue($objForm->GetValue("x_biaya_"));
		}
		if (!$this->kantor->FldIsDetailKey) {
			$this->kantor->setFormValue($objForm->GetValue("x_kantor"));
		}
		if (!$this->keterangan->FldIsDetailKey) {
			$this->keterangan->setFormValue($objForm->GetValue("x_keterangan"));
		}
		if (!$this->active->FldIsDetailKey) {
			$this->active->setFormValue($objForm->GetValue("x_active"));
		}
		if (!$this->ip->FldIsDetailKey) {
			$this->ip->setFormValue($objForm->GetValue("x_ip"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
		if (!$this->user->FldIsDetailKey) {
			$this->user->setFormValue($objForm->GetValue("x_user"));
		}
		if (!$this->created->FldIsDetailKey) {
			$this->created->setFormValue($objForm->GetValue("x_created"));
			$this->created->CurrentValue = ew_UnFormatDateTime($this->created->CurrentValue, 0);
		}
		if (!$this->modified->FldIsDetailKey) {
			$this->modified->setFormValue($objForm->GetValue("x_modified"));
			$this->modified->CurrentValue = ew_UnFormatDateTime($this->modified->CurrentValue, 0);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->tanggal->CurrentValue = $this->tanggal->FormValue;
		$this->tanggal->CurrentValue = ew_UnFormatDateTime($this->tanggal->CurrentValue, 0);
		$this->periode->CurrentValue = $this->periode->FormValue;
		$this->id->CurrentValue = $this->id->FormValue;
		$this->nomor->CurrentValue = $this->nomor->FormValue;
		$this->transaksi->CurrentValue = $this->transaksi->FormValue;
		$this->referensi->CurrentValue = $this->referensi->FormValue;
		$this->model->CurrentValue = $this->model->FormValue;
		$this->rekening->CurrentValue = $this->rekening->FormValue;
		$this->debet->CurrentValue = $this->debet->FormValue;
		$this->credit->CurrentValue = $this->credit->FormValue;
		$this->pembayaran_->CurrentValue = $this->pembayaran_->FormValue;
		$this->bunga_->CurrentValue = $this->bunga_->FormValue;
		$this->denda_->CurrentValue = $this->denda_->FormValue;
		$this->titipan_->CurrentValue = $this->titipan_->FormValue;
		$this->administrasi_->CurrentValue = $this->administrasi_->FormValue;
		$this->modal_->CurrentValue = $this->modal_->FormValue;
		$this->pinjaman_->CurrentValue = $this->pinjaman_->FormValue;
		$this->biaya_->CurrentValue = $this->biaya_->FormValue;
		$this->kantor->CurrentValue = $this->kantor->FormValue;
		$this->keterangan->CurrentValue = $this->keterangan->FormValue;
		$this->active->CurrentValue = $this->active->FormValue;
		$this->ip->CurrentValue = $this->ip->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
		$this->user->CurrentValue = $this->user->FormValue;
		$this->created->CurrentValue = $this->created->FormValue;
		$this->created->CurrentValue = ew_UnFormatDateTime($this->created->CurrentValue, 0);
		$this->modified->CurrentValue = $this->modified->FormValue;
		$this->modified->CurrentValue = ew_UnFormatDateTime($this->modified->CurrentValue, 0);
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// tanggal
			$this->tanggal->EditAttrs["class"] = "form-control";
			$this->tanggal->EditCustomAttributes = "";
			$this->tanggal->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tanggal->CurrentValue, 8));
			$this->tanggal->PlaceHolder = ew_RemoveHtml($this->tanggal->FldCaption());

			// periode
			$this->periode->EditAttrs["class"] = "form-control";
			$this->periode->EditCustomAttributes = "";
			$this->periode->EditValue = ew_HtmlEncode($this->periode->CurrentValue);
			$this->periode->PlaceHolder = ew_RemoveHtml($this->periode->FldCaption());

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// nomor
			$this->nomor->EditAttrs["class"] = "form-control";
			$this->nomor->EditCustomAttributes = "";
			$this->nomor->EditValue = $this->nomor->CurrentValue;
			$this->nomor->ViewCustomAttributes = "";

			// transaksi
			$this->transaksi->EditAttrs["class"] = "form-control";
			$this->transaksi->EditCustomAttributes = "";
			$this->transaksi->EditValue = ew_HtmlEncode($this->transaksi->CurrentValue);
			$this->transaksi->PlaceHolder = ew_RemoveHtml($this->transaksi->FldCaption());

			// referensi
			$this->referensi->EditAttrs["class"] = "form-control";
			$this->referensi->EditCustomAttributes = "";
			$this->referensi->EditValue = ew_HtmlEncode($this->referensi->CurrentValue);
			$this->referensi->PlaceHolder = ew_RemoveHtml($this->referensi->FldCaption());

			// model
			$this->model->EditAttrs["class"] = "form-control";
			$this->model->EditCustomAttributes = "";
			$this->model->EditValue = ew_HtmlEncode($this->model->CurrentValue);
			$this->model->PlaceHolder = ew_RemoveHtml($this->model->FldCaption());

			// rekening
			$this->rekening->EditAttrs["class"] = "form-control";
			$this->rekening->EditCustomAttributes = "";
			$this->rekening->EditValue = ew_HtmlEncode($this->rekening->CurrentValue);
			$this->rekening->PlaceHolder = ew_RemoveHtml($this->rekening->FldCaption());

			// debet
			$this->debet->EditAttrs["class"] = "form-control";
			$this->debet->EditCustomAttributes = "";
			$this->debet->EditValue = ew_HtmlEncode($this->debet->CurrentValue);
			$this->debet->PlaceHolder = ew_RemoveHtml($this->debet->FldCaption());
			if (strval($this->debet->EditValue) <> "" && is_numeric($this->debet->EditValue)) $this->debet->EditValue = ew_FormatNumber($this->debet->EditValue, -2, -1, -2, 0);

			// credit
			$this->credit->EditAttrs["class"] = "form-control";
			$this->credit->EditCustomAttributes = "";
			$this->credit->EditValue = ew_HtmlEncode($this->credit->CurrentValue);
			$this->credit->PlaceHolder = ew_RemoveHtml($this->credit->FldCaption());
			if (strval($this->credit->EditValue) <> "" && is_numeric($this->credit->EditValue)) $this->credit->EditValue = ew_FormatNumber($this->credit->EditValue, -2, -1, -2, 0);

			// pembayaran_
			$this->pembayaran_->EditAttrs["class"] = "form-control";
			$this->pembayaran_->EditCustomAttributes = "";
			$this->pembayaran_->EditValue = ew_HtmlEncode($this->pembayaran_->CurrentValue);
			$this->pembayaran_->PlaceHolder = ew_RemoveHtml($this->pembayaran_->FldCaption());
			if (strval($this->pembayaran_->EditValue) <> "" && is_numeric($this->pembayaran_->EditValue)) $this->pembayaran_->EditValue = ew_FormatNumber($this->pembayaran_->EditValue, -2, -1, -2, 0);

			// bunga_
			$this->bunga_->EditAttrs["class"] = "form-control";
			$this->bunga_->EditCustomAttributes = "";
			$this->bunga_->EditValue = ew_HtmlEncode($this->bunga_->CurrentValue);
			$this->bunga_->PlaceHolder = ew_RemoveHtml($this->bunga_->FldCaption());
			if (strval($this->bunga_->EditValue) <> "" && is_numeric($this->bunga_->EditValue)) $this->bunga_->EditValue = ew_FormatNumber($this->bunga_->EditValue, -2, -1, -2, 0);

			// denda_
			$this->denda_->EditAttrs["class"] = "form-control";
			$this->denda_->EditCustomAttributes = "";
			$this->denda_->EditValue = ew_HtmlEncode($this->denda_->CurrentValue);
			$this->denda_->PlaceHolder = ew_RemoveHtml($this->denda_->FldCaption());
			if (strval($this->denda_->EditValue) <> "" && is_numeric($this->denda_->EditValue)) $this->denda_->EditValue = ew_FormatNumber($this->denda_->EditValue, -2, -1, -2, 0);

			// titipan_
			$this->titipan_->EditAttrs["class"] = "form-control";
			$this->titipan_->EditCustomAttributes = "";
			$this->titipan_->EditValue = ew_HtmlEncode($this->titipan_->CurrentValue);
			$this->titipan_->PlaceHolder = ew_RemoveHtml($this->titipan_->FldCaption());
			if (strval($this->titipan_->EditValue) <> "" && is_numeric($this->titipan_->EditValue)) $this->titipan_->EditValue = ew_FormatNumber($this->titipan_->EditValue, -2, -1, -2, 0);

			// administrasi_
			$this->administrasi_->EditAttrs["class"] = "form-control";
			$this->administrasi_->EditCustomAttributes = "";
			$this->administrasi_->EditValue = ew_HtmlEncode($this->administrasi_->CurrentValue);
			$this->administrasi_->PlaceHolder = ew_RemoveHtml($this->administrasi_->FldCaption());
			if (strval($this->administrasi_->EditValue) <> "" && is_numeric($this->administrasi_->EditValue)) $this->administrasi_->EditValue = ew_FormatNumber($this->administrasi_->EditValue, -2, -1, -2, 0);

			// modal_
			$this->modal_->EditAttrs["class"] = "form-control";
			$this->modal_->EditCustomAttributes = "";
			$this->modal_->EditValue = ew_HtmlEncode($this->modal_->CurrentValue);
			$this->modal_->PlaceHolder = ew_RemoveHtml($this->modal_->FldCaption());
			if (strval($this->modal_->EditValue) <> "" && is_numeric($this->modal_->EditValue)) $this->modal_->EditValue = ew_FormatNumber($this->modal_->EditValue, -2, -1, -2, 0);

			// pinjaman_
			$this->pinjaman_->EditAttrs["class"] = "form-control";
			$this->pinjaman_->EditCustomAttributes = "";
			$this->pinjaman_->EditValue = ew_HtmlEncode($this->pinjaman_->CurrentValue);
			$this->pinjaman_->PlaceHolder = ew_RemoveHtml($this->pinjaman_->FldCaption());
			if (strval($this->pinjaman_->EditValue) <> "" && is_numeric($this->pinjaman_->EditValue)) $this->pinjaman_->EditValue = ew_FormatNumber($this->pinjaman_->EditValue, -2, -1, -2, 0);

			// biaya_
			$this->biaya_->EditAttrs["class"] = "form-control";
			$this->biaya_->EditCustomAttributes = "";
			$this->biaya_->EditValue = ew_HtmlEncode($this->biaya_->CurrentValue);
			$this->biaya_->PlaceHolder = ew_RemoveHtml($this->biaya_->FldCaption());
			if (strval($this->biaya_->EditValue) <> "" && is_numeric($this->biaya_->EditValue)) $this->biaya_->EditValue = ew_FormatNumber($this->biaya_->EditValue, -2, -1, -2, 0);

			// kantor
			$this->kantor->EditAttrs["class"] = "form-control";
			$this->kantor->EditCustomAttributes = "";
			$this->kantor->EditValue = ew_HtmlEncode($this->kantor->CurrentValue);
			$this->kantor->PlaceHolder = ew_RemoveHtml($this->kantor->FldCaption());

			// keterangan
			$this->keterangan->EditAttrs["class"] = "form-control";
			$this->keterangan->EditCustomAttributes = "";
			$this->keterangan->EditValue = ew_HtmlEncode($this->keterangan->CurrentValue);
			$this->keterangan->PlaceHolder = ew_RemoveHtml($this->keterangan->FldCaption());

			// active
			$this->active->EditCustomAttributes = "";
			$this->active->EditValue = $this->active->Options(FALSE);

			// ip
			$this->ip->EditAttrs["class"] = "form-control";
			$this->ip->EditCustomAttributes = "";
			$this->ip->EditValue = ew_HtmlEncode($this->ip->CurrentValue);
			$this->ip->PlaceHolder = ew_RemoveHtml($this->ip->FldCaption());

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = ew_HtmlEncode($this->status->CurrentValue);
			$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

			// user
			$this->user->EditAttrs["class"] = "form-control";
			$this->user->EditCustomAttributes = "";
			$this->user->EditValue = ew_HtmlEncode($this->user->CurrentValue);
			$this->user->PlaceHolder = ew_RemoveHtml($this->user->FldCaption());

			// created
			$this->created->EditAttrs["class"] = "form-control";
			$this->created->EditCustomAttributes = "";
			$this->created->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->created->CurrentValue, 8));
			$this->created->PlaceHolder = ew_RemoveHtml($this->created->FldCaption());

			// modified
			$this->modified->EditAttrs["class"] = "form-control";
			$this->modified->EditCustomAttributes = "";
			$this->modified->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->modified->CurrentValue, 8));
			$this->modified->PlaceHolder = ew_RemoveHtml($this->modified->FldCaption());

			// Edit refer script
			// tanggal

			$this->tanggal->LinkCustomAttributes = "";
			$this->tanggal->HrefValue = "";

			// periode
			$this->periode->LinkCustomAttributes = "";
			$this->periode->HrefValue = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";

			// nomor
			$this->nomor->LinkCustomAttributes = "";
			$this->nomor->HrefValue = "";

			// transaksi
			$this->transaksi->LinkCustomAttributes = "";
			$this->transaksi->HrefValue = "";

			// referensi
			$this->referensi->LinkCustomAttributes = "";
			$this->referensi->HrefValue = "";

			// model
			$this->model->LinkCustomAttributes = "";
			$this->model->HrefValue = "";

			// rekening
			$this->rekening->LinkCustomAttributes = "";
			$this->rekening->HrefValue = "";

			// debet
			$this->debet->LinkCustomAttributes = "";
			$this->debet->HrefValue = "";

			// credit
			$this->credit->LinkCustomAttributes = "";
			$this->credit->HrefValue = "";

			// pembayaran_
			$this->pembayaran_->LinkCustomAttributes = "";
			$this->pembayaran_->HrefValue = "";

			// bunga_
			$this->bunga_->LinkCustomAttributes = "";
			$this->bunga_->HrefValue = "";

			// denda_
			$this->denda_->LinkCustomAttributes = "";
			$this->denda_->HrefValue = "";

			// titipan_
			$this->titipan_->LinkCustomAttributes = "";
			$this->titipan_->HrefValue = "";

			// administrasi_
			$this->administrasi_->LinkCustomAttributes = "";
			$this->administrasi_->HrefValue = "";

			// modal_
			$this->modal_->LinkCustomAttributes = "";
			$this->modal_->HrefValue = "";

			// pinjaman_
			$this->pinjaman_->LinkCustomAttributes = "";
			$this->pinjaman_->HrefValue = "";

			// biaya_
			$this->biaya_->LinkCustomAttributes = "";
			$this->biaya_->HrefValue = "";

			// kantor
			$this->kantor->LinkCustomAttributes = "";
			$this->kantor->HrefValue = "";

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";

			// active
			$this->active->LinkCustomAttributes = "";
			$this->active->HrefValue = "";

			// ip
			$this->ip->LinkCustomAttributes = "";
			$this->ip->HrefValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";

			// user
			$this->user->LinkCustomAttributes = "";
			$this->user->HrefValue = "";

			// created
			$this->created->LinkCustomAttributes = "";
			$this->created->HrefValue = "";

			// modified
			$this->modified->LinkCustomAttributes = "";
			$this->modified->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->tanggal->FldIsDetailKey && !is_null($this->tanggal->FormValue) && $this->tanggal->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tanggal->FldCaption(), $this->tanggal->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->tanggal->FormValue)) {
			ew_AddMessage($gsFormError, $this->tanggal->FldErrMsg());
		}
		if (!$this->periode->FldIsDetailKey && !is_null($this->periode->FormValue) && $this->periode->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->periode->FldCaption(), $this->periode->ReqErrMsg));
		}
		if (!$this->id->FldIsDetailKey && !is_null($this->id->FormValue) && $this->id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id->FldCaption(), $this->id->ReqErrMsg));
		}
		if (!$this->nomor->FldIsDetailKey && !is_null($this->nomor->FormValue) && $this->nomor->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nomor->FldCaption(), $this->nomor->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->nomor->FormValue)) {
			ew_AddMessage($gsFormError, $this->nomor->FldErrMsg());
		}
		if (!$this->transaksi->FldIsDetailKey && !is_null($this->transaksi->FormValue) && $this->transaksi->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->transaksi->FldCaption(), $this->transaksi->ReqErrMsg));
		}
		if (!$this->referensi->FldIsDetailKey && !is_null($this->referensi->FormValue) && $this->referensi->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->referensi->FldCaption(), $this->referensi->ReqErrMsg));
		}
		if (!$this->model->FldIsDetailKey && !is_null($this->model->FormValue) && $this->model->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->model->FldCaption(), $this->model->ReqErrMsg));
		}
		if (!$this->rekening->FldIsDetailKey && !is_null($this->rekening->FormValue) && $this->rekening->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->rekening->FldCaption(), $this->rekening->ReqErrMsg));
		}
		if (!$this->debet->FldIsDetailKey && !is_null($this->debet->FormValue) && $this->debet->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->debet->FldCaption(), $this->debet->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->debet->FormValue)) {
			ew_AddMessage($gsFormError, $this->debet->FldErrMsg());
		}
		if (!$this->credit->FldIsDetailKey && !is_null($this->credit->FormValue) && $this->credit->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->credit->FldCaption(), $this->credit->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->credit->FormValue)) {
			ew_AddMessage($gsFormError, $this->credit->FldErrMsg());
		}
		if (!$this->pembayaran_->FldIsDetailKey && !is_null($this->pembayaran_->FormValue) && $this->pembayaran_->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pembayaran_->FldCaption(), $this->pembayaran_->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->pembayaran_->FormValue)) {
			ew_AddMessage($gsFormError, $this->pembayaran_->FldErrMsg());
		}
		if (!$this->bunga_->FldIsDetailKey && !is_null($this->bunga_->FormValue) && $this->bunga_->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->bunga_->FldCaption(), $this->bunga_->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->bunga_->FormValue)) {
			ew_AddMessage($gsFormError, $this->bunga_->FldErrMsg());
		}
		if (!$this->denda_->FldIsDetailKey && !is_null($this->denda_->FormValue) && $this->denda_->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->denda_->FldCaption(), $this->denda_->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->denda_->FormValue)) {
			ew_AddMessage($gsFormError, $this->denda_->FldErrMsg());
		}
		if (!$this->titipan_->FldIsDetailKey && !is_null($this->titipan_->FormValue) && $this->titipan_->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->titipan_->FldCaption(), $this->titipan_->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->titipan_->FormValue)) {
			ew_AddMessage($gsFormError, $this->titipan_->FldErrMsg());
		}
		if (!$this->administrasi_->FldIsDetailKey && !is_null($this->administrasi_->FormValue) && $this->administrasi_->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->administrasi_->FldCaption(), $this->administrasi_->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->administrasi_->FormValue)) {
			ew_AddMessage($gsFormError, $this->administrasi_->FldErrMsg());
		}
		if (!$this->modal_->FldIsDetailKey && !is_null($this->modal_->FormValue) && $this->modal_->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->modal_->FldCaption(), $this->modal_->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->modal_->FormValue)) {
			ew_AddMessage($gsFormError, $this->modal_->FldErrMsg());
		}
		if (!$this->pinjaman_->FldIsDetailKey && !is_null($this->pinjaman_->FormValue) && $this->pinjaman_->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pinjaman_->FldCaption(), $this->pinjaman_->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->pinjaman_->FormValue)) {
			ew_AddMessage($gsFormError, $this->pinjaman_->FldErrMsg());
		}
		if (!$this->biaya_->FldIsDetailKey && !is_null($this->biaya_->FormValue) && $this->biaya_->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->biaya_->FldCaption(), $this->biaya_->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->biaya_->FormValue)) {
			ew_AddMessage($gsFormError, $this->biaya_->FldErrMsg());
		}
		if (!$this->kantor->FldIsDetailKey && !is_null($this->kantor->FormValue) && $this->kantor->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->kantor->FldCaption(), $this->kantor->ReqErrMsg));
		}
		if (!$this->keterangan->FldIsDetailKey && !is_null($this->keterangan->FormValue) && $this->keterangan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->keterangan->FldCaption(), $this->keterangan->ReqErrMsg));
		}
		if ($this->active->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->active->FldCaption(), $this->active->ReqErrMsg));
		}
		if (!$this->ip->FldIsDetailKey && !is_null($this->ip->FormValue) && $this->ip->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ip->FldCaption(), $this->ip->ReqErrMsg));
		}
		if (!$this->status->FldIsDetailKey && !is_null($this->status->FormValue) && $this->status->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->status->FldCaption(), $this->status->ReqErrMsg));
		}
		if (!$this->user->FldIsDetailKey && !is_null($this->user->FormValue) && $this->user->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->user->FldCaption(), $this->user->ReqErrMsg));
		}
		if (!$this->created->FldIsDetailKey && !is_null($this->created->FormValue) && $this->created->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->created->FldCaption(), $this->created->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->created->FormValue)) {
			ew_AddMessage($gsFormError, $this->created->FldErrMsg());
		}
		if (!$this->modified->FldIsDetailKey && !is_null($this->modified->FormValue) && $this->modified->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->modified->FldCaption(), $this->modified->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->modified->FormValue)) {
			ew_AddMessage($gsFormError, $this->modified->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// tanggal
			$this->tanggal->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tanggal->CurrentValue, 0), ew_CurrentDate(), $this->tanggal->ReadOnly);

			// periode
			$this->periode->SetDbValueDef($rsnew, $this->periode->CurrentValue, "", $this->periode->ReadOnly);

			// id
			// nomor
			// transaksi

			$this->transaksi->SetDbValueDef($rsnew, $this->transaksi->CurrentValue, "", $this->transaksi->ReadOnly);

			// referensi
			$this->referensi->SetDbValueDef($rsnew, $this->referensi->CurrentValue, "", $this->referensi->ReadOnly);

			// model
			$this->model->SetDbValueDef($rsnew, $this->model->CurrentValue, "", $this->model->ReadOnly);

			// rekening
			$this->rekening->SetDbValueDef($rsnew, $this->rekening->CurrentValue, "", $this->rekening->ReadOnly);

			// debet
			$this->debet->SetDbValueDef($rsnew, $this->debet->CurrentValue, 0, $this->debet->ReadOnly);

			// credit
			$this->credit->SetDbValueDef($rsnew, $this->credit->CurrentValue, 0, $this->credit->ReadOnly);

			// pembayaran_
			$this->pembayaran_->SetDbValueDef($rsnew, $this->pembayaran_->CurrentValue, 0, $this->pembayaran_->ReadOnly);

			// bunga_
			$this->bunga_->SetDbValueDef($rsnew, $this->bunga_->CurrentValue, 0, $this->bunga_->ReadOnly);

			// denda_
			$this->denda_->SetDbValueDef($rsnew, $this->denda_->CurrentValue, 0, $this->denda_->ReadOnly);

			// titipan_
			$this->titipan_->SetDbValueDef($rsnew, $this->titipan_->CurrentValue, 0, $this->titipan_->ReadOnly);

			// administrasi_
			$this->administrasi_->SetDbValueDef($rsnew, $this->administrasi_->CurrentValue, 0, $this->administrasi_->ReadOnly);

			// modal_
			$this->modal_->SetDbValueDef($rsnew, $this->modal_->CurrentValue, 0, $this->modal_->ReadOnly);

			// pinjaman_
			$this->pinjaman_->SetDbValueDef($rsnew, $this->pinjaman_->CurrentValue, 0, $this->pinjaman_->ReadOnly);

			// biaya_
			$this->biaya_->SetDbValueDef($rsnew, $this->biaya_->CurrentValue, 0, $this->biaya_->ReadOnly);

			// kantor
			$this->kantor->SetDbValueDef($rsnew, $this->kantor->CurrentValue, "", $this->kantor->ReadOnly);

			// keterangan
			$this->keterangan->SetDbValueDef($rsnew, $this->keterangan->CurrentValue, "", $this->keterangan->ReadOnly);

			// active
			$this->active->SetDbValueDef($rsnew, $this->active->CurrentValue, "", $this->active->ReadOnly);

			// ip
			$this->ip->SetDbValueDef($rsnew, $this->ip->CurrentValue, "", $this->ip->ReadOnly);

			// status
			$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, "", $this->status->ReadOnly);

			// user
			$this->user->SetDbValueDef($rsnew, $this->user->CurrentValue, "", $this->user->ReadOnly);

			// created
			$this->created->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->created->CurrentValue, 0), ew_CurrentDate(), $this->created->ReadOnly);

			// modified
			$this->modified->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->modified->CurrentValue, 0), ew_CurrentDate(), $this->modified->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("tjurnaltransaksilist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($tjurnaltransaksi_edit)) $tjurnaltransaksi_edit = new ctjurnaltransaksi_edit();

// Page init
$tjurnaltransaksi_edit->Page_Init();

// Page main
$tjurnaltransaksi_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tjurnaltransaksi_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = ftjurnaltransaksiedit = new ew_Form("ftjurnaltransaksiedit", "edit");

// Validate form
ftjurnaltransaksiedit.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_tanggal");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnaltransaksi->tanggal->FldCaption(), $tjurnaltransaksi->tanggal->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tanggal");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tjurnaltransaksi->tanggal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_periode");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnaltransaksi->periode->FldCaption(), $tjurnaltransaksi->periode->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnaltransaksi->id->FldCaption(), $tjurnaltransaksi->id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nomor");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnaltransaksi->nomor->FldCaption(), $tjurnaltransaksi->nomor->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nomor");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tjurnaltransaksi->nomor->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_transaksi");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnaltransaksi->transaksi->FldCaption(), $tjurnaltransaksi->transaksi->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_referensi");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnaltransaksi->referensi->FldCaption(), $tjurnaltransaksi->referensi->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_model");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnaltransaksi->model->FldCaption(), $tjurnaltransaksi->model->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_rekening");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnaltransaksi->rekening->FldCaption(), $tjurnaltransaksi->rekening->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_debet");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnaltransaksi->debet->FldCaption(), $tjurnaltransaksi->debet->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_debet");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tjurnaltransaksi->debet->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_credit");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnaltransaksi->credit->FldCaption(), $tjurnaltransaksi->credit->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_credit");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tjurnaltransaksi->credit->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_pembayaran_");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnaltransaksi->pembayaran_->FldCaption(), $tjurnaltransaksi->pembayaran_->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pembayaran_");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tjurnaltransaksi->pembayaran_->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_bunga_");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnaltransaksi->bunga_->FldCaption(), $tjurnaltransaksi->bunga_->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bunga_");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tjurnaltransaksi->bunga_->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_denda_");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnaltransaksi->denda_->FldCaption(), $tjurnaltransaksi->denda_->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_denda_");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tjurnaltransaksi->denda_->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_titipan_");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnaltransaksi->titipan_->FldCaption(), $tjurnaltransaksi->titipan_->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_titipan_");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tjurnaltransaksi->titipan_->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_administrasi_");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnaltransaksi->administrasi_->FldCaption(), $tjurnaltransaksi->administrasi_->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_administrasi_");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tjurnaltransaksi->administrasi_->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modal_");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnaltransaksi->modal_->FldCaption(), $tjurnaltransaksi->modal_->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modal_");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tjurnaltransaksi->modal_->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_pinjaman_");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnaltransaksi->pinjaman_->FldCaption(), $tjurnaltransaksi->pinjaman_->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pinjaman_");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tjurnaltransaksi->pinjaman_->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_biaya_");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnaltransaksi->biaya_->FldCaption(), $tjurnaltransaksi->biaya_->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_biaya_");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tjurnaltransaksi->biaya_->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_kantor");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnaltransaksi->kantor->FldCaption(), $tjurnaltransaksi->kantor->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_keterangan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnaltransaksi->keterangan->FldCaption(), $tjurnaltransaksi->keterangan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_active");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnaltransaksi->active->FldCaption(), $tjurnaltransaksi->active->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ip");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnaltransaksi->ip->FldCaption(), $tjurnaltransaksi->ip->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnaltransaksi->status->FldCaption(), $tjurnaltransaksi->status->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_user");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnaltransaksi->user->FldCaption(), $tjurnaltransaksi->user->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_created");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnaltransaksi->created->FldCaption(), $tjurnaltransaksi->created->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_created");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tjurnaltransaksi->created->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modified");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnaltransaksi->modified->FldCaption(), $tjurnaltransaksi->modified->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modified");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tjurnaltransaksi->modified->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
ftjurnaltransaksiedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftjurnaltransaksiedit.ValidateRequired = true;
<?php } else { ?>
ftjurnaltransaksiedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftjurnaltransaksiedit.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftjurnaltransaksiedit.Lists["x_active"].Options = <?php echo json_encode($tjurnaltransaksi->active->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$tjurnaltransaksi_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $tjurnaltransaksi_edit->ShowPageHeader(); ?>
<?php
$tjurnaltransaksi_edit->ShowMessage();
?>
<form name="ftjurnaltransaksiedit" id="ftjurnaltransaksiedit" class="<?php echo $tjurnaltransaksi_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tjurnaltransaksi_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tjurnaltransaksi_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tjurnaltransaksi">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($tjurnaltransaksi_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($tjurnaltransaksi->tanggal->Visible) { // tanggal ?>
	<div id="r_tanggal" class="form-group">
		<label id="elh_tjurnaltransaksi_tanggal" for="x_tanggal" class="col-sm-2 control-label ewLabel"><?php echo $tjurnaltransaksi->tanggal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnaltransaksi->tanggal->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_tanggal">
<input type="text" data-table="tjurnaltransaksi" data-field="x_tanggal" name="x_tanggal" id="x_tanggal" placeholder="<?php echo ew_HtmlEncode($tjurnaltransaksi->tanggal->getPlaceHolder()) ?>" value="<?php echo $tjurnaltransaksi->tanggal->EditValue ?>"<?php echo $tjurnaltransaksi->tanggal->EditAttributes() ?>>
</span>
<?php echo $tjurnaltransaksi->tanggal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnaltransaksi->periode->Visible) { // periode ?>
	<div id="r_periode" class="form-group">
		<label id="elh_tjurnaltransaksi_periode" for="x_periode" class="col-sm-2 control-label ewLabel"><?php echo $tjurnaltransaksi->periode->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnaltransaksi->periode->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_periode">
<input type="text" data-table="tjurnaltransaksi" data-field="x_periode" name="x_periode" id="x_periode" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tjurnaltransaksi->periode->getPlaceHolder()) ?>" value="<?php echo $tjurnaltransaksi->periode->EditValue ?>"<?php echo $tjurnaltransaksi->periode->EditAttributes() ?>>
</span>
<?php echo $tjurnaltransaksi->periode->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnaltransaksi->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_tjurnaltransaksi_id" for="x_id" class="col-sm-2 control-label ewLabel"><?php echo $tjurnaltransaksi->id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnaltransaksi->id->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_id">
<span<?php echo $tjurnaltransaksi->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $tjurnaltransaksi->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="tjurnaltransaksi" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($tjurnaltransaksi->id->CurrentValue) ?>">
<?php echo $tjurnaltransaksi->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnaltransaksi->nomor->Visible) { // nomor ?>
	<div id="r_nomor" class="form-group">
		<label id="elh_tjurnaltransaksi_nomor" for="x_nomor" class="col-sm-2 control-label ewLabel"><?php echo $tjurnaltransaksi->nomor->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnaltransaksi->nomor->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_nomor">
<span<?php echo $tjurnaltransaksi->nomor->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $tjurnaltransaksi->nomor->EditValue ?></p></span>
</span>
<input type="hidden" data-table="tjurnaltransaksi" data-field="x_nomor" name="x_nomor" id="x_nomor" value="<?php echo ew_HtmlEncode($tjurnaltransaksi->nomor->CurrentValue) ?>">
<?php echo $tjurnaltransaksi->nomor->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnaltransaksi->transaksi->Visible) { // transaksi ?>
	<div id="r_transaksi" class="form-group">
		<label id="elh_tjurnaltransaksi_transaksi" for="x_transaksi" class="col-sm-2 control-label ewLabel"><?php echo $tjurnaltransaksi->transaksi->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnaltransaksi->transaksi->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_transaksi">
<input type="text" data-table="tjurnaltransaksi" data-field="x_transaksi" name="x_transaksi" id="x_transaksi" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tjurnaltransaksi->transaksi->getPlaceHolder()) ?>" value="<?php echo $tjurnaltransaksi->transaksi->EditValue ?>"<?php echo $tjurnaltransaksi->transaksi->EditAttributes() ?>>
</span>
<?php echo $tjurnaltransaksi->transaksi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnaltransaksi->referensi->Visible) { // referensi ?>
	<div id="r_referensi" class="form-group">
		<label id="elh_tjurnaltransaksi_referensi" for="x_referensi" class="col-sm-2 control-label ewLabel"><?php echo $tjurnaltransaksi->referensi->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnaltransaksi->referensi->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_referensi">
<input type="text" data-table="tjurnaltransaksi" data-field="x_referensi" name="x_referensi" id="x_referensi" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tjurnaltransaksi->referensi->getPlaceHolder()) ?>" value="<?php echo $tjurnaltransaksi->referensi->EditValue ?>"<?php echo $tjurnaltransaksi->referensi->EditAttributes() ?>>
</span>
<?php echo $tjurnaltransaksi->referensi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnaltransaksi->model->Visible) { // model ?>
	<div id="r_model" class="form-group">
		<label id="elh_tjurnaltransaksi_model" for="x_model" class="col-sm-2 control-label ewLabel"><?php echo $tjurnaltransaksi->model->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnaltransaksi->model->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_model">
<input type="text" data-table="tjurnaltransaksi" data-field="x_model" name="x_model" id="x_model" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tjurnaltransaksi->model->getPlaceHolder()) ?>" value="<?php echo $tjurnaltransaksi->model->EditValue ?>"<?php echo $tjurnaltransaksi->model->EditAttributes() ?>>
</span>
<?php echo $tjurnaltransaksi->model->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnaltransaksi->rekening->Visible) { // rekening ?>
	<div id="r_rekening" class="form-group">
		<label id="elh_tjurnaltransaksi_rekening" for="x_rekening" class="col-sm-2 control-label ewLabel"><?php echo $tjurnaltransaksi->rekening->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnaltransaksi->rekening->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_rekening">
<input type="text" data-table="tjurnaltransaksi" data-field="x_rekening" name="x_rekening" id="x_rekening" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tjurnaltransaksi->rekening->getPlaceHolder()) ?>" value="<?php echo $tjurnaltransaksi->rekening->EditValue ?>"<?php echo $tjurnaltransaksi->rekening->EditAttributes() ?>>
</span>
<?php echo $tjurnaltransaksi->rekening->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnaltransaksi->debet->Visible) { // debet ?>
	<div id="r_debet" class="form-group">
		<label id="elh_tjurnaltransaksi_debet" for="x_debet" class="col-sm-2 control-label ewLabel"><?php echo $tjurnaltransaksi->debet->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnaltransaksi->debet->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_debet">
<input type="text" data-table="tjurnaltransaksi" data-field="x_debet" name="x_debet" id="x_debet" size="30" placeholder="<?php echo ew_HtmlEncode($tjurnaltransaksi->debet->getPlaceHolder()) ?>" value="<?php echo $tjurnaltransaksi->debet->EditValue ?>"<?php echo $tjurnaltransaksi->debet->EditAttributes() ?>>
</span>
<?php echo $tjurnaltransaksi->debet->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnaltransaksi->credit->Visible) { // credit ?>
	<div id="r_credit" class="form-group">
		<label id="elh_tjurnaltransaksi_credit" for="x_credit" class="col-sm-2 control-label ewLabel"><?php echo $tjurnaltransaksi->credit->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnaltransaksi->credit->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_credit">
<input type="text" data-table="tjurnaltransaksi" data-field="x_credit" name="x_credit" id="x_credit" size="30" placeholder="<?php echo ew_HtmlEncode($tjurnaltransaksi->credit->getPlaceHolder()) ?>" value="<?php echo $tjurnaltransaksi->credit->EditValue ?>"<?php echo $tjurnaltransaksi->credit->EditAttributes() ?>>
</span>
<?php echo $tjurnaltransaksi->credit->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnaltransaksi->pembayaran_->Visible) { // pembayaran_ ?>
	<div id="r_pembayaran_" class="form-group">
		<label id="elh_tjurnaltransaksi_pembayaran_" for="x_pembayaran_" class="col-sm-2 control-label ewLabel"><?php echo $tjurnaltransaksi->pembayaran_->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnaltransaksi->pembayaran_->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_pembayaran_">
<input type="text" data-table="tjurnaltransaksi" data-field="x_pembayaran_" name="x_pembayaran_" id="x_pembayaran_" size="30" placeholder="<?php echo ew_HtmlEncode($tjurnaltransaksi->pembayaran_->getPlaceHolder()) ?>" value="<?php echo $tjurnaltransaksi->pembayaran_->EditValue ?>"<?php echo $tjurnaltransaksi->pembayaran_->EditAttributes() ?>>
</span>
<?php echo $tjurnaltransaksi->pembayaran_->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnaltransaksi->bunga_->Visible) { // bunga_ ?>
	<div id="r_bunga_" class="form-group">
		<label id="elh_tjurnaltransaksi_bunga_" for="x_bunga_" class="col-sm-2 control-label ewLabel"><?php echo $tjurnaltransaksi->bunga_->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnaltransaksi->bunga_->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_bunga_">
<input type="text" data-table="tjurnaltransaksi" data-field="x_bunga_" name="x_bunga_" id="x_bunga_" size="30" placeholder="<?php echo ew_HtmlEncode($tjurnaltransaksi->bunga_->getPlaceHolder()) ?>" value="<?php echo $tjurnaltransaksi->bunga_->EditValue ?>"<?php echo $tjurnaltransaksi->bunga_->EditAttributes() ?>>
</span>
<?php echo $tjurnaltransaksi->bunga_->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnaltransaksi->denda_->Visible) { // denda_ ?>
	<div id="r_denda_" class="form-group">
		<label id="elh_tjurnaltransaksi_denda_" for="x_denda_" class="col-sm-2 control-label ewLabel"><?php echo $tjurnaltransaksi->denda_->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnaltransaksi->denda_->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_denda_">
<input type="text" data-table="tjurnaltransaksi" data-field="x_denda_" name="x_denda_" id="x_denda_" size="30" placeholder="<?php echo ew_HtmlEncode($tjurnaltransaksi->denda_->getPlaceHolder()) ?>" value="<?php echo $tjurnaltransaksi->denda_->EditValue ?>"<?php echo $tjurnaltransaksi->denda_->EditAttributes() ?>>
</span>
<?php echo $tjurnaltransaksi->denda_->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnaltransaksi->titipan_->Visible) { // titipan_ ?>
	<div id="r_titipan_" class="form-group">
		<label id="elh_tjurnaltransaksi_titipan_" for="x_titipan_" class="col-sm-2 control-label ewLabel"><?php echo $tjurnaltransaksi->titipan_->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnaltransaksi->titipan_->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_titipan_">
<input type="text" data-table="tjurnaltransaksi" data-field="x_titipan_" name="x_titipan_" id="x_titipan_" size="30" placeholder="<?php echo ew_HtmlEncode($tjurnaltransaksi->titipan_->getPlaceHolder()) ?>" value="<?php echo $tjurnaltransaksi->titipan_->EditValue ?>"<?php echo $tjurnaltransaksi->titipan_->EditAttributes() ?>>
</span>
<?php echo $tjurnaltransaksi->titipan_->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnaltransaksi->administrasi_->Visible) { // administrasi_ ?>
	<div id="r_administrasi_" class="form-group">
		<label id="elh_tjurnaltransaksi_administrasi_" for="x_administrasi_" class="col-sm-2 control-label ewLabel"><?php echo $tjurnaltransaksi->administrasi_->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnaltransaksi->administrasi_->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_administrasi_">
<input type="text" data-table="tjurnaltransaksi" data-field="x_administrasi_" name="x_administrasi_" id="x_administrasi_" size="30" placeholder="<?php echo ew_HtmlEncode($tjurnaltransaksi->administrasi_->getPlaceHolder()) ?>" value="<?php echo $tjurnaltransaksi->administrasi_->EditValue ?>"<?php echo $tjurnaltransaksi->administrasi_->EditAttributes() ?>>
</span>
<?php echo $tjurnaltransaksi->administrasi_->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnaltransaksi->modal_->Visible) { // modal_ ?>
	<div id="r_modal_" class="form-group">
		<label id="elh_tjurnaltransaksi_modal_" for="x_modal_" class="col-sm-2 control-label ewLabel"><?php echo $tjurnaltransaksi->modal_->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnaltransaksi->modal_->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_modal_">
<input type="text" data-table="tjurnaltransaksi" data-field="x_modal_" name="x_modal_" id="x_modal_" size="30" placeholder="<?php echo ew_HtmlEncode($tjurnaltransaksi->modal_->getPlaceHolder()) ?>" value="<?php echo $tjurnaltransaksi->modal_->EditValue ?>"<?php echo $tjurnaltransaksi->modal_->EditAttributes() ?>>
</span>
<?php echo $tjurnaltransaksi->modal_->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnaltransaksi->pinjaman_->Visible) { // pinjaman_ ?>
	<div id="r_pinjaman_" class="form-group">
		<label id="elh_tjurnaltransaksi_pinjaman_" for="x_pinjaman_" class="col-sm-2 control-label ewLabel"><?php echo $tjurnaltransaksi->pinjaman_->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnaltransaksi->pinjaman_->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_pinjaman_">
<input type="text" data-table="tjurnaltransaksi" data-field="x_pinjaman_" name="x_pinjaman_" id="x_pinjaman_" size="30" placeholder="<?php echo ew_HtmlEncode($tjurnaltransaksi->pinjaman_->getPlaceHolder()) ?>" value="<?php echo $tjurnaltransaksi->pinjaman_->EditValue ?>"<?php echo $tjurnaltransaksi->pinjaman_->EditAttributes() ?>>
</span>
<?php echo $tjurnaltransaksi->pinjaman_->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnaltransaksi->biaya_->Visible) { // biaya_ ?>
	<div id="r_biaya_" class="form-group">
		<label id="elh_tjurnaltransaksi_biaya_" for="x_biaya_" class="col-sm-2 control-label ewLabel"><?php echo $tjurnaltransaksi->biaya_->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnaltransaksi->biaya_->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_biaya_">
<input type="text" data-table="tjurnaltransaksi" data-field="x_biaya_" name="x_biaya_" id="x_biaya_" size="30" placeholder="<?php echo ew_HtmlEncode($tjurnaltransaksi->biaya_->getPlaceHolder()) ?>" value="<?php echo $tjurnaltransaksi->biaya_->EditValue ?>"<?php echo $tjurnaltransaksi->biaya_->EditAttributes() ?>>
</span>
<?php echo $tjurnaltransaksi->biaya_->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnaltransaksi->kantor->Visible) { // kantor ?>
	<div id="r_kantor" class="form-group">
		<label id="elh_tjurnaltransaksi_kantor" for="x_kantor" class="col-sm-2 control-label ewLabel"><?php echo $tjurnaltransaksi->kantor->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnaltransaksi->kantor->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_kantor">
<input type="text" data-table="tjurnaltransaksi" data-field="x_kantor" name="x_kantor" id="x_kantor" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tjurnaltransaksi->kantor->getPlaceHolder()) ?>" value="<?php echo $tjurnaltransaksi->kantor->EditValue ?>"<?php echo $tjurnaltransaksi->kantor->EditAttributes() ?>>
</span>
<?php echo $tjurnaltransaksi->kantor->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnaltransaksi->keterangan->Visible) { // keterangan ?>
	<div id="r_keterangan" class="form-group">
		<label id="elh_tjurnaltransaksi_keterangan" for="x_keterangan" class="col-sm-2 control-label ewLabel"><?php echo $tjurnaltransaksi->keterangan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnaltransaksi->keterangan->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_keterangan">
<input type="text" data-table="tjurnaltransaksi" data-field="x_keterangan" name="x_keterangan" id="x_keterangan" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($tjurnaltransaksi->keterangan->getPlaceHolder()) ?>" value="<?php echo $tjurnaltransaksi->keterangan->EditValue ?>"<?php echo $tjurnaltransaksi->keterangan->EditAttributes() ?>>
</span>
<?php echo $tjurnaltransaksi->keterangan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnaltransaksi->active->Visible) { // active ?>
	<div id="r_active" class="form-group">
		<label id="elh_tjurnaltransaksi_active" class="col-sm-2 control-label ewLabel"><?php echo $tjurnaltransaksi->active->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnaltransaksi->active->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_active">
<div id="tp_x_active" class="ewTemplate"><input type="radio" data-table="tjurnaltransaksi" data-field="x_active" data-value-separator="<?php echo $tjurnaltransaksi->active->DisplayValueSeparatorAttribute() ?>" name="x_active" id="x_active" value="{value}"<?php echo $tjurnaltransaksi->active->EditAttributes() ?>></div>
<div id="dsl_x_active" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $tjurnaltransaksi->active->RadioButtonListHtml(FALSE, "x_active") ?>
</div></div>
</span>
<?php echo $tjurnaltransaksi->active->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnaltransaksi->ip->Visible) { // ip ?>
	<div id="r_ip" class="form-group">
		<label id="elh_tjurnaltransaksi_ip" for="x_ip" class="col-sm-2 control-label ewLabel"><?php echo $tjurnaltransaksi->ip->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnaltransaksi->ip->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_ip">
<input type="text" data-table="tjurnaltransaksi" data-field="x_ip" name="x_ip" id="x_ip" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tjurnaltransaksi->ip->getPlaceHolder()) ?>" value="<?php echo $tjurnaltransaksi->ip->EditValue ?>"<?php echo $tjurnaltransaksi->ip->EditAttributes() ?>>
</span>
<?php echo $tjurnaltransaksi->ip->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnaltransaksi->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_tjurnaltransaksi_status" for="x_status" class="col-sm-2 control-label ewLabel"><?php echo $tjurnaltransaksi->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnaltransaksi->status->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_status">
<input type="text" data-table="tjurnaltransaksi" data-field="x_status" name="x_status" id="x_status" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tjurnaltransaksi->status->getPlaceHolder()) ?>" value="<?php echo $tjurnaltransaksi->status->EditValue ?>"<?php echo $tjurnaltransaksi->status->EditAttributes() ?>>
</span>
<?php echo $tjurnaltransaksi->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnaltransaksi->user->Visible) { // user ?>
	<div id="r_user" class="form-group">
		<label id="elh_tjurnaltransaksi_user" for="x_user" class="col-sm-2 control-label ewLabel"><?php echo $tjurnaltransaksi->user->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnaltransaksi->user->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_user">
<input type="text" data-table="tjurnaltransaksi" data-field="x_user" name="x_user" id="x_user" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tjurnaltransaksi->user->getPlaceHolder()) ?>" value="<?php echo $tjurnaltransaksi->user->EditValue ?>"<?php echo $tjurnaltransaksi->user->EditAttributes() ?>>
</span>
<?php echo $tjurnaltransaksi->user->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnaltransaksi->created->Visible) { // created ?>
	<div id="r_created" class="form-group">
		<label id="elh_tjurnaltransaksi_created" for="x_created" class="col-sm-2 control-label ewLabel"><?php echo $tjurnaltransaksi->created->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnaltransaksi->created->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_created">
<input type="text" data-table="tjurnaltransaksi" data-field="x_created" name="x_created" id="x_created" placeholder="<?php echo ew_HtmlEncode($tjurnaltransaksi->created->getPlaceHolder()) ?>" value="<?php echo $tjurnaltransaksi->created->EditValue ?>"<?php echo $tjurnaltransaksi->created->EditAttributes() ?>>
</span>
<?php echo $tjurnaltransaksi->created->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnaltransaksi->modified->Visible) { // modified ?>
	<div id="r_modified" class="form-group">
		<label id="elh_tjurnaltransaksi_modified" for="x_modified" class="col-sm-2 control-label ewLabel"><?php echo $tjurnaltransaksi->modified->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnaltransaksi->modified->CellAttributes() ?>>
<span id="el_tjurnaltransaksi_modified">
<input type="text" data-table="tjurnaltransaksi" data-field="x_modified" name="x_modified" id="x_modified" placeholder="<?php echo ew_HtmlEncode($tjurnaltransaksi->modified->getPlaceHolder()) ?>" value="<?php echo $tjurnaltransaksi->modified->EditValue ?>"<?php echo $tjurnaltransaksi->modified->EditAttributes() ?>>
</span>
<?php echo $tjurnaltransaksi->modified->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$tjurnaltransaksi_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $tjurnaltransaksi_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ftjurnaltransaksiedit.Init();
</script>
<?php
$tjurnaltransaksi_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tjurnaltransaksi_edit->Page_Terminate();
?>
