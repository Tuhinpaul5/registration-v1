<!DOCTYPE html>
<html>
<head>
    <title>User PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .container { padding: 20px; }
        .profile-img { width: 150px; height: auto; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>User Registration Details</h2>
        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Location:</strong> {{ $user->location }}</p>

        @if($imageBase64)
            <p><strong>Profile Picture:</strong></p>
            <img src="{{ $imageBase64 }}" class="profile-img">
        @endif
    </div>
</body>
</html>
