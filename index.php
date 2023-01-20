<?php
require_once 'ImageScaler.php';
require_once 'ZipHandler.php';
/*try {
    $scalerInstance = new ImageScaler(__DIR__ . '/images/lighthouse.jpg');


    // rescales the image from 0 to 1, keeping the aspect ratio
    // $scalerInstance->scale(0.1);

    // resizes the image disregarding original aspect ratio
    //$scalerInstance->resize(2000, 2000);



    // sets the target type for rendering or exporting
    $scalerInstance->setTargetType('webp');
    $scalerInstance->toBrowser(5);
    //$scalerInstance->store('images', 'test');


} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}
*/


try {
    $zip = new ZipHandler();
    $zip->download(__DIR__ . '/images', 'zip');
} catch (Exception $e){
    echo $e->getMessage();
    exit;
}
