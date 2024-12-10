<style>
    .breadcrumb-item.disabled {
        color: #6c757d;
        /* Grey out the disabled breadcrumb */
        pointer-events: none;
        /* Prevents clicking */
        cursor: default;
        /* Changes cursor to default */
        text-decoration: none;
        /* Removes underline */
    }
</style>
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{ url('/') }}">Home</a>
    </li>
    @php
    $urlSegments = request()->segments();
    $urlCount = count($urlSegments);
    $currentPath = '';
    @endphp

    @foreach ($urlSegments as $key => $segment)
    @php
    $currentPath .= '/' . $segment;
    @endphp

    <li class="breadcrumb-item
                   {{ ($key === 0) ? 'disabled' : '' }} 
                   {{ ($key + 1 == $urlCount) ? 'active' : '' }}"
        aria-disabled="{{ ($key === 0) ? 'true' : 'false' }}">

        @if ($key === 0)
        <!-- Disabled breadcrumb for the first segment after 'Home' -->
        <span class="disabled">{{ ucwords(str_replace('-', ' ', $segment)) }}</span>
        @elseif ($key + 1 == $urlCount)
        <!-- Last segment (active) - no link -->
        {{ ucwords(str_replace('-', ' ', $segment)) }}
        @else
        <!-- Normal clickable breadcrumb link -->
        <a href="{{ url($currentPath) }}">{{ ucwords(str_replace('-', ' ', $segment)) }}</a>
        @endif
    </li>
    @endforeach
</ol>