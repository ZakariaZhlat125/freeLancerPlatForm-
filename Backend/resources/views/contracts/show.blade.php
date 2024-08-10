@extends('client.master_layout')

@section('content')
    <div class="container d-flex justify-content-between mt-20">
        <h3 class="font-xl font-bold">
            {{ __('static.contract_details') }}
        </h3>
        <div class="card--actions hidden-xs">
            <div class="dropdown btn-group">
                <div class="dropdown inline-block relative min-w-fit">
                    <a tabindex="-1"
                        class="mo-btn btn-pink-bg text-white text-gray-700 py-2 px-4 rounded inline-flex items-center"
                        href="{{ route('contracts.index') }}">
                        <i class="fa-solid fa-file-contract font-sm mx-1"></i>
                        <span class="mr-1">
                            {{ __('static.view_all_contracts') }}
                        </span>
                        <svg class="fill-current h-4 w-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path style="color:white; stroke: white; fill: white;"
                                d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                        </svg>
                    </a>

                    <ul class="dropdown-menu w-fit absolute hidden text-dark-gray bg-light-gray rounded-sm shadow-md pt-2">
                        <li class="border-b border-primary-light-pink">
                            <a class="rounded-t bg-gray-200 hover:bg-gray-400 hover:bg-primary-light-gray hover:text-dark-gray py-2 px-4 block whitespace-no-wrap"
                                href="{{ route('contracts.completed') }}">
                                <i class="fa-solid fa-check font-sm px-3"></i>
                                <span class="mr-1">
                                    {{ __('static.completed_contracts') }}
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row d-flex flex-column justify-content-center">
        <div class="mx-lg-5 col-lg-9">
            <div class="container card px-3 my-3">
                <div class="row">
                    <div class="col-sm-6">
                        <p class="font-md"> {{ $contract->title }}</p>
                    </div>
                    <div class="col-sm-6">
                        <div class="card--actions hidden-xs flex-wrap float-start">
                            <div class="dropdown btn-group">
                                @if ($contract->status == 'signed')
                                    <button tabindex="-1" class="mo-btn btn-blue-bg cursor-pointer">
                                        <i class="fa-regular fa-paper-plane"></i>
                                        <span class="action-text">
                                            {{ __('static.contract_signed') }}
                                        </span>
                                    </button>
                                @else
                                    <button tabindex="-1" class="mo-btn btn-pink-bg cursor-pointer" data-bs-toggle="modal"
                                        data-bs-target="#signContractModal">
                                        <i class="fa-solid fa-pen"></i>
                                        <span class="action-text">
                                            {{ __('static.sign_contract') }}
                                        </span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="info mx-0">
                    <div class="rate">
                        <ul class="project__meta list-meta-items d-flex justify-content-start-flex margin-right: -23px;">
                            <li class="font-md color-gray-dark">
                                <i class="fa-regular fa-money-bill-1"></i>
                                <span style="color: red">${{ $contract->amount }}</span>
                            </li>
                            <li class="text-muted font-md mx-3 color-gray-dark">
                                <i class="fa-regular fa-calendar"></i>
                                <span>{{ $contract->created_at }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Modal for Signing Contract -->
                <div class="modal fade" id="signContractModal" tabindex="-1" aria-labelledby="signContractModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form class="modal-content" method="POST" action="{{ route('contracts.sign', $contract->id) }}">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title font-lg" id="signContractModalLabel">
                                    {{ __('static.sign_contract') }} - {{ $contract->title }}
                                </h5>
                            </div>
                            <div class="modal-body">
                                <h2 class="font-md">{{ __('static.confirm_signature') }}</h2>
                                <p>{{ __('static.confirm_signature_description') }}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="mo-btn btn-pink-bg pink font-md">
                                    {{ __('static.sign_now') }}
                                </button>
                                <button type="button" class="mo-btn btn-blue-bg font-md" data-bs-dismiss="modal">
                                    {{ __('static.cancel') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
