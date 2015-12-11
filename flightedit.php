<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "flightinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$flight_edit = NULL; // Initialize page object first

class cflight_edit extends cflight {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{B2F6402D-7C0A-4760-82AD-045088CEBA18}";

	// Table name
	var $TableName = 'flight';

	// Page object name
	var $PageObjName = 'flight_edit';

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

		// Table object (flight)
		if (!isset($GLOBALS["flight"]) || get_class($GLOBALS["flight"]) == "cflight") {
			$GLOBALS["flight"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["flight"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'flight', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("flightlist.php"));
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
		$this->FLIGHT_ID->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $flight;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($flight);
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
		if (@$_GET["FLIGHT_ID"] <> "") {
			$this->FLIGHT_ID->setQueryStringValue($_GET["FLIGHT_ID"]);
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
		if ($this->FLIGHT_ID->CurrentValue == "")
			$this->Page_Terminate("flightlist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("flightlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "flightlist.php")
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
		if (!$this->FLIGHT_ID->FldIsDetailKey)
			$this->FLIGHT_ID->setFormValue($objForm->GetValue("x_FLIGHT_ID"));
		if (!$this->CODE->FldIsDetailKey) {
			$this->CODE->setFormValue($objForm->GetValue("x_CODE"));
		}
		if (!$this->DESCRIPTION->FldIsDetailKey) {
			$this->DESCRIPTION->setFormValue($objForm->GetValue("x_DESCRIPTION"));
		}
		if (!$this->AIRPLANE_ID->FldIsDetailKey) {
			$this->AIRPLANE_ID->setFormValue($objForm->GetValue("x_AIRPLANE_ID"));
		}
		if (!$this->AIR_PORT_ID_DEPARTURE->FldIsDetailKey) {
			$this->AIR_PORT_ID_DEPARTURE->setFormValue($objForm->GetValue("x_AIR_PORT_ID_DEPARTURE"));
		}
		if (!$this->AIR_PORT_ID_ARRIVAL->FldIsDetailKey) {
			$this->AIR_PORT_ID_ARRIVAL->setFormValue($objForm->GetValue("x_AIR_PORT_ID_ARRIVAL"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->FLIGHT_ID->CurrentValue = $this->FLIGHT_ID->FormValue;
		$this->CODE->CurrentValue = $this->CODE->FormValue;
		$this->DESCRIPTION->CurrentValue = $this->DESCRIPTION->FormValue;
		$this->AIRPLANE_ID->CurrentValue = $this->AIRPLANE_ID->FormValue;
		$this->AIR_PORT_ID_DEPARTURE->CurrentValue = $this->AIR_PORT_ID_DEPARTURE->FormValue;
		$this->AIR_PORT_ID_ARRIVAL->CurrentValue = $this->AIR_PORT_ID_ARRIVAL->FormValue;
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
		$this->FLIGHT_ID->setDbValue($rs->fields('FLIGHT_ID'));
		$this->CODE->setDbValue($rs->fields('CODE'));
		$this->DESCRIPTION->setDbValue($rs->fields('DESCRIPTION'));
		$this->AIRPLANE_ID->setDbValue($rs->fields('AIRPLANE_ID'));
		$this->AIR_PORT_ID_DEPARTURE->setDbValue($rs->fields('AIR_PORT_ID_DEPARTURE'));
		$this->AIR_PORT_ID_ARRIVAL->setDbValue($rs->fields('AIR_PORT_ID_ARRIVAL'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->FLIGHT_ID->DbValue = $row['FLIGHT_ID'];
		$this->CODE->DbValue = $row['CODE'];
		$this->DESCRIPTION->DbValue = $row['DESCRIPTION'];
		$this->AIRPLANE_ID->DbValue = $row['AIRPLANE_ID'];
		$this->AIR_PORT_ID_DEPARTURE->DbValue = $row['AIR_PORT_ID_DEPARTURE'];
		$this->AIR_PORT_ID_ARRIVAL->DbValue = $row['AIR_PORT_ID_ARRIVAL'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// FLIGHT_ID
		// CODE
		// DESCRIPTION
		// AIRPLANE_ID
		// AIR_PORT_ID_DEPARTURE
		// AIR_PORT_ID_ARRIVAL

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// FLIGHT_ID
		$this->FLIGHT_ID->ViewValue = $this->FLIGHT_ID->CurrentValue;
		$this->FLIGHT_ID->ViewCustomAttributes = "";

		// CODE
		$this->CODE->ViewValue = $this->CODE->CurrentValue;
		$this->CODE->ViewCustomAttributes = "";

		// DESCRIPTION
		$this->DESCRIPTION->ViewValue = $this->DESCRIPTION->CurrentValue;
		$this->DESCRIPTION->ViewCustomAttributes = "";

		// AIRPLANE_ID
		$this->AIRPLANE_ID->ViewValue = $this->AIRPLANE_ID->CurrentValue;
		$this->AIRPLANE_ID->ViewCustomAttributes = "";

		// AIR_PORT_ID_DEPARTURE
		$this->AIR_PORT_ID_DEPARTURE->ViewValue = $this->AIR_PORT_ID_DEPARTURE->CurrentValue;
		$this->AIR_PORT_ID_DEPARTURE->ViewCustomAttributes = "";

		// AIR_PORT_ID_ARRIVAL
		$this->AIR_PORT_ID_ARRIVAL->ViewValue = $this->AIR_PORT_ID_ARRIVAL->CurrentValue;
		$this->AIR_PORT_ID_ARRIVAL->ViewCustomAttributes = "";

			// FLIGHT_ID
			$this->FLIGHT_ID->LinkCustomAttributes = "";
			$this->FLIGHT_ID->HrefValue = "";
			$this->FLIGHT_ID->TooltipValue = "";

			// CODE
			$this->CODE->LinkCustomAttributes = "";
			$this->CODE->HrefValue = "";
			$this->CODE->TooltipValue = "";

			// DESCRIPTION
			$this->DESCRIPTION->LinkCustomAttributes = "";
			$this->DESCRIPTION->HrefValue = "";
			$this->DESCRIPTION->TooltipValue = "";

			// AIRPLANE_ID
			$this->AIRPLANE_ID->LinkCustomAttributes = "";
			$this->AIRPLANE_ID->HrefValue = "";
			$this->AIRPLANE_ID->TooltipValue = "";

			// AIR_PORT_ID_DEPARTURE
			$this->AIR_PORT_ID_DEPARTURE->LinkCustomAttributes = "";
			$this->AIR_PORT_ID_DEPARTURE->HrefValue = "";
			$this->AIR_PORT_ID_DEPARTURE->TooltipValue = "";

			// AIR_PORT_ID_ARRIVAL
			$this->AIR_PORT_ID_ARRIVAL->LinkCustomAttributes = "";
			$this->AIR_PORT_ID_ARRIVAL->HrefValue = "";
			$this->AIR_PORT_ID_ARRIVAL->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// FLIGHT_ID
			$this->FLIGHT_ID->EditAttrs["class"] = "form-control";
			$this->FLIGHT_ID->EditCustomAttributes = "";
			$this->FLIGHT_ID->EditValue = $this->FLIGHT_ID->CurrentValue;
			$this->FLIGHT_ID->ViewCustomAttributes = "";

			// CODE
			$this->CODE->EditAttrs["class"] = "form-control";
			$this->CODE->EditCustomAttributes = "";
			$this->CODE->EditValue = ew_HtmlEncode($this->CODE->CurrentValue);
			$this->CODE->PlaceHolder = ew_RemoveHtml($this->CODE->FldCaption());

			// DESCRIPTION
			$this->DESCRIPTION->EditAttrs["class"] = "form-control";
			$this->DESCRIPTION->EditCustomAttributes = "";
			$this->DESCRIPTION->EditValue = ew_HtmlEncode($this->DESCRIPTION->CurrentValue);
			$this->DESCRIPTION->PlaceHolder = ew_RemoveHtml($this->DESCRIPTION->FldCaption());

			// AIRPLANE_ID
			$this->AIRPLANE_ID->EditAttrs["class"] = "form-control";
			$this->AIRPLANE_ID->EditCustomAttributes = "";
			$this->AIRPLANE_ID->EditValue = ew_HtmlEncode($this->AIRPLANE_ID->CurrentValue);
			$this->AIRPLANE_ID->PlaceHolder = ew_RemoveHtml($this->AIRPLANE_ID->FldCaption());

			// AIR_PORT_ID_DEPARTURE
			$this->AIR_PORT_ID_DEPARTURE->EditAttrs["class"] = "form-control";
			$this->AIR_PORT_ID_DEPARTURE->EditCustomAttributes = "";
			$this->AIR_PORT_ID_DEPARTURE->EditValue = ew_HtmlEncode($this->AIR_PORT_ID_DEPARTURE->CurrentValue);
			$this->AIR_PORT_ID_DEPARTURE->PlaceHolder = ew_RemoveHtml($this->AIR_PORT_ID_DEPARTURE->FldCaption());

			// AIR_PORT_ID_ARRIVAL
			$this->AIR_PORT_ID_ARRIVAL->EditAttrs["class"] = "form-control";
			$this->AIR_PORT_ID_ARRIVAL->EditCustomAttributes = "";
			$this->AIR_PORT_ID_ARRIVAL->EditValue = ew_HtmlEncode($this->AIR_PORT_ID_ARRIVAL->CurrentValue);
			$this->AIR_PORT_ID_ARRIVAL->PlaceHolder = ew_RemoveHtml($this->AIR_PORT_ID_ARRIVAL->FldCaption());

			// Edit refer script
			// FLIGHT_ID

			$this->FLIGHT_ID->LinkCustomAttributes = "";
			$this->FLIGHT_ID->HrefValue = "";

			// CODE
			$this->CODE->LinkCustomAttributes = "";
			$this->CODE->HrefValue = "";

			// DESCRIPTION
			$this->DESCRIPTION->LinkCustomAttributes = "";
			$this->DESCRIPTION->HrefValue = "";

			// AIRPLANE_ID
			$this->AIRPLANE_ID->LinkCustomAttributes = "";
			$this->AIRPLANE_ID->HrefValue = "";

			// AIR_PORT_ID_DEPARTURE
			$this->AIR_PORT_ID_DEPARTURE->LinkCustomAttributes = "";
			$this->AIR_PORT_ID_DEPARTURE->HrefValue = "";

			// AIR_PORT_ID_ARRIVAL
			$this->AIR_PORT_ID_ARRIVAL->LinkCustomAttributes = "";
			$this->AIR_PORT_ID_ARRIVAL->HrefValue = "";
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
		if (!$this->AIRPLANE_ID->FldIsDetailKey && !is_null($this->AIRPLANE_ID->FormValue) && $this->AIRPLANE_ID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->AIRPLANE_ID->FldCaption(), $this->AIRPLANE_ID->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->AIRPLANE_ID->FormValue)) {
			ew_AddMessage($gsFormError, $this->AIRPLANE_ID->FldErrMsg());
		}
		if (!$this->AIR_PORT_ID_DEPARTURE->FldIsDetailKey && !is_null($this->AIR_PORT_ID_DEPARTURE->FormValue) && $this->AIR_PORT_ID_DEPARTURE->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->AIR_PORT_ID_DEPARTURE->FldCaption(), $this->AIR_PORT_ID_DEPARTURE->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->AIR_PORT_ID_DEPARTURE->FormValue)) {
			ew_AddMessage($gsFormError, $this->AIR_PORT_ID_DEPARTURE->FldErrMsg());
		}
		if (!$this->AIR_PORT_ID_ARRIVAL->FldIsDetailKey && !is_null($this->AIR_PORT_ID_ARRIVAL->FormValue) && $this->AIR_PORT_ID_ARRIVAL->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->AIR_PORT_ID_ARRIVAL->FldCaption(), $this->AIR_PORT_ID_ARRIVAL->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->AIR_PORT_ID_ARRIVAL->FormValue)) {
			ew_AddMessage($gsFormError, $this->AIR_PORT_ID_ARRIVAL->FldErrMsg());
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

			// CODE
			$this->CODE->SetDbValueDef($rsnew, $this->CODE->CurrentValue, "", $this->CODE->ReadOnly);

			// DESCRIPTION
			$this->DESCRIPTION->SetDbValueDef($rsnew, $this->DESCRIPTION->CurrentValue, NULL, $this->DESCRIPTION->ReadOnly);

			// AIRPLANE_ID
			$this->AIRPLANE_ID->SetDbValueDef($rsnew, $this->AIRPLANE_ID->CurrentValue, 0, $this->AIRPLANE_ID->ReadOnly);

			// AIR_PORT_ID_DEPARTURE
			$this->AIR_PORT_ID_DEPARTURE->SetDbValueDef($rsnew, $this->AIR_PORT_ID_DEPARTURE->CurrentValue, 0, $this->AIR_PORT_ID_DEPARTURE->ReadOnly);

			// AIR_PORT_ID_ARRIVAL
			$this->AIR_PORT_ID_ARRIVAL->SetDbValueDef($rsnew, $this->AIR_PORT_ID_ARRIVAL->CurrentValue, 0, $this->AIR_PORT_ID_ARRIVAL->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("flightlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($flight_edit)) $flight_edit = new cflight_edit();

// Page init
$flight_edit->Page_Init();

// Page main
$flight_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$flight_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fflightedit = new ew_Form("fflightedit", "edit");

// Validate form
fflightedit.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $flight->CODE->FldCaption(), $flight->CODE->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_AIRPLANE_ID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $flight->AIRPLANE_ID->FldCaption(), $flight->AIRPLANE_ID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_AIRPLANE_ID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($flight->AIRPLANE_ID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_AIR_PORT_ID_DEPARTURE");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $flight->AIR_PORT_ID_DEPARTURE->FldCaption(), $flight->AIR_PORT_ID_DEPARTURE->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_AIR_PORT_ID_DEPARTURE");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($flight->AIR_PORT_ID_DEPARTURE->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_AIR_PORT_ID_ARRIVAL");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $flight->AIR_PORT_ID_ARRIVAL->FldCaption(), $flight->AIR_PORT_ID_ARRIVAL->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_AIR_PORT_ID_ARRIVAL");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($flight->AIR_PORT_ID_ARRIVAL->FldErrMsg()) ?>");

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
fflightedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fflightedit.ValidateRequired = true;
<?php } else { ?>
fflightedit.ValidateRequired = false; 
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
<?php $flight_edit->ShowPageHeader(); ?>
<?php
$flight_edit->ShowMessage();
?>
<form name="fflightedit" id="fflightedit" class="<?php echo $flight_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($flight_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $flight_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="flight">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($flight->FLIGHT_ID->Visible) { // FLIGHT_ID ?>
	<div id="r_FLIGHT_ID" class="form-group">
		<label id="elh_flight_FLIGHT_ID" class="col-sm-2 control-label ewLabel"><?php echo $flight->FLIGHT_ID->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $flight->FLIGHT_ID->CellAttributes() ?>>
<span id="el_flight_FLIGHT_ID">
<span<?php echo $flight->FLIGHT_ID->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $flight->FLIGHT_ID->EditValue ?></p></span>
</span>
<input type="hidden" data-table="flight" data-field="x_FLIGHT_ID" name="x_FLIGHT_ID" id="x_FLIGHT_ID" value="<?php echo ew_HtmlEncode($flight->FLIGHT_ID->CurrentValue) ?>">
<?php echo $flight->FLIGHT_ID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($flight->CODE->Visible) { // CODE ?>
	<div id="r_CODE" class="form-group">
		<label id="elh_flight_CODE" for="x_CODE" class="col-sm-2 control-label ewLabel"><?php echo $flight->CODE->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $flight->CODE->CellAttributes() ?>>
<span id="el_flight_CODE">
<input type="text" data-table="flight" data-field="x_CODE" name="x_CODE" id="x_CODE" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($flight->CODE->getPlaceHolder()) ?>" value="<?php echo $flight->CODE->EditValue ?>"<?php echo $flight->CODE->EditAttributes() ?>>
</span>
<?php echo $flight->CODE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($flight->DESCRIPTION->Visible) { // DESCRIPTION ?>
	<div id="r_DESCRIPTION" class="form-group">
		<label id="elh_flight_DESCRIPTION" for="x_DESCRIPTION" class="col-sm-2 control-label ewLabel"><?php echo $flight->DESCRIPTION->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $flight->DESCRIPTION->CellAttributes() ?>>
<span id="el_flight_DESCRIPTION">
<input type="text" data-table="flight" data-field="x_DESCRIPTION" name="x_DESCRIPTION" id="x_DESCRIPTION" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($flight->DESCRIPTION->getPlaceHolder()) ?>" value="<?php echo $flight->DESCRIPTION->EditValue ?>"<?php echo $flight->DESCRIPTION->EditAttributes() ?>>
</span>
<?php echo $flight->DESCRIPTION->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($flight->AIRPLANE_ID->Visible) { // AIRPLANE_ID ?>
	<div id="r_AIRPLANE_ID" class="form-group">
		<label id="elh_flight_AIRPLANE_ID" for="x_AIRPLANE_ID" class="col-sm-2 control-label ewLabel"><?php echo $flight->AIRPLANE_ID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $flight->AIRPLANE_ID->CellAttributes() ?>>
<span id="el_flight_AIRPLANE_ID">
<input type="text" data-table="flight" data-field="x_AIRPLANE_ID" name="x_AIRPLANE_ID" id="x_AIRPLANE_ID" size="30" placeholder="<?php echo ew_HtmlEncode($flight->AIRPLANE_ID->getPlaceHolder()) ?>" value="<?php echo $flight->AIRPLANE_ID->EditValue ?>"<?php echo $flight->AIRPLANE_ID->EditAttributes() ?>>
</span>
<?php echo $flight->AIRPLANE_ID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($flight->AIR_PORT_ID_DEPARTURE->Visible) { // AIR_PORT_ID_DEPARTURE ?>
	<div id="r_AIR_PORT_ID_DEPARTURE" class="form-group">
		<label id="elh_flight_AIR_PORT_ID_DEPARTURE" for="x_AIR_PORT_ID_DEPARTURE" class="col-sm-2 control-label ewLabel"><?php echo $flight->AIR_PORT_ID_DEPARTURE->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $flight->AIR_PORT_ID_DEPARTURE->CellAttributes() ?>>
<span id="el_flight_AIR_PORT_ID_DEPARTURE">
<input type="text" data-table="flight" data-field="x_AIR_PORT_ID_DEPARTURE" name="x_AIR_PORT_ID_DEPARTURE" id="x_AIR_PORT_ID_DEPARTURE" size="30" placeholder="<?php echo ew_HtmlEncode($flight->AIR_PORT_ID_DEPARTURE->getPlaceHolder()) ?>" value="<?php echo $flight->AIR_PORT_ID_DEPARTURE->EditValue ?>"<?php echo $flight->AIR_PORT_ID_DEPARTURE->EditAttributes() ?>>
</span>
<?php echo $flight->AIR_PORT_ID_DEPARTURE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($flight->AIR_PORT_ID_ARRIVAL->Visible) { // AIR_PORT_ID_ARRIVAL ?>
	<div id="r_AIR_PORT_ID_ARRIVAL" class="form-group">
		<label id="elh_flight_AIR_PORT_ID_ARRIVAL" for="x_AIR_PORT_ID_ARRIVAL" class="col-sm-2 control-label ewLabel"><?php echo $flight->AIR_PORT_ID_ARRIVAL->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $flight->AIR_PORT_ID_ARRIVAL->CellAttributes() ?>>
<span id="el_flight_AIR_PORT_ID_ARRIVAL">
<input type="text" data-table="flight" data-field="x_AIR_PORT_ID_ARRIVAL" name="x_AIR_PORT_ID_ARRIVAL" id="x_AIR_PORT_ID_ARRIVAL" size="30" placeholder="<?php echo ew_HtmlEncode($flight->AIR_PORT_ID_ARRIVAL->getPlaceHolder()) ?>" value="<?php echo $flight->AIR_PORT_ID_ARRIVAL->EditValue ?>"<?php echo $flight->AIR_PORT_ID_ARRIVAL->EditAttributes() ?>>
</span>
<?php echo $flight->AIR_PORT_ID_ARRIVAL->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $flight_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fflightedit.Init();
</script>
<?php
$flight_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$flight_edit->Page_Terminate();
?>
