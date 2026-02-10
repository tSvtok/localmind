<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Bienvenue sur QConnect' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --bg-input: #f8fafc;
            --border: #e2e8f0;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #ffffff;
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
        }

        /* Split Layout */
        .split-container {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* Left Side - Visual */
        .visual-side {
            flex: 1;
            background: linear-gradient(135deg, #4f46e5 0%, #818cf8 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .visual-side::after {
            content: '';
            position: absolute;
            width: 150%;
            height: 150%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
            top: -25%;
            left: -25%;
        }

        .visual-content {
            position: relative;
            z-index: 10;
            max-width: 500px;
        }

        .visual-icon {
            font-size: 48px;
            margin-bottom: 32px;
            background: rgba(255,255,255,0.2);
            width: 80px; height: 80px;
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            backdrop-filter: blur(10px);
        }

        .visual-title {
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 24px;
            line-height: 1.1;
        }

        .visual-text {
            font-size: 18px;
            line-height: 1.6;
            opacity: 0.9;
        }

        /* Right Side - Form */
        .form-side {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: #ffffff;
        }

        .form-wrapper {
            width: 100%;
            max-width: 420px;
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-header { text-align: center; margin-bottom: 40px; }
        .form-title { font-size: 28px; font-weight: 800; margin-bottom: 8px; color: var(--text-main); }
        .form-subtitle { color: var(--text-muted); font-size: 15px; }

        .input-group { margin-bottom: 20px; }
        .label { display: block; font-size: 13px; font-weight: 700; margin-bottom: 8px; color: var(--text-main); }
        
        .input-field {
            width: 100%;
            padding: 14px 16px;
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: 12px;
            font-family: inherit; font-size: 14px;
            transition: all 0.2s;
        }

        .input-field:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .switch-auth {
            text-align: center;
            margin-top: 24px;
            font-size: 14px;
            color: var(--text-muted);
        }

        .switch-link {
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }
        
        .switch-link:hover { text-decoration: underline; }

        .hidden { display: none; }
        .fade-in { animation: fadeIn 0.4s ease forwards; }

        @media (max-width: 900px) {
            .split-container { flex-direction: column; }
            .visual-side { padding: 40px; min-height: 300px; flex: none; }
            .visual-title { font-size: 32px; }
            .form-side { flex: 1; padding: 40px 20px; }
        }
    </style>
</head>
<body>

<div class="split-container">
    <!-- Visual Side -->
    <div class="visual-side">
        <div class="visual-content">
            <div class="visual-icon">
                <i class="fa-solid fa-street-view"></i>
            </div>
            <h1 class="visual-title">Connectez-vous à votre quartier.</h1>
            <p class="visual-text">
                Rejoignez QConnect pour échanger avec vos voisins, poser des questions et découvrir ce qui se passe autour de vous. Une communauté bienveillante vous attend.
            </p>
        </div>
    </div>

    <!-- Form Side -->
    <div class="form-side">
        <div class="form-wrapper">
            
            <!-- Login Form -->
            <div id="login-form" class="fade-in">
                <div class="form-header">
                    <h2 class="form-title">Bon retour !</h2>
                    <p class="form-subtitle">Entrez vos identifiants pour accéder à votre compte.</p>
                </div>

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="input-group">
                        <label class="label">Adresse Email</label>
                        <input type="email" name="email" class="input-field" placeholder="exemple@email.com" required>
                    </div>
                    <div class="input-group">
                        <label class="label">Mot de passe</label>
                        <input type="password" name="password" class="input-field" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn-submit">Se connecter</button>
                </form>

                <p class="switch-auth">
                    Pas encore de compte ? <a class="switch-link" onclick="toggleAuth()">Créer un compte</a>
                </p>
            </div>

            <!-- Register Form -->
            <div id="register-form" class="hidden">
                <div class="form-header">
                    <h2 class="form-title">Créer un compte</h2>
                    <p class="form-subtitle">Rejoignez la communauté en quelques secondes.</p>
                </div>

                <form method="POST" action="{{ route('register.post') }}">
                    @csrf
                    <div class="input-group">
                        <label class="label">Nom complet</label>
                        <input type="text" name="full_name" class="input-field" placeholder="John Doe" required>
                    </div>
                    <div class="input-group">
                        <label class="label">Ville de résidence</label>
                        <input type="text" name="city" class="input-field" placeholder="Ex: Lyon" required>
                    </div>
                    <div class="input-group">
                        <label class="label">Adresse Email</label>
                        <input type="email" name="email" class="input-field" placeholder="exemple@email.com" required>
                    </div>
                    <div class="input-group">
                        <label class="label">Mot de passe</label>
                        <input type="password" name="password" class="input-field" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn-submit">S'inscrire</button>
                </form>

                <p class="switch-auth">
                    Déjà inscrit ? <a class="switch-link" onclick="toggleAuth()">Me connecter</a>
                </p>
            </div>

        </div>
    </div>
</div>

<script>
    function toggleAuth() {
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');

        if (loginForm.classList.contains('hidden')) {
            loginForm.classList.remove('hidden');
            loginForm.classList.add('fade-in');
            registerForm.classList.add('hidden');
            registerForm.classList.remove('fade-in');
        } else {
            loginForm.classList.add('hidden');
            loginForm.classList.remove('fade-in');
            registerForm.classList.remove('hidden');
            registerForm.classList.add('fade-in');
        }
    }
</script>

</body>
</html>

