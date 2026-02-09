<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI;
use PhpOffice\PhpWord\IOFactory;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Str;

class QuestionParseController extends Controller
{
    public function extract(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,docx|max:2048',
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        $text = '';

        if ($extension === 'docx') {
            $phpWord = IOFactory::load($file->getPathname());
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getText')) {
                        $text .= $element->getText() . "\n";
                    }
                }
            }
        } elseif ($extension === 'pdf') {
            $parser = new Parser();
            $pdf = $parser->parseFile($file->getPathname());
            $text = $pdf->getText();
        }

        if (Str::length($text) < 100) {
            return response()->json(['error' => 'Failed to extract enough text.'], 422);
        }

        $prompt = "Extract all multiple-choice questions from the following text. For each question, return this JSON format:\n\n".
                  '{
  "question": "...",
  "options": ["...", "...", "...", "..."],
  "answer": "..."
}' . "\n\nText:\n" . $text;

        $client = OpenAI::client(config('services.openai.key'));

        $response = $client->chat()->create([
            'model' => 'gpt-4',
            'messages' => [
                ['role' => 'system', 'content' => 'You are an expert in parsing exam question documents.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.2,
        ]);

        $result = $response->choices[0]->message->content;

        return response()->json([
            'questions' => json_decode($result, true) ?? $result
        ]);
    }
}
