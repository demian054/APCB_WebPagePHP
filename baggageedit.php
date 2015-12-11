<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "baggageinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$baggage_edit = NULL; // Initialize page object first

class cbaggage_edit extends cbaggage {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{B2F6402D-7C0A-4760-82AD-045088CEBA18}";

	// Table name
	var $TableName = 'baggage';

	// Page object name
	var $PageObjName = 'baggage_edit';

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

		// Table object (baggage)
		if (!isset($GLOBALS["baggage"]) || get_class($GLOBALS["baggage"]) == "cbaggage") {
			$GLOBALS["baggage"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["baggage"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'baggage', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("baggagelist.php"));
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
		$this->BAGGAGE_ID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $baggage;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($baggage);
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
		if (@$_GET["BAGGAGE_ID"] <> "") {
			$this->BAGGAGE_ID->setQueryStringValue($_GET["BAGGAGE_ID"]);
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
		if ($this->BAGGAGE_ID->CurrentValue == "")
			$this->Page_Terminate("baggagelist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("baggagelist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "baggagelist.php")
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
		if (!$this->BAGGAGE_ID->FldIsDetailKey)
			$this->BAGGAGE_ID->setFormValue($objForm->GetValue("x_BAGGAGE_ID"));
		if (!$this->BOARDING_ID->FldIsDetailKey) {
			$this->BOARDING_ID->setFormValue($objForm->GetValue("x_BOARDING_ID"));
		}
		if (!$this->PASSANGER_ID->FldIsDetailKey) {
			$this->PASSANGER_ID->setFormValue($objForm->GetValue("x_PASSANGER_ID"));
		}
		if (!$this->WEIGHT->FldIsDetailKey) {
			$this->WEIGHT->setFormValue($objForm->GetValue("x_WEIGHT"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->BAGGAGE_ID->CurrentValue = $this->BAGGAGE_ID->FormValue;
		$this->BOARDING_ID->CurrentValue = $this->BOARDING_ID->FormValue;
		$this->PASSANGER_ID->CurrentValue = $this->PASSANGER_ID->FormValue;
		$this->WEIGHT->CurrentValue = $this->WEIGHT->FormValue;
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
		$this->BAGGAGE_ID->setDbValue($rs->fields('BAGGAGE_ID'));
		$this->BOARDING_ID->setDbValue($rs->fields('BOARDING_ID'));
		$this->PASSANGER_ID->setDbValue($rs->fields('PASSANGER_ID'));
		$this->WEIGHT->setDbValue($rs->fields('WEIGHT'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->BAGGAGE_ID->DbValue = $row['BAGGAGE_ID'];
		$this->BOARDING_ID->DbValue = $row['BOARDING_ID'];
		$this->PASSANGER_ID->DbValue = $row['PASSANGER_ID'];
		$this->WEIGHT->DbValue = $row['WEIGHT'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->BOARDING_ID->FormValue == $this->BOARDING_ID->CurrentValue && is_numeric(ew_StrToFloat($this->BOARDING_ID->CurrentValue)))
			$this->BOARDING_ID->CurrentValue = ew_StrToFloat($this->BOARDING_ID->CurrentValue);

		// Convert decimal values if posted back
		if ($this->WEIGHT->FormValue == $this->WEIGHT->CurrentValue && is_numeric(ew_StrToFloat($this->WEIGHT->CurrentValue)))
			$this->WEIGHT->CurrentValue = ew_StrToFloat($this->WEIGHT->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// BAGGAGE_ID
		// BOARDING_ID
		// PASSANGER_ID
		// WEIGHT

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// BAGGAGE_ID
		$this->BAGGAGE_ID->ViewValue = $this->BAGGAGE_ID->CurrentValue;
		$this->BAGGAGE_ID->ViewCustomAttributes = "";

		// BOARDING_ID
		$this->BOARDING_ID->ViewValue = $this->BOARDING_ID->CurrentValue;
		$this->BOARDING_ID->ViewCustomAttributes = "";

		// PASSANGER_ID
		$this->PASSANGER_ID->ViewValue = $this->PASSANGER_ID->CurrentValue;
		$this->PASSANGER_ID->ViewCustomAttributes = "";

		// WEIGHT
		$this->WEIGHT->ViewValue = $this->WEIGHT->CurrentValue;
		$this->WEIGHT->ViewCustomAttributes = "";

			// BAGGAGE_ID
			$this->BAGGAGE_ID->LinkCustomAttributes = "";
			$this->BAGGAGE_ID->HrefValue = "";
			$this->BAGGAGE_ID->TooltipValue = "";

			// BOARDING_ID
			$this->BOARDING_ID->LinkCustomAttributes = "";
			$this->BOARDING_ID->HrefValue = "";
			$this->BOARDING_ID->TooltipValue = "";

			// PASSANGER_ID
			$this->PASSANGER_ID->LinkCustomAttributes = "";
			$this->PASSANGER_ID->HrefValue = "";
			$this->PASSANGER_ID->TooltipValue = "";

			// WEIGHT
			$this->WEIGHT->LinkCustomAttributes = "";
			$this->WEIGHT->HrefValue = "";
			$this->WEIGHT->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// BAGGAGE_ID
			$this->BAGGAGE_ID->EditAttrs["class"] = "form-control";
			$this->BAGGAGE_ID->EditCustomAttributes = "";
			$this->BAGGAGE_ID->EditValue = $this->BAGGAGE_ID->CurrentValue;
			$this->BAGGAGE_ID->ViewCustomAttributes = "";

			// BOARDING_ID
			$this->BOARDING_ID->EditAttrs["class"] = "form-control";
			$this->BOARDING_ID->EditCustomAttributes = "";
			$this->BOARDING_ID->EditValue = ew_HtmlEncode($this->BOARDING_ID->CurrentValue);
			$this->BOARDING_ID->PlaceHolder = ew_RemoveHtml($this->BOARDING_ID->FldCaption());
			if (strval($this->BOARDING_ID->EditValue) <> "" && is_numeric($this->BOARDING_ID->EditValue)) $this->BOARDING_ID->EditValue = ew_FormatNumber($this->BOARDING_ID->EditValue, -2, -1, -2, 0);

			// PASSANGER_ID
			$this->PASSANGER_ID->EditAttrs["class"] = "form-control";
			$this->PASSANGER_ID->EditCustomAttributes = "";
			$this->PASSANGER_ID->EditValue = ew_HtmlEncode($this->PASSANGER_ID->CurrentValue);
			$this->PASSANGER_ID->PlaceHolder = ew_RemoveHtml($this->PASSANGER_ID->FldCaption());

			// WEIGHT
			$this->WEIGHT->EditAttrs["class"] = "form-control";
			$this->WEIGHT->EditCustomAttributes = "";
			$this->WEIGHT->EditValue = ew_HtmlEncode($this->WEIGHT->CurrentValue);
			$this->WEIGHT->PlaceHolder = ew_RemoveHtml($this->WEIGHT->FldCaption());
			if (strval($this->WEIGHT->EditValue) <> "" && is_numeric($this->WEIGHT->EditValue)) $this->WEIGHT->EditValue = ew_FormatNumber($this->WEIGHT->EditValue, -2, -1, -2, 0);

			// Edit refer script
			// BAGGAGE_ID

			$this->BAGGAGE_ID->LinkCustomAttributes = "";
			$this->BAGGAGE_ID->HrefValue = "";

			// BOARDING_ID
			$this->BOARDING_ID->LinkCustomAttributes = "";
			$this->BOARDING_ID->HrefValue = "";

			// PASSANGER_ID
			$this->PASSANGER_ID->LinkCustomAttributes = "";
			$this->PASSANGER_ID->HrefValue = "";

			// WEIGHT
			$this->WEIGHT->LinkCustomAttributes = "";
			$this->WEIGHT->HrefValue = "";
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
		if (!$this->BOARDING_ID->FldIsDetailKey && !is_null($this->BOARDING_ID->FormValue) && $this->BOARDING_ID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->BOARDING_ID->FldCaption(), $this->BOARDING_ID->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->BOARDING_ID->FormValue)) {
			ew_AddMessage($gsFormError, $this->BOARDING_ID->FldErrMsg());
		}
		if (!$this->PASSANGER_ID->FldIsDetailKey && !is_null($this->PASSANGER_ID->FormValue) && $this->PASSANGER_ID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->PASSANGER_ID->FldCaption(), $this->PASSANGER_ID->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->PASSANGER_ID->FormValue)) {
			ew_AddMessage($gsFormError, $this->PASSANGER_ID->FldErrMsg());
		}
		if (!$this->WEIGHT->FldIsDetailKey && !is_null($this->WEIGHT->FormValue) && $this->WEIGHT->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->WEIGHT->FldCaption(), $this->WEIGHT->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->WEIGHT->FormValue)) {
			ew_AddMessage($gsFormError, $this->WEIGHT->FldErrMsg());
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

			// BOARDING_ID
			$this->BOARDING_ID->SetDbValueDef($rsnew, $this->BOARDING_ID->CurrentValue, 0, $this->BOARDING_ID->ReadOnly);

			// PASSANGER_ID
			$this->PASSANGER_ID->SetDbValueDef($rsnew, $this->PASSANGER_ID->CurrentValue, 0, $this->PASSANGER_ID->ReadOnly);

			// WEIGHT
			$this->WEIGHT->SetDbValueDef($rsnew, $this->WEIGHT->CurrentValue, 0, $this->WEIGHT->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("baggagelist.php"), "", $this->TableVar, TRUE);
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
if (!isset($baggage_edit)) $baggage_edit = new cbaggage_edit();

// Page init
$baggage_edit->Page_Init();

// Page main
$baggage_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$baggage_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fbaggageedit = new ew_Form("fbaggageedit", "edit");

// Validate form
fbaggageedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_BOARDING_ID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $baggage->BOARDING_ID->FldCaption(), $baggage->BOARDING_ID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_BOARDING_ID");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($baggage->BOARDING_ID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_PASSANGER_ID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $baggage->PASSANGER_ID->FldCaption(), $baggage->PASSANGER_ID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_PASSANGER_ID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($baggage->PASSANGER_ID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_WEIGHT");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $baggage->WEIGHT->FldCaption(), $baggage->WEIGHT->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_WEIGHT");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($baggage->WEIGHT->FldErrMsg()) ?>");

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
fbaggageedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fbaggageedit.ValidateRequired = true;
<?php } else { ?>
fbaggageedit.ValidateRequired = false; 
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
<?php $baggage_edit->ShowPageHeader(); ?>
<?php
$baggage_edit->ShowMessage();
?>
<form name="fbaggageedit" id="fbaggageedit" class="<?php echo $baggage_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($baggage_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $baggage_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="baggage">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($baggage->BAGGAGE_ID->Visible) { // BAGGAGE_ID ?>
	<div id="r_BAGGAGE_ID" class="form-group">
		<label id="elh_baggage_BAGGAGE_ID" class="col-sm-2 control-label ewLabel"><?php echo $baggage->BAGGAGE_ID->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $baggage->BAGGAGE_ID->CellAttributes() ?>>
<span id="el_baggage_BAGGAGE_ID">
<span<?php echo $baggage->BAGGAGE_ID->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $baggage->BAGGAGE_ID->EditValue ?></p></span>
</span>
<input type="hidden" data-table="baggage" data-field="x_BAGGAGE_ID" name="x_BAGGAGE_ID" id="x_BAGGAGE_ID" value="<?php echo ew_HtmlEncode($baggage->BAGGAGE_ID->CurrentValue) ?>">
<?php echo $baggage->BAGGAGE_ID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($baggage->BOARDING_ID->Visible) { // BOARDING_ID ?>
	<div id="r_BOARDING_ID" class="form-group">
		<label id="elh_baggage_BOARDING_ID" for="x_BOARDING_ID" class="col-sm-2 control-label ewLabel"><?php echo $baggage->BOARDING_ID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $baggage->BOARDING_ID->CellAttributes() ?>>
<span id="el_baggage_BOARDING_ID">
<input type="text" data-table="baggage" data-field="x_BOARDING_ID" name="x_BOARDING_ID" id="x_BOARDING_ID" size="30" placeholder="<?php echo ew_HtmlEncode($baggage->BOARDING_ID->getPlaceHolder()) ?>" value="<?php echo $baggage->BOARDING_ID->EditValue ?>"<?php echo $baggage->BOARDING_ID->EditAttributes() ?>>
</span>
<?php echo $baggage->BOARDING_ID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($baggage->PASSANGER_ID->Visible) { // PASSANGER_ID ?>
	<div id="r_PASSANGER_ID" class="form-group">
		<label id="elh_baggage_PASSANGER_ID" for="x_PASSANGER_ID" class="col-sm-2 control-label ewLabel"><?php echo $baggage->PASSANGER_ID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $baggage->PASSANGER_ID->CellAttributes() ?>>
<span id="el_baggage_PASSANGER_ID">
<input type="text" data-table="baggage" data-field="x_PASSANGER_ID" name="x_PASSANGER_ID" id="x_PASSANGER_ID" size="30" placeholder="<?php echo ew_HtmlEncode($baggage->PASSANGER_ID->getPlaceHolder()) ?>" value="<?php echo $baggage->PASSANGER_ID->EditValue ?>"<?php echo $baggage->PASSANGER_ID->EditAttributes() ?>>
</span>
<?php echo $baggage->PASSANGER_ID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($baggage->WEIGHT->Visible) { // WEIGHT ?>
	<div id="r_WEIGHT" class="form-group">
		<label id="elh_baggage_WEIGHT" for="x_WEIGHT" class="col-sm-2 control-label ewLabel"><?php echo $baggage->WEIGHT->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $baggage->WEIGHT->CellAttributes() ?>>
<span id="el_baggage_WEIGHT">
<input type="text" data-table="baggage" data-field="x_WEIGHT" name="x_WEIGHT" id="x_WEIGHT" size="30" placeholder="<?php echo ew_HtmlEncode($baggage->WEIGHT->getPlaceHolder()) ?>" value="<?php echo $baggage->WEIGHT->EditValue ?>"<?php echo $baggage->WEIGHT->EditAttributes() ?>>
</span>
<?php echo $baggage->WEIGHT->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $baggage_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fbaggageedit.Init();
</script>
<?php
$baggage_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$baggage_edit->Page_Terminate();
?>
