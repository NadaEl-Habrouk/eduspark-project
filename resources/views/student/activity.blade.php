@php
    $lang = session('locale', 'ar');
    $isRtl = $lang === 'ar';
@endphp
<!DOCTYPE html>
<html lang="{{ $lang }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}" id="htmlRoot" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="pageTitle">EduSpark | Activity</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        emerald: { 500: '#10b981', 600: '#059669', 700: '#047857' },
                        amber: { 400: '#fbbf24', 500: '#f59e0b', 600: '#d97706' },
                        sky: { 400: '#38bdf8', 500: '#0ea5e9' }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .glass-card {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.7) 0%, rgba(15, 23, 42, 0.8) 100%);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glow-effect {
            box-shadow: 0 0 40px -10px rgba(16, 185, 129, 0.15);
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col selection:bg-emerald-500 selection:text-slate-950 relative overflow-x-hidden">

    <!-- Decorative Background Glows -->
    <div class="absolute top-0 right-1/4 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/3 left-1/4 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <!-- Header -->
    <header class="bg-slate-900/80 backdrop-blur-xl border-b border-slate-800/80 sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-3.5 flex items-center justify-between">
            <button onclick="window.location.href='{{ route('student.dashboard') }}'" class="flex items-center gap-2 text-slate-400 hover:text-white transition-colors text-sm font-bold group">
                <span class="transform group-hover:-translate-x-0.5 transition-transform" id="backArrow">←</span>
                <span id="backBtnText" data-i18n="backBtn">العودة للرئيسية</span>
            </button>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></span>
                <span class="text-xs font-bold text-emerald-400" id="modeIndicator" data-i18n="selfLearningMode">وضع التعلم الذاتي</span>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 max-w-4xl w-full mx-auto px-4 sm:px-6 py-8 space-y-8 z-10">

        <!-- نافذة اختيار وضع التعلم -->
        <div id="modeSelectionModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-50 flex items-center justify-center p-4">
            <div class="glass-card border border-emerald-500/30 rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl text-center space-y-6">
                <div class="w-16 h-16 bg-emerald-500/10 text-emerald-400 rounded-2xl mx-auto flex items-center justify-center text-3xl border border-emerald-500/20 shadow-inner">
                    🎯
                </div>
                <div>
                    <h3 id="modalTitle" class="text-xl font-black text-white mb-2" data-i18n="modalTitle">اختر طريقة التعلم قبل البدء</h3>
                    <p id="modalDesc" class="text-slate-400 text-sm" data-i18n="modalDesc">حدد كيف ترغب في أداء هذا النشاط اليوم:</p>
                </div>
                <div class="space-y-3">
                    <button onclick="setLearningMode('individual')" class="w-full py-3.5 px-4 bg-slate-900 hover:bg-slate-800 border border-slate-700 hover:border-emerald-500 rounded-2xl font-bold text-white transition-all flex items-center justify-between group">
                        <span id="soloModeText" class="flex items-center gap-2" data-i18n="soloMode">👤 تعلم ذاتي فردي (Solo)</span>
                        <span class="text-xs text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-lg border border-emerald-500/20" data-i18n="soloBadge">شخصي</span>
                    </button>
                    <button onclick="setLearningMode('team')" class="w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-500 text-white rounded-2xl font-bold transition-all shadow-lg shadow-emerald-600/30 flex items-center justify-between">
                        <span id="teamModeText" class="flex items-center gap-2" data-i18n="teamMode">👥 تحدي جماعي للفصل (Team)</span>
                        <span class="text-xs bg-black/20 px-2.5 py-1 rounded-lg" data-i18n="teamBadge">يكسب نقاط لفصلك</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- حاوية النشاط الرئيسية -->
        <div id="activityContainer" class="space-y-6 hidden">
            <div class="glass-card rounded-3xl p-6 sm:p-8 shadow-xl relative overflow-hidden glow-effect border-l-4 border-l-emerald-500">
                <div class="flex items-center justify-between mb-4">
                    <span id="subjectBadge" class="px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold rounded-full">تصنيف المادة</span>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-amber-400 bg-amber-500/10 border border-amber-500/20 px-3 py-1 rounded-full font-bold" id="attemptsDisplay">المحاولات المتبقية: 2</span>
                        <span class="text-xs text-slate-400 bg-slate-800 px-3 py-1 rounded-full font-bold border border-slate-700" id="currentModeDisplay">وضع فردي</span>
                    </div>
                </div>
                <h2 id="activityTitle" class="text-2xl font-black text-white mb-2">عنوان النشاط</h2>
                <p id="activityDesc" class="text-slate-300 text-sm leading-relaxed">وصف التحدي أو المهمة التعليمية...</p>
            </div>

            <div class="glass-card rounded-3xl p-6 sm:p-8 shadow-xl space-y-6">
                <div class="space-y-3" id="optionsContainer">
                    <!-- خيارات الإجابات -->
                </div>

                <button onclick="handleMainAction()" id="actionBtn" class="w-full py-4 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-2xl shadow-lg shadow-emerald-600/30 transition-all border border-emerald-500/40">
                    تأكيد الإجابة
                </button>

                <div id="feedbackToast" class="hidden p-4 rounded-2xl text-sm font-semibold transition-all"></div>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="mt-auto py-6 text-center border-t border-slate-900 text-xs text-slate-500 z-10">
        EduSpark Platform • WE Applied Technology Schools © <span id="dynamicYear"></span>
    </footer>

    <!-- Scripts -->
    <script>
        document.getElementById('dynamicYear').innerText = new Date().getFullYear();

        let learningMode = 'individual';
        let currentQuestionIndex = 0;
        let isAnswerSubmitted = false;
        let activeQuestionsList = [];
        let selectedCategoryKey = 'coding';
        let remainingAttempts = 2;

        // استخراج اللغة المخزنة أو الافتراضية
        const sessionData = JSON.parse(localStorage.getItem('eduspark_session') || '{}');
        let currentLang = sessionData.language || localStorage.getItem('eduspark_lang') || '{{ $lang }}';

        const pageTranslations = {
            ar: {
                dir: 'rtl',
                title: 'EduSpark | الأنشطة والتحديات',
                backBtn: 'العودة للرئيسية',
                selfLearningMode: 'وضع التعلم الذاتي',
                modalTitle: 'اختر طريقة التعلم قبل البدء',
                modalDesc: 'حدد كيف ترغب في أداء هذا النشاط اليوم:',
                soloMode: '👤 تعلم ذاتي فردي (Solo)',
                soloBadge: 'شخصي',
                teamMode: '👥 تحدي جماعي للفصل (Team)',
                teamBadge: 'يكسب نقاط لفصلك',
                submitBtn: 'تأكيد الإجابة',
                nextBtn: 'السؤال التالي ←',
                finishBtn: 'إنهاء التحدي والعودة للرئيسية 🚀',
                soloDisplay: 'وضع فردي',
                teamDisplay: 'وضع جماعي',
                selectAnswerAlert: 'الرجاء اختيار إجابة أولاً',
                correctFeedback: 'إجابة صحيحة! أحسنت، يتم حفظ النقاط لفصلك 🌟',
                incorrectFeedback: 'إجابة غير دقيقة، حاول مرة أخرى لديك فرصة أخيرة 💡',
                outOfAttempts: (correctText) => `نفدت محاولاتك! الإجابة الصحيحة هي: "${correctText}" ❌`,
                attemptsLeft: (count) => `المحاولات المتبقية: ${count}`
            },
            en: {
                dir: 'ltr',
                title: 'EduSpark | Activities & Challenges',
                backBtn: 'Back to Dashboard',
                selfLearningMode: 'Self-Learning Mode',
                modalTitle: 'Choose Learning Mode Before Starting',
                modalDesc: 'Select how you want to perform this activity today:',
                soloMode: '👤 Solo Self-Learning',
                soloBadge: 'Personal',
                teamMode: '👥 Class Team Challenge',
                teamBadge: 'Earns Class Points',
                submitBtn: 'Submit Answer',
                nextBtn: 'Next Question →',
                finishBtn: 'Finish Challenge & Return 🚀',
                soloDisplay: 'Solo Mode',
                teamDisplay: 'Team Mode',
                selectAnswerAlert: 'Please select an answer first',
                correctFeedback: 'Correct answer! Well done, points saved for your class 🌟',
                incorrectFeedback: 'Incorrect, try again, 1 attempt left 💡',
                outOfAttempts: (correctText) => `Out of attempts! The correct answer is: "${correctText}" ❌`,
                attemptsLeft: (count) => `Attempts left: ${count}`
            }
        };

        function applyTranslations() {
            const t = pageTranslations[currentLang] || pageTranslations['ar'];
            const htmlRoot = document.getElementById('htmlRoot');
            
            if (htmlRoot) {
                htmlRoot.setAttribute('lang', currentLang);
                htmlRoot.setAttribute('dir', t.dir);
            }
            
            document.title = t.title;

            // تحديث العناصر الثابتة التي تحمل data-i18n
            document.querySelectorAll('[data-i18n]').forEach(el => {
                const key = el.getAttribute('data-i18n');
                if (t[key]) {
                    el.innerText = t[key];
                }
            });
        }

        document.addEventListener('DOMContentLoaded', async () => {
            applyTranslations();
            selectedCategoryKey = localStorage.getItem('selected_subject') || 'coding';

            try {
                const response = await fetch(`/student/api/activities?category=${selectedCategoryKey}`);
                const data = await response.json();
                activeQuestionsList = data; 
            } catch (error) {
                console.error('Failed to fetch activities from database', error);
                activeQuestionsList = [];
            }
        });

        function setLearningMode(mode) {
            learningMode = mode;
            const t = pageTranslations[currentLang] || pageTranslations['ar'];

            document.getElementById('modeSelectionModal').classList.add('hidden');
            document.getElementById('activityContainer').classList.remove('hidden');
            document.getElementById('currentModeDisplay').innerText = (mode === 'individual' ? t.soloDisplay : t.teamDisplay);

            const dbTargetMode = (mode === 'individual') ? 'individual' : 'team';
            const filtered = activeQuestionsList.filter(q => q.mode === dbTargetMode);
            
            if (filtered.length > 0) {
                activeQuestionsList = filtered;
            }

            loadQuestion();
        }

        function loadQuestion() {
            isAnswerSubmitted = false;
            remainingAttempts = 2;
            updateAttemptsUI();
            
            const t = pageTranslations[currentLang] || pageTranslations['ar'];
            
            if (!activeQuestionsList[currentQuestionIndex]) {
                window.location.href = '{{ route('student.dashboard') }}';
                return;
            }

            const currentQ = activeQuestionsList[currentQuestionIndex];

            document.getElementById('subjectBadge').innerText = currentQ.badge;
            
            const titleObj = typeof currentQ.title === 'string' ? JSON.parse(currentQ.title) : currentQ.title;
            const descObj = typeof currentQ.description === 'string' ? JSON.parse(currentQ.description) : currentQ.description;
            const optionsArr = typeof currentQ.options === 'string' ? JSON.parse(currentQ.options) : currentQ.options;

            document.getElementById('activityTitle').innerText = titleObj[currentLang] || titleObj['en'] || titleObj['ar'];
            document.getElementById('activityDesc').innerText = descObj[currentLang] || descObj['en'] || descObj['ar'];

            const optionsContainer = document.getElementById('optionsContainer');
            optionsContainer.innerHTML = '';
            
            optionsArr.forEach((opt, idx) => {
                const optText = typeof opt === 'string' ? opt : (opt[currentLang] || opt['en'] || opt['ar']);
                optionsContainer.innerHTML += `
                    <label class="flex items-center gap-3 p-4 bg-slate-950/60 border border-slate-800 rounded-2xl cursor-pointer hover:border-emerald-500/50 transition-all group">
                        <input type="radio" name="quizOption" value="${idx}" class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 bg-slate-900 border-slate-700">
                        <span class="text-slate-200 text-sm font-medium group-hover:text-white">${optText}</span>
                    </label>
                `;
            });

            const actionBtn = document.getElementById('actionBtn');
            actionBtn.innerText = t.submitBtn;
            actionBtn.className = 'w-full py-4 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-2xl shadow-lg shadow-emerald-600/30 transition-all border border-emerald-500/40';
            actionBtn.disabled = false;

            document.getElementById('feedbackToast').classList.add('hidden');
        }

        function updateAttemptsUI() {
            const t = pageTranslations[currentLang] || pageTranslations['ar'];
            document.getElementById('attemptsDisplay').innerText = t.attemptsLeft(remainingAttempts);
        }

        function handleMainAction() {
            const t = pageTranslations[currentLang] || pageTranslations['ar'];

            if (isAnswerSubmitted) {
                currentQuestionIndex++;
                if (currentQuestionIndex < activeQuestionsList.length) {
                    loadQuestion();
                } else {
                    window.location.href = '{{ route('student.dashboard') }}';
                }
                return;
            }

            const selectedOpt = document.querySelector('input[name="quizOption"]:checked');
            if (!selectedOpt) {
                alert(t.selectAnswerAlert);
                return;
            }

            const answerIdx = parseInt(selectedOpt.value);
            const currentQ = activeQuestionsList[currentQuestionIndex];

            const toast = document.getElementById('feedbackToast');
            toast.classList.remove('hidden');

            if (answerIdx === currentQ.correct_option) {
                toast.className = 'p-4 rounded-2xl text-sm font-semibold transition-all bg-emerald-500/20 border border-emerald-500/40 text-emerald-300';
                toast.innerText = t.correctFeedback;

                fetch("{{ route('student.leaderboard.update') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ question_id: currentQ.id })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        console.log('Points recorded in DB successfully.');
                    }
                })
                .catch(error => console.error('Error recording score:', error));

                finishQuestionState();
            } else {
                remainingAttempts--;
                updateAttemptsUI();

                if (remainingAttempts > 0) {
                    toast.className = 'p-4 rounded-2xl text-sm font-semibold transition-all bg-amber-500/20 border border-amber-500/40 text-amber-300';
                    toast.innerText = t.incorrectFeedback;
                } else {
                    const optionsArr = typeof currentQ.options === 'string' ? JSON.parse(currentQ.options) : currentQ.options;
                    const correctTextObj = optionsArr[currentQ.correct_option];
                    const correctText = typeof correctTextObj === 'string' ? correctTextObj : (correctTextObj[currentLang] || correctTextObj['en'] || correctTextObj['ar']);

                    toast.className = 'p-4 rounded-2xl text-sm font-semibold transition-all bg-rose-500/20 border border-rose-500/40 text-rose-300';
                    toast.innerText = t.outOfAttempts(correctText);

                    finishQuestionState();
                }
            }
        }

        function finishQuestionState() {
            const t = pageTranslations[currentLang] || pageTranslations['ar'];
            isAnswerSubmitted = true;
            const actionBtn = document.getElementById('actionBtn');
            
            if (currentQuestionIndex < activeQuestionsList.length - 1) {
                actionBtn.innerText = t.nextBtn;
            } else {
                actionBtn.innerText = t.finishBtn;
            }
            actionBtn.className = 'w-full py-4 bg-amber-500 hover:bg-amber-400 text-slate-950 font-black rounded-2xl shadow-lg shadow-amber-500/30 transition-all border border-amber-500/40';
        }
    </script>
</body>
</html>