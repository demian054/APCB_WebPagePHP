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

$user_edit = NULL; // Initialize page object first

class cuser_edit extends cuser {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{B2F6402D-7C0A-4760-82AD-045088CEBA18}";

	// Table name
	var $TableName = 'user';

	// Page object name
	var $PageObjName = 'user_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (!$Security->CanEdit()) {
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
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["USER_ID"] <> "") {
			$this->USER_ID->setQueryStringValue($_GET["USER_ID"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->USER_ID->CurrentValue == "")
			$this->Page_Terminate("userlist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("userlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "userlist.php")
					$sReturnUrl = $this->AddMasterUrl($this->GetListUrl()); // List page, return to list page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
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
		if (!$this->USER_ID->FldIsDetailKey)
			$this->USER_ID->setFormValue($objForm->GetValue("x_USER_ID"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->USER_ID->CurrentValue = $this->USER_ID->FormValue;
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

		// Check if valid user id
		if ($res) {
			$res = $this->ShowOptionLink('edit');
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

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

			// Edit refer script
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

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// FIRSTNAME
			$this->FIRSTNAME->SetDbValueDef($rsnew, $this->FIRSTNAME->CurrentValue, "", $this->FIRSTNAME->ReadOnly);

			// SECONDNAME
			$this->SECONDNAME->SetDbValueDef($rsnew, $this->SECONDNAME->CurrentValue, "", $this->SECONDNAME->ReadOnly);

			// LASTNAME
			$this->LASTNAME->SetDbValueDef($rsnew, $this->LASTNAME->CurrentValue, "", $this->LASTNAME->ReadOnly);

			// SURNAME
			$this->SURNAME->SetDbValueDef($rsnew, $this->SURNAME->CurrentValue, "", $this->SURNAME->ReadOnly);

			// MAIL
			$this->MAIL->SetDbValueDef($rsnew, $this->MAIL->CurrentValue, "", $this->MAIL->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
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
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
if (!isset($user_edit)) $user_edit = new cuser_edit();

// Page init
$user_edit->Page_Init();

// Page main
$user_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$user_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fuseredit = new ew_Form("fuseredit", "edit");

// Validate form
fuseredit.Validate = function() {
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
fuseredit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fuseredit.ValidateRequired = true;
<?php } else { ?>
fuseredit.ValidateRequired = false; 
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
<?php $user_edit->ShowPageHeader(); ?>
<?php
$user_edit->ShowMessage();
?>
<form name="fuseredit" id="fuseredit" class="<?php echo $user_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($user_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $user_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="user">
<input type="hidden" name="a_edit" id="a_edit" value="U">
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
</div>
<input type="hidden" data-table="user" data-field="x_USER_ID" name="x_USER_ID" id="x_USER_ID" value="<?php echo ew_HtmlEncode($user->USER_ID->CurrentValue) ?>">
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $user_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fuseredit.Init();
</script>
<?php
$user_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$user_edit->Page_Terminate();
?>
