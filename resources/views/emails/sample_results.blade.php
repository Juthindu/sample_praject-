@component('mail::message')
# Water Sample Test Results – {{ $sample->laboratory_no }}

Dear {{ $sample->consumer->full_name }},

Your water sample submitted on **{{ \Carbon\Carbon::parse($sample->date)->format('Y-m-d') }}** (Laboratory No. **{{ $sample->laboratory_no }}**) has been **fully processed**.  
Please find the **official PDF report** attached to this email.

If you have any questions or require clarification on any test result, simply reply to this email and our team will assist you.

Warm regards,  
**Name**  
**Position** 
National Water Supply & Drainage Board – Central Laboratory  
Tel: +94 11 263 8999 / +94 11 261 1589  
https://www.waterboard.lk/  
National Water Supply and Drainage Board,  
Galle Road, Ratmalana,  
Sri Lanka.

@slot('subcopy')
If you did not request this report or believe you received it in error, please contact us immediately.
@endslot
@endcomponent
