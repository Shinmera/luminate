<? if(!defined("INIT"))include("/var/www/TyNET/config.php"); ?>
<? $folder = opendir(ROOT.IMAGEPATH.'chan/headers/');
$banners = array();
while(($file=readdir($folder))!==FALSE){
        if($file!="."&&$file!="..")$banners[]=$file;
}
if(count($banners)>0){ ?>
    <?='<? $banners="'.implode(',',$banners).'";$banners=explode(",",$banners);?>'?>
    <?='<? $banner=$banners[mt_rand(0,count($banners)-1)]; ?>'?>
    <div id='pageBanner'><img src='<?=IMAGEPATH?>chan/headers/<?='<?=$banner?>'?>' alt='banner' /></div>
<? } ?>
