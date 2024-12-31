<x-mail::message>

    Dear { { $employee->name }},

    Greetings for the day!!

    You have been granted access to the HRMS. Please use the credentials provided below to login to the system.
    Once logged in, we strongly recommend changing your password immediately for security purposes.


    - **URL**: [https://hrms.tashicell.com](https://hrms.tashicell.com)
    - **Username**: {{ $employee->username }}
    - **Password**: {{ $password }}


    Thanks,
    TIPL HRMS.


</x-mail::message>
