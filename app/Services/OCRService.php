<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use App\Models\Stock\StockSupplier;

class OCRService
{
    protected array $config;

    public function __construct()
    {
        $this->config = config('services.ocr', []);
    }

    /**
     * Parse invoice PDF/Image and extract data
     */
    public function parseDocument(UploadedFile $file): array
    {
        try {
            // Read the actual file content for processing
            $extractedText = file_get_contents($file->getPathname());
            
            // Debug: Show first part of extracted text
            \Illuminate\Support\Facades\Log::debug('OCR Extracted Text Length: ' . strlen($extractedText));
            \Illuminate\Support\Facades\Log::debug('OCR First 200 chars: ' . substr($extractedText, 0, 200));
            
            $parsedData = $this->parseInvoiceText($extractedText);
            $parsedData['extracted_text'] = $extractedText;
            $parsedData['file_type'] = $file->getClientMimeType();
            $parsedData['confidence_score'] = $this->calculateConfidenceScore($parsedData);
            
            return $parsedData;
        } catch (\Exception $e) {
            Log::error('OCR parsing failed', [
                'file' => $file->getClientOriginalName(),
                'error' => $e->getMessage()
            ]);
            
            return $this->getEmptyResult();
        }
    }

    /**
     * Extract text from image using OCR
     */
    protected function extractTextFromImage(UploadedFile $file): string
    {
        // In a real implementation, you would integrate with:
        // - Tesseract OCR for local processing
        // - Google Cloud Vision API
        // - AWS Textract
        // - Azure Cognitive Services
        
        // For demonstration, we'll simulate OCR results based on common Turkish invoice patterns
        $filename = $file->getClientOriginalName();
        
        // Simulate different types of invoices based on filename or content
        $simulatedTexts = [
            "HUKUK DİŞ HEKİMLİĞİ TİCARET LTD. ŞTİ.\nVergi No: 1234567890\nAdres: Atatürk Cad. No:123 İstanbul\nTel: 0212 123 45 67\n\nFATURA\nFatura No: 2024-DH-001\nTarih: 15/01/2024\nVade Tarihi: 30/01/2024\n\n--- ÜRÜN LİSTESİ ---\nDental Eldiven Latex M Beden    10 Kutu    25.50 TL    255.00 TL\nAnestezi İğnesi 27G Kısa        5 Paket    45.00 TL    225.00 TL\nKompozit Dolgu A2 Renk          2 Adet     180.00 TL   360.00 TL\nDental Ayna No:5                3 Adet     35.00 TL    105.00 TL\n\nAra Toplam: 945.00 TL\nKDV %18: 170.10 TL\nGenel Toplam: 1,115.10 TL\n\nÖdeme Şekli: 30 Gün Vadeli\nTeşekkürler.",
            
            "MEDICAL DENTAL SUPPLY CO.\nTax ID: 9876543210\nİstanbul Medipol Üniversitesi Karşısı\nPhone: +90 532 123 4567\nE-mail: info@medicaldental.com\n\nSATIŞ FATURASI\nBelge No: MDS-2024-456\nDüzenleme Tarihi: 22/01/2024\nVade: 45 Gün\n\nMÜŞTERİ: Örnek Diş Kliniği\n\n═══════════════════════════════════════\nÜRÜN ADI                    MİKTAR  BİRİM FİYAT  TOPLAM\n═══════════════════════════════════════\nDental Ünit Başlığı Kılıfı    50     2.75 TL     137.50\nOrtodontik Braket Set          1     850.00 TL   850.00\nDişçi Eldiveni Nitrile L       5     28.00 TL    140.00\nKök Kanal Eğesi H-File        10     15.50 TL    155.00\nDental Siman Geçici           3     65.00 TL    195.00\n═══════════════════════════════════════\nNet Tutar:                                      1,477.50 TL\nKDV Tutarı (%18):                                265.95 TL\nFATURA TUTARI:                                 1,743.45 TL\n═══════════════════════════════════════",
            
            "ANKARA DENTAL MALZEME SANAYİ\nVKN: 5555666677\nOstim Organize Sanayi Bölgesi\nAnkara / TÜRKİYE\nTelefon: 0312 555 66 77\n\nFATURA BELGESI\nSeri: ADM  No: 2024/789\nTarih: 28/01/2024\n\nSayın: Güzellik Diş Kliniği\nAdres: Kızılay Mah. Ankara\n\n┌─────────────────────────────────────────┐\n│  ÜRÜN DETAYLARI                         │\n├─────────────────────────────────────────┤\n│ Dental Composite Light Cure   2 Şırınga │\n│ Birim Fiyat: 95.00 TL                   │\n│ Toplam: 190.00 TL                       │\n├─────────────────────────────────────────┤\n│ İmplant Vida Titanyum 4.2mm   4 Adet    │\n│ Birim Fiyat: 450.00 TL                  │\n│ Toplam: 1,800.00 TL                     │\n├─────────────────────────────────────────┤\n│ Cerrahi Penset Anatomik       1 Adet    │\n│ Birim Fiyat: 125.00 TL                  │\n│ Toplam: 125.00 TL                       │\n└─────────────────────────────────────────┘\n\nAra Toplam: 2,115.00 TL\nKDV (%18): 380.70 TL\nFATURA TOPLAMI: 2,495.70 TL\n\nÖdeme Koşulu: 60 Gün Vade"
        ];
        
        // Return a random realistic invoice
        return $simulatedTexts[array_rand($simulatedTexts)];
    }

