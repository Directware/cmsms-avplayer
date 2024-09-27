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
	$query = "SELECT id, alias FROM ".cms_db_prefix()."module_avplayer_mediafile WHERE id=? LIMIT 1";
	$dbresult = $db->Execute($query,array($params["feadd_id"]));
	if($dbresult && $row = $dbresult->FetchRow()){
		$item->id = $row["id"];
		$item->alias = $row["alias"];
	}
}elseif(!isset($params["feadd_id"]) && isset($params["alias"])){
	$query = "SELECT id, alias FROM ".cms_db_prefix()."module_avplayer_mediafile WHERE alias=? LIMIT 1";
	$dbresult = $db->Execute($query,array($params["alias"]));
	if($dbresult && $row = $dbresult->FetchRow()){
		$item->id = $row["id"];
		$item->alias = $row["alias"];
	}
}

// we check if there is sufficient permission ( function.feadd_permcheck.php does most of the job - see FAQ)
if(	(!$allowaddnew && !$item->id)	||
	(!$this->feadd_permcheck("mediafile", $item->id, $item->id?$item->alias:false))
  ){
	echo '<div class="feadd_form_message">'.$this->Lang("error_feadddenied")."</div>";
	return false;
}

if(isset($params["feadd_id"]) && isset($params["feaddfiledelete"])){
	$query = "DELETE FROM ".cms_db_prefix()."module_avplayer_multiplefilesfields WHERE fileid=? AND itemid=? LIMIT 1";
	$db->Execute( $query, array($params["feaddfiledelete"], $item->id) );
	unset($params["feaddfiledelete"]);
}


if($this->GetPreference("use_hierarchy",false)){
	$parentoptions = $this->get_admin_hierarchyoptions("mediafile",false);
}else{
	$parentoptions = $this->get_options("player");
}

