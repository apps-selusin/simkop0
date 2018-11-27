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

$tpinjaman_add = NULL; // Initialize page object first

class ctpinjaman_add extends ctpinjaman {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tpinjaman';

	// Page object name
	var $PageObjName = 'tpinjaman_add';

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

		// Table object (tpinjaman)
		if (!isset($GLOBALS["tpinjaman"]) || get_class($GLOBALS["tpinjaman"]) == "ctpinjaman") {
			$GLOBALS["tpinjaman"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tpinjaman"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tpinjaman', TRUE);

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
					$this->Page_Terminate("tpinjamanlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "tpinjamanlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "tpinjamanview.php")
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
		$this->transaksi->CurrentValue = NULL;
		$this->transaksi->OldValue = $this->transaksi->CurrentValue;
		$this->referensi->CurrentValue = NULL;
		$this->referensi->OldValue = $this->referensi->CurrentValue;
		$this->anggota->CurrentValue = NULL;
		$this->anggota->OldValue = $this->anggota->CurrentValue;
		$this->namaanggota->CurrentValue = NULL;
		$this->namaanggota->OldValue = $this->namaanggota->CurrentValue;
		$this->alamat->CurrentValue = NULL;
		$this->alamat->OldValue = $this->alamat->CurrentValue;
		$this->pekerjaan->CurrentValue = NULL;
		$this->pekerjaan->OldValue = $this->pekerjaan->CurrentValue;
		$this->telepon->CurrentValue = NULL;
		$this->telepon->OldValue = $this->telepon->CurrentValue;
		$this->hp->CurrentValue = NULL;
		$this->hp->OldValue = $this->hp->CurrentValue;
		$this->fax->CurrentValue = NULL;
		$this->fax->OldValue = $this->fax->CurrentValue;
		$this->_email->CurrentValue = NULL;
		$this->_email->OldValue = $this->_email->CurrentValue;
		$this->website->CurrentValue = NULL;
		$this->website->OldValue = $this->website->CurrentValue;
		$this->jenisanggota->CurrentValue = NULL;
		$this->jenisanggota->OldValue = $this->jenisanggota->CurrentValue;
		$this->model->CurrentValue = NULL;
		$this->model->OldValue = $this->model->CurrentValue;
		$this->jenispinjaman->CurrentValue = NULL;
		$this->jenispinjaman->OldValue = $this->jenispinjaman->CurrentValue;
		$this->jenisbunga->CurrentValue = NULL;
		$this->jenisbunga->OldValue = $this->jenisbunga->CurrentValue;
		$this->angsuran->CurrentValue = 0;
		$this->masaangsuran->CurrentValue = NULL;
		$this->masaangsuran->OldValue = $this->masaangsuran->CurrentValue;
		$this->jatuhtempo->CurrentValue = "0000-00-00 00:00:00";
		$this->dispensasidenda->CurrentValue = 0;
		$this->agunan->CurrentValue = NULL;
		$this->agunan->OldValue = $this->agunan->CurrentValue;
		$this->dataagunan1->CurrentValue = NULL;
		$this->dataagunan1->OldValue = $this->dataagunan1->CurrentValue;
		$this->dataagunan2->CurrentValue = NULL;
		$this->dataagunan2->OldValue = $this->dataagunan2->CurrentValue;
		$this->dataagunan3->CurrentValue = NULL;
		$this->dataagunan3->OldValue = $this->dataagunan3->CurrentValue;
		$this->dataagunan4->CurrentValue = NULL;
		$this->dataagunan4->OldValue = $this->dataagunan4->CurrentValue;
		$this->dataagunan5->CurrentValue = NULL;
		$this->dataagunan5->OldValue = $this->dataagunan5->CurrentValue;
		$this->saldobekusimpanan->CurrentValue = 0;
		$this->saldobekuminimal->CurrentValue = 0;
		$this->plafond->CurrentValue = 0;
		$this->bunga->CurrentValue = 0;
		$this->bungapersen->CurrentValue = 0;
		$this->administrasi->CurrentValue = 0;
		$this->administrasipersen->CurrentValue = 0;
		$this->asuransi->CurrentValue = 0;
		$this->notaris->CurrentValue = 0;
		$this->biayamaterai->CurrentValue = 0;
		$this->potongansaldobeku->CurrentValue = 0;
		$this->angsuranpokok->CurrentValue = 0;
		$this->angsuranpokokauto->CurrentValue = 0;
		$this->angsuranbunga->CurrentValue = 0;
		$this->angsuranbungaauto->CurrentValue = 0;
		$this->denda->CurrentValue = 0;
		$this->dendapersen->CurrentValue = 0;
		$this->totalangsuran->CurrentValue = 0;
		$this->totalangsuranauto->CurrentValue = 0;
		$this->totalterima->CurrentValue = 0;
		$this->totalterimaauto->CurrentValue = 0;
		$this->terbilang->CurrentValue = NULL;
		$this->terbilang->OldValue = $this->terbilang->CurrentValue;
		$this->petugas->CurrentValue = NULL;
		$this->petugas->OldValue = $this->petugas->CurrentValue;
		$this->pembayaran->CurrentValue = NULL;
		$this->pembayaran->OldValue = $this->pembayaran->CurrentValue;
		$this->bank->CurrentValue = NULL;
		$this->bank->OldValue = $this->bank->CurrentValue;
		$this->atasnama->CurrentValue = NULL;
		$this->atasnama->OldValue = $this->atasnama->CurrentValue;
		$this->tipe->CurrentValue = NULL;
		$this->tipe->OldValue = $this->tipe->CurrentValue;
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
		if (!$this->agunan->FldIsDetailKey) {
			$this->agunan->setFormValue($objForm->GetValue("x_agunan"));
		}
		if (!$this->dataagunan1->FldIsDetailKey) {
			$this->dataagunan1->setFormValue($objForm->GetValue("x_dataagunan1"));
		}
		if (!$this->dataagunan2->FldIsDetailKey) {
			$this->dataagunan2->setFormValue($objForm->GetValue("x_dataagunan2"));
		}
		if (!$this->dataagunan3->FldIsDetailKey) {
			$this->dataagunan3->setFormValue($objForm->GetValue("x_dataagunan3"));
		}
		if (!$this->dataagunan4->FldIsDetailKey) {
			$this->dataagunan4->setFormValue($objForm->GetValue("x_dataagunan4"));
		}
		if (!$this->dataagunan5->FldIsDetailKey) {
			$this->dataagunan5->setFormValue($objForm->GetValue("x_dataagunan5"));
		}
		if (!$this->saldobekusimpanan->FldIsDetailKey) {
			$this->saldobekusimpanan->setFormValue($objForm->GetValue("x_saldobekusimpanan"));
		}
		if (!$this->saldobekuminimal->FldIsDetailKey) {
			$this->saldobekuminimal->setFormValue($objForm->GetValue("x_saldobekuminimal"));
		}
		if (!$this->plafond->FldIsDetailKey) {
			$this->plafond->setFormValue($objForm->GetValue("x_plafond"));
		}
		if (!$this->bunga->FldIsDetailKey) {
			$this->bunga->setFormValue($objForm->GetValue("x_bunga"));
		}
		if (!$this->bungapersen->FldIsDetailKey) {
			$this->bungapersen->setFormValue($objForm->GetValue("x_bungapersen"));
		}
		if (!$this->administrasi->FldIsDetailKey) {
			$this->administrasi->setFormValue($objForm->GetValue("x_administrasi"));
		}
		if (!$this->administrasipersen->FldIsDetailKey) {
			$this->administrasipersen->setFormValue($objForm->GetValue("x_administrasipersen"));
		}
		if (!$this->asuransi->FldIsDetailKey) {
			$this->asuransi->setFormValue($objForm->GetValue("x_asuransi"));
		}
		if (!$this->notaris->FldIsDetailKey) {
			$this->notaris->setFormValue($objForm->GetValue("x_notaris"));
		}
		if (!$this->biayamaterai->FldIsDetailKey) {
			$this->biayamaterai->setFormValue($objForm->GetValue("x_biayamaterai"));
		}
		if (!$this->potongansaldobeku->FldIsDetailKey) {
			$this->potongansaldobeku->setFormValue($objForm->GetValue("x_potongansaldobeku"));
		}
		if (!$this->angsuranpokok->FldIsDetailKey) {
			$this->angsuranpokok->setFormValue($objForm->GetValue("x_angsuranpokok"));
		}
		if (!$this->angsuranpokokauto->FldIsDetailKey) {
			$this->angsuranpokokauto->setFormValue($objForm->GetValue("x_angsuranpokokauto"));
		}
		if (!$this->angsuranbunga->FldIsDetailKey) {
			$this->angsuranbunga->setFormValue($objForm->GetValue("x_angsuranbunga"));
		}
		if (!$this->angsuranbungaauto->FldIsDetailKey) {
			$this->angsuranbungaauto->setFormValue($objForm->GetValue("x_angsuranbungaauto"));
		}
		if (!$this->denda->FldIsDetailKey) {
			$this->denda->setFormValue($objForm->GetValue("x_denda"));
		}
		if (!$this->dendapersen->FldIsDetailKey) {
			$this->dendapersen->setFormValue($objForm->GetValue("x_dendapersen"));
		}
		if (!$this->totalangsuran->FldIsDetailKey) {
			$this->totalangsuran->setFormValue($objForm->GetValue("x_totalangsuran"));
		}
		if (!$this->totalangsuranauto->FldIsDetailKey) {
			$this->totalangsuranauto->setFormValue($objForm->GetValue("x_totalangsuranauto"));
		}
		if (!$this->totalterima->FldIsDetailKey) {
			$this->totalterima->setFormValue($objForm->GetValue("x_totalterima"));
		}
		if (!$this->totalterimaauto->FldIsDetailKey) {
			$this->totalterimaauto->setFormValue($objForm->GetValue("x_totalterimaauto"));
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
		$this->LoadOldRecord();
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
		$this->agunan->CurrentValue = $this->agunan->FormValue;
		$this->dataagunan1->CurrentValue = $this->dataagunan1->FormValue;
		$this->dataagunan2->CurrentValue = $this->dataagunan2->FormValue;
		$this->dataagunan3->CurrentValue = $this->dataagunan3->FormValue;
		$this->dataagunan4->CurrentValue = $this->dataagunan4->FormValue;
		$this->dataagunan5->CurrentValue = $this->dataagunan5->FormValue;
		$this->saldobekusimpanan->CurrentValue = $this->saldobekusimpanan->FormValue;
		$this->saldobekuminimal->CurrentValue = $this->saldobekuminimal->FormValue;
		$this->plafond->CurrentValue = $this->plafond->FormValue;
		$this->bunga->CurrentValue = $this->bunga->FormValue;
		$this->bungapersen->CurrentValue = $this->bungapersen->FormValue;
		$this->administrasi->CurrentValue = $this->administrasi->FormValue;
		$this->administrasipersen->CurrentValue = $this->administrasipersen->FormValue;
		$this->asuransi->CurrentValue = $this->asuransi->FormValue;
		$this->notaris->CurrentValue = $this->notaris->FormValue;
		$this->biayamaterai->CurrentValue = $this->biayamaterai->FormValue;
		$this->potongansaldobeku->CurrentValue = $this->potongansaldobeku->FormValue;
		$this->angsuranpokok->CurrentValue = $this->angsuranpokok->FormValue;
		$this->angsuranpokokauto->CurrentValue = $this->angsuranpokokauto->FormValue;
		$this->angsuranbunga->CurrentValue = $this->angsuranbunga->FormValue;
		$this->angsuranbungaauto->CurrentValue = $this->angsuranbungaauto->FormValue;
		$this->denda->CurrentValue = $this->denda->FormValue;
		$this->dendapersen->CurrentValue = $this->dendapersen->FormValue;
		$this->totalangsuran->CurrentValue = $this->totalangsuran->FormValue;
		$this->totalangsuranauto->CurrentValue = $this->totalangsuranauto->FormValue;
		$this->totalterima->CurrentValue = $this->totalterima->FormValue;
		$this->totalterimaauto->CurrentValue = $this->totalterimaauto->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
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

			// agunan
			$this->agunan->EditAttrs["class"] = "form-control";
			$this->agunan->EditCustomAttributes = "";
			$this->agunan->EditValue = ew_HtmlEncode($this->agunan->CurrentValue);
			$this->agunan->PlaceHolder = ew_RemoveHtml($this->agunan->FldCaption());

			// dataagunan1
			$this->dataagunan1->EditAttrs["class"] = "form-control";
			$this->dataagunan1->EditCustomAttributes = "";
			$this->dataagunan1->EditValue = ew_HtmlEncode($this->dataagunan1->CurrentValue);
			$this->dataagunan1->PlaceHolder = ew_RemoveHtml($this->dataagunan1->FldCaption());

			// dataagunan2
			$this->dataagunan2->EditAttrs["class"] = "form-control";
			$this->dataagunan2->EditCustomAttributes = "";
			$this->dataagunan2->EditValue = ew_HtmlEncode($this->dataagunan2->CurrentValue);
			$this->dataagunan2->PlaceHolder = ew_RemoveHtml($this->dataagunan2->FldCaption());

			// dataagunan3
			$this->dataagunan3->EditAttrs["class"] = "form-control";
			$this->dataagunan3->EditCustomAttributes = "";
			$this->dataagunan3->EditValue = ew_HtmlEncode($this->dataagunan3->CurrentValue);
			$this->dataagunan3->PlaceHolder = ew_RemoveHtml($this->dataagunan3->FldCaption());

			// dataagunan4
			$this->dataagunan4->EditAttrs["class"] = "form-control";
			$this->dataagunan4->EditCustomAttributes = "";
			$this->dataagunan4->EditValue = ew_HtmlEncode($this->dataagunan4->CurrentValue);
			$this->dataagunan4->PlaceHolder = ew_RemoveHtml($this->dataagunan4->FldCaption());

			// dataagunan5
			$this->dataagunan5->EditAttrs["class"] = "form-control";
			$this->dataagunan5->EditCustomAttributes = "";
			$this->dataagunan5->EditValue = ew_HtmlEncode($this->dataagunan5->CurrentValue);
			$this->dataagunan5->PlaceHolder = ew_RemoveHtml($this->dataagunan5->FldCaption());

			// saldobekusimpanan
			$this->saldobekusimpanan->EditAttrs["class"] = "form-control";
			$this->saldobekusimpanan->EditCustomAttributes = "";
			$this->saldobekusimpanan->EditValue = ew_HtmlEncode($this->saldobekusimpanan->CurrentValue);
			$this->saldobekusimpanan->PlaceHolder = ew_RemoveHtml($this->saldobekusimpanan->FldCaption());
			if (strval($this->saldobekusimpanan->EditValue) <> "" && is_numeric($this->saldobekusimpanan->EditValue)) $this->saldobekusimpanan->EditValue = ew_FormatNumber($this->saldobekusimpanan->EditValue, -2, -1, -2, 0);

			// saldobekuminimal
			$this->saldobekuminimal->EditAttrs["class"] = "form-control";
			$this->saldobekuminimal->EditCustomAttributes = "";
			$this->saldobekuminimal->EditValue = ew_HtmlEncode($this->saldobekuminimal->CurrentValue);
			$this->saldobekuminimal->PlaceHolder = ew_RemoveHtml($this->saldobekuminimal->FldCaption());
			if (strval($this->saldobekuminimal->EditValue) <> "" && is_numeric($this->saldobekuminimal->EditValue)) $this->saldobekuminimal->EditValue = ew_FormatNumber($this->saldobekuminimal->EditValue, -2, -1, -2, 0);

			// plafond
			$this->plafond->EditAttrs["class"] = "form-control";
			$this->plafond->EditCustomAttributes = "";
			$this->plafond->EditValue = ew_HtmlEncode($this->plafond->CurrentValue);
			$this->plafond->PlaceHolder = ew_RemoveHtml($this->plafond->FldCaption());
			if (strval($this->plafond->EditValue) <> "" && is_numeric($this->plafond->EditValue)) $this->plafond->EditValue = ew_FormatNumber($this->plafond->EditValue, -2, -1, -2, 0);

			// bunga
			$this->bunga->EditAttrs["class"] = "form-control";
			$this->bunga->EditCustomAttributes = "";
			$this->bunga->EditValue = ew_HtmlEncode($this->bunga->CurrentValue);
			$this->bunga->PlaceHolder = ew_RemoveHtml($this->bunga->FldCaption());
			if (strval($this->bunga->EditValue) <> "" && is_numeric($this->bunga->EditValue)) $this->bunga->EditValue = ew_FormatNumber($this->bunga->EditValue, -2, -1, -2, 0);

			// bungapersen
			$this->bungapersen->EditAttrs["class"] = "form-control";
			$this->bungapersen->EditCustomAttributes = "";
			$this->bungapersen->EditValue = ew_HtmlEncode($this->bungapersen->CurrentValue);
			$this->bungapersen->PlaceHolder = ew_RemoveHtml($this->bungapersen->FldCaption());
			if (strval($this->bungapersen->EditValue) <> "" && is_numeric($this->bungapersen->EditValue)) $this->bungapersen->EditValue = ew_FormatNumber($this->bungapersen->EditValue, -2, -1, -2, 0);

			// administrasi
			$this->administrasi->EditAttrs["class"] = "form-control";
			$this->administrasi->EditCustomAttributes = "";
			$this->administrasi->EditValue = ew_HtmlEncode($this->administrasi->CurrentValue);
			$this->administrasi->PlaceHolder = ew_RemoveHtml($this->administrasi->FldCaption());
			if (strval($this->administrasi->EditValue) <> "" && is_numeric($this->administrasi->EditValue)) $this->administrasi->EditValue = ew_FormatNumber($this->administrasi->EditValue, -2, -1, -2, 0);

			// administrasipersen
			$this->administrasipersen->EditAttrs["class"] = "form-control";
			$this->administrasipersen->EditCustomAttributes = "";
			$this->administrasipersen->EditValue = ew_HtmlEncode($this->administrasipersen->CurrentValue);
			$this->administrasipersen->PlaceHolder = ew_RemoveHtml($this->administrasipersen->FldCaption());
			if (strval($this->administrasipersen->EditValue) <> "" && is_numeric($this->administrasipersen->EditValue)) $this->administrasipersen->EditValue = ew_FormatNumber($this->administrasipersen->EditValue, -2, -1, -2, 0);

			// asuransi
			$this->asuransi->EditAttrs["class"] = "form-control";
			$this->asuransi->EditCustomAttributes = "";
			$this->asuransi->EditValue = ew_HtmlEncode($this->asuransi->CurrentValue);
			$this->asuransi->PlaceHolder = ew_RemoveHtml($this->asuransi->FldCaption());
			if (strval($this->asuransi->EditValue) <> "" && is_numeric($this->asuransi->EditValue)) $this->asuransi->EditValue = ew_FormatNumber($this->asuransi->EditValue, -2, -1, -2, 0);

			// notaris
			$this->notaris->EditAttrs["class"] = "form-control";
			$this->notaris->EditCustomAttributes = "";
			$this->notaris->EditValue = ew_HtmlEncode($this->notaris->CurrentValue);
			$this->notaris->PlaceHolder = ew_RemoveHtml($this->notaris->FldCaption());
			if (strval($this->notaris->EditValue) <> "" && is_numeric($this->notaris->EditValue)) $this->notaris->EditValue = ew_FormatNumber($this->notaris->EditValue, -2, -1, -2, 0);

			// biayamaterai
			$this->biayamaterai->EditAttrs["class"] = "form-control";
			$this->biayamaterai->EditCustomAttributes = "";
			$this->biayamaterai->EditValue = ew_HtmlEncode($this->biayamaterai->CurrentValue);
			$this->biayamaterai->PlaceHolder = ew_RemoveHtml($this->biayamaterai->FldCaption());
			if (strval($this->biayamaterai->EditValue) <> "" && is_numeric($this->biayamaterai->EditValue)) $this->biayamaterai->EditValue = ew_FormatNumber($this->biayamaterai->EditValue, -2, -1, -2, 0);

			// potongansaldobeku
			$this->potongansaldobeku->EditAttrs["class"] = "form-control";
			$this->potongansaldobeku->EditCustomAttributes = "";
			$this->potongansaldobeku->EditValue = ew_HtmlEncode($this->potongansaldobeku->CurrentValue);
			$this->potongansaldobeku->PlaceHolder = ew_RemoveHtml($this->potongansaldobeku->FldCaption());
			if (strval($this->potongansaldobeku->EditValue) <> "" && is_numeric($this->potongansaldobeku->EditValue)) $this->potongansaldobeku->EditValue = ew_FormatNumber($this->potongansaldobeku->EditValue, -2, -1, -2, 0);

			// angsuranpokok
			$this->angsuranpokok->EditAttrs["class"] = "form-control";
			$this->angsuranpokok->EditCustomAttributes = "";
			$this->angsuranpokok->EditValue = ew_HtmlEncode($this->angsuranpokok->CurrentValue);
			$this->angsuranpokok->PlaceHolder = ew_RemoveHtml($this->angsuranpokok->FldCaption());
			if (strval($this->angsuranpokok->EditValue) <> "" && is_numeric($this->angsuranpokok->EditValue)) $this->angsuranpokok->EditValue = ew_FormatNumber($this->angsuranpokok->EditValue, -2, -1, -2, 0);

			// angsuranpokokauto
			$this->angsuranpokokauto->EditAttrs["class"] = "form-control";
			$this->angsuranpokokauto->EditCustomAttributes = "";
			$this->angsuranpokokauto->EditValue = ew_HtmlEncode($this->angsuranpokokauto->CurrentValue);
			$this->angsuranpokokauto->PlaceHolder = ew_RemoveHtml($this->angsuranpokokauto->FldCaption());
			if (strval($this->angsuranpokokauto->EditValue) <> "" && is_numeric($this->angsuranpokokauto->EditValue)) $this->angsuranpokokauto->EditValue = ew_FormatNumber($this->angsuranpokokauto->EditValue, -2, -1, -2, 0);

			// angsuranbunga
			$this->angsuranbunga->EditAttrs["class"] = "form-control";
			$this->angsuranbunga->EditCustomAttributes = "";
			$this->angsuranbunga->EditValue = ew_HtmlEncode($this->angsuranbunga->CurrentValue);
			$this->angsuranbunga->PlaceHolder = ew_RemoveHtml($this->angsuranbunga->FldCaption());
			if (strval($this->angsuranbunga->EditValue) <> "" && is_numeric($this->angsuranbunga->EditValue)) $this->angsuranbunga->EditValue = ew_FormatNumber($this->angsuranbunga->EditValue, -2, -1, -2, 0);

			// angsuranbungaauto
			$this->angsuranbungaauto->EditAttrs["class"] = "form-control";
			$this->angsuranbungaauto->EditCustomAttributes = "";
			$this->angsuranbungaauto->EditValue = ew_HtmlEncode($this->angsuranbungaauto->CurrentValue);
			$this->angsuranbungaauto->PlaceHolder = ew_RemoveHtml($this->angsuranbungaauto->FldCaption());
			if (strval($this->angsuranbungaauto->EditValue) <> "" && is_numeric($this->angsuranbungaauto->EditValue)) $this->angsuranbungaauto->EditValue = ew_FormatNumber($this->angsuranbungaauto->EditValue, -2, -1, -2, 0);

			// denda
			$this->denda->EditAttrs["class"] = "form-control";
			$this->denda->EditCustomAttributes = "";
			$this->denda->EditValue = ew_HtmlEncode($this->denda->CurrentValue);
			$this->denda->PlaceHolder = ew_RemoveHtml($this->denda->FldCaption());
			if (strval($this->denda->EditValue) <> "" && is_numeric($this->denda->EditValue)) $this->denda->EditValue = ew_FormatNumber($this->denda->EditValue, -2, -1, -2, 0);

			// dendapersen
			$this->dendapersen->EditAttrs["class"] = "form-control";
			$this->dendapersen->EditCustomAttributes = "";
			$this->dendapersen->EditValue = ew_HtmlEncode($this->dendapersen->CurrentValue);
			$this->dendapersen->PlaceHolder = ew_RemoveHtml($this->dendapersen->FldCaption());
			if (strval($this->dendapersen->EditValue) <> "" && is_numeric($this->dendapersen->EditValue)) $this->dendapersen->EditValue = ew_FormatNumber($this->dendapersen->EditValue, -2, -1, -2, 0);

			// totalangsuran
			$this->totalangsuran->EditAttrs["class"] = "form-control";
			$this->totalangsuran->EditCustomAttributes = "";
			$this->totalangsuran->EditValue = ew_HtmlEncode($this->totalangsuran->CurrentValue);
			$this->totalangsuran->PlaceHolder = ew_RemoveHtml($this->totalangsuran->FldCaption());
			if (strval($this->totalangsuran->EditValue) <> "" && is_numeric($this->totalangsuran->EditValue)) $this->totalangsuran->EditValue = ew_FormatNumber($this->totalangsuran->EditValue, -2, -1, -2, 0);

			// totalangsuranauto
			$this->totalangsuranauto->EditAttrs["class"] = "form-control";
			$this->totalangsuranauto->EditCustomAttributes = "";
			$this->totalangsuranauto->EditValue = ew_HtmlEncode($this->totalangsuranauto->CurrentValue);
			$this->totalangsuranauto->PlaceHolder = ew_RemoveHtml($this->totalangsuranauto->FldCaption());
			if (strval($this->totalangsuranauto->EditValue) <> "" && is_numeric($this->totalangsuranauto->EditValue)) $this->totalangsuranauto->EditValue = ew_FormatNumber($this->totalangsuranauto->EditValue, -2, -1, -2, 0);

			// totalterima
			$this->totalterima->EditAttrs["class"] = "form-control";
			$this->totalterima->EditCustomAttributes = "";
			$this->totalterima->EditValue = ew_HtmlEncode($this->totalterima->CurrentValue);
			$this->totalterima->PlaceHolder = ew_RemoveHtml($this->totalterima->FldCaption());
			if (strval($this->totalterima->EditValue) <> "" && is_numeric($this->totalterima->EditValue)) $this->totalterima->EditValue = ew_FormatNumber($this->totalterima->EditValue, -2, -1, -2, 0);

			// totalterimaauto
			$this->totalterimaauto->EditAttrs["class"] = "form-control";
			$this->totalterimaauto->EditCustomAttributes = "";
			$this->totalterimaauto->EditValue = ew_HtmlEncode($this->totalterimaauto->CurrentValue);
			$this->totalterimaauto->PlaceHolder = ew_RemoveHtml($this->totalterimaauto->FldCaption());
			if (strval($this->totalterimaauto->EditValue) <> "" && is_numeric($this->totalterimaauto->EditValue)) $this->totalterimaauto->EditValue = ew_FormatNumber($this->totalterimaauto->EditValue, -2, -1, -2, 0);

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

			// agunan
			$this->agunan->LinkCustomAttributes = "";
			$this->agunan->HrefValue = "";

			// dataagunan1
			$this->dataagunan1->LinkCustomAttributes = "";
			$this->dataagunan1->HrefValue = "";

			// dataagunan2
			$this->dataagunan2->LinkCustomAttributes = "";
			$this->dataagunan2->HrefValue = "";

			// dataagunan3
			$this->dataagunan3->LinkCustomAttributes = "";
			$this->dataagunan3->HrefValue = "";

			// dataagunan4
			$this->dataagunan4->LinkCustomAttributes = "";
			$this->dataagunan4->HrefValue = "";

			// dataagunan5
			$this->dataagunan5->LinkCustomAttributes = "";
			$this->dataagunan5->HrefValue = "";

			// saldobekusimpanan
			$this->saldobekusimpanan->LinkCustomAttributes = "";
			$this->saldobekusimpanan->HrefValue = "";

			// saldobekuminimal
			$this->saldobekuminimal->LinkCustomAttributes = "";
			$this->saldobekuminimal->HrefValue = "";

			// plafond
			$this->plafond->LinkCustomAttributes = "";
			$this->plafond->HrefValue = "";

			// bunga
			$this->bunga->LinkCustomAttributes = "";
			$this->bunga->HrefValue = "";

			// bungapersen
			$this->bungapersen->LinkCustomAttributes = "";
			$this->bungapersen->HrefValue = "";

			// administrasi
			$this->administrasi->LinkCustomAttributes = "";
			$this->administrasi->HrefValue = "";

			// administrasipersen
			$this->administrasipersen->LinkCustomAttributes = "";
			$this->administrasipersen->HrefValue = "";

			// asuransi
			$this->asuransi->LinkCustomAttributes = "";
			$this->asuransi->HrefValue = "";

			// notaris
			$this->notaris->LinkCustomAttributes = "";
			$this->notaris->HrefValue = "";

			// biayamaterai
			$this->biayamaterai->LinkCustomAttributes = "";
			$this->biayamaterai->HrefValue = "";

			// potongansaldobeku
			$this->potongansaldobeku->LinkCustomAttributes = "";
			$this->potongansaldobeku->HrefValue = "";

			// angsuranpokok
			$this->angsuranpokok->LinkCustomAttributes = "";
			$this->angsuranpokok->HrefValue = "";

			// angsuranpokokauto
			$this->angsuranpokokauto->LinkCustomAttributes = "";
			$this->angsuranpokokauto->HrefValue = "";

			// angsuranbunga
			$this->angsuranbunga->LinkCustomAttributes = "";
			$this->angsuranbunga->HrefValue = "";

			// angsuranbungaauto
			$this->angsuranbungaauto->LinkCustomAttributes = "";
			$this->angsuranbungaauto->HrefValue = "";

			// denda
			$this->denda->LinkCustomAttributes = "";
			$this->denda->HrefValue = "";

			// dendapersen
			$this->dendapersen->LinkCustomAttributes = "";
			$this->dendapersen->HrefValue = "";

			// totalangsuran
			$this->totalangsuran->LinkCustomAttributes = "";
			$this->totalangsuran->HrefValue = "";

			// totalangsuranauto
			$this->totalangsuranauto->LinkCustomAttributes = "";
			$this->totalangsuranauto->HrefValue = "";

			// totalterima
			$this->totalterima->LinkCustomAttributes = "";
			$this->totalterima->HrefValue = "";

			// totalterimaauto
			$this->totalterimaauto->LinkCustomAttributes = "";
			$this->totalterimaauto->HrefValue = "";

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
		if (!$this->agunan->FldIsDetailKey && !is_null($this->agunan->FormValue) && $this->agunan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->agunan->FldCaption(), $this->agunan->ReqErrMsg));
		}
		if (!$this->dataagunan1->FldIsDetailKey && !is_null($this->dataagunan1->FormValue) && $this->dataagunan1->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->dataagunan1->FldCaption(), $this->dataagunan1->ReqErrMsg));
		}
		if (!$this->dataagunan2->FldIsDetailKey && !is_null($this->dataagunan2->FormValue) && $this->dataagunan2->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->dataagunan2->FldCaption(), $this->dataagunan2->ReqErrMsg));
		}
		if (!$this->dataagunan3->FldIsDetailKey && !is_null($this->dataagunan3->FormValue) && $this->dataagunan3->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->dataagunan3->FldCaption(), $this->dataagunan3->ReqErrMsg));
		}
		if (!$this->dataagunan4->FldIsDetailKey && !is_null($this->dataagunan4->FormValue) && $this->dataagunan4->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->dataagunan4->FldCaption(), $this->dataagunan4->ReqErrMsg));
		}
		if (!$this->dataagunan5->FldIsDetailKey && !is_null($this->dataagunan5->FormValue) && $this->dataagunan5->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->dataagunan5->FldCaption(), $this->dataagunan5->ReqErrMsg));
		}
		if (!$this->saldobekusimpanan->FldIsDetailKey && !is_null($this->saldobekusimpanan->FormValue) && $this->saldobekusimpanan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->saldobekusimpanan->FldCaption(), $this->saldobekusimpanan->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->saldobekusimpanan->FormValue)) {
			ew_AddMessage($gsFormError, $this->saldobekusimpanan->FldErrMsg());
		}
		if (!$this->saldobekuminimal->FldIsDetailKey && !is_null($this->saldobekuminimal->FormValue) && $this->saldobekuminimal->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->saldobekuminimal->FldCaption(), $this->saldobekuminimal->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->saldobekuminimal->FormValue)) {
			ew_AddMessage($gsFormError, $this->saldobekuminimal->FldErrMsg());
		}
		if (!$this->plafond->FldIsDetailKey && !is_null($this->plafond->FormValue) && $this->plafond->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->plafond->FldCaption(), $this->plafond->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->plafond->FormValue)) {
			ew_AddMessage($gsFormError, $this->plafond->FldErrMsg());
		}
		if (!$this->bunga->FldIsDetailKey && !is_null($this->bunga->FormValue) && $this->bunga->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->bunga->FldCaption(), $this->bunga->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->bunga->FormValue)) {
			ew_AddMessage($gsFormError, $this->bunga->FldErrMsg());
		}
		if (!$this->bungapersen->FldIsDetailKey && !is_null($this->bungapersen->FormValue) && $this->bungapersen->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->bungapersen->FldCaption(), $this->bungapersen->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->bungapersen->FormValue)) {
			ew_AddMessage($gsFormError, $this->bungapersen->FldErrMsg());
		}
		if (!$this->administrasi->FldIsDetailKey && !is_null($this->administrasi->FormValue) && $this->administrasi->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->administrasi->FldCaption(), $this->administrasi->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->administrasi->FormValue)) {
			ew_AddMessage($gsFormError, $this->administrasi->FldErrMsg());
		}
		if (!$this->administrasipersen->FldIsDetailKey && !is_null($this->administrasipersen->FormValue) && $this->administrasipersen->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->administrasipersen->FldCaption(), $this->administrasipersen->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->administrasipersen->FormValue)) {
			ew_AddMessage($gsFormError, $this->administrasipersen->FldErrMsg());
		}
		if (!$this->asuransi->FldIsDetailKey && !is_null($this->asuransi->FormValue) && $this->asuransi->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->asuransi->FldCaption(), $this->asuransi->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->asuransi->FormValue)) {
			ew_AddMessage($gsFormError, $this->asuransi->FldErrMsg());
		}
		if (!$this->notaris->FldIsDetailKey && !is_null($this->notaris->FormValue) && $this->notaris->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->notaris->FldCaption(), $this->notaris->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->notaris->FormValue)) {
			ew_AddMessage($gsFormError, $this->notaris->FldErrMsg());
		}
		if (!$this->biayamaterai->FldIsDetailKey && !is_null($this->biayamaterai->FormValue) && $this->biayamaterai->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->biayamaterai->FldCaption(), $this->biayamaterai->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->biayamaterai->FormValue)) {
			ew_AddMessage($gsFormError, $this->biayamaterai->FldErrMsg());
		}
		if (!$this->potongansaldobeku->FldIsDetailKey && !is_null($this->potongansaldobeku->FormValue) && $this->potongansaldobeku->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->potongansaldobeku->FldCaption(), $this->potongansaldobeku->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->potongansaldobeku->FormValue)) {
			ew_AddMessage($gsFormError, $this->potongansaldobeku->FldErrMsg());
		}
		if (!$this->angsuranpokok->FldIsDetailKey && !is_null($this->angsuranpokok->FormValue) && $this->angsuranpokok->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->angsuranpokok->FldCaption(), $this->angsuranpokok->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->angsuranpokok->FormValue)) {
			ew_AddMessage($gsFormError, $this->angsuranpokok->FldErrMsg());
		}
		if (!$this->angsuranpokokauto->FldIsDetailKey && !is_null($this->angsuranpokokauto->FormValue) && $this->angsuranpokokauto->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->angsuranpokokauto->FldCaption(), $this->angsuranpokokauto->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->angsuranpokokauto->FormValue)) {
			ew_AddMessage($gsFormError, $this->angsuranpokokauto->FldErrMsg());
		}
		if (!$this->angsuranbunga->FldIsDetailKey && !is_null($this->angsuranbunga->FormValue) && $this->angsuranbunga->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->angsuranbunga->FldCaption(), $this->angsuranbunga->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->angsuranbunga->FormValue)) {
			ew_AddMessage($gsFormError, $this->angsuranbunga->FldErrMsg());
		}
		if (!$this->angsuranbungaauto->FldIsDetailKey && !is_null($this->angsuranbungaauto->FormValue) && $this->angsuranbungaauto->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->angsuranbungaauto->FldCaption(), $this->angsuranbungaauto->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->angsuranbungaauto->FormValue)) {
			ew_AddMessage($gsFormError, $this->angsuranbungaauto->FldErrMsg());
		}
		if (!$this->denda->FldIsDetailKey && !is_null($this->denda->FormValue) && $this->denda->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->denda->FldCaption(), $this->denda->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->denda->FormValue)) {
			ew_AddMessage($gsFormError, $this->denda->FldErrMsg());
		}
		if (!$this->dendapersen->FldIsDetailKey && !is_null($this->dendapersen->FormValue) && $this->dendapersen->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->dendapersen->FldCaption(), $this->dendapersen->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->dendapersen->FormValue)) {
			ew_AddMessage($gsFormError, $this->dendapersen->FldErrMsg());
		}
		if (!$this->totalangsuran->FldIsDetailKey && !is_null($this->totalangsuran->FormValue) && $this->totalangsuran->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->totalangsuran->FldCaption(), $this->totalangsuran->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->totalangsuran->FormValue)) {
			ew_AddMessage($gsFormError, $this->totalangsuran->FldErrMsg());
		}
		if (!$this->totalangsuranauto->FldIsDetailKey && !is_null($this->totalangsuranauto->FormValue) && $this->totalangsuranauto->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->totalangsuranauto->FldCaption(), $this->totalangsuranauto->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->totalangsuranauto->FormValue)) {
			ew_AddMessage($gsFormError, $this->totalangsuranauto->FldErrMsg());
		}
		if (!$this->totalterima->FldIsDetailKey && !is_null($this->totalterima->FormValue) && $this->totalterima->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->totalterima->FldCaption(), $this->totalterima->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->totalterima->FormValue)) {
			ew_AddMessage($gsFormError, $this->totalterima->FldErrMsg());
		}
		if (!$this->totalterimaauto->FldIsDetailKey && !is_null($this->totalterimaauto->FormValue) && $this->totalterimaauto->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->totalterimaauto->FldCaption(), $this->totalterimaauto->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->totalterimaauto->FormValue)) {
			ew_AddMessage($gsFormError, $this->totalterimaauto->FldErrMsg());
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

		// transaksi
		$this->transaksi->SetDbValueDef($rsnew, $this->transaksi->CurrentValue, "", FALSE);

		// referensi
		$this->referensi->SetDbValueDef($rsnew, $this->referensi->CurrentValue, "", FALSE);

		// anggota
		$this->anggota->SetDbValueDef($rsnew, $this->anggota->CurrentValue, "", FALSE);

		// namaanggota
		$this->namaanggota->SetDbValueDef($rsnew, $this->namaanggota->CurrentValue, "", FALSE);

		// alamat
		$this->alamat->SetDbValueDef($rsnew, $this->alamat->CurrentValue, "", FALSE);

		// pekerjaan
		$this->pekerjaan->SetDbValueDef($rsnew, $this->pekerjaan->CurrentValue, "", FALSE);

		// telepon
		$this->telepon->SetDbValueDef($rsnew, $this->telepon->CurrentValue, "", FALSE);

		// hp
		$this->hp->SetDbValueDef($rsnew, $this->hp->CurrentValue, "", FALSE);

		// fax
		$this->fax->SetDbValueDef($rsnew, $this->fax->CurrentValue, "", FALSE);

		// email
		$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, "", FALSE);

		// website
		$this->website->SetDbValueDef($rsnew, $this->website->CurrentValue, "", FALSE);

		// jenisanggota
		$this->jenisanggota->SetDbValueDef($rsnew, $this->jenisanggota->CurrentValue, "", FALSE);

		// model
		$this->model->SetDbValueDef($rsnew, $this->model->CurrentValue, "", FALSE);

		// jenispinjaman
		$this->jenispinjaman->SetDbValueDef($rsnew, $this->jenispinjaman->CurrentValue, "", FALSE);

		// jenisbunga
		$this->jenisbunga->SetDbValueDef($rsnew, $this->jenisbunga->CurrentValue, "", FALSE);

		// angsuran
		$this->angsuran->SetDbValueDef($rsnew, $this->angsuran->CurrentValue, 0, strval($this->angsuran->CurrentValue) == "");

		// masaangsuran
		$this->masaangsuran->SetDbValueDef($rsnew, $this->masaangsuran->CurrentValue, "", FALSE);

		// jatuhtempo
		$this->jatuhtempo->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->jatuhtempo->CurrentValue, 0), ew_CurrentDate(), strval($this->jatuhtempo->CurrentValue) == "");

		// dispensasidenda
		$this->dispensasidenda->SetDbValueDef($rsnew, $this->dispensasidenda->CurrentValue, 0, strval($this->dispensasidenda->CurrentValue) == "");

		// agunan
		$this->agunan->SetDbValueDef($rsnew, $this->agunan->CurrentValue, "", FALSE);

		// dataagunan1
		$this->dataagunan1->SetDbValueDef($rsnew, $this->dataagunan1->CurrentValue, "", FALSE);

		// dataagunan2
		$this->dataagunan2->SetDbValueDef($rsnew, $this->dataagunan2->CurrentValue, "", FALSE);

		// dataagunan3
		$this->dataagunan3->SetDbValueDef($rsnew, $this->dataagunan3->CurrentValue, "", FALSE);

		// dataagunan4
		$this->dataagunan4->SetDbValueDef($rsnew, $this->dataagunan4->CurrentValue, "", FALSE);

		// dataagunan5
		$this->dataagunan5->SetDbValueDef($rsnew, $this->dataagunan5->CurrentValue, "", FALSE);

		// saldobekusimpanan
		$this->saldobekusimpanan->SetDbValueDef($rsnew, $this->saldobekusimpanan->CurrentValue, 0, strval($this->saldobekusimpanan->CurrentValue) == "");

		// saldobekuminimal
		$this->saldobekuminimal->SetDbValueDef($rsnew, $this->saldobekuminimal->CurrentValue, 0, strval($this->saldobekuminimal->CurrentValue) == "");

		// plafond
		$this->plafond->SetDbValueDef($rsnew, $this->plafond->CurrentValue, 0, strval($this->plafond->CurrentValue) == "");

		// bunga
		$this->bunga->SetDbValueDef($rsnew, $this->bunga->CurrentValue, 0, strval($this->bunga->CurrentValue) == "");

		// bungapersen
		$this->bungapersen->SetDbValueDef($rsnew, $this->bungapersen->CurrentValue, 0, strval($this->bungapersen->CurrentValue) == "");

		// administrasi
		$this->administrasi->SetDbValueDef($rsnew, $this->administrasi->CurrentValue, 0, strval($this->administrasi->CurrentValue) == "");

		// administrasipersen
		$this->administrasipersen->SetDbValueDef($rsnew, $this->administrasipersen->CurrentValue, 0, strval($this->administrasipersen->CurrentValue) == "");

		// asuransi
		$this->asuransi->SetDbValueDef($rsnew, $this->asuransi->CurrentValue, 0, strval($this->asuransi->CurrentValue) == "");

		// notaris
		$this->notaris->SetDbValueDef($rsnew, $this->notaris->CurrentValue, 0, strval($this->notaris->CurrentValue) == "");

		// biayamaterai
		$this->biayamaterai->SetDbValueDef($rsnew, $this->biayamaterai->CurrentValue, 0, strval($this->biayamaterai->CurrentValue) == "");

		// potongansaldobeku
		$this->potongansaldobeku->SetDbValueDef($rsnew, $this->potongansaldobeku->CurrentValue, 0, strval($this->potongansaldobeku->CurrentValue) == "");

		// angsuranpokok
		$this->angsuranpokok->SetDbValueDef($rsnew, $this->angsuranpokok->CurrentValue, 0, strval($this->angsuranpokok->CurrentValue) == "");

		// angsuranpokokauto
		$this->angsuranpokokauto->SetDbValueDef($rsnew, $this->angsuranpokokauto->CurrentValue, 0, strval($this->angsuranpokokauto->CurrentValue) == "");

		// angsuranbunga
		$this->angsuranbunga->SetDbValueDef($rsnew, $this->angsuranbunga->CurrentValue, 0, strval($this->angsuranbunga->CurrentValue) == "");

		// angsuranbungaauto
		$this->angsuranbungaauto->SetDbValueDef($rsnew, $this->angsuranbungaauto->CurrentValue, 0, strval($this->angsuranbungaauto->CurrentValue) == "");

		// denda
		$this->denda->SetDbValueDef($rsnew, $this->denda->CurrentValue, 0, strval($this->denda->CurrentValue) == "");

		// dendapersen
		$this->dendapersen->SetDbValueDef($rsnew, $this->dendapersen->CurrentValue, 0, strval($this->dendapersen->CurrentValue) == "");

		// totalangsuran
		$this->totalangsuran->SetDbValueDef($rsnew, $this->totalangsuran->CurrentValue, 0, strval($this->totalangsuran->CurrentValue) == "");

		// totalangsuranauto
		$this->totalangsuranauto->SetDbValueDef($rsnew, $this->totalangsuranauto->CurrentValue, 0, strval($this->totalangsuranauto->CurrentValue) == "");

		// totalterima
		$this->totalterima->SetDbValueDef($rsnew, $this->totalterima->CurrentValue, 0, strval($this->totalterima->CurrentValue) == "");

		// totalterimaauto
		$this->totalterimaauto->SetDbValueDef($rsnew, $this->totalterimaauto->CurrentValue, 0, strval($this->totalterimaauto->CurrentValue) == "");

		// terbilang
		$this->terbilang->SetDbValueDef($rsnew, $this->terbilang->CurrentValue, "", FALSE);

		// petugas
		$this->petugas->SetDbValueDef($rsnew, $this->petugas->CurrentValue, "", FALSE);

		// pembayaran
		$this->pembayaran->SetDbValueDef($rsnew, $this->pembayaran->CurrentValue, "", FALSE);

		// bank
		$this->bank->SetDbValueDef($rsnew, $this->bank->CurrentValue, "", FALSE);

		// atasnama
		$this->atasnama->SetDbValueDef($rsnew, $this->atasnama->CurrentValue, "", FALSE);

		// tipe
		$this->tipe->SetDbValueDef($rsnew, $this->tipe->CurrentValue, "", FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("tpinjamanlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($tpinjaman_add)) $tpinjaman_add = new ctpinjaman_add();

// Page init
$tpinjaman_add->Page_Init();

// Page main
$tpinjaman_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tpinjaman_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = ftpinjamanadd = new ew_Form("ftpinjamanadd", "add");

// Validate form
ftpinjamanadd.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->tanggal->FldCaption(), $tpinjaman->tanggal->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tanggal");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjaman->tanggal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_periode");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->periode->FldCaption(), $tpinjaman->periode->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->id->FldCaption(), $tpinjaman->id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_transaksi");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->transaksi->FldCaption(), $tpinjaman->transaksi->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_referensi");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->referensi->FldCaption(), $tpinjaman->referensi->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_anggota");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->anggota->FldCaption(), $tpinjaman->anggota->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_namaanggota");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->namaanggota->FldCaption(), $tpinjaman->namaanggota->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_alamat");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->alamat->FldCaption(), $tpinjaman->alamat->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pekerjaan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->pekerjaan->FldCaption(), $tpinjaman->pekerjaan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_telepon");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->telepon->FldCaption(), $tpinjaman->telepon->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_hp");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->hp->FldCaption(), $tpinjaman->hp->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fax");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->fax->FldCaption(), $tpinjaman->fax->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__email");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->_email->FldCaption(), $tpinjaman->_email->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_website");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->website->FldCaption(), $tpinjaman->website->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jenisanggota");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->jenisanggota->FldCaption(), $tpinjaman->jenisanggota->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_model");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->model->FldCaption(), $tpinjaman->model->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jenispinjaman");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->jenispinjaman->FldCaption(), $tpinjaman->jenispinjaman->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jenisbunga");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->jenisbunga->FldCaption(), $tpinjaman->jenisbunga->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_angsuran");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->angsuran->FldCaption(), $tpinjaman->angsuran->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_angsuran");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjaman->angsuran->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_masaangsuran");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->masaangsuran->FldCaption(), $tpinjaman->masaangsuran->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jatuhtempo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->jatuhtempo->FldCaption(), $tpinjaman->jatuhtempo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jatuhtempo");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjaman->jatuhtempo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dispensasidenda");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->dispensasidenda->FldCaption(), $tpinjaman->dispensasidenda->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dispensasidenda");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjaman->dispensasidenda->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_agunan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->agunan->FldCaption(), $tpinjaman->agunan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dataagunan1");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->dataagunan1->FldCaption(), $tpinjaman->dataagunan1->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dataagunan2");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->dataagunan2->FldCaption(), $tpinjaman->dataagunan2->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dataagunan3");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->dataagunan3->FldCaption(), $tpinjaman->dataagunan3->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dataagunan4");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->dataagunan4->FldCaption(), $tpinjaman->dataagunan4->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dataagunan5");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->dataagunan5->FldCaption(), $tpinjaman->dataagunan5->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_saldobekusimpanan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->saldobekusimpanan->FldCaption(), $tpinjaman->saldobekusimpanan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_saldobekusimpanan");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjaman->saldobekusimpanan->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_saldobekuminimal");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->saldobekuminimal->FldCaption(), $tpinjaman->saldobekuminimal->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_saldobekuminimal");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjaman->saldobekuminimal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_plafond");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->plafond->FldCaption(), $tpinjaman->plafond->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_plafond");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjaman->plafond->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_bunga");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->bunga->FldCaption(), $tpinjaman->bunga->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bunga");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjaman->bunga->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_bungapersen");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->bungapersen->FldCaption(), $tpinjaman->bungapersen->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bungapersen");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjaman->bungapersen->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_administrasi");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->administrasi->FldCaption(), $tpinjaman->administrasi->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_administrasi");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjaman->administrasi->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_administrasipersen");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->administrasipersen->FldCaption(), $tpinjaman->administrasipersen->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_administrasipersen");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjaman->administrasipersen->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_asuransi");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->asuransi->FldCaption(), $tpinjaman->asuransi->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_asuransi");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjaman->asuransi->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_notaris");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->notaris->FldCaption(), $tpinjaman->notaris->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_notaris");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjaman->notaris->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_biayamaterai");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->biayamaterai->FldCaption(), $tpinjaman->biayamaterai->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_biayamaterai");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjaman->biayamaterai->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_potongansaldobeku");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->potongansaldobeku->FldCaption(), $tpinjaman->potongansaldobeku->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_potongansaldobeku");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjaman->potongansaldobeku->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_angsuranpokok");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->angsuranpokok->FldCaption(), $tpinjaman->angsuranpokok->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_angsuranpokok");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjaman->angsuranpokok->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_angsuranpokokauto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->angsuranpokokauto->FldCaption(), $tpinjaman->angsuranpokokauto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_angsuranpokokauto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjaman->angsuranpokokauto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_angsuranbunga");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->angsuranbunga->FldCaption(), $tpinjaman->angsuranbunga->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_angsuranbunga");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjaman->angsuranbunga->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_angsuranbungaauto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->angsuranbungaauto->FldCaption(), $tpinjaman->angsuranbungaauto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_angsuranbungaauto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjaman->angsuranbungaauto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_denda");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->denda->FldCaption(), $tpinjaman->denda->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_denda");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjaman->denda->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dendapersen");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->dendapersen->FldCaption(), $tpinjaman->dendapersen->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dendapersen");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjaman->dendapersen->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_totalangsuran");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->totalangsuran->FldCaption(), $tpinjaman->totalangsuran->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_totalangsuran");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjaman->totalangsuran->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_totalangsuranauto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->totalangsuranauto->FldCaption(), $tpinjaman->totalangsuranauto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_totalangsuranauto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjaman->totalangsuranauto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_totalterima");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->totalterima->FldCaption(), $tpinjaman->totalterima->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_totalterima");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjaman->totalterima->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_totalterimaauto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->totalterimaauto->FldCaption(), $tpinjaman->totalterimaauto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_totalterimaauto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjaman->totalterimaauto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_terbilang");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->terbilang->FldCaption(), $tpinjaman->terbilang->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_petugas");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->petugas->FldCaption(), $tpinjaman->petugas->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pembayaran");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->pembayaran->FldCaption(), $tpinjaman->pembayaran->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bank");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->bank->FldCaption(), $tpinjaman->bank->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_atasnama");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->atasnama->FldCaption(), $tpinjaman->atasnama->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tipe");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->tipe->FldCaption(), $tpinjaman->tipe->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_kantor");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->kantor->FldCaption(), $tpinjaman->kantor->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_keterangan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->keterangan->FldCaption(), $tpinjaman->keterangan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_active");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->active->FldCaption(), $tpinjaman->active->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ip");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->ip->FldCaption(), $tpinjaman->ip->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->status->FldCaption(), $tpinjaman->status->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_user");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->user->FldCaption(), $tpinjaman->user->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_created");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->created->FldCaption(), $tpinjaman->created->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_created");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjaman->created->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modified");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjaman->modified->FldCaption(), $tpinjaman->modified->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modified");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjaman->modified->FldErrMsg()) ?>");

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
ftpinjamanadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftpinjamanadd.ValidateRequired = true;
<?php } else { ?>
ftpinjamanadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftpinjamanadd.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftpinjamanadd.Lists["x_active"].Options = <?php echo json_encode($tpinjaman->active->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$tpinjaman_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $tpinjaman_add->ShowPageHeader(); ?>
<?php
$tpinjaman_add->ShowMessage();
?>
<form name="ftpinjamanadd" id="ftpinjamanadd" class="<?php echo $tpinjaman_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tpinjaman_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tpinjaman_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tpinjaman">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($tpinjaman_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($tpinjaman->tanggal->Visible) { // tanggal ?>
	<div id="r_tanggal" class="form-group">
		<label id="elh_tpinjaman_tanggal" for="x_tanggal" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->tanggal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->tanggal->CellAttributes() ?>>
<span id="el_tpinjaman_tanggal">
<input type="text" data-table="tpinjaman" data-field="x_tanggal" name="x_tanggal" id="x_tanggal" placeholder="<?php echo ew_HtmlEncode($tpinjaman->tanggal->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->tanggal->EditValue ?>"<?php echo $tpinjaman->tanggal->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->tanggal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->periode->Visible) { // periode ?>
	<div id="r_periode" class="form-group">
		<label id="elh_tpinjaman_periode" for="x_periode" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->periode->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->periode->CellAttributes() ?>>
<span id="el_tpinjaman_periode">
<input type="text" data-table="tpinjaman" data-field="x_periode" name="x_periode" id="x_periode" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tpinjaman->periode->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->periode->EditValue ?>"<?php echo $tpinjaman->periode->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->periode->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_tpinjaman_id" for="x_id" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->id->CellAttributes() ?>>
<span id="el_tpinjaman_id">
<input type="text" data-table="tpinjaman" data-field="x_id" name="x_id" id="x_id" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tpinjaman->id->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->id->EditValue ?>"<?php echo $tpinjaman->id->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->transaksi->Visible) { // transaksi ?>
	<div id="r_transaksi" class="form-group">
		<label id="elh_tpinjaman_transaksi" for="x_transaksi" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->transaksi->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->transaksi->CellAttributes() ?>>
<span id="el_tpinjaman_transaksi">
<input type="text" data-table="tpinjaman" data-field="x_transaksi" name="x_transaksi" id="x_transaksi" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tpinjaman->transaksi->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->transaksi->EditValue ?>"<?php echo $tpinjaman->transaksi->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->transaksi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->referensi->Visible) { // referensi ?>
	<div id="r_referensi" class="form-group">
		<label id="elh_tpinjaman_referensi" for="x_referensi" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->referensi->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->referensi->CellAttributes() ?>>
<span id="el_tpinjaman_referensi">
<input type="text" data-table="tpinjaman" data-field="x_referensi" name="x_referensi" id="x_referensi" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tpinjaman->referensi->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->referensi->EditValue ?>"<?php echo $tpinjaman->referensi->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->referensi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->anggota->Visible) { // anggota ?>
	<div id="r_anggota" class="form-group">
		<label id="elh_tpinjaman_anggota" for="x_anggota" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->anggota->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->anggota->CellAttributes() ?>>
<span id="el_tpinjaman_anggota">
<input type="text" data-table="tpinjaman" data-field="x_anggota" name="x_anggota" id="x_anggota" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tpinjaman->anggota->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->anggota->EditValue ?>"<?php echo $tpinjaman->anggota->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->anggota->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->namaanggota->Visible) { // namaanggota ?>
	<div id="r_namaanggota" class="form-group">
		<label id="elh_tpinjaman_namaanggota" for="x_namaanggota" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->namaanggota->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->namaanggota->CellAttributes() ?>>
<span id="el_tpinjaman_namaanggota">
<input type="text" data-table="tpinjaman" data-field="x_namaanggota" name="x_namaanggota" id="x_namaanggota" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($tpinjaman->namaanggota->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->namaanggota->EditValue ?>"<?php echo $tpinjaman->namaanggota->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->namaanggota->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->alamat->Visible) { // alamat ?>
	<div id="r_alamat" class="form-group">
		<label id="elh_tpinjaman_alamat" for="x_alamat" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->alamat->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->alamat->CellAttributes() ?>>
<span id="el_tpinjaman_alamat">
<input type="text" data-table="tpinjaman" data-field="x_alamat" name="x_alamat" id="x_alamat" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($tpinjaman->alamat->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->alamat->EditValue ?>"<?php echo $tpinjaman->alamat->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->alamat->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->pekerjaan->Visible) { // pekerjaan ?>
	<div id="r_pekerjaan" class="form-group">
		<label id="elh_tpinjaman_pekerjaan" for="x_pekerjaan" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->pekerjaan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->pekerjaan->CellAttributes() ?>>
<span id="el_tpinjaman_pekerjaan">
<input type="text" data-table="tpinjaman" data-field="x_pekerjaan" name="x_pekerjaan" id="x_pekerjaan" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tpinjaman->pekerjaan->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->pekerjaan->EditValue ?>"<?php echo $tpinjaman->pekerjaan->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->pekerjaan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->telepon->Visible) { // telepon ?>
	<div id="r_telepon" class="form-group">
		<label id="elh_tpinjaman_telepon" for="x_telepon" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->telepon->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->telepon->CellAttributes() ?>>
<span id="el_tpinjaman_telepon">
<input type="text" data-table="tpinjaman" data-field="x_telepon" name="x_telepon" id="x_telepon" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($tpinjaman->telepon->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->telepon->EditValue ?>"<?php echo $tpinjaman->telepon->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->telepon->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->hp->Visible) { // hp ?>
	<div id="r_hp" class="form-group">
		<label id="elh_tpinjaman_hp" for="x_hp" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->hp->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->hp->CellAttributes() ?>>
<span id="el_tpinjaman_hp">
<input type="text" data-table="tpinjaman" data-field="x_hp" name="x_hp" id="x_hp" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($tpinjaman->hp->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->hp->EditValue ?>"<?php echo $tpinjaman->hp->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->hp->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->fax->Visible) { // fax ?>
	<div id="r_fax" class="form-group">
		<label id="elh_tpinjaman_fax" for="x_fax" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->fax->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->fax->CellAttributes() ?>>
<span id="el_tpinjaman_fax">
<input type="text" data-table="tpinjaman" data-field="x_fax" name="x_fax" id="x_fax" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($tpinjaman->fax->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->fax->EditValue ?>"<?php echo $tpinjaman->fax->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->fax->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_tpinjaman__email" for="x__email" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->_email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->_email->CellAttributes() ?>>
<span id="el_tpinjaman__email">
<input type="text" data-table="tpinjaman" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($tpinjaman->_email->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->_email->EditValue ?>"<?php echo $tpinjaman->_email->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->website->Visible) { // website ?>
	<div id="r_website" class="form-group">
		<label id="elh_tpinjaman_website" for="x_website" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->website->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->website->CellAttributes() ?>>
<span id="el_tpinjaman_website">
<input type="text" data-table="tpinjaman" data-field="x_website" name="x_website" id="x_website" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($tpinjaman->website->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->website->EditValue ?>"<?php echo $tpinjaman->website->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->website->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->jenisanggota->Visible) { // jenisanggota ?>
	<div id="r_jenisanggota" class="form-group">
		<label id="elh_tpinjaman_jenisanggota" for="x_jenisanggota" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->jenisanggota->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->jenisanggota->CellAttributes() ?>>
<span id="el_tpinjaman_jenisanggota">
<input type="text" data-table="tpinjaman" data-field="x_jenisanggota" name="x_jenisanggota" id="x_jenisanggota" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tpinjaman->jenisanggota->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->jenisanggota->EditValue ?>"<?php echo $tpinjaman->jenisanggota->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->jenisanggota->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->model->Visible) { // model ?>
	<div id="r_model" class="form-group">
		<label id="elh_tpinjaman_model" for="x_model" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->model->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->model->CellAttributes() ?>>
<span id="el_tpinjaman_model">
<input type="text" data-table="tpinjaman" data-field="x_model" name="x_model" id="x_model" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tpinjaman->model->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->model->EditValue ?>"<?php echo $tpinjaman->model->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->model->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->jenispinjaman->Visible) { // jenispinjaman ?>
	<div id="r_jenispinjaman" class="form-group">
		<label id="elh_tpinjaman_jenispinjaman" for="x_jenispinjaman" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->jenispinjaman->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->jenispinjaman->CellAttributes() ?>>
<span id="el_tpinjaman_jenispinjaman">
<input type="text" data-table="tpinjaman" data-field="x_jenispinjaman" name="x_jenispinjaman" id="x_jenispinjaman" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tpinjaman->jenispinjaman->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->jenispinjaman->EditValue ?>"<?php echo $tpinjaman->jenispinjaman->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->jenispinjaman->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->jenisbunga->Visible) { // jenisbunga ?>
	<div id="r_jenisbunga" class="form-group">
		<label id="elh_tpinjaman_jenisbunga" for="x_jenisbunga" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->jenisbunga->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->jenisbunga->CellAttributes() ?>>
<span id="el_tpinjaman_jenisbunga">
<input type="text" data-table="tpinjaman" data-field="x_jenisbunga" name="x_jenisbunga" id="x_jenisbunga" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tpinjaman->jenisbunga->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->jenisbunga->EditValue ?>"<?php echo $tpinjaman->jenisbunga->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->jenisbunga->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->angsuran->Visible) { // angsuran ?>
	<div id="r_angsuran" class="form-group">
		<label id="elh_tpinjaman_angsuran" for="x_angsuran" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->angsuran->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->angsuran->CellAttributes() ?>>
<span id="el_tpinjaman_angsuran">
<input type="text" data-table="tpinjaman" data-field="x_angsuran" name="x_angsuran" id="x_angsuran" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjaman->angsuran->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->angsuran->EditValue ?>"<?php echo $tpinjaman->angsuran->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->angsuran->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->masaangsuran->Visible) { // masaangsuran ?>
	<div id="r_masaangsuran" class="form-group">
		<label id="elh_tpinjaman_masaangsuran" for="x_masaangsuran" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->masaangsuran->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->masaangsuran->CellAttributes() ?>>
<span id="el_tpinjaman_masaangsuran">
<input type="text" data-table="tpinjaman" data-field="x_masaangsuran" name="x_masaangsuran" id="x_masaangsuran" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tpinjaman->masaangsuran->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->masaangsuran->EditValue ?>"<?php echo $tpinjaman->masaangsuran->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->masaangsuran->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->jatuhtempo->Visible) { // jatuhtempo ?>
	<div id="r_jatuhtempo" class="form-group">
		<label id="elh_tpinjaman_jatuhtempo" for="x_jatuhtempo" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->jatuhtempo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->jatuhtempo->CellAttributes() ?>>
<span id="el_tpinjaman_jatuhtempo">
<input type="text" data-table="tpinjaman" data-field="x_jatuhtempo" name="x_jatuhtempo" id="x_jatuhtempo" placeholder="<?php echo ew_HtmlEncode($tpinjaman->jatuhtempo->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->jatuhtempo->EditValue ?>"<?php echo $tpinjaman->jatuhtempo->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->jatuhtempo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->dispensasidenda->Visible) { // dispensasidenda ?>
	<div id="r_dispensasidenda" class="form-group">
		<label id="elh_tpinjaman_dispensasidenda" for="x_dispensasidenda" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->dispensasidenda->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->dispensasidenda->CellAttributes() ?>>
<span id="el_tpinjaman_dispensasidenda">
<input type="text" data-table="tpinjaman" data-field="x_dispensasidenda" name="x_dispensasidenda" id="x_dispensasidenda" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjaman->dispensasidenda->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->dispensasidenda->EditValue ?>"<?php echo $tpinjaman->dispensasidenda->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->dispensasidenda->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->agunan->Visible) { // agunan ?>
	<div id="r_agunan" class="form-group">
		<label id="elh_tpinjaman_agunan" for="x_agunan" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->agunan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->agunan->CellAttributes() ?>>
<span id="el_tpinjaman_agunan">
<input type="text" data-table="tpinjaman" data-field="x_agunan" name="x_agunan" id="x_agunan" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tpinjaman->agunan->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->agunan->EditValue ?>"<?php echo $tpinjaman->agunan->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->agunan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->dataagunan1->Visible) { // dataagunan1 ?>
	<div id="r_dataagunan1" class="form-group">
		<label id="elh_tpinjaman_dataagunan1" for="x_dataagunan1" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->dataagunan1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->dataagunan1->CellAttributes() ?>>
<span id="el_tpinjaman_dataagunan1">
<input type="text" data-table="tpinjaman" data-field="x_dataagunan1" name="x_dataagunan1" id="x_dataagunan1" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($tpinjaman->dataagunan1->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->dataagunan1->EditValue ?>"<?php echo $tpinjaman->dataagunan1->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->dataagunan1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->dataagunan2->Visible) { // dataagunan2 ?>
	<div id="r_dataagunan2" class="form-group">
		<label id="elh_tpinjaman_dataagunan2" for="x_dataagunan2" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->dataagunan2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->dataagunan2->CellAttributes() ?>>
<span id="el_tpinjaman_dataagunan2">
<input type="text" data-table="tpinjaman" data-field="x_dataagunan2" name="x_dataagunan2" id="x_dataagunan2" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($tpinjaman->dataagunan2->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->dataagunan2->EditValue ?>"<?php echo $tpinjaman->dataagunan2->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->dataagunan2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->dataagunan3->Visible) { // dataagunan3 ?>
	<div id="r_dataagunan3" class="form-group">
		<label id="elh_tpinjaman_dataagunan3" for="x_dataagunan3" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->dataagunan3->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->dataagunan3->CellAttributes() ?>>
<span id="el_tpinjaman_dataagunan3">
<input type="text" data-table="tpinjaman" data-field="x_dataagunan3" name="x_dataagunan3" id="x_dataagunan3" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($tpinjaman->dataagunan3->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->dataagunan3->EditValue ?>"<?php echo $tpinjaman->dataagunan3->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->dataagunan3->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->dataagunan4->Visible) { // dataagunan4 ?>
	<div id="r_dataagunan4" class="form-group">
		<label id="elh_tpinjaman_dataagunan4" for="x_dataagunan4" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->dataagunan4->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->dataagunan4->CellAttributes() ?>>
<span id="el_tpinjaman_dataagunan4">
<input type="text" data-table="tpinjaman" data-field="x_dataagunan4" name="x_dataagunan4" id="x_dataagunan4" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($tpinjaman->dataagunan4->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->dataagunan4->EditValue ?>"<?php echo $tpinjaman->dataagunan4->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->dataagunan4->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->dataagunan5->Visible) { // dataagunan5 ?>
	<div id="r_dataagunan5" class="form-group">
		<label id="elh_tpinjaman_dataagunan5" for="x_dataagunan5" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->dataagunan5->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->dataagunan5->CellAttributes() ?>>
<span id="el_tpinjaman_dataagunan5">
<input type="text" data-table="tpinjaman" data-field="x_dataagunan5" name="x_dataagunan5" id="x_dataagunan5" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($tpinjaman->dataagunan5->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->dataagunan5->EditValue ?>"<?php echo $tpinjaman->dataagunan5->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->dataagunan5->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->saldobekusimpanan->Visible) { // saldobekusimpanan ?>
	<div id="r_saldobekusimpanan" class="form-group">
		<label id="elh_tpinjaman_saldobekusimpanan" for="x_saldobekusimpanan" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->saldobekusimpanan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->saldobekusimpanan->CellAttributes() ?>>
<span id="el_tpinjaman_saldobekusimpanan">
<input type="text" data-table="tpinjaman" data-field="x_saldobekusimpanan" name="x_saldobekusimpanan" id="x_saldobekusimpanan" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjaman->saldobekusimpanan->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->saldobekusimpanan->EditValue ?>"<?php echo $tpinjaman->saldobekusimpanan->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->saldobekusimpanan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->saldobekuminimal->Visible) { // saldobekuminimal ?>
	<div id="r_saldobekuminimal" class="form-group">
		<label id="elh_tpinjaman_saldobekuminimal" for="x_saldobekuminimal" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->saldobekuminimal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->saldobekuminimal->CellAttributes() ?>>
<span id="el_tpinjaman_saldobekuminimal">
<input type="text" data-table="tpinjaman" data-field="x_saldobekuminimal" name="x_saldobekuminimal" id="x_saldobekuminimal" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjaman->saldobekuminimal->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->saldobekuminimal->EditValue ?>"<?php echo $tpinjaman->saldobekuminimal->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->saldobekuminimal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->plafond->Visible) { // plafond ?>
	<div id="r_plafond" class="form-group">
		<label id="elh_tpinjaman_plafond" for="x_plafond" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->plafond->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->plafond->CellAttributes() ?>>
<span id="el_tpinjaman_plafond">
<input type="text" data-table="tpinjaman" data-field="x_plafond" name="x_plafond" id="x_plafond" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjaman->plafond->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->plafond->EditValue ?>"<?php echo $tpinjaman->plafond->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->plafond->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->bunga->Visible) { // bunga ?>
	<div id="r_bunga" class="form-group">
		<label id="elh_tpinjaman_bunga" for="x_bunga" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->bunga->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->bunga->CellAttributes() ?>>
<span id="el_tpinjaman_bunga">
<input type="text" data-table="tpinjaman" data-field="x_bunga" name="x_bunga" id="x_bunga" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjaman->bunga->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->bunga->EditValue ?>"<?php echo $tpinjaman->bunga->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->bunga->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->bungapersen->Visible) { // bungapersen ?>
	<div id="r_bungapersen" class="form-group">
		<label id="elh_tpinjaman_bungapersen" for="x_bungapersen" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->bungapersen->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->bungapersen->CellAttributes() ?>>
<span id="el_tpinjaman_bungapersen">
<input type="text" data-table="tpinjaman" data-field="x_bungapersen" name="x_bungapersen" id="x_bungapersen" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjaman->bungapersen->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->bungapersen->EditValue ?>"<?php echo $tpinjaman->bungapersen->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->bungapersen->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->administrasi->Visible) { // administrasi ?>
	<div id="r_administrasi" class="form-group">
		<label id="elh_tpinjaman_administrasi" for="x_administrasi" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->administrasi->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->administrasi->CellAttributes() ?>>
<span id="el_tpinjaman_administrasi">
<input type="text" data-table="tpinjaman" data-field="x_administrasi" name="x_administrasi" id="x_administrasi" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjaman->administrasi->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->administrasi->EditValue ?>"<?php echo $tpinjaman->administrasi->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->administrasi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->administrasipersen->Visible) { // administrasipersen ?>
	<div id="r_administrasipersen" class="form-group">
		<label id="elh_tpinjaman_administrasipersen" for="x_administrasipersen" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->administrasipersen->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->administrasipersen->CellAttributes() ?>>
<span id="el_tpinjaman_administrasipersen">
<input type="text" data-table="tpinjaman" data-field="x_administrasipersen" name="x_administrasipersen" id="x_administrasipersen" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjaman->administrasipersen->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->administrasipersen->EditValue ?>"<?php echo $tpinjaman->administrasipersen->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->administrasipersen->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->asuransi->Visible) { // asuransi ?>
	<div id="r_asuransi" class="form-group">
		<label id="elh_tpinjaman_asuransi" for="x_asuransi" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->asuransi->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->asuransi->CellAttributes() ?>>
<span id="el_tpinjaman_asuransi">
<input type="text" data-table="tpinjaman" data-field="x_asuransi" name="x_asuransi" id="x_asuransi" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjaman->asuransi->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->asuransi->EditValue ?>"<?php echo $tpinjaman->asuransi->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->asuransi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->notaris->Visible) { // notaris ?>
	<div id="r_notaris" class="form-group">
		<label id="elh_tpinjaman_notaris" for="x_notaris" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->notaris->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->notaris->CellAttributes() ?>>
<span id="el_tpinjaman_notaris">
<input type="text" data-table="tpinjaman" data-field="x_notaris" name="x_notaris" id="x_notaris" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjaman->notaris->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->notaris->EditValue ?>"<?php echo $tpinjaman->notaris->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->notaris->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->biayamaterai->Visible) { // biayamaterai ?>
	<div id="r_biayamaterai" class="form-group">
		<label id="elh_tpinjaman_biayamaterai" for="x_biayamaterai" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->biayamaterai->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->biayamaterai->CellAttributes() ?>>
<span id="el_tpinjaman_biayamaterai">
<input type="text" data-table="tpinjaman" data-field="x_biayamaterai" name="x_biayamaterai" id="x_biayamaterai" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjaman->biayamaterai->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->biayamaterai->EditValue ?>"<?php echo $tpinjaman->biayamaterai->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->biayamaterai->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->potongansaldobeku->Visible) { // potongansaldobeku ?>
	<div id="r_potongansaldobeku" class="form-group">
		<label id="elh_tpinjaman_potongansaldobeku" for="x_potongansaldobeku" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->potongansaldobeku->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->potongansaldobeku->CellAttributes() ?>>
<span id="el_tpinjaman_potongansaldobeku">
<input type="text" data-table="tpinjaman" data-field="x_potongansaldobeku" name="x_potongansaldobeku" id="x_potongansaldobeku" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjaman->potongansaldobeku->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->potongansaldobeku->EditValue ?>"<?php echo $tpinjaman->potongansaldobeku->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->potongansaldobeku->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->angsuranpokok->Visible) { // angsuranpokok ?>
	<div id="r_angsuranpokok" class="form-group">
		<label id="elh_tpinjaman_angsuranpokok" for="x_angsuranpokok" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->angsuranpokok->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->angsuranpokok->CellAttributes() ?>>
<span id="el_tpinjaman_angsuranpokok">
<input type="text" data-table="tpinjaman" data-field="x_angsuranpokok" name="x_angsuranpokok" id="x_angsuranpokok" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjaman->angsuranpokok->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->angsuranpokok->EditValue ?>"<?php echo $tpinjaman->angsuranpokok->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->angsuranpokok->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->angsuranpokokauto->Visible) { // angsuranpokokauto ?>
	<div id="r_angsuranpokokauto" class="form-group">
		<label id="elh_tpinjaman_angsuranpokokauto" for="x_angsuranpokokauto" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->angsuranpokokauto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->angsuranpokokauto->CellAttributes() ?>>
<span id="el_tpinjaman_angsuranpokokauto">
<input type="text" data-table="tpinjaman" data-field="x_angsuranpokokauto" name="x_angsuranpokokauto" id="x_angsuranpokokauto" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjaman->angsuranpokokauto->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->angsuranpokokauto->EditValue ?>"<?php echo $tpinjaman->angsuranpokokauto->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->angsuranpokokauto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->angsuranbunga->Visible) { // angsuranbunga ?>
	<div id="r_angsuranbunga" class="form-group">
		<label id="elh_tpinjaman_angsuranbunga" for="x_angsuranbunga" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->angsuranbunga->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->angsuranbunga->CellAttributes() ?>>
<span id="el_tpinjaman_angsuranbunga">
<input type="text" data-table="tpinjaman" data-field="x_angsuranbunga" name="x_angsuranbunga" id="x_angsuranbunga" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjaman->angsuranbunga->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->angsuranbunga->EditValue ?>"<?php echo $tpinjaman->angsuranbunga->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->angsuranbunga->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->angsuranbungaauto->Visible) { // angsuranbungaauto ?>
	<div id="r_angsuranbungaauto" class="form-group">
		<label id="elh_tpinjaman_angsuranbungaauto" for="x_angsuranbungaauto" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->angsuranbungaauto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->angsuranbungaauto->CellAttributes() ?>>
<span id="el_tpinjaman_angsuranbungaauto">
<input type="text" data-table="tpinjaman" data-field="x_angsuranbungaauto" name="x_angsuranbungaauto" id="x_angsuranbungaauto" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjaman->angsuranbungaauto->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->angsuranbungaauto->EditValue ?>"<?php echo $tpinjaman->angsuranbungaauto->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->angsuranbungaauto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->denda->Visible) { // denda ?>
	<div id="r_denda" class="form-group">
		<label id="elh_tpinjaman_denda" for="x_denda" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->denda->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->denda->CellAttributes() ?>>
<span id="el_tpinjaman_denda">
<input type="text" data-table="tpinjaman" data-field="x_denda" name="x_denda" id="x_denda" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjaman->denda->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->denda->EditValue ?>"<?php echo $tpinjaman->denda->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->denda->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->dendapersen->Visible) { // dendapersen ?>
	<div id="r_dendapersen" class="form-group">
		<label id="elh_tpinjaman_dendapersen" for="x_dendapersen" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->dendapersen->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->dendapersen->CellAttributes() ?>>
<span id="el_tpinjaman_dendapersen">
<input type="text" data-table="tpinjaman" data-field="x_dendapersen" name="x_dendapersen" id="x_dendapersen" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjaman->dendapersen->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->dendapersen->EditValue ?>"<?php echo $tpinjaman->dendapersen->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->dendapersen->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->totalangsuran->Visible) { // totalangsuran ?>
	<div id="r_totalangsuran" class="form-group">
		<label id="elh_tpinjaman_totalangsuran" for="x_totalangsuran" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->totalangsuran->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->totalangsuran->CellAttributes() ?>>
<span id="el_tpinjaman_totalangsuran">
<input type="text" data-table="tpinjaman" data-field="x_totalangsuran" name="x_totalangsuran" id="x_totalangsuran" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjaman->totalangsuran->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->totalangsuran->EditValue ?>"<?php echo $tpinjaman->totalangsuran->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->totalangsuran->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->totalangsuranauto->Visible) { // totalangsuranauto ?>
	<div id="r_totalangsuranauto" class="form-group">
		<label id="elh_tpinjaman_totalangsuranauto" for="x_totalangsuranauto" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->totalangsuranauto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->totalangsuranauto->CellAttributes() ?>>
<span id="el_tpinjaman_totalangsuranauto">
<input type="text" data-table="tpinjaman" data-field="x_totalangsuranauto" name="x_totalangsuranauto" id="x_totalangsuranauto" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjaman->totalangsuranauto->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->totalangsuranauto->EditValue ?>"<?php echo $tpinjaman->totalangsuranauto->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->totalangsuranauto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->totalterima->Visible) { // totalterima ?>
	<div id="r_totalterima" class="form-group">
		<label id="elh_tpinjaman_totalterima" for="x_totalterima" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->totalterima->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->totalterima->CellAttributes() ?>>
<span id="el_tpinjaman_totalterima">
<input type="text" data-table="tpinjaman" data-field="x_totalterima" name="x_totalterima" id="x_totalterima" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjaman->totalterima->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->totalterima->EditValue ?>"<?php echo $tpinjaman->totalterima->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->totalterima->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->totalterimaauto->Visible) { // totalterimaauto ?>
	<div id="r_totalterimaauto" class="form-group">
		<label id="elh_tpinjaman_totalterimaauto" for="x_totalterimaauto" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->totalterimaauto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->totalterimaauto->CellAttributes() ?>>
<span id="el_tpinjaman_totalterimaauto">
<input type="text" data-table="tpinjaman" data-field="x_totalterimaauto" name="x_totalterimaauto" id="x_totalterimaauto" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjaman->totalterimaauto->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->totalterimaauto->EditValue ?>"<?php echo $tpinjaman->totalterimaauto->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->totalterimaauto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->terbilang->Visible) { // terbilang ?>
	<div id="r_terbilang" class="form-group">
		<label id="elh_tpinjaman_terbilang" for="x_terbilang" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->terbilang->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->terbilang->CellAttributes() ?>>
<span id="el_tpinjaman_terbilang">
<input type="text" data-table="tpinjaman" data-field="x_terbilang" name="x_terbilang" id="x_terbilang" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($tpinjaman->terbilang->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->terbilang->EditValue ?>"<?php echo $tpinjaman->terbilang->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->terbilang->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->petugas->Visible) { // petugas ?>
	<div id="r_petugas" class="form-group">
		<label id="elh_tpinjaman_petugas" for="x_petugas" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->petugas->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->petugas->CellAttributes() ?>>
<span id="el_tpinjaman_petugas">
<input type="text" data-table="tpinjaman" data-field="x_petugas" name="x_petugas" id="x_petugas" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tpinjaman->petugas->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->petugas->EditValue ?>"<?php echo $tpinjaman->petugas->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->petugas->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->pembayaran->Visible) { // pembayaran ?>
	<div id="r_pembayaran" class="form-group">
		<label id="elh_tpinjaman_pembayaran" for="x_pembayaran" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->pembayaran->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->pembayaran->CellAttributes() ?>>
<span id="el_tpinjaman_pembayaran">
<input type="text" data-table="tpinjaman" data-field="x_pembayaran" name="x_pembayaran" id="x_pembayaran" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tpinjaman->pembayaran->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->pembayaran->EditValue ?>"<?php echo $tpinjaman->pembayaran->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->pembayaran->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->bank->Visible) { // bank ?>
	<div id="r_bank" class="form-group">
		<label id="elh_tpinjaman_bank" for="x_bank" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->bank->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->bank->CellAttributes() ?>>
<span id="el_tpinjaman_bank">
<input type="text" data-table="tpinjaman" data-field="x_bank" name="x_bank" id="x_bank" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tpinjaman->bank->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->bank->EditValue ?>"<?php echo $tpinjaman->bank->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->bank->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->atasnama->Visible) { // atasnama ?>
	<div id="r_atasnama" class="form-group">
		<label id="elh_tpinjaman_atasnama" for="x_atasnama" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->atasnama->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->atasnama->CellAttributes() ?>>
<span id="el_tpinjaman_atasnama">
<input type="text" data-table="tpinjaman" data-field="x_atasnama" name="x_atasnama" id="x_atasnama" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($tpinjaman->atasnama->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->atasnama->EditValue ?>"<?php echo $tpinjaman->atasnama->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->atasnama->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->tipe->Visible) { // tipe ?>
	<div id="r_tipe" class="form-group">
		<label id="elh_tpinjaman_tipe" for="x_tipe" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->tipe->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->tipe->CellAttributes() ?>>
<span id="el_tpinjaman_tipe">
<input type="text" data-table="tpinjaman" data-field="x_tipe" name="x_tipe" id="x_tipe" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tpinjaman->tipe->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->tipe->EditValue ?>"<?php echo $tpinjaman->tipe->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->tipe->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->kantor->Visible) { // kantor ?>
	<div id="r_kantor" class="form-group">
		<label id="elh_tpinjaman_kantor" for="x_kantor" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->kantor->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->kantor->CellAttributes() ?>>
<span id="el_tpinjaman_kantor">
<input type="text" data-table="tpinjaman" data-field="x_kantor" name="x_kantor" id="x_kantor" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tpinjaman->kantor->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->kantor->EditValue ?>"<?php echo $tpinjaman->kantor->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->kantor->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->keterangan->Visible) { // keterangan ?>
	<div id="r_keterangan" class="form-group">
		<label id="elh_tpinjaman_keterangan" for="x_keterangan" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->keterangan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->keterangan->CellAttributes() ?>>
<span id="el_tpinjaman_keterangan">
<input type="text" data-table="tpinjaman" data-field="x_keterangan" name="x_keterangan" id="x_keterangan" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($tpinjaman->keterangan->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->keterangan->EditValue ?>"<?php echo $tpinjaman->keterangan->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->keterangan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->active->Visible) { // active ?>
	<div id="r_active" class="form-group">
		<label id="elh_tpinjaman_active" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->active->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->active->CellAttributes() ?>>
<span id="el_tpinjaman_active">
<div id="tp_x_active" class="ewTemplate"><input type="radio" data-table="tpinjaman" data-field="x_active" data-value-separator="<?php echo $tpinjaman->active->DisplayValueSeparatorAttribute() ?>" name="x_active" id="x_active" value="{value}"<?php echo $tpinjaman->active->EditAttributes() ?>></div>
<div id="dsl_x_active" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $tpinjaman->active->RadioButtonListHtml(FALSE, "x_active") ?>
</div></div>
</span>
<?php echo $tpinjaman->active->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->ip->Visible) { // ip ?>
	<div id="r_ip" class="form-group">
		<label id="elh_tpinjaman_ip" for="x_ip" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->ip->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->ip->CellAttributes() ?>>
<span id="el_tpinjaman_ip">
<input type="text" data-table="tpinjaman" data-field="x_ip" name="x_ip" id="x_ip" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tpinjaman->ip->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->ip->EditValue ?>"<?php echo $tpinjaman->ip->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->ip->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_tpinjaman_status" for="x_status" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->status->CellAttributes() ?>>
<span id="el_tpinjaman_status">
<input type="text" data-table="tpinjaman" data-field="x_status" name="x_status" id="x_status" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tpinjaman->status->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->status->EditValue ?>"<?php echo $tpinjaman->status->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->user->Visible) { // user ?>
	<div id="r_user" class="form-group">
		<label id="elh_tpinjaman_user" for="x_user" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->user->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->user->CellAttributes() ?>>
<span id="el_tpinjaman_user">
<input type="text" data-table="tpinjaman" data-field="x_user" name="x_user" id="x_user" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tpinjaman->user->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->user->EditValue ?>"<?php echo $tpinjaman->user->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->user->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->created->Visible) { // created ?>
	<div id="r_created" class="form-group">
		<label id="elh_tpinjaman_created" for="x_created" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->created->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->created->CellAttributes() ?>>
<span id="el_tpinjaman_created">
<input type="text" data-table="tpinjaman" data-field="x_created" name="x_created" id="x_created" placeholder="<?php echo ew_HtmlEncode($tpinjaman->created->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->created->EditValue ?>"<?php echo $tpinjaman->created->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->created->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjaman->modified->Visible) { // modified ?>
	<div id="r_modified" class="form-group">
		<label id="elh_tpinjaman_modified" for="x_modified" class="col-sm-2 control-label ewLabel"><?php echo $tpinjaman->modified->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjaman->modified->CellAttributes() ?>>
<span id="el_tpinjaman_modified">
<input type="text" data-table="tpinjaman" data-field="x_modified" name="x_modified" id="x_modified" placeholder="<?php echo ew_HtmlEncode($tpinjaman->modified->getPlaceHolder()) ?>" value="<?php echo $tpinjaman->modified->EditValue ?>"<?php echo $tpinjaman->modified->EditAttributes() ?>>
</span>
<?php echo $tpinjaman->modified->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$tpinjaman_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $tpinjaman_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ftpinjamanadd.Init();
</script>
<?php
$tpinjaman_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tpinjaman_add->Page_Terminate();
?>
