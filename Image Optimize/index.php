<?php
// Create the result folder if it doesn't exist
if (!file_exists('optimize')) {
    mkdir('optimize', 0777, true);
}

function optimize_image($source, $destination, $quality = 50) {


    // Get image details
    $image_info = getimagesize('images/'.$source);
    $image_type = $image_info[2];
    echo $image_type;

    // Check for valid image type
    if ($image_type == IMAGETYPE_JPEG) {

        // Load JPEG image
        $image = imagecreatefromjpeg('images/'.$source);

        // Save compressed image
        imagejpeg($image, $destination, $quality);

        // Free memory
        imagedestroy($image);
    } elseif ($image_type == IMAGETYPE_PNG) {
        // Load PNG image
        $image = imagecreatefrompng('images/'.$source);

        // Save compressed image
        imagepng($image, $destination, 9);  // PNG compression (0-9)

        // Free memory
        imagedestroy($image);
    } elseif ($image_type == IMAGETYPE_GIF) {
        // Load GIF image
        $image = imagecreatefromgif('images/'.$source);

        // Save compressed image
        imagegif($image, $destination);

        // Free memory
        imagedestroy($image);
    } else {
        echo "Unsupported image type for: $source\n";
        return;
    }

    echo "Image optimized and saved to $destination\n";
}

// Get all files in the current directory
$files = scandir('images/');


// Loop through files
foreach ($files as $file) {

    // Skip directories and non-image files
    if (in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif'])) {
        // Set the destination path in the 'result' folder
        $destination = 'optimize/' . basename($file);

        // Optimize the image
        optimize_image($file, $destination);
    }
}
?>