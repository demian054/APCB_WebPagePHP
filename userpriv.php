<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "user_levelsinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$userpriv = NULL; // Initialize page object first

class cuserpriv extends cuser_levels {

	// Page ID
	var $PageID = 'userpriv';

	// Project ID
	var $ProjectID = "{B2F6402D-7C0A-4760-82AD-045088CEBA18}";

	// Page object name
	var $PageObjName = 'userpriv';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
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
		return TRUE;
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

		// Table object (user_levels)
		if (!isset($GLOBALS["user_levels"]) || get_class($GLOBALS["user_levels"]) == "cuser_levels") {
			$GLOBALS["user_levels"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["user_levels"];
		}
		if (!isset($GLOBALS["user_levels"])) $GLOBALS["user_levels"] = &$this;

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'userpriv', TRUE);

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
		$Security->LoadCurrentUserLevel(CurrentProjectID() . 'user_levels');
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanAdmin()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
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
	var $TempPriv;
	var $Disabled;
	var $Privileges;
	var $TableNameCount;
	var $ReportLanguage;
	var $UserLevelList = array();
	var $UserLevelPrivList = array();
	var $TableList = array();

	//
	// Page main
	//
	function Page_Main() {
		global $Security, $Language;
		global $EW_RELATED_LANGUAGE_FOLDER;
		global $Breadcrumb;
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb = new cBreadcrumb;
		$Breadcrumb->Add("list", "user_levels", "user_levelslist.php", "", "user_levels");
		$Breadcrumb->Add("userpriv", "UserLevelPermission", $url);

		// Try to load PHP Report Maker language file
		// Note: The langauge IDs must be the same in both projects

		$Security->LoadUserLevelFromConfigFile($this->UserLevelList, $this->UserLevelPrivList, $this->TableList, TRUE);
		if ($EW_RELATED_LANGUAGE_FOLDER <> "")
			$this->ReportLanguage = new cLanguage($EW_RELATED_LANGUAGE_FOLDER);
		$this->TableNameCount = count($this->TableList);
		$this->Privileges = &ew_InitArray($this->TableNameCount, 0);

		// Get action
		if (@$_POST["a_edit"] == "") {
			$this->CurrentAction = "I"; // Display with input box

			// Load key from QueryString
			if (@$_GET["USER_LEVEL_ID"] <> "") {
				$this->USER_LEVEL_ID->setQueryStringValue($_GET["USER_LEVEL_ID"]);
			} else {
				$this->Page_Terminate("user_levelslist.php"); // Return to list
			}
			if ($this->USER_LEVEL_ID->QueryStringValue == "-1") {
				$this->Disabled = " disabled=\"disabled\"";
			} else {
				$this->Disabled = "";
			}
		} else {
			$this->CurrentAction = $_POST["a_edit"];

			// Get fields from form
			$this->USER_LEVEL_ID->setFormValue($_POST["x_USER_LEVEL_ID"]);
			for ($i = 0; $i < $this->TableNameCount; $i++) {
				if (defined("EW_USER_LEVEL_COMPAT")) {
					$this->Privileges[$i] = intval(@$_POST["Add_" . $i]) + 
						intval(@$_POST["Delete_" . $i]) + intval(@$_POST["Edit_" . $i]) +
						intval(@$_POST["List_" . $i]);
				} else {
					$this->Privileges[$i] = intval(@$_POST["Add_" . $i]) +
						intval(@$_POST["Delete_" . $i]) + intval(@$_POST["Edit_" . $i]) +
						intval(@$_POST["List_" . $i]) + intval(@$_POST["View_" . $i]) +
						intval(@$_POST["Search_" . $i]);
				}
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Display
				if (!$Security->SetUpUserLevelEx()) // Get all User Level info
					$this->Page_Terminate("user_levelslist.php"); // Return to list
				break;
			case "U": // Update
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up update success message

					// Alternatively, comment out the following line to go back to this page
					$this->Page_Terminate("user_levelslist.php"); // Return to list
				}
		}
	}

