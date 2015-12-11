<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "bank_accountinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$bank_account_add = NULL; // Initialize page object first

class cbank_account_add extends cbank_account {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{B2F6402D-7C0A-4760-82AD-045088CEBA18}";

	// Table name
	var $TableName = 'bank_account';

	// Page object name
	var $PageObjName = 'bank_account_add';

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

		// Table object (bank_account)
		if (!isset($GLOBALS["bank_account"]) || get_class($GLOBALS["bank_account"]) == "cbank_account") {
			$GLOBALS["bank_account"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["bank_account"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'bank_account', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("bank_accountlist.php"));
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
		global $EW_EXPORT, $bank_account;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($bank_account);
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
			if (@$_GET["BANK_ACCOUNT_ID"] != "") {
				$this->BANK_ACCOUNT_ID->setQueryStringValue($_GET["BANK_ACCOUNT_ID"]);
				$this->setKey("BANK_ACCOUNT_ID", $this->BANK_ACCOUNT_ID->CurrentValue); // Set up key
			} else {
				$this->setKey("BANK_ACCOUNT_ID", ""); // Clear key
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
					$this->Page_Terminate("bank_accountlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "bank_accountlist.php")
						$sReturnUrl = $this->AddMasterUrl($this->GetListUrl()); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "bank_accountview.php")
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
		$this->ACCOUNT_NUMBER->CurrentValue = NULL;
		$this->ACCOUNT_NUMBER->OldValue = $this->ACCOUNT_NUMBER->CurrentValue;
		$this->BANK_ID->CurrentValue = NULL;
		$this->BANK_ID->OldValue = $this->BANK_ID->CurrentValue;
		$this->ACTIVE->CurrentValue = 1;
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
		if (!$this->ACCOUNT_NUMBER->FldIsDetailKey) {
			$this->ACCOUNT_NUMBER->setFormValue($objForm->GetValue("x_ACCOUNT_NUMBER"));
		}
		if (!$this->BANK_ID->FldIsDetailKey) {
			$this->BANK_ID->setFormValue($objForm->GetValue("x_BANK_ID"));
		}
		if (!$this->ACTIVE->FldIsDetailKey) {
			$this->ACTIVE->setFormValue($objForm->GetValue("x_ACTIVE"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->CODE->CurrentValue = $this->CODE->FormValue;
		$this->DESCRIPTION->CurrentValue = $this->DESCRIPTION->FormValue;
		$this->ACCOUNT_NUMBER->CurrentValue = $this->ACCOUNT_NUMBER->FormValue;
		$this->BANK_ID->CurrentValue = $this->BANK_ID->FormValue;
		$this->ACTIVE->CurrentValue = $this->ACTIVE->FormValue;
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
		$this->BANK_ACCOUNT_ID->setDbValue($rs->fields('BANK_ACCOUNT_ID'));
		$this->CODE->setDbValue($rs->fields('CODE'));
		$this->DESCRIPTION->setDbValue($rs->fields('DESCRIPTION'));
		$this->ACCOUNT_NUMBER->setDbValue($rs->fields('ACCOUNT_NUMBER'));
		$this->BANK_ID->setDbValue($rs->fields('BANK_ID'));
		$this->ACTIVE->setDbValue($rs->fields('ACTIVE'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->BANK_ACCOUNT_ID->DbValue = $row['BANK_ACCOUNT_ID'];
		$this->CODE->DbValue = $row['CODE'];
		$this->DESCRIPTION->DbValue = $row['DESCRIPTION'];
		$this->ACCOUNT_NUMBER->DbValue = $row['ACCOUNT_NUMBER'];
		$this->BANK_ID->DbValue = $row['BANK_ID'];
		$this->ACTIVE->DbValue = $row['ACTIVE'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("BANK_ACCOUNT_ID")) <> "")
			$this->BANK_ACCOUNT_ID->CurrentValue = $this->getKey("BANK_ACCOUNT_ID"); // BANK_ACCOUNT_ID
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

		if ($this->ACCOUNT_NUMBER->FormValue == $this->ACCOUNT_NUMBER->CurrentValue && is_numeric(ew_StrToFloat($this->ACCOUNT_NUMBER->CurrentValue)))
			$this->ACCOUNT_NUMBER->CurrentValue = ew_StrToFloat($this->ACCOUNT_NUMBER->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// BANK_ACCOUNT_ID
		// CODE
		// DESCRIPTION
		// ACCOUNT_NUMBER
		// BANK_ID
		// ACTIVE

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// CODE
		$this->CODE->ViewValue = $this->CODE->CurrentValue;
		$this->CODE->ViewCustomAttributes = "";

		// DESCRIPTION
		$this->DESCRIPTION->ViewValue = $this->DESCRIPTION->CurrentValue;
		$this->DESCRIPTION->ViewCustomAttributes = "";

		// ACCOUNT_NUMBER
		$this->ACCOUNT_NUMBER->ViewValue = $this->ACCOUNT_NUMBER->CurrentValue;
		$this->ACCOUNT_NUMBER->ViewCustomAttributes = "";

		// BANK_ID
		if (strval($this->BANK_ID->CurrentValue) <> "") {
			$sFilterWrk = "`BANK_ID`" . ew_SearchString("=", $this->BANK_ID->CurrentValue, EW_DATATYPE_NUMBER, "");
		switch (@$gsLanguage) {
			case "es":
				$sSqlWrk = "SELECT `BANK_ID`, `DESCRIPTION` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `bank`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `BANK_ID`, `DESCRIPTION` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `bank`";
				$sWhereWrk = "";
				break;
		}
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->BANK_ID, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->BANK_ID->ViewValue = $this->BANK_ID->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->BANK_ID->ViewValue = $this->BANK_ID->CurrentValue;
			}
		} else {
			$this->BANK_ID->ViewValue = NULL;
		}
		$this->BANK_ID->ViewCustomAttributes = "";

		// ACTIVE
		if (strval($this->ACTIVE->CurrentValue) <> "") {
			$this->ACTIVE->ViewValue = $this->ACTIVE->OptionCaption($this->ACTIVE->CurrentValue);
		} else {
			$this->ACTIVE->ViewValue = NULL;
		}
		$this->ACTIVE->ViewCustomAttributes = "";

			// CODE
			$this->CODE->LinkCustomAttributes = "";
			$this->CODE->HrefValue = "";
			$this->CODE->TooltipValue = "";

			// DESCRIPTION
			$this->DESCRIPTION->LinkCustomAttributes = "";
			$this->DESCRIPTION->HrefValue = "";
			$this->DESCRIPTION->TooltipValue = "";

			// ACCOUNT_NUMBER
			$this->ACCOUNT_NUMBER->LinkCustomAttributes = "";
			$this->ACCOUNT_NUMBER->HrefValue = "";
			$this->ACCOUNT_NUMBER->TooltipValue = "";

			// BANK_ID
			$this->BANK_ID->LinkCustomAttributes = "";
			$this->BANK_ID->HrefValue = "";
			$this->BANK_ID->TooltipValue = "";

			// ACTIVE
			$this->ACTIVE->LinkCustomAttributes = "";
			$this->ACTIVE->HrefValue = "";
			$this->ACTIVE->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

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

			// ACCOUNT_NUMBER
			$this->ACCOUNT_NUMBER->EditAttrs["class"] = "form-control";
			$this->ACCOUNT_NUMBER->EditCustomAttributes = "";
			$this->ACCOUNT_NUMBER->EditValue = ew_HtmlEncode($this->ACCOUNT_NUMBER->CurrentValue);
			$this->ACCOUNT_NUMBER->PlaceHolder = ew_RemoveHtml($this->ACCOUNT_NUMBER->FldCaption());
			if (strval($this->ACCOUNT_NUMBER->EditValue) <> "" && is_numeric($this->ACCOUNT_NUMBER->EditValue)) $this->ACCOUNT_NUMBER->EditValue = ew_FormatNumber($this->ACCOUNT_NUMBER->EditValue, -2, -1, -2, 0);

			// BANK_ID
			$this->BANK_ID->EditAttrs["class"] = "form-control";
			$this->BANK_ID->EditCustomAttributes = "";
			if (trim(strval($this->BANK_ID->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`BANK_ID`" . ew_SearchString("=", $this->BANK_ID->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			switch (@$gsLanguage) {
				case "es":
					$sSqlWrk = "SELECT `BANK_ID`, `DESCRIPTION` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `bank`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `BANK_ID`, `DESCRIPTION` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `bank`";
					$sWhereWrk = "";
					break;
			}
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->BANK_ID, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->BANK_ID->EditValue = $arwrk;

			// ACTIVE
			$this->ACTIVE->EditAttrs["class"] = "form-control";
			$this->ACTIVE->EditCustomAttributes = "";
			$this->ACTIVE->EditValue = $this->ACTIVE->Options(TRUE);

			// Add refer script
			// CODE

			$this->CODE->LinkCustomAttributes = "";
			$this->CODE->HrefValue = "";

			// DESCRIPTION
			$this->DESCRIPTION->LinkCustomAttributes = "";
			$this->DESCRIPTION->HrefValue = "";

			// ACCOUNT_NUMBER
			$this->ACCOUNT_NUMBER->LinkCustomAttributes = "";
			$this->ACCOUNT_NUMBER->HrefValue = "";

			// BANK_ID
			$this->BANK_ID->LinkCustomAttributes = "";
			$this->BANK_ID->HrefValue = "";

			// ACTIVE
			$this->ACTIVE->LinkCustomAttributes = "";
			$this->ACTIVE->HrefValue = "";
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
		if (!$this->DESCRIPTION->FldIsDetailKey && !is_null($this->DESCRIPTION->FormValue) && $this->DESCRIPTION->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->DESCRIPTION->FldCaption(), $this->DESCRIPTION->ReqErrMsg));
		}
		if (!$this->ACCOUNT_NUMBER->FldIsDetailKey && !is_null($this->ACCOUNT_NUMBER->FormValue) && $this->ACCOUNT_NUMBER->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ACCOUNT_NUMBER->FldCaption(), $this->ACCOUNT_NUMBER->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->ACCOUNT_NUMBER->FormValue)) {
			ew_AddMessage($gsFormError, $this->ACCOUNT_NUMBER->FldErrMsg());
		}
		if (!$this->BANK_ID->FldIsDetailKey && !is_null($this->BANK_ID->FormValue) && $this->BANK_ID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->BANK_ID->FldCaption(), $this->BANK_ID->ReqErrMsg));
		}
		if (!$this->ACTIVE->FldIsDetailKey && !is_null($this->ACTIVE->FormValue) && $this->ACTIVE->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ACTIVE->FldCaption(), $this->ACTIVE->ReqErrMsg));
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
		$this->CODE->SetDbValueDef($rsnew, $this->CODE->CurrentValue, NULL, FALSE);

		// DESCRIPTION
		$this->DESCRIPTION->SetDbValueDef($rsnew, $this->DESCRIPTION->CurrentValue, "", FALSE);

		// ACCOUNT_NUMBER
		$this->ACCOUNT_NUMBER->SetDbValueDef($rsnew, $this->ACCOUNT_NUMBER->CurrentValue, 0, FALSE);

		// BANK_ID
		$this->BANK_ID->SetDbValueDef($rsnew, $this->BANK_ID->CurrentValue, 0, FALSE);

		// ACTIVE
		$this->ACTIVE->SetDbValueDef($rsnew, $this->ACTIVE->CurrentValue, 0, strval($this->ACTIVE->CurrentValue) == "");

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->BANK_ACCOUNT_ID->setDbValue($conn->Insert_ID());
				$rsnew['BANK_ACCOUNT_ID'] = $this->BANK_ACCOUNT_ID->DbValue;
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("bank_accountlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($bank_account_add)) $bank_account_add = new cbank_account_add();

// Page init
$bank_account_add->Page_Init();

// Page main
$bank_account_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$bank_account_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fbank_accountadd = new ew_Form("fbank_accountadd", "add");

// Validate form
fbank_accountadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_DESCRIPTION");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $bank_account->DESCRIPTION->FldCaption(), $bank_account->DESCRIPTION->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ACCOUNT_NUMBER");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $bank_account->ACCOUNT_NUMBER->FldCaption(), $bank_account->ACCOUNT_NUMBER->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ACCOUNT_NUMBER");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($bank_account->ACCOUNT_NUMBER->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_BANK_ID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $bank_account->BANK_ID->FldCaption(), $bank_account->BANK_ID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ACTIVE");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $bank_account->ACTIVE->FldCaption(), $bank_account->ACTIVE->ReqErrMsg)) ?>");

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
fbank_accountadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fbank_accountadd.ValidateRequired = true;
<?php } else { ?>
fbank_accountadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fbank_accountadd.Lists["x_BANK_ID"] = {"LinkField":"x_BANK_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_DESCRIPTION","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fbank_accountadd.Lists["x_ACTIVE"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fbank_accountadd.Lists["x_ACTIVE"].Options = <?php echo json_encode($bank_account->ACTIVE->Options()) ?>;

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
<?php $bank_account_add->ShowPageHeader(); ?>
<?php
$bank_account_add->ShowMessage();
?>
<form name="fbank_accountadd" id="fbank_accountadd" class="<?php echo $bank_account_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($bank_account_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $bank_account_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="bank_account">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($bank_account->CODE->Visible) { // CODE ?>
	<div id="r_CODE" class="form-group">
		<label id="elh_bank_account_CODE" for="x_CODE" class="col-sm-2 control-label ewLabel"><?php echo $bank_account->CODE->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $bank_account->CODE->CellAttributes() ?>>
<span id="el_bank_account_CODE">
<input type="text" data-table="bank_account" data-field="x_CODE" name="x_CODE" id="x_CODE" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($bank_account->CODE->getPlaceHolder()) ?>" value="<?php echo $bank_account->CODE->EditValue ?>"<?php echo $bank_account->CODE->EditAttributes() ?>>
</span>
<?php echo $bank_account->CODE->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($bank_account->DESCRIPTION->Visible) { // DESCRIPTION ?>
	<div id="r_DESCRIPTION" class="form-group">
		<label id="elh_bank_account_DESCRIPTION" for="x_DESCRIPTION" class="col-sm-2 control-label ewLabel"><?php echo $bank_account->DESCRIPTION->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $bank_account->DESCRIPTION->CellAttributes() ?>>
<span id="el_bank_account_DESCRIPTION">
<input type="text" data-table="bank_account" data-field="x_DESCRIPTION" name="x_DESCRIPTION" id="x_DESCRIPTION" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($bank_account->DESCRIPTION->getPlaceHolder()) ?>" value="<?php echo $bank_account->DESCRIPTION->EditValue ?>"<?php echo $bank_account->DESCRIPTION->EditAttributes() ?>>
</span>
<?php echo $bank_account->DESCRIPTION->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($bank_account->ACCOUNT_NUMBER->Visible) { // ACCOUNT_NUMBER ?>
	<div id="r_ACCOUNT_NUMBER" class="form-group">
		<label id="elh_bank_account_ACCOUNT_NUMBER" for="x_ACCOUNT_NUMBER" class="col-sm-2 control-label ewLabel"><?php echo $bank_account->ACCOUNT_NUMBER->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $bank_account->ACCOUNT_NUMBER->CellAttributes() ?>>
<span id="el_bank_account_ACCOUNT_NUMBER">
<input type="text" data-table="bank_account" data-field="x_ACCOUNT_NUMBER" name="x_ACCOUNT_NUMBER" id="x_ACCOUNT_NUMBER" size="30" placeholder="<?php echo ew_HtmlEncode($bank_account->ACCOUNT_NUMBER->getPlaceHolder()) ?>" value="<?php echo $bank_account->ACCOUNT_NUMBER->EditValue ?>"<?php echo $bank_account->ACCOUNT_NUMBER->EditAttributes() ?>>
</span>
<?php echo $bank_account->ACCOUNT_NUMBER->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($bank_account->BANK_ID->Visible) { // BANK_ID ?>
	<div id="r_BANK_ID" class="form-group">
		<label id="elh_bank_account_BANK_ID" for="x_BANK_ID" class="col-sm-2 control-label ewLabel"><?php echo $bank_account->BANK_ID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $bank_account->BANK_ID->CellAttributes() ?>>
<span id="el_bank_account_BANK_ID">
<select data-table="bank_account" data-field="x_BANK_ID" data-value-separator="<?php echo ew_HtmlEncode(is_array($bank_account->BANK_ID->DisplayValueSeparator) ? json_encode($bank_account->BANK_ID->DisplayValueSeparator) : $bank_account->BANK_ID->DisplayValueSeparator) ?>" id="x_BANK_ID" name="x_BANK_ID"<?php echo $bank_account->BANK_ID->EditAttributes() ?>>
<?php
if (is_array($bank_account->BANK_ID->EditValue)) {
	$arwrk = $bank_account->BANK_ID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($bank_account->BANK_ID->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $bank_account->BANK_ID->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($bank_account->BANK_ID->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($bank_account->BANK_ID->CurrentValue) ?>" selected><?php echo $bank_account->BANK_ID->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
switch (@$gsLanguage) {
	case "es":
		$sSqlWrk = "SELECT `BANK_ID`, `DESCRIPTION` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `bank`";
		$sWhereWrk = "";
		break;
	default:
		$sSqlWrk = "SELECT `BANK_ID`, `DESCRIPTION` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `bank`";
		$sWhereWrk = "";
		break;
}
$bank_account->BANK_ID->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$bank_account->BANK_ID->LookupFilters += array("f0" => "`BANK_ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$bank_account->Lookup_Selecting($bank_account->BANK_ID, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $bank_account->BANK_ID->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_BANK_ID" id="s_x_BANK_ID" value="<?php echo $bank_account->BANK_ID->LookupFilterQuery() ?>">
</span>
<?php echo $bank_account->BANK_ID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($bank_account->ACTIVE->Visible) { // ACTIVE ?>
	<div id="r_ACTIVE" class="form-group">
		<label id="elh_bank_account_ACTIVE" for="x_ACTIVE" class="col-sm-2 control-label ewLabel"><?php echo $bank_account->ACTIVE->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $bank_account->ACTIVE->CellAttributes() ?>>
<span id="el_bank_account_ACTIVE">
<select data-table="bank_account" data-field="x_ACTIVE" data-value-separator="<?php echo ew_HtmlEncode(is_array($bank_account->ACTIVE->DisplayValueSeparator) ? json_encode($bank_account->ACTIVE->DisplayValueSeparator) : $bank_account->ACTIVE->DisplayValueSeparator) ?>" id="x_ACTIVE" name="x_ACTIVE"<?php echo $bank_account->ACTIVE->EditAttributes() ?>>
<?php
if (is_array($bank_account->ACTIVE->EditValue)) {
	$arwrk = $bank_account->ACTIVE->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($bank_account->ACTIVE->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $bank_account->ACTIVE->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($bank_account->ACTIVE->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($bank_account->ACTIVE->CurrentValue) ?>" selected><?php echo $bank_account->ACTIVE->CurrentValue ?></option>
<?php
    }
}
?>
</select>
</span>
<?php echo $bank_account->ACTIVE->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $bank_account_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fbank_accountadd.Init();
</script>
<?php
$bank_account_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$bank_account_add->Page_Terminate();
?>
