<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class IndonesiaController extends Controller
{
    /* ================================================================
       1. API LOKASI INDONESIA (EMSIFA)
    ================================================================ */

    public function provinsi()
    {
        try {
            $res = Http::timeout(10)->get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
            return response()->json($res->json());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengambil data provinsi', 'message' => $e->getMessage()], 500);
        }
    }

    public function kota($provId)
    {
        try {
            $res = Http::timeout(10)->get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$provId}.json");
            return response()->json($res->json());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengambil data kota', 'message' => $e->getMessage()], 500);
        }
    }

    public function kecamatan($kotaId)
    {
        try {
            $res = Http::timeout(10)->get("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$kotaId}.json");
            return response()->json($res->json());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengambil data kecamatan', 'message' => $e->getMessage()], 500);
        }
    }

    public function kelurahan($kecId)
    {
        try {
            $res = Http::timeout(10)->get("https://www.emsifa.com/api-wilayah-indonesia/api/villages/{$kecId}.json");
            return response()->json($res->json());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengambil data kelurahan', 'message' => $e->getMessage()], 500);
        }
    }


    /* ================================================================
       2. API CUACA BMKG
    ================================================================ */

    public function cuaca($kota)
    {
        try {
            $res = Http::timeout(10)->get("https://api.bmkg.go.id/publikasi/cuaca?page=1");

            if (!$res->successful()) {
                return response()->json(['error' => 'Gagal ambil data cuaca'], 500);
            }

            $list = $res->json()['data'] ?? [];

            $cari = collect($list)->first(function ($item) use ($kota) {
                return str_contains(strtolower($item['kota'] ?? ''), strtolower($kota));
            });

            if (!$cari) {
                return response()->json(['message' => 'Kota tidak ditemukan di BMKG'], 404);
            }

            return response()->json($cari);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data cuaca', 'message' => $e->getMessage()], 500);
        }
    }


    /* ================================================================
       3. API SHALAT (AL-ADHAN)
    ================================================================ */

    public function shalat($kota)
    {
        try {
            $res = Http::timeout(10)->get("https://api.aladhan.com/v1/timingsByCity", [
                'city'    => $kota,
                'country' => 'Indonesia',
                'method'  => 11
            ]);

            if (!$res->successful()) {
                return response()->json(['error' => 'Gagal mengambil jadwal shalat'], 500);
            }

            $data = $res->json()['data'] ?? null;

            if (!$data) {
                return response()->json(['error' => 'Data shalat tidak ditemukan'], 404);
            }

            return response()->json([
                'lokasi' => $data['meta'] ?? null,
                'jadwal' => $data['timings'] ?? null,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil jadwal shalat', 'message' => $e->getMessage()], 500);
        }
    }
}
