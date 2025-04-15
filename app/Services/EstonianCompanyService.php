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
            // Get company name from the database
            $company = \App\Models\Company::where('tax_number', $registryCode)->first();
            if (!$company) {
                Log::warning("Company not found with registry code: {$registryCode}");
                return null;
            }

            Log::info("Searching for company", [
                'name' => $company->name,
                'registry_code' => $registryCode
            ]);

            // Get company information from autocomplete endpoint
            $url = "{$this->baseUrl}/est/api/autocomplete";
            $params = ['q' => $company->name];
            
            Log::info("Making API request", [
                'url' => $url,
                'params' => $params
            ]);

            $response = Http::get($url, $params);
            
            if (!$response->successful()) {
                Log::warning("Failed to get company information: {$response->status()}");
                return null;
            }

            $data = $response->json();
            $companyData = null;

            if (isset($data['data']) && is_array($data['data'])) {
                foreach ($data['data'] as $result) {
                    if ((string)$result['reg_code'] === (string)$registryCode) {
                        $companyData = $result;
                        break;
                    }
                }
            }

            if (!$companyData) {
                Log::warning("No company found with name: {$company->name} and registry code: {$registryCode}");
                return null;
            }
            
            Log::info("Company data", [
                'data' => $companyData
            ]);

            // Try to extract foundation date from available fields
            $foundationDate = null;
            if (isset($companyData['reg_date'])) {
                try {
                    $foundationDate = Carbon::parse($companyData['reg_date'])->format('Y-m-d');
                } catch (\Exception $e) {
                    Log::warning("Failed to parse registration date: {$e->getMessage()}");
                }
            }
            
            $result = [
                'name' => $companyData['name'] ?? $company->name,
                'registry_code' => $companyData['reg_code'] ?? $registryCode,
                'address' => $companyData['legal_address'] ?? null,
                'foundation_date' => $foundationDate,
                'status' => $companyData['status'] ?? null,
                'vat_number' => $companyData['vat_number'] ?? null
            ];
            
            Log::info("Extracted company details", [
                'result' => $result
            ]);
            
            return $result;
        } catch (\Exception $e) {
            Log::error("Error fetching Estonian company details: {$e->getMessage()}", [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
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
        // Log the data to see what's available
        Log::debug('Checking foundation date fields in company data', [
            'available_fields' => array_keys($data)
        ]);
        
        // Try to get foundation date from the API response
        if (isset($data['registration_date'])) {
            Log::debug("Found foundation date in 'registration_date' field", ['value' => $data['registration_date']]);
            return Carbon::parse($data['registration_date'])->format('Y-m-d');
        }
        
        // Alternative field names that might contain the foundation date
        $possibleFields = [
            'registered_at', 
            'foundation_date',
            'established_on',
            'asutatud', // Estonian for "founded"
            'registreeritud', // Estonian for "registered"
            'reg_date',
            'establishment_date',
            'date_of_establishment',
            'incorporation_date',
            'date_of_incorporation'
        ];
        
        foreach ($possibleFields as $field) {
            if (isset($data[$field])) {
                Log::debug("Found foundation date in '{$field}' field", ['value' => $data[$field]]);
                return Carbon::parse($data[$field])->format('Y-m-d');
            }
        }
        
        Log::warning("No foundation date found in company data");
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
    
    /**
     * Extract address from company data
     */
    private function extractAddress($data)
    {
        if (isset($data['address'])) {
            return $data['address'];
        }
        
        // Try to combine address parts if available
        $addressParts = [];
        
        $addressFields = [
            'street', 'house', 'apartment',
            'city', 'county', 'country',
            'postal_code', 'address_line1',
            'address_line2'
        ];
        
        foreach ($addressFields as $field) {
            if (isset($data[$field]) && !empty($data[$field])) {
                $addressParts[] = $data[$field];
            }
        }
        
        return !empty($addressParts) ? implode(', ', $addressParts) : null;
    }
} 