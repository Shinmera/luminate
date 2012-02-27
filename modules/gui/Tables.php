<?
class TableData {
    var $columns;
    var $data;

    function __construct(){
        $colnum = func_num_args();
        if($colnum>2){
            $this->columns = func_get_arg(0);
            $this->data = array();
            $args = array_slice(func_get_args(),1);
            for($i=0;$i<count(func_get_arg(1));$i++){
                for($j=1;$j<$colnum;$j++){
                    $temp = func_get_arg($j);
                    $this->data[$i][$j]=$temp[$i];
                }
            }
        }else if($colnum==2){
            $this->columns = func_get_arg(0);
            $this->data = func_get_arg(1);
        }else{
            $this->columns = array();
            $this->data = array();
        }
        if(!is_array($this->data))$this->data=array();
        $data=array();$i=0;
        foreach($this->data as $row){
            $j=0;
            foreach($row as $cell){
                $data[$i][$j]=$cell;
                $j++;
            }
            $i++;$j=0;
        }
        $this->data=$data;
    }
}

class TableIterator {
    var $functions = array();
    var $begin=0;
    var $end=-1;
    var $column=-1;
    
    function __construct($functions,$begin=0,$end=-1,$column=-1){
        $this->functions=$functions;
        $this->begin=$begin;
        $this->end=$end;
        $this->column=$column;
    }

    function iterate($data,$begin=0,$end=-1,$column=-1){
        if($begin==0)$begin=$this->begin;
        if($end==-1)$end=$this->end;
        if($column==-1)$column=$this->column;
        
        if($end==-1)$end=count($data->data);
        for($i=$begin;$i<$end;$i++){
            for($j=0;$j<count($data->data[$i]);$j++){
                if($column==-1||$column==$j){
                    if(!is_array($this->functions))  $data->data[$i][$j]=call_user_func_array($this->functions,    array($data->data[$i][$j],$i,$j,$data->data));
                    else if($this->functions[$j]!="")$data->data[$i][$j]=call_user_func_array($this->functions[$j],array($data->data[$i][$j],$i,$j,$data->data));
                }
            }
        }
        return $data;
    }
}

class QueryTable {
    var $name = "";
    var $data = null;
    var $query = "";
    var $upperLimit=-1;
    var $lowerLimit=0;
    var $maxRange=50;
    var $max=0;

    function __construct($name,$rows,$query,$upperLimit=-1,$lowerLimit=0,$maxRange=50,$max=50,$iterator=null){
        global $c;
        $this->name=$name;
        $this->maxRange=$maxRange;
        $this->query=$query;
        if(is_numeric($_POST['varlower']))$lowerLimit=$_POST['varlower'];
        if(is_numeric($_POST['varupper']))$upperLimit=$_POST['varupper'];
        if(($upperLimit-$lowerLimit)>$maxRange)$upperLimit=$lowerLimit+$maxRange;
        if($lowerLimit<0)$lowerLimit=0;
        if($upperLimit>$max)$upperLimit=$max;
        $this->upperLimit=$upperLimit;
        $this->lowerLimit=$lowerLimit;
        $limit=$lowerLimit;
        if($upperLimit>0)$limit.=",".$upperLimit;
        $this->data=new TableData($rows,$c->getData($query." LIMIT ".$limit));
        if($iterator!=null)$this->data=$iterator->iterate($this->data);
    }

