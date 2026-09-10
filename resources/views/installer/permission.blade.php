@extends('installer.layouts.master')

@section('title', 'ফোল্ডার পারমিশন')

@section('container')
    @php
        $hasErrors = isset($permissions['errors']);
    @endphp

    <h2 class="step-title">ফোল্ডার পারমিশন</h2>

    <div style="border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; margin-bottom: 20px;">
        @foreach($permissions['permissions'] as $permission)
            <div style="padding: 11px 14px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #f1f5f9; font-size: 13px;">
                <div>
                    <span style="color: #0f172a; font-weight: 600; font-family: monospace;">{{ $permission['folder'] }}</span>
                    <span style="color: #64748b; font-size: 11px; margin-left: 6px;">({{ $permission['permission'] }})</span>
                </div>
                <div>
                    @if($permission['isSet'])
                        <span style="color: #059669; font-size: 11px; font-weight: 600; background: #ecfdf5; border: 1px solid #a7f3d0; padding: 2px 8px; border-radius: 4px;">
                            Writable
                        </span>
                    @else
                        <span style="color: #dc2626; font-size: 11px; font-weight: 600; background: #fef2f2; border: 1px solid #fecaca; padding: 2px 8px; border-radius: 4px;">
                            Not Writable
                        </span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @if($hasErrors)
        <div class="installer-alert danger">
            <span>ফোল্ডারে পারমিশন দিতে টার্মিনালে রান করুন: <code>chmod -R 775 storage bootstrap/cache</code></span>
        </div>
    @endif

    <div class="action-footer">
        <a href="{{ route('installer.requirement') }}" class="btn-prev">
            <i class="fa-solid fa-arrow-left" style="font-size: 10px;"></i>
            <span>পূর্ববর্তী</span>
        </a>

        @if(!$hasErrors)
            <a href="{{ route('installer.site') }}" class="btn-submit">
                <span>পরবর্তী</span>
                <i class="fa-solid fa-arrow-right" style="font-size: 11px;"></i>
            </a>
        @else
            <button disabled class="btn-submit">পরবর্তী</button>
        @endif
    </div>
@endsection
