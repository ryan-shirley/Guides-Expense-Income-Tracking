<?php

namespace App\Traits;

trait ImageHandler
{
    public function SaveReceipt($imageToSave, $paymentRefId, $paymentId) {
        // User
        $user =  auth()->user();

        // Image Folder
        $imageFolder = 'payments';

        // Image file name
        $filename = '/p_' . $paymentRefId . '_' . $paymentId;

        // Save image on server
        $savedImagePath = $imageToSave->storeAs($imageFolder, $filename);
    }
}
