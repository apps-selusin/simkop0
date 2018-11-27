<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "tanggotainfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$tanggota_delete = NULL; // Initialize page object first

class ctanggota_delete extends ctanggota {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{CC9F4FC0-1C64-4EFD-9A35-42F5D47E54FF}";

	// Table name
	var $TableName = 'tanggota';

	// Page object name
	var $PageObjName = 'tanggota_delete';

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

		// Table object (tanggota)
		if (!isset($GLOBALS["tanggota"]) || get_class($GLOBALS["tanggota"]) == "ctanggota") {
			$GLOBALS["tanggota"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tanggota"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tanggota', TRUE);

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
		$this->registrasi->SetVisibility();
		$this->periode->SetVisibility();
		$this->id->SetVisibility();
		$this->anggota->SetVisibility();
		$this->namaanggota->SetVisibility();
		$this->alamat->SetVisibility();
		$this->tempatlahir->SetVisibility();
		$this->tanggallahir->SetVisibility();
		$this->kelamin->SetVisibility();
		$this->pekerjaan->SetVisibility();
		$this->telepon->SetVisibility();
		$this->hp->SetVisibility();
		$this->fax->SetVisibility();
		$this->_email->SetVisibility();
		$this->website->SetVisibility();
		$this->jenisanggota->SetVisibility();
		$this->model->SetVisibility();
		$this->namakantor->SetVisibility();
		$this->alamatkantor->SetVisibility();
		$this->wilayah->SetVisibility();
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
		global $EW_EXPORT, $tanggota;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($tanggota);
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
			$this->Page_Terminate("tanggotalist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in tanggota class, tanggotainfo.php

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
				$this->Page_Terminate("tanggotalist.php"); // Return to list
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
		$this->registrasi->setDbValue($rs->fields('registrasi'));
		$this->periode->setDbValue($rs->fields('periode'));
		$this->id->setDbValue($rs->fields('id'));
		$this->anggota->setDbValue($rs->fields('anggota'));
		$this->namaanggota->setDbValue($rs->fields('namaanggota'));
		$this->alamat->setDbValue($rs->fields('alamat'));
		$this->tempatlahir->setDbValue($rs->fields('tempatlahir'));
		$this->tanggallahir->setDbValue($rs->fields('tanggallahir'));
		$this->kelamin->setDbValue($rs->fields('kelamin'));
		$this->pekerjaan->setDbValue($rs->fields('pekerjaan'));
		$this->telepon->setDbValue($rs->fields('telepon'));
		$this->hp->setDbValue($rs->fields('hp'));
		$this->fax->setDbValue($rs->fields('fax'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->website->setDbValue($rs->fields('website'));
		$this->jenisanggota->setDbValue($rs->fields('jenisanggota'));
		$this->model->setDbValue($rs->fields('model'));
		$this->namakantor->setDbValue($rs->fields('namakantor'));
		$this->alamatkantor->setDbValue($rs->fields('alamatkantor'));
		$this->wilayah->setDbValue($rs->fields('wilayah'));
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
		$this->registrasi->DbValue = $row['registrasi'];
		$this->periode->DbValue = $row['periode'];
		$this->id->DbValue = $row['id'];
		$this->anggota->DbValue = $row['anggota'];
		$this->namaanggota->DbValue = $row['namaanggota'];
		$this->alamat->DbValue = $row['alamat'];
		$this->tempatlahir->DbValue = $row['tempatlahir'];
		$this->tanggallahir->DbValue = $row['tanggallahir'];
		$this->kelamin->DbValue = $row['kelamin'];
		$this->pekerjaan->DbValue = $row['pekerjaan'];
		$this->telepon->DbValue = $row['telepon'];
		$this->hp->DbValue = $row['hp'];
		$this->fax->DbValue = $row['fax'];
		$this->_email->DbValue = $row['email'];
		$this->website->DbValue = $row['website'];
		$this->jenisanggota->DbValue = $row['jenisanggota'];
		$this->model->DbValue = $row['model'];
		$this->namakantor->DbValue = $row['namakantor'];
		$this->alamatkantor->DbValue = $row['alamatkantor'];
		$this->wilayah->DbValue = $row['wilayah'];
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
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// registrasi
		// periode
		// id
		// anggota
		// namaanggota
		// alamat
		// tempatlahir
		// tanggallahir
		// kelamin
		// pekerjaan
		// telepon
		// hp
		// fax
		// email
		// website
		// jenisanggota
		// model
		// namakantor
		// alamatkantor
		// wilayah
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

		// registrasi
		$this->registrasi->ViewValue = $this->registrasi->CurrentValue;
		$this->registrasi->ViewValue = ew_FormatDateTime($this->registrasi->ViewValue, 0);
		$this->registrasi->ViewCustomAttributes = "";

		// periode
		$this->periode->ViewValue = $this->periode->CurrentValue;
		$this->periode->ViewCustomAttributes = "";

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// anggota
		$this->anggota->ViewValue = $this->anggota->CurrentValue;
		$this->anggota->ViewCustomAttributes = "";

		// namaanggota
		$this->namaanggota->ViewValue = $this->namaanggota->CurrentValue;
		$this->namaanggota->ViewCustomAttributes = "";

		// alamat
		$this->alamat->ViewValue = $this->alamat->CurrentValue;
		$this->alamat->ViewCustomAttributes = "";

		// tempatlahir
		$this->tempatlahir->ViewValue = $this->tempatlahir->CurrentValue;
		$this->tempatlahir->ViewCustomAttributes = "";

		// tanggallahir
		$this->tanggallahir->ViewValue = $this->tanggallahir->CurrentValue;
		$this->tanggallahir->ViewValue = ew_FormatDateTime($this->tanggallahir->ViewValue, 0);
		$this->tanggallahir->ViewCustomAttributes = "";

		// kelamin
		$this->kelamin->ViewValue = $this->kelamin->CurrentValue;
		$this->kelamin->ViewCustomAttributes = "";

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

		// namakantor
		$this->namakantor->ViewValue = $this->namakantor->CurrentValue;
		$this->namakantor->ViewCustomAttributes = "";

		// alamatkantor
		$this->alamatkantor->ViewValue = $this->alamatkantor->CurrentValue;
		$this->alamatkantor->ViewCustomAttributes = "";

		// wilayah
		$this->wilayah->ViewValue = $this->wilayah->CurrentValue;
		$this->wilayah->ViewCustomAttributes = "";

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

			// registrasi
			$this->registrasi->LinkCustomAttributes = "";
			$this->registrasi->HrefValue = "";
			$this->registrasi->TooltipValue = "";

			// periode
			$this->periode->LinkCustomAttributes = "";
			$this->periode->HrefValue = "";
			$this->periode->TooltipValue = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

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

			// tempatlahir
			$this->tempatlahir->LinkCustomAttributes = "";
			$this->tempatlahir->HrefValue = "";
			$this->tempatlahir->TooltipValue = "";

			// tanggallahir
			$this->tanggallahir->LinkCustomAttributes = "";
			$this->tanggallahir->HrefValue = "";
			$this->tanggallahir->TooltipValue = "";

			// kelamin
			$this->kelamin->LinkCustomAttributes = "";
			$this->kelamin->HrefValue = "";
			$this->kelamin->TooltipValue = "";

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

			// namakantor
			$this->namakantor->LinkCustomAttributes = "";
			$this->namakantor->HrefValue = "";
			$this->namakantor->TooltipValue = "";

			// alamatkantor
			$this->alamatkantor->LinkCustomAttributes = "";
			$this->alamatkantor->HrefValue = "";
			$this->alamatkantor->TooltipValue = "";

			// wilayah
			$this->wilayah->LinkCustomAttributes = "";
			$this->wilayah->HrefValue = "";
			$this->wilayah->TooltipValue = "";

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("tanggotalist.php"), "", $this->TableVar, TRUE);
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
if (!isset($tanggota_delete)) $tanggota_delete = new ctanggota_delete();

// Page init
$tanggota_delete->Page_Init();

// Page main
$tanggota_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tanggota_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = ftanggotadelete = new ew_Form("ftanggotadelete", "delete");

// Form_CustomValidate event
ftanggotadelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftanggotadelete.ValidateRequired = true;
<?php } else { ?>
ftanggotadelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftanggotadelete.Lists["x_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ftanggotadelete.Lists["x_active"].Options = <?php echo json_encode($tanggota->active->Options()) ?>;

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
<?php $tanggota_delete->ShowPageHeader(); ?>
<?php
$tanggota_delete->ShowMessage();
?>
<form name="ftanggotadelete" id="ftanggotadelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tanggota_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tanggota_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tanggota">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($tanggota_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $tanggota->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($tanggota->registrasi->Visible) { // registrasi ?>
		<th><span id="elh_tanggota_registrasi" class="tanggota_registrasi"><?php echo $tanggota->registrasi->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->periode->Visible) { // periode ?>
		<th><span id="elh_tanggota_periode" class="tanggota_periode"><?php echo $tanggota->periode->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->id->Visible) { // id ?>
		<th><span id="elh_tanggota_id" class="tanggota_id"><?php echo $tanggota->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->anggota->Visible) { // anggota ?>
		<th><span id="elh_tanggota_anggota" class="tanggota_anggota"><?php echo $tanggota->anggota->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->namaanggota->Visible) { // namaanggota ?>
		<th><span id="elh_tanggota_namaanggota" class="tanggota_namaanggota"><?php echo $tanggota->namaanggota->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->alamat->Visible) { // alamat ?>
		<th><span id="elh_tanggota_alamat" class="tanggota_alamat"><?php echo $tanggota->alamat->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->tempatlahir->Visible) { // tempatlahir ?>
		<th><span id="elh_tanggota_tempatlahir" class="tanggota_tempatlahir"><?php echo $tanggota->tempatlahir->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->tanggallahir->Visible) { // tanggallahir ?>
		<th><span id="elh_tanggota_tanggallahir" class="tanggota_tanggallahir"><?php echo $tanggota->tanggallahir->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->kelamin->Visible) { // kelamin ?>
		<th><span id="elh_tanggota_kelamin" class="tanggota_kelamin"><?php echo $tanggota->kelamin->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->pekerjaan->Visible) { // pekerjaan ?>
		<th><span id="elh_tanggota_pekerjaan" class="tanggota_pekerjaan"><?php echo $tanggota->pekerjaan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->telepon->Visible) { // telepon ?>
		<th><span id="elh_tanggota_telepon" class="tanggota_telepon"><?php echo $tanggota->telepon->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->hp->Visible) { // hp ?>
		<th><span id="elh_tanggota_hp" class="tanggota_hp"><?php echo $tanggota->hp->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->fax->Visible) { // fax ?>
		<th><span id="elh_tanggota_fax" class="tanggota_fax"><?php echo $tanggota->fax->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->_email->Visible) { // email ?>
		<th><span id="elh_tanggota__email" class="tanggota__email"><?php echo $tanggota->_email->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->website->Visible) { // website ?>
		<th><span id="elh_tanggota_website" class="tanggota_website"><?php echo $tanggota->website->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->jenisanggota->Visible) { // jenisanggota ?>
		<th><span id="elh_tanggota_jenisanggota" class="tanggota_jenisanggota"><?php echo $tanggota->jenisanggota->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->model->Visible) { // model ?>
		<th><span id="elh_tanggota_model" class="tanggota_model"><?php echo $tanggota->model->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->namakantor->Visible) { // namakantor ?>
		<th><span id="elh_tanggota_namakantor" class="tanggota_namakantor"><?php echo $tanggota->namakantor->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->alamatkantor->Visible) { // alamatkantor ?>
		<th><span id="elh_tanggota_alamatkantor" class="tanggota_alamatkantor"><?php echo $tanggota->alamatkantor->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->wilayah->Visible) { // wilayah ?>
		<th><span id="elh_tanggota_wilayah" class="tanggota_wilayah"><?php echo $tanggota->wilayah->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->petugas->Visible) { // petugas ?>
		<th><span id="elh_tanggota_petugas" class="tanggota_petugas"><?php echo $tanggota->petugas->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->pembayaran->Visible) { // pembayaran ?>
		<th><span id="elh_tanggota_pembayaran" class="tanggota_pembayaran"><?php echo $tanggota->pembayaran->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->bank->Visible) { // bank ?>
		<th><span id="elh_tanggota_bank" class="tanggota_bank"><?php echo $tanggota->bank->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->atasnama->Visible) { // atasnama ?>
		<th><span id="elh_tanggota_atasnama" class="tanggota_atasnama"><?php echo $tanggota->atasnama->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->tipe->Visible) { // tipe ?>
		<th><span id="elh_tanggota_tipe" class="tanggota_tipe"><?php echo $tanggota->tipe->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->kantor->Visible) { // kantor ?>
		<th><span id="elh_tanggota_kantor" class="tanggota_kantor"><?php echo $tanggota->kantor->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->keterangan->Visible) { // keterangan ?>
		<th><span id="elh_tanggota_keterangan" class="tanggota_keterangan"><?php echo $tanggota->keterangan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->active->Visible) { // active ?>
		<th><span id="elh_tanggota_active" class="tanggota_active"><?php echo $tanggota->active->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->ip->Visible) { // ip ?>
		<th><span id="elh_tanggota_ip" class="tanggota_ip"><?php echo $tanggota->ip->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->status->Visible) { // status ?>
		<th><span id="elh_tanggota_status" class="tanggota_status"><?php echo $tanggota->status->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->user->Visible) { // user ?>
		<th><span id="elh_tanggota_user" class="tanggota_user"><?php echo $tanggota->user->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->created->Visible) { // created ?>
		<th><span id="elh_tanggota_created" class="tanggota_created"><?php echo $tanggota->created->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tanggota->modified->Visible) { // modified ?>
		<th><span id="elh_tanggota_modified" class="tanggota_modified"><?php echo $tanggota->modified->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$tanggota_delete->RecCnt = 0;
$i = 0;
while (!$tanggota_delete->Recordset->EOF) {
	$tanggota_delete->RecCnt++;
	$tanggota_delete->RowCnt++;

	// Set row properties
	$tanggota->ResetAttrs();
	$tanggota->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$tanggota_delete->LoadRowValues($tanggota_delete->Recordset);

	// Render row
	$tanggota_delete->RenderRow();
?>
	<tr<?php echo $tanggota->RowAttributes() ?>>
<?php if ($tanggota->registrasi->Visible) { // registrasi ?>
		<td<?php echo $tanggota->registrasi->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_registrasi" class="tanggota_registrasi">
<span<?php echo $tanggota->registrasi->ViewAttributes() ?>>
<?php echo $tanggota->registrasi->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->periode->Visible) { // periode ?>
		<td<?php echo $tanggota->periode->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_periode" class="tanggota_periode">
<span<?php echo $tanggota->periode->ViewAttributes() ?>>
<?php echo $tanggota->periode->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->id->Visible) { // id ?>
		<td<?php echo $tanggota->id->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_id" class="tanggota_id">
<span<?php echo $tanggota->id->ViewAttributes() ?>>
<?php echo $tanggota->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->anggota->Visible) { // anggota ?>
		<td<?php echo $tanggota->anggota->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_anggota" class="tanggota_anggota">
<span<?php echo $tanggota->anggota->ViewAttributes() ?>>
<?php echo $tanggota->anggota->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->namaanggota->Visible) { // namaanggota ?>
		<td<?php echo $tanggota->namaanggota->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_namaanggota" class="tanggota_namaanggota">
<span<?php echo $tanggota->namaanggota->ViewAttributes() ?>>
<?php echo $tanggota->namaanggota->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->alamat->Visible) { // alamat ?>
		<td<?php echo $tanggota->alamat->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_alamat" class="tanggota_alamat">
<span<?php echo $tanggota->alamat->ViewAttributes() ?>>
<?php echo $tanggota->alamat->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->tempatlahir->Visible) { // tempatlahir ?>
		<td<?php echo $tanggota->tempatlahir->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_tempatlahir" class="tanggota_tempatlahir">
<span<?php echo $tanggota->tempatlahir->ViewAttributes() ?>>
<?php echo $tanggota->tempatlahir->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->tanggallahir->Visible) { // tanggallahir ?>
		<td<?php echo $tanggota->tanggallahir->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_tanggallahir" class="tanggota_tanggallahir">
<span<?php echo $tanggota->tanggallahir->ViewAttributes() ?>>
<?php echo $tanggota->tanggallahir->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->kelamin->Visible) { // kelamin ?>
		<td<?php echo $tanggota->kelamin->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_kelamin" class="tanggota_kelamin">
<span<?php echo $tanggota->kelamin->ViewAttributes() ?>>
<?php echo $tanggota->kelamin->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->pekerjaan->Visible) { // pekerjaan ?>
		<td<?php echo $tanggota->pekerjaan->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_pekerjaan" class="tanggota_pekerjaan">
<span<?php echo $tanggota->pekerjaan->ViewAttributes() ?>>
<?php echo $tanggota->pekerjaan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->telepon->Visible) { // telepon ?>
		<td<?php echo $tanggota->telepon->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_telepon" class="tanggota_telepon">
<span<?php echo $tanggota->telepon->ViewAttributes() ?>>
<?php echo $tanggota->telepon->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->hp->Visible) { // hp ?>
		<td<?php echo $tanggota->hp->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_hp" class="tanggota_hp">
<span<?php echo $tanggota->hp->ViewAttributes() ?>>
<?php echo $tanggota->hp->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->fax->Visible) { // fax ?>
		<td<?php echo $tanggota->fax->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_fax" class="tanggota_fax">
<span<?php echo $tanggota->fax->ViewAttributes() ?>>
<?php echo $tanggota->fax->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->_email->Visible) { // email ?>
		<td<?php echo $tanggota->_email->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota__email" class="tanggota__email">
<span<?php echo $tanggota->_email->ViewAttributes() ?>>
<?php echo $tanggota->_email->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->website->Visible) { // website ?>
		<td<?php echo $tanggota->website->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_website" class="tanggota_website">
<span<?php echo $tanggota->website->ViewAttributes() ?>>
<?php echo $tanggota->website->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->jenisanggota->Visible) { // jenisanggota ?>
		<td<?php echo $tanggota->jenisanggota->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_jenisanggota" class="tanggota_jenisanggota">
<span<?php echo $tanggota->jenisanggota->ViewAttributes() ?>>
<?php echo $tanggota->jenisanggota->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->model->Visible) { // model ?>
		<td<?php echo $tanggota->model->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_model" class="tanggota_model">
<span<?php echo $tanggota->model->ViewAttributes() ?>>
<?php echo $tanggota->model->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->namakantor->Visible) { // namakantor ?>
		<td<?php echo $tanggota->namakantor->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_namakantor" class="tanggota_namakantor">
<span<?php echo $tanggota->namakantor->ViewAttributes() ?>>
<?php echo $tanggota->namakantor->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->alamatkantor->Visible) { // alamatkantor ?>
		<td<?php echo $tanggota->alamatkantor->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_alamatkantor" class="tanggota_alamatkantor">
<span<?php echo $tanggota->alamatkantor->ViewAttributes() ?>>
<?php echo $tanggota->alamatkantor->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->wilayah->Visible) { // wilayah ?>
		<td<?php echo $tanggota->wilayah->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_wilayah" class="tanggota_wilayah">
<span<?php echo $tanggota->wilayah->ViewAttributes() ?>>
<?php echo $tanggota->wilayah->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->petugas->Visible) { // petugas ?>
		<td<?php echo $tanggota->petugas->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_petugas" class="tanggota_petugas">
<span<?php echo $tanggota->petugas->ViewAttributes() ?>>
<?php echo $tanggota->petugas->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->pembayaran->Visible) { // pembayaran ?>
		<td<?php echo $tanggota->pembayaran->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_pembayaran" class="tanggota_pembayaran">
<span<?php echo $tanggota->pembayaran->ViewAttributes() ?>>
<?php echo $tanggota->pembayaran->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->bank->Visible) { // bank ?>
		<td<?php echo $tanggota->bank->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_bank" class="tanggota_bank">
<span<?php echo $tanggota->bank->ViewAttributes() ?>>
<?php echo $tanggota->bank->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->atasnama->Visible) { // atasnama ?>
		<td<?php echo $tanggota->atasnama->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_atasnama" class="tanggota_atasnama">
<span<?php echo $tanggota->atasnama->ViewAttributes() ?>>
<?php echo $tanggota->atasnama->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->tipe->Visible) { // tipe ?>
		<td<?php echo $tanggota->tipe->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_tipe" class="tanggota_tipe">
<span<?php echo $tanggota->tipe->ViewAttributes() ?>>
<?php echo $tanggota->tipe->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->kantor->Visible) { // kantor ?>
		<td<?php echo $tanggota->kantor->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_kantor" class="tanggota_kantor">
<span<?php echo $tanggota->kantor->ViewAttributes() ?>>
<?php echo $tanggota->kantor->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->keterangan->Visible) { // keterangan ?>
		<td<?php echo $tanggota->keterangan->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_keterangan" class="tanggota_keterangan">
<span<?php echo $tanggota->keterangan->ViewAttributes() ?>>
<?php echo $tanggota->keterangan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->active->Visible) { // active ?>
		<td<?php echo $tanggota->active->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_active" class="tanggota_active">
<span<?php echo $tanggota->active->ViewAttributes() ?>>
<?php echo $tanggota->active->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->ip->Visible) { // ip ?>
		<td<?php echo $tanggota->ip->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_ip" class="tanggota_ip">
<span<?php echo $tanggota->ip->ViewAttributes() ?>>
<?php echo $tanggota->ip->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->status->Visible) { // status ?>
		<td<?php echo $tanggota->status->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_status" class="tanggota_status">
<span<?php echo $tanggota->status->ViewAttributes() ?>>
<?php echo $tanggota->status->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->user->Visible) { // user ?>
		<td<?php echo $tanggota->user->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_user" class="tanggota_user">
<span<?php echo $tanggota->user->ViewAttributes() ?>>
<?php echo $tanggota->user->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->created->Visible) { // created ?>
		<td<?php echo $tanggota->created->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_created" class="tanggota_created">
<span<?php echo $tanggota->created->ViewAttributes() ?>>
<?php echo $tanggota->created->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tanggota->modified->Visible) { // modified ?>
		<td<?php echo $tanggota->modified->CellAttributes() ?>>
<span id="el<?php echo $tanggota_delete->RowCnt ?>_tanggota_modified" class="tanggota_modified">
<span<?php echo $tanggota->modified->ViewAttributes() ?>>
<?php echo $tanggota->modified->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$tanggota_delete->Recordset->MoveNext();
}
$tanggota_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $tanggota_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
ftanggotadelete.Init();
</script>
<?php
$tanggota_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tanggota_delete->Page_Terminate();
?>
