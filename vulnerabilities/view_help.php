<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ] = 'Help' . $page[ 'title_separator' ].$page[ 'title' ];

if (array_key_exists ("id", $_GET) &&
	array_key_exists ("security", $_GET) &&
	array_key_exists ("locale", $_GET)) {
	$id       = $_GET[ 'id' ];
	$security = $_GET[ 'security' ];
	$locale = $_GET[ 'locale' ];

	$allowed_locales = ['en'];

    if (!in_array($locale, $allowed_locales, true)) {
        $locale = 'en';
    }

    $allowed_ids = [
        'brute',
        'command',
        'csrf',
        'exec',
        'fi',
        'file_upload',
        'sqli',
        'sqli_blind',
        'xss_d',
        'xss_r'
    ];

    if (!in_array($id, $allowed_ids, true)) {
        $help = "<p>Not Found</p>";
    } else {
	ob_start();
	if ($locale == 'en') {
		include DVWA_WEB_PAGE_TO_ROOT .
                "vulnerabilities/{$id}/help/help.php";
	} else {
		include DVWA_WEB_PAGE_TO_ROOT .
                "vulnerabilities/{$id}/help/help.{$locale}.php";
	}
	$help = ob_get_contents();
	ob_end_clean();
	}
} else {
	$help = "<p>Not Found</p>";
}

$page[ 'body' ] .= "
<script src='/vulnerabilities/help.js'></script>
<link rel='stylesheet' type='text/css' href='/vulnerabilities/help.css' />

<div class=\"body_padded\">
	{$help}
</div>\n";

dvwaHelpHtmlEcho( $page );

?>
