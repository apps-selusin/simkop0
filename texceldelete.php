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

$texcel_delete = NULL; // Initialize page object first

class ctexcel_delete extends ctexcel {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'texcel';

	// Page object name
	var $PageObjName = 'texcel_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("texcellist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in texcel class, texcelinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("texcellist.php"); // Return to list
			}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['SHEET'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['NOMOR'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("texcellist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($texcel_delete)) $texcel_delete = new ctexcel_delete();

// Page init
$texcel_delete->Page_Init();

// Page main
$texcel_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$texcel_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = ftexceldelete = new ew_Form("ftexceldelete", "delete");

// Form_CustomValidate event
ftexceldelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftexceldelete.ValidateRequired = true;
<?php } else { ?>
ftexceldelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $texcel_delete->ShowPageHeader(); ?>
<?php
$texcel_delete->ShowMessage();
?>
<form name="ftexceldelete" id="ftexceldelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($texcel_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $texcel_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="texcel">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($texcel_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $texcel->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($texcel->SHEET->Visible) { // SHEET ?>
		<th><span id="elh_texcel_SHEET" class="texcel_SHEET"><?php echo $texcel->SHEET->FldCaption() ?></span></th>
<?php } ?>
<?php if ($texcel->NOMOR->Visible) { // NOMOR ?>
		<th><span id="elh_texcel_NOMOR" class="texcel_NOMOR"><?php echo $texcel->NOMOR->FldCaption() ?></span></th>
<?php } ?>
<?php if ($texcel->A->Visible) { // A ?>
		<th><span id="elh_texcel_A" class="texcel_A"><?php echo $texcel->A->FldCaption() ?></span></th>
<?php } ?>
<?php if ($texcel->B->Visible) { // B ?>
		<th><span id="elh_texcel_B" class="texcel_B"><?php echo $texcel->B->FldCaption() ?></span></th>
<?php } ?>
<?php if ($texcel->C->Visible) { // C ?>
		<th><span id="elh_texcel_C" class="texcel_C"><?php echo $texcel->C->FldCaption() ?></span></th>
<?php } ?>
<?php if ($texcel->D->Visible) { // D ?>
		<th><span id="elh_texcel_D" class="texcel_D"><?php echo $texcel->D->FldCaption() ?></span></th>
<?php } ?>
<?php if ($texcel->E->Visible) { // E ?>
		<th><span id="elh_texcel_E" class="texcel_E"><?php echo $texcel->E->FldCaption() ?></span></th>
<?php } ?>
<?php if ($texcel->F->Visible) { // F ?>
		<th><span id="elh_texcel_F" class="texcel_F"><?php echo $texcel->F->FldCaption() ?></span></th>
<?php } ?>
<?php if ($texcel->G->Visible) { // G ?>
		<th><span id="elh_texcel_G" class="texcel_G"><?php echo $texcel->G->FldCaption() ?></span></th>
<?php } ?>
<?php if ($texcel->H->Visible) { // H ?>
		<th><span id="elh_texcel_H" class="texcel_H"><?php echo $texcel->H->FldCaption() ?></span></th>
<?php } ?>
<?php if ($texcel->_I->Visible) { // I ?>
		<th><span id="elh_texcel__I" class="texcel__I"><?php echo $texcel->_I->FldCaption() ?></span></th>
<?php } ?>
<?php if ($texcel->J->Visible) { // J ?>
		<th><span id="elh_texcel_J" class="texcel_J"><?php echo $texcel->J->FldCaption() ?></span></th>
<?php } ?>
<?php if ($texcel->K->Visible) { // K ?>
		<th><span id="elh_texcel_K" class="texcel_K"><?php echo $texcel->K->FldCaption() ?></span></th>
<?php } ?>
<?php if ($texcel->L->Visible) { // L ?>
		<th><span id="elh_texcel_L" class="texcel_L"><?php echo $texcel->L->FldCaption() ?></span></th>
<?php } ?>
<?php if ($texcel->M->Visible) { // M ?>
		<th><span id="elh_texcel_M" class="texcel_M"><?php echo $texcel->M->FldCaption() ?></span></th>
<?php } ?>
<?php if ($texcel->N->Visible) { // N ?>
		<th><span id="elh_texcel_N" class="texcel_N"><?php echo $texcel->N->FldCaption() ?></span></th>
<?php } ?>
<?php if ($texcel->O->Visible) { // O ?>
		<th><span id="elh_texcel_O" class="texcel_O"><?php echo $texcel->O->FldCaption() ?></span></th>
<?php } ?>
<?php if ($texcel->P->Visible) { // P ?>
		<th><span id="elh_texcel_P" class="texcel_P"><?php echo $texcel->P->FldCaption() ?></span></th>
<?php } ?>
<?php if ($texcel->Q->Visible) { // Q ?>
		<th><span id="elh_texcel_Q" class="texcel_Q"><?php echo $texcel->Q->FldCaption() ?></span></th>
<?php } ?>
<?php if ($texcel->R->Visible) { // R ?>
		<th><span id="elh_texcel_R" class="texcel_R"><?php echo $texcel->R->FldCaption() ?></span></th>
<?php } ?>
<?php if ($texcel->S->Visible) { // S ?>
		<th><span id="elh_texcel_S" class="texcel_S"><?php echo $texcel->S->FldCaption() ?></span></th>
<?php } ?>
<?php if ($texcel->T->Visible) { // T ?>
		<th><span id="elh_texcel_T" class="texcel_T"><?php echo $texcel->T->FldCaption() ?></span></th>
<?php } ?>
<?php if ($texcel->U->Visible) { // U ?>
		<th><span id="elh_texcel_U" class="texcel_U"><?php echo $texcel->U->FldCaption() ?></span></th>
<?php } ?>
<?php if ($texcel->V->Visible) { // V ?>
		<th><span id="elh_texcel_V" class="texcel_V"><?php echo $texcel->V->FldCaption() ?></span></th>
<?php } ?>
<?php if ($texcel->W->Visible) { // W ?>
		<th><span id="elh_texcel_W" class="texcel_W"><?php echo $texcel->W->FldCaption() ?></span></th>
<?php } ?>
<?php if ($texcel->X->Visible) { // X ?>
		<th><span id="elh_texcel_X" class="texcel_X"><?php echo $texcel->X->FldCaption() ?></span></th>
<?php } ?>
<?php if ($texcel->Y->Visible) { // Y ?>
		<th><span id="elh_texcel_Y" class="texcel_Y"><?php echo $texcel->Y->FldCaption() ?></span></th>
<?php } ?>
<?php if ($texcel->Z->Visible) { // Z ?>
		<th><span id="elh_texcel_Z" class="texcel_Z"><?php echo $texcel->Z->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$texcel_delete->RecCnt = 0;
$i = 0;
while (!$texcel_delete->Recordset->EOF) {
	$texcel_delete->RecCnt++;
	$texcel_delete->RowCnt++;

	// Set row properties
	$texcel->ResetAttrs();
	$texcel->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$texcel_delete->LoadRowValues($texcel_delete->Recordset);

	// Render row
	$texcel_delete->RenderRow();
?>
	<tr<?php echo $texcel->RowAttributes() ?>>
<?php if ($texcel->SHEET->Visible) { // SHEET ?>
		<td<?php echo $texcel->SHEET->CellAttributes() ?>>
<span id="el<?php echo $texcel_delete->RowCnt ?>_texcel_SHEET" class="texcel_SHEET">
<span<?php echo $texcel->SHEET->ViewAttributes() ?>>
<?php echo $texcel->SHEET->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($texcel->NOMOR->Visible) { // NOMOR ?>
		<td<?php echo $texcel->NOMOR->CellAttributes() ?>>
<span id="el<?php echo $texcel_delete->RowCnt ?>_texcel_NOMOR" class="texcel_NOMOR">
<span<?php echo $texcel->NOMOR->ViewAttributes() ?>>
<?php echo $texcel->NOMOR->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($texcel->A->Visible) { // A ?>
		<td<?php echo $texcel->A->CellAttributes() ?>>
<span id="el<?php echo $texcel_delete->RowCnt ?>_texcel_A" class="texcel_A">
<span<?php echo $texcel->A->ViewAttributes() ?>>
<?php echo $texcel->A->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($texcel->B->Visible) { // B ?>
		<td<?php echo $texcel->B->CellAttributes() ?>>
<span id="el<?php echo $texcel_delete->RowCnt ?>_texcel_B" class="texcel_B">
<span<?php echo $texcel->B->ViewAttributes() ?>>
<?php echo $texcel->B->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($texcel->C->Visible) { // C ?>
		<td<?php echo $texcel->C->CellAttributes() ?>>
<span id="el<?php echo $texcel_delete->RowCnt ?>_texcel_C" class="texcel_C">
<span<?php echo $texcel->C->ViewAttributes() ?>>
<?php echo $texcel->C->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($texcel->D->Visible) { // D ?>
		<td<?php echo $texcel->D->CellAttributes() ?>>
<span id="el<?php echo $texcel_delete->RowCnt ?>_texcel_D" class="texcel_D">
<span<?php echo $texcel->D->ViewAttributes() ?>>
<?php echo $texcel->D->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($texcel->E->Visible) { // E ?>
		<td<?php echo $texcel->E->CellAttributes() ?>>
<span id="el<?php echo $texcel_delete->RowCnt ?>_texcel_E" class="texcel_E">
<span<?php echo $texcel->E->ViewAttributes() ?>>
<?php echo $texcel->E->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($texcel->F->Visible) { // F ?>
		<td<?php echo $texcel->F->CellAttributes() ?>>
<span id="el<?php echo $texcel_delete->RowCnt ?>_texcel_F" class="texcel_F">
<span<?php echo $texcel->F->ViewAttributes() ?>>
<?php echo $texcel->F->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($texcel->G->Visible) { // G ?>
		<td<?php echo $texcel->G->CellAttributes() ?>>
<span id="el<?php echo $texcel_delete->RowCnt ?>_texcel_G" class="texcel_G">
<span<?php echo $texcel->G->ViewAttributes() ?>>
<?php echo $texcel->G->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($texcel->H->Visible) { // H ?>
		<td<?php echo $texcel->H->CellAttributes() ?>>
<span id="el<?php echo $texcel_delete->RowCnt ?>_texcel_H" class="texcel_H">
<span<?php echo $texcel->H->ViewAttributes() ?>>
<?php echo $texcel->H->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($texcel->_I->Visible) { // I ?>
		<td<?php echo $texcel->_I->CellAttributes() ?>>
<span id="el<?php echo $texcel_delete->RowCnt ?>_texcel__I" class="texcel__I">
<span<?php echo $texcel->_I->ViewAttributes() ?>>
<?php echo $texcel->_I->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($texcel->J->Visible) { // J ?>
		<td<?php echo $texcel->J->CellAttributes() ?>>
<span id="el<?php echo $texcel_delete->RowCnt ?>_texcel_J" class="texcel_J">
<span<?php echo $texcel->J->ViewAttributes() ?>>
<?php echo $texcel->J->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($texcel->K->Visible) { // K ?>
		<td<?php echo $texcel->K->CellAttributes() ?>>
<span id="el<?php echo $texcel_delete->RowCnt ?>_texcel_K" class="texcel_K">
<span<?php echo $texcel->K->ViewAttributes() ?>>
<?php echo $texcel->K->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($texcel->L->Visible) { // L ?>
		<td<?php echo $texcel->L->CellAttributes() ?>>
<span id="el<?php echo $texcel_delete->RowCnt ?>_texcel_L" class="texcel_L">
<span<?php echo $texcel->L->ViewAttributes() ?>>
<?php echo $texcel->L->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($texcel->M->Visible) { // M ?>
		<td<?php echo $texcel->M->CellAttributes() ?>>
<span id="el<?php echo $texcel_delete->RowCnt ?>_texcel_M" class="texcel_M">
<span<?php echo $texcel->M->ViewAttributes() ?>>
<?php echo $texcel->M->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($texcel->N->Visible) { // N ?>
		<td<?php echo $texcel->N->CellAttributes() ?>>
<span id="el<?php echo $texcel_delete->RowCnt ?>_texcel_N" class="texcel_N">
<span<?php echo $texcel->N->ViewAttributes() ?>>
<?php echo $texcel->N->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($texcel->O->Visible) { // O ?>
		<td<?php echo $texcel->O->CellAttributes() ?>>
<span id="el<?php echo $texcel_delete->RowCnt ?>_texcel_O" class="texcel_O">
<span<?php echo $texcel->O->ViewAttributes() ?>>
<?php echo $texcel->O->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($texcel->P->Visible) { // P ?>
		<td<?php echo $texcel->P->CellAttributes() ?>>
<span id="el<?php echo $texcel_delete->RowCnt ?>_texcel_P" class="texcel_P">
<span<?php echo $texcel->P->ViewAttributes() ?>>
<?php echo $texcel->P->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($texcel->Q->Visible) { // Q ?>
		<td<?php echo $texcel->Q->CellAttributes() ?>>
<span id="el<?php echo $texcel_delete->RowCnt ?>_texcel_Q" class="texcel_Q">
<span<?php echo $texcel->Q->ViewAttributes() ?>>
<?php echo $texcel->Q->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($texcel->R->Visible) { // R ?>
		<td<?php echo $texcel->R->CellAttributes() ?>>
<span id="el<?php echo $texcel_delete->RowCnt ?>_texcel_R" class="texcel_R">
<span<?php echo $texcel->R->ViewAttributes() ?>>
<?php echo $texcel->R->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($texcel->S->Visible) { // S ?>
		<td<?php echo $texcel->S->CellAttributes() ?>>
<span id="el<?php echo $texcel_delete->RowCnt ?>_texcel_S" class="texcel_S">
<span<?php echo $texcel->S->ViewAttributes() ?>>
<?php echo $texcel->S->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($texcel->T->Visible) { // T ?>
		<td<?php echo $texcel->T->CellAttributes() ?>>
<span id="el<?php echo $texcel_delete->RowCnt ?>_texcel_T" class="texcel_T">
<span<?php echo $texcel->T->ViewAttributes() ?>>
<?php echo $texcel->T->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($texcel->U->Visible) { // U ?>
		<td<?php echo $texcel->U->CellAttributes() ?>>
<span id="el<?php echo $texcel_delete->RowCnt ?>_texcel_U" class="texcel_U">
<span<?php echo $texcel->U->ViewAttributes() ?>>
<?php echo $texcel->U->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($texcel->V->Visible) { // V ?>
		<td<?php echo $texcel->V->CellAttributes() ?>>
<span id="el<?php echo $texcel_delete->RowCnt ?>_texcel_V" class="texcel_V">
<span<?php echo $texcel->V->ViewAttributes() ?>>
<?php echo $texcel->V->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($texcel->W->Visible) { // W ?>
		<td<?php echo $texcel->W->CellAttributes() ?>>
<span id="el<?php echo $texcel_delete->RowCnt ?>_texcel_W" class="texcel_W">
<span<?php echo $texcel->W->ViewAttributes() ?>>
<?php echo $texcel->W->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($texcel->X->Visible) { // X ?>
		<td<?php echo $texcel->X->CellAttributes() ?>>
<span id="el<?php echo $texcel_delete->RowCnt ?>_texcel_X" class="texcel_X">
<span<?php echo $texcel->X->ViewAttributes() ?>>
<?php echo $texcel->X->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($texcel->Y->Visible) { // Y ?>
		<td<?php echo $texcel->Y->CellAttributes() ?>>
<span id="el<?php echo $texcel_delete->RowCnt ?>_texcel_Y" class="texcel_Y">
<span<?php echo $texcel->Y->ViewAttributes() ?>>
<?php echo $texcel->Y->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($texcel->Z->Visible) { // Z ?>
		<td<?php echo $texcel->Z->CellAttributes() ?>>
<span id="el<?php echo $texcel_delete->RowCnt ?>_texcel_Z" class="texcel_Z">
<span<?php echo $texcel->Z->ViewAttributes() ?>>
<?php echo $texcel->Z->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$texcel_delete->Recordset->MoveNext();
}
$texcel_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $texcel_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
ftexceldelete.Init();
</script>
<?php
$texcel_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$texcel_delete->Page_Terminate();
?>
