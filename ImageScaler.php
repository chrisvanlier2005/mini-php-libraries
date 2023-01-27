<?php

class ImageScaler
{
    protected $path;
    protected $gd_image;
    protected $target_type = "jpeg";

    /**
     * @throws Exception
     */
    public function __construct($path)
    {
        $this->path = $path;
        $this->initializeImage();
    }

    public function scale($scale = 1)
    {
        list($width, $height) = getimagesize($this->path);
        $aspect_ratio = $width / $height;
        $new_width = $width * $scale;
        $new_height = $new_width / $aspect_ratio;
        $this->gd_image = imagescale($this->gd_image, $new_width, $new_height);
    }

    public function resize($width, $height)
    {
        $this->gd_image = imagescale($this->gd_image, $width, $height);
    }

    /**
     * Sets the target type for future exports. Defaults to jpeg.
     * @param $type [jpeg|png|webp]
     * @throws Exception
     */
    public function setTargetType($type)
    {
        switch ($type) {
            case 'webp':
                $this->target_type = 'webp';
                break;
            case 'jpg':
            case 'jpeg':
                // header
                $this->target_type = 'jpeg';
                break;
            case 'png':
                $this->target_type = 'png';
                break;
            default:
                throw new Exception('Unsupported target type');

        }
    }

    /**
     * @throws Exception
     * @param $quality int 1 - 100
     */
    public function toBrowser($quality = 1)
    {
        header('Content-Type: image/' . $this->target_type);
        switch ($this->target_type) {
            case 'webp':
                imagewebp($this->gd_image, null, $quality);
                break;
            case 'jpeg':
                imagejpeg($this->gd_image, null, $quality);
                break;
            case 'png':
                imagepng($this->gd_image, null, $quality);
                break;
                default:
                    throw new Exception('Unsupported target type');
        }
    }

    /**
     * Stores the image in the specified direcory and the name, overwriting the file if it exists extension is added automatically
     * @param $directory string
     * @param $filename string
     * @throws Exception
     */
    public function store($directory, $filename){
        if(!is_dir($directory)){
            throw new Exception('Directory does not exist');
        }

        $path = $directory . '/' . $filename . '.' . $this->target_type;

        switch ($this->target_type) {
            case 'webp':
                imagewebp($this->gd_image, $path);
                break;
            case 'jpeg':
                imagejpeg($this->gd_image, $path);
                break;
            case 'png':
                imagepng($this->gd_image, $path);
                break;
            default:
                throw new Exception('Unsupported target type');
        }
    }

    protected function initializeImage()
    {
        $ext = $this->getFileExtension();
        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                $this->gd_image = imagecreatefromjpeg($this->path);
                break;
            case 'png':
                $this->gd_image = imagecreatefrompng($this->path);
                break;
            case 'gif':
                $this->gd_image = imagecreatefromgif($this->path);
                break;
            default:
                throw new Exception('Unsupported image type');
        }
    }

    protected function getFileExtension()
    {
        return strtolower(pathinfo($this->path, PATHINFO_EXTENSION));
    }

}