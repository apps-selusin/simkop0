<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "tpinjamaninfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$tpinjaman_delete = NULL; // Initialize page object first

class ctpinjaman_delete extends ctpinjaman {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tpinjaman';

	// Page object name
	var $PageObjName = 'tpinjaman_delete';

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

		// Table object (tpinjaman)
		if (!isset($GLOBALS["tpinjaman"]) || get_class($GLOBALS["tpinjaman"]) == "ctpinjaman") {
			$GLOBALS["tpinjaman"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tpinjaman"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tpinjaman', TRUE);

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
		$this->agunan->SetVisibility();
		$this->dataagunan1->SetVisibility();
		$this->dataagunan2->SetVisibility();
		$this->dataagunan3->SetVisibility();
		$this->dataagunan4->SetVisibility();
		$this->dataagunan5->SetVisibility();
		$this->saldobekusimpanan->SetVisibility();
		$this->saldobekuminimal->SetVisibility();
		$this->plafond->SetVisibility();
		$this->bunga->SetVisibility();
		$this->bungapersen->SetVisibility();
		$this->administrasi->SetVisibility();
		$this->administrasipersen->SetVisibility();
		$this->asuransi->SetVisibility();
		$this->notaris->SetVisibility();
		$this->biayamaterai->SetVisibility();
		$this->potongansaldobeku->SetVisibility();
		$this->angsuranpokok->SetVisibility();
		$this->angsuranpokokauto->SetVisibility();
		$this->angsuranbunga->SetVisibility();
		$this->angsuranbungaauto->SetVisibility();
		$this->denda->SetVisibility();
		$this->dendapersen->SetVisibility();
		$this->totalangsuran->SetVisibility();
		$this->totalangsuranauto->SetVisibility();
		$this->totalterima->SetVisibility();
		$this->totalterimaauto->SetVisibility();
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
		global $EW_EXPORT, $tpinjaman;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($tpinjaman);
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
			$this->Page_Terminate("tpinjamanlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in tpinjaman class, tpinjamaninfo.php

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
				$this->Page_Terminate("tpinjamanlist.php"); // Return to list
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
		$this->agunan->setDbValue($rs->fields('agunan'));
		$this->dataagunan1->setDbValue($rs->fields('dataagunan1'));
		$this->dataagunan2->setDbValue($rs->fields('dataagunan2'));
		$this->dataagunan3->setDbValue($rs->fields('dataagunan3'));
		$this->dataagunan4->setDbValue($rs->fields('dataagunan4'));
		$this->dataagunan5->setDbValue($rs->fields('dataagunan5'));
		$this->saldobekusimpanan->setDbValue($rs->fields('saldobekusimpanan'));
		$this->saldobekuminimal->setDbValue($rs->fields('saldobekuminimal'));
		$this->plafond->setDbValue($rs->fields('plafond'));
		$this->bunga->setDbValue($rs->fields('bunga'));
		$this->bungapersen->setDbValue($rs->fields('bungapersen'));
		$this->administrasi->setDbValue($rs->fields('administrasi'));
		$this->administrasipersen->setDbValue($rs->fields('administrasipersen'));
		$this->asuransi->setDbValue($rs->fields('asuransi'));
		$this->notaris->setDbValue($rs->fields('notaris'));
		$this->biayamaterai->setDbValue($rs->fields('biayamaterai'));
		$this->potongansaldobeku->setDbValue($rs->fields('potongansaldobeku'));
		$this->angsuranpokok->setDbValue($rs->fields('angsuranpokok'));
		$this->angsuranpokokauto->setDbValue($rs->fields('angsuranpokokauto'));
		$this->angsuranbunga->setDbValue($rs->fields('angsuranbunga'));
		$this->angsuranbungaauto->setDbValue($rs->fields('angsuranbungaauto'));
		$this->denda->setDbValue($rs->fields('denda'));
		$this->dendapersen->setDbValue($rs->fields('dendapersen'));
		$this->totalangsuran->setDbValue($rs->fields('totalangsuran'));
		$this->totalangsuranauto->setDbValue($rs->fields('totalangsuranauto'));
		$this->totalterima->setDbValue($rs->fields('totalterima'));
		$this->totalterimaauto->setDbValue($rs->fields('totalterimaauto'));
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
		$this->agunan->DbValue = $row['agunan'];
		$this->dataagunan1->DbValue = $row['dataagunan1'];
		$this->dataagunan2->DbValue = $row['dataagunan2'];
		$this->dataagunan3->DbValue = $row['dataagunan3'];
		$this->dataagunan4->DbValue = $row['dataagunan4'];
		$this->dataagunan5->DbValue = $row['dataagunan5'];
		$this->saldobekusimpanan->DbValue = $row['saldobekusimpanan'];
		$this->saldobekuminimal->DbValue = $row['saldobekuminimal'];
		$this->plafond->DbValue = $row['plafond'];
		$this->bunga->DbValue = $row['bunga'];
		$this->bungapersen->DbValue = $row['bungapersen'];
		$this->administrasi->DbValue = $row['administrasi'];
		$this->administrasipersen->DbValue = $row['administrasipersen'];
		$this->asuransi->DbValue = $row['asuransi'];
		$this->notaris->DbValue = $row['notaris'];
		$this->biayamaterai->DbValue = $row['biayamaterai'];
		$this->potongansaldobeku->DbValue = $row['potongansaldobeku'];
		$this->angsuranpokok->DbValue = $row['angsuranpokok'];
		$this->angsuranpokokauto->DbValue = $row['angsuranpokokauto'];
		$this->angsuranbunga->DbValue = $row['angsuranbunga'];
		$this->angsuranbungaauto->DbValue = $row['angsuranbungaauto'];
		$this->denda->DbValue = $row['denda'];
		$this->dendapersen->DbValue = $row['dendapersen'];
		$this->totalangsuran->DbValue = $row['totalangsuran'];
		$this->totalangsuranauto->DbValue = $row['totalangsuranauto'];
		$this->totalterima->DbValue = $row['totalterima'];
		$this->totalterimaauto->DbValue = $row['totalterimaauto'];
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

		if ($this->saldobekusimpanan->FormValue == $this->saldobekusimpanan->CurrentValue && is_numeric(ew_StrToFloat($this->saldobekusimpanan->CurrentValue)))
			$this->saldobekusimpanan->CurrentValue = ew_StrToFloat($this->saldobekusimpanan->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldobekuminimal->FormValue == $this->saldobekuminimal->CurrentValue && is_numeric(ew_StrToFloat($this->saldobekuminimal->CurrentValue)))
			$this->saldobekuminimal->CurrentValue = ew_StrToFloat($this->saldobekuminimal->CurrentValue);

		// Convert decimal values if posted back
		if ($this->plafond->FormValue == $this->plafond->CurrentValue && is_numeric(ew_StrToFloat($this->plafond->CurrentValue)))
			$this->plafond->CurrentValue = ew_StrToFloat($this->plafond->CurrentValue);

		// Convert decimal values if posted back
		if ($this->bunga->FormValue == $this->bunga->CurrentValue && is_numeric(ew_StrToFloat($this->bunga->CurrentValue)))
			$this->bunga->CurrentValue = ew_StrToFloat($this->bunga->CurrentValue);

		// Convert decimal values if posted back
		if ($this->bungapersen->FormValue == $this->bungapersen->CurrentValue && is_numeric(ew_StrToFloat($this->bungapersen->CurrentValue)))
			$this->bungapersen->CurrentValue = ew_StrToFloat($this->bungapersen->CurrentValue);

		// Convert decimal values if posted back
		if ($this->administrasi->FormValue == $this->administrasi->CurrentValue && is_numeric(ew_StrToFloat($this->administrasi->CurrentValue)))
			$this->administrasi->CurrentValue = ew_StrToFloat($this->administrasi->CurrentValue);

		// Convert decimal values if posted back
		if ($this->administrasipersen->FormValue == $this->administrasipersen->CurrentValue && is_numeric(ew_StrToFloat($this->administrasipersen->CurrentValue)))
			$this->administrasipersen->CurrentValue = ew_StrToFloat($this->administrasipersen->CurrentValue);

		// Convert decimal values if posted back
		if ($this->asuransi->FormValue == $this->asuransi->CurrentValue && is_numeric(ew_StrToFloat($this->asuransi->CurrentValue)))
			$this->asuransi->CurrentValue = ew_StrToFloat($this->asuransi->CurrentValue);

		// Convert decimal values if posted back
		if ($this->notaris->FormValue == $this->notaris->CurrentValue && is_numeric(ew_StrToFloat($this->notaris->CurrentValue)))
			$this->notaris->CurrentValue = ew_StrToFloat($this->notaris->CurrentValue);

		// Convert decimal values if posted back
		if ($this->biayamaterai->FormValue == $this->biayamaterai->CurrentValue && is_numeric(ew_StrToFloat($this->biayamaterai->CurrentValue)))
			$this->biayamaterai->CurrentValue = ew_StrToFloat($this->biayamaterai->CurrentValue);

		// Convert decimal values if posted back
		if ($this->potongansaldobeku->FormValue == $this->potongansaldobeku->CurrentValue && is_numeric(ew_StrToFloat($this->potongansaldobeku->CurrentValue)))
			$this->potongansaldobeku->CurrentValue = ew_StrToFloat($this->potongansaldobeku->CurrentValue);

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
		if ($this->denda->FormValue == $this->denda->CurrentValue && is_numeric(ew_StrToFloat($this->denda->CurrentValue)))
			$this->denda->CurrentValue = ew_StrToFloat($this->denda->CurrentValue);

		// Convert decimal values if posted back
		if ($this->dendapersen->FormValue == $this->dendapersen->CurrentValue && is_numeric(ew_StrToFloat($this->dendapersen->CurrentValue)))
			$this->dendapersen->CurrentValue = ew_StrToFloat($this->dendapersen->CurrentValue);

		// Convert decimal values if posted back
		if ($this->totalangsuran->FormValue == $this->totalangsuran->CurrentValue && is_numeric(ew_StrToFloat($this->totalangsuran->CurrentValue)))
			$this->totalangsuran->CurrentValue = ew_StrToFloat($this->totalangsuran->CurrentValue);

		// Convert decimal values if posted back
		if ($this->totalangsuranauto->FormValue == $this->totalangsuranauto->CurrentValue && is_numeric(ew_StrToFloat($this->totalangsuranauto->CurrentValue)))
			$this->totalangsuranauto->CurrentValue = ew_StrToFloat($this->totalangsuranauto->CurrentValue);

		// Convert decimal values if posted back
		if ($this->totalterima->FormValue == $this->totalterima->CurrentValue && is_numeric(ew_StrToFloat($this->totalterima->CurrentValue)))
			$this->totalterima->CurrentValue = ew_StrToFloat($this->totalterima->CurrentValue);

		// Convert decimal values if posted back
		if ($this->totalterimaauto->FormValue == $this->totalterimaauto->CurrentValue && is_numeric(ew_StrToFloat($this->totalterimaauto->CurrentValue)))
			$this->totalterimaauto->CurrentValue = ew_StrToFloat($this->totalterimaauto->CurrentValue);

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
		// agunan
		// dataagunan1
		// dataagunan2
		// dataagunan3
		// dataagunan4
		// dataagunan5
		// saldobekusimpanan
		// saldobekuminimal
		// plafond
		// bunga
		// bungapersen
		// administrasi
		// administrasipersen
		// asuransi
		// notaris
		// biayamaterai
		// potongansaldobeku
		// angsuranpokok
		// angsuranpokokauto
		// angsuranbunga
		// angsuranbungaauto
		// denda
		// dendapersen
		// totalangsuran
		// totalangsuranauto
		// totalterima
		// totalterimaauto
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

		// agunan
		$this->agunan->ViewValue = $this->agunan->CurrentValue;
		$this->agunan->ViewCustomAttributes = "";

		// dataagunan1
		$this->dataagunan1->ViewValue = $this->dataagunan1->CurrentValue;
		$this->dataagunan1->ViewCustomAttributes = "";

		// dataagunan2
		$this->dataagunan2->ViewValue = $this->dataagunan2->CurrentValue;
		$this->dataagunan2->ViewCustomAttributes = "";

		// dataagunan3
		$this->dataagunan3->ViewValue = $this->dataagunan3->CurrentValue;
		$this->dataagunan3->ViewCustomAttributes = "";

		// dataagunan4
		$this->dataagunan4->ViewValue = $this->dataagunan4->CurrentValue;
		$this->dataagunan4->ViewCustomAttributes = "";

		// dataagunan5
		$this->dataagunan5->ViewValue = $this->dataagunan5->CurrentValue;
		$this->dataagunan5->ViewCustomAttributes = "";

		// saldobekusimpanan
		$this->saldobekusimpanan->ViewValue = $this->saldobekusimpanan->CurrentValue;
		$this->saldobekusimpanan->ViewCustomAttributes = "";

		// saldobekuminimal
		$this->saldobekuminimal->ViewValue = $this->saldobekuminimal->CurrentValue;
		$this->saldobekuminimal->ViewCustomAttributes = "";

		// plafond
		$this->plafond->ViewValue = $this->plafond->CurrentValue;
		$this->plafond->ViewCustomAttributes = "";

		// bunga
		$this->bunga->ViewValue = $this->bunga->CurrentValue;
		$this->bunga->ViewCustomAttributes = "";

		// bungapersen
		$this->bungapersen->ViewValue = $this->bungapersen->CurrentValue;
		$this->bungapersen->ViewCustomAttributes = "";

		// administrasi
		$this->administrasi->ViewValue = $this->administrasi->CurrentValue;
		$this->administrasi->ViewCustomAttributes = "";

		// administrasipersen
		$this->administrasipersen->ViewValue = $this->administrasipersen->CurrentValue;
		$this->administrasipersen->ViewCustomAttributes = "";

		// asuransi
		$this->asuransi->ViewValue = $this->asuransi->CurrentValue;
		$this->asuransi->ViewCustomAttributes = "";

		// notaris
		$this->notaris->ViewValue = $this->notaris->CurrentValue;
		$this->notaris->ViewCustomAttributes = "";

		// biayamaterai
		$this->biayamaterai->ViewValue = $this->biayamaterai->CurrentValue;
		$this->biayamaterai->ViewCustomAttributes = "";

		// potongansaldobeku
		$this->potongansaldobeku->ViewValue = $this->potongansaldobeku->CurrentValue;
		$this->potongansaldobeku->ViewCustomAttributes = "";

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

		// denda
		$this->denda->ViewValue = $this->denda->CurrentValue;
		$this->denda->ViewCustomAttributes = "";

		// dendapersen
		$this->dendapersen->ViewValue = $this->dendapersen->CurrentValue;
		$this->dendapersen->ViewCustomAttributes = "";

		// totalangsuran
		$this->totalangsuran->ViewValue = $this->totalangsuran->CurrentValue;
		$this->totalangsuran->ViewCustomAttributes = "";

		// totalangsuranauto
		$this->totalangsuranauto->ViewValue = $this->totalangsuranauto->CurrentValue;
		$this->totalangsuranauto->ViewCustomAttributes = "";

		// totalterima
		$this->totalterima->ViewValue = $this->totalterima->CurrentValue;
		$this->totalterima->ViewCustomAttributes = "";

		// totalterimaauto
		$this->totalterimaauto->ViewValue = $this->totalterimaauto->CurrentValue;
		$this->totalterimaauto->ViewCustomAttributes = "";

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

			// agunan
			$this->agunan->LinkCustomAttributes = "";
			$this->agunan->HrefValue = "";
			$this->agunan->TooltipValue = "";

			// dataagunan1
			$this->dataagunan1->LinkCustomAttributes = "";
			$this->dataagunan1->HrefValue = "";
			$this->dataagunan1->TooltipValue = "";

			// dataagunan2
			$this->dataagunan2->LinkCustomAttributes = "";
			$this->dataagunan2->HrefValue = "";
			$this->dataagunan2->TooltipValue = "";

			// dataagunan3
			$this->dataagunan3->LinkCustomAttributes = "";
			$this->dataagunan3->HrefValue = "";
			$this->dataagunan3->TooltipValue = "";

			// dataagunan4
			$this->dataagunan4->LinkCustomAttributes = "";
			$this->dataagunan4->HrefValue = "";
			$this->dataagunan4->TooltipValue = "";

			// dataagunan5
			$this->dataagunan5->LinkCustomAttributes = "";
			$this->dataagunan5->HrefValue = "";
			$this->dataagunan5->TooltipValue = "";

			// saldobekusimpanan
			$this->saldobekusimpanan->LinkCustomAttributes = "";
			$this->saldobekusimpanan->HrefValue = "";
			$this->saldobekusimpanan->TooltipValue = "";

			// saldobekuminimal
			$this->saldobekuminimal->LinkCustomAttributes = "";
			$this->saldobekuminimal->HrefValue = "";
			$this->saldobekuminimal->TooltipValue = "";

			// plafond
			$this->plafond->LinkCustomAttributes = "";
			$this->plafond->HrefValue = "";
			$this->plafond->TooltipValue = "";

			// bunga
			$this->bunga->LinkCustomAttributes = "";
			$this->bunga->HrefValue = "";
			$this->bunga->TooltipValue = "";

			// bungapersen
			$this->bungapersen->LinkCustomAttributes = "";
			$this->bungapersen->HrefValue = "";
			$this->bungapersen->TooltipValue = "";

			// administrasi
			$this->administrasi->LinkCustomAttributes = "";
			$this->administrasi->HrefValue = "";
			$this->administrasi->TooltipValue = "";

			// administrasipersen
			$this->administrasipersen->LinkCustomAttributes = "";
			$this->administrasipersen->HrefValue = "";
			$this->administrasipersen->TooltipValue = "";

			// asuransi
			$this->asuransi->LinkCustomAttributes = "";
			$this->asuransi->HrefValue = "";
			$this->asuransi->TooltipValue = "";

			// notaris
			$this->notaris->LinkCustomAttributes = "";
			$this->notaris->HrefValue = "";
			$this->notaris->TooltipValue = "";

			// biayamaterai
			$this->biayamaterai->LinkCustomAttributes = "";
			$this->biayamaterai->HrefValue = "";
			$this->biayamaterai->TooltipValue = "";

			// potongansaldobeku
			$this->potongansaldobeku->LinkCustomAttributes = "";
			$this->potongansaldobeku->HrefValue = "";
			$this->potongansaldobeku->TooltipValue = "";

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

			// denda
			$this->denda->LinkCustomAttributes = "";
			$this->denda->HrefValue = "";
			$this->denda->TooltipValue = "";

			// dendapersen
			$this->dendapersen->LinkCustomAttributes = "";
			$this->dendapersen->HrefValue = "";
			$this->dendapersen->TooltipValue = "";

			// totalangsuran
			$this->totalangsuran->LinkCustomAttributes = "";
			$this->totalangsuran->HrefValue = "";
			$this->totalangsuran->TooltipValue = "";

			// totalangsuranauto
			$this->totalangsuranauto->LinkCustomAttributes = "";
			$this->totalangsuranauto->HrefValue = "";
			$this->totalangsuranauto->TooltipValue = "";

			// totalterima
			$this->totalterima->LinkCustomAttributes = "";
			$this->totalterima->HrefValue = "";
			$this->totalterima->TooltipValue = "";

			// totalterimaauto
			$this->totalterimaauto->LinkCustomAttributes = "";
			$this->totalterimaauto->HrefValue = "";
			$this->totalterimaauto->TooltipValue = "";

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("tpinjamanlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($tpinjaman_delete)) $tpinjaman_delete = new ctpinjaman_delete();

// Page init
$tpinjaman_delete->Page_Init();

// Page main
$tpinjaman_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tpinjaman_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = ftpinjamandelete = new ew_Form("ftpinjamandelete", "delete");

// Form_CustomValidate event
ftpinjamandelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftpinjamandelete.ValidateRequired = true;
<?php } else { ?>
ftpinjamandelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftpinjamandelete.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftpinjamandelete.Lists["x_active"].Options = <?php echo json_encode($tpinjaman->active->Options()) ?>;

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
<?php $tpinjaman_delete->ShowPageHeader(); ?>
<?php
$tpinjaman_delete->ShowMessage();
?>
<form name="ftpinjamandelete" id="ftpinjamandelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tpinjaman_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tpinjaman_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tpinjaman">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($tpinjaman_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $tpinjaman->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($tpinjaman->tanggal->Visible) { // tanggal ?>
		<th><span id="elh_tpinjaman_tanggal" class="tpinjaman_tanggal"><?php echo $tpinjaman->tanggal->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->periode->Visible) { // periode ?>
		<th><span id="elh_tpinjaman_periode" class="tpinjaman_periode"><?php echo $tpinjaman->periode->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->id->Visible) { // id ?>
		<th><span id="elh_tpinjaman_id" class="tpinjaman_id"><?php echo $tpinjaman->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->transaksi->Visible) { // transaksi ?>
		<th><span id="elh_tpinjaman_transaksi" class="tpinjaman_transaksi"><?php echo $tpinjaman->transaksi->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->referensi->Visible) { // referensi ?>
		<th><span id="elh_tpinjaman_referensi" class="tpinjaman_referensi"><?php echo $tpinjaman->referensi->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->anggota->Visible) { // anggota ?>
		<th><span id="elh_tpinjaman_anggota" class="tpinjaman_anggota"><?php echo $tpinjaman->anggota->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->namaanggota->Visible) { // namaanggota ?>
		<th><span id="elh_tpinjaman_namaanggota" class="tpinjaman_namaanggota"><?php echo $tpinjaman->namaanggota->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->alamat->Visible) { // alamat ?>
		<th><span id="elh_tpinjaman_alamat" class="tpinjaman_alamat"><?php echo $tpinjaman->alamat->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->pekerjaan->Visible) { // pekerjaan ?>
		<th><span id="elh_tpinjaman_pekerjaan" class="tpinjaman_pekerjaan"><?php echo $tpinjaman->pekerjaan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->telepon->Visible) { // telepon ?>
		<th><span id="elh_tpinjaman_telepon" class="tpinjaman_telepon"><?php echo $tpinjaman->telepon->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->hp->Visible) { // hp ?>
		<th><span id="elh_tpinjaman_hp" class="tpinjaman_hp"><?php echo $tpinjaman->hp->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->fax->Visible) { // fax ?>
		<th><span id="elh_tpinjaman_fax" class="tpinjaman_fax"><?php echo $tpinjaman->fax->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->_email->Visible) { // email ?>
		<th><span id="elh_tpinjaman__email" class="tpinjaman__email"><?php echo $tpinjaman->_email->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->website->Visible) { // website ?>
		<th><span id="elh_tpinjaman_website" class="tpinjaman_website"><?php echo $tpinjaman->website->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->jenisanggota->Visible) { // jenisanggota ?>
		<th><span id="elh_tpinjaman_jenisanggota" class="tpinjaman_jenisanggota"><?php echo $tpinjaman->jenisanggota->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->model->Visible) { // model ?>
		<th><span id="elh_tpinjaman_model" class="tpinjaman_model"><?php echo $tpinjaman->model->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->jenispinjaman->Visible) { // jenispinjaman ?>
		<th><span id="elh_tpinjaman_jenispinjaman" class="tpinjaman_jenispinjaman"><?php echo $tpinjaman->jenispinjaman->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->jenisbunga->Visible) { // jenisbunga ?>
		<th><span id="elh_tpinjaman_jenisbunga" class="tpinjaman_jenisbunga"><?php echo $tpinjaman->jenisbunga->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->angsuran->Visible) { // angsuran ?>
		<th><span id="elh_tpinjaman_angsuran" class="tpinjaman_angsuran"><?php echo $tpinjaman->angsuran->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->masaangsuran->Visible) { // masaangsuran ?>
		<th><span id="elh_tpinjaman_masaangsuran" class="tpinjaman_masaangsuran"><?php echo $tpinjaman->masaangsuran->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->jatuhtempo->Visible) { // jatuhtempo ?>
		<th><span id="elh_tpinjaman_jatuhtempo" class="tpinjaman_jatuhtempo"><?php echo $tpinjaman->jatuhtempo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->dispensasidenda->Visible) { // dispensasidenda ?>
		<th><span id="elh_tpinjaman_dispensasidenda" class="tpinjaman_dispensasidenda"><?php echo $tpinjaman->dispensasidenda->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->agunan->Visible) { // agunan ?>
		<th><span id="elh_tpinjaman_agunan" class="tpinjaman_agunan"><?php echo $tpinjaman->agunan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->dataagunan1->Visible) { // dataagunan1 ?>
		<th><span id="elh_tpinjaman_dataagunan1" class="tpinjaman_dataagunan1"><?php echo $tpinjaman->dataagunan1->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->dataagunan2->Visible) { // dataagunan2 ?>
		<th><span id="elh_tpinjaman_dataagunan2" class="tpinjaman_dataagunan2"><?php echo $tpinjaman->dataagunan2->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->dataagunan3->Visible) { // dataagunan3 ?>
		<th><span id="elh_tpinjaman_dataagunan3" class="tpinjaman_dataagunan3"><?php echo $tpinjaman->dataagunan3->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->dataagunan4->Visible) { // dataagunan4 ?>
		<th><span id="elh_tpinjaman_dataagunan4" class="tpinjaman_dataagunan4"><?php echo $tpinjaman->dataagunan4->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->dataagunan5->Visible) { // dataagunan5 ?>
		<th><span id="elh_tpinjaman_dataagunan5" class="tpinjaman_dataagunan5"><?php echo $tpinjaman->dataagunan5->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->saldobekusimpanan->Visible) { // saldobekusimpanan ?>
		<th><span id="elh_tpinjaman_saldobekusimpanan" class="tpinjaman_saldobekusimpanan"><?php echo $tpinjaman->saldobekusimpanan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->saldobekuminimal->Visible) { // saldobekuminimal ?>
		<th><span id="elh_tpinjaman_saldobekuminimal" class="tpinjaman_saldobekuminimal"><?php echo $tpinjaman->saldobekuminimal->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->plafond->Visible) { // plafond ?>
		<th><span id="elh_tpinjaman_plafond" class="tpinjaman_plafond"><?php echo $tpinjaman->plafond->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->bunga->Visible) { // bunga ?>
		<th><span id="elh_tpinjaman_bunga" class="tpinjaman_bunga"><?php echo $tpinjaman->bunga->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->bungapersen->Visible) { // bungapersen ?>
		<th><span id="elh_tpinjaman_bungapersen" class="tpinjaman_bungapersen"><?php echo $tpinjaman->bungapersen->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->administrasi->Visible) { // administrasi ?>
		<th><span id="elh_tpinjaman_administrasi" class="tpinjaman_administrasi"><?php echo $tpinjaman->administrasi->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->administrasipersen->Visible) { // administrasipersen ?>
		<th><span id="elh_tpinjaman_administrasipersen" class="tpinjaman_administrasipersen"><?php echo $tpinjaman->administrasipersen->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->asuransi->Visible) { // asuransi ?>
		<th><span id="elh_tpinjaman_asuransi" class="tpinjaman_asuransi"><?php echo $tpinjaman->asuransi->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->notaris->Visible) { // notaris ?>
		<th><span id="elh_tpinjaman_notaris" class="tpinjaman_notaris"><?php echo $tpinjaman->notaris->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->biayamaterai->Visible) { // biayamaterai ?>
		<th><span id="elh_tpinjaman_biayamaterai" class="tpinjaman_biayamaterai"><?php echo $tpinjaman->biayamaterai->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->potongansaldobeku->Visible) { // potongansaldobeku ?>
		<th><span id="elh_tpinjaman_potongansaldobeku" class="tpinjaman_potongansaldobeku"><?php echo $tpinjaman->potongansaldobeku->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->angsuranpokok->Visible) { // angsuranpokok ?>
		<th><span id="elh_tpinjaman_angsuranpokok" class="tpinjaman_angsuranpokok"><?php echo $tpinjaman->angsuranpokok->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->angsuranpokokauto->Visible) { // angsuranpokokauto ?>
		<th><span id="elh_tpinjaman_angsuranpokokauto" class="tpinjaman_angsuranpokokauto"><?php echo $tpinjaman->angsuranpokokauto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->angsuranbunga->Visible) { // angsuranbunga ?>
		<th><span id="elh_tpinjaman_angsuranbunga" class="tpinjaman_angsuranbunga"><?php echo $tpinjaman->angsuranbunga->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->angsuranbungaauto->Visible) { // angsuranbungaauto ?>
		<th><span id="elh_tpinjaman_angsuranbungaauto" class="tpinjaman_angsuranbungaauto"><?php echo $tpinjaman->angsuranbungaauto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->denda->Visible) { // denda ?>
		<th><span id="elh_tpinjaman_denda" class="tpinjaman_denda"><?php echo $tpinjaman->denda->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->dendapersen->Visible) { // dendapersen ?>
		<th><span id="elh_tpinjaman_dendapersen" class="tpinjaman_dendapersen"><?php echo $tpinjaman->dendapersen->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->totalangsuran->Visible) { // totalangsuran ?>
		<th><span id="elh_tpinjaman_totalangsuran" class="tpinjaman_totalangsuran"><?php echo $tpinjaman->totalangsuran->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->totalangsuranauto->Visible) { // totalangsuranauto ?>
		<th><span id="elh_tpinjaman_totalangsuranauto" class="tpinjaman_totalangsuranauto"><?php echo $tpinjaman->totalangsuranauto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->totalterima->Visible) { // totalterima ?>
		<th><span id="elh_tpinjaman_totalterima" class="tpinjaman_totalterima"><?php echo $tpinjaman->totalterima->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->totalterimaauto->Visible) { // totalterimaauto ?>
		<th><span id="elh_tpinjaman_totalterimaauto" class="tpinjaman_totalterimaauto"><?php echo $tpinjaman->totalterimaauto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->terbilang->Visible) { // terbilang ?>
		<th><span id="elh_tpinjaman_terbilang" class="tpinjaman_terbilang"><?php echo $tpinjaman->terbilang->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->petugas->Visible) { // petugas ?>
		<th><span id="elh_tpinjaman_petugas" class="tpinjaman_petugas"><?php echo $tpinjaman->petugas->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->pembayaran->Visible) { // pembayaran ?>
		<th><span id="elh_tpinjaman_pembayaran" class="tpinjaman_pembayaran"><?php echo $tpinjaman->pembayaran->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->bank->Visible) { // bank ?>
		<th><span id="elh_tpinjaman_bank" class="tpinjaman_bank"><?php echo $tpinjaman->bank->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->atasnama->Visible) { // atasnama ?>
		<th><span id="elh_tpinjaman_atasnama" class="tpinjaman_atasnama"><?php echo $tpinjaman->atasnama->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->tipe->Visible) { // tipe ?>
		<th><span id="elh_tpinjaman_tipe" class="tpinjaman_tipe"><?php echo $tpinjaman->tipe->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->kantor->Visible) { // kantor ?>
		<th><span id="elh_tpinjaman_kantor" class="tpinjaman_kantor"><?php echo $tpinjaman->kantor->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->keterangan->Visible) { // keterangan ?>
		<th><span id="elh_tpinjaman_keterangan" class="tpinjaman_keterangan"><?php echo $tpinjaman->keterangan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->active->Visible) { // active ?>
		<th><span id="elh_tpinjaman_active" class="tpinjaman_active"><?php echo $tpinjaman->active->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->ip->Visible) { // ip ?>
		<th><span id="elh_tpinjaman_ip" class="tpinjaman_ip"><?php echo $tpinjaman->ip->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->status->Visible) { // status ?>
		<th><span id="elh_tpinjaman_status" class="tpinjaman_status"><?php echo $tpinjaman->status->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->user->Visible) { // user ?>
		<th><span id="elh_tpinjaman_user" class="tpinjaman_user"><?php echo $tpinjaman->user->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->created->Visible) { // created ?>
		<th><span id="elh_tpinjaman_created" class="tpinjaman_created"><?php echo $tpinjaman->created->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tpinjaman->modified->Visible) { // modified ?>
		<th><span id="elh_tpinjaman_modified" class="tpinjaman_modified"><?php echo $tpinjaman->modified->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$tpinjaman_delete->RecCnt = 0;
$i = 0;
while (!$tpinjaman_delete->Recordset->EOF) {
	$tpinjaman_delete->RecCnt++;
	$tpinjaman_delete->RowCnt++;

	// Set row properties
	$tpinjaman->ResetAttrs();
	$tpinjaman->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$tpinjaman_delete->LoadRowValues($tpinjaman_delete->Recordset);

	// Render row
	$tpinjaman_delete->RenderRow();
?>
	<tr<?php echo $tpinjaman->RowAttributes() ?>>
<?php if ($tpinjaman->tanggal->Visible) { // tanggal ?>
		<td<?php echo $tpinjaman->tanggal->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_tanggal" class="tpinjaman_tanggal">
<span<?php echo $tpinjaman->tanggal->ViewAttributes() ?>>
<?php echo $tpinjaman->tanggal->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->periode->Visible) { // periode ?>
		<td<?php echo $tpinjaman->periode->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_periode" class="tpinjaman_periode">
<span<?php echo $tpinjaman->periode->ViewAttributes() ?>>
<?php echo $tpinjaman->periode->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->id->Visible) { // id ?>
		<td<?php echo $tpinjaman->id->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_id" class="tpinjaman_id">
<span<?php echo $tpinjaman->id->ViewAttributes() ?>>
<?php echo $tpinjaman->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->transaksi->Visible) { // transaksi ?>
		<td<?php echo $tpinjaman->transaksi->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_transaksi" class="tpinjaman_transaksi">
<span<?php echo $tpinjaman->transaksi->ViewAttributes() ?>>
<?php echo $tpinjaman->transaksi->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->referensi->Visible) { // referensi ?>
		<td<?php echo $tpinjaman->referensi->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_referensi" class="tpinjaman_referensi">
<span<?php echo $tpinjaman->referensi->ViewAttributes() ?>>
<?php echo $tpinjaman->referensi->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->anggota->Visible) { // anggota ?>
		<td<?php echo $tpinjaman->anggota->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_anggota" class="tpinjaman_anggota">
<span<?php echo $tpinjaman->anggota->ViewAttributes() ?>>
<?php echo $tpinjaman->anggota->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->namaanggota->Visible) { // namaanggota ?>
		<td<?php echo $tpinjaman->namaanggota->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_namaanggota" class="tpinjaman_namaanggota">
<span<?php echo $tpinjaman->namaanggota->ViewAttributes() ?>>
<?php echo $tpinjaman->namaanggota->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->alamat->Visible) { // alamat ?>
		<td<?php echo $tpinjaman->alamat->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_alamat" class="tpinjaman_alamat">
<span<?php echo $tpinjaman->alamat->ViewAttributes() ?>>
<?php echo $tpinjaman->alamat->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->pekerjaan->Visible) { // pekerjaan ?>
		<td<?php echo $tpinjaman->pekerjaan->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_pekerjaan" class="tpinjaman_pekerjaan">
<span<?php echo $tpinjaman->pekerjaan->ViewAttributes() ?>>
<?php echo $tpinjaman->pekerjaan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->telepon->Visible) { // telepon ?>
		<td<?php echo $tpinjaman->telepon->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_telepon" class="tpinjaman_telepon">
<span<?php echo $tpinjaman->telepon->ViewAttributes() ?>>
<?php echo $tpinjaman->telepon->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->hp->Visible) { // hp ?>
		<td<?php echo $tpinjaman->hp->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_hp" class="tpinjaman_hp">
<span<?php echo $tpinjaman->hp->ViewAttributes() ?>>
<?php echo $tpinjaman->hp->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->fax->Visible) { // fax ?>
		<td<?php echo $tpinjaman->fax->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_fax" class="tpinjaman_fax">
<span<?php echo $tpinjaman->fax->ViewAttributes() ?>>
<?php echo $tpinjaman->fax->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->_email->Visible) { // email ?>
		<td<?php echo $tpinjaman->_email->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman__email" class="tpinjaman__email">
<span<?php echo $tpinjaman->_email->ViewAttributes() ?>>
<?php echo $tpinjaman->_email->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->website->Visible) { // website ?>
		<td<?php echo $tpinjaman->website->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_website" class="tpinjaman_website">
<span<?php echo $tpinjaman->website->ViewAttributes() ?>>
<?php echo $tpinjaman->website->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->jenisanggota->Visible) { // jenisanggota ?>
		<td<?php echo $tpinjaman->jenisanggota->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_jenisanggota" class="tpinjaman_jenisanggota">
<span<?php echo $tpinjaman->jenisanggota->ViewAttributes() ?>>
<?php echo $tpinjaman->jenisanggota->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->model->Visible) { // model ?>
		<td<?php echo $tpinjaman->model->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_model" class="tpinjaman_model">
<span<?php echo $tpinjaman->model->ViewAttributes() ?>>
<?php echo $tpinjaman->model->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->jenispinjaman->Visible) { // jenispinjaman ?>
		<td<?php echo $tpinjaman->jenispinjaman->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_jenispinjaman" class="tpinjaman_jenispinjaman">
<span<?php echo $tpinjaman->jenispinjaman->ViewAttributes() ?>>
<?php echo $tpinjaman->jenispinjaman->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->jenisbunga->Visible) { // jenisbunga ?>
		<td<?php echo $tpinjaman->jenisbunga->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_jenisbunga" class="tpinjaman_jenisbunga">
<span<?php echo $tpinjaman->jenisbunga->ViewAttributes() ?>>
<?php echo $tpinjaman->jenisbunga->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->angsuran->Visible) { // angsuran ?>
		<td<?php echo $tpinjaman->angsuran->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_angsuran" class="tpinjaman_angsuran">
<span<?php echo $tpinjaman->angsuran->ViewAttributes() ?>>
<?php echo $tpinjaman->angsuran->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->masaangsuran->Visible) { // masaangsuran ?>
		<td<?php echo $tpinjaman->masaangsuran->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_masaangsuran" class="tpinjaman_masaangsuran">
<span<?php echo $tpinjaman->masaangsuran->ViewAttributes() ?>>
<?php echo $tpinjaman->masaangsuran->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->jatuhtempo->Visible) { // jatuhtempo ?>
		<td<?php echo $tpinjaman->jatuhtempo->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_jatuhtempo" class="tpinjaman_jatuhtempo">
<span<?php echo $tpinjaman->jatuhtempo->ViewAttributes() ?>>
<?php echo $tpinjaman->jatuhtempo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->dispensasidenda->Visible) { // dispensasidenda ?>
		<td<?php echo $tpinjaman->dispensasidenda->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_dispensasidenda" class="tpinjaman_dispensasidenda">
<span<?php echo $tpinjaman->dispensasidenda->ViewAttributes() ?>>
<?php echo $tpinjaman->dispensasidenda->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->agunan->Visible) { // agunan ?>
		<td<?php echo $tpinjaman->agunan->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_agunan" class="tpinjaman_agunan">
<span<?php echo $tpinjaman->agunan->ViewAttributes() ?>>
<?php echo $tpinjaman->agunan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->dataagunan1->Visible) { // dataagunan1 ?>
		<td<?php echo $tpinjaman->dataagunan1->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_dataagunan1" class="tpinjaman_dataagunan1">
<span<?php echo $tpinjaman->dataagunan1->ViewAttributes() ?>>
<?php echo $tpinjaman->dataagunan1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->dataagunan2->Visible) { // dataagunan2 ?>
		<td<?php echo $tpinjaman->dataagunan2->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_dataagunan2" class="tpinjaman_dataagunan2">
<span<?php echo $tpinjaman->dataagunan2->ViewAttributes() ?>>
<?php echo $tpinjaman->dataagunan2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->dataagunan3->Visible) { // dataagunan3 ?>
		<td<?php echo $tpinjaman->dataagunan3->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_dataagunan3" class="tpinjaman_dataagunan3">
<span<?php echo $tpinjaman->dataagunan3->ViewAttributes() ?>>
<?php echo $tpinjaman->dataagunan3->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->dataagunan4->Visible) { // dataagunan4 ?>
		<td<?php echo $tpinjaman->dataagunan4->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_dataagunan4" class="tpinjaman_dataagunan4">
<span<?php echo $tpinjaman->dataagunan4->ViewAttributes() ?>>
<?php echo $tpinjaman->dataagunan4->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->dataagunan5->Visible) { // dataagunan5 ?>
		<td<?php echo $tpinjaman->dataagunan5->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_dataagunan5" class="tpinjaman_dataagunan5">
<span<?php echo $tpinjaman->dataagunan5->ViewAttributes() ?>>
<?php echo $tpinjaman->dataagunan5->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->saldobekusimpanan->Visible) { // saldobekusimpanan ?>
		<td<?php echo $tpinjaman->saldobekusimpanan->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_saldobekusimpanan" class="tpinjaman_saldobekusimpanan">
<span<?php echo $tpinjaman->saldobekusimpanan->ViewAttributes() ?>>
<?php echo $tpinjaman->saldobekusimpanan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->saldobekuminimal->Visible) { // saldobekuminimal ?>
		<td<?php echo $tpinjaman->saldobekuminimal->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_saldobekuminimal" class="tpinjaman_saldobekuminimal">
<span<?php echo $tpinjaman->saldobekuminimal->ViewAttributes() ?>>
<?php echo $tpinjaman->saldobekuminimal->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->plafond->Visible) { // plafond ?>
		<td<?php echo $tpinjaman->plafond->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_plafond" class="tpinjaman_plafond">
<span<?php echo $tpinjaman->plafond->ViewAttributes() ?>>
<?php echo $tpinjaman->plafond->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->bunga->Visible) { // bunga ?>
		<td<?php echo $tpinjaman->bunga->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_bunga" class="tpinjaman_bunga">
<span<?php echo $tpinjaman->bunga->ViewAttributes() ?>>
<?php echo $tpinjaman->bunga->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->bungapersen->Visible) { // bungapersen ?>
		<td<?php echo $tpinjaman->bungapersen->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_bungapersen" class="tpinjaman_bungapersen">
<span<?php echo $tpinjaman->bungapersen->ViewAttributes() ?>>
<?php echo $tpinjaman->bungapersen->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->administrasi->Visible) { // administrasi ?>
		<td<?php echo $tpinjaman->administrasi->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_administrasi" class="tpinjaman_administrasi">
<span<?php echo $tpinjaman->administrasi->ViewAttributes() ?>>
<?php echo $tpinjaman->administrasi->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->administrasipersen->Visible) { // administrasipersen ?>
		<td<?php echo $tpinjaman->administrasipersen->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_administrasipersen" class="tpinjaman_administrasipersen">
<span<?php echo $tpinjaman->administrasipersen->ViewAttributes() ?>>
<?php echo $tpinjaman->administrasipersen->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->asuransi->Visible) { // asuransi ?>
		<td<?php echo $tpinjaman->asuransi->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_asuransi" class="tpinjaman_asuransi">
<span<?php echo $tpinjaman->asuransi->ViewAttributes() ?>>
<?php echo $tpinjaman->asuransi->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->notaris->Visible) { // notaris ?>
		<td<?php echo $tpinjaman->notaris->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_notaris" class="tpinjaman_notaris">
<span<?php echo $tpinjaman->notaris->ViewAttributes() ?>>
<?php echo $tpinjaman->notaris->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->biayamaterai->Visible) { // biayamaterai ?>
		<td<?php echo $tpinjaman->biayamaterai->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_biayamaterai" class="tpinjaman_biayamaterai">
<span<?php echo $tpinjaman->biayamaterai->ViewAttributes() ?>>
<?php echo $tpinjaman->biayamaterai->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->potongansaldobeku->Visible) { // potongansaldobeku ?>
		<td<?php echo $tpinjaman->potongansaldobeku->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_potongansaldobeku" class="tpinjaman_potongansaldobeku">
<span<?php echo $tpinjaman->potongansaldobeku->ViewAttributes() ?>>
<?php echo $tpinjaman->potongansaldobeku->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->angsuranpokok->Visible) { // angsuranpokok ?>
		<td<?php echo $tpinjaman->angsuranpokok->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_angsuranpokok" class="tpinjaman_angsuranpokok">
<span<?php echo $tpinjaman->angsuranpokok->ViewAttributes() ?>>
<?php echo $tpinjaman->angsuranpokok->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->angsuranpokokauto->Visible) { // angsuranpokokauto ?>
		<td<?php echo $tpinjaman->angsuranpokokauto->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_angsuranpokokauto" class="tpinjaman_angsuranpokokauto">
<span<?php echo $tpinjaman->angsuranpokokauto->ViewAttributes() ?>>
<?php echo $tpinjaman->angsuranpokokauto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->angsuranbunga->Visible) { // angsuranbunga ?>
		<td<?php echo $tpinjaman->angsuranbunga->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_angsuranbunga" class="tpinjaman_angsuranbunga">
<span<?php echo $tpinjaman->angsuranbunga->ViewAttributes() ?>>
<?php echo $tpinjaman->angsuranbunga->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->angsuranbungaauto->Visible) { // angsuranbungaauto ?>
		<td<?php echo $tpinjaman->angsuranbungaauto->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_angsuranbungaauto" class="tpinjaman_angsuranbungaauto">
<span<?php echo $tpinjaman->angsuranbungaauto->ViewAttributes() ?>>
<?php echo $tpinjaman->angsuranbungaauto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->denda->Visible) { // denda ?>
		<td<?php echo $tpinjaman->denda->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_denda" class="tpinjaman_denda">
<span<?php echo $tpinjaman->denda->ViewAttributes() ?>>
<?php echo $tpinjaman->denda->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->dendapersen->Visible) { // dendapersen ?>
		<td<?php echo $tpinjaman->dendapersen->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_dendapersen" class="tpinjaman_dendapersen">
<span<?php echo $tpinjaman->dendapersen->ViewAttributes() ?>>
<?php echo $tpinjaman->dendapersen->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->totalangsuran->Visible) { // totalangsuran ?>
		<td<?php echo $tpinjaman->totalangsuran->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_totalangsuran" class="tpinjaman_totalangsuran">
<span<?php echo $tpinjaman->totalangsuran->ViewAttributes() ?>>
<?php echo $tpinjaman->totalangsuran->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->totalangsuranauto->Visible) { // totalangsuranauto ?>
		<td<?php echo $tpinjaman->totalangsuranauto->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_totalangsuranauto" class="tpinjaman_totalangsuranauto">
<span<?php echo $tpinjaman->totalangsuranauto->ViewAttributes() ?>>
<?php echo $tpinjaman->totalangsuranauto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->totalterima->Visible) { // totalterima ?>
		<td<?php echo $tpinjaman->totalterima->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_totalterima" class="tpinjaman_totalterima">
<span<?php echo $tpinjaman->totalterima->ViewAttributes() ?>>
<?php echo $tpinjaman->totalterima->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->totalterimaauto->Visible) { // totalterimaauto ?>
		<td<?php echo $tpinjaman->totalterimaauto->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_totalterimaauto" class="tpinjaman_totalterimaauto">
<span<?php echo $tpinjaman->totalterimaauto->ViewAttributes() ?>>
<?php echo $tpinjaman->totalterimaauto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->terbilang->Visible) { // terbilang ?>
		<td<?php echo $tpinjaman->terbilang->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_terbilang" class="tpinjaman_terbilang">
<span<?php echo $tpinjaman->terbilang->ViewAttributes() ?>>
<?php echo $tpinjaman->terbilang->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->petugas->Visible) { // petugas ?>
		<td<?php echo $tpinjaman->petugas->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_petugas" class="tpinjaman_petugas">
<span<?php echo $tpinjaman->petugas->ViewAttributes() ?>>
<?php echo $tpinjaman->petugas->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->pembayaran->Visible) { // pembayaran ?>
		<td<?php echo $tpinjaman->pembayaran->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_pembayaran" class="tpinjaman_pembayaran">
<span<?php echo $tpinjaman->pembayaran->ViewAttributes() ?>>
<?php echo $tpinjaman->pembayaran->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->bank->Visible) { // bank ?>
		<td<?php echo $tpinjaman->bank->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_bank" class="tpinjaman_bank">
<span<?php echo $tpinjaman->bank->ViewAttributes() ?>>
<?php echo $tpinjaman->bank->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->atasnama->Visible) { // atasnama ?>
		<td<?php echo $tpinjaman->atasnama->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_atasnama" class="tpinjaman_atasnama">
<span<?php echo $tpinjaman->atasnama->ViewAttributes() ?>>
<?php echo $tpinjaman->atasnama->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->tipe->Visible) { // tipe ?>
		<td<?php echo $tpinjaman->tipe->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_tipe" class="tpinjaman_tipe">
<span<?php echo $tpinjaman->tipe->ViewAttributes() ?>>
<?php echo $tpinjaman->tipe->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->kantor->Visible) { // kantor ?>
		<td<?php echo $tpinjaman->kantor->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_kantor" class="tpinjaman_kantor">
<span<?php echo $tpinjaman->kantor->ViewAttributes() ?>>
<?php echo $tpinjaman->kantor->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->keterangan->Visible) { // keterangan ?>
		<td<?php echo $tpinjaman->keterangan->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_keterangan" class="tpinjaman_keterangan">
<span<?php echo $tpinjaman->keterangan->ViewAttributes() ?>>
<?php echo $tpinjaman->keterangan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->active->Visible) { // active ?>
		<td<?php echo $tpinjaman->active->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_active" class="tpinjaman_active">
<span<?php echo $tpinjaman->active->ViewAttributes() ?>>
<?php echo $tpinjaman->active->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->ip->Visible) { // ip ?>
		<td<?php echo $tpinjaman->ip->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_ip" class="tpinjaman_ip">
<span<?php echo $tpinjaman->ip->ViewAttributes() ?>>
<?php echo $tpinjaman->ip->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->status->Visible) { // status ?>
		<td<?php echo $tpinjaman->status->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_status" class="tpinjaman_status">
<span<?php echo $tpinjaman->status->ViewAttributes() ?>>
<?php echo $tpinjaman->status->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->user->Visible) { // user ?>
		<td<?php echo $tpinjaman->user->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_user" class="tpinjaman_user">
<span<?php echo $tpinjaman->user->ViewAttributes() ?>>
<?php echo $tpinjaman->user->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->created->Visible) { // created ?>
		<td<?php echo $tpinjaman->created->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_created" class="tpinjaman_created">
<span<?php echo $tpinjaman->created->ViewAttributes() ?>>
<?php echo $tpinjaman->created->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tpinjaman->modified->Visible) { // modified ?>
		<td<?php echo $tpinjaman->modified->CellAttributes() ?>>
<span id="el<?php echo $tpinjaman_delete->RowCnt ?>_tpinjaman_modified" class="tpinjaman_modified">
<span<?php echo $tpinjaman->modified->ViewAttributes() ?>>
<?php echo $tpinjaman->modified->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$tpinjaman_delete->Recordset->MoveNext();
}
$tpinjaman_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $tpinjaman_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
ftpinjamandelete.Init();
</script>
<?php
$tpinjaman_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tpinjaman_delete->Page_Terminate();
?>
