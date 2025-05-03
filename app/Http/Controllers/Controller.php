<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Respons sukses standard
     * 
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($data = null, $message = 'Operasi berhasil', $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * Respons error standard
     * 
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse($message = 'Terjadi kesalahan', $statusCode = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $statusCode);
    }

    /**
     * Log aktivitas pengguna
     * 
     * @param string $aktivitas
     * @param string $modul
     * @param mixed $dataId
     */
    protected function logAktivitas($aktivitas, $modul, $dataId = null)
    {
        $user = Auth::user();

        if ($user) {
            \App\Models\LogAktivitas::create([
                'user_id' => $user->id,
                'aktivitas' => $aktivitas,
                'modul' => $modul,
                'data_id' => $dataId,
                'ip_address' => request()->ip(),
                'detail' => null
            ]);
        }

        return true;
    }
}
