<style>
    .whatsapp-float {
        position: fixed;
        right: 22px;
        bottom: 22px;
        z-index: 999;
        width: 58px; height: 58px;
        border-radius: 50%;
        background: #25d366;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        box-shadow: 0 8px 20px rgba(37, 211, 102, .45);
        transition: transform .2s, box-shadow .2s;
    }
    .whatsapp-float:hover { transform: scale(1.08); box-shadow: 0 12px 26px rgba(37, 211, 102, .55); color: #fff; }
</style>
<a href="https://wa.me/51916377263?text=demo%2Fprueba"
   class="whatsapp-float" target="_blank" rel="noopener" aria-label="Escríbenos por WhatsApp">
    <i class="bi bi-whatsapp"></i>
</a>