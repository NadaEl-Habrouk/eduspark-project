@php
    $lang = app()->getLocale();
    $isRtl = $lang === 'ar';
@endphp
<!DOCTYPE html>
<html lang="{{ $lang }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}" id="htmlRoot" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="pageTitle">EduSpark | Teacher Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        emerald: { 500: '#10b981', 600: '#059669', 700: '#047857' },
                        amber: { 400: '#fbbf24', 500: '#f59e0b', 600: '#d97706' }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col selection:bg-emerald-500 selection:text-slate-950">

<header class="bg-slate-900/85 backdrop-blur-xl border-b border-slate-800/80 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3.5 flex items-center justify-between">
        
        <!-- Logo & Title Section -->
        <div class="flex items-center {{ $isRtl ? 'space-x-4 space-x-reverse' : 'space-x-4' }}" id="brandContainer">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-white border-2 border-emerald-500/40 rounded-full shadow-lg shadow-emerald-600/30 overflow-hidden transform hover:scale-105 transition-transform shrink-0">
                <img src="{{ asset('images/logo.png') }}" alt="EduSpark Logo" class="w-full h-full object-cover rounded-full" onerror="this.onerror=null; this.parentElement.innerHTML='<span class=\'text-2xl\'>⚡</span>';">
            </div>
            <div class="flex items-center gap-3">
                <div class="h-4 w-px bg-slate-700/60 hidden sm:block"></div>
                <div>
                    <span class="text-white font-black text-lg tracking-wider block leading-tight">EduSpark</span>
                    <p class="text-xs text-amber-400 font-semibold tracking-wide" id="teacherGreeting">{{ $teacherName ?? 'Nada Saad' }}</p>
                </div>
            </div>
        </div>

        <!-- Controls: Language Switcher & Logout -->
        <div class="flex items-center gap-3">
            <div class="flex bg-slate-800 border border-slate-700 rounded-xl p-1">
                <button type="button" onclick="toggleLanguage('ar')" id="langArBtn" class="px-3 py-1 rounded-lg text-xs font-bold transition-all">عربي</button>
                <button type="button" onclick="toggleLanguage('en')" id="langEnBtn" class="px-3 py-1 rounded-lg text-xs font-bold transition-all">EN</button>
            </div>

            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 active:bg-amber-500 text-white rounded-xl transition-all duration-300 border border-emerald-500/40 shadow-lg shadow-emerald-600/30 flex items-center gap-2 group" id="logoutBtn" title="تسجيل الخروج">
                    <svg class="w-4 h-4 text-white transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span class="text-white text-xs font-semibold" data-i18n="logoutText">تسجيل خروج</span>
                </button>
            </form>
        </div>
    </div>
</header> 

