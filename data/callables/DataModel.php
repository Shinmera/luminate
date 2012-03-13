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
    
    public function __construct($table,$fields,$data=null) {
        $this->table=$table;
        if($data==null){
            foreach($fields as $field)$this->holder[$field['name']]="";
        }else{
            foreach($data as $key=>$value)$this->holder[$key]=$value;
        }
        foreach($fields as $field){
            $this->fields[]=$field['name'];
        }
    }
    
    public function saveData(){
        global $c;
        $data = array();
        $query = 'UPDATE '.$table.' SET';
        foreach($holder as $key=>$value){
            $data[]=$value;
            $query.=' `'.$key.'`=?,';
        }
        $query=substr($query,0,strlen($query)-1);
        $query.= ' WHERE `'.$this->fields[0].'`=?';
        $data[]=$holder[$this->fields[0]];
        $c->query($query,$data);
    }
    
    public function insertData(){
        global $c;
        $data = array();
        $query = 'INSERT INTO '.$table.' VALUES(';
        foreach($fields as $field){
            if($this->holder[$field]==""){
                $query.='NULL,';
            }else{
                $data[]=$value;
                $query.='?,';
            }
        }
        $query=substr($query,0,strlen($query)-1);
        $query.= ')';
        $c->query($query,$data);
    }
    
    public static function getData($table,$query,$args=array(),$fields=array()){
        global $c;
        if(count($fields)==0)
            $fields = $c->getData('SELECT column_name AS name FROM information_schema.columns WHERE table_name=?',array($table));
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
        $fields = $c->getData('SELECT column_name AS name FROM information_schema.columns WHERE table_name=?',array($table));
        return new DataModel($table,$fields);
    }
}
?>
