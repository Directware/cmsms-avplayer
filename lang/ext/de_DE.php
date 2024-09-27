<?php

	$lang["friendlyname"] = "AVPlayer";

	$lang["moddescription"] = "Player Front End";

	$lang["admindescription"] = "Player Front End";



	$lang["pagemenudelimiter"] = "&nbsp;&#124;&nbsp;";

	$lang["pagemenuoverflow"] = "&nbsp;...&nbsp;";

	

// strings for player
    $lang["uploadfile"] = "Datei aussuchen und hochladen";

	$lang["player"] = "Player";

	$lang["player_plural"] = "Player";

	$lang["add_player"] = "Player hinzufügen";

	$lang["edit_player"] = "Player bearbeiten";

	$lang["filterby_player"] = "Nach Playern filtern";

	$lang["player_location"] = "Speicherplatz";

	$lang["player_width"] = "Breite";

	$lang["player_height"] = "Höhe";

	$lang["player_parameters"] = "Parameters (trennen mit &amp;)";

	$lang["player_userdefined1"] = "Selbstdefiniertes Feld 1";

	$lang["player_playertype"] = "Player Typ";

	$lang["player_playertype_option_0"] = "mp3";

	$lang["player_playertype_option_1"] = "flv";

	$lang["prompt_deleteplayer"] = "Wollen Sie diesen Player (%s) wirklich löschen? Alle 'children' werden verlorengehen. Weitermachen?";

	

// strings for Mediendatei

	$lang["mediafile"] = "Mediadatei";

	$lang["mediafile_plural"] = "Mediendateien";

	$lang["add_mediafile"] = "Mediadatei hinzufügen";

	$lang["edit_mediafile"] = "Mediendatei bearbeiten";

	$lang["filterby_mediafile"] = "Mit Mediadatei filten";

	$lang["mediafile_mediafile"] = "Mediadatei";

	$lang["mediafile_select_mediafile"] = "Mediendatei auswählen";

	$lang["mediafile_picture"] = "Thumbnail";

	$lang["mediafile_select_picture"] = "Thumbnail auswählen";

	$lang["mediafile_userdefined2"] = "Selbstdefiniertes Feld  2";

	$lang["prompt_deletemediafile"] = "Wollen Sie diese Mediadatei (%s) wirklich löschen? Weitermachen?";

	$lang["templatehelp"] = '<div><h3 style="cursor: pointer;" onclick="ctlmm_displaytoggle(this);">&gt; Smarty Variablen für Listen-Vorlage für: player</h3><div class="tplvars_hide"><ul>

	<li>$leveltitle</li>

	<li>$parentobj (falls parent definiert ist)</li>

	<li>$itemlist (Array der Einträge)</li>

	<li>$item-&gt;is_selected</li>

	<li>$item-&gt;nbchildren (wenn geladen)</li>

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

	<li>$item-&gt;playertype</li>

	<li>$item-&gt;playertype_namevalue</li>

	<li>$item-&gt;isdefault</li>

	<li>$item-&gt;date_modified</li>

	<li>$item-&gt;date_created</li>

	</ul><br/><br/></div></div><div><h3 style="cursor: pointer;" onclick="ctlmm_displaytoggle(this);">&gt; Smarty Variablen für Listenvorlage für: Mediendatei</h3><div class="tplvars_hide"><ul>

	<li>$leveltitle</li>

	<li>$parentobj (falls parent definiert ist)</li>

	<li>$itemlist (Array der Einträge)</li>

	<li>$item-&gt;is_selected</li>

	<li>$item-&gt;name</li>

	<li>$item-&gt;alias</li>

	<li>$item-&gt;detaillink</li>

	<li>$item-&gt;detailurl</li>

	<li>$item-&gt;mediafile (Datei-Objekt)</li>

	<li>$item-&gt;picture (Datei-Objekt)</li>

	<li>$item-&gt;userdefined2</li>

	<li>$item-&gt;userdefined2_namevalue</li>

	<li>$item-&gt;parent_id</li>

	<li>$item-&gt;parent_alias</li>

	<li>$item-&gt;parent_name</li>

	<li>$item-&gt;parentlink</li>

	<li>$tiem-&gt;parenturl</li>

	<li>$item-&gt;isdefault</li>

	<li>$item-&gt;date_modified</li>

	<li>$item-&gt;date_created</li>

	</ul><br/><br/></div></div><div><h3 style="cursor: pointer;" onclick="ctlmm_displaytoggle(this);">&gt; Smarty Variablen für die Detail-Vorlage</h3><div class="tplvars_hide"><ul>

	<li>$leveltitle</li>

	<li>$previous_item (wenn geladen)</li>

	<li>$next_item (wenn geladen)</li>

	<li>$item-&gt;name</li>

	<li>$item-&gt;alias</li>

	<li>$item-&gt;mediafile (Datei-Objekt)</li>

	<li>$item-&gt;picture (Datei-Objekt)</li>

	<li>$item-&gt;userdefined2</li>

	<li>$item-&gt;userdefined2_namevalue</li>

	<li>$item-&gt;parent_id</li>

	<li>$item-&gt;parent_alias</li>

	<li>$item-&gt;parent_name</li>

	<li>$item-&gt;parentlink</li>

	<li>$tiem-&gt;parenturl</li>

	<li>$item-&gt;isdefault</li>

	<li>$item-&gt;date_modified</li>

	<li>$item-&gt;date_created</li>

	<li>$labels->...</li>

	</ul><br/><p>Im "final level detail template" können Sie das Objekt $labels nutzen, um sprach-spezifische Feld-labels auszugeben  ($labels->fieldname).</p><p>Das Parent-Objekt erhalten Sie mit $item->parent_object->parent_object->... und so weiter.</p><br/></div></div>

