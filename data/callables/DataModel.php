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
    
    public function insertData(){
        global $c;
        if(count($this->fields)==0)$this->fields=$c->getTableColumns($this->table);
        
        $data = array();
        $query = 'INSERT INTO '.$this->table.' VALUES(';
        foreach($this->fields as $field){
            if($this->holder[$field]===""||!isset($this->holder[$field])){
                $query.='NULL,';
            }else{
                $data[]=$this->holder[$field];
                $query.='?,';
            }
        }
        $query=substr($query,0,strlen($query)-1);
        $query.= ')';
        $c->query($query,$data);
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
    
    public static function getHull($table){
        global $c;
        //$fields = $c->getData('SELECT column_name AS name FROM information_schema.columns WHERE table_name=? AND table_schema=?',array($table,SQLDB));
        return new DataModel($table);
    }
}
?>
