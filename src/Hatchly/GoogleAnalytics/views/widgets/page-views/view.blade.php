<div class="col-sm-12">
    <div class="widget">
        <h3>Google Analytics Stats</h3>
        @if($error)
            <p><strong>Error:</strong> {{ $error }}</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>Today</th>
                        <th>Yesterday</th>
                        <th>This Week</th>
                        <th>This Month</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Sessions</th>
                        <td>{{ $analytics->today['sessions'] }}</td>
                        <td>{{ $analytics->yesterday['sessions'] }}</td>
                        <td>{{ $analytics->week['sessions'] }}</td>
                        <td>{{ $analytics->month['sessions'] }}</td>
                    </tr>
                    <tr>
                        <th>Users</th>
                        <td>{{ $analytics->today['users'] }}</td>
                        <td>{{ $analytics->yesterday['users'] }}</td>
                        <td>{{ $analytics->week['users'] }}</td>
                        <td>{{ $analytics->month['users'] }}</td>
                    </tr>
                    <tr>
                        <th>Page Views</th>
                        <td>{{ $analytics->today['pageviews'] }}</td>
                        <td>{{ $analytics->yesterday['pageviews'] }}</td>
                        <td>{{ $analytics->week['pageviews'] }}</td>
                        <td>{{ $analytics->month['pageviews'] }}</td>
                    </tr>
                    <tr>
                        <th>Bounce Rate</th>
                        <td>{{ $analytics->today['bounceRate'] }}%</td>
                        <td>{{ $analytics->yesterday['bounceRate'] }}%</td>
                        <td>{{ $analytics->week['bounceRate'] }}%</td>
                        <td>{{ $analytics->month['bounceRate'] }}%</td>
                    </tr>
                    <tr>
                        <th>Organic Searches</th>
                        <td>{{ $analytics->today['organicSearches'] }}</td>
                        <td>{{ $analytics->yesterday['organicSearches'] }}</td>
                        <td>{{ $analytics->week['organicSearches'] }}</td>
                        <td>{{ $analytics->month['organicSearches'] }}</td>
                    </tr>
                    <tr>
                        <th>Views Per Session</th>
                        <td>{{ $analytics->today['pageviewsPerSession'] }}</td>
                        <td>{{ $analytics->yesterday['pageviewsPerSession'] }}</td>
                        <td>{{ $analytics->week['pageviewsPerSession'] }}</td>
                        <td>{{ $analytics->month['pageviewsPerSession'] }}</td>
                    </tr>
                    <tr>
                        <th>Time On Page</th>
                        <td>{{ round($analytics->today['avgTimeOnPage']) }}s</td>
                        <td>{{ round($analytics->yesterday['avgTimeOnPage']) }}s</td>
                        <td>{{ round($analytics->week['avgTimeOnPage']) }}s</td>
                        <td>{{ round($analytics->month['avgTimeOnPage']) }}s</td>
                    </tr>
                    <tr>
                        <th>Page Load Time</th>
                        <td>{{ $analytics->today['avgPageLoadTime'] }}s</td>
                        <td>{{ $analytics->yesterday['avgPageLoadTime'] }}s</td>
                        <td>{{ $analytics->week['avgPageLoadTime'] }}s</td>
                        <td>{{ $analytics->month['avgPageLoadTime'] }}s</td>
                    </tr>
                    <tr>
                        <th>Session Duration</th>
                        <td>{{ round($analytics->today['avgSessionDuration']) }}s</td>
                        <td>{{ round($analytics->yesterday['avgSessionDuration']) }}s</td>
                        <td>{{ round($analytics->week['avgSessionDuration']) }}s</td>
                        <td>{{ round($analytics->month['avgSessionDuration']) }}s</td>
                    </tr>
                </tbody>
            </table>
        @endif
    </div>
</div>