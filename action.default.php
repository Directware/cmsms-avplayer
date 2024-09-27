<?php
if(!isset($gCms)) exit;

// we need to know which level is the final level
$levelarray = $this->get_levelarray();
$finallevel = "mediafile";

// we check what level we're watching... if none is specified, we use the final level
$what = (isset($params["what"]) && in_array($params["what"],$levelarray))?$params["what"]:$finallevel;
// we give it back to the params for pretty urls and page view
if(!isset($params["what"]) || $params["what"] == "")	$params["what"] = $what;
$parentlevel = $this->get_nextlevel($what, false);

// we retrieve some other parameters
if($what != $finallevel){
	$forcelist = true;		// Always display as list when we're not on the final level
}elseif(isset($params["alias"])){
	$forcelist = false;		// never display a list when the alias is specified
}else{
	// default value
	$forcelist = isset($params["forcelist"])?$params["forcelist"]:$this->GetPreference("force_list",false);
}
$orderby = isset($params["orderby"])?$params["orderby"]:false;
$inline = (isset($params["inline"]) && $params["inline"])?true:false;
$limit = isset($params["limit"])?$params["limit"]:0;
$limit = (int) $limit;
if(isset($params["nbperpage"]) && $params["nbperpage"] > $limit){
	$nbperpage = (int) $params["nbperpage"];
}else{
	// default pagination
	$default_nbperpage = $this->GetPreference($what."_pagination",0);
	$nbperpage = ($default_nbperpage > $limit)?$default_nbperpage:false;
	$params["nbperpage"] = $nbperpage;	// give it back for links
}
if($nbperpage)	$limit = (((isset($params["pageindex"])?$params["pageindex"]:1)-1) * $nbperpage).",".$nbperpage;

// we build the query. First, we check if we're using a saved query :
if(isset($params["query"])){
	$queryid = (int) $params["query"];
	if($query = $this->get_queries(array("id"=>$queryid))){
		// we retrieve the name of the function that will do the query
		$query = $query[0];
		$what = $query->what;
		$getfunction = "get_level_".$what;
		$parentlevel = $this->get_nextlevel($what, false);
		$whereclause = $query->whereclause;
		$wherevalues = $query->wherevalues;
		$customorder = ($query->queryorder == ""?false:$query->queryorder);
	}elseif($this->GetPreference("allow_sql",false)){
		$getfunction = "get_level_".$what;
		$parentlevel = $this->get_nextlevel($what, false);
		$whereclause = $params["query"]; // query should be sanitized...
		$wherevalues = array();
		$customorder = false;
		$itemlist = $this->$getfunction(array(), false, "", "", $orderby, $limit, $query);
	}else{
		$message = $this->Lang("error_wrongquery");
		if($queryid != $query && $msg = mysql_error())		$message .= "<br/>".$this->Lang("givenerror").$msg;
		echo $this->ShowErrors($message);
		return false;
	}
	if(!isset($params["alias"]) && $nbperpage){
		// we need pagination - so we first retrieve the total number of items fitting the query
		$total = $this->countsomething($what,"id",array(),$whereclause, $wherevalues, $parentlevel);
	}
	if(isset($total) && $total == 0){
		$itemlist = array();	// we already know there's nothing to query
	}else{
		$itemlist = $this->$getfunction(array(), false, "", "", $orderby, $limit, $whereclause, $wherevalues, $customorder);
	}
}else{
	// we're not using a saved query, so we parse the parameters
	// we retrieve the name of the function that will do the query
	$getfunction = "get_level_".$what;
	// The $where holds elements of the WHERE clause of the query, in the form field_name=>field_value
	if(isset($params["alias"]) && $params["alias"] != "") {
		$where = array("alias"=>$params["alias"]);
	}elseif(isset($params["showdefault"]) && $params["showdefault"]) {
		$where = array("isdefault"=>1);
	}else{
		$where = array();
	}
	if(isset($params["parent"]) && $params["parent"] != "") $where["parent"] = $params["parent"];

	$where["active"] = 1;
	if(!isset($params["alias"]) && $nbperpage){
		// we need pagination - so we first retrieve the total number of items fitting the query
		$total = $this->countsomething($what,"id",$where,false,array(),$parentlevel);
	}
	if(isset($total) && $total == 0){
		$itemlist = array();	// we already know there's nothing to query
	}else{
		$itemlist = $this->$getfunction($where, false, "", "", $orderby, $limit);
	}
}

