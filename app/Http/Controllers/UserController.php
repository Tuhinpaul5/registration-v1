<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Repositories\FileRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\FileData;
use Barryvdh\DomPDF\Facade\Pdf;
use Log;

class UserController extends Controller
{
    protected UserRepository $userRepository;
    protected FileRepository $fileRepository;

    public function __construct(UserRepository $userRepository, FileRepository $fileRepository)
    {
        $this->userRepository = $userRepository;
        $this->fileRepository = $fileRepository;
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'location' => 'required|string|',
                'password' => 'required|string',
            ]);


            if ($validator->fails()) {
                return response_handler('Validation failed', false, 422, ['errors' => $validator->errors()->toArray()]);
            }

            $user = $this->userRepository->insert_user($validator->validated());

            if ($request->has('image')) {
                try {
                    $imageData = $request->input('image');

                    if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $matches)) {
                        $extension = strtolower($matches[1]);
                        $imageData = substr($imageData, strpos($imageData, ',') + 1);
                    } else {
                        $extension = 'png';
                    }

                    $decodedImage = base64_decode($imageData);

                    if ($decodedImage === false) {
                        throw new \Exception('Base64 decode failed');
                    }

                    $filename = time() . '_' . $validator->validated()['name'] . '.' . $extension;

                    Storage::disk('minio')->put('profile_pictures/' . $filename, $decodedImage);

                    FileData::create([
                        'email' => $user->email,
                        'image' => $filename,
                    ]);

                } catch (\Exception $e) {
                    return response_handler('Image upload failed', false, 500, ['error' => $e->getMessage()]);
                }
            }

            return response_handler(
                'User registered successfully',
                true,
                201,
                [
                    'user' => $user->toArray(),
                    'download_link' => url('/download-pdf?email=' . $user->email)
                ]
            );
        } catch (\Exception $e) {
            Log::error("Registration error: " . $e->getMessage());
            return response_handler('An unexpected error occurred.', false, 500, ['error' => $e->getMessage()]);
        }
    }

    public function downloadPdf(Request $request)
    {
        try {
            $email = $request->input('email');
            $user = $this->userRepository->get_user_by_email($email);

            if (!$user) {
                return response_handler('User not found', false, 404);
            }

            // Fetch image linked to user
            $fileData = $this->fileRepository->get_file_by_email($email);
            $imageBase64 = null;

            if ($fileData) {
                $filePath = "profile_pictures/{$fileData->image}";

                if (Storage::disk('minio')->exists($filePath)) {
                    $fileContent = Storage::disk('minio')->get($filePath);
                    $extension = pathinfo($fileData->image, PATHINFO_EXTENSION);
                    $imageBase64 = 'data:image/' . $extension . ';base64,' . base64_encode($fileContent);
                }
            }


            Log::info('File content length: ' . ($fileContent ? strlen($fileContent) : 'No file content'));
            // Pass data to blade (base64 image instead of URL)
            $pdf = Pdf::loadView('pdf.user', [
                'user' => $user,
                'imageBase64' => $imageBase64,
            ]);
            $time = time(); 
            return $pdf->download("user_{$time}.pdf");

        } catch (\Exception $e) {
            Log::error("PDF generation error: " . $e->getMessage());
            return response_handler('PDF generation failed', false, 500, ['error' => $e->getMessage()]);
        }
    }
}
