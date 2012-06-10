<? $s = array('(Don\'t worry, we still love you)',
              'I bet it\'s top secret stuff!',
              'You\'ll get there eventually.',
              'Maybe you forgot to log in?',
              'Shoosh, you don\'t belong here.',
              'I\'m afraid I can\'t let you do that, Dave.',
              'Unauthorised access. Your IP has been logged and will be reported to the FBI- just kidding. You still can\'t access this page though',
              'Looks like you forgot your multipass.',
              'Welp, looks like you need to hack into the IRC interface and through there get your proverbial super root api key to access the internals of this page.<br />Good luck.',
              'You shall not pass!',
              'I\'d let you see this page, but then I\'d have to kill you.',
              'It\s for your own safety.',
              'DNA test negative.',
              'I bet it was just boring stuff anyway.',
              'The bridge troll demands a red herring.',
              'Ultraviolet security clearance required. Access would be treason.',
              'Shoosh shoosh pap. Pap pap pap shooosh. SHOOSH SHOOSH!'); ?>
<div id="e403">
    <h1>Error 403: Your priveleges are insufficient to access the requested item.</h1>
    <h2><?=$s[mt_rand(0,count($s)-1)]?></h2>
</div>