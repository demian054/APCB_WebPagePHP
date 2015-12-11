<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "boardinginfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$boarding_add = NULL; // Initialize page object first

class cboarding_add extends cboarding {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{B2F6402D-7C0A-4760-82AD-045088CEBA18}";

	// Table name
	var $TableName = 'boarding';

	// Page object name
	var $PageObjName = 'boarding_add';

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

		// Table object (boarding)
		if (!isset($GLOBALS["boarding"]) || get_class($GLOBALS["boarding"]) == "cboarding") {
			$GLOBALS["boarding"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["boarding"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'boarding', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("boardinglist.php"));
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
		global $EW_EXPORT, $boarding;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($boarding);
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
			if (@$_GET["BOARDING_ID"] != "") {
				$this->BOARDING_ID->setQueryStringValue($_GET["BOARDING_ID"]);
				$this->setKey("BOARDING_ID", $this->BOARDING_ID->CurrentValue); // Set up key
			} else {
				$this->setKey("BOARDING_ID", ""); // Clear key
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
					$this->Page_Terminate("boardinglist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "boardinglist.php")
						$sReturnUrl = $this->AddMasterUrl($this->GetListUrl()); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "boardingview.php")
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
		$this->PASSANGER_ID->CurrentValue = NULL;
		$this->PASSANGER_ID->OldValue = $this->PASSANGER_ID->CurrentValue;
		$this->FLIGHT_ID->CurrentValue = NULL;
		$this->FLIGHT_ID->OldValue = $this->FLIGHT_ID->CurrentValue;
		$this->GATE->CurrentValue = NULL;
		$this->GATE->OldValue = $this->GATE->CurrentValue;
		$this->SEAT->CurrentValue = NULL;
		$this->SEAT->OldValue = $this->SEAT->CurrentValue;
		$this->DATETIME->CurrentValue = NULL;
		$this->DATETIME->OldValue = $this->DATETIME->CurrentValue;
		$this->RESERVATION_ID->CurrentValue = NULL;
		$this->RESERVATION_ID->OldValue = $this->RESERVATION_ID->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->PASSANGER_ID->FldIsDetailKey) {
			$this->PASSANGER_ID->setFormValue($objForm->GetValue("x_PASSANGER_ID"));
		}
		if (!$this->FLIGHT_ID->FldIsDetailKey) {
			$this->FLIGHT_ID->setFormValue($objForm->GetValue("x_FLIGHT_ID"));
		}
		if (!$this->GATE->FldIsDetailKey) {
			$this->GATE->setFormValue($objForm->GetValue("x_GATE"));
		}
		if (!$this->SEAT->FldIsDetailKey) {
			$this->SEAT->setFormValue($objForm->GetValue("x_SEAT"));
		}
		if (!$this->DATETIME->FldIsDetailKey) {
			$this->DATETIME->setFormValue($objForm->GetValue("x_DATETIME"));
			$this->DATETIME->CurrentValue = ew_UnFormatDateTime($this->DATETIME->CurrentValue, 7);
		}
		if (!$this->RESERVATION_ID->FldIsDetailKey) {
			$this->RESERVATION_ID->setFormValue($objForm->GetValue("x_RESERVATION_ID"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->PASSANGER_ID->CurrentValue = $this->PASSANGER_ID->FormValue;
		$this->FLIGHT_ID->CurrentValue = $this->FLIGHT_ID->FormValue;
		$this->GATE->CurrentValue = $this->GATE->FormValue;
		$this->SEAT->CurrentValue = $this->SEAT->FormValue;
		$this->DATETIME->CurrentValue = $this->DATETIME->FormValue;
		$this->DATETIME->CurrentValue = ew_UnFormatDateTime($this->DATETIME->CurrentValue, 7);
		$this->RESERVATION_ID->CurrentValue = $this->RESERVATION_ID->FormValue;
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
		$this->BOARDING_ID->setDbValue($rs->fields('BOARDING_ID'));
		$this->PASSANGER_ID->setDbValue($rs->fields('PASSANGER_ID'));
		$this->FLIGHT_ID->setDbValue($rs->fields('FLIGHT_ID'));
		$this->GATE->setDbValue($rs->fields('GATE'));
		$this->SEAT->setDbValue($rs->fields('SEAT'));
		$this->DATETIME->setDbValue($rs->fields('DATETIME'));
		$this->RESERVATION_ID->setDbValue($rs->fields('RESERVATION_ID'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->BOARDING_ID->DbValue = $row['BOARDING_ID'];
		$this->PASSANGER_ID->DbValue = $row['PASSANGER_ID'];
		$this->FLIGHT_ID->DbValue = $row['FLIGHT_ID'];
		$this->GATE->DbValue = $row['GATE'];
		$this->SEAT->DbValue = $row['SEAT'];
		$this->DATETIME->DbValue = $row['DATETIME'];
		$this->RESERVATION_ID->DbValue = $row['RESERVATION_ID'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("BOARDING_ID")) <> "")
			$this->BOARDING_ID->CurrentValue = $this->getKey("BOARDING_ID"); // BOARDING_ID
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

		if ($this->GATE->FormValue == $this->GATE->CurrentValue && is_numeric(ew_StrToFloat($this->GATE->CurrentValue)))
			$this->GATE->CurrentValue = ew_StrToFloat($this->GATE->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// BOARDING_ID
		// PASSANGER_ID
		// FLIGHT_ID
		// GATE
		// SEAT
		// DATETIME
		// RESERVATION_ID

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// BOARDING_ID
		$this->BOARDING_ID->ViewValue = $this->BOARDING_ID->CurrentValue;
		$this->BOARDING_ID->ViewCustomAttributes = "";

		// PASSANGER_ID
		$this->PASSANGER_ID->ViewValue = $this->PASSANGER_ID->CurrentValue;
		$this->PASSANGER_ID->ViewCustomAttributes = "";

		// FLIGHT_ID
		$this->FLIGHT_ID->ViewValue = $this->FLIGHT_ID->CurrentValue;
		$this->FLIGHT_ID->ViewCustomAttributes = "";

		// GATE
		$this->GATE->ViewValue = $this->GATE->CurrentValue;
		$this->GATE->ViewCustomAttributes = "";

		// SEAT
		$this->SEAT->ViewValue = $this->SEAT->CurrentValue;
		$this->SEAT->ViewCustomAttributes = "";

		// DATETIME
		$this->DATETIME->ViewValue = $this->DATETIME->CurrentValue;
		$this->DATETIME->ViewValue = ew_FormatDateTime($this->DATETIME->ViewValue, 7);
		$this->DATETIME->ViewCustomAttributes = "";

		// RESERVATION_ID
		$this->RESERVATION_ID->ViewValue = $this->RESERVATION_ID->CurrentValue;
		$this->RESERVATION_ID->ViewCustomAttributes = "";

			// PASSANGER_ID
			$this->PASSANGER_ID->LinkCustomAttributes = "";
			$this->PASSANGER_ID->HrefValue = "";
			$this->PASSANGER_ID->TooltipValue = "";

			// FLIGHT_ID
			$this->FLIGHT_ID->LinkCustomAttributes = "";
			$this->FLIGHT_ID->HrefValue = "";
			$this->FLIGHT_ID->TooltipValue = "";

			// GATE
			$this->GATE->LinkCustomAttributes = "";
			$this->GATE->HrefValue = "";
			$this->GATE->TooltipValue = "";

			// SEAT
			$this->SEAT->LinkCustomAttributes = "";
			$this->SEAT->HrefValue = "";
			$this->SEAT->TooltipValue = "";

			// DATETIME
			$this->DATETIME->LinkCustomAttributes = "";
			$this->DATETIME->HrefValue = "";
			$this->DATETIME->TooltipValue = "";

			// RESERVATION_ID
			$this->RESERVATION_ID->LinkCustomAttributes = "";
			$this->RESERVATION_ID->HrefValue = "";
			$this->RESERVATION_ID->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// PASSANGER_ID
			$this->PASSANGER_ID->EditAttrs["class"] = "form-control";
			$this->PASSANGER_ID->EditCustomAttributes = "";
			$this->PASSANGER_ID->EditValue = ew_HtmlEncode($this->PASSANGER_ID->CurrentValue);
			$this->PASSANGER_ID->PlaceHolder = ew_RemoveHtml($this->PASSANGER_ID->FldCaption());

			// FLIGHT_ID
			$this->FLIGHT_ID->EditAttrs["class"] = "form-control";
			$this->FLIGHT_ID->EditCustomAttributes = "";
			$this->FLIGHT_ID->EditValue = ew_HtmlEncode($this->FLIGHT_ID->CurrentValue);
			$this->FLIGHT_ID->PlaceHolder = ew_RemoveHtml($this->FLIGHT_ID->FldCaption());

			// GATE
			$this->GATE->EditAttrs["class"] = "form-control";
			$this->GATE->EditCustomAttributes = "";
			$this->GATE->EditValue = ew_HtmlEncode($this->GATE->CurrentValue);
			$this->GATE->PlaceHolder = ew_RemoveHtml($this->GATE->FldCaption());
			if (strval($this->GATE->EditValue) <> "" && is_numeric($this->GATE->EditValue)) $this->GATE->EditValue = ew_FormatNumber($this->GATE->EditValue, -2, -1, -2, 0);

			// SEAT
			$this->SEAT->EditAttrs["class"] = "form-control";
			$this->SEAT->EditCustomAttributes = "";
			$this->SEAT->EditValue = ew_HtmlEncode($this->SEAT->CurrentValue);
			$this->SEAT->PlaceHolder = ew_RemoveHtml($this->SEAT->FldCaption());

			// DATETIME
			$this->DATETIME->EditAttrs["class"] = "form-control";
			$this->DATETIME->EditCustomAttributes = "";
			$this->DATETIME->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->DATETIME->CurrentValue, 7));
			$this->DATETIME->PlaceHolder = ew_RemoveHtml($this->DATETIME->FldCaption());

			// RESERVATION_ID
			$this->RESERVATION_ID->EditAttrs["class"] = "form-control";
			$this->RESERVATION_ID->EditCustomAttributes = "";
			$this->RESERVATION_ID->EditValue = ew_HtmlEncode($this->RESERVATION_ID->CurrentValue);
			$this->RESERVATION_ID->PlaceHolder = ew_RemoveHtml($this->RESERVATION_ID->FldCaption());

			// Add refer script
			// PASSANGER_ID

			$this->PASSANGER_ID->LinkCustomAttributes = "";
			$this->PASSANGER_ID->HrefValue = "";

			// FLIGHT_ID
			$this->FLIGHT_ID->LinkCustomAttributes = "";
			$this->FLIGHT_ID->HrefValue = "";

			// GATE
			$this->GATE->LinkCustomAttributes = "";
			$this->GATE->HrefValue = "";

			// SEAT
			$this->SEAT->LinkCustomAttributes = "";
			$this->SEAT->HrefValue = "";

			// DATETIME
			$this->DATETIME->LinkCustomAttributes = "";
			$this->DATETIME->HrefValue = "";

			// RESERVATION_ID
			$this->RESERVATION_ID->LinkCustomAttributes = "";
			$this->RESERVATION_ID->HrefValue = "";
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
		if (!$this->PASSANGER_ID->FldIsDetailKey && !is_null($this->PASSANGER_ID->FormValue) && $this->PASSANGER_ID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->PASSANGER_ID->FldCaption(), $this->PASSANGER_ID->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->PASSANGER_ID->FormValue)) {
			ew_AddMessage($gsFormError, $this->PASSANGER_ID->FldErrMsg());
		}
		if (!$this->FLIGHT_ID->FldIsDetailKey && !is_null($this->FLIGHT_ID->FormValue) && $this->FLIGHT_ID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->FLIGHT_ID->FldCaption(), $this->FLIGHT_ID->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->FLIGHT_ID->FormValue)) {
			ew_AddMessage($gsFormError, $this->FLIGHT_ID->FldErrMsg());
		}
		if (!$this->GATE->FldIsDetailKey && !is_null($this->GATE->FormValue) && $this->GATE->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->GATE->FldCaption(), $this->GATE->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->GATE->FormValue)) {
			ew_AddMessage($gsFormError, $this->GATE->FldErrMsg());
		}
		if (!$this->SEAT->FldIsDetailKey && !is_null($this->SEAT->FormValue) && $this->SEAT->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->SEAT->FldCaption(), $this->SEAT->ReqErrMsg));
		}
		if (!$this->DATETIME->FldIsDetailKey && !is_null($this->DATETIME->FormValue) && $this->DATETIME->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->DATETIME->FldCaption(), $this->DATETIME->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->DATETIME->FormValue)) {
			ew_AddMessage($gsFormError, $this->DATETIME->FldErrMsg());
		}
		if (!$this->RESERVATION_ID->FldIsDetailKey && !is_null($this->RESERVATION_ID->FormValue) && $this->RESERVATION_ID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->RESERVATION_ID->FldCaption(), $this->RESERVATION_ID->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->RESERVATION_ID->FormValue)) {
			ew_AddMessage($gsFormError, $this->RESERVATION_ID->FldErrMsg());
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

		// PASSANGER_ID
		$this->PASSANGER_ID->SetDbValueDef($rsnew, $this->PASSANGER_ID->CurrentValue, 0, FALSE);

		// FLIGHT_ID
		$this->FLIGHT_ID->SetDbValueDef($rsnew, $this->FLIGHT_ID->CurrentValue, 0, FALSE);

		// GATE
		$this->GATE->SetDbValueDef($rsnew, $this->GATE->CurrentValue, 0, FALSE);

		// SEAT
		$this->SEAT->SetDbValueDef($rsnew, $this->SEAT->CurrentValue, "", FALSE);

		// DATETIME
		$this->DATETIME->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->DATETIME->CurrentValue, 7), ew_CurrentDate(), FALSE);

		// RESERVATION_ID
		$this->RESERVATION_ID->SetDbValueDef($rsnew, $this->RESERVATION_ID->CurrentValue, 0, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->BOARDING_ID->setDbValue($conn->Insert_ID());
				$rsnew['BOARDING_ID'] = $this->BOARDING_ID->DbValue;
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("boardinglist.php"), "", $this->TableVar, TRUE);
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
if (!isset($boarding_add)) $boarding_add = new cboarding_add();

