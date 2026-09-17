<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            // Retrieve all announcements, ordered by publication_date descending
            $announcements = Announcement::orderBy('publication_date', 'desc')
                                       ->orderBy('created_at', 'desc')
                                       ->get();
            
            return response()->json($announcements);
        } catch (\Exception $e) {
            Log::error('Error fetching announcements: ' . $e->getMessage());
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data pengumuman'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'type' => 'nullable|string|max:100',
                'content' => 'required|string',
                'publication_date' => 'required|date',
            ]);

            // Create a new Announcement instance and save it to the database
            $announcement = Announcement::create($validatedData);

            // Return a JSON response indicating success
            return response()->json([
                'message' => 'Pengumuman berhasil ditambahkan!',
                'announcement' => $announcement
            ], 201);
        } catch (ValidationException $e) {
            // Return validation errors if validation fails
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating announcement: ' . $e->getMessage());
            return response()->json([
                'message' => 'Terjadi kesalahan saat menambahkan pengumuman: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            // Find an announcement by its ID, or throw a 404 exception if not found
            $announcement = Announcement::findOrFail($id);
            return response()->json($announcement);
        } catch (\Exception $e) {
            Log::error('Error fetching announcement: ' . $e->getMessage());
            return response()->json([
                'message' => 'Pengumuman tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            // Find the announcement by its ID
            $announcement = Announcement::findOrFail($id);

            // Validate the incoming request data
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'type' => 'nullable|string|max:100',
                'content' => 'required|string',
                'publication_date' => 'required|date',
            ]);

            // Update the announcement with the validated data
            $announcement->update($validatedData);

            // Return a JSON response indicating success
            return response()->json([
                'message' => 'Pengumuman berhasil diperbarui!',
                'announcement' => $announcement->fresh()
            ]);
        } catch (ValidationException $e) {
            // Return validation errors if validation fails
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating announcement: ' . $e->getMessage());
            return response()->json([
                'message' => 'Terjadi kesalahan saat memperbarui pengumuman: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            // Find the announcement by its ID and delete it
            $announcement = Announcement::findOrFail($id);
            $announcementTitle = $announcement->title;
            $announcement->delete();

            // Return a JSON response indicating success
            return response()->json([
                'message' => "Pengumuman '{$announcementTitle}' berhasil dihapus!"
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting announcement: ' . $e->getMessage());
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus pengumuman: ' . $e->getMessage()
            ], 500);
        }
    }
}