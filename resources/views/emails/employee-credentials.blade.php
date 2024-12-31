<x-mail::message>

    Dear {{ $employee->name }}, <br />

    Greetings for the day!!


    <p>
        You have been granted access to the HRMS. Please use the credentials provided below to login to the system.
        Once logged in, we strongly recommend changing your password immediately for security purposes.
    </p>


    <p> URL: https://hrms.tashicell.com</p>
    <p> Username: {{ $employee->username }}</p>
    <p> Password: {{ $password }}</p>


    Thanks,
    TIPL HRMS.

</x-mail::message>
