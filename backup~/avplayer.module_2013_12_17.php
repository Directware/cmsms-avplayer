<?php
#-------------------------------------------------------------------------
# Module: avplayer
# Version: 1.6, 
#
#-------------------------------------------------------------------------
# CMS - CMS Made Simple is (c) 2008 by Ted Kulp (wishy@cmsmadesimple.org)
# This project"s homepage is: http://www.cmsmadesimple.org
#
# This module was created with CTLModuleMaker 1.8.9.3
# CTLModuleMaker was created by Pierre-Luc Germain and is released under GNU
# http://dev.cmsmadesimple.org/projects/ctlmodulemaker
#
#-------------------------------------------------------------------------
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
# Or read it online: http://www.gnu.org/licenses/licenses.html#GPL
#
#-------------------------------------------------------------------------

class avplayer extends CMSModule
{
	var $currenttree = false;
	var $currentpageindex = 1;
	var $plcurrent = array();

	function GetName()
	{
		return "avplayer";
	}

	/*---------------------------------------------------------
	   GetFriendlyName()
	   This can return any string, preferably a localized name
	   of the module. This is the name that"s shown in the
	   Admin Menus and section pages (if the module has an admin
	   component).
	   
	   See the note on localization at the top of this file.
	  ---------------------------------------------------------*/
	function GetFriendlyName()
	{
		return $this->Lang("friendlyname");
	}
	
	/*---------------------------------------------------------
	   GetVersion()
	   This can return any string, preferably a number or
	   something that makes sense for designating a version.
	   The CMS will use this to identify whether or not
	   the installed version of the module is current, and
	   the module will use it to figure out how to upgrade
	   itself if requested.	   
	  ---------------------------------------------------------*/
	function GetVersion()
	{
		return "1.6";
	}


	/*---------------------------------------------------------
	   GetDependencies()
	   Your module may need another module to already be installed
	   before you can install it.
	   This method returns a list of those dependencies and
	   minimum version numbers that this module requires.
	   
	   It should return an hash, eg.
	   return array("somemodule"=>"1.0", "othermodule"=>"1.1");
	  ---------------------------------------------------------*/
	function GetDependencies()
	{
		return array();
	}

	/*---------------------------------------------------------
	   GetHelp()
	   This returns HTML information on the module.
	   Typically, you"ll want to include information on how to
	   use the module.
	   
	   See the note on localization at the top of this file.
	  ---------------------------------------------------------*/
	function GetHelp()
	{
		return $this->Lang("help");
	}

	/*---------------------------------------------------------
	   GetAuthor()
	   This returns a string that is presented in the Module
	   Admin if you click on the "About" link.
	  ---------------------------------------------------------*/
	function GetAuthor()
	{
		return "CTLModuleMaker 1.8.9.3";
		// of course you may change this, but it would be nice
		// to keep a mention of the CTLModuleMaker somewhere
	}


	/*---------------------------------------------------------
	   GetAuthorEmail()
	   This returns a string that is presented in the Module
	   Admin if you click on the "About" link. It helps users
	   of your module get in touch with you to send bug reports,
	   questions, cases of beer, and/or large sums of money.
	  ---------------------------------------------------------*/
	function GetAuthorEmail()
	{
		return "Martin Woods";
	}


	/*---------------------------------------------------------
	   IsPluginModule()
	   This function returns true or false, depending upon
	   whether users can include the module in a page or
	   template using a smarty tag of the form
	   {cms_module module="Prod" param1=val param2=val...}
	   
	   If your module does not get included in pages or
	   templates, return "false" here.
	  ---------------------------------------------------------*/
	function IsPluginModule()
	{
		return true;
	}


	/*---------------------------------------------------------
	   HasAdmin()
	   This function returns a boolean value, depending on
	   whether your module adds anything to the Admin area of
	   the site. For the rest of these comments, I"ll be calling
	   the admin part of your module the "Admin Panel" for
	   want of a better term.
	  ---------------------------------------------------------*/
	function HasAdmin() {	return true;	}
	function GetAdminSection() {return "content";}
	function GetAdminDescription() {return $this->Lang("admindescription");}

	function VisibleToAdminUser(){
		return ($this->CheckPermission("avplayer_normaluser") || $this->CheckPermission("avplayer_advanced"));
	}
	
	/*---------------------------------------------------------
	   Module Constructor 
	---------------------------------------------------------*/
	function avplayer()
	{
		global $gCms;
    	$smarty =& $gCms->GetSmarty();
		$smarty->register_function("avplayer_breadcrumbs", array(&$this,"function_modbreadcrumbs"));
		$smarty->register_function("avplayer_get_levelitem", array(&$this,"function_get_levelitem"));
		parent::CMSModule();
	}
	
	/*---------------------------------------------------------
	   SetParameters()
	   This function enables you to create mappings for
	   your module when using "Pretty Urls".
	   
	   Typically, modules create internal links that have
	   big ugly strings along the lines of:
	   index.php?mact=ModName,cntnt01,actionName,0&cntnt01param1=1&cntnt01param2=2&cntnt01returnid=3
	   
	   You might prefer these to look like:
	   /ModuleFunction/2/3
	   
	   To do this, you have to register routes and map
	   your parameters in a way that the API will be able
	   to understand.

	   Also note that any calls to CreateLink will need to
	   be updated to pass the pretty url parameter.
	   
	   Since the Skeleton doesn"t really create any links,
	   the section below is commented out, but you can
	   use it to figure out pretty urls.
	   
	   ---------------------------------------------------------*/
	function SetParameters()
	{

		// these are for internal pretty URLS.
		// you may change these, but you will also need to change the BuildPrettyURLs function below accordingly
		// see FAQ for more info on this
		$defact = array("action"=>"default");
		$this->RegisterRoute("/[aA]vplayer\/([Qq]uery)\/(?P<query>[0-9]+)\/(?P<returnid>[0-9]+)$/", $defact);
		$this->RegisterRoute("/[aA]vplayer\/([Qq]uery)\/(?P<query>[0-9]+)\/(?P<pageindex>[0-9]+)\/(?P<nbperpage>[0-9]+)\/(?P<returnid>[0-9]+)$/", $defact);
		$this->RegisterRoute("/[aA]vplayer\/([Dd]etail)\/(?P<alias>[^\/]+)\/(?P<returnid>[0-9]+)$/", $defact);
		$this->RegisterRoute("/[aA]vplayer\/(?P<what>[^\/]+)\/(?P<returnid>[0-9]+)$/", $defact);
		$this->RegisterRoute("/[aA]vplayer\/(?P<what>[^\/]+)\/(?P<parent>[^\/]+)\/(?P<returnid>[0-9]+)$/", $defact);
		$this->RegisterRoute("/[aA]vplayer\/(?P<what>[^\/]+)\/(?P<pageindex>[0-9]+)\/(?P<nbperpage>[0-9]+)\/(?P<returnid>[0-9]+)$/", $defact);
		$this->RegisterRoute("/[aA]vplayer\/(?P<what>[^\/]+)\/(?P<parent>[^\/]+)\/(?P<pageindex>[0-9]+)\/(?P<nbperpage>[0-9]+)\/(?P<returnid>[0-9]+)$/", $defact);
	
		$this->RestrictUnknownParams();
		
		$this->CreateParameter("action", "default", $this->Lang("phelp_action"));
		$this->CreateParameter("what", "", $this->Lang("phelp_what"));
		$this->SetParameterType("what",CLEAN_STRING);
		$this->CreateParameter("alias", "", $this->Lang("phelp_alias"));
		$this->SetParameterType("alias",CLEAN_STRING);
		$this->CreateParameter("showdefault", false, $this->Lang("phelp_showdefault"));
		$this->SetParameterType("showdefault",CLEAN_INT);
		$this->CreateParameter("parent", "", $this->Lang("phelp_parent"));
		$this->SetParameterType("parent",CLEAN_STRING);
		$this->CreateParameter("limit", 0, $this->Lang("phelp_limit"));
		$this->SetParameterType("limit",CLEAN_INT);
		$this->CreateParameter("nbperpage", 0, $this->Lang("phelp_nbperpage"));
		$this->SetParameterType("nbperpage",CLEAN_STRING);
		$this->CreateParameter("orderby", 0, $this->Lang("phelp_orderby"));
		$this->SetParameterType("orderby",CLEAN_STRING);
		$this->CreateParameter("detailpage", "", $this->Lang("phelp_detailpage"));
		$this->SetParameterType("detailpage",CLEAN_STRING);
		$this->CreateParameter("random", 0, $this->Lang("phelp_random"));
		$this->SetParameterType("random",CLEAN_INT);
		$this->CreateParameter("listtemplate", "", $this->lang("phelp_listtemplate"));
		$this->SetParameterType("listtemplate",CLEAN_STRING);
		$this->CreateParameter("finaltemplate", "", $this->lang("phelp_finaltemplate"));
		$this->SetParameterType("finaltemplate",CLEAN_STRING);
		$this->CreateParameter("forcelist", "0", $this->lang("phelp_forcelist"));
		$this->SetParameterType("forcelist",CLEAN_STRING);
		$this->CreateParameter("inline", 0, $this->lang("phelp_inline"));
		$this->SetParameterType("inline",CLEAN_STRING);
		$this->CreateParameter("searchmode", "advanced", $this->lang("phelp_searchmode"));
		$this->SetParameterType("searchmode",CLEAN_STRING);
		$this->CreateParameter("query", 0, $this->lang("phelp_query"));
		$this->SetParameterType("query",CLEAN_STRING);
		$this->CreateParameter("toaction", "", $this->Lang("phelp_toaction"));
		$this->SetParameterType("toaction",CLEAN_STRING);
		$this->SetParameterType("pageindex",CLEAN_INT);

		// for the search form (trick from ikulis) :
		$this->SetParameterType(CLEAN_REGEXP."/date_.*/",CLEAN_STRING);
		$this->SetParameterType(CLEAN_REGEXP."/field_.*/",CLEAN_STRING);
		$this->SetParameterType(CLEAN_REGEXP."/compare_.*/",CLEAN_STRING);
		$this->SetParameterType("submitsearch",CLEAN_STRING);
		$this->SetParameterType("searchfield",CLEAN_STRING);
		
		// for the frontend add action
		$this->SetParameterType(CLEAN_REGEXP."/feadd.*/",CLEAN_STRING);
		$this->SetParameterType(CLEAN_REGEXP."/fefile.*/",CLEAN_STRING);
		$this->SetParameterType("captcha_input", CLEAN_STRING);
		
	}

