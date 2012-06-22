<? $folder = opendir(ROOT.IMAGEPATH.'banners/');
while(($file=readdir($folder))!==FALSE){
        if($file!="."&&$file!="..")$banners[]=$file;
}
$banner=$banners[mt_rand(0,count($banners)-1)];
echo("<center><img src='".IMAGEPATH.'banners/'.$banner."' alt='banner' id='pageBanner' /></center>");
?>
