<x-mail::message>

Dear {{ $approver }}, <br/>
Greetings for the day!! <br/>

{{ $emailContent }} <br/><br/>

Kindly Approve/Reject from HRMS, please click the link below:<br/>
@component('mail::button', ['url' => url('/')])
    Visit The Site
@endcomponent
Sincerely, <br/>
Tashi InfoComm Private Limited.

</x-mail::message>