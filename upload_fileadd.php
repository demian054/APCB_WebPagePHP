<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "upload_fileinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$upload_file_add = NULL; // Initialize page object first

class cupload_file_add extends cupload_file {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{B2F6402D-7C0A-4760-82AD-045088CEBA18}";

	// Table name
	var $TableName = 'upload_file';

	// Page object name
	var $PageObjName = 'upload_file_add';

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

		// Table object (upload_file)
		if (!isset($GLOBALS["upload_file"]) || get_class($GLOBALS["upload_file"]) == "cupload_file") {
			$GLOBALS["upload_file"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["upload_file"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'upload_file', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("upload_filelist.php"));
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
		global $EW_EXPORT, $upload_file;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($upload_file);
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
			if (@$_GET["UPLOAD_FILE_ID"] != "") {
				$this->UPLOAD_FILE_ID->setQueryStringValue($_GET["UPLOAD_FILE_ID"]);
				$this->setKey("UPLOAD_FILE_ID", $this->UPLOAD_FILE_ID->CurrentValue); // Set up key
			} else {
				$this->setKey("UPLOAD_FILE_ID", ""); // Clear key
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
					$this->Page_Terminate("upload_filelist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "upload_filelist.php")
						$sReturnUrl = $this->AddMasterUrl($this->GetListUrl()); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "upload_fileview.php")
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
		$this->PATH->Upload->Index = $objForm->Index;
		$this->PATH->Upload->UploadFile();
		$this->PATH->CurrentValue = $this->PATH->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->PATH->Upload->DbValue = NULL;
		$this->PATH->OldValue = $this->PATH->Upload->DbValue;
		$this->PATH->CurrentValue = NULL; // Clear file related field
		$this->PROCESS->CurrentValue = NULL;
		$this->PROCESS->OldValue = $this->PROCESS->CurrentValue;
		$this->BANK_ACCOUNT_ID->CurrentValue = NULL;
		$this->BANK_ACCOUNT_ID->OldValue = $this->BANK_ACCOUNT_ID->CurrentValue;
		$this->UPLOAD_FILE_STATUS_ID->CurrentValue = NULL;
		$this->UPLOAD_FILE_STATUS_ID->OldValue = $this->UPLOAD_FILE_STATUS_ID->CurrentValue;
		$this->DATETIME->CurrentValue = NULL;
		$this->DATETIME->OldValue = $this->DATETIME->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->PROCESS->FldIsDetailKey) {
			$this->PROCESS->setFormValue($objForm->GetValue("x_PROCESS"));
		}
		if (!$this->BANK_ACCOUNT_ID->FldIsDetailKey) {
			$this->BANK_ACCOUNT_ID->setFormValue($objForm->GetValue("x_BANK_ACCOUNT_ID"));
		}
		if (!$this->UPLOAD_FILE_STATUS_ID->FldIsDetailKey) {
			$this->UPLOAD_FILE_STATUS_ID->setFormValue($objForm->GetValue("x_UPLOAD_FILE_STATUS_ID"));
		}
		if (!$this->DATETIME->FldIsDetailKey) {
			$this->DATETIME->setFormValue($objForm->GetValue("x_DATETIME"));
			$this->DATETIME->CurrentValue = ew_UnFormatDateTime($this->DATETIME->CurrentValue, 7);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->PROCESS->CurrentValue = $this->PROCESS->FormValue;
		$this->BANK_ACCOUNT_ID->CurrentValue = $this->BANK_ACCOUNT_ID->FormValue;
		$this->UPLOAD_FILE_STATUS_ID->CurrentValue = $this->UPLOAD_FILE_STATUS_ID->FormValue;
		$this->DATETIME->CurrentValue = $this->DATETIME->FormValue;
		$this->DATETIME->CurrentValue = ew_UnFormatDateTime($this->DATETIME->CurrentValue, 7);
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
		$this->UPLOAD_FILE_ID->setDbValue($rs->fields('UPLOAD_FILE_ID'));
		$this->PATH->Upload->DbValue = $rs->fields('PATH');
		$this->PATH->CurrentValue = $this->PATH->Upload->DbValue;
		$this->PROCESS->setDbValue($rs->fields('PROCESS'));
		$this->BANK_ACCOUNT_ID->setDbValue($rs->fields('BANK_ACCOUNT_ID'));
		$this->UPLOAD_FILE_STATUS_ID->setDbValue($rs->fields('UPLOAD_FILE_STATUS_ID'));
		$this->DATETIME->setDbValue($rs->fields('DATETIME'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->UPLOAD_FILE_ID->DbValue = $row['UPLOAD_FILE_ID'];
		$this->PATH->Upload->DbValue = $row['PATH'];
		$this->PROCESS->DbValue = $row['PROCESS'];
		$this->BANK_ACCOUNT_ID->DbValue = $row['BANK_ACCOUNT_ID'];
		$this->UPLOAD_FILE_STATUS_ID->DbValue = $row['UPLOAD_FILE_STATUS_ID'];
		$this->DATETIME->DbValue = $row['DATETIME'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("UPLOAD_FILE_ID")) <> "")
			$this->UPLOAD_FILE_ID->CurrentValue = $this->getKey("UPLOAD_FILE_ID"); // UPLOAD_FILE_ID
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
		// UPLOAD_FILE_ID
		// PATH
		// PROCESS
		// BANK_ACCOUNT_ID
		// UPLOAD_FILE_STATUS_ID
		// DATETIME

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// UPLOAD_FILE_ID
		$this->UPLOAD_FILE_ID->ViewValue = $this->UPLOAD_FILE_ID->CurrentValue;
		$this->UPLOAD_FILE_ID->ViewCustomAttributes = "";

		// PATH
		if (!ew_Empty($this->PATH->Upload->DbValue)) {
			$this->PATH->ViewValue = $this->PATH->Upload->DbValue;
		} else {
			$this->PATH->ViewValue = "";
		}
		$this->PATH->ViewCustomAttributes = "";

		// PROCESS
		$this->PROCESS->ViewValue = $this->PROCESS->CurrentValue;
		$this->PROCESS->ViewCustomAttributes = "";

		// BANK_ACCOUNT_ID
		$this->BANK_ACCOUNT_ID->ViewValue = $this->BANK_ACCOUNT_ID->CurrentValue;
		$this->BANK_ACCOUNT_ID->ViewCustomAttributes = "";

		// UPLOAD_FILE_STATUS_ID
		$this->UPLOAD_FILE_STATUS_ID->ViewValue = $this->UPLOAD_FILE_STATUS_ID->CurrentValue;
		$this->UPLOAD_FILE_STATUS_ID->ViewCustomAttributes = "";

		// DATETIME
		$this->DATETIME->ViewValue = $this->DATETIME->CurrentValue;
		$this->DATETIME->ViewValue = ew_FormatDateTime($this->DATETIME->ViewValue, 7);
		$this->DATETIME->ViewCustomAttributes = "";

			// PATH
			$this->PATH->LinkCustomAttributes = "";
			$this->PATH->HrefValue = "";
			$this->PATH->HrefValue2 = $this->PATH->UploadPath . $this->PATH->Upload->DbValue;
			$this->PATH->TooltipValue = "";

			// PROCESS
			$this->PROCESS->LinkCustomAttributes = "";
			$this->PROCESS->HrefValue = "";
			$this->PROCESS->TooltipValue = "";

			// BANK_ACCOUNT_ID
			$this->BANK_ACCOUNT_ID->LinkCustomAttributes = "";
			$this->BANK_ACCOUNT_ID->HrefValue = "";
			$this->BANK_ACCOUNT_ID->TooltipValue = "";

			// UPLOAD_FILE_STATUS_ID
			$this->UPLOAD_FILE_STATUS_ID->LinkCustomAttributes = "";
			$this->UPLOAD_FILE_STATUS_ID->HrefValue = "";
			$this->UPLOAD_FILE_STATUS_ID->TooltipValue = "";

			// DATETIME
			$this->DATETIME->LinkCustomAttributes = "";
			$this->DATETIME->HrefValue = "";
			$this->DATETIME->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// PATH
			$this->PATH->EditAttrs["class"] = "form-control";
			$this->PATH->EditCustomAttributes = "";
			if (!ew_Empty($this->PATH->Upload->DbValue)) {
				$this->PATH->EditValue = $this->PATH->Upload->DbValue;
			} else {
				$this->PATH->EditValue = "";
			}
			if (!ew_Empty($this->PATH->CurrentValue))
				$this->PATH->Upload->FileName = $this->PATH->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->PATH);

			// PROCESS
			$this->PROCESS->EditAttrs["class"] = "form-control";
			$this->PROCESS->EditCustomAttributes = "";
			$this->PROCESS->EditValue = ew_HtmlEncode($this->PROCESS->CurrentValue);
			$this->PROCESS->PlaceHolder = ew_RemoveHtml($this->PROCESS->FldCaption());

			// BANK_ACCOUNT_ID
			$this->BANK_ACCOUNT_ID->EditAttrs["class"] = "form-control";
			$this->BANK_ACCOUNT_ID->EditCustomAttributes = "";
			$this->BANK_ACCOUNT_ID->EditValue = ew_HtmlEncode($this->BANK_ACCOUNT_ID->CurrentValue);
			$this->BANK_ACCOUNT_ID->PlaceHolder = ew_RemoveHtml($this->BANK_ACCOUNT_ID->FldCaption());

			// UPLOAD_FILE_STATUS_ID
			$this->UPLOAD_FILE_STATUS_ID->EditAttrs["class"] = "form-control";
			$this->UPLOAD_FILE_STATUS_ID->EditCustomAttributes = "";
			$this->UPLOAD_FILE_STATUS_ID->EditValue = ew_HtmlEncode($this->UPLOAD_FILE_STATUS_ID->CurrentValue);
			$this->UPLOAD_FILE_STATUS_ID->PlaceHolder = ew_RemoveHtml($this->UPLOAD_FILE_STATUS_ID->FldCaption());

			// DATETIME
			$this->DATETIME->EditAttrs["class"] = "form-control";
			$this->DATETIME->EditCustomAttributes = "";
			$this->DATETIME->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->DATETIME->CurrentValue, 7));
			$this->DATETIME->PlaceHolder = ew_RemoveHtml($this->DATETIME->FldCaption());

			// Add refer script
			// PATH

			$this->PATH->LinkCustomAttributes = "";
			$this->PATH->HrefValue = "";
			$this->PATH->HrefValue2 = $this->PATH->UploadPath . $this->PATH->Upload->DbValue;

			// PROCESS
			$this->PROCESS->LinkCustomAttributes = "";
			$this->PROCESS->HrefValue = "";

			// BANK_ACCOUNT_ID
			$this->BANK_ACCOUNT_ID->LinkCustomAttributes = "";
			$this->BANK_ACCOUNT_ID->HrefValue = "";

			// UPLOAD_FILE_STATUS_ID
			$this->UPLOAD_FILE_STATUS_ID->LinkCustomAttributes = "";
			$this->UPLOAD_FILE_STATUS_ID->HrefValue = "";

			// DATETIME
			$this->DATETIME->LinkCustomAttributes = "";
			$this->DATETIME->HrefValue = "";
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
		if ($this->PATH->Upload->FileName == "" && !$this->PATH->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->PATH->FldCaption(), $this->PATH->ReqErrMsg));
		}
		if (!$this->PROCESS->FldIsDetailKey && !is_null($this->PROCESS->FormValue) && $this->PROCESS->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->PROCESS->FldCaption(), $this->PROCESS->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->PROCESS->FormValue)) {
			ew_AddMessage($gsFormError, $this->PROCESS->FldErrMsg());
		}
		if (!$this->BANK_ACCOUNT_ID->FldIsDetailKey && !is_null($this->BANK_ACCOUNT_ID->FormValue) && $this->BANK_ACCOUNT_ID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->BANK_ACCOUNT_ID->FldCaption(), $this->BANK_ACCOUNT_ID->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->BANK_ACCOUNT_ID->FormValue)) {
			ew_AddMessage($gsFormError, $this->BANK_ACCOUNT_ID->FldErrMsg());
		}
		if (!$this->UPLOAD_FILE_STATUS_ID->FldIsDetailKey && !is_null($this->UPLOAD_FILE_STATUS_ID->FormValue) && $this->UPLOAD_FILE_STATUS_ID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->UPLOAD_FILE_STATUS_ID->FldCaption(), $this->UPLOAD_FILE_STATUS_ID->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->UPLOAD_FILE_STATUS_ID->FormValue)) {
			ew_AddMessage($gsFormError, $this->UPLOAD_FILE_STATUS_ID->FldErrMsg());
		}
		if (!$this->DATETIME->FldIsDetailKey && !is_null($this->DATETIME->FormValue) && $this->DATETIME->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->DATETIME->FldCaption(), $this->DATETIME->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->DATETIME->FormValue)) {
			ew_AddMessage($gsFormError, $this->DATETIME->FldErrMsg());
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

		// PATH
		if ($this->PATH->Visible && !$this->PATH->Upload->KeepFile) {
			$this->PATH->Upload->DbValue = ""; // No need to delete old file
			if ($this->PATH->Upload->FileName == "") {
				$rsnew['PATH'] = NULL;
			} else {
				$rsnew['PATH'] = $this->PATH->Upload->FileName;
			}
		}

		// PROCESS
		$this->PROCESS->SetDbValueDef($rsnew, $this->PROCESS->CurrentValue, 0, FALSE);

		// BANK_ACCOUNT_ID
		$this->BANK_ACCOUNT_ID->SetDbValueDef($rsnew, $this->BANK_ACCOUNT_ID->CurrentValue, 0, FALSE);

		// UPLOAD_FILE_STATUS_ID
		$this->UPLOAD_FILE_STATUS_ID->SetDbValueDef($rsnew, $this->UPLOAD_FILE_STATUS_ID->CurrentValue, 0, FALSE);

		// DATETIME
		$this->DATETIME->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->DATETIME->CurrentValue, 7), ew_CurrentDate(), FALSE);
		if ($this->PATH->Visible && !$this->PATH->Upload->KeepFile) {
			if (!ew_Empty($this->PATH->Upload->Value)) {
				$rsnew['PATH'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->PATH->UploadPath), $rsnew['PATH']); // Get new file name
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->UPLOAD_FILE_ID->setDbValue($conn->Insert_ID());
				$rsnew['UPLOAD_FILE_ID'] = $this->UPLOAD_FILE_ID->DbValue;
				if ($this->PATH->Visible && !$this->PATH->Upload->KeepFile) {
					if (!ew_Empty($this->PATH->Upload->Value)) {
						$this->PATH->Upload->SaveToFile($this->PATH->UploadPath, $rsnew['PATH'], TRUE);
					}
				}
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

		// PATH
		ew_CleanUploadTempPath($this->PATH, $this->PATH->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("upload_filelist.php"), "", $this->TableVar, TRUE);
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
if (!isset($upload_file_add)) $upload_file_add = new cupload_file_add();

// Page init
$upload_file_add->Page_Init();

// Page main
$upload_file_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$upload_file_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fupload_fileadd = new ew_Form("fupload_fileadd", "add");

// Validate form
fupload_fileadd.Validate = function() {
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
			felm = this.GetElements("x" + infix + "_PATH");
			elm = this.GetElements("fn_x" + infix + "_PATH");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $upload_file->PATH->FldCaption(), $upload_file->PATH->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_PROCESS");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $upload_file->PROCESS->FldCaption(), $upload_file->PROCESS->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_PROCESS");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($upload_file->PROCESS->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_BANK_ACCOUNT_ID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $upload_file->BANK_ACCOUNT_ID->FldCaption(), $upload_file->BANK_ACCOUNT_ID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_BANK_ACCOUNT_ID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($upload_file->BANK_ACCOUNT_ID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_UPLOAD_FILE_STATUS_ID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $upload_file->UPLOAD_FILE_STATUS_ID->FldCaption(), $upload_file->UPLOAD_FILE_STATUS_ID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_UPLOAD_FILE_STATUS_ID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($upload_file->UPLOAD_FILE_STATUS_ID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_DATETIME");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $upload_file->DATETIME->FldCaption(), $upload_file->DATETIME->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_DATETIME");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($upload_file->DATETIME->FldErrMsg()) ?>");

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
fupload_fileadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fupload_fileadd.ValidateRequired = true;
<?php } else { ?>
fupload_fileadd.ValidateRequired = false; 
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
<?php $upload_file_add->ShowPageHeader(); ?>
<?php
$upload_file_add->ShowMessage();
?>
<form name="fupload_fileadd" id="fupload_fileadd" class="<?php echo $upload_file_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($upload_file_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $upload_file_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="upload_file">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($upload_file->PATH->Visible) { // PATH ?>
	<div id="r_PATH" class="form-group">
		<label id="elh_upload_file_PATH" class="col-sm-2 control-label ewLabel"><?php echo $upload_file->PATH->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $upload_file->PATH->CellAttributes() ?>>
<span id="el_upload_file_PATH">
<div id="fd_x_PATH">
<span title="<?php echo $upload_file->PATH->FldTitle() ? $upload_file->PATH->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($upload_file->PATH->ReadOnly || $upload_file->PATH->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="upload_file" data-field="x_PATH" name="x_PATH" id="x_PATH"<?php echo $upload_file->PATH->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_PATH" id= "fn_x_PATH" value="<?php echo $upload_file->PATH->Upload->FileName ?>">
<input type="hidden" name="fa_x_PATH" id= "fa_x_PATH" value="0">
<input type="hidden" name="fs_x_PATH" id= "fs_x_PATH" value="255">
<input type="hidden" name="fx_x_PATH" id= "fx_x_PATH" value="<?php echo $upload_file->PATH->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_PATH" id= "fm_x_PATH" value="<?php echo $upload_file->PATH->UploadMaxFileSize ?>">
</div>
<table id="ft_x_PATH" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $upload_file->PATH->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($upload_file->PROCESS->Visible) { // PROCESS ?>
	<div id="r_PROCESS" class="form-group">
		<label id="elh_upload_file_PROCESS" for="x_PROCESS" class="col-sm-2 control-label ewLabel"><?php echo $upload_file->PROCESS->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $upload_file->PROCESS->CellAttributes() ?>>
<span id="el_upload_file_PROCESS">
<input type="text" data-table="upload_file" data-field="x_PROCESS" name="x_PROCESS" id="x_PROCESS" size="30" placeholder="<?php echo ew_HtmlEncode($upload_file->PROCESS->getPlaceHolder()) ?>" value="<?php echo $upload_file->PROCESS->EditValue ?>"<?php echo $upload_file->PROCESS->EditAttributes() ?>>
</span>
<?php echo $upload_file->PROCESS->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($upload_file->BANK_ACCOUNT_ID->Visible) { // BANK_ACCOUNT_ID ?>
	<div id="r_BANK_ACCOUNT_ID" class="form-group">
		<label id="elh_upload_file_BANK_ACCOUNT_ID" for="x_BANK_ACCOUNT_ID" class="col-sm-2 control-label ewLabel"><?php echo $upload_file->BANK_ACCOUNT_ID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $upload_file->BANK_ACCOUNT_ID->CellAttributes() ?>>
<span id="el_upload_file_BANK_ACCOUNT_ID">
<input type="text" data-table="upload_file" data-field="x_BANK_ACCOUNT_ID" name="x_BANK_ACCOUNT_ID" id="x_BANK_ACCOUNT_ID" size="30" placeholder="<?php echo ew_HtmlEncode($upload_file->BANK_ACCOUNT_ID->getPlaceHolder()) ?>" value="<?php echo $upload_file->BANK_ACCOUNT_ID->EditValue ?>"<?php echo $upload_file->BANK_ACCOUNT_ID->EditAttributes() ?>>
</span>
<?php echo $upload_file->BANK_ACCOUNT_ID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($upload_file->UPLOAD_FILE_STATUS_ID->Visible) { // UPLOAD_FILE_STATUS_ID ?>
	<div id="r_UPLOAD_FILE_STATUS_ID" class="form-group">
		<label id="elh_upload_file_UPLOAD_FILE_STATUS_ID" for="x_UPLOAD_FILE_STATUS_ID" class="col-sm-2 control-label ewLabel"><?php echo $upload_file->UPLOAD_FILE_STATUS_ID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $upload_file->UPLOAD_FILE_STATUS_ID->CellAttributes() ?>>
<span id="el_upload_file_UPLOAD_FILE_STATUS_ID">
<input type="text" data-table="upload_file" data-field="x_UPLOAD_FILE_STATUS_ID" name="x_UPLOAD_FILE_STATUS_ID" id="x_UPLOAD_FILE_STATUS_ID" size="30" placeholder="<?php echo ew_HtmlEncode($upload_file->UPLOAD_FILE_STATUS_ID->getPlaceHolder()) ?>" value="<?php echo $upload_file->UPLOAD_FILE_STATUS_ID->EditValue ?>"<?php echo $upload_file->UPLOAD_FILE_STATUS_ID->EditAttributes() ?>>
</span>
<?php echo $upload_file->UPLOAD_FILE_STATUS_ID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($upload_file->DATETIME->Visible) { // DATETIME ?>
	<div id="r_DATETIME" class="form-group">
		<label id="elh_upload_file_DATETIME" for="x_DATETIME" class="col-sm-2 control-label ewLabel"><?php echo $upload_file->DATETIME->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $upload_file->DATETIME->CellAttributes() ?>>
<span id="el_upload_file_DATETIME">
<input type="text" data-table="upload_file" data-field="x_DATETIME" data-format="7" name="x_DATETIME" id="x_DATETIME" placeholder="<?php echo ew_HtmlEncode($upload_file->DATETIME->getPlaceHolder()) ?>" value="<?php echo $upload_file->DATETIME->EditValue ?>"<?php echo $upload_file->DATETIME->EditAttributes() ?>>
</span>
<?php echo $upload_file->DATETIME->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $upload_file_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fupload_fileadd.Init();
</script>
<?php
$upload_file_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$upload_file_add->Page_Terminate();
?>
