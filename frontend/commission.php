<?php
$pageTitle = 'دفع العمولة - حراج اليمن';
require_once 'includes/header.php';
?>
    <style>
        .comm-container {
            max-width: 800px;
            margin: 3rem auto;
            padding: 2rem;
            background: var(--card-bg);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
        }
        .bank-card {
            background: rgba(5, 150, 105, 0.05);
            border: 1px solid rgba(5, 150, 105, 0.2);
            padding: 1.5rem;
            border-radius: var(--radius-lg);
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .bank-details {
            font-weight: 800;
        }
        .bank-details span {
            color: var(--primary);
            font-size: 1.25rem;
            display: block;
            margin-top: 0.25rem;
        }
    </style>

    <div class="comm-container animate-fade-in">
        <h1 style="color: var(--primary); text-align: center; font-weight: 900; margin-bottom: 0.5rem;">عمولة الموقع 1% فقط</h1>
        <p style="text-align: center; color: var(--text-muted); font-weight: 700; margin-bottom: 2rem;">
            عمولة الموقع هي أمانة في ذمتك، وهي 1% من قيمة السلعة المباعة.
        </p>

        <h3 style="margin-bottom: 1rem;">حساباتنا البنكية المعتمدة في اليمن:</h3>
        
        <div class="bank-card">
            <div class="bank-details">
                بنك الكريمي الإسلامي
                <span>123456789</span>
            </div>
            <div style="font-size: 2rem;">🏦</div>
        </div>
        
        <div class="bank-card">
            <div class="bank-details">
                بنك التضامن الإسلامي
                <span>000-111-2222</span>
            </div>
            <div style="font-size: 2rem;">🏛️</div>
        </div>

        <div style="margin-top: 2rem; padding: 1.5rem; background: rgba(245,158,11,0.1); border-radius: var(--radius-lg); border: 1px solid rgba(245,158,11,0.2);">
            <h4 style="margin: 0 0 1rem 0; color: #b45309;">تأكيد الدفع (نموذج إرسال الحوالة)</h4>
            
            <form id="commission-form" onsubmit="submitCommission(event)">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="font-weight:bold; color:var(--text-muted); font-size:0.8rem;">اسم المُحَوِّل</label>
                        <input type="text" id="comm_name" required class="input-premium" placeholder="الاسم المطابق للسند">
                    </div>
                    <div>
                        <label style="font-weight:bold; color:var(--text-muted); font-size:0.8rem;">البنك المحول إليه</label>
                        <select id="comm_bank" required class="input-premium">
                            <option value="الكريمي">الكريمي</option>
                            <option value="التضامن">التضامن</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-weight:bold; color:var(--text-muted); font-size:0.8rem;">المبلغ (ريال يمني)</label>
                        <input type="number" id="comm_amount" required class="input-premium" placeholder="مثال: 5000">
                    </div>
                    <div>
                        <label style="font-weight:bold; color:var(--text-muted); font-size:0.8rem;">رقم الإعلان</label>
                        <input type="number" id="comm_ad" required class="input-premium" placeholder="رقم الإعلان المباع">
                    </div>
                    <div style="grid-column: 1 / -1;">
                        <label style="font-weight:bold; color:var(--text-muted); font-size:0.8rem;">تاريخ التحويل</label>
                        <input type="date" id="comm_date" required class="input-premium">
                    </div>
                </div>
                <button type="submit" class="btn-primary" style="width:100%;">إرسال بيانات الحوالة للإدارة</button>
            </form>
        </div>
    </div>

    <script src="assets/js/app.js"></script>
    <script>
        async function submitCommission(e) {
            e.preventDefault();
            const data = {
                action: 'submit_transfer',
                username: document.getElementById('comm_name').value,
                bankName: document.getElementById('comm_bank').value,
                amount: document.getElementById('comm_amount').value,
                adNumber: document.getElementById('comm_ad').value,
                transferDate: document.getElementById('comm_date').value
            };
            try {
                const res = await apiRequest('commission', 'POST', data);
                showToast(res.message);
                document.getElementById('commission-form').reset();
            } catch(error) {}
        }
    </script>
<?php require_once 'includes/footer.php'; ?>
