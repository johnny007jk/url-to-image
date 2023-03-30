<!DOCTYPE html>
<html>
<head>
  <title>Image Downloader</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
  <div>
    <h2>Batch Image Downloader</h2>
    <p>Paste the URLs of the images to download below, one per line:</p>
    <textarea id="urls" rows="10" cols="80"></textarea>
    <br>
    <button id="download-button">Download Images as .zip</button>
  </div>
  <script>
    $(function() {
      $('#download-button').click(function() {
        var urls = $('#urls').val();
        $.ajax({
          url: 'download.php',
          method: 'POST',
          data: {urls: urls},
          success: function(data) {
            var url = window.URL.createObjectURL(new Blob([data]));
            var link = document.createElement('a');
            link.href = url;
            link.download = 'downloads.zip';
            link.click();
          }
        });
      });
    });
  </script>
</body>
</html>
