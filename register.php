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

$register = NULL; // Initialize page object first

class cregister extends cuser {

	// Page ID
	var $PageID = 'register';

	// Project ID
	var $ProjectID = "{B2F6402D-7C0A-4760-82AD-045088CEBA18}";

	// Page object name
	var $PageObjName = 'register';

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

		// Table object (user)
		if (!isset($GLOBALS["user"]) || get_class($GLOBALS["user"]) == "cuser") {
			$GLOBALS["user"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["user"];
		}
		if (!isset($GLOBALS["user"])) $GLOBALS["user"] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'register', TRUE);

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
	var $FormClassName = "form-horizontal ewForm ewRegisterForm";

	//
	// Page main
	//
	function Page_Main() {
		global $UserTableConn, $Security, $Language, $gsLanguage, $gsFormError, $objForm;
		global $Breadcrumb;

		// Set up Breadcrumb
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("register", "RegisterPage", $url, "", "", TRUE);
		$bUserExists = FALSE;
		if (@$_POST["a_register"] <> "") {

			// Get action
			$this->CurrentAction = $_POST["a_register"];
			$this->LoadFormValues(); // Get form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->setFailureMessage($gsFormError);
			}
		} else {
			$this->CurrentAction = "I"; // Display blank record
			$this->LoadDefaultValues(); // Load default values
		}
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "A": // Add

				// Check for duplicate User ID
				$sFilter = str_replace("%u", ew_AdjustSql($this->CODE->CurrentValue, EW_USER_TABLE_DBID), EW_USER_NAME_FILTER);

				// Set up filter (SQL WHERE clause) and get return SQL
				// SQL constructor in user class, userinfo.php

				$this->CurrentFilter = $sFilter;
				$sUserSql = $this->SQL();
				if ($rs = $UserTableConn->Execute($sUserSql)) {
					if (!$rs->EOF) {
						$bUserExists = TRUE;
						$this->RestoreFormValues(); // Restore form values
						$this->setFailureMessage($Language->Phrase("UserExists")); // Set user exist message
					}
					$rs->Close();
				}
				if (!$bUserExists) {
					$this->SendEmail = TRUE; // Send email on add success
					if ($this->AddRow()) { // Add record
						if ($this->getSuccessMessage() == "")
							$this->setSuccessMessage($Language->Phrase("RegisterSuccess")); // Register success

						// Auto login user
						if ($Security->ValidateUser($this->CODE->CurrentValue, $this->PASS->FormValue, TRUE)) {

							// Nothing to do
						}
						$this->Page_Terminate("index.php"); // Return
					} else {
						$this->RestoreFormValues(); // Restore form values
					}
				}
		}

		// Render row
		$this->RowType = EW_ROWTYPE_ADD; // Render add
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
		$this->PASS->CurrentValue = NULL;
		$this->PASS->OldValue = $this->PASS->CurrentValue;
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
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->CODE->FldIsDetailKey) {
			$this->CODE->setFormValue($objForm->GetValue("x_CODE"));
		}
		if (!$this->PASS->FldIsDetailKey) {
			$this->PASS->setFormValue($objForm->GetValue("x_PASS"));
		}
		$this->PASS->ConfirmValue = $objForm->GetValue("c_PASS");
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
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->CODE->CurrentValue = $this->CODE->FormValue;
		$this->PASS->CurrentValue = $this->PASS->FormValue;
		$this->FIRSTNAME->CurrentValue = $this->FIRSTNAME->FormValue;
		$this->SECONDNAME->CurrentValue = $this->SECONDNAME->FormValue;
		$this->LASTNAME->CurrentValue = $this->LASTNAME->FormValue;
		$this->SURNAME->CurrentValue = $this->SURNAME->FormValue;
		$this->MAIL->CurrentValue = $this->MAIL->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// CODE
			$this->CODE->EditAttrs["class"] = "form-control";
			$this->CODE->EditCustomAttributes = "";
			$this->CODE->EditValue = ew_HtmlEncode($this->CODE->CurrentValue);
			$this->CODE->PlaceHolder = ew_RemoveHtml($this->CODE->FldCaption());

			// PASS
			$this->PASS->EditAttrs["class"] = "form-control ewPasswordStrength";
			$this->PASS->EditCustomAttributes = "";
			$this->PASS->EditValue = ew_HtmlEncode($this->PASS->CurrentValue);
			$this->PASS->PlaceHolder = ew_RemoveHtml($this->PASS->FldCaption());

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

			// Add refer script
			// CODE

			$this->CODE->LinkCustomAttributes = "";
			$this->CODE->HrefValue = "";

			// PASS
			$this->PASS->LinkCustomAttributes = "";
			$this->PASS->HrefValue = "";

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
			ew_AddMessage($gsFormError, $Language->Phrase("EnterUserName"));
		}
		if (!$this->PASS->FldIsDetailKey && !is_null($this->PASS->FormValue) && $this->PASS->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterPassword"));
		}
		if ($this->PASS->ConfirmValue <> $this->PASS->FormValue) {
			ew_AddMessage($gsFormError, $Language->Phrase("MismatchPassword"));
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

		// PASS
		$this->PASS->SetDbValueDef($rsnew, $this->PASS->CurrentValue, NULL, FALSE);

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

			// Call User Registered event
			$this->User_Registered($rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
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

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}

	// User Registered event
	function User_Registered(&$rs) {

	  //echo "User_Registered";
	}

	// User Activated event
	function User_Activated(&$rs) {

	  //echo "User_Activated";
	}
}
?>
<?php ew_Header(TRUE) ?>
<?php

