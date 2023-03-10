import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = [ "origin", "hover" ]

    connect() {
        if (this.targets.has('hover')) {
            this.element.setAttribute('data-action', 'mouseover->hover#mouseOver mouseout->hover#mouseOut');
        }
    }

    mouseOver()
    {
        this.originTarget.classList.add('hide');
        this.hoverTarget.classList.remove('hide');
    }

    mouseOut()
    {
        this.originTarget.classList.remove('hide');
        this.hoverTarget.classList.add('hide');
    }
}
