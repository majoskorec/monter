import { Controller } from '@hotwired/stimulus';

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * row-scroll_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
    static targets = [ "left", "right", "item", "row" ]

    connect() {
        this.scrollingAnimation = false;
        let items = this.itemTargets;
        this.firstItem = items.shift();
        this.lastItem = items.pop();
        this.width = 250;
        if (this.firstItem && this.lastItem && this.lastItem.offsetTop !== this.firstItem.offsetTop) {
            this.arrowVisibility();
        }
    }

    arrowVisibility()
    {
        if (this.lastItem.offsetTop === this.firstItem.offsetTop) {
            this.leftTarget.classList.add('hide');
        } else {
            this.leftTarget.classList.remove('hide');
        }

        if ((parseInt(this.rowTarget.style.marginLeft) || 0) === 0) {
            this.rightTarget.classList.add('hide');
        } else {
            this.rightTarget.classList.remove('hide');
        }
    }

    leftScrolling()
    {
        this.animate(150, this.rowTarget, -1 * this.width);
    }

    rightScrolling()
    {
        this.animate(150, this.rowTarget, this.width);
    }

    animate(duration, element, length)
    {
        if (this.scrollingAnimation) {
            return;
        }
        this.scrollingAnimation = true;
        let startTimestamp = null;
        let startValue = parseInt(element.style.marginLeft) || 0

        const step = (timestamp) => {
            if (!startTimestamp) {
                startTimestamp = timestamp;
            }

            const elapsed = timestamp - startTimestamp;
            const progress = Math.min(elapsed / duration, 1)

            element.style.marginLeft = (startValue + progress * length) + 'px';

            if (progress < 1) {
                window.requestAnimationFrame(step)
            }
            if (progress === 1) {
                this.scrollingAnimation = false;
                this.arrowVisibility();
            }
        }

        window.requestAnimationFrame(step)
    }
}
