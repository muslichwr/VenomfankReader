<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use App\Helpers\TransactionHelper;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    // Payment status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';
    public const STATUS_REFUNDED = 'refunded';
    
    // Payment method constants
    public const METHOD_MIDTRANS = 'midtrans';
    public const METHOD_ADMIN_ADJUSTMENT = 'admin_adjustment';

    protected $fillable = [
        'user_id',
        'coin_package_id',
        'coins_received',
        'amount_paid',
        'payment_status',
        'payment_method',
        'payment_code',
    ];

    protected $casts = [
        'coins_received' => 'integer',
        'amount_paid' => 'decimal:2',
    ];

    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate UUID if not set
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
            
            // Generate payment code if not set
            if (empty($model->payment_code)) {
                $model->payment_code = TransactionHelper::generatePaymentCode();
            }
        });
    }

    /**
     * Get the user that owns the transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the coin package for this transaction.
     */
    public function coinPackage(): BelongsTo
    {
        return $this->belongsTo(CoinPackage::class);
    }
    
    /**
     * Mark transaction as completed and add coins to user.
     */
    public function markAsCompleted(): bool
    {
        // Only process if not already completed
        if ($this->payment_status === self::STATUS_COMPLETED) {
            return true;
        }
        
        // Update status
        $this->payment_status = self::STATUS_COMPLETED;
        $saved = $this->save();
        
        // Add coins to user's balance
        if ($saved) {
            $this->user->addCoins($this->coins_received);
        }
        
        return $saved;
    }
    
    /**
     * Mark transaction as failed.
     */
    public function markAsFailed(): bool
    {
        $this->payment_status = self::STATUS_FAILED;
        return $this->save();
    }
    
    /**
     * Mark transaction as refunded and deduct coins if possible.
     */
    public function markAsRefunded(): bool
    {
        // Only refund if was previously completed
        if ($this->payment_status !== self::STATUS_COMPLETED) {
            return false;
        }
        
        // Try to remove coins from user balance
        $coinRemoved = $this->user->subtractCoins($this->coins_received);
        
        // If coins couldn't be removed (user already spent them), still mark as refunded
        $this->payment_status = self::STATUS_REFUNDED;
        return $this->save();
    }
    
    /**
     * Check if the transaction is completed.
     */
    public function isCompleted(): bool
    {
        return $this->payment_status === self::STATUS_COMPLETED;
    }
    
    /**
     * Check if the transaction is pending.
     */
    public function isPending(): bool
    {
        return $this->payment_status === self::STATUS_PENDING;
    }
    
    /**
     * Check if the transaction is failed.
     */
    public function isFailed(): bool
    {
        return $this->payment_status === self::STATUS_FAILED;
    }

    /**
     * Format the payment code for display.
     *
     * @return string|null
     */
    public function getFormattedPaymentCodeAttribute(): ?string
    {
        if (!$this->payment_code) {
            return null;
        }

        return TransactionHelper::formatPaymentCode($this->payment_code);
    }
    
    /**
     * Generate a new payment code for this transaction.
     *
     * @return string
     */
    public function generatePaymentCode(): string
    {
        $this->payment_code = TransactionHelper::generatePaymentCode();
        $this->save();
        
        return $this->payment_code;
    }
}
