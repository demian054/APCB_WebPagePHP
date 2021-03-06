<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "cardinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$card_delete = NULL; // Initialize page object first

class ccard_delete extends ccard {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{B2F6402D-7C0A-4760-82AD-045088CEBA18}";

	// Table name
	var $TableName = 'card';

	// Page object name
	var $PageObjName = 'card_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = TRUE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (card)
		if (!isset($GLOBALS["card"]) || get_class($GLOBALS["card"]) == "ccard") {
			$GLOBALS["card"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["card"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'card', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (user)
		if (!isset($UserTable)) {
			$UserTable = new cuser();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("cardlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
			if (strval($Security->CurrentUserID()) == "") {
				$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
				$this->Page_Terminate(ew_GetUrl("cardlist.php"));
			}
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $card;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($card);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("cardlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in card class, cardinfo.php

		$this->CurrentFilter = $sFilter;

		// Check if valid user id
		$conn = &$this->Connection();
		$sql = $this->GetSQL($this->CurrentFilter, "");
		if ($this->Recordset = ew_LoadRecordset($sql, $conn)) {
			$res = TRUE;
			while (!$this->Recordset->EOF) {
				$this->LoadRowValues($this->Recordset);
				if (!$this->ShowOptionLink('delete')) {
					$sUserIdMsg = $Language->Phrase("NoDeletePermission");
					$this->setFailureMessage($sUserIdMsg);
					$res = FALSE;
					break;
				}
				$this->Recordset->MoveNext();
			}
			$this->Recordset->Close();
			if (!$res) $this->Page_Terminate("cardlist.php"); // Return to list
		}

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->CARD_ID->setDbValue($rs->fields('CARD_ID'));
		$this->NAME_IN_CARD->setDbValue($rs->fields('NAME_IN_CARD'));
		$this->NUMBER->setDbValue($rs->fields('NUMBER'));
		$this->CARD_TYPE_ID->setDbValue($rs->fields('CARD_TYPE_ID'));
		$this->USER_ID->setDbValue($rs->fields('USER_ID'));
		$this->BANK_ID->setDbValue($rs->fields('BANK_ID'));
		$this->VALID_THRU_MONTH->setDbValue($rs->fields('VALID_THRU_MONTH'));
		$this->VALID_THRU_YEAR->setDbValue($rs->fields('VALID_THRU_YEAR'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->CARD_ID->DbValue = $row['CARD_ID'];
		$this->NAME_IN_CARD->DbValue = $row['NAME_IN_CARD'];
		$this->NUMBER->DbValue = $row['NUMBER'];
		$this->CARD_TYPE_ID->DbValue = $row['CARD_TYPE_ID'];
		$this->USER_ID->DbValue = $row['USER_ID'];
		$this->BANK_ID->DbValue = $row['BANK_ID'];
		$this->VALID_THRU_MONTH->DbValue = $row['VALID_THRU_MONTH'];
		$this->VALID_THRU_YEAR->DbValue = $row['VALID_THRU_YEAR'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->NUMBER->FormValue == $this->NUMBER->CurrentValue && is_numeric(ew_StrToFloat($this->NUMBER->CurrentValue)))
			$this->NUMBER->CurrentValue = ew_StrToFloat($this->NUMBER->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// CARD_ID
		// NAME_IN_CARD
		// NUMBER
		// CARD_TYPE_ID
		// USER_ID

		$this->USER_ID->CellCssStyle = "white-space: nowrap;";

		// BANK_ID
		// VALID_THRU_MONTH
		// VALID_THRU_YEAR

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['CARD_ID'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Show link optionally based on User ID
	function ShowOptionLink($id = "") {
		global $Security;
		if ($Security->IsLoggedIn() && !$Security->IsAdmin() && !$this->UserIDAllow($id))
			return $Security->IsValidUserID($this->USER_ID->CurrentValue);
		return TRUE;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("cardlist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(TRUE) ?>
<?php

// Create page object
if (!isset($card_delete)) $card_delete = new ccard_delete();

// Page init
$card_delete->Page_Init();

// Page main
$card_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$card_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fcarddelete = new ew_Form("fcarddelete", "delete");

// Form_CustomValidate event
fcarddelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcarddelete.ValidateRequired = true;
<?php } else { ?>
fcarddelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcarddelete.Lists["x_CARD_TYPE_ID"] = {"LinkField":"x_CARD_TYPE_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_DESCRIPTION","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcarddelete.Lists["x_BANK_ID"] = {"LinkField":"x_BANK_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_DESCRIPTION","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($card_delete->Recordset = $card_delete->LoadRecordset())
	$card_deleteTotalRecs = $card_delete->Recordset->RecordCount(); // Get record count
if ($card_deleteTotalRecs <= 0) { // No record found, exit
	if ($card_delete->Recordset)
		$card_delete->Recordset->Close();
	$card_delete->Page_Terminate("cardlist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $card_delete->ShowPageHeader(); ?>
<?php
$card_delete->ShowMessage();
?>
<form name="fcarddelete" id="fcarddelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($card_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $card_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="card">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($card_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $card->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($card->NAME_IN_CARD->Visible) { // NAME_IN_CARD ?>
		<th><span id="elh_card_NAME_IN_CARD" class="card_NAME_IN_CARD"><?php echo $card->NAME_IN_CARD->FldCaption() ?></span></th>
<?php } ?>
<?php if ($card->NUMBER->Visible) { // NUMBER ?>
		<th><span id="elh_card_NUMBER" class="card_NUMBER"><?php echo $card->NUMBER->FldCaption() ?></span></th>
<?php } ?>
<?php if ($card->CARD_TYPE_ID->Visible) { // CARD_TYPE_ID ?>
		<th><span id="elh_card_CARD_TYPE_ID" class="card_CARD_TYPE_ID"><?php echo $card->CARD_TYPE_ID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($card->BANK_ID->Visible) { // BANK_ID ?>
		<th><span id="elh_card_BANK_ID" class="card_BANK_ID"><?php echo $card->BANK_ID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($card->VALID_THRU_MONTH->Visible) { // VALID_THRU_MONTH ?>
		<th><span id="elh_card_VALID_THRU_MONTH" class="card_VALID_THRU_MONTH"><?php echo $card->VALID_THRU_MONTH->FldCaption() ?></span></th>
<?php } ?>
<?php if ($card->VALID_THRU_YEAR->Visible) { // VALID_THRU_YEAR ?>
		<th><span id="elh_card_VALID_THRU_YEAR" class="card_VALID_THRU_YEAR"><?php echo $card->VALID_THRU_YEAR->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$card_delete->RecCnt = 0;
$i = 0;
while (!$card_delete->Recordset->EOF) {
	$card_delete->RecCnt++;
	$card_delete->RowCnt++;

	// Set row properties
	$card->ResetAttrs();
	$card->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$card_delete->LoadRowValues($card_delete->Recordset);

	// Render row
	$card_delete->RenderRow();
?>
	<tr<?php echo $card->RowAttributes() ?>>
<?php if ($card->NAME_IN_CARD->Visible) { // NAME_IN_CARD ?>
		<td<?php echo $card->NAME_IN_CARD->CellAttributes() ?>>
<span id="el<?php echo $card_delete->RowCnt ?>_card_NAME_IN_CARD" class="card_NAME_IN_CARD">
<span<?php echo $card->NAME_IN_CARD->ViewAttributes() ?>>
<?php echo $card->NAME_IN_CARD->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($card->NUMBER->Visible) { // NUMBER ?>
		<td<?php echo $card->NUMBER->CellAttributes() ?>>
<span id="el<?php echo $card_delete->RowCnt ?>_card_NUMBER" class="card_NUMBER">
<span<?php echo $card->NUMBER->ViewAttributes() ?>>
<?php echo $card->NUMBER->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($card->CARD_TYPE_ID->Visible) { // CARD_TYPE_ID ?>
		<td<?php echo $card->CARD_TYPE_ID->CellAttributes() ?>>
<span id="el<?php echo $card_delete->RowCnt ?>_card_CARD_TYPE_ID" class="card_CARD_TYPE_ID">
<span<?php echo $card->CARD_TYPE_ID->ViewAttributes() ?>>
<?php echo $card->CARD_TYPE_ID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($card->BANK_ID->Visible) { // BANK_ID ?>
		<td<?php echo $card->BANK_ID->CellAttributes() ?>>
<span id="el<?php echo $card_delete->RowCnt ?>_card_BANK_ID" class="card_BANK_ID">
<span<?php echo $card->BANK_ID->ViewAttributes() ?>>
<?php echo $card->BANK_ID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($card->VALID_THRU_MONTH->Visible) { // VALID_THRU_MONTH ?>
		<td<?php echo $card->VALID_THRU_MONTH->CellAttributes() ?>>
<span id="el<?php echo $card_delete->RowCnt ?>_card_VALID_THRU_MONTH" class="card_VALID_THRU_MONTH">
<span<?php echo $card->VALID_THRU_MONTH->ViewAttributes() ?>>
<?php echo $card->VALID_THRU_MONTH->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($card->VALID_THRU_YEAR->Visible) { // VALID_THRU_YEAR ?>
		<td<?php echo $card->VALID_THRU_YEAR->CellAttributes() ?>>
<span id="el<?php echo $card_delete->RowCnt ?>_card_VALID_THRU_YEAR" class="card_VALID_THRU_YEAR">
<span<?php echo $card->VALID_THRU_YEAR->ViewAttributes() ?>>
<?php echo $card->VALID_THRU_YEAR->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$card_delete->Recordset->MoveNext();
}
$card_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $card_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fcarddelete.Init();
</script>
<?php
$card_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$card_delete->Page_Terminate();
?>
