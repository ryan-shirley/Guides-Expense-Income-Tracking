<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait ImageHandler
{
    public function SaveReceipt($imageToSave, $paymentRefId, $paymentId) {
        if($imageToSave == null) {
            return;
        }

        // Image Folder
        $imageFolder = 'payments';

        // Image file name
        $filename = $this->GenerateFileName($paymentRefId, $paymentId);
        $interventionImage = \Intervention\Image\Facades\Image::make($imageToSave)->encode("jpg", 80);

        // Save image on server
        Storage::put($imageFolder . '/' . $filename, $interventionImage->__toString());
    }

    public function GetReceiptUrl($paymentRefId, $paymentId) {
        $domainPath = env('GOOGLE_CLOUD_STORAGE_API_PATH');

        // Image Folder
        $imageFolder = 'payments';

        if($domainPath == null) {
            return '';
        }

        // Image file url
        return $domainPath . '/' . $imageFolder . '/' . $this->GenerateFileName($paymentRefId, $paymentId);
    }

    private function GenerateFileName($paymentRefId, $paymentId) {
        return 'p_' . $paymentRefId . '_' . $paymentId . '.jpg';
    }

}
