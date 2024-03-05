<?php

use Illuminate\Support\Facades\File;

// START Upload File
if (!function_exists('uploadFile')) {
    function uploadFile($path, $files, $includeFileInfo = false, $includeThumbnail = false)
    {
        $allowedDocumentExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'gif'];
        $return = [];

        if (is_array($files)) {
            foreach ($files as $key => $file) {
                File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
                $fileExtension = strtolower($file->extension());

                if (in_array($fileExtension, $allowedDocumentExtensions)) {
                    $imageName = $key . time() . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT) . '.' . $fileExtension;
                    $fileData = ['imageName' => $imageName];
                    if ($includeFileInfo) {
                        $fileSize = $file->getSize();
                        $fileData['fileSize'] = formatFileSize($fileSize);
                        $fileData['fileType'] = $file->getClientMimeType();
                    }

                    $mime = $file->getMimeType();
                    if ($includeThumbnail && str_starts_with($mime, 'image/')) {
                        $thumbnailPath = $path . 'thumbnails/' . $imageName;
                        generateThumbnail($file->getPathname(), $thumbnailPath, 120, 120);
                        $fileData['thumbnailPath'] = $thumbnailPath;
                    }

                    if ($file->move($path, $imageName)) {
                        $return[] = $includeFileInfo ? $fileData : $imageName;
                    }
                }
            }
            return $return;
        } else {
            if (isset($files) && !empty($files)) {
                File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
                $fileExtension = strtolower($files->extension());

                if (in_array($fileExtension, $allowedDocumentExtensions)) {
                    $imageName = time() . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT) . '.' . $fileExtension;
                    $fileData = ['imageName' => $imageName];

                    if ($includeFileInfo) {
                        $fileSize = $files->getSize();
                        $fileData['fileSize'] = formatFileSize($fileSize);
                        $fileData['fileType'] = $files->getClientMimeType();
                    }

                    $mime = $files->getMimeType();
                    if ($includeThumbnail && str_starts_with($mime, 'image/')) {
                        $thumbnailPath = $path . 'thumbnails/' . $imageName;
                        generateThumbnail($files->getPathname(), $thumbnailPath, 120, 120);
                        $fileData['thumbnailPath'] = $thumbnailPath;
                    }

                    if ($files->move($path, $imageName)) {
                        return $includeFileInfo ? $fileData : $imageName;
                    }
                }
            }
        }
    }
}

// Update File
if (!function_exists('updateFile')) {
    function updateFile($path, $existingFileNames, $files, $includeFileInfo = false, $includeThumbnail = false)
    {
        $allowedDocumentExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'jpg', 'jpeg', 'png', 'gif'];
        $return = [];

        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);

        if (!is_array($existingFileNames)) {
            $existingFileNames = [$existingFileNames];
        }

        foreach ($existingFileNames as $index => $existingFileName) {
            $file = is_array($files) ? $files[$index] : $files;
            $fileExtension = strtolower($file->extension());

            if (in_array($fileExtension, $allowedDocumentExtensions)) {
                $imageName = $existingFileName;
                $fileData = ['imageName' => $imageName];

                if ($includeFileInfo) {
                    $fileSize = $file->getSize();
                    $fileData['fileSize'] = formatFileSize($fileSize);
                    $fileData['fileType'] = $file->getClientMimeType();
                }

                $mime = $file->getMimeType();
                if ($includeThumbnail && str_starts_with($mime, 'image/')) {
                    $thumbnailPath = $path . 'thumbnails/' . $imageName;
                    generateThumbnail($file->getPathname(), $thumbnailPath, 120, 120);
                    $fileData['thumbnailPath'] = $thumbnailPath;
                }

                if ($file->move($path, $imageName)) {
                    $return[] = $includeFileInfo ? $fileData : $imageName;
                }
            }
        }
        return $return;
    }
}

// START Generate Thumbnail
if (!function_exists('generateThumbnail')) {
    function generateThumbnail($sourcePath, $destinationPath, $width, $height)
    {
        list($originalWidth, $originalHeight) = getimagesize($sourcePath);

        // Ensure the directory exists before saving the thumbnail
        $thumbnailDir = dirname($destinationPath);
        if (!file_exists($thumbnailDir)) {
            mkdir($thumbnailDir, 0777, true);
        }

        $sourceImage = imagecreatefromstring(file_get_contents($sourcePath));
        $thumbnail = imagecreatetruecolor($width, $height);
        imagecopyresampled($thumbnail, $sourceImage, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);

        // Ensure the directory exists before saving the thumbnail
        $thumbnailDir = dirname($destinationPath);
        if (!file_exists($thumbnailDir)) {
            mkdir($thumbnailDir, 0777, true);
        }

        if (!imagejpeg($thumbnail, $destinationPath, 80)) {
            // Handle the error, e.g., log it or return a meaningful error message
            return false;
        }

        imagedestroy($sourceImage);
        imagedestroy($thumbnail);

        return true;
    }
}

