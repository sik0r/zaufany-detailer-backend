import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['name', 'region', 'city', 'preview'];
    connect() {
        if (!this.hasNameTarget || !this.hasRegionTarget || !this.hasCityTarget || !this.hasPreviewTarget) {
            console.error('Brak wymaganych targetów dla kontrolera workshop_url_preview');
            return;
        }

        // Inicjalizacja nasłuchiwania zdarzeń
        this.nameTarget.addEventListener('input', this.updatePreview.bind(this));
        this.regionTarget.addEventListener('change', this.updatePreview.bind(this));
        this.cityTarget.addEventListener('change', this.updatePreview.bind(this));

        // Try initial update if all fields have values
        this.updatePreview();
    }

    disconnect() {
        if (this.hasNameTarget) this.nameTarget.removeEventListener('input', this.updatePreview);
        if (this.hasRegionTarget) this.regionTarget.removeEventListener('change', this.updatePreview);
        if (this.hasCityTarget) this.cityTarget.removeEventListener('change', this.updatePreview);
    }

    async updatePreview() {
        // Pobierz wartości z pól formularza
        const name = this.nameTarget.value.trim();
        const regionId = this.regionTarget.value;
        const cityId = this.cityTarget.value;

        // Jeśli któreś z pól jest puste, nie aktualizuj podglądu
        if (!name || !regionId || !cityId) {
            this.previewTarget.innerHTML = '<span class="text-warning">Uzupełnij nazwę, województwo i miasto, aby zobaczyć podgląd URL</span>';
            return;
        }

        try {
            // Pobierz slug z API
            const response = await fetch(`/api/workshop-url-preview?name=${encodeURIComponent(name)}&region=${encodeURIComponent(regionId)}&city=${encodeURIComponent(cityId)}`);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.url) {
                this.previewTarget.innerHTML = `<span class="text-success">Podgląd URL: <strong>${data.url}</strong></span>`;
            } else {
                this.previewTarget.innerHTML = `<span class="text-warning">${data.message || 'Nie udało się wygenerować URL'}</span>`;
            }
        } catch (error) {
            console.error('Błąd podczas generowania podglądu URL:', error);
            this.previewTarget.innerHTML = '<span class="text-error">Wystąpił błąd podczas generowania podglądu URL</span>';
        }
    }
}
