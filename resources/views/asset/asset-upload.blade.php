<!DOCTYPE html>
<html>
<head>
    <title>Upload Assets Excel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .container { max-width: 500px; margin: auto; }
        input[type=file] { margin-bottom: 10px; }
        .message { margin-top: 20px; padding: 10px; border-radius: 5px; }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
<div class="container">
    <h2>Upload Assets Excel File</h2>
    <form id="uploadForm" enctype="multipart/form-data">
        <input type="file" name="file" id="fileInput" accept=".xlsx,.csv" required>
        <br>
        <button type="submit">Upload</button>
    </form>

    <div id="message" class="message" style="display: none;"></div>
</div>

<script>
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const fileInput = document.getElementById('fileInput');
    const file = fileInput.files[0];
    const allowedExtensions = /(\.xlsx|\.csv)$/i;

    if (!file) {
        showMessage('Please select a file.', 'error');
        return;
    }

    if(!allowedExtensions.exec(file.name)){
        showMessage('Invalid file type. Only XLSX or CSV allowed.', 'error');
        return;
    }

    const formData = new FormData();
    formData.append('file', file);

    fetch("{{ route('assets.upload') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.message){
            showMessage(data.message, 'success');
        } else if(data.error){
            showMessage(data.error, 'error');
        }
    })
    .catch(err => showMessage('Upload failed. ' + err, 'error'));
});

function showMessage(msg, type){
    const messageDiv = document.getElementById('message');
    messageDiv.innerText = msg;
    messageDiv.className = 'message ' + type;
    messageDiv.style.display = 'block';
}
</script>
</body>
</html>
