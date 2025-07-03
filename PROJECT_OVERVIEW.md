# Muhasebe EU - European Accounting & Business Management Platform

## Project Overview

**Muhasebe EU** (Turkish for "Accounting EU") is a comprehensive multi-tenant SaaS accounting and business management platform specifically designed for Estonian and European Union businesses. Built with Laravel 12 and modern web technologies, it provides a complete solution for accounting, tax compliance, document management, and payroll processing.

## Technology Stack

### Backend
- **Framework**: Laravel 12.0 (Latest version)
- **PHP Version**: 8.2+
- **Database**: SQLite (default), MySQL/PostgreSQL compatible
- **Queue System**: Database-driven
- **Cache System**: Database-based
- **Session Management**: Database sessions (120-minute lifetime)

### Frontend
- **Livewire 3.6**: Reactive UI components
- **Alpine.js 3.14**: Lightweight JavaScript framework
- **Tailwind CSS 3.1**: Utility-first CSS framework
- **Vite**: Modern build tool
- **Dropzone.js**: File upload functionality
- **Axios**: HTTP client

### Third-Party Integrations
- **Stripe**: Payment processing and subscription management
- **Bunny CDN**: File storage and content delivery
- **Estonian Business Registry API**: Company data integration
- **Barryvdh/Laravel-DomPDF**: PDF generation
- **Spatie/PDF-to-Text**: PDF text extraction

## Core Features

### 1. Multi-Tenant Architecture
- Users can manage multiple companies
- Company-level data isolation
- Role-based access control (Admin, Accountant, User)
- Current company switching functionality

### 2. User Management & Authentication
- **Three user roles**:
  - **Admin**: System-wide access, user management, subscription control
  - **Accountant**: Manage multiple clients, review tax tasks, approve documents
  - **User**: Business owner/employee with company-specific access
- Email verification
- Activity logging and audit trails
- Profile management

### 3. Subscription & Billing
- **Three subscription tiers**:
  - Basic Plan
  - Pro Plan
  - Enterprise Plan
- Stripe integration for payment processing
- 30-day trial period
- Grace period for canceled subscriptions
- Subscription management (create, cancel, resume, swap)
- Billing portal access

### 4. Company Management
- Estonian company integration via Business Registry API
- Automatic company data retrieval using registry code
- Multi-company support per user
- Company details: tax number, VAT number, address, foundation date
- Company-user relationships with role assignments

### 5. Document Management System
- **Hierarchical folder structure**:
  ```
  Company
  └── Year (e.g., 2025)
      └── Month (01-12)
          ├── Income
          ├── Expense
          ├── Banks
          └── Other
  ```
- Bunny CDN integration for secure file storage
- Chunked file upload for large files
- AI-powered document classification (infrastructure ready)
- Folder-based access control
- Automatic folder creation for future months
- File preview and download capabilities

### 6. Invoice Management
- Create, edit, and manage invoices
- Multi-language support (Turkish, English, German)
- PDF generation with custom templates
- Automatic invoice numbering
- Client/customer management
- Invoice status tracking (draft, sent, paid)
- EU-specific features:
  - Reverse charge mechanism
  - VAT exemption handling
- Invoice items with tax calculations

### 7. Tax Calendar & Compliance
- **Tax obligation tracking**:
  - Monthly, quarterly, and annual obligations
  - Country-specific tax requirements
  - EMTA (Estonian Tax Authority) integration
- **Task workflow**:
  - Pending → In Progress → Under Review → Approved/Rejected
- **Dual checklist system**:
  - Accountant checklist
  - User checklist
- Task messaging between users and accountants
- Progress tracking and reminders
- Automatic task creation from tax calendar

### 8. Estonian-Specific Features
- Registry code (tax number) validation
- Automatic company information retrieval
- Estonian tax compliance support
- EMTA form codes and links
- Foundation date tracking

### 9. Payroll System (In Development)
Based on uncommitted files, the system includes:
- Employee management
- Compensation types (salary, bonus, benefits)
- Time entry tracking with approval workflow
- Payroll period processing
- Tax rate management
- Employee compensation approvals

### 10. Onboarding Process
- Step-by-step guided setup:
  1. Country selection
  2. Company creation/selection
  3. Subscription plan selection
- Automatic folder structure creation
- Support for existing companies

## Security & Compliance

- Role-based access control (RBAC)
- Company-level data isolation
- Secure file storage with CDN
- Activity logging for audit trails
- GDPR-compliant data handling
- Database-based sessions
- Email verification requirement

## User Workflows

### For Business Owners (Users)
1. Complete onboarding process
2. Upload documents to organized folders
3. Create and manage invoices
4. Complete tax calendar tasks
5. Communicate with accountant via task messages
6. Track business metrics via dashboard

