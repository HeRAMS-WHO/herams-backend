
class PopupListRenderer {

    
    constructor(markers, popup)
    {
        this.markers = markers;
        this.popup = popup;
    }


    renderFailed()
    {
        let content = document.createElement('div');
        content.classList.add('project-list');
        let title = document.createElement('h1');
        title.textContent = 'Loading failed';
        content.appendChild(title);
        content.innerHTML += '<h2>Loading Error</h2>';
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

    async render()
    {
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





