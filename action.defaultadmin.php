<?php
if (!isset($gCms)) exit;
$admintheme = cms_utils::get_theme_object();
$active_tab = isset($params["active_tab"])?$params["active_tab"]:"player";

$has_advanced_perm = $this->CheckPermission("avplayer_advanced");

$filter = $this->GetPreference("display_filter",false);
$instantsearch = $this->GetPreference("display_instantsearch",false);
$instantsort = $this->GetPreference("display_instantsort",false);
$adminpages = $this->GetPreference("adminpages", 20);
$use_hierarchy = $this->GetPreference("use_hierarchy",false);
$use_session = $this->GetPreference("use_session",true);

if($instantsort || $instantsearch){
	echo '
	<script type="text/javascript">
';
	if($instantsearch)	echo $this->getFileContent("instantsearch.js");
	if($instantsort)	echo $this->getFileContent("instantsort.js");
	echo "
	</script>
";
}

echo $this->StartTabHeaders();
	if( $has_advanced_perm || $this->GetPreference("tabdisplay_player",false) || $this->CheckPermission("avplayer_manage_player") ) {
		echo $this->SetTabHeader("player", $this->Lang("player_plural"), "player" == $active_tab ? true : false);
	}
	if( $has_advanced_perm || $this->GetPreference("tabdisplay_mediafile",false) || $this->CheckPermission("avplayer_manage_mediafile") ) {
		echo $this->SetTabHeader("mediafile", $this->Lang("mediafile_plural"), "mediafile" == $active_tab ? true : false);
	}
	if( $has_advanced_perm || $this->GetPreference("tabdisplay_fieldoptions",false) ) {
		echo $this->SetTabHeader("fieldoptions", $this->Lang("fieldoptions"), "fieldoptions" == $active_tab ? true : false);		
	}
	if( $has_advanced_perm || $this->CheckPermission("Modify Templates") || $this->GetPreference("tabdisplay_templates",false) ) {
		echo $this->SetTabHeader("templates", $this->Lang("templates"), "templates" == $active_tab ? true : false);		
	}
	if( $has_advanced_perm || $this->GetPreference("tabdisplay_queries",false) ) {
		echo $this->SetTabHeader("queries", $this->Lang("queries"), "queries" == $active_tab ? true : false);		
	}
	if( $has_advanced_perm ) {
		echo $this->SetTabHeader("preferences", $this->Lang("preferences"), "preferences" == $active_tab ? true : false);		
	}
echo $this->EndTabHeaders();


echo $this->StartTabContent();

