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
        $filename = 'p_' . $paymentRefId . '_' . $paymentId . '.jpg';
        $interventionImage = \ImageConvert::make($imageToSave)->stream("jpg", 80);

        // Save image on server
        $savedImagePath = $interventionImage->storeAs($imageFolder, $filename);
    }

    public function GetReceiptUrl($paymentRefId, $paymentId) {
        $domainPath = env('GOOGLE_CLOUD_STORAGE_API_PATH');

        if($domainPath == null) {
            return '';
        }

        // Image Folder
        $imageFolder = 'payments';

        // Image file url
        return $domainPath . '/' . $imageFolder . 'p_' . $paymentRefId . '_' . $paymentId . '.jpg';
    }

}
