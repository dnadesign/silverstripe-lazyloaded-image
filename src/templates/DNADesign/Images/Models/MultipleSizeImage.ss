<picture>
	<% loop $Sources %>
		<source media="($Rule)" srcset="$Image.URL">
	<% end_loop %>
	{$BaseImage}
</picture>
