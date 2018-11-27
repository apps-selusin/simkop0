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

$tbayartitipan_edit = NULL; // Initialize page object first

class ctbayartitipan_edit extends ctbayartitipan {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tbayartitipan';

	// Page object name
	var $PageObjName = 'tbayartitipan_edit';

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

		// Table object (tbayartitipan)
		if (!isset($GLOBALS["tbayartitipan"]) || get_class($GLOBALS["tbayartitipan"]) == "ctbayartitipan") {
			$GLOBALS["tbayartitipan"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tbayartitipan"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tbayartitipan', TRUE);

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
		if (@$_GET["titipan"] <> "") {
			$this->titipan->setQueryStringValue($_GET["titipan"]);
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
			$this->Page_Terminate("tbayartitipanlist.php"); // Invalid key, return to list
		}
		if ($this->titipan->CurrentValue == "") {
			$this->Page_Terminate("tbayartitipanlist.php"); // Invalid key, return to list
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
					$this->Page_Terminate("tbayartitipanlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "tbayartitipanlist.php")
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
		if (!$this->transaksi->FldIsDetailKey) {
			$this->transaksi->setFormValue($objForm->GetValue("x_transaksi"));
		}
		if (!$this->referensi->FldIsDetailKey) {
			$this->referensi->setFormValue($objForm->GetValue("x_referensi"));
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
		if (!$this->jenispinjaman->FldIsDetailKey) {
			$this->jenispinjaman->setFormValue($objForm->GetValue("x_jenispinjaman"));
		}
		if (!$this->jenisbunga->FldIsDetailKey) {
			$this->jenisbunga->setFormValue($objForm->GetValue("x_jenisbunga"));
		}
		if (!$this->angsuran->FldIsDetailKey) {
			$this->angsuran->setFormValue($objForm->GetValue("x_angsuran"));
		}
		if (!$this->masaangsuran->FldIsDetailKey) {
			$this->masaangsuran->setFormValue($objForm->GetValue("x_masaangsuran"));
		}
		if (!$this->jatuhtempo->FldIsDetailKey) {
			$this->jatuhtempo->setFormValue($objForm->GetValue("x_jatuhtempo"));
			$this->jatuhtempo->CurrentValue = ew_UnFormatDateTime($this->jatuhtempo->CurrentValue, 0);
		}
		if (!$this->dispensasidenda->FldIsDetailKey) {
			$this->dispensasidenda->setFormValue($objForm->GetValue("x_dispensasidenda"));
		}
		if (!$this->titipan->FldIsDetailKey) {
			$this->titipan->setFormValue($objForm->GetValue("x_titipan"));
		}
		if (!$this->bayartitipan->FldIsDetailKey) {
			$this->bayartitipan->setFormValue($objForm->GetValue("x_bayartitipan"));
		}
		if (!$this->bayartitipanauto->FldIsDetailKey) {
			$this->bayartitipanauto->setFormValue($objForm->GetValue("x_bayartitipanauto"));
		}
		if (!$this->terbilang->FldIsDetailKey) {
			$this->terbilang->setFormValue($objForm->GetValue("x_terbilang"));
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
		$this->tanggal->CurrentValue = $this->tanggal->FormValue;
		$this->tanggal->CurrentValue = ew_UnFormatDateTime($this->tanggal->CurrentValue, 0);
		$this->periode->CurrentValue = $this->periode->FormValue;
		$this->id->CurrentValue = $this->id->FormValue;
		$this->transaksi->CurrentValue = $this->transaksi->FormValue;
		$this->referensi->CurrentValue = $this->referensi->FormValue;
		$this->anggota->CurrentValue = $this->anggota->FormValue;
		$this->namaanggota->CurrentValue = $this->namaanggota->FormValue;
		$this->alamat->CurrentValue = $this->alamat->FormValue;
		$this->pekerjaan->CurrentValue = $this->pekerjaan->FormValue;
		$this->telepon->CurrentValue = $this->telepon->FormValue;
		$this->hp->CurrentValue = $this->hp->FormValue;
		$this->fax->CurrentValue = $this->fax->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->website->CurrentValue = $this->website->FormValue;
		$this->jenisanggota->CurrentValue = $this->jenisanggota->FormValue;
		$this->model->CurrentValue = $this->model->FormValue;
		$this->jenispinjaman->CurrentValue = $this->jenispinjaman->FormValue;
		$this->jenisbunga->CurrentValue = $this->jenisbunga->FormValue;
		$this->angsuran->CurrentValue = $this->angsuran->FormValue;
		$this->masaangsuran->CurrentValue = $this->masaangsuran->FormValue;
		$this->jatuhtempo->CurrentValue = $this->jatuhtempo->FormValue;
		$this->jatuhtempo->CurrentValue = ew_UnFormatDateTime($this->jatuhtempo->CurrentValue, 0);
		$this->dispensasidenda->CurrentValue = $this->dispensasidenda->FormValue;
		$this->titipan->CurrentValue = $this->titipan->FormValue;
		$this->bayartitipan->CurrentValue = $this->bayartitipan->FormValue;
		$this->bayartitipanauto->CurrentValue = $this->bayartitipanauto->FormValue;
		$this->terbilang->CurrentValue = $this->terbilang->FormValue;
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

			// jenispinjaman
			$this->jenispinjaman->EditAttrs["class"] = "form-control";
			$this->jenispinjaman->EditCustomAttributes = "";
			$this->jenispinjaman->EditValue = ew_HtmlEncode($this->jenispinjaman->CurrentValue);
			$this->jenispinjaman->PlaceHolder = ew_RemoveHtml($this->jenispinjaman->FldCaption());

			// jenisbunga
			$this->jenisbunga->EditAttrs["class"] = "form-control";
			$this->jenisbunga->EditCustomAttributes = "";
			$this->jenisbunga->EditValue = ew_HtmlEncode($this->jenisbunga->CurrentValue);
			$this->jenisbunga->PlaceHolder = ew_RemoveHtml($this->jenisbunga->FldCaption());

			// angsuran
			$this->angsuran->EditAttrs["class"] = "form-control";
			$this->angsuran->EditCustomAttributes = "";
			$this->angsuran->EditValue = ew_HtmlEncode($this->angsuran->CurrentValue);
			$this->angsuran->PlaceHolder = ew_RemoveHtml($this->angsuran->FldCaption());

			// masaangsuran
			$this->masaangsuran->EditAttrs["class"] = "form-control";
			$this->masaangsuran->EditCustomAttributes = "";
			$this->masaangsuran->EditValue = ew_HtmlEncode($this->masaangsuran->CurrentValue);
			$this->masaangsuran->PlaceHolder = ew_RemoveHtml($this->masaangsuran->FldCaption());

			// jatuhtempo
			$this->jatuhtempo->EditAttrs["class"] = "form-control";
			$this->jatuhtempo->EditCustomAttributes = "";
			$this->jatuhtempo->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->jatuhtempo->CurrentValue, 8));
			$this->jatuhtempo->PlaceHolder = ew_RemoveHtml($this->jatuhtempo->FldCaption());

			// dispensasidenda
			$this->dispensasidenda->EditAttrs["class"] = "form-control";
			$this->dispensasidenda->EditCustomAttributes = "";
			$this->dispensasidenda->EditValue = ew_HtmlEncode($this->dispensasidenda->CurrentValue);
			$this->dispensasidenda->PlaceHolder = ew_RemoveHtml($this->dispensasidenda->FldCaption());

			// titipan
			$this->titipan->EditAttrs["class"] = "form-control";
			$this->titipan->EditCustomAttributes = "";
			$this->titipan->EditValue = $this->titipan->CurrentValue;
			$this->titipan->ViewCustomAttributes = "";

			// bayartitipan
			$this->bayartitipan->EditAttrs["class"] = "form-control";
			$this->bayartitipan->EditCustomAttributes = "";
			$this->bayartitipan->EditValue = ew_HtmlEncode($this->bayartitipan->CurrentValue);
			$this->bayartitipan->PlaceHolder = ew_RemoveHtml($this->bayartitipan->FldCaption());
			if (strval($this->bayartitipan->EditValue) <> "" && is_numeric($this->bayartitipan->EditValue)) $this->bayartitipan->EditValue = ew_FormatNumber($this->bayartitipan->EditValue, -2, -1, -2, 0);

			// bayartitipanauto
			$this->bayartitipanauto->EditAttrs["class"] = "form-control";
			$this->bayartitipanauto->EditCustomAttributes = "";
			$this->bayartitipanauto->EditValue = ew_HtmlEncode($this->bayartitipanauto->CurrentValue);
			$this->bayartitipanauto->PlaceHolder = ew_RemoveHtml($this->bayartitipanauto->FldCaption());
			if (strval($this->bayartitipanauto->EditValue) <> "" && is_numeric($this->bayartitipanauto->EditValue)) $this->bayartitipanauto->EditValue = ew_FormatNumber($this->bayartitipanauto->EditValue, -2, -1, -2, 0);

			// terbilang
			$this->terbilang->EditAttrs["class"] = "form-control";
			$this->terbilang->EditCustomAttributes = "";
			$this->terbilang->EditValue = ew_HtmlEncode($this->terbilang->CurrentValue);
			$this->terbilang->PlaceHolder = ew_RemoveHtml($this->terbilang->FldCaption());

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
			// tanggal

			$this->tanggal->LinkCustomAttributes = "";
			$this->tanggal->HrefValue = "";

			// periode
			$this->periode->LinkCustomAttributes = "";
			$this->periode->HrefValue = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";

			// transaksi
			$this->transaksi->LinkCustomAttributes = "";
			$this->transaksi->HrefValue = "";

			// referensi
			$this->referensi->LinkCustomAttributes = "";
			$this->referensi->HrefValue = "";

			// anggota
			$this->anggota->LinkCustomAttributes = "";
			$this->anggota->HrefValue = "";

			// namaanggota
			$this->namaanggota->LinkCustomAttributes = "";
			$this->namaanggota->HrefValue = "";

			// alamat
			$this->alamat->LinkCustomAttributes = "";
			$this->alamat->HrefValue = "";

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

			// jenispinjaman
			$this->jenispinjaman->LinkCustomAttributes = "";
			$this->jenispinjaman->HrefValue = "";

			// jenisbunga
			$this->jenisbunga->LinkCustomAttributes = "";
			$this->jenisbunga->HrefValue = "";

			// angsuran
			$this->angsuran->LinkCustomAttributes = "";
			$this->angsuran->HrefValue = "";

			// masaangsuran
			$this->masaangsuran->LinkCustomAttributes = "";
			$this->masaangsuran->HrefValue = "";

			// jatuhtempo
			$this->jatuhtempo->LinkCustomAttributes = "";
			$this->jatuhtempo->HrefValue = "";

			// dispensasidenda
			$this->dispensasidenda->LinkCustomAttributes = "";
			$this->dispensasidenda->HrefValue = "";

			// titipan
			$this->titipan->LinkCustomAttributes = "";
			$this->titipan->HrefValue = "";

			// bayartitipan
			$this->bayartitipan->LinkCustomAttributes = "";
			$this->bayartitipan->HrefValue = "";

			// bayartitipanauto
			$this->bayartitipanauto->LinkCustomAttributes = "";
			$this->bayartitipanauto->HrefValue = "";

			// terbilang
			$this->terbilang->LinkCustomAttributes = "";
			$this->terbilang->HrefValue = "";

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
		if (!$this->transaksi->FldIsDetailKey && !is_null($this->transaksi->FormValue) && $this->transaksi->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->transaksi->FldCaption(), $this->transaksi->ReqErrMsg));
		}
		if (!$this->referensi->FldIsDetailKey && !is_null($this->referensi->FormValue) && $this->referensi->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->referensi->FldCaption(), $this->referensi->ReqErrMsg));
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
		if (!$this->jenispinjaman->FldIsDetailKey && !is_null($this->jenispinjaman->FormValue) && $this->jenispinjaman->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jenispinjaman->FldCaption(), $this->jenispinjaman->ReqErrMsg));
		}
		if (!$this->jenisbunga->FldIsDetailKey && !is_null($this->jenisbunga->FormValue) && $this->jenisbunga->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jenisbunga->FldCaption(), $this->jenisbunga->ReqErrMsg));
		}
		if (!$this->angsuran->FldIsDetailKey && !is_null($this->angsuran->FormValue) && $this->angsuran->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->angsuran->FldCaption(), $this->angsuran->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->angsuran->FormValue)) {
			ew_AddMessage($gsFormError, $this->angsuran->FldErrMsg());
		}
		if (!$this->masaangsuran->FldIsDetailKey && !is_null($this->masaangsuran->FormValue) && $this->masaangsuran->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->masaangsuran->FldCaption(), $this->masaangsuran->ReqErrMsg));
		}
		if (!$this->jatuhtempo->FldIsDetailKey && !is_null($this->jatuhtempo->FormValue) && $this->jatuhtempo->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jatuhtempo->FldCaption(), $this->jatuhtempo->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->jatuhtempo->FormValue)) {
			ew_AddMessage($gsFormError, $this->jatuhtempo->FldErrMsg());
		}
		if (!$this->dispensasidenda->FldIsDetailKey && !is_null($this->dispensasidenda->FormValue) && $this->dispensasidenda->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->dispensasidenda->FldCaption(), $this->dispensasidenda->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->dispensasidenda->FormValue)) {
			ew_AddMessage($gsFormError, $this->dispensasidenda->FldErrMsg());
		}
		if (!$this->titipan->FldIsDetailKey && !is_null($this->titipan->FormValue) && $this->titipan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->titipan->FldCaption(), $this->titipan->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->titipan->FormValue)) {
			ew_AddMessage($gsFormError, $this->titipan->FldErrMsg());
		}
		if (!$this->bayartitipan->FldIsDetailKey && !is_null($this->bayartitipan->FormValue) && $this->bayartitipan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->bayartitipan->FldCaption(), $this->bayartitipan->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->bayartitipan->FormValue)) {
			ew_AddMessage($gsFormError, $this->bayartitipan->FldErrMsg());
		}
		if (!$this->bayartitipanauto->FldIsDetailKey && !is_null($this->bayartitipanauto->FormValue) && $this->bayartitipanauto->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->bayartitipanauto->FldCaption(), $this->bayartitipanauto->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->bayartitipanauto->FormValue)) {
			ew_AddMessage($gsFormError, $this->bayartitipanauto->FldErrMsg());
		}
		if (!$this->terbilang->FldIsDetailKey && !is_null($this->terbilang->FormValue) && $this->terbilang->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->terbilang->FldCaption(), $this->terbilang->ReqErrMsg));
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

			// tanggal
			$this->tanggal->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tanggal->CurrentValue, 0), ew_CurrentDate(), $this->tanggal->ReadOnly);

			// periode
			$this->periode->SetDbValueDef($rsnew, $this->periode->CurrentValue, "", $this->periode->ReadOnly);

			// id
			// transaksi

			$this->transaksi->SetDbValueDef($rsnew, $this->transaksi->CurrentValue, "", $this->transaksi->ReadOnly);

			// referensi
			$this->referensi->SetDbValueDef($rsnew, $this->referensi->CurrentValue, "", $this->referensi->ReadOnly);

			// anggota
			$this->anggota->SetDbValueDef($rsnew, $this->anggota->CurrentValue, "", $this->anggota->ReadOnly);

			// namaanggota
			$this->namaanggota->SetDbValueDef($rsnew, $this->namaanggota->CurrentValue, "", $this->namaanggota->ReadOnly);

			// alamat
			$this->alamat->SetDbValueDef($rsnew, $this->alamat->CurrentValue, "", $this->alamat->ReadOnly);

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

			// jenispinjaman
			$this->jenispinjaman->SetDbValueDef($rsnew, $this->jenispinjaman->CurrentValue, "", $this->jenispinjaman->ReadOnly);

			// jenisbunga
			$this->jenisbunga->SetDbValueDef($rsnew, $this->jenisbunga->CurrentValue, "", $this->jenisbunga->ReadOnly);

			// angsuran
			$this->angsuran->SetDbValueDef($rsnew, $this->angsuran->CurrentValue, 0, $this->angsuran->ReadOnly);

			// masaangsuran
			$this->masaangsuran->SetDbValueDef($rsnew, $this->masaangsuran->CurrentValue, "", $this->masaangsuran->ReadOnly);

			// jatuhtempo
			$this->jatuhtempo->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->jatuhtempo->CurrentValue, 0), ew_CurrentDate(), $this->jatuhtempo->ReadOnly);

			// dispensasidenda
			$this->dispensasidenda->SetDbValueDef($rsnew, $this->dispensasidenda->CurrentValue, 0, $this->dispensasidenda->ReadOnly);

			// titipan
			// bayartitipan

			$this->bayartitipan->SetDbValueDef($rsnew, $this->bayartitipan->CurrentValue, 0, $this->bayartitipan->ReadOnly);

			// bayartitipanauto
			$this->bayartitipanauto->SetDbValueDef($rsnew, $this->bayartitipanauto->CurrentValue, 0, $this->bayartitipanauto->ReadOnly);

			// terbilang
			$this->terbilang->SetDbValueDef($rsnew, $this->terbilang->CurrentValue, "", $this->terbilang->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("tbayartitipanlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($tbayartitipan_edit)) $tbayartitipan_edit = new ctbayartitipan_edit();

// Page init
$tbayartitipan_edit->Page_Init();

// Page main
$tbayartitipan_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tbayartitipan_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = ftbayartitipanedit = new ew_Form("ftbayartitipanedit", "edit");

// Validate form
ftbayartitipanedit.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->tanggal->FldCaption(), $tbayartitipan->tanggal->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tanggal");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayartitipan->tanggal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_periode");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->periode->FldCaption(), $tbayartitipan->periode->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->id->FldCaption(), $tbayartitipan->id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_transaksi");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->transaksi->FldCaption(), $tbayartitipan->transaksi->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_referensi");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->referensi->FldCaption(), $tbayartitipan->referensi->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_anggota");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->anggota->FldCaption(), $tbayartitipan->anggota->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_namaanggota");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->namaanggota->FldCaption(), $tbayartitipan->namaanggota->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_alamat");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->alamat->FldCaption(), $tbayartitipan->alamat->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pekerjaan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->pekerjaan->FldCaption(), $tbayartitipan->pekerjaan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_telepon");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->telepon->FldCaption(), $tbayartitipan->telepon->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_hp");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->hp->FldCaption(), $tbayartitipan->hp->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fax");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->fax->FldCaption(), $tbayartitipan->fax->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__email");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->_email->FldCaption(), $tbayartitipan->_email->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_website");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->website->FldCaption(), $tbayartitipan->website->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jenisanggota");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->jenisanggota->FldCaption(), $tbayartitipan->jenisanggota->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_model");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->model->FldCaption(), $tbayartitipan->model->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jenispinjaman");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->jenispinjaman->FldCaption(), $tbayartitipan->jenispinjaman->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jenisbunga");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->jenisbunga->FldCaption(), $tbayartitipan->jenisbunga->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_angsuran");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->angsuran->FldCaption(), $tbayartitipan->angsuran->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_angsuran");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayartitipan->angsuran->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_masaangsuran");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->masaangsuran->FldCaption(), $tbayartitipan->masaangsuran->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jatuhtempo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->jatuhtempo->FldCaption(), $tbayartitipan->jatuhtempo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jatuhtempo");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayartitipan->jatuhtempo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dispensasidenda");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->dispensasidenda->FldCaption(), $tbayartitipan->dispensasidenda->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dispensasidenda");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayartitipan->dispensasidenda->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_titipan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->titipan->FldCaption(), $tbayartitipan->titipan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_titipan");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayartitipan->titipan->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_bayartitipan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->bayartitipan->FldCaption(), $tbayartitipan->bayartitipan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bayartitipan");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayartitipan->bayartitipan->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_bayartitipanauto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->bayartitipanauto->FldCaption(), $tbayartitipan->bayartitipanauto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bayartitipanauto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayartitipan->bayartitipanauto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_terbilang");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->terbilang->FldCaption(), $tbayartitipan->terbilang->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_petugas");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->petugas->FldCaption(), $tbayartitipan->petugas->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pembayaran");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->pembayaran->FldCaption(), $tbayartitipan->pembayaran->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bank");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->bank->FldCaption(), $tbayartitipan->bank->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_atasnama");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->atasnama->FldCaption(), $tbayartitipan->atasnama->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tipe");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->tipe->FldCaption(), $tbayartitipan->tipe->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_kantor");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->kantor->FldCaption(), $tbayartitipan->kantor->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_keterangan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->keterangan->FldCaption(), $tbayartitipan->keterangan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_active");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->active->FldCaption(), $tbayartitipan->active->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ip");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->ip->FldCaption(), $tbayartitipan->ip->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->status->FldCaption(), $tbayartitipan->status->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_user");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->user->FldCaption(), $tbayartitipan->user->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_created");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->created->FldCaption(), $tbayartitipan->created->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_created");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayartitipan->created->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modified");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayartitipan->modified->FldCaption(), $tbayartitipan->modified->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modified");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayartitipan->modified->FldErrMsg()) ?>");

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
ftbayartitipanedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbayartitipanedit.ValidateRequired = true;
<?php } else { ?>
ftbayartitipanedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftbayartitipanedit.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftbayartitipanedit.Lists["x_active"].Options = <?php echo json_encode($tbayartitipan->active->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$tbayartitipan_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $tbayartitipan_edit->ShowPageHeader(); ?>
<?php
$tbayartitipan_edit->ShowMessage();
?>
<form name="ftbayartitipanedit" id="ftbayartitipanedit" class="<?php echo $tbayartitipan_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tbayartitipan_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tbayartitipan_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tbayartitipan">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($tbayartitipan_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($tbayartitipan->tanggal->Visible) { // tanggal ?>
	<div id="r_tanggal" class="form-group">
		<label id="elh_tbayartitipan_tanggal" for="x_tanggal" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->tanggal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->tanggal->CellAttributes() ?>>
<span id="el_tbayartitipan_tanggal">
<input type="text" data-table="tbayartitipan" data-field="x_tanggal" name="x_tanggal" id="x_tanggal" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->tanggal->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->tanggal->EditValue ?>"<?php echo $tbayartitipan->tanggal->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->tanggal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->periode->Visible) { // periode ?>
	<div id="r_periode" class="form-group">
		<label id="elh_tbayartitipan_periode" for="x_periode" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->periode->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->periode->CellAttributes() ?>>
<span id="el_tbayartitipan_periode">
<input type="text" data-table="tbayartitipan" data-field="x_periode" name="x_periode" id="x_periode" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->periode->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->periode->EditValue ?>"<?php echo $tbayartitipan->periode->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->periode->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_tbayartitipan_id" for="x_id" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->id->CellAttributes() ?>>
<span id="el_tbayartitipan_id">
<span<?php echo $tbayartitipan->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $tbayartitipan->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="tbayartitipan" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($tbayartitipan->id->CurrentValue) ?>">
<?php echo $tbayartitipan->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->transaksi->Visible) { // transaksi ?>
	<div id="r_transaksi" class="form-group">
		<label id="elh_tbayartitipan_transaksi" for="x_transaksi" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->transaksi->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->transaksi->CellAttributes() ?>>
<span id="el_tbayartitipan_transaksi">
<input type="text" data-table="tbayartitipan" data-field="x_transaksi" name="x_transaksi" id="x_transaksi" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->transaksi->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->transaksi->EditValue ?>"<?php echo $tbayartitipan->transaksi->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->transaksi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->referensi->Visible) { // referensi ?>
	<div id="r_referensi" class="form-group">
		<label id="elh_tbayartitipan_referensi" for="x_referensi" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->referensi->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->referensi->CellAttributes() ?>>
<span id="el_tbayartitipan_referensi">
<input type="text" data-table="tbayartitipan" data-field="x_referensi" name="x_referensi" id="x_referensi" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->referensi->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->referensi->EditValue ?>"<?php echo $tbayartitipan->referensi->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->referensi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->anggota->Visible) { // anggota ?>
	<div id="r_anggota" class="form-group">
		<label id="elh_tbayartitipan_anggota" for="x_anggota" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->anggota->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->anggota->CellAttributes() ?>>
<span id="el_tbayartitipan_anggota">
<input type="text" data-table="tbayartitipan" data-field="x_anggota" name="x_anggota" id="x_anggota" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->anggota->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->anggota->EditValue ?>"<?php echo $tbayartitipan->anggota->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->anggota->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->namaanggota->Visible) { // namaanggota ?>
	<div id="r_namaanggota" class="form-group">
		<label id="elh_tbayartitipan_namaanggota" for="x_namaanggota" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->namaanggota->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->namaanggota->CellAttributes() ?>>
<span id="el_tbayartitipan_namaanggota">
<input type="text" data-table="tbayartitipan" data-field="x_namaanggota" name="x_namaanggota" id="x_namaanggota" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->namaanggota->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->namaanggota->EditValue ?>"<?php echo $tbayartitipan->namaanggota->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->namaanggota->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->alamat->Visible) { // alamat ?>
	<div id="r_alamat" class="form-group">
		<label id="elh_tbayartitipan_alamat" for="x_alamat" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->alamat->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->alamat->CellAttributes() ?>>
<span id="el_tbayartitipan_alamat">
<input type="text" data-table="tbayartitipan" data-field="x_alamat" name="x_alamat" id="x_alamat" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->alamat->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->alamat->EditValue ?>"<?php echo $tbayartitipan->alamat->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->alamat->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->pekerjaan->Visible) { // pekerjaan ?>
	<div id="r_pekerjaan" class="form-group">
		<label id="elh_tbayartitipan_pekerjaan" for="x_pekerjaan" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->pekerjaan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->pekerjaan->CellAttributes() ?>>
<span id="el_tbayartitipan_pekerjaan">
<input type="text" data-table="tbayartitipan" data-field="x_pekerjaan" name="x_pekerjaan" id="x_pekerjaan" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->pekerjaan->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->pekerjaan->EditValue ?>"<?php echo $tbayartitipan->pekerjaan->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->pekerjaan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->telepon->Visible) { // telepon ?>
	<div id="r_telepon" class="form-group">
		<label id="elh_tbayartitipan_telepon" for="x_telepon" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->telepon->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->telepon->CellAttributes() ?>>
<span id="el_tbayartitipan_telepon">
<input type="text" data-table="tbayartitipan" data-field="x_telepon" name="x_telepon" id="x_telepon" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->telepon->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->telepon->EditValue ?>"<?php echo $tbayartitipan->telepon->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->telepon->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->hp->Visible) { // hp ?>
	<div id="r_hp" class="form-group">
		<label id="elh_tbayartitipan_hp" for="x_hp" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->hp->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->hp->CellAttributes() ?>>
<span id="el_tbayartitipan_hp">
<input type="text" data-table="tbayartitipan" data-field="x_hp" name="x_hp" id="x_hp" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->hp->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->hp->EditValue ?>"<?php echo $tbayartitipan->hp->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->hp->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->fax->Visible) { // fax ?>
	<div id="r_fax" class="form-group">
		<label id="elh_tbayartitipan_fax" for="x_fax" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->fax->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->fax->CellAttributes() ?>>
<span id="el_tbayartitipan_fax">
<input type="text" data-table="tbayartitipan" data-field="x_fax" name="x_fax" id="x_fax" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->fax->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->fax->EditValue ?>"<?php echo $tbayartitipan->fax->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->fax->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_tbayartitipan__email" for="x__email" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->_email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->_email->CellAttributes() ?>>
<span id="el_tbayartitipan__email">
<input type="text" data-table="tbayartitipan" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->_email->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->_email->EditValue ?>"<?php echo $tbayartitipan->_email->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->website->Visible) { // website ?>
	<div id="r_website" class="form-group">
		<label id="elh_tbayartitipan_website" for="x_website" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->website->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->website->CellAttributes() ?>>
<span id="el_tbayartitipan_website">
<input type="text" data-table="tbayartitipan" data-field="x_website" name="x_website" id="x_website" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->website->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->website->EditValue ?>"<?php echo $tbayartitipan->website->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->website->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->jenisanggota->Visible) { // jenisanggota ?>
	<div id="r_jenisanggota" class="form-group">
		<label id="elh_tbayartitipan_jenisanggota" for="x_jenisanggota" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->jenisanggota->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->jenisanggota->CellAttributes() ?>>
<span id="el_tbayartitipan_jenisanggota">
<input type="text" data-table="tbayartitipan" data-field="x_jenisanggota" name="x_jenisanggota" id="x_jenisanggota" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->jenisanggota->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->jenisanggota->EditValue ?>"<?php echo $tbayartitipan->jenisanggota->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->jenisanggota->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->model->Visible) { // model ?>
	<div id="r_model" class="form-group">
		<label id="elh_tbayartitipan_model" for="x_model" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->model->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->model->CellAttributes() ?>>
<span id="el_tbayartitipan_model">
<input type="text" data-table="tbayartitipan" data-field="x_model" name="x_model" id="x_model" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->model->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->model->EditValue ?>"<?php echo $tbayartitipan->model->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->model->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->jenispinjaman->Visible) { // jenispinjaman ?>
	<div id="r_jenispinjaman" class="form-group">
		<label id="elh_tbayartitipan_jenispinjaman" for="x_jenispinjaman" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->jenispinjaman->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->jenispinjaman->CellAttributes() ?>>
<span id="el_tbayartitipan_jenispinjaman">
<input type="text" data-table="tbayartitipan" data-field="x_jenispinjaman" name="x_jenispinjaman" id="x_jenispinjaman" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->jenispinjaman->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->jenispinjaman->EditValue ?>"<?php echo $tbayartitipan->jenispinjaman->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->jenispinjaman->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->jenisbunga->Visible) { // jenisbunga ?>
	<div id="r_jenisbunga" class="form-group">
		<label id="elh_tbayartitipan_jenisbunga" for="x_jenisbunga" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->jenisbunga->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->jenisbunga->CellAttributes() ?>>
<span id="el_tbayartitipan_jenisbunga">
<input type="text" data-table="tbayartitipan" data-field="x_jenisbunga" name="x_jenisbunga" id="x_jenisbunga" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->jenisbunga->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->jenisbunga->EditValue ?>"<?php echo $tbayartitipan->jenisbunga->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->jenisbunga->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->angsuran->Visible) { // angsuran ?>
	<div id="r_angsuran" class="form-group">
		<label id="elh_tbayartitipan_angsuran" for="x_angsuran" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->angsuran->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->angsuran->CellAttributes() ?>>
<span id="el_tbayartitipan_angsuran">
<input type="text" data-table="tbayartitipan" data-field="x_angsuran" name="x_angsuran" id="x_angsuran" size="30" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->angsuran->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->angsuran->EditValue ?>"<?php echo $tbayartitipan->angsuran->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->angsuran->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->masaangsuran->Visible) { // masaangsuran ?>
	<div id="r_masaangsuran" class="form-group">
		<label id="elh_tbayartitipan_masaangsuran" for="x_masaangsuran" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->masaangsuran->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->masaangsuran->CellAttributes() ?>>
<span id="el_tbayartitipan_masaangsuran">
<input type="text" data-table="tbayartitipan" data-field="x_masaangsuran" name="x_masaangsuran" id="x_masaangsuran" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->masaangsuran->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->masaangsuran->EditValue ?>"<?php echo $tbayartitipan->masaangsuran->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->masaangsuran->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->jatuhtempo->Visible) { // jatuhtempo ?>
	<div id="r_jatuhtempo" class="form-group">
		<label id="elh_tbayartitipan_jatuhtempo" for="x_jatuhtempo" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->jatuhtempo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->jatuhtempo->CellAttributes() ?>>
<span id="el_tbayartitipan_jatuhtempo">
<input type="text" data-table="tbayartitipan" data-field="x_jatuhtempo" name="x_jatuhtempo" id="x_jatuhtempo" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->jatuhtempo->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->jatuhtempo->EditValue ?>"<?php echo $tbayartitipan->jatuhtempo->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->jatuhtempo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->dispensasidenda->Visible) { // dispensasidenda ?>
	<div id="r_dispensasidenda" class="form-group">
		<label id="elh_tbayartitipan_dispensasidenda" for="x_dispensasidenda" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->dispensasidenda->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->dispensasidenda->CellAttributes() ?>>
<span id="el_tbayartitipan_dispensasidenda">
<input type="text" data-table="tbayartitipan" data-field="x_dispensasidenda" name="x_dispensasidenda" id="x_dispensasidenda" size="30" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->dispensasidenda->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->dispensasidenda->EditValue ?>"<?php echo $tbayartitipan->dispensasidenda->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->dispensasidenda->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->titipan->Visible) { // titipan ?>
	<div id="r_titipan" class="form-group">
		<label id="elh_tbayartitipan_titipan" for="x_titipan" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->titipan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->titipan->CellAttributes() ?>>
<span id="el_tbayartitipan_titipan">
<span<?php echo $tbayartitipan->titipan->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $tbayartitipan->titipan->EditValue ?></p></span>
</span>
<input type="hidden" data-table="tbayartitipan" data-field="x_titipan" name="x_titipan" id="x_titipan" value="<?php echo ew_HtmlEncode($tbayartitipan->titipan->CurrentValue) ?>">
<?php echo $tbayartitipan->titipan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->bayartitipan->Visible) { // bayartitipan ?>
	<div id="r_bayartitipan" class="form-group">
		<label id="elh_tbayartitipan_bayartitipan" for="x_bayartitipan" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->bayartitipan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->bayartitipan->CellAttributes() ?>>
<span id="el_tbayartitipan_bayartitipan">
<input type="text" data-table="tbayartitipan" data-field="x_bayartitipan" name="x_bayartitipan" id="x_bayartitipan" size="30" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->bayartitipan->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->bayartitipan->EditValue ?>"<?php echo $tbayartitipan->bayartitipan->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->bayartitipan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->bayartitipanauto->Visible) { // bayartitipanauto ?>
	<div id="r_bayartitipanauto" class="form-group">
		<label id="elh_tbayartitipan_bayartitipanauto" for="x_bayartitipanauto" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->bayartitipanauto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->bayartitipanauto->CellAttributes() ?>>
<span id="el_tbayartitipan_bayartitipanauto">
<input type="text" data-table="tbayartitipan" data-field="x_bayartitipanauto" name="x_bayartitipanauto" id="x_bayartitipanauto" size="30" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->bayartitipanauto->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->bayartitipanauto->EditValue ?>"<?php echo $tbayartitipan->bayartitipanauto->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->bayartitipanauto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->terbilang->Visible) { // terbilang ?>
	<div id="r_terbilang" class="form-group">
		<label id="elh_tbayartitipan_terbilang" for="x_terbilang" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->terbilang->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->terbilang->CellAttributes() ?>>
<span id="el_tbayartitipan_terbilang">
<input type="text" data-table="tbayartitipan" data-field="x_terbilang" name="x_terbilang" id="x_terbilang" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->terbilang->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->terbilang->EditValue ?>"<?php echo $tbayartitipan->terbilang->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->terbilang->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->petugas->Visible) { // petugas ?>
	<div id="r_petugas" class="form-group">
		<label id="elh_tbayartitipan_petugas" for="x_petugas" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->petugas->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->petugas->CellAttributes() ?>>
<span id="el_tbayartitipan_petugas">
<input type="text" data-table="tbayartitipan" data-field="x_petugas" name="x_petugas" id="x_petugas" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->petugas->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->petugas->EditValue ?>"<?php echo $tbayartitipan->petugas->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->petugas->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->pembayaran->Visible) { // pembayaran ?>
	<div id="r_pembayaran" class="form-group">
		<label id="elh_tbayartitipan_pembayaran" for="x_pembayaran" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->pembayaran->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->pembayaran->CellAttributes() ?>>
<span id="el_tbayartitipan_pembayaran">
<input type="text" data-table="tbayartitipan" data-field="x_pembayaran" name="x_pembayaran" id="x_pembayaran" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->pembayaran->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->pembayaran->EditValue ?>"<?php echo $tbayartitipan->pembayaran->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->pembayaran->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->bank->Visible) { // bank ?>
	<div id="r_bank" class="form-group">
		<label id="elh_tbayartitipan_bank" for="x_bank" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->bank->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->bank->CellAttributes() ?>>
<span id="el_tbayartitipan_bank">
<input type="text" data-table="tbayartitipan" data-field="x_bank" name="x_bank" id="x_bank" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->bank->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->bank->EditValue ?>"<?php echo $tbayartitipan->bank->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->bank->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->atasnama->Visible) { // atasnama ?>
	<div id="r_atasnama" class="form-group">
		<label id="elh_tbayartitipan_atasnama" for="x_atasnama" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->atasnama->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->atasnama->CellAttributes() ?>>
<span id="el_tbayartitipan_atasnama">
<input type="text" data-table="tbayartitipan" data-field="x_atasnama" name="x_atasnama" id="x_atasnama" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->atasnama->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->atasnama->EditValue ?>"<?php echo $tbayartitipan->atasnama->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->atasnama->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->tipe->Visible) { // tipe ?>
	<div id="r_tipe" class="form-group">
		<label id="elh_tbayartitipan_tipe" for="x_tipe" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->tipe->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->tipe->CellAttributes() ?>>
<span id="el_tbayartitipan_tipe">
<input type="text" data-table="tbayartitipan" data-field="x_tipe" name="x_tipe" id="x_tipe" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->tipe->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->tipe->EditValue ?>"<?php echo $tbayartitipan->tipe->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->tipe->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->kantor->Visible) { // kantor ?>
	<div id="r_kantor" class="form-group">
		<label id="elh_tbayartitipan_kantor" for="x_kantor" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->kantor->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->kantor->CellAttributes() ?>>
<span id="el_tbayartitipan_kantor">
<input type="text" data-table="tbayartitipan" data-field="x_kantor" name="x_kantor" id="x_kantor" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->kantor->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->kantor->EditValue ?>"<?php echo $tbayartitipan->kantor->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->kantor->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->keterangan->Visible) { // keterangan ?>
	<div id="r_keterangan" class="form-group">
		<label id="elh_tbayartitipan_keterangan" for="x_keterangan" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->keterangan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->keterangan->CellAttributes() ?>>
<span id="el_tbayartitipan_keterangan">
<input type="text" data-table="tbayartitipan" data-field="x_keterangan" name="x_keterangan" id="x_keterangan" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->keterangan->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->keterangan->EditValue ?>"<?php echo $tbayartitipan->keterangan->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->keterangan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->active->Visible) { // active ?>
	<div id="r_active" class="form-group">
		<label id="elh_tbayartitipan_active" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->active->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->active->CellAttributes() ?>>
<span id="el_tbayartitipan_active">
<div id="tp_x_active" class="ewTemplate"><input type="radio" data-table="tbayartitipan" data-field="x_active" data-value-separator="<?php echo $tbayartitipan->active->DisplayValueSeparatorAttribute() ?>" name="x_active" id="x_active" value="{value}"<?php echo $tbayartitipan->active->EditAttributes() ?>></div>
<div id="dsl_x_active" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $tbayartitipan->active->RadioButtonListHtml(FALSE, "x_active") ?>
</div></div>
</span>
<?php echo $tbayartitipan->active->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->ip->Visible) { // ip ?>
	<div id="r_ip" class="form-group">
		<label id="elh_tbayartitipan_ip" for="x_ip" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->ip->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->ip->CellAttributes() ?>>
<span id="el_tbayartitipan_ip">
<input type="text" data-table="tbayartitipan" data-field="x_ip" name="x_ip" id="x_ip" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->ip->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->ip->EditValue ?>"<?php echo $tbayartitipan->ip->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->ip->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_tbayartitipan_status" for="x_status" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->status->CellAttributes() ?>>
<span id="el_tbayartitipan_status">
<input type="text" data-table="tbayartitipan" data-field="x_status" name="x_status" id="x_status" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->status->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->status->EditValue ?>"<?php echo $tbayartitipan->status->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->user->Visible) { // user ?>
	<div id="r_user" class="form-group">
		<label id="elh_tbayartitipan_user" for="x_user" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->user->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->user->CellAttributes() ?>>
<span id="el_tbayartitipan_user">
<input type="text" data-table="tbayartitipan" data-field="x_user" name="x_user" id="x_user" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->user->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->user->EditValue ?>"<?php echo $tbayartitipan->user->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->user->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->created->Visible) { // created ?>
	<div id="r_created" class="form-group">
		<label id="elh_tbayartitipan_created" for="x_created" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->created->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->created->CellAttributes() ?>>
<span id="el_tbayartitipan_created">
<input type="text" data-table="tbayartitipan" data-field="x_created" name="x_created" id="x_created" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->created->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->created->EditValue ?>"<?php echo $tbayartitipan->created->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->created->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayartitipan->modified->Visible) { // modified ?>
	<div id="r_modified" class="form-group">
		<label id="elh_tbayartitipan_modified" for="x_modified" class="col-sm-2 control-label ewLabel"><?php echo $tbayartitipan->modified->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayartitipan->modified->CellAttributes() ?>>
<span id="el_tbayartitipan_modified">
<input type="text" data-table="tbayartitipan" data-field="x_modified" name="x_modified" id="x_modified" placeholder="<?php echo ew_HtmlEncode($tbayartitipan->modified->getPlaceHolder()) ?>" value="<?php echo $tbayartitipan->modified->EditValue ?>"<?php echo $tbayartitipan->modified->EditAttributes() ?>>
</span>
<?php echo $tbayartitipan->modified->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$tbayartitipan_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $tbayartitipan_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ftbayartitipanedit.Init();
</script>
<?php
$tbayartitipan_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tbayartitipan_edit->Page_Terminate();
?>