// Page init
$boarding_add->Page_Init();

// Page main
$boarding_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$boarding_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fboardingadd = new ew_Form("fboardingadd", "add");

// Validate form
fboardingadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_PASSANGER_ID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $boarding->PASSANGER_ID->FldCaption(), $boarding->PASSANGER_ID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_PASSANGER_ID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($boarding->PASSANGER_ID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_FLIGHT_ID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $boarding->FLIGHT_ID->FldCaption(), $boarding->FLIGHT_ID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_FLIGHT_ID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($boarding->FLIGHT_ID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_GATE");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $boarding->GATE->FldCaption(), $boarding->GATE->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_GATE");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($boarding->GATE->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_SEAT");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $boarding->SEAT->FldCaption(), $boarding->SEAT->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_DATETIME");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $boarding->DATETIME->FldCaption(), $boarding->DATETIME->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_DATETIME");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($boarding->DATETIME->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_RESERVATION_ID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $boarding->RESERVATION_ID->FldCaption(), $boarding->RESERVATION_ID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_RESERVATION_ID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($boarding->RESERVATION_ID->FldErrMsg()) ?>");

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
fboardingadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fboardingadd.ValidateRequired = true;
<?php } else { ?>
fboardingadd.ValidateRequired = false; 
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
<?php $boarding_add->ShowPageHeader(); ?>
<?php
$boarding_add->ShowMessage();
?>
<form name="fboardingadd" id="fboardingadd" class="<?php echo $boarding_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($boarding_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $boarding_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="boarding">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($boarding->PASSANGER_ID->Visible) { // PASSANGER_ID ?>
	<div id="r_PASSANGER_ID" class="form-group">
		<label id="elh_boarding_PASSANGER_ID" for="x_PASSANGER_ID" class="col-sm-2 control-label ewLabel"><?php echo $boarding->PASSANGER_ID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $boarding->PASSANGER_ID->CellAttributes() ?>>
<span id="el_boarding_PASSANGER_ID">
<input type="text" data-table="boarding" data-field="x_PASSANGER_ID" name="x_PASSANGER_ID" id="x_PASSANGER_ID" size="30" placeholder="<?php echo ew_HtmlEncode($boarding->PASSANGER_ID->getPlaceHolder()) ?>" value="<?php echo $boarding->PASSANGER_ID->EditValue ?>"<?php echo $boarding->PASSANGER_ID->EditAttributes() ?>>
</span>
<?php echo $boarding->PASSANGER_ID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($boarding->FLIGHT_ID->Visible) { // FLIGHT_ID ?>
	<div id="r_FLIGHT_ID" class="form-group">
		<label id="elh_boarding_FLIGHT_ID" for="x_FLIGHT_ID" class="col-sm-2 control-label ewLabel"><?php echo $boarding->FLIGHT_ID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $boarding->FLIGHT_ID->CellAttributes() ?>>
<span id="el_boarding_FLIGHT_ID">
<input type="text" data-table="boarding" data-field="x_FLIGHT_ID" name="x_FLIGHT_ID" id="x_FLIGHT_ID" size="30" placeholder="<?php echo ew_HtmlEncode($boarding->FLIGHT_ID->getPlaceHolder()) ?>" value="<?php echo $boarding->FLIGHT_ID->EditValue ?>"<?php echo $boarding->FLIGHT_ID->EditAttributes() ?>>
</span>
<?php echo $boarding->FLIGHT_ID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($boarding->GATE->Visible) { // GATE ?>
	<div id="r_GATE" class="form-group">
		<label id="elh_boarding_GATE" for="x_GATE" class="col-sm-2 control-label ewLabel"><?php echo $boarding->GATE->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $boarding->GATE->CellAttributes() ?>>
<span id="el_boarding_GATE">
<input type="text" data-table="boarding" data-field="x_GATE" name="x_GATE" id="x_GATE" size="30" placeholder="<?php echo ew_HtmlEncode($boarding->GATE->getPlaceHolder()) ?>" value="<?php echo $boarding->GATE->EditValue ?>"<?php echo $boarding->GATE->EditAttributes() ?>>
</span>
<?php echo $boarding->GATE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($boarding->SEAT->Visible) { // SEAT ?>
	<div id="r_SEAT" class="form-group">
		<label id="elh_boarding_SEAT" for="x_SEAT" class="col-sm-2 control-label ewLabel"><?php echo $boarding->SEAT->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $boarding->SEAT->CellAttributes() ?>>
<span id="el_boarding_SEAT">
<input type="text" data-table="boarding" data-field="x_SEAT" name="x_SEAT" id="x_SEAT" size="30" maxlength="2" placeholder="<?php echo ew_HtmlEncode($boarding->SEAT->getPlaceHolder()) ?>" value="<?php echo $boarding->SEAT->EditValue ?>"<?php echo $boarding->SEAT->EditAttributes() ?>>
</span>
<?php echo $boarding->SEAT->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($boarding->DATETIME->Visible) { // DATETIME ?>
	<div id="r_DATETIME" class="form-group">
		<label id="elh_boarding_DATETIME" for="x_DATETIME" class="col-sm-2 control-label ewLabel"><?php echo $boarding->DATETIME->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $boarding->DATETIME->CellAttributes() ?>>
<span id="el_boarding_DATETIME">
<input type="text" data-table="boarding" data-field="x_DATETIME" data-format="7" name="x_DATETIME" id="x_DATETIME" placeholder="<?php echo ew_HtmlEncode($boarding->DATETIME->getPlaceHolder()) ?>" value="<?php echo $boarding->DATETIME->EditValue ?>"<?php echo $boarding->DATETIME->EditAttributes() ?>>
</span>
<?php echo $boarding->DATETIME->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($boarding->RESERVATION_ID->Visible) { // RESERVATION_ID ?>
	<div id="r_RESERVATION_ID" class="form-group">
		<label id="elh_boarding_RESERVATION_ID" for="x_RESERVATION_ID" class="col-sm-2 control-label ewLabel"><?php echo $boarding->RESERVATION_ID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $boarding->RESERVATION_ID->CellAttributes() ?>>
<span id="el_boarding_RESERVATION_ID">
<input type="text" data-table="boarding" data-field="x_RESERVATION_ID" name="x_RESERVATION_ID" id="x_RESERVATION_ID" size="30" placeholder="<?php echo ew_HtmlEncode($boarding->RESERVATION_ID->getPlaceHolder()) ?>" value="<?php echo $boarding->RESERVATION_ID->EditValue ?>"<?php echo $boarding->RESERVATION_ID->EditAttributes() ?>>
</span>
<?php echo $boarding->RESERVATION_ID->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $boarding_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fboardingadd.Init();
</script>
<?php
$boarding_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$boarding_add->Page_Terminate();
?>
