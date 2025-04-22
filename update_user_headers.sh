#!/bin/bash

# Script to update all user views to use the unified header approach
# This will find all blade files with breadcrumb navigation and update them

USER_VIEWS_DIR="/Users/turgayyilmaz/Desktop/muhasebeeu/resources/views/user"

# Function to update a file with the new header pattern
update_file() {
  local file=$1
  local title=$2
  
  # Read the file content
  content=$(cat "$file")
  
  # Check if the file has the old breadcrumb pattern
  if [[ $content == *"<!-- Breadcrumb Navigation -->"* || $content == *"<!-- Add Accountant Style Breadcrumb Navigation -->"* ]]; then
    echo "Updating $file with title: $title"
    
    # Create a temporary file
    tmp_file=$(mktemp)
    
    # Replace the old pattern with the new header
    perl -0777 -pe "s/<x-app-layout>\s*<div class=\"py-6\">\s*<div class=\"max-w-7xl mx-auto px-4 sm:px-6 lg:px-8\">\s*(<!-- (Breadcrumb|Add Accountant Style Breadcrumb) Navigation -->.*?<\/div>\s*<\/div>\s*<\/div>)/<x-app-layout>\n    <x-slot name=\"header\">\n        <h2 class=\"text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200\">\n            {{ __('$title') }}\n        <\/h2>\n    <\/x-slot>\n    \n    <div class=\"py-6\">\n        <div class=\"max-w-7xl mx-auto px-4 sm:px-6 lg:px-8\">/s" "$file" > "$tmp_file"
    
    # Move the temporary file to the original
    mv "$tmp_file" "$file"
  else
    echo "Skipping $file - no breadcrumb pattern found"
  fi
}

# Update specific files with appropriate titles
update_file "$USER_VIEWS_DIR/clients/index.blade.php" "Clients"
update_file "$USER_VIEWS_DIR/clients/create.blade.php" "Create Client"
update_file "$USER_VIEWS_DIR/clients/edit.blade.php" "Edit Client"

update_file "$USER_VIEWS_DIR/companies/index.blade.php" "Companies"
update_file "$USER_VIEWS_DIR/companies/edit.blade.php" "Edit Company"

update_file "$USER_VIEWS_DIR/customers/index.blade.php" "Customers"
update_file "$USER_VIEWS_DIR/customers/create.blade.php" "Create Customer"
update_file "$USER_VIEWS_DIR/customers/edit.blade.php" "Edit Customer"

update_file "$USER_VIEWS_DIR/folders/index.blade.php" "Folders"
update_file "$USER_VIEWS_DIR/folders/create.blade.php" "Create Folder"
update_file "$USER_VIEWS_DIR/folders/show.blade.php" "Folder Details"

update_file "$USER_VIEWS_DIR/invoices/index.blade.php" "Invoices"
update_file "$USER_VIEWS_DIR/invoices/create.blade.php" "Create Invoice"
update_file "$USER_VIEWS_DIR/invoices/edit.blade.php" "Edit Invoice"
update_file "$USER_VIEWS_DIR/invoices/show.blade.php" "Invoice Details"

update_file "$USER_VIEWS_DIR/profile/edit.blade.php" "Profile"

update_file "$USER_VIEWS_DIR/subscriptions/plans.blade.php" "Subscription Plans"
update_file "$USER_VIEWS_DIR/subscriptions/payment.blade.php" "Payment"
update_file "$USER_VIEWS_DIR/subscriptions/complete.blade.php" "Subscription Complete"

update_file "$USER_VIEWS_DIR/company_temp/index.blade.php" "Company Templates"

echo "All user views have been updated!"
