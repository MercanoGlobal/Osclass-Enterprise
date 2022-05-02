<?php

class CustomZip {
    static function zip_folder($archive_folder, $archive_name) {
        if(strpos($archive_folder, "../")!==false || strpos($archive_name,"../")!==false || strpos($archive_folder, "..\\")!==false || strpos($archive_name,"..\\")!==false) {
            return false;
        }

        if (class_exists('ZipArchive')) {
            return self::_zip_folder_ziparchive($archive_folder, $archive_name);
        }
        // if ZipArchive class doesn't exist, we use PclZip
        return self::_zip_folder_pclzip($archive_folder, $archive_name);
    }

    static function _zip_folder_ziparchive($archive_folder, $archive_name) {
        if(strpos($archive_folder, "../")!==false || strpos($archive_name,"../")!==false || strpos($archive_folder, "..\\")!==false || strpos($archive_name,"..\\")!==false) {
            return false;
        }

        $tmppath = sys_get_temp_dir()."/";

        $zip = new ZipArchive;
        if ($zip -> open($archive_name, ZipArchive::CREATE) === TRUE) {
            $dir = preg_replace('/[\/]{2,}/', '/', $archive_folder."/");

            $dirs = array($dir);
            while (count($dirs)) {
                $dir = current($dirs);
                $zip -> addEmptyDir(str_replace($tmppath, '', $dir));

                $dh = opendir($dir);
                while (false !== ($_file = readdir($dh))) {
                    if ($_file != '.' && $_file != '..' && stripos($_file, 'Osclass_backup.')===FALSE) {
                        if (is_file($dir.$_file)) {
                            $zip -> addFile($dir.$_file, str_replace($tmppath, '', $dir.$_file));
                        } elseif (is_dir($dir.$_file)) {
                            $dirs[] = $dir.$_file."/";
                        }
                    }
                }
                closedir($dh);
                array_shift($dirs);
            }
            $zip -> close();
            return true;
        } else {
            return false;
        }
    }

    static function _zip_folder_pclzip($archive_folder, $archive_name) {
        if(strpos($archive_folder, "../")!==false || strpos($archive_name,"../")!==false || strpos($archive_folder, "..\\")!==false || strpos($archive_name,"..\\")!==false) {
            return false;
        }

        // first, we load the library
        require_once LIB_PATH . 'pclzip/pclzip.lib.php';

        $zip = new PclZip($archive_name);
        if($zip) {
            $dir = preg_replace('/[\/]{2,}/', '/', $archive_folder."/");

            $v_dir = osc_base_path();
            $v_remove = $v_dir;

            // To support windows and the C: root you need to add the
            // following 3 lines, should be ignored on linux
            if (substr($v_dir, 1,1) == ':') {
                $v_remove = substr($v_dir, 2);
            }
            $v_list = $zip->create($dir, PCLZIP_OPT_REMOVE_PATH, $v_remove);
            if ($v_list == 0) {
                return false;
            }
            return true;
        } else {
            return false;
        }

    }
}