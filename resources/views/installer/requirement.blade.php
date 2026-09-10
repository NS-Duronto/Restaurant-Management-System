@extends('installer.layouts.master')

@section('title', 'সার্ভার রিকোয়ারমেন্ট')

@section('container')
    @php
        $isSupported = (!isset($requirements['errors']) && ($phpSupportInfo['supported'] ?? false));
    @endphp

    <h2 class="step-title">সার্ভার রিকোয়ারমেন্ট</h2>

    <!-- PHP Version -->
    <div style="padding: 12px 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 16px; display: flex; align-items: center; justify-content: space-between;">
        <span style="font-size: 13px; font-weight: 600; color: #334155;">
            PHP সংস্করণ (প্রয়োজন {{ $phpSupportInfo['minimum'] }}+)
        </span>
        <span style="font-size: 12px; font-weight: 700; color: {{ $phpSupportInfo['supported'] ? '#059669' : '#dc2626' }};">
            {{ $phpSupportInfo['current'] }}
            <i class="fa-solid fa-{{ $phpSupportInfo['supported'] ? 'check' : 'xmark' }} ml-1"></i>
        </span>
    </div>

    <!-- Extensions list -->
    <div style="border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; margin-bottom: 20px;">
        @foreach($requirements['requirements'] as $type => $requirement)
            @foreach($requirements['requirements'][$type] as $extension => $enabled)
                <div style="padding: 8px 14px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #f1f5f9; font-size: 12px;">
                    <span style="color: #334155; font-weight: 500; text-transform: capitalize;">{{ $extension }}</span>
                    @if($enabled)
                        <span style="color: #059669; font-weight: 600;"><i class="fa-solid fa-check"></i></span>
                    @else
                        <span style="color: #dc2626; font-weight: 600;"><i class="fa-solid fa-xmark"></i></span>
                    @endif
                </div>
            @endforeach
        @endforeach
    </div>

    @if(!$isSupported)
        <div class="installer-alert danger">
            <span>কিছু প্রয়োজনীয় এক্সটেনশন বন্ধ রয়েছে। অনুগ্রহ করে সার্ভার কনফিগারেশন আপডেট করুন।</span>
        </div>
    @endif

    <div class="action-footer">
        <a href="{{ route('installer.index') }}" class="btn-prev">
            <i class="fa-solid fa-arrow-left" style="font-size: 10px;"></i>
            <span>পূর্ববর্তী</span>
        </a>

        @if($isSupported)
            <a href="{{ route('installer.permission') }}" class="btn-submit">
                <span>পরবর্তী</span>
                <i class="fa-solid fa-arrow-right" style="font-size: 11px;"></i>
            </a>
        @else
            <button disabled class="btn-submit">পরবর্তী</button>
        @endif
    </div>
@endsection
