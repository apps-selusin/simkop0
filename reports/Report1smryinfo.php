<?php

// Global variable for table object
$Report1 = NULL;

//
// Table class for Report1
//
class crReport1 extends crTableBase {
	var $ShowGroupHeaderAsRow = FALSE;
	var $ShowCompactSummaryFooter = TRUE;
	var $id1;
	var $rekening1;
	var $id2;
	var $rekening2;
	var $tipe;
	var $posisi;
	var $laporan;
	var $status;
	var $parent;
	var $keterangan;
	var $active;
	var $group;
	var $id;
	var $rekening;

	//
	// Table class constructor
	//
	function __construct() {
		global $ReportLanguage, $gsLanguage;
		$this->TableVar = 'Report1';
		$this->TableName = 'Report1';
		$this->TableType = 'REPORT';
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0;

		// id1
		$this->id1 = new crField('Report1', 'Report1', 'x_id1', 'id1', '`id1`', 200, EWR_DATATYPE_STRING, -1);
		$this->id1->Sortable = TRUE; // Allow sort
		$this->fields['id1'] = &$this->id1;
		$this->id1->DateFilter = "";
		$this->id1->SqlSelect = "";
		$this->id1->SqlOrderBy = "";

		// rekening1
		$this->rekening1 = new crField('Report1', 'Report1', 'x_rekening1', 'rekening1', '`rekening1`', 200, EWR_DATATYPE_STRING, -1);
		$this->rekening1->Sortable = TRUE; // Allow sort
		$this->fields['rekening1'] = &$this->rekening1;
		$this->rekening1->DateFilter = "";
		$this->rekening1->SqlSelect = "";
		$this->rekening1->SqlOrderBy = "";

		// id2
		$this->id2 = new crField('Report1', 'Report1', 'x_id2', 'id2', '`id2`', 200, EWR_DATATYPE_STRING, -1);
		$this->id2->Sortable = TRUE; // Allow sort
		$this->fields['id2'] = &$this->id2;
		$this->id2->DateFilter = "";
		$this->id2->SqlSelect = "";
		$this->id2->SqlOrderBy = "";

		// rekening2
		$this->rekening2 = new crField('Report1', 'Report1', 'x_rekening2', 'rekening2', '`rekening2`', 200, EWR_DATATYPE_STRING, -1);
		$this->rekening2->Sortable = TRUE; // Allow sort
		$this->fields['rekening2'] = &$this->rekening2;
		$this->rekening2->DateFilter = "";
		$this->rekening2->SqlSelect = "";
		$this->rekening2->SqlOrderBy = "";

		// tipe
		$this->tipe = new crField('Report1', 'Report1', 'x_tipe', 'tipe', '`tipe`', 200, EWR_DATATYPE_STRING, -1);
		$this->tipe->Sortable = TRUE; // Allow sort
		$this->fields['tipe'] = &$this->tipe;
		$this->tipe->DateFilter = "";
		$this->tipe->SqlSelect = "";
		$this->tipe->SqlOrderBy = "";

		// posisi
		$this->posisi = new crField('Report1', 'Report1', 'x_posisi', 'posisi', '`posisi`', 200, EWR_DATATYPE_STRING, -1);
		$this->posisi->Sortable = TRUE; // Allow sort
		$this->fields['posisi'] = &$this->posisi;
		$this->posisi->DateFilter = "";
		$this->posisi->SqlSelect = "";
		$this->posisi->SqlOrderBy = "";

		// laporan
		$this->laporan = new crField('Report1', 'Report1', 'x_laporan', 'laporan', '`laporan`', 200, EWR_DATATYPE_STRING, -1);
		$this->laporan->Sortable = TRUE; // Allow sort
		$this->fields['laporan'] = &$this->laporan;
		$this->laporan->DateFilter = "";
		$this->laporan->SqlSelect = "";
		$this->laporan->SqlOrderBy = "";

		// status
		$this->status = new crField('Report1', 'Report1', 'x_status', 'status', '`status`', 200, EWR_DATATYPE_STRING, -1);
		$this->status->Sortable = TRUE; // Allow sort
		$this->fields['status'] = &$this->status;
		$this->status->DateFilter = "";
		$this->status->SqlSelect = "";
		$this->status->SqlOrderBy = "";

		// parent
		$this->parent = new crField('Report1', 'Report1', 'x_parent', 'parent', '`parent`', 200, EWR_DATATYPE_STRING, -1);
		$this->parent->Sortable = TRUE; // Allow sort
		$this->fields['parent'] = &$this->parent;
		$this->parent->DateFilter = "";
		$this->parent->SqlSelect = "";
		$this->parent->SqlOrderBy = "";

		// keterangan
		$this->keterangan = new crField('Report1', 'Report1', 'x_keterangan', 'keterangan', '`keterangan`', 200, EWR_DATATYPE_STRING, -1);
		$this->keterangan->Sortable = TRUE; // Allow sort
		$this->fields['keterangan'] = &$this->keterangan;
		$this->keterangan->DateFilter = "";
		$this->keterangan->SqlSelect = "";
		$this->keterangan->SqlOrderBy = "";

		// active
		$this->active = new crField('Report1', 'Report1', 'x_active', 'active', '`active`', 202, EWR_DATATYPE_STRING, -1);
		$this->active->Sortable = TRUE; // Allow sort
		$this->fields['active'] = &$this->active;
		$this->active->DateFilter = "";
		$this->active->SqlSelect = "";
		$this->active->SqlOrderBy = "";

		// group
		$this->group = new crField('Report1', 'Report1', 'x_group', 'group', '`group`', 20, EWR_DATATYPE_NUMBER, -1);
		$this->group->Sortable = TRUE; // Allow sort
		$this->group->GroupingFieldId = 1;
		$this->group->ShowGroupHeaderAsRow = $this->ShowGroupHeaderAsRow;
		$this->group->ShowCompactSummaryFooter = $this->ShowCompactSummaryFooter;
		$this->group->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['group'] = &$this->group;
		$this->group->DateFilter = "";
		$this->group->SqlSelect = "";
		$this->group->SqlOrderBy = "";
		$this->group->FldGroupByType = "";
		$this->group->FldGroupInt = "0";
		$this->group->FldGroupSql = "";

		// id
		$this->id = new crField('Report1', 'Report1', 'x_id', 'id', '`id`', 200, EWR_DATATYPE_STRING, -1);
		$this->id->Sortable = TRUE; // Allow sort
		$this->fields['id'] = &$this->id;
		$this->id->DateFilter = "";
		$this->id->SqlSelect = "";
		$this->id->SqlOrderBy = "";

		// rekening
		$this->rekening = new crField('Report1', 'Report1', 'x_rekening', 'rekening', '`rekening`', 200, EWR_DATATYPE_STRING, -1);
		$this->rekening->Sortable = TRUE; // Allow sort
		$this->fields['rekening'] = &$this->rekening;
		$this->rekening->DateFilter = "";
		$this->rekening->SqlSelect = "";
		$this->rekening->SqlOrderBy = "";
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
			if ($ofld->GroupingFieldId == 0)
				$this->setDetailOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			if ($ofld->GroupingFieldId == 0) $ofld->setSort("");
		}
	}

	// Get Sort SQL
	function SortSql() {
		$sDtlSortSql = $this->getDetailOrderBy(); // Get ORDER BY for detail fields from session
		$argrps = array();
		foreach ($this->fields as $fld) {
			if ($fld->getSort() <> "") {
				$fldsql = $fld->FldExpression;
				if ($fld->GroupingFieldId > 0) {
					if ($fld->FldGroupSql <> "")
						$argrps[$fld->GroupingFieldId] = str_replace("%s", $fldsql, $fld->FldGroupSql) . " " . $fld->getSort();
					else
						$argrps[$fld->GroupingFieldId] = $fldsql . " " . $fld->getSort();
				}
			}
		}
		$sSortSql = "";
		foreach ($argrps as $grp) {
			if ($sSortSql <> "") $sSortSql .= ", ";
			$sSortSql .= $grp;
		}
		if ($sDtlSortSql <> "") {
			if ($sSortSql <> "") $sSortSql .= ", ";
			$sSortSql .= $sDtlSortSql;
		}
		return $sSortSql;
	}

	// Table level SQL
	// From

	var $_SqlFrom = "";

	function getSqlFrom() {
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`vrekening2`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}

	// Select
	var $_SqlSelect = "";

	function getSqlSelect() {
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}

	// Where
	var $_SqlWhere = "";

	function getSqlWhere() {
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}

	// Group By
	var $_SqlGroupBy = "";

	function getSqlGroupBy() {
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}

	// Having
	var $_SqlHaving = "";

	function getSqlHaving() {
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}

	// Order By
	var $_SqlOrderBy = "";

	function getSqlOrderBy() {
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "`group` ASC";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Table Level Group SQL
	// First Group Field

	var $_SqlFirstGroupField = "";

	function getSqlFirstGroupField() {
		return ($this->_SqlFirstGroupField <> "") ? $this->_SqlFirstGroupField : "`group`";
	}

	function SqlFirstGroupField() { // For backward compatibility
		return $this->getSqlFirstGroupField();
	}

	function setSqlFirstGroupField($v) {
		$this->_SqlFirstGroupField = $v;
	}

	// Select Group
	var $_SqlSelectGroup = "";

	function getSqlSelectGroup() {
		return ($this->_SqlSelectGroup <> "") ? $this->_SqlSelectGroup : "SELECT DISTINCT " . $this->getSqlFirstGroupField() . " FROM " . $this->getSqlFrom();
	}

	function SqlSelectGroup() { // For backward compatibility
		return $this->getSqlSelectGroup();
	}

	function setSqlSelectGroup($v) {
		$this->_SqlSelectGroup = $v;
	}

	// Order By Group
	var $_SqlOrderByGroup = "";

	function getSqlOrderByGroup() {
		return ($this->_SqlOrderByGroup <> "") ? $this->_SqlOrderByGroup : "`group` ASC";
	}

	function SqlOrderByGroup() { // For backward compatibility
		return $this->getSqlOrderByGroup();
	}

	function setSqlOrderByGroup($v) {
		$this->_SqlOrderByGroup = $v;
	}

	// Select Aggregate
	var $_SqlSelectAgg = "";

	function getSqlSelectAgg() {
		return ($this->_SqlSelectAgg <> "") ? $this->_SqlSelectAgg : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelectAgg() { // For backward compatibility
		return $this->getSqlSelectAgg();
	}

	function setSqlSelectAgg($v) {
		$this->_SqlSelectAgg = $v;
	}

	// Aggregate Prefix
	var $_SqlAggPfx = "";

	function getSqlAggPfx() {
		return ($this->_SqlAggPfx <> "") ? $this->_SqlAggPfx : "";
	}

	function SqlAggPfx() { // For backward compatibility
		return $this->getSqlAggPfx();
	}

	function setSqlAggPfx($v) {
		$this->_SqlAggPfx = $v;
	}

	// Aggregate Suffix
	var $_SqlAggSfx = "";

	function getSqlAggSfx() {
		return ($this->_SqlAggSfx <> "") ? $this->_SqlAggSfx : "";
	}

	function SqlAggSfx() { // For backward compatibility
		return $this->getSqlAggSfx();
	}

	function setSqlAggSfx($v) {
		$this->_SqlAggSfx = $v;
	}

	// Select Count
	var $_SqlSelectCount = "";

	function getSqlSelectCount() {
		return ($this->_SqlSelectCount <> "") ? $this->_SqlSelectCount : "SELECT COUNT(*) FROM " . $this->getSqlFrom();
	}

	function SqlSelectCount() { // For backward compatibility
		return $this->getSqlSelectCount();
	}

	function setSqlSelectCount($v) {
		$this->_SqlSelectCount = $v;
	}

	// Sort URL
	function SortUrl(&$fld) {
		return "";
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld) {
		global $gsLanguage;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld) {
		global $gsLanguage;
		switch ($fld->FldVar) {
		}
	}

	// Table level events
	// Page Selecting event
	function Page_Selecting(&$filter) {

		// Enter your code here
	}

	// Page Breaking event
	function Page_Breaking(&$break, &$content) {

		// Example:
		//$break = FALSE; // Skip page break, or
		//$content = "<div style=\"page-break-after:always;\">&nbsp;</div>"; // Modify page break content

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here
	}

	// Cell Rendered event
	function Cell_Rendered(&$Field, $CurrentValue, &$ViewValue, &$ViewAttrs, &$CellAttrs, &$HrefValue, &$LinkAttrs) {

		//$ViewValue = "xxx";
		//$ViewAttrs["style"] = "xxx";

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

	// Load Filters event
	function Page_FilterLoad() {

		// Enter your code here
		// Example: Register/Unregister Custom Extended Filter
		//ewr_RegisterFilter($this-><Field>, 'StartsWithA', 'Starts With A', 'GetStartsWithAFilter'); // With function, or
		//ewr_RegisterFilter($this-><Field>, 'StartsWithA', 'Starts With A'); // No function, use Page_Filtering event
		//ewr_UnregisterFilter($this-><Field>, 'StartsWithA');

	}

	// Page Filter Validated event
	function Page_FilterValidated() {

		// Example:
		//$this->MyField1->SearchValue = "your search criteria"; // Search value

	}

	// Page Filtering event
	function Page_Filtering(&$fld, &$filter, $typ, $opr = "", $val = "", $cond = "", $opr2 = "", $val2 = "") {

		// Note: ALWAYS CHECK THE FILTER TYPE ($typ)! Example:
		//if ($typ == "dropdown" && $fld->FldName == "MyField") // Dropdown filter
		//	$filter = "..."; // Modify the filter
		//if ($typ == "extended" && $fld->FldName == "MyField") // Extended filter
		//	$filter = "..."; // Modify the filter
		//if ($typ == "popup" && $fld->FldName == "MyField") // Popup filter
		//	$filter = "..."; // Modify the filter
		//if ($typ == "custom" && $opr == "..." && $fld->FldName == "MyField") // Custom filter, $opr is the custom filter ID
		//	$filter = "..."; // Modify the filter

	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		// Enter your code here
	}
}
?>
