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

$tbayarangsuran_add = NULL; // Initialize page object first

class ctbayarangsuran_add extends ctbayarangsuran {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tbayarangsuran';

	// Page object name
	var $PageObjName = 'tbayarangsuran_add';

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

		// Table object (tbayarangsuran)
		if (!isset($GLOBALS["tbayarangsuran"]) || get_class($GLOBALS["tbayarangsuran"]) == "ctbayarangsuran") {
			$GLOBALS["tbayarangsuran"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tbayarangsuran"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tbayarangsuran', TRUE);

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
			if (@$_GET["angsuran"] != "") {
				$this->angsuran->setQueryStringValue($_GET["angsuran"]);
				$this->setKey("angsuran", $this->angsuran->CurrentValue); // Set up key
			} else {
				$this->setKey("angsuran", ""); // Clear key
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
					$this->Page_Terminate("tbayarangsuranlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "tbayarangsuranlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "tbayarangsuranview.php")
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
		$this->terlambat->CurrentValue = 0;
		$this->dispensasidenda->CurrentValue = 0;
		$this->plafond->CurrentValue = 0;
		$this->angsuranpokok->CurrentValue = 0;
		$this->angsuranpokokauto->CurrentValue = 0;
		$this->angsuranbunga->CurrentValue = 0;
		$this->angsuranbungaauto->CurrentValue = 0;
		$this->denda->CurrentValue = 0;
		$this->dendapersen->CurrentValue = 0;
		$this->totalangsuran->CurrentValue = 0;
		$this->totalangsuranauto->CurrentValue = 0;
		$this->sisaangsuran->CurrentValue = 0;
		$this->sisaangsuranauto->CurrentValue = 0;
		$this->saldotitipan->CurrentValue = 0;
		$this->saldotitipansisa->CurrentValue = 0;
		$this->bayarpokok->CurrentValue = 0;
		$this->bayarpokokauto->CurrentValue = 0;
		$this->bayarbunga->CurrentValue = 0;
		$this->bayarbungaauto->CurrentValue = 0;
		$this->bayardenda->CurrentValue = 0;
		$this->bayardendaauto->CurrentValue = 0;
		$this->bayartitipan->CurrentValue = 0;
		$this->bayartitipanauto->CurrentValue = 0;
		$this->totalbayar->CurrentValue = 0;
		$this->totalbayarauto->CurrentValue = 0;
		$this->pelunasan->CurrentValue = 0;
		$this->pelunasanauto->CurrentValue = 0;
		$this->finalty->CurrentValue = 0;
		$this->finaltyauto->CurrentValue = 0;
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
		if (!$this->terlambat->FldIsDetailKey) {
			$this->terlambat->setFormValue($objForm->GetValue("x_terlambat"));
		}
		if (!$this->dispensasidenda->FldIsDetailKey) {
			$this->dispensasidenda->setFormValue($objForm->GetValue("x_dispensasidenda"));
		}
		if (!$this->plafond->FldIsDetailKey) {
			$this->plafond->setFormValue($objForm->GetValue("x_plafond"));
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
		if (!$this->sisaangsuran->FldIsDetailKey) {
			$this->sisaangsuran->setFormValue($objForm->GetValue("x_sisaangsuran"));
		}
		if (!$this->sisaangsuranauto->FldIsDetailKey) {
			$this->sisaangsuranauto->setFormValue($objForm->GetValue("x_sisaangsuranauto"));
		}
		if (!$this->saldotitipan->FldIsDetailKey) {
			$this->saldotitipan->setFormValue($objForm->GetValue("x_saldotitipan"));
		}
		if (!$this->saldotitipansisa->FldIsDetailKey) {
			$this->saldotitipansisa->setFormValue($objForm->GetValue("x_saldotitipansisa"));
		}
		if (!$this->bayarpokok->FldIsDetailKey) {
			$this->bayarpokok->setFormValue($objForm->GetValue("x_bayarpokok"));
		}
		if (!$this->bayarpokokauto->FldIsDetailKey) {
			$this->bayarpokokauto->setFormValue($objForm->GetValue("x_bayarpokokauto"));
		}
		if (!$this->bayarbunga->FldIsDetailKey) {
			$this->bayarbunga->setFormValue($objForm->GetValue("x_bayarbunga"));
		}
		if (!$this->bayarbungaauto->FldIsDetailKey) {
			$this->bayarbungaauto->setFormValue($objForm->GetValue("x_bayarbungaauto"));
		}
		if (!$this->bayardenda->FldIsDetailKey) {
			$this->bayardenda->setFormValue($objForm->GetValue("x_bayardenda"));
		}
		if (!$this->bayardendaauto->FldIsDetailKey) {
			$this->bayardendaauto->setFormValue($objForm->GetValue("x_bayardendaauto"));
		}
		if (!$this->bayartitipan->FldIsDetailKey) {
			$this->bayartitipan->setFormValue($objForm->GetValue("x_bayartitipan"));
		}
		if (!$this->bayartitipanauto->FldIsDetailKey) {
			$this->bayartitipanauto->setFormValue($objForm->GetValue("x_bayartitipanauto"));
		}
		if (!$this->totalbayar->FldIsDetailKey) {
			$this->totalbayar->setFormValue($objForm->GetValue("x_totalbayar"));
		}
		if (!$this->totalbayarauto->FldIsDetailKey) {
			$this->totalbayarauto->setFormValue($objForm->GetValue("x_totalbayarauto"));
		}
		if (!$this->pelunasan->FldIsDetailKey) {
			$this->pelunasan->setFormValue($objForm->GetValue("x_pelunasan"));
		}
		if (!$this->pelunasanauto->FldIsDetailKey) {
			$this->pelunasanauto->setFormValue($objForm->GetValue("x_pelunasanauto"));
		}
		if (!$this->finalty->FldIsDetailKey) {
			$this->finalty->setFormValue($objForm->GetValue("x_finalty"));
		}
		if (!$this->finaltyauto->FldIsDetailKey) {
			$this->finaltyauto->setFormValue($objForm->GetValue("x_finaltyauto"));
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
		$this->terlambat->CurrentValue = $this->terlambat->FormValue;
		$this->dispensasidenda->CurrentValue = $this->dispensasidenda->FormValue;
		$this->plafond->CurrentValue = $this->plafond->FormValue;
		$this->angsuranpokok->CurrentValue = $this->angsuranpokok->FormValue;
		$this->angsuranpokokauto->CurrentValue = $this->angsuranpokokauto->FormValue;
		$this->angsuranbunga->CurrentValue = $this->angsuranbunga->FormValue;
		$this->angsuranbungaauto->CurrentValue = $this->angsuranbungaauto->FormValue;
		$this->denda->CurrentValue = $this->denda->FormValue;
		$this->dendapersen->CurrentValue = $this->dendapersen->FormValue;
		$this->totalangsuran->CurrentValue = $this->totalangsuran->FormValue;
		$this->totalangsuranauto->CurrentValue = $this->totalangsuranauto->FormValue;
		$this->sisaangsuran->CurrentValue = $this->sisaangsuran->FormValue;
		$this->sisaangsuranauto->CurrentValue = $this->sisaangsuranauto->FormValue;
		$this->saldotitipan->CurrentValue = $this->saldotitipan->FormValue;
		$this->saldotitipansisa->CurrentValue = $this->saldotitipansisa->FormValue;
		$this->bayarpokok->CurrentValue = $this->bayarpokok->FormValue;
		$this->bayarpokokauto->CurrentValue = $this->bayarpokokauto->FormValue;
		$this->bayarbunga->CurrentValue = $this->bayarbunga->FormValue;
		$this->bayarbungaauto->CurrentValue = $this->bayarbungaauto->FormValue;
		$this->bayardenda->CurrentValue = $this->bayardenda->FormValue;
		$this->bayardendaauto->CurrentValue = $this->bayardendaauto->FormValue;
		$this->bayartitipan->CurrentValue = $this->bayartitipan->FormValue;
		$this->bayartitipanauto->CurrentValue = $this->bayartitipanauto->FormValue;
		$this->totalbayar->CurrentValue = $this->totalbayar->FormValue;
		$this->totalbayarauto->CurrentValue = $this->totalbayarauto->FormValue;
		$this->pelunasan->CurrentValue = $this->pelunasan->FormValue;
		$this->pelunasanauto->CurrentValue = $this->pelunasanauto->FormValue;
		$this->finalty->CurrentValue = $this->finalty->FormValue;
		$this->finaltyauto->CurrentValue = $this->finaltyauto->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("angsuran")) <> "")
			$this->angsuran->CurrentValue = $this->getKey("angsuran"); // angsuran
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

			// terlambat
			$this->terlambat->EditAttrs["class"] = "form-control";
			$this->terlambat->EditCustomAttributes = "";
			$this->terlambat->EditValue = ew_HtmlEncode($this->terlambat->CurrentValue);
			$this->terlambat->PlaceHolder = ew_RemoveHtml($this->terlambat->FldCaption());

			// dispensasidenda
			$this->dispensasidenda->EditAttrs["class"] = "form-control";
			$this->dispensasidenda->EditCustomAttributes = "";
			$this->dispensasidenda->EditValue = ew_HtmlEncode($this->dispensasidenda->CurrentValue);
			$this->dispensasidenda->PlaceHolder = ew_RemoveHtml($this->dispensasidenda->FldCaption());

			// plafond
			$this->plafond->EditAttrs["class"] = "form-control";
			$this->plafond->EditCustomAttributes = "";
			$this->plafond->EditValue = ew_HtmlEncode($this->plafond->CurrentValue);
			$this->plafond->PlaceHolder = ew_RemoveHtml($this->plafond->FldCaption());
			if (strval($this->plafond->EditValue) <> "" && is_numeric($this->plafond->EditValue)) $this->plafond->EditValue = ew_FormatNumber($this->plafond->EditValue, -2, -1, -2, 0);

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

			// sisaangsuran
			$this->sisaangsuran->EditAttrs["class"] = "form-control";
			$this->sisaangsuran->EditCustomAttributes = "";
			$this->sisaangsuran->EditValue = ew_HtmlEncode($this->sisaangsuran->CurrentValue);
			$this->sisaangsuran->PlaceHolder = ew_RemoveHtml($this->sisaangsuran->FldCaption());
			if (strval($this->sisaangsuran->EditValue) <> "" && is_numeric($this->sisaangsuran->EditValue)) $this->sisaangsuran->EditValue = ew_FormatNumber($this->sisaangsuran->EditValue, -2, -1, -2, 0);

			// sisaangsuranauto
			$this->sisaangsuranauto->EditAttrs["class"] = "form-control";
			$this->sisaangsuranauto->EditCustomAttributes = "";
			$this->sisaangsuranauto->EditValue = ew_HtmlEncode($this->sisaangsuranauto->CurrentValue);
			$this->sisaangsuranauto->PlaceHolder = ew_RemoveHtml($this->sisaangsuranauto->FldCaption());
			if (strval($this->sisaangsuranauto->EditValue) <> "" && is_numeric($this->sisaangsuranauto->EditValue)) $this->sisaangsuranauto->EditValue = ew_FormatNumber($this->sisaangsuranauto->EditValue, -2, -1, -2, 0);

			// saldotitipan
			$this->saldotitipan->EditAttrs["class"] = "form-control";
			$this->saldotitipan->EditCustomAttributes = "";
			$this->saldotitipan->EditValue = ew_HtmlEncode($this->saldotitipan->CurrentValue);
			$this->saldotitipan->PlaceHolder = ew_RemoveHtml($this->saldotitipan->FldCaption());
			if (strval($this->saldotitipan->EditValue) <> "" && is_numeric($this->saldotitipan->EditValue)) $this->saldotitipan->EditValue = ew_FormatNumber($this->saldotitipan->EditValue, -2, -1, -2, 0);

			// saldotitipansisa
			$this->saldotitipansisa->EditAttrs["class"] = "form-control";
			$this->saldotitipansisa->EditCustomAttributes = "";
			$this->saldotitipansisa->EditValue = ew_HtmlEncode($this->saldotitipansisa->CurrentValue);
			$this->saldotitipansisa->PlaceHolder = ew_RemoveHtml($this->saldotitipansisa->FldCaption());
			if (strval($this->saldotitipansisa->EditValue) <> "" && is_numeric($this->saldotitipansisa->EditValue)) $this->saldotitipansisa->EditValue = ew_FormatNumber($this->saldotitipansisa->EditValue, -2, -1, -2, 0);

			// bayarpokok
			$this->bayarpokok->EditAttrs["class"] = "form-control";
			$this->bayarpokok->EditCustomAttributes = "";
			$this->bayarpokok->EditValue = ew_HtmlEncode($this->bayarpokok->CurrentValue);
			$this->bayarpokok->PlaceHolder = ew_RemoveHtml($this->bayarpokok->FldCaption());
			if (strval($this->bayarpokok->EditValue) <> "" && is_numeric($this->bayarpokok->EditValue)) $this->bayarpokok->EditValue = ew_FormatNumber($this->bayarpokok->EditValue, -2, -1, -2, 0);

			// bayarpokokauto
			$this->bayarpokokauto->EditAttrs["class"] = "form-control";
			$this->bayarpokokauto->EditCustomAttributes = "";
			$this->bayarpokokauto->EditValue = ew_HtmlEncode($this->bayarpokokauto->CurrentValue);
			$this->bayarpokokauto->PlaceHolder = ew_RemoveHtml($this->bayarpokokauto->FldCaption());
			if (strval($this->bayarpokokauto->EditValue) <> "" && is_numeric($this->bayarpokokauto->EditValue)) $this->bayarpokokauto->EditValue = ew_FormatNumber($this->bayarpokokauto->EditValue, -2, -1, -2, 0);

			// bayarbunga
			$this->bayarbunga->EditAttrs["class"] = "form-control";
			$this->bayarbunga->EditCustomAttributes = "";
			$this->bayarbunga->EditValue = ew_HtmlEncode($this->bayarbunga->CurrentValue);
			$this->bayarbunga->PlaceHolder = ew_RemoveHtml($this->bayarbunga->FldCaption());
			if (strval($this->bayarbunga->EditValue) <> "" && is_numeric($this->bayarbunga->EditValue)) $this->bayarbunga->EditValue = ew_FormatNumber($this->bayarbunga->EditValue, -2, -1, -2, 0);

			// bayarbungaauto
			$this->bayarbungaauto->EditAttrs["class"] = "form-control";
			$this->bayarbungaauto->EditCustomAttributes = "";
			$this->bayarbungaauto->EditValue = ew_HtmlEncode($this->bayarbungaauto->CurrentValue);
			$this->bayarbungaauto->PlaceHolder = ew_RemoveHtml($this->bayarbungaauto->FldCaption());
			if (strval($this->bayarbungaauto->EditValue) <> "" && is_numeric($this->bayarbungaauto->EditValue)) $this->bayarbungaauto->EditValue = ew_FormatNumber($this->bayarbungaauto->EditValue, -2, -1, -2, 0);

			// bayardenda
			$this->bayardenda->EditAttrs["class"] = "form-control";
			$this->bayardenda->EditCustomAttributes = "";
			$this->bayardenda->EditValue = ew_HtmlEncode($this->bayardenda->CurrentValue);
			$this->bayardenda->PlaceHolder = ew_RemoveHtml($this->bayardenda->FldCaption());
			if (strval($this->bayardenda->EditValue) <> "" && is_numeric($this->bayardenda->EditValue)) $this->bayardenda->EditValue = ew_FormatNumber($this->bayardenda->EditValue, -2, -1, -2, 0);

			// bayardendaauto
			$this->bayardendaauto->EditAttrs["class"] = "form-control";
			$this->bayardendaauto->EditCustomAttributes = "";
			$this->bayardendaauto->EditValue = ew_HtmlEncode($this->bayardendaauto->CurrentValue);
			$this->bayardendaauto->PlaceHolder = ew_RemoveHtml($this->bayardendaauto->FldCaption());
			if (strval($this->bayardendaauto->EditValue) <> "" && is_numeric($this->bayardendaauto->EditValue)) $this->bayardendaauto->EditValue = ew_FormatNumber($this->bayardendaauto->EditValue, -2, -1, -2, 0);

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

			// totalbayar
			$this->totalbayar->EditAttrs["class"] = "form-control";
			$this->totalbayar->EditCustomAttributes = "";
			$this->totalbayar->EditValue = ew_HtmlEncode($this->totalbayar->CurrentValue);
			$this->totalbayar->PlaceHolder = ew_RemoveHtml($this->totalbayar->FldCaption());
			if (strval($this->totalbayar->EditValue) <> "" && is_numeric($this->totalbayar->EditValue)) $this->totalbayar->EditValue = ew_FormatNumber($this->totalbayar->EditValue, -2, -1, -2, 0);

			// totalbayarauto
			$this->totalbayarauto->EditAttrs["class"] = "form-control";
			$this->totalbayarauto->EditCustomAttributes = "";
			$this->totalbayarauto->EditValue = ew_HtmlEncode($this->totalbayarauto->CurrentValue);
			$this->totalbayarauto->PlaceHolder = ew_RemoveHtml($this->totalbayarauto->FldCaption());
			if (strval($this->totalbayarauto->EditValue) <> "" && is_numeric($this->totalbayarauto->EditValue)) $this->totalbayarauto->EditValue = ew_FormatNumber($this->totalbayarauto->EditValue, -2, -1, -2, 0);

			// pelunasan
			$this->pelunasan->EditAttrs["class"] = "form-control";
			$this->pelunasan->EditCustomAttributes = "";
			$this->pelunasan->EditValue = ew_HtmlEncode($this->pelunasan->CurrentValue);
			$this->pelunasan->PlaceHolder = ew_RemoveHtml($this->pelunasan->FldCaption());
			if (strval($this->pelunasan->EditValue) <> "" && is_numeric($this->pelunasan->EditValue)) $this->pelunasan->EditValue = ew_FormatNumber($this->pelunasan->EditValue, -2, -1, -2, 0);

			// pelunasanauto
			$this->pelunasanauto->EditAttrs["class"] = "form-control";
			$this->pelunasanauto->EditCustomAttributes = "";
			$this->pelunasanauto->EditValue = ew_HtmlEncode($this->pelunasanauto->CurrentValue);
			$this->pelunasanauto->PlaceHolder = ew_RemoveHtml($this->pelunasanauto->FldCaption());
			if (strval($this->pelunasanauto->EditValue) <> "" && is_numeric($this->pelunasanauto->EditValue)) $this->pelunasanauto->EditValue = ew_FormatNumber($this->pelunasanauto->EditValue, -2, -1, -2, 0);

			// finalty
			$this->finalty->EditAttrs["class"] = "form-control";
			$this->finalty->EditCustomAttributes = "";
			$this->finalty->EditValue = ew_HtmlEncode($this->finalty->CurrentValue);
			$this->finalty->PlaceHolder = ew_RemoveHtml($this->finalty->FldCaption());
			if (strval($this->finalty->EditValue) <> "" && is_numeric($this->finalty->EditValue)) $this->finalty->EditValue = ew_FormatNumber($this->finalty->EditValue, -2, -1, -2, 0);

			// finaltyauto
			$this->finaltyauto->EditAttrs["class"] = "form-control";
			$this->finaltyauto->EditCustomAttributes = "";
			$this->finaltyauto->EditValue = ew_HtmlEncode($this->finaltyauto->CurrentValue);
			$this->finaltyauto->PlaceHolder = ew_RemoveHtml($this->finaltyauto->FldCaption());
			if (strval($this->finaltyauto->EditValue) <> "" && is_numeric($this->finaltyauto->EditValue)) $this->finaltyauto->EditValue = ew_FormatNumber($this->finaltyauto->EditValue, -2, -1, -2, 0);

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

			// terlambat
			$this->terlambat->LinkCustomAttributes = "";
			$this->terlambat->HrefValue = "";

			// dispensasidenda
			$this->dispensasidenda->LinkCustomAttributes = "";
			$this->dispensasidenda->HrefValue = "";

			// plafond
			$this->plafond->LinkCustomAttributes = "";
			$this->plafond->HrefValue = "";

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

			// sisaangsuran
			$this->sisaangsuran->LinkCustomAttributes = "";
			$this->sisaangsuran->HrefValue = "";

			// sisaangsuranauto
			$this->sisaangsuranauto->LinkCustomAttributes = "";
			$this->sisaangsuranauto->HrefValue = "";

			// saldotitipan
			$this->saldotitipan->LinkCustomAttributes = "";
			$this->saldotitipan->HrefValue = "";

			// saldotitipansisa
			$this->saldotitipansisa->LinkCustomAttributes = "";
			$this->saldotitipansisa->HrefValue = "";

			// bayarpokok
			$this->bayarpokok->LinkCustomAttributes = "";
			$this->bayarpokok->HrefValue = "";

			// bayarpokokauto
			$this->bayarpokokauto->LinkCustomAttributes = "";
			$this->bayarpokokauto->HrefValue = "";

			// bayarbunga
			$this->bayarbunga->LinkCustomAttributes = "";
			$this->bayarbunga->HrefValue = "";

			// bayarbungaauto
			$this->bayarbungaauto->LinkCustomAttributes = "";
			$this->bayarbungaauto->HrefValue = "";

			// bayardenda
			$this->bayardenda->LinkCustomAttributes = "";
			$this->bayardenda->HrefValue = "";

			// bayardendaauto
			$this->bayardendaauto->LinkCustomAttributes = "";
			$this->bayardendaauto->HrefValue = "";

			// bayartitipan
			$this->bayartitipan->LinkCustomAttributes = "";
			$this->bayartitipan->HrefValue = "";

			// bayartitipanauto
			$this->bayartitipanauto->LinkCustomAttributes = "";
			$this->bayartitipanauto->HrefValue = "";

			// totalbayar
			$this->totalbayar->LinkCustomAttributes = "";
			$this->totalbayar->HrefValue = "";

			// totalbayarauto
			$this->totalbayarauto->LinkCustomAttributes = "";
			$this->totalbayarauto->HrefValue = "";

			// pelunasan
			$this->pelunasan->LinkCustomAttributes = "";
			$this->pelunasan->HrefValue = "";

			// pelunasanauto
			$this->pelunasanauto->LinkCustomAttributes = "";
			$this->pelunasanauto->HrefValue = "";

			// finalty
			$this->finalty->LinkCustomAttributes = "";
			$this->finalty->HrefValue = "";

			// finaltyauto
			$this->finaltyauto->LinkCustomAttributes = "";
			$this->finaltyauto->HrefValue = "";

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
		if (!$this->terlambat->FldIsDetailKey && !is_null($this->terlambat->FormValue) && $this->terlambat->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->terlambat->FldCaption(), $this->terlambat->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->terlambat->FormValue)) {
			ew_AddMessage($gsFormError, $this->terlambat->FldErrMsg());
		}
		if (!$this->dispensasidenda->FldIsDetailKey && !is_null($this->dispensasidenda->FormValue) && $this->dispensasidenda->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->dispensasidenda->FldCaption(), $this->dispensasidenda->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->dispensasidenda->FormValue)) {
			ew_AddMessage($gsFormError, $this->dispensasidenda->FldErrMsg());
		}
		if (!$this->plafond->FldIsDetailKey && !is_null($this->plafond->FormValue) && $this->plafond->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->plafond->FldCaption(), $this->plafond->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->plafond->FormValue)) {
			ew_AddMessage($gsFormError, $this->plafond->FldErrMsg());
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
		if (!$this->sisaangsuran->FldIsDetailKey && !is_null($this->sisaangsuran->FormValue) && $this->sisaangsuran->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->sisaangsuran->FldCaption(), $this->sisaangsuran->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->sisaangsuran->FormValue)) {
			ew_AddMessage($gsFormError, $this->sisaangsuran->FldErrMsg());
		}
		if (!$this->sisaangsuranauto->FldIsDetailKey && !is_null($this->sisaangsuranauto->FormValue) && $this->sisaangsuranauto->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->sisaangsuranauto->FldCaption(), $this->sisaangsuranauto->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->sisaangsuranauto->FormValue)) {
			ew_AddMessage($gsFormError, $this->sisaangsuranauto->FldErrMsg());
		}
		if (!$this->saldotitipan->FldIsDetailKey && !is_null($this->saldotitipan->FormValue) && $this->saldotitipan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->saldotitipan->FldCaption(), $this->saldotitipan->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->saldotitipan->FormValue)) {
			ew_AddMessage($gsFormError, $this->saldotitipan->FldErrMsg());
		}
		if (!$this->saldotitipansisa->FldIsDetailKey && !is_null($this->saldotitipansisa->FormValue) && $this->saldotitipansisa->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->saldotitipansisa->FldCaption(), $this->saldotitipansisa->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->saldotitipansisa->FormValue)) {
			ew_AddMessage($gsFormError, $this->saldotitipansisa->FldErrMsg());
		}
		if (!$this->bayarpokok->FldIsDetailKey && !is_null($this->bayarpokok->FormValue) && $this->bayarpokok->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->bayarpokok->FldCaption(), $this->bayarpokok->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->bayarpokok->FormValue)) {
			ew_AddMessage($gsFormError, $this->bayarpokok->FldErrMsg());
		}
		if (!$this->bayarpokokauto->FldIsDetailKey && !is_null($this->bayarpokokauto->FormValue) && $this->bayarpokokauto->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->bayarpokokauto->FldCaption(), $this->bayarpokokauto->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->bayarpokokauto->FormValue)) {
			ew_AddMessage($gsFormError, $this->bayarpokokauto->FldErrMsg());
		}
		if (!$this->bayarbunga->FldIsDetailKey && !is_null($this->bayarbunga->FormValue) && $this->bayarbunga->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->bayarbunga->FldCaption(), $this->bayarbunga->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->bayarbunga->FormValue)) {
			ew_AddMessage($gsFormError, $this->bayarbunga->FldErrMsg());
		}
		if (!$this->bayarbungaauto->FldIsDetailKey && !is_null($this->bayarbungaauto->FormValue) && $this->bayarbungaauto->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->bayarbungaauto->FldCaption(), $this->bayarbungaauto->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->bayarbungaauto->FormValue)) {
			ew_AddMessage($gsFormError, $this->bayarbungaauto->FldErrMsg());
		}
		if (!$this->bayardenda->FldIsDetailKey && !is_null($this->bayardenda->FormValue) && $this->bayardenda->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->bayardenda->FldCaption(), $this->bayardenda->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->bayardenda->FormValue)) {
			ew_AddMessage($gsFormError, $this->bayardenda->FldErrMsg());
		}
		if (!$this->bayardendaauto->FldIsDetailKey && !is_null($this->bayardendaauto->FormValue) && $this->bayardendaauto->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->bayardendaauto->FldCaption(), $this->bayardendaauto->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->bayardendaauto->FormValue)) {
			ew_AddMessage($gsFormError, $this->bayardendaauto->FldErrMsg());
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
		if (!$this->totalbayar->FldIsDetailKey && !is_null($this->totalbayar->FormValue) && $this->totalbayar->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->totalbayar->FldCaption(), $this->totalbayar->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->totalbayar->FormValue)) {
			ew_AddMessage($gsFormError, $this->totalbayar->FldErrMsg());
		}
		if (!$this->totalbayarauto->FldIsDetailKey && !is_null($this->totalbayarauto->FormValue) && $this->totalbayarauto->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->totalbayarauto->FldCaption(), $this->totalbayarauto->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->totalbayarauto->FormValue)) {
			ew_AddMessage($gsFormError, $this->totalbayarauto->FldErrMsg());
		}
		if (!$this->pelunasan->FldIsDetailKey && !is_null($this->pelunasan->FormValue) && $this->pelunasan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pelunasan->FldCaption(), $this->pelunasan->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->pelunasan->FormValue)) {
			ew_AddMessage($gsFormError, $this->pelunasan->FldErrMsg());
		}
		if (!$this->pelunasanauto->FldIsDetailKey && !is_null($this->pelunasanauto->FormValue) && $this->pelunasanauto->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pelunasanauto->FldCaption(), $this->pelunasanauto->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->pelunasanauto->FormValue)) {
			ew_AddMessage($gsFormError, $this->pelunasanauto->FldErrMsg());
		}
		if (!$this->finalty->FldIsDetailKey && !is_null($this->finalty->FormValue) && $this->finalty->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->finalty->FldCaption(), $this->finalty->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->finalty->FormValue)) {
			ew_AddMessage($gsFormError, $this->finalty->FldErrMsg());
		}
		if (!$this->finaltyauto->FldIsDetailKey && !is_null($this->finaltyauto->FormValue) && $this->finaltyauto->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->finaltyauto->FldCaption(), $this->finaltyauto->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->finaltyauto->FormValue)) {
			ew_AddMessage($gsFormError, $this->finaltyauto->FldErrMsg());
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

		// terlambat
		$this->terlambat->SetDbValueDef($rsnew, $this->terlambat->CurrentValue, 0, strval($this->terlambat->CurrentValue) == "");

		// dispensasidenda
		$this->dispensasidenda->SetDbValueDef($rsnew, $this->dispensasidenda->CurrentValue, 0, strval($this->dispensasidenda->CurrentValue) == "");

		// plafond
		$this->plafond->SetDbValueDef($rsnew, $this->plafond->CurrentValue, 0, strval($this->plafond->CurrentValue) == "");

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

		// sisaangsuran
		$this->sisaangsuran->SetDbValueDef($rsnew, $this->sisaangsuran->CurrentValue, 0, strval($this->sisaangsuran->CurrentValue) == "");

		// sisaangsuranauto
		$this->sisaangsuranauto->SetDbValueDef($rsnew, $this->sisaangsuranauto->CurrentValue, 0, strval($this->sisaangsuranauto->CurrentValue) == "");

		// saldotitipan
		$this->saldotitipan->SetDbValueDef($rsnew, $this->saldotitipan->CurrentValue, 0, strval($this->saldotitipan->CurrentValue) == "");

		// saldotitipansisa
		$this->saldotitipansisa->SetDbValueDef($rsnew, $this->saldotitipansisa->CurrentValue, 0, strval($this->saldotitipansisa->CurrentValue) == "");

		// bayarpokok
		$this->bayarpokok->SetDbValueDef($rsnew, $this->bayarpokok->CurrentValue, 0, strval($this->bayarpokok->CurrentValue) == "");

		// bayarpokokauto
		$this->bayarpokokauto->SetDbValueDef($rsnew, $this->bayarpokokauto->CurrentValue, 0, strval($this->bayarpokokauto->CurrentValue) == "");

		// bayarbunga
		$this->bayarbunga->SetDbValueDef($rsnew, $this->bayarbunga->CurrentValue, 0, strval($this->bayarbunga->CurrentValue) == "");

		// bayarbungaauto
		$this->bayarbungaauto->SetDbValueDef($rsnew, $this->bayarbungaauto->CurrentValue, 0, strval($this->bayarbungaauto->CurrentValue) == "");

		// bayardenda
		$this->bayardenda->SetDbValueDef($rsnew, $this->bayardenda->CurrentValue, 0, strval($this->bayardenda->CurrentValue) == "");

		// bayardendaauto
		$this->bayardendaauto->SetDbValueDef($rsnew, $this->bayardendaauto->CurrentValue, 0, strval($this->bayardendaauto->CurrentValue) == "");

		// bayartitipan
		$this->bayartitipan->SetDbValueDef($rsnew, $this->bayartitipan->CurrentValue, 0, strval($this->bayartitipan->CurrentValue) == "");

		// bayartitipanauto
		$this->bayartitipanauto->SetDbValueDef($rsnew, $this->bayartitipanauto->CurrentValue, 0, strval($this->bayartitipanauto->CurrentValue) == "");

		// totalbayar
		$this->totalbayar->SetDbValueDef($rsnew, $this->totalbayar->CurrentValue, 0, strval($this->totalbayar->CurrentValue) == "");

		// totalbayarauto
		$this->totalbayarauto->SetDbValueDef($rsnew, $this->totalbayarauto->CurrentValue, 0, strval($this->totalbayarauto->CurrentValue) == "");

		// pelunasan
		$this->pelunasan->SetDbValueDef($rsnew, $this->pelunasan->CurrentValue, 0, strval($this->pelunasan->CurrentValue) == "");

		// pelunasanauto
		$this->pelunasanauto->SetDbValueDef($rsnew, $this->pelunasanauto->CurrentValue, 0, strval($this->pelunasanauto->CurrentValue) == "");

		// finalty
		$this->finalty->SetDbValueDef($rsnew, $this->finalty->CurrentValue, 0, strval($this->finalty->CurrentValue) == "");

		// finaltyauto
		$this->finaltyauto->SetDbValueDef($rsnew, $this->finaltyauto->CurrentValue, 0, strval($this->finaltyauto->CurrentValue) == "");

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

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['angsuran']) == "") {
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("tbayarangsuranlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($tbayarangsuran_add)) $tbayarangsuran_add = new ctbayarangsuran_add();

// Page init
$tbayarangsuran_add->Page_Init();

// Page main
$tbayarangsuran_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tbayarangsuran_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = ftbayarangsuranadd = new ew_Form("ftbayarangsuranadd", "add");

// Validate form
ftbayarangsuranadd.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->tanggal->FldCaption(), $tbayarangsuran->tanggal->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tanggal");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->tanggal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_periode");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->periode->FldCaption(), $tbayarangsuran->periode->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->id->FldCaption(), $tbayarangsuran->id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_transaksi");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->transaksi->FldCaption(), $tbayarangsuran->transaksi->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_referensi");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->referensi->FldCaption(), $tbayarangsuran->referensi->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_anggota");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->anggota->FldCaption(), $tbayarangsuran->anggota->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_namaanggota");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->namaanggota->FldCaption(), $tbayarangsuran->namaanggota->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_alamat");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->alamat->FldCaption(), $tbayarangsuran->alamat->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pekerjaan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->pekerjaan->FldCaption(), $tbayarangsuran->pekerjaan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_telepon");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->telepon->FldCaption(), $tbayarangsuran->telepon->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_hp");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->hp->FldCaption(), $tbayarangsuran->hp->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fax");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->fax->FldCaption(), $tbayarangsuran->fax->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__email");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->_email->FldCaption(), $tbayarangsuran->_email->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_website");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->website->FldCaption(), $tbayarangsuran->website->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jenisanggota");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->jenisanggota->FldCaption(), $tbayarangsuran->jenisanggota->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_model");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->model->FldCaption(), $tbayarangsuran->model->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jenispinjaman");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->jenispinjaman->FldCaption(), $tbayarangsuran->jenispinjaman->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jenisbunga");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->jenisbunga->FldCaption(), $tbayarangsuran->jenisbunga->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_angsuran");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->angsuran->FldCaption(), $tbayarangsuran->angsuran->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_angsuran");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->angsuran->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_masaangsuran");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->masaangsuran->FldCaption(), $tbayarangsuran->masaangsuran->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jatuhtempo");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->jatuhtempo->FldCaption(), $tbayarangsuran->jatuhtempo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jatuhtempo");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->jatuhtempo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_terlambat");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->terlambat->FldCaption(), $tbayarangsuran->terlambat->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_terlambat");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->terlambat->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dispensasidenda");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->dispensasidenda->FldCaption(), $tbayarangsuran->dispensasidenda->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dispensasidenda");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->dispensasidenda->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_plafond");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->plafond->FldCaption(), $tbayarangsuran->plafond->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_plafond");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->plafond->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_angsuranpokok");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->angsuranpokok->FldCaption(), $tbayarangsuran->angsuranpokok->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_angsuranpokok");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->angsuranpokok->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_angsuranpokokauto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->angsuranpokokauto->FldCaption(), $tbayarangsuran->angsuranpokokauto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_angsuranpokokauto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->angsuranpokokauto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_angsuranbunga");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->angsuranbunga->FldCaption(), $tbayarangsuran->angsuranbunga->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_angsuranbunga");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->angsuranbunga->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_angsuranbungaauto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->angsuranbungaauto->FldCaption(), $tbayarangsuran->angsuranbungaauto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_angsuranbungaauto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->angsuranbungaauto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_denda");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->denda->FldCaption(), $tbayarangsuran->denda->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_denda");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->denda->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dendapersen");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->dendapersen->FldCaption(), $tbayarangsuran->dendapersen->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dendapersen");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->dendapersen->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_totalangsuran");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->totalangsuran->FldCaption(), $tbayarangsuran->totalangsuran->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_totalangsuran");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->totalangsuran->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_totalangsuranauto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->totalangsuranauto->FldCaption(), $tbayarangsuran->totalangsuranauto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_totalangsuranauto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->totalangsuranauto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_sisaangsuran");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->sisaangsuran->FldCaption(), $tbayarangsuran->sisaangsuran->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_sisaangsuran");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->sisaangsuran->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_sisaangsuranauto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->sisaangsuranauto->FldCaption(), $tbayarangsuran->sisaangsuranauto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_sisaangsuranauto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->sisaangsuranauto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_saldotitipan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->saldotitipan->FldCaption(), $tbayarangsuran->saldotitipan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_saldotitipan");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->saldotitipan->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_saldotitipansisa");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->saldotitipansisa->FldCaption(), $tbayarangsuran->saldotitipansisa->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_saldotitipansisa");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->saldotitipansisa->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_bayarpokok");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->bayarpokok->FldCaption(), $tbayarangsuran->bayarpokok->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bayarpokok");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->bayarpokok->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_bayarpokokauto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->bayarpokokauto->FldCaption(), $tbayarangsuran->bayarpokokauto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bayarpokokauto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->bayarpokokauto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_bayarbunga");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->bayarbunga->FldCaption(), $tbayarangsuran->bayarbunga->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bayarbunga");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->bayarbunga->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_bayarbungaauto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->bayarbungaauto->FldCaption(), $tbayarangsuran->bayarbungaauto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bayarbungaauto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->bayarbungaauto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_bayardenda");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->bayardenda->FldCaption(), $tbayarangsuran->bayardenda->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bayardenda");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->bayardenda->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_bayardendaauto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->bayardendaauto->FldCaption(), $tbayarangsuran->bayardendaauto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bayardendaauto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->bayardendaauto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_bayartitipan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->bayartitipan->FldCaption(), $tbayarangsuran->bayartitipan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bayartitipan");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->bayartitipan->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_bayartitipanauto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->bayartitipanauto->FldCaption(), $tbayarangsuran->bayartitipanauto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bayartitipanauto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->bayartitipanauto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_totalbayar");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->totalbayar->FldCaption(), $tbayarangsuran->totalbayar->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_totalbayar");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->totalbayar->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_totalbayarauto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->totalbayarauto->FldCaption(), $tbayarangsuran->totalbayarauto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_totalbayarauto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->totalbayarauto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_pelunasan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->pelunasan->FldCaption(), $tbayarangsuran->pelunasan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pelunasan");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->pelunasan->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_pelunasanauto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->pelunasanauto->FldCaption(), $tbayarangsuran->pelunasanauto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pelunasanauto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->pelunasanauto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_finalty");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->finalty->FldCaption(), $tbayarangsuran->finalty->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_finalty");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->finalty->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_finaltyauto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->finaltyauto->FldCaption(), $tbayarangsuran->finaltyauto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_finaltyauto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->finaltyauto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_terbilang");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->terbilang->FldCaption(), $tbayarangsuran->terbilang->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_petugas");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->petugas->FldCaption(), $tbayarangsuran->petugas->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pembayaran");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->pembayaran->FldCaption(), $tbayarangsuran->pembayaran->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bank");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->bank->FldCaption(), $tbayarangsuran->bank->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_atasnama");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->atasnama->FldCaption(), $tbayarangsuran->atasnama->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tipe");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->tipe->FldCaption(), $tbayarangsuran->tipe->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_kantor");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->kantor->FldCaption(), $tbayarangsuran->kantor->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_keterangan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->keterangan->FldCaption(), $tbayarangsuran->keterangan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_active");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->active->FldCaption(), $tbayarangsuran->active->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ip");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->ip->FldCaption(), $tbayarangsuran->ip->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->status->FldCaption(), $tbayarangsuran->status->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_user");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->user->FldCaption(), $tbayarangsuran->user->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_created");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->created->FldCaption(), $tbayarangsuran->created->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_created");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->created->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modified");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tbayarangsuran->modified->FldCaption(), $tbayarangsuran->modified->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_modified");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbayarangsuran->modified->FldErrMsg()) ?>");

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
ftbayarangsuranadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbayarangsuranadd.ValidateRequired = true;
<?php } else { ?>
ftbayarangsuranadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftbayarangsuranadd.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftbayarangsuranadd.Lists["x_active"].Options = <?php echo json_encode($tbayarangsuran->active->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$tbayarangsuran_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $tbayarangsuran_add->ShowPageHeader(); ?>
<?php
$tbayarangsuran_add->ShowMessage();
?>
<form name="ftbayarangsuranadd" id="ftbayarangsuranadd" class="<?php echo $tbayarangsuran_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tbayarangsuran_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tbayarangsuran_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tbayarangsuran">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($tbayarangsuran_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($tbayarangsuran->tanggal->Visible) { // tanggal ?>
	<div id="r_tanggal" class="form-group">
		<label id="elh_tbayarangsuran_tanggal" for="x_tanggal" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->tanggal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->tanggal->CellAttributes() ?>>
<span id="el_tbayarangsuran_tanggal">
<input type="text" data-table="tbayarangsuran" data-field="x_tanggal" name="x_tanggal" id="x_tanggal" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->tanggal->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->tanggal->EditValue ?>"<?php echo $tbayarangsuran->tanggal->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->tanggal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->periode->Visible) { // periode ?>
	<div id="r_periode" class="form-group">
		<label id="elh_tbayarangsuran_periode" for="x_periode" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->periode->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->periode->CellAttributes() ?>>
<span id="el_tbayarangsuran_periode">
<input type="text" data-table="tbayarangsuran" data-field="x_periode" name="x_periode" id="x_periode" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->periode->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->periode->EditValue ?>"<?php echo $tbayarangsuran->periode->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->periode->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_tbayarangsuran_id" for="x_id" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->id->CellAttributes() ?>>
<span id="el_tbayarangsuran_id">
<input type="text" data-table="tbayarangsuran" data-field="x_id" name="x_id" id="x_id" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->id->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->id->EditValue ?>"<?php echo $tbayarangsuran->id->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->transaksi->Visible) { // transaksi ?>
	<div id="r_transaksi" class="form-group">
		<label id="elh_tbayarangsuran_transaksi" for="x_transaksi" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->transaksi->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->transaksi->CellAttributes() ?>>
<span id="el_tbayarangsuran_transaksi">
<input type="text" data-table="tbayarangsuran" data-field="x_transaksi" name="x_transaksi" id="x_transaksi" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->transaksi->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->transaksi->EditValue ?>"<?php echo $tbayarangsuran->transaksi->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->transaksi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->referensi->Visible) { // referensi ?>
	<div id="r_referensi" class="form-group">
		<label id="elh_tbayarangsuran_referensi" for="x_referensi" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->referensi->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->referensi->CellAttributes() ?>>
<span id="el_tbayarangsuran_referensi">
<input type="text" data-table="tbayarangsuran" data-field="x_referensi" name="x_referensi" id="x_referensi" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->referensi->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->referensi->EditValue ?>"<?php echo $tbayarangsuran->referensi->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->referensi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->anggota->Visible) { // anggota ?>
	<div id="r_anggota" class="form-group">
		<label id="elh_tbayarangsuran_anggota" for="x_anggota" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->anggota->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->anggota->CellAttributes() ?>>
<span id="el_tbayarangsuran_anggota">
<input type="text" data-table="tbayarangsuran" data-field="x_anggota" name="x_anggota" id="x_anggota" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->anggota->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->anggota->EditValue ?>"<?php echo $tbayarangsuran->anggota->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->anggota->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->namaanggota->Visible) { // namaanggota ?>
	<div id="r_namaanggota" class="form-group">
		<label id="elh_tbayarangsuran_namaanggota" for="x_namaanggota" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->namaanggota->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->namaanggota->CellAttributes() ?>>
<span id="el_tbayarangsuran_namaanggota">
<input type="text" data-table="tbayarangsuran" data-field="x_namaanggota" name="x_namaanggota" id="x_namaanggota" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->namaanggota->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->namaanggota->EditValue ?>"<?php echo $tbayarangsuran->namaanggota->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->namaanggota->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->alamat->Visible) { // alamat ?>
	<div id="r_alamat" class="form-group">
		<label id="elh_tbayarangsuran_alamat" for="x_alamat" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->alamat->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->alamat->CellAttributes() ?>>
<span id="el_tbayarangsuran_alamat">
<input type="text" data-table="tbayarangsuran" data-field="x_alamat" name="x_alamat" id="x_alamat" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->alamat->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->alamat->EditValue ?>"<?php echo $tbayarangsuran->alamat->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->alamat->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->pekerjaan->Visible) { // pekerjaan ?>
	<div id="r_pekerjaan" class="form-group">
		<label id="elh_tbayarangsuran_pekerjaan" for="x_pekerjaan" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->pekerjaan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->pekerjaan->CellAttributes() ?>>
<span id="el_tbayarangsuran_pekerjaan">
<input type="text" data-table="tbayarangsuran" data-field="x_pekerjaan" name="x_pekerjaan" id="x_pekerjaan" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->pekerjaan->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->pekerjaan->EditValue ?>"<?php echo $tbayarangsuran->pekerjaan->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->pekerjaan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->telepon->Visible) { // telepon ?>
	<div id="r_telepon" class="form-group">
		<label id="elh_tbayarangsuran_telepon" for="x_telepon" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->telepon->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->telepon->CellAttributes() ?>>
<span id="el_tbayarangsuran_telepon">
<input type="text" data-table="tbayarangsuran" data-field="x_telepon" name="x_telepon" id="x_telepon" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->telepon->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->telepon->EditValue ?>"<?php echo $tbayarangsuran->telepon->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->telepon->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->hp->Visible) { // hp ?>
	<div id="r_hp" class="form-group">
		<label id="elh_tbayarangsuran_hp" for="x_hp" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->hp->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->hp->CellAttributes() ?>>
<span id="el_tbayarangsuran_hp">
<input type="text" data-table="tbayarangsuran" data-field="x_hp" name="x_hp" id="x_hp" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->hp->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->hp->EditValue ?>"<?php echo $tbayarangsuran->hp->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->hp->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->fax->Visible) { // fax ?>
	<div id="r_fax" class="form-group">
		<label id="elh_tbayarangsuran_fax" for="x_fax" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->fax->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->fax->CellAttributes() ?>>
<span id="el_tbayarangsuran_fax">
<input type="text" data-table="tbayarangsuran" data-field="x_fax" name="x_fax" id="x_fax" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->fax->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->fax->EditValue ?>"<?php echo $tbayarangsuran->fax->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->fax->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_tbayarangsuran__email" for="x__email" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->_email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->_email->CellAttributes() ?>>
<span id="el_tbayarangsuran__email">
<input type="text" data-table="tbayarangsuran" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->_email->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->_email->EditValue ?>"<?php echo $tbayarangsuran->_email->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->website->Visible) { // website ?>
	<div id="r_website" class="form-group">
		<label id="elh_tbayarangsuran_website" for="x_website" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->website->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->website->CellAttributes() ?>>
<span id="el_tbayarangsuran_website">
<input type="text" data-table="tbayarangsuran" data-field="x_website" name="x_website" id="x_website" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->website->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->website->EditValue ?>"<?php echo $tbayarangsuran->website->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->website->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->jenisanggota->Visible) { // jenisanggota ?>
	<div id="r_jenisanggota" class="form-group">
		<label id="elh_tbayarangsuran_jenisanggota" for="x_jenisanggota" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->jenisanggota->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->jenisanggota->CellAttributes() ?>>
<span id="el_tbayarangsuran_jenisanggota">
<input type="text" data-table="tbayarangsuran" data-field="x_jenisanggota" name="x_jenisanggota" id="x_jenisanggota" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->jenisanggota->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->jenisanggota->EditValue ?>"<?php echo $tbayarangsuran->jenisanggota->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->jenisanggota->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->model->Visible) { // model ?>
	<div id="r_model" class="form-group">
		<label id="elh_tbayarangsuran_model" for="x_model" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->model->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->model->CellAttributes() ?>>
<span id="el_tbayarangsuran_model">
<input type="text" data-table="tbayarangsuran" data-field="x_model" name="x_model" id="x_model" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->model->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->model->EditValue ?>"<?php echo $tbayarangsuran->model->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->model->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->jenispinjaman->Visible) { // jenispinjaman ?>
	<div id="r_jenispinjaman" class="form-group">
		<label id="elh_tbayarangsuran_jenispinjaman" for="x_jenispinjaman" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->jenispinjaman->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->jenispinjaman->CellAttributes() ?>>
<span id="el_tbayarangsuran_jenispinjaman">
<input type="text" data-table="tbayarangsuran" data-field="x_jenispinjaman" name="x_jenispinjaman" id="x_jenispinjaman" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->jenispinjaman->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->jenispinjaman->EditValue ?>"<?php echo $tbayarangsuran->jenispinjaman->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->jenispinjaman->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->jenisbunga->Visible) { // jenisbunga ?>
	<div id="r_jenisbunga" class="form-group">
		<label id="elh_tbayarangsuran_jenisbunga" for="x_jenisbunga" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->jenisbunga->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->jenisbunga->CellAttributes() ?>>
<span id="el_tbayarangsuran_jenisbunga">
<input type="text" data-table="tbayarangsuran" data-field="x_jenisbunga" name="x_jenisbunga" id="x_jenisbunga" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->jenisbunga->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->jenisbunga->EditValue ?>"<?php echo $tbayarangsuran->jenisbunga->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->jenisbunga->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->angsuran->Visible) { // angsuran ?>
	<div id="r_angsuran" class="form-group">
		<label id="elh_tbayarangsuran_angsuran" for="x_angsuran" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->angsuran->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->angsuran->CellAttributes() ?>>
<span id="el_tbayarangsuran_angsuran">
<input type="text" data-table="tbayarangsuran" data-field="x_angsuran" name="x_angsuran" id="x_angsuran" size="30" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->angsuran->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->angsuran->EditValue ?>"<?php echo $tbayarangsuran->angsuran->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->angsuran->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->masaangsuran->Visible) { // masaangsuran ?>
	<div id="r_masaangsuran" class="form-group">
		<label id="elh_tbayarangsuran_masaangsuran" for="x_masaangsuran" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->masaangsuran->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->masaangsuran->CellAttributes() ?>>
<span id="el_tbayarangsuran_masaangsuran">
<input type="text" data-table="tbayarangsuran" data-field="x_masaangsuran" name="x_masaangsuran" id="x_masaangsuran" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->masaangsuran->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->masaangsuran->EditValue ?>"<?php echo $tbayarangsuran->masaangsuran->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->masaangsuran->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->jatuhtempo->Visible) { // jatuhtempo ?>
	<div id="r_jatuhtempo" class="form-group">
		<label id="elh_tbayarangsuran_jatuhtempo" for="x_jatuhtempo" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->jatuhtempo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->jatuhtempo->CellAttributes() ?>>
<span id="el_tbayarangsuran_jatuhtempo">
<input type="text" data-table="tbayarangsuran" data-field="x_jatuhtempo" name="x_jatuhtempo" id="x_jatuhtempo" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->jatuhtempo->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->jatuhtempo->EditValue ?>"<?php echo $tbayarangsuran->jatuhtempo->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->jatuhtempo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->terlambat->Visible) { // terlambat ?>
	<div id="r_terlambat" class="form-group">
		<label id="elh_tbayarangsuran_terlambat" for="x_terlambat" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->terlambat->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->terlambat->CellAttributes() ?>>
<span id="el_tbayarangsuran_terlambat">
<input type="text" data-table="tbayarangsuran" data-field="x_terlambat" name="x_terlambat" id="x_terlambat" size="30" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->terlambat->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->terlambat->EditValue ?>"<?php echo $tbayarangsuran->terlambat->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->terlambat->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->dispensasidenda->Visible) { // dispensasidenda ?>
	<div id="r_dispensasidenda" class="form-group">
		<label id="elh_tbayarangsuran_dispensasidenda" for="x_dispensasidenda" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->dispensasidenda->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->dispensasidenda->CellAttributes() ?>>
<span id="el_tbayarangsuran_dispensasidenda">
<input type="text" data-table="tbayarangsuran" data-field="x_dispensasidenda" name="x_dispensasidenda" id="x_dispensasidenda" size="30" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->dispensasidenda->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->dispensasidenda->EditValue ?>"<?php echo $tbayarangsuran->dispensasidenda->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->dispensasidenda->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->plafond->Visible) { // plafond ?>
	<div id="r_plafond" class="form-group">
		<label id="elh_tbayarangsuran_plafond" for="x_plafond" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->plafond->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->plafond->CellAttributes() ?>>
<span id="el_tbayarangsuran_plafond">
<input type="text" data-table="tbayarangsuran" data-field="x_plafond" name="x_plafond" id="x_plafond" size="30" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->plafond->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->plafond->EditValue ?>"<?php echo $tbayarangsuran->plafond->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->plafond->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->angsuranpokok->Visible) { // angsuranpokok ?>
	<div id="r_angsuranpokok" class="form-group">
		<label id="elh_tbayarangsuran_angsuranpokok" for="x_angsuranpokok" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->angsuranpokok->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->angsuranpokok->CellAttributes() ?>>
<span id="el_tbayarangsuran_angsuranpokok">
<input type="text" data-table="tbayarangsuran" data-field="x_angsuranpokok" name="x_angsuranpokok" id="x_angsuranpokok" size="30" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->angsuranpokok->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->angsuranpokok->EditValue ?>"<?php echo $tbayarangsuran->angsuranpokok->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->angsuranpokok->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->angsuranpokokauto->Visible) { // angsuranpokokauto ?>
	<div id="r_angsuranpokokauto" class="form-group">
		<label id="elh_tbayarangsuran_angsuranpokokauto" for="x_angsuranpokokauto" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->angsuranpokokauto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->angsuranpokokauto->CellAttributes() ?>>
<span id="el_tbayarangsuran_angsuranpokokauto">
<input type="text" data-table="tbayarangsuran" data-field="x_angsuranpokokauto" name="x_angsuranpokokauto" id="x_angsuranpokokauto" size="30" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->angsuranpokokauto->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->angsuranpokokauto->EditValue ?>"<?php echo $tbayarangsuran->angsuranpokokauto->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->angsuranpokokauto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->angsuranbunga->Visible) { // angsuranbunga ?>
	<div id="r_angsuranbunga" class="form-group">
		<label id="elh_tbayarangsuran_angsuranbunga" for="x_angsuranbunga" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->angsuranbunga->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->angsuranbunga->CellAttributes() ?>>
<span id="el_tbayarangsuran_angsuranbunga">
<input type="text" data-table="tbayarangsuran" data-field="x_angsuranbunga" name="x_angsuranbunga" id="x_angsuranbunga" size="30" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->angsuranbunga->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->angsuranbunga->EditValue ?>"<?php echo $tbayarangsuran->angsuranbunga->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->angsuranbunga->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->angsuranbungaauto->Visible) { // angsuranbungaauto ?>
	<div id="r_angsuranbungaauto" class="form-group">
		<label id="elh_tbayarangsuran_angsuranbungaauto" for="x_angsuranbungaauto" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->angsuranbungaauto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->angsuranbungaauto->CellAttributes() ?>>
<span id="el_tbayarangsuran_angsuranbungaauto">
<input type="text" data-table="tbayarangsuran" data-field="x_angsuranbungaauto" name="x_angsuranbungaauto" id="x_angsuranbungaauto" size="30" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->angsuranbungaauto->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->angsuranbungaauto->EditValue ?>"<?php echo $tbayarangsuran->angsuranbungaauto->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->angsuranbungaauto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->denda->Visible) { // denda ?>
	<div id="r_denda" class="form-group">
		<label id="elh_tbayarangsuran_denda" for="x_denda" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->denda->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->denda->CellAttributes() ?>>
<span id="el_tbayarangsuran_denda">
<input type="text" data-table="tbayarangsuran" data-field="x_denda" name="x_denda" id="x_denda" size="30" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->denda->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->denda->EditValue ?>"<?php echo $tbayarangsuran->denda->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->denda->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->dendapersen->Visible) { // dendapersen ?>
	<div id="r_dendapersen" class="form-group">
		<label id="elh_tbayarangsuran_dendapersen" for="x_dendapersen" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->dendapersen->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->dendapersen->CellAttributes() ?>>
<span id="el_tbayarangsuran_dendapersen">
<input type="text" data-table="tbayarangsuran" data-field="x_dendapersen" name="x_dendapersen" id="x_dendapersen" size="30" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->dendapersen->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->dendapersen->EditValue ?>"<?php echo $tbayarangsuran->dendapersen->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->dendapersen->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->totalangsuran->Visible) { // totalangsuran ?>
	<div id="r_totalangsuran" class="form-group">
		<label id="elh_tbayarangsuran_totalangsuran" for="x_totalangsuran" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->totalangsuran->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->totalangsuran->CellAttributes() ?>>
<span id="el_tbayarangsuran_totalangsuran">
<input type="text" data-table="tbayarangsuran" data-field="x_totalangsuran" name="x_totalangsuran" id="x_totalangsuran" size="30" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->totalangsuran->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->totalangsuran->EditValue ?>"<?php echo $tbayarangsuran->totalangsuran->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->totalangsuran->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->totalangsuranauto->Visible) { // totalangsuranauto ?>
	<div id="r_totalangsuranauto" class="form-group">
		<label id="elh_tbayarangsuran_totalangsuranauto" for="x_totalangsuranauto" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->totalangsuranauto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->totalangsuranauto->CellAttributes() ?>>
<span id="el_tbayarangsuran_totalangsuranauto">
<input type="text" data-table="tbayarangsuran" data-field="x_totalangsuranauto" name="x_totalangsuranauto" id="x_totalangsuranauto" size="30" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->totalangsuranauto->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->totalangsuranauto->EditValue ?>"<?php echo $tbayarangsuran->totalangsuranauto->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->totalangsuranauto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->sisaangsuran->Visible) { // sisaangsuran ?>
	<div id="r_sisaangsuran" class="form-group">
		<label id="elh_tbayarangsuran_sisaangsuran" for="x_sisaangsuran" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->sisaangsuran->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->sisaangsuran->CellAttributes() ?>>
<span id="el_tbayarangsuran_sisaangsuran">
<input type="text" data-table="tbayarangsuran" data-field="x_sisaangsuran" name="x_sisaangsuran" id="x_sisaangsuran" size="30" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->sisaangsuran->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->sisaangsuran->EditValue ?>"<?php echo $tbayarangsuran->sisaangsuran->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->sisaangsuran->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->sisaangsuranauto->Visible) { // sisaangsuranauto ?>
	<div id="r_sisaangsuranauto" class="form-group">
		<label id="elh_tbayarangsuran_sisaangsuranauto" for="x_sisaangsuranauto" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->sisaangsuranauto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->sisaangsuranauto->CellAttributes() ?>>
<span id="el_tbayarangsuran_sisaangsuranauto">
<input type="text" data-table="tbayarangsuran" data-field="x_sisaangsuranauto" name="x_sisaangsuranauto" id="x_sisaangsuranauto" size="30" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->sisaangsuranauto->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->sisaangsuranauto->EditValue ?>"<?php echo $tbayarangsuran->sisaangsuranauto->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->sisaangsuranauto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->saldotitipan->Visible) { // saldotitipan ?>
	<div id="r_saldotitipan" class="form-group">
		<label id="elh_tbayarangsuran_saldotitipan" for="x_saldotitipan" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->saldotitipan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->saldotitipan->CellAttributes() ?>>
<span id="el_tbayarangsuran_saldotitipan">
<input type="text" data-table="tbayarangsuran" data-field="x_saldotitipan" name="x_saldotitipan" id="x_saldotitipan" size="30" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->saldotitipan->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->saldotitipan->EditValue ?>"<?php echo $tbayarangsuran->saldotitipan->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->saldotitipan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->saldotitipansisa->Visible) { // saldotitipansisa ?>
	<div id="r_saldotitipansisa" class="form-group">
		<label id="elh_tbayarangsuran_saldotitipansisa" for="x_saldotitipansisa" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->saldotitipansisa->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->saldotitipansisa->CellAttributes() ?>>
<span id="el_tbayarangsuran_saldotitipansisa">
<input type="text" data-table="tbayarangsuran" data-field="x_saldotitipansisa" name="x_saldotitipansisa" id="x_saldotitipansisa" size="30" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->saldotitipansisa->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->saldotitipansisa->EditValue ?>"<?php echo $tbayarangsuran->saldotitipansisa->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->saldotitipansisa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->bayarpokok->Visible) { // bayarpokok ?>
	<div id="r_bayarpokok" class="form-group">
		<label id="elh_tbayarangsuran_bayarpokok" for="x_bayarpokok" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->bayarpokok->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->bayarpokok->CellAttributes() ?>>
<span id="el_tbayarangsuran_bayarpokok">
<input type="text" data-table="tbayarangsuran" data-field="x_bayarpokok" name="x_bayarpokok" id="x_bayarpokok" size="30" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->bayarpokok->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->bayarpokok->EditValue ?>"<?php echo $tbayarangsuran->bayarpokok->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->bayarpokok->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->bayarpokokauto->Visible) { // bayarpokokauto ?>
	<div id="r_bayarpokokauto" class="form-group">
		<label id="elh_tbayarangsuran_bayarpokokauto" for="x_bayarpokokauto" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->bayarpokokauto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->bayarpokokauto->CellAttributes() ?>>
<span id="el_tbayarangsuran_bayarpokokauto">
<input type="text" data-table="tbayarangsuran" data-field="x_bayarpokokauto" name="x_bayarpokokauto" id="x_bayarpokokauto" size="30" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->bayarpokokauto->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->bayarpokokauto->EditValue ?>"<?php echo $tbayarangsuran->bayarpokokauto->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->bayarpokokauto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->bayarbunga->Visible) { // bayarbunga ?>
	<div id="r_bayarbunga" class="form-group">
		<label id="elh_tbayarangsuran_bayarbunga" for="x_bayarbunga" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->bayarbunga->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->bayarbunga->CellAttributes() ?>>
<span id="el_tbayarangsuran_bayarbunga">
<input type="text" data-table="tbayarangsuran" data-field="x_bayarbunga" name="x_bayarbunga" id="x_bayarbunga" size="30" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->bayarbunga->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->bayarbunga->EditValue ?>"<?php echo $tbayarangsuran->bayarbunga->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->bayarbunga->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->bayarbungaauto->Visible) { // bayarbungaauto ?>
	<div id="r_bayarbungaauto" class="form-group">
		<label id="elh_tbayarangsuran_bayarbungaauto" for="x_bayarbungaauto" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->bayarbungaauto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->bayarbungaauto->CellAttributes() ?>>
<span id="el_tbayarangsuran_bayarbungaauto">
<input type="text" data-table="tbayarangsuran" data-field="x_bayarbungaauto" name="x_bayarbungaauto" id="x_bayarbungaauto" size="30" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->bayarbungaauto->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->bayarbungaauto->EditValue ?>"<?php echo $tbayarangsuran->bayarbungaauto->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->bayarbungaauto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->bayardenda->Visible) { // bayardenda ?>
	<div id="r_bayardenda" class="form-group">
		<label id="elh_tbayarangsuran_bayardenda" for="x_bayardenda" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->bayardenda->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->bayardenda->CellAttributes() ?>>
<span id="el_tbayarangsuran_bayardenda">
<input type="text" data-table="tbayarangsuran" data-field="x_bayardenda" name="x_bayardenda" id="x_bayardenda" size="30" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->bayardenda->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->bayardenda->EditValue ?>"<?php echo $tbayarangsuran->bayardenda->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->bayardenda->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->bayardendaauto->Visible) { // bayardendaauto ?>
	<div id="r_bayardendaauto" class="form-group">
		<label id="elh_tbayarangsuran_bayardendaauto" for="x_bayardendaauto" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->bayardendaauto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->bayardendaauto->CellAttributes() ?>>
<span id="el_tbayarangsuran_bayardendaauto">
<input type="text" data-table="tbayarangsuran" data-field="x_bayardendaauto" name="x_bayardendaauto" id="x_bayardendaauto" size="30" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->bayardendaauto->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->bayardendaauto->EditValue ?>"<?php echo $tbayarangsuran->bayardendaauto->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->bayardendaauto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->bayartitipan->Visible) { // bayartitipan ?>
	<div id="r_bayartitipan" class="form-group">
		<label id="elh_tbayarangsuran_bayartitipan" for="x_bayartitipan" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->bayartitipan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->bayartitipan->CellAttributes() ?>>
<span id="el_tbayarangsuran_bayartitipan">
<input type="text" data-table="tbayarangsuran" data-field="x_bayartitipan" name="x_bayartitipan" id="x_bayartitipan" size="30" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->bayartitipan->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->bayartitipan->EditValue ?>"<?php echo $tbayarangsuran->bayartitipan->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->bayartitipan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->bayartitipanauto->Visible) { // bayartitipanauto ?>
	<div id="r_bayartitipanauto" class="form-group">
		<label id="elh_tbayarangsuran_bayartitipanauto" for="x_bayartitipanauto" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->bayartitipanauto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->bayartitipanauto->CellAttributes() ?>>
<span id="el_tbayarangsuran_bayartitipanauto">
<input type="text" data-table="tbayarangsuran" data-field="x_bayartitipanauto" name="x_bayartitipanauto" id="x_bayartitipanauto" size="30" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->bayartitipanauto->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->bayartitipanauto->EditValue ?>"<?php echo $tbayarangsuran->bayartitipanauto->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->bayartitipanauto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->totalbayar->Visible) { // totalbayar ?>
	<div id="r_totalbayar" class="form-group">
		<label id="elh_tbayarangsuran_totalbayar" for="x_totalbayar" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->totalbayar->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->totalbayar->CellAttributes() ?>>
<span id="el_tbayarangsuran_totalbayar">
<input type="text" data-table="tbayarangsuran" data-field="x_totalbayar" name="x_totalbayar" id="x_totalbayar" size="30" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->totalbayar->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->totalbayar->EditValue ?>"<?php echo $tbayarangsuran->totalbayar->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->totalbayar->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->totalbayarauto->Visible) { // totalbayarauto ?>
	<div id="r_totalbayarauto" class="form-group">
		<label id="elh_tbayarangsuran_totalbayarauto" for="x_totalbayarauto" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->totalbayarauto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->totalbayarauto->CellAttributes() ?>>
<span id="el_tbayarangsuran_totalbayarauto">
<input type="text" data-table="tbayarangsuran" data-field="x_totalbayarauto" name="x_totalbayarauto" id="x_totalbayarauto" size="30" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->totalbayarauto->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->totalbayarauto->EditValue ?>"<?php echo $tbayarangsuran->totalbayarauto->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->totalbayarauto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->pelunasan->Visible) { // pelunasan ?>
	<div id="r_pelunasan" class="form-group">
		<label id="elh_tbayarangsuran_pelunasan" for="x_pelunasan" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->pelunasan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->pelunasan->CellAttributes() ?>>
<span id="el_tbayarangsuran_pelunasan">
<input type="text" data-table="tbayarangsuran" data-field="x_pelunasan" name="x_pelunasan" id="x_pelunasan" size="30" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->pelunasan->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->pelunasan->EditValue ?>"<?php echo $tbayarangsuran->pelunasan->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->pelunasan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->pelunasanauto->Visible) { // pelunasanauto ?>
	<div id="r_pelunasanauto" class="form-group">
		<label id="elh_tbayarangsuran_pelunasanauto" for="x_pelunasanauto" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->pelunasanauto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->pelunasanauto->CellAttributes() ?>>
<span id="el_tbayarangsuran_pelunasanauto">
<input type="text" data-table="tbayarangsuran" data-field="x_pelunasanauto" name="x_pelunasanauto" id="x_pelunasanauto" size="30" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->pelunasanauto->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->pelunasanauto->EditValue ?>"<?php echo $tbayarangsuran->pelunasanauto->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->pelunasanauto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->finalty->Visible) { // finalty ?>
	<div id="r_finalty" class="form-group">
		<label id="elh_tbayarangsuran_finalty" for="x_finalty" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->finalty->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->finalty->CellAttributes() ?>>
<span id="el_tbayarangsuran_finalty">
<input type="text" data-table="tbayarangsuran" data-field="x_finalty" name="x_finalty" id="x_finalty" size="30" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->finalty->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->finalty->EditValue ?>"<?php echo $tbayarangsuran->finalty->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->finalty->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->finaltyauto->Visible) { // finaltyauto ?>
	<div id="r_finaltyauto" class="form-group">
		<label id="elh_tbayarangsuran_finaltyauto" for="x_finaltyauto" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->finaltyauto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->finaltyauto->CellAttributes() ?>>
<span id="el_tbayarangsuran_finaltyauto">
<input type="text" data-table="tbayarangsuran" data-field="x_finaltyauto" name="x_finaltyauto" id="x_finaltyauto" size="30" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->finaltyauto->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->finaltyauto->EditValue ?>"<?php echo $tbayarangsuran->finaltyauto->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->finaltyauto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->terbilang->Visible) { // terbilang ?>
	<div id="r_terbilang" class="form-group">
		<label id="elh_tbayarangsuran_terbilang" for="x_terbilang" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->terbilang->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->terbilang->CellAttributes() ?>>
<span id="el_tbayarangsuran_terbilang">
<input type="text" data-table="tbayarangsuran" data-field="x_terbilang" name="x_terbilang" id="x_terbilang" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->terbilang->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->terbilang->EditValue ?>"<?php echo $tbayarangsuran->terbilang->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->terbilang->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->petugas->Visible) { // petugas ?>
	<div id="r_petugas" class="form-group">
		<label id="elh_tbayarangsuran_petugas" for="x_petugas" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->petugas->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->petugas->CellAttributes() ?>>
<span id="el_tbayarangsuran_petugas">
<input type="text" data-table="tbayarangsuran" data-field="x_petugas" name="x_petugas" id="x_petugas" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->petugas->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->petugas->EditValue ?>"<?php echo $tbayarangsuran->petugas->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->petugas->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->pembayaran->Visible) { // pembayaran ?>
	<div id="r_pembayaran" class="form-group">
		<label id="elh_tbayarangsuran_pembayaran" for="x_pembayaran" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->pembayaran->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->pembayaran->CellAttributes() ?>>
<span id="el_tbayarangsuran_pembayaran">
<input type="text" data-table="tbayarangsuran" data-field="x_pembayaran" name="x_pembayaran" id="x_pembayaran" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->pembayaran->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->pembayaran->EditValue ?>"<?php echo $tbayarangsuran->pembayaran->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->pembayaran->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->bank->Visible) { // bank ?>
	<div id="r_bank" class="form-group">
		<label id="elh_tbayarangsuran_bank" for="x_bank" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->bank->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->bank->CellAttributes() ?>>
<span id="el_tbayarangsuran_bank">
<input type="text" data-table="tbayarangsuran" data-field="x_bank" name="x_bank" id="x_bank" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->bank->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->bank->EditValue ?>"<?php echo $tbayarangsuran->bank->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->bank->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->atasnama->Visible) { // atasnama ?>
	<div id="r_atasnama" class="form-group">
		<label id="elh_tbayarangsuran_atasnama" for="x_atasnama" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->atasnama->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->atasnama->CellAttributes() ?>>
<span id="el_tbayarangsuran_atasnama">
<input type="text" data-table="tbayarangsuran" data-field="x_atasnama" name="x_atasnama" id="x_atasnama" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->atasnama->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->atasnama->EditValue ?>"<?php echo $tbayarangsuran->atasnama->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->atasnama->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->tipe->Visible) { // tipe ?>
	<div id="r_tipe" class="form-group">
		<label id="elh_tbayarangsuran_tipe" for="x_tipe" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->tipe->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->tipe->CellAttributes() ?>>
<span id="el_tbayarangsuran_tipe">
<input type="text" data-table="tbayarangsuran" data-field="x_tipe" name="x_tipe" id="x_tipe" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->tipe->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->tipe->EditValue ?>"<?php echo $tbayarangsuran->tipe->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->tipe->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->kantor->Visible) { // kantor ?>
	<div id="r_kantor" class="form-group">
		<label id="elh_tbayarangsuran_kantor" for="x_kantor" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->kantor->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->kantor->CellAttributes() ?>>
<span id="el_tbayarangsuran_kantor">
<input type="text" data-table="tbayarangsuran" data-field="x_kantor" name="x_kantor" id="x_kantor" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->kantor->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->kantor->EditValue ?>"<?php echo $tbayarangsuran->kantor->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->kantor->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->keterangan->Visible) { // keterangan ?>
	<div id="r_keterangan" class="form-group">
		<label id="elh_tbayarangsuran_keterangan" for="x_keterangan" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->keterangan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->keterangan->CellAttributes() ?>>
<span id="el_tbayarangsuran_keterangan">
<input type="text" data-table="tbayarangsuran" data-field="x_keterangan" name="x_keterangan" id="x_keterangan" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->keterangan->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->keterangan->EditValue ?>"<?php echo $tbayarangsuran->keterangan->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->keterangan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->active->Visible) { // active ?>
	<div id="r_active" class="form-group">
		<label id="elh_tbayarangsuran_active" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->active->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->active->CellAttributes() ?>>
<span id="el_tbayarangsuran_active">
<div id="tp_x_active" class="ewTemplate"><input type="radio" data-table="tbayarangsuran" data-field="x_active" data-value-separator="<?php echo $tbayarangsuran->active->DisplayValueSeparatorAttribute() ?>" name="x_active" id="x_active" value="{value}"<?php echo $tbayarangsuran->active->EditAttributes() ?>></div>
<div id="dsl_x_active" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $tbayarangsuran->active->RadioButtonListHtml(FALSE, "x_active") ?>
</div></div>
</span>
<?php echo $tbayarangsuran->active->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->ip->Visible) { // ip ?>
	<div id="r_ip" class="form-group">
		<label id="elh_tbayarangsuran_ip" for="x_ip" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->ip->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->ip->CellAttributes() ?>>
<span id="el_tbayarangsuran_ip">
<input type="text" data-table="tbayarangsuran" data-field="x_ip" name="x_ip" id="x_ip" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->ip->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->ip->EditValue ?>"<?php echo $tbayarangsuran->ip->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->ip->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_tbayarangsuran_status" for="x_status" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->status->CellAttributes() ?>>
<span id="el_tbayarangsuran_status">
<input type="text" data-table="tbayarangsuran" data-field="x_status" name="x_status" id="x_status" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->status->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->status->EditValue ?>"<?php echo $tbayarangsuran->status->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->user->Visible) { // user ?>
	<div id="r_user" class="form-group">
		<label id="elh_tbayarangsuran_user" for="x_user" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->user->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->user->CellAttributes() ?>>
<span id="el_tbayarangsuran_user">
<input type="text" data-table="tbayarangsuran" data-field="x_user" name="x_user" id="x_user" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->user->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->user->EditValue ?>"<?php echo $tbayarangsuran->user->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->user->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->created->Visible) { // created ?>
	<div id="r_created" class="form-group">
		<label id="elh_tbayarangsuran_created" for="x_created" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->created->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->created->CellAttributes() ?>>
<span id="el_tbayarangsuran_created">
<input type="text" data-table="tbayarangsuran" data-field="x_created" name="x_created" id="x_created" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->created->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->created->EditValue ?>"<?php echo $tbayarangsuran->created->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->created->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tbayarangsuran->modified->Visible) { // modified ?>
	<div id="r_modified" class="form-group">
		<label id="elh_tbayarangsuran_modified" for="x_modified" class="col-sm-2 control-label ewLabel"><?php echo $tbayarangsuran->modified->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tbayarangsuran->modified->CellAttributes() ?>>
<span id="el_tbayarangsuran_modified">
<input type="text" data-table="tbayarangsuran" data-field="x_modified" name="x_modified" id="x_modified" placeholder="<?php echo ew_HtmlEncode($tbayarangsuran->modified->getPlaceHolder()) ?>" value="<?php echo $tbayarangsuran->modified->EditValue ?>"<?php echo $tbayarangsuran->modified->EditAttributes() ?>>
</span>
<?php echo $tbayarangsuran->modified->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$tbayarangsuran_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $tbayarangsuran_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ftbayarangsuranadd.Init();
</script>
<?php
$tbayarangsuran_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tbayarangsuran_add->Page_Terminate();
?>