    function GetEventDescription($eventname)
    {
		$eventname = str_replace("avplayer", "", $eventname);
		return $this->lang("eventdesc".$eventname);
    }
    
	function InstallPostMessage()
	{
		return $this->Lang("postinstall");
	}
	function UninstallPostMessage()
	{
		return $this->Lang("postuninstall");
	}
	function UninstallPreMessage()
	{
		return $this->Lang("really_uninstall");
	}


	/*---------------------------------------------------------
	   Install()
	   When your module is installed, you may need to do some
	   setup. Typical things that happen here are the creation
	   and prepopulation of database tables, database sequences,
	   permissions, preferences, etc.
	   	   
	   For information on the creation of database tables,
	   check out the ADODB Data Dictionary page at
	   http://phplens.com/lens/adodb/docs-datadict.htm
	   
	   This function can return a string in case of any error,
	   and CMS will not consider the module installed.
	   Successful installs should return FALSE or nothing at all.
	  ---------------------------------------------------------*/
	function Install()
	{
		global $gCms;
		require "method.install.php";
	}

	/*---------------------------------------------------------
	   Uninstall()
	   Sometimes, an exceptionally unenlightened or ignorant
	   admin will wish to uninstall your module. While it
	   would be best to lay into these idiots with a cluestick,
	   we will do the magnanimous thing and remove the module
	   and clean up the database, permissions, and preferences
	   that are specific to it.
	   This is the method where we do this.
	  ---------------------------------------------------------*/
	function Uninstall()
	{
		global $gCms;
		require "method.uninstall.php";
	}


    function SearchResult($returnid, $itemid, $level = "")
    {
		$result = array();
		$wantedparam = false;
		$newparams = array();
		if($level == "mediafile"){
			// we seek an element of the last level, and will display the detail view
			$wantedparam = "alias";
		}else{
			if($newparams["what"] = $this->get_nextlevel($level)){
			// we seek an element of another level, and will display the list view of its children
				$wantedparam = "parent";
			}
		}
		if ($wantedparam){
			$tablename = cms_db_prefix()."module_avplayer_".$level;
			$db =& $this->GetDb();
			$query = "SELECT name, alias FROM $tablename WHERE id = ?";
			$dbresult = $db->Execute( $query, array( $itemid ) );
			if ($dbresult){
				$row = $dbresult->FetchRow();
				$newparams[$wantedparam] = $row["alias"];

				//0 position is the prefix displayed in the list results.
				$result[0] = $this->GetFriendlyName();

				//1 position is the title
				$result[1] = $row["name"];
		
				//2 position is the URL to the title.
				$result[2] = $this->CreateLink($id, "default", $returnid, "", $newparams, "", true, false, "", false, $this->BuildPrettyUrls($newparams, $returnid));
			}
		}

		return $result;
	}
	
	function SearchReindex(&$module)
    {
		$db =& $this->GetDb();
		if($this->GetPreference("searchmodule_index_player",false)){
			$itemlist = $this->get_level_player();
			foreach($itemlist as $item){
				$text = "$item->name";
				$module->AddWords($this->GetName(), $item->id, "player", $text, NULL);
			}
		}
		if($this->GetPreference("searchmodule_index_mediafile",false)){
			$itemlist = $this->get_level_mediafile();
			foreach($itemlist as $item){
				$text = "$item->name";
				$module->AddWords($this->GetName(), $item->id, "mediafile", $text, NULL);
			}
		}
		
    }	

/* ---------------------------------------------
NOT PART OF THE NORMAL MODULE API
----------------------------------------------*/

    function getDefaultTemplates(){
    	// returns an array of the templates that are selected as default (just so that we don't delete them)
	   $result = array();
	   $result[] = $this->GetPreference("finaltemplate");
	   $result[] = $this->GetPreference("searchresultstemplate");
	   $result[] = $this->GetPreference("listtemplate_player");
	   $result[] = $this->GetPreference("listtemplate_mediafile");
	   return $result;
    }
	
    function getOrderType($what){
		// returns whether a level is ordered by parent or not
		$return = array("player"=>true,"mediafile"=>true);
		return (isset($return[$what])?$return[$what]:false);
    }

