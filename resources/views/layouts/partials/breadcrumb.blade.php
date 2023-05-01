<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
    @php
        $urlSegments = request()->segments();
        $urlCount = count($urlSegments);
    @endphp
    @foreach ($urlSegments as $key => $segment)
        <li class="breadcrumb-item {{ ($key+1 == $urlCount) ? 'active' : '' }}" aria-current="{{ ($key+1 == $urlCount) ? 'page' : '' }}">
            @if ($key+1 == $urlCount)
                {{ ucwords(str_replace('-', ' ', $segment)) }}
            @else
                <a href="javascript:void(0);">{{ ucwords(str_replace('-', ' ', $segment)) }}</a>
            @endif
        </li>
    @endforeach
</ol>