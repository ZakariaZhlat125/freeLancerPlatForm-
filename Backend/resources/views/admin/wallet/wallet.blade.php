@extends('admin.master_layout')
@section('content')
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>

    <div class="page-heading">
        <h3>{{ __('dash.user_Statistics') }} </h3>
    </div>

    <div class="page-content">

        @if ($messge)
            <section class="row mx-4">
                <div class="col-12 col-lg-10 mx-auto">
                    <div class="row m-auto ">
                        {{-- <div class="col-12 col-xl-4 mx-4"> --}}
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-12">
                                            <h3 class="mb-0 text-primary-pink font-bold font-lg">${{ $messge }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {{-- </div> --}}

                    </div>

            </section>
        @else
            <section class="row mx-4">
                <div class="col-12 col-lg-10 mx-auto">
                    <div class="row m-auto ">
                        <div class="col-12 col-xl-4 mx-4">
                            <div class="card">
                                <div class="card-header">
                                    <h3>
                                        {{ __('static.user_total_balance') }}
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-12">
                                            <h3 class="mb-0 text-primary-pink font-bold font-lg">${{ $balance }}
                                                </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-xl-4 mx-4">
                            <div class="card">
                                <div class="card-header">
                                    <h3>
                                        {{ __('static.user_fees') }}
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-12">
                                            <h3 class="mb-0 text-primary-pink font-bold font-lg">${{ $fee }}
                                                </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </section>
            <section class="section">
                <div class="row" id="table-head">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content">
                                <!-- table head dark -->
                                <div class="table-responsive py-2" x-data='{deposit: true , withdraw : false }'>
                                    <div class="w-full flex justify-end ">
                                        <button
                                            :class="{ ' text-primary-pink font-bold': deposit == true }
                                            'font-sm mx-2'"
                                            @click="deposit = true ; withdraw = false">
                                            {{ __('static.deposit_wallet') }}
                                        </button>
                                        <span class="font-bold mx-3">|</span>
                                        <button
                                            :class="{ ' text-primary-pink font-bold': withdraw == true }
                                            'font-sm mx-2'"
                                            @click="deposit = false ; withdraw = true">
                                            {{ __('static.withdraw_wallet') }}
                                        </button>
                                    </div>
                                    <table class="table border">
                                        <thead>
                                            <tr>
                                                <th class="font-md">
                                                    {{ __('static.transation_number') }}
                                                </th>
                                                <th class="font-md">
                                                    {{ __('static.transation_type') }}
                                                </th>
                                                {{-- <th class="font-md">المستلم</th> --}}
                                                <th class="font-md">
                                                    {{ __('static.transation_amount') }}
                                                </th>
                                                <th class="font-md">
                                                    {{ __('static.transation_project') }}
                                                </th>
                                                <th class="font-md">
                                                    {{ __('static.transation_date') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-light">
                                            @foreach ($deposit as $tran)
                                                <tr x-show=' deposit'>
                                                    <td class="font-md">{{ $loop->iteration }}</td>
                                                    <td class="font-md">{{ $tran->name }}</td>
                                                    <td class="font-md">{{ $tran->dep_amount }}</td>
                                                    {{-- <td class="font-md">{{ $tran->with_amount }}</td> --}}
                                                    <td class="font-md">{{ $tran->title }}</td>
                                                    <td class="font-md">{{ $tran->created_at }}</td>
                                                    {{-- <td class="font-md">{{ $item->meta['seeker_id'] }}</td> --}}
                                                    {{-- <td class="font-md">{{ $item->meta['project_id'] }}</td> --}}
                                                </tr>
                                            @endforeach

                                            @foreach ($withdraw as $tran)
                                                <tr x-show='withdraw'>
                                                    <td class="font-md">{{ $loop->iteration }}</td>
                                                    <td class="font-md">{{ $tran->name }}</td>
                                                    <td class="font-md">{{ $tran->with_amount }}</td>
                                                    {{-- <td class="font-md">{{ $tran->with_amount }}</td> --}}
                                                    <td class="font-md">{{ $tran->title }}</td>
                                                    <td class="font-md">{{ $tran->created_at }}</td>
                                                    {{-- <td class="font-md">{{ $item->meta['seeker_id'] }}</td> --}}
                                                    {{-- <td class="font-md">{{ $item->meta['project_id'] }}</td> --}}
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
        @endif ($messge)







    </div>
@endsection
