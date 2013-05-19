<?
define('CONTENT_DIR', dirname(__FILE__) . '/content/'); // Where to find the Markdown files (must end in a slash)
define('CONTENT_EXT', '.md'); // File extension all files must have
define('CONTENT_PASSTHRU_EXT', 'pdf,odp'); // CSV of allowed file types to pass through to the browser (if the file exists in CONTENT_DIR)
define('CONTENT_DEFAULT', 'home,index'); // CSV of files to use as the default. The list is traversed until one is found
define('CONTENT_TEMPLATE', 'templates/bootstrap-navbar.php'); // Use this file to render the Markdown in a template

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

require('lib/php-markdown/Michelf/Markdown.php');
require('lib/php-markdown/Michelf/MarkdownExtra.php');

$title = ucfirst(basename($path, CONTENT_EXT));

if (CONTENT_MENU)
	$menu = (file_exists($f = CONTENT_DIR . CONTENT_MENU . CONTENT_EXT)) ? MarkdownExtra::defaultTransform(file_get_contents($f)) : 'Menu file not found';

$markdown = MarkdownExtra::defaultTransform($md);
require(CONTENT_TEMPLATE);
