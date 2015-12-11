<?php

// Global variable for table object
$upload_file_detail = NULL;

//
// Table class for upload_file_detail
//
class cupload_file_detail extends cTable {
	var $UPLOAD_FILE_DETAIL_ID;
	var $CODE;
	var $DESCRIPTION;
	var $DATE;
	var $AMOUNT;
	var $UPLOAD_FILE_ID;
	var $UPLOAD_FILE_DETAIL_STATUS_ID;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'upload_file_detail';
		$this->TableName = 'upload_file_detail';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`upload_file_detail`";
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

		// UPLOAD_FILE_DETAIL_ID
		$this->UPLOAD_FILE_DETAIL_ID = new cField('upload_file_detail', 'upload_file_detail', 'x_UPLOAD_FILE_DETAIL_ID', 'UPLOAD_FILE_DETAIL_ID', '`UPLOAD_FILE_DETAIL_ID`', '`UPLOAD_FILE_DETAIL_ID`', 3, -1, FALSE, '`UPLOAD_FILE_DETAIL_ID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->UPLOAD_FILE_DETAIL_ID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['UPLOAD_FILE_DETAIL_ID'] = &$this->UPLOAD_FILE_DETAIL_ID;

		// CODE
		$this->CODE = new cField('upload_file_detail', 'upload_file_detail', 'x_CODE', 'CODE', '`CODE`', '`CODE`', 131, -1, FALSE, '`CODE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->CODE->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['CODE'] = &$this->CODE;

		// DESCRIPTION
		$this->DESCRIPTION = new cField('upload_file_detail', 'upload_file_detail', 'x_DESCRIPTION', 'DESCRIPTION', '`DESCRIPTION`', '`DESCRIPTION`', 200, -1, FALSE, '`DESCRIPTION`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['DESCRIPTION'] = &$this->DESCRIPTION;

		// DATE
		$this->DATE = new cField('upload_file_detail', 'upload_file_detail', 'x_DATE', 'DATE', '`DATE`', 'DATE_FORMAT(`DATE`, \'%d/%m/%Y\')', 133, 7, FALSE, '`DATE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->DATE->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['DATE'] = &$this->DATE;

		// AMOUNT
		$this->AMOUNT = new cField('upload_file_detail', 'upload_file_detail', 'x_AMOUNT', 'AMOUNT', '`AMOUNT`', '`AMOUNT`', 131, -1, FALSE, '`AMOUNT`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->AMOUNT->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['AMOUNT'] = &$this->AMOUNT;

		// UPLOAD_FILE_ID
		$this->UPLOAD_FILE_ID = new cField('upload_file_detail', 'upload_file_detail', 'x_UPLOAD_FILE_ID', 'UPLOAD_FILE_ID', '`UPLOAD_FILE_ID`', '`UPLOAD_FILE_ID`', 3, -1, FALSE, '`UPLOAD_FILE_ID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->UPLOAD_FILE_ID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['UPLOAD_FILE_ID'] = &$this->UPLOAD_FILE_ID;

		// UPLOAD_FILE_DETAIL_STATUS_ID
		$this->UPLOAD_FILE_DETAIL_STATUS_ID = new cField('upload_file_detail', 'upload_file_detail', 'x_UPLOAD_FILE_DETAIL_STATUS_ID', 'UPLOAD_FILE_DETAIL_STATUS_ID', '`UPLOAD_FILE_DETAIL_STATUS_ID`', '`UPLOAD_FILE_DETAIL_STATUS_ID`', 3, -1, FALSE, '`UPLOAD_FILE_DETAIL_STATUS_ID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->UPLOAD_FILE_DETAIL_STATUS_ID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['UPLOAD_FILE_DETAIL_STATUS_ID'] = &$this->UPLOAD_FILE_DETAIL_STATUS_ID;
	}

	// Multiple column sort
	function UpdateSort(&$ofld, $ctrl) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			if ($ctrl) {
				$sOrderBy = $this->getSessionOrderBy();
				if (strpos($sOrderBy, $sSortField . " " . $sLastSort) !== FALSE) {
					$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
				} else {
					if ($sOrderBy <> "") $sOrderBy .= ", ";
					$sOrderBy .= $sSortField . " " . $sThisSort;
				}
				$this->setSessionOrderBy($sOrderBy); // Save to Session
			} else {
				$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
			}
		} else {
			if (!$ctrl) $ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`upload_file_detail`";
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
		return $conn->Execute($this->InsertSQL($rs));
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
		return $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('UPLOAD_FILE_DETAIL_ID', $rs))
				ew_AddFilter($where, ew_QuotedName('UPLOAD_FILE_DETAIL_ID', $this->DBID) . '=' . ew_QuotedValue($rs['UPLOAD_FILE_DETAIL_ID'], $this->UPLOAD_FILE_DETAIL_ID->FldDataType, $this->DBID));
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
		return $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`UPLOAD_FILE_DETAIL_ID` = @UPLOAD_FILE_DETAIL_ID@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->UPLOAD_FILE_DETAIL_ID->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@UPLOAD_FILE_DETAIL_ID@", ew_AdjustSql($this->UPLOAD_FILE_DETAIL_ID->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "upload_file_detaillist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "upload_file_detaillist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("upload_file_detailview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("upload_file_detailview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "upload_file_detailadd.php?" . $this->UrlParm($parm);
		else
			$url = "upload_file_detailadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("upload_file_detailedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("upload_file_detailadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("upload_file_detaildelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "UPLOAD_FILE_DETAIL_ID:" . ew_VarToJson($this->UPLOAD_FILE_DETAIL_ID->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->UPLOAD_FILE_DETAIL_ID->CurrentValue)) {
			$sUrl .= "UPLOAD_FILE_DETAIL_ID=" . urlencode($this->UPLOAD_FILE_DETAIL_ID->CurrentValue);
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
			return ew_CurrentPage() . "?" . $sUrlParm;
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
			if ($isPost && isset($_POST["UPLOAD_FILE_DETAIL_ID"]))
				$arKeys[] = ew_StripSlashes($_POST["UPLOAD_FILE_DETAIL_ID"]);
			elseif (isset($_GET["UPLOAD_FILE_DETAIL_ID"]))
				$arKeys[] = ew_StripSlashes($_GET["UPLOAD_FILE_DETAIL_ID"]);
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
			$this->UPLOAD_FILE_DETAIL_ID->CurrentValue = $key;
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
		$this->UPLOAD_FILE_DETAIL_ID->setDbValue($rs->fields('UPLOAD_FILE_DETAIL_ID'));
		$this->CODE->setDbValue($rs->fields('CODE'));
		$this->DESCRIPTION->setDbValue($rs->fields('DESCRIPTION'));
		$this->DATE->setDbValue($rs->fields('DATE'));
		$this->AMOUNT->setDbValue($rs->fields('AMOUNT'));
		$this->UPLOAD_FILE_ID->setDbValue($rs->fields('UPLOAD_FILE_ID'));
		$this->UPLOAD_FILE_DETAIL_STATUS_ID->setDbValue($rs->fields('UPLOAD_FILE_DETAIL_STATUS_ID'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// UPLOAD_FILE_DETAIL_ID
		// CODE
		// DESCRIPTION
		// DATE
		// AMOUNT
		// UPLOAD_FILE_ID
		// UPLOAD_FILE_DETAIL_STATUS_ID
		// UPLOAD_FILE_DETAIL_ID

		$this->UPLOAD_FILE_DETAIL_ID->ViewValue = $this->UPLOAD_FILE_DETAIL_ID->CurrentValue;
		$this->UPLOAD_FILE_DETAIL_ID->ViewCustomAttributes = "";

		// CODE
		$this->CODE->ViewValue = $this->CODE->CurrentValue;
		$this->CODE->ViewCustomAttributes = "";

		// DESCRIPTION
		$this->DESCRIPTION->ViewValue = $this->DESCRIPTION->CurrentValue;
		$this->DESCRIPTION->ViewCustomAttributes = "";

		// DATE
		$this->DATE->ViewValue = $this->DATE->CurrentValue;
		$this->DATE->ViewValue = ew_FormatDateTime($this->DATE->ViewValue, 7);
		$this->DATE->ViewCustomAttributes = "";

		// AMOUNT
		$this->AMOUNT->ViewValue = $this->AMOUNT->CurrentValue;
		$this->AMOUNT->ViewCustomAttributes = "";

		// UPLOAD_FILE_ID
		$this->UPLOAD_FILE_ID->ViewValue = $this->UPLOAD_FILE_ID->CurrentValue;
		$this->UPLOAD_FILE_ID->ViewCustomAttributes = "";

		// UPLOAD_FILE_DETAIL_STATUS_ID
		$this->UPLOAD_FILE_DETAIL_STATUS_ID->ViewValue = $this->UPLOAD_FILE_DETAIL_STATUS_ID->CurrentValue;
		$this->UPLOAD_FILE_DETAIL_STATUS_ID->ViewCustomAttributes = "";

		// UPLOAD_FILE_DETAIL_ID
		$this->UPLOAD_FILE_DETAIL_ID->LinkCustomAttributes = "";
		$this->UPLOAD_FILE_DETAIL_ID->HrefValue = "";
		$this->UPLOAD_FILE_DETAIL_ID->TooltipValue = "";

		// CODE
		$this->CODE->LinkCustomAttributes = "";
		$this->CODE->HrefValue = "";
		$this->CODE->TooltipValue = "";

		// DESCRIPTION
		$this->DESCRIPTION->LinkCustomAttributes = "";
		$this->DESCRIPTION->HrefValue = "";
		$this->DESCRIPTION->TooltipValue = "";

		// DATE
		$this->DATE->LinkCustomAttributes = "";
		$this->DATE->HrefValue = "";
		$this->DATE->TooltipValue = "";

		// AMOUNT
		$this->AMOUNT->LinkCustomAttributes = "";
		$this->AMOUNT->HrefValue = "";
		$this->AMOUNT->TooltipValue = "";

		// UPLOAD_FILE_ID
		$this->UPLOAD_FILE_ID->LinkCustomAttributes = "";
		$this->UPLOAD_FILE_ID->HrefValue = "";
		$this->UPLOAD_FILE_ID->TooltipValue = "";

		// UPLOAD_FILE_DETAIL_STATUS_ID
		$this->UPLOAD_FILE_DETAIL_STATUS_ID->LinkCustomAttributes = "";
		$this->UPLOAD_FILE_DETAIL_STATUS_ID->HrefValue = "";
		$this->UPLOAD_FILE_DETAIL_STATUS_ID->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// UPLOAD_FILE_DETAIL_ID
		$this->UPLOAD_FILE_DETAIL_ID->EditAttrs["class"] = "form-control";
		$this->UPLOAD_FILE_DETAIL_ID->EditCustomAttributes = "";
		$this->UPLOAD_FILE_DETAIL_ID->EditValue = $this->UPLOAD_FILE_DETAIL_ID->CurrentValue;
		$this->UPLOAD_FILE_DETAIL_ID->ViewCustomAttributes = "";

		// CODE
		$this->CODE->EditAttrs["class"] = "form-control";
		$this->CODE->EditCustomAttributes = "";
		$this->CODE->EditValue = $this->CODE->CurrentValue;
		$this->CODE->PlaceHolder = ew_RemoveHtml($this->CODE->FldCaption());
		if (strval($this->CODE->EditValue) <> "" && is_numeric($this->CODE->EditValue)) $this->CODE->EditValue = ew_FormatNumber($this->CODE->EditValue, -2, -1, -2, 0);

		// DESCRIPTION
		$this->DESCRIPTION->EditAttrs["class"] = "form-control";
		$this->DESCRIPTION->EditCustomAttributes = "";
		$this->DESCRIPTION->EditValue = $this->DESCRIPTION->CurrentValue;
		$this->DESCRIPTION->PlaceHolder = ew_RemoveHtml($this->DESCRIPTION->FldCaption());

		// DATE
		$this->DATE->EditAttrs["class"] = "form-control";
		$this->DATE->EditCustomAttributes = "";
		$this->DATE->EditValue = ew_FormatDateTime($this->DATE->CurrentValue, 7);
		$this->DATE->PlaceHolder = ew_RemoveHtml($this->DATE->FldCaption());

		// AMOUNT
		$this->AMOUNT->EditAttrs["class"] = "form-control";
		$this->AMOUNT->EditCustomAttributes = "";
		$this->AMOUNT->EditValue = $this->AMOUNT->CurrentValue;
		$this->AMOUNT->PlaceHolder = ew_RemoveHtml($this->AMOUNT->FldCaption());
		if (strval($this->AMOUNT->EditValue) <> "" && is_numeric($this->AMOUNT->EditValue)) $this->AMOUNT->EditValue = ew_FormatNumber($this->AMOUNT->EditValue, -2, -1, -2, 0);

		// UPLOAD_FILE_ID
		$this->UPLOAD_FILE_ID->EditAttrs["class"] = "form-control";
		$this->UPLOAD_FILE_ID->EditCustomAttributes = "";
		$this->UPLOAD_FILE_ID->EditValue = $this->UPLOAD_FILE_ID->CurrentValue;
		$this->UPLOAD_FILE_ID->PlaceHolder = ew_RemoveHtml($this->UPLOAD_FILE_ID->FldCaption());

		// UPLOAD_FILE_DETAIL_STATUS_ID
		$this->UPLOAD_FILE_DETAIL_STATUS_ID->EditAttrs["class"] = "form-control";
		$this->UPLOAD_FILE_DETAIL_STATUS_ID->EditCustomAttributes = "";
		$this->UPLOAD_FILE_DETAIL_STATUS_ID->EditValue = $this->UPLOAD_FILE_DETAIL_STATUS_ID->CurrentValue;
		$this->UPLOAD_FILE_DETAIL_STATUS_ID->PlaceHolder = ew_RemoveHtml($this->UPLOAD_FILE_DETAIL_STATUS_ID->FldCaption());

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
					if ($this->UPLOAD_FILE_DETAIL_ID->Exportable) $Doc->ExportCaption($this->UPLOAD_FILE_DETAIL_ID);
					if ($this->CODE->Exportable) $Doc->ExportCaption($this->CODE);
					if ($this->DESCRIPTION->Exportable) $Doc->ExportCaption($this->DESCRIPTION);
					if ($this->DATE->Exportable) $Doc->ExportCaption($this->DATE);
					if ($this->AMOUNT->Exportable) $Doc->ExportCaption($this->AMOUNT);
					if ($this->UPLOAD_FILE_ID->Exportable) $Doc->ExportCaption($this->UPLOAD_FILE_ID);
					if ($this->UPLOAD_FILE_DETAIL_STATUS_ID->Exportable) $Doc->ExportCaption($this->UPLOAD_FILE_DETAIL_STATUS_ID);
				} else {
					if ($this->UPLOAD_FILE_DETAIL_ID->Exportable) $Doc->ExportCaption($this->UPLOAD_FILE_DETAIL_ID);
					if ($this->CODE->Exportable) $Doc->ExportCaption($this->CODE);
					if ($this->DESCRIPTION->Exportable) $Doc->ExportCaption($this->DESCRIPTION);
					if ($this->DATE->Exportable) $Doc->ExportCaption($this->DATE);
					if ($this->AMOUNT->Exportable) $Doc->ExportCaption($this->AMOUNT);
					if ($this->UPLOAD_FILE_ID->Exportable) $Doc->ExportCaption($this->UPLOAD_FILE_ID);
					if ($this->UPLOAD_FILE_DETAIL_STATUS_ID->Exportable) $Doc->ExportCaption($this->UPLOAD_FILE_DETAIL_STATUS_ID);
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
						if ($this->UPLOAD_FILE_DETAIL_ID->Exportable) $Doc->ExportField($this->UPLOAD_FILE_DETAIL_ID);
						if ($this->CODE->Exportable) $Doc->ExportField($this->CODE);
						if ($this->DESCRIPTION->Exportable) $Doc->ExportField($this->DESCRIPTION);
						if ($this->DATE->Exportable) $Doc->ExportField($this->DATE);
						if ($this->AMOUNT->Exportable) $Doc->ExportField($this->AMOUNT);
						if ($this->UPLOAD_FILE_ID->Exportable) $Doc->ExportField($this->UPLOAD_FILE_ID);
						if ($this->UPLOAD_FILE_DETAIL_STATUS_ID->Exportable) $Doc->ExportField($this->UPLOAD_FILE_DETAIL_STATUS_ID);
					} else {
						if ($this->UPLOAD_FILE_DETAIL_ID->Exportable) $Doc->ExportField($this->UPLOAD_FILE_DETAIL_ID);
						if ($this->CODE->Exportable) $Doc->ExportField($this->CODE);
						if ($this->DESCRIPTION->Exportable) $Doc->ExportField($this->DESCRIPTION);
						if ($this->DATE->Exportable) $Doc->ExportField($this->DATE);
						if ($this->AMOUNT->Exportable) $Doc->ExportField($this->AMOUNT);
						if ($this->UPLOAD_FILE_ID->Exportable) $Doc->ExportField($this->UPLOAD_FILE_ID);
						if ($this->UPLOAD_FILE_DETAIL_STATUS_ID->Exportable) $Doc->ExportField($this->UPLOAD_FILE_DETAIL_STATUS_ID);
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
