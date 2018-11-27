<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "texcelinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$texcel_add = NULL; // Initialize page object first

class ctexcel_add extends ctexcel {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'texcel';

	// Page object name
	var $PageObjName = 'texcel_add';

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

		// Table object (texcel)
		if (!isset($GLOBALS["texcel"]) || get_class($GLOBALS["texcel"]) == "ctexcel") {
			$GLOBALS["texcel"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["texcel"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'texcel', TRUE);

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
		$this->SHEET->SetVisibility();
		$this->NOMOR->SetVisibility();
		$this->A->SetVisibility();
		$this->B->SetVisibility();
		$this->C->SetVisibility();
		$this->D->SetVisibility();
		$this->E->SetVisibility();
		$this->F->SetVisibility();
		$this->G->SetVisibility();
		$this->H->SetVisibility();
		$this->_I->SetVisibility();
		$this->J->SetVisibility();
		$this->K->SetVisibility();
		$this->L->SetVisibility();
		$this->M->SetVisibility();
		$this->N->SetVisibility();
		$this->O->SetVisibility();
		$this->P->SetVisibility();
		$this->Q->SetVisibility();
		$this->R->SetVisibility();
		$this->S->SetVisibility();
		$this->T->SetVisibility();
		$this->U->SetVisibility();
		$this->V->SetVisibility();
		$this->W->SetVisibility();
		$this->X->SetVisibility();
		$this->Y->SetVisibility();
		$this->Z->SetVisibility();

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
		global $EW_EXPORT, $texcel;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($texcel);
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
			if (@$_GET["SHEET"] != "") {
				$this->SHEET->setQueryStringValue($_GET["SHEET"]);
				$this->setKey("SHEET", $this->SHEET->CurrentValue); // Set up key
			} else {
				$this->setKey("SHEET", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["NOMOR"] != "") {
				$this->NOMOR->setQueryStringValue($_GET["NOMOR"]);
				$this->setKey("NOMOR", $this->NOMOR->CurrentValue); // Set up key
			} else {
				$this->setKey("NOMOR", ""); // Clear key
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
					$this->Page_Terminate("texcellist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "texcellist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "texcelview.php")
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
		$this->SHEET->CurrentValue = NULL;
		$this->SHEET->OldValue = $this->SHEET->CurrentValue;
		$this->NOMOR->CurrentValue = 0;
		$this->A->CurrentValue = NULL;
		$this->A->OldValue = $this->A->CurrentValue;
		$this->B->CurrentValue = NULL;
		$this->B->OldValue = $this->B->CurrentValue;
		$this->C->CurrentValue = NULL;
		$this->C->OldValue = $this->C->CurrentValue;
		$this->D->CurrentValue = NULL;
		$this->D->OldValue = $this->D->CurrentValue;
		$this->E->CurrentValue = NULL;
		$this->E->OldValue = $this->E->CurrentValue;
		$this->F->CurrentValue = NULL;
		$this->F->OldValue = $this->F->CurrentValue;
		$this->G->CurrentValue = NULL;
		$this->G->OldValue = $this->G->CurrentValue;
		$this->H->CurrentValue = NULL;
		$this->H->OldValue = $this->H->CurrentValue;
		$this->_I->CurrentValue = NULL;
		$this->_I->OldValue = $this->_I->CurrentValue;
		$this->J->CurrentValue = NULL;
		$this->J->OldValue = $this->J->CurrentValue;
		$this->K->CurrentValue = NULL;
		$this->K->OldValue = $this->K->CurrentValue;
		$this->L->CurrentValue = NULL;
		$this->L->OldValue = $this->L->CurrentValue;
		$this->M->CurrentValue = NULL;
		$this->M->OldValue = $this->M->CurrentValue;
		$this->N->CurrentValue = NULL;
		$this->N->OldValue = $this->N->CurrentValue;
		$this->O->CurrentValue = NULL;
		$this->O->OldValue = $this->O->CurrentValue;
		$this->P->CurrentValue = NULL;
		$this->P->OldValue = $this->P->CurrentValue;
		$this->Q->CurrentValue = NULL;
		$this->Q->OldValue = $this->Q->CurrentValue;
		$this->R->CurrentValue = NULL;
		$this->R->OldValue = $this->R->CurrentValue;
		$this->S->CurrentValue = NULL;
		$this->S->OldValue = $this->S->CurrentValue;
		$this->T->CurrentValue = NULL;
		$this->T->OldValue = $this->T->CurrentValue;
		$this->U->CurrentValue = NULL;
		$this->U->OldValue = $this->U->CurrentValue;
		$this->V->CurrentValue = NULL;
		$this->V->OldValue = $this->V->CurrentValue;
		$this->W->CurrentValue = NULL;
		$this->W->OldValue = $this->W->CurrentValue;
		$this->X->CurrentValue = NULL;
		$this->X->OldValue = $this->X->CurrentValue;
		$this->Y->CurrentValue = NULL;
		$this->Y->OldValue = $this->Y->CurrentValue;
		$this->Z->CurrentValue = NULL;
		$this->Z->OldValue = $this->Z->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->SHEET->FldIsDetailKey) {
			$this->SHEET->setFormValue($objForm->GetValue("x_SHEET"));
		}
		if (!$this->NOMOR->FldIsDetailKey) {
			$this->NOMOR->setFormValue($objForm->GetValue("x_NOMOR"));
		}
		if (!$this->A->FldIsDetailKey) {
			$this->A->setFormValue($objForm->GetValue("x_A"));
		}
		if (!$this->B->FldIsDetailKey) {
			$this->B->setFormValue($objForm->GetValue("x_B"));
		}
		if (!$this->C->FldIsDetailKey) {
			$this->C->setFormValue($objForm->GetValue("x_C"));
		}
		if (!$this->D->FldIsDetailKey) {
			$this->D->setFormValue($objForm->GetValue("x_D"));
		}
		if (!$this->E->FldIsDetailKey) {
			$this->E->setFormValue($objForm->GetValue("x_E"));
		}
		if (!$this->F->FldIsDetailKey) {
			$this->F->setFormValue($objForm->GetValue("x_F"));
		}
		if (!$this->G->FldIsDetailKey) {
			$this->G->setFormValue($objForm->GetValue("x_G"));
		}
		if (!$this->H->FldIsDetailKey) {
			$this->H->setFormValue($objForm->GetValue("x_H"));
		}
		if (!$this->_I->FldIsDetailKey) {
			$this->_I->setFormValue($objForm->GetValue("x__I"));
		}
		if (!$this->J->FldIsDetailKey) {
			$this->J->setFormValue($objForm->GetValue("x_J"));
		}
		if (!$this->K->FldIsDetailKey) {
			$this->K->setFormValue($objForm->GetValue("x_K"));
		}
		if (!$this->L->FldIsDetailKey) {
			$this->L->setFormValue($objForm->GetValue("x_L"));
		}
		if (!$this->M->FldIsDetailKey) {
			$this->M->setFormValue($objForm->GetValue("x_M"));
		}
		if (!$this->N->FldIsDetailKey) {
			$this->N->setFormValue($objForm->GetValue("x_N"));
		}
		if (!$this->O->FldIsDetailKey) {
			$this->O->setFormValue($objForm->GetValue("x_O"));
		}
		if (!$this->P->FldIsDetailKey) {
			$this->P->setFormValue($objForm->GetValue("x_P"));
		}
		if (!$this->Q->FldIsDetailKey) {
			$this->Q->setFormValue($objForm->GetValue("x_Q"));
		}
		if (!$this->R->FldIsDetailKey) {
			$this->R->setFormValue($objForm->GetValue("x_R"));
		}
		if (!$this->S->FldIsDetailKey) {
			$this->S->setFormValue($objForm->GetValue("x_S"));
		}
		if (!$this->T->FldIsDetailKey) {
			$this->T->setFormValue($objForm->GetValue("x_T"));
		}
		if (!$this->U->FldIsDetailKey) {
			$this->U->setFormValue($objForm->GetValue("x_U"));
		}
		if (!$this->V->FldIsDetailKey) {
			$this->V->setFormValue($objForm->GetValue("x_V"));
		}
		if (!$this->W->FldIsDetailKey) {
			$this->W->setFormValue($objForm->GetValue("x_W"));
		}
		if (!$this->X->FldIsDetailKey) {
			$this->X->setFormValue($objForm->GetValue("x_X"));
		}
		if (!$this->Y->FldIsDetailKey) {
			$this->Y->setFormValue($objForm->GetValue("x_Y"));
		}
		if (!$this->Z->FldIsDetailKey) {
			$this->Z->setFormValue($objForm->GetValue("x_Z"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->SHEET->CurrentValue = $this->SHEET->FormValue;
		$this->NOMOR->CurrentValue = $this->NOMOR->FormValue;
		$this->A->CurrentValue = $this->A->FormValue;
		$this->B->CurrentValue = $this->B->FormValue;
		$this->C->CurrentValue = $this->C->FormValue;
		$this->D->CurrentValue = $this->D->FormValue;
		$this->E->CurrentValue = $this->E->FormValue;
		$this->F->CurrentValue = $this->F->FormValue;
		$this->G->CurrentValue = $this->G->FormValue;
		$this->H->CurrentValue = $this->H->FormValue;
		$this->_I->CurrentValue = $this->_I->FormValue;
		$this->J->CurrentValue = $this->J->FormValue;
		$this->K->CurrentValue = $this->K->FormValue;
		$this->L->CurrentValue = $this->L->FormValue;
		$this->M->CurrentValue = $this->M->FormValue;
		$this->N->CurrentValue = $this->N->FormValue;
		$this->O->CurrentValue = $this->O->FormValue;
		$this->P->CurrentValue = $this->P->FormValue;
		$this->Q->CurrentValue = $this->Q->FormValue;
		$this->R->CurrentValue = $this->R->FormValue;
		$this->S->CurrentValue = $this->S->FormValue;
		$this->T->CurrentValue = $this->T->FormValue;
		$this->U->CurrentValue = $this->U->FormValue;
		$this->V->CurrentValue = $this->V->FormValue;
		$this->W->CurrentValue = $this->W->FormValue;
		$this->X->CurrentValue = $this->X->FormValue;
		$this->Y->CurrentValue = $this->Y->FormValue;
		$this->Z->CurrentValue = $this->Z->FormValue;
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
		$this->SHEET->setDbValue($rs->fields('SHEET'));
		$this->NOMOR->setDbValue($rs->fields('NOMOR'));
		$this->A->setDbValue($rs->fields('A'));
		$this->B->setDbValue($rs->fields('B'));
		$this->C->setDbValue($rs->fields('C'));
		$this->D->setDbValue($rs->fields('D'));
		$this->E->setDbValue($rs->fields('E'));
		$this->F->setDbValue($rs->fields('F'));
		$this->G->setDbValue($rs->fields('G'));
		$this->H->setDbValue($rs->fields('H'));
		$this->_I->setDbValue($rs->fields('I'));
		$this->J->setDbValue($rs->fields('J'));
		$this->K->setDbValue($rs->fields('K'));
		$this->L->setDbValue($rs->fields('L'));
		$this->M->setDbValue($rs->fields('M'));
		$this->N->setDbValue($rs->fields('N'));
		$this->O->setDbValue($rs->fields('O'));
		$this->P->setDbValue($rs->fields('P'));
		$this->Q->setDbValue($rs->fields('Q'));
		$this->R->setDbValue($rs->fields('R'));
		$this->S->setDbValue($rs->fields('S'));
		$this->T->setDbValue($rs->fields('T'));
		$this->U->setDbValue($rs->fields('U'));
		$this->V->setDbValue($rs->fields('V'));
		$this->W->setDbValue($rs->fields('W'));
		$this->X->setDbValue($rs->fields('X'));
		$this->Y->setDbValue($rs->fields('Y'));
		$this->Z->setDbValue($rs->fields('Z'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->SHEET->DbValue = $row['SHEET'];
		$this->NOMOR->DbValue = $row['NOMOR'];
		$this->A->DbValue = $row['A'];
		$this->B->DbValue = $row['B'];
		$this->C->DbValue = $row['C'];
		$this->D->DbValue = $row['D'];
		$this->E->DbValue = $row['E'];
		$this->F->DbValue = $row['F'];
		$this->G->DbValue = $row['G'];
		$this->H->DbValue = $row['H'];
		$this->_I->DbValue = $row['I'];
		$this->J->DbValue = $row['J'];
		$this->K->DbValue = $row['K'];
		$this->L->DbValue = $row['L'];
		$this->M->DbValue = $row['M'];
		$this->N->DbValue = $row['N'];
		$this->O->DbValue = $row['O'];
		$this->P->DbValue = $row['P'];
		$this->Q->DbValue = $row['Q'];
		$this->R->DbValue = $row['R'];
		$this->S->DbValue = $row['S'];
		$this->T->DbValue = $row['T'];
		$this->U->DbValue = $row['U'];
		$this->V->DbValue = $row['V'];
		$this->W->DbValue = $row['W'];
		$this->X->DbValue = $row['X'];
		$this->Y->DbValue = $row['Y'];
		$this->Z->DbValue = $row['Z'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("SHEET")) <> "")
			$this->SHEET->CurrentValue = $this->getKey("SHEET"); // SHEET
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("NOMOR")) <> "")
			$this->NOMOR->CurrentValue = $this->getKey("NOMOR"); // NOMOR
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
		// SHEET
		// NOMOR
		// A
		// B
		// C
		// D
		// E
		// F
		// G
		// H
		// I
		// J
		// K
		// L
		// M
		// N
		// O
		// P
		// Q
		// R
		// S
		// T
		// U
		// V
		// W
		// X
		// Y
		// Z

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// SHEET
		$this->SHEET->ViewValue = $this->SHEET->CurrentValue;
		$this->SHEET->ViewCustomAttributes = "";

		// NOMOR
		$this->NOMOR->ViewValue = $this->NOMOR->CurrentValue;
		$this->NOMOR->ViewCustomAttributes = "";

		// A
		$this->A->ViewValue = $this->A->CurrentValue;
		$this->A->ViewCustomAttributes = "";

		// B
		$this->B->ViewValue = $this->B->CurrentValue;
		$this->B->ViewCustomAttributes = "";

		// C
		$this->C->ViewValue = $this->C->CurrentValue;
		$this->C->ViewCustomAttributes = "";

		// D
		$this->D->ViewValue = $this->D->CurrentValue;
		$this->D->ViewCustomAttributes = "";

		// E
		$this->E->ViewValue = $this->E->CurrentValue;
		$this->E->ViewCustomAttributes = "";

		// F
		$this->F->ViewValue = $this->F->CurrentValue;
		$this->F->ViewCustomAttributes = "";

		// G
		$this->G->ViewValue = $this->G->CurrentValue;
		$this->G->ViewCustomAttributes = "";

		// H
		$this->H->ViewValue = $this->H->CurrentValue;
		$this->H->ViewCustomAttributes = "";

		// I
		$this->_I->ViewValue = $this->_I->CurrentValue;
		$this->_I->ViewCustomAttributes = "";

		// J
		$this->J->ViewValue = $this->J->CurrentValue;
		$this->J->ViewCustomAttributes = "";

		// K
		$this->K->ViewValue = $this->K->CurrentValue;
		$this->K->ViewCustomAttributes = "";

		// L
		$this->L->ViewValue = $this->L->CurrentValue;
		$this->L->ViewCustomAttributes = "";

		// M
		$this->M->ViewValue = $this->M->CurrentValue;
		$this->M->ViewCustomAttributes = "";

		// N
		$this->N->ViewValue = $this->N->CurrentValue;
		$this->N->ViewCustomAttributes = "";

		// O
		$this->O->ViewValue = $this->O->CurrentValue;
		$this->O->ViewCustomAttributes = "";

		// P
		$this->P->ViewValue = $this->P->CurrentValue;
		$this->P->ViewCustomAttributes = "";

		// Q
		$this->Q->ViewValue = $this->Q->CurrentValue;
		$this->Q->ViewCustomAttributes = "";

		// R
		$this->R->ViewValue = $this->R->CurrentValue;
		$this->R->ViewCustomAttributes = "";

		// S
		$this->S->ViewValue = $this->S->CurrentValue;
		$this->S->ViewCustomAttributes = "";

		// T
		$this->T->ViewValue = $this->T->CurrentValue;
		$this->T->ViewCustomAttributes = "";

		// U
		$this->U->ViewValue = $this->U->CurrentValue;
		$this->U->ViewCustomAttributes = "";

		// V
		$this->V->ViewValue = $this->V->CurrentValue;
		$this->V->ViewCustomAttributes = "";

		// W
		$this->W->ViewValue = $this->W->CurrentValue;
		$this->W->ViewCustomAttributes = "";

		// X
		$this->X->ViewValue = $this->X->CurrentValue;
		$this->X->ViewCustomAttributes = "";

		// Y
		$this->Y->ViewValue = $this->Y->CurrentValue;
		$this->Y->ViewCustomAttributes = "";

		// Z
		$this->Z->ViewValue = $this->Z->CurrentValue;
		$this->Z->ViewCustomAttributes = "";

			// SHEET
			$this->SHEET->LinkCustomAttributes = "";
			$this->SHEET->HrefValue = "";
			$this->SHEET->TooltipValue = "";

			// NOMOR
			$this->NOMOR->LinkCustomAttributes = "";
			$this->NOMOR->HrefValue = "";
			$this->NOMOR->TooltipValue = "";

			// A
			$this->A->LinkCustomAttributes = "";
			$this->A->HrefValue = "";
			$this->A->TooltipValue = "";

			// B
			$this->B->LinkCustomAttributes = "";
			$this->B->HrefValue = "";
			$this->B->TooltipValue = "";

			// C
			$this->C->LinkCustomAttributes = "";
			$this->C->HrefValue = "";
			$this->C->TooltipValue = "";

			// D
			$this->D->LinkCustomAttributes = "";
			$this->D->HrefValue = "";
			$this->D->TooltipValue = "";

			// E
			$this->E->LinkCustomAttributes = "";
			$this->E->HrefValue = "";
			$this->E->TooltipValue = "";

			// F
			$this->F->LinkCustomAttributes = "";
			$this->F->HrefValue = "";
			$this->F->TooltipValue = "";

			// G
			$this->G->LinkCustomAttributes = "";
			$this->G->HrefValue = "";
			$this->G->TooltipValue = "";

			// H
			$this->H->LinkCustomAttributes = "";
			$this->H->HrefValue = "";
			$this->H->TooltipValue = "";

			// I
			$this->_I->LinkCustomAttributes = "";
			$this->_I->HrefValue = "";
			$this->_I->TooltipValue = "";

			// J
			$this->J->LinkCustomAttributes = "";
			$this->J->HrefValue = "";
			$this->J->TooltipValue = "";

			// K
			$this->K->LinkCustomAttributes = "";
			$this->K->HrefValue = "";
			$this->K->TooltipValue = "";

			// L
			$this->L->LinkCustomAttributes = "";
			$this->L->HrefValue = "";
			$this->L->TooltipValue = "";

			// M
			$this->M->LinkCustomAttributes = "";
			$this->M->HrefValue = "";
			$this->M->TooltipValue = "";

			// N
			$this->N->LinkCustomAttributes = "";
			$this->N->HrefValue = "";
			$this->N->TooltipValue = "";

			// O
			$this->O->LinkCustomAttributes = "";
			$this->O->HrefValue = "";
			$this->O->TooltipValue = "";

			// P
			$this->P->LinkCustomAttributes = "";
			$this->P->HrefValue = "";
			$this->P->TooltipValue = "";

			// Q
			$this->Q->LinkCustomAttributes = "";
			$this->Q->HrefValue = "";
			$this->Q->TooltipValue = "";

			// R
			$this->R->LinkCustomAttributes = "";
			$this->R->HrefValue = "";
			$this->R->TooltipValue = "";

			// S
			$this->S->LinkCustomAttributes = "";
			$this->S->HrefValue = "";
			$this->S->TooltipValue = "";

			// T
			$this->T->LinkCustomAttributes = "";
			$this->T->HrefValue = "";
			$this->T->TooltipValue = "";

			// U
			$this->U->LinkCustomAttributes = "";
			$this->U->HrefValue = "";
			$this->U->TooltipValue = "";

			// V
			$this->V->LinkCustomAttributes = "";
			$this->V->HrefValue = "";
			$this->V->TooltipValue = "";

			// W
			$this->W->LinkCustomAttributes = "";
			$this->W->HrefValue = "";
			$this->W->TooltipValue = "";

			// X
			$this->X->LinkCustomAttributes = "";
			$this->X->HrefValue = "";
			$this->X->TooltipValue = "";

			// Y
			$this->Y->LinkCustomAttributes = "";
			$this->Y->HrefValue = "";
			$this->Y->TooltipValue = "";

			// Z
			$this->Z->LinkCustomAttributes = "";
			$this->Z->HrefValue = "";
			$this->Z->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// SHEET
			$this->SHEET->EditAttrs["class"] = "form-control";
			$this->SHEET->EditCustomAttributes = "";
			$this->SHEET->EditValue = ew_HtmlEncode($this->SHEET->CurrentValue);
			$this->SHEET->PlaceHolder = ew_RemoveHtml($this->SHEET->FldCaption());

			// NOMOR
			$this->NOMOR->EditAttrs["class"] = "form-control";
			$this->NOMOR->EditCustomAttributes = "";
			$this->NOMOR->EditValue = ew_HtmlEncode($this->NOMOR->CurrentValue);
			$this->NOMOR->PlaceHolder = ew_RemoveHtml($this->NOMOR->FldCaption());

			// A
			$this->A->EditAttrs["class"] = "form-control";
			$this->A->EditCustomAttributes = "";
			$this->A->EditValue = ew_HtmlEncode($this->A->CurrentValue);
			$this->A->PlaceHolder = ew_RemoveHtml($this->A->FldCaption());

			// B
			$this->B->EditAttrs["class"] = "form-control";
			$this->B->EditCustomAttributes = "";
			$this->B->EditValue = ew_HtmlEncode($this->B->CurrentValue);
			$this->B->PlaceHolder = ew_RemoveHtml($this->B->FldCaption());

			// C
			$this->C->EditAttrs["class"] = "form-control";
			$this->C->EditCustomAttributes = "";
			$this->C->EditValue = ew_HtmlEncode($this->C->CurrentValue);
			$this->C->PlaceHolder = ew_RemoveHtml($this->C->FldCaption());

			// D
			$this->D->EditAttrs["class"] = "form-control";
			$this->D->EditCustomAttributes = "";
			$this->D->EditValue = ew_HtmlEncode($this->D->CurrentValue);
			$this->D->PlaceHolder = ew_RemoveHtml($this->D->FldCaption());

			// E
			$this->E->EditAttrs["class"] = "form-control";
			$this->E->EditCustomAttributes = "";
			$this->E->EditValue = ew_HtmlEncode($this->E->CurrentValue);
			$this->E->PlaceHolder = ew_RemoveHtml($this->E->FldCaption());

			// F
			$this->F->EditAttrs["class"] = "form-control";
			$this->F->EditCustomAttributes = "";
			$this->F->EditValue = ew_HtmlEncode($this->F->CurrentValue);
			$this->F->PlaceHolder = ew_RemoveHtml($this->F->FldCaption());

			// G
			$this->G->EditAttrs["class"] = "form-control";
			$this->G->EditCustomAttributes = "";
			$this->G->EditValue = ew_HtmlEncode($this->G->CurrentValue);
			$this->G->PlaceHolder = ew_RemoveHtml($this->G->FldCaption());

			// H
			$this->H->EditAttrs["class"] = "form-control";
			$this->H->EditCustomAttributes = "";
			$this->H->EditValue = ew_HtmlEncode($this->H->CurrentValue);
			$this->H->PlaceHolder = ew_RemoveHtml($this->H->FldCaption());

			// I
			$this->_I->EditAttrs["class"] = "form-control";
			$this->_I->EditCustomAttributes = "";
			$this->_I->EditValue = ew_HtmlEncode($this->_I->CurrentValue);
			$this->_I->PlaceHolder = ew_RemoveHtml($this->_I->FldCaption());

			// J
			$this->J->EditAttrs["class"] = "form-control";
			$this->J->EditCustomAttributes = "";
			$this->J->EditValue = ew_HtmlEncode($this->J->CurrentValue);
			$this->J->PlaceHolder = ew_RemoveHtml($this->J->FldCaption());

			// K
			$this->K->EditAttrs["class"] = "form-control";
			$this->K->EditCustomAttributes = "";
			$this->K->EditValue = ew_HtmlEncode($this->K->CurrentValue);
			$this->K->PlaceHolder = ew_RemoveHtml($this->K->FldCaption());

			// L
			$this->L->EditAttrs["class"] = "form-control";
			$this->L->EditCustomAttributes = "";
			$this->L->EditValue = ew_HtmlEncode($this->L->CurrentValue);
			$this->L->PlaceHolder = ew_RemoveHtml($this->L->FldCaption());

			// M
			$this->M->EditAttrs["class"] = "form-control";
			$this->M->EditCustomAttributes = "";
			$this->M->EditValue = ew_HtmlEncode($this->M->CurrentValue);
			$this->M->PlaceHolder = ew_RemoveHtml($this->M->FldCaption());

			// N
			$this->N->EditAttrs["class"] = "form-control";
			$this->N->EditCustomAttributes = "";
			$this->N->EditValue = ew_HtmlEncode($this->N->CurrentValue);
			$this->N->PlaceHolder = ew_RemoveHtml($this->N->FldCaption());

			// O
			$this->O->EditAttrs["class"] = "form-control";
			$this->O->EditCustomAttributes = "";
			$this->O->EditValue = ew_HtmlEncode($this->O->CurrentValue);
			$this->O->PlaceHolder = ew_RemoveHtml($this->O->FldCaption());

			// P
			$this->P->EditAttrs["class"] = "form-control";
			$this->P->EditCustomAttributes = "";
			$this->P->EditValue = ew_HtmlEncode($this->P->CurrentValue);
			$this->P->PlaceHolder = ew_RemoveHtml($this->P->FldCaption());

			// Q
			$this->Q->EditAttrs["class"] = "form-control";
			$this->Q->EditCustomAttributes = "";
			$this->Q->EditValue = ew_HtmlEncode($this->Q->CurrentValue);
			$this->Q->PlaceHolder = ew_RemoveHtml($this->Q->FldCaption());

			// R
			$this->R->EditAttrs["class"] = "form-control";
			$this->R->EditCustomAttributes = "";
			$this->R->EditValue = ew_HtmlEncode($this->R->CurrentValue);
			$this->R->PlaceHolder = ew_RemoveHtml($this->R->FldCaption());

			// S
			$this->S->EditAttrs["class"] = "form-control";
			$this->S->EditCustomAttributes = "";
			$this->S->EditValue = ew_HtmlEncode($this->S->CurrentValue);
			$this->S->PlaceHolder = ew_RemoveHtml($this->S->FldCaption());

			// T
			$this->T->EditAttrs["class"] = "form-control";
			$this->T->EditCustomAttributes = "";
			$this->T->EditValue = ew_HtmlEncode($this->T->CurrentValue);
			$this->T->PlaceHolder = ew_RemoveHtml($this->T->FldCaption());

			// U
			$this->U->EditAttrs["class"] = "form-control";
			$this->U->EditCustomAttributes = "";
			$this->U->EditValue = ew_HtmlEncode($this->U->CurrentValue);
			$this->U->PlaceHolder = ew_RemoveHtml($this->U->FldCaption());

			// V
			$this->V->EditAttrs["class"] = "form-control";
			$this->V->EditCustomAttributes = "";
			$this->V->EditValue = ew_HtmlEncode($this->V->CurrentValue);
			$this->V->PlaceHolder = ew_RemoveHtml($this->V->FldCaption());

			// W
			$this->W->EditAttrs["class"] = "form-control";
			$this->W->EditCustomAttributes = "";
			$this->W->EditValue = ew_HtmlEncode($this->W->CurrentValue);
			$this->W->PlaceHolder = ew_RemoveHtml($this->W->FldCaption());

			// X
			$this->X->EditAttrs["class"] = "form-control";
			$this->X->EditCustomAttributes = "";
			$this->X->EditValue = ew_HtmlEncode($this->X->CurrentValue);
			$this->X->PlaceHolder = ew_RemoveHtml($this->X->FldCaption());

			// Y
			$this->Y->EditAttrs["class"] = "form-control";
			$this->Y->EditCustomAttributes = "";
			$this->Y->EditValue = ew_HtmlEncode($this->Y->CurrentValue);
			$this->Y->PlaceHolder = ew_RemoveHtml($this->Y->FldCaption());

			// Z
			$this->Z->EditAttrs["class"] = "form-control";
			$this->Z->EditCustomAttributes = "";
			$this->Z->EditValue = ew_HtmlEncode($this->Z->CurrentValue);
			$this->Z->PlaceHolder = ew_RemoveHtml($this->Z->FldCaption());

			// Add refer script
			// SHEET

			$this->SHEET->LinkCustomAttributes = "";
			$this->SHEET->HrefValue = "";

			// NOMOR
			$this->NOMOR->LinkCustomAttributes = "";
			$this->NOMOR->HrefValue = "";

			// A
			$this->A->LinkCustomAttributes = "";
			$this->A->HrefValue = "";

			// B
			$this->B->LinkCustomAttributes = "";
			$this->B->HrefValue = "";

			// C
			$this->C->LinkCustomAttributes = "";
			$this->C->HrefValue = "";

			// D
			$this->D->LinkCustomAttributes = "";
			$this->D->HrefValue = "";

			// E
			$this->E->LinkCustomAttributes = "";
			$this->E->HrefValue = "";

			// F
			$this->F->LinkCustomAttributes = "";
			$this->F->HrefValue = "";

			// G
			$this->G->LinkCustomAttributes = "";
			$this->G->HrefValue = "";

			// H
			$this->H->LinkCustomAttributes = "";
			$this->H->HrefValue = "";

			// I
			$this->_I->LinkCustomAttributes = "";
			$this->_I->HrefValue = "";

			// J
			$this->J->LinkCustomAttributes = "";
			$this->J->HrefValue = "";

			// K
			$this->K->LinkCustomAttributes = "";
			$this->K->HrefValue = "";

			// L
			$this->L->LinkCustomAttributes = "";
			$this->L->HrefValue = "";

			// M
			$this->M->LinkCustomAttributes = "";
			$this->M->HrefValue = "";

			// N
			$this->N->LinkCustomAttributes = "";
			$this->N->HrefValue = "";

			// O
			$this->O->LinkCustomAttributes = "";
			$this->O->HrefValue = "";

			// P
			$this->P->LinkCustomAttributes = "";
			$this->P->HrefValue = "";

			// Q
			$this->Q->LinkCustomAttributes = "";
			$this->Q->HrefValue = "";

			// R
			$this->R->LinkCustomAttributes = "";
			$this->R->HrefValue = "";

			// S
			$this->S->LinkCustomAttributes = "";
			$this->S->HrefValue = "";

			// T
			$this->T->LinkCustomAttributes = "";
			$this->T->HrefValue = "";

			// U
			$this->U->LinkCustomAttributes = "";
			$this->U->HrefValue = "";

			// V
			$this->V->LinkCustomAttributes = "";
			$this->V->HrefValue = "";

			// W
			$this->W->LinkCustomAttributes = "";
			$this->W->HrefValue = "";

			// X
			$this->X->LinkCustomAttributes = "";
			$this->X->HrefValue = "";

			// Y
			$this->Y->LinkCustomAttributes = "";
			$this->Y->HrefValue = "";

			// Z
			$this->Z->LinkCustomAttributes = "";
			$this->Z->HrefValue = "";
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
		if (!$this->SHEET->FldIsDetailKey && !is_null($this->SHEET->FormValue) && $this->SHEET->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->SHEET->FldCaption(), $this->SHEET->ReqErrMsg));
		}
		if (!$this->NOMOR->FldIsDetailKey && !is_null($this->NOMOR->FormValue) && $this->NOMOR->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->NOMOR->FldCaption(), $this->NOMOR->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->NOMOR->FormValue)) {
			ew_AddMessage($gsFormError, $this->NOMOR->FldErrMsg());
		}
		if (!$this->A->FldIsDetailKey && !is_null($this->A->FormValue) && $this->A->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->A->FldCaption(), $this->A->ReqErrMsg));
		}
		if (!$this->B->FldIsDetailKey && !is_null($this->B->FormValue) && $this->B->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->B->FldCaption(), $this->B->ReqErrMsg));
		}
		if (!$this->C->FldIsDetailKey && !is_null($this->C->FormValue) && $this->C->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->C->FldCaption(), $this->C->ReqErrMsg));
		}
		if (!$this->D->FldIsDetailKey && !is_null($this->D->FormValue) && $this->D->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->D->FldCaption(), $this->D->ReqErrMsg));
		}
		if (!$this->E->FldIsDetailKey && !is_null($this->E->FormValue) && $this->E->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->E->FldCaption(), $this->E->ReqErrMsg));
		}
		if (!$this->F->FldIsDetailKey && !is_null($this->F->FormValue) && $this->F->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->F->FldCaption(), $this->F->ReqErrMsg));
		}
		if (!$this->G->FldIsDetailKey && !is_null($this->G->FormValue) && $this->G->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->G->FldCaption(), $this->G->ReqErrMsg));
		}
		if (!$this->H->FldIsDetailKey && !is_null($this->H->FormValue) && $this->H->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->H->FldCaption(), $this->H->ReqErrMsg));
		}
		if (!$this->_I->FldIsDetailKey && !is_null($this->_I->FormValue) && $this->_I->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->_I->FldCaption(), $this->_I->ReqErrMsg));
		}
		if (!$this->J->FldIsDetailKey && !is_null($this->J->FormValue) && $this->J->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->J->FldCaption(), $this->J->ReqErrMsg));
		}
		if (!$this->K->FldIsDetailKey && !is_null($this->K->FormValue) && $this->K->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->K->FldCaption(), $this->K->ReqErrMsg));
		}
		if (!$this->L->FldIsDetailKey && !is_null($this->L->FormValue) && $this->L->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->L->FldCaption(), $this->L->ReqErrMsg));
		}
		if (!$this->M->FldIsDetailKey && !is_null($this->M->FormValue) && $this->M->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->M->FldCaption(), $this->M->ReqErrMsg));
		}
		if (!$this->N->FldIsDetailKey && !is_null($this->N->FormValue) && $this->N->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->N->FldCaption(), $this->N->ReqErrMsg));
		}
		if (!$this->O->FldIsDetailKey && !is_null($this->O->FormValue) && $this->O->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->O->FldCaption(), $this->O->ReqErrMsg));
		}
		if (!$this->P->FldIsDetailKey && !is_null($this->P->FormValue) && $this->P->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->P->FldCaption(), $this->P->ReqErrMsg));
		}
		if (!$this->Q->FldIsDetailKey && !is_null($this->Q->FormValue) && $this->Q->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Q->FldCaption(), $this->Q->ReqErrMsg));
		}
		if (!$this->R->FldIsDetailKey && !is_null($this->R->FormValue) && $this->R->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->R->FldCaption(), $this->R->ReqErrMsg));
		}
		if (!$this->S->FldIsDetailKey && !is_null($this->S->FormValue) && $this->S->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->S->FldCaption(), $this->S->ReqErrMsg));
		}
		if (!$this->T->FldIsDetailKey && !is_null($this->T->FormValue) && $this->T->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->T->FldCaption(), $this->T->ReqErrMsg));
		}
		if (!$this->U->FldIsDetailKey && !is_null($this->U->FormValue) && $this->U->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->U->FldCaption(), $this->U->ReqErrMsg));
		}
		if (!$this->V->FldIsDetailKey && !is_null($this->V->FormValue) && $this->V->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->V->FldCaption(), $this->V->ReqErrMsg));
		}
		if (!$this->W->FldIsDetailKey && !is_null($this->W->FormValue) && $this->W->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->W->FldCaption(), $this->W->ReqErrMsg));
		}
		if (!$this->X->FldIsDetailKey && !is_null($this->X->FormValue) && $this->X->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->X->FldCaption(), $this->X->ReqErrMsg));
		}
		if (!$this->Y->FldIsDetailKey && !is_null($this->Y->FormValue) && $this->Y->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Y->FldCaption(), $this->Y->ReqErrMsg));
		}
		if (!$this->Z->FldIsDetailKey && !is_null($this->Z->FormValue) && $this->Z->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Z->FldCaption(), $this->Z->ReqErrMsg));
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

		// SHEET
		$this->SHEET->SetDbValueDef($rsnew, $this->SHEET->CurrentValue, "", FALSE);

		// NOMOR
		$this->NOMOR->SetDbValueDef($rsnew, $this->NOMOR->CurrentValue, 0, strval($this->NOMOR->CurrentValue) == "");

		// A
		$this->A->SetDbValueDef($rsnew, $this->A->CurrentValue, "", FALSE);

		// B
		$this->B->SetDbValueDef($rsnew, $this->B->CurrentValue, "", FALSE);

		// C
		$this->C->SetDbValueDef($rsnew, $this->C->CurrentValue, "", FALSE);

		// D
		$this->D->SetDbValueDef($rsnew, $this->D->CurrentValue, "", FALSE);

		// E
		$this->E->SetDbValueDef($rsnew, $this->E->CurrentValue, "", FALSE);

		// F
		$this->F->SetDbValueDef($rsnew, $this->F->CurrentValue, "", FALSE);

		// G
		$this->G->SetDbValueDef($rsnew, $this->G->CurrentValue, "", FALSE);

		// H
		$this->H->SetDbValueDef($rsnew, $this->H->CurrentValue, "", FALSE);

		// I
		$this->_I->SetDbValueDef($rsnew, $this->_I->CurrentValue, "", FALSE);

		// J
		$this->J->SetDbValueDef($rsnew, $this->J->CurrentValue, "", FALSE);

		// K
		$this->K->SetDbValueDef($rsnew, $this->K->CurrentValue, "", FALSE);

		// L
		$this->L->SetDbValueDef($rsnew, $this->L->CurrentValue, "", FALSE);

		// M
		$this->M->SetDbValueDef($rsnew, $this->M->CurrentValue, "", FALSE);

		// N
		$this->N->SetDbValueDef($rsnew, $this->N->CurrentValue, "", FALSE);

		// O
		$this->O->SetDbValueDef($rsnew, $this->O->CurrentValue, "", FALSE);

		// P
		$this->P->SetDbValueDef($rsnew, $this->P->CurrentValue, "", FALSE);

		// Q
		$this->Q->SetDbValueDef($rsnew, $this->Q->CurrentValue, "", FALSE);

		// R
		$this->R->SetDbValueDef($rsnew, $this->R->CurrentValue, "", FALSE);

		// S
		$this->S->SetDbValueDef($rsnew, $this->S->CurrentValue, "", FALSE);

		// T
		$this->T->SetDbValueDef($rsnew, $this->T->CurrentValue, "", FALSE);

		// U
		$this->U->SetDbValueDef($rsnew, $this->U->CurrentValue, "", FALSE);

		// V
		$this->V->SetDbValueDef($rsnew, $this->V->CurrentValue, "", FALSE);

		// W
		$this->W->SetDbValueDef($rsnew, $this->W->CurrentValue, "", FALSE);

		// X
		$this->X->SetDbValueDef($rsnew, $this->X->CurrentValue, "", FALSE);

		// Y
		$this->Y->SetDbValueDef($rsnew, $this->Y->CurrentValue, "", FALSE);

		// Z
		$this->Z->SetDbValueDef($rsnew, $this->Z->CurrentValue, "", FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['SHEET']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['NOMOR']) == "") {
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("texcellist.php"), "", $this->TableVar, TRUE);
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
if (!isset($texcel_add)) $texcel_add = new ctexcel_add();

