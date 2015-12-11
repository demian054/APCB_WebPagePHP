<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$default = NULL; // Initialize page object first

class cdefault {

	// Page ID
	var $PageID = 'default';

	// Project ID
	var $ProjectID = "{B2F6402D-7C0A-4760-82AD-045088CEBA18}";

	// Page object name
	var $PageObjName = 'default';

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

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'default', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

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

	//
	// Page main
	//
	function Page_Main() {
		global $Security, $Language;
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		$Security->LoadUserLevel(); // Load User Level
		if ($Security->AllowList(CurrentProjectID() . 'PrincipalHome.php'))
		$this->Page_Terminate("PrincipalHome.php"); // Exit and go to default page
		if ($Security->AllowList(CurrentProjectID() . 'air_port'))
			$this->Page_Terminate("air_portlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'airplane'))
			$this->Page_Terminate("airplanelist.php");
		if ($Security->AllowList(CurrentProjectID() . 'audittrail'))
			$this->Page_Terminate("audittraillist.php");
		if ($Security->AllowList(CurrentProjectID() . 'baggage'))
			$this->Page_Terminate("baggagelist.php");
		if ($Security->AllowList(CurrentProjectID() . 'bank'))
			$this->Page_Terminate("banklist.php");
		if ($Security->AllowList(CurrentProjectID() . 'bank_account'))
			$this->Page_Terminate("bank_accountlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'boarding'))
			$this->Page_Terminate("boardinglist.php");
		if ($Security->AllowList(CurrentProjectID() . 'card'))
			$this->Page_Terminate("cardlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'card_type'))
			$this->Page_Terminate("card_typelist.php");
		if ($Security->AllowList(CurrentProjectID() . 'conciliation'))
			$this->Page_Terminate("conciliationlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'flight'))
			$this->Page_Terminate("flightlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'passanger'))
			$this->Page_Terminate("passangerlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'passanger_type'))
			$this->Page_Terminate("passanger_typelist.php");
		if ($Security->AllowList(CurrentProjectID() . 'pay_type'))
			$this->Page_Terminate("pay_typelist.php");
		if ($Security->AllowList(CurrentProjectID() . 'PrincipalContact.php'))
			$this->Page_Terminate("PrincipalContact.php");
		if ($Security->AllowList(CurrentProjectID() . 'PrincipalDestinations.php'))
			$this->Page_Terminate("PrincipalDestinations.php");
		if ($Security->AllowList(CurrentProjectID() . 'PrincipalLocations.php'))
			$this->Page_Terminate("PrincipalLocations.php");
		if ($Security->AllowList(CurrentProjectID() . 'PrincipalNews.php'))
			$this->Page_Terminate("PrincipalNews.php");
		if ($Security->AllowList(CurrentProjectID() . 'PrincipalOrganization.php'))
			$this->Page_Terminate("PrincipalOrganization.php");
		if ($Security->AllowList(CurrentProjectID() . 'PrincipalOurOffices.php'))
			$this->Page_Terminate("PrincipalOurOffices.php");
		if ($Security->AllowList(CurrentProjectID() . 'PrincipalRecomendations.php'))
			$this->Page_Terminate("PrincipalRecomendations.php");
		if ($Security->AllowList(CurrentProjectID() . 'PrincipalRecurrentAnswers.php'))
			$this->Page_Terminate("PrincipalRecurrentAnswers.php");
		if ($Security->AllowList(CurrentProjectID() . 'ReportePrueba'))
			$this->Page_Terminate("ReportePruebareport.php");
		if ($Security->AllowList(CurrentProjectID() . 'reservation'))
			$this->Page_Terminate("reservationlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'reservation_status'))
			$this->Page_Terminate("reservation_statuslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'transference'))
			$this->Page_Terminate("transferencelist.php");
		if ($Security->AllowList(CurrentProjectID() . 'upload_file'))
			$this->Page_Terminate("upload_filelist.php");
		if ($Security->AllowList(CurrentProjectID() . 'upload_file_detail'))
			$this->Page_Terminate("upload_file_detaillist.php");
		if ($Security->AllowList(CurrentProjectID() . 'upload_file_detail_status'))
			$this->Page_Terminate("upload_file_detail_statuslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'upload_file_status'))
			$this->Page_Terminate("upload_file_statuslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'user'))
			$this->Page_Terminate("userlist.php");
		if ($Security->AllowList(CurrentProjectID() . 'user_level_permissions'))
			$this->Page_Terminate("user_level_permissionslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'user_levels'))
			$this->Page_Terminate("user_levelslist.php");
		if ($Security->AllowList(CurrentProjectID() . 'view2'))
			$this->Page_Terminate("view2list.php");
		if ($Security->AllowList(CurrentProjectID() . 'PrincipalPassangerServices'))
			$this->Page_Terminate("PrincipalPassangerServices");
		if ($Security->IsLoggedIn()) {
			$this->setFailureMessage($Language->Phrase("NoPermission") . "<br><br><a href=\"logout.php\">" . $Language->Phrase("BackToLogin") . "</a>");
		} else {
			$this->Page_Terminate("login.php"); // Exit and go to login page
		}
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
}
?>
<?php ew_Header(TRUE) ?>
<?php

// Create page object
if (!isset($default)) $default = new cdefault();

// Page init
$default->Page_Init();

// Page main
$default->Page_Main();
?>
<?php include_once "header.php" ?>
<?php
$default->ShowMessage();
?>
<?php include_once "footer.php" ?>
<?php
$default->Page_Terminate();
?>
