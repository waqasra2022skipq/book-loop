# Book Loans Implementation Guide

## Overview

This implementation extends the existing book-loop system to include a comprehensive book loan management system. The loan system tracks the entire lifecycle of a book from the moment a request is accepted until the book is returned and confirmed by the owner.

## Database Schema

### New Table: `book_loans`

```sql
CREATE TABLE book_loans (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    book_request_id BIGINT NOT NULL,
    book_id BIGINT NOT NULL,
    book_instance_id BIGINT NOT NULL,
    borrower_id BIGINT NOT NULL,
    owner_id BIGINT NOT NULL,
    delivered_date DATE NULL,
    due_date DATE NOT NULL,
    return_date DATE NULL,
    status ENUM('delivered', 'received', 'reading', 'returned', 'return_confirmed', 'return_denied', 'lost', 'disputed') DEFAULT 'delivered',
    notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (book_request_id) REFERENCES book_requests(id),
    FOREIGN KEY (book_id) REFERENCES books(id),
    FOREIGN KEY (book_instance_id) REFERENCES book_instances(id),
    FOREIGN KEY (borrower_id) REFERENCES users(id),
    FOREIGN KEY (owner_id) REFERENCES users(id)
);
```

## Loan Lifecycle States

### 1. **delivered** (Initial State)
- Owner marks book as delivered to borrower
- Loan start date and due date are set
- BookInstance status changes to 'borrowed'
- Borrower is notified

### 2. **received**
- Borrower confirms receipt of the book
- Owner is notified of confirmation

### 3. **reading**
- Borrower marks book as being actively read
- Optional status for tracking reading progress

### 4. **returned**
- Borrower claims to have returned the book
- Owner is notified to confirm receipt
- Awaiting owner confirmation

### 5. **return_confirmed**
- Owner confirms receipt of returned book
- Loan is completed successfully
- BookInstance status changes back to 'available'
- Borrower is notified

### 6. **return_denied**
- Owner denies receiving the returned book
- Dispute process may be initiated
- Borrower is notified

### 7. **lost**
- Book is marked as lost
- Can be triggered by either party
- BookInstance remains as 'borrowed' until resolved

### 8. **disputed**
- Dispute has been raised
- Requires manual resolution
- Can be triggered by either party

## Key Features

### Loan Management
- **Automatic Loan Creation**: When a book request is accepted, a loan is automatically created
- **Flexible Due Dates**: Default 30-day loan period, customizable by owner
- **Status Tracking**: Complete visibility into loan status for both parties
- **Overdue Detection**: Automatic calculation of overdue loans
- **Loan Extensions**: Owners can extend loan periods

### Notifications
- **Real-time Updates**: Both parties receive notifications on status changes
- **Contextual Messages**: Different messages based on the status and user role
- **Integration**: Uses existing notification system

### Statistics & Reporting
- **User Statistics**: Comprehensive loan statistics for both borrowers and owners
- **Active Loans**: Track currently active loans
- **Completed Loans**: History of successful returns
- **Problem Loans**: Overdue, lost, or disputed loans

## File Structure

### Models
- `app/Models/BookLoan.php` - Main loan model with relationships and helper methods
- Updated `app/Models/BookRequest.php` - Added loan relationship
- Updated `app/Models/BookInstance.php` - Added loan relationships
- Updated `app/Models/User.php` - Added loan relationships
- Updated `app/Models/Book.php` - Added loan relationships

### Services
- `app/Services/BookLoanService.php` - Comprehensive loan management service
- Updated `app/Services/BookRequestService.php` - Integration with loan creation

### Livewire Components
- `app/Livewire/BookLoans.php` - Main loan management interface
- Updated `app/Livewire/MyBookRequests.php` - Added loan duration setting

### Views
- `resources/views/livewire/book-loans.blade.php` - Loan management UI

### Database
- `database/migrations/2025_08_09_000000_create_book_loans_table.php` - Migration
- `database/factories/BookLoanFactory.php` - Factory for testing
- `database/factories/BookRequestFactory.php` - Missing factory created
- `database/seeders/BookLoanSeeder.php` - Sample data seeder

## API Endpoints

### Loan Management Routes
- `GET /books/loans` - Main loan management page (authenticated users only)

### Existing Routes Enhanced
- `POST /books/requests/{id}/accept` - Now creates a loan with optional duration

## Usage Examples

### Creating a Loan from Accepted Request
```php
// In BookRequestService
$loan = BookLoanService::createLoanFromRequest($request, $loanDurationDays);
```

### Updating Loan Status
```php
// Mark as received
BookLoanService::markAsReceived($loan, 'Received in good condition');

// Confirm return
BookLoanService::confirmReturn($loan, 'Book returned in excellent condition');

// Extend loan
BookLoanService::extendLoan($loan, 14, 'Extension granted due to travel');
```

### Getting User Statistics
```php
$stats = BookLoanService::getLoanStatistics($userId);
// Returns detailed statistics for both borrower and owner roles
```

## User Interface Features

### Dual Perspective Design
- **Borrower View**: Shows books you've borrowed
- **Owner View**: Shows books you've loaned out
- **Tabbed Interface**: Easy switching between perspectives

### Action-Based Controls
- **Context-Sensitive Actions**: Only relevant actions are shown based on loan status and user role
- **Modal Confirmations**: Important actions require confirmation with optional notes
- **Real-time Updates**: Interface updates immediately after actions

### Status Visualization
- **Color-Coded Badges**: Visual status indicators
- **Overdue Warnings**: Clear indication of overdue loans
- **Due Date Alerts**: Warnings for loans due soon

## Security Considerations

### Access Control
- **User-Specific Data**: Users only see their own loans (as borrower or owner)
- **Action Permissions**: Status changes are restricted based on user role and current status
- **Data Validation**: All input is validated before database updates

### Data Integrity
- **Transaction Safety**: Database operations use transactions
- **Constraint Enforcement**: Foreign key constraints ensure data consistency
- **Soft Deletes**: Loans can be soft-deleted for audit trails

## Testing Data

The seeder creates sample loans with various statuses:
- Active loans (delivered, received, reading)
- Completed loans (return_confirmed)
- Problem loans (overdue, lost, disputed)
- Pending returns (returned)

## Integration Points

### Existing System Integration
- **Notification System**: Reuses existing notification infrastructure
- **User Authentication**: Integrates with existing auth system
- **UI Framework**: Uses existing Livewire and styling approach
- **Database Structure**: Extends existing tables with relationships

### Future Enhancements
- **Rating System**: Add book/user ratings after completed loans
- **Automated Reminders**: Email reminders for due dates
- **Dispute Resolution**: Admin interface for handling disputes
- **Analytics Dashboard**: Advanced reporting and analytics
- **Mobile Optimization**: Enhanced mobile experience

## Deployment Notes

1. **Run Migration**: `php artisan migrate`
2. **Seed Data** (optional): `php artisan db:seed --class=BookLoanSeeder`
3. **Clear Cache**: `php artisan config:clear && php artisan view:clear`
4. **Set Permissions**: Ensure storage directory has proper permissions
5. **Update Navigation**: The navigation has been updated to include the loans link

## Configuration

### Default Settings
- **Default Loan Duration**: 30 days (configurable in `BookLoanService::DEFAULT_LOAN_DURATION`)
- **Notification Messages**: Customizable in `BookLoanService::sendLoanNotification()`
- **Status Transitions**: Defined in model constants and service methods

This implementation provides a complete, production-ready loan management system that seamlessly integrates with the existing book-loop application while maintaining code quality and user experience standards.
