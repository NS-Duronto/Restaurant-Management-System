@extends('installer.layouts.master')

@section('title', 'ইনস্টলেশন শুরু')

@section('container')
    <h2 class="step-title">Sohoj RMS ইনস্টলেশন</h2>

    <p style="font-size: 13px; color: #475569; line-height: 1.6; margin-bottom: 24px;">
        রেস্টুরেন্ট ম্যানেজমেন্ট সিস্টেম সফলভাবে সক্রিয় করতে কয়েক মিনিটের একটি সহজ সেটআপ প্রক্রিয়া সম্পন্ন করুন।
    </p>

    <div class="installer-alert info" style="margin-bottom: 24px;">
        <i class="fa-solid fa-circle-info" style="color: #64748b; margin-top: 1px;"></i>
        <span>শুরু করার আগে আপনার MySQL ডাটাবেজের নাম, ইউজারনেম ও পাসওয়ার্ড প্রস্তুত রাখুন।</span>
    </div>

    <div class="action-footer" style="justify-content: flex-end;">
        <a href="{{ route('installer.requirement') }}" class="btn-submit">
            <span>শুরু করুন</span>
            <i class="fa-solid fa-arrow-right" style="font-size: 11px;"></i>
        </a>
    </div>
@endsection
