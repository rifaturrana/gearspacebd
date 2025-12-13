<!-- File: /Users/lvbandit/Downloads/New Folder With Items/gearspace/modules/Storefront/Resources/views/public/partials/whatsapp_button.blade.php -->
<div class="whatsapp-float">
    <a href="https://wa.me/+8801626435955" target="_blank" class="whatsapp-btn" title="Chat with us on WhatsApp">
        <i class="lab la-whatsapp"></i>
    </a>
</div>

<style>
.whatsapp-float {
    position: fixed;
    bottom: 55px;
    right: 12px;
    z-index: 100;
}

.whatsapp-float .whatsapp-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    background: #25D366;
    color: white;
    border-radius: 50%;
    text-align: center;
    font-size: 30px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    animation: jump 2s ease-in-out infinite;
    transition: all 0.3s ease;
}

.whatsapp-float .whatsapp-btn:hover {
    background: #128C7E;
    animation: none; /* Stop the animation on hover */
    transform: scale(1.1);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

.whatsapp-float .whatsapp-btn i {
    line-height: 1;
}

@keyframes jump {
    0% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
    100% {
        transform: translateY(0);
    }
}
</style>