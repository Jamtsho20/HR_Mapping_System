@extends('layouts.app')
@section('content')
@include('layouts.includes.loader')
<div class="container">
    <h2>Upload Assets Excel File</h2>
    <form id="uploadForm" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" id="fileInput" accept=".xlsx,.csv" required>
        <br>
        <button class="btn btn-primary" type="submit">Upload</button>
    </form>

    <div id="message" class="message" style="display: none;"></div>
</div>

<script>
    // Grab the CSRF token from the meta tag

    document.getElementById('uploadForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const fileInput = document.getElementById('fileInput');
    const file = fileInput.files[0];
    const allowedExtensions = /(\.xlsx|\.csv)$/i;

    if (!file) return showMessage('Please select a file.', 'error');
    if (!allowedExtensions.exec(file.name)) return showMessage('Invalid file type. Only XLSX or CSV allowed.', 'error');

    const formData = new FormData();
    formData.append('file', file);
    formData.append('_token', "{{ csrf_token() }}"); // ✅ add CSRF here instead of headers
     $('#loader').show();
    fetch("/asset/upload-assets", {
        method: 'POST',
        body: formData,
        credentials: 'same-origin' // keep this
    })
    .then(res => res.json())
    .then(data => {
         $('#loader').hide();
        if (data.success) showMessage(data.message, 'success');
        else showMessage(data.error || 'Upload failed', 'error');
    })
    .catch(err => showMessage('Upload failed. ' + err, 'error'));
     $('#loader').hide();
});

    function showMessage(msg, type){
        const messageDiv = document.getElementById('message');
        messageDiv.innerText = msg;
        messageDiv.className = 'message ' + type;
        messageDiv.style.display = 'block';
    }
</script>

@endsection
