<table>
    <tr>
        <th colspan="9" style="background-color: #dcfce7;font-weight: bolder; font-size: 16pt;">{{ __('timer.billed_hours') }}</th>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <th style="font-weight: bolder;font-size:13pt;">{{ __('timer.user') }}</th>
            <th style="font-weight: bolder;font-size:13pt;">{{ __('timer.client') }}</th>
            <th style="font-weight: bolder;font-size:13pt;">{{ __('timer.project') }}</th>
            <th style="font-weight: bolder;font-size:13pt;">{{ __('timer.description') }}</th>
            <th style="font-weight: bolder;font-size:13pt;">{{ __('timer.ticket_link') }}</th>
            <th style="font-weight: bolder;font-size:13pt;">{{ __('timer.start') }}</th>
            <th style="font-weight: bolder;font-size:13pt;">{{ __('timer.end') }}</th>
            <th style="font-weight: bolder;font-size:13pt;">{{ __('timer.duration_hours') }}</th>
            <th style="font-weight: bolder;font-size:13pt;">{{ __('timer.duration_decimal') }}</th>
        </tr>
    </thead>
    <tbody>
    @php $count = 0; @endphp
    @foreach($time_entries as $time_entry)
        @if($time_entry->billable)
            {{-- TODO Keep finding timeEntry this way so we can use it through api too --}}
            @php $count = $count + \App\Models\TimeEntry::find($time_entry->id)->calculateDurationInDecimal(); @endphp
            <tr>
                <td style="font-size:11pt;">{{ $time_entry->user->name }}</td>
                <td style="font-size:11pt;">{{ $time_entry->client->name }}</td>
                <td style="font-size:11pt;">{{ $time_entry->project->title }}</td>
                <td style="font-size:11pt;">{{ $time_entry->description }}</td>
                <td style="font-size:11pt;">{{ $time_entry->link }}</td>
                <td style="font-size:11pt;">{{ \App\Models\TimeEntry::find($time_entry->id)->start->format('d.m.Y H:i') }}</td>
                <td style="font-size:11pt;">{{ \App\Models\TimeEntry::find($time_entry->id)->end->format('d.m.Y H:i') }}</td>
                <td style="font-size:11pt;">{{ \App\Models\TimeEntry::find($time_entry->id)->calculateDurationInHours() }}</td>
                <td style="font-size:11pt;">{{ number_format(\App\Models\TimeEntry::find($time_entry->id)->calculateDurationInDecimal(), 2, ".", "'") }}</td>
            </tr>
        @endif
    @endforeach
    <tr></tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="font-weight: bolder;font-size:13pt;">{{ __('timer.total') }}</td>
        <td style="font-weight: bolder;font-size:13pt;">{{ number_format($count, 2, ".", "'") }}</td>
    </tr>
    </tbody>
</table>

<table>
    <tr>
        <th colspan="9" style="background-color: #fee2e2;font-weight: bolder; font-size: 16pt;">{{ __('timer.non_billed_hours') }}</th>
    </tr>
</table>

<table>
    <thead>
    <tr>
        <th style="font-weight: bolder;font-size:13pt;">{{ __('timer.user') }}</th>
        <th style="font-weight: bolder;font-size:13pt;">{{ __('timer.client') }}</th>
        <th style="font-weight: bolder;font-size:13pt;">{{ __('timer.project') }}</th>
        <th style="font-weight: bolder;font-size:13pt;">{{ __('timer.description') }}</th>
        <th style="font-weight: bolder;font-size:13pt;">{{ __('timer.ticket_link') }}</th>
        <th style="font-weight: bolder;font-size:13pt;">{{ __('timer.start') }}</th>
        <th style="font-weight: bolder;font-size:13pt;">{{ __('timer.end') }}</th>
        <th style="font-weight: bolder;font-size:13pt;">{{ __('timer.duration_hours') }}</th>
        <th style="font-weight: bolder;font-size:13pt;">{{ __('timer.duration_decimal') }}</th>
    </tr>
    </thead>
    <tbody>
    @php $free_count = 0; @endphp
    @foreach($time_entries as $time_entry)
        @if(!$time_entry->billable)
            @php $free_count = $free_count + \App\Models\TimeEntry::find($time_entry->id)->calculateDurationInDecimal(); @endphp
            <tr>
                <td style="font-size:11pt;">{{ $time_entry->user->name }}</td>
                <td style="font-size:11pt;">{{ $time_entry->client->name }}</td>
                <td style="font-size:11pt;">{{ $time_entry->project->title }}</td>
                <td style="font-size:11pt;">{{ $time_entry->description }}</td>
                <td style="font-size:11pt;">{{ $time_entry->link }}</td>
                <td style="font-size:11pt;">{{ \App\Models\TimeEntry::find($time_entry->id)->start->format('d.m.Y H:i') }}</td>
                <td style="font-size:11pt;">{{ \App\Models\TimeEntry::find($time_entry->id)->end->format('d.m.Y H:i') }}</td>
                <td style="font-size:11pt;">{{ \App\Models\TimeEntry::find($time_entry->id)->calculateDurationInHours() }}</td>
                <td style="font-size:11pt;">{{ number_format(\App\Models\TimeEntry::find($time_entry->id)->calculateDurationInDecimal(), 2, ".", "'") }}</td>
            </tr>
        @endif
    @endforeach
    <tr></tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="font-weight: bolder;font-size:13pt;">{{ __('timer.total') }}</td>
        <td style="font-weight: bolder;font-size:13pt;">{{ number_format($free_count, 2, ".", "'") }}</td>
    </tr>
    </tbody>
</table>
