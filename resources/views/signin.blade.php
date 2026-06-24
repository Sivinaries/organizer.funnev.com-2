<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    @include('layout.head')

    <style>
        .password-container { position: relative; }
        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #9ca3af;
        }
    </style>
</head>

<body class="font-sans">
    <div class="min-h-screen w-full bg-gradient-to-br from-orange-600 to-orange-300 flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8 space-y-6">
            <div class="text-center space-y-1">
                <h1 class="text-4xl font-extrabold text-orange-500">Funnev</h1>
                <h2 class="text-xl font-bold text-gray-800">Login</h2>
                <p class="text-gray-500 text-sm">Masuk untuk melanjutkan</p>
            </div>

            <form method="post" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-bold text-gray-500 uppercase mb-1">Email</label>
                    <input class="w-full rounded-lg border-gray-300 shadow-sm p-2.5 border focus:ring-2 focus:ring-orange-500"
                        type="email" name="email" required />
                </div>
                <div>
                    <label for="password" class="block text-xs font-bold text-gray-500 uppercase mb-1">Password</label>
                    <div class="password-container">
                        <input id="password"
                            class="w-full rounded-lg border-gray-300 shadow-sm p-2.5 pr-12 border focus:ring-2 focus:ring-orange-500"
                            type="password" name="password" required />
                        <i id="toggle-password" class="fas fa-eye toggle-password"></i>
                    </div>
                </div>

                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500">Belum punya akun?</span>
                    <a href="{{ route('signup') }}" class="text-orange-600 font-semibold hover:underline">Register</a>
                </div>

                <x-button type="submit" variant="primary" icon="right-to-bracket"
                    class="w-full justify-center">Sign In</x-button>
            </form>
        </div>
    </div>

    @include('sweetalert::alert')
    <script>
        document.getElementById('toggle-password').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            passwordField.type = passwordField.type === 'password' ? 'text' : 'password';
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>