	function DoAction($action, $id, $params, $returnid=-1){
		global $gCms;
		
		switch($action){
			case "link":
				$toaction = isset($params["toaction"])?$params["toaction"]:"default";
				echo $this->CreateLink($id,$toaction,$returnid,"",$params,"",true);
				break;
			case "breadcrumbs":
				$smarty =& $gCms->GetSmarty();
				$this->function_modbreadcrumbs($params, $smarty);
				break;
			case "frontend_edit":
				$what = isset($params["what"])?$params["what"]:"mediafile";
				switch($what){
					case "player":
						require "action.FEaddA.php";
						break;
					case "mediafile":
						require "action.FEaddB.php";
						break;
					
				}
				break;
			case "changepreferences":
				if(!$this->CheckPermission("avplayer_advanced")){
					$this->Redirect($id, "defaultadmin", $returnid, array("module_message"=>$this->Lang("error_denied")));
					break;
				}
				$prefs = array("restrict_permissions","use_hierarchy","orderbyname","display_filter","display_instantsearch","display_instantsort","editable_aliases","autoincrement_alias","allow_sql","force_list","delete_files","tabdisplay_templates","tabdisplay_fieldoptions","tabdisplay_queries","showthumbnails","fe_wysiwyg","fe_decodeentities","fe_allowfiles","fe_allownamechange","fe_allowaddnew","fe_usecaptcha","allow_complex_order","load_nextprevious","load_nbchildren","use_session","retrievetree");
				foreach($this->get_levelarray() as $level){
					$prefs[] = "newitemsfirst_".$level;
					$prefs[] = "searchmodule_index_".$level;
					$prefs[] = "tabdisplay_".$level;
				}
				foreach($prefs as $pref)	$this->SetPreference($pref, isset($params[$pref]));
				if(isset($params["fe_aftersubmit"]))	$this->SetPreference("fe_aftersubmit", $params["fe_aftersubmit"]);
				if(isset($params["fe_maxfilesize"]))	$this->SetPreference("fe_maxfilesize", $params["fe_maxfilesize"]);
				$maxshownpages = (int)$params["maxshownpages"];
				$adminpages = (int)$params["adminpages"];
				foreach($this->get_levelarray() as $level){
					$nbperpage = (int) (isset($params[$level."_pagination"])?$params[$level."_pagination"]:0);
					$this->SetPreference($level."_pagination", $nbperpage);
				}
				$this->SetPreference("maxshownpages", $maxshownpages);
				$this->SetPreference("adminpages", $adminpages);
				$this->Redirect($id, "defaultadmin", $returnid, array("active_tab"=>"preferences", "module_message"=>$this->Lang("message_modified")));
				break;
			case "changedeftemplates":
				foreach($params as $key=>$value){
				    if($key != "submit")	   $this->setPreference($key, $value);
				}
				$this->Redirect($id, "defaultadmin", $returnid, array("active_tab"=>"templates", "module_message"=>$this->Lang("message_modified")));
				break;
			case "deletetpl":
				$newparams = array("active_tab"=>"templates");
				$deftemplates = $this->getDefaultTemplates();
			    if(isset($params["tplname"]) && !in_array($params["tplname"], $deftemplates)){
				    if($this->DeleteTemplate($params["tplname"]))	   $newparams["module_message>"] = $this->Lang("message_modified");
				}
				$this->Redirect($id, "defaultadmin", $returnid, $newparams);
				break;
			case "deletequery":
				$newparams = array("active_tab"=>"queries");
				if(isset($params["queryid"])){
					$db =& $this->GetDb();
					if($db->Execute("DELETE FROM ".cms_db_prefix()."module_avplayer_saved_queries WHERE id=? LIMIT 1", array($params["queryid"]))){
						$newparams["module_message"] = $this->Lang("message_deleted");
					}
				}
				$this->Redirect($id, "defaultadmin", $returnid, $newparams);
				break;
			case "testquery":
				if(isset($params["queryid"]) && $query = $this->get_queries(array("id"=>$params["queryid"]))){
					$query = $query[0];
					echo "<p><b>".$this->Lang("query").":</b> ".$query->name;
					if($query->order != "")	echo " ORDER BY ".$query->order;
					echo "</p>";
					$getfunction = "get_level_".$query->what;
					$itemlist = $this->$getfunction(array(), false, "", "", 0, 0, $query->whereclause, $query->wherevalues, ($query->queryorder == ""?false:$query->queryorder));
					echo "<p><b>".$this->Lang("results")." (".count($itemlist).") :</b></p>";
					echo "<ul>";
					foreach($itemlist as $item)	echo "<li>".$item->name."</li>";
					echo "</ul>";
				}
				echo "<p>".$this->CreateLink($id, "defaultadmin", $returnid, lang("back"), array("active_tab"=>"queries"))."</p>";
				break;
					

			case "add_optionvalue":
				if(isset($params["field"]) && isset($params["optionname"]) && $params["optionname"] != ""){
					$tablename = cms_db_prefix()."module_avplayer_fieldoptions";
					$db =& $this->GetDb();
					$itemid = $db->GenID($tablename."_seq");
					$itemorder = $this->countsomething("fieldoptions", "id", array("field"=>$params["field"])) + 1;
					$query = "INSERT INTO ".$tablename." SET id=?, field=?, name=?, item_order=?";
					$db->Execute($query,array($itemid,$params["field"],$params["optionname"],$itemorder));
				}
				$this->Redirect($id, "defaultadmin", $returnid, array("active_tab"=>"fieldoptions") );
				break;
			case "delete_optionvalue":
				if(isset($params["field"]) && isset($params["optionid"]) && $params["optionid"] > 0){
					$tablename = cms_db_prefix()."module_avplayer_fieldoptions";
					$db =& $this->GetDb();
					$query = "DELETE FROM $tablename WHERE id=? AND field=? LIMIT 1";
					if($db->Execute($query,array($params["optionid"], $params["field"]))){
						$query = "UPDATE $tablename SET item_order=(item_order-1) WHERE field=? AND item_order > ?";
						$db->Execute($query, array($params["field"], $params["currentorder"]));
					}
				}
				$this->Redirect($id, "defaultadmin", $returnid, array("active_tab"=>"fieldoptions") );
				break;
			case "move_optionvalue":
				$db =& $this->GetDb();
				$tablename = cms_db_prefix()."module_avplayer_fieldoptions";
				if(isset($params["field"]) && isset($params["optionid"]) && isset($params["move"]) && isset($params["currentorder"])){
					if($params["move"] == "up" && $params["currentorder"] != 1){
						$query = "UPDATE $tablename SET item_order=(item_order+1) WHERE field=? AND item_order = ? LIMIT 1;";
						$db->Execute($query, array($params["field"], $params["currentorder"] -1));
						$query = "UPDATE $tablename SET item_order=(item_order-1) WHERE field=? AND id = ? LIMIT 1;";
						$db->Execute($query, array($params["field"], $params["optionid"]));
					}elseif($params["move"] == "down"){
						$query = "UPDATE $tablename SET item_order=(item_order-1) WHERE field=? AND item_order = ? LIMIT 1;";
						if($db->Execute($query, array($params["field"], $params["currentorder"] +1))){
							$query = "UPDATE $tablename SET item_order=(item_order+1) WHERE field=? AND id = ? LIMIT 1;";
							$db->Execute($query, array($params["field"], $params["optionid"]));
						}
					}
				}
				$this->Redirect($id, "defaultadmin", $returnid, array("active_tab"=>"fieldoptions") );
				break;
			case "rename_optionvalue":
				if(!isset($params["cancel"]) && isset($params["optionid"]) && isset($params["field"]) && isset($params["optionname"]) ){
					if(isset($params["submit"]) && $params["optionname"] != "" ){
						$tablename = cms_db_prefix()."module_avplayer_fieldoptions";
						$db =& $this->GetDb();
						$query = "UPDATE $tablename SET name=? WHERE field=? AND id=?";
						$db->Execute($query,array($params["optionname"], $params["field"], $params["optionid"]));
						$this->Redirect($id, "defaultadmin", $returnid, array("active_tab"=>"fieldoptions") );
					}else{
						echo "<h2>".$this->Lang("modifyanoption")."</h2>";
						echo $this->CreateFormStart($id, "rename_optionvalue", $returnid);
						echo "<p>".$this->CreateInputText($id,"optionname",$params["optionname"],40,64)."</p>";
						echo "<p>".$this->CreateInputSubmit($id, "submit", lang("submit")).$this->CreateInputSubmit($id, "cancel", lang("cancel"))."</p>";
						echo $this->CreateInputHidden($id, "optionid", $params["optionid"]).$this->CreateInputHidden($id, "field", $params["field"]);
						echo $this->CreateFormEnd();
					}
				}else{
					$this->Redirect($id, "defaultadmin", $returnid, array("active_tab"=>"fieldoptions") );
				}
				break;
			case "default":
			default:
				parent::DoAction($action, $id, $params, $returnid);
				break;
		}


	}
	function plcreatealias($name){
		// transforms $name into a url-friendly alias
		
		// as a suggestion from AMT, the first part deals with smart quotes
 		$search = array(chr(0xe2) . chr(0x80) . chr(0x98),
						  chr(0xe2) . chr(0x80) . chr(0x99),
						  chr(0xe2) . chr(0x80) . chr(0x9c),
						  chr(0xe2) . chr(0x80) . chr(0x9d),
						  chr(0xe2) . chr(0x80) . chr(0x93),
						  chr(0xe2) . chr(0x80) . chr(0x94));
 		$name = str_replace($search, "", $name);
		
 		// the second part uses the cms version
 		$alias = munge_string_to_url($name, false);
 		return $alias;
	}

	function checkalias($dbtable, $alias, $itemid=false, $idfield="id", $aliasfield="alias"){
		// checks if this alias already exists in the level
		$query = "SELECT ".$idfield." FROM ".cms_db_prefix().$dbtable." WHERE ".$aliasfield." = ?";
		if($itemid) $query .= " AND ".$idfield."!=".$itemid;
		$db = $this->GetDb();
		$dbresult = $db->Execute($query,array($alias));
		$targetid = 0;
		if($dbresult && $row = $dbresult->FetchRow()) $targetid = $row["id"];
		return ($targetid == 0);
	}
	function BuildPrettyUrls($params, $returnid=-1){
		// transforms given params into a pretty url
		$prettyurl = $this->GetName()."/";
		if(isset($params["query"])){
			$prettyurl .= "query/".$params["query"];	
		}elseif(isset($params["alias"])){
			$prettyurl .= "detail/".$params["alias"];
		}elseif(isset($params["parent"])){
			$prettyurl .= $params["what"]."/".$params["parent"];
		}else{
			$prettyurl .= $params["what"];
		}
		if(!isset($params["alias"]) && isset($params["pageindex"]) && isset($params["nbperpage"]))	$prettyurl .= "/".$params["pageindex"]."/".$params["nbperpage"];
		$prettyurl .= "/".$returnid;
		return $prettyurl;
	}
	function DoCheckboxes($id, $name, $choices, $selected=array(), $delimiter="<br/>"){
		// pretty much like CreateInputRadioGroup, but using checkboxes
		if(!is_array($selected))	$selected = array();
		$output = "";
		foreach($choices as $key=>$value){
			$output .= $this->CreateInputCheckbox($id, $name."[]", $value, (in_array($value, $selected)?$value:0))." ".$key.$delimiter;
		}
		return $output;
	}
	function parsekeywords($string){
		// cuts the searchwords of the search form into pieces
		// bascially, this explodes the $string, but takes notice of the quotes
		
		$inside = (substr($string,0,1) == '"');
		$parts = explode('"',$string);
	
		if(count($parts) < 2){
			if($inside){
				return array(str_replace('"',"",$string));
			}else{
				return explode(" ",$string);
			}
		}
	
		$keywords = array();
		foreach($parts as $part){
			if($part != ""){
				if($inside){
					$keywords[] = $part;
				}else{
					$words = explode(" ",trim($part));
					foreach($words as $word){
						if(trim($word) != "")	$keywords[] = $word;
					}
				}
				$inside = !$inside;
			}
		}
		return $keywords;
	}


	function feadd_permcheck($what, $itemid, $alias){
		global $gCms;
		$return = true;
		require "function.feadd_permcheck.php";
		return $return;
	}

	function get_levelarray(){
		// returns an array of the levels (top to bottom)
		return array("player","mediafile");
	}

	function get_modulehierarchy($level=false){
		$hierarchy = array();
			$hierarchy[1] = "player";
			$hierarchy[2] = "mediafile";
		return $hierarchy;
	}
	
