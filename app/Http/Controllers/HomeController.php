<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class HomeController extends Controller
{
    public function __construct() {
        //
    }

    public function image(Request $request, $max_height = 200, $max_width = 200, $image) {
        if (isset($image) && !empty($image)) {
            $src_file = env('UPLOADPATH') . basename($image);
            if (file_exists($src_file)) {
                $ext = pathinfo($image, PATHINFO_EXTENSION);

                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                    $dst_file = dirname($src_file) . DIRECTORY_SEPARATOR . 'thumbnail_' . (!empty($max_height) || $max_height ? $max_height . '_' : '') . basename($src_file);

                    if (!file_exists($dst_file)) {

                        list ( $width, $height, $image_type ) = getimagesize($src_file);

                        switch ($image_type) {
                            case 1 :
                                $src = imagecreatefromgif($src_file);
                                break;
                            case 2 :
                                $src = imagecreatefromjpeg($src_file);
                                break;
                            case 3 :
                                $src = imagecreatefrompng($src_file);
                                break;
                            default :
                                return '';
                                break;
                        }

                        $x_ratio = $max_width / $width;
                        $y_ratio = $max_height / $height;

                        if (($width <= $max_width) && ($height <= $max_height)) {
                            $tn_width = $width;
                            $tn_height = $height;
                        } elseif (($x_ratio * $height) < $max_height) {
                            $tn_height = ceil($x_ratio * $height);
                            $tn_width = $max_width;
                        } else {
                            $tn_width = ceil($y_ratio * $width);
                            $tn_height = $max_height;
                        }

                        $tmp = imagecreatetruecolor($tn_width, $tn_height);

                        /* Check if this image is PNG or GIF to preserve its transparency */
                        if (($image_type == 1) or ( $image_type == 3)) {
                            imagealphablending($tmp, false);
                            imagesavealpha($tmp, true);
                            $transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
                            imagefilledrectangle($tmp, 0, 0, $tn_width, $tn_height, $transparent);
                        }
                        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height);

                        /*
                         * imageXXX() has only two options, save as a file, or send to the browser.
                         * It does not provide you the oppurtunity to manipulate the final GIF/JPG/PNG file stream
                         * So I start the output buffering, use imageXXX() to output the data stream to the browser,
                         * get the contents of the stream, and use clean to silently discard the buffered contents.
                         */
                        imagejpeg($tmp, $dst_file, 85);
                    }
                } else {
                    $dst_file = dirname($src_file) . DIRECTORY_SEPARATOR . '' . basename($src_file);
                }

                if (file_exists($dst_file)) {
                    header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
                    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                    header('Pragma: no-cache');
                    $type = $this->mime_content_type($dst_file);
                    // echo $type;die();
                    header("Content-Type: " . $type . "");
                    header("Content-Disposition: attachment; filename=\"" . basename($dst_file) . "\";");
                    header("Content-Length: " . filesize($dst_file));
                    readfile($dst_file);
                    exit();
                }
            }
        }

        //throw new CHttpException(404, Yii::t('app', 'File not found'));
    }

    public function mime_content_type($filename) {
        if ($filename) {
            $mime_types = array(
                'txt' => 'text/plain',
                'htm' => 'text/html',
                'html' => 'text/html',
                'php' => 'text/html',
                'css' => 'text/css',
                'js' => 'application/javascript',
                'json' => 'application/json',
                'xml' => 'application/xml',
                'swf' => 'application/x-shockwave-flash',
                'flv' => 'video/x-flv',
                // images
                'png' => 'image/png',
                'jpe' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'jpg' => 'image/jpeg',
                'gif' => 'image/gif',
                'bmp' => 'image/bmp',
                'ico' => 'image/vnd.microsoft.icon',
                'tiff' => 'image/tiff',
                'tif' => 'image/tiff',
                'svg' => 'image/svg+xml',
                'svgz' => 'image/svg+xml',
                // archives
                'zip' => 'application/zip',
                'rar' => 'application/x-rar-compressed',
                'exe' => 'application/x-msdownload',
                'msi' => 'application/x-msdownload',
                'cab' => 'application/vnd.ms-cab-compressed',
                // audio/video
                'mp3' => 'audio/mpeg',
                'mp4' => 'video/mp4',
                'qt' => 'video/quicktime',
                'mov' => 'video/quicktime',
                '3gp' => ' video/3gpp',
                // adobe
                'pdf' => 'application/pdf',
                'psd' => 'image/vnd.adobe.photoshop',
                'ai' => 'application/postscript',
                'eps' => 'application/postscript',
                'ps' => 'application/postscript',
                // ms office
                'doc' => 'application/msword',
                'rtf' => 'application/rtf',
                'xls' => 'application/vnd.ms-excel',
                'ppt' => 'application/vnd.ms-powerpoint',
                // open office
                'odt' => 'application/vnd.oasis.opendocument.text',
                'ods' => 'application/vnd.oasis.opendocument.spreadsheet'
            );

            $var_d = explode('.', $filename);
            $ext = strtolower(array_pop($var_d));
            if (array_key_exists($ext, $mime_types)) {
                return $mime_types [$ext];
            } elseif (function_exists('finfo_open')) {
                $finfo = finfo_open(FILEINFO_MIME);
                $mimetype = finfo_file($finfo, $filename);
                finfo_close($finfo);
                return $mimetype;
            }
            return 'application/octet-stream';
        }
    }

    public function imageUpload(Request $request) {
        ini_set('max_execution_time', 999999);
        ini_set('memory_limit', '999999M');
        ini_set('upload_max_filesize', '500M');
        ini_set('max_input_time', '-1');
        ini_set('max_execution_time', '-1');
        if($request->isMethod('post')) {
            $request->validate([
                'profile' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
            ]);
            if($request->hasFile('profile')) {
                $image       = $request->file('profile');
                $filename    = time().rand(0000,9999) . '.' .$image->getClientOriginalExtension();
                $image_resize = Image::make($image->getRealPath());
                
                $image_resize->save(public_path('images/' .$filename));
                $filename    = time().rand(0000,9999) . '.' .$image->getClientOriginalExtension();
                $image_resize->resize(500, 300);
                $image_resize->save(public_path('images/' .$filename));
            }
        }
        die('Called-----------');
    }
}
