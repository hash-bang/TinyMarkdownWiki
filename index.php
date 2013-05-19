<?
define('CONTENT_DIR_WWW', rtrim(dirname($_SERVER['PHP_SELF']), '/') . '/');
define('CONTENT_DIR', dirname(__FILE__) . '/content/'); // Where to find the Markdown files (must end in a slash)
define('CONTENT_EXT', '.md'); // File extension all files must have
define('CONTENT_PASSTHRU_EXT', 'pdf,odp'); // CSV of allowed file types to pass through to the browser (if the file exists in CONTENT_DIR)
define('CONTENT_DEFAULT', 'home,index'); // CSV of files to use as the default. The list is traversed until one is found
define('CONTENT_TEMPLATE', 'templates/bootstrap-navbar.php'); // Use this file to render the Markdown in a template
define('CONTENT_FORMAT_DEFAULT', 'markdown'); // Default renderer to use when ?f= is not specified
define('CONTENT_FORMAT_ALLOWED', 'markdown,plain'); // CSV of allowable formats for use with ?f=

/**
* Optionally load this file in as the hierachical menu of the project (it will set the content of the file as $menu instead of $markdown)
* @var null|string
*/
define('CONTENT_MENU', 'menu'); 


$path = isset($_REQUEST['path']) && trim($_REQUEST['path']) ? $_REQUEST['path'] : FALSE;

if (!$path) { // Determine default file from CONTENT_DEFAULT CSV
	foreach (preg_split('/\s*,\s*/', CONTENT_DEFAULT) as $file)
		if (file_exists($f = CONTENT_DIR . $file . CONTENT_EXT)) {
			$path = $file;
			break;
		}
	if (!$path)
		die('No default file specified as CONTENT_DEFAULT');
}

$ext = trim(pathinfo($path, PATHINFO_EXTENSION));
if ($ext) {
	if (!in_array($ext, explode(',', CONTENT_PASSTHRU_EXT)))
		die('That file extension is not allowed');
	// Stream file to browser
	$path = CONTENT_DIR . $path;
	header('Content-Length: ' . filesize($path));
	header('Content-Type: ' . mime_content_type($path));
	readfile($path);
	die();
} else
	$path .= CONTENT_EXT;

if (!file_exists($file = CONTENT_DIR . $path))
	die('File not found: ' . $path);


if (!$md = file_get_contents($file))
	die('No file content');

parse_str(substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], '?') + 1), $_REQUEST);
if (isset($_REQUEST['f'])) {
	$format = $_REQUEST['f'];
	if (!in_array($format, preg_split('/\s*,\s*/', CONTENT_FORMAT_ALLOWED)))
		die("Not an allowed output format: $format");
} else
	$format = CONTENT_FORMAT_DEFAULT;

// Functions to clean up Markdown {{{
function clean($md) {
	// Repoint all links to the correct directory
	$md = preg_replace_callback('!(href|src)=(["\'])(.*?)\2!i', 'clean_links_callback', $md);
	return $md;
}

function clean_links_callback($matches) {
	if (preg_match('!^(mailto|http|https):!', $matches[3])) { // Absolute URL - use it by itself
		$link = $matches[3];
	} else // Relative - in system - URL, correct path
		$link = CONTENT_DIR_WWW . ltrim($matches[3], '/');
	return $matches[1] . '=' . $matches[2] . $link . $matches[2];
}
// }}}

switch ($format) {
	case 'plain':
		header('Content-type: text/plain');
		echo $md;
		break;
	case 'markdown':
	default:
		require('lib/php-markdown/Michelf/Markdown.php');
		require('lib/php-markdown/Michelf/MarkdownExtra.php');

		$title = ucfirst(basename($path, CONTENT_EXT));

		if (CONTENT_MENU)
			$menu = (file_exists($f = CONTENT_DIR . CONTENT_MENU . CONTENT_EXT)) ? clean(MarkdownExtra::defaultTransform(file_get_contents($f))) : 'Menu file not found';
		$markdown = clean(MarkdownExtra::defaultTransform($md));

		require(CONTENT_TEMPLATE);
		break;
}
