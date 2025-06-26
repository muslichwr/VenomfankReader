<div class="py-4">
    <div class="text-center">
        <h3 class="text-lg font-medium">Payment Code</h3>
        <div class="mt-4 bg-gray-100 rounded-lg py-5 px-10">
            <span id="payment-code" class="text-2xl font-bold text-primary-600">{{ $code }}</span>
        </div>
        <button 
            type="button" 
            class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700"
            onclick="copyPaymentCode()"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
            </svg>
            Copy Code
        </button>
        <div id="copy-success" class="mt-2 text-sm text-green-600 hidden">
            Copied to clipboard!
        </div>
    </div>
</div>

<script>
    function copyPaymentCode() {
        const paymentCode = document.getElementById('payment-code').innerText;
        navigator.clipboard.writeText(paymentCode);
        
        const copySuccess = document.getElementById('copy-success');
        copySuccess.classList.remove('hidden');
        
        setTimeout(() => {
            copySuccess.classList.add('hidden');
        }, 2000);
    }
</script> 