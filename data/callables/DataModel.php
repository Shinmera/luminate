<?
class DataModel{
    private $table;
    private $fields=array();
    private $primary=array();
    private $holder=array();
  
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
    
    public function saveData(){
        global $c;
        if(count($this->fields)==0)$this->fields=$c->getTableColumns($this->table);
        if(count($this->primary)==0)$this->primary=$c->getTablePrimaryKeys($this->table);
            
        $data = array();
        $query = 'UPDATE '.$this->table.' SET';
        foreach($this->holder as $key=>$value){
            if(in_array($key,$this->fields)){
                $data[]=$value;
                $query.=' `'.$key.'`=?,';
            }
        }
        $query=substr($query,0,strlen($query)-1).' WHERE ';
        
        foreach($this->primary as $primary){
            if(is_numeric($this->holder[$primary]))$query.= '`'.$primary.'`=?';
            else                                   $query.= '`'.$primary.'` LIKE ?';
            $data[]=$this->holder[$primary];
        }
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
    
    //TODO: Somehow allow more complex operations on selects and wheres: ( WHERE x/2=y )
    //TODO: Add support for multi-parameter function calls
    /**
     * Arguments explained in proper structure:
     * ( 
     *   :table table
     *   :fields ( &body field1 field2 )
     *           ( &body (:field &opt :table=&:table :function='' :args='' :as=&:field )
     *   &opt
     *   :jointable table=NIL
     *   :joinvars field=NIL
     *             ( field &opt field2 )
     *   :joincomparison comparison=NIL
     *   :where wherestruct
     *   :vars ( &body ( key1 value1 &opt comparison='=' keytable=&:table valuetable=NIL keyoperator='' valueoperator='' )
     *   :order order=NIL
     *          ( field )
     *          ( &body ( field &opt direction='ASC' table=&:table ) )
     *   :limit limit=NIL
     *          ( limit )
     *          ( lower offset )
     * )
     * 
     * Simple function call example:
     * MIN  "SELECT username,relationship FROM users WHERE awesomeness=100"
     * CALL selectData(array(
     *                  "table"=>"users",
     *                  "fields"=>array("username","relationship"),
     *                  "where"=>"?","vars"=>array(array("awesomeness",100))));
     * RES  "SELECT `users`.`username` AS 'username',`users`.`relationship` AS 'relationship' FROM `users` WHERE `users`.`awesomeness` = 100"
     * 
     * Complex function call example:
     * TO   selectData(array("table"=>"users",
     *                       "fields"=>array("id",
     *                          array("field"=>"id","function"=>"COUNT","as"=>"COUNT"),
     *                          array("field"=>"tel","table"=>"telephone")),
     *                       "jointable" => "telephone",
     *                       "joinvars"  => array("id","uid"),
     *                       "where"     => "(?|?|?)&?",
     *                       "vars"      => array(
     *                                           array( "username" "Mithent"),
     *                                           array( "username "Shinmera" "LIKE"),
     *                                           array( "username" "%A%" "LIKE"),
     *                                           array( "awesomeness" 90 ">")
     *                                      ),
     *                       "order"     => array(
     *                                           "firstname"
     *                                           "lastname"
     *                                           array("awesomeness" "DESC")
     *                                      ),
     *                       "limit"     => 20));
     * RES  "SELECT `users`.`id` AS 'id',COUNT(`users`.`id`) AS 'COUNT',`telephone`.`tel` AS 'tel'
     *       FROM `users` INNER JOIN `telephone` ON `users`.`id`=`telephone`.`uid`
     *       WHERE ( `users`.`username` = 'Mithent' OR `users`.`username` LIKE 'Shinmera' OR `users`.`username` LIKE '%A%' ) AND `users`.`awesomeness` > 90
     *       ORDER BY `users`.`firstname` ASC,`users`.`lastname` ASC,`users`.`awesomeness` DESC 
     *       LIMIT 20"
     */
    public static function selectData($args,$loadFields=false){
        global $k;$data=array();
        
        $table=$args['table'];
        $fields=$args['fields'];
        
        //Validate
        if(!is_string($table)||$table=="")throw new Exception('No valid table name specified.');
        if(!is_array($fields))throw new Exception('$fields has to be an array!');
        if(count($fields)==0)throw new Exception('No field to select specified.');
        if(!is_array($args))throw new Exception('Args needs to be an array!');
        
        //Defaults
        if(!isset($args['where']))$whereString=null;        else $whereString   =$args['where'];
        if(!isset($args['vars'])) $whereVars  =array();     else $whereVars     =$args['vars'];
        if(!isset($args['limit']))$limit      =null;        else $limit         =$args['limit'];
        if(!isset($args['order']))$orders     =null;        else $orders        =$args['order'];
        if(!isset($args['jointable']))$innerJoinTable=null; else $innerJoinTable=$args['jointable'];
        if(!isset($args['joinvars'])) $innerJoinVars =null; else $innerJoinVars =$args['joinvars'];
        if(!isset($args['joincomparison']))$innerJoinComparison='=';else $innerJoinComparison=$args['joincomparison'];
        
        //Compose SELECT part
        $query = 'SELECT ';
        foreach($fields as $field){
            if(!is_array($field))
                $query.= '`'.$table.'`.`'.$field.'` AS \''.$field.'\'';
            else{
                if(!isset($field['table']))$field['table']=$table;
                if(isset($field['function']))$field['function'].='(';
                if(!isset($field['as']))$field['as']=$field['field'];

                $query.= $field['function'].'`'.$field['table'].'`.`'.$field['field'].'`';
                if(isset($field['args']))$query.=','.implode(',',$field['args']);
                if(isset($field['function']))$query.=')';
                $query.= ' AS \''.$field['as'].'\'';
            }
            $query.=',';
        }
        $query=substr($query,0,strlen($query)-1).' FROM `'.$table.'` ';
        
        //Compose INNER JOIN part
        if($innerJoinTable!=""){
            $query.='INNER JOIN `'.$innerJoinTable.'` ';
            if(!is_array($innerJoinVars))$query.='USING(`'.$innerJoinVars.'`) ';
            else{
                
                switch(count($innerJoinVars)){
                    case 0:throw new Exception("InnerJoinVars cannot be empty!");break;
                    case 1:$query.='USING(`'.$innerJoinVars[0].'`) ';break;
                    case 2:$query.='ON `'.$table.'`.`'.$innerJoinVars[0].'` '.$innerJoinComparison.' `'.$innerJoinTable.'`.`'.$innerJoinVars[1].'` ';break;
                    default:throw new Exception("InnerJoinVars does not accept more than 2 arguments!");break;
                }
            }
        }
        
        //Compose WHERE part
        if(count($whereVars)>0){
            if(!is_array($whereVars)&&$whereVars!=null)
                throw new Exception("Unexpected where variable format!\nWhereVars: ".print_r($whereVars, true));
            if(count($whereVars)!=substr_count($whereString,"?"))
                throw new Exception("Unmatched where variables!\nWhereString: ".$whereString."\nWhereVars: ".print_r($whereVars,true));
            
            //Where vars: ( :key :value &opt :conjunction :table1 :table2 :operator1 :operator2 )
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
                        case 0:throw new Exception("Unexpected empty where key!\nWhereString: ".$whereString.'\nWhereVars: '.print_r($whereVars,true));break;
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
        
        //Compose ORDER BY part
        if($orders!=null){
            $query.='ORDER BY ';
            if(!is_array($orders))$query.='`'.$table.'`.`'.$orders.'` ASC ';
            else{
                foreach($orders as $order){
                    if(!is_array($order))$query.='`'.$table.'`.`'.$order.'` ASC,';
                    else{
                        switch(count($order)){
                            case 0:break;
                            case 1:$order[1]='ASC';
                            case 2:$order[2]=$table;
                            case 3:$query.='`'.$order[2].'`.`'.$order[0].'` '.$order[1].',';break;
                        }
                    }
                }
                $query=substr($query,0,strlen($query)-1).' ';
            }
        }
        
        //Compose LIMIT part
        if($limit!=null){
            if(!is_array($limit))$query.='LIMIT '.$limit;
            else{
                if(count($limit)==1)$query.='LIMIT '.$limit[0];
                else                $query.='LIMIT '.$limit[0].','.$limit[1];
            }
        }
        
        //Execute query
        return DataModel::getData($table, $query, $data, array(), $loadFields);
    }
    
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
    
    public static function getHull($table,$loadFields=false){
        global $c;
        if($loadFields)$fields=$c->getTableColumns($this->table);
        else           $fields=array();
        return new DataModel($table,$fields);
    }
}
?>
