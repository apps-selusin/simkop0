<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(1, "mmi_tanggota", $Language->MenuPhrase("1", "MenuText"), "tanggotalist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(2, "mmi_tbayarangsuran", $Language->MenuPhrase("2", "MenuText"), "tbayarangsuranlist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(3, "mmi_tbayartitipan", $Language->MenuPhrase("3", "MenuText"), "tbayartitipanlist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(4, "mmi_tbayartitipansaldo", $Language->MenuPhrase("4", "MenuText"), "tbayartitipansaldolist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(5, "mmi_texcel", $Language->MenuPhrase("5", "MenuText"), "texcellist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(6, "mmi_texcelsheet", $Language->MenuPhrase("6", "MenuText"), "texcelsheetlist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(7, "mmi_tjurnal", $Language->MenuPhrase("7", "MenuText"), "tjurnallist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(8, "mmi_tjurnalkasbank", $Language->MenuPhrase("8", "MenuText"), "tjurnalkasbanklist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(9, "mmi_tjurnalsaldo", $Language->MenuPhrase("9", "MenuText"), "tjurnalsaldolist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(10, "mmi_tjurnaltransaksi", $Language->MenuPhrase("10", "MenuText"), "tjurnaltransaksilist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(11, "mmi_tkantor", $Language->MenuPhrase("11", "MenuText"), "tkantorlist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(12, "mmi_tlaporan", $Language->MenuPhrase("12", "MenuText"), "tlaporanlist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(13, "mmi_tpinjaman", $Language->MenuPhrase("13", "MenuText"), "tpinjamanlist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(14, "mmi_tpinjamandetail", $Language->MenuPhrase("14", "MenuText"), "tpinjamandetaillist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(15, "mmi_tpinjamandetail_", $Language->MenuPhrase("15", "MenuText"), "tpinjamandetail_list.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(16, "mmi_trekening", $Language->MenuPhrase("16", "MenuText"), "trekeninglist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(17, "mmi_tuser", $Language->MenuPhrase("17", "MenuText"), "tuserlist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(18, "mmi_vrekening", $Language->MenuPhrase("18", "MenuText"), "vrekeninglist.php?cmd=resetall", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(19, "mmi_vrekeninggroup", $Language->MenuPhrase("19", "MenuText"), "vrekeninggrouplist.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(20, "mmi_vrekening2", $Language->MenuPhrase("20", "MenuText"), "vrekening2list.php", -1, "", TRUE, FALSE, FALSE);
$RootMenu->Render();
?>
<!-- End Main Menu -->