// Page init
$texcel_add->Page_Init();

// Page main
$texcel_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$texcel_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = ftexceladd = new ew_Form("ftexceladd", "add");

// Validate form
ftexceladd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_SHEET");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $texcel->SHEET->FldCaption(), $texcel->SHEET->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_NOMOR");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $texcel->NOMOR->FldCaption(), $texcel->NOMOR->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_NOMOR");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($texcel->NOMOR->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_A");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $texcel->A->FldCaption(), $texcel->A->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_B");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $texcel->B->FldCaption(), $texcel->B->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_C");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $texcel->C->FldCaption(), $texcel->C->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_D");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $texcel->D->FldCaption(), $texcel->D->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_E");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $texcel->E->FldCaption(), $texcel->E->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_F");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $texcel->F->FldCaption(), $texcel->F->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_G");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $texcel->G->FldCaption(), $texcel->G->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_H");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $texcel->H->FldCaption(), $texcel->H->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__I");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $texcel->_I->FldCaption(), $texcel->_I->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_J");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $texcel->J->FldCaption(), $texcel->J->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_K");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $texcel->K->FldCaption(), $texcel->K->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_L");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $texcel->L->FldCaption(), $texcel->L->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_M");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $texcel->M->FldCaption(), $texcel->M->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_N");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $texcel->N->FldCaption(), $texcel->N->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_O");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $texcel->O->FldCaption(), $texcel->O->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_P");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $texcel->P->FldCaption(), $texcel->P->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Q");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $texcel->Q->FldCaption(), $texcel->Q->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_R");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $texcel->R->FldCaption(), $texcel->R->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_S");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $texcel->S->FldCaption(), $texcel->S->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_T");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $texcel->T->FldCaption(), $texcel->T->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_U");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $texcel->U->FldCaption(), $texcel->U->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_V");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $texcel->V->FldCaption(), $texcel->V->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_W");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $texcel->W->FldCaption(), $texcel->W->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_X");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $texcel->X->FldCaption(), $texcel->X->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Y");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $texcel->Y->FldCaption(), $texcel->Y->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Z");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $texcel->Z->FldCaption(), $texcel->Z->ReqErrMsg)) ?>");

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
ftexceladd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftexceladd.ValidateRequired = true;
<?php } else { ?>
ftexceladd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$texcel_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $texcel_add->ShowPageHeader(); ?>
<?php
$texcel_add->ShowMessage();
?>
<form name="ftexceladd" id="ftexceladd" class="<?php echo $texcel_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($texcel_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $texcel_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="texcel">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($texcel_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($texcel->SHEET->Visible) { // SHEET ?>
	<div id="r_SHEET" class="form-group">
		<label id="elh_texcel_SHEET" for="x_SHEET" class="col-sm-2 control-label ewLabel"><?php echo $texcel->SHEET->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $texcel->SHEET->CellAttributes() ?>>
<span id="el_texcel_SHEET">
<input type="text" data-table="texcel" data-field="x_SHEET" name="x_SHEET" id="x_SHEET" size="30" maxlength="35" placeholder="<?php echo ew_HtmlEncode($texcel->SHEET->getPlaceHolder()) ?>" value="<?php echo $texcel->SHEET->EditValue ?>"<?php echo $texcel->SHEET->EditAttributes() ?>>
</span>
<?php echo $texcel->SHEET->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($texcel->NOMOR->Visible) { // NOMOR ?>
	<div id="r_NOMOR" class="form-group">
		<label id="elh_texcel_NOMOR" for="x_NOMOR" class="col-sm-2 control-label ewLabel"><?php echo $texcel->NOMOR->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $texcel->NOMOR->CellAttributes() ?>>
<span id="el_texcel_NOMOR">
<input type="text" data-table="texcel" data-field="x_NOMOR" name="x_NOMOR" id="x_NOMOR" size="30" placeholder="<?php echo ew_HtmlEncode($texcel->NOMOR->getPlaceHolder()) ?>" value="<?php echo $texcel->NOMOR->EditValue ?>"<?php echo $texcel->NOMOR->EditAttributes() ?>>
</span>
<?php echo $texcel->NOMOR->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($texcel->A->Visible) { // A ?>
	<div id="r_A" class="form-group">
		<label id="elh_texcel_A" for="x_A" class="col-sm-2 control-label ewLabel"><?php echo $texcel->A->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $texcel->A->CellAttributes() ?>>
<span id="el_texcel_A">
<input type="text" data-table="texcel" data-field="x_A" name="x_A" id="x_A" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($texcel->A->getPlaceHolder()) ?>" value="<?php echo $texcel->A->EditValue ?>"<?php echo $texcel->A->EditAttributes() ?>>
</span>
<?php echo $texcel->A->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($texcel->B->Visible) { // B ?>
	<div id="r_B" class="form-group">
		<label id="elh_texcel_B" for="x_B" class="col-sm-2 control-label ewLabel"><?php echo $texcel->B->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $texcel->B->CellAttributes() ?>>
<span id="el_texcel_B">
<input type="text" data-table="texcel" data-field="x_B" name="x_B" id="x_B" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($texcel->B->getPlaceHolder()) ?>" value="<?php echo $texcel->B->EditValue ?>"<?php echo $texcel->B->EditAttributes() ?>>
</span>
<?php echo $texcel->B->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($texcel->C->Visible) { // C ?>
	<div id="r_C" class="form-group">
		<label id="elh_texcel_C" for="x_C" class="col-sm-2 control-label ewLabel"><?php echo $texcel->C->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $texcel->C->CellAttributes() ?>>
<span id="el_texcel_C">
<input type="text" data-table="texcel" data-field="x_C" name="x_C" id="x_C" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($texcel->C->getPlaceHolder()) ?>" value="<?php echo $texcel->C->EditValue ?>"<?php echo $texcel->C->EditAttributes() ?>>
</span>
<?php echo $texcel->C->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($texcel->D->Visible) { // D ?>
	<div id="r_D" class="form-group">
		<label id="elh_texcel_D" for="x_D" class="col-sm-2 control-label ewLabel"><?php echo $texcel->D->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $texcel->D->CellAttributes() ?>>
<span id="el_texcel_D">
<input type="text" data-table="texcel" data-field="x_D" name="x_D" id="x_D" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($texcel->D->getPlaceHolder()) ?>" value="<?php echo $texcel->D->EditValue ?>"<?php echo $texcel->D->EditAttributes() ?>>
</span>
<?php echo $texcel->D->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($texcel->E->Visible) { // E ?>
	<div id="r_E" class="form-group">
		<label id="elh_texcel_E" for="x_E" class="col-sm-2 control-label ewLabel"><?php echo $texcel->E->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $texcel->E->CellAttributes() ?>>
<span id="el_texcel_E">
<input type="text" data-table="texcel" data-field="x_E" name="x_E" id="x_E" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($texcel->E->getPlaceHolder()) ?>" value="<?php echo $texcel->E->EditValue ?>"<?php echo $texcel->E->EditAttributes() ?>>
</span>
<?php echo $texcel->E->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($texcel->F->Visible) { // F ?>
	<div id="r_F" class="form-group">
		<label id="elh_texcel_F" for="x_F" class="col-sm-2 control-label ewLabel"><?php echo $texcel->F->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $texcel->F->CellAttributes() ?>>
<span id="el_texcel_F">
<input type="text" data-table="texcel" data-field="x_F" name="x_F" id="x_F" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($texcel->F->getPlaceHolder()) ?>" value="<?php echo $texcel->F->EditValue ?>"<?php echo $texcel->F->EditAttributes() ?>>
</span>
<?php echo $texcel->F->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($texcel->G->Visible) { // G ?>
	<div id="r_G" class="form-group">
		<label id="elh_texcel_G" for="x_G" class="col-sm-2 control-label ewLabel"><?php echo $texcel->G->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $texcel->G->CellAttributes() ?>>
<span id="el_texcel_G">
<input type="text" data-table="texcel" data-field="x_G" name="x_G" id="x_G" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($texcel->G->getPlaceHolder()) ?>" value="<?php echo $texcel->G->EditValue ?>"<?php echo $texcel->G->EditAttributes() ?>>
</span>
<?php echo $texcel->G->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($texcel->H->Visible) { // H ?>
	<div id="r_H" class="form-group">
		<label id="elh_texcel_H" for="x_H" class="col-sm-2 control-label ewLabel"><?php echo $texcel->H->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $texcel->H->CellAttributes() ?>>
<span id="el_texcel_H">
<input type="text" data-table="texcel" data-field="x_H" name="x_H" id="x_H" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($texcel->H->getPlaceHolder()) ?>" value="<?php echo $texcel->H->EditValue ?>"<?php echo $texcel->H->EditAttributes() ?>>
</span>
<?php echo $texcel->H->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($texcel->_I->Visible) { // I ?>
	<div id="r__I" class="form-group">
		<label id="elh_texcel__I" for="x__I" class="col-sm-2 control-label ewLabel"><?php echo $texcel->_I->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $texcel->_I->CellAttributes() ?>>
<span id="el_texcel__I">
<input type="text" data-table="texcel" data-field="x__I" name="x__I" id="x__I" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($texcel->_I->getPlaceHolder()) ?>" value="<?php echo $texcel->_I->EditValue ?>"<?php echo $texcel->_I->EditAttributes() ?>>
</span>
<?php echo $texcel->_I->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($texcel->J->Visible) { // J ?>
	<div id="r_J" class="form-group">
		<label id="elh_texcel_J" for="x_J" class="col-sm-2 control-label ewLabel"><?php echo $texcel->J->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $texcel->J->CellAttributes() ?>>
<span id="el_texcel_J">
<input type="text" data-table="texcel" data-field="x_J" name="x_J" id="x_J" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($texcel->J->getPlaceHolder()) ?>" value="<?php echo $texcel->J->EditValue ?>"<?php echo $texcel->J->EditAttributes() ?>>
</span>
<?php echo $texcel->J->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($texcel->K->Visible) { // K ?>
	<div id="r_K" class="form-group">
		<label id="elh_texcel_K" for="x_K" class="col-sm-2 control-label ewLabel"><?php echo $texcel->K->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $texcel->K->CellAttributes() ?>>
<span id="el_texcel_K">
<input type="text" data-table="texcel" data-field="x_K" name="x_K" id="x_K" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($texcel->K->getPlaceHolder()) ?>" value="<?php echo $texcel->K->EditValue ?>"<?php echo $texcel->K->EditAttributes() ?>>
</span>
<?php echo $texcel->K->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($texcel->L->Visible) { // L ?>
	<div id="r_L" class="form-group">
		<label id="elh_texcel_L" for="x_L" class="col-sm-2 control-label ewLabel"><?php echo $texcel->L->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $texcel->L->CellAttributes() ?>>
<span id="el_texcel_L">
<input type="text" data-table="texcel" data-field="x_L" name="x_L" id="x_L" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($texcel->L->getPlaceHolder()) ?>" value="<?php echo $texcel->L->EditValue ?>"<?php echo $texcel->L->EditAttributes() ?>>
</span>
<?php echo $texcel->L->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($texcel->M->Visible) { // M ?>
	<div id="r_M" class="form-group">
		<label id="elh_texcel_M" for="x_M" class="col-sm-2 control-label ewLabel"><?php echo $texcel->M->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $texcel->M->CellAttributes() ?>>
<span id="el_texcel_M">
<input type="text" data-table="texcel" data-field="x_M" name="x_M" id="x_M" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($texcel->M->getPlaceHolder()) ?>" value="<?php echo $texcel->M->EditValue ?>"<?php echo $texcel->M->EditAttributes() ?>>
</span>
<?php echo $texcel->M->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($texcel->N->Visible) { // N ?>
	<div id="r_N" class="form-group">
		<label id="elh_texcel_N" for="x_N" class="col-sm-2 control-label ewLabel"><?php echo $texcel->N->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $texcel->N->CellAttributes() ?>>
<span id="el_texcel_N">
<input type="text" data-table="texcel" data-field="x_N" name="x_N" id="x_N" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($texcel->N->getPlaceHolder()) ?>" value="<?php echo $texcel->N->EditValue ?>"<?php echo $texcel->N->EditAttributes() ?>>
</span>
<?php echo $texcel->N->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($texcel->O->Visible) { // O ?>
	<div id="r_O" class="form-group">
		<label id="elh_texcel_O" for="x_O" class="col-sm-2 control-label ewLabel"><?php echo $texcel->O->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $texcel->O->CellAttributes() ?>>
<span id="el_texcel_O">
<input type="text" data-table="texcel" data-field="x_O" name="x_O" id="x_O" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($texcel->O->getPlaceHolder()) ?>" value="<?php echo $texcel->O->EditValue ?>"<?php echo $texcel->O->EditAttributes() ?>>
</span>
<?php echo $texcel->O->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($texcel->P->Visible) { // P ?>
	<div id="r_P" class="form-group">
		<label id="elh_texcel_P" for="x_P" class="col-sm-2 control-label ewLabel"><?php echo $texcel->P->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $texcel->P->CellAttributes() ?>>
<span id="el_texcel_P">
<input type="text" data-table="texcel" data-field="x_P" name="x_P" id="x_P" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($texcel->P->getPlaceHolder()) ?>" value="<?php echo $texcel->P->EditValue ?>"<?php echo $texcel->P->EditAttributes() ?>>
</span>
<?php echo $texcel->P->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($texcel->Q->Visible) { // Q ?>
	<div id="r_Q" class="form-group">
		<label id="elh_texcel_Q" for="x_Q" class="col-sm-2 control-label ewLabel"><?php echo $texcel->Q->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $texcel->Q->CellAttributes() ?>>
<span id="el_texcel_Q">
<input type="text" data-table="texcel" data-field="x_Q" name="x_Q" id="x_Q" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($texcel->Q->getPlaceHolder()) ?>" value="<?php echo $texcel->Q->EditValue ?>"<?php echo $texcel->Q->EditAttributes() ?>>
</span>
<?php echo $texcel->Q->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($texcel->R->Visible) { // R ?>
	<div id="r_R" class="form-group">
		<label id="elh_texcel_R" for="x_R" class="col-sm-2 control-label ewLabel"><?php echo $texcel->R->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $texcel->R->CellAttributes() ?>>
<span id="el_texcel_R">
<input type="text" data-table="texcel" data-field="x_R" name="x_R" id="x_R" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($texcel->R->getPlaceHolder()) ?>" value="<?php echo $texcel->R->EditValue ?>"<?php echo $texcel->R->EditAttributes() ?>>
</span>
<?php echo $texcel->R->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($texcel->S->Visible) { // S ?>
	<div id="r_S" class="form-group">
		<label id="elh_texcel_S" for="x_S" class="col-sm-2 control-label ewLabel"><?php echo $texcel->S->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $texcel->S->CellAttributes() ?>>
<span id="el_texcel_S">
<input type="text" data-table="texcel" data-field="x_S" name="x_S" id="x_S" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($texcel->S->getPlaceHolder()) ?>" value="<?php echo $texcel->S->EditValue ?>"<?php echo $texcel->S->EditAttributes() ?>>
</span>
<?php echo $texcel->S->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($texcel->T->Visible) { // T ?>
	<div id="r_T" class="form-group">
		<label id="elh_texcel_T" for="x_T" class="col-sm-2 control-label ewLabel"><?php echo $texcel->T->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $texcel->T->CellAttributes() ?>>
<span id="el_texcel_T">
<input type="text" data-table="texcel" data-field="x_T" name="x_T" id="x_T" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($texcel->T->getPlaceHolder()) ?>" value="<?php echo $texcel->T->EditValue ?>"<?php echo $texcel->T->EditAttributes() ?>>
</span>
<?php echo $texcel->T->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($texcel->U->Visible) { // U ?>
	<div id="r_U" class="form-group">
		<label id="elh_texcel_U" for="x_U" class="col-sm-2 control-label ewLabel"><?php echo $texcel->U->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $texcel->U->CellAttributes() ?>>
<span id="el_texcel_U">
<input type="text" data-table="texcel" data-field="x_U" name="x_U" id="x_U" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($texcel->U->getPlaceHolder()) ?>" value="<?php echo $texcel->U->EditValue ?>"<?php echo $texcel->U->EditAttributes() ?>>
</span>
<?php echo $texcel->U->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($texcel->V->Visible) { // V ?>
	<div id="r_V" class="form-group">
		<label id="elh_texcel_V" for="x_V" class="col-sm-2 control-label ewLabel"><?php echo $texcel->V->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $texcel->V->CellAttributes() ?>>
<span id="el_texcel_V">
<input type="text" data-table="texcel" data-field="x_V" name="x_V" id="x_V" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($texcel->V->getPlaceHolder()) ?>" value="<?php echo $texcel->V->EditValue ?>"<?php echo $texcel->V->EditAttributes() ?>>
</span>
<?php echo $texcel->V->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($texcel->W->Visible) { // W ?>
	<div id="r_W" class="form-group">
		<label id="elh_texcel_W" for="x_W" class="col-sm-2 control-label ewLabel"><?php echo $texcel->W->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $texcel->W->CellAttributes() ?>>
<span id="el_texcel_W">
<input type="text" data-table="texcel" data-field="x_W" name="x_W" id="x_W" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($texcel->W->getPlaceHolder()) ?>" value="<?php echo $texcel->W->EditValue ?>"<?php echo $texcel->W->EditAttributes() ?>>
</span>
<?php echo $texcel->W->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($texcel->X->Visible) { // X ?>
	<div id="r_X" class="form-group">
		<label id="elh_texcel_X" for="x_X" class="col-sm-2 control-label ewLabel"><?php echo $texcel->X->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $texcel->X->CellAttributes() ?>>
<span id="el_texcel_X">
<input type="text" data-table="texcel" data-field="x_X" name="x_X" id="x_X" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($texcel->X->getPlaceHolder()) ?>" value="<?php echo $texcel->X->EditValue ?>"<?php echo $texcel->X->EditAttributes() ?>>
</span>
<?php echo $texcel->X->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($texcel->Y->Visible) { // Y ?>
	<div id="r_Y" class="form-group">
		<label id="elh_texcel_Y" for="x_Y" class="col-sm-2 control-label ewLabel"><?php echo $texcel->Y->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $texcel->Y->CellAttributes() ?>>
<span id="el_texcel_Y">
<input type="text" data-table="texcel" data-field="x_Y" name="x_Y" id="x_Y" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($texcel->Y->getPlaceHolder()) ?>" value="<?php echo $texcel->Y->EditValue ?>"<?php echo $texcel->Y->EditAttributes() ?>>
</span>
<?php echo $texcel->Y->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($texcel->Z->Visible) { // Z ?>
	<div id="r_Z" class="form-group">
		<label id="elh_texcel_Z" for="x_Z" class="col-sm-2 control-label ewLabel"><?php echo $texcel->Z->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $texcel->Z->CellAttributes() ?>>
<span id="el_texcel_Z">
<input type="text" data-table="texcel" data-field="x_Z" name="x_Z" id="x_Z" size="30" maxlength="90" placeholder="<?php echo ew_HtmlEncode($texcel->Z->getPlaceHolder()) ?>" value="<?php echo $texcel->Z->EditValue ?>"<?php echo $texcel->Z->EditAttributes() ?>>
</span>
<?php echo $texcel->Z->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$texcel_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $texcel_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ftexceladd.Init();
</script>
<?php
$texcel_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$texcel_add->Page_Terminate();
?>
