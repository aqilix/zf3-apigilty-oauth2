<?php
namespace Aqilix\Image;

class Resizer
{
    static protected $width  = 200;

    static protected $height = 200;

    /**
     * Crop-to-fit PHP-GD
     * http://salman-w.blogspot.com/2009/04/crop-to-fit-image-using-aspphp.html
     *
     * Resize and center crop an arbitrary size image to fixed width and height
     * e.g. convert a large portrait/landscape image to a small square thumbnail
     *
     * @param   string   $src
     * @param   string   $dst
     * @param   int|null $width
     * @param   int|null $height
     *
     * @throws Exception\RuntimeException If filtering $value is impossible
     * @return void
     */
    public static function save($src, $dst, $width = null, $height = null)
    {
        if (is_null($width)) {
            $width = self::$width;
        }

        if (is_null($height)) {
            $height = self::$height;
        }

        /**
         * Add file validation code here
         */
        list($srcWidth, $srcHeight, $srcType) = getimagesize($src);

        switch ($srcType) {
            case IMAGETYPE_GIF:
                $srcGdim = imagecreatefromgif($src);
                break;
            case IMAGETYPE_JPEG:
                $srcGdim = imagecreatefromjpeg($src);
                break;
            case IMAGETYPE_PNG:
                $srcGdim = imagecreatefrompng($src);
                break;
        }

        $srcAspectRatio = $srcWidth / $srcHeight;
        $dstAspectRatio = $width / $height;

        if ($srcAspectRatio > $dstAspectRatio) {
            /**
             * Triggered when source image is wider
             */
            $tmpHeight = $height;
            $tmpWidth  = (int) ($height * $srcAspectRatio);
        } else {
            /**
             * Triggered otherwise (i.e. source image is similar or taller)
             */
            $tmpWidth  = $width;
            $tmpHeight = (int) ($width / $srcAspectRatio);
        }

        /**
         * Resize the image into a temporary GD image
         */
        $tempGdim = imagecreatetruecolor($tmpWidth, $tmpHeight);
        imagecopyresampled($tempGdim, $srcGdim, 0, 0, 0, 0, $tmpWidth, $tmpHeight, $srcWidth, $srcHeight);

        /**
         * Copy cropped region from temporary image into the desired GD image
         */
        $x0 = ($tmpWidth - $width) / 2;
        $y0 = ($tmpHeight - $height) / 2;
        $dstGdim = imagecreatetruecolor($width, $height);
        imagecopy($dstGdim, $tempGdim, 0, 0, $x0, $y0, $width, $height);

        /**
         * Render the image
         * Alternatively, you can save the image in file-system or database
         */
        return imagejpeg($dstGdim, $dst);
    }
}
