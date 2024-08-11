@extends('client.master_layout')

@section('content')
    <div class="container d-flex justify-content-between mt-20">
        <h3 class="font-xl font-bold">
            تفاصيل العقد
        </h3>
        <div class="card--actions hidden-xs">
            <div class="dropdown btn-group">
                <div class="dropdown inline-block relative min-w-fit">
                    <a tabindex="-1"
                        class="mo-btn btn-pink-bg text-white text-gray-700 py-2 px-4 rounded inline-flex items-center"
                        href="{{ route('contracts.index') }}">
                        <i class="fa-solid fa-file-contract font-sm mx-1"></i>
                        <span class="mr-1">
                            كل العقود
                        </span>
                        <svg class="fill-current h-4 w-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path style="color:white; stroke: white; fill: white;"
                                d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                        </svg>
                    </a>
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
                                @if (Auth::id() == $contract->freelancer_id && !$freelancer_signed)
                                    <button tabindex="-1" class="mo-btn btn-pink-bg cursor-pointer" data-bs-toggle="modal"
                                        data-bs-target="#signContractModal{{ $contract->id }}">
                                        <i class="fa-solid fa-pen"></i>
                                        <span class="action-text">
                                            توقيع العقد
                                        </span>
                                    </button>
                                @elseif ($contract->status == 'signed')
                                    <button tabindex="-1" class="mo-btn btn-blue-bg cursor-pointer">
                                        <i class="fa-regular fa-paper-plane"></i>
                                        <span class="action-text">
                                            العقد موقّع
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
                            <li class="font-md color-gray-dark">
                                <i class="fa-solid fa-user"></i>
                                <span>المستقل: {{ $contract->freelancer->name }}</span>
                            </li>
                            <li class="font-md color-gray-dark mx-3">
                                <i class="fa-solid fa-user-tie"></i>
                                <span>صاحب العمل: {{ $contract->seeker->name }}</span>
                            </li>
                            <li class="font-md color-gray-dark">
                                <i class="fa-solid fa-info-circle"></i>
                                <span>الحالة: {{ $contract->status }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="mt-3">
                    <h4 class="font-lg">محتوى العقد:</h4>
                    <p>{{ $contract->contract_content }}</p>
                </div>

                <div class="mt-3">
                    <h4 class="font-lg">التوقيعات:</h4>
                    <ul>
                        <li>توقيع المستقل: {{ $freelancer_signed ? ($freelancer_signature_valid ? 'صالح' : 'غير صالح') : 'غير موقّع بعد' }}</li>
                        <li>توقيع صاحب العمل: {{ $seeker_signed ? ($seeker_signature_valid ? 'صالح' : 'غير صالح') : 'غير موقّع بعد' }}</li>
                        {{-- <li>توقيع المشرف: {{ $admin_signed ? ($admin_signature_valid ? 'صالح' : 'غير صالح') : 'غير موقّع بعد' }}</li> --}}
                    </ul>
                </div>

                <!-- Modal for Signing Contract -->
                <div class="modal fade" id="signContractModal{{ $contract->id }}" tabindex="-1" aria-labelledby="signContractModalLabel{{ $contract->id }}"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form class="modal-content" method="POST" action="{{ route('contracts.sign', $contract->id) }}">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title font-lg" id="signContractModalLabel{{ $contract->id }}">
                                    توقيع العقد - {{ $contract->title }}
                                </h5>
                            </div>
                            <div class="modal-body">
                                <h2 class="font-md">تأكيد التوقيع</h2>
                                <p>هل أنت متأكد أنك تريد توقيع هذا العقد؟</p>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="mo-btn btn-pink-bg pink font-md">
                                    توقيع الآن
                                </button>
                                <button type="button" class="mo-btn btn-blue-bg font-md" data-bs-dismiss="modal">
                                    إلغاء
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
