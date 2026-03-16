<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DrugDatabaseService
{
    /**
     * Search drug information from multiple sources
     */
    public function search($query)
    {
        $results = [];

        // 1. Search OpenFDA (International - Good for clinical info)
        $openFdaResults = $this->searchOpenFda($query);
        foreach ($openFdaResults as $item) {
            $results[] = $item;
        }

        // 2. Search Thai FDA / Data.go.th (Local - Good for registration/Thai names)
        // Note: In real production, you need an API Key from data.go.th
        // We will implement the structure here.
        $thaiFdaResults = $this->searchThaiFda($query);
        foreach ($thaiFdaResults as $item) {
            $results[] = $item;
        }

        return $results;
    }

    /**
     * Search OpenFDA API
     */
    private function searchOpenFda($query)
    {
        try {
            // searching both brand_name and generic_name
            $response = Http::get("https://api.fda.gov/drug/label.json", [
                'search' => "openfda.brand_name:\"$query\" OR openfda.generic_name:\"$query\"",
                'limit' => 3
            ]);

            if (!$response->successful()) {
                return [];
            }

            $data = $response->json();
            $items = [];

            if (isset($data['results'])) {
                foreach ($data['results'] as $res) {
                    $openfda = $res['openfda'] ?? [];

                    $items[] = [
                        'source' => 'OpenFDA',
                        'sku' => strtoupper(substr(($openfda['brand_name'][0] ?? 'FDA'), 0, 3)) . '-' . rand(100, 999),
                        'barcode' => $openfda['upc'][0] ?? '',
                        'name' => $openfda['brand_name'][0] ?? 'Unknown',
                        'name_th' => '', // FDA only provides English
                        'generic_name' => $openfda['generic_name'][0] ?? '',
                        'manufacturer' => $openfda['manufacturer_name'][0] ?? '',
                        'drug_class' => 'ยาอันตราย', // Default for clinical drugs
                        'fda_registration_no' => $openfda['application_number'][0] ?? '',
                        'precautions' => $res['warnings'][0] ?? ($res['precautions'][0] ?? 'See label for warnings'),
                        'side_effects' => $res['adverse_reactions'][0] ?? 'See label for side effects',
                        'default_instructions' => $res['dosage_and_administration'][0] ?? 'As directed by physician',
                    ];
                }
            }

            return $items;
        } catch (\Exception $e) {
            Log::error("OpenFDA search failed: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Search Thai FDA API (Simulated structure for Data.go.th)
     */
    private function searchThaiFda($query)
    {
        // Thai FDA Resource ID on Data.go.th (Example: รายการทะเบียนตำรับยา)
        $resourceId = config('services.thai_fda.resource_id', '07b1d6b0-1365-4bd4-9a84-7a0f6705db85');
        $apiKey = config('services.data_go_th.api_key');

        if (!$apiKey) {
            // If no API key, return a few specific items as "Free Preview" or mock
            return $this->getMockThaiData($query);
        }

        try {
            $response = Http::withHeaders([
                'api-key' => $apiKey
            ])->get("https://data.go.th/api/3/action/datastore_search", [
                'resource_id' => $resourceId,
                'q' => $query,
                'limit' => 5
            ]);

            if (!$response->successful()) {
                return $this->getMockThaiData($query);
            }

            $data = $response->json();
            $items = [];

            if (isset($data['result']['records'])) {
                foreach ($data['result']['records'] as $record) {
                    // Mapping depends on the specific dataset columns
                    $items[] = [
                        'source' => 'Thai FDA',
                        'sku' => 'TH-' . ($record['new_regis_no'] ?? rand(1000, 9999)),
                        'barcode' => '',
                        'name' => $record['trade_name_eng'] ?? $record['trade_name_th'] ?? 'Unknown',
                        'name_th' => $record['trade_name_th'] ?? '',
                        'generic_name' => $record['generic_name'] ?? '',
                        'manufacturer' => $record['manufacturer_name'] ?? '',
                        'drug_class' => $record['drug_type'] ?? 'ยาอันตราย',
                        'fda_registration_no' => $record['new_regis_no'] ?? '',
                        'precautions' => 'โปรดตรวจสอบรายละเอียดในเอกสารกำกับยา',
                        'side_effects' => '',
                        'default_instructions' => '',
                    ];
                }
            }

            return $items;
        } catch (\Exception $e) {
            return $this->getMockThaiData($query);
        }
    }

    /**
     * Simulated Thai Data when API Key is missing
     */
    private function getMockThaiData($query)
    {
        $mockData = [
            [
                'source' => 'Thai FDA (Preview)',
                'sku' => 'PAN-500',
                'barcode' => '8850123456001',
                'name' => 'Panadol 500mg',
                'name_th' => 'พานาดอล 500 มก.',
                'generic_name' => 'Paracetamol',
                'manufacturer' => 'GSK Consumer Healthcare',
                'drug_class' => 'ยาสามัญประจำบ้าน',
                'fda_registration_no' => '1A 123/45',
                'precautions' => 'ไม่ควรรับประทานเกินวันละ 8 เม็ด',
                'side_effects' => 'ผื่นคัน, คลื่นไส้ (หากแพ้ยา)',
                'default_instructions' => 'รับประทานครั้งละ 1-2 เม็ด ทุก 4-6 ชั่วโมง',
            ],
            // ... more mock items can be added
        ];

        return array_filter($mockData, function ($item) use ($query) {
            $query = strtolower($query);
            return str_contains(strtolower($item['name']), $query) ||
                str_contains(strtolower($item['name_th']), $query) ||
                str_contains(strtolower($item['generic_name']), $query);
        });
    }
}
