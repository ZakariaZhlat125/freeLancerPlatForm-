@extends('client.master_layout')
@section('content')
    <style>
        .bootstrap-select>.dropdown-toggle.bs-placeholder,
        .bootstrap-select>.dropdown-toggle.bs-placeholder:active,
        .bootstrap-select>.dropdown-toggle.bs-placeholder:focus,
        .bootstrap-select>.dropdown-toggle.bs-placeholder:hover,
        /* [type=button]:not(:disabled), */
        /* [type=reset]:not(:disabled), */
        .bootstrap-select>.dropdown-toggle.bs-placeholder:not(:disabled),
        .bootstrap-select>.dropdown-toggle.bs-placeholder:not(:disabled) {
            border: 1px solid #d5dadf;
            border-radius: 5px;
            width: 100%;
            text-align: right;

        }

        .bootstrap-select[class*=col-] .dropdown-toggle {
            width: 675px;
        }

    </style>
    <div class="container mt-20">
        <h3 class="my-5  font-xl font-bold ">
            {{ __('static.post_add') }}
        </h3>
        <div class="row my-5 grid place-items-center">
            <div class="col-lg-8 col-sm-12">
                <div class="card shadow-sm ">
                    <div class="card-body">
                        <form id="contactForm" class="row g-3" action='{{ route('savePost') }}' method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <!-- Name input -->
                            <div class="col-md-6">
                                <label class="form-label font-md" for="name">
                                     {{ __('static.post_title') }}
                                </label>
                                <input
                                    class="appearance-none block w-full bg-sacondary-light-white-pinky border-primary-light-pink border-sm text-gray-700 border border-red-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-primary-pink"
                                    name='title' id="name" value="{{ old('title') }}" type="text"
                                    data-sb-validations="required" required />
                                <p class="text-muted font-xs">
                                    {{ __('static.post_add_title') }}
                                </p>
                                @error('title')
                                    <div id='alert ' class="   px-4 alert position-fixed  alert-warning" role="alert"
                                        style="width: fit-content; position: fixed; top: 20% ; right: 0px ; z-index: 9999999">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- category --}}
                            <div class=" pt-2 col-md-6">
                                <div class="form-group  ">
                                    <label class="font-md">
                                        {{ __('static.post_add_category') }}
                                    </label>
                                    <select
                                        class="appearance-none block w-full bg-sacondary-light-white-pinky border-primary-light-pink border-sm text-gray-700 border border-red-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-primary-pink"
                                        aria-label="Default select example" name="category" value="{{ old('category') }}"
                                        required="required">
                                        @foreach ($categories as $cate)
                                            <option value="{{ $cate->id }}">{{ $cate->title }}</option>
                                        @endforeach

                                    </select>
                                    <p class="text-muted font-xs">
                                        {{ __('static.post_add_budget') }}
                                    </p>
                                </div>
                                @error('category')
                                    <div id='alert ' class="   px-4 alert position-fixed  alert-warning" role="alert"
                                        style="width: fit-content; position: fixed; top: 20% ; right: 0px ; z-index: 9999999">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- skills --}}
                            <div class="col-12">
                                <label for="" class="col-md-6 col-form-label font-md" style="">
                                    {{ __('filter.skills') }}</label>
                                <select class="selectpicker col-12 w-full " name="skills[]" multiple
                                    style="width: 100% ; border:1px solid gray ;border-radius: 5px"
                                    aria-label="size 2 select example" data-actions-box="true">
                                    @foreach ($skills as $item)
                                        <option id='skills' value="{{ $item->id }}" autocomplete="off">
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                                <p class="text-muted font-xs">
                                    {{ __('static.post_add_required_skills') }}
                                </p>
                            </div>



                            <!-- Message input -->
                            <div>
                                <label class="form-label font-md" for="message">
                                    {{ __('static.post_detail_desc7') }}
                                </label>
                                <textarea class="appearance-none block w-full bg-sacondary-light-white-pinky border-primary-light-pink border-sm text-gray-700 border border-red-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-primary-pink"
                                    name='message' id="message" type="text" style="height: 10rem;"
                                    data-sb-validations="required" required>{{ old('message') }}</textarea>
                                <p class="text-muted font-xs">
                                    {{ __('static.post_detail_desc14') }}
                                </p>
                                @error('message')
                                    <div id='alert ' class="   px-4 alert position-fixed  alert-warning" role="alert"
                                        style="width: fit-content; position: fixed; top: 20% ; right: 0px ; z-index: 9999999">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- the cost --}}
                            <div class="col-sm-6 col-xs-12 pt-3">
                                <div class="form-group  ">
                                    <label class="font-md">
                                        {{ __('static.post_estimeed_budget') }}
                                    </label>
                                    <select
                                        class="appearance-none block w-full bg-sacondary-light-white-pinky border-primary-light-pink border-sm text-gray-700 border border-red-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-primary-pink"
                                        aria-label="Default select example" name="cost" value="{{ old('cost') }}"
                                        required="required">
                                        <option value="0-25"> 0 -15 $</option>
                                        <option value="50-25">50-25 $</option>
                                        <option value="100-50">50-100 $</option>
                                        <option value="250-100">250-100 $</option>
                                        <option value="500-250">500-250 $</option>
                                        <option value="1000-500">1000-500 $</option>
                                        <option value="2500-5000">2500-5000 $</option>
                                        <option value="5000-10000">5000-10000 $</option>


                                    </select>
                                    <p class="text-muted font-xs">
                                        {{ __('static.post_add_budget') }}
                                    </p>
                                </div>
                                @error('cost')
                                    <div id='alert ' class="   px-4 alert position-fixed  alert-warning" role="alert"
                                        style="width: fit-content; position: fixed; top: 20% ; right: 0px ; z-index: 9999999">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>


                            {{-- duration --}}
                            <div class="col-sm-6 col-xs-12 pt-3 font-md">
                                <label class="font-md">
                                    {{ __('static.post_detail_desc12') }}
                                </label>
                                <div class="input-group ">

                                    {{-- <input name="duration" class='appearance-none  border-primary-light-pink border-sm text-gray-700 border border-red-500 rounded py-3  mb-3 leading-tight focus:outline-none focus:bg-white focus:border-primary-pink' id="phone" type="number"
                                        value="{{ old('duration') }}" aria-label="Username"
                                        aria-describedby="basic-addon1">
                                    {{-- <span class="input-group-text" style="height: 46px;" id="basic-addon1">ايام</span> --}}
                                    {{-- <div class="w-8 flex items-center justify-center bg-blue-lighter border-t border-l border-b border-blue-lighter rounded-l text-blue-dark">$</div>
                                    </div> --}}
                                    <div class="flex">
                                        <input type="number"  min="1" name="duration"
                                            class='appearance-none  border-primary-light-pink border-sm text-gray-700 border border-red-500 rounded py-3  mb-3 leading-tight focus:outline-none focus:bg-white focus:border-primary-pink'
                                            id="phone" type="number" value="{{ old('duration') }}"
                                            aria-label="Username" />
                                        <div
                                            class=" flex items-center justify-center appearance-none  border-primary-light-pink border-sm text-gray-700 border border-red-500 rounded py-3  mb-3 leading-tight focus:outline-none focus:bg-white focus:border-primary-pink w-8 bg-primary-light-pink">
                                            {{ __('static.post_detail_desc22') }}
                                        </div>

                                    </div>
                                    <span class="text-muted font-xs">
                                        {{ __('static.post_add_submit_date') }}
                                    </span>
                                    @error('duration')
                                        <div id='alert ' class="   px-4 alert position-fixed  alert-warning" role="alert"
                                            style="width: fit-content; position: fixed; top: 20% ; right: 0px ; z-index: 9999999">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>
                            </div>
                            <div class="mb-1">
                                <label class="font-md " for="message">
                                    {{ __('static.post_detail_desc15') }}
                                </label>
                                <input
                                    class="appearance-none block w-full bg-sacondary-light-white-pinky border-primary-light-pink border-sm text-gray-700 border border-red-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-primary-pink"
                                    id="dropzone" multiple name='files' type="file" value="{{ old('files') }}"
                                    data-sb-validations="required">


                            </div>
                            <div>
                                <button class="mo-btn btn-blue-bg float-left font-md" type="submit">
                                    {{ __('static.post_save_deatils') }}
                                </button>
                            </div>
                            <!-- Form submit button -->
                            {{-- <div class="row">
                                <div class="col-md-8">
                                    <button class="wak_btn " type="submit">انشر الان
                                    </button>
                                </div>
                                <div class="col-md-4">
                                    <button class="wak_btn " type="submit"> حفظ كمسوده
                                    </button>
                                </div>
                            </div> --}}
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                {{-- <h6>أنشر مشروعك على متاح</h6>
                <p>تساعدك منصة متاح على الوصول إلى أفضل
                    المستقلين المحترفين لإنجاز أعمالك عن بعد
                    . بعد إضافة مشروعك على نفذلي ومراجعته ،
                    سيتقدم إليك عدد من العروض من المستقلين
                    المتخصيين يمكنك إختيار العرض
                    الأنسب لك من العروض المقدمة وإعتماده</p>

                <h6> <i class="fa-solid fa-lightbulb p-2 color-green"></i>نصائح إضافة المشروع</h6>
                <ul>
                    <li><i class="fa-duotone fa-caret-left color-green p-1"></i>أدخل تفاصيل المشروع بدقة</li>
                    <li><i class="fa-duotone fa-caret-left color-green p-1"></i>املأ جميع الحقول ووفّر أمثلة لما
                        تريد
                    </li>
                    <li><i class="fa-duotone fa-caret-left color-green p-1"></i>جزّء المشروع على عدّة مراحل صغيرة
                    </li>
                </ul> --}}
            </div>
        </div>
    </div>
@endsection
