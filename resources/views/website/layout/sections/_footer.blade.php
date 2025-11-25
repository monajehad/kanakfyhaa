<!-- Footer -->
<footer class="mt-16 pt-12 pb-8" style="background: var(--md-surface); border-top: 1px solid var(--md-surface-variant); color: var(--md-on-surface);">
    <div class="container mx-auto px-4">
        <!-- Footer Content -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            <!-- About Section -->
            <div>
                <h3 class="md-headline-small mb-3" style="color: var(--md-on-surface)">
                    {{ app()->getLocale() === 'ar' ? 'عن كأنك فيها' : 'About Kanak Feeha' }}
                </h3>
                <p class="md-body-small text-md-on-surface-variant">
                    {{ app()->getLocale() === 'ar' 
                        ? 'منصة فريدة تجمع بين التراث الفلسطيني والحداثة.' 
                        : 'A unique platform combining Palestinian heritage with modernity.' }}
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="md-headline-small mb-3" style="color: var(--md-on-surface)">
                    {{ app()->getLocale() === 'ar' ? 'روابط سريعة' : 'Quick Links' }}
                </h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('pages-home') }}" class="md-body-small text-md-on-surface-variant hover:text-md-primary transition">{{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Home' }}</a></li>
                    <li><a href="#" class="md-body-small text-md-on-surface-variant hover:text-md-primary transition">{{ app()->getLocale() === 'ar' ? 'من نحن' : 'About Us' }}</a></li>
                    <li><a href="#" class="md-body-small text-md-on-surface-variant hover:text-md-primary transition">{{ app()->getLocale() === 'ar' ? 'الشروط' : 'Terms' }}</a></li>
                </ul>
            </div>

            <!-- Contact Section -->
            <div>
                <h3 class="md-headline-small mb-3" style="color: var(--md-on-surface)">
                    {{ app()->getLocale() === 'ar' ? 'تواصل معنا' : 'Contact Us' }}
                </h3>
                <div class="flex gap-3">
                    <a href="#" class="inline-flex items-center justify-center w-10 h-10 rounded-full hover:bg-black/10 dark:hover:bg-white/10 transition">
                        <span class="material-icons-outlined icon-md" style="color: var(--md-on-surface)">facebook</span>
                    </a>
                    <a href="#" class="inline-flex items-center justify-center w-10 h-10 rounded-full hover:bg-black/10 dark:hover:bg-white/10 transition">
                        <span class="material-icons-outlined icon-md" style="color: var(--md-on-surface)">language</span>
                    </a>
                    <a href="#" class="inline-flex items-center justify-center w-10 h-10 rounded-full hover:bg-black/10 dark:hover:bg-white/10 transition">
                        <span class="material-icons-outlined icon-md" style="color: var(--md-on-surface)">mail</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t" style="border-color: var(--md-surface-variant); margin-bottom: 16px;"></div>

        <!-- Copyright -->
        <div class="text-center">
            <p class="md-body-small text-md-on-surface-variant" data-ar="© 2024 كأنك فيها. جميع الحقوق محفوظة." data-en="© 2024 Kanak Feeha. All rights reserved.">
                {{ app()->getLocale() === 'ar' 
                    ? '© 2024 كأنك فيها. جميع الحقوق محفوظة.' 
                    : '© 2024 Kanak Feeha. All rights reserved.' }}
            </p>
        </div>
    </div>
</footer>

