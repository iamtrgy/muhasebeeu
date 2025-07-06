-- BACKUP SCRIPT - Run this BEFORE migrating
-- This creates a complete backup of your tax calendar data

-- Backup the entire table structure and data
CREATE TABLE tax_calendar_tasks_backup AS SELECT * FROM tax_calendar_tasks;

-- Backup task messages if they exist
CREATE TABLE task_messages_backup AS SELECT * FROM task_messages WHERE 1=1;

-- Create a summary report of what will be migrated
SELECT 
    'Data Summary' as report_type,
    COUNT(*) as total_tasks,
    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_tasks,
    SUM(CASE WHEN status = 'under_review' THEN 1 ELSE 0 END) as under_review_tasks,
    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_tasks,
    SUM(CASE WHEN user_checklist IS NOT NULL THEN 1 ELSE 0 END) as tasks_with_user_progress,
    SUM(CASE WHEN review_feedback IS NOT NULL THEN 1 ELSE 0 END) as tasks_with_feedback
FROM tax_calendar_tasks;

-- Show which tasks have user progress that will be preserved
SELECT 
    id,
    tax_calendar_id,
    company_id,
    status,
    CASE WHEN user_checklist IS NOT NULL THEN 'HAS USER PROGRESS' ELSE 'NO USER PROGRESS' END as user_progress_status,
    CASE WHEN review_feedback IS NOT NULL THEN 'HAS FEEDBACK' ELSE 'NO FEEDBACK' END as feedback_status
FROM tax_calendar_tasks
ORDER BY id;