	function get_levelsearchfields($level){
		// returns the field of a level which are searchable (for the simple search action)
		$fields = array();
		switch($level){
			case "player":
				$fields = array("location","width","height","parameters","name");
				break;
			case "mediafile":
				$fields = array("description","name");
				break;
						
		}
		return $fields;
	}
	function get_hierarchyoptions($end=false, $withemptyrow=true, $depthsymbol="&nbsp;-&nbsp;"){
		// returns an array of parent options for a linked to a selected level
		// used for the hierarchy dropdown list in search action
		$levelarray = $this->get_levelarray();
		 if(!in_array($end, $levelarray))	$end = $levelarray[count($levelarray)-1];
		
		// building the joined query
		$tables = "";
		$fields = "";
		$orderby = "";
		$where = "";
		$finished = false;
		$i = 1;
		foreach($levelarray as $level){
			if($level == $end)	$finished = true;
			if(!$finished){
				$tables .= ($tables == ""?"":", ").cms_db_prefix()."module_".$this->GetName()."_".$level." t".$i." ";
				$fields .= ($fields == ""?"":", ")."t".$i.".id id".$i.", t".$i.".name name".$i;
				$orderby .= ($orderby == ""?"":", ")."t".$i.".item_order DESC";
				if($i > 1) $where .= " AND t".($i-1).".id = t".$i.".parent ";
				$where .= ($where == ""?"":" AND ")." t".$i.".active=1";
				$i++;
			}
		}
		if($fields == "")	return false;
		
		$db =& $this->GetDb();
		$query = "SELECT ".$fields." FROM ".$tables." WHERE ".$where." ORDER BY ".$orderby;
		$dbresult = $db->Execute($query);

		// parsing results
		$current = array();
		$tmplastlevel = array();
		$results = array();
		$final = $i -1;
		while($j < $final ){
			$current[$j] = array("id"=>false,"name"=>"");
			$j++;
		}
		while($dbresult && $row = $dbresult->FetchRow()){
			// each row has a full hierarchy, from top parent to final child
			$j = $final;
			while($j > 0 ){
				if($row["id".$j] != $current[$j]["id"]){
					if($current[$j]["id"] && count($current[$j]["res"]) > 0){
						$key = str_repeat($depthsymbol, ($j -1)).$current[$j]["name"];
						$results[$key] = implode(",",$current[$j]["res"]);
					}
					$current[$j] = array("id"=>$row["id".$j], "name"=>$row["name".$j], "res"=>array());
				}
				$current[$j]["res"][] = $row["id".$final];
				$j--;
			}
			$results[str_repeat($depthsymbol, ($final -1)).$row["name".$final]] = $row["id".$final];
		}
		// we close the remaining sets
		$j = $final -1;
		while($j > 0 ){
			$key = str_repeat($depthsymbol, ($j -1)).$current[$j]["name"];
			$results[$key] = implode(",",$current[$j]["res"]);
			$j--;
		}
		if($withemptyrow)	$results[""] = "";
		return array_reverse($results);
	}
	function get_admin_hierarchyoptions($end=false, $withemptyrow=true, $separator=" &gt; ", $orderbyname=false){
		// returns an array of parent options linked to a selected level
		// used for the parent and filter dropdown in the admin
		// shows empty parents too
		$levelarray = $this->get_levelarray();
		if(!in_array($end, $levelarray))	$end = "item";
		
		// building the joined query
		$tables = "";
		$fields = "";
		$orderby = "";
		$finished = false;
		$i = 1;
		foreach($levelarray as $level){
			if($level == $end)	$finished = true;
			if(!$finished){
				$tables .= ($i==1?"":" LEFT JOIN ").cms_db_prefix()."module_".$this->GetName()."_".$level." t".$i." ";
				if($i>1)	$tables .= "ON t".($i-1).".id = t".$i.".parent ";
				$fields .= ($fields == ""?"":", ")."t".$i.".id id".$i.", t".$i.".name name".$i;
				if($orderbyname){
					$orderby .= ($orderby == ""?"":", ")."t".$i.".name";
				}else{
					$orderby .= ($orderby == ""?"":", ")."t".$i.".item_order, t".$i.".name";
				}
				$i++;
			}
		}
		if($fields == "")	return array();
		
		$db =& $this->GetDb();
		$query = "SELECT ".$fields." FROM ".$tables." ORDER BY ".$orderby;
		$dbresult = $db->Execute($query);
		$options = array();
		while($dbresult && $row = $dbresult->FetchRow()){
			// each row has a full hierarchy, from top parent to final child
			$value = $row["id".($i-1)];
			$j = 1;
			$option_name = "";
			while($j < $i){
				$option_name .= ($j==1?"":$separator).$row["name".$j];
				$j++;
			}
			$options[$option_name] = $value;
		}
		if($withemptyrow)	$options[""] = "";
		return $options;
	}
	function get_moduleGetVars(){
		// unorthodox hack so that different calls of the module speak with each other
		// basically, we retrieve parameters in the url that were meant for other instances of the module
		// see FAQ for more info on this
		global $_GET;
		global $gCms;
		$redirection = false;
		if(isset($gCms->config["url_rewriting"])){
			// core 1.6 or more
			$redirection = $gCms->config["url_rewriting"];
		}else{
			// core below 1.6
			if($gCms->config["assume_mod_rewrite"] && isset($_GET["page"])){
				$redirection = "mod_rewrite";
			}elseif(!$gCms->config["assume_mod_rewrite"] && $gCms->config["internal_pretty_urls"] && isset($_SERVER["REQUEST_URI"])){
				$redirection = "internal";
			}
		}
		$params = array();
		$globalmodulevars = array();
		if(isset($_GET["mact"])){
			// if we aren't using pretty urls...
			$modinfo = explode(",",$_GET["mact"]);
			if(isset($modinfo) && $modinfo[0] == $this->GetName()){
				if(isset($_GET[$modinfo[1]."parent"]))
					$globalmodulevars["parent"]=$_GET[$modinfo[1]."parent"];
				if(isset($_GET[$modinfo[1]."what"]))
					$globalmodulevars["what"]=$_GET[$modinfo[1]."what"];
				if(isset($_GET[$modinfo[1]."alias"]))
					$globalmodulevars["alias"]=$_GET[$modinfo[1]."alias"];
				if(isset($_GET[$modinfo[1]."pageindex"]))
					$globalmodulevars["pageindex"]=$_GET[$modinfo[1]."pageindex"];
			}
		}elseif($redirection == "mod_rewrite" || $redirection == "internal"){
			$params = array();
			if($redirection == "mod_rewrite" && isset($_GET["page"])){
				// if we are using an external mod_rewrite, assuming you are using the very
				// basic rewrite which puts the module informations inside the page variable
				$parts = explode("/",$_GET["page"]);
				foreach($parts as $part){
					if($part != "")	$params[] = $part;
				}
			}elseif($redirection == "internal"){
				// if we are using the internal pretty urls
				$parts = explode("/",$_SERVER["REQUEST_URI"]);
				$started = false;
				foreach($parts as $part){
					if($started && $part != "")	$params[] = $part;
					if(strtolower($part) == "index.php")	$started = true;
				}
			}
			if(isset($params[0]) && strtolower($params[0]) == strtolower($this->GetName())){
				 // we are in a module action
				 // this part should be changed if you change the pretty urls structure
				if(!isset($params[1]) || strtolower($params[1]) == "query"){

				}elseif(isset($params[1]) && strtolower($params[1]) == "detail"){
					$levels = $this->get_levelarray();
					$globalmodulevars["what"] = $levels[count($levels)-1];
					$globalmodulevars["alias"] = $params[2];
				}else{
					$globalmodulevars["what"] = $params[1];
					switch(count($params)){
						case 6:
							$globalmodulevars["pageindex"] = $params[3];
							$globalmodulevars["nbperpage"] = $params[4];
						case 4:
							$globalmodulevars["parent"] = $params[2];
							break;
						case 5:
							$globalmodulevars["pageindex"] = $params[2];
							$globalmodulevars["nbperpage"] = $params[3];
							break;
					}
				}
			}
		}
		return $globalmodulevars;
	}
	function get_objtree($curid, $curlevel=false, $field="id"){
		// this builds an object tree ($item->parent_object->parent_object...)
		// we first put all the parents in an array
		$parents = array();
		$levels = $this->get_levelarray();
		if(!$curlevel)	$curlevel = $levels[count($levels)-1];
		$i = count($levels);
		$started = false;
		while($curid && $i > 0){
			if($levels[$i -1] == $curlevel)	$started = true;
			if($started){
				$getfunction = "get_level_".$levels[$i -1];
				$item = $this->$getfunction(array($field=>$curid));
				$item = is_array($item)?$item[0]:$item;
				$item->__what = $levels[$i-1];
				$parents[] = $item;
				$field = "id";
				$curid = isset($item->parent_id)?$item->parent_id:false;
			}
			$i--;
		}
		
		// next, we process the array of parents to build the parent tree
		$parenttree = false;
		$i = count($parents) - 1;
		while($i >= 0){
			if($parenttree){
				$newtree = $parents[$i];
				$newtree->parent_object = $parenttree;
				$parenttree = $newtree;
			}else{
				$parenttree = $parents[$i];
			}
			$i--;
		}
		$this->currenttree = $parenttree;
		return $parenttree;
	}
	function buildGlobalTree(){
		// this saves the current tree in the module...
		$levels = $this->get_levelarray();
		$glob = $this->get_moduleGetVars();
		if(isset($glob["pageindex"]))	$this->currentpageindex = $glob["pageindex"];
		if(isset($glob["alias"])){
			$tree = $this->get_objtree($glob["alias"], $levels[count($levels)-1], "alias");
		}elseif(isset($glob["parent"]) && isset($glob["what"])){
			$tree = $this->get_objtree($glob["parent"], $this->get_nextlevel($glob["what"],false), "alias");
		}
		
		if(isset($tree) && $tree){
			$this->currenttree = $tree;
			// this saves the selected item for each level
			$this->plcurrent[$tree->__what] = $tree->alias;
			while(isset($tree->parent_object)){
				$tree = $tree->parent_object;
				$this->plcurrent[$tree->__what] = $tree->alias;
			}
		}
	}
	function function_modbreadcrumbs($params, &$smarty){
		// registered smarty function that generates breadcrumbs
		// uses similar parameters to the core breadcrumbs function
		if(!$this->currenttree)		$this->buildGlobalTree();
		
		if($this->currenttree){
			global $gCms;
			$returnid = $gCms->variables["content_id"];
			// we create the breadcrumbs
			$classid = isset($params["classid"])?" class=\"".$params["classid"]."\"":"";
			$currentclassid = isset($params["currentclassid"])?" class=\"".$params["currentclassid"]."\"":" class=\"lastitem\"";
			$startlevel = isset($params["startlevel"])?$this->get_nextlevel($params["startlevel"], false):false;
			$delimiter = isset($params["delimiter"])?$params["delimiter"]:$this->Lang("breadcrumbs_delimiter");
			$initial = isset($params["initial"])?$params["initial"]:$this->Lang("youarehere");

			$output = "";
			$current = $this->currenttree;
			$goingon = false;
			while(isset($current->parent_object)){
				$current = $current->parent_object;
				if($current->__what == $startlevel)	$ended = true;
				if(!$ended){
					$params = array("what"=>$this->get_nextlevel($current->__what), "parent"=>$current->alias);
					$prettyurl = $this->BuildPrettyUrls($params, $returnid);
					$output = "<span".$classid.">".$this->CreateLink("", "default", $returnid, $current->name, $params, "", false, false, "", false, $prettyurl)."</span>".$delimiter.$output;
				}
			}
			if($output != "") $output = $initial.$output."<span".$currentclassid.">".$this->currenttree->name."</span>";
			
			if(isset($params["assign"]) && $params["assign"] != ""){
				$smarty->assign($params["assign"], $output);
			}else{
				echo $output;
			}
		}
	}
	function function_get_levelitem($params, &$smarty){
		// registered smarty function that retrieves a given item (alias) of a given level (what)
		if(	!isset($params["assign"]) || $params["assign"] == "" || !isset($params["what"]) || !isset($params["alias"]) )	return false;
		if(!in_array($params["what"], $this->get_levelarray()))	return false;
		$getfunction = "get_level_".$params["what"];
		$results = $this->$getfunction(array("alias"=>$params["alias"]));
		$item = isset($results[0])?$results[0]:false;
		$smarty->assign($params["assign"], $item);
	}
	function countsomething($tablename,$what="id",$where=array(),$wherestring=false,$wherevalues=array(),$parent=false){
		// returns the number of elements in a table corresponding to criterias
		if(!$parent && isset($where["parent"]))	unset($where["parent"]);
		$db =& $this->GetDb();
		if($wherestring){
			$wherestring = " WHERE ".$wherestring;
		}else{
			$wherestring = "";
			$wherevalues = array();
			foreach($where as $key=>$value){
				if($key == "parent"){
					$wherestring .= ($wherestring == ""?" WHERE ":" AND ")."A.parent=B.id AND B.alias=?";
				}else{
					$wherestring .= ($wherestring == ""?" WHERE ":" AND ")."A.".$key."=?";
				}
				$wherevalues[] = $value;
			}
		}
		$query = "SELECT COUNT(A.$what) ourcount FROM ".cms_db_prefix()."module_".$this->GetName()."_".$tablename." A";
		if($parent && isset($what["parent"]))	$query .= ", ".cms_db_prefix()."module_".$this->GetName()."_".$parent." B";
		$query .= $wherestring;
		$dbresult = $db->Execute($query,$wherevalues);
		if ($dbresult && $row = $dbresult->FetchRow()){
			return $row["ourcount"];
		}else{
			return 0;
		}
	}
	
	
	function admin_paginate($level,$nbperpage,$id,$returnid,$params){
		// same as paginate, but for the admin panels
		$whereclause = array();
		if(isset($params[$level."_showonly"]) && $params[$level."_showonly"] != "")	$whereclause["parent"] = $params[$level."_showonly"];
		$total = $this->countsomething($level,"id",$whereclause);
		$nextpage = false;
		$previouspage = false;
		$pageinfo = false;
		$pages = false;
		if($nbperpage > 0 && $total > $nbperpage){
			$curpage = isset($params[$level."_page"])?$params[$level."_page"]:0;
			$nbpages = ceil($total/$nbperpage);
			$pageinfo = (($curpage * $nbperpage) + 1);
			$tmpend = $pageinfo + $nbperpage - 1;
			if($tmpend > $total)	$tmpend = $total;
			if($tmpend != $pageinfo)	$pageinfo .= "-".$tmpend;
			$pageinfo .= " / ".$total;
			$pageparams = array("active_tab"=>$level);
			if(isset($params[$level."_showonly"]) && $params[$level."_showonly"] != ""){
				$pageparams[$level."_showonly"] = $params[$level."_showonly"];
			}
			if($curpage > 0){
				$pageparams[$level."_page"] = $curpage - 1;
				$previouspage = $this->CreateLink($id, "defaultadmin", $returnid, $this->Lang("previouspage"), $pageparams);
			}
			if($curpage < ($nbpages-1)){
				$pageparams[$level."_page"] = $curpage + 1;
				$nextpage = $this->CreateLink($id, "defaultadmin", $returnid, $this->Lang("nextpage"), $pageparams);
			}
			$i = 0;
			$pages = $this->Lang("pages");
			while($i < $nbpages){
				if($i > 0)	$pages .= "&nbsp; ";
				if($i == $curpage){
					$pages .= $i;
				}else{
					$pageparams[$level."_page"] = $i;
					$pages .= $this->CreateLink($id, "defaultadmin", $returnid, $i, $pageparams);
				}
				$i++;
			}
		}
		return array($pageinfo, $nextpage, $previouspage, $pages);
	}

