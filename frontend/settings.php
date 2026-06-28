<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit;
}
$pageTitle = 'إعدادات الحساب - حراج اليمن';
require_once 'includes/header.php';
?>
    <style>
        .settings-container {
            max-width: 600px;
            margin: 3rem auto;
            padding: 0 1rem;
        }
        .page-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .settings-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 2.5rem;
            box-shadow: var(--shadow-lg);
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 800;
            color: var(--text-main);
            font-size: 0.95rem;
        }
        .form-note {
            display: block;
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }
    </style>

    <main class="settings-container animate-fade-in">
        <div class="page-header">
            <h1 style="color:var(--primary); font-size:2rem; margin-bottom:0.5rem;">إعدادات الحساب ⚙️</h1>
            <p style="color:var(--text-muted); margin:0;">تحديث معلومات ملفك الشخصي</p>
        </div>

        <div class="settings-card">
            <form id="settings-form" onsubmit="saveSettings(event)">
                <div class="form-group">
                    <label class="form-label">الاسم الكامل</label>
                    <input type="text" id="set_name" class="input-premium" required value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">رقم الجوال</label>
                    <input type="tel" id="set_phone" class="input-premium" required placeholder="مثال: 777123456" dir="ltr" style="text-align:right;">
                </div>
                
                <div class="form-group">
                    <label class="form-label">كلمة المرور الجديدة</label>
                    <input type="password" id="set_password" class="input-premium" placeholder="اتركه فارغاً إذا لم ترغب بتغييره">
                    <span class="form-note">يجب أن تكون 6 أحرف على الأقل في حال أردت التغيير.</span>
                </div>
                
                <button type="submit" class="btn-primary" style="width:100%; font-size:1rem; padding:0.8rem;">حفظ التعديلات</button>
            </form>
        </div>
    </main>

    <script src="assets/js/app.js"></script>
    <script>
        async function saveSettings(e) {
            e.preventDefault();
            const data = {
                action: 'update_settings',
                name: document.getElementById('set_name').value,
                phone: document.getElementById('set_phone').value,
                password: document.getElementById('set_password').value
            };
            
            try {
                const res = await apiRequest('user', 'POST', data);
                showToast(res.message);
                document.getElementById('set_password').value = '';
                
                // Update top header name if needed or reload
                setTimeout(() => window.location.reload(), 1500);
            } catch(err) {}
        }
    </script>
<?php require_once 'includes/footer.php'; ?>
