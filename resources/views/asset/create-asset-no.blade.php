<form action="{{ url('asset/upload-asset-no') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="file">Upload Asset File (.csv / .xlsx)</label>
        <input type="file" name="file" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary mt-2">Upload</button>
</form>
