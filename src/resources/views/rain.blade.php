<html>
    <body>
        <h1>気象予測</h1>
        <h2>{{ $data['title'] }}</h2>
        <h2>------------------------------------</h2>
        @foreach ($data['weather_list_data'] as $row)
            <p>時点: {{ $row['min'] }}分後</p>
            <p>降水量: {{ $row['rain_fall'] }}</p>
            <p>------------------------------</p>
        @endforeach
    </body>
</html>
