<?php
class ZipHandler {

    /**
     * @throws Exception
     */
    public static function CreateZip($directory, $zipname = 'archive') {
        $instance = new ZipHandler();
        return $instance->create($directory, $zipname);
    }

    /**
     * @throws Exception
     */
    public function create($directory, $name = "archive"){
        if (!is_dir($directory)) {
            throw new Exception('Directory does not exist');
        }
        $zip = new ZipArchive();
        $name = $directory. '/' . $name . '.zip';
        $zip->open($name, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        foreach ($files as $file){
            if (!$file->isDir())
            {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($name) + 1);
                $zip->addFile($filePath, $file->getFilename());
            }
        }
        $zip->close();

        return $name;
    }

    /**
     * @throws Exception
     */
    public function download($directory, $name = "archive"){
        $zip = $this->create($directory, $name);
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename=' . basename($zip));
        header('Content-Length: ' . filesize($zip));
        readfile($zip);
        unlink($zip);

    }

}