<div><h3 style="cursor: pointer;" onclick="ctlmm_displaytoggle(this);">&gt; Datei Objekt</h3><div class="tplvars_hide">

<ul>

<li>$file-&gt;filepath (relativ)</li>

<li>$file-&gt;ext</li>

<li>$file-&gt;url (absolute URL)</li>

<li>$file-&gt;size (Größe in bytes)</li>

<li>$file-&gt;size_wformat (formatierte Größe)</li>

<li>$file-&gt;imagesize (nur für Bild-Dateien)</li>

<li>$file-&gt;width (nur für Bild-Dateien)</li>

<li>$file-&gt;height (nur für Bild-Dateien)</li>

<li>$file-&gt;image (Image Tag; nur für Bild-Dateien)</li>

<li>$file-&gt;thumbnail (Thumbnail Url, wenn notwendig)</li>

<li>$file-&gt;filemtime (letzte Änderung, Unix Zeitformat)</li>

<li>$file-&gt;modified (letzte Änderung, formatiertes Zeitformat)</li>

</ul><br/><br/></div></div>

<div><h3 style="cursor: pointer;" onclick="ctlmm_displaytoggle(this);">&gt; Breadcrumbs</h3><div class="tplvars_hide">

				<p>In einer Modul-Vorlage können Sie die "Breadcrumbs-Navigation" mit dem  {avplayer_breadcrumbs} Tag nutzen. Sie können de gleichen Parameter wie beim "Core"-Breadcrumbs-Tag nutzen  (initial, delimiter, classid, currentclassid), genau wie auch den  "startlevel" Parameter.<br/>

				Außerhalb der Modul-Vorlagen, irgendwo auf der Seite, können Sie die Breadcrumbs-Funktionalität <i>{cms_module module="avplayer" action="breadcrumbs"}</i> einsetzen, auch wieder mit den gleichen Parametern..</p></div></div>';



// For file fields

$lang["Remove"] = "Entfernen";

$lang["delete"] = "Löschen";

$lang["browsefilestitle"] = "Neue Datei hochladen oder aus vorhandenen Dateien auswählen.";

$lang["showingdir"] = "Verzeichnis anschauen";

$lang["browsefilesresize"] = "Das Bild wird automatisch in die richtige Größe gewandelt.";

$lang["browsefilecurrentpath"] = "Es werde Dateien angezeigt aus: ";

$lang["parentdir"] = "Übergeordnetes Verzeichnis";

$lang["addafile"] = "Datei(en) hinzufügen";

$lang["submitting_file"] = "Datei wird hochgeladen...";

$lang["filename"] = "Dateiname";

$lang["imagesize"] = "Bildgröße";

$lang["fileext"] = "Ext";

$lang["filesize"] = "Dateigröße";

$lang["lastmod"] = "zuletzt geändert";

$lang["fileowner"] = "Eigentümer";

$lang["fileperms"] = "Rechte";

$lang["viewallimages"] = "Alle Bilder anzeigen";

$lang["postmaxsize"] = "Beachten: Sie die maximale Dateigröße zum Hochladen beträgt ";

$lang["addfileinput"] = "Weitere Datei hinzufügen";