// START Formate File Size
if (!function_exists('formatFileSize')) {
    function formatFileSize($size)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        for ($i = 0; $size > 1024; $i++) {
            $size /= 1024;
        }
        return round($size, 2) . ' ' . $units[$i];
    }
}

// Parse File Size
if (!function_exists('parseFileSize')) {
    function parseFileSize($formattedSize)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $parts = explode(' ', $formattedSize);
        if (count($parts) === 2) {
            list($size, $unit) = $parts;

            $unitIndex = array_search($unit, $units);
            if ($unitIndex !== false) {
                $size *= pow(1024, $unitIndex);
                return $size;
            }
        }
        // Handle invalid format or missing unit
        return false;
    }
}

// Update Image
if (!function_exists('updateImage')) {
    function updateImage($files, $path, $old_file)
    {
        if (isset($files) && !empty($files)) {
            if (is_array($files)) {
                $return = [];
                foreach ($files as $key => $file) {
                    $return[] = uploadFile(public_path() . $path, $file);
                }
                if (is_array($old_file)) {
                    foreach ($old_file as $oldFile) {
                        $filePath = public_path() . $path . $oldFile;
                        deleteImage($filePath);
                    }
                } else {
                    $filePath = public_path() . $path . $old_file;
                    deleteImage($filePath);
                }
                return $return;
            } else {
                if (isset($files) && !empty($files)) {
                    $file = uploadFile(public_path() . $path, $files);
                    $filePath = public_path() . $path . $old_file;
                    deleteImage($filePath);
                    return $file;
                }
            }
        } else {
            return $old_file;
        }
    }
}

// Delete Image
if (!function_exists('deleteImage')) {
    function deleteImage($filePath)
    {

        if (File::exists($filePath)) {
            File::delete($filePath);
            return true;
        }
        return false;
    }
}

// Delete File
if (!function_exists('deleteFile')) {
    function deleteFile($filePath, $fileName, $includeThumbnail = false)
    {
        $file =  public_path() . $filePath . '/' . $fileName;
        if (File::exists($file)) {
            File::delete($file);
            if ($includeThumbnail) {
                $file =  public_path() . $filePath . '/thumbnails/' . $fileName;
                if (File::exists($file)) {
                    File::delete($file);
                }
            }
            return true;
        }
        return false;
    }
}

// Move File
if (!function_exists('moveFile')) {
    function moveFile($sourceDirectory, $destinationDirectory, $filename, $moveThumbnail = false)
    {
        $sourcePath = public_path($sourceDirectory . '/' . $filename);
        $destinationPath = public_path($destinationDirectory . '/' . $filename);

        // Check if the source directory exists
        if (is_dir(public_path($sourceDirectory))) {

            // Check if the original file exists
            if (file_exists($sourcePath)) {

                // Ensure the destination directory exists
                $destinationDir = public_path($destinationDirectory);
                if (!is_dir($destinationDir)) {
                    mkdir($destinationDir, 0777, true);
                }

                // Move the original file
                if (rename($sourcePath, $destinationPath)) {

                    // Move the thumbnail if requested
                    if ($moveThumbnail) {
                        $thumbnailFilename = $filename;
                        $thumbnailSourcePath = public_path($sourceDirectory . '/thumbnails/' . $thumbnailFilename);
                        $thumbnailDestinationPath = public_path($destinationDirectory . '/thumbnails/' . $thumbnailFilename);

                        // Ensure the thumbnail source file exists
                        if (file_exists($thumbnailSourcePath)) {

                            // Ensure the thumbnail source directory exists
                            $thumbnailSourceDir = dirname($thumbnailSourcePath);
                            if (is_dir($thumbnailSourceDir)) {

                                // Ensure the thumbnail destination directory exists
                                $thumbnailDestinationDir = dirname($thumbnailDestinationPath);
                                if (!is_dir($thumbnailDestinationDir)) {
                                    mkdir($thumbnailDestinationDir, 0777, true);
                                }

                                // Move the thumbnail
                                rename($thumbnailSourcePath, $thumbnailDestinationPath);
                            }
                        }
                    }
                    return true;
                } else {
                    return false; // Unable to move the original file
                }
            } else {
                return false; // Original file doesn't exist
            }
        }
        return false; // Source directory doesn't exist
    }
}

// Array Flatten
if (!function_exists('array_flatten')) {
    function array_flatten($array)
    {
        if (!is_array($array)) {
            return FALSE;
        }
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, array_flatten($value));
            } else {
                $result[] = $value;
            }
        }
        return $result;
    }
}
