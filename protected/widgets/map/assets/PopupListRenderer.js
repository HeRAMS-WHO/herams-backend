
class PopupListRenderer {


    constructor(popup, translations)
    {
        this.popup = popup;
        this.translations = translations;
    }


    renderFailed()
    {
        let content = document.createElement('div');
        content.classList.add('project-list');
        let title = document.createElement('h1');
        title.textContent = this.translations["loading-failed"];
        content.appendChild(title);
        content.innerHTML += '<h2>' + this.translations["loading-error"] + '</h2>';
        this.popup.setContent(content);
        this.popup.update();
    }

    static createProject(name, id)
    {
        let project = document.createElement('div');
        project.classList.add('project-item');
        project.setAttribute('data-id' , id);
        project.textContent = name;
        return project;
    }

    async render(markers)
    {
        this.markers = markers;
        if (!this.markers || this.markers.length === 0) {
            return this.renderFailed();
        }

        let content = document.createElement('div');
        content.classList.add('project-list');
        for (let marker of this.markers) {
            content.appendChild(PopupListRenderer.createProject(marker.feature.properties.title,marker.feature.properties.id));
        }

        this.popup.setContent(content);
        this.popup.update();
    }
}





