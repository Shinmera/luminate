<? class Test extends Module{
public static $name="Test";
public static $author="NexT";
public static $version=0.01;
public static $short='test';
public static $required=array("Themes");
public static $hooks=array("foo");

function runTests(){
    global $c,$l,$k,$t;
    $constants = get_defined_constants(true);
    $message = '
        <html>
            <head>
                <title>TyNET: Uncaught Exception</title>
            </head>
            <body>
                <b>IP:</b> '.$_SERVER['REMOTE_ADDR'].'<br />
                <b>Date:</b> '.date('Y-m-d H:i:s').'<br />
                <b>File:</b> '.$e->getFile().':'.$e->getLine().'<br />
                <b>Message:</b> '.$e->getMessage().'<br />
                <b>Stack Trace:</b><br />
                '.$e->getTraceAsString().'<br />
                <b>Constants:</b><br />
                '.str_replace("\n",'<br />',print_r($constants['user'],true)).'
            </body>
        </html>';
    $headers  = 'MIME-Version: 1.0'."\r\n".
                'Content-type: text/html; charset=UTF-8'."\r\n".
                'To: Sysop <'.SYSOPMAIL.'>'."\r\n".
                'From: TyNET system <sys@'.HOST.'>'."\r\n";
    if(mail('shinmera@tymoon.eu', 'TyNET: Uncaught Exception', $message, $headers)){
        echo("MAIL SENT!");
    }else{
        echo("MAIL FAILED!");
    }
}
}?>