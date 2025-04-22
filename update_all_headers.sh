#!/bin/bash

# Script to update all Blade views to use the unified header component
# Created: April 22, 2025

echo "Starting header update process..."

# Define directories to search
DIRS_TO_SEARCH=(
    "/Users/turgayyilmaz/Desktop/muhasebeeu/resources/views/user"
    "/Users/turgayyilmaz/Desktop/muhasebeeu/resources/views/tax-calendar"
    "/Users/turgayyilmaz/Desktop/muhasebeeu/resources/views/invoices"
    "/Users/turgayyilmaz/Desktop/muhasebeeu/resources/views/documents"
    "/Users/turgayyilmaz/Desktop/muhasebeeu/resources/views/companies"
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
    
    # Check if file has the old header pattern
    if grep -q "<x-slot name=\"header\">" "$file"; then
        echo "  - Updating: $file"
        
        # Create a temporary file with the changes
        awk '
        BEGIN { updated = 0; in_header = 0; }
        
        # When we find the app-layout opening tag, we prepare for header replacement
        /<x-app-layout>/ { 
            print $0; 
            next; 
        }
        
        # When we find the header slot, we mark that we are in the header section
        /<x-slot name="header">/ { 
            in_header = 1; 
            # Print the unified header instead
            print "    <x-unified-header />";
            next; 
        }
        
        # If we are in the header section and find the closing tag, we exit the header section
        in_header && /<\/x-slot>/ { 
            in_header = 0; 
            updated = 1; 
            next; 
        }
        
        # Skip all lines inside the header section
        in_header { next; }
        
        # Print all other lines normally
        { print $0; }
        ' "$file" > "$temp_file"
        
        # Replace the original file with the temporary file
        mv "$temp_file" "$file"
        ((UPDATED_FILES++))
    else
        echo "  - No header slot found: $file"
        ((SKIPPED_FILES++))
    fi
}

# Main process
echo "Searching for Blade files in specified directories..."

for dir in "${DIRS_TO_SEARCH[@]}"; do
    echo "Processing directory: $dir"
    
    # Find all Blade files in the directory
    while IFS= read -r file; do
        ((TOTAL_FILES++))
        update_file "$file"
    done < <(find "$dir" -type f -name "*.blade.php")
done

# Rebuild assets
echo "Rebuilding assets..."
cd /Users/turgayyilmaz/Desktop/muhasebeeu
npm run build

# Summary
echo ""
echo "=== Header Update Summary ==="
echo "Total files processed: $TOTAL_FILES"
echo "Files updated: $UPDATED_FILES"
echo "Files skipped: $SKIPPED_FILES"
echo "==========================="
echo ""
echo "Header update process completed!"