	function paginate($what,$total,$id,$returnid,$params,$action="default"){
		// for frontend usage
		// this assigns the different parts of the page menu
		
		$nbperpage = isset($params["nbperpage"])?$params["nbperpage"]:0;
		$nbperpage = (int) $nbperpage;
		if($nbperpage == 0 || $nbperpage >= $total){
			// we're not using pagination
			$this->smarty->assign(array("page_showing"=>false,"page_totalitems"=>false,"page_pagenumbers"=>false,"page_next"=>false,"page_previous"=>false));
			return false;
		}
		
		// we're using pagination
		$curpage = isset($params["pageindex"])?$params["pageindex"]:1;
		$nbpages = ceil($total/$nbperpage);
		$showing = ((($curpage -1) * $nbperpage) + 1);
		$tmpend = $showing + $nbperpage - 1;
		if($tmpend > $total)	$tmpend = $total;
		if($tmpend != $showing)	$showing .= "-".$tmpend;
		$this->smarty->assign("page_showing",$showing);
		$this->smarty->assign("page_totalitems",$total);

		$newparams = $params;
		$previouslink = false;
		$nextlink = false;
		if($curpage > 1){
			// we create the link to the previous page
			$newparams["pageindex"] = $curpage - 1;
			$previouslink = $this->CreateLink($id, $action, $returnid, "&lt;", $newparams, "", false, true, " class=\"previouslink\"", false, ($action != "default")?"":$this->BuildPrettyUrls($newparams, $returnid));
		}
		if($curpage < $nbpages){
			// we create the link to the next page
			$newparams["pageindex"] = $curpage + 1;
			$nextlink = $this->CreateLink($id, $action, $returnid, "&gt;", $newparams, "", false, true, " class=\"nextlink\"", false, ($action != "default")?"":$this->BuildPrettyUrls($newparams, $returnid));
		}
		$this->smarty->assign("page_next",$nextlink);
		$this->smarty->assign("page_previous",$previouslink);	
				
		// we create links for each page number
		$i = 1;
		$links = array();
		while($i <= $nbpages && $nbpages > 1){
			$newparams["pageindex"] = $i;
			if($i == $curpage){
				$links[] = "<a class=\"pagenumber current\">".$i."</a>";
			}else{
				$links[] = $this->CreateLink($id, $action, $returnid, $i, $newparams, "", false, true, " class=\"pagenumber\"", false, ($action != "default")?"":$this->BuildPrettyUrls($newparams, $returnid));
			}
			$i++;
		}
		$i = 0;
		$tmpflag = true;
		$maxpages = $this->GetPreference("maxshownpages",7);
		$maxpages = (int) $maxpages;
		$pagemenu = "";
		if($maxpages < 1)	$maxpages = 999;
		while($i < count($links)){
			if($i < ($maxpages-1) || $nbpages <= $maxpages){
				$pagemenu .= ($pagemenu == ""?"":$this->Lang("pagemenudelimiter")).$links[$i];
			}elseif($i == count($links)){
				$pagemenu .= $links[$i];
			}elseif($tmpflag){
				$tmpflag = false;
				$pagemenu .= "<span class=\"pagemenuoverflow\">".$this->Lang("pagemenuoverflow")."</span>";
			}
			$i++;
		}
		$this->smarty->assign("page_pagenumbers",$pagemenu);
	}

