<?php
if (!isset($gCms)) exit;

// we retrieve some preferences
$decode = $this->GetPreference("fe_decodeentities",false);
$wysiwyg = $this->GetPreference("fe_wysiwyg", false);
$allowfiles = $this->GetPreference("fe_allowfiles", false);
$allownamechange = $this->GetPreference("fe_allownamechange", false);
$allowaddnew = $this->GetPreference("fe_allowaddnew", false);
$filemaxsize = (int)$this->GetPreference("fe_maxfilesize", "");

$captcha = false;
if($this->GetPreference("fe_usecaptcha", false) && isset($gCms->modules["Captcha"]) && $gCms->modules["Captcha"]["active"]){
	$captcha = $this->getModuleInstance("Captcha");
}

$db =& $this->GetDb();

$item = new stdClass();
$item->id = false;

// here we retrieve the item we're working with.
// We can't work with either id or alias alone, as it is alias that are being used in the tags but alias can change...
if(!isset($params["alias"]) && isset($params["feadd_id"])){
	$query = "SELECT id, alias FROM ".cms_db_prefix()."module_avplayer_player WHERE id=? LIMIT 1";
	$dbresult = $db->Execute($query,array($params["feadd_id"]));
	if($dbresult && $row = $dbresult->FetchRow()){
		$item->id = $row["id"];
		$item->alias = $row["alias"];
	}
}elseif(!isset($params["feadd_id"]) && isset($params["alias"])){
	$query = "SELECT id, alias FROM ".cms_db_prefix()."module_avplayer_player WHERE alias=? LIMIT 1";
	$dbresult = $db->Execute($query,array($params["alias"]));
	if($dbresult && $row = $dbresult->FetchRow()){
		$item->id = $row["id"];
		$item->alias = $row["alias"];
	}
}

// we check if there is sufficient permission ( function.feadd_permcheck.php does most of the job - see FAQ)
if(	(!$allowaddnew && !$item->id)	||
	(!$this->feadd_permcheck("player", $item->id, $item->id?$item->alias:false))
  ){
	echo '<div class="feadd_form_message">'.$this->Lang("error_feadddenied")."</div>";
	return false;
}

if(isset($params["feadd_id"]) && isset($params["feaddfiledelete"])){
	$query = "DELETE FROM ".cms_db_prefix()."module_avplayer_multiplefilesfields WHERE fileid=? AND itemid=? LIMIT 1";
	$db->Execute( $query, array($params["feaddfiledelete"], $item->id) );
	unset($params["feaddfiledelete"]);
}

$userdefined1options = $this->get_fieldoptions("player_userdefined1");


// BEGIN FORM SUBMISSION
if (isset($params["feaddsubmit"])) {
	
	debug_buffer("Edit Form has been submitted".__LINE__);

	// RETRIEVING THE FORM VALUES (and escaping it, if needed)
	if(isset($params["feadd_item_order"])) $item->item_order = $params["feadd_item_order"];
	$item->location = $decode?html_entity_decode($params["feadd_location"]):$params["feadd_location"];
		$item->width = $decode?html_entity_decode($params["feadd_width"]):$params["feadd_width"];
		$item->height = $decode?html_entity_decode($params["feadd_height"]):$params["feadd_height"];
		$item->parameters = $decode?html_entity_decode($params["feadd_parameters"]):$params["feadd_parameters"];
		$item->userdefined1 = $decode?html_entity_decode($params["feadd_userdefined1"]):$params["feadd_userdefined1"];
		$item->name = $decode?html_entity_decode($params["feadd_name"]):$params["feadd_name"];
		
	$item->alias = $this->plcreatealias($item->name);
	
	$autoincrementalias = $this->GetPreference("autoincrement_alias",false);

	if($captcha && !$captcha->checkCaptcha($params["captcha_input"])){
		echo '<div class="feadd_form_message">'.$this->Lang("error_captcha")."</div>";
	}elseif(	!isset($params["feadd_location"]) || $params["feadd_location"] == ""
			 || !isset($params["feadd_name"]) || $params["feadd_name"] == ""
			 )
	{
		echo '<div class="feadd_form_message">'.$this->Lang("error_missginvalue")."</div>";
	}elseif(!$autoincrementalias && false == $this->checkalias("module_avplayer_player", $item->alias, $item->id?$item->id:false)){
		echo '<div class="feadd_form_message">'.$this->Lang("error_alreadyexists")."</div>";
	}else{
		############ DOING THE UPDATE

		if($autoincrementalias){
			$basealias = $item->alias;
			$tmpalias = $item->alias;
			$i = 1;
			while(!$this->checkalias("module_avplayer_player", $tmpalias, $item->id?$item->id:false)){
				$tmpalias = $basealias."_".$i;
				$i++;
			}
			$item->alias = $tmpalias;
		}

		// FIELDS TO UPDATE
		$query = ($item->id?"UPDATE ":"INSERT INTO ").cms_db_prefix()."module_avplayer_player SET 
			location=?,
				width=?,
				height=?,
				parameters=?,
				userdefined1=?,
				name=?,
		alias=?,
		date_modified=?,
		active=".(isset($item->active)?$item->active:1).",
		isdefault=".(isset($item->isdefault)?$item->isdefault:0)."";
			
		// VALUES
		$values = array(addslashes($item->location),
				addslashes($item->width),
				addslashes($item->height),
				addslashes($item->parameters),
				$item->userdefined1,
				addslashes($item->name),$item->alias,str_replace("'","",$db->DBTimeStamp(time())));

		if($item->id){
			$event = "avplayer_modified";
			$query .= " WHERE id=?;";
			array_push($values,$item->id);
		}else{
			// NEW ITEM
			$event = "avplayer_added";
			$query .= ", date_created=?";
			$values[] = str_replace("'","",$db->DBTimeStamp(time()));
			// get a new id from the sequence table
			$item->id = $db->GenID(cms_db_prefix()."module_avplayer_player_seq");
			if($this->GetPreference("newitemsfirst_player",false)){
				// new items get to the top - so we must put all other items down from one step, and then set this item's order to 1
				$query2 = "UPDATE ".cms_db_prefix()."module_avplayer_player SET item_order=(item_order+1)";
				$db->Execute($query2);
				$query .= ",item_order=1, id=".$item->id;
			}else{
				// new items get to the bottom - so we must set the item_order to the number of items + 1
				$item_order = $this->countsomething("player") + 1;
				$query .= ",item_order=".$item_order.", id=".$item->id;
			}
		}
		$db->Execute($query, $values);


		

		if($db->Affected_Rows()){
			if($this->GetPreference("searchmodule_index_player",false)){
				// IF ANYTHING WAS MODIFIED, WE MUST UPDATE THE SEARCH INDEX AND SEND AN EVENT...
				if(isset($event))	$this->SendEvent($event, array("what"=>"player", "itemid" => $item->id, "alias"=>$item->alias));
				$module =& $this->GetModuleInstance("Search");
				if($module) {
					debug_buffer("SEARHC INDEX WAS UPDATED ".__LINE__);
					$text = "$item->name";
					$module->AddWords($this->GetName(), $item->id, "player", $text, NULL);
				}
			}
			echo '<div class="feadd_form_message">'.$this->Lang("message_modified")."</div>";
		}
		
		// if a content redirection has been set, we redirect...
		$redirect_to_id = $this->GetPreference("fe_aftersubmit",-1);
		if( $redirect_to_id > 1 )	$this->RedirectContent($redirect_to_id);
	}
	// END OF FORM SUBMISSION
	
	if(!isset($params["feadd_id"]) && isset($item->id))	$params["feadd_id"] = $item->id;
}

