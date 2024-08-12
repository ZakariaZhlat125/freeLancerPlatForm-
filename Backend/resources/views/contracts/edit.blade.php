@extends('client.master_layout')

@section('content')
    <div class="container d-flex justify-content-between mt-20">
        <h3 class="font-xl font-bold">
            تعديل العقد
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
                <form method="POST" action="{{ route('contracts.update', $contract->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="title">عنوان العقد:</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $contract->title) }}" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="amount">المبلغ:</label>
                                <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount', $contract->amount) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label for="contract_content">محتوى العقد:</label>
                        <textarea class="form-control" id="contract_content" name="contract_content" rows="5" required>{{ old('contract_content', $contract->contract_content) }}</textarea>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="mo-btn btn-pink-bg">
                            حفظ التعديلات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
