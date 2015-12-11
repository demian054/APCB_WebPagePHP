<?php

// Global variable for table object
$card = NULL;

//
// Table class for card
//
class ccard extends cTable {
	var $CARD_ID;
	var $NAME_IN_CARD;
	var $NUMBER;
	var $CARD_TYPE_ID;
	var $USER_ID;
	var $BANK_ID;
	var $VALID_THRU_MONTH;
	var $VALID_THRU_YEAR;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'card';
		$this->TableName = 'card';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`card`";
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

		// CARD_ID
		$this->CARD_ID = new cField('card', 'card', 'x_CARD_ID', 'CARD_ID', '`CARD_ID`', '`CARD_ID`', 3, -1, FALSE, '`CARD_ID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->CARD_ID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['CARD_ID'] = &$this->CARD_ID;

		// NAME_IN_CARD
		$this->NAME_IN_CARD = new cField('card', 'card', 'x_NAME_IN_CARD', 'NAME_IN_CARD', '`NAME_IN_CARD`', '`NAME_IN_CARD`', 200, -1, FALSE, '`NAME_IN_CARD`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['NAME_IN_CARD'] = &$this->NAME_IN_CARD;

		// NUMBER
		$this->NUMBER = new cField('card', 'card', 'x_NUMBER', 'NUMBER', '`NUMBER`', '`NUMBER`', 131, -1, FALSE, '`NUMBER`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->NUMBER->FldDefaultErrMsg = $Language->Phrase("IncorrectCreditCard");
		$this->fields['NUMBER'] = &$this->NUMBER;

		// CARD_TYPE_ID
		$this->CARD_TYPE_ID = new cField('card', 'card', 'x_CARD_TYPE_ID', 'CARD_TYPE_ID', '`CARD_TYPE_ID`', '`CARD_TYPE_ID`', 3, -1, FALSE, '`CARD_TYPE_ID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->CARD_TYPE_ID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['CARD_TYPE_ID'] = &$this->CARD_TYPE_ID;

		// USER_ID
		$this->USER_ID = new cField('card', 'card', 'x_USER_ID', 'USER_ID', '`USER_ID`', '`USER_ID`', 3, -1, FALSE, '`USER_ID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->USER_ID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['USER_ID'] = &$this->USER_ID;

		// BANK_ID
		$this->BANK_ID = new cField('card', 'card', 'x_BANK_ID', 'BANK_ID', '`BANK_ID`', '`BANK_ID`', 3, -1, FALSE, '`BANK_ID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->BANK_ID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['BANK_ID'] = &$this->BANK_ID;

		// VALID_THRU_MONTH
		$this->VALID_THRU_MONTH = new cField('card', 'card', 'x_VALID_THRU_MONTH', 'VALID_THRU_MONTH', '`VALID_THRU_MONTH`', '`VALID_THRU_MONTH`', 3, -1, FALSE, '`VALID_THRU_MONTH`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->VALID_THRU_MONTH->FldDefaultErrMsg = str_replace(array("%1", "%2"), array("1", "12"), $Language->Phrase("IncorrectRange"));
		$this->fields['VALID_THRU_MONTH'] = &$this->VALID_THRU_MONTH;

		// VALID_THRU_YEAR
		$this->VALID_THRU_YEAR = new cField('card', 'card', 'x_VALID_THRU_YEAR', 'VALID_THRU_YEAR', '`VALID_THRU_YEAR`', '`VALID_THRU_YEAR`', 3, -1, FALSE, '`VALID_THRU_YEAR`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->VALID_THRU_YEAR->FldDefaultErrMsg = str_replace(array("%1", "%2"), array("15", "50"), $Language->Phrase("IncorrectRange"));
		$this->fields['VALID_THRU_YEAR'] = &$this->VALID_THRU_YEAR;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`card`";
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
		$this->TableFilter = CurrentUserID();
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
		global $Security;

		// Add User ID filter
		if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
			$sFilter = $this->AddUserIDFilter($sFilter);
		}
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = $this->UserIDAllowSecurity;
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
			if (array_key_exists('CARD_ID', $rs))
				ew_AddFilter($where, ew_QuotedName('CARD_ID', $this->DBID) . '=' . ew_QuotedValue($rs['CARD_ID'], $this->CARD_ID->FldDataType, $this->DBID));
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
		return "`CARD_ID` = @CARD_ID@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->CARD_ID->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@CARD_ID@", ew_AdjustSql($this->CARD_ID->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "cardlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "cardlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("cardview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("cardview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "cardadd.php?" . $this->UrlParm($parm);
		else
			$url = "cardadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("cardedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("cardadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("carddelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "CARD_ID:" . ew_VarToJson($this->CARD_ID->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->CARD_ID->CurrentValue)) {
			$sUrl .= "CARD_ID=" . urlencode($this->CARD_ID->CurrentValue);
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
			if ($isPost && isset($_POST["CARD_ID"]))
				$arKeys[] = ew_StripSlashes($_POST["CARD_ID"]);
			elseif (isset($_GET["CARD_ID"]))
				$arKeys[] = ew_StripSlashes($_GET["CARD_ID"]);
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
			$this->CARD_ID->CurrentValue = $key;
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
		$this->CARD_ID->setDbValue($rs->fields('CARD_ID'));
		$this->NAME_IN_CARD->setDbValue($rs->fields('NAME_IN_CARD'));
		$this->NUMBER->setDbValue($rs->fields('NUMBER'));
		$this->CARD_TYPE_ID->setDbValue($rs->fields('CARD_TYPE_ID'));
		$this->USER_ID->setDbValue($rs->fields('USER_ID'));
		$this->BANK_ID->setDbValue($rs->fields('BANK_ID'));
		$this->VALID_THRU_MONTH->setDbValue($rs->fields('VALID_THRU_MONTH'));
		$this->VALID_THRU_YEAR->setDbValue($rs->fields('VALID_THRU_YEAR'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// CARD_ID
		// NAME_IN_CARD
		// NUMBER
		// CARD_TYPE_ID
		// USER_ID

		$this->USER_ID->CellCssStyle = "white-space: nowrap;";

		// BANK_ID
		// VALID_THRU_MONTH
		// VALID_THRU_YEAR
		// CARD_ID

		$this->CARD_ID->ViewValue = $this->CARD_ID->CurrentValue;
		$this->CARD_ID->ViewCustomAttributes = "";

		// NAME_IN_CARD
		$this->NAME_IN_CARD->ViewValue = $this->NAME_IN_CARD->CurrentValue;
		$this->NAME_IN_CARD->ViewCustomAttributes = "";

		// NUMBER
		$this->NUMBER->ViewValue = $this->NUMBER->CurrentValue;
		$this->NUMBER->ViewCustomAttributes = "";

		// CARD_TYPE_ID
		if (strval($this->CARD_TYPE_ID->CurrentValue) <> "") {
			$sFilterWrk = "`CARD_TYPE_ID`" . ew_SearchString("=", $this->CARD_TYPE_ID->CurrentValue, EW_DATATYPE_NUMBER, "");
		switch (@$gsLanguage) {
			case "es":
				$sSqlWrk = "SELECT `CARD_TYPE_ID`, `DESCRIPTION` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `card_type`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `CARD_TYPE_ID`, `DESCRIPTION` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `card_type`";
				$sWhereWrk = "";
				break;
		}
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->CARD_TYPE_ID, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->CARD_TYPE_ID->ViewValue = $this->CARD_TYPE_ID->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->CARD_TYPE_ID->ViewValue = $this->CARD_TYPE_ID->CurrentValue;
			}
		} else {
			$this->CARD_TYPE_ID->ViewValue = NULL;
		}
		$this->CARD_TYPE_ID->ViewCustomAttributes = "";

		// USER_ID
		$this->USER_ID->ViewValue = $this->USER_ID->CurrentValue;
		$this->USER_ID->ViewCustomAttributes = "";

		// BANK_ID
		if (strval($this->BANK_ID->CurrentValue) <> "") {
			$sFilterWrk = "`BANK_ID`" . ew_SearchString("=", $this->BANK_ID->CurrentValue, EW_DATATYPE_NUMBER, "");
		switch (@$gsLanguage) {
			case "es":
				$sSqlWrk = "SELECT `BANK_ID`, `DESCRIPTION` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `bank`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `BANK_ID`, `DESCRIPTION` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `bank`";
				$sWhereWrk = "";
				break;
		}
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->BANK_ID, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->BANK_ID->ViewValue = $this->BANK_ID->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->BANK_ID->ViewValue = $this->BANK_ID->CurrentValue;
			}
		} else {
			$this->BANK_ID->ViewValue = NULL;
		}
		$this->BANK_ID->ViewCustomAttributes = "";

		// VALID_THRU_MONTH
		$this->VALID_THRU_MONTH->ViewValue = $this->VALID_THRU_MONTH->CurrentValue;
		$this->VALID_THRU_MONTH->ViewCustomAttributes = "";

		// VALID_THRU_YEAR
		$this->VALID_THRU_YEAR->ViewValue = $this->VALID_THRU_YEAR->CurrentValue;
		$this->VALID_THRU_YEAR->ViewCustomAttributes = "";

		// CARD_ID
		$this->CARD_ID->LinkCustomAttributes = "";
		$this->CARD_ID->HrefValue = "";
		$this->CARD_ID->TooltipValue = "";

		// NAME_IN_CARD
		$this->NAME_IN_CARD->LinkCustomAttributes = "";
		$this->NAME_IN_CARD->HrefValue = "";
		$this->NAME_IN_CARD->TooltipValue = "";

		// NUMBER
		$this->NUMBER->LinkCustomAttributes = "";
		$this->NUMBER->HrefValue = "";
		$this->NUMBER->TooltipValue = "";

		// CARD_TYPE_ID
		$this->CARD_TYPE_ID->LinkCustomAttributes = "";
		$this->CARD_TYPE_ID->HrefValue = "";
		$this->CARD_TYPE_ID->TooltipValue = "";

		// USER_ID
		$this->USER_ID->LinkCustomAttributes = "";
		$this->USER_ID->HrefValue = "";
		$this->USER_ID->TooltipValue = "";

		// BANK_ID
		$this->BANK_ID->LinkCustomAttributes = "";
		$this->BANK_ID->HrefValue = "";
		$this->BANK_ID->TooltipValue = "";

		// VALID_THRU_MONTH
		$this->VALID_THRU_MONTH->LinkCustomAttributes = "";
		$this->VALID_THRU_MONTH->HrefValue = "";
		$this->VALID_THRU_MONTH->TooltipValue = "";

		// VALID_THRU_YEAR
		$this->VALID_THRU_YEAR->LinkCustomAttributes = "";
		$this->VALID_THRU_YEAR->HrefValue = "";
		$this->VALID_THRU_YEAR->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// CARD_ID
		$this->CARD_ID->EditAttrs["class"] = "form-control";
		$this->CARD_ID->EditCustomAttributes = "";
		$this->CARD_ID->EditValue = $this->CARD_ID->CurrentValue;
		$this->CARD_ID->ViewCustomAttributes = "";

		// NAME_IN_CARD
		$this->NAME_IN_CARD->EditAttrs["class"] = "form-control";
		$this->NAME_IN_CARD->EditCustomAttributes = "";
		$this->NAME_IN_CARD->EditValue = $this->NAME_IN_CARD->CurrentValue;
		$this->NAME_IN_CARD->PlaceHolder = ew_RemoveHtml($this->NAME_IN_CARD->FldCaption());

		// NUMBER
		$this->NUMBER->EditAttrs["class"] = "form-control";
		$this->NUMBER->EditCustomAttributes = "";
		$this->NUMBER->EditValue = $this->NUMBER->CurrentValue;
		$this->NUMBER->PlaceHolder = ew_RemoveHtml($this->NUMBER->FldCaption());
		if (strval($this->NUMBER->EditValue) <> "" && is_numeric($this->NUMBER->EditValue)) $this->NUMBER->EditValue = ew_FormatNumber($this->NUMBER->EditValue, -2, -1, -2, 0);

		// CARD_TYPE_ID
		$this->CARD_TYPE_ID->EditAttrs["class"] = "form-control";
		$this->CARD_TYPE_ID->EditCustomAttributes = "";

		// USER_ID
		$this->USER_ID->EditAttrs["class"] = "form-control";
		$this->USER_ID->EditCustomAttributes = "";
		if (!$Security->IsAdmin() && $Security->IsLoggedIn() && !$this->UserIDAllow("info")) { // Non system admin
			$this->USER_ID->CurrentValue = CurrentUserID();
		$this->USER_ID->EditValue = $this->USER_ID->CurrentValue;
		$this->USER_ID->ViewCustomAttributes = "";
		} else {
		$this->USER_ID->EditValue = $this->USER_ID->CurrentValue;
		$this->USER_ID->PlaceHolder = ew_RemoveHtml($this->USER_ID->FldCaption());
		}

		// BANK_ID
		$this->BANK_ID->EditAttrs["class"] = "form-control";
		$this->BANK_ID->EditCustomAttributes = "";

		// VALID_THRU_MONTH
		$this->VALID_THRU_MONTH->EditAttrs["class"] = "form-control";
		$this->VALID_THRU_MONTH->EditCustomAttributes = "";
		$this->VALID_THRU_MONTH->EditValue = $this->VALID_THRU_MONTH->CurrentValue;
		$this->VALID_THRU_MONTH->PlaceHolder = ew_RemoveHtml($this->VALID_THRU_MONTH->FldCaption());

		// VALID_THRU_YEAR
		$this->VALID_THRU_YEAR->EditAttrs["class"] = "form-control";
		$this->VALID_THRU_YEAR->EditCustomAttributes = "";
		$this->VALID_THRU_YEAR->EditValue = $this->VALID_THRU_YEAR->CurrentValue;
		$this->VALID_THRU_YEAR->PlaceHolder = ew_RemoveHtml($this->VALID_THRU_YEAR->FldCaption());

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
					if ($this->CARD_ID->Exportable) $Doc->ExportCaption($this->CARD_ID);
					if ($this->NAME_IN_CARD->Exportable) $Doc->ExportCaption($this->NAME_IN_CARD);
					if ($this->NUMBER->Exportable) $Doc->ExportCaption($this->NUMBER);
					if ($this->CARD_TYPE_ID->Exportable) $Doc->ExportCaption($this->CARD_TYPE_ID);
					if ($this->BANK_ID->Exportable) $Doc->ExportCaption($this->BANK_ID);
					if ($this->VALID_THRU_MONTH->Exportable) $Doc->ExportCaption($this->VALID_THRU_MONTH);
					if ($this->VALID_THRU_YEAR->Exportable) $Doc->ExportCaption($this->VALID_THRU_YEAR);
				} else {
					if ($this->NAME_IN_CARD->Exportable) $Doc->ExportCaption($this->NAME_IN_CARD);
					if ($this->NUMBER->Exportable) $Doc->ExportCaption($this->NUMBER);
					if ($this->CARD_TYPE_ID->Exportable) $Doc->ExportCaption($this->CARD_TYPE_ID);
					if ($this->BANK_ID->Exportable) $Doc->ExportCaption($this->BANK_ID);
					if ($this->VALID_THRU_MONTH->Exportable) $Doc->ExportCaption($this->VALID_THRU_MONTH);
					if ($this->VALID_THRU_YEAR->Exportable) $Doc->ExportCaption($this->VALID_THRU_YEAR);
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
						if ($this->CARD_ID->Exportable) $Doc->ExportField($this->CARD_ID);
						if ($this->NAME_IN_CARD->Exportable) $Doc->ExportField($this->NAME_IN_CARD);
						if ($this->NUMBER->Exportable) $Doc->ExportField($this->NUMBER);
						if ($this->CARD_TYPE_ID->Exportable) $Doc->ExportField($this->CARD_TYPE_ID);
						if ($this->BANK_ID->Exportable) $Doc->ExportField($this->BANK_ID);
						if ($this->VALID_THRU_MONTH->Exportable) $Doc->ExportField($this->VALID_THRU_MONTH);
						if ($this->VALID_THRU_YEAR->Exportable) $Doc->ExportField($this->VALID_THRU_YEAR);
					} else {
						if ($this->NAME_IN_CARD->Exportable) $Doc->ExportField($this->NAME_IN_CARD);
						if ($this->NUMBER->Exportable) $Doc->ExportField($this->NUMBER);
						if ($this->CARD_TYPE_ID->Exportable) $Doc->ExportField($this->CARD_TYPE_ID);
						if ($this->BANK_ID->Exportable) $Doc->ExportField($this->BANK_ID);
						if ($this->VALID_THRU_MONTH->Exportable) $Doc->ExportField($this->VALID_THRU_MONTH);
						if ($this->VALID_THRU_YEAR->Exportable) $Doc->ExportField($this->VALID_THRU_YEAR);
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

	// Add User ID filter
	function AddUserIDFilter($sFilter) {
		global $Security;
		$sFilterWrk = "";
		$id = (CurrentPageID() == "list") ? $this->CurrentAction : CurrentPageID();
		if (!$this->UserIDAllow($id) && !$Security->IsAdmin()) {
			$sFilterWrk = $Security->UserIDList();
			if ($sFilterWrk <> "")
				$sFilterWrk = '`USER_ID` IN (' . $sFilterWrk . ')';
		}

		// Call User ID Filtering event
		$this->UserID_Filtering($sFilterWrk);
		ew_AddFilter($sFilter, $sFilterWrk);
		return $sFilter;
	}

	// User ID subquery
	function GetUserIDSubquery(&$fld, &$masterfld) {
		global $UserTableConn;
		$sWrk = "";
		$sSql = "SELECT " . $masterfld->FldExpression . " FROM `card`";
		$sFilter = $this->AddUserIDFilter("");
		if ($sFilter <> "") $sSql .= " WHERE " . $sFilter;

		// Use subquery
		if (EW_USE_SUBQUERY_FOR_MASTER_USER_ID) {
			$sWrk = $sSql;
		} else {

			// List all values
			if ($rs = $UserTableConn->Execute($sSql)) {
				while (!$rs->EOF) {
					if ($sWrk <> "") $sWrk .= ",";
					$sWrk .= ew_QuotedValue($rs->fields[0], $masterfld->FldDataType, EW_USER_TABLE_DBID);
					$rs->MoveNext();
				}
				$rs->Close();
			}
		}
		if ($sWrk <> "") {
			$sWrk = $fld->FldExpression . " IN (" . $sWrk . ")";
		}
		return $sWrk;
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
