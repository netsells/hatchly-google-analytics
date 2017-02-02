<div class="col-sm-6">
    <div class="widget">
        <h3>Google Analytics Page Views</h3>
        @if($error)
            <p><strong>Error:</strong> {{ $error }}</p>
        @else
            <ul>
                <li><strong>Today:</strong> {{ $analytics->today }}</li>
                <li><strong>Yesterday:</strong> {{ $analytics->yesterday }}</li>
                <li><strong>This Week:</strong> {{ $analytics->week }}</li>
                <li><strong>This Month:</strong> {{ $analytics->month }}</li>
            </ul>
        @endif
    </div>
</div>