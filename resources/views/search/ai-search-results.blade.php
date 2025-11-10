@extends('layouts.app')

@section('content')
<div class="page-container" style="padding-top: 90px; padding-left: 300px; padding-right: 30px; max-width: 900px;">

    <div class="search-results-header" style="margin-bottom: 30px;">
        <i class="fa-solid fa-atom gradient-icon" style="font-size: 2.5rem; margin-bottom: 10px; display: block;"></i>
        
        <h1 style="font-size: 2.5rem; font-weight: 700; color: #1d1d1f;">AI Search</h1>
        <p style="font-size: 1.2rem; color: #555;">
            Asking AI about: <strong style="color: #a65efc;">{{ $query }}</strong>
        </p>
    </div>

    <div class="ai-result-panel" style="background: #ffffff; border-radius: 14px; padding: 25px 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.07); line-height: 1.7;">
        
        <p style="font-size: 1rem; color: #333;">
            {!! nl2br(e($aiResult)) !!}
        </p>
        
    </div>

</div>
@endsection