if( $has_advanced_perm || $this->GetPreference("tabdisplay_player",false) || $this->CheckPermission("avplayer_manage_player") ) {
	echo $this->StartTab("player");

		$whereclause = array();
		$filteroutput = false;

		$this->smarty->assign("filter", $filteroutput);
		$this->smarty->assign("instantsearch", $instantsearch?$this->Lang("searchthistable")." ".$this->CreateInputText($id, "searchtable_player", "", 10, 64, ' onkeyup="ctlmm_search(this.value,\'player_table\');"'):false);
			
		$this->smarty->assign("addnew", $this->CreateLink($id, "editA", $returnid, $admintheme->DisplayImage("icons/system/newobject.gif", "","","","systemicon")." ".$this->Lang("add_player")));
		$reorder_btn = false;if($has_advanced_perm || !$this->GetPreference("restrict_permissions",false) || $this->CheckPermission("avplayer_manage_player"))	$reorder_btn = $this->CreateLink($id, "reorder", $returnid, $admintheme->DisplayImage("icons/system/reorder.gif", "","","","systemicon")." ".$this->Lang("reorder"));
			
		$this->smarty->assign("reorder", $reorder_btn);
		$limit = false;
		if($adminpages){
			$limit = ((isset($params["player_page"])?$params["player_page"]:0) * $adminpages).",".$adminpages;
		}
		
			$itemlist = $this->get_level_player(isset($whereclause)?$whereclause:array(),true, $id, $returnid, false, $limit);
			$this->smarty->assign("tableid", "player_table");
			$this->smarty->assign("itemlist", $itemlist);
			$adminshow = array(
				array($this->Lang("name"),"editlink",false),
                                array($this->Lang("insert_tag"),"insertTag",true),
				array($this->Lang("active"),"toggleactive",true),
				array($this->Lang("reorder"),"movelinks",true),
				array($this->Lang("alias"),"alias",false),
				array($this->Lang("Actions"),"deletelink",true)		
				);
			if($instantsort && count($itemlist)>1){
				$i = 0;
				while($i<count($adminshow)){
					if(!$adminshow[$i][2])	$adminshow[$i][0] = '<div style="float:left;"><a style="cursor: pointer;" onclick="ctlmm_sortRows(\'player_table\','.$i.');"><img src="themes/default/images/icons/system/sort_up.gif" alt="^"/></a><br/><a style="cursor: pointer;" onclick="ctlmm_sortRows(\'player_table\','.$i.',true);"><img src="themes/default/images/icons/system/sort_down.gif" alt="v"/></a></div><div style="line-height: 24px;"> &nbsp;'.$adminshow[$i][0]."</div>";
					$i++;
				}
			}
			list($pageinfo, $nextpage, $previouspage, $pages) = $this->admin_paginate("player",$adminpages,$id,$returnid,$params);
			$this->smarty->assign("nextpage", $nextpage);
			$this->smarty->assign("previouspage", $previouspage);
			$this->smarty->assign("pageinfo", $pageinfo);
			$this->smarty->assign("pages", $pages);
			$this->smarty->assign("adminshow", $adminshow);
			echo $this->ProcessTemplate("adminpanel.tpl");

	echo $this->EndTab();
}