if($item->id) {
	// if we are working on an item that exists, we load it. We must do this even when the form is submitted, otherwise we won't have the file fields
	$items = $this->get_level_player(array("id"=>$item->id));
	$item = $items[0];
}


/* ## PREPARING SMARTY ELEMENTS
CreateInputText : (id,name,value,size,maxlength)
CreateTextArea : (wysiwyg,id,text,name)
CreateInputSelectList : (id,name,items,selecteditems,size)
CreateInputDropdown : (id,name,items,sindex,svalue)
*/

if(!$item->id || $allownamechange){
	$nameinput = $this->CreateInputText($id,"feadd_name",$item->id?$item->name:"",50,64);
}else{
	$nameinput = $item->name.$this->CreateInputHidden($id, "feadd_name", $item->name);
}
$this->smarty->assign("name_label", $this->Lang("name"));
$this->smarty->assign("name_input", $nameinput);
$this->smarty->assign("location_label", $this->Lang("player_location"));
$this->smarty->assign("location_input", $this->CreateInputText($id,"feadd_location",$item->id?$item->location:"",50,255));
$this->smarty->assign("width_label", $this->Lang("player_width"));
$this->smarty->assign("width_input", $this->CreateInputText($id,"feadd_width",$item->id?$item->width:"",30,10));
$this->smarty->assign("height_label", $this->Lang("player_height"));
$this->smarty->assign("height_input", $this->CreateInputText($id,"feadd_height",$item->id?$item->height:"",30,10));
$this->smarty->assign("parameters_label", $this->Lang("player_parameters"));
$this->smarty->assign("parameters_input", $this->CreateInputText($id,"feadd_parameters",$item->id?$item->parameters:"",50,255));
$this->smarty->assign("userdefined1_label", $this->Lang("player_userdefined1"));
$this->smarty->assign("userdefined1_input", $this->CreateInputDropdown($id,"feadd_userdefined1",$userdefined1options,-1,$item->id?$item->userdefined1:0));
$this->smarty->assign("itemalias",isset($item->alias)?"(alias : ".$item->alias.")":false);
$this->smarty->assign("alias_input", false);

$this->smarty->assign("edittitle", $this->Lang("edit_player"));

$this->smarty->assign("submit", $this->CreateInputSubmit($id, "feaddsubmit", $this->Lang("submit")));
$this->smarty->assign("cancel", $this->CreateInputSubmit($id, "feaddcancel", $this->Lang("cancel")));
$this->smarty->assign("captcha_image", $captcha?$captcha->getCaptcha():false);
$this->smarty->assign("captcha_input", $captcha?$this->CreateInputText($id,"captcha_input","",30,10):false);
$this->smarty->assign("captcha_prompt", $captcha?$this->Lang("prompt_captcha"):false);


$this->smarty->assign("item", $item);


// DISPLAYING
	
echo $this->CreateFormStart($id, "FEaddA", $returnid, "post", "multipart/form-data");
echo $this->ProcessTemplate("frontend_add_player.tpl");

if(isset($item) && isset($item->id)){
	echo $this->CreateInputHidden($id, "feadd_id", $item->id);
	if(isset($item->parent)) echo $this->CreateInputHidden($id, "feadd_oldparent", $item->parent);
	echo $this->CreateInputHidden($id, "feadd_item_order", $item->item_order);
}
echo $this->CreateFormEnd();

?>