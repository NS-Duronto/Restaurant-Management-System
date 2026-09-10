@extends('installer.layouts.master')

@section('title', 'সাইট কনফিগারেশন')

@section('container')
    <h2 class="step-title">সাইট কনফিগারেশন</h2>

    <form method="post" action="{{ route('installer.siteStore') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="form-row">
            <label class="form-label">
                {{ trans('installer.site.label.app_name') }} <span class="req">*</span>
            </label>
            <input name="app_name" type="text"
                   value="{{ old('app_name', config('app.name', 'Sohoj RMS - Restaurant Management System')) }}"
                   class="form-input">
            @if ($errors->has('app_name'))
                <div class="form-error">{{ $errors->first('app_name') }}</div>
            @endif
        </div>

        <div class="form-row">
            <label class="form-label">
                {{ trans('installer.site.label.app_url') }} <span class="req">*</span>
            </label>
            <input name="app_url" type="text"
                   value="{{ old('app_url', url('/')) }}"
                   class="form-input">
            @if ($errors->has('app_url'))
                <div class="form-error">{{ $errors->first('app_url') }}</div>
            @endif
        </div>

        <div class="action-footer">
            <a href="{{ route('installer.permission') }}" class="btn-prev">
                <i class="fa-solid fa-arrow-left" style="font-size: 10px;"></i>
                <span>পূর্ববর্তী</span>
            </a>

            <button type="submit" class="btn-submit">
                <span>পরবর্তী</span>
                <i class="fa-solid fa-arrow-right" style="font-size: 11px;"></i>
            </button>
        </div>
    </form>
@endsection
