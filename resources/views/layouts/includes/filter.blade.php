<form action="{{ url()->current() }}" method="GET">
    <div class="row filter">
        {{ $slot }}
        <div class="col-md-1">
            <div class="btn-group">
                <button type="submit" class="btn btn-success"><i class="fa fa-search"></i></button>
                <a href="{{ url()->current() }}" class="btn btn-danger"><i class="fa fa-undo"></i></a>
            </div>
        </div>
    </div>
</form>