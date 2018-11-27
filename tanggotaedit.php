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

$tanggota_edit = NULL; // Initialize page object first

class ctanggota_edit extends ctanggota {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tanggota';

	// Page object name
	var $PageObjName = 'tanggota_edit';

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

		// Table object (tanggota)
		if (!isset($GLOBALS["tanggota"]) || get_class($GLOBALS["tanggota"]) == "ctanggota") {
			$GLOBALS["tanggota"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tanggota"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tanggota', TRUE);

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

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->id->CurrentValue == "") {
			$this->Page_Terminate("tanggotalist.php"); // Invalid key, return to list
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
					$this->Page_Terminate("tanggotalist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "tanggotalist.php")
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
		if (!$this->registrasi->FldIsDetailKey) {
			$this->registrasi->setFormValue($objForm->GetValue("x_registrasi"));
			$this->registrasi->CurrentValue = ew_UnFormatDateTime($this->registrasi->CurrentValue, 0);
		}
		if (!$this->periode->FldIsDetailKey) {
			$this->periode->setFormValue($objForm->GetValue("x_periode"));
		}
		if (!$this->id->FldIsDetailKey) {
			$this->id->setFormValue($objForm->GetValue("x_id"));
		}
		if (!$this->anggota->FldIsDetailKey) {
			$this->anggota->setFormValue($objForm->GetValue("x_anggota"));
		}
		if (!$this->namaanggota->FldIsDetailKey) {
			$this->namaanggota->setFormValue($objForm->GetValue("x_namaanggota"));
		}
		if (!$this->alamat->FldIsDetailKey) {
			$this->alamat->setFormValue($objForm->GetValue("x_alamat"));
		}
		if (!$this->tempatlahir->FldIsDetailKey) {
			$this->tempatlahir->setFormValue($objForm->GetValue("x_tempatlahir"));
		}
		if (!$this->tanggallahir->FldIsDetailKey) {
			$this->tanggallahir->setFormValue($objForm->GetValue("x_tanggallahir"));
			$this->tanggallahir->CurrentValue = ew_UnFormatDateTime($this->tanggallahir->CurrentValue, 0);
		}
		if (!$this->kelamin->FldIsDetailKey) {
			$this->kelamin->setFormValue($objForm->GetValue("x_kelamin"));
		}
		if (!$this->pekerjaan->FldIsDetailKey) {
			$this->pekerjaan->setFormValue($objForm->GetValue("x_pekerjaan"));
		}
		if (!$this->telepon->FldIsDetailKey) {
			$this->telepon->setFormValue($objForm->GetValue("x_telepon"));
		}
		if (!$this->hp->FldIsDetailKey) {
			$this->hp->setFormValue($objForm->GetValue("x_hp"));
		}
		if (!$this->fax->FldIsDetailKey) {
			$this->fax->setFormValue($objForm->GetValue("x_fax"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->website->FldIsDetailKey) {
			$this->website->setFormValue($objForm->GetValue("x_website"));
		}
		if (!$this->jenisanggota->FldIsDetailKey) {
			$this->jenisanggota->setFormValue($objForm->GetValue("x_jenisanggota"));
		}
		if (!$this->model->FldIsDetailKey) {
			$this->model->setFormValue($objForm->GetValue("x_model"));
		}
		if (!$this->namakantor->FldIsDetailKey) {
			$this->namakantor->setFormValue($objForm->GetValue("x_namakantor"));
		}
		if (!$this->alamatkantor->FldIsDetailKey) {
			$this->alamatkantor->setFormValue($objForm->GetValue("x_alamatkantor"));
		}
		if (!$this->wilayah->FldIsDetailKey) {
			$this->wilayah->setFormValue($objForm->GetValue("x_wilayah"));
		}
		if (!$this->petugas->FldIsDetailKey) {
			$this->petugas->setFormValue($objForm->GetValue("x_petugas"));
		}
		if (!$this->pembayaran->FldIsDetailKey) {
			$this->pembayaran->setFormValue($objForm->GetValue("x_pembayaran"));
		}
		if (!$this->bank->FldIsDetailKey) {
			$this->bank->setFormValue($objForm->GetValue("x_bank"));
		}
		if (!$this->atasnama->FldIsDetailKey) {
			$this->atasnama->setFormValue($objForm->GetValue("x_atasnama"));
		}
		if (!$this->tipe->FldIsDetailKey) {
			$this->tipe->setFormValue($objForm->GetValue("x_tipe"));
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
		$this->registrasi->CurrentValue = $this->registrasi->FormValue;
		$this->registrasi->CurrentValue = ew_UnFormatDateTime($this->registrasi->CurrentValue, 0);
		$this->periode->CurrentValue = $this->periode->FormValue;
		$this->id->CurrentValue = $this->id->FormValue;
		$this->anggota->CurrentValue = $this->anggota->FormValue;
		$this->namaanggota->CurrentValue = $this->namaanggota->FormValue;
		$this->alamat->CurrentValue = $this->alamat->FormValue;
		$this->tempatlahir->CurrentValue = $this->tempatlahir->FormValue;
		$this->tanggallahir->CurrentValue = $this->tanggallahir->FormValue;
		$this->tanggallahir->CurrentValue = ew_UnFormatDateTime($this->tanggallahir->CurrentValue, 0);
		$this->kelamin->CurrentValue = $this->kelamin->FormValue;
		$this->pekerjaan->CurrentValue = $this->pekerjaan->FormValue;
		$this->telepon->CurrentValue = $this->telepon->FormValue;
		$this->hp->CurrentValue = $this->hp->FormValue;
		$this->fax->CurrentValue = $this->fax->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->website->CurrentValue = $this->website->FormValue;
		$this->jenisanggota->CurrentValue = $this->jenisanggota->FormValue;
		$this->model->CurrentValue = $this->model->FormValue;
		$this->namakantor->CurrentValue = $this->namakantor->FormValue;
		$this->alamatkantor->CurrentValue = $this->alamatkantor->FormValue;
		$this->wilayah->CurrentValue = $this->wilayah->FormValue;
		$this->petugas->CurrentValue = $this->petugas->FormValue;
		$this->pembayaran->CurrentValue = $this->pembayaran->FormValue;
		$this->bank->CurrentValue = $this->bank->FormValue;
		$this->atasnama->CurrentValue = $this->atasnama->FormValue;
		$this->tipe->CurrentValue = $this->tipe->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// registrasi
			$this->registrasi->EditAttrs["class"] = "form-control";
			$this->registrasi->EditCustomAttributes = "";
			$this->registrasi->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->registrasi->CurrentValue, 8));
			$this->registrasi->PlaceHolder = ew_RemoveHtml($this->registrasi->FldCaption());

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

			// anggota
			$this->anggota->EditAttrs["class"] = "form-control";
			$this->anggota->EditCustomAttributes = "";
			$this->anggota->EditValue = ew_HtmlEncode($this->anggota->CurrentValue);
			$this->anggota->PlaceHolder = ew_RemoveHtml($this->anggota->FldCaption());

			// namaanggota
			$this->namaanggota->EditAttrs["class"] = "form-control";
			$this->namaanggota->EditCustomAttributes = "";
			$this->namaanggota->EditValue = ew_HtmlEncode($this->namaanggota->CurrentValue);
			$this->namaanggota->PlaceHolder = ew_RemoveHtml($this->namaanggota->FldCaption());

			// alamat
			$this->alamat->EditAttrs["class"] = "form-control";
			$this->alamat->EditCustomAttributes = "";
			$this->alamat->EditValue = ew_HtmlEncode($this->alamat->CurrentValue);
			$this->alamat->PlaceHolder = ew_RemoveHtml($this->alamat->FldCaption());

			// tempatlahir
			$this->tempatlahir->EditAttrs["class"] = "form-control";
			$this->tempatlahir->EditCustomAttributes = "";
			$this->tempatlahir->EditValue = ew_HtmlEncode($this->tempatlahir->CurrentValue);
			$this->tempatlahir->PlaceHolder = ew_RemoveHtml($this->tempatlahir->FldCaption());

			// tanggallahir
			$this->tanggallahir->EditAttrs["class"] = "form-control";
			$this->tanggallahir->EditCustomAttributes = "";
			$this->tanggallahir->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tanggallahir->CurrentValue, 8));
			$this->tanggallahir->PlaceHolder = ew_RemoveHtml($this->tanggallahir->FldCaption());

			// kelamin
			$this->kelamin->EditAttrs["class"] = "form-control";
			$this->kelamin->EditCustomAttributes = "";
			$this->kelamin->EditValue = ew_HtmlEncode($this->kelamin->CurrentValue);
			$this->kelamin->PlaceHolder = ew_RemoveHtml($this->kelamin->FldCaption());

			// pekerjaan
			$this->pekerjaan->EditAttrs["class"] = "form-control";
			$this->pekerjaan->EditCustomAttributes = "";
			$this->pekerjaan->EditValue = ew_HtmlEncode($this->pekerjaan->CurrentValue);
			$this->pekerjaan->PlaceHolder = ew_RemoveHtml($this->pekerjaan->FldCaption());

			// telepon
			$this->telepon->EditAttrs["class"] = "form-control";
			$this->telepon->EditCustomAttributes = "";
			$this->telepon->EditValue = ew_HtmlEncode($this->telepon->CurrentValue);
			$this->telepon->PlaceHolder = ew_RemoveHtml($this->telepon->FldCaption());

			// hp
			$this->hp->EditAttrs["class"] = "form-control";
			$this->hp->EditCustomAttributes = "";
			$this->hp->EditValue = ew_HtmlEncode($this->hp->CurrentValue);
			$this->hp->PlaceHolder = ew_RemoveHtml($this->hp->FldCaption());

			// fax
			$this->fax->EditAttrs["class"] = "form-control";
			$this->fax->EditCustomAttributes = "";
			$this->fax->EditValue = ew_HtmlEncode($this->fax->CurrentValue);
			$this->fax->PlaceHolder = ew_RemoveHtml($this->fax->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// website
			$this->website->EditAttrs["class"] = "form-control";
			$this->website->EditCustomAttributes = "";
			$this->website->EditValue = ew_HtmlEncode($this->website->CurrentValue);
			$this->website->PlaceHolder = ew_RemoveHtml($this->website->FldCaption());

			// jenisanggota
			$this->jenisanggota->EditAttrs["class"] = "form-control";
			$this->jenisanggota->EditCustomAttributes = "";
			$this->jenisanggota->EditValue = ew_HtmlEncode($this->jenisanggota->CurrentValue);
			$this->jenisanggota->PlaceHolder = ew_RemoveHtml($this->jenisanggota->FldCaption());

			// model
			$this->model->EditAttrs["class"] = "form-control";
			$this->model->EditCustomAttributes = "";
			$this->model->EditValue = ew_HtmlEncode($this->model->CurrentValue);
			$this->model->PlaceHolder = ew_RemoveHtml($this->model->FldCaption());

			// namakantor
			$this->namakantor->EditAttrs["class"] = "form-control";
			$this->namakantor->EditCustomAttributes = "";
			$this->namakantor->EditValue = ew_HtmlEncode($this->namakantor->CurrentValue);
			$this->namakantor->PlaceHolder = ew_RemoveHtml($this->namakantor->FldCaption());

			// alamatkantor
			$this->alamatkantor->EditAttrs["class"] = "form-control";
			$this->alamatkantor->EditCustomAttributes = "";
			$this->alamatkantor->EditValue = ew_HtmlEncode($this->alamatkantor->CurrentValue);
			$this->alamatkantor->PlaceHolder = ew_RemoveHtml($this->alamatkantor->FldCaption());

			// wilayah
			$this->wilayah->EditAttrs["class"] = "form-control";
			$this->wilayah->EditCustomAttributes = "";
			$this->wilayah->EditValue = ew_HtmlEncode($this->wilayah->CurrentValue);
			$this->wilayah->PlaceHolder = ew_RemoveHtml($this->wilayah->FldCaption());

			// petugas
			$this->petugas->EditAttrs["class"] = "form-control";
			$this->petugas->EditCustomAttributes = "";
			$this->petugas->EditValue = ew_HtmlEncode($this->petugas->CurrentValue);
			$this->petugas->PlaceHolder = ew_RemoveHtml($this->petugas->FldCaption());

			// pembayaran
			$this->pembayaran->EditAttrs["class"] = "form-control";
			$this->pembayaran->EditCustomAttributes = "";
			$this->pembayaran->EditValue = ew_HtmlEncode($this->pembayaran->CurrentValue);
			$this->pembayaran->PlaceHolder = ew_RemoveHtml($this->pembayaran->FldCaption());

			// bank
			$this->bank->EditAttrs["class"] = "form-control";
			$this->bank->EditCustomAttributes = "";
			$this->bank->EditValue = ew_HtmlEncode($this->bank->CurrentValue);
			$this->bank->PlaceHolder = ew_RemoveHtml($this->bank->FldCaption());

			// atasnama
			$this->atasnama->EditAttrs["class"] = "form-control";
			$this->atasnama->EditCustomAttributes = "";
			$this->atasnama->EditValue = ew_HtmlEncode($this->atasnama->CurrentValue);
			$this->atasnama->PlaceHolder = ew_RemoveHtml($this->atasnama->FldCaption());

			// tipe
			$this->tipe->EditAttrs["class"] = "form-control";
			$this->tipe->EditCustomAttributes = "";
			$this->tipe->EditValue = ew_HtmlEncode($this->tipe->CurrentValue);
			$this->tipe->PlaceHolder = ew_RemoveHtml($this->tipe->FldCaption());

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
			// registrasi

			$this->registrasi->LinkCustomAttributes = "";
			$this->registrasi->HrefValue = "";

			// periode
			$this->periode->LinkCustomAttributes = "";
			$this->periode->HrefValue = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";

			// anggota
			$this->anggota->LinkCustomAttributes = "";
			$this->anggota->HrefValue = "";

			// namaanggota
			$this->namaanggota->LinkCustomAttributes = "";
			$this->namaanggota->HrefValue = "";

			// alamat
			$this->alamat->LinkCustomAttributes = "";
			$this->alamat->HrefValue = "";

			// tempatlahir
			$this->tempatlahir->LinkCustomAttributes = "";
			$this->tempatlahir->HrefValue = "";

			// tanggallahir
			$this->tanggallahir->LinkCustomAttributes = "";
			$this->tanggallahir->HrefValue = "";

			// kelamin
			$this->kelamin->LinkCustomAttributes = "";
			$this->kelamin->HrefValue = "";

			// pekerjaan
			$this->pekerjaan->LinkCustomAttributes = "";
			$this->pekerjaan->HrefValue = "";

			// telepon
			$this->telepon->LinkCustomAttributes = "";
			$this->telepon->HrefValue = "";

			// hp
			$this->hp->LinkCustomAttributes = "";
			$this->hp->HrefValue = "";

			// fax
			$this->fax->LinkCustomAttributes = "";
			$this->fax->HrefValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";

			// website
			$this->website->LinkCustomAttributes = "";
			$this->website->HrefValue = "";

			// jenisanggota
			$this->jenisanggota->LinkCustomAttributes = "";
			$this->jenisanggota->HrefValue = "";

			// model
			$this->model->LinkCustomAttributes = "";
			$this->model->HrefValue = "";

			// namakantor
			$this->namakantor->LinkCustomAttributes = "";
			$this->namakantor->HrefValue = "";

			// alamatkantor
			$this->alamatkantor->LinkCustomAttributes = "";
			$this->alamatkantor->HrefValue = "";

			// wilayah
			$this->wilayah->LinkCustomAttributes = "";
			$this->wilayah->HrefValue = "";

			// petugas
			$this->petugas->LinkCustomAttributes = "";
			$this->petugas->HrefValue = "";

			// pembayaran
			$this->pembayaran->LinkCustomAttributes = "";
			$this->pembayaran->HrefValue = "";

			// bank
			$this->bank->LinkCustomAttributes = "";
			$this->bank->HrefValue = "";

			// atasnama
			$this->atasnama->LinkCustomAttributes = "";
			$this->atasnama->HrefValue = "";

			// tipe
			$this->tipe->LinkCustomAttributes = "";
			$this->tipe->HrefValue = "";

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
		if (!$this->registrasi->FldIsDetailKey && !is_null($this->registrasi->FormValue) && $this->registrasi->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->registrasi->FldCaption(), $this->registrasi->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->registrasi->FormValue)) {
			ew_AddMessage($gsFormError, $this->registrasi->FldErrMsg());
		}
		if (!$this->periode->FldIsDetailKey && !is_null($this->periode->FormValue) && $this->periode->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->periode->FldCaption(), $this->periode->ReqErrMsg));
		}
		if (!$this->id->FldIsDetailKey && !is_null($this->id->FormValue) && $this->id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id->FldCaption(), $this->id->ReqErrMsg));
		}
		if (!$this->anggota->FldIsDetailKey && !is_null($this->anggota->FormValue) && $this->anggota->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->anggota->FldCaption(), $this->anggota->ReqErrMsg));
		}
		if (!$this->namaanggota->FldIsDetailKey && !is_null($this->namaanggota->FormValue) && $this->namaanggota->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->namaanggota->FldCaption(), $this->namaanggota->ReqErrMsg));
		}
		if (!$this->alamat->FldIsDetailKey && !is_null($this->alamat->FormValue) && $this->alamat->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->alamat->FldCaption(), $this->alamat->ReqErrMsg));
		}
		if (!$this->tempatlahir->FldIsDetailKey && !is_null($this->tempatlahir->FormValue) && $this->tempatlahir->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tempatlahir->FldCaption(), $this->tempatlahir->ReqErrMsg));
		}
		if (!$this->tanggallahir->FldIsDetailKey && !is_null($this->tanggallahir->FormValue) && $this->tanggallahir->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tanggallahir->FldCaption(), $this->tanggallahir->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->tanggallahir->FormValue)) {
			ew_AddMessage($gsFormError, $this->tanggallahir->FldErrMsg());
		}
		if (!$this->kelamin->FldIsDetailKey && !is_null($this->kelamin->FormValue) && $this->kelamin->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->kelamin->FldCaption(), $this->kelamin->ReqErrMsg));
		}
		if (!$this->pekerjaan->FldIsDetailKey && !is_null($this->pekerjaan->FormValue) && $this->pekerjaan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pekerjaan->FldCaption(), $this->pekerjaan->ReqErrMsg));
		}
		if (!$this->telepon->FldIsDetailKey && !is_null($this->telepon->FormValue) && $this->telepon->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->telepon->FldCaption(), $this->telepon->ReqErrMsg));
		}
		if (!$this->hp->FldIsDetailKey && !is_null($this->hp->FormValue) && $this->hp->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->hp->FldCaption(), $this->hp->ReqErrMsg));
		}
		if (!$this->fax->FldIsDetailKey && !is_null($this->fax->FormValue) && $this->fax->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->fax->FldCaption(), $this->fax->ReqErrMsg));
		}
		if (!$this->_email->FldIsDetailKey && !is_null($this->_email->FormValue) && $this->_email->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->_email->FldCaption(), $this->_email->ReqErrMsg));
		}
		if (!$this->website->FldIsDetailKey && !is_null($this->website->FormValue) && $this->website->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->website->FldCaption(), $this->website->ReqErrMsg));
		}
		if (!$this->jenisanggota->FldIsDetailKey && !is_null($this->jenisanggota->FormValue) && $this->jenisanggota->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jenisanggota->FldCaption(), $this->jenisanggota->ReqErrMsg));
		}
		if (!$this->model->FldIsDetailKey && !is_null($this->model->FormValue) && $this->model->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->model->FldCaption(), $this->model->ReqErrMsg));
		}
		if (!$this->namakantor->FldIsDetailKey && !is_null($this->namakantor->FormValue) && $this->namakantor->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->namakantor->FldCaption(), $this->namakantor->ReqErrMsg));
		}
		if (!$this->alamatkantor->FldIsDetailKey && !is_null($this->alamatkantor->FormValue) && $this->alamatkantor->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->alamatkantor->FldCaption(), $this->alamatkantor->ReqErrMsg));
		}
		if (!$this->wilayah->FldIsDetailKey && !is_null($this->wilayah->FormValue) && $this->wilayah->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->wilayah->FldCaption(), $this->wilayah->ReqErrMsg));
		}
		if (!$this->petugas->FldIsDetailKey && !is_null($this->petugas->FormValue) && $this->petugas->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->petugas->FldCaption(), $this->petugas->ReqErrMsg));
		}
		if (!$this->pembayaran->FldIsDetailKey && !is_null($this->pembayaran->FormValue) && $this->pembayaran->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pembayaran->FldCaption(), $this->pembayaran->ReqErrMsg));
		}
		if (!$this->bank->FldIsDetailKey && !is_null($this->bank->FormValue) && $this->bank->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->bank->FldCaption(), $this->bank->ReqErrMsg));
		}
		if (!$this->atasnama->FldIsDetailKey && !is_null($this->atasnama->FormValue) && $this->atasnama->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->atasnama->FldCaption(), $this->atasnama->ReqErrMsg));
		}
		if (!$this->tipe->FldIsDetailKey && !is_null($this->tipe->FormValue) && $this->tipe->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tipe->FldCaption(), $this->tipe->ReqErrMsg));
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

			// registrasi
			$this->registrasi->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->registrasi->CurrentValue, 0), ew_CurrentDate(), $this->registrasi->ReadOnly);

			// periode
			$this->periode->SetDbValueDef($rsnew, $this->periode->CurrentValue, "", $this->periode->ReadOnly);

			// id
			// anggota

			$this->anggota->SetDbValueDef($rsnew, $this->anggota->CurrentValue, "", $this->anggota->ReadOnly);

			// namaanggota
			$this->namaanggota->SetDbValueDef($rsnew, $this->namaanggota->CurrentValue, "", $this->namaanggota->ReadOnly);

			// alamat
			$this->alamat->SetDbValueDef($rsnew, $this->alamat->CurrentValue, "", $this->alamat->ReadOnly);

			// tempatlahir
			$this->tempatlahir->SetDbValueDef($rsnew, $this->tempatlahir->CurrentValue, "", $this->tempatlahir->ReadOnly);

			// tanggallahir
			$this->tanggallahir->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tanggallahir->CurrentValue, 0), ew_CurrentDate(), $this->tanggallahir->ReadOnly);

			// kelamin
			$this->kelamin->SetDbValueDef($rsnew, $this->kelamin->CurrentValue, "", $this->kelamin->ReadOnly);

			// pekerjaan
			$this->pekerjaan->SetDbValueDef($rsnew, $this->pekerjaan->CurrentValue, "", $this->pekerjaan->ReadOnly);

			// telepon
			$this->telepon->SetDbValueDef($rsnew, $this->telepon->CurrentValue, "", $this->telepon->ReadOnly);

			// hp
			$this->hp->SetDbValueDef($rsnew, $this->hp->CurrentValue, "", $this->hp->ReadOnly);

			// fax
			$this->fax->SetDbValueDef($rsnew, $this->fax->CurrentValue, "", $this->fax->ReadOnly);

			// email
			$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, "", $this->_email->ReadOnly);

			// website
			$this->website->SetDbValueDef($rsnew, $this->website->CurrentValue, "", $this->website->ReadOnly);

			// jenisanggota
			$this->jenisanggota->SetDbValueDef($rsnew, $this->jenisanggota->CurrentValue, "", $this->jenisanggota->ReadOnly);

			// model
			$this->model->SetDbValueDef($rsnew, $this->model->CurrentValue, "", $this->model->ReadOnly);

			// namakantor
			$this->namakantor->SetDbValueDef($rsnew, $this->namakantor->CurrentValue, "", $this->namakantor->ReadOnly);

			// alamatkantor
			$this->alamatkantor->SetDbValueDef($rsnew, $this->alamatkantor->CurrentValue, "", $this->alamatkantor->ReadOnly);

			// wilayah
			$this->wilayah->SetDbValueDef($rsnew, $this->wilayah->CurrentValue, "", $this->wilayah->ReadOnly);

			// petugas
			$this->petugas->SetDbValueDef($rsnew, $this->petugas->CurrentValue, "", $this->petugas->ReadOnly);

			// pembayaran
			$this->pembayaran->SetDbValueDef($rsnew, $this->pembayaran->CurrentValue, "", $this->pembayaran->ReadOnly);

			// bank
			$this->bank->SetDbValueDef($rsnew, $this->bank->CurrentValue, "", $this->bank->ReadOnly);

			// atasnama
			$this->atasnama->SetDbValueDef($rsnew, $this->atasnama->CurrentValue, "", $this->atasnama->ReadOnly);

			// tipe
			$this->tipe->SetDbValueDef($rsnew, $this->tipe->CurrentValue, "", $this->tipe->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("tanggotalist.php"), "", $this->TableVar, TRUE);
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
if (!isset($tanggota_edit)) $tanggota_edit = new ctanggota_edit();