	// Update privileges
	function EditRow() {
		$c = &Conn(EW_USER_LEVEL_PRIV_DBID);
		for ($i = 0; $i < $this->TableNameCount; $i++) {
			$Sql = "SELECT * FROM " . EW_USER_LEVEL_PRIV_TABLE . " WHERE " . 
				EW_USER_LEVEL_PRIV_TABLE_NAME_FIELD . " = '" . ew_AdjustSql($this->TableList[$i][4] . $this->TableList[$i][0], EW_USER_LEVEL_PRIV_DBID) . "' AND " .
				EW_USER_LEVEL_PRIV_USER_LEVEL_ID_FIELD . " = " . $this->USER_LEVEL_ID->CurrentValue;
			$rs = $c->Execute($Sql);
			if ($rs && !$rs->EOF) {
				$Sql = "UPDATE " . EW_USER_LEVEL_PRIV_TABLE . " SET " . EW_USER_LEVEL_PRIV_PRIV_FIELD . " = " . $this->Privileges[$i] . " WHERE " .
					EW_USER_LEVEL_PRIV_TABLE_NAME_FIELD . " = '" . ew_AdjustSql($this->TableList[$i][4] . $this->TableList[$i][0], EW_USER_LEVEL_PRIV_DBID) . "' AND " .
					EW_USER_LEVEL_PRIV_USER_LEVEL_ID_FIELD . " = " . $this->USER_LEVEL_ID->CurrentValue;
				$c->Execute($Sql);
			} else {
				$Sql = "INSERT INTO " . EW_USER_LEVEL_PRIV_TABLE . " (" . EW_USER_LEVEL_PRIV_TABLE_NAME_FIELD . ", " . EW_USER_LEVEL_PRIV_USER_LEVEL_ID_FIELD . ", " . EW_USER_LEVEL_PRIV_PRIV_FIELD . ") VALUES ('" . ew_AdjustSql($this->TableList[$i][4] . $this->TableList[$i][0], EW_USER_LEVEL_PRIV_DBID) . "', " . $this->USER_LEVEL_ID->CurrentValue . ", " . $this->Privileges[$i] . ")";
				$c->Execute($Sql);
			}
			if ($rs)
				$rs->Close();
		}
		return TRUE;
	}