### For Accountants
1. Manage multiple client companies
2. Review and approve tax calendar tasks
3. Access client documents and files
4. Provide feedback via task messaging
5. Monitor client compliance status
6. Approve payroll and time entries

### For Administrators
1. Manage all users and subscriptions
2. Monitor system usage and statistics
3. Create and manage companies
4. Assign accountants to users/companies
5. Access all files and folders
6. Debug subscription issues

## Key Differentiators

1. **Estonian Business Integration**: Direct integration with Estonian Business Registry for automatic company data retrieval
2. **Multi-Role System**: Comprehensive role-based system supporting business owners, accountants, and administrators
3. **Tax Compliance Focus**: Built-in tax calendar with Estonian tax authority integration
4. **Automated Organization**: Automatic folder structure creation and document classification
5. **Multi-Language Support**: Interface and invoice generation in multiple languages
6. **SaaS-Ready**: Complete subscription management with Stripe integration

## Future Enhancements

1. **AI-Powered Features**:
   - Document classification and automatic filing
   - Expense categorization
   - Invoice data extraction

2. **Enhanced Payroll**:
   - Complete payroll processing
   - Automatic tax calculations
   - Employee self-service portal

3. **Reporting & Analytics**:
   - Financial reports
   - Tax reports
   - Business insights dashboard

4. **Mobile Application**:
   - Document upload on-the-go
   - Invoice creation
   - Task management

## Technical Architecture

### Database Structure
- **Multi-tenancy**: Company-based data isolation
- **Soft deletes**: Data retention for compliance
- **Audit trails**: Activity logging for all actions
- **Approval workflows**: For time entries, payroll, and tax tasks

### File Storage
- **Bunny CDN**: Primary storage provider
- **Chunked uploads**: Support for large files
- **Secure access**: URL-based file access with authentication

### Queue System
- Database-driven for reliability
- Used for:
  - File processing
  - Email notifications
  - Background tasks

## Deployment Considerations

1. **Environment Requirements**:
   - PHP 8.2+
   - Composer 2.x
   - Node.js 16+
   - SQLite/MySQL/PostgreSQL

2. **External Services**:
   - Stripe account for payments
   - Bunny CDN account for file storage
   - SMTP service for emails
   - Estonian Business Registry API access

3. **Security Configurations**:
   - SSL certificate required
   - Secure session configuration
   - Environment variable protection
   - Regular security updates

## View Folder Structure Guidelines

### Current Issues
1. **Inconsistent Dashboard Locations**:
   - Admin: `/admin/dashboard.blade.php`
   - Accountant: `/accountant/dashboard/index.blade.php`
   - User: `/user/dashboard.blade.php`

2. **Naming Inconsistencies**:
   - Some files use underscores: `subscription_debug.blade.php`
   - Others use hyphens: `subscription-debug.blade.php`
   - Mixed conventions: `company_temp` folder vs other folders

3. **Duplicate/Test Files**:
   - Multiple test files in root views directory
   - Backup files (.bak) in production code
   - Duplicate subscription debug files

### Recommended Structure
```
resources/views/
├── admin/
│   ├── dashboard/
│   │   └── index.blade.php
│   ├── companies/
│   ├── users/
│   ├── folders/
│   └── profile/
├── accountant/
│   ├── dashboard/
│   │   └── index.blade.php (already correct)
│   ├── companies/
│   ├── users/
│   └── profile/
├── user/
│   ├── dashboard/
│   │   └── index.blade.php (needs restructuring)
│   ├── companies/
│   ├── invoices/
│   ├── folders/
│   └── profile/
├── auth/
├── components/
├── layouts/
├── onboarding/
├── tax-calendar/
├── emails/ (if needed)
└── shared/ (for views used across roles)
```

### Naming Conventions
1. **Use hyphens for file names**: `subscription-debug.blade.php`
2. **Use snake_case for folder names if multi-word**: `tax_calendar` or preferably single words
3. **Consistent index.blade.php for main views**
4. **Group related partials in `partials/` subdirectories**

### Action Items Before Table Component Migration
1. **Standardize dashboard locations** - Move all to `/role/dashboard/index.blade.php`
2. **Remove test files** from production views directory
3. **Fix naming inconsistencies** - Choose either hyphens or underscores
4. **Clean up duplicates** - Remove duplicate subscription debug files
5. **Move temporary/test components** to a dedicated test directory

## Conclusion

Muhasebe EU is a production-ready, enterprise-level accounting platform that combines modern web technologies with specific Estonian and EU business requirements. Its multi-tenant architecture, comprehensive feature set, and focus on tax compliance make it an ideal solution for Estonian businesses and their accountants.