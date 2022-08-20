
<?php
chdir (dirname(__FILE__) . '/..');
include_once('config/config.inc.php');
$includeFilename = 'config/config.inc.php';
if ( 0 == filesize( $includeFilename ) )
{
    $url1=$_SERVER['REQUEST_URI'];
        header("Refresh: .2; URL=$url1");
    echo true;

}
else{
        $webRoot = ($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"]:$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'];
        $request_uri = filter_input(INPUT_SERVER, 'REQUEST_URI');
        $slash_pos = strrpos($request_uri, "/");
        $request_uri_full = substr($request_uri, 0, $slash_pos);
        $webRoot = (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? "https://":"http://").$webRoot.$request_uri_full.'/';
        
        $webRoot = ($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"]:$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'];
        $webRoot .= $_SERVER["REQUEST_URI"];
        $webRoot = str_replace( "index.php", "", $webRoot);
        $webRoot = (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? "https://":"http://").$webRoot;
        $_SESSION['config_file_info']['site_URL'] = $webRoot;
        $_SESSION['config_file_info']['root_directory'] = getcwd().'/';
        $site_url=explode("migration/configmigration.php",$webRoot);
        // print_r( $site_url[0]);

		//read the entire string
		$str=file_get_contents($includeFilename);
		// echo $str;
		//replace something in the file string - this is a VERY simple example
		$str=str_replace($site_URL, $site_url[0],$str);
		 $str =str_replace($root_directory,$_SESSION['config_file_info']['root_directory'] = getcwd().'/',$str);
		//write the entire string
		file_put_contents($includeFilename, $str);
        $url1=$_SERVER['REQUEST_URI'];
        header("Refresh: .2; URL=$url1");
        $php_self = $_SERVER['PHP_SELF'];
		$php_self = str_replace('/configmigration.php','',$php_self);
        $php_self = str_replace('/migration','',$php_self);
		$base = str_replace('/index.php','',$php_self);
		if(empty($base))    {
			$base = '/';
		}
		$filename = '.htaccess';
		$content .= "\n<IfModule mod_rewrite.c>\n";
		$content .= "\nRewriteEngine On\n";
		$content .= "\nRewriteBase ".$base."\n";
		$content .= "\nRewriteRule ^index\.php$ - [L]\n";
		$content .= "\nRewriteCond %{REQUEST_FILENAME} !-f\n";
		$content .= "\nRewriteCond %{REQUEST_FILENAME} !-d\n";
		$content .= "\nRewriteRule . ".$php_self."/index.php [L]\n";
		$content .="\n</IfModule>\n";
		$handle = fopen($filename, 'w+');
		fputs($handle, $content);
		fclose($handle);
		chmod($filename, 0777);
		echo false;
    }
?>