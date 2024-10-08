<?php
	$lang["friendlyname"] = "AVPlayer";
	$lang["moddescription"] = "Player front end";
	$lang["admindescription"] = "Player front end";

	$lang["pagemenudelimiter"] = "&nbsp;&#124;&nbsp;";
	$lang["pagemenuoverflow"] = "&nbsp;...&nbsp;";
	
// strings for player
$lang["uploadfile"] = "Datei aussuchen und hochladen";
	$lang["player"] = "Player";
	$lang["player_plural"] = "Player";
	$lang["add_player"] = "Add Player";
	$lang["edit_player"] = "Edit Player";
	$lang["filterby_player"] = "Filter by Player";
	$lang["player_location"] = "Location";
	$lang["player_width"] = "Width";
	$lang["player_height"] = "Height";
	$lang["player_parameters"] = "Parameters";
	$lang["player_userdefined1"] = "UserDefined 1";
	$lang["prompt_deleteplayer"] = "You are about to delete this Player (%s)? All children will be lost. Do you wish to continue?";
	
// strings for mediafile
	$lang["mediafile"] = "Mediafile";
	$lang["mediafile_plural"] = "Media";
	$lang["add_mediafile"] = "Add Mediafile";
	$lang["edit_mediafile"] = "Edit Mediafile";
	$lang["filterby_mediafile"] = "Filter by Mediafile";
	$lang["mediafile_flvfile"] = "FLV Mediafile";
	$lang["mediafile_select_flvfile"] = "Select FLV Mediafile";
	$lang["mediafile_mp4file"] = "MP4 Mediafile";
	$lang["mediafile_select_mp4file"] = "Select MP4 Mediafile";
	$lang["mediafile_webmfile"] = "WEBM Mediafile";
	$lang["mediafile_select_webmfile"] = "Select WEBM Mediafile";
	$lang["mediafile_ogvfile"] = "OGG Media File";
	$lang["mediafile_select_ogvfile"] = "Select OGG Media File";
	$lang["mediafile_poster"] = "Poster";
	$lang["mediafile_select_poster"] = "Select Poster";
	$lang["mediafile_mp3file"] = "MP3 Media File";
	$lang["mediafile_select_mp3file"] = "Select MP3 Media File";
	$lang["mediafile_description"] = "Description";
	$lang["prompt_deletemediafile"] = "You are about to delete this Mediafile (%s)? Do you wish to continue?";
	$lang["templatehelp"] = '<div><h3 style="cursor: pointer;" onclick="ctlmm_displaytoggle(this);">&gt; Smarty variables for list template of: player</h3><div class="tplvars_hide"><ul>
	<li>$leveltitle</li>
	<li>$parentobj (if parent is specified)</li>
	<li>$itemlist (array of items)</li>
	<li>$item-&gt;is_selected</li>
	<li>$item-&gt;nbchildren (if loaded)</li>
	<li>$item-&gt;name</li>
	<li>$item-&gt;alias</li>
	<li>$item-&gt;detaillink</li>
	<li>$item-&gt;detailurl</li>
	<li>$item-&gt;location</li>
	<li>$item-&gt;width</li>
	<li>$item-&gt;height</li>
	<li>$item-&gt;parameters</li>
	<li>$item-&gt;userdefined1</li>
	<li>$item-&gt;userdefined1_namevalue</li>
	<li>$item-&gt;isdefault</li>
	<li>$item-&gt;date_modified</li>
	<li>$item-&gt;date_created</li>
	</ul><br/><br/></div></div><div><h3 style="cursor: pointer;" onclick="ctlmm_displaytoggle(this);">&gt; Smarty variables for list template of: mediafile</h3><div class="tplvars_hide"><ul>
	<li>$leveltitle</li>
	<li>$parentobj (if parent is specified)</li>
	<li>$itemlist (array of items)</li>
	<li>$item-&gt;is_selected</li>
	<li>$item-&gt;name</li>
	<li>$item-&gt;alias</li>
	<li>$item-&gt;detaillink</li>
	<li>$item-&gt;detailurl</li>
	<li>$item-&gt;flvfile (file object)</li>
	<li>$item-&gt;mp4file (file object)</li>
	<li>$item-&gt;webmfile (file object)</li>
	<li>$item-&gt;ogvfile (file object)</li>
	<li>$item-&gt;poster (file object)</li>
	<li>$item-&gt;mp3file (file object)</li>
	<li>$item-&gt;description</li>
	<li>$item-&gt;parent_id</li>
	<li>$item-&gt;parent_alias</li>
	<li>$item-&gt;parent_name</li>
	<li>$item-&gt;parentlink</li>
	<li>$tiem-&gt;parenturl</li>
	<li>$item-&gt;isdefault</li>
	<li>$item-&gt;date_modified</li>
	<li>$item-&gt;date_created</li>
	</ul><br/><br/></div></div><div><h3 style="cursor: pointer;" onclick="ctlmm_displaytoggle(this);">&gt; Smarty variables for the detail template</h3><div class="tplvars_hide"><ul>
	<li>$leveltitle</li>
	<li>$previous_item (if loaded)</li>
	<li>$next_item (if loaded)</li>
	<li>$item-&gt;name</li>
	<li>$item-&gt;alias</li>
	<li>$item-&gt;flvfile (file object)</li>
	<li>$item-&gt;mp4file (file object)</li>
	<li>$item-&gt;webmfile (file object)</li>
	<li>$item-&gt;ogvfile (file object)</li>
	<li>$item-&gt;poster (file object)</li>
	<li>$item-&gt;mp3file (file object)</li>
	<li>$item-&gt;description</li>
	<li>$item-&gt;parent_id</li>
	<li>$item-&gt;parent_alias</li>
	<li>$item-&gt;parent_name</li>
	<li>$item-&gt;parentlink</li>
	<li>$tiem-&gt;parenturl</li>
	<li>$item-&gt;isdefault</li>
	<li>$item-&gt;date_modified</li>
	<li>$item-&gt;date_created</li>
	<li>$labels->...</li>
	</ul><br/><p>In the final level detail template, use the object $labels to print language-sensible field labels ($labels->fieldname).</p><p>You may reach the parent objects using $item->parent_object->parent_object->... and so on.</p><br/></div></div>
