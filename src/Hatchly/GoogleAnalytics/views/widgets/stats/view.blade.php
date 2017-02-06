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
                        @foreach($stats as $title => $stat)
                            <th>{{ $title }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($metrics as $title => $metric)
                    <tr>
                        <th>{{ $title }}</th>
                        @foreach($stats as $stat)
                            <td>{{ $stat[$metric] }}</td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>