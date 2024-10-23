use Illuminate\Http\Request;
use thiagoalessio\TesseractOCR\TesseractOCR;

class OCR
{
    public function extractText(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'image' => 'required|image|max:2048', // Max size of 2MB
        ]);
        // Store the uploaded image
        $imagePath = $request->file('image')->store('ocr_images');
        // Run Tesseract OCR on the stored image
        $ocr = new TesseractOCR(storage_path('app/' . $imagePath));
        $text = $ocr->run();
        // Return the extracted text
        return response()->json(['text' => $text]);
    }
}