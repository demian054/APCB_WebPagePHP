<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "cardinfo.php" ?>
<?php include_once "userinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$card_add = NULL; // Initialize page object first

class ccard_add extends ccard {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{B2F6402D-7C0A-4760-82AD-045088CEBA18}";

	// Table name
	var $TableName = 'card';

	// Page object name
	var $PageObjName = 'card_add';

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

		// Table object (card)
		if (!isset($GLOBALS["card"]) || get_class($GLOBALS["card"]) == "ccard") {
			$GLOBALS["card"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["card"];
		}

		// Table object (user)
		if (!isset($GLOBALS['user'])) $GLOBALS['user'] = new cuser();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'card', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("cardlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
			if (strval($Security->CurrentUserID()) == "") {
				$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
				$this->Page_Terminate(ew_GetUrl("cardlist.php"));
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
		global $EW_EXPORT, $card;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($card);
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
			if (@$_GET["CARD_ID"] != "") {
				$this->CARD_ID->setQueryStringValue($_GET["CARD_ID"]);
				$this->setKey("CARD_ID", $this->CARD_ID->CurrentValue); // Set up key
			} else {
				$this->setKey("CARD_ID", ""); // Clear key
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
					$this->Page_Terminate("cardlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "cardlist.php")
						$sReturnUrl = $this->AddMasterUrl($this->GetListUrl()); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "cardview.php")
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
		$this->NAME_IN_CARD->CurrentValue = NULL;
		$this->NAME_IN_CARD->OldValue = $this->NAME_IN_CARD->CurrentValue;
		$this->NUMBER->CurrentValue = NULL;
		$this->NUMBER->OldValue = $this->NUMBER->CurrentValue;
		$this->CARD_TYPE_ID->CurrentValue = NULL;
		$this->CARD_TYPE_ID->OldValue = $this->CARD_TYPE_ID->CurrentValue;
		$this->BANK_ID->CurrentValue = NULL;
		$this->BANK_ID->OldValue = $this->BANK_ID->CurrentValue;
		$this->VALID_THRU_MONTH->CurrentValue = NULL;
		$this->VALID_THRU_MONTH->OldValue = $this->VALID_THRU_MONTH->CurrentValue;
		$this->VALID_THRU_YEAR->CurrentValue = NULL;
		$this->VALID_THRU_YEAR->OldValue = $this->VALID_THRU_YEAR->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->NAME_IN_CARD->FldIsDetailKey) {
			$this->NAME_IN_CARD->setFormValue($objForm->GetValue("x_NAME_IN_CARD"));
		}
		if (!$this->NUMBER->FldIsDetailKey) {
			$this->NUMBER->setFormValue($objForm->GetValue("x_NUMBER"));
		}
		if (!$this->CARD_TYPE_ID->FldIsDetailKey) {
			$this->CARD_TYPE_ID->setFormValue($objForm->GetValue("x_CARD_TYPE_ID"));
		}
		if (!$this->BANK_ID->FldIsDetailKey) {
			$this->BANK_ID->setFormValue($objForm->GetValue("x_BANK_ID"));
		}
		if (!$this->VALID_THRU_MONTH->FldIsDetailKey) {
			$this->VALID_THRU_MONTH->setFormValue($objForm->GetValue("x_VALID_THRU_MONTH"));
		}
		if (!$this->VALID_THRU_YEAR->FldIsDetailKey) {
			$this->VALID_THRU_YEAR->setFormValue($objForm->GetValue("x_VALID_THRU_YEAR"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->NAME_IN_CARD->CurrentValue = $this->NAME_IN_CARD->FormValue;
		$this->NUMBER->CurrentValue = $this->NUMBER->FormValue;
		$this->CARD_TYPE_ID->CurrentValue = $this->CARD_TYPE_ID->FormValue;
		$this->BANK_ID->CurrentValue = $this->BANK_ID->FormValue;
		$this->VALID_THRU_MONTH->CurrentValue = $this->VALID_THRU_MONTH->FormValue;
		$this->VALID_THRU_YEAR->CurrentValue = $this->VALID_THRU_YEAR->FormValue;
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
			$res = $this->ShowOptionLink('add');
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
		$this->CARD_ID->setDbValue($rs->fields('CARD_ID'));
		$this->NAME_IN_CARD->setDbValue($rs->fields('NAME_IN_CARD'));
		$this->NUMBER->setDbValue($rs->fields('NUMBER'));
		$this->CARD_TYPE_ID->setDbValue($rs->fields('CARD_TYPE_ID'));
		$this->USER_ID->setDbValue($rs->fields('USER_ID'));
		$this->BANK_ID->setDbValue($rs->fields('BANK_ID'));
		$this->VALID_THRU_MONTH->setDbValue($rs->fields('VALID_THRU_MONTH'));
		$this->VALID_THRU_YEAR->setDbValue($rs->fields('VALID_THRU_YEAR'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->CARD_ID->DbValue = $row['CARD_ID'];
		$this->NAME_IN_CARD->DbValue = $row['NAME_IN_CARD'];
		$this->NUMBER->DbValue = $row['NUMBER'];
		$this->CARD_TYPE_ID->DbValue = $row['CARD_TYPE_ID'];
		$this->USER_ID->DbValue = $row['USER_ID'];
		$this->BANK_ID->DbValue = $row['BANK_ID'];
		$this->VALID_THRU_MONTH->DbValue = $row['VALID_THRU_MONTH'];
		$this->VALID_THRU_YEAR->DbValue = $row['VALID_THRU_YEAR'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("CARD_ID")) <> "")
			$this->CARD_ID->CurrentValue = $this->getKey("CARD_ID"); // CARD_ID
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

		if ($this->NUMBER->FormValue == $this->NUMBER->CurrentValue && is_numeric(ew_StrToFloat($this->NUMBER->CurrentValue)))
			$this->NUMBER->CurrentValue = ew_StrToFloat($this->NUMBER->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// CARD_ID
		// NAME_IN_CARD
		// NUMBER
		// CARD_TYPE_ID
		// USER_ID
		// BANK_ID
		// VALID_THRU_MONTH
		// VALID_THRU_YEAR

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// NAME_IN_CARD
		$this->NAME_IN_CARD->ViewValue = $this->NAME_IN_CARD->CurrentValue;
		$this->NAME_IN_CARD->ViewCustomAttributes = "";

		// NUMBER
		$this->NUMBER->ViewValue = $this->NUMBER->CurrentValue;
		$this->NUMBER->ViewCustomAttributes = "";

		// CARD_TYPE_ID
		if (strval($this->CARD_TYPE_ID->CurrentValue) <> "") {
			$sFilterWrk = "`CARD_TYPE_ID`" . ew_SearchString("=", $this->CARD_TYPE_ID->CurrentValue, EW_DATATYPE_NUMBER, "");
		switch (@$gsLanguage) {
			case "es":
				$sSqlWrk = "SELECT `CARD_TYPE_ID`, `DESCRIPTION` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `card_type`";
				$sWhereWrk = "";
				break;
			default:
				$sSqlWrk = "SELECT `CARD_TYPE_ID`, `DESCRIPTION` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `card_type`";
				$sWhereWrk = "";
				break;
		}
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->CARD_TYPE_ID, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->CARD_TYPE_ID->ViewValue = $this->CARD_TYPE_ID->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->CARD_TYPE_ID->ViewValue = $this->CARD_TYPE_ID->CurrentValue;
			}
		} else {
			$this->CARD_TYPE_ID->ViewValue = NULL;
		}
		$this->CARD_TYPE_ID->ViewCustomAttributes = "";

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

		// VALID_THRU_MONTH
		$this->VALID_THRU_MONTH->ViewValue = $this->VALID_THRU_MONTH->CurrentValue;
		$this->VALID_THRU_MONTH->ViewCustomAttributes = "";

		// VALID_THRU_YEAR
		$this->VALID_THRU_YEAR->ViewValue = $this->VALID_THRU_YEAR->CurrentValue;
		$this->VALID_THRU_YEAR->ViewCustomAttributes = "";

			// NAME_IN_CARD
			$this->NAME_IN_CARD->LinkCustomAttributes = "";
			$this->NAME_IN_CARD->HrefValue = "";
			$this->NAME_IN_CARD->TooltipValue = "";

			// NUMBER
			$this->NUMBER->LinkCustomAttributes = "";
			$this->NUMBER->HrefValue = "";
			$this->NUMBER->TooltipValue = "";

			// CARD_TYPE_ID
			$this->CARD_TYPE_ID->LinkCustomAttributes = "";
			$this->CARD_TYPE_ID->HrefValue = "";
			$this->CARD_TYPE_ID->TooltipValue = "";

			// BANK_ID
			$this->BANK_ID->LinkCustomAttributes = "";
			$this->BANK_ID->HrefValue = "";
			$this->BANK_ID->TooltipValue = "";

			// VALID_THRU_MONTH
			$this->VALID_THRU_MONTH->LinkCustomAttributes = "";
			$this->VALID_THRU_MONTH->HrefValue = "";
			$this->VALID_THRU_MONTH->TooltipValue = "";

			// VALID_THRU_YEAR
			$this->VALID_THRU_YEAR->LinkCustomAttributes = "";
			$this->VALID_THRU_YEAR->HrefValue = "";
			$this->VALID_THRU_YEAR->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// NAME_IN_CARD
			$this->NAME_IN_CARD->EditAttrs["class"] = "form-control";
			$this->NAME_IN_CARD->EditCustomAttributes = "";
			$this->NAME_IN_CARD->EditValue = ew_HtmlEncode($this->NAME_IN_CARD->CurrentValue);
			$this->NAME_IN_CARD->PlaceHolder = ew_RemoveHtml($this->NAME_IN_CARD->FldCaption());

			// NUMBER
			$this->NUMBER->EditAttrs["class"] = "form-control";
			$this->NUMBER->EditCustomAttributes = "";
			$this->NUMBER->EditValue = ew_HtmlEncode($this->NUMBER->CurrentValue);
			$this->NUMBER->PlaceHolder = ew_RemoveHtml($this->NUMBER->FldCaption());
			if (strval($this->NUMBER->EditValue) <> "" && is_numeric($this->NUMBER->EditValue)) $this->NUMBER->EditValue = ew_FormatNumber($this->NUMBER->EditValue, -2, -1, -2, 0);

			// CARD_TYPE_ID
			$this->CARD_TYPE_ID->EditAttrs["class"] = "form-control";
			$this->CARD_TYPE_ID->EditCustomAttributes = "";
			if (trim(strval($this->CARD_TYPE_ID->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`CARD_TYPE_ID`" . ew_SearchString("=", $this->CARD_TYPE_ID->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			switch (@$gsLanguage) {
				case "es":
					$sSqlWrk = "SELECT `CARD_TYPE_ID`, `DESCRIPTION` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `card_type`";
					$sWhereWrk = "";
					break;
				default:
					$sSqlWrk = "SELECT `CARD_TYPE_ID`, `DESCRIPTION` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `card_type`";
					$sWhereWrk = "";
					break;
			}
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->CARD_TYPE_ID, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->CARD_TYPE_ID->EditValue = $arwrk;

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

			// VALID_THRU_MONTH
			$this->VALID_THRU_MONTH->EditAttrs["class"] = "form-control";
			$this->VALID_THRU_MONTH->EditCustomAttributes = "";
			$this->VALID_THRU_MONTH->EditValue = ew_HtmlEncode($this->VALID_THRU_MONTH->CurrentValue);
			$this->VALID_THRU_MONTH->PlaceHolder = ew_RemoveHtml($this->VALID_THRU_MONTH->FldCaption());

			// VALID_THRU_YEAR
			$this->VALID_THRU_YEAR->EditAttrs["class"] = "form-control";
			$this->VALID_THRU_YEAR->EditCustomAttributes = "";
			$this->VALID_THRU_YEAR->EditValue = ew_HtmlEncode($this->VALID_THRU_YEAR->CurrentValue);
			$this->VALID_THRU_YEAR->PlaceHolder = ew_RemoveHtml($this->VALID_THRU_YEAR->FldCaption());

			// Add refer script
			// NAME_IN_CARD

			$this->NAME_IN_CARD->LinkCustomAttributes = "";
			$this->NAME_IN_CARD->HrefValue = "";

			// NUMBER
			$this->NUMBER->LinkCustomAttributes = "";
			$this->NUMBER->HrefValue = "";

			// CARD_TYPE_ID
			$this->CARD_TYPE_ID->LinkCustomAttributes = "";
			$this->CARD_TYPE_ID->HrefValue = "";

			// BANK_ID
			$this->BANK_ID->LinkCustomAttributes = "";
			$this->BANK_ID->HrefValue = "";

			// VALID_THRU_MONTH
			$this->VALID_THRU_MONTH->LinkCustomAttributes = "";
			$this->VALID_THRU_MONTH->HrefValue = "";

			// VALID_THRU_YEAR
			$this->VALID_THRU_YEAR->LinkCustomAttributes = "";
			$this->VALID_THRU_YEAR->HrefValue = "";
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
		if (!$this->NAME_IN_CARD->FldIsDetailKey && !is_null($this->NAME_IN_CARD->FormValue) && $this->NAME_IN_CARD->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->NAME_IN_CARD->FldCaption(), $this->NAME_IN_CARD->ReqErrMsg));
		}
		if (!$this->NUMBER->FldIsDetailKey && !is_null($this->NUMBER->FormValue) && $this->NUMBER->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->NUMBER->FldCaption(), $this->NUMBER->ReqErrMsg));
		}
		if (!ew_CheckCreditCard($this->NUMBER->FormValue)) {
			ew_AddMessage($gsFormError, $this->NUMBER->FldErrMsg());
		}
		if (!$this->CARD_TYPE_ID->FldIsDetailKey && !is_null($this->CARD_TYPE_ID->FormValue) && $this->CARD_TYPE_ID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->CARD_TYPE_ID->FldCaption(), $this->CARD_TYPE_ID->ReqErrMsg));
		}
		if (!$this->BANK_ID->FldIsDetailKey && !is_null($this->BANK_ID->FormValue) && $this->BANK_ID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->BANK_ID->FldCaption(), $this->BANK_ID->ReqErrMsg));
		}
		if (!$this->VALID_THRU_MONTH->FldIsDetailKey && !is_null($this->VALID_THRU_MONTH->FormValue) && $this->VALID_THRU_MONTH->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->VALID_THRU_MONTH->FldCaption(), $this->VALID_THRU_MONTH->ReqErrMsg));
		}
		if (!ew_CheckRange($this->VALID_THRU_MONTH->FormValue, 1, 12)) {
			ew_AddMessage($gsFormError, $this->VALID_THRU_MONTH->FldErrMsg());
		}
		if (!$this->VALID_THRU_YEAR->FldIsDetailKey && !is_null($this->VALID_THRU_YEAR->FormValue) && $this->VALID_THRU_YEAR->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->VALID_THRU_YEAR->FldCaption(), $this->VALID_THRU_YEAR->ReqErrMsg));
		}
		if (!ew_CheckRange($this->VALID_THRU_YEAR->FormValue, 15, 50)) {
			ew_AddMessage($gsFormError, $this->VALID_THRU_YEAR->FldErrMsg());
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

		// NAME_IN_CARD
		$this->NAME_IN_CARD->SetDbValueDef($rsnew, $this->NAME_IN_CARD->CurrentValue, "", FALSE);

		// NUMBER
		$this->NUMBER->SetDbValueDef($rsnew, $this->NUMBER->CurrentValue, 0, FALSE);

		// CARD_TYPE_ID
		$this->CARD_TYPE_ID->SetDbValueDef($rsnew, $this->CARD_TYPE_ID->CurrentValue, 0, FALSE);

		// BANK_ID
		$this->BANK_ID->SetDbValueDef($rsnew, $this->BANK_ID->CurrentValue, 0, FALSE);

		// VALID_THRU_MONTH
		$this->VALID_THRU_MONTH->SetDbValueDef($rsnew, $this->VALID_THRU_MONTH->CurrentValue, 0, FALSE);

		// VALID_THRU_YEAR
		$this->VALID_THRU_YEAR->SetDbValueDef($rsnew, $this->VALID_THRU_YEAR->CurrentValue, 0, FALSE);

		// USER_ID
		if (!$Security->IsAdmin() && $Security->IsLoggedIn()) { // Non system admin
			$rsnew['USER_ID'] = CurrentUserID();
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
				$this->CARD_ID->setDbValue($conn->Insert_ID());
				$rsnew['CARD_ID'] = $this->CARD_ID->DbValue;
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("cardlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($card_add)) $card_add = new ccard_add();

// Page init
$card_add->Page_Init();

// Page main
$card_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$card_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fcardadd = new ew_Form("fcardadd", "add");

// Validate form
fcardadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_NAME_IN_CARD");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $card->NAME_IN_CARD->FldCaption(), $card->NAME_IN_CARD->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_NUMBER");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $card->NUMBER->FldCaption(), $card->NUMBER->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_NUMBER");
			if (elm && !ew_CheckCreditCard(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($card->NUMBER->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_CARD_TYPE_ID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $card->CARD_TYPE_ID->FldCaption(), $card->CARD_TYPE_ID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_BANK_ID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $card->BANK_ID->FldCaption(), $card->BANK_ID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_VALID_THRU_MONTH");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $card->VALID_THRU_MONTH->FldCaption(), $card->VALID_THRU_MONTH->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_VALID_THRU_MONTH");
			if (elm && !ew_CheckRange(elm.value, 1, 12))
				return this.OnError(elm, "<?php echo ew_JsEncode2($card->VALID_THRU_MONTH->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_VALID_THRU_YEAR");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $card->VALID_THRU_YEAR->FldCaption(), $card->VALID_THRU_YEAR->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_VALID_THRU_YEAR");
			if (elm && !ew_CheckRange(elm.value, 15, 50))
				return this.OnError(elm, "<?php echo ew_JsEncode2($card->VALID_THRU_YEAR->FldErrMsg()) ?>");

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
fcardadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcardadd.ValidateRequired = true;
<?php } else { ?>
fcardadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcardadd.Lists["x_CARD_TYPE_ID"] = {"LinkField":"x_CARD_TYPE_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_DESCRIPTION","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcardadd.Lists["x_BANK_ID"] = {"LinkField":"x_BANK_ID","Ajax":true,"AutoFill":false,"DisplayFields":["x_DESCRIPTION","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

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
<?php $card_add->ShowPageHeader(); ?>
<?php
$card_add->ShowMessage();
?>
<form name="fcardadd" id="fcardadd" class="<?php echo $card_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($card_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $card_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="card">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($card->NAME_IN_CARD->Visible) { // NAME_IN_CARD ?>
	<div id="r_NAME_IN_CARD" class="form-group">
		<label id="elh_card_NAME_IN_CARD" for="x_NAME_IN_CARD" class="col-sm-2 control-label ewLabel"><?php echo $card->NAME_IN_CARD->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $card->NAME_IN_CARD->CellAttributes() ?>>
<span id="el_card_NAME_IN_CARD">
<input type="text" data-table="card" data-field="x_NAME_IN_CARD" name="x_NAME_IN_CARD" id="x_NAME_IN_CARD" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($card->NAME_IN_CARD->getPlaceHolder()) ?>" value="<?php echo $card->NAME_IN_CARD->EditValue ?>"<?php echo $card->NAME_IN_CARD->EditAttributes() ?>>
</span>
<?php echo $card->NAME_IN_CARD->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($card->NUMBER->Visible) { // NUMBER ?>
	<div id="r_NUMBER" class="form-group">
		<label id="elh_card_NUMBER" for="x_NUMBER" class="col-sm-2 control-label ewLabel"><?php echo $card->NUMBER->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $card->NUMBER->CellAttributes() ?>>
<span id="el_card_NUMBER">
<input type="text" data-table="card" data-field="x_NUMBER" name="x_NUMBER" id="x_NUMBER" size="30" placeholder="<?php echo ew_HtmlEncode($card->NUMBER->getPlaceHolder()) ?>" value="<?php echo $card->NUMBER->EditValue ?>"<?php echo $card->NUMBER->EditAttributes() ?>>
</span>
<?php echo $card->NUMBER->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($card->CARD_TYPE_ID->Visible) { // CARD_TYPE_ID ?>
	<div id="r_CARD_TYPE_ID" class="form-group">
		<label id="elh_card_CARD_TYPE_ID" for="x_CARD_TYPE_ID" class="col-sm-2 control-label ewLabel"><?php echo $card->CARD_TYPE_ID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $card->CARD_TYPE_ID->CellAttributes() ?>>
<span id="el_card_CARD_TYPE_ID">
<select data-table="card" data-field="x_CARD_TYPE_ID" data-value-separator="<?php echo ew_HtmlEncode(is_array($card->CARD_TYPE_ID->DisplayValueSeparator) ? json_encode($card->CARD_TYPE_ID->DisplayValueSeparator) : $card->CARD_TYPE_ID->DisplayValueSeparator) ?>" id="x_CARD_TYPE_ID" name="x_CARD_TYPE_ID"<?php echo $card->CARD_TYPE_ID->EditAttributes() ?>>
<?php
if (is_array($card->CARD_TYPE_ID->EditValue)) {
	$arwrk = $card->CARD_TYPE_ID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($card->CARD_TYPE_ID->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $card->CARD_TYPE_ID->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($card->CARD_TYPE_ID->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($card->CARD_TYPE_ID->CurrentValue) ?>" selected><?php echo $card->CARD_TYPE_ID->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php
switch (@$gsLanguage) {
	case "es":
		$sSqlWrk = "SELECT `CARD_TYPE_ID`, `DESCRIPTION` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `card_type`";
		$sWhereWrk = "";
		break;
	default:
		$sSqlWrk = "SELECT `CARD_TYPE_ID`, `DESCRIPTION` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `card_type`";
		$sWhereWrk = "";
		break;
}
$card->CARD_TYPE_ID->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$card->CARD_TYPE_ID->LookupFilters += array("f0" => "`CARD_TYPE_ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$card->Lookup_Selecting($card->CARD_TYPE_ID, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $card->CARD_TYPE_ID->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_CARD_TYPE_ID" id="s_x_CARD_TYPE_ID" value="<?php echo $card->CARD_TYPE_ID->LookupFilterQuery() ?>">
</span>
<?php echo $card->CARD_TYPE_ID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($card->BANK_ID->Visible) { // BANK_ID ?>
	<div id="r_BANK_ID" class="form-group">
		<label id="elh_card_BANK_ID" for="x_BANK_ID" class="col-sm-2 control-label ewLabel"><?php echo $card->BANK_ID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $card->BANK_ID->CellAttributes() ?>>
<span id="el_card_BANK_ID">
<select data-table="card" data-field="x_BANK_ID" data-value-separator="<?php echo ew_HtmlEncode(is_array($card->BANK_ID->DisplayValueSeparator) ? json_encode($card->BANK_ID->DisplayValueSeparator) : $card->BANK_ID->DisplayValueSeparator) ?>" id="x_BANK_ID" name="x_BANK_ID"<?php echo $card->BANK_ID->EditAttributes() ?>>
<?php
if (is_array($card->BANK_ID->EditValue)) {
	$arwrk = $card->BANK_ID->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($card->BANK_ID->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $card->BANK_ID->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($card->BANK_ID->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($card->BANK_ID->CurrentValue) ?>" selected><?php echo $card->BANK_ID->CurrentValue ?></option>
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
$card->BANK_ID->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$card->BANK_ID->LookupFilters += array("f0" => "`BANK_ID` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$card->Lookup_Selecting($card->BANK_ID, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $card->BANK_ID->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_BANK_ID" id="s_x_BANK_ID" value="<?php echo $card->BANK_ID->LookupFilterQuery() ?>">
</span>
<?php echo $card->BANK_ID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($card->VALID_THRU_MONTH->Visible) { // VALID_THRU_MONTH ?>
	<div id="r_VALID_THRU_MONTH" class="form-group">
		<label id="elh_card_VALID_THRU_MONTH" for="x_VALID_THRU_MONTH" class="col-sm-2 control-label ewLabel"><?php echo $card->VALID_THRU_MONTH->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $card->VALID_THRU_MONTH->CellAttributes() ?>>
<span id="el_card_VALID_THRU_MONTH">
<input type="text" data-table="card" data-field="x_VALID_THRU_MONTH" name="x_VALID_THRU_MONTH" id="x_VALID_THRU_MONTH" size="30" placeholder="<?php echo ew_HtmlEncode($card->VALID_THRU_MONTH->getPlaceHolder()) ?>" value="<?php echo $card->VALID_THRU_MONTH->EditValue ?>"<?php echo $card->VALID_THRU_MONTH->EditAttributes() ?>>
</span>
<?php echo $card->VALID_THRU_MONTH->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($card->VALID_THRU_YEAR->Visible) { // VALID_THRU_YEAR ?>
	<div id="r_VALID_THRU_YEAR" class="form-group">
		<label id="elh_card_VALID_THRU_YEAR" for="x_VALID_THRU_YEAR" class="col-sm-2 control-label ewLabel"><?php echo $card->VALID_THRU_YEAR->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $card->VALID_THRU_YEAR->CellAttributes() ?>>
<span id="el_card_VALID_THRU_YEAR">
<input type="text" data-table="card" data-field="x_VALID_THRU_YEAR" name="x_VALID_THRU_YEAR" id="x_VALID_THRU_YEAR" size="30" placeholder="<?php echo ew_HtmlEncode($card->VALID_THRU_YEAR->getPlaceHolder()) ?>" value="<?php echo $card->VALID_THRU_YEAR->EditValue ?>"<?php echo $card->VALID_THRU_YEAR->EditAttributes() ?>>
</span>
<?php echo $card->VALID_THRU_YEAR->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $card_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fcardadd.Init();
</script>
<?php
$card_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$card_add->Page_Terminate();
?>
