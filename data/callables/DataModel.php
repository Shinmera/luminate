<?
class DataModel{
    private $table;
    private $fields;
    private $holder;
  
    public function __get($key){
        return isset($this->holder[$key]) ? $this->holder[$key] : false;
    }

    public function __set($key, $value){
        $this->holder[$key] = $value;
    }

    public function toArray(){
        return print_r($this->holder, true);
    }
    
    private function __construct($table,$fields=array(),$data=null) {
        $this->table=$table;
        if($data==null){
            foreach($fields as $field)$this->holder[$field['name']]="";
        }else{
            foreach($data as $key=>$value)$this->holder[$key]=$value;
        }
    }
    
    //TODO: Add support for multiple primary keys.
    //TODO: Add support for functions on values.
    public function saveData(){
        global $c;
        if(count($this->fields)==0)$this->fields=$c->getTableColumns($this->table);
            
        $data = array();
        $query = 'UPDATE '.$this->table.' SET';
        foreach($this->holder as $key=>$value){
            if(in_array($key,$this->fields)){
                $data[]=$value;
                $query.=' `'.$key.'`=?,';
            }
        }
        $data[]=$this->holder[$this->fields[0]];
        $query=substr($query,0,strlen($query)-1);
        if(is_numeric($this->holder[$this->fields[0]]))$query.= ' WHERE `'.$this->fields[0].'`=?';
        else                                           $query.= ' WHERE `'.$this->fields[0].'` LIKE ?';
        $c->query($query,$data);
    }
    
    //TODO: Add support for functions on values.
    public function insertData(){
        global $c;
        if(count($this->fields)==0)$this->fields=$c->getTableColumns($this->table);
        
        $data = array();
        $query = 'INSERT INTO '.$this->table.' SET';
        foreach($this->fields as $field){
            if(isset($this->holder[$field])){
                $data[]=$this->holder[$field];
                $query.=' `'.$field.'`=?,';
            }
        }
        $query=substr($query,0,strlen($query)-1);
        $c->query($query,$data);
    }
    
