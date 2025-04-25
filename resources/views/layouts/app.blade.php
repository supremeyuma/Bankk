<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Default Title')</title>  <!-- Title will be defined per page -->
    <!-- Add any common stylesheets here -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles') <!-- Add custom styles for specific pages -->
</head>
<body>

    <!-- Navigation or Sidebar -->
    <nav>
    <ul>
        <li><a href="{{ route('user.dashboard') }}">Home</a></li>
        <li><a href="{{ route('user.transfer.form') }}">Transfer</a></li>

        <!-- Logout Form as a link -->
        <li>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" style="background: none; border: none; color: inherit; text-decoration: underline; cursor: pointer;">
                    Logout
                </button>
            </form>
        </li>
    </ul>
    </nav>

    <!-- Main Content -->
    <div class="container">
        @yield('content') <!-- Content of specific page will be inserted here -->
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; {{ date('Y') }} My Bank</p>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts') <!-- Add custom scripts for specific pages -->
</body>
</html>
