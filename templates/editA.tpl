
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
		<p class="pagetext">{$location_label}* :</p>
		<p class="pageinput">{$location_input}</p>
	</div>
	<div class="pageoverflow">
		<p class="pagetext">{$width_label} :</p>
		<p class="pageinput">{$width_input}</p>
	</div>
	<div class="pageoverflow">
		<p class="pagetext">{$height_label} :</p>
		<p class="pageinput">{$height_input}</p>
	</div>
	<div class="pageoverflow">
		<p class="pagetext">{$parameters_label} :</p>
		<p class="pageinput">{$parameters_input}</p>
	</div>
	<div class="pageoverflow">
		<p class="pagetext">{$userdefined1_label} :</p>
		<p class="pageinput">{$userdefined1_input}</p>
	</div>
<br/>
<p>{$submit} {$apply} {$cancel}</p>
