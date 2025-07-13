<?php

namespace App\Services;

use App\Models\File;

class AIAnalysisDisplayService
{
    /**
     * Format AI analysis data for display
     */
    public static function formatAnalysis(?array $analysis): array
    {
        if (!$analysis) {
            return [
                'has_suggestion' => false,
                'suggestion_text' => 'No suggestion',
                'confidence' => null,
                'suggests_deletion' => false,
                'folder_name' => null,
                'folder_path' => null
            ];
        }

        return [
            'has_suggestion' => isset($analysis['folder_name']) || isset($analysis['suggest_deletion']),
            'suggestion_text' => self::getSuggestionText($analysis),
            'confidence' => $analysis['confidence'] ?? null,
            'suggests_deletion' => $analysis['suggest_deletion'] ?? false,
            'folder_name' => $analysis['folder_name'] ?? null,
            'folder_path' => $analysis['folder_path'] ?? $analysis['folder_name'] ?? null,
            'reasoning' => $analysis['reasoning'] ?? null
        ];
    }

    /**
     * Get suggestion text for display
     */
    private static function getSuggestionText(array $analysis): string
    {
        if (isset($analysis['suggest_deletion']) && $analysis['suggest_deletion']) {
            return 'Delete File';
        }

        if (isset($analysis['folder_name'])) {
            $suggestedPath = $analysis['folder_path'] ?? $analysis['folder_name'];
            $suggestedName = $analysis['folder_name'];
            
            if ($suggestedPath && str_contains($suggestedPath, '/')) {
                $suggestedName = last(explode('/', $suggestedPath));
            }
            
            return $suggestedName;
        }

        return 'No suggestion';
    }

    /**
     * Get AI analysis summary for file
     */
    public static function getAnalysisSummary(File $file): array
    {
        $analysis = self::formatAnalysis($file->ai_analysis);
        $status = FileStatusService::getFileStatus($file);

        return [
            'analyzed' => (bool) $file->ai_analyzed_at,
            'analyzed_at' => $file->ai_analyzed_at,
            'suggestion' => $analysis,
            'status' => $status,
            'action' => FileStatusService::getSuggestedAction($file)
        ];
    }

    /**
     * Get confidence level styling
     */
    public static function getConfidenceStyle(?int $confidence): array
    {
        if (!$confidence) {
            return ['color' => 'gray', 'text' => 'Unknown'];
        }

        if ($confidence >= 90) {
            return ['color' => 'green', 'text' => 'Very High'];
        }
        
        if ($confidence >= 80) {
            return ['color' => 'blue', 'text' => 'High'];
        }
        
        if ($confidence >= 70) {
            return ['color' => 'yellow', 'text' => 'Medium'];
        }
        
        return ['color' => 'red', 'text' => 'Low'];
    }

    /**
     * Check if analysis requires user attention
     */
    public static function requiresAttention(File $file): bool
    {
        $status = FileStatusService::getFileStatus($file);
        return $status['needs_action'];
    }

    /**
     * Get file type icon information
     */
    public static function getFileIcon(File $file): array
    {
        $extension = strtolower(pathinfo($file->original_name ?? $file->name, PATHINFO_EXTENSION));
        
        $iconMap = [
            'pdf' => ['color' => 'red-500', 'type' => 'document'],
            'jpg' => ['color' => 'green-500', 'type' => 'image'],
            'jpeg' => ['color' => 'green-500', 'type' => 'image'],
            'png' => ['color' => 'green-500', 'type' => 'image'],
            'gif' => ['color' => 'green-500', 'type' => 'image'],
            'webp' => ['color' => 'green-500', 'type' => 'image'],
            'doc' => ['color' => 'blue-500', 'type' => 'document'],
            'docx' => ['color' => 'blue-500', 'type' => 'document'],
            'xls' => ['color' => 'green-600', 'type' => 'document'],
            'xlsx' => ['color' => 'green-600', 'type' => 'document'],
            'txt' => ['color' => 'gray-400', 'type' => 'document'],
        ];

        return $iconMap[$extension] ?? ['color' => 'gray-400', 'type' => 'document'];
    }
}