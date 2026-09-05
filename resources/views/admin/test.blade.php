<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('front/css/style.css') }}">
</head>

<body>
    <div style="padding: 40px; text-align: center;">
        <h1>مرحباً بك في لوحة التحكم - SuperFit</h1>
        <p style="margin-top: 10px; color: #666;">اختر من القائمة أدناه للبدء في إدارة النظام:</p>

        <div style="margin-top: 30px;">
            <a href="{{ route('admin.users.manage') }}"
               style="padding: 12px 25px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">
                إدارة المستخدمين
            </a>
        </div>


         <div style="margin-top: 30px;">
            <a href="{{ route('admin.trainer.manage') }}"
               style="padding: 12px 25px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">
                إدارة المدربين
            </a>
        </div>
    </div>
</body>

</html>
