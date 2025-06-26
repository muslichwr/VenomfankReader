<?php

namespace App\Helpers;

class TransactionHelper
{
    /**
     * Generate a random payment code
     * 
     * @param int $length The length of the payment code
     * @return string
     */
    public static function generatePaymentCode(int $length = 10): string
    {
        // Current timestamp for uniqueness
        $timestamp = now()->format('YmdHis');
        
        // Generate random part
        $characters = '0123456789';
        $randomPart = '';
        
        for ($i = 0; $i < $length - strlen($timestamp); $i++) {
            $randomPart .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        // Combine to ensure uniqueness
        $paymentCode = substr($timestamp . $randomPart, 0, $length);
        
        return $paymentCode;
    }
    
    /**
     * Validate if a payment code is in a valid format
     * 
     * @param string $code The payment code to validate
     * @return bool
     */
    public static function isValidPaymentCode(string $code): bool
    {
        // Check if code is numeric and has the correct length
        return is_numeric($code) && strlen($code) === 10;
    }
    
    /**
     * Format a payment code for display
     * 
     * @param string $code The payment code to format
     * @return string
     */
    public static function formatPaymentCode(string $code): string
    {
        // Format as groups of digits: XXX-XXX-XXXX
        if (strlen($code) === 10) {
            return substr($code, 0, 3) . '-' . 
                   substr($code, 3, 3) . '-' . 
                   substr($code, 6);
        }
        
        return $code;
    }
}
