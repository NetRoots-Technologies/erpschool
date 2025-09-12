<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;

class ImageHelper
{
    public static function uploadImage(UploadedFile $file, $destinationPath = 'employee_files')
    {
        $fileNameToStore = null;

        if ($file->isValid()) {
            $filenameWithExt = $file->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $filename = preg_replace("/[^A-Za-z0-9 ]/", '', $filename);
            $filename = preg_replace("/\s+/", '-', $filename);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;

            $file->move($destinationPath, $fileNameToStore);
        }

        return $fileNameToStore;
    }

    public static function Image($request, $fieldName)
    {
        if ($request->hasFile($fieldName)) {
            $image = $request->file($fieldName);
            $filename = time() . '.' . $image->getClientOriginalExtension();
            StudentPictures::make($image)->save(storage_path('/uploads/' . $filename));
            // You might want to return the filename or some indication of success
            return $filename;
        }
        // Return null if no file was uploaded
        return null;
    }
}
