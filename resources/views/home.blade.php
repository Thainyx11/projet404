<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atelier 404 - Repair Café Étudiant</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-900">Atelier 404</h1>
                @auth
                    <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-800">
                        Tableau de bord →
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800">
                        Connexion →
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-indigo-700 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold mb-4">Repair Café Étudiant</h2>
            <p class="text-xl mb-8">Faites réparer gratuitement vos équipements informatiques par nos étudiants techniciens</p>
            <a href="#contact" class="bg-white text-indigo-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 inline-block">
                Faire une demande
            </a>
        </div>
    </section>

    <!-- Services -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h3 class="text-3xl font-bold text-center mb-12">Nos Services</h3>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="text-4xl mb-4">💻</div>
                    <h4 class="text-xl font-semibold mb-2">Ordinateurs</h4>
                    <p class="text-gray-600">Diagnostic et réparation de PC fixes et portables</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="text-4xl mb-4">📱</div>
                    <h4 class="text-xl font-semibold mb-2">Smartphones & Tablettes</h4>
                    <p class="text-gray-600">Réparation logicielle et conseils techniques</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="text-4xl mb-4">🖨️</div>
                    <h4 class="text-xl font-semibold mb-2">Périphériques</h4>
                    <p class="text-gray-600">Imprimantes, claviers, souris et accessoires</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Horaires -->
    <section class="bg-gray-100 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h3 class="text-3xl font-bold text-center mb-8">Horaires d'ouverture</h3>
            <div class="bg-white p-8 rounded-lg shadow-md max-w-2xl mx-auto">
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="font-semibold">Mardi :</span>
                        <span>14h00 - 18h00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-semibold">Jeudi :</span>
                        <span>14h00 - 18h00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-semibold">Vendredi :</span>
                        <span>10h00 - 16h00</span>
                    </div>
                </div>
                <div class="mt-6 pt-6 border-t">
                    <p class="text-center text-gray-600">
                        📍 Local B-404, Bâtiment B, Campus Technique<br>
                        📧 contact@atelier404.be
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Formulaire de contact -->
    <section id="contact" class="py-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <h3 class="text-3xl font-bold text-center mb-8">Faire une demande</h3>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('contact.submit') }}" method="POST" class="bg-white p-8 rounded-lg shadow-md">
                @csrf

                <div class="mb-4">
                    <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">Nom complet *</label>
                    <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('nom') border-red-500 @enderror">
                    @error('nom')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="telephone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone *</label>
                    <input type="tel" name="telephone" id="telephone" value="{{ old('telephone') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('telephone') border-red-500 @enderror">
                    @error('telephone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="type_appareil" class="block text-sm font-medium text-gray-700 mb-2">Type d'appareil *</label>
                    <select name="type_appareil" id="type_appareil" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('type_appareil') border-red-500 @enderror">
                        <option value="">Sélectionnez...</option>
                        <option value="Ordinateur portable" {{ old('type_appareil') == 'Ordinateur portable' ? 'selected' : '' }}>Ordinateur portable</option>
                        <option value="PC fixe" {{ old('type_appareil') == 'PC fixe' ? 'selected' : '' }}>PC fixe</option>
                        <option value="Smartphone" {{ old('type_appareil') == 'Smartphone' ? 'selected' : '' }}>Smartphone</option>
                        <option value="Tablette" {{ old('type_appareil') == 'Tablette' ? 'selected' : '' }}>Tablette</option>
                        <option value="Imprimante" {{ old('type_appareil') == 'Imprimante' ? 'selected' : '' }}>Imprimante</option>
                        <option value="Autre" {{ old('type_appareil') == 'Autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('type_appareil')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description du problème *</label>
                    <textarea name="description" id="description" rows="5" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">Décrivez le plus précisément possible le problème (minimum 10 caractères)</p>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                    Envoyer ma demande
                </button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>&copy; 2025 Atelier 404 - Tous droits réservés</p>
            <p class="text-sm text-gray-400 mt-2">Service gratuit géré par les étudiants en informatique</p>
        </div>
    </footer>
</body>
</html>