$lang["zipfilenotice"] = "Die Dateien sollten sich im Archiv-Hauptverzeichnis befinden.";

$lang["uploadzipfile"] = "ZIP-Datei hochladen";



// strings for general fields

$lang["id"] = "id";

$lang["name"] = "Name";

$lang["alias"] = "Alias";

$lang["isdefault"] = "Default??";

$lang["active"] = "Activ";

$lang["parent"] = "Parent";

$lang["submit"] = "absenden";

$lang["cancel"] = "abbrechen";

$lang["nbchildren"] = "Anzahl Einträge";

$lang["date_modified"] = "Letzte Änderung";

$lang["date_created"] = "Erstelldatum";

	

// GENERAL

$lang["pages"] = "Seiten: ";

$lang["nextpage"] = "Nächste Seite";

$lang["previouspage"] = "Vorherige Seite";

$lang["activate"] = "Aktivieren";

$lang["unactivate"] = "Abschalten";

$lang["searchthistable"] = "Liste durchsuchen nach:";

$lang["resetorder"] = "Sortierfolge zurücksetzen";

$lang["Yes"] = "JA";

$lang["No"] = "NEIN";

$lang["Actions"] = "Actionen";

$lang["reorder"] = "Neu ordnen";

$lang["listtemplate"] = "Vorlagen anzeigen für";

$lang["templates"] = "Vorlagen";

$lang["template"] = "Vorlage";

$lang["defaulttemplates"] = "Standard-Vorlagen";

$lang["templatevars"] = "Vorlagen-Variablen";

$lang["edittemplate"] = "Vorlage bearbeiten";

$lang["deftemplatefor"] = "Standard-Listen-Vorlage für Level ";

$lang["defdetailtemplate"] = "Standard-Einzel-Vorlage";

$lang["defsearchresultstemplate"] = "Standard-Vorlage für Suchergebnisse";

$lang["defemptytemplate"] = "Vorlage für leere Ergebnisliste";

$lang["uselevellisttpl"] = "Benutzen Sie die Listenvorlage für das entsprechende Level";

$lang["addtemplate"] = "Vorlage hinzufügen";

$lang["filterby"] = "Filtern nach";

$lang["showingonly"] = "Filter: ";

$lang["showall"] = "Alle anzeigen (kein FIlter)";

$lang["fieldoptions"] = "Feld Optionen";

$lang["addoption"] = "Option hinzufügen";

$lang["modifyanoption"] = "Option ändern";

$lang["message_deleted"] = "Element wurde gelöscht";

$lang["message_modified"] = "Änderung wurde gespeichert";

$lang["warning_tab"] = "Achtung: Sichern Sie erst die Änderungen in den anderen Tabs, bevor Sie hier weiterarbeiten ...";

$lang["error_missginvalue"] = "Ein oder mehrere Muß-Felder sind leer.";

$lang["error_alreadyexists"] = "Es gibt schon ein Element mit diesem Namen.";

$lang["error_date"] = "Das eingegebene Datum ist ungültig.";

$lang["error_noparent"] = "Kein Eltern-Element definiert!";

$lang["error_notfound"] = "Das Element wurde nicht gefunden.";

$lang["error_noitemfound"] = "Nichts gefunden.";

$lang["error_denied"] = "Dazu sind Sie nicht berechtigt";

$lang["error_wrongquery"] = "Ungültige Abfrage.";

$lang["error_feadddenied"] = "Sie sind nicht ausreichend berechtigt um hier weiterzuarbeiten.";

$lang["error_wrongfiletype"] = "Dieser Dateityp ist nicht zugelassen.";

$lang["error_captcha"] = "Der von Ihnen eingegebene Text passt nicht zum Captcha-Bild.";

$lang["error_filetoobig"] = "Die Datei ist zu groß, sie überschreitet die erlaubte Dateigröße zum Hochladen.";

$lang["givenerror"] = "Fehler: ";

$lang["finaltemplate"] = "Vorlage für Finales Level anzeigen (??)  (Mediedatei)";

$lang["prompt_deleteoption"] = "Wollen Sie diese Option wirklich löschen?";

$lang["prompt_generaldelete"] = "Wollen Sie dies wirklich löschen?";

$lang["prompt_captcha"] = "Geben Sie den im Bild angezeigten Text ein.";

$lang["queries"] = "Abfragen";

$lang["query"] = "Abfrage";

$lang["results"] = "Ergebnis";

$lang["createquery"] = "Neue Abfrage erstellen";

