<? $folder = opendir(ROOT.IMAGEPATH.'chan/headers/');
$banners = array();
while(($file=readdir($folder))!==FALSE){
        if($file!="."&&$file!="..")$banners[]=$file;
}
if(count($banners)>0){
    $banner=$banners[mt_rand(0,count($banners)-1)];
    echo("<center><img src='".IMAGEPATH.'banners/'.$banner."' alt='banner' id='pageBanner' /></center>");
}
?>
