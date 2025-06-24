<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelapor' => 'required|string',
            'nomor_pelapor' => 'required|string',
            'alamat_pelapor' => 'required|string',
            'tanggal_pelaporan' => 'required|date',
            'deskripsi' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'status_laporan' => 'required|string',
        ]);

        $laporan = new \App\Models\Laporan($validated);

        if ($request->user()) {
            $laporan->user_id = $request->user()->id;
        }

        $laporan->save();

        return response()->json(['message' => 'Laporan berhasil disimpan'], 201);
    }


    public function index()
    {
        $laporans = Laporan::orderBy('created_at', 'desc')->get();
        return response()->json($laporans);
    }

    public function statistik()
    {
        $total = \App\Models\Laporan::count();
        $pending = \App\Models\Laporan::where('status_laporan', 'Pending')->count();
        $inProgress = \App\Models\Laporan::where('status_laporan', 'In Progress')->count();
        $selesai = \App\Models\Laporan::where('status_laporan', 'Selesai')->count();

        return response()->json([
            'total' => $total,
            'pending' => $pending,
            'inProgress' => $inProgress,
            'selesai' => $selesai,
        ]);
    }
    public function updateStatus(Request $request, $id)
    {
        $user = $request->user();

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status_laporan' => 'required|in:Pending,In Progress,Selesai',
        ]);

        $laporan = \App\Models\Laporan::find($id);
        if (!$laporan) {
            return response()->json(['message' => 'Laporan tidak ditemukan'], 404);
        }

        $laporan->status_laporan = $request->status_laporan;
        $laporan->save();

        return response()->json(['message' => 'Status berhasil diperbarui', 'data' => $laporan]);
    }
    public function show($id)
    {
        $laporan = Laporan::find($id);

        if (!$laporan) {
            return response()->json(['message' => 'Laporan tidak ditemukan'], 404);
        }

        return response()->json($laporan);
    }
    public function update(Request $request, $id)
    {
        $user = $request->user();

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'nama_pelapor' => 'required|string',
            'nomor_pelapor' => 'required|string',
            'alamat_pelapor' => 'required|string',
            'tanggal_pelaporan' => 'required|date',
            'deskripsi' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'status_laporan' => 'required|in:Pending,In Progress,Selesai',
        ]);

        $laporan = Laporan::find($id);

        if (!$laporan) {
            return response()->json(['message' => 'Laporan tidak ditemukan'], 404);
        }

        $laporan->update($validated);

        return response()->json(['message' => 'Laporan berhasil diperbarui', 'data' => $laporan]);
    }
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $laporan = Laporan::find($id);

        if (!$laporan) {
            return response()->json(['message' => 'Laporan tidak ditemukan'], 404);
        }

        $laporan->delete();

        return response()->json(['message' => 'Laporan berhasil dihapus']);
    }
}
