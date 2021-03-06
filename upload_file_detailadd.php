<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "upload_file_detailinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$upload_file_detail_add = NULL; // Initialize page object first

class cupload_file_detail_add extends cupload_file_detail {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{B2F6402D-7C0A-4760-82AD-045088CEBA18}";

	// Table name
	var $TableName = 'upload_file_detail';

	// Page object name
	var $PageObjName = 'upload_file_detail_add';

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

		// Table object (upload_file_detail)
		if (!isset($GLOBALS["upload_file_detail"]) || get_class($GLOBALS["upload_file_detail"]) == "cupload_file_detail") {
			$GLOBALS["upload_file_detail"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["upload_file_detail"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'upload_file_detail', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("upload_file_detaillist.php"));
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
		global $EW_EXPORT, $upload_file_detail;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($upload_file_detail);
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
			if (@$_GET["UPLOAD_FILE_DETAIL_ID"] != "") {
				$this->UPLOAD_FILE_DETAIL_ID->setQueryStringValue($_GET["UPLOAD_FILE_DETAIL_ID"]);
				$this->setKey("UPLOAD_FILE_DETAIL_ID", $this->UPLOAD_FILE_DETAIL_ID->CurrentValue); // Set up key
			} else {
				$this->setKey("UPLOAD_FILE_DETAIL_ID", ""); // Clear key
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
					$this->Page_Terminate("upload_file_detaillist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "upload_file_detaillist.php")
						$sReturnUrl = $this->AddMasterUrl($this->GetListUrl()); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "upload_file_detailview.php")
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
		$this->DESCRIPTION->CurrentValue = NULL;
		$this->DESCRIPTION->OldValue = $this->DESCRIPTION->CurrentValue;
		$this->DATE->CurrentValue = NULL;
		$this->DATE->OldValue = $this->DATE->CurrentValue;
		$this->AMOUNT->CurrentValue = NULL;
		$this->AMOUNT->OldValue = $this->AMOUNT->CurrentValue;
		$this->UPLOAD_FILE_ID->CurrentValue = NULL;
		$this->UPLOAD_FILE_ID->OldValue = $this->UPLOAD_FILE_ID->CurrentValue;
		$this->UPLOAD_FILE_DETAIL_STATUS_ID->CurrentValue = NULL;
		$this->UPLOAD_FILE_DETAIL_STATUS_ID->OldValue = $this->UPLOAD_FILE_DETAIL_STATUS_ID->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->CODE->FldIsDetailKey) {
			$this->CODE->setFormValue($objForm->GetValue("x_CODE"));
		}
		if (!$this->DESCRIPTION->FldIsDetailKey) {
			$this->DESCRIPTION->setFormValue($objForm->GetValue("x_DESCRIPTION"));
		}
		if (!$this->DATE->FldIsDetailKey) {
			$this->DATE->setFormValue($objForm->GetValue("x_DATE"));
			$this->DATE->CurrentValue = ew_UnFormatDateTime($this->DATE->CurrentValue, 7);
		}
		if (!$this->AMOUNT->FldIsDetailKey) {
			$this->AMOUNT->setFormValue($objForm->GetValue("x_AMOUNT"));
		}
		if (!$this->UPLOAD_FILE_ID->FldIsDetailKey) {
			$this->UPLOAD_FILE_ID->setFormValue($objForm->GetValue("x_UPLOAD_FILE_ID"));
		}
		if (!$this->UPLOAD_FILE_DETAIL_STATUS_ID->FldIsDetailKey) {
			$this->UPLOAD_FILE_DETAIL_STATUS_ID->setFormValue($objForm->GetValue("x_UPLOAD_FILE_DETAIL_STATUS_ID"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->CODE->CurrentValue = $this->CODE->FormValue;
		$this->DESCRIPTION->CurrentValue = $this->DESCRIPTION->FormValue;
		$this->DATE->CurrentValue = $this->DATE->FormValue;
		$this->DATE->CurrentValue = ew_UnFormatDateTime($this->DATE->CurrentValue, 7);
		$this->AMOUNT->CurrentValue = $this->AMOUNT->FormValue;
		$this->UPLOAD_FILE_ID->CurrentValue = $this->UPLOAD_FILE_ID->FormValue;
		$this->UPLOAD_FILE_DETAIL_STATUS_ID->CurrentValue = $this->UPLOAD_FILE_DETAIL_STATUS_ID->FormValue;
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
		$this->UPLOAD_FILE_DETAIL_ID->setDbValue($rs->fields('UPLOAD_FILE_DETAIL_ID'));
		$this->CODE->setDbValue($rs->fields('CODE'));
		$this->DESCRIPTION->setDbValue($rs->fields('DESCRIPTION'));
		$this->DATE->setDbValue($rs->fields('DATE'));
		$this->AMOUNT->setDbValue($rs->fields('AMOUNT'));
		$this->UPLOAD_FILE_ID->setDbValue($rs->fields('UPLOAD_FILE_ID'));
		$this->UPLOAD_FILE_DETAIL_STATUS_ID->setDbValue($rs->fields('UPLOAD_FILE_DETAIL_STATUS_ID'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->UPLOAD_FILE_DETAIL_ID->DbValue = $row['UPLOAD_FILE_DETAIL_ID'];
		$this->CODE->DbValue = $row['CODE'];
		$this->DESCRIPTION->DbValue = $row['DESCRIPTION'];
		$this->DATE->DbValue = $row['DATE'];
		$this->AMOUNT->DbValue = $row['AMOUNT'];
		$this->UPLOAD_FILE_ID->DbValue = $row['UPLOAD_FILE_ID'];
		$this->UPLOAD_FILE_DETAIL_STATUS_ID->DbValue = $row['UPLOAD_FILE_DETAIL_STATUS_ID'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("UPLOAD_FILE_DETAIL_ID")) <> "")
			$this->UPLOAD_FILE_DETAIL_ID->CurrentValue = $this->getKey("UPLOAD_FILE_DETAIL_ID"); // UPLOAD_FILE_DETAIL_ID
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
		// Convert decimal values if posted back

		if ($this->CODE->FormValue == $this->CODE->CurrentValue && is_numeric(ew_StrToFloat($this->CODE->CurrentValue)))
			$this->CODE->CurrentValue = ew_StrToFloat($this->CODE->CurrentValue);

		// Convert decimal values if posted back
		if ($this->AMOUNT->FormValue == $this->AMOUNT->CurrentValue && is_numeric(ew_StrToFloat($this->AMOUNT->CurrentValue)))
			$this->AMOUNT->CurrentValue = ew_StrToFloat($this->AMOUNT->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// UPLOAD_FILE_DETAIL_ID
		// CODE
		// DESCRIPTION
		// DATE
		// AMOUNT
		// UPLOAD_FILE_ID
		// UPLOAD_FILE_DETAIL_STATUS_ID

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// UPLOAD_FILE_DETAIL_ID
		$this->UPLOAD_FILE_DETAIL_ID->ViewValue = $this->UPLOAD_FILE_DETAIL_ID->CurrentValue;
		$this->UPLOAD_FILE_DETAIL_ID->ViewCustomAttributes = "";

		// CODE
		$this->CODE->ViewValue = $this->CODE->CurrentValue;
		$this->CODE->ViewCustomAttributes = "";

		// DESCRIPTION
		$this->DESCRIPTION->ViewValue = $this->DESCRIPTION->CurrentValue;
		$this->DESCRIPTION->ViewCustomAttributes = "";

		// DATE
		$this->DATE->ViewValue = $this->DATE->CurrentValue;
		$this->DATE->ViewValue = ew_FormatDateTime($this->DATE->ViewValue, 7);
		$this->DATE->ViewCustomAttributes = "";

		// AMOUNT
		$this->AMOUNT->ViewValue = $this->AMOUNT->CurrentValue;
		$this->AMOUNT->ViewCustomAttributes = "";

		// UPLOAD_FILE_ID
		$this->UPLOAD_FILE_ID->ViewValue = $this->UPLOAD_FILE_ID->CurrentValue;
		$this->UPLOAD_FILE_ID->ViewCustomAttributes = "";

		// UPLOAD_FILE_DETAIL_STATUS_ID
		$this->UPLOAD_FILE_DETAIL_STATUS_ID->ViewValue = $this->UPLOAD_FILE_DETAIL_STATUS_ID->CurrentValue;
		$this->UPLOAD_FILE_DETAIL_STATUS_ID->ViewCustomAttributes = "";

			// CODE
			$this->CODE->LinkCustomAttributes = "";
			$this->CODE->HrefValue = "";
			$this->CODE->TooltipValue = "";

			// DESCRIPTION
			$this->DESCRIPTION->LinkCustomAttributes = "";
			$this->DESCRIPTION->HrefValue = "";
			$this->DESCRIPTION->TooltipValue = "";

			// DATE
			$this->DATE->LinkCustomAttributes = "";
			$this->DATE->HrefValue = "";
			$this->DATE->TooltipValue = "";

			// AMOUNT
			$this->AMOUNT->LinkCustomAttributes = "";
			$this->AMOUNT->HrefValue = "";
			$this->AMOUNT->TooltipValue = "";

			// UPLOAD_FILE_ID
			$this->UPLOAD_FILE_ID->LinkCustomAttributes = "";
			$this->UPLOAD_FILE_ID->HrefValue = "";
			$this->UPLOAD_FILE_ID->TooltipValue = "";

			// UPLOAD_FILE_DETAIL_STATUS_ID
			$this->UPLOAD_FILE_DETAIL_STATUS_ID->LinkCustomAttributes = "";
			$this->UPLOAD_FILE_DETAIL_STATUS_ID->HrefValue = "";
			$this->UPLOAD_FILE_DETAIL_STATUS_ID->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// CODE
			$this->CODE->EditAttrs["class"] = "form-control";
			$this->CODE->EditCustomAttributes = "";
			$this->CODE->EditValue = ew_HtmlEncode($this->CODE->CurrentValue);
			$this->CODE->PlaceHolder = ew_RemoveHtml($this->CODE->FldCaption());
			if (strval($this->CODE->EditValue) <> "" && is_numeric($this->CODE->EditValue)) $this->CODE->EditValue = ew_FormatNumber($this->CODE->EditValue, -2, -1, -2, 0);

			// DESCRIPTION
			$this->DESCRIPTION->EditAttrs["class"] = "form-control";
			$this->DESCRIPTION->EditCustomAttributes = "";
			$this->DESCRIPTION->EditValue = ew_HtmlEncode($this->DESCRIPTION->CurrentValue);
			$this->DESCRIPTION->PlaceHolder = ew_RemoveHtml($this->DESCRIPTION->FldCaption());

			// DATE
			$this->DATE->EditAttrs["class"] = "form-control";
			$this->DATE->EditCustomAttributes = "";
			$this->DATE->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->DATE->CurrentValue, 7));
			$this->DATE->PlaceHolder = ew_RemoveHtml($this->DATE->FldCaption());

			// AMOUNT
			$this->AMOUNT->EditAttrs["class"] = "form-control";
			$this->AMOUNT->EditCustomAttributes = "";
			$this->AMOUNT->EditValue = ew_HtmlEncode($this->AMOUNT->CurrentValue);
			$this->AMOUNT->PlaceHolder = ew_RemoveHtml($this->AMOUNT->FldCaption());
			if (strval($this->AMOUNT->EditValue) <> "" && is_numeric($this->AMOUNT->EditValue)) $this->AMOUNT->EditValue = ew_FormatNumber($this->AMOUNT->EditValue, -2, -1, -2, 0);

			// UPLOAD_FILE_ID
			$this->UPLOAD_FILE_ID->EditAttrs["class"] = "form-control";
			$this->UPLOAD_FILE_ID->EditCustomAttributes = "";
			$this->UPLOAD_FILE_ID->EditValue = ew_HtmlEncode($this->UPLOAD_FILE_ID->CurrentValue);
			$this->UPLOAD_FILE_ID->PlaceHolder = ew_RemoveHtml($this->UPLOAD_FILE_ID->FldCaption());

			// UPLOAD_FILE_DETAIL_STATUS_ID
			$this->UPLOAD_FILE_DETAIL_STATUS_ID->EditAttrs["class"] = "form-control";
			$this->UPLOAD_FILE_DETAIL_STATUS_ID->EditCustomAttributes = "";
			$this->UPLOAD_FILE_DETAIL_STATUS_ID->EditValue = ew_HtmlEncode($this->UPLOAD_FILE_DETAIL_STATUS_ID->CurrentValue);
			$this->UPLOAD_FILE_DETAIL_STATUS_ID->PlaceHolder = ew_RemoveHtml($this->UPLOAD_FILE_DETAIL_STATUS_ID->FldCaption());

			// Add refer script
			// CODE

			$this->CODE->LinkCustomAttributes = "";
			$this->CODE->HrefValue = "";

			// DESCRIPTION
			$this->DESCRIPTION->LinkCustomAttributes = "";
			$this->DESCRIPTION->HrefValue = "";

			// DATE
			$this->DATE->LinkCustomAttributes = "";
			$this->DATE->HrefValue = "";

			// AMOUNT
			$this->AMOUNT->LinkCustomAttributes = "";
			$this->AMOUNT->HrefValue = "";

			// UPLOAD_FILE_ID
			$this->UPLOAD_FILE_ID->LinkCustomAttributes = "";
			$this->UPLOAD_FILE_ID->HrefValue = "";

			// UPLOAD_FILE_DETAIL_STATUS_ID
			$this->UPLOAD_FILE_DETAIL_STATUS_ID->LinkCustomAttributes = "";
			$this->UPLOAD_FILE_DETAIL_STATUS_ID->HrefValue = "";
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
		if (!ew_CheckNumber($this->CODE->FormValue)) {
			ew_AddMessage($gsFormError, $this->CODE->FldErrMsg());
		}
		if (!$this->DESCRIPTION->FldIsDetailKey && !is_null($this->DESCRIPTION->FormValue) && $this->DESCRIPTION->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->DESCRIPTION->FldCaption(), $this->DESCRIPTION->ReqErrMsg));
		}
		if (!$this->DATE->FldIsDetailKey && !is_null($this->DATE->FormValue) && $this->DATE->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->DATE->FldCaption(), $this->DATE->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->DATE->FormValue)) {
			ew_AddMessage($gsFormError, $this->DATE->FldErrMsg());
		}
		if (!$this->AMOUNT->FldIsDetailKey && !is_null($this->AMOUNT->FormValue) && $this->AMOUNT->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->AMOUNT->FldCaption(), $this->AMOUNT->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->AMOUNT->FormValue)) {
			ew_AddMessage($gsFormError, $this->AMOUNT->FldErrMsg());
		}
		if (!$this->UPLOAD_FILE_ID->FldIsDetailKey && !is_null($this->UPLOAD_FILE_ID->FormValue) && $this->UPLOAD_FILE_ID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->UPLOAD_FILE_ID->FldCaption(), $this->UPLOAD_FILE_ID->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->UPLOAD_FILE_ID->FormValue)) {
			ew_AddMessage($gsFormError, $this->UPLOAD_FILE_ID->FldErrMsg());
		}
		if (!$this->UPLOAD_FILE_DETAIL_STATUS_ID->FldIsDetailKey && !is_null($this->UPLOAD_FILE_DETAIL_STATUS_ID->FormValue) && $this->UPLOAD_FILE_DETAIL_STATUS_ID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->UPLOAD_FILE_DETAIL_STATUS_ID->FldCaption(), $this->UPLOAD_FILE_DETAIL_STATUS_ID->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->UPLOAD_FILE_DETAIL_STATUS_ID->FormValue)) {
			ew_AddMessage($gsFormError, $this->UPLOAD_FILE_DETAIL_STATUS_ID->FldErrMsg());
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
		$this->CODE->SetDbValueDef($rsnew, $this->CODE->CurrentValue, 0, FALSE);

		// DESCRIPTION
		$this->DESCRIPTION->SetDbValueDef($rsnew, $this->DESCRIPTION->CurrentValue, "", FALSE);

		// DATE
		$this->DATE->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->DATE->CurrentValue, 7), ew_CurrentDate(), FALSE);

		// AMOUNT
		$this->AMOUNT->SetDbValueDef($rsnew, $this->AMOUNT->CurrentValue, 0, FALSE);

		// UPLOAD_FILE_ID
		$this->UPLOAD_FILE_ID->SetDbValueDef($rsnew, $this->UPLOAD_FILE_ID->CurrentValue, 0, FALSE);

		// UPLOAD_FILE_DETAIL_STATUS_ID
		$this->UPLOAD_FILE_DETAIL_STATUS_ID->SetDbValueDef($rsnew, $this->UPLOAD_FILE_DETAIL_STATUS_ID->CurrentValue, 0, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->UPLOAD_FILE_DETAIL_ID->setDbValue($conn->Insert_ID());
				$rsnew['UPLOAD_FILE_DETAIL_ID'] = $this->UPLOAD_FILE_DETAIL_ID->DbValue;
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("upload_file_detaillist.php"), "", $this->TableVar, TRUE);
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
if (!isset($upload_file_detail_add)) $upload_file_detail_add = new cupload_file_detail_add();

