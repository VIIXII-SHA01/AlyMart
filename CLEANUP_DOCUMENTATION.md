# Database Cleanup System

This system automatically removes old sales and inventory records to keep the database optimized and prevent excessive data accumulation.

## Overview

The cleanup system deletes the following records older than the specified time period:
- Sales and sale items
- Inventory movements  
- Notifications

## Default Settings

- **Default retention period**: 37 days (1 month + 7 days)
- **Automatic schedule**: Daily at 2:00 AM
- **Manual trigger**: Available through admin interface

## Usage

### 1. Automatic Cleanup (Recommended)

The system runs automatically every day at 2:00 AM. No manual intervention required.

### 2. Manual Command Line

```bash
# Run with default settings (37 days)
php artisan app:cleanup-old-records

# Run with custom retention period
php artisan app:cleanup-old-records --days=30

# Run with 60 days retention
php artisan app:cleanup-old-records --days=60
```

### 3. Web Interface (Admin Only)

1. Log in as admin
2. Navigate to **System** → **System Maintenance**
3. Set the desired retention period (days)
4. Click **Run Cleanup**
5. Monitor the output for results

## Command Options

```
php artisan app:cleanup-old-records [options]

Options:
  --days[=DAYS]  Number of days to keep records (default: 37)
  --help         Display help
```

## What Gets Cleaned

### Sales Records
- Sales older than retention period
- Associated sale items
- Related inventory movements

### Inventory Records
- Stock movements older than retention period
- Manual adjustments
- Transfer records

### Notifications
- System notifications older than retention period
- Stock alerts
- User notifications

## Safety Features

### Database Transactions
- All deletions run within database transactions
- Rollback on any error
- Data integrity maintained

### Foreign Key Constraints
- Sale items deleted before sales
- Proper dependency order maintained
- No orphaned records

### Logging
- All cleanup operations logged
- Error tracking and reporting
- Performance metrics

## Configuration

### Modify Retention Period

Edit `app/Console/Commands/CleanupOldRecords.php`:

```php
// Change default retention period
protected $signature = 'app:cleanup-old-records {--days=60 : Number of days to keep records}';
```

### Modify Schedule

Edit `app/Console/Kernel.php`:

```php
// Change automatic schedule
$schedule->command('app:cleanup-old-records')
         ->dailyAt('03:00')  // Change to 3 AM
         ->withoutOverlapping();
```

## Monitoring

### Log Files
Check `storage/logs/laravel.log` for cleanup activity:

```bash
tail -f storage/logs/laravel.log | grep "cleanup"
```

### Database Statistics
Monitor database size and performance:

```sql
-- Check record counts
SELECT 
    (SELECT COUNT(*) FROM sales) as total_sales,
    (SELECT COUNT(*) FROM inventory_movements) as total_movements,
    (SELECT COUNT(*) FROM notifications) as total_notifications;
```

## Best Practices

### 1. Regular Monitoring
- Check cleanup logs weekly
- Monitor database performance
- Verify retention periods

### 2. Backup Strategy
- Regular database backups
- Backup before major cleanup changes
- Test restore procedures

### 3. Performance Considerations
- Run cleanup during low-traffic hours
- Monitor system performance during cleanup
- Adjust schedule if needed

### 4. Compliance Requirements
- Verify data retention policies
- Check legal requirements for data storage
- Document cleanup procedures

## Troubleshooting

### Common Issues

#### Cleanup Fails
- Check database permissions
- Verify foreign key constraints
- Review error logs

#### Performance Issues
- Run cleanup during off-peak hours
- Consider batch processing for large datasets
- Optimize database indexes

#### Data Loss
- Always backup before cleanup
- Test with small retention periods first
- Verify cleanup results

### Emergency Recovery

If cleanup deletes important data:

1. Stop any running cleanup processes
2. Restore from recent backup
3. Review and fix cleanup logic
4. Test with safe retention period

## Examples

### Custom Retention Periods

```bash
# Keep last 30 days
php artisan app:cleanup-old-records --days=30

# Keep last 90 days  
php artisan app:cleanup-old-records --days=90

# Keep last 6 months
php artisan app:cleanup-old-records --days=180
```

### Scheduled Tasks

```bash
# Add to crontab for additional scheduling
0 2 * * * cd /path/to/project && php artisan app:cleanup-old-records
```

## Support

For issues or questions:
1. Check Laravel logs
2. Review this documentation
3. Test with safe retention periods
4. Contact system administrator

---

**Important**: This cleanup permanently deletes data. Always backup your database before running cleanup operations.
