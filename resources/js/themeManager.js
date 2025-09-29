import Alpine from 'alpinejs';

// Tema yönetimi için Alpine.js component'i
Alpine.data('themeManager', () => ({
    // State
    darkMode: false,

    // Sayfa yüklendiğinde localStorage'dan tema seçimini yükle
    init() {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            this.darkMode = savedTheme === 'dark';
        } else {
            // İlk kez gelen kullanıcı için sistem tercihini kullan
            this.darkMode = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        }
        this.applyTheme();
    },

    // Tema geçiş butonuna tıklandığında çağrılır
    toggleTheme() {
        this.darkMode = !this.darkMode;
        this.applyTheme();
        this.saveTheme();
    },

    // HTML elementine dark class'ını ekle/çıkar
    applyTheme() {
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    },

    // Kullanıcı seçimini localStorage'a kaydet
    saveTheme() {
        localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
    }
}));