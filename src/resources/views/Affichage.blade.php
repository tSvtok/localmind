<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QConnect - Feed</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --primary-light: #e0e7ff;
            --primary-dark: #4338ca;
            --secondary: #ec4899;
            --secondary-light: #fce7f3;
            
            --bg-body: #f8fafc;
            --bg-card: #ffffff;
            --bg-sidebar: #ffffff;
            
            --text-main: #0f172a;
            --text-muted: #64748b;
            --text-light: #94a3b8;
            
            --border: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            
            --sidebar-width: 280px;
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

        /* Sidebar */
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

        .nav-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            color: var(--text-muted);
            background: transparent;
            border: none;
            width: 100%;
            cursor: pointer;
            border-radius: 16px;
            font-weight: 600;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.2s ease;
            text-align: left;
            margin-bottom: 8px;
        }

        .nav-btn:hover {
            background: var(--bg-body);
            color: var(--primary);
            transform: translateX(4px);
        }

        .nav-btn.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
        }

        .nav-btn.active:hover { transform: none; }

        .nav-footer {
            margin-top: auto;
            border-top: 1px solid var(--border);
            padding-top: 24px;
        }

        .user-mini {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: var(--bg-body);
            border-radius: 16px;
            margin-bottom: 12px;
        }

        .mini-avatar {
            width: 36px; height: 36px;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 14px;
        }

        .logout-link {
            display: flex; align-items: center; gap: 10px;
            color: var(--danger); text-decoration: none;
            font-size: 13px; font-weight: 600;
            padding: 8px 12px;
            border-radius: 12px;
            transition: background 0.2s;
        }
        .logout-link:hover { background: var(--danger-light); }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 40px;
            max-width: 1000px;
            margin-right: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            position: sticky;
            top: 20px;
            z-index: 40;
            backdrop-filter: blur(8px);
            padding: 10px 0;
        }

        .page-title {
            font-size: 28px;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -1px;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 14px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
        }

        .search-bar {
            width: 100%;
            max-width: 400px;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 14px 20px 14px 48px;
            border-radius: 16px;
            border: 1px solid var(--border);
            font-family: inherit;
            background: white;
            box-shadow: var(--shadow-sm);
            transition: all 0.2s;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        .search-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }

        /* Feed Card */
        .feed-card {
            background: var(--bg-card);
            border-radius: 24px;
            padding: 32px;
            margin-bottom: 24px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            animation: slideUp 0.4s ease forwards;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .author-box {
            display: flex; align-items: center; gap: 12px;
        }

        .author-avatar {
            width: 44px; height: 44px;
            background: linear-gradient(135deg, #e2e8f0, #f1f5f9);
            color: var(--text-muted);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
        }

        .author-info h4 { font-size: 15px; font-weight: 700; color: var(--text-main); }
        .author-info span { font-size: 12px; color: var(--text-muted); }

        .card-actions-top { display: flex; gap: 8px; }

        .btn-icon {
            width: 36px; height: 36px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: white;
            color: var(--text-muted);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-icon:hover { background: var(--bg-body); color: var(--text-main); }
        .btn-icon.delete:hover { background: #fee2e2; color: #ef4444; border-color: #fee2e2; }

        .badge-loc {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 12px;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 99px;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .card-title {
            font-size: 20px;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 12px;
            line-height: 1.4;
        }

        .card-body {
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .card-footer {
            border-top: 1px solid var(--border);
            padding-top: 20px;
            display: flex;
            gap: 16px;
        }

        .action-btn {
            display: flex; align-items: center; gap: 8px;
            padding: 8px 16px;
            border-radius: 12px;
            background: transparent;
            border: none;
            color: var(--text-muted);
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s;
        }

        .action-btn:hover { background: var(--bg-body); color: var(--text-main); }
        .action-btn.active { color: var(--primary); background: var(--primary-light); }

        /* Comments Section */
        .comments-section {
            background: var(--bg-body);
            border-radius: 16px;
            padding: 20px;
            margin-top: 20px;
        }

        .comment {
            display: flex; gap: 12px; margin-bottom: 16px;
        }

        .comment-avatar {
            width: 32px; height: 32px;
            background: white; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 700;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .comment-bubble {
            background: white;
            padding: 12px 16px;
            border-radius: 0 16px 16px 16px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            flex: 1;
        }

        .comment-author { font-size: 13px; font-weight: 700; margin-bottom: 4px; color: var(--text-main); }
        .comment-text { font-size: 14px; color: var(--text-muted); line-height: 1.5; }

        .reply-form {
            display: flex; gap: 10px;
            margin-top: 16px;
        }

        .reply-input {
            flex: 1;
            padding: 10px 16px;
            border: 1px solid var(--border);
            border-radius: 12px;
            font-family: inherit;
            font-size: 14px;
        }

        .reply-btn {
            width: 40px; height: 40px;
            background: var(--primary);
            color: white;
            border: none; border-radius: 12px;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: transform 0.1s;
        }
        .reply-btn:hover { transform: scale(1.05); }

        /* Modals */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 100;
            align-items: center;
            justify-content: center;
        }

        .modal-card {
            background: white;
            width: 90%;
            max-width: 550px;
            border-radius: 24px;
            padding: 32px;
            box-shadow: var(--shadow-lg);
            animation: modalPop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes modalPop {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        .modal-title { font-size: 24px; font-weight: 800; margin-bottom: 24px; color: var(--text-main); }

        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 13px; font-weight: 700; margin-bottom: 8px; color: var(--text-main); }
        .form-control {
            width: 100%; padding: 12px 16px;
            border: 1px solid var(--border);
            border-radius: 12px;
            font-family: inherit; font-size: 14px;
            background: var(--bg-body);
            transition: all 0.2s;
        }
        .form-control:focus { outline: none; border-color: var(--primary); background: white; }

        .modal-actions { display: flex; gap: 12px; margin-top: 32px; }
        
        .btn-cancel {
            flex: 1; padding: 12px; border-radius: 14px;
            border: 1px solid var(--border); background: white;
            font-weight: 700; cursor: pointer; color: var(--text-muted);
        }
        .btn-cancel:hover { background: var(--bg-body); }
        
        #favorisSection { display: none; }

        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main-content { margin-left: 0; padding: 20px; }
            .header { flex-direction: column; gap: 16px; align-items: stretch; position: static; }
            .search-bar { max-width: 100%; }
        }
    </style>
</head>
<body>

@auth
    <!-- Sidebar -->
    <aside class="sidebar">
        <a href="#" class="brand">
            <div class="brand-icon">
                <i class="fa-solid fa-street-view"></i>
            </div>
            <span class="brand-text">QConnect</span>
        </a>

        <nav style="flex: 1">
            <button onclick="showSection('discover')" class="nav-btn active" id="link-discover">
                <i class="fa-solid fa-house"></i> Découvrir
            </button>
            <button onclick="showSection('favoris')" class="nav-btn" id="link-favoris">
                <i class="fa-solid fa-bookmark"></i> Mes Favoris
            </button>
        </nav>

        <div class="nav-footer">
            <div class="user-mini">
                <div class="mini-avatar">
                    {{ substr(Auth::user()->fullname, 0, 1) }}
                </div>
                <div style="flex: 1; overflow: hidden;">
                    <div style="font-weight: 700; font-size: 14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ Auth::user()->fullname }}
                    </div>
                    <div style="font-size: 12px; color: var(--text-muted);">Membre</div>
                </div>
            </div>
            <a href="/logout" class="logout-link">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Déconnexion
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="header">
            <div>
                <h1 id="pageTitle" class="page-title">Découvrir</h1>
                <p style="color: var(--text-muted); margin-top: 4px;">Explorez les questions de votre communauté.</p>
            </div>
            <button onclick="openModal('postModal')" class="btn-primary">
                <i class="fa-solid fa-plus"></i>
                <span>Poser une question</span>
            </button>
        </header>

        <!-- Search -->
        <div style="margin-bottom: 32px;">
            <form method="GET" action="{{ route('affichage') }}" class="search-bar">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" name="search" placeholder="Rechercher une ville, un sujet..." class="search-input" value="{{ request('search') }}">
            </form>
        </div>

        <!-- Question Feed -->
        <div id="questionsFeed">
            @foreach($questions as $question)
                <article class="feed-card">
                    <!-- Card Header -->
                    <div class="card-header">
                        <div class="author-box">
                            <div class="author-avatar">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <div class="author-info">
                                <h4>{{ $question->user->fullname }}</h4>
                                <span>{{ $question->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        @if($question->user_id === Auth::id())
                            <div class="card-actions-top">
                                <button onclick="openEditModal('{{ $question->id }}', '{{ addslashes($question->titre) }}', '{{ addslashes($question->city) }}', '{{ addslashes($question->description) }}')" class="btn-icon">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form action="{{ route('question.delete')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="questionid" value="{{ $question->id }}">
                                    <button type="submit" class="btn-icon delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="badge-loc">
                        <i class="fa-solid fa-location-dot"></i> {{ $question->city }}
                    </div>
                    <h2 class="card-title">{{ $question->titre }}</h2>
                    <p class="card-body">{{ $question->description }}</p>

                    <!-- Actions -->
                    <div class="card-footer">
                        <button class="action-btn active">
                            <i class="fa-regular fa-comment-dots"></i>
                            {{ $question->reponses->count() }} Réponses
                        </button>
                        
                        <form method="POST" action="{{ route('favoris.store') }}">
                            @csrf
                            <input type="hidden" name="question_id" value="{{ $question->id }}">
                            <button type="submit" class="action-btn">
                                <i class="fa-regular fa-bookmark"></i> Sauvegarder
                            </button>
                        </form>
                    </div>

                    <!-- Comments -->
                    <div class="comments-section">
                        @foreach($question->reponses as $reply)
                            <div class="comment">
                                <div class="comment-avatar">{{ substr($reply->user->fullname, 0, 1) }}</div>
                                <div class="comment-bubble">
                                    <div class="comment-author">{{ $reply->user->fullname }}</div>
                                    <div class="comment-text">{{ $reply->content }}</div>
                                </div>
                            </div>
                        @endforeach

                        @if($question->user_id !== Auth::id())
                            <form action="{{ route('reponses.store') }}" method="POST" class="reply-form">
                                @csrf
                                <input type="hidden" name="question_id" value="{{ $question->id }}">
                                <input type="text" name="message" class="reply-input" placeholder="Écrire une réponse..." required>
                                <button type="submit" class="reply-btn">
                                    <i class="fa-solid fa-paper-plane"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>

        <!-- Favorites Section -->
        <div id="favorisSection">
            @foreach($favoris as $fav)
                <article class="feed-card" style="border-left: 4px solid var(--warning);">
                    <div class="card-header">
                        <h2 class="card-title" style="margin:0">{{ $fav->question->titre }}</h2>
                        <form action="{{ route('favoris.delete')}}" method="POST">
                            @csrf
                            <input type="hidden" name="favid" value="{{ $fav->id }}">
                            <button type="submit" class="btn-icon delete"><i class="fa-solid fa-xmark"></i></button>
                        </form>
                    </div>
                    <p class="card-body">{{ Str::limit($fav->question->description, 150) }}</p>
                    <div class="badge-loc">
                        <i class="fa-solid fa-location-dot"></i> {{ $fav->question->city }}
                    </div>
                </article>
            @endforeach
        </div>

    </main>

    <!-- Post Modal -->
    <div id="postModal" class="modal-overlay">
        <div class="modal-card">
            <h2 class="modal-title">Nouvelle Question</h2>
            <form action="{{ route('questions') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Sujet de votre question</label>
                    <input type="text" name="titre" class="form-control" placeholder="Ex: Recherche plombier..." required>
                </div>
                <div class="form-group">
                    <label class="form-label">Ville concernée</label>
                    <input type="text" name="city" class="form-control" placeholder="Ex: Paris 15" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Détails</label>
                    <textarea name="description" rows="4" class="form-control" placeholder="Décrivez votre demande en détail..." required></textarea>
                </div>
                <div class="modal-actions">
                    <button type="submit" class="btn-primary" style="flex: 2; justify-content: center">Publier</button>
                    <button type="button" onclick="closeModal('postModal')" class="btn-cancel">Annuler</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editForm" class="modal-overlay">
        <div class="modal-card">
            <h2 class="modal-title">Modifier la question</h2>
            <form action="{{ route('question.update') }}" method="POST">
                @csrf
                <input type="hidden" name="question_id" id="question_id">
                <div class="form-group">
                    <label class="form-label">Sujet</label>
                    <input type="text" name="titre" id="editTitreInput" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Ville</label>
                    <input type="text" name="city" id="editcity" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" id="editDescInput" rows="4" class="form-control" required></textarea>
                </div>
                <div class="modal-actions">
                    <button type="submit" class="btn-primary" style="flex: 2; justify-content: center">Enregistrer</button>
                    <button type="button" onclick="closeModal('editForm')" class="btn-cancel">Annuler</button>
                </div>
            </form>
        </div>
    </div>
@endauth

<script>
    function openModal(id) {
        document.getElementById(id).style.display = 'flex';
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    // Close modal when clicking outside
    window.onclick = function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            closeModal(e.target.id);
        }
    }

    function showSection(id) {
        const feed = document.getElementById('questionsFeed');
        const favs = document.getElementById('favorisSection');
        const title = document.getElementById('pageTitle');
        const btns = document.querySelectorAll('.nav-btn');

        btns.forEach(b => b.classList.remove('active'));

        if (id === 'favoris') {
            feed.style.display = 'none';
            favs.style.display = 'block';
            title.innerText = 'Mes Favoris';
            document.getElementById('link-favoris').classList.add('active');
        } else {
            feed.style.display = 'block';
            favs.style.display = 'none';
            title.innerText = 'Découvrir';
            document.getElementById('link-discover').classList.add('active');
        }
    }

    function openEditModal(id, titre, city, desc) {
        document.getElementById('question_id').value = id;
        document.getElementById('editTitreInput').value = titre;
        document.getElementById('editcity').value = city;
        document.getElementById('editDescInput').value = desc;
        openModal('editForm');
    }
</script>
</body>
</html>