    //@untested
    //TODO: Add opertors for selected values.
    //TODO: Somehow allow more complex operations on selects and wheres: ( WHERE x/2=y )
    //TODO: Somehow allow more complex operations on selects and wheres with multiple function arguments, ( CONCAT(x,y) = z )
    public static function selectData($table,$fields,$whereString=null,$whereVars=array(),$limit=null,$innerJoinTable=null,$innerJoinVars=null,$loadFields=false){
        if(!is_string($table)||$table=="")throw new Exception('No valid table name specified.');
        if(!is_array($fields))throw new Exception('$fields has to be an array!');
        if(count($fields)==0)throw new Exception('No field to select specified.');
        global $k;$data=array();
        
        if(!$k->isAssociative($fields))
            $query = 'SELECT `'.$table.'`.`'.implode('`,`'.$table.'`.`',$fields).'` FROM `'.$table.'` ';
        else{
            $query = 'SELECT ';
            foreach($fields as $field => $ftable){
                $query.= '`'.$ftable.'`.`'.$field.'`,';
            }
            $query= substr($query,0,strlen($query)-1).' FROM `'.$table.'` ';
        }
        
        if($innerJoinTable!=""){
            $query.='INNER JOIN `'.$innerJoinTable.'` ';
            if(!is_array($innerJoinVars))$query.='USING(`'.$innerJoinVars.'`) ';
            else{
                switch(count($innerJoinVars)){
                    case 0:throw new Exception("InnerJoinVars cannot be empty!");break;
                    case 1:$query.='USING(`'.$innerJoinVars[0].'`) ';break;
                    case 2:$query.='ON `'.$table.'`.`'.$innerJoinVars[0].'` = `'.$innerJoinTable.'`.`'.$innerJoinVars[0].'` ';break;
                    default:throw new Exception("InnerJoinVars does not accept more than 2 arguments!");break;
                }
            }
        }
        
        if(count($whereVars)>0){
            if(!is_array($whereVars)&&$whereVars!=null)
                throw new Exception("Unexpected where variable format!\nWhereVars: ".print_r($whereVars, true));
            if(count($whereVars)!=substr_count($whereString,"?"))
                throw new Exception("Unmatched where variables!\nWhereString: ".$whereString."\nWhereVars: ".print_r($whereVars,true));
            
            //Where vars: :key ( :value &opt :conjunction :table1 :table2 :operator1 :operator2)
            //Defaults:     ?    ?           =            null    null    ''         '' 
            $query.='WHERE ';
            $whereString=str_replace('|',' OR ',str_replace('&',' AND ',$whereString));
            $where = explode('?',$whereString);
            $i=0;
            foreach($whereVars as $els){
                $query.=$where[$i];
                if(!is_array($els)){
                    throw new Exception("Elements of WhereVars have to be an array!\nWhereString: ".$whereString.'\nWhereVars: '.print_r($whereVars,true));
                }else{
                    switch(count($els)){
                        case 0:throw new Exception("Unexpected empty where value!\nWhereString: ".$whereString.'\nWhereVars: '.print_r($whereVars,true));break;
                        case 1:throw new Exception("Value is missing!\nWhereString: ".$whereString.'\nWhereVars: '.print_r($whereVars,true));break;
                        case 2:$els[2]='=';
                        case 3:$els[3]=null;
                        case 4:$els[4]=null;
                        case 5:$els[5]='';
                        case 6:$els[6]='';
                        case 7:
                            global $c;
                            if(!in_array($els[2],$c->comparisonOperators))
                                throw new Exception("Invalid or unsupported comparison operator: ".$els[2]."\nWhereString: ".$whereString."\nWhereVars: ".print_r($whereVars,true));
                            if(!in_array($els[5],$c->fieldOperators)&&$els[5]!='')
                                throw new Exception("Invalid or unsupported field operator: ".$els[5]."\nWhereString: ".$whereString."\nWhereVars: ".print_r($whereVars,true));
                            if(!in_array($els[6],$c->fieldOperators)&&$els[6]!='')
                                throw new Exception("Invalid or unsupported field operator: ".$els[6]."\nWhereString: ".$whereString."\nWhereVars: ".print_r($whereVars,true));
                            
                            if($els[3]==null)$field1='`'.$els[0].'`';
                            else             $field1='`'.$els[3].'`.`'.$els[0].'`';
                            if($els[4]==null)$field2='?';
                            else             $field2='`'.$els[4].'`.`'.$els[1].'`';
                            
                            if($els[5]!='')$els[5]=$els[5].'('.$field1.')';
                            else           $els[5]=            $field1;
                            if($els[6]!='')$els[6]=$els[6].'('.$field2.')';
                            else           $els[6]=            $field2;
                            
                            $query.=' '.$els[5].' '.$els[2].' '.$els[6].' ';
                            if($els[4]==null)$data[]=$els[1];
                            break;
                        default:throw new Exception("WhereVars does not accept more than 7 arguments!");break;
                    }
                }
                $i++;
            }
            $query.=$where[$i].' ';
        }
        
        if($limit!=null){
            if(!is_array($limit))$query.='LIMIT '.$limit;
            else{
                if(count($limit)==1)$query.='LIMIT '.$limit[0];
                else                $query.='LIMIT '.$limit[0].','.$limit[1];
            }
        }
        
        return DataModel::getData($table, $query, $data, array(), $loadFields);
    }
    
    //@deprecated
    public static function getData($table,$query,$args=array(),$fields=array(),$loadFields=false){
        global $c;
        if($loadFields)$fields=$c->getTableColumns($this->table);
        $data = $c->getData($query,$args);
        $models = array();
        foreach($data as $element){
            $models[] = new DataModel($table,$fields,$element);
        }
        if(count($models)>1)return $models;
        else if(count($models)==1)return $models[0];
        else return null;
    }
    
    public static function getHull($table){
        global $c;
        //$fields = $c->getData('SELECT column_name AS name FROM information_schema.columns WHERE table_name=? AND table_schema=?',array($table,SQLDB));
        return new DataModel($table);
    }
}
?>
