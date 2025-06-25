# Summary of Database Structure

I've designed and implemented a complete database structure for your manga/novel reading platform with the following features:

## Models and Tables

### 1. Series
- Uses UUIDs for IDs and slugs (for URLs like `https://webbaca.com/series/d8efab60-cada-4d99-be3a-e813f75ba72f`)
- Tracks type (manga, manhwa, manhua, novel) using enum
- Tracks status (ongoing, completed, hiatus) using enum
- Has popularity and featured flags for featuring content
- Configurable number of free chapters before requiring payment
- View count tracking for popularity metrics
- Cover image storage
- Author information
- Soft deletes for data integrity
- Relations to chapters and categories
- Methods for retrieving first/latest chapters
- Methods for checking chapter counts
- Methods for retrieving related series based on categories

### 2. Chapters
- Uses UUIDs for IDs and slugs (for URLs like `https://webbaca.com/chapter/9bb2ff1e-b042-47ab-b647-e32e7abaf3ad`)
- Belongs to a series
- Can be free or paid (with coin cost)
- Tracks content (text or image paths)
- Chapter numbering for sequence
- View count tracking
- Soft deletes
- Methods for navigation (next/previous chapter)
- Methods to check if first/last chapter
- Content processing based on series type (novel text vs manga images)

### 3. Users
- Extended with coin balance for the payment system
- Methods for adding/subtracting coins
- Methods for checking chapter access permissions
- Methods for purchasing chapters with permanent access
- Methods to check if user can afford a chapter
- Relations to transactions, coin usages, and reading history

### 4. CoinPackages
- Defines packages that users can purchase
- Has price, coin amount, and feature flag
- Optional description field
- Active/inactive status
- Soft deletes
- Methods to calculate value ratio (coins per currency)
- Methods to identify best value packages
- Methods to get active and featured packages
- Method to recommend packages based on coin requirements

### 5. Transactions
- Tracks coin purchases by users
- Uses UUIDs
- Records payment status and method (with constants)
- Records amount paid and coins received
- Soft deletes
- Methods for marking transactions as completed/failed/refunded
- Methods for checking transaction status
- Automatic coin balance update on status changes

### 6. CoinUsages
- Tracks when users spend coins to access chapters
- Uses UUIDs
- Records coins spent
- Provides permanent lifetime access to purchased chapters
- Unique constraint to prevent duplicate purchases
- Soft deletes
- Static methods to find existing access records

### 7. ReadingHistory
- Tracks user reading patterns
- Records progress percentage through chapters
- Timestamps for last read times
- Allows users to continue where they left off
- Soft deletes
- Methods to mark chapters as read/complete
- Methods to check reading status (completed, recent)
- Methods to get next chapter to read
- Static methods for retrieving reading history

### 8. Categories
- For categorizing series content by genre/type
- Has many-to-many relationship with series
- Uses slug-based URLs
- Optional description field
- Soft deletes
- Methods to get series counts
- Methods to get active, popular, featured series
- Methods to get recently updated series
- Slug generation and uniqueness checking

## Key Features

- **UUID Slugs**: Both series and chapters use UUID slugs in URLs for security and consistency
- **Coin System**: Full support for free/paid content with coin purchases and usage tracking
- **Reading History**: Track user progress through series and chapters
- **Categories**: Organize content by categories/genres
- **Soft Deletes**: All models use soft deletes for data integrity
- **View Counts**: Track popularity of series and chapters
- **Relationships**: Proper Eloquent relationships between all models
- **Rich Model Methods**: Custom methods on all models to support business logic
- **Payment Processing**: Complete transaction lifecycle handling
- **Permanent Access**: All purchased chapters are available permanently to users
- **Navigation**: Methods for series and chapter navigation
- **Recommendations**: Methods for finding related content

## Usage Example Flow

1. User registers and gets default 0 coins
2. User browses series catalog (sorted by popularity, type, or category)
3. User selects a series (e.g., `https://webbaca.com/series/d8efab60-cada-4d99-be3a-e813f75ba72f`)
4. User reads free chapters (first 3 by default)
5. When trying to access a paid chapter, they're prompted to purchase coins
6. User buys a coin package, transaction is recorded
7. User spends coins to unlock paid chapter(s) permanently
8. Reading history is tracked automatically
9. User can navigate between chapters with next/previous functionality
10. User can see their reading progress and continue where they left off

This structure provides a solid foundation for your manga reading platform, with all the key features implemented and ready for you to build your UI with Filament for admin pages and Breeze for user authentication.
