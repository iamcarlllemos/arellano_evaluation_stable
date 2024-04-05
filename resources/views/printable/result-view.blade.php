<!DOCTYPE html>
<html>
<head>
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

* {
    font-family: 'Poppins' !important;
}

ul {
    font-size: 12px;
}

table {
  font-family: 'Poppins' !important;
  border-collapse: collapse;
  width: 100%;
  font-size: 12px;
  border: 1px solid #66768c;
}

table td, table th {
  border: 1px solid #66768c;
  padding: 8px;
}

table th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #fff;
  color: #1e429e;
}
</style>
</head>
<body>
<div style="text-align: center; margin-bottom: 30px">
    <h2>Evaluation Result</h2>
</div>
<div>
    <div style="font-size: 14px; font-weight: bold; margin-bottom: 5px;">
        Faculty Information
    </div>
    <ul style="list-style:none; list-style-type: none; padding: 0; margin: 0;">
        <li>Name: Carl Llemos</li>
        <li>Subject: BSIT</li>
        <li>Academic Stage: 1st Year & 1st Semester</li>
    </ul>
</div>

<table style="margin-top: 10px;">
    <tr>
        <th style="width: 100%; background-color: #fff; text-transform: uppercase;">
            Total Responses:
            <span>
                {{$view['evaluation_result']['total_responses']}}
            </span>
        </th>
        <th style="width: 20px; text-align:center; color: #2449a6; background-color: #75a8fa">4</th>
        <th style="width: 20px; text-align:center; color: #703a13; background-color: #e0a009">3</th>
        <th style="width: 20px; text-align:center; color: #9c3916; background-color: #ff8b4d">2</th>
        <th style="width: 20px; text-align:center; color: #9c1c1c; background-color: #fa7f7f">1</th>
        @if ($display['wm'])
            <th style="width: 20px; text-align:center;">Weighted Mean</th>
        @endif
        @if ($display['sqm'])
            <th style="width: 20px; text-align:center; color: #5d25b8; background-color: #a689fa">Mean Squared</th>
        @endif
        @if ($display['std'])
            <th style="width: 20px; text-align:center;">Standard Deviation</th>
        @endif
        @if ($display['itrprtn'])
            <th style="width: 20px; text-align:center;">Interpretation</th>
        @endif
    </tr>
    @forelse ($view['evaluation_result']['stats'] as $questionnaire)
        <tr>
            <td style="width: 100%; background-color: #ebf5ff;">
                {{ucwords($questionnaire['criteria_name'])}}
            </td>
            <td style="width: 20px; text-align:center; background-color: #75a8fa"></td>
            <td style="width: 20px; text-align:center; background-color: #e0a009"></td>
            <td style="width: 20px; text-align:center; background-color: #ff8b4d"></td>
            <td style="width: 20px; text-align:center; background-color: #fa7f7f"></td>

            @if ($display['wm'])
                <td style="width: 20px; text-align:center;"></td>
            @endif
            @if ($display['sqm'])
                <td style="width: 20px; text-align:center; background-color: #a689fa"></td>
            @endif
            @if ($display['std'])
                <td style="width: 20px; text-align:center;"></td>
            @endif
            @if ($display['itrprtn'])
                <td style="width: 20px; text-align:center;"></td>
            @endif
        </tr>
        @forelse ($questionnaire['items'] as $items)
            <tr>
                <td style="width: 100%;">
                    {{$items['name']}}
                </td>
                <td style="width: 20px; text-align:center; color: #2449a6; background-color: #75a8fa">
                    {{number_format($items['tally'][4], 2)}}
                </td>
                <td style="width: 20px; text-align:center; color: #703a13; background-color: #e0a009">
                    {{number_format($items['tally'][3], 2)}}
                </td>
                <td style="width: 20px; text-align:center; color: #9c3916; background-color: #ff8b4d">
                    {{number_format($items['tally'][2], 2)}}
                </td>
                <td style="width: 20px; text-align:center; color: #9c1c1c; background-color: #fa7f7f">
                    {{number_format($items['tally'][1], 2)}}
                </td>

                @if ($display['wm'])
                    <td style="width: 20px; text-align:center;">
                        {{number_format($items['weighted_mean'], 2)}}
                    </td>
                @endif
                @if ($display['sqm'])
                    <td style="width: 20px; text-align:center; color: #5d25b8; background-color: #a689fa">
                        {{number_format($items['mean_squared'], 2)}}
                    </td>
                @endif
                @if ($display['std'])
                    <td style="width: 20px; text-align:center;">
                        {{number_format($items['standard_deviation'], 2)}}
                    </td>
                @endif
                @if ($display['itrprtn'])
                    <td style="width: 20px; text-align:center;">
                        {!!to_interpret($items['interpretation'])!!}
                    </td>
                @endif
            </tr>
        @empty
        <tr>
            <td style="width: 100%;">
                No responses yet
            </td>
            <td style="width: 20px; text-align:center; color: #2449a6; background-color: #75a8fa">
                {{number_format(0, 2)}}
            </td>
            <td style="width: 20px; text-align:center; color: #703a13; background-color: #e0a009">
                {{number_format(0, 2)}}
            </td>
            <td style="width: 20px; text-align:center; color: #9c3916; background-color: #ff8b4d">
                {{number_format(0, 2)}}
            </td>
            <td style="width: 20px; text-align:center; color: #9c1c1c; background-color: #fa7f7f">
                {{number_format(0, 2)}}
            </td>
            @if ($display['wm'])
                <td style="width: 20px; text-align:center;">
                    {{number_format(0, 2)}}
                </td>
            @endif
            @if ($display['sqm'])
                <td style="width: 20px; text-align:center; color: #5d25b8; background-color: #a689fa">
                    {{number_format(0, 2)}}
                </td>
            @endif
            @if ($display['std'])
                <td style="width: 20px; text-align:center;">
                    {{number_format(0, 2)}}
                </td>
            @endif
            @if ($display['itrprtn'])
                <td style="width: 20px; text-align:center;">
                    {{ 'No responses yet.' }}
                </td>
            @endif
        </tr>
        @endforelse
    @empty
        <div>
            Currently no survery questionnaires added.
        </div>
    @endforelse
    @php
        $trueDisplay = 0;
        foreach($display as $value) {
            if($value) {
                $trueDisplay++;
            }
        }
    @endphp
    @if ($trueDisplay > 1)
        <tr>
            <td colspan="5" style="text-align: center">
                AVERAGES
            </td>
            @if ($display['wm'])
                <td style="width: 20px; text-align:center;">
                    {{number_format($view['evaluation_result']['averages']['mean'], 2)}}
                </td>
            @endif
            @if ($display['sqm'])
                <td style="width: 20px; text-align:center; color: #5d25b8; background-color: #a689fa">
                    {{number_format($view['evaluation_result']['averages']['squared_mean'], 2)}}
                </td>
            @endif
            @if ($display['std'])
                <td style="width: 20px; text-align:center;">
                    {{number_format($view['evaluation_result']['averages']['standard_deviation'], 2)}}
                </td>
            @endif
            @if ($display['itrprtn'])
                <td style="width: 20px; text-align:center;">
                    {!!to_interpret($view['evaluation_result']['averages']['descriptive_interpretation'])!!}
                </td>
            @endif
        </tr>
    @endif