// Page init
$tanggota_edit->Page_Init();

// Page main
$tanggota_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tanggota_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = ftanggotaedit = new ew_Form("ftanggotaedit", "edit");

// Validate form
ftanggotaedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_registrasi");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->registrasi->FldCaption(), $tanggota->registrasi->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_registrasi");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tanggota->registrasi->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_periode");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->periode->FldCaption(), $tanggota->periode->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->id->FldCaption(), $tanggota->id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_anggota");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->anggota->FldCaption(), $tanggota->anggota->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_namaanggota");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->namaanggota->FldCaption(), $tanggota->namaanggota->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_alamat");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->alamat->FldCaption(), $tanggota->alamat->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tempatlahir");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->tempatlahir->FldCaption(), $tanggota->tempatlahir->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tanggallahir");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->tanggallahir->FldCaption(), $tanggota->tanggallahir->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tanggallahir");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tanggota->tanggallahir->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_kelamin");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->kelamin->FldCaption(), $tanggota->kelamin->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pekerjaan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->pekerjaan->FldCaption(), $tanggota->pekerjaan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_telepon");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->telepon->FldCaption(), $tanggota->telepon->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_hp");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->hp->FldCaption(), $tanggota->hp->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fax");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->fax->FldCaption(), $tanggota->fax->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__email");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->_email->FldCaption(), $tanggota->_email->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_website");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->website->FldCaption(), $tanggota->website->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jenisanggota");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->jenisanggota->FldCaption(), $tanggota->jenisanggota->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_model");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->model->FldCaption(), $tanggota->model->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_namakantor");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->namakantor->FldCaption(), $tanggota->namakantor->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_alamatkantor");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->alamatkantor->FldCaption(), $tanggota->alamatkantor->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_wilayah");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->wilayah->FldCaption(), $tanggota->wilayah->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_petugas");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->petugas->FldCaption(), $tanggota->petugas->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pembayaran");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->pembayaran->FldCaption(), $tanggota->pembayaran->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bank");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->bank->FldCaption(), $tanggota->bank->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_atasnama");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->atasnama->FldCaption(), $tanggota->atasnama->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tipe");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->tipe->FldCaption(), $tanggota->tipe->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_kantor");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->kantor->FldCaption(), $tanggota->kantor->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_keterangan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->keterangan->FldCaption(), $tanggota->keterangan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_active");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->active->FldCaption(), $tanggota->active->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ip");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->ip->FldCaption(), $tanggota->ip->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->status->FldCaption(), $tanggota->status->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_user");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->user->FldCaption(), $tanggota->user->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_created");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->created->FldCaption(), $tanggota->created->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_created");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tanggota->created->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modified");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tanggota->modified->FldCaption(), $tanggota->modified->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modified");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tanggota->modified->FldErrMsg()) ?>");

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
ftanggotaedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftanggotaedit.ValidateRequired = true;
<?php } else { ?>
ftanggotaedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftanggotaedit.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftanggotaedit.Lists["x_active"].Options = <?php echo json_encode($tanggota->active->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$tanggota_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $tanggota_edit->ShowPageHeader(); ?>
<?php
$tanggota_edit->ShowMessage();
?>
<form name="ftanggotaedit" id="ftanggotaedit" class="<?php echo $tanggota_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tanggota_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tanggota_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tanggota">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($tanggota_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($tanggota->registrasi->Visible) { // registrasi ?>
	<div id="r_registrasi" class="form-group">
		<label id="elh_tanggota_registrasi" for="x_registrasi" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->registrasi->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->registrasi->CellAttributes() ?>>
<span id="el_tanggota_registrasi">
<input type="text" data-table="tanggota" data-field="x_registrasi" name="x_registrasi" id="x_registrasi" placeholder="<?php echo ew_HtmlEncode($tanggota->registrasi->getPlaceHolder()) ?>" value="<?php echo $tanggota->registrasi->EditValue ?>"<?php echo $tanggota->registrasi->EditAttributes() ?>>
</span>
<?php echo $tanggota->registrasi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->periode->Visible) { // periode ?>
	<div id="r_periode" class="form-group">
		<label id="elh_tanggota_periode" for="x_periode" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->periode->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->periode->CellAttributes() ?>>
<span id="el_tanggota_periode">
<input type="text" data-table="tanggota" data-field="x_periode" name="x_periode" id="x_periode" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tanggota->periode->getPlaceHolder()) ?>" value="<?php echo $tanggota->periode->EditValue ?>"<?php echo $tanggota->periode->EditAttributes() ?>>
</span>
<?php echo $tanggota->periode->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_tanggota_id" for="x_id" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->id->CellAttributes() ?>>
<span id="el_tanggota_id">
<span<?php echo $tanggota->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $tanggota->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="tanggota" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($tanggota->id->CurrentValue) ?>">
<?php echo $tanggota->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->anggota->Visible) { // anggota ?>
	<div id="r_anggota" class="form-group">
		<label id="elh_tanggota_anggota" for="x_anggota" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->anggota->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->anggota->CellAttributes() ?>>
<span id="el_tanggota_anggota">
<input type="text" data-table="tanggota" data-field="x_anggota" name="x_anggota" id="x_anggota" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tanggota->anggota->getPlaceHolder()) ?>" value="<?php echo $tanggota->anggota->EditValue ?>"<?php echo $tanggota->anggota->EditAttributes() ?>>
</span>
<?php echo $tanggota->anggota->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->namaanggota->Visible) { // namaanggota ?>
	<div id="r_namaanggota" class="form-group">
		<label id="elh_tanggota_namaanggota" for="x_namaanggota" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->namaanggota->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->namaanggota->CellAttributes() ?>>
<span id="el_tanggota_namaanggota">
<input type="text" data-table="tanggota" data-field="x_namaanggota" name="x_namaanggota" id="x_namaanggota" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($tanggota->namaanggota->getPlaceHolder()) ?>" value="<?php echo $tanggota->namaanggota->EditValue ?>"<?php echo $tanggota->namaanggota->EditAttributes() ?>>
</span>
<?php echo $tanggota->namaanggota->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->alamat->Visible) { // alamat ?>
	<div id="r_alamat" class="form-group">
		<label id="elh_tanggota_alamat" for="x_alamat" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->alamat->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->alamat->CellAttributes() ?>>
<span id="el_tanggota_alamat">
<input type="text" data-table="tanggota" data-field="x_alamat" name="x_alamat" id="x_alamat" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($tanggota->alamat->getPlaceHolder()) ?>" value="<?php echo $tanggota->alamat->EditValue ?>"<?php echo $tanggota->alamat->EditAttributes() ?>>
</span>
<?php echo $tanggota->alamat->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->tempatlahir->Visible) { // tempatlahir ?>
	<div id="r_tempatlahir" class="form-group">
		<label id="elh_tanggota_tempatlahir" for="x_tempatlahir" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->tempatlahir->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->tempatlahir->CellAttributes() ?>>
<span id="el_tanggota_tempatlahir">
<input type="text" data-table="tanggota" data-field="x_tempatlahir" name="x_tempatlahir" id="x_tempatlahir" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tanggota->tempatlahir->getPlaceHolder()) ?>" value="<?php echo $tanggota->tempatlahir->EditValue ?>"<?php echo $tanggota->tempatlahir->EditAttributes() ?>>
</span>
<?php echo $tanggota->tempatlahir->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->tanggallahir->Visible) { // tanggallahir ?>
	<div id="r_tanggallahir" class="form-group">
		<label id="elh_tanggota_tanggallahir" for="x_tanggallahir" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->tanggallahir->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->tanggallahir->CellAttributes() ?>>
<span id="el_tanggota_tanggallahir">
<input type="text" data-table="tanggota" data-field="x_tanggallahir" name="x_tanggallahir" id="x_tanggallahir" placeholder="<?php echo ew_HtmlEncode($tanggota->tanggallahir->getPlaceHolder()) ?>" value="<?php echo $tanggota->tanggallahir->EditValue ?>"<?php echo $tanggota->tanggallahir->EditAttributes() ?>>
</span>
<?php echo $tanggota->tanggallahir->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->kelamin->Visible) { // kelamin ?>
	<div id="r_kelamin" class="form-group">
		<label id="elh_tanggota_kelamin" for="x_kelamin" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->kelamin->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->kelamin->CellAttributes() ?>>
<span id="el_tanggota_kelamin">
<input type="text" data-table="tanggota" data-field="x_kelamin" name="x_kelamin" id="x_kelamin" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tanggota->kelamin->getPlaceHolder()) ?>" value="<?php echo $tanggota->kelamin->EditValue ?>"<?php echo $tanggota->kelamin->EditAttributes() ?>>
</span>
<?php echo $tanggota->kelamin->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->pekerjaan->Visible) { // pekerjaan ?>
	<div id="r_pekerjaan" class="form-group">
		<label id="elh_tanggota_pekerjaan" for="x_pekerjaan" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->pekerjaan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->pekerjaan->CellAttributes() ?>>
<span id="el_tanggota_pekerjaan">
<input type="text" data-table="tanggota" data-field="x_pekerjaan" name="x_pekerjaan" id="x_pekerjaan" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tanggota->pekerjaan->getPlaceHolder()) ?>" value="<?php echo $tanggota->pekerjaan->EditValue ?>"<?php echo $tanggota->pekerjaan->EditAttributes() ?>>
</span>
<?php echo $tanggota->pekerjaan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->telepon->Visible) { // telepon ?>
	<div id="r_telepon" class="form-group">
		<label id="elh_tanggota_telepon" for="x_telepon" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->telepon->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->telepon->CellAttributes() ?>>
<span id="el_tanggota_telepon">
<input type="text" data-table="tanggota" data-field="x_telepon" name="x_telepon" id="x_telepon" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($tanggota->telepon->getPlaceHolder()) ?>" value="<?php echo $tanggota->telepon->EditValue ?>"<?php echo $tanggota->telepon->EditAttributes() ?>>
</span>
<?php echo $tanggota->telepon->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->hp->Visible) { // hp ?>
	<div id="r_hp" class="form-group">
		<label id="elh_tanggota_hp" for="x_hp" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->hp->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->hp->CellAttributes() ?>>
<span id="el_tanggota_hp">
<input type="text" data-table="tanggota" data-field="x_hp" name="x_hp" id="x_hp" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($tanggota->hp->getPlaceHolder()) ?>" value="<?php echo $tanggota->hp->EditValue ?>"<?php echo $tanggota->hp->EditAttributes() ?>>
</span>
<?php echo $tanggota->hp->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->fax->Visible) { // fax ?>
	<div id="r_fax" class="form-group">
		<label id="elh_tanggota_fax" for="x_fax" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->fax->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->fax->CellAttributes() ?>>
<span id="el_tanggota_fax">
<input type="text" data-table="tanggota" data-field="x_fax" name="x_fax" id="x_fax" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($tanggota->fax->getPlaceHolder()) ?>" value="<?php echo $tanggota->fax->EditValue ?>"<?php echo $tanggota->fax->EditAttributes() ?>>
</span>
<?php echo $tanggota->fax->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_tanggota__email" for="x__email" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->_email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->_email->CellAttributes() ?>>
<span id="el_tanggota__email">
<input type="text" data-table="tanggota" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($tanggota->_email->getPlaceHolder()) ?>" value="<?php echo $tanggota->_email->EditValue ?>"<?php echo $tanggota->_email->EditAttributes() ?>>
</span>
<?php echo $tanggota->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->website->Visible) { // website ?>
	<div id="r_website" class="form-group">
		<label id="elh_tanggota_website" for="x_website" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->website->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->website->CellAttributes() ?>>
<span id="el_tanggota_website">
<input type="text" data-table="tanggota" data-field="x_website" name="x_website" id="x_website" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($tanggota->website->getPlaceHolder()) ?>" value="<?php echo $tanggota->website->EditValue ?>"<?php echo $tanggota->website->EditAttributes() ?>>
</span>
<?php echo $tanggota->website->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->jenisanggota->Visible) { // jenisanggota ?>
	<div id="r_jenisanggota" class="form-group">
		<label id="elh_tanggota_jenisanggota" for="x_jenisanggota" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->jenisanggota->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->jenisanggota->CellAttributes() ?>>
<span id="el_tanggota_jenisanggota">
<input type="text" data-table="tanggota" data-field="x_jenisanggota" name="x_jenisanggota" id="x_jenisanggota" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tanggota->jenisanggota->getPlaceHolder()) ?>" value="<?php echo $tanggota->jenisanggota->EditValue ?>"<?php echo $tanggota->jenisanggota->EditAttributes() ?>>
</span>
<?php echo $tanggota->jenisanggota->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->model->Visible) { // model ?>
	<div id="r_model" class="form-group">
		<label id="elh_tanggota_model" for="x_model" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->model->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->model->CellAttributes() ?>>
<span id="el_tanggota_model">
<input type="text" data-table="tanggota" data-field="x_model" name="x_model" id="x_model" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tanggota->model->getPlaceHolder()) ?>" value="<?php echo $tanggota->model->EditValue ?>"<?php echo $tanggota->model->EditAttributes() ?>>
</span>
<?php echo $tanggota->model->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->namakantor->Visible) { // namakantor ?>
	<div id="r_namakantor" class="form-group">
		<label id="elh_tanggota_namakantor" for="x_namakantor" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->namakantor->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->namakantor->CellAttributes() ?>>
<span id="el_tanggota_namakantor">
<input type="text" data-table="tanggota" data-field="x_namakantor" name="x_namakantor" id="x_namakantor" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($tanggota->namakantor->getPlaceHolder()) ?>" value="<?php echo $tanggota->namakantor->EditValue ?>"<?php echo $tanggota->namakantor->EditAttributes() ?>>
</span>
<?php echo $tanggota->namakantor->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->alamatkantor->Visible) { // alamatkantor ?>
	<div id="r_alamatkantor" class="form-group">
		<label id="elh_tanggota_alamatkantor" for="x_alamatkantor" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->alamatkantor->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->alamatkantor->CellAttributes() ?>>
<span id="el_tanggota_alamatkantor">
<input type="text" data-table="tanggota" data-field="x_alamatkantor" name="x_alamatkantor" id="x_alamatkantor" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($tanggota->alamatkantor->getPlaceHolder()) ?>" value="<?php echo $tanggota->alamatkantor->EditValue ?>"<?php echo $tanggota->alamatkantor->EditAttributes() ?>>
</span>
<?php echo $tanggota->alamatkantor->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->wilayah->Visible) { // wilayah ?>
	<div id="r_wilayah" class="form-group">
		<label id="elh_tanggota_wilayah" for="x_wilayah" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->wilayah->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->wilayah->CellAttributes() ?>>
<span id="el_tanggota_wilayah">
<input type="text" data-table="tanggota" data-field="x_wilayah" name="x_wilayah" id="x_wilayah" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tanggota->wilayah->getPlaceHolder()) ?>" value="<?php echo $tanggota->wilayah->EditValue ?>"<?php echo $tanggota->wilayah->EditAttributes() ?>>
</span>
<?php echo $tanggota->wilayah->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->petugas->Visible) { // petugas ?>
	<div id="r_petugas" class="form-group">
		<label id="elh_tanggota_petugas" for="x_petugas" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->petugas->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->petugas->CellAttributes() ?>>
<span id="el_tanggota_petugas">
<input type="text" data-table="tanggota" data-field="x_petugas" name="x_petugas" id="x_petugas" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tanggota->petugas->getPlaceHolder()) ?>" value="<?php echo $tanggota->petugas->EditValue ?>"<?php echo $tanggota->petugas->EditAttributes() ?>>
</span>
<?php echo $tanggota->petugas->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->pembayaran->Visible) { // pembayaran ?>
	<div id="r_pembayaran" class="form-group">
		<label id="elh_tanggota_pembayaran" for="x_pembayaran" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->pembayaran->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->pembayaran->CellAttributes() ?>>
<span id="el_tanggota_pembayaran">
<input type="text" data-table="tanggota" data-field="x_pembayaran" name="x_pembayaran" id="x_pembayaran" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tanggota->pembayaran->getPlaceHolder()) ?>" value="<?php echo $tanggota->pembayaran->EditValue ?>"<?php echo $tanggota->pembayaran->EditAttributes() ?>>
</span>
<?php echo $tanggota->pembayaran->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->bank->Visible) { // bank ?>
	<div id="r_bank" class="form-group">
		<label id="elh_tanggota_bank" for="x_bank" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->bank->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->bank->CellAttributes() ?>>
<span id="el_tanggota_bank">
<input type="text" data-table="tanggota" data-field="x_bank" name="x_bank" id="x_bank" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tanggota->bank->getPlaceHolder()) ?>" value="<?php echo $tanggota->bank->EditValue ?>"<?php echo $tanggota->bank->EditAttributes() ?>>
</span>
<?php echo $tanggota->bank->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->atasnama->Visible) { // atasnama ?>
	<div id="r_atasnama" class="form-group">
		<label id="elh_tanggota_atasnama" for="x_atasnama" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->atasnama->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->atasnama->CellAttributes() ?>>
<span id="el_tanggota_atasnama">
<input type="text" data-table="tanggota" data-field="x_atasnama" name="x_atasnama" id="x_atasnama" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($tanggota->atasnama->getPlaceHolder()) ?>" value="<?php echo $tanggota->atasnama->EditValue ?>"<?php echo $tanggota->atasnama->EditAttributes() ?>>
</span>
<?php echo $tanggota->atasnama->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->tipe->Visible) { // tipe ?>
	<div id="r_tipe" class="form-group">
		<label id="elh_tanggota_tipe" for="x_tipe" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->tipe->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->tipe->CellAttributes() ?>>
<span id="el_tanggota_tipe">
<input type="text" data-table="tanggota" data-field="x_tipe" name="x_tipe" id="x_tipe" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tanggota->tipe->getPlaceHolder()) ?>" value="<?php echo $tanggota->tipe->EditValue ?>"<?php echo $tanggota->tipe->EditAttributes() ?>>
</span>
<?php echo $tanggota->tipe->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->kantor->Visible) { // kantor ?>
	<div id="r_kantor" class="form-group">
		<label id="elh_tanggota_kantor" for="x_kantor" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->kantor->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->kantor->CellAttributes() ?>>
<span id="el_tanggota_kantor">
<input type="text" data-table="tanggota" data-field="x_kantor" name="x_kantor" id="x_kantor" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tanggota->kantor->getPlaceHolder()) ?>" value="<?php echo $tanggota->kantor->EditValue ?>"<?php echo $tanggota->kantor->EditAttributes() ?>>
</span>
<?php echo $tanggota->kantor->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->keterangan->Visible) { // keterangan ?>
	<div id="r_keterangan" class="form-group">
		<label id="elh_tanggota_keterangan" for="x_keterangan" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->keterangan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->keterangan->CellAttributes() ?>>
<span id="el_tanggota_keterangan">
<input type="text" data-table="tanggota" data-field="x_keterangan" name="x_keterangan" id="x_keterangan" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($tanggota->keterangan->getPlaceHolder()) ?>" value="<?php echo $tanggota->keterangan->EditValue ?>"<?php echo $tanggota->keterangan->EditAttributes() ?>>
</span>
<?php echo $tanggota->keterangan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->active->Visible) { // active ?>
	<div id="r_active" class="form-group">
		<label id="elh_tanggota_active" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->active->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->active->CellAttributes() ?>>
<span id="el_tanggota_active">
<div id="tp_x_active" class="ewTemplate"><input type="radio" data-table="tanggota" data-field="x_active" data-value-separator="<?php echo $tanggota->active->DisplayValueSeparatorAttribute() ?>" name="x_active" id="x_active" value="{value}"<?php echo $tanggota->active->EditAttributes() ?>></div>
<div id="dsl_x_active" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $tanggota->active->RadioButtonListHtml(FALSE, "x_active") ?>
</div></div>
</span>
<?php echo $tanggota->active->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->ip->Visible) { // ip ?>
	<div id="r_ip" class="form-group">
		<label id="elh_tanggota_ip" for="x_ip" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->ip->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->ip->CellAttributes() ?>>
<span id="el_tanggota_ip">
<input type="text" data-table="tanggota" data-field="x_ip" name="x_ip" id="x_ip" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tanggota->ip->getPlaceHolder()) ?>" value="<?php echo $tanggota->ip->EditValue ?>"<?php echo $tanggota->ip->EditAttributes() ?>>
</span>
<?php echo $tanggota->ip->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_tanggota_status" for="x_status" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->status->CellAttributes() ?>>
<span id="el_tanggota_status">
<input type="text" data-table="tanggota" data-field="x_status" name="x_status" id="x_status" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tanggota->status->getPlaceHolder()) ?>" value="<?php echo $tanggota->status->EditValue ?>"<?php echo $tanggota->status->EditAttributes() ?>>
</span>
<?php echo $tanggota->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->user->Visible) { // user ?>
	<div id="r_user" class="form-group">
		<label id="elh_tanggota_user" for="x_user" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->user->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->user->CellAttributes() ?>>
<span id="el_tanggota_user">
<input type="text" data-table="tanggota" data-field="x_user" name="x_user" id="x_user" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tanggota->user->getPlaceHolder()) ?>" value="<?php echo $tanggota->user->EditValue ?>"<?php echo $tanggota->user->EditAttributes() ?>>
</span>
<?php echo $tanggota->user->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->created->Visible) { // created ?>
	<div id="r_created" class="form-group">
		<label id="elh_tanggota_created" for="x_created" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->created->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->created->CellAttributes() ?>>
<span id="el_tanggota_created">
<input type="text" data-table="tanggota" data-field="x_created" name="x_created" id="x_created" placeholder="<?php echo ew_HtmlEncode($tanggota->created->getPlaceHolder()) ?>" value="<?php echo $tanggota->created->EditValue ?>"<?php echo $tanggota->created->EditAttributes() ?>>
</span>
<?php echo $tanggota->created->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tanggota->modified->Visible) { // modified ?>
	<div id="r_modified" class="form-group">
		<label id="elh_tanggota_modified" for="x_modified" class="col-sm-2 control-label ewLabel"><?php echo $tanggota->modified->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tanggota->modified->CellAttributes() ?>>
<span id="el_tanggota_modified">
<input type="text" data-table="tanggota" data-field="x_modified" name="x_modified" id="x_modified" placeholder="<?php echo ew_HtmlEncode($tanggota->modified->getPlaceHolder()) ?>" value="<?php echo $tanggota->modified->EditValue ?>"<?php echo $tanggota->modified->EditAttributes() ?>>
</span>
<?php echo $tanggota->modified->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$tanggota_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $tanggota_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ftanggotaedit.Init();
</script>
<?php
$tanggota_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tanggota_edit->Page_Terminate();
?>
