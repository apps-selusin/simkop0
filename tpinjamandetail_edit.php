<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "tpinjamandetail_info.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$tpinjamandetail__edit = NULL; // Initialize page object first

class ctpinjamandetail__edit extends ctpinjamandetail_ {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tpinjamandetail_';

	// Page object name
	var $PageObjName = 'tpinjamandetail__edit';

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

		// Table object (tpinjamandetail_)
		if (!isset($GLOBALS["tpinjamandetail_"]) || get_class($GLOBALS["tpinjamandetail_"]) == "ctpinjamandetail_") {
			$GLOBALS["tpinjamandetail_"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tpinjamandetail_"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tpinjamandetail_', TRUE);

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
		$this->id->SetVisibility();
		$this->berjangka->SetVisibility();
		$this->angsuran->SetVisibility();
		$this->angsuranpokok->SetVisibility();
		$this->angsuranpokokauto->SetVisibility();
		$this->angsuranbunga->SetVisibility();
		$this->angsuranbungaauto->SetVisibility();
		$this->totalangsuran->SetVisibility();
		$this->totalangsuranauto->SetVisibility();
		$this->sisaangsuran->SetVisibility();
		$this->sisaangsuranauto->SetVisibility();

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
		global $EW_EXPORT, $tpinjamandetail_;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($tpinjamandetail_);
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
		if (@$_GET["berjangka"] <> "") {
			$this->berjangka->setQueryStringValue($_GET["berjangka"]);
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
			$this->Page_Terminate("tpinjamandetail_list.php"); // Invalid key, return to list
		}
		if ($this->berjangka->CurrentValue == "") {
			$this->Page_Terminate("tpinjamandetail_list.php"); // Invalid key, return to list
		}
		if ($this->angsuran->CurrentValue == "") {
			$this->Page_Terminate("tpinjamandetail_list.php"); // Invalid key, return to list
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
					$this->Page_Terminate("tpinjamandetail_list.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "tpinjamandetail_list.php")
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
		if (!$this->id->FldIsDetailKey) {
			$this->id->setFormValue($objForm->GetValue("x_id"));
		}
		if (!$this->berjangka->FldIsDetailKey) {
			$this->berjangka->setFormValue($objForm->GetValue("x_berjangka"));
		}
		if (!$this->angsuran->FldIsDetailKey) {
			$this->angsuran->setFormValue($objForm->GetValue("x_angsuran"));
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
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->berjangka->CurrentValue = $this->berjangka->FormValue;
		$this->angsuran->CurrentValue = $this->angsuran->FormValue;
		$this->angsuranpokok->CurrentValue = $this->angsuranpokok->FormValue;
		$this->angsuranpokokauto->CurrentValue = $this->angsuranpokokauto->FormValue;
		$this->angsuranbunga->CurrentValue = $this->angsuranbunga->FormValue;
		$this->angsuranbungaauto->CurrentValue = $this->angsuranbungaauto->FormValue;
		$this->totalangsuran->CurrentValue = $this->totalangsuran->FormValue;
		$this->totalangsuranauto->CurrentValue = $this->totalangsuranauto->FormValue;
		$this->sisaangsuran->CurrentValue = $this->sisaangsuran->FormValue;
		$this->sisaangsuranauto->CurrentValue = $this->sisaangsuranauto->FormValue;
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
		$this->id->setDbValue($rs->fields('id'));
		$this->berjangka->setDbValue($rs->fields('berjangka'));
		$this->angsuran->setDbValue($rs->fields('angsuran'));
		$this->angsuranpokok->setDbValue($rs->fields('angsuranpokok'));
		$this->angsuranpokokauto->setDbValue($rs->fields('angsuranpokokauto'));
		$this->angsuranbunga->setDbValue($rs->fields('angsuranbunga'));
		$this->angsuranbungaauto->setDbValue($rs->fields('angsuranbungaauto'));
		$this->totalangsuran->setDbValue($rs->fields('totalangsuran'));
		$this->totalangsuranauto->setDbValue($rs->fields('totalangsuranauto'));
		$this->sisaangsuran->setDbValue($rs->fields('sisaangsuran'));
		$this->sisaangsuranauto->setDbValue($rs->fields('sisaangsuranauto'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->berjangka->DbValue = $row['berjangka'];
		$this->angsuran->DbValue = $row['angsuran'];
		$this->angsuranpokok->DbValue = $row['angsuranpokok'];
		$this->angsuranpokokauto->DbValue = $row['angsuranpokokauto'];
		$this->angsuranbunga->DbValue = $row['angsuranbunga'];
		$this->angsuranbungaauto->DbValue = $row['angsuranbungaauto'];
		$this->totalangsuran->DbValue = $row['totalangsuran'];
		$this->totalangsuranauto->DbValue = $row['totalangsuranauto'];
		$this->sisaangsuran->DbValue = $row['sisaangsuran'];
		$this->sisaangsuranauto->DbValue = $row['sisaangsuranauto'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
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

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// berjangka
		// angsuran
		// angsuranpokok
		// angsuranpokokauto
		// angsuranbunga
		// angsuranbungaauto
		// totalangsuran
		// totalangsuranauto
		// sisaangsuran
		// sisaangsuranauto

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// berjangka
		$this->berjangka->ViewValue = $this->berjangka->CurrentValue;
		$this->berjangka->ViewCustomAttributes = "";

		// angsuran
		$this->angsuran->ViewValue = $this->angsuran->CurrentValue;
		$this->angsuran->ViewCustomAttributes = "";

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

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// berjangka
			$this->berjangka->LinkCustomAttributes = "";
			$this->berjangka->HrefValue = "";
			$this->berjangka->TooltipValue = "";

			// angsuran
			$this->angsuran->LinkCustomAttributes = "";
			$this->angsuran->HrefValue = "";
			$this->angsuran->TooltipValue = "";

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// berjangka
			$this->berjangka->EditAttrs["class"] = "form-control";
			$this->berjangka->EditCustomAttributes = "";
			$this->berjangka->EditValue = $this->berjangka->CurrentValue;
			$this->berjangka->ViewCustomAttributes = "";

			// angsuran
			$this->angsuran->EditAttrs["class"] = "form-control";
			$this->angsuran->EditCustomAttributes = "";
			$this->angsuran->EditValue = $this->angsuran->CurrentValue;
			$this->angsuran->ViewCustomAttributes = "";

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

			// Edit refer script
			// id

			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";

			// berjangka
			$this->berjangka->LinkCustomAttributes = "";
			$this->berjangka->HrefValue = "";

			// angsuran
			$this->angsuran->LinkCustomAttributes = "";
			$this->angsuran->HrefValue = "";

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
		if (!$this->id->FldIsDetailKey && !is_null($this->id->FormValue) && $this->id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id->FldCaption(), $this->id->ReqErrMsg));
		}
		if (!$this->berjangka->FldIsDetailKey && !is_null($this->berjangka->FormValue) && $this->berjangka->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->berjangka->FldCaption(), $this->berjangka->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->berjangka->FormValue)) {
			ew_AddMessage($gsFormError, $this->berjangka->FldErrMsg());
		}
		if (!$this->angsuran->FldIsDetailKey && !is_null($this->angsuran->FormValue) && $this->angsuran->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->angsuran->FldCaption(), $this->angsuran->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->angsuran->FormValue)) {
			ew_AddMessage($gsFormError, $this->angsuran->FldErrMsg());
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

			// id
			// berjangka
			// angsuran
			// angsuranpokok

			$this->angsuranpokok->SetDbValueDef($rsnew, $this->angsuranpokok->CurrentValue, 0, $this->angsuranpokok->ReadOnly);

			// angsuranpokokauto
			$this->angsuranpokokauto->SetDbValueDef($rsnew, $this->angsuranpokokauto->CurrentValue, 0, $this->angsuranpokokauto->ReadOnly);

			// angsuranbunga
			$this->angsuranbunga->SetDbValueDef($rsnew, $this->angsuranbunga->CurrentValue, 0, $this->angsuranbunga->ReadOnly);

			// angsuranbungaauto
			$this->angsuranbungaauto->SetDbValueDef($rsnew, $this->angsuranbungaauto->CurrentValue, 0, $this->angsuranbungaauto->ReadOnly);

			// totalangsuran
			$this->totalangsuran->SetDbValueDef($rsnew, $this->totalangsuran->CurrentValue, 0, $this->totalangsuran->ReadOnly);

			// totalangsuranauto
			$this->totalangsuranauto->SetDbValueDef($rsnew, $this->totalangsuranauto->CurrentValue, 0, $this->totalangsuranauto->ReadOnly);

			// sisaangsuran
			$this->sisaangsuran->SetDbValueDef($rsnew, $this->sisaangsuran->CurrentValue, 0, $this->sisaangsuran->ReadOnly);

			// sisaangsuranauto
			$this->sisaangsuranauto->SetDbValueDef($rsnew, $this->sisaangsuranauto->CurrentValue, 0, $this->sisaangsuranauto->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("tpinjamandetail_list.php"), "", $this->TableVar, TRUE);
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
if (!isset($tpinjamandetail__edit)) $tpinjamandetail__edit = new ctpinjamandetail__edit();

// Page init
$tpinjamandetail__edit->Page_Init();

// Page main
$tpinjamandetail__edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tpinjamandetail__edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = ftpinjamandetail_edit = new ew_Form("ftpinjamandetail_edit", "edit");

// Validate form
ftpinjamandetail_edit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail_->id->FldCaption(), $tpinjamandetail_->id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_berjangka");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail_->berjangka->FldCaption(), $tpinjamandetail_->berjangka->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_berjangka");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail_->berjangka->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_angsuran");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail_->angsuran->FldCaption(), $tpinjamandetail_->angsuran->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_angsuran");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail_->angsuran->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_angsuranpokok");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail_->angsuranpokok->FldCaption(), $tpinjamandetail_->angsuranpokok->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_angsuranpokok");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail_->angsuranpokok->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_angsuranpokokauto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail_->angsuranpokokauto->FldCaption(), $tpinjamandetail_->angsuranpokokauto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_angsuranpokokauto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail_->angsuranpokokauto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_angsuranbunga");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail_->angsuranbunga->FldCaption(), $tpinjamandetail_->angsuranbunga->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_angsuranbunga");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail_->angsuranbunga->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_angsuranbungaauto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail_->angsuranbungaauto->FldCaption(), $tpinjamandetail_->angsuranbungaauto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_angsuranbungaauto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail_->angsuranbungaauto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_totalangsuran");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail_->totalangsuran->FldCaption(), $tpinjamandetail_->totalangsuran->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_totalangsuran");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail_->totalangsuran->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_totalangsuranauto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail_->totalangsuranauto->FldCaption(), $tpinjamandetail_->totalangsuranauto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_totalangsuranauto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail_->totalangsuranauto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_sisaangsuran");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail_->sisaangsuran->FldCaption(), $tpinjamandetail_->sisaangsuran->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_sisaangsuran");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail_->sisaangsuran->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_sisaangsuranauto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $tpinjamandetail_->sisaangsuranauto->FldCaption(), $tpinjamandetail_->sisaangsuranauto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_sisaangsuranauto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tpinjamandetail_->sisaangsuranauto->FldErrMsg()) ?>");

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
ftpinjamandetail_edit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftpinjamandetail_edit.ValidateRequired = true;
<?php } else { ?>
ftpinjamandetail_edit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$tpinjamandetail__edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $tpinjamandetail__edit->ShowPageHeader(); ?>
<?php
$tpinjamandetail__edit->ShowMessage();
?>
<form name="ftpinjamandetail_edit" id="ftpinjamandetail_edit" class="<?php echo $tpinjamandetail__edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tpinjamandetail__edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tpinjamandetail__edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tpinjamandetail_">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($tpinjamandetail__edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($tpinjamandetail_->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_tpinjamandetail__id" for="x_id" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail_->id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail_->id->CellAttributes() ?>>
<span id="el_tpinjamandetail__id">
<span<?php echo $tpinjamandetail_->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $tpinjamandetail_->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="tpinjamandetail_" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($tpinjamandetail_->id->CurrentValue) ?>">
<?php echo $tpinjamandetail_->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail_->berjangka->Visible) { // berjangka ?>
	<div id="r_berjangka" class="form-group">
		<label id="elh_tpinjamandetail__berjangka" for="x_berjangka" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail_->berjangka->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail_->berjangka->CellAttributes() ?>>
<span id="el_tpinjamandetail__berjangka">
<span<?php echo $tpinjamandetail_->berjangka->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $tpinjamandetail_->berjangka->EditValue ?></p></span>
</span>
<input type="hidden" data-table="tpinjamandetail_" data-field="x_berjangka" name="x_berjangka" id="x_berjangka" value="<?php echo ew_HtmlEncode($tpinjamandetail_->berjangka->CurrentValue) ?>">
<?php echo $tpinjamandetail_->berjangka->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail_->angsuran->Visible) { // angsuran ?>
	<div id="r_angsuran" class="form-group">
		<label id="elh_tpinjamandetail__angsuran" for="x_angsuran" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail_->angsuran->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail_->angsuran->CellAttributes() ?>>
<span id="el_tpinjamandetail__angsuran">
<span<?php echo $tpinjamandetail_->angsuran->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $tpinjamandetail_->angsuran->EditValue ?></p></span>
</span>
<input type="hidden" data-table="tpinjamandetail_" data-field="x_angsuran" name="x_angsuran" id="x_angsuran" value="<?php echo ew_HtmlEncode($tpinjamandetail_->angsuran->CurrentValue) ?>">
<?php echo $tpinjamandetail_->angsuran->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail_->angsuranpokok->Visible) { // angsuranpokok ?>
	<div id="r_angsuranpokok" class="form-group">
		<label id="elh_tpinjamandetail__angsuranpokok" for="x_angsuranpokok" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail_->angsuranpokok->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail_->angsuranpokok->CellAttributes() ?>>
<span id="el_tpinjamandetail__angsuranpokok">
<input type="text" data-table="tpinjamandetail_" data-field="x_angsuranpokok" name="x_angsuranpokok" id="x_angsuranpokok" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail_->angsuranpokok->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail_->angsuranpokok->EditValue ?>"<?php echo $tpinjamandetail_->angsuranpokok->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail_->angsuranpokok->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail_->angsuranpokokauto->Visible) { // angsuranpokokauto ?>
	<div id="r_angsuranpokokauto" class="form-group">
		<label id="elh_tpinjamandetail__angsuranpokokauto" for="x_angsuranpokokauto" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail_->angsuranpokokauto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail_->angsuranpokokauto->CellAttributes() ?>>
<span id="el_tpinjamandetail__angsuranpokokauto">
<input type="text" data-table="tpinjamandetail_" data-field="x_angsuranpokokauto" name="x_angsuranpokokauto" id="x_angsuranpokokauto" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail_->angsuranpokokauto->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail_->angsuranpokokauto->EditValue ?>"<?php echo $tpinjamandetail_->angsuranpokokauto->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail_->angsuranpokokauto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail_->angsuranbunga->Visible) { // angsuranbunga ?>
	<div id="r_angsuranbunga" class="form-group">
		<label id="elh_tpinjamandetail__angsuranbunga" for="x_angsuranbunga" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail_->angsuranbunga->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail_->angsuranbunga->CellAttributes() ?>>
<span id="el_tpinjamandetail__angsuranbunga">
<input type="text" data-table="tpinjamandetail_" data-field="x_angsuranbunga" name="x_angsuranbunga" id="x_angsuranbunga" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail_->angsuranbunga->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail_->angsuranbunga->EditValue ?>"<?php echo $tpinjamandetail_->angsuranbunga->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail_->angsuranbunga->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail_->angsuranbungaauto->Visible) { // angsuranbungaauto ?>
	<div id="r_angsuranbungaauto" class="form-group">
		<label id="elh_tpinjamandetail__angsuranbungaauto" for="x_angsuranbungaauto" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail_->angsuranbungaauto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail_->angsuranbungaauto->CellAttributes() ?>>
<span id="el_tpinjamandetail__angsuranbungaauto">
<input type="text" data-table="tpinjamandetail_" data-field="x_angsuranbungaauto" name="x_angsuranbungaauto" id="x_angsuranbungaauto" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail_->angsuranbungaauto->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail_->angsuranbungaauto->EditValue ?>"<?php echo $tpinjamandetail_->angsuranbungaauto->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail_->angsuranbungaauto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail_->totalangsuran->Visible) { // totalangsuran ?>
	<div id="r_totalangsuran" class="form-group">
		<label id="elh_tpinjamandetail__totalangsuran" for="x_totalangsuran" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail_->totalangsuran->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail_->totalangsuran->CellAttributes() ?>>
<span id="el_tpinjamandetail__totalangsuran">
<input type="text" data-table="tpinjamandetail_" data-field="x_totalangsuran" name="x_totalangsuran" id="x_totalangsuran" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail_->totalangsuran->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail_->totalangsuran->EditValue ?>"<?php echo $tpinjamandetail_->totalangsuran->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail_->totalangsuran->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail_->totalangsuranauto->Visible) { // totalangsuranauto ?>
	<div id="r_totalangsuranauto" class="form-group">
		<label id="elh_tpinjamandetail__totalangsuranauto" for="x_totalangsuranauto" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail_->totalangsuranauto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail_->totalangsuranauto->CellAttributes() ?>>
<span id="el_tpinjamandetail__totalangsuranauto">
<input type="text" data-table="tpinjamandetail_" data-field="x_totalangsuranauto" name="x_totalangsuranauto" id="x_totalangsuranauto" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail_->totalangsuranauto->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail_->totalangsuranauto->EditValue ?>"<?php echo $tpinjamandetail_->totalangsuranauto->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail_->totalangsuranauto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail_->sisaangsuran->Visible) { // sisaangsuran ?>
	<div id="r_sisaangsuran" class="form-group">
		<label id="elh_tpinjamandetail__sisaangsuran" for="x_sisaangsuran" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail_->sisaangsuran->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail_->sisaangsuran->CellAttributes() ?>>
<span id="el_tpinjamandetail__sisaangsuran">
<input type="text" data-table="tpinjamandetail_" data-field="x_sisaangsuran" name="x_sisaangsuran" id="x_sisaangsuran" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail_->sisaangsuran->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail_->sisaangsuran->EditValue ?>"<?php echo $tpinjamandetail_->sisaangsuran->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail_->sisaangsuran->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tpinjamandetail_->sisaangsuranauto->Visible) { // sisaangsuranauto ?>
	<div id="r_sisaangsuranauto" class="form-group">
		<label id="elh_tpinjamandetail__sisaangsuranauto" for="x_sisaangsuranauto" class="col-sm-2 control-label ewLabel"><?php echo $tpinjamandetail_->sisaangsuranauto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $tpinjamandetail_->sisaangsuranauto->CellAttributes() ?>>
<span id="el_tpinjamandetail__sisaangsuranauto">
<input type="text" data-table="tpinjamandetail_" data-field="x_sisaangsuranauto" name="x_sisaangsuranauto" id="x_sisaangsuranauto" size="30" placeholder="<?php echo ew_HtmlEncode($tpinjamandetail_->sisaangsuranauto->getPlaceHolder()) ?>" value="<?php echo $tpinjamandetail_->sisaangsuranauto->EditValue ?>"<?php echo $tpinjamandetail_->sisaangsuranauto->EditAttributes() ?>>
</span>
<?php echo $tpinjamandetail_->sisaangsuranauto->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$tpinjamandetail__edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $tpinjamandetail__edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ftpinjamandetail_edit.Init();
</script>
<?php
$tpinjamandetail__edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tpinjamandetail__edit->Page_Terminate();
?>
