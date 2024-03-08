import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = [ "slide" ]
    static values = {index: {type: Number, default: 2}}

    // initialize() {
    //     console.log(this.indexValue)
    //     console.log(typeof this.indexValue)
    //     this.showCurrentSlide();
    // }

    indexValueChanged() {
        this.showCurrentSlide();
    }

    next() {
        this.indexValue = (this.indexValue + 1) % this.slideTargets.length
    }

    previous() {
        this.indexValue = (this.indexValue - 1 + this.slideTargets.length) % this.slideTargets.length
    }

    showCurrentSlide() {
        this.slideTargets.forEach((element, index) => {
            element.hidden = index !== this.indexValue
        })
    }
}
