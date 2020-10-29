{{-- 編輯個人資料 --}}
@extends('layouts.app')

@section('title', '編輯 ' . $user->name . ' 的個人資料')

@section('content')
    <div class="container">
        <div class="col-md-8 offset-md-2">

            <div class="card shadow">
                <div class="card-header">
                    <h4>
                        <i class="glyphicon glyphicon-edit"></i> 編輯個人資料
                    </h4>
                </div>

                <div class="card-body">

                    <form action="{{ route('users.update', $user->id) }}" method="POST" accept-charset="UTF-8" enctype="multipart/form-data">
                        @method('PUT')

                        @csrf

                        @include('shared.error')

                        <div class="form-group">
                            <label for="" class="avatar-label">會員大頭貼由 <a href="https://zh-tw.gravatar.com/" target="_blank" rel="nofollow noopener noreferrer">Gravatar</a> 技術提供</label>
                            <br>
                            <img class="thumbnail rounded-lg" src="{{ $user->gravatar('200') }}" alt="圖片連結已失效"  width="200">
                        </div>

                        <div class="form-group">
                            <label for="name-field">會員名稱（只支持英文、數字、橫槓和底線）</label>
                            <input class="form-control" type="text" name="name" id="name-field" value="{{ old('name', $user->name) }}" />
                        </div>

                        <div class="form-group">
                            <label for="email-field">信 箱</label>
                            <input class="form-control" type="text" name="email" id="email-field" value="{{ old('email', $user->email) }}" disabled/>
                        </div>

                        <div class="form-group">
                            <label for="introduction-field">個人簡介</label>
                            <textarea name="introduction" id="introduction-field" class="form-control" rows="3">{{ old('introduction', $user->introduction) }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary float-right"><i class="far fa-save mr-2" aria-hidden="true"></i>儲存</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