</table>
<table style="margin-top: 10px">
    <tr>
        @if ($view['evaluation_result']['total_responses'] > 0)
            @if ($trueDisplay >= 1 && $trueDisplay <= 2)
                <td style="width: 50%; text-align: center; text-transform: uppercase;">
                    Descriptive Interpretation
                </td>
                <td >
                    The collective weighted mean registers at
                    <span style="font-style: bold;">{{number_format($view['evaluation_result']['averages']['mean'], 2)}}</span>,
                    accompanied by a mean squared figure of <span style="font-style: bold;">{{number_format($view['evaluation_result']['averages']['squared_mean'], 2)}}</span>
                    and a standard deviation resting at <span style="font-style: bold;">{{number_format($view['evaluation_result']['averages']['standard_deviation'], 2)}}</span>.
                    In essence, the overall interpretation tends towards
                    <span style="text-decoration: underline; font-style: bold;">{!!strip_tags(to_interpret($view['evaluation_result']['averages']['descriptive_interpretation'])) !!}</span>
                </td>
            @elseif ($trueDisplay >= 3)
                <td  style="width: 50%; text-align: center; text-transform: uppercase;">
                    Descriptive Interpretation
                </td>
                <td>
                    The collective weighted mean registers at
                    <span style="font-style: bold;">{{number_format($view['evaluation_result']['averages']['mean'], 2)}}</span>,
                    accompanied by a mean squared figure of <span style="font-style: bold;">{{number_format($view['evaluation_result']['averages']['squared_mean'], 2)}}</span>
                    and a standard deviation resting at <span style="font-style: bold;">{{number_format($view['evaluation_result']['averages']['standard_deviation'], 2)}}</span>.
                    In essence, the overall interpretation tends towards
                    <span style="text-decoration: underline; font-style: bold;">{!!strip_tags(to_interpret($view['evaluation_result']['averages']['descriptive_interpretation'])) !!}</span>
                </td>
            @endif
        @else
            <td colspan="3" style="text-align: center">
                Descriptive Interpretation
            </td>
            <td colspan="12">
                No responses yet.
            </td>
        @endif
    </tr>
</table>
</body>
</html>


