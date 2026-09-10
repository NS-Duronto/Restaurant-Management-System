@extends('installer.layouts.master')

@section('title', 'ডাটাবেজ কনফিগারেশন')

@section('container')
    <h2 class="step-title">ডাটাবেজ কনফিগারেশন</h2>

    @if($errors->has('global'))
        <div class="installer-alert danger">
            {{ $errors->first('global') }}
        </div>
    @endif

    <form method="post" action="{{ route('installer.databaseStore') }}" id="db-form">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="form-grid-host">
            <div class="form-row">
                <label class="form-label">
                    {{ trans('installer.database.label.database_host') }} <span class="req">*</span>
                </label>
                <input name="database_host" type="text"
                       value="{{ old('database_host', '127.0.0.1') }}"
                       class="form-input">
                @if ($errors->has('database_host'))
                    <div class="form-error">{{ $errors->first('database_host') }}</div>
                @endif
            </div>

            <div class="form-row">
                <label class="form-label">
                    {{ trans('installer.database.label.database_port') }} <span class="req">*</span>
                </label>
                <input name="database_port" type="text"
                       value="{{ old('database_port', '3306') }}"
                       class="form-input">
                @if ($errors->has('database_port'))
                    <div class="form-error">{{ $errors->first('database_port') }}</div>
                @endif
            </div>
        </div>

        <div class="form-row">
            <label class="form-label">
                {{ trans('installer.database.label.database_name') }} <span class="req">*</span>
            </label>
            <input name="database_name" type="text"
                   value="{{ old('database_name', env('DB_DATABASE', 'foodking')) }}"
                   class="form-input">
            @if ($errors->has('database_name'))
                <div class="form-error">{{ $errors->first('database_name') }}</div>
            @endif
        </div>

        <div class="form-grid-2">
            <div class="form-row">
                <label class="form-label">
                    {{ trans('installer.database.label.database_username') }} <span class="req">*</span>
                </label>
                <input name="database_username" type="text"
                       value="{{ old('database_username', env('DB_USERNAME', 'root')) }}"
                       class="form-input">
                @if ($errors->has('database_username'))
                    <div class="form-error">{{ $errors->first('database_username') }}</div>
                @endif
            </div>

            <div class="form-row">
                <label class="form-label">
                    {{ trans('installer.database.label.database_password') }}
                </label>
                <input name="database_password" type="password"
                       value="{{ old('database_password', env('DB_PASSWORD')) }}"
                       class="form-input">
                @if ($errors->has('database_password'))
                    <div class="form-error">{{ $errors->first('database_password') }}</div>
                @endif
            </div>
        </div>

        <div class="action-footer">
            <a href="{{ route('installer.site') }}" class="btn-prev">
                <i class="fa-solid fa-arrow-left" style="font-size: 10px;"></i>
                <span>পূর্ববর্তী</span>
            </a>

            <button type="submit" id="submit-btn" class="btn-submit">
                <span id="btn-text">ডাটাবেজ সেটআপ করুন</span>
                <i id="btn-icon" class="fa-solid fa-arrow-right" style="font-size: 11px;"></i>
            </button>
        </div>
    </form>

    <script>
        document.getElementById('db-form').addEventListener('submit', function() {
            const btn = document.getElementById('submit-btn');
            const btnText = document.getElementById('btn-text');
            const btnIcon = document.getElementById('btn-icon');
            btn.disabled = true;
            btnText.innerText = 'সেটআপ হচ্ছে...';
            btnIcon.className = 'fa-solid fa-circle-notch fa-spin';
        });
    </script>
@endsection
