<? class Test extends Module{
public static $name="Test";
public static $author="NexT";
public static $version=0.01;
public static $short='test';
public static $required=array("Themes");
public static $hooks=array("foo");

function runTests(){
    global $c,$l,$k,$t;
    
    echo('<div style="font-family: Inconsolata, Consolas, Monospace">');
    
    echo(nl2br(Toolkit::autoBreakLines('Lorem ipsum dolor sit amet, consectetur adipiscing elit. In consequat molestie orci eu hendrerit. In rhoncus ornare urna a consectetur. Fusce mi est, vulputate et adipiscing condimentum, pretium eu ligula. Sed a arcu sed orci luctus mollis. Pellentesque tellus ligula, viverra id mattis a, rhoncus in tortor. Quisque egestas aliquet bibendum. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Aenean aliquam, felis eget dignissim vestibulum, quam enim hendrerit mi, ac suscipit quam nisl in mauris. Donec erat augue, pellentesque sed blandit convallis, semper vel augue. Nam augue purus, consequat at eleifend quis, ultrices quis odio.

Sed vulputate, elit a tincidunt rutrum, quam tortor tristique est, at tincidunt diam orci at metus. Morbi non turpis mi, sed congue sem. Aenean pretium laoreet orci, sit amet dictum nisl vestibulum a. Aenean non nisl nibh, nec placerat orci. Aenean justo justo, aliquet at venenatis id, mattis hendrerit mauris. Nullam non dolor tellus, ut sagittis metus. Donec vel urna non odio condimentum pulvinar. Suspendisse potenti. Donec in lorem eu enim faucibus egestas sed sed mauris. Nullam adipiscing turpis et nisi elementum tempor.',120)));

    echo('<hr />');
    echo(nl2br(Toolkit::autoBreakLines('And another update.
Fixed Javascript compatibility with Firefox and Opera.
Partially fixed it on IE9.
Not fully, because IE9 does some really weird shit that I\'ll have to take a closer look at some other day.
When it isn\'t freaking 1AM and when I\'m not tired from travelling. ',120)));
    
    echo('<hr />');
    echo(nl2br(Toolkit::autoBreakLines('01234567890123456789012345678901234567890123456789012345678901234567890012345678901234567890123456789012345678901234567890123456789012345678900123456789012345678901234567890123456789012345678901234567890123456789001234567890123456789012345678901234567890123456789012345678901234567890012345678901234567890123456789012345678901234567890123456789012345678900123456789012345678901234567890123456789012345678901234567890123456789001234567890123456789012345678901234567890123456789012345678901234567890',120)));
    echo('<hr />');
    echo(nl2br(Toolkit::autoBreakLines('0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789 0123456789',120)));
    echo('<hr />');
    echo(nl2br(Toolkit::autoBreakLines('01234 56789 0123 56789 01234 56789 0123 56789 01234 56789 0123 56789 01234 56789 0123 56789 01234 56789 0123 56789 01234 56789 0123 56789 01234 56789 0123 56789 01234 56789 0123 56789 01234 56789 0123 56789 01234 56789 0123 56789 01234 56789 0123 56789 01234 56789 0123 56789 01234 56789 0123 56789 01234 56789 0123 56789 01234 56789 0123 56789 01234 56789 0123 56789 01234 56789 0123 56789 01234 56789 0123 56789 01234 56789 0123 56789 01234 56789 0123 56789 01234 56789 0123 56789 01234 56789 0123 56789',120)));
    echo('<hr />');
    echo(nl2br(Toolkit::autoBreakLines('0 23 456 789 0 123 456 789 023 456 789 0 123 456 789 0123 456 987 1 01 23 456 789 0 23 456 789 0 123 456 789 023 456 789 0 123 456 789 0123 456 987 1 01 23 456 789 0 23 456 789 0 123 456 789 023 456 789 0 123 456 789 0123 456 987 1 01 23 456 789 0 23 456 789 0 123 456 789 023 456 789 0 123 456 789 0123 456 987 1 01 23 456 789 0 23 456 789 0 123 456 789 023 456 789 0 123 456 789 0123 456 987 1 01 23 456 789 0 23 456 789 0 123 456 789 023 456 789 0 123 456 789 0123 456 987 1 01 23 456 789',120)));
    echo('</div>');
    
}
}
?>