<!DOCTYPE html>
<html lang="ar" dir="rtl" id="htmlRoot" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title id="pageTitle">EduSpark</title>
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3.5 flex items-center justify-between">
            
            <!-- Logo & Title Section -->
            <div class="flex items-center space-x-4 space-x-reverse" id="brandContainer">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-white border-2 border-emerald-500/40 rounded-full shadow-lg shadow-emerald-600/30 overflow-hidden transform hover:scale-105 transition-transform shrink-0">
                    <img src="{{ asset('images/logo.png') }}" alt="EduSpark Logo" class="w-full h-full object-cover rounded-full" onerror="this.onerror=null; this.parentElement.innerHTML='<span class=\'text-2xl\'>⚡</span>';">
                </div>
                <div class="flex items-center gap-4">
                    <div class="h-4 w-px bg-slate-700/60 hidden sm:block"></div>
                    <p class="text-xs text-slate-300 font-semibold tracking-wide" id="studentGreeting">أهلاً بك يا بطل</p>
                </div>
            </div>

            <!-- Class Badge, Language Switcher & Logout Button -->
            <div class="flex items-center gap-3">
                <!-- Language Toggle Button -->
                <button onclick="toggleLanguage()" class="px-3 py-2 bg-slate-800/80 hover:bg-slate-700 border border-slate-700/50 rounded-xl text-xs font-bold text-emerald-400 flex items-center gap-1.5 transition-all shadow-sm" title="تغيير اللغة / Change Language">
                    <span>🌐</span>
                    <span id="langButtonText">EN</span>
                </button>

                <div class="bg-slate-800/80 border border-slate-700/50 px-3.5 py-2 rounded-xl text-xs font-bold text-amber-400 flex items-center gap-2 shadow-sm">
                    <span id="badgeClassCode" class="text-white font-mono tracking-wider">--</span>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 active:bg-amber-500 text-white rounded-xl transition-all duration-300 border border-emerald-500/40 shadow-lg shadow-emerald-600/30 active:shadow-amber-500/40 flex items-center gap-2 group" id="logoutBtn" title="تسجيل الخروج">
                        <svg class="w-4 h-4 text-white transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span class="text-white text-xs font-semibold" data-i18n="logoutBtn">تسجيل خروج</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 py-8 space-y-8 z-10">
        
        <!-- Welcome Banner -->
        <div class="glass-card rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden glow-effect border-l-4 border-l-emerald-500">
            <div class="absolute top-0 right-0 w-72 h-72 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                <div class="md:col-span-2">
                    <span id="bannerTag" class="px-3 py-1 bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-bold rounded-full inline-block mb-3">Your first step in self-learning starts here 🚀</span>
                    <h1 id="bannerTitle" class="text-2xl sm:text-3xl font-black text-white leading-tight mb-2">مدرس الحصة غايب؟ استغل وقتك الذكي وابدأ رحلة التطوير!</h1>
                    <p id="bannerDesc" class="text-slate-300 text-sm leading-relaxed">اختر مسارك أدناه، وانخرط في الأنشطة التفاعلية لترفع نقاط فصلك وتؤكد تميزك كأحد نابغين مدرسة وي للتكنولوجيا التطبيقية.</p>
                </div>
                
                <!-- Database Driven Stats Cards -->
                <div class="bg-slate-900/90 border border-slate-700/60 rounded-2xl p-4 flex items-center justify-around text-center backdrop-blur-md shadow-inner">
                    <div>
                        <div class="text-2xl font-black text-amber-400 font-mono" id="userPoints">{{ $userPoints ?? 0 }}</div>
                        <div class="text-[11px] text-slate-400 font-bold mt-0.5" id="pointsLabelText">نقاط الفصل 🏆</div>
                    </div>
                    <div class="w-px h-8 bg-slate-800"></div>
                    <div>
                        <div class="text-2xl font-black text-emerald-400 font-mono" id="completedCount">{{ $completedCount ?? 0 }}</div>
                        <div class="text-[11px] text-slate-400 font-bold mt-0.5" id="completedLabelText">أنشطة منجزة ✨</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subjects Grid Section -->
        <div>
            <div class="flex items-center justify-between mb-6">
                <h3 id="subjectsHeader" class="text-lg font-bold text-white flex items-center gap-2.5">
                    <span class="p-2 bg-emerald-500/10 text-emerald-400 rounded-xl text-base border border-emerald-500/20">📚</span> 
                    <span id="subjectsHeaderText">المسارات التعليمية المتاحة اليوم</span>
                </h3>
                <span id="subjectsSubHeader" class="text-xs text-slate-400 font-medium">اختر مساراً وابدأ التحدي الآن</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Card 1: Coding -->
                <div onclick="selectSubject('coding')" class="glass-card rounded-3xl p-6 cursor-pointer transition-all duration-300 transform hover:-translate-y-1.5 shadow-xl relative overflow-hidden flex flex-col justify-between group hover:border-emerald-500/50">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-amber-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-emerald-500/10 text-emerald-400 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform shadow-inner border border-emerald-500/20">
                                💻
                            </div>
                            <span id="badge-coding" class="text-[11px] px-3 py-1 rounded-full bg-slate-800 text-slate-400 font-bold border border-slate-700/60">متاح</span>
                        </div>
                        <h4 id="card1Title" class="text-lg font-bold text-white mb-2 group-hover:text-emerald-300 transition-colors">تكنولوجيا وهندسة برمجيات</h4>
                        <p id="card1Desc" class="text-slate-400 text-sm leading-relaxed mb-6">تحديات هندسة الأكواد وحل المشكلات البرمجية لتعزيز مهاراتك التقنية في بناء الويب.</p>
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-800/80 text-sm font-bold text-emerald-400 group-hover:text-emerald-300">
                        <span class="cardActionText">ابدأ التحدي الآن</span>
                        <span class="transform group-hover:-translate-x-1.5 transition-transform">←</span>
                    </div>
                </div>

                <!-- Card 2: Entrepreneurship -->
                <div onclick="selectSubject('entrepreneurship')" class="glass-card rounded-3xl p-6 cursor-pointer transition-all duration-300 transform hover:-translate-y-1.5 shadow-xl relative overflow-hidden flex flex-col justify-between group hover:border-amber-500/50">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-amber-500 to-emerald-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-amber-500/10 text-amber-400 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform shadow-inner border border-amber-500/20">
                                💡
                            </div>
                            <span id="badge-entrepreneurship" class="text-[11px] px-3 py-1 rounded-full bg-slate-800 text-slate-400 font-bold border border-slate-700/60">متاح</span>
                        </div>
                        <h4 id="card2Title" class="text-lg font-bold text-white mb-2 group-hover:text-amber-300 transition-colors">ريادة الأعمال والابتكار الرقمي</h4>
                        <p id="card2Desc" class="text-slate-400 text-sm leading-relaxed mb-6">دراسة نماذج الأعمال الناشئة وتحفيز التفكير النقدي لحل التحديات المجتمعية بابتكار.</p>
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-800/80 text-sm font-bold text-amber-400 group-hover:text-amber-300">
                        <span class="cardActionText">ابدأ التحدي الآن</span>
                        <span class="transform group-hover:-translate-x-1.5 transition-transform">←</span>
                    </div>
                </div>

                <!-- Card 3: Core Subjects -->
                <div onclick="selectSubject('core')" class="glass-card rounded-3xl p-6 cursor-pointer transition-all duration-300 transform hover:-translate-y-1.5 shadow-xl relative overflow-hidden flex flex-col justify-between group hover:border-sky-500/50">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-sky-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-sky-500/10 text-sky-400 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform shadow-inner border border-sky-500/20">
                                📐
                            </div>
                            <span id="badge-core" class="text-[11px] px-3 py-1 rounded-full bg-slate-800 text-slate-400 font-bold border border-slate-700/60">متاح</span>
                        </div>
                        <h4 id="card3Title" class="text-lg font-bold text-white mb-2 group-hover:text-sky-300 transition-colors">العلوم المعرفية والأساسية</h4>
                        <p id="card3Desc" class="text-slate-400 text-sm leading-relaxed mb-6">مسابقات واختبارات تفاعلية ذكية لتطوير الحصيلة المعرفية في العلوم والرياضيات واللغات.</p>
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-800/80 text-sm font-bold text-sky-400 group-hover:text-sky-300">
                        <span class="cardActionText">ابدأ التحدي الآن</span>
                        <span class="transform group-hover:-translate-x-1.5 transition-transform">←</span>
                    </div>
                </div>

            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="mt-auto py-6 text-center border-t border-slate-900 text-xs text-slate-500 z-10" id="footerText">
        EduSpark Platform • WE Applied Technology Schools © <span id="dynamicYear"></span>
    </footer>

    <!-- Scripts -->
    <script>
        @if(session('user_name'))
            const savedLang = localStorage.getItem('eduspark_lang') || '{{ session("locale", "ar") }}';
            
            localStorage.setItem('eduspark_session', JSON.stringify({
                role: "{{ session('role') }}",
                userName: "{{ session('user_name') }}",
                classCode: "{{ session('class_code') }}",
                language: savedLang
            }));
        @endif

        document.getElementById('dynamicYear').innerText = new Date().getFullYear();

        const dashboardTranslations = {
            ar: {
                dir: 'rtl',
                title: 'EduSpark ⚡ - لوحة تحكم الطالب',
                studentDashboardTitle: 'لوحة الطالب',
                greeting: (name) => `أهلاً بك، ${name}`,
                pointsLabel: 'نقاط الفصل 🏆',
                completedLabel: 'أنشطة منجزة ✨',
                bannerTag: 'خطوتك الأولى في التعلم الذاتي تبدأ من هنا 🚀',
                bannerTitle: 'المدرس غايب؟ دي مش حصة ضايعة، دي فرصة تسبق بيها وتطور مهاراتك!',
                bannerDesc: 'اختر مسارك أدناه، وانخرط في الأنشطة التفاعلية لترفع نقاط فصلك وتؤكد تميزك كأحد نابغين مدرسة وي للتكنولوجيا التطبيقية.',
                subjectsHeader: 'المسارات التعليمية المتاحة اليوم',
                subjectsSub: 'اختر مساراً وابدأ التحدي الآن',
                card1Title: 'تكنولوجيا وهندسة برمجيات',
                card1Desc: 'تحديات هندسة الأكواد وحل المشكلات البرمجية لتعزيز مهاراتك التقنية في بناء الويب.',
                card2Title: 'ريادة الأعمال والابتكار الرقمي',
                card2Desc: 'دراسة نماذج الأعمال الناشئة وتحفيز التفكير النقدي لحل التحديات المجتمعية بابتكار.',
                card3Title: 'العلوم المعرفية والأساسية',
                card3Desc: 'مسابقات واختبارات تفاعلية ذكية لتطوير الحصيلة المعرفية في العلوم والرياضيات واللغات.',
                cardAction: 'ابدأ التحدي الآن',
                badgeAvailable: 'متاح',
                badgeCompleted: 'تم الإنجاز ✅',
                logoutBtn: 'تسجيل خروج',
                footer: 'EduSpark Platform • WE Applied Technology Schools © '
            },
            en: {
                dir: 'ltr',
                title: 'EduSpark ⚡ - Student Dashboard',
                studentDashboardTitle: 'Student Dashboard',
                greeting: (name) => `Welcome, ${name}`,
                pointsLabel: 'Class Points 🏆',
                completedLabel: 'Completed ✨',
                bannerTag: 'Your first step in self-learning starts here 🚀',
                bannerTitle: 'Teacher absent? That’s not a wasted class; it’s your head start to level up your skills!',
                bannerDesc: 'Choose a path below and start interactive activities to earn points for your class.',
                subjectsHeader: 'Available Learning Paths',
                subjectsSub: 'Choose a path to start immediately',
                card1Title: 'Technology & Software Engineering',
                card1Desc: 'Coding challenges and logical problem-solving to boost your web development skills.',
                card2Title: 'Entrepreneurship & Digital Innovation',
                card2Desc: 'Startup case studies and critical thinking for community problem-solving.',
                card3Title: 'Core & Cognitive Sciences',
                card3Desc: 'Gamified interactive quizzes to solidify knowledge in science, math, and languages.',
                cardAction: 'Start Challenge Now',
                badgeAvailable: 'Available',
                badgeCompleted: 'Completed ✅',
                logoutBtn: 'Logout',
                footer: 'EduSpark Platform • WE Applied Technology Schools © '
            }
        };

        function applyTranslations(lang) {
            const t = dashboardTranslations[lang];
            const htmlRoot = document.getElementById('htmlRoot');
            
            if(htmlRoot) {
                htmlRoot.setAttribute('lang', lang);
                htmlRoot.setAttribute('dir', t.dir);
            }
            
            document.title = t.title;
            document.getElementById('studentGreeting').innerText = t.greeting(getUserName());
            document.getElementById('badgeClassCode').innerText = getClassCode() || 'Class: CSA3';
            document.getElementById('pointsLabelText').innerText = t.pointsLabel;
            document.getElementById('completedLabelText').innerText = t.completedLabel;
            document.getElementById('bannerTag').innerText = t.bannerTag;
            document.getElementById('bannerTitle').innerText = t.bannerTitle;
            document.getElementById('bannerDesc').innerText = t.bannerDesc;
            document.getElementById('subjectsSubHeader').innerText = t.subjectsSub;
            document.getElementById('subjectsHeaderText').innerText = t.subjectsHeader;

            document.getElementById('card1Title').innerText = t.card1Title;
            document.getElementById('card1Desc').innerText = t.card1Desc;
            document.getElementById('card2Title').innerText = t.card2Title;
            document.getElementById('card2Desc').innerText = t.card2Desc;
            document.getElementById('card3Title').innerText = t.card3Title;
            document.getElementById('card3Desc').innerText = t.card3Desc;

            document.querySelectorAll('[data-i18n]').forEach(el => {
                const key = el.getAttribute('data-i18n');
                if (t[key]) el.innerText = t[key];
            });

            document.querySelectorAll('.cardActionText').forEach(el => el.innerText = t.cardAction);
            document.getElementById('footerText').innerHTML = `EduSpark Platform • WE Applied Technology Schools © <span id="dynamicYear">${new Date().getFullYear()}</span>`;
            
            document.getElementById('langButtonText').innerText = lang === 'ar' ? 'EN' : 'AR';
        }

        function getUserName() {
            try {
                const session = JSON.parse(localStorage.getItem('eduspark_session'));
                return session ? session.userName : 'بطل';
            } catch(e) {
                return 'بطل';
            }
        }

        function getClassCode() {
            try {
                const session = JSON.parse(localStorage.getItem('eduspark_session'));
                return session ? session.classCode : '';
            } catch(e) {
                return '';
            }
        }

        function toggleLanguage() {
            const currentLang = localStorage.getItem('eduspark_lang') || 'ar';
            const newLang = currentLang === 'ar' ? 'en' : 'ar';
            
            localStorage.setItem('eduspark_lang', newLang);
            
            try {
                let session = JSON.parse(localStorage.getItem('eduspark_session')) || {};
                session.language = newLang;
                localStorage.setItem('eduspark_session', JSON.stringify(session));
            } catch(e) {}

            applyTranslations(newLang);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const session = JSON.parse(localStorage.getItem('eduspark_session'));
            
            if (!session || session.role !== 'student') {
                window.location.href = "{{ route('login') }}"; 
                return;
            }

            const lang = session.language || localStorage.getItem('eduspark_lang') || 'ar';
            localStorage.setItem('eduspark_lang', lang);
            
            applyTranslations(lang);

            // جلب البيانات من الداتابيز مباشرة، مع حفظها وتحديثها محلياً
            const userName = session.userName;
            const dbPoints = '{{ $userPoints ?? 0 }}';
            const dbCompleted = '{{ $completedCount ?? 0 }}';

            // دمج القيمة القادمة من الداتابيز مع التخزين المحلي لتفادي أي تصفير غير مرغوب
            const userPoints = Math.max(parseInt(localStorage.getItem(`points_${userName}`) || 0), parseInt(dbPoints));
            const userCompleted = Math.max(parseInt(localStorage.getItem(`completed_${userName}`) || 0), parseInt(dbCompleted));

            localStorage.setItem(`points_${userName}`, userPoints);
            localStorage.setItem(`completed_${userName}`, userCompleted);

            document.getElementById('userPoints').innerText = userPoints;
            document.getElementById('completedCount').innerText = userCompleted;
        });

        // دالة لتحديث النقاط والأنشطة فور الإجابة الصحيحة
        function recordCorrectAnswer() {
            fetch('/student/leaderboard/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const sessionData = JSON.parse(localStorage.getItem('eduspark_session'));
                    if(sessionData && sessionData.userName) {
                        localStorage.setItem(`points_${sessionData.userName}`, data.points);
                        localStorage.setItem(`completed_${sessionData.userName}`, data.completed_activities);
                    }
                    
                    const pointsEl = document.getElementById('userPoints');
                    const completedEl = document.getElementById('completedCount');
                    if(pointsEl) pointsEl.innerText = data.points;
                    if(completedEl) completedEl.innerText = data.completed_activities;
                }
            })
            .catch(error => console.error('Error updating score:', error));
        }

        function selectSubject(subjectKey) {
            localStorage.setItem('selected_subject', subjectKey);
            let mode = 'solo'; 
            window.location.href = `/student/activity/${subjectKey}/${mode}`;
        }
    </script>
</body>
</html>