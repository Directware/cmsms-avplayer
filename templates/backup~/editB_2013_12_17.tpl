
{if $itemalias}<span style="float: right; font-size: 80%">{$itemalias}</span>{/if}
<h1>{$edittitle}</h1>
<p>{$submit} {$apply} {$cancel}</p>
<br/><br/>
	<div class="pageoverflow">
		<p class="pagetext">{$name_label}* :</p>
		<p class="pageinput">{$name_input}</p>
	</div>
	{if $alias_input}<div class="pageoverflow">
		<p class="pagetext">{$alias_label} :</p>
		<p class="pageinput">{$alias_input}</p>
	</div>
	{/if}

	<div class="pageoverflow">
		<p class="pagetext">{$parent_label}* :</p>
		<p class="pageinput">{$parent_input}</p>
	</div>
	<div class="pageoverflow">
		<p class="pagetext">{$flvfile_label} :</p>
		<p class="pageinput">{$flvfile_input}</p>
	</div>
	<div class="pageoverflow">
		<p class="pagetext">{$mp4file_label} :</p>
		<p class="pageinput">{$mp4file_input}</p>
	</div>
	<div class="pageoverflow">
		<p class="pagetext">{$webmfile_label} :</p>
		<p class="pageinput">{$webmfile_input}</p>
	</div>
	<div class="pageoverflow">
		<p class="pagetext">{$ogvfile_label} :</p>
		<p class="pageinput">{$ogvfile_input}</p>
	</div>
	<div class="pageoverflow">
		<p class="pagetext">{$poster_label} :</p>
		<p class="pageinput">{$poster_input}</p>
	</div>
	<div class="pageoverflow">
		<p class="pagetext">{$mp3file_label} :</p>
		<p class="pageinput">{$mp3file_input}</p>
	</div>
	<div class="pageoverflow">
		<p class="pagetext">{$description_label} :</p>
		<p class="pageinput">{$description_input}</p>
	</div>
<br/>
<p>{$submit} {$apply} {$cancel}</p>
