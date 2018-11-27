<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "tjurnalsaldoinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$tjurnalsaldo_delete = NULL; // Initialize page object first

class ctjurnalsaldo_delete extends ctjurnalsaldo {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tjurnalsaldo';

	// Page object name
	var $PageObjName = 'tjurnalsaldo_delete';

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

		// Table object (tjurnalsaldo)
		if (!isset($GLOBALS["tjurnalsaldo"]) || get_class($GLOBALS["tjurnalsaldo"]) == "ctjurnalsaldo") {
			$GLOBALS["tjurnalsaldo"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tjurnalsaldo"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tjurnalsaldo', TRUE);

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
		$this->tanggal->SetVisibility();
		$this->periode->SetVisibility();
		$this->id->SetVisibility();
		$this->nomor->SetVisibility();
		$this->transaksi->SetVisibility();
		$this->referensi->SetVisibility();
		$this->model->SetVisibility();
		$this->rekening->SetVisibility();
		$this->debet->SetVisibility();
		$this->credit->SetVisibility();
		$this->kantor->SetVisibility();
		$this->keterangan->SetVisibility();
		$this->active->SetVisibility();
		$this->ip->SetVisibility();
		$this->status->SetVisibility();
		$this->user->SetVisibility();
		$this->created->SetVisibility();
		$this->modified->SetVisibility();

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
		global $EW_EXPORT, $tjurnalsaldo;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($tjurnalsaldo);
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
			$this->Page_Terminate("tjurnalsaldolist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in tjurnalsaldo class, tjurnalsaldoinfo.php

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
				$this->Page_Terminate("tjurnalsaldolist.php"); // Return to list
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
		$this->tanggal->setDbValue($rs->fields('tanggal'));
		$this->periode->setDbValue($rs->fields('periode'));
		$this->id->setDbValue($rs->fields('id'));
		$this->nomor->setDbValue($rs->fields('nomor'));
		$this->transaksi->setDbValue($rs->fields('transaksi'));
		$this->referensi->setDbValue($rs->fields('referensi'));
		$this->model->setDbValue($rs->fields('model'));
		$this->rekening->setDbValue($rs->fields('rekening'));
		$this->debet->setDbValue($rs->fields('debet'));
		$this->credit->setDbValue($rs->fields('credit'));
		$this->kantor->setDbValue($rs->fields('kantor'));
		$this->keterangan->setDbValue($rs->fields('keterangan'));
		$this->active->setDbValue($rs->fields('active'));
		$this->ip->setDbValue($rs->fields('ip'));
		$this->status->setDbValue($rs->fields('status'));
		$this->user->setDbValue($rs->fields('user'));
		$this->created->setDbValue($rs->fields('created'));
		$this->modified->setDbValue($rs->fields('modified'));
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
		$this->model->DbValue = $row['model'];
		$this->rekening->DbValue = $row['rekening'];
		$this->debet->DbValue = $row['debet'];
		$this->credit->DbValue = $row['credit'];
		$this->kantor->DbValue = $row['kantor'];
		$this->keterangan->DbValue = $row['keterangan'];
		$this->active->DbValue = $row['active'];
		$this->ip->DbValue = $row['ip'];
		$this->status->DbValue = $row['status'];
		$this->user->DbValue = $row['user'];
		$this->created->DbValue = $row['created'];
		$this->modified->DbValue = $row['modified'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->debet->FormValue == $this->debet->CurrentValue && is_numeric(ew_StrToFloat($this->debet->CurrentValue)))
			$this->debet->CurrentValue = ew_StrToFloat($this->debet->CurrentValue);

		// Convert decimal values if posted back
		if ($this->credit->FormValue == $this->credit->CurrentValue && is_numeric(ew_StrToFloat($this->credit->CurrentValue)))
			$this->credit->CurrentValue = ew_StrToFloat($this->credit->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// tanggal
		// periode
		// id
		// nomor
		// transaksi
		// referensi
		// model
		// rekening
		// debet
		// credit
		// kantor
		// keterangan
		// active
		// ip
		// status
		// user
		// created
		// modified

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

		// model
		$this->model->ViewValue = $this->model->CurrentValue;
		$this->model->ViewCustomAttributes = "";

		// rekening
		$this->rekening->ViewValue = $this->rekening->CurrentValue;
		$this->rekening->ViewCustomAttributes = "";

		// debet
		$this->debet->ViewValue = $this->debet->CurrentValue;
		$this->debet->ViewCustomAttributes = "";

		// credit
		$this->credit->ViewValue = $this->credit->CurrentValue;
		$this->credit->ViewCustomAttributes = "";

		// kantor
		$this->kantor->ViewValue = $this->kantor->CurrentValue;
		$this->kantor->ViewCustomAttributes = "";

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

		// ip
		$this->ip->ViewValue = $this->ip->CurrentValue;
		$this->ip->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		$this->status->ViewCustomAttributes = "";

		// user
		$this->user->ViewValue = $this->user->CurrentValue;
		$this->user->ViewCustomAttributes = "";

		// created
		$this->created->ViewValue = $this->created->CurrentValue;
		$this->created->ViewValue = ew_FormatDateTime($this->created->ViewValue, 0);
		$this->created->ViewCustomAttributes = "";

		// modified
		$this->modified->ViewValue = $this->modified->CurrentValue;
		$this->modified->ViewValue = ew_FormatDateTime($this->modified->ViewValue, 0);
		$this->modified->ViewCustomAttributes = "";

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

			// model
			$this->model->LinkCustomAttributes = "";
			$this->model->HrefValue = "";
			$this->model->TooltipValue = "";

			// rekening
			$this->rekening->LinkCustomAttributes = "";
			$this->rekening->HrefValue = "";
			$this->rekening->TooltipValue = "";

			// debet
			$this->debet->LinkCustomAttributes = "";
			$this->debet->HrefValue = "";
			$this->debet->TooltipValue = "";

			// credit
			$this->credit->LinkCustomAttributes = "";
			$this->credit->HrefValue = "";
			$this->credit->TooltipValue = "";

			// kantor
			$this->kantor->LinkCustomAttributes = "";
			$this->kantor->HrefValue = "";
			$this->kantor->TooltipValue = "";

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";
			$this->keterangan->TooltipValue = "";

			// active
			$this->active->LinkCustomAttributes = "";
			$this->active->HrefValue = "";
			$this->active->TooltipValue = "";

			// ip
			$this->ip->LinkCustomAttributes = "";
			$this->ip->HrefValue = "";
			$this->ip->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// user
			$this->user->LinkCustomAttributes = "";
			$this->user->HrefValue = "";
			$this->user->TooltipValue = "";

			// created
			$this->created->LinkCustomAttributes = "";
			$this->created->HrefValue = "";
			$this->created->TooltipValue = "";

			// modified
			$this->modified->LinkCustomAttributes = "";
			$this->modified->HrefValue = "";
			$this->modified->TooltipValue = "";
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
				$sThisKey .= $row['nomor'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("tjurnalsaldolist.php"), "", $this->TableVar, TRUE);
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
if (!isset($tjurnalsaldo_delete)) $tjurnalsaldo_delete = new ctjurnalsaldo_delete();

// Page init
$tjurnalsaldo_delete->Page_Init();

// Page main
$tjurnalsaldo_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tjurnalsaldo_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = ftjurnalsaldodelete = new ew_Form("ftjurnalsaldodelete", "delete");

// Form_CustomValidate event
ftjurnalsaldodelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftjurnalsaldodelete.ValidateRequired = true;
<?php } else { ?>
ftjurnalsaldodelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftjurnalsaldodelete.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftjurnalsaldodelete.Lists["x_active"].Options = <?php echo json_encode($tjurnalsaldo->active->Options()) ?>;

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
<?php $tjurnalsaldo_delete->ShowPageHeader(); ?>
<?php
$tjurnalsaldo_delete->ShowMessage();
?>
<form name="ftjurnalsaldodelete" id="ftjurnalsaldodelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tjurnalsaldo_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tjurnalsaldo_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tjurnalsaldo">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($tjurnalsaldo_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $tjurnalsaldo->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($tjurnalsaldo->tanggal->Visible) { // tanggal ?>
		<th><span id="elh_tjurnalsaldo_tanggal" class="tjurnalsaldo_tanggal"><?php echo $tjurnalsaldo->tanggal->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalsaldo->periode->Visible) { // periode ?>
		<th><span id="elh_tjurnalsaldo_periode" class="tjurnalsaldo_periode"><?php echo $tjurnalsaldo->periode->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalsaldo->id->Visible) { // id ?>
		<th><span id="elh_tjurnalsaldo_id" class="tjurnalsaldo_id"><?php echo $tjurnalsaldo->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalsaldo->nomor->Visible) { // nomor ?>
		<th><span id="elh_tjurnalsaldo_nomor" class="tjurnalsaldo_nomor"><?php echo $tjurnalsaldo->nomor->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalsaldo->transaksi->Visible) { // transaksi ?>
		<th><span id="elh_tjurnalsaldo_transaksi" class="tjurnalsaldo_transaksi"><?php echo $tjurnalsaldo->transaksi->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalsaldo->referensi->Visible) { // referensi ?>
		<th><span id="elh_tjurnalsaldo_referensi" class="tjurnalsaldo_referensi"><?php echo $tjurnalsaldo->referensi->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalsaldo->model->Visible) { // model ?>
		<th><span id="elh_tjurnalsaldo_model" class="tjurnalsaldo_model"><?php echo $tjurnalsaldo->model->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalsaldo->rekening->Visible) { // rekening ?>
		<th><span id="elh_tjurnalsaldo_rekening" class="tjurnalsaldo_rekening"><?php echo $tjurnalsaldo->rekening->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalsaldo->debet->Visible) { // debet ?>
		<th><span id="elh_tjurnalsaldo_debet" class="tjurnalsaldo_debet"><?php echo $tjurnalsaldo->debet->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalsaldo->credit->Visible) { // credit ?>
		<th><span id="elh_tjurnalsaldo_credit" class="tjurnalsaldo_credit"><?php echo $tjurnalsaldo->credit->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalsaldo->kantor->Visible) { // kantor ?>
		<th><span id="elh_tjurnalsaldo_kantor" class="tjurnalsaldo_kantor"><?php echo $tjurnalsaldo->kantor->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalsaldo->keterangan->Visible) { // keterangan ?>
		<th><span id="elh_tjurnalsaldo_keterangan" class="tjurnalsaldo_keterangan"><?php echo $tjurnalsaldo->keterangan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalsaldo->active->Visible) { // active ?>
		<th><span id="elh_tjurnalsaldo_active" class="tjurnalsaldo_active"><?php echo $tjurnalsaldo->active->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalsaldo->ip->Visible) { // ip ?>
		<th><span id="elh_tjurnalsaldo_ip" class="tjurnalsaldo_ip"><?php echo $tjurnalsaldo->ip->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalsaldo->status->Visible) { // status ?>
		<th><span id="elh_tjurnalsaldo_status" class="tjurnalsaldo_status"><?php echo $tjurnalsaldo->status->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalsaldo->user->Visible) { // user ?>
		<th><span id="elh_tjurnalsaldo_user" class="tjurnalsaldo_user"><?php echo $tjurnalsaldo->user->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalsaldo->created->Visible) { // created ?>
		<th><span id="elh_tjurnalsaldo_created" class="tjurnalsaldo_created"><?php echo $tjurnalsaldo->created->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalsaldo->modified->Visible) { // modified ?>
		<th><span id="elh_tjurnalsaldo_modified" class="tjurnalsaldo_modified"><?php echo $tjurnalsaldo->modified->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$tjurnalsaldo_delete->RecCnt = 0;
$i = 0;
while (!$tjurnalsaldo_delete->Recordset->EOF) {
	$tjurnalsaldo_delete->RecCnt++;
	$tjurnalsaldo_delete->RowCnt++;

	// Set row properties
	$tjurnalsaldo->ResetAttrs();
	$tjurnalsaldo->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$tjurnalsaldo_delete->LoadRowValues($tjurnalsaldo_delete->Recordset);

	// Render row
	$tjurnalsaldo_delete->RenderRow();
?>
	<tr<?php echo $tjurnalsaldo->RowAttributes() ?>>
<?php if ($tjurnalsaldo->tanggal->Visible) { // tanggal ?>
		<td<?php echo $tjurnalsaldo->tanggal->CellAttributes() ?>>
<span id="el<?php echo $tjurnalsaldo_delete->RowCnt ?>_tjurnalsaldo_tanggal" class="tjurnalsaldo_tanggal">
<span<?php echo $tjurnalsaldo->tanggal->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->tanggal->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalsaldo->periode->Visible) { // periode ?>
		<td<?php echo $tjurnalsaldo->periode->CellAttributes() ?>>
<span id="el<?php echo $tjurnalsaldo_delete->RowCnt ?>_tjurnalsaldo_periode" class="tjurnalsaldo_periode">
<span<?php echo $tjurnalsaldo->periode->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->periode->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalsaldo->id->Visible) { // id ?>
		<td<?php echo $tjurnalsaldo->id->CellAttributes() ?>>
<span id="el<?php echo $tjurnalsaldo_delete->RowCnt ?>_tjurnalsaldo_id" class="tjurnalsaldo_id">
<span<?php echo $tjurnalsaldo->id->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalsaldo->nomor->Visible) { // nomor ?>
		<td<?php echo $tjurnalsaldo->nomor->CellAttributes() ?>>
<span id="el<?php echo $tjurnalsaldo_delete->RowCnt ?>_tjurnalsaldo_nomor" class="tjurnalsaldo_nomor">
<span<?php echo $tjurnalsaldo->nomor->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->nomor->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalsaldo->transaksi->Visible) { // transaksi ?>
		<td<?php echo $tjurnalsaldo->transaksi->CellAttributes() ?>>
<span id="el<?php echo $tjurnalsaldo_delete->RowCnt ?>_tjurnalsaldo_transaksi" class="tjurnalsaldo_transaksi">
<span<?php echo $tjurnalsaldo->transaksi->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->transaksi->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalsaldo->referensi->Visible) { // referensi ?>
		<td<?php echo $tjurnalsaldo->referensi->CellAttributes() ?>>
<span id="el<?php echo $tjurnalsaldo_delete->RowCnt ?>_tjurnalsaldo_referensi" class="tjurnalsaldo_referensi">
<span<?php echo $tjurnalsaldo->referensi->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->referensi->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalsaldo->model->Visible) { // model ?>
		<td<?php echo $tjurnalsaldo->model->CellAttributes() ?>>
<span id="el<?php echo $tjurnalsaldo_delete->RowCnt ?>_tjurnalsaldo_model" class="tjurnalsaldo_model">
<span<?php echo $tjurnalsaldo->model->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->model->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalsaldo->rekening->Visible) { // rekening ?>
		<td<?php echo $tjurnalsaldo->rekening->CellAttributes() ?>>
<span id="el<?php echo $tjurnalsaldo_delete->RowCnt ?>_tjurnalsaldo_rekening" class="tjurnalsaldo_rekening">
<span<?php echo $tjurnalsaldo->rekening->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->rekening->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalsaldo->debet->Visible) { // debet ?>
		<td<?php echo $tjurnalsaldo->debet->CellAttributes() ?>>
<span id="el<?php echo $tjurnalsaldo_delete->RowCnt ?>_tjurnalsaldo_debet" class="tjurnalsaldo_debet">
<span<?php echo $tjurnalsaldo->debet->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->debet->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalsaldo->credit->Visible) { // credit ?>
		<td<?php echo $tjurnalsaldo->credit->CellAttributes() ?>>
<span id="el<?php echo $tjurnalsaldo_delete->RowCnt ?>_tjurnalsaldo_credit" class="tjurnalsaldo_credit">
<span<?php echo $tjurnalsaldo->credit->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->credit->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalsaldo->kantor->Visible) { // kantor ?>
		<td<?php echo $tjurnalsaldo->kantor->CellAttributes() ?>>
<span id="el<?php echo $tjurnalsaldo_delete->RowCnt ?>_tjurnalsaldo_kantor" class="tjurnalsaldo_kantor">
<span<?php echo $tjurnalsaldo->kantor->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->kantor->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalsaldo->keterangan->Visible) { // keterangan ?>
		<td<?php echo $tjurnalsaldo->keterangan->CellAttributes() ?>>
<span id="el<?php echo $tjurnalsaldo_delete->RowCnt ?>_tjurnalsaldo_keterangan" class="tjurnalsaldo_keterangan">
<span<?php echo $tjurnalsaldo->keterangan->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->keterangan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalsaldo->active->Visible) { // active ?>
		<td<?php echo $tjurnalsaldo->active->CellAttributes() ?>>
<span id="el<?php echo $tjurnalsaldo_delete->RowCnt ?>_tjurnalsaldo_active" class="tjurnalsaldo_active">
<span<?php echo $tjurnalsaldo->active->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->active->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalsaldo->ip->Visible) { // ip ?>
		<td<?php echo $tjurnalsaldo->ip->CellAttributes() ?>>
<span id="el<?php echo $tjurnalsaldo_delete->RowCnt ?>_tjurnalsaldo_ip" class="tjurnalsaldo_ip">
<span<?php echo $tjurnalsaldo->ip->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->ip->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalsaldo->status->Visible) { // status ?>
		<td<?php echo $tjurnalsaldo->status->CellAttributes() ?>>
<span id="el<?php echo $tjurnalsaldo_delete->RowCnt ?>_tjurnalsaldo_status" class="tjurnalsaldo_status">
<span<?php echo $tjurnalsaldo->status->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->status->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalsaldo->user->Visible) { // user ?>
		<td<?php echo $tjurnalsaldo->user->CellAttributes() ?>>
<span id="el<?php echo $tjurnalsaldo_delete->RowCnt ?>_tjurnalsaldo_user" class="tjurnalsaldo_user">
<span<?php echo $tjurnalsaldo->user->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->user->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalsaldo->created->Visible) { // created ?>
		<td<?php echo $tjurnalsaldo->created->CellAttributes() ?>>
<span id="el<?php echo $tjurnalsaldo_delete->RowCnt ?>_tjurnalsaldo_created" class="tjurnalsaldo_created">
<span<?php echo $tjurnalsaldo->created->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->created->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalsaldo->modified->Visible) { // modified ?>
		<td<?php echo $tjurnalsaldo->modified->CellAttributes() ?>>
<span id="el<?php echo $tjurnalsaldo_delete->RowCnt ?>_tjurnalsaldo_modified" class="tjurnalsaldo_modified">
<span<?php echo $tjurnalsaldo->modified->ViewAttributes() ?>>
<?php echo $tjurnalsaldo->modified->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$tjurnalsaldo_delete->Recordset->MoveNext();
}
$tjurnalsaldo_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $tjurnalsaldo_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
ftjurnalsaldodelete.Init();
</script>
<?php
$tjurnalsaldo_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tjurnalsaldo_delete->Page_Terminate();
?>
