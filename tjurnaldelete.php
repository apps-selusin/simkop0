<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "tjurnalinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$tjurnal_delete = NULL; // Initialize page object first

class ctjurnal_delete extends ctjurnal {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tjurnal';

	// Page object name
	var $PageObjName = 'tjurnal_delete';

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

		// Table object (tjurnal)
		if (!isset($GLOBALS["tjurnal"]) || get_class($GLOBALS["tjurnal"]) == "ctjurnal") {
			$GLOBALS["tjurnal"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tjurnal"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tjurnal', TRUE);

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
		$this->tipe->SetVisibility();
		$this->posisi->SetVisibility();
		$this->laporan->SetVisibility();
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
		global $EW_EXPORT, $tjurnal;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($tjurnal);
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
			$this->Page_Terminate("tjurnallist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in tjurnal class, tjurnalinfo.php

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
				$this->Page_Terminate("tjurnallist.php"); // Return to list
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
		$this->tipe->setDbValue($rs->fields('tipe'));
		$this->posisi->setDbValue($rs->fields('posisi'));
		$this->laporan->setDbValue($rs->fields('laporan'));
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
		$this->tipe->DbValue = $row['tipe'];
		$this->posisi->DbValue = $row['posisi'];
		$this->laporan->DbValue = $row['laporan'];
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
		// tipe
		// posisi
		// laporan
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

		// tipe
		$this->tipe->ViewValue = $this->tipe->CurrentValue;
		$this->tipe->ViewCustomAttributes = "";

		// posisi
		$this->posisi->ViewValue = $this->posisi->CurrentValue;
		$this->posisi->ViewCustomAttributes = "";

		// laporan
		$this->laporan->ViewValue = $this->laporan->CurrentValue;
		$this->laporan->ViewCustomAttributes = "";

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("tjurnallist.php"), "", $this->TableVar, TRUE);
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
if (!isset($tjurnal_delete)) $tjurnal_delete = new ctjurnal_delete();

// Page init
$tjurnal_delete->Page_Init();

// Page main
$tjurnal_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tjurnal_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = ftjurnaldelete = new ew_Form("ftjurnaldelete", "delete");

// Form_CustomValidate event
ftjurnaldelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftjurnaldelete.ValidateRequired = true;
<?php } else { ?>
ftjurnaldelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftjurnaldelete.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftjurnaldelete.Lists["x_active"].Options = <?php echo json_encode($tjurnal->active->Options()) ?>;

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
<?php $tjurnal_delete->ShowPageHeader(); ?>
<?php
$tjurnal_delete->ShowMessage();
?>
<form name="ftjurnaldelete" id="ftjurnaldelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tjurnal_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tjurnal_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tjurnal">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($tjurnal_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $tjurnal->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($tjurnal->tanggal->Visible) { // tanggal ?>
		<th><span id="elh_tjurnal_tanggal" class="tjurnal_tanggal"><?php echo $tjurnal->tanggal->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnal->periode->Visible) { // periode ?>
		<th><span id="elh_tjurnal_periode" class="tjurnal_periode"><?php echo $tjurnal->periode->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnal->id->Visible) { // id ?>
		<th><span id="elh_tjurnal_id" class="tjurnal_id"><?php echo $tjurnal->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnal->nomor->Visible) { // nomor ?>
		<th><span id="elh_tjurnal_nomor" class="tjurnal_nomor"><?php echo $tjurnal->nomor->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnal->transaksi->Visible) { // transaksi ?>
		<th><span id="elh_tjurnal_transaksi" class="tjurnal_transaksi"><?php echo $tjurnal->transaksi->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnal->referensi->Visible) { // referensi ?>
		<th><span id="elh_tjurnal_referensi" class="tjurnal_referensi"><?php echo $tjurnal->referensi->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnal->model->Visible) { // model ?>
		<th><span id="elh_tjurnal_model" class="tjurnal_model"><?php echo $tjurnal->model->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnal->rekening->Visible) { // rekening ?>
		<th><span id="elh_tjurnal_rekening" class="tjurnal_rekening"><?php echo $tjurnal->rekening->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnal->tipe->Visible) { // tipe ?>
		<th><span id="elh_tjurnal_tipe" class="tjurnal_tipe"><?php echo $tjurnal->tipe->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnal->posisi->Visible) { // posisi ?>
		<th><span id="elh_tjurnal_posisi" class="tjurnal_posisi"><?php echo $tjurnal->posisi->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnal->laporan->Visible) { // laporan ?>
		<th><span id="elh_tjurnal_laporan" class="tjurnal_laporan"><?php echo $tjurnal->laporan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnal->debet->Visible) { // debet ?>
		<th><span id="elh_tjurnal_debet" class="tjurnal_debet"><?php echo $tjurnal->debet->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnal->credit->Visible) { // credit ?>
		<th><span id="elh_tjurnal_credit" class="tjurnal_credit"><?php echo $tjurnal->credit->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnal->kantor->Visible) { // kantor ?>
		<th><span id="elh_tjurnal_kantor" class="tjurnal_kantor"><?php echo $tjurnal->kantor->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnal->keterangan->Visible) { // keterangan ?>
		<th><span id="elh_tjurnal_keterangan" class="tjurnal_keterangan"><?php echo $tjurnal->keterangan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnal->active->Visible) { // active ?>
		<th><span id="elh_tjurnal_active" class="tjurnal_active"><?php echo $tjurnal->active->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnal->ip->Visible) { // ip ?>
		<th><span id="elh_tjurnal_ip" class="tjurnal_ip"><?php echo $tjurnal->ip->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnal->status->Visible) { // status ?>
		<th><span id="elh_tjurnal_status" class="tjurnal_status"><?php echo $tjurnal->status->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnal->user->Visible) { // user ?>
		<th><span id="elh_tjurnal_user" class="tjurnal_user"><?php echo $tjurnal->user->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnal->created->Visible) { // created ?>
		<th><span id="elh_tjurnal_created" class="tjurnal_created"><?php echo $tjurnal->created->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnal->modified->Visible) { // modified ?>
		<th><span id="elh_tjurnal_modified" class="tjurnal_modified"><?php echo $tjurnal->modified->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$tjurnal_delete->RecCnt = 0;
$i = 0;
while (!$tjurnal_delete->Recordset->EOF) {
	$tjurnal_delete->RecCnt++;
	$tjurnal_delete->RowCnt++;

	// Set row properties
	$tjurnal->ResetAttrs();
	$tjurnal->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$tjurnal_delete->LoadRowValues($tjurnal_delete->Recordset);

	// Render row
	$tjurnal_delete->RenderRow();
?>
	<tr<?php echo $tjurnal->RowAttributes() ?>>
<?php if ($tjurnal->tanggal->Visible) { // tanggal ?>
		<td<?php echo $tjurnal->tanggal->CellAttributes() ?>>
<span id="el<?php echo $tjurnal_delete->RowCnt ?>_tjurnal_tanggal" class="tjurnal_tanggal">
<span<?php echo $tjurnal->tanggal->ViewAttributes() ?>>
<?php echo $tjurnal->tanggal->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnal->periode->Visible) { // periode ?>
		<td<?php echo $tjurnal->periode->CellAttributes() ?>>
<span id="el<?php echo $tjurnal_delete->RowCnt ?>_tjurnal_periode" class="tjurnal_periode">
<span<?php echo $tjurnal->periode->ViewAttributes() ?>>
<?php echo $tjurnal->periode->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnal->id->Visible) { // id ?>
		<td<?php echo $tjurnal->id->CellAttributes() ?>>
<span id="el<?php echo $tjurnal_delete->RowCnt ?>_tjurnal_id" class="tjurnal_id">
<span<?php echo $tjurnal->id->ViewAttributes() ?>>
<?php echo $tjurnal->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnal->nomor->Visible) { // nomor ?>
		<td<?php echo $tjurnal->nomor->CellAttributes() ?>>
<span id="el<?php echo $tjurnal_delete->RowCnt ?>_tjurnal_nomor" class="tjurnal_nomor">
<span<?php echo $tjurnal->nomor->ViewAttributes() ?>>
<?php echo $tjurnal->nomor->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnal->transaksi->Visible) { // transaksi ?>
		<td<?php echo $tjurnal->transaksi->CellAttributes() ?>>
<span id="el<?php echo $tjurnal_delete->RowCnt ?>_tjurnal_transaksi" class="tjurnal_transaksi">
<span<?php echo $tjurnal->transaksi->ViewAttributes() ?>>
<?php echo $tjurnal->transaksi->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnal->referensi->Visible) { // referensi ?>
		<td<?php echo $tjurnal->referensi->CellAttributes() ?>>
<span id="el<?php echo $tjurnal_delete->RowCnt ?>_tjurnal_referensi" class="tjurnal_referensi">
<span<?php echo $tjurnal->referensi->ViewAttributes() ?>>
<?php echo $tjurnal->referensi->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnal->model->Visible) { // model ?>
		<td<?php echo $tjurnal->model->CellAttributes() ?>>
<span id="el<?php echo $tjurnal_delete->RowCnt ?>_tjurnal_model" class="tjurnal_model">
<span<?php echo $tjurnal->model->ViewAttributes() ?>>
<?php echo $tjurnal->model->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnal->rekening->Visible) { // rekening ?>
		<td<?php echo $tjurnal->rekening->CellAttributes() ?>>
<span id="el<?php echo $tjurnal_delete->RowCnt ?>_tjurnal_rekening" class="tjurnal_rekening">
<span<?php echo $tjurnal->rekening->ViewAttributes() ?>>
<?php echo $tjurnal->rekening->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnal->tipe->Visible) { // tipe ?>
		<td<?php echo $tjurnal->tipe->CellAttributes() ?>>
<span id="el<?php echo $tjurnal_delete->RowCnt ?>_tjurnal_tipe" class="tjurnal_tipe">
<span<?php echo $tjurnal->tipe->ViewAttributes() ?>>
<?php echo $tjurnal->tipe->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnal->posisi->Visible) { // posisi ?>
		<td<?php echo $tjurnal->posisi->CellAttributes() ?>>
<span id="el<?php echo $tjurnal_delete->RowCnt ?>_tjurnal_posisi" class="tjurnal_posisi">
<span<?php echo $tjurnal->posisi->ViewAttributes() ?>>
<?php echo $tjurnal->posisi->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnal->laporan->Visible) { // laporan ?>
		<td<?php echo $tjurnal->laporan->CellAttributes() ?>>
<span id="el<?php echo $tjurnal_delete->RowCnt ?>_tjurnal_laporan" class="tjurnal_laporan">
<span<?php echo $tjurnal->laporan->ViewAttributes() ?>>
<?php echo $tjurnal->laporan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnal->debet->Visible) { // debet ?>
		<td<?php echo $tjurnal->debet->CellAttributes() ?>>
<span id="el<?php echo $tjurnal_delete->RowCnt ?>_tjurnal_debet" class="tjurnal_debet">
<span<?php echo $tjurnal->debet->ViewAttributes() ?>>
<?php echo $tjurnal->debet->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnal->credit->Visible) { // credit ?>
		<td<?php echo $tjurnal->credit->CellAttributes() ?>>
<span id="el<?php echo $tjurnal_delete->RowCnt ?>_tjurnal_credit" class="tjurnal_credit">
<span<?php echo $tjurnal->credit->ViewAttributes() ?>>
<?php echo $tjurnal->credit->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnal->kantor->Visible) { // kantor ?>
		<td<?php echo $tjurnal->kantor->CellAttributes() ?>>
<span id="el<?php echo $tjurnal_delete->RowCnt ?>_tjurnal_kantor" class="tjurnal_kantor">
<span<?php echo $tjurnal->kantor->ViewAttributes() ?>>
<?php echo $tjurnal->kantor->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnal->keterangan->Visible) { // keterangan ?>
		<td<?php echo $tjurnal->keterangan->CellAttributes() ?>>
<span id="el<?php echo $tjurnal_delete->RowCnt ?>_tjurnal_keterangan" class="tjurnal_keterangan">
<span<?php echo $tjurnal->keterangan->ViewAttributes() ?>>
<?php echo $tjurnal->keterangan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnal->active->Visible) { // active ?>
		<td<?php echo $tjurnal->active->CellAttributes() ?>>
<span id="el<?php echo $tjurnal_delete->RowCnt ?>_tjurnal_active" class="tjurnal_active">
<span<?php echo $tjurnal->active->ViewAttributes() ?>>
<?php echo $tjurnal->active->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnal->ip->Visible) { // ip ?>
		<td<?php echo $tjurnal->ip->CellAttributes() ?>>
<span id="el<?php echo $tjurnal_delete->RowCnt ?>_tjurnal_ip" class="tjurnal_ip">
<span<?php echo $tjurnal->ip->ViewAttributes() ?>>
<?php echo $tjurnal->ip->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnal->status->Visible) { // status ?>
		<td<?php echo $tjurnal->status->CellAttributes() ?>>
<span id="el<?php echo $tjurnal_delete->RowCnt ?>_tjurnal_status" class="tjurnal_status">
<span<?php echo $tjurnal->status->ViewAttributes() ?>>
<?php echo $tjurnal->status->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnal->user->Visible) { // user ?>
		<td<?php echo $tjurnal->user->CellAttributes() ?>>
<span id="el<?php echo $tjurnal_delete->RowCnt ?>_tjurnal_user" class="tjurnal_user">
<span<?php echo $tjurnal->user->ViewAttributes() ?>>
<?php echo $tjurnal->user->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnal->created->Visible) { // created ?>
		<td<?php echo $tjurnal->created->CellAttributes() ?>>
<span id="el<?php echo $tjurnal_delete->RowCnt ?>_tjurnal_created" class="tjurnal_created">
<span<?php echo $tjurnal->created->ViewAttributes() ?>>
<?php echo $tjurnal->created->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnal->modified->Visible) { // modified ?>
		<td<?php echo $tjurnal->modified->CellAttributes() ?>>
<span id="el<?php echo $tjurnal_delete->RowCnt ?>_tjurnal_modified" class="tjurnal_modified">
<span<?php echo $tjurnal->modified->ViewAttributes() ?>>
<?php echo $tjurnal->modified->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$tjurnal_delete->Recordset->MoveNext();
}
$tjurnal_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $tjurnal_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
ftjurnaldelete.Init();
</script>
<?php
$tjurnal_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tjurnal_delete->Page_Terminate();
?>
