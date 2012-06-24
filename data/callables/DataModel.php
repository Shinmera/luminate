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
    
    public function getTable(){return $this->table;}
    public function getHolder(){return $this->holder;}
    
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
        if(count($this->fields)==0) $this->fields=$c->getTableColumns($this->table);
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
            if(is_numeric($this->holder[$primary]))$query.= ' `'.$primary.'`=? AND';
            else                                   $query.= ' `'.$primary.'` LIKE ? AND';
            $data[]=$this->holder[$primary];
        }
        $c->query(substr($query,0,strlen($query)-3),$data);
    }
    
    public function deleteData(){
        global $c;
        if(count($this->primary)==0)$this->primary=$c->getTablePrimaryKeys($this->table);
        
        $data = array();
        $query = 'DELETE FROM '.$this->table.' WHERE ';
        foreach($this->primary as $primary){
            if(is_numeric($this->holder[$primary]))$query.= ' `'.$primary.'`=? AND';
            else                                   $query.= ' `'.$primary.'` LIKE ? AND';
            $data[]=$this->holder[$primary];
        }
        $c->query(substr($query,0,strlen($query)-3),$data);
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