$lang["prompt_query"] = "Für welches Level soll die Abfrage erstellt werden?";

$lang["orderbyfield"] = "Sortierne nach";

$lang["date_modified"] = "Datum wurde geändert";

$lang["frontend_submit"] = "Absenden";

$lang["insert_tag"] = "Usage";



// BREADCRUMBS :

$lang["youarehere"] = "Hier sind Sie gerade: ";

$lang["breadcrumbs_delimiter"] = " &gt; ";



// SEARCH :

$lang["searchtitle"] = "Suche";

$lang["searchagain"] = "Erneut suchen";

$lang["searchbtn"] = "Suchen!";

$lang["contains"] = "enthält";

$lang["isexactly"] = "ist genau";

$lang["isnot"] = "ist nicht";

$lang["ishigherthan"] = "ist größer als";

$lang["islowerthan"] = "ist kleiner as";

$lang["isafter"] = "steht nach";

$lang["isbefore"] = "steht bevor";

$lang["isbetween"] = "steht zwischen";

$lang["thisandthis"] = "und";

$lang["queryuse"] = "Nicht benutzt";

$lang["queryname"] = "Name der Abfrage";



// IMPORT / EXPORT

$lang["export_title"] = "Einträge als XML-Datei exportieren";

$lang["exportwhichlevels"] = "Welche Tabellen sollen exportiert werden?";

$lang["export_templates"] = "Vorlagen auch exportieren?";

$lang["import_title"] = "Einträge aus XML-Datei importieren";

$lang["import_fileprompt"] = "Wählen Sie die zu importierende XML-Datei aus.";

$lang["import_done"] = "Import durchgeführt. %s Datensätze wurden übernommen.";

$lang["import_entries"] = "Die Datei enthält %s Datensätze.";

$lang["import_delete"] = "Tabellen vor dem Import leeren?";

$lang["importwhichlevels"] = "Welche Tabellen sollen importiert werden?";

$lang["import_templates"] = "Vorlagen auch importieren?";

$lang["couldnotimport"] = "Es trat ein Fehler auf beim Importieren der Datensätze für: ";

$lang["error_invalidxml"] = "Diese Datei ist keine für dieses Modul brauchbare XML-Datei.";



// MODULE INTERACTION

$lang["postinstall"] = "Modul wurde erfolgreich hinzugefügt.<br/>Stellen Sie sicher, dass die für dieses Modul notwendigen Verzeichnisse im Uplaods-Verzeichnis angelegt sind. Vergeben Sie die notwendigen Rechte für Ihre Anwender!";

$lang["postuninstall"] = "Modul wurde erfolgreich entfernt.";

$lang["really_uninstall"] = "Alle Daten dieses Moduls werden entfernt. Weitermachen";

$lang["uninstalled"] = "Modul wurde de-installiert.";

$lang["installed"] = "Das Modul wurde in der Version %s installiert.";

$lang["help"] = "<h3>Was macht dieses Modul?</h3>

				<p>Dieses Modul verwaltet Kataloge mit Dateien und zeigt diese auch an.</p>

			<br/><h3>Wie wird es benutzt?</h3>

				<br/><h4>Rechte</h4>

					<p>Stellen Sie sicher, daß Sie Ihren Anwendern die notwendigen Rechte vergeben. Falls Sie keine Zugriffsbeschränkungs-Optionen nutzen möchte (siehe Einstellungen), reichen die Rechte für den \"avplayer: Normal user\" zur Nutzung des Moduls aus. Bei den Einstellungen können Sie entscheiden, welche Auswahlen dem \"normalen\" Anwender angezeigt werden sollen. Nur der Administrator und Anwender mit den Rechten  \"avplayer: Advanced\" haben Zugriff auf die Einstellungen.<br/>

