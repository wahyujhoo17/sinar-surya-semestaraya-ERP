<?php

namespace App\Traits;

use App\Services\QRCodeService;
use Illuminate\Support\Facades\Auth;

trait HasPDFQRCode
{
    /**
     * Generate QR code for document signature
     *
     * @param string $documentType
     * @param string $documentNumber
     * @param object|null $user
     * @param string $action
     * @param array $additionalData
     * @return string Base64 encoded QR code
     */
    public function generateDocumentQRCode($documentType, $documentNumber, $user = null, $action = 'signed', $additionalData = [])
    {
        $qrService = app(QRCodeService::class);

        if (!$user) {
            $user = Auth::user();
        }

        $userData = [
            'user_name' => $user->name,
            'user_email' => $user->email,
            'action' => $action,
            'timestamp' => now()->toISOString()
        ];

        $allData = array_merge($userData, $additionalData);

        return $qrService->generateDocumentQRCode($documentType, $documentNumber, $user->name, $allData);
    }

    /**
     * Generate signature QR code for user
     *
     * @param object|null $user
     * @param string $action
     * @return string Base64 encoded QR code
     */
    public function generateSignatureQRCode($user = null, $action = 'signed')
    {
        $qrService = app(QRCodeService::class);

        if (!$user) {
            $user = Auth::user();
        }

        $role = $user->roles->first()->name ?? 'User';

        return $qrService->generateSignatureQRCode($user->name, $user->email, $role, $action);
    }

    /**
     * Get QR code data for PDF view
     *
     * @param string $documentType
     * @param string $documentNumber
     * @param object|null $createdBy
     * @param object|null $processedBy
     * @param array $additionalData
     * @return array
     */
    public function getPDFQRCodeData($documentType, $documentNumber, $createdBy = null, $processedBy = null, $additionalData = [])
    {
        $qrData = [];

        // QR Code for document creator
        if ($createdBy) {
            $qrData['created_qr'] = $this->generateDocumentQRCode(
                $documentType,
                $documentNumber,
                $createdBy,
                'created',
                array_merge($additionalData, ['role' => 'creator'])
            );
        }

        // QR Code for document processor/approver
        if ($processedBy) {
            $qrData['processed_qr'] = $this->generateDocumentQRCode(
                $documentType,
                $documentNumber,
                $processedBy,
                'processed',
                array_merge($additionalData, ['role' => 'processor'])
            );
        }

        // General document QR code
        $currentUser = $processedBy ?? $createdBy ?? Auth::user();
        $qrData['document_qr'] = $this->generateDocumentQRCode(
            $documentType,
            $documentNumber,
            $currentUser,
            'verified',
            $additionalData
        );

        return $qrData;
    }

    /**
     * Generate all QR codes needed for PDF display
     *
     * @param string $documentType
     * @param int $documentId
     * @param string $documentNumber
     * @param object|null $createdBy
     * @param object|null $processedBy
     * @param \Carbon\Carbon|null $processedAt
     * @param array $additionalData
     * @return array
     */
    public function generatePDFQRCodes($documentType, $documentId, $documentNumber, $createdBy = null, $processedBy = null, $processedAt = null, $additionalData = [])
    {
        $qrData = [];

        // Add document ID to additional data
        $additionalData['document_id'] = $documentId;
        $additionalData['created_at'] = $createdBy ? $createdBy->created_at->toISOString() : now()->toISOString();

        if ($processedAt) {
            $additionalData['processed_at'] = $processedAt->toISOString();
        }

        // QR Code for document creator
        if ($createdBy) {
            $qrData['created_qr'] = $this->generateDocumentQRCode(
                $documentType,
                $documentNumber,
                $createdBy,
                'created',
                array_merge($additionalData, ['role' => 'creator'])
            );
        }

        // QR Code for document processor/approver
        if ($processedBy) {
            $qrData['processed_qr'] = $this->generateDocumentQRCode(
                $documentType,
                $documentNumber,
                $processedBy,
                'processed',
                array_merge($additionalData, ['role' => 'processor'])
            );
        }

        // General document QR code for verification
        $currentUser = $processedBy ?? $createdBy ?? Auth::user();
        $qrData['document_qr'] = $this->generateDocumentQRCode(
            $documentType,
            $documentNumber,
            $currentUser,
            'verified',
            $additionalData
        );

        return $qrData;
    }
}
