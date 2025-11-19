<x-app-layout>
<div class="page-container" style="padding-top: 90px; padding-left: 300px; padding-right: 30px;">

    <div class="search-results-header" style="margin-bottom: 30px;">
        <h1 style="font-size: 2.5rem; font-weight: 700; color: #1d1d1f;">Search Results</h1>
        <p style="font-size: 1.2rem; color: #555;">
            Showing results for: <strong style="color: #007aff;">{{ $query }}</strong>
        </p>
    </div>

    <h2 style="font-size: 1.5rem; font-weight: 600; color: #1d1d1f; border-bottom: 1px solid #e5e5e5; padding-bottom: 10px; margin-top: 30px;">Products</h2>
    <div class="results-list" style="margin-top: 20px;">
        @forelse ($products as $product)
            <div class="result-item" style="background: #fff; border-radius: 12px; padding: 15px 20px; margin-bottom: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                <a href="{{-- route('products.show', $product->id) --}}" style="text-decoration: none; color: #007aff; font-weight: 600;">
                    {{ $product->name }}
                </a>
                <p style="color: #666; margin: 5px 0 0 0;">{{ $product->description ?? 'No description available.' }}</p>
            </div>
        @empty
            <p style="color: #888;">No products found matching your search.</p>
        @endforelse
    </div>

    <h2 style="font-size: 1.5rem; font-weight: 600; color: #1d1d1f; border-bottom: 1px solid #e5e5e5; padding-bottom: 10px; margin-top: 30px;">Patients</h2>
    <div class="results-list" style="margin-top: 20px;">
        @forelse ($patients as $patient)
            <div class="result-item" style="background: #fff; border-radius: 12px; padding: 15px 20px; margin-bottom: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                <a href="{{-- route('patients.show', $patient->id) --}}" style="text-decoration: none; color: #007aff; font-weight: 600;">
                    {{ $patient->name }}
                </a>
                <p style="color: #666; margin: 5px 0 0 0;">HN: {{ $patient->hn_number ?? 'N/A' }}</p>
            </div>
        @empty
            <p style="color: #888;">No patients found matching your search.</p>
        @endforelse
    </div>

</div>
</x-app-layout>