	function get_distancetolevel($parentname,$childname=false){
		// get the distance between two levels (most likely between a level and the final level)
		$levels = $this->get_levelarray();
		if(!$childname)	$childname = $levels[count($levels)-1];
		$parentposition = false;
		$childposition = false;
		$counter = 0;
		foreach($levels as $level){
			$counter++;
			if($level == $parentname) $parentposition = $counter;
			if($level == $childname) $childposition = $counter;
		}
		if($childposition && $parentposition){
			return abs($parentposition - $childposition);
		}
	}
	function get_nextlevel($curlevel,$findchild=true){
		// return the name of the level below ($findchild=true) or above ($findchild=false)
		$levels = $this->get_levelarray();
		$i = 0;
		$wantedlevel = false;
		while($i < count($levels)){
			$next = $findchild?$i+1:$i-1;
			if($levels[$i] == $curlevel && isset($levels[$next])) $wantedlevel = $levels[$next];
			$i++;
		}
		return $wantedlevel;
	}
	function get_parents($parentname,$childname,$childid){
		// when using the sharechildren option, retrieves the parents of a given element
		$db =& $this->GetDb();
		$query = "SELECT ".$parentname."_id parentid FROM ".cms_db_prefix()."module_".$this->GetName()."_".$parentname."_has_".$childname." WHERE ".$childname."_id=?";
		$dbresult = $db->Execute($query,array($childid));
		$parents = array();
		while ($dbresult && $row = $dbresult->FetchRow()){
			$parents[] = $row["parentid"];
		}
		return $parents;
	}
	function get_options($tablename,$onlyactive=false,$fullobject=false){
		// returns the elements of any table as options to use for a dropdown list
		// returns an array of $label=>$value
		$orderbyname = $this->GetPreference("orderbyname",false);
		$db =& $this->GetDb();
		$query = "SELECT ".($fullobject?"*":"id, name")." FROM ".cms_db_prefix()."module_".$this->GetName()."_".$tablename;
		if($onlyactive)	$query .= " WHERE active=1";
		$query .= " ORDER BY ".($orderbyname?"name":"item_order");
		$dbresult = $db->Execute($query);
		$options = array();
		while ($dbresult && $row = $dbresult->FetchRow()){
			if($fullobject){
				$obj = new StdClass();
				foreach($row as $key=>$value)	$obj->$key = $value;
				$options[] = $obj;
			}else{
				$options[$row["name"]] = $row["id"];
			}
		}
		
		return $options;
	}
	function get_fieldoptions($field,$fullobject=false){
		// returns field options as options to use for a dropdown list
		$db =& $this->GetDb();
		$query = "SELECT * FROM ".cms_db_prefix()."module_".$this->GetName()."_fieldoptions WHERE field=? ORDER BY item_order";
		$dbresult = $db->Execute($query,array($field));
		$options = array();
		while ($dbresult && $row = $dbresult->FetchRow()){
			if($fullobject){
				$obj = new StdClass();
				foreach($row as $key=>$value)	$obj->$key = $value;
				$options[] = $obj;
			}else{
				$options[$row["name"]] = $row["id"];
			}
		}
		
		return $options;
	}
	function get_pageid($alias){
		// returns the page id from an alias
		global $gCms;
		$manager =& $gCms->GetHierarchyManager();
		$node =& $manager->sureGetNodeByAlias($alias);
		if (isset($node)) {
			$content =& $node->GetContent();	
			if (isset($content))	return $content->Id();
		}else{
			$node =& $manager->sureGetNodeById($alias);
			if (isset($node)) return $alias;
		}
	}
	function addfrontendurls($item,$params,$id,$returnid){
		// adds $item->detaillink and $item->detailurl to a given $item
		$nextlevel = $this->get_nextlevel($item->__what);
		if(!$nextlevel){
			$newparams = array("what"=>$item->__what, "alias"=>$item->alias);
		}else{
			$newparams = array("what"=>$nextlevel, "parent"=>$item->alias);
			if(isset($params["forcelist"]) && $params["forcelist"])	$newparams["forcelist"] = true;
			if(isset($params["nbperpage"]) && $params["nbperpage"] > 0)	$newparams["nbperpage"] = $params["nbperpage"];
			if(isset($params["pageindex"]) && $params["pageindex"] > 1)	$newparams["pageindex"] = $params["pageindex"];
		}
		$inline = (isset($params["inline"]) && $params["inline"])?true:false;
		if($inline)	$newparams["inline"] = true;
		$item->detaillink = $this->CreateLink($id, "default", $returnid, $item->name, $newparams, "", false, $inline, "", false, $this->BuildPrettyUrls($newparams, $returnid));
		$item->detailurl = $this->CreateLink($id, "default", $returnid, "", $newparams, "", true, $inline, "", false, $this->BuildPrettyUrls($newparams, $returnid));
		return $item;
	}
	function addCartUrls($itemlist,$id,$returnid){
		// for eventual use with CartMadeSimple
		// however, integration requires changes to CartMadeSimple
		$CartMadeSimple = false;
		if (isset($gCms->modules["CartMadeSimple"]) && $gCms->modules["CartMadeSimple"]["active"])	$CartMadeSimple = $gCms->modules["CartMadeSimple"]["object"];
		if(!$CartMadeSimple)	return $itemlist;
		$cartparams = array("perfaction"=>"add_product", "qty"=>1, "returnmod"=>$this->GetName());
		$newlist = array();
		foreach($itemlist as $item){
			$cartparams["product_id"] = $item->id;
			$cartparams["name"] = $item->name;
			$item->addtocartlink = $CartMadeSimple->CreateLink($id, "cart", $returnid, $this->Lang("addtocart"), $cartparams);
			$item->addtocarturl = $CartMadeSimple->CreateLink($id, "cart", $returnid, "", $cartparams, "", true);
			array_push($newlist, $item);
		}
		return $newlist;
	}
	function addadminlinks($item,$params,$id,$returnid){
		// adds the admin links to the level items...
		/* $params : the base parameters for the admin links
				prefix
				tablename
				child (if the level has a child level, the name of the child level)
				levelname
				parentdefault (bool... whether or not there is a default element for each parent)
				orderbyparent (bool)
				addfiles
				sharechildren (bool)
				sharedbyparents (bool)
				files (string,csv)
		*/

		global $gCms;
		$admintheme = $gCms->variables["admintheme"];
		
		if( $this->GetPreference("restrict_permissions",false) && !$this->CheckPermission($this->GetName()."_advanced") && !$this->CheckPermission($this->GetName()."_manage_".$params["levelname"]) ){
			// no access - we don't create links
			$item->editlink = $item->name;
			$item->deletelink = "";
			$item->movelinks = "";
			$item->toggleactive = $item->active==1?$admintheme->DisplayImage("icons/system/true.gif","","","","systemicon"):$admintheme->DisplayImage("icons/system/false.gif","","","","systemicon");
			$item->toggledefault = $item->isdefault==1?$admintheme->DisplayImage("icons/system/true.gif","","","","systemicon"):$admintheme->DisplayImage("icons/system/false.gif","","","","systemicon");
			return $item;
		}
		
		$prefix = $params["prefix"];
		$moveparams = $params;
		$moveparams[$prefix."id"] = $item->id;
		$moveparams["currentorder"] = $item->item_order;
		if(isset($moveparams["files"]))	unset($moveparams["files"]);
		if($moveparams["orderbyparent"] && isset($item->parent_id)) $moveparams["parent"] = $item->parent_id;

		$item->editlink = $this->CreateLink($id, "edit".$prefix, $returnid, $item->name, array($prefix."id"=>$item->id));
		$item->deletelink = $this->CreateLink($id, "movesomething", $returnid, $admintheme->DisplayImage("icons/system/delete.gif",lang("delete"),"","","systemicon"), array_merge(array("move"=>"delete","files"=>$params["files"]),$moveparams),$this->Lang("prompt_delete".$params["levelname"], str_replace("'","\'",$item->name)));
		$item->moveuplink = $this->CreateLink($id, "movesomething", $returnid, $admintheme->DisplayImage("icons/system/arrow-u.gif",lang("up"),"","","systemicon"), array_merge(array("move"=>"up"),$moveparams));
		$item->movedownlink = $this->CreateLink($id, "movesomething", $returnid, $admintheme->DisplayImage("icons/system/arrow-d.gif",lang("down"),"","","systemicon"), array_merge(array("move"=>"down"),$moveparams));
		$item->movelinks = $item->moveuplink ." ". $item->movedownlink;
		
		// we rebuild the params, because we don't need so many for the toggle action
		$toggleparams = array($prefix."id"=>$item->id, "prefix"=>$prefix, "tablename"=>$params["tablename"], "levelname"=>$params["levelname"]);
		if($params["parentdefault"] && isset($item->parent_id)){
			$toggleparams["parent"] = $item->parent_id;
			$toggleparams["parentdefault"] = 1;
		}
		if ($item->active == 1){
			$item->toggleactive = $this->CreateLink($id, "toggle", $returnid, $admintheme->DisplayImage("icons/system/true.gif",lang("setfalse"),"","","systemicon"), array_merge(array("what"=>"active","newval"=>0),$toggleparams));
		}else{
			$item->toggleactive = $this->CreateLink($id, "toggle", $returnid, $admintheme->DisplayImage("icons/system/false.gif",lang("settrue"),"","","systemicon"), array_merge(array("what"=>"active","newval"=>1),$toggleparams));
		}

		if ($item->isdefault == 1){
			$item->toggledefault = $this->CreateLink($id, "toggle", $returnid, $admintheme->DisplayImage("icons/system/true.gif",lang("setfalse"),"","","systemicon"), array_merge(array("what"=>"default","newval"=>0),$toggleparams));
		}else{
			$item->toggledefault = $this->CreateLink($id, "toggle", $returnid, $admintheme->DisplayImage("icons/system/false.gif",lang("settrue"),"","","systemicon"), array_merge(array("what"=>"default","newval"=>1),$toggleparams));
		}
		return $item;
	}
	function split_into_pages($itemlist, $id, $returnid, $params, $action="default", $prettyurls=true){
		// deprecated - should use function paginate instead
		return $itemlist;
	}
	function get_queries($where=array(), $admin=false, $id=false, $returnid=false){
		// retrives queries from the database and returns an array of objects
		global $gCms;
		$db =& $this->GetDb();
		$query = "SELECT * FROM ".cms_db_prefix()."module_".$this->GetName()."_saved_queries";
		$whereclause = "";
		$wherevalues = array();
		if(count($where)>0){
			foreach($where as $key=>$value){
				$whereclause .= ($whereclause == ""?" WHERE ":" AND ").$key."=?";
				$wherevalues[] = $value;
			}
		}
		$query .= $whereclause;
		$dbresult = $db->Execute($query,$wherevalues);
		$itemlist = array();
		while ($dbresult && $row = $dbresult->FetchRow()){
			$item = new stdClass();
			foreach($row as $key=>$value){
				$item->$key = $value;
			}
			$item->wherevalues = unserialize($item->wherevalues);
			if(!is_array($item->values))	$item->values = array();
			if($admin){
				$item->actions = $this->CreateLink($id, "deletequery", $returnid, $gCms->variables["admintheme"]->DisplayImage("icons/system/delete.gif",lang("delete"),"","","systemicon"), array("queryid"=>$item->id));
				$item->actions .= $this->CreateLink($id, "testquery", $returnid, $gCms->variables["admintheme"]->DisplayImage("icons/system/view.gif",lang("view"),"","","systemicon"), array("queryid"=>$item->id));
			}
			$itemlist[] = $item;
		}
		return (count($itemlist)>0?$itemlist:false);
	}

