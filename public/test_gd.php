<?php
// Test GD Extension Functionality
header('Content-Type: text/html');

echo "<h2>GD Extension Test</h2>";

if (extension_loaded('gd')) {
    echo "<p style='color: green;'>✓ GD Extension is loaded!</p>";

    $gd_info = gd_info();
    echo "<h3>GD Information:</h3>";
    echo "<ul>";
    foreach ($gd_info as $key => $value) {
        if (is_bool($value)) {
            $value = $value ? 'Yes' : 'No';
        }
        echo "<li><strong>$key:</strong> $value</li>";
    }
    echo "</ul>";

    // Test image creation
    echo "<h3>Testing Image Creation:</h3>";
    try {
        $image = imagecreate(100, 50);
        $bg_color = imagecolorallocate($image, 255, 255, 255);
        $text_color = imagecolorallocate($image, 0, 0, 0);
        imagestring($image, 3, 10, 15, 'GD Works!', $text_color);

        ob_start();
        imagepng($image);
        $image_data = ob_get_contents();
        ob_end_clean();

        if ($image_data) {
            echo "<p style='color: green;'>✓ Image creation successful!</p>";
            echo "<p>Image size: " . strlen($image_data) . " bytes</p>";
        } else {
            echo "<p style='color: red;'>✗ Image creation failed!</p>";
        }

        imagedestroy($image);

    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Error creating image: " . $e->getMessage() . "</p>";
    }

} else {
    echo "<p style='color: red;'>✗ GD Extension is not loaded!</p>";
}

echo "<hr>";
echo "<p><a href='test_installation.php'>← Back to Installation Test</a></p>";
echo "<p><a href='index.php'>← Back to Homepage</a></p>";
?>