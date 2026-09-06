<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        // تفريغ الجدول قبل الإضافة لمنع تكرار البيانات
        Activity::truncate();

        $activities = [
            // 1. قسم البرمجة - وضع فردي (individual)
            [
                'category' => 'coding',
                'mode' => 'individual',
                'title' => [
                    'ar' => 'تحدي تتبع أخطاء الأكواد',
                    'en' => 'Code Debugging Challenge'
                ],
                'description' => [
                    'ar' => 'لديك كود JavaScript بسيط لحساب مجموع أرقام في مصفوفة، ولكن يوجد خطأ منطقي. اختر الإصلاح الصحيح:',
                    'en' => 'You have a simple JavaScript code to sum array numbers, but there is a logical error. Choose the correct fix:'
                ],
                'options' => [
                    ['ar' => 'تغيير شرط الحلقة إلى i < arr.length', 'en' => 'Change loop condition to i < arr.length'],
                    ['ar' => 'تغيير let sum = 0 إلى 1', 'en' => 'Change let sum = 0 to 1'],
                    ['ar' => 'استخدام دالة map فقط', 'en' => 'Use map function only']
                ],
                'correct_option' => 0,
                'badge' => 'تكنولوجيا وبرمجية',
                'points' => 10
            ],
            // 2. قسم البرمجة - وضع جماعي (team)
            [
                'category' => 'coding',
                'mode' => 'team',
                'title' => [
                    'ar' => 'هيكلة التطبيق الجماعية',
                    'en' => 'Team App Architecture'
                ],
                'description' => [
                    'ar' => 'تعاون مع مجموعتك لتصميم هيكل المكونات لصفحة لوحة تحكم مستخدم باستخدام Next.js.',
                    'en' => 'Collaborate with your team to design component structures for a dashboard page using Next.js.'
                ],
                'options' => [
                    ['ar' => 'تقسيم المكونات إلى مجلدات منظمة ومشاركة المهام', 'en' => 'Divide components into organized folders and share tasks'],
                    ['ar' => 'وضع كل الكود في ملف واحد رئيسي', 'en' => 'Put all code in a single main file'],
                    ['ar' => 'إهمال التصميم والبدء عشوائياً', 'en' => 'Ignore design and start randomly']
                ],
                'correct_option' => 0,
                'badge' => 'تكنولوجيا وبرمجية',
                'points' => 15
            ],
            // 3. قسم ريادة الأعمال - وضع فردي (individual)
            [
                'category' => 'entrepreneurship',
                'mode' => 'individual',
                'title' => [
                    'ar' => 'تحديد الحد الأدنى للمنتج (MVP)',
                    'en' => 'Defining the MVP'
                ],
                'description' => [
                    'ar' => 'لماذا يُعد بناء الحد الأدنى للمنتج القابل للاستخدام خطوة أولى حرجة قبل استثمار ميزانية ضخمة؟',
                    'en' => 'Why is building a Minimum Viable Product a critical first step before investing a massive budget?'
                ],
                'options' => [
                    ['ar' => 'لاختبار الفكرة في السوق وقياس تفاعل العملاء بأقل تكلفة', 'en' => 'To test the market idea and measure customer engagement with minimal cost'],
                    ['ar' => 'لإهدار الوقت فقط', 'en' => 'To waste time only'],
                    ['ar' => 'لزيادة عدد السيرفرات', 'en' => 'To increase server numbers']
                ],
                'correct_option' => 0,
                'badge' => 'ريادة أعمال',
                'points' => 10
            ],
            // 4. قسم الأساسيات (core بدلاً من math) - وضع فردي (individual)
            [
                'category' => 'core',
                'mode' => 'individual',
                'title' => [
                    'ar' => 'خوارزمية البحث الثنائي الأساسية',
                    'en' => 'Core Binary Search Algorithm'
                ],
                'description' => [
                    'ar' => 'احسب أقصى عدد من الخطوات المطلوبة للبحث الثنائي في مصفوفة مرتبة تحتوي على 64 عنصرًا.',
                    'en' => 'Calculate the maximum number of steps required for binary search in a sorted array of 64 elements.'
                ],
                'options' => [
                    ['ar' => '6 خطوات (لأن 2 أس 6 = 64)', 'en' => '6 steps (because 2 to the power of 6 = 64)'],
                    ['ar' => '64 خطوة كاملة', 'en' => '64 full steps'],
                    ['ar' => '32 خطوة', 'en' => '32 steps']
                ],
                'correct_option' => 0,
                'badge' => 'أساسيات',
                'points' => 10
            ]
        ];

        foreach ($activities as $activity) {
            Activity::create($activity);
        }
    }
}