<div><h3 style="cursor: pointer;" onclick="ctlmm_displaytoggle(this);">&gt; File Object</h3><div class="tplvars_hide">
<ul>
<li>$file-&gt;filepath (relative)</li>
<li>$file-&gt;ext</li>
<li>$file-&gt;url (absolute url)</li>
<li>$file-&gt;size (size in bytes)</li>
<li>$file-&gt;size_wformat (formated size)</li>
<li>$file-&gt;imagesize (for images only)</li>
<li>$file-&gt;width (for images only)</li>
<li>$file-&gt;height (for images only)</li>
<li>$file-&gt;image (image tag; for images only)</li>
<li>$file-&gt;thumbnail (thumbnail url, if applicable)</li>
<li>$file-&gt;filemtime (last modified, unix time)</li>
<li>$file-&gt;modified (last modified, formated time)</li>
</ul><br/><br/></div></div>
<div><h3 style="cursor: pointer;" onclick="ctlmm_displaytoggle(this);">&gt; Breadcrumbs</h3><div class="tplvars_hide">
				<p>When you are in a module template, you may call the breadcrumbs using the {avplayer_breadcrumbs} tag. You may use the same parameters as the cms core breadcrumbs tag (initial, delimiter, classid, currentclassid), as well as the "startlevel" parameter.<br/>
				Outside module templates, anywhere on the page, you may call the breadcrumbs action {cms_module module="avplayer" action="breadcrumbs"}, once again using the same parameters.</p></div></div>';

// For file fields
$lang["Remove"] = "Remove";
$lang["delete"] = "Delete";
$lang["browsefilestitle"] = "Upload a new file or select a file below.";
$lang["showingdir"] = "Showing directory";
$lang["browsefilesresize"] = "The picture will be automatically resized for the module.";
$lang["browsefilecurrentpath"] = "Currently seeing files in : ";
$lang["parentdir"] = "Parent Directory";
$lang["addafile"] = "Add file(s)";
$lang["submitting_file"] = "Please wait while the file is being uploaded...";
$lang["filename"] = "Filename";
$lang["imagesize"] = "Image size";
$lang["fileext"] = "Ext";
$lang["filesize"] = "File size";
$lang["lastmod"] = "Last modified";
$lang["fileowner"] = "Owner";
$lang["fileperms"] = "Permissions";
$lang["viewallimages"] = "View all images";
$lang["postmaxsize"] = "Notice: the post max size is ";
$lang["addfileinput"] = "Add another file";
$lang["zipfilenotice"] = "The files should be in the root of the archive.";
$lang["uploadzipfile"] = "Upload zip archive";

