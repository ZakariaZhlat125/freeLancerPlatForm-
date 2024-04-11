@extends('client.master_layout')

@section('content')

    @if (!$data->isEmpty())
        @foreach ($data as $item)
            <main class="container pt-20">
                <div class="row mx-1 my-3 col-12 d-flex justify-content-lg-between ">
                    <nav aria-label="breadcrumb" class="main-breadcrumb col-6 p-3">
                        <h3 class="m-5 font-xl font-bold">
                            {{ __('static.control_pannel') }}
                        </h3>
                    </nav>
                    <div class="col-6 mt-8">
                        <a href="{{ route('userProfile', Auth::user()->id) }}"
                            class="mo-btn btn-blue-bg float-start font-md"><i class="fa fa-user p-1"></i>
                            {{ __('static.my_profile') }}
                        </a>
                    </div>
                </div>

                <div class="row">
                    @include('client.components.dash_nav')

                    <section class="col-lg-8 col-md-8 col-12" id="perso">
                        <div class="card shadow-sm ">
                            <div class="card-body">
                                <form action="{{ route('profile_save') }}" method="POST" class="login-form"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0 font-md">{{ __('static.profile.type') }}</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <div class="mx-2 my-2 px-2">
                                                @role('seeker')
                                                    <strong>{{ __('static.seeker_role') }}</strong>
                                                    (
                                                        {{ __('static.looking_for_freelancers') }}
                                                    )
                                                @endrole
                                                @role('provider')
                                                    <strong>{{ __('static.provider_role') }}</strong>
                                                    (
                                                        {{ __('static.looking_for_projects') }}
                                                    )
                                                    <input class="form-check-input mx-2" type="checkbox" name="hire_me"
                                                        {{ $item->hire_me ? 'checked' : '' }}>
                                                    <strong>{{ __('profile.person21') }}</strong>
                                                    (
                                                        {{ __('static.remove_sign') }}
                                                    )
                                                @endrole
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="" class="col-md-6 col-form-label font-md">
                                                {{ __('static.category') }}</label>
                                            <select
                                                class="appearance-none block w-full bg-sacondary-light-white-pinky border-primary-light-pink border-sm text-gray-700 border border-red-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-primary-pink"
                                                name="category_id" data-actions-box="true">
                                                @foreach ($categories as $cate)
                                                    <option value="{{ $cate->id }}">{{ $cate->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="" class="col-md-6 col-form-label font-md">
                                                {{ __('static.job_title') }}</label>
                                            <div class="col-sm-10">
                                                <input type="text"
                                                    class="appearance-none block w-full bg-sacondary-light-white-pinky border-primary-light-pink border-sm text-gray-700 border border-red-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-primary-pink"
                                                    id="" name="job_title" value="{{ $item->job_title }}">
                                            </div>
                                            @error('job_title')
                                                <span class="text-danger w-100">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <label for="" class="col-md-6 col-form-label font-md">
                                            {{ __('static.bio') }}</label>
                                        <textarea
                                            class="appearance-none block w-full bg-sacondary-light-white-pinky border-primary-light-pink border-sm text-gray-700 border border-red-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-primary-pink font-md"
                                            placeholder=" {{ __('static.bio') }}" id="" name="bio">{{ $item->bio }}</textarea>
                                        @error('bio')
                                            <span class="text-danger w-100">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="block border-top mt-5 w-full"></div>

                                    <div class="row mt-4">
                                        <label for="" class="col-md-12 col-form-label font-md">
                                            {{ __('static.video') }}</label>
                                        <input type="url"
                                            class="appearance-none block w-full bg-sacondary-light-white-pinky border-primary-light-pink border-sm text-gray-700 border border-red-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-primary-pink"
                                            id="" name="video" value="{{ $item->video }}">
                                        @error('video')
                                            <span class="text-danger w-100">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <button class="mo-btn btn-blue-bg float-left font-md" type="submit">
                                            {{ __('static.save_button') }}
                                        </button>
                                    </div>

                                    {{ csrf_field() }}
                                </form>
                            </div>
                        </div>
                    </section>
                </div>
            </main>
        @endforeach
    @else
        <main class="container pt-20">
            <div class="row mx-1 my-3 col-12 d-flex justify-content-lg-between ">
                <nav aria-label="breadcrumb" class="main-breadcrumb col-6 p-3">
                    <h3 class="m-5 font-xl font-bold">
                        {{ __('static.control_pannel') }}
                    </h3>
                </nav>
                <div class="col-6 mt-8">
                    <a href="{{ route('userProfile', Auth::user()->id) }}"
                        class="mo-btn btn-blue-bg float-start font-md"><i class="fa fa-user p-1"></i>
                        {{ __('static.my_profile') }}
                    </a>
                </div>
            </div>

            <div class="row">
                {{-- @include('client.components.dash_nav') --}}

                <section class="col-lg-8 col-md-8 col-12" id="perso">
                    <div class="card shadow-sm ">
                        <div class="card-body">
                            {{-- <p>{{ $data }}</p> --}}
                            <form action="{{ route('profile_save') }}" method="POST" class="login-form"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0 font-md">{{ __('static.profile.type') }}</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <div class="mx-2 my-2 px-2">
                                            @role('seeker')
                                                <strong>{{ __('static.seeker_role') }}</strong>
                                                (
                                                        {{ __('static.looking_for_freelancers') }}
                                                )
                                            @endrole
                                            @role('provider')
                                                <strong>{{ __('static.provider_role') }}</strong>
                                                (
                                                        {{ __('static.looking_for_projects') }}
                                                )
                                                <input class="form-check-input mx-2" type="checkbox" name="hire_me"
                                                    {{ $item->hire_me ? 'checked' : '' }}>
                                                <strong>{{ __('profile.person21') }}</strong>
                                                (
                                                        {{ __('static.remove_sign') }}
                                                )
                                            @endrole
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="" class="col-md-6 col-form-label font-md">
                                            {{ __('static.category') }}</label>
                                        <select
                                            class="appearance-none block w-full bg-sacondary-light-white-pinky border-primary-light-pink border-sm text-gray-700 border border-red-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-primary-pink"
                                            name="category_id" data-actions-box="true">
                                            @foreach ($categories as $cate)
                                                <option value="{{ $cate->id }}">{{ $cate->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="" class="col-md-6 col-form-label font-md">
                                            {{ __('static.job_title') }}</label>
                                        <div class="col-sm-10">
                                            <input type="text"
                                                class="appearance-none block w-full bg-sacondary-light-white-pinky border-primary-light-pink border-sm text-gray-700 border border-red-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-primary-pink"
                                                id="" name="job_title" value="">
                                        </div>
                                        @error('job_title')
                                            <span class="text-danger w-100">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <label for="" class="col-md-6 col-form-label font-md">
                                        {{ __('static.bio') }}</label>
                                    <textarea
                                        class="appearance-none block w-full bg-sacondary-light-white-pinky border-primary-light-pink border-sm text-gray-700 border border-red-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-primary-pink font-md"
                                        placeholder=" {{ __('static.bio') }}" id="" name="bio">Your bio</textarea>
                                    @error('bio')
                                        <span class="text-danger w-100">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="block border-top mt-5 w-full"></div>

                                <div class="row mt-4">
                                    <label for="" class="col-md-12 col-form-label font-md">
                                        {{ __('static.video') }}</label>
                                    <input type="url"
                                        class="appearance-none block w-full bg-sacondary-light-white-pinky border-primary-light-pink border-sm text-gray-700 border border-red-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-primary-pink"
                                        id="" name="video" value="">
                                    @error('video')
                                        <span class="text-danger w-100">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <button class="mo-btn btn-blue-bg float-left font-md" type="submit">
                                        {{ __('static.save_button') }}
                                    </button>
                                </div>

                                {{ csrf_field() }}
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    @endif

@endsection
