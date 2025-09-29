<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use App\Models\Stock\StockSupplier;
use thiagoalessio\TesseractOCR\TesseractOCR;

class OCRService
{
    protected array $config;
    protected string $tempDir;

    public function __construct()
    {
        $this->config = config('services.ocr', []);
        $this->tempDir = $this->config['temp_dir'] ?? sys_get_temp_dir() . '/ocr';

        // Ensure temp directory exists
        if (!is_dir($this->tempDir)) {
            mkdir($this->tempDir, 0755, true);
        }
    }

    /**
     * Parse invoice PDF/Image and extract data with timing
     */
    public function parseDocument(UploadedFile $file): array
    {
        $startTime = microtime(true);

        try {
            // Validate file
            $this->validateFile($file);

            // Extract text based on file type
            $mimeType = $file->getMimeType();
            if (str_starts_with($mimeType, 'image/')) {
                $extractedText = $this->extractTextFromImage($file);
            } elseif ($mimeType === 'application/pdf') {
                $extractedText = $this->extractTextFromPdf($file);
            } else {
                throw new \InvalidArgumentException("Unsupported file type: {$mimeType}");
            }

            // Normalize text
            $normalizedText = $this->normalizeText($extractedText);

            // Debug: Log extracted text for troubleshooting
            Log::debug('OCR extracted text', [
                'file' => $file->getClientOriginalName(),
                'raw_text_length' => strlen($extractedText),
                'normalized_text_length' => strlen($normalizedText),
                'first_500_chars' => substr($normalizedText, 0, 500),
                'mime_type' => $mimeType
            ]);

            // Parse invoice data
            $parsedData = $this->parseInvoiceText($normalizedText);
            $parsedData['extracted_text'] = $normalizedText;
            $parsedData['file_type'] = $mimeType;
            $parsedData['confidence_score'] = $this->calculateConfidenceScore($parsedData);
            $parsedData['processing_time_ms'] = (int) ((microtime(true) - $startTime) * 1000);
            $parsedData['needs_review'] = $this->config['strict_mode'] && $parsedData['confidence_score'] < 70;

            // Debug logging (without PII)
            Log::debug('OCR processing completed', [
                'file' => $file->getClientOriginalName(),
                'text_length' => strlen($normalizedText),
                'confidence' => $parsedData['confidence_score'],
                'processing_time_ms' => $parsedData['processing_time_ms'],
                'has_invoice_number' => !empty($parsedData['invoice_number']),
                'has_date' => !empty($parsedData['invoice_date']),
                'has_total' => !empty($parsedData['grand_total']),
                'line_items_count' => count($parsedData['line_items'] ?? [])
            ]);

            return $parsedData;
        } catch (\Exception $e) {
            $processingTime = (int) ((microtime(true) - $startTime) * 1000);

            Log::error('OCR parsing failed', [
                'file' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'error' => $e->getMessage(),
                'processing_time_ms' => $processingTime
            ]);

            $result = $this->getEmptyResult();
            $result['processing_time_ms'] = $processingTime;
            $result['error'] = $e->getMessage();

            return $result;
        }
    }

    /**
     * Validate uploaded file for security and type
     */
    protected function validateFile(UploadedFile $file): void
    {
        $allowedMimes = ['image/jpeg', 'image/png', 'application/pdf'];
        $maxSize = 20 * 1024 * 1024; // 20MB

        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new \InvalidArgumentException('Unsupported file type. Only PDF, JPG, and PNG files are allowed.');
        }

        if ($file->getSize() > $maxSize) {
            throw new \InvalidArgumentException('File size exceeds maximum limit of 20MB.');
        }

        // Additional security check: ensure file extension matches mime type
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeToExt = [
            'image/jpeg' => ['jpg', 'jpeg'],
            'image/png' => ['png'],
            'application/pdf' => ['pdf']
        ];

