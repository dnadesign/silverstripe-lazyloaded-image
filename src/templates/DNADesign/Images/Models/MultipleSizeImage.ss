<% if $Lazyload %>
<picture>
	<% loop $Sources %>
		<source media="($Rule)" data-srcset="$Image.URL">
	<% end_loop %>
	$BaseImage.Lazyloaded
</picture>
<% else %>
	<picture>
	<% loop $Sources %>
		<source media="($Rule)" srcset="$Image.URL">
	<% end_loop %>
	$BaseImage
</picture>
<% end_if %>