	// Get table caption
	function GetTableCaption($i) {
		global $Language, $EW_RELATED_PROJECT_ID;
		$caption = "";
		if ($i < $this->TableNameCount) {
			$report = ($this->TableList[$i][4] == $EW_RELATED_PROJECT_ID);
			$other = (!$report && $this->TableList[$i][4] <> CurrentProjectID());
			if (!$report && !$other)
				$caption = $Language->TablePhrase($this->TableList[$i][1], "TblCaption");
            if ($report && is_object($this->ReportLanguage))
				$caption = $this->ReportLanguage->TablePhrase($this->TableList[$i][1], "TblCaption");
			if ($caption == "")
				$caption = $this->TableList[$i][2];
			if ($caption == "") {
				$caption = $this->TableList[$i][0];
				$caption = preg_replace('/^\{\w{8}-\w{4}-\w{4}-\w{4}-\w{12}\}/', '', $caption); // Remove project id
			}
			if ($report)
				$caption .= "<span class=\"ewUserprivProject\"> (" . $Language->Phrase("Report") . ")</span>";
			if ($other) {
				if ($this->TableList[$i][5] <> "") {
					$pathinfo = pathinfo($this->TableList[$i][5]);
					$ext = $pathinfo['extension'];
					$project = basename($this->TableList[$i][5], "." . $ext);
				} else {
					$project = $this->TableList[$i][4];
				}

				//$project = $this->TableList[$i][4]; // ** Uncomment to use project id
				$caption .= "<span class=\"ewUserprivProject\"> (" . $project . ")</span>";
			}
		}
		return $caption;
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
	// $type = ''|'success'|'failure'
	function Message_Showing(&$msg, $type) {

		// Example:
		//if ($type == 'success') $msg = "your success message";

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
if (!isset($userpriv)) $userpriv = new cuserpriv();

// Page init
$userpriv->Page_Init();

// Page main
$userpriv->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$userpriv->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "userpriv";
var CurrentForm = fuserpriv = new ew_Form("fuserpriv", "userpriv");

// Form_CustomValidate event
fuserpriv.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fuserpriv.ValidateRequired = true;
<?php } else { ?>
fuserpriv.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<p><?php echo $Language->Phrase("UserLevel") ?><?php echo $Security->GetUserLevelName($user_levels->USER_LEVEL_ID->CurrentValue) ?>(<?php echo $user_levels->USER_LEVEL_ID->CurrentValue ?>)</p>
<?php
$userpriv->ShowMessage();
?>
<script type="text/javascript">
var fuserpriv = new ew_Form("fuserpriv");
</script>
<form name="fuserpriv" id="fuserpriv" class="form-inline ewForm ewUserprivForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($userpriv->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $userpriv->Token ?>">
<?php } ?>
<div class="ewDesktop">
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<input type="hidden" name="t" value="user_levels">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<!-- hidden tag for User Level ID -->
<input type="hidden" name="x_USER_LEVEL_ID" id="x_USER_LEVEL_ID" value="<?php echo $user_levels->USER_LEVEL_ID->CurrentValue ?>">
<table id="tbl_userpriv" class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
		<th><?php echo $Language->Phrase("TableOrView") ?></th>
		<th><?php echo $Language->Phrase("PermissionAddCopy") ?> <input type="checkbox" class="ewPriv" name="Add" id="Add" onclick="ew_SelectAll(this);"<?php echo $userpriv->Disabled ?>></th>
		<th><?php echo $Language->Phrase("PermissionDelete") ?> <input type="checkbox" class="ewPriv" name="Delete" id="Delete" onclick="ew_SelectAll(this);"<?php echo $userpriv->Disabled ?>></th>
		<th><?php echo $Language->Phrase("PermissionEdit") ?> <input type="checkbox" class="ewPriv" name="Edit" id="Edit" onclick="ew_SelectAll(this);"<?php echo $userpriv->Disabled ?>></th>
<?php if (defined("EW_USER_LEVEL_COMPAT")) { ?>
		<th><?php echo $Language->Phrase("PermissionListSearchView") ?> <input type="checkbox" class="ewPriv" name="List" id="List" onclick="ew_SelectAll(this);"<?php echo $userpriv->Disabled ?>></th>
<?php } else { ?>
		<th><?php echo $Language->Phrase("PermissionList") ?> <input type="checkbox" class="ewPriv" name="List" id="List" onclick="ew_SelectAll(this);"<?php echo $userpriv->Disabled ?>></th>
		<th><?php echo $Language->Phrase("PermissionView") ?> <input type="checkbox" class="ewPriv" name="View" id="View" onclick="ew_SelectAll(this);"<?php echo $userpriv->Disabled ?>></th>
		<th><?php echo $Language->Phrase("PermissionSearch") ?> <input type="checkbox" class="ewPriv" name="Search" id="Search" onclick="ew_SelectAll(this);"<?php echo $userpriv->Disabled ?>></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
for ($i = 0; $i < $userpriv->TableNameCount; $i++) {
	$userpriv->TempPriv = $Security->GetUserLevelPrivEx($userpriv->TableList[$i][4] . $userpriv->TableList[$i][0], $user_levels->USER_LEVEL_ID->CurrentValue);

		// Set row properties
		$user_levels->ResetAttrs();
?>
	<tr<?php echo $user_levels->RowAttributes() ?>>
		<td><?php echo $userpriv->GetTableCaption($i) ?></td>
		<td class="ewCheckbox"><input type="checkbox" class="ewPriv" name="Add_<?php echo $i ?>" id="Add_<?php echo $i ?>" value="1"<?php if (($userpriv->TempPriv & EW_ALLOW_ADD) == EW_ALLOW_ADD) { ?> checked="checked"<?php } ?><?php echo $userpriv->Disabled ?>></td>
		<td class="ewCheckbox"><input type="checkbox" class="ewPriv" name="Delete_<?php echo $i ?>" id="Delete_<?php echo $i ?>" value="2"<?php if (($userpriv->TempPriv & EW_ALLOW_DELETE) == EW_ALLOW_DELETE) { ?> checked="checked"<?php } ?><?php echo $userpriv->Disabled ?>></td>
		<td class="ewCheckbox"><input type="checkbox" class="ewPriv" name="Edit_<?php echo $i ?>" id="Edit_<?php echo $i ?>" value="4"<?php if (($userpriv->TempPriv & EW_ALLOW_EDIT) == EW_ALLOW_EDIT) { ?> checked="checked"<?php } ?><?php echo $userpriv->Disabled ?>></td>
<?php if (defined("EW_USER_LEVEL_COMPAT")) { ?>
		<td class="ewCheckbox"><input type="checkbox" class="ewPriv" name="List_<?php echo $i ?>" id="List_<?php echo $i ?>" value="8"<?php if (($userpriv->TempPriv & EW_ALLOW_LIST) == EW_ALLOW_LIST) { ?> checked="checked"<?php } ?><?php echo $userpriv->Disabled ?>></td>
<?php } else { ?>
		<td class="ewCheckbox"><input type="checkbox" class="ewPriv" name="List_<?php echo $i ?>" id="List_<?php echo $i ?>" value="8"<?php if (($userpriv->TempPriv & EW_ALLOW_LIST) == EW_ALLOW_LIST) { ?> checked="checked"<?php } ?><?php echo $userpriv->Disabled ?>></td>
		<td class="ewCheckbox"><input type="checkbox" class="ewPriv" name="View_<?php echo $i ?>" id="View_<?php echo $i ?>" value="32"<?php if (($userpriv->TempPriv & EW_ALLOW_VIEW) == EW_ALLOW_VIEW) { ?> checked="checked"<?php } ?><?php echo $userpriv->Disabled ?>></td>
		<td class="ewCheckbox"><input type="checkbox" class="ewPriv" name="Search_<?php echo $i ?>" id="Search_<?php echo $i ?>" value="64"<?php if (($userpriv->TempPriv & EW_ALLOW_SEARCH) == EW_ALLOW_SEARCH) { ?> checked="checked"<?php } ?><?php echo $userpriv->Disabled ?>></td>
<?php } ?>
	</tr>
<?php } ?>
	</tbody>
</table>
</div>
</div>
<div class="ewDesktopButton">
<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"<?php echo $userpriv->Disabled ?>><?php echo $Language->Phrase("Update") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $userpriv->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</div>
</form>
<script type="text/javascript">
fuserpriv.Init();
</script>
<script type="text/javascript">

// Write your startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$userpriv->Page_Terminate();
?>