if( $has_advanced_perm || $this->GetPreference("tabdisplay_mediafile",false) || $this->CheckPermission("avplayer_manage_mediafile") ) {
	echo $this->StartTab("mediafile");

	if($this->countsomething("player") > 0){

		$whereclause = array();
		$filteroutput = false;

			if($filter){
				$cur_filter = false;
				if(isset($params["mediafile_showonly"])){
					$cur_filter = $params["mediafile_showonly"];
					if($use_sessoin)	$_SESSION["ctlmm_filter_mediafile"] = $params["mediafile_showonly"];
				}elseif($use_session && isset($_SESSION["ctlmm_filter_mediafile"]) && $_SESSION["ctlmm_filter_mediafile"]){
					$cur_filter = $_SESSION["ctlmm_filter_mediafile"];
				}
				if($cur_filter && $cur_filter != ""){
					$filteroptions = array_flip($this->get_options("player"));
					$filteroutput = isset($filteroptions[$params["mediafile_showonly"]])?$this->Lang("showingonly").$filteroptions[$params["mediafile_showonly"]]." ":"";
					$filteroutput .= $this->CreateLink($id, "defaultadmin", $returnid, $this->Lang("showall"), array("active_tab" => "mediafile", "mediafile_showonly"=>false));
					$whereclause = array("parent_id"=>$params["mediafile_showonly"]);
				}else{					
					if($use_hierarchy){
						$filteroptions = $this->get_admin_hierarchyoptions("mediafile",true);
					}else{
						$filteroptions = $this->get_options("player");
					}
					$filteroutput = $this->CreateFormStart($id, "defaultadmin", $returnid);
					$filteroutput .= $this->Lang("filterby_player")." : ";
					$filteroutput .= $this->CreateInputDropdown($id, "mediafile_showonly", $filteroptions, -1);
					$filteroutput .= $this->CreateInputHidden($id, "active_tab", "mediafile");
					$filteroutput .= " ".$this->CreateInputSubmit($id, "submit", lang("submit"));
					$filteroutput .= $this->CreateFormEnd();
				}
			}
		$this->smarty->assign("filter", $filteroutput);
		$this->smarty->assign("instantsearch", $instantsearch?$this->Lang("searchthistable")." ".$this->CreateInputText($id, "searchtable_mediafile", "", 10, 64, ' onkeyup="ctlmm_search(this.value,\'mediafile_table\');"'):false);
			
		$this->smarty->assign("addnew", $this->CreateLink($id, "editB", $returnid, $admintheme->DisplayImage("icons/system/newobject.gif", "","","","systemicon")." ".$this->Lang("add_mediafile")));
		$reorder_btn = false;if($has_advanced_perm || !$this->GetPreference("restrict_permissions",false) || $this->CheckPermission("avplayer_manage_mediafile"))	$reorder_btn = $this->CreateLink($id, "reorder", $returnid, $admintheme->DisplayImage("icons/system/reorder.gif", "","","","systemicon")." ".$this->Lang("reorder"));
			
		$this->smarty->assign("reorder", $reorder_btn);
		$limit = false;
		if($adminpages){
			$limit = ((isset($params["mediafile_page"])?$params["mediafile_page"]:0) * $adminpages).",".$adminpages;
		}
		
			$itemlist = $this->get_level_mediafile(isset($whereclause)?$whereclause:array(),true, $id, $returnid, false, $limit);
			$this->smarty->assign("tableid", "mediafile_table");
			$this->smarty->assign("itemlist", $itemlist);
			$adminshow = array(
				array($this->Lang("name"),"editlink",false),
				array($this->Lang("alias"),"alias",false),
				array($this->Lang("active"),"toggleactive",true),
				array($this->Lang("isdefault"),"toggledefault",true),
				array($this->Lang("reorder"),"movelinks",true),
				array($this->Lang("Actions"),"deletelink",true)		
				);
			if($instantsort && count($itemlist)>1){
				$i = 0;
				while($i<count($adminshow)){
					if(!$adminshow[$i][2])	$adminshow[$i][0] = '<div style="float:left;"><a style="cursor: pointer;" onclick="ctlmm_sortRows(\'mediafile_table\','.$i.');"><img src="themes/default/images/icons/system/sort_up.gif" alt="^"/></a><br/><a style="cursor: pointer;" onclick="ctlmm_sortRows(\'mediafile_table\','.$i.',true);"><img src="themes/default/images/icons/system/sort_down.gif" alt="v"/></a></div><div style="line-height: 24px;"> &nbsp;'.$adminshow[$i][0]."</div>";
					$i++;
				}
			}
			list($pageinfo, $nextpage, $previouspage, $pages) = $this->admin_paginate("mediafile",$adminpages,$id,$returnid,$params);
			$this->smarty->assign("nextpage", $nextpage);
			$this->smarty->assign("previouspage", $previouspage);
			$this->smarty->assign("pageinfo", $pageinfo);
			$this->smarty->assign("pages", $pages);
			$this->smarty->assign("adminshow", $adminshow);
			echo $this->ProcessTemplate("adminpanel.tpl");

	}else{
		echo "<p>".$this->Lang("error_noparent")."</p>";
	}

	echo $this->EndTab();

}
	$this->smarty->assign(array("nextpage"=>false,"previouspage"=>false,"pageinfo"=>false,"pages"=>false));

