<? class Test extends Module{
public static $name="Test";
public static $author="NexT";
public static $version=0.01;
public static $short='test';
public static $required=array("Themes");
public static $hooks=array("foo");

function runTests(){
    global $c,$l,$k,$t;
    echo(nl2br(Toolkit::autoBreakLines('Lorem ipsum dolor sit amet, consectetur adipiscing elit. In consequat molestie orci eu hendrerit. In rhoncus ornare urna a consectetur. Fusce mi est, vulputate et adipiscing condimentum, pretium eu ligula. Sed a arcu sed orci luctus mollis. Pellentesque tellus ligula, viverra id mattis a, rhoncus in tortor. Quisque egestas aliquet bibendum. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Aenean aliquam, felis eget dignissim vestibulum, quam enim hendrerit mi, ac suscipit quam nisl in mauris. Donec erat augue, pellentesque sed blandit convallis, semper vel augue. Nam augue purus, consequat at eleifend quis, ultrices quis odio.

Sed vulputate, elit a tincidunt rutrum, quam tortor tristique est, at tincidunt diam orci at metus. Morbi non turpis mi, sed congue sem. Aenean pretium laoreet orci, sit amet dictum nisl vestibulum a. Aenean non nisl nibh, nec placerat orci. Aenean justo justo, aliquet at venenatis id, mattis hendrerit mauris. Nullam non dolor tellus, ut sagittis metus. Donec vel urna non odio condimentum pulvinar. Suspendisse potenti. Donec in lorem eu enim faucibus egestas sed sed mauris. Nullam adipiscing turpis et nisi elementum tempor.',120)));
}



}
?>