Falls Sie Rechte für verschiedene Level definieren möchten, aktivieren Sie die \"Zugangsbeschränkungs-Optionen\" bei den Einstellungen und erteilen die  \"avplayer: Manage name_of_level\" Berechtigung für die gewünschten Anwender.</p>

				<br/><h4>Grundlegendes</h4>

					<p>Zum Einbinden des Moduls in einer Seite:<br /><strong>{cms_module module=\"avplayer\"}</strong></p>

					<p>In diesem Fall wird die Liste aller Mediadateien des obersten Levels angezeigt. Um ein bestimmtes Level zu wählen, anzuzeigen, nutzen Sie den \"what\" Parameter:<br/><strong>{cms_module module=\"avplayer\" what=\"Mediadatei\"}</strong><br/>

					<i>Mögliche Werte für den \"what\" Parameter sind: player, mediafile</i></p>

					<p>Sie können auch Elemente anzeigen, die einem bestimmten Parent-Element zugeordnet sind:<br/>

					<strong>{cms_module module=\"avplayer\" parent=\"Alias_des_Eltern-Elementes\"}</strong></p>

					<p>Sie können auch ein ganz spezielles Element anzeigent:<br />

					<strong>{cms_module module=\"avplayer\" alias=\"Alias_der_Mediendatei\"}</strong></p>

				<br/><h4>Seitenweise Ausgabe (Paginierung)</h4>

					<p>Sie können die Zahl der auf einer Seite anzuzeigenden Medien begrenzen::

					<br/><strong>{cms_module module=\"avplayer\" nbperpage=\"5\"}</strong><br/>

					In der Vorlage können Sie folgende Tags für das Seiten-Menü nutzen:<br/>

					{".'$'."page_showing}, {".'$'."page_totalitems}, {".'$'."page_pagenumbers}, {".'$'."page_next}, {".'$'."page_previous}</p>

				<br/><h4>Abfragen nutzen</h4>

					<p>Um eine Liste mit Einträgen, die einem bestimmten Kriterium genügen, zu erhalten, können Sie Abfragen (Queries) erstellen. Nutzen Sie dazu den Abfrage-Tab (Query-Tab) im Verwaltungsmenü und binden Sie diese mit dem \"query\" Parameter ein:

					<br/><strong>{cms_module module=\"avplayer\" query=\"5\"}</strong></p>

					<p>Falls die Option zur direkten Eingabe von Abfragen aktiviet ist, (siehe bei den Einstellungen),können Sie die Abfrage auch direkt als \"query\" - Parameter mitgeben. Ein Beispiel:

					<br/><strong>{cms_module module=\"avplayer\" what=\"mediafile\" query=\"A.date_modified > '2009-03-15' AND A.active = 1\"}</strong><br/>

					Dieser Query-Parameter darf nur die WHERE-Klausel und nicht den WHERE-Befehl selbst enthalten. Um Probleme zu vemeiden, nutze Sie den Präfix \"A.\" vor den Feld-Namen, (falls notwendig, kann der  \"B.\" -Präfix genutzt werden, um spezielle Kriterien der Parent-Felder zu spezifizieren).<br/>

					Auch wenn der Abstraction-Layer nicht so leicht mißbraucht werden kann (SQL-Injectios), seien Sie sich trotzdem bewußt, daß Sie mit dieser Option einen Zugang zu den SQL-Optionen der Vorlagen eröffnen. Seien Sie vorsichtig.</p>

				<br/><h4>Verlinkung</h4>

					<p>Sie können die Aktion <b>\"link\"</b> einsetzen, um einen Link zu der Standard-Ausgabe zu ermöglichen. Dazu gibt es einige Parameter:<br/><strong>{cms_module module=\"avplayer\" action=\"link\" what=\"mediafile\" random=\"1\"}</strong><br/>

					generiert einen Link zu einem zufälligen Element dieses Levels.</p>

				<br/><h4>Sitemap</h4>

					<p>Enthält Ihre Einrichtung keine untergeordneten Dateien (children), können Sie die Aktion <b>\"sitemap\"</b> einsetzen, um eine Sitemap des Moduls auszugeben:<br/>

					<strong>{cms_module module=\"avplayer\" action=\"sitemap\"}</strong><br/>

					Sie können Levels auswählen mit dem \"what\" -Parameter. Es ist auch möglich, ehr als einen Level auszuwählen, nutzen Sie dafür <strong> \"|\" : what=\"level1|level2\"</strong>.<br/>

					Weitere mögliche Parameter für diese Auswahl sind <strong>\"detailpage\"</strong> und <strong>\"inline\"</strong>.<br/>

					Um eine Google-SiteMap anzulegen, schauen Sie in der <a href=\"../modules/avplayer/doc/faq.html#q16\" target=\"_blank\">FAQ</a> nach.</p>

				<br/><h4>Frontend - Bearbeitung</h4>

					<p>Ein Formular für die Bearbeitung von Elementen im Frontend rufen Sie folgendermaßen auf:

					<br/><strong>{cms_module module=\"avplayer\" action=\"frontend_edit\" what=\"mediafile\"}</strong><br/>

					Dieser Tag gibt ein leeres Formular aus und legt ein neues Element des Levels \"mediafile\" an. <br />

					Um bestehende Elemente so zu editieren, geben Sie den Alias des betreffenden Elementes an:

					<br/><strong>{cms_module module=\"avplayer\" action=\"frontend_edit\" what=\"mediafile\" alias=\"item_alias\"}</strong></p>

					<p>Möchten Sie einen Link zu dem Editierformular eines Elementes setzen, nutzen Sie \"link\":<br/>

					<strong>{cms_module module=\"avplayer\" action=\"link\" toaction=\"frontend_edit\" what=\"mediafile\" alias=\"item_alias\"}<br/>

					You could, for example, do this in a list template...</p></strong>

					<p>(Beachten Sie die Optionen für das Front-End-Editieren in den Einstellungen)<br/>

					Es wird empfohlen, das Front-End-Editieren zusammen mit dem Modul \"Front End Users\" für das Rechte-Management einzusetzen.  Mehr dazu in der <a href=\"../modules/avplayer/doc/faq.html#q20\" target=\"_blank\">FAQ</a>.</p>

				<br/><h4>Suche</h4>

					<p>Nutzen Sie die Aktion  <b>\"search\"</b> zur Ausgabe eines Such-Formulars:<br/>

					<strong>{cms_module module=\"avplayer\" action=\"search\"}</strong><br/>

					Mit dem  \"searchmode\" können sie zwischen <i>advanced</a></i> (Standard) und einfachen Such-Modus wechseln. Sie können das Level, in dem gesucht werden soll, festlegen mit dem \"what\" - Parameter. Es ist nicht möglich, alle Levels im Advanced-Modus auf einaml zu durchsuchen.<br/>

					Die nachstehenden Parameter stehen für die Suche zur Verfügung: <i>what, limit, nbperpage, orderby, detailpage, listtemplate, inline, searchmode</i>.<br/>

					Um das Such-Formular zu modifizieren, nutzen Sie die Vorlage \"avplayer/templates/search.tpl\".</p><br/>



				<h4>Audio/Video Player</h4>

				Ein <i>Player</i> ist eine Definition, wie eine Media-Datei abgespielt und auf der Webseite dargestellt werden soll. Also z.B. die Farben der Ränder, die Größe der Anzeige, die Anzeige von Steuer-Elementen, automatisches Abspielen etc.<br />

				Auch wenn man andere \"Player\" mit AVPlayer einsetzen kann, ist es für für den Einsatz des <strong>NeoLao Player</strong> optimiert worden. <br />

				Für die Ausgabe von Video-Dateien können Sie den <strong>NeoLao Video (FLV Player)</strong> von

				<a href=\"http://flv-player.net/players/maxi/download\" target=\"_blank\">http://flv-player.net/players/maxi/download</a> herunterladen.<br />

				Laden Sie ihn dann auf Ihre Seite hoch und geben Sie den Pfad zu dieser Datei im \"Location\"-Feld des Players (\"Abspielers\") an, z.B. <i>uploads/FLVPlayer/player_flv_maxi.swf</i>.<br />

			Für die Ausgabe von <strong>MP3's</strong> können Sie den  <strong>NeoLao Flash MP3 Player</strong> von <br>

<a href=\"http://flash-mp3-player.net/players/maxi/download\" target=\"_blank\">http://flash-mp3-player.net/players/maxi/download</a> herunterladen.<br />

Die <strong>Player Parameter</strong> können Sie mit dem <i>Maxi-Generator</i> auf <a href=\"http://flv-player.net/players/maxi/generator\" target=\"_blank\">http://flv-player.net/players/maxi/generator</a> zusammenstellen und ausprobieren und anschließend als Parameter eintragen.<br />

<p>Weitere Informationen finden Sie in der  <a href=\"../modules/avplayer/doc/faq.html\" target=\"_blank\">FAQ</a>.</p>

<br/>

<h3>Copyright und Lizenz</h3><p>Dieses Modul steht unter der GNU Public License.</p><br/><br/>";



//EVENTS

$lang["eventdesc_modified"] = "aktiv, nachdem ein Element geändert wurde. Parameter: \"what\"=>Level des Elementes, \"itemid\"=>ID des Elements, \"alias\"=>Alias des Elements.";

$lang["eventdesc_deleted"] = "aktiv, nachdem ein Element gelöscht wurde. Parameter: \"what\"=>Level des Elementes.";

$lang["eventdesc_added"] = "aktiv, nachdem ein Element hinzugefügt wurde. Parameter: \"what\"=>Level des Elementes, \"itemid\"=>ID des Elements, \"alias\"=>Alias des Elements.";



//PREFERENCES

$lang["preferences"] = "Einstellungen";

$lang["pref_tabdisplay"] = "Vewaltungs-Tabs, die dem einfachen Anwender angezeigt werden.";

$lang["help_tabdisplay"] = "(einfache Anwender sind die, die das Recht \"avplayer: normal user\" zugeteilt bekamen. Die jedem Level zugeteilten Rechte überschreiben die hier ausgewählten Tabs eventuell.))";

$lang["pref_searchmodule_index"] = "Index der nachfolgenden Levels für das Such-Modul";

$lang["help_searchmodule_index"] = "(Dies wirkt sichnicht auf die eigene, interne Suchfunktion des AVPlayers aus. Beachten Sie auch, daß Sie, wenn Sie diee Einstellungen ändern, Sie den Suchindex neu generieren müssen (Erweiterungen -&gt;Suche))";

$lang["pref_newitemsfirst"] = "Neue Elementesollen oberhalb der nachstehenden Level angezeigt werden:";

$lang["help_newitemsfirst"] = "(Wird hier kein Level ausgesucht, werden die neuesten Einträge selbstverständlich am Listenende angezeigt)";

$lang["pref_restrict_permissions"] = "Soll die Bearbeitung der Einträge auf Anwender <b>beschränkt</b> werden, die eine spezielle Berechtigung für dieses Level zuerteilt bekommen haben? (Wenn Sie keine verschiedenen Berechtigungen für die verschiedenen Levels vergeben möchten, wählen Sie diese Auswahl nicht an und nutzen einfach das \"avplayer: normal user\" - Profil.)";

$lang["pref_display_filter"] = "<b>Filter</b> im Verwaltungsmenü anzeigen?";

$lang["pref_display_instantsearch"] = "<b>Schnellsuche</b> im Verwaltungsmenü anzeigen?";

$lang["pref_display_instantsort"] = "<b>Schnell-Sortierung</b> bei den Spalten-Links anzeigen?";

$lang["pref_editable_aliases"] = "Darf der <b>Alias</b> der Einträge manuell editiert werden?";

$lang["pref_force_list"] = "Soll der <b>forcelist</b> Parameter standardmäßig aktiv sein? (dann wird die Liste auch dann als Liste angezeigt, wenn es nur ein Element gibt)";

$lang["pref_delete_files"] = "Sollen, wenn ein Eintrag <b>gelöscht</b> wird, auch die dazugehörigen Dateien gelöscht werden (wenn welche vorhanden sind)";

$lang["pref_allow_sql"] = "Manuelle SQL Queries? (mit dem \"query\" -Parameter) zulassen?";

$lang["pref_use_hierarchy"] = "Die komplette Hierarchie im Filter und \"Eltern-Dropdown-Liste\" im Verwaltungsmenü anzeigen?";

$lang["pref_orderbyname"] = "Eltern-Dropdown-Liste nach Namen sortieren?";

$lang["pref_showthumbnails"] = "Alle Thumbnails statt einer Tabelle auflisten, wenn eine Bild-Datei ausgewählt wird?";

$lang["pref_maxshownpages"] = "Wieviele Seiten sollen im Seiten-Menü angezeigt werden: ";

$lang["pref_autoincrement_alias"] = "Sollen die Aliase hochgezählt werden, wenn identische Namen vergeben werden?";

$lang["pref_decodeentities"] = "Sollen die HTML-Entities, wenn ein Front-End-Formular abgesandt wird, dekodiert werden?";

$lang["pref_frontend"] = "Frontend Bearbeitung";

$lang["help_frontend"] = "Aus Sicherheitsgründen sollte das Front-End-Editieren nur im Zusammenspiel mit dem Front End Users Modul ermöglicht werden. Weitere Informationen zur Einbindungen finden Sie in der <a href=\"modules/avplayer/doc/faq.html#q20\" target=\"_blank\">Faq</a>.";

$lang["pref_fe_wysiwyg"] = "WYSIWG im Frontend erlauben (muss auch in den Allgemeinen Einstellungen erlaubt werden.)";

$lang["pref_fe_decodeentities"] = "HTML-Entities von Front-End-Formularen dekodieren? Davon wird abgeraten.";

$lang["pref_fe_allowfiles"] = "Datei-Upload im Front-End erlauben?";

$lang["pref_fe_allownamechange"] = "Front-End-Anwender dürfen Datei-Namen (und damit implizit auch die Alias-Bezeichnungen) des Elementes, das sie bearbeiten, verändern?";

$lang["pref_fe_allowaddnew"] = "Front-End-Anwender dürfen neue Elemente ochladen.";

$lang["pref_fe_usecaptcha"] = "Captcha einsetzen? (Das Modul \"Captcha\" muss aktiviert sein)";

$lang["pref_fe_aftersubmit"] = "Nach dem Absenden eines Formulars weiterleiten nach ";

$lang["pref_fe_maxfilesize"] = "Maximale Dateigröße in Bytes für den Front-End-Upload (eingeschränkt durch die in der PHP.INI definierte maximale Dateigröße)";

$lang["pref_allow_complex_order"] = "Komplexe \"orderby\" -Sortier-Parameter zulassen (beta).";

$lang["pref_adminpages"] = "Anzahl der Einträge pro Seite im Verwaltungsmenü (0 = keine Paginierung).";

$lang["pref_load_nbchildren"] = "Anzahl der Kind-Elemente (item-&gt;nbchildren) für alle Einträge anzeigen?";

$lang["pref_load_nextprevious"] = "Weiter- / Zurück-Links anzeigen (nur in der Detail-Ansicht)";

$lang["pref_use_session"] = "Verwaltungsmenü-Filter per session speichern.";

$lang["pref_levelpagination"] = "Standard-Anzahl der auf einer Seite angezeigten Elemente im Front-End";

$lang["help_levelpagination"] = "(kein Eintrag oder 0 deaktiviert standardmäßige Paginierung. Der \"nbperpage\"-Parameterüberschreibt diese Einstellung.)";



//PARAMETERS

$lang["phelp_action"] = "Mögliche Aktionen: \"link\", \"search\", \"breadcrumbs\", \"default\", \"sitemap\" und \"frontend_edit\".";

$lang["phelp_what"] = "Geben Sie das Level an, welches Sie anzeigen möchten. Mögliche Werte sind : <i>player, mediafile</i>";

$lang["phelp_alias"] = "Alias des anzuzeigenden Elementes.";

$lang["phelp_parent"] = "Wenn Sie die Anzeige der Elemente auf diejenigen beschränken möchte, die zu einem Eltern-Element gehören, tragen Sie hier den Namen des Eltern-Elementes ein.";

$lang["phelp_limit"] = "Anzahl der Abfrage-Ergebnisse (0 = keine Einschränkung)";

$lang["phelp_nbperpage"] = "Anzahl der auf einer Seite anzuzeigenden Elemente.";

$lang["phelp_orderby"] = "Sie können die Einträge nach \"modified\", \"created\" oder dem Feldnamer sortieren lassen. Jeder andere Wert gibt in der Standard-Reihenfolge der Elemente aus.";

$lang["phelp_detailpage"] = "Geben Sie den Alias der Seite an, in der die Links zu den Kind-Elementen angezeigt werden sollen (Kein Eintrag = die Ausgabe erfolgt auf der aktuellen Seite))";

$lang["phelp_showdefault"] = "Die Eingabe \"true\" zeigt das Standard-Element an.";

$lang["phelp_random"] = "Definieren Sie, wieviel Elemente Ihrer Abfrage in zufälliger Anordnung ausgegeben werden sollen.";

$lang["phelp_finaltemplate"] = "Welche Vorlage soll für die Detail-Anzeige des Ergebnis-Levels genommen werden.";

$lang["phelp_listtemplate"] = "Template für die Listen-Anzeige";

$lang["phelp_forcelist"] = "\"1\" wenn Sie auch dann eine Liste anzeigen möchten, wenn es nur ein Element gibt.";

$lang["phelp_internal"] = "Für internen Gebrauch; nennen Sie die Seite, wenn Sie den Parameter \"nbperpage\" einsetzen.";

$lang["phelp_query"] = "Optional: gehört zur \"default\" Aktion. Legt die ID der Query fest, die Sie einsetzen möchten.";

$lang["phelp_inline"] = "Links als Inline-Links erstellen.";

$lang["phelp_searchmode"] = "Gehört zur \"search\" - Ackion. \"simple\": es wird in allen Text-Feldern gesucht, \"advanced\": es wird in zu spezifizierenden Feldern gesucht.";

$lang["phelp_toaction"] = "gehört zur  \"link\" - Aktion. Definiert, zu welcher Aktion der Link zurückverweisen soll.";



?>