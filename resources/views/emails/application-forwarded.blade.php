<x-mail::message>

Dear {{ $approvingEmpName }}, <br/>
Greetings for the day!! <br/>

{{ $reqEmpName }} {{ $emailContent }} <br/><br/>

To review and approve, please click the link below:<br/>
@component('mail::button', ['url' => url('/')])
    Visit The Site
@endcomponent
Thanks, <br/>
TIPL HRMS.

</x-mail::message>
