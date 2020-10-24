<?PHP
//declare(strict_types = 1);
header("Content-Type:text/html;charset=utf-8");
//定义常量，授权includes的包含文件
//define('Inc_Tag', TRUE);
require __DIR__.'/../check.php';

//时区的设置
ini_set('date.timezone','Asia/Shanghai');
if(function_exists('date_default_timezone_set')){
  date_default_timezone_set('Asia/Shanghai');
}

$promoter = isset($_GET['prom'])?$_GET['prom']:1;
$cpu='all';
switch ($_GET['cpu']) {
    case 'armx64':
        $cpu='ar64';
        break;
    case 'armx32':
        $cpu='ar32';
        break;
    case 'pcx32':
        $cpu='pc32';
        break;
    case 'pcx64':
        $cpu='pc64';
        break;
    default:
        $cpu='all';
        break;
}

if(!empty($promoter)){
	$apkFile="apk/fisher-{$cpu}.apk";
	$output="temp/fisher-{$cpu}-{$promoter}.apk";
	if(!file_exists($output)){
		//$apkPacker = new \ApkPacker\ApkPacker();
		//$apkPacker->packerSingleApk('fisher.apk',$promoter,$output);
		//create new apk file
		$newApkFile = fopen( $output, 'wb' );
		if ( $newApkFile == false ) {
			//throw new ApkPackerException( 'failed to create new apk file' );
		}

		if ( copy( $apkFile, $output ) ) {
		    //https://www.jb51.net/article/188913.htm
		    $zip = new ZipArchive;
            $res = $zip->open($output, ZipArchive::CREATE);
            if ($res === TRUE) {
                //$zip->addFromString('test.txt', 'file content goes here');
                $zip->setArchiveComment($promoter);
                $zip->close();
                //echo 'ok';
            } else {
                //echo 'failed';
            }
		}
	}
	
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: $output");
}

?>