// BEGIN FORM SUBMISSION
if (isset($params["feaddsubmit"])) {
	
	debug_buffer("Edit Form has been submitted".__LINE__);

	// RETRIEVING THE FORM VALUES (and escaping it, if needed)
	if(isset($params["feadd_item_order"])) $item->item_order = $params["feadd_item_order"];
	$item->description = $decode?html_entity_decode($params["feadd_description"]):$params["feadd_description"];
		$item->parent = $decode?html_entity_decode($params["feadd_parent"]):$params["feadd_parent"];
		$item->name = $decode?html_entity_decode($params["feadd_name"]):$params["feadd_name"];
		
	$item->alias = $this->plcreatealias($item->name);
	
	$autoincrementalias = $this->GetPreference("autoincrement_alias",false);

	if($captcha && !$captcha->checkCaptcha($params["captcha_input"])){
		echo '<div class="feadd_form_message">'.$this->Lang("error_captcha")."</div>";
	}elseif(	!isset($params["feadd_name"]) || $params["feadd_name"] == ""
			 )
	{
		echo '<div class="feadd_form_message">'.$this->Lang("error_missginvalue")."</div>";
	}elseif(!$autoincrementalias && false == $this->checkalias("module_avplayer_mediafile", $item->alias, $item->id?$item->id:false)){
		echo '<div class="feadd_form_message">'.$this->Lang("error_alreadyexists")."</div>";
	}else{
		############ DOING THE UPDATE

		if($autoincrementalias){
			$basealias = $item->alias;
			$tmpalias = $item->alias;
			$i = 1;
			while(!$this->checkalias("module_avplayer_mediafile", $tmpalias, $item->id?$item->id:false)){
				$tmpalias = $basealias."_".$i;
				$i++;
			}
			$item->alias = $tmpalias;
		}

		// FIELDS TO UPDATE
		$query = ($item->id?"UPDATE ":"INSERT INTO ").cms_db_prefix()."module_avplayer_mediafile SET 
			description=?,
				parent=?,
				name=?,
		alias=?,
		date_modified=?,
		active=".(isset($item->active)?$item->active:1).",
		isdefault=".(isset($item->isdefault)?$item->isdefault:0)."";
			
		// VALUES
		$values = array($item->description,
				$item->parent,
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
			$item->id = $db->GenID(cms_db_prefix()."module_avplayer_mediafile_seq");
			if($this->GetPreference("newitemsfirst_mediafile",false)){
				// new items get to the top - so we must put all other items down from one step, and then set this item's order to 1
				$query2 = "UPDATE ".cms_db_prefix()."module_avplayer_mediafile SET item_order=(item_order+1) WHERE parent=?";
				$db->Execute($query2, array($item->parent));
				$query .= ",item_order=1, id=".$item->id;
			}else{
				// new items get to the bottom - so we must set the item_order to the number of items + 1
				$item_order = $this->countsomething("mediafile","id",array("parent"=>$item->parent)) + 1;
				$query .= ",item_order=".$item_order.", id=".$item->id;
			}
		}
		$db->Execute($query, $values);

	if(isset($params["feadd_oldparent"]) && $params["feadd_oldparent"] != $item->parent){
		// the item is changing parent, and we're ordering by parents
		
		if($this->GetPreference("newitemsfirst_mediafile",false)){
			// new items get to the top
			
			// UPDATE THE ORDER OF THE ITEMS WITH THE OLD PARENT
			$query = "UPDATE ".cms_db_prefix()."module_avplayer_mediafile SET item_order=(item_order-1) WHERE item_order > ? AND parent=?";
			$db->Execute($query, array($item->item_order, $params["feadd_oldparent"]));
			// GET NEW ITEM ORDER
			$item->item_order = $this->countsomething("mediafile","id",array("parent"=>$item->parent)) + 1;
			$query = "UPDATE ".cms_db_prefix()."module_avplayer_mediafile SET item_order=? WHERE id=?";
			$db->Execute($query, array($item->item_order, $item->id));

		}else{

			// UPDATE THE ORDER OF THE ITEMS WITH THE OLD PARENT
			$query = "UPDATE ".cms_db_prefix()."module_avplayer_mediafile SET item_order=(item_order-1) WHERE item_order > ? AND parent=?";
			$db->Execute($query, array($item->item_order, $params["feadd_oldparent"]));
			// UPDATE NEW PARENT
			$query = "UPDATE ".cms_db_prefix()."module_avplayer_mediafile SET item_order=(item_order+1) WHERE parent=?";
			$db->Execute($query, array($item->parent));
			// GET NEW ITEM ORDER
			$item->item_order = 1;
			$query = "UPDATE ".cms_db_prefix()."module_avplayer_mediafile SET item_order=? WHERE id=?";
			$db->Execute($query, array($item->item_order, $item->id));
		
		}
	}


		if(isset($item->id) && isset($_FILES) && isset($_FILES[$id."fefile_flvfile"]) && $_FILES[$id."fefile_flvfile"]["name"] != ""){
			if($filemaxsize && $filemaxsize > 0 && $_FILES[$id."fefile_flvfile"]["size"] > $filemaxsize){
				echo '<div class="feadd_form_message">'.$this->Lang("error_filetoobig")."</div>";
			}else{
				$extension = strtolower(substr(strrchr($_FILES[$id."fefile_flvfile"]["name"], "."),1));
				$allowedext = array("flv");
				if( !$allowedext || in_array($extension, $allowedext) ){
					if( $filepath = $this->plUploadFile($_FILES[$id."fefile_flvfile"], "media", false, false) ){
						$this->plAssignFile($filepath, "avplayer_mediafile", $item->id, "flvfile", false, false);
					}
				}
			}
		}
		if(isset($item->id) && isset($_FILES) && isset($_FILES[$id."fefile_mp4file"]) && $_FILES[$id."fefile_mp4file"]["name"] != ""){
			if($filemaxsize && $filemaxsize > 0 && $_FILES[$id."fefile_mp4file"]["size"] > $filemaxsize){
				echo '<div class="feadd_form_message">'.$this->Lang("error_filetoobig")."</div>";
			}else{
				$extension = strtolower(substr(strrchr($_FILES[$id."fefile_mp4file"]["name"], "."),1));
				$allowedext = array("mp4");
				if( !$allowedext || in_array($extension, $allowedext) ){
					if( $filepath = $this->plUploadFile($_FILES[$id."fefile_mp4file"], "media", false, false) ){
						$this->plAssignFile($filepath, "avplayer_mediafile", $item->id, "mp4file", false, false);
					}
				}
			}
		}
		if(isset($item->id) && isset($_FILES) && isset($_FILES[$id."fefile_webmfile"]) && $_FILES[$id."fefile_webmfile"]["name"] != ""){
			if($filemaxsize && $filemaxsize > 0 && $_FILES[$id."fefile_webmfile"]["size"] > $filemaxsize){
				echo '<div class="feadd_form_message">'.$this->Lang("error_filetoobig")."</div>";
			}else{
				$extension = strtolower(substr(strrchr($_FILES[$id."fefile_webmfile"]["name"], "."),1));
				$allowedext = array("webm");
				if( !$allowedext || in_array($extension, $allowedext) ){
					if( $filepath = $this->plUploadFile($_FILES[$id."fefile_webmfile"], "media", false, false) ){
						$this->plAssignFile($filepath, "avplayer_mediafile", $item->id, "webmfile", false, false);
					}
				}
			}
		}
		if(isset($item->id) && isset($_FILES) && isset($_FILES[$id."fefile_ogvfile"]) && $_FILES[$id."fefile_ogvfile"]["name"] != ""){
			if($filemaxsize && $filemaxsize > 0 && $_FILES[$id."fefile_ogvfile"]["size"] > $filemaxsize){
				echo '<div class="feadd_form_message">'.$this->Lang("error_filetoobig")."</div>";
			}else{
				$extension = strtolower(substr(strrchr($_FILES[$id."fefile_ogvfile"]["name"], "."),1));
				$allowedext = array("ogv");
				if( !$allowedext || in_array($extension, $allowedext) ){
					if( $filepath = $this->plUploadFile($_FILES[$id."fefile_ogvfile"], "media", false, false) ){
						$this->plAssignFile($filepath, "avplayer_mediafile", $item->id, "ogvfile", false, false);
					}
				}
			}
		}
		if(isset($item->id) && isset($_FILES) && isset($_FILES[$id."fefile_poster"]) && $_FILES[$id."fefile_poster"]["name"] != ""){
			if($filemaxsize && $filemaxsize > 0 && $_FILES[$id."fefile_poster"]["size"] > $filemaxsize){
				echo '<div class="feadd_form_message">'.$this->Lang("error_filetoobig")."</div>";
			}else{
				$extension = strtolower(substr(strrchr($_FILES[$id."fefile_poster"]["name"], "."),1));
				$allowedext = false;
				if( !$allowedext || in_array($extension, $allowedext) ){
					if( $filepath = $this->plUploadFile($_FILES[$id."fefile_poster"], "media", "", false) ){
						$this->plAssignFile($filepath, "avplayer_mediafile", $item->id, "poster", "", false);
					}
				}
			}
		}
		if(isset($item->id) && isset($_FILES) && isset($_FILES[$id."fefile_mp3file"]) && $_FILES[$id."fefile_mp3file"]["name"] != ""){
			if($filemaxsize && $filemaxsize > 0 && $_FILES[$id."fefile_mp3file"]["size"] > $filemaxsize){
				echo '<div class="feadd_form_message">'.$this->Lang("error_filetoobig")."</div>";
			}else{
				$extension = strtolower(substr(strrchr($_FILES[$id."fefile_mp3file"]["name"], "."),1));
				$allowedext = array("mp3");
				if( !$allowedext || in_array($extension, $allowedext) ){
					if( $filepath = $this->plUploadFile($_FILES[$id."fefile_mp3file"], "media", false, false) ){
						$this->plAssignFile($filepath, "avplayer_mediafile", $item->id, "mp3file", false, false);
					}
				}
			}
		}
		

		if($db->Affected_Rows()){
			if($this->GetPreference("searchmodule_index_mediafile",false)){
				// IF ANYTHING WAS MODIFIED, WE MUST UPDATE THE SEARCH INDEX AND SEND AN EVENT...
				if(isset($event))	$this->SendEvent($event, array("what"=>"mediafile", "itemid" => $item->id, "alias"=>$item->alias));
				$module =& $this->GetModuleInstance("Search");
				if($module) {
					debug_buffer("SEARHC INDEX WAS UPDATED ".__LINE__);
					$text = "$item->name";
					$module->AddWords($this->GetName(), $item->id, "mediafile", $text, NULL);
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
	$items = $this->get_level_mediafile(array("id"=>$item->id));
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
$this->smarty->assign("flvfile_label", $this->Lang("mediafile_flvfile"));
$this->smarty->assign("flvfile_input", ((isset($item->flvfile) && $item->flvfile)?$item->flvfile->pic.'<a href="'.$item->flvfile->url.'" >'.$item->flvfile->filepath."</a> (".$item->flvfile->size_wformat.")<br/>":"").($allowfiles?$this->CreateFileUploadInput($id,"fefile_flvfile"):""));
$this->smarty->assign("mp4file_label", $this->Lang("mediafile_mp4file"));
$this->smarty->assign("mp4file_input", ((isset($item->mp4file) && $item->mp4file)?$item->mp4file->pic.'<a href="'.$item->mp4file->url.'" >'.$item->mp4file->filepath."</a> (".$item->mp4file->size_wformat.")<br/>":"").($allowfiles?$this->CreateFileUploadInput($id,"fefile_mp4file"):""));
$this->smarty->assign("webmfile_label", $this->Lang("mediafile_webmfile"));
$this->smarty->assign("webmfile_input", ((isset($item->webmfile) && $item->webmfile)?$item->webmfile->pic.'<a href="'.$item->webmfile->url.'" >'.$item->webmfile->filepath."</a> (".$item->webmfile->size_wformat.")<br/>":"").($allowfiles?$this->CreateFileUploadInput($id,"fefile_webmfile"):""));
$this->smarty->assign("ogvfile_label", $this->Lang("mediafile_ogvfile"));
$this->smarty->assign("ogvfile_input", ((isset($item->ogvfile) && $item->ogvfile)?$item->ogvfile->pic.'<a href="'.$item->ogvfile->url.'" >'.$item->ogvfile->filepath."</a> (".$item->ogvfile->size_wformat.")<br/>":"").($allowfiles?$this->CreateFileUploadInput($id,"fefile_ogvfile"):""));
$this->smarty->assign("poster_label", $this->Lang("mediafile_poster"));
$this->smarty->assign("poster_input", ((isset($item->poster) && $item->poster)?'<img src="'.$item->poster->url.'" /><br/>':"").($allowfiles?$this->CreateFileUploadInput($id,"fefile_poster"):""));
$this->smarty->assign("mp3file_label", $this->Lang("mediafile_mp3file"));
$this->smarty->assign("mp3file_input", ((isset($item->mp3file) && $item->mp3file)?$item->mp3file->pic.'<a href="'.$item->mp3file->url.'" >'.$item->mp3file->filepath."</a> (".$item->mp3file->size_wformat.")<br/>":"").($allowfiles?$this->CreateFileUploadInput($id,"fefile_mp3file"):""));
$this->smarty->assign("description_label", $this->Lang("mediafile_description"));
$this->smarty->assign("description_input", $this->CreateTextArea($wysiwyg,$id,$item->id?$item->description:"","feadd_description"));
$this->smarty->assign("parent_label", $this->Lang("player"));
$this->smarty->assign("parent_input", $this->CreateInputDropdown($id,"feadd_parent",$parentoptions,-1,$item->id?$item->parent:0));
$this->smarty->assign("itemalias",isset($item->alias)?"(alias : ".$item->alias.")":false);
$this->smarty->assign("alias_input", false);

$this->smarty->assign("edittitle", $this->Lang("edit_mediafile"));

$this->smarty->assign("submit", $this->CreateInputSubmit($id, "feaddsubmit", $this->Lang("submit")));
$this->smarty->assign("cancel", $this->CreateInputSubmit($id, "feaddcancel", $this->Lang("cancel")));
$this->smarty->assign("captcha_image", $captcha?$captcha->getCaptcha():false);
$this->smarty->assign("captcha_input", $captcha?$this->CreateInputText($id,"captcha_input","",30,10):false);
$this->smarty->assign("captcha_prompt", $captcha?$this->Lang("prompt_captcha"):false);


$this->smarty->assign("item", $item);


// DISPLAYING
	
echo $this->CreateFormStart($id, "FEaddB", $returnid, "post", "multipart/form-data");
echo $this->ProcessTemplate("frontend_add_mediafile.tpl");

if(isset($item) && isset($item->id)){
	echo $this->CreateInputHidden($id, "feadd_id", $item->id);
	if(isset($item->parent)) echo $this->CreateInputHidden($id, "feadd_oldparent", $item->parent);
	echo $this->CreateInputHidden($id, "feadd_item_order", $item->item_order);
}
echo $this->CreateFormEnd();

?>