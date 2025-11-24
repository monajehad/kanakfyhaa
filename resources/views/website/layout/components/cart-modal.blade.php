<div id="cartModal" class="modal" role="dialog" aria-modal="true" onclick="if(event.target.id==='cartModal'){ closeCart(); }" >
    <div class="modal-content animate-in" >
        <div class="p-6 max-h-[80vh] overflow-y-auto" style="padding: 1.5rem 1.5rem 0 1.5rem;">
            <div class="flex justify-between items-center mb-6" >
                <div>
                    <h3 class="text-2xl font-bold" data-ar="سلة التسوق" data-en="Shopping Cart">سلة التسوق</h3>
                    <p class="text-sm mt-1" style="color: var(--gray-text)">
                        <span data-ar="عدد العناصر:" data-en="Items:">عدد العناصر:</span>
                        <span id="cartItemsCount">0</span>
                    </p>
                </div>
                <div class="flex gap-2">
                    <button onclick="clearCart()" class="text-sm px-3 py-2 rounded" style="background: var(--gray-bg)">
                        <span data-ar="تفريغ السلة" data-en="Clear cart">تفريغ السلة</span>
                    </button>
                    <button onclick="closeCart()" class="rounded p-2" style="color: var(--gray-text); background: var(--gray-bg);" aria-label="Close">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div id="cartItems" class="space-y-4 mb-6">
                <!-- Cart items will be loaded here dynamically -->
            </div>
            
            <div class="border-t pt-6 sticky bottom-0" style="background-color: var(--border-color); border-color: var(--border-color); box-shadow: 0 -2px 10px rgba(0,0,0,0.1); padding: 1.5rem; margin-top: 1rem;">
                <div class="flex justify-between items-center mb-4 text-xl font-bold">
                    <span data-ar="الإجمالي:" data-en="Total:">الإجمالي:</span>
                    <span id="cartTotal">$0.00</span>
                </div>
                <button id="checkoutBtn" onclick="proceedToCheckout()" class="w-full py-4 text-lg disabled:opacity-60 disabled:cursor-not-allowed" style="background: var(--primary-yellow); color: white; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer;"
                        data-ar="إتمام الشراء" data-en="Checkout">
                    إتمام الشراء
                </button>
                <p class="text-xs mt-3 text-center" style="color: var(--gray-text); background: var(--gray-bg); padding: 0.75rem; border-radius: 0.375rem;">
                    <span data-ar="سيتم حساب الشحن عند الدفع" data-en="Shipping calculated at checkout">سيتم حساب الشحن عند الدفع</span>
                </p>
            </div>
        </div>
    </div>
</div>

