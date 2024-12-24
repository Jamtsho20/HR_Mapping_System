<form action="{{ url()->current() }}" method="GET">
    <div class="d-flex justify-content-between">
        <div class="w-90">
            <div class="row pb-2">
                {{ $slot }}
            </div>
        </div>
        <div>
            <div class="btn-group">
                <button type="submit" class="btn btn-success"><i class="fa fa-search"></i></button>
                <a href="{{ url()->current() }}" class="btn btn-danger"><i class="fe fe-refresh-ccw"></i></a>
            </div>
        </div>
    </div>
</form>