// strings for general fields
$lang["id"] = "id";
$lang["name"] = "Name";
$lang["alias"] = "Alias";
$lang["isdefault"] = "Is default?";
$lang["active"] = "Active";
$lang["parent"] = "Parent";
$lang["submit"] = "Submit";
$lang["cancel"] = "Cancel";
$lang["nbchildren"] = "Nb of items";
$lang["date_modified"] = "Last modification";
$lang["date_created"] = "Creation";
	
// GENERAL
$lang["pages"] = "Pages: ";
$lang["nextpage"] = "Next page";
$lang["previouspage"] = "Previous page";
$lang["activate"] = "Activate";
$lang["unactivate"] = "Turn off";
$lang["searchthistable"] = "Search this table for:";
$lang["resetorder"] = "Reset order";
$lang["Yes"] = "Yes";
$lang["No"] = "No";
$lang["Actions"] = "Actions";
$lang["reorder"] = "Reorder";
$lang["listtemplate"] = "List template for";
$lang["templates"] = "Templates";
$lang["template"] = "Template";
$lang["defaulttemplates"] = "Default templates";
$lang["templatevars"] = "Template variables";
$lang["edittemplate"] = "Edit template";
$lang["deftemplatefor"] = "Default list template for level ";
$lang["defdetailtemplate"] = "Default detail template";
$lang["defsearchresultstemplate"] = "Default template for search results";
$lang["defemptytemplate"] = "Template for empty result sets";
$lang["uselevellisttpl"] = "Use the list template of the appropriate level";
$lang["addtemplate"] = "Add template";
$lang["filterby"] = "Filter by";
$lang["showingonly"] = "Filter: ";
$lang["showall"] = "Show all (no filter)";
$lang["fieldoptions"] = "Field options";
$lang["addoption"] = "Add an option";
$lang["modifyanoption"] = "Modify an option";
$lang["message_deleted"] = "Element deleted";
$lang["message_modified"] = "Modification saved";
$lang["warning_tab"] = "Notice: Save changes in other tabs before working in this one...";
$lang["error_missginvalue"] = "One or more necessary values have not been entered.";
$lang["error_alreadyexists"] = "There is already an element bearing that name.";
$lang["error_date"] = "The date you have entered is invalid.";
$lang["error_noparent"] = "No parent is defined!";
$lang["error_notfound"] = "The item could not be found.";
$lang["error_noitemfound"] = "No item found.";
$lang["error_denied"] = "Permission denied";
$lang["error_wrongquery"] = "Invalid query.";
$lang["error_feadddenied"] = "You do not have sufficient access to continue.";
$lang["error_wrongfiletype"] = "This type of file is not allowed here.";
$lang["error_captcha"] = "The text given does not match the captcha image.";
$lang["error_filetoobig"] = "The file is to big to be uploaded here.";
$lang["givenerror"] = "Error: ";
$lang["finaltemplate"] = "Display template for final level (mediafile)";
$lang["prompt_deleteoption"] = "Do you really want to delete this option?";
$lang["prompt_generaldelete"] = "Do you really want to delete this?";
$lang["prompt_captcha"] = "Enter the text from the image.";
$lang["queries"] = "Queries";
$lang["query"] = "Query";
$lang["results"] = "Results";
$lang["createquery"] = "Create a new query";
$lang["prompt_query"] = "Create a query for which level?";
$lang["orderbyfield"] = "Order by";
$lang["date_modified"] = "Date modified";
$lang["frontend_submit"] = "Submit";
$lang["insert_tag"] = "Usage...";

// BREADCRUMBS :
$lang["youarehere"] = "You are here: ";
$lang["breadcrumbs_delimiter"] = " &gt; ";

// SEARCH :
$lang["searchtitle"] = "Search";
$lang["searchagain"] = "Do another search";
$lang["searchbtn"] = "Search!";
$lang["contains"] = "Contains";
$lang["isexactly"] = "Is exactly";
$lang["isnot"] = "Is not";
$lang["ishigherthan"] = "Is higher than";
$lang["islowerthan"] = "Is lower than";
$lang["isafter"] = "Is after";
$lang["isbefore"] = "Is before";
$lang["isbetween"] = "Is between";
$lang["thisandthis"] = "and";
$lang["queryuse"] = "Not used";
$lang["queryname"] = "Query name";

