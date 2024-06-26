<div class="card">
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>
                            {{ __('static.username') }}
                        </th>
                        <th>
                            {{ __('static.form_email') }}
                        </th>



                        <th>
                            {{ __('static.user_state') }}
                        </th>
                        <th>
                            {{ __('static.user_operations') }}
                        </th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>

                            <td>
                                @if ($user->is_active == 1)
                                    <span class="badge bg-label-success me-1">
                                        {{ __('static.user_active') }}
                                    </span>
                                @else
                                    <span class="badge bg-label-danger me-1">
                                        {{ __('static.user_unactive') }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ url('/admin/edit_user') }}" class="btn btn-icon btn-outline-dribbble">
                                    <i class="tf-icons bx bx-edit-alt me-1"></i>
                                </a>

                                <form action="{{ url('add_user') }}" method="POST" style="display: inline-block;">
                                    <button type="submit" class="btn btn-icon btn-outline-dribbble">
                                        @if ($user->is_active == 1)
                                            <i class="tf-icons bx bx-trash me-1"></i>
                                        @else
                                            <i class="tf-icons bx bx-refresh me-1"></i>
                                        @endif
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>
<!--/ Bordered Table -->
