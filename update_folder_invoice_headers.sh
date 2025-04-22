#!/bin/bash

# Script to update folder and invoice pages to use the unified header component
# Created: April 22, 2025

echo "Starting folder and invoice header update process..."

# Define directories to search
DIRS_TO_SEARCH=(
    "/Users/turgayyilmaz/Desktop/muhasebeeu/resources/views/user/folders"
    "/Users/turgayyilmaz/Desktop/muhasebeeu/resources/views/user/invoices"
    "/Users/turgayyilmaz/Desktop/muhasebeeu/resources/views/accountant/folders"
    "/Users/turgayyilmaz/Desktop/muhasebeeu/resources/views/accountant/invoices"
    "/Users/turgayyilmaz/Desktop/muhasebeeu/resources/views/admin/folders"
    "/Users/turgayyilmaz/Desktop/muhasebeeu/resources/views/admin/invoices"
)

# Counter for tracking changes
UPDATED_FILES=0
SKIPPED_FILES=0
TOTAL_FILES=0

# Function to update a file
update_file() {
    local file=$1
    local temp_file="${file}.temp"
    
    # Check if file already uses unified header
    if grep -q "<x-unified-header" "$file"; then
        echo "  - Already using unified header: $file"
        ((SKIPPED_FILES++))
        return
    fi
    
    echo "  - Updating: $file"
    
    # Create a temporary file with the changes
    awk '
    BEGIN { updated = 0; in_breadcrumb = 0; }
    
    # When we find the app-layout opening tag, we prepare for header replacement
    /<x-app-layout>/ { 
        print $0; 
        print "    <x-unified-header />";
        next; 
    }
    
    # Skip the file-preview-modal line if it exists
    /<x-folder.file-preview-modal/ {
        print $0;
        next;
    }
    
    # When we find the custom breadcrumb section, we mark that we are in that section
    /<!-- Add .* Breadcrumb Navigation -->/ || /class="bg-white.*shadow.*sm:rounded-lg mb-6"/ { 
        in_breadcrumb = 1; 
        next; 
    }
    
    # If we are in the breadcrumb section and find a closing div that matches the pattern
    in_breadcrumb && /<\/div>/ { 
        in_breadcrumb--; 
        if (in_breadcrumb <= 0) {
            in_breadcrumb = 0;
            updated = 1;
        }
        next; 
    }
    
    # Count opening divs in breadcrumb section to ensure we capture the entire section
    in_breadcrumb && /<div/ { 
        in_breadcrumb++; 
        next; 
    }
    
    # Skip all lines inside the breadcrumb section
    in_breadcrumb { next; }
    
    # Print all other lines normally
    { print $0; }
    ' "$file" > "$temp_file"
    
    # Check if changes were made
    if cmp -s "$file" "$temp_file"; then
        echo "    No changes needed for this file"
        rm "$temp_file"
        ((SKIPPED_FILES++))
    else
        # Replace the original file with the temporary file
        mv "$temp_file" "$file"
        ((UPDATED_FILES++))
    fi
}

# Main process
echo "Searching for Blade files in specified directories..."

for dir in "${DIRS_TO_SEARCH[@]}"; do
    if [ -d "$dir" ]; then
        echo "Processing directory: $dir"
        
        # Find all Blade files in the directory
        while IFS= read -r file; do
            ((TOTAL_FILES++))
            update_file "$file"
        done < <(find "$dir" -type f -name "*.blade.php")
    else
        echo "Directory not found: $dir (skipping)"
    fi
done

# Rebuild assets
echo "Rebuilding assets..."
cd /Users/turgayyilmaz/Desktop/muhasebeeu
npm run build

# Summary
echo ""
echo "=== Folder and Invoice Header Update Summary ==="
echo "Total files processed: $TOTAL_FILES"
echo "Files updated: $UPDATED_FILES"
echo "Files skipped: $SKIPPED_FILES"
echo "=============================================="
echo ""
echo "Header update process completed!"
