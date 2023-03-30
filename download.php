<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $urls = explode("\n", $_POST['urls']);
  $download_dir = './downloads/' . uniqid() . '/';
  if (!file_exists($download_dir)) {
    mkdir($download_dir, 0777, true);
  }
  foreach ($urls as $url) {
    $url = trim($url);
    if (!empty($url)) {
      $filename = basename($url);
      $parts = explode('/', parse_url($url, PHP_URL_PATH));
      $folder_name = $parts[5] . '_' . str_replace('-', '_', $parts[8]);
      $folder_name = str_replace(' ', '-', $folder_name);
      $folder = $download_dir . $folder_name . '/';
      if (!file_exists($folder)) {
        mkdir($folder, 0777, true);
      }
      $file = $folder . $filename;
      $image_data = file_get_contents($url);
      $image = imagecreatefromstring($image_data);
      imagewebp($image, $file, 80);
      imagedestroy($image);
    }
  }
  $zipname = $download_dir . 'downloads.zip';
  $zip = new ZipArchive();
  $zip->open($zipname, ZipArchive::CREATE);
  $files = scandir($download_dir);
  foreach ($files as $file) {
    if ($file !== '.' && $file !== '..') {
      $zip->addFile($download_dir . $file, $file);
    }
  }
  $zip->close();
  header('Content-Type: application/zip');
  header('Content-disposition: attachment; filename=' . basename($zipname));
  header('Content-Length: ' . filesize($zipname));
  readfile($zipname);
  unlink($zipname);
  rmdir($download_dir);
  exit;
}
?>
