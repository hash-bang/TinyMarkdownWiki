<?
define('CONTENT_DIR', __DIR__ . '/content/'); // Where to find the Markdown files (must end in a slash)
define('CONTENT_EXT', '.md'); // File extension all files must have
define('CONTENT_DEFAULT', 'index'); // If no file is specified use this file


$path = isset($_REQUEST['path']) && trim($_REQUEST['path']) ? $_REQUEST['path'] : CONTENT_DEFAULT;

if (trim(pathinfo($path, PATHINFO_EXTENSION)))
	die('File extensions are not allowed');

if (!file_exists($file = CONTENT_DIR . $path . CONTENT_EXT))
	die('File not found');

if (!$md = file_get_contents($file))
	die('No file content');

require('lib/markdown/markdown.php');
echo Markdown($md);
