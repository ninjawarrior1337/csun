<?php
$sk = "<SECRET KEY HERE>";

$site_dir = "main";
$target_dir = "staging/";

if(!file_exists($target_dir)) {
    mkdir($target_dir);
}

function endsWith( $haystack, $needle ) {
    $length = strlen( $needle );
    if( !$length ) {
        return true;
    }
    return substr( $haystack, -$length ) === $needle;
}

function deleteAll($dir) {
    foreach(glob($dir . '/*') as $file) {
        if(is_dir($file)) {
            deleteAll($file);
        }
        else {
            unlink($file);
        }
    }    
    rmdir($dir);
}

if(isset($_POST) && $_POST["sk"] == $sk && endsWith($_FILES["payload"]["name"], "tar.gz")) {
    echo $_FILES["payload"]["name"] . ": ". $_FILES["payload"]["size"] . "\n";
    $target_file = $target_dir . $_FILES["payload"]["name"];

    try {
        move_uploaded_file($_FILES["payload"]["tmp_name"], $target_file);

        $phar = new PharData($target_file);

        deleteAll($site_dir);
        mkdir($site_dir);

        $phar->extractTo("main", null, true);
        unlink($target_file);

        exec("chmod -R 775 $site_dir");

        echo "Upload Successful!";
        http_response_code(200);
    } catch (Exception $e) {
        echo "Failed to upload: $e";
        exit(400);
    }
} else {
    http_response_code(401);
}
?>