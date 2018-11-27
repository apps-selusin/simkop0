<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "tpinjamandetailinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$tpinjamandetail_edit = NULL; // Initialize page object first

class ctpinjamandetail_edit extends ctpinjamandetail {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tpinjamandetail';

	// Page object name
	var $PageObjName = 'tpinjamandetail_edit';

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

		// Table object (tpinjamandetail)
		if (!isset($GLOBALS["tpinjamandetail"]) || get_class($GLOBALS["tpinjamandetail"]) == "ctpinjamandetail") {
			$GLOBALS["tpinjamandetail"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tpinjamandetail"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tpinjamandetail', TRUE);

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
		$this->angsuran->SetVisibility();
		$this->masaangsuran->SetVisibility();
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
		$this->tanggalbayar->SetVisibility();
		$this->terlambat->SetVisibility();
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
		$this->status->SetVisibility();
		$this->keterangan->SetVisibility();

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
		global $EW_EXPORT, $tpinjamandetail;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($tpinjamandetail);
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
		if (@$_GET["angsuran"] <> "") {
			$this->angsuran->setQueryStringValue($_GET["angsuran"]);
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
			$this->Page_Terminate("tpinjamandetaillist.php"); // Invalid key, return to list
		}
		if ($this->angsuran->CurrentValue == "") {
			$this->Page_Terminate("tpinjamandetaillist.php"); // Invalid key, return to list
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
					$this->Page_Terminate("tpinjamandetaillist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "tpinjamandetaillist.php")
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
		if (!$this->angsuran->FldIsDetailKey) {
			$this->angsuran->setFormValue($objForm->GetValue("x_angsuran"));
		}
		if (!$this->masaangsuran->FldIsDetailKey) {
			$this->masaangsuran->setFormValue($objForm->GetValue("x_masaangsuran"));
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
		if (!$this->tanggalbayar->FldIsDetailKey) {
			$this->tanggalbayar->setFormValue($objForm->GetValue("x_tanggalbayar"));
			$this->tanggalbayar->CurrentValue = ew_UnFormatDateTime($this->tanggalbayar->CurrentValue, 0);
		}
		if (!$this->terlambat->FldIsDetailKey) {
			$this->terlambat->setFormValue($objForm->GetValue("x_terlambat"));
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
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
		if (!$this->keterangan->FldIsDetailKey) {
			$this->keterangan->setFormValue($objForm->GetValue("x_keterangan"));
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
		$this->angsuran->CurrentValue = $this->angsuran->FormValue;
		$this->masaangsuran->CurrentValue = $this->masaangsuran->FormValue;
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
		$this->tanggalbayar->CurrentValue = $this->tanggalbayar->FormValue;
		$this->tanggalbayar->CurrentValue = ew_UnFormatDateTime($this->tanggalbayar->CurrentValue, 0);
		$this->terlambat->CurrentValue = $this->terlambat->FormValue;
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
		$this->status->CurrentValue = $this->status->FormValue;
		$this->keterangan->CurrentValue = $this->keterangan->FormValue;
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
		$this->angsuran->setDbValue($rs->fields('angsuran'));
		$this->masaangsuran->setDbValue($rs->fields('masaangsuran'));
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
		$this->tanggalbayar->setDbValue($rs->fields('tanggalbayar'));
		$this->terlambat->setDbValue($rs->fields('terlambat'));
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
		$this->status->setDbValue($rs->fields('status'));
		$this->keterangan->setDbValue($rs->fields('keterangan'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->tanggal->DbValue = $row['tanggal'];
		$this->periode->DbValue = $row['periode'];
		$this->id->DbValue = $row['id'];
		$this->angsuran->DbValue = $row['angsuran'];
		$this->masaangsuran->DbValue = $row['masaangsuran'];
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
		$this->tanggalbayar->DbValue = $row['tanggalbayar'];
		$this->terlambat->DbValue = $row['terlambat'];
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
		$this->status->DbValue = $row['status'];
		$this->keterangan->DbValue = $row['keterangan'];
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
		// angsuran
		// masaangsuran
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
		// tanggalbayar
		// terlambat
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
		// status
		// keterangan

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

		// angsuran
		$this->angsuran->ViewValue = $this->angsuran->CurrentValue;
		$this->angsuran->ViewCustomAttributes = "";

		// masaangsuran
		$this->masaangsuran->ViewValue = $this->masaangsuran->CurrentValue;
		$this->masaangsuran->ViewCustomAttributes = "";

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

		// tanggalbayar
		$this->tanggalbayar->ViewValue = $this->tanggalbayar->CurrentValue;
		$this->tanggalbayar->ViewValue = ew_FormatDateTime($this->tanggalbayar->ViewValue, 0);
		$this->tanggalbayar->ViewCustomAttributes = "";

		// terlambat
		$this->terlambat->ViewValue = $this->terlambat->CurrentValue;
		$this->terlambat->ViewCustomAttributes = "";

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

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		$this->status->ViewCustomAttributes = "";

		// keterangan
		$this->keterangan->ViewValue = $this->keterangan->CurrentValue;
		$this->keterangan->ViewCustomAttributes = "";

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

			// angsuran
			$this->angsuran->LinkCustomAttributes = "";
			$this->angsuran->HrefValue = "";
			$this->angsuran->TooltipValue = "";

			// masaangsuran
			$this->masaangsuran->LinkCustomAttributes = "";
			$this->masaangsuran->HrefValue = "";
			$this->masaangsuran->TooltipValue = "";

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

			// tanggalbayar
			$this->tanggalbayar->LinkCustomAttributes = "";
			$this->tanggalbayar->HrefValue = "";
			$this->tanggalbayar->TooltipValue = "";

			// terlambat
			$this->terlambat->LinkCustomAttributes = "";
			$this->terlambat->HrefValue = "";
			$this->terlambat->TooltipValue = "";

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

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";
			$this->keterangan->TooltipValue = "";
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

			// angsuran
			$this->angsuran->EditAttrs["class"] = "form-control";
			$this->angsuran->EditCustomAttributes = "";
			$this->angsuran->EditValue = $this->angsuran->CurrentValue;
			$this->angsuran->ViewCustomAttributes = "";

			// masaangsuran
			$this->masaangsuran->EditAttrs["class"] = "form-control";
			$this->masaangsuran->EditCustomAttributes = "";
			$this->masaangsuran->EditValue = ew_HtmlEncode($this->masaangsuran->CurrentValue);
			$this->masaangsuran->PlaceHolder = ew_RemoveHtml($this->masaangsuran->FldCaption());

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

			// tanggalbayar
			$this->tanggalbayar->EditAttrs["class"] = "form-control";
			$this->tanggalbayar->EditCustomAttributes = "";
			$this->tanggalbayar->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tanggalbayar->CurrentValue, 8));
			$this->tanggalbayar->PlaceHolder = ew_RemoveHtml($this->tanggalbayar->FldCaption());

			// terlambat
			$this->terlambat->EditAttrs["class"] = "form-control";
			$this->terlambat->EditCustomAttributes = "";
			$this->terlambat->EditValue = ew_HtmlEncode($this->terlambat->CurrentValue);
			$this->terlambat->PlaceHolder = ew_RemoveHtml($this->terlambat->FldCaption());

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

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = ew_HtmlEncode($this->status->CurrentValue);
			$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

			// keterangan
			$this->keterangan->EditAttrs["class"] = "form-control";
			$this->keterangan->EditCustomAttributes = "";
			$this->keterangan->EditValue = ew_HtmlEncode($this->keterangan->CurrentValue);
			$this->keterangan->PlaceHolder = ew_RemoveHtml($this->keterangan->FldCaption());

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

			// angsuran
			$this->angsuran->LinkCustomAttributes = "";
			$this->angsuran->HrefValue = "";

			// masaangsuran
			$this->masaangsuran->LinkCustomAttributes = "";
			$this->masaangsuran->HrefValue = "";

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

			// tanggalbayar
			$this->tanggalbayar->LinkCustomAttributes = "";
			$this->tanggalbayar->HrefValue = "";

			// terlambat
			$this->terlambat->LinkCustomAttributes = "";
			$this->terlambat->HrefValue = "";

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

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";
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
		if (!$this->angsuran->FldIsDetailKey && !is_null($this->angsuran->FormValue) && $this->angsuran->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->angsuran->FldCaption(), $this->angsuran->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->angsuran->FormValue)) {
			ew_AddMessage($gsFormError, $this->angsuran->FldErrMsg());
		}
		if (!$this->masaangsuran->FldIsDetailKey && !is_null($this->masaangsuran->FormValue) && $this->masaangsuran->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->masaangsuran->FldCaption(), $this->masaangsuran->ReqErrMsg));
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
		if (!$this->tanggalbayar->FldIsDetailKey && !is_null($this->tanggalbayar->FormValue) && $this->tanggalbayar->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tanggalbayar->FldCaption(), $this->tanggalbayar->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->tanggalbayar->FormValue)) {
			ew_AddMessage($gsFormError, $this->tanggalbayar->FldErrMsg());
		}
		if (!$this->terlambat->FldIsDetailKey && !is_null($this->terlambat->FormValue) && $this->terlambat->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->terlambat->FldCaption(), $this->terlambat->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->terlambat->FormValue)) {
			ew_AddMessage($gsFormError, $this->terlambat->FldErrMsg());
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
		if (!$this->status->FldIsDetailKey && !is_null($this->status->FormValue) && $this->status->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->status->FldCaption(), $this->status->ReqErrMsg));
		}
		if (!$this->keterangan->FldIsDetailKey && !is_null($this->keterangan->FormValue) && $this->keterangan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->keterangan->FldCaption(), $this->keterangan->ReqErrMsg));
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
			// angsuran
			// masaangsuran

			$this->masaangsuran->SetDbValueDef($rsnew, $this->masaangsuran->CurrentValue, "", $this->masaangsuran->ReadOnly);

			// dispensasidenda
			$this->dispensasidenda->SetDbValueDef($rsnew, $this->dispensasidenda->CurrentValue, 0, $this->dispensasidenda->ReadOnly);

			// plafond
			$this->plafond->SetDbValueDef($rsnew, $this->plafond->CurrentValue, 0, $this->plafond->ReadOnly);

			// angsuranpokok
			$this->angsuranpokok->SetDbValueDef($rsnew, $this->angsuranpokok->CurrentValue, 0, $this->angsuranpokok->ReadOnly);

			// angsuranpokokauto
			$this->angsuranpokokauto->SetDbValueDef($rsnew, $this->angsuranpokokauto->CurrentValue, 0, $this->angsuranpokokauto->ReadOnly);

			// angsuranbunga
			$this->angsuranbunga->SetDbValueDef($rsnew, $this->angsuranbunga->CurrentValue, 0, $this->angsuranbunga->ReadOnly);

			// angsuranbungaauto
			$this->angsuranbungaauto->SetDbValueDef($rsnew, $this->angsuranbungaauto->CurrentValue, 0, $this->angsuranbungaauto->ReadOnly);

			// denda
			$this->denda->SetDbValueDef($rsnew, $this->denda->CurrentValue, 0, $this->denda->ReadOnly);

			// dendapersen
			$this->dendapersen->SetDbValueDef($rsnew, $this->dendapersen->CurrentValue, 0, $this->dendapersen->ReadOnly);

			// totalangsuran
			$this->totalangsuran->SetDbValueDef($rsnew, $this->totalangsuran->CurrentValue, 0, $this->totalangsuran->ReadOnly);

			// totalangsuranauto
			$this->totalangsuranauto->SetDbValueDef($rsnew, $this->totalangsuranauto->CurrentValue, 0, $this->totalangsuranauto->ReadOnly);

			// sisaangsuran
			$this->sisaangsuran->SetDbValueDef($rsnew, $this->sisaangsuran->CurrentValue, 0, $this->sisaangsuran->ReadOnly);

			// sisaangsuranauto
			$this->sisaangsuranauto->SetDbValueDef($rsnew, $this->sisaangsuranauto->CurrentValue, 0, $this->sisaangsuranauto->ReadOnly);

			// tanggalbayar
			$this->tanggalbayar->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tanggalbayar->CurrentValue, 0), ew_CurrentDate(), $this->tanggalbayar->ReadOnly);

			// terlambat
			$this->terlambat->SetDbValueDef($rsnew, $this->terlambat->CurrentValue, 0, $this->terlambat->ReadOnly);

			// bayarpokok
			$this->bayarpokok->SetDbValueDef($rsnew, $this->bayarpokok->CurrentValue, 0, $this->bayarpokok->ReadOnly);

			// bayarpokokauto
			$this->bayarpokokauto->SetDbValueDef($rsnew, $this->bayarpokokauto->CurrentValue, 0, $this->bayarpokokauto->ReadOnly);

			// bayarbunga
			$this->bayarbunga->SetDbValueDef($rsnew, $this->bayarbunga->CurrentValue, 0, $this->bayarbunga->ReadOnly);

			// bayarbungaauto
			$this->bayarbungaauto->SetDbValueDef($rsnew, $this->bayarbungaauto->CurrentValue, 0, $this->bayarbungaauto->ReadOnly);

			// bayardenda
			$this->bayardenda->SetDbValueDef($rsnew, $this->bayardenda->CurrentValue, 0, $this->bayardenda->ReadOnly);

			// bayardendaauto
			$this->bayardendaauto->SetDbValueDef($rsnew, $this->bayardendaauto->CurrentValue, 0, $this->bayardendaauto->ReadOnly);

			// bayartitipan
			$this->bayartitipan->SetDbValueDef($rsnew, $this->bayartitipan->CurrentValue, 0, $this->bayartitipan->ReadOnly);

			// bayartitipanauto
			$this->bayartitipanauto->SetDbValueDef($rsnew, $this->bayartitipanauto->CurrentValue, 0, $this->bayartitipanauto->ReadOnly);

			// totalbayar
			$this->totalbayar->SetDbValueDef($rsnew, $this->totalbayar->CurrentValue, 0, $this->totalbayar->ReadOnly);

			// totalbayarauto
			$this->totalbayarauto->SetDbValueDef($rsnew, $this->totalbayarauto->CurrentValue, 0, $this->totalbayarauto->ReadOnly);

			// pelunasan
			$this->pelunasan->SetDbValueDef($rsnew, $this->pelunasan->CurrentValue, 0, $this->pelunasan->ReadOnly);

			// pelunasanauto
			$this->pelunasanauto->SetDbValueDef($rsnew, $this->pelunasanauto->CurrentValue, 0, $this->pelunasanauto->ReadOnly);

			// finalty
			$this->finalty->SetDbValueDef($rsnew, $this->finalty->CurrentValue, 0, $this->finalty->ReadOnly);

			// finaltyauto
			$this->finaltyauto->SetDbValueDef($rsnew, $this->finaltyauto->CurrentValue, 0, $this->finaltyauto->ReadOnly);

			// status
			$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, "", $this->status->ReadOnly);

			// keterangan
			$this->keterangan->SetDbValueDef($rsnew, $this->keterangan->CurrentValue, "", $this->keterangan->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("tpinjamandetaillist.php"), "", $this->TableVar, TRUE);
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
if (!isset($tpinjamandetail_edit)) $tpinjamandetail_edit = new ctpinjamandetail_edit();

