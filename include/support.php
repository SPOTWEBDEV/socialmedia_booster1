<script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.2.0/js/all.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.2.0/css/fontawesome.min.css">
<!-- FLOATING SUPPORT -->
<div class="float-support">

    <a href="https://wa.me/2348012345678" target="_blank" class="support-btn whatsapp">
        <i class="fab fa-whatsapp"></i>
        <span class="support-label">WhatsApp</span>
    </a>

    <a href="mailto:support@boostkore.com" class="support-btn email">
        <i class="fas fa-envelope"></i>
        <span class="support-label">Email Support</span>
    </a>

    <a href="https://t.me/yourusername" target="_blank" class="support-btn telegram">
        <i class="fab fa-telegram-plane"></i>
        <span class="support-label">Telegram</span>
    </a>

</div>

<style>
    /* FLOAT SUPPORT WRAPPER */
.float-support {
    position: fixed;
    right: 20px;
    bottom: 40px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    z-index: 9999;
}

/* EACH BUTTON */
.support-btn {
    display: flex;
    align-items: center;
    height: 55px;
    width: 55px;
    border-radius: 50px;
    color: #fff !important;
    font-size: 22px;
    text-decoration: none;
    transition: all 0.3s ease;
    overflow: hidden;
    padding: 0 16px;
}

/* ICON */
.support-btn i {
    color: #fff !important;
    min-width: 24px;
    text-align: center;
}

/* LABEL */
.support-label {
    margin-left: 12px;
    font-size: 14px;
    white-space: nowrap;
    opacity: 0;
    transition: opacity 0.3s ease;
    color: #fff;
}

/* HOVER — ONLY TARGETED BUTTON EXPANDS */
.support-btn:hover {
    width: 180px;
}

.support-btn:hover .support-label {
    opacity: 1;
}

/* COLORS */
.whatsapp {
    background: #25D366;
}

.email {
    background: #007bff;
}

.telegram {
    background: #0088cc;
}

/* MOBILE FIX */
@media (max-width: 768px) {
    .support-btn {
        width: 55px !important;
    }

    .support-label {
        display: none;
    }
}
</style>