// IMPORT / EXPORT
$lang["export_title"] = "Export items to xml";
$lang["exportwhichlevels"] = "Which tables should be exported?";
$lang["export_templates"] = "Export templates too?";
$lang["import_title"] = "Import from xml file";
$lang["import_fileprompt"] = "Select xml file from which to import.";
$lang["import_done"] = "Import done. %s records where imported.";
$lang["import_entries"] = "The file contains %s records.";
$lang["import_delete"] = "Delete table content before importing?";
$lang["importwhichlevels"] = "Which tables should be imported?";
$lang["import_templates"] = "Import templates too?";
$lang["couldnotimport"] = "There was an error importing records for: ";
$lang["error_invalidxml"] = "The file you provided is not a valid xml export for this module.";

// MODULE INTERACTION
$lang["postinstall"] = "Module successfully added.<br/>If this module has any file fields, please make sure that the appropriate directories exist in the uploads folder. Don't forget to give your users the permission to use this module!";
$lang["postuninstall"] = "Module successfully removed.";
$lang["really_uninstall"] = "All of this module's content will be lost. Continue?";
$lang["uninstalled"] = "Module Uninstalled.";
$lang["installed"] = "Module version %s installed.";
$lang["help"] = "<h3>What Does This Do?</h3>
				<p>This module manages and displays an item catalogue.</p>
			<br/><h3>How Do I Use It?</h3>
				<br/><h4>Permissions</h4>
					<p>Make sure your users have the appropriate permissions. If you are not using the access restriction option (see settings), the \"avplayer: Normal user\" permission will be enough to use the module. In the settings tab, you can choose which tabs will be displayed for the normal user. Only the administrator and users with the \"avplayer: Advanced\" permission will have access to settings tab.<br/>
					If you wish to define level-specific permissions, activate the access restriction option in the settings tab and give the \"avplayer: Manage name_of_level\" permission to the appropriate users.</p>
				<br/><h4>Basics</h4>
					<p>To call the module, simply use the following tag:<br/>
					{cms_module module=\"avplayer\"}</p>
					<p>In this case the list of the last level elements (mediafile) will be displayed. To select a level, use the \"what\" parameter:<br/>
					{cms_module module=\"avplayer\" what=\"mediafile\"}<br/>
					<i>The possible values for the \"what\" parameter are : player, mediafile</i></p>
					<p>You may also ask for elements who belong to a specific parent:<br/>
					{cms_module module=\"avplayer\" parent=\"alias_of_parent\"}</p>
					<p>You may finally ask for a specific element:<br>
					{cms_module module=\"avplayer\" alias=\"alias_of_item\"}</p>
				<br/><h4>Separating into pages</h4>
					<p>You may limit the number of items to be shown on one page:<br/>
					{cms_module module=\"avplayer\" nbperpage=\"5\"}<br/>
					In the template, you can build the page menu using the following tags:<br/>
					{".'$'."page_showing}, {".'$'."page_totalitems}, {".'$'."page_pagenumbers}, {".'$'."page_next}, {".'$'."page_previous}</p>
				<br/><h4>Using queries</h4>
					<p>To get a list of item that meet specific criteria, you can create a query using the queries tab of the admin panel, and call them using the \"query\" parameter:<br/>
					{cms_module module=\"avplayer\" query=\"5\"}</p>
					<p><i>If the option to allow manuel sql queries is enabled</i> (see \"Settings\" tab), you may also provide the sql query directly with the query parameter. For example:<br/>
					{cms_module module=\"avplayer\" what=\"mediafile\" query=\"A.date_modified > '2009-03-15' AND A.active = 1\"}<br/>
					The query parameter can only hold the WHERE clause of the query, and should not include the WHERE command itself. To avoid problems, the prefix \"A.\" should be used before field names (where applicable, the \"B.\" prefix can be used to specify criteria on the parent's fields).<br/>
					Although the abstraction layer is not easily prone to injections, remember that activating this option means giving a sql opening within the templates and be careful.</p>
				<br/><h4>The link action</h4>
					<p>You may use the action <b>\"link\"</b> to create a link to the default action, using the same parameters:<br/>
					{cms_module module=\"avplayer\" action=\"link\" what=\"mediafile\" random=\"1\"}<br/>
					would create a link to a random element of this level.</p>
				<br/><h4>The sitemap action</h4>
					<p>If your module is not sharing children, you may use the action <b>\"sitemap\"</b> to create a module sitemap:<br/>
					{cms_module module=\"avplayer\" action=\"sitemap\"}<br/>
					You can select the levels using the \"what\" parameter, and may select more than one using \"|\" : what=\"level1|level2\".<br/>
					Other available parameters for this action are \"detailpage\" and \"inline\".<br/>
					To create a google sitemap, see the <a href=\"../modules/avplayer/doc/faq.html#q16\" target=\"_blank\">FAQ</a>.</p>
				<br/><h4>The frontend edit action</h4>
					<p>A form for the frontend edition of elements can be called using:<br/>
					{cms_module module=\"avplayer\" action=\"frontend_edit\" what=\"mediafile\"}<br/>
					This tag would show an empty form, and create a new element of the level \"mediafile\". In order to edit an existing element, you simply have to specify the alias:<br/>
					{cms_module module=\"avplayer\" action=\"frontend_edit\" what=\"mediafile\" alias=\"item_alias\"}</p>
					<p>If you wish to display a link to the edition of an item, use the \"link\" action:<br/>
					{cms_module module=\"avplayer\" action=\"link\" toaction=\"frontend_edit\" what=\"mediafile\" alias=\"item_alias\"}<br/>
					You could, for example, do this in a list template...</p>
					<p>(See the settings tab for options about the frontend_edit feature)<br/>
					It is recommanded that the frontend edit feature be used with the Front End Users module for permissions management. See <a href=\"../modules/avplayer/doc/faq.html#q20\" target=\"_blank\">FAQ</a> for more information on how to do this.</p>
				<br/><h4>The search action</h4>
					<p>You may use the action <b>\"search\"</b> to display a search form:<br/>
					{cms_module module=\"avplayer\" action=\"search\"}<br/>
					Use the \"searchmode\" parameter to switch between advanced (default) and simple mode. You may specify the level in which to search using the \"what\" parameter. You cannot use the advanced search mode in all levels at the same time.<br/>
					The following parameters can be used with the search action: what, limit, nbperpage, orderby, detailpage, listtemplate, inline, searchmode.<br/>
					See avplayer/templates/search.tpl to modify the search form.</p><br/>
				<p>For more help, you may take a look at the <a href=\"../modules/avplayer/doc/faq.html\" target=\"_blank\">FAQ</a>.</p>
			<br/><h3>Copyright and License</h3>
				<p>This module has been created with CTLModuleMaker, and is released under the GNU Public License.</p><br/><br/>";

//EVENTS
$lang["eventdesc_modified"] = "Called after an element has been modified. Params: \"what\"=>level of the element, \"itemid\"=>id of the element, \"alias\"=>alias of the element.";
$lang["eventdesc_deleted"] = "Called after an element has been deleted. Params: \"what\"=>level of the element.";
$lang["eventdesc_added"] = "Called after an element has been added. Params: \"what\"=>level of the element, \"itemid\"=>id of the element, \"alias\"=>alias of the element.";

//PREFERENCES
$lang["preferences"] = "Settings";
$lang["pref_tabdisplay"] = "Admin tabs shown to the normal user";
$lang["help_tabdisplay"] = "(Normal users are those who have been given the \"avplayer: normal user\" permission. The level-specific manage permissions override the visible tabs selected here.)";
$lang["pref_searchmodule_index"] = "Index the following levels for the search module";
$lang["help_searchmodule_index"] = "(This has no effect on this module own internal search action. Also note that after changing these settings you might have to reindex to get of previously indexed data, which you may do in Extension-&gt;Search.)";
$lang["pref_newitemsfirst"] = "New items should appear on top for the following levels";
$lang["help_newitemsfirst"] = "(Obviously, if the level is not checked here, new items will appear at the bottom of the list)";
$lang["pref_restrict_permissions"] = "Should we <b>restrict</b> the management of items to the users who have specific management permissions for the level? (if you do not wish to specify different permissions for different levels, you can uncheck this and simply use the \"avplayer: normal user\" permission.)";
$lang["pref_display_filter"] = "Display <b>filter</b> on the module admin panel?";
$lang["pref_display_instantsearch"] = "Display <b>instant search</b> on the module admin panel?";
$lang["pref_display_instantsort"] = "Display <b>instant sort</b> by column links?";
$lang["pref_editable_aliases"] = "Should the <b>alias</b> of items be manually editable?";
$lang["pref_force_list"] = "Should the <b>forcelist</b> parameter be enabled by default? (list view will be shown even when there is only one item)";
$lang["pref_delete_files"] = "When an item is <b>deleted</b>, should the associated files be also deleted? (provided the item has any file fields...)";
$lang["pref_allow_sql"] = "Allow manual SQL queries? (with the \"query\" parameter)";
$lang["pref_use_hierarchy"] = "Display full hierarchy in filter and parent dropdown? (admin panel)";
$lang["pref_orderbyname"] = "Order parent dropdowns by name?";
$lang["pref_showthumbnails"] = "Display all thumbnails instead of a table when selected image files?";
$lang["pref_maxshownpages"] = "Max number of pages displayed in the page menu: ";
$lang["pref_autoincrement_alias"] = "Auto-increment aliases in case of identical names?";
$lang["pref_decodeentities"] = "Decode html entities on frontend form submission.";
$lang["pref_frontend"] = "Frontend edit";
$lang["help_frontend"] = "For security, frontend edition should be used with the Front End Users module. See <a href=\"modules/avplayer/doc/faq.html#q20\" target=\"_blank\">Faq</a> for help on permission integration.";
$lang["pref_fe_wysiwyg"] = "Enable wysiwyg in the frontend (must be enabled in the Global Settings too)";
$lang["pref_fe_decodeentities"] = "Decode html entities from frontend forms. This is not recommanded.";
$lang["pref_fe_allowfiles"] = "Allow file upload from the frontend.";
$lang["pref_fe_allownamechange"] = "Allow frontend users to change the name (and through it the alias) of the item they are editing.";
$lang["pref_fe_allowaddnew"] = "Allow frontend users to add new elements.";
$lang["pref_fe_usecaptcha"] = "Use captcha? (module Captcha required)";
$lang["pref_fe_aftersubmit"] = "Once the form is submitted, redirect to ";
$lang["pref_fe_maxfilesize"] = "Maximum size, in bytes, of the files uploaded through the frontend (cannot be over php.ini limit)";
$lang["pref_allow_complex_order"] = "Allow complex \"orderby\" parameters (beta).";
$lang["pref_adminpages"] = "Number of items per page in the admin panel (0 = pas de pagination).";
$lang["pref_load_nbchildren"] = "Load the number of children (item-&gt;nbchildren) for all items?";
$lang["pref_load_nextprevious"] = "Load next/previous links? (detail view only)";
$lang["pref_use_session"] = "Use session to store admin panel filters.";
$lang["pref_levelpagination"] = "Default number of element per page for the frontend";
$lang["help_levelpagination"] = "(Empty or 0 for will disable default pagination. The nbperpage parameter overrides these settings.)";

//PARAMETERS
$lang["phelp_action"] = "Possible actions: \"link\", \"search\", \"breadcrumbs\", \"default\", \"sitemap\" and \"frontend_edit\".";
$lang["phelp_what"] = "Allows you to specify the level you wish to display. Possible values are : <i>player, mediafile</i>";
$lang["phelp_alias"] = "Alias of the item you wish to display.";
$lang["phelp_parent"] = "If you wish to limit the displayed elements to those who belong to a specific parent, enter the parent alias here.";
$lang["phelp_limit"] = "Limit the number of item returned by the query (0 = no limit)";
$lang["phelp_nbperpage"] = "Set the number of items displayed on each page.";
$lang["phelp_orderby"] = "You can set to \"modified\", \"created\" or the name of a field to order items in this way. Any other value will order with the item order.";
$lang["phelp_detailpage"] = "Specify the alias of the page in which links to child elements should be sent (if none is specified, current page is used)";
$lang["phelp_showdefault"] = "Set to \"true\" if you wish to display the default element.";
$lang["phelp_random"] = "Set to a number to show a number of random elements from your query.";
$lang["phelp_finaltemplate"] = "Specify the template you wish to use for the detail view of the final level.";
$lang["phelp_listtemplate"] = "Specify the template you wish to use for the list view.";
$lang["phelp_forcelist"] = "Set to 1 if you wish to display a list view even when there is only one element.";
$lang["phelp_internal"] = "For internal use; specify the page (when using nbperpage).";
$lang["phelp_query"] = "For optional use with the \"default\" action. Specifies the id of the query to use.";
$lang["phelp_inline"] = "Makes the links inline.";
$lang["phelp_searchmode"] = "For use with the \"search\" action. Set to \"simple\" to search in all text fields, and \"advanced\" to search in specific fields.";
$lang["phelp_toaction"] = "For use with the \"link\" action. Sets the action to which the link should redirect.";

?>