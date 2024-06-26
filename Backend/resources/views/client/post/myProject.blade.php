@extends('client.master_layout')
@section('content')
    <h3 class="m-5 font-xl font-bold pt-20 flex flex-col justify-start items-start">
        {{ __('static.my_personal_projects') }}
    </h3>
    {{-- updating --}}
    @foreach ($projects as $item)
        {{-- one card --}}
        <div class="  w-12/12 lg:w-9/12 card mt-5 sm:px-16 lg:px-10 " id='{{ $item->project_id }}'>

            <div class="row ">
                <div class="col-sm-6">
                    <a href="{{ route('posts.details', $item->post_id) }}" class="my-5">
                        <p class="font-md"> {{ $item->title }}</p>
                    </a>
                </div>

                <div class="col-sm-6 ">
                    <span class="badge bg-primary-light-pink text-md-center text-black font-md float-start">
                        {{ __('static.project_status') }}
                        @if ($item->status == 'pending')
                            {{ __('static.project_status1') }}
                        @elseif ($item->status == 'at_work' && $item->payment_status == 'unpaid')
                            {{ __('static.project_status2') }}
                        @elseif ($item->status == 'at_work')
                            {{ __('static.project_status3') }}
                        @elseif ($item->status == 'done')
                            {{ __('static.project_status4') }}
                        @elseif ($item->status == 'rejected')
                            {{ __('static.project_status5') }}
                        @elseif ($item->status == 'received')
                            {{ __('static.project_status6') }}
                        @endif
                    </span>
                </div>
            </div>
            <div class="info my-3">
                <div class="rate">
                    <ul class="project__meta list-meta-items d-flex justify-content-start-flex margin-right: -23px;">

                        <li class="text-muted font-sm color-gray-dark">
                            <i class="fa-solid fa-calendar-days ms-1"></i> {{ $item->created_at }}
                        </li>

                        {{-- !need to find the way of build it --}}
                        {{-- <li class="text-muted font-sm color-gray-dark px-3">
                        <time datetime="2022-04-23 12:21:47" title="" itemprop="datePublished" data-toggle="tooltip"
                            data-original-title="2022-04-23 12:21:47">
                            <i class="fa fa-clock-o"></i> منذ
                            دقيقتين
                        </time>
                         </li> --}}

                        <li class="text-muted font-sm color-gray-dark mx-5">
                            <i class="fa-regular fa-clock  ms-1"></i>
                            {{ __('static.estimmed_duration') }} {{ $item->duration }}
                            {{ __('static.estimmed_duration_days') }}
                        </li>

                    </ul>

                </div>

            </div>
            <div class="flex justify-between">
                <div>
                    {{ __('static.project_cost') }}
                    <span class="text-primary-pink font-bold mx-1"> ${{ $item->amount }}</span>

                </div>
                <div class="flex justify-content-end gap-1 margin-right: -23px;">

                    {{-- @if ($item->payment_status == 'unpaid' && $item->status == 'at_work')
                        <a href="{{ route('payment.do', [$item->project_id, $item->seeker_id]) }}"
                            class="mo-btn btn-pink-bg text-white text-gray-700  py-2 px-4 rounded inline-flex items-center">
                            <p class="font-md">
                                 {{ __('static.project_cost_option1') }}
                            </p>
                        </a>
                    @endif --}}
                    @if ($item->payment_status == 'unpaid' && $item->status == 'at_work')
                        <button type="button"
                            class="mo-btn btn-pink-bg text-white text-gray-700 py-2 px-4 rounded inline-flex items-center"
                            data-bs-toggle="modal" data-bs-target="#paymentModal{{ $item->project_id }}">
                            <p class="font-md">{{ __('static.project_cost_option1') }}</p>
                        </button>
                    @endif
                    @if ($item->status == 'done')
                        <a href="{{ route('markAsRecive', [$item->project_id, $item->provider_id]) }}"
                            class="mo-btn btn-pink-bg text-white text-gray-700  py-2 px-4 rounded inline-flex items-center">
                            <p class="font-md">
                                {{ __('static.project_cost_option2') }}
                            </p>
                        </a>
                    @endif
                    {{-- <div class="card--actions hidden-xs   flex justify-content-end gap-1">
                        <a class=" border-2 hover:bg-primary-green flex justify-center items-center border-primary-green p-1 w-10 rounded-md bg-transparent "
                            href="{{ route('editPosts', $item->post_id) }}">
                            <i class="fa-solid fa-pen   text-black text-center"></i>

                        </a>
                        {{-- <a class="border-2 hover:bg-primary-pink
            flex justify-center items-center border-primary-pink p-1 w-10 rounded-md bg-transparent"
                            data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <i class="fa fa-xmark text-center "></i>



                        </a> --}}

                    {{-- </div> --}}
                </div>
            </div>


        </div>
        {{-- end --}}




        {{-- Modal for Payment --}}
        <div class="modal fade" id="paymentModal{{ $item->project_id }}" tabindex="-1"
            aria-labelledby="paymentModalLabel{{ $item->project_id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="paymentModalLabel{{ $item->project_id }}">
                            {{ __('static.payment_title') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="paymentForm{{ $item->project_id }}"
                            action="{{ route('payment.do', [$item->project_id, $item->seeker_id]) }}" method="GET">
                            @csrf
                            <div class="mb-3">
                                <label for="paymentMethod" class="form-label">{{ __('static.payment_method') }}</label>
                                <select class="form-select" id="paymentMethod" name="payment_method" required>
                                    <option value="credit_card">{{ __('static.credit_card') }}</option>
                                    <option value="paypal">{{ __('static.paypal') }}</option>
                                    <option value="bank_transfer">{{ __('static.bank_transfer') }}</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="cardNumber" class="form-label">{{ __('static.card_number') }}</label>
                                <input type="text" class="form-control" id="cardNumber" name="card_number" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="mo-btn btn-blue-bg"
                            data-bs-dismiss="modal">{{ __('static.close') }}</button>
                        <button type="submit" class="mo-btn btn-pink-bg"
                            form="paymentForm{{ $item->project_id }}">{{ __('static.complete_payment') }}</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Button trigger modal -->


        <!-- Modal -->
        <div class="modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            {{ __('static.post_detail_desc3') }}
                        </h5>
                        <a class="fa fa-xmark" data-bs-dismiss="modal" aria-label="Close"></a>
                    </div>
                    <div class="modal-body">
                        {{ __('static.post_detail_desc4') }} {{ $item->title }}
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('toggle_post', $item->post_id) }}" class="mo-btn btn-pink-bg pink">
                            {{ __('static.post_detail_desc5') }}
                        </a>
                        <button type="button" class="mo-btn btn-blue-bg" data-bs-dismiss="modal">
                            {{ __('static.post_detail_desc6') }}
                        </button>
                        {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}

                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
