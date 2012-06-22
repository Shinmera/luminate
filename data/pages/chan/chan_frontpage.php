<style type="text/css">
    .frontContainer{
        position:relative;
        margin-right:310px;
    }
    
    .frontbox{
        border-radius: 5px;
        border: 1px solid #333;
        box-shadow: 0px 0px 5px #000;
        background-color: #111;
        color: #EEE;
        padding: 5px;
        margin: 5px;
        display:inline-block;
        min-height:50px;
        min-width:50px;
        vertical-align: text-top;
        text-align: justify;
    }
    
    .boxtitle{
        font-size: 18pt;
        font-weight: bold;
    }
    
    .fullwidth{
        margin-left:10px;
        margin-right:10px;
        display:block;
    }
    
    .breakingnewsbox{text-align:center;}
    .welcomebox{max-width:30%;}
    .boardsbox a{width: 50px;display:inline-block;color: #FFF;}
    .boardsbox a:hover{text-shadow: 0px 0px 5px #FFF;}
    .ircbox input{
        border: 1px solid #AAA;
        box-shadow: 0px 0px 2px #000;
        border-radius: 5px;
        max-width: 150px;
    }
</style>
<div class="frontContainer">
<div class="frontbox breakingnewsbox fullwidth">
    <div class="boxtitle" style="font-size:28pt">Breaking News</div>
    <img src="http://img.tymoon.eu/suiseiseki/Army/a6bb75b676c83612f0af1f27317fe83c.jpg" alt="breaking news" />
</div>
<div class="frontbox welcomebox">
    <div class="boxtitle">Welcome!</div>
    Welcome to the Faggotry Central.<br />
    Or as what this is usually called: Stevenchan.<br />
    <br />
    Have a look around and make yourself comfortable. We're chill.
</div>
<div class="frontbox boardsbox">
    <div class="boxtitle">Boards</div>
    <a href="/stc/">/stc/</a> - Stevenchan Intern<br />
    <a href="/test/">/test/</a> - Testing Board<br />
    <a href="/arch/">/arch/</a> - Archived Threads<br />
    <br />
    <a href="/anon/">/anon/</a> - Anonymous Posting<br />
    <a href="/mag/">/mag/</a> - Serious Discussions<br />
    <br />
    <a href="/rand/">/rand/</a> - Wildcard Board<br />
    <a href="/fab/">/fab/</a> - Random, General Board<br />
    <a href="/syr/">/syr/</a> - Creepy Stuff<br />
    <br />
    <a href="/ga/">/ga/</a> - Gallery<br />
    <a href="/mt/">/mt/</a> - Media & Technology<br />
</div> 
<div class="frontbox ircbox">
    <div class="boxtitle">IRChat</div>
    <form action="http://<?=HOST.PROOT?>pages/chat.php" method="get" id="chatform">
        <div><label style="width:100px;float:left;">Nick:</label><input type="text" name="nick" value="<?=$_COOKIE['nick']?>" /></div>
        <div><label style="width:100px;float:left;">Password:</label><input type="password" name="pw" value="<?=$_COOKIE['pw']?>" /></div>
        <div><label style="width:100px;float:left;">Channel:</label><input type="text" name="channel" value="#Stevenchan" /></div>
        <input type="submit" value="Go" />
    </form>
    <script type="text/javascript">
    $(document).ready(function() {
        $('#chatform').submit(function() {
            window.open('', 'formpopup', 'width=500,height=500,resizeable,scrollbars');
            this.target = 'formpopup';
        });
    });
    </script>
</div>
<div class="frontbox twitterbox">
    <div class="boxtitle">Twitter News</div>
    <script type="text/javascript" src="http://widgets.twimg.com/j/2/widget.js"></script>
    <script type="text/javascript">
        new TWTR.Widget({
            version: 2,
            type: 'profile',
            rpp: 5,
            interval: 30000,
            width: 250,
            height: 100,
            theme: {
                shell: {
                background: '#111',
                color: '#ffffff'
                },
                tweets: {
                background: '#000000',
                color: '#ffffff',
                links: '#1B87E0'
                }
            },
            features: {
                scrollbar: true,
                loop: false,
                live: true,
                behavior: 'all'
            }
            }).render().setUser("Ty_Stevenchan").start();
    </script>
</div>
<br class="clear" />
</div>