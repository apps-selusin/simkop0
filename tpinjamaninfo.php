<?php

// Global variable for table object
$tpinjaman = NULL;

//
// Table class for tpinjaman
//
class ctpinjaman extends cTable {
	var $tanggal;
	var $periode;
	var $id;
	var $transaksi;
	var $referensi;
	var $anggota;
	var $namaanggota;
	var $alamat;
	var $pekerjaan;
	var $telepon;
	var $hp;
	var $fax;
	var $_email;
	var $website;
	var $jenisanggota;
	var $model;
	var $jenispinjaman;
	var $jenisbunga;
	var $angsuran;
	var $masaangsuran;
	var $jatuhtempo;
	var $dispensasidenda;
	var $agunan;
	var $dataagunan1;
	var $dataagunan2;
	var $dataagunan3;
	var $dataagunan4;
	var $dataagunan5;
	var $saldobekusimpanan;
	var $saldobekuminimal;
	var $plafond;
	var $bunga;
	var $bungapersen;
	var $administrasi;
	var $administrasipersen;
	var $asuransi;
	var $notaris;
	var $biayamaterai;
	var $potongansaldobeku;
	var $angsuranpokok;
	var $angsuranpokokauto;
	var $angsuranbunga;
	var $angsuranbungaauto;
	var $denda;
	var $dendapersen;
	var $totalangsuran;
	var $totalangsuranauto;
	var $totalterima;
	var $totalterimaauto;
	var $terbilang;
	var $petugas;
	var $pembayaran;
	var $bank;
	var $atasnama;
	var $tipe;
	var $kantor;
	var $keterangan;
	var $active;
	var $ip;
	var $status;
	var $user;
	var $created;
	var $modified;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'tpinjaman';
		$this->TableName = 'tpinjaman';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`tpinjaman`";
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
		$this->tanggal = new cField('tpinjaman', 'tpinjaman', 'x_tanggal', 'tanggal', '`tanggal`', ew_CastDateFieldForLike('`tanggal`', 0, "DB"), 135, 0, FALSE, '`tanggal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tanggal->Sortable = TRUE; // Allow sort
		$this->tanggal->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['tanggal'] = &$this->tanggal;

		// periode
		$this->periode = new cField('tpinjaman', 'tpinjaman', 'x_periode', 'periode', '`periode`', '`periode`', 200, -1, FALSE, '`periode`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->periode->Sortable = TRUE; // Allow sort
		$this->fields['periode'] = &$this->periode;

		// id
		$this->id = new cField('tpinjaman', 'tpinjaman', 'x_id', 'id', '`id`', '`id`', 200, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->id->Sortable = TRUE; // Allow sort
		$this->fields['id'] = &$this->id;

		// transaksi
		$this->transaksi = new cField('tpinjaman', 'tpinjaman', 'x_transaksi', 'transaksi', '`transaksi`', '`transaksi`', 200, -1, FALSE, '`transaksi`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->transaksi->Sortable = TRUE; // Allow sort
		$this->fields['transaksi'] = &$this->transaksi;

		// referensi
		$this->referensi = new cField('tpinjaman', 'tpinjaman', 'x_referensi', 'referensi', '`referensi`', '`referensi`', 200, -1, FALSE, '`referensi`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->referensi->Sortable = TRUE; // Allow sort
		$this->fields['referensi'] = &$this->referensi;

		// anggota
		$this->anggota = new cField('tpinjaman', 'tpinjaman', 'x_anggota', 'anggota', '`anggota`', '`anggota`', 200, -1, FALSE, '`anggota`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->anggota->Sortable = TRUE; // Allow sort
		$this->fields['anggota'] = &$this->anggota;

		// namaanggota
		$this->namaanggota = new cField('tpinjaman', 'tpinjaman', 'x_namaanggota', 'namaanggota', '`namaanggota`', '`namaanggota`', 200, -1, FALSE, '`namaanggota`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->namaanggota->Sortable = TRUE; // Allow sort
		$this->fields['namaanggota'] = &$this->namaanggota;

		// alamat
		$this->alamat = new cField('tpinjaman', 'tpinjaman', 'x_alamat', 'alamat', '`alamat`', '`alamat`', 200, -1, FALSE, '`alamat`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->alamat->Sortable = TRUE; // Allow sort
		$this->fields['alamat'] = &$this->alamat;

		// pekerjaan
		$this->pekerjaan = new cField('tpinjaman', 'tpinjaman', 'x_pekerjaan', 'pekerjaan', '`pekerjaan`', '`pekerjaan`', 200, -1, FALSE, '`pekerjaan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pekerjaan->Sortable = TRUE; // Allow sort
		$this->fields['pekerjaan'] = &$this->pekerjaan;

		// telepon
		$this->telepon = new cField('tpinjaman', 'tpinjaman', 'x_telepon', 'telepon', '`telepon`', '`telepon`', 200, -1, FALSE, '`telepon`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->telepon->Sortable = TRUE; // Allow sort
		$this->fields['telepon'] = &$this->telepon;

		// hp
		$this->hp = new cField('tpinjaman', 'tpinjaman', 'x_hp', 'hp', '`hp`', '`hp`', 200, -1, FALSE, '`hp`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->hp->Sortable = TRUE; // Allow sort
		$this->fields['hp'] = &$this->hp;

		// fax
		$this->fax = new cField('tpinjaman', 'tpinjaman', 'x_fax', 'fax', '`fax`', '`fax`', 200, -1, FALSE, '`fax`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fax->Sortable = TRUE; // Allow sort
		$this->fields['fax'] = &$this->fax;

		// email
		$this->_email = new cField('tpinjaman', 'tpinjaman', 'x__email', 'email', '`email`', '`email`', 200, -1, FALSE, '`email`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->_email->Sortable = TRUE; // Allow sort
		$this->fields['email'] = &$this->_email;

		// website
		$this->website = new cField('tpinjaman', 'tpinjaman', 'x_website', 'website', '`website`', '`website`', 200, -1, FALSE, '`website`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->website->Sortable = TRUE; // Allow sort
		$this->fields['website'] = &$this->website;

		// jenisanggota
		$this->jenisanggota = new cField('tpinjaman', 'tpinjaman', 'x_jenisanggota', 'jenisanggota', '`jenisanggota`', '`jenisanggota`', 200, -1, FALSE, '`jenisanggota`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jenisanggota->Sortable = TRUE; // Allow sort
		$this->fields['jenisanggota'] = &$this->jenisanggota;

		// model
		$this->model = new cField('tpinjaman', 'tpinjaman', 'x_model', 'model', '`model`', '`model`', 200, -1, FALSE, '`model`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->model->Sortable = TRUE; // Allow sort
		$this->fields['model'] = &$this->model;

		// jenispinjaman
		$this->jenispinjaman = new cField('tpinjaman', 'tpinjaman', 'x_jenispinjaman', 'jenispinjaman', '`jenispinjaman`', '`jenispinjaman`', 200, -1, FALSE, '`jenispinjaman`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jenispinjaman->Sortable = TRUE; // Allow sort
		$this->fields['jenispinjaman'] = &$this->jenispinjaman;

		// jenisbunga
		$this->jenisbunga = new cField('tpinjaman', 'tpinjaman', 'x_jenisbunga', 'jenisbunga', '`jenisbunga`', '`jenisbunga`', 200, -1, FALSE, '`jenisbunga`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jenisbunga->Sortable = TRUE; // Allow sort
		$this->fields['jenisbunga'] = &$this->jenisbunga;

		// angsuran
		$this->angsuran = new cField('tpinjaman', 'tpinjaman', 'x_angsuran', 'angsuran', '`angsuran`', '`angsuran`', 20, -1, FALSE, '`angsuran`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->angsuran->Sortable = TRUE; // Allow sort
		$this->angsuran->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['angsuran'] = &$this->angsuran;

		// masaangsuran
		$this->masaangsuran = new cField('tpinjaman', 'tpinjaman', 'x_masaangsuran', 'masaangsuran', '`masaangsuran`', '`masaangsuran`', 200, -1, FALSE, '`masaangsuran`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->masaangsuran->Sortable = TRUE; // Allow sort
		$this->fields['masaangsuran'] = &$this->masaangsuran;

		// jatuhtempo
		$this->jatuhtempo = new cField('tpinjaman', 'tpinjaman', 'x_jatuhtempo', 'jatuhtempo', '`jatuhtempo`', ew_CastDateFieldForLike('`jatuhtempo`', 0, "DB"), 135, 0, FALSE, '`jatuhtempo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jatuhtempo->Sortable = TRUE; // Allow sort
		$this->jatuhtempo->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['jatuhtempo'] = &$this->jatuhtempo;

		// dispensasidenda
		$this->dispensasidenda = new cField('tpinjaman', 'tpinjaman', 'x_dispensasidenda', 'dispensasidenda', '`dispensasidenda`', '`dispensasidenda`', 20, -1, FALSE, '`dispensasidenda`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->dispensasidenda->Sortable = TRUE; // Allow sort
		$this->dispensasidenda->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['dispensasidenda'] = &$this->dispensasidenda;

		// agunan
		$this->agunan = new cField('tpinjaman', 'tpinjaman', 'x_agunan', 'agunan', '`agunan`', '`agunan`', 200, -1, FALSE, '`agunan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->agunan->Sortable = TRUE; // Allow sort
		$this->fields['agunan'] = &$this->agunan;

		// dataagunan1
		$this->dataagunan1 = new cField('tpinjaman', 'tpinjaman', 'x_dataagunan1', 'dataagunan1', '`dataagunan1`', '`dataagunan1`', 200, -1, FALSE, '`dataagunan1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->dataagunan1->Sortable = TRUE; // Allow sort
		$this->fields['dataagunan1'] = &$this->dataagunan1;

		// dataagunan2
		$this->dataagunan2 = new cField('tpinjaman', 'tpinjaman', 'x_dataagunan2', 'dataagunan2', '`dataagunan2`', '`dataagunan2`', 200, -1, FALSE, '`dataagunan2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->dataagunan2->Sortable = TRUE; // Allow sort
		$this->fields['dataagunan2'] = &$this->dataagunan2;

		// dataagunan3
		$this->dataagunan3 = new cField('tpinjaman', 'tpinjaman', 'x_dataagunan3', 'dataagunan3', '`dataagunan3`', '`dataagunan3`', 200, -1, FALSE, '`dataagunan3`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->dataagunan3->Sortable = TRUE; // Allow sort
		$this->fields['dataagunan3'] = &$this->dataagunan3;

		// dataagunan4
		$this->dataagunan4 = new cField('tpinjaman', 'tpinjaman', 'x_dataagunan4', 'dataagunan4', '`dataagunan4`', '`dataagunan4`', 200, -1, FALSE, '`dataagunan4`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->dataagunan4->Sortable = TRUE; // Allow sort
		$this->fields['dataagunan4'] = &$this->dataagunan4;

		// dataagunan5
		$this->dataagunan5 = new cField('tpinjaman', 'tpinjaman', 'x_dataagunan5', 'dataagunan5', '`dataagunan5`', '`dataagunan5`', 200, -1, FALSE, '`dataagunan5`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->dataagunan5->Sortable = TRUE; // Allow sort
		$this->fields['dataagunan5'] = &$this->dataagunan5;

		// saldobekusimpanan
		$this->saldobekusimpanan = new cField('tpinjaman', 'tpinjaman', 'x_saldobekusimpanan', 'saldobekusimpanan', '`saldobekusimpanan`', '`saldobekusimpanan`', 5, -1, FALSE, '`saldobekusimpanan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->saldobekusimpanan->Sortable = TRUE; // Allow sort
		$this->saldobekusimpanan->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['saldobekusimpanan'] = &$this->saldobekusimpanan;

		// saldobekuminimal
		$this->saldobekuminimal = new cField('tpinjaman', 'tpinjaman', 'x_saldobekuminimal', 'saldobekuminimal', '`saldobekuminimal`', '`saldobekuminimal`', 5, -1, FALSE, '`saldobekuminimal`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->saldobekuminimal->Sortable = TRUE; // Allow sort
		$this->saldobekuminimal->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['saldobekuminimal'] = &$this->saldobekuminimal;

		// plafond
		$this->plafond = new cField('tpinjaman', 'tpinjaman', 'x_plafond', 'plafond', '`plafond`', '`plafond`', 5, -1, FALSE, '`plafond`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->plafond->Sortable = TRUE; // Allow sort
		$this->plafond->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['plafond'] = &$this->plafond;

		// bunga
		$this->bunga = new cField('tpinjaman', 'tpinjaman', 'x_bunga', 'bunga', '`bunga`', '`bunga`', 5, -1, FALSE, '`bunga`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->bunga->Sortable = TRUE; // Allow sort
		$this->bunga->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['bunga'] = &$this->bunga;

		// bungapersen
		$this->bungapersen = new cField('tpinjaman', 'tpinjaman', 'x_bungapersen', 'bungapersen', '`bungapersen`', '`bungapersen`', 5, -1, FALSE, '`bungapersen`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->bungapersen->Sortable = TRUE; // Allow sort
		$this->bungapersen->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['bungapersen'] = &$this->bungapersen;

		// administrasi
		$this->administrasi = new cField('tpinjaman', 'tpinjaman', 'x_administrasi', 'administrasi', '`administrasi`', '`administrasi`', 5, -1, FALSE, '`administrasi`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->administrasi->Sortable = TRUE; // Allow sort
		$this->administrasi->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['administrasi'] = &$this->administrasi;

		// administrasipersen
		$this->administrasipersen = new cField('tpinjaman', 'tpinjaman', 'x_administrasipersen', 'administrasipersen', '`administrasipersen`', '`administrasipersen`', 5, -1, FALSE, '`administrasipersen`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->administrasipersen->Sortable = TRUE; // Allow sort
		$this->administrasipersen->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['administrasipersen'] = &$this->administrasipersen;

		// asuransi
		$this->asuransi = new cField('tpinjaman', 'tpinjaman', 'x_asuransi', 'asuransi', '`asuransi`', '`asuransi`', 5, -1, FALSE, '`asuransi`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->asuransi->Sortable = TRUE; // Allow sort
		$this->asuransi->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['asuransi'] = &$this->asuransi;

		// notaris
		$this->notaris = new cField('tpinjaman', 'tpinjaman', 'x_notaris', 'notaris', '`notaris`', '`notaris`', 5, -1, FALSE, '`notaris`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->notaris->Sortable = TRUE; // Allow sort
		$this->notaris->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['notaris'] = &$this->notaris;

		// biayamaterai
		$this->biayamaterai = new cField('tpinjaman', 'tpinjaman', 'x_biayamaterai', 'biayamaterai', '`biayamaterai`', '`biayamaterai`', 5, -1, FALSE, '`biayamaterai`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->biayamaterai->Sortable = TRUE; // Allow sort
		$this->biayamaterai->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['biayamaterai'] = &$this->biayamaterai;

		// potongansaldobeku
		$this->potongansaldobeku = new cField('tpinjaman', 'tpinjaman', 'x_potongansaldobeku', 'potongansaldobeku', '`potongansaldobeku`', '`potongansaldobeku`', 5, -1, FALSE, '`potongansaldobeku`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->potongansaldobeku->Sortable = TRUE; // Allow sort
		$this->potongansaldobeku->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['potongansaldobeku'] = &$this->potongansaldobeku;

		// angsuranpokok
		$this->angsuranpokok = new cField('tpinjaman', 'tpinjaman', 'x_angsuranpokok', 'angsuranpokok', '`angsuranpokok`', '`angsuranpokok`', 5, -1, FALSE, '`angsuranpokok`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->angsuranpokok->Sortable = TRUE; // Allow sort
		$this->angsuranpokok->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['angsuranpokok'] = &$this->angsuranpokok;

		// angsuranpokokauto
		$this->angsuranpokokauto = new cField('tpinjaman', 'tpinjaman', 'x_angsuranpokokauto', 'angsuranpokokauto', '`angsuranpokokauto`', '`angsuranpokokauto`', 5, -1, FALSE, '`angsuranpokokauto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->angsuranpokokauto->Sortable = TRUE; // Allow sort
		$this->angsuranpokokauto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['angsuranpokokauto'] = &$this->angsuranpokokauto;

		// angsuranbunga
		$this->angsuranbunga = new cField('tpinjaman', 'tpinjaman', 'x_angsuranbunga', 'angsuranbunga', '`angsuranbunga`', '`angsuranbunga`', 5, -1, FALSE, '`angsuranbunga`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->angsuranbunga->Sortable = TRUE; // Allow sort
		$this->angsuranbunga->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['angsuranbunga'] = &$this->angsuranbunga;

		// angsuranbungaauto
		$this->angsuranbungaauto = new cField('tpinjaman', 'tpinjaman', 'x_angsuranbungaauto', 'angsuranbungaauto', '`angsuranbungaauto`', '`angsuranbungaauto`', 5, -1, FALSE, '`angsuranbungaauto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->angsuranbungaauto->Sortable = TRUE; // Allow sort
		$this->angsuranbungaauto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['angsuranbungaauto'] = &$this->angsuranbungaauto;

		// denda
		$this->denda = new cField('tpinjaman', 'tpinjaman', 'x_denda', 'denda', '`denda`', '`denda`', 5, -1, FALSE, '`denda`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->denda->Sortable = TRUE; // Allow sort
		$this->denda->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['denda'] = &$this->denda;

		// dendapersen
		$this->dendapersen = new cField('tpinjaman', 'tpinjaman', 'x_dendapersen', 'dendapersen', '`dendapersen`', '`dendapersen`', 5, -1, FALSE, '`dendapersen`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->dendapersen->Sortable = TRUE; // Allow sort
		$this->dendapersen->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['dendapersen'] = &$this->dendapersen;

		// totalangsuran
		$this->totalangsuran = new cField('tpinjaman', 'tpinjaman', 'x_totalangsuran', 'totalangsuran', '`totalangsuran`', '`totalangsuran`', 5, -1, FALSE, '`totalangsuran`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->totalangsuran->Sortable = TRUE; // Allow sort
		$this->totalangsuran->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['totalangsuran'] = &$this->totalangsuran;

		// totalangsuranauto
		$this->totalangsuranauto = new cField('tpinjaman', 'tpinjaman', 'x_totalangsuranauto', 'totalangsuranauto', '`totalangsuranauto`', '`totalangsuranauto`', 5, -1, FALSE, '`totalangsuranauto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->totalangsuranauto->Sortable = TRUE; // Allow sort
		$this->totalangsuranauto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['totalangsuranauto'] = &$this->totalangsuranauto;

		// totalterima
		$this->totalterima = new cField('tpinjaman', 'tpinjaman', 'x_totalterima', 'totalterima', '`totalterima`', '`totalterima`', 5, -1, FALSE, '`totalterima`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->totalterima->Sortable = TRUE; // Allow sort
		$this->totalterima->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['totalterima'] = &$this->totalterima;

		// totalterimaauto
		$this->totalterimaauto = new cField('tpinjaman', 'tpinjaman', 'x_totalterimaauto', 'totalterimaauto', '`totalterimaauto`', '`totalterimaauto`', 5, -1, FALSE, '`totalterimaauto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->totalterimaauto->Sortable = TRUE; // Allow sort
		$this->totalterimaauto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['totalterimaauto'] = &$this->totalterimaauto;

		// terbilang
		$this->terbilang = new cField('tpinjaman', 'tpinjaman', 'x_terbilang', 'terbilang', '`terbilang`', '`terbilang`', 200, -1, FALSE, '`terbilang`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->terbilang->Sortable = TRUE; // Allow sort
		$this->fields['terbilang'] = &$this->terbilang;

		// petugas
		$this->petugas = new cField('tpinjaman', 'tpinjaman', 'x_petugas', 'petugas', '`petugas`', '`petugas`', 200, -1, FALSE, '`petugas`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->petugas->Sortable = TRUE; // Allow sort
		$this->fields['petugas'] = &$this->petugas;

		// pembayaran
		$this->pembayaran = new cField('tpinjaman', 'tpinjaman', 'x_pembayaran', 'pembayaran', '`pembayaran`', '`pembayaran`', 200, -1, FALSE, '`pembayaran`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pembayaran->Sortable = TRUE; // Allow sort
		$this->fields['pembayaran'] = &$this->pembayaran;

		// bank
		$this->bank = new cField('tpinjaman', 'tpinjaman', 'x_bank', 'bank', '`bank`', '`bank`', 200, -1, FALSE, '`bank`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->bank->Sortable = TRUE; // Allow sort
		$this->fields['bank'] = &$this->bank;

		// atasnama
		$this->atasnama = new cField('tpinjaman', 'tpinjaman', 'x_atasnama', 'atasnama', '`atasnama`', '`atasnama`', 200, -1, FALSE, '`atasnama`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->atasnama->Sortable = TRUE; // Allow sort
		$this->fields['atasnama'] = &$this->atasnama;

		// tipe
		$this->tipe = new cField('tpinjaman', 'tpinjaman', 'x_tipe', 'tipe', '`tipe`', '`tipe`', 200, -1, FALSE, '`tipe`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tipe->Sortable = TRUE; // Allow sort
		$this->fields['tipe'] = &$this->tipe;

		// kantor
		$this->kantor = new cField('tpinjaman', 'tpinjaman', 'x_kantor', 'kantor', '`kantor`', '`kantor`', 200, -1, FALSE, '`kantor`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->kantor->Sortable = TRUE; // Allow sort
		$this->fields['kantor'] = &$this->kantor;

		// keterangan
		$this->keterangan = new cField('tpinjaman', 'tpinjaman', 'x_keterangan', 'keterangan', '`keterangan`', '`keterangan`', 200, -1, FALSE, '`keterangan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->keterangan->Sortable = TRUE; // Allow sort
		$this->fields['keterangan'] = &$this->keterangan;

		// active
		$this->active = new cField('tpinjaman', 'tpinjaman', 'x_active', 'active', '`active`', '`active`', 202, -1, FALSE, '`active`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->active->Sortable = TRUE; // Allow sort
		$this->active->OptionCount = 2;
		$this->fields['active'] = &$this->active;

		// ip
		$this->ip = new cField('tpinjaman', 'tpinjaman', 'x_ip', 'ip', '`ip`', '`ip`', 200, -1, FALSE, '`ip`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->ip->Sortable = TRUE; // Allow sort
		$this->fields['ip'] = &$this->ip;

		// status
		$this->status = new cField('tpinjaman', 'tpinjaman', 'x_status', 'status', '`status`', '`status`', 200, -1, FALSE, '`status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->status->Sortable = TRUE; // Allow sort
		$this->fields['status'] = &$this->status;

		// user
		$this->user = new cField('tpinjaman', 'tpinjaman', 'x_user', 'user', '`user`', '`user`', 200, -1, FALSE, '`user`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->user->Sortable = TRUE; // Allow sort
		$this->fields['user'] = &$this->user;

		// created
		$this->created = new cField('tpinjaman', 'tpinjaman', 'x_created', 'created', '`created`', ew_CastDateFieldForLike('`created`', 0, "DB"), 135, 0, FALSE, '`created`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->created->Sortable = TRUE; // Allow sort
		$this->created->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['created'] = &$this->created;

		// modified
		$this->modified = new cField('tpinjaman', 'tpinjaman', 'x_modified', 'modified', '`modified`', ew_CastDateFieldForLike('`modified`', 0, "DB"), 135, 0, FALSE, '`modified`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->modified->Sortable = TRUE; // Allow sort
		$this->modified->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['modified'] = &$this->modified;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`tpinjaman`";
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
		return "`id` = '@id@'";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		$sKeyFilter = str_replace("@id@", ew_AdjustSql($this->id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "tpinjamanlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "tpinjamanlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("tpinjamanview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("tpinjamanview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "tpinjamanadd.php?" . $this->UrlParm($parm);
		else
			$url = "tpinjamanadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("tpinjamanedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("tpinjamanadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("tpinjamandelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "id:" . ew_VarToJson($this->id->CurrentValue, "string", "'");
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
			if ($isPost && isset($_POST["id"]))
				$arKeys[] = ew_StripSlashes($_POST["id"]);
			elseif (isset($_GET["id"]))
				$arKeys[] = ew_StripSlashes($_GET["id"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
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
			$this->id->CurrentValue = $key;
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

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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

		// anggota
		$this->anggota->EditAttrs["class"] = "form-control";
		$this->anggota->EditCustomAttributes = "";
		$this->anggota->EditValue = $this->anggota->CurrentValue;
		$this->anggota->PlaceHolder = ew_RemoveHtml($this->anggota->FldCaption());

		// namaanggota
		$this->namaanggota->EditAttrs["class"] = "form-control";
		$this->namaanggota->EditCustomAttributes = "";
		$this->namaanggota->EditValue = $this->namaanggota->CurrentValue;
		$this->namaanggota->PlaceHolder = ew_RemoveHtml($this->namaanggota->FldCaption());

		// alamat
		$this->alamat->EditAttrs["class"] = "form-control";
		$this->alamat->EditCustomAttributes = "";
		$this->alamat->EditValue = $this->alamat->CurrentValue;
		$this->alamat->PlaceHolder = ew_RemoveHtml($this->alamat->FldCaption());

		// pekerjaan
		$this->pekerjaan->EditAttrs["class"] = "form-control";
		$this->pekerjaan->EditCustomAttributes = "";
		$this->pekerjaan->EditValue = $this->pekerjaan->CurrentValue;
		$this->pekerjaan->PlaceHolder = ew_RemoveHtml($this->pekerjaan->FldCaption());

		// telepon
		$this->telepon->EditAttrs["class"] = "form-control";
		$this->telepon->EditCustomAttributes = "";
		$this->telepon->EditValue = $this->telepon->CurrentValue;
		$this->telepon->PlaceHolder = ew_RemoveHtml($this->telepon->FldCaption());

		// hp
		$this->hp->EditAttrs["class"] = "form-control";
		$this->hp->EditCustomAttributes = "";
		$this->hp->EditValue = $this->hp->CurrentValue;
		$this->hp->PlaceHolder = ew_RemoveHtml($this->hp->FldCaption());

		// fax
		$this->fax->EditAttrs["class"] = "form-control";
		$this->fax->EditCustomAttributes = "";
		$this->fax->EditValue = $this->fax->CurrentValue;
		$this->fax->PlaceHolder = ew_RemoveHtml($this->fax->FldCaption());

		// email
		$this->_email->EditAttrs["class"] = "form-control";
		$this->_email->EditCustomAttributes = "";
		$this->_email->EditValue = $this->_email->CurrentValue;
		$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

		// website
		$this->website->EditAttrs["class"] = "form-control";
		$this->website->EditCustomAttributes = "";
		$this->website->EditValue = $this->website->CurrentValue;
		$this->website->PlaceHolder = ew_RemoveHtml($this->website->FldCaption());

		// jenisanggota
		$this->jenisanggota->EditAttrs["class"] = "form-control";
		$this->jenisanggota->EditCustomAttributes = "";
		$this->jenisanggota->EditValue = $this->jenisanggota->CurrentValue;
		$this->jenisanggota->PlaceHolder = ew_RemoveHtml($this->jenisanggota->FldCaption());

		// model
		$this->model->EditAttrs["class"] = "form-control";
		$this->model->EditCustomAttributes = "";
		$this->model->EditValue = $this->model->CurrentValue;
		$this->model->PlaceHolder = ew_RemoveHtml($this->model->FldCaption());

		// jenispinjaman
		$this->jenispinjaman->EditAttrs["class"] = "form-control";
		$this->jenispinjaman->EditCustomAttributes = "";
		$this->jenispinjaman->EditValue = $this->jenispinjaman->CurrentValue;
		$this->jenispinjaman->PlaceHolder = ew_RemoveHtml($this->jenispinjaman->FldCaption());

		// jenisbunga
		$this->jenisbunga->EditAttrs["class"] = "form-control";
		$this->jenisbunga->EditCustomAttributes = "";
		$this->jenisbunga->EditValue = $this->jenisbunga->CurrentValue;
		$this->jenisbunga->PlaceHolder = ew_RemoveHtml($this->jenisbunga->FldCaption());

		// angsuran
		$this->angsuran->EditAttrs["class"] = "form-control";
		$this->angsuran->EditCustomAttributes = "";
		$this->angsuran->EditValue = $this->angsuran->CurrentValue;
		$this->angsuran->PlaceHolder = ew_RemoveHtml($this->angsuran->FldCaption());

		// masaangsuran
		$this->masaangsuran->EditAttrs["class"] = "form-control";
		$this->masaangsuran->EditCustomAttributes = "";
		$this->masaangsuran->EditValue = $this->masaangsuran->CurrentValue;
		$this->masaangsuran->PlaceHolder = ew_RemoveHtml($this->masaangsuran->FldCaption());

		// jatuhtempo
		$this->jatuhtempo->EditAttrs["class"] = "form-control";
		$this->jatuhtempo->EditCustomAttributes = "";
		$this->jatuhtempo->EditValue = ew_FormatDateTime($this->jatuhtempo->CurrentValue, 8);
		$this->jatuhtempo->PlaceHolder = ew_RemoveHtml($this->jatuhtempo->FldCaption());

		// dispensasidenda
		$this->dispensasidenda->EditAttrs["class"] = "form-control";
		$this->dispensasidenda->EditCustomAttributes = "";
		$this->dispensasidenda->EditValue = $this->dispensasidenda->CurrentValue;
		$this->dispensasidenda->PlaceHolder = ew_RemoveHtml($this->dispensasidenda->FldCaption());

		// agunan
		$this->agunan->EditAttrs["class"] = "form-control";
		$this->agunan->EditCustomAttributes = "";
		$this->agunan->EditValue = $this->agunan->CurrentValue;
		$this->agunan->PlaceHolder = ew_RemoveHtml($this->agunan->FldCaption());

		// dataagunan1
		$this->dataagunan1->EditAttrs["class"] = "form-control";
		$this->dataagunan1->EditCustomAttributes = "";
		$this->dataagunan1->EditValue = $this->dataagunan1->CurrentValue;
		$this->dataagunan1->PlaceHolder = ew_RemoveHtml($this->dataagunan1->FldCaption());

		// dataagunan2
		$this->dataagunan2->EditAttrs["class"] = "form-control";
		$this->dataagunan2->EditCustomAttributes = "";
		$this->dataagunan2->EditValue = $this->dataagunan2->CurrentValue;
		$this->dataagunan2->PlaceHolder = ew_RemoveHtml($this->dataagunan2->FldCaption());

		// dataagunan3
		$this->dataagunan3->EditAttrs["class"] = "form-control";
		$this->dataagunan3->EditCustomAttributes = "";
		$this->dataagunan3->EditValue = $this->dataagunan3->CurrentValue;
		$this->dataagunan3->PlaceHolder = ew_RemoveHtml($this->dataagunan3->FldCaption());

		// dataagunan4
		$this->dataagunan4->EditAttrs["class"] = "form-control";
		$this->dataagunan4->EditCustomAttributes = "";
		$this->dataagunan4->EditValue = $this->dataagunan4->CurrentValue;
		$this->dataagunan4->PlaceHolder = ew_RemoveHtml($this->dataagunan4->FldCaption());

		// dataagunan5
		$this->dataagunan5->EditAttrs["class"] = "form-control";
		$this->dataagunan5->EditCustomAttributes = "";
		$this->dataagunan5->EditValue = $this->dataagunan5->CurrentValue;
		$this->dataagunan5->PlaceHolder = ew_RemoveHtml($this->dataagunan5->FldCaption());

		// saldobekusimpanan
		$this->saldobekusimpanan->EditAttrs["class"] = "form-control";
		$this->saldobekusimpanan->EditCustomAttributes = "";
		$this->saldobekusimpanan->EditValue = $this->saldobekusimpanan->CurrentValue;
		$this->saldobekusimpanan->PlaceHolder = ew_RemoveHtml($this->saldobekusimpanan->FldCaption());
		if (strval($this->saldobekusimpanan->EditValue) <> "" && is_numeric($this->saldobekusimpanan->EditValue)) $this->saldobekusimpanan->EditValue = ew_FormatNumber($this->saldobekusimpanan->EditValue, -2, -1, -2, 0);

		// saldobekuminimal
		$this->saldobekuminimal->EditAttrs["class"] = "form-control";
		$this->saldobekuminimal->EditCustomAttributes = "";
		$this->saldobekuminimal->EditValue = $this->saldobekuminimal->CurrentValue;
		$this->saldobekuminimal->PlaceHolder = ew_RemoveHtml($this->saldobekuminimal->FldCaption());
		if (strval($this->saldobekuminimal->EditValue) <> "" && is_numeric($this->saldobekuminimal->EditValue)) $this->saldobekuminimal->EditValue = ew_FormatNumber($this->saldobekuminimal->EditValue, -2, -1, -2, 0);

		// plafond
		$this->plafond->EditAttrs["class"] = "form-control";
		$this->plafond->EditCustomAttributes = "";
		$this->plafond->EditValue = $this->plafond->CurrentValue;
		$this->plafond->PlaceHolder = ew_RemoveHtml($this->plafond->FldCaption());
		if (strval($this->plafond->EditValue) <> "" && is_numeric($this->plafond->EditValue)) $this->plafond->EditValue = ew_FormatNumber($this->plafond->EditValue, -2, -1, -2, 0);

		// bunga
		$this->bunga->EditAttrs["class"] = "form-control";
		$this->bunga->EditCustomAttributes = "";
		$this->bunga->EditValue = $this->bunga->CurrentValue;
		$this->bunga->PlaceHolder = ew_RemoveHtml($this->bunga->FldCaption());
		if (strval($this->bunga->EditValue) <> "" && is_numeric($this->bunga->EditValue)) $this->bunga->EditValue = ew_FormatNumber($this->bunga->EditValue, -2, -1, -2, 0);

		// bungapersen
		$this->bungapersen->EditAttrs["class"] = "form-control";
		$this->bungapersen->EditCustomAttributes = "";
		$this->bungapersen->EditValue = $this->bungapersen->CurrentValue;
		$this->bungapersen->PlaceHolder = ew_RemoveHtml($this->bungapersen->FldCaption());
		if (strval($this->bungapersen->EditValue) <> "" && is_numeric($this->bungapersen->EditValue)) $this->bungapersen->EditValue = ew_FormatNumber($this->bungapersen->EditValue, -2, -1, -2, 0);

		// administrasi
		$this->administrasi->EditAttrs["class"] = "form-control";
		$this->administrasi->EditCustomAttributes = "";
		$this->administrasi->EditValue = $this->administrasi->CurrentValue;
		$this->administrasi->PlaceHolder = ew_RemoveHtml($this->administrasi->FldCaption());
		if (strval($this->administrasi->EditValue) <> "" && is_numeric($this->administrasi->EditValue)) $this->administrasi->EditValue = ew_FormatNumber($this->administrasi->EditValue, -2, -1, -2, 0);

		// administrasipersen
		$this->administrasipersen->EditAttrs["class"] = "form-control";
		$this->administrasipersen->EditCustomAttributes = "";
		$this->administrasipersen->EditValue = $this->administrasipersen->CurrentValue;
		$this->administrasipersen->PlaceHolder = ew_RemoveHtml($this->administrasipersen->FldCaption());
		if (strval($this->administrasipersen->EditValue) <> "" && is_numeric($this->administrasipersen->EditValue)) $this->administrasipersen->EditValue = ew_FormatNumber($this->administrasipersen->EditValue, -2, -1, -2, 0);

		// asuransi
		$this->asuransi->EditAttrs["class"] = "form-control";
		$this->asuransi->EditCustomAttributes = "";
		$this->asuransi->EditValue = $this->asuransi->CurrentValue;
		$this->asuransi->PlaceHolder = ew_RemoveHtml($this->asuransi->FldCaption());
		if (strval($this->asuransi->EditValue) <> "" && is_numeric($this->asuransi->EditValue)) $this->asuransi->EditValue = ew_FormatNumber($this->asuransi->EditValue, -2, -1, -2, 0);

		// notaris
		$this->notaris->EditAttrs["class"] = "form-control";
		$this->notaris->EditCustomAttributes = "";
		$this->notaris->EditValue = $this->notaris->CurrentValue;
		$this->notaris->PlaceHolder = ew_RemoveHtml($this->notaris->FldCaption());
		if (strval($this->notaris->EditValue) <> "" && is_numeric($this->notaris->EditValue)) $this->notaris->EditValue = ew_FormatNumber($this->notaris->EditValue, -2, -1, -2, 0);

		// biayamaterai
		$this->biayamaterai->EditAttrs["class"] = "form-control";
		$this->biayamaterai->EditCustomAttributes = "";
		$this->biayamaterai->EditValue = $this->biayamaterai->CurrentValue;
		$this->biayamaterai->PlaceHolder = ew_RemoveHtml($this->biayamaterai->FldCaption());
		if (strval($this->biayamaterai->EditValue) <> "" && is_numeric($this->biayamaterai->EditValue)) $this->biayamaterai->EditValue = ew_FormatNumber($this->biayamaterai->EditValue, -2, -1, -2, 0);

		// potongansaldobeku
		$this->potongansaldobeku->EditAttrs["class"] = "form-control";
		$this->potongansaldobeku->EditCustomAttributes = "";
		$this->potongansaldobeku->EditValue = $this->potongansaldobeku->CurrentValue;
		$this->potongansaldobeku->PlaceHolder = ew_RemoveHtml($this->potongansaldobeku->FldCaption());
		if (strval($this->potongansaldobeku->EditValue) <> "" && is_numeric($this->potongansaldobeku->EditValue)) $this->potongansaldobeku->EditValue = ew_FormatNumber($this->potongansaldobeku->EditValue, -2, -1, -2, 0);

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

		// totalterima
		$this->totalterima->EditAttrs["class"] = "form-control";
		$this->totalterima->EditCustomAttributes = "";
		$this->totalterima->EditValue = $this->totalterima->CurrentValue;
		$this->totalterima->PlaceHolder = ew_RemoveHtml($this->totalterima->FldCaption());
		if (strval($this->totalterima->EditValue) <> "" && is_numeric($this->totalterima->EditValue)) $this->totalterima->EditValue = ew_FormatNumber($this->totalterima->EditValue, -2, -1, -2, 0);

		// totalterimaauto
		$this->totalterimaauto->EditAttrs["class"] = "form-control";
		$this->totalterimaauto->EditCustomAttributes = "";
		$this->totalterimaauto->EditValue = $this->totalterimaauto->CurrentValue;
		$this->totalterimaauto->PlaceHolder = ew_RemoveHtml($this->totalterimaauto->FldCaption());
		if (strval($this->totalterimaauto->EditValue) <> "" && is_numeric($this->totalterimaauto->EditValue)) $this->totalterimaauto->EditValue = ew_FormatNumber($this->totalterimaauto->EditValue, -2, -1, -2, 0);

		// terbilang
		$this->terbilang->EditAttrs["class"] = "form-control";
		$this->terbilang->EditCustomAttributes = "";
		$this->terbilang->EditValue = $this->terbilang->CurrentValue;
		$this->terbilang->PlaceHolder = ew_RemoveHtml($this->terbilang->FldCaption());

		// petugas
		$this->petugas->EditAttrs["class"] = "form-control";
		$this->petugas->EditCustomAttributes = "";
		$this->petugas->EditValue = $this->petugas->CurrentValue;
		$this->petugas->PlaceHolder = ew_RemoveHtml($this->petugas->FldCaption());

		// pembayaran
		$this->pembayaran->EditAttrs["class"] = "form-control";
		$this->pembayaran->EditCustomAttributes = "";
		$this->pembayaran->EditValue = $this->pembayaran->CurrentValue;
		$this->pembayaran->PlaceHolder = ew_RemoveHtml($this->pembayaran->FldCaption());

		// bank
		$this->bank->EditAttrs["class"] = "form-control";
		$this->bank->EditCustomAttributes = "";
		$this->bank->EditValue = $this->bank->CurrentValue;
		$this->bank->PlaceHolder = ew_RemoveHtml($this->bank->FldCaption());

		// atasnama
		$this->atasnama->EditAttrs["class"] = "form-control";
		$this->atasnama->EditCustomAttributes = "";
		$this->atasnama->EditValue = $this->atasnama->CurrentValue;
		$this->atasnama->PlaceHolder = ew_RemoveHtml($this->atasnama->FldCaption());

		// tipe
		$this->tipe->EditAttrs["class"] = "form-control";
		$this->tipe->EditCustomAttributes = "";
		$this->tipe->EditValue = $this->tipe->CurrentValue;
		$this->tipe->PlaceHolder = ew_RemoveHtml($this->tipe->FldCaption());

		// kantor
		$this->kantor->EditAttrs["class"] = "form-control";
		$this->kantor->EditCustomAttributes = "";
		$this->kantor->EditValue = $this->kantor->CurrentValue;
		$this->kantor->PlaceHolder = ew_RemoveHtml($this->kantor->FldCaption());

		// keterangan
		$this->keterangan->EditAttrs["class"] = "form-control";
		$this->keterangan->EditCustomAttributes = "";
		$this->keterangan->EditValue = $this->keterangan->CurrentValue;
		$this->keterangan->PlaceHolder = ew_RemoveHtml($this->keterangan->FldCaption());

		// active
		$this->active->EditCustomAttributes = "";
		$this->active->EditValue = $this->active->Options(FALSE);

		// ip
		$this->ip->EditAttrs["class"] = "form-control";
		$this->ip->EditCustomAttributes = "";
		$this->ip->EditValue = $this->ip->CurrentValue;
		$this->ip->PlaceHolder = ew_RemoveHtml($this->ip->FldCaption());

		// status
		$this->status->EditAttrs["class"] = "form-control";
		$this->status->EditCustomAttributes = "";
		$this->status->EditValue = $this->status->CurrentValue;
		$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

		// user
		$this->user->EditAttrs["class"] = "form-control";
		$this->user->EditCustomAttributes = "";
		$this->user->EditValue = $this->user->CurrentValue;
		$this->user->PlaceHolder = ew_RemoveHtml($this->user->FldCaption());

		// created
		$this->created->EditAttrs["class"] = "form-control";
		$this->created->EditCustomAttributes = "";
		$this->created->EditValue = ew_FormatDateTime($this->created->CurrentValue, 8);
		$this->created->PlaceHolder = ew_RemoveHtml($this->created->FldCaption());

		// modified
		$this->modified->EditAttrs["class"] = "form-control";
		$this->modified->EditCustomAttributes = "";
		$this->modified->EditValue = ew_FormatDateTime($this->modified->CurrentValue, 8);
		$this->modified->PlaceHolder = ew_RemoveHtml($this->modified->FldCaption());

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
					if ($this->transaksi->Exportable) $Doc->ExportCaption($this->transaksi);
					if ($this->referensi->Exportable) $Doc->ExportCaption($this->referensi);
					if ($this->anggota->Exportable) $Doc->ExportCaption($this->anggota);
					if ($this->namaanggota->Exportable) $Doc->ExportCaption($this->namaanggota);
					if ($this->alamat->Exportable) $Doc->ExportCaption($this->alamat);
					if ($this->pekerjaan->Exportable) $Doc->ExportCaption($this->pekerjaan);
					if ($this->telepon->Exportable) $Doc->ExportCaption($this->telepon);
					if ($this->hp->Exportable) $Doc->ExportCaption($this->hp);
					if ($this->fax->Exportable) $Doc->ExportCaption($this->fax);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->website->Exportable) $Doc->ExportCaption($this->website);
					if ($this->jenisanggota->Exportable) $Doc->ExportCaption($this->jenisanggota);
					if ($this->model->Exportable) $Doc->ExportCaption($this->model);
					if ($this->jenispinjaman->Exportable) $Doc->ExportCaption($this->jenispinjaman);
					if ($this->jenisbunga->Exportable) $Doc->ExportCaption($this->jenisbunga);
					if ($this->angsuran->Exportable) $Doc->ExportCaption($this->angsuran);
					if ($this->masaangsuran->Exportable) $Doc->ExportCaption($this->masaangsuran);
					if ($this->jatuhtempo->Exportable) $Doc->ExportCaption($this->jatuhtempo);
					if ($this->dispensasidenda->Exportable) $Doc->ExportCaption($this->dispensasidenda);
					if ($this->agunan->Exportable) $Doc->ExportCaption($this->agunan);
					if ($this->dataagunan1->Exportable) $Doc->ExportCaption($this->dataagunan1);
					if ($this->dataagunan2->Exportable) $Doc->ExportCaption($this->dataagunan2);
					if ($this->dataagunan3->Exportable) $Doc->ExportCaption($this->dataagunan3);
					if ($this->dataagunan4->Exportable) $Doc->ExportCaption($this->dataagunan4);
					if ($this->dataagunan5->Exportable) $Doc->ExportCaption($this->dataagunan5);
					if ($this->saldobekusimpanan->Exportable) $Doc->ExportCaption($this->saldobekusimpanan);
					if ($this->saldobekuminimal->Exportable) $Doc->ExportCaption($this->saldobekuminimal);
					if ($this->plafond->Exportable) $Doc->ExportCaption($this->plafond);
					if ($this->bunga->Exportable) $Doc->ExportCaption($this->bunga);
					if ($this->bungapersen->Exportable) $Doc->ExportCaption($this->bungapersen);
					if ($this->administrasi->Exportable) $Doc->ExportCaption($this->administrasi);
					if ($this->administrasipersen->Exportable) $Doc->ExportCaption($this->administrasipersen);
					if ($this->asuransi->Exportable) $Doc->ExportCaption($this->asuransi);
					if ($this->notaris->Exportable) $Doc->ExportCaption($this->notaris);
					if ($this->biayamaterai->Exportable) $Doc->ExportCaption($this->biayamaterai);
					if ($this->potongansaldobeku->Exportable) $Doc->ExportCaption($this->potongansaldobeku);
					if ($this->angsuranpokok->Exportable) $Doc->ExportCaption($this->angsuranpokok);
					if ($this->angsuranpokokauto->Exportable) $Doc->ExportCaption($this->angsuranpokokauto);
					if ($this->angsuranbunga->Exportable) $Doc->ExportCaption($this->angsuranbunga);
					if ($this->angsuranbungaauto->Exportable) $Doc->ExportCaption($this->angsuranbungaauto);
					if ($this->denda->Exportable) $Doc->ExportCaption($this->denda);
					if ($this->dendapersen->Exportable) $Doc->ExportCaption($this->dendapersen);
					if ($this->totalangsuran->Exportable) $Doc->ExportCaption($this->totalangsuran);
					if ($this->totalangsuranauto->Exportable) $Doc->ExportCaption($this->totalangsuranauto);
					if ($this->totalterima->Exportable) $Doc->ExportCaption($this->totalterima);
					if ($this->totalterimaauto->Exportable) $Doc->ExportCaption($this->totalterimaauto);
					if ($this->terbilang->Exportable) $Doc->ExportCaption($this->terbilang);
					if ($this->petugas->Exportable) $Doc->ExportCaption($this->petugas);
					if ($this->pembayaran->Exportable) $Doc->ExportCaption($this->pembayaran);
					if ($this->bank->Exportable) $Doc->ExportCaption($this->bank);
					if ($this->atasnama->Exportable) $Doc->ExportCaption($this->atasnama);
					if ($this->tipe->Exportable) $Doc->ExportCaption($this->tipe);
					if ($this->kantor->Exportable) $Doc->ExportCaption($this->kantor);
					if ($this->keterangan->Exportable) $Doc->ExportCaption($this->keterangan);
					if ($this->active->Exportable) $Doc->ExportCaption($this->active);
					if ($this->ip->Exportable) $Doc->ExportCaption($this->ip);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->user->Exportable) $Doc->ExportCaption($this->user);
					if ($this->created->Exportable) $Doc->ExportCaption($this->created);
					if ($this->modified->Exportable) $Doc->ExportCaption($this->modified);
				} else {
					if ($this->tanggal->Exportable) $Doc->ExportCaption($this->tanggal);
					if ($this->periode->Exportable) $Doc->ExportCaption($this->periode);
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->transaksi->Exportable) $Doc->ExportCaption($this->transaksi);
					if ($this->referensi->Exportable) $Doc->ExportCaption($this->referensi);
					if ($this->anggota->Exportable) $Doc->ExportCaption($this->anggota);
					if ($this->namaanggota->Exportable) $Doc->ExportCaption($this->namaanggota);
					if ($this->alamat->Exportable) $Doc->ExportCaption($this->alamat);
					if ($this->pekerjaan->Exportable) $Doc->ExportCaption($this->pekerjaan);
					if ($this->telepon->Exportable) $Doc->ExportCaption($this->telepon);
					if ($this->hp->Exportable) $Doc->ExportCaption($this->hp);
					if ($this->fax->Exportable) $Doc->ExportCaption($this->fax);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->website->Exportable) $Doc->ExportCaption($this->website);
					if ($this->jenisanggota->Exportable) $Doc->ExportCaption($this->jenisanggota);
					if ($this->model->Exportable) $Doc->ExportCaption($this->model);
					if ($this->jenispinjaman->Exportable) $Doc->ExportCaption($this->jenispinjaman);
					if ($this->jenisbunga->Exportable) $Doc->ExportCaption($this->jenisbunga);
					if ($this->angsuran->Exportable) $Doc->ExportCaption($this->angsuran);
					if ($this->masaangsuran->Exportable) $Doc->ExportCaption($this->masaangsuran);
					if ($this->jatuhtempo->Exportable) $Doc->ExportCaption($this->jatuhtempo);
					if ($this->dispensasidenda->Exportable) $Doc->ExportCaption($this->dispensasidenda);
					if ($this->agunan->Exportable) $Doc->ExportCaption($this->agunan);
					if ($this->dataagunan1->Exportable) $Doc->ExportCaption($this->dataagunan1);
					if ($this->dataagunan2->Exportable) $Doc->ExportCaption($this->dataagunan2);
					if ($this->dataagunan3->Exportable) $Doc->ExportCaption($this->dataagunan3);
					if ($this->dataagunan4->Exportable) $Doc->ExportCaption($this->dataagunan4);
					if ($this->dataagunan5->Exportable) $Doc->ExportCaption($this->dataagunan5);
					if ($this->saldobekusimpanan->Exportable) $Doc->ExportCaption($this->saldobekusimpanan);
					if ($this->saldobekuminimal->Exportable) $Doc->ExportCaption($this->saldobekuminimal);
					if ($this->plafond->Exportable) $Doc->ExportCaption($this->plafond);
					if ($this->bunga->Exportable) $Doc->ExportCaption($this->bunga);
					if ($this->bungapersen->Exportable) $Doc->ExportCaption($this->bungapersen);
					if ($this->administrasi->Exportable) $Doc->ExportCaption($this->administrasi);
					if ($this->administrasipersen->Exportable) $Doc->ExportCaption($this->administrasipersen);
					if ($this->asuransi->Exportable) $Doc->ExportCaption($this->asuransi);
					if ($this->notaris->Exportable) $Doc->ExportCaption($this->notaris);
					if ($this->biayamaterai->Exportable) $Doc->ExportCaption($this->biayamaterai);
					if ($this->potongansaldobeku->Exportable) $Doc->ExportCaption($this->potongansaldobeku);
					if ($this->angsuranpokok->Exportable) $Doc->ExportCaption($this->angsuranpokok);
					if ($this->angsuranpokokauto->Exportable) $Doc->ExportCaption($this->angsuranpokokauto);
					if ($this->angsuranbunga->Exportable) $Doc->ExportCaption($this->angsuranbunga);
					if ($this->angsuranbungaauto->Exportable) $Doc->ExportCaption($this->angsuranbungaauto);
					if ($this->denda->Exportable) $Doc->ExportCaption($this->denda);
					if ($this->dendapersen->Exportable) $Doc->ExportCaption($this->dendapersen);
					if ($this->totalangsuran->Exportable) $Doc->ExportCaption($this->totalangsuran);
					if ($this->totalangsuranauto->Exportable) $Doc->ExportCaption($this->totalangsuranauto);
					if ($this->totalterima->Exportable) $Doc->ExportCaption($this->totalterima);
					if ($this->totalterimaauto->Exportable) $Doc->ExportCaption($this->totalterimaauto);
					if ($this->terbilang->Exportable) $Doc->ExportCaption($this->terbilang);
					if ($this->petugas->Exportable) $Doc->ExportCaption($this->petugas);
					if ($this->pembayaran->Exportable) $Doc->ExportCaption($this->pembayaran);
					if ($this->bank->Exportable) $Doc->ExportCaption($this->bank);
					if ($this->atasnama->Exportable) $Doc->ExportCaption($this->atasnama);
					if ($this->tipe->Exportable) $Doc->ExportCaption($this->tipe);
					if ($this->kantor->Exportable) $Doc->ExportCaption($this->kantor);
					if ($this->keterangan->Exportable) $Doc->ExportCaption($this->keterangan);
					if ($this->active->Exportable) $Doc->ExportCaption($this->active);
					if ($this->ip->Exportable) $Doc->ExportCaption($this->ip);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->user->Exportable) $Doc->ExportCaption($this->user);
					if ($this->created->Exportable) $Doc->ExportCaption($this->created);
					if ($this->modified->Exportable) $Doc->ExportCaption($this->modified);
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
						if ($this->transaksi->Exportable) $Doc->ExportField($this->transaksi);
						if ($this->referensi->Exportable) $Doc->ExportField($this->referensi);
						if ($this->anggota->Exportable) $Doc->ExportField($this->anggota);
						if ($this->namaanggota->Exportable) $Doc->ExportField($this->namaanggota);
						if ($this->alamat->Exportable) $Doc->ExportField($this->alamat);
						if ($this->pekerjaan->Exportable) $Doc->ExportField($this->pekerjaan);
						if ($this->telepon->Exportable) $Doc->ExportField($this->telepon);
						if ($this->hp->Exportable) $Doc->ExportField($this->hp);
						if ($this->fax->Exportable) $Doc->ExportField($this->fax);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->website->Exportable) $Doc->ExportField($this->website);
						if ($this->jenisanggota->Exportable) $Doc->ExportField($this->jenisanggota);
						if ($this->model->Exportable) $Doc->ExportField($this->model);
						if ($this->jenispinjaman->Exportable) $Doc->ExportField($this->jenispinjaman);
						if ($this->jenisbunga->Exportable) $Doc->ExportField($this->jenisbunga);
						if ($this->angsuran->Exportable) $Doc->ExportField($this->angsuran);
						if ($this->masaangsuran->Exportable) $Doc->ExportField($this->masaangsuran);
						if ($this->jatuhtempo->Exportable) $Doc->ExportField($this->jatuhtempo);
						if ($this->dispensasidenda->Exportable) $Doc->ExportField($this->dispensasidenda);
						if ($this->agunan->Exportable) $Doc->ExportField($this->agunan);
						if ($this->dataagunan1->Exportable) $Doc->ExportField($this->dataagunan1);
						if ($this->dataagunan2->Exportable) $Doc->ExportField($this->dataagunan2);
						if ($this->dataagunan3->Exportable) $Doc->ExportField($this->dataagunan3);
						if ($this->dataagunan4->Exportable) $Doc->ExportField($this->dataagunan4);
						if ($this->dataagunan5->Exportable) $Doc->ExportField($this->dataagunan5);
						if ($this->saldobekusimpanan->Exportable) $Doc->ExportField($this->saldobekusimpanan);
						if ($this->saldobekuminimal->Exportable) $Doc->ExportField($this->saldobekuminimal);
						if ($this->plafond->Exportable) $Doc->ExportField($this->plafond);
						if ($this->bunga->Exportable) $Doc->ExportField($this->bunga);
						if ($this->bungapersen->Exportable) $Doc->ExportField($this->bungapersen);
						if ($this->administrasi->Exportable) $Doc->ExportField($this->administrasi);
						if ($this->administrasipersen->Exportable) $Doc->ExportField($this->administrasipersen);
						if ($this->asuransi->Exportable) $Doc->ExportField($this->asuransi);
						if ($this->notaris->Exportable) $Doc->ExportField($this->notaris);
						if ($this->biayamaterai->Exportable) $Doc->ExportField($this->biayamaterai);
						if ($this->potongansaldobeku->Exportable) $Doc->ExportField($this->potongansaldobeku);
						if ($this->angsuranpokok->Exportable) $Doc->ExportField($this->angsuranpokok);
						if ($this->angsuranpokokauto->Exportable) $Doc->ExportField($this->angsuranpokokauto);
						if ($this->angsuranbunga->Exportable) $Doc->ExportField($this->angsuranbunga);
						if ($this->angsuranbungaauto->Exportable) $Doc->ExportField($this->angsuranbungaauto);
						if ($this->denda->Exportable) $Doc->ExportField($this->denda);
						if ($this->dendapersen->Exportable) $Doc->ExportField($this->dendapersen);
						if ($this->totalangsuran->Exportable) $Doc->ExportField($this->totalangsuran);
						if ($this->totalangsuranauto->Exportable) $Doc->ExportField($this->totalangsuranauto);
						if ($this->totalterima->Exportable) $Doc->ExportField($this->totalterima);
						if ($this->totalterimaauto->Exportable) $Doc->ExportField($this->totalterimaauto);
						if ($this->terbilang->Exportable) $Doc->ExportField($this->terbilang);
						if ($this->petugas->Exportable) $Doc->ExportField($this->petugas);
						if ($this->pembayaran->Exportable) $Doc->ExportField($this->pembayaran);
						if ($this->bank->Exportable) $Doc->ExportField($this->bank);
						if ($this->atasnama->Exportable) $Doc->ExportField($this->atasnama);
						if ($this->tipe->Exportable) $Doc->ExportField($this->tipe);
						if ($this->kantor->Exportable) $Doc->ExportField($this->kantor);
						if ($this->keterangan->Exportable) $Doc->ExportField($this->keterangan);
						if ($this->active->Exportable) $Doc->ExportField($this->active);
						if ($this->ip->Exportable) $Doc->ExportField($this->ip);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->user->Exportable) $Doc->ExportField($this->user);
						if ($this->created->Exportable) $Doc->ExportField($this->created);
						if ($this->modified->Exportable) $Doc->ExportField($this->modified);
					} else {
						if ($this->tanggal->Exportable) $Doc->ExportField($this->tanggal);
						if ($this->periode->Exportable) $Doc->ExportField($this->periode);
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->transaksi->Exportable) $Doc->ExportField($this->transaksi);
						if ($this->referensi->Exportable) $Doc->ExportField($this->referensi);
						if ($this->anggota->Exportable) $Doc->ExportField($this->anggota);
						if ($this->namaanggota->Exportable) $Doc->ExportField($this->namaanggota);
						if ($this->alamat->Exportable) $Doc->ExportField($this->alamat);
						if ($this->pekerjaan->Exportable) $Doc->ExportField($this->pekerjaan);
						if ($this->telepon->Exportable) $Doc->ExportField($this->telepon);
						if ($this->hp->Exportable) $Doc->ExportField($this->hp);
						if ($this->fax->Exportable) $Doc->ExportField($this->fax);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->website->Exportable) $Doc->ExportField($this->website);
						if ($this->jenisanggota->Exportable) $Doc->ExportField($this->jenisanggota);
						if ($this->model->Exportable) $Doc->ExportField($this->model);
						if ($this->jenispinjaman->Exportable) $Doc->ExportField($this->jenispinjaman);
						if ($this->jenisbunga->Exportable) $Doc->ExportField($this->jenisbunga);
						if ($this->angsuran->Exportable) $Doc->ExportField($this->angsuran);
						if ($this->masaangsuran->Exportable) $Doc->ExportField($this->masaangsuran);
						if ($this->jatuhtempo->Exportable) $Doc->ExportField($this->jatuhtempo);
						if ($this->dispensasidenda->Exportable) $Doc->ExportField($this->dispensasidenda);
						if ($this->agunan->Exportable) $Doc->ExportField($this->agunan);
						if ($this->dataagunan1->Exportable) $Doc->ExportField($this->dataagunan1);
						if ($this->dataagunan2->Exportable) $Doc->ExportField($this->dataagunan2);
						if ($this->dataagunan3->Exportable) $Doc->ExportField($this->dataagunan3);
						if ($this->dataagunan4->Exportable) $Doc->ExportField($this->dataagunan4);
						if ($this->dataagunan5->Exportable) $Doc->ExportField($this->dataagunan5);
						if ($this->saldobekusimpanan->Exportable) $Doc->ExportField($this->saldobekusimpanan);
						if ($this->saldobekuminimal->Exportable) $Doc->ExportField($this->saldobekuminimal);
						if ($this->plafond->Exportable) $Doc->ExportField($this->plafond);
						if ($this->bunga->Exportable) $Doc->ExportField($this->bunga);
						if ($this->bungapersen->Exportable) $Doc->ExportField($this->bungapersen);
						if ($this->administrasi->Exportable) $Doc->ExportField($this->administrasi);
						if ($this->administrasipersen->Exportable) $Doc->ExportField($this->administrasipersen);
						if ($this->asuransi->Exportable) $Doc->ExportField($this->asuransi);
						if ($this->notaris->Exportable) $Doc->ExportField($this->notaris);
						if ($this->biayamaterai->Exportable) $Doc->ExportField($this->biayamaterai);
						if ($this->potongansaldobeku->Exportable) $Doc->ExportField($this->potongansaldobeku);
						if ($this->angsuranpokok->Exportable) $Doc->ExportField($this->angsuranpokok);
						if ($this->angsuranpokokauto->Exportable) $Doc->ExportField($this->angsuranpokokauto);
						if ($this->angsuranbunga->Exportable) $Doc->ExportField($this->angsuranbunga);
						if ($this->angsuranbungaauto->Exportable) $Doc->ExportField($this->angsuranbungaauto);
						if ($this->denda->Exportable) $Doc->ExportField($this->denda);
						if ($this->dendapersen->Exportable) $Doc->ExportField($this->dendapersen);
						if ($this->totalangsuran->Exportable) $Doc->ExportField($this->totalangsuran);
						if ($this->totalangsuranauto->Exportable) $Doc->ExportField($this->totalangsuranauto);
						if ($this->totalterima->Exportable) $Doc->ExportField($this->totalterima);
						if ($this->totalterimaauto->Exportable) $Doc->ExportField($this->totalterimaauto);
						if ($this->terbilang->Exportable) $Doc->ExportField($this->terbilang);
						if ($this->petugas->Exportable) $Doc->ExportField($this->petugas);
						if ($this->pembayaran->Exportable) $Doc->ExportField($this->pembayaran);
						if ($this->bank->Exportable) $Doc->ExportField($this->bank);
						if ($this->atasnama->Exportable) $Doc->ExportField($this->atasnama);
						if ($this->tipe->Exportable) $Doc->ExportField($this->tipe);
						if ($this->kantor->Exportable) $Doc->ExportField($this->kantor);
						if ($this->keterangan->Exportable) $Doc->ExportField($this->keterangan);
						if ($this->active->Exportable) $Doc->ExportField($this->active);
						if ($this->ip->Exportable) $Doc->ExportField($this->ip);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->user->Exportable) $Doc->ExportField($this->user);
						if ($this->created->Exportable) $Doc->ExportField($this->created);
						if ($this->modified->Exportable) $Doc->ExportField($this->modified);
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
