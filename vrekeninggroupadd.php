<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "vrekeninggroupinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$vrekeninggroup_add = NULL; // Initialize page object first

class cvrekeninggroup_add extends cvrekeninggroup {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'vrekeninggroup';

	// Page object name
	var $PageObjName = 'vrekeninggroup_add';

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

		// Table object (vrekeninggroup)
		if (!isset($GLOBALS["vrekeninggroup"]) || get_class($GLOBALS["vrekeninggroup"]) == "cvrekeninggroup") {
			$GLOBALS["vrekeninggroup"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["vrekeninggroup"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'vrekeninggroup', TRUE);

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
		$this->group->SetVisibility();
		$this->id->SetVisibility();
		$this->rekening->SetVisibility();
		$this->tipe->SetVisibility();
		$this->posisi->SetVisibility();
		$this->laporan->SetVisibility();
		$this->status->SetVisibility();
		$this->parent->SetVisibility();
		$this->keterangan->SetVisibility();
		$this->active->SetVisibility();

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
		global $EW_EXPORT, $vrekeninggroup;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($vrekeninggroup);
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
					$this->Page_Terminate("vrekeninggrouplist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "vrekeninggrouplist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "vrekeninggroupview.php")
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
		$this->group->CurrentValue = 0;
		$this->id->CurrentValue = NULL;
		$this->id->OldValue = $this->id->CurrentValue;
		$this->rekening->CurrentValue = NULL;
		$this->rekening->OldValue = $this->rekening->CurrentValue;
		$this->tipe->CurrentValue = NULL;
		$this->tipe->OldValue = $this->tipe->CurrentValue;
		$this->posisi->CurrentValue = NULL;
		$this->posisi->OldValue = $this->posisi->CurrentValue;
		$this->laporan->CurrentValue = NULL;
		$this->laporan->OldValue = $this->laporan->CurrentValue;
		$this->status->CurrentValue = NULL;
		$this->status->OldValue = $this->status->CurrentValue;
		$this->parent->CurrentValue = NULL;
		$this->parent->OldValue = $this->parent->CurrentValue;
		$this->keterangan->CurrentValue = NULL;
		$this->keterangan->OldValue = $this->keterangan->CurrentValue;
		$this->active->CurrentValue = "yes";
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->group->FldIsDetailKey) {
			$this->group->setFormValue($objForm->GetValue("x_group"));
		}
		if (!$this->id->FldIsDetailKey) {
			$this->id->setFormValue($objForm->GetValue("x_id"));
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
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
		if (!$this->parent->FldIsDetailKey) {
			$this->parent->setFormValue($objForm->GetValue("x_parent"));
		}
		if (!$this->keterangan->FldIsDetailKey) {
			$this->keterangan->setFormValue($objForm->GetValue("x_keterangan"));
		}
		if (!$this->active->FldIsDetailKey) {
			$this->active->setFormValue($objForm->GetValue("x_active"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->group->CurrentValue = $this->group->FormValue;
		$this->id->CurrentValue = $this->id->FormValue;
		$this->rekening->CurrentValue = $this->rekening->FormValue;
		$this->tipe->CurrentValue = $this->tipe->FormValue;
		$this->posisi->CurrentValue = $this->posisi->FormValue;
		$this->laporan->CurrentValue = $this->laporan->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
		$this->parent->CurrentValue = $this->parent->FormValue;
		$this->keterangan->CurrentValue = $this->keterangan->FormValue;
		$this->active->CurrentValue = $this->active->FormValue;
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
		$this->group->setDbValue($rs->fields('group'));
		$this->id->setDbValue($rs->fields('id'));
		$this->rekening->setDbValue($rs->fields('rekening'));
		$this->tipe->setDbValue($rs->fields('tipe'));
		$this->posisi->setDbValue($rs->fields('posisi'));
		$this->laporan->setDbValue($rs->fields('laporan'));
		$this->status->setDbValue($rs->fields('status'));
		$this->parent->setDbValue($rs->fields('parent'));
		$this->keterangan->setDbValue($rs->fields('keterangan'));
		$this->active->setDbValue($rs->fields('active'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->group->DbValue = $row['group'];
		$this->id->DbValue = $row['id'];
		$this->rekening->DbValue = $row['rekening'];
		$this->tipe->DbValue = $row['tipe'];
		$this->posisi->DbValue = $row['posisi'];
		$this->laporan->DbValue = $row['laporan'];
		$this->status->DbValue = $row['status'];
		$this->parent->DbValue = $row['parent'];
		$this->keterangan->DbValue = $row['keterangan'];
		$this->active->DbValue = $row['active'];
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
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// group
		// id
		// rekening
		// tipe
		// posisi
		// laporan
		// status
		// parent
		// keterangan
		// active

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// group
		$this->group->ViewValue = $this->group->CurrentValue;
		$this->group->ViewCustomAttributes = "";

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

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

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		$this->status->ViewCustomAttributes = "";

		// parent
		$this->parent->ViewValue = $this->parent->CurrentValue;
		$this->parent->ViewCustomAttributes = "";

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

			// group
			$this->group->LinkCustomAttributes = "";
			$this->group->HrefValue = "";
			$this->group->TooltipValue = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

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

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// parent
			$this->parent->LinkCustomAttributes = "";
			$this->parent->HrefValue = "";
			$this->parent->TooltipValue = "";

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";
			$this->keterangan->TooltipValue = "";

			// active
			$this->active->LinkCustomAttributes = "";
			$this->active->HrefValue = "";
			$this->active->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// group
			$this->group->EditAttrs["class"] = "form-control";
			$this->group->EditCustomAttributes = "";
			$this->group->EditValue = ew_HtmlEncode($this->group->CurrentValue);
			$this->group->PlaceHolder = ew_RemoveHtml($this->group->FldCaption());

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->CurrentValue);
			$this->id->PlaceHolder = ew_RemoveHtml($this->id->FldCaption());

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

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = ew_HtmlEncode($this->status->CurrentValue);
			$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

			// parent
			$this->parent->EditAttrs["class"] = "form-control";
			$this->parent->EditCustomAttributes = "";
			$this->parent->EditValue = ew_HtmlEncode($this->parent->CurrentValue);
			$this->parent->PlaceHolder = ew_RemoveHtml($this->parent->FldCaption());

			// keterangan
			$this->keterangan->EditAttrs["class"] = "form-control";
			$this->keterangan->EditCustomAttributes = "";
			$this->keterangan->EditValue = ew_HtmlEncode($this->keterangan->CurrentValue);
			$this->keterangan->PlaceHolder = ew_RemoveHtml($this->keterangan->FldCaption());

			// active
			$this->active->EditCustomAttributes = "";
			$this->active->EditValue = $this->active->Options(FALSE);

			// Add refer script
			// group

			$this->group->LinkCustomAttributes = "";
			$this->group->HrefValue = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";

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

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";

			// parent
			$this->parent->LinkCustomAttributes = "";
			$this->parent->HrefValue = "";

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";

			// active
			$this->active->LinkCustomAttributes = "";
			$this->active->HrefValue = "";
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
		if (!$this->group->FldIsDetailKey && !is_null($this->group->FormValue) && $this->group->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->group->FldCaption(), $this->group->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->group->FormValue)) {
			ew_AddMessage($gsFormError, $this->group->FldErrMsg());
		}
		if (!$this->id->FldIsDetailKey && !is_null($this->id->FormValue) && $this->id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id->FldCaption(), $this->id->ReqErrMsg));
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
		if (!$this->status->FldIsDetailKey && !is_null($this->status->FormValue) && $this->status->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->status->FldCaption(), $this->status->ReqErrMsg));
		}
		if (!$this->parent->FldIsDetailKey && !is_null($this->parent->FormValue) && $this->parent->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->parent->FldCaption(), $this->parent->ReqErrMsg));
		}
		if (!$this->keterangan->FldIsDetailKey && !is_null($this->keterangan->FormValue) && $this->keterangan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->keterangan->FldCaption(), $this->keterangan->ReqErrMsg));
		}
		if ($this->active->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->active->FldCaption(), $this->active->ReqErrMsg));
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

		// group
		$this->group->SetDbValueDef($rsnew, $this->group->CurrentValue, NULL, strval($this->group->CurrentValue) == "");

		// id
		$this->id->SetDbValueDef($rsnew, $this->id->CurrentValue, "", FALSE);

		// rekening
		$this->rekening->SetDbValueDef($rsnew, $this->rekening->CurrentValue, NULL, FALSE);

		// tipe
		$this->tipe->SetDbValueDef($rsnew, $this->tipe->CurrentValue, NULL, FALSE);

		// posisi
		$this->posisi->SetDbValueDef($rsnew, $this->posisi->CurrentValue, NULL, FALSE);

		// laporan
		$this->laporan->SetDbValueDef($rsnew, $this->laporan->CurrentValue, NULL, FALSE);

		// status
		$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, NULL, FALSE);

		// parent
		$this->parent->SetDbValueDef($rsnew, $this->parent->CurrentValue, NULL, FALSE);

		// keterangan
		$this->keterangan->SetDbValueDef($rsnew, $this->keterangan->CurrentValue, NULL, FALSE);

		// active
		$this->active->SetDbValueDef($rsnew, $this->active->CurrentValue, NULL, strval($this->active->CurrentValue) == "");

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("vrekeninggrouplist.php"), "", $this->TableVar, TRUE);
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
if (!isset($vrekeninggroup_add)) $vrekeninggroup_add = new cvrekeninggroup_add();

