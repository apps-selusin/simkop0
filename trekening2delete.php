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

$trekening2_delete = NULL; // Initialize page object first

class ctrekening2_delete extends ctrekening2 {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'trekening2';

	// Page object name
	var $PageObjName = 'trekening2_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
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
			$this->Page_Terminate("trekening2list.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in trekening2 class, trekening2info.php

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
				$this->Page_Terminate("trekening2list.php"); // Return to list
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
				$sThisKey .= $row['id'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("trekening2list.php"), "", $this->TableVar, TRUE);
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
if (!isset($trekening2_delete)) $trekening2_delete = new ctrekening2_delete();

// Page init
$trekening2_delete->Page_Init();

// Page main
$trekening2_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$trekening2_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = ftrekening2delete = new ew_Form("ftrekening2delete", "delete");

// Form_CustomValidate event
ftrekening2delete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftrekening2delete.ValidateRequired = true;
<?php } else { ?>
ftrekening2delete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftrekening2delete.Lists["x_parent1"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_rekening","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"trekening2"};
ftrekening2delete.Lists["x_parent2"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_rekening","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"trekening2"};
ftrekening2delete.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftrekening2delete.Lists["x_active"].Options = <?php echo json_encode($trekening2->active->Options()) ?>;

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
<?php $trekening2_delete->ShowPageHeader(); ?>
<?php
$trekening2_delete->ShowMessage();
?>
<form name="ftrekening2delete" id="ftrekening2delete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($trekening2_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $trekening2_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="trekening2">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($trekening2_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $trekening2->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($trekening2->parent1->Visible) { // parent1 ?>
		<th><span id="elh_trekening2_parent1" class="trekening2_parent1"><?php echo $trekening2->parent1->FldCaption() ?></span></th>
<?php } ?>
<?php if ($trekening2->id1->Visible) { // id1 ?>
		<th><span id="elh_trekening2_id1" class="trekening2_id1"><?php echo $trekening2->id1->FldCaption() ?></span></th>
<?php } ?>
<?php if ($trekening2->rekening1->Visible) { // rekening1 ?>
		<th><span id="elh_trekening2_rekening1" class="trekening2_rekening1"><?php echo $trekening2->rekening1->FldCaption() ?></span></th>
<?php } ?>
<?php if ($trekening2->parent2->Visible) { // parent2 ?>
		<th><span id="elh_trekening2_parent2" class="trekening2_parent2"><?php echo $trekening2->parent2->FldCaption() ?></span></th>
<?php } ?>
<?php if ($trekening2->id2->Visible) { // id2 ?>
		<th><span id="elh_trekening2_id2" class="trekening2_id2"><?php echo $trekening2->id2->FldCaption() ?></span></th>
<?php } ?>
<?php if ($trekening2->rekening2->Visible) { // rekening2 ?>
		<th><span id="elh_trekening2_rekening2" class="trekening2_rekening2"><?php echo $trekening2->rekening2->FldCaption() ?></span></th>
<?php } ?>
<?php if ($trekening2->tipe->Visible) { // tipe ?>
		<th><span id="elh_trekening2_tipe" class="trekening2_tipe"><?php echo $trekening2->tipe->FldCaption() ?></span></th>
<?php } ?>
<?php if ($trekening2->posisi->Visible) { // posisi ?>
		<th><span id="elh_trekening2_posisi" class="trekening2_posisi"><?php echo $trekening2->posisi->FldCaption() ?></span></th>
<?php } ?>
<?php if ($trekening2->laporan->Visible) { // laporan ?>
		<th><span id="elh_trekening2_laporan" class="trekening2_laporan"><?php echo $trekening2->laporan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($trekening2->status->Visible) { // status ?>
		<th><span id="elh_trekening2_status" class="trekening2_status"><?php echo $trekening2->status->FldCaption() ?></span></th>
<?php } ?>
<?php if ($trekening2->keterangan->Visible) { // keterangan ?>
		<th><span id="elh_trekening2_keterangan" class="trekening2_keterangan"><?php echo $trekening2->keterangan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($trekening2->active->Visible) { // active ?>
		<th><span id="elh_trekening2_active" class="trekening2_active"><?php echo $trekening2->active->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$trekening2_delete->RecCnt = 0;
$i = 0;
while (!$trekening2_delete->Recordset->EOF) {
	$trekening2_delete->RecCnt++;
	$trekening2_delete->RowCnt++;

	// Set row properties
	$trekening2->ResetAttrs();
	$trekening2->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$trekening2_delete->LoadRowValues($trekening2_delete->Recordset);

	// Render row
	$trekening2_delete->RenderRow();
?>
	<tr<?php echo $trekening2->RowAttributes() ?>>
<?php if ($trekening2->parent1->Visible) { // parent1 ?>
		<td<?php echo $trekening2->parent1->CellAttributes() ?>>
<span id="el<?php echo $trekening2_delete->RowCnt ?>_trekening2_parent1" class="trekening2_parent1">
<span<?php echo $trekening2->parent1->ViewAttributes() ?>>
<?php echo $trekening2->parent1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($trekening2->id1->Visible) { // id1 ?>
		<td<?php echo $trekening2->id1->CellAttributes() ?>>
<span id="el<?php echo $trekening2_delete->RowCnt ?>_trekening2_id1" class="trekening2_id1">
<span<?php echo $trekening2->id1->ViewAttributes() ?>>
<?php echo $trekening2->id1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($trekening2->rekening1->Visible) { // rekening1 ?>
		<td<?php echo $trekening2->rekening1->CellAttributes() ?>>
<span id="el<?php echo $trekening2_delete->RowCnt ?>_trekening2_rekening1" class="trekening2_rekening1">
<span<?php echo $trekening2->rekening1->ViewAttributes() ?>>
<?php echo $trekening2->rekening1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($trekening2->parent2->Visible) { // parent2 ?>
		<td<?php echo $trekening2->parent2->CellAttributes() ?>>
<span id="el<?php echo $trekening2_delete->RowCnt ?>_trekening2_parent2" class="trekening2_parent2">
<span<?php echo $trekening2->parent2->ViewAttributes() ?>>
<?php echo $trekening2->parent2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($trekening2->id2->Visible) { // id2 ?>
		<td<?php echo $trekening2->id2->CellAttributes() ?>>
<span id="el<?php echo $trekening2_delete->RowCnt ?>_trekening2_id2" class="trekening2_id2">
<span<?php echo $trekening2->id2->ViewAttributes() ?>>
<?php echo $trekening2->id2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($trekening2->rekening2->Visible) { // rekening2 ?>
		<td<?php echo $trekening2->rekening2->CellAttributes() ?>>
<span id="el<?php echo $trekening2_delete->RowCnt ?>_trekening2_rekening2" class="trekening2_rekening2">
<span<?php echo $trekening2->rekening2->ViewAttributes() ?>>
<?php echo $trekening2->rekening2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($trekening2->tipe->Visible) { // tipe ?>
		<td<?php echo $trekening2->tipe->CellAttributes() ?>>
<span id="el<?php echo $trekening2_delete->RowCnt ?>_trekening2_tipe" class="trekening2_tipe">
<span<?php echo $trekening2->tipe->ViewAttributes() ?>>
<?php echo $trekening2->tipe->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($trekening2->posisi->Visible) { // posisi ?>
		<td<?php echo $trekening2->posisi->CellAttributes() ?>>
<span id="el<?php echo $trekening2_delete->RowCnt ?>_trekening2_posisi" class="trekening2_posisi">
<span<?php echo $trekening2->posisi->ViewAttributes() ?>>
<?php echo $trekening2->posisi->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($trekening2->laporan->Visible) { // laporan ?>
		<td<?php echo $trekening2->laporan->CellAttributes() ?>>
<span id="el<?php echo $trekening2_delete->RowCnt ?>_trekening2_laporan" class="trekening2_laporan">
<span<?php echo $trekening2->laporan->ViewAttributes() ?>>
<?php echo $trekening2->laporan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($trekening2->status->Visible) { // status ?>
		<td<?php echo $trekening2->status->CellAttributes() ?>>
<span id="el<?php echo $trekening2_delete->RowCnt ?>_trekening2_status" class="trekening2_status">
<span<?php echo $trekening2->status->ViewAttributes() ?>>
<?php echo $trekening2->status->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($trekening2->keterangan->Visible) { // keterangan ?>
		<td<?php echo $trekening2->keterangan->CellAttributes() ?>>
<span id="el<?php echo $trekening2_delete->RowCnt ?>_trekening2_keterangan" class="trekening2_keterangan">
<span<?php echo $trekening2->keterangan->ViewAttributes() ?>>
<?php echo $trekening2->keterangan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($trekening2->active->Visible) { // active ?>
		<td<?php echo $trekening2->active->CellAttributes() ?>>
<span id="el<?php echo $trekening2_delete->RowCnt ?>_trekening2_active" class="trekening2_active">
<span<?php echo $trekening2->active->ViewAttributes() ?>>
<?php echo $trekening2->active->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$trekening2_delete->Recordset->MoveNext();
}
$trekening2_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $trekening2_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
ftrekening2delete.Init();
</script>
<?php
$trekening2_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$trekening2_delete->Page_Terminate();
?>