// Create page object
if (!isset($register)) $register = new cregister();

// Page init
$register->Page_Init();

// Page main
$register->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$register->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "register";
var CurrentForm = fregister = new ew_Form("fregister", "register");

// Validate form
fregister.Validate = function() {
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
				return this.OnError(elm, ewLanguage.Phrase("EnterUserName"));
			elm = this.GetElements("x" + infix + "_PASS");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterPassword"));
			if ($(fobj.x_PASS).hasClass("ewPasswordStrength") && !$(fobj.x_PASS).data("validated"))
				return this.OnError(fobj.x_PASS, ewLanguage.Phrase("PasswordTooSimple"));
			if (fobj.c_PASS.value != fobj.x_PASS.value)
				return this.OnError(fobj.c_PASS, ewLanguage.Phrase("MismatchPassword"));
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

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fregister.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fregister.ValidateRequired = true;
<?php } else { ?>
fregister.ValidateRequired = false; 
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
<?php $register->ShowPageHeader(); ?>
<?php
$register->ShowMessage();
?>
<form name="fregister" id="fregister" class="<?php echo $register->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($register->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $register->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="user">
<input type="hidden" name="a_register" id="a_register" value="A">
<!-- Fields to prevent google autofill -->
<input class="hidden" type="text" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<input class="hidden" type="password" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<div>
<?php if ($user->CODE->Visible) { // CODE ?>
	<div id="r_CODE" class="form-group">
		<label id="elh_user_CODE" for="x_CODE" class="col-sm-2 control-label ewLabel"><?php echo $user->CODE->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $user->CODE->CellAttributes() ?>>
<span id="el_user_CODE">
<input type="text" data-table="user" data-field="x_CODE" name="x_CODE" id="x_CODE" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($user->CODE->getPlaceHolder()) ?>" value="<?php echo $user->CODE->EditValue ?>"<?php echo $user->CODE->EditAttributes() ?>>
</span>
<?php echo $user->CODE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user->PASS->Visible) { // PASS ?>
	<div id="r_PASS" class="form-group">
		<label id="elh_user_PASS" for="x_PASS" class="col-sm-2 control-label ewLabel"><?php echo $user->PASS->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $user->PASS->CellAttributes() ?>>
<span id="el_user_PASS">
<div class="input-group" id="ig_x_PASS">
<input type="text" data-password-strength="pst_x_PASS" data-password-generated="pgt_x_PASS" data-table="user" data-field="x_PASS" name="x_PASS" id="x_PASS" value="<?php echo $user->PASS->EditValue ?>" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($user->PASS->getPlaceHolder()) ?>"<?php echo $user->PASS->EditAttributes() ?>>
<span class="input-group-btn">
	<button type="button" class="btn btn-default ewPasswordGenerator" title="<?php echo ew_HtmlTitle($Language->Phrase("GeneratePassword")) ?>" data-password-field="x_PASS" data-password-confirm="c_PASS" data-password-strength="pst_x_PASS" data-password-generated="pgt_x_PASS"><?php echo $Language->Phrase("GeneratePassword") ?></button>
</span>
</div>
<span class="help-block" id="pgt_x_PASS" style="display: none;"></span>
<div class="progress ewPasswordStrengthBar" id="pst_x_PASS" style="display: none;">
	<div class="progress-bar" role="progressbar"></div>
</div>
</span>
<?php echo $user->PASS->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($user->PASS->Visible) { // PASS ?>
	<div id="r_c_PASS" class="form-group">
		<label id="elh_c_user_PASS" for="c_PASS" class="col-sm-2 control-label ewLabel"><?php echo $Language->Phrase("Confirm") ?> <?php echo $user->PASS->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $user->PASS->CellAttributes() ?>>
<span id="el_c_user_PASS">
<input type="text" data-table="user" data-field="c_PASS" name="c_PASS" id="c_PASS" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($user->PASS->getPlaceHolder()) ?>" value="<?php echo $user->PASS->EditValue ?>"<?php echo $user->PASS->EditAttributes() ?>>
</span>
</div></div>
	</div>
<?php } ?>
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
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("RegisterBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fregister.Init();
</script>
<?php
$register->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$register->Page_Terminate();
?>
