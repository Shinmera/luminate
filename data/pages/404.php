<? $s = array('Your URL might be incorrect.',
              'Use the power of <a href="http://www.google.com/search?as_q=http://'.HOST.$_SERVER['REQUEST_URI'].'">google</a>.',
              'Maybe the content exists <a href="http://wayback.archive.org/web/*/http://'.HOST.$_SERVER['REQUEST_URI'].'">way back</a>?',
              'Ah well. Some other time maybe?',
              'Sorry about that.',
              'Are you sure that this is the right place?',
              'Statistically, you will always be infinitely more likely to see this page than an actual result.',
              'Oh nooo!',
              'Sad smiley face.',
              '<a href="http://www.youtube.com/watch?v=w4CQin03MDQ">This</a> will make you know.',
              '(Don\'t worry, we still love you)',
              'Try hugging someone. It won\'t help you get the content back, but it sure feels good!',
              'Quick, quick! The magpie took it! Someone catch that magpie!',
              'Curses, foiled again!',
              'If you wait long enough, this page might actually turn into what you want to have.<br />It\'s possible at least.',
              'Make sure there are no quantum fluctuations involved.',
              'It is currently on holidays and might not return for a while.',
              'Don\'t ask me, I have no idea where it is either!',
              'It might have fallen down the well. You should better go and check.',
              'Not again!',
              'What if this actually isn\'t an error at all and you are looking at the right page?',
              'How about you just go play some games instead?');?>
<div id="e404">
    <h1>Error 404: The item you were looking for does not exist.</h1>
    <h2><?=$s[mt_rand(0,count($s))]?></h2>
</div>