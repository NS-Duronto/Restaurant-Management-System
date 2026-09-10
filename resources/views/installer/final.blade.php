@extends('installer.layouts.master')

@section('title', 'ইনস্টলেশন সমাপ্ত')

@section('container')
    <div style="text-align: center; margin-bottom: 24px;">
        <div style="width: 48px; height: 48px; border-radius: 50%; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; font-size: 22px; margin: 0 auto 12px;">
            <i class="fa-solid fa-check"></i>
        </div>
        <h2 class="step-title" style="margin-bottom: 4px;">ইনস্টলেশন সফল হয়েছে</h2>
        <p style="font-size: 13px; color: #64748b;">Sohoj RMS সফলভাবে সেটআপ ও ডাটাবেজ প্রস্তুত হয়েছে।</p>
    </div>

    <!-- Credentials -->
    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px 16px; margin-bottom: 24px;">
        <div style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; margin-bottom: 10px;">
            অ্যাডমিন লগইন তথ্য:
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 6px;">
            <span style="color: #64748b;">ইমেইল:</span>
            <strong style="color: #0f172a; font-family: monospace;">admin@foodking.com</strong>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 13px;">
            <span style="color: #64748b;">পাসওয়ার্ড:</span>
            <strong style="color: #0f172a; font-family: monospace;">123456</strong>
        </div>
    </div>

    <div>
        <a href="{{ route('installer.finalStore') }}" class="btn-submit" style="width: 100%; justify-content: center; height: 42px; font-size: 14px;">
            <span>সিস্টেমে প্রবেশ করুন</span>
            <i class="fa-solid fa-arrow-right" style="font-size: 11px;"></i>
        </a>
    </div>
@endsection
