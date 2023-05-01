@if (session('msg_success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <span class="alert-inner--text">
            {{ session('msg_success') }}
        </span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
@endif
@if (session('msg_error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <span class="alert-inner--text">
            {{ session('msg_error') }}
        </span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <span class="alert-inner--text">
            <i class="fa fa-ban"></i>
            Error(s) have occurred. You need to correct them and try again.<br>

            <ul>
                @foreach($errors->all(':message') as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
@endif