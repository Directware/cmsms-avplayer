<?php
if(!isset($gCms)) exit;

$db = $this->GetDb();
$levelarray = $this->get_levelarray();

$orderedbyparent = array("player","mediafile");
if(isset($params["cancel"])){
	$this->Redirect($id,"defaultadmin",$returnid);
}elseif(isset($params["submitreorder"])){
	$order = array();
	$lastid = array();
	foreach(explode(",",$params["neworder"]) as $item){
		list($level,$itemid) = explode("___",$item);
		$lastid[$level] = $itemid;
		if(!isset($order[$level]))	$order[$level]=array();
		if($level==1){
			$order[1][]=$itemid;
		}else{
			if(!isset($order[$level][$lastid[$level-1]]))	$order[$level][$lastid[$level-1]]=array();
			$order[$level][$lastid[$level-1]][] = $itemid;
		}
	}
	foreach($order as $key=>$items){
		$level = $levelarray[$key-1];
		$item_order = 1;
		if($key==1){
			foreach($items as $item){
				$query = "UPDATE ".cms_db_prefix()."module_avplayer_".$level." SET item_order=? WHERE id=? LIMIT 1";
				$db->Execute($query,array($item_order,$item));
				$item_order++;
			}
		}else{
			foreach($items as $parent=>$children){
				if(in_array($level, $orderedbyparent))	$item_order = 1;
				foreach($children as $child){
					$query = "UPDATE ".cms_db_prefix()."module_avplayer_".$level." SET item_order=? WHERE id=? LIMIT 1";
					$db->Execute($query,array($item_order,$child));
					$item_order++;
				}
			}			
				
		}
		
	}
	$this->Redirect($id,"defaultadmin",$returnid,array("module_message"=>$this->Lang("message_modified")));
}

echo "<h1>".$this->Lang("reorder")."</h1>";
$i = 0;
$select = "";
$tables = "";
$order = "";
while($i < count($levelarray)){
	$level = $levelarray[$i];
	$i++;
	$select .= ($select==""?"":", ")."table".$i.".id table".$i."_id, table".$i.".name table".$i."_name";
	if($i == 1){
		$tables = cms_db_prefix()."module_avplayer_".$level." table".$i;
	}else{
		$tables .= " LEFT JOIN ".cms_db_prefix()."module_avplayer_".$level." table".$i. " ON table".($i-1).".id=table".$i.".parent";
	}
	$order .= ($order==""?"":", ")."table".$i.".item_order";
}
$query = "SELECT $select FROM $tables ORDER BY $order";
$dbresult = $db->Execute($query);

$last = array();
$lists = array();
$containment = array();
$parselevel = 1;
echo $this->CreateFormStart($id, "reorder", $returnid, 'post', '', false, '', array(), ' onsubmit="return ctlmm_submit_reorder();"');
while($parselevel <= $i){
	$last[$parselevel] = false;
	$lists[$parselevel] = array();
	$containment[$parselevel] = "";
	$parselevel++;
}
echo "<p>".$this->CreateInputSubmit($id,"submitreorder",lang("submit"))." ".$this->CreateInputSubmit($id,"resetorder",$this->Lang("resetorder"))." ".$this->CreateInputSubmit($id,"cancel",lang("cancel"))."</p>";
$currentlevel = 1;
echo '<ul class="sortableList sortable" id="ctlmm_bigsortlist">';
while($dbresult && $row = $dbresult->FetchRow()){
	$parselevel = 1;
	while($parselevel <= $i){
		if($row["table".$parselevel."_id"] != $last[$parselevel]){
			if($currentlevel == $parselevel){
				if($last[$currentlevel])	echo "</li>";
			}elseif($currentlevel > $parselevel){
				while($currentlevel > $parselevel){
					echo "</li></ul></li>";
					$currentlevel--;
				}
			}elseif($currentlevel < $parselevel){
				$listid = "list_".$levelarray[$parselevel]."_".$row["table".$parselevel."_id"];
				$currentlevel++;
				echo '<ul id="'.$listid.'" class="sortableList sortable">';
				$lists[$parselevel][] = $listid;
				$containment[$parselevel] .= ($containment[$parselevel]==""?"":",").'"'.$listid.'"';
			}
			echo '<li id="ctlmmlevel'.$parselevel.'___'.$row["table".$parselevel."_id"].'">'.htmlentities($row["table".$parselevel."_name"]);
			$last[$parselevel] = $row["table".$parselevel."_id"];
		}
		$parselevel++;
	}
}
echo "</li>";
while($currentlevel > 1){
	echo "</ul></li>";
	$currentlevel--;
}
echo "</ul>";
echo "<p>".$this->CreateInputSubmit($id,"submitreorder",lang("submit"))." ".$this->CreateInputSubmit($id,"resetorder",$this->Lang("resetorder"))." ".$this->CreateInputSubmit($id,"cancel",lang("cancel"))."</p>";
echo $this->CreateInputHidden($id,"neworder","",' id="'.$id.'neworder"');
echo $this->CreateFormEnd();

$restricted = $this->GetPreference("restrict_permissions",false);
if($restricted && $this->CheckPermission("avplayer_advanced"))	$restricted = false;

echo '
<script type="text/javascript" src="../lib/jquery/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../lib/jquery/js/jquery-ui-1.8.4.custom.min.js"></script>
<script type="text/javascript" src="../lib/jquery/js/jquery.ui.nestedSortable.js"></script>
<script type="text/javascript" src="../lib/jquery/js/jquery.json-2.2.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$( ".sortable" ).sortable();
	$( ".sortable" ).disableSelection();
	document.getElementById("'.$id.'moduleform_1").onsubmit = function(){
		return ctlmm_submit_reorder();
	}
});

function ctlmm_submit_reorder(){
	var neworder = document.getElementById("'.$id.'neworder");
	var listnodes = document.getElementById("ctlmm_bigsortlist").getElementsByTagName("li");
	for(i=0;i < listnodes.length;i++){
		if(neworder.value != "") neworder.value += ",";
		neworder.value += listnodes[i].id.substr(10);
	}
	return true;	
}
</script>

