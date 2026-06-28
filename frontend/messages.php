<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit;
}
$pageTitle = 'المراسلات الخاصة - حراج اليمن';
require_once 'includes/header.php';
?>
    <style>
        .chat-layout {
            display: grid;
            grid-template-columns: 350px 1fr;
            height: calc(100vh - 65px);
            max-width: 1200px;
            margin: 0 auto;
            background-color: var(--card-bg);
            border-left: 1px solid var(--border-color);
            border-right: 1px solid var(--border-color);
        }
        @media (max-width: 768px) {
            .chat-layout {
                grid-template-columns: 1fr;
            }
            .threads-sidebar {
                display: block;
            }
            .chat-area.active {
                display: flex !important;
                position: fixed;
                top: 65px;
                left: 0;
                right: 0;
                bottom: 0;
                z-index: 200;
                background-color: var(--card-bg);
            }
            .threads-sidebar.hidden-mobile {
                display: none;
            }
        }
        
        .threads-sidebar {
            border-left: 1px solid var(--border-color);
            overflow-y: auto;
            background-color: var(--card-bg);
            display: flex;
            flex-direction: column;
        }

        .threads-sidebar-header {
            padding: 1rem;
            font-weight: 800;
            font-size: 0.95rem;
            color: var(--primary);
            border-bottom: 1px solid var(--border-color);
            background-color: var(--bg-color);
        }
        
        .thread-item {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }
        .thread-item:hover, .thread-item.active {
            background-color: rgba(0, 77, 122, 0.04);
        }
        .thread-item.unread {
            border-right: 4px solid var(--secondary);
            background-color: rgba(0, 153, 102, 0.05);
        }

        .chat-area {
            display: flex;
            flex-direction: column;
            background-color: var(--bg-color);
            height: 100%;
        }
        .chat-header {
            padding: 0.9rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
            background-color: var(--card-bg);
            font-weight: 800;
            font-size: 0.95rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .messages-list {
            flex: 1;
            padding: 1.5rem;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        .msg-bubble {
            max-width: 65%;
            padding: 0.65rem 1rem;
            border-radius: var(--radius-lg);
            font-size: 0.85rem;
            line-height: 1.5;
            font-weight: 700;
            position: relative;
        }
        .msg-bubble.me {
            background-color: var(--primary);
            color: white;
            align-self: flex-start;
            border-top-right-radius: 2px;
        }
        .msg-bubble.other {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            color: var(--text-main);
            align-self: flex-end;
            border-top-left-radius: 2px;
        }
        .chat-input-area {
            padding: 1rem;
            background-color: var(--card-bg);
            border-top: 1px solid var(--border-color);
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }
    </style>
    <div class="chat-layout animate-fade-in">
        
        <!-- Sidebar Conversation List -->
        <div class="threads-sidebar" id="threads-sidebar-container">
            <div class="threads-sidebar-header">📥 علبة الوارد (الرسائل)</div>
            <div id="threads-list" style="flex:1; overflow-y:auto;">
                <div style="padding:2rem; text-align:center; color:var(--text-muted); font-weight:bold;">جاري التحميل...</div>
            </div>
        </div>
        
        <!-- Active Chat Window -->
        <div class="chat-area" id="chat-area" style="display:none;">
            <div class="chat-header">
                <div>
                    <button class="btn-gold" id="btn-back-sidebar" style="padding:0.25rem 0.75rem; font-size:0.75rem; display:none; margin-left:8px;">⬅️ رجوع</button>
                    <span id="chat-ad-title" style="color:var(--primary); font-weight:900;"></span> - مع <span id="chat-other-name" style="color:var(--secondary);"></span>
                </div>
                <a href="#" id="view-ad-link" class="btn-gold" style="font-size:0.75rem; padding:0.3rem 0.8rem;">📦 عرض السلعة</a>
            </div>
            
            <!-- Messages Bubble List -->
            <div class="messages-list" id="messages-list"></div>
            
            <!-- Text message input -->
            <form class="chat-input-area" onsubmit="sendMessage(event)">
                <input type="text" id="msg-text" class="input-premium" style="margin:0;" placeholder="اكتب رسالتك الخاصة هنا بوضوح..." required autocomplete="off">
                <button type="submit" class="btn-gold" style="margin:0; padding:0.6rem 1.5rem;">إرسال ✈️</button>
            </form>
        </div>
        
        <!-- No Chat Selected Placeholder -->
        <div id="no-chat-selected" style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; color:var(--text-muted); font-weight:bold; background-color:var(--bg-color);">
            <div style="font-size:3rem; margin-bottom:1rem;">✉️</div>
            <div>يرجى اختيار محادثة من القائمة الجانبية للبدء بالدردشة الخاصة.</div>
        </div>
    </div>

    <!-- Core App JS Utilities -->
    <script src="assets/js/app.js"></script>
    <script>
        let currentThreadId = null;
        let pollInterval = null;

        const urlParams = new URLSearchParams(window.location.search);
        const initThreadId = urlParams.get('thread');

        async function loadThreads() {
            try {
                const res = await apiRequest('chat&action=threads');
                const list = document.getElementById('threads-list');
                
                if (res.data.length === 0) {
                    list.innerHTML = '<div style="padding:3rem; text-align:center; color:var(--text-muted); font-size:0.85rem; font-weight:700;">لا توجد محادثات نشطة بعد.</div>';
                    return;
                }
                
                list.innerHTML = res.data.map(t => `
                    <div class="thread-item ${t.isUnread ? 'unread' : ''} ${t.id == currentThreadId ? 'active' : ''}" onclick="selectThread(${t.id}, '${t.adTitle}', '${t.otherName}', ${t.adId})">
                        <div style="font-weight:900; color:var(--text-main); font-size:0.85rem; margin-bottom:2px;">${t.otherName}</div>
                        <div style="font-size:0.75rem; color:var(--primary); font-weight:700; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">📦 سلعة: ${t.adTitle}</div>
                        <div style="font-size:0.75rem; color:var(--text-muted); margin-top:0.4rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; font-weight:600;">${t.lastMessage}</div>
                        <span style="position:absolute; left:10px; top:10px; font-size:0.65rem; color:var(--text-muted); font-weight:700;">${t.date}</span>
                    </div>
                `).join('');
                
            } catch(e) {}
        }

        function selectThread(threadId, adTitle, otherName, adId) {
            currentThreadId = threadId;
            
            // Adjust mobile display
            if (window.innerWidth <= 768) {
                document.getElementById('threads-sidebar-container').classList.add('hidden-mobile');
                document.getElementById('chat-area').classList.add('active');
                const backBtn = document.getElementById('btn-back-sidebar');
                if (backBtn) {
                    backBtn.style.display = 'inline-flex';
                    backBtn.onclick = () => {
                        document.getElementById('threads-sidebar-container').classList.remove('hidden-mobile');
                        document.getElementById('chat-area').classList.remove('active');
                        currentThreadId = null;
                        if (pollInterval) {
                            clearInterval(pollInterval);
                            pollInterval = null;
                        }
                    };
                }
            }

            loadChat(threadId, adTitle, otherName, adId);
        }

        async function loadChat(threadId, adTitle, otherName, adId) {
            currentThreadId = threadId;
            document.getElementById('no-chat-selected').style.display = 'none';
            document.getElementById('chat-area').style.display = 'flex';
            
            if (adTitle) document.getElementById('chat-ad-title').innerText = adTitle;
            if (otherName) document.getElementById('chat-other-name').innerText = otherName;
            if (adId) document.getElementById('view-ad-link').href = `ad.php?id=${adId}`;
            
            // Sync active states on sidebar items
            document.querySelectorAll('.thread-item').forEach(i => i.classList.remove('active'));
            
            try {
                const res = await apiRequest(`chat&action=messages&thread_id=${threadId}`);
                const list = document.getElementById('messages-list');
                
                list.innerHTML = res.data.messages.map(m => `
                    <div class="msg-bubble ${m.isMe ? 'me' : 'other'}">
                        <div>${m.text}</div>
                        <div style="font-size:0.6rem; opacity:0.75; margin-top:0.25rem; text-align:${m.isMe ? 'left' : 'right'}; font-weight:700;">${m.date}</div>
                    </div>
                `).join('');
                
                list.scrollTop = list.scrollHeight;
                
                if (!pollInterval) {
                    pollInterval = setInterval(() => loadChat(currentThreadId), 5000);
                }
            } catch(e) {}
        }

        async function sendMessage(e) {
            e.preventDefault();
            const textInput = document.getElementById('msg-text');
            const text = textInput.value.trim();
            
            if (!text || !currentThreadId) return;
            
            try {
                await apiRequest('chat', 'POST', { action: 'send', thread_id: currentThreadId, text: text });
                textInput.value = '';
                loadChat(currentThreadId);
                loadThreads();
            } catch(e) {}
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadThreads();
            if (initThreadId) {
                // If thread parameter is passed in URL, load it directly
                selectThread(initThreadId);
            }
            setInterval(loadThreads, 10000);
        });
    </script>
<?php require_once 'includes/footer.php'; ?>
