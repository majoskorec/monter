import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = [ "folder", "content", "toggleChildren", "children" ]
    static values = {
        pageContentUrl: String
    }

    connect()
    {
        this.opened = false;
    }

    expandContent()
    {
        if (this.opened) {
            this.opened = false;
            this.setFolderClass('bi-folder');
            this.contentTarget.classList.add('hide');

        } else {
            this.opened = true;
            this.setFolderClass('bi-folder-fill');
            this.contentTarget.classList.remove('hide');
            this.contentTarget.setAttribute('src', this.pageContentUrlValue);
        }
    }

    folderMouseOver()
    {
        if (this.opened) {
            this.setFolderClass('bi-folder-x');
        } else {
            this.setFolderClass('bi-folder2-open');
        }
    }

    folderMouseOut()
    {
        if (this.opened) {
            this.setFolderClass('bi-folder-fill');
        } else {
            this.setFolderClass('bi-folder');
        }
    }

    setFolderClass(className)
    {
        const classNames = ['bi-folder', 'bi-folder2-open', 'bi-folder-fill', 'bi-folder-x'];
        classNames.forEach(item => {
            if (className !== item) {
                this.folderTarget.classList.remove(item);
            }
        });
        this.folderTarget.classList.add(className);
    }

    toggleChildren()
    {
        if (this.toggleChildrenTarget.classList.contains('bi-arrows-expand')) {
            this.toggleChildrenTarget.classList.add('bi-arrows-collapse');
            this.toggleChildrenTarget.classList.remove('bi-arrows-expand');
            this.childrenTarget.classList.add('hide');
            this.toggleChildrenTarget.classList.add('text-white');
        } else {
            this.toggleChildrenTarget.classList.remove('bi-arrows-collapse');
            this.toggleChildrenTarget.classList.add('bi-arrows-expand');
            this.childrenTarget.classList.remove('hide');
            this.toggleChildrenTarget.classList.remove('text-white');
        }
    }
}
