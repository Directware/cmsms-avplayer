<?php
if(!isset($gCms)) exit;

// Typical Database Initialization
$db =& $this->GetDb();
$dict = NewDataDictionary($db);
		
// mysql-specific, but ignored by other database
$taboptarray = array("mysql" => "TYPE=MyISAM");
		

// Creates the player table
$flds = "
	location C(255),
	width C(10),
	height C(10),
	parameters C(255),
	userdefined1 I,
	id I KEY,
	name C(64),
	alias C(64),
	item_order I,
	active L,
	isdefault L,
    date_modified ".CMS_ADODB_DT.",
	date_created ".CMS_ADODB_DT."
	";

$sqlarray = $dict->CreateTableSQL(cms_db_prefix()."module_avplayer_player", $flds, $taboptarray);
$dict->ExecuteSQLArray($sqlarray);
$db->CreateSequence(cms_db_prefix()."module_avplayer_player_seq");


// Creates the mediafile table
$flds = "
	flvfile C(255),
	mp4file C(255),
	webmfile C(255),
	ogvfile C(255),
	poster C(255),
	mp3file C(255),
	description X,
	parent I,
	id I KEY,
	name C(64),
	alias C(64),
	item_order I,
	active L,
	isdefault L,
    date_modified ".CMS_ADODB_DT.",
	date_created ".CMS_ADODB_DT."
	";

$sqlarray = $dict->CreateTableSQL(cms_db_prefix()."module_avplayer_mediafile", $flds, $taboptarray);
$dict->ExecuteSQLArray($sqlarray);
$db->CreateSequence(cms_db_prefix()."module_avplayer_mediafile_seq");


// Create the fieldoptions table
$flds = "
	id I,
	field C(128),
	name C(32),
	item_order I
	";
$sqlarray = $dict->CreateTableSQL(cms_db_prefix()."module_avplayer_fieldoptions", $flds, $tabopt);
$dict->ExecuteSQLArray($sqlarray);
$db->CreateSequence(cms_db_prefix()."module_avplayer_fieldoptions_seq");



// Creates the queries table
$flds = "
    id I,
	name C(64),
	what C(32),
	whereclause C(255),
	wherevalues C(255),
	queryorder C(32)
	";

$sqlarray = $dict->CreateTableSQL(cms_db_prefix()."module_avplayer_saved_queries", $flds, $taboptarray);
$dict->ExecuteSQLArray($sqlarray);
$db->CreateSequence(cms_db_prefix()."module_avplayer_saved_queries_seq");


// INSERTING DEFAULT TEMPLATES
	$template = '<h2>{$leveltitle}</h2>
{if $itemcount > 0}
<ul>
{foreach from=$itemlist item="item"}
	<li {if $item->is_selected}class="active"{/if}>{$item->detaillink}</li>
{/foreach}
</ul>
{if $page_pagenumbers}
<div id="pagemenu" style="text-align: center;">
{$page_previous}&nbsp; {$page_showing}/{$page_totalitems} &nbsp;{$page_next}<br/>
{$page_pagenumbers}
</div>
{/if}
{else}
<p>{$error_msg}</p>
{/if}
';
$this->SetTemplate("list_default",$template,$this->GetName());
    $this->SetPreference("listtemplate_player","list_default");
    $this->SetPreference("listtemplate_mediafile","list_default");

$template = '<h3>{$item->name}</h3>
<div class="video-js-box">
<video class="video-js vjs-default-skin" controls preload="auto" width="{$item->parent_object->width}"  height="{$item->parent_object->height}" poster="{$item->poster->url}" data-setup=\'\'>
{if $item->mp4file != ""}<source src="{root_url}/uploads{$item->mp4file->filepath}" type="video/mp4" />{/if}
{if $item->webmfile != ""}<source src="{root_url}/uploads{$item->webmfile->filepath}" type="video/webm" />{/if}
{if $item->ogvfile != ""}<source src="{root_url}/uploads{$item->ogvfile->filepath}" type="video/ogg" />{/if}
<p>{$labels->description}:<br/>{$item->description}</p>
</video>
</div>

';

$this->SetTemplate("final_default",$template,$this->GetName());
$this->SetPreference("finaltemplate","final_default");

$template = '<p>{$error_msg}</p>
{if $backlink}<p>{$backlink}</p>{/if}';
$this->SetTemplate("no_result",$template,$this->GetName());

// CREATING PERMISSIONS :

// permissions
$this->CreatePermission("avplayer_normaluser", "avplayer: Normal user");
$this->CreatePermission("avplayer_advanced", "avplayer: Advanced");
	$this->CreatePermission("avplayer_manage_player", "avplayer: Manage player");
	$this->CreatePermission("avplayer_manage_mediafile", "avplayer: Manage mediafile");
// activating default preferences
	$defprefs = array("tabdisplay_player","searchmodule_index_player","newitemsfirst_player","tabdisplay_mediafile","searchmodule_index_mediafile","newitemsfirst_mediafile","restrict_permissions","orderbyname","display_filter","display_instantsearch","display_instantsort","showthumbnails","load_nbchildren","use_session");
	foreach($defprefs as $onepref)	$this->SetPreference($onepref,true);
	$this->SetPreference("emptytemplate","**");

// events
	$this->CreateEvent("avplayer_added");
	$this->CreateEvent("avplayer_modified");
	$this->CreateEvent("avplayer_deleted");
	
// prepare information for an eventual upgrade
	$this->SetPreference("makerversion","1.8.9.3");

// put mention into the admin log
	$this->Audit( 0, $this->Lang("friendlyname"), $this->Lang("installed",$this->GetVersion()));

?>
