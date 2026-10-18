<!-- Welkar Horological Customer Support & Ticket Helpdesk Widget -->
<div id="welkar-support-container">
    <!-- Floating Concierge Action Button Stack (replaces WhatsApp) -->
    <div class="welkar-floating-actions position-fixed end-0 me-3 me-md-4 d-flex flex-column gap-2 align-items-center" style="bottom: 74px; z-index: 1040;">
        <!-- Back to Top Button -->
        <button type="button" id="welkar-back-to-top" class="btn btn-dark shadow-lg rounded-circle border-0 d-none align-items-center justify-content-center" style="width: 46px; height: 46px; background: #1A1A1C; border: 1.5px solid #2E2E2E !important; color: #F5F5F5; box-shadow: 0 6px 18px rgba(0,0,0,0.4) !important; transition: opacity 0.3s ease, transform 0.3s ease;" aria-label="Back to top" title="Back to top">
            <i class="bi bi-arrow-up"></i>
        </button>

        <!-- Customer Support Concierge Button -->
        <button type="button" id="welkar-support-toggle" class="btn btn-dark shadow-lg rounded-circle border-0 d-flex align-items-center justify-content-center position-relative" style="width: 52px; height: 52px; background: linear-gradient(135deg, #1A1A1C 0%, #292F2B 100%); border: 1.5px solid #DFB75C !important; box-shadow: 0 8px 24px rgba(0,0,0,0.35) !important;" aria-label="Open Welkar Concierge & Customer Support" title="Welkar Concierge & Support">
            <i class="bi bi-headset fs-4" style="color: #DFB75C;"></i>
            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle" style="width: 12px; height: 12px;" title="Support Team Online">
                <span class="visually-hidden">Online</span>
            </span>
        </button>
    </div>

    <!-- Concierge Slide-Up / Popup Modal -->
    <div id="welkar-support-modal" class="position-fixed shadow-lg d-none flex-column" style="bottom: 84px; right: 20px; width: 380px; max-width: calc(100vw - 32px); height: 560px; max-height: calc(100vh - 120px); z-index: 1050; border-radius: 16px; overflow: hidden; background: #FFFFFF; border: 1px solid #DDD9D0; font-family: var(--font-ui, 'Inter', -apple-system, sans-serif); animation: welkarSupportSlideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);">
        <!-- Widget Header -->
        <div class="p-3 text-white d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #121314 0%, #202522 100%); border-bottom: 2px solid #C59B3F;">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: rgba(223, 183, 92, 0.15); border: 1px solid #DFB75C;">
                    <i class="bi bi-shield-check" style="color: #DFB75C; font-size: 18px;"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold" style="font-family: var(--font-display, 'DM Serif Display', Georgia, serif); letter-spacing: 0.5px; font-size: 15px; color: #F5F5F5;">WELKAR Concierge</h6>
                    <div class="d-flex align-items-center gap-1" style="font-size: 11px; color: #A0A0A0;">
                        <span class="d-inline-block rounded-circle bg-success" style="width: 7px; height: 7px;"></span>
                        <span>Helpdesk &amp; Ticket Resolution</span>
                    </div>
                </div>
            </div>
            <button type="button" id="welkar-support-close" class="btn-close btn-close-white" aria-label="Close"></button>
        </div>

        <!-- Widget Body -->
        <div id="welkar-support-body" class="p-3 flex-grow-1 overflow-y-auto" style="background: #FAFAFA;">
            <!-- View 1: Category Quick Selector & Ticket Creation -->
            <div id="support-view-create">
                <div class="mb-3 text-center">
                    <p class="small text-muted mb-2" style="font-size: 12px; line-height: 1.4;">
                        Select an inquiry topic below. Your request will be securely tracked in our Admin Portal until resolved.
                    </p>
                </div>

                <!-- Quick Category Selector Buttons -->
                <div class="d-flex flex-column gap-2 mb-3">
                    <button type="button" class="btn btn-outline-dark text-start p-2 d-flex align-items-center gap-2 support-cat-btn" data-category="return_exchange" data-title="Return or Replacement Request">
                        <i class="bi bi-arrow-repeat text-warning fs-5"></i>
                        <div>
                            <div class="fw-bold small" style="font-size: 12.5px;">Return / Exchange Request</div>
                            <div class="text-muted" style="font-size: 11px;">7-Day hassle-free return or replacement</div>
                        </div>
                    </button>

                    <button type="button" class="btn btn-outline-dark text-start p-2 d-flex align-items-center gap-2 support-cat-btn" data-category="order_status" data-title="Order Tracking & Status">
                        <i class="bi bi-truck text-primary fs-5"></i>
                        <div>
                            <div class="fw-bold small" style="font-size: 12.5px;">Order Status &amp; Dispatch</div>
                            <div class="text-muted" style="font-size: 11px;">Track AWB shipment, dispatch timing</div>
                        </div>
                    </button>

                    <button type="button" class="btn btn-outline-dark text-start p-2 d-flex align-items-center gap-2 support-cat-btn" data-category="payment_issue" data-title="Payment or Refund Issue">
                        <i class="bi bi-credit-card-2-front text-danger fs-5"></i>
                        <div>
                            <div class="fw-bold small" style="font-size: 12.5px;">Payment / Refund Issue</div>
                            <div class="text-muted" style="font-size: 11px;">UPI deduction, card verification, refunds</div>
                        </div>
                    </button>

                    <button type="button" class="btn btn-outline-dark text-start p-2 d-flex align-items-center gap-2 support-cat-btn" data-category="product_find" data-title="Watch Finder & Horological Advice">
                        <i class="bi bi-watch text-success fs-5"></i>
                        <div>
                            <div class="fw-bold small" style="font-size: 12.5px;">Find a Watch / Dial Styling</div>
                            <div class="text-muted" style="font-size: 11px;">Dial style, wrist fit &amp; availability</div>
                        </div>
                    </button>

                    <button type="button" class="btn btn-outline-dark text-start p-2 d-flex align-items-center gap-2 support-cat-btn" data-category="general" data-title="General Support Inquiry">
                        <i class="bi bi-chat-text text-secondary fs-5"></i>
                        <div>
                            <div class="fw-bold small" style="font-size: 12.5px;">General Support &amp; Chat</div>
                            <div class="text-muted" style="font-size: 11px;">Speak with Welkar Care Specialist</div>
                        </div>
                    </button>
                </div>

                <!-- Form Section (Shown once category is clicked or default) -->
                <form id="support-ticket-form" class="card p-3 border shadow-none bg-white" style="border-radius: 10px;">
                    <input type="hidden" name="category" id="ticket-category" value="general">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span id="category-badge" class="badge bg-dark text-warning small px-2 py-1">General Support</span>
                        <a href="javascript:void(0)" id="btn-show-existing" class="small text-decoration-none" style="font-size: 11px; color: #856744;">Existing Ticket?</a>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-semibold mb-1" style="font-size: 11px;">Full Name *</label>
                        <input type="text" name="customer_name" id="ticket-name" class="form-control form-control-sm" required placeholder="Your name" value="<?= e(auth_user()['full_name'] ?? '') ?>">
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-7">
                            <label class="form-label small fw-semibold mb-1" style="font-size: 11px;">Phone (10 Digits) *</label>
                            <input type="tel" name="customer_phone" id="ticket-phone" class="form-control form-control-sm" required pattern="[0-9]{10}" maxlength="10" placeholder="9876543210" value="<?= e(auth_user()['phone'] ?? '') ?>">
                        </div>
                        <div class="col-5">
                            <label class="form-label small fw-semibold mb-1" style="font-size: 11px;">Order # (If any)</label>
                            <input type="text" name="order_number" id="ticket-order-no" class="form-control form-control-sm" placeholder="WEL-2026-...">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-semibold mb-1" style="font-size: 11px;">Email (For Updates)</label>
                        <input type="email" name="customer_email" id="ticket-email" class="form-control form-control-sm" placeholder="you@example.com" value="<?= e(auth_user()['email'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold mb-1" style="font-size: 11px;">How can we help you? *</label>
                        <textarea name="message" id="ticket-message" class="form-control form-control-sm" rows="3" required placeholder="Describe your request or question in detail..."></textarea>
                    </div>

                    <button type="submit" id="btn-submit-ticket" class="btn btn-dark w-100 btn-sm fw-bold py-2" style="background: #202522; border-color: #202522;">
                        <i class="bi bi-send-fill me-1 text-warning"></i> Submit Support Ticket
                    </button>
                </form>
            </div>

            <!-- View 2: Live Chat & Ticket Thread -->
            <div id="support-view-chat" class="d-none d-flex flex-column h-100">
                <div class="p-2 border rounded bg-white mb-2 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold small text-dark" id="chat-ticket-number">TKT-XXXX</div>
                        <div class="text-muted" style="font-size: 10.5px;" id="chat-ticket-subject">Inquiry</div>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <span class="badge bg-secondary" id="chat-ticket-status">Open</span>
                        <button type="button" id="btn-new-ticket" class="btn btn-sm btn-outline-secondary py-0 px-1" style="font-size: 10px;" title="New Request">New</button>
                    </div>
                </div>

                <!-- Chat Messages Scroll Container -->
                <div id="chat-messages-container" class="flex-grow-1 overflow-y-auto d-flex flex-column gap-2 mb-2 p-2 bg-light rounded" style="min-height: 260px; max-height: 290px;">
                    <!-- Messages will be populated dynamically -->
                </div>

                <!-- Send Reply Box -->
                <form id="chat-reply-form" class="mt-auto d-flex gap-2">
                    <input type="text" id="chat-reply-input" class="form-control form-control-sm" placeholder="Type your reply here..." required autocomplete="off">
                    <button type="submit" class="btn btn-dark btn-sm px-3" id="btn-send-reply">
                        <i class="bi bi-send-fill text-warning"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes welkarSupportSlideUp {
    from { opacity: 0; transform: translateY(20px) scale(0.97); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}
.support-cat-btn {
    border-color: #E2E8F0;
    transition: all 0.15s ease;
}
.support-cat-btn:hover {
    background-color: #F8F9FA;
    border-color: #DFB75C;
    transform: translateX(2px);
}
.chat-bubble-customer {
    background-color: #202522;
    color: #FFFFFF;
    border-radius: 12px 12px 2px 12px;
    padding: 8px 12px;
    max-width: 82%;
    align-self: flex-end;
    font-size: 12px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}
.chat-bubble-admin {
    background-color: #FFFFFF;
    color: #202522;
    border: 1px solid #E2E8F0;
    border-radius: 12px 12px 12px 2px;
    padding: 8px 12px;
    max-width: 85%;
    align-self: flex-start;
    font-size: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
.chat-bubble-system {
    background-color: #EBF8FF;
    color: #2B6CB0;
    border: 1px solid #BEE3F8;
    border-radius: 8px;
    padding: 6px 10px;
    max-width: 95%;
    align-self: center;
    font-size: 11px;
    text-align: center;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('welkar-support-toggle');
    const closeBtn = document.getElementById('welkar-support-close');
    const modal = document.getElementById('welkar-support-modal');
    const catBtns = document.querySelectorAll('.support-cat-btn');
    const catInput = document.getElementById('ticket-category');
    const catBadge = document.getElementById('category-badge');
    const ticketForm = document.getElementById('support-ticket-form');
    const viewCreate = document.getElementById('support-view-create');
    const viewChat = document.getElementById('support-view-chat');
    const messagesContainer = document.getElementById('chat-messages-container');
    const replyForm = document.getElementById('chat-reply-form');
    const replyInput = document.getElementById('chat-reply-input');
    const btnNewTicket = document.getElementById('btn-new-ticket');
    const btnShowExisting = document.getElementById('btn-show-existing');

    let activeTicketNumber = localStorage.getItem('welkar_active_ticket_no') || null;
    let pollInterval = null;

    if (toggleBtn && modal) {
        toggleBtn.addEventListener('click', () => {
            modal.classList.toggle('d-none');
            modal.classList.toggle('d-flex');
            if (modal.classList.contains('d-flex')) {
                if (activeTicketNumber) {
                    loadTicketThread(activeTicketNumber);
                }
            }
        });
    }

    if (closeBtn && modal) {
        closeBtn.addEventListener('click', () => {
            modal.classList.add('d-none');
            modal.classList.remove('d-flex');
            clearInterval(pollInterval);
        });
    }

    catBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const cat = this.dataset.category;
            const title = this.dataset.title;
            catInput.value = cat;
            catBadge.textContent = title;
            document.getElementById('ticket-message').focus();
        });
    });

    if (btnShowExisting) {
        btnShowExisting.addEventListener('click', () => {
            const ticketNo = prompt("Please enter your Ticket Number (e.g. TKT-2026-XXXX):");
            if (ticketNo && ticketNo.trim()) {
                loadTicketThread(ticketNo.trim());
            }
        });
    }

    if (btnNewTicket) {
        btnNewTicket.addEventListener('click', () => {
            activeTicketNumber = null;
            localStorage.removeItem('welkar_active_ticket_no');
            clearInterval(pollInterval);
            viewChat.classList.add('d-none');
            viewCreate.classList.remove('d-none');
        });
    }

    if (ticketForm) {
        ticketForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = document.getElementById('btn-submit-ticket');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Creating Ticket...';

            const payload = {
                category: catInput.value,
                customer_name: document.getElementById('ticket-name').value.trim(),
                customer_phone: document.getElementById('ticket-phone').value.trim(),
                customer_email: document.getElementById('ticket-email').value.trim(),
                order_number: document.getElementById('ticket-order-no').value.trim(),
                message: document.getElementById('ticket-message').value.trim(),
                csrf_token: '<?= csrf_token() ?>'
            };

            fetch('/api/support/ticket', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': '<?= csrf_token() ?>'
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-send-fill me-1 text-warning"></i> Submit Support Ticket';
                if (data.success && data.ticket) {
                    activeTicketNumber = data.ticket.ticket_number;
                    localStorage.setItem('welkar_active_ticket_no', activeTicketNumber);
                    loadTicketThread(activeTicketNumber);
                } else {
                    alert(data.message || 'Error creating ticket.');
                }
            })
            .catch(err => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-send-fill me-1 text-warning"></i> Submit Support Ticket';
                alert('Connection error. Please try again.');
            });
        });
    }

    function loadTicketThread(ticketNo) {
        fetch('/api/support/ticket/' + encodeURIComponent(ticketNo))
            .then(res => res.json())
            .then(data => {
                if (data.success && data.ticket) {
                    activeTicketNumber = data.ticket.ticket_number;
                    localStorage.setItem('welkar_active_ticket_no', activeTicketNumber);

                    document.getElementById('chat-ticket-number').textContent = data.ticket.ticket_number;
                    document.getElementById('chat-ticket-subject').textContent = data.ticket.subject + (data.ticket.order_number ? ' (' + data.ticket.order_number + ')' : '');
                    
                    const statusBadge = document.getElementById('chat-ticket-status');
                    statusBadge.textContent = data.ticket.status.toUpperCase();
                    if (data.ticket.status === 'resolved' || data.ticket.status === 'closed') {
                        statusBadge.className = 'badge bg-success';
                    } else if (data.ticket.status === 'in_progress') {
                        statusBadge.className = 'badge bg-warning text-dark';
                    } else {
                        statusBadge.className = 'badge bg-info text-dark';
                    }

                    renderMessages(data.messages || []);
                    viewCreate.classList.add('d-none');
                    viewChat.classList.remove('d-none');

                    clearInterval(pollInterval);
                    pollInterval = setInterval(() => {
                        if (activeTicketNumber && modal.classList.contains('d-flex')) {
                            fetch('/api/support/ticket/' + encodeURIComponent(activeTicketNumber))
                                .then(r => r.json())
                                .then(d => {
                                    if (d.success && d.messages) {
                                        renderMessages(d.messages);
                                    }
                                });
                        }
                    }, 5000);
                }
            })
            .catch(err => console.error(err));
    }

    function renderMessages(messages) {
        messagesContainer.innerHTML = '';
        messages.forEach(m => {
            const div = document.createElement('div');
            if (m.sender_type === 'customer') {
                div.className = 'chat-bubble-customer';
                div.innerHTML = `<div>${escapeHtml(m.message)}</div><div class="text-white-50 text-end" style="font-size: 9.5px; margin-top: 2px;">${m.time || ''}</div>`;
            } else if (m.sender_type === 'admin') {
                div.className = 'chat-bubble-admin';
                div.innerHTML = `<div class="fw-bold text-dark" style="font-size: 10.5px; color: #856744 !important;"><i class="bi bi-shield-check me-1"></i>${escapeHtml(m.sender_name)}</div><div>${escapeHtml(m.message)}</div><div class="text-muted text-end" style="font-size: 9.5px; margin-top: 2px;">${m.time || ''}</div>`;
            } else {
                div.className = 'chat-bubble-system';
                div.innerHTML = `<div>${escapeHtml(m.message)}</div>`;
            }
            messagesContainer.appendChild(div);
        });
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    if (replyForm) {
        replyForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const text = replyInput.value.trim();
            if (!text || !activeTicketNumber) return;

            const sendBtn = document.getElementById('btn-send-reply');
            sendBtn.disabled = true;

            fetch('/api/support/ticket/' + encodeURIComponent(activeTicketNumber) + '/message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': '<?= csrf_token() ?>'
                },
                body: JSON.stringify({ message: text })
            })
            .then(res => res.json())
            .then(data => {
                sendBtn.disabled = false;
                replyInput.value = '';
                if (data.success && data.messages) {
                    renderMessages(data.messages);
                }
            })
            .catch(() => {
                sendBtn.disabled = false;
            });
        });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});
</script>
