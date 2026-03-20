<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS CodeVerse | Point of sales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .calendar th,
.calendar td {
    width: 14.28%;
    text-align: center;
    vertical-align: middle;
    border: 1px solid black;
    padding: 0;
}

.calendar thead th {
    background-color: #d6e9c6; /* light green */
    height: 40px;
}

.week-row td {
    background-color: #00B0F0; /* light blue */
    color: white;
    font-weight: bold;
    height: 40px; /* Shorter height for week rows */
}

.data-row td {
    height: 80px; /* Taller height for data rows */
}

    </style>
</head>
<body>

<div class="container mt-4">
    <div class="row mb-2">
        <div class="col-6">
            <strong>Name: <i>{{ $sale_person->name }}</i></strong><br>
            <strong>Position:<i>{{ $sale_person->position }}</i></strong>
        </div>
        <div class="col-6 text-end">
            <strong>Principal:<i>{{ $sale_person->principal }}</i></strong><br>
            <strong>Month:@php
                $monthName = date('F', mktime(0, 0, 0, $month, 1));
                echo $monthName . '- ' . $year;
            @endphp</strong> <br>

        </div>
    </div>
@php
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $waysByDay = [];
    foreach ($ways as $way) {
        $day = date('j', strtotime($way->date)); // Get day number without leading zero
        $waysByDay[$day][] = $way;
    }
@endphp

<table class="table table-bordered calendar text-center">
    <thead>
        <tr>
            <th>Mon</th><th>Tues</th><th>Wed</th><th>Thurs</th><th>Fri</th><th>Sat</th><th>Sun</th>
        </tr>
    </thead>
    <tbody>
        {{-- @php
            $startDay = date('N', strtotime("$year-$month-01")); // 1 (Mon) - 7 (Sun)
            $day = 1 - ($startDay - 1);
        @endphp

        @while ($day <= $daysInMonth)
            <tr class="week-row">
                @for ($i = 1; $i <= 7; $i++)
                    <td class="day-cell" data-day="{{ $day }}">
                        @if ($day > 0 && $day <= $daysInMonth)
                            {{ $day }}
                        @endif
                    </td>
                    @php $day++; @endphp
                @endfor
            </tr>
            <tr class="data-row">
                @php $dataDay = $day - 8; @endphp
                @for ($i = 1; $i <= 7; $i++)
                    <td>
                        @if ($dataDay > 0 && $dataDay <= $daysInMonth)
                            @if (!empty($waysByDay[$dataDay]))
                                @foreach ($waysByDay[$dataDay] as $way)
                                    <div><p style="font-size: 14px;" class="fw-bold">{{ $way->name ?? '-' }}<br>{{ $way->phno ?? '-' }}</p></div>
                                @endforeach
                            @endif
                        @endif
                        @php $dataDay++; @endphp
                    </td>
                @endfor
            </tr>
        @endwhile --}}
        @php
    $startDay = date('N', strtotime("$year-$month-01")); // 1 (Mon) - 7 (Sun)
    $day = 1 - ($startDay - 1);
@endphp

@while ($day <= $daysInMonth)
    <tr class="week-row">
        @php $weekDays = []; @endphp
        @for ($i = 1; $i <= 7; $i++)
            <td class="day-cell" data-day="{{ $day }}">
                @if ($day > 0 && $day <= $daysInMonth)
                    {{ $day }}
                    @php $weekDays[] = $day; @endphp
                @else
                    @php $weekDays[] = null; @endphp
                @endif
            </td>
            @php $day++; @endphp
        @endfor
    </tr>
    <tr class="data-row">
        @foreach ($weekDays as $dataDay)
            <td>
                @if ($dataDay && !empty($waysByDay[$dataDay]))
                    @foreach ($waysByDay[$dataDay] as $way)
                        <div>
                            <p style="font-size: 14px;" class="fw-bold">
                                {{ $way->name ?? '-' }}<br>{{ $way->phno ?? '-' }}
                            </p>
                        </div>
                    @endforeach
                @endif
            </td>
        @endforeach
    </tr>
@endwhile

    </tbody>
</table>

    {{-- <table class="table table-bordered calendar text-center">
        <thead>
            <tr>
                <th>Mon</th>
                <th>Tues</th>
                <th>Wed</th>
                <th>Thurs</th>
                <th>Fri</th>
                <th>Sat</th>
                <th>Sun</th>
            </tr>
        </thead>
        <tbody>
            <tr class="week-row">
                <td>30</td><td>1</td><td>2</td><td>3</td><td>4</td><td>5</td><td>6</td>
            </tr>
            <tr class="data-row">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr class="week-row">
                <td>7</td><td>8</td><td>9</td><td>10</td><td>11</td><td>12</td><td>13</td>
            </tr>
            <tr class="data-row">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr class="week-row">
                <td>14</td><td>15</td><td>16</td><td>17</td><td>18</td><td>19</td><td>20</td>
            </tr>
             <tr class="data-row">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr class="week-row">
                <td>21</td><td>22</td><td>23</td><td>24</td><td>25</td><td>26</td><td>27</td>
            </tr>
             <tr class="data-row">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr class="week-row">
                <td class="day-cell" data-day="28">28</td>
                <td class="day-cell" data-day="29">29</td>
                <td class="day-cell" data-day="30">30</td>
                <td class="day-cell" data-day="31">31</td>
                <td class="day-cell" data-day="32"></td>
                <td class="day-cell" data-day="33"></td>
                <td class="day-cell" data-day="34"></td>
            </tr>

             <tr class="data-row">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table> --}}
</div>
 <script src="{{ asset('plugins/jquery/jquery.min.js ') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script>
$(document).ready(function() {
    let daysInMonth = @json(cal_days_in_month(CAL_GREGORIAN, $month, $year));
    $('.day-cell').each(function() {
        let day = parseInt($(this).attr('data-day'));
        if (isNaN(day) || day > daysInMonth) {
            $(this).html(''); // Clear the content
        }
    });
});</script>

</body>
</html>
