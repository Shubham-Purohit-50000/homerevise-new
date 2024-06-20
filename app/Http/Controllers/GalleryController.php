<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use App\Models\Gallery;

class GalleryController extends Controller
{
    public function index(Request $request){
        $galleryImages = Gallery::all();
        return view('gallery.index',compact('galleryImages'));
    }

    public function bulkUpload(Request $request){ 
        function formatSizeUnits($bytes) {
            $units = array('B', 'KB', 'MB', 'GB', 'TB');
            $i = 0;
            while ($bytes >= 1024) {
                $bytes /= 1024;
                $i++;
            }
            return round($bytes, 2) . ' ' . $units[$i];
        }
        if($request->file){
            $file = $request->file('file');
            $zipPath = $request->file('file')->store('uploads/zips', 'public');

            // Get the full path to the uploaded zip file
            $zipFilePath = Storage::disk('public')->path($zipPath);

            // Extract the images from the zip file
            $extractedFiles = [];

            $zip = new \ZipArchive();
            if ($zip->open($zipFilePath) === true) {
                $extractPath = public_path('storage/uploads/images/gallery/');
                $zip->extractTo($extractPath);
                $zip->close();

                    // Get the list of extracted files
                $extractedFiles = [];
                $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($extractPath));
                foreach ($iterator as $file) {
                    if ($file->isFile()) {
                        $fileInfo = new \SplFileInfo($file);
                        $fileNameWithExtension = $fileInfo->getFilename();
                        $checkUrl = asset('/storage/uploads/images/gallery/'. $fileNameWithExtension);
                        $checkUrlInGallery = Gallery::where('url','=',$checkUrl)->exists();
                        if(!$checkUrlInGallery){
                            $extractedFiles[] = $file->getPathname();
                        }
                    }
                }
                // dd($extractedFiles);
                $fileURLs = [];
                foreach ($extractedFiles as $filePath) { 
                    $fileInfo = pathinfo($filePath);
                    $fileSize = filesize($filePath);
                    $fileURLs[] = [
                        'name' => $fileInfo['filename'],
                        'extension' => $fileInfo['extension'],
                        'size' => $fileSize, // Size in bytes
                        'size_formatted' => formatSizeUnits($fileSize),
                        'url' => asset(str_replace(public_path(), '', $filePath))
                    ];
                } 
                foreach($fileURLs as $item){ 
                    $gallery = new Gallery;
                    
                    $gallery->name = $item['name'];
                    $gallery->extension = $item['extension'];
                    $gallery->size = $item['size_formatted'];
                    $gallery->url = $item['url'];

                    $gallery->save();
                }
                
            }
            return response()->json(['message' => 'File uploaded successfully','errors' => false]);
        }
    }
    public function edit(Request $request,$id){
        $gallery = Gallery::find($id);

        return view('gallery.edit',compact('gallery'));
    }
    public function update(Request $request){
        $gallery = Gallery::find($request->input('id'));
        $oldName = $request->input('oldName');
        $oldExt = $request->input('oldExt');
        $newName = $request->input('name');
        // Get the old and new file paths
        $oldFilePath = public_path('storage/uploads/images/gallery/' . $oldName . '.' . $oldExt);
        $newFilePath = public_path('storage/uploads/images/gallery/' . $newName . '.' . $oldExt);
        $newUrl = asset('storage/uploads/images/gallery/'. $newName . '.' . $oldExt);
        // Rename the file
        if (file_exists($oldFilePath)) {
            rename($oldFilePath, $newFilePath);

            $gallery->url = $newUrl; 
        } else {
            return response()->json(['success' => false, 'message' => 'File not found'], 404);
        }

        $fileName = $newName;
        
        if ($request->hasFile('updatedImageFile')) {
            $filePath = public_path('/uploads/images/gallery/' . $oldName . '.' . $oldExt);

            // Delete the file from the server
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $file = $request->file('updatedImageFile');
            $extension = $file->extension(); 
            $filePath = '/storage/uploads/images/gallery/' . $fileName. '.' . $extension;
            $file->storeAs('/uploads/images/gallery/', $fileName. '.' . $extension, 'public');

            $imageUrl = asset($filePath);

            $gallery->url = $imageUrl; 
        } 
        $gallery->name = $newName;

        $gallery->save();
    }
    public function destroy(Request $request)
    {
        $imageId = $request->input('id');
        $gallery = Gallery::find($imageId);
        if (!$gallery) {
            return response()->json(['success' => false, 'message' => 'Image not found'], 404);
        }
        // Get the file path
        $filePath = public_path('storage/uploads/images/gallery/' . $gallery->name . '.' . $gallery->extension);

        // Delete the file from the server
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        $gallery->delete();

        return redirect()->back()
            ->with('success', 'Image deleted successfully');
    }
    
}