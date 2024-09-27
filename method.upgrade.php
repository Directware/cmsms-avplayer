<?php
if (!isset($gCms)) exit;

$db =& $this->GetDb();
$dict = NewDataDictionary($db);
$tabopt = array("mysql" => "TYPE=MyISAM");

// PART 1: This does the upgrade for the CTLModuleMaker part
// Unfortunately, this is only backward compatible to CTLModuleMaker 1.6.3. I couldn't go any further behind because at that time the maker version wasn't saved anywhere

$oldmaker = $this->GetPreference("makerversion", "veryold");

switch($oldmaker){
	// BEGIN SWITCH($oldmaker)

	case "veryold":
		// the module was created with version 1.6.3 or prior.
		$this->CreateEvent("avplayer_added");
		$this->CreateEvent("avplayer_modified");
		$this->CreateEvent("avplayer_deleted");
		break;
	case "1.6.4":
	case "1.7":
	case "1.7.1":
	case "1.7.2":
	case "1.7.3":
	case "1.7.4":
		
		// option values for dynamic list fields are now all in the same table

		// Create the new fieldoptions table
		$newtable = cms_db_prefix()."module_avplayer_fieldoptions";
		$flds = "
			id I,
			field C(128),
			name C(32),
			item_order I
			";
		$sqlarray = $dict->CreateTableSQL($newtable, $flds, $tabopt);
		$dict->ExecuteSQLArray($sqlarray);
		$db->CreateSequence($newtable."_seq");

		// transfer the existing options
		$listfields = array(array("A", "player", "userdefined1"));
		$maxid = 0;
		foreach($listfields as $field){
			$oldtable = cms_db_prefix()."module_avplayer_".$field[0]."_".$field[2]."_options";
			$dbresult = $db->Execute("SELECT * FROM ".$oldtable);
			$item_order = 1;
			while($dbresult && $row = $dbresult->FetchRow()){
				if($row["id"] > $maxid)	$maxid = $row["id"];
				$newid = $db->GenID($newtable."_seq");
				$query = "INSERT INTO ".$newtable." SET id=?, field=?, name=?, item_order=?";
				$db->Execute($query, array($row["id"], $field[1]."_".$field[2], $row["name"], $item_order));
				$item_order++;
			}
			$sqlarray = $dict->DropTableSQL($oldtable);
			$dict->ExecuteSQLArray($sqlarray);
			$db->DropSequence($oldtable."_seq");
		}
		// we need to make sure that the next options won't have the same ids
		$dummyid = $db->GenID($newtable."_seq");
		while($dummyid <= $maxid){
			$dummyid = $db->GenID($newtable."_seq");
		};
	case "1.8.1":
	case "1.8.2":
	case "1.8.2.2":
	case "1.8.3":
	case "1.8.3.1":
		// Creates the queries table
		$flds = "
			id I,
			name C(64),
			what C(32),
			whereclause C(255),
			wherevalues C(255),
			queryorder C(32)
			";
		$sqlarray = $dict->CreateTableSQL(cms_db_prefix()."module_avplayer_saved_queries", $flds, $tabopt);
		$dict->ExecuteSQLArray($sqlarray);
		$db->CreateSequence(cms_db_prefix()."module_avplayer_saved_queries_seq");
		$this->CreatePermission("avplayer_advanced", "avplayer: Advanced");
	case "1.8.4":
	case "1.8.4.1":
	case "1.8.5":
	case "1.8.5.1":
		$this->CreatePermission("avplayer_normaluser", "avplayer: Normal user");
		// activating default preferences
		$defprefs = array("tabdisplay_player","searchmodule_index_player","newitemsfirst_player","tabdisplay_mediafile","searchmodule_index_mediafile","newitemsfirst_mediafile","restrict_permissions","display_filter","display_instantsearch");
		foreach($defprefs as $onepref)	$this->SetPreference($onepref,true);
	case "1.8.6":
	case "1.8.6.1":
	case "1.8.7":
	case "1.8.7.1":
		if($db->dbtype == "mysql" || $db->dbtype == "mysqli"){
			// msyql
			$queries = array();
			$queries[] = "ALTER TABLE ".cms_db_prefix()."module_avplayer_player ADD COLUMN date_created DATETIME";
			$queries[] = "UPDATE ".cms_db_prefix()."module_avplayer_player SET date_created=date_modified";
			$queries[] = "ALTER TABLE ".cms_db_prefix()."module_avplayer_mediafile ADD COLUMN date_created DATETIME";
			$queries[] = "UPDATE ".cms_db_prefix()."module_avplayer_mediafile SET date_created=date_modified";
			foreach($queries as $query)		mysql_query($query);

		}else{
			// non-mysql
			$dict->AddColumnSQL(cms_db_prefix()."module_avplayer_player", "date_created ".CMS_ADODB_DT);
			$dict->AddColumnSQL(cms_db_prefix()."module_avplayer_mediafile", "date_created ".CMS_ADODB_DT);
			
		}
	case "1.8.7.2":
	case "1.8.8":
	case "1.8.8.1":
	case "1.8.8.2":
	case "1.8.8.3":
	case "1.8.8.4":
		$this->SetPreference("adminpages",0);
		$this->SetPreference("load_nbchildren",true);
		$this->SetPreference("load_nextprevious",false);
	case "1.8.9":
		$this->SetPreference("use_session",true);
		$this->SetPreference("defemptytemplate","**");
		
	// END SWITCH($oldmaker)
}

$this->SetPreference("makerversion", "1.8.9.3");
?>