<?php

// Global variable for table object
$user = NULL;

//
// Table class for user
//
class cuser extends cTable {
	var $USER_ID;
	var $CODE;
	var $PASS;
	var $FIRSTNAME;
	var $SECONDNAME;
	var $LASTNAME;
	var $SURNAME;
	var $MAIL;
	var $USER_ACT;
	var $USER_LEVEL_ID;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'user';
		$this->TableName = 'user';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`user`";
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

		// USER_ID
		$this->USER_ID = new cField('user', 'user', 'x_USER_ID', 'USER_ID', '`USER_ID`', '`USER_ID`', 3, -1, FALSE, '`USER_ID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->USER_ID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['USER_ID'] = &$this->USER_ID;

		// CODE
		$this->CODE = new cField('user', 'user', 'x_CODE', 'CODE', '`CODE`', '`CODE`', 200, -1, FALSE, '`CODE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['CODE'] = &$this->CODE;

		// PASS
		$this->PASS = new cField('user', 'user', 'x_PASS', 'PASS', '`PASS`', '`PASS`', 200, -1, FALSE, '`PASS`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['PASS'] = &$this->PASS;

		// FIRSTNAME
		$this->FIRSTNAME = new cField('user', 'user', 'x_FIRSTNAME', 'FIRSTNAME', '`FIRSTNAME`', '`FIRSTNAME`', 200, -1, FALSE, '`FIRSTNAME`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['FIRSTNAME'] = &$this->FIRSTNAME;

		// SECONDNAME
		$this->SECONDNAME = new cField('user', 'user', 'x_SECONDNAME', 'SECONDNAME', '`SECONDNAME`', '`SECONDNAME`', 200, -1, FALSE, '`SECONDNAME`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['SECONDNAME'] = &$this->SECONDNAME;

		// LASTNAME
		$this->LASTNAME = new cField('user', 'user', 'x_LASTNAME', 'LASTNAME', '`LASTNAME`', '`LASTNAME`', 200, -1, FALSE, '`LASTNAME`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['LASTNAME'] = &$this->LASTNAME;

		// SURNAME
		$this->SURNAME = new cField('user', 'user', 'x_SURNAME', 'SURNAME', '`SURNAME`', '`SURNAME`', 200, -1, FALSE, '`SURNAME`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['SURNAME'] = &$this->SURNAME;

		// MAIL
		$this->MAIL = new cField('user', 'user', 'x_MAIL', 'MAIL', '`MAIL`', '`MAIL`', 200, -1, FALSE, '`MAIL`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['MAIL'] = &$this->MAIL;

		// USER_ACT
		$this->USER_ACT = new cField('user', 'user', 'x_USER_ACT', 'USER_ACT', '`USER_ACT`', '`USER_ACT`', 16, -1, FALSE, '`USER_ACT`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->USER_ACT->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['USER_ACT'] = &$this->USER_ACT;

		// USER_LEVEL_ID
		$this->USER_LEVEL_ID = new cField('user', 'user', 'x_USER_LEVEL_ID', 'USER_LEVEL_ID', '`USER_LEVEL_ID`', '`USER_LEVEL_ID`', 3, -1, FALSE, '`USER_LEVEL_ID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->USER_LEVEL_ID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['USER_LEVEL_ID'] = &$this->USER_LEVEL_ID;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`user`";
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
			if (EW_ENCRYPTED_PASSWORD && $name == 'PASS')
				$value = (EW_CASE_SENSITIVE_PASSWORD) ? ew_EncryptPassword($value) : ew_EncryptPassword(strtolower($value));
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
			if (EW_ENCRYPTED_PASSWORD && $name == 'PASS') {
				$value = (EW_CASE_SENSITIVE_PASSWORD) ? ew_EncryptPassword($value) : ew_EncryptPassword(strtolower($value));
			}
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
			if (array_key_exists('USER_ID', $rs))
				ew_AddFilter($where, ew_QuotedName('USER_ID', $this->DBID) . '=' . ew_QuotedValue($rs['USER_ID'], $this->USER_ID->FldDataType, $this->DBID));
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
		return "`USER_ID` = @USER_ID@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->USER_ID->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@USER_ID@", ew_AdjustSql($this->USER_ID->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "userlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "userlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("userview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("userview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "useradd.php?" . $this->UrlParm($parm);
		else
			$url = "useradd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("useredit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("useradd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("userdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "USER_ID:" . ew_VarToJson($this->USER_ID->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->USER_ID->CurrentValue)) {
			$sUrl .= "USER_ID=" . urlencode($this->USER_ID->CurrentValue);
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
			if ($isPost && isset($_POST["USER_ID"]))
				$arKeys[] = ew_StripSlashes($_POST["USER_ID"]);
			elseif (isset($_GET["USER_ID"]))
				$arKeys[] = ew_StripSlashes($_GET["USER_ID"]);
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
			$this->USER_ID->CurrentValue = $key;
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
		$this->USER_ID->setDbValue($rs->fields('USER_ID'));
		$this->CODE->setDbValue($rs->fields('CODE'));
		$this->PASS->setDbValue($rs->fields('PASS'));
		$this->FIRSTNAME->setDbValue($rs->fields('FIRSTNAME'));
		$this->SECONDNAME->setDbValue($rs->fields('SECONDNAME'));
		$this->LASTNAME->setDbValue($rs->fields('LASTNAME'));
		$this->SURNAME->setDbValue($rs->fields('SURNAME'));
		$this->MAIL->setDbValue($rs->fields('MAIL'));
		$this->USER_ACT->setDbValue($rs->fields('USER_ACT'));
		$this->USER_LEVEL_ID->setDbValue($rs->fields('USER_LEVEL_ID'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// USER_ID

		$this->USER_ID->CellCssStyle = "white-space: nowrap;";

		// CODE
		$this->CODE->CellCssStyle = "white-space: nowrap;";

		// PASS
		$this->PASS->CellCssStyle = "white-space: nowrap;";

		// FIRSTNAME
		// SECONDNAME
		// LASTNAME
		// SURNAME
		// MAIL
		// USER_ACT

		$this->USER_ACT->CellCssStyle = "white-space: nowrap;";

		// USER_LEVEL_ID
		$this->USER_LEVEL_ID->CellCssStyle = "white-space: nowrap;";

		// USER_ID
		$this->USER_ID->ViewValue = $this->USER_ID->CurrentValue;
		$this->USER_ID->ViewCustomAttributes = "";

		// CODE
		$this->CODE->ViewValue = $this->CODE->CurrentValue;
		$this->CODE->ViewCustomAttributes = "";

		// PASS
		$this->PASS->ViewValue = $this->PASS->CurrentValue;
		$this->PASS->ViewCustomAttributes = "";

		// FIRSTNAME
		$this->FIRSTNAME->ViewValue = $this->FIRSTNAME->CurrentValue;
		$this->FIRSTNAME->ViewCustomAttributes = "";

		// SECONDNAME
		$this->SECONDNAME->ViewValue = $this->SECONDNAME->CurrentValue;
		$this->SECONDNAME->ViewCustomAttributes = "";

		// LASTNAME
		$this->LASTNAME->ViewValue = $this->LASTNAME->CurrentValue;
		$this->LASTNAME->ViewCustomAttributes = "";

		// SURNAME
		$this->SURNAME->ViewValue = $this->SURNAME->CurrentValue;
		$this->SURNAME->ViewCustomAttributes = "";

		// MAIL
		$this->MAIL->ViewValue = $this->MAIL->CurrentValue;
		$this->MAIL->ViewCustomAttributes = "";

		// USER_ACT
		$this->USER_ACT->ViewValue = $this->USER_ACT->CurrentValue;
		$this->USER_ACT->ViewCustomAttributes = "";

		// USER_LEVEL_ID
		if ($Security->CanAdmin()) { // System admin
		if (strval($this->USER_LEVEL_ID->CurrentValue) <> "") {
			$sFilterWrk = "`USER_LEVEL_ID`" . ew_SearchString("=", $this->USER_LEVEL_ID->CurrentValue, EW_DATATYPE_NUMBER, "");
		switch (@$gsLanguage) {
			case "es":
				$sSqlWrk = "SELECT `USER_LEVEL_ID`, `DESCRIPTION` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `user_levels`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `USER_LEVEL_ID`, `DESCRIPTION` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `user_levels`";
				$sWhereWrk = "";
				break;
		}
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->USER_LEVEL_ID, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->USER_LEVEL_ID->ViewValue = $this->USER_LEVEL_ID->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->USER_LEVEL_ID->ViewValue = $this->USER_LEVEL_ID->CurrentValue;
			}
		} else {
			$this->USER_LEVEL_ID->ViewValue = NULL;
		}
		} else {
			$this->USER_LEVEL_ID->ViewValue = $Language->Phrase("PasswordMask");
		}
		$this->USER_LEVEL_ID->ViewCustomAttributes = "";

		// USER_ID
		$this->USER_ID->LinkCustomAttributes = "";
		$this->USER_ID->HrefValue = "";
		$this->USER_ID->TooltipValue = "";

		// CODE
		$this->CODE->LinkCustomAttributes = "";
		$this->CODE->HrefValue = "";
		$this->CODE->TooltipValue = "";

		// PASS
		$this->PASS->LinkCustomAttributes = "";
		$this->PASS->HrefValue = "";
		$this->PASS->TooltipValue = "";

		// FIRSTNAME
		$this->FIRSTNAME->LinkCustomAttributes = "";
		$this->FIRSTNAME->HrefValue = "";
		$this->FIRSTNAME->TooltipValue = "";

		// SECONDNAME
		$this->SECONDNAME->LinkCustomAttributes = "";
		$this->SECONDNAME->HrefValue = "";
		$this->SECONDNAME->TooltipValue = "";

		// LASTNAME
		$this->LASTNAME->LinkCustomAttributes = "";
		$this->LASTNAME->HrefValue = "";
		$this->LASTNAME->TooltipValue = "";

		// SURNAME
		$this->SURNAME->LinkCustomAttributes = "";
		$this->SURNAME->HrefValue = "";
		$this->SURNAME->TooltipValue = "";

		// MAIL
		$this->MAIL->LinkCustomAttributes = "";
		$this->MAIL->HrefValue = "";
		$this->MAIL->TooltipValue = "";

		// USER_ACT
		$this->USER_ACT->LinkCustomAttributes = "";
		$this->USER_ACT->HrefValue = "";
		$this->USER_ACT->TooltipValue = "";

		// USER_LEVEL_ID
		$this->USER_LEVEL_ID->LinkCustomAttributes = "";
		$this->USER_LEVEL_ID->HrefValue = "";
		$this->USER_LEVEL_ID->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// USER_ID
		$this->USER_ID->EditAttrs["class"] = "form-control";
		$this->USER_ID->EditCustomAttributes = "";
		$this->USER_ID->EditValue = $this->USER_ID->CurrentValue;
		$this->USER_ID->ViewCustomAttributes = "";

		// CODE
		$this->CODE->EditAttrs["class"] = "form-control";
		$this->CODE->EditCustomAttributes = "";
		$this->CODE->EditValue = $this->CODE->CurrentValue;
		$this->CODE->PlaceHolder = ew_RemoveHtml($this->CODE->FldCaption());

		// PASS
		$this->PASS->EditAttrs["class"] = "form-control ewPasswordStrength";
		$this->PASS->EditCustomAttributes = "";
		$this->PASS->EditValue = $this->PASS->CurrentValue;
		$this->PASS->PlaceHolder = ew_RemoveHtml($this->PASS->FldCaption());

		// FIRSTNAME
		$this->FIRSTNAME->EditAttrs["class"] = "form-control";
		$this->FIRSTNAME->EditCustomAttributes = "";
		$this->FIRSTNAME->EditValue = $this->FIRSTNAME->CurrentValue;
		$this->FIRSTNAME->PlaceHolder = ew_RemoveHtml($this->FIRSTNAME->FldCaption());

		// SECONDNAME
		$this->SECONDNAME->EditAttrs["class"] = "form-control";
		$this->SECONDNAME->EditCustomAttributes = "";
		$this->SECONDNAME->EditValue = $this->SECONDNAME->CurrentValue;
		$this->SECONDNAME->PlaceHolder = ew_RemoveHtml($this->SECONDNAME->FldCaption());

		// LASTNAME
		$this->LASTNAME->EditAttrs["class"] = "form-control";
		$this->LASTNAME->EditCustomAttributes = "";
		$this->LASTNAME->EditValue = $this->LASTNAME->CurrentValue;
		$this->LASTNAME->PlaceHolder = ew_RemoveHtml($this->LASTNAME->FldCaption());

		// SURNAME
		$this->SURNAME->EditAttrs["class"] = "form-control";
		$this->SURNAME->EditCustomAttributes = "";
		$this->SURNAME->EditValue = $this->SURNAME->CurrentValue;
		$this->SURNAME->PlaceHolder = ew_RemoveHtml($this->SURNAME->FldCaption());

		// MAIL
		$this->MAIL->EditAttrs["class"] = "form-control";
		$this->MAIL->EditCustomAttributes = "";
		$this->MAIL->EditValue = $this->MAIL->CurrentValue;
		$this->MAIL->PlaceHolder = ew_RemoveHtml($this->MAIL->FldCaption());

		// USER_ACT
		$this->USER_ACT->EditAttrs["class"] = "form-control";
		$this->USER_ACT->EditCustomAttributes = "";
		$this->USER_ACT->EditValue = $this->USER_ACT->CurrentValue;
		$this->USER_ACT->PlaceHolder = ew_RemoveHtml($this->USER_ACT->FldCaption());

		// USER_LEVEL_ID
		$this->USER_LEVEL_ID->EditAttrs["class"] = "form-control";
		$this->USER_LEVEL_ID->EditCustomAttributes = "";
		if (!$Security->CanAdmin()) { // System admin
			$this->USER_LEVEL_ID->EditValue = $Language->Phrase("PasswordMask");
		} else {
		}

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
					if ($this->FIRSTNAME->Exportable) $Doc->ExportCaption($this->FIRSTNAME);
					if ($this->SECONDNAME->Exportable) $Doc->ExportCaption($this->SECONDNAME);
					if ($this->LASTNAME->Exportable) $Doc->ExportCaption($this->LASTNAME);
					if ($this->SURNAME->Exportable) $Doc->ExportCaption($this->SURNAME);
					if ($this->MAIL->Exportable) $Doc->ExportCaption($this->MAIL);
				} else {
					if ($this->FIRSTNAME->Exportable) $Doc->ExportCaption($this->FIRSTNAME);
					if ($this->SECONDNAME->Exportable) $Doc->ExportCaption($this->SECONDNAME);
					if ($this->LASTNAME->Exportable) $Doc->ExportCaption($this->LASTNAME);
					if ($this->SURNAME->Exportable) $Doc->ExportCaption($this->SURNAME);
					if ($this->MAIL->Exportable) $Doc->ExportCaption($this->MAIL);
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
						if ($this->FIRSTNAME->Exportable) $Doc->ExportField($this->FIRSTNAME);
						if ($this->SECONDNAME->Exportable) $Doc->ExportField($this->SECONDNAME);
						if ($this->LASTNAME->Exportable) $Doc->ExportField($this->LASTNAME);
						if ($this->SURNAME->Exportable) $Doc->ExportField($this->SURNAME);
						if ($this->MAIL->Exportable) $Doc->ExportField($this->MAIL);
					} else {
						if ($this->FIRSTNAME->Exportable) $Doc->ExportField($this->FIRSTNAME);
						if ($this->SECONDNAME->Exportable) $Doc->ExportField($this->SECONDNAME);
						if ($this->LASTNAME->Exportable) $Doc->ExportField($this->LASTNAME);
						if ($this->SURNAME->Exportable) $Doc->ExportField($this->SURNAME);
						if ($this->MAIL->Exportable) $Doc->ExportField($this->MAIL);
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

	// User ID filter
	function UserIDFilter($userid) {
		$sUserIDFilter = '`USER_ID` = ' . ew_QuotedValue($userid, EW_DATATYPE_NUMBER, EW_USER_TABLE_DBID);
		return $sUserIDFilter;
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
		$sSql = "SELECT " . $masterfld->FldExpression . " FROM `user`";
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
