<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "tjurnaltransaksiinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$tjurnaltransaksi_delete = NULL; // Initialize page object first

class ctjurnaltransaksi_delete extends ctjurnaltransaksi {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tjurnaltransaksi';

	// Page object name
	var $PageObjName = 'tjurnaltransaksi_delete';

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

		// Table object (tjurnaltransaksi)
		if (!isset($GLOBALS["tjurnaltransaksi"]) || get_class($GLOBALS["tjurnaltransaksi"]) == "ctjurnaltransaksi") {
			$GLOBALS["tjurnaltransaksi"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tjurnaltransaksi"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tjurnaltransaksi', TRUE);

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
		$this->pembayaran_->SetVisibility();
		$this->bunga_->SetVisibility();
		$this->denda_->SetVisibility();
		$this->titipan_->SetVisibility();
		$this->administrasi_->SetVisibility();
		$this->modal_->SetVisibility();
		$this->pinjaman_->SetVisibility();
		$this->biaya_->SetVisibility();
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
		global $EW_EXPORT, $tjurnaltransaksi;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($tjurnaltransaksi);
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
			$this->Page_Terminate("tjurnaltransaksilist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in tjurnaltransaksi class, tjurnaltransaksiinfo.php

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
				$this->Page_Terminate("tjurnaltransaksilist.php"); // Return to list
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
		$this->pembayaran_->setDbValue($rs->fields('pembayaran_'));
		$this->bunga_->setDbValue($rs->fields('bunga_'));
		$this->denda_->setDbValue($rs->fields('denda_'));
		$this->titipan_->setDbValue($rs->fields('titipan_'));
		$this->administrasi_->setDbValue($rs->fields('administrasi_'));
		$this->modal_->setDbValue($rs->fields('modal_'));
		$this->pinjaman_->setDbValue($rs->fields('pinjaman_'));
		$this->biaya_->setDbValue($rs->fields('biaya_'));
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
		$this->pembayaran_->DbValue = $row['pembayaran_'];
		$this->bunga_->DbValue = $row['bunga_'];
		$this->denda_->DbValue = $row['denda_'];
		$this->titipan_->DbValue = $row['titipan_'];
		$this->administrasi_->DbValue = $row['administrasi_'];
		$this->modal_->DbValue = $row['modal_'];
		$this->pinjaman_->DbValue = $row['pinjaman_'];
		$this->biaya_->DbValue = $row['biaya_'];
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

		// Convert decimal values if posted back
		if ($this->pembayaran_->FormValue == $this->pembayaran_->CurrentValue && is_numeric(ew_StrToFloat($this->pembayaran_->CurrentValue)))
			$this->pembayaran_->CurrentValue = ew_StrToFloat($this->pembayaran_->CurrentValue);

		// Convert decimal values if posted back
		if ($this->bunga_->FormValue == $this->bunga_->CurrentValue && is_numeric(ew_StrToFloat($this->bunga_->CurrentValue)))
			$this->bunga_->CurrentValue = ew_StrToFloat($this->bunga_->CurrentValue);

		// Convert decimal values if posted back
		if ($this->denda_->FormValue == $this->denda_->CurrentValue && is_numeric(ew_StrToFloat($this->denda_->CurrentValue)))
			$this->denda_->CurrentValue = ew_StrToFloat($this->denda_->CurrentValue);

		// Convert decimal values if posted back
		if ($this->titipan_->FormValue == $this->titipan_->CurrentValue && is_numeric(ew_StrToFloat($this->titipan_->CurrentValue)))
			$this->titipan_->CurrentValue = ew_StrToFloat($this->titipan_->CurrentValue);

		// Convert decimal values if posted back
		if ($this->administrasi_->FormValue == $this->administrasi_->CurrentValue && is_numeric(ew_StrToFloat($this->administrasi_->CurrentValue)))
			$this->administrasi_->CurrentValue = ew_StrToFloat($this->administrasi_->CurrentValue);

		// Convert decimal values if posted back
		if ($this->modal_->FormValue == $this->modal_->CurrentValue && is_numeric(ew_StrToFloat($this->modal_->CurrentValue)))
			$this->modal_->CurrentValue = ew_StrToFloat($this->modal_->CurrentValue);

		// Convert decimal values if posted back
		if ($this->pinjaman_->FormValue == $this->pinjaman_->CurrentValue && is_numeric(ew_StrToFloat($this->pinjaman_->CurrentValue)))
			$this->pinjaman_->CurrentValue = ew_StrToFloat($this->pinjaman_->CurrentValue);

		// Convert decimal values if posted back
		if ($this->biaya_->FormValue == $this->biaya_->CurrentValue && is_numeric(ew_StrToFloat($this->biaya_->CurrentValue)))
			$this->biaya_->CurrentValue = ew_StrToFloat($this->biaya_->CurrentValue);

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
		// pembayaran_
		// bunga_
		// denda_
		// titipan_
		// administrasi_
		// modal_
		// pinjaman_
		// biaya_
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

		// pembayaran_
		$this->pembayaran_->ViewValue = $this->pembayaran_->CurrentValue;
		$this->pembayaran_->ViewCustomAttributes = "";

		// bunga_
		$this->bunga_->ViewValue = $this->bunga_->CurrentValue;
		$this->bunga_->ViewCustomAttributes = "";

		// denda_
		$this->denda_->ViewValue = $this->denda_->CurrentValue;
		$this->denda_->ViewCustomAttributes = "";

		// titipan_
		$this->titipan_->ViewValue = $this->titipan_->CurrentValue;
		$this->titipan_->ViewCustomAttributes = "";

		// administrasi_
		$this->administrasi_->ViewValue = $this->administrasi_->CurrentValue;
		$this->administrasi_->ViewCustomAttributes = "";

		// modal_
		$this->modal_->ViewValue = $this->modal_->CurrentValue;
		$this->modal_->ViewCustomAttributes = "";

		// pinjaman_
		$this->pinjaman_->ViewValue = $this->pinjaman_->CurrentValue;
		$this->pinjaman_->ViewCustomAttributes = "";

		// biaya_
		$this->biaya_->ViewValue = $this->biaya_->CurrentValue;
		$this->biaya_->ViewCustomAttributes = "";

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

			// pembayaran_
			$this->pembayaran_->LinkCustomAttributes = "";
			$this->pembayaran_->HrefValue = "";
			$this->pembayaran_->TooltipValue = "";

			// bunga_
			$this->bunga_->LinkCustomAttributes = "";
			$this->bunga_->HrefValue = "";
			$this->bunga_->TooltipValue = "";

			// denda_
			$this->denda_->LinkCustomAttributes = "";
			$this->denda_->HrefValue = "";
			$this->denda_->TooltipValue = "";

			// titipan_
			$this->titipan_->LinkCustomAttributes = "";
			$this->titipan_->HrefValue = "";
			$this->titipan_->TooltipValue = "";

			// administrasi_
			$this->administrasi_->LinkCustomAttributes = "";
			$this->administrasi_->HrefValue = "";
			$this->administrasi_->TooltipValue = "";

			// modal_
			$this->modal_->LinkCustomAttributes = "";
			$this->modal_->HrefValue = "";
			$this->modal_->TooltipValue = "";

			// pinjaman_
			$this->pinjaman_->LinkCustomAttributes = "";
			$this->pinjaman_->HrefValue = "";
			$this->pinjaman_->TooltipValue = "";

			// biaya_
			$this->biaya_->LinkCustomAttributes = "";
			$this->biaya_->HrefValue = "";
			$this->biaya_->TooltipValue = "";

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("tjurnaltransaksilist.php"), "", $this->TableVar, TRUE);
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
if (!isset($tjurnaltransaksi_delete)) $tjurnaltransaksi_delete = new ctjurnaltransaksi_delete();

// Page init
$tjurnaltransaksi_delete->Page_Init();

// Page main
$tjurnaltransaksi_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tjurnaltransaksi_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = ftjurnaltransaksidelete = new ew_Form("ftjurnaltransaksidelete", "delete");

// Form_CustomValidate event
ftjurnaltransaksidelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftjurnaltransaksidelete.ValidateRequired = true;
<?php } else { ?>
ftjurnaltransaksidelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftjurnaltransaksidelete.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftjurnaltransaksidelete.Lists["x_active"].Options = <?php echo json_encode($tjurnaltransaksi->active->Options()) ?>;

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
<?php $tjurnaltransaksi_delete->ShowPageHeader(); ?>
<?php
$tjurnaltransaksi_delete->ShowMessage();
?>
<form name="ftjurnaltransaksidelete" id="ftjurnaltransaksidelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tjurnaltransaksi_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tjurnaltransaksi_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tjurnaltransaksi">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($tjurnaltransaksi_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $tjurnaltransaksi->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($tjurnaltransaksi->tanggal->Visible) { // tanggal ?>
		<th><span id="elh_tjurnaltransaksi_tanggal" class="tjurnaltransaksi_tanggal"><?php echo $tjurnaltransaksi->tanggal->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnaltransaksi->periode->Visible) { // periode ?>
		<th><span id="elh_tjurnaltransaksi_periode" class="tjurnaltransaksi_periode"><?php echo $tjurnaltransaksi->periode->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnaltransaksi->id->Visible) { // id ?>
		<th><span id="elh_tjurnaltransaksi_id" class="tjurnaltransaksi_id"><?php echo $tjurnaltransaksi->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnaltransaksi->nomor->Visible) { // nomor ?>
		<th><span id="elh_tjurnaltransaksi_nomor" class="tjurnaltransaksi_nomor"><?php echo $tjurnaltransaksi->nomor->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnaltransaksi->transaksi->Visible) { // transaksi ?>
		<th><span id="elh_tjurnaltransaksi_transaksi" class="tjurnaltransaksi_transaksi"><?php echo $tjurnaltransaksi->transaksi->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnaltransaksi->referensi->Visible) { // referensi ?>
		<th><span id="elh_tjurnaltransaksi_referensi" class="tjurnaltransaksi_referensi"><?php echo $tjurnaltransaksi->referensi->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnaltransaksi->model->Visible) { // model ?>
		<th><span id="elh_tjurnaltransaksi_model" class="tjurnaltransaksi_model"><?php echo $tjurnaltransaksi->model->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnaltransaksi->rekening->Visible) { // rekening ?>
		<th><span id="elh_tjurnaltransaksi_rekening" class="tjurnaltransaksi_rekening"><?php echo $tjurnaltransaksi->rekening->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnaltransaksi->debet->Visible) { // debet ?>
		<th><span id="elh_tjurnaltransaksi_debet" class="tjurnaltransaksi_debet"><?php echo $tjurnaltransaksi->debet->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnaltransaksi->credit->Visible) { // credit ?>
		<th><span id="elh_tjurnaltransaksi_credit" class="tjurnaltransaksi_credit"><?php echo $tjurnaltransaksi->credit->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnaltransaksi->pembayaran_->Visible) { // pembayaran_ ?>
		<th><span id="elh_tjurnaltransaksi_pembayaran_" class="tjurnaltransaksi_pembayaran_"><?php echo $tjurnaltransaksi->pembayaran_->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnaltransaksi->bunga_->Visible) { // bunga_ ?>
		<th><span id="elh_tjurnaltransaksi_bunga_" class="tjurnaltransaksi_bunga_"><?php echo $tjurnaltransaksi->bunga_->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnaltransaksi->denda_->Visible) { // denda_ ?>
		<th><span id="elh_tjurnaltransaksi_denda_" class="tjurnaltransaksi_denda_"><?php echo $tjurnaltransaksi->denda_->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnaltransaksi->titipan_->Visible) { // titipan_ ?>
		<th><span id="elh_tjurnaltransaksi_titipan_" class="tjurnaltransaksi_titipan_"><?php echo $tjurnaltransaksi->titipan_->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnaltransaksi->administrasi_->Visible) { // administrasi_ ?>
		<th><span id="elh_tjurnaltransaksi_administrasi_" class="tjurnaltransaksi_administrasi_"><?php echo $tjurnaltransaksi->administrasi_->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnaltransaksi->modal_->Visible) { // modal_ ?>
		<th><span id="elh_tjurnaltransaksi_modal_" class="tjurnaltransaksi_modal_"><?php echo $tjurnaltransaksi->modal_->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnaltransaksi->pinjaman_->Visible) { // pinjaman_ ?>
		<th><span id="elh_tjurnaltransaksi_pinjaman_" class="tjurnaltransaksi_pinjaman_"><?php echo $tjurnaltransaksi->pinjaman_->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnaltransaksi->biaya_->Visible) { // biaya_ ?>
		<th><span id="elh_tjurnaltransaksi_biaya_" class="tjurnaltransaksi_biaya_"><?php echo $tjurnaltransaksi->biaya_->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnaltransaksi->kantor->Visible) { // kantor ?>
		<th><span id="elh_tjurnaltransaksi_kantor" class="tjurnaltransaksi_kantor"><?php echo $tjurnaltransaksi->kantor->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnaltransaksi->keterangan->Visible) { // keterangan ?>
		<th><span id="elh_tjurnaltransaksi_keterangan" class="tjurnaltransaksi_keterangan"><?php echo $tjurnaltransaksi->keterangan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnaltransaksi->active->Visible) { // active ?>
		<th><span id="elh_tjurnaltransaksi_active" class="tjurnaltransaksi_active"><?php echo $tjurnaltransaksi->active->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnaltransaksi->ip->Visible) { // ip ?>
		<th><span id="elh_tjurnaltransaksi_ip" class="tjurnaltransaksi_ip"><?php echo $tjurnaltransaksi->ip->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnaltransaksi->status->Visible) { // status ?>
		<th><span id="elh_tjurnaltransaksi_status" class="tjurnaltransaksi_status"><?php echo $tjurnaltransaksi->status->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnaltransaksi->user->Visible) { // user ?>
		<th><span id="elh_tjurnaltransaksi_user" class="tjurnaltransaksi_user"><?php echo $tjurnaltransaksi->user->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnaltransaksi->created->Visible) { // created ?>
		<th><span id="elh_tjurnaltransaksi_created" class="tjurnaltransaksi_created"><?php echo $tjurnaltransaksi->created->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tjurnaltransaksi->modified->Visible) { // modified ?>
		<th><span id="elh_tjurnaltransaksi_modified" class="tjurnaltransaksi_modified"><?php echo $tjurnaltransaksi->modified->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$tjurnaltransaksi_delete->RecCnt = 0;
$i = 0;
while (!$tjurnaltransaksi_delete->Recordset->EOF) {
	$tjurnaltransaksi_delete->RecCnt++;
	$tjurnaltransaksi_delete->RowCnt++;

	// Set row properties
	$tjurnaltransaksi->ResetAttrs();
	$tjurnaltransaksi->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$tjurnaltransaksi_delete->LoadRowValues($tjurnaltransaksi_delete->Recordset);

	// Render row
	$tjurnaltransaksi_delete->RenderRow();
?>
	<tr<?php echo $tjurnaltransaksi->RowAttributes() ?>>
<?php if ($tjurnaltransaksi->tanggal->Visible) { // tanggal ?>
		<td<?php echo $tjurnaltransaksi->tanggal->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_delete->RowCnt ?>_tjurnaltransaksi_tanggal" class="tjurnaltransaksi_tanggal">
<span<?php echo $tjurnaltransaksi->tanggal->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->tanggal->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnaltransaksi->periode->Visible) { // periode ?>
		<td<?php echo $tjurnaltransaksi->periode->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_delete->RowCnt ?>_tjurnaltransaksi_periode" class="tjurnaltransaksi_periode">
<span<?php echo $tjurnaltransaksi->periode->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->periode->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnaltransaksi->id->Visible) { // id ?>
		<td<?php echo $tjurnaltransaksi->id->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_delete->RowCnt ?>_tjurnaltransaksi_id" class="tjurnaltransaksi_id">
<span<?php echo $tjurnaltransaksi->id->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnaltransaksi->nomor->Visible) { // nomor ?>
		<td<?php echo $tjurnaltransaksi->nomor->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_delete->RowCnt ?>_tjurnaltransaksi_nomor" class="tjurnaltransaksi_nomor">
<span<?php echo $tjurnaltransaksi->nomor->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->nomor->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnaltransaksi->transaksi->Visible) { // transaksi ?>
		<td<?php echo $tjurnaltransaksi->transaksi->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_delete->RowCnt ?>_tjurnaltransaksi_transaksi" class="tjurnaltransaksi_transaksi">
<span<?php echo $tjurnaltransaksi->transaksi->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->transaksi->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnaltransaksi->referensi->Visible) { // referensi ?>
		<td<?php echo $tjurnaltransaksi->referensi->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_delete->RowCnt ?>_tjurnaltransaksi_referensi" class="tjurnaltransaksi_referensi">
<span<?php echo $tjurnaltransaksi->referensi->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->referensi->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnaltransaksi->model->Visible) { // model ?>
		<td<?php echo $tjurnaltransaksi->model->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_delete->RowCnt ?>_tjurnaltransaksi_model" class="tjurnaltransaksi_model">
<span<?php echo $tjurnaltransaksi->model->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->model->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnaltransaksi->rekening->Visible) { // rekening ?>
		<td<?php echo $tjurnaltransaksi->rekening->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_delete->RowCnt ?>_tjurnaltransaksi_rekening" class="tjurnaltransaksi_rekening">
<span<?php echo $tjurnaltransaksi->rekening->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->rekening->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnaltransaksi->debet->Visible) { // debet ?>
		<td<?php echo $tjurnaltransaksi->debet->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_delete->RowCnt ?>_tjurnaltransaksi_debet" class="tjurnaltransaksi_debet">
<span<?php echo $tjurnaltransaksi->debet->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->debet->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnaltransaksi->credit->Visible) { // credit ?>
		<td<?php echo $tjurnaltransaksi->credit->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_delete->RowCnt ?>_tjurnaltransaksi_credit" class="tjurnaltransaksi_credit">
<span<?php echo $tjurnaltransaksi->credit->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->credit->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnaltransaksi->pembayaran_->Visible) { // pembayaran_ ?>
		<td<?php echo $tjurnaltransaksi->pembayaran_->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_delete->RowCnt ?>_tjurnaltransaksi_pembayaran_" class="tjurnaltransaksi_pembayaran_">
<span<?php echo $tjurnaltransaksi->pembayaran_->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->pembayaran_->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnaltransaksi->bunga_->Visible) { // bunga_ ?>
		<td<?php echo $tjurnaltransaksi->bunga_->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_delete->RowCnt ?>_tjurnaltransaksi_bunga_" class="tjurnaltransaksi_bunga_">
<span<?php echo $tjurnaltransaksi->bunga_->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->bunga_->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnaltransaksi->denda_->Visible) { // denda_ ?>
		<td<?php echo $tjurnaltransaksi->denda_->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_delete->RowCnt ?>_tjurnaltransaksi_denda_" class="tjurnaltransaksi_denda_">
<span<?php echo $tjurnaltransaksi->denda_->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->denda_->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnaltransaksi->titipan_->Visible) { // titipan_ ?>
		<td<?php echo $tjurnaltransaksi->titipan_->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_delete->RowCnt ?>_tjurnaltransaksi_titipan_" class="tjurnaltransaksi_titipan_">
<span<?php echo $tjurnaltransaksi->titipan_->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->titipan_->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnaltransaksi->administrasi_->Visible) { // administrasi_ ?>
		<td<?php echo $tjurnaltransaksi->administrasi_->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_delete->RowCnt ?>_tjurnaltransaksi_administrasi_" class="tjurnaltransaksi_administrasi_">
<span<?php echo $tjurnaltransaksi->administrasi_->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->administrasi_->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnaltransaksi->modal_->Visible) { // modal_ ?>
		<td<?php echo $tjurnaltransaksi->modal_->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_delete->RowCnt ?>_tjurnaltransaksi_modal_" class="tjurnaltransaksi_modal_">
<span<?php echo $tjurnaltransaksi->modal_->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->modal_->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnaltransaksi->pinjaman_->Visible) { // pinjaman_ ?>
		<td<?php echo $tjurnaltransaksi->pinjaman_->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_delete->RowCnt ?>_tjurnaltransaksi_pinjaman_" class="tjurnaltransaksi_pinjaman_">
<span<?php echo $tjurnaltransaksi->pinjaman_->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->pinjaman_->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnaltransaksi->biaya_->Visible) { // biaya_ ?>
		<td<?php echo $tjurnaltransaksi->biaya_->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_delete->RowCnt ?>_tjurnaltransaksi_biaya_" class="tjurnaltransaksi_biaya_">
<span<?php echo $tjurnaltransaksi->biaya_->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->biaya_->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnaltransaksi->kantor->Visible) { // kantor ?>
		<td<?php echo $tjurnaltransaksi->kantor->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_delete->RowCnt ?>_tjurnaltransaksi_kantor" class="tjurnaltransaksi_kantor">
<span<?php echo $tjurnaltransaksi->kantor->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->kantor->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnaltransaksi->keterangan->Visible) { // keterangan ?>
		<td<?php echo $tjurnaltransaksi->keterangan->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_delete->RowCnt ?>_tjurnaltransaksi_keterangan" class="tjurnaltransaksi_keterangan">
<span<?php echo $tjurnaltransaksi->keterangan->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->keterangan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnaltransaksi->active->Visible) { // active ?>
		<td<?php echo $tjurnaltransaksi->active->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_delete->RowCnt ?>_tjurnaltransaksi_active" class="tjurnaltransaksi_active">
<span<?php echo $tjurnaltransaksi->active->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->active->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnaltransaksi->ip->Visible) { // ip ?>
		<td<?php echo $tjurnaltransaksi->ip->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_delete->RowCnt ?>_tjurnaltransaksi_ip" class="tjurnaltransaksi_ip">
<span<?php echo $tjurnaltransaksi->ip->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->ip->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnaltransaksi->status->Visible) { // status ?>
		<td<?php echo $tjurnaltransaksi->status->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_delete->RowCnt ?>_tjurnaltransaksi_status" class="tjurnaltransaksi_status">
<span<?php echo $tjurnaltransaksi->status->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->status->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnaltransaksi->user->Visible) { // user ?>
		<td<?php echo $tjurnaltransaksi->user->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_delete->RowCnt ?>_tjurnaltransaksi_user" class="tjurnaltransaksi_user">
<span<?php echo $tjurnaltransaksi->user->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->user->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnaltransaksi->created->Visible) { // created ?>
		<td<?php echo $tjurnaltransaksi->created->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_delete->RowCnt ?>_tjurnaltransaksi_created" class="tjurnaltransaksi_created">
<span<?php echo $tjurnaltransaksi->created->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->created->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tjurnaltransaksi->modified->Visible) { // modified ?>
		<td<?php echo $tjurnaltransaksi->modified->CellAttributes() ?>>
<span id="el<?php echo $tjurnaltransaksi_delete->RowCnt ?>_tjurnaltransaksi_modified" class="tjurnaltransaksi_modified">
<span<?php echo $tjurnaltransaksi->modified->ViewAttributes() ?>>
<?php echo $tjurnaltransaksi->modified->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$tjurnaltransaksi_delete->Recordset->MoveNext();
}
$tjurnaltransaksi_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $tjurnaltransaksi_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
ftjurnaltransaksidelete.Init();
</script>
<?php
$tjurnaltransaksi_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tjurnaltransaksi_delete->Page_Terminate();
?>
