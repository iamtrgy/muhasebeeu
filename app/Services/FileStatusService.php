<?php

namespace App\Services;

use App\Models\File;
use Carbon\Carbon;

class FileStatusService
{
    /**
     * Determine the status information for a file
     */
    public static function getFileStatus(File $file): array
    {
        $currentFolderId = $file->folder_id;
        $suggestedFolderId = $file->ai_analysis['suggested_folder_id'] ?? null;
        $isInCorrectFolder = $currentFolderId == $suggestedFolderId;
        $wasRecentlyAnalyzed = $file->ai_analyzed_at && $file->ai_analyzed_at->diffInMinutes(now()) < 5;
        $suggestsDeletion = isset($file->ai_analysis['suggest_deletion']) && $file->ai_analysis['suggest_deletion'];

        return [
            'current_folder_id' => $currentFolderId,
            'suggested_folder_id' => $suggestedFolderId,
            'is_in_correct_folder' => $isInCorrectFolder,
            'was_recently_analyzed' => $wasRecentlyAnalyzed,
            'suggests_deletion' => $suggestsDeletion,
            'status_type' => self::determineStatusType($suggestsDeletion, $isInCorrectFolder, $file->ai_suggestion_accepted, $wasRecentlyAnalyzed),
            'needs_action' => self::needsAction($suggestsDeletion, $isInCorrectFolder, $file->ai_suggestion_accepted)
        ];
    }

    /**
     * Determine the primary status type
     */
    private static function determineStatusType(bool $suggestsDeletion, bool $isInCorrectFolder, bool $suggestionAccepted, bool $wasRecentlyAnalyzed): string
    {
        if ($suggestsDeletion) {
            return 'needs_review_deletion';
        }
        
        if ($isInCorrectFolder) {
            return 'correct_place';
        }
        
        if ($suggestionAccepted) {
            return 'accepted';
        }
        
        if ($wasRecentlyAnalyzed) {
            return 'just_analyzed';
        }
        
        return 'needs_review';
    }

    /**
     * Check if file needs user action
     */
    private static function needsAction(bool $suggestsDeletion, bool $isInCorrectFolder, bool $suggestionAccepted): bool
    {
        return $suggestsDeletion || (!$isInCorrectFolder && !$suggestionAccepted);
    }

    /**
     * Get status display information
     */
    public static function getStatusDisplay(string $statusType): array
    {
        $statusMap = [
            'needs_review_deletion' => [
                'text' => 'Needs Review',
                'color' => 'amber',
                'icon' => 'clock',
                'clickable' => true
            ],
            'correct_place' => [
                'text' => 'Correct Place',
                'color' => 'green',
                'icon' => 'check',
                'clickable' => false
            ],
            'accepted' => [
                'text' => 'Accepted',
                'color' => 'green',
                'icon' => 'check',
                'clickable' => false
            ],
            'just_analyzed' => [
                'text' => 'Just Analyzed',
                'color' => 'blue',
                'icon' => 'refresh',
                'clickable' => true
            ],
            'needs_review' => [
                'text' => 'Needs Review',
                'color' => 'amber',
                'icon' => 'clock',
                'clickable' => true
            ]
        ];

        return $statusMap[$statusType] ?? $statusMap['needs_review'];
    }

    /**
     * Get suggested action for a file
     */
    public static function getSuggestedAction(File $file): ?array
    {
        $status = self::getFileStatus($file);
        
        if ($status['suggests_deletion']) {
            return [
                'type' => 'delete',
                'text' => 'Delete',
                'color' => 'red',
                'icon' => 'trash'
            ];
        }
        
        if (!$status['is_in_correct_folder'] && $status['suggested_folder_id']) {
            return [
                'type' => 'move',
                'text' => 'Accept',
                'color' => 'indigo',
                'icon' => 'check',
                'target_folder_id' => $status['suggested_folder_id']
            ];
        }
        
        return null;
    }
}