<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Dashboard' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --primary-light: #e0e7ff;
            --secondary: #ec4899;
            --secondary-light: #fce7f3;
            --success: #10b981;
            --success-light: #d1fae5;
            --warning: #f59e0b;
            --warning-light: #fef3c7;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            
            --bg-body: #f8fafc;
            --bg-card: #ffffff;
            --bg-sidebar: #ffffff;
            
            --text-main: #0f172a;
            --text-muted: #64748b;
            --text-light: #94a3b8;
            
            --border: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            
            --sidebar-width: 280px;
            --header-height: 80px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 40px 40px;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            padding: 32px 24px;
            display: flex;
            flex-direction: column;
            z-index: 50;
            transition: transform 0.3s ease;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text-main);
            margin-bottom: 48px;
            padding: 0 12px;
        }

        .brand-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), #4f46e5);
            color: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .brand-text {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #1e293b, #475569);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 16px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .nav-item:hover {
            background: var(--bg-body);
            color: var(--primary);
            transform: translateX(4px);
        }

        .nav-item.active {
            background: var(--primary-light);
            color: var(--primary);
        }

        .nav-item i { width: 20px; text-align: center; }

        .logout-btn {
            margin-top: auto;
            color: var(--danger);
            background: var(--danger-light);
        }
        
        .logout-btn:hover {
            background: #fee2e2;
            color: #dc2626;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 40px;
            max-width: 1600px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .page-title h1 {
            font-size: 32px;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -1px;
            margin-bottom: 8px;
        }

        .page-title p {
            color: var(--text-muted);
            font-size: 14px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 16px;
            background: var(--bg-card);
            padding: 8px 16px 8px 8px;
            border-radius: 50px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 700;
            font-size: 14px;
            color: var(--text-main);
        }

        .user-role {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            margin-bottom: 48px;
        }

        .stat-card {
            background: var(--bg-card);
            border-radius: 24px;
            padding: 32px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(226, 232, 240, 0.8);
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stat-info h3 {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 36px;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -1px;
            line-height: 1;
        }

        .stat-icon {
            width: 64px;
            height: 64px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            opacity: 0.9;
        }

        .stat-primary .stat-icon { background: var(--primary-light); color: var(--primary); }
        .stat-success .stat-icon { background: var(--success-light); color: var(--success); }
        .stat-warning .stat-icon { background: var(--warning-light); color: var(--warning); }

        .stat-card::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .stat-primary::before { background: var(--primary); }
        .stat-success::before { background: var(--success); }
        .stat-warning::before { background: var(--warning); }

        /* Content Sections */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .section-icon {
            width: 32px; height: 32px; background: var(--primary-light); color: var(--primary);
            border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 14px;
        }

        .data-card {
            background: var(--bg-card);
            border-radius: 24px;
            padding: 8px; /* Padding specifically for the table container */
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            margin-bottom: 40px;
            overflow: hidden;
        }

        /* Modern Table */
        .table-container {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        th {
            text-align: left;
            padding: 20px 24px;
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #f9fafb;
            border-bottom: 1px solid var(--border);
        }
        
        th:first-child { border-top-left-radius: 16px; border-bottom-left-radius: 16px; }
        th:last-child { border-top-right-radius: 16px; border-bottom-right-radius: 16px; }

        td {
            padding: 20px 24px;
            color: var(--text-main);
            font-size: 14px;
            font-weight: 500;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        tr:last-child td { border-bottom: none; }
        
        tr:hover td {
            background: #f8fafc;
        }
        
        .user-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .user-cell-avatar {
            width: 32px; height: 32px;
            border-radius: 10px;
            background: var(--primary-light);
            color: var(--primary);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 12px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 99px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-geo {
            background: var(--primary-light);
            color: var(--primary);
        }

        .badge-date {
            background: #f1f5f9;
            color: var(--text-muted);
        }

        .reply-preview {
            color: var(--text-muted);
            font-style: italic;
            background: #f8fafc;
            padding: 8px 12px;
            border-radius: 8px;
            border-left: 3px solid var(--border);
            display: inline-block;
            max-width: 400px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Forbidden Page */
        .forbidden-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
            padding: 20px;
        }

        .lock-icon {
            font-size: 80px;
            color: var(--danger);
            margin-bottom: 24px;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-10deg); }
            75% { transform: rotate(10deg); }
        }

        .btn-home {
            margin-top: 32px;
            padding: 16px 32px;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 16px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
            background: #4338ca;
        }

        @media (max-width: 1024px) {
            .sidebar { width: 80px; padding: 20px 10px; }
            .brand-text, .nav-item span { display: none; }
            .main-content { margin-left: 80px; }
            .nav-item { justify-content: center; padding: 14px; }
            .nav-item i { width: auto; font-size: 18px; }
        }
    </style>
</head>
<body>

@auth
    @if(Auth::user()->role === 'admin')
        <!-- Sidebar -->
        <aside class="sidebar">
            <a href="#" class="brand">
                <div class="brand-icon">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <span class="brand-text">Admin</span>
            </a>
            
            <nav class="nav-menu">
                <a href="#" class="nav-item active">
                    <i class="fa-solid fa-chart-pie"></i>
                    <span>Vue d'ensemble</span>
                </a>
                <a href="#questions" class="nav-item">
                    <i class="fa-solid fa-message"></i>
                    <span>Questions</span>
                </a>
                <a href="#responses" class="nav-item">
                    <i class="fa-solid fa-comments"></i>
                    <span>Réponses</span>
                </a>
                
                <a href="/logout" class="nav-item logout-btn">
                    <i class="fa-solid fa-power-off"></i>
                    <span>Déconnexion</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Bar -->
            <div class="top-bar">
                <div class="page-title">
                    <h1>Tableau de bord</h1>
                    <p>Bienvenue sur votre espace d'administration.</p>
                </div>
                
                <div class="user-profile">
                    <div class="avatar">
                        {{ substr(Auth::user()->fullname, 0, 1) }}
                    </div>
                    <div class="user-info">
                        <span class="user-name">{{ Auth::user()->fullname }}</span>
                        <span class="user-role">Super Admin</span>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card stat-primary">
                    <div class="stat-info">
                        <h3>Total Questions</h3>
                        <div class="stat-value">{{ count($questions) }}</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fa-solid fa-circle-question"></i>
                    </div>
                </div>

                <div class="stat-card stat-success">
                    <div class="stat-info">
                        <h3>Total Réponses</h3>
                        <div class="stat-value">{{ count($reponses) }}</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fa-solid fa-comment-dots"></i>
                    </div>
                </div>

                <div class="stat-card stat-warning">
                    <div class="stat-info">
                        <h3>Citoyens Inscrits</h3>
                        <div class="stat-value">{{ count($users) }}</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
            </div>

            <!-- Recent Questions Table -->
            <div id="questions" class="section-header">
                <div class="section-title">
                    <div class="section-icon"><i class="fa-solid fa-bolt"></i></div>
                    Dernières activités
                </div>
            </div>

            <div class="data-card">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 25%">Auteur</th>
                                <th style="width: 35%">Titre du sujet</th>
                                <th style="width: 20%">Localisation</th>
                                <th style="width: 20%">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($questions as $question)
                            <tr>
                                <td>
                                    <div class="user-cell">
                                        <div class="user-cell-avatar">
                                            {{ substr($question->user->fullname, 0, 1) }}
                                        </div>
                                        <div style="display:flex; flex-direction:column">
                                            <span style="font-weight:700">{{ $question->user->fullname }}</span>
                                            <span style="font-size:11px; color:var(--text-muted)">ID: #{{ $question->user->id }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span style="font-weight:600; color:var(--text-main)">{{ Str::limit($question->titre, 40) }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-geo">
                                        <i class="fa-solid fa-location-dot"></i> {{ $question->city }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-date">
                                        <i class="fa-regular fa-calendar"></i> {{ $question->created_at->format('d M Y') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Responses Table -->
            <div id="responses" class="section-header" style="margin-top: 60px">
                <div class="section-title">
                    <div class="section-icon" style="background:#fce7f3; color:#ec4899"><i class="fa-solid fa-shield-halved"></i></div>
                    Modération des réponses
                </div>
            </div>

            <div class="data-card">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 25%">Utilisateur</th>
                                <th style="width: 45%">Contenu du message</th>
                                <th style="width: 30%">Question liée</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($reponses as $reply)
                            <tr>
                                <td>
                                    <div class="user-cell">
                                        <div class="user-cell-avatar" style="background:var(--secondary-light); color:var(--secondary)">
                                            {{ substr($reply->user->fullname, 0, 1) }}
                                        </div>
                                        <span style="font-weight:700">{{ $reply->user->fullname }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="reply-preview">
                                        "{{ Str::limit($reply->content, 60) }}"
                                    </div>
                                </td>
                                <td>
                                    <a href="#" style="color:var(--primary); font-weight:600; text-decoration:none">
                                        <i class="fa-solid fa-link"></i> {{ Str::limit($reply->question->titre, 30) }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    @else
        <!-- Forbidden Access -->
        <div class="forbidden-wrap">
            <div class="lock-icon">
                <i class="fa-solid fa-lock"></i>
            </div>
            <h1 style="font-size:32px; font-weight:800; margin-bottom:12px">Accès Restreint</h1>
            <p style="color:var(--text-muted); max-width:400px; line-height:1.6">
                Cette zone est réservée aux administrateurs. Veuillez vous connecter avec un compte autorisé pour accéder au tableau de bord.
            </p>
            <a href="/home" class="btn-home">
                <i class="fa-solid fa-arrow-left"></i> Retour à l'accueil
            </a>
        </div>
    @endif
@endauth

</body>
</html>

