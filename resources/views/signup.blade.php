<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register</title>
    @include('layout.head')
    <style>
        .strength-bar { height: 8px; background-color: #e5e7eb; border-radius: 4px; margin-top: 6px; }
        .strength-bar span { display: block; height: 100%; border-radius: 4px; }
        .strength-weak { width: 33%; background-color: #ef4444; }
        .strength-medium { width: 66%; background-color: #f97316; }
        .strength-strong { width: 100%; background-color: #22c55e; }
        .password-requirements { font-size: 0.8rem; color: #6b7280; }
        .password-container { position: relative; }
        .toggle-password {
            position: absolute; top: 50%; right: 12px;
            transform: translateY(-50%); cursor: pointer; color: #9ca3af;
        }
    </style>
</head>

<body class="font-sans">
    <div class="min-h-screen w-full bg-gradient-to-br from-orange-600 to-orange-300 flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8 space-y-6 my-8">
            <div class="text-center space-y-1">
                <h1 class="text-4xl font-extrabold text-orange-500">Funnev</h1>
                <h2 class="text-xl font-bold text-gray-800">Register</h2>
                <p class="text-gray-500 text-sm">Buat akun organizer</p>
            </div>

            <form method="post" action="{{ route('register') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="name" class="block text-xs font-bold text-gray-500 uppercase mb-1">Organizer Name</label>
                    <input class="w-full rounded-lg border-gray-300 shadow-sm p-2.5 border focus:ring-2 focus:ring-orange-500"
                        type="text" name="name" required />
                </div>
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
                    <div class="strength-bar" id="strength-bar"><span></span></div>
                    <div class="password-requirements mt-1" id="password-requirements">
                        Minimal 8 karakter, mengandung huruf besar, angka, dan karakter spesial.
                    </div>
                </div>
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-gray-500 uppercase mb-1">Konfirmasi Password</label>
                    <div class="password-container">
                        <input id="password_confirmation"
                            class="w-full rounded-lg border-gray-300 shadow-sm p-2.5 pr-12 border focus:ring-2 focus:ring-orange-500"
                            type="password" name="password_confirmation" required />
                        <i id="toggle-password-confirmation" class="fas fa-eye toggle-password"></i>
                    </div>
                </div>

                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500">Sudah punya akun?</span>
                    <a href="{{ route('signin') }}" class="text-orange-600 font-semibold hover:underline">Sign In</a>
                </div>

                <x-button type="submit" variant="primary" icon="user-plus"
                    class="w-full justify-center">Sign Up</x-button>
            </form>
        </div>
    </div>

    @include('sweetalert::alert')
    <script>
        document.getElementById('toggle-password').addEventListener('click', function() {
            const f = document.getElementById('password');
            f.type = f.type === 'password' ? 'text' : 'password';
            this.classList.toggle('fa-eye-slash');
        });
        document.getElementById('toggle-password-confirmation').addEventListener('click', function() {
            const f = document.getElementById('password_confirmation');
            f.type = f.type === 'password' ? 'text' : 'password';
            this.classList.toggle('fa-eye-slash');
        });

        const passwordInput = document.getElementById('password');
        const strengthBar = document.getElementById('strength-bar');
        const requirements = document.getElementById('password-requirements');
        passwordInput.addEventListener('input', () => {
            const value = passwordInput.value;
            let strength = 0;
            if (value.length >= 8) strength++;
            if (/[A-Z]/.test(value)) strength++;
            if (/[0-9]/.test(value)) strength++;
            if (/[\W_]/.test(value)) strength++;
            const strengthClasses = ['strength-weak', 'strength-medium', 'strength-strong'];
            strengthBar.querySelector('span').className = strengthClasses[strength - 1] || 'strength-weak';
            requirements.style.color = strength < 4 ? '#ef4444' : '#22c55e';
        });
    </script>
</body>

</html>