<main class="flex-1 max-w-6xl w-full mx-auto px-4 py-8 space-y-8">
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-xl flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 mb-1" data-i18n="stat1Title">إجمالي الحصص المفعلة للفصل</p>
                <h3 class="text-3xl font-black text-white" id="statActiveClasses">
                    {{ $activeClassesCount ?? 0 }} <span class="text-sm font-normal text-slate-400" data-i18n="classWord">حصة</span>
                    @if(!empty($targetClassCode))
                        <span class="block text-xs font-mono text-emerald-400 mt-1">({{ $targetClassCode }})</span>
                    @endif
                </h3>
            </div>
            <div class="w-14 h-14 bg-emerald-500/10 text-emerald-400 rounded-2xl flex items-center justify-center text-2xl">
                🏫
            </div>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-xl flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 mb-1" data-i18n="stat2Title">إجمالي الأنشطة المنجزة للفصل</p>
                <h3 class="text-3xl font-black text-amber-400" id="statCompletedActivities">
                    {{ $totalActivities ?? 0 }} <span class="text-sm font-normal text-slate-400" data-i18n="activitiesWord">نشاط</span>
                </h3>
            </div>
            <div class="w-14 h-14 bg-amber-500/10 text-amber-400 rounded-2xl flex items-center justify-center text-2xl">
                ⚡
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Leaderboard Section -->
        <div class="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <span>🏆</span> <span data-i18n="leaderboardTitle">أكثر الفصول تفاعلاً (Leaderboard)</span>
                </h3>
                <span class="text-xs text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-full border border-emerald-500/20" data-i18n="liveUpdate">تحديث لحظي</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full {{ $isRtl ? 'text-right' : 'text-left' }} border-collapse" id="mainTableElement">
                    <thead>
                        <tr class="border-b border-slate-800 text-xs text-slate-400 uppercase">
                            <th class="py-3 px-4" data-i18n="thRank">الترتيب</th>
                            <th class="py-3 px-4" data-i18n="thClassCode">كود الفصل</th>
                            <th class="py-3 px-4" data-i18n="thActivities">الأنشطة المنجزة</th>
                            <th class="py-3 px-4" data-i18n="thPoints">النقاط الإجمالية</th>
                        </tr>
                    </thead>
                    <tbody id="leaderboardTableBody" class="divide-y divide-slate-800/60 text-sm">
                        @forelse($leaderboards as $item)
                            @php 
                                $rank = $item->calculated_rank; 
                                $isTargetClass = (!empty($targetClassCode) && strcasecmp($item->class_code ?? $item->code ?? '', $targetClassCode) === 0);
                            @endphp
                            <tr class="hover:bg-slate-800/40 transition-colors {{ $isTargetClass ? 'bg-emerald-500/10 border-emerald-500/30' : '' }}">
                                <td class="py-3 px-4">
                                    @if($rank === 1)
                                        <span class="px-2.5 py-1 bg-amber-500/20 text-amber-400 border border-amber-500/30 rounded-lg text-xs font-bold" data-i18n="rank1">🥇 المركز الأول</span>
                                    @elseif($rank === 2)
                                        <span class="px-2.5 py-1 bg-slate-400/20 text-slate-300 border border-slate-400/30 rounded-lg text-xs font-bold" data-i18n="rank2">🥈 المركز الثاني</span>
                                    @elseif($rank === 3)
                                        <span class="px-2.5 py-1 bg-amber-700/20 text-amber-600 border border-amber-700/30 rounded-lg text-xs font-bold" data-i18n="rank3">🥉 المركز الثالث</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-slate-800 text-slate-300 rounded-lg text-xs font-bold">#{{ $rank }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 font-mono font-bold text-white">{{ $item->class_code ?? $item->code }}</td>
                                <td class="py-3 px-4 text-slate-300"><span class="activity-count-val">{{ $item->completed_activities ?? 0 }}</span> <span data-i18n="activitiesWord">أنشطة</span></td>
                                <td class="py-3 px-4 font-black text-amber-400"><span class="points-val">{{ $item->points ?? 0 }}</span> <span data-i18n="pointsWord">نقطة</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-500 text-xs" data-i18n="noData">لا توجد بيانات متاحة حالياً</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Evaluation Form Section -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xl flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-bold text-white mb-2 flex items-center gap-2">
                    <span>📝</span> <span data-i18n="evalTitle">نموذج تقييم الحصة</span>
                </h3>
                <p class="text-slate-400 text-xs mb-6" data-i18n="evalSub">سجل ملاحظاتك وسلوك الفصل خلال حصة الاحتياطي.</p>

                <form id="evalForm" action="{{ route('teacher.evaluation.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5" data-i18n="labelClassCode">كود الفصل المستهدف</label>
                        <input type="text" name="class_code" id="evalClassCode" value="{{ $targetClassCode ?? '' }}" required placeholder="A3" 
                            class="w-full px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:border-emerald-500 uppercase font-mono">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5" data-i18n="labelRating">تقييم الالتزام والتفاعل (من 5 نجوم)</label>
                        <div class="flex items-center {{ $isRtl ? 'space-x-2 space-x-reverse' : 'space-x-2' }}" id="starContainer">
                            <button type="button" class="text-2xl text-slate-600 hover:text-amber-400 transition-colors star-btn" data-value="1">★</button>
                            <button type="button" class="text-2xl text-slate-600 hover:text-amber-400 transition-colors star-btn" data-value="2">★</button>
                            <button type="button" class="text-2xl text-slate-600 hover:text-amber-400 transition-colors star-btn" data-value="3">★</button>
                            <button type="button" class="text-2xl text-slate-600 hover:text-amber-400 transition-colors star-btn" data-value="4">★</button>
                            <button type="button" class="text-2xl text-slate-600 hover:text-amber-400 transition-colors star-btn" data-value="5">★</button>
                            
                            <input type="hidden" name="engagement_rating" id="selectedEngagementRating" value="5">
                            <input type="hidden" name="commitment_rating" id="selectedCommitmentRating" value="5">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5" data-i18n="labelNotes">ملاحظات المعلم المشرف</label>
                        <textarea name="notes" id="evalNotes" rows="3" required placeholder="..." 
                            class="w-full px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-white text-sm focus:outline-none focus:border-emerald-500"></textarea>
                    </div>

                    <button type="submit" class="w-full py-3 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-bold rounded-xl shadow-lg shadow-amber-500/20 transition-all" data-i18n="submitBtn">حفظ وتقييم الحصة 📋</button>
                </form>
            </div>

            @if(session('success'))
                <div id="evalToast" class="mt-4 p-3 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold rounded-xl text-center">
                    {{ session('success') }}
                </div>
            @endif
        </div>

    </div>