if( $has_advanced_perm || $this->CheckPermission("Modify Templates") || $this->GetPreference("tabdisplay_fieldoptions",false) ) {
	echo $this->StartTab("fieldoptions");

	echo "<fieldset style=\"width: 600px;\"><legend><b>(player) userdefined1 :</b></legend><ul>";
	$options = $this->get_fieldoptions("player_userdefined1",true);
	echo '<table cellspacing="0" cellpadding="0" class="pagetable">';
	$rowclass = 1;
	foreach($options as $option){
		$newparams = array("field"=>"player_userdefined1", "optionid"=>$option->id, "currentorder"=>$option->item_order);
		echo "
		<tr class=\"row".$rowclass."\"><td>".$option->name."</td><td width=\"120\">";
		echo $this->CreateLink($id, "rename_optionvalue", $returnid, $admintheme->DisplayImage("icons/system/edit.gif", lang("edit"),"","","systemicon"), array_merge($newparams, array("optionname"=>$option->name)))." ";
		echo $this->CreateLink($id, "delete_optionvalue", $returnid, $admintheme->DisplayImage("icons/system/delete.gif",lang("delete"),"","","systemicon"), $newparams, $this->Lang("prompt_deleteoption"));
		echo $this->CreateLink($id, "move_optionvalue", $returnid, $admintheme->DisplayImage("icons/system/arrow-u.gif",lang("up"),"","","systemicon"), array_merge($newparams, array("move"=>"up")));
		echo $this->CreateLink($id, "move_optionvalue", $returnid, $admintheme->DisplayImage("icons/system/arrow-d.gif",lang("down"),"","","systemicon"), array_merge($newparams, array("move"=>"down")));
		echo "</td></tr>";
		$rowclass = $rowclass == 1?2:1;
	}
	echo "</table>";
	echo "<p>".$this->CreateFormStart($id, "add_optionvalue");
		echo "<p>".$this->Lang("addoption").": ".$this->CreateInputText($id, "optionname", "", 20, 64);
		echo $this->CreateInputHidden($id, "field", "player_userdefined1");
		echo " ".$this->CreateInputSubmit($id, "submit", lang("submit"))."</p>";
	echo $this->CreateFormEnd();
	echo "</p></fieldset><br/><br/>";
	echo $this->EndTab();
}

	$this->smarty->assign(array("filter"=>false,"reorder"=>false,"instantsearch"=>false,"tableid"=>false));

if( $has_advanced_perm || $this->CheckPermission("Modify Templates") || $this->GetPreference("tabdisplay_templates",false) ) {
	echo $this->StartTab("templates");
	
    echo "<fieldset style=\"width: 600px;\"><legend><b>".$this->Lang("defaulttemplates")."</b></legend>";
    echo $this->CreateFormStart($id, "changedeftemplates", $returnid);
    $templatelist = $this->ListTemplates($this->GetName());
    $deftpls = $this->getDefaultTemplates();
    $tploptions = array();
    $itemlist = array();
    foreach($templatelist as $onetpl){
	   $tploptions[$onetpl] = $onetpl;
	   $tpl = new stdClass();
	   $tpl->editlink = $this->CreateLink( $id, "editTemplate", $returnid, $onetpl, array("tplname"=>$onetpl) );
	   $tpl->deletelink = in_array($onetpl, $deftpls)?"":$this->CreateLink( $id, "deletetpl", $returnid, $admintheme->DisplayImage("icons/system/delete.gif", $this->Lang("delete"), "", "", "systemicon"), array("tplname"=>$onetpl) );
	   array_push($itemlist, $tpl);
    }

	   echo "	<div class=\"pageoverflow\">
			 <p class=\"pagetext\">".$this->Lang("deftemplatefor")." \"player\":</p>
			 <p class=\"pageinput\">".$this->CreateInputDropdown($id,"listtemplate_player",$tploptions,-1,$this->GetPreference("listtemplate_player"))."</p>
		</div>
    ";
	   echo "	<div class=\"pageoverflow\">
			 <p class=\"pagetext\">".$this->Lang("deftemplatefor")." \"mediafile\":</p>
			 <p class=\"pageinput\">".$this->CreateInputDropdown($id,"listtemplate_mediafile",$tploptions,-1,$this->GetPreference("listtemplate_mediafile"))."</p>
		</div>
    ";
    echo "	<div class=\"pageoverflow\">
			 <p class=\"pagetext\">".$this->Lang("defdetailtemplate").":</p>
			 <p class=\"pageinput\">".$this->CreateInputDropdown($id,"finaltemplate",$tploptions,-1,$this->GetPreference("finaltemplate"))."</p>
		</div>
	";
	$tploptions[$this->Lang("uselevellisttpl")] = "**";
	echo "	<div class=\"pageoverflow\">
			 <p class=\"pagetext\">".$this->Lang("defsearchresultstemplate").":</p>
			 <p class=\"pageinput\">".$this->CreateInputDropdown($id,"searchresultstemplate",$tploptions,-1,$this->GetPreference("searchresultstemplate","**"))."</p>
		</div>
	";
	echo "	<div class=\"pageoverflow\">
			 <p class=\"pagetext\">".$this->Lang("defemptytemplate").":</p>
			 <p class=\"pageinput\">".$this->CreateInputDropdown($id,"emptytemplate",$tploptions,-1,$this->GetPreference("emptytemplate","**"))."</p>
		</div>
    <p>".$this->CreateInputSubmit($id, "submit", lang("submit"))."</p>";
    echo $this->CreateFormEnd();

    echo "</fieldset><br/><br/>";
    $this->smarty->assign("itemlist", $itemlist);
	$this->smarty->assign("addnew", $this->CreateLink($id, "editTemplate", $returnid, $admintheme->DisplayImage("icons/system/newobject.gif", "","","","systemicon")." ".$this->Lang("addtemplate")));
    $adminshow = array(	array($this->Lang("template"), "editlink", false), array($this->Lang("Actions"), "deletelink", false)	);
    $this->smarty->assign("adminshow", $adminshow);
    echo $this->ProcessTemplate("adminpanel.tpl");
	echo $this->EndTab();
}