// Integration would require some changes to CartMadeSimple
// $itemlist = $this->addCartUrls($itemlist,$id,$returnid);



//  START PROCESSING

if(count($itemlist) == 1 && !$forcelist){
	
	// ################# WE ARE DISPLAYING AN ITEM IN DETAIL VIEW
	
	$item=$itemlist[0];

	// if a template has been specified, we try to retrieve it	
	$template = false;
	if(isset($params["finaltemplate"]) && $params["finaltemplate"] != ""){
		$template = $this->GetTemplate($params["finaltemplate"]);
	}

	// if no template has been specified, we retrieve the default final template
	if(!$template || $template == ""){
		$templatename = $this->GetPreference("finaltemplate");
		$template = $this->GetTemplate($templatename, $this->GetName());
	}
	// we retrieve the parent tree:
	$parenttree = $this->get_objtree( $item->parent_id, $this->get_nextlevel($what, false) );
	$item->parent_object = $parenttree;

	
	// if the item has parents, we assign links to that parent
	if(isset($item->parent_alias)){
		$prettyurl = $this->BuildPrettyUrls(array("parent"=>$item->parent_alias, "what"=>$what), $returnid);
		$item->parentlink = $this->CreateLink($id, "default", $returnid, $item->parent_name, array("parent"=>$item->parent_alias), "", false, $inline, "", false, $prettyurl);
		$item->parenturl = $this->CreateLink($id, "default", $returnid, "", array("parent"=>$item->parent_alias), "", true, $inline, "", false, $prettyurl);
	}
	
	// we retrieve a label for each of the item's field and assign it to smarty
	$labels = new StdClass();
	foreach($item as $key=>$value){
		$labels->$key = $this->Lang($what."_".$key);		
	}
	$this->smarty->assign("labels", $labels);
	
	$this->smarty->assign("item",$item);
	$this->smarty->assign("leveltitle",$this->Lang($what."_plural"));

	if(!isset($params["query"]) && $this->GetPreference("load_nextprevious",false)){
		// we load links to next and previous items
		// as this implies two more queries, it must be activated in the settings tabs
		$newwhere = array("active"=>1);
		if(isset($params["parent"])){
			$newwhere["parent"] = $params["parent"];	
		}elseif(isset($item->parent_alias) && $this->getOrderType($what)){
			$newwhere["parent"] = $item->parent_alias;
		}
		$nextitem = false;
		$newwhere["item_order"] = $item->item_order + 1;
		$newparms = $params;
		$tmpitem = $this->$getfunction($newwhere);
		if(isset($tmpitem[0])){
			$newparms["alias"] = $tmpitem[0]->alias;
			$nextitem = $this->addfrontendurls($tmpitem[0],$newparms,$id,$returnid);
		}
		$previtem = false;
		if($item->item_order > 1){
			$newwhere["item_order"] = $item->item_order - 1;
			$tmpitem = $this->$getfunction($newwhere);
			if(isset($tmpitem[0])){
				$newparms["alias"] = $tmpitem[0]->alias;
				$previtem = $this->addfrontendurls($tmpitem[0],$newparms,$id,$returnid);
			}
		}
		$this->smarty->assign("next_item",$nextitem);
		$this->smarty->assign("previous_item",$previtem);
	}
	
	// we process the template
	echo $this->ProcessTemplateFromData($template);
	
}elseif(count($itemlist) > 0){
	
	// ################# WE ARE DISPLAYING A LIST VIEW
	$parentobj = false;
	// if we are watching items from a specific parents, we want to have the informations of this parent available
	// in the template (for example, if we want to display a category page, we might want to show the category description)
	// if it is the case, we retrieve the parent and give it to smarty
	if(isset($params["parent"]) && $params["parent"] != ""){
		$parentobj = $this->get_objtree($params["parent"], $this->get_nextlevel($what,false), "alias");
	}
	$this->smarty->assign("parentobj",$parentobj);
	
	$selectedalias = false;
	// we check if the current page has other instances of the module which are in action
	$glob = $this->get_moduleGetVars();
	if($what == $finallevel && isset($glob["alias"])){
		$selectedalias = $glob["alias"];
	}elseif(isset($glob["parent"]) && isset($glob["what"]) && $glob["what"] == $this->get_nextlevel($what)){
		$selectedalias = $glob["parent"];
	}
	if(isset($params["nbperpage"]) && isset($glob["pageindex"]) && !isset($params["pageindex"])) $params["pageindex"] = $glob["pageindex"];
	if(!isset($this->plcurrent[$what]))	$this->buildGlobalTree();
	if(isset($this->plcurrent[$what]))	$selectedalias = $this->plcurrent[$what];
	if(isset($params["nbperpage"]) && !isset($params["pageindex"])) $params["pageindex"] = $this->currentpageindex;
	$linkreturnid = (isset($params["detailpage"]))?$this->get_pageid($params["detailpage"]):$returnid;

	// we retrieve the template
	if(isset($params["listtemplate"]) && $params["listtemplate"] != "" && $customtpl = $this->GetTemplate($params["listtemplate"], $this->GetName())){
		$template = $customtpl;
	}else{
		$templatename = $this->GetPreference("listtemplate_".$what);
		$template = $this->GetTemplate($templatename, $this->GetName());
	}

	// if RANDOM option is set, we randomly selected only a number of the items retrieved
	if( isset($params["random"]) && $params["random"] > 0 && $params["random"] < count($itemlist) ){
		$newlist = array();
		$i = 1;
		$selected = array();
		$currand = rand(0,(count($itemlist)-1));
		while($i <= $params["random"]){
			while(in_array($currand, $selected) && count($selected) < count($itemlist))	$currand = rand(0,(count($itemlist)-1));
			$newlist[] = $itemlist[$currand];
			$selected[] = $currand;
			$i++;
		}
		$itemlist = $newlist;
	}
		
	// final processing - we create the detaillinks for each item
	$newlist = array();
	foreach($itemlist as $item){
		$item = $this->addfrontendurls($item,$params,$id,$linkreturnid);
		$item->is_selected = ($item->alias == $selectedalias)?true:false;
		array_push($newlist, $item);
	}
	$itemlist = $newlist;
	
	$this->paginate($what,$total,$id,$returnid,$params);
	
	// we give everything to smarty and process the template
	$this->smarty->assign("itemlist",$itemlist);
	$this->smarty->assign("leveltitle",$this->Lang($what."_plural"));
	$this->smarty->assign("itemcount",count($itemlist));
	echo $this->ProcessTemplateFromData($template);
	
}else{
	
	// ################# WE AREN'T DISPLAYING ANYTHING AT ALL
	$this->smarty->assign("error_msg",(isset($params["alias"])?$this->Lang("error_notfound"):$this->Lang("error_noitemfound")));
	$parentobj = false;
	if(isset($params["parent"]) && $parentlevel){
		$tmpfunction = "get_level_".$parentlevel;
		$parentobj = $this->$tmpfunction(array("alias"=>$params["parent"]));
		$parentobj = isset($parentobj[0])?$parentobj[0]:false;
	}
	$this->smarty->assign("parentobj",$parentobj);
	
	$template = $this->GetPreference("emptytemplate","**");
	if($template == "**"){
		// we use the list template
		if(isset($params["listtemplate"]) && $params["listtemplate"] != "" && $customtpl = $this->GetTemplate($params["listtemplate"], $this->GetName())){
			$template = $customtpl;
		}else{
			$templatename = $this->GetPreference("listtemplate_".$what);
			$template = $this->GetTemplate($templatename, $this->GetName());
		}
		$this->smarty->assign("itemlist",$itemlist);
		$this->smarty->assign("leveltitle",$this->Lang($what."_plural"));
		$this->smarty->assign("itemcount",0);
		$this->smarty->assign(array("page_showing"=>false,"page_totalitems"=>false,"page_pagenumbers"=>false,"page_next"=>false,"page_previous"=>false));
		echo $this->ProcessTemplateFromData($template);
	}else{
		// we use the noresult template
		echo $this->ProcessTemplateFromDatabase($template);
	}

}

?>
