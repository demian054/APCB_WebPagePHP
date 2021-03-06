<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(47, "mi_PrincipalHome_php", $Language->MenuPhrase("47", "MenuText"), "PrincipalHome.php", -1, "", AllowListMenu('{B2F6402D-7C0A-4760-82AD-045088CEBA18}PrincipalHome.php'), FALSE);
$RootMenu->AddMenuItem(91, "mci_Menu", $Language->MenuPhrase("91", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(46, "mi_audittrail", $Language->MenuPhrase("46", "MenuText"), "audittraillist.php", 91, "", AllowListMenu('{B2F6402D-7C0A-4760-82AD-045088CEBA18}audittrail'), FALSE);
$RootMenu->AddMenuItem(7, "mi_boarding", $Language->MenuPhrase("7", "MenuText"), "boardinglist.php", 91, "", AllowListMenu('{B2F6402D-7C0A-4760-82AD-045088CEBA18}boarding'), FALSE);
$RootMenu->AddMenuItem(4, "mi_baggage", $Language->MenuPhrase("4", "MenuText"), "baggagelist.php", 7, "", AllowListMenu('{B2F6402D-7C0A-4760-82AD-045088CEBA18}baggage'), FALSE);
$RootMenu->AddMenuItem(11, "mi_flight", $Language->MenuPhrase("11", "MenuText"), "flightlist.php", 7, "", AllowListMenu('{B2F6402D-7C0A-4760-82AD-045088CEBA18}flight'), FALSE);
$RootMenu->AddMenuItem(12, "mi_passanger", $Language->MenuPhrase("12", "MenuText"), "passangerlist.php", 7, "", AllowListMenu('{B2F6402D-7C0A-4760-82AD-045088CEBA18}passanger'), FALSE);
$RootMenu->AddMenuItem(28, "mci_Configuration", $Language->MenuPhrase("28", "MenuText"), "", 91, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(1, "mi_air_port", $Language->MenuPhrase("1", "MenuText"), "air_portlist.php", 28, "", AllowListMenu('{B2F6402D-7C0A-4760-82AD-045088CEBA18}air_port'), FALSE);
$RootMenu->AddMenuItem(5, "mi_bank", $Language->MenuPhrase("5", "MenuText"), "banklist.php", 28, "", AllowListMenu('{B2F6402D-7C0A-4760-82AD-045088CEBA18}bank'), FALSE);
$RootMenu->AddMenuItem(6, "mi_bank_account", $Language->MenuPhrase("6", "MenuText"), "bank_accountlist.php", 28, "", AllowListMenu('{B2F6402D-7C0A-4760-82AD-045088CEBA18}bank_account'), FALSE);
$RootMenu->AddMenuItem(16, "mi_reservation_status", $Language->MenuPhrase("16", "MenuText"), "reservation_statuslist.php", 28, "", AllowListMenu('{B2F6402D-7C0A-4760-82AD-045088CEBA18}reservation_status'), FALSE);
$RootMenu->AddMenuItem(20, "mi_upload_file_detail_status", $Language->MenuPhrase("20", "MenuText"), "upload_file_detail_statuslist.php", 28, "", AllowListMenu('{B2F6402D-7C0A-4760-82AD-045088CEBA18}upload_file_detail_status'), FALSE);
$RootMenu->AddMenuItem(2, "mi_airplane", $Language->MenuPhrase("2", "MenuText"), "airplanelist.php", 28, "", AllowListMenu('{B2F6402D-7C0A-4760-82AD-045088CEBA18}airplane'), FALSE);
$RootMenu->AddMenuItem(21, "mi_upload_file_status", $Language->MenuPhrase("21", "MenuText"), "upload_file_statuslist.php", 28, "", AllowListMenu('{B2F6402D-7C0A-4760-82AD-045088CEBA18}upload_file_status'), FALSE);
$RootMenu->AddMenuItem(13, "mi_passanger_type", $Language->MenuPhrase("13", "MenuText"), "passanger_typelist.php", 28, "", AllowListMenu('{B2F6402D-7C0A-4760-82AD-045088CEBA18}passanger_type'), FALSE);
$RootMenu->AddMenuItem(9, "mi_card_type", $Language->MenuPhrase("9", "MenuText"), "card_typelist.php", 28, "", AllowListMenu('{B2F6402D-7C0A-4760-82AD-045088CEBA18}card_type'), FALSE);
$RootMenu->AddMenuItem(14, "mi_pay_type", $Language->MenuPhrase("14", "MenuText"), "pay_typelist.php", 28, "", AllowListMenu('{B2F6402D-7C0A-4760-82AD-045088CEBA18}pay_type'), FALSE);
$RootMenu->AddMenuItem(29, "mci_Conciliation", $Language->MenuPhrase("29", "MenuText"), "", 91, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(10, "mi_conciliation", $Language->MenuPhrase("10", "MenuText"), "conciliationlist.php", 29, "", AllowListMenu('{B2F6402D-7C0A-4760-82AD-045088CEBA18}conciliation'), FALSE);
$RootMenu->AddMenuItem(17, "mi_transference", $Language->MenuPhrase("17", "MenuText"), "transferencelist.php", 29, "", AllowListMenu('{B2F6402D-7C0A-4760-82AD-045088CEBA18}transference'), FALSE);
$RootMenu->AddMenuItem(18, "mi_upload_file", $Language->MenuPhrase("18", "MenuText"), "upload_filelist.php", 29, "", AllowListMenu('{B2F6402D-7C0A-4760-82AD-045088CEBA18}upload_file'), FALSE);
$RootMenu->AddMenuItem(19, "mi_upload_file_detail", $Language->MenuPhrase("19", "MenuText"), "upload_file_detaillist.php", 18, "", AllowListMenu('{B2F6402D-7C0A-4760-82AD-045088CEBA18}upload_file_detail'), FALSE);
$RootMenu->AddMenuItem(44, "mci_Users_Profiles", $Language->MenuPhrase("44", "MenuText"), "", 91, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(24, "mi_user_levels", $Language->MenuPhrase("24", "MenuText"), "user_levelslist.php", 44, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE);
$RootMenu->AddMenuItem(23, "mi_user_level_permissions", $Language->MenuPhrase("23", "MenuText"), "user_level_permissionslist.php", 24, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE);
$RootMenu->AddMenuItem(45, "mci_My_User", $Language->MenuPhrase("45", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(8, "mi_card", $Language->MenuPhrase("8", "MenuText"), "cardlist.php", 45, "", AllowListMenu('{B2F6402D-7C0A-4760-82AD-045088CEBA18}card'), FALSE);
$RootMenu->AddMenuItem(22, "mi_user", $Language->MenuPhrase("22", "MenuText"), "userlist.php", 45, "", AllowListMenu('{B2F6402D-7C0A-4760-82AD-045088CEBA18}user'), FALSE);
$RootMenu->AddMenuItem(15, "mi_reservation", $Language->MenuPhrase("15", "MenuText"), "reservationlist.php", 45, "", AllowListMenu('{B2F6402D-7C0A-4760-82AD-045088CEBA18}reservation'), FALSE);
$RootMenu->AddMenuItem(-2, "mi_changepwd", $Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, "mi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
