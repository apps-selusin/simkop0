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

$tpinjamandetail__delete = NULL; // Initialize page object first

class ctpinjamandetail__delete extends ctpinjamandetail_ {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tpinjamandetail_';

	// Page object name
	var $PageObjName = 'tpinjamandetail__delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
			$this->Page_Terminate("tpinjamandetail_list.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in tpinjamandetail_ class, tpinjamandetail_info.php

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
				$this->Page_Terminate("tpinjamandetail_list.php"); // Return to list
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
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['berjangka'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['angsuran'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("tpinjamandetail_list.php"), "", $this->TableVar, TRUE);
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
if (!isset($tpinjamandetail__delete)) $tpinjamandetail__delete = new ctpinjamandetail__delete();

// Page init
$tpinjamandetail__delete->Page_Init();

// Page main
$tpinjamandetail__delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tpinjamandetail__delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = ftpinjamandetail_delete = new ew_Form("ftpinjamandetail_delete", "delete");

// Form_CustomValidate event
ftpinjamandetail_delete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftpinjamandetail_delete.ValidateRequired = true;
<?php } else { ?>
ftpinjamandetail_delete.ValidateRequired = false; 
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
<?php $tpinjamandetail__delete->ShowPageHeader(); ?>
<?php
$tpinjamandetail__delete->ShowMessage();
?>
<form name="ftpinjamandetail_delete" id="ftpinjamandetail_delete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tpinjamandetail__delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tpinjamandetail__delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tpinjamandetail_">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($tpinjamandetail__delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $tpinjamandetail_->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($tpinjamandetail_->id->Visible) { // id ?>
		<th><span id="elh_tpinjamandetail__id" class="tpinjamandetail__id"><?php echo $tpinjamandetail_->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjamandetail_->berjangka->Visible) { // berjangka ?>
		<th><span id="elh_tpinjamandetail__berjangka" class="tpinjamandetail__berjangka"><?php echo $tpinjamandetail_->berjangka->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjamandetail_->angsuran->Visible) { // angsuran ?>
		<th><span id="elh_tpinjamandetail__angsuran" class="tpinjamandetail__angsuran"><?php echo $tpinjamandetail_->angsuran->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjamandetail_->angsuranpokok->Visible) { // angsuranpokok ?>
		<th><span id="elh_tpinjamandetail__angsuranpokok" class="tpinjamandetail__angsuranpokok"><?php echo $tpinjamandetail_->angsuranpokok->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjamandetail_->angsuranpokokauto->Visible) { // angsuranpokokauto ?>
		<th><span id="elh_tpinjamandetail__angsuranpokokauto" class="tpinjamandetail__angsuranpokokauto"><?php echo $tpinjamandetail_->angsuranpokokauto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjamandetail_->angsuranbunga->Visible) { // angsuranbunga ?>
		<th><span id="elh_tpinjamandetail__angsuranbunga" class="tpinjamandetail__angsuranbunga"><?php echo $tpinjamandetail_->angsuranbunga->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjamandetail_->angsuranbungaauto->Visible) { // angsuranbungaauto ?>
		<th><span id="elh_tpinjamandetail__angsuranbungaauto" class="tpinjamandetail__angsuranbungaauto"><?php echo $tpinjamandetail_->angsuranbungaauto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjamandetail_->totalangsuran->Visible) { // totalangsuran ?>
		<th><span id="elh_tpinjamandetail__totalangsuran" class="tpinjamandetail__totalangsuran"><?php echo $tpinjamandetail_->totalangsuran->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjamandetail_->totalangsuranauto->Visible) { // totalangsuranauto ?>
		<th><span id="elh_tpinjamandetail__totalangsuranauto" class="tpinjamandetail__totalangsuranauto"><?php echo $tpinjamandetail_->totalangsuranauto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjamandetail_->sisaangsuran->Visible) { // sisaangsuran ?>
		<th><span id="elh_tpinjamandetail__sisaangsuran" class="tpinjamandetail__sisaangsuran"><?php echo $tpinjamandetail_->sisaangsuran->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjamandetail_->sisaangsuranauto->Visible) { // sisaangsuranauto ?>
		<th><span id="elh_tpinjamandetail__sisaangsuranauto" class="tpinjamandetail__sisaangsuranauto"><?php echo $tpinjamandetail_->sisaangsuranauto->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$tpinjamandetail__delete->RecCnt = 0;
$i = 0;
while (!$tpinjamandetail__delete->Recordset->EOF) {
	$tpinjamandetail__delete->RecCnt++;
	$tpinjamandetail__delete->RowCnt++;

	// Set row properties
	$tpinjamandetail_->ResetAttrs();
	$tpinjamandetail_->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$tpinjamandetail__delete->LoadRowValues($tpinjamandetail__delete->Recordset);

	// Render row
	$tpinjamandetail__delete->RenderRow();
?>
	<tr<?php echo $tpinjamandetail_->RowAttributes() ?>>
<?php if ($tpinjamandetail_->id->Visible) { // id ?>
		<td<?php echo $tpinjamandetail_->id->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail__delete->RowCnt ?>_tpinjamandetail__id" class="tpinjamandetail__id">
<span<?php echo $tpinjamandetail_->id->ViewAttributes() ?>>
<?php echo $tpinjamandetail_->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjamandetail_->berjangka->Visible) { // berjangka ?>
		<td<?php echo $tpinjamandetail_->berjangka->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail__delete->RowCnt ?>_tpinjamandetail__berjangka" class="tpinjamandetail__berjangka">
<span<?php echo $tpinjamandetail_->berjangka->ViewAttributes() ?>>
<?php echo $tpinjamandetail_->berjangka->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjamandetail_->angsuran->Visible) { // angsuran ?>
		<td<?php echo $tpinjamandetail_->angsuran->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail__delete->RowCnt ?>_tpinjamandetail__angsuran" class="tpinjamandetail__angsuran">
<span<?php echo $tpinjamandetail_->angsuran->ViewAttributes() ?>>
<?php echo $tpinjamandetail_->angsuran->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjamandetail_->angsuranpokok->Visible) { // angsuranpokok ?>
		<td<?php echo $tpinjamandetail_->angsuranpokok->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail__delete->RowCnt ?>_tpinjamandetail__angsuranpokok" class="tpinjamandetail__angsuranpokok">
<span<?php echo $tpinjamandetail_->angsuranpokok->ViewAttributes() ?>>
<?php echo $tpinjamandetail_->angsuranpokok->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjamandetail_->angsuranpokokauto->Visible) { // angsuranpokokauto ?>
		<td<?php echo $tpinjamandetail_->angsuranpokokauto->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail__delete->RowCnt ?>_tpinjamandetail__angsuranpokokauto" class="tpinjamandetail__angsuranpokokauto">
<span<?php echo $tpinjamandetail_->angsuranpokokauto->ViewAttributes() ?>>
<?php echo $tpinjamandetail_->angsuranpokokauto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjamandetail_->angsuranbunga->Visible) { // angsuranbunga ?>
		<td<?php echo $tpinjamandetail_->angsuranbunga->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail__delete->RowCnt ?>_tpinjamandetail__angsuranbunga" class="tpinjamandetail__angsuranbunga">
<span<?php echo $tpinjamandetail_->angsuranbunga->ViewAttributes() ?>>
<?php echo $tpinjamandetail_->angsuranbunga->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjamandetail_->angsuranbungaauto->Visible) { // angsuranbungaauto ?>
		<td<?php echo $tpinjamandetail_->angsuranbungaauto->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail__delete->RowCnt ?>_tpinjamandetail__angsuranbungaauto" class="tpinjamandetail__angsuranbungaauto">
<span<?php echo $tpinjamandetail_->angsuranbungaauto->ViewAttributes() ?>>
<?php echo $tpinjamandetail_->angsuranbungaauto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjamandetail_->totalangsuran->Visible) { // totalangsuran ?>
		<td<?php echo $tpinjamandetail_->totalangsuran->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail__delete->RowCnt ?>_tpinjamandetail__totalangsuran" class="tpinjamandetail__totalangsuran">
<span<?php echo $tpinjamandetail_->totalangsuran->ViewAttributes() ?>>
<?php echo $tpinjamandetail_->totalangsuran->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjamandetail_->totalangsuranauto->Visible) { // totalangsuranauto ?>
		<td<?php echo $tpinjamandetail_->totalangsuranauto->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail__delete->RowCnt ?>_tpinjamandetail__totalangsuranauto" class="tpinjamandetail__totalangsuranauto">
<span<?php echo $tpinjamandetail_->totalangsuranauto->ViewAttributes() ?>>
<?php echo $tpinjamandetail_->totalangsuranauto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjamandetail_->sisaangsuran->Visible) { // sisaangsuran ?>
		<td<?php echo $tpinjamandetail_->sisaangsuran->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail__delete->RowCnt ?>_tpinjamandetail__sisaangsuran" class="tpinjamandetail__sisaangsuran">
<span<?php echo $tpinjamandetail_->sisaangsuran->ViewAttributes() ?>>
<?php echo $tpinjamandetail_->sisaangsuran->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjamandetail_->sisaangsuranauto->Visible) { // sisaangsuranauto ?>
		<td<?php echo $tpinjamandetail_->sisaangsuranauto->CellAttributes() ?>>
<span id="el<?php echo $tpinjamandetail__delete->RowCnt ?>_tpinjamandetail__sisaangsuranauto" class="tpinjamandetail__sisaangsuranauto">
<span<?php echo $tpinjamandetail_->sisaangsuranauto->ViewAttributes() ?>>
<?php echo $tpinjamandetail_->sisaangsuranauto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$tpinjamandetail__delete->Recordset->MoveNext();
}
$tpinjamandetail__delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $tpinjamandetail__delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
ftpinjamandetail_delete.Init();
</script>
<?php
$tpinjamandetail__delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tpinjamandetail__delete->Page_Terminate();
?>
