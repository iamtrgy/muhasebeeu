<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EstonianCompanyService
{
    /**
     * Base URL for the Estonian Business Registry API
     */
    protected $baseUrl = 'https://ariregister.rik.ee';
    
    /**
     * Get detailed information about an Estonian company by registry code
     * 
     * @param string $registryCode
     * @return array|null
     */
    public function getCompanyDetails(string $registryCode)
    {
        try {
            // Make API request to get company details
            $response = Http::get("{$this->baseUrl}/est/api/card/company/{$registryCode}");
            
            // If the request was successful
            if ($response->successful()) {
                $data = $response->json();
                
                // Extract the needed information
                return [
                    'name' => $data['name'] ?? null,
                    'registry_code' => $data['code'] ?? $registryCode,
                    'address' => $data['address'] ?? null,
                    'foundation_date' => $this->extractFoundationDate($data),
                    'status' => $data['status'] ?? null,
                    'vat_number' => $this->formatVatNumber($data, $registryCode),
                ];
            }
            
            Log::warning("Failed to get Estonian company details: {$response->status()}");
            return null;
        } catch (\Exception $e) {
            Log::error("Error fetching Estonian company details: {$e->getMessage()}");
            return null;
        }
    }
    
    /**
     * Extract the foundation date from the company data
     * 
     * @param array $data
     * @return string|null
     */
    private function extractFoundationDate(array $data)
    {
        // Try to get foundation date from the API response
        if (isset($data['foundation_date'])) {
            return Carbon::parse($data['foundation_date'])->format('Y-m-d');
        }
        
        // Alternative field names that might contain the foundation date
        $possibleFields = ['registered_at', 'registration_date', 'established_on'];
        
        foreach ($possibleFields as $field) {
            if (isset($data[$field])) {
                return Carbon::parse($data[$field])->format('Y-m-d');
            }
        }
        
        return null;
    }
    
    /**
     * Format the VAT number based on company data
     * 
     * @param array $data
     * @param string $registryCode
     * @return string|null
     */
    private function formatVatNumber(array $data, string $registryCode)
    {
        // Log the data to see what's available
        Log::debug('Checking VAT number fields in company data', [
            'registry_code' => $registryCode,
            'available_fields' => array_keys($data)
        ]);
        
        // Check if VAT number is directly provided in API response
        if (isset($data['vat_number'])) {
            Log::debug("Found VAT number in 'vat_number' field", ['value' => $data['vat_number']]);
            return $data['vat_number'];
        }
        
        // Check other possible VAT number fields in the response
        $possibleVatFields = ['vat', 'vat_id', 'vat_code', 'tax_id', 'KMKR', 'vatNumber', 'vatId'];
        
        foreach ($possibleVatFields as $field) {
            if (isset($data[$field])) {
                Log::debug("Found VAT number in '{$field}' field", ['value' => $data[$field]]);
                return $data[$field];
            }
        }
        
        // Check if there's a field containing "vat" or "VAT" in its name
        foreach ($data as $key => $value) {
            if (is_string($value) && (stripos($key, 'vat') !== false || stripos($key, 'KMKR') !== false)) {
                Log::debug("Found potential VAT number in field '{$key}'", ['value' => $value]);
                return $value;
            }
        }
        
        // If no VAT number is found, return null
        Log::debug("No VAT number found for company with registry code {$registryCode}");
        return null;
    }
} 