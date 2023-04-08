<?php

namespace App\Traits;

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
        $interventionImage = \Intervention\Image\Facades\Image::make($imageToSave)->stream("jpg", 80);

        // Save image on server
        $savedImagePath = $interventionImage->storeAs($imageFolder, $filename);
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
