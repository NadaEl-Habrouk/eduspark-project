@php
    $currentLang = session('locale', 'ar');
    $isRtl = $currentLang === 'ar';
@endphp
<!DOCTYPE html>
<html lang="{{ $currentLang }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('messages.title'))</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('styles')
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen">

    <!-- زر تغيير اللغة ليظهر في كل الصفحات -->
    <div class="p-4 flex justify-end">
        <button type="button" onclick="toggleLanguage()" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold rounded-xl transition-all">
            {{ $currentLang === 'ar' ? 'English 🌐' : 'عربي 🌐' }}
        </button>
    </div>

    <main>
        @yield('content')
    </main>

    <script>
        function toggleLanguage() {
            const current = "{{ $currentLang }}";
            const newLang = current === 'ar' ? 'en' : 'ar';
            
            fetch('/set-locale', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ locale: newLang })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    location.reload(); // إعادة تحميل الصفحة لتطبيق اللغة الجديدة والاتجاه في كل الموقع
                }
            });
        }
    </script>
    @stack('scripts')
</body>
</html>