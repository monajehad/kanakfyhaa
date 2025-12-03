@if($enableWhatsappFab && !empty($whatsappNumber))
<a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsappNumber) }}" 
   class="whatsapp-fab" 
   target="_blank" 
   rel="noopener noreferrer"
   title="{{ __('Contact us on WhatsApp') }}">
  <i class="ri-whatsapp-line"></i>
</a>

<style>
.whatsapp-fab {
  position: fixed;
  bottom: 30px;
  left: 30px;
  width: 60px;
  height: 60px;
  background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 32px;
  box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
  z-index: 1000;
  transition: all 0.3s ease;
  text-decoration: none;
  animation: whatsapp-pulse 2s ease-in-out infinite;
}

.whatsapp-fab:hover {
  transform: scale(1.1) translateY(-3px);
  box-shadow: 0 6px 25px rgba(37, 211, 102, 0.5);
  color: white;
}

.whatsapp-fab i {
  line-height: 1;
}

@keyframes whatsapp-pulse {
  0%, 100% {
    box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
  }
  50% {
    box-shadow: 0 4px 30px rgba(37, 211, 102, 0.6);
  }
}

/* RTL Support */
[dir="rtl"] .whatsapp-fab {
  left: auto;
  right: 30px;
}

/* Responsive */
@media (max-width: 768px) {
  .whatsapp-fab {
    width: 50px;
    height: 50px;
    font-size: 26px;
    bottom: 20px;
    left: 20px;
  }
  
  [dir="rtl"] .whatsapp-fab {
    left: auto;
    right: 20px;
  }
}

/* Dark mode support */
[data-bs-theme="dark"] .whatsapp-fab {
  background: linear-gradient(135deg, #1ebe59 0%, #0e7a69 100%);
}
</style>
@endif
