<?php

// Global variable for table object
$tpinjamandetail = NULL;

//
// Table class for tpinjamandetail
//
class ctpinjamandetail extends cTable {
	var $tanggal;
	var $periode;
	var $id;
	var $angsuran;
	var $masaangsuran;
	var $dispensasidenda;
	var $plafond;
	var $angsuranpokok;
	var $angsuranpokokauto;
	var $angsuranbunga;
	var $angsuranbungaauto;
	var $denda;
	var $dendapersen;
	var $totalangsuran;
	var $totalangsuranauto;
	var $sisaangsuran;
	var $sisaangsuranauto;
	var $tanggalbayar;
	var $terlambat;
	var $bayarpokok;
	var $bayarpokokauto;
	var $bayarbunga;
	var $bayarbungaauto;
	var $bayardenda;
	var $bayardendaauto;
	var $bayartitipan;
	var $bayartitipanauto;
	var $totalbayar;
	var $totalbayarauto;
	var $pelunasan;
	var $pelunasanauto;
	var $finalty;
	var $finaltyauto;
	var $status;
	var $keterangan;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'tpinjamandetail';
		$this->TableName = 'tpinjamandetail';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`tpinjamandetail`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// tanggal
		$this->tanggal = new cField('tpinjamandetail', 'tpinjamandetail', 'x_tanggal', 'tanggal', '`tanggal`', ew_CastDateFieldForLike('`tanggal`', 0, "DB"), 135, 0, FALSE, '`tanggal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tanggal->Sortable = TRUE; // Allow sort
		$this->tanggal->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['tanggal'] = &$this->tanggal;

		// periode
		$this->periode = new cField('tpinjamandetail', 'tpinjamandetail', 'x_periode', 'periode', '`periode`', '`periode`', 200, -1, FALSE, '`periode`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->periode->Sortable = TRUE; // Allow sort
		$this->fields['periode'] = &$this->periode;

		// id
		$this->id = new cField('tpinjamandetail', 'tpinjamandetail', 'x_id', 'id', '`id`', '`id`', 200, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->id->Sortable = TRUE; // Allow sort
		$this->fields['id'] = &$this->id;

		// angsuran
		$this->angsuran = new cField('tpinjamandetail', 'tpinjamandetail', 'x_angsuran', 'angsuran', '`angsuran`', '`angsuran`', 20, -1, FALSE, '`angsuran`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->angsuran->Sortable = TRUE; // Allow sort
		$this->angsuran->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['angsuran'] = &$this->angsuran;

		// masaangsuran
		$this->masaangsuran = new cField('tpinjamandetail', 'tpinjamandetail', 'x_masaangsuran', 'masaangsuran', '`masaangsuran`', '`masaangsuran`', 200, -1, FALSE, '`masaangsuran`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->masaangsuran->Sortable = TRUE; // Allow sort
		$this->fields['masaangsuran'] = &$this->masaangsuran;

		// dispensasidenda
		$this->dispensasidenda = new cField('tpinjamandetail', 'tpinjamandetail', 'x_dispensasidenda', 'dispensasidenda', '`dispensasidenda`', '`dispensasidenda`', 20, -1, FALSE, '`dispensasidenda`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->dispensasidenda->Sortable = TRUE; // Allow sort
		$this->dispensasidenda->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['dispensasidenda'] = &$this->dispensasidenda;

		// plafond
		$this->plafond = new cField('tpinjamandetail', 'tpinjamandetail', 'x_plafond', 'plafond', '`plafond`', '`plafond`', 5, -1, FALSE, '`plafond`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->plafond->Sortable = TRUE; // Allow sort
		$this->plafond->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['plafond'] = &$this->plafond;

		// angsuranpokok
		$this->angsuranpokok = new cField('tpinjamandetail', 'tpinjamandetail', 'x_angsuranpokok', 'angsuranpokok', '`angsuranpokok`', '`angsuranpokok`', 5, -1, FALSE, '`angsuranpokok`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->angsuranpokok->Sortable = TRUE; // Allow sort
		$this->angsuranpokok->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['angsuranpokok'] = &$this->angsuranpokok;

		// angsuranpokokauto
		$this->angsuranpokokauto = new cField('tpinjamandetail', 'tpinjamandetail', 'x_angsuranpokokauto', 'angsuranpokokauto', '`angsuranpokokauto`', '`angsuranpokokauto`', 5, -1, FALSE, '`angsuranpokokauto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->angsuranpokokauto->Sortable = TRUE; // Allow sort
		$this->angsuranpokokauto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['angsuranpokokauto'] = &$this->angsuranpokokauto;

		// angsuranbunga
		$this->angsuranbunga = new cField('tpinjamandetail', 'tpinjamandetail', 'x_angsuranbunga', 'angsuranbunga', '`angsuranbunga`', '`angsuranbunga`', 5, -1, FALSE, '`angsuranbunga`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->angsuranbunga->Sortable = TRUE; // Allow sort
		$this->angsuranbunga->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['angsuranbunga'] = &$this->angsuranbunga;

		// angsuranbungaauto
		$this->angsuranbungaauto = new cField('tpinjamandetail', 'tpinjamandetail', 'x_angsuranbungaauto', 'angsuranbungaauto', '`angsuranbungaauto`', '`angsuranbungaauto`', 5, -1, FALSE, '`angsuranbungaauto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->angsuranbungaauto->Sortable = TRUE; // Allow sort
		$this->angsuranbungaauto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['angsuranbungaauto'] = &$this->angsuranbungaauto;

		// denda
		$this->denda = new cField('tpinjamandetail', 'tpinjamandetail', 'x_denda', 'denda', '`denda`', '`denda`', 5, -1, FALSE, '`denda`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->denda->Sortable = TRUE; // Allow sort
		$this->denda->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['denda'] = &$this->denda;

		// dendapersen
		$this->dendapersen = new cField('tpinjamandetail', 'tpinjamandetail', 'x_dendapersen', 'dendapersen', '`dendapersen`', '`dendapersen`', 5, -1, FALSE, '`dendapersen`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->dendapersen->Sortable = TRUE; // Allow sort
		$this->dendapersen->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['dendapersen'] = &$this->dendapersen;

		// totalangsuran
		$this->totalangsuran = new cField('tpinjamandetail', 'tpinjamandetail', 'x_totalangsuran', 'totalangsuran', '`totalangsuran`', '`totalangsuran`', 5, -1, FALSE, '`totalangsuran`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->totalangsuran->Sortable = TRUE; // Allow sort
		$this->totalangsuran->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['totalangsuran'] = &$this->totalangsuran;

		// totalangsuranauto
		$this->totalangsuranauto = new cField('tpinjamandetail', 'tpinjamandetail', 'x_totalangsuranauto', 'totalangsuranauto', '`totalangsuranauto`', '`totalangsuranauto`', 5, -1, FALSE, '`totalangsuranauto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->totalangsuranauto->Sortable = TRUE; // Allow sort
		$this->totalangsuranauto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['totalangsuranauto'] = &$this->totalangsuranauto;

		// sisaangsuran
		$this->sisaangsuran = new cField('tpinjamandetail', 'tpinjamandetail', 'x_sisaangsuran', 'sisaangsuran', '`sisaangsuran`', '`sisaangsuran`', 5, -1, FALSE, '`sisaangsuran`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->sisaangsuran->Sortable = TRUE; // Allow sort
		$this->sisaangsuran->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['sisaangsuran'] = &$this->sisaangsuran;

		// sisaangsuranauto
		$this->sisaangsuranauto = new cField('tpinjamandetail', 'tpinjamandetail', 'x_sisaangsuranauto', 'sisaangsuranauto', '`sisaangsuranauto`', '`sisaangsuranauto`', 5, -1, FALSE, '`sisaangsuranauto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->sisaangsuranauto->Sortable = TRUE; // Allow sort
		$this->sisaangsuranauto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['sisaangsuranauto'] = &$this->sisaangsuranauto;

		// tanggalbayar
		$this->tanggalbayar = new cField('tpinjamandetail', 'tpinjamandetail', 'x_tanggalbayar', 'tanggalbayar', '`tanggalbayar`', ew_CastDateFieldForLike('`tanggalbayar`', 0, "DB"), 135, 0, FALSE, '`tanggalbayar`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tanggalbayar->Sortable = TRUE; // Allow sort
		$this->tanggalbayar->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['tanggalbayar'] = &$this->tanggalbayar;

		// terlambat
		$this->terlambat = new cField('tpinjamandetail', 'tpinjamandetail', 'x_terlambat', 'terlambat', '`terlambat`', '`terlambat`', 20, -1, FALSE, '`terlambat`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->terlambat->Sortable = TRUE; // Allow sort
		$this->terlambat->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['terlambat'] = &$this->terlambat;

		// bayarpokok
		$this->bayarpokok = new cField('tpinjamandetail', 'tpinjamandetail', 'x_bayarpokok', 'bayarpokok', '`bayarpokok`', '`bayarpokok`', 5, -1, FALSE, '`bayarpokok`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->bayarpokok->Sortable = TRUE; // Allow sort
		$this->bayarpokok->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['bayarpokok'] = &$this->bayarpokok;

		// bayarpokokauto
		$this->bayarpokokauto = new cField('tpinjamandetail', 'tpinjamandetail', 'x_bayarpokokauto', 'bayarpokokauto', '`bayarpokokauto`', '`bayarpokokauto`', 5, -1, FALSE, '`bayarpokokauto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->bayarpokokauto->Sortable = TRUE; // Allow sort
		$this->bayarpokokauto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['bayarpokokauto'] = &$this->bayarpokokauto;

		// bayarbunga
		$this->bayarbunga = new cField('tpinjamandetail', 'tpinjamandetail', 'x_bayarbunga', 'bayarbunga', '`bayarbunga`', '`bayarbunga`', 5, -1, FALSE, '`bayarbunga`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->bayarbunga->Sortable = TRUE; // Allow sort
		$this->bayarbunga->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['bayarbunga'] = &$this->bayarbunga;

		// bayarbungaauto
		$this->bayarbungaauto = new cField('tpinjamandetail', 'tpinjamandetail', 'x_bayarbungaauto', 'bayarbungaauto', '`bayarbungaauto`', '`bayarbungaauto`', 5, -1, FALSE, '`bayarbungaauto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->bayarbungaauto->Sortable = TRUE; // Allow sort
		$this->bayarbungaauto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['bayarbungaauto'] = &$this->bayarbungaauto;

		// bayardenda
		$this->bayardenda = new cField('tpinjamandetail', 'tpinjamandetail', 'x_bayardenda', 'bayardenda', '`bayardenda`', '`bayardenda`', 5, -1, FALSE, '`bayardenda`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->bayardenda->Sortable = TRUE; // Allow sort
		$this->bayardenda->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['bayardenda'] = &$this->bayardenda;

		// bayardendaauto
		$this->bayardendaauto = new cField('tpinjamandetail', 'tpinjamandetail', 'x_bayardendaauto', 'bayardendaauto', '`bayardendaauto`', '`bayardendaauto`', 5, -1, FALSE, '`bayardendaauto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->bayardendaauto->Sortable = TRUE; // Allow sort
		$this->bayardendaauto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['bayardendaauto'] = &$this->bayardendaauto;

		// bayartitipan
		$this->bayartitipan = new cField('tpinjamandetail', 'tpinjamandetail', 'x_bayartitipan', 'bayartitipan', '`bayartitipan`', '`bayartitipan`', 5, -1, FALSE, '`bayartitipan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->bayartitipan->Sortable = TRUE; // Allow sort
		$this->bayartitipan->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['bayartitipan'] = &$this->bayartitipan;

		// bayartitipanauto
		$this->bayartitipanauto = new cField('tpinjamandetail', 'tpinjamandetail', 'x_bayartitipanauto', 'bayartitipanauto', '`bayartitipanauto`', '`bayartitipanauto`', 5, -1, FALSE, '`bayartitipanauto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->bayartitipanauto->Sortable = TRUE; // Allow sort
		$this->bayartitipanauto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['bayartitipanauto'] = &$this->bayartitipanauto;

		// totalbayar
		$this->totalbayar = new cField('tpinjamandetail', 'tpinjamandetail', 'x_totalbayar', 'totalbayar', '`totalbayar`', '`totalbayar`', 5, -1, FALSE, '`totalbayar`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->totalbayar->Sortable = TRUE; // Allow sort
		$this->totalbayar->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['totalbayar'] = &$this->totalbayar;

		// totalbayarauto
		$this->totalbayarauto = new cField('tpinjamandetail', 'tpinjamandetail', 'x_totalbayarauto', 'totalbayarauto', '`totalbayarauto`', '`totalbayarauto`', 5, -1, FALSE, '`totalbayarauto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->totalbayarauto->Sortable = TRUE; // Allow sort
		$this->totalbayarauto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['totalbayarauto'] = &$this->totalbayarauto;

		// pelunasan
		$this->pelunasan = new cField('tpinjamandetail', 'tpinjamandetail', 'x_pelunasan', 'pelunasan', '`pelunasan`', '`pelunasan`', 5, -1, FALSE, '`pelunasan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pelunasan->Sortable = TRUE; // Allow sort
		$this->pelunasan->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['pelunasan'] = &$this->pelunasan;

		// pelunasanauto
		$this->pelunasanauto = new cField('tpinjamandetail', 'tpinjamandetail', 'x_pelunasanauto', 'pelunasanauto', '`pelunasanauto`', '`pelunasanauto`', 5, -1, FALSE, '`pelunasanauto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pelunasanauto->Sortable = TRUE; // Allow sort
		$this->pelunasanauto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['pelunasanauto'] = &$this->pelunasanauto;

		// finalty
		$this->finalty = new cField('tpinjamandetail', 'tpinjamandetail', 'x_finalty', 'finalty', '`finalty`', '`finalty`', 5, -1, FALSE, '`finalty`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->finalty->Sortable = TRUE; // Allow sort
		$this->finalty->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['finalty'] = &$this->finalty;

		// finaltyauto
		$this->finaltyauto = new cField('tpinjamandetail', 'tpinjamandetail', 'x_finaltyauto', 'finaltyauto', '`finaltyauto`', '`finaltyauto`', 5, -1, FALSE, '`finaltyauto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->finaltyauto->Sortable = TRUE; // Allow sort
		$this->finaltyauto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['finaltyauto'] = &$this->finaltyauto;

		// status
		$this->status = new cField('tpinjamandetail', 'tpinjamandetail', 'x_status', 'status', '`status`', '`status`', 200, -1, FALSE, '`status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->status->Sortable = TRUE; // Allow sort
		$this->fields['status'] = &$this->status;

		// keterangan
		$this->keterangan = new cField('tpinjamandetail', 'tpinjamandetail', 'x_keterangan', 'keterangan', '`keterangan`', '`keterangan`', 200, -1, FALSE, '`keterangan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->keterangan->Sortable = TRUE; // Allow sort
		$this->fields['keterangan'] = &$this->keterangan;
	}

	// Set Field Visibility
	function SetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`tpinjamandetail`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		$bInsert = $conn->Execute($this->InsertSQL($rs));
		if ($bInsert) {
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('id', $rs))
				ew_AddFilter($where, ew_QuotedName('id', $this->DBID) . '=' . ew_QuotedValue($rs['id'], $this->id->FldDataType, $this->DBID));
			if (array_key_exists('angsuran', $rs))
				ew_AddFilter($where, ew_QuotedName('angsuran', $this->DBID) . '=' . ew_QuotedValue($rs['angsuran'], $this->angsuran->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`id` = '@id@' AND `angsuran` = @angsuran@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		$sKeyFilter = str_replace("@id@", ew_AdjustSql($this->id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		if (!is_numeric($this->angsuran->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@angsuran@", ew_AdjustSql($this->angsuran->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "tpinjamandetaillist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "tpinjamandetaillist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("tpinjamandetailview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("tpinjamandetailview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "tpinjamandetailadd.php?" . $this->UrlParm($parm);
		else
			$url = "tpinjamandetailadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("tpinjamandetailedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("tpinjamandetailadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("tpinjamandetaildelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "id:" . ew_VarToJson($this->id->CurrentValue, "string", "'");
		$json .= ",angsuran:" . ew_VarToJson($this->angsuran->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id->CurrentValue)) {
			$sUrl .= "id=" . urlencode($this->id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->angsuran->CurrentValue)) {
			$sUrl .= "&angsuran=" . urlencode($this->angsuran->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
			for ($i = 0; $i < $cnt; $i++)
				$arKeys[$i] = explode($EW_COMPOSITE_KEY_SEPARATOR, $arKeys[$i]);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
			for ($i = 0; $i < $cnt; $i++)
				$arKeys[$i] = explode($EW_COMPOSITE_KEY_SEPARATOR, $arKeys[$i]);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["id"]))
				$arKey[] = ew_StripSlashes($_POST["id"]);
			elseif (isset($_GET["id"]))
				$arKey[] = ew_StripSlashes($_GET["id"]);
			else
				$arKeys = NULL; // Do not setup
			if ($isPost && isset($_POST["angsuran"]))
				$arKey[] = ew_StripSlashes($_POST["angsuran"]);
			elseif (isset($_GET["angsuran"]))
				$arKey[] = ew_StripSlashes($_GET["angsuran"]);
			else
				$arKeys = NULL; // Do not setup
			if (is_array($arKeys)) $arKeys[] = $arKey;

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_array($key) || count($key) <> 2)
					continue; // Just skip so other keys will still work
				if (!is_numeric($key[1])) // angsuran
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->id->CurrentValue = $key[0];
			$this->angsuran->CurrentValue = $key[1];
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->tanggal->setDbValue($rs->fields('tanggal'));
		$this->periode->setDbValue($rs->fields('periode'));
		$this->id->setDbValue($rs->fields('id'));
		$this->angsuran->setDbValue($rs->fields('angsuran'));
		$this->masaangsuran->setDbValue($rs->fields('masaangsuran'));
		$this->dispensasidenda->setDbValue($rs->fields('dispensasidenda'));
		$this->plafond->setDbValue($rs->fields('plafond'));
		$this->angsuranpokok->setDbValue($rs->fields('angsuranpokok'));
		$this->angsuranpokokauto->setDbValue($rs->fields('angsuranpokokauto'));
		$this->angsuranbunga->setDbValue($rs->fields('angsuranbunga'));
		$this->angsuranbungaauto->setDbValue($rs->fields('angsuranbungaauto'));
		$this->denda->setDbValue($rs->fields('denda'));
		$this->dendapersen->setDbValue($rs->fields('dendapersen'));
		$this->totalangsuran->setDbValue($rs->fields('totalangsuran'));
		$this->totalangsuranauto->setDbValue($rs->fields('totalangsuranauto'));
		$this->sisaangsuran->setDbValue($rs->fields('sisaangsuran'));
		$this->sisaangsuranauto->setDbValue($rs->fields('sisaangsuranauto'));
		$this->tanggalbayar->setDbValue($rs->fields('tanggalbayar'));
		$this->terlambat->setDbValue($rs->fields('terlambat'));
		$this->bayarpokok->setDbValue($rs->fields('bayarpokok'));
		$this->bayarpokokauto->setDbValue($rs->fields('bayarpokokauto'));
		$this->bayarbunga->setDbValue($rs->fields('bayarbunga'));
		$this->bayarbungaauto->setDbValue($rs->fields('bayarbungaauto'));
		$this->bayardenda->setDbValue($rs->fields('bayardenda'));
		$this->bayardendaauto->setDbValue($rs->fields('bayardendaauto'));
		$this->bayartitipan->setDbValue($rs->fields('bayartitipan'));
		$this->bayartitipanauto->setDbValue($rs->fields('bayartitipanauto'));
		$this->totalbayar->setDbValue($rs->fields('totalbayar'));
		$this->totalbayarauto->setDbValue($rs->fields('totalbayarauto'));
		$this->pelunasan->setDbValue($rs->fields('pelunasan'));
		$this->pelunasanauto->setDbValue($rs->fields('pelunasanauto'));
		$this->finalty->setDbValue($rs->fields('finalty'));
		$this->finaltyauto->setDbValue($rs->fields('finaltyauto'));
		$this->status->setDbValue($rs->fields('status'));
		$this->keterangan->setDbValue($rs->fields('keterangan'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// tanggal
		// periode
		// id
		// angsuran
		// masaangsuran
		// dispensasidenda
		// plafond
		// angsuranpokok
		// angsuranpokokauto
		// angsuranbunga
		// angsuranbungaauto
		// denda
		// dendapersen
		// totalangsuran
		// totalangsuranauto
		// sisaangsuran
		// sisaangsuranauto
		// tanggalbayar
		// terlambat
		// bayarpokok
		// bayarpokokauto
		// bayarbunga
		// bayarbungaauto
		// bayardenda
		// bayardendaauto
		// bayartitipan
		// bayartitipanauto
		// totalbayar
		// totalbayarauto
		// pelunasan
		// pelunasanauto
		// finalty
		// finaltyauto
		// status
		// keterangan
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

		// angsuran
		$this->angsuran->ViewValue = $this->angsuran->CurrentValue;
		$this->angsuran->ViewCustomAttributes = "";

		// masaangsuran
		$this->masaangsuran->ViewValue = $this->masaangsuran->CurrentValue;
		$this->masaangsuran->ViewCustomAttributes = "";

		// dispensasidenda
		$this->dispensasidenda->ViewValue = $this->dispensasidenda->CurrentValue;
		$this->dispensasidenda->ViewCustomAttributes = "";

		// plafond
		$this->plafond->ViewValue = $this->plafond->CurrentValue;
		$this->plafond->ViewCustomAttributes = "";

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

		// sisaangsuran
		$this->sisaangsuran->ViewValue = $this->sisaangsuran->CurrentValue;
		$this->sisaangsuran->ViewCustomAttributes = "";

		// sisaangsuranauto
		$this->sisaangsuranauto->ViewValue = $this->sisaangsuranauto->CurrentValue;
		$this->sisaangsuranauto->ViewCustomAttributes = "";

		// tanggalbayar
		$this->tanggalbayar->ViewValue = $this->tanggalbayar->CurrentValue;
		$this->tanggalbayar->ViewValue = ew_FormatDateTime($this->tanggalbayar->ViewValue, 0);
		$this->tanggalbayar->ViewCustomAttributes = "";

		// terlambat
		$this->terlambat->ViewValue = $this->terlambat->CurrentValue;
		$this->terlambat->ViewCustomAttributes = "";

		// bayarpokok
		$this->bayarpokok->ViewValue = $this->bayarpokok->CurrentValue;
		$this->bayarpokok->ViewCustomAttributes = "";

		// bayarpokokauto
		$this->bayarpokokauto->ViewValue = $this->bayarpokokauto->CurrentValue;
		$this->bayarpokokauto->ViewCustomAttributes = "";

		// bayarbunga
		$this->bayarbunga->ViewValue = $this->bayarbunga->CurrentValue;
		$this->bayarbunga->ViewCustomAttributes = "";

		// bayarbungaauto
		$this->bayarbungaauto->ViewValue = $this->bayarbungaauto->CurrentValue;
		$this->bayarbungaauto->ViewCustomAttributes = "";

		// bayardenda
		$this->bayardenda->ViewValue = $this->bayardenda->CurrentValue;
		$this->bayardenda->ViewCustomAttributes = "";

		// bayardendaauto
		$this->bayardendaauto->ViewValue = $this->bayardendaauto->CurrentValue;
		$this->bayardendaauto->ViewCustomAttributes = "";

		// bayartitipan
		$this->bayartitipan->ViewValue = $this->bayartitipan->CurrentValue;
		$this->bayartitipan->ViewCustomAttributes = "";

		// bayartitipanauto
		$this->bayartitipanauto->ViewValue = $this->bayartitipanauto->CurrentValue;
		$this->bayartitipanauto->ViewCustomAttributes = "";

		// totalbayar
		$this->totalbayar->ViewValue = $this->totalbayar->CurrentValue;
		$this->totalbayar->ViewCustomAttributes = "";

		// totalbayarauto
		$this->totalbayarauto->ViewValue = $this->totalbayarauto->CurrentValue;
		$this->totalbayarauto->ViewCustomAttributes = "";

		// pelunasan
		$this->pelunasan->ViewValue = $this->pelunasan->CurrentValue;
		$this->pelunasan->ViewCustomAttributes = "";

		// pelunasanauto
		$this->pelunasanauto->ViewValue = $this->pelunasanauto->CurrentValue;
		$this->pelunasanauto->ViewCustomAttributes = "";

		// finalty
		$this->finalty->ViewValue = $this->finalty->CurrentValue;
		$this->finalty->ViewCustomAttributes = "";

		// finaltyauto
		$this->finaltyauto->ViewValue = $this->finaltyauto->CurrentValue;
		$this->finaltyauto->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		$this->status->ViewCustomAttributes = "";

		// keterangan
		$this->keterangan->ViewValue = $this->keterangan->CurrentValue;
		$this->keterangan->ViewCustomAttributes = "";

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

		// angsuran
		$this->angsuran->LinkCustomAttributes = "";
		$this->angsuran->HrefValue = "";
		$this->angsuran->TooltipValue = "";

		// masaangsuran
		$this->masaangsuran->LinkCustomAttributes = "";
		$this->masaangsuran->HrefValue = "";
		$this->masaangsuran->TooltipValue = "";

		// dispensasidenda
		$this->dispensasidenda->LinkCustomAttributes = "";
		$this->dispensasidenda->HrefValue = "";
		$this->dispensasidenda->TooltipValue = "";

		// plafond
		$this->plafond->LinkCustomAttributes = "";
		$this->plafond->HrefValue = "";
		$this->plafond->TooltipValue = "";

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

		// sisaangsuran
		$this->sisaangsuran->LinkCustomAttributes = "";
		$this->sisaangsuran->HrefValue = "";
		$this->sisaangsuran->TooltipValue = "";

		// sisaangsuranauto
		$this->sisaangsuranauto->LinkCustomAttributes = "";
		$this->sisaangsuranauto->HrefValue = "";
		$this->sisaangsuranauto->TooltipValue = "";

		// tanggalbayar
		$this->tanggalbayar->LinkCustomAttributes = "";
		$this->tanggalbayar->HrefValue = "";
		$this->tanggalbayar->TooltipValue = "";

		// terlambat
		$this->terlambat->LinkCustomAttributes = "";
		$this->terlambat->HrefValue = "";
		$this->terlambat->TooltipValue = "";

		// bayarpokok
		$this->bayarpokok->LinkCustomAttributes = "";
		$this->bayarpokok->HrefValue = "";
		$this->bayarpokok->TooltipValue = "";

		// bayarpokokauto
		$this->bayarpokokauto->LinkCustomAttributes = "";
		$this->bayarpokokauto->HrefValue = "";
		$this->bayarpokokauto->TooltipValue = "";

		// bayarbunga
		$this->bayarbunga->LinkCustomAttributes = "";
		$this->bayarbunga->HrefValue = "";
		$this->bayarbunga->TooltipValue = "";

		// bayarbungaauto
		$this->bayarbungaauto->LinkCustomAttributes = "";
		$this->bayarbungaauto->HrefValue = "";
		$this->bayarbungaauto->TooltipValue = "";

		// bayardenda
		$this->bayardenda->LinkCustomAttributes = "";
		$this->bayardenda->HrefValue = "";
		$this->bayardenda->TooltipValue = "";

		// bayardendaauto
		$this->bayardendaauto->LinkCustomAttributes = "";
		$this->bayardendaauto->HrefValue = "";
		$this->bayardendaauto->TooltipValue = "";

		// bayartitipan
		$this->bayartitipan->LinkCustomAttributes = "";
		$this->bayartitipan->HrefValue = "";
		$this->bayartitipan->TooltipValue = "";

		// bayartitipanauto
		$this->bayartitipanauto->LinkCustomAttributes = "";
		$this->bayartitipanauto->HrefValue = "";
		$this->bayartitipanauto->TooltipValue = "";

		// totalbayar
		$this->totalbayar->LinkCustomAttributes = "";
		$this->totalbayar->HrefValue = "";
		$this->totalbayar->TooltipValue = "";

		// totalbayarauto
		$this->totalbayarauto->LinkCustomAttributes = "";
		$this->totalbayarauto->HrefValue = "";
		$this->totalbayarauto->TooltipValue = "";

		// pelunasan
		$this->pelunasan->LinkCustomAttributes = "";
		$this->pelunasan->HrefValue = "";
		$this->pelunasan->TooltipValue = "";

		// pelunasanauto
		$this->pelunasanauto->LinkCustomAttributes = "";
		$this->pelunasanauto->HrefValue = "";
		$this->pelunasanauto->TooltipValue = "";

		// finalty
		$this->finalty->LinkCustomAttributes = "";
		$this->finalty->HrefValue = "";
		$this->finalty->TooltipValue = "";

		// finaltyauto
		$this->finaltyauto->LinkCustomAttributes = "";
		$this->finaltyauto->HrefValue = "";
		$this->finaltyauto->TooltipValue = "";

		// status
		$this->status->LinkCustomAttributes = "";
		$this->status->HrefValue = "";
		$this->status->TooltipValue = "";

		// keterangan
		$this->keterangan->LinkCustomAttributes = "";
		$this->keterangan->HrefValue = "";
		$this->keterangan->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// tanggal
		$this->tanggal->EditAttrs["class"] = "form-control";
		$this->tanggal->EditCustomAttributes = "";
		$this->tanggal->EditValue = ew_FormatDateTime($this->tanggal->CurrentValue, 8);
		$this->tanggal->PlaceHolder = ew_RemoveHtml($this->tanggal->FldCaption());

		// periode
		$this->periode->EditAttrs["class"] = "form-control";
		$this->periode->EditCustomAttributes = "";
		$this->periode->EditValue = $this->periode->CurrentValue;
		$this->periode->PlaceHolder = ew_RemoveHtml($this->periode->FldCaption());

		// id
		$this->id->EditAttrs["class"] = "form-control";
		$this->id->EditCustomAttributes = "";
		$this->id->EditValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// angsuran
		$this->angsuran->EditAttrs["class"] = "form-control";
		$this->angsuran->EditCustomAttributes = "";
		$this->angsuran->EditValue = $this->angsuran->CurrentValue;
		$this->angsuran->ViewCustomAttributes = "";

		// masaangsuran
		$this->masaangsuran->EditAttrs["class"] = "form-control";
		$this->masaangsuran->EditCustomAttributes = "";
		$this->masaangsuran->EditValue = $this->masaangsuran->CurrentValue;
		$this->masaangsuran->PlaceHolder = ew_RemoveHtml($this->masaangsuran->FldCaption());

		// dispensasidenda
		$this->dispensasidenda->EditAttrs["class"] = "form-control";
		$this->dispensasidenda->EditCustomAttributes = "";
		$this->dispensasidenda->EditValue = $this->dispensasidenda->CurrentValue;
		$this->dispensasidenda->PlaceHolder = ew_RemoveHtml($this->dispensasidenda->FldCaption());

		// plafond
		$this->plafond->EditAttrs["class"] = "form-control";
		$this->plafond->EditCustomAttributes = "";
		$this->plafond->EditValue = $this->plafond->CurrentValue;
		$this->plafond->PlaceHolder = ew_RemoveHtml($this->plafond->FldCaption());
		if (strval($this->plafond->EditValue) <> "" && is_numeric($this->plafond->EditValue)) $this->plafond->EditValue = ew_FormatNumber($this->plafond->EditValue, -2, -1, -2, 0);

		// angsuranpokok
		$this->angsuranpokok->EditAttrs["class"] = "form-control";
		$this->angsuranpokok->EditCustomAttributes = "";
		$this->angsuranpokok->EditValue = $this->angsuranpokok->CurrentValue;
		$this->angsuranpokok->PlaceHolder = ew_RemoveHtml($this->angsuranpokok->FldCaption());
		if (strval($this->angsuranpokok->EditValue) <> "" && is_numeric($this->angsuranpokok->EditValue)) $this->angsuranpokok->EditValue = ew_FormatNumber($this->angsuranpokok->EditValue, -2, -1, -2, 0);

		// angsuranpokokauto
		$this->angsuranpokokauto->EditAttrs["class"] = "form-control";
		$this->angsuranpokokauto->EditCustomAttributes = "";
		$this->angsuranpokokauto->EditValue = $this->angsuranpokokauto->CurrentValue;
		$this->angsuranpokokauto->PlaceHolder = ew_RemoveHtml($this->angsuranpokokauto->FldCaption());
		if (strval($this->angsuranpokokauto->EditValue) <> "" && is_numeric($this->angsuranpokokauto->EditValue)) $this->angsuranpokokauto->EditValue = ew_FormatNumber($this->angsuranpokokauto->EditValue, -2, -1, -2, 0);

		// angsuranbunga
		$this->angsuranbunga->EditAttrs["class"] = "form-control";
		$this->angsuranbunga->EditCustomAttributes = "";
		$this->angsuranbunga->EditValue = $this->angsuranbunga->CurrentValue;
		$this->angsuranbunga->PlaceHolder = ew_RemoveHtml($this->angsuranbunga->FldCaption());
		if (strval($this->angsuranbunga->EditValue) <> "" && is_numeric($this->angsuranbunga->EditValue)) $this->angsuranbunga->EditValue = ew_FormatNumber($this->angsuranbunga->EditValue, -2, -1, -2, 0);

		// angsuranbungaauto
		$this->angsuranbungaauto->EditAttrs["class"] = "form-control";
		$this->angsuranbungaauto->EditCustomAttributes = "";
		$this->angsuranbungaauto->EditValue = $this->angsuranbungaauto->CurrentValue;
		$this->angsuranbungaauto->PlaceHolder = ew_RemoveHtml($this->angsuranbungaauto->FldCaption());
		if (strval($this->angsuranbungaauto->EditValue) <> "" && is_numeric($this->angsuranbungaauto->EditValue)) $this->angsuranbungaauto->EditValue = ew_FormatNumber($this->angsuranbungaauto->EditValue, -2, -1, -2, 0);

		// denda
		$this->denda->EditAttrs["class"] = "form-control";
		$this->denda->EditCustomAttributes = "";
		$this->denda->EditValue = $this->denda->CurrentValue;
		$this->denda->PlaceHolder = ew_RemoveHtml($this->denda->FldCaption());
		if (strval($this->denda->EditValue) <> "" && is_numeric($this->denda->EditValue)) $this->denda->EditValue = ew_FormatNumber($this->denda->EditValue, -2, -1, -2, 0);

		// dendapersen
		$this->dendapersen->EditAttrs["class"] = "form-control";
		$this->dendapersen->EditCustomAttributes = "";
		$this->dendapersen->EditValue = $this->dendapersen->CurrentValue;
		$this->dendapersen->PlaceHolder = ew_RemoveHtml($this->dendapersen->FldCaption());
		if (strval($this->dendapersen->EditValue) <> "" && is_numeric($this->dendapersen->EditValue)) $this->dendapersen->EditValue = ew_FormatNumber($this->dendapersen->EditValue, -2, -1, -2, 0);

		// totalangsuran
		$this->totalangsuran->EditAttrs["class"] = "form-control";
		$this->totalangsuran->EditCustomAttributes = "";
		$this->totalangsuran->EditValue = $this->totalangsuran->CurrentValue;
		$this->totalangsuran->PlaceHolder = ew_RemoveHtml($this->totalangsuran->FldCaption());
		if (strval($this->totalangsuran->EditValue) <> "" && is_numeric($this->totalangsuran->EditValue)) $this->totalangsuran->EditValue = ew_FormatNumber($this->totalangsuran->EditValue, -2, -1, -2, 0);

		// totalangsuranauto
		$this->totalangsuranauto->EditAttrs["class"] = "form-control";
		$this->totalangsuranauto->EditCustomAttributes = "";
		$this->totalangsuranauto->EditValue = $this->totalangsuranauto->CurrentValue;
		$this->totalangsuranauto->PlaceHolder = ew_RemoveHtml($this->totalangsuranauto->FldCaption());
		if (strval($this->totalangsuranauto->EditValue) <> "" && is_numeric($this->totalangsuranauto->EditValue)) $this->totalangsuranauto->EditValue = ew_FormatNumber($this->totalangsuranauto->EditValue, -2, -1, -2, 0);

		// sisaangsuran
		$this->sisaangsuran->EditAttrs["class"] = "form-control";
		$this->sisaangsuran->EditCustomAttributes = "";
		$this->sisaangsuran->EditValue = $this->sisaangsuran->CurrentValue;
		$this->sisaangsuran->PlaceHolder = ew_RemoveHtml($this->sisaangsuran->FldCaption());
		if (strval($this->sisaangsuran->EditValue) <> "" && is_numeric($this->sisaangsuran->EditValue)) $this->sisaangsuran->EditValue = ew_FormatNumber($this->sisaangsuran->EditValue, -2, -1, -2, 0);

		// sisaangsuranauto
		$this->sisaangsuranauto->EditAttrs["class"] = "form-control";
		$this->sisaangsuranauto->EditCustomAttributes = "";
		$this->sisaangsuranauto->EditValue = $this->sisaangsuranauto->CurrentValue;
		$this->sisaangsuranauto->PlaceHolder = ew_RemoveHtml($this->sisaangsuranauto->FldCaption());
		if (strval($this->sisaangsuranauto->EditValue) <> "" && is_numeric($this->sisaangsuranauto->EditValue)) $this->sisaangsuranauto->EditValue = ew_FormatNumber($this->sisaangsuranauto->EditValue, -2, -1, -2, 0);

		// tanggalbayar
		$this->tanggalbayar->EditAttrs["class"] = "form-control";
		$this->tanggalbayar->EditCustomAttributes = "";
		$this->tanggalbayar->EditValue = ew_FormatDateTime($this->tanggalbayar->CurrentValue, 8);
		$this->tanggalbayar->PlaceHolder = ew_RemoveHtml($this->tanggalbayar->FldCaption());

		// terlambat
		$this->terlambat->EditAttrs["class"] = "form-control";
		$this->terlambat->EditCustomAttributes = "";
		$this->terlambat->EditValue = $this->terlambat->CurrentValue;
		$this->terlambat->PlaceHolder = ew_RemoveHtml($this->terlambat->FldCaption());

		// bayarpokok
		$this->bayarpokok->EditAttrs["class"] = "form-control";
		$this->bayarpokok->EditCustomAttributes = "";
		$this->bayarpokok->EditValue = $this->bayarpokok->CurrentValue;
		$this->bayarpokok->PlaceHolder = ew_RemoveHtml($this->bayarpokok->FldCaption());
		if (strval($this->bayarpokok->EditValue) <> "" && is_numeric($this->bayarpokok->EditValue)) $this->bayarpokok->EditValue = ew_FormatNumber($this->bayarpokok->EditValue, -2, -1, -2, 0);

		// bayarpokokauto
		$this->bayarpokokauto->EditAttrs["class"] = "form-control";
		$this->bayarpokokauto->EditCustomAttributes = "";
		$this->bayarpokokauto->EditValue = $this->bayarpokokauto->CurrentValue;
		$this->bayarpokokauto->PlaceHolder = ew_RemoveHtml($this->bayarpokokauto->FldCaption());
		if (strval($this->bayarpokokauto->EditValue) <> "" && is_numeric($this->bayarpokokauto->EditValue)) $this->bayarpokokauto->EditValue = ew_FormatNumber($this->bayarpokokauto->EditValue, -2, -1, -2, 0);

		// bayarbunga
		$this->bayarbunga->EditAttrs["class"] = "form-control";
		$this->bayarbunga->EditCustomAttributes = "";
		$this->bayarbunga->EditValue = $this->bayarbunga->CurrentValue;
		$this->bayarbunga->PlaceHolder = ew_RemoveHtml($this->bayarbunga->FldCaption());
		if (strval($this->bayarbunga->EditValue) <> "" && is_numeric($this->bayarbunga->EditValue)) $this->bayarbunga->EditValue = ew_FormatNumber($this->bayarbunga->EditValue, -2, -1, -2, 0);

		// bayarbungaauto
		$this->bayarbungaauto->EditAttrs["class"] = "form-control";
		$this->bayarbungaauto->EditCustomAttributes = "";
		$this->bayarbungaauto->EditValue = $this->bayarbungaauto->CurrentValue;
		$this->bayarbungaauto->PlaceHolder = ew_RemoveHtml($this->bayarbungaauto->FldCaption());
		if (strval($this->bayarbungaauto->EditValue) <> "" && is_numeric($this->bayarbungaauto->EditValue)) $this->bayarbungaauto->EditValue = ew_FormatNumber($this->bayarbungaauto->EditValue, -2, -1, -2, 0);

		// bayardenda
		$this->bayardenda->EditAttrs["class"] = "form-control";
		$this->bayardenda->EditCustomAttributes = "";
		$this->bayardenda->EditValue = $this->bayardenda->CurrentValue;
		$this->bayardenda->PlaceHolder = ew_RemoveHtml($this->bayardenda->FldCaption());
		if (strval($this->bayardenda->EditValue) <> "" && is_numeric($this->bayardenda->EditValue)) $this->bayardenda->EditValue = ew_FormatNumber($this->bayardenda->EditValue, -2, -1, -2, 0);

		// bayardendaauto
		$this->bayardendaauto->EditAttrs["class"] = "form-control";
		$this->bayardendaauto->EditCustomAttributes = "";
		$this->bayardendaauto->EditValue = $this->bayardendaauto->CurrentValue;
		$this->bayardendaauto->PlaceHolder = ew_RemoveHtml($this->bayardendaauto->FldCaption());
		if (strval($this->bayardendaauto->EditValue) <> "" && is_numeric($this->bayardendaauto->EditValue)) $this->bayardendaauto->EditValue = ew_FormatNumber($this->bayardendaauto->EditValue, -2, -1, -2, 0);

		// bayartitipan
		$this->bayartitipan->EditAttrs["class"] = "form-control";
		$this->bayartitipan->EditCustomAttributes = "";
		$this->bayartitipan->EditValue = $this->bayartitipan->CurrentValue;
		$this->bayartitipan->PlaceHolder = ew_RemoveHtml($this->bayartitipan->FldCaption());
		if (strval($this->bayartitipan->EditValue) <> "" && is_numeric($this->bayartitipan->EditValue)) $this->bayartitipan->EditValue = ew_FormatNumber($this->bayartitipan->EditValue, -2, -1, -2, 0);

		// bayartitipanauto
		$this->bayartitipanauto->EditAttrs["class"] = "form-control";
		$this->bayartitipanauto->EditCustomAttributes = "";
		$this->bayartitipanauto->EditValue = $this->bayartitipanauto->CurrentValue;
		$this->bayartitipanauto->PlaceHolder = ew_RemoveHtml($this->bayartitipanauto->FldCaption());
		if (strval($this->bayartitipanauto->EditValue) <> "" && is_numeric($this->bayartitipanauto->EditValue)) $this->bayartitipanauto->EditValue = ew_FormatNumber($this->bayartitipanauto->EditValue, -2, -1, -2, 0);

		// totalbayar
		$this->totalbayar->EditAttrs["class"] = "form-control";
		$this->totalbayar->EditCustomAttributes = "";
		$this->totalbayar->EditValue = $this->totalbayar->CurrentValue;
		$this->totalbayar->PlaceHolder = ew_RemoveHtml($this->totalbayar->FldCaption());
		if (strval($this->totalbayar->EditValue) <> "" && is_numeric($this->totalbayar->EditValue)) $this->totalbayar->EditValue = ew_FormatNumber($this->totalbayar->EditValue, -2, -1, -2, 0);

		// totalbayarauto
		$this->totalbayarauto->EditAttrs["class"] = "form-control";
		$this->totalbayarauto->EditCustomAttributes = "";
		$this->totalbayarauto->EditValue = $this->totalbayarauto->CurrentValue;
		$this->totalbayarauto->PlaceHolder = ew_RemoveHtml($this->totalbayarauto->FldCaption());
		if (strval($this->totalbayarauto->EditValue) <> "" && is_numeric($this->totalbayarauto->EditValue)) $this->totalbayarauto->EditValue = ew_FormatNumber($this->totalbayarauto->EditValue, -2, -1, -2, 0);

		// pelunasan
		$this->pelunasan->EditAttrs["class"] = "form-control";
		$this->pelunasan->EditCustomAttributes = "";
		$this->pelunasan->EditValue = $this->pelunasan->CurrentValue;
		$this->pelunasan->PlaceHolder = ew_RemoveHtml($this->pelunasan->FldCaption());
		if (strval($this->pelunasan->EditValue) <> "" && is_numeric($this->pelunasan->EditValue)) $this->pelunasan->EditValue = ew_FormatNumber($this->pelunasan->EditValue, -2, -1, -2, 0);

		// pelunasanauto
		$this->pelunasanauto->EditAttrs["class"] = "form-control";
		$this->pelunasanauto->EditCustomAttributes = "";
		$this->pelunasanauto->EditValue = $this->pelunasanauto->CurrentValue;
		$this->pelunasanauto->PlaceHolder = ew_RemoveHtml($this->pelunasanauto->FldCaption());
		if (strval($this->pelunasanauto->EditValue) <> "" && is_numeric($this->pelunasanauto->EditValue)) $this->pelunasanauto->EditValue = ew_FormatNumber($this->pelunasanauto->EditValue, -2, -1, -2, 0);

		// finalty
		$this->finalty->EditAttrs["class"] = "form-control";
		$this->finalty->EditCustomAttributes = "";
		$this->finalty->EditValue = $this->finalty->CurrentValue;
		$this->finalty->PlaceHolder = ew_RemoveHtml($this->finalty->FldCaption());
		if (strval($this->finalty->EditValue) <> "" && is_numeric($this->finalty->EditValue)) $this->finalty->EditValue = ew_FormatNumber($this->finalty->EditValue, -2, -1, -2, 0);

		// finaltyauto
		$this->finaltyauto->EditAttrs["class"] = "form-control";
		$this->finaltyauto->EditCustomAttributes = "";
		$this->finaltyauto->EditValue = $this->finaltyauto->CurrentValue;
		$this->finaltyauto->PlaceHolder = ew_RemoveHtml($this->finaltyauto->FldCaption());
		if (strval($this->finaltyauto->EditValue) <> "" && is_numeric($this->finaltyauto->EditValue)) $this->finaltyauto->EditValue = ew_FormatNumber($this->finaltyauto->EditValue, -2, -1, -2, 0);

		// status
		$this->status->EditAttrs["class"] = "form-control";
		$this->status->EditCustomAttributes = "";
		$this->status->EditValue = $this->status->CurrentValue;
		$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

		// keterangan
		$this->keterangan->EditAttrs["class"] = "form-control";
		$this->keterangan->EditCustomAttributes = "";
		$this->keterangan->EditValue = $this->keterangan->CurrentValue;
		$this->keterangan->PlaceHolder = ew_RemoveHtml($this->keterangan->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->tanggal->Exportable) $Doc->ExportCaption($this->tanggal);
					if ($this->periode->Exportable) $Doc->ExportCaption($this->periode);
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->angsuran->Exportable) $Doc->ExportCaption($this->angsuran);
					if ($this->masaangsuran->Exportable) $Doc->ExportCaption($this->masaangsuran);
					if ($this->dispensasidenda->Exportable) $Doc->ExportCaption($this->dispensasidenda);
					if ($this->plafond->Exportable) $Doc->ExportCaption($this->plafond);
					if ($this->angsuranpokok->Exportable) $Doc->ExportCaption($this->angsuranpokok);
					if ($this->angsuranpokokauto->Exportable) $Doc->ExportCaption($this->angsuranpokokauto);
					if ($this->angsuranbunga->Exportable) $Doc->ExportCaption($this->angsuranbunga);
					if ($this->angsuranbungaauto->Exportable) $Doc->ExportCaption($this->angsuranbungaauto);
					if ($this->denda->Exportable) $Doc->ExportCaption($this->denda);
					if ($this->dendapersen->Exportable) $Doc->ExportCaption($this->dendapersen);
					if ($this->totalangsuran->Exportable) $Doc->ExportCaption($this->totalangsuran);
					if ($this->totalangsuranauto->Exportable) $Doc->ExportCaption($this->totalangsuranauto);
					if ($this->sisaangsuran->Exportable) $Doc->ExportCaption($this->sisaangsuran);
					if ($this->sisaangsuranauto->Exportable) $Doc->ExportCaption($this->sisaangsuranauto);
					if ($this->tanggalbayar->Exportable) $Doc->ExportCaption($this->tanggalbayar);
					if ($this->terlambat->Exportable) $Doc->ExportCaption($this->terlambat);
					if ($this->bayarpokok->Exportable) $Doc->ExportCaption($this->bayarpokok);
					if ($this->bayarpokokauto->Exportable) $Doc->ExportCaption($this->bayarpokokauto);
					if ($this->bayarbunga->Exportable) $Doc->ExportCaption($this->bayarbunga);
					if ($this->bayarbungaauto->Exportable) $Doc->ExportCaption($this->bayarbungaauto);
					if ($this->bayardenda->Exportable) $Doc->ExportCaption($this->bayardenda);
					if ($this->bayardendaauto->Exportable) $Doc->ExportCaption($this->bayardendaauto);
					if ($this->bayartitipan->Exportable) $Doc->ExportCaption($this->bayartitipan);
					if ($this->bayartitipanauto->Exportable) $Doc->ExportCaption($this->bayartitipanauto);
					if ($this->totalbayar->Exportable) $Doc->ExportCaption($this->totalbayar);
					if ($this->totalbayarauto->Exportable) $Doc->ExportCaption($this->totalbayarauto);
					if ($this->pelunasan->Exportable) $Doc->ExportCaption($this->pelunasan);
					if ($this->pelunasanauto->Exportable) $Doc->ExportCaption($this->pelunasanauto);
					if ($this->finalty->Exportable) $Doc->ExportCaption($this->finalty);
					if ($this->finaltyauto->Exportable) $Doc->ExportCaption($this->finaltyauto);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->keterangan->Exportable) $Doc->ExportCaption($this->keterangan);
				} else {
					if ($this->tanggal->Exportable) $Doc->ExportCaption($this->tanggal);
					if ($this->periode->Exportable) $Doc->ExportCaption($this->periode);
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->angsuran->Exportable) $Doc->ExportCaption($this->angsuran);
					if ($this->masaangsuran->Exportable) $Doc->ExportCaption($this->masaangsuran);
					if ($this->dispensasidenda->Exportable) $Doc->ExportCaption($this->dispensasidenda);
					if ($this->plafond->Exportable) $Doc->ExportCaption($this->plafond);
					if ($this->angsuranpokok->Exportable) $Doc->ExportCaption($this->angsuranpokok);
					if ($this->angsuranpokokauto->Exportable) $Doc->ExportCaption($this->angsuranpokokauto);
					if ($this->angsuranbunga->Exportable) $Doc->ExportCaption($this->angsuranbunga);
					if ($this->angsuranbungaauto->Exportable) $Doc->ExportCaption($this->angsuranbungaauto);
					if ($this->denda->Exportable) $Doc->ExportCaption($this->denda);
					if ($this->dendapersen->Exportable) $Doc->ExportCaption($this->dendapersen);
					if ($this->totalangsuran->Exportable) $Doc->ExportCaption($this->totalangsuran);
					if ($this->totalangsuranauto->Exportable) $Doc->ExportCaption($this->totalangsuranauto);
					if ($this->sisaangsuran->Exportable) $Doc->ExportCaption($this->sisaangsuran);
					if ($this->sisaangsuranauto->Exportable) $Doc->ExportCaption($this->sisaangsuranauto);
					if ($this->tanggalbayar->Exportable) $Doc->ExportCaption($this->tanggalbayar);
					if ($this->terlambat->Exportable) $Doc->ExportCaption($this->terlambat);
					if ($this->bayarpokok->Exportable) $Doc->ExportCaption($this->bayarpokok);
					if ($this->bayarpokokauto->Exportable) $Doc->ExportCaption($this->bayarpokokauto);
					if ($this->bayarbunga->Exportable) $Doc->ExportCaption($this->bayarbunga);
					if ($this->bayarbungaauto->Exportable) $Doc->ExportCaption($this->bayarbungaauto);
					if ($this->bayardenda->Exportable) $Doc->ExportCaption($this->bayardenda);
					if ($this->bayardendaauto->Exportable) $Doc->ExportCaption($this->bayardendaauto);
					if ($this->bayartitipan->Exportable) $Doc->ExportCaption($this->bayartitipan);
					if ($this->bayartitipanauto->Exportable) $Doc->ExportCaption($this->bayartitipanauto);
					if ($this->totalbayar->Exportable) $Doc->ExportCaption($this->totalbayar);
					if ($this->totalbayarauto->Exportable) $Doc->ExportCaption($this->totalbayarauto);
					if ($this->pelunasan->Exportable) $Doc->ExportCaption($this->pelunasan);
					if ($this->pelunasanauto->Exportable) $Doc->ExportCaption($this->pelunasanauto);
					if ($this->finalty->Exportable) $Doc->ExportCaption($this->finalty);
					if ($this->finaltyauto->Exportable) $Doc->ExportCaption($this->finaltyauto);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->keterangan->Exportable) $Doc->ExportCaption($this->keterangan);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->tanggal->Exportable) $Doc->ExportField($this->tanggal);
						if ($this->periode->Exportable) $Doc->ExportField($this->periode);
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->angsuran->Exportable) $Doc->ExportField($this->angsuran);
						if ($this->masaangsuran->Exportable) $Doc->ExportField($this->masaangsuran);
						if ($this->dispensasidenda->Exportable) $Doc->ExportField($this->dispensasidenda);
						if ($this->plafond->Exportable) $Doc->ExportField($this->plafond);
						if ($this->angsuranpokok->Exportable) $Doc->ExportField($this->angsuranpokok);
						if ($this->angsuranpokokauto->Exportable) $Doc->ExportField($this->angsuranpokokauto);
						if ($this->angsuranbunga->Exportable) $Doc->ExportField($this->angsuranbunga);
						if ($this->angsuranbungaauto->Exportable) $Doc->ExportField($this->angsuranbungaauto);
						if ($this->denda->Exportable) $Doc->ExportField($this->denda);
						if ($this->dendapersen->Exportable) $Doc->ExportField($this->dendapersen);
						if ($this->totalangsuran->Exportable) $Doc->ExportField($this->totalangsuran);
						if ($this->totalangsuranauto->Exportable) $Doc->ExportField($this->totalangsuranauto);
						if ($this->sisaangsuran->Exportable) $Doc->ExportField($this->sisaangsuran);
						if ($this->sisaangsuranauto->Exportable) $Doc->ExportField($this->sisaangsuranauto);
						if ($this->tanggalbayar->Exportable) $Doc->ExportField($this->tanggalbayar);
						if ($this->terlambat->Exportable) $Doc->ExportField($this->terlambat);
						if ($this->bayarpokok->Exportable) $Doc->ExportField($this->bayarpokok);
						if ($this->bayarpokokauto->Exportable) $Doc->ExportField($this->bayarpokokauto);
						if ($this->bayarbunga->Exportable) $Doc->ExportField($this->bayarbunga);
						if ($this->bayarbungaauto->Exportable) $Doc->ExportField($this->bayarbungaauto);
						if ($this->bayardenda->Exportable) $Doc->ExportField($this->bayardenda);
						if ($this->bayardendaauto->Exportable) $Doc->ExportField($this->bayardendaauto);
						if ($this->bayartitipan->Exportable) $Doc->ExportField($this->bayartitipan);
						if ($this->bayartitipanauto->Exportable) $Doc->ExportField($this->bayartitipanauto);
						if ($this->totalbayar->Exportable) $Doc->ExportField($this->totalbayar);
						if ($this->totalbayarauto->Exportable) $Doc->ExportField($this->totalbayarauto);
						if ($this->pelunasan->Exportable) $Doc->ExportField($this->pelunasan);
						if ($this->pelunasanauto->Exportable) $Doc->ExportField($this->pelunasanauto);
						if ($this->finalty->Exportable) $Doc->ExportField($this->finalty);
						if ($this->finaltyauto->Exportable) $Doc->ExportField($this->finaltyauto);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->keterangan->Exportable) $Doc->ExportField($this->keterangan);
					} else {
						if ($this->tanggal->Exportable) $Doc->ExportField($this->tanggal);
						if ($this->periode->Exportable) $Doc->ExportField($this->periode);
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->angsuran->Exportable) $Doc->ExportField($this->angsuran);
						if ($this->masaangsuran->Exportable) $Doc->ExportField($this->masaangsuran);
						if ($this->dispensasidenda->Exportable) $Doc->ExportField($this->dispensasidenda);
						if ($this->plafond->Exportable) $Doc->ExportField($this->plafond);
						if ($this->angsuranpokok->Exportable) $Doc->ExportField($this->angsuranpokok);
						if ($this->angsuranpokokauto->Exportable) $Doc->ExportField($this->angsuranpokokauto);
						if ($this->angsuranbunga->Exportable) $Doc->ExportField($this->angsuranbunga);
						if ($this->angsuranbungaauto->Exportable) $Doc->ExportField($this->angsuranbungaauto);
						if ($this->denda->Exportable) $Doc->ExportField($this->denda);
						if ($this->dendapersen->Exportable) $Doc->ExportField($this->dendapersen);
						if ($this->totalangsuran->Exportable) $Doc->ExportField($this->totalangsuran);
						if ($this->totalangsuranauto->Exportable) $Doc->ExportField($this->totalangsuranauto);
						if ($this->sisaangsuran->Exportable) $Doc->ExportField($this->sisaangsuran);
						if ($this->sisaangsuranauto->Exportable) $Doc->ExportField($this->sisaangsuranauto);
						if ($this->tanggalbayar->Exportable) $Doc->ExportField($this->tanggalbayar);
						if ($this->terlambat->Exportable) $Doc->ExportField($this->terlambat);
						if ($this->bayarpokok->Exportable) $Doc->ExportField($this->bayarpokok);
						if ($this->bayarpokokauto->Exportable) $Doc->ExportField($this->bayarpokokauto);
						if ($this->bayarbunga->Exportable) $Doc->ExportField($this->bayarbunga);
						if ($this->bayarbungaauto->Exportable) $Doc->ExportField($this->bayarbungaauto);
						if ($this->bayardenda->Exportable) $Doc->ExportField($this->bayardenda);
						if ($this->bayardendaauto->Exportable) $Doc->ExportField($this->bayardendaauto);
						if ($this->bayartitipan->Exportable) $Doc->ExportField($this->bayartitipan);
						if ($this->bayartitipanauto->Exportable) $Doc->ExportField($this->bayartitipanauto);
						if ($this->totalbayar->Exportable) $Doc->ExportField($this->totalbayar);
						if ($this->totalbayarauto->Exportable) $Doc->ExportField($this->totalbayarauto);
						if ($this->pelunasan->Exportable) $Doc->ExportField($this->pelunasan);
						if ($this->pelunasanauto->Exportable) $Doc->ExportField($this->pelunasanauto);
						if ($this->finalty->Exportable) $Doc->ExportField($this->finalty);
						if ($this->finaltyauto->Exportable) $Doc->ExportField($this->finaltyauto);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->keterangan->Exportable) $Doc->ExportField($this->keterangan);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