if( $has_advanced_perm || $this->GetPreference("tabdisplay_queries",false) ) {
	echo $this->StartTab("queries");

		$itemlist = $this->get_queries($where=array(), true, $id, $returnid);
		$this->smarty->assign("itemlist", $itemlist?$itemlist:array());
		$adminshow = array( array("id","id"),
							array($this->Lang("name"), "name"),
							array($this->Lang("Actions"), "actions")
							);
		$this->smarty->assign("adminshow", $adminshow);
		$this->smarty->assign("addnew", $this->CreateLink($id, "adminquery", $returnid, $admintheme->DisplayImage("icons/system/newobject.gif", "","","","systemicon")." ".$this->Lang("createquery")));
		echo $this->ProcessTemplate("adminpanel.tpl");
		
	echo $this->EndTab();
}

if( $has_advanced_perm ) {
	echo $this->StartTab("preferences");
	echo $this->CreateFormStart($id, "changepreferences", $returnid);
	echo "<fieldset style=\"width: 465px;\"><legend><b>".$this->Lang("pref_tabdisplay").":</b></legend><ul>
	";
	$tabdisplay = array("player","mediafile","fieldoptions","templates","queries");
	foreach($tabdisplay as $onepref){
		echo "<li>".$this->CreateInputCheckbox($id, "tabdisplay_".$onepref, true, $this->GetPreference("tabdisplay_".$onepref,false)).$this->Lang($onepref)."</li>
	";
	}
	echo "</ul><br/><p>".$this->Lang("help_tabdisplay")."</p>
</fieldset><br/>
";
	echo "<fieldset style=\"width: 465px;\"><legend><b>".$this->Lang("pref_searchmodule_index").":</b></legend><ul>
	";
	foreach($this->get_levelarray() as $onepref){
		echo "<li>".$this->CreateInputCheckbox($id, "searchmodule_index_".$onepref, true, $this->GetPreference("searchmodule_index_".$onepref,false)).$this->Lang($onepref)."</li>
	";
	}
	echo "</ul><br/><p>".$this->Lang("help_searchmodule_index")."</p>
</fieldset><br/>
";
	echo "<fieldset style=\"width: 465px;\"><legend><b>".$this->Lang("pref_newitemsfirst").":</b></legend><ul>
	";
	foreach($this->get_levelarray() as $onepref){
		echo "<li>".$this->CreateInputCheckbox($id, "newitemsfirst_".$onepref, true, $this->GetPreference("newitemsfirst_".$onepref,false)).$this->Lang($onepref)."</li>
	";
	}
	echo "</ul><br/><p>".$this->Lang("help_newitemsfirst")."</p>
</fieldset><br/>
";

	echo "<fieldset style=\"width: 465px;\"><legend><b>".$this->Lang("pref_levelpagination").":</b></legend><ul>
	";
	foreach($this->get_levelarray() as $onepref){
		echo "<li>".$this->Lang($onepref).": ";
		echo $this->CreateInputText($id, $onepref."_pagination", $this->GetPreference($onepref."_pagination",0), 12, 3)."</li>
	";
	}
	echo "</ul><br/><p>".$this->Lang("help_levelpagination")."</p>
</fieldset><br/>
";

	echo "<fieldset style=\"width: 600px;\"><legend><b>".$this->Lang("pref_frontend").":</b></legend>
	";
	$checkboxprefs = array("fe_wysiwyg","fe_decodeentities","fe_allowfiles","fe_allownamechange","fe_allowaddnew","fe_usecaptcha");
	foreach($checkboxprefs as $onepref){
		echo "<p>".$this->CreateInputCheckbox($id, $onepref, true, $this->GetPreference($onepref,false)).$this->Lang("pref_".$onepref)."</p>
	";
	}
	echo "<p>".$this->Lang("pref_fe_maxfilesize").":".$this->CreateInputText($id, "fe_maxfilesize", $this->GetPreference("fe_maxfilesize",""), 12, 12)."</p>
	";
	$cntoperations = $gCms->getContentOperations();
	$cur_redirect = $this->GetPreference("fe_aftersubmit",-1);
	echo "<p>".$this->Lang("pref_fe_aftersubmit").$cntoperations->CreateHierarchyDropdown("",$cur_redirect,$id."fe_aftersubmit")."</p>
	";
	echo "<br/><p>".$this->Lang("help_frontend")."</p>
</fieldset><br/>
";

	echo "<fieldset style=\"width: 600px;\"><legend><b>".$this->Lang("preferences").":</b></legend>
	";
	$checkboxprefs = array("load_nbchildren","load_nextprevious","restrict_permissions","use_hierarchy","orderbyname","display_filter","display_instantsearch","display_instantsort","editable_aliases","autoincrement_alias","allow_sql","force_list","showthumbnails","delete_files","allow_complex_order","use_session");
	foreach($checkboxprefs as $onepref){
		echo "<p>".$this->CreateInputCheckbox($id, $onepref, true, $this->GetPreference($onepref,false))." ".$this->Lang("pref_".$onepref)."</p>
	";
	}
	echo "<p>".$this->Lang("pref_adminpages").$this->CreateInputText($id, "adminpages", $this->GetPreference("adminpages",0), 12, 3)."</p>
	";
	echo "<p>".$this->Lang("pref_maxshownpages").$this->CreateInputText($id, "maxshownpages", $this->GetPreference("maxshownpages",7), 12, 2)."</p>
	";
	echo "</fieldset><br/><p>".$this->CreateInputSubmit($id, "submit", lang("submit"))."</p>";
    echo $this->CreateFormEnd();
	echo "<br/><p>".$admintheme->DisplayImage("icons/system/import.gif", "","","","systemicon")." ";
	echo $this->CreateLink($id, "export", $returnid, $this->Lang("export_title"))." | ".$this->CreateLink($id, "import", $returnid, $this->Lang("import_title"))."</p>";
	echo $this->EndTab();
}

echo $this->EndTabContent();
?>