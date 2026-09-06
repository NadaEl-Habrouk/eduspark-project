<!DOCTYPE html>
<html lang="ar" dir="rtl" id="htmlRoot">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="pageTitle">EduSpark ⚡ - نظام الحصص الاحتياطي</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        emerald: {
                            600: '#059669',
                            700: '#047857',
                        },
                        amber: {
                            500: '#f59e0b',
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.2s;
        }
        input[type="date"]::-webkit-calendar-picker-indicator:hover {
            opacity: 1;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-emerald-950 via-slate-900 to-slate-950 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-slate-900/90 backdrop-blur-xl border border-emerald-500/20 rounded-3xl shadow-2xl p-8 relative overflow-hidden">
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex justify-between items-center mb-4">
            <button type="button" onclick="toggleLanguage()" class="px-3 py-1.5 bg-slate-800/85 hover:bg-slate-700/80 border border-slate-700 text-slate-300 hover:text-white text-xs font-bold rounded-xl transition-all flex items-center gap-1.5">
                <span id="langBtnText">English 🌐</span>
            </button>
            <div id="brandBadge" class="text-xs text-slate-400 font-semibold">Backup Session System</div>
        </div>

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-28 h-28 bg-white border-2 border-emerald-500/40 rounded-full shadow-lg shadow-emerald-600/30 mb-4 overflow-hidden transform hover:scale-105 transition-transform">
                <img src="{{ asset('images/logo.png') }}" alt="EduSpark Logo" class="w-full h-full object-cover scale-110 rounded-full" onerror="this.onerror=null; this.parentElement.innerHTML='<span class=\'text-3xl\'>⚡</span>';">
            </div>
            <h1 class="text-2xl font-black text-white tracking-wide">EduSpark</h1>
            <p class="text-xs font-bold text-emerald-400 mt-1 tracking-wide">• Your first step in self-learning starts here </p>
            <p class="text-[11px] text-slate-400 mt-0.5 font-medium">Backup Session System</p>
        </div>

        <div class="grid grid-cols-2 gap-2 p-1 bg-slate-800/80 rounded-2xl mb-6 border border-slate-700/50">
            <button type="button" id="tabStudent" onclick="switchRole('student')" class="py-2.5 text-sm font-bold rounded-xl transition-all duration-300 bg-emerald-600 text-white shadow-md">
                <span id="studentTabText">طالب</span>
            </button>
            <button type="button" id="tabTeacher" onclick="switchRole('teacher')" class="py-2.5 text-sm font-bold rounded-xl transition-all duration-300 text-slate-400 hover:text-white">
                <span id="teacherTabText">معلم</span>
            </button>
        </div>

        <form action="{{ route('session.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="role" id="selectedRole" value="student">
            <!-- Hidden input to pass the validation check for session_date -->
            <input type="hidden" name="session_date" id="hiddenSessionDate">

            <div>
                <label id="classCodeLabel" class="block text-xs font-semibold text-slate-300 mb-1.5 uppercase tracking-wider">كود الفصل</label>
                <input type="text" name="class_code" id="classCode" required placeholder="مثال: IT-201 أو CS-302" 
                    class="w-full px-4 py-3 bg-slate-800/60 border border-slate-600 rounded-2xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all uppercase">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5 uppercase tracking-wider" id="nameLabel">اسم الطالب بالكامل</label>
                <input type="text" name="full_student_name" id="userName" required placeholder="اكتب اسمك الثلاثي" 
                    class="w-full px-4 py-3 bg-slate-800/60 border border-slate-600 rounded-2xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all">
            </div>

            <div>
                <label id="dateLabel" class="block text-xs font-semibold text-slate-300 mb-1.5 uppercase tracking-wider">تاريخ اليوم (محسوب تلقائياً)</label>
                <input type="date" id="sessionDate" readonly 
                    class="w-full px-4 py-3 bg-slate-900/90 border border-slate-700 rounded-2xl text-slate-400 text-sm cursor-not-allowed focus:outline-none transition-all">
            </div>

            <button type="submit" class="w-full mt-2 py-3.5 px-4 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-500 hover:to-emerald-600 text-white font-bold rounded-2xl shadow-lg shadow-emerald-600/30 transition-all duration-300 transform active:scale-95 flex items-center justify-center">
                <span id="submitBtnText">دخول للحصة التفاعلية</span>
            </button>
        </form>

        <div class="mt-6 text-center">
            <p id="footerNoteText" class="text-xs text-slate-400 font-medium">نظام للحصة التفاعليه • Backup Session System</p>
        </div>
    </div>

    <script>
        let currentLang = localStorage.getItem('eduspark_lang') || 'ar';

        const translations = {
            ar: {
                dir: 'rtl',
                title: 'EduSpark - نظام الحصص الاحتياطي',
                studentTab: 'طالب',
                teacherTab: 'معلم',
                classCodeLabel: 'كود الفصل',
                classCodePlaceholder: 'مثال: IT-201 أو CS-302',
                nameLabelStudent: 'اسم الطالب بالكامل',
                namePlaceholderStudent: 'اكتب اسمك الثلاثي',
                nameLabelTeacher: 'اسم المعلم',
                namePlaceholderTeacher: 'اكتب اسمك المهني',
                dateLabel: 'تاريخ اليوم (محسوب تلقائياً)',
                submitBtn: 'دخول للحصة التفاعلية',
                langBtn: 'English 🌐',
                brandBadge: 'Backup session System',
                footerNote: 'نظام للحصة التفاعليه في المدارس • Backup Session System'
            },
            en: {
                dir: 'ltr',
                title: 'EduSpark - Backup Session System',
                studentTab: 'Student',
                teacherTab: 'Teacher',
                classCodeLabel: 'Class Code',
                classCodePlaceholder: 'e.g., IT-201 or CS-302',
                nameLabelStudent: 'Full Student Name',
                namePlaceholderStudent: 'Enter your full name',
                nameLabelTeacher: 'Teacher Name',
                namePlaceholderTeacher: 'Enter your professional name',
                dateLabel: 'Session Date (Auto-calculated)',
                submitBtn: 'Enter Interactive Session',
                langBtn: 'عربي 🌐',
                brandBadge: 'Backup session System',
                footerNote: 'Interactive Session System in Schools • Backup Session System'
            }
        };

        function applyLanguage(lang) {
            currentLang = lang;
            localStorage.setItem('eduspark_lang', lang);
            const t = translations[lang];

            const htmlRoot = document.getElementById('htmlRoot');
            if(htmlRoot) {
                htmlRoot.setAttribute('lang', lang);
                htmlRoot.setAttribute('dir', t.dir);
            }
            const pageTitle = document.getElementById('pageTitle');
            if(pageTitle) pageTitle.innerText = t.title;
            const studentTabText = document.getElementById('studentTabText');
            if(studentTabText) studentTabText.innerText = t.studentTab;
            const teacherTabText = document.getElementById('teacherTabText');
            if(teacherTabText) teacherTabText.innerText = t.teacherTab;
            const classCodeLabel = document.getElementById('classCodeLabel');
            if(classCodeLabel) classCodeLabel.innerText = t.classCodeLabel;
            const classCode = document.getElementById('classCode');
            if(classCode) classCode.placeholder = t.classCodePlaceholder;
            const dateLabel = document.getElementById('dateLabel');
            if(dateLabel) dateLabel.innerText = t.dateLabel;
            const submitBtnText = document.getElementById('submitBtnText');
            if(submitBtnText) submitBtnText.innerText = t.submitBtn;
            const langBtnText = document.getElementById('langBtnText');
            if(langBtnText) langBtnText.innerText = t.langBtn;
            const brandBadge = document.getElementById('brandBadge');
            if(brandBadge) brandBadge.innerText = t.brandBadge;
            const footerNoteText = document.getElementById('footerNoteText');
            if(footerNoteText) footerNoteText.innerText = t.footerNote;

            const selectedRoleInput = document.getElementById('selectedRole');
            if(selectedRoleInput) {
                const role = selectedRoleInput.value;
                updateNameFields(role);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const today = new Date().toISOString().split('T')[0];
            const sessionDate = document.getElementById('sessionDate');
            const hiddenSessionDate = document.getElementById('hiddenSessionDate');
            
            if(sessionDate) sessionDate.value = today;
            if(hiddenSessionDate) hiddenSessionDate.value = today;

            applyLanguage(currentLang);
        });

        function toggleLanguage() {
    const newLang = currentLang === 'ar' ? 'en' : 'ar';
    
    // إرسال طلب للسيرفر لتخزين اللغة الجديدة في الـ Session الخاصة بـ Laravel
    fetch('/set-locale', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ locale: newLang })
    })
    .then(response => response.json())
    .then(data => {
        // تحديث المتصفح وإعادة تحميل الصفحة لتطبيق اللغة الجديدة في كل مكان
        localStorage.setItem('eduspark_lang', newLang);
        location.reload();
    })
    .catch(error => {
        // في حال فشل الاتصال بالسيرفر، يتم التغيير محلياً على الأقل
        localStorage.setItem('eduspark_lang', newLang);
        applyLanguage(newLang);
    });
}

        function switchRole(role) {
            const tabStudent = document.getElementById('tabStudent');
            const tabTeacher = document.getElementById('tabTeacher');
            const selectedRoleInput = document.getElementById('selectedRole');

            selectedRoleInput.value = role;

            if (role === 'student') {
                tabStudent.className = "py-2.5 text-sm font-bold rounded-xl transition-all duration-300 bg-emerald-600 text-white shadow-md";
                tabTeacher.className = "py-2.5 text-sm font-bold rounded-xl transition-all duration-300 text-slate-400 hover:text-white";
            } else {
                tabTeacher.className = "py-2.5 text-sm font-bold rounded-xl transition-all duration-300 bg-amber-500 text-slate-950 shadow-md font-black";
                tabStudent.className = "py-2.5 text-sm font-bold rounded-xl transition-all duration-300 text-slate-400 hover:text-white";
            }
            updateNameFields(role);
        }

        function updateNameFields(role) {
            const t = translations[currentLang];
            const nameLabel = document.getElementById('nameLabel');
            const userNameInput = document.getElementById('userName');

            if(!nameLabel || !userNameInput) return;

            if (role === 'student') {
                nameLabel.innerText = t.nameLabelStudent;
                userNameInput.placeholder = t.namePlaceholderStudent;
            } else {
                nameLabel.innerText = t.nameLabelTeacher;
                userNameInput.placeholder = t.namePlaceholderTeacher;
            }
        }
    </script>
</body>
</html>