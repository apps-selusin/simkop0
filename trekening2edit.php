<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "trekening2info.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$trekening2_edit = NULL; // Initialize page object first

class ctrekening2_edit extends ctrekening2 {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'trekening2';

	// Page object name
	var $PageObjName = 'trekening2_edit';

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

		// Table object (trekening2)
		if (!isset($GLOBALS["trekening2"]) || get_class($GLOBALS["trekening2"]) == "ctrekening2") {
			$GLOBALS["trekening2"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["trekening2"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'trekening2', TRUE);

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
		$this->parent1->SetVisibility();
		$this->id1->SetVisibility();
		$this->rekening1->SetVisibility();
		$this->parent2->SetVisibility();
		$this->id2->SetVisibility();
		$this->rekening2->SetVisibility();
		$this->tipe->SetVisibility();
		$this->posisi->SetVisibility();
		$this->laporan->SetVisibility();
		$this->status->SetVisibility();
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
		global $EW_EXPORT, $trekening2;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($trekening2);
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
			$this->Page_Terminate("trekening2list.php"); // Invalid key, return to list
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
					$this->Page_Terminate("trekening2list.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "trekening2list.php")
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
		if (!$this->group->FldIsDetailKey) {
			$this->group->setFormValue($objForm->GetValue("x_group"));
		}
		if (!$this->parent1->FldIsDetailKey) {
			$this->parent1->setFormValue($objForm->GetValue("x_parent1"));
		}
		if (!$this->id1->FldIsDetailKey) {
			$this->id1->setFormValue($objForm->GetValue("x_id1"));
		}
		if (!$this->rekening1->FldIsDetailKey) {
			$this->rekening1->setFormValue($objForm->GetValue("x_rekening1"));
		}
		if (!$this->parent2->FldIsDetailKey) {
			$this->parent2->setFormValue($objForm->GetValue("x_parent2"));
		}
		if (!$this->id2->FldIsDetailKey) {
			$this->id2->setFormValue($objForm->GetValue("x_id2"));
		}
		if (!$this->rekening2->FldIsDetailKey) {
			$this->rekening2->setFormValue($objForm->GetValue("x_rekening2"));
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
		if (!$this->keterangan->FldIsDetailKey) {
			$this->keterangan->setFormValue($objForm->GetValue("x_keterangan"));
		}
		if (!$this->active->FldIsDetailKey) {
			$this->active->setFormValue($objForm->GetValue("x_active"));
		}
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->group->CurrentValue = $this->group->FormValue;
		$this->parent1->CurrentValue = $this->parent1->FormValue;
		$this->id1->CurrentValue = $this->id1->FormValue;
		$this->rekening1->CurrentValue = $this->rekening1->FormValue;
		$this->parent2->CurrentValue = $this->parent2->FormValue;
		$this->id2->CurrentValue = $this->id2->FormValue;
		$this->rekening2->CurrentValue = $this->rekening2->FormValue;
		$this->tipe->CurrentValue = $this->tipe->FormValue;
		$this->posisi->CurrentValue = $this->posisi->FormValue;
		$this->laporan->CurrentValue = $this->laporan->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
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
		$this->parent->setDbValue($rs->fields('parent'));
		$this->parent1->setDbValue($rs->fields('parent1'));
		$this->id1->setDbValue($rs->fields('id1'));
		$this->rekening1->setDbValue($rs->fields('rekening1'));
		$this->parent2->setDbValue($rs->fields('parent2'));
		$this->id2->setDbValue($rs->fields('id2'));
		$this->rekening2->setDbValue($rs->fields('rekening2'));
		$this->tipe->setDbValue($rs->fields('tipe'));
		$this->posisi->setDbValue($rs->fields('posisi'));
		$this->laporan->setDbValue($rs->fields('laporan'));
		$this->status->setDbValue($rs->fields('status'));
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
		$this->parent->DbValue = $row['parent'];
		$this->parent1->DbValue = $row['parent1'];
		$this->id1->DbValue = $row['id1'];
		$this->rekening1->DbValue = $row['rekening1'];
		$this->parent2->DbValue = $row['parent2'];
		$this->id2->DbValue = $row['id2'];
		$this->rekening2->DbValue = $row['rekening2'];
		$this->tipe->DbValue = $row['tipe'];
		$this->posisi->DbValue = $row['posisi'];
		$this->laporan->DbValue = $row['laporan'];
		$this->status->DbValue = $row['status'];
		$this->keterangan->DbValue = $row['keterangan'];
		$this->active->DbValue = $row['active'];
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
		// parent
		// parent1
		// id1
		// rekening1
		// parent2
		// id2
		// rekening2
		// tipe
		// posisi
		// laporan
		// status
		// keterangan
		// active

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// group
		$this->group->ViewValue = $this->group->CurrentValue;
		if (strval($this->group->CurrentValue) <> "") {
			$sFilterWrk = "`group`" . ew_SearchString("=", $this->group->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `group`, `rekening` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `trekening2`";
		$sWhereWrk = "";
		$this->group->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->group, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->group->ViewValue = $this->group->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->group->ViewValue = $this->group->CurrentValue;
			}
		} else {
			$this->group->ViewValue = NULL;
		}
		$this->group->ViewCustomAttributes = "";

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// rekening
		$this->rekening->ViewValue = $this->rekening->CurrentValue;
		$this->rekening->ViewCustomAttributes = "";

		// parent
		if (strval($this->parent->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->parent->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `id`, `rekening` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `trekening2`";
		$sWhereWrk = "";
		$this->parent->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->parent, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->parent->ViewValue = $this->parent->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->parent->ViewValue = $this->parent->CurrentValue;
			}
		} else {
			$this->parent->ViewValue = NULL;
		}
		$this->parent->ViewCustomAttributes = "";

		// parent1
		$this->parent1->ViewValue = $this->parent1->CurrentValue;
		if (strval($this->parent1->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->parent1->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `id`, `rekening` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `trekening2`";
		$sWhereWrk = "";
		$this->parent1->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->parent1, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->parent1->ViewValue = $this->parent1->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->parent1->ViewValue = $this->parent1->CurrentValue;
			}
		} else {
			$this->parent1->ViewValue = NULL;
		}
		$this->parent1->ViewCustomAttributes = "";

		// id1
		$this->id1->ViewValue = $this->id1->CurrentValue;
		$this->id1->ViewCustomAttributes = "";

		// rekening1
		$this->rekening1->ViewValue = $this->rekening1->CurrentValue;
		$this->rekening1->ViewCustomAttributes = "";

		// parent2
		$this->parent2->ViewValue = $this->parent2->CurrentValue;
		if (strval($this->parent2->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->parent2->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `id`, `rekening` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `trekening2`";
		$sWhereWrk = "";
		$this->parent2->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->parent2, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->parent2->ViewValue = $this->parent2->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->parent2->ViewValue = $this->parent2->CurrentValue;
			}
		} else {
			$this->parent2->ViewValue = NULL;
		}
		$this->parent2->ViewCustomAttributes = "";

		// id2
		$this->id2->ViewValue = $this->id2->CurrentValue;
		$this->id2->ViewCustomAttributes = "";

		// rekening2
		$this->rekening2->ViewValue = $this->rekening2->CurrentValue;
		$this->rekening2->ViewCustomAttributes = "";

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

			// parent1
			$this->parent1->LinkCustomAttributes = "";
			$this->parent1->HrefValue = "";
			$this->parent1->TooltipValue = "";

			// id1
			$this->id1->LinkCustomAttributes = "";
			$this->id1->HrefValue = "";
			$this->id1->TooltipValue = "";

			// rekening1
			$this->rekening1->LinkCustomAttributes = "";
			$this->rekening1->HrefValue = "";
			$this->rekening1->TooltipValue = "";

			// parent2
			$this->parent2->LinkCustomAttributes = "";
			$this->parent2->HrefValue = "";
			$this->parent2->TooltipValue = "";

			// id2
			$this->id2->LinkCustomAttributes = "";
			$this->id2->HrefValue = "";
			$this->id2->TooltipValue = "";

			// rekening2
			$this->rekening2->LinkCustomAttributes = "";
			$this->rekening2->HrefValue = "";
			$this->rekening2->TooltipValue = "";

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

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";
			$this->keterangan->TooltipValue = "";

			// active
			$this->active->LinkCustomAttributes = "";
			$this->active->HrefValue = "";
			$this->active->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// group
			$this->group->EditAttrs["class"] = "form-control";
			$this->group->EditCustomAttributes = "";
			$this->group->EditValue = ew_HtmlEncode($this->group->CurrentValue);
			if (strval($this->group->CurrentValue) <> "") {
				$sFilterWrk = "`group`" . ew_SearchString("=", $this->group->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `group`, `rekening` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `trekening2`";
			$sWhereWrk = "";
			$this->group->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->group, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->group->EditValue = $this->group->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->group->EditValue = ew_HtmlEncode($this->group->CurrentValue);
				}
			} else {
				$this->group->EditValue = NULL;
			}
			$this->group->PlaceHolder = ew_RemoveHtml($this->group->FldCaption());

			// parent1
			$this->parent1->EditAttrs["class"] = "form-control";
			$this->parent1->EditCustomAttributes = "";
			$this->parent1->EditValue = ew_HtmlEncode($this->parent1->CurrentValue);
			if (strval($this->parent1->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->parent1->CurrentValue, EW_DATATYPE_STRING, "");
			$sSqlWrk = "SELECT `id`, `rekening` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `trekening2`";
			$sWhereWrk = "";
			$this->parent1->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->parent1, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->parent1->EditValue = $this->parent1->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->parent1->EditValue = ew_HtmlEncode($this->parent1->CurrentValue);
				}
			} else {
				$this->parent1->EditValue = NULL;
			}
			$this->parent1->PlaceHolder = ew_RemoveHtml($this->parent1->FldCaption());

			// id1
			$this->id1->EditAttrs["class"] = "form-control";
			$this->id1->EditCustomAttributes = "";
			$this->id1->EditValue = ew_HtmlEncode($this->id1->CurrentValue);
			$this->id1->PlaceHolder = ew_RemoveHtml($this->id1->FldCaption());

			// rekening1
			$this->rekening1->EditAttrs["class"] = "form-control";
			$this->rekening1->EditCustomAttributes = "";
			$this->rekening1->EditValue = ew_HtmlEncode($this->rekening1->CurrentValue);
			$this->rekening1->PlaceHolder = ew_RemoveHtml($this->rekening1->FldCaption());

			// parent2
			$this->parent2->EditAttrs["class"] = "form-control";
			$this->parent2->EditCustomAttributes = "";
			$this->parent2->EditValue = ew_HtmlEncode($this->parent2->CurrentValue);
			if (strval($this->parent2->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->parent2->CurrentValue, EW_DATATYPE_STRING, "");
			$sSqlWrk = "SELECT `id`, `rekening` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `trekening2`";
			$sWhereWrk = "";
			$this->parent2->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->parent2, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->parent2->EditValue = $this->parent2->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->parent2->EditValue = ew_HtmlEncode($this->parent2->CurrentValue);
				}
			} else {
				$this->parent2->EditValue = NULL;
			}
			$this->parent2->PlaceHolder = ew_RemoveHtml($this->parent2->FldCaption());

			// id2
			$this->id2->EditAttrs["class"] = "form-control";
			$this->id2->EditCustomAttributes = "";
			$this->id2->EditValue = ew_HtmlEncode($this->id2->CurrentValue);
			$this->id2->PlaceHolder = ew_RemoveHtml($this->id2->FldCaption());

			// rekening2
			$this->rekening2->EditAttrs["class"] = "form-control";
			$this->rekening2->EditCustomAttributes = "";
			$this->rekening2->EditValue = ew_HtmlEncode($this->rekening2->CurrentValue);
			$this->rekening2->PlaceHolder = ew_RemoveHtml($this->rekening2->FldCaption());

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

			// keterangan
			$this->keterangan->EditAttrs["class"] = "form-control";
			$this->keterangan->EditCustomAttributes = "";
			$this->keterangan->EditValue = ew_HtmlEncode($this->keterangan->CurrentValue);
			$this->keterangan->PlaceHolder = ew_RemoveHtml($this->keterangan->FldCaption());

			// active
			$this->active->EditCustomAttributes = "";
			$this->active->EditValue = $this->active->Options(FALSE);

			// Edit refer script
			// group

			$this->group->LinkCustomAttributes = "";
			$this->group->HrefValue = "";

			// parent1
			$this->parent1->LinkCustomAttributes = "";
			$this->parent1->HrefValue = "";

			// id1
			$this->id1->LinkCustomAttributes = "";
			$this->id1->HrefValue = "";

			// rekening1
			$this->rekening1->LinkCustomAttributes = "";
			$this->rekening1->HrefValue = "";

			// parent2
			$this->parent2->LinkCustomAttributes = "";
			$this->parent2->HrefValue = "";

			// id2
			$this->id2->LinkCustomAttributes = "";
			$this->id2->HrefValue = "";

			// rekening2
			$this->rekening2->LinkCustomAttributes = "";
			$this->rekening2->HrefValue = "";

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
		if (!ew_CheckInteger($this->group->FormValue)) {
			ew_AddMessage($gsFormError, $this->group->FldErrMsg());
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

			// group
			$this->group->SetDbValueDef($rsnew, $this->group->CurrentValue, NULL, $this->group->ReadOnly);

			// parent1
			$this->parent1->SetDbValueDef($rsnew, $this->parent1->CurrentValue, NULL, $this->parent1->ReadOnly);

			// id1
			$this->id1->SetDbValueDef($rsnew, $this->id1->CurrentValue, NULL, $this->id1->ReadOnly);

			// rekening1
			$this->rekening1->SetDbValueDef($rsnew, $this->rekening1->CurrentValue, NULL, $this->rekening1->ReadOnly);

			// parent2
			$this->parent2->SetDbValueDef($rsnew, $this->parent2->CurrentValue, NULL, $this->parent2->ReadOnly);

			// id2
			$this->id2->SetDbValueDef($rsnew, $this->id2->CurrentValue, NULL, $this->id2->ReadOnly);

			// rekening2
			$this->rekening2->SetDbValueDef($rsnew, $this->rekening2->CurrentValue, NULL, $this->rekening2->ReadOnly);

			// tipe
			$this->tipe->SetDbValueDef($rsnew, $this->tipe->CurrentValue, NULL, $this->tipe->ReadOnly);

			// posisi
			$this->posisi->SetDbValueDef($rsnew, $this->posisi->CurrentValue, NULL, $this->posisi->ReadOnly);

			// laporan
			$this->laporan->SetDbValueDef($rsnew, $this->laporan->CurrentValue, NULL, $this->laporan->ReadOnly);

			// status
			$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, NULL, $this->status->ReadOnly);

			// keterangan
			$this->keterangan->SetDbValueDef($rsnew, $this->keterangan->CurrentValue, NULL, $this->keterangan->ReadOnly);

			// active
			$this->active->SetDbValueDef($rsnew, $this->active->CurrentValue, NULL, $this->active->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("trekening2list.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_group":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `group` AS `LinkFld`, `rekening` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `trekening2`";
			$sWhereWrk = "{filter}";
			$this->group->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`group` = {filter_value}', "t0" => "20", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->group, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_parent1":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `rekening` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `trekening2`";
			$sWhereWrk = "{filter}";
			$this->parent1->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` = {filter_value}', "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->parent1, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_parent2":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `rekening` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `trekening2`";
			$sWhereWrk = "{filter}";
			$this->parent2->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` = {filter_value}', "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->parent2, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_group":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `group`, `rekening` AS `DispFld` FROM `trekening2`";
			$sWhereWrk = "`rekening` LIKE '{query_value}%'";
			$this->group->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->group, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_parent1":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `rekening` AS `DispFld` FROM `trekening2`";
			$sWhereWrk = "`rekening` LIKE '{query_value}%'";
			$this->parent1->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->parent1, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_parent2":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id`, `rekening` AS `DispFld` FROM `trekening2`";
			$sWhereWrk = "`rekening` LIKE '{query_value}%'";
			$this->parent2->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->parent2, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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
if (!isset($trekening2_edit)) $trekening2_edit = new ctrekening2_edit();

// Page init
$trekening2_edit->Page_Init();

// Page main
$trekening2_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$trekening2_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = ftrekening2edit = new ew_Form("ftrekening2edit", "edit");

// Validate form
ftrekening2edit.Validate = function() {
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
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($trekening2->group->FldErrMsg()) ?>");

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
ftrekening2edit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftrekening2edit.ValidateRequired = true;
<?php } else { ?>
ftrekening2edit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftrekening2edit.Lists["x_group"] = {"LinkField":"x_group","Ajax":true,"AutoFill":false,"DisplayFields":["x_rekening","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"trekening2"};
ftrekening2edit.Lists["x_parent1"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_rekening","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"trekening2"};
ftrekening2edit.Lists["x_parent2"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_rekening","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"trekening2"};
ftrekening2edit.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftrekening2edit.Lists["x_active"].Options = <?php echo json_encode($trekening2->active->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$trekening2_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $trekening2_edit->ShowPageHeader(); ?>
<?php
$trekening2_edit->ShowMessage();
?>
<form name="ftrekening2edit" id="ftrekening2edit" class="<?php echo $trekening2_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($trekening2_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $trekening2_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="trekening2">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($trekening2_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($trekening2->group->Visible) { // group ?>
	<div id="r_group" class="form-group">
		<label id="elh_trekening2_group" class="col-sm-2 control-label ewLabel"><?php echo $trekening2->group->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $trekening2->group->CellAttributes() ?>>
<span id="el_trekening2_group">
<?php
$wrkonchange = trim(" " . @$trekening2->group->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$trekening2->group->EditAttrs["onchange"] = "";
?>
<span id="as_x_group" style="white-space: nowrap; z-index: 8990">
	<input type="text" name="sv_x_group" id="sv_x_group" value="<?php echo $trekening2->group->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($trekening2->group->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($trekening2->group->getPlaceHolder()) ?>"<?php echo $trekening2->group->EditAttributes() ?>>
</span>
<input type="hidden" data-table="trekening2" data-field="x_group" data-value-separator="<?php echo $trekening2->group->DisplayValueSeparatorAttribute() ?>" name="x_group" id="x_group" value="<?php echo ew_HtmlEncode($trekening2->group->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x_group" id="q_x_group" value="<?php echo $trekening2->group->LookupFilterQuery(true) ?>">
<script type="text/javascript">
ftrekening2edit.CreateAutoSuggest({"id":"x_group","forceSelect":false});
</script>
</span>
<?php echo $trekening2->group->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($trekening2->parent1->Visible) { // parent1 ?>
	<div id="r_parent1" class="form-group">
		<label id="elh_trekening2_parent1" class="col-sm-2 control-label ewLabel"><?php echo $trekening2->parent1->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $trekening2->parent1->CellAttributes() ?>>
<span id="el_trekening2_parent1">
<?php
$wrkonchange = trim(" " . @$trekening2->parent1->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$trekening2->parent1->EditAttrs["onchange"] = "";
?>
<span id="as_x_parent1" style="white-space: nowrap; z-index: 8950">
	<input type="text" name="sv_x_parent1" id="sv_x_parent1" value="<?php echo $trekening2->parent1->EditValue ?>" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($trekening2->parent1->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($trekening2->parent1->getPlaceHolder()) ?>"<?php echo $trekening2->parent1->EditAttributes() ?>>
</span>
<input type="hidden" data-table="trekening2" data-field="x_parent1" data-value-separator="<?php echo $trekening2->parent1->DisplayValueSeparatorAttribute() ?>" name="x_parent1" id="x_parent1" value="<?php echo ew_HtmlEncode($trekening2->parent1->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x_parent1" id="q_x_parent1" value="<?php echo $trekening2->parent1->LookupFilterQuery(true) ?>">
<script type="text/javascript">
ftrekening2edit.CreateAutoSuggest({"id":"x_parent1","forceSelect":false});
</script>
</span>
<?php echo $trekening2->parent1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($trekening2->id1->Visible) { // id1 ?>
	<div id="r_id1" class="form-group">
		<label id="elh_trekening2_id1" for="x_id1" class="col-sm-2 control-label ewLabel"><?php echo $trekening2->id1->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $trekening2->id1->CellAttributes() ?>>
<span id="el_trekening2_id1">
<input type="text" data-table="trekening2" data-field="x_id1" name="x_id1" id="x_id1" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($trekening2->id1->getPlaceHolder()) ?>" value="<?php echo $trekening2->id1->EditValue ?>"<?php echo $trekening2->id1->EditAttributes() ?>>
</span>
<?php echo $trekening2->id1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($trekening2->rekening1->Visible) { // rekening1 ?>
	<div id="r_rekening1" class="form-group">
		<label id="elh_trekening2_rekening1" for="x_rekening1" class="col-sm-2 control-label ewLabel"><?php echo $trekening2->rekening1->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $trekening2->rekening1->CellAttributes() ?>>
<span id="el_trekening2_rekening1">
<input type="text" data-table="trekening2" data-field="x_rekening1" name="x_rekening1" id="x_rekening1" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($trekening2->rekening1->getPlaceHolder()) ?>" value="<?php echo $trekening2->rekening1->EditValue ?>"<?php echo $trekening2->rekening1->EditAttributes() ?>>
</span>
<?php echo $trekening2->rekening1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($trekening2->parent2->Visible) { // parent2 ?>
	<div id="r_parent2" class="form-group">
		<label id="elh_trekening2_parent2" class="col-sm-2 control-label ewLabel"><?php echo $trekening2->parent2->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $trekening2->parent2->CellAttributes() ?>>
<span id="el_trekening2_parent2">
<?php
$wrkonchange = trim(" " . @$trekening2->parent2->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$trekening2->parent2->EditAttrs["onchange"] = "";
?>
<span id="as_x_parent2" style="white-space: nowrap; z-index: 8920">
	<input type="text" name="sv_x_parent2" id="sv_x_parent2" value="<?php echo $trekening2->parent2->EditValue ?>" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($trekening2->parent2->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($trekening2->parent2->getPlaceHolder()) ?>"<?php echo $trekening2->parent2->EditAttributes() ?>>
</span>
<input type="hidden" data-table="trekening2" data-field="x_parent2" data-value-separator="<?php echo $trekening2->parent2->DisplayValueSeparatorAttribute() ?>" name="x_parent2" id="x_parent2" value="<?php echo ew_HtmlEncode($trekening2->parent2->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x_parent2" id="q_x_parent2" value="<?php echo $trekening2->parent2->LookupFilterQuery(true) ?>">
<script type="text/javascript">
ftrekening2edit.CreateAutoSuggest({"id":"x_parent2","forceSelect":false});
</script>
</span>
<?php echo $trekening2->parent2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($trekening2->id2->Visible) { // id2 ?>
	<div id="r_id2" class="form-group">
		<label id="elh_trekening2_id2" for="x_id2" class="col-sm-2 control-label ewLabel"><?php echo $trekening2->id2->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $trekening2->id2->CellAttributes() ?>>
<span id="el_trekening2_id2">
<input type="text" data-table="trekening2" data-field="x_id2" name="x_id2" id="x_id2" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($trekening2->id2->getPlaceHolder()) ?>" value="<?php echo $trekening2->id2->EditValue ?>"<?php echo $trekening2->id2->EditAttributes() ?>>
</span>
<?php echo $trekening2->id2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($trekening2->rekening2->Visible) { // rekening2 ?>
	<div id="r_rekening2" class="form-group">
		<label id="elh_trekening2_rekening2" for="x_rekening2" class="col-sm-2 control-label ewLabel"><?php echo $trekening2->rekening2->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $trekening2->rekening2->CellAttributes() ?>>
<span id="el_trekening2_rekening2">
<input type="text" data-table="trekening2" data-field="x_rekening2" name="x_rekening2" id="x_rekening2" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($trekening2->rekening2->getPlaceHolder()) ?>" value="<?php echo $trekening2->rekening2->EditValue ?>"<?php echo $trekening2->rekening2->EditAttributes() ?>>
</span>
<?php echo $trekening2->rekening2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($trekening2->tipe->Visible) { // tipe ?>
	<div id="r_tipe" class="form-group">
		<label id="elh_trekening2_tipe" for="x_tipe" class="col-sm-2 control-label ewLabel"><?php echo $trekening2->tipe->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $trekening2->tipe->CellAttributes() ?>>
<span id="el_trekening2_tipe">
<input type="text" data-table="trekening2" data-field="x_tipe" name="x_tipe" id="x_tipe" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($trekening2->tipe->getPlaceHolder()) ?>" value="<?php echo $trekening2->tipe->EditValue ?>"<?php echo $trekening2->tipe->EditAttributes() ?>>
</span>
<?php echo $trekening2->tipe->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($trekening2->posisi->Visible) { // posisi ?>
	<div id="r_posisi" class="form-group">
		<label id="elh_trekening2_posisi" for="x_posisi" class="col-sm-2 control-label ewLabel"><?php echo $trekening2->posisi->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $trekening2->posisi->CellAttributes() ?>>
<span id="el_trekening2_posisi">
<input type="text" data-table="trekening2" data-field="x_posisi" name="x_posisi" id="x_posisi" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($trekening2->posisi->getPlaceHolder()) ?>" value="<?php echo $trekening2->posisi->EditValue ?>"<?php echo $trekening2->posisi->EditAttributes() ?>>
</span>
<?php echo $trekening2->posisi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($trekening2->laporan->Visible) { // laporan ?>
	<div id="r_laporan" class="form-group">
		<label id="elh_trekening2_laporan" for="x_laporan" class="col-sm-2 control-label ewLabel"><?php echo $trekening2->laporan->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $trekening2->laporan->CellAttributes() ?>>
<span id="el_trekening2_laporan">
<input type="text" data-table="trekening2" data-field="x_laporan" name="x_laporan" id="x_laporan" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($trekening2->laporan->getPlaceHolder()) ?>" value="<?php echo $trekening2->laporan->EditValue ?>"<?php echo $trekening2->laporan->EditAttributes() ?>>
</span>
<?php echo $trekening2->laporan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($trekening2->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_trekening2_status" for="x_status" class="col-sm-2 control-label ewLabel"><?php echo $trekening2->status->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $trekening2->status->CellAttributes() ?>>
<span id="el_trekening2_status">
<input type="text" data-table="trekening2" data-field="x_status" name="x_status" id="x_status" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($trekening2->status->getPlaceHolder()) ?>" value="<?php echo $trekening2->status->EditValue ?>"<?php echo $trekening2->status->EditAttributes() ?>>
</span>
<?php echo $trekening2->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($trekening2->keterangan->Visible) { // keterangan ?>
	<div id="r_keterangan" class="form-group">
		<label id="elh_trekening2_keterangan" for="x_keterangan" class="col-sm-2 control-label ewLabel"><?php echo $trekening2->keterangan->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $trekening2->keterangan->CellAttributes() ?>>
<span id="el_trekening2_keterangan">
<input type="text" data-table="trekening2" data-field="x_keterangan" name="x_keterangan" id="x_keterangan" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($trekening2->keterangan->getPlaceHolder()) ?>" value="<?php echo $trekening2->keterangan->EditValue ?>"<?php echo $trekening2->keterangan->EditAttributes() ?>>
</span>
<?php echo $trekening2->keterangan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($trekening2->active->Visible) { // active ?>
	<div id="r_active" class="form-group">
		<label id="elh_trekening2_active" class="col-sm-2 control-label ewLabel"><?php echo $trekening2->active->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $trekening2->active->CellAttributes() ?>>
<span id="el_trekening2_active">
<div id="tp_x_active" class="ewTemplate"><input type="radio" data-table="trekening2" data-field="x_active" data-value-separator="<?php echo $trekening2->active->DisplayValueSeparatorAttribute() ?>" name="x_active" id="x_active" value="{value}"<?php echo $trekening2->active->EditAttributes() ?>></div>
<div id="dsl_x_active" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $trekening2->active->RadioButtonListHtml(FALSE, "x_active") ?>
</div></div>
</span>
<?php echo $trekening2->active->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-table="trekening2" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($trekening2->id->CurrentValue) ?>">
<?php if (!$trekening2_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $trekening2_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ftrekening2edit.Init();
</script>
<?php
$trekening2_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$trekening2_edit->Page_Terminate();
?>
