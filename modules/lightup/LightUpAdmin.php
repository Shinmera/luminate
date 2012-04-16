<?
    function displayTagsAdminPage(){
        global $k,$c;
        
        if($_GET['action']=="Delete"){
            $c->query("DELETE FROM lightup_tags WHERE name=?",array($_GET['tag']));
            $err="Tag deleted.";
        }
        
        ?><div class="box">
            <a class="button" href="<?=PROOT?>LightUp/create/<?=$tag->name?>">Add a new tag</a>
            <span style="color:red;font-weight:bold;" id="err"><?=$err?></span><br />
        <table id="table">
            <thead>
                <tr>
                    <th style="width:100px;"></th>
                    <th style="width:150px;">Name</th>
                    <th style="width:150px;">Suite</th>
                    <th style="min-width:300px;">Description</th>
                </tr>
            </thead>
            <tbody>
                <? $tags = DataModel::getData("lightup_tags","SELECT `order`,name,suite,description FROM lightup_tags ORDER BY `order`,suite,name");
                if($tags!=null){
                    $i=0;
                    foreach($tags as $tag){
                        ?><tr id="<?=$i?>" name="<?=$tag->name?>">
                            <td>
                                <a href="<?=PROOT?>LightUp/create/<?=$tag->name?>" class="button">Edit</a>
                                <a href="?action=Delete&tag=<?=$tag->name?>" class="button">Delete</a>
                            </td>
                            <td><?=$tag->name?></td>
                            <td><?=$tag->suite?></td>
                            <td><?=$tag->description?></td>
                        </tr><?
                        $i++;
                    }
                }
                ?>
            </tbody>
        </table>
        <script type="text/javascript" src="<?=DATAPATH?>js/jquery.tablednd.js" ></script>
        <script type="text/javascript">
            $("#table").tableDnD({
                onDrop: function(table,row){
                    var rows = table.tBodies[0].rows;
                    var queryString = "";
                    for (var i=0; i<rows.length; i++) {
                        queryString += $(rows[i]).attr("name")+":"+i;
                        if(i<rows.length-1)queryString+=",";
                    }
                    $("#err").html("Please wait...");
                    $.post("<?=PROOT?>api/LightUpTagOrder",{order:queryString},function(data){
                        $("#err").html(data);
                    });
                }
            });
        </script>
        </div><?
    }
    
    function displaySuitesAdminPage(){
        global $k,$c;
        
        switch($_GET['action']){
            case 'Add':$c->query("INSERT INTO lightup_suites VALUES(?,?)",array($_GET['name'],$_GET['module']));break;
            case 'Delete':
                $c->query("DELETE FROM lightup_suites WHERE name=?",array($_GET['suite']));
                $c->query("UPDATE lightup_tags SET suite=? WHERE suite=?",array("default",$_GET['suite']));break;
            case 'Edit':
                if($_GET['module']!=""){
                    $c->query("UPDATE lightup_suites SET name=?,module=? WHERE name=?",array($_GET['name'],$_GET['module'],$_GET['suite']));
                    $c->query("UPDATE lightup_tags SET suite=? WHERE suite=?",array($_GET['name'],$_GET['suite']));
                }break;
        }
        
        $suite = DataModel::getData("lightup_suites","SELECT name,module FROM lightup_suites WHERE name=?",array($_GET['suite']));
        $modules = DataModel::getData("ms_modules", "SELECT `name` FROM ms_modules");
        ?>
        <form class="box" action="#" method="get">
            <h3><? if($suite==null)echo('Add');else echo('Edit');?> a Tag Suite</h3>
            <label>Name:  </label><input type="text" name="name" placeholder="Suite" autocomplete="off" value="<?=$suite->name?>" required /><br />
            <label>Module:</label><?=$k->printSelectObj("module",$modules,"name","name",$suite->module);?><br />
            <input type="hidden" name="suite" value="<?=$suite->name?>" />
            <input type="submit" value="<? if($suite==null)echo('Add');else echo('Edit');?>" name="action" />
        </form>
        <div class="box"><table>
            <thead>
                <tr>
                    <th style="width:100px;"></th>
                    <th style="width:150px;">Name</th>
                    <th style="width:150px;">Module</th>
                </tr>
            </thead>
            <tbody>
                <? $suites = DataModel::getData("lightup_suites","SELECT name,module FROM lightup_suites");
                if($suites!=null){
                    foreach($suites as $suite){
                        ?><tr>
                            <td>
                                <a href="?action=Edit&suite=<?=$suite->name?>" class="button">Edit</a>
                                <a href="?action=Delete&suite=<?=$suite->name?>" class="button">Delete</a>
                            </td>
                            <td><?=$suite->name?></td>
                            <td><?=$suite->module?></td>
                        </tr><?
                    }
                }
                ?>
            </tbody>
        </table></div><?
    }
    
    function displayTagCreatorPage(){
        global $params,$k;
        $tag = DataModel::getData("lightup_tags","SELECT * FROM lightup_tags WHERE name=?",array($params[2]));
        $suites = DataModel::getData("lightup_suites","SELECT name FROM lightup_suites");
        
        if($_POST['name']!=""){
            if($tag==null)$tag = DataModel::getHull ("lightup_tags");
            foreach($_POST as $name => $value){
                $tag->$name = $value;
            }
            if($params[2]!=""){$tag->saveData();$err="Tag updated!";}
            else              {$tag->insertData();$err="Tag added!";$tag->femcode=htmlspecialchars($tag->femcode);}
        }
        
        ?><form action="#" method="post" class="box">
            <label>Name: </label><input type="text" name="name" value="<?=$tag->name?>" placeholder="Tag" autocomplete="off" maxlength="32" required />
            <label>Suite:</label><?=$k->printSelectObj("suite",$suites,"name","name",$tag->suite);?><br />
            <label>Tag:  </label><input type="text" name="tag" value="<?=$tag->tag?>" placeholder="tag" autocomplete="off" maxlength="16" required />
            <label>Limit:</label><input type="number" name="limit" value="<?=$tag->limit?>" placeholder="-1" autocomplete="off" required /><br />
            Description:<br />
            <input type="text" name="description" value="<?=$tag->description?>" style="width:100%" placeholder="Markup Tag" autocomplete="off" maxlength="64" /><br />
            <br />
            Femcode:<br />
            <input type="text" id="femcode" name="femcode" value="<?=$tag->femcode?>" style="width:100%" placeholder='<tag attr="$TYPE|default$">@</tag>' autocomplete="off" maxlength="128" required /><br />
            Tagcode:<br />
            <input type="text" id="tagcode" name="tagcode" value="<?=$tag->tagcode?>" style="width:100%" placeholder='tag($Enter TYPE|type$){@}' autocomplete="off" maxlength="128" required /><br />
            Preview:<br />
            <style>.previewbox{border: 1px solid #CCC;background-color:#EEE;}</style>
            <div class="previewbox" id="userpreview">i{lol}</div>
            <div class="previewbox" id="parsepreview" style="min-height:100px;"><em>lol</em></div>
            <input type="submit" value="Submit" /><span style="color:red;font-weight:bold;"><?=$err?></span>
        </form>
        <script type="text/javascript">
            function updatePreviewBoxes(){
                var tagcode = $("#tagcode").attr("value")+" ";
                var femcode = $("#femcode").attr("value")+" ";
                tagcode = tagcode.replace("@","This is something random.");
                while(stringContains(tagcode,"$")){
                    var strings0 = tagcode.substring(0,tagcode.indexOf("$"));tagcode = tagcode.substring(tagcode.indexOf("$")+1,tagcode.length);
                    var strings1 = tagcode.substring(0,tagcode.indexOf("$"));
                    var strings2 = tagcode.substring(tagcode.indexOf("$")+1,tagcode.length);
                    var type = "text";
                    if(stringContains(strings1,"|")){
                        type = strings1.substring(strings1.indexOf("|")+1);
                    }
                    switch(type){
                        case "text":    tagcode=strings0+"Random text"+strings2;break;
                        case "number":  tagcode=strings0+"42"+strings2;break;
                        case "email":   tagcode=strings0+"text@code.ty"+strings2;break;
                        case "date":    tagcode=strings0+"1.1.1970"+strings2;break;
                        case "url":     tagcode=strings0+"http://<?=HOST?>"+strings2;break;
                    }
                }
                femcode = femcode.replace("@","This is something random.");
                while(stringContains(femcode,"$")){
                    var strings0 = femcode.substring(0,femcode.indexOf("$"));femcode = femcode.substring(femcode.indexOf("$")+1,femcode.length);
                    var strings1 = femcode.substring(0,femcode.indexOf("$"));
                    var strings2 = femcode.substring(femcode.indexOf("$")+1,femcode.length);
                    var type = "TEXT";
                    if(stringContains(strings1,"|")){
                        type = strings1.substring(0,strings1.indexOf("|"));
                    }
                    switch(type){
                        case "TEXT":femcode=strings0+"Random text"+strings2;break;
                        case "STRI":femcode=strings0+"String"+strings2;break;
                        case "INTE":femcode=strings0+"42"+strings2;break;
                        case "MAIL":femcode=strings0+"text@code.ty"+strings2;break;
                        case "DATE":femcode=strings0+"1.1.1970"+strings2;break;
                        case "URLS":femcode=strings0+"http://<?=HOST?>"+strings2;break;
                        default    :femcode=strings0+"20"+strings2;break;
                    }
                }
                
                $("#userpreview").html(tagcode);
                $("#parsepreview").html(femcode);
            }
            
            $().ready(function(){
                updatePreviewBoxes();
                $("#femcode").keypress(function(){updatePreviewBoxes();});
                $("#tagcode").keypress(function(){updatePreviewBoxes();});
            });
        </script><?
    }
?>
