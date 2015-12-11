<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "passangerinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$passanger_delete = NULL; // Initialize page object first

class cpassanger_delete extends cpassanger {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{B2F6402D-7C0A-4760-82AD-045088CEBA18}";

	// Table name
	var $TableName = 'passanger';

	// Page object name
	var $PageObjName = 'passanger_delete';

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

		// Table object (passanger)
		if (!isset($GLOBALS["passanger"]) || get_class($GLOBALS["passanger"]) == "cpassanger") {
			$GLOBALS["passanger"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["passanger"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'passanger', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("passangerlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->PASSANGER_ID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $passanger;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($passanger);
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
			$this->Page_Terminate("passangerlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in passanger class, passangerinfo.php

		$this->CurrentFilter = $sFilter;

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
		$this->PASSANGER_ID->setDbValue($rs->fields('PASSANGER_ID'));
		$this->CODE->setDbValue($rs->fields('CODE'));
		$this->FIRSTNAME->setDbValue($rs->fields('FIRSTNAME'));
		$this->SECONDNAME->setDbValue($rs->fields('SECONDNAME'));
		$this->LASTNAME->setDbValue($rs->fields('LASTNAME'));
		$this->SURNAME->setDbValue($rs->fields('SURNAME'));
		$this->MAIL->setDbValue($rs->fields('MAIL'));
		$this->PASSANGER_TYPE_ID->setDbValue($rs->fields('PASSANGER_TYPE_ID'));
		$this->USER_ID->setDbValue($rs->fields('USER_ID'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->PASSANGER_ID->DbValue = $row['PASSANGER_ID'];
		$this->CODE->DbValue = $row['CODE'];
		$this->FIRSTNAME->DbValue = $row['FIRSTNAME'];
		$this->SECONDNAME->DbValue = $row['SECONDNAME'];
		$this->LASTNAME->DbValue = $row['LASTNAME'];
		$this->SURNAME->DbValue = $row['SURNAME'];
		$this->MAIL->DbValue = $row['MAIL'];
		$this->PASSANGER_TYPE_ID->DbValue = $row['PASSANGER_TYPE_ID'];
		$this->USER_ID->DbValue = $row['USER_ID'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// PASSANGER_ID
		// CODE
		// FIRSTNAME
		// SECONDNAME
		// LASTNAME
		// SURNAME
		// MAIL
		// PASSANGER_TYPE_ID
		// USER_ID

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// PASSANGER_ID
		$this->PASSANGER_ID->ViewValue = $this->PASSANGER_ID->CurrentValue;
		$this->PASSANGER_ID->ViewCustomAttributes = "";

		// CODE
		$this->CODE->ViewValue = $this->CODE->CurrentValue;
		$this->CODE->ViewCustomAttributes = "";

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

		// PASSANGER_TYPE_ID
		$this->PASSANGER_TYPE_ID->ViewValue = $this->PASSANGER_TYPE_ID->CurrentValue;
		$this->PASSANGER_TYPE_ID->ViewCustomAttributes = "";

		// USER_ID
		$this->USER_ID->ViewValue = $this->USER_ID->CurrentValue;
		$this->USER_ID->ViewCustomAttributes = "";

			// PASSANGER_ID
			$this->PASSANGER_ID->LinkCustomAttributes = "";
			$this->PASSANGER_ID->HrefValue = "";
			$this->PASSANGER_ID->TooltipValue = "";

			// CODE
			$this->CODE->LinkCustomAttributes = "";
			$this->CODE->HrefValue = "";
			$this->CODE->TooltipValue = "";

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

			// PASSANGER_TYPE_ID
			$this->PASSANGER_TYPE_ID->LinkCustomAttributes = "";
			$this->PASSANGER_TYPE_ID->HrefValue = "";
			$this->PASSANGER_TYPE_ID->TooltipValue = "";

			// USER_ID
			$this->USER_ID->LinkCustomAttributes = "";
			$this->USER_ID->HrefValue = "";
			$this->USER_ID->TooltipValue = "";
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
				$sThisKey .= $row['PASSANGER_ID'];
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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("passangerlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($passanger_delete)) $passanger_delete = new cpassanger_delete();

// Page init
$passanger_delete->Page_Init();

// Page main
$passanger_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$passanger_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fpassangerdelete = new ew_Form("fpassangerdelete", "delete");

// Form_CustomValidate event
fpassangerdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpassangerdelete.ValidateRequired = true;
<?php } else { ?>
fpassangerdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($passanger_delete->Recordset = $passanger_delete->LoadRecordset())
	$passanger_deleteTotalRecs = $passanger_delete->Recordset->RecordCount(); // Get record count
if ($passanger_deleteTotalRecs <= 0) { // No record found, exit
	if ($passanger_delete->Recordset)
		$passanger_delete->Recordset->Close();
	$passanger_delete->Page_Terminate("passangerlist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $passanger_delete->ShowPageHeader(); ?>
<?php
$passanger_delete->ShowMessage();
?>
<form name="fpassangerdelete" id="fpassangerdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($passanger_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $passanger_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="passanger">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($passanger_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $passanger->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($passanger->PASSANGER_ID->Visible) { // PASSANGER_ID ?>
		<th><span id="elh_passanger_PASSANGER_ID" class="passanger_PASSANGER_ID"><?php echo $passanger->PASSANGER_ID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($passanger->CODE->Visible) { // CODE ?>
		<th><span id="elh_passanger_CODE" class="passanger_CODE"><?php echo $passanger->CODE->FldCaption() ?></span></th>
<?php } ?>
<?php if ($passanger->FIRSTNAME->Visible) { // FIRSTNAME ?>
		<th><span id="elh_passanger_FIRSTNAME" class="passanger_FIRSTNAME"><?php echo $passanger->FIRSTNAME->FldCaption() ?></span></th>
<?php } ?>
<?php if ($passanger->SECONDNAME->Visible) { // SECONDNAME ?>
		<th><span id="elh_passanger_SECONDNAME" class="passanger_SECONDNAME"><?php echo $passanger->SECONDNAME->FldCaption() ?></span></th>
<?php } ?>
<?php if ($passanger->LASTNAME->Visible) { // LASTNAME ?>
		<th><span id="elh_passanger_LASTNAME" class="passanger_LASTNAME"><?php echo $passanger->LASTNAME->FldCaption() ?></span></th>
<?php } ?>
<?php if ($passanger->SURNAME->Visible) { // SURNAME ?>
		<th><span id="elh_passanger_SURNAME" class="passanger_SURNAME"><?php echo $passanger->SURNAME->FldCaption() ?></span></th>
<?php } ?>
<?php if ($passanger->MAIL->Visible) { // MAIL ?>
		<th><span id="elh_passanger_MAIL" class="passanger_MAIL"><?php echo $passanger->MAIL->FldCaption() ?></span></th>
<?php } ?>
<?php if ($passanger->PASSANGER_TYPE_ID->Visible) { // PASSANGER_TYPE_ID ?>
		<th><span id="elh_passanger_PASSANGER_TYPE_ID" class="passanger_PASSANGER_TYPE_ID"><?php echo $passanger->PASSANGER_TYPE_ID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($passanger->USER_ID->Visible) { // USER_ID ?>
		<th><span id="elh_passanger_USER_ID" class="passanger_USER_ID"><?php echo $passanger->USER_ID->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$passanger_delete->RecCnt = 0;
$i = 0;
while (!$passanger_delete->Recordset->EOF) {
	$passanger_delete->RecCnt++;
	$passanger_delete->RowCnt++;

	// Set row properties
	$passanger->ResetAttrs();
	$passanger->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$passanger_delete->LoadRowValues($passanger_delete->Recordset);

	// Render row
	$passanger_delete->RenderRow();
?>
	<tr<?php echo $passanger->RowAttributes() ?>>
<?php if ($passanger->PASSANGER_ID->Visible) { // PASSANGER_ID ?>
		<td<?php echo $passanger->PASSANGER_ID->CellAttributes() ?>>
<span id="el<?php echo $passanger_delete->RowCnt ?>_passanger_PASSANGER_ID" class="passanger_PASSANGER_ID">
<span<?php echo $passanger->PASSANGER_ID->ViewAttributes() ?>>
<?php echo $passanger->PASSANGER_ID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($passanger->CODE->Visible) { // CODE ?>
		<td<?php echo $passanger->CODE->CellAttributes() ?>>
<span id="el<?php echo $passanger_delete->RowCnt ?>_passanger_CODE" class="passanger_CODE">
<span<?php echo $passanger->CODE->ViewAttributes() ?>>
<?php echo $passanger->CODE->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($passanger->FIRSTNAME->Visible) { // FIRSTNAME ?>
		<td<?php echo $passanger->FIRSTNAME->CellAttributes() ?>>
<span id="el<?php echo $passanger_delete->RowCnt ?>_passanger_FIRSTNAME" class="passanger_FIRSTNAME">
<span<?php echo $passanger->FIRSTNAME->ViewAttributes() ?>>
<?php echo $passanger->FIRSTNAME->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($passanger->SECONDNAME->Visible) { // SECONDNAME ?>
		<td<?php echo $passanger->SECONDNAME->CellAttributes() ?>>
<span id="el<?php echo $passanger_delete->RowCnt ?>_passanger_SECONDNAME" class="passanger_SECONDNAME">
<span<?php echo $passanger->SECONDNAME->ViewAttributes() ?>>
<?php echo $passanger->SECONDNAME->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($passanger->LASTNAME->Visible) { // LASTNAME ?>
		<td<?php echo $passanger->LASTNAME->CellAttributes() ?>>
<span id="el<?php echo $passanger_delete->RowCnt ?>_passanger_LASTNAME" class="passanger_LASTNAME">
<span<?php echo $passanger->LASTNAME->ViewAttributes() ?>>
<?php echo $passanger->LASTNAME->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($passanger->SURNAME->Visible) { // SURNAME ?>
		<td<?php echo $passanger->SURNAME->CellAttributes() ?>>
<span id="el<?php echo $passanger_delete->RowCnt ?>_passanger_SURNAME" class="passanger_SURNAME">
<span<?php echo $passanger->SURNAME->ViewAttributes() ?>>
<?php echo $passanger->SURNAME->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($passanger->MAIL->Visible) { // MAIL ?>
		<td<?php echo $passanger->MAIL->CellAttributes() ?>>
<span id="el<?php echo $passanger_delete->RowCnt ?>_passanger_MAIL" class="passanger_MAIL">
<span<?php echo $passanger->MAIL->ViewAttributes() ?>>
<?php echo $passanger->MAIL->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($passanger->PASSANGER_TYPE_ID->Visible) { // PASSANGER_TYPE_ID ?>
		<td<?php echo $passanger->PASSANGER_TYPE_ID->CellAttributes() ?>>
<span id="el<?php echo $passanger_delete->RowCnt ?>_passanger_PASSANGER_TYPE_ID" class="passanger_PASSANGER_TYPE_ID">
<span<?php echo $passanger->PASSANGER_TYPE_ID->ViewAttributes() ?>>
<?php echo $passanger->PASSANGER_TYPE_ID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($passanger->USER_ID->Visible) { // USER_ID ?>
		<td<?php echo $passanger->USER_ID->CellAttributes() ?>>
<span id="el<?php echo $passanger_delete->RowCnt ?>_passanger_USER_ID" class="passanger_USER_ID">
<span<?php echo $passanger->USER_ID->ViewAttributes() ?>>
<?php echo $passanger->USER_ID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$passanger_delete->Recordset->MoveNext();
}
$passanger_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $passanger_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fpassangerdelete.Init();
</script>
<?php
$passanger_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$passanger_delete->Page_Terminate();
?>
