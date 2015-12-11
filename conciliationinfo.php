<?php

// Global variable for table object
$conciliation = NULL;

//
// Table class for conciliation
//
class cconciliation extends cTable {
	var $CONCILIATION_ID;
	var $UPLOAD_FILE_DETAIL_ID;
	var $DATETIME;
	var $CARD_ID;
	var $TRANSFERENCE_ID;
	var $PAY_TYPE_ID;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'conciliation';
		$this->TableName = 'conciliation';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`conciliation`";
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

		// CONCILIATION_ID
		$this->CONCILIATION_ID = new cField('conciliation', 'conciliation', 'x_CONCILIATION_ID', 'CONCILIATION_ID', '`CONCILIATION_ID`', '`CONCILIATION_ID`', 3, -1, FALSE, '`CONCILIATION_ID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->CONCILIATION_ID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['CONCILIATION_ID'] = &$this->CONCILIATION_ID;

		// UPLOAD_FILE_DETAIL_ID
		$this->UPLOAD_FILE_DETAIL_ID = new cField('conciliation', 'conciliation', 'x_UPLOAD_FILE_DETAIL_ID', 'UPLOAD_FILE_DETAIL_ID', '`UPLOAD_FILE_DETAIL_ID`', '`UPLOAD_FILE_DETAIL_ID`', 3, -1, FALSE, '`UPLOAD_FILE_DETAIL_ID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->UPLOAD_FILE_DETAIL_ID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['UPLOAD_FILE_DETAIL_ID'] = &$this->UPLOAD_FILE_DETAIL_ID;

		// DATETIME
		$this->DATETIME = new cField('conciliation', 'conciliation', 'x_DATETIME', 'DATETIME', '`DATETIME`', 'DATE_FORMAT(`DATETIME`, \'%d/%m/%Y\')', 135, 7, FALSE, '`DATETIME`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->DATETIME->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['DATETIME'] = &$this->DATETIME;

		// CARD_ID
		$this->CARD_ID = new cField('conciliation', 'conciliation', 'x_CARD_ID', 'CARD_ID', '`CARD_ID`', '`CARD_ID`', 3, -1, FALSE, '`CARD_ID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->CARD_ID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['CARD_ID'] = &$this->CARD_ID;

		// TRANSFERENCE_ID
		$this->TRANSFERENCE_ID = new cField('conciliation', 'conciliation', 'x_TRANSFERENCE_ID', 'TRANSFERENCE_ID', '`TRANSFERENCE_ID`', '`TRANSFERENCE_ID`', 3, -1, FALSE, '`TRANSFERENCE_ID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->TRANSFERENCE_ID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['TRANSFERENCE_ID'] = &$this->TRANSFERENCE_ID;

		// PAY_TYPE_ID
		$this->PAY_TYPE_ID = new cField('conciliation', 'conciliation', 'x_PAY_TYPE_ID', 'PAY_TYPE_ID', '`PAY_TYPE_ID`', '`PAY_TYPE_ID`', 3, -1, FALSE, '`PAY_TYPE_ID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->PAY_TYPE_ID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['PAY_TYPE_ID'] = &$this->PAY_TYPE_ID;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`conciliation`";
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
			if (array_key_exists('CONCILIATION_ID', $rs))
				ew_AddFilter($where, ew_QuotedName('CONCILIATION_ID', $this->DBID) . '=' . ew_QuotedValue($rs['CONCILIATION_ID'], $this->CONCILIATION_ID->FldDataType, $this->DBID));
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
		return "`CONCILIATION_ID` = @CONCILIATION_ID@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->CONCILIATION_ID->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@CONCILIATION_ID@", ew_AdjustSql($this->CONCILIATION_ID->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "conciliationlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "conciliationlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("conciliationview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("conciliationview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "conciliationadd.php?" . $this->UrlParm($parm);
		else
			$url = "conciliationadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("conciliationedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("conciliationadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("conciliationdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "CONCILIATION_ID:" . ew_VarToJson($this->CONCILIATION_ID->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->CONCILIATION_ID->CurrentValue)) {
			$sUrl .= "CONCILIATION_ID=" . urlencode($this->CONCILIATION_ID->CurrentValue);
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
			if ($isPost && isset($_POST["CONCILIATION_ID"]))
				$arKeys[] = ew_StripSlashes($_POST["CONCILIATION_ID"]);
			elseif (isset($_GET["CONCILIATION_ID"]))
				$arKeys[] = ew_StripSlashes($_GET["CONCILIATION_ID"]);
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
			$this->CONCILIATION_ID->CurrentValue = $key;
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
		$this->CONCILIATION_ID->setDbValue($rs->fields('CONCILIATION_ID'));
		$this->UPLOAD_FILE_DETAIL_ID->setDbValue($rs->fields('UPLOAD_FILE_DETAIL_ID'));
		$this->DATETIME->setDbValue($rs->fields('DATETIME'));
		$this->CARD_ID->setDbValue($rs->fields('CARD_ID'));
		$this->TRANSFERENCE_ID->setDbValue($rs->fields('TRANSFERENCE_ID'));
		$this->PAY_TYPE_ID->setDbValue($rs->fields('PAY_TYPE_ID'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// CONCILIATION_ID
		// UPLOAD_FILE_DETAIL_ID
		// DATETIME
		// CARD_ID
		// TRANSFERENCE_ID
		// PAY_TYPE_ID
		// CONCILIATION_ID

		$this->CONCILIATION_ID->ViewValue = $this->CONCILIATION_ID->CurrentValue;
		$this->CONCILIATION_ID->ViewCustomAttributes = "";

		// UPLOAD_FILE_DETAIL_ID
		$this->UPLOAD_FILE_DETAIL_ID->ViewValue = $this->UPLOAD_FILE_DETAIL_ID->CurrentValue;
		$this->UPLOAD_FILE_DETAIL_ID->ViewCustomAttributes = "";

		// DATETIME
		$this->DATETIME->ViewValue = $this->DATETIME->CurrentValue;
		$this->DATETIME->ViewValue = ew_FormatDateTime($this->DATETIME->ViewValue, 7);
		$this->DATETIME->ViewCustomAttributes = "";

		// CARD_ID
		$this->CARD_ID->ViewValue = $this->CARD_ID->CurrentValue;
		$this->CARD_ID->ViewCustomAttributes = "";

		// TRANSFERENCE_ID
		$this->TRANSFERENCE_ID->ViewValue = $this->TRANSFERENCE_ID->CurrentValue;
		$this->TRANSFERENCE_ID->ViewCustomAttributes = "";

		// PAY_TYPE_ID
		$this->PAY_TYPE_ID->ViewValue = $this->PAY_TYPE_ID->CurrentValue;
		$this->PAY_TYPE_ID->ViewCustomAttributes = "";

		// CONCILIATION_ID
		$this->CONCILIATION_ID->LinkCustomAttributes = "";
		$this->CONCILIATION_ID->HrefValue = "";
		$this->CONCILIATION_ID->TooltipValue = "";

		// UPLOAD_FILE_DETAIL_ID
		$this->UPLOAD_FILE_DETAIL_ID->LinkCustomAttributes = "";
		$this->UPLOAD_FILE_DETAIL_ID->HrefValue = "";
		$this->UPLOAD_FILE_DETAIL_ID->TooltipValue = "";

		// DATETIME
		$this->DATETIME->LinkCustomAttributes = "";
		$this->DATETIME->HrefValue = "";
		$this->DATETIME->TooltipValue = "";

		// CARD_ID
		$this->CARD_ID->LinkCustomAttributes = "";
		$this->CARD_ID->HrefValue = "";
		$this->CARD_ID->TooltipValue = "";

		// TRANSFERENCE_ID
		$this->TRANSFERENCE_ID->LinkCustomAttributes = "";
		$this->TRANSFERENCE_ID->HrefValue = "";
		$this->TRANSFERENCE_ID->TooltipValue = "";

		// PAY_TYPE_ID
		$this->PAY_TYPE_ID->LinkCustomAttributes = "";
		$this->PAY_TYPE_ID->HrefValue = "";
		$this->PAY_TYPE_ID->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// CONCILIATION_ID
		$this->CONCILIATION_ID->EditAttrs["class"] = "form-control";
		$this->CONCILIATION_ID->EditCustomAttributes = "";
		$this->CONCILIATION_ID->EditValue = $this->CONCILIATION_ID->CurrentValue;
		$this->CONCILIATION_ID->ViewCustomAttributes = "";

		// UPLOAD_FILE_DETAIL_ID
		$this->UPLOAD_FILE_DETAIL_ID->EditAttrs["class"] = "form-control";
		$this->UPLOAD_FILE_DETAIL_ID->EditCustomAttributes = "";
		$this->UPLOAD_FILE_DETAIL_ID->EditValue = $this->UPLOAD_FILE_DETAIL_ID->CurrentValue;
		$this->UPLOAD_FILE_DETAIL_ID->PlaceHolder = ew_RemoveHtml($this->UPLOAD_FILE_DETAIL_ID->FldCaption());

		// DATETIME
		$this->DATETIME->EditAttrs["class"] = "form-control";
		$this->DATETIME->EditCustomAttributes = "";
		$this->DATETIME->EditValue = ew_FormatDateTime($this->DATETIME->CurrentValue, 7);
		$this->DATETIME->PlaceHolder = ew_RemoveHtml($this->DATETIME->FldCaption());

		// CARD_ID
		$this->CARD_ID->EditAttrs["class"] = "form-control";
		$this->CARD_ID->EditCustomAttributes = "";
		$this->CARD_ID->EditValue = $this->CARD_ID->CurrentValue;
		$this->CARD_ID->PlaceHolder = ew_RemoveHtml($this->CARD_ID->FldCaption());

		// TRANSFERENCE_ID
		$this->TRANSFERENCE_ID->EditAttrs["class"] = "form-control";
		$this->TRANSFERENCE_ID->EditCustomAttributes = "";
		$this->TRANSFERENCE_ID->EditValue = $this->TRANSFERENCE_ID->CurrentValue;
		$this->TRANSFERENCE_ID->PlaceHolder = ew_RemoveHtml($this->TRANSFERENCE_ID->FldCaption());

		// PAY_TYPE_ID
		$this->PAY_TYPE_ID->EditAttrs["class"] = "form-control";
		$this->PAY_TYPE_ID->EditCustomAttributes = "";
		$this->PAY_TYPE_ID->EditValue = $this->PAY_TYPE_ID->CurrentValue;
		$this->PAY_TYPE_ID->PlaceHolder = ew_RemoveHtml($this->PAY_TYPE_ID->FldCaption());

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
					if ($this->CONCILIATION_ID->Exportable) $Doc->ExportCaption($this->CONCILIATION_ID);
					if ($this->UPLOAD_FILE_DETAIL_ID->Exportable) $Doc->ExportCaption($this->UPLOAD_FILE_DETAIL_ID);
					if ($this->DATETIME->Exportable) $Doc->ExportCaption($this->DATETIME);
					if ($this->CARD_ID->Exportable) $Doc->ExportCaption($this->CARD_ID);
					if ($this->TRANSFERENCE_ID->Exportable) $Doc->ExportCaption($this->TRANSFERENCE_ID);
					if ($this->PAY_TYPE_ID->Exportable) $Doc->ExportCaption($this->PAY_TYPE_ID);
				} else {
					if ($this->CONCILIATION_ID->Exportable) $Doc->ExportCaption($this->CONCILIATION_ID);
					if ($this->UPLOAD_FILE_DETAIL_ID->Exportable) $Doc->ExportCaption($this->UPLOAD_FILE_DETAIL_ID);
					if ($this->DATETIME->Exportable) $Doc->ExportCaption($this->DATETIME);
					if ($this->CARD_ID->Exportable) $Doc->ExportCaption($this->CARD_ID);
					if ($this->TRANSFERENCE_ID->Exportable) $Doc->ExportCaption($this->TRANSFERENCE_ID);
					if ($this->PAY_TYPE_ID->Exportable) $Doc->ExportCaption($this->PAY_TYPE_ID);
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
						if ($this->CONCILIATION_ID->Exportable) $Doc->ExportField($this->CONCILIATION_ID);
						if ($this->UPLOAD_FILE_DETAIL_ID->Exportable) $Doc->ExportField($this->UPLOAD_FILE_DETAIL_ID);
						if ($this->DATETIME->Exportable) $Doc->ExportField($this->DATETIME);
						if ($this->CARD_ID->Exportable) $Doc->ExportField($this->CARD_ID);
						if ($this->TRANSFERENCE_ID->Exportable) $Doc->ExportField($this->TRANSFERENCE_ID);
						if ($this->PAY_TYPE_ID->Exportable) $Doc->ExportField($this->PAY_TYPE_ID);
					} else {
						if ($this->CONCILIATION_ID->Exportable) $Doc->ExportField($this->CONCILIATION_ID);
						if ($this->UPLOAD_FILE_DETAIL_ID->Exportable) $Doc->ExportField($this->UPLOAD_FILE_DETAIL_ID);
						if ($this->DATETIME->Exportable) $Doc->ExportField($this->DATETIME);
						if ($this->CARD_ID->Exportable) $Doc->ExportField($this->CARD_ID);
						if ($this->TRANSFERENCE_ID->Exportable) $Doc->ExportField($this->TRANSFERENCE_ID);
						if ($this->PAY_TYPE_ID->Exportable) $Doc->ExportField($this->PAY_TYPE_ID);
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
