# Filament Resources for Venomfank Manga/Novel Reader Platform

This document outlines the Filament 3.3 resources created for managing the Venomfank manga/novel reader platform with its coin system.

## Resource Structure

### Content Management Group

1. **SeriesResource**
   - Manages manga, manhwa, manhua, and novel series
   - UUID-based IDs for security
   - Rich form with sections for basic information, content, and settings
   - Relationship management for chapters and categories
   - Features toggles for popularity and featured status
   - Soft delete support for data integrity

2. **CategoryResource**
   - Manages content categories/genres
   - Relationship management with series
   - Auto-generating slugs from names
   - Soft delete support

### Payment Management Group

1. **CoinPackageResource**
   - Manages different coin packages users can purchase
   - Price and coin amount configuration
   - Active and featured status toggles
   - Value ratio calculation (coins per dollar)
   - Best value detection
   - Duplication functionality
   - Soft delete support

2. **TransactionResource**
   - Tracks user coin purchases
   - UUID-based IDs
   - Payment status workflow (pending → completed/failed → refunded)
   - Multiple payment method support (PayPal, Stripe, Bank Transfer, Admin Adjustment)
   - Pending transaction count badge in navigation
   - Actions for changing transaction status
   - Automatic user coin balance updates
   - Soft delete support

## Relationship Managers

1. **SeriesResource\RelationManagers\ChaptersRelationManager**
   - Manages chapters within a series
   - Dynamic form based on series type (novel vs manga/manhwa/manhua)
   - Free/paid chapter management with coin costs
   - Chapter numbering with decimal support for special chapters
   - Auto-incrementing chapter numbers
   - Bulk actions for toggling free/paid status

2. **CategoryResource\RelationManagers\SeriesRelationManager**
   - Manages series within categories
   - Many-to-many relationship
   - Quick series type and status setting
   - Attach existing series to categories

## Key Features

- **Consistent Navigation**: Organized in logical groups (Content Management, Payment Management)
- **Soft Deletes**: All resources implement soft deletes for data integrity
- **Rich Form Layout**: Sections and multi-column layouts for better UX
- **Resource Relationships**: Properly managed relationships between all models
- **UUID Security**: Uses UUIDs for key resources
- **Custom Actions**: Resource-specific actions like marking transactions as completed/failed
- **Bulk Operations**: Bulk actions for common tasks
- **Dynamic Forms**: Content forms that adapt based on content type
- **Filters and Sorting**: Comprehensive filtering and sorting options
- **Real-time Value Calculations**: Dynamic calculated fields like value ratio
- **Status Workflows**: Support for complex status workflows with visual indicators

## Next Steps

1. **User Management Resource**:
   - Add resource for managing users with coin balances
   - Track user reading history and purchases

2. **Reading History Resource**:
   - Create resource for managing user reading history
   - Track progress through series and chapters

3. **CoinUsage Resource**:
   - Track when users spend coins to access chapters
   - Ensure permanent lifetime access to purchased chapters

4. **Dashboard Widgets**:
   - Create dashboard for platform metrics
   - Track revenue, popular content, and user engagement

5. **Custom Theme**:
   - Apply Venomfank branding to the admin panel
   - Custom colors, logo, and layout 