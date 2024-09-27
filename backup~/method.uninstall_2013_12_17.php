<?php
if(!isset($gCms)) exit;

// Typical Database Initialization

$db =& $this->GetDb();
$dict = NewDataDictionary($db);



	$sqlarray = $dict->DropTableSQL(cms_db_prefix()."module_avplayer_player");
	$dict->ExecuteSQLArray($sqlarray);
	$db->DropSequence(cms_db_prefix()."module_avplayer_player_seq");
	

	$sqlarray = $dict->DropTableSQL(cms_db_prefix()."module_avplayer_mediafile");
	$dict->ExecuteSQLArray($sqlarray);
	$db->DropSequence(cms_db_prefix()."module_avplayer_mediafile_seq");
	
	$sqlarray = $dict->DropTableSQL(cms_db_prefix()."module_avplayer_fieldoptions");
	$dict->ExecuteSQLArray($sqlarray);
	$db->DropSequence(cms_db_prefix()."module_avplayer_fieldoptions_seq");
	

	//$sqlarray = $dict->DropTableSQL(cms_db_prefix()."module_avplayer_templates");
	//$dict->ExecuteSQLArray($sqlarray);
	$this->DeleteTemplate("",$this->GetName());
	
	$sqlarray = $dict->DropTableSQL(cms_db_prefix()."module_avplayer_saved_queries");
	$dict->ExecuteSQLArray($sqlarray);
	$db->DropSequence(cms_db_prefix()."module_avplayer_saved_queries_seq");

// permissions
	$this->RemovePermission("avplayer_manage_player");
	$this->RemovePermission("avplayer_manage_mediafile");
	$this->RemovePermission("avplayer_advanced");
	$this->RemovePreference();
	
// events
	$this->RemoveEvent("avplayer_added");
	$this->RemoveEvent("avplayer_modified");
	$this->RemoveEvent("avplayer_deleted");

// put mention into the admin log
	$this->Audit( 0, $this->Lang("friendlyname"), $this->Lang("uninstalled"));

?>