	function createFieldForm($what,$id,$assign=true){
		require "function.createFieldForm.php";
	}
	

	function buildWhere($where=array(),$what=""){
		$multiplelistfields = array();
		$whereclause = "A.active=1";
		$wherevalues = array();
		foreach($where as $clause){
			if(in_array($what."_".$clause[0], $multiplelistfields)){
				// list with multiple selected values are saved in a serialized array, so we have to tweak the value
				$clause[1] = '"'.$clause[1].'"';
				$clause[2] = 1;
			}
			if($clause[0] == "parent"){
					$criteria = explode(",",$clause[1]);
					if(count($criteria) > 1){
						$tmp = "";
						foreach($criteria as $crit){
							$tmp = ($tmp==""?"":" OR ")."A.parent=".$crit;
						}
						$tmp = " AND (".$tmp.")";
					}else{
						$tmp = " AND A.parent=?";
						$wherevalues[] = $clause[1];
					}
					$whereclause .= $tmp;
				
			}else{
				switch($clause[2]){
					case 0:
						$whereclause .= " AND A.".$clause[0]."=?";
						$wherevalues[] = addslashes($clause[1]);					
						break;
					
					case 1:
						$whereclause .= " AND A.".$clause[0]." LIKE '%".addslashes($clause[1])."%'";
						break;
					
					case 2:
						$whereclause .= " AND A.".$clause[0]." != ?";
						$wherevalues[] = addslashes($clause[1]);
						break;
						
					case 3:
						$whereclause .= " AND A.".$clause[0]." > ?";
						$wherevalues[] = addslashes($clause[1]);					
						break;
						
					case 4:
						$whereclause .= " AND A.".$clause[0]." < ?";
						$wherevalues[] = addslashes($clause[1]);					
						break;
					case 5:
						$whereclause .= " AND (A.".$clause[0]." > ? AND A.".$clause[0]." < ?)";
						$wherevalues[] = addslashes($clause[1][0]);
						$wherevalues[] = addslashes($clause[1][1]);
						break;					
				}
			}
		}
		return array($whereclause, $wherevalues);
	}
	function getWhereFromParams($params, $htmlencoded=false){
		// used for query creation and frontend advanced search action
		// transforms parameters into $where - an array of criteria:
		// $where[] = array($fieldname, $searchvalue, $comparison_type)
		$db =& $this->GetDb();
		$where = array();
		foreach($params as $key=>$value){
			if(substr($key,0,6) == "field_" && trim($value) != ""){
				$key = substr($key,6);
				if(is_array($value) && count($value)==1)	$value = $value[0];
				$compare = isset($params["compare_".$key])?$params["compare_".$key]:0;
				if($value == "__date_field"){
					if($compare != "NA"){
						// The code is such that removing any part of the time input from the form will result in using the default value
						$timeinputs = array("Hour"=>"H","Minute"=>"i","Second"=>"s","Month"=>"n","Day"=>"j","Year"=>"Y");
						$parts = array();
						foreach($timeinputs as $inputname=>$datepart){	
							$parts[] = (isset($params["date_".$key."_".$inputname]))?$params["date_".$key."_".$inputname]:date($datepart);
						}
						$value = mktime($parts[0],$parts[1],$parts[2],$parts[3],$parts[4],$parts[5]);
						$value = str_replace("\'","",$db->DBTimeStamp($value));
						
						if($compare == 5){
							$parts = array();
							foreach($timeinputs as $inputname=>$datepart){	
								$parts[] = (isset($params["date_".$key."_part2_".$inputname]))?$params["date_".$key."_part2_".$inputname]:date($datepart);
							}
							$value2 = mktime($parts[0],$parts[1],$parts[2],$parts[3],$parts[4],$parts[5]);
							$value2 = str_replace("\'","",$db->DBTimeStamp($value2));
							$where[] = array($key, array($value,$value2), $compare);
						}else{	
							$where[] = array($key, $value, $compare);
						}
					}
				}else{
					if(is_array($value)){
						foreach($value as $onevalue)	$where[] = array($key, $onevalue, 1);
					}elseif($compare == 1){
						$keywords = $this->parsekeywords($value);
						foreach($keywords as $value){
							if($htmlencoded)	$value = html_entity_decode($value);
							$value = addslashes($value);							
							$where[] = array($key, $value, $compare);
						}
					}else{
						if($htmlencoded)	$value = html_entity_decode($value);
						$value = addslashes($value);
						$where[] = array($key, $value, $compare);
					}
				}				
			}
		}		
		return $where;
	}

	function get_level_player($where=array(),$admin=false,$id="",$returnid="",$order=false,$limit=0,$customwhere=false,$customvalues=array(),$customorder=false){
		global $gCms;
		$load_nbchildren = $this->GetPreference("load_nbchildren",true);
		if($admin)	$admintheme = $gCms->variables["admintheme"];
		if(!$order)	$order = "";
		$db =& $this->GetDb();
		$fields = array("location","width","height","parameters","userdefined1","id","name","alias","item_order","active","isdefault");
		
		$wherestring = "";
		$wherevalues = array();
		

		if($customwhere){
			$wherestring = $customwhere;
			$wherevalues = $customvalues;
		}else{
			foreach($where as $key=>$value){
				if(in_array(strtolower($key), $fields)){
					$wherestring .= ($wherestring == ""?"":" AND ").$key."=?";
					$wherevalues[] = $value;
				}
			}
		}
		$query = "SELECT * FROM ".cms_db_prefix()."module_avplayer_player A ".($wherestring == ""?"":" WHERE ".$wherestring);
		$query .= ($customorder?" ORDER BY ".$customorder:" ORDER BY item_order");

		if($limit && $limit != "0" && $limit != "")	$query .= " LIMIT ".$limit;

			$options_userdefined1 = array_flip($this->get_fieldoptions("player_userdefined1"));
		$dbresult = $db->Execute($query,$wherevalues);
		$itemlist = array();
		$idlist = "";
		while ($dbresult && $row = $dbresult->FetchRow()){
			$item = new stdClass();
			$item->__what = "player";
			foreach($row as $key=>$value){
				$item->$key = $value;
			}
			$item->location = stripslashes($item->location);
			$item->width = stripslashes($item->width);
			$item->height = stripslashes($item->height);
			$item->parameters = stripslashes($item->parameters);
			$item->userdefined1 = (isset($options_userdefined1[$item->userdefined1]))?$item->userdefined1:false;
			$item->userdefined1_namevalue = (isset($options_userdefined1[$item->userdefined1]))?$options_userdefined1[$item->userdefined1]:"";
			$item->name = stripslashes($item->name);
			$item->alias = stripslashes($item->alias);
                        $item->insertTag = '{cms_module module="avplayer" parent="'.$item->alias.'"}';
			if($admin == true){
				// $parms will be the base for parameters of the admin links
				$parms = array(
					"prefix"=>"A",
					"tablename"=>"avplayer_player",
					"levelname"=>"player",
					"child"=>"avplayer_mediafile",
					"parentdefault"=>false,
					"orderbyparent"=>true,
					"addfiles"=>"",
					"sharechildren"=>false,
					"sharedbyparents"=>false,
					"files"=>""
					);
				$item = $this->addadminlinks($item,$parms,$id,$returnid);
			}
			$idlist .= ($idlist==""?"":" OR ")." parent='".$item->id."'";
			array_push($itemlist,$item);
		}

		if($admin || $load_nbchildren){
			$nbchildrens = array();
			$query = "SELECT parent, count(id) nbchildren FROM ".cms_db_prefix()."module_avplayer_mediafile WHERE (".$idlist.") ".($admin?"":"AND active=1 ")."GROUP BY parent";
			$dbresult = $db->Execute($query);
			while($dbresult && $row = $dbresult->FetchRow())	$nbchildrens[$row["parent"]] = $row["nbchildren"];
			$newlist = array();
			foreach($itemlist as $item){
				$item->nbchildren = isset($nbchildrens[$item->id])?$nbchildrens[$item->id]:false;
				array_push($newlist, $item);
			}
			$itemlist = $newlist;
		}
		return $itemlist;
	}

