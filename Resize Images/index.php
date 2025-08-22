<?php


ini_set('memory_limit', '512M'); // or '1G'
set_time_limit(0); // prevent timeouts

function resizeImageFromCenter($srcPath, $destPath, $newWidth, $newHeight) {
    list($width, $height, $type) = getimagesize($srcPath);

    switch ($type) {
        case IMAGETYPE_JPEG: $srcImg = imagecreatefromjpeg($srcPath); break;
        case IMAGETYPE_PNG:  $srcImg = imagecreatefrompng($srcPath); break;
        case IMAGETYPE_GIF:  $srcImg = imagecreatefromgif($srcPath); break;
        default: return; // skip unsupported
    }

    $srcAspect = $width / $height;
    $dstAspect = $newWidth / $newHeight;

    if ($srcAspect > $dstAspect) {
        $cropHeight = $height;
        $cropWidth = (int)($height * $dstAspect);
    } else {
        $cropWidth = $width;
        $cropHeight = (int)($width / $dstAspect);
    }

    $srcX = (int)(($width - $cropWidth) / 2);
    $srcY = (int)(($height - $cropHeight) / 2);

    $dstImg = imagecreatetruecolor($newWidth, $newHeight);

    if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
        imagecolortransparent($dstImg, imagecolorallocatealpha($dstImg, 0, 0, 0, 127));
        imagealphablending($dstImg, false);
        imagesavealpha($dstImg, true);
    }

    imagecopyresampled($dstImg, $srcImg, 0, 0, $srcX, $srcY, $newWidth, $newHeight, $cropWidth, $cropHeight);

    switch ($type) {
        case IMAGETYPE_JPEG: imagejpeg($dstImg, $destPath, 90); break;
        case IMAGETYPE_PNG:  imagepng($dstImg, $destPath); break;
        case IMAGETYPE_GIF:  imagegif($dstImg, $destPath); break;
    }

    imagedestroy($srcImg);
    imagedestroy($dstImg);
}

// SETTINGS
$newWidth = 1382;   // target width
$newHeight = 922;  // target height

$inputDir = __DIR__ . '/images';
$outputDir = __DIR__ . '/result';

// Process all images in "images" folder
foreach (glob($inputDir . "/*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}", GLOB_BRACE) as $filePath) {
    $fileName = basename($filePath);
    $outputPath = $outputDir . '/' . $fileName;

    resizeImageFromCenter($filePath, $outputPath, $newWidth, $newHeight);
    echo "Processed: $fileName<br>";
}

echo "<strong>All images processed to 'result/' folder.</strong>";
?>
