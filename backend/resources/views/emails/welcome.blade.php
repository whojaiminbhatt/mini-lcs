<!DOCTYPE html>
<html>
<head>
    <title>Welcome to LCS</title>
</head>
<body>
    <h2>Welcome, {{ $user->name }}!</h2>
    <p>Your account has been successfully created in the Loan Collection System.</p>
    <p>Your Role: <b>{{ ucfirst(str_replace('_', ' ', $user->role)) }}</b></p>
    <p>You can now log in using your email address ({{ $user->email }}).</p>
</body>
</html>