    function printTable(){
        global $k;
        ?><form action="#" methd="post" >
            Show entries from
            <input type="text" name="varlower" value="<?=$this->lowerLimit?>" style="width:50px"/>
            to
            <input type="text" name="varupper" value="<?=$this->upperLimit?>" style="width:50px"/>
            <input type="submit" value="Go" />
        </form>
        <form action="#" method="post" style="float:left">
            <input type="hidden" name="varlower" value="0" />
            <input type="hidden" name="varupper" value="<?=$this->maxRange?>" />
            <input type="submit" value="<<" />
        </form>
        <form action="#" method="post" style="float:left">
            <input type="hidden" name="varlower" value="<?=($this->lowerLimit-$this->maxRange)?>" />
            <input type="hidden" name="varupper" value="<?=($this->lowerLimit)?>" />
            <input type="submit" value="<" />
        </form>
        <form action="#" method="post" style="float:left">
            <input type="hidden" name="varlower" value="<?=($this->upperLimit)?>" />
            <input type="hidden" name="varupper" value="<?=($this->upperLimit+$this->maxRange)?>" />
            <input type="submit" value=">" />
        </form>
        <form action="#" method="post" style="float:left">
            <input type="hidden" name="varlower" value="<?=($this->max-$this->maxRange)?>" />
            <input type="hidden" name="varupper" value="<?=($this->max)?>" />
            <input type="submit" value=">>" />
        </form>
        <br class="clear" />
        <table id="<?=$this->name?>" class="settingsTable">
            <thead>
                <tr><? $k->wrapAndPrint($this->data->columns,"<th>","</th>"); ?></tr>
            </thead>
            <tbody>
                <? for($i=0;$i<count($this->data->data);$i++){
                    $id=$this->data->data[$i][key($this->data->data[$i])];?>
                <tr id="<?=$i?>">
                <? $k->wrapAndPrint($this->data->data[$i],"<td>","</td>"); ?>
                </tr>
                <? } ?>
            </tbody>
        </table>
        <?
    }
}


class SettingsTable {
    var $name = "";
    var $data = null;
    var $actions = array();
    var $actionsBaseUrl = "";
    var $sortingUrl = "";
    var $deleteUrl = "";

    function __construct($name,$data,$actions=array(),$actionsBaseUrl="",$deleteUrl="",$sortingUrl=""){
        $this->name=$name;
        $this->data=$data;
        $this->actions=$actions;
        $this->actionsBaseUrl=$actionsBaseUrl;
        $this->sortingUrl=$sortingUrl;
        $this->deleteUrl=$deleteUrl;
    }

    function printTable(){
        global $k;
        ?><table id="<?=$this->name?>" class="settingsTable">
            <thead>
                <tr><?
                echo("<th></th>");
                $k->wrapAndPrint($this->data->columns,"<th>","</th>");
                if(count($this->actions)>0)echo("<th>Actions</th>"); ?></tr>
            </thead>
            <tbody>
                <? for($i=0;$i<count($this->data->data);$i++){ 
                    $id=$this->data->data[$i][key($this->data->data[$i])];?>
                <tr id="<?=$i?>">
                <? if($this->deleteUrl!="")echo('<td><a href="'.$this->deleteUrl.'&id='.$id.'">X</a></td>');else echo("<td></td>"); ?>
                <? $k->wrapAndPrint($this->data->data[$i],"<td>","</td>"); ?>
                <? if(count($this->actions)>0){
                    echo("<td>");
                    for($j=0;$j<count($this->actions);$j++){
                        echo('<form action="'.$this->actionsBaseUrl.$this->actions[$j].'" method="post">');
                            echo('<input type="hidden" name="varkey" value="'.$id.'" /><input type="submit" value="'.$this->actions[$j].'" />');
                        echo('</form>');
                    }
                    echo("</td>");
                }?>
                </tr>
                <? } ?>
            </tbody>
        </table>
        <script type="text/javascript">
            $(function(){
            <? if($this->deleteUrl!=""){ ?>
            var clicked;
            $(".settingsTable a").each(function(){
                $(this).click(function(){
                    clicked = $(this).parent().parent();
                    $.ajax({
                        url: $(this).attr("href"),
                        success: function(html){
                            if(html=="true"||html==1||html==true)clicked.remove();
                        }
                    });
                    return false;
                });
            });
            <? } ?>
            <? if($this->sortingUrl!=""){ ?>
            $("#<?=$this->name?>").tableDnD({
                onDrop: function(table, row){
                    var rows = table.tBodies[0].rows;
                    var order = rows[0].id;
                    for (var i=1; i<rows.length; i++) {
                        order += ";"+rows[i].id;
                    }
                    $.ajax({
                        url: "<?=$this->sortingUrl?>&o="+order
                    });
                }
            });
            <? } ?>
            });
        </script><?
    }
}
?>