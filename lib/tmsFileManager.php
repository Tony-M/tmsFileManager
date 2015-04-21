<?php

/**
 * Created by PhpStorm.
 * User: morozov
 * Date: 21.04.2015
 * Time: 12:40
 */
class tmsFileManager
{

    protected static $path = null;

    protected static $files = array();
    protected static $folders = array();


    public static function setPath($path = '')
    {
        $path = self::decode($path);
        if (!self::isValidPath($path)) {
            throw new Exception('Incorrect path');
        }

        self::$path = $path;

        return true;
    }

    public static function getPath()
    {
        return self::$path;
    }

    public static function getBreadcrumbParts()
    {
        $result = array();
        $parts = explode('/', self::$path);
        $path = '';
        if (is_array($parts)) {
            foreach ($parts as $part) {
                $path .= ($path != '' ? '/' : '') . $part;
                $result[] = array('title' => $part, 'path' => self::encode($path));
//                $result[]=array('title'=>$part, 'path'=>$path);
            }
        }
        return $result;
    }

    public static function scan()
    {
        if (is_null(self::$path)) {
            throw new Exception('Invalid path');
        }
        $rows = array_diff(scandir('files/' . self::$path), array('..', '.'));
//        echo 'files/'.self::$path;
//        $rows  = scandir('files/'.self::$path);
        if (is_array($rows) && count($rows)) {
            foreach ($rows as $row) {
                $path = (self::$path != '' ? self::$path . '/' : '') . $row;

                if (is_dir('files/' . $path)) {
                    self::$folders[] = array('title' => $row, 'path' => self::decode($path));
                } else {
                    self::$files[] = array(
                        'title' => $path
                    , 'path' => self::decode($path)
                    , 'size' => self::human_filesize(filesize('files/' . $path))
                    );
                }
            }
        }
        return true;
    }

    public static function getFiles()
    {
        return self::$files;
    }

    public static function getFolders()
    {
        return self::$folders;
    }

    public static function isValidPath($path = '')
    {
        if (preg_match('/(\.\/)/', $path)) {
            return false;
        }

        return true;
    }

    public static function encode($text = '')
    {
        return urlencode($text);
    }

    public static function decode($text = '')
    {
        return urldecode($text);
    }

    public static function getIco($file_name = '', $is_folder = false)
    {
        if ($file_name == '') return false;
        if (!is_bool($is_folder)) return
            false;

        if ($is_folder) {
            return 'folder.png';
        } else {
            $path_parts = pathinfo($file_name);

            $ext = (isset($path_parts['extension']) && $path_parts['extension'] != '' ? $path_parts['extension'] : '');
            switch ($ext) {
                default:
                    return 'blank.png';
                    break;
                case 'pdf':
                    return 'pdf.png';
                    break;
                case 'ai':
                    return 'ai.png';
                    break;
                case 'bin':
                    return 'binary.png';
                    break;
                case 'iso':
                    return 'cd.png';
                    break;
                case 'deb':
                    return 'deb.png';
                    break;
                case 'rpm':
                    return 'rpm.png';
                    break;
                case 'pdf':
                    return 'pdf.png';
                    break;
                case 'doc':
                    return 'doc.png';
                    break;
                case 'docx':
                    return 'docx.png';
                    break;
                case 'xls':
                    return 'xls.png';
                    break;
                case 'xlsx':
                    return 'xlsx.png';
                    break;
                case 'ppt':
                    return 'ppt.png';
                    break;
                case 'pptx':
                    return 'pptx.png';
                    break;
                case 'exe':
                    return 'exe.png';
                    break;
                case 'ttf':
                    return 'font_truetype.png';
                    break;
                case 'gif':
                case 'bmp':
                case 'png':
                case 'jpeg':
                case 'jpg':
                    return 'image.png';
                    break;
                case 'inf':
                    return 'info.png';
                    break;
                case 'jar':
                    return 'jar.png';
                    break;
                case 'js':
                    return 'js.png';
                    break;
                case 'log':
                    return 'log.png';
                    break;
                case 'php':
                    return 'php.png';
                    break;
                case 'rar':
                    return 'rar.png';
                    break;
                case 'wave':
                case 'mp3':
                    return 'sound.png';
                    break;
                case 'c':
                    return 'source_c.png';
                    break;
                case 'cpp':
                    return 'source_cpp.png';
                    break;
                case 'f':
                    return 'source_f.png';
                    break;
                case 'h':
                    return 'source_h.png';
                    break;
                case 'j':
                    return 'source_j.png';
                    break;
                case 'txt':
                    return 'txt.png';
                    break;
                case 'mp4':
                case 'avi':
                    return 'video.png';
                    break;
                case 'xml':
                case 'html':
                    return 'xml.png';
                    break;
                case 'zip':
                    return 'zip.png';
                    break;
                case 'css':
                    return 'css.png';
                    break;

            }
        }

    }

    public static function download()
    {
        $path = 'files/' . self::$path;
        if (!file_exists($path) || !is_file($path)) return false;


        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($path));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;

    }

    public static function remove()
    {
        $path = 'files/' . self::$path;

        $path_parts = pathinfo(self::$path);

        if($path_parts['dirname']=='.')$path_parts['dirname']='';
//        exit;
        if (!file_exists($path) || !is_file($path)){
            header('Location: ?path='.self::encode($path_parts['dirname']));
        }
        try{
            unlink($path);
        }catch (Exception $e){

        }
        header('Location: ?path='.self::encode($path_parts['dirname']));
        exit;

    }

    public static function human_filesize($bytes, $decimals = 2)
    {
        $size = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }
}