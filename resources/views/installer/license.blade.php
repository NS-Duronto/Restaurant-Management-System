@extends('installer.layouts.master')

@section('template_title')
    {{ trans('installer.license.templateTitle') }}
@endsection

@section('step_badge')
    Step 04 / 06 &bull; লাইসেন্স
@endsection

@section('title')
    লাইসেন্স অ্যাক্টিভেশন
@endsection

@section('step_subtitle')
    আপনার কেনা লাইসেন্স কোডটি দিন অথবা পরবর্তী ধাপে এগিয়ে যান।
@endsection

@section('container')
    <form method="post" action="{{ route('installer.licenseStore') }}" class="space-y-6">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">
                {{ trans('installer.license.label.license_code') }} <span class="text-orange-400">*</span>
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 pointer-events-none text-sm">
                    <i class="fa-solid fa-key text-orange-400"></i>
                </span>
                <input name="license_key" type="text" value="{{ old('license_key') }}"
                       placeholder="আপনার লাইসেন্স কোড পেস্ট করুন"
                       class="modern-input w-full h-12 rounded-xl pl-11 pr-4 text-sm font-mono text-white placeholder-slate-500">
            </div>
            @if ($errors->has('license_key'))
                <small class="block mt-2 text-xs font-semibold text-red-400 flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ $errors->first('license_key') }}</span>
                </small>
            @endif
            @if($errors->has('global'))
                <small class="block mt-2 text-xs font-semibold text-red-400 flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ $errors->first('global') }}</span>
                </small>
            @endif
        </div>

        <!-- Navigation Buttons -->
        <div class="flex items-center justify-between pt-4 border-t border-white/10">
            <a href="{{ route('installer.permission') }}" class="btn-secondary px-5 py-2.5 rounded-xl text-xs font-semibold inline-flex items-center gap-2">
                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                <span>পূর্ববর্তী ধাপ</span>
            </a>

            <button type="submit" class="btn-glow px-6 py-3 rounded-xl font-bold text-white text-xs sm:text-sm inline-flex items-center gap-2">
                <span>পরবর্তী ধাপ</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </div>
    </form>
@endsection
