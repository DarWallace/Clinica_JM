<x-mail::layout>
{{-- Header --}}
<x-slot:header>
<x-mail::header :url="config('app.url')">
{{ config('app.name') }}
</x-mail::header>
</x-slot:header>

{{-- Body --}}
{!! $slot !!}

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::subcopy>
{!! $subcopy !!}
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
{{-- Social Media Icons --}}
<table class="social-links">
<tr>
<td><a href="https://www.facebook.com/linguameeting"><img src="{{ asset('images/facebook-logo.png') }}" alt="Facebook"></a></td>
<td><a href="https://instagram.com/linguameeting"><img src="{{ asset('images/instagram-logo.png') }}" alt="Instagram"></a></td>
<td><a href="https://www.youtube.com/channel/UCXOmDy-1vdxVT6a9lsz9eqg/videos"><img src="{{ asset('images/youtube-logo.png') }}" alt="YouTube"></a></td>
<td><a href="https://www.linkedin.com/company/linguameeting"><img src="{{ asset('images/linkedin-logo.png') }}" alt="LinkedIn"></a></td>
</tr>
</table>

© {{ date('Y') }} [{{ config('app.name') }}](https://linguameting.com). {{ __('All rights reserved.') }}
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>