        if (!isset($mimeToExt[$file->getMimeType()]) || !in_array($extension, $mimeToExt[$file->getMimeType()])) {
            throw new \InvalidArgumentException('File extension does not match file type.');
        }
    }

    /**
     * Extract text from image using Tesseract OCR with advanced configuration
     */
    protected function extractTextFromImage(UploadedFile $file): string
    {
        $timeout = $this->config['timeout'];

        try {
            $tesseract = new TesseractOCR($file->getPathname());
            $tesseract->lang($this->config['lang'])
                     ->psm($this->config['psm'])
                     ->oem($this->config['oem']);

            // Set timeout if supported
            if (method_exists($tesseract, 'timeout')) {
                $tesseract->timeout($timeout);
            }

            $text = $tesseract->run();

            return $text;
        } catch (\Exception $e) {
            Log::error('Tesseract OCR failed for image', [
                'file' => $file->getClientOriginalName(),
                'error' => $e->getMessage(),
                'lang' => $this->config['lang'],
                'psm' => $this->config['psm'],
                'oem' => $this->config['oem']
            ]);

            return '';
        }
    }

    /**
     * Extract text from PDF with advanced processing: text layer extraction + OCR fallback
     */
    protected function extractTextFromPdf(UploadedFile $file): string
    {
        $engine = $this->config['pdf_engine'];
        $maxPages = $this->config['max_pages'];
        $dpi = $this->config['dpi'];

        try {
            // First, try to extract text directly from PDF (if it has text layer)
            $directText = $this->extractTextFromPdfTextLayer($file, $engine);
            if (!empty(trim($directText))) {
                return $directText;
            }

            // Fallback: Convert to images and OCR
            return $this->extractTextFromPdfImages($file, $maxPages, $dpi, $engine);

        } catch (\Exception $e) {
            Log::error('PDF text extraction failed', [
                'file' => $file->getClientOriginalName(),
                'engine' => $engine,
                'error' => $e->getMessage()
            ]);

            return '';
        }
    }

    /**
     * Extract text directly from PDF text layer
     */
    protected function extractTextFromPdfTextLayer(UploadedFile $file, string $engine): string
    {
        try {
            if ($engine === 'imagick' && extension_loaded('imagick')) {
                $imagick = new \Imagick();
                $imagick->readImage($file->getPathname());
                $text = '';

                foreach ($imagick as $page) {
                    $pageText = $page->getImageProperty('text') ?: '';
                    if (!empty($pageText)) {
                        $text .= $pageText . "\n";
                    }
                }

                $imagick->clear();
                $imagick->destroy();

                return $text;
            }

            // Try poppler/pdftotext if available
            if ($engine === 'poppler' && $this->isCommandAvailable('pdftotext')) {
                $tempTextFile = tempnam($this->tempDir, 'pdf_text_') . '.txt';
                $command = "pdftotext -layout \"{$file->getPathname()}\" \"$tempTextFile\" 2>/dev/null";

                exec($command, $output, $returnCode);

                if ($returnCode === 0 && file_exists($tempTextFile)) {
                    $text = file_get_contents($tempTextFile);
                    unlink($tempTextFile);
                    return $text;
                }

                if (file_exists($tempTextFile)) {
                    unlink($tempTextFile);
                }
            }

        } catch (\Exception $e) {
            Log::debug('PDF text layer extraction failed, falling back to OCR', [
                'error' => $e->getMessage()
            ]);
        }

        return '';
    }

    /**
     * Extract text from PDF by converting to images and OCRing each page
     */
    protected function extractTextFromPdfImages(UploadedFile $file, int $maxPages, int $dpi, string $engine): string
    {
        $tempFiles = [];
        $extractedText = '';

        try {
            if ($engine === 'imagick' && extension_loaded('imagick')) {
                $imagick = new \Imagick();
                $imagick->setResolution($dpi, $dpi);
                $imagick->readImage($file->getPathname());
                $imagick->setImageFormat('png');
                $imagick->setImageAlphaChannel(\Imagick::ALPHACHANNEL_REMOVE);
                $imagick->setImageDepth(8);

                // Limit pages
                $pageCount = $imagick->getNumberImages();
                $pagesToProcess = min($pageCount, $maxPages);

                for ($i = 0; $i < $pagesToProcess; $i++) {
                    $imagick->setIteratorIndex($i);

                    // Optimize image for OCR
                    $imagick->despeckleImage();
                    $imagick->normalizeImage();
                    $imagick->contrastStretchImage(0, 0.7);

                    $tempFile = tempnam($this->tempDir, 'ocr_page_') . '.png';
                    $tempFiles[] = $tempFile;

                    $imagick->writeImage($tempFile);

                    $pageText = $this->ocrImageFile($tempFile);
                    $extractedText .= $pageText . "\n";
                }

                $imagick->clear();
                $imagick->destroy();

            } elseif ($engine === 'poppler' && $this->isCommandAvailable('pdftoppm')) {
                // Use poppler for page extraction
                $baseTempFile = tempnam($this->tempDir, 'pdf_page_');

                for ($i = 1; $i <= $maxPages; $i++) {
                    $tempFile = "{$baseTempFile}-{$i}.png";
                    $tempFiles[] = $tempFile;

                    $command = "pdftoppm -png -r {$dpi} -f {$i} -l {$i} \"{$file->getPathname()}\" \"{$baseTempFile}\" 2>/dev/null";
                    exec($command, $output, $returnCode);

                    if ($returnCode === 0 && file_exists($tempFile)) {
                        $pageText = $this->ocrImageFile($tempFile);
                        $extractedText .= $pageText . "\n";
                    } else {
                        break; // No more pages
                    }
                }
            }

            return $extractedText;

        } catch (\Exception $e) {
            Log::error('PDF image OCR failed', [
                'file' => $file->getClientOriginalName(),
                'error' => $e->getMessage()
            ]);

            return '';
        } finally {
            // Clean up temporary files
            foreach ($tempFiles as $tempFile) {
                if (file_exists($tempFile)) {
                    unlink($tempFile);
                }
            }
        }
    }

    /**
     * OCR a single image file
     */
    protected function ocrImageFile(string $imagePath): string
    {
        try {
            $tesseract = new TesseractOCR($imagePath);
            $tesseract->lang($this->config['lang'])
                     ->psm($this->config['psm'])
                     ->oem($this->config['oem']);

            if (method_exists($tesseract, 'timeout')) {
                $tesseract->timeout($this->config['timeout']);
            }

            return $tesseract->run();
        } catch (\Exception $e) {
            Log::warning('Failed to OCR image file', [
                'path' => basename($imagePath),
                'error' => $e->getMessage()
            ]);

            return '';
        }
    }

    /**
     * Check if a command is available on the system
     */
    protected function isCommandAvailable(string $command): bool
    {
        $which = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'where' : 'which';
        exec("$which $command 2>/dev/null", $output, $returnCode);
        return $returnCode === 0;
    }

    /**
     * Parse extracted text to find invoice information
     */
    protected function parseInvoiceText(string $text): array
    {
        \Illuminate\Support\Facades\Log::debug('Parsing invoice text, length: ' . strlen($text));
        \Illuminate\Support\Facades\Log::debug('First 200 chars: ' . substr($text, 0, 200));
        
        $data = $this->getEmptyResult();
        
        // Enhanced invoice number patterns (Turkish) - More flexible
        $invoicePatterns = [
            '/(?:fatura\s*no|fatura\s*numarası|e-arşiv\s*fatura|e-fatura)[:\s]*([A-Z0-9\-\/\.]+)/ui',
            '/(?:fatura|invoice|belge)\s*(?:no|number|numarası|seri)?[:\s]*([A-Z0-9\-\/\.]+)/ui',
            '/(?:belge|seri)\s*(?:no|numarası)?[:\s]*([A-Z0-9\-\/\.]+)/ui',
            '/(?:INV|FB|SF|ADM|MDS|DH|GIB|FAT|FTR)[\-\s\/\.]*([0-9\-\/\.]+)/i',
            '/no[:\s]*([0-9]{4}[\-\/\.][0-9\-\/\.]+)/ui',
            '/([A-Z]{2,}[\-\s]*[0-9]{4}[\-\s]*[0-9\-\/\.]+)/ui', // FTR-2024-001 format
            '/([0-9]{4}[\-\/\.][A-Z]{2,}[\-\s]*[0-9\-\/\.]+)/ui', // 2024-DH-001 format
        ];
        
        foreach ($invoicePatterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $number = trim($matches[1]);
                // Clean up common false positives
                if (strlen($number) > 3 && $number !== 'BELGESI' && !preg_match('/^\d{1,2}[\/\-]\d{1,2}/', $number)) {
                    $data['invoice_number'] = $number;
                    break;
                }
            }
        }
        
        // Enhanced date patterns (Turkish format) - More flexible
        $datePatterns = [
            '/(?:fatura\s*tarihi|tarih|date|düzenlenme\s*tarihi|düzenleme\s*tarihi|tarihçe)[:\s]*(\d{1,2}[\.\/\-]\d{1,2}[\.\/\-]\d{2,4})/ui',
            '/(\d{1,2}[\.\/\-]\d{1,2}[\.\/\-]\d{4})/',
            '/(?:^|\n)(\d{1,2}[\.\/\-]\d{1,2}[\.\/\-]\d{4})(?:\n|$)/m',
            '/(\d{4}[\.\/\-]\d{1,2}[\.\/\-]\d{1,2})/', // YYYY-MM-DD format
            '/(?:tarih|date)[:\s]*(\d{1,2}[\.\/\-]\d{1,2}[\.\/\-]\d{2,4})/ui',
        ];
        
        foreach ($datePatterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                try {
                    $dateStr = $matches[1];
                    $dateParts = preg_split('/[\.\/\-]/', $dateStr);
                    if (count($dateParts) === 3) {
                        $day = str_pad($dateParts[0], 2, '0', STR_PAD_LEFT);
                        $month = str_pad($dateParts[1], 2, '0', STR_PAD_LEFT);
                        $year = strlen($dateParts[2]) == 2 ? '20' . $dateParts[2] : $dateParts[2];
                        
                        if ($year >= 2020 && $year <= 2030 && $month <= 12 && $day <= 31) {
                            $data['invoice_date'] = "$year-$month-$day";
                            break;
                        }
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
        }
        
        // Enhanced amount patterns (Turkish lira) - Look for the final total - More flexible
        $amountPatterns = [
            '/(?:vergiler\s*dahil\s*toplam\s*tutar|ödenecek\s*tutar|genel\s*toplam\s*tutar)[:\s]*([^\s]+)\s*(?:tl|₺|TRY)/ui',
            '/(?:fatura\s*tutarı|genel\s*toplam|toplam\s*tutar|net\s*tutar)[:\s]*([^\s]+)\s*(?:tl|₺|TRY)/ui',
            '/(?:total|toplam|ara\s*toplam)[:\s]*([^\s]+)\s*(?:tl|₺|TRY)/ui',
            '/([^\s]+)\s*(?:₺|tl|TRY)\s*$/ui',
            '/(?:₺|tl|TRY)\s*([^\s]+)/ui',
            '/tutar[:\s]*([^\s]+)\s*(?:tl|₺|TRY)/ui',
            '/toplam[:\s]*([^\s]+)\s*(?:tl|₺|TRY)/ui',
        ];

        foreach ($amountPatterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $amountString = $matches[1];
                $parsedAmount = $this->formatMoneyToFloat($amountString);

                if ($parsedAmount !== null && $parsedAmount > 0) {
                    $data['grand_total'] = $parsedAmount;
                    break;
                }
            }
        }
        
        // Extract line items with enhanced patterns
        $data['line_items'] = $this->extractLineItems($text);
        
        // Extract supplier information
        $data['supplier_info'] = $this->extractSupplierInfo($text);
        
        // Supplier validation against database
        $data['supplier_match'] = $this->validateSupplier($text);
        
        // Calculate subtotal and VAT if not found
        if (!empty($data['line_items']) && empty($data['subtotal'])) {
            $subtotal = array_sum(array_map(function($item) {
                return $item['quantity'] * $item['unit_price'];
            }, $data['line_items']));
            $data['subtotal'] = $subtotal;
            
            if (empty($data['vat_total']) && !empty($data['grand_total'])) {
                $data['vat_total'] = $data['grand_total'] - $subtotal;
            }
        }
        
        return $data;
    }

    /**
     * Normalize extracted text with advanced Unicode processing
     */
    protected function normalizeText(string $text): string
    {
        // Convert to UTF-8 if not already
        if (!mb_check_encoding($text, 'UTF-8')) {
            $text = mb_convert_encoding($text, 'UTF-8', 'auto');
        }

        // Unicode NFKC normalization (canonical decomposition followed by canonical composition)
        // This helps with ligatures and composed characters
        if (function_exists('normalizer_normalize')) {
            $text = normalizer_normalize($text, \Normalizer::FORM_KC);
        }

        // Fix common ligatures that OCR might misinterpret
        $ligatureReplacements = [
            'ﬁ' => 'fi',
            'ﬂ' => 'fl',
            'ﬃ' => 'ffi',
            'ﬄ' => 'ffl',
            'ﬅ' => 'ft',
            'ﬆ' => 'st',
            'Æ' => 'AE',
            'æ' => 'ae',
            'Œ' => 'OE',
            'œ' => 'oe',
            'ß' => 'ss',
        ];
        $text = strtr($text, $ligatureReplacements);

        // Normalize line endings
        $text = str_replace(["\r\n", "\r"], "\n", $text);

        // Clean up excessive whitespace but preserve structure
        $text = preg_replace('/\h+/', ' ', $text); // Horizontal whitespace to single space
        $text = preg_replace('/\v+/', "\n", $text); // Vertical whitespace to single newline

        // Fix common OCR character misrecognitions for Turkish
        $ocrFixes = [
            // Common OCR errors for Turkish characters
            'I' => 'I', // Keep as is (dotless I)
            'i' => 'i', // Keep as is (dotted i)
            'İ' => 'İ', // Keep as is
            'ı' => 'ı', // Keep as is
            'Ğ' => 'Ğ', // Keep as is
            'ğ' => 'ğ', // Keep as is
            'Ü' => 'Ü', // Keep as is
            'ü' => 'ü', // Keep as is
            'Ş' => 'Ş', // Keep as is
            'ş' => 'ş', // Keep as is
            'Ç' => 'Ç', // Keep as is
            'ç' => 'ç', // Keep as is
            'Ö' => 'Ö', // Keep as is
            'ö' => 'ö', // Keep as is

            // Common OCR errors that might affect invoice parsing
            'l' => 'l', // Lowercase L
            '1' => '1', // Number one
            '0' => '0', // Zero
            'O' => 'O', // Letter O
            'o' => 'o', // Letter o

            // Currency symbols
            'TL' => 'TL',
            '₺' => '₺',
            'TRY' => 'TRY',
        ];

        // Apply character fixes (only where context makes sense)
        $text = $this->applySmartReplacements($text, $ocrFixes);

        // Remove control characters but keep newlines and tabs
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $text);

        // Clean up multiple spaces around punctuation
        $text = preg_replace('/\s*([.,;:!?])\s*/u', '$1 ', $text);
        $text = preg_replace('/\s+([.,;:!?])/u', '$1', $text);

        // Remove trailing/leading whitespace from each line
        $lines = explode("\n", $text);
        $lines = array_map('trim', $lines);
        $lines = array_filter($lines, function($line) {
            return strlen($line) > 0;
        });

        return implode("\n", $lines);
    }

    /**
     * Apply smart character replacements based on context
     */
    protected function applySmartReplacements(string $text, array $replacements): string
    {
        // For now, apply all replacements
        // In the future, this could be enhanced with context-aware replacement
        return strtr($text, $replacements);
    }

    /**
     * Parse money string to float with Turkish locale support
     */
    protected function formatMoneyToFloat(string $moneyString): ?float
    {
        if (empty($moneyString)) {
            return null;
        }

        // Remove currency symbols and extra whitespace
        $cleaned = preg_replace('/[^\d.,\s-]/u', '', $moneyString);
        $cleaned = trim($cleaned);

        if (empty($cleaned)) {
            return null;
        }

        // Handle different decimal/thousands separator patterns
        $patterns = [
            // 1.743,45 (Turkish: comma as decimal, dot as thousands)
            '/^(\d{1,3}(?:\.\d{3})*),\d{1,2}$/',
            // 1,743.45 (US: dot as decimal, comma as thousands)
            '/^(\d{1,3}(?:,\d{3})*)\.\d{1,2}$/',
            // 1743,45 (just comma as decimal)
            '/^\d+,\d{1,2}$/',
            // 1743.45 (just dot as decimal)
            '/^\d+\.\d{1,2}$/',
            // Whole numbers
            '/^\d+$/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $cleaned)) {
                // Turkish format: replace dots (thousands) and convert comma to dot (decimal)
                $normalized = str_replace('.', '', $cleaned);
                $normalized = str_replace(',', '.', $normalized);

                $float = (float) $normalized;

                // Sanity check: reasonable money amounts
                if ($float >= 0 && $float <= 10000000) {
                    return round($float, 2);
                }
                break;
            }
        }

        return null;
    }

    /**
     * Extract line items from invoice text
     */
    protected function extractLineItems(string $text): array
    {
        $items = [];
        
        // Enhanced pattern to match line items with quantities and prices - More flexible
        // Handle table-like formats for Turkish invoices
        $patterns = [
            // Pattern 1: Complex Turkish invoice table format
            '/(\d+)\s+([^\n]+?)\s+(\d+(?:[\.,]\d+)?)\s+(Adet|Kutu|Paket|Şırınga|CM|Lü|Kg|Lt|Metre)\s+(\d+(?:[\.,]\d+)?)\s+(?:TL|₺|TRY)\s+%?\d*[\.,]?\d*%?\s*\d*[\.,]?\d*\s*(?:TL|₺|TRY)\s+(\d+(?:[\.,]\d+)?)\s*(?:TL|₺|TRY)/ui',

            // Pattern 2: Simpler format with description, quantity, unit, price, total
            '/([^\n]+?)\s+(\d+(?:[\.,]\d+)?)\s+(Adet|Kutu|Paket|Şırınga|CM|Lü|Kg|Lt|Metre)\s+(\d+(?:[\.,]\d+)?)\s*(?:TL|₺|TRY).*?(\d+(?:[\.,]\d+)?)\s*(?:TL|₺|TRY)/ui',

            // Pattern 3: Item with detailed pricing information
            '/([\w\s\-\(\)\d]+?)\s+(\d+(?:[\.,]\d+)?)\s+(?:adet|kutu|paket|şırınga|penset|adet|kg|lt|metre)\s+(\d+(?:[\.,]\d+)?)\s*(?:tl|₺|TRY)\s+(\d+(?:[\.,]\d+)?)\s*(?:tl|₺|TRY)/ui',

            // Pattern 4: Table format with columns
            '/([\w\s\-\(\)\d]+?)\s{2,}(\d+(?:[\.,]\d+)?)\s+(\w+)\s+(\d+(?:[\.,]\d+)?)\s*(?:tl|₺|TRY)\s+(\d+(?:[\.,]\d+)?)\s*(?:tl|₺|TRY)/ui',

            // Pattern 5: Simple format
            '/([\w\s\-\(\)\d]+?)\s+(\d+(?:[\.,]\d+)?)\s+(?:adet|kutu|paket|şırınga|penset|kg|lt|metre).*?(\d+(?:[\.,]\d+)?)\s*(?:tl|₺|TRY)/ui',

            // Pattern 6: Quantity and price only
            '/([^\n]+?)\s+(\d+(?:[\.,]\d+)?)\s*[x\*]\s*(\d+(?:[\.,]\d+)?)\s*(?:tl|₺|TRY)/ui',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match_all($pattern, $text, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    // Extract data based on pattern structure
                    if (count($match) >= 6) {
                        // Pattern 1: Complex format with line number
                        if (is_numeric($match[1]) && count($match) >= 7) {
                            $lineNumber = $match[1];
                            $description = trim($match[2]);
                            $quantity = (float) str_replace([',', '.'], ['.', '.'], $match[3]);
                            $unit = $match[4];
                            $unitPrice = (float) str_replace([',', '.'], ['.', '.'], $match[5]);
                            $totalPrice = (float) str_replace([',', '.'], ['.', '.'], $match[6]);
                        } 
                        // Pattern 2: Simpler format
                        else {
                            $description = trim($match[1]);
                            $quantity = (float) str_replace([',', '.'], ['.', '.'], $match[2]);
                            $unit = $match[3];
                            $unitPrice = (float) str_replace([',', '.'], ['.', '.'], $match[4]);
                            $totalPrice = (float) str_replace([',', '.'], ['.', '.'], $match[5]);
                        }
                    } else {
                        // Fallback for simpler patterns
                        $description = trim($match[1]);
                        $quantity = (float) str_replace([',', '.'], ['.', '.'], $match[2]);
                        $unitPrice = (float) str_replace([',', '.'], ['.', '.'], $match[3]);
                        $totalPrice = isset($match[4]) ? (float) str_replace([',', '.'], ['.', '.'], $match[4]) : ($quantity * $unitPrice);
                    }
                    
                    // Clean up the description
                    $description = trim(preg_replace('/\s+/', ' ', $description));
                    
                    // Calculate actual unit price from total if needed
                    if (isset($totalPrice) && $quantity > 0) {
                        $calculatedUnitPrice = $totalPrice / $quantity;
                        // Use the calculated price if it's more accurate
                        if (abs($calculatedUnitPrice - $unitPrice) < 0.01) {
                            $unitPrice = $calculatedUnitPrice;
                        }
                    }
                    
                    // Skip if description is too short or quantity/price is zero
                    if (strlen($description) < 3 || $quantity <= 0 || $unitPrice <= 0) {
                        continue;
                    }
                    
                    // Determine VAT rate based on common Turkish rates
                    $vatRate = 18; // Default
                    if (strpos($description, 'TUVALET') !== false || 
                        strpos($description, 'HAVLU') !== false || 
                        strpos($description, 'PEÇETE') !== false) {
                        $vatRate = 8; // These typically have 8% VAT
                    }
                    
                    $items[] = [
                        'description' => $description,
                        'quantity' => $quantity,
                        'unit' => 'adet',
                        'unit_price' => round($unitPrice, 2),
                        'vat_rate' => $vatRate
                    ];
                }
                
                // If we found items, break to avoid duplicate matches
                if (!empty($items)) {
                    break;
                }
            }
        }
        
        // If no structured items found, try to extract from common patterns
        if (empty($items)) {
            // Try to find simple item descriptions
            $itemPatterns = [
                '/(?:ürün|malzeme|açıklama)[:\s]*([^\n]+)/ui',
                '/([\w\s\-\(\)\d]+?)\s+(\d+(?:[\.,]\d+)?)\s+(?:adet|kutu|paket)/ui'
            ];
            
            foreach ($itemPatterns as $pattern) {
                if (preg_match_all($pattern, $text, $matches, PREG_SET_ORDER)) {
                    foreach ($matches as $match) {
                        $description = trim($match[1]);
                        $quantity = isset($match[2]) ? (float) $match[2] : 1;
                        
                        if (strlen($description) > 3) {
                            $items[] = [
                                'description' => $description,
                                'quantity' => $quantity,
                                'unit' => 'adet',
                                'unit_price' => 0, // Will be filled manually
                                'vat_rate' => 18
                            ];
                        }
                    }
                }
            }
        }
        
        return $items;
    }

    /**
     * Calculate confidence score based on extracted data
     */
    protected function calculateConfidenceScore(array $data): int
    {
        $score = 0;
        
        if (!empty($data['invoice_number']) && $data['invoice_number'] !== 'Fatura') $score += 20;
        if (!empty($data['invoice_date'])) $score += 15;
        if (!empty($data['grand_total']) && $data['grand_total'] > 0) $score += 15;
        if (!empty($data['line_items']) && count($data['line_items']) > 0) $score += 25;
        if (!empty($data['supplier_info']) && count($data['supplier_info']) > 0) $score += 15;
        if (!empty($data['supplier_match']) && count($data['supplier_match']) > 0) {
            $score += min(20, $data['supplier_match'][0]['confidence'] / 5);
        }
        
        // Bonus points for complete data
        if (!empty($data['invoice_number']) && !empty($data['invoice_date']) && !empty($data['grand_total']) && $data['grand_total'] > 0) {
            $score += 10;
        }
        
        // Bonus for multiple line items
        if (!empty($data['line_items']) && count($data['line_items']) >= 3) {
            $score += 10;
        }
        
        return min(100, $score);
    }

    /**
     * Extract supplier information from invoice text
     */
    protected function extractSupplierInfo(string $text): array
    {
        $supplier = [];
        
        // Extract supplier name - look for company names before "VKN" or "Vergi No" - More flexible
        $namePatterns = [
            '/^(.*?)\s*V(KN|ergi\s*No|ergi\s*numarası|TCKN)[:\s]*[\d\-\/]+/mi',
            '/(?:tedarikçi|firma|satıcı|company|şirket|kurum)[:\s]*([^\n]+)/ui',
            '/^(.*?)(?:\n|$)/m', // First line
            '/(?:ltd|şti|co|inc|AŞ|A\.Ş\.|LTD\.ŞTİ\.|TİC\.|SAN\.|LİMİTED|ANONİM)[\s\S]*?(?:\n|$)/i',
            '/([A-ZÇĞİÖŞÜ\s\-\.]+(?:LTD|ŞTİ|AŞ|CO|INC|TİC|SAN|LİMİTED|ANONİM)[\.\s]*)/ui'
        ];
        
        foreach ($namePatterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $name = trim($matches[1]);
                // Clean up the name
                $name = preg_replace('/\s+/', ' ', $name);
                $name = trim($name, " \t\n\r\0\x0B:\/\/");
                
                if (strlen($name) > 5 && !preg_match('/^(fatura|invoice|belge|tarih|sayın)/i', $name)) {
                    $supplier['name'] = $name;
                    break;
                }
            }
        }
        
        // Extract tax number/VKN - More flexible
        if (preg_match('/(?:vergi\s*no|vergi\s*numarası|vkn|tax\s*id|tax\s*number)[:\s]*([\d\-\/]+)/i', $text, $matches)) {
            $supplier['tax_number'] = trim($matches[1]);
        } elseif (preg_match('/VKN[:]\s*([\d\-\/]+)/i', $text, $matches)) {
            $supplier['tax_number'] = trim($matches[1]);
        }

        // Extract TCKN (personal tax number) if VKN not found
        if (empty($supplier['tax_number']) && preg_match('/(?:TCKN|TC\s*kimlik\s*no)[:\s]*([\d]+)/i', $text, $matches)) {
            $supplier['tax_number'] = trim($matches[1]);
        }
        
        // Extract phone - More flexible
        if (preg_match('/(?:tel|telefon|phone|cep|mobile|fax)[:\s]*([\+\d\s\-\(\)\.]+)/', $text, $matches)) {
            $supplier['phone'] = trim($matches[1]);
        }

        // Extract email - More flexible
        if (preg_match('/[\w\.\-]+@[\w\.\-]+\.[a-z]{2,}/i', $text, $matches)) {
            $supplier['email'] = trim($matches[0]);
        }
        
        // Extract address - look for address after supplier name or "Adres" - More flexible
        if (preg_match('/(?:adres|address|adresi)[:\s]*([^\n]+)/i', $text, $matches)) {
            $supplier['address'] = trim($matches[1]);
        } elseif (!empty($supplier['name'])) {
            // Try to find address near the supplier name
            $namePos = strpos($text, $supplier['name']);
            if ($namePos !== false) {
                $addressText = substr($text, $namePos + strlen($supplier['name']), 300);
                if (preg_match('/([\w\s\-\/,\.]+(?:mah\.|sok\.|cad\.|bulvar|bulv\.|caddesi|sokağı|no:|No:|numara)[\w\s\-\/,\.]+)/i', $addressText, $matches)) {
                    $supplier['address'] = trim($matches[1]);
                }
            }
        }
        
        return $supplier;
    }

    /**
     * Validate supplier information against database
     */
    protected function validateSupplier(string $text): array
    {
        $suppliers = StockSupplier::all(['id', 'name', 'tax_number', 'phone', 'email']);
        $matches = [];
        
        foreach ($suppliers as $supplier) {
            $confidence = 0;
            
            // Check name match
            if (stripos($text, $supplier->name) !== false) {
                $confidence += 40;
            }
            
            // Check tax number match
            if ($supplier->tax_number && stripos($text, $supplier->tax_number) !== false) {
                $confidence += 30;
            }
            
            // Check phone match
            if ($supplier->phone && stripos($text, $supplier->phone) !== false) {
                $confidence += 15;
            }
            
            // Check email match
            if ($supplier->email && stripos($text, $supplier->email) !== false) {
                $confidence += 15;
            }
            
            if ($confidence > 0) {
                $matches[] = [
                    'supplier_id' => $supplier->id,
                    'supplier_name' => $supplier->name,
                    'confidence' => $confidence
                ];
            }
        }
        
        // Sort by confidence
        usort($matches, function($a, $b) {
            return $b['confidence'] <=> $a['confidence'];
        });
        
        return $matches;
    }

    /**
     * Batch process multiple invoices with sequential execution
     */
    public function batchProcess(array $files): array
    {
        $results = [];
        $totalStartTime = microtime(true);

        Log::info('Starting batch OCR processing', [
            'file_count' => count($files),
            'max_pages' => $this->config['max_pages'],
            'timeout' => $this->config['timeout']
        ]);

        foreach ($files as $index => $file) {
            $fileStartTime = microtime(true);

            try {
                // Validate file before processing
                $this->validateFile($file);

                $parsedData = $this->parseDocument($file);
                $processingTime = (int) ((microtime(true) - $fileStartTime) * 1000);

                $results[] = [
                    'file_index' => $index,
                    'original_name' => $file->getClientOriginalName(),
                    'parsed_data' => $parsedData,
                    'processing_time_ms' => $processingTime,
                    'success' => true,
                    'confidence' => $parsedData['confidence_score'] ?? 0,
                    'needs_review' => $parsedData['needs_review'] ?? false
                ];

                Log::debug('Batch file processed successfully', [
                    'index' => $index,
                    'file' => $file->getClientOriginalName(),
                    'confidence' => $parsedData['confidence_score'] ?? 0,
                    'processing_time_ms' => $processingTime
                ]);

            } catch (\Exception $e) {
                $processingTime = (int) ((microtime(true) - $fileStartTime) * 1000);

                Log::error('Batch OCR processing failed for file', [
                    'index' => $index,
                    'file' => $file->getClientOriginalName(),
                    'error' => $e->getMessage(),
                    'processing_time_ms' => $processingTime
                ]);

                $results[] = [
                    'file_index' => $index,
                    'original_name' => $file->getClientOriginalName(),
                    'parsed_data' => $this->getEmptyResult(),
                    'processing_time_ms' => $processingTime,
                    'success' => false,
                    'error' => $e->getMessage(),
                    'error_code' => $this->getErrorCode($e)
                ];
            }
        }

        $totalProcessingTime = (int) ((microtime(true) - $totalStartTime) * 1000);

        // Sort by confidence score (highest first), then by success status
        usort($results, function($a, $b) {
            // Successful results first
            if ($a['success'] && !$b['success']) return -1;
            if (!$a['success'] && $b['success']) return 1;

            // Then by confidence score
            return ($b['confidence'] ?? 0) <=> ($a['confidence'] ?? 0);
        });

        Log::info('Batch OCR processing completed', [
            'total_files' => count($files),
            'successful_files' => count(array_filter($results, fn($r) => $r['success'])),
            'total_processing_time_ms' => $totalProcessingTime
        ]);

        return $results;
    }

    /**
     * Get error code from exception
     */
    protected function getErrorCode(\Exception $e): string
    {
        if ($e instanceof \InvalidArgumentException) {
            return 'INVALID_FILE';
        }

        $message = strtolower($e->getMessage());
        if (strpos($message, 'timeout') !== false) {
            return 'OCR_TIMEOUT';
        }

        if (strpos($message, 'tesseract') !== false) {
            return 'OCR_ENGINE_ERROR';
        }

        if (strpos($message, 'memory') !== false || strpos($message, 'allocation') !== false) {
            return 'MEMORY_ERROR';
        }

        return 'UNKNOWN_ERROR';
    }

    /**
     * Suggest item matches based on description
     */
    public function suggestStockItems(string $description): array
    {
        // Use fuzzy matching to find similar stock items
        $items = \App\Models\Stock\StockItem::where('is_active', true)
                                          ->orderBy('name')
                                          ->get(['id', 'name', 'sku', 'category_id']);
        
        $suggestions = [];
        $description = strtolower(trim($description));
        
        foreach ($items as $item) {
            $itemName = strtolower($item->name);
            $similarity = 0;
            
            // Calculate string similarity
            similar_text($description, $itemName, $similarity);
            
            // Boost score if description contains item name or vice versa
            if (stripos($description, $itemName) !== false || stripos($itemName, $description) !== false) {
                $similarity += 25;
            }
            
            // Check SKU match
            if ($item->sku && stripos($description, $item->sku) !== false) {
                $similarity += 35;
            }
            
            // Category-based boost
            if ($item->category_id) {
                $category = \App\Models\Stock\StockCategory::find($item->category_id);
                if ($category && stripos($description, strtolower($category->name)) !== false) {
                    $similarity += 15;
                }
            }
            
            if ($similarity > 35) {
                $suggestions[] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'sku' => $item->sku,
                    'similarity' => $similarity
                ];
            }
        }
        
        // Sort by similarity
        usort($suggestions, function($a, $b) {
            return $b['similarity'] <=> $a['similarity'];
        });
        
        return array_slice($suggestions, 0, 8); // Return top 8 matches
    }

    /**
     * Get empty result structure
     */
    protected function getEmptyResult(): array
    {
        return [
            'invoice_number' => null,
            'invoice_date' => null,
            'due_date' => null,
            'grand_total' => null,
            'subtotal' => null,
            'vat_total' => null,
            'supplier_id' => null,
            'supplier_info' => [],
            'supplier_match' => [],
            'line_items' => [],
            'extracted_text' => '',
            'confidence_score' => 0,
            'processing_time_ms' => 0,
            'needs_review' => false,
            'error' => null,
        ];
    }
}