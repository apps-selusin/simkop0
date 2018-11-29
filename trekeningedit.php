<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "trekeninginfo.php" ?>
<?php include_once "vrekeninggridcls.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$trekening_edit = NULL; // Initialize page object first

class ctrekening_edit extends ctrekening {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'trekening';

	// Page object name
	var $PageObjName = 'trekening_edit';

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

		// Table object (trekening)
		if (!isset($GLOBALS["trekening"]) || get_class($GLOBALS["trekening"]) == "ctrekening") {
			$GLOBALS["trekening"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["trekening"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'trekening', TRUE);

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

			// Process auto fill for detail table 'vrekening'
			if (@$_POST["grid"] == "fvrekeninggrid") {
				if (!isset($GLOBALS["vrekening_grid"])) $GLOBALS["vrekening_grid"] = new cvrekening_grid;
				$GLOBALS["vrekening_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}
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
		global $EW_EXPORT, $trekening;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($trekening);
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

			// Set up detail parameters
			$this->SetUpDetailParms();
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->id->CurrentValue == "") {
			$this->Page_Terminate("trekeninglist.php"); // Invalid key, return to list
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
					$this->Page_Terminate("trekeninglist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			Case "U": // Update
				if ($this->getCurrentDetailTable() <> "") // Master/detail edit
					$sReturnUrl = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $this->getCurrentDetailTable()); // Master/Detail view page
				else
					$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "trekeninglist.php")
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

					// Set up detail parameters
					$this->SetUpDetailParms();
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
		$this->LoadRow();
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
		if (strval($this->group->CurrentValue) <> "") {
			$sFilterWrk = "`group`" . ew_SearchString("=", $this->group->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `group`, `rekening` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `trekening`";
		$sWhereWrk = "";
		$this->group->LookupFilters = array();
		$lookuptblfilter = "`tipe` = 'GROUP'";
		ew_AddFilter($sWhereWrk, $lookuptblfilter);
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

		// tipe
		if (strval($this->tipe->CurrentValue) <> "") {
			$this->tipe->ViewValue = "";
			$arwrk = explode(",", strval($this->tipe->CurrentValue));
			$cnt = count($arwrk);
			for ($ari = 0; $ari < $cnt; $ari++) {
				$this->tipe->ViewValue .= $this->tipe->OptionCaption(trim($arwrk[$ari]));
				if ($ari < $cnt-1) $this->tipe->ViewValue .= ew_ViewOptionSeparator($ari);
			}
		} else {
			$this->tipe->ViewValue = NULL;
		}
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// group
			$this->group->EditAttrs["class"] = "form-control";
			$this->group->EditCustomAttributes = "";
			if (trim(strval($this->group->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`group`" . ew_SearchString("=", $this->group->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `group`, `rekening` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `trekening`";
			$sWhereWrk = "";
			$this->group->LookupFilters = array();
			$lookuptblfilter = "`tipe` = 'GROUP'";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->group, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->group->EditValue = $arwrk;

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// rekening
			$this->rekening->EditAttrs["class"] = "form-control";
			$this->rekening->EditCustomAttributes = "";
			$this->rekening->EditValue = ew_HtmlEncode($this->rekening->CurrentValue);
			$this->rekening->PlaceHolder = ew_RemoveHtml($this->rekening->FldCaption());

			// tipe
			$this->tipe->EditAttrs["class"] = "form-control";
			$this->tipe->EditCustomAttributes = "";
			$this->tipe->EditValue = $this->tipe->Options(FALSE);

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

			// Edit refer script
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
		if (!$this->id->FldIsDetailKey && !is_null($this->id->FormValue) && $this->id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id->FldCaption(), $this->id->ReqErrMsg));
		}
		if (!$this->rekening->FldIsDetailKey && !is_null($this->rekening->FormValue) && $this->rekening->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->rekening->FldCaption(), $this->rekening->ReqErrMsg));
		}
		if ($this->tipe->FormValue == "") {
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

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("vrekening", $DetailTblVar) && $GLOBALS["vrekening"]->DetailEdit) {
			if (!isset($GLOBALS["vrekening_grid"])) $GLOBALS["vrekening_grid"] = new cvrekening_grid(); // get detail page object
			$GLOBALS["vrekening_grid"]->ValidateGridForm();
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

			// Begin transaction
			if ($this->getCurrentDetailTable() <> "")
				$conn->BeginTrans();

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// group
			$this->group->SetDbValueDef($rsnew, $this->group->CurrentValue, NULL, $this->group->ReadOnly);

			// id
			// rekening

			$this->rekening->SetDbValueDef($rsnew, $this->rekening->CurrentValue, NULL, $this->rekening->ReadOnly);

			// tipe
			$this->tipe->SetDbValueDef($rsnew, $this->tipe->CurrentValue, NULL, $this->tipe->ReadOnly);

			// posisi
			$this->posisi->SetDbValueDef($rsnew, $this->posisi->CurrentValue, NULL, $this->posisi->ReadOnly);

			// laporan
			$this->laporan->SetDbValueDef($rsnew, $this->laporan->CurrentValue, NULL, $this->laporan->ReadOnly);

			// status
			$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, NULL, $this->status->ReadOnly);

			// parent
			$this->parent->SetDbValueDef($rsnew, $this->parent->CurrentValue, NULL, $this->parent->ReadOnly);

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

				// Update detail records
				$DetailTblVar = explode(",", $this->getCurrentDetailTable());
				if ($EditRow) {
					if (in_array("vrekening", $DetailTblVar) && $GLOBALS["vrekening"]->DetailEdit) {
						if (!isset($GLOBALS["vrekening_grid"])) $GLOBALS["vrekening_grid"] = new cvrekening_grid(); // Get detail page object
						$EditRow = $GLOBALS["vrekening_grid"]->GridUpdate();
					}
				}

				// Commit/Rollback transaction
				if ($this->getCurrentDetailTable() <> "") {
					if ($EditRow) {
						$conn->CommitTrans(); // Commit transaction
					} else {
						$conn->RollbackTrans(); // Rollback transaction
					}
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

	// Set up detail parms based on QueryString
	function SetUpDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("vrekening", $DetailTblVar)) {
				if (!isset($GLOBALS["vrekening_grid"]))
					$GLOBALS["vrekening_grid"] = new cvrekening_grid;
				if ($GLOBALS["vrekening_grid"]->DetailEdit) {
					$GLOBALS["vrekening_grid"]->CurrentMode = "edit";
					$GLOBALS["vrekening_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["vrekening_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["vrekening_grid"]->setStartRecordNumber(1);
					$GLOBALS["vrekening_grid"]->parent->FldIsDetailKey = TRUE;
					$GLOBALS["vrekening_grid"]->parent->CurrentValue = $this->id->CurrentValue;
					$GLOBALS["vrekening_grid"]->parent->setSessionValue($GLOBALS["vrekening_grid"]->parent->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("trekeninglist.php"), "", $this->TableVar, TRUE);
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
			$sSqlWrk = "SELECT `group` AS `LinkFld`, `rekening` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `trekening`";
			$sWhereWrk = "";
			$this->group->LookupFilters = array();
			$lookuptblfilter = "`tipe` = 'GROUP'";
			ew_AddFilter($sWhereWrk, $lookuptblfilter);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`group` = {filter_value}', "t0" => "20", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->group, $sWhereWrk); // Call Lookup selecting
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
if (!isset($trekening_edit)) $trekening_edit = new ctrekening_edit();

// Page init
$trekening_edit->Page_Init();

// Page main
$trekening_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$trekening_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = ftrekeningedit = new ew_Form("ftrekeningedit", "edit");

// Validate form
ftrekeningedit.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $trekening->group->FldCaption(), $trekening->group->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $trekening->id->FldCaption(), $trekening->id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_rekening");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $trekening->rekening->FldCaption(), $trekening->rekening->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tipe[]");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $trekening->tipe->FldCaption(), $trekening->tipe->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_posisi");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $trekening->posisi->FldCaption(), $trekening->posisi->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_laporan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $trekening->laporan->FldCaption(), $trekening->laporan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $trekening->status->FldCaption(), $trekening->status->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_parent");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $trekening->parent->FldCaption(), $trekening->parent->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_keterangan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $trekening->keterangan->FldCaption(), $trekening->keterangan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_active");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $trekening->active->FldCaption(), $trekening->active->ReqErrMsg)) ?>");

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
ftrekeningedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftrekeningedit.ValidateRequired = true;
<?php } else { ?>
ftrekeningedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftrekeningedit.Lists["x_group"] = {"LinkField":"x_group","Ajax":true,"AutoFill":false,"DisplayFields":["x_rekening","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"trekening"};
ftrekeningedit.Lists["x_tipe[]"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftrekeningedit.Lists["x_tipe[]"].Options = <?php echo json_encode($trekening->tipe->Options()) ?>;
ftrekeningedit.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftrekeningedit.Lists["x_active"].Options = <?php echo json_encode($trekening->active->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$trekening_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $trekening_edit->ShowPageHeader(); ?>
<?php
$trekening_edit->ShowMessage();
?>
<form name="ftrekeningedit" id="ftrekeningedit" class="<?php echo $trekening_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($trekening_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $trekening_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="trekening">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($trekening_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($trekening->group->Visible) { // group ?>
	<div id="r_group" class="form-group">
		<label id="elh_trekening_group" for="x_group" class="col-sm-2 control-label ewLabel"><?php echo $trekening->group->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $trekening->group->CellAttributes() ?>>
<span id="el_trekening_group">
<select data-table="trekening" data-field="x_group" data-value-separator="<?php echo $trekening->group->DisplayValueSeparatorAttribute() ?>" id="x_group" name="x_group"<?php echo $trekening->group->EditAttributes() ?>>
<?php echo $trekening->group->SelectOptionListHtml("x_group") ?>
</select>
<input type="hidden" name="s_x_group" id="s_x_group" value="<?php echo $trekening->group->LookupFilterQuery() ?>">
</span>
<?php echo $trekening->group->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($trekening->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_trekening_id" for="x_id" class="col-sm-2 control-label ewLabel"><?php echo $trekening->id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $trekening->id->CellAttributes() ?>>
<span id="el_trekening_id">
<span<?php echo $trekening->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $trekening->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="trekening" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($trekening->id->CurrentValue) ?>">
<?php echo $trekening->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($trekening->rekening->Visible) { // rekening ?>
	<div id="r_rekening" class="form-group">
		<label id="elh_trekening_rekening" for="x_rekening" class="col-sm-2 control-label ewLabel"><?php echo $trekening->rekening->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $trekening->rekening->CellAttributes() ?>>
<span id="el_trekening_rekening">
<input type="text" data-table="trekening" data-field="x_rekening" name="x_rekening" id="x_rekening" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($trekening->rekening->getPlaceHolder()) ?>" value="<?php echo $trekening->rekening->EditValue ?>"<?php echo $trekening->rekening->EditAttributes() ?>>
</span>
<?php echo $trekening->rekening->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($trekening->tipe->Visible) { // tipe ?>
	<div id="r_tipe" class="form-group">
		<label id="elh_trekening_tipe" for="x_tipe" class="col-sm-2 control-label ewLabel"><?php echo $trekening->tipe->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $trekening->tipe->CellAttributes() ?>>
<span id="el_trekening_tipe">
<select data-table="trekening" data-field="x_tipe" data-value-separator="<?php echo $trekening->tipe->DisplayValueSeparatorAttribute() ?>" id="x_tipe[]" name="x_tipe[]" multiple="multiple"<?php echo $trekening->tipe->EditAttributes() ?>>
<?php echo $trekening->tipe->SelectOptionListHtml("x_tipe[]") ?>
</select>
</span>
<?php echo $trekening->tipe->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($trekening->posisi->Visible) { // posisi ?>
	<div id="r_posisi" class="form-group">
		<label id="elh_trekening_posisi" for="x_posisi" class="col-sm-2 control-label ewLabel"><?php echo $trekening->posisi->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $trekening->posisi->CellAttributes() ?>>
<span id="el_trekening_posisi">
<input type="text" data-table="trekening" data-field="x_posisi" name="x_posisi" id="x_posisi" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($trekening->posisi->getPlaceHolder()) ?>" value="<?php echo $trekening->posisi->EditValue ?>"<?php echo $trekening->posisi->EditAttributes() ?>>
</span>
<?php echo $trekening->posisi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($trekening->laporan->Visible) { // laporan ?>
	<div id="r_laporan" class="form-group">
		<label id="elh_trekening_laporan" for="x_laporan" class="col-sm-2 control-label ewLabel"><?php echo $trekening->laporan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $trekening->laporan->CellAttributes() ?>>
<span id="el_trekening_laporan">
<input type="text" data-table="trekening" data-field="x_laporan" name="x_laporan" id="x_laporan" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($trekening->laporan->getPlaceHolder()) ?>" value="<?php echo $trekening->laporan->EditValue ?>"<?php echo $trekening->laporan->EditAttributes() ?>>
</span>
<?php echo $trekening->laporan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($trekening->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_trekening_status" for="x_status" class="col-sm-2 control-label ewLabel"><?php echo $trekening->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $trekening->status->CellAttributes() ?>>
<span id="el_trekening_status">
<input type="text" data-table="trekening" data-field="x_status" name="x_status" id="x_status" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($trekening->status->getPlaceHolder()) ?>" value="<?php echo $trekening->status->EditValue ?>"<?php echo $trekening->status->EditAttributes() ?>>
</span>
<?php echo $trekening->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($trekening->parent->Visible) { // parent ?>
	<div id="r_parent" class="form-group">
		<label id="elh_trekening_parent" for="x_parent" class="col-sm-2 control-label ewLabel"><?php echo $trekening->parent->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $trekening->parent->CellAttributes() ?>>
<span id="el_trekening_parent">
<input type="text" data-table="trekening" data-field="x_parent" name="x_parent" id="x_parent" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($trekening->parent->getPlaceHolder()) ?>" value="<?php echo $trekening->parent->EditValue ?>"<?php echo $trekening->parent->EditAttributes() ?>>
</span>
<?php echo $trekening->parent->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($trekening->keterangan->Visible) { // keterangan ?>
	<div id="r_keterangan" class="form-group">
		<label id="elh_trekening_keterangan" for="x_keterangan" class="col-sm-2 control-label ewLabel"><?php echo $trekening->keterangan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $trekening->keterangan->CellAttributes() ?>>
<span id="el_trekening_keterangan">
<input type="text" data-table="trekening" data-field="x_keterangan" name="x_keterangan" id="x_keterangan" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($trekening->keterangan->getPlaceHolder()) ?>" value="<?php echo $trekening->keterangan->EditValue ?>"<?php echo $trekening->keterangan->EditAttributes() ?>>
</span>
<?php echo $trekening->keterangan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($trekening->active->Visible) { // active ?>
	<div id="r_active" class="form-group">
		<label id="elh_trekening_active" class="col-sm-2 control-label ewLabel"><?php echo $trekening->active->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $trekening->active->CellAttributes() ?>>
<span id="el_trekening_active">
<div id="tp_x_active" class="ewTemplate"><input type="radio" data-table="trekening" data-field="x_active" data-value-separator="<?php echo $trekening->active->DisplayValueSeparatorAttribute() ?>" name="x_active" id="x_active" value="{value}"<?php echo $trekening->active->EditAttributes() ?>></div>
<div id="dsl_x_active" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $trekening->active->RadioButtonListHtml(FALSE, "x_active") ?>
</div></div>
</span>
<?php echo $trekening->active->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php
	if (in_array("vrekening", explode(",", $trekening->getCurrentDetailTable())) && $vrekening->DetailEdit) {
?>
<?php if ($trekening->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("vrekening", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "vrekeninggrid.php" ?>
<?php } ?>
<?php if (!$trekening_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $trekening_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ftrekeningedit.Init();
</script>
<?php
$trekening_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$trekening_edit->Page_Terminate();
?>
