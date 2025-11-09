<div id="cartModal" class="modal">
    <div class="modal-content">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold" data-ar="سلة التسوق" data-en="Shopping Cart">سلة التسوق</h3>
                <button onclick="closeCart()" class="text-gray-500 hover:text-black">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div id="cartItems" class="space-y-4 mb-6">
                <!-- Cart items will be loaded here dynamically -->
            </div>
            
            <div class="border-t pt-6" style="border-color: var(--border-color)">
                <div class="flex justify-between items-center mb-6 text-xl font-bold">
                    <span data-ar="الإجمالي:" data-en="Total:">الإجمالي:</span>
                    <span id="cartTotal">$0.00</span>
                </div>
                <button onclick="proceedToCheckout()" class="btn-yellow w-full py-4 text-lg"
                        data-ar="إتمام الشراء" data-en="Checkout">
                    إتمام الشراء
                </button>
            </div>
        </div>
    </div>
</div>