// Page init
$upload_file_detail_add->Page_Init();

// Page main
$upload_file_detail_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$upload_file_detail_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fupload_file_detailadd = new ew_Form("fupload_file_detailadd", "add");

// Validate form
fupload_file_detailadd.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $upload_file_detail->CODE->FldCaption(), $upload_file_detail->CODE->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_CODE");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($upload_file_detail->CODE->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_DESCRIPTION");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $upload_file_detail->DESCRIPTION->FldCaption(), $upload_file_detail->DESCRIPTION->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_DATE");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $upload_file_detail->DATE->FldCaption(), $upload_file_detail->DATE->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_DATE");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($upload_file_detail->DATE->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_AMOUNT");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $upload_file_detail->AMOUNT->FldCaption(), $upload_file_detail->AMOUNT->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_AMOUNT");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($upload_file_detail->AMOUNT->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_UPLOAD_FILE_ID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $upload_file_detail->UPLOAD_FILE_ID->FldCaption(), $upload_file_detail->UPLOAD_FILE_ID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_UPLOAD_FILE_ID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($upload_file_detail->UPLOAD_FILE_ID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_UPLOAD_FILE_DETAIL_STATUS_ID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $upload_file_detail->UPLOAD_FILE_DETAIL_STATUS_ID->FldCaption(), $upload_file_detail->UPLOAD_FILE_DETAIL_STATUS_ID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_UPLOAD_FILE_DETAIL_STATUS_ID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($upload_file_detail->UPLOAD_FILE_DETAIL_STATUS_ID->FldErrMsg()) ?>");

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
fupload_file_detailadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fupload_file_detailadd.ValidateRequired = true;
<?php } else { ?>
fupload_file_detailadd.ValidateRequired = false; 
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
<?php $upload_file_detail_add->ShowPageHeader(); ?>
<?php
$upload_file_detail_add->ShowMessage();
?>
<form name="fupload_file_detailadd" id="fupload_file_detailadd" class="<?php echo $upload_file_detail_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($upload_file_detail_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $upload_file_detail_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="upload_file_detail">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($upload_file_detail->CODE->Visible) { // CODE ?>
	<div id="r_CODE" class="form-group">
		<label id="elh_upload_file_detail_CODE" for="x_CODE" class="col-sm-2 control-label ewLabel"><?php echo $upload_file_detail->CODE->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $upload_file_detail->CODE->CellAttributes() ?>>
<span id="el_upload_file_detail_CODE">
<input type="text" data-table="upload_file_detail" data-field="x_CODE" name="x_CODE" id="x_CODE" size="30" placeholder="<?php echo ew_HtmlEncode($upload_file_detail->CODE->getPlaceHolder()) ?>" value="<?php echo $upload_file_detail->CODE->EditValue ?>"<?php echo $upload_file_detail->CODE->EditAttributes() ?>>
</span>
<?php echo $upload_file_detail->CODE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($upload_file_detail->DESCRIPTION->Visible) { // DESCRIPTION ?>
	<div id="r_DESCRIPTION" class="form-group">
		<label id="elh_upload_file_detail_DESCRIPTION" for="x_DESCRIPTION" class="col-sm-2 control-label ewLabel"><?php echo $upload_file_detail->DESCRIPTION->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $upload_file_detail->DESCRIPTION->CellAttributes() ?>>
<span id="el_upload_file_detail_DESCRIPTION">
<input type="text" data-table="upload_file_detail" data-field="x_DESCRIPTION" name="x_DESCRIPTION" id="x_DESCRIPTION" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($upload_file_detail->DESCRIPTION->getPlaceHolder()) ?>" value="<?php echo $upload_file_detail->DESCRIPTION->EditValue ?>"<?php echo $upload_file_detail->DESCRIPTION->EditAttributes() ?>>
</span>
<?php echo $upload_file_detail->DESCRIPTION->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($upload_file_detail->DATE->Visible) { // DATE ?>
	<div id="r_DATE" class="form-group">
		<label id="elh_upload_file_detail_DATE" for="x_DATE" class="col-sm-2 control-label ewLabel"><?php echo $upload_file_detail->DATE->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $upload_file_detail->DATE->CellAttributes() ?>>
<span id="el_upload_file_detail_DATE">
<input type="text" data-table="upload_file_detail" data-field="x_DATE" data-format="7" name="x_DATE" id="x_DATE" placeholder="<?php echo ew_HtmlEncode($upload_file_detail->DATE->getPlaceHolder()) ?>" value="<?php echo $upload_file_detail->DATE->EditValue ?>"<?php echo $upload_file_detail->DATE->EditAttributes() ?>>
</span>
<?php echo $upload_file_detail->DATE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($upload_file_detail->AMOUNT->Visible) { // AMOUNT ?>
	<div id="r_AMOUNT" class="form-group">
		<label id="elh_upload_file_detail_AMOUNT" for="x_AMOUNT" class="col-sm-2 control-label ewLabel"><?php echo $upload_file_detail->AMOUNT->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $upload_file_detail->AMOUNT->CellAttributes() ?>>
<span id="el_upload_file_detail_AMOUNT">
<input type="text" data-table="upload_file_detail" data-field="x_AMOUNT" name="x_AMOUNT" id="x_AMOUNT" size="30" placeholder="<?php echo ew_HtmlEncode($upload_file_detail->AMOUNT->getPlaceHolder()) ?>" value="<?php echo $upload_file_detail->AMOUNT->EditValue ?>"<?php echo $upload_file_detail->AMOUNT->EditAttributes() ?>>
</span>
<?php echo $upload_file_detail->AMOUNT->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($upload_file_detail->UPLOAD_FILE_ID->Visible) { // UPLOAD_FILE_ID ?>
	<div id="r_UPLOAD_FILE_ID" class="form-group">
		<label id="elh_upload_file_detail_UPLOAD_FILE_ID" for="x_UPLOAD_FILE_ID" class="col-sm-2 control-label ewLabel"><?php echo $upload_file_detail->UPLOAD_FILE_ID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $upload_file_detail->UPLOAD_FILE_ID->CellAttributes() ?>>
<span id="el_upload_file_detail_UPLOAD_FILE_ID">
<input type="text" data-table="upload_file_detail" data-field="x_UPLOAD_FILE_ID" name="x_UPLOAD_FILE_ID" id="x_UPLOAD_FILE_ID" size="30" placeholder="<?php echo ew_HtmlEncode($upload_file_detail->UPLOAD_FILE_ID->getPlaceHolder()) ?>" value="<?php echo $upload_file_detail->UPLOAD_FILE_ID->EditValue ?>"<?php echo $upload_file_detail->UPLOAD_FILE_ID->EditAttributes() ?>>
</span>
<?php echo $upload_file_detail->UPLOAD_FILE_ID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($upload_file_detail->UPLOAD_FILE_DETAIL_STATUS_ID->Visible) { // UPLOAD_FILE_DETAIL_STATUS_ID ?>
	<div id="r_UPLOAD_FILE_DETAIL_STATUS_ID" class="form-group">
		<label id="elh_upload_file_detail_UPLOAD_FILE_DETAIL_STATUS_ID" for="x_UPLOAD_FILE_DETAIL_STATUS_ID" class="col-sm-2 control-label ewLabel"><?php echo $upload_file_detail->UPLOAD_FILE_DETAIL_STATUS_ID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $upload_file_detail->UPLOAD_FILE_DETAIL_STATUS_ID->CellAttributes() ?>>
<span id="el_upload_file_detail_UPLOAD_FILE_DETAIL_STATUS_ID">
<input type="text" data-table="upload_file_detail" data-field="x_UPLOAD_FILE_DETAIL_STATUS_ID" name="x_UPLOAD_FILE_DETAIL_STATUS_ID" id="x_UPLOAD_FILE_DETAIL_STATUS_ID" size="30" placeholder="<?php echo ew_HtmlEncode($upload_file_detail->UPLOAD_FILE_DETAIL_STATUS_ID->getPlaceHolder()) ?>" value="<?php echo $upload_file_detail->UPLOAD_FILE_DETAIL_STATUS_ID->EditValue ?>"<?php echo $upload_file_detail->UPLOAD_FILE_DETAIL_STATUS_ID->EditAttributes() ?>>
</span>
<?php echo $upload_file_detail->UPLOAD_FILE_DETAIL_STATUS_ID->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $upload_file_detail_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fupload_file_detailadd.Init();
</script>
<?php
$upload_file_detail_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$upload_file_detail_add->Page_Terminate();
?>
