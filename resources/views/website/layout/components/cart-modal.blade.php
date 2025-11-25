<div id="cartModal" class="modal" role="dialog" aria-modal="true" onclick="if(event.target.id==='cartModal'){ closeCart(); }" >
    <div class="modal-content animate-in md-card elevation-5" style="background: var(--md-surface); border-radius: 28px; max-height: 90vh; overflow: hidden; display: flex; flex-direction: column;">
        <!-- Header -->
        <div class="p-6 border-b" style="border-color: var(--md-surface-variant);">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <h2 class="md-headline-medium" data-ar="سلة التسوق" data-en="Shopping Cart">سلة التسوق</h2>
                    <p class="md-body-small text-md-on-surface-variant mt-1">
                        <span data-ar="عدد العناصر:" data-en="Items:">عدد العناصر:</span>
                        <span id="cartItemsCount" class="font-semibold">0</span>
                    </p>
                </div>
                <button onclick="closeCart()" class="inline-flex items-center justify-center p-2 rounded-full hover:bg-black/5 dark:hover:bg-white/10 transition" style="color: var(--md-on-surface);" aria-label="Close">
                    <span class="material-icons-outlined icon-md">close</span>
                </button>
            </div>
        </div>

        <!-- Cart Items -->
        <div id="cartItems" class="flex-1 overflow-y-auto p-6 space-y-4">
            <!-- Cart items will be loaded here dynamically -->
        </div>

        <!-- Footer/Checkout Section -->
        <div class="border-t p-6 space-y-4" style="border-color: var(--md-surface-variant); background: var(--md-surface-variant);">
            <!-- Total -->
            <div class="flex justify-between items-center">
                <span class="md-headline-small" data-ar="الإجمالي:" data-en="Total:">الإجمالي:</span>
                <span id="cartTotal" class="md-headline-medium" style="color: var(--md-primary);">$0.00</span>
            </div>

            <!-- Clear Cart Button -->
            <button onclick="clearCart()" class="w-full btn-md btn-outlined" style="color: var(--md-error); border-color: var(--md-error); background: transparent;">
                <span class="material-icons-outlined icon-sm">delete_outline</span>
                <span data-ar="تفريغ السلة" data-en="Clear cart">تفريغ السلة</span>
            </button>

            <!-- Checkout Button -->
            <button id="checkoutBtn" onclick="proceedToCheckout()" class="w-full btn-md btn-filled" style="background: var(--md-primary); color: var(--md-on-primary);" data-ar="إتمام الشراء" data-en="Checkout">
                <span class="material-icons-outlined icon-sm">payment</span>
                <span data-ar="إتمام الشراء" data-en="Checkout">إتمام الشراء</span>
            </button>

            <!-- Info Message -->
            <p class="md-label-small text-center text-md-on-surface-variant px-4 py-3 rounded-lg" style="background: var(--md-surface);">
                <span class="flex items-center justify-center gap-2">
                    <span class="material-icons-outlined icon-sm">info</span>
                    <span data-ar="سيتم حساب الشحن عند الدفع" data-en="Shipping calculated at checkout">سيتم حساب الشحن عند الدفع</span>
                </span>
            </p>
        </div>
    </div>
</div>