</main>

<footer class="mt-auto py-6 text-center border-t border-slate-900 text-xs text-slate-500">
    EduSpark Platform © <span id="dynamicYear"></span>
</footer>

<script>
    document.getElementById('dynamicYear').innerText = new Date().getFullYear();

    const sessionData = JSON.parse(localStorage.getItem('eduspark_session') || '{}');
    let currentLang = sessionData.language || localStorage.getItem('eduspark_lang') || '{{ $lang }}';

    const teacherTranslations = {
        ar: {
            dir: 'rtl',
            title: 'EduSpark | لوحة تحكم المعلم',
            greeting: (name) => name || 'Nada Saad',
            logoutText: 'تسجيل خروج',
            stat1Title: 'إجمالي الحصص المفعلة للفصل',
            stat2Title: 'إجمالي الأنشطة المنجزة للفصل',
            classWord: 'حصة',
            leaderboardTitle: 'أكثر الفصول تفاعلاً (Leaderboard)',
            liveUpdate: 'تحديث لحظي',
            thRank: 'الترتيب',
            thClassCode: 'كود الفصل',
            thActivities: 'الأنشطة المنجزة',
            thPoints: 'النقاط الإجمالية',
            rank1: '🥇 المركز الأول',
            rank2: '🥈 المركز الثاني',
            rank3: '🥉 المركز الثالث',
            activitiesWord: 'نشاط',
            pointsWord: 'نقطة',
            noData: 'لا توجد بيانات متاحة حالياً',
            evalTitle: 'نموذج تقييم الحصة',
            evalSub: 'سجل ملاحظاتك وسلوك الفصل خلال حصة الاحتياطي.',
            labelClassCode: 'كود الفصل المستهدف',
            labelRating: 'تقييم الالتزام والتفاعل (من 5 نجوم)',
            labelNotes: 'ملاحظات المعلم المشرف',
            submitBtn: 'حفظ وتقييم الحصة 📋'
        },
        en: {
            dir: 'ltr',
            title: 'EduSpark | Teacher Dashboard',
            greeting: (name) => name || 'Nada Saad',
            logoutText: 'Logout',
            stat1Title: 'Total Active Classes for Class',
            stat2Title: 'Total Completed Activities for Class',
            classWord: 'Classes',
            leaderboardTitle: 'Most Active Classes (Leaderboard)',
            liveUpdate: 'Live Update',
            thRank: 'Rank',
            thClassCode: 'Class Code',
            thActivities: 'Completed Activities',
            thPoints: 'Total Points',
            rank1: '🥇 1st Place',
            rank2: '🥈 2nd Place',
            rank3: '🥉 3rd Place',
            activitiesWord: 'Activities',
            pointsWord: 'Points',
            noData: 'No data available at the moment',
            evalTitle: 'Class Evaluation Form',
            evalSub: 'Record your notes and class behavior during the substitute session.',
            labelClassCode: 'Target Class Code',
            labelRating: 'Commitment & Engagement Rating (Out of 5)',
            labelNotes: 'Supervisor Notes',
            submitBtn: 'Save & Evaluate Class 📋'
        }
    };

    function applyTeacherTranslations() {
        const t = teacherTranslations[currentLang] || teacherTranslations.ar;
        const isEn = currentLang === 'en';

        const htmlRoot = document.getElementById('htmlRoot');
        if(htmlRoot) {
            htmlRoot.setAttribute('lang', currentLang);
            htmlRoot.setAttribute('dir', t.dir);
        }

        const table = document.getElementById('mainTableElement');
        if(table) {
            table.className = isEn ? 'w-full text-left border-collapse' : 'w-full text-right border-collapse';
        }

        const brandContainer = document.getElementById('brandContainer');
        if(brandContainer) {
            brandContainer.className = isEn ? 'flex items-center space-x-4' : 'flex items-center space-x-4 space-x-reverse';
        }

        const starContainer = document.getElementById('starContainer');
        if(starContainer) {
            starContainer.className = isEn ? 'flex items-center space-x-2' : 'flex items-center space-x-2 space-x-reverse';
        }

        // تحديث أزرار تبديل اللغة النشطة بصرياً
        const btnAr = document.getElementById('langArBtn');
        const btnEn = document.getElementById('langEnBtn');
        if (btnAr && btnEn) {
            if (isEn) {
                btnEn.className = 'px-3 py-1 rounded-lg text-xs font-bold transition-all bg-emerald-600 text-white shadow';
                btnAr.className = 'px-3 py-1 rounded-lg text-xs font-bold transition-all text-slate-400 hover:text-white';
            } else {
                btnAr.className = 'px-3 py-1 rounded-lg text-xs font-bold transition-all bg-emerald-600 text-white shadow';
                btnEn.className = 'px-3 py-1 rounded-lg text-xs font-bold transition-all text-slate-400 hover:text-white';
            }
        }

        document.title = t.title;
        const teacherGreetingEl = document.getElementById('teacherGreeting');
        if(teacherGreetingEl) {
            teacherGreetingEl.innerText = t.greeting("{{ $teacherName ?? 'Nada Saad' }}");
        }

        document.querySelectorAll('[data-i18n]').forEach(el => {
            const key = el.getAttribute('data-i18n');
            if (t[key]) {
                el.innerText = t[key];
            }
        });
    }

    function toggleLanguage(lang) {
        currentLang = lang;
        localStorage.setItem('eduspark_lang', lang);
        const sessionData = JSON.parse(localStorage.getItem('eduspark_session') || '{}');
        sessionData.language = lang;
        localStorage.setItem('eduspark_session', JSON.stringify(sessionData));
        
        applyTeacherTranslations();
    }

    document.addEventListener('DOMContentLoaded', () => {
        applyTeacherTranslations();

        const stars = document.querySelectorAll('.star-btn');
        const engagementInput = document.getElementById('selectedEngagementRating');
        const commitmentInput = document.getElementById('selectedCommitmentRating');

        function setRating(val) {
            engagementInput.value = val;
            commitmentInput.value = val;
            stars.forEach((star, idx) => {
                if (idx < val) {
                    star.classList.remove('text-slate-600');
                    star.classList.add('text-amber-400');
                } else {
                    star.classList.remove('text-amber-400');
                    star.classList.add('text-slate-600');
                }
            });
        }

        stars.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const val = parseInt(e.target.getAttribute('data-value'), 10);
                setRating(val);
            });
        });

        setRating(0); 
    });
</script>
</body>
</html>