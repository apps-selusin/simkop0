<?php

// Global variable for table object
$tlaporan = NULL;

//
// Table class for tlaporan
//
class ctlaporan extends cTable {
	var $tanggal;
	var $periode;
	var $id;
	var $nomor;
	var $transaksi;
	var $referensi;
	var $group;
	var $rekening;
	var $tipe;
	var $posisi;
	var $laporan;
	var $keterangan;
	var $debet1;
	var $credit1;
	var $saldo1;
	var $debet2;
	var $credit2;
	var $saldo2;
	var $debet3;
	var $credit3;
	var $saldo3;
	var $debet4;
	var $credit4;
	var $saldo4;
	var $debet5;
	var $credit5;
	var $saldo5;
	var $debet6;
	var $credit6;
	var $saldo6;
	var $debet7;
	var $credit7;
	var $saldo7;
	var $debet8;
	var $credit8;
	var $saldo8;
	var $debet9;
	var $credit9;
	var $saldo9;
	var $debet10;
	var $credit10;
	var $saldo10;
	var $debet11;
	var $credit11;
	var $saldo11;
	var $debet12;
	var $credit12;
	var $saldo12;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'tlaporan';
		$this->TableName = 'tlaporan';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`tlaporan`";
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
		$this->tanggal = new cField('tlaporan', 'tlaporan', 'x_tanggal', 'tanggal', '`tanggal`', ew_CastDateFieldForLike('`tanggal`', 0, "DB"), 135, 0, FALSE, '`tanggal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tanggal->Sortable = TRUE; // Allow sort
		$this->tanggal->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['tanggal'] = &$this->tanggal;

		// periode
		$this->periode = new cField('tlaporan', 'tlaporan', 'x_periode', 'periode', '`periode`', '`periode`', 200, -1, FALSE, '`periode`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->periode->Sortable = TRUE; // Allow sort
		$this->fields['periode'] = &$this->periode;

		// id
		$this->id = new cField('tlaporan', 'tlaporan', 'x_id', 'id', '`id`', '`id`', 200, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->id->Sortable = TRUE; // Allow sort
		$this->fields['id'] = &$this->id;

		// nomor
		$this->nomor = new cField('tlaporan', 'tlaporan', 'x_nomor', 'nomor', '`nomor`', '`nomor`', 20, -1, FALSE, '`nomor`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nomor->Sortable = TRUE; // Allow sort
		$this->nomor->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nomor'] = &$this->nomor;

		// transaksi
		$this->transaksi = new cField('tlaporan', 'tlaporan', 'x_transaksi', 'transaksi', '`transaksi`', '`transaksi`', 200, -1, FALSE, '`transaksi`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->transaksi->Sortable = TRUE; // Allow sort
		$this->fields['transaksi'] = &$this->transaksi;

		// referensi
		$this->referensi = new cField('tlaporan', 'tlaporan', 'x_referensi', 'referensi', '`referensi`', '`referensi`', 200, -1, FALSE, '`referensi`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->referensi->Sortable = TRUE; // Allow sort
		$this->fields['referensi'] = &$this->referensi;

		// group
		$this->group = new cField('tlaporan', 'tlaporan', 'x_group', 'group', '`group`', '`group`', 20, -1, FALSE, '`group`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->group->Sortable = TRUE; // Allow sort
		$this->group->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['group'] = &$this->group;

		// rekening
		$this->rekening = new cField('tlaporan', 'tlaporan', 'x_rekening', 'rekening', '`rekening`', '`rekening`', 200, -1, FALSE, '`rekening`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->rekening->Sortable = TRUE; // Allow sort
		$this->fields['rekening'] = &$this->rekening;

		// tipe
		$this->tipe = new cField('tlaporan', 'tlaporan', 'x_tipe', 'tipe', '`tipe`', '`tipe`', 200, -1, FALSE, '`tipe`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tipe->Sortable = TRUE; // Allow sort
		$this->fields['tipe'] = &$this->tipe;

		// posisi
		$this->posisi = new cField('tlaporan', 'tlaporan', 'x_posisi', 'posisi', '`posisi`', '`posisi`', 200, -1, FALSE, '`posisi`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->posisi->Sortable = TRUE; // Allow sort
		$this->fields['posisi'] = &$this->posisi;

		// laporan
		$this->laporan = new cField('tlaporan', 'tlaporan', 'x_laporan', 'laporan', '`laporan`', '`laporan`', 200, -1, FALSE, '`laporan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->laporan->Sortable = TRUE; // Allow sort
		$this->fields['laporan'] = &$this->laporan;

		// keterangan
		$this->keterangan = new cField('tlaporan', 'tlaporan', 'x_keterangan', 'keterangan', '`keterangan`', '`keterangan`', 200, -1, FALSE, '`keterangan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->keterangan->Sortable = TRUE; // Allow sort
		$this->fields['keterangan'] = &$this->keterangan;

		// debet1
		$this->debet1 = new cField('tlaporan', 'tlaporan', 'x_debet1', 'debet1', '`debet1`', '`debet1`', 5, -1, FALSE, '`debet1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->debet1->Sortable = TRUE; // Allow sort
		$this->debet1->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['debet1'] = &$this->debet1;

		// credit1
		$this->credit1 = new cField('tlaporan', 'tlaporan', 'x_credit1', 'credit1', '`credit1`', '`credit1`', 5, -1, FALSE, '`credit1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->credit1->Sortable = TRUE; // Allow sort
		$this->credit1->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['credit1'] = &$this->credit1;

		// saldo1
		$this->saldo1 = new cField('tlaporan', 'tlaporan', 'x_saldo1', 'saldo1', '`saldo1`', '`saldo1`', 5, -1, FALSE, '`saldo1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->saldo1->Sortable = TRUE; // Allow sort
		$this->saldo1->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['saldo1'] = &$this->saldo1;

		// debet2
		$this->debet2 = new cField('tlaporan', 'tlaporan', 'x_debet2', 'debet2', '`debet2`', '`debet2`', 5, -1, FALSE, '`debet2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->debet2->Sortable = TRUE; // Allow sort
		$this->debet2->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['debet2'] = &$this->debet2;

		// credit2
		$this->credit2 = new cField('tlaporan', 'tlaporan', 'x_credit2', 'credit2', '`credit2`', '`credit2`', 5, -1, FALSE, '`credit2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->credit2->Sortable = TRUE; // Allow sort
		$this->credit2->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['credit2'] = &$this->credit2;

		// saldo2
		$this->saldo2 = new cField('tlaporan', 'tlaporan', 'x_saldo2', 'saldo2', '`saldo2`', '`saldo2`', 5, -1, FALSE, '`saldo2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->saldo2->Sortable = TRUE; // Allow sort
		$this->saldo2->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['saldo2'] = &$this->saldo2;

		// debet3
		$this->debet3 = new cField('tlaporan', 'tlaporan', 'x_debet3', 'debet3', '`debet3`', '`debet3`', 5, -1, FALSE, '`debet3`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->debet3->Sortable = TRUE; // Allow sort
		$this->debet3->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['debet3'] = &$this->debet3;

		// credit3
		$this->credit3 = new cField('tlaporan', 'tlaporan', 'x_credit3', 'credit3', '`credit3`', '`credit3`', 5, -1, FALSE, '`credit3`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->credit3->Sortable = TRUE; // Allow sort
		$this->credit3->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['credit3'] = &$this->credit3;

		// saldo3
		$this->saldo3 = new cField('tlaporan', 'tlaporan', 'x_saldo3', 'saldo3', '`saldo3`', '`saldo3`', 5, -1, FALSE, '`saldo3`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->saldo3->Sortable = TRUE; // Allow sort
		$this->saldo3->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['saldo3'] = &$this->saldo3;

		// debet4
		$this->debet4 = new cField('tlaporan', 'tlaporan', 'x_debet4', 'debet4', '`debet4`', '`debet4`', 5, -1, FALSE, '`debet4`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->debet4->Sortable = TRUE; // Allow sort
		$this->debet4->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['debet4'] = &$this->debet4;

		// credit4
		$this->credit4 = new cField('tlaporan', 'tlaporan', 'x_credit4', 'credit4', '`credit4`', '`credit4`', 5, -1, FALSE, '`credit4`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->credit4->Sortable = TRUE; // Allow sort
		$this->credit4->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['credit4'] = &$this->credit4;

		// saldo4
		$this->saldo4 = new cField('tlaporan', 'tlaporan', 'x_saldo4', 'saldo4', '`saldo4`', '`saldo4`', 5, -1, FALSE, '`saldo4`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->saldo4->Sortable = TRUE; // Allow sort
		$this->saldo4->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['saldo4'] = &$this->saldo4;

		// debet5
		$this->debet5 = new cField('tlaporan', 'tlaporan', 'x_debet5', 'debet5', '`debet5`', '`debet5`', 5, -1, FALSE, '`debet5`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->debet5->Sortable = TRUE; // Allow sort
		$this->debet5->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['debet5'] = &$this->debet5;

		// credit5
		$this->credit5 = new cField('tlaporan', 'tlaporan', 'x_credit5', 'credit5', '`credit5`', '`credit5`', 5, -1, FALSE, '`credit5`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->credit5->Sortable = TRUE; // Allow sort
		$this->credit5->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['credit5'] = &$this->credit5;

		// saldo5
		$this->saldo5 = new cField('tlaporan', 'tlaporan', 'x_saldo5', 'saldo5', '`saldo5`', '`saldo5`', 5, -1, FALSE, '`saldo5`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->saldo5->Sortable = TRUE; // Allow sort
		$this->saldo5->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['saldo5'] = &$this->saldo5;

		// debet6
		$this->debet6 = new cField('tlaporan', 'tlaporan', 'x_debet6', 'debet6', '`debet6`', '`debet6`', 5, -1, FALSE, '`debet6`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->debet6->Sortable = TRUE; // Allow sort
		$this->debet6->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['debet6'] = &$this->debet6;

		// credit6
		$this->credit6 = new cField('tlaporan', 'tlaporan', 'x_credit6', 'credit6', '`credit6`', '`credit6`', 5, -1, FALSE, '`credit6`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->credit6->Sortable = TRUE; // Allow sort
		$this->credit6->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['credit6'] = &$this->credit6;

		// saldo6
		$this->saldo6 = new cField('tlaporan', 'tlaporan', 'x_saldo6', 'saldo6', '`saldo6`', '`saldo6`', 5, -1, FALSE, '`saldo6`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->saldo6->Sortable = TRUE; // Allow sort
		$this->saldo6->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['saldo6'] = &$this->saldo6;

		// debet7
		$this->debet7 = new cField('tlaporan', 'tlaporan', 'x_debet7', 'debet7', '`debet7`', '`debet7`', 5, -1, FALSE, '`debet7`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->debet7->Sortable = TRUE; // Allow sort
		$this->debet7->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['debet7'] = &$this->debet7;

		// credit7
		$this->credit7 = new cField('tlaporan', 'tlaporan', 'x_credit7', 'credit7', '`credit7`', '`credit7`', 5, -1, FALSE, '`credit7`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->credit7->Sortable = TRUE; // Allow sort
		$this->credit7->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['credit7'] = &$this->credit7;

		// saldo7
		$this->saldo7 = new cField('tlaporan', 'tlaporan', 'x_saldo7', 'saldo7', '`saldo7`', '`saldo7`', 5, -1, FALSE, '`saldo7`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->saldo7->Sortable = TRUE; // Allow sort
		$this->saldo7->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['saldo7'] = &$this->saldo7;

		// debet8
		$this->debet8 = new cField('tlaporan', 'tlaporan', 'x_debet8', 'debet8', '`debet8`', '`debet8`', 5, -1, FALSE, '`debet8`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->debet8->Sortable = TRUE; // Allow sort
		$this->debet8->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['debet8'] = &$this->debet8;

		// credit8
		$this->credit8 = new cField('tlaporan', 'tlaporan', 'x_credit8', 'credit8', '`credit8`', '`credit8`', 5, -1, FALSE, '`credit8`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->credit8->Sortable = TRUE; // Allow sort
		$this->credit8->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['credit8'] = &$this->credit8;

		// saldo8
		$this->saldo8 = new cField('tlaporan', 'tlaporan', 'x_saldo8', 'saldo8', '`saldo8`', '`saldo8`', 5, -1, FALSE, '`saldo8`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->saldo8->Sortable = TRUE; // Allow sort
		$this->saldo8->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['saldo8'] = &$this->saldo8;

		// debet9
		$this->debet9 = new cField('tlaporan', 'tlaporan', 'x_debet9', 'debet9', '`debet9`', '`debet9`', 5, -1, FALSE, '`debet9`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->debet9->Sortable = TRUE; // Allow sort
		$this->debet9->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['debet9'] = &$this->debet9;

		// credit9
		$this->credit9 = new cField('tlaporan', 'tlaporan', 'x_credit9', 'credit9', '`credit9`', '`credit9`', 5, -1, FALSE, '`credit9`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->credit9->Sortable = TRUE; // Allow sort
		$this->credit9->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['credit9'] = &$this->credit9;

		// saldo9
		$this->saldo9 = new cField('tlaporan', 'tlaporan', 'x_saldo9', 'saldo9', '`saldo9`', '`saldo9`', 5, -1, FALSE, '`saldo9`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->saldo9->Sortable = TRUE; // Allow sort
		$this->saldo9->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['saldo9'] = &$this->saldo9;

		// debet10
		$this->debet10 = new cField('tlaporan', 'tlaporan', 'x_debet10', 'debet10', '`debet10`', '`debet10`', 5, -1, FALSE, '`debet10`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->debet10->Sortable = TRUE; // Allow sort
		$this->debet10->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['debet10'] = &$this->debet10;

		// credit10
		$this->credit10 = new cField('tlaporan', 'tlaporan', 'x_credit10', 'credit10', '`credit10`', '`credit10`', 5, -1, FALSE, '`credit10`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->credit10->Sortable = TRUE; // Allow sort
		$this->credit10->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['credit10'] = &$this->credit10;

		// saldo10
		$this->saldo10 = new cField('tlaporan', 'tlaporan', 'x_saldo10', 'saldo10', '`saldo10`', '`saldo10`', 5, -1, FALSE, '`saldo10`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->saldo10->Sortable = TRUE; // Allow sort
		$this->saldo10->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['saldo10'] = &$this->saldo10;

		// debet11
		$this->debet11 = new cField('tlaporan', 'tlaporan', 'x_debet11', 'debet11', '`debet11`', '`debet11`', 5, -1, FALSE, '`debet11`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->debet11->Sortable = TRUE; // Allow sort
		$this->debet11->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['debet11'] = &$this->debet11;

		// credit11
		$this->credit11 = new cField('tlaporan', 'tlaporan', 'x_credit11', 'credit11', '`credit11`', '`credit11`', 5, -1, FALSE, '`credit11`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->credit11->Sortable = TRUE; // Allow sort
		$this->credit11->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['credit11'] = &$this->credit11;

		// saldo11
		$this->saldo11 = new cField('tlaporan', 'tlaporan', 'x_saldo11', 'saldo11', '`saldo11`', '`saldo11`', 5, -1, FALSE, '`saldo11`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->saldo11->Sortable = TRUE; // Allow sort
		$this->saldo11->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['saldo11'] = &$this->saldo11;

		// debet12
		$this->debet12 = new cField('tlaporan', 'tlaporan', 'x_debet12', 'debet12', '`debet12`', '`debet12`', 5, -1, FALSE, '`debet12`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->debet12->Sortable = TRUE; // Allow sort
		$this->debet12->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['debet12'] = &$this->debet12;

		// credit12
		$this->credit12 = new cField('tlaporan', 'tlaporan', 'x_credit12', 'credit12', '`credit12`', '`credit12`', 5, -1, FALSE, '`credit12`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->credit12->Sortable = TRUE; // Allow sort
		$this->credit12->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['credit12'] = &$this->credit12;

		// saldo12
		$this->saldo12 = new cField('tlaporan', 'tlaporan', 'x_saldo12', 'saldo12', '`saldo12`', '`saldo12`', 5, -1, FALSE, '`saldo12`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->saldo12->Sortable = TRUE; // Allow sort
		$this->saldo12->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['saldo12'] = &$this->saldo12;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`tlaporan`";
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
			if (array_key_exists('nomor', $rs))
				ew_AddFilter($where, ew_QuotedName('nomor', $this->DBID) . '=' . ew_QuotedValue($rs['nomor'], $this->nomor->FldDataType, $this->DBID));
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
		return "`nomor` = @nomor@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->nomor->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@nomor@", ew_AdjustSql($this->nomor->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "tlaporanlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "tlaporanlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("tlaporanview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("tlaporanview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "tlaporanadd.php?" . $this->UrlParm($parm);
		else
			$url = "tlaporanadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("tlaporanedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("tlaporanadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("tlaporandelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "nomor:" . ew_VarToJson($this->nomor->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->nomor->CurrentValue)) {
			$sUrl .= "nomor=" . urlencode($this->nomor->CurrentValue);
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
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["nomor"]))
				$arKeys[] = ew_StripSlashes($_POST["nomor"]);
			elseif (isset($_GET["nomor"]))
				$arKeys[] = ew_StripSlashes($_GET["nomor"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
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
			$this->nomor->CurrentValue = $key;
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
		$this->nomor->setDbValue($rs->fields('nomor'));
		$this->transaksi->setDbValue($rs->fields('transaksi'));
		$this->referensi->setDbValue($rs->fields('referensi'));
		$this->group->setDbValue($rs->fields('group'));
		$this->rekening->setDbValue($rs->fields('rekening'));
		$this->tipe->setDbValue($rs->fields('tipe'));
		$this->posisi->setDbValue($rs->fields('posisi'));
		$this->laporan->setDbValue($rs->fields('laporan'));
		$this->keterangan->setDbValue($rs->fields('keterangan'));
		$this->debet1->setDbValue($rs->fields('debet1'));
		$this->credit1->setDbValue($rs->fields('credit1'));
		$this->saldo1->setDbValue($rs->fields('saldo1'));
		$this->debet2->setDbValue($rs->fields('debet2'));
		$this->credit2->setDbValue($rs->fields('credit2'));
		$this->saldo2->setDbValue($rs->fields('saldo2'));
		$this->debet3->setDbValue($rs->fields('debet3'));
		$this->credit3->setDbValue($rs->fields('credit3'));
		$this->saldo3->setDbValue($rs->fields('saldo3'));
		$this->debet4->setDbValue($rs->fields('debet4'));
		$this->credit4->setDbValue($rs->fields('credit4'));
		$this->saldo4->setDbValue($rs->fields('saldo4'));
		$this->debet5->setDbValue($rs->fields('debet5'));
		$this->credit5->setDbValue($rs->fields('credit5'));
		$this->saldo5->setDbValue($rs->fields('saldo5'));
		$this->debet6->setDbValue($rs->fields('debet6'));
		$this->credit6->setDbValue($rs->fields('credit6'));
		$this->saldo6->setDbValue($rs->fields('saldo6'));
		$this->debet7->setDbValue($rs->fields('debet7'));
		$this->credit7->setDbValue($rs->fields('credit7'));
		$this->saldo7->setDbValue($rs->fields('saldo7'));
		$this->debet8->setDbValue($rs->fields('debet8'));
		$this->credit8->setDbValue($rs->fields('credit8'));
		$this->saldo8->setDbValue($rs->fields('saldo8'));
		$this->debet9->setDbValue($rs->fields('debet9'));
		$this->credit9->setDbValue($rs->fields('credit9'));
		$this->saldo9->setDbValue($rs->fields('saldo9'));
		$this->debet10->setDbValue($rs->fields('debet10'));
		$this->credit10->setDbValue($rs->fields('credit10'));
		$this->saldo10->setDbValue($rs->fields('saldo10'));
		$this->debet11->setDbValue($rs->fields('debet11'));
		$this->credit11->setDbValue($rs->fields('credit11'));
		$this->saldo11->setDbValue($rs->fields('saldo11'));
		$this->debet12->setDbValue($rs->fields('debet12'));
		$this->credit12->setDbValue($rs->fields('credit12'));
		$this->saldo12->setDbValue($rs->fields('saldo12'));
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
		// nomor
		// transaksi
		// referensi
		// group
		// rekening
		// tipe
		// posisi
		// laporan
		// keterangan
		// debet1
		// credit1
		// saldo1
		// debet2
		// credit2
		// saldo2
		// debet3
		// credit3
		// saldo3
		// debet4
		// credit4
		// saldo4
		// debet5
		// credit5
		// saldo5
		// debet6
		// credit6
		// saldo6
		// debet7
		// credit7
		// saldo7
		// debet8
		// credit8
		// saldo8
		// debet9
		// credit9
		// saldo9
		// debet10
		// credit10
		// saldo10
		// debet11
		// credit11
		// saldo11
		// debet12
		// credit12
		// saldo12
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

		// group
		$this->group->ViewValue = $this->group->CurrentValue;
		$this->group->ViewCustomAttributes = "";

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

		// keterangan
		$this->keterangan->ViewValue = $this->keterangan->CurrentValue;
		$this->keterangan->ViewCustomAttributes = "";

		// debet1
		$this->debet1->ViewValue = $this->debet1->CurrentValue;
		$this->debet1->ViewCustomAttributes = "";

		// credit1
		$this->credit1->ViewValue = $this->credit1->CurrentValue;
		$this->credit1->ViewCustomAttributes = "";

		// saldo1
		$this->saldo1->ViewValue = $this->saldo1->CurrentValue;
		$this->saldo1->ViewCustomAttributes = "";

		// debet2
		$this->debet2->ViewValue = $this->debet2->CurrentValue;
		$this->debet2->ViewCustomAttributes = "";

		// credit2
		$this->credit2->ViewValue = $this->credit2->CurrentValue;
		$this->credit2->ViewCustomAttributes = "";

		// saldo2
		$this->saldo2->ViewValue = $this->saldo2->CurrentValue;
		$this->saldo2->ViewCustomAttributes = "";

		// debet3
		$this->debet3->ViewValue = $this->debet3->CurrentValue;
		$this->debet3->ViewCustomAttributes = "";

		// credit3
		$this->credit3->ViewValue = $this->credit3->CurrentValue;
		$this->credit3->ViewCustomAttributes = "";

		// saldo3
		$this->saldo3->ViewValue = $this->saldo3->CurrentValue;
		$this->saldo3->ViewCustomAttributes = "";

		// debet4
		$this->debet4->ViewValue = $this->debet4->CurrentValue;
		$this->debet4->ViewCustomAttributes = "";

		// credit4
		$this->credit4->ViewValue = $this->credit4->CurrentValue;
		$this->credit4->ViewCustomAttributes = "";

		// saldo4
		$this->saldo4->ViewValue = $this->saldo4->CurrentValue;
		$this->saldo4->ViewCustomAttributes = "";

		// debet5
		$this->debet5->ViewValue = $this->debet5->CurrentValue;
		$this->debet5->ViewCustomAttributes = "";

		// credit5
		$this->credit5->ViewValue = $this->credit5->CurrentValue;
		$this->credit5->ViewCustomAttributes = "";

		// saldo5
		$this->saldo5->ViewValue = $this->saldo5->CurrentValue;
		$this->saldo5->ViewCustomAttributes = "";

		// debet6
		$this->debet6->ViewValue = $this->debet6->CurrentValue;
		$this->debet6->ViewCustomAttributes = "";

		// credit6
		$this->credit6->ViewValue = $this->credit6->CurrentValue;
		$this->credit6->ViewCustomAttributes = "";

		// saldo6
		$this->saldo6->ViewValue = $this->saldo6->CurrentValue;
		$this->saldo6->ViewCustomAttributes = "";

		// debet7
		$this->debet7->ViewValue = $this->debet7->CurrentValue;
		$this->debet7->ViewCustomAttributes = "";

		// credit7
		$this->credit7->ViewValue = $this->credit7->CurrentValue;
		$this->credit7->ViewCustomAttributes = "";

		// saldo7
		$this->saldo7->ViewValue = $this->saldo7->CurrentValue;
		$this->saldo7->ViewCustomAttributes = "";

		// debet8
		$this->debet8->ViewValue = $this->debet8->CurrentValue;
		$this->debet8->ViewCustomAttributes = "";

		// credit8
		$this->credit8->ViewValue = $this->credit8->CurrentValue;
		$this->credit8->ViewCustomAttributes = "";

		// saldo8
		$this->saldo8->ViewValue = $this->saldo8->CurrentValue;
		$this->saldo8->ViewCustomAttributes = "";

		// debet9
		$this->debet9->ViewValue = $this->debet9->CurrentValue;
		$this->debet9->ViewCustomAttributes = "";

		// credit9
		$this->credit9->ViewValue = $this->credit9->CurrentValue;
		$this->credit9->ViewCustomAttributes = "";

		// saldo9
		$this->saldo9->ViewValue = $this->saldo9->CurrentValue;
		$this->saldo9->ViewCustomAttributes = "";

		// debet10
		$this->debet10->ViewValue = $this->debet10->CurrentValue;
		$this->debet10->ViewCustomAttributes = "";

		// credit10
		$this->credit10->ViewValue = $this->credit10->CurrentValue;
		$this->credit10->ViewCustomAttributes = "";

		// saldo10
		$this->saldo10->ViewValue = $this->saldo10->CurrentValue;
		$this->saldo10->ViewCustomAttributes = "";

		// debet11
		$this->debet11->ViewValue = $this->debet11->CurrentValue;
		$this->debet11->ViewCustomAttributes = "";

		// credit11
		$this->credit11->ViewValue = $this->credit11->CurrentValue;
		$this->credit11->ViewCustomAttributes = "";

		// saldo11
		$this->saldo11->ViewValue = $this->saldo11->CurrentValue;
		$this->saldo11->ViewCustomAttributes = "";

		// debet12
		$this->debet12->ViewValue = $this->debet12->CurrentValue;
		$this->debet12->ViewCustomAttributes = "";

		// credit12
		$this->credit12->ViewValue = $this->credit12->CurrentValue;
		$this->credit12->ViewCustomAttributes = "";

		// saldo12
		$this->saldo12->ViewValue = $this->saldo12->CurrentValue;
		$this->saldo12->ViewCustomAttributes = "";

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

		// group
		$this->group->LinkCustomAttributes = "";
		$this->group->HrefValue = "";
		$this->group->TooltipValue = "";

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

		// keterangan
		$this->keterangan->LinkCustomAttributes = "";
		$this->keterangan->HrefValue = "";
		$this->keterangan->TooltipValue = "";

		// debet1
		$this->debet1->LinkCustomAttributes = "";
		$this->debet1->HrefValue = "";
		$this->debet1->TooltipValue = "";

		// credit1
		$this->credit1->LinkCustomAttributes = "";
		$this->credit1->HrefValue = "";
		$this->credit1->TooltipValue = "";

		// saldo1
		$this->saldo1->LinkCustomAttributes = "";
		$this->saldo1->HrefValue = "";
		$this->saldo1->TooltipValue = "";

		// debet2
		$this->debet2->LinkCustomAttributes = "";
		$this->debet2->HrefValue = "";
		$this->debet2->TooltipValue = "";

		// credit2
		$this->credit2->LinkCustomAttributes = "";
		$this->credit2->HrefValue = "";
		$this->credit2->TooltipValue = "";

		// saldo2
		$this->saldo2->LinkCustomAttributes = "";
		$this->saldo2->HrefValue = "";
		$this->saldo2->TooltipValue = "";

		// debet3
		$this->debet3->LinkCustomAttributes = "";
		$this->debet3->HrefValue = "";
		$this->debet3->TooltipValue = "";

		// credit3
		$this->credit3->LinkCustomAttributes = "";
		$this->credit3->HrefValue = "";
		$this->credit3->TooltipValue = "";

		// saldo3
		$this->saldo3->LinkCustomAttributes = "";
		$this->saldo3->HrefValue = "";
		$this->saldo3->TooltipValue = "";

		// debet4
		$this->debet4->LinkCustomAttributes = "";
		$this->debet4->HrefValue = "";
		$this->debet4->TooltipValue = "";

		// credit4
		$this->credit4->LinkCustomAttributes = "";
		$this->credit4->HrefValue = "";
		$this->credit4->TooltipValue = "";

		// saldo4
		$this->saldo4->LinkCustomAttributes = "";
		$this->saldo4->HrefValue = "";
		$this->saldo4->TooltipValue = "";

		// debet5
		$this->debet5->LinkCustomAttributes = "";
		$this->debet5->HrefValue = "";
		$this->debet5->TooltipValue = "";

		// credit5
		$this->credit5->LinkCustomAttributes = "";
		$this->credit5->HrefValue = "";
		$this->credit5->TooltipValue = "";

		// saldo5
		$this->saldo5->LinkCustomAttributes = "";
		$this->saldo5->HrefValue = "";
		$this->saldo5->TooltipValue = "";

		// debet6
		$this->debet6->LinkCustomAttributes = "";
		$this->debet6->HrefValue = "";
		$this->debet6->TooltipValue = "";

		// credit6
		$this->credit6->LinkCustomAttributes = "";
		$this->credit6->HrefValue = "";
		$this->credit6->TooltipValue = "";

		// saldo6
		$this->saldo6->LinkCustomAttributes = "";
		$this->saldo6->HrefValue = "";
		$this->saldo6->TooltipValue = "";

		// debet7
		$this->debet7->LinkCustomAttributes = "";
		$this->debet7->HrefValue = "";
		$this->debet7->TooltipValue = "";

		// credit7
		$this->credit7->LinkCustomAttributes = "";
		$this->credit7->HrefValue = "";
		$this->credit7->TooltipValue = "";

		// saldo7
		$this->saldo7->LinkCustomAttributes = "";
		$this->saldo7->HrefValue = "";
		$this->saldo7->TooltipValue = "";

		// debet8
		$this->debet8->LinkCustomAttributes = "";
		$this->debet8->HrefValue = "";
		$this->debet8->TooltipValue = "";

		// credit8
		$this->credit8->LinkCustomAttributes = "";
		$this->credit8->HrefValue = "";
		$this->credit8->TooltipValue = "";

		// saldo8
		$this->saldo8->LinkCustomAttributes = "";
		$this->saldo8->HrefValue = "";
		$this->saldo8->TooltipValue = "";

		// debet9
		$this->debet9->LinkCustomAttributes = "";
		$this->debet9->HrefValue = "";
		$this->debet9->TooltipValue = "";

		// credit9
		$this->credit9->LinkCustomAttributes = "";
		$this->credit9->HrefValue = "";
		$this->credit9->TooltipValue = "";

		// saldo9
		$this->saldo9->LinkCustomAttributes = "";
		$this->saldo9->HrefValue = "";
		$this->saldo9->TooltipValue = "";

		// debet10
		$this->debet10->LinkCustomAttributes = "";
		$this->debet10->HrefValue = "";
		$this->debet10->TooltipValue = "";

		// credit10
		$this->credit10->LinkCustomAttributes = "";
		$this->credit10->HrefValue = "";
		$this->credit10->TooltipValue = "";

		// saldo10
		$this->saldo10->LinkCustomAttributes = "";
		$this->saldo10->HrefValue = "";
		$this->saldo10->TooltipValue = "";

		// debet11
		$this->debet11->LinkCustomAttributes = "";
		$this->debet11->HrefValue = "";
		$this->debet11->TooltipValue = "";

		// credit11
		$this->credit11->LinkCustomAttributes = "";
		$this->credit11->HrefValue = "";
		$this->credit11->TooltipValue = "";

		// saldo11
		$this->saldo11->LinkCustomAttributes = "";
		$this->saldo11->HrefValue = "";
		$this->saldo11->TooltipValue = "";

		// debet12
		$this->debet12->LinkCustomAttributes = "";
		$this->debet12->HrefValue = "";
		$this->debet12->TooltipValue = "";

		// credit12
		$this->credit12->LinkCustomAttributes = "";
		$this->credit12->HrefValue = "";
		$this->credit12->TooltipValue = "";

		// saldo12
		$this->saldo12->LinkCustomAttributes = "";
		$this->saldo12->HrefValue = "";
		$this->saldo12->TooltipValue = "";

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
		$this->id->PlaceHolder = ew_RemoveHtml($this->id->FldCaption());

		// nomor
		$this->nomor->EditAttrs["class"] = "form-control";
		$this->nomor->EditCustomAttributes = "";
		$this->nomor->EditValue = $this->nomor->CurrentValue;
		$this->nomor->ViewCustomAttributes = "";

		// transaksi
		$this->transaksi->EditAttrs["class"] = "form-control";
		$this->transaksi->EditCustomAttributes = "";
		$this->transaksi->EditValue = $this->transaksi->CurrentValue;
		$this->transaksi->PlaceHolder = ew_RemoveHtml($this->transaksi->FldCaption());

		// referensi
		$this->referensi->EditAttrs["class"] = "form-control";
		$this->referensi->EditCustomAttributes = "";
		$this->referensi->EditValue = $this->referensi->CurrentValue;
		$this->referensi->PlaceHolder = ew_RemoveHtml($this->referensi->FldCaption());

		// group
		$this->group->EditAttrs["class"] = "form-control";
		$this->group->EditCustomAttributes = "";
		$this->group->EditValue = $this->group->CurrentValue;
		$this->group->PlaceHolder = ew_RemoveHtml($this->group->FldCaption());

		// rekening
		$this->rekening->EditAttrs["class"] = "form-control";
		$this->rekening->EditCustomAttributes = "";
		$this->rekening->EditValue = $this->rekening->CurrentValue;
		$this->rekening->PlaceHolder = ew_RemoveHtml($this->rekening->FldCaption());

		// tipe
		$this->tipe->EditAttrs["class"] = "form-control";
		$this->tipe->EditCustomAttributes = "";
		$this->tipe->EditValue = $this->tipe->CurrentValue;
		$this->tipe->PlaceHolder = ew_RemoveHtml($this->tipe->FldCaption());

		// posisi
		$this->posisi->EditAttrs["class"] = "form-control";
		$this->posisi->EditCustomAttributes = "";
		$this->posisi->EditValue = $this->posisi->CurrentValue;
		$this->posisi->PlaceHolder = ew_RemoveHtml($this->posisi->FldCaption());

		// laporan
		$this->laporan->EditAttrs["class"] = "form-control";
		$this->laporan->EditCustomAttributes = "";
		$this->laporan->EditValue = $this->laporan->CurrentValue;
		$this->laporan->PlaceHolder = ew_RemoveHtml($this->laporan->FldCaption());

		// keterangan
		$this->keterangan->EditAttrs["class"] = "form-control";
		$this->keterangan->EditCustomAttributes = "";
		$this->keterangan->EditValue = $this->keterangan->CurrentValue;
		$this->keterangan->PlaceHolder = ew_RemoveHtml($this->keterangan->FldCaption());

		// debet1
		$this->debet1->EditAttrs["class"] = "form-control";
		$this->debet1->EditCustomAttributes = "";
		$this->debet1->EditValue = $this->debet1->CurrentValue;
		$this->debet1->PlaceHolder = ew_RemoveHtml($this->debet1->FldCaption());
		if (strval($this->debet1->EditValue) <> "" && is_numeric($this->debet1->EditValue)) $this->debet1->EditValue = ew_FormatNumber($this->debet1->EditValue, -2, -1, -2, 0);

		// credit1
		$this->credit1->EditAttrs["class"] = "form-control";
		$this->credit1->EditCustomAttributes = "";
		$this->credit1->EditValue = $this->credit1->CurrentValue;
		$this->credit1->PlaceHolder = ew_RemoveHtml($this->credit1->FldCaption());
		if (strval($this->credit1->EditValue) <> "" && is_numeric($this->credit1->EditValue)) $this->credit1->EditValue = ew_FormatNumber($this->credit1->EditValue, -2, -1, -2, 0);

		// saldo1
		$this->saldo1->EditAttrs["class"] = "form-control";
		$this->saldo1->EditCustomAttributes = "";
		$this->saldo1->EditValue = $this->saldo1->CurrentValue;
		$this->saldo1->PlaceHolder = ew_RemoveHtml($this->saldo1->FldCaption());
		if (strval($this->saldo1->EditValue) <> "" && is_numeric($this->saldo1->EditValue)) $this->saldo1->EditValue = ew_FormatNumber($this->saldo1->EditValue, -2, -1, -2, 0);

		// debet2
		$this->debet2->EditAttrs["class"] = "form-control";
		$this->debet2->EditCustomAttributes = "";
		$this->debet2->EditValue = $this->debet2->CurrentValue;
		$this->debet2->PlaceHolder = ew_RemoveHtml($this->debet2->FldCaption());
		if (strval($this->debet2->EditValue) <> "" && is_numeric($this->debet2->EditValue)) $this->debet2->EditValue = ew_FormatNumber($this->debet2->EditValue, -2, -1, -2, 0);

		// credit2
		$this->credit2->EditAttrs["class"] = "form-control";
		$this->credit2->EditCustomAttributes = "";
		$this->credit2->EditValue = $this->credit2->CurrentValue;
		$this->credit2->PlaceHolder = ew_RemoveHtml($this->credit2->FldCaption());
		if (strval($this->credit2->EditValue) <> "" && is_numeric($this->credit2->EditValue)) $this->credit2->EditValue = ew_FormatNumber($this->credit2->EditValue, -2, -1, -2, 0);

		// saldo2
		$this->saldo2->EditAttrs["class"] = "form-control";
		$this->saldo2->EditCustomAttributes = "";
		$this->saldo2->EditValue = $this->saldo2->CurrentValue;
		$this->saldo2->PlaceHolder = ew_RemoveHtml($this->saldo2->FldCaption());
		if (strval($this->saldo2->EditValue) <> "" && is_numeric($this->saldo2->EditValue)) $this->saldo2->EditValue = ew_FormatNumber($this->saldo2->EditValue, -2, -1, -2, 0);

		// debet3
		$this->debet3->EditAttrs["class"] = "form-control";
		$this->debet3->EditCustomAttributes = "";
		$this->debet3->EditValue = $this->debet3->CurrentValue;
		$this->debet3->PlaceHolder = ew_RemoveHtml($this->debet3->FldCaption());
		if (strval($this->debet3->EditValue) <> "" && is_numeric($this->debet3->EditValue)) $this->debet3->EditValue = ew_FormatNumber($this->debet3->EditValue, -2, -1, -2, 0);

		// credit3
		$this->credit3->EditAttrs["class"] = "form-control";
		$this->credit3->EditCustomAttributes = "";
		$this->credit3->EditValue = $this->credit3->CurrentValue;
		$this->credit3->PlaceHolder = ew_RemoveHtml($this->credit3->FldCaption());
		if (strval($this->credit3->EditValue) <> "" && is_numeric($this->credit3->EditValue)) $this->credit3->EditValue = ew_FormatNumber($this->credit3->EditValue, -2, -1, -2, 0);

		// saldo3
		$this->saldo3->EditAttrs["class"] = "form-control";
		$this->saldo3->EditCustomAttributes = "";
		$this->saldo3->EditValue = $this->saldo3->CurrentValue;
		$this->saldo3->PlaceHolder = ew_RemoveHtml($this->saldo3->FldCaption());
		if (strval($this->saldo3->EditValue) <> "" && is_numeric($this->saldo3->EditValue)) $this->saldo3->EditValue = ew_FormatNumber($this->saldo3->EditValue, -2, -1, -2, 0);

		// debet4
		$this->debet4->EditAttrs["class"] = "form-control";
		$this->debet4->EditCustomAttributes = "";
		$this->debet4->EditValue = $this->debet4->CurrentValue;
		$this->debet4->PlaceHolder = ew_RemoveHtml($this->debet4->FldCaption());
		if (strval($this->debet4->EditValue) <> "" && is_numeric($this->debet4->EditValue)) $this->debet4->EditValue = ew_FormatNumber($this->debet4->EditValue, -2, -1, -2, 0);

		// credit4
		$this->credit4->EditAttrs["class"] = "form-control";
		$this->credit4->EditCustomAttributes = "";
		$this->credit4->EditValue = $this->credit4->CurrentValue;
		$this->credit4->PlaceHolder = ew_RemoveHtml($this->credit4->FldCaption());
		if (strval($this->credit4->EditValue) <> "" && is_numeric($this->credit4->EditValue)) $this->credit4->EditValue = ew_FormatNumber($this->credit4->EditValue, -2, -1, -2, 0);

		// saldo4
		$this->saldo4->EditAttrs["class"] = "form-control";
		$this->saldo4->EditCustomAttributes = "";
		$this->saldo4->EditValue = $this->saldo4->CurrentValue;
		$this->saldo4->PlaceHolder = ew_RemoveHtml($this->saldo4->FldCaption());
		if (strval($this->saldo4->EditValue) <> "" && is_numeric($this->saldo4->EditValue)) $this->saldo4->EditValue = ew_FormatNumber($this->saldo4->EditValue, -2, -1, -2, 0);

		// debet5
		$this->debet5->EditAttrs["class"] = "form-control";
		$this->debet5->EditCustomAttributes = "";
		$this->debet5->EditValue = $this->debet5->CurrentValue;
		$this->debet5->PlaceHolder = ew_RemoveHtml($this->debet5->FldCaption());
		if (strval($this->debet5->EditValue) <> "" && is_numeric($this->debet5->EditValue)) $this->debet5->EditValue = ew_FormatNumber($this->debet5->EditValue, -2, -1, -2, 0);

		// credit5
		$this->credit5->EditAttrs["class"] = "form-control";
		$this->credit5->EditCustomAttributes = "";
		$this->credit5->EditValue = $this->credit5->CurrentValue;
		$this->credit5->PlaceHolder = ew_RemoveHtml($this->credit5->FldCaption());
		if (strval($this->credit5->EditValue) <> "" && is_numeric($this->credit5->EditValue)) $this->credit5->EditValue = ew_FormatNumber($this->credit5->EditValue, -2, -1, -2, 0);

		// saldo5
		$this->saldo5->EditAttrs["class"] = "form-control";
		$this->saldo5->EditCustomAttributes = "";
		$this->saldo5->EditValue = $this->saldo5->CurrentValue;
		$this->saldo5->PlaceHolder = ew_RemoveHtml($this->saldo5->FldCaption());
		if (strval($this->saldo5->EditValue) <> "" && is_numeric($this->saldo5->EditValue)) $this->saldo5->EditValue = ew_FormatNumber($this->saldo5->EditValue, -2, -1, -2, 0);

		// debet6
		$this->debet6->EditAttrs["class"] = "form-control";
		$this->debet6->EditCustomAttributes = "";
		$this->debet6->EditValue = $this->debet6->CurrentValue;
		$this->debet6->PlaceHolder = ew_RemoveHtml($this->debet6->FldCaption());
		if (strval($this->debet6->EditValue) <> "" && is_numeric($this->debet6->EditValue)) $this->debet6->EditValue = ew_FormatNumber($this->debet6->EditValue, -2, -1, -2, 0);

		// credit6
		$this->credit6->EditAttrs["class"] = "form-control";
		$this->credit6->EditCustomAttributes = "";
		$this->credit6->EditValue = $this->credit6->CurrentValue;
		$this->credit6->PlaceHolder = ew_RemoveHtml($this->credit6->FldCaption());
		if (strval($this->credit6->EditValue) <> "" && is_numeric($this->credit6->EditValue)) $this->credit6->EditValue = ew_FormatNumber($this->credit6->EditValue, -2, -1, -2, 0);

		// saldo6
		$this->saldo6->EditAttrs["class"] = "form-control";
		$this->saldo6->EditCustomAttributes = "";
		$this->saldo6->EditValue = $this->saldo6->CurrentValue;
		$this->saldo6->PlaceHolder = ew_RemoveHtml($this->saldo6->FldCaption());
		if (strval($this->saldo6->EditValue) <> "" && is_numeric($this->saldo6->EditValue)) $this->saldo6->EditValue = ew_FormatNumber($this->saldo6->EditValue, -2, -1, -2, 0);

		// debet7
		$this->debet7->EditAttrs["class"] = "form-control";
		$this->debet7->EditCustomAttributes = "";
		$this->debet7->EditValue = $this->debet7->CurrentValue;
		$this->debet7->PlaceHolder = ew_RemoveHtml($this->debet7->FldCaption());
		if (strval($this->debet7->EditValue) <> "" && is_numeric($this->debet7->EditValue)) $this->debet7->EditValue = ew_FormatNumber($this->debet7->EditValue, -2, -1, -2, 0);

		// credit7
		$this->credit7->EditAttrs["class"] = "form-control";
		$this->credit7->EditCustomAttributes = "";
		$this->credit7->EditValue = $this->credit7->CurrentValue;
		$this->credit7->PlaceHolder = ew_RemoveHtml($this->credit7->FldCaption());
		if (strval($this->credit7->EditValue) <> "" && is_numeric($this->credit7->EditValue)) $this->credit7->EditValue = ew_FormatNumber($this->credit7->EditValue, -2, -1, -2, 0);

		// saldo7
		$this->saldo7->EditAttrs["class"] = "form-control";
		$this->saldo7->EditCustomAttributes = "";
		$this->saldo7->EditValue = $this->saldo7->CurrentValue;
		$this->saldo7->PlaceHolder = ew_RemoveHtml($this->saldo7->FldCaption());
		if (strval($this->saldo7->EditValue) <> "" && is_numeric($this->saldo7->EditValue)) $this->saldo7->EditValue = ew_FormatNumber($this->saldo7->EditValue, -2, -1, -2, 0);

		// debet8
		$this->debet8->EditAttrs["class"] = "form-control";
		$this->debet8->EditCustomAttributes = "";
		$this->debet8->EditValue = $this->debet8->CurrentValue;
		$this->debet8->PlaceHolder = ew_RemoveHtml($this->debet8->FldCaption());
		if (strval($this->debet8->EditValue) <> "" && is_numeric($this->debet8->EditValue)) $this->debet8->EditValue = ew_FormatNumber($this->debet8->EditValue, -2, -1, -2, 0);

		// credit8
		$this->credit8->EditAttrs["class"] = "form-control";
		$this->credit8->EditCustomAttributes = "";
		$this->credit8->EditValue = $this->credit8->CurrentValue;
		$this->credit8->PlaceHolder = ew_RemoveHtml($this->credit8->FldCaption());
		if (strval($this->credit8->EditValue) <> "" && is_numeric($this->credit8->EditValue)) $this->credit8->EditValue = ew_FormatNumber($this->credit8->EditValue, -2, -1, -2, 0);

		// saldo8
		$this->saldo8->EditAttrs["class"] = "form-control";
		$this->saldo8->EditCustomAttributes = "";
		$this->saldo8->EditValue = $this->saldo8->CurrentValue;
		$this->saldo8->PlaceHolder = ew_RemoveHtml($this->saldo8->FldCaption());
		if (strval($this->saldo8->EditValue) <> "" && is_numeric($this->saldo8->EditValue)) $this->saldo8->EditValue = ew_FormatNumber($this->saldo8->EditValue, -2, -1, -2, 0);

		// debet9
		$this->debet9->EditAttrs["class"] = "form-control";
		$this->debet9->EditCustomAttributes = "";
		$this->debet9->EditValue = $this->debet9->CurrentValue;
		$this->debet9->PlaceHolder = ew_RemoveHtml($this->debet9->FldCaption());
		if (strval($this->debet9->EditValue) <> "" && is_numeric($this->debet9->EditValue)) $this->debet9->EditValue = ew_FormatNumber($this->debet9->EditValue, -2, -1, -2, 0);

		// credit9
		$this->credit9->EditAttrs["class"] = "form-control";
		$this->credit9->EditCustomAttributes = "";
		$this->credit9->EditValue = $this->credit9->CurrentValue;
		$this->credit9->PlaceHolder = ew_RemoveHtml($this->credit9->FldCaption());
		if (strval($this->credit9->EditValue) <> "" && is_numeric($this->credit9->EditValue)) $this->credit9->EditValue = ew_FormatNumber($this->credit9->EditValue, -2, -1, -2, 0);

		// saldo9
		$this->saldo9->EditAttrs["class"] = "form-control";
		$this->saldo9->EditCustomAttributes = "";
		$this->saldo9->EditValue = $this->saldo9->CurrentValue;
		$this->saldo9->PlaceHolder = ew_RemoveHtml($this->saldo9->FldCaption());
		if (strval($this->saldo9->EditValue) <> "" && is_numeric($this->saldo9->EditValue)) $this->saldo9->EditValue = ew_FormatNumber($this->saldo9->EditValue, -2, -1, -2, 0);

		// debet10
		$this->debet10->EditAttrs["class"] = "form-control";
		$this->debet10->EditCustomAttributes = "";
		$this->debet10->EditValue = $this->debet10->CurrentValue;
		$this->debet10->PlaceHolder = ew_RemoveHtml($this->debet10->FldCaption());
		if (strval($this->debet10->EditValue) <> "" && is_numeric($this->debet10->EditValue)) $this->debet10->EditValue = ew_FormatNumber($this->debet10->EditValue, -2, -1, -2, 0);

		// credit10
		$this->credit10->EditAttrs["class"] = "form-control";
		$this->credit10->EditCustomAttributes = "";
		$this->credit10->EditValue = $this->credit10->CurrentValue;
		$this->credit10->PlaceHolder = ew_RemoveHtml($this->credit10->FldCaption());
		if (strval($this->credit10->EditValue) <> "" && is_numeric($this->credit10->EditValue)) $this->credit10->EditValue = ew_FormatNumber($this->credit10->EditValue, -2, -1, -2, 0);

		// saldo10
		$this->saldo10->EditAttrs["class"] = "form-control";
		$this->saldo10->EditCustomAttributes = "";
		$this->saldo10->EditValue = $this->saldo10->CurrentValue;
		$this->saldo10->PlaceHolder = ew_RemoveHtml($this->saldo10->FldCaption());
		if (strval($this->saldo10->EditValue) <> "" && is_numeric($this->saldo10->EditValue)) $this->saldo10->EditValue = ew_FormatNumber($this->saldo10->EditValue, -2, -1, -2, 0);

		// debet11
		$this->debet11->EditAttrs["class"] = "form-control";
		$this->debet11->EditCustomAttributes = "";
		$this->debet11->EditValue = $this->debet11->CurrentValue;
		$this->debet11->PlaceHolder = ew_RemoveHtml($this->debet11->FldCaption());
		if (strval($this->debet11->EditValue) <> "" && is_numeric($this->debet11->EditValue)) $this->debet11->EditValue = ew_FormatNumber($this->debet11->EditValue, -2, -1, -2, 0);

		// credit11
		$this->credit11->EditAttrs["class"] = "form-control";
		$this->credit11->EditCustomAttributes = "";
		$this->credit11->EditValue = $this->credit11->CurrentValue;
		$this->credit11->PlaceHolder = ew_RemoveHtml($this->credit11->FldCaption());
		if (strval($this->credit11->EditValue) <> "" && is_numeric($this->credit11->EditValue)) $this->credit11->EditValue = ew_FormatNumber($this->credit11->EditValue, -2, -1, -2, 0);

		// saldo11
		$this->saldo11->EditAttrs["class"] = "form-control";
		$this->saldo11->EditCustomAttributes = "";
		$this->saldo11->EditValue = $this->saldo11->CurrentValue;
		$this->saldo11->PlaceHolder = ew_RemoveHtml($this->saldo11->FldCaption());
		if (strval($this->saldo11->EditValue) <> "" && is_numeric($this->saldo11->EditValue)) $this->saldo11->EditValue = ew_FormatNumber($this->saldo11->EditValue, -2, -1, -2, 0);

		// debet12
		$this->debet12->EditAttrs["class"] = "form-control";
		$this->debet12->EditCustomAttributes = "";
		$this->debet12->EditValue = $this->debet12->CurrentValue;
		$this->debet12->PlaceHolder = ew_RemoveHtml($this->debet12->FldCaption());
		if (strval($this->debet12->EditValue) <> "" && is_numeric($this->debet12->EditValue)) $this->debet12->EditValue = ew_FormatNumber($this->debet12->EditValue, -2, -1, -2, 0);

		// credit12
		$this->credit12->EditAttrs["class"] = "form-control";
		$this->credit12->EditCustomAttributes = "";
		$this->credit12->EditValue = $this->credit12->CurrentValue;
		$this->credit12->PlaceHolder = ew_RemoveHtml($this->credit12->FldCaption());
		if (strval($this->credit12->EditValue) <> "" && is_numeric($this->credit12->EditValue)) $this->credit12->EditValue = ew_FormatNumber($this->credit12->EditValue, -2, -1, -2, 0);

		// saldo12
		$this->saldo12->EditAttrs["class"] = "form-control";
		$this->saldo12->EditCustomAttributes = "";
		$this->saldo12->EditValue = $this->saldo12->CurrentValue;
		$this->saldo12->PlaceHolder = ew_RemoveHtml($this->saldo12->FldCaption());
		if (strval($this->saldo12->EditValue) <> "" && is_numeric($this->saldo12->EditValue)) $this->saldo12->EditValue = ew_FormatNumber($this->saldo12->EditValue, -2, -1, -2, 0);

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
					if ($this->nomor->Exportable) $Doc->ExportCaption($this->nomor);
					if ($this->transaksi->Exportable) $Doc->ExportCaption($this->transaksi);
					if ($this->referensi->Exportable) $Doc->ExportCaption($this->referensi);
					if ($this->group->Exportable) $Doc->ExportCaption($this->group);
					if ($this->rekening->Exportable) $Doc->ExportCaption($this->rekening);
					if ($this->tipe->Exportable) $Doc->ExportCaption($this->tipe);
					if ($this->posisi->Exportable) $Doc->ExportCaption($this->posisi);
					if ($this->laporan->Exportable) $Doc->ExportCaption($this->laporan);
					if ($this->keterangan->Exportable) $Doc->ExportCaption($this->keterangan);
					if ($this->debet1->Exportable) $Doc->ExportCaption($this->debet1);
					if ($this->credit1->Exportable) $Doc->ExportCaption($this->credit1);
					if ($this->saldo1->Exportable) $Doc->ExportCaption($this->saldo1);
					if ($this->debet2->Exportable) $Doc->ExportCaption($this->debet2);
					if ($this->credit2->Exportable) $Doc->ExportCaption($this->credit2);
					if ($this->saldo2->Exportable) $Doc->ExportCaption($this->saldo2);
					if ($this->debet3->Exportable) $Doc->ExportCaption($this->debet3);
					if ($this->credit3->Exportable) $Doc->ExportCaption($this->credit3);
					if ($this->saldo3->Exportable) $Doc->ExportCaption($this->saldo3);
					if ($this->debet4->Exportable) $Doc->ExportCaption($this->debet4);
					if ($this->credit4->Exportable) $Doc->ExportCaption($this->credit4);
					if ($this->saldo4->Exportable) $Doc->ExportCaption($this->saldo4);
					if ($this->debet5->Exportable) $Doc->ExportCaption($this->debet5);
					if ($this->credit5->Exportable) $Doc->ExportCaption($this->credit5);
					if ($this->saldo5->Exportable) $Doc->ExportCaption($this->saldo5);
					if ($this->debet6->Exportable) $Doc->ExportCaption($this->debet6);
					if ($this->credit6->Exportable) $Doc->ExportCaption($this->credit6);
					if ($this->saldo6->Exportable) $Doc->ExportCaption($this->saldo6);
					if ($this->debet7->Exportable) $Doc->ExportCaption($this->debet7);
					if ($this->credit7->Exportable) $Doc->ExportCaption($this->credit7);
					if ($this->saldo7->Exportable) $Doc->ExportCaption($this->saldo7);
					if ($this->debet8->Exportable) $Doc->ExportCaption($this->debet8);
					if ($this->credit8->Exportable) $Doc->ExportCaption($this->credit8);
					if ($this->saldo8->Exportable) $Doc->ExportCaption($this->saldo8);
					if ($this->debet9->Exportable) $Doc->ExportCaption($this->debet9);
					if ($this->credit9->Exportable) $Doc->ExportCaption($this->credit9);
					if ($this->saldo9->Exportable) $Doc->ExportCaption($this->saldo9);
					if ($this->debet10->Exportable) $Doc->ExportCaption($this->debet10);
					if ($this->credit10->Exportable) $Doc->ExportCaption($this->credit10);
					if ($this->saldo10->Exportable) $Doc->ExportCaption($this->saldo10);
					if ($this->debet11->Exportable) $Doc->ExportCaption($this->debet11);
					if ($this->credit11->Exportable) $Doc->ExportCaption($this->credit11);
					if ($this->saldo11->Exportable) $Doc->ExportCaption($this->saldo11);
					if ($this->debet12->Exportable) $Doc->ExportCaption($this->debet12);
					if ($this->credit12->Exportable) $Doc->ExportCaption($this->credit12);
					if ($this->saldo12->Exportable) $Doc->ExportCaption($this->saldo12);
				} else {
					if ($this->tanggal->Exportable) $Doc->ExportCaption($this->tanggal);
					if ($this->periode->Exportable) $Doc->ExportCaption($this->periode);
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->nomor->Exportable) $Doc->ExportCaption($this->nomor);
					if ($this->transaksi->Exportable) $Doc->ExportCaption($this->transaksi);
					if ($this->referensi->Exportable) $Doc->ExportCaption($this->referensi);
					if ($this->group->Exportable) $Doc->ExportCaption($this->group);
					if ($this->rekening->Exportable) $Doc->ExportCaption($this->rekening);
					if ($this->tipe->Exportable) $Doc->ExportCaption($this->tipe);
					if ($this->posisi->Exportable) $Doc->ExportCaption($this->posisi);
					if ($this->laporan->Exportable) $Doc->ExportCaption($this->laporan);
					if ($this->keterangan->Exportable) $Doc->ExportCaption($this->keterangan);
					if ($this->debet1->Exportable) $Doc->ExportCaption($this->debet1);
					if ($this->credit1->Exportable) $Doc->ExportCaption($this->credit1);
					if ($this->saldo1->Exportable) $Doc->ExportCaption($this->saldo1);
					if ($this->debet2->Exportable) $Doc->ExportCaption($this->debet2);
					if ($this->credit2->Exportable) $Doc->ExportCaption($this->credit2);
					if ($this->saldo2->Exportable) $Doc->ExportCaption($this->saldo2);
					if ($this->debet3->Exportable) $Doc->ExportCaption($this->debet3);
					if ($this->credit3->Exportable) $Doc->ExportCaption($this->credit3);
					if ($this->saldo3->Exportable) $Doc->ExportCaption($this->saldo3);
					if ($this->debet4->Exportable) $Doc->ExportCaption($this->debet4);
					if ($this->credit4->Exportable) $Doc->ExportCaption($this->credit4);
					if ($this->saldo4->Exportable) $Doc->ExportCaption($this->saldo4);
					if ($this->debet5->Exportable) $Doc->ExportCaption($this->debet5);
					if ($this->credit5->Exportable) $Doc->ExportCaption($this->credit5);
					if ($this->saldo5->Exportable) $Doc->ExportCaption($this->saldo5);
					if ($this->debet6->Exportable) $Doc->ExportCaption($this->debet6);
					if ($this->credit6->Exportable) $Doc->ExportCaption($this->credit6);
					if ($this->saldo6->Exportable) $Doc->ExportCaption($this->saldo6);
					if ($this->debet7->Exportable) $Doc->ExportCaption($this->debet7);
					if ($this->credit7->Exportable) $Doc->ExportCaption($this->credit7);
					if ($this->saldo7->Exportable) $Doc->ExportCaption($this->saldo7);
					if ($this->debet8->Exportable) $Doc->ExportCaption($this->debet8);
					if ($this->credit8->Exportable) $Doc->ExportCaption($this->credit8);
					if ($this->saldo8->Exportable) $Doc->ExportCaption($this->saldo8);
					if ($this->debet9->Exportable) $Doc->ExportCaption($this->debet9);
					if ($this->credit9->Exportable) $Doc->ExportCaption($this->credit9);
					if ($this->saldo9->Exportable) $Doc->ExportCaption($this->saldo9);
					if ($this->debet10->Exportable) $Doc->ExportCaption($this->debet10);
					if ($this->credit10->Exportable) $Doc->ExportCaption($this->credit10);
					if ($this->saldo10->Exportable) $Doc->ExportCaption($this->saldo10);
					if ($this->debet11->Exportable) $Doc->ExportCaption($this->debet11);
					if ($this->credit11->Exportable) $Doc->ExportCaption($this->credit11);
					if ($this->saldo11->Exportable) $Doc->ExportCaption($this->saldo11);
					if ($this->debet12->Exportable) $Doc->ExportCaption($this->debet12);
					if ($this->credit12->Exportable) $Doc->ExportCaption($this->credit12);
					if ($this->saldo12->Exportable) $Doc->ExportCaption($this->saldo12);
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
						if ($this->nomor->Exportable) $Doc->ExportField($this->nomor);
						if ($this->transaksi->Exportable) $Doc->ExportField($this->transaksi);
						if ($this->referensi->Exportable) $Doc->ExportField($this->referensi);
						if ($this->group->Exportable) $Doc->ExportField($this->group);
						if ($this->rekening->Exportable) $Doc->ExportField($this->rekening);
						if ($this->tipe->Exportable) $Doc->ExportField($this->tipe);
						if ($this->posisi->Exportable) $Doc->ExportField($this->posisi);
						if ($this->laporan->Exportable) $Doc->ExportField($this->laporan);
						if ($this->keterangan->Exportable) $Doc->ExportField($this->keterangan);
						if ($this->debet1->Exportable) $Doc->ExportField($this->debet1);
						if ($this->credit1->Exportable) $Doc->ExportField($this->credit1);
						if ($this->saldo1->Exportable) $Doc->ExportField($this->saldo1);
						if ($this->debet2->Exportable) $Doc->ExportField($this->debet2);
						if ($this->credit2->Exportable) $Doc->ExportField($this->credit2);
						if ($this->saldo2->Exportable) $Doc->ExportField($this->saldo2);
						if ($this->debet3->Exportable) $Doc->ExportField($this->debet3);
						if ($this->credit3->Exportable) $Doc->ExportField($this->credit3);
						if ($this->saldo3->Exportable) $Doc->ExportField($this->saldo3);
						if ($this->debet4->Exportable) $Doc->ExportField($this->debet4);
						if ($this->credit4->Exportable) $Doc->ExportField($this->credit4);
						if ($this->saldo4->Exportable) $Doc->ExportField($this->saldo4);
						if ($this->debet5->Exportable) $Doc->ExportField($this->debet5);
						if ($this->credit5->Exportable) $Doc->ExportField($this->credit5);
						if ($this->saldo5->Exportable) $Doc->ExportField($this->saldo5);
						if ($this->debet6->Exportable) $Doc->ExportField($this->debet6);
						if ($this->credit6->Exportable) $Doc->ExportField($this->credit6);
						if ($this->saldo6->Exportable) $Doc->ExportField($this->saldo6);
						if ($this->debet7->Exportable) $Doc->ExportField($this->debet7);
						if ($this->credit7->Exportable) $Doc->ExportField($this->credit7);
						if ($this->saldo7->Exportable) $Doc->ExportField($this->saldo7);
						if ($this->debet8->Exportable) $Doc->ExportField($this->debet8);
						if ($this->credit8->Exportable) $Doc->ExportField($this->credit8);
						if ($this->saldo8->Exportable) $Doc->ExportField($this->saldo8);
						if ($this->debet9->Exportable) $Doc->ExportField($this->debet9);
						if ($this->credit9->Exportable) $Doc->ExportField($this->credit9);
						if ($this->saldo9->Exportable) $Doc->ExportField($this->saldo9);
						if ($this->debet10->Exportable) $Doc->ExportField($this->debet10);
						if ($this->credit10->Exportable) $Doc->ExportField($this->credit10);
						if ($this->saldo10->Exportable) $Doc->ExportField($this->saldo10);
						if ($this->debet11->Exportable) $Doc->ExportField($this->debet11);
						if ($this->credit11->Exportable) $Doc->ExportField($this->credit11);
						if ($this->saldo11->Exportable) $Doc->ExportField($this->saldo11);
						if ($this->debet12->Exportable) $Doc->ExportField($this->debet12);
						if ($this->credit12->Exportable) $Doc->ExportField($this->credit12);
						if ($this->saldo12->Exportable) $Doc->ExportField($this->saldo12);
					} else {
						if ($this->tanggal->Exportable) $Doc->ExportField($this->tanggal);
						if ($this->periode->Exportable) $Doc->ExportField($this->periode);
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->nomor->Exportable) $Doc->ExportField($this->nomor);
						if ($this->transaksi->Exportable) $Doc->ExportField($this->transaksi);
						if ($this->referensi->Exportable) $Doc->ExportField($this->referensi);
						if ($this->group->Exportable) $Doc->ExportField($this->group);
						if ($this->rekening->Exportable) $Doc->ExportField($this->rekening);
						if ($this->tipe->Exportable) $Doc->ExportField($this->tipe);
						if ($this->posisi->Exportable) $Doc->ExportField($this->posisi);
						if ($this->laporan->Exportable) $Doc->ExportField($this->laporan);
						if ($this->keterangan->Exportable) $Doc->ExportField($this->keterangan);
						if ($this->debet1->Exportable) $Doc->ExportField($this->debet1);
						if ($this->credit1->Exportable) $Doc->ExportField($this->credit1);
						if ($this->saldo1->Exportable) $Doc->ExportField($this->saldo1);
						if ($this->debet2->Exportable) $Doc->ExportField($this->debet2);
						if ($this->credit2->Exportable) $Doc->ExportField($this->credit2);
						if ($this->saldo2->Exportable) $Doc->ExportField($this->saldo2);
						if ($this->debet3->Exportable) $Doc->ExportField($this->debet3);
						if ($this->credit3->Exportable) $Doc->ExportField($this->credit3);
						if ($this->saldo3->Exportable) $Doc->ExportField($this->saldo3);
						if ($this->debet4->Exportable) $Doc->ExportField($this->debet4);
						if ($this->credit4->Exportable) $Doc->ExportField($this->credit4);
						if ($this->saldo4->Exportable) $Doc->ExportField($this->saldo4);
						if ($this->debet5->Exportable) $Doc->ExportField($this->debet5);
						if ($this->credit5->Exportable) $Doc->ExportField($this->credit5);
						if ($this->saldo5->Exportable) $Doc->ExportField($this->saldo5);
						if ($this->debet6->Exportable) $Doc->ExportField($this->debet6);
						if ($this->credit6->Exportable) $Doc->ExportField($this->credit6);
						if ($this->saldo6->Exportable) $Doc->ExportField($this->saldo6);
						if ($this->debet7->Exportable) $Doc->ExportField($this->debet7);
						if ($this->credit7->Exportable) $Doc->ExportField($this->credit7);
						if ($this->saldo7->Exportable) $Doc->ExportField($this->saldo7);
						if ($this->debet8->Exportable) $Doc->ExportField($this->debet8);
						if ($this->credit8->Exportable) $Doc->ExportField($this->credit8);
						if ($this->saldo8->Exportable) $Doc->ExportField($this->saldo8);
						if ($this->debet9->Exportable) $Doc->ExportField($this->debet9);
						if ($this->credit9->Exportable) $Doc->ExportField($this->credit9);
						if ($this->saldo9->Exportable) $Doc->ExportField($this->saldo9);
						if ($this->debet10->Exportable) $Doc->ExportField($this->debet10);
						if ($this->credit10->Exportable) $Doc->ExportField($this->credit10);
						if ($this->saldo10->Exportable) $Doc->ExportField($this->saldo10);
						if ($this->debet11->Exportable) $Doc->ExportField($this->debet11);
						if ($this->credit11->Exportable) $Doc->ExportField($this->credit11);
						if ($this->saldo11->Exportable) $Doc->ExportField($this->saldo11);
						if ($this->debet12->Exportable) $Doc->ExportField($this->debet12);
						if ($this->credit12->Exportable) $Doc->ExportField($this->credit12);
						if ($this->saldo12->Exportable) $Doc->ExportField($this->saldo12);
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
