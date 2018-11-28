<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(10022, "mi_trekening2", $Language->MenuPhrase("10022", "MenuText"), "trekening2list.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(21, "mi_Report1", $Language->MenuPhrase("21", "MenuText"), "Report1report.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(1, "mi_tanggota", $Language->MenuPhrase("1", "MenuText"), "tanggotalist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(2, "mi_tbayarangsuran", $Language->MenuPhrase("2", "MenuText"), "tbayarangsuranlist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(3, "mi_tbayartitipan", $Language->MenuPhrase("3", "MenuText"), "tbayartitipanlist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(4, "mi_tbayartitipansaldo", $Language->MenuPhrase("4", "MenuText"), "tbayartitipansaldolist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(5, "mi_texcel", $Language->MenuPhrase("5", "MenuText"), "texcellist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(6, "mi_texcelsheet", $Language->MenuPhrase("6", "MenuText"), "texcelsheetlist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(7, "mi_tjurnal", $Language->MenuPhrase("7", "MenuText"), "tjurnallist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(8, "mi_tjurnalkasbank", $Language->MenuPhrase("8", "MenuText"), "tjurnalkasbanklist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(9, "mi_tjurnalsaldo", $Language->MenuPhrase("9", "MenuText"), "tjurnalsaldolist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(10, "mi_tjurnaltransaksi", $Language->MenuPhrase("10", "MenuText"), "tjurnaltransaksilist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(11, "mi_tkantor", $Language->MenuPhrase("11", "MenuText"), "tkantorlist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(12, "mi_tlaporan", $Language->MenuPhrase("12", "MenuText"), "tlaporanlist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(13, "mi_tpinjaman", $Language->MenuPhrase("13", "MenuText"), "tpinjamanlist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(14, "mi_tpinjamandetail", $Language->MenuPhrase("14", "MenuText"), "tpinjamandetaillist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(15, "mi_tpinjamandetail_", $Language->MenuPhrase("15", "MenuText"), "tpinjamandetail_list.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(16, "mi_trekening", $Language->MenuPhrase("16", "MenuText"), "trekeninglist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(17, "mi_tuser", $Language->MenuPhrase("17", "MenuText"), "tuserlist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(18, "mi_vrekening", $Language->MenuPhrase("18", "MenuText"), "vrekeninglist.php?cmd=resetall", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(19, "mi_vrekeninggroup", $Language->MenuPhrase("19", "MenuText"), "vrekeninggrouplist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(20, "mi_vrekening2", $Language->MenuPhrase("20", "MenuText"), "vrekening2list.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(10021, "mri_Report1", $Language->MenuPhrase("10021", "MenuText"), "Report1smry.php", -1, "{e3b093f1-120d-4a26-9ac0-4e96d12c121b}", TRUE, FALSE, FALSE);
$RootMenu->Render();
?>
<!-- End Main Menu -->
