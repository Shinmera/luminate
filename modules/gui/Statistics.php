<? 
class Chart{
    var $id;
    var $cats;
    var $data;
    var $type;
    var $caption;
    
    function __construct($id,$cats,$data=array(),$type='line'){
        $this->id=$id;
        $this->cats=$cats;
        $this->data=$data;
        $this->type=$type;
    }
    
    function setData($data){$this->data=$data;}
    function setCaption($caption){$this->caption=$caption;}
    
    function display($dir='x'){
        ?><table id=<?=$this->id?>>
            <thead>
                <tr>
                    <td></td>
                    <? foreach($this->cats as $cat){ ?>
                        <th scope="col"><?=$cat?></th>
                    <? } ?>
                </tr>
            </thead>
            <tbody>
                <? foreach($this->data as $name=>$dat){ 
                    echo('<tr><th scope="row">'.$name.'</th>');
                    foreach($this->cats as $cat){
                        if(is_object($dat))echo('<td>'.$dat->$cat.'</td>');
                        else               echo('<td>'.$dat[$cat].'</td>');
                    }
                    echo('</tr>');
                } ?>
            </tbody>
        </table>
        
        <link rel="stylesheet" type="text/css" href="<?=PROOT?>css/visualize/basic.css"/>
        <link rel="stylesheet" type="text/css" href="<?=PROOT?>css/visualize/visualize.css"/>
        <script type="text/javascript" src="<?=PROOT?>js/visualize/excanvas.js"></script>
        <script type="text/javascript" src="<?=PROOT?>js/visualize/visualize.js"></script>
        <script type="text/javascript">
            $(function(){
                $('#<?=$this->id?>').visualize({'type':'<?=$this->type?>',
                                                'title':'<?=$this->caption?>',
                                                'parseDirection':'<?=$dir?>'});
            });
        </script><?
    }
}
?>