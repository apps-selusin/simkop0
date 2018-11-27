<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "tlaporaninfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$tlaporan_add = NULL; // Initialize page object first

class ctlaporan_add extends ctlaporan {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tlaporan';

	// Page object name
	var $PageObjName = 'tlaporan_add';

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

		// Table object (tlaporan)
		if (!isset($GLOBALS["tlaporan"]) || get_class($GLOBALS["tlaporan"]) == "ctlaporan") {
			$GLOBALS["tlaporan"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tlaporan"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tlaporan', TRUE);

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
		$this->group->SetVisibility();
		$this->rekening->SetVisibility();
		$this->tipe->SetVisibility();
		$this->posisi->SetVisibility();
		$this->laporan->SetVisibility();
		$this->keterangan->SetVisibility();
		$this->debet1->SetVisibility();
		$this->credit1->SetVisibility();
		$this->saldo1->SetVisibility();
		$this->debet2->SetVisibility();
		$this->credit2->SetVisibility();
		$this->saldo2->SetVisibility();
		$this->debet3->SetVisibility();
		$this->credit3->SetVisibility();
		$this->saldo3->SetVisibility();
		$this->debet4->SetVisibility();
		$this->credit4->SetVisibility();
		$this->saldo4->SetVisibility();
		$this->debet5->SetVisibility();
		$this->credit5->SetVisibility();
		$this->saldo5->SetVisibility();
		$this->debet6->SetVisibility();
		$this->credit6->SetVisibility();
		$this->saldo6->SetVisibility();
		$this->debet7->SetVisibility();
		$this->credit7->SetVisibility();
		$this->saldo7->SetVisibility();
		$this->debet8->SetVisibility();
		$this->credit8->SetVisibility();
		$this->saldo8->SetVisibility();
		$this->debet9->SetVisibility();
		$this->credit9->SetVisibility();
		$this->saldo9->SetVisibility();
		$this->debet10->SetVisibility();
		$this->credit10->SetVisibility();
		$this->saldo10->SetVisibility();
		$this->debet11->SetVisibility();
		$this->credit11->SetVisibility();
		$this->saldo11->SetVisibility();
		$this->debet12->SetVisibility();
		$this->credit12->SetVisibility();
		$this->saldo12->SetVisibility();

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
		global $EW_EXPORT, $tlaporan;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($tlaporan);
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
					$this->Page_Terminate("tlaporanlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "tlaporanlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "tlaporanview.php")
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
		$this->group->CurrentValue = 0;
		$this->rekening->CurrentValue = NULL;
		$this->rekening->OldValue = $this->rekening->CurrentValue;
		$this->tipe->CurrentValue = NULL;
		$this->tipe->OldValue = $this->tipe->CurrentValue;
		$this->posisi->CurrentValue = NULL;
		$this->posisi->OldValue = $this->posisi->CurrentValue;
		$this->laporan->CurrentValue = NULL;
		$this->laporan->OldValue = $this->laporan->CurrentValue;
		$this->keterangan->CurrentValue = NULL;
		$this->keterangan->OldValue = $this->keterangan->CurrentValue;
		$this->debet1->CurrentValue = 0;
		$this->credit1->CurrentValue = 0;
		$this->saldo1->CurrentValue = 0;
		$this->debet2->CurrentValue = 0;
		$this->credit2->CurrentValue = 0;
		$this->saldo2->CurrentValue = 0;
		$this->debet3->CurrentValue = 0;
		$this->credit3->CurrentValue = 0;
		$this->saldo3->CurrentValue = 0;
		$this->debet4->CurrentValue = 0;
		$this->credit4->CurrentValue = 0;
		$this->saldo4->CurrentValue = 0;
		$this->debet5->CurrentValue = 0;
		$this->credit5->CurrentValue = 0;
		$this->saldo5->CurrentValue = 0;
		$this->debet6->CurrentValue = 0;
		$this->credit6->CurrentValue = 0;
		$this->saldo6->CurrentValue = 0;
		$this->debet7->CurrentValue = 0;
		$this->credit7->CurrentValue = 0;
		$this->saldo7->CurrentValue = 0;
		$this->debet8->CurrentValue = 0;
		$this->credit8->CurrentValue = 0;
		$this->saldo8->CurrentValue = 0;
		$this->debet9->CurrentValue = 0;
		$this->credit9->CurrentValue = 0;
		$this->saldo9->CurrentValue = 0;
		$this->debet10->CurrentValue = 0;
		$this->credit10->CurrentValue = 0;
		$this->saldo10->CurrentValue = 0;
		$this->debet11->CurrentValue = 0;
		$this->credit11->CurrentValue = 0;
		$this->saldo11->CurrentValue = 0;
		$this->debet12->CurrentValue = 0;
		$this->credit12->CurrentValue = 0;
		$this->saldo12->CurrentValue = 0;
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
		if (!$this->group->FldIsDetailKey) {
			$this->group->setFormValue($objForm->GetValue("x_group"));
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
		if (!$this->keterangan->FldIsDetailKey) {
			$this->keterangan->setFormValue($objForm->GetValue("x_keterangan"));
		}
		if (!$this->debet1->FldIsDetailKey) {
			$this->debet1->setFormValue($objForm->GetValue("x_debet1"));
		}
		if (!$this->credit1->FldIsDetailKey) {
			$this->credit1->setFormValue($objForm->GetValue("x_credit1"));
		}
		if (!$this->saldo1->FldIsDetailKey) {
			$this->saldo1->setFormValue($objForm->GetValue("x_saldo1"));
		}
		if (!$this->debet2->FldIsDetailKey) {
			$this->debet2->setFormValue($objForm->GetValue("x_debet2"));
		}
		if (!$this->credit2->FldIsDetailKey) {
			$this->credit2->setFormValue($objForm->GetValue("x_credit2"));
		}
		if (!$this->saldo2->FldIsDetailKey) {
			$this->saldo2->setFormValue($objForm->GetValue("x_saldo2"));
		}
		if (!$this->debet3->FldIsDetailKey) {
			$this->debet3->setFormValue($objForm->GetValue("x_debet3"));
		}
		if (!$this->credit3->FldIsDetailKey) {
			$this->credit3->setFormValue($objForm->GetValue("x_credit3"));
		}
		if (!$this->saldo3->FldIsDetailKey) {
			$this->saldo3->setFormValue($objForm->GetValue("x_saldo3"));
		}
		if (!$this->debet4->FldIsDetailKey) {
			$this->debet4->setFormValue($objForm->GetValue("x_debet4"));
		}
		if (!$this->credit4->FldIsDetailKey) {
			$this->credit4->setFormValue($objForm->GetValue("x_credit4"));
		}
		if (!$this->saldo4->FldIsDetailKey) {
			$this->saldo4->setFormValue($objForm->GetValue("x_saldo4"));
		}
		if (!$this->debet5->FldIsDetailKey) {
			$this->debet5->setFormValue($objForm->GetValue("x_debet5"));
		}
		if (!$this->credit5->FldIsDetailKey) {
			$this->credit5->setFormValue($objForm->GetValue("x_credit5"));
		}
		if (!$this->saldo5->FldIsDetailKey) {
			$this->saldo5->setFormValue($objForm->GetValue("x_saldo5"));
		}
		if (!$this->debet6->FldIsDetailKey) {
			$this->debet6->setFormValue($objForm->GetValue("x_debet6"));
		}
		if (!$this->credit6->FldIsDetailKey) {
			$this->credit6->setFormValue($objForm->GetValue("x_credit6"));
		}
		if (!$this->saldo6->FldIsDetailKey) {
			$this->saldo6->setFormValue($objForm->GetValue("x_saldo6"));
		}
		if (!$this->debet7->FldIsDetailKey) {
			$this->debet7->setFormValue($objForm->GetValue("x_debet7"));
		}
		if (!$this->credit7->FldIsDetailKey) {
			$this->credit7->setFormValue($objForm->GetValue("x_credit7"));
		}
		if (!$this->saldo7->FldIsDetailKey) {
			$this->saldo7->setFormValue($objForm->GetValue("x_saldo7"));
		}
		if (!$this->debet8->FldIsDetailKey) {
			$this->debet8->setFormValue($objForm->GetValue("x_debet8"));
		}
		if (!$this->credit8->FldIsDetailKey) {
			$this->credit8->setFormValue($objForm->GetValue("x_credit8"));
		}
		if (!$this->saldo8->FldIsDetailKey) {
			$this->saldo8->setFormValue($objForm->GetValue("x_saldo8"));
		}
		if (!$this->debet9->FldIsDetailKey) {
			$this->debet9->setFormValue($objForm->GetValue("x_debet9"));
		}
		if (!$this->credit9->FldIsDetailKey) {
			$this->credit9->setFormValue($objForm->GetValue("x_credit9"));
		}
		if (!$this->saldo9->FldIsDetailKey) {
			$this->saldo9->setFormValue($objForm->GetValue("x_saldo9"));
		}
		if (!$this->debet10->FldIsDetailKey) {
			$this->debet10->setFormValue($objForm->GetValue("x_debet10"));
		}
		if (!$this->credit10->FldIsDetailKey) {
			$this->credit10->setFormValue($objForm->GetValue("x_credit10"));
		}
		if (!$this->saldo10->FldIsDetailKey) {
			$this->saldo10->setFormValue($objForm->GetValue("x_saldo10"));
		}
		if (!$this->debet11->FldIsDetailKey) {
			$this->debet11->setFormValue($objForm->GetValue("x_debet11"));
		}
		if (!$this->credit11->FldIsDetailKey) {
			$this->credit11->setFormValue($objForm->GetValue("x_credit11"));
		}
		if (!$this->saldo11->FldIsDetailKey) {
			$this->saldo11->setFormValue($objForm->GetValue("x_saldo11"));
		}
		if (!$this->debet12->FldIsDetailKey) {
			$this->debet12->setFormValue($objForm->GetValue("x_debet12"));
		}
		if (!$this->credit12->FldIsDetailKey) {
			$this->credit12->setFormValue($objForm->GetValue("x_credit12"));
		}
		if (!$this->saldo12->FldIsDetailKey) {
			$this->saldo12->setFormValue($objForm->GetValue("x_saldo12"));
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
		$this->group->CurrentValue = $this->group->FormValue;
		$this->rekening->CurrentValue = $this->rekening->FormValue;
		$this->tipe->CurrentValue = $this->tipe->FormValue;
		$this->posisi->CurrentValue = $this->posisi->FormValue;
		$this->laporan->CurrentValue = $this->laporan->FormValue;
		$this->keterangan->CurrentValue = $this->keterangan->FormValue;
		$this->debet1->CurrentValue = $this->debet1->FormValue;
		$this->credit1->CurrentValue = $this->credit1->FormValue;
		$this->saldo1->CurrentValue = $this->saldo1->FormValue;
		$this->debet2->CurrentValue = $this->debet2->FormValue;
		$this->credit2->CurrentValue = $this->credit2->FormValue;
		$this->saldo2->CurrentValue = $this->saldo2->FormValue;
		$this->debet3->CurrentValue = $this->debet3->FormValue;
		$this->credit3->CurrentValue = $this->credit3->FormValue;
		$this->saldo3->CurrentValue = $this->saldo3->FormValue;
		$this->debet4->CurrentValue = $this->debet4->FormValue;
		$this->credit4->CurrentValue = $this->credit4->FormValue;
		$this->saldo4->CurrentValue = $this->saldo4->FormValue;
		$this->debet5->CurrentValue = $this->debet5->FormValue;
		$this->credit5->CurrentValue = $this->credit5->FormValue;
		$this->saldo5->CurrentValue = $this->saldo5->FormValue;
		$this->debet6->CurrentValue = $this->debet6->FormValue;
		$this->credit6->CurrentValue = $this->credit6->FormValue;
		$this->saldo6->CurrentValue = $this->saldo6->FormValue;
		$this->debet7->CurrentValue = $this->debet7->FormValue;
		$this->credit7->CurrentValue = $this->credit7->FormValue;
		$this->saldo7->CurrentValue = $this->saldo7->FormValue;
		$this->debet8->CurrentValue = $this->debet8->FormValue;
		$this->credit8->CurrentValue = $this->credit8->FormValue;
		$this->saldo8->CurrentValue = $this->saldo8->FormValue;
		$this->debet9->CurrentValue = $this->debet9->FormValue;
		$this->credit9->CurrentValue = $this->credit9->FormValue;
		$this->saldo9->CurrentValue = $this->saldo9->FormValue;
		$this->debet10->CurrentValue = $this->debet10->FormValue;
		$this->credit10->CurrentValue = $this->credit10->FormValue;
		$this->saldo10->CurrentValue = $this->saldo10->FormValue;
		$this->debet11->CurrentValue = $this->debet11->FormValue;
		$this->credit11->CurrentValue = $this->credit11->FormValue;
		$this->saldo11->CurrentValue = $this->saldo11->FormValue;
		$this->debet12->CurrentValue = $this->debet12->FormValue;
		$this->credit12->CurrentValue = $this->credit12->FormValue;
		$this->saldo12->CurrentValue = $this->saldo12->FormValue;
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
		$this->group->setDbValue($rs->fields('group'));
		$this->rekening->setDbValue($rs->fields('rekening'));
		$this->tipe->setDbValue($rs->fields('tipe'));
		$this->posisi->setDbValue($rs->fields('posisi'));
		$this->laporan->setDbValue($rs->fields('laporan'));
		$this->keterangan->setDbValue($rs->fields('keterangan'));
		$this->debet1->setDbValue($rs->fields('debet1'));
		$this->credit1->setDbValue($rs->fields('credit1'));
		$this->saldo1->setDbValue($rs->fields('saldo1'));
		$this->debet2->setDbValue($rs->fields('debet2'));
		$this->credit2->setDbValue($rs->fields('credit2'));
		$this->saldo2->setDbValue($rs->fields('saldo2'));
		$this->debet3->setDbValue($rs->fields('debet3'));
		$this->credit3->setDbValue($rs->fields('credit3'));
		$this->saldo3->setDbValue($rs->fields('saldo3'));
		$this->debet4->setDbValue($rs->fields('debet4'));
		$this->credit4->setDbValue($rs->fields('credit4'));
		$this->saldo4->setDbValue($rs->fields('saldo4'));
		$this->debet5->setDbValue($rs->fields('debet5'));
		$this->credit5->setDbValue($rs->fields('credit5'));
		$this->saldo5->setDbValue($rs->fields('saldo5'));
		$this->debet6->setDbValue($rs->fields('debet6'));
		$this->credit6->setDbValue($rs->fields('credit6'));
		$this->saldo6->setDbValue($rs->fields('saldo6'));
		$this->debet7->setDbValue($rs->fields('debet7'));
		$this->credit7->setDbValue($rs->fields('credit7'));
		$this->saldo7->setDbValue($rs->fields('saldo7'));
		$this->debet8->setDbValue($rs->fields('debet8'));
		$this->credit8->setDbValue($rs->fields('credit8'));
		$this->saldo8->setDbValue($rs->fields('saldo8'));
		$this->debet9->setDbValue($rs->fields('debet9'));
		$this->credit9->setDbValue($rs->fields('credit9'));
		$this->saldo9->setDbValue($rs->fields('saldo9'));
		$this->debet10->setDbValue($rs->fields('debet10'));
		$this->credit10->setDbValue($rs->fields('credit10'));
		$this->saldo10->setDbValue($rs->fields('saldo10'));
		$this->debet11->setDbValue($rs->fields('debet11'));
		$this->credit11->setDbValue($rs->fields('credit11'));
		$this->saldo11->setDbValue($rs->fields('saldo11'));
		$this->debet12->setDbValue($rs->fields('debet12'));
		$this->credit12->setDbValue($rs->fields('credit12'));
		$this->saldo12->setDbValue($rs->fields('saldo12'));
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
		$this->group->DbValue = $row['group'];
		$this->rekening->DbValue = $row['rekening'];
		$this->tipe->DbValue = $row['tipe'];
		$this->posisi->DbValue = $row['posisi'];
		$this->laporan->DbValue = $row['laporan'];
		$this->keterangan->DbValue = $row['keterangan'];
		$this->debet1->DbValue = $row['debet1'];
		$this->credit1->DbValue = $row['credit1'];
		$this->saldo1->DbValue = $row['saldo1'];
		$this->debet2->DbValue = $row['debet2'];
		$this->credit2->DbValue = $row['credit2'];
		$this->saldo2->DbValue = $row['saldo2'];
		$this->debet3->DbValue = $row['debet3'];
		$this->credit3->DbValue = $row['credit3'];
		$this->saldo3->DbValue = $row['saldo3'];
		$this->debet4->DbValue = $row['debet4'];
		$this->credit4->DbValue = $row['credit4'];
		$this->saldo4->DbValue = $row['saldo4'];
		$this->debet5->DbValue = $row['debet5'];
		$this->credit5->DbValue = $row['credit5'];
		$this->saldo5->DbValue = $row['saldo5'];
		$this->debet6->DbValue = $row['debet6'];
		$this->credit6->DbValue = $row['credit6'];
		$this->saldo6->DbValue = $row['saldo6'];
		$this->debet7->DbValue = $row['debet7'];
		$this->credit7->DbValue = $row['credit7'];
		$this->saldo7->DbValue = $row['saldo7'];
		$this->debet8->DbValue = $row['debet8'];
		$this->credit8->DbValue = $row['credit8'];
		$this->saldo8->DbValue = $row['saldo8'];
		$this->debet9->DbValue = $row['debet9'];
		$this->credit9->DbValue = $row['credit9'];
		$this->saldo9->DbValue = $row['saldo9'];
		$this->debet10->DbValue = $row['debet10'];
		$this->credit10->DbValue = $row['credit10'];
		$this->saldo10->DbValue = $row['saldo10'];
		$this->debet11->DbValue = $row['debet11'];
		$this->credit11->DbValue = $row['credit11'];
		$this->saldo11->DbValue = $row['saldo11'];
		$this->debet12->DbValue = $row['debet12'];
		$this->credit12->DbValue = $row['credit12'];
		$this->saldo12->DbValue = $row['saldo12'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
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

		if ($this->debet1->FormValue == $this->debet1->CurrentValue && is_numeric(ew_StrToFloat($this->debet1->CurrentValue)))
			$this->debet1->CurrentValue = ew_StrToFloat($this->debet1->CurrentValue);

		// Convert decimal values if posted back
		if ($this->credit1->FormValue == $this->credit1->CurrentValue && is_numeric(ew_StrToFloat($this->credit1->CurrentValue)))
			$this->credit1->CurrentValue = ew_StrToFloat($this->credit1->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldo1->FormValue == $this->saldo1->CurrentValue && is_numeric(ew_StrToFloat($this->saldo1->CurrentValue)))
			$this->saldo1->CurrentValue = ew_StrToFloat($this->saldo1->CurrentValue);

		// Convert decimal values if posted back
		if ($this->debet2->FormValue == $this->debet2->CurrentValue && is_numeric(ew_StrToFloat($this->debet2->CurrentValue)))
			$this->debet2->CurrentValue = ew_StrToFloat($this->debet2->CurrentValue);

		// Convert decimal values if posted back
		if ($this->credit2->FormValue == $this->credit2->CurrentValue && is_numeric(ew_StrToFloat($this->credit2->CurrentValue)))
			$this->credit2->CurrentValue = ew_StrToFloat($this->credit2->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldo2->FormValue == $this->saldo2->CurrentValue && is_numeric(ew_StrToFloat($this->saldo2->CurrentValue)))
			$this->saldo2->CurrentValue = ew_StrToFloat($this->saldo2->CurrentValue);

		// Convert decimal values if posted back
		if ($this->debet3->FormValue == $this->debet3->CurrentValue && is_numeric(ew_StrToFloat($this->debet3->CurrentValue)))
			$this->debet3->CurrentValue = ew_StrToFloat($this->debet3->CurrentValue);

		// Convert decimal values if posted back
		if ($this->credit3->FormValue == $this->credit3->CurrentValue && is_numeric(ew_StrToFloat($this->credit3->CurrentValue)))
			$this->credit3->CurrentValue = ew_StrToFloat($this->credit3->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldo3->FormValue == $this->saldo3->CurrentValue && is_numeric(ew_StrToFloat($this->saldo3->CurrentValue)))
			$this->saldo3->CurrentValue = ew_StrToFloat($this->saldo3->CurrentValue);

		// Convert decimal values if posted back
		if ($this->debet4->FormValue == $this->debet4->CurrentValue && is_numeric(ew_StrToFloat($this->debet4->CurrentValue)))
			$this->debet4->CurrentValue = ew_StrToFloat($this->debet4->CurrentValue);

		// Convert decimal values if posted back
		if ($this->credit4->FormValue == $this->credit4->CurrentValue && is_numeric(ew_StrToFloat($this->credit4->CurrentValue)))
			$this->credit4->CurrentValue = ew_StrToFloat($this->credit4->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldo4->FormValue == $this->saldo4->CurrentValue && is_numeric(ew_StrToFloat($this->saldo4->CurrentValue)))
			$this->saldo4->CurrentValue = ew_StrToFloat($this->saldo4->CurrentValue);

		// Convert decimal values if posted back
		if ($this->debet5->FormValue == $this->debet5->CurrentValue && is_numeric(ew_StrToFloat($this->debet5->CurrentValue)))
			$this->debet5->CurrentValue = ew_StrToFloat($this->debet5->CurrentValue);

		// Convert decimal values if posted back
		if ($this->credit5->FormValue == $this->credit5->CurrentValue && is_numeric(ew_StrToFloat($this->credit5->CurrentValue)))
			$this->credit5->CurrentValue = ew_StrToFloat($this->credit5->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldo5->FormValue == $this->saldo5->CurrentValue && is_numeric(ew_StrToFloat($this->saldo5->CurrentValue)))
			$this->saldo5->CurrentValue = ew_StrToFloat($this->saldo5->CurrentValue);

		// Convert decimal values if posted back
		if ($this->debet6->FormValue == $this->debet6->CurrentValue && is_numeric(ew_StrToFloat($this->debet6->CurrentValue)))
			$this->debet6->CurrentValue = ew_StrToFloat($this->debet6->CurrentValue);

		// Convert decimal values if posted back
		if ($this->credit6->FormValue == $this->credit6->CurrentValue && is_numeric(ew_StrToFloat($this->credit6->CurrentValue)))
			$this->credit6->CurrentValue = ew_StrToFloat($this->credit6->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldo6->FormValue == $this->saldo6->CurrentValue && is_numeric(ew_StrToFloat($this->saldo6->CurrentValue)))
			$this->saldo6->CurrentValue = ew_StrToFloat($this->saldo6->CurrentValue);

		// Convert decimal values if posted back
		if ($this->debet7->FormValue == $this->debet7->CurrentValue && is_numeric(ew_StrToFloat($this->debet7->CurrentValue)))
			$this->debet7->CurrentValue = ew_StrToFloat($this->debet7->CurrentValue);

		// Convert decimal values if posted back
		if ($this->credit7->FormValue == $this->credit7->CurrentValue && is_numeric(ew_StrToFloat($this->credit7->CurrentValue)))
			$this->credit7->CurrentValue = ew_StrToFloat($this->credit7->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldo7->FormValue == $this->saldo7->CurrentValue && is_numeric(ew_StrToFloat($this->saldo7->CurrentValue)))
			$this->saldo7->CurrentValue = ew_StrToFloat($this->saldo7->CurrentValue);

		// Convert decimal values if posted back
		if ($this->debet8->FormValue == $this->debet8->CurrentValue && is_numeric(ew_StrToFloat($this->debet8->CurrentValue)))
			$this->debet8->CurrentValue = ew_StrToFloat($this->debet8->CurrentValue);

		// Convert decimal values if posted back
		if ($this->credit8->FormValue == $this->credit8->CurrentValue && is_numeric(ew_StrToFloat($this->credit8->CurrentValue)))
			$this->credit8->CurrentValue = ew_StrToFloat($this->credit8->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldo8->FormValue == $this->saldo8->CurrentValue && is_numeric(ew_StrToFloat($this->saldo8->CurrentValue)))
			$this->saldo8->CurrentValue = ew_StrToFloat($this->saldo8->CurrentValue);

		// Convert decimal values if posted back
		if ($this->debet9->FormValue == $this->debet9->CurrentValue && is_numeric(ew_StrToFloat($this->debet9->CurrentValue)))
			$this->debet9->CurrentValue = ew_StrToFloat($this->debet9->CurrentValue);

		// Convert decimal values if posted back
		if ($this->credit9->FormValue == $this->credit9->CurrentValue && is_numeric(ew_StrToFloat($this->credit9->CurrentValue)))
			$this->credit9->CurrentValue = ew_StrToFloat($this->credit9->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldo9->FormValue == $this->saldo9->CurrentValue && is_numeric(ew_StrToFloat($this->saldo9->CurrentValue)))
			$this->saldo9->CurrentValue = ew_StrToFloat($this->saldo9->CurrentValue);

		// Convert decimal values if posted back
		if ($this->debet10->FormValue == $this->debet10->CurrentValue && is_numeric(ew_StrToFloat($this->debet10->CurrentValue)))
			$this->debet10->CurrentValue = ew_StrToFloat($this->debet10->CurrentValue);

		// Convert decimal values if posted back
		if ($this->credit10->FormValue == $this->credit10->CurrentValue && is_numeric(ew_StrToFloat($this->credit10->CurrentValue)))
			$this->credit10->CurrentValue = ew_StrToFloat($this->credit10->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldo10->FormValue == $this->saldo10->CurrentValue && is_numeric(ew_StrToFloat($this->saldo10->CurrentValue)))
			$this->saldo10->CurrentValue = ew_StrToFloat($this->saldo10->CurrentValue);

		// Convert decimal values if posted back
		if ($this->debet11->FormValue == $this->debet11->CurrentValue && is_numeric(ew_StrToFloat($this->debet11->CurrentValue)))
			$this->debet11->CurrentValue = ew_StrToFloat($this->debet11->CurrentValue);

		// Convert decimal values if posted back
		if ($this->credit11->FormValue == $this->credit11->CurrentValue && is_numeric(ew_StrToFloat($this->credit11->CurrentValue)))
			$this->credit11->CurrentValue = ew_StrToFloat($this->credit11->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldo11->FormValue == $this->saldo11->CurrentValue && is_numeric(ew_StrToFloat($this->saldo11->CurrentValue)))
			$this->saldo11->CurrentValue = ew_StrToFloat($this->saldo11->CurrentValue);

		// Convert decimal values if posted back
		if ($this->debet12->FormValue == $this->debet12->CurrentValue && is_numeric(ew_StrToFloat($this->debet12->CurrentValue)))
			$this->debet12->CurrentValue = ew_StrToFloat($this->debet12->CurrentValue);

		// Convert decimal values if posted back
		if ($this->credit12->FormValue == $this->credit12->CurrentValue && is_numeric(ew_StrToFloat($this->credit12->CurrentValue)))
			$this->credit12->CurrentValue = ew_StrToFloat($this->credit12->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldo12->FormValue == $this->saldo12->CurrentValue && is_numeric(ew_StrToFloat($this->saldo12->CurrentValue)))
			$this->saldo12->CurrentValue = ew_StrToFloat($this->saldo12->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// tanggal
		// periode
		// id
		// nomor
		// transaksi
		// referensi
		// group
		// rekening
		// tipe
		// posisi
		// laporan
		// keterangan
		// debet1
		// credit1
		// saldo1
		// debet2
		// credit2
		// saldo2
		// debet3
		// credit3
		// saldo3
		// debet4
		// credit4
		// saldo4
		// debet5
		// credit5
		// saldo5
		// debet6
		// credit6
		// saldo6
		// debet7
		// credit7
		// saldo7
		// debet8
		// credit8
		// saldo8
		// debet9
		// credit9
		// saldo9
		// debet10
		// credit10
		// saldo10
		// debet11
		// credit11
		// saldo11
		// debet12
		// credit12
		// saldo12

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

		// group
		$this->group->ViewValue = $this->group->CurrentValue;
		$this->group->ViewCustomAttributes = "";

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

		// keterangan
		$this->keterangan->ViewValue = $this->keterangan->CurrentValue;
		$this->keterangan->ViewCustomAttributes = "";

		// debet1
		$this->debet1->ViewValue = $this->debet1->CurrentValue;
		$this->debet1->ViewCustomAttributes = "";

		// credit1
		$this->credit1->ViewValue = $this->credit1->CurrentValue;
		$this->credit1->ViewCustomAttributes = "";

		// saldo1
		$this->saldo1->ViewValue = $this->saldo1->CurrentValue;
		$this->saldo1->ViewCustomAttributes = "";

		// debet2
		$this->debet2->ViewValue = $this->debet2->CurrentValue;
		$this->debet2->ViewCustomAttributes = "";

		// credit2
		$this->credit2->ViewValue = $this->credit2->CurrentValue;
		$this->credit2->ViewCustomAttributes = "";

		// saldo2
		$this->saldo2->ViewValue = $this->saldo2->CurrentValue;
		$this->saldo2->ViewCustomAttributes = "";

		// debet3
		$this->debet3->ViewValue = $this->debet3->CurrentValue;
		$this->debet3->ViewCustomAttributes = "";

		// credit3
		$this->credit3->ViewValue = $this->credit3->CurrentValue;
		$this->credit3->ViewCustomAttributes = "";

		// saldo3
		$this->saldo3->ViewValue = $this->saldo3->CurrentValue;
		$this->saldo3->ViewCustomAttributes = "";

		// debet4
		$this->debet4->ViewValue = $this->debet4->CurrentValue;
		$this->debet4->ViewCustomAttributes = "";

		// credit4
		$this->credit4->ViewValue = $this->credit4->CurrentValue;
		$this->credit4->ViewCustomAttributes = "";

		// saldo4
		$this->saldo4->ViewValue = $this->saldo4->CurrentValue;
		$this->saldo4->ViewCustomAttributes = "";

		// debet5
		$this->debet5->ViewValue = $this->debet5->CurrentValue;
		$this->debet5->ViewCustomAttributes = "";

		// credit5
		$this->credit5->ViewValue = $this->credit5->CurrentValue;
		$this->credit5->ViewCustomAttributes = "";

		// saldo5
		$this->saldo5->ViewValue = $this->saldo5->CurrentValue;
		$this->saldo5->ViewCustomAttributes = "";

		// debet6
		$this->debet6->ViewValue = $this->debet6->CurrentValue;
		$this->debet6->ViewCustomAttributes = "";

		// credit6
		$this->credit6->ViewValue = $this->credit6->CurrentValue;
		$this->credit6->ViewCustomAttributes = "";

		// saldo6
		$this->saldo6->ViewValue = $this->saldo6->CurrentValue;
		$this->saldo6->ViewCustomAttributes = "";

		// debet7
		$this->debet7->ViewValue = $this->debet7->CurrentValue;
		$this->debet7->ViewCustomAttributes = "";

		// credit7
		$this->credit7->ViewValue = $this->credit7->CurrentValue;
		$this->credit7->ViewCustomAttributes = "";

		// saldo7
		$this->saldo7->ViewValue = $this->saldo7->CurrentValue;
		$this->saldo7->ViewCustomAttributes = "";

		// debet8
		$this->debet8->ViewValue = $this->debet8->CurrentValue;
		$this->debet8->ViewCustomAttributes = "";

		// credit8
		$this->credit8->ViewValue = $this->credit8->CurrentValue;
		$this->credit8->ViewCustomAttributes = "";

		// saldo8
		$this->saldo8->ViewValue = $this->saldo8->CurrentValue;
		$this->saldo8->ViewCustomAttributes = "";

		// debet9
		$this->debet9->ViewValue = $this->debet9->CurrentValue;
		$this->debet9->ViewCustomAttributes = "";

		// credit9
		$this->credit9->ViewValue = $this->credit9->CurrentValue;
		$this->credit9->ViewCustomAttributes = "";

		// saldo9
		$this->saldo9->ViewValue = $this->saldo9->CurrentValue;
		$this->saldo9->ViewCustomAttributes = "";

		// debet10
		$this->debet10->ViewValue = $this->debet10->CurrentValue;
		$this->debet10->ViewCustomAttributes = "";

		// credit10
		$this->credit10->ViewValue = $this->credit10->CurrentValue;
		$this->credit10->ViewCustomAttributes = "";

		// saldo10
		$this->saldo10->ViewValue = $this->saldo10->CurrentValue;
		$this->saldo10->ViewCustomAttributes = "";

		// debet11
		$this->debet11->ViewValue = $this->debet11->CurrentValue;
		$this->debet11->ViewCustomAttributes = "";

		// credit11
		$this->credit11->ViewValue = $this->credit11->CurrentValue;
		$this->credit11->ViewCustomAttributes = "";

		// saldo11
		$this->saldo11->ViewValue = $this->saldo11->CurrentValue;
		$this->saldo11->ViewCustomAttributes = "";

		// debet12
		$this->debet12->ViewValue = $this->debet12->CurrentValue;
		$this->debet12->ViewCustomAttributes = "";

		// credit12
		$this->credit12->ViewValue = $this->credit12->CurrentValue;
		$this->credit12->ViewCustomAttributes = "";

		// saldo12
		$this->saldo12->ViewValue = $this->saldo12->CurrentValue;
		$this->saldo12->ViewCustomAttributes = "";

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

			// group
			$this->group->LinkCustomAttributes = "";
			$this->group->HrefValue = "";
			$this->group->TooltipValue = "";

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

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";
			$this->keterangan->TooltipValue = "";

			// debet1
			$this->debet1->LinkCustomAttributes = "";
			$this->debet1->HrefValue = "";
			$this->debet1->TooltipValue = "";

			// credit1
			$this->credit1->LinkCustomAttributes = "";
			$this->credit1->HrefValue = "";
			$this->credit1->TooltipValue = "";

			// saldo1
			$this->saldo1->LinkCustomAttributes = "";
			$this->saldo1->HrefValue = "";
			$this->saldo1->TooltipValue = "";

			// debet2
			$this->debet2->LinkCustomAttributes = "";
			$this->debet2->HrefValue = "";
			$this->debet2->TooltipValue = "";

			// credit2
			$this->credit2->LinkCustomAttributes = "";
			$this->credit2->HrefValue = "";
			$this->credit2->TooltipValue = "";

			// saldo2
			$this->saldo2->LinkCustomAttributes = "";
			$this->saldo2->HrefValue = "";
			$this->saldo2->TooltipValue = "";

			// debet3
			$this->debet3->LinkCustomAttributes = "";
			$this->debet3->HrefValue = "";
			$this->debet3->TooltipValue = "";

			// credit3
			$this->credit3->LinkCustomAttributes = "";
			$this->credit3->HrefValue = "";
			$this->credit3->TooltipValue = "";

			// saldo3
			$this->saldo3->LinkCustomAttributes = "";
			$this->saldo3->HrefValue = "";
			$this->saldo3->TooltipValue = "";

			// debet4
			$this->debet4->LinkCustomAttributes = "";
			$this->debet4->HrefValue = "";
			$this->debet4->TooltipValue = "";

			// credit4
			$this->credit4->LinkCustomAttributes = "";
			$this->credit4->HrefValue = "";
			$this->credit4->TooltipValue = "";

			// saldo4
			$this->saldo4->LinkCustomAttributes = "";
			$this->saldo4->HrefValue = "";
			$this->saldo4->TooltipValue = "";

			// debet5
			$this->debet5->LinkCustomAttributes = "";
			$this->debet5->HrefValue = "";
			$this->debet5->TooltipValue = "";

			// credit5
			$this->credit5->LinkCustomAttributes = "";
			$this->credit5->HrefValue = "";
			$this->credit5->TooltipValue = "";

			// saldo5
			$this->saldo5->LinkCustomAttributes = "";
			$this->saldo5->HrefValue = "";
			$this->saldo5->TooltipValue = "";

			// debet6
			$this->debet6->LinkCustomAttributes = "";
			$this->debet6->HrefValue = "";
			$this->debet6->TooltipValue = "";

			// credit6
			$this->credit6->LinkCustomAttributes = "";
			$this->credit6->HrefValue = "";
			$this->credit6->TooltipValue = "";

			// saldo6
			$this->saldo6->LinkCustomAttributes = "";
			$this->saldo6->HrefValue = "";
			$this->saldo6->TooltipValue = "";

			// debet7
			$this->debet7->LinkCustomAttributes = "";
			$this->debet7->HrefValue = "";
			$this->debet7->TooltipValue = "";

			// credit7
			$this->credit7->LinkCustomAttributes = "";
			$this->credit7->HrefValue = "";
			$this->credit7->TooltipValue = "";

			// saldo7
			$this->saldo7->LinkCustomAttributes = "";
			$this->saldo7->HrefValue = "";
			$this->saldo7->TooltipValue = "";

			// debet8
			$this->debet8->LinkCustomAttributes = "";
			$this->debet8->HrefValue = "";
			$this->debet8->TooltipValue = "";

			// credit8
			$this->credit8->LinkCustomAttributes = "";
			$this->credit8->HrefValue = "";
			$this->credit8->TooltipValue = "";

			// saldo8
			$this->saldo8->LinkCustomAttributes = "";
			$this->saldo8->HrefValue = "";
			$this->saldo8->TooltipValue = "";

			// debet9
			$this->debet9->LinkCustomAttributes = "";
			$this->debet9->HrefValue = "";
			$this->debet9->TooltipValue = "";

			// credit9
			$this->credit9->LinkCustomAttributes = "";
			$this->credit9->HrefValue = "";
			$this->credit9->TooltipValue = "";

			// saldo9
			$this->saldo9->LinkCustomAttributes = "";
			$this->saldo9->HrefValue = "";
			$this->saldo9->TooltipValue = "";

			// debet10
			$this->debet10->LinkCustomAttributes = "";
			$this->debet10->HrefValue = "";
			$this->debet10->TooltipValue = "";

			// credit10
			$this->credit10->LinkCustomAttributes = "";
			$this->credit10->HrefValue = "";
			$this->credit10->TooltipValue = "";

			// saldo10
			$this->saldo10->LinkCustomAttributes = "";
			$this->saldo10->HrefValue = "";
			$this->saldo10->TooltipValue = "";

			// debet11
			$this->debet11->LinkCustomAttributes = "";
			$this->debet11->HrefValue = "";
			$this->debet11->TooltipValue = "";

			// credit11
			$this->credit11->LinkCustomAttributes = "";
			$this->credit11->HrefValue = "";
			$this->credit11->TooltipValue = "";

			// saldo11
			$this->saldo11->LinkCustomAttributes = "";
			$this->saldo11->HrefValue = "";
			$this->saldo11->TooltipValue = "";

			// debet12
			$this->debet12->LinkCustomAttributes = "";
			$this->debet12->HrefValue = "";
			$this->debet12->TooltipValue = "";

			// credit12
			$this->credit12->LinkCustomAttributes = "";
			$this->credit12->HrefValue = "";
			$this->credit12->TooltipValue = "";

			// saldo12
			$this->saldo12->LinkCustomAttributes = "";
			$this->saldo12->HrefValue = "";
			$this->saldo12->TooltipValue = "";
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

			// group
			$this->group->EditAttrs["class"] = "form-control";
			$this->group->EditCustomAttributes = "";
			$this->group->EditValue = ew_HtmlEncode($this->group->CurrentValue);
			$this->group->PlaceHolder = ew_RemoveHtml($this->group->FldCaption());

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

			// keterangan
			$this->keterangan->EditAttrs["class"] = "form-control";
			$this->keterangan->EditCustomAttributes = "";
			$this->keterangan->EditValue = ew_HtmlEncode($this->keterangan->CurrentValue);
			$this->keterangan->PlaceHolder = ew_RemoveHtml($this->keterangan->FldCaption());

			// debet1
			$this->debet1->EditAttrs["class"] = "form-control";
			$this->debet1->EditCustomAttributes = "";
			$this->debet1->EditValue = ew_HtmlEncode($this->debet1->CurrentValue);
			$this->debet1->PlaceHolder = ew_RemoveHtml($this->debet1->FldCaption());
			if (strval($this->debet1->EditValue) <> "" && is_numeric($this->debet1->EditValue)) $this->debet1->EditValue = ew_FormatNumber($this->debet1->EditValue, -2, -1, -2, 0);

			// credit1
			$this->credit1->EditAttrs["class"] = "form-control";
			$this->credit1->EditCustomAttributes = "";
			$this->credit1->EditValue = ew_HtmlEncode($this->credit1->CurrentValue);
			$this->credit1->PlaceHolder = ew_RemoveHtml($this->credit1->FldCaption());
			if (strval($this->credit1->EditValue) <> "" && is_numeric($this->credit1->EditValue)) $this->credit1->EditValue = ew_FormatNumber($this->credit1->EditValue, -2, -1, -2, 0);

			// saldo1
			$this->saldo1->EditAttrs["class"] = "form-control";
			$this->saldo1->EditCustomAttributes = "";
			$this->saldo1->EditValue = ew_HtmlEncode($this->saldo1->CurrentValue);
			$this->saldo1->PlaceHolder = ew_RemoveHtml($this->saldo1->FldCaption());
			if (strval($this->saldo1->EditValue) <> "" && is_numeric($this->saldo1->EditValue)) $this->saldo1->EditValue = ew_FormatNumber($this->saldo1->EditValue, -2, -1, -2, 0);

			// debet2
			$this->debet2->EditAttrs["class"] = "form-control";
			$this->debet2->EditCustomAttributes = "";
			$this->debet2->EditValue = ew_HtmlEncode($this->debet2->CurrentValue);
			$this->debet2->PlaceHolder = ew_RemoveHtml($this->debet2->FldCaption());
			if (strval($this->debet2->EditValue) <> "" && is_numeric($this->debet2->EditValue)) $this->debet2->EditValue = ew_FormatNumber($this->debet2->EditValue, -2, -1, -2, 0);

			// credit2
			$this->credit2->EditAttrs["class"] = "form-control";
			$this->credit2->EditCustomAttributes = "";
			$this->credit2->EditValue = ew_HtmlEncode($this->credit2->CurrentValue);
			$this->credit2->PlaceHolder = ew_RemoveHtml($this->credit2->FldCaption());
			if (strval($this->credit2->EditValue) <> "" && is_numeric($this->credit2->EditValue)) $this->credit2->EditValue = ew_FormatNumber($this->credit2->EditValue, -2, -1, -2, 0);

			// saldo2
			$this->saldo2->EditAttrs["class"] = "form-control";
			$this->saldo2->EditCustomAttributes = "";
			$this->saldo2->EditValue = ew_HtmlEncode($this->saldo2->CurrentValue);
			$this->saldo2->PlaceHolder = ew_RemoveHtml($this->saldo2->FldCaption());
			if (strval($this->saldo2->EditValue) <> "" && is_numeric($this->saldo2->EditValue)) $this->saldo2->EditValue = ew_FormatNumber($this->saldo2->EditValue, -2, -1, -2, 0);

			// debet3
			$this->debet3->EditAttrs["class"] = "form-control";
			$this->debet3->EditCustomAttributes = "";
			$this->debet3->EditValue = ew_HtmlEncode($this->debet3->CurrentValue);
			$this->debet3->PlaceHolder = ew_RemoveHtml($this->debet3->FldCaption());
			if (strval($this->debet3->EditValue) <> "" && is_numeric($this->debet3->EditValue)) $this->debet3->EditValue = ew_FormatNumber($this->debet3->EditValue, -2, -1, -2, 0);

			// credit3
			$this->credit3->EditAttrs["class"] = "form-control";
			$this->credit3->EditCustomAttributes = "";
			$this->credit3->EditValue = ew_HtmlEncode($this->credit3->CurrentValue);
			$this->credit3->PlaceHolder = ew_RemoveHtml($this->credit3->FldCaption());
			if (strval($this->credit3->EditValue) <> "" && is_numeric($this->credit3->EditValue)) $this->credit3->EditValue = ew_FormatNumber($this->credit3->EditValue, -2, -1, -2, 0);

			// saldo3
			$this->saldo3->EditAttrs["class"] = "form-control";
			$this->saldo3->EditCustomAttributes = "";
			$this->saldo3->EditValue = ew_HtmlEncode($this->saldo3->CurrentValue);
			$this->saldo3->PlaceHolder = ew_RemoveHtml($this->saldo3->FldCaption());
			if (strval($this->saldo3->EditValue) <> "" && is_numeric($this->saldo3->EditValue)) $this->saldo3->EditValue = ew_FormatNumber($this->saldo3->EditValue, -2, -1, -2, 0);

			// debet4
			$this->debet4->EditAttrs["class"] = "form-control";
			$this->debet4->EditCustomAttributes = "";
			$this->debet4->EditValue = ew_HtmlEncode($this->debet4->CurrentValue);
			$this->debet4->PlaceHolder = ew_RemoveHtml($this->debet4->FldCaption());
			if (strval($this->debet4->EditValue) <> "" && is_numeric($this->debet4->EditValue)) $this->debet4->EditValue = ew_FormatNumber($this->debet4->EditValue, -2, -1, -2, 0);

			// credit4
			$this->credit4->EditAttrs["class"] = "form-control";
			$this->credit4->EditCustomAttributes = "";
			$this->credit4->EditValue = ew_HtmlEncode($this->credit4->CurrentValue);
			$this->credit4->PlaceHolder = ew_RemoveHtml($this->credit4->FldCaption());
			if (strval($this->credit4->EditValue) <> "" && is_numeric($this->credit4->EditValue)) $this->credit4->EditValue = ew_FormatNumber($this->credit4->EditValue, -2, -1, -2, 0);

			// saldo4
			$this->saldo4->EditAttrs["class"] = "form-control";
			$this->saldo4->EditCustomAttributes = "";
			$this->saldo4->EditValue = ew_HtmlEncode($this->saldo4->CurrentValue);
			$this->saldo4->PlaceHolder = ew_RemoveHtml($this->saldo4->FldCaption());
			if (strval($this->saldo4->EditValue) <> "" && is_numeric($this->saldo4->EditValue)) $this->saldo4->EditValue = ew_FormatNumber($this->saldo4->EditValue, -2, -1, -2, 0);

			// debet5
			$this->debet5->EditAttrs["class"] = "form-control";
			$this->debet5->EditCustomAttributes = "";
			$this->debet5->EditValue = ew_HtmlEncode($this->debet5->CurrentValue);
			$this->debet5->PlaceHolder = ew_RemoveHtml($this->debet5->FldCaption());
			if (strval($this->debet5->EditValue) <> "" && is_numeric($this->debet5->EditValue)) $this->debet5->EditValue = ew_FormatNumber($this->debet5->EditValue, -2, -1, -2, 0);

			// credit5
			$this->credit5->EditAttrs["class"] = "form-control";
			$this->credit5->EditCustomAttributes = "";
			$this->credit5->EditValue = ew_HtmlEncode($this->credit5->CurrentValue);
			$this->credit5->PlaceHolder = ew_RemoveHtml($this->credit5->FldCaption());
			if (strval($this->credit5->EditValue) <> "" && is_numeric($this->credit5->EditValue)) $this->credit5->EditValue = ew_FormatNumber($this->credit5->EditValue, -2, -1, -2, 0);

			// saldo5
			$this->saldo5->EditAttrs["class"] = "form-control";
			$this->saldo5->EditCustomAttributes = "";
			$this->saldo5->EditValue = ew_HtmlEncode($this->saldo5->CurrentValue);
			$this->saldo5->PlaceHolder = ew_RemoveHtml($this->saldo5->FldCaption());
			if (strval($this->saldo5->EditValue) <> "" && is_numeric($this->saldo5->EditValue)) $this->saldo5->EditValue = ew_FormatNumber($this->saldo5->EditValue, -2, -1, -2, 0);

			// debet6
			$this->debet6->EditAttrs["class"] = "form-control";
			$this->debet6->EditCustomAttributes = "";
			$this->debet6->EditValue = ew_HtmlEncode($this->debet6->CurrentValue);
			$this->debet6->PlaceHolder = ew_RemoveHtml($this->debet6->FldCaption());
			if (strval($this->debet6->EditValue) <> "" && is_numeric($this->debet6->EditValue)) $this->debet6->EditValue = ew_FormatNumber($this->debet6->EditValue, -2, -1, -2, 0);

			// credit6
			$this->credit6->EditAttrs["class"] = "form-control";
			$this->credit6->EditCustomAttributes = "";
			$this->credit6->EditValue = ew_HtmlEncode($this->credit6->CurrentValue);
			$this->credit6->PlaceHolder = ew_RemoveHtml($this->credit6->FldCaption());
			if (strval($this->credit6->EditValue) <> "" && is_numeric($this->credit6->EditValue)) $this->credit6->EditValue = ew_FormatNumber($this->credit6->EditValue, -2, -1, -2, 0);

			// saldo6
			$this->saldo6->EditAttrs["class"] = "form-control";
			$this->saldo6->EditCustomAttributes = "";
			$this->saldo6->EditValue = ew_HtmlEncode($this->saldo6->CurrentValue);
			$this->saldo6->PlaceHolder = ew_RemoveHtml($this->saldo6->FldCaption());
			if (strval($this->saldo6->EditValue) <> "" && is_numeric($this->saldo6->EditValue)) $this->saldo6->EditValue = ew_FormatNumber($this->saldo6->EditValue, -2, -1, -2, 0);

			// debet7
			$this->debet7->EditAttrs["class"] = "form-control";
			$this->debet7->EditCustomAttributes = "";
			$this->debet7->EditValue = ew_HtmlEncode($this->debet7->CurrentValue);
			$this->debet7->PlaceHolder = ew_RemoveHtml($this->debet7->FldCaption());
			if (strval($this->debet7->EditValue) <> "" && is_numeric($this->debet7->EditValue)) $this->debet7->EditValue = ew_FormatNumber($this->debet7->EditValue, -2, -1, -2, 0);

			// credit7
			$this->credit7->EditAttrs["class"] = "form-control";
			$this->credit7->EditCustomAttributes = "";
			$this->credit7->EditValue = ew_HtmlEncode($this->credit7->CurrentValue);
			$this->credit7->PlaceHolder = ew_RemoveHtml($this->credit7->FldCaption());
			if (strval($this->credit7->EditValue) <> "" && is_numeric($this->credit7->EditValue)) $this->credit7->EditValue = ew_FormatNumber($this->credit7->EditValue, -2, -1, -2, 0);

			// saldo7
			$this->saldo7->EditAttrs["class"] = "form-control";
			$this->saldo7->EditCustomAttributes = "";
			$this->saldo7->EditValue = ew_HtmlEncode($this->saldo7->CurrentValue);
			$this->saldo7->PlaceHolder = ew_RemoveHtml($this->saldo7->FldCaption());
			if (strval($this->saldo7->EditValue) <> "" && is_numeric($this->saldo7->EditValue)) $this->saldo7->EditValue = ew_FormatNumber($this->saldo7->EditValue, -2, -1, -2, 0);

			// debet8
			$this->debet8->EditAttrs["class"] = "form-control";
			$this->debet8->EditCustomAttributes = "";
			$this->debet8->EditValue = ew_HtmlEncode($this->debet8->CurrentValue);
			$this->debet8->PlaceHolder = ew_RemoveHtml($this->debet8->FldCaption());
			if (strval($this->debet8->EditValue) <> "" && is_numeric($this->debet8->EditValue)) $this->debet8->EditValue = ew_FormatNumber($this->debet8->EditValue, -2, -1, -2, 0);

			// credit8
			$this->credit8->EditAttrs["class"] = "form-control";
			$this->credit8->EditCustomAttributes = "";
			$this->credit8->EditValue = ew_HtmlEncode($this->credit8->CurrentValue);
			$this->credit8->PlaceHolder = ew_RemoveHtml($this->credit8->FldCaption());
			if (strval($this->credit8->EditValue) <> "" && is_numeric($this->credit8->EditValue)) $this->credit8->EditValue = ew_FormatNumber($this->credit8->EditValue, -2, -1, -2, 0);

			// saldo8
			$this->saldo8->EditAttrs["class"] = "form-control";
			$this->saldo8->EditCustomAttributes = "";
			$this->saldo8->EditValue = ew_HtmlEncode($this->saldo8->CurrentValue);
			$this->saldo8->PlaceHolder = ew_RemoveHtml($this->saldo8->FldCaption());
			if (strval($this->saldo8->EditValue) <> "" && is_numeric($this->saldo8->EditValue)) $this->saldo8->EditValue = ew_FormatNumber($this->saldo8->EditValue, -2, -1, -2, 0);

			// debet9
			$this->debet9->EditAttrs["class"] = "form-control";
			$this->debet9->EditCustomAttributes = "";
			$this->debet9->EditValue = ew_HtmlEncode($this->debet9->CurrentValue);
			$this->debet9->PlaceHolder = ew_RemoveHtml($this->debet9->FldCaption());
			if (strval($this->debet9->EditValue) <> "" && is_numeric($this->debet9->EditValue)) $this->debet9->EditValue = ew_FormatNumber($this->debet9->EditValue, -2, -1, -2, 0);

			// credit9
			$this->credit9->EditAttrs["class"] = "form-control";
			$this->credit9->EditCustomAttributes = "";
			$this->credit9->EditValue = ew_HtmlEncode($this->credit9->CurrentValue);
			$this->credit9->PlaceHolder = ew_RemoveHtml($this->credit9->FldCaption());
			if (strval($this->credit9->EditValue) <> "" && is_numeric($this->credit9->EditValue)) $this->credit9->EditValue = ew_FormatNumber($this->credit9->EditValue, -2, -1, -2, 0);

			// saldo9
			$this->saldo9->EditAttrs["class"] = "form-control";
			$this->saldo9->EditCustomAttributes = "";
			$this->saldo9->EditValue = ew_HtmlEncode($this->saldo9->CurrentValue);
			$this->saldo9->PlaceHolder = ew_RemoveHtml($this->saldo9->FldCaption());
			if (strval($this->saldo9->EditValue) <> "" && is_numeric($this->saldo9->EditValue)) $this->saldo9->EditValue = ew_FormatNumber($this->saldo9->EditValue, -2, -1, -2, 0);

			// debet10
			$this->debet10->EditAttrs["class"] = "form-control";
			$this->debet10->EditCustomAttributes = "";
			$this->debet10->EditValue = ew_HtmlEncode($this->debet10->CurrentValue);
			$this->debet10->PlaceHolder = ew_RemoveHtml($this->debet10->FldCaption());
			if (strval($this->debet10->EditValue) <> "" && is_numeric($this->debet10->EditValue)) $this->debet10->EditValue = ew_FormatNumber($this->debet10->EditValue, -2, -1, -2, 0);

			// credit10
			$this->credit10->EditAttrs["class"] = "form-control";
			$this->credit10->EditCustomAttributes = "";
			$this->credit10->EditValue = ew_HtmlEncode($this->credit10->CurrentValue);
			$this->credit10->PlaceHolder = ew_RemoveHtml($this->credit10->FldCaption());
			if (strval($this->credit10->EditValue) <> "" && is_numeric($this->credit10->EditValue)) $this->credit10->EditValue = ew_FormatNumber($this->credit10->EditValue, -2, -1, -2, 0);

			// saldo10
			$this->saldo10->EditAttrs["class"] = "form-control";
			$this->saldo10->EditCustomAttributes = "";
			$this->saldo10->EditValue = ew_HtmlEncode($this->saldo10->CurrentValue);
			$this->saldo10->PlaceHolder = ew_RemoveHtml($this->saldo10->FldCaption());
			if (strval($this->saldo10->EditValue) <> "" && is_numeric($this->saldo10->EditValue)) $this->saldo10->EditValue = ew_FormatNumber($this->saldo10->EditValue, -2, -1, -2, 0);

			// debet11
			$this->debet11->EditAttrs["class"] = "form-control";
			$this->debet11->EditCustomAttributes = "";
			$this->debet11->EditValue = ew_HtmlEncode($this->debet11->CurrentValue);
			$this->debet11->PlaceHolder = ew_RemoveHtml($this->debet11->FldCaption());
			if (strval($this->debet11->EditValue) <> "" && is_numeric($this->debet11->EditValue)) $this->debet11->EditValue = ew_FormatNumber($this->debet11->EditValue, -2, -1, -2, 0);

			// credit11
			$this->credit11->EditAttrs["class"] = "form-control";
			$this->credit11->EditCustomAttributes = "";
			$this->credit11->EditValue = ew_HtmlEncode($this->credit11->CurrentValue);
			$this->credit11->PlaceHolder = ew_RemoveHtml($this->credit11->FldCaption());
			if (strval($this->credit11->EditValue) <> "" && is_numeric($this->credit11->EditValue)) $this->credit11->EditValue = ew_FormatNumber($this->credit11->EditValue, -2, -1, -2, 0);

			// saldo11
			$this->saldo11->EditAttrs["class"] = "form-control";
			$this->saldo11->EditCustomAttributes = "";
			$this->saldo11->EditValue = ew_HtmlEncode($this->saldo11->CurrentValue);
			$this->saldo11->PlaceHolder = ew_RemoveHtml($this->saldo11->FldCaption());
			if (strval($this->saldo11->EditValue) <> "" && is_numeric($this->saldo11->EditValue)) $this->saldo11->EditValue = ew_FormatNumber($this->saldo11->EditValue, -2, -1, -2, 0);

			// debet12
			$this->debet12->EditAttrs["class"] = "form-control";
			$this->debet12->EditCustomAttributes = "";
			$this->debet12->EditValue = ew_HtmlEncode($this->debet12->CurrentValue);
			$this->debet12->PlaceHolder = ew_RemoveHtml($this->debet12->FldCaption());
			if (strval($this->debet12->EditValue) <> "" && is_numeric($this->debet12->EditValue)) $this->debet12->EditValue = ew_FormatNumber($this->debet12->EditValue, -2, -1, -2, 0);

			// credit12
			$this->credit12->EditAttrs["class"] = "form-control";
			$this->credit12->EditCustomAttributes = "";
			$this->credit12->EditValue = ew_HtmlEncode($this->credit12->CurrentValue);
			$this->credit12->PlaceHolder = ew_RemoveHtml($this->credit12->FldCaption());
			if (strval($this->credit12->EditValue) <> "" && is_numeric($this->credit12->EditValue)) $this->credit12->EditValue = ew_FormatNumber($this->credit12->EditValue, -2, -1, -2, 0);

			// saldo12
			$this->saldo12->EditAttrs["class"] = "form-control";
			$this->saldo12->EditCustomAttributes = "";
			$this->saldo12->EditValue = ew_HtmlEncode($this->saldo12->CurrentValue);
			$this->saldo12->PlaceHolder = ew_RemoveHtml($this->saldo12->FldCaption());
			if (strval($this->saldo12->EditValue) <> "" && is_numeric($this->saldo12->EditValue)) $this->saldo12->EditValue = ew_FormatNumber($this->saldo12->EditValue, -2, -1, -2, 0);

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

			// group
			$this->group->LinkCustomAttributes = "";
			$this->group->HrefValue = "";

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

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";

			// debet1
			$this->debet1->LinkCustomAttributes = "";
			$this->debet1->HrefValue = "";

			// credit1
			$this->credit1->LinkCustomAttributes = "";
			$this->credit1->HrefValue = "";

			// saldo1
			$this->saldo1->LinkCustomAttributes = "";
			$this->saldo1->HrefValue = "";

			// debet2
			$this->debet2->LinkCustomAttributes = "";
			$this->debet2->HrefValue = "";

			// credit2
			$this->credit2->LinkCustomAttributes = "";
			$this->credit2->HrefValue = "";

			// saldo2
			$this->saldo2->LinkCustomAttributes = "";
			$this->saldo2->HrefValue = "";

			// debet3
			$this->debet3->LinkCustomAttributes = "";
			$this->debet3->HrefValue = "";

			// credit3
			$this->credit3->LinkCustomAttributes = "";
			$this->credit3->HrefValue = "";

			// saldo3
			$this->saldo3->LinkCustomAttributes = "";
			$this->saldo3->HrefValue = "";

			// debet4
			$this->debet4->LinkCustomAttributes = "";
			$this->debet4->HrefValue = "";

			// credit4
			$this->credit4->LinkCustomAttributes = "";
			$this->credit4->HrefValue = "";

			// saldo4
			$this->saldo4->LinkCustomAttributes = "";
			$this->saldo4->HrefValue = "";

			// debet5
			$this->debet5->LinkCustomAttributes = "";
			$this->debet5->HrefValue = "";

			// credit5
			$this->credit5->LinkCustomAttributes = "";
			$this->credit5->HrefValue = "";

			// saldo5
			$this->saldo5->LinkCustomAttributes = "";
			$this->saldo5->HrefValue = "";

			// debet6
			$this->debet6->LinkCustomAttributes = "";
			$this->debet6->HrefValue = "";

			// credit6
			$this->credit6->LinkCustomAttributes = "";
			$this->credit6->HrefValue = "";

			// saldo6
			$this->saldo6->LinkCustomAttributes = "";
			$this->saldo6->HrefValue = "";

			// debet7
			$this->debet7->LinkCustomAttributes = "";
			$this->debet7->HrefValue = "";

			// credit7
			$this->credit7->LinkCustomAttributes = "";
			$this->credit7->HrefValue = "";

			// saldo7
			$this->saldo7->LinkCustomAttributes = "";
			$this->saldo7->HrefValue = "";

			// debet8
			$this->debet8->LinkCustomAttributes = "";
			$this->debet8->HrefValue = "";

			// credit8
			$this->credit8->LinkCustomAttributes = "";
			$this->credit8->HrefValue = "";

			// saldo8
			$this->saldo8->LinkCustomAttributes = "";
			$this->saldo8->HrefValue = "";

			// debet9
			$this->debet9->LinkCustomAttributes = "";
			$this->debet9->HrefValue = "";

			// credit9
			$this->credit9->LinkCustomAttributes = "";
			$this->credit9->HrefValue = "";

			// saldo9
			$this->saldo9->LinkCustomAttributes = "";
			$this->saldo9->HrefValue = "";

			// debet10
			$this->debet10->LinkCustomAttributes = "";
			$this->debet10->HrefValue = "";

			// credit10
			$this->credit10->LinkCustomAttributes = "";
			$this->credit10->HrefValue = "";

			// saldo10
			$this->saldo10->LinkCustomAttributes = "";
			$this->saldo10->HrefValue = "";

			// debet11
			$this->debet11->LinkCustomAttributes = "";
			$this->debet11->HrefValue = "";

			// credit11
			$this->credit11->LinkCustomAttributes = "";
			$this->credit11->HrefValue = "";

			// saldo11
			$this->saldo11->LinkCustomAttributes = "";
			$this->saldo11->HrefValue = "";

			// debet12
			$this->debet12->LinkCustomAttributes = "";
			$this->debet12->HrefValue = "";

			// credit12
			$this->credit12->LinkCustomAttributes = "";
			$this->credit12->HrefValue = "";

			// saldo12
			$this->saldo12->LinkCustomAttributes = "";
			$this->saldo12->HrefValue = "";
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
		if (!$this->group->FldIsDetailKey && !is_null($this->group->FormValue) && $this->group->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->group->FldCaption(), $this->group->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->group->FormValue)) {
			ew_AddMessage($gsFormError, $this->group->FldErrMsg());
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
		if (!$this->keterangan->FldIsDetailKey && !is_null($this->keterangan->FormValue) && $this->keterangan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->keterangan->FldCaption(), $this->keterangan->ReqErrMsg));
		}
		if (!$this->debet1->FldIsDetailKey && !is_null($this->debet1->FormValue) && $this->debet1->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->debet1->FldCaption(), $this->debet1->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->debet1->FormValue)) {
			ew_AddMessage($gsFormError, $this->debet1->FldErrMsg());
		}
		if (!$this->credit1->FldIsDetailKey && !is_null($this->credit1->FormValue) && $this->credit1->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->credit1->FldCaption(), $this->credit1->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->credit1->FormValue)) {
			ew_AddMessage($gsFormError, $this->credit1->FldErrMsg());
		}
		if (!$this->saldo1->FldIsDetailKey && !is_null($this->saldo1->FormValue) && $this->saldo1->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->saldo1->FldCaption(), $this->saldo1->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->saldo1->FormValue)) {
			ew_AddMessage($gsFormError, $this->saldo1->FldErrMsg());
		}
		if (!$this->debet2->FldIsDetailKey && !is_null($this->debet2->FormValue) && $this->debet2->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->debet2->FldCaption(), $this->debet2->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->debet2->FormValue)) {
			ew_AddMessage($gsFormError, $this->debet2->FldErrMsg());
		}
		if (!$this->credit2->FldIsDetailKey && !is_null($this->credit2->FormValue) && $this->credit2->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->credit2->FldCaption(), $this->credit2->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->credit2->FormValue)) {
			ew_AddMessage($gsFormError, $this->credit2->FldErrMsg());
		}
		if (!$this->saldo2->FldIsDetailKey && !is_null($this->saldo2->FormValue) && $this->saldo2->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->saldo2->FldCaption(), $this->saldo2->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->saldo2->FormValue)) {
			ew_AddMessage($gsFormError, $this->saldo2->FldErrMsg());
		}
		if (!$this->debet3->FldIsDetailKey && !is_null($this->debet3->FormValue) && $this->debet3->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->debet3->FldCaption(), $this->debet3->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->debet3->FormValue)) {
			ew_AddMessage($gsFormError, $this->debet3->FldErrMsg());
		}
		if (!$this->credit3->FldIsDetailKey && !is_null($this->credit3->FormValue) && $this->credit3->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->credit3->FldCaption(), $this->credit3->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->credit3->FormValue)) {
			ew_AddMessage($gsFormError, $this->credit3->FldErrMsg());
		}
		if (!$this->saldo3->FldIsDetailKey && !is_null($this->saldo3->FormValue) && $this->saldo3->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->saldo3->FldCaption(), $this->saldo3->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->saldo3->FormValue)) {
			ew_AddMessage($gsFormError, $this->saldo3->FldErrMsg());
		}
		if (!$this->debet4->FldIsDetailKey && !is_null($this->debet4->FormValue) && $this->debet4->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->debet4->FldCaption(), $this->debet4->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->debet4->FormValue)) {
			ew_AddMessage($gsFormError, $this->debet4->FldErrMsg());
		}
		if (!$this->credit4->FldIsDetailKey && !is_null($this->credit4->FormValue) && $this->credit4->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->credit4->FldCaption(), $this->credit4->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->credit4->FormValue)) {
			ew_AddMessage($gsFormError, $this->credit4->FldErrMsg());
		}
		if (!$this->saldo4->FldIsDetailKey && !is_null($this->saldo4->FormValue) && $this->saldo4->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->saldo4->FldCaption(), $this->saldo4->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->saldo4->FormValue)) {
			ew_AddMessage($gsFormError, $this->saldo4->FldErrMsg());
		}
		if (!$this->debet5->FldIsDetailKey && !is_null($this->debet5->FormValue) && $this->debet5->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->debet5->FldCaption(), $this->debet5->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->debet5->FormValue)) {
			ew_AddMessage($gsFormError, $this->debet5->FldErrMsg());
		}
		if (!$this->credit5->FldIsDetailKey && !is_null($this->credit5->FormValue) && $this->credit5->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->credit5->FldCaption(), $this->credit5->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->credit5->FormValue)) {
			ew_AddMessage($gsFormError, $this->credit5->FldErrMsg());
		}
		if (!$this->saldo5->FldIsDetailKey && !is_null($this->saldo5->FormValue) && $this->saldo5->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->saldo5->FldCaption(), $this->saldo5->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->saldo5->FormValue)) {
			ew_AddMessage($gsFormError, $this->saldo5->FldErrMsg());
		}
		if (!$this->debet6->FldIsDetailKey && !is_null($this->debet6->FormValue) && $this->debet6->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->debet6->FldCaption(), $this->debet6->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->debet6->FormValue)) {
			ew_AddMessage($gsFormError, $this->debet6->FldErrMsg());
		}
		if (!$this->credit6->FldIsDetailKey && !is_null($this->credit6->FormValue) && $this->credit6->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->credit6->FldCaption(), $this->credit6->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->credit6->FormValue)) {
			ew_AddMessage($gsFormError, $this->credit6->FldErrMsg());
		}
		if (!$this->saldo6->FldIsDetailKey && !is_null($this->saldo6->FormValue) && $this->saldo6->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->saldo6->FldCaption(), $this->saldo6->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->saldo6->FormValue)) {
			ew_AddMessage($gsFormError, $this->saldo6->FldErrMsg());
		}
		if (!$this->debet7->FldIsDetailKey && !is_null($this->debet7->FormValue) && $this->debet7->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->debet7->FldCaption(), $this->debet7->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->debet7->FormValue)) {
			ew_AddMessage($gsFormError, $this->debet7->FldErrMsg());
		}
		if (!$this->credit7->FldIsDetailKey && !is_null($this->credit7->FormValue) && $this->credit7->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->credit7->FldCaption(), $this->credit7->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->credit7->FormValue)) {
			ew_AddMessage($gsFormError, $this->credit7->FldErrMsg());
		}
		if (!$this->saldo7->FldIsDetailKey && !is_null($this->saldo7->FormValue) && $this->saldo7->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->saldo7->FldCaption(), $this->saldo7->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->saldo7->FormValue)) {
			ew_AddMessage($gsFormError, $this->saldo7->FldErrMsg());
		}
		if (!$this->debet8->FldIsDetailKey && !is_null($this->debet8->FormValue) && $this->debet8->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->debet8->FldCaption(), $this->debet8->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->debet8->FormValue)) {
			ew_AddMessage($gsFormError, $this->debet8->FldErrMsg());
		}
		if (!$this->credit8->FldIsDetailKey && !is_null($this->credit8->FormValue) && $this->credit8->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->credit8->FldCaption(), $this->credit8->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->credit8->FormValue)) {
			ew_AddMessage($gsFormError, $this->credit8->FldErrMsg());
		}
		if (!$this->saldo8->FldIsDetailKey && !is_null($this->saldo8->FormValue) && $this->saldo8->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->saldo8->FldCaption(), $this->saldo8->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->saldo8->FormValue)) {
			ew_AddMessage($gsFormError, $this->saldo8->FldErrMsg());
		}
		if (!$this->debet9->FldIsDetailKey && !is_null($this->debet9->FormValue) && $this->debet9->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->debet9->FldCaption(), $this->debet9->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->debet9->FormValue)) {
			ew_AddMessage($gsFormError, $this->debet9->FldErrMsg());
		}
		if (!$this->credit9->FldIsDetailKey && !is_null($this->credit9->FormValue) && $this->credit9->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->credit9->FldCaption(), $this->credit9->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->credit9->FormValue)) {
			ew_AddMessage($gsFormError, $this->credit9->FldErrMsg());
		}
		if (!$this->saldo9->FldIsDetailKey && !is_null($this->saldo9->FormValue) && $this->saldo9->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->saldo9->FldCaption(), $this->saldo9->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->saldo9->FormValue)) {
			ew_AddMessage($gsFormError, $this->saldo9->FldErrMsg());
		}
		if (!$this->debet10->FldIsDetailKey && !is_null($this->debet10->FormValue) && $this->debet10->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->debet10->FldCaption(), $this->debet10->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->debet10->FormValue)) {
			ew_AddMessage($gsFormError, $this->debet10->FldErrMsg());
		}
		if (!$this->credit10->FldIsDetailKey && !is_null($this->credit10->FormValue) && $this->credit10->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->credit10->FldCaption(), $this->credit10->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->credit10->FormValue)) {
			ew_AddMessage($gsFormError, $this->credit10->FldErrMsg());
		}
		if (!$this->saldo10->FldIsDetailKey && !is_null($this->saldo10->FormValue) && $this->saldo10->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->saldo10->FldCaption(), $this->saldo10->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->saldo10->FormValue)) {
			ew_AddMessage($gsFormError, $this->saldo10->FldErrMsg());
		}
		if (!$this->debet11->FldIsDetailKey && !is_null($this->debet11->FormValue) && $this->debet11->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->debet11->FldCaption(), $this->debet11->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->debet11->FormValue)) {
			ew_AddMessage($gsFormError, $this->debet11->FldErrMsg());
		}
		if (!$this->credit11->FldIsDetailKey && !is_null($this->credit11->FormValue) && $this->credit11->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->credit11->FldCaption(), $this->credit11->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->credit11->FormValue)) {
			ew_AddMessage($gsFormError, $this->credit11->FldErrMsg());
		}
		if (!$this->saldo11->FldIsDetailKey && !is_null($this->saldo11->FormValue) && $this->saldo11->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->saldo11->FldCaption(), $this->saldo11->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->saldo11->FormValue)) {
			ew_AddMessage($gsFormError, $this->saldo11->FldErrMsg());
		}
		if (!$this->debet12->FldIsDetailKey && !is_null($this->debet12->FormValue) && $this->debet12->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->debet12->FldCaption(), $this->debet12->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->debet12->FormValue)) {
			ew_AddMessage($gsFormError, $this->debet12->FldErrMsg());
		}
		if (!$this->credit12->FldIsDetailKey && !is_null($this->credit12->FormValue) && $this->credit12->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->credit12->FldCaption(), $this->credit12->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->credit12->FormValue)) {
			ew_AddMessage($gsFormError, $this->credit12->FldErrMsg());
		}
		if (!$this->saldo12->FldIsDetailKey && !is_null($this->saldo12->FormValue) && $this->saldo12->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->saldo12->FldCaption(), $this->saldo12->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->saldo12->FormValue)) {
			ew_AddMessage($gsFormError, $this->saldo12->FldErrMsg());
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

		// group
		$this->group->SetDbValueDef($rsnew, $this->group->CurrentValue, 0, strval($this->group->CurrentValue) == "");

		// rekening
		$this->rekening->SetDbValueDef($rsnew, $this->rekening->CurrentValue, "", FALSE);

		// tipe
		$this->tipe->SetDbValueDef($rsnew, $this->tipe->CurrentValue, "", FALSE);

		// posisi
		$this->posisi->SetDbValueDef($rsnew, $this->posisi->CurrentValue, "", FALSE);

		// laporan
		$this->laporan->SetDbValueDef($rsnew, $this->laporan->CurrentValue, "", FALSE);

		// keterangan
		$this->keterangan->SetDbValueDef($rsnew, $this->keterangan->CurrentValue, "", FALSE);

		// debet1
		$this->debet1->SetDbValueDef($rsnew, $this->debet1->CurrentValue, 0, strval($this->debet1->CurrentValue) == "");

		// credit1
		$this->credit1->SetDbValueDef($rsnew, $this->credit1->CurrentValue, 0, strval($this->credit1->CurrentValue) == "");

		// saldo1
		$this->saldo1->SetDbValueDef($rsnew, $this->saldo1->CurrentValue, 0, strval($this->saldo1->CurrentValue) == "");

		// debet2
		$this->debet2->SetDbValueDef($rsnew, $this->debet2->CurrentValue, 0, strval($this->debet2->CurrentValue) == "");

		// credit2
		$this->credit2->SetDbValueDef($rsnew, $this->credit2->CurrentValue, 0, strval($this->credit2->CurrentValue) == "");

		// saldo2
		$this->saldo2->SetDbValueDef($rsnew, $this->saldo2->CurrentValue, 0, strval($this->saldo2->CurrentValue) == "");

		// debet3
		$this->debet3->SetDbValueDef($rsnew, $this->debet3->CurrentValue, 0, strval($this->debet3->CurrentValue) == "");

		// credit3
		$this->credit3->SetDbValueDef($rsnew, $this->credit3->CurrentValue, 0, strval($this->credit3->CurrentValue) == "");

		// saldo3
		$this->saldo3->SetDbValueDef($rsnew, $this->saldo3->CurrentValue, 0, strval($this->saldo3->CurrentValue) == "");

		// debet4
		$this->debet4->SetDbValueDef($rsnew, $this->debet4->CurrentValue, 0, strval($this->debet4->CurrentValue) == "");

		// credit4
		$this->credit4->SetDbValueDef($rsnew, $this->credit4->CurrentValue, 0, strval($this->credit4->CurrentValue) == "");

		// saldo4
		$this->saldo4->SetDbValueDef($rsnew, $this->saldo4->CurrentValue, 0, strval($this->saldo4->CurrentValue) == "");

		// debet5
		$this->debet5->SetDbValueDef($rsnew, $this->debet5->CurrentValue, 0, strval($this->debet5->CurrentValue) == "");

		// credit5
		$this->credit5->SetDbValueDef($rsnew, $this->credit5->CurrentValue, 0, strval($this->credit5->CurrentValue) == "");

		// saldo5
		$this->saldo5->SetDbValueDef($rsnew, $this->saldo5->CurrentValue, 0, strval($this->saldo5->CurrentValue) == "");

		// debet6
		$this->debet6->SetDbValueDef($rsnew, $this->debet6->CurrentValue, 0, strval($this->debet6->CurrentValue) == "");

		// credit6
		$this->credit6->SetDbValueDef($rsnew, $this->credit6->CurrentValue, 0, strval($this->credit6->CurrentValue) == "");

		// saldo6
		$this->saldo6->SetDbValueDef($rsnew, $this->saldo6->CurrentValue, 0, strval($this->saldo6->CurrentValue) == "");

		// debet7
		$this->debet7->SetDbValueDef($rsnew, $this->debet7->CurrentValue, 0, strval($this->debet7->CurrentValue) == "");

		// credit7
		$this->credit7->SetDbValueDef($rsnew, $this->credit7->CurrentValue, 0, strval($this->credit7->CurrentValue) == "");

		// saldo7
		$this->saldo7->SetDbValueDef($rsnew, $this->saldo7->CurrentValue, 0, strval($this->saldo7->CurrentValue) == "");

		// debet8
		$this->debet8->SetDbValueDef($rsnew, $this->debet8->CurrentValue, 0, strval($this->debet8->CurrentValue) == "");

		// credit8
		$this->credit8->SetDbValueDef($rsnew, $this->credit8->CurrentValue, 0, strval($this->credit8->CurrentValue) == "");

		// saldo8
		$this->saldo8->SetDbValueDef($rsnew, $this->saldo8->CurrentValue, 0, strval($this->saldo8->CurrentValue) == "");

		// debet9
		$this->debet9->SetDbValueDef($rsnew, $this->debet9->CurrentValue, 0, strval($this->debet9->CurrentValue) == "");

		// credit9
		$this->credit9->SetDbValueDef($rsnew, $this->credit9->CurrentValue, 0, strval($this->credit9->CurrentValue) == "");

		// saldo9
		$this->saldo9->SetDbValueDef($rsnew, $this->saldo9->CurrentValue, 0, strval($this->saldo9->CurrentValue) == "");

		// debet10
		$this->debet10->SetDbValueDef($rsnew, $this->debet10->CurrentValue, 0, strval($this->debet10->CurrentValue) == "");

		// credit10
		$this->credit10->SetDbValueDef($rsnew, $this->credit10->CurrentValue, 0, strval($this->credit10->CurrentValue) == "");

		// saldo10
		$this->saldo10->SetDbValueDef($rsnew, $this->saldo10->CurrentValue, 0, strval($this->saldo10->CurrentValue) == "");

		// debet11
		$this->debet11->SetDbValueDef($rsnew, $this->debet11->CurrentValue, 0, strval($this->debet11->CurrentValue) == "");

		// credit11
		$this->credit11->SetDbValueDef($rsnew, $this->credit11->CurrentValue, 0, strval($this->credit11->CurrentValue) == "");

		// saldo11
		$this->saldo11->SetDbValueDef($rsnew, $this->saldo11->CurrentValue, 0, strval($this->saldo11->CurrentValue) == "");

		// debet12
		$this->debet12->SetDbValueDef($rsnew, $this->debet12->CurrentValue, 0, strval($this->debet12->CurrentValue) == "");

		// credit12
		$this->credit12->SetDbValueDef($rsnew, $this->credit12->CurrentValue, 0, strval($this->credit12->CurrentValue) == "");

		// saldo12
		$this->saldo12->SetDbValueDef($rsnew, $this->saldo12->CurrentValue, 0, strval($this->saldo12->CurrentValue) == "");

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("tlaporanlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($tlaporan_add)) $tlaporan_add = new ctlaporan_add();

// Page init
$tlaporan_add->Page_Init();

// Page main
$tlaporan_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tlaporan_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = ftlaporanadd = new ew_Form("ftlaporanadd", "add");

// Validate form
ftlaporanadd.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->tanggal->FldCaption(), $tlaporan->tanggal->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tanggal");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->tanggal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_periode");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->periode->FldCaption(), $tlaporan->periode->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->id->FldCaption(), $tlaporan->id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nomor");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->nomor->FldCaption(), $tlaporan->nomor->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nomor");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->nomor->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_transaksi");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->transaksi->FldCaption(), $tlaporan->transaksi->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_referensi");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->referensi->FldCaption(), $tlaporan->referensi->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_group");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->group->FldCaption(), $tlaporan->group->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_group");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->group->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_rekening");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->rekening->FldCaption(), $tlaporan->rekening->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tipe");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->tipe->FldCaption(), $tlaporan->tipe->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_posisi");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->posisi->FldCaption(), $tlaporan->posisi->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_laporan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->laporan->FldCaption(), $tlaporan->laporan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_keterangan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->keterangan->FldCaption(), $tlaporan->keterangan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_debet1");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->debet1->FldCaption(), $tlaporan->debet1->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_debet1");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->debet1->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_credit1");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->credit1->FldCaption(), $tlaporan->credit1->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_credit1");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->credit1->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_saldo1");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->saldo1->FldCaption(), $tlaporan->saldo1->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_saldo1");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->saldo1->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_debet2");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->debet2->FldCaption(), $tlaporan->debet2->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_debet2");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->debet2->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_credit2");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->credit2->FldCaption(), $tlaporan->credit2->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_credit2");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->credit2->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_saldo2");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->saldo2->FldCaption(), $tlaporan->saldo2->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_saldo2");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->saldo2->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_debet3");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->debet3->FldCaption(), $tlaporan->debet3->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_debet3");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->debet3->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_credit3");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->credit3->FldCaption(), $tlaporan->credit3->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_credit3");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->credit3->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_saldo3");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->saldo3->FldCaption(), $tlaporan->saldo3->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_saldo3");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->saldo3->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_debet4");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->debet4->FldCaption(), $tlaporan->debet4->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_debet4");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->debet4->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_credit4");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->credit4->FldCaption(), $tlaporan->credit4->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_credit4");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->credit4->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_saldo4");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->saldo4->FldCaption(), $tlaporan->saldo4->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_saldo4");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->saldo4->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_debet5");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->debet5->FldCaption(), $tlaporan->debet5->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_debet5");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->debet5->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_credit5");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->credit5->FldCaption(), $tlaporan->credit5->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_credit5");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->credit5->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_saldo5");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->saldo5->FldCaption(), $tlaporan->saldo5->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_saldo5");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->saldo5->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_debet6");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->debet6->FldCaption(), $tlaporan->debet6->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_debet6");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->debet6->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_credit6");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->credit6->FldCaption(), $tlaporan->credit6->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_credit6");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->credit6->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_saldo6");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->saldo6->FldCaption(), $tlaporan->saldo6->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_saldo6");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->saldo6->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_debet7");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->debet7->FldCaption(), $tlaporan->debet7->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_debet7");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->debet7->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_credit7");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->credit7->FldCaption(), $tlaporan->credit7->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_credit7");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->credit7->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_saldo7");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->saldo7->FldCaption(), $tlaporan->saldo7->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_saldo7");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->saldo7->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_debet8");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->debet8->FldCaption(), $tlaporan->debet8->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_debet8");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->debet8->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_credit8");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->credit8->FldCaption(), $tlaporan->credit8->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_credit8");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->credit8->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_saldo8");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->saldo8->FldCaption(), $tlaporan->saldo8->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_saldo8");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->saldo8->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_debet9");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->debet9->FldCaption(), $tlaporan->debet9->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_debet9");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->debet9->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_credit9");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->credit9->FldCaption(), $tlaporan->credit9->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_credit9");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->credit9->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_saldo9");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->saldo9->FldCaption(), $tlaporan->saldo9->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_saldo9");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->saldo9->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_debet10");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->debet10->FldCaption(), $tlaporan->debet10->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_debet10");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->debet10->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_credit10");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->credit10->FldCaption(), $tlaporan->credit10->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_credit10");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->credit10->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_saldo10");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->saldo10->FldCaption(), $tlaporan->saldo10->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_saldo10");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->saldo10->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_debet11");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->debet11->FldCaption(), $tlaporan->debet11->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_debet11");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->debet11->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_credit11");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->credit11->FldCaption(), $tlaporan->credit11->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_credit11");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->credit11->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_saldo11");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->saldo11->FldCaption(), $tlaporan->saldo11->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_saldo11");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->saldo11->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_debet12");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->debet12->FldCaption(), $tlaporan->debet12->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_debet12");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->debet12->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_credit12");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->credit12->FldCaption(), $tlaporan->credit12->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_credit12");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->credit12->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_saldo12");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tlaporan->saldo12->FldCaption(), $tlaporan->saldo12->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_saldo12");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tlaporan->saldo12->FldErrMsg()) ?>");

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
ftlaporanadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftlaporanadd.ValidateRequired = true;
<?php } else { ?>
ftlaporanadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$tlaporan_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $tlaporan_add->ShowPageHeader(); ?>
<?php
$tlaporan_add->ShowMessage();
?>
<form name="ftlaporanadd" id="ftlaporanadd" class="<?php echo $tlaporan_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tlaporan_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tlaporan_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tlaporan">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($tlaporan_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($tlaporan->tanggal->Visible) { // tanggal ?>
	<div id="r_tanggal" class="form-group">
		<label id="elh_tlaporan_tanggal" for="x_tanggal" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->tanggal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->tanggal->CellAttributes() ?>>
<span id="el_tlaporan_tanggal">
<input type="text" data-table="tlaporan" data-field="x_tanggal" name="x_tanggal" id="x_tanggal" placeholder="<?php echo ew_HtmlEncode($tlaporan->tanggal->getPlaceHolder()) ?>" value="<?php echo $tlaporan->tanggal->EditValue ?>"<?php echo $tlaporan->tanggal->EditAttributes() ?>>
</span>
<?php echo $tlaporan->tanggal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->periode->Visible) { // periode ?>
	<div id="r_periode" class="form-group">
		<label id="elh_tlaporan_periode" for="x_periode" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->periode->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->periode->CellAttributes() ?>>
<span id="el_tlaporan_periode">
<input type="text" data-table="tlaporan" data-field="x_periode" name="x_periode" id="x_periode" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tlaporan->periode->getPlaceHolder()) ?>" value="<?php echo $tlaporan->periode->EditValue ?>"<?php echo $tlaporan->periode->EditAttributes() ?>>
</span>
<?php echo $tlaporan->periode->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_tlaporan_id" for="x_id" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->id->CellAttributes() ?>>
<span id="el_tlaporan_id">
<input type="text" data-table="tlaporan" data-field="x_id" name="x_id" id="x_id" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tlaporan->id->getPlaceHolder()) ?>" value="<?php echo $tlaporan->id->EditValue ?>"<?php echo $tlaporan->id->EditAttributes() ?>>
</span>
<?php echo $tlaporan->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->nomor->Visible) { // nomor ?>
	<div id="r_nomor" class="form-group">
		<label id="elh_tlaporan_nomor" for="x_nomor" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->nomor->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->nomor->CellAttributes() ?>>
<span id="el_tlaporan_nomor">
<input type="text" data-table="tlaporan" data-field="x_nomor" name="x_nomor" id="x_nomor" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->nomor->getPlaceHolder()) ?>" value="<?php echo $tlaporan->nomor->EditValue ?>"<?php echo $tlaporan->nomor->EditAttributes() ?>>
</span>
<?php echo $tlaporan->nomor->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->transaksi->Visible) { // transaksi ?>
	<div id="r_transaksi" class="form-group">
		<label id="elh_tlaporan_transaksi" for="x_transaksi" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->transaksi->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->transaksi->CellAttributes() ?>>
<span id="el_tlaporan_transaksi">
<input type="text" data-table="tlaporan" data-field="x_transaksi" name="x_transaksi" id="x_transaksi" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tlaporan->transaksi->getPlaceHolder()) ?>" value="<?php echo $tlaporan->transaksi->EditValue ?>"<?php echo $tlaporan->transaksi->EditAttributes() ?>>
</span>
<?php echo $tlaporan->transaksi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->referensi->Visible) { // referensi ?>
	<div id="r_referensi" class="form-group">
		<label id="elh_tlaporan_referensi" for="x_referensi" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->referensi->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->referensi->CellAttributes() ?>>
<span id="el_tlaporan_referensi">
<input type="text" data-table="tlaporan" data-field="x_referensi" name="x_referensi" id="x_referensi" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tlaporan->referensi->getPlaceHolder()) ?>" value="<?php echo $tlaporan->referensi->EditValue ?>"<?php echo $tlaporan->referensi->EditAttributes() ?>>
</span>
<?php echo $tlaporan->referensi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->group->Visible) { // group ?>
	<div id="r_group" class="form-group">
		<label id="elh_tlaporan_group" for="x_group" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->group->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->group->CellAttributes() ?>>
<span id="el_tlaporan_group">
<input type="text" data-table="tlaporan" data-field="x_group" name="x_group" id="x_group" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->group->getPlaceHolder()) ?>" value="<?php echo $tlaporan->group->EditValue ?>"<?php echo $tlaporan->group->EditAttributes() ?>>
</span>
<?php echo $tlaporan->group->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->rekening->Visible) { // rekening ?>
	<div id="r_rekening" class="form-group">
		<label id="elh_tlaporan_rekening" for="x_rekening" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->rekening->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->rekening->CellAttributes() ?>>
<span id="el_tlaporan_rekening">
<input type="text" data-table="tlaporan" data-field="x_rekening" name="x_rekening" id="x_rekening" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tlaporan->rekening->getPlaceHolder()) ?>" value="<?php echo $tlaporan->rekening->EditValue ?>"<?php echo $tlaporan->rekening->EditAttributes() ?>>
</span>
<?php echo $tlaporan->rekening->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->tipe->Visible) { // tipe ?>
	<div id="r_tipe" class="form-group">
		<label id="elh_tlaporan_tipe" for="x_tipe" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->tipe->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->tipe->CellAttributes() ?>>
<span id="el_tlaporan_tipe">
<input type="text" data-table="tlaporan" data-field="x_tipe" name="x_tipe" id="x_tipe" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tlaporan->tipe->getPlaceHolder()) ?>" value="<?php echo $tlaporan->tipe->EditValue ?>"<?php echo $tlaporan->tipe->EditAttributes() ?>>
</span>
<?php echo $tlaporan->tipe->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->posisi->Visible) { // posisi ?>
	<div id="r_posisi" class="form-group">
		<label id="elh_tlaporan_posisi" for="x_posisi" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->posisi->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->posisi->CellAttributes() ?>>
<span id="el_tlaporan_posisi">
<input type="text" data-table="tlaporan" data-field="x_posisi" name="x_posisi" id="x_posisi" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tlaporan->posisi->getPlaceHolder()) ?>" value="<?php echo $tlaporan->posisi->EditValue ?>"<?php echo $tlaporan->posisi->EditAttributes() ?>>
</span>
<?php echo $tlaporan->posisi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->laporan->Visible) { // laporan ?>
	<div id="r_laporan" class="form-group">
		<label id="elh_tlaporan_laporan" for="x_laporan" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->laporan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->laporan->CellAttributes() ?>>
<span id="el_tlaporan_laporan">
<input type="text" data-table="tlaporan" data-field="x_laporan" name="x_laporan" id="x_laporan" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tlaporan->laporan->getPlaceHolder()) ?>" value="<?php echo $tlaporan->laporan->EditValue ?>"<?php echo $tlaporan->laporan->EditAttributes() ?>>
</span>
<?php echo $tlaporan->laporan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->keterangan->Visible) { // keterangan ?>
	<div id="r_keterangan" class="form-group">
		<label id="elh_tlaporan_keterangan" for="x_keterangan" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->keterangan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->keterangan->CellAttributes() ?>>
<span id="el_tlaporan_keterangan">
<input type="text" data-table="tlaporan" data-field="x_keterangan" name="x_keterangan" id="x_keterangan" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($tlaporan->keterangan->getPlaceHolder()) ?>" value="<?php echo $tlaporan->keterangan->EditValue ?>"<?php echo $tlaporan->keterangan->EditAttributes() ?>>
</span>
<?php echo $tlaporan->keterangan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->debet1->Visible) { // debet1 ?>
	<div id="r_debet1" class="form-group">
		<label id="elh_tlaporan_debet1" for="x_debet1" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->debet1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->debet1->CellAttributes() ?>>
<span id="el_tlaporan_debet1">
<input type="text" data-table="tlaporan" data-field="x_debet1" name="x_debet1" id="x_debet1" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->debet1->getPlaceHolder()) ?>" value="<?php echo $tlaporan->debet1->EditValue ?>"<?php echo $tlaporan->debet1->EditAttributes() ?>>
</span>
<?php echo $tlaporan->debet1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->credit1->Visible) { // credit1 ?>
	<div id="r_credit1" class="form-group">
		<label id="elh_tlaporan_credit1" for="x_credit1" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->credit1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->credit1->CellAttributes() ?>>
<span id="el_tlaporan_credit1">
<input type="text" data-table="tlaporan" data-field="x_credit1" name="x_credit1" id="x_credit1" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->credit1->getPlaceHolder()) ?>" value="<?php echo $tlaporan->credit1->EditValue ?>"<?php echo $tlaporan->credit1->EditAttributes() ?>>
</span>
<?php echo $tlaporan->credit1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->saldo1->Visible) { // saldo1 ?>
	<div id="r_saldo1" class="form-group">
		<label id="elh_tlaporan_saldo1" for="x_saldo1" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->saldo1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->saldo1->CellAttributes() ?>>
<span id="el_tlaporan_saldo1">
<input type="text" data-table="tlaporan" data-field="x_saldo1" name="x_saldo1" id="x_saldo1" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->saldo1->getPlaceHolder()) ?>" value="<?php echo $tlaporan->saldo1->EditValue ?>"<?php echo $tlaporan->saldo1->EditAttributes() ?>>
</span>
<?php echo $tlaporan->saldo1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->debet2->Visible) { // debet2 ?>
	<div id="r_debet2" class="form-group">
		<label id="elh_tlaporan_debet2" for="x_debet2" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->debet2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->debet2->CellAttributes() ?>>
<span id="el_tlaporan_debet2">
<input type="text" data-table="tlaporan" data-field="x_debet2" name="x_debet2" id="x_debet2" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->debet2->getPlaceHolder()) ?>" value="<?php echo $tlaporan->debet2->EditValue ?>"<?php echo $tlaporan->debet2->EditAttributes() ?>>
</span>
<?php echo $tlaporan->debet2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->credit2->Visible) { // credit2 ?>
	<div id="r_credit2" class="form-group">
		<label id="elh_tlaporan_credit2" for="x_credit2" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->credit2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->credit2->CellAttributes() ?>>
<span id="el_tlaporan_credit2">
<input type="text" data-table="tlaporan" data-field="x_credit2" name="x_credit2" id="x_credit2" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->credit2->getPlaceHolder()) ?>" value="<?php echo $tlaporan->credit2->EditValue ?>"<?php echo $tlaporan->credit2->EditAttributes() ?>>
</span>
<?php echo $tlaporan->credit2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->saldo2->Visible) { // saldo2 ?>
	<div id="r_saldo2" class="form-group">
		<label id="elh_tlaporan_saldo2" for="x_saldo2" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->saldo2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->saldo2->CellAttributes() ?>>
<span id="el_tlaporan_saldo2">
<input type="text" data-table="tlaporan" data-field="x_saldo2" name="x_saldo2" id="x_saldo2" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->saldo2->getPlaceHolder()) ?>" value="<?php echo $tlaporan->saldo2->EditValue ?>"<?php echo $tlaporan->saldo2->EditAttributes() ?>>
</span>
<?php echo $tlaporan->saldo2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->debet3->Visible) { // debet3 ?>
	<div id="r_debet3" class="form-group">
		<label id="elh_tlaporan_debet3" for="x_debet3" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->debet3->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->debet3->CellAttributes() ?>>
<span id="el_tlaporan_debet3">
<input type="text" data-table="tlaporan" data-field="x_debet3" name="x_debet3" id="x_debet3" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->debet3->getPlaceHolder()) ?>" value="<?php echo $tlaporan->debet3->EditValue ?>"<?php echo $tlaporan->debet3->EditAttributes() ?>>
</span>
<?php echo $tlaporan->debet3->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->credit3->Visible) { // credit3 ?>
	<div id="r_credit3" class="form-group">
		<label id="elh_tlaporan_credit3" for="x_credit3" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->credit3->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->credit3->CellAttributes() ?>>
<span id="el_tlaporan_credit3">
<input type="text" data-table="tlaporan" data-field="x_credit3" name="x_credit3" id="x_credit3" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->credit3->getPlaceHolder()) ?>" value="<?php echo $tlaporan->credit3->EditValue ?>"<?php echo $tlaporan->credit3->EditAttributes() ?>>
</span>
<?php echo $tlaporan->credit3->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->saldo3->Visible) { // saldo3 ?>
	<div id="r_saldo3" class="form-group">
		<label id="elh_tlaporan_saldo3" for="x_saldo3" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->saldo3->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->saldo3->CellAttributes() ?>>
<span id="el_tlaporan_saldo3">
<input type="text" data-table="tlaporan" data-field="x_saldo3" name="x_saldo3" id="x_saldo3" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->saldo3->getPlaceHolder()) ?>" value="<?php echo $tlaporan->saldo3->EditValue ?>"<?php echo $tlaporan->saldo3->EditAttributes() ?>>
</span>
<?php echo $tlaporan->saldo3->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->debet4->Visible) { // debet4 ?>
	<div id="r_debet4" class="form-group">
		<label id="elh_tlaporan_debet4" for="x_debet4" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->debet4->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->debet4->CellAttributes() ?>>
<span id="el_tlaporan_debet4">
<input type="text" data-table="tlaporan" data-field="x_debet4" name="x_debet4" id="x_debet4" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->debet4->getPlaceHolder()) ?>" value="<?php echo $tlaporan->debet4->EditValue ?>"<?php echo $tlaporan->debet4->EditAttributes() ?>>
</span>
<?php echo $tlaporan->debet4->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->credit4->Visible) { // credit4 ?>
	<div id="r_credit4" class="form-group">
		<label id="elh_tlaporan_credit4" for="x_credit4" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->credit4->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->credit4->CellAttributes() ?>>
<span id="el_tlaporan_credit4">
<input type="text" data-table="tlaporan" data-field="x_credit4" name="x_credit4" id="x_credit4" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->credit4->getPlaceHolder()) ?>" value="<?php echo $tlaporan->credit4->EditValue ?>"<?php echo $tlaporan->credit4->EditAttributes() ?>>
</span>
<?php echo $tlaporan->credit4->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->saldo4->Visible) { // saldo4 ?>
	<div id="r_saldo4" class="form-group">
		<label id="elh_tlaporan_saldo4" for="x_saldo4" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->saldo4->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->saldo4->CellAttributes() ?>>
<span id="el_tlaporan_saldo4">
<input type="text" data-table="tlaporan" data-field="x_saldo4" name="x_saldo4" id="x_saldo4" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->saldo4->getPlaceHolder()) ?>" value="<?php echo $tlaporan->saldo4->EditValue ?>"<?php echo $tlaporan->saldo4->EditAttributes() ?>>
</span>
<?php echo $tlaporan->saldo4->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->debet5->Visible) { // debet5 ?>
	<div id="r_debet5" class="form-group">
		<label id="elh_tlaporan_debet5" for="x_debet5" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->debet5->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->debet5->CellAttributes() ?>>
<span id="el_tlaporan_debet5">
<input type="text" data-table="tlaporan" data-field="x_debet5" name="x_debet5" id="x_debet5" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->debet5->getPlaceHolder()) ?>" value="<?php echo $tlaporan->debet5->EditValue ?>"<?php echo $tlaporan->debet5->EditAttributes() ?>>
</span>
<?php echo $tlaporan->debet5->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->credit5->Visible) { // credit5 ?>
	<div id="r_credit5" class="form-group">
		<label id="elh_tlaporan_credit5" for="x_credit5" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->credit5->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->credit5->CellAttributes() ?>>
<span id="el_tlaporan_credit5">
<input type="text" data-table="tlaporan" data-field="x_credit5" name="x_credit5" id="x_credit5" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->credit5->getPlaceHolder()) ?>" value="<?php echo $tlaporan->credit5->EditValue ?>"<?php echo $tlaporan->credit5->EditAttributes() ?>>
</span>
<?php echo $tlaporan->credit5->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->saldo5->Visible) { // saldo5 ?>
	<div id="r_saldo5" class="form-group">
		<label id="elh_tlaporan_saldo5" for="x_saldo5" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->saldo5->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->saldo5->CellAttributes() ?>>
<span id="el_tlaporan_saldo5">
<input type="text" data-table="tlaporan" data-field="x_saldo5" name="x_saldo5" id="x_saldo5" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->saldo5->getPlaceHolder()) ?>" value="<?php echo $tlaporan->saldo5->EditValue ?>"<?php echo $tlaporan->saldo5->EditAttributes() ?>>
</span>
<?php echo $tlaporan->saldo5->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->debet6->Visible) { // debet6 ?>
	<div id="r_debet6" class="form-group">
		<label id="elh_tlaporan_debet6" for="x_debet6" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->debet6->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->debet6->CellAttributes() ?>>
<span id="el_tlaporan_debet6">
<input type="text" data-table="tlaporan" data-field="x_debet6" name="x_debet6" id="x_debet6" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->debet6->getPlaceHolder()) ?>" value="<?php echo $tlaporan->debet6->EditValue ?>"<?php echo $tlaporan->debet6->EditAttributes() ?>>
</span>
<?php echo $tlaporan->debet6->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->credit6->Visible) { // credit6 ?>
	<div id="r_credit6" class="form-group">
		<label id="elh_tlaporan_credit6" for="x_credit6" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->credit6->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->credit6->CellAttributes() ?>>
<span id="el_tlaporan_credit6">
<input type="text" data-table="tlaporan" data-field="x_credit6" name="x_credit6" id="x_credit6" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->credit6->getPlaceHolder()) ?>" value="<?php echo $tlaporan->credit6->EditValue ?>"<?php echo $tlaporan->credit6->EditAttributes() ?>>
</span>
<?php echo $tlaporan->credit6->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->saldo6->Visible) { // saldo6 ?>
	<div id="r_saldo6" class="form-group">
		<label id="elh_tlaporan_saldo6" for="x_saldo6" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->saldo6->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->saldo6->CellAttributes() ?>>
<span id="el_tlaporan_saldo6">
<input type="text" data-table="tlaporan" data-field="x_saldo6" name="x_saldo6" id="x_saldo6" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->saldo6->getPlaceHolder()) ?>" value="<?php echo $tlaporan->saldo6->EditValue ?>"<?php echo $tlaporan->saldo6->EditAttributes() ?>>
</span>
<?php echo $tlaporan->saldo6->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->debet7->Visible) { // debet7 ?>
	<div id="r_debet7" class="form-group">
		<label id="elh_tlaporan_debet7" for="x_debet7" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->debet7->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->debet7->CellAttributes() ?>>
<span id="el_tlaporan_debet7">
<input type="text" data-table="tlaporan" data-field="x_debet7" name="x_debet7" id="x_debet7" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->debet7->getPlaceHolder()) ?>" value="<?php echo $tlaporan->debet7->EditValue ?>"<?php echo $tlaporan->debet7->EditAttributes() ?>>
</span>
<?php echo $tlaporan->debet7->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->credit7->Visible) { // credit7 ?>
	<div id="r_credit7" class="form-group">
		<label id="elh_tlaporan_credit7" for="x_credit7" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->credit7->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->credit7->CellAttributes() ?>>
<span id="el_tlaporan_credit7">
<input type="text" data-table="tlaporan" data-field="x_credit7" name="x_credit7" id="x_credit7" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->credit7->getPlaceHolder()) ?>" value="<?php echo $tlaporan->credit7->EditValue ?>"<?php echo $tlaporan->credit7->EditAttributes() ?>>
</span>
<?php echo $tlaporan->credit7->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->saldo7->Visible) { // saldo7 ?>
	<div id="r_saldo7" class="form-group">
		<label id="elh_tlaporan_saldo7" for="x_saldo7" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->saldo7->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->saldo7->CellAttributes() ?>>
<span id="el_tlaporan_saldo7">
<input type="text" data-table="tlaporan" data-field="x_saldo7" name="x_saldo7" id="x_saldo7" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->saldo7->getPlaceHolder()) ?>" value="<?php echo $tlaporan->saldo7->EditValue ?>"<?php echo $tlaporan->saldo7->EditAttributes() ?>>
</span>
<?php echo $tlaporan->saldo7->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->debet8->Visible) { // debet8 ?>
	<div id="r_debet8" class="form-group">
		<label id="elh_tlaporan_debet8" for="x_debet8" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->debet8->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->debet8->CellAttributes() ?>>
<span id="el_tlaporan_debet8">
<input type="text" data-table="tlaporan" data-field="x_debet8" name="x_debet8" id="x_debet8" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->debet8->getPlaceHolder()) ?>" value="<?php echo $tlaporan->debet8->EditValue ?>"<?php echo $tlaporan->debet8->EditAttributes() ?>>
</span>
<?php echo $tlaporan->debet8->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->credit8->Visible) { // credit8 ?>
	<div id="r_credit8" class="form-group">
		<label id="elh_tlaporan_credit8" for="x_credit8" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->credit8->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->credit8->CellAttributes() ?>>
<span id="el_tlaporan_credit8">
<input type="text" data-table="tlaporan" data-field="x_credit8" name="x_credit8" id="x_credit8" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->credit8->getPlaceHolder()) ?>" value="<?php echo $tlaporan->credit8->EditValue ?>"<?php echo $tlaporan->credit8->EditAttributes() ?>>
</span>
<?php echo $tlaporan->credit8->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->saldo8->Visible) { // saldo8 ?>
	<div id="r_saldo8" class="form-group">
		<label id="elh_tlaporan_saldo8" for="x_saldo8" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->saldo8->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->saldo8->CellAttributes() ?>>
<span id="el_tlaporan_saldo8">
<input type="text" data-table="tlaporan" data-field="x_saldo8" name="x_saldo8" id="x_saldo8" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->saldo8->getPlaceHolder()) ?>" value="<?php echo $tlaporan->saldo8->EditValue ?>"<?php echo $tlaporan->saldo8->EditAttributes() ?>>
</span>
<?php echo $tlaporan->saldo8->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->debet9->Visible) { // debet9 ?>
	<div id="r_debet9" class="form-group">
		<label id="elh_tlaporan_debet9" for="x_debet9" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->debet9->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->debet9->CellAttributes() ?>>
<span id="el_tlaporan_debet9">
<input type="text" data-table="tlaporan" data-field="x_debet9" name="x_debet9" id="x_debet9" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->debet9->getPlaceHolder()) ?>" value="<?php echo $tlaporan->debet9->EditValue ?>"<?php echo $tlaporan->debet9->EditAttributes() ?>>
</span>
<?php echo $tlaporan->debet9->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->credit9->Visible) { // credit9 ?>
	<div id="r_credit9" class="form-group">
		<label id="elh_tlaporan_credit9" for="x_credit9" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->credit9->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->credit9->CellAttributes() ?>>
<span id="el_tlaporan_credit9">
<input type="text" data-table="tlaporan" data-field="x_credit9" name="x_credit9" id="x_credit9" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->credit9->getPlaceHolder()) ?>" value="<?php echo $tlaporan->credit9->EditValue ?>"<?php echo $tlaporan->credit9->EditAttributes() ?>>
</span>
<?php echo $tlaporan->credit9->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->saldo9->Visible) { // saldo9 ?>
	<div id="r_saldo9" class="form-group">
		<label id="elh_tlaporan_saldo9" for="x_saldo9" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->saldo9->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->saldo9->CellAttributes() ?>>
<span id="el_tlaporan_saldo9">
<input type="text" data-table="tlaporan" data-field="x_saldo9" name="x_saldo9" id="x_saldo9" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->saldo9->getPlaceHolder()) ?>" value="<?php echo $tlaporan->saldo9->EditValue ?>"<?php echo $tlaporan->saldo9->EditAttributes() ?>>
</span>
<?php echo $tlaporan->saldo9->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->debet10->Visible) { // debet10 ?>
	<div id="r_debet10" class="form-group">
		<label id="elh_tlaporan_debet10" for="x_debet10" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->debet10->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->debet10->CellAttributes() ?>>
<span id="el_tlaporan_debet10">
<input type="text" data-table="tlaporan" data-field="x_debet10" name="x_debet10" id="x_debet10" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->debet10->getPlaceHolder()) ?>" value="<?php echo $tlaporan->debet10->EditValue ?>"<?php echo $tlaporan->debet10->EditAttributes() ?>>
</span>
<?php echo $tlaporan->debet10->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->credit10->Visible) { // credit10 ?>
	<div id="r_credit10" class="form-group">
		<label id="elh_tlaporan_credit10" for="x_credit10" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->credit10->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->credit10->CellAttributes() ?>>
<span id="el_tlaporan_credit10">
<input type="text" data-table="tlaporan" data-field="x_credit10" name="x_credit10" id="x_credit10" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->credit10->getPlaceHolder()) ?>" value="<?php echo $tlaporan->credit10->EditValue ?>"<?php echo $tlaporan->credit10->EditAttributes() ?>>
</span>
<?php echo $tlaporan->credit10->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->saldo10->Visible) { // saldo10 ?>
	<div id="r_saldo10" class="form-group">
		<label id="elh_tlaporan_saldo10" for="x_saldo10" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->saldo10->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->saldo10->CellAttributes() ?>>
<span id="el_tlaporan_saldo10">
<input type="text" data-table="tlaporan" data-field="x_saldo10" name="x_saldo10" id="x_saldo10" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->saldo10->getPlaceHolder()) ?>" value="<?php echo $tlaporan->saldo10->EditValue ?>"<?php echo $tlaporan->saldo10->EditAttributes() ?>>
</span>
<?php echo $tlaporan->saldo10->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->debet11->Visible) { // debet11 ?>
	<div id="r_debet11" class="form-group">
		<label id="elh_tlaporan_debet11" for="x_debet11" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->debet11->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->debet11->CellAttributes() ?>>
<span id="el_tlaporan_debet11">
<input type="text" data-table="tlaporan" data-field="x_debet11" name="x_debet11" id="x_debet11" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->debet11->getPlaceHolder()) ?>" value="<?php echo $tlaporan->debet11->EditValue ?>"<?php echo $tlaporan->debet11->EditAttributes() ?>>
</span>
<?php echo $tlaporan->debet11->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->credit11->Visible) { // credit11 ?>
	<div id="r_credit11" class="form-group">
		<label id="elh_tlaporan_credit11" for="x_credit11" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->credit11->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->credit11->CellAttributes() ?>>
<span id="el_tlaporan_credit11">
<input type="text" data-table="tlaporan" data-field="x_credit11" name="x_credit11" id="x_credit11" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->credit11->getPlaceHolder()) ?>" value="<?php echo $tlaporan->credit11->EditValue ?>"<?php echo $tlaporan->credit11->EditAttributes() ?>>
</span>
<?php echo $tlaporan->credit11->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->saldo11->Visible) { // saldo11 ?>
	<div id="r_saldo11" class="form-group">
		<label id="elh_tlaporan_saldo11" for="x_saldo11" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->saldo11->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->saldo11->CellAttributes() ?>>
<span id="el_tlaporan_saldo11">
<input type="text" data-table="tlaporan" data-field="x_saldo11" name="x_saldo11" id="x_saldo11" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->saldo11->getPlaceHolder()) ?>" value="<?php echo $tlaporan->saldo11->EditValue ?>"<?php echo $tlaporan->saldo11->EditAttributes() ?>>
</span>
<?php echo $tlaporan->saldo11->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->debet12->Visible) { // debet12 ?>
	<div id="r_debet12" class="form-group">
		<label id="elh_tlaporan_debet12" for="x_debet12" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->debet12->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->debet12->CellAttributes() ?>>
<span id="el_tlaporan_debet12">
<input type="text" data-table="tlaporan" data-field="x_debet12" name="x_debet12" id="x_debet12" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->debet12->getPlaceHolder()) ?>" value="<?php echo $tlaporan->debet12->EditValue ?>"<?php echo $tlaporan->debet12->EditAttributes() ?>>
</span>
<?php echo $tlaporan->debet12->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->credit12->Visible) { // credit12 ?>
	<div id="r_credit12" class="form-group">
		<label id="elh_tlaporan_credit12" for="x_credit12" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->credit12->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->credit12->CellAttributes() ?>>
<span id="el_tlaporan_credit12">
<input type="text" data-table="tlaporan" data-field="x_credit12" name="x_credit12" id="x_credit12" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->credit12->getPlaceHolder()) ?>" value="<?php echo $tlaporan->credit12->EditValue ?>"<?php echo $tlaporan->credit12->EditAttributes() ?>>
</span>
<?php echo $tlaporan->credit12->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tlaporan->saldo12->Visible) { // saldo12 ?>
	<div id="r_saldo12" class="form-group">
		<label id="elh_tlaporan_saldo12" for="x_saldo12" class="col-sm-2 control-label ewLabel"><?php echo $tlaporan->saldo12->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tlaporan->saldo12->CellAttributes() ?>>
<span id="el_tlaporan_saldo12">
<input type="text" data-table="tlaporan" data-field="x_saldo12" name="x_saldo12" id="x_saldo12" size="30" placeholder="<?php echo ew_HtmlEncode($tlaporan->saldo12->getPlaceHolder()) ?>" value="<?php echo $tlaporan->saldo12->EditValue ?>"<?php echo $tlaporan->saldo12->EditAttributes() ?>>
</span>
<?php echo $tlaporan->saldo12->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$tlaporan_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $tlaporan_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ftlaporanadd.Init();
</script>
<?php
$tlaporan_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tlaporan_add->Page_Terminate();
?>
