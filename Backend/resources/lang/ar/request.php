<?php

return [
    'field.required' => 'هذا الحقل مطلوب.',
    // comment controller
    'duration.required' => 'حقل المده مطلوب',
    'duration.numeric' => 'يجب ان يكون حق المده من نوع رقمي',
    'duration.gt' => 'يجب ان يكون حقل المده اكبر من صفر',

    // personal information
    'name.required' => 'رجاء قم بأدخال الاسم',
    'name.min' => 'يجب أن يكون الاسم أكثر من 8 أحرف.',
    'gender.required' => 'هذا الحقل مطلوب.',
    'country.required' => 'ادخل الدولة.',
    'avatar.required' => 'أضف صورة.',
    'avatar.image' => 'الصيغة غير صحيحة.',
    'avatar.mimes' => 'نوع الصورة يجب أن يكون jpg أو png أو jpeg أو gif أو svg.',
    'mobile.required' => 'ادخل رقم الهاتف.',
    'mobile.regex' => 'ادخل صيغة رقم صحيحة.',
    'mobile.min' => 'يجب أن يكون الرقم أكبر من 8 أرقام.',

    // email
    'email.required' => 'رجاء قم بأدخال الايميل',
    'email.email' => 'ادخل الايميل بشكل صحيح',
    'email.unique' => 'الايميل موجود مسبقا',

    // password
    'password.required' => 'ادخل كلمة السر',
    'password.min' => 'يجب ام تكون كلمة السر اكثر من 8 خانات',
    'password.max' => 'يجب ام تكون كلمة السر اقل من 20 خانات',
    'password.regex' => 'كلمه المرور يجيب ان تحتوي على حروف كبيره وصغيره وارقام اورموز ',
    'confirm_pass.same' => 'كلمة السر غير متطابقة ',
    'old_password.required' => 'ادخل كلمة السر القديمة ',
    'new_password.confirmed' => 'الكلمة ليست متطابقة',
    'new_password.required' => 'ادخل كلمة السر الجديدة',
    // messges
    'message.required' => 'رجاء قم بأدخال رسالتك',
    'details.required' => 'اضف تفاصيل للعرض',
    'message.min' => 'حقل الوصف يجب ان يحتوي على 255 حرف على الاقل',


    // works
    'works.title' => 'يجب ان تقوم بأدخال عنوان لعملك',
    'works.comple_date' => 'رجاء ادخل تاريخ الانجاز ',
    'works.details' => 'اضف وصف للعمل',

    'category.required' => 'رجاء ادخل القسم ',
    'title.min' => 'يجب ان يحتوي العنوان على 15 حرف على الاقل',
    'title.max' => 'يجب ان يحتوي العنوان على 35 حرف على الاكثر',
    // project
    'project.amount' => 'المبلغ المتفق عليه مطلوب *',
    'project.title.required' => 'يجب ان تقوم بأدخال عنوان للمشروع',
    'cost.required' => 'رجاء قم بأدخال التكلفه لهذا المشروع',
    'project.details' => 'اضف وصف للمشروع',

    // skill
    'skill.name.required' => 'ادخل اسم المهارة',
    'skill.name.max' => 'يجب ان يكون الاسم اقل من 25 حروف',


];
