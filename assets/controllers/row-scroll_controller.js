import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = [ "left", "right", "item", "row" ]

    connect()
    {
        this.scrollingAnimation = false;
        let items = this.itemTargets;
        this.firstItem = items.shift();
        this.lastItem = items.pop();
        this.width = 250;
        if (this.firstItem && this.lastItem && this.lastItem.offsetTop !== this.firstItem.offsetTop) {
            this.arrowVisibility();
        }
    }

    canScrollRight()
    {
        return (parseInt(this.rowTarget.style.marginLeft) || 0) !== 0;
    }

    canScrollLeft()
    {
        return this.lastItem.offsetTop !== this.firstItem.offsetTop;
    }

    arrowVisibility()
    {
        if (this.canScrollLeft()) {
            this.leftTarget.classList.remove('hide');
        } else {
            this.leftTarget.classList.add('hide');
        }

        if (this.canScrollRight()) {
            this.rightTarget.classList.remove('hide');
        } else {
            this.rightTarget.classList.add('hide');
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

    wheelScroll(event)
    {
        if (event.deltaY > 0) {
            if (this.canScrollRight()) {
                this.animate(150, this.rowTarget, this.width);
            }

            return;
        }

        if (event.deltaY < 0) {
            if (this.canScrollLeft()) {
                this.animate(150, this.rowTarget, -1 * this.width);
            }
        }
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