    /**
     * Extract text from PDF using available OCR service
     */
    protected function extractTextFromPdf(UploadedFile $file): string
    {
        // In a real implementation, this would integrate with actual OCR services
        // For now, we'll generate more realistic sample invoice data
        
        $sampleInvoices = [
            "HUKUK DİŞ HEKİMLİĞİ TİCARET LTD. ŞTİ.\nVergi No: 1234567890\nAdres: Atatürk Cad. No:123 İstanbul\nTel: 0212 123 45 67\n\nFATURA\nFatura No: 2024-DH-001\nTarih: 15/01/2024\nVade Tarihi: 30/01/2024\n\n--- ÜRÜN LİSTESİ ---\nDental Eldiven Latex M Beden    10 Kutu    25.50 TL    255.00 TL\nAnestezi İğnesi 27G Kısa        5 Paket    45.00 TL    225.00 TL\nKompozit Dolgu A2 Renk          2 Adet     180.00 TL   360.00 TL\nDental Ayna No:5                3 Adet     35.00 TL    105.00 TL\n\nAra Toplam: 945.00 TL\nKDV %18: 170.10 TL\nGenel Toplam: 1,115.10 TL\n\nÖdeme Şekli: 30 Gün Vadeli\nTeşekkürler.",
            
            "MEDICAL DENTAL SUPPLY CO.\nTax ID: 9876543210\nİstanbul Medipol Üniversitesi Karşısı\nPhone: +90 532 123 4567\nE-mail: info@medicaldental.com\n\nSATIŞ FATURASI\nBelge No: MDS-2024-456\nDüzenleme Tarihi: 22/01/2024\nVade: 45 Gün\n\nMÜŞTERİ: Örnek Diş Kliniği\n\n═══════════════════════════════════════\nÜRÜN ADI                    MİKTAR  BİRİM FİYAT  TOPLAM\n═══════════════════════════════════════\nDental Ünit Başlığı Kılıfı    50     2.75 TL     137.50\nOrtodontik Braket Set          1     850.00 TL   850.00\nDişçi Eldiveni Nitrile L       5     28.00 TL    140.00\nKök Kanal Eğesi H-File        10     15.50 TL    155.00\nDental Siman Geçici           3     65.00 TL    195.00\n═══════════════════════════════════════\nNet Tutar:                                      1,477.50 TL\nKDV Tutarı (%18):                                265.95 TL\nFATURA TUTARI:                                 1,743.45 TL\n═══════════════════════════════════════",
            
            "ANKARA DENTAL MALZEME SANAYİ\nVKN: 5555666677\nOstim Organize Sanayi Bölgesi\nAnkara / TÜRKİYE\nTelefon: 0312 555 66 77\n\nFATURA BELGESI\nSeri: ADM  No: 2024/789\nTarih: 28/01/2024\n\nSayın: Güzellik Diş Kliniği\nAdres: Kızılay Mah. Ankara\n\n┌─────────────────────────────────────────┐\n│  ÜRÜN DETAYLARI                         │\n├─────────────────────────────────────────┤\n│ Dental Composite Light Cure   2 Şırınga │\n│ Birim Fiyat: 95.00 TL                   │\n│ Toplam: 190.00 TL                       │\n├─────────────────────────────────────────┤\n│ İmplant Vida Titanyum 4.2mm   4 Adet    │\n│ Birim Fiyat: 450.00 TL                  │\n│ Toplam: 1,800.00 TL                     │\n├─────────────────────────────────────────┤\n│ Cerrahi Penset Anatomik       1 Adet    │\n│ Birim Fiyat: 125.00 TL                  │\n│ Toplam: 125.00 TL                       │\n└─────────────────────────────────────────┘\n\nAra Toplam: 2,115.00 TL\nKDV (%18): 380.70 TL\nFATURA TOPLAMI: 2,495.70 TL\n\nÖdeme Koşulu: 60 Gün Vade"
        ];
        
        // Return a random realistic invoice
        return $sampleInvoices[array_rand($sampleInvoices)];
    }

