{{-- 編輯個人資料 --}}
@extends('layouts.app')

@section('title', '編輯 ' . $user->name . ' 的個人資料')

@section('content')
    <div class="container mb-5">
        <div class="d-flex justify-content-center">
            <div class="col-md-6">

                <div class="card shadow">
                    <h5 class="card-header py-3"><i class="fas fa-user-edit"></i> 編輯個人資料</h5>

                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <div class="col-md-11">

                                <form action="{{ route('users.update', $user->id) }}" method="POST" accept-charset="UTF-8">
                                    @method('PUT')
                                    @csrf

                                    <div class="d-flex justify-content-center mb-2">
                                        <div class="p-1">
                                            <img class="thumbnail rounded-circle" src="{{ $user->gravatar('200') }}" alt="圖片連結已失效"  width="200">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center mb-3">
                                        <span>會員大頭貼由</span>
                                        <a class="text-decoration-none"
                                        href="https://zh-tw.gravatar.com/"
                                        target="_blank" rel="nofollow noopener noreferrer">Gravatar</a>
                                        <span>技術提供</span>
                                    </div>

                                    {{-- E-mail --}}
                                    <div class="mb-3">
                                        <input class="form-control"
                                        value="{{ old('email', $user->email) }}"
                                        type="email" name="email" required readonly>
                                    </div>

                                    @error('email')
                                        <div class="mb-3">
                                            <span class="text-danger">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        </div>
                                    @enderror

                                    {{-- 會員名稱 --}}
                                    <div class="form-floating mb-3">
                                        <input class="form-control @error('name') is-invalid @enderror" id="floatingInput" placeholder="name"
                                        type="text" name="name" value="{{ old('name', $user->name) }}" autocomplete="name" required>
                                        <label for="floatingInput">{{ __('Name') }}（請使用英文、數字、橫槓和底線）</label>
                                    </div>

                                    @error('name')
                                        <div class="mb-3">
                                            <span class="text-danger">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        </div>
                                    @enderror

                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" placeholder="introduction"
                                        name="introduction" id="floatingTextarea"
                                        style="height: 150px">{{ old('introduction', $user->introduction) }}</textarea>
                                        <label for="floatingTextarea">個人簡介</label>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary"><i class="far fa-save mr-2" aria-hidden="true"></i> 儲存</button>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
