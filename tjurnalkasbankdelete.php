<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "tjurnalkasbankinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$tjurnalkasbank_delete = NULL; // Initialize page object first

class ctjurnalkasbank_delete extends ctjurnalkasbank {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tjurnalkasbank';

	// Page object name
	var $PageObjName = 'tjurnalkasbank_delete';

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

		// Table object (tjurnalkasbank)
		if (!isset($GLOBALS["tjurnalkasbank"]) || get_class($GLOBALS["tjurnalkasbank"]) == "ctjurnalkasbank") {
			$GLOBALS["tjurnalkasbank"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tjurnalkasbank"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tjurnalkasbank', TRUE);

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
		global $EW_EXPORT, $tjurnalkasbank;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($tjurnalkasbank);
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
			$this->Page_Terminate("tjurnalkasbanklist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in tjurnalkasbank class, tjurnalkasbankinfo.php

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
				$this->Page_Terminate("tjurnalkasbanklist.php"); // Return to list
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("tjurnalkasbanklist.php"), "", $this->TableVar, TRUE);
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
if (!isset($tjurnalkasbank_delete)) $tjurnalkasbank_delete = new ctjurnalkasbank_delete();

// Page init
$tjurnalkasbank_delete->Page_Init();

// Page main
$tjurnalkasbank_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tjurnalkasbank_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = ftjurnalkasbankdelete = new ew_Form("ftjurnalkasbankdelete", "delete");

// Form_CustomValidate event
ftjurnalkasbankdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftjurnalkasbankdelete.ValidateRequired = true;
<?php } else { ?>
ftjurnalkasbankdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftjurnalkasbankdelete.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftjurnalkasbankdelete.Lists["x_active"].Options = <?php echo json_encode($tjurnalkasbank->active->Options()) ?>;

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
<?php $tjurnalkasbank_delete->ShowPageHeader(); ?>
<?php
$tjurnalkasbank_delete->ShowMessage();
?>
<form name="ftjurnalkasbankdelete" id="ftjurnalkasbankdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tjurnalkasbank_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tjurnalkasbank_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tjurnalkasbank">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($tjurnalkasbank_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $tjurnalkasbank->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($tjurnalkasbank->tanggal->Visible) { // tanggal ?>
		<th><span id="elh_tjurnalkasbank_tanggal" class="tjurnalkasbank_tanggal"><?php echo $tjurnalkasbank->tanggal->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalkasbank->periode->Visible) { // periode ?>
		<th><span id="elh_tjurnalkasbank_periode" class="tjurnalkasbank_periode"><?php echo $tjurnalkasbank->periode->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalkasbank->id->Visible) { // id ?>
		<th><span id="elh_tjurnalkasbank_id" class="tjurnalkasbank_id"><?php echo $tjurnalkasbank->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalkasbank->nomor->Visible) { // nomor ?>
		<th><span id="elh_tjurnalkasbank_nomor" class="tjurnalkasbank_nomor"><?php echo $tjurnalkasbank->nomor->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalkasbank->transaksi->Visible) { // transaksi ?>
		<th><span id="elh_tjurnalkasbank_transaksi" class="tjurnalkasbank_transaksi"><?php echo $tjurnalkasbank->transaksi->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalkasbank->referensi->Visible) { // referensi ?>
		<th><span id="elh_tjurnalkasbank_referensi" class="tjurnalkasbank_referensi"><?php echo $tjurnalkasbank->referensi->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalkasbank->model->Visible) { // model ?>
		<th><span id="elh_tjurnalkasbank_model" class="tjurnalkasbank_model"><?php echo $tjurnalkasbank->model->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalkasbank->rekening->Visible) { // rekening ?>
		<th><span id="elh_tjurnalkasbank_rekening" class="tjurnalkasbank_rekening"><?php echo $tjurnalkasbank->rekening->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalkasbank->debet->Visible) { // debet ?>
		<th><span id="elh_tjurnalkasbank_debet" class="tjurnalkasbank_debet"><?php echo $tjurnalkasbank->debet->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalkasbank->credit->Visible) { // credit ?>
		<th><span id="elh_tjurnalkasbank_credit" class="tjurnalkasbank_credit"><?php echo $tjurnalkasbank->credit->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalkasbank->kantor->Visible) { // kantor ?>
		<th><span id="elh_tjurnalkasbank_kantor" class="tjurnalkasbank_kantor"><?php echo $tjurnalkasbank->kantor->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalkasbank->keterangan->Visible) { // keterangan ?>
		<th><span id="elh_tjurnalkasbank_keterangan" class="tjurnalkasbank_keterangan"><?php echo $tjurnalkasbank->keterangan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalkasbank->active->Visible) { // active ?>
		<th><span id="elh_tjurnalkasbank_active" class="tjurnalkasbank_active"><?php echo $tjurnalkasbank->active->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalkasbank->ip->Visible) { // ip ?>
		<th><span id="elh_tjurnalkasbank_ip" class="tjurnalkasbank_ip"><?php echo $tjurnalkasbank->ip->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalkasbank->status->Visible) { // status ?>
		<th><span id="elh_tjurnalkasbank_status" class="tjurnalkasbank_status"><?php echo $tjurnalkasbank->status->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalkasbank->user->Visible) { // user ?>
		<th><span id="elh_tjurnalkasbank_user" class="tjurnalkasbank_user"><?php echo $tjurnalkasbank->user->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalkasbank->created->Visible) { // created ?>
		<th><span id="elh_tjurnalkasbank_created" class="tjurnalkasbank_created"><?php echo $tjurnalkasbank->created->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnalkasbank->modified->Visible) { // modified ?>
		<th><span id="elh_tjurnalkasbank_modified" class="tjurnalkasbank_modified"><?php echo $tjurnalkasbank->modified->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$tjurnalkasbank_delete->RecCnt = 0;
$i = 0;
while (!$tjurnalkasbank_delete->Recordset->EOF) {
	$tjurnalkasbank_delete->RecCnt++;
	$tjurnalkasbank_delete->RowCnt++;

	// Set row properties
	$tjurnalkasbank->ResetAttrs();
	$tjurnalkasbank->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$tjurnalkasbank_delete->LoadRowValues($tjurnalkasbank_delete->Recordset);

	// Render row
	$tjurnalkasbank_delete->RenderRow();
?>
	<tr<?php echo $tjurnalkasbank->RowAttributes() ?>>
<?php if ($tjurnalkasbank->tanggal->Visible) { // tanggal ?>
		<td<?php echo $tjurnalkasbank->tanggal->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_delete->RowCnt ?>_tjurnalkasbank_tanggal" class="tjurnalkasbank_tanggal">
<span<?php echo $tjurnalkasbank->tanggal->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->tanggal->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalkasbank->periode->Visible) { // periode ?>
		<td<?php echo $tjurnalkasbank->periode->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_delete->RowCnt ?>_tjurnalkasbank_periode" class="tjurnalkasbank_periode">
<span<?php echo $tjurnalkasbank->periode->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->periode->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalkasbank->id->Visible) { // id ?>
		<td<?php echo $tjurnalkasbank->id->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_delete->RowCnt ?>_tjurnalkasbank_id" class="tjurnalkasbank_id">
<span<?php echo $tjurnalkasbank->id->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalkasbank->nomor->Visible) { // nomor ?>
		<td<?php echo $tjurnalkasbank->nomor->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_delete->RowCnt ?>_tjurnalkasbank_nomor" class="tjurnalkasbank_nomor">
<span<?php echo $tjurnalkasbank->nomor->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->nomor->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalkasbank->transaksi->Visible) { // transaksi ?>
		<td<?php echo $tjurnalkasbank->transaksi->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_delete->RowCnt ?>_tjurnalkasbank_transaksi" class="tjurnalkasbank_transaksi">
<span<?php echo $tjurnalkasbank->transaksi->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->transaksi->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalkasbank->referensi->Visible) { // referensi ?>
		<td<?php echo $tjurnalkasbank->referensi->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_delete->RowCnt ?>_tjurnalkasbank_referensi" class="tjurnalkasbank_referensi">
<span<?php echo $tjurnalkasbank->referensi->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->referensi->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalkasbank->model->Visible) { // model ?>
		<td<?php echo $tjurnalkasbank->model->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_delete->RowCnt ?>_tjurnalkasbank_model" class="tjurnalkasbank_model">
<span<?php echo $tjurnalkasbank->model->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->model->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalkasbank->rekening->Visible) { // rekening ?>
		<td<?php echo $tjurnalkasbank->rekening->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_delete->RowCnt ?>_tjurnalkasbank_rekening" class="tjurnalkasbank_rekening">
<span<?php echo $tjurnalkasbank->rekening->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->rekening->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalkasbank->debet->Visible) { // debet ?>
		<td<?php echo $tjurnalkasbank->debet->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_delete->RowCnt ?>_tjurnalkasbank_debet" class="tjurnalkasbank_debet">
<span<?php echo $tjurnalkasbank->debet->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->debet->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalkasbank->credit->Visible) { // credit ?>
		<td<?php echo $tjurnalkasbank->credit->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_delete->RowCnt ?>_tjurnalkasbank_credit" class="tjurnalkasbank_credit">
<span<?php echo $tjurnalkasbank->credit->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->credit->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalkasbank->kantor->Visible) { // kantor ?>
		<td<?php echo $tjurnalkasbank->kantor->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_delete->RowCnt ?>_tjurnalkasbank_kantor" class="tjurnalkasbank_kantor">
<span<?php echo $tjurnalkasbank->kantor->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->kantor->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalkasbank->keterangan->Visible) { // keterangan ?>
		<td<?php echo $tjurnalkasbank->keterangan->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_delete->RowCnt ?>_tjurnalkasbank_keterangan" class="tjurnalkasbank_keterangan">
<span<?php echo $tjurnalkasbank->keterangan->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->keterangan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalkasbank->active->Visible) { // active ?>
		<td<?php echo $tjurnalkasbank->active->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_delete->RowCnt ?>_tjurnalkasbank_active" class="tjurnalkasbank_active">
<span<?php echo $tjurnalkasbank->active->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->active->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalkasbank->ip->Visible) { // ip ?>
		<td<?php echo $tjurnalkasbank->ip->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_delete->RowCnt ?>_tjurnalkasbank_ip" class="tjurnalkasbank_ip">
<span<?php echo $tjurnalkasbank->ip->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->ip->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalkasbank->status->Visible) { // status ?>
		<td<?php echo $tjurnalkasbank->status->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_delete->RowCnt ?>_tjurnalkasbank_status" class="tjurnalkasbank_status">
<span<?php echo $tjurnalkasbank->status->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->status->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalkasbank->user->Visible) { // user ?>
		<td<?php echo $tjurnalkasbank->user->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_delete->RowCnt ?>_tjurnalkasbank_user" class="tjurnalkasbank_user">
<span<?php echo $tjurnalkasbank->user->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->user->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalkasbank->created->Visible) { // created ?>
		<td<?php echo $tjurnalkasbank->created->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_delete->RowCnt ?>_tjurnalkasbank_created" class="tjurnalkasbank_created">
<span<?php echo $tjurnalkasbank->created->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->created->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnalkasbank->modified->Visible) { // modified ?>
		<td<?php echo $tjurnalkasbank->modified->CellAttributes() ?>>
<span id="el<?php echo $tjurnalkasbank_delete->RowCnt ?>_tjurnalkasbank_modified" class="tjurnalkasbank_modified">
<span<?php echo $tjurnalkasbank->modified->ViewAttributes() ?>>
<?php echo $tjurnalkasbank->modified->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$tjurnalkasbank_delete->Recordset->MoveNext();
}
$tjurnalkasbank_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $tjurnalkasbank_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
ftjurnalkasbankdelete.Init();
</script>
<?php
$tjurnalkasbank_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tjurnalkasbank_delete->Page_Terminate();
?>