	function get_level_mediafile($where=array(),$admin=false,$id="",$returnid="",$order=false,$limit=0,$customwhere=false,$customvalues=array(),$customorder=false){
		global $gCms;
		$load_nbchildren = $this->GetPreference("load_nbchildren",true);
		if($admin)	$admintheme = $gCms->variables["admintheme"];
		if(!$order)	$order = "";
		$db =& $this->GetDb();
		$fields = array("flvfile","mp4file","webmfile","ogvfile","poster","mp3file","description","parent","id","name","alias","item_order","active","isdefault");
		
		$wherestring = "";
		$wherevalues = array();
		

		if($customwhere){
			$wherestring = $customwhere;
			$wherevalues = $customvalues;
		}else{
			foreach($where as $key=>$value){
				if($key == "parent"){
					$wherestring .= ($wherestring == ""?"":" AND ")."B.alias=?";
					$wherevalues[] = $value;
				}elseif($key == "parent_id"){
					$wherestring .= ($wherestring == ""?"":" AND ")."B.id=?";
					$wherevalues[] = $value;
				}elseif(in_array(strtolower($key), $fields)){
					$wherestring .= ($wherestring == ""?"":" AND ")."A.".$key."=?";
					$wherevalues[] = $value;
				}
			}
		}
		
		$query = "SELECT A.*, B.id parent_id, B.name parent_name, B.alias parent_alias FROM ".cms_db_prefix()."module_avplayer_mediafile A, ".cms_db_prefix()."module_avplayer_player B WHERE A.parent = B.id ".($wherestring == ""?"":" AND ").$wherestring;
		if($customorder){
			 $query .= " ORDER BY A.".$customorder;
		}elseif($order == "modified"){
			 $query .= " ORDER BY A.date_modified DESC";
		}elseif($order == "created"){
			 $query .= " ORDER BY A.id DESC";
		}elseif( $order != "" && ($this->GetPreference("allow_complex_order",false) || in_array(strtolower($order), $fields)) ){
			$query .= " ORDER BY A.".$order;
		}else{
			 $query .= " ORDER BY B.item_order, A.item_order";
		}
		if($limit && $limit != "0" && $limit != "")	$query .= " LIMIT ".$limit;

		$dbresult = $db->Execute($query,$wherevalues);
		$itemlist = array();
		$idlist = "";
		while ($dbresult && $row = $dbresult->FetchRow()){
			$item = new stdClass();
			$item->__what = "mediafile";
			foreach($row as $key=>$value){
				$item->$key = $value;
			}
			if($item->flvfile != "" && $item->flvfile != "/"){
				$file = new StdClass();
				$file->filepath = (substr($item->flvfile,0,1)=="/"?"":"/").$item->flvfile;
				$file->url = $gCms->config["uploads_url"].$file->filepath;
				$info = $this->plGetFileInfo($gCms->config["uploads_path"].$file->filepath);
				foreach($info as $key=>$value)	$file->$key = $value;
				if($file->fileicon == "icons/filetypes/fpaint.gif")	$file->image = '<img src="'.$file->url.'" alt=""/>';
				if($admin)	$file->pic = $admintheme->DisplayImage($file->fileicon);
			}else{
				$file = false;
			}
			$item->flvfile = $file;
			if($item->mp4file != "" && $item->mp4file != "/"){
				$file = new StdClass();
				$file->filepath = (substr($item->mp4file,0,1)=="/"?"":"/").$item->mp4file;
				$file->url = $gCms->config["uploads_url"].$file->filepath;
				$info = $this->plGetFileInfo($gCms->config["uploads_path"].$file->filepath);
				foreach($info as $key=>$value)	$file->$key = $value;
				if($file->fileicon == "icons/filetypes/fpaint.gif")	$file->image = '<img src="'.$file->url.'" alt=""/>';
				if($admin)	$file->pic = $admintheme->DisplayImage($file->fileicon);
			}else{
				$file = false;
			}
			$item->mp4file = $file;
			if($item->webmfile != "" && $item->webmfile != "/"){
				$file = new StdClass();
				$file->filepath = (substr($item->webmfile,0,1)=="/"?"":"/").$item->webmfile;
				$file->url = $gCms->config["uploads_url"].$file->filepath;
				$info = $this->plGetFileInfo($gCms->config["uploads_path"].$file->filepath);
				foreach($info as $key=>$value)	$file->$key = $value;
				if($file->fileicon == "icons/filetypes/fpaint.gif")	$file->image = '<img src="'.$file->url.'" alt=""/>';
				if($admin)	$file->pic = $admintheme->DisplayImage($file->fileicon);
			}else{
				$file = false;
			}
			$item->webmfile = $file;
			if($item->ogvfile != "" && $item->ogvfile != "/"){
				$file = new StdClass();
				$file->filepath = (substr($item->ogvfile,0,1)=="/"?"":"/").$item->ogvfile;
				$file->url = $gCms->config["uploads_url"].$file->filepath;
				$info = $this->plGetFileInfo($gCms->config["uploads_path"].$file->filepath);
				foreach($info as $key=>$value)	$file->$key = $value;
				if($file->fileicon == "icons/filetypes/fpaint.gif")	$file->image = '<img src="'.$file->url.'" alt=""/>';
				if($admin)	$file->pic = $admintheme->DisplayImage($file->fileicon);
			}else{
				$file = false;
			}
			$item->ogvfile = $file;
			if($item->poster != "" && $item->poster != "/"){
				$file = new StdClass();
				$file->filepath = (substr($item->poster,0,1)=="/"?"":"/").$item->poster;
				$file->url = $gCms->config["uploads_url"].$file->filepath;
				$info = $this->plGetFileInfo($gCms->config["uploads_path"].$file->filepath);
				foreach($info as $key=>$value)	$file->$key = $value;
				if($file->fileicon == "icons/filetypes/fpaint.gif")	$file->image = '<img src="'.$file->url.'" alt=""/>';
				if($admin)	$file->pic = $admintheme->DisplayImage($file->fileicon);
			}else{
				$file = false;
			}
			$item->poster = $file;
			if($item->mp3file != "" && $item->mp3file != "/"){
				$file = new StdClass();
				$file->filepath = (substr($item->mp3file,0,1)=="/"?"":"/").$item->mp3file;
				$file->url = $gCms->config["uploads_url"].$file->filepath;
				$info = $this->plGetFileInfo($gCms->config["uploads_path"].$file->filepath);
				foreach($info as $key=>$value)	$file->$key = $value;
				if($file->fileicon == "icons/filetypes/fpaint.gif")	$file->image = '<img src="'.$file->url.'" alt=""/>';
				if($admin)	$file->pic = $admintheme->DisplayImage($file->fileicon);
			}else{
				$file = false;
			}
			$item->mp3file = $file;
			$item->name = stripslashes($item->name);
			$item->alias = stripslashes($item->alias);
		$item->nbchildren = false;
		
			if($admin == true){
				// $parms will be the base for parameters of the admin links
				$parms = array(
					"prefix"=>"B",
					"tablename"=>"avplayer_mediafile",
					"levelname"=>"mediafile",
					"child"=>false,
					"parentdefault"=>false,
					"orderbyparent"=>true,
					"addfiles"=>"",
					"sharechildren"=>false,
					"sharedbyparents"=>false,
					"files"=>"flvfile,mp4file,webmfile,ogvfile,poster,mp3file"
					);
				$item = $this->addadminlinks($item,$parms,$id,$returnid);
			}
			$idlist .= ($idlist==""?"":" OR ")." parent='".$item->id."'";
			array_push($itemlist,$item);
		}

		return $itemlist;
	}
	function plResize($fullpath, $newpath, $newwidth, $newheight=false, $transparency=false, $crop=false) {
		require "function.plresize.php";
		return $return;
	}
	
	function plGetFileInfo($filepath){
		if(!file_exists($filepath))	return false;
		require "function.plGetFileInfo.php";
		return $info;
	}
	
	function upload_checkfilename($filename, $dir){
		// if a file of that name exists, appends number to the filename
		$tmpfilename = str_replace(" ","_",$filename);
		$extension = strrchr($tmpfilename, ".");
		$cleanfilename = str_replace($extension, "", $tmpfilename);

		$destdir = $dir.$tmpfilename;
		$i = 1;
		while(file_exists($destdir)){
			$tmpfilename = $cleanfilename."_".$i.$extension;
			$destdir = $dir.$tmpfilename;
			$i++;
		}
		return $tmpfilename;
	}
	
	function plUploadFile($file, $destination="", $resize=false, $crop=false){
		global $gCms;
		$dir = $gCms->config["uploads_path"].str_replace("//","/","/".$destination."/");
		
		$filename = $this->upload_checkfilename($file["name"],$dir);
		
		if (cms_move_uploaded_file($file["tmp_name"], $dir.$filename)) {
			if($resize && !is_array($resize))	$resize = explode("x",$resize);
			if($resize && count($resize) == 2) $this->plResize($dir.$filename, '', $resize[0], $resize[1],true,$crop);
			return str_replace("//","/","/".$destination."/".$filename);
		}else{
			return false;
		}
	}
	
	function plAssignFile($filepath, $table, $itemid, $field, $thumbsize=false, $cropthumb=false){
		$tablename = cms_db_prefix().'module_'.$table;
		$filepath = str_replace("//","/",$filepath);

		if($thumbsize && !is_array($thumbsize))	$thumbsize = explode("x",$thumbsize);
		if( $filepath != "" && $thumbsize && count($thumbsize) ==2 ) {
			global $gCms;
			$basepath = $gCms->config["uploads_path"];
			$exploded = explode("/",$filepath);
			$exploded[count($exploded)-1] = "plthumb_".$exploded[count($exploded)-1];
			$thumbpath = implode("/",$exploded);
			$this->plResize($basepath."/".$filepath, $basepath."/".$thumbpath, $thumbsize[0], $thumbsize[1], true, $cropthumb);
		}

		$db = $this->GetDb();

		if( $table == $this->GetName()."_multiplefilesfields" ) {
			$newid = $db->GenID($tablename."_seq");
			$query = "INSERT INTO $tablename SET fileid=?, itemid=?, fieldname=?, filepath=?";
			$dbresult = $db->Execute( $query, array($newid, $itemid, $field, $filepath) );
		}else{
			$query = "UPDATE $tablename SET $field=? WHERE id=? LIMIT 1";
			$dbresult = $db->Execute($query,array($filepath,$itemid));
		}
		
		return $dbresult;
	}
	function getFileContent($filename){
		// returns the content of a file
		$filepath = dirname(__FILE__).DIRECTORY_SEPARATOR.$filename;
		if(file_exists($filepath)){
			$fhandle = fopen($filepath, "r+");
			$content = fread($fhandle, filesize($filepath));
			return $content;
		}else{
			return false;
		}
	}

}

?>
