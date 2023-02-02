<?php

declare(strict_types=1);

namespace App\Addons\FileSystem;

class FileManager{
    public static function moveUploadedFile(array $fileInfo, string $destination):bool
    {
        if($fileInfo['error'] === UPLOAD_ERR_OK)
        {
            return move_uploaded_file($fileInfo['tmp_name'], $destination);
        }
        return false;
    }

    public static function validateUploadedFile(array $fileInfo, array $allowedExtensions = [], int|string $maxSize = '10MB'):bool
    {
        if(self::validateFileExtension($fileInfo['name'], $allowedExtensions)){
            $maxSizeInBytes = is_string($maxSize) ?  self::convertToBytes($maxSize) : $maxSize;
            if($fileInfo['size'] <= $maxSizeInBytes)
                return true;
        }
        return false;
    }


    public static function validateFileExtension(string $fileName, array $allowedExtensions):bool
    {
        if(empty($allowedExtensions)){
            return true;
        }
        else{
            $extension = pathinfo($fileName)['extension'] ?? '';
            return in_array($extension, $allowedExtensions) && strlen($fileName) > strlen($extension)+1;
        }
    }

    public static function convertToBytes(string $size):?int
    {
        $byteUnitPattern = '/^\d+B$/';
        $byteSuffixPattern = '/^\d+([KMGT]B)$/';
        $exponents = ['K'=>1, 'M'=>2, 'G'=>3, 'T'=>4];
        if(preg_match($byteSuffixPattern, $size)){
            $number = intval(substr($size,0,-2));
            $exponent = intval($exponents[substr($size,-2,1)]);
            return $number*(1024**$exponent);
        }
        else if(preg_match($byteUnitPattern, $size)){
            return intval(substr($size,0,-1));
        }
        return null;
    }

    public static function removeFile($filePath){
        $result = unlink($filePath);
        // var_dump($result);
        // var_dump($filePath);
        return $result;
    }
}