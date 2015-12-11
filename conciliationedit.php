<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "conciliationinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$conciliation_edit = NULL; // Initialize page object first

class cconciliation_edit extends cconciliation {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{B2F6402D-7C0A-4760-82AD-045088CEBA18}";

	// Table name
	var $TableName = 'conciliation';

	// Page object name
	var $PageObjName = 'conciliation_edit';

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

		// Table object (conciliation)
		if (!isset($GLOBALS["conciliation"]) || get_class($GLOBALS["conciliation"]) == "cconciliation") {
			$GLOBALS["conciliation"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["conciliation"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'conciliation', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("conciliationlist.php"));
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
		$this->CONCILIATION_ID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $conciliation;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($conciliation);
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
		if (@$_GET["CONCILIATION_ID"] <> "") {
			$this->CONCILIATION_ID->setQueryStringValue($_GET["CONCILIATION_ID"]);
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
		if ($this->CONCILIATION_ID->CurrentValue == "")
			$this->Page_Terminate("conciliationlist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("conciliationlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "conciliationlist.php")
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
		if (!$this->CONCILIATION_ID->FldIsDetailKey)
			$this->CONCILIATION_ID->setFormValue($objForm->GetValue("x_CONCILIATION_ID"));
		if (!$this->UPLOAD_FILE_DETAIL_ID->FldIsDetailKey) {
			$this->UPLOAD_FILE_DETAIL_ID->setFormValue($objForm->GetValue("x_UPLOAD_FILE_DETAIL_ID"));
		}
		if (!$this->DATETIME->FldIsDetailKey) {
			$this->DATETIME->setFormValue($objForm->GetValue("x_DATETIME"));
			$this->DATETIME->CurrentValue = ew_UnFormatDateTime($this->DATETIME->CurrentValue, 7);
		}
		if (!$this->CARD_ID->FldIsDetailKey) {
			$this->CARD_ID->setFormValue($objForm->GetValue("x_CARD_ID"));
		}
		if (!$this->TRANSFERENCE_ID->FldIsDetailKey) {
			$this->TRANSFERENCE_ID->setFormValue($objForm->GetValue("x_TRANSFERENCE_ID"));
		}
		if (!$this->PAY_TYPE_ID->FldIsDetailKey) {
			$this->PAY_TYPE_ID->setFormValue($objForm->GetValue("x_PAY_TYPE_ID"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->CONCILIATION_ID->CurrentValue = $this->CONCILIATION_ID->FormValue;
		$this->UPLOAD_FILE_DETAIL_ID->CurrentValue = $this->UPLOAD_FILE_DETAIL_ID->FormValue;
		$this->DATETIME->CurrentValue = $this->DATETIME->FormValue;
		$this->DATETIME->CurrentValue = ew_UnFormatDateTime($this->DATETIME->CurrentValue, 7);
		$this->CARD_ID->CurrentValue = $this->CARD_ID->FormValue;
		$this->TRANSFERENCE_ID->CurrentValue = $this->TRANSFERENCE_ID->FormValue;
		$this->PAY_TYPE_ID->CurrentValue = $this->PAY_TYPE_ID->FormValue;
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
		$this->CONCILIATION_ID->setDbValue($rs->fields('CONCILIATION_ID'));
		$this->UPLOAD_FILE_DETAIL_ID->setDbValue($rs->fields('UPLOAD_FILE_DETAIL_ID'));
		$this->DATETIME->setDbValue($rs->fields('DATETIME'));
		$this->CARD_ID->setDbValue($rs->fields('CARD_ID'));
		$this->TRANSFERENCE_ID->setDbValue($rs->fields('TRANSFERENCE_ID'));
		$this->PAY_TYPE_ID->setDbValue($rs->fields('PAY_TYPE_ID'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->CONCILIATION_ID->DbValue = $row['CONCILIATION_ID'];
		$this->UPLOAD_FILE_DETAIL_ID->DbValue = $row['UPLOAD_FILE_DETAIL_ID'];
		$this->DATETIME->DbValue = $row['DATETIME'];
		$this->CARD_ID->DbValue = $row['CARD_ID'];
		$this->TRANSFERENCE_ID->DbValue = $row['TRANSFERENCE_ID'];
		$this->PAY_TYPE_ID->DbValue = $row['PAY_TYPE_ID'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// CONCILIATION_ID
		// UPLOAD_FILE_DETAIL_ID
		// DATETIME
		// CARD_ID
		// TRANSFERENCE_ID
		// PAY_TYPE_ID

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// CONCILIATION_ID
		$this->CONCILIATION_ID->ViewValue = $this->CONCILIATION_ID->CurrentValue;
		$this->CONCILIATION_ID->ViewCustomAttributes = "";

		// UPLOAD_FILE_DETAIL_ID
		$this->UPLOAD_FILE_DETAIL_ID->ViewValue = $this->UPLOAD_FILE_DETAIL_ID->CurrentValue;
		$this->UPLOAD_FILE_DETAIL_ID->ViewCustomAttributes = "";

		// DATETIME
		$this->DATETIME->ViewValue = $this->DATETIME->CurrentValue;
		$this->DATETIME->ViewValue = ew_FormatDateTime($this->DATETIME->ViewValue, 7);
		$this->DATETIME->ViewCustomAttributes = "";

		// CARD_ID
		$this->CARD_ID->ViewValue = $this->CARD_ID->CurrentValue;
		$this->CARD_ID->ViewCustomAttributes = "";

		// TRANSFERENCE_ID
		$this->TRANSFERENCE_ID->ViewValue = $this->TRANSFERENCE_ID->CurrentValue;
		$this->TRANSFERENCE_ID->ViewCustomAttributes = "";

		// PAY_TYPE_ID
		$this->PAY_TYPE_ID->ViewValue = $this->PAY_TYPE_ID->CurrentValue;
		$this->PAY_TYPE_ID->ViewCustomAttributes = "";

			// CONCILIATION_ID
			$this->CONCILIATION_ID->LinkCustomAttributes = "";
			$this->CONCILIATION_ID->HrefValue = "";
			$this->CONCILIATION_ID->TooltipValue = "";

			// UPLOAD_FILE_DETAIL_ID
			$this->UPLOAD_FILE_DETAIL_ID->LinkCustomAttributes = "";
			$this->UPLOAD_FILE_DETAIL_ID->HrefValue = "";
			$this->UPLOAD_FILE_DETAIL_ID->TooltipValue = "";

			// DATETIME
			$this->DATETIME->LinkCustomAttributes = "";
			$this->DATETIME->HrefValue = "";
			$this->DATETIME->TooltipValue = "";

			// CARD_ID
			$this->CARD_ID->LinkCustomAttributes = "";
			$this->CARD_ID->HrefValue = "";
			$this->CARD_ID->TooltipValue = "";

			// TRANSFERENCE_ID
			$this->TRANSFERENCE_ID->LinkCustomAttributes = "";
			$this->TRANSFERENCE_ID->HrefValue = "";
			$this->TRANSFERENCE_ID->TooltipValue = "";

			// PAY_TYPE_ID
			$this->PAY_TYPE_ID->LinkCustomAttributes = "";
			$this->PAY_TYPE_ID->HrefValue = "";
			$this->PAY_TYPE_ID->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// CONCILIATION_ID
			$this->CONCILIATION_ID->EditAttrs["class"] = "form-control";
			$this->CONCILIATION_ID->EditCustomAttributes = "";
			$this->CONCILIATION_ID->EditValue = $this->CONCILIATION_ID->CurrentValue;
			$this->CONCILIATION_ID->ViewCustomAttributes = "";

			// UPLOAD_FILE_DETAIL_ID
			$this->UPLOAD_FILE_DETAIL_ID->EditAttrs["class"] = "form-control";
			$this->UPLOAD_FILE_DETAIL_ID->EditCustomAttributes = "";
			$this->UPLOAD_FILE_DETAIL_ID->EditValue = ew_HtmlEncode($this->UPLOAD_FILE_DETAIL_ID->CurrentValue);
			$this->UPLOAD_FILE_DETAIL_ID->PlaceHolder = ew_RemoveHtml($this->UPLOAD_FILE_DETAIL_ID->FldCaption());

			// DATETIME
			$this->DATETIME->EditAttrs["class"] = "form-control";
			$this->DATETIME->EditCustomAttributes = "";
			$this->DATETIME->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->DATETIME->CurrentValue, 7));
			$this->DATETIME->PlaceHolder = ew_RemoveHtml($this->DATETIME->FldCaption());

			// CARD_ID
			$this->CARD_ID->EditAttrs["class"] = "form-control";
			$this->CARD_ID->EditCustomAttributes = "";
			$this->CARD_ID->EditValue = ew_HtmlEncode($this->CARD_ID->CurrentValue);
			$this->CARD_ID->PlaceHolder = ew_RemoveHtml($this->CARD_ID->FldCaption());

			// TRANSFERENCE_ID
			$this->TRANSFERENCE_ID->EditAttrs["class"] = "form-control";
			$this->TRANSFERENCE_ID->EditCustomAttributes = "";
			$this->TRANSFERENCE_ID->EditValue = ew_HtmlEncode($this->TRANSFERENCE_ID->CurrentValue);
			$this->TRANSFERENCE_ID->PlaceHolder = ew_RemoveHtml($this->TRANSFERENCE_ID->FldCaption());

			// PAY_TYPE_ID
			$this->PAY_TYPE_ID->EditAttrs["class"] = "form-control";
			$this->PAY_TYPE_ID->EditCustomAttributes = "";
			$this->PAY_TYPE_ID->EditValue = ew_HtmlEncode($this->PAY_TYPE_ID->CurrentValue);
			$this->PAY_TYPE_ID->PlaceHolder = ew_RemoveHtml($this->PAY_TYPE_ID->FldCaption());

			// Edit refer script
			// CONCILIATION_ID

			$this->CONCILIATION_ID->LinkCustomAttributes = "";
			$this->CONCILIATION_ID->HrefValue = "";

			// UPLOAD_FILE_DETAIL_ID
			$this->UPLOAD_FILE_DETAIL_ID->LinkCustomAttributes = "";
			$this->UPLOAD_FILE_DETAIL_ID->HrefValue = "";

			// DATETIME
			$this->DATETIME->LinkCustomAttributes = "";
			$this->DATETIME->HrefValue = "";

			// CARD_ID
			$this->CARD_ID->LinkCustomAttributes = "";
			$this->CARD_ID->HrefValue = "";

			// TRANSFERENCE_ID
			$this->TRANSFERENCE_ID->LinkCustomAttributes = "";
			$this->TRANSFERENCE_ID->HrefValue = "";

			// PAY_TYPE_ID
			$this->PAY_TYPE_ID->LinkCustomAttributes = "";
			$this->PAY_TYPE_ID->HrefValue = "";
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
		if (!$this->UPLOAD_FILE_DETAIL_ID->FldIsDetailKey && !is_null($this->UPLOAD_FILE_DETAIL_ID->FormValue) && $this->UPLOAD_FILE_DETAIL_ID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->UPLOAD_FILE_DETAIL_ID->FldCaption(), $this->UPLOAD_FILE_DETAIL_ID->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->UPLOAD_FILE_DETAIL_ID->FormValue)) {
			ew_AddMessage($gsFormError, $this->UPLOAD_FILE_DETAIL_ID->FldErrMsg());
		}
		if (!$this->DATETIME->FldIsDetailKey && !is_null($this->DATETIME->FormValue) && $this->DATETIME->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->DATETIME->FldCaption(), $this->DATETIME->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->DATETIME->FormValue)) {
			ew_AddMessage($gsFormError, $this->DATETIME->FldErrMsg());
		}
		if (!ew_CheckInteger($this->CARD_ID->FormValue)) {
			ew_AddMessage($gsFormError, $this->CARD_ID->FldErrMsg());
		}
		if (!ew_CheckInteger($this->TRANSFERENCE_ID->FormValue)) {
			ew_AddMessage($gsFormError, $this->TRANSFERENCE_ID->FldErrMsg());
		}
		if (!$this->PAY_TYPE_ID->FldIsDetailKey && !is_null($this->PAY_TYPE_ID->FormValue) && $this->PAY_TYPE_ID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->PAY_TYPE_ID->FldCaption(), $this->PAY_TYPE_ID->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->PAY_TYPE_ID->FormValue)) {
			ew_AddMessage($gsFormError, $this->PAY_TYPE_ID->FldErrMsg());
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

			// UPLOAD_FILE_DETAIL_ID
			$this->UPLOAD_FILE_DETAIL_ID->SetDbValueDef($rsnew, $this->UPLOAD_FILE_DETAIL_ID->CurrentValue, 0, $this->UPLOAD_FILE_DETAIL_ID->ReadOnly);

			// DATETIME
			$this->DATETIME->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->DATETIME->CurrentValue, 7), ew_CurrentDate(), $this->DATETIME->ReadOnly);

			// CARD_ID
			$this->CARD_ID->SetDbValueDef($rsnew, $this->CARD_ID->CurrentValue, NULL, $this->CARD_ID->ReadOnly);

			// TRANSFERENCE_ID
			$this->TRANSFERENCE_ID->SetDbValueDef($rsnew, $this->TRANSFERENCE_ID->CurrentValue, NULL, $this->TRANSFERENCE_ID->ReadOnly);

			// PAY_TYPE_ID
			$this->PAY_TYPE_ID->SetDbValueDef($rsnew, $this->PAY_TYPE_ID->CurrentValue, 0, $this->PAY_TYPE_ID->ReadOnly);

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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("conciliationlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($conciliation_edit)) $conciliation_edit = new cconciliation_edit();

// Page init
$conciliation_edit->Page_Init();

// Page main
$conciliation_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$conciliation_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fconciliationedit = new ew_Form("fconciliationedit", "edit");

// Validate form
fconciliationedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_UPLOAD_FILE_DETAIL_ID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $conciliation->UPLOAD_FILE_DETAIL_ID->FldCaption(), $conciliation->UPLOAD_FILE_DETAIL_ID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_UPLOAD_FILE_DETAIL_ID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($conciliation->UPLOAD_FILE_DETAIL_ID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_DATETIME");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $conciliation->DATETIME->FldCaption(), $conciliation->DATETIME->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_DATETIME");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($conciliation->DATETIME->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_CARD_ID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($conciliation->CARD_ID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_TRANSFERENCE_ID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($conciliation->TRANSFERENCE_ID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_PAY_TYPE_ID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $conciliation->PAY_TYPE_ID->FldCaption(), $conciliation->PAY_TYPE_ID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_PAY_TYPE_ID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($conciliation->PAY_TYPE_ID->FldErrMsg()) ?>");

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
fconciliationedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fconciliationedit.ValidateRequired = true;
<?php } else { ?>
fconciliationedit.ValidateRequired = false; 
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
<?php $conciliation_edit->ShowPageHeader(); ?>
<?php
$conciliation_edit->ShowMessage();
?>
<form name="fconciliationedit" id="fconciliationedit" class="<?php echo $conciliation_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($conciliation_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $conciliation_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="conciliation">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($conciliation->CONCILIATION_ID->Visible) { // CONCILIATION_ID ?>
	<div id="r_CONCILIATION_ID" class="form-group">
		<label id="elh_conciliation_CONCILIATION_ID" class="col-sm-2 control-label ewLabel"><?php echo $conciliation->CONCILIATION_ID->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $conciliation->CONCILIATION_ID->CellAttributes() ?>>
<span id="el_conciliation_CONCILIATION_ID">
<span<?php echo $conciliation->CONCILIATION_ID->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $conciliation->CONCILIATION_ID->EditValue ?></p></span>
</span>
<input type="hidden" data-table="conciliation" data-field="x_CONCILIATION_ID" name="x_CONCILIATION_ID" id="x_CONCILIATION_ID" value="<?php echo ew_HtmlEncode($conciliation->CONCILIATION_ID->CurrentValue) ?>">
<?php echo $conciliation->CONCILIATION_ID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($conciliation->UPLOAD_FILE_DETAIL_ID->Visible) { // UPLOAD_FILE_DETAIL_ID ?>
	<div id="r_UPLOAD_FILE_DETAIL_ID" class="form-group">
		<label id="elh_conciliation_UPLOAD_FILE_DETAIL_ID" for="x_UPLOAD_FILE_DETAIL_ID" class="col-sm-2 control-label ewLabel"><?php echo $conciliation->UPLOAD_FILE_DETAIL_ID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $conciliation->UPLOAD_FILE_DETAIL_ID->CellAttributes() ?>>
<span id="el_conciliation_UPLOAD_FILE_DETAIL_ID">
<input type="text" data-table="conciliation" data-field="x_UPLOAD_FILE_DETAIL_ID" name="x_UPLOAD_FILE_DETAIL_ID" id="x_UPLOAD_FILE_DETAIL_ID" size="30" placeholder="<?php echo ew_HtmlEncode($conciliation->UPLOAD_FILE_DETAIL_ID->getPlaceHolder()) ?>" value="<?php echo $conciliation->UPLOAD_FILE_DETAIL_ID->EditValue ?>"<?php echo $conciliation->UPLOAD_FILE_DETAIL_ID->EditAttributes() ?>>
</span>
<?php echo $conciliation->UPLOAD_FILE_DETAIL_ID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($conciliation->DATETIME->Visible) { // DATETIME ?>
	<div id="r_DATETIME" class="form-group">
		<label id="elh_conciliation_DATETIME" for="x_DATETIME" class="col-sm-2 control-label ewLabel"><?php echo $conciliation->DATETIME->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $conciliation->DATETIME->CellAttributes() ?>>
<span id="el_conciliation_DATETIME">
<input type="text" data-table="conciliation" data-field="x_DATETIME" data-format="7" name="x_DATETIME" id="x_DATETIME" placeholder="<?php echo ew_HtmlEncode($conciliation->DATETIME->getPlaceHolder()) ?>" value="<?php echo $conciliation->DATETIME->EditValue ?>"<?php echo $conciliation->DATETIME->EditAttributes() ?>>
</span>
<?php echo $conciliation->DATETIME->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($conciliation->CARD_ID->Visible) { // CARD_ID ?>
	<div id="r_CARD_ID" class="form-group">
		<label id="elh_conciliation_CARD_ID" for="x_CARD_ID" class="col-sm-2 control-label ewLabel"><?php echo $conciliation->CARD_ID->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $conciliation->CARD_ID->CellAttributes() ?>>
<span id="el_conciliation_CARD_ID">
<input type="text" data-table="conciliation" data-field="x_CARD_ID" name="x_CARD_ID" id="x_CARD_ID" size="30" placeholder="<?php echo ew_HtmlEncode($conciliation->CARD_ID->getPlaceHolder()) ?>" value="<?php echo $conciliation->CARD_ID->EditValue ?>"<?php echo $conciliation->CARD_ID->EditAttributes() ?>>
</span>
<?php echo $conciliation->CARD_ID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($conciliation->TRANSFERENCE_ID->Visible) { // TRANSFERENCE_ID ?>
	<div id="r_TRANSFERENCE_ID" class="form-group">
		<label id="elh_conciliation_TRANSFERENCE_ID" for="x_TRANSFERENCE_ID" class="col-sm-2 control-label ewLabel"><?php echo $conciliation->TRANSFERENCE_ID->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $conciliation->TRANSFERENCE_ID->CellAttributes() ?>>
<span id="el_conciliation_TRANSFERENCE_ID">
<input type="text" data-table="conciliation" data-field="x_TRANSFERENCE_ID" name="x_TRANSFERENCE_ID" id="x_TRANSFERENCE_ID" size="30" placeholder="<?php echo ew_HtmlEncode($conciliation->TRANSFERENCE_ID->getPlaceHolder()) ?>" value="<?php echo $conciliation->TRANSFERENCE_ID->EditValue ?>"<?php echo $conciliation->TRANSFERENCE_ID->EditAttributes() ?>>
</span>
<?php echo $conciliation->TRANSFERENCE_ID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($conciliation->PAY_TYPE_ID->Visible) { // PAY_TYPE_ID ?>
	<div id="r_PAY_TYPE_ID" class="form-group">
		<label id="elh_conciliation_PAY_TYPE_ID" for="x_PAY_TYPE_ID" class="col-sm-2 control-label ewLabel"><?php echo $conciliation->PAY_TYPE_ID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $conciliation->PAY_TYPE_ID->CellAttributes() ?>>
<span id="el_conciliation_PAY_TYPE_ID">
<input type="text" data-table="conciliation" data-field="x_PAY_TYPE_ID" name="x_PAY_TYPE_ID" id="x_PAY_TYPE_ID" size="30" placeholder="<?php echo ew_HtmlEncode($conciliation->PAY_TYPE_ID->getPlaceHolder()) ?>" value="<?php echo $conciliation->PAY_TYPE_ID->EditValue ?>"<?php echo $conciliation->PAY_TYPE_ID->EditAttributes() ?>>
</span>
<?php echo $conciliation->PAY_TYPE_ID->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $conciliation_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fconciliationedit.Init();
</script>
<?php
$conciliation_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$conciliation_edit->Page_Terminate();
?>
