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
        $filename = 'p_' . $paymentRefId . '_' . $paymentId . '.' . $imageToSave->extension();

        // Save image on server
        $savedImagePath = $imageToSave->storeAs($imageFolder, $filename);
    }
}