// Page init
$vrekeninggroup_add->Page_Init();

// Page main
$vrekeninggroup_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$vrekeninggroup_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fvrekeninggroupadd = new ew_Form("fvrekeninggroupadd", "add");

// Validate form
fvrekeninggroupadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_group");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $vrekeninggroup->group->FldCaption(), $vrekeninggroup->group->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_group");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($vrekeninggroup->group->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $vrekeninggroup->id->FldCaption(), $vrekeninggroup->id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_rekening");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $vrekeninggroup->rekening->FldCaption(), $vrekeninggroup->rekening->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tipe");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $vrekeninggroup->tipe->FldCaption(), $vrekeninggroup->tipe->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_posisi");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $vrekeninggroup->posisi->FldCaption(), $vrekeninggroup->posisi->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_laporan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $vrekeninggroup->laporan->FldCaption(), $vrekeninggroup->laporan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $vrekeninggroup->status->FldCaption(), $vrekeninggroup->status->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_parent");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $vrekeninggroup->parent->FldCaption(), $vrekeninggroup->parent->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_keterangan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $vrekeninggroup->keterangan->FldCaption(), $vrekeninggroup->keterangan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_active");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $vrekeninggroup->active->FldCaption(), $vrekeninggroup->active->ReqErrMsg)) ?>");

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
fvrekeninggroupadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fvrekeninggroupadd.ValidateRequired = true;
<?php } else { ?>
fvrekeninggroupadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fvrekeninggroupadd.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fvrekeninggroupadd.Lists["x_active"].Options = <?php echo json_encode($vrekeninggroup->active->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$vrekeninggroup_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $vrekeninggroup_add->ShowPageHeader(); ?>
<?php
$vrekeninggroup_add->ShowMessage();
?>
<form name="fvrekeninggroupadd" id="fvrekeninggroupadd" class="<?php echo $vrekeninggroup_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($vrekeninggroup_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $vrekeninggroup_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="vrekeninggroup">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($vrekeninggroup_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($vrekeninggroup->group->Visible) { // group ?>
	<div id="r_group" class="form-group">
		<label id="elh_vrekeninggroup_group" for="x_group" class="col-sm-2 control-label ewLabel"><?php echo $vrekeninggroup->group->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $vrekeninggroup->group->CellAttributes() ?>>
<span id="el_vrekeninggroup_group">
<input type="text" data-table="vrekeninggroup" data-field="x_group" name="x_group" id="x_group" size="30" placeholder="<?php echo ew_HtmlEncode($vrekeninggroup->group->getPlaceHolder()) ?>" value="<?php echo $vrekeninggroup->group->EditValue ?>"<?php echo $vrekeninggroup->group->EditAttributes() ?>>
</span>
<?php echo $vrekeninggroup->group->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($vrekeninggroup->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_vrekeninggroup_id" for="x_id" class="col-sm-2 control-label ewLabel"><?php echo $vrekeninggroup->id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $vrekeninggroup->id->CellAttributes() ?>>
<span id="el_vrekeninggroup_id">
<input type="text" data-table="vrekeninggroup" data-field="x_id" name="x_id" id="x_id" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($vrekeninggroup->id->getPlaceHolder()) ?>" value="<?php echo $vrekeninggroup->id->EditValue ?>"<?php echo $vrekeninggroup->id->EditAttributes() ?>>
</span>
<?php echo $vrekeninggroup->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($vrekeninggroup->rekening->Visible) { // rekening ?>
	<div id="r_rekening" class="form-group">
		<label id="elh_vrekeninggroup_rekening" for="x_rekening" class="col-sm-2 control-label ewLabel"><?php echo $vrekeninggroup->rekening->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $vrekeninggroup->rekening->CellAttributes() ?>>
<span id="el_vrekeninggroup_rekening">
<input type="text" data-table="vrekeninggroup" data-field="x_rekening" name="x_rekening" id="x_rekening" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($vrekeninggroup->rekening->getPlaceHolder()) ?>" value="<?php echo $vrekeninggroup->rekening->EditValue ?>"<?php echo $vrekeninggroup->rekening->EditAttributes() ?>>
</span>
<?php echo $vrekeninggroup->rekening->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($vrekeninggroup->tipe->Visible) { // tipe ?>
	<div id="r_tipe" class="form-group">
		<label id="elh_vrekeninggroup_tipe" for="x_tipe" class="col-sm-2 control-label ewLabel"><?php echo $vrekeninggroup->tipe->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $vrekeninggroup->tipe->CellAttributes() ?>>
<span id="el_vrekeninggroup_tipe">
<input type="text" data-table="vrekeninggroup" data-field="x_tipe" name="x_tipe" id="x_tipe" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($vrekeninggroup->tipe->getPlaceHolder()) ?>" value="<?php echo $vrekeninggroup->tipe->EditValue ?>"<?php echo $vrekeninggroup->tipe->EditAttributes() ?>>
</span>
<?php echo $vrekeninggroup->tipe->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($vrekeninggroup->posisi->Visible) { // posisi ?>
	<div id="r_posisi" class="form-group">
		<label id="elh_vrekeninggroup_posisi" for="x_posisi" class="col-sm-2 control-label ewLabel"><?php echo $vrekeninggroup->posisi->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $vrekeninggroup->posisi->CellAttributes() ?>>
<span id="el_vrekeninggroup_posisi">
<input type="text" data-table="vrekeninggroup" data-field="x_posisi" name="x_posisi" id="x_posisi" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($vrekeninggroup->posisi->getPlaceHolder()) ?>" value="<?php echo $vrekeninggroup->posisi->EditValue ?>"<?php echo $vrekeninggroup->posisi->EditAttributes() ?>>
</span>
<?php echo $vrekeninggroup->posisi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($vrekeninggroup->laporan->Visible) { // laporan ?>
	<div id="r_laporan" class="form-group">
		<label id="elh_vrekeninggroup_laporan" for="x_laporan" class="col-sm-2 control-label ewLabel"><?php echo $vrekeninggroup->laporan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $vrekeninggroup->laporan->CellAttributes() ?>>
<span id="el_vrekeninggroup_laporan">
<input type="text" data-table="vrekeninggroup" data-field="x_laporan" name="x_laporan" id="x_laporan" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($vrekeninggroup->laporan->getPlaceHolder()) ?>" value="<?php echo $vrekeninggroup->laporan->EditValue ?>"<?php echo $vrekeninggroup->laporan->EditAttributes() ?>>
</span>
<?php echo $vrekeninggroup->laporan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($vrekeninggroup->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_vrekeninggroup_status" for="x_status" class="col-sm-2 control-label ewLabel"><?php echo $vrekeninggroup->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $vrekeninggroup->status->CellAttributes() ?>>
<span id="el_vrekeninggroup_status">
<input type="text" data-table="vrekeninggroup" data-field="x_status" name="x_status" id="x_status" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($vrekeninggroup->status->getPlaceHolder()) ?>" value="<?php echo $vrekeninggroup->status->EditValue ?>"<?php echo $vrekeninggroup->status->EditAttributes() ?>>
</span>
<?php echo $vrekeninggroup->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($vrekeninggroup->parent->Visible) { // parent ?>
	<div id="r_parent" class="form-group">
		<label id="elh_vrekeninggroup_parent" for="x_parent" class="col-sm-2 control-label ewLabel"><?php echo $vrekeninggroup->parent->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $vrekeninggroup->parent->CellAttributes() ?>>
<span id="el_vrekeninggroup_parent">
<input type="text" data-table="vrekeninggroup" data-field="x_parent" name="x_parent" id="x_parent" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($vrekeninggroup->parent->getPlaceHolder()) ?>" value="<?php echo $vrekeninggroup->parent->EditValue ?>"<?php echo $vrekeninggroup->parent->EditAttributes() ?>>
</span>
<?php echo $vrekeninggroup->parent->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($vrekeninggroup->keterangan->Visible) { // keterangan ?>
	<div id="r_keterangan" class="form-group">
		<label id="elh_vrekeninggroup_keterangan" for="x_keterangan" class="col-sm-2 control-label ewLabel"><?php echo $vrekeninggroup->keterangan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $vrekeninggroup->keterangan->CellAttributes() ?>>
<span id="el_vrekeninggroup_keterangan">
<input type="text" data-table="vrekeninggroup" data-field="x_keterangan" name="x_keterangan" id="x_keterangan" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($vrekeninggroup->keterangan->getPlaceHolder()) ?>" value="<?php echo $vrekeninggroup->keterangan->EditValue ?>"<?php echo $vrekeninggroup->keterangan->EditAttributes() ?>>
</span>
<?php echo $vrekeninggroup->keterangan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($vrekeninggroup->active->Visible) { // active ?>
	<div id="r_active" class="form-group">
		<label id="elh_vrekeninggroup_active" class="col-sm-2 control-label ewLabel"><?php echo $vrekeninggroup->active->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $vrekeninggroup->active->CellAttributes() ?>>
<span id="el_vrekeninggroup_active">
<div id="tp_x_active" class="ewTemplate"><input type="radio" data-table="vrekeninggroup" data-field="x_active" data-value-separator="<?php echo $vrekeninggroup->active->DisplayValueSeparatorAttribute() ?>" name="x_active" id="x_active" value="{value}"<?php echo $vrekeninggroup->active->EditAttributes() ?>></div>
<div id="dsl_x_active" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $vrekeninggroup->active->RadioButtonListHtml(FALSE, "x_active") ?>
</div></div>
</span>
<?php echo $vrekeninggroup->active->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$vrekeninggroup_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $vrekeninggroup_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fvrekeninggroupadd.Init();
</script>
<?php
$vrekeninggroup_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$vrekeninggroup_add->Page_Terminate();
?>