// Page init
$tpinjamandetail_edit->Page_Init();

// Page main
$tpinjamandetail_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tpinjamandetail_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = ftpinjamandetailedit = new ew_Form("ftpinjamandetailedit", "edit");

// Validate form
ftpinjamandetailedit.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->tanggal->FldCaption(), $tpinjamandetail->tanggal->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tanggal");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail->tanggal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_periode");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->periode->FldCaption(), $tpinjamandetail->periode->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->id->FldCaption(), $tpinjamandetail->id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_angsuran");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->angsuran->FldCaption(), $tpinjamandetail->angsuran->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_angsuran");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail->angsuran->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_masaangsuran");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->masaangsuran->FldCaption(), $tpinjamandetail->masaangsuran->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dispensasidenda");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->dispensasidenda->FldCaption(), $tpinjamandetail->dispensasidenda->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dispensasidenda");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail->dispensasidenda->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_plafond");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->plafond->FldCaption(), $tpinjamandetail->plafond->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_plafond");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail->plafond->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_angsuranpokok");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->angsuranpokok->FldCaption(), $tpinjamandetail->angsuranpokok->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_angsuranpokok");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail->angsuranpokok->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_angsuranpokokauto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->angsuranpokokauto->FldCaption(), $tpinjamandetail->angsuranpokokauto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_angsuranpokokauto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail->angsuranpokokauto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_angsuranbunga");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->angsuranbunga->FldCaption(), $tpinjamandetail->angsuranbunga->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_angsuranbunga");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail->angsuranbunga->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_angsuranbungaauto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->angsuranbungaauto->FldCaption(), $tpinjamandetail->angsuranbungaauto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_angsuranbungaauto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail->angsuranbungaauto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_denda");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->denda->FldCaption(), $tpinjamandetail->denda->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_denda");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail->denda->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dendapersen");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->dendapersen->FldCaption(), $tpinjamandetail->dendapersen->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_dendapersen");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail->dendapersen->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_totalangsuran");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->totalangsuran->FldCaption(), $tpinjamandetail->totalangsuran->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_totalangsuran");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail->totalangsuran->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_totalangsuranauto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->totalangsuranauto->FldCaption(), $tpinjamandetail->totalangsuranauto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_totalangsuranauto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail->totalangsuranauto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_sisaangsuran");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->sisaangsuran->FldCaption(), $tpinjamandetail->sisaangsuran->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_sisaangsuran");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail->sisaangsuran->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_sisaangsuranauto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->sisaangsuranauto->FldCaption(), $tpinjamandetail->sisaangsuranauto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_sisaangsuranauto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail->sisaangsuranauto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tanggalbayar");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->tanggalbayar->FldCaption(), $tpinjamandetail->tanggalbayar->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tanggalbayar");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail->tanggalbayar->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_terlambat");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->terlambat->FldCaption(), $tpinjamandetail->terlambat->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_terlambat");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail->terlambat->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_bayarpokok");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->bayarpokok->FldCaption(), $tpinjamandetail->bayarpokok->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bayarpokok");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail->bayarpokok->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_bayarpokokauto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->bayarpokokauto->FldCaption(), $tpinjamandetail->bayarpokokauto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bayarpokokauto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail->bayarpokokauto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_bayarbunga");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->bayarbunga->FldCaption(), $tpinjamandetail->bayarbunga->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bayarbunga");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail->bayarbunga->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_bayarbungaauto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->bayarbungaauto->FldCaption(), $tpinjamandetail->bayarbungaauto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bayarbungaauto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail->bayarbungaauto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_bayardenda");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->bayardenda->FldCaption(), $tpinjamandetail->bayardenda->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bayardenda");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail->bayardenda->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_bayardendaauto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->bayardendaauto->FldCaption(), $tpinjamandetail->bayardendaauto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bayardendaauto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail->bayardendaauto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_bayartitipan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->bayartitipan->FldCaption(), $tpinjamandetail->bayartitipan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bayartitipan");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail->bayartitipan->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_bayartitipanauto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->bayartitipanauto->FldCaption(), $tpinjamandetail->bayartitipanauto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bayartitipanauto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail->bayartitipanauto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_totalbayar");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->totalbayar->FldCaption(), $tpinjamandetail->totalbayar->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_totalbayar");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail->totalbayar->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_totalbayarauto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->totalbayarauto->FldCaption(), $tpinjamandetail->totalbayarauto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_totalbayarauto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail->totalbayarauto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_pelunasan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->pelunasan->FldCaption(), $tpinjamandetail->pelunasan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pelunasan");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail->pelunasan->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_pelunasanauto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->pelunasanauto->FldCaption(), $tpinjamandetail->pelunasanauto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pelunasanauto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail->pelunasanauto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_finalty");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->finalty->FldCaption(), $tpinjamandetail->finalty->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_finalty");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail->finalty->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_finaltyauto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->finaltyauto->FldCaption(), $tpinjamandetail->finaltyauto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_finaltyauto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail->finaltyauto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->status->FldCaption(), $tpinjamandetail->status->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_keterangan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail->keterangan->FldCaption(), $tpinjamandetail->keterangan->ReqErrMsg)) ?>");

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
ftpinjamandetailedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftpinjamandetailedit.ValidateRequired = true;
<?php } else { ?>
ftpinjamandetailedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$tpinjamandetail_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $tpinjamandetail_edit->ShowPageHeader(); ?>
<?php
$tpinjamandetail_edit->ShowMessage();
?>
<form name="ftpinjamandetailedit" id="ftpinjamandetailedit" class="<?php echo $tpinjamandetail_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tpinjamandetail_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tpinjamandetail_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tpinjamandetail">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($tpinjamandetail_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($tpinjamandetail->tanggal->Visible) { // tanggal ?>
	<div id="r_tanggal" class="form-group">
		<label id="elh_tpinjamandetail_tanggal" for="x_tanggal" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->tanggal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->tanggal->CellAttributes() ?>>
<span id="el_tpinjamandetail_tanggal">
<input type="text" data-table="tpinjamandetail" data-field="x_tanggal" name="x_tanggal" id="x_tanggal" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->tanggal->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->tanggal->EditValue ?>"<?php echo $tpinjamandetail->tanggal->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->tanggal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->periode->Visible) { // periode ?>
	<div id="r_periode" class="form-group">
		<label id="elh_tpinjamandetail_periode" for="x_periode" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->periode->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->periode->CellAttributes() ?>>
<span id="el_tpinjamandetail_periode">
<input type="text" data-table="tpinjamandetail" data-field="x_periode" name="x_periode" id="x_periode" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->periode->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->periode->EditValue ?>"<?php echo $tpinjamandetail->periode->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->periode->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_tpinjamandetail_id" for="x_id" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->id->CellAttributes() ?>>
<span id="el_tpinjamandetail_id">
<span<?php echo $tpinjamandetail->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $tpinjamandetail->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="tpinjamandetail" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($tpinjamandetail->id->CurrentValue) ?>">
<?php echo $tpinjamandetail->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->angsuran->Visible) { // angsuran ?>
	<div id="r_angsuran" class="form-group">
		<label id="elh_tpinjamandetail_angsuran" for="x_angsuran" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->angsuran->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->angsuran->CellAttributes() ?>>
<span id="el_tpinjamandetail_angsuran">
<span<?php echo $tpinjamandetail->angsuran->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $tpinjamandetail->angsuran->EditValue ?></p></span>
</span>
<input type="hidden" data-table="tpinjamandetail" data-field="x_angsuran" name="x_angsuran" id="x_angsuran" value="<?php echo ew_HtmlEncode($tpinjamandetail->angsuran->CurrentValue) ?>">
<?php echo $tpinjamandetail->angsuran->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->masaangsuran->Visible) { // masaangsuran ?>
	<div id="r_masaangsuran" class="form-group">
		<label id="elh_tpinjamandetail_masaangsuran" for="x_masaangsuran" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->masaangsuran->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->masaangsuran->CellAttributes() ?>>
<span id="el_tpinjamandetail_masaangsuran">
<input type="text" data-table="tpinjamandetail" data-field="x_masaangsuran" name="x_masaangsuran" id="x_masaangsuran" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->masaangsuran->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->masaangsuran->EditValue ?>"<?php echo $tpinjamandetail->masaangsuran->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->masaangsuran->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->dispensasidenda->Visible) { // dispensasidenda ?>
	<div id="r_dispensasidenda" class="form-group">
		<label id="elh_tpinjamandetail_dispensasidenda" for="x_dispensasidenda" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->dispensasidenda->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->dispensasidenda->CellAttributes() ?>>
<span id="el_tpinjamandetail_dispensasidenda">
<input type="text" data-table="tpinjamandetail" data-field="x_dispensasidenda" name="x_dispensasidenda" id="x_dispensasidenda" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->dispensasidenda->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->dispensasidenda->EditValue ?>"<?php echo $tpinjamandetail->dispensasidenda->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->dispensasidenda->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->plafond->Visible) { // plafond ?>
	<div id="r_plafond" class="form-group">
		<label id="elh_tpinjamandetail_plafond" for="x_plafond" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->plafond->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->plafond->CellAttributes() ?>>
<span id="el_tpinjamandetail_plafond">
<input type="text" data-table="tpinjamandetail" data-field="x_plafond" name="x_plafond" id="x_plafond" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->plafond->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->plafond->EditValue ?>"<?php echo $tpinjamandetail->plafond->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->plafond->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->angsuranpokok->Visible) { // angsuranpokok ?>
	<div id="r_angsuranpokok" class="form-group">
		<label id="elh_tpinjamandetail_angsuranpokok" for="x_angsuranpokok" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->angsuranpokok->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->angsuranpokok->CellAttributes() ?>>
<span id="el_tpinjamandetail_angsuranpokok">
<input type="text" data-table="tpinjamandetail" data-field="x_angsuranpokok" name="x_angsuranpokok" id="x_angsuranpokok" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->angsuranpokok->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->angsuranpokok->EditValue ?>"<?php echo $tpinjamandetail->angsuranpokok->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->angsuranpokok->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->angsuranpokokauto->Visible) { // angsuranpokokauto ?>
	<div id="r_angsuranpokokauto" class="form-group">
		<label id="elh_tpinjamandetail_angsuranpokokauto" for="x_angsuranpokokauto" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->angsuranpokokauto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->angsuranpokokauto->CellAttributes() ?>>
<span id="el_tpinjamandetail_angsuranpokokauto">
<input type="text" data-table="tpinjamandetail" data-field="x_angsuranpokokauto" name="x_angsuranpokokauto" id="x_angsuranpokokauto" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->angsuranpokokauto->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->angsuranpokokauto->EditValue ?>"<?php echo $tpinjamandetail->angsuranpokokauto->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->angsuranpokokauto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->angsuranbunga->Visible) { // angsuranbunga ?>
	<div id="r_angsuranbunga" class="form-group">
		<label id="elh_tpinjamandetail_angsuranbunga" for="x_angsuranbunga" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->angsuranbunga->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->angsuranbunga->CellAttributes() ?>>
<span id="el_tpinjamandetail_angsuranbunga">
<input type="text" data-table="tpinjamandetail" data-field="x_angsuranbunga" name="x_angsuranbunga" id="x_angsuranbunga" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->angsuranbunga->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->angsuranbunga->EditValue ?>"<?php echo $tpinjamandetail->angsuranbunga->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->angsuranbunga->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->angsuranbungaauto->Visible) { // angsuranbungaauto ?>
	<div id="r_angsuranbungaauto" class="form-group">
		<label id="elh_tpinjamandetail_angsuranbungaauto" for="x_angsuranbungaauto" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->angsuranbungaauto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->angsuranbungaauto->CellAttributes() ?>>
<span id="el_tpinjamandetail_angsuranbungaauto">
<input type="text" data-table="tpinjamandetail" data-field="x_angsuranbungaauto" name="x_angsuranbungaauto" id="x_angsuranbungaauto" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->angsuranbungaauto->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->angsuranbungaauto->EditValue ?>"<?php echo $tpinjamandetail->angsuranbungaauto->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->angsuranbungaauto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->denda->Visible) { // denda ?>
	<div id="r_denda" class="form-group">
		<label id="elh_tpinjamandetail_denda" for="x_denda" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->denda->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->denda->CellAttributes() ?>>
<span id="el_tpinjamandetail_denda">
<input type="text" data-table="tpinjamandetail" data-field="x_denda" name="x_denda" id="x_denda" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->denda->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->denda->EditValue ?>"<?php echo $tpinjamandetail->denda->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->denda->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->dendapersen->Visible) { // dendapersen ?>
	<div id="r_dendapersen" class="form-group">
		<label id="elh_tpinjamandetail_dendapersen" for="x_dendapersen" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->dendapersen->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->dendapersen->CellAttributes() ?>>
<span id="el_tpinjamandetail_dendapersen">
<input type="text" data-table="tpinjamandetail" data-field="x_dendapersen" name="x_dendapersen" id="x_dendapersen" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->dendapersen->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->dendapersen->EditValue ?>"<?php echo $tpinjamandetail->dendapersen->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->dendapersen->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->totalangsuran->Visible) { // totalangsuran ?>
	<div id="r_totalangsuran" class="form-group">
		<label id="elh_tpinjamandetail_totalangsuran" for="x_totalangsuran" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->totalangsuran->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->totalangsuran->CellAttributes() ?>>
<span id="el_tpinjamandetail_totalangsuran">
<input type="text" data-table="tpinjamandetail" data-field="x_totalangsuran" name="x_totalangsuran" id="x_totalangsuran" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->totalangsuran->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->totalangsuran->EditValue ?>"<?php echo $tpinjamandetail->totalangsuran->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->totalangsuran->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->totalangsuranauto->Visible) { // totalangsuranauto ?>
	<div id="r_totalangsuranauto" class="form-group">
		<label id="elh_tpinjamandetail_totalangsuranauto" for="x_totalangsuranauto" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->totalangsuranauto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->totalangsuranauto->CellAttributes() ?>>
<span id="el_tpinjamandetail_totalangsuranauto">
<input type="text" data-table="tpinjamandetail" data-field="x_totalangsuranauto" name="x_totalangsuranauto" id="x_totalangsuranauto" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->totalangsuranauto->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->totalangsuranauto->EditValue ?>"<?php echo $tpinjamandetail->totalangsuranauto->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->totalangsuranauto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->sisaangsuran->Visible) { // sisaangsuran ?>
	<div id="r_sisaangsuran" class="form-group">
		<label id="elh_tpinjamandetail_sisaangsuran" for="x_sisaangsuran" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->sisaangsuran->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->sisaangsuran->CellAttributes() ?>>
<span id="el_tpinjamandetail_sisaangsuran">
<input type="text" data-table="tpinjamandetail" data-field="x_sisaangsuran" name="x_sisaangsuran" id="x_sisaangsuran" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->sisaangsuran->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->sisaangsuran->EditValue ?>"<?php echo $tpinjamandetail->sisaangsuran->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->sisaangsuran->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->sisaangsuranauto->Visible) { // sisaangsuranauto ?>
	<div id="r_sisaangsuranauto" class="form-group">
		<label id="elh_tpinjamandetail_sisaangsuranauto" for="x_sisaangsuranauto" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->sisaangsuranauto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->sisaangsuranauto->CellAttributes() ?>>
<span id="el_tpinjamandetail_sisaangsuranauto">
<input type="text" data-table="tpinjamandetail" data-field="x_sisaangsuranauto" name="x_sisaangsuranauto" id="x_sisaangsuranauto" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->sisaangsuranauto->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->sisaangsuranauto->EditValue ?>"<?php echo $tpinjamandetail->sisaangsuranauto->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->sisaangsuranauto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->tanggalbayar->Visible) { // tanggalbayar ?>
	<div id="r_tanggalbayar" class="form-group">
		<label id="elh_tpinjamandetail_tanggalbayar" for="x_tanggalbayar" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->tanggalbayar->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->tanggalbayar->CellAttributes() ?>>
<span id="el_tpinjamandetail_tanggalbayar">
<input type="text" data-table="tpinjamandetail" data-field="x_tanggalbayar" name="x_tanggalbayar" id="x_tanggalbayar" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->tanggalbayar->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->tanggalbayar->EditValue ?>"<?php echo $tpinjamandetail->tanggalbayar->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->tanggalbayar->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->terlambat->Visible) { // terlambat ?>
	<div id="r_terlambat" class="form-group">
		<label id="elh_tpinjamandetail_terlambat" for="x_terlambat" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->terlambat->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->terlambat->CellAttributes() ?>>
<span id="el_tpinjamandetail_terlambat">
<input type="text" data-table="tpinjamandetail" data-field="x_terlambat" name="x_terlambat" id="x_terlambat" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->terlambat->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->terlambat->EditValue ?>"<?php echo $tpinjamandetail->terlambat->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->terlambat->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->bayarpokok->Visible) { // bayarpokok ?>
	<div id="r_bayarpokok" class="form-group">
		<label id="elh_tpinjamandetail_bayarpokok" for="x_bayarpokok" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->bayarpokok->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->bayarpokok->CellAttributes() ?>>
<span id="el_tpinjamandetail_bayarpokok">
<input type="text" data-table="tpinjamandetail" data-field="x_bayarpokok" name="x_bayarpokok" id="x_bayarpokok" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->bayarpokok->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->bayarpokok->EditValue ?>"<?php echo $tpinjamandetail->bayarpokok->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->bayarpokok->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->bayarpokokauto->Visible) { // bayarpokokauto ?>
	<div id="r_bayarpokokauto" class="form-group">
		<label id="elh_tpinjamandetail_bayarpokokauto" for="x_bayarpokokauto" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->bayarpokokauto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->bayarpokokauto->CellAttributes() ?>>
<span id="el_tpinjamandetail_bayarpokokauto">
<input type="text" data-table="tpinjamandetail" data-field="x_bayarpokokauto" name="x_bayarpokokauto" id="x_bayarpokokauto" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->bayarpokokauto->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->bayarpokokauto->EditValue ?>"<?php echo $tpinjamandetail->bayarpokokauto->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->bayarpokokauto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->bayarbunga->Visible) { // bayarbunga ?>
	<div id="r_bayarbunga" class="form-group">
		<label id="elh_tpinjamandetail_bayarbunga" for="x_bayarbunga" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->bayarbunga->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->bayarbunga->CellAttributes() ?>>
<span id="el_tpinjamandetail_bayarbunga">
<input type="text" data-table="tpinjamandetail" data-field="x_bayarbunga" name="x_bayarbunga" id="x_bayarbunga" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->bayarbunga->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->bayarbunga->EditValue ?>"<?php echo $tpinjamandetail->bayarbunga->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->bayarbunga->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->bayarbungaauto->Visible) { // bayarbungaauto ?>
	<div id="r_bayarbungaauto" class="form-group">
		<label id="elh_tpinjamandetail_bayarbungaauto" for="x_bayarbungaauto" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->bayarbungaauto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->bayarbungaauto->CellAttributes() ?>>
<span id="el_tpinjamandetail_bayarbungaauto">
<input type="text" data-table="tpinjamandetail" data-field="x_bayarbungaauto" name="x_bayarbungaauto" id="x_bayarbungaauto" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->bayarbungaauto->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->bayarbungaauto->EditValue ?>"<?php echo $tpinjamandetail->bayarbungaauto->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->bayarbungaauto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->bayardenda->Visible) { // bayardenda ?>
	<div id="r_bayardenda" class="form-group">
		<label id="elh_tpinjamandetail_bayardenda" for="x_bayardenda" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->bayardenda->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->bayardenda->CellAttributes() ?>>
<span id="el_tpinjamandetail_bayardenda">
<input type="text" data-table="tpinjamandetail" data-field="x_bayardenda" name="x_bayardenda" id="x_bayardenda" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->bayardenda->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->bayardenda->EditValue ?>"<?php echo $tpinjamandetail->bayardenda->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->bayardenda->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->bayardendaauto->Visible) { // bayardendaauto ?>
	<div id="r_bayardendaauto" class="form-group">
		<label id="elh_tpinjamandetail_bayardendaauto" for="x_bayardendaauto" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->bayardendaauto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->bayardendaauto->CellAttributes() ?>>
<span id="el_tpinjamandetail_bayardendaauto">
<input type="text" data-table="tpinjamandetail" data-field="x_bayardendaauto" name="x_bayardendaauto" id="x_bayardendaauto" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->bayardendaauto->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->bayardendaauto->EditValue ?>"<?php echo $tpinjamandetail->bayardendaauto->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->bayardendaauto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->bayartitipan->Visible) { // bayartitipan ?>
	<div id="r_bayartitipan" class="form-group">
		<label id="elh_tpinjamandetail_bayartitipan" for="x_bayartitipan" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->bayartitipan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->bayartitipan->CellAttributes() ?>>
<span id="el_tpinjamandetail_bayartitipan">
<input type="text" data-table="tpinjamandetail" data-field="x_bayartitipan" name="x_bayartitipan" id="x_bayartitipan" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->bayartitipan->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->bayartitipan->EditValue ?>"<?php echo $tpinjamandetail->bayartitipan->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->bayartitipan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->bayartitipanauto->Visible) { // bayartitipanauto ?>
	<div id="r_bayartitipanauto" class="form-group">
		<label id="elh_tpinjamandetail_bayartitipanauto" for="x_bayartitipanauto" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->bayartitipanauto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->bayartitipanauto->CellAttributes() ?>>
<span id="el_tpinjamandetail_bayartitipanauto">
<input type="text" data-table="tpinjamandetail" data-field="x_bayartitipanauto" name="x_bayartitipanauto" id="x_bayartitipanauto" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->bayartitipanauto->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->bayartitipanauto->EditValue ?>"<?php echo $tpinjamandetail->bayartitipanauto->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->bayartitipanauto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->totalbayar->Visible) { // totalbayar ?>
	<div id="r_totalbayar" class="form-group">
		<label id="elh_tpinjamandetail_totalbayar" for="x_totalbayar" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->totalbayar->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->totalbayar->CellAttributes() ?>>
<span id="el_tpinjamandetail_totalbayar">
<input type="text" data-table="tpinjamandetail" data-field="x_totalbayar" name="x_totalbayar" id="x_totalbayar" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->totalbayar->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->totalbayar->EditValue ?>"<?php echo $tpinjamandetail->totalbayar->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->totalbayar->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->totalbayarauto->Visible) { // totalbayarauto ?>
	<div id="r_totalbayarauto" class="form-group">
		<label id="elh_tpinjamandetail_totalbayarauto" for="x_totalbayarauto" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->totalbayarauto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->totalbayarauto->CellAttributes() ?>>
<span id="el_tpinjamandetail_totalbayarauto">
<input type="text" data-table="tpinjamandetail" data-field="x_totalbayarauto" name="x_totalbayarauto" id="x_totalbayarauto" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->totalbayarauto->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->totalbayarauto->EditValue ?>"<?php echo $tpinjamandetail->totalbayarauto->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->totalbayarauto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->pelunasan->Visible) { // pelunasan ?>
	<div id="r_pelunasan" class="form-group">
		<label id="elh_tpinjamandetail_pelunasan" for="x_pelunasan" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->pelunasan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->pelunasan->CellAttributes() ?>>
<span id="el_tpinjamandetail_pelunasan">
<input type="text" data-table="tpinjamandetail" data-field="x_pelunasan" name="x_pelunasan" id="x_pelunasan" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->pelunasan->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->pelunasan->EditValue ?>"<?php echo $tpinjamandetail->pelunasan->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->pelunasan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->pelunasanauto->Visible) { // pelunasanauto ?>
	<div id="r_pelunasanauto" class="form-group">
		<label id="elh_tpinjamandetail_pelunasanauto" for="x_pelunasanauto" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->pelunasanauto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->pelunasanauto->CellAttributes() ?>>
<span id="el_tpinjamandetail_pelunasanauto">
<input type="text" data-table="tpinjamandetail" data-field="x_pelunasanauto" name="x_pelunasanauto" id="x_pelunasanauto" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->pelunasanauto->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->pelunasanauto->EditValue ?>"<?php echo $tpinjamandetail->pelunasanauto->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->pelunasanauto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->finalty->Visible) { // finalty ?>
	<div id="r_finalty" class="form-group">
		<label id="elh_tpinjamandetail_finalty" for="x_finalty" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->finalty->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->finalty->CellAttributes() ?>>
<span id="el_tpinjamandetail_finalty">
<input type="text" data-table="tpinjamandetail" data-field="x_finalty" name="x_finalty" id="x_finalty" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->finalty->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->finalty->EditValue ?>"<?php echo $tpinjamandetail->finalty->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->finalty->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->finaltyauto->Visible) { // finaltyauto ?>
	<div id="r_finaltyauto" class="form-group">
		<label id="elh_tpinjamandetail_finaltyauto" for="x_finaltyauto" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->finaltyauto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->finaltyauto->CellAttributes() ?>>
<span id="el_tpinjamandetail_finaltyauto">
<input type="text" data-table="tpinjamandetail" data-field="x_finaltyauto" name="x_finaltyauto" id="x_finaltyauto" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->finaltyauto->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->finaltyauto->EditValue ?>"<?php echo $tpinjamandetail->finaltyauto->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->finaltyauto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_tpinjamandetail_status" for="x_status" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->status->CellAttributes() ?>>
<span id="el_tpinjamandetail_status">
<input type="text" data-table="tpinjamandetail" data-field="x_status" name="x_status" id="x_status" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->status->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->status->EditValue ?>"<?php echo $tpinjamandetail->status->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail->keterangan->Visible) { // keterangan ?>
	<div id="r_keterangan" class="form-group">
		<label id="elh_tpinjamandetail_keterangan" for="x_keterangan" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail->keterangan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail->keterangan->CellAttributes() ?>>
<span id="el_tpinjamandetail_keterangan">
<input type="text" data-table="tpinjamandetail" data-field="x_keterangan" name="x_keterangan" id="x_keterangan" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail->keterangan->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail->keterangan->EditValue ?>"<?php echo $tpinjamandetail->keterangan->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail->keterangan->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$tpinjamandetail_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $tpinjamandetail_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ftpinjamandetailedit.Init();
</script>
<?php
$tpinjamandetail_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tpinjamandetail_edit->Page_Terminate();
?>
