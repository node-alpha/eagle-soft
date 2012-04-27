<?php 
function __autoload($class_name) {
	$a_ModuleFolderPath = scandir(MODULE_FOLDER);
	foreach ( $a_ModuleFolderPath as $directory ) {
		$folder = MODULE_FOLDER . DIRECTORY_SEPARATOR . $directory;
		if (is_dir($folder) && file_exists ( $folder . DIRECTORY_SEPARATOR . $class_name . '.php' )) {
			require_once ($folder . DIRECTORY_SEPARATOR .$class_name . '.php');
			return;
		}
	}
	if (file_exists ( MODULE_FOLDER . DIRECTORY_SEPARATOR . $class_name . '.php' )) {
		require_once (MODULE_FOLDER . DIRECTORY_SEPARATOR . $class_name . '.php');
		return;
	}
}

function isAjaxRequest() {
	return (isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) && ($_SERVER ['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
}

//Our basic captcha - does the job well

function captcha ()
{
    $im = imagecreate(100, 20);

    $string = substr ( md5 ( time () ), 0 , 8 );

    $bg = imagecolorallocate($im, 143, 216, 216);
    $black = imagecolorallocate($im, 0, 0, 0);
    $len=strlen($string)-1;
    $rand = null;

    $line = imagecolorallocate($im,233,239,239);
    imageline($im,0,0,39,29,$line);
    imageline($im,40,0,42,12,$line);
    imageline($im,90,0,112,42,$line);
    imageline($im,15,0,22,79,$line);
    imageline($im,64,0,89,98,$line);

    $xpos=rand(0,20);
    $ypos=rand(0,3);
    imagestring($im, 5,$xpos,$ypos,$string,$black);

    $_SESSION['captcha'] = md5 ( $string );

    header('Content-type: image/png');
    imagepng($im);
    imagedestroy ($im);
}

?>