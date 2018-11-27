<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "tjurnalinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$tjurnal_add = NULL; // Initialize page object first

class ctjurnal_add extends ctjurnal {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tjurnal';

	// Page object name
	var $PageObjName = 'tjurnal_add';

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

		// Table object (tjurnal)
		if (!isset($GLOBALS["tjurnal"]) || get_class($GLOBALS["tjurnal"]) == "ctjurnal") {
			$GLOBALS["tjurnal"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tjurnal"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tjurnal', TRUE);

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
		$this->tipe->SetVisibility();
		$this->posisi->SetVisibility();
		$this->laporan->SetVisibility();
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
		global $EW_EXPORT, $tjurnal;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($tjurnal);
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

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

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id"] != "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["nomor"] != "") {
				$this->nomor->setQueryStringValue($_GET["nomor"]);
				$this->setKey("nomor", $this->nomor->CurrentValue); // Set up key
			} else {
				$this->setKey("nomor", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("tjurnallist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "tjurnallist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "tjurnalview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->tanggal->CurrentValue = "0000-00-00 00:00:00";
		$this->periode->CurrentValue = NULL;
		$this->periode->OldValue = $this->periode->CurrentValue;
		$this->id->CurrentValue = NULL;
		$this->id->OldValue = $this->id->CurrentValue;
		$this->nomor->CurrentValue = 0;
		$this->transaksi->CurrentValue = NULL;
		$this->transaksi->OldValue = $this->transaksi->CurrentValue;
		$this->referensi->CurrentValue = NULL;
		$this->referensi->OldValue = $this->referensi->CurrentValue;
		$this->model->CurrentValue = NULL;
		$this->model->OldValue = $this->model->CurrentValue;
		$this->rekening->CurrentValue = NULL;
		$this->rekening->OldValue = $this->rekening->CurrentValue;
		$this->tipe->CurrentValue = NULL;
		$this->tipe->OldValue = $this->tipe->CurrentValue;
		$this->posisi->CurrentValue = NULL;
		$this->posisi->OldValue = $this->posisi->CurrentValue;
		$this->laporan->CurrentValue = NULL;
		$this->laporan->OldValue = $this->laporan->CurrentValue;
		$this->debet->CurrentValue = 0;
		$this->credit->CurrentValue = 0;
		$this->kantor->CurrentValue = NULL;
		$this->kantor->OldValue = $this->kantor->CurrentValue;
		$this->keterangan->CurrentValue = NULL;
		$this->keterangan->OldValue = $this->keterangan->CurrentValue;
		$this->active->CurrentValue = "yes";
		$this->ip->CurrentValue = NULL;
		$this->ip->OldValue = $this->ip->CurrentValue;
		$this->status->CurrentValue = NULL;
		$this->status->OldValue = $this->status->CurrentValue;
		$this->user->CurrentValue = NULL;
		$this->user->OldValue = $this->user->CurrentValue;
		$this->created->CurrentValue = "0000-00-00 00:00:00";
		$this->modified->CurrentValue = "0000-00-00 00:00:00";
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
		if (!$this->tipe->FldIsDetailKey) {
			$this->tipe->setFormValue($objForm->GetValue("x_tipe"));
		}
		if (!$this->posisi->FldIsDetailKey) {
			$this->posisi->setFormValue($objForm->GetValue("x_posisi"));
		}
		if (!$this->laporan->FldIsDetailKey) {
			$this->laporan->setFormValue($objForm->GetValue("x_laporan"));
		}
		if (!$this->debet->FldIsDetailKey) {
			$this->debet->setFormValue($objForm->GetValue("x_debet"));
		}
		if (!$this->credit->FldIsDetailKey) {
			$this->credit->setFormValue($objForm->GetValue("x_credit"));
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
		$this->LoadOldRecord();
		$this->tanggal->CurrentValue = $this->tanggal->FormValue;
		$this->tanggal->CurrentValue = ew_UnFormatDateTime($this->tanggal->CurrentValue, 0);
		$this->periode->CurrentValue = $this->periode->FormValue;
		$this->id->CurrentValue = $this->id->FormValue;
		$this->nomor->CurrentValue = $this->nomor->FormValue;
		$this->transaksi->CurrentValue = $this->transaksi->FormValue;
		$this->referensi->CurrentValue = $this->referensi->FormValue;
		$this->model->CurrentValue = $this->model->FormValue;
		$this->rekening->CurrentValue = $this->rekening->FormValue;
		$this->tipe->CurrentValue = $this->tipe->FormValue;
		$this->posisi->CurrentValue = $this->posisi->FormValue;
		$this->laporan->CurrentValue = $this->laporan->FormValue;
		$this->debet->CurrentValue = $this->debet->FormValue;
		$this->credit->CurrentValue = $this->credit->FormValue;
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
		$this->tipe->setDbValue($rs->fields('tipe'));
		$this->posisi->setDbValue($rs->fields('posisi'));
		$this->laporan->setDbValue($rs->fields('laporan'));
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
		$this->tipe->DbValue = $row['tipe'];
		$this->posisi->DbValue = $row['posisi'];
		$this->laporan->DbValue = $row['laporan'];
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("nomor")) <> "")
			$this->nomor->CurrentValue = $this->getKey("nomor"); // nomor
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
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
		// tipe
		// posisi
		// laporan
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

		// tipe
		$this->tipe->ViewValue = $this->tipe->CurrentValue;
		$this->tipe->ViewCustomAttributes = "";

		// posisi
		$this->posisi->ViewValue = $this->posisi->CurrentValue;
		$this->posisi->ViewCustomAttributes = "";

		// laporan
		$this->laporan->ViewValue = $this->laporan->CurrentValue;
		$this->laporan->ViewCustomAttributes = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

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
			$this->id->EditValue = ew_HtmlEncode($this->id->CurrentValue);
			$this->id->PlaceHolder = ew_RemoveHtml($this->id->FldCaption());

			// nomor
			$this->nomor->EditAttrs["class"] = "form-control";
			$this->nomor->EditCustomAttributes = "";
			$this->nomor->EditValue = ew_HtmlEncode($this->nomor->CurrentValue);
			$this->nomor->PlaceHolder = ew_RemoveHtml($this->nomor->FldCaption());

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

			// tipe
			$this->tipe->EditAttrs["class"] = "form-control";
			$this->tipe->EditCustomAttributes = "";
			$this->tipe->EditValue = ew_HtmlEncode($this->tipe->CurrentValue);
			$this->tipe->PlaceHolder = ew_RemoveHtml($this->tipe->FldCaption());

			// posisi
			$this->posisi->EditAttrs["class"] = "form-control";
			$this->posisi->EditCustomAttributes = "";
			$this->posisi->EditValue = ew_HtmlEncode($this->posisi->CurrentValue);
			$this->posisi->PlaceHolder = ew_RemoveHtml($this->posisi->FldCaption());

			// laporan
			$this->laporan->EditAttrs["class"] = "form-control";
			$this->laporan->EditCustomAttributes = "";
			$this->laporan->EditValue = ew_HtmlEncode($this->laporan->CurrentValue);
			$this->laporan->PlaceHolder = ew_RemoveHtml($this->laporan->FldCaption());

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

			// Add refer script
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

			// tipe
			$this->tipe->LinkCustomAttributes = "";
			$this->tipe->HrefValue = "";

			// posisi
			$this->posisi->LinkCustomAttributes = "";
			$this->posisi->HrefValue = "";

			// laporan
			$this->laporan->LinkCustomAttributes = "";
			$this->laporan->HrefValue = "";

			// debet
			$this->debet->LinkCustomAttributes = "";
			$this->debet->HrefValue = "";

			// credit
			$this->credit->LinkCustomAttributes = "";
			$this->credit->HrefValue = "";

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
		if (!$this->tipe->FldIsDetailKey && !is_null($this->tipe->FormValue) && $this->tipe->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tipe->FldCaption(), $this->tipe->ReqErrMsg));
		}
		if (!$this->posisi->FldIsDetailKey && !is_null($this->posisi->FormValue) && $this->posisi->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->posisi->FldCaption(), $this->posisi->ReqErrMsg));
		}
		if (!$this->laporan->FldIsDetailKey && !is_null($this->laporan->FormValue) && $this->laporan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->laporan->FldCaption(), $this->laporan->ReqErrMsg));
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// tanggal
		$this->tanggal->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tanggal->CurrentValue, 0), ew_CurrentDate(), strval($this->tanggal->CurrentValue) == "");

		// periode
		$this->periode->SetDbValueDef($rsnew, $this->periode->CurrentValue, "", FALSE);

		// id
		$this->id->SetDbValueDef($rsnew, $this->id->CurrentValue, "", FALSE);

		// nomor
		$this->nomor->SetDbValueDef($rsnew, $this->nomor->CurrentValue, 0, strval($this->nomor->CurrentValue) == "");

		// transaksi
		$this->transaksi->SetDbValueDef($rsnew, $this->transaksi->CurrentValue, "", FALSE);

		// referensi
		$this->referensi->SetDbValueDef($rsnew, $this->referensi->CurrentValue, "", FALSE);

		// model
		$this->model->SetDbValueDef($rsnew, $this->model->CurrentValue, "", FALSE);

		// rekening
		$this->rekening->SetDbValueDef($rsnew, $this->rekening->CurrentValue, "", FALSE);

		// tipe
		$this->tipe->SetDbValueDef($rsnew, $this->tipe->CurrentValue, "", FALSE);

		// posisi
		$this->posisi->SetDbValueDef($rsnew, $this->posisi->CurrentValue, "", FALSE);

		// laporan
		$this->laporan->SetDbValueDef($rsnew, $this->laporan->CurrentValue, "", FALSE);

		// debet
		$this->debet->SetDbValueDef($rsnew, $this->debet->CurrentValue, 0, strval($this->debet->CurrentValue) == "");

		// credit
		$this->credit->SetDbValueDef($rsnew, $this->credit->CurrentValue, 0, strval($this->credit->CurrentValue) == "");

		// kantor
		$this->kantor->SetDbValueDef($rsnew, $this->kantor->CurrentValue, "", FALSE);

		// keterangan
		$this->keterangan->SetDbValueDef($rsnew, $this->keterangan->CurrentValue, "", FALSE);

		// active
		$this->active->SetDbValueDef($rsnew, $this->active->CurrentValue, "", strval($this->active->CurrentValue) == "");

		// ip
		$this->ip->SetDbValueDef($rsnew, $this->ip->CurrentValue, "", FALSE);

		// status
		$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, "", FALSE);

		// user
		$this->user->SetDbValueDef($rsnew, $this->user->CurrentValue, "", FALSE);

		// created
		$this->created->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->created->CurrentValue, 0), ew_CurrentDate(), strval($this->created->CurrentValue) == "");

		// modified
		$this->modified->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->modified->CurrentValue, 0), ew_CurrentDate(), strval($this->modified->CurrentValue) == "");

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['id']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['nomor']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check for duplicate key
		if ($bInsertRow && $this->ValidateKey) {
			$sFilter = $this->KeyFilter();
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sKeyErrMsg = str_replace("%f", $sFilter, $Language->Phrase("DupKey"));
				$this->setFailureMessage($sKeyErrMsg);
				$rsChk->Close();
				$bInsertRow = FALSE;
			}
		}
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("tjurnallist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($tjurnal_add)) $tjurnal_add = new ctjurnal_add();

// Page init
$tjurnal_add->Page_Init();

// Page main
$tjurnal_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tjurnal_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = ftjurnaladd = new ew_Form("ftjurnaladd", "add");

// Validate form
ftjurnaladd.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnal->tanggal->FldCaption(), $tjurnal->tanggal->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tanggal");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tjurnal->tanggal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_periode");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnal->periode->FldCaption(), $tjurnal->periode->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnal->id->FldCaption(), $tjurnal->id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nomor");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnal->nomor->FldCaption(), $tjurnal->nomor->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nomor");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tjurnal->nomor->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_transaksi");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnal->transaksi->FldCaption(), $tjurnal->transaksi->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_referensi");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnal->referensi->FldCaption(), $tjurnal->referensi->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_model");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnal->model->FldCaption(), $tjurnal->model->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_rekening");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnal->rekening->FldCaption(), $tjurnal->rekening->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tipe");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnal->tipe->FldCaption(), $tjurnal->tipe->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_posisi");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnal->posisi->FldCaption(), $tjurnal->posisi->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_laporan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnal->laporan->FldCaption(), $tjurnal->laporan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_debet");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnal->debet->FldCaption(), $tjurnal->debet->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_debet");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tjurnal->debet->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_credit");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnal->credit->FldCaption(), $tjurnal->credit->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_credit");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tjurnal->credit->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_kantor");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnal->kantor->FldCaption(), $tjurnal->kantor->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_keterangan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnal->keterangan->FldCaption(), $tjurnal->keterangan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_active");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnal->active->FldCaption(), $tjurnal->active->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ip");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnal->ip->FldCaption(), $tjurnal->ip->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnal->status->FldCaption(), $tjurnal->status->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_user");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnal->user->FldCaption(), $tjurnal->user->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_created");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnal->created->FldCaption(), $tjurnal->created->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_created");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tjurnal->created->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modified");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tjurnal->modified->FldCaption(), $tjurnal->modified->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modified");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tjurnal->modified->FldErrMsg()) ?>");

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
ftjurnaladd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftjurnaladd.ValidateRequired = true;
<?php } else { ?>
ftjurnaladd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftjurnaladd.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftjurnaladd.Lists["x_active"].Options = <?php echo json_encode($tjurnal->active->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$tjurnal_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $tjurnal_add->ShowPageHeader(); ?>
<?php
$tjurnal_add->ShowMessage();
?>
<form name="ftjurnaladd" id="ftjurnaladd" class="<?php echo $tjurnal_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tjurnal_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tjurnal_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tjurnal">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($tjurnal_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($tjurnal->tanggal->Visible) { // tanggal ?>
	<div id="r_tanggal" class="form-group">
		<label id="elh_tjurnal_tanggal" for="x_tanggal" class="col-sm-2 control-label ewLabel"><?php echo $tjurnal->tanggal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnal->tanggal->CellAttributes() ?>>
<span id="el_tjurnal_tanggal">
<input type="text" data-table="tjurnal" data-field="x_tanggal" name="x_tanggal" id="x_tanggal" placeholder="<?php echo ew_HtmlEncode($tjurnal->tanggal->getPlaceHolder()) ?>" value="<?php echo $tjurnal->tanggal->EditValue ?>"<?php echo $tjurnal->tanggal->EditAttributes() ?>>
</span>
<?php echo $tjurnal->tanggal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnal->periode->Visible) { // periode ?>
	<div id="r_periode" class="form-group">
		<label id="elh_tjurnal_periode" for="x_periode" class="col-sm-2 control-label ewLabel"><?php echo $tjurnal->periode->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnal->periode->CellAttributes() ?>>
<span id="el_tjurnal_periode">
<input type="text" data-table="tjurnal" data-field="x_periode" name="x_periode" id="x_periode" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tjurnal->periode->getPlaceHolder()) ?>" value="<?php echo $tjurnal->periode->EditValue ?>"<?php echo $tjurnal->periode->EditAttributes() ?>>
</span>
<?php echo $tjurnal->periode->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnal->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_tjurnal_id" for="x_id" class="col-sm-2 control-label ewLabel"><?php echo $tjurnal->id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnal->id->CellAttributes() ?>>
<span id="el_tjurnal_id">
<input type="text" data-table="tjurnal" data-field="x_id" name="x_id" id="x_id" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tjurnal->id->getPlaceHolder()) ?>" value="<?php echo $tjurnal->id->EditValue ?>"<?php echo $tjurnal->id->EditAttributes() ?>>
</span>
<?php echo $tjurnal->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnal->nomor->Visible) { // nomor ?>
	<div id="r_nomor" class="form-group">
		<label id="elh_tjurnal_nomor" for="x_nomor" class="col-sm-2 control-label ewLabel"><?php echo $tjurnal->nomor->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnal->nomor->CellAttributes() ?>>
<span id="el_tjurnal_nomor">
<input type="text" data-table="tjurnal" data-field="x_nomor" name="x_nomor" id="x_nomor" size="30" placeholder="<?php echo ew_HtmlEncode($tjurnal->nomor->getPlaceHolder()) ?>" value="<?php echo $tjurnal->nomor->EditValue ?>"<?php echo $tjurnal->nomor->EditAttributes() ?>>
</span>
<?php echo $tjurnal->nomor->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnal->transaksi->Visible) { // transaksi ?>
	<div id="r_transaksi" class="form-group">
		<label id="elh_tjurnal_transaksi" for="x_transaksi" class="col-sm-2 control-label ewLabel"><?php echo $tjurnal->transaksi->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnal->transaksi->CellAttributes() ?>>
<span id="el_tjurnal_transaksi">
<input type="text" data-table="tjurnal" data-field="x_transaksi" name="x_transaksi" id="x_transaksi" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tjurnal->transaksi->getPlaceHolder()) ?>" value="<?php echo $tjurnal->transaksi->EditValue ?>"<?php echo $tjurnal->transaksi->EditAttributes() ?>>
</span>
<?php echo $tjurnal->transaksi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnal->referensi->Visible) { // referensi ?>
	<div id="r_referensi" class="form-group">
		<label id="elh_tjurnal_referensi" for="x_referensi" class="col-sm-2 control-label ewLabel"><?php echo $tjurnal->referensi->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnal->referensi->CellAttributes() ?>>
<span id="el_tjurnal_referensi">
<input type="text" data-table="tjurnal" data-field="x_referensi" name="x_referensi" id="x_referensi" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tjurnal->referensi->getPlaceHolder()) ?>" value="<?php echo $tjurnal->referensi->EditValue ?>"<?php echo $tjurnal->referensi->EditAttributes() ?>>
</span>
<?php echo $tjurnal->referensi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnal->model->Visible) { // model ?>
	<div id="r_model" class="form-group">
		<label id="elh_tjurnal_model" for="x_model" class="col-sm-2 control-label ewLabel"><?php echo $tjurnal->model->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnal->model->CellAttributes() ?>>
<span id="el_tjurnal_model">
<input type="text" data-table="tjurnal" data-field="x_model" name="x_model" id="x_model" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tjurnal->model->getPlaceHolder()) ?>" value="<?php echo $tjurnal->model->EditValue ?>"<?php echo $tjurnal->model->EditAttributes() ?>>
</span>
<?php echo $tjurnal->model->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnal->rekening->Visible) { // rekening ?>
	<div id="r_rekening" class="form-group">
		<label id="elh_tjurnal_rekening" for="x_rekening" class="col-sm-2 control-label ewLabel"><?php echo $tjurnal->rekening->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnal->rekening->CellAttributes() ?>>
<span id="el_tjurnal_rekening">
<input type="text" data-table="tjurnal" data-field="x_rekening" name="x_rekening" id="x_rekening" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tjurnal->rekening->getPlaceHolder()) ?>" value="<?php echo $tjurnal->rekening->EditValue ?>"<?php echo $tjurnal->rekening->EditAttributes() ?>>
</span>
<?php echo $tjurnal->rekening->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnal->tipe->Visible) { // tipe ?>
	<div id="r_tipe" class="form-group">
		<label id="elh_tjurnal_tipe" for="x_tipe" class="col-sm-2 control-label ewLabel"><?php echo $tjurnal->tipe->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnal->tipe->CellAttributes() ?>>
<span id="el_tjurnal_tipe">
<input type="text" data-table="tjurnal" data-field="x_tipe" name="x_tipe" id="x_tipe" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tjurnal->tipe->getPlaceHolder()) ?>" value="<?php echo $tjurnal->tipe->EditValue ?>"<?php echo $tjurnal->tipe->EditAttributes() ?>>
</span>
<?php echo $tjurnal->tipe->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnal->posisi->Visible) { // posisi ?>
	<div id="r_posisi" class="form-group">
		<label id="elh_tjurnal_posisi" for="x_posisi" class="col-sm-2 control-label ewLabel"><?php echo $tjurnal->posisi->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnal->posisi->CellAttributes() ?>>
<span id="el_tjurnal_posisi">
<input type="text" data-table="tjurnal" data-field="x_posisi" name="x_posisi" id="x_posisi" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tjurnal->posisi->getPlaceHolder()) ?>" value="<?php echo $tjurnal->posisi->EditValue ?>"<?php echo $tjurnal->posisi->EditAttributes() ?>>
</span>
<?php echo $tjurnal->posisi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnal->laporan->Visible) { // laporan ?>
	<div id="r_laporan" class="form-group">
		<label id="elh_tjurnal_laporan" for="x_laporan" class="col-sm-2 control-label ewLabel"><?php echo $tjurnal->laporan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnal->laporan->CellAttributes() ?>>
<span id="el_tjurnal_laporan">
<input type="text" data-table="tjurnal" data-field="x_laporan" name="x_laporan" id="x_laporan" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tjurnal->laporan->getPlaceHolder()) ?>" value="<?php echo $tjurnal->laporan->EditValue ?>"<?php echo $tjurnal->laporan->EditAttributes() ?>>
</span>
<?php echo $tjurnal->laporan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnal->debet->Visible) { // debet ?>
	<div id="r_debet" class="form-group">
		<label id="elh_tjurnal_debet" for="x_debet" class="col-sm-2 control-label ewLabel"><?php echo $tjurnal->debet->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnal->debet->CellAttributes() ?>>
<span id="el_tjurnal_debet">
<input type="text" data-table="tjurnal" data-field="x_debet" name="x_debet" id="x_debet" size="30" placeholder="<?php echo ew_HtmlEncode($tjurnal->debet->getPlaceHolder()) ?>" value="<?php echo $tjurnal->debet->EditValue ?>"<?php echo $tjurnal->debet->EditAttributes() ?>>
</span>
<?php echo $tjurnal->debet->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnal->credit->Visible) { // credit ?>
	<div id="r_credit" class="form-group">
		<label id="elh_tjurnal_credit" for="x_credit" class="col-sm-2 control-label ewLabel"><?php echo $tjurnal->credit->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnal->credit->CellAttributes() ?>>
<span id="el_tjurnal_credit">
<input type="text" data-table="tjurnal" data-field="x_credit" name="x_credit" id="x_credit" size="30" placeholder="<?php echo ew_HtmlEncode($tjurnal->credit->getPlaceHolder()) ?>" value="<?php echo $tjurnal->credit->EditValue ?>"<?php echo $tjurnal->credit->EditAttributes() ?>>
</span>
<?php echo $tjurnal->credit->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnal->kantor->Visible) { // kantor ?>
	<div id="r_kantor" class="form-group">
		<label id="elh_tjurnal_kantor" for="x_kantor" class="col-sm-2 control-label ewLabel"><?php echo $tjurnal->kantor->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnal->kantor->CellAttributes() ?>>
<span id="el_tjurnal_kantor">
<input type="text" data-table="tjurnal" data-field="x_kantor" name="x_kantor" id="x_kantor" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tjurnal->kantor->getPlaceHolder()) ?>" value="<?php echo $tjurnal->kantor->EditValue ?>"<?php echo $tjurnal->kantor->EditAttributes() ?>>
</span>
<?php echo $tjurnal->kantor->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnal->keterangan->Visible) { // keterangan ?>
	<div id="r_keterangan" class="form-group">
		<label id="elh_tjurnal_keterangan" for="x_keterangan" class="col-sm-2 control-label ewLabel"><?php echo $tjurnal->keterangan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnal->keterangan->CellAttributes() ?>>
<span id="el_tjurnal_keterangan">
<input type="text" data-table="tjurnal" data-field="x_keterangan" name="x_keterangan" id="x_keterangan" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($tjurnal->keterangan->getPlaceHolder()) ?>" value="<?php echo $tjurnal->keterangan->EditValue ?>"<?php echo $tjurnal->keterangan->EditAttributes() ?>>
</span>
<?php echo $tjurnal->keterangan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnal->active->Visible) { // active ?>
	<div id="r_active" class="form-group">
		<label id="elh_tjurnal_active" class="col-sm-2 control-label ewLabel"><?php echo $tjurnal->active->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnal->active->CellAttributes() ?>>
<span id="el_tjurnal_active">
<div id="tp_x_active" class="ewTemplate"><input type="radio" data-table="tjurnal" data-field="x_active" data-value-separator="<?php echo $tjurnal->active->DisplayValueSeparatorAttribute() ?>" name="x_active" id="x_active" value="{value}"<?php echo $tjurnal->active->EditAttributes() ?>></div>
<div id="dsl_x_active" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $tjurnal->active->RadioButtonListHtml(FALSE, "x_active") ?>
</div></div>
</span>
<?php echo $tjurnal->active->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnal->ip->Visible) { // ip ?>
	<div id="r_ip" class="form-group">
		<label id="elh_tjurnal_ip" for="x_ip" class="col-sm-2 control-label ewLabel"><?php echo $tjurnal->ip->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnal->ip->CellAttributes() ?>>
<span id="el_tjurnal_ip">
<input type="text" data-table="tjurnal" data-field="x_ip" name="x_ip" id="x_ip" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tjurnal->ip->getPlaceHolder()) ?>" value="<?php echo $tjurnal->ip->EditValue ?>"<?php echo $tjurnal->ip->EditAttributes() ?>>
</span>
<?php echo $tjurnal->ip->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnal->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_tjurnal_status" for="x_status" class="col-sm-2 control-label ewLabel"><?php echo $tjurnal->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnal->status->CellAttributes() ?>>
<span id="el_tjurnal_status">
<input type="text" data-table="tjurnal" data-field="x_status" name="x_status" id="x_status" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tjurnal->status->getPlaceHolder()) ?>" value="<?php echo $tjurnal->status->EditValue ?>"<?php echo $tjurnal->status->EditAttributes() ?>>
</span>
<?php echo $tjurnal->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnal->user->Visible) { // user ?>
	<div id="r_user" class="form-group">
		<label id="elh_tjurnal_user" for="x_user" class="col-sm-2 control-label ewLabel"><?php echo $tjurnal->user->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnal->user->CellAttributes() ?>>
<span id="el_tjurnal_user">
<input type="text" data-table="tjurnal" data-field="x_user" name="x_user" id="x_user" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tjurnal->user->getPlaceHolder()) ?>" value="<?php echo $tjurnal->user->EditValue ?>"<?php echo $tjurnal->user->EditAttributes() ?>>
</span>
<?php echo $tjurnal->user->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnal->created->Visible) { // created ?>
	<div id="r_created" class="form-group">
		<label id="elh_tjurnal_created" for="x_created" class="col-sm-2 control-label ewLabel"><?php echo $tjurnal->created->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnal->created->CellAttributes() ?>>
<span id="el_tjurnal_created">
<input type="text" data-table="tjurnal" data-field="x_created" name="x_created" id="x_created" placeholder="<?php echo ew_HtmlEncode($tjurnal->created->getPlaceHolder()) ?>" value="<?php echo $tjurnal->created->EditValue ?>"<?php echo $tjurnal->created->EditAttributes() ?>>
</span>
<?php echo $tjurnal->created->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tjurnal->modified->Visible) { // modified ?>
	<div id="r_modified" class="form-group">
		<label id="elh_tjurnal_modified" for="x_modified" class="col-sm-2 control-label ewLabel"><?php echo $tjurnal->modified->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tjurnal->modified->CellAttributes() ?>>
<span id="el_tjurnal_modified">
<input type="text" data-table="tjurnal" data-field="x_modified" name="x_modified" id="x_modified" placeholder="<?php echo ew_HtmlEncode($tjurnal->modified->getPlaceHolder()) ?>" value="<?php echo $tjurnal->modified->EditValue ?>"<?php echo $tjurnal->modified->EditAttributes() ?>>
</span>
<?php echo $tjurnal->modified->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$tjurnal_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $tjurnal_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ftjurnaladd.Init();
</script>
<?php
$tjurnal_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tjurnal_add->Page_Terminate();
?>
