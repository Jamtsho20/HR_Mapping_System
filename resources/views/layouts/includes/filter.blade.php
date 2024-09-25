<form action="{{ url()->current() }}" method="GET" style="width: 99%">
    <div class="row filter">
        <div class="col-12 col-sm-11">
            <div class="row">
                {{ $slot }}
            </div>
        </div>

        <div class="col-md-1 float-end">
            <div class="btn-group">
                <button type="submit" class="btn btn-success"><i class="fa fa-search"></i></button>
                <a href="{{ url()->current() }}" class="btn btn-danger"><i class="fe fe-refresh-ccw"></i></a>
            </div>
        </div>
    </div>
</form>