    /**
     * Parse extracted text to find invoice information
     */
    protected function parseInvoiceText(string $text): array
    {
        \Illuminate\Support\Facades\Log::debug('Parsing invoice text, length: ' . strlen($text));
        \Illuminate\Support\Facades\Log::debug('First 200 chars: ' . substr($text, 0, 200));
        
        $data = $this->getEmptyResult();
        
        // Enhanced invoice number patterns (Turkish)
        $invoicePatterns = [
            '/(?:fatura\s*no|e-ar\u015fiv\s*fatura)[:\s]*([A-Z0-9\-\/]+)/ui',
            '/(?:fatura|invoice)\s*(?:no|number|numarası)?[:\s]*([A-Z0-9\-\/]+)/ui',
            '/(?:belge|seri)\s*(?:no)?[:\s]*([A-Z0-9\-\/]+)/ui',
            '/(?:INV|FB|SF|ADM|MDS|DH|GIB)[\-\s\/]*([0-9\-\/]+)/i',
            '/no[:\s]*([0-9]{4}[\-\/][0-9]+)/ui'
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
        
        // Enhanced date patterns (Turkish format)
        $datePatterns = [
            '/(?:fatura\s*tarihi|tarih|date|düzenlenme\s*tarihi)[:\s]*(\d{1,2}[\.\/\-]\d{1,2}[\.\/\-]\d{2,4})/ui',
            '/(\d{1,2}[\.\/\-]\d{1,2}[\.\/\-]\d{4})/',
            '/(?:^|\n)(\d{1,2}[\.\/\-]\d{1,2}[\.\/\-]\d{4})(?:\n|$)/m',
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
        
        // Enhanced amount patterns (Turkish lira) - Look for the final total
        $amountPatterns = [
            '/(?:vergiler\s*dahil\s*toplam\s*tutar|ödenecek\s*tutar)[:\s]*([\d,\.]+)\s*(?:tl|₺)/ui',
            '/(?:fatura\s*tutarı|genel\s*toplam|toplam)[:\s]*([\d,\.]+)\s*(?:tl|₺)/ui',
            '/(?:total|toplam)[:\s]*([\d,\.]+)\s*(?:tl|₺)/ui',
            '/([\d,\.]+)\s*(?:₺|tl)\s*$/ui',
            '/(?:₺|tl)\s*([\d,\.]+)/ui'
        ];
        
        foreach ($amountPatterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $amount = $matches[1];
                // Handle both Turkish (comma) and international (dot) decimal formats
                // First, replace all dots with temporary marker
                $amount = str_replace('.', '#TEMP#', $amount);
                // Replace commas with dots (decimal separator)
                $amount = str_replace(',', '.', $amount);
                // Replace temporary markers with empty (thousands separator)
                $amount = str_replace('#TEMP#', '', $amount);
                
                $numericAmount = (float) $amount;
                if ($numericAmount > 0) {
                    $data['grand_total'] = $numericAmount;
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
     * Extract line items from invoice text
     */
    protected function extractLineItems(string $text): array
    {
        $items = [];
        
        // Enhanced pattern to match line items with quantities and prices
        // Handle table-like formats for Turkish invoices
        $patterns = [
            // Pattern 1: Complex Turkish invoice table format
            '/(\d+)\s+([^\n]+?)\s+(\d+(?:[\.,]\d+)?)\s+(Adet|Kutu|Paket|Şırınga|CM|Lü)\s+(\d+(?:[\.,]\d+)?)\s+TL\s+%?\d*[\.,]?\d*%?\s*\d*[\.,]?\d*\s*TL\s+(\d+(?:[\.,]\d+)?)\s*TL/ui',
            
            // Pattern 2: Simpler format with description, quantity, unit, price, total
            '/([^\n]+?)\s+(\d+(?:[\.,]\d+)?)\s+(Adet|Kutu|Paket|Şırınga|CM|Lü)\s+(\d+(?:[\.,]\d+)?)\s+TL.*?(\d+(?:[\.,]\d+)?)\s*TL/ui',
            
            // Pattern 3: Item with detailed pricing information
            '/([\w\s\-\(\)\d]+?)\s+(\d+(?:[\.,]\d+)?)\s+(?:adet|kutu|paket|şırınga|penset)\s+(\d+(?:[\.,]\d+)?)\s*(?:tl|₺)\s+(\d+(?:[\.,]\d+)?)\s*(?:tl|₺)/ui',
            
            // Pattern 4: Table format with columns
            '/([\w\s\-\(\)\d]+?)\s{2,}(\d+(?:[\.,]\d+)?)\s+(\w+)\s+(\d+(?:[\.,]\d+)?)\s+(?:tl|₺)\s+(\d+(?:[\.,]\d+)?)\s+(?:tl|₺)/ui',
            
            // Pattern 5: Simple format
            '/([\w\s\-\(\)\d]+?)\s+(\d+(?:[\.,]\d+)?)\s+(?:adet|kutu|paket).*?(\d+(?:[\.,]\d+)?)\s*(?:tl|₺)/ui'
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
        
        // Extract supplier name - look for company names before "VKN" or "Vergi No"
        $namePatterns = [
            '/^(.*?)\s*V(KN|ergi\s*No)[:\s]*[\d\-\/]+/mi',
            '/(?:tedarikçi|firma|satıcı|company)[:\s]*([^\n]+)/ui',
            '/^(.*?)(?:\n|$)/m', // First line
            '/(?:ltd|şti|co|inc|AŞ|A\.Ş\.|LTD\.ŞTİ\.)[^\n]*/i'
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
        
        // Extract tax number/VKN
        if (preg_match('/(?:vergi\s*no|vkn|tax\s*id)[:\s]*([\d\-\/]+)/i', $text, $matches)) {
            $supplier['tax_number'] = trim($matches[1]);
        } elseif (preg_match('/VKN[:]\s*([\d\-\/]+)/i', $text, $matches)) {
            $supplier['tax_number'] = trim($matches[1]);
        }
        
        // Extract TCKN (personal tax number) if VKN not found
        if (empty($supplier['tax_number']) && preg_match('/TCKN[:]\s*([\d]+)/i', $text, $matches)) {
            $supplier['tax_number'] = trim($matches[1]);
        }
        
        // Extract phone
        if (preg_match('/(?:tel|telefon|phone)[:\s]*([\+\d\s\-\(\)]+)/', $text, $matches)) {
            $supplier['phone'] = trim($matches[1]);
        }
        
        // Extract email
        if (preg_match('/[\w\.\-]+@[\w\.\-]+\.[a-z]{2,}/i', $text, $matches)) {
            $supplier['email'] = trim($matches[0]);
        }
        
        // Extract address - look for address after supplier name or "Adres"
        if (preg_match('/(?:adres|address)[:\s]*([^\n]+)/i', $text, $matches)) {
            $supplier['address'] = trim($matches[1]);
        } elseif (!empty($supplier['name'])) {
            // Try to find address near the supplier name
            $namePos = strpos($text, $supplier['name']);
            if ($namePos !== false) {
                $addressText = substr($text, $namePos + strlen($supplier['name']), 200);
                if (preg_match('/([\w\s\-\/,\.]+(?:mah\.|sok\.|cad\.|no:|No:)[\w\s\-\/,\.]+)/i', $addressText, $matches)) {
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
     * Batch process multiple invoices
     */
    public function batchProcess(array $files): array
    {
        $results = [];
        
        foreach ($files as $index => $file) {
            try {
                $parsedData = $this->parseDocument($file);
                
                $results[] = [
                    'file_index' => $index,
                    'original_name' => $file->getClientOriginalName(),
                    'parsed_data' => $parsedData,
                    'processing_time' => microtime(true),
                    'success' => true,
                    'confidence' => $parsedData['confidence_score'] ?? 0
                ];
            } catch (\Exception $e) {
                Log::error('Batch OCR processing failed', [
                    'file' => $file->getClientOriginalName(),
                    'error' => $e->getMessage()
                ]);
                
                $results[] = [
                    'file_index' => $index,
                    'original_name' => $file->getClientOriginalName(),
                    'parsed_data' => $this->getEmptyResult(),
                    'processing_time' => microtime(true),
                    'success' => false,
                    'error' => $e->getMessage()
                ];
            }
        }
        
        // Sort by confidence score (highest first)
        usort($results, function($a, $b) {
            return ($b['confidence'] ?? 0) <=> ($a['confidence'] ?? 0);
        });
        
        return $results;
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
        ];
    }
}