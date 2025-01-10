<?php

namespace App\Traits;

use Str;

trait FileManageTrait
{
    public function uploadImage($query, $old = null)
    {
        $allowExt = ['jpeg', 'png', 'jpg', 'gif', 'svg'];
        $ext = strtolower($query->getClientOriginalExtension());

        // Check if the file size is within the limit and the extension is allowed
        if ($query->getSize() <= 5100000 && in_array($ext, $allowExt)) {
            // Delete the old file if it exists
            if ($old !== null) {
                self::deleteImage($old);
            }

            $image_name = Str::random(20);
            $image_full_name = $image_name.'.'.$ext;
            $upload_path = 'assets/general/images/';
            $image_url = $upload_path.$image_full_name;

            // Move the uploaded file
            $success = $query->move($upload_path, $image_full_name);

            // If file moved successfully, return the image URL
            if ($success) {
                return str_replace('assets/', '', $image_url);
            }
        }

        abort('406', 'File upload failed. Max file size: 5MB. Allowed formats: JPEG, PNG, JPG, GIF, SVG');

    }

    public function uploadFile($query, $old = null)
    {
        $allowExt = ['zip', 'rar', 'pdf', 'doc', 'docx', 'txt', 'csv', 'xml', 'json', 'ppt', 'pptx', 'ods', 'odt', 'xls', 'xlsx', 'png', 'jpg', 'gif', 'svg'];
        $ext = strtolower($query->getClientOriginalExtension());

        // Check if the file size is within the limit and the extension is allowed
        if ($query->getSize() <= 5100000 && in_array($ext, $allowExt)) {
            // Delete the old file if it exists
            if ($old !== null) {
                self::deleteFile($old);
            }

            $file_name = Str::random(20);
            $file_full_name = $file_name.'.'.$ext;
            $upload_path = 'assets/general/files/';
            $file_url = $upload_path.$file_full_name;

            // Move the uploaded file
            $success = $query->move($upload_path, $file_full_name);

            // If file moved successfully, return the file URL
            if ($success) {
                return str_replace('assets/', '', $file_url);
            }
        }

        abort('406', 'File upload failed. Max file size: 5MB. Allowed formats: ZIP, RAR, PDF, DOC, DOCX, TXT, CSV, XML, JSON, PPT, PPTX, ODS, ODT, XLS, XLSX');
    }

    public function deleteImage($path): void
    {
        if (file_exists('assets/'.$path)) {
            @unlink('assets/'.$path);
        }
    }

    public function deleteFile($path): void
    {
        if (file_exists('assets/'.$path)) {
            @unlink('assets/'.$path);
        }
    }
}
