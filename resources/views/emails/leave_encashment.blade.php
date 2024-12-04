<!DOCTYPE html>
<html>
<head>
    <title>Leave Encashment Notification</title>
</head>
<body>
    <p>Dear {{ $employee->name }},</p>

    <p>You are eligible for leave encashment. Please apply to encash your leave balance.</p>

    <p>Thank you,</p>
    <p>{{ config('HRMS') }}</p>
</body>
</html>
