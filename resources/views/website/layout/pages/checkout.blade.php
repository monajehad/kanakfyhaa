@extends('website.layout.main')

@section('title', 'إتمام الطلب - كأنك فيها')

@section('content')
<style>
    .checkout-section {
        background: var(--primary-white);
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    }

    [data-theme="dark"] .checkout-section {
        background: #1A1A1A;
        box-shadow: 0 2px 15px rgba(255,255,255,0.05);
    }

    .checkout-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--primary-black);
        margin-bottom: 30px;
        text-align: center;
    }

    .form-label {
        color: var(--primary-black);
        font-weight: 700;
        font-size: 0.95rem;
        margin-bottom: 8px;
        display: block;
    }

    [data-theme="dark"] .form-label {
        color: var(--primary-white);
    }

    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        background: var(--primary-white);
        color: var(--primary-black);
        font-size: 0.9rem;
        transition: all 0.3s;
    }

    [data-theme="dark"] .form-input {
        background: #0A0A0A;
        border-color: #333;
        color: var(--primary-white);
    }

    .form-input:focus {
        outline: none;
        border-color: var(--primary-yellow);
        box-shadow: 0 0 0 3px rgba(200, 212, 0, 0.1);
    }

    .form-input::placeholder {
        color: var(--gray-text);
    }

    .required::after {
        content: ' *';
        color: #DC2626;
    }

    .order-item {
        background: var(--gray-bg);
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 15px;
        border: 2px solid var(--border-color);
    }

    [data-theme="dark"] .order-item {
        background: #0A0A0A;
        border-color: #333;
    }

    .error-message {
        background: rgba(220, 38, 38, 0.1);
        border: 2px solid rgba(220, 38, 38, 0.3);
        color: #DC2626;
        padding: 10px 15px;
        border-radius: 8px;
        margin-top: 8px;
        display: none;
        font-size: 0.85rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid var(--border-color);
        color: var(--primary-black);
    }

    [data-theme="dark"] .summary-row {
        color: var(--primary-white);
        border-color: #333;
    }

    .summary-total {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--primary-black);
        border-top: 3px solid var(--primary-yellow);
        padding-top: 15px;
        margin-top: 15px;
    }

    [data-theme="dark"] .summary-total {
        color: var(--primary-white);
    }

    .payment-option {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        margin-bottom: 15px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .payment-option:hover {
        border-color: var(--primary-yellow);
        background: rgba(200, 212, 0, 0.05);
    }

    .payment-option input[type="radio"] {
        width: 20px;
        height: 20px;
        accent-color: var(--primary-yellow);
    }
</style>

<section class="container mx-auto px-4 py-12">
    <h1 class="checkout-title" data-ar="إتمام الطلب" data-en="Checkout">إتمام الطلب</h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Billing Form -->
        <div class="checkout-section">
            <h2 class="text-2xl font-bold mb-6" style="color: var(--primary-black);" data-ar="معلومات الشحن" data-en="Shipping Information">معلومات الشحن</h2>
            
            <form id="checkoutForm" class="space-y-5">
                <!-- Full Name -->
                <div>
                    <label class="form-label required" data-ar="الاسم الكامل" data-en="Full Name">الاسم الكامل</label>
                    <input type="text" id="fullName" class="form-input" placeholder="أدخل اسمك الكامل" required>
                    <div class="error-message" id="nameError">الرجاء إدخال الاسم الكامل</div>
                </div>

                <!-- Email -->
                <div>
                    <label class="form-label required" data-ar="البريد الإلكتروني" data-en="Email">البريد الإلكتروني</label>
                    <input type="email" id="email" class="form-input" placeholder="example@email.com" required>
                    <div class="error-message" id="emailError">الرجاء إدخال بريد إلكتروني صحيح</div>
                </div>

                <!-- Phone -->
                <div>
                    <label class="form-label required" data-ar="رقم الهاتف" data-en="Phone">رقم الهاتف</label>
                    <input type="tel" id="phone" class="form-input" placeholder="+970 599 123 456" required>
                    <div class="error-message" id="phoneError">الرجاء إدخال رقم هاتف صحيح</div>
                </div>

                <!-- Country -->
                <div>
                    <label class="form-label required" data-ar="الدولة" data-en="Country">الدولة</label>
                    <select id="country" class="form-input" required>
                        <option value="">اختر الدولة</option>
                        <option value="PS">فلسطين</option>
                        <option value="JO">الأردن</option>
                        <option value="SA">السعودية</option>
                        <option value="AE">الإمارات</option>
                        <option value="EG">مصر</option>
                        <option value="LB">لبنان</option>
                        <option value="SY">سوريا</option>
                        <option value="IQ">العراق</option>
                        <option value="KW">الكويت</option>
                        <option value="QA">قطر</option>
                        <option value="BH">البحرين</option>
                        <option value="OM">عمان</option>
                        <option value="YE">اليمن</option>
                        <option value="MA">المغرب</option>
                        <option value="DZ">الجزائر</option>
                        <option value="TN">تونس</option>
                        <option value="LY">ليبيا</option>
                        <option value="SD">السودان</option>
                        <option value="US">الولايات المتحدة</option>
                        <option value="GB">المملكة المتحدة</option>
                        <option value="FR">فرنسا</option>
                        <option value="DE">ألمانيا</option>
                        <option value="IT">إيطاليا</option>
                        <option value="ES">إسبانيا</option>
                        <option value="TR">تركيا</option>
                        <option value="CA">كندا</option>
                        <option value="AU">أستراليا</option>
                        <option value="NL">هولندا</option>
                        <option value="SE">السويد</option>
                        <option value="NO">النرويج</option>
                        <option value="DK">الدنمارك</option>
                        <option value="FI">فنلندا</option>
                        <option value="CH">سويسرا</option>
                        <option value="AT">النمسا</option>
                        <option value="BE">بلجيكا</option>
                        <option value="PL">بولندا</option>
                        <option value="CZ">التشيك</option>
                        <option value="HU">المجر</option>
                        <option value="RO">رومانيا</option>
                        <option value="BG">بلغاريا</option>
                        <option value="GR">اليونان</option>
                        <option value="PT">البرتغال</option>
                        <option value="IE">أيرلندا</option>
                        <option value="JP">اليابان</option>
                        <option value="KR">كوريا الجنوبية</option>
                        <option value="CN">الصين</option>
                        <option value="IN">الهند</option>
                        <option value="MY">ماليزيا</option>
                        <option value="SG">سنغافورة</option>
                        <option value="TH">تايلاند</option>
                        <option value="ID">إندونيسيا</option>
                        <option value="PH">الفلبين</option>
                        <option value="VN">فيتنام</option>
                        <option value="NZ">نيوزيلندا</option>
                        <option value="ZA">جنوب أفريقيا</option>
                        <option value="BR">البرازيل</option>
                        <option value="MX">المكسيك</option>
                        <option value="AR">الأرجنتين</option>
                        <option value="CL">تشيلي</option>
                        <option value="CO">كولومبيا</option>
                    </select>
                    <div class="error-message" id="countryError">الرجاء اختيار الدولة</div>
                </div>

                <!-- City -->
                <div>
                    <label class="form-label required" data-ar="المدينة" data-en="City">المدينة</label>
                    <input type="text" id="city" class="form-input" placeholder="أدخل المدينة" required>
                    <div class="error-message" id="cityError">الرجاء إدخال المدينة</div>
                </div>

                <!-- Address -->
                <div>
                    <label class="form-label required" data-ar="العنوان التفصيلي" data-en="Address">العنوان التفصيلي</label>
                    <textarea id="address" class="form-input" rows="3" placeholder="الشارع، رقم المبنى، تفاصيل إضافية..." required></textarea>
                    <div class="error-message" id="addressError">الرجاء إدخال العنوان التفصيلي</div>
                </div>

                <!-- Postal Code -->
                <div>
                    <label class="form-label" data-ar="الرمز البريدي (اختياري)" data-en="Postal Code (Optional)">الرمز البريدي (اختياري)</label>
                    <input type="text" id="postalCode" class="form-input" placeholder="12345">
                </div>

                <!-- Additional Notes -->
                <div>
                    <label class="form-label" data-ar="ملاحظات إضافية (اختياري)" data-en="Additional Notes (Optional)">ملاحظات إضافية (اختياري)</label>
                    <textarea id="notes" class="form-input" rows="2" placeholder="أي ملاحظات خاصة بالطلب..."></textarea>
                </div>
            </form>
        </div>

        <!-- Order Summary -->
        <div>
            <div class="checkout-section">
                <h2 class="text-2xl font-bold mb-6" style="color: var(--primary-black);" data-ar="ملخص الطلب" data-en="Order Summary">ملخص الطلب</h2>
                
                <div id="orderItems" class="mb-6">
                    <!-- Order items will be loaded here -->
                </div>

                <div class="space-y-3">
                    <div class="summary-row">
                        <span data-ar="المجموع الفرعي:" data-en="Subtotal:">المجموع الفرعي:</span>
                        <span id="subtotal" class="font-bold">$0.00</span>
                    </div>
                    <div class="summary-row">
                        <span data-ar="الشحن:" data-en="Shipping:">الشحن:</span>
                        <span id="shipping" class="font-bold">$10.00</span>
                    </div>
                    <div class="summary-row summary-total">
                        <span data-ar="الإجمالي:" data-en="Total:">الإجمالي:</span>
                        <span id="total">$0.00</span>
                    </div>
                </div>
            </div>

            <!-- Payment Section -->
            <div class="checkout-section">
                <h2 class="text-2xl font-bold mb-6" style="color: var(--primary-black);" data-ar="طريقة الدفع" data-en="Payment Method">طريقة الدفع</h2>
                
                <div class="space-y-4 mb-6">
                    <label class="payment-option">
                        <input type="radio" name="payment" value="paypal" checked class="w-5 h-5">
                        <span class="font-medium" style="color: var(--primary-black);" data-ar="PayPal" data-en="PayPal">PayPal</span>
                        <img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_37x23.jpg" alt="PayPal" class="h-6">
                    </label>
                </div>

                <!-- PayPal Button Container -->
                <div id="paypal-button-container" class="hidden mb-4"></div>

                <button type="button" id="placeOrderBtn" onclick="validateAndPay()" class="btn-yellow w-full py-4 text-lg">
                    <span data-ar="الدفع الآن" data-en="Pay Now">الدفع الآن</span>
                </button>

                <p class="text-center mt-4" style="color: var(--gray-text); font-size: 0.85rem;">
                    🔒 <span data-ar="جميع المعاملات آمنة ومشفرة" data-en="All transactions are secure and encrypted">جميع المعاملات آمنة ومشفرة</span>
                </p>
            </div>
        </div>
    </div>
</section>

<script src="https://www.paypal.com/sdk/js?client-id=YOUR_PAYPAL_CLIENT_ID&currency=USD"></script>
<script>
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let currency = JSON.parse(localStorage.getItem('currency')) || { symbol: '$', rate: 1 };
    const SHIPPING_COST = 10;
    let currentLang = localStorage.getItem('language') || 'ar';

    // Load Order Items
    function loadOrderItems() {
        const orderItemsContainer = document.getElementById('orderItems');
        
        if (cart.length === 0) {
            window.location.href = '/';
            return;
        }

        orderItemsContainer.innerHTML = cart.map(item => {
            const productName = item.name[currentLang] || item.name.ar || item.name;
            const cityName = item.cityName[currentLang] || item.cityName.ar || item.cityName;
            
            return `
                <div class="order-item">
                    <div class="flex gap-4">
                        <img src="${item.image}" alt="${productName}" class="w-20 h-20 rounded-lg object-cover">
                        <div class="flex-1">
                            <h4 class="font-bold" style="color: var(--primary-black);">${productName}</h4>
                            <p style="color: var(--gray-text); font-size: 0.85rem;">${cityName}</p>
                            <p style="color: var(--gray-text); font-size: 0.85rem;">
                                ${currentLang === 'ar' ? 'المقاس:' : 'Size:'} ${item.selectedSize} | 
                                ${currentLang === 'ar' ? 'الكمية:' : 'Quantity:'} ${item.quantity}
                            </p>
                        </div>
                        <div class="font-bold" style="color: var(--primary-black);">
                            ${currency.symbol}${(item.price * item.quantity * currency.rate).toFixed(2)}
                        </div>
                    </div>
                </div>
            `;
        }).join('');

        updateTotals();
    }

    // Update Totals
    function updateTotals() {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const shipping = SHIPPING_COST;
        const total = subtotal + shipping;

        document.getElementById('subtotal').textContent = 
            `${currency.symbol}${(subtotal * currency.rate).toFixed(2)}`;
        document.getElementById('shipping').textContent = 
            `${currency.symbol}${(shipping * currency.rate).toFixed(2)}`;
        document.getElementById('total').textContent = 
            `${currency.symbol}${(total * currency.rate).toFixed(2)}`;
    }

    // Validate Form
    function validateForm() {
        let isValid = true;
        const fields = [
            { id: 'fullName', error: 'nameError', message: currentLang === 'ar' ? 'الرجاء إدخال الاسم الكامل' : 'Please enter your full name' },
            { id: 'email', error: 'emailError', message: currentLang === 'ar' ? 'الرجاء إدخال بريد إلكتروني صحيح' : 'Please enter a valid email' },
            { id: 'phone', error: 'phoneError', message: currentLang === 'ar' ? 'الرجاء إدخال رقم هاتف صحيح' : 'Please enter a valid phone number' },
            { id: 'country', error: 'countryError', message: currentLang === 'ar' ? 'الرجاء اختيار الدولة' : 'Please select a country' },
            { id: 'city', error: 'cityError', message: currentLang === 'ar' ? 'الرجاء إدخال المدينة' : 'Please enter the city' },
            { id: 'address', error: 'addressError', message: currentLang === 'ar' ? 'الرجاء إدخال العنوان التفصيلي' : 'Please enter the address' }
        ];

        fields.forEach(field => {
            const input = document.getElementById(field.id);
            const errorDiv = document.getElementById(field.error);
            
            if (!input.value.trim()) {
                errorDiv.style.display = 'block';
                errorDiv.textContent = field.message;
                isValid = false;
            } else {
                errorDiv.style.display = 'none';
            }
        });

        // Email validation
        const emailInput = document.getElementById('email');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (emailInput.value && !emailRegex.test(emailInput.value)) {
            document.getElementById('emailError').style.display = 'block';
            document.getElementById('emailError').textContent = currentLang === 'ar' ? 'البريد الإلكتروني غير صحيح' : 'Invalid email address';
            isValid = false;
        }

        return isValid;
    }

    // Validate and Show PayPal
    function validateAndPay() {
        if (!validateForm()) {
            alert(currentLang === 'ar' ? 'الرجاء تعبئة جميع الحقول المطلوبة' : 'Please fill all required fields');
            return;
        }

        // Show PayPal button
        document.getElementById('paypal-button-container').classList.remove('hidden');
        document.getElementById('placeOrderBtn').disabled = true;
        document.getElementById('placeOrderBtn').innerHTML = currentLang === 'ar' ? '<span>جاري تحميل PayPal...</span>' : '<span>Loading PayPal...</span>';

        // Initialize PayPal
        initializePayPal();
    }

    // Initialize PayPal
    function initializePayPal() {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const total = (subtotal + SHIPPING_COST).toFixed(2);

        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: total,
                            currency_code: 'USD'
                        },
                        description: currentLang === 'ar' ? 'طلب من متجر الهوديهات' : 'Order from Hoodies Store'
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    // Collect order data
                    const orderData = {
                        orderId: 'ORD-' + Date.now(),
                        customerName: document.getElementById('fullName').value,
                        email: document.getElementById('email').value,
                        phone: document.getElementById('phone').value,
                        country: document.getElementById('country').value,
                        city: document.getElementById('city').value,
                        address: document.getElementById('address').value,
                        postalCode: document.getElementById('postalCode').value,
                        notes: document.getElementById('notes').value,
                        items: cart,
                        subtotal: subtotal,
                        shipping: SHIPPING_COST,
                        total: parseFloat(total),
                        currency: currency,
                        paymentMethod: 'PayPal',
                        paymentStatus: 'Paid',
                        orderStatus: 'Processing',
                        transactionId: details.id,
                        payerEmail: details.payer.email_address,
                        orderDate: new Date().toISOString()
                    };

                    // Save order to localStorage (in real app, send to server)
                    let orders = JSON.parse(localStorage.getItem('orders')) || [];
                    orders.push(orderData);
                    localStorage.setItem('orders', JSON.stringify(orders));

                    // Clear cart
                    localStorage.removeItem('cart');

                    // Redirect to success page
                    window.location.href = '/order-success?orderId=' + orderData.orderId;
                });
            },
            onError: function(err) {
                console.error('PayPal Error:', err);
                alert(currentLang === 'ar' ? 'حدث خطأ في عملية الدفع. الرجاء المحاولة مرة أخرى.' : 'Payment error occurred. Please try again.');
                document.getElementById('placeOrderBtn').disabled = false;
                document.getElementById('placeOrderBtn').innerHTML = currentLang === 'ar' ? '<span>الدفع الآن</span>' : '<span>Pay Now</span>';
            }
        }).render('#paypal-button-container');
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        loadOrderItems();
    });
</script>
@endsection

