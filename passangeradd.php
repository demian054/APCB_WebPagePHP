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

$passanger_add = NULL; // Initialize page object first

class cpassanger_add extends cpassanger {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{B2F6402D-7C0A-4760-82AD-045088CEBA18}";

	// Table name
	var $TableName = 'passanger';

	// Page object name
	var $PageObjName = 'passanger_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
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
			if (@$_GET["PASSANGER_ID"] != "") {
				$this->PASSANGER_ID->setQueryStringValue($_GET["PASSANGER_ID"]);
				$this->setKey("PASSANGER_ID", $this->PASSANGER_ID->CurrentValue); // Set up key
			} else {
				$this->setKey("PASSANGER_ID", ""); // Clear key
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
					$this->Page_Terminate("passangerlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "passangerlist.php")
						$sReturnUrl = $this->AddMasterUrl($this->GetListUrl()); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "passangerview.php")
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
		$this->CODE->CurrentValue = NULL;
		$this->CODE->OldValue = $this->CODE->CurrentValue;
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
		$this->PASSANGER_TYPE_ID->CurrentValue = NULL;
		$this->PASSANGER_TYPE_ID->OldValue = $this->PASSANGER_TYPE_ID->CurrentValue;
		$this->USER_ID->CurrentValue = NULL;
		$this->USER_ID->OldValue = $this->USER_ID->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->CODE->FldIsDetailKey) {
			$this->CODE->setFormValue($objForm->GetValue("x_CODE"));
		}
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
		if (!$this->PASSANGER_TYPE_ID->FldIsDetailKey) {
			$this->PASSANGER_TYPE_ID->setFormValue($objForm->GetValue("x_PASSANGER_TYPE_ID"));
		}
		if (!$this->USER_ID->FldIsDetailKey) {
			$this->USER_ID->setFormValue($objForm->GetValue("x_USER_ID"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->CODE->CurrentValue = $this->CODE->FormValue;
		$this->FIRSTNAME->CurrentValue = $this->FIRSTNAME->FormValue;
		$this->SECONDNAME->CurrentValue = $this->SECONDNAME->FormValue;
		$this->LASTNAME->CurrentValue = $this->LASTNAME->FormValue;
		$this->SURNAME->CurrentValue = $this->SURNAME->FormValue;
		$this->MAIL->CurrentValue = $this->MAIL->FormValue;
		$this->PASSANGER_TYPE_ID->CurrentValue = $this->PASSANGER_TYPE_ID->FormValue;
		$this->USER_ID->CurrentValue = $this->USER_ID->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("PASSANGER_ID")) <> "")
			$this->PASSANGER_ID->CurrentValue = $this->getKey("PASSANGER_ID"); // PASSANGER_ID
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// CODE
			$this->CODE->EditAttrs["class"] = "form-control";
			$this->CODE->EditCustomAttributes = "";
			$this->CODE->EditValue = ew_HtmlEncode($this->CODE->CurrentValue);
			$this->CODE->PlaceHolder = ew_RemoveHtml($this->CODE->FldCaption());

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

			// PASSANGER_TYPE_ID
			$this->PASSANGER_TYPE_ID->EditAttrs["class"] = "form-control";
			$this->PASSANGER_TYPE_ID->EditCustomAttributes = "";
			$this->PASSANGER_TYPE_ID->EditValue = ew_HtmlEncode($this->PASSANGER_TYPE_ID->CurrentValue);
			$this->PASSANGER_TYPE_ID->PlaceHolder = ew_RemoveHtml($this->PASSANGER_TYPE_ID->FldCaption());

			// USER_ID
			$this->USER_ID->EditAttrs["class"] = "form-control";
			$this->USER_ID->EditCustomAttributes = "";
			$this->USER_ID->EditValue = ew_HtmlEncode($this->USER_ID->CurrentValue);
			$this->USER_ID->PlaceHolder = ew_RemoveHtml($this->USER_ID->FldCaption());

			// Add refer script
			// CODE

			$this->CODE->LinkCustomAttributes = "";
			$this->CODE->HrefValue = "";

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

			// PASSANGER_TYPE_ID
			$this->PASSANGER_TYPE_ID->LinkCustomAttributes = "";
			$this->PASSANGER_TYPE_ID->HrefValue = "";

			// USER_ID
			$this->USER_ID->LinkCustomAttributes = "";
			$this->USER_ID->HrefValue = "";
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
		if (!$this->CODE->FldIsDetailKey && !is_null($this->CODE->FormValue) && $this->CODE->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->CODE->FldCaption(), $this->CODE->ReqErrMsg));
		}
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
		if (!$this->PASSANGER_TYPE_ID->FldIsDetailKey && !is_null($this->PASSANGER_TYPE_ID->FormValue) && $this->PASSANGER_TYPE_ID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->PASSANGER_TYPE_ID->FldCaption(), $this->PASSANGER_TYPE_ID->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->PASSANGER_TYPE_ID->FormValue)) {
			ew_AddMessage($gsFormError, $this->PASSANGER_TYPE_ID->FldErrMsg());
		}
		if (!ew_CheckInteger($this->USER_ID->FormValue)) {
			ew_AddMessage($gsFormError, $this->USER_ID->FldErrMsg());
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

		// CODE
		$this->CODE->SetDbValueDef($rsnew, $this->CODE->CurrentValue, "", FALSE);

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

		// PASSANGER_TYPE_ID
		$this->PASSANGER_TYPE_ID->SetDbValueDef($rsnew, $this->PASSANGER_TYPE_ID->CurrentValue, 0, FALSE);

		// USER_ID
		$this->USER_ID->SetDbValueDef($rsnew, $this->USER_ID->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->PASSANGER_ID->setDbValue($conn->Insert_ID());
				$rsnew['PASSANGER_ID'] = $this->PASSANGER_ID->DbValue;
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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("passangerlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($passanger_add)) $passanger_add = new cpassanger_add();

// Page init
$passanger_add->Page_Init();

// Page main
$passanger_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$passanger_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fpassangeradd = new ew_Form("fpassangeradd", "add");

// Validate form
fpassangeradd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_CODE");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $passanger->CODE->FldCaption(), $passanger->CODE->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_FIRSTNAME");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $passanger->FIRSTNAME->FldCaption(), $passanger->FIRSTNAME->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_SECONDNAME");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $passanger->SECONDNAME->FldCaption(), $passanger->SECONDNAME->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_LASTNAME");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $passanger->LASTNAME->FldCaption(), $passanger->LASTNAME->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_SURNAME");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $passanger->SURNAME->FldCaption(), $passanger->SURNAME->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_MAIL");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $passanger->MAIL->FldCaption(), $passanger->MAIL->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_PASSANGER_TYPE_ID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $passanger->PASSANGER_TYPE_ID->FldCaption(), $passanger->PASSANGER_TYPE_ID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_PASSANGER_TYPE_ID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($passanger->PASSANGER_TYPE_ID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_USER_ID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($passanger->USER_ID->FldErrMsg()) ?>");

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
fpassangeradd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpassangeradd.ValidateRequired = true;
<?php } else { ?>
fpassangeradd.ValidateRequired = false; 
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
<?php $passanger_add->ShowPageHeader(); ?>
<?php
$passanger_add->ShowMessage();
?>
<form name="fpassangeradd" id="fpassangeradd" class="<?php echo $passanger_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($passanger_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $passanger_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="passanger">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($passanger->CODE->Visible) { // CODE ?>
	<div id="r_CODE" class="form-group">
		<label id="elh_passanger_CODE" for="x_CODE" class="col-sm-2 control-label ewLabel"><?php echo $passanger->CODE->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $passanger->CODE->CellAttributes() ?>>
<span id="el_passanger_CODE">
<input type="text" data-table="passanger" data-field="x_CODE" name="x_CODE" id="x_CODE" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($passanger->CODE->getPlaceHolder()) ?>" value="<?php echo $passanger->CODE->EditValue ?>"<?php echo $passanger->CODE->EditAttributes() ?>>
</span>
<?php echo $passanger->CODE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($passanger->FIRSTNAME->Visible) { // FIRSTNAME ?>
	<div id="r_FIRSTNAME" class="form-group">
		<label id="elh_passanger_FIRSTNAME" for="x_FIRSTNAME" class="col-sm-2 control-label ewLabel"><?php echo $passanger->FIRSTNAME->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $passanger->FIRSTNAME->CellAttributes() ?>>
<span id="el_passanger_FIRSTNAME">
<input type="text" data-table="passanger" data-field="x_FIRSTNAME" name="x_FIRSTNAME" id="x_FIRSTNAME" size="30" maxlength="127" placeholder="<?php echo ew_HtmlEncode($passanger->FIRSTNAME->getPlaceHolder()) ?>" value="<?php echo $passanger->FIRSTNAME->EditValue ?>"<?php echo $passanger->FIRSTNAME->EditAttributes() ?>>
</span>
<?php echo $passanger->FIRSTNAME->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($passanger->SECONDNAME->Visible) { // SECONDNAME ?>
	<div id="r_SECONDNAME" class="form-group">
		<label id="elh_passanger_SECONDNAME" for="x_SECONDNAME" class="col-sm-2 control-label ewLabel"><?php echo $passanger->SECONDNAME->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $passanger->SECONDNAME->CellAttributes() ?>>
<span id="el_passanger_SECONDNAME">
<input type="text" data-table="passanger" data-field="x_SECONDNAME" name="x_SECONDNAME" id="x_SECONDNAME" size="30" maxlength="127" placeholder="<?php echo ew_HtmlEncode($passanger->SECONDNAME->getPlaceHolder()) ?>" value="<?php echo $passanger->SECONDNAME->EditValue ?>"<?php echo $passanger->SECONDNAME->EditAttributes() ?>>
</span>
<?php echo $passanger->SECONDNAME->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($passanger->LASTNAME->Visible) { // LASTNAME ?>
	<div id="r_LASTNAME" class="form-group">
		<label id="elh_passanger_LASTNAME" for="x_LASTNAME" class="col-sm-2 control-label ewLabel"><?php echo $passanger->LASTNAME->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $passanger->LASTNAME->CellAttributes() ?>>
<span id="el_passanger_LASTNAME">
<input type="text" data-table="passanger" data-field="x_LASTNAME" name="x_LASTNAME" id="x_LASTNAME" size="30" maxlength="127" placeholder="<?php echo ew_HtmlEncode($passanger->LASTNAME->getPlaceHolder()) ?>" value="<?php echo $passanger->LASTNAME->EditValue ?>"<?php echo $passanger->LASTNAME->EditAttributes() ?>>
</span>
<?php echo $passanger->LASTNAME->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($passanger->SURNAME->Visible) { // SURNAME ?>
	<div id="r_SURNAME" class="form-group">
		<label id="elh_passanger_SURNAME" for="x_SURNAME" class="col-sm-2 control-label ewLabel"><?php echo $passanger->SURNAME->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $passanger->SURNAME->CellAttributes() ?>>
<span id="el_passanger_SURNAME">
<input type="text" data-table="passanger" data-field="x_SURNAME" name="x_SURNAME" id="x_SURNAME" size="30" maxlength="127" placeholder="<?php echo ew_HtmlEncode($passanger->SURNAME->getPlaceHolder()) ?>" value="<?php echo $passanger->SURNAME->EditValue ?>"<?php echo $passanger->SURNAME->EditAttributes() ?>>
</span>
<?php echo $passanger->SURNAME->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($passanger->MAIL->Visible) { // MAIL ?>
	<div id="r_MAIL" class="form-group">
		<label id="elh_passanger_MAIL" for="x_MAIL" class="col-sm-2 control-label ewLabel"><?php echo $passanger->MAIL->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $passanger->MAIL->CellAttributes() ?>>
<span id="el_passanger_MAIL">
<input type="text" data-table="passanger" data-field="x_MAIL" name="x_MAIL" id="x_MAIL" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($passanger->MAIL->getPlaceHolder()) ?>" value="<?php echo $passanger->MAIL->EditValue ?>"<?php echo $passanger->MAIL->EditAttributes() ?>>
</span>
<?php echo $passanger->MAIL->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($passanger->PASSANGER_TYPE_ID->Visible) { // PASSANGER_TYPE_ID ?>
	<div id="r_PASSANGER_TYPE_ID" class="form-group">
		<label id="elh_passanger_PASSANGER_TYPE_ID" for="x_PASSANGER_TYPE_ID" class="col-sm-2 control-label ewLabel"><?php echo $passanger->PASSANGER_TYPE_ID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $passanger->PASSANGER_TYPE_ID->CellAttributes() ?>>
<span id="el_passanger_PASSANGER_TYPE_ID">
<input type="text" data-table="passanger" data-field="x_PASSANGER_TYPE_ID" name="x_PASSANGER_TYPE_ID" id="x_PASSANGER_TYPE_ID" size="30" placeholder="<?php echo ew_HtmlEncode($passanger->PASSANGER_TYPE_ID->getPlaceHolder()) ?>" value="<?php echo $passanger->PASSANGER_TYPE_ID->EditValue ?>"<?php echo $passanger->PASSANGER_TYPE_ID->EditAttributes() ?>>
</span>
<?php echo $passanger->PASSANGER_TYPE_ID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($passanger->USER_ID->Visible) { // USER_ID ?>
	<div id="r_USER_ID" class="form-group">
		<label id="elh_passanger_USER_ID" for="x_USER_ID" class="col-sm-2 control-label ewLabel"><?php echo $passanger->USER_ID->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $passanger->USER_ID->CellAttributes() ?>>
<span id="el_passanger_USER_ID">
<input type="text" data-table="passanger" data-field="x_USER_ID" name="x_USER_ID" id="x_USER_ID" size="30" placeholder="<?php echo ew_HtmlEncode($passanger->USER_ID->getPlaceHolder()) ?>" value="<?php echo $passanger->USER_ID->EditValue ?>"<?php echo $passanger->USER_ID->EditAttributes() ?>>
</span>
<?php echo $passanger->USER_ID->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $passanger_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fpassangeradd.Init();
</script>
<?php
$passanger_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$passanger_add->Page_Terminate();
?>
