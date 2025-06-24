# D-64 Theme

**Modulares WordPress-Theme für D-64 - Zentrum für Digitalen Fortschritt**

Ein anpassbares Theme basierend auf _tw mit Tailwind CSS, das Template Parts für flexible Inhaltsgestaltung nutzt.

## Schnellstart

### Installation
1. Diesen Ordner nach `wp-content/themes` in deine lokale Entwicklungsumgebung verschieben
2. `npm install && npm run dev` in diesem Ordner ausführen  
3. Theme in WordPress aktivieren
4. ACF Pro und Contact Form 7 Plugins installieren

### Entwicklung
5. `npm run watch` ausführen
6. [Tailwind Utility Classes](https://tailwindcss.com/docs/utility-first) nach Belieben hinzufügen
7. Template Parts in `template-parts/` anpassen

### Bereitstellung
8. `npm run bundle` ausführen
9. Die resultierende ZIP-Datei über "Theme hochladen" in WordPress installieren

## Installation & Setup

### Voraussetzungen
- **WordPress 5.8+**
- **PHP 7.4+** 
- **Node.js 16+** & npm (für Build-Prozess und Tailwind CSS)
- **Advanced Custom Fields Pro** Plugin
- **The Events Calendar** Plugin (für Veranstaltungen)

### Erste Schritte
```bash
# 1. Theme installieren
cd wp-content/themes/
git clone [repository] d64

# 2. Dependencies installieren
cd d64
npm install

# 3. Development starten
npm run dev

# 4. Production Build
npm run build
```


### Template-Struktur
**Template Parts** für maximale Flexibilität:
- `template-parts/components/` - Wiederverwendbare Komponenten (Hero, Jobs, Timeline, etc.)
- `template-parts/content/` - Haupt-Content Templates (Single, Page, etc.)
- `tribe/events/v2/` - **Veranstaltungstool Templates** (The Events Calendar Plugin)
- `tribe-events/modules/` - **Event-Module Templates** (erweiterte Event-Anpassungen)
- Jede Datei enthält eine **Dokumentation** zur Funktionsweise

**Event-Templates bearbeiten:**
- Haupt-Event-Templates: `theme/tribe/events/v2/`
- Event-Module & Widgets: `theme/tribe-events/modules/`
- Diese überschreiben die Plugin-Standard-Templates für individuelle Anpassungen

### Styling
- **Tailwind CSS** für Utility-First Styling
- Custom D-64 Farbschema (`d64blue-900`, `d64gray-500`, etc.)
- Responsive Mobile-First Design
- Typography-Plugin für Content-Bereiche


## Dokumentation

### Grundlagen
Jede Template-Datei enthält:
- **Funktionsbeschreibung** - Was macht die Komponente
- **ACF-Feldstruktur** - Welche Custom Fields benötigt werden
- **Usage Notes** - Wie die Komponente konfiguriert wird
- **Dependencies** - Erforderliche Plugins und Template Parts


### Erweiterungen
- **Multi-Language Ready** - i18n-Unterstützung
- **Accessibility** - ARIA-Labels und Screen Reader Support  
- **Performance** - Optimierte Queries und Conditional Loading
- **Security** - XSS-Prevention und Input-Validation

## Anpassungen

### ACF-Felder konfigurieren
Alle benötigten ACF-Feldgruppen sind in den Template Part Headers dokumentiert.

### Neue Template Parts hinzufügen
1. Neue Datei in `template-parts/components/` erstellen
2. Header-Dokumentation nach Vorlage hinzufügen
3. In entsprechende Page-Templates einbinden

### Styling erweitern
1. Tailwind Classes in Templates nutzen
2. Custom CSS in `style.css` für spezielle Anforderungen
3. Neue Farben über Tailwind-Konfiguration# d64_custom
