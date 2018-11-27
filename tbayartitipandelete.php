<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "tbayartitipaninfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$tbayartitipan_delete = NULL; // Initialize page object first

class ctbayartitipan_delete extends ctbayartitipan {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tbayartitipan';

	// Page object name
	var $PageObjName = 'tbayartitipan_delete';

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

		// Table object (tbayartitipan)
		if (!isset($GLOBALS["tbayartitipan"]) || get_class($GLOBALS["tbayartitipan"]) == "ctbayartitipan") {
			$GLOBALS["tbayartitipan"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tbayartitipan"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tbayartitipan', TRUE);

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
		$this->transaksi->SetVisibility();
		$this->referensi->SetVisibility();
		$this->anggota->SetVisibility();
		$this->namaanggota->SetVisibility();
		$this->alamat->SetVisibility();
		$this->pekerjaan->SetVisibility();
		$this->telepon->SetVisibility();
		$this->hp->SetVisibility();
		$this->fax->SetVisibility();
		$this->_email->SetVisibility();
		$this->website->SetVisibility();
		$this->jenisanggota->SetVisibility();
		$this->model->SetVisibility();
		$this->jenispinjaman->SetVisibility();
		$this->jenisbunga->SetVisibility();
		$this->angsuran->SetVisibility();
		$this->masaangsuran->SetVisibility();
		$this->jatuhtempo->SetVisibility();
		$this->dispensasidenda->SetVisibility();
		$this->titipan->SetVisibility();
		$this->bayartitipan->SetVisibility();
		$this->bayartitipanauto->SetVisibility();
		$this->terbilang->SetVisibility();
		$this->petugas->SetVisibility();
		$this->pembayaran->SetVisibility();
		$this->bank->SetVisibility();
		$this->atasnama->SetVisibility();
		$this->tipe->SetVisibility();
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
		global $EW_EXPORT, $tbayartitipan;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($tbayartitipan);
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
			$this->Page_Terminate("tbayartitipanlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in tbayartitipan class, tbayartitipaninfo.php

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
				$this->Page_Terminate("tbayartitipanlist.php"); // Return to list
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
		$this->transaksi->setDbValue($rs->fields('transaksi'));
		$this->referensi->setDbValue($rs->fields('referensi'));
		$this->anggota->setDbValue($rs->fields('anggota'));
		$this->namaanggota->setDbValue($rs->fields('namaanggota'));
		$this->alamat->setDbValue($rs->fields('alamat'));
		$this->pekerjaan->setDbValue($rs->fields('pekerjaan'));
		$this->telepon->setDbValue($rs->fields('telepon'));
		$this->hp->setDbValue($rs->fields('hp'));
		$this->fax->setDbValue($rs->fields('fax'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->website->setDbValue($rs->fields('website'));
		$this->jenisanggota->setDbValue($rs->fields('jenisanggota'));
		$this->model->setDbValue($rs->fields('model'));
		$this->jenispinjaman->setDbValue($rs->fields('jenispinjaman'));
		$this->jenisbunga->setDbValue($rs->fields('jenisbunga'));
		$this->angsuran->setDbValue($rs->fields('angsuran'));
		$this->masaangsuran->setDbValue($rs->fields('masaangsuran'));
		$this->jatuhtempo->setDbValue($rs->fields('jatuhtempo'));
		$this->dispensasidenda->setDbValue($rs->fields('dispensasidenda'));
		$this->titipan->setDbValue($rs->fields('titipan'));
		$this->bayartitipan->setDbValue($rs->fields('bayartitipan'));
		$this->bayartitipanauto->setDbValue($rs->fields('bayartitipanauto'));
		$this->terbilang->setDbValue($rs->fields('terbilang'));
		$this->petugas->setDbValue($rs->fields('petugas'));
		$this->pembayaran->setDbValue($rs->fields('pembayaran'));
		$this->bank->setDbValue($rs->fields('bank'));
		$this->atasnama->setDbValue($rs->fields('atasnama'));
		$this->tipe->setDbValue($rs->fields('tipe'));
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
		$this->transaksi->DbValue = $row['transaksi'];
		$this->referensi->DbValue = $row['referensi'];
		$this->anggota->DbValue = $row['anggota'];
		$this->namaanggota->DbValue = $row['namaanggota'];
		$this->alamat->DbValue = $row['alamat'];
		$this->pekerjaan->DbValue = $row['pekerjaan'];
		$this->telepon->DbValue = $row['telepon'];
		$this->hp->DbValue = $row['hp'];
		$this->fax->DbValue = $row['fax'];
		$this->_email->DbValue = $row['email'];
		$this->website->DbValue = $row['website'];
		$this->jenisanggota->DbValue = $row['jenisanggota'];
		$this->model->DbValue = $row['model'];
		$this->jenispinjaman->DbValue = $row['jenispinjaman'];
		$this->jenisbunga->DbValue = $row['jenisbunga'];
		$this->angsuran->DbValue = $row['angsuran'];
		$this->masaangsuran->DbValue = $row['masaangsuran'];
		$this->jatuhtempo->DbValue = $row['jatuhtempo'];
		$this->dispensasidenda->DbValue = $row['dispensasidenda'];
		$this->titipan->DbValue = $row['titipan'];
		$this->bayartitipan->DbValue = $row['bayartitipan'];
		$this->bayartitipanauto->DbValue = $row['bayartitipanauto'];
		$this->terbilang->DbValue = $row['terbilang'];
		$this->petugas->DbValue = $row['petugas'];
		$this->pembayaran->DbValue = $row['pembayaran'];
		$this->bank->DbValue = $row['bank'];
		$this->atasnama->DbValue = $row['atasnama'];
		$this->tipe->DbValue = $row['tipe'];
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

		if ($this->bayartitipan->FormValue == $this->bayartitipan->CurrentValue && is_numeric(ew_StrToFloat($this->bayartitipan->CurrentValue)))
			$this->bayartitipan->CurrentValue = ew_StrToFloat($this->bayartitipan->CurrentValue);

		// Convert decimal values if posted back
		if ($this->bayartitipanauto->FormValue == $this->bayartitipanauto->CurrentValue && is_numeric(ew_StrToFloat($this->bayartitipanauto->CurrentValue)))
			$this->bayartitipanauto->CurrentValue = ew_StrToFloat($this->bayartitipanauto->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// tanggal
		// periode
		// id
		// transaksi
		// referensi
		// anggota
		// namaanggota
		// alamat
		// pekerjaan
		// telepon
		// hp
		// fax
		// email
		// website
		// jenisanggota
		// model
		// jenispinjaman
		// jenisbunga
		// angsuran
		// masaangsuran
		// jatuhtempo
		// dispensasidenda
		// titipan
		// bayartitipan
		// bayartitipanauto
		// terbilang
		// petugas
		// pembayaran
		// bank
		// atasnama
		// tipe
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

		// transaksi
		$this->transaksi->ViewValue = $this->transaksi->CurrentValue;
		$this->transaksi->ViewCustomAttributes = "";

		// referensi
		$this->referensi->ViewValue = $this->referensi->CurrentValue;
		$this->referensi->ViewCustomAttributes = "";

		// anggota
		$this->anggota->ViewValue = $this->anggota->CurrentValue;
		$this->anggota->ViewCustomAttributes = "";

		// namaanggota
		$this->namaanggota->ViewValue = $this->namaanggota->CurrentValue;
		$this->namaanggota->ViewCustomAttributes = "";

		// alamat
		$this->alamat->ViewValue = $this->alamat->CurrentValue;
		$this->alamat->ViewCustomAttributes = "";

		// pekerjaan
		$this->pekerjaan->ViewValue = $this->pekerjaan->CurrentValue;
		$this->pekerjaan->ViewCustomAttributes = "";

		// telepon
		$this->telepon->ViewValue = $this->telepon->CurrentValue;
		$this->telepon->ViewCustomAttributes = "";

		// hp
		$this->hp->ViewValue = $this->hp->CurrentValue;
		$this->hp->ViewCustomAttributes = "";

		// fax
		$this->fax->ViewValue = $this->fax->CurrentValue;
		$this->fax->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// website
		$this->website->ViewValue = $this->website->CurrentValue;
		$this->website->ViewCustomAttributes = "";

		// jenisanggota
		$this->jenisanggota->ViewValue = $this->jenisanggota->CurrentValue;
		$this->jenisanggota->ViewCustomAttributes = "";

		// model
		$this->model->ViewValue = $this->model->CurrentValue;
		$this->model->ViewCustomAttributes = "";

		// jenispinjaman
		$this->jenispinjaman->ViewValue = $this->jenispinjaman->CurrentValue;
		$this->jenispinjaman->ViewCustomAttributes = "";

		// jenisbunga
		$this->jenisbunga->ViewValue = $this->jenisbunga->CurrentValue;
		$this->jenisbunga->ViewCustomAttributes = "";

		// angsuran
		$this->angsuran->ViewValue = $this->angsuran->CurrentValue;
		$this->angsuran->ViewCustomAttributes = "";

		// masaangsuran
		$this->masaangsuran->ViewValue = $this->masaangsuran->CurrentValue;
		$this->masaangsuran->ViewCustomAttributes = "";

		// jatuhtempo
		$this->jatuhtempo->ViewValue = $this->jatuhtempo->CurrentValue;
		$this->jatuhtempo->ViewValue = ew_FormatDateTime($this->jatuhtempo->ViewValue, 0);
		$this->jatuhtempo->ViewCustomAttributes = "";

		// dispensasidenda
		$this->dispensasidenda->ViewValue = $this->dispensasidenda->CurrentValue;
		$this->dispensasidenda->ViewCustomAttributes = "";

		// titipan
		$this->titipan->ViewValue = $this->titipan->CurrentValue;
		$this->titipan->ViewCustomAttributes = "";

		// bayartitipan
		$this->bayartitipan->ViewValue = $this->bayartitipan->CurrentValue;
		$this->bayartitipan->ViewCustomAttributes = "";

		// bayartitipanauto
		$this->bayartitipanauto->ViewValue = $this->bayartitipanauto->CurrentValue;
		$this->bayartitipanauto->ViewCustomAttributes = "";

		// terbilang
		$this->terbilang->ViewValue = $this->terbilang->CurrentValue;
		$this->terbilang->ViewCustomAttributes = "";

		// petugas
		$this->petugas->ViewValue = $this->petugas->CurrentValue;
		$this->petugas->ViewCustomAttributes = "";

		// pembayaran
		$this->pembayaran->ViewValue = $this->pembayaran->CurrentValue;
		$this->pembayaran->ViewCustomAttributes = "";

		// bank
		$this->bank->ViewValue = $this->bank->CurrentValue;
		$this->bank->ViewCustomAttributes = "";

		// atasnama
		$this->atasnama->ViewValue = $this->atasnama->CurrentValue;
		$this->atasnama->ViewCustomAttributes = "";

		// tipe
		$this->tipe->ViewValue = $this->tipe->CurrentValue;
		$this->tipe->ViewCustomAttributes = "";

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

			// transaksi
			$this->transaksi->LinkCustomAttributes = "";
			$this->transaksi->HrefValue = "";
			$this->transaksi->TooltipValue = "";

			// referensi
			$this->referensi->LinkCustomAttributes = "";
			$this->referensi->HrefValue = "";
			$this->referensi->TooltipValue = "";

			// anggota
			$this->anggota->LinkCustomAttributes = "";
			$this->anggota->HrefValue = "";
			$this->anggota->TooltipValue = "";

			// namaanggota
			$this->namaanggota->LinkCustomAttributes = "";
			$this->namaanggota->HrefValue = "";
			$this->namaanggota->TooltipValue = "";

			// alamat
			$this->alamat->LinkCustomAttributes = "";
			$this->alamat->HrefValue = "";
			$this->alamat->TooltipValue = "";

			// pekerjaan
			$this->pekerjaan->LinkCustomAttributes = "";
			$this->pekerjaan->HrefValue = "";
			$this->pekerjaan->TooltipValue = "";

			// telepon
			$this->telepon->LinkCustomAttributes = "";
			$this->telepon->HrefValue = "";
			$this->telepon->TooltipValue = "";

			// hp
			$this->hp->LinkCustomAttributes = "";
			$this->hp->HrefValue = "";
			$this->hp->TooltipValue = "";

			// fax
			$this->fax->LinkCustomAttributes = "";
			$this->fax->HrefValue = "";
			$this->fax->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// website
			$this->website->LinkCustomAttributes = "";
			$this->website->HrefValue = "";
			$this->website->TooltipValue = "";

			// jenisanggota
			$this->jenisanggota->LinkCustomAttributes = "";
			$this->jenisanggota->HrefValue = "";
			$this->jenisanggota->TooltipValue = "";

			// model
			$this->model->LinkCustomAttributes = "";
			$this->model->HrefValue = "";
			$this->model->TooltipValue = "";

			// jenispinjaman
			$this->jenispinjaman->LinkCustomAttributes = "";
			$this->jenispinjaman->HrefValue = "";
			$this->jenispinjaman->TooltipValue = "";

			// jenisbunga
			$this->jenisbunga->LinkCustomAttributes = "";
			$this->jenisbunga->HrefValue = "";
			$this->jenisbunga->TooltipValue = "";

			// angsuran
			$this->angsuran->LinkCustomAttributes = "";
			$this->angsuran->HrefValue = "";
			$this->angsuran->TooltipValue = "";

			// masaangsuran
			$this->masaangsuran->LinkCustomAttributes = "";
			$this->masaangsuran->HrefValue = "";
			$this->masaangsuran->TooltipValue = "";

			// jatuhtempo
			$this->jatuhtempo->LinkCustomAttributes = "";
			$this->jatuhtempo->HrefValue = "";
			$this->jatuhtempo->TooltipValue = "";

			// dispensasidenda
			$this->dispensasidenda->LinkCustomAttributes = "";
			$this->dispensasidenda->HrefValue = "";
			$this->dispensasidenda->TooltipValue = "";

			// titipan
			$this->titipan->LinkCustomAttributes = "";
			$this->titipan->HrefValue = "";
			$this->titipan->TooltipValue = "";

			// bayartitipan
			$this->bayartitipan->LinkCustomAttributes = "";
			$this->bayartitipan->HrefValue = "";
			$this->bayartitipan->TooltipValue = "";

			// bayartitipanauto
			$this->bayartitipanauto->LinkCustomAttributes = "";
			$this->bayartitipanauto->HrefValue = "";
			$this->bayartitipanauto->TooltipValue = "";

			// terbilang
			$this->terbilang->LinkCustomAttributes = "";
			$this->terbilang->HrefValue = "";
			$this->terbilang->TooltipValue = "";

			// petugas
			$this->petugas->LinkCustomAttributes = "";
			$this->petugas->HrefValue = "";
			$this->petugas->TooltipValue = "";

			// pembayaran
			$this->pembayaran->LinkCustomAttributes = "";
			$this->pembayaran->HrefValue = "";
			$this->pembayaran->TooltipValue = "";

			// bank
			$this->bank->LinkCustomAttributes = "";
			$this->bank->HrefValue = "";
			$this->bank->TooltipValue = "";

			// atasnama
			$this->atasnama->LinkCustomAttributes = "";
			$this->atasnama->HrefValue = "";
			$this->atasnama->TooltipValue = "";

			// tipe
			$this->tipe->LinkCustomAttributes = "";
			$this->tipe->HrefValue = "";
			$this->tipe->TooltipValue = "";

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
				$sThisKey .= $row['titipan'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("tbayartitipanlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($tbayartitipan_delete)) $tbayartitipan_delete = new ctbayartitipan_delete();

// Page init
$tbayartitipan_delete->Page_Init();

// Page main
$tbayartitipan_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tbayartitipan_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = ftbayartitipandelete = new ew_Form("ftbayartitipandelete", "delete");

// Form_CustomValidate event
ftbayartitipandelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbayartitipandelete.ValidateRequired = true;
<?php } else { ?>
ftbayartitipandelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftbayartitipandelete.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftbayartitipandelete.Lists["x_active"].Options = <?php echo json_encode($tbayartitipan->active->Options()) ?>;

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
<?php $tbayartitipan_delete->ShowPageHeader(); ?>
<?php
$tbayartitipan_delete->ShowMessage();
?>
<form name="ftbayartitipandelete" id="ftbayartitipandelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tbayartitipan_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tbayartitipan_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tbayartitipan">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($tbayartitipan_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $tbayartitipan->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($tbayartitipan->tanggal->Visible) { // tanggal ?>
		<th><span id="elh_tbayartitipan_tanggal" class="tbayartitipan_tanggal"><?php echo $tbayartitipan->tanggal->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->periode->Visible) { // periode ?>
		<th><span id="elh_tbayartitipan_periode" class="tbayartitipan_periode"><?php echo $tbayartitipan->periode->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->id->Visible) { // id ?>
		<th><span id="elh_tbayartitipan_id" class="tbayartitipan_id"><?php echo $tbayartitipan->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->transaksi->Visible) { // transaksi ?>
		<th><span id="elh_tbayartitipan_transaksi" class="tbayartitipan_transaksi"><?php echo $tbayartitipan->transaksi->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->referensi->Visible) { // referensi ?>
		<th><span id="elh_tbayartitipan_referensi" class="tbayartitipan_referensi"><?php echo $tbayartitipan->referensi->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->anggota->Visible) { // anggota ?>
		<th><span id="elh_tbayartitipan_anggota" class="tbayartitipan_anggota"><?php echo $tbayartitipan->anggota->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->namaanggota->Visible) { // namaanggota ?>
		<th><span id="elh_tbayartitipan_namaanggota" class="tbayartitipan_namaanggota"><?php echo $tbayartitipan->namaanggota->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->alamat->Visible) { // alamat ?>
		<th><span id="elh_tbayartitipan_alamat" class="tbayartitipan_alamat"><?php echo $tbayartitipan->alamat->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->pekerjaan->Visible) { // pekerjaan ?>
		<th><span id="elh_tbayartitipan_pekerjaan" class="tbayartitipan_pekerjaan"><?php echo $tbayartitipan->pekerjaan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->telepon->Visible) { // telepon ?>
		<th><span id="elh_tbayartitipan_telepon" class="tbayartitipan_telepon"><?php echo $tbayartitipan->telepon->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->hp->Visible) { // hp ?>
		<th><span id="elh_tbayartitipan_hp" class="tbayartitipan_hp"><?php echo $tbayartitipan->hp->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->fax->Visible) { // fax ?>
		<th><span id="elh_tbayartitipan_fax" class="tbayartitipan_fax"><?php echo $tbayartitipan->fax->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->_email->Visible) { // email ?>
		<th><span id="elh_tbayartitipan__email" class="tbayartitipan__email"><?php echo $tbayartitipan->_email->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->website->Visible) { // website ?>
		<th><span id="elh_tbayartitipan_website" class="tbayartitipan_website"><?php echo $tbayartitipan->website->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->jenisanggota->Visible) { // jenisanggota ?>
		<th><span id="elh_tbayartitipan_jenisanggota" class="tbayartitipan_jenisanggota"><?php echo $tbayartitipan->jenisanggota->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->model->Visible) { // model ?>
		<th><span id="elh_tbayartitipan_model" class="tbayartitipan_model"><?php echo $tbayartitipan->model->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->jenispinjaman->Visible) { // jenispinjaman ?>
		<th><span id="elh_tbayartitipan_jenispinjaman" class="tbayartitipan_jenispinjaman"><?php echo $tbayartitipan->jenispinjaman->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->jenisbunga->Visible) { // jenisbunga ?>
		<th><span id="elh_tbayartitipan_jenisbunga" class="tbayartitipan_jenisbunga"><?php echo $tbayartitipan->jenisbunga->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->angsuran->Visible) { // angsuran ?>
		<th><span id="elh_tbayartitipan_angsuran" class="tbayartitipan_angsuran"><?php echo $tbayartitipan->angsuran->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->masaangsuran->Visible) { // masaangsuran ?>
		<th><span id="elh_tbayartitipan_masaangsuran" class="tbayartitipan_masaangsuran"><?php echo $tbayartitipan->masaangsuran->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->jatuhtempo->Visible) { // jatuhtempo ?>
		<th><span id="elh_tbayartitipan_jatuhtempo" class="tbayartitipan_jatuhtempo"><?php echo $tbayartitipan->jatuhtempo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->dispensasidenda->Visible) { // dispensasidenda ?>
		<th><span id="elh_tbayartitipan_dispensasidenda" class="tbayartitipan_dispensasidenda"><?php echo $tbayartitipan->dispensasidenda->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->titipan->Visible) { // titipan ?>
		<th><span id="elh_tbayartitipan_titipan" class="tbayartitipan_titipan"><?php echo $tbayartitipan->titipan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->bayartitipan->Visible) { // bayartitipan ?>
		<th><span id="elh_tbayartitipan_bayartitipan" class="tbayartitipan_bayartitipan"><?php echo $tbayartitipan->bayartitipan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->bayartitipanauto->Visible) { // bayartitipanauto ?>
		<th><span id="elh_tbayartitipan_bayartitipanauto" class="tbayartitipan_bayartitipanauto"><?php echo $tbayartitipan->bayartitipanauto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->terbilang->Visible) { // terbilang ?>
		<th><span id="elh_tbayartitipan_terbilang" class="tbayartitipan_terbilang"><?php echo $tbayartitipan->terbilang->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->petugas->Visible) { // petugas ?>
		<th><span id="elh_tbayartitipan_petugas" class="tbayartitipan_petugas"><?php echo $tbayartitipan->petugas->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->pembayaran->Visible) { // pembayaran ?>
		<th><span id="elh_tbayartitipan_pembayaran" class="tbayartitipan_pembayaran"><?php echo $tbayartitipan->pembayaran->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->bank->Visible) { // bank ?>
		<th><span id="elh_tbayartitipan_bank" class="tbayartitipan_bank"><?php echo $tbayartitipan->bank->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->atasnama->Visible) { // atasnama ?>
		<th><span id="elh_tbayartitipan_atasnama" class="tbayartitipan_atasnama"><?php echo $tbayartitipan->atasnama->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->tipe->Visible) { // tipe ?>
		<th><span id="elh_tbayartitipan_tipe" class="tbayartitipan_tipe"><?php echo $tbayartitipan->tipe->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->kantor->Visible) { // kantor ?>
		<th><span id="elh_tbayartitipan_kantor" class="tbayartitipan_kantor"><?php echo $tbayartitipan->kantor->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->keterangan->Visible) { // keterangan ?>
		<th><span id="elh_tbayartitipan_keterangan" class="tbayartitipan_keterangan"><?php echo $tbayartitipan->keterangan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->active->Visible) { // active ?>
		<th><span id="elh_tbayartitipan_active" class="tbayartitipan_active"><?php echo $tbayartitipan->active->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->ip->Visible) { // ip ?>
		<th><span id="elh_tbayartitipan_ip" class="tbayartitipan_ip"><?php echo $tbayartitipan->ip->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->status->Visible) { // status ?>
		<th><span id="elh_tbayartitipan_status" class="tbayartitipan_status"><?php echo $tbayartitipan->status->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->user->Visible) { // user ?>
		<th><span id="elh_tbayartitipan_user" class="tbayartitipan_user"><?php echo $tbayartitipan->user->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->created->Visible) { // created ?>
		<th><span id="elh_tbayartitipan_created" class="tbayartitipan_created"><?php echo $tbayartitipan->created->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tbayartitipan->modified->Visible) { // modified ?>
		<th><span id="elh_tbayartitipan_modified" class="tbayartitipan_modified"><?php echo $tbayartitipan->modified->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$tbayartitipan_delete->RecCnt = 0;
$i = 0;
while (!$tbayartitipan_delete->Recordset->EOF) {
	$tbayartitipan_delete->RecCnt++;
	$tbayartitipan_delete->RowCnt++;

	// Set row properties
	$tbayartitipan->ResetAttrs();
	$tbayartitipan->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$tbayartitipan_delete->LoadRowValues($tbayartitipan_delete->Recordset);

	// Render row
	$tbayartitipan_delete->RenderRow();
?>
	<tr<?php echo $tbayartitipan->RowAttributes() ?>>
<?php if ($tbayartitipan->tanggal->Visible) { // tanggal ?>
		<td<?php echo $tbayartitipan->tanggal->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_tanggal" class="tbayartitipan_tanggal">
<span<?php echo $tbayartitipan->tanggal->ViewAttributes() ?>>
<?php echo $tbayartitipan->tanggal->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->periode->Visible) { // periode ?>
		<td<?php echo $tbayartitipan->periode->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_periode" class="tbayartitipan_periode">
<span<?php echo $tbayartitipan->periode->ViewAttributes() ?>>
<?php echo $tbayartitipan->periode->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->id->Visible) { // id ?>
		<td<?php echo $tbayartitipan->id->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_id" class="tbayartitipan_id">
<span<?php echo $tbayartitipan->id->ViewAttributes() ?>>
<?php echo $tbayartitipan->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->transaksi->Visible) { // transaksi ?>
		<td<?php echo $tbayartitipan->transaksi->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_transaksi" class="tbayartitipan_transaksi">
<span<?php echo $tbayartitipan->transaksi->ViewAttributes() ?>>
<?php echo $tbayartitipan->transaksi->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->referensi->Visible) { // referensi ?>
		<td<?php echo $tbayartitipan->referensi->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_referensi" class="tbayartitipan_referensi">
<span<?php echo $tbayartitipan->referensi->ViewAttributes() ?>>
<?php echo $tbayartitipan->referensi->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->anggota->Visible) { // anggota ?>
		<td<?php echo $tbayartitipan->anggota->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_anggota" class="tbayartitipan_anggota">
<span<?php echo $tbayartitipan->anggota->ViewAttributes() ?>>
<?php echo $tbayartitipan->anggota->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->namaanggota->Visible) { // namaanggota ?>
		<td<?php echo $tbayartitipan->namaanggota->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_namaanggota" class="tbayartitipan_namaanggota">
<span<?php echo $tbayartitipan->namaanggota->ViewAttributes() ?>>
<?php echo $tbayartitipan->namaanggota->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->alamat->Visible) { // alamat ?>
		<td<?php echo $tbayartitipan->alamat->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_alamat" class="tbayartitipan_alamat">
<span<?php echo $tbayartitipan->alamat->ViewAttributes() ?>>
<?php echo $tbayartitipan->alamat->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->pekerjaan->Visible) { // pekerjaan ?>
		<td<?php echo $tbayartitipan->pekerjaan->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_pekerjaan" class="tbayartitipan_pekerjaan">
<span<?php echo $tbayartitipan->pekerjaan->ViewAttributes() ?>>
<?php echo $tbayartitipan->pekerjaan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->telepon->Visible) { // telepon ?>
		<td<?php echo $tbayartitipan->telepon->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_telepon" class="tbayartitipan_telepon">
<span<?php echo $tbayartitipan->telepon->ViewAttributes() ?>>
<?php echo $tbayartitipan->telepon->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->hp->Visible) { // hp ?>
		<td<?php echo $tbayartitipan->hp->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_hp" class="tbayartitipan_hp">
<span<?php echo $tbayartitipan->hp->ViewAttributes() ?>>
<?php echo $tbayartitipan->hp->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->fax->Visible) { // fax ?>
		<td<?php echo $tbayartitipan->fax->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_fax" class="tbayartitipan_fax">
<span<?php echo $tbayartitipan->fax->ViewAttributes() ?>>
<?php echo $tbayartitipan->fax->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->_email->Visible) { // email ?>
		<td<?php echo $tbayartitipan->_email->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan__email" class="tbayartitipan__email">
<span<?php echo $tbayartitipan->_email->ViewAttributes() ?>>
<?php echo $tbayartitipan->_email->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->website->Visible) { // website ?>
		<td<?php echo $tbayartitipan->website->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_website" class="tbayartitipan_website">
<span<?php echo $tbayartitipan->website->ViewAttributes() ?>>
<?php echo $tbayartitipan->website->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->jenisanggota->Visible) { // jenisanggota ?>
		<td<?php echo $tbayartitipan->jenisanggota->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_jenisanggota" class="tbayartitipan_jenisanggota">
<span<?php echo $tbayartitipan->jenisanggota->ViewAttributes() ?>>
<?php echo $tbayartitipan->jenisanggota->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->model->Visible) { // model ?>
		<td<?php echo $tbayartitipan->model->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_model" class="tbayartitipan_model">
<span<?php echo $tbayartitipan->model->ViewAttributes() ?>>
<?php echo $tbayartitipan->model->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->jenispinjaman->Visible) { // jenispinjaman ?>
		<td<?php echo $tbayartitipan->jenispinjaman->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_jenispinjaman" class="tbayartitipan_jenispinjaman">
<span<?php echo $tbayartitipan->jenispinjaman->ViewAttributes() ?>>
<?php echo $tbayartitipan->jenispinjaman->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->jenisbunga->Visible) { // jenisbunga ?>
		<td<?php echo $tbayartitipan->jenisbunga->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_jenisbunga" class="tbayartitipan_jenisbunga">
<span<?php echo $tbayartitipan->jenisbunga->ViewAttributes() ?>>
<?php echo $tbayartitipan->jenisbunga->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->angsuran->Visible) { // angsuran ?>
		<td<?php echo $tbayartitipan->angsuran->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_angsuran" class="tbayartitipan_angsuran">
<span<?php echo $tbayartitipan->angsuran->ViewAttributes() ?>>
<?php echo $tbayartitipan->angsuran->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->masaangsuran->Visible) { // masaangsuran ?>
		<td<?php echo $tbayartitipan->masaangsuran->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_masaangsuran" class="tbayartitipan_masaangsuran">
<span<?php echo $tbayartitipan->masaangsuran->ViewAttributes() ?>>
<?php echo $tbayartitipan->masaangsuran->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->jatuhtempo->Visible) { // jatuhtempo ?>
		<td<?php echo $tbayartitipan->jatuhtempo->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_jatuhtempo" class="tbayartitipan_jatuhtempo">
<span<?php echo $tbayartitipan->jatuhtempo->ViewAttributes() ?>>
<?php echo $tbayartitipan->jatuhtempo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->dispensasidenda->Visible) { // dispensasidenda ?>
		<td<?php echo $tbayartitipan->dispensasidenda->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_dispensasidenda" class="tbayartitipan_dispensasidenda">
<span<?php echo $tbayartitipan->dispensasidenda->ViewAttributes() ?>>
<?php echo $tbayartitipan->dispensasidenda->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->titipan->Visible) { // titipan ?>
		<td<?php echo $tbayartitipan->titipan->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_titipan" class="tbayartitipan_titipan">
<span<?php echo $tbayartitipan->titipan->ViewAttributes() ?>>
<?php echo $tbayartitipan->titipan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->bayartitipan->Visible) { // bayartitipan ?>
		<td<?php echo $tbayartitipan->bayartitipan->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_bayartitipan" class="tbayartitipan_bayartitipan">
<span<?php echo $tbayartitipan->bayartitipan->ViewAttributes() ?>>
<?php echo $tbayartitipan->bayartitipan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->bayartitipanauto->Visible) { // bayartitipanauto ?>
		<td<?php echo $tbayartitipan->bayartitipanauto->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_bayartitipanauto" class="tbayartitipan_bayartitipanauto">
<span<?php echo $tbayartitipan->bayartitipanauto->ViewAttributes() ?>>
<?php echo $tbayartitipan->bayartitipanauto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->terbilang->Visible) { // terbilang ?>
		<td<?php echo $tbayartitipan->terbilang->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_terbilang" class="tbayartitipan_terbilang">
<span<?php echo $tbayartitipan->terbilang->ViewAttributes() ?>>
<?php echo $tbayartitipan->terbilang->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->petugas->Visible) { // petugas ?>
		<td<?php echo $tbayartitipan->petugas->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_petugas" class="tbayartitipan_petugas">
<span<?php echo $tbayartitipan->petugas->ViewAttributes() ?>>
<?php echo $tbayartitipan->petugas->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->pembayaran->Visible) { // pembayaran ?>
		<td<?php echo $tbayartitipan->pembayaran->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_pembayaran" class="tbayartitipan_pembayaran">
<span<?php echo $tbayartitipan->pembayaran->ViewAttributes() ?>>
<?php echo $tbayartitipan->pembayaran->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->bank->Visible) { // bank ?>
		<td<?php echo $tbayartitipan->bank->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_bank" class="tbayartitipan_bank">
<span<?php echo $tbayartitipan->bank->ViewAttributes() ?>>
<?php echo $tbayartitipan->bank->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->atasnama->Visible) { // atasnama ?>
		<td<?php echo $tbayartitipan->atasnama->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_atasnama" class="tbayartitipan_atasnama">
<span<?php echo $tbayartitipan->atasnama->ViewAttributes() ?>>
<?php echo $tbayartitipan->atasnama->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->tipe->Visible) { // tipe ?>
		<td<?php echo $tbayartitipan->tipe->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_tipe" class="tbayartitipan_tipe">
<span<?php echo $tbayartitipan->tipe->ViewAttributes() ?>>
<?php echo $tbayartitipan->tipe->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->kantor->Visible) { // kantor ?>
		<td<?php echo $tbayartitipan->kantor->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_kantor" class="tbayartitipan_kantor">
<span<?php echo $tbayartitipan->kantor->ViewAttributes() ?>>
<?php echo $tbayartitipan->kantor->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->keterangan->Visible) { // keterangan ?>
		<td<?php echo $tbayartitipan->keterangan->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_keterangan" class="tbayartitipan_keterangan">
<span<?php echo $tbayartitipan->keterangan->ViewAttributes() ?>>
<?php echo $tbayartitipan->keterangan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->active->Visible) { // active ?>
		<td<?php echo $tbayartitipan->active->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_active" class="tbayartitipan_active">
<span<?php echo $tbayartitipan->active->ViewAttributes() ?>>
<?php echo $tbayartitipan->active->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->ip->Visible) { // ip ?>
		<td<?php echo $tbayartitipan->ip->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_ip" class="tbayartitipan_ip">
<span<?php echo $tbayartitipan->ip->ViewAttributes() ?>>
<?php echo $tbayartitipan->ip->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->status->Visible) { // status ?>
		<td<?php echo $tbayartitipan->status->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_status" class="tbayartitipan_status">
<span<?php echo $tbayartitipan->status->ViewAttributes() ?>>
<?php echo $tbayartitipan->status->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->user->Visible) { // user ?>
		<td<?php echo $tbayartitipan->user->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_user" class="tbayartitipan_user">
<span<?php echo $tbayartitipan->user->ViewAttributes() ?>>
<?php echo $tbayartitipan->user->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->created->Visible) { // created ?>
		<td<?php echo $tbayartitipan->created->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_created" class="tbayartitipan_created">
<span<?php echo $tbayartitipan->created->ViewAttributes() ?>>
<?php echo $tbayartitipan->created->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tbayartitipan->modified->Visible) { // modified ?>
		<td<?php echo $tbayartitipan->modified->CellAttributes() ?>>
<span id="el<?php echo $tbayartitipan_delete->RowCnt ?>_tbayartitipan_modified" class="tbayartitipan_modified">
<span<?php echo $tbayartitipan->modified->ViewAttributes() ?>>
<?php echo $tbayartitipan->modified->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$tbayartitipan_delete->Recordset->MoveNext();
}
$tbayartitipan_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $tbayartitipan_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
ftbayartitipandelete.Init();
</script>
<?php
$tbayartitipan_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tbayartitipan_delete->Page_Terminate();
?>
