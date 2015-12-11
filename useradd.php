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

$user_add = NULL; // Initialize page object first

class cuser_add extends cuser {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{B2F6402D-7C0A-4760-82AD-045088CEBA18}";

	// Table name
	var $TableName = 'user';

	// Page object name
	var $PageObjName = 'user_add';

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

		// Table object (user)
		if (!isset($GLOBALS["user"]) || get_class($GLOBALS["user"]) == "cuser") {
			$GLOBALS["user"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["user"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'user', TRUE);

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
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("userlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
			if (strval($Security->CurrentUserID()) == "") {
				$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
				$this->Page_Terminate(ew_GetUrl("userlist.php"));
			}
		}

		// Create form object
		$objForm = new cFormObj();
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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
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
		global $EW_EXPORT, $user;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($user);
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["USER_ID"] != "") {
				$this->USER_ID->setQueryStringValue($_GET["USER_ID"]);
				$this->setKey("USER_ID", $this->USER_ID->CurrentValue); // Set up key
			} else {
				$this->setKey("USER_ID", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("userlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "userlist.php")
						$sReturnUrl = $this->AddMasterUrl($this->GetListUrl()); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "userview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->FIRSTNAME->CurrentValue = NULL;
		$this->FIRSTNAME->OldValue = $this->FIRSTNAME->CurrentValue;
		$this->SECONDNAME->CurrentValue = NULL;
		$this->SECONDNAME->OldValue = $this->SECONDNAME->CurrentValue;
		$this->LASTNAME->CurrentValue = NULL;
		$this->LASTNAME->OldValue = $this->LASTNAME->CurrentValue;
		$this->SURNAME->CurrentValue = NULL;
		$this->SURNAME->OldValue = $this->SURNAME->CurrentValue;
		$this->MAIL->CurrentValue = NULL;
		$this->MAIL->OldValue = $this->MAIL->CurrentValue;
		$this->USER_ACT->CurrentValue = NULL;
		$this->USER_ACT->OldValue = $this->USER_ACT->CurrentValue;
		$this->USER_LEVEL_ID->CurrentValue = NULL;
		$this->USER_LEVEL_ID->OldValue = $this->USER_LEVEL_ID->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->FIRSTNAME->FldIsDetailKey) {
			$this->FIRSTNAME->setFormValue($objForm->GetValue("x_FIRSTNAME"));
		}
		if (!$this->SECONDNAME->FldIsDetailKey) {
			$this->SECONDNAME->setFormValue($objForm->GetValue("x_SECONDNAME"));
		}
		if (!$this->LASTNAME->FldIsDetailKey) {
			$this->LASTNAME->setFormValue($objForm->GetValue("x_LASTNAME"));
		}
		if (!$this->SURNAME->FldIsDetailKey) {
			$this->SURNAME->setFormValue($objForm->GetValue("x_SURNAME"));
		}
		if (!$this->MAIL->FldIsDetailKey) {
			$this->MAIL->setFormValue($objForm->GetValue("x_MAIL"));
		}
		if (!$this->USER_ACT->FldIsDetailKey) {
			$this->USER_ACT->setFormValue($objForm->GetValue("x_USER_ACT"));
		}
		if (!$this->USER_LEVEL_ID->FldIsDetailKey) {
			$this->USER_LEVEL_ID->setFormValue($objForm->GetValue("x_USER_LEVEL_ID"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->FIRSTNAME->CurrentValue = $this->FIRSTNAME->FormValue;
		$this->SECONDNAME->CurrentValue = $this->SECONDNAME->FormValue;
		$this->LASTNAME->CurrentValue = $this->LASTNAME->FormValue;
		$this->SURNAME->CurrentValue = $this->SURNAME->FormValue;
		$this->MAIL->CurrentValue = $this->MAIL->FormValue;
		$this->USER_ACT->CurrentValue = $this->USER_ACT->FormValue;
		$this->USER_LEVEL_ID->CurrentValue = $this->USER_LEVEL_ID->FormValue;
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

		// Check if valid user id
		if ($res) {
			$res = $this->ShowOptionLink('add');
			if (!$res) {
				$sUserIdMsg = $Language->Phrase("NoPermission");
				$this->setFailureMessage($sUserIdMsg);
			}
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
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

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->USER_ID->DbValue = $row['USER_ID'];
		$this->CODE->DbValue = $row['CODE'];
		$this->PASS->DbValue = $row['PASS'];
		$this->FIRSTNAME->DbValue = $row['FIRSTNAME'];
		$this->SECONDNAME->DbValue = $row['SECONDNAME'];
		$this->LASTNAME->DbValue = $row['LASTNAME'];
		$this->SURNAME->DbValue = $row['SURNAME'];
		$this->MAIL->DbValue = $row['MAIL'];
		$this->USER_ACT->DbValue = $row['USER_ACT'];
		$this->USER_LEVEL_ID->DbValue = $row['USER_LEVEL_ID'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("USER_ID")) <> "")
			$this->USER_ID->CurrentValue = $this->getKey("USER_ID"); // USER_ID
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// USER_ID
		// CODE
		// PASS
		// FIRSTNAME
		// SECONDNAME
		// LASTNAME
		// SURNAME
		// MAIL
		// USER_ACT
		// USER_LEVEL_ID

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// FIRSTNAME
			$this->FIRSTNAME->EditAttrs["class"] = "form-control";
			$this->FIRSTNAME->EditCustomAttributes = "";
			$this->FIRSTNAME->EditValue = ew_HtmlEncode($this->FIRSTNAME->CurrentValue);
			$this->FIRSTNAME->PlaceHolder = ew_RemoveHtml($this->FIRSTNAME->FldCaption());

			// SECONDNAME
			$this->SECONDNAME->EditAttrs["class"] = "form-control";
			$this->SECONDNAME->EditCustomAttributes = "";
			$this->SECONDNAME->EditValue = ew_HtmlEncode($this->SECONDNAME->CurrentValue);
			$this->SECONDNAME->PlaceHolder = ew_RemoveHtml($this->SECONDNAME->FldCaption());

			// LASTNAME
			$this->LASTNAME->EditAttrs["class"] = "form-control";
			$this->LASTNAME->EditCustomAttributes = "";
			$this->LASTNAME->EditValue = ew_HtmlEncode($this->LASTNAME->CurrentValue);
			$this->LASTNAME->PlaceHolder = ew_RemoveHtml($this->LASTNAME->FldCaption());

			// SURNAME
			$this->SURNAME->EditAttrs["class"] = "form-control";
			$this->SURNAME->EditCustomAttributes = "";
			$this->SURNAME->EditValue = ew_HtmlEncode($this->SURNAME->CurrentValue);
			$this->SURNAME->PlaceHolder = ew_RemoveHtml($this->SURNAME->FldCaption());

			// MAIL
			$this->MAIL->EditAttrs["class"] = "form-control";
			$this->MAIL->EditCustomAttributes = "";
			$this->MAIL->EditValue = ew_HtmlEncode($this->MAIL->CurrentValue);
			$this->MAIL->PlaceHolder = ew_RemoveHtml($this->MAIL->FldCaption());

			// USER_ACT
			$this->USER_ACT->EditAttrs["class"] = "form-control";
			$this->USER_ACT->EditCustomAttributes = "";
			$this->USER_ACT->EditValue = ew_HtmlEncode($this->USER_ACT->CurrentValue);
			$this->USER_ACT->PlaceHolder = ew_RemoveHtml($this->USER_ACT->FldCaption());

			// USER_LEVEL_ID
			$this->USER_LEVEL_ID->EditAttrs["class"] = "form-control";
			$this->USER_LEVEL_ID->EditCustomAttributes = "";
			if (!$Security->CanAdmin()) { // System admin
				$this->USER_LEVEL_ID->EditValue = $Language->Phrase("PasswordMask");
			} else {
			if (trim(strval($this->USER_LEVEL_ID->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`USER_LEVEL_ID`" . ew_SearchString("=", $this->USER_LEVEL_ID->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			switch (@$gsLanguage) {
				case "es":
					$sSqlWrk = "SELECT `USER_LEVEL_ID`, `DESCRIPTION` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `user_levels`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `USER_LEVEL_ID`, `DESCRIPTION` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `user_levels`";
					$sWhereWrk = "";
					break;
			}
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->USER_LEVEL_ID, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->USER_LEVEL_ID->EditValue = $arwrk;
			}

			// Add refer script
			// FIRSTNAME

			$this->FIRSTNAME->LinkCustomAttributes = "";
			$this->FIRSTNAME->HrefValue = "";

			// SECONDNAME
			$this->SECONDNAME->LinkCustomAttributes = "";
			$this->SECONDNAME->HrefValue = "";

			// LASTNAME
			$this->LASTNAME->LinkCustomAttributes = "";
			$this->LASTNAME->HrefValue = "";

			// SURNAME
			$this->SURNAME->LinkCustomAttributes = "";
			$this->SURNAME->HrefValue = "";

			// MAIL
			$this->MAIL->LinkCustomAttributes = "";
			$this->MAIL->HrefValue = "";

			// USER_ACT
			$this->USER_ACT->LinkCustomAttributes = "";
			$this->USER_ACT->HrefValue = "";

			// USER_LEVEL_ID
			$this->USER_LEVEL_ID->LinkCustomAttributes = "";
			$this->USER_LEVEL_ID->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->FIRSTNAME->FldIsDetailKey && !is_null($this->FIRSTNAME->FormValue) && $this->FIRSTNAME->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->FIRSTNAME->FldCaption(), $this->FIRSTNAME->ReqErrMsg));
		}
		if (!$this->SECONDNAME->FldIsDetailKey && !is_null($this->SECONDNAME->FormValue) && $this->SECONDNAME->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->SECONDNAME->FldCaption(), $this->SECONDNAME->ReqErrMsg));
		}
		if (!$this->LASTNAME->FldIsDetailKey && !is_null($this->LASTNAME->FormValue) && $this->LASTNAME->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->LASTNAME->FldCaption(), $this->LASTNAME->ReqErrMsg));
		}
		if (!$this->SURNAME->FldIsDetailKey && !is_null($this->SURNAME->FormValue) && $this->SURNAME->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->SURNAME->FldCaption(), $this->SURNAME->ReqErrMsg));
		}
		if (!$this->MAIL->FldIsDetailKey && !is_null($this->MAIL->FormValue) && $this->MAIL->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->MAIL->FldCaption(), $this->MAIL->ReqErrMsg));
		}
		if (!$this->USER_ACT->FldIsDetailKey && !is_null($this->USER_ACT->FormValue) && $this->USER_ACT->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->USER_ACT->FldCaption(), $this->USER_ACT->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->USER_ACT->FormValue)) {
			ew_AddMessage($gsFormError, $this->USER_ACT->FldErrMsg());
		}
		if (!$this->USER_LEVEL_ID->FldIsDetailKey && !is_null($this->USER_LEVEL_ID->FormValue) && $this->USER_LEVEL_ID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->USER_LEVEL_ID->FldCaption(), $this->USER_LEVEL_ID->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// FIRSTNAME
		$this->FIRSTNAME->SetDbValueDef($rsnew, $this->FIRSTNAME->CurrentValue, "", FALSE);

		// SECONDNAME
		$this->SECONDNAME->SetDbValueDef($rsnew, $this->SECONDNAME->CurrentValue, "", FALSE);

		// LASTNAME
		$this->LASTNAME->SetDbValueDef($rsnew, $this->LASTNAME->CurrentValue, "", FALSE);

		// SURNAME
		$this->SURNAME->SetDbValueDef($rsnew, $this->SURNAME->CurrentValue, "", FALSE);

		// MAIL
		$this->MAIL->SetDbValueDef($rsnew, $this->MAIL->CurrentValue, "", FALSE);

		// USER_ACT
		$this->USER_ACT->SetDbValueDef($rsnew, $this->USER_ACT->CurrentValue, 0, FALSE);

		// USER_LEVEL_ID
		if ($Security->CanAdmin()) { // System admin
		$this->USER_LEVEL_ID->SetDbValueDef($rsnew, $this->USER_LEVEL_ID->CurrentValue, 0, FALSE);
		}

		// USER_ID
		// Call Row Inserting event

		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->USER_ID->setDbValue($conn->Insert_ID());
				$rsnew['USER_ID'] = $this->USER_ID->DbValue;
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("userlist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(TRUE) ?>
<?php

// Create page object
if (!isset($user_add)) $user_add = new cuser_add();

// Page init
$user_add->Page_Init();

// Page main
$user_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$user_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fuseradd = new ew_Form("fuseradd", "add");

// Validate form
fuseradd.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_FIRSTNAME");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user->FIRSTNAME->FldCaption(), $user->FIRSTNAME->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_SECONDNAME");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user->SECONDNAME->FldCaption(), $user->SECONDNAME->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_LASTNAME");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user->LASTNAME->FldCaption(), $user->LASTNAME->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_SURNAME");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user->SURNAME->FldCaption(), $user->SURNAME->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_MAIL");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user->MAIL->FldCaption(), $user->MAIL->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_USER_ACT");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user->USER_ACT->FldCaption(), $user->USER_ACT->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_USER_ACT");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($user->USER_ACT->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_USER_LEVEL_ID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $user->USER_LEVEL_ID->FldCaption(), $user->USER_LEVEL_ID->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fuseradd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fuseradd.ValidateRequired = true;
<?php } else { ?>
fuseradd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fuseradd.Lists["x_USER_LEVEL_ID"] = {"LinkField":"x_USER_LEVEL_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_DESCRIPTION","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

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
<?php $user_add->ShowPageHeader(); ?>
<?php
$user_add->ShowMessage();
?>
<form name="fuseradd" id="fuseradd" class="<?php echo $user_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($user_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $user_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="user">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($user->FIRSTNAME->Visible) { // FIRSTNAME ?>
	<div id="r_FIRSTNAME" class="form-group">
		<label id="elh_user_FIRSTNAME" for="x_FIRSTNAME" class="col-sm-2 control-label ewLabel"><?php echo $user->FIRSTNAME->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $user->FIRSTNAME->CellAttributes() ?>>
<span id="el_user_FIRSTNAME">
<input type="text" data-table="user" data-field="x_FIRSTNAME" name="x_FIRSTNAME" id="x_FIRSTNAME" size="30" maxlength="127" placeholder="<?php echo ew_HtmlEncode($user->FIRSTNAME->getPlaceHolder()) ?>" value="<?php echo $user->FIRSTNAME->EditValue ?>"<?php echo $user->FIRSTNAME->EditAttributes() ?>>
</span>
<?php echo $user->FIRSTNAME->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user->SECONDNAME->Visible) { // SECONDNAME ?>
	<div id="r_SECONDNAME" class="form-group">
		<label id="elh_user_SECONDNAME" for="x_SECONDNAME" class="col-sm-2 control-label ewLabel"><?php echo $user->SECONDNAME->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $user->SECONDNAME->CellAttributes() ?>>
<span id="el_user_SECONDNAME">
<input type="text" data-table="user" data-field="x_SECONDNAME" name="x_SECONDNAME" id="x_SECONDNAME" size="30" maxlength="127" placeholder="<?php echo ew_HtmlEncode($user->SECONDNAME->getPlaceHolder()) ?>" value="<?php echo $user->SECONDNAME->EditValue ?>"<?php echo $user->SECONDNAME->EditAttributes() ?>>
</span>
<?php echo $user->SECONDNAME->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user->LASTNAME->Visible) { // LASTNAME ?>
	<div id="r_LASTNAME" class="form-group">
		<label id="elh_user_LASTNAME" for="x_LASTNAME" class="col-sm-2 control-label ewLabel"><?php echo $user->LASTNAME->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $user->LASTNAME->CellAttributes() ?>>
<span id="el_user_LASTNAME">
<input type="text" data-table="user" data-field="x_LASTNAME" name="x_LASTNAME" id="x_LASTNAME" size="30" maxlength="127" placeholder="<?php echo ew_HtmlEncode($user->LASTNAME->getPlaceHolder()) ?>" value="<?php echo $user->LASTNAME->EditValue ?>"<?php echo $user->LASTNAME->EditAttributes() ?>>
</span>
<?php echo $user->LASTNAME->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user->SURNAME->Visible) { // SURNAME ?>
	<div id="r_SURNAME" class="form-group">
		<label id="elh_user_SURNAME" for="x_SURNAME" class="col-sm-2 control-label ewLabel"><?php echo $user->SURNAME->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $user->SURNAME->CellAttributes() ?>>
<span id="el_user_SURNAME">
<input type="text" data-table="user" data-field="x_SURNAME" name="x_SURNAME" id="x_SURNAME" size="30" maxlength="127" placeholder="<?php echo ew_HtmlEncode($user->SURNAME->getPlaceHolder()) ?>" value="<?php echo $user->SURNAME->EditValue ?>"<?php echo $user->SURNAME->EditAttributes() ?>>
</span>
<?php echo $user->SURNAME->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user->MAIL->Visible) { // MAIL ?>
	<div id="r_MAIL" class="form-group">
		<label id="elh_user_MAIL" for="x_MAIL" class="col-sm-2 control-label ewLabel"><?php echo $user->MAIL->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $user->MAIL->CellAttributes() ?>>
<span id="el_user_MAIL">
<input type="text" data-table="user" data-field="x_MAIL" name="x_MAIL" id="x_MAIL" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($user->MAIL->getPlaceHolder()) ?>" value="<?php echo $user->MAIL->EditValue ?>"<?php echo $user->MAIL->EditAttributes() ?>>
</span>
<?php echo $user->MAIL->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user->USER_ACT->Visible) { // USER_ACT ?>
	<div id="r_USER_ACT" class="form-group">
		<label id="elh_user_USER_ACT" for="x_USER_ACT" class="col-sm-2 control-label ewLabel"><?php echo $user->USER_ACT->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $user->USER_ACT->CellAttributes() ?>>
<span id="el_user_USER_ACT">
<input type="text" data-table="user" data-field="x_USER_ACT" name="x_USER_ACT" id="x_USER_ACT" size="30" placeholder="<?php echo ew_HtmlEncode($user->USER_ACT->getPlaceHolder()) ?>" value="<?php echo $user->USER_ACT->EditValue ?>"<?php echo $user->USER_ACT->EditAttributes() ?>>
</span>
<?php echo $user->USER_ACT->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user->USER_LEVEL_ID->Visible) { // USER_LEVEL_ID ?>
	<div id="r_USER_LEVEL_ID" class="form-group">
		<label id="elh_user_USER_LEVEL_ID" for="x_USER_LEVEL_ID" class="col-sm-2 control-label ewLabel"><?php echo $user->USER_LEVEL_ID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $user->USER_LEVEL_ID->CellAttributes() ?>>
<?php if (!$Security->IsAdmin() && $Security->IsLoggedIn()) { // Non system admin ?>
<span id="el_user_USER_LEVEL_ID">
<p class="form-control-static"><?php echo $user->USER_LEVEL_ID->EditValue ?></p>
</span>
<?php } else { ?>
<span id="el_user_USER_LEVEL_ID">
<select data-table="user" data-field="x_USER_LEVEL_ID" data-value-separator="<?php echo ew_HtmlEncode(is_array($user->USER_LEVEL_ID->DisplayValueSeparator) ? json_encode($user->USER_LEVEL_ID->DisplayValueSeparator) : $user->USER_LEVEL_ID->DisplayValueSeparator) ?>" id="x_USER_LEVEL_ID" name="x_USER_LEVEL_ID"<?php echo $user->USER_LEVEL_ID->EditAttributes() ?>>
<?php
if (is_array($user->USER_LEVEL_ID->EditValue)) {
	$arwrk = $user->USER_LEVEL_ID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($user->USER_LEVEL_ID->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $user->USER_LEVEL_ID->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($user->USER_LEVEL_ID->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($user->USER_LEVEL_ID->CurrentValue) ?>" selected><?php echo $user->USER_LEVEL_ID->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
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
$user->USER_LEVEL_ID->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$user->USER_LEVEL_ID->LookupFilters += array("f0" => "`USER_LEVEL_ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$user->Lookup_Selecting($user->USER_LEVEL_ID, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $user->USER_LEVEL_ID->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_USER_LEVEL_ID" id="s_x_USER_LEVEL_ID" value="<?php echo $user->USER_LEVEL_ID->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php echo $user->USER_LEVEL_ID->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $user_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fuseradd.Init();
</script>
<?php
$user_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$user_add->Page_Terminate();
?>
