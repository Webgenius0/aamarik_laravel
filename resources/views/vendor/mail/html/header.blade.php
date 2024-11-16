@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="http://sis.test/backend/assets/images/sis-logo.png" class="logo" alt="SIS">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
