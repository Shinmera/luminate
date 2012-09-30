<? if($_GET['c']!="true")$_GET['c']="false";
   if($_GET['r']!="true")$_GET['r']="false";
   if($_GET['b']!="true")$_GET['b']="false";
   if(!is_numeric($_GET['bs']))$_GET['bs']=200;
   if(!is_numeric($_GET['ts']))$_GET['ts']=500;
   if($_GET['t']=="")$_GET['t']="argh";
   if(!is_numeric($_GET['a']))  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http:/www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?=$_GET['t']?></title>
    <link rel='stylesheet' href="style.css" type="text/css" />
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="http://bitstorm.org/jquery/color-animation/jquery.animate-colors.js" type="text/javascript"></script>
    <style type="text/css">
        html,body{
            text-align:center;
            font-family: Arial;
            font-size:500%;
            font-weight:bold;
            background-image:url('<?=$_GET['bgp']?>');
            <?=str_replace("\\'","'",str_replace('\\"','"',$_GET['s']))?>;
        }

        #argh{
            text-align:center;
        }
    </style>
    <script type="text/javascript">
        function shake(){
            $("#argh").css({"padding-left":Math.random()*10,"padding-right":Math.random()*10,"padding-top":Math.random()*10,"padding-bottom":Math.random()*10});
            setTimeout('shake()',10);
        }

        function epi(){
            $("#argh").animate({color: '#0000FF'}, <?=$_GET['ts']?>)
                        .animate({color: '#00FF00'}, <?=$_GET['ts']?>)
                        .animate({color: '#FF0000'}, <?=$_GET['ts']?>)
                        .animate({color: '#FFFF00'}, <?=$_GET['ts']?>)
                        .animate({color: '#FF00FF'}, <?=$_GET['ts']?>)
                        .animate({color: '#00FFFF'}, <?=$_GET['ts']?>, function(){epi()});
        }

        function epiBG(){
            $("html").animate({backgroundColor: '#0000FF'}, <?=$_GET['bs']?>)
                        .animate({backgroundColor: '#00FF00'}, <?=$_GET['bs']?>)
                        .animate({backgroundColor: '#FF0000'}, <?=$_GET['bs']?>)
                        .animate({backgroundColor: '#FFFF00'}, <?=$_GET['bs']?>)
                        .animate({backgroundColor: '#FF00FF'}, <?=$_GET['bs']?>)
                        .animate({backgroundColor: '#00FFFF'}, <?=$_GET['bs']?>, function(){epiBG()});
        }

        function center(){
            $("#argh").css({"position":"absolute","top":($(window).height()-$("#argh").height())/2+"px","left":($(window).width()-$("#argh").width())/2+"px"});
        }

        $(function(){
            $(window).resize(function(){center();});
            center();
            if(<?=$_GET['c']?>){shake();}
            if(<?=$_GET['r']?>){epi();}
            if(<?=$_GET['b']?>){epiBG();}
        });
    </script>
</head>
<body>
    <span id="argh"><?=$_GET['t']?></span>
</body>
</html>

