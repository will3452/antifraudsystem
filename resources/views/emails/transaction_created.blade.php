@component('mail::message')
# Transaction Details

{{$transaction->created_at}}, Your  P {{$transaction->amount}}
Remittance with Reference No. **{{$transaction->reference_number}}** was successfully send!
{{$transaction->receiver_first_name}} {{$transaction->receiver_last_name}} with Valid ID may claim at any accredited
{{nova_get_setting('system_name') ?? 'Anti